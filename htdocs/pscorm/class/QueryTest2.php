<?php

class PScorm_QueryTest2 extends PScorm_Query {
  
  public function getAllAggregation2Project() {
    $o = new PScorm_Aggregation2Project;

    $sql = "SELECT aggregations_projects.*,
                aggregations.id as aggregations_id,
                aggregations.closed as aggregations_closed,
                projects.id as projects_id,
                projects.name as projects_name,
                projects.contingent as projects_contingent,
                projects.listvisible as projects_listvisible ";
    $sql .= "FROM ".$o->getTable()." ";
    $sql .= SQL::LEFTJOIN('aggregations',array($o->getTable(),'id','aggregation_id'),'1:n');
    $sql .= SQL::LEFTJOIN('projects',array($o->getTable(),'id','project_id'),'1:n');
    
    $ret = array();
    $cache = PScorm_Cache::instance();
    foreach ($this->fetchResult($sql) as $row) {
      $a = new PScorm_Aggregation;
      $a->result = $row;
      $a->prefix = 'aggregations_';
      $a->init();
      $cache->populate($a);
      
      $p = new PScorm_Project;
      $p->result = $row;
      $p->prefix = 'projects_';      
      $p->init();
      $cache->populate($p);
      
      $o = new PScorm_Aggregation2Project;
      $o->result = $row;
      $o->init();
      $cache->populate($o);
      
      $ret[] = $o;
    }
    return $ret;
  }
}