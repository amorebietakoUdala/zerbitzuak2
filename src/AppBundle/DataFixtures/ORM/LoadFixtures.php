<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

/**
 * Description of LoadFixtures
 *
 * @author ibilbao
 */
class LoadFixtures implements FixtureInterface {

    public function load(ObjectManager $manager) {
	Fixtures::load(__DIR__.'/fixtures.yml',$manager,
		[
		    'providers' => [$this]
		]);
    }

    public function izenak() {
	$izenak  = [
	    'Mikel',
	    'Aitor',
	    'Jon',
	    'Unai',
	    'Maider',
	    'Saioa',
	    'Nahia',
	    'Nahiara',
	    'Uxue',
	    'Lander',
	    'Erlantz'
	];
	
	$key = array_rand($izenak);
	return ($izenak[$key]);
    }

    public function zerbitzuak_eu() {
	$zerbitzuak  = [
	    'OINARRIZKO Saneamendu SAREA garbitzea (Astepe-Euba kolektorea)',
	    'Isurketen kontrola ( Gabiscom )',
	    'Zerbitzuak: hobetzeko  iradokizunak eta jarduera berriak',
	    'Hondakin uren ponpaketak (Euba, Zubieta, Astepe, Urritxe, Arriagane',
	    'Astepeko ur zikinen araztegia eta ponpaketak',
	    'Edateko uraren kalitatea',
	    'Urritxeko Edateko Ura Tratatzeko Gunea ustiatu eta haren mantentze-lanak',
	    'Umeen jolas-guneen mantentze-lanak',
	    'Berdeguneen mantentze-lanak',
	    'Eraikinak eta komun publikoak garbitzea',
	    'Arratoiak kentzea (gune publikoak)',
	    'Pilak batzea',
	    'Altzariak eta tresnak batzea',
	    'Saneamendu SARE OROKORRA garbitu, trabatzeak (oinarrizko sarekoak izan ezik)',
	    'Zaborra batu (edukiontzi grisa)',
	    'Kaleak garbitu, pintadak eta kartelak kendu, isurtegiak garbitu, markesinak eta panelak garbitu',
	    'Zaratak',
	    'Ur horniketa',
	    'Airearen kalitatea',
	    'Industria jarduerak',
	    'Industria isurtzea',
	    'Obretako lan taldea',
	    'Zerbitzuak: hobetzeko iradokizunak eta jarduera berriak ( Iñigo Artetxe )',
	    'Ur kontagailuak irakurtzea',
	    'Edukiontziak: papera',
	    'Edukiontziak: beira',
	    'Edukiontziak: ontziak',
	    'Edukiontziak: arropa',
	    'Etxeko olioa batzea',
	    'Larrialdiak (ibaietara egindako isurketak)',
	    'Hiri altzarien mantentze-lanak',
	    'Herriko argiak (Ocer)',
	    'Lurzoru ez industrialean egindako jarduerak',
	    'Lizentzia: obra txikiak',
	    'Irisgarritasuna',
	    'Bereizteko lizentziak',
	    'Kale eta auzoen berri emateko seinaleztapenak',
	    'Lizentzia: obra handiak',
	    'Tokiko Agenda 21',
	    'Legez kanpoko hondakin-metaketa',
	    'Baso-mozketa',
	    'Katastroa',
	    'Udal bideen ertzak: mantentze lanak',
	    'Obreen kontrola',
	    'Herriko argiak',
	    'Etxebizitza',
	    'Mantentze lanak: gune publikoa eta hiri altzariak (konponketak)',
	    'Hirigintza: hobetzeko iradokizunak eta jarduera berriak',
	    'Udal bideetako ertzak garbitzea (Europea de trabajos)',
	    'Mantentze lanak: bide publikoak',
	    'Isurketak (Orokorra)',
	    'Lurrazpiko edukiontzien mantentze-lanak',
	    'Auzobusa',
	    'Terrazen instalazioa (ostalaritza)',
	    'Lur mugimendu eta betetzeak',
	    'Amorebiziz',
	    'Izotza edo elurra udal bideetan (Europea de trabajos)',
	    'Isurketen controla',
	    'ZornotzaWifi sarea',
	    'Organiko edukiontzien mantentze lanak',
	    'Udal bideetako ertzak garbitzea',
	    'Izotza edo elurra udal bideetan',
	    'Izer eta Nafarroako parkinen mantenimendua',
	];
	
	$key = array_rand($zerbitzuak);
	return ($zerbitzuak[$key]);
    }

