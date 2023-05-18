<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here 'ca'.
 *
 * @package     mod_isardvdi
 * @category    string
 * @copyright   2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Isard VDI Desktops';
$string['modulename'] = 'Isard VDI Desktops';
$string['modulenameplural'] = 'Isard VDI Desktops';
$string['missingidandcmid'] = "No s'ha trobat el mòdul del curs";
$string['nonewmodules'] = "No hi ha nous mòduls";
$string['pluginadministration'] = 'Isard VDI Desktops administració';
$string['isardvdi:addinstance'] = 'Afegir instància Isard VDI Desktops';
$string['isardvdi:view'] = 'Veure Isard VDI Desktops';
$string['view_desktop'] = 'Isard VDI Desktop';
$string['isardvdi:grade'] = 'Qualificar Isard VDI Desktops';
$string['isardvdi:viewgrades'] = 'Veure qualificacions Isard VDI Desktops';
$string['generalheading'] = 'Descripció General';
$string['generalheadingdesc'] = 'Configuració general per al funcionament del plugin';
$string['url'] = 'URL Isard VDI Desktops';
$string['kid'] = 'KEY Id';
$string['secret'] = 'Secret';
$string['maproleheading'] = 'Mapeig de rols';
$string['maproleheadingdesc'] = "Seleccioneu quins rols de Moodle corresponen al rol de l'entorn d'Isard VDI. Si el rol seleccionat correspon amb ambdós rols, serà prioritari el que apareix al formulari en un lloc superior";
$string['advanced'] = 'Rol Advanced';
$string['advanceddesc'] = 'Select the Moodle roles that correspond to the Isard VDI advanced role';
$string['userrole'] = 'Rol User';
$string['userroledesc'] = 'Select the Moodle roles that correspond to the Isard VDI user role';
$string['type'] = 'Tipus';
$string['type_help'] = "Seleccioneu el tipus de comportament del mòdul Isard VDI.<br><ul><li><strong>Jump:</strong> Salt directe a l'escriptori remot d'ISARD VDI</li></ul>";
$string['type_jump'] = 'Jump';
$string['exp'] = "Temps d'expiració";
$string['exp_desc'] = "Temps d'expiració del token des que es calcula fins que es pot utilitzar.";
$string['error_is_admin'] = "Aquest usuari és administrador, per la qual cosa no es pot redirigir a l'escriptori remot d'Isard VDI.";
$string['error_is_not_enrol'] = 'Aquest usuari no està inscrit en aquest curs.';
$string['error_not_role_valid'] = 'Aquest usuari no té vàlid role. Comproveu la configuració del connector.';
$string['otherheading'] = 'Altres configuracions';
$string['logtoken'] = 'Token en Registres';
$string['logtokendesc'] = "Si l'opció està seleccionada, apareixerà el testimoni als registres de Moodle.";
$string['privacy:metadata:userid'] = "ID de l'usuari";
$string['privacy:metadata:courseid'] = "ID del curs de l'usuari on esteu matriculat";
$string['privacy:metadata:roleisard'] = "Rol de l'usuari a l'entorn d'ISARD VDI";
$string['privacy:metadata:rolemoodle'] = "Rol de l'usuari a Moodle";
$string['privacy:metadata:username'] = "Nom d'usuari";
$string['privacy:metadata:email'] = "Email de l'usuari";
$string['privacy:metadata:fullname'] = "Nom complet de l'usuari";
$string['privacy:metadata:firstname'] = "Primer nom de l'usuari";
$string['privacy:metadata:lastname'] = "Cognom de l'usuari";
$string['privacy:metadata:cmid'] = "ID del mòdul ISARD VDI";
$string['privacy:metadata:cmname'] = "Nom del mòdul ISARD VDI";
$string['privacy:metadata:cmdescription'] = "Descripció del mòdul ISARD VDI";
$string['privacy:metadata:courseshortname'] = 'Nom curt del curs';
$string['privacy:metadata:coursefullname'] = 'Nom llarg del curs';
$string['privacy:metadata:externalpurpose'] = "Enviament de les dades de l'usuari a l'entorn extern d'ISARD VDI";
