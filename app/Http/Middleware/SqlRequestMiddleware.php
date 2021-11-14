<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SqlRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $type = $request->type;
        $raw = Str::lower(json_encode($request->all()));
        $contains = ['INFORMATION_SCHEMA'];
        if (Str::contains($raw, json_decode(Str::lower(json_encode($contains))))) {
            $this->forbidden('Terdapat query berbahaya, permintaan kami tolak');
        }

        // Dont worry about `where` or `where(`
        $haveToBindings = ['where', 'values', 'set'];
        $needles = [];
        foreach ($haveToBindings as $item) {
            $needles[] = ' '.$item.' ';
            $needles[] = $item.'(';
        }
        if (Str::contains($raw, $needles)) {
            if (!$request->has('bindings')) {
                $this->forbidden('Mohon gunakan query binding');
            }
        }

        /*
         * Selain tipe write cek apakah ada query write yang termasuk kedalam query
         * apabila ada maka hentikan requestnya
         */
        if ('write' != $type) {
            $writeOnly = ['insert', 'update', 'delete', 'drop', 'alter', 'create'];
            if (Str::contains($raw, $writeOnly)) {
                $this->forbidden("Query anda seharusnya ada pada tipe 'write'");
            }
        }

        return $next($request);
    }

    public function forbidden(string $message)
    {
        return abort(403, $message);
    }
}