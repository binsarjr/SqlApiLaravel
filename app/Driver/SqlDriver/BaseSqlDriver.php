<?php

namespace App\Driver\SqlDriver;

abstract class BaseSqlDriver extends MaliciousProperties
{
    /**
     * @var enum read|write
     */
    public static $type;
    /**
     * connection name that has been set.
     */
    public static string|null $connection = null;
    /**
     * Do your magic query.
     */
    public static string $query;
    /**
     * data binding from query.
     */
    public static array|object $bindings;
    /**
     * Rows records.
     */
    public static array $rows = [];

    /**
     * Reset static property.
     */
    public static function reset()
    {
        self::$connection = null;
        self::$type = 'read';
        self::$rows = [];
        self::$bindings = [];
    }

    /**
     * Instance properties.
     */
    private static $instance;

    /**
     * Singleton.
     */
    public static function getInstance(): self
    {
        if (null == self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public static function addMaliciousSchema(...$schema)
    {
        $schema = json_decode(strtolower(json_encode($schema)));
        self::getInstance()->_addMaliciousSchema(...$schema);
    }

    public static function getMaliciousSchema(): array
    {
        return self::getInstance()->_malicious_schema;
    }
}