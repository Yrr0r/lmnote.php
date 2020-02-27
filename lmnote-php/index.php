<?php
$SUBDIR = "notes";

?>

<!DOCTYPE html>
<html>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.25, maximum-scale=2, user-scalable=0" name="viewport">
<body>

<font size=5><a href="?">Reset Page</a></font> </br> </br>

<?php

$formtext=<<<EOF
<table class="tblform">
<tr>
<form action="index.php" method="post">
<caption>SendNote</caption>
<td id="t"> Title: </td>
<tr>
<td id="i"> <input type="text" name="fname" 
value="%s"/> </td>
</tr>
</tr>
<tr>
<td id="t">Text: </td>
</tr>
<tr>
<td> <textarea style="" name="content" rows="5"/>%s</textarea> </td>
</tr>
<td id="i"> <input type="submit" value="SUBMIT" /> </tr>
</form>
</table>
EOF; // Vars in order: defaultTitle, textArea

$fname=""; $content=""; 
if(array_key_exists("fname",$_POST) ) $fname=$_POST['fname'];
if(array_key_exists("content",$_POST) ) $content=$_POST['content'];
$showfile=""; 
if(array_key_exists("showfile",$_GET) ) $showfile=$_GET['showfile'];

if($fname == "" && $content == "" && $showfile=="" ){
    //welcome page
    $timestring=date("Ymd-H.i.s");
    echo sprintf($formtext,$timestring,"");
    echo "Server Timezone: ", date("e"); //get timezone printed
    listfile($SUBDIR); //append the file list

} else if($fname != "" && $content != "" && $showfile=="" ){
    //Create file
    if(file_exists($SUBDIR) == false) mkdir($SUBDIR);
    $fpath=$SUBDIR."/".$fname.".txt";
    $file=fopen($fpath, "w+");
    if($file==false) ;//error handle
    fwrite($file, $content);
    echo "<h3> Note Written. </h3>";
    echo "<h4>File: ",$fname.".txt", "</h4>";

    echo "<h4>Written Content:</h4><pre>";
    echo $content,"</pre>"; 
    //content in a table. too long so split into 2 rows.

    fclose($file);
    echo "</br></br></br>";
    listfile($SUBDIR); //append the file list

} else if($fname == "" && $content == "" && $showfile!="" ){
    //show file
    $fpath=$SUBDIR."/".$showfile;
    $file=fopen($fpath,"r");
    echo $showfile."</br>";
    $rawlink=$SUBDIR."/".$showfile;
    echo "<a href=".$rawlink.">Visit Raw File</a>"; //link to raw file
    echo "<pre>";
    echo fread($file, 100000);
    echo "</pre>";
    fclose($file);
    echo "</br></br></br>";
    listfile($SUBDIR); //append the file list
} 

// Output files list:
function listfile($dir){
    echo "<h2> View a note entry: </h2>";
    $dirlist=scandir($dir);
    echo "<table border=0 cellspacing=10 >";
    foreach($dirlist as $entry){
        if(pathinfo($entry,PATHINFO_EXTENSION) == "txt"){
            $link = "?showfile=".$entry;
            $rawlink = $dir."/".$entry;
            echo "<tr>";
            echo "<td>","<a href=".$link.">".$entry."</a>","</td>";
            echo "<td>","<a href=".$rawlink.">Raw</a>","</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
}

?>

<style>
table.tblform {
	border: 1px solid black;
	padding: 10px;
}
#t {text-align: left}
#i {text-align: center}
</style>

</body>
</html>
