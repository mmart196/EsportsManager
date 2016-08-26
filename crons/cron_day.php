<?php

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
//else if (!isset($_GET['code']) || $_GET['code'] !== $cron_code)
//{
//    exit;
//}
$elorange = rand(1,5);
$namesimulator2014 = rand(1,10000);
$name = "tempname{$namesimulator2014}";
$role = rand(0,4);
$age = rand(12, 25);
if ($elorange == 5) {$realelo = rand(500, 1500);}
if ($elorange == 4) {$realelo = rand(600, 1400);}
if ($elorange == 3) {$realelo = rand(700, 1300);}
if ($elorange == 2) {$realelo = rand(800, 1200);}
if ($elorange == 1) {$realelo = rand(900, 1100);}
mysql_query("INSERT INTO players ( name, role, age, userid, elo, mood, realelo, wins, loss) VALUES ('$name', '$role', '$age', 0, 1000, 0, '$realelo', 0, 0)", $c);


$q = mysql_query("SELECT * FROM players", $c);
while($r=mysql_fetch_array($q))
{
$coachcheck = mysql_query("SELECT * FROM teams WHERE userid={$r['userid']}", $c);
$coachcheck = mysql_fetch_array($coachcheck);
if ($coachcheck['Coach'] != 0)
{
$checky = mysql_query("SELECT * FROM players where playerid={$coachcheck['Coach']}", $c);
$checky = mysql_fetch_array($checky);
if ($checky['cexp'] >= 175)
{
$rood = rand(0,1);
}
if ($checky['cexp'] >= 70)
{
$rood = rand(0,2);
}
if ($checky['cexp'] >= 28)
{
$rood = rand(0,3);
}
if ($checky['cexp'] >= 0)
{
$rood = rand(0,4);
}
}
else
{
$rood = rand(0,5);
}

mysql_query("UPDATE players SET mood=$rood WHERE playerid={$r['playerid']}", $c);
}
mysql_query("UPDATE users SET sponsor=0 WHERE sponsor=10", $c);
mysql_query("DELETE FROM tourneylog",$c);
mysql_query("UPDATE cron SET id=id+1", $c);
$time = time();
mysql_query("INSERT INTO cron ( time, id) VALUES ('$time', 1)", $c); 