<?php
if (DEF_IS_PRODUCTION)
{
    $serverName = $_ENV['DB_SERVER_LIVE'];
    $dbName = $_ENV['DB_NAME_LIVE'];
    $userName = $_ENV['DB_USERNAME_LIVE'];
    $password = $_ENV['DB_PASSWORD_LIVE'];
}
else
{
    //LOCAL
    $serverName = $_ENV['DB_SERVER_LOCAL'];
    $dbName = $_ENV['DB_NAME_LOCAL'];
    $userName = $_ENV['DB_USERNAME_LOCAL'];
    $password = $_ENV['DB_PASSWORD_LOCAL'];
}

try
{
    $db = new PDO("mysql:host=$serverName;dbname=$dbName", $userName, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>