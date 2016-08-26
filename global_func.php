<?php
/*
MCCodes FREE
global_func.php Rev 1.1.0c
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

if (strpos($_SERVER['PHP_SELF'], "global_func.php") !== false)
{
    exit;
}

function money_formatter($muny, $symb = '$')
{
    $moneys = "";
    $muny = (string) $muny;
    if (strlen($muny) <= 3)
    {
        return $symb . $muny;
    }
    $dun = 0;
    for ($i = strlen($muny); $i > 0; $i -= 1)
    {
        if ($dun % 3 == 0 && $dun > 0)
        {
            $moneys = "," . $moneys;
        }
        $dun += 1;
        $moneys = $muny[$i - 1] . $moneys;
    }
    return $symb . $moneys;
}

function itemtype_dropdown($connection, $ddname = "item_type", $selected = -1)
{
    $ret = "<select name='$ddname' type='dropdown'>";
    $q =
            mysql_query("SELECT * FROM itemtypes ORDER BY itmtypename ASC",
                    $connection);
    if ($selected == -1)
    {
        $first = 0;
    }
    else
    {
        $first = 1;
    }
    while ($r = mysql_fetch_array($q))
    {
        $ret .= "\n<option value='{$r['itmtypeid']}'";
        if ($selected == $r['itmtypeid'] || $first == 0)
        {
            $ret .= " selected='selected'";
            $first = 1;
        }
        $ret .= ">{$r['itmtypename']}</option>";
    }
    $ret .= "\n</select>";
    return $ret;
}

function item_dropdown($connection, $ddname = "item", $selected = -1)
{
    $ret = "<select name='$ddname' type='dropdown'>";
    $q =
            mysql_query("SELECT * FROM items ORDER BY itmname ASC",
                    $connection);
    if ($selected == -1)
    {
        $first = 0;
    }
    else
    {
        $first = 1;
    }
    while ($r = mysql_fetch_array($q))
    {
        $ret .= "\n<option value='{$r['itmid']}'";
        if ($selected == $r['itmid'] || $first == 0)
        {
            $ret .= " selected='selected'";
            $first = 1;
        }
        $ret .= ">{$r['itmname']}</option>";
    }
    $ret .= "\n</select>";
    return $ret;
}

function location_dropdown($connection, $ddname = "location", $selected = -1)
{
    $ret = "<select name='$ddname' type='dropdown'>";
    $q =
            mysql_query("SELECT * FROM cities ORDER BY cityname ASC",
                    $connection);
    if ($selected == -1)
    {
        $first = 0;
    }
    else
    {
        $first = 1;
    }
    while ($r = mysql_fetch_array($q))
    {
        $ret .= "\n<option value='{$r['cityid']}'";
        if ($selected == $r['cityid'] || $first == 0)
        {
            $ret .= " selected='selected'";
            $first = 1;
        }
        $ret .= ">{$r['cityname']}</option>";
    }
    $ret .= "\n</select>";
    return $ret;
}

function shop_dropdown($connection, $ddname = "shop", $selected = -1)
{
    $ret = "<select name='$ddname' type='dropdown'>";
    $q =
            mysql_query("SELECT * FROM shops ORDER BY shopNAME ASC",
                    $connection);
    if ($selected == -1)
    {
        $first = 0;
    }
    else
    {
        $first = 1;
    }
    while ($r = mysql_fetch_array($q))
    {
        $ret .= "\n<option value='{$r['shopID']}'";
        if ($selected == $r['shopID'] || $first == 0)
        {
            $ret .= " selected='selected'";
            $first = 1;
        }
        $ret .= ">{$r['shopNAME']}</option>";
    }
    $ret .= "\n</select>";
    return $ret;
}

function user_dropdown($connection, $ddname = "user", $selected = -1)
{
    $ret = "<select name='$ddname' type='dropdown'>";
    $q =
            mysql_query("SELECT * FROM users ORDER BY username ASC",
                    $connection);
    if ($selected == -1)
    {
        $first = 0;
    }
    else
    {
        $first = 1;
    }
    while ($r = mysql_fetch_array($q))
    {
        $ret .= "\n<option value='{$r['userid']}'";
        if ($selected == $r['userid'] || $first == 0)
        {
            $ret .= " selected='selected'";
            $first = 1;
        }
        $ret .= ">{$r['username']}</option>";
    }
    $ret .= "\n</select>";
    return $ret;
}

function fed_user_dropdown($connection, $ddname = "user", $selected = -1)
{
    $ret = "<select name='$ddname' type='dropdown'>";
    $q =
            mysql_query(
                    "SELECT * FROM users WHERE fedjail=1 ORDER BY username ASC",
                    $connection);
    if ($selected == -1)
    {
        $first = 0;
    }
    else
    {
        $first = 1;
    }
    while ($r = mysql_fetch_array($q))
    {
        $ret .= "\n<option value='{$r['userid']}'";
        if ($selected == $r['userid'] || $first == 0)
        {
            $ret .= " selected='selected'";
            $first = 1;
        }
        $ret .= ">{$r['username']}</option>";
    }
    $ret .= "\n</select>";
    return $ret;
}

function event_add($userid, $text, $connection)
{
    $text = mysql_real_escape_string($text, $connection);
    mysql_query(
            "INSERT INTO events VALUES(NULL,$userid," . time() . ",0,'$text')",
            $connection) or die(mysql_error());
    return 1;
}

function mysql_escape($str)
{
    global $c;
    return mysql_real_escape_string($str, $c);
}

function player($pick)
{
global $ir, $userid, $r, $c;
if ($pick == 'Top')
{
if ($r['Top'] == 0)
{
return 'N/A';
}
$nu = $r['Top'];
$pick = mysql_query("SELECT * FROM players WHERE userid={$r['userid']} AND playerid={$r['Top']}", $c);
}
if ($pick == 'Mid')
{
if ($r['Mid'] == 0)
{
return 'N/A';
}
$nu = $r['Mid'];
$pick = mysql_query("SELECT * FROM players WHERE userid={$r['userid']} AND playerid={$r['Mid']}", $c);
}
if ($pick == 'Jungle')
{
if ($r['Jungle'] == 0)
{
return 'N/A';
}
$nu = $r['Jungle'];
$pick = mysql_query("SELECT * FROM players WHERE userid={$r['userid']} AND playerid={$r['Jungle']}", $c);
}
if ($pick == 'ADC')
{
if ($r['ADC'] == 0)
{
return 'N/A';
}
$nu = $r['ADC'];
$pick = mysql_query("SELECT * FROM players WHERE userid={$r['userid']} AND playerid={$r['ADC']}", $c);
}
if ($pick == 'Support')
{
if ($r['Support'] == 0)
{
return 'N/A';
}
$nu = $r['Support'];
$pick = mysql_query("SELECT * FROM players WHERE userid={$r['userid']} AND playerid={$r['Support']}", $c);
}
if ($pick == 'Coach')
{
if ($r['Coach'] == 0)
{
return 'N/A';
}
$nu = $r['Coach'];
$pick = mysql_query("SELECT * FROM players WHERE userid={$r['userid']} AND playerid={$r['Coach']}", $c);
}
if ($pick == 'Analyst')
{
if ($r['Analyst'] == 0)
{
return 'N/A';
}
$nu = $r['Analyst'];
$pick = mysql_query("SELECT * FROM players WHERE userid={$r['userid']} AND playerid={$r['Analyst']}", $c);
}
if ($pick != null && $pick != 'N/A')
{
$pick = mysql_fetch_array($pick);
return "<a href='viewplayer.php?u={$nu}'>{$pick['name']}</a>";
}
else
{
return 'N/A';
}
}

function ranking()
{
$rank = "Unranked";
if ($ir['rank'] == 1)
{
$rank = "Bronze";
}
if ($ir['rank'] == 2)
{
$rank = "Silver";
}
if ($ir['rank'] == 3)
{
$rank = "Gold";
}
if ($ir['rank'] == 4)
{
$rank = "Platinum";
}
if ($ir['rank'] == 5)
{
$rank = "Diamond";
}
if ($ir['rank'] == 6)
{
$rank = "Challenger";
}
return $rank;
}



function stack_items()
{
global $ir, $userid, $c;
$item = 109;
$qty = 0;
$stacked=false;
while ($stacked == false)
{
$spec = mysql_num_rows(mysql_query("SELECT * FROM inventory WHERE inv_userid=$userid AND inv_itemid=$item", $c));

while ($spec>0)
{
$quat = mysql_query("SELECT * FROM inventory WHERE inv_userid=$userid AND inv_itemid=$item AND inv_qty<2 LIMIT 1", $c);
if (mysql_num_rows($quat)>0)
{
mysql_query("DELETE FROM inventory WHERE inv_userid=$userid AND inv_itemid=$item AND inv_qty<2 LIMIT 1", $c);
$qty++;
}
//$tests= mysql_num_rows(mysql_query("SELECT * FROM inventory WHERE inv_userid=$userid AND inv_itemid=$item", $c));
$qua = mysql_query("SELECT * FROM inventory WHERE inv_userid=$userid AND inv_itemid=$item AND inv_qty>1 ORDER BY inv_qty LIMIT 1", $c);
if (mysql_num_rows($qua)>0)
{
$qua = mysql_fetch_array($qua);
$qty+=$qua['inv_qty'];
mysql_query("DELETE FROM inventory WHERE inv_userid=$userid AND inv_itemid=$item AND inv_qty>1 ORDER BY inv_qty LIMIT 1", $c);
}
$spec = mysql_num_rows(mysql_query("SELECT * FROM inventory WHERE inv_userid=$userid AND inv_itemid=$item ", $c));
}
if ($qty>0){
mysql_query("INSERT INTO inventory VALUES (NULL, $item, $userid, $qty)", $c);}
$qty = 0;
$item++;
if ($item > 999)
{
$stacked = true;
}
}
}


function get_request()
{
global $ir, $userid, $c;
$req =
        mysql_query(
                "SELECT * FROM requests WHERE Sid=$userid", $c);   
$req = mysql_fetch_array($req);
if ($userid == $req['Sid'])
{
return true;
} 
}

function get_rank($stat, $mykey)
{
    global $ir, $userid, $c;
    $q =
            mysql_query(
                    "SELECT count(*) FROM players WHERE $mykey > $stat",
                    $c);
    return mysql_result($q, 0, 0) + 1;
}

function get_cost()
{
global $ir, $userid, $r, $c;
                    
if ($r['userid'] == 0 && $r['age'] > 16 && $r['role'] != 5)     	     	
{
$elos = pow($r['elo'], 2);
$eloss = pow($r['elo'], 2.005);
if ($r['elo'] > 2480) {$cost =  0.2551275510204082*($eloss)-872.5719387755101*($r['elo'])+694841.918367347;} //challenger 
else if ($r['elo'] <= 1150) {$cost =  (0.00006547619047619048*$elos)-(0.03422619047619048*($r['elo']))+2.767857142857143;} //Bronze 
else if ($r['elo'] <= 1500) {$cost =  -0.005702137998056365*($elos)+16.31508746355685*($r['elo'])-11170.273080660836;} //Silver
else if ($r['elo'] <= 1850) {$cost =  0.01278061224489796*($elos)-(36.56683673469388*($r['elo']))+26594.877551020407;} //Gold
else if ($r['elo'] <= 2200) {$cost =  (0.05104591836734694*($elos))-(174.59489795918367*($r['elo']))+150296.9056122449; }  //Plat
else if ($r['elo'] <= 2480) {$cost =  0.2551275510204082*($elos)-872.5719387755101*($r['elo'])+694841.918367347;} //Diamond 
$cost =money_formatter(round($cost));
return $cost;
}
else if($r['userid'] != 0)
{
return 'Contracted';
}
else if ($r['age'] <= 16)
{
return 'Restricted';
}
else if ($r['role'] == 5)
{
$cost = pow($r['cexp'],2.5)+$r['cexp']*2+50;
}
}

function player_ranks($lol)
{
global $ir, $userid, $c;
$r = $lol;
	if ($r['elo'] <= 870) { $rankk = "<font color='CC9900'>Bronze V</font>"; }
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
     	return $rankk;
}


function player_ranking()
{
global $ir, $userid, $r, $c;
	if ($r['elo'] <= 870) { $rankk = "<font color='CC9900'>Bronze V</font>"; }
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
     	return $rankk;
}



function house($current)
{
global $ir, $userid, $c;
$se = mysql_query("SELECT * FROM housing WHERE houseid={$current}", $c);
$se = mysql_fetch_array($se);
$se = $se['name'];
return $se;
}


/**
 * Given a password input given by the user and their actual details,
 * determine whether the password entered was correct.
 *
 * Note that password-salt systems don't require the extra md5() on the $input.
 * This is only here to ensure backwards compatibility - that is,
 * a v2 game can be upgraded to use the password salt system without having
 * previously used it, without resetting every user's password.
 *
 * @param string $input The input password given by the user.
 * 						Should be without slashes.
 * @param string $salt 	The user's unique pass salt
 * @param string $pass	The user's encrypted password
 *
 * @return boolean	true for equal, false for not (login failed etc)
 *
 */
