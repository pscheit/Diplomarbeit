<?php

$production = FALSE;
$base = 'D:/stuff/webseiten/test/Umsetzung/base/';

require 'doctrine-orm/Doctrine/Common/ClassLoader.php';
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine', $base.'inc/doctrine-orm');
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Entities', $base.'inc');
$classLoader->register();

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration;

if ($production) {
  $cache = new \Doctrine\Common\Cache\ArrayCache;
} else {
  $cache = new \Doctrine\Common\Cache\ApcCache;
}


$config = new Configuration;
$config->setMetadataCacheImpl($cache);
$driverImpl = $config->newDefaultAnnotationDriver($base.'inc/Entities');
$config->setMetadataDriverImpl($driverImpl);
$config->setQueryCacheImpl($cache);
//$config->setSQLLogger(new Doctrine\DBAL\Logging\DebugStack);

$config->setProxyDir($base.'inc/Proxies');
$config->setProxyNamespace('Proxies');

$config->setAutoGenerateProxyClasses($production);

$connectionOptions = array(
   'dbname' => 'pscorm',
   'user' => 'pscorm',
   'password' => 'nW.tFc6BPSDHHVFb',
   'host' => 'localhost',
   'driver' => 'pdo_mysql',
);

$em = EntityManager::create($connectionOptions, $config);

$em->getConnection()->query('SET SESSION query_cache_type = OFF');

$em->getRepository('Entities\Project')->findAll();
$em->getRepository('Entities\Aggregation')->findAll();
$objects = $em->getRepository('Entities\Aggregation2Project')->findAll();

//$query = $em->createQuery("SELECT a2p, p, a FROM Entities\Aggregation2Project a2p JOIN a2p.project p JOIN a2p.aggregation a ORDER BY a2p.id");
//$objects = $query->getResult();



//foreach ($config->getSQLLogger()->queries as $item) {
//  print $item['sql'].' params: '.implode(', ',$item['params'])."\n<br />";
//}

?>
<html><head><title>Doctrine Test1</title></head>

<body>
<?php include 'D:\stuff\Webseiten\psc-cms\Umsetzung\base\src\print_objects.php'; ?>
</body>

</html>