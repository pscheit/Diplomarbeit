<?php

class Aggregations_Project_Model extends ORM {

 // helper model to access data from pivot table
 // see: http://docs.kohanaphp.com/libraries/orm/advanced
 // has_many ''through'' relationships
 
	protected $belongs_to = array('aggregation', 'project');
  protected $has_one = array('aggregation', 'project');
  
  //protected $load_with = array('aggregation','project');

  public function getProject() {
	return $this->project;
  }

  public function getSeconds() {
   return $this->seconds;
  }

  public function getShare() {
   return $this->share;
  }

  public function getAggregation() {
   return $this->aggregation;
  }

}
?>