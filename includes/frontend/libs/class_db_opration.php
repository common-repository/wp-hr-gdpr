<?php

if (!class_exists('wphrgdpr_manage_db')) {

    class wphrgdpr_manage_db {

        public function __construct($tableName = false) {
            $this->tableName = $tableName;
        }

        public function insert(array $data) {
            global $wpdb;
            if (empty($data)) {
                return false;
            }
            $err=$wpdb->insert($this->tableName, $data);
            return $wpdb->insert_id;
        }

        public function wphrgdpr_get_all($orderBy = NULL) {
            global $wpdb;
            $sql = 'SELECT * FROM `' . $this->tableName . '`';
            if (!empty($orderBy)) {
                $sql .= ' ORDER BY ' . $orderBy;
            }
            $all = $wpdb->get_results($sql);
            return $all;
        }

        public function wphrgdpr_get_by(array $conditionValue, $condition = '=', $returnSingleRow = FALSE,$fieldCondition=FALSE,$gropby=FALSE,$order='',$order_by='DESC', $limit = 0) {
            global $wpdb;
            try {
                if($fieldCondition!=NULL)
                {
                    $fields_condition=$fieldCondition;
                }
                else
                {
                    $fields_condition="*";
                }
                $sql = 'SELECT '.$fields_condition.' FROM `' . $this->tableName . '` WHERE ';
                $conditionCounter = 1;
                foreach ($conditionValue as $field => $value) {
                    if ($conditionCounter > 1) {
                        $sql .= ' AND ';
                    }
                    switch (strtolower($condition)) {
                        case 'in':
                            if (!is_array($value)) {
                                throw new Exception("Values for IN query must be an array.", 1);
                            }
                            $sql .= sprintf('`%s` IN (%s)', $field, implode(',', $value));
                            break;
                        default:
                            $sql .= sprintf('`' . $field . '` ' . $condition . ' %s', $value);
                            break;
                    }
                    $conditionCounter++;
                }
                $sql.=$gropby;
                $sql.=' order by '.$order. ' ' .$order_by;
                if( $limit ){
                	$sql .= ' limit 0, '.$limit;
                }
                $result = $wpdb->get_results($sql);

                if (count($result) == 1 && $returnSingleRow) {
                    $result = $result[0];
                }
                return $result;
            } catch (Exception $ex) {
                return false;
            }
        }

        public function update(array $data, array $conditionValue) {
            global $wpdb;
            if (empty($data)) {
                return false;
            }
            $updated = $wpdb->update($this->tableName, $data, $conditionValue);
           
            return $updated;
        }

        public function wphrgdpr_delete(array $conditionValue) {
            global $wpdb;
            $deleted = $wpdb->delete($this->tableName, $conditionValue);
            return $deleted;
        }
    }

}
?>