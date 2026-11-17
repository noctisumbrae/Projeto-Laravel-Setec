@extends('layouts.app')

@section('title', 'Editar Palestrante ou Instrutor')

@section('content')
    <h2>Editar Palestrante ou Instrutor</h2>

    <form action="{{ route('speakers.update', $speaker) }}" method="POST">
        @csrf
        @method('PUT')

        <p>
            <label for="name">Nome</label><br>
            <input id="name" name="name" type="text" value="{{ old('name', $speaker->name) }}" required maxlength="255">
            @error('name')<br><span role="alert">{{ $message }}</span>@enderror
        </p>

        <p>
            <label for="institution">Instituição</label><br>
            <input id="institution" name="institution" type="text" value="{{ old('institution', $speaker->institution) }}" maxlength="255">
            @error('institution')<br><span role="alert">{{ $message }}</span>@enderror
        </p>

        <p>
            <label for="academic_title">Formação / Titulação</label><br>
            <input id="academic_title" name="academic_title" type="text" value="{{ old('academic_title', $speaker->academic_title) }}" maxlength="255">
            @error('academic_title')<br><span role="alert">{{ $message }}</span>@enderror
        </p>

        <p>
            <label for="bio">Minicurrículo</label><br>
            <textarea id="bio" name="bio" rows="6" required>{{ old('bio', $speaker->bio) }}</textarea>
            @error('bio')<br><span role="alert">{{ $message }}</span>@enderror
        </p>

        <button type="submit">Salvar alterações</button>
        <a href="{{ route('speakers.index') }}">Cancelar</a>
    </form>
@endsection
