\# Módulo de Certificados - Equipe 5



\## Funcionalidades



\- Cálculo da carga horária com base nas presenças confirmadas.

\- Emissão de certificados por inscrição.

\- Registro dos certificados no banco de dados.

\- Geração de código UUID para validação.

\- Geração e download de documentos em PDF.

\- Histórico de certificados por participante.

\- Controle de acesso aos certificados individuais.

\- Prevenção de emissões duplicadas.



\## Dependências



O módulo foi desenvolvido utilizando Laravel 12, PHP 8.2,

MySQL e a biblioteca barryvdh/laravel-dompdf.



A implementação utiliza a mesma estrutura de banco de dados

adotada no módulo de relatórios e validação da Equipe 5.



\## Tabelas utilizadas



\- users

\- edicoes

\- atividades

\- inscricoes

\- inscricao\_atividade

\- presencas

\- certificados



\## Regras de emissão



O participante deve possuir uma inscrição confirmada e

carga horária válida, calculada a partir das atividades

com inscrição e presença confirmadas.



Cada inscrição pode possuir apenas um certificado.

O participante pode emitir e baixar somente certificados

relacionados às suas próprias inscrições.



\## Rotas principais



GET /meus-certificados



Exibe o histórico e as inscrições disponíveis para emissão.



POST /meus-certificados/{inscricao}/emitir



Realiza a emissão do certificado.



GET /meus-certificados/{certificado}/pdf



Realiza o download do certificado emitido.



GET /validar-certificado?codigo=UUID



Permite consultar a autenticidade do certificado por meio

do módulo de validação desenvolvido pela Lívia.



As rotas de emissão, histórico e download exigem autenticação.



\## Ambiente de demonstração



As rotas abaixo são destinadas exclusivamente ao ambiente

local de desenvolvimento.



\- /certificados

\- /certificados/modelo

\- /certificados/pdf



O CertificadosTesteSeeder disponibiliza dados fictícios

para demonstração e testes do módulo.



Para cadastrar esses dados em um banco local de desenvolvimento:



php artisan db:seed --class=CertificadosTesteSeeder



\## Testes automatizados



Os testes verificam o cálculo de carga horária, a emissão,

a prevenção de duplicidades, o controle de acesso, o download

e a integração com a validação de certificados.



Para executar:



php artisan test --filter="CalculadoraCargaHorariaTest|HistoricoCertificadoTest|EmissaoCertificadoTest|FluxoCertificadoTest"



\## Considerações sobre a integração



O desenvolvimento foi realizado a partir da implementação

de relatórios e validação da Equipe 5, preservando sua

estrutura de banco de dados e suas funcionalidades.



A emissão e o histórico utilizam o usuário autenticado,

sem depender de um participante fixo.



A integração entre emissão, download e validação foi

verificada por testes automatizados com diferentes

participantes, edições e cargas horárias.



A autenticação foi simulada nos testes, pois o fluxo de

login das demais equipes ainda não foi integrado nesta branch.



Durante o desenvolvimento, foram identificadas diferenças

entre as versões do Laravel e as migrations utilizadas

pelas equipes.



A compatibilização dessas estruturas e a integração com

o login real permanecem pendentes na integração geral

do projeto.



O teste padrão da rota inicial também permanece pendente,

pois esta branch não possui uma página inicial registrada.



