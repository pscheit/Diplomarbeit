<?php

class App_Model extends Model {
  private $validlogin;
  public $view;

  function __construct() {
    $this->view=new View('basic_template');
  }

  function set_current_controller() {
    $this->view=new View('login');
  }
}
?>