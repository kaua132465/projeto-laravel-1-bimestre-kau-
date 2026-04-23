<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response(<<<'HTML'
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #091540;
            --bg-soft: #0f1f5c;
            --panel: rgba(255, 255, 255, 0.12);
            --panel-strong: rgba(255, 255, 255, 0.18);
            --line: rgba(255, 255, 255, 0.14);
            --text: #f8fbff;
            --muted: #b7c4ff;
            --accent: #ffd166;
            --accent-2: #7bdff2;
            --shadow: 0 24px 80px rgba(0, 0, 0, 0.35);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            font-family: "Space Grotesk", sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(123, 223, 242, 0.25), transparent 28%),
                radial-gradient(circle at bottom right, rgba(255, 209, 102, 0.22), transparent 30%),
                linear-gradient(135deg, var(--bg), var(--bg-soft));
        }

        .shell {
            width: min(100%, 980px);
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: 24px;
            align-items: stretch;
        }

        .hero,
        .calculator {
            border: 1px solid var(--line);
            background: var(--panel);
            backdrop-filter: blur(18px);
            border-radius: 32px;
            box-shadow: var(--shadow);
        }

        .hero {
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 28px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.09);
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.12em;
            font-size: 12px;
            font-weight: 700;
        }

        h1 {
            margin: 0;
            font-size: clamp(42px, 7vw, 86px);
            line-height: 0.95;
        }

        p {
            margin: 0;
            font-size: 18px;
            line-height: 1.7;
            color: var(--muted);
        }

        .tips {
            display: grid;
            gap: 14px;
        }

        .tip {
            padding: 18px 20px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .tip strong {
            display: block;
            margin-bottom: 6px;
            font-size: 15px;
        }

        .calculator {
            padding: 22px;
        }

        .display {
            padding: 18px;
            border-radius: 24px;
            background: rgba(6, 13, 38, 0.78);
            border: 1px solid var(--line);
            margin-bottom: 18px;
        }

        .display-label {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--muted);
        }

        .expression {
            min-height: 28px;
            margin-top: 12px;
            font-size: 16px;
            color: #d5ddff;
            word-break: break-all;
        }

        .result {
            margin-top: 8px;
            font-size: clamp(42px, 6vw, 56px);
            font-weight: 700;
            text-align: right;
            word-break: break-all;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        button {
            border: 0;
            border-radius: 20px;
            padding: 18px;
            font: inherit;
            font-size: 22px;
            font-weight: 700;
            color: var(--text);
            cursor: pointer;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid transparent;
            transition: transform 0.15s ease, background 0.15s ease, border-color 0.15s ease;
        }

        button:hover {
            transform: translateY(-1px);
            background: rgba(255, 255, 255, 0.14);
            border-color: rgba(255, 255, 255, 0.12);
        }

        button.operator {
            background: rgba(123, 223, 242, 0.14);
            color: #dffaff;
        }

        button.accent {
            background: linear-gradient(135deg, var(--accent), #ffb703);
            color: #372400;
        }

        button.equal {
            background: linear-gradient(135deg, #7bdff2, #54c6eb);
            color: #06273d;
        }

        button.span-2 {
            grid-column: span 2;
        }

        .footer-note {
            margin-top: 18px;
            text-align: center;
            font-size: 14px;
            color: var(--muted);
        }

        @media (max-width: 900px) {
            .shell {
                grid-template-columns: 1fr;
            }

            .hero {
                padding: 28px;
            }
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="hero">
            <div>
                <span class="eyebrow">Site substituido por calculadora</span>
                <h1>Calcule rapido, sem sair da tela inicial.</h1>
            </div>

            <p>O site agora abre direto em uma calculadora com operacoes basicas, interface responsiva e atalhos de teclado para usar no desktop.</p>

            <div class="tips">
                <div class="tip">
                    <strong>Atalhos</strong>
                    Digite numeros, use <code>+ - * /</code>, pressione <code>Enter</code> para calcular e <code>Backspace</code> para apagar.
                </div>
                <div class="tip">
                    <strong>Recursos</strong>
                    A calculadora aceita ponto decimal, limpa tudo com <code>AC</code> e tenta evitar erros como divisao por zero.
                </div>
            </div>
        </section>

        <section class="calculator">
            <div class="display">
                <div class="display-label">Calculadora</div>
                <div class="expression" id="expression">0</div>
                <div class="result" id="result">0</div>
            </div>

            <div class="grid">
                <button class="accent" data-action="clear">AC</button>
                <button class="operator" data-action="backspace">DEL</button>
                <button class="operator" data-value="%">%</button>
                <button class="operator" data-value="/">÷</button>

                <button data-value="7">7</button>
                <button data-value="8">8</button>
                <button data-value="9">9</button>
                <button class="operator" data-value="*">×</button>

                <button data-value="4">4</button>
                <button data-value="5">5</button>
                <button data-value="6">6</button>
                <button class="operator" data-value="-">−</button>

                <button data-value="1">1</button>
                <button data-value="2">2</button>
                <button data-value="3">3</button>
                <button class="operator" data-value="+">+</button>

                <button class="span-2" data-value="0">0</button>
                <button data-value=".">.</button>
                <button class="equal" data-action="calculate">=</button>
            </div>

            <div class="footer-note">Use a calculadora pelo mouse ou teclado.</div>
        </section>
    </main>

    <script>
        const expressionEl = document.getElementById('expression');
        const resultEl = document.getElementById('result');
        const buttons = document.querySelectorAll('button');
        let expression = '';

        const render = () => {
            expressionEl.textContent = expression || '0';
            resultEl.textContent = preview(expression);
        };

        const sanitizePercent = (value) => value.replace(/(\d+(\.\d+)?)%/g, '($1/100)');

        const preview = (value) => {
            if (!value) return '0';

            try {
                const normalized = sanitizePercent(value);
                const outcome = Function('"use strict"; return (' + normalized + ')')();

                if (!Number.isFinite(outcome)) {
                    return 'Erro';
                }

                return formatNumber(outcome);
            } catch {
                return '...';
            }
        };

        const formatNumber = (value) => {
            if (Number.isInteger(value)) {
                return String(value);
            }

            return String(parseFloat(value.toFixed(10)));
        };

        const appendValue = (value) => {
            const operators = ['+', '-', '*', '/', '%'];
            const last = expression.slice(-1);

            if (operators.includes(value)) {
                if (!expression && value !== '-') return;
                if (operators.includes(last)) {
                    expression = expression.slice(0, -1) + value;
                    return render();
                }
            }

            if (value === '.') {
                const currentChunk = expression.split(/[+\-*/%]/).pop();
                if (currentChunk.includes('.')) return;
                if (!currentChunk) {
                    expression += '0';
                }
            }

            expression += value;
            render();
        };

        const clearAll = () => {
            expression = '';
            render();
        };

        const backspace = () => {
            expression = expression.slice(0, -1);
            render();
        };

        const calculate = () => {
            const result = preview(expression);
            if (result === 'Erro' || result === '...') return;
            expression = result;
            render();
        };

        buttons.forEach((button) => {
            button.addEventListener('click', () => {
                const { value, action } = button.dataset;
                if (value) appendValue(value);
                if (action === 'clear') clearAll();
                if (action === 'backspace') backspace();
                if (action === 'calculate') calculate();
            });
        });

        window.addEventListener('keydown', (event) => {
            const allowed = '0123456789+-*/%.';

            if (allowed.includes(event.key)) {
                appendValue(event.key);
            } else if (event.key === 'Enter' || event.key === '=') {
                event.preventDefault();
                calculate();
            } else if (event.key === 'Backspace') {
                backspace();
            } else if (event.key === 'Escape') {
                clearAll();
            }
        });

        render();
    </script>
</body>
</html>
HTML
    );
});
