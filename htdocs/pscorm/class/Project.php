<?php
class PScorm_Project extends PScorm_ORMObject {
  
  public $name;
  public $contingent;
  public $listvisible;
  
  public $prefix;
  
  public function __construct() {
    $this->table = 'projects';
  }
  
  public function init() {
    $this->id = (int) $this->result[$this->prefix.'id'];
    $this->name = (string) $this->result[$this->prefix.'name'];
    $this->contingent = (int) $this->result[$this->prefix.'contingent'];
    $this->listvisible = ($this->result[$this->prefix.'listvisible'] == 1);
  }
  
  public function getName() { return $this->name; }
  public function getContingent() { return $this->contingent; }
  public function getListvisible() { return $this->listvisible; }
}
?>