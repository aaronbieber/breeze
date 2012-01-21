<?
/* This file is part of Breeze.
 * 
 * Breeze is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Breeze is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Breeze.  If not, see <http://www.gnu.org/licenses/>.
 */

// Create a new MySQL connection to the primary database
$db = new mysqli(
	'',	// Server address
	'',	// Username
	'',	// Password
	''	// Database name
);

// If the connection failed, throw an error
if($db->connect_error)
	die('Connect Error (' . $db->connect_errno . ') '. $db->connect_error);
?>