function verify_user_password($input, $salt, $pass)
{
    return ($pass === encode_password($input, $salt));
}

/**
 * Given a password and a salt, encode them to the form which is stored in
 * the game's database.
 *
 * @param string $password 		The password to be encoded
 * @param string $salt			The user's unique pass salt
 * @param boolean $already_md5	Whether the specified password is already
 * 								a md5 hash. This would be true for legacy
 * 								v2 passwords.
 *
 * @return string	The resulting encoded password.
 */
function encode_password($password, $salt, $already_md5 = false)
{
    if (!$already_md5)
    {
        $password = md5($password);
    }
    return md5($salt . $password);
}

/**
 * Generate a salt to use to secure a user's password
 * from rainbow table attacks.
 *
 * @return string	The generated salt, 8 alphanumeric characters
 */
function generate_pass_salt()
{
    return substr(md5(microtime_float()), 0, 8);
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}

/**
 *
 * @return string The URL of the game.
 */
function determine_game_urlbase()
{
    $domain = $_SERVER['HTTP_HOST'];
    $turi = $_SERVER['REQUEST_URI'];
    $turiq = '';
    for ($t = strlen($turi) - 1; $t >= 0; $t--)
    {
        if ($turi[$t] != '/')
        {
            $turiq = $turi[$t] . $turiq;
        }
        else
        {
            break;
        }
    }
    $turiq = '/' . $turiq;
    if ($turiq == '/')
    {
        $domain .= substr($turi, 0, -1);
    }
    else
    {
        $domain .= str_replace($turiq, '', $turi);
    }
    return $domain;
}

