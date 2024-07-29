<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Licenca;
use App\Models\Pagamento;
use App\Utils\Util;

class LicencaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $today = Date('Y-m-d');
            $licenca = Licenca::orderBy('validade', 'desc')->first();

            if (!isset($licenca)){
                $this->checarPagamento();
                if (auth()->user()->superuser == 1) {
                    return redirect()->route('pagamentos.index');
                } else {
                    return redirect('/negado');
                }

            } else {
                if ($today > $licenca->validade) {
                    $this->checarPagamento();
                    if (auth()->user()->superuser == 1) {
                        return redirect()->route('pagamentos.index');
                    } else {
                        return redirect('/negado');
                    }
                }
            }

        }

        return $next($request);
    }

    /**
     * Chega se tem algum pagamento em aberto, caso nao tenho
     * cria um
     *
     * @return void
     */
    private function checarPagamento()
    {
        $pagamento = Pagamento::whereIn('status', ['ABERTO', 'ATIVA'])->get();
        if (count($pagamento) == 0) {
            Pagamento::create([
                'valor' => Util::$mensalidade,
                'status' => 'ABERTO'
            ]);
        }

    }
}
