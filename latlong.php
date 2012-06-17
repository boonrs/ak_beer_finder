<?php

$link = mysql_connect('localhost', 'root', 'root');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}

// make foo the current db
$db_selected = mysql_select_db('ak_beer_finder', $link);
if (!$db_selected) {
    die ('Can\'t use foo : ' . mysql_error());
}

$result = mysql_query("SELECT * FROM bars");
while ($row = mysql_fetch_assoc($result)) {
    $geocoding = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($row['address']).'&sensor=true');
    $json = json_decode($geocoding);
    $lat = $json["results"]["geometry"]["location"]["lat"];
    $lng = $json["results"]["geometry"]["location"]["long"];
    
    mysql_query("UPDATE  `ak_beer_finder`.`bars` SET  `lat` =  '{$lat}', `long` =  '{$lng}' WHERE  `bars`.`id` =135;");
    
}