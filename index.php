<?php
/* Steven Williams
 * 6/2019
 *
 * this page is meant to be for the user to use a click-and-drag interface to turn on lights
 *
 * TODO: add 'delete route' button with confirmation
*/

//file containing json data of lights currently on board
$dataFile = './resources/lights.txt';
//folder to save user patterns into
$saveFolder = "./savedLights/";

//this file contains the functions used in the rest of the programs
include "./resources/functions.php";

$lightData = keysStartWith($_POST,"lightData"); //isolate lightdata from other data

//define rows and cols of board
$rows = 18;
$cols = 11;
$lightIndex = $rows*$cols-1;


//get the function - should be: update, save, load, auto,
switch(keysStartWith($_POST,"function")["function"]){
  case "update":
    saveJsonFile($lightData,$dataFile); //save lightData to dataFile
    updateBoard($rows*$cols, $dataFile);
    break;
  case "save":
    //ensure file isset and has a length>0, else set as "blank"
    $file2save = "blank";
    //ensure data is set and clean
    if(isset($_POST['saveFile'])) {
      if(strlen($_POST['saveFile'])>0) { $file2save = sanitizeData($_POST['saveFile']); }
    }
    saveJsonFile($lightData,$dataFile); //save lightData to dataFile
    //only save if the filename is not blank, else throw an error
    if($file2save=="blank") {
      echo '<script>alert("You cannot save with that name. Please choose a different name.");</script>';
    } else {
      saveJsonFile($lightData, $saveFolder.$file2save); //save to file
    }
    break;
  case "loadDelete": //load/delete selected file
    //TODO: add logic to determine if a file is to be deleted
    $file2load = "blank"; //init as "blank"
    $file2delete = "blank"; //init as "blank"
    if(isset($_POST['loadFile'])) { //if post data exists
      if(strlen($file2load)>0) { //if post data is>0 chars
        $file2load = sanitizeData($_POST['loadFile']); //sanitize and set
      }
    $jsonData = readJsonFile($saveFolder.$file2load); //read json into var
    saveJsonFile($jsonData,$dataFile); //save json data to tree file
    } elseif(isset($_POST['delete'])) { //if delete is the function
      $file2delete = $_POST['delete'];
      if(file_exists($saveFolder.$file2delete)) { //if the file exists
        if($file2delete<>"blank"){ //if it's not the blank template
          unlink($saveFolder.$file2delete);//delete it
        } else {
          echo "<script>alert('Cannot delete that pattern.');</script>";
        }
      }
    }
    break;
  case "auto":
    break;
  default:
    break;
}

//read $dataFile into array
$lightData = readJsonFile($dataFile);


//start html display
?>
<!DOCTYPE html>

<html>
  <head>
    <title>Escarpment Portable Climbing Wall</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="./resources/logo-favicon.png" />
    <link type="text/css" rel="stylesheet" href="./resources/home.css" />
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="./resources/functions.js"></script>
  </head>
  <body onmousedown="mousedown();" onmouseup="mouseup();">

    <p><?php echo $function['function'];?></p>

    <p id="title">Enter a color to color the holds. Click/tap to color, right click or double click/tap to turn off</p>
    <p>Choose a Color: <input type="color" name="color" id="color" onchange="updateColorText();" value="#ff0000" autofocus> #<input type="text" name="colorText" id="colorText" maxlength="6" onkeypress="this.onchange();" onpaste="this.onchange();" oninput="this.onchange();" onchange="updateColor();" value="ff0000"></p>
    <form method="post">
      <div id="board" oncontextmenu="return false;">
<?php

//loop through rows
for($j=0;$j<$rows;$j++) {
  //set up index to provide for zigzagging lights
  if($j%2) {
    $lightIndex = $lightIndex-$cols+1;
  } elseif($j>0){
    $lightIndex = $lightIndex-$cols-1;
  }
  //loop through lights per row
  for($i=0;$i<$cols;$i++) {
    //check if light color value exists
    if(isset($lightData['lightData'.$lightIndex])) {
      $val = $lightData['lightData'.$lightIndex];  //if light exists, set color val to data
      if(strlen($val)<>6) { //if it's not exactly 6 chars long, set it to black
        $val = "000000";
      }
    } else {
      $val = "000000";  //if it doesn't exist, set value to black
    }
    //display light div and hidden input data
?>
      <div class="light" onmousedown="mousedown();" onclick="changeColor(this);" oncontextmenu="clearBack(this);" id="light<?php echo $lightIndex; ?>" style="background-color: #<?php echo $val;?>;"></div>
      <input type="hidden" id="lightData<?php echo $lightIndex; ?>" name="lightData<?php echo $lightIndex; ?>" value="<?php echo $val;?>">
<?php
    //lights zigzag, increment or decriment accordingly
    if($j%2) { $lightIndex++; } else { $lightIndex--; }
  }
  echo "<br>\n"; //new line
  //for decoration, add divider to show different boards
  if(($j+1)%3==0 && $j<$rows-1) { echo "<hr>\n"; }
}

//start html
?>
      </div>
      <p><?php echo $file2load;?></p>
      <p><button value="update" type="submit" name="function" class="button">Update Board</button></p>

      <p style="line-height: 200%;">Save Route: <input type="text" maxlength="20" pattern="[A-Za-z0-9 !_+'=\-&()]+" id="saveFile" name="saveFile" title="Name must be letters and numbers only"> <button name="function" value="save" class="button" type="submit">Save/Update Route</button></p>
      <p>Saving a route with the same name as an existing route will overwrite the old one.</p>
    </form>

    <div id="fileList">
      <p>Saved Routes:</p><br>
      <form method="post">
        <input type="hidden" value="loadDelete" name="function">
<?php
    $files = preg_grep('/^([^.])/', scandir($saveFolder)); //remove hidden files/dirs
    //display all files in directory
    foreach($files as $e) {
      $e = pathinfo($e, PATHINFO_FILENAME);
?>
      <p><button class="trash" name="delete" value="<?php echo $e; ?>" type="submit" onclick="return confirm('Are you sure you want to delete &quot;<?php echo $e;?>&quot;?');"><div class="trashPic"></div></button><input type="submit" name="loadFile" value="<?php echo $e; ?>" class="fileName"></p>
<?php } ?>
      </form>
    </div>
    <div id="credits">Made with &lt;3 by Steven Williams - 2020</div>
  </body>
</html>