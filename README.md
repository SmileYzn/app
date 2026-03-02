# Dashboard Administrativo

Esse é um framework administrativo robusto e escalável, desenvolvido com tecnologias modernas para gerenciamento eficiente de dados e usuários.

## 🛠️ Tecnologias Utilizadas

- **PHP 7.4+** - Backend server-side
- **Bootstrap 5** - Framework CSS responsivo
- **JavaScript (Vanilla)** - Interatividade frontend
- **MariaDB / MySQL** - Banco de dados relacional

## 📋 Funcionalidades Principais

- ✅ Sistema de Autenticação e Login
- ✅ Gerenciamento de Usuários
- ✅ Controle de Permissões e Acessos
- ✅ Módulos Configuráveis
- ✅ Unidades de Negócio
- ✅ Logs de Atividades
- ✅ Upload de Arquivos
- ✅ Redimensionamento de Imagens
- ✅ Suporte a CEP, CNPJ, Estados e Cidades
- ✅ Sistema de Paginação
- ✅ Recuperação de Senha
- ✅ Integração com Turnstile (CAPTCHA)

## 📁 Estrutura do Projeto

```
root/
├── class/                  # Classes PHP reutilizáveis
│   ├── Acesso.php         # Controle de acesso
│   ├── Usuario.php        # Gerenciamento de usuários
│   ├── Conexao.php        # Conexão com banco de dados
│   ├── Email.php          # Envio de e-mails
│   ├── Upload.php         # Manipulação de uploads
│   └── ...
├── configuracoes/         # Módulos de configuração
│   ├── usuario/           # Gerenciamento de usuários
│   ├── permissao/         # Controle de permissões
│   ├── modulo/            # Gerenciamento de módulos
│   ├── unidade/           # Unidades de negócio
│   ├── logs/              # Visualização de logs
│   └── ...
├── login/                 # Páginas de autenticação
│   ├── index.php          # Formulário de login
│   └── logout.php         # Logout
├── public/                # Arquivos públicos
│   ├── index.php         # Página pública
│   ├── email/            # Templates de e-mail
│   └── modal/            # Modais reutilizáveis
├── assets/                # Arquivos estáticos
│   ├── css/               # Estilos CSS
│   ├── js/                # Scripts JavaScript
│   └── img/               # Imagens
├── recuperar/             # Sistema de recuperação de senha
├── logs/                  # Visualização de logs
├── composer.json          # Dependências PHP
├── autoload.php           # Autoload de classes
├── index.php              # Página inicial
└── 404.php                # Página de erro 404
```

## 🚀 Como Começar

### Pré-requisitos

- PHP 7.4 ou superior
- MariaDB ou MySQL 5.7+
- Composer (obrigatório para gerenciamento de dependências)

### Instalação

1. **Clone ou copie o projeto**
   ```bash
   cd /home/eu/Downloads/app
   ```

2. **Instale as dependências do Composer**
   ```bash
   composer update
   composer install
   ```
   Este passo é obrigatório para baixar todas as dependências necessárias do projeto.

3. **Configure o banco de dados**
   - Importe o arquivo de estrutura: `public/sql/instalar.sql`
   - Importe os dados padrão: `public/sql/instalar_dados.sql`
   
   **⚠️ Importante:** Após importar o `instalar_dados.sql`, você deve configurar os seguintes campos na tabela `configuracao`:
   - `SG_URL_BACKEND` - URL do painel administrativo
   - `SG_URL_FRONTEND` - URL do site frontend
   - `SG_PATH` - Caminho absoluto da aplicação no servidor
   - `SG_PATH_PUBLIC` - Caminho da pasta pública
   - `SG_SESSAO_DOMINIO` - Domínio principal de sessão
   - `SG_SMTP_HOST` - Host do servidor de email (SMTP)
   - `SG_SMTP_PORT` - Porta SMTP (padrão 587)
   - `SG_SMTP_USER` - Usuário SMTP
   - `SG_SMTP_PASS` - Senha SMTP
   - `SG_SMTP_NAME` - Nome do remetente de email
   - `SG_CAPTCHA_SITE_KEY` - Cloudflare Turnstile Site Key
   - `SG_CAPTCHA_SECRET_KEY` - Cloudflare Turnstile Secret Key
   - `SG_SUPORTE_URL` - URL de suporte da aplicação

4. **Configure as credenciais do banco**
   - Edite a classe `Conexao.php` com suas credenciais

5. **Defina as permissões**
   ```bash
   chmod 755 /path/to/app
   chmod 644 /path/to/app/*.php
   ```

6. **Acesse a aplicação**
   ```
   http://localhost/path/to/app
   ```

## 🔐 Segurança

- Validação de entrada de dados
- Proteção contra SQL Injection
- Autenticação segura com sessões
- Integração com Turnstile (CAPTCHA)
- Sistema de logs para auditoria
- Controle granular de permissões

## 📝 Modelos de Dados

O sistema gerencia os seguintes entidades principais:

- **Usuários** - Cadastro e autenticação de usuários
- **Permissões** - Controle de acesso por funcionalidade
- **Áreas** - Divisões organizacionais
- **Módulos** - Funcionalidades do sistema
- **Unidades** - Unidades de negócio
- **Estados/Cidades** - Dados geográficos
- **Documentos** - CNPJ e tipos de documento
- **Regime Tributário** - Configurações fiscais

## 📄 Licença

Este projeto é proprietário. Todos os direitos reservados.

## 👨‍💻 Suporte

Para reportar problemas ou sugestões, entre em contato com a equipe de desenvolvimento.

---

**Última atualização:** Março de 2026