/**
 * Get the file size in bytes of a remote file, if we can.
 *
 * @param string $url	The url to the file
 *
 * @return int			The file's size in bytes, or 0 if we could
 * 						not determine its size.
 */

function get_filesize_remote($url)
{
    // Retrieve headers
    if (strlen($url) < 8)
    {
        return 0; // no file
    }
    $is_ssl = false;
    if (substr($url, 0, 7) == 'http://')
    {
        $port = 80;
    }
    else if (substr($url, 0, 8) == 'https://' && extension_loaded('openssl'))
    {
        $port = 443;
        $is_ssl = true;
    }
    else
    {
        return 0; // bad protocol
    }
    // Break up url
    $url_parts = explode('/', $url);
    $host = $url_parts[2];
    unset($url_parts[2]);
    unset($url_parts[1]);
    unset($url_parts[0]);
    $path = '/' . implode('/', $url_parts);
    if (strpos($host, ':') !== false)
    {
        $host_parts = explode(':', $host);
        if (count($host_parts) == 2 && ctype_digit($host_parts[1]))
        {
            $port = (int) $host_parts[1];
            $host = $host_parts[0];
        }
        else
        {
            return 0; // malformed host
        }
    }
    $request =
            "HEAD {$path} HTTP/1.1\r\n" . "Host: {$host}\r\n"
                    . "Connection: Close\r\n\r\n";
    $fh = fsockopen(($is_ssl ? 'ssl://' : '') . $host, $port);
    if ($fh === false)
    {
        return 0;
    }
    fwrite($fh, $request);
    $headers = array();
    $total_loaded = 0;
    while (!feof($fh) && $line = fgets($fh, 1024))
    {
        if ($line == "\r\n")
        {
            break;
        }
        if (strpos($line, ':') !== false)
        {
            list($key, $val) = explode(':', $line, 2);
            $headers[strtolower($key)] = trim($val);
        }
        else
        {
            $headers[] = strtolower($line);
        }
        $total_loaded += strlen($line);
        if ($total_loaded > 50000)
        {
            // Stop loading garbage!
            break;
        }
    }
    fclose($fh);
    if (!isset($headers['content-length']))
    {
        return 0;
    }
    return (int) $headers['content-length'];
}
// GPC fix: added in 1.1.1
if (version_compare(PHP_VERSION, '5.4.0-dev') < 0
        && function_exists('get_magic_quotes_gpc'))
{
    $_core_gpc_on = get_magic_quotes_gpc();
}
else
{
    $_core_gpc_on = false;
}
if (!$_core_gpc_on)
{
    foreach ($_POST as $k => $v)
    {
        $_POST[$k] = addslashes($v);
    }
    foreach ($_GET as $k => $v)
    {
        $_GET[$k] = addslashes($v);
    }
}
// Error reporting we want
@error_reporting(E_ALL & ~E_NOTICE);
// Tidy?
if (class_exists('tidy'))
{

    function tidy_test()
    {
        $html = ob_get_clean();

        // Specify configuration
        $config =
                array('indent' => true, 'output-xhtml' => true, 'wrap' => 200);

        // Tidy
        $tidy = new tidy;
        $tidy->parseString($html, $config, 'latin1');
        $tidy->cleanRepair();

        // Output
        echo $tidy;
    }
    ob_start();
    register_shutdown_function('tidy_test');
}