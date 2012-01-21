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

/* Model class for a page request. */
class Request {
	private $_controller = '';
	private $_action = '';
	private $_settings = array();
	private $_params = array();

	public function __construct() {
		// Load the configuration options
		$this->loadConfig();

		// Extract out the controller variable
		$controller = $this->calculateController();
		
		// Extract out the action variable
		$action = $this->calculateAction();

		// Try to read in the controller. If it's not available, error.
		if(!file_exists($_SERVER['DOCUMENT_ROOT'] . '/controllers/' . $controller . '.php')) {
			throw new Exception('There is no controller file named ' . $controller . '.php in ' . $_SERVER['DOCUMENT_ROOT'] . '/controllers/');
		// If it is available, instantiate it with the desired action
		} else {
			// Populate the $this->_params data
			$this->calculateParams();

			// Record the action name
			$this->_action = $action;

			// Open the file that contains the controller and instantiate that controller into $this->_controller
			require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/' . $controller . '.php';
			$controller_class = ucfirst($controller) . 'Controller';
			$this->_controller = new $controller_class($action, $this->_params);
		}
	}

	private function loadConfig() {
		$lines = explode("\n", trim(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/lib/framework/settings.cfg')));

		foreach($lines as $line) {
			if(		substr(trim($line), 0, 1) != '#'
				&&	strlen(trim($line))
			) {
				$setting = explode('=', $line);
				$this->_settings[trim($setting[0])] = trim($setting[1]);
			}
		}

		if(!array_key_exists('controller_variable', $this->_settings))
			$this->_settings['controller_variable'] = 'pp_controller';
		if(!array_key_exists('action_variable', $this->_settings))
			$this->_settings['action_variable'] = 'pp_action';
	}

	private function calculateController() {
		$controller = '';

		if(array_key_exists($this->_settings['controller_variable'], $_GET))
			$controller = $_GET[$this->_settings['controller_variable']];
		elseif(array_key_exists($this->_settings['controller_variable'], $_POST))
			$controller = $_POST[$this->_settings['controller_variable']];

		if(!strlen(trim($controller)))
			$controller = $this->_settings['default_controller'];

		return $controller;
	}

	private function calculateAction() {
		$action = '';

		if(array_key_exists($this->_settings['action_variable'], $_GET))
			$action = $_GET[$this->_settings['action_variable']];
		elseif(array_key_exists($this->_settings['action_variable'], $_POST))
			$action = $_POST[$this->_settings['action_variable']];

		if(!strlen(trim($action)))
			$action = $this->_settings['default_action'];

		return $action;
	}

	private function calculateParams() {
		foreach(array_merge($_GET, $_POST) as $key => $value) {
			if(		$key != $this->_settings['controller_variable']
				&&	$key != $this->_settings['action_variable']
			) {
				$this->_params[$key] = trim($value);
			}
		}
	}

	public function __get($name) {
		if(method_exists($this, 'get' . ucfirst($name)))
			return $this->{'get' . ucfirst($name)}();
		else
			throw new Exception("Property $name is invalid.");
	}

	public function getController() {
		return $this->_controller;
	}
	public function getAction() {
		return $this->_action;
	}

	public function getSetting($setting) {
		if(array_key_exists($setting, $this->_settings))
			return $this->_settings[$setting];
		else
			throw new Exception("Setting $setting is invalid.");
	}
}
?>
