<?php
if (isset($_POST['firstinstall'])) {
    $r = requiredfields(array(
        "title",
        "username",
        "password",
        "software"
    ));
    if ($r) {
?><form action="<?php
        echo $_SERVER['PHP_SELF'];
?>" method="post">
            <h2>Please Confirm</h2>
            <p>Make sure these settings are correct, and then press Install.</p><ul>
            <?php
        foreach ($_POST as $key => $value) {
            if ($key !== "firstinstall") {
?><li><strong><?php
                echo htmlspecialchars($key);
?></strong>: <?php
                echo htmlspecialchars(json_encode($value));
?></li><?php
            }
        }
?>
       </ul>
            <?php
        foreach ($_POST as $key => $value) {
            if ($key !== "firstinstall") {
                field('textareahidden', $key);
            }
        }
?>
       <input type="submit" name="install" value="Confirm and Install" /></form><?php
        exit(0);
    }
}
if (isset($_POST['install'])) {
    $r = requiredfields(array(
        "title",
        "username",
        "password",
        "software"
    ));
    if ($r) {
?><h2>Creating data files...</h2><?php
        $filesToInstall = array(
            "os2x_config.json",
            "os2x_users.json",
            "os2x_nameindex.json",
            "os2x_software.json"
        );
        foreach ($filesToInstall as $index => $fileToInstall) {
?><p>Installing file <?php
            echo $fileToInstall;
?> (<?php
            echo $index + 1;
?>/<?php
            echo count($filesToInstall);
?>, <?php
            echo 50 * ($index + 1) / count($filesToInstall);
?>%)</p><?php
            fwrite(fopen(__DIR__ . '/' . $fileToInstall, "w+"), "{}");
        }
?><p>Adding info to files - os2x_config.json (50%)...</p><?php
        $configobj                 = new stdClass;
        $configobj->title          = $_POST['title'];
        $configobj->currentuserid  = 1;
        $configobj->currentissueid = 0;
        $configobj->currentpostid  = 0;
        $configobj->installtime    = time();
        fwrite(fopen(__DIR__ . '/os2x_config.json', "w+"), json_encode($configobj));
?><p>Adding info to files - os2x_users.json (<?php
        echo 200 / 3;
?>%)...</p>
            <?php
        $userobj                     = new stdClass;
        $userobj->{"id-0"}           = new stdClass;
        $userobj->{"id-0"}->name     = $_POST['username'];
        $userobj->{"id-0"}->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $userobj->{"id-0"}->groups   = array(
            "admin",
            "user"
        );
        fwrite(fopen(__DIR__ . '/os2x_users.json', "w+"), json_encode($userobj));
        $_SESSION['logged_in']           = true;
        $_SESSION['usernumber']          = 0;
        $nameindex                       = new stdClass;
        $nameindex->{$_POST['username']} = 0;
        fwrite(fopen(__DIR__ . '/os2x_nameindex.json', "w+"), json_encode($nameindex));
?><p>Adding info to files - os2x_software.json (<?php
        echo 250 / 3;
?>%)...</p><?php
        $softwareobj       = new stdClass;
        $softwareobj->name = $_POST['software'];
        if ($_POST['software'] === 'Custom_Key')
            $softwareobj->name = $_POST['software_name'];
        if ($_POST['software'] !== "Custom_Key")
            $softwareobj->contents = file_get_contents(__DIR__ . '/' . $_POST['software'] . '.txt');
        else
            $softwareobj->contents = $_POST['software_contents'];
        fwrite(fopen(__DIR__ . '/os2x_software.json', "w+"), json_encode($softwareobj));
?><p>Install complete (100%).</p><?php
        exit(0);
    }
}
?>
<form action="<?php
echo $_SERVER['PHP_SELF'];
?>" method="post">
    <h2>General Info</h2>
    <label>Type the installation title: <?php
field('text', 'title');
?></label>
<hr />
    <h2>Set up installation administrator account</h2>
<label>Username: <?php
field('text', 'username');
?></label>
<label>Password: <?php
field('password', 'password');
?></label>
    <hr />
    <h2>Software Installer Key</h2>
    <label>Choose software:<br /> <?php
field('select', 'software', array(
    "5h8ubwkkck",
    "9ybrcy6kj5",
    "48hw81oygy",
    "pbj14ydt53",
    "6ahj5bxavc",
	"Custom_Key"
));
?></label>
    <hr />
    <h3>Specify software (optional)</h3>
    <p>If you chose "Custom_Key" for the software, please enter your software key below.</p>
    <label>software name: <?php
field('text', 'software_name');
?></label>
    <label>software contents: <br /><textarea rows="5" cols="50" name="software_contents"><?php
if (isset($_POST['software_contents']))
    echo htmlspecialchars($_POST['software_contents']);
?></textarea></label>
    <hr />
    <h2>Start installation</h2>
<label>Install: <input type="submit" value="Install" name="firstinstall" /></label>
</form>