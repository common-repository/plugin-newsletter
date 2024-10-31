<?php
	$ordner = "../wp-content/plugins/plugin-newsletter/templates";
	$info = "";
	
	if(isset($_GET['action']) && $_GET['action'] != "") {
		if(!file_exists($ordner . "/" . $_GET['template'])) {
			header("Location: options-general.php?page=plugin_newsletter&site=templates&error=not_exists");
			exit;
		}
		
		switch($_GET['action']) {
			case "edit":
				$template = self::getTemplate($ordner . "/" . $_GET['template']);
				
				if(isset($_POST['save']) || isset($_POST['preview'])) {
					$settings = "/*
							Name:			" . $template->name . "
							Author:			" . $template->author . "
							Version:		" . $template->version . "
							Type:			" . $template->type . "
							Author Mail:	" . $template->author_mail . "
							Author WWW:		" . $template->author_web . "
						*/";
					$settings = str_replace("\t\t\t\t\t\t", "", $settings);
					$html = $settings . "\n" . stripslashes($_POST['html']);
					$file = $ordner . "/" . $_GET['template'];
					if(is_writable($file)) {
						$handler = fopen($file, "w");
						fwrite($handler, $html);
						fclose($handler);
						$info = "Das E-Mail Template wurde erfolgreich gespeichert.";
					} else {
						$info = "Fehler: Beim Schreiben des Templates ist ein Fehler aufgetreten.<br />Ist eventuell die Berechtigung falsch? Diese muss mindestens auf <strong>666</strong> oder <strong>0666</strong> liegen.";
					}
					if(isset($_POST['preview'])) {
						$info .= " Die Vorschau des Templates wird nun geöffnet...";
						print "<script type=\"text/javascript\">newsletterPreview('" . $_GET['template'] . "');</script>";
					}
				}
				
				$html = self::parseTemplate($ordner . "/" . $_GET['template'], false);
				?>
					<form method="post" name="form1" action="options-general.php?page=plugin_newsletter&site=templates&template=<?php print $_GET['template']; ?>&action=edit">
						<h3>Template "<?php print $template->name; ?>" bearbeiten</h3>
						<?php
							if(isset($info) && $info != "") {
								print "<div class=\"updated below-h2\" id=\"message\"><p>" . $info . "</p></div><br />";
							}
						?>
						<table class="widefat">
							<thead>
								<tr>
									<th scope="col"><?php print $template->name; ?></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Version:</td>
									<td><?php print $template->version; ?></td>
								</tr>
								<tr>
									<td>Author:</td>
									<td>
										<?php print $template->author; ?>
										<br />E-Mail: <a href="mailto: <?php print $template->author_mail; ?>"><?php print $template->author_mail; ?></a>
										<br />Internet: <a href="http://<?php print $template->author_web; ?>"><?php print $template->author_web; ?></a>
									</td>
								</tr>
								<tr>
									<td colspan="2">Inhalt:</td>
								</tr>
								<tr>
									<td colspan="2">
										<textarea class="codeedit html lineNumbers" style="width: 100%; height: 400px;" name="html" wrap="off"><?php print $html; ?></textarea>
									</td>
								</tr>
							</tbody>
						</table>
						<br />
						<div style="float: right">
							<input type="submit" value="Speichern &amp; Vorschau" class="button-primary" name="preview">
							<input type="submit" value="Speichern" class="button-primary" id="submit" name="save">
						</div>
					</form>
				<?php
			break;
			case "delete":
				if(@unlink($ordner . "/" . $_GET['template'])) {
					header("Location: options-general.php?page=plugin_newsletter&site=templates&error=deleted");
				} else {
					header("Location: options-general.php?page=plugin_newsletter&site=templates&error=no_deleted");
				}
				exit;
			break;
			default:
				header("Location: options-general.php?page=plugin_newsletter&site=templates");
				exit;
			break;
		}
	} else {
	?>
		<h3>Templates</h3>
		<p>Weitere Templates findest du in der Plugin-Store:
		<?php
			if(file_exists("../wp-content/plugins/plugin-store/")) {
				print "<a href=\"options-general.php?page=plugin_store\">Plugin-Store öffnen</a></p>";
			} else {
				print "<a href=\"plugin-install.php?tab=plugin-information&plugin=plugin-store&TB_iframe=true&width=600&height=550\" class=\"thickbox\">Plugin-Store installieren</a></p>";
			}
			
			if(isset($_GET['error']) && $_GET['error'] != "") {
				switch($_GET['error']) {
					case "not_exists":
						$info = "Das von Ihnen gewählte E-Mail Template existiert nicht.";
					break;
					case "deleted":
						$info = "Das Template wurde erfolgreich gelöscht.";
					break;
					case "no_deleted":
						$info = "Beim Löschen ist ein Fehler aufgetreten. Das Template konnte nicht gelöscht werden.";
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
					<th scope="col">Name</th>
					<th scope="col">Version</th>
					<th scope="col">Typ</th>
					<th scope="col">Aktion</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$handle = opendir($ordner);
				while($file = readdir($handle)) {
					if($file != "." && $file != "..") {
						if(!is_dir($ordner . "/" . $file)) {
							$template = self::getTemplate($ordner . "/" . $file);
							print "<tr>";
							print "<td>
										<strong>" . $template->name . "</strong>
										<div class=\"row-actions\">
											Author: <a href=\"http://" . $template->author_web . "\">" . $template->author . "</a> | <a href=\"mailto: " . $template->author_mail . "\">" . $template->author_mail . "</a>
										</div>
								</td>";
							print "<td>" . $template->version . "</td>";
							print "<td>" . $template->type . "</td>";
							print "<td>";
							print " <a href=\"options-general.php?page=plugin_newsletter&site=templates&template=" . $file . "&action=delete\" onclick=\"if(confirm('Möchten Sie dieses Template wirklich löschen?')) return; else return false;\">Löschen</a> -";
							print " <a href=\"options-general.php?page=plugin_newsletter&site=templates&template=" . $file . "&action=edit\">Bearbeiten</a> -";
							print "</td>";
							print "</tr>";
						}
					}
				}
			?>
			</tbody>
		</table>
	<?php
	}
?>