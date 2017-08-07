/**
 * Bootstrap Table Basque translation
 * Author: Marc Pina<iwalkalone69@gmail.com>
 */
(function ($) {
    'use strict';

    $.fn.bootstrapTable.locales['es-EU'] = {
        formatLoadingMessage: function () {
            return 'Itxaron mesedez...';
        },
        formatRecordsPerPage: function (pageNumber) {
            return pageNumber + ' emaitza orrialdeko';
        },
        formatShowingRows: function (pageFrom, pageTo, totalRows) {
            return  pageFrom + '. emaitzatik ' + pageTo + ' emaitzara - Guztira ' + totalRows + ' emaitza';
        },
        formatSearch: function () {
            return 'Bilatu';
        },
        formatNoMatches: function () {
            return 'Ez da emaitzarik aurkitu';
        },
        formatPaginationSwitch: function () {
            return 'Erakutsi/ezkutatu orrialdekatzea';
        },
        formatRefresh: function () {
            return 'Berriztu';
        },
        formatToggle: function () {
            return 'Erakutsi/ezkutatu';
        },
        formatColumns: function () {
            return 'Zutabeak';
        },
        formatAllRows: function () {
            return 'Denak';
        }
    };

    $.extend($.fn.bootstrapTable.defaults, $.fn.bootstrapTable.locales['es-ES']);

})(jQuery);
