<?php
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class wphrgdrplist_request extends wp_list_table {
    
    function __construct() {
        
        parent::__construct(array(
            'singular' => __('Access request', "wphrgdpr"),
            'plural' => __('Access request', "wphrgdpr"),
            'ajax' => false
        ));

        //$this->items = self::get_rec( 5, 1 );
    }

    public static function get_rec($per_page = 10, $page_number = 1) {
        global $wpdb, $gdrppro_dpo;
        
		$user = wp_get_current_user();
		$user_query = '';
		if ( in_array( wphr_hr_get_employee_role(), (array) $user->roles ) &&  in_array( $gdrppro_dpo->wphr_gdpr_get_dpo_role(), (array) $user->roles ) ) {
			$user_query = ' WHERE dpo_manager = '.$user->ID;
		}
        
        $sql = "SELECT * FROM `" . WPHRGDPR_SUBJECT_ACCESS_TBL .'` '.$user_query;
        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : 'DESC';
        }
        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
        $result = $wpdb->get_results($sql, 'ARRAY_A');
        return $result;
    }

    public static function delete_rec($id) {
        global $wpdb;
        $wpdb->delete(
                WPHRGDPR_SUBJECT_ACCESS_TBL, ['ID' => $id], ['%d']
        );
    }

    public static function record_count() {
        global $wpdb, $gdrppro_dpo;
        
		$user = wp_get_current_user();
		$user_query = '';
		if ( in_array( wphr_hr_get_employee_role(), (array) $user->roles ) &&  in_array( $gdrppro_dpo->wphr_gdpr_get_dpo_role(), (array) $user->roles ) ) {
			$user_query = ' WHERE dpo_manager = '.$user->ID;
		}
       	$sql = "SELECT COUNT(*) FROM `" . WPHRGDPR_SUBJECT_ACCESS_TBL .'` '.$user_query;
       
        return $wpdb->get_var($sql);
    }

    public function no_items() {
        _e('No Records.', "wphrgdpr");
    }

    function column_Name2($item) {
//        wphrgdpr_dd($item['ID']);
        $delete_nonce = wp_create_nonce('wphrgdpr_delete_rec');
        $id = $item['name'];
        $actions = [
            'delete' => sprintf('<a href="'.admin_url("admin.php?page=%s&action=%s&wphrgdpr_id=%s&_wpnonce=%s").'">'.__("Delete","wphrgdpr").'</a>', esc_attr($_REQUEST['page']), 'delete', absint($item['ID']), $delete_nonce)
        ];
        return $id . $this->row_actions($actions);
    }

    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'link':
				$link = sprintf( '<a href="admin.php?page=wphrgdpr_access_request&request_no=%s">%s</a>', $item['ID'], __( 'View and Manage in full Register', 'wp-hr-gdpr' ) );
                return $link;
				break;
			case 'created':
				return date('Y-m-d', strtotime( $item[$column_name] )); 
				break;
			case 'name':
				return ucwords( $item[$column_name] );
            default:
                return $item[$column_name];
				break;
        }
    }

    function column_cb($item) {
        return sprintf('<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']);
    }

    function get_columns() {
        $columns = array(
                'cb' => '<input type="checkbox" name="chkid" />',
                'ID' => __('Request Number', "wphrgdpr"),
				'created'	=>	__( 'Date of Receipt', 'wp-hr-gdpr'),
                'name' => __('From', "wphrgdpr"),
                'status' => __('Status', "wphrgdpr"),
				'link'	=>	__('Link to Full Record', 'wp-hr-gdpr' )
            );
        
        return $columns;
    }

    public function get_sortable_columns() {
        $sortable_columns = array(
            'created' => array('created', true),
            'name' => array('name', true),
            'status' => array('status', true),
        );
        return $sortable_columns;
    }

    public function get_bulk_actions() {
        $actions = [
            'bulk-delete' => 'Delete'
        ];
        return $actions;
    }

    public function prepare_items() {
        $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());
        /** Process bulk action */
        $this->process_bulk_action();

        $per_page = $this->get_items_per_page('rec_per_page', 10);
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();

        $this->set_pagination_args([
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page' => $per_page //WE have to determine how many items to show on a page
        ]);

        $this->items = self::get_rec($per_page, $current_page);
    }

    public function process_bulk_action() {
        if ('delete' === $this->current_action()) {
            $nonce = esc_attr($_REQUEST['_wpnonce']);
            if (!wp_verify_nonce($nonce, 'wphrgdpr_delete_rec')) {
                die('Go get a life script kiddies');
            } else {
                self::delete_rec(absint($_GET['wphrgdpr_id']));
            }
        }
        if (( isset($_POST['action']) && $_POST['action'] == 'bulk-delete' ) || ( isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete' )
        ) {
            $delete_ids = esc_sql($_POST['bulk-delete']);
            foreach ($delete_ids as $id) {
                self::delete_rec($id);
            }
        }
    }

}
