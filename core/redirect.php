<?php

/**
 * Force HTTPS - Redirect class
 *
 * @package 404 To Homepage
 * @subpackage 404 To Homepage Core
 */
final class FHTTPS_Core_Redirect {


	// Properties
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Single class instance
	 */
	private static $instance;



	// Initialization
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Create or retrieve instance
	 */
	public static function instance() {

		// Check instance
		if (!isset(self::$instance))
			self::$instance = new self;

		// Done
		return self::$instance;
	}



	/**
	 * Do the redirect in a header-clean way
	 */
	private function __construct() {

		// Remove existing headers
		$this->removeHeaders();

		// Do the redirection
		$this->redirect();

		// End
		die;
	}



	/**
	 * Perform the URL redirection
	 */
	private function redirect() {

		// The REQUEST URI var contains the current URL
		if (0 === strpos($_SERVER['REQUEST_URI'], 'http')) {

			// Change URL scheme
			wp_redirect(set_url_scheme($_SERVER['REQUEST_URI'], 'https' ), 301);

		// HOST/URI
		} else {

			// Compose target URL
			wp_redirect('https://'. $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], 301);
		}
	}



	/**
	 * Remove any existing header
	 */
	private function removeHeaders() {

		// Check headers list
		$headers = @headers_list();
		if (!empty($headers) && is_array($headers)) {

			// Check header_remove function (PHP 5 >= 5.3.0, PHP 7)
			$byFunction = function_exists('header_remove');

			// Enum and clean
			foreach ($headers as $header) {
				list($k, $v) = array_map('trim', explode(':', $header, 2));
				$byFunction? @header_remove($k) : @header($k.':');
			}
		}
	}



}