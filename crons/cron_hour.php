<?php
/*
MCCodes FREE
crons/cron_hour.php Rev 1.1.0c
Copyright (C) 2005-2012 Dabomstew

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
define("__ROOT__", dirname(dirname(__FILE__))); 
require_once(__ROOT__."/mysql.php"); 
require_once(__ROOT__."/global_func.php");
$cron_code = 'bda08ab16764c22520596e5786bb5852';
if ($argc == 2)
{
    if ($argv[1] != $cron_code)
    {
        exit;
    }
}
else
{
$result = mysql_query("SELECT * FROM players", $c);
$n = mysql_num_rows($result);
for ($i = 1;$i <= $n; $i++)
{
$playerinfo = mysql_query("SELECT * FROM players WHERE playerid={$i}", $c);
$playerinfo = mysql_fetch_array($playerinfo);
$elo = $playerinfo['elo'];
$real = $playerinfo['realelo'];
$rand = rand(1,100);
$wins = $playerinfo['wins'];
$losses = $playerinfo['loss'];
$housegain = 0;
if ($playerinfo['userid'] != 0)
{
$housegain = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE userid={$playerinfo['userid']}", $c));
$housegain = ($housegain['houseid'])/10;
}
if ($wins > $losses)
{
$diff = $wins - $losses;
$wgain = ((pow($diff, 2)*0.15)+(2.5*($diff))+10);
$lgain = ((pow($diff, 2)*0.15)-(2.5*($diff))-10);
if ($wgain < 0) { $wgain = 5; }
if ($lgain < 0) { $lgain = 0; }
}
else if ($wins < $losses)
{
$diff = $losses - $wins;
$wgain = ((pow($diff, 2)*0.15)-(2.5*($diff))-10);
$lgain = ((pow($diff, 2)*0.15)+(2.5*($diff))+10);
if ($wgain < 0) { $wgain = 5; }
if ($lgain < 0) { $lgain = 0; }
}
else
{
$wgain = 10;
$lgain = 10;
}
if ($real <= $elo)
{
$calc = $elo - $real;
	if ($calc > 400)
	{
	$gain = rand(0,20);
	if ($playerinfo['mood'] != 0) { $gain = ((8-$playerinfo['mood'])*$gain)/10; } 
	$gain = $gain+$housegain;
	mysql_query("UPDATE players SET elo=elo-{$lgain}, realelo=realelo+{$gain}, loss=loss+1 WHERE playerid={$i}", $c);
		print" {$playerinfo['name']}loses!<br>";
	}
	else
	{
	$calc = $calc*(0.125)+50;
	if ($rand <= $calc)
	{
	$gain = rand(0,10);
	if ($playerinfo['mood'] != 0) { $gain = ((8-$playerinfo['mood'])*$gain)/10; } 
	$gain = $gain+$housegain;
	mysql_query("UPDATE players SET elo=elo-{$lgain}, realelo=realelo+{$gain}, loss=loss+1 WHERE playerid={$i}", $c);
		print" {$playerinfo['name']} loses!<br>";
	}
	else
	{
	$gain = rand(0,5);
	if ($playerinfo['mood'] != 0) { $gain = ((8-$playerinfo['mood'])*$gain)/10; } 
	$gain = $gain+$housegain;
	mysql_query("UPDATE players SET elo=elo+{$wgain}, realelo=realelo+{$gain}, wins=wins+1 WHERE playerid={$i}", $c);
		print"{$playerinfo['name']}wins!<br>";
	}
	}

}
else
{
$calc = $real - $elo;
	if ($calc > 400)
	{
	mysql_query("UPDATE players SET elo=elo+{$wgain}, wins=wins+1 WHERE playerid={$i}", $c);
	print" {$playerinfo['name']} wins!<br>";
	}
	else
	{
	$calc = $calc*(0.125)+50;
	if ($rand <= $calc)
	{
	$gain = rand(0,1);
	if ($playerinfo['mood'] != 0) 
	{ 
	$gain = ((8-$playerinfo['mood'])*$gain)/10; 
	} 
	$gain = $gain+$housegain;
	mysql_query("UPDATE players SET elo=elo+{$wgain}, realelo=realelo+{$gain}, wins=wins+1 WHERE playerid={$i}", $c);
		print"{$playerinfo['name']} wins!<br>";
	}
	else
	{
	$gain = rand(0,5);
	if ($playerinfo['mood'] != 0) { $gain = ((8-$playerinfo['mood'])*$gain)/10; } 
	$gain = $gain+$housegain;
	mysql_query("UPDATE players SET elo=elo-{$lgain}, realelo=realelo+{$gain}, loss=loss+1 WHERE playerid={$i}", $c);
		print" {$playerinfo['name']} loses!<br>";
	}
	}
}
mysql_query("INSERT INTO elologs (playerid, time, elo) VALUES ('$i',CURRENT_TIMESTAMP, '$elo')", $c);
}
}
?>