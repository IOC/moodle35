# Activity Module type plugin for managing remote desktops in ISARD VDI

### EN
This plugin allows a user to jump to a remote desktop in ISARD VDI.

### ES
Este plugin permite que un usuario pueda saltar a un escritorio remoto en ISARD VDI.

### CA
Aquest connector permet que un usuari pugui saltar a un escriptori remot a ISARD VDI.

## Compatibility

This plugin version is tested for:

* Moodle 3.11.8+ (Build: 20220805) - 2021051708.04


## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

## Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/mod/isardvdi

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

## Configuration

Go to the URL:

    {your/moodle/dirroot}/admin/settings.php?section=modsettingisardvdi

  * URL Isard VDI Desktops: mod_isardvdi | url
  * KEY Id: mod_isardvdi | kid
  * Secret: mod_isardvdi | secret
  * Expiration time: mod_isardvdi | exp

    *Token expiration time from when it is calculated until it can be used.*
    
  * Role Advanced: mod_isardvdi | advancedrole

    *Select the Moodle roles that correspond to the Isard VDI advanced role*

  * Role User: mod_isardvdi | userrole

    *Select the Moodle roles that correspond to the Isard VDI user role*

  * Token in Logs:  mod_isardvdi | logtoken

    *If this option is selected, the tokens will appear in the Moodle logs.*

## Create Activity

### EN
When the activity is created, the user can select the type of behavior. In this version, only the 'jump' type is developed, which allows the user to jump to the Isard VDI remote desktop.

### ES
Cuando se crea la actividad, el usuario puede seleccionar el tipo de comportamiento. En esta versión, solo se encuentra desarrollado el tipo 'jump', que permite al usuario dar un salto al escritorio remoto de Isard VDI.

### CA
Quan creeu l'activitat, l'usuari pot seleccionar el tipus de comportament. En aquesta versió, només es troba desenvolupat el tipus 'jump', que permet a l'usuari fer un salt a l'escriptori remot d'Isard VDI.

## View Activity

### EN
The user will be redirected to the Isard VDI remote desktop. If there are any errors or restrictions, the user will be notified.

### ES
El usuario será redirigido al escritorio remoto de Isard VDI. Si hubiera algún error o restricción, el usuario será notificado.

### CA
L'usuari serà redirigit a l'escriptori remot d'Isard VDI. Si hi ha algun error o restricció, l'usuari serà notificat.

