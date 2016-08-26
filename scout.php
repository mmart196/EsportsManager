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
$_GET['st'] = abs((int) $_GET['st']);
$st = ($_GET['st']) ? $_GET['st'] : 0;
$allowed_by = array('playerid', 'name', 'elo');
$by = (in_array($_GET['by'], $allowed_by)) ? $_GET['by'] : 'playerid';
$allowed_ord = array('asc', 'desc', 'ASC', 'DESC');
$ord = (in_array($_GET['ord'], $allowed_ord)) ? $_GET['ord'] : 'DESC';
print "<h3>Leaderboards</h3>";
$cnt = mysql_query("SELECT playerid FROM players", $c);
$membs = mysql_num_rows($cnt);
$pages = (int) ($membs / 100) + 1;
if ($membs % 100 == 0)
{
    $pages--;
}
print "Pages: ";
for ($i = 1; $i <= $pages; $i++)
{
    $stl = ($i - 1) * 100;
    print "<a href='scout.php?st=$stl&by=$by&ord=$ord'>$i</a>&nbsp;";
}
print "<br />
Order By: <a href='scout.php?st=$st&by=playerid&ord=$ord'>Player ID</a>&nbsp;| <a href='scout.php?st=$st&by=name&ord=$ord'>Name</a>&nbsp;| <a href='scout.php?st=$st&by=elo&ord=$ord'>Elo</a>&nbsp;| <a href='scout.php?st=$st&by=role&ord=$ord'>Role</a><br />
<a href='scout.php?st=$st&by=$by&ord=asc'>Ascending</a>&nbsp;| <a href='scout.php?st=$st&by=$by&ord=desc'>Descending</a><br /><br />";
$q=mysql_query("SELECT * FROM players ORDER BY ELO $ord LIMIT $st,5000",$c);
$no1=$st+1;
$no2=$st+500;
print "Scout players and contract them into your team! (STILL WORKING ON THE ORDER BY THING)
<table width=75% border=2><tr style='background:gray'><th>ID</th><th>Name</th><th>Age</th><th>Money</th><th>Role</th><th>Rank</th><th>Elo</th><th>Games</th><th>Wins</th><th>Losses</th></tr>";
while($r=mysql_fetch_array($q))
{

$ts=$r['elo'];
$tsrank=get_rank($ts,'elo');
$ts=number_format($ts);
$rank = $ts;
$cost = get_cost();
$rankk = player_ranking();
$tot = $r['wins']+$r['loss'];
if ($r['role'] == 0) { $role = 'Top';}
     	
     	if ($r['role'] == 1) { $role = 'Mid';}
     	
     	if ($r['role'] == 2) { $role = 'Jungle';}
     	
     	if ($r['role'] == 3) { $role = 'AD Carry';}
     	
     	if ($r['role'] == 4) { $role = 'Support';}
     	
     	if ($r['role'] == 5) { $role = 'Coach';}
     	
     	if ($r['role'] == 6) { $role = 'Analyst';}
     	
$d="";

print "<tr><td>{$r['playerid']}</td><td><a href='viewplayer.php?u={$r['playerid']}'> {$r['name']} $d</a></td><td>{$r['age']}</td><td>{$cost}</td><td>{$role}</td><td>{$rankk}</td><td>{$ts}</td><td>{$tot}</td><td>{$r['wins']}</td><td>{$r['loss']}</td>";
print "</td></tr>";
}
print "</table>";

$h->endpage();
?>