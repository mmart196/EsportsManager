<?php
/*
MCCodes FREE
crons/cron_minute.php Rev 1.1.0c
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

require_once(dirname(__FILE__) . "/../mysql.php");
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
mysql_query("UPDATE players SET age=age+1", $c);
$q = mysql_query("SELECT * FROM players WHERE userid!=0", $c);
while($r=mysql_fetch_array($q))
{
if ($r['role'] == 5)
{
mysql_query("UPDATE players SET cexp=cexp+1 WHERE playerid={$r['playerid']}", $c);
}
}
