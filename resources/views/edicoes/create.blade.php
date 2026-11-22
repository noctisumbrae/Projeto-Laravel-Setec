@extends('layouts.app')

@section('content')
    <h1>Nova edição</h1>

    @include('edicoes._mensagens')

    <form method="POST" action="{{ route('edicoes.store') }}">
        @csrf
        @include('edicoes._form')
    </form>
@endsection