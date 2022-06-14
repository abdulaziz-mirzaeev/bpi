<?php
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$host = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);


return [
    'class' => 'yii\db\Connection',
    'dsn' => sprintf('mysql:host=%s;dbname=%s', $host, $db),
    'username' => $username,
    'password' => $password,
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
