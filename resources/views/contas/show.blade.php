@extends('master')

@section('content')
    <div class="card mt-4 mb-4 border-ligth shadow">
        <div class="card-header d-flex justify-content-between">
            <span>Visualizar Conta</span>
            <span>
                <a class="btn btn-info btn-sm me-1" href="{{ route('conta.index') }}">
                    Voltar
                </a>
                <a class="btn btn-warning btn-sm me-1" href="{{ route('conta.edit', ['conta' => $conta->id]) }}">
                    Editar
                </a>
            </span>
        </div>

        @if (session('success'))
            <div class="alert alert-success m-3" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">ID</dt>
                <dd class="col-sm-9">{{ $conta->id }}</dd>
                <dt class="col-sm-3">Nome</dt>
                <dd class="col-sm-9">{{ $conta->nome }}</dd>
                <dt class="col-sm-3">Valor</dt>
                <dd class="col-sm-9">{{ 'R$'. number_format($conta->valor, 2, ',', '.') }}</dd>
                <dt class="col-sm-3">Vencimento</dt>
                <dd class="col-sm-9">{{ \Carbon\Carbon::parse($conta->vencimento)->tz('America/Sao_Paulo')->format('d/m/Y')  }}</dd>
                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">{!! '<span class="badge text-bg-'.$conta->statusConta->cor.'">'.$conta->statusConta->nome.'</span>' !!}</dd>
                <dt class="col-sm-3">Cadastrado</dt>
                <dd class="col-sm-9">{{ \Carbon\Carbon::parse($conta->created_at)->tz('America/Sao_Paulo')->format('d/m/Y H:i:s')  }}</dd>
                <dt class="col-sm-3">Editado</dt>
                <dd class="col-sm-9">{{ \Carbon\Carbon::parse($conta->updated_at)->tz('America/Sao_Paulo')->format('d/m/Y H:i:s')  }}</dd>
            </dl>
        </div>
    </div>
@endsection