<?php
session_start();
require "global_func.php";
if($_SESSION['loggedin']==0) { header("Location: login.php");exit; }
$userid=$_SESSION['userid'];
require "header.php";
$h = new headers;
$h->startheaders();
include "mysql.php";
global $c;
$is=mysql_query("SELECT u.*,us.* FROM users u LEFT JOIN teams us ON u.userid=us.userid WHERE u.userid=$userid",$c) or die(mysql_error());
$ir=mysql_fetch_array($is);
$fm=money_formatter($ir['money']);
$lv=date('F j, Y, g:i a',$ir['laston']);
$h->userdata($ir,$fm);
$h->menuarea();
$_GET['u'] = abs((int) $_GET['u']);
if (!$_GET['u'])
{
    print "No player with ID of {$_GET['u']}";
}
else
{
$re=mysql_query("SELECT * FROM players WHERE playerid={$_GET['u']}", $c);
$re=mysql_fetch_array($re);
if ($re['role'] != 5)     	     	
{
$elos = pow($re['elo'], 2);
if ($re['elo'] > 2480) {$cost = 15.950226244343892*($elos)-85676.4705882353*($re['elo'])+114477375.56561086;} //challenger
else if ($re['elo'] <= 1150) {$cost =  (0.00006547619047619048*$elos)-(0.03422619047619048*($re['elo']))+2.767857142857143;} //Bronze 
else if ($re['elo'] <= 1500) {$cost =  -0.005702137998056365*($elos)+16.31508746355685*($re['elo'])-11170.273080660836;} //Silver
else if ($re['elo'] <= 1850) {$cost =  0.01278061224489796*($elos)-36.56683673469388*($re['elo'])+26594.877551020407;} //Gold
else if ($re['elo'] <= 2200) {$cost =  (0.05104591836734694*($elos))-(174.59489795918367*($re['elo']))+150296.9056122449; }  //Plat
else if ($re['elo'] <= 2480) {$cost =  0.2551275510204082*($elos)-872.5719387755101*($re['elo'])+694841.918367347;} //Diamond 
$costo = round($cost);
$cost =money_formatter(round($cost));
     	if ($re['role'] == 0) { $role = 'Top';}
     	
     	if ($re['role'] == 1) { $role = 'Mid';}
     	
     	if ($re['role'] == 2) { $role = 'Jungle';}
     	
     	if ($re['role'] == 3) { $role = 'ADC';}
     	
     	if ($re['role'] == 4) { $role = 'Support';}
     	
     	if ($re['role'] == 5) { $role = 'Coach';}
     	
     	if ($re['role'] == 6) { $role = 'Analyst';}
 if ($ir['Coach'] != 0 && $role == 'Coach') { print" You have a Coach or this player is already a Coach or both!";}
else 
{
print "{$re['name']} is now a Coach!";
mysql_query("UPDATE teams SET $role=0 WHERE userid=$userid", $c);
mysql_query("UPDATE teams SET Coach={$_GET['u']} WHERE userid=$userid", $c);
mysql_query("UPDATE players SET role=5 WHERE playerid={$_GET['u']}", $c);
}
}
else 
{
print"This player is not in your team.";
}
}
?>