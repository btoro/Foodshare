<?php
require("db.php");

// define variables and set to empty values
$celiac = $autism = $vegetarian = $diabetes = $candiada = cardio = "0";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $celiac = test_input($_POST["celiac"]);
  $autism = test_input($_POST["autism"]);
  $vegetarian = test_input($_POST["vegetarian"]);
  $diabetes = test_input($_POST["diabetes"]);
  $candiada = test_input($_POST["candiada"]);
  $cardio = test_input($_POST["cardio"]);
  $maxprice = test_input($_POST["maxprice"]);

}


function parseToXML($htmlStr)
{
$xmlStr=str_replace('<','&lt;',$htmlStr);
$xmlStr=str_replace('>','&gt;',$xmlStr);
$xmlStr=str_replace('"','&quot;',$xmlStr);
$xmlStr=str_replace("'",'&#39;',$xmlStr);
$xmlStr=str_replace("&",'&amp;',$xmlStr);
return $xmlStr;
}

$connection=mysqli_connect ('localhost', $username, $password);
if (!$connection) {
  die('Not connected : ' . mysqli_error());
}
//echo 'Connected... ' . mysqli_get_host_info($link) . "\n";
// Set the active MySQL database
$db_selected = mysqli_select_db( $connection, $database);
if (!$db_selected) {
  die ('Can\'t use db : ' . mysqli_error());
}
// Select all the rows in the markers table
//$query = "SELECT * FROM events WHERE 1";

$query = "SELECT * FROM events WHERE celiac = $celiac AND autism = $autism AND vegetarian = $vegetarian AND diabetes = $diabetes AND candiada = $candiada AND cardio = $cardio";


$result = mysqli_query($connection, $query);
if (!$result) {
  die('Invalid query: ' . mysqli_error());
}
header("Content-type: text/xml");
ob_clean();
// Start XML file, echo parent node
echo "<?xml version='1.0' ?>";
echo '<markers>';
$ind=0;
// Iterate through the rows, printing XML nodes for each
while ($row = @mysqli_fetch_assoc($result)){
  // Add to XML document node
  echo '<marker ';
  echo 'id="' . $row['id'] . '" ';
  echo 'host="' . parseToXML($row['host']) . '" ';
  echo 'data="' . parseToXML($row['date']) . '" ';
  echo 'address="' . parseToXML($row['address']) . '" ';
  echo 'city="' . parseToXML($row['city']) . '" ';
  echo 'description="' . parseToXML($row['Description']) . '" ';
  echo 'maxParticipants="' . parseToXML($row['maxParticipants']) . '" ';
  echo 'lat="' . $row['lat'] . '" ';
  echo 'lng="' . $row['lon'] . '" ';
  echo '/>';
  $ind = $ind + 1;
}
// End XML file
echo '</markers>';
?>
