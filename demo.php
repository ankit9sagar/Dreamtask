  <?php
    $conn=mysql_connect("server1", "itwelezo_health", "password@123"); 
	mysql_select_db("itwelezo_health", $conn);
 		?>

<?php

If(!empty($SERVER["HTTP_CLIENT_IP"]))
{
	$IP=$SERVER["HTTP_CLIENT_IP"];
}
else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
{
	$IP=$_SERVER["HTTP_X_FORWARDED_FOR"];
}
else{
	$IP=$_SERVER["REMOTE_ADDR"];
}
echo $IP;

?>
<?php
  // This program generates a web pages that gets 
  // the user's information, saves it to a file, 
  // and displays it on the web page.
  // Created by Mitchell Robinson.
  // 27 July, 2014.
  
  // Name of the ip address log.
  $outputWebBug = 'iplog.csv';

  // Get the ip address and info about client.
  @ $details = json_decode(file_get_contents("http://ipinfo.io/{$_SERVER['REMOTE_ADDR']}/json"));
  @ $hostname=gethostbyaddr($_SERVER['REMOTE_ADDR']);
  
  // Get the query string from the URL.
  $QUERY_STRING = preg_replace("%[^/a-zA-Z0-9@,_=]%", '', $_SERVER['QUERY_STRING']);
  
  // Write the ip address and info to file.
  @ $fileHandle = fopen($outputWebBug, "a");
  if ($fileHandle)
  {
    $string ='"'.$QUERY_STRING.'","' // everything after "?" in the URL
      .$_SERVER['REMOTE_ADDR'].'","' // ip address
      .$hostname.'","' // hostname
      .$_SERVER['HTTP_USER_AGENT'].'","' // browser and operating system
      .$_SERVER['HTTP_REFERER'].'","' // where they got the link for this page
      .$details->loc.'","' // latitude, longitude
      .$details->org.'","' // internet service provider
      .$details->city.'","'  // city
      .$details->region.'","' // state
      .$details->country.'","' // country
      .date("D dS M,Y h:i a").'"' // date
      ."\n"
      ;
     $write = fputs($fileHandle, $string);
    @ fclose($fileHandle);
  }

  $string = '<code>'
    .'<p>'.$QUERY_STRING.'</p><p>IP address:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
    .$_SERVER['REMOTE_ADDR'].'</p><p>Hostname:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
    .$hostname.'</p><p>Browser and OS:&nbsp;'
    .$_SERVER['HTTP_USER_AGENT'].'</p><p>'
    .$_SERVER['HTTP_REFERER'].'</p><p>Coordinates:&nbsp;&nbsp;&nbsp;&nbsp;'
    .$details->loc.'</p><p>ISP provider:&nbsp;&nbsp;&nbsp;'
    .$details->org.'</p><p>City:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
    .$details->city.'</p><p>State:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
    .$details->region.'</p><p>Country:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
    .$details->country.'</p><p>Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
    .date("D dS M,Y h:i a").'</p></code>'
    ;

     
     
  echo '<!DOCTYPE html><html><head><title>Who Am I?</title></head><body>';
  echo $string;
$string=$details->loc; 
$arr = explode(",", $string);

$lat = $arr[0];
$lng = $arr[1];
$IP =$_SERVER['REMOTE_ADDR'] ;
$Hostname =$hostname;
$date = date("Y-m-d");
$Browser = $_SERVER['HTTP_USER_AGENT'];
$provider=$details->org;
$city=$details->city;
$region=$details->region;
$country=$details->country;

//$lat= 12.966198; //latitude
//$lng=77.660729; //longitude
 $address= getaddress($lat,$lng);
 
 
  function getaddress($lat,$lng)
{
$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
$json = @file_get_contents($url);
$data=json_decode($json);
$status = $data->status;
if($status=="OK")
return $data->results[0]->formatted_address;
else
return false;
}
     echo '</body></html>';
?>
