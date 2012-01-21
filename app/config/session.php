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

/* Name:		sessionOpen
 * Description:	Starts a new session.
 */
function sessionOpen() { }

/* Name:		sessionClose
 * Description:	Called when the session is closed. This doesn't destroy any data.
 */
function sessionClose() {}

/* Name:		sessionRead
 * Description:	Reads any data associated with the existing session and populates $_SESSION
 */
function sessionRead($session_id) {
	global $db;

	// Get the session data out of the database, if available
	$sql =
		"	SELECT	session.ip, session.data, session.last_accessed
			FROM	session
			WHERE	session.id = ?
		";
	$query = $db->stmt_init();
	$query->prepare($sql);
	$query->bind_param('s', $session_id);
	$query->execute();
	$query->store_result();
	$query->bind_result($results['ip'], $results['data'], $results['last_accessed']);
	$query->fetch();

	// If the IP recorded in the session record doesn't match the user's current IP, clear the
	//  session and don't give the user any data
	if($query->num_rows && ($results['ip'] != $_SERVER['REMOTE_ADDR'] || (strtotime("now") - strtotime($results['last_accessed'])) > 1800)) {
		// Clear the session
		$sql =
			"	DELETE FROM	session
				WHERE		session.id = ?
			";
		$query = $db->stmt_init();
		$query->prepare($sql);
		$query->bind_param('s', $session_id);

		// Clear out the return structure so they don't have the invalid session
		$results['data'] = '';
	}

	$query->close();

	// Returning the serialized data from this function automatically deserializes it and places
	//  it into the $_SESSION.
	return $results['data'];
}

/* Name:		sessionWrite
 * Description:	Writes the session data out so that it'll be usuable next time the user visits
 */
function sessionWrite($session_id, $data) {
	/* The session write function is called after all objects have been destructed. Since the
	 *  db connection is stored in an object, it's not available. We need to write to the db, so
	 *  we have to re-initialize the connection by including the database.php file. We have to use
	 *  the 'include' command instead of 'require_once' because the file's already been included
	 *  at the top of the file, so require_once wouldn't call it again if we used it.
	 */
	include $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';

	// Get the session data out of the database
	$sql =
		"	INSERT INTO	session(id, ip, user_id, data)
			VALUES		(	?,
							?,
							?,
							?
						)
			ON DUPLICATE KEY
				UPDATE	user_id = ?,
						data = ?,
						last_accessed = CURRENT_TIMESTAMP()
		";

	$user_id = (!empty($_SESSION['user'])) ? $_SESSION['user']->getID() : NULL;

	$query = $db->stmt_init();
	$query->prepare($sql);
	$query->bind_param('ssisis', $session_id, $_SERVER['REMOTE_ADDR'], $user_id, $data, $user_id, $data);
	$query->execute();
	$query->close();
}

/* Name:		sessionDestroy
 * Description:	Destroys the session
 */
function sessionDestroy() { }

/* Name:		sessionGarbageCollect
 * Description:	Cleans up any sessions that are ready for deletion.
 */
function sessionGarbageCollect() { }

// Set the session function handlers
session_set_save_handler(
	"sessionOpen",
	"sessionClose",
	"sessionRead",
	"sessionWrite",
	"sessionDestroy",
	"sessionGarbageCollect"
);

// Start this effin session
session_start();

if(!array_key_exists('user', $_SESSION))
	$_SESSION['user'] = new User;
?>