    public function zerbitzuak_es() {
	$zerbitzuak  = [
	    'Limpieza RED PRIMARIA Saneamiento (Colector Astepe-Euba)',
	    'Control de vertidos ( Gabiscom )',
	    'Sugerencias de Mejora y Nuevas Actuaciones Servicios',
	    'Bombeos de aguas residuales (Euba, Zubieta, Astepe,Urritxe, Arriagane)',
	    'Explotación EDAR Astepe y bombeos',
	    'Calidad del agua potable',
	    'Explotación y Mantenimiento ETAP Urritxe',
	    'Mantenimiento de areas de juegos infantiles',
	    'Mantenimiento de zonas verdes',
	    'Limpieza de edificios y aseos públicos',
	    'Desratizacion (zonas publicas)',
	    'Recogida de pilas',
	    'Recogida de muebles y enseres',
	    'Limpieza RED GENERAL  de saneamiento, atascos (Excepto red primaria)',
	    'Recogida de basuras (contenedor gris)',
	    'Limpieza viaria, eliminación pintadas y carteles, limpieza sumideros, limpieza de marquesinas y paneles',
	    'Ruidos',
	    'Abastecimiento de aguas',
	    'Calidad del aire',
	    'Actividades industriales',
	    'Vertido industrial',
	    'Brigada de obras',
	    'Sugerencias de Mejora y Nuevas Actuaciones Servicios ( Iñigo Artetxe )',
	    'Lectura de contadores de agua',
	    'Contenedores papel',
	    'Contenedores vidrio',
	    'Contenedores envases',
	    'Contenedores de ropa',
	    'Recogida de aceite domestico',
	    'Emergencias (Vertidos a rios)',
	    'Mantenimiento mobiliario urbano.',
	    'Alumbrado publico (Ocer)',
	    'Actividades en suelo no industrial',
	    'Licencia de obras menores',
	    'Accesibilidad',
	    'Licencias de segregación',
	    'Señalización informativa de calles y barrios',
	    'Licencias obras mayores',
	    'Agenda Local 21',
	    'Depositos de residuos ilegales',
	    'Tala de montes',
	    'Catastro',
	    'Mantenimiento de margenes de caminos municipales',
	    'Control de obras',
	    'Alumbrado publico',
	    'Vivienda',
	    'Mantenimiento de via publica y mobiliario urbano (reparaciones)',
	    'Sugerencias de Mejora y Nuevas Actuaciones Urbanismo',
	    'Desbroce de márgenes de caminos municipales (Europea de trabajos)',
	    'Mantenimiento vias publicas y caminos',
	    'Vertidos (General)',
	    'Mantenimiento contenedores soterrados',
	    'Auzobusa',
	    'Instalacion de terrazas (hostalaritza)',
	    'Movimiento tierras y rellenos',
	    'Amorebiziz',
	    'Hielo o nieve en caminos municipales (Europea de trabajos)',
	    'Control de vertidos',
	    'red',
	    'Mantenimeno contenedores orgánicos',
	    'Desbroce de márgenes de caminos municipales',
	    'Hielo o nieve en caminos municipales',
	    'Mantenimiento parking automático Izer y Nafarroa',
	];
	
	$key = array_rand($zerbitzuak);
	return ($zerbitzuak[$key]);
    }

}
