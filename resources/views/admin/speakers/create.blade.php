@extends('layouts.app')

@section('title', 'Cadastrar Palestrante ou Instrutor')

@section('content')
    <h2>Cadastrar Palestrante ou Instrutor</h2>

    <form action="{{ route('speakers.store') }}" method="POST">
        @csrf

        <p>
            <label for="name">Nome</label><br>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required maxlength="255">
            @error('name')<br><span role="alert">{{ $message }}</span>@enderror
        </p>

        <p>
            <label for="institution">Instituição</label><br>
            <input id="institution" name="institution" type="text" value="{{ old('institution') }}" maxlength="255">
            @error('institution')<br><span role="alert">{{ $message }}</span>@enderror
        </p>

        <p>
            <label for="academic_title">Formação / Titulação</label><br>
            <input id="academic_title" name="academic_title" type="text" value="{{ old('academic_title') }}" maxlength="255">
            @error('academic_title')<br><span role="alert">{{ $message }}</span>@enderror
        </p>

        <p>
            <label for="bio">Minicurrículo</label><br>
            <textarea id="bio" name="bio" rows="6" required>{{ old('bio') }}</textarea>
            @error('bio')<br><span role="alert">{{ $message }}</span>@enderror
        </p>

        <button type="submit">Salvar</button>
        <a href="{{ route('speakers.index') }}">Cancelar</a>
    </form>
@endsection
