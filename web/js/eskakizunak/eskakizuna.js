$(function() {

$(document).ready(function(){
    var latitudea = 43.2206664;
    var longitudea = -2.733066600000029;
    var locale = $('html').attr('lang');
    var role = $('html').attr('role');

    if ($("#eskakizuna_form_georeferentziazioa_longitudea").val() !== '' && $("#eskakizuna_form_georeferentziazioa_latitudea").val() !== '') {
	latitudea = $("#eskakizuna_form_georeferentziazioa_latitudea").val();
	longitudea = $("#eskakizuna_form_georeferentziazioa_longitudea").val();
    }

    if ( typeof $( "#eskakizuna_form_eskatzailea_izena" ).val() !== "undefined" ) {
	/* INICIO Autocompletes */
	$( "#eskakizuna_form_eskatzailea_izena" ).autocomplete({
	    minLength: 3,
	    source: function (request, response) {
	      $.ajax({
		  url: "../../api/eskatzailea",
		  dataType: "json",
		  data: {
		    izena: request.term
		  },
		  success: function (data) {
		    response(data.eskatzaileak);
		  }
	      });
	    },
	    focus: function( event, ui ) {
	      $( "#eskakizuna_form_eskatzailea_izena" ).val( ui.item.izena );
	      return false;
	    },
	    select: function( event, ui ) {
	    $( "#eskakizuna_form_eskatzailea_id" ).val( ui.item.id );
	    $( "#eskakizuna_form_eskatzailea_nan" ).val( ui.item.nan );
	    $( "#eskakizuna_form_eskatzailea_izena" ).val( ui.item.izena );
	    $( "#eskakizuna_form_eskatzailea_emaila" ).val( ui.item.emaila );
	    $( "#eskakizuna_form_eskatzailea_telefonoa" ).val( ui.item.telefonoa );
	    $( "#eskakizuna_form_eskatzailea_faxa" ).val( ui.item.faxa );
	    $( "#eskakizuna_form_eskatzailea_helbidea" ).val( ui.item.helbidea );
	    $( "#eskakizuna_form_eskatzailea_postaKodea" ).val( ui.item.posta_kodea );
	    $( "#eskakizuna_form_eskatzailea_herria" ).val( ui.item.herria );
	    $( "i + label + input" ).each(function(x){
		if ($(this).val() !== "" ) {
		    $(this).addClass("active");
		    $(this).siblings().addClass("active");
		}
	    });
	    return false;
	    }
	})
	.autocomplete( "instance" )._renderItem = function( ul, item ) {
	  return $( "<li>" )
	    .append( "<div>" + item.izena + "</div>" )
	    .appendTo( ul );
	};            
    }
    if ( typeof $( "#eskakizuna_form_eskatzailea_nan" ).val() !== "undefined" ) {
	$( "#eskakizuna_form_eskatzailea_nan" ).autocomplete({
	    minLength: 3,
	    source: function (request, response) {
	      $.ajax({
		  url: "../../api/eskatzailea",
		  dataType: "json",
		  data: {
		    nan: request.term
		  },
		  success: function (data) {
		    console.log(data);
		    response(data.eskatzaileak);
		  }
	      });
	    },
	    focus: function( event, ui ) {
	      $( "#eskakizuna_form_eskatzailea_nan" ).val( ui.item.nan );
	      return false;
	    },
	    select: function( event, ui ) {
	    $( "#eskakizuna_form_eskatzailea_id" ).val( ui.item.id );
	    $( "#eskakizuna_form_eskatzailea_nan" ).val( ui.item.nan );
	    $( "#eskakizuna_form_eskatzailea_izena" ).val( ui.item.izena );
	    $( "#eskakizuna_form_eskatzailea_emaila" ).val( ui.item.emaila );
	    $( "#eskakizuna_form_eskatzailea_telefonoa" ).val( ui.item.telefonoa );
	    $( "#eskakizuna_form_eskatzailea_faxa" ).val( ui.item.faxa );
	    $( "#eskakizuna_form_eskatzailea_helbidea" ).val( ui.item.helbidea );
	    $( "#eskakizuna_form_eskatzailea_postaKodea" ).val( ui.item.postaKodea );
	    $( "#eskakizuna_form_eskatzailea_herria" ).val( ui.item.herria );
	    $( "i + label + input" ).each(function(x){
		if ($(this).val() !== "" ) {
		    $(this).addClass("active");
		    $(this).siblings().addClass("active");
		}
	    });
	    return false;
	    }
	})
	.autocomplete( "instance" )._renderItem = function( ul, item ) {
	  return $( "<li>" )
	    .append( "<div>" + item.nan + "</div>" )
	    .appendTo( ul );
	};     
    }
    /* FIN Autocompletes */

    /* INICIO Erantzunak */
/*
    $('#add-another-erantzuna').click(function(e) {
	e.preventDefault();

	var erantzunakList = $('#js-erantzunak-list');
	var erantzunakCount = $('#js-erantzunak-list li').length;

	var newWidget = erantzunakList.attr('data-prototype');
	
	newWidget = newWidget.replace(/__name__/g, erantzunakCount);
	erantzunakCount++;

	// create a new list element and add it to the list
	var newLi = $('<li></li>').html(newWidget);
	newLi.appendTo(erantzunakList);
	tinymce.init({ selector:'#js-erantzunak-list textarea',
            menubar: false,
            resize: false,
            statusbar: false
	});
    });
*/
    // Borratzeko 
//    var $erantzunakList = $('#js-erantzunak-list');
//    
//    $erantzunakList.find('li').each(function() {
//        addErantzaunakFormDeleteLink($(this));
//    });
    
    /* FIN Erantzunak*/
    
    tinymce.init({ 
	selector:'textarea',
        menubar: false,
        resize: false,
        statusbar: false,
	theme: 'modern',
    	init_instance_callback : function(editor) {
	  if (editor.id === 'eskakizuna_form_mamia' ) {
	    if (role === "KANPOKO_TEKNIKARIA") {
		editor.setMode('readonly');
	    }
	  }
	}
    });
    

//    function addErantzaunakFormDeleteLink ($tagFormLi) {
//        var $removeFormA = $('<a href="#"><i class="fa fa-close prefix active"></i></a>');
//	$tagFormLi.append($removeFormA);
//        $removeFormA.on('click', function(e) {
//	    // prevent the link from creating a "#" on the URL
//	    e.preventDefault();
//	    // remove the li for the tag form
//	    $tagFormLi.remove();
//	});
//    }
    
//    tinymce.get('textarea').getBody().setAttribute('contenteditable', false);

    
    /* INICIO Eventos */

    $("label[for], input[type='text']").click( function (e) {
        $(this).addClass("active");
        $(this).siblings("input[type='text']").addClass("active").focus();
    });

    $("select").click( function (e) {
        $(this).addClass("active");
        $(this).siblings().addClass("active").focus();
    });

    $('.js-datepicker').datetimepicker({
	format: 'yyyy-mm-dd hh:ii',
	autoclose: true,
	language: locale,
	fontAwesome: true
    }).attr('type','text'); // Honekin chromen ez da testua agertzen

     $(".js-argazkia").change(function(){
	console.log(locale);
        var ok = readURL(this,$('argazkia-preview'));
	if (!ok) {
	    if (locale === 'eu')
		alert("Fitxategia handiegia da. Hautatu beste bat mesedez.");
	    else 
		alert("El fichero es demasiado grande elija uno más pequeño");
	}
    });
    

    $(".js-btn-erantzun").click(function() {
        $(".js-erantzuna").show();
    });

    $("#buscar").click(function() {
        var direccion = $("#eskakizuna_form_kalea").val();
            if (direccion !== "") {
                    localizar("map-container", direccion + ", Amorebieta-Etxano");
            }
    });            

    google.maps.event.addDomListener(window, 'load', init_map(latitudea, longitudea));

    $(".js-gorde_botoia, .js-erantzun_botoia").click(function(e) {
	$("#eskakizunaForm").submit();
    });


});    

function readURL(input) {
    if (input.files && input.files[0]) {
	var filesize = input.files[0].size;
	if ( filesize <=  3*1024*1024 ) {
	    
	    var reader = new FileReader();

	    reader.onload = function (e) {
		$('#argazkia-preview').attr('src', e.target.result);
	    }

	    reader.readAsDataURL(input.files[0]);
	    $('#argazkia-preview').css("height","270px");
	    $('#argazkia-preview').css("width","auto");
	    return true;
	} else {
		return false;
	    
	}
	
    }
}
    
function init_map(latitudea, longitudea) {
    
    var var_location = new google.maps.LatLng(latitudea, longitudea);

    var var_mapoptions = {
        center: var_location,
        zoom: 16,
        scrollwheel: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
    };

    var var_marker = new google.maps.Marker({
        position: var_location,
        map: var_map,
	draggable: true,
	animation: google.maps.Animation.DROP,
    });

    var var_map = new google.maps.Map(document.getElementById("map-container"),
        var_mapoptions);

    var_marker.setMap(var_map);
    
    google.maps.event.addListener(var_marker,'drag',function(event) {
	document.getElementById('eskakizuna_form_georeferentziazioa_latitudea').value = event.latLng.lat();
	document.getElementById('eskakizuna_form_georeferentziazioa_longitudea').value = event.latLng.lng();
    });

    google.maps.event.addListener(var_marker,'dragend',function(event) {
	document.getElementById('eskakizuna_form_georeferentziazioa_latitudea').value =event.latLng.lat();
	document.getElementById('eskakizuna_form_georeferentziazioa_longitudea').value =event.latLng.lng();
    });
}

function parse_results(results) {
    $("#eskakizuna_form_georeferentziazioa_longitudea").val( results[0].geometry.location.lng);
    $("#eskakizuna_form_georeferentziazioa_latitudea").val( results[0].geometry.location.lat);
    $("#eskakizuna_form_georeferentziazioa_google_address").val( results[0].formatted_address);
}

function localizar(elemento,direccion) {
        var geocoder = new google.maps.Geocoder();

        var map = new google.maps.Map(document.getElementById(elemento), {
          zoom: 16,
          scrollwheel: true,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
	  draggable: true
        });

        geocoder.geocode({'address': direccion}, function(results, status) {
                if (status === 'OK') {
                        var resultados = results[0].geometry.location,
                                resultados_lat = resultados.lat(),
                                resultados_long = resultados.lng();

                        map.setCenter(results[0].geometry.location);
                        var marker = new google.maps.Marker({
                                map: map,
                                position: results[0].geometry.location,
				title: direccion,
				animation: google.maps.Animation.DROP,
				draggable: true
                        });
			    google.maps.event.addListener(marker,'drag',function(event) {
				document.getElementById('eskakizuna_form_georeferentziazioa_latitudea').value = event.latLng.lat();
				document.getElementById('eskakizuna_form_georeferentziazioa_longitudea').value = event.latLng.lng();
    });

			    google.maps.event.addListener(marker,'dragend',function(event) {
				document.getElementById('eskakizuna_form_georeferentziazioa_latitudea').value =event.latLng.lat();
				document.getElementById('eskakizuna_form_georeferentziazioa_longitudea').value =event.latLng.lng();
			});

			parse_results(results);
                } else {
                        var mensajeError = "";
                        if (status === "ZERO_RESULTS") {
                                mensajeError = "No hubo resultados para la dirección ingresada.";
                        } else if (status === "OVER_QUERY_LIMIT" || status === "REQUEST_DENIED" || status === "UNKNOWN_ERROR") {
                                mensajeError = "Error general del mapa.";
                        } else if (status === "INVALID_REQUEST") {
                                mensajeError = "Error de la web. Contacte con Name Agency.";
                        }
                        alert(mensajeError);
                }
        });
}

});

