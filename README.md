# Sistema de Gestão da Semana de Tecnologia

Projeto colaborativo desenvolvido na disciplina de **Programação Web** do curso de **Análise e Desenvolvimento de Sistemas da Fatec Presidente Prudente**.

A aplicação será desenvolvida em **Laravel** e terá como objetivo apoiar a gestão da Semana de Tecnologia, integrando os módulos implementados pelas equipes da turma.

> Este repositório contém apenas a estrutura inicial compartilhada. As entidades, regras de negócio, migrations, controllers e funcionalidades específicas serão adicionadas pelas equipes conforme a modelagem e as definições coletivas do projeto.

## Tecnologias e requisitos

- PHP 8.2 ou superior
- Composer
- MySQL ou MariaDB
- Laravel 12
- Git
- Node.js e npm apenas quando for necessário trabalhar com os assets do Vite

## Estrutura adotada

O projeto segue a organização MVC padrão do Laravel trabalhada em aula.

```text
app/
├── Http/Controllers/     Controllers da aplicação
├── Models/               Models Eloquent
└── Providers/

database/
├── factories/
├── migrations/           Estrutura versionada do banco de dados
└── seeders/

resources/
├── css/
├── js/
└── views/                Views Blade

routes/
├── console.php
└── web.php               Rotas das páginas web
```

A estrutura inicial possui apenas um exemplo mínimo de composição de views Blade:

```text
resources/views/layouts/app.blade.php
resources/views/home.blade.php
```

As novas telas devem seguir o mesmo princípio de organização, evitando colocar regras de negócio diretamente nas views.

---

# Configuração do projeto

## 1. Clonar o repositório

No GitHub, abra o repositório, clique em **Code**, copie a URL HTTPS e execute:

```bash
git clone <URL_DO_REPOSITORIO>
```

Entre na pasta clonada:

```bash
cd <NOME_DA_PASTA_DO_REPOSITORIO>
```

## 2. Instalar as dependências PHP

```bash
composer install
```

## 3. Criar o arquivo de ambiente

No Windows (Prompt de Comando):

```bat
copy .env.example .env
```

No PowerShell, Linux ou macOS:

```bash
cp .env.example .env
```

Depois gere a chave da aplicação:

```bash
php artisan key:generate
```

## 4. Configurar o banco de dados

Crie no MySQL/MariaDB um banco chamado:

```text
semana_tecnologia
```

O `.env.example` já utiliza esta configuração como base:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=semana_tecnologia
DB_USERNAME=root
DB_PASSWORD=
```

Cada integrante deve ajustar `DB_USERNAME` e `DB_PASSWORD` no seu próprio `.env`, conforme o ambiente local.

**Nunca envie o arquivo `.env` para o GitHub.**

## 5. Executar as migrations

```bash
php artisan migrate
```

## 6. Iniciar a aplicação

```bash
php artisan serve
```

Acesse no navegador:

```text
http://127.0.0.1:8000
```

### Assets front-end

O projeto mantém a estrutura padrão do Vite do Laravel. Quando houver necessidade de trabalhar com CSS/JavaScript processado pelo Vite:

```bash
npm install
npm run dev
```

---

# Fluxo de branches da turma

O desenvolvimento seguirá o fluxo:

```text
branch pessoal
      ↓ Pull Request
branch da equipe
      ↓ Pull Request
    develop
      ↓ Pull Request
      main
```

Branches compartilhadas do projeto:

```text
main
develop
equipe-1
equipe-2
equipe-3
equipe-4
equipe-5
equipe-6
```

A `main` representa a versão estável do projeto.

A `develop` concentra a integração dos módulos antes de uma versão chegar à `main`.

Cada `equipe-X` concentra o código já revisado pelos integrantes daquela equipe.

## Criando sua branch pessoal

**A branch pessoal deve ser criada a partir da branch da sua própria equipe.**

Exemplo para um integrante da Equipe 1.

Primeiro atualize as referências do repositório:

```bash
git fetch origin
```

Entre na branch da sua equipe:

```bash
git switch equipe-1
```

Atualize a branch local:

```bash
git pull origin equipe-1
```

Agora crie sua branch pessoal:

```bash
git switch -c equipe-1/seu-nome
```

Exemplo:

```bash
git switch -c equipe-1/joao-silva
```

Publique a branch no GitHub:

```bash
git push -u origin equipe-1/joao-silva
```

Para integrantes das outras equipes, altere apenas o número e o nome:

```text
equipe-2/maria-souza
equipe-3/pedro-santos
equipe-4/ana-silva
equipe-5/carlos-lima
equipe-6/lucas-oliveira
```

### Regra importante

Não crie sua branch pessoal diretamente a partir de `main` ou `develop`.

```text
Correto:

equipe-1 → equipe-1/joao-silva

Incorreto:

main    → equipe-1/joao-silva
develop → equipe-1/joao-silva
```

---

# Trabalhando na sua branch

Antes de começar uma tarefa, confirme em qual branch você está:

```bash
git branch
```

Depois de alterar os arquivos:

```bash
git status
git add .
git commit -m "feat: descricao da alteracao"
git push
```

Sugestão de prefixos de commits:

```text
feat: nova funcionalidade
fix: correção de problema
refactor: reorganização de código sem mudar comportamento
test: criação ou alteração de testes
docs: alteração de documentação
chore: configuração ou manutenção do projeto
```

Faça commits pequenos e relacionados a uma única alteração sempre que possível.

---

# Atualizando sua branch com mudanças da equipe

Se outros integrantes já tiveram alterações integradas na branch da equipe, atualize sua branch antes de continuar trabalhando.

Exemplo para a Equipe 1:

```bash
git fetch origin
git switch equipe-1/joao-silva
git merge origin/equipe-1
```

Se houver conflitos, resolva-os localmente, teste o projeto e só depois faça o commit e o push.

---

# Pull Requests

## Branch pessoal → branch da equipe

Quando sua tarefa estiver pronta, envie sua branch:

```bash
git push
```

No GitHub, abra um Pull Request usando:

```text
base:    equipe-X
compare: equipe-X/seu-nome
```

Exemplo:

```text
base:    equipe-1
compare: equipe-1/joao-silva
```

Descreva o que foi desenvolvido e solicite manualmente um **review** conforme a organização definida pela turma.

Não abra Pull Request da branch pessoal diretamente para `develop` ou `main`.

## Branch da equipe → develop

Quando um conjunto de funcionalidades do módulo estiver revisado e integrado na branch da equipe:

```text
equipe-X → develop
```

Esse Pull Request representa uma integração entre módulos e deve ser acompanhado pela **Equipe 6**.

## develop → main

A entrada na `main` ocorre somente por Pull Request:

```text
develop → main
```

A `main` deve permanecer como a versão estável do sistema.

---

# Antes de abrir um Pull Request

Confirme que:

- o projeto inicia sem erros;
- as migrations executam corretamente;
- você não enviou `.env`, senhas ou dados locais;
- sua branch está atualizada com a branch da equipe;
- não existem arquivos temporários ou desnecessários no commit;
- as alterações pertencem à responsabilidade da sua equipe;
- o código segue a organização padrão do Laravel usada no projeto.

---

# Padrão de organização Laravel

Ao criar novas funcionalidades, mantenha as convenções do framework.

Exemplo para uma entidade `Palestra`:

```text
Model
app/Models/Palestra.php

Controller
app/Http/Controllers/PalestraController.php

Views
resources/views/palestras/

Migration
database/migrations/..._create_palestras_table.php

Rotas
routes/web.php
```

Convenções gerais:

```text
Models       → singular e PascalCase
               Palestra, Participante, Inscricao

Controllers  → NomeController
               PalestraController

Views/URLs   → plural e minúsculo
               palestras, participantes, inscricoes

Tabelas      → plural e snake_case
               palestras, participantes, inscricoes
```

Antes de criar tabelas, relacionamentos ou estruturas compartilhadas que afetem outras equipes, alinhe a alteração para evitar migrations e contratos incompatíveis entre os módulos.

---

# Comandos úteis

```bash
# Ver branch atual
git branch

# Ver alterações locais
git status

# Atualizar referências remotas
git fetch origin

# Listar rotas Laravel
php artisan route:list

# Executar migrations
php artisan migrate

# Desfazer o último lote de migrations
php artisan migrate:rollback

# Executar testes
php artisan test

# Limpar caches de desenvolvimento
php artisan optimize:clear
```

## Licença

Este projeto utiliza a licença MIT.
