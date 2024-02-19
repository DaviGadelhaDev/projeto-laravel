<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use App\Models\StatusConta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ContaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //when usado para adicionar condições dinãmicas de consulta
        $contas = Conta::when($request->has('nome'), function($whenQuery) use ($request){
            $whenQuery->where('nome', 'like', '%'. $request->nome .'%');
        })
        ->when($request->filled('data_inicio'), function ($whenQuery) use ($request){
            $whenQuery->where('vencimento', '>=', \Carbon\Carbon::parse($request->data_inicio)->format('Y/m/d'));
        })
        ->when($request->filled('data_fim'), function ($whenQuery) use ($request){
            $whenQuery->where('vencimento', '<=', \Carbon\Carbon::parse($request->data_fim)->format('Y/m/d'));
        })
        ->with('statusConta')
        ->orderByDesc('created_at')
        ->paginate(10)
        ->withQueryString();
        return view('contas.index', ['contas' => $contas, 'nome' => $request->nome, 'data_inicio' => $request->data_inicio, 'data_fim' => $request->data_fim]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //recuperar do banco de dados as situações
        $statusContas = StatusConta::orderBy('nome', 'asc')->get();
        return view('contas.create', ['statusContas' => $statusContas]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        $request->validate([
            'nome' => 'required',
            'valor' => 'required',
            'vencimento' => 'required',
            'status_conta_id' => 'required'
        ],
        [
            'status_conta_id.required' => 'Selecione um status para a conta'
        ]); 

        Conta::create([
            'nome' => $request->nome,
            'valor' => str_replace(',', '.', str_replace('.', '', $request->valor)),
            'vencimento' => $request->vencimento,
            'status_conta_id' => $request->status_conta_id,
        ]);

        return redirect()->route('conta.index')->with('success', 'Conta cadastrada com sucesso');
    }

    /**
     * Display the specified resource.
     */
    public function show(Conta $conta)
    {
        return view('contas.show', ['conta' => $conta]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Conta $conta)
    {
        $statusContas = StatusConta::orderBy('nome', 'asc')->get();
        return view('contas.edit', ['conta' => $conta, 'statusContas' => $statusContas]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Conta $conta)
    {
        $request->validate([
            'nome' => 'required',
            'valor' => 'required',
            'vencimento' => 'required',
            'status_conta_id' => 'required'
        ],
        [
            'status_conta_id.required' => 'Selecione um status para a conta'
        ]); 
            $conta->update([
                'nome' => $request->nome,
                'valor' => str_replace(',', '.', str_replace('.', '', $request->valor)),
                'vencimento' => $request->vencimento,
                'status_conta_id' => $request->status_conta_id,
            ]);
            return redirect()->route('conta.index', ['conta' => $conta->id])->with('success', 'Conta editada com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conta $conta){
        $conta->delete();
        return redirect()->route('conta.index')->with('success', 'Conta excluida com sucesso');
    }

    public function gerarPdf(Request $request){
        $contas = Conta::when($request->has('nome'), function($whenQuery) use ($request){
            $whenQuery->where('nome', 'like', '%' .$request->nome. '%');
        })
        ->when($request->filled('data_inicio'), function ($whenQuery) use ($request){
            $whenQuery->where('vencimento', '>=', \Carbon\Carbon::parse($request->data_inicio)->format('Y/m/d'));
        })
        ->when($request->filled('data_fim'), function ($whenQuery) use ($request){
            $whenQuery->where('vencimento', '<=', \Carbon\Carbon::parse($request->data_fim)->format('Y/m/d'));
        })
        ->orderByDesc('created_at')
        ->get();
        $totalValor = $contas->sum('valor');
        $pdf = PDF::loadView('contas.gerar-pdf', ['contas' => $contas, 'totalValor' => $totalValor])->setPaper('a4', 'portrait');
        return $pdf->download('listar_contas.pdf');
    }

    public function gerarCsv(Request $request){
        $contas = Conta::when($request->has('nome'), function($whenQuery) use ($request){
            $whenQuery->where('nome', 'like', '%' .$request->nome. '%');
        })
        ->when($request->filled('data_inicio'), function ($whenQuery) use ($request){
            $whenQuery->where('vencimento', '>=', \Carbon\Carbon::parse($request->data_inicio)->format('Y/m/d'));
        })
        ->when($request->filled('data_fim'), function ($whenQuery) use ($request){
            $whenQuery->where('vencimento', '<=', \Carbon\Carbon::parse($request->data_fim)->format('Y/m/d'));
        })
        ->with('statusConta')
        ->orderByDesc('vencimento')
        ->get();

        $totalValor = $contas->sum('valor');
        $csvNomeArquivo = tempnam(sys_get_temp_dir(), 'csv_'. Str::ulid());
        $arquivoAberto = fopen($csvNomeArquivo, 'w');
        $cabecalho = ['id', 'Nome', 'Vencimento', 'Status', 'Valor'];
        fputcsv($arquivoAberto, $cabecalho, ';');
        foreach($contas as $conta){
            $contaArray = [
                'id' => $conta->id,
                'nome' => $conta->nome,
                'vencimento' => $conta->vencimento,
                'status' => $conta->StatusConta->nome,
                'Valor' => number_format($conta->valor, 2, ',', '.')
            ];
            fputcsv($arquivoAberto, $contaArray, ';');
        }  
        $rodape = ['', '', '', '', number_format($totalValor, 2, ',', '.')];
        fputcsv($arquivoAberto, $rodape, ';');

        fclose($arquivoAberto);

        return response()->download($csvNomeArquivo, 'relatorio_contas'. Str::ulid(). '.csv');
    }
}
