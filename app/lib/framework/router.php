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
class Router {
	private $_routes = array();

	public function __construct() {
		// I don't know if this does anything.
	}

	public function add_route($path, $destination) {
		// Split up the path.
		$path_parts = explode('/', $path);

		// Map the parts of the path into our routing tree.
		// Begin at the beginning.
		$tree_node =& $this->_routes;
		for($p = 0; $p < sizeof($path_parts); $p++) {
			$part = $path_parts[$p];
			if(!array_key_exists($part, $tree_node))
				$tree_node[$part] = array();

			$tree_node =& $tree_node[$part];
		}
		$tree_node['destination'] = $destination;
	}

	public function find($path) {
		/* If the route contains variable parts, $parameters will be populated
		 * with the values from the path.
		 */
		$parameters = array();

		// Trim leading slash, if there is one.
		if(substr($path, 0, 1) == '/')
			$path = substr($path, 1);

		// Split up the path.
		$path_parts = explode('/', $path);

		$tree_node =& $this->_routes;
		for($p = 0; $p < sizeof($path_parts); $p++) {
			$part = $path_parts[$p];
			if(array_key_exists($part, $tree_node)) {
				$tree_node =& $tree_node[$part];
			} else {
				$found = false;
				// See if there is a variable match at this level
				foreach($tree_node as $node => $branch) {
					if(substr($node, 0, 1) == '$') {
						$parameters[substr($node, 1)] = $part;
						$tree_node =& $tree_node[$node];
						$found = true;
						break;
					}
				}
				if(!$found)
					break;
			}
		}
		if(array_key_exists('destination', $tree_node)) {
			$destination = $tree_node['destination'];
			$destination['params'] = $parameters;
			return $destination;
		} else {
			return false;
		}
	}

	public function dump_routes() {
		dump($this->_routes);
	}
}
