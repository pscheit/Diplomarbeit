<?php

class PScorm_Cache extends Object {
  
  protected static $instance;
  
  protected $objects = array();
  
  public $log = array();
  
  protected function __construct() {
  }
  
  public static function instance() {
    if (!isset(self::$instance)) {
      self::$instance = new PScorm_Cache;
    }
    
    return self::$instance;
  }
  
  /**
   * Speichert ein Objekt im Cache
   * @param Object $o das Objekt
   * @chainable
   */
  public function populate(Object $o) {
    $class = $o->getClass();
    $oid = $o->getId();
    
    $this->objects[$class][$oid] =& $o;
    $this->log[] = array('populate',$class,$oid);

    return $this;
  }
  
  /**
   * Gibt ein Objekt aus dem Cache zurück
   *
   * @param string $class der volle Name der Klasse
   * @param int $oid der Object Identifier des Objektes
   * @return Object
   */
  public function get($class, $oid) {
    if (isset($this->objects[$class][$oid])) {
      $this->log[] = array('hit',$class,$oid);
      return $this->objects[$class][$oid];
    }
    
    $this->log[] = array('miss',$class,$oid);
  }
  
  public function __toString() {
    $str = NULL;
    foreach($this->log as $entry) {
      list ($type, $class, $oid) = $entry;
      $str .= '['.$type.'] '.$class.':'.$oid."<br />\n";
    }
    return $str;
  }
}

?>