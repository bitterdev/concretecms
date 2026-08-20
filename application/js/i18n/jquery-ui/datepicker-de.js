/* Author: Fabian Bitter (fabian@bitter.de) */

/*
 * The German words of the jQuery UI date picker, held back until the date
 * picker itself is there.
 *
 * WHY THIS FILE EXISTS AT ALL. /ccm/assets/localization/jquery/ui/js hands out
 * whatever stands in js/i18n/jquery-ui/datepicker-<language>.js, unchanged, and
 * the file it hands out ends in
 *
 *     factory(jQuery.datepicker);
 *     ... datepicker.regional['de'] = { ... }
 *
 * That script is drawn into every page of the dashboard BEFORE the bundle that
 * brings jQuery UI, so jQuery.datepicker is undefined when it runs and every
 * dashboard page carried
 *
 *     TypeError: Cannot read properties of undefined (reading 'regional')
 *
 * in its console. Nothing visible broke, but the date fields kept their English
 * month names and the console was never clean.
 *
 * Concrete fixed this in its own house on 9.4.7, by wrapping whatever it reads
 * out of this file in exactly the guard below (pull request 12765, issue
 * 12145). The installation this site runs on is 9.3.9 and does not have that
 * fix, so the guard is put on the file instead of on the reader: the reader
 * takes it either way, and on an installation that has grown past 9.4.7 it
 * simply sits inside the guard the core wrote and costs nothing.
 *
 * IT NEVER RUNS WITHOUT THE DATE PICKER. Where the picker is already there the
 * words are set at once; where the page is still loading they wait for it and
 * are set then. Where jQuery UI is not on the page at all nothing happens,
 * which is what should have happened all along.
 *
 * Everything under this comment is the file of the core, word for word.
 */
(function () {
    function ready() {
        if (!window.jQuery || !window.jQuery.datepicker) {
            return;
        }

        /* German initialisation for the jQuery UI date picker plugin. */
        /* Written by Milian Wolff (mail@milianw.de). */
        (function( factory ) {
        	if ( typeof define === "function" && define.amd ) {

        		// AMD. Register as an anonymous module.
        		define([ "../datepicker" ], factory );
        	} else {

        		// Browser globals
        		factory( jQuery.datepicker );
        	}
        }(function( datepicker ) {

        datepicker.regional['de'] = {
        	closeText: 'Schließen',
        	prevText: '&#x3C;Zurück',
        	nextText: 'Vor&#x3E;',
        	currentText: 'Heute',
        	monthNames: ['Januar','Februar','März','April','Mai','Juni',
        	'Juli','August','September','Oktober','November','Dezember'],
        	monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun',
        	'Jul','Aug','Sep','Okt','Nov','Dez'],
        	dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
        	dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
        	dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
        	weekHeader: 'KW',
        	dateFormat: 'dd.mm.yy',
        	firstDay: 1,
        	isRTL: false,
        	showMonthAfterYear: false,
        	yearSuffix: ''};
        datepicker.setDefaults(datepicker.regional['de']);

        return datepicker.regional['de'];

        }));
    }

    if (window.jQuery && window.jQuery.datepicker) {
        ready();
    } else if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', ready);
    } else {
        ready();
    }
})();
