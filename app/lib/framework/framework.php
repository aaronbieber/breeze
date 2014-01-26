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

// Set up our custom error handler
function catch_exceptions($exception) {
	try {
		require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/framework/errors.php';
	} catch(Exception $e) {
		print '<h1>Uncaught exception</h1>';
		var_dump($exception);
	}
}
set_exception_handler('catch_exceptions');

// Include all of the files that make this framework work
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/framework/request.php';			// Request class
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/framework/controller.php';		// Controller class
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/framework/router.php';			// Router class
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/smarty/Smarty.class.php';		// Smarty templating system

// Include all models
foreach(glob($_SERVER['DOCUMENT_ROOT'] . '/models/*.php') as $file) {
	require_once $file;
}

// Set up the Smarty object for future use
$smarty = new Smarty();
$smarty->setTemplateDir($_SERVER['DOCUMENT_ROOT'] . '/smarty/templates');
$smarty->setCompileDir($_SERVER['DOCUMENT_ROOT'] . '/smarty/templates_c');
$smarty->setCacheDir($_SERVER['DOCUMENT_ROOT'] . '/smarty/cache');
$smarty->setConfigDir($_SERVER['DOCUMENT_ROOT'] . '/smarty/configs');

// Load the routes.
$router = new Router();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/routes.php';

// Load up the request. During the instantiation, the request object will read a configuration file to determine where the data it uses is stored,
// and then parse through those data variables and populate the object as needed.
$request = new Request();
