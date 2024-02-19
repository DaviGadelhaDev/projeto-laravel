<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use Exception;
use Illuminate\Http\Request;

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
        return view('contas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        $request->validate([
            'nome' => 'required',
            'valor' => 'required',
            'vencimento' => 'required'
        ]); 

        $conta = Conta::create([
            'nome' => $request->nome,
            'valor' => str_replace(',', '.', str_replace('.', '', $request->valor)),
            'vencimento' => $request->vencimento
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
        return view('contas.edit', ['conta' => $conta]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Conta $conta)
    {
        $request->validate([
            'nome' => 'required',
            'valor' => 'required',
            'vencimento' => 'required'
        ]); 
        try{
            $conta->update($request->all());
            return redirect()->route('conta.index', ['conta' => $conta->id])->with('success', 'Conta editada com sucesso');
        }
       catch(Exception $error){
        return redirect()->back()->withInput()->whith('error', 'Erro ao editar a conta!!');
       }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conta $conta){
        $conta->delete();
        return redirect()->route('conta.index')->with('success', 'Conta excluida com sucesso');
    }
}
