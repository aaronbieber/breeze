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

// Basic configuration prerequisites
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';

// Run the controller/action they want
$request->controller->{$request->action}();
?>
