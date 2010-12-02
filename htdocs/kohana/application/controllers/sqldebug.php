<?php

abstract class Sqldebug_Controller extends Controller {
  
  public function __construct() {
    
    ini_set('max_execution_time',0);
    /* disable query cache von mysql für tests */
    Database::instance()->query('SET SESSION query_cache_type = OFF');
    
    parent::__construct();
  }


  public function __destruct() {
    $queryLog = NULL;
    
    $sqls = array();
    $puresqls = array();

    foreach (Database::$benchmarks as $entry) {
      $sql = $this->parse_sql($entry['query']);
      //$sql = $entry['query'];
      $key = md5($sql);

      if (!isset($sqls[$key])) $sqls[$key] = array(
        'sql'=>$sql,
        'count'=>0,
        'time'=>0
      );
        
      $sqls[$key]['count']++;
      $sqls[$key]['time'] = max($sqls[$key]['time'],$entry['time']);
      $puresqls[] = $entry['query'];
    }
    
    foreach ($sqls as $key => $info) {
      $queryLog .= str_replace('???','<b style="color: red">???</b>',$info['sql'])." <i>took (worstcase): ".round($info['time'],4)."s executed: ".$info['count']." times</i><br /><br />\n\n";
    }

    print "<i>found ".count(Database::$benchmarks)." Queries. ".count(array_unique($puresqls))." were unique. ".count($sqls)." were grouped.</i><br /><br />";
    //print '<i>Queries: '.print_r($sqlCounts,TRUE).'</i><br />';
    print $queryLog;
  }

  protected function parse_sql($sql) {
    /* wir versuchen hier querys zu gruppieren und ihre Range darzustellen. 
    */
    //$sql = 'SELECT `users`.* FROM `users` WHERE `users`.`id` = 2 ORDER BY `users`.`id` ASC LIMIT 0, 1'; // einfacher fall
    //$sql = 'SELECT `aggregation`.`id` AS `aggregation:id`, `aggregation`.`closed` AS `aggregation:closed`, `aggregation:timeslice`.`id` AS `aggregation:timeslice:id`, `aggregation:timeslice`.`user_id` AS `aggregation:timeslice:user_id`, `aggregation:timeslice`.`aggregation_id` AS `aggregation:timeslice:aggregation_id`, `aggregation:timeslice`.`start` AS `aggregation:timeslice:start`, `aggregation:timeslice`.`end` AS `aggregation:timeslice:end`, `aggregations_projects`.* FROM `aggregations_projects` LEFT JOIN `aggregations` AS `aggregation` ON (`aggregation`.`id` = `aggregations_projects`.`aggregation_id`) LEFT JOIN `timeslices` AS `aggregation:timeslice` ON (`aggregation:timeslice`.`aggregation_id` = `aggregation`.`id`) WHERE `project_id` = 1 AND `user_id` = 2 GROUP BY `aggregation:id` ORDER BY `aggregations_projects`.`id` ASC'; // nicht so einfacher fall
    //$sql = "SELECT `timeslice`.`id` AS `timeslice:id`, `timeslice`.`user_id` AS `timeslice:user_id`, `timeslice`.`aggregation_id` AS `timeslice:aggregation_id`, `timeslice`.`start` AS `timeslice:start`, `timeslice`.`end` AS `timeslice:end`, `aggregations_project`.`id` AS `aggregations_project:id`, `aggregations_project`.`aggregation_id` AS `aggregations_project:aggregation_id`, `aggregations_project`.`project_id` AS `aggregations_project:project_id`, `aggregations_project`.`share` AS `aggregations_project:share`, `aggregations_project`.`seconds` AS `aggregations_project:seconds`, `aggregations`.* FROM `aggregations` LEFT JOIN `timeslices` AS `timeslice` ON (`timeslice`.`aggregation_id` = `aggregations`.`id`) LEFT JOIN `aggregations_projects` AS `aggregations_project` ON (`aggregations_project`.`aggregation_id` = `aggregations`.`id`) WHERE `aggregations`.`closed` = '1' AND `timeslice`.`user_id` = 2 GROUP BY `aggregations_project`.`id` HAVING MIN(start) >= '2010-06-07' AND MAX(start) <= '2010-06-13 23:59:59' ORDER BY `aggregations`.`id` ASC";
    
    /* zuerst nehmen wir das query, finden die where klausel und anonymisieren diese 
       (wir ersetzen die Parameter der Where Klausel mit regulären ausdrücken */
    $identifier = '`?[a-zA-Z_0-9]+`?';
    $column = $identifier.'(?:\.'.$identifier.')?'; // spalte oder tabelle.spalte
    $eqop = '(?:=|>=|<=|!=)'; // equal operator
    $stringInQuotes = "'(?:'.|[^'])*?'";
    $atomValue = '(?:[0-9]+|'.$stringInQuotes.')';
    $w = "(?:\s*\n*)*";
    
    /* where klausel nehmen */
    $m = array();
    if (preg_match('/WHERE'.$w.'(.*?)'.$w.'(ORDER BY|GROUP BY|^)/su',$sql,$m) == 1) { 
      $whereSQL = $m[1];
    
      
      $whereSQLAnonym = $whereSQL;
      /* jetzt ersetzen wir in der ganzen where klausel jeweils 'spalte = atomwert' teile */
      $whereSQLAnonym = preg_replace('/('.$w.$column.$w.$eqop.$w.')('.$atomValue.')/u','\1???',$whereSQLAnonym);

      /* where IN () */
      $whereSQLAnonym = preg_replace('/('.$w.$column.$w.'IN'.$w.')\(.*?\)/u','\1(???)',$whereSQLAnonym);

      /* haben wir nun ein SQL anonymisiert können wir es zählen und die werte der Where Parameter parsen */  
      $sqlAnonym = str_replace($whereSQL,$whereSQLAnonym,$sql);

      return $sqlAnonym;
    } else {
      return $sql;
    }
  }
}

?>