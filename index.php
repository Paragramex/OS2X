<?php 
function cleanFilename($stuff) {
	$illegal = array(" ","?","/","\\","*","|","<",">",'"');
	// legal characters
	$legal = array("-","_","_","_","_","_","_","_","_");
	$cleaned = str_replace($illegal,$legal,$stuff);
	return $cleaned;
}
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'download') {
        header('Access-Control-Allow-Origin: *');
        if (!isset($_GET['filename'])) {
            die('No filename specified');
        }
        $name = cleanFilename($_GET['filename']);
        if (file_exists("projects/$name/download.json")) {
            header('Content-Type: application/json');
            exit(file_get_contents("projects/$name/download.json"));
        }
        if (file_exists("projects/$name/download.js")) {
            header('Content-Type: text/javascript');
            exit(file_get_contents("projects/$name/download.js"));
        }
        if (file_exists("projects/$name/download.css")) {
            header('Content-Type: text/css');
            exit(file_get_contents("projects/$name/download.css"));
        }
			if (file_exists("projects/$name/download.php")) {
            header('Content-Type: text/php');
            exit(file_get_contents("projects/$name/download.php"));
        }
        die('Bad filename');
    }
    die('Unrecognized action');
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>CDN</title>
      <link href="style.css" rel="stylesheet" />
  </head>
  <body id="baudy">
      <div id="wrapper">
          <div id="yet-another-wrapper">
      <div id="navigation">
    <span id="header-text"><a href="index.php">CDN</a></span>
				<br>
          <span class="header-section">Navigation</span>
          <ul>
              <li><a href="index.php">All packages</a></li>
              <li><a href="random.php">Random package</a></li>
          </ul>
          <span class="header-section">Random 2 sites using these</span>
          <ul>
              <?php 
    $sites = array(
        "https://random.paragram.repl.co" => "Random",
        "https://www.paragram.repl.co" => "Stellar",
        "https://forum.paragram.repl.co" => "Forum",
    );
$indices = array_rand($sites, 2);
foreach ($indices as $index) {
    ?><li><a href="<?php echo htmlspecialchars($index); ?>"><?php echo htmlspecialchars($sites[$index]); ?></a></li><?php
}
    ?>
          </ul>
          <span class="header-section">Get help</span>
<ul>
    <li><a href="https://forum.paragram.repl.co">Help Forum</a></li>
</ul>
      </div>
      <div id="body-content">
    <?php 
    if (!isset($_GET['filename'])) {
        ?><h1>Browse projects</h1>
        <ul class="cards"><?php 
        $projects = scandir("projects/");
        foreach ($projects as $project) {
            if ($project === "." || $project === '..') continue;
            ?><li>
                <h2><?php echo htmlspecialchars(file_get_contents("projects/$project/title.txt")); ?></h2>
                <p>Filename: <?php echo htmlspecialchars($project); ?></p>
                <p><?php echo htmlspecialchars(substr(strip_tags(file_get_contents("projects/$project/description.txt")), 0, 50)); ?>...</p>
                <p><a href="index.php?filename=<?php echo htmlspecialchars(urlencode($project)); ?>" class="dl-link">More information/download</a></p>
            </li><?php
        }
        ?></ul>
        <?php
    } else {
        $name = cleanFilename($_GET['filename']);
        if (!is_dir("projects/$name")) die('<h1>Project not found</h1>Project not found. Return <a href="index.php">home</a> for a list of all projects.');
        ?>
        <h1><?php echo htmlspecialchars(file_get_contents("projects/$name/title.txt")); ?></h1>
            <p><a href="index.php">Back to all projects</a></p>

      <a href="#download" class="dl-link">Download</a>
        <h2>Description</h2>
      <div class="desc"><?php echo htmlspecialchars(file_get_contents("projects/$name/description.txt")); ?></div>
      <h2>Author</h2>
      <p>Made by:<br />
      <strong><?php echo htmlspecialchars(file_get_contents("projects/$name/author.txt")); ?></strong></p>
      <p>License: <?php echo htmlspecialchars(file_get_contents("projects/$name/license.txt")); ?></p>
      <details>
          <summary>Show license text</summary>
          <pre><?php echo htmlspecialchars(file_get_contents("projects/$name/licensetext.txt")); ?></pre>
      </details>
      <h2 id="download">Get files</h2>
        <a href="index.php?action=download&amp;filename=<?php echo htmlspecialchars(urlencode($name)); ?>" class="dl-link">Download CSS/JS/PHP File</a><br />
      <label>URL for embedding:<br />
      <input type="text" oninput="this.value = this.getAttribute('value');" value="https://CDN.paragram.repl.co/index.php?action=download&amp;filename=<?php echo htmlspecialchars(urlencode($name)); ?>" />
      </label>
        <?php
        if (file_exists("projects/$name/download.js")) {
            ?><br /><label>Embed:<br />
            <input oninput="this.value = this.getAttribute('value');" value="<?php echo htmlspecialchars('<script src="https://CDN.paragram.repl.co/index.php?action=download&filename=' . htmlspecialchars(urlencode($name)) . '"></script>'); ?>" type="text" /></label><?php
        }
			if (file_exists("projects/$name/download.php")) {
            ?><br /><label>PHP Library:<br />
            <input oninput="this.value = this.getAttribute('value');" value="<?php echo htmlspecialchars('https://CDN.paragram.repl.co/index.php?action=download&filename=' . htmlspecialchars(urlencode($name))); ?>" type="text" /></label><?php
        }
        if (file_exists("projects/$name/download.css")) {
            ?><br /><label>Use this CSS:<br />
            <input oninput="this.value = this.getAttribute('value');" value="<?php echo htmlspecialchars('<link href="https://CDN.paragram.repl.co/index.php?action=download&filename=' . htmlspecialchars(urlencode($name)) . '" rel="stylesheet" />'); ?>" type="text" /></label><?php
        }
    }
    ?>
      </div>
  </div>
</div>
  </body>
</html>