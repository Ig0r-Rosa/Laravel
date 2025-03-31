@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Autenticação Nexti</div>

                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('auth.login') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="client_id">Client ID</label>
                            <input id="client_id" type="text" class="form-control" name="client_id" value="{{ old('client_id') }}" required autofocus>
                        </div>

                        <div class="form-group mb-3">
                            <label for="client_secret">Client Secret</label>
                            <input id="client_secret" type="password" class="form-control" name="client_secret" required>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                Autenticar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection