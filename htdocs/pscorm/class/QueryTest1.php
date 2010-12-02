<?php

class PScorm_QueryTest1 extends PScorm_Query {
  
  
  public function getAllAggregation2Project() {
    $this->getAll('Project');
    $this->getAll('Aggregation');
    
    return $this->getAll('Aggregation2Project',' ORDER BY aggregations_projects.id');
  }
  
  public function getAllProject() {
    return $this->getAll('Project');
  }
  
}