<?php

/* Author: Fabian Bitter (fabian@bitter.de) */

namespace Application\Controller\SinglePage\Dashboard\Files;

use Concrete\Controller\SinglePage\Dashboard\Files\Details as CoreDetails;

/**
 * The file details page, answering a hand written address with its own refusal
 * instead of with a 500.
 *
 * WHAT HAPPENS WITHOUT THIS. The core reads the file out of the path, so
 * /dashboard/files/details/6229 works and is the only address the file manager
 * ever links to. Typed or bookmarked as /dashboard/files/details?fID=6229 the
 * method gets no argument at all and falls back to its own default, the empty
 * string, which its typed helper refuses:
 *
 *     public function view($fID = '') { $this->getFileVersion($fID, null); }
 *     protected function getFileVersion(?int $fID = null, ...)
 *
 *     Details::getFileVersion(): Argument #1 ($fID) must be of type ?int,
 *     string given
 *
 * PHP coerces "6229" to an int by itself, so only the empty default ever
 * reaches it, and the page answers with "Ein unerwarteter Fehler ist
 * aufgetreten" rather than with the sentence the core wrote for exactly this
 * case. The core's own rescan() already casts the same way this does; view()
 * and preview_version() were left out.
 *
 * Nothing else is changed: both readings hand their work straight back to the
 * core, and a number given the usual way arrives there untouched.
 */
class Details extends CoreDetails
{
    public function view($fID = '')
    {
        return parent::view($this->fileId($fID));
    }

    public function preview_version($fID = null, $fvID = null)
    {
        return parent::preview_version($this->fileId($fID), $this->fileId($fvID));
    }

    /**
     * The number in an address, or null when there is none.
     *
     * Null rather than nought: the core reads null as "no file was named" and
     * says so, while a nought would be looked up and missed with the same
     * sentence for a different reason.
     *
     * @param mixed $value
     */
    protected function fileId($value): ?int
    {
        return is_numeric($value) ? (int) $value : null;
    }
}
