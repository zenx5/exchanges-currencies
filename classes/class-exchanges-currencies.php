<?php

defined( 'ABSPATH' ) || exit;

require_once 'class-exchanges-db.php';

class ExchangesCurrencies {

    public static function activation(){
        ExChangesDB::create_all();
    }

    public static function deactivation(){
        
    }

    public static function init() {
        add_action( 'admin_menu', [__CLASS__, 'admin_menu']);
        add_action( 'wp_ajax_update_founds', [__CLASS__, 'action_update_founds']);
        add_action( 'wp_ajax_add_currency', [__CLASS__, 'action_add_currency']);
        add_action( 'wp_ajax_update_rate', [__CLASS__, 'action_update_rate']);
        add_action( 'wp_ajax_add_change', [__CLASS__, 'action_add_change']);
        add_shortcode( 'exchange-app', [__CLASS__, 'client_app']);
        add_action( 'wp_head', [__CLASS__, 'insert_resources']);
    }

    public static function insert_resources() {
        ?>
            <script src="https://cdn.tailwindcss.com"></script>
            <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
            <script src="<?='/wp-content/plugins/exchanges-currencies/template/front/app.js'?>"></script>
        <?php
    }  


    public static function client_app() {
        include_once WP_PLUGIN_DIR.'/exchanges-currencies/template/front/app.html';
    }

    public static function action_add_change() {
        $request = [
            "currency_from" => $_POST['currency_from'],
            "currency_to" => $_POST['currency_to'],
            "rate" => $_POST['rate'],
            "created_at" => date("Y-m-d")
        ];
        $Change = new ExchangesDB('ex_exchanges');
        $Change->set(0, $request );
        echo json_encode($request);
        die();
    }

    public static function action_update_rate() {
        $exchange_id = $_POST['exchange_id'];
        $rate = $_POST['rate'];
        $Change = new ExchangesDB('ex_exchanges');
        $Change->set($exchange_id, ["rate" => $rate ]);
        echo json_encode([
            "exchange_id" => $exchange_id,
            "rate" => $rate
        ]);
        die();
    }

    public static function action_add_currency(){
        $request = [
            "name" => $_POST['name'],
            "code" => $_POST['code'],
            "symbol" => $_POST['symbol'],
            "founds" => $_POST['founds'],
            "created_at" => date("Y-m-d")
        ];
        $Currency = new ExchangesDB('ex_currencies');
        $Currency->set(0, $request );
        echo json_encode($request);
        die();
    }

    public static function action_update_founds() {
        $currency_id = $_POST['currency_id'];
        $founds = $_POST['founds'];
        $Currency = new ExchangesDB('ex_currencies');
        $Currency->set($currency_id, ["founds" => $founds ]);
        echo json_encode([
            "currency_id" => $currency_id,
            "founds" => $founds
        ]);
        die();
    }

    public static function admin_menu() {
        add_menu_page(
            'Remesas',
            'Remesas',
            'manage_options',
            'remesa-config',
            'remesa_config_html',
            "",
            10
        );

        function remesa_config_html() {
            include_once WP_PLUGIN_DIR.'/exchanges-currencies/template/admin/remesa.php';
        }
    }
}