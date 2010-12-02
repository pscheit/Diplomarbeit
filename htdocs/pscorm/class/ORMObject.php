<?php

abstract class PScorm_ORMObject extends Object {

  public $id;
  public $table;
  public $result;
  
  public $db;
  
  public function get($id) {
    $this->id = $id;
    $q = $this->db->query($this->getSQL());
    $res = $q->fetch(PDO::FETCH_ASSOC);
    
    if (is_array($res)) {
      $this->result = $res;
      
      $this->init();
    }
  }
  
  public function getSQL() {
    return "SELECT * FROM ".$this->table." WHERE id = ".$this->id;
  }
  
  abstract public function init();
  
  /**
   * @param string $class ohne PScorm_ davor
   */
  public static function load($class, $oid) {
    global $db;
    $class = 'PScorm_'.$class;
    $cache = PScorm_Cache::instance();
    
    if (($o = $cache->get($class, $oid)) !== NULL) {
      //echo "cache hit: ".$class.":".$oi.'<br />';
      return $o;
    } else {
      //echo "cache load: ".$class.":".$oid.'<br />';
      $o = new $class;
      $o->db = $db;
      $o->get($oid);
      
      $cache->populate($o);
      
      return $o;
    }
  }
  
  public function getTable() { return $this->table; }
  public function getId() { return $this->id; }
}

?>