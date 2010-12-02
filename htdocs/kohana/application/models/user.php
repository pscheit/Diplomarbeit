<?php

class User_Model extends Auth_User_Model {
	protected $has_and_belongs_to_many = array('roles');
	protected $has_many = array('timeslices', 'aggregations');

  public $seconds = 0;

  /**
   * 
   * @var timeslice
   */
  public $openTrack;

  public function getDisplayName() {
    return $this->lastname.', '.$this->name;
  }

  public function hasOpenTrack() {
    if (!isset($this->id))
      return FALSE;

    $timeslices = ORM::factory('timeslice')
        ->where('user_id',$this->id)
        ->where('end IS NULL')
        ->orderby('start','DESC') // den letzten nehmen
        ->find_all();

    if (count($timeslices) > 0) {
      $this->openTrack = $timeslices->current();
      return TRUE;
    }

    return FALSE;
  }
}
?>