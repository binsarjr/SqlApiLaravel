<?php

namespace App\Driver;

use App\Driver\SqlDriver\BaseSqlDriver;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SqlDriver extends BaseSqlDriver
{
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

    /**
     * Check query security.
     */
    public static function security(Request $request): bool
    {
        $rawLower = Str::lower(json_encode($request->all()));
        $cls = self::getInstance();
        $cls->SecureFromMaliciousSchema($rawLower);
        $cls->SecureFromMaliciousDataBinding($rawLower);

        return boolval(self::$securityMessage);
    }

    public function SecureFromMaliciousSchema(string $raw)
    {
        if (Str::contains($raw, self::getMaliciousSchema())) {
            self::$securityMessage = 'There is a dangerous scheme, your request is denied';
        }
    }

    public function SecureFromMaliciousDataBinding(string $raw)
    {
        // I have no idea (ngantuk, tidur dulu besok dilanjut)
        // // Dont worry about ` where ` or `where(`
        // $haveToBindings = ['where', 'values', 'set'];
        // $needles = [];
        // foreach ($haveToBindings as $item) {
        //     $needles[] = ' '.$item.' ';
        //     $needles[] = $item.'(';
        // }
        // if (Str::contains($raw, $needles)) {
        //     if (!$request->has('bindings')) {
        //         $this->forbidden('Mohon gunakan query binding');
        //     }
        // }

        // /*
        //  * Selain tipe write cek apakah ada query write yang termasuk kedalam query
        //  * apabila ada maka hentikan requestnya
        //  */
        // if ('write' != $type) {
        //     $writeOnly = ['insert', 'update', 'delete', 'drop', 'alter', 'create'];
        //     if (Str::contains($raw, $writeOnly)) {
        //         $this->forbidden("Query anda seharusnya ada pada tipe 'write'");
        //     }
        // }
    }
}