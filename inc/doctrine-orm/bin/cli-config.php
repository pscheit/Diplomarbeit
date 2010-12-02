<?php

$classLoader = new \Doctrine\Common\ClassLoader('Doctrine', $base.'inc/doctrine-orm');
$classLoader->register(); // register on SPL autoload stack


$classLoader = new \Doctrine\Common\ClassLoader('Entities', $base.'inc/Entities');
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Proxies', $base.'inc/Proxies');
$classLoader->register();

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration;

$cache = new \Doctrine\Common\Cache\ArrayCache;

$config = new Configuration;
$config->setMetadataCacheImpl($cache);
$driverImpl = $config->newDefaultAnnotationDriver($base.'inc/Entities');
$config->setMetadataDriverImpl($driverImpl);
$config->setQueryCacheImpl($cache);

$config->setProxyDir($base.'inc/Proxies');
$config->setProxyNamespace('Proxies');

$config->setAutoGenerateProxyClasses(true);

$connectionOptions = array(
   'dbname' => 'pscorm',
   'user' => 'pscorm',
   'password' => 'nW.tFc6BPSDHHVFb',
   'host' => 'localhost',
   'driver' => 'pdo_mysql',
);

$em = EntityManager::create($connectionOptions, $config);

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));

