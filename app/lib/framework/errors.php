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
?>
<html>
<head>
	<style>
		body {
			font-family: verdana, helvetica, sans-serif;
			font-size: 10pt;
		}

		h2 {
			margin-bottom: 5px;
		}

		table.info {
			border-top: #999 1px solid;
			border-left: #999 1px solid;
			width: 100%;
			font-size: 1em;
		}
		table.info th {
			border-right: #999 1px solid;
			border-bottom: #999 1px solid;
			text-align: left;
			vertical-align: top;
			padding: 5px 10px;
			width: 100px;
		}
		table.info td {
			border-right: #999 1px solid;
			border-bottom: #999 1px solid;
			padding: 5px 10px;
		}
		table.info tr.row1 {
			background-color: #f3f3f3;
		}
		table.info td.trace {
			padding: 0;
		}
		table.info tr.nodata td {
			font-style: italic;
		}
		
		table.trace {
			font-size: 1em;
			width: 100%;
		}
		table.trace th {
			border: none;
			width: auto;
		}
		table.trace td {
			border: none;
		}
		table.trace th.line, table.trace th.file {
			padding: 5px 5px 5px 10px;
		}
		table.trace td.line {
			padding: 5px 5px 5px 10px;
			width: 40px;
		}
		table.trace td.file {
			width: auto;
			padding: 5px 10px 5px 5px;
		}
	</style>
</head>
<body>
	<h2>Error Details</h2>
	<table class="info" border="0" cellpadding="0" cellspacing="0">
		<tr class="row1">
			<th>Message:</th>
			<td><?=$exception->getMessage();?></td>
		</tr>
		<tr>
			<th>File:</th>
			<td><?=$exception->getFile();?></td>
		</tr>
		<tr class="row1">
			<th>Line:</th>
			<td><?=$exception->getLine();?></td>
		</tr>
		<tr>
			<th>Trace:</th>
			<td class="trace">
				<table class="trace" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<th class="line">Line</th>
						<th class="file">File</th>
					</tr>
					<? 
						foreach($exception->getTrace() as $x_exception => $line) {
							echo '<tr class="row' . ($x_exception % 2 + 1) . '">';
							echo '<td class="line">' . $line['line'] . '</td>';
							echo '<td class="file">' . $line['file'] . '</td>';
							echo '</tr>';
						}
					?>
				</table>
			</td>
		</tr>
	</table>

	<h2>Page &amp; User</h2>
	<table class="info" border="0" cellpadding="0" cellspacing="0">
		<tr class="row1">
			<th>URI:</th>
			<td>
				<?
					$uri = array_key_exists('HTTPS', $_SERVER) ? 'https://' : 'http://';
					$uri .= $_SERVER['HTTP_HOST'];
					$uri .= $_SERVER['REQUEST_URI'];
					echo $uri;
				?>
			</td>
		</tr>
		<tr>
			<th>Script:</th>
			<td><?=$_SERVER['SCRIPT_NAME'];?></td>
		</tr>
		<tr class="row1">
			<th>Query String:</th>
			<td><?=$_SERVER['QUERY_STRING'];?></td>
		</tr>
		<tr>
			<th>User IP:</th>
			<td><?=$_SERVER['REMOTE_ADDR'];?></td>
		</tr>
		<tr class="row1">
			<th>User Agent:</th>
			<td><?=$_SERVER['HTTP_USER_AGENT'];?></td>
		</tr>
	</table>

	<h2>URL Variables</h2>
	<table class="info" border="0" cellpadding="0" cellspacing="0">
		<?
			if(!empty($_GET))
				echo '<tr class="nodata"><td>No URL variables used.</td></tr>';
			else {
				$row = 0;
				foreach($_GET as $key => $value) {
					echo '<tr class="row' . ($row % 2 + 1) . '">';
					echo '<th>' . $key . '</th>';
					echo '<td>' . $value . '</td>';
					echo '</tr>';
					$row++;
				}
			}
		?>
	</table>

	<h2>Form Variables</h2>
	<table class="info" border="0" cellpadding="0" cellspacing="0">
		<?
			if(empty($_POST))
				echo '<tr class="nodata"><td>No form variables used.</td></tr>';
			else {
				$row = 0;
				foreach($_POST as $key => $value) {
					echo '<tr class="row' . ($row % 2 + 1) . '">';
					echo '<th>' . $key . '</th>';
					echo '<td>' . $value . '</td>';
					echo '</tr>';
					$row++;
				}
			}
		?>
	</table>

	<h2>Cookies</h2>
	<table class="info" border="0" cellpadding="0" cellspacing="0">
		<?
			if(empty($_COOKIE))
				echo '<tr class="nodata"><td>No cookies available.</td></tr>';
			else {
				$row = 0;
				foreach($_COOKIE as $key => $value) {
					echo '<tr class="row' . ($row % 2 + 1) . '">';
					echo '<th>' . $key . '</th>';
					echo '<td>' . $value . '</td>';
					echo '</tr>';
					$row++;
				}
			}
		?>
	</table>
</body>
</html>
