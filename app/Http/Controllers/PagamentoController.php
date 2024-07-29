<?php

namespace App\Http\Controllers;

use App\Http\Requests\PagamentoFormRequest;
use App\Models\Licenca;
use App\Models\Pagamento;
use Illuminate\Http\Request;
use App\Utils\Util;
use Efi\EfiPay;
use Efi\Exception\EfiException;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class PagamentoController extends Controller
{

    protected $model;

    public function __construct(Pagamento $model)
    {
        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $licenca = Licenca::orderBy('validade', 'desc')->first();
        $pagamentos = $this->model->orderBy('id', 'desc')->get();
        return view('pagamento.index', compact('pagamentos', 'licenca'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pagamento.form_pix');
    }


    public function status(Request $request, $documento)
    {

        $pagto = Pagamento::where('status', 'ATIVA')
            ->where('documento', $documento)
            ->orderBy('id', 'desc')->first();

            if (isset($pagto)) {
                $params = [
                    "txid" => $pagto->documento
                ];

                try {
                    $api =  new EfiPay(Util::credentials());
                    $response = $api->pixDetailCharge($params);

                    if ($response['status'] == 'CONCLUIDA') {

                        try {
                            $pagto->update([
                                'status' => $response['status'],
                                'data_pagamento' => Util::formatBrDate($response['pix'][0]['horario'],'Y-m-d')
                            ]);

                        } catch (\Throwable $th) {
                            return response()->json(['message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
                        }

                        Licenca::create([
                            'validade' => Carbon::now()->addYears(1)->format('Y-m-d')
                            ]
                        );

                        return redirect()->route('home');
                    }
                    else {
                        return redirect()->route('pagamentos.index');
                    }

                } catch (EfiException $e) {
                    response()->json([
                        'code' => $e->code,
                        'error' => $e->error,
                        'message' => $e->errorDescription
                    ],Response::HTTP_BAD_REQUEST);
                } catch (\Exception $e) {
                    return redirect()->route('pagamentos.index');
                }
            } else {
                return redirect()->route('pagamentos.index');
            }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PagamentoFormRequest $request)
    {
        $data = $request->only('nome', 'cpfcnpj');
        $data['cpfcnpj'] = Util::numberOnly($data['cpfcnpj']);

        $tipoDocumento = strlen($data['cpfcnpj']) == 11 ? 'cpf' : 'cnpj';

        $pagto = Pagamento::whereIn('status', ['ABERTO', 'ATIVA'])
            ->orderBy('id', 'desc')->first();

        $body = [
            "calendario" => [
                "expiracao" => 3600
            ],
            "devedor" => [
                $tipoDocumento => $data['cpfcnpj'],
                "nome" => $data['nome']
            ],
            "valor" => [
               "original" => number_format($pagto->valor,2,'.',',')

            ],
            "chave" => env('GN_CHAVE_PIX'),
        ];

//        dd($body);

        try {
            $api =  new EfiPay(Util::credentials());

            //dd($api);

	        $pix = $api->pixCreateImmediateCharge($params = [], $body);



            if ($pix["txid"]) {

                $params = [
                    "id" => $pix["loc"]["id"]
                ];

                $qrcode = $api->pixGenerateQRCode($params);

                $pagto->update([
                    'documento' => $pix["txid"],
                    'status' =>  $pix["status"],
                    'qrcode' => $qrcode['qrcode']
                ]);



                return view('pagamento.qrcode', [
                    'qrcode' => $qrcode['qrcode'],
                    'image' => $qrcode["imagemQrcode"],
                ]);

            }


        } catch (EfiException $e) {
            return response()->json([
                'code' => $e->code,
                'error' => $e->error,
                'message' => $e->errorDescription
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
