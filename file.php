<!doctype html>
<html>
<title>Mon Editeur Online</title>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0">
<link rel=stylesheet href="highlight/doc/docs.css">

<link rel="stylesheet" href="highlight/lib/codemirror.css">
<link rel="stylesheet" href="highlight/theme/night.css">
<script src="highlight/lib/codemirror.js"></script>
<script src="highlight/mode/xml/xml.js"></script>
<script src="highlight/addon/display/fullscreen.js"></script>
<style>.CodeMirror.cm-s-night {margin-bottom: 1em}
    .btn, a.btn {
        background-color: #e79e4b;
        border-radius: 5px;
        border: none;
        padding: 5px 1.8em;
        width: 150px;
        cursor: pointer;
        color: #272727;
        font-weight: 600;
        text-decoration: none;
    }
    .btn[disabled] {
        pointer-events: none;
        background-color: dimgrey;
        opacity: 0.5;
    }
    .btn.btn-see{background-color: #a9a9a9;}</style>
<body style="
    text-align: center;
    overflow: auto;
    padding-bottom: 40px;
    display: block;
    height: -webkit-fill-available;
    ">
	<div style="display: inline-flex;">
		<h1>Edit Php</h1>
		<div style="margin: auto 2em;">
			<?php
			$dir = "./source";
			$files1 = scandir($dir);
			$length = (count($files1) - 2);
			echo $length . " fichier(s)<br>";
			echo '<SELECT name="nom" size="1" onchange="window.location.href = &#039;file.php?file=&#039;+this.value"><option disabled selected value> -- select un fichier -- </option>';

			for ($i = 2; $i < $length + 2; $i++) {
				echo "<option>" . $files1[$i] . "</option>";
			}
			echo "</SELECT></div></div>";

			$myfile = "source/exemple.php";
			$del = 0;
			if (!empty($_GET['file'])) {
				$myfile = "source/" . $_GET['file'];
				if (!empty($_GET['delet'])) {
					if ($_GET['delet'] = "confirm") {
						unlink($myfile);
						$del = 1;
					}
				}
				if (!$del) {
					if (!empty($_POST['text'])) {
						echo "<p>Fichier Modifié</p>";
						if (file_exists($myfile)) {
							file_put_contents($myfile, $_POST['text']);
						} else {
							$fp = fopen($myfile, "w+");
							fwrite($fp, $_POST['text']);
							fclose($fp);
						}
					} else {
						if (!file_exists($myfile)) {
							$fp = fopen($myfile, "w+");
							fwrite($fp, "");
							fclose($fp);
						}
					};
					if (!empty($_GET['file'])) {
						echo '<div style="
						display: flex;position: relative;
						"><div style="
						margin: 2px 5px 0 3em;
						padding: 2px 10px;
						width: fit-content;
						background-color: #6c6b4f;
						color: #ffffff;
						border-top-left-radius: 10px;
						border-top-right-radius: 10px;
						">' . $_GET['file'] . '</div><a href="?file=' . $_GET['file'] . '&delet=confirm" style="
						background: #e05a5a;
						color: white;
						width: 40px;
						height: 30px;
						border-top-left-radius: 10px;
						border-top-right-radius: 10px;
						text-decoration: none;
						right: 40px;
						position: absolute;
						font-weight: bolder;
						font-family: sans-serif;
						line-height: 2;
						">X</a></div>';
					}
				} else {
					header("Location: /file/file.php");
					exit();
				}

				echo '<form method="post" style="padding-bottom: 1em"><textarea id="code" rows="5" name="text" style="width:90vw;height: 70vh;background: #cfcbc5;padding: 1em 2em;box-sizing: border-box;margin: auto;margin-bottom: 1em;display: block;">';
				$texte = file_get_contents($myfile);
				echo $texte . '</textarea>
                <label for="save" class="btn btn-save" disabled="true">Enregistrer</label>
				<input type="submit" id="save" name="envoyer" style="display: none"/>
				<a target="_blank" href=' . $myfile . ' class="btn btn-see">Voir</a>
				</form>';
			} else {
				echo "<div><div>aucun fichier selectionner</div><input type='text' placeholder='Nom fichier avec extension' style='width: 200px;' name='filename'>
				<button onclick=" . '"' . "window.location.href=('file.php?file='+(document.getElementsByName('filename')[0].value))" . '"' . ">Créer</button></div>";
			} ?>
</body>
<script>
	var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
		lineNumbers: true,
		theme: "night",
		extraKeys: {
			"F11": function(cm) {
				cm.setOption("fullScreen", !cm.getOption("fullScreen"));
			},
			"Esc": function(cm) {
				if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
			}
		}
	});
</script>
