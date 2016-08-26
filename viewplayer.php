<?php
/*
MCCodes Lite
userlist.php Rev 1.0.0
Copyright (C) 2006 Dabomstew

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
require_once "jpgraph/jpgraph.php";
require_once "jpgraph/jpgraph_line.php";
if($_SESSION['loggedin']==0) { header("Location: login.php");exit; }
$userid=$_SESSION['userid'];
require "graph.php";
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
    $q =mysql_query("SELECT * FROM players WHERE playerid={$_GET['u']}",
                    $c);
    if (mysql_num_rows($q) == 0)
    {
        print "Sorry, we could not find a player with that ID, check your source.";
    }
    else
    {
        $r = mysql_fetch_array($q);
        print "<h3>{$r['name']}</h3> <br>";
        $rf = mysql_query("SELECT r.*, rd.*, rf.* FROM players r LEFT JOIN teams rd ON r.userid=rd.userid LEFT JOIN users rf ON r.userid=rf.userid where playerid={$_GET['u']}",$c);
     	$rf = mysql_fetch_array($rf);
     	if ($r['elo'] < 870) { $rankk = "<font color='CC9900'>Bronze V</font>"; }
     	if ($r['elo'] > 870 && $r['elo'] <= 940) { $rankk = "<font color='CC9900'>Bronze IV</font>"; }
     	if ($r['elo'] > 940 && $r['elo'] <= 1010) { $rankk = "<font color='CC9900'>Bronze III</font>"; }
     	if ($r['elo'] > 1010 && $r['elo'] <= 1080) { $rankk = "<font color='CC9900'>Bronze II</font>"; }
     	if ($r['elo'] > 1080 && $r['elo'] <= 1150) { $rankk = "<font color='CC9900'>Bronze I</font>"; }
     	if ($r['elo'] > 1150 && $r['elo'] <= 1220) { $rankk = "<font color='#505050'>Silver V</font>"; } 
     	if ($r['elo'] > 1220 && $r['elo'] <= 1290) { $rankk = "<font color='#505050'>Silver IV</font>"; }
     	if ($r['elo'] > 1290 && $r['elo'] <= 1360) { $rankk = "<font color='#505050'>Silver III</font>"; }
     	if ($r['elo'] > 1360 && $r['elo'] <= 1430) { $rankk = "<font color='#505050'>Silver II</font>"; }
     	if ($r['elo'] > 1430 && $r['elo'] <= 1500) { $rankk = "<font color='#505050'>Silver I</font>"; }
     	if ($r['elo'] > 1500 && $r['elo'] <= 1570) { $rankk = "<font color='FFFF00'>Gold V</font>"; }
     	if ($r['elo'] > 1570 && $r['elo'] <= 1640) { $rankk = "<font color='FFFF00'>Gold IV</font>"; }
     	if ($r['elo'] > 1640 && $r['elo'] <= 1710) { $rankk = "<font color='FFFF00'>Gold III</font>"; }
     	if ($r['elo'] > 1710 && $r['elo'] <= 1780) { $rankk = "<font color='FFFF00'>Gold II</font>"; }
     	if ($r['elo'] > 1780 && $r['elo'] <= 1850) { $rankk = "<font color='FFFF00'>Gold I</font>"; }
     	if ($r['elo'] > 1850 && $r['elo'] <= 1920) { $rankk = "<font color='darkgreen'>Platinum V</font>"; }
     	if ($r['elo'] > 1920 && $r['elo'] <= 1990) { $rankk = "<font color='darkgreen'>Platinum IV</font>"; }
     	if ($r['elo'] > 1990 && $r['elo'] <= 2060) { $rankk = "<font color='darkgreen'>Platinum III</font>"; }
     	if ($r['elo'] > 2060 && $r['elo'] <= 2130) { $rankk = "<font color='darkgreen'>Platinum II</font>"; }
     	if ($r['elo'] > 2130 && $r['elo'] <= 2200) { $rankk = "<font color='darkgreen'>Platinum I</font>"; }
     	if ($r['elo'] > 2200 && $r['elo'] <= 2270) { $rankk = "<font color='66FFFF'>Diamond V</font>"; }
     	if ($r['elo'] > 2270 && $r['elo'] <= 2340) { $rankk = "<font color='66FFFF'>Diamond IV</font>"; }
     	if ($r['elo'] > 2340 && $r['elo'] <= 2410) { $rankk = "<font color='66FFFF'>Diamond III</font>"; }
     	if ($r['elo'] > 2410 && $r['elo'] <= 2480) { $rankk = "<font color='66FFFF'>Diamond II</font>"; }
     	if ($r['elo'] > 2480 && $r['elo'] <= 2999) { $rankk = "<font color='66FFFF'>Diamond I</font>"; }
     	if ($r['elo'] > 2999) { $rankk = "<font color='FF9900'>Challenger</font>"; }
     	
     	if ($r['role'] == 0) { $role = 'Top';}
     	
     	if ($r['role'] == 1) { $role = 'Mid';}
     	
     	if ($r['role'] == 2) { $role = 'Jungle';}
     	
     	if ($r['role'] == 3) { $role = 'ADC';}
     	
     	if ($r['role'] == 4) { $role = 'Support';}
     	
     	if ($r['role'] == 5) { $role = 'Coach';}
     	
     	if ($r['role'] == 6) { $role = 'Analyst';}
     	
     	
     	
if ($r['userid'] == 0 && $r['age'] > 16)     	     	
{
$elos = pow($r['elo'], 2);
$eloss = pow($r['elo'], 2.005);
if ($r['elo'] > 2480) {$cost =  0.2551275510204082*($eloss)-872.5719387755101*($r['elo'])+694841.918367347;} //challenger 
else if ($r['elo'] <= 1150) {$cost =  (0.00006547619047619048*$elos)-(0.03422619047619048*($r['elo']))+2.767857142857143;} //Bronze 
else if ($r['elo'] <= 1500) {$cost =  -0.005702137998056365*($elos)+16.31508746355685*($r['elo'])-11170.273080660836;} //Silver
else if ($r['elo'] <= 1850) {$cost =  0.01278061224489796*($elos)-36.56683673469388*($r['elo'])+26594.877551020407;} //Gold
else if ($r['elo'] <= 2200) {$cost =  (0.05104591836734694*($elos))-(174.59489795918367*($r['elo']))+150296.9056122449; }  //Plat
else if ($r['elo'] <= 2480) {$cost =  0.2551275510204082*($elos)-872.5719387755101*($r['elo'])+694841.918367347;} //Diamond 
$cost =money_formatter(round($cost));
}
     	
 	
      ?>
<table x:str border=0 cellpadding=0 cellspacing=0 width=256 style='border-collapse:
 collapse;table-layout:fixed;width:192pt'>
 <col width=64 span=4 style='width:48pt'>
 <tr height=17 style='height:12.75pt'>
  <td height=17 width=64 style='height:12.75pt;width:48pt'><u><b>Stats</b></u></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 style='height:12.75pt'>Rank:</td>
  <td colspan=3 style='mso-ignore:colspan'> <?php print"{$rankk}"; ?></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 style='height:12.75pt'>Role:</td>
  <td colspan=3 style='mso-ignore:colspan'> <?php print"{$role}"; ?></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 style='height:12.75pt'>Age:</td>
  <td colspan=3 style='mso-ignore:colspan'><?php print" {$r['age']}"; ?></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 style='height:12.75pt'>Elo:</td>
  <td colspan=3 style='mso-ignore:colspan'><?php print" {$r['elo']}"; ?></td>
 </tr>
  </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 style='height:12.75pt'>Wins:</td>
  <td colspan=3 style='mso-ignore:colspan'><?php print" {$r['wins']}"; ?></td>
 </tr>
  </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 style='height:12.75pt'>Losses:</td>
  <td colspan=3 style='mso-ignore:colspan'><?php print"{$r['loss']}"; ?></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 style='height:12.75pt'>Manager:</td>
<td colspan=3 style='mso-ignore:colspan'><?php print" <a href='viewuser.php?u={$rf['userid']}'> {$rf['username']}</a>"; ?></td></tr>
 <![if supportMisalignedColumns]>
 <tr height=0 style='display:none'>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
 </tr>
 <![endif]>
</table>
<br>
<?php

$next = $_GET['u']+1;
$prev = $_GET['u']-1;


if ($r['userid'] == 0 && $r['age'] > 16)    	     	
{
?>


<br><a href="contract.php?u=<?php print"{$_GET['u']}";?>" onclick="return confirm('Are you sure?');">Contract for <?php print" {$cost}"; ?></a><br>
<?php

}
print"<br>
[<a href ='viewplayer.php?u={$next}'>Check next user</a>]<br>
<br>
[<a href ='viewplayer.php?u={$prev}'>Check previous user</a>]<br>
<br>";



}
}
?>