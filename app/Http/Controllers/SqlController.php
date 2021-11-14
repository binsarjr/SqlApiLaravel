<?php

namespace App\Http\Controllers;

use App\Http\Requests\SqlPostRequest;
use Illuminate\Support\Facades\DB;

class SqlController extends Controller
{
    public function index(SqlPostRequest $request)
    {
        $bindings = $request->bindings ?? [];
        $query = $request->sql;
        $select = DB::connection($request->connection);
        switch ($request->type) {
            case 'write':
                try {
                    $select->statement($query, $bindings);

                    return response()->json(true);
                } catch (\Throwable $th) {
                    return response()->json(false);
                }
                break;
            default:
                $query = $select->select($query, $bindings);

                return response()->json($query);
                break;
        }
    }
}