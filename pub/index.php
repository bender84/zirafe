<?php

/*
 * This file is part of Zirafe.
 *
 * Zirafe is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Zirafe is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Zirafe. If not, see <http://www.gnu.org/licenses/>.
 */

define('IN_ZIRAFE', 1);
require_once "./inc/init.php";

/* check if the destination dirs are writable */
$writable = is_writable(VAR_FILES) && is_writable(VAR_LINKS) && is_writable(VAR_TRASH); //TODO: create a function for that and more

$res = array();
if($writable && isset($_POST['zirafe']))
{
	$key = $_POST['key'];

	if(isset($_POST['expiration']))
	{
		$expiration = $_POST['expiration'];
		if(isset($cfg['expiration_time'][$expiration]))
		{
			$best_before = time() + $cfg['expiration_time'][$expiration];
			$human_expiration_time = $expiration;
		}
	}

	if(!isset($best_before))
	{
		$best_before = time() + date_to_seconds($cfg['default_expiration_time_config']);
		$human_expiration_time = $cfg['default_expiration'];
	}

	$res = zirafe_upload($_FILES['file'], isset($_POST['one_time_download']), $key, $best_before, $cfg);
}

require ZIRAFE_ROOT."inc/template/header.php";

/* Checking for errors. */
if(!is_writable(VAR_FILES))
{
	add_error (_('The file directory is not writable!'), VAR_FILES);
}

if(!is_writable(VAR_LINKS))
{
	add_error (_('The link directory is not writable!'), VAR_LINKS);
}

if(!is_writable(VAR_TRASH))
{
	add_error (_('The trash directory is not writable!'), VAR_TRASH);
}

/* Check if the install.php script is still in the directory. */
if (!file_exists(ZIRAFE_ROOT."inc/config.php"))
{
	add_error (_('Installer script still present'),
	 _('Please make sure to delete the installer script "install.php" before continuing.'));
}

if(!has_error() && !empty($res))
{
	if($res['error']['has_error'])
	{
		add_error (_('Une erreur est survenue.'), $res['error']['why']);
	}
	else
	{
		$link = $cfg['web_root'];
		$link .= $res['link'];

		$ext = pathinfo($res['final_name'], PATHINFO_EXTENSION);
		echo '<div class="message" id="links">' . NL;
		echo '<p class="ok">' . _('Fichier envoyé ! Il est maintenant disponible pour ') .$human_expiration_time. _(' à ces adresses :'). '</p>' . NL;
		echo '<table>'. NL;		
		echo '	<tr>'. NL;
		echo '		<td class="label"><strong><a href="' . $link . '/' . rawurlencode($res['file']) . '">Lien</a> long direct</strong></td>'. NL;
		echo '		<td><input type="text" readonly="readonly" value="' . $link . '/' . rawurlencode($res['file']) . '"/></td>'. NL;
		echo '	</tr>'. NL;
		
		if(!empty($ext))
		{
			echo '	<tr>'. NL;
			echo '		<td class="label"><strong><a href="' . $link . '.' . $ext.'">Lien</a> court direct</strong></td>'. NL;
			echo '		<td><input type="text" readonly="readonly" value="' . $link . '.' . $ext.'"/></td>'. NL;
			echo '	</tr>'. NL;
		}
		
		echo '	<tr>'. NL;
		echo '		<td class="label"><a href="' . $link . '">Lien</a> court (<span class="info" title="Le lien court redirige vers le lien long direct">redirection</span>)</td>'. NL;
		echo '		<td><input type="text" readonly="readonly" value="' . $link . '"/></td>'. NL;
		echo '	</tr>'. NL;
		
		
		echo '	<tr class="hide">'. NL;
		echo '		<td class="label"><a href="' . $link . '/' . rawurlencode($res['file']) . '/download">Lien</a> long direct <span style="text-decoration:underline">avec</span> <span class="info" title="Le téléchargement est forcé. Même si le contenu est habituellement affiché par le navigateur (.png, .swf, .pdf, etc)."><strong>téléchargement</strong> forcé</span></td>'. NL;
		echo '		<td><input type="text" readonly="readonly" value="' . $link . '/' . rawurlencode($res['file']) . '/download"/></td>'. NL;
		echo '	</tr>'. NL;
		
		
		echo '	<tr class="hide">'. NL;
		echo '		<td class="label"><a href="' . $link . '/' . rawurlencode($res['file']) . '/view">Lien</a> long direct <span style="text-decoration:underline">sans</span> <span class="info" title="'. _("Le téléchargement n'est pas forcé. Si le navigateur le permet, le contenu est affiché directement.").'">téléchargement forcé</span></td>'. NL;
		echo '		<td><input type="text" readonly="readonly" value="' . $link . '/' . rawurlencode($res['file']) . '/view"/></td>'. NL;
		echo '	</tr>'. NL;
		
		
		echo '	<tr class="hide">'. NL;
		echo '		<td class="label"><a href="' . $link . '/' . rawurlencode($res['file']) . '/text">Lien</a> long direct<br/><span class="info" title="Le contenu est considéré par le navigateur comme du texte brut.">lu comme du <strong>texte brut</strong></span></td>'. NL;
		echo '		<td><input type="text" readonly="readonly" value="' . $link . '/' . rawurlencode($res['file']) . '/text"/></td>'. NL;
		echo '	</tr>'. NL;
		
		if(!empty($ext))
		{
			echo '	<tr class="hide">'. NL;
			echo '		<td class="label"><a href="' . $link . '.' . $ext.'/dl">Lien</a> court direct <span style="text-decoration:underline">avec</span> <span class="info" title="Le téléchargement est forcé. Même si le contenu est habituellement affiché par le navigateur (.png, .swf, .pdf, etc)."><strong>téléchargement</strong> forcé</span></td>'. NL;
			echo '		<td><input type="text" readonly="readonly" value="' . $link . '.' . $ext.'/dl"/></td>'. NL;
			echo '	</tr>'. NL;

			echo '	<tr class="hide">'. NL;
			echo '		<td class="label"><a href="' . $link . '.' . $ext.'/v">Lien</a> court direct <span style="text-decoration:underline">sans</span> <span class="info" title="'. _("Le téléchargement n'est pas forcé. Si le navigateur le permet, le contenu est affiché directement.").'">téléchargement forcé</span></td>'. NL;
			echo '		<td><input type="text" readonly="readonly" value="' . $link . '.' . $ext.'/v"/></td>'. NL;
			echo '	</tr>'. NL;
		}
		
		echo '	<tr class="hide">'. NL;
		echo '		<td class="label"><a href="' . $link . '.txt">Lien</a> court direct<br/><span class="info" title="Le contenu est considéré par le navigateur comme du texte brut.">lu comme du <strong>texte brut</strong></span></td>'. NL;
		echo '		<td><input type="text" readonly="readonly" value="' . $link . '.txt"/></td>'. NL;
		echo '	</tr>'. NL;
		
		echo '</table>'. NL;
		echo '<p class="options"><span>'. _("Plus d'options").'</span></p>'. NL;
		echo '<p class="best_before">' . _('Le lien restera valide jusqu\'à cette date :') . '<br /><strong>' . strftime("%c", $best_before) . '</strong>';

			if(!empty($_POST['one_time_download']) || !empty($_POST['key']))
			{
				echo '<br /><br /><span style="color: red; font-weight: bold;">N\'oubliez pas que :</span>';
			}

			if(!empty($_POST['one_time_download']))
			{
				echo ' Le lien est à usage unique.';
			}

			if(!empty($_POST['key']))
			{
				echo ' La clé est nécessaire pour le téléchargement du fichier.';
			}

		echo '</p></div>';
	}
}

if(has_error ())
{
	show_errors ();
}

if(!has_error () && $writable)
{
?>

<div id="upload">
<form enctype="multipart/form-data" onsubmit="DoSubmit();" name="zirafe_form" action="<?php echo $cfg['web_root']; ?>" method="post">
<div><input type="hidden" name="zirafe" value="<?php $_42 = rand(); echo md5($_42); ?>" /></div>
	<div><span class="title"><?php echo _('Envoyer un '); if($writable && isset($_POST['zirafe'])) { echo _('autre '); } echo _('fichier'); ?></span></div>
	<p><input size="15" type="file" name="file" /></p>
	<p class="config"><?php printf(_('Taille maximum : %dMB'), zirafe_get_max_upload_size()/(1024*1024)); ?></p>
	<p id="submit-btn"><input name="submit" type="submit" value="Envoyer"/></p>
	<p id="submit-loader" style="display:none;"><img src="./media/loader.gif"/></p>

	<hr />
	<p><label for="expiration"><?php echo _('Limite de disponibilité'); ?></label>
			<select name="expiration" id="select_time">
				<?php
					foreach ($cfg['expiration_time'] as $expiration => $expiration_time)
					{
						if ($cfg['default_expiration'] == $expiration)
						{
							echo '<option value="'.$expiration.'" selected="selected">'.$expiration.'</option>';
						}
						else
						{
							echo '<option value="'.$expiration.'">'.$expiration.'</option>';
						}
					}
				?>
			</select>
	</p>			
	<div id="moreoptions">
	<p title="Si cette case est cochée, le téléchargement du fichier ne pourra se faire qu'une seule fois."><label><?php echo _('Usage unique '); ?></label><input type="checkbox" name="one_time_download" /> </p>&nbsp;-&nbsp;
	<p title="Si une clé est entrée, elle sera demandée au moment du téléchargement."><label for="input_key"><?php echo _('Clé'); ?></label> <input type="password" name="key" id="input_key" /></p>
	</div>
</form>
</div>

<?php
}

require ZIRAFE_ROOT."inc/template/footer.php";
