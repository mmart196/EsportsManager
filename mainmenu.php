<?php
/*
MCCodes FREE
mainmenu.php Rev 1.1.0c
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

if (strpos($_SERVER['PHP_SELF'], "mainmenu.php") !== false)
{
    exit;
}
global $c, $ir;
    print"
<a href='status.php'><img src='Status.png'></a><br />";  			//Check how your team is doing

//print" <a href='manage.php'>Manage(NOT DONE)</a><br />";  	 //Manage your team's training regiment and focus

print" <a href='scout.php'><img src='scout.png'></a><br />";            //Scout for talent that pops up everyday

//print" <a href='shop.php'>Shop(NOT DONE)</a><br />";	 //Shop for equipment to enhance your teams performance

print" <a href='realestate.php'><img src='real estate.png'></a><br />";	 //Buy a house for your team

print" <a href='compete.php'><img src='compete.png'></a><br />";     //Compete in tournaments for prize money

//print" <a href='search.php'>Team Search(NOT DONE)</a><br />";	 //Check out other teams

print" <a href='userlist.php'><img src='user list.png'></a><br />";  

print" <a href='viewuser.php?u={$ir['userid']}'><img src='profile.png'></a><br />";   //Check out your profile

print" <a href='logout.php'><img src='logout.png'></a><br /><br />";