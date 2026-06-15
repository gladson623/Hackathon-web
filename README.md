# Portal de Estágios UniALFA - Front-end Web

Aplicação web do Portal de Estágios da UniALFA, desenvolvida em PHP Orientado a Objetos para atender alunos e empresas por meio da integração com a API Node.js.

---

## Descrição do Problema e da Solução

O `Hackathon-web` é a camada web do Portal de Estágios UniALFA, desenvolvida em PHP com aplicação de conceitos de Programação Orientada a Objetos.

O sistema atende dois públicos:

- **Alunos**, que podem se cadastrar, consultar vagas, enviar candidaturas e acompanhar processos seletivos;
- **Empresas**, que podem se cadastrar, gerenciar suas próprias vagas e acompanhar os candidatos recebidos.

O projeto funciona como interface para a API RESTful desenvolvida em Node.js. Toda leitura ou alteração de dados é realizada por requisições HTTP com respostas JSON. O PHP **não acessa o banco MySQL diretamente**.

---

## Objetivos do Projeto

- aproximar alunos das oportunidades de estágio publicadas por empresas parceiras;
- oferecer uma jornada simples de candidatura e acompanhamento;
- permitir que empresas administrem vagas e candidatos em uma área restrita;
- manter a persistência e as regras de negócio centralizadas na API Node.js;
- oferecer uma interface visual coerente, legível e responsiva.

---

## Tecnologias e Ferramentas Utilizadas

| Tecnologia | Versão | Finalidade |
|---|---|---|
| PHP | 8+ | Controllers, models, sessões, roteamento e integração HTTP |
| Programação Orientada a Objetos | - | Encapsulamento dos models, herança de controllers e separação de responsabilidades |
| HTML | 5 | Estrutura semântica das páginas e formulários |
| CSS | 3 | Identidade visual, componentes, estados, tabelas e responsividade |
| JavaScript | ES6+ | Máscaras, notificações, perfil flutuante e interações de interface |
| Apache | 2.x | Servidor web e reescrita de URLs pelo `.htaccess` |
| XAMPP ou Laragon | - | Ambiente local para execução do PHP e Apache |
| API Node.js | - | Autenticação, vagas, candidaturas, empresas, alunos e notificações |
| JSON e HTTP | - | Formato e protocolo de integração com a API |
| Google Fonts | - | Fonte `Inter` utilizada pela interface |
| SweetAlert2 | - | Mensagens modais de sucesso, erro e aviso |
| Git / GitHub | - | Controle de versão e colaboração |

O projeto utiliza PHP e JavaScript puros, sem framework web ou dependências instaladas por Composer.

---

## Instruções para Instalação e Execução Local

### Pré-requisitos

- PHP 8 ou superior;
- Apache com `mod_rewrite` habilitado;
- `AllowOverride` habilitado para o uso do `.htaccess`;
- `allow_url_fopen` habilitado no PHP, pois o cliente HTTP utiliza `file_get_contents`;
- projeto `Hackathon-api` configurado e executando;
- MySQL disponível para a API Node.js.

### 1. Execução com XAMPP

Coloque o projeto dentro do diretório `htdocs`. No ambiente usado durante a validação, o caminho foi:

```text
C:\xampp\htdocs\nova\Hackathon-web
```

Inicie o **Apache** e o **MySQL** pelo painel do XAMPP.

Com essa estrutura, acesse:

```text
http://localhost/nova/Hackathon-web/
```

Se o projeto for colocado diretamente em `C:\xampp\htdocs\Hackathon-web`, a URL será:

```text
http://localhost/Hackathon-web/
```

### 2. Execução com Laragon

Coloque o projeto no diretório:

```text
C:\laragon\www\Hackathon-web
```

Inicie o servidor web pelo Laragon. A URL dependerá da configuração adotada:

```text
http://localhost/Hackathon-web/
```

ou:

```text
http://hackathon-web.test/
```

O domínio local pode variar conforme o nome da pasta e a configuração de virtual hosts do Laragon.

### 3. Iniciar a API Node.js

Em outro terminal, acesse o projeto da API:

```powershell
cd C:\xampp\htdocs\nova\Hackathon-api
npm install
npm run migration:run
npm run dev
```

Para carregar os dados de demonstração em um banco preparado para testes:

```powershell
npm run seed
```

> O seed é opcional e repopula tabelas de negócio. Não o execute sobre um banco com dados que precisam ser preservados.

A API local deve responder em:

```text
http://localhost:3000
```

Teste antes de abrir o front:

```text
http://localhost:3000/health
```

### 4. Configurar a URL da API

A URL prevista para configuração está em:

```text
config/app.php
```

Valor local:

```php
'api_url' => 'http://localhost:3000/api',
```

O cliente HTTP também possui o mesmo endereço como fallback em:

```text
core/ApiClient.php
```

---

## Estrutura do Projeto

```text
Hackathon-web/
|-- app/
|   |-- Controllers/
|   |-- Models/
|   `-- Views/
|       |-- admin/
|       |-- aluno/
|       |-- auth/
|       |-- empresa/
|       |-- errors/
|       `-- layouts/
|-- config/
|   `-- app.php
|-- core/
|   |-- ApiClient.php
|   |-- Controller.php
|   |-- Router.php
|   `-- helpers.php
|-- public/
|   |-- assets/
|   |-- css/
|   `-- js/
|-- .htaccess
`-- index.php
```

### `app/Controllers`

Coordena os fluxos da aplicação, valida a sessão, recebe dados dos formulários, chama os models e escolhe a view que será apresentada.

Principais controllers:

- `AuthController`: home, cadastro, login e logout;
- `AlunoController`: vagas, candidatura, acompanhamento, notificações, currículo e perfil;
- `EmpresaController`: estados da empresa, dashboard, vagas, candidatos e status;
- `AdminController`: autenticação e usuários institucionais.

### `app/Models`

Encapsula as operações de cada entidade e sua comunicação com a API:

- `Aluno`;
- `Empresa`;
- `Vaga`;
- `Candidatura`;
- `Notificacao`;
- `Usuario`.

As classes obrigatórias definidas no documento do Hackathon, `Aluno`, `Empresa`, `Vaga` e `Candidatura`, estão presentes.

### `app/Views`

Contém os templates PHP/HTML separados por área:

- autenticação e cadastro;
- portal do aluno;
- painel da empresa;
- administração;
- páginas de erro;
- componentes de layout.

### `core`

Contém a infraestrutura compartilhada:

- `ApiClient`: requisições HTTP para a API;
- `Controller`: renderização, redirecionamento, sessão e autorização;
- `Router`: mapeamento das URLs;
- `helpers`: URLs, sanitização, formatação e componentes auxiliares.

### `public`

Reúne arquivos públicos:

- folha de estilo principal;
- JavaScript de interface;
- imagens;
- ícones e badges em SVG.

### `index.php`

É o front controller da aplicação. Inicializa os recursos e registra as rotas públicas, do aluno, da empresa e da administração.

---

## Integração com a API Node.js

O projeto não utiliza `PDO`, `mysqli` ou instruções SQL. A comunicação ocorre exclusivamente por HTTP por meio da classe:

```text
core/ApiClient.php
```

O cliente oferece métodos para:

```php
get()
post()
put()
patch()
delete()
```

Quando existe uma sessão autenticada, o token recebido no login é enviado no cabeçalho:

```http
Authorization: Bearer TOKEN
```

### Principais integrações

| Recurso | Endpoints utilizados |
|---|---|
| Autenticação | `POST /api/login` |
| Alunos | `POST /api/alunos`, `GET/PUT /api/alunos/:id` |
| Empresas | `POST /api/empresas`, `GET /api/empresas/:id` |
| Vagas públicas | `GET /api/vagas/ativas`, `GET /api/vagas/:id` |
| Vagas da empresa | `GET /api/vagas/empresa/:id`, `POST/PUT/DELETE /api/vagas` |
| Candidaturas | `POST /api/candidaturas`, `GET/PUT /api/candidaturas` |
| Notificações | `GET /api/notificacoes`, `PATCH /api/notificacoes/:id/lida` |
| Usuários institucionais | Endpoints em `/api/usuarios` |

O banco de dados, as migrations, os seeds e as regras centrais pertencem ao projeto `Hackathon-api`.

---

## Funcionalidades Implementadas

### Portal do Aluno

#### Cadastro e login

- cadastro com nome, e-mail, telefone, curso, período e senha;
- confirmação de senha no cadastro do aluno;
- autenticação pela API;
- armazenamento do token e dos dados do aluno na sessão PHP;
- mensagem de erro para credenciais inválidas;
- aviso quando o cadastro ainda não foi considerado apto pela instituição.

#### Listagem e detalhes das vagas

- consulta das vagas ativas pela API;
- apresentação em cards;
- exibição de título, empresa, área, modalidade, local e bolsa;
- página de detalhes;
- bloqueio visual de vagas indisponíveis.

#### Candidatura

