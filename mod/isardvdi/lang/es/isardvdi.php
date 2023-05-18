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
 * Plugin strings are defined here 'es'.
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
$string['missingidandcmid'] = 'Módulo del curso no encontrado';
$string['nonewmodules'] = 'No existen nuevos Módulos';
$string['pluginadministration'] = 'Administración Isard VDI Desktops';
$string['isardvdi:addinstance'] = 'Añadir instancia Isard VDI Desktops';
$string['isardvdi:view'] = 'Ver Isard VDI Desktops';
$string['view_desktop'] = 'Isard VDI Desktop';
$string['isardvdi:grade'] = 'Calificar Isard VDI Desktops';
$string['isardvdi:viewgrades'] = 'Ver calificaciones Isard VDI Desktops';
$string['url'] = 'URL Isard VDI Desktops';
$string['generalheading'] = 'Descripción General';
$string['generalheadingdesc'] = 'Configuración general para el funcionamiento del plugin';
$string['kid'] = 'KEY Id';
$string['secret'] = 'Secreto';
$string['maproleheading'] = 'Mapeo de roles';
$string['maproleheadingdesc'] = 'Selecciona que roles de Moodle corresponden al rol del entorno de Isard VDI. Si el rol seleccionado corresponde con ambos roles, será prioritario el que en el formulario aparece en lugar superior';
$string['advanced'] = 'Rol Advanced';
$string['advanceddesc'] = 'Selecciona los roles de Moodle que corresponden al rol advanced de Isard VDI';
$string['userrole'] = 'Rol User';
$string['userroledesc'] = 'Selecciona los roles de Moodle que corresponden al rol user de Isard VDI';
$string['type'] = 'Tipo';
$string['type_help'] = 'Seleccione el tipo de comportamiento del módulo Isard VDI.<br><ul><li><strong>Jump:</strong> Salto directo al escritorio remoto de ISARD VDI</li></ul>';
$string['type_jump'] = 'Jump';
$string['exp'] = 'Tiempo de expiración';
$string['exp_desc'] = 'Tiempo de expiración del token desde que se cálcula hasta que puede usarse.';
$string['error_is_admin'] = 'Este usuario es administrador, por lo que no se puede redirigir al escritorio remoto de Isard VDI.';
$string['error_is_not_enrol'] = 'Este usuario no está inscrito en este curso.';
$string['error_not_role_valid'] = 'Este usuario no tiene valido role. Revisa la configuración del plugin.';
$string['otherheading'] = 'Otras configuraciones';
$string['logtoken'] = 'Token en Logs';
$string['logtokendesc'] = 'Si está opción está seleccionada, aparecerán los token en los logs de Moodle.';
$string['privacy:metadata:userid'] = 'Id del usuario';
$string['privacy:metadata:courseid'] = 'Id del curso del usuario donde está matriculado';
$string['privacy:metadata:roleisard'] = 'Rol del usuario en el entorno de ISARD VDI';
$string['privacy:metadata:rolemoodle'] = 'Rol del usuario en Moodle';
$string['privacy:metadata:username'] = 'Nombre de usuario';
$string['privacy:metadata:email'] = 'Email del usuario';
$string['privacy:metadata:fullname'] = 'Nombre completo del usuario';
$string['privacy:metadata:firstname'] = 'Primer nombre del usuario';
$string['privacy:metadata:lastname'] = 'Apellido del usuario';
$string['privacy:metadata:cmid'] = 'ID del módulo ISARD VDI';
$string['privacy:metadata:cmname'] = 'Nombre del módulo ISARD VDI';
$string['privacy:metadata:cmdescription'] = 'Descripción del módulo ISARD VDI';
$string['privacy:metadata:courseshortname'] = 'Nombre corto del curso';
$string['privacy:metadata:coursefullname'] = 'Nombre largo del curso';
$string['privacy:metadata:externalpurpose'] = 'Envío de los datos del usuario al entorno externo de ISARD VDI';
