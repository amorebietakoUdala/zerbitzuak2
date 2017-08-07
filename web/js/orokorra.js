$(function() {

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
	    title: 'Ezabatu?',
	    text: 'Konfirmatu mesedez',
	    confirmButtonText: 'Bai',
	    cancelButtonText: 'Ez',
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