<?php

class PScorm_Query extends Object {
  
  public $db;
  
  public function __construct(PScorm_DB $db) {
    $this->db = $db;
  }
  
  public function fetchResult($sql) {
    $stmt = $this->db->query($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
    return $stmt->fetchAll();
  }

  /**
   * @param string $class ohne PScorm_
   */
  public function getAll($class, $orderbySQL = NULL) {
    $class = 'PScorm_'.$class;
    $o = new $class;
    
    $sql = "SELECT * FROM ".$o->getTable().$orderbySQL;
    
    $ret = array();
    $cache = PScorm_Cache::instance();
    foreach ($this->fetchResult($sql) as $row) {
      $o = new $class();
      $o->result = $row;
      $o->init();
      
      $cache->populate($o);
      $ret[] = $o;
    }
    return $ret;
  }
  /**
   * Filtert alle Schlüssel aus dem Array die nicht mit $prefix anfangen herraus */
  public function filterResult(Array $row, $prefix) {
    $r = array();
    $index = mb_strlen($prefix);
    foreach ($row as $key => $value) {
      if (S::startsWith($key,$prefix)) {
        $r[mb_substr($key,$index)] = $value;
      }
    }
    return $r;
  }
}


?>