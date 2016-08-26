<?php

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
$is =
        mysql_query(
                "SELECT u.*,us.* FROM users u LEFT JOIN teams us ON u.userid=us.userid WHERE u.userid=$userid",
                $c) or die(mysql_error());
$ir = mysql_fetch_array($is);
$fm = money_formatter($ir['money']);
$cm = money_formatter($ir['crystals'], '');
$lv = date('F j, Y, g:i a', $ir['laston']);
$h->userdata($ir, $fm);
$h->menuarea();
$mpq = mysql_query("SELECT * FROM users WHERE userid=$userid", $c);
$mp = mysql_fetch_array($mpq);
$current = "<font color ='red'>NONE</font>";
if ($mp['houseid'] != 0)
{
$current = $mp['houseid'];
$current = house($current);
$current = "<a href='realestate.php?sell=1'>{$current}</a>";
if ($_GET['sell'] == 1)
{
$thehouse = mysql_query("SELECT * FROM housing WHERE houseid={$ir['houseid']}", $c);
$thehous = mysql_fetch_array($thehouse);
mysql_query("UPDATE users SET houseid=0, money=money+{$thehous['cost']} WHERE userid=$userid", $c);
print "You sold your {$current}";
}
}
else if ($_GET['sell'] == 1 && $mp['houseid'] == 0)
{
print "You cant sell your shitty apartment. Stop trying..";
}
$_GET['property'] = abs((int) $_GET['property']);
if ($_GET['property'])
{
    $npq =mysql_query("SELECT * FROM housing WHERE houseid={$_GET['property']}",
                    $c);
    $np = mysql_fetch_array($npq);
    if ($np['houseid'] < $mp['houseid'])
    {
        print "You cannot go backwards in houses!";
    }
    else if ($np['cost'] > $ir['money'])
    {
        print "You do not have enough money to buy the {$np['name']}.";
    }
    else
    {
        mysql_query(
                "UPDATE users SET money=money-{$np['cost']}, houseid={$np['houseid']} WHERE userid=$userid",
                $c);
        print "Congrats, you bought the {$np['name']} for \${$np['cost']}!";
    }
}
else
{
    print
            "<br />
            
The houses you can buy are listed below. Click a house to buy it. Sell your house before you buy a new one!
<br>Buying a house will increase the learning growth of your team!<br />

<br>Sell your current house: {$current}</br>";
$hos = $ir['houseid'];
    $hq = mysql_query("SELECT * FROM housing WHERE houseid>$hos ORDER BY houseid ASC",$c);
    while ($r = mysql_fetch_array($hq))
    {
        print"<a href='realestate.php?property={$r['houseid']}'>{$r['name']}</a>&nbsp;&nbsp - Cost: \${$r['cost']}&nbsp;&nbsp<br />";
    }
}
$h->endpage();
?>