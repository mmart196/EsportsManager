<?php
/*
MCCodes FREE
index.php Rev 1.1.0c
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

session_start();
require "global_func.php";
if ($_SESSION['loggedin'] == 0)
{
    header("Location: login.php");
    exit;
}
$userid = $_SESSION['userid'];
require "header.php";
$h = new headers;
$h->startheaders();
include "mysql.php";
global $c;
$is = mysql_query("SELECT u.*,us.*,h.*,ud.* FROM users u LEFT JOIN teams us ON u.userid=us.userid LEFT JOIN players ud 
                ON u.userid=ud.userid LEFT JOIN housing h ON h.houseid=u.houseid WHERE u.userid=$userid",
                $c) or die(mysql_error());
$ir = mysql_fetch_array($is);
$fm = money_formatter($ir['money']);
$h->userdata($ir, $fm);
$h->menuarea();
print"Mini News: Making an Rpg Maker game. Just a heads up! Ask me personally if you want to try out the demo."; 
print "<h3>LOLManager:</h2>";

if ($ir['sponsor'] < 10)
{
if ($_GET['u'] != 0)
{
if ($ir['sponsor'] == 0)
{
if ($ir['fame'] >= 5000)
{
mysql_query("UPDATE users SET money=money+30000, sponsor=10 WHERE userid=$userid", $c);
print "You collected $30000 dollars!";
}
else if ($ir['fame'] >= 1000)
{
mysql_query("UPDATE users SET money=money+10000, sponsor=10 WHERE userid=$userid", $c);
print "You collected $10000 dollars!";
}
else if ($ir['fame'] >= 200)
{
mysql_query("UPDATE users SET money=money+5000, sponsor=10 WHERE userid=$userid", $c);
print "You collected $5000 dollars!";
}
else if ($ir['fame'] >= 50)
{
mysql_query("UPDATE users SET money=money+1000, sponsor=10 WHERE userid=$userid", $c);
print "You collected $1000 dollars!";
}
else if ($ir['fame'] >= 25)
{
mysql_query("UPDATE users SET money=money+500, sponsor=10 WHERE userid=$userid", $c);
print "You collected $500 dollars!";
}
else if ($ir['fame'] >= 10)
{
mysql_query("UPDATE users SET money=money+300, sponsor=10 WHERE userid=$userid", $c);
print "You collected $300 dollars!";
}
else if ($ir['fame'] >= 5)
{
mysql_query("UPDATE users SET money=money+150, sponsor=10 WHERE userid=$userid", $c);
print "You collected $150 dollars!";
}
if ($ir['fame'] < 5)
{
mysql_query("UPDATE users SET money=money+50, sponsor=10 WHERE userid=$userid", $c);
print "You collected $50 dollars!";
}
}
}
$collect = "Collect Sponsor Money";
?>
<Form Name ="form1" Method ="POST" ACTION= "status.php?u=1">
<input type='submit' value='Collect Sponsor Money'></input>
<?php
}
else
{

  $myQuery = mysql_query("SELECT time FROM cron WHERE id = 1", $c);

  while($result = mysql_fetch_assoc($myQuery)) {
    $setTime = $result['time']+86400;

    //echo $setTime,'<br>'; // For debugging purposes
    
    $lastRun = date("H:i", $setTime);         // Set the last run time
    $nextRun = date("H:i", $setTime);    // Set the next run time
 
    //$timeDiffs = (($setTime-time())+86400);     // Calculate time difference

    $timeDiff = (($setTime-time()));     // Calculate time difference
    
    $counth = floor($timeDiff/3600);	      // count the number of hours
    $countMins = floor($timeDiff/60);         // Count the number of minutes
    $countSecs = ($timeDiff-($countMins*60)); // Count the number of seconds

    echo 'Collect your Sponsor Money in ',$counth,' hours or ',$countMins,' minutes and ',$countSecs,' seconds at ',$nextRun,'';
}
}


$top = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid=$userid and role = 0", $c)); 
$mid = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid=$userid and role = 1", $c)); 
$jung = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid=$userid and role = 2", $c)); 
$adc = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid=$userid and role = 3", $c)); 
$sup = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid=$userid and role = 4", $c)); 
$coa = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid=$userid and role = 5", $c)); 
$ana = mysql_fetch_array(mysql_query("SELECT * FROM players WHERE userid=$userid and role = 6", $c)); 



function state($mood)
{
if ($mood == 0)
{
return "<font color ='darkblue'>Motivated</font>";
}
if ($mood == 1 )
{
return "<font color ='darkgreen'>Normal</font>";
}

if ($mood == 2 )
{
return "<font color ='yellow'>Anxious</font>";
}

if ($mood == 3 )
{
return "<font color ='red'>Angry</font>";
}

if ($mood == 4 )
{
return "<font color ='bronze'>Bored</font>";
}

if ($mood == 5 )
{
return "<font color ='black'>Apathetic</font>";
}
}

if ($ir['Top'] != 0){
$trank = player_ranks($top);
$topstate = state($top['mood']);}
if ($ir['Mid'] != 0){
$mrank = player_ranks($mid);
$midstate = state($mid['mood']);}
if ($ir['Jungle'] != 0){
$jrank = player_ranks($jung);
$jungstate = state($jung['mood']);}
if ($ir['ADC'] != 0){
$arank = player_ranks($adc);
$adcstate = state($adc['mood']);}
if ($ir['Support'] != 0){
$srank = player_ranks($sup);
$supstate = state($sup['mood']);}
if ($ir['Coach'] != 0){
$crank = player_ranks($coa);
$coastate = state($coa['mood']);
}
if ($ir['Analyst'] != 0){
$anrank = player_ranks($ana);
$anastate = state($ana['mood']);
}
print"
<table width=75%><tr style='background:grey'>
<th>Top</th><th>Mid</th><th>Jungle</th><th>ADC</th><th>Support</th></tr>
<tr><td>Name: {$top['name']}<br />
Rank: {$trank}<br />
Elo: {$top['elo']}<br />
Age: {$top['age']}<br />
State: {$topstate}<br />";
if ($top['age'] >= 25 && $ir['Coach']==0)
{
?>
<br><a href="coach.php?u=<?php print"{$top['playerid']}";?>" onclick="return confirm('Are you sure?  They will stay as a coach forever!');">Turn into Coach</a><br>
<?php
}
?>
<br><a href="endcontract.php?u=<?php print"{$top['playerid']}";?>" onclick="return confirm('Are you sure?  You will receieve their player value as return.');">End Contract</a><br>
<?php
print"
</td>
<td>Name: {$mid['name']}<br />
Rank: {$mrank}<br />
Elo: {$mid['elo']}<br />
Age: {$mid['age']}<br />
State: {$midstate}<br />";
if ($mid['age'] >= 25 && $ir['Coach']==0)
{
?>
<br><a href="coach.php?u=<?php print"{$mid['playerid']}";?>" onclick="return confirm('Are you sure?  They will stay as a coach forever!');">Turn into Coach</a><br>
<?php
}
?>
<br><a href="endcontract.php?u=<?php print"{$mid['playerid']}";?>" onclick="return confirm('Are you sure?  You will receieve their player value as return.');">End Contract</a><br>
<?php
print"
</td>
</td>
<td>Name: {$jung['name']}<br />
Rank: {$jrank}<br />
Elo: {$jung['elo']}<br />
Age: {$jung['age']}<br />
State: {$jungstate}<br />
";
if ($jung['age'] >= 25 && $ir['Coach']==0)
{
?>
<br><a href="coach.php?u=<?php print"{$jung['playerid']}";?>" onclick="return confirm('Are you sure?  They will stay as a coach forever!');">Turn into Coach</a><br>
<?php
}
?>
<br><a href="endcontract.php?u=<?php print"{$jung['playerid']}";?>" onclick="return confirm('Are you sure?  You will receieve their player value as return.');">End Contract</a><br>
<?php
print"
</td>
</td>
<td>Name: {$adc['name']}<br />
Rank: {$arank}<br />
Elo: {$adc['elo']}<br />
Age: {$adc['age']}<br />
State: {$adcstate}<br />
";

if ($adc['age'] >= 25 && $ir['Coach']==0)
{
?>
<br><a href="coach.php?u=<?php print"{$adc['playerid']}";?>" onclick="return confirm('Are you sure?  They will stay as a coach forever!');">Turn into Coach</a><br>
<?php
}
?>
<br><a href="endcontract.php?u=<?php print"{$adc['playerid']}";?>" onclick="return confirm('Are you sure?  You will receieve their player value as return.');">End Contract</a><br>
<?php
print"
</td>
</td>
<td>Name: {$sup['name']}<br />
Rank: {$srank}<br />
Elo: {$sup['elo']}<br />
Age: {$sup['age']}<br />
State: {$supstate}<br />
";

if ($sup['age'] >= 25 && $ir['Coach']==0)
{
?>
<br><a href="coach.php?u=<?php print"{$sup['playerid']}";?>" onclick="return confirm('Are you sure?  They will stay as a coach forever!');">Turn into Coach</a><br>
<?php
}
?>
<br><a href="endcontract.php?u=<?php print"{$sup['playerid']}";?>" onclick="return confirm('Are you sure?  You will receieve their player value as return.');">End Contract</a><br>
<?php
$exp = $coa['cexp'];
print"
</td>
</td></tr>
<tr style='background:grey'><th></th><th>Coach</th><th></th><th>Analyst</th><th></th>
<tr><td></td><td>Name: {$coa['name']}<br />
Coached for {$exp} year(s)<br />
Age: {$coa['age']}<br />
";
?>
<br><a href="endcontract.php?u=<?php print"{$coa['playerid']}";?>" onclick="return confirm('Are you sure?  You will receieve their player value as return.');">End Contract</a><br>
<?php
print"
</td>
<td>
</td>
<td>Name: {$ana['name']}<br />
Rank: {$anrank}<br />
Elo: {$ana['elo']}<br />
Age: {$ana['age']}<br />
";

?>
<br><a href="endcontract.php?u=<?php print"{$ana['playerid']}";?>" onclick="return confirm('Are you sure?  You will receieve their player value as return.');">End Contract</a><br>
<?php
print"
</td>
</table>";
$h->endpage();
?>