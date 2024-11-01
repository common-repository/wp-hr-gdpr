<?php
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class wphrgdrplist_training extends wp_list_table {
    
	private $row_number;
	
    function __construct() {
        
        parent::__construct(array(
            'singular' => __('Data Protection Training', "wphrgdpr"),
            'plural' => __('Data Protection Training', "wphrgdpr"),
            'ajax' => false
        ));
		$this->row_number = 0;
        //$this->items = self::get_rec( 5, 1 );
    }

    public static function get_rec($per_page = 10, $page_number = 1) {
        global $wpdb;
        
        $sql = "SELECT * FROM `" . WPHRGDPR_TRAINING_TBL .'`';
		$offset = ( $page_number - 1 ) * $per_page;
        if (!empty($_REQUEST['orderby'])) {
			if( $_REQUEST['orderby'] == 'user_id' ){
				$user_query = wphr_hr_get_employees( array( 'orderby' => 'employee_name', 'number' => -1, 'order' => $_REQUEST['order'] ) );
				foreach( $user_query as $user ){
					$user_ids[] = $user->id;
				}
				$sql .= ' ORDER BY FIELD ( `user_id`, ' . implode( ',', $user_ids ) .' ) '; 	
			}else{
	            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
	            $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : 'DESC';	
			}
        }else{
            $sql .= ' ORDER BY ID DESC';
        }
        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . $offset;
        $result = $wpdb->get_results($sql, 'ARRAY_A');
        return $result;
    }

    public static function delete_rec($id) {
        global $wpdb;
        $wpdb->delete(
                WPHRGDPR_TRAINING_TBL, ['ID' => $id], ['%d']
        );
    }
	
    public function column_number($item) {
        $delete_nonce = wp_create_nonce('wphrgdpr_delete_rec');
		$current_page = $this->get_pagenum();
		$this->row_number++; 
		$id = 	$this->row_number * $current_page;
		$data = array(
			'id'			=>	$item['ID'],
			'user_id'		=>	$item['user_id'],
			'course_name'	=>	$item['course_name'],
			'training_date'	=>	date( 'm/d/Y', strtotime( $item['date'] ) ),
			'note'			=>	$item['note']
		);
        $actions = [
			'edit'	=>	sprintf('<a href="'.admin_url("admin.php?page=%s&action=%s&id=%s").'" data-data=\'%s\'>'.__("Edit","wphrgdpr").'</a>', esc_attr($_REQUEST['page']), 'edit', absint($item['ID'] ), json_encode( $data ) ),
            'delete' => sprintf('<a href="'.admin_url("admin.php?page=%s&action=%s&id=%s&_wpnonce=%s").'">'.__("Delete","wphrgdpr").'</a>', esc_attr($_REQUEST['page']), 'delete', absint($item['ID']), $delete_nonce)
        ];
        return $id . $this->row_actions($actions);
    }

    public static function record_count() {
        global $wpdb;
        
       $sql = "SELECT COUNT(*) FROM `" . WPHRGDPR_TRAINING_TBL .'`';
       
        return $wpdb->get_var($sql);
    }

    public function no_items() {
        _e('No Records.', "wphrgdpr");
    }


    function column_cb($item) {
        return sprintf('<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']);
    }

    function get_columns() {
        $columns = array(
                'cb' => '<input type="checkbox" name="chkid" />',
				'number'	=>	'#',
				'date'	=>	__( 'Course date', 'wp-hr-gdpr'),
                'course_name' => __('Course name', "wphrgdpr"),
                'user_id' => __('Employee', "wphrgdpr"),
                'note' => __('Notes', "wphrgdpr"),
            );
        
        return $columns;
    }
	
    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'user_id':
				$name = '';
				if( $item['user_id'] ){
					$user = get_user_by( 'id', $item['user_id'] );
					$name = $user->first_name . ' ' . $user->last_name;	
				}
                return $name;
				break;
			case 'date':
				return date('Y-m-d', strtotime( $item[$column_name] )); 
				break;
			case 'number':
				$current_page = $this->get_pagenum();
				$this->row_number++; 
				return 	$this->row_number * $current_page;
				break;
            default:
				$data = '';
				if( isset( $item[$column_name] ) ){
					$data = $item[$column_name];	
				}
                return $data; 
				break;
        }
    }

    public function get_sortable_columns() {
        $sortable_columns = array(
            'date' => array('date', true),
            'course_name' => array('course_name', true),
            'user_id' => array('user_id', true),
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
                self::delete_rec(absint($_GET['id']));
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
