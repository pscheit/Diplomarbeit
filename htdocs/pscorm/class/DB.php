<?php

class PSCorm_DB extends PDO {
  
  public $queries = array();

  public function __construct($con = NULL) {
    $con = Code::forceDefString($con, 'default');

    $conf = Config::get('db',$con);
    
    $dsn = 'mysql:';
    if (isset($conf['host']))
      $dsn .= 'host='.$conf['host'].';';
    if (isset($conf['database']))
      $dsn .= 'dbname='.$conf['database'].';';
    if (isset($conf['charset']))
      $dsn .= 'charset='.$conf['charset'].';';
    
    parent::__construct($dsn, $conf['user'],$conf['password'],array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
    
    if (isset($conf['charset'])) {
      $this->query("SET CHARACTER SET '".$conf['charset']."'");
    }
  }
  
  public function query($sql) {
    $ms = 100;
    //usleep($ms*10000);
    $this->queries[] = $sql;

    $args = func_get_args();
    if (count($args) == 1) {
      return parent::query($args[0]);
    } elseif (count($args) == 2) {
      return parent::query($args[0],$args[1]);
    } elseif (count($args) == 3) {
      return parent::query($args[0],$args[1],$args[2]);
    }
  }
  
  
  public function __toString() {
    $str = NULL;
    foreach($this->queries as $sql) {
      $str .= '[DB] '.$sql."\n<br />";
    }
    return $str;
  }
}

?>