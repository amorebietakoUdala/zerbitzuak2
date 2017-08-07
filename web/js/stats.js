$(function() {

    $(document).ready(function(){
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
    });
});
