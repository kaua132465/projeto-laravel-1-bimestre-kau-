<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    $game = $request->session()->get('hangman');

    return view('hangman', [
        'game' => $game,
        'maskedWord' => $game ? collect(mb_str_split($game['word']))
            ->map(fn (string $letter) => $letter === ' ' ? ' ' : (in_array($letter, $game['guesses'], true) ? $letter : '_'))
            ->implode(' ') : null,
    ]);
});

Route::post('/jogo', function (Request $request) {
    $action = $request->input('action');

    if ($action === 'start') {
        $validated = $request->validate([
            'word' => ['required', 'string', 'min:1', 'max:30', 'regex:/^[\pL\s]+$/u'],
        ], [
            'word.regex' => 'Use apenas letras e espacos na palavra secreta.',
        ]);

        $word = preg_replace('/\s+/', ' ', trim(mb_strtoupper($validated['word'])));

        $request->session()->put('hangman', [
            'word' => $word,
            'guesses' => [],
            'wrong_guesses' => 0,
            'max_wrong_guesses' => 6,
            'status' => 'playing',
            'message' => 'Jogo iniciado. Boa sorte!',
        ]);

        return redirect('/');
    }

    if ($action === 'guess') {
        $game = $request->session()->get('hangman');

        if (! $game) {
            return redirect('/')->withErrors(['word' => 'Crie um jogo antes de tentar letras.']);
        }

        $validated = $request->validate([
            'letter' => ['required', 'string', 'size:1', 'regex:/^\pL$/u'],
        ], [
            'letter.regex' => 'Digite apenas uma letra.',
        ]);

        if ($game['status'] !== 'playing') {
            $game['message'] = 'Esse jogo ja terminou. Crie outro para continuar.';
            $request->session()->put('hangman', $game);

            return redirect('/');
        }

        $letter = mb_strtoupper($validated['letter']);

        if (in_array($letter, $game['guesses'], true)) {
            $game['message'] = "A letra {$letter} ja foi tentada.";
            $request->session()->put('hangman', $game);

            return redirect('/');
        }

        $game['guesses'][] = $letter;

        if (! str_contains($game['word'], $letter)) {
            $game['wrong_guesses']++;
            $game['message'] = "A letra {$letter} nao aparece na palavra.";
        } else {
            $game['message'] = "Boa! A letra {$letter} existe na palavra.";
        }

        $lettersInWord = collect(mb_str_split(str_replace(' ', '', $game['word'])))
            ->unique()
            ->values()
            ->all();

        $guessedAllLetters = count(array_diff($lettersInWord, $game['guesses'])) === 0;

        if ($guessedAllLetters) {
            $game['status'] = 'won';
            $game['message'] = 'Voce venceu!';
        } elseif ($game['wrong_guesses'] >= $game['max_wrong_guesses']) {
            $game['status'] = 'lost';
            $game['message'] = 'Fim de jogo! A palavra foi revelada.';
        }

        $request->session()->put('hangman', $game);

        return redirect('/');
    }

    $request->session()->forget('hangman');

    return redirect('/');
});