O fluxo implementado segue esta jornada:

1. acesso ao portal;
2. consulta das vagas disponíveis;
3. abertura dos detalhes;
4. confirmação da candidatura;
5. envio à API;
6. tela de candidatura confirmada;
7. acompanhamento em `Minhas Candidaturas`.

A candidatura exige sessão de aluno e aptidão para estágio. A API também valida aptidão, estado da vaga, empresa e duplicidade.

#### Acompanhamento

- listagem das candidaturas do aluno;
- filtros para todas, em andamento e finalizadas;
- estados `Enviada`, `Em análise`, `Aprovada` e `Reprovada`;
- visualização das observações registradas pela empresa.

#### Notificações

- contador de notificações não lidas;
- dropdown no menu;
- aviso após o login;
- página com histórico;
- ação para marcar uma notificação como lida.

As notificações são fornecidas pela API quando uma candidatura é criada ou atualizada.

#### Perfil e currículo

- consulta e edição dos dados básicos do aluno;
- tela de currículo;
- visualização do currículo básico pela empresa;
- exibição da situação de aptidão para estágio.

### Painel da Empresa

#### Cadastro e login

- cadastro com nome, e-mail, CNPJ, telefone e senha;
- autenticação pela API;
- armazenamento do token em sessão;
- redirecionamento conforme o estado cadastral.

#### Estados da empresa

| Estado | Comportamento |
|---|---|
| `PENDENTE` | Exibe tela de cadastro em análise e bloqueia a gestão de vagas/candidatos |
| `APROVADA` | Libera dashboard, vagas e candidatos |
| `BLOQUEADA` | Exibe aviso institucional e bloqueia as operações restritas |

O status é consultado novamente na API durante a navegação para refletir alterações feitas pelo backoffice institucional.

#### Dashboard

Apresenta indicadores de:

- vagas cadastradas;
- vagas abertas;
- processos em andamento;
- candidatos recebidos.

Também oferece acessos rápidos para vagas, criação de nova vaga e candidatos.

#### CRUD de vagas

A empresa aprovada pode:

- cadastrar vagas;
- listar suas próprias vagas;
- editar dados e status;
- excluir vagas com confirmação;
- filtrar candidatos por vaga.

Os formulários incluem título, descrição, requisitos, bolsa, modalidade, área, local, carga horária, atividades e status.

#### Candidatos e currículo

- listagem dos alunos candidatos às vagas da empresa;
- filtro por vaga;
- visualização dos dados básicos do candidato;
- consulta de curso, período, contato e aptidão;
- acesso à candidatura e sua observação.

#### Atualização de status

A empresa pode alterar a candidatura para:

- `Em análise`;
- `Aprovada`;
- `Reprovada`.

Também pode registrar uma observação. A atualização é enviada à API, responsável por criar a notificação do aluno.

---

## Experiência do Usuário e Interface

### Identidade visual

A interface utiliza a marca **UniALFA Estágios**, acompanhada por um símbolo relacionado à formação acadêmica. A composição visual utiliza tons de azul, fundos claros, cartões brancos, bordas suaves e cantos arredondados.

As telas fornecidas como referência demonstram consistência entre o portal do aluno e o painel da empresa, com navegação superior, estado ativo destacado, ícones e componentes reutilizáveis.

### Tipografia

A fonte principal é:

```text
Inter: 400, 600, 700 e 800
```

O sistema utiliza títulos com maior peso, textos auxiliares em cinza azulado e botões com contraste visual.

### Paleta principal

As cores abaixo estão definidas como variáveis em `public/css/style.css`:

| Uso | Variável | Cor |
|---|---|---|
| Azul de destaque | `--azul` | `#16B9FF` |
| Azul escuro/texto | `--azul-escuro` | `#0B1F5E` |
| Azul dos botões | `--azul-botao` | `#0B82BD` |
| Texto secundário | `--cinza-azul` | `#597180` |
| Fundo principal | `--fundo` | `#EFEFEF` |
| Bordas | `--borda` | `#D9E1EC` |
| Superfícies | `--branco` | `#FFFFFF` |
| Sucesso | `--verde` | `#22C55E` |
| Erro | `--vermelho` | `#EF4444` |

### Organização das telas

- barra de navegação superior;
- menus específicos para aluno e empresa;
- destaque para a seção ativa;
- cards para vagas, etapas e métricas;
- tabelas para candidaturas, vagas e candidatos;
- formulários centralizados e agrupados;
- botões primários e secundários consistentes;
- estados vazios e páginas de erro.

### Mensagens de erro, aviso e sucesso

- alertas vermelhos para erro;
- alertas amarelos para avisos;
- alertas verdes para sucesso;
- modais SweetAlert2 após operações;
- tela dedicada para candidatura confirmada;
- mensagens para vaga indisponível, cadastro não apto e empresa pendente/bloqueada;
- badge, dropdown e toast para notificações.

### Experiência do aluno

A jornada prioriza a consulta rápida de vagas e reduz o fluxo de candidatura a poucas etapas. O aluno recebe confirmação visual e pode acompanhar o processo sem sair do portal.

### Experiência da empresa

O painel apresenta indicadores resumidos, atalhos para as principais ações e tabelas voltadas ao trabalho de recrutamento. A separação entre vagas, candidatos e atualização de status reduz a complexidade da navegação.

### Responsividade e acessibilidade

O CSS possui media queries e adaptações de layout para desktop, tablet e celular. A interface foi validada nas seguintes larguras:

| Largura | Dispositivo simulado | Resultado |
|---|---|---|
| 1440px | Desktop | Funcionando |
| 768px | Tablet | Funcionando |
| 390px | Celular | Funcionando |
| 320px | Celular compacto | Funcionando |

A interface também possui recursos básicos de acessibilidade:

- estilos de `focus-visible`;
- `role="alert"` e `role="status"` em mensagens;
- textos alternativos em imagens relevantes;
- regiões de tabela identificadas;
- contraste definido para textos e botões.

---

## Controle de Versão e Colaboração

O desenvolvimento foi versionado com **Git** e hospedado no **GitHub**, seguindo as boas práticas de DevOps previstas para o Hackathon.

- Repositório: <https://github.com/gladson623/Hackathon-web>

### Histórico de commits

As mensagens seguem o padrão **Conventional Commits**, evidenciando a evolução do projeto de forma organizada e legível:

| Prefixo | Uso no projeto |
|---|---|
| `chore` | Estrutura inicial do repositório |
| `feat` | Implementação de funcionalidades (estrutura MVC, cadastros, SweetAlert2) |
| `docs` | README e evidências visuais do front-end |

### Branches

O trabalho foi dividido em branches por funcionalidade, posteriormente integradas à `main`:

- `main` — branch estável e integrada;
- `chore/estrutura-inicial` — estrutura inicial do repositório;
- `feature/gustavo.francisco/estrutura-completa-mvc` — estrutura completa da aplicação MVC;
- `feature/gustavo.francisco/implementando-sweet-alert` — integração do SweetAlert2.

### Pull Requests

A integração de código ocorreu por meio de Pull Requests, permitindo revisão entre os membros da equipe antes do merge na `main`:

| PR | Branch de origem | Conteúdo |
|---|---|---|
| #1 | `chore/estrutura-inicial` | Estrutura inicial do projeto |
| #2 | `feature/gustavo.francisco/estrutura-completa-mvc` | Estrutura completa da aplicação MVC |
| #3 | `feature/gustavo.francisco/implementando-sweet-alert` | Integração do SweetAlert2 e ajustes nos cadastros de estagiário e empresa |

---

## Integrantes da Equipe e Contribuições

| Integrante | Identificação | Contribuições evidenciadas no Git |
|---|---|---|
| Gladson Coronado | [@gladson623](https://github.com/gladson623) | Estrutura inicial do repositório, integração dos Pull Requests, README e organização das evidências visuais |
| Gustavo Francisco | [@gustavofrancisc0](https://github.com/gustavofrancisc0) | Implementação da estrutura MVC, controllers, models, views, integração HTTP, interface, cadastros e SweetAlert2 |
| Thaina | Registro de autoria no Git | Revisão e integração da estrutura inicial por meio do Pull Request nº 1 |

---

## Evidências de Testes e Funcionalidades

### Evidências visuais da interface

As capturas abaixo registram telas implementadas no sistema e complementam as evidências textuais de UX/UI e funcionamento.

#### Login do aluno e tratamento de erro

<p align="center">
  <img src="docs/images/login-aluno-erro.png" alt="Tela de login do aluno exibindo mensagem para credenciais inválidas" width="700">
</p>

*Formulário de autenticação com hierarquia visual, campos identificados e retorno de erro.*

#### Confirmação da candidatura

<p align="center">
  <img src="docs/images/candidatura-confirmada.png" alt="Tela de confirmação da candidatura do aluno" width="700">
</p>

*Retorno visual apresentado após o envio da candidatura, com resumo da vaga e ações de continuidade.*

#### Notificações do aluno

<p align="center">
  <img src="docs/images/notificacoes-aluno.png" alt="Área do aluno exibindo notificações relacionadas às candidaturas" width="900">
</p>

*Notificações de candidatura e mudança de status integradas à navegação do aluno.*

#### Dashboard da empresa

<p align="center">
  <img src="docs/images/dashboard-empresa.png" alt="Dashboard da empresa com indicadores de vagas e candidatos" width="900">
</p>

*Painel com indicadores resumidos e atalhos para vagas e candidatos.*

#### Atualização do status da candidatura

<p align="center">
  <img src="docs/images/atualizar-status-candidatura.png" alt="Formulário empresarial para atualizar o status de uma candidatura" width="800">
</p>

*Fluxo empresarial para registrar o status, incluir observação e notificar o aluno.*

#### Confirmação da atualização de vaga

<p align="center">
  <img src="docs/images/vaga-atualizada-sucesso.png" alt="Mensagem de sucesso após atualização de uma vaga" width="900">
</p>

*Feedback modal apresentado após a atualização de uma vaga da empresa.*

### Testes manuais

Verificação realizada em ambiente local com Apache, PHP 8.3, API Node.js e MySQL.

| Fluxo | Resultado | Evidência |
|---|---|---|
| Página inicial | Funcionando | HTTP 200 e seleção dos portais |
| Login do aluno | Funcionando | Redirecionamento para `/portal` |
| Login inválido | Funcionando | Formulário exibe mensagem semântica para credenciais inválidas |
| Listagem de vagas | Funcionando | Cards carregados pela API |
| Detalhes da vaga | Funcionando | Rota respondeu HTTP 200 |
| Confirmação da candidatura | Funcionando | Tela de confirmação e referência visual fornecida |
| Envio da candidatura | Funcionando | Candidatura enviada à API e tela de confirmação exibida |
| Minhas candidaturas | Funcionando | Rota respondeu HTTP 200 com sessão de aluno |
| Notificações | Funcionando | Rota HTTP 200, contador, dropdown e histórico |
| Perfil do aluno | Funcionando | Consulta e atualização dos dados básicos confirmadas |
| Currículo | Funcionando | Dados básicos consultados e apresentados no portal |
| Login da empresa aprovada | Funcionando | Redirecionamento para `/empresa/dashboard` |
| Empresa pendente | Funcionando | Redirecionamento para `/empresa/aguardando-aprovacao` |
| Empresa bloqueada | Funcionando | Tela específica exibida e operações restritas bloqueadas |
| Dashboard da empresa | Funcionando | HTTP 200 e métricas exibidas |
| Formulário de nova vaga | Funcionando | Rota respondeu HTTP 200 |
| Cadastro de vaga | Funcionando | Vaga criada pela API a partir do formulário |
| Edição de vaga | Funcionando | Vaga atualizada pela API a partir do formulário |
| Exclusão de vaga | Funcionando | Vaga removida pela API após confirmação |
| Lista de candidatos | Funcionando | Rota respondeu HTTP 200 |
| Visualização de currículo | Funcionando | Dados básicos retornados pela API exibidos para a empresa |
| Atualização de status | Funcionando | Status alterado pela API e notificação gerada ao aluno |
| Responsividade | Funcionando | Validada em 1440px, 768px, 390px e 320px |
| Sintaxe PHP | Funcionando | 47 arquivos verificados com `php -l`, sem erros |

### Ambiente da evidência

| Item | Valor |
|---|---|
| Data da verificação | 15/06/2026 |
| Sistema operacional | Windows |
| Servidor web | Apache/XAMPP |
| PHP | 8.3.30 |
| API | `http://localhost:3000/api` |
| URL do front | `http://localhost/nova/Hackathon-web/` |

---

## Princípios de Design e Requisitos PHP

| Requisito | Evidência no projeto |
|---|---|
| Classes `Aluno`, `Empresa`, `Vaga` e `Candidatura` | `app/Models` |
| Painel restrito da empresa | `EmpresaController` e `app/Views/empresa` |
| CRUD das próprias vagas | Rotas e métodos de criação, listagem, edição e exclusão |
| Lista de candidatos por vaga | Tela de candidatos com filtro |
| Portal do aluno | Vagas, detalhes, candidatura e acompanhamento |
| Integração exclusivamente HTTP | `core/ApiClient.php` |
| Encapsulamento | Models encapsulam chamadas e normalização dos dados |
| Herança | Controllers herdam de `Controller` |
| Separação de responsabilidades | Divisão em controllers, models, views e core |
