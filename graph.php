<?php

function elo_graph($id)
{
global $ir, $c;
$datay1 = array();
$datay = mysql_query("SELECT * FROM elologs WHERE playerid=$id", $c);
while ($ero=mysql_fetch_array($datay))
{
array_push($datay1, $ero);
}
}
?>