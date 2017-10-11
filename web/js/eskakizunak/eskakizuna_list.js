$(function() {

$(document).ready(function(){

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

    var locale = $('html').attr('lang');
    $(document).on('click','.js-itxi_botoia' ,function (e) {
	e.preventDefault();
	var url = $(e.currentTarget).data('url');
	swal({
	    title: locale === 'eu' ? 'Itxi?' : 'Cerrar?',
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
		console.log('Cancelado!');
	});
    });

    $(document).on('click','.js-erreklamatu_botoia' ,function (e) {
	e.preventDefault();
	var url = $(e.currentTarget).data('url');
	swal({
	    title: locale === 'eu' ? 'Erreklamatu?' : 'Reclamar?',
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
		console.log('Cancelado!');
	});
    });

    $('.js-datepicker').datetimepicker({
	format: 'yyyy-mm-dd hh:ii',
	autoclose: true,
	language: locale,
	fontAwesome: true
    }).attr('type','text'); // Honekin chromen ez da testua agertzen

/*
    $('.js-datepicker').siblings().on('click', function (e) {
	$(e.currentTarget).siblings().addClass("active");
	$(e.currentTarget).addClass("active");
	$(e.currentTarget).siblings('input').datetimepicker("show");
    });

    $('.js-datepicker').siblings().on('focus', function (e) {
	$(e.currentTarget).siblings().addClass("active");
	$(e.currentTarget).addClass("active");
	$(e.currentTarget).siblings('input').datetimepicker("show");
    });

    $('.js-datepicker').siblings().on('focusout', function (e) {
	if ( $(e.currentTarget).siblings('input').val() === '' ) {
	    $(e.currentTarget).siblings().removeClass("active");
            $(e.currentTarget).removeClass("active");
	}
    });
*/
});    

});

