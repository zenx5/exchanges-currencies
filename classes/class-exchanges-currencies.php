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
        add_action( 'wp_ajax_update_currency', [__CLASS__, 'action_update_currency']);
        add_action( 'wp_ajax_add_currency', [__CLASS__, 'action_add_currency']);
        add_action( 'wp_ajax_update_rate', [__CLASS__, 'action_update_rate']);
        add_action( 'wp_ajax_add_change', [__CLASS__, 'action_add_change']);
        add_shortcode( 'exchange-app', [__CLASS__, 'client_app']);
        add_action( 'wp_ajax_create_operation', [__CLASS__, 'action_create_operation']);
        add_action( 'wp_ajax_update_operation', [__CLASS__, 'action_update_operation']);
    }

    public static function action_update_operation() {
        if( isset($_POST['id']) ) {
            $request = [];
            if( isset($_POST["reference"]) ) {
                $request["reference"] = $_POST["reference"];
            }
            if( isset($_POST["approved_by"]) ) {
                $request["approved_by"] = $_POST["approved_by"];
            }
            $Operation = new ExchangesDB('ex_operation');
            $Operation->set($_POST['id'], $request );
            echo json_encode( $request );
        } else {
            echo json_encode([]);
        }
        die();
    }

    public static function action_create_operation() {
        $request = [
            "exchange_id" => $_POST["exchange_id"],
            "mount" => $_POST["mount"],
            "reference" => "",
            "approved_by" => 0,
            "created_at" => date("Y-m-d")
        ];
        $Operation = new ExchangesDB('ex_operation');
        $request["id"] = $Operation->set(0, $request );
        echo json_encode( $request );
        die();
    }

    public static function client_app() {
        $Change = new ExchangesDB('ex_exchanges');
        $Currency = new ExchangesDB('ex_currencies');
        $exchanges = $Change->get();
        $currencies = $Currency->get();
        ?>
            <script src="https://cdn.tailwindcss.com"></script>
            <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
            <script>
                window.exchanges = {
                    currencies: <?=json_encode($currencies)?>,
                    changes: <?=json_encode($exchanges)?>
                }
            </script>
            <script src="<?='/wp-content/plugins/exchanges-currencies/template/front/app.js'?>"></script>
        <?php
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
            "details" => $_POST['details'],
            "created_at" => date("Y-m-d")
        ];
        $Currency = new ExchangesDB('ex_currencies');
        $Currency->set(0, $request );
        echo json_encode($request);
        die();
    }

    public static function action_update_currency() {
        $currency_id = $_POST['currency_id'];
        $founds = $_POST['founds'];
        $details = $_POST['details'];
        $Currency = new ExchangesDB('ex_currencies');
        $Currency->set($currency_id, ["founds" => $founds, "details" => $details ]);
        echo json_encode([
            "currency_id" => $currency_id,
            "founds" => $founds,
            "details" => $details
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