$(function() {

    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    };

    $(document).ready(function() {
        var locale = $('html').attr('lang');

        if ($('.js-datepicker-noiztik').val() !== '') {
            $('.js-datepicker-noiztik').siblings().addClass("active");
        }

        if ($('.js-datepicker-nora').val() !== '') {
            $('.js-datepicker-nora').siblings().addClass("active");
        }

        $('#bilatzailea').show();
        var erakutsi = true;
        $('#bilaketa_titulua').click(function() {
            if (!erakutsi) {
                $('#bilatzailea').show();
                erakutsi = true;
            } else {
                $('#bilatzailea').hide();
                erakutsi = false;
            }
        });

        $('#js-btn-bilatu').click(function(e) {
            e.preventDefault();
            console.log($('form[name="eskakizuna_bilatzailea_form"]'));
            var action = location.href;
            action = new URI(location.href);
            action.setQuery("returnPage", 1);
            action.setQuery("pageSize", $('li[role="menuitem"].active a').text());
            $('form[name="eskakizuna_bilatzailea_form"]').attr('action', action.toString());
            $('form[name="eskakizuna_bilatzailea_form"]').submit();
        });

        $('#js-btn-garbitu').click(function(e) {
            e.preventDefault();
            $('form[name="eskakizuna_bilatzailea_form"] input').not('input[type="hidden"]').val('');
            $('form[name="eskakizuna_bilatzailea_form"] input').not('input[type="hidden"]').siblings().removeClass('active');
            $('form[name="eskakizuna_bilatzailea_form"] select').val('');
            $('form[name="eskakizuna_bilatzailea_form"] select').siblings().removeClass('active');
        });


        /* AJAX bidez bilatzeko, oraingoz ez dut erabiltzen*/
        //    $("#js-btn-bilatu").click(function() {
        //	$.ajax({
        //              url: "/api/eskakizuna",
        //              dataType: "json",
        //              data: {
        //		  let: $('#eskakizuna_bilatzailea_form_let').val(),
        //		  noiztik: $('#eskakizuna_bilatzailea_form_noiztik').val(),
        //		  nora: $('#eskakizuna_bilatzailea_form_nora').val(),
        //		  egoera: $('#eskakizuna_bilatzailea_form_egoera').val(),
        //		  zerbitzua: $('#eskakizuna_bilatzailea_form_zerbitzua').val(),
        //		  arduraduna: $('#eskakizuna_bilatzailea_form_arduraduna').val()
        ////                nan: request.term
        //              },
        //              success: function (data) {
        //                console.log(data);
        //		$('#taula').bootstrapTable("load", data.eskakizunak);
        ////                response(data.eskakizunak);
        //              }
        //          });
        //    });

        $('.js-datepicker').datetimepicker({
            format: 'yyyy-mm-dd hh:ii',
            autoclose: true,
            language: locale,
            fontAwesome: true
        }).attr('type', 'text'); // Honekin chromen ez da testua agertzen

        $table = $('.taula');
        var options = $table.bootstrapTable('getOptions');

        //Get the page number
        var pageNumber = options.pageNumber;

        var returnPage = getUrlParameter('returnPage');
        if (!returnPage) {
            returnPage = 1;
        }

        if (returnPage > options.totalPages) {
            returnPage = options.totalPages;
        }

        $table.bootstrapTable('selectPage', returnPage);

        var $remove = $('#batchclose');
        $remove.click(function() {
            var ids = $.map($table.bootstrapTable('getSelections'), function(row) {
                return row.id;
            });

            console.log(ids);
            $.ajax({
                url: "../../api/batchclose",
                dataType: "json",
                method: "POST",
                data: {
                    ids: JSON.stringify(ids),
                },
                success: function(data) {
                    $table.bootstrapTable('remove', {
                        field: 'id',
                        values: ids
                    });
                }
            });

            //		  $remove.prop('disabled', true);
        });
        window.icons = {
            export: 'fa fa-file-export'
        };
    });

});