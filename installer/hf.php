<?php
session_start();
session_regenerate_id(false);
register_shutdown_function('page_footer');
function page_header(string $title, bool $appendname = true, string $ns = 'Main')
{
    $GLOBALS['starttime'] = microtime(true);
?><!DOCTYPE html><html><head><style type="text/css">
        <?php
    echo str_replace(array(
        "\n",
        "\t",
        " "
    ), "", file_get_contents(__DIR__ . '/style.css'));
?></style><meta name="viewport" content="width=device-width,initial-scale=1.0" />
        <title><?php
    if (file_exists(__DIR__ . '/os2x_config.json')) {
        displayTitle($title, true);
    } else {
        displayTitle($title, false);
    }
?></title>
    </head><body>
    <div class="header"><h1><span class="tag"><?php
    echo htmlspecialchars($ns);
?></span>: <?php
    echo htmlspecialchars($title);
?></h1>
    <div><?php
    //implament system login to check if user has checked out my software from my servers, later, maybe, idk, sure, one day. On god though, never gonna happen 😅
	
    /*
if (isset($_SESSION['logged_in'])) {
    $u = getUserById($_SESSION['usernumber']);
    ?><b>Logged in as: </b><?php 
    echo htmlspecialchars($u->name);
    ?> <a href="logout.php">(log out)</a><?php
    } else {
    ?>Not logged in (<a href="login.php">log in</a> | <a href="signup.php">Create account</a>)<?php
    } 
*/
?></div></div><?php
}
function page_footer()
{
    $GLOBALS['donetime'] = microtime(true);
?><footer>
        End of page, page load speed: <?php
    echo (float) $GLOBALS['donetime'] - $GLOBALS['starttime'];
?> seconds, memory consumed: <?php
    echo memory_get_usage();
?> bytes.
    </footer></body></html><?php
}
function get_config_item(string $item)
{
    $config = json_decode(file_get_contents(__DIR__ . '/os2x_config.json'));
    return $config->$item;
}
function displayTitle(string $title, bool $appendname = true)
{
    echo htmlspecialchars($title);
    if ($appendname) {
?> - <?php
        echo htmlspecialchars(get_config_item('title'));
    }
}
function field(string $type, string $name, array $options = array())
{
    if ($type != "checkbox" && $type != "select" && $type != "textareahidden" && $type != "textarea") {
?><input type="<?php
        echo htmlspecialchars($type);
?>" name="<?php
        echo htmlspecialchars($name);
?>" value="<?php
        if (isset($_POST[$name])) {
            echo htmlspecialchars($_POST[$name]);
        }
?>" /><?php
    }
    if ($type == "select") {
?><select open="open" size="10" name="<?php
        echo htmlspecialchars($name);
?>"><?php
        foreach ($options as $option) {
?><option <?php
            if (isset($_POST[$name])) {
                if ($_POST[$name] === $option) {
?>selected="selected" <?php
                }
            }
?>value="<?php
            echo htmlspecialchars($option);
?>"><?php
            echo htmlspecialchars($option);
?></option><?php
        }
?></select><?php
    }
    if ($type == 'textareahidden') {
?><label hidden="hidden">DO NOT TOUCH THIS FIELD.<textarea name="<?php
        echo htmlspecialchars($name);
?>"><?php
        if (isset($_POST[$name])) {
            echo htmlspecialchars($_POST[$name]);
        }
?></textarea></label><?php
    }
    if ($type == 'textarea') {
?><textarea rows="5" cols="50" name="<?php
        echo htmlspecialchars($name);
?>"><?php
        if (isset($_POST[$name])) {
            echo htmlspecialchars($_POST[$name]);
        }
?></textarea><?php
    }
}
function requiredFields(array $fields)
{
    $missingfields = array();
    foreach ($fields as $field) {
        if (!isset($_POST[$field])) {
            array_push($missingfields, $field);
        } else {
            if ($_POST[$field] == '') {
                array_push($missingfields, $field);
            }
        }
    }
    if (count($missingfields) > 0) {
?><div class="errorbox">Please fill out the following required fields:
        <ul><?php
        foreach ($missingfields as $missingfield) {
?><li><?php
            echo htmlspecialchars($missingfield);
?></li>
        <?php
        }
?></ul></div><?php
    }
    return count($missingfields) === 0;
}
function getUserById(int $id)
{
    $user = json_decode(file_get_contents(__DIR__ . '/os2x_users.json'));
    return $user->{"id-" . $id};
}