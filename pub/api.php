<?php
/*
 *  Jyraphe, your web file repository
 *  Copyright (C) 2008  Julien "axolotl" BERNARD <axolotl@magieeternelle.org>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

define('JYRAPHE_ROOT', dirname(__FILE__) . '/');
define('DEBUG', false);

require(JYRAPHE_ROOT . 'lib/config.php');
require(JYRAPHE_ROOT . 'lib/settings.php');
require(JYRAPHE_ROOT . 'lib/functions.php');

/* check if the destination dirs are writable */
$writable = is_writable(VAR_FILES) && is_writable(VAR_LINKS) && is_writable(VAR_TRASH);

$res = array();
if($writable) {

  $key = '';

  $time = time();
  $time += JYRAPHE_42MINUTES;
  $fr_time = "42 minutes";

  $res = jyraphe_upload($_FILES['file'], isset($_POST['one_time_download']), $key, $time, $cfg);
}
/* Checking for errors. */
if(!is_writable(VAR_FILES)) {
  add_error (_('The file directory is not writable!'), VAR_FILES);
}

if(!is_writable(VAR_LINKS)) {
  add_error (_('The link directory is not writable!'), VAR_LINKS);
}

if(!is_writable(VAR_TRASH)) {
  add_error (_('The trash directory is not writable!'), VAR_TRASH);
}

/* Check if the install.php script is still in the directory. */
if (file_exists(JYRAPHE_ROOT . 'install.php')) {
  add_error (_('Installer script still present'),
                       _('Please make sure to delete the installer script "install.php" before continuing.'));
}

if(!has_error() && !empty($res)) {
  if($res['error']['has_error']) {
      add_error (_('Une erreur est survenue.'), $res['error']['why']);
  } else {
      echo $res['link']."\n";
  }
}

if(has_error ()) {
    show_errors ();
}

if(!has_error () && $writable) {
}