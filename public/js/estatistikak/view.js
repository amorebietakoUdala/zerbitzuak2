function View() {
  var dom = {
    bilatzailea: $('#bilatzailea'),
    bilaketaTitulua: $('#bilaketa_titulua'),
    noiztikEremua: $('.js-datepicker-noiztik'),
    noraEremua: $('.js-datepicker-nora'),
  };
  var erakutsi = true;
  function onTituluaClick() { 
    dom.bilaketaTitulua.on('click', function () {
	if (!erakutsi) {
	    dom.bilatzailea.show();
	    erakutsi = true;
	} else  {
	    dom.bilatzailea.hide();
	    erakutsi = false;
	}
	return false;
    });
  }
  function onNoiztikChanged() {
      dom.noiztikEremua.on('change',function () {
        if ( dom.noiztikEremua.val() !== '' ) {
          dom.noiztikEremua.siblings().addClass("active");
        }
      });
  }
  function onNoraChanged() {
      dom.noraEremua.on('change',function () {
        if ( dom.noraEremua.val() !== '' ) {
          dom.noraEremua.siblings().addClass("active");
        }
      });
  }
  return {
    onTituluaClick: onTituluaClick,
    onNoiztikChanged: onNoiztikChanged,
    onNoraChanged: onNoraChanged
  };
}

$(document).ready(function(){
    var locale = $('html').attr('lang');
    $('.js-datepicker').datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        autoclose: true,
        language: locale,
        fontAwesome: true
        }).attr('type','text'); // Honekin chromen ez da testua agertzen
});
