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

/* Model class for a controller. */
class Controller {
	private $_controller = '';
	private $_action = '';
	protected $params = array();

	public function __construct($action = '', $params = '') {
		// Calculate the name of the controller they're instantiating. This class is intended to be used through its class extensions, and all of
		// those extensions are named in the format of 'ItemController', where 'Item' represents whatever the name of the controller should be.
		// The 'Controller' text will remain constant for all extensions, only that 'Item' will change.
		$controller = strtolower(preg_replace('/^(.+)Controller$/', '$1', get_class($this)));

		// If there's a method with the name of the specified action, this controller is valid and can be constructed.
		if(method_exists(get_class($this), $action)) {
			$this->_controller = $controller;
			$this->_action = $action;
			$this->params = $params;

		// Otherwise, if the method doesn't exist, throw an error.
		} else {
			throw new Exception("Controller $controller does not have an action named: $action");
		}
	}

	public function __get($name) {
		if(method_exists($this, 'get' . ucfirst($name)))
			return $this->{'get' . ucfirst($name)}();
		else
			throw new Exception("Property $name is invalid.");
	}

	public function render($parameters = array(), $layout = 'application', $view_override = '') {
		// The $smarty object has already been instantiated up in the framework level. We just need to access it down here.
		global $smarty;
		global $request;

		// Prep the parameters to be used in the template
		$smarty->assign($parameters);

		// It's possible for a user to override the view that was supposed to render for this action and instead render something else.
		// Prepare the $view variable so that the override will be handled, if applicable.
		$view = strlen(trim($view_override)) ? $view_override : $this->_action;

		// The output will be collected into $output and printed out if there are no errors.
		$output = '';

		// If a layout-specific version of a view template exists, use it.
		if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/views/' . $this->_controller . '/' . $view . '.' . $layout . '.tpl')) {

			// Render any errors or messages that may be available
			// TODO: I really don't like this file name or how we're handling errors and messages. Discuss with Aaron.
			include $_SERVER['DOCUMENT_ROOT'] . '/lib/framework/messages.php';

			$view_output = $smarty->fetch($_SERVER['DOCUMENT_ROOT'] . '/views/' . $this->_controller . '/' . $view . '.' . $layout . '.tpl');
		} elseif(file_exists($_SERVER['DOCUMENT_ROOT'] . '/views/' . $this->_controller . '/' . $view . '.tpl')) {
			// If there is no layout-specific template, use the normal one.

			// Render any errors or messages that may be available
			// TODO: I really don't like this file name or how we're handling errors and messages. Discuss with Aaron.
			include $_SERVER['DOCUMENT_ROOT'] . '/lib/framework/messages.php';

			$view_output = $smarty->fetch($_SERVER['DOCUMENT_ROOT'] . '/views/' . $this->_controller . '/' . $view . '.tpl');
		} else {
			// If the normal view template doesn't exist, this is a fatal error.
			throw new Exception("There is no view available for the $view action of the $this->_controller controller.");
		}

		$smarty->assign(array(
			$request->getSetting('scope_prefix').'request' => array(
				'controller' => $this->_controller,
				'action' => $this->_action,
				'view' => $view_output
			)
		));

		// Attempt to find the layout template itself.
		if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/layouts/' . $layout . '.tpl')) {
			$smarty->display($_SERVER['DOCUMENT_ROOT'] . '/layouts/' . $layout . '.tpl');
		} else {
			// If the layout template doesn't exist, this is a fatal error.
			throw new Exception("There is no layout available with the name $layout.");
		}

	}
}
