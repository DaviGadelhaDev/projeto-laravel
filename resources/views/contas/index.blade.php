@extends('master')

@section('content')

    <div class="card mt-3 mb-4 border-ligth shadow">
        <div class="card-header d-flex justify-content-between">
            <span>Pesquisar</span>
        </div>
        <div class="card-body">
            <form action="{{ route('conta.index') }}">
                <div class="row">
                    <div class="col-md-3 col-sm-12">
                        <label class="form-label" for="nome">Nome</label>
                        <input type="text" name="nome" id="nome" class="form-control" value="{{ $nome }}" placeholder="Nome da conta">
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <label class="form-label" for="data_inicio">Data Início</label>
                        <input type="date" name="data_inicio" id="data_inicio" class="form-control" value="{{ $data_inicio }}">
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <label class="form-label" for="data_fim">Data Final</label>
                        <input type="date" name="data_fim" id="data_fim" class="form-control" value="{{ $data_fim }}">
                    </div>
                    <div class="col-md-3 col-sm-12 mt-3 pt-4">
                        <button type="submit" class="btn btn-info btn-sm">Pesquisar</button>
                        <a href="{{ route('conta.index') }}" class="btn btn-warning btn-sm">Limpar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-4 mb-4 border-ligth shadow">
        <div class="card-header d-flex justify-content-between">
            <span>Listar Contas</span>
            <span>
                <a  class=" btn btn-dark btn-sm" href="{{ route('conta.create') }}">
                  Cadastrar
                </a>
                <a href="{{ url('gerar-pdf-conta?' . request()->getQueryString()) }}" class="btn btn-secondary btn-sm">Exportar</a>
                <a href="{{ url('gerar-csv-conta?' . request()->getQueryString()) }}" class="btn btn-success btn-sm">Gerar Excel</a>
            </span>
        </div>

        @if (session('success'))
            <div class="alert alert-success m-3" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <td scope="col">Id: </td>
                        <td scope="col">Nome:</td>
                        <td scope="col">Valor:</td>
                        <td scope="col">Vencimento: </td>
                        <td scope="col">Status</td>
                        <td scope="col"></td>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($contas as $conta)
                    <tr>
                        <td>{{ $conta->id }}</td>
                        <td>{{ $conta->nome }}</td>
                        <td>{{ $conta->valor }}</td>
                        <td>{{ \Carbon\Carbon::parse($conta->vencimento)->tz('America/Sao_Paulo')->format('d/m/Y') }}</td>
                        <td>{!! '<span class="badge text-bg-'.$conta->statusConta->cor.'">'.$conta->statusConta->nome.'</span>' !!}</td>
                        <td class="d-md-flex justify-content-center">
                            <a  class="btn btn-primary btn-sm me-1" href="{{ route('conta.show', ['conta' => $conta->id]) }}">
                                Visualizar
                            </a>
                            <a class="btn btn-warning btn-sm me-1" href="{{ route('conta.edit', ['conta' => $conta->id]) }}">
                                Editar
                            </a>
                            <form action="{{ route('conta.destroy', ['conta' => $conta->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm me-1" onclick="return confirm('Tem certeza que deseja excluir essa conta ?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <span style="color:#f00;">Nenhuma conta encontrada</span>
                    @endforelse 
                </tbody>
            </table>
            {{ $contas->links() }}
        </div>
    </div>
@endsection