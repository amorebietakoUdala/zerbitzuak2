function View() {
  var dom = {
    bilatzailea: $('#bilatzailea'),
    bilaketaTitulua: $('#bilaketa_titulua'),
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
  return {
    onTituluaClick: onTituluaClick
  };
}