/*
-- Query: SELECT * FROM zerbitzuak2.jatorriak
LIMIT 0, 1000

-- Date: 2017-08-17 12:04
*/
TRUNCATE TABLE `jatorriak`;

INSERT INTO `jatorriak` (`id`,`deskripzioa_es`,`deskripzioa_eu`) VALUES (1,'Escrita','Idatziz');
INSERT INTO `jatorriak` (`id`,`deskripzioa_es`,`deskripzioa_eu`) VALUES (2,'Web','Web');
INSERT INTO `jatorriak` (`id`,`deskripzioa_es`,`deskripzioa_eu`) VALUES (3,'Oral','Ahoz');
INSERT INTO `jatorriak` (`id`,`deskripzioa_es`,`deskripzioa_eu`) VALUES (4,'Telefónica','Telefonoz');


/*
-- Query: SELECT * FROM zerbitzuak2.egoerak
LIMIT 0, 1000

-- Date: 2017-08-17 10:07
*/
TRUNCATE TABLE `egoerak`;

INSERT INTO `egoerak` (`id`,`deskripzioa_es`,`deskripzioa_eu`) VALUES (1,'Sin enviar','Bidali-gabe');
INSERT INTO `egoerak` (`id`,`deskripzioa_es`,`deskripzioa_eu`) VALUES (2,'Enviado','Bidalia');
INSERT INTO `egoerak` (`id`,`deskripzioa_es`,`deskripzioa_eu`) VALUES (3,'Respondido','Erantzunda');
INSERT INTO `egoerak` (`id`,`deskripzioa_es`,`deskripzioa_eu`) VALUES (4,'Cerrado','Itxia');

/*
-- Query: SELECT * FROM zerbitzuak2.eskakizunMotak
LIMIT 0, 1000

-- Date: 2017-08-17 10:08
*/
TRUNCATE TABLE `eskakizunMotak`;

INSERT INTO `eskakizunMotak` (`id`,`deskripzioa_es`,`deskripzioa_eu`) VALUES (1,'Aviso','Abisua');
INSERT INTO `eskakizunMotak` (`id`,`deskripzioa_es`,`deskripzioa_eu`) VALUES (2,'Queja','Kexa');
INSERT INTO `eskakizunMotak` (`id`,`deskripzioa_es`,`deskripzioa_eu`) VALUES (3,'Sugerencia','Iradokizuna');

/*
-- Query: SELECT * FROM zerbitzuak2.enpresak
LIMIT 0, 1000

-- Date: 2017-08-17 10:08
*/

TRUNCATE TABLE `enpresak`;
--
-- Dumping data for table `enpresak`
--

INSERT INTO `enpresak` (`id`, `izena`, `ordena`, `aktibatua` ) VALUES
(11, 'Gabiscom', 11, 1),
(10, 'Acciona', 12, 1),
(9, 'Lantegi Batuak', 3, 1),
(8, 'Aquagest', 10, 1),
(7, 'Biurrarena', 4, 1),
(6, 'Garbiker', 7, 1),
(5, 'Impacto S.L.', 9, 1),
(4, 'Cespa, S.A.', 1, 1),
(3, 'Ecovidrio', 6, 1),
(2, 'Rezikleta', 5, 1),
(1, 'Amorebieta-Etxanoko Udala', 13, 1),
(13, 'Rafrinor, S.L.', 8, 1),
(14, 'Emergencias de GV', 14, 1),
(16, 'Administraria', 15, 1),
(17, 'Ocer', 2, 1),
(18, 'Tecuni, S.A.', 0, 1),
(19, 'Garbialdi, S.A.L.', 0, 1),
(20, 'Emaús', 0, 1),
(21, 'Europea de Trabajos', 0, 1),
(22, 'Koopera', 0, 1),
(23, 'Belako Lanak', 0, 1),
(24, 'Gespol', 0, 1);

/*
-- Query: SELECT * FROM zerbitzuak2.zerbitzuak
LIMIT 0, 1000

-- Date: 2017-08-17 10:08
*/

TRUNCATE TABLE `zerbitzuak`;
--
-- Dumping data for table `zerbitzuak2`
--

INSERT INTO `zerbitzuak` (`id`, `izena_eu`, `izena_es`,`enpresa_id`, `ordena`, `aktibatua`) VALUES
(14, '10b OINARRIZKO Saneamendu SAREA garbitzea (Astepe-Euba kolektorea)', '10b Limpieza RED PRIMARIA Saneamiento (Colector Astepe-Euba)', 10, 2, 1),
(15, '09a Isurketen kontrola ( Gabiscom )', '09a Control de vertidos ( Gabiscom )', 16, 1, 1),
(65, '52a Zerbitzuak: hobetzeko  iradokizunak eta jarduera berriak ', '52a Sugerencias de Mejora y Nuevas Actuaciones Servicios ', 1, 99, 1),
(13, '10c Hondakin uren ponpaketak (Euba, Zubieta, Astepe, Urritxe, Arriagane', '10c Bombeos de aguas residuales (Euba, Zubieta, Astepe,Urritxe, Arriagane)', 10, 99, 1),
(12, '10a Astepeko ur zikinen araztegia eta ponpaketak', '10a Explotación EDAR Astepe y bombeos', 10, 1, 1),
(11, '08a Edateko uraren kalitatea', '08a Calidad del agua potable', 8, 1, 1),
(10, '08b Urritxeko Edateko Ura Tratatzeko Gunea ustiatu eta haren mantentze-lanak', '08b Explotación y Mantenimiento ETAP Urritxe', 8, 99, 1),
(9, '03b Umeen jolas-guneen mantentze-lanak', '03b Mantenimiento de areas de juegos infantiles', 9, 2, 1),
(8, '03a Berdeguneen mantentze-lanak', '03a Mantenimiento de zonas verdes ', 9, 1, 1),
(7, '11a Eraikinak eta komun publikoak garbitzea', '11a Limpieza de edificios y aseos públicos', 5, 1, 1),
(6, '01f Arratoiak kentzea (gune publikoak)', '01f Desratizacion (zonas publicas)', 4, 6, 1),
(5, '01e Pilak batzea', '01e Recogida de pilas', 4, 5, 1),
(4, '01d Altzariak eta tresnak batzea', '01d Recogida de muebles y enseres', 4, 4, 1),
(3, '01c Saneamendu SARE OROKORRA garbitu, trabatzeak (oinarrizko sarekoak izan ezik)', '01c Limpieza RED GENERAL  de saneamiento, atascos (Excepto red primaria)', 4, 3, 1),
(2, '01b Zaborra batu (edukiontzi grisa)', '01b Recogida de basuras (contenedor gris)', 4, 2, 1),
(1, '01a Kaleak garbitu, pintadak eta kartelak kendu, isurtegiak garbitu, markesinak eta panelak garbitu', '01a Limpieza viaria, eliminación pintadas y carteles, limpieza sumideros, limpieza de marquesinas y paneles', 4, 1, 1),
(31, '57c Zaratak', '57c Ruidos', 1, 3, 1),
(32, '57d Ur horniketa', '57d Abastecimiento de aguas', 1, 2, 1),
(33, '57b Airearen kalitatea', '57b Calidad del aire', 1, 4, 1),
(41, '57a Industria jarduerak', '57a Actividades industriales', 1, 5, 1),
(35, '57b Industria isurtzea', '57b Vertido industrial', 16, 7, 1),
(36, '57e Obretako lan taldea', '57e Brigada de obras', 1, 2, 1),
(37, '52a Zerbitzuak: hobetzeko iradokizunak eta jarduera berriak ( Iñigo Artetxe )', '52a Sugerencias de Mejora y Nuevas Actuaciones Servicios ( Iñigo Artetxe )', 16, 99, 1),
(50, '08c Ur kontagailuak irakurtzea', '08c Lectura de contadores de agua', 8, 99, 1),
(17, '01g Edukiontziak: papera', '01g Contenedores papel', 4, 1, 1),
(18, '05a Edukiontziak: beira', '05a Contenedores vidrio', 3, 1, 1),
(19, '06a Edukiontziak: ontziak', '06a Contenedores envases', 6, 1, 1),
(20, '04a Edukiontziak: arropa', '04a Contenedores de ropa', 22, 2, 1),
(21, '07a Etxeko olioa batzea', '07a Recogida de aceite domestico', 13, 1, 1),
(22, '57d Larrialdiak (ibaietara egindako isurketak)', '57d Emergencias (Vertidos a rios)', 16, 99, 1),
(23, '53a Hiri altzarien mantentze-lanak', '53a Mantenimiento mobiliario urbano.', 1, 1, 1),
(24, '13a Herriko argiak (Ocer)', '13a Alumbrado publico (Ocer)', 16, 1, 1),
(25, '54a Lurzoru ez industrialean egindako jarduerak', '54a Actividades en suelo no industrial', 1, 1, 1),
(26, '54d Lizentzia: obra txikiak', '54d Licencia de obras menores', 1, 4, 1),
(27, '54b Irisgarritasuna', '54b Accesibilidad', 1, 2, 1),
(28, '54e Bereizteko lizentziak', '54e Licencias de segregación', 1, 5, 1),
(29, '54c Kale eta auzoen berri emateko seinaleztapenak', '54c Señalización informativa de calles y barrios', 1, 3, 1),
(30, '55a Lizentzia: obra handiak', '55a Licencias obras mayores', 1, 1, 1),
(42, '58a Tokiko Agenda 21', '58a Agenda Local 21', 1, 1, 1),
(43, '58b Legez kanpoko hondakin-metaketa', '58b Depositos de residuos ilegales', 1, 2, 1),
(44, '57a Baso-mozketa', '57a Tala de montes', 16, 1, 1),
(45, '57b Katastroa', '57b Catastro', 16, 2, 1),
(46, '13b Udal bideen ertzak: mantentze lanak', '13b Mantenimiento de margenes de caminos municipales', 16, 99, 1),
(47, '56a Obreen kontrola', '56a Control de obras', 1, 1, 1),
(58, '02a Herriko argiak', '02a Alumbrado publico', 18, 99, 1),
(51, '59a Etxebizitza', '59a Vivienda', 1, 1, 1),
(52, '50a Mantentze lanak: gune publikoa eta hiri altzariak (konponketak)', '50a Mantenimiento de via publica y mobiliario urbano (reparaciones)', 1, 1, 1),
(53, '51a Hirigintza: hobetzeko iradokizunak eta jarduera berriak', '51a Sugerencias de Mejora y Nuevas Actuaciones Urbanismo', 1, 1, 1),
(54, '13a Udal bideetako ertzak garbitzea (Europea de trabajos)', '13a Desbroce de márgenes de caminos municipales (Europea de trabajos)', 16, 3, 1),
(55, '53b Mantentze lanak: bide publikoak ', '53b Mantenimiento vias publicas y caminos', 1, 4, 1),
(56, '60c Isurketak (Orokorra)', '60c Vertidos (General)', 16, 6, 1),
(64, '01h Lurrazpiko edukiontzien mantentze-lanak', '01h Mantenimiento contenedores soterrados', 4, 99, 1),
(59, '58d Auzobusa', '58d Auzobusa', 1, 99, 1),
(60, '54f Terrazen instalazioa (ostalaritza)', '54f Instalacion de terrazas (hostalaritza)', 1, 99, 1),
(61, '58c Lur mugimendu eta betetzeak', '58c Movimiento tierras y rellenos', 1, 99, 1),
(62, '12a Amorebiziz', '12a Amorebiziz', 16, 99, 1),
(63, '13b Izotza edo elurra udal bideetan (Europea de trabajos)', '13b Hielo o nieve en caminos municipales (Europea de trabajos)', 16, 99, 1),
(66, '09a Isurketen controla ', '09a Control de vertidos ', 8, 99, 1),
(67, 'ZornotzaWifi sarea', 'ZornotzaWifi red', 1, 99, 1),
(69, '01i Organiko edukiontzien mantentze lanak', '01i Mantenimeno contenedores orgánicos', 4, 99, 1),
(70, '14a Udal bideetako ertzak garbitzea', '14a Desbroce de márgenes de caminos municipales', 23, 99, 1),
(71, '14b Izotza edo elurra udal bideetan', '14b Hielo o nieve en caminos municipales', 23, 99, 1),
(76, '60a Izer eta Nafarroako parkinen mantenimendua', '60a Mantenimiento parking automático Izer y Nafarroa', 24, 99, 1),
(77, 'Parking-en panelak', 'Paneles de parking', 1, 99, 1);


