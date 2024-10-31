<?php
	class plugin_newsletter_database {
	
		function check_email($email, $status = 1) {
			global $wpdb;
			
			$wpdb->query("SELECT * FROM `" . $wpdb->prefix . "newsletter_emails` WHERE `email`='" . $email . "' AND status='" . $status . "' LIMIT 1");
			return $wpdb->num_rows;
		}
		
		function check_auth($authToken) {
			global $wpdb;
			
			return $wpdb->get_results("SELECT * FROM `" . $wpdb->prefix . "newsletter_emails` WHERE MD5(`email`)='" . $authToken . "' AND `verified`='0' LIMIT 1");
		}
		
		function set_auth($authToken) {
			global $wpdb;
			
			$wpdb->query("UPDATE `" . $wpdb->prefix . "newsletter_emails` SET `verified`='1' WHERE MD5(`email`)='" . $authToken . "' LIMIT 1");
		}
		
		function status_add($email, $name, $ip) {
			global $wpdb;
			
			$wpdb->query("INSERT INTO `" . $wpdb->prefix . "newsletter_emails` (`id`, `email`, `status`, `verified`, `name`, `ip_address`, `ip_hostname`, `time`) VALUES (null, '" . $email . "', '1', '0', '" . $name . "', '" . $ip . "', '" . gethostbyaddr($ip) . "', '" . time() . "')");
		}
		
		function status_in($email, $ip) {
			global $wpdb;
			
			$wpdb->query("UPDATE `" . $wpdb->prefix . "newsletter_emails` SET status='1', verified='0', ip_address='" . $ip . "', ip_hostname='" . gethostbyaddr($ip) . "', time='" . time() . "' WHERE email='" . $email . "' LIMIT 1");
		}
		
		function status_out($email, $ip) {
			global $wpdb;
			
			$wpdb->query("UPDATE `" . $wpdb->prefix . "newsletter_emails` SET status='0', ip_address='" . $ip . "', ip_hostname='" . gethostbyaddr($ip) . "', time='" . time() . "' WHERE email='" . $email . "' LIMIT 1");
		}
		
		function delete($email) {
			global $wpdb;
			
			$wpdb->query("DELETE FROM `" . $wpdb->prefix . "newsletter_emails` WHERE email='" . $email . "' LIMIT 1");
		}
		
		function get_option($name) {
		
		}
		
		function set_option($name, $value) {
		
		}
	}
?>