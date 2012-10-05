<?php
function autoload ($pClassName) {
    include(sprintf('models/%s.php', $pClassName));
}
spl_autoload_register('autoload');

class Model {
    public $fields = array();
    public $fields_type = array();
    public $fields_val = array();
    public $primary = array();
    public $table_name = '';
    public $mysql_connector = null;

    public function __construct($fields=array(), $table_name="") {
        foreach ($fields as $f) {
            $this->fields[] = $f[0];
            $this->fields_type[$f[0]] = '%'.$f[1];
            $this->fields_val[$f[0]] = null;
            if (!empty($f[2]) && $f[2] == true) {
                $this->primary[] = $f[0];
            }
        }
        $this->table_name = $table_name;
        $this->mysql_connector = MySQLConnector::get_instance();
    }

    public function find_by($field, $value, $type='s') {
        $record = $this->mysql_connector->fetchOne(
            'SELECT `'.implode('`, `', $this->fields).'` 
            FROM `'.$this->mysql_connector->idcontent_db.'`.`'.$this->table_name.'` 
            WHERE `'.$field.'`=%'.$type, $value);
        if (!empty($record)) {
            foreach ($this->fields as $f) {
                $this->fields_val[$f] = $record[$f];
            }
            return $this;
        } else {
            return null;
        }
    }

    public function find($id) {
        return $this->find_by('id', $id, "i");
    }

    public function save() {
        $is_record = true;
        foreach ($this->primary as $pf) {
            if (empty($this->fields_val[$pf])) {
                $is_record = false;
            }
        }
        if ($is_record) {
            $this->update();
        } else {
            $this->insert();
        }
    }

    public function insert() {
        $r = $this->mysql_connector->insert('INSERT INTO `'.$this->mysql_connector->idcontent_db.'`.`'.$this->table_name.'`(`'.implode('`, `', $this->fields).'`)
            VALUES('.implode(', ',$this->fields_type).')', $this->fields_val);
        if (in_array('id', $this->fields)) {
            $this->fields_val['id'] = $r['insert_id'];
        }
        return $r;
    }
    
    public function update() {
        $set = array();
        $vals = array();
        $where = array();
        $where_vals = array();
        foreach ($this->fields as $f_name) {
            if (!in_array($f_name, $this->primary)) {
                $set[] = '`'.$f_name.'` = '.$this->fields_type[$f_name];
                $vals[] = $this->fields_val[$f_name];
            } else {
                $where[] = '`'.$f_name.'` = '.$this->fields_type[$f_name];
                $where_vals[] = $this->fields_val[$f_name];
            }
        }
        $final_vals = array_merge($vals, $where_vals);
        $r = $this->mysql_connector->update('UPDATE `'.$this->mysql_connector->idcontent_db.'`.`'.$this->table_name.'
            SET '.implode('`, `', $set).'`)
            VALUES('.implode(', ',$where).')', $final_vals);
        return $r;
    }
    
    public function __get($name) {
        if (in_array($name, $this->fields)) {
            return $this->fields_val[$name];
        }
        return null;
    }

    public function __set($name, $val) {
        if (in_array($name, $this->fields)) {
            $this->fields_val[$name] = $val;
        }
    }

    public function __isset($name) {
        return isset($this->fields[$name]);
    }

    public function __unset($name) {
        unset($this->fields[$name]);
    }
}
?>
