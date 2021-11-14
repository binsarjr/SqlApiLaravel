<?php

namespace App\Http\Middleware;

use App\Driver\SqlDriver;
use Closure;
use Illuminate\Http\Request;

class SqlRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        SqlDriver::$type = $request->type;
        SqlDriver::$bindings = $request->bindings ?? [];
        SqlDriver::$query = $request->sql;
        SqlDriver::$connection = $request->connection;

        if (SqlDriver::security($request)) {
            $this->forbidden(SqlDriver::$securityMessage);
        }

        return $next($request);
    }

    public function forbidden(string $message)
    {
        return abort(403, $message);
    }
}