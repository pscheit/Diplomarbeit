<?php

class PScorm_QueryTest3 extends PScorm_Query {
  
  
  public function getSeveralAggregation2Project() {
    //$limit = ' LIMIT 91';
    $limit = NULL;
    $this->getAll('Project',$limit);
  }
  
  public function getAllProject() {
    return $this->getAll('Project');
  }
  
}