<?php

if ( !class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
class wphrgdrplist_rec_list extends wp_list_table
{
    private  $user_consent_status ;
    function __construct()
    {
        parent::__construct( array(
            'singular' => __( 'Consent', "wphrgdpr" ),
            'plural'   => __( 'Consent', "wphrgdpr" ),
            'ajax'     => false,
        ) );
        $this->user_consent_status = array(
            '' => __( 'Not Submitted', 'wp-hr-gdpr' ),
            1  => __( 'Agreed', 'wp-hr-gdpr' ),
            2  => __( 'Partial', 'wp-hr-gdpr' ),
            3  => __( 'Revoked', 'wp-hr-gdpr' ),
        );
        //$this->items = self::get_rec( 5, 1 );
    }
    
    public static function get_rec( $per_page = 10, $page_number = 1 )
    {
        global  $wpdb ;
        $consent_table = WPHRGDPR_CONSENT_TBL;
        $sql = "SELECT * FROM `{$consent_table}` ";
        
        if ( !empty($_REQUEST['orderby']) ) {
            $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
            $sql .= ( !empty($_REQUEST['order']) ? ' ' . esc_sql( $_REQUEST['order'] ) : 'DESC' );
        }
        
        $sql .= " LIMIT {$per_page}";
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;
        $result = $wpdb->get_results( $sql, 'ARRAY_A' );
        return $result;
    }
    
    function column_Name( $item )
    {
        $delete_nonce = wp_create_nonce( 'wphrgdpr_delete_rec' );
        $id = $item['name'];
        $actions = [
            'delete' => sprintf(
            '<a href="' . admin_url( "admin.php?page=%s&action=%s&wphrgdpr_id=%s&_wpnonce=%s" ) . '">' . __( "Delete", "wp-hr-gdpr" ) . '</a>',
            esc_attr( $_REQUEST['page'] ),
            'delete',
            absint( $item['ID'] ),
            $delete_nonce
        ),
        ];
        return $id . $this->row_actions( $actions );
    }
    
    public function column_default( $employee, $column_name )
    {
        switch ( $column_name ) {
            case 'name':
                return sprintf( '<a href="%s"><strong>%s</strong></a>', wphr_hr_url_single_employee( $employee['user_id'] ), $employee['name'] );
            case 'created':
                return ( $employee[$column_name] ? $employee[$column_name] : '-' );
            case 'employee_status':
                return ucwords( $employee[$column_name] );
            case 'status':
                return $this->user_consent_status[$employee[$column_name]];
            default:
                return $employee[$column_name];
        }
    }
    
    function column_status( $employee )
    {
        $status = $this->user_consent_status[$employee['status']];
        return sprintf( '<span class="consent_status_%s">%s</span>', str_replace( ' ', '_', strtolower( $status ) ), $status );
    }
    
    function column_view( $employee )
    {
        $data = unserialize( $employee['data'] );
        $data = json_encode( $data );
        
        if ( $data != 'false' ) {
            return sprintf( '<a href="#" class="view_consent_form_link" data-data=\'%s\'>%s</a>', $data, __( 'View form', 'wp-hr-gdpr' ) );
        } else {
            return '-';
        }
    
    }
    
    function column_cb( $item )
    {
        return sprintf( '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID'] );
    }
    
    function get_columns()
    {
        $columns = array(
            'name'            => __( 'From', "wphrgdpr" ),
            'created'         => __( 'Last updated', "wphrgdpr" ),
            'employee_status' => __( 'Employement Status', "wphrgdpr" ),
            'status'          => __( 'Agreed', "wphrgdpr" ),
            'view'            => __( 'View Consent Form', "wphrgdpr" ),
        );
        $columns = array(
            'cb'      => '<input type="checkbox" name="chkid" />',
            'name'    => __( 'Name', "wp-hr-gdpr" ),
            'email'   => __( 'Email', "wp-hr-gdpr" ),
            'created' => __( 'Time', "wp-hr-gdpr" ),
        );
        return $columns;
    }
    
    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'created'         => array( 'created', true ),
            'name'            => array( 'name', true ),
            'employee_status' => array( 'employee_status', true ),
            'status'          => array( 'status', true ),
        );
        return $sortable_columns;
    }
    
    public function get_bulk_actions()
    {
        $actions = [
            'bulk-delete' => 'Delete',
        ];
        return $actions;
    }
    
    public static function delete_rec( $id )
    {
        global  $wpdb ;
        $wpdb->delete( WPHRGDPR_CONSENT_TBL, [
            'ID' => $id,
        ], [ '%d' ] );
    }
    
    public function prepare_items()
    {
        $this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
        $per_page = 20;
        $current_page = $this->get_pagenum();
        $offset = ($current_page - 1) * $per_page;
        $args = array(
            'offset' => $offset,
            'number' => $per_page,
        );
        $this->process_bulk_action();
        $this->items = self::get_rec( $per_page, $current_page );
        $args['count'] = true;
        $total_items = self::record_count();
        $this->set_pagination_args( [
            'total_items' => $total_items,
            'per_page'    => $per_page,
        ] );
    }
    
    public static function record_count()
    {
        global  $wpdb ;
        $sql = "SELECT COUNT(*) FROM `" . WPHRGDPR_CONSENT_TBL . '`';
        return $wpdb->get_var( $sql );
    }
    
    public function process_bulk_action()
    {
        
        if ( 'delete' === $this->current_action() ) {
            $nonce = esc_attr( $_REQUEST['_wpnonce'] );
            
            if ( !wp_verify_nonce( $nonce, 'wphrgdpr_delete_rec' ) ) {
                die( 'Go get a life script kiddies' );
            } else {
                self::delete_rec( absint( $_GET['wphrgdpr_id'] ) );
            }
        
        }
        
        
        if ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' || isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' ) {
            $delete_ids = esc_sql( $_POST['bulk-delete'] );
            foreach ( $delete_ids as $id ) {
                self::delete_rec( $id );
            }
        }
    
    }

}