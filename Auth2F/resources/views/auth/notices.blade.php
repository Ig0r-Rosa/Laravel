@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Notices</div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Data Início</th>
                                    <th>Data Fim</th>
                                    <th>Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notices as $notice)
                                <tr>
                                    <td>{{ $notice['id'] }}</td>
                                    <td>{{ $notice['name'] }}</td>
                                    <td>{{ \Carbon\Carbon::createFromFormat('dmYHis', $notice['startDate'])->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::createFromFormat('dmYHis', $notice['finishDate'])->format('d/m/Y') }}</td>
                                    <td>{{ $notice['noticeTypeId'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection