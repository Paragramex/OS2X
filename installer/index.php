<?php
require './hf.php';
if (!file_exists('os2x_config.json')) {
	page_header("Installer", false, " OS2X ");
	require './install.php';
} else {
	page_header('Home');
	?><p>Software should be downloaded. <br><br>You are not supposed to view this page. <a href="/">Click to go home.</a> If this issue persists, contact SysAdmin / Developers. <br>If they can not fix this issue, please contact Paragram on discord: Paragram#0121</p><?php
}