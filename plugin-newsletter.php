<?php
	/*
		Plugin Name: Plugin: Newsletter
		Plugin URI: http://hovida-design.de
		Description: Newsletter mit Widget und Seitenfunktion
		Author: Adrian Preuß
		Version: 1.5
		Author URI: mailto:a.preuss@hovida-design.de
	*/
	
	ob_start();
	include("database.php");
	
	class plugin_newsletter extends WP_Widget {
		private static $widget_name = "Newsletter";
		private static $widget_class = "plugin_newsletter";
		private static $db;
		
		function plugin_newsletter() {
			self::$db = new plugin_newsletter_database;
			$options = array('classname' => self::$widget_class, 'description' => __('Newsletter mit Widget und Seitenfunktion'));
			$control = array('id_base' => self::$widget_class);
			self::WP_Widget(self::$widget_class, self::$widget_name, $options, $control);
		}
		
		function init() {
			register_widget(self::$widget_class);
		}
		
		function admin_init() {
			wp_enqueue_script('jquery');
			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox.css', '/' . WPINC . '/js/thickbox/thickbox.css', null, '1.0');
			wp_enqueue_script('editor.js', '/wp-content/plugins/plugin-newsletter/editor/editor.js', null, '1.0');
			wp_enqueue_script('plugin-newsletter.js', '/wp-content/plugins/plugin-newsletter/plugin-newsletter.js', null, '1.0');
			wp_enqueue_style(self::$widget_class, "/wp-content/plugins/plugin-newsletter/plugin-newsletter.css");
		}
		
		/* Backend :: Widget */
		function form($instance) {
			print "Bitte nutzen Sie die Globalen Einstellungen des Newsletter-Plugins (<a href=\"options-general.php?page=" . self::$widget_class . "\">zu den Einstellungen</a>).";
		}
		
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			return $instance;
		}
		
		/* Frontend :: Widget */
		function widget($args, $instance) {
			global $post;
			
			if(isset($_POST['newsletter_submit'])) {
				if(is_email($_POST['newsletter_email']) != false) {
					if($_POST['newsletter_type'] == "in") {
						if(self::$db->check_email($_POST['newsletter_email'], 0) == 1) {
							self::$db->status_in($_POST['newsletter_email'], self::getIP());
							$info = "<span style=\"color: #093;\">E-Mail erfolgreich eingetragen.</span>";
						} else {
							if(strlen($_POST['newsletter_name']) >= 3) {
								self::$db->status_add($_POST['newsletter_email'], $_POST['newsletter_name'], self::getIP());
								$info = "<span style=\"color: #093;\">E-Mail erfolgreich eingetragen.</span>";
							} else {
								$info = "Bitte geben Sie Ihren Namen ein.";
							}
						}
					} else if($_POST['newsletter_type'] == "out") {
						if(self::$db->check_email($_POST['newsletter_email'], 1) == 1) {
							$info = "<span style=\"color: #093;\">E-Mail erfolgreich ausgetragen.</span>";
							self::$db->status_out($_POST['newsletter_email'], self::getIP());
						} else {
							$info = "<span style=\"color: #FF0000;\">Bitte überprüfen Sie die angegebene E-Mail Adresse.</span>";
						}
					}
				} else {
					$info = "<span style=\"color: #FF0000;\">Bitte überprüfen Sie die angegebene E-Mail Adresse.</span>";
				}
			} else {
				$info = "Bitte geben Sie Ihre E-Mail Adresse ein.";
			}
			
			print "<div class=\"widget widget_newsletter\">
					<h4>Newsletter</h4>
					<p style=\"padding:10px 0 0 8px;\">" . $info . "</p>
					<form style=\"padding:10px 0 0 8px;\" method=\"post\" action\"?newsletter\">
						<p><label for=\"newsletter_name\">Ihr Name:</label><input id=\"newsletter_name\" type=\"text\" name=\"newsletter_name\" value=\"\" /></p>
						<p><label for=\"newsletter_email\">Ihre E-Mail:</label><input id=\"newsletter_email\" type=\"text\" name=\"newsletter_email\" value=\"\" /></p>
						<p>
							<input type=\"radio\" name=\"newsletter_type\" value=\"in\" CHECKED /> Eintragen <input type=\"radio\" name=\"newsletter_type\" value=\"out\" /> Austragen
							<input id=\"newsletter_button\" type=\"submit\" name=\"newsletter_submit\" value=\"Weiter\" />
						</p>
					</form>
				</div>";
		}
		
		/* Frontend :: Page */
		function formular() {
			if(isset($_POST['newsletter_submit'])) {
				// Check Email
				if(is_email($_POST['newsletter_email']) != false) {
					if($_POST['newsletter_type'] == "in") {
						// EXIST ON SYS?
						if(self::$db->check_email($_POST['newsletter_email'], 1) == 1) {
							$info = "Sie sind bereits eingetragen.";
						} else {
							$info = "E-Mail erfolgreich eingetragen.";
							if(self::$db->check_email($_POST['newsletter_email'], 0) == 1) {
								self::$db->status_in($_POST['newsletter_email'], self::getIP());
								self::sendVerify($_POST['newsletter_email'], $_POST['newsletter_name'], self::getIP());
							} else {
								if(strlen($_POST['newsletter_name']) >= 3) {
									self::$db->status_add($_POST['newsletter_email'], $_POST['newsletter_name'], self::getIP());
									self::sendVerify($_POST['newsletter_email'], $_POST['newsletter_name'], self::getIP());
								} else {
									$info = "Bitte geben Sie Ihren Namen ein.";
								}
							}
						}
					} else if($_POST['newsletter_type'] == "out") {
						if(self::$db->check_email($_POST['newsletter_email'], 0) == 1) {
							$info = "E-Mail erfolgreich ausgetragen.";
							self::$db->status_out($_POST['newsletter_email'], self::getIP());
						} else {
							$info = "Bitte überprüfen Sie die angegebene E-Mail Adresse.";
						}
					}
				} else {
					$info = "Bitte überprüfen Sie die angegebene E-Mail Adresse.";
				}
			} else {
				$info = "Bitte geben Sie Ihre E-Mail Adresse ein.";
			}
			
			if(isset($_GET['authToken']) && $_GET['authToken'] != "") {
				$auth = self::$db->check_auth($_GET['authToken']);
				if(count($auth) == 1) {
					self::$db->set_auth($_GET['authToken']);
					$info = "Die E-Mail Adresse <strong>" . $auth[0]->email . "</strong> wurde erfolgreich verifiziert.";
				} else {
					$info = "Bei der verifizierung ist ein Fehler aufgetreten.";
				}
			}
			
			return "<div class=\"widget widget_newsletter\">
					<p>" . $info . "</p>
					<form method=\"post\" action\"?newsletter\">
						<p><label for=\"newsletter_name\">Ihr Name:</label><input id=\"newsletter_name\" type=\"text\" name=\"newsletter_name\" value=\"\" /></p>
						<p><label for=\"newsletter_email\">Ihre E-Mail:</label><input id=\"newsletter_email\" type=\"text\" name=\"newsletter_email\" value=\"\" /></p>
						<p>
							<input type=\"radio\" name=\"newsletter_type\" value=\"in\" CHECKED /> Eintragen <input type=\"radio\" name=\"newsletter_type\" value=\"out\" /> Austragen
							<input type=\"submit\" name=\"newsletter_submit\" value=\"Weiter\" />
						</p>
					</form>
				</div>";
		}
		
		function frontend($content) {
			if(preg_match("[plugin_newsletter]", $content)) {
				$content = str_replace("[plugin_newsletter]", self::formular(), $content);
			}
			
			return $content;
		}
		
		function getAuthURL($authToken) {
			global $post;
			
			$url = "";
			$url .= get_bloginfo("siteurl");
			
			if(get_option('permalink_structure') == NULL) {
				if($post->post_type == "page") {
					$url .= "?page_id=" . $post->ID;
				} else {
					$url .= "?p=" . $post->ID;
				}
				$url .= "&";
			} else {
				$url .= "/" . $post->post_name;
				$url .= "/?";
			}
			$url .= "authToken=" . $authToken;
			
			return $url;
		}
		
		function sendVerify($email, $name, $ip) {
			$ordner = "wp-content/plugins/plugin-newsletter/templates";
			$user = null;
			$user->email = $email;
			$user->name = $name;
			$user->ip = $ip;
			$authURL = self::getAuthURL(MD5($email));
			$message = "Hallo <strong>[USER_NAME]</strong>,<br /><br />Sie haben sich erfolgreich in unserem Newsletter eingetragen. Bevor Sie aber weitere Newsletter-Emails von uns erhalten, müssen Sie Ihre E-Mail Adresse bestätigen.<br /><br />Bitte aktivieren Sie Ihre E-Mail Adresse unter folgendem Link:<br /><a href=\"" . $authURL . "\" target=\"_blank\">" . $authURL . "</a><br /><br />Vielen Dank.";
			self::sendMail("Newsletter - Aktivierung", $ordner . "/template_1.html", $message, $user);
		}
		
		/* Navigation */
		function navigation() {
			add_submenu_page("options-general.php", self::$widget_name, self::$widget_name, 0, self::$widget_class, array('plugin_newsletter', 'backend'));
		}
		
		/* Backend */
		function backend() {
			global $wpdb;
			
			print "<div id=\"" . self::$widget_class . "\">";
			include("navigation.php");
			if(!isset($_GET['site'])) {
				$_GET['site'] = "newsletter";
			}
			
			switch($_GET['site']) {
				case "users":			$page = "users";		break;
				case "templates":		$page = "templates";	break;
				case "newsletter":		$page = "newsletter";	break;
				default:				$page = "newsletter";	break;
			}
			include("admin/" . $page . ".php");
			print "</div>";
		}
		
		/* Get rewrited ip address*/
		function getIP() {
			return $_SERVER['REMOTE_ADDR'];
		}
		
		function sendMail($subject, $template, $text, $user) {
			$html = file_get_contents($template);
			
			$html = preg_replace("/\/\*(.*)\*\//Uis", "", $html);
			$html = str_replace("[TITLE]", get_bloginfo("name"), $html);
			$html = str_replace("[SUBJECT]", $subject, $html);
			$text = str_replace("[USER_NAME]", $user->name, $text);
			$text = str_replace("[USER_EMAIL]", $user->email, $text);
			$html = str_replace("[TEXT]", $text, $html);
			
			$header = "From: " . get_bloginfo("name") . " <" . get_bloginfo("admin_email") . ">\r\n";
			wp_mail(plugin_abo::getSession("customer_email"), "Abonnement - Bestellung", utf8_decode($text), $header);
		}
		
		function getTemplate($file) {
			$template = file_get_contents($file);
			preg_match_all("/\/\*(.*)\*\//Uis", $template, $array);
			$template = str_replace("\t", "", $array[0][0]);
			$template = str_replace("/*", "", $template);
			$template = str_replace("*/", "", $template);
			preg_match_all("/(Name|Version|Author|Type|Author Mail|Author WWW):(.*)([(\r|\n|\r\n)]+)/Uis", $template, $array2);
			$data = null;
			
			for($i = 0; $i < count($array2[1]); $i++) {
				// Name
				if(strtolower($array2[1][$i]) == "name") {
					$data->name = $array2[2][$i];
				}
				
				// Author
				if(strtolower($array2[1][$i]) == "author") {
					$data->author = $array2[2][$i];
				}
				
				// Author Mail
				if(strtolower($array2[1][$i]) == "author mail") {
					$data->author_mail = $array2[2][$i];
				}
				
				// Author WWW
				if(strtolower($array2[1][$i]) == "author www") {
					$data->author_web = $array2[2][$i];
				}
				
				// Version
				if(strtolower($array2[1][$i]) == "version") {
					$data->version = $array2[2][$i];
				}
				
				// Type
				if(strtolower($array2[1][$i]) == "type") {
					$data->type = $array2[2][$i];
				}
			}
			
			$data->id = MD5($data->name . "_" . $data->version);
			$data->file = $file;
			return $data;
		}
		
		function parseTemplate($file) {
			$html = file_get_contents($file);
			$html = preg_replace("/\/\*(.*)\*\//Uis", "", $html);			
			return $html;
		}
		
		/* Installation Routines */
		function install() {
			global $wpdb;
			
			/* Data Table*/
			$wpdb->query("CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "newsletter_emails` (
							`id` int(11) NOT NULL auto_increment,
							`email` char(255) collate latin1_german2_ci NOT NULL,
							`status` enum('0','1') collate latin1_german2_ci NOT NULL default '1',
							`name` char(255) collate latin1_german2_ci NOT NULL,
							PRIMARY KEY  (`id`)
						) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=1;");
			$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "newsletter_emails` ADD UNIQUE (`email`)");
			/* Update 1.2 */
			$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "newsletter_emails` ADD `ip_hostname` CHAR(255) NOT NULL, ADD `ip_address` CHAR(255) NOT NULL, ADD `time` INT(11) NOT NULL;");
			/* Update 1.4 */
			$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "newsletter_emails` ADD `verified` ENUM('0','1') NOT NULL default '0';");
			/* Settings Table*/
			$wpdb->query("CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "newsletter_settings` (
							`name` char(255) collate latin1_german2_ci NOT NULL,
							`value` char(255) collate latin1_german2_ci NOT NULL
						) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;");
		}
		
		function uninstall() {
			global $wpdb;
			
			/* Data Table*/
			$wpdb->query("`DROP TABLE `" . $wpdb->prefix . "newsletter_emails`");
			/* Settings Table*/
			$wpdb->query("`DROP TABLE `" . $wpdb->prefix . "newsletter_settings`");
		}
	}
	register_activation_hook(__FILE__, array('plugin_newsletter', 'install'));
	register_deactivation_hook(__FILE__, array('plugin_newsletter', 'uninstall'));
	add_action("admin_init", array('plugin_newsletter', 'admin_init'), 1);
	add_action("widgets_init", array('plugin_newsletter', 'init'), 1);
	add_action('admin_menu', array('plugin_newsletter', 'navigation'));
	add_filter('the_content', array('plugin_newsletter', 'frontend'));
?>