<?php

class PScorm_Aggregation extends PScorm_ORMObject {
  public $closed;
  
  public $prefix;
  
  public function __construct() {
    $this->table = 'aggregationssmall';
  }
  
  public function init() {
    $this->closed = ($this->result[$this->prefix.'closed'] == '1');
    $this->id = (int) $this->result[$this->prefix.'id'];
  }
  
  public function getClosed() { return $this->closed; }
}


?>