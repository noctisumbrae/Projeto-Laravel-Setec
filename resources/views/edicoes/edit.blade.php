@extends('layouts.app')

@section('content')
    <h1>Editar edição</h1>

    @include('edicoes._mensagens')

    <form method="POST" action="{{ route('edicoes.update', $edicao) }}">
        @csrf
        @method('PUT')
        @include('edicoes._form')
    </form>
@endsection