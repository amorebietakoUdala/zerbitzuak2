$(document).ready(function() {
    var locale = $('html').attr('lang');
    if ($("select").val() !== "") {
        $("select").addClass("active");
        $("select").siblings().addClass("active").focus();
    }

    $("select").click(function(e) {
        $(this).addClass("active");
        $(this).siblings().addClass("active").focus();
    });

    $("js-lang-es").click(function(e) {
        $(this).addClass("active");
        $(this).siblings().addClass("active").focus();
    });

    $(".js-datepicker").siblings().on('click', function(e) {
        $(e.currentTarget).siblings().addClass("active");
        $(e.currentTarget).addClass("active");
        $(e.currentTarget).siblings('input').datetimepicker("show");
    });

    $(".js-datepicker").siblings().on('focus', function(e) {
        $(e.currentTarget).siblings().addClass("active");
        $(e.currentTarget).addClass("active");
        $(e.currentTarget).siblings('input').datetimepicker("show");
    });

    $(".js-datepicker").siblings().on('focusout', function(e) {
        if ($(e.currentTarget).siblings('input').val() === '') {
            $(e.currentTarget).siblings().removeClass("active");
            $(e.currentTarget).removeClass("active");
        }
    });

    $("label[for], input[type='text']").click(function(e) {
        $(this).addClass("active");
        $(this).siblings("input[type='text']").addClass("active").focus();
    });

    $(document).on("click", ".js-ezabatu_botoia", function(e) {
        e.preventDefault();
        console.log('Ezabatu botoia sakatuta');
        var url = $(e.currentTarget).data('url');
        var locale = $('html').attr('lang');
        swal({
            title: locale === 'eu' ? 'Ezabatu?' : 'Borrar?',
            text: locale === 'eu' ? 'Konfirmatu mesedez' : 'Confirme por favor',
            confirmButtonText: locale === 'eu' ? 'Bai' : 'Sí',
            cancelButtonText: locale === 'eu' ? 'Ez' : 'No',
            showCancelButton: true,
            showLoaderOnConfirm: true,
            preConfirm: function() {
                $table = $('.taula');
                if ((typeof $table.bootstrapTable) != 'undefined') {
                    var options = $table.bootstrapTable('getOptions');
                    var pageNumber = options.pageNumber;
                    uri = new URI(url);
                    var uri = new URI(url);
                    uri.addQuery("returnPage", pageNumber);
                    url = uri.toString();
                }
                window.location.href = url;
            }
        }).catch(function(arg) {
            console.log('Cancelado!');
        });
    });

    $(document).on('click', '.js-itxi_botoia', function(e) {
        e.preventDefault();
        var url = $(e.currentTarget).data('url');
        swal({
            title: locale === 'eu' ? 'Itxi?' : 'Cerrar?',
            text: locale === 'eu' ? 'Konfirmatu mesedez' : 'Confirme por favor',
            confirmButtonText: locale === 'eu' ? 'Bai' : 'Sí',
            cancelButtonText: locale === 'eu' ? 'Ez' : 'No',
            showCancelButton: true,
            showLoaderOnConfirm: true,
            preConfirm: function() {
                $table = $('.taula');
                if ((typeof $table.bootstrapTable) != 'undefined') {
                    var options = $table.bootstrapTable('getOptions');
                    var pageNumber = options.pageNumber;
                    var uri = new URI(url);
                    uri.addQuery("returnPage", pageNumber);
                    url = uri.toString();
                }
                window.location.href = url;
            }
        }).catch(function(arg) {
            console.log('Cancelado!');
        });
    });

    $(document).on('click', '.js-erreklamatu_botoia', function(e) {
        e.preventDefault();
        var url = $(e.currentTarget).data('url');
        swal({
            title: locale === 'eu' ? 'Erreklamatu?' : 'Reclamar?',
            text: locale === 'eu' ? 'Konfirmatu mesedez' : 'Confirme por favor',
            confirmButtonText: locale === 'eu' ? 'Bai' : 'Sí',
            cancelButtonText: locale === 'eu' ? 'Ez' : 'No',
            showCancelButton: true,
            showLoaderOnConfirm: true,
            preConfirm: function() {
                $table = $('.taula');
                if ((typeof $table.bootstrapTable) != 'undefined') {
                    var options = $table.bootstrapTable('getOptions');
                    var pageNumber = options.pageNumber;
                    var uri = new URI(url);
                    uri.addQuery("returnPage", pageNumber);
                    url = uri.toString();
                }
                window.location.href = url;
            }
        }).catch(function(arg) {
            console.log('Cancelado!');
        });
    });

    $(document).on('click', '.js-erakutsi_botoia', function(e) {
        e.preventDefault();
        var url = $(e.currentTarget).data('url');
        $table = $('.taula');
        var options = $table.bootstrapTable('getOptions');
        var pageNumber = options.pageNumber;
        uri = new URI(url);
        uri.addQuery("returnPage", pageNumber);
        window.location.href = uri;
    });

    $(document).on('click', '.js-editatu_botoia', function(e) {
        e.preventDefault();
        var url = $(e.currentTarget).data('url');
        $table = $('.taula');
        var options = $table.bootstrapTable('getOptions');
        var pageNumber = options.pageNumber;
        uri = new URI(url);
        uri.addQuery("returnPage", pageNumber);
        window.location.href = uri;
    });
});