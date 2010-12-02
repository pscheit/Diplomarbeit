<?php
class PScorm_Aggregation2Project extends PScorm_ORMObject {
  public $share;
  public $seconds;
  
  public $project;
  public $aggregation;
  
  public $prefix;
  
  public function __construct() {
    $this->table = 'aggregations_projects';
  }
  
  public function init() {
    $this->id = (int) $this->result[$this->prefix.'id'];
    $this->share = $this->result[$this->prefix.'share'];
    $this->seconds = (int) $this->result[$this->prefix.'seconds'];
    
    $this->project = PScorm_ORMObject::load('Project',(int) $this->result[$this->prefix.'project_id']);
    $this->aggregation = PScorm_ORMObject::load('Aggregation',(int) $this->result[$this->prefix.'aggregation_id']);
  }
  
  public function getProject() { return $this->project; }
  public function getAggregation() { return $this->aggregation; }

  public function getSeconds() { return $this->seconds; }
  public function getShare() { return $this->share; }
}
?>