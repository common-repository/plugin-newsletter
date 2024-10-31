<h3>Newsletter Nutzer</h3>
<?php
	if(isset($_GET['notify']) && $_GET['notify'] != "") {
		switch($_GET['notify']) {
			case "deleted":
				$info = "Der Nutzer wurde erfolgreich gelöscht.";
			break;
			case "in":
				$info = "Der Nutzer wurde erfolgreich eingetragen.";
			break;
			case "out":
				$info = "Der Nutzer wurde erfolgreich ausgetragen.";
			break;
			default:
				$info = "";
			break;
		}
		
		if(isset($info) && $info != "") {
			print "<div class=\"updated below-h2\" id=\"message\"><p>" . $info . "</p></div><br />";
		}
	}
?>
<table class="widefat">
	<thead>
		<tr>
			<th scope="col">ID</th>
			<th scope="col">Name</th>
			<th scope="col">E-Mail</th>
			<th scope="col">Geändert am</th>
			<th scope="col">IP</th>
			<th scope="col">Status</th>
			<th scope="col">Aktion</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$users = $wpdb->get_results("SELECT * FROM `" . $wpdb->prefix . "newsletter_emails` ORDER BY `id` DESC");
		$user_counter = 0;
		if(count($users) == 0) {
			print "<tr><td colspan=\"7\" style=\"text-align: center; font-style: italic;\">Es existieren derzeit keine Nutzer</td></tr>";
		} else {
			foreach($users as $user) {
				if($user_counter % 2 == 0) {
					print "<tr class=\"alternate\">";
				} else {
					print "<tr>";
				}
				
				if(isset($_GET['delete']) && $_GET['delete'] == $user->id) {
					self::$db->delete($user->email);
					header("Location: options-general.php?page=plugin_newsletter&site=users&notify=deleted");
					exit;
				}
				
				switch($user->status) {
					case 1:
						if(isset($_GET['status']) && $_GET['status'] == $user->id) {
							self::$db->status_out($user->email, null);
							header("Location: options-general.php?page=plugin_newsletter&site=users&notify=out");
							exit;
						}
						$status = "Eingetragen";
						$action = " <a href=\"options-general.php?page=plugin_newsletter&site=users&status=" . $user->id . "\">Austrtagen</a> ";
					break;
					case 0:
						if(isset($_GET['status']) && $_GET['status'] == $user->id) {
							self::$db->status_in($user->email, null);
							header("Location: options-general.php?page=plugin_newsletter&site=users&notify=in");
							exit;
						}
						$status = "Ausgetragen";
						$action = " <a href=\"options-general.php?page=plugin_newsletter&site=users&status=" . $user->id . "\">Eintragen</a> ";
					break;
				}
				
				print "<td>" . $user->id . "</td>";
				print "<td>" . $user->name . "</td>";
				print "<td>" . $user->email . " (" . ($user->verified == 1 ? "<span style=\"color: green;\">verifiziert</span>" : "<span style=\"color: #FF0000;\">nicht verifiziert</span>") . ")</td>";
				print "<td>" . date(get_option('date_format'), $user->time). " " . date(get_option('time_format'), $user->time) . "</td>";
				print "<td>" . ($user->ip_address == null ? "<i>- Nicht vorhanden -</i>" : $user->ip_address . " (" . $user->ip_hostname . ")") . "</td>";
				print "<td>" . $status . "</td>";
				print "<td>";
				print $action;
				print "- <a href=\"options-general.php?page=plugin_newsletter&site=users&delete=" . $user->id . "\" onclick=\"if(confirm('Möchten Sie diesen Nutzer wirklich löschen?')) return; else return false;\">Löschen</a> ";
				print "</td>";
				print "</tr>";
				$user_counter++;
			}
		}
	?>
	</tbody>
</table>