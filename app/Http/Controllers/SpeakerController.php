<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpeakerRequest;
use App\Models\Speaker;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SpeakerController extends Controller
{
    public function index(): View
    {
        $speakers = Speaker::query()->orderBy('name')->paginate(10);

        return view('admin.speakers.index', compact('speakers'));
    }

    public function create(): View
    {
        return view('admin.speakers.create');
    }

    public function store(SpeakerRequest $request): RedirectResponse
    {
        Speaker::create($request->validated());

        return redirect()->route('speakers.index')
            ->with('success', 'Palestrante/instrutor cadastrado com sucesso.');
    }

    public function show(Speaker $speaker): View
    {
        return view('admin.speakers.show', compact('speaker'));
    }

    public function edit(Speaker $speaker): View
    {
        return view('admin.speakers.edit', compact('speaker'));
    }

    public function update(SpeakerRequest $request, Speaker $speaker): RedirectResponse
    {
        $speaker->update($request->validated());

        return redirect()->route('speakers.index')
            ->with('success', 'Palestrante/instrutor atualizado com sucesso.');
    }

    public function destroy(Speaker $speaker): RedirectResponse
    {
        $speaker->delete();

        return redirect()->route('speakers.index')
            ->with('success', 'Palestrante/instrutor excluído com sucesso.');
    }
}
