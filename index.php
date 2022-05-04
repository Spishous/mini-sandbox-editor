<?php
$dir = __DIR__."/source";
if (isset($_GET['delet']) && isset($_GET['file']) && $_GET['delet'] = "confirm") {
    $filepath = $_GET['file'];
    if (file_exists($dir . '/' . $filepath)) {
        unlink($dir . '/' . $filepath);
    }
    header("Location: ./");
    exit();
}else{
?>

<!doctype html>
<html lang="fr">
<title>Mon Editeur Online</title>
<link rel="icon" href="icon.svg">
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0">

<link rel="stylesheet" href="highlight/theme/atom-one-dark.css">
<style>
    .file.php svg {color:tomato}
    .file.twig svg {color:#a6c56a}
    .file.css svg {color:#36bfe8}
    .file.js svg {color:#ff8c2c}
    .file.html svg {color:blanchedalmond}
    .file.svg svg {color:darksalmon}
    a.file:hover {background:#2f3542}
    select,input,button {padding:0.5em 1em;border-radius:4px;border:1px solid grey;outline:none}
    button,select {cursor:pointer}
    pre {position:relative;display:flex;flex-direction:column;font-size:75%;margin:0;max-width:100%}
    pre code.hljs {position:absolute;width:100%;height:100%;overflow:hidden;padding-bottom:1rem;border-top-right-radius:8px;border-bottom-right-radius:8px;box-sizing:border-box;overflow-y:scroll}
    pre textarea {margin-top:0;margin-bottom:0;width:100%!important;height:calc(100vh - 300px);color:transparent;background:transparent;position:relative;top:0;left:0;padding:.5em;caret-color:#fff;border:none;overflow-y:scroll}
    code.hljs, pre textarea {white-space:pre-wrap}
    .linedwrap {display:flex;overflow:hidden;position:relative;width:100%;box-shadow:0 0 5px #1f1f1f;margin-bottom:1em}
    .linedtextarea {position:relative;flex:1;display:flex}
    .lines {padding:.5em;min-width:1.5em;width:min-content!important;line-height:inherit;text-align:end;height:calc(100vh - 300px)!important;overflow:hidden;border-right:1px solid #33495f;background-color:#a9a9a9;border-bottom-left-radius:7px;border-top-left-radius:7px;text-align:end;color:#282c34}
    pre textarea:focus {outline:none}
    .popup pre textarea {margin-top:0;margin-bottom:0;height:230px;resize:none;overflow:auto}
    .act-btn {display:flex;justify-content:center;column-gap:1em;margin:1em;font-family:sans-serif}
    .btn, a.btn {background-color:#e79e4b;border-radius:5px;border:none;padding:7px 1.8em;width:150px;cursor:pointer;color:#212121;font-weight:600;transition:background .1s;user-select:none;text-decoration:none}
    .btn.btn-save:hover {background:#f9b567}
    .btn.btn-save:active {background-color:#ce8b3e}
    .btn.btn-see {background-color:#a9a9a9}
    code,textarea,.lines {font-family:SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;font-size:12px;text-align:left}
    body {text-align:center;padding-bottom:40px;display:block;font-family:sans-serif;background:#24272c;color:#bbb;overflow:hiddenmargin:8px}
    .tools-info {display:flex;position:relative;height:auto;font-size:14px}
    .tools-info .add-file {margin:0 -5px 0 30px;padding: 6px 9px;width:fit-content;background-color: #ca9a64;color: #24272c;box-shadow:0 0 4px #1e1e1e;z-index:2;font-weight:600;font-size:18px;line-height:.9em;border-top-left-radius: 7px;border-top-right-radius: 7px;text-decoration:none;;}
    .tools-info .name-info {margin:2px 5px 0;padding:5px 15px;width:fit-content;background-color:#a9a9a9;color:#282c34;font-weight:600;border-top-left-radius:10px;border-top-right-radius:10px}
    .tools-info .suppr {background:#8f3131;color:#fff;width:40px;height:100%;border-top-left-radius:10px;border-top-right-radius:10px;text-decoration:none;right:40px;position:absolute;font-weight:bolder;font-family:sans-serif;line-height:2}
    .tools-info .suppr svg {height:20px; padding:5px}
    span.savefile {display:flex;align-items:center;padding:0 10px;opacity:0}
    span.savefile.show {animation:fadeout 3s forwards}
    .name-info.unsave:after {content:'*';display:inline-block}
    .view-files a.file {width:6em;height:6em;display:flex;flex-direction:column;color:unset;text-decoration:none;justify-content:flex-start;position:relative;margin:0.5em;border-radius:6px}
    .view-files {display:flex;flex-flow:wrap;justify-content:center;margin-top:1em}
    .view-files a.file span {align-self:flex-end;color:lightgrey;font-size:0.9em;width:100%;height:2.4em;overflow:hidden;text-overflow:ellipsis;word-break:break-word;text-decoration:underline}
    .view-files a.file svg {height:33px;
        /* display:none; */
        padding:0.8em;
        /* padding-top:0; */
    .name-info.unsave {text-decoration: underline}
    }
    button {background-color:#4cd0f9;font-weight:700;border-color:#3c869c;transition:background .1s}
    button:hover {background-color:#5ed6ff}
    button:active {background-color:#49b8e8}
    @keyframes fadeout {
        0% {opacity:0}
        12% {opacity:1}
        80% {opacity:1}
        100% {opacity:0}
    }
    .btn[disabled] {pointer-events:none;background-color:dimgrey;opacity:0.5}
</style>
<script src="highlight/jquery.min.js"></script>
<script src="highlight/highlight.pack.js"></script>
<script src="highlight/editor-area.js?"<?=time()?>></script>
<script>hljs.initHighlightingOnLoad();</script>
<body>
<div style="display: flex;justify-content: space-between;max-width: 100%;overflow: hidden;">
    <h1 style="padding: 0 1em">Editor</h1>
    <div style="margin: auto">
        <?php
        function scan_dir($dir) {
            $ignored = array('.', '..', '.svn', '.htaccess');

            $files = array();
            if(!is_dir($dir)){
                mkdir($dir);
            }
            foreach (scandir($dir) as $file) {
                if (in_array($file, $ignored)) continue;
                $files[$file] = filemtime($dir . '/' . $file);
            }
            arsort($files);
            $files = array_keys($files);

            return ($files) ? $files : [];
        }
        $files1 = scan_dir($dir);
        $length = count($files1);
        $list= $length . " fichier(s)<br>";
        $list.= '<SELECT name="nom" size="1" onchange="window.location.href = \'?file=\'+this.value"><option disabled selected value> -- select un fichier -- </option>';

        for ($i = 0; $i < $length; $i++) {
            $list.= "<option>" . $files1[$i] . "</option>";
        }
        $list.= '</SELECT>';

        echo $list.'</div><div style="padding: 0 4em"></div></div>';

        if (isset($_GET['file'])) {
            $file=$_GET['file'];
            $filepath = "source/" . $file;
            $ext = pathinfo($filepath, PATHINFO_EXTENSION);
            $lang = "xml";
            switch ($ext) {
                case "css":
                    $lang="css";
                    break;
                case "php":
                    $lang="php-template";
                    break;
                case "js":
                    $lang="javascript";
                    break;
                case "json":
                    $lang="json";
                    break;
                case "ini":
                    $lang="ini";
                    break;
            };
            $valuefile="";$disable_view='';
            if (isset($_POST['text'])) {
                if (file_exists($filepath)) {
                    file_put_contents($filepath, $_POST['text']);
                } else {
                    $fp = fopen($filepath, "w+");
                    fwrite($fp, $_POST['text']);
                    fclose($fp);
                }
                $_POST = [];
            } else {
                if (!file_exists($filepath)) {
                    /*$fp = fopen($filepath, "w+");
                    fwrite($fp, "");
                    fclose($fp);*/
                    $disable_view=' disabled';
                }else{
                    $file_res = fopen($filepath, "r") or die("Unable to open file!");
                    $valuefile = @fread($file_res, filesize($filepath));
                    fclose($file_res);
                }
            };
                echo '<div class="tools-info"><a class="add-file" href="."><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16" height="14" width="14">
  <path d="M8 0a1 1 0 0 1 1 1v6h6a1 1 0 1 1 0 2H9v6a1 1 0 1 1-2 0V9H1a1 1 0 0 1 0-2h6V1a1 1 0 0 1 1-1z"></path>
</svg></a><div class="name-info">' . $file . '</div><span class="savefile">Save</span>
<a href="?file=' . $file . '&delet=confirm" class="suppr" title="supprimer"  onclick="return confirm(\'Confirmez-vous la suppression?\')"><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="trash-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-trash-alt fa-w-14 fa-3x"><path fill="currentColor" d="M32 464a48 48 0 0 0 48 48h288a48 48 0 0 0 48-48V128H32zm272-256a16 16 0 0 1 32 0v224a16 16 0 0 1-32 0zm-96 0a16 16 0 0 1 32 0v224a16 16 0 0 1-32 0zm-96 0a16 16 0 0 1 32 0v224a16 16 0 0 1-32 0zM432 32H312l-9.4-18.7A24 24 0 0 0 281.1 0H166.8a23.72 23.72 0 0 0-21.4 13.3L136 32H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16z" class=""></path></svg></a></div>';

            echo '<form method="post" style="padding-bottom: 1em">
    <pre>
    <div class="linedtextarea"><code class="' . $lang . '">';
            echo htmlentities($valuefile);
            echo '</code>
                <textarea class="lined editor-css" spellcheck="false" name="text"></textarea></div>
                <div class="act-btn"><label for="save" class="btn btn-save" disabled>Enregistrer</label>
				<input type="submit" id="save" name="envoyer" style="display: none"/>
				<a target="_blank" href=' . $filepath . ' class="btn btn-see" '.$disable_view.'>Voir</a></div>

				</form>';
        } else {
            echo "<div><input type='text' id='filename' autocomplete='false' placeholder='Nom fichier avec extension' style='width: 200px;' name='filename'>
				<button id='createFile' onclick=" . '"' . "window.location.href=('?file='+(document.getElementsByName('filename')[0].value))" . '"' . ">Créer</button></div>
				<br>";
            $list="";
            for ($i = 0; $i < $length; $i++) {
                $ext = pathinfo('./source/'.$files1[$i], PATHINFO_EXTENSION);
                $iconfile='<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="file-code" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="svg-inline--fa fa-file-code fa-w-12 fa-7x"><path fill="currentColor" d="M384 121.941V128H256V0h6.059c6.365 0 12.47 2.529 16.971 7.029l97.941 97.941A24.005 24.005 0 0 1 384 121.941zM248 160c-13.2 0-24-10.8-24-24V0H24C10.745 0 0 10.745 0 24v464c0 13.255 10.745 24 24 24h336c13.255 0 24-10.745 24-24V160H248zM123.206 400.505a5.4 5.4 0 0 1-7.633.246l-64.866-60.812a5.4 5.4 0 0 1 0-7.879l64.866-60.812a5.4 5.4 0 0 1 7.633.246l19.579 20.885a5.4 5.4 0 0 1-.372 7.747L101.65 336l40.763 35.874a5.4 5.4 0 0 1 .372 7.747l-19.579 20.884zm51.295 50.479l-27.453-7.97a5.402 5.402 0 0 1-3.681-6.692l61.44-211.626a5.402 5.402 0 0 1 6.692-3.681l27.452 7.97a5.4 5.4 0 0 1 3.68 6.692l-61.44 211.626a5.397 5.397 0 0 1-6.69 3.681zm160.792-111.045l-64.866 60.812a5.4 5.4 0 0 1-7.633-.246l-19.58-20.885a5.4 5.4 0 0 1 .372-7.747L284.35 336l-40.763-35.874a5.4 5.4 0 0 1-.372-7.747l19.58-20.885a5.4 5.4 0 0 1 7.633-.246l64.866 60.812a5.4 5.4 0 0 1-.001 7.879z" class=""></path></svg>';
                $list.= "<a href='?file=".$files1[$i]."' class='file $ext'>$iconfile<span>" . $files1[$i] . "</span></a>";
            }
            echo "<div class='view-files'>".$list."</div>";
        }
        ?>
</body>
<script>
    $(function() {
        $('#filename').on('keyup paste',function(e){
            if(e.keyCode===13){// Action click Entrer
                if($('a.file').has(':visible').length===0){
                    $('#createFile')[0].click();
                }
                if($('a.file').has(':visible').length===1){
                    $('a.file').has(':visible')[0].click();
                }
            }
            $val=$(this).val().toLowerCase();
            if($val.length>=1){
                $('a.file').each(function(){
                    if($(this).text().toLowerCase().includes($val)){
                        $(this).show();
                    }else{
                        $(this).hide();
                    }
                })
            }else{
                $('a.file').show();
            }

        })
        $('form').submit(function(e){
            e.preventDefault();
            $('span.savefile').removeClass('show');
            $.ajax({
                url : '?<?=$_SERVER["QUERY_STRING"]?>',
                type : 'POST',
                data: $(this).serialize(),
                success:function(e){
                    $('span.savefile').addClass('show');
                    $('.name-info').removeClass('unsave');
                }
            })
        });
        $.fn.linedtextarea = function(options) {
            var opts = $.extend({}, $.fn.linedtextarea.defaults, options);
            var fillOutLines = function(codeLines, h, lineNo){
                while ( (codeLines.height() - h ) <= 0 ){
                    if ( lineNo == opts.selectedLine )
                        codeLines.append("<div class='lineno lineselect'>" + lineNo + "</div>");
                    else
                        codeLines.append("<div class='lineno'>" + lineNo + "</div>");
                    lineNo++;
                }
                while ( (codeLines.height() - h ) > 0 ){
                    codeLines.find('.lineno:last-child').remove();
                    lineNo--;
                }
                return lineNo;
            };

            return this.each(function() {
                var lineNo = 1;
                var textarea = $(this);

                /* Turn off the wrapping of as we don't want to screw up the line numbers */
                textarea.attr("wrap", "off");
                textarea.css({resize:'none'});
                var originalTextAreaWidth	= textarea.outerWidth();

                /* Wrap the text area in the elements we need */
                //textarea.wrap("<div class='linedtextarea'></div>");
                //$('code').wrap("<div class='linedtextarea'></div>");
                var linedTextAreaDiv	= textarea.parent().wrap("<div class='linedwrap'></div>");
                var linedWrapDiv 			= linedTextAreaDiv.parent();

                linedWrapDiv.prepend("<div class='lines' style='width:50px'></div>");

                var linesDiv	= linedWrapDiv.find(".lines");
                linesDiv.height( textarea.height() + 6 );


                /* Draw the number bar; filling it out where necessary */
                linesDiv.append( "<div class='codelines'></div>" );
                var codeLinesDiv	= linesDiv.find(".codelines");
                lineNo = fillOutLines( codeLinesDiv, linesDiv.height(), 1 );

                /* Move the textarea to the selected line */
                if ( opts.selectedLine != -1 && !isNaN(opts.selectedLine) ){
                    var fontSize = parseInt( textarea.height() / (lineNo-2) );
                    var position = parseInt( fontSize * opts.selectedLine ) - (textarea.height()/2);
                    textarea[0].scrollTop = position;
                }


                /* Set the width
                var sidebarWidth					= linesDiv.outerWidth();
                var paddingHorizontal 		= parseInt( linedWrapDiv.css("border-left-width") ) + parseInt( linedWrapDiv.css("border-right-width") ) + parseInt( linedWrapDiv.css("padding-left") ) + parseInt( linedWrapDiv.css("padding-right") );
                var linedWrapDivNewWidth 	= originalTextAreaWidth - paddingHorizontal;
                var textareaNewWidth			= originalTextAreaWidth - sidebarWidth - paddingHorizontal - 20;

                textarea.width( textareaNewWidth );
                /linedWrapDiv.width( linedWrapDivNewWidth );
                */
                /* React to the scroll event */
                textarea.scroll( function(tn){
                    var domTextArea		= $(this)[0];
                    var scrollTop 		= domTextArea.scrollTop;
                    var clientHeight 	= domTextArea.clientHeight;
                    codeLinesDiv.css( {'margin-top': (-1*scrollTop) + "px"} );
                    lineNo = fillOutLines( codeLinesDiv, scrollTop + clientHeight, lineNo );
                });


                /* Should the textarea get resized outside of our control */
                textarea.resize( function(tn){
                    var domTextArea	= $(this)[0];
                    linesDiv.height( domTextArea.clientHeight + 6 );
                });

            });
        };

        // default options
        $.fn.linedtextarea.defaults = {
            selectedLine: -1,
            selectedClass: 'lineselect'
        };
        $(".lined").linedtextarea(
            {selectedLine: 1}
        );
    });
</script>
<?php }?>
