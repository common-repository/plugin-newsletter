<h2>Newsletter versenden</h2>
<?php
	$ordner = "../wp-content/plugins/plugin-newsletter/templates";
	
	if(isset($_POST['save']) && $_POST['save'] != "") {
		$users = $wpdb->get_results("SELECT * FROM `" . $wpdb->prefix . "newsletter_emails` WHERE `status`='1' AND `verified`='1' ORDER BY `id` DESC");
		foreach($users as $user) {
			self::sendMail($_POST['subject'], $ordner . "/" . $_POST['template'], $_POST['message'], $user);
		}
	}
	
	if(isset($_GET['notify']) && $_GET['notify'] != "") {
		switch($_GET['notify']) {
			case "send":
				$info = "Das Newsletter wurde erfolgreich an alle Eingetragenen Nutzer versendet.";
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
<form method="post" action="options-general.php?page=plugin_newsletter&site=newsletter">
	<table class="wp-list-table widefat fixed pages">
		<thead>
			<tr>
				<th colspan="2" class="manage-column column-title sortable desc" id="title" scope="col">
					<a href=""><span>Newsletter schreiben</span><span class="sorting-indicator"></span></a>
				</th>
			</tr>
		</thead>
		<tbody id="the-list">
			<tr>
				<td>Betreff</td>
				<td><input name="subject" style="width: 80%;" value=""></td>
			</tr>
			<tr>
				<td>Template</td>
				<td>
					<select name="template">
						<?php
							$handle = opendir($ordner);
							while($file = readdir($handle)) {
								if($file != "." && $file != "..") {
									if(!is_dir($ordner . "/" . $file)) {
										$template = self::getTemplate($ordner . "/" . $file);
										print "<option value=\"" . $file . "\">" . $template->name . "</option>";
									}
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">Nachricht</td>
			</tr>
			<tr>
				<td colspan="2"><textarea style="width: 100%;" name="message"></textarea></td>
			</tr>
		</tbody>
	</table>
	<br />
	<div style="float: right">
		<input type="submit" value="Versenden" class="button-primary" id="submit" name="save">
	</div>
</form>