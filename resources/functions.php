<?php
//functiosn to use in the main php file

//saves data to a file in json format (automatically)
function saveJsonFile($data, $fileName) {
  file_put_contents($fileName, json_encode(sanitizeData($data)));
}

//returns array from saved json data
function readJsonFile($fileName) {
  return json_decode(sanitizeData(file_get_contents($fileName)),true);
}

//sanitize the data for use in the programs - in this case usually post data
function sanitizeData($data) {
  $invalid_characters = array("$", "%", "#", "<", ">", "|");
  $data = str_replace($invalid_characters, "", $data);
  return $data;
}

//run python script to read $dataFile into light strand
function updateBoard($lightNum, $dataFile) {
  echo "running";
  $output = shell_exec('sudo python3 /home/pi/portaWall/resources/updater.py '.$lightNum.' '.$dataFile);
  //file_put_contents("log.txt",$output); //used for debugging
  echo $output;
  echo "ran";
}

//returns array with the keys only starting with $keyStart
function keysStartWith($array, $keyStart) {
  $array = sanitizeData($array);
  foreach ($array as $key => $value) {
    if (strpos($key, $keyStart) === 0) {
      $arrOut[$key] = $value;
    }
  }
  return $arrOut;
}


?>
