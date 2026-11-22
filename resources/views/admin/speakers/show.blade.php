@extends('layouts.app')

@section('title', $speaker->name)

@section('content')
    <h2>{{ $speaker->name }}</h2>

    <p><strong>Instituição:</strong> {{ $speaker->institution ?: 'Não informada' }}</p>
    <p><strong>Formação / Titulação:</strong> {{ $speaker->academic_title ?: 'Não informada' }}</p>
    <h3>Minicurrículo</h3>
    <p>{!! nl2br(e($speaker->bio)) !!}</p>

    <p>
        <a href="{{ route('speakers.edit', $speaker) }}">Editar</a>
        <a href="{{ route('speakers.index') }}">Voltar à lista</a>
    </p>
@endsection
