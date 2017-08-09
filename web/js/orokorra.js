$(function() {
    var locale = $('html').attr('lang');
    $(document).ready(function(){
        if ( $("select").val() !== "" ) {
            $("select").addClass("active");
            $("select").siblings().addClass("active").focus();
        }
        
        $("select").click( function (e) {
            $(this).addClass("active");
            $(this).siblings().addClass("active").focus();
        });

        $("js-lang-es").click( function (e) {
            $(this).addClass("active");
            $(this).siblings().addClass("active").focus();
        });

    });

    $("label[for], input[type='text']").click( function (e) {
        $(this).addClass("active");
        $(this).siblings("input[type='text']").addClass("active").focus();
    });

    $(document).on('click','.js-ezabatu_botoia' ,function (e) {
	e.preventDefault();
	var url = $(e.currentTarget).data('url');
	swal({
	    title: locale === 'eu' ? 'Ezabatu?' : 'Borrar?',
	    text: locale === 'eu' ? 'Konfirmatu mesedez' : 'Confirme por favor',
	    confirmButtonText: locale === 'eu' ? 'Bai' : 'Sí',
	    cancelButtonText: locale === 'eu' ? 'Ez' : 'No',
	    showCancelButton: true,
	    showLoaderOnConfirm: true,
	    preConfirm: function () {
		console.log(url);
		window.location.href=url;
	    }
	}).catch(function (arg) {
		console.log('Canceled!');
	});
    
    });

});