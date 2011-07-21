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

/* Routes are defined as string paths that the user's request should match,
 * followed by an array containing, at a minimum, the controller and action
 * names that the request should be sent to.
 *
 * Any variables specified in the path string will be mapped to variables
 * available to the action.
 */
$router->add_route(
	'user/$name/view',
	array(	'controller' => 'user',
			'action' => 'view'
	)
);

$router->add_route(
	'user/$name/update',
	array(	'controller' => 'user',
			'action' => 'update'
	)
);

$router->add_route(
	'user/edit/$name',
	array(	'controller' => 'user',
			'action' => 'edit'
	)
);

$router->add_route(
	'user/delete/$name',
	array(	'controller' => 'user',
			'action' => 'delete'
	)
);

$router->add_route(
	'item/view/$id',
	array(	'controller' => 'item',
			'action' => 'view'
	)
);
?>
