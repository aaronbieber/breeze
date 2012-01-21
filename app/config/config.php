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

// Set up some variables used throughout the app
$GLOBALS['errors'] = array();						// Errors
$GLOBALS['success'] = array();						// Success messages
$GLOBALS['errors_stop'] = array();					// Errors that necessitate stopping further loading of the current page

$_SERVER['environment'] = 'development';			// Which environment we're currently in

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/common/helpers.php';			// Common helper functions
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';			// DB connection
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/framework/framework.php';	// Framework for this whole shebang
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/session.php';				// Session manipulation
?>
