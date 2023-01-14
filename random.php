<?php
$dir = array_diff(
    scandir('projects/', SCANDIR_SORT_NONE),     
    array('.', '..')
);
shuffle($dir);
header('Location: index.php?filename=' . urlencode($dir[array_rand($dir)]));