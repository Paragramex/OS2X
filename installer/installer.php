<?php
$a = readline('Commence install (y/n): ');

function getSoftware(string $item)
{
    $config = json_decode(file_get_contents(__DIR__ . '/os2x_software.json'));
    return $config->$item;
}

if ($a != "y"){
	echo "\n - Canceled\n\n";
} else {
	$b = getSoftware("name");
	echo "You have chosen install key: " . $b . "\n" . "";
}
?>
