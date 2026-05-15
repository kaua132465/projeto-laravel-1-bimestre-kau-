<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo da Forca em Grupo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;500;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f6efe5;
            --bg-strong: #eadcc8;
            --surface: rgba(255, 251, 245, 0.86);
            --surface-strong: #fffaf1;
            --text: #2d1f12;
            --muted: #705845;
            --line: rgba(75, 46, 24, 0.14);
            --accent: #c96c3a;
            --accent-dark: #8e3f16;
            --success: #2f7a49;
            --danger: #a53d2d;
            --shadow: 0 26px 70px rgba(104, 63, 33, 0.18);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "DM Sans", sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(201, 108, 58, 0.22), transparent 28%),
                radial-gradient(circle at bottom right, rgba(96, 148, 113, 0.16), transparent 24%),
                linear-gradient(135deg, var(--bg), var(--bg-strong));
        }

        .page {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
            padding: 32px 0 48px;
        }

        .hero {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 24px;
            align-items: stretch;
        }

        .panel {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 32px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(12px);
        }

        .intro {
            padding: 36px;
        }

        .eyebrow {
            display: inline-flex;
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(201, 108, 58, 0.12);
            color: var(--accent-dark);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 12px;
            font-weight: 700;
        }

        h1, h2 {
            font-family: "Bricolage Grotesque", sans-serif;
            margin: 0;
        }

        h1 {
            margin-top: 18px;
            font-size: clamp(42px, 8vw, 78px);
            line-height: 0.95;
        }

        .intro p {
            margin: 18px 0 0;
            max-width: 58ch;
            font-size: 18px;
            line-height: 1.7;
            color: var(--muted);
        }

        .tips {
            margin-top: 28px;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .tip {
            padding: 18px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.56);
            border: 1px solid rgba(75, 46, 24, 0.08);
        }

        .tip strong {
            display: block;
            margin-bottom: 8px;
            font-size: 15px;
        }

        .setup,
        .game {
            padding: 28px;
        }

        .setup h2,
        .game h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .setup p,
        .game p {
            margin: 0 0 18px;
            color: var(--muted);
            line-height: 1.6;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            border: 1px solid rgba(75, 46, 24, 0.16);
            border-radius: 18px;
            padding: 16px 18px;
            font: inherit;
            font-size: 18px;
            color: var(--text);
            background: rgba(255, 255, 255, 0.82);
        }

        input:focus {
            outline: 3px solid rgba(201, 108, 58, 0.22);
            border-color: rgba(201, 108, 58, 0.42);
        }

        button {
            border: 0;
            border-radius: 18px;
            padding: 15px 20px;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.16s ease, box-shadow 0.16s ease, opacity 0.16s ease;
        }

        button:hover {
            transform: translateY(-1px);
        }

        .primary {
            background: linear-gradient(135deg, var(--accent), #dd9350);
            color: #fff8f2;
            box-shadow: 0 16px 30px rgba(201, 108, 58, 0.28);
        }

        .secondary {
            background: rgba(75, 46, 24, 0.08);
            color: var(--text);
        }

        .form-row {
            display: flex;
            gap: 12px;
            align-items: end;
        }

        .form-row .field {
            flex: 1;
        }

        .stack {
            display: grid;
            gap: 24px;
            margin-top: 24px;
        }

        .board {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            align-items: start;
        }

        .gallows {
            min-height: 340px;
            padding: 24px;
            border-radius: 28px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.55), rgba(234, 220, 200, 0.7));
            border: 1px solid rgba(75, 46, 24, 0.08);
        }

        .word-card {
            padding: 24px;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.58);
            border: 1px solid rgba(75, 46, 24, 0.08);
        }

        .masked-word {
            font-family: "Bricolage Grotesque", sans-serif;
            font-size: clamp(28px, 5vw, 48px);
            letter-spacing: 0.2em;
            line-height: 1.3;
            word-break: break-word;
        }

        .status {
            display: inline-flex;
            margin-top: 18px;
            padding: 10px 14px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 700;
        }

        .status.playing { background: rgba(201, 108, 58, 0.12); color: var(--accent-dark); }
        .status.won { background: rgba(47, 122, 73, 0.14); color: var(--success); }
        .status.lost { background: rgba(165, 61, 45, 0.12); color: var(--danger); }

        .meta {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-top: 18px;
        }

        .meta-box {
            padding: 16px;
            border-radius: 20px;
            background: rgba(255, 250, 241, 0.9);
            border: 1px solid rgba(75, 46, 24, 0.08);
        }

        .meta-box span {
            display: block;
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 6px;
        }

        .meta-box strong {
            font-size: 22px;
        }

        .letters {
            margin-top: 18px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .letters span {
            min-width: 42px;
            padding: 10px 12px;
            text-align: center;
            border-radius: 14px;
            background: rgba(75, 46, 24, 0.08);
            font-weight: 700;
        }

        .message {
            padding: 16px 18px;
            border-radius: 18px;
            background: rgba(255, 250, 241, 0.88);
            border: 1px solid rgba(75, 46, 24, 0.08);
        }

        .errors {
            margin-top: 14px;
            padding: 14px 16px;
            border-radius: 18px;
            background: rgba(165, 61, 45, 0.08);
            color: var(--danger);
        }

        .reveal {
            margin-top: 16px;
            font-weight: 700;
            color: var(--danger);
        }

        svg {
            width: 100%;
            height: auto;
        }

        .hangman-part {
            opacity: 0.14;
            transition: opacity 0.2s ease;
        }

        .hangman-part.visible {
            opacity: 1;
        }

        @media (max-width: 900px) {
            .hero,
            .board,
            .tips,
            .meta {
                grid-template-columns: 1fr;
            }

            .intro,
            .setup,
            .game {
                padding: 24px;
            }

            .form-row {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <main class="page">
        @if (! $game)
            <section class="hero">
                <article class="panel intro">
                    <span class="eyebrow">Laravel + jogo classico em grupo</span>
                    <h1>Forca feita para jogar em grupo.</h1>
                    <p>Uma pessoa ou equipe escolhe a palavra secreta, e o restante do grupo tenta descobrir antes do boneco ser completado. Tudo acontece na tela inicial do projeto.</p>

                    <div class="tips">
                        <div class="tip">
                            <strong>Como funciona</strong>
                            Digite a palavra, inicie o jogo e depois o grupo pode sugerir letras uma por vez.
                        </div>
                        <div class="tip">
                            <strong>Modo grupo</strong>
                            Dividam a turma em quem escolhe a palavra e quem tenta adivinhar. Sao 6 erros maximos por rodada.
                        </div>
                    </div>
                </article>

                <aside class="panel setup">
                    <h2>Nova palavra</h2>
                    <p>Escolha a palavra secreta que vai iniciar a rodada do grupo.</p>

                    <form method="POST" action="/jogo">
                        @csrf
                        <input type="hidden" name="action" value="start">

                        <label for="word">Palavra secreta</label>
                        <input
                            id="word"
                            name="word"
                            type="password"
                            inputmode="text"
                            autocomplete="off"
                            placeholder="Ex.: abacaxi"
                            maxlength="30"
                            required
                        >

                        <div style="margin-top: 16px;">
                            <button class="primary" type="submit">Iniciar jogo</button>
                        </div>
                    </form>

                    @if ($errors->any())
                        <div class="errors">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                </aside>
            </section>
        @endif

        @if ($game)
            <section class="panel game stack">
                <div>
                    <h2>Rodada atual do grupo</h2>
                    <p>O grupo precisa descobrir a palavra secreta antes de atingir o limite de erros.</p>
                </div>

                <div class="board">
                    <div class="gallows">
                        <svg viewBox="0 0 260 320" aria-hidden="true">
                            <line x1="30" y1="290" x2="180" y2="290" stroke="#6b4a2f" stroke-width="8" stroke-linecap="round"/>
                            <line x1="65" y1="290" x2="65" y2="35" stroke="#6b4a2f" stroke-width="8" stroke-linecap="round"/>
                            <line x1="65" y1="35" x2="170" y2="35" stroke="#6b4a2f" stroke-width="8" stroke-linecap="round"/>
                            <line x1="170" y1="35" x2="170" y2="70" stroke="#6b4a2f" stroke-width="8" stroke-linecap="round"/>

                            <circle class="hangman-part {{ $game['wrong_guesses'] >= 1 ? 'visible' : '' }}" cx="170" cy="95" r="24" fill="none" stroke="#c96c3a" stroke-width="7"/>
                            <line class="hangman-part {{ $game['wrong_guesses'] >= 2 ? 'visible' : '' }}" x1="170" y1="119" x2="170" y2="185" stroke="#c96c3a" stroke-width="7" stroke-linecap="round"/>
                            <line class="hangman-part {{ $game['wrong_guesses'] >= 3 ? 'visible' : '' }}" x1="170" y1="140" x2="130" y2="168" stroke="#c96c3a" stroke-width="7" stroke-linecap="round"/>
                            <line class="hangman-part {{ $game['wrong_guesses'] >= 4 ? 'visible' : '' }}" x1="170" y1="140" x2="210" y2="168" stroke="#c96c3a" stroke-width="7" stroke-linecap="round"/>
                            <line class="hangman-part {{ $game['wrong_guesses'] >= 5 ? 'visible' : '' }}" x1="170" y1="185" x2="138" y2="232" stroke="#c96c3a" stroke-width="7" stroke-linecap="round"/>
                            <line class="hangman-part {{ $game['wrong_guesses'] >= 6 ? 'visible' : '' }}" x1="170" y1="185" x2="202" y2="232" stroke="#c96c3a" stroke-width="7" stroke-linecap="round"/>
                        </svg>
                    </div>

                    <div class="word-card">
                        <div class="masked-word">{{ $maskedWord }}</div>
                        <div class="status {{ $game['status'] }}">
                            @if ($game['status'] === 'won')
                                Vitoria
                            @elseif ($game['status'] === 'lost')
                                Derrota
                            @else
                                Em andamento
                            @endif
                        </div>

                        <div class="meta">
                            <div class="meta-box">
                                <span>Erros</span>
                                <strong>{{ $game['wrong_guesses'] }}</strong>
                            </div>
                            <div class="meta-box">
                                <span>Limite</span>
                                <strong>{{ $game['max_wrong_guesses'] }}</strong>
                            </div>
                            <div class="meta-box">
                                <span>Letras usadas</span>
                                <strong>{{ count($game['guesses']) }}</strong>
                            </div>
                        </div>

                        <div class="letters">
                            @forelse ($game['guesses'] as $guess)
                                <span>{{ $guess }}</span>
                            @empty
                                <span>Nenhuma</span>
                            @endforelse
                        </div>

                        <div class="message" style="margin-top: 18px;">
                            {{ $game['message'] }}
                        </div>

                        @if ($game['status'] === 'lost')
                            <div class="reveal">Palavra correta: {{ $game['word'] }}</div>
                        @endif
                    </div>
                </div>

                <form method="POST" action="/jogo">
                    @csrf
                    <input type="hidden" name="action" value="guess">

                    <div class="form-row">
                        <div class="field">
                            <label for="letter">Tente uma letra</label>
                            <input
                                id="letter"
                                name="letter"
                                type="text"
                                inputmode="text"
                                autocomplete="off"
                                maxlength="1"
                                placeholder="A"
                                {{ $game['status'] !== 'playing' ? 'disabled' : '' }}
                            >
                        </div>

                        <button class="primary" type="submit" {{ $game['status'] !== 'playing' ? 'disabled' : '' }}>
                            Chutar letra
                        </button>
                    </div>
                </form>

                <form method="POST" action="/jogo">
                    @csrf
                    <input type="hidden" name="action" value="reset">
                    <button class="secondary" type="submit">Reiniciar rodada</button>
                </form>
            </section>
        @endif
    </main>
</body>
</html>
