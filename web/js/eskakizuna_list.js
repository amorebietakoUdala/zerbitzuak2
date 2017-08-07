$(function() {

$(document).ready(function(){

    $('.js-datepicker-noiztik').on('focus', function(e){
        $('.js-noiztik-label').addClass("active");
        $('.js-calendar').addClass("active");
    });

    $('.js-datepicker-noora').on('focus', function(e){
        $('.js-nora-label').addClass("active");
        $('.js-calendar').addClass("active");
    });

    $('.js-datepicker-noiztik').on('focusout', function(e){
        if ( $('.js-datepicker-noiztik').val() === '' ) {
	    $('.js-datepicker').siblings().removeClass("active");
            $('.js-noiztik-label').removeClass("active");
            $('.js-calendar').removeClass("active");
        }
    });

    $('.js-datepicker-nora').on('focusout', function(e){
        if ( $('.js-datepicker-nora').val() === '' ) {
	    $('.js-datepicker-nora').siblings().removeClass("active");
            $('.js-nora-label').removeClass("active");
            $('.js-calendar').removeClass("active");
        }
    });

    if ( $('.js-datepicker-noiztik').val() !== '' ) {
	$('.js-datepicker-noiztik').siblings().addClass("active");
    }

    if ( $('.js-datepicker-nora').val() !== '' ) {
	$('.js-datepicker-nora').siblings().addClass("active");
    }

    $('#bilatzailea').show();
    var erakutsi = true;
    $('#bilaketa_titulua').click(function () {
	if (!erakutsi) {
	    $('#bilatzailea').show();
	    erakutsi = true;
	} else  {
	    $('#bilatzailea').hide();
	    erakutsi = false;
	}
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


});    

});

