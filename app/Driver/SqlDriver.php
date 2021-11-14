<?php

namespace App\Driver;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class SqlDriver
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
     * Execute query.
     *
     * @return bool|array
     */
    public static function exec()
    {
        $db = DB::connection(self::$connection);
        $result = null;
        if ('write' == self::$type) {
            try {
                $db->statement(self::$query, self::$bindings);
                $result = true;
            } catch (\Throwable $th) {
                if (env('APP_DEBUG')) {
                    return new QueryException(SqlDriver::$query, SqlDriver::$bindings, $th);
                }
                $result = false;
            }
        } else {
            $result = $db->select(self::$query, self::$bindings);
        }
        self::reset();

        return response()->json($result);
    }

    public static function reset()
    {
        self::$connection = null;
        self::$type = 'read';
        self::$rows = [];
        self::$bindings = [];
    }
}