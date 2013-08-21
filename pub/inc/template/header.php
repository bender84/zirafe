<?php
header("Vary: Accept");
header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $_SERVER['HTTP_HOST'];?> - Hébergement temporaire de fichiers</title>
	<link href="<?php echo $cfg['web_root'] . '/media/style.css'; ?>" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?php echo $cfg['web_root'] . 'media/jquery.js'; ?>"></script>
</head>
<body>

<div id="content">
	<h1><a href="<?php echo $cfg['web_root']; ?>"><img src="<?php echo $cfg['web_root'] . 'media/answer_to_life.png'; ?>" alt="answer to life"/></a></h1>
	<h2>Hébergement temporaire de fichiers</h2>
