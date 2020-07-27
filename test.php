<?php

//get todays date
$today = date('Y-m-d');

$tom = new DateTime($today);
//find tomorrows day
$tom->modify('+1 day');
$tomorrow = $tom->format('Y-m-d');

//add correct formatting
$tomorrow .= "T00:00:00-04:00";
$today .= "T00:00:00-04:00";

