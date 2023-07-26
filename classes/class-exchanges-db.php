<?php

defined( 'ABSPATH' ) || exit;

require_once(ABSPATH.'wp-admin/includes/upgrade.php');

class ExChangesDB {

    protected $name = '';

    public function __construct( $name ) {
        $this->name = $name;
    }

    public static function create_all() {
        self::def_table__currencies();
        self::def_table__exchanges();
        self::def_table__rules();
        self::def_table__operation();
        
    }

    public static function drop_all() {
        self::drop_table("ex_currencies");
        self::drop_table("ex_exchanges");
        self::drop_table("ex_rules");
        self::drop_table("ex_operation");
    }

    public static function drop_table($name){
        global $wpdb;
        $prefix = $wpdb->prefix;
        $sql = "drop table {$prefix}{$name}";
        dbDelta($sql);
    }

    public static function def_table__currencies() {
        global $wpdb;
        $collate = $wpdb->collate;
        $prefix = $wpdb->prefix;
        $name_table = $prefix.'ex_currencies';
        $sql = "CREATE TABLE IF NOT EXISTS {$name_table} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            name varchar(255),
            code varchar(30),
            symbol varchar(255),
            founds double precision,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id) )
            COLLATE {$collate}";
        dbDelta($sql);
    }

    public static function def_table__exchanges() {
        global $wpdb;
        $collate = $wpdb->collate;
        $prefix = $wpdb->prefix;
        $name_table = $prefix.'ex_exchanges';
        $sql = "CREATE TABLE IF NOT EXISTS {$name_table} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            currency_from bigint(20),
            currency_to bigint(20),
            rate float(8),
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id) )
            COLLATE {$collate}";
        dbDelta($sql);
    }

    public static function def_table__rules() {
        global $wpdb;
        $collate = $wpdb->collate;
        $prefix = $wpdb->prefix;
        $name_table = $prefix.'ex_rules';
        $sql = "CREATE TABLE IF NOT EXISTS {$name_table} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            relation varchar(5),
            deposit double precision,
            value_format varchar(5),
            value float(8),
            exchange_id bigint(20),
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id) )
            COLLATE {$collate}";
        dbDelta($sql);
    }

    public static function def_table__operation() {
        global $wpdb;
        $collate = $wpdb->collate;
        $prefix = $wpdb->prefix;
        $name_table = $prefix.'ex_operation';
        $sql = "CREATE TABLE IF NOT EXISTS {$name_table} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            exchange_id bigint(20),
            reference varchar(30),
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id) )
            COLLATE {$collate}";
        dbDelta($sql);
    }

    public function get_row( $table, $where = 1 ) {
        global $wpdb;
        return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}{$table} WHERE $where ORDER BY created_at DESC", OBJECT );
    }

    public function insert( $table, $data) {
        global $wpdb;
        $wpdb->insert(
            "{$wpdb->prefix}{$table}",
            $data
        );
    }

    public function update( $table, $data, $where ) {
        global $wpdb;
        $wpdb->update(
            "{$wpdb->prefix}{$table}",
            $data,
            $where 
        );
    }

    public function get( $id = 0) {
        return $this->get_row($this->name, $id ? "id=$id" : "1" );
    }

    public function set( $id = 0, $data) {
        if( $id!=0 ) {
            $this->update( $this->name, $data, [ "id" => $id ] );
        } else {
            $this->insert( $this->name, $data );
        }
    }


}

