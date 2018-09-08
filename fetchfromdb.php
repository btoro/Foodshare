<?php
require("db.php");

// define variables and set to empty values
$celiac = "0";
$autism = "0";
$vegetarian= "0";
$diabetes = "0";
$candiada = "0";
$cardio = "0";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $celiac = $_POST["celiac"];
//  $autism = $_POST["autism"];
//  $vegetarian = $_POST["vegetarian"];
//  $diabetes = $_POST["diabetes"];
//  $candiada = $_POST["candiada"];
//  $cardio = $_POST["cardio"];
//  $maxprice = $_POST["maxprice"];

file_put_contents("/home/btoro93/log.txt", 'VARIABLE: ', FILE_APPEND);
file_put_contents("/home/btoro93/log.txt", $celiac.PHP_EOL, FILE_APPEND);

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

file_put_contents("/home/btoro93/log.txt", '/n', FILE_APPEND);
file_put_contents("/home/btoro93/log.txt", $query, FILE_APPEND);


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
