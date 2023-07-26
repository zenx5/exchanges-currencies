<?php
/**
 * Plugin Name: Exchanges Currencies by Zenx5
 * Plugin URI: https://zenx5.pro
 * Description: ...
 * Version: 1.0.0
 * Author: Octavio Martinez
 * Author URI: https://zenx5.pro
 * Domain Path: /i18n/languages/
 * Requires at least: 5.9
 * Requires PHP: 7.2
 *
 
 */

 require_once 'classes/class-exchanges-currencies.php';
 $nameclass = 'ExchangesCurrencies';
 
 register_activation_hook(__FILE__, [$nameclass, 'activation']);
 register_deactivation_hook(__FILE__, [$nameclass, 'deactivation']);

 add_action('init', [$nameclass, 'init']);