<?php

class Test1_Controller extends Sqldebug_Controller {

  public function index() {

    ini_set('max_execution_time',0);
    /* disable query cache von mysql für tests */
    Database::instance()->query('SET SESSION query_cache_type = OFF');


    $objects = ORM::factory('Aggregations_Project')
      //->with('project')
      //->with('aggregation')
      //->limit(8000)
      ->find_all();
      
   //$objects = ORM::factory('Aggregation')
   //->find_all();

?>
<html><head><title>Kohana Test1</title></head>

<body>
<?php include 'D:\stuff\Webseiten\psc-cms\Umsetzung\base\src\print_objects.php'; ?>
</body>

</html>
<?php

  }
}

?>