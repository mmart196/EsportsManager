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
if ($re['userid'] == 0 && $re['age'] > 16)     	     	
{
$elos = pow($re['elo'], 2);
$eloss = pow($re['elo'], 2.005);
if ($re['elo'] > 2480) {$cost =  0.2551275510204082*($eloss)-872.5719387755101*($re['elo'])+694841.918367347;} //challenger 
else if ($re['elo'] <= 1150) {$cost =  (0.00006547619047619048*$elos)-(0.03422619047619048*($re['elo']))+2.767857142857143;} //Bronze 
else if ($re['elo'] <= 1500) {$cost =  -0.005702137998056365*($elos)+16.31508746355685*($re['elo'])-11170.273080660836;} //Silver
else if ($re['elo'] <= 1850) {$cost =  0.01278061224489796*($elos)-36.56683673469388*($re['elo'])+26594.877551020407;} //Gold
else if ($re['elo'] <= 2200) {$cost =  (0.05104591836734694*($elos))-(174.59489795918367*($re['elo']))+150296.9056122449; }  //Plat
else if ($re['elo'] <= 2480) {$cost =  0.2551275510204082*($elos)-872.5719387755101*($re['elo'])+694841.918367347;} //Diamond 
     	if ($re['role'] == 0) { $role = 'Top';}
     	
     	if ($re['role'] == 1) { $role = 'Mid';}
     	
     	if ($re['role'] == 2) { $role = 'Jungle';}
     	
     	if ($re['role'] == 3) { $role = 'ADC';}
     	
     	if ($re['role'] == 4) { $role = 'Support';}
     	
     	if ($re['role'] == 5) { $role = 'Coach'; $cost = pow($re['cexp'],2.5)+$re['cexp']*2+50;}
     	
     	if ($re['role'] == 6) { $role = 'Analyst';}
$costo = round($cost);
$cost = money_formatter(round($cost));    	
if ($ir['money'] < $costo)
{
print "You don't have enough money";
return;
break;
}
else if ($ir['money'] >= $costo)
{
if ($ir['Top'] != 0 && $role == 'Top') { print" You already have a Top laner";}
else if ($ir['Mid'] != 0 && $role == 'Mid') { print" You already have a Mid Laner";}
else if ($ir['Jungle'] != 0 && $role == 'Jungle') { print" You already have a Jungler";}
else if ($ir['Support'] != 0 && $role == 'Support') { print" You already have a Support";}
else if ($ir['ADC'] != 0 && $role == 'ADC') { print" You already have an ADC";}
else if ($ir['Coach'] != 0 && $role == 'Coach') { print" You already have a Coach";}
else if ($ir['Analyst'] != 0 && $role == 'Analyst') { print" You already have an Analyst";}
else
{
print "You have contracted {$re['name']}";
mysql_query("UPDATE users SET money=money-$costo WHERE userid=$userid",$c);
mysql_query("UPDATE teams SET $role={$_GET['u']} WHERE userid=$userid", $c);
mysql_query("UPDATE players SET userid=$userid WHERE playerid={$_GET['u']}", $c);
}
}
else 
{
print"This player is under 17 or is already contracted.";
}
}
}
?>