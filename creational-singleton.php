<?php

/**
 * Class DatabaseConfig
 * 2nees.com
 */
class DatabaseConfig {
    private static string $host;
    private static int $port;
    private static string $dbUser;
    private static string $dbPassword;
    // singleton instance
    private static ?self $database = null;
    // prevent clone instance
    private function __clone(){}
    // prevent share construct
    private function __construct()
    {
        //TODO fetch it from ini file for example...this written like this for tutorial
        self::$host         = "localhost";
        self::$port         = 80;
        self::$dbUser       = "anees";
        self::$dbPassword   = "password";
    }

    // The static method that controls access to the singleton instance
    public static function DB(): self
    {
        if(is_null(self::$database)){
            self::$database = new self();
        }

        return self::$database;
    }

    // Sample static method
    public static function getDBConfig(): array
    {
        return [
            self::$host,
            self::$port,
            self::$dbPassword,
            self::$dbUser
        ];
    }

    // Sample normal method
    public function getNeededConfig($key)
    {
        return self::$$key ?? null;
    }
}

$db = DatabaseConfig::DB();
print_r($db::getDBConfig());
echo PHP_EOL;
print_r($db->getNeededConfig("dbUser"));
echo PHP_EOL;
print_r(DatabaseConfig::DB()->getNeededConfig("dbUser"));