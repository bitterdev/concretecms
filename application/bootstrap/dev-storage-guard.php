<?php
/* Author: Fabian Bitter (fabian@bitter.de) */

/**
 * Refuses to run the development installation against a storage location that is
 * not local.
 *
 * A storage location keeps its whole configuration, bucket and keys included, as
 * a serialized object in FileStorageLocations. It therefore travels with a
 * database dump. On 2026-08-20 this installation was rebuilt from a production
 * dump and silently inherited the production S3 bucket, so every upload made
 * while clicking through the development site landed in the live bucket next to
 * the real applicant photos. Nothing in the installation said a word about it.
 *
 * This guard is the check the start script cannot be: a dump imported into a
 * running installation takes effect on the very next request, long before anyone
 * restarts the server. So the check has to sit on the request itself.
 *
 * It is armed by the installation directory alone, taken from this file's own
 * location rather than from DIR_BASE, which the console runtime derives from the
 * script it was started with. The path below exists on this machine and on no
 * server, so the guard can never fire on a live site even if this file were to
 * travel there by accident.
 *
 * It fails open. Anything unexpected, a missing table during a fresh install
 * above all, leaves the request alone. Only a location that is positively read
 * and positively remote stops it. A dead development installation is a great
 * deal cheaper than rubbish in the live bucket, so stopping is the right answer
 * once that is established.
 */

(static function ($app) {
    $installation = '/Volumes/OWC Express/Projekte/Softwarenetwicklung/Repositories/GitHub/concretecms';
    $storageRoot = '/Volumes/OWC Express/Projekte/Softwarenetwicklung/Repositories/GitHub/concretecms-storage';

    if (rtrim(str_replace(DIRECTORY_SEPARATOR, '/', dirname(__DIR__, 2)), '/') !== $installation) {
        return;
    }

    $localClass = 'Concrete\\Core\\File\\StorageLocation\\Configuration\\LocalConfiguration';
    $defaultClass = 'Concrete\\Core\\File\\StorageLocation\\Configuration\\DefaultConfiguration';

    try {
        $connection = $app->make('database')->connection();
        $rows = method_exists($connection, 'fetchAllAssociative')
            ? $connection->fetchAllAssociative('SELECT fslID, fslName, fslConfiguration FROM FileStorageLocations')
            : $connection->fetchAll('SELECT fslID, fslName, fslConfiguration FROM FileStorageLocations');
    } catch (\Throwable $e) {
        return;
    }

    $refused = [];

    foreach ($rows as $row) {
        $configuration = (string) $row['fslConfiguration'];

        if (!preg_match('/^O:\d+:"([^"]+)":/', $configuration, $match)) {
            continue;
        }

        $class = $match[1];

        if ($class === $defaultClass) {
            continue;
        }

        if ($class !== $localClass) {
            // An S3 bucket looks exactly like this, and so does every other
            // remote driver a package may bring along.
            $refused[$row['fslName']] = 'kein lokaler Speicher (' . $class . ')';
            continue;
        }

        // The declared length is read rather than scanning to the next quote, so
        // a path holding a quote or a backslash is still cut correctly.
        $marker = 's:7:"' . "\0" . '*' . "\0" . 'path";s:';
        $start = strpos($configuration, $marker);
        if ($start === false) {
            $refused[$row['fslName']] = 'lokaler Speicher ohne Pfad';
            continue;
        }
        $cursor = $start + strlen($marker);
        $end = strpos($configuration, ':', $cursor);
        $path = substr($configuration, $end + 2, (int) substr($configuration, $cursor, $end - $cursor));

        $real = realpath($path);
        $root = realpath($storageRoot);
        if ($real !== false && $root !== false && ($real === $root || strpos($real, $root . '/') === 0)) {
            continue;
        }

        $refused[$row['fslName']] = 'liegt ausserhalb von ' . $storageRoot;
    }

    // A probe so the guard can be shown to run inside the real boot without
    // anything being broken on purpose to see it. It says who it is and then
    // gets out of the way; it never changes the verdict.
    if (getenv('JUNO_CONCRETE_SPEICHER_PROBE')) {
        fwrite(STDERR, sprintf(
            'PROBE dev-storage-guard: %d Ablageorte gelesen, %d gesperrt' . PHP_EOL,
            count($rows), count($refused)
        ));
    }

    if (!$refused) {
        return;
    }

    $lines = ['Die Entwicklungsinstallation ist angehalten.', ''];
    $lines[] = 'Sie zeigt auf einen Speicher, der ihr nicht gehoert, und darf deshalb';
    $lines[] = 'nichts schreiben. Betroffen:';
    $lines[] = '';
    foreach ($refused as $name => $reason) {
        $lines[] = '  - ' . $name . ': ' . $reason;
    }
    $lines[] = '';
    $lines[] = 'Das passiert, wenn ein Abzug der Produktion eingespielt wurde:';
    $lines[] = 'FileStorageLocations reist im Dump mit, Bucket und Schluessel inklusive.';
    $lines[] = '';
    $lines[] = 'Zurechtruecken:';
    $lines[] = '  juno/bin/juno-concrete-speicher.py pruefen';
    $lines[] = '  juno/bin/juno-concrete-speicher.py sichern --ja';

    $message = implode(PHP_EOL, $lines) . PHP_EOL;

    if (PHP_SAPI !== 'cli' && !headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/plain; charset=utf-8');
    }

    echo $message;
    exit(1);
})($app);
