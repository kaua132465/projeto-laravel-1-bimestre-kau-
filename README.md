# Jogo da Forca em Laravel

Este projeto roda um jogo da forca simples em Laravel e pode ser publicado na nuvem para acesso por navegador em outro PC.

## Deploy simples na Railway

Este repositório já foi preparado com um `Procfile` para a Railway subir a aplicação web direto com Laravel.

### 1. Suba o projeto para o GitHub

Dentro da pasta do projeto:

```powershell
git add .
git commit -m "Preparar deploy na Railway"
git branch -M main
git remote add origin SEU_REPOSITORIO_GIT
git push -u origin main
```

Se o remoto já existir, pule a linha do `git remote add origin`.

### 2. Crie o projeto na Railway

1. Acesse `https://railway.app`
2. Faça login
3. Clique em `New Project`
4. Escolha `Deploy from GitHub repo`
5. Selecione este repositório

### 3. Configure as variáveis de ambiente

Na Railway, abra o projeto e adicione estas variáveis:

```env
APP_NAME="Jogo da Forca"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=
APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR
APP_FAKER_LOCALE=pt_BR
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
LOG_LEVEL=warning
```

Use o arquivo `.env.railway.example` como referência.

### 4. Gere a APP_KEY

No terminal local, dentro do projeto:

```powershell
php artisan key:generate --show
```

Copie o valor gerado e cole na variável `APP_KEY` da Railway.

### 5. Ajuste a URL pública

Depois que a Railway gerar o domínio do app:

1. Copie a URL pública
2. Cole essa URL na variável `APP_URL`
3. Faça um redeploy

## Observações importantes

- Esse jogo foi preparado para produção usando arquivo para sessão e cache, então ele não depende de banco para funcionar.
- Como a sessão fica no servidor, cada navegador mantém sua própria rodada.
- Se você quiser que várias pessoas compartilhem exatamente a mesma partida ao mesmo tempo, o ideal é evoluir o jogo para salvar o estado em banco.

## Rodando localmente

```powershell
composer install
php artisan serve
```
