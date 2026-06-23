# Dashboard Administrativo

Um framework administrativo moderno, robusto e escalável para gerenciamento eficiente de dados, usuários e permissões. Ideal para desenvolvimento ágil de painéis administrativos com funcionalidades já pré-configuradas.

## 🛠️ Tecnologias Utilizadas

- **PHP 8.3+** - Backend server-side
- **Bootstrap 5** - Framework CSS responsivo  
- **JavaScript (Vanilla)** - Interatividade frontend sem dependências
- **MariaDB / MySQL** - Banco de dados relacional
- **PHPMailer** - Envio robusto de e-mails
- **Cloudflare Turnstile** - CAPTCHA moderno e seguro

## 📋 Funcionalidades Principais

**Autenticação e Segurança**
- ✅ Sistema de Autenticação robusta com sessões seguras
- ✅ Recuperação de senha por e-mail
- ✅ Integração com Turnstile (CAPTCHA)
- ✅ Proteção contra SQL Injection e XSS

**Gerenciamento de Usuários e Permissões**
- ✅ Cadastro e gerenciamento completo de usuários
- ✅ Sistema granular de permissões por módulo
- ✅ Controle de áreas e acessos
- ✅ Rastreamento de atividades com logs

**Configurações e Dados**
- ✅ Módulos configuráveis e extensíveis
- ✅ Unidades de negócio com suporte multi-unidade
- ✅ Gerenciamento de Estados, Cidades e Países
- ✅ Documentos (CNPJ, CPF)
- ✅ Regime Tributário configurável

**Recursos de Arquivo e Imagem**
- ✅ Upload seguro de arquivos
- ✅ Redimensionamento automático de imagens
- ✅ Validação de tipos MIME

**Ferramentas Auxiliares**
- ✅ Sistema de Paginação eficiente
- ✅ Validação de CEP, CNPJ, CPF
- ✅ Envio de e-mails via SMTP
- ✅ Sistema de logs para auditoria completa

## 📁 Estrutura do Projeto

```
app/
├── class/                    # Classes PHP reutilizáveis (principal lógica de negócio)
│   ├── Usuario.php          # Gerenciamento completo de usuários
│   ├── Login.php            # Autenticação de usuários
│   ├── Permissao.php        # Controle de permissões e acessos
│   ├── Acesso.php           # Validação de acesso a funcionalidades
│   ├── Conexao.php          # Conexão e gerenciamento de BD
│   ├── Email.php            # Envio de e-mails via PHPMailer
│   ├── Upload.php           # Upload seguro de arquivos
│   ├── ImageResize.php      # Redimensionamento de imagens
│   ├── Sessao.php           # Gerenciamento de sessões
│   ├── Paginacao.php        # Sistema de paginação
│   ├── Logs.php             # Registro de atividades
│   ├── Backend.php          # Utilitários backend
│   ├── Area.php             # Gerenciamento de áreas
│   ├── Estado.php           # Estados do Brasil
│   ├── Cidade.php           # Cidades brasileiras
│   ├── Documento.php        # Tipos de documento (CNPJ, CPF)
│   ├── RegimeTributario.php # Configurações fiscais
│   ├── Modulo.php           # Módulos do sistema
│   ├── Unidade.php          # Unidades de negócio
│   ├── Turnstile.php        # Integração com CAPTCHA
│   └── ...
├── configuracoes/           # Módulos de configuração (painéis administrativos)
│   ├── usuario/             # Gerenciar usuários
│   ├── permissao/           # Gerenciar permissões
│   ├── modulo/              # Gerenciar módulos
│   ├── unidade/             # Gerenciar unidades
│   ├── area/                # Gerenciar áreas
│   ├── logs/                # Visualizar logs
│   └── ...
├── login/                   # Sistema de autenticação
│   ├── index.php            # Formulário de login
│   └── logout.php           # Logout e destruição de sessão
├── recuperar/               # Recuperação de senha
│   └── index.php            # Formulário de recuperação
├── public/                  # Arquivos públicos
│   ├── index.php            # Página pública/dashboard
│   ├── email/               # Templates HTML de e-mail
│   ├── modal/               # Modais reutilizáveis
│   ├── sql/                 # Scripts de instalação do BD
│   └── unidade/             # Dados de unidades
├── assets/                  # Arquivos estáticos
│   ├── css/                 # Estilos CSS (Bootstrap)
│   ├── js/                  # Scripts JavaScript
│   │   ├── app.js           # Aplicação principal
│   │   ├── cep.js           # Validação de CEP
│   │   ├── cnpj.js          # Validação de CNPJ
│   │   ├── select.js        # Controles de seleção
│   │   └── ...
│   └── img/                 # Imagens e ícones
├── logs/                    # Visualização de logs
├── vendor/                  # Dependências (Composer)
├── composer.json            # Configuração de dependências
├── autoload.php             # Autoload de classes PHP
├── db.php                   # Configuração do banco de dados
├── index.php                # Ponto de entrada principal
├── 404.php                  # Página de erro 404
└── README.md                # Este arquivo
```

### 📌 Classes Principais

| Classe | Descrição |
|--------|-----------|
| `Usuario` | CRUD completo de usuários, atribuição de permissões |
| `Login` | Autenticação, validação de credenciais |
| `Permissao` | Gerenciar permissões, verificar acesso |
| `Acesso` | Middleware de validação de acesso |
| `Conexao` | Pool de conexões com banco de dados |
| `Email` | Envio de e-mails com PHPMailer |
| `Upload` | Validação e upload de arquivos |
| `Sessao` | Gerenciamento seguro de sessões |

## 🚀 Como Começar

### Pré-requisitos

- **PHP 8.3 ou superior**
- **MariaDB 10.5+** ou **MySQL 5.7+**
- **Composer** (gerenciador de dependências PHP)
- **Servidor Web** (Apache com mod_rewrite ou nginx)
- **curl** (para validações de CAPTCHA)

### Instalação Rápida

#### 1. Clone ou copie o projeto
```bash
cd /seu/diretorio/do/servidor
# ou se já está no diretório
cd /home/eu/Documentos/app
```

#### 2. Instale as dependências
```bash
composer install
```

Este comando instala PHPMailer e outras dependências necessárias.

#### 3. Configure o banco de dados

**Opção A: Linha de comando**
```bash
mysql -u root -p sua_base_de_dados < public/sql/instalar.sql
mysql -u root -p sua_base_de_dados < public/sql/instalar_dados.sql
```

**Opção B: phpMyAdmin**
- Crie um novo banco de dados
- Importe `public/sql/instalar.sql` (estrutura)
- Importe `public/sql/instalar_dados.sql` (dados padrão)

#### 4. Configure as credenciais do banco

Edite [db.php](db.php) com suas credenciais:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');
define('DB_NAME', 'sua_base_dados');
```

Ou edite a classe [class/Conexao.php](class/Conexao.php) diretamente.

#### 5. Defina permissões de arquivo
```bash
chmod -R 755 /path/to/app
chmod -R 644 /path/to/app/*.php
chmod -R 777 /path/to/app/assets  # Pasta para uploads
```

#### 6. Configure os parâmetros essenciais

Acesse o painel e configure na seção **Configurações** os seguintes campos:

| Campo | Descrição | Exemplo |
|-------|-----------|---------|
| `SG_URL_BACKEND` | URL do painel administrativo | `https://seu-dominio.com/admin` |
| `SG_URL_FRONTEND` | URL do site público | `https://seu-dominio.com` |
| `SG_PATH` | Caminho absoluto da aplicação | `/var/www/app` |
| `SG_PATH_PUBLIC` | Caminho da pasta pública | `/var/www/app/public` |
| `SG_SESSAO_DOMINIO` | Domínio para cookies | `seu-dominio.com` |
| `SG_SMTP_HOST` | Host SMTP | `smtp.seuservidor.com` |
| `SG_SMTP_PORT` | Porta SMTP | `587` (TLS) ou `465` (SSL) |
| `SG_SMTP_USER` | Usuário SMTP | `seu-email@dominio.com` |
| `SG_SMTP_PASS` | Senha SMTP | `sua_senha_segura` |
| `SG_SMTP_NAME` | Nome do remetente | `Minha Empresa` |
| `SG_CAPTCHA_SITE_KEY` | Cloudflare Turnstile Site Key | `[obter em turnstile.com]` |
| `SG_CAPTCHA_SECRET_KEY` | Cloudflare Turnstile Secret | `[obter em turnstile.com]` |
| `SG_SUPORTE_URL` | URL de suporte | `https://suporte.seu-dominio.com` |

#### 7. Acesse a aplicação
```
http://seu-servidor/path/to/app/login
```

**Credenciais padrão** (após importar instalar_dados.sql):
- **Usuário:** `admin`
- **Senha:** `admin` (⚠️ **Altere imediatamente em produção**)

## 🔐 Segurança

Implementações de segurança incluídas:

- ✅ **SQL Injection Prevention** - Consultas preparadas (prepared statements)
- ✅ **XSS Protection** - Sanitização de entrada/saída de dados
- ✅ **CSRF Tokens** - Proteção contra falsificação de requisições
- ✅ **Autenticação Segura** - Hash bcrypt para senhas
- ✅ **Sessões Seguras** - Validação de sessão e timeout
- ✅ **CAPTCHA Moderno** - Cloudflare Turnstile
- ✅ **Logs de Auditoria** - Rastreamento completo de ações
- ✅ **Controle de Permissões** - Granular por módulo
- ✅ **Rate Limiting** - Proteção contra brute force
- ✅ **HTTPS Ready** - Compatível com SSL/TLS

### Recomendações de Segurança para Produção

1. **Altere credenciais padrão imediatamente**
   - Usuário `admin` deve ter senha forte
   - Altere todas as chaves de API e secrets

2. **Configure HTTPS**
   - Ative SSL/TLS no servidor
   - Redirecione HTTP para HTTPS

3. **Mantenha PHP e dependências atualizados**
   ```bash
   composer update
   ```

4. **Configure variáveis de ambiente**
   - Use `.env` para dados sensíveis (recomendado)
   - Nunca commite credenciais no Git

5. **Habilite firewalls**
   - Configure WAF (Web Application Firewall)
   - Restrinja acesso ao `/admin` por IP

6. **Monitore logs regularmente**
   - Revise logs de erro e atividade
   - Configure alertas para anomalias

## � Modelo de Dados

O sistema gerencia os seguintes entidades principais:

### Estrutura de Dados
- **Usuários** - Cadastro, autenticação e gerenciamento de usuários do sistema
- **Permissões** - Controle granular de acesso por funcionalidade/módulo
- **Áreas** - Divisões organizacionais (Financeiro, RH, Vendas, etc.)
- **Módulos** - Funcionalidades/features disponíveis no sistema
- **Unidades** - Unidades de negócio (filiais, departamentos, setores)
- **Logs** - Rastreamento de todas as ações do sistema
- **Configurações** - Parâmetros globais da aplicação

### Dados de Referência
- **Estados** - Estados brasileiros
- **Cidades** - Cidades por estado
- **Países** - Lista de países
- **Documentos** - Tipos (CNPJ, CPF)
- **Regime Tributário** - Enquadramentos fiscais
- **Situação** - Status genéricos do sistema

### Relacionamentos Principais
```
Usuários ← → Permissões
    ↓
 Áreas → Módulos
    ↓
Unidades
```

## � Desenvolvimento e Customização

### Estrutura de Pastas para Novos Módulos

Para adicionar um novo módulo/funcionalidade:

```bash
# Criar classe de modelo
touch class/MeuModelo.php

# Criar páginas no admin
mkdir -p configuracoes/meu-modulo
touch configuracoes/meu-modulo/index.php
touch configuracoes/meu-modulo/adicionar.php
touch configuracoes/meu-modulo/editar.php
```

### Exemplo: Criar Nova Classe
```php
<?php
namespace App;

class MeuModelo extends Base {
    public function __construct() {
        parent::__construct();
        $this->tabela = 'meu_modulo';
    }
    
    public function listar($pagina = 1, $limite = 10) {
        // Sua lógica aqui
    }
}
```

### Boas Práticas

- Sempre use prepared statements para queries
- Valide entrada de dados com `Backend::validar()`
- Use sessões com `Sessao::obter()`
- Registre ações importantes em `Logs::registrar()`
- Siga PSR-1/PSR-12 para estilos de código

## 📚 Documentação Adicional

- [Guia de Permissões](./docs/permissoes.md) - Como configurar permissões
- [API de Classes](./docs/api.md) - Referência das classes PHP
- [Estrutura de BD](./public/sql/instalar.sql) - Schema do banco
- [Endpoints Disponíveis](./docs/endpoints.md) - API REST endpoints

## ❓ Perguntas Frequentes

**P: Como recuperar senha de um usuário?**
- O usuário pode usar a opção "Esqueci minha senha" na tela de login
- Alternativa: Admin pode resetar senha na seção de Usuários

**P: Como adicionar uma nova permissão?**
- Vá para Configurações → Permissões
- Clique em "Adicionar Nova Permissão"
- Associe a permissão aos usuários desejados

**P: Como fazer backup do banco de dados?**
```bash
mysqldump -u root -p sua_base_dados > backup.sql
```

**P: Posso usar em produção?**
- Sim! Mas siga as [recomendações de segurança](#recomendações-de-segurança-para-produção)
- Configure corretamente SMTP, CAPTCHA e credenciais

**P: Como fazer deploy?**
- Configure variáveis de ambiente (DB, SMTP, CAPTCHA)
- Execute `composer install --no-dev`
- Defina permissões corretas de arquivo
- Configure Turnstile e certificado SSL

## 📄 Licença

Este projeto é **proprietário**. Todos os direitos reservados.

Uso não autorizado, cópia ou redistribuição é proibido sem permissão expressa do proprietário.

## 👨‍💻 Suporte e Contribuição

### Relatar Problemas

Para reportar bugs ou sugestões:
- Entre em contato com a equipe de desenvolvimento
- Descreva o problema detalhadamente
- Inclua passos para reproduzir
- Anexe logs se relevante

### Contribuições

Contribuições são bem-vindas! Para colaborar:
1. Faça fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-funcionalidade`)
3. Commit suas mudanças (`git commit -am 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

## 🛠️ Stack Técnico Completo

| Camada | Tecnologia | Versão |
|--------|-----------|--------|
| **Backend** | PHP | 8.3+ |
| **Frontend** | Bootstrap | 5.x |
| **JavaScript** | Vanilla JS | ES6+ |
| **Banco de Dados** | MySQL/MariaDB | 5.7+/10.5+ |
| **Email** | PHPMailer | 6.x |
| **CAPTCHA** | Cloudflare Turnstile | Latest |
| **Servidor** | Apache/Nginx | Latest |

## 📈 Roadmap Futuro

- [ ] API REST completa com JWT
- [ ] Dashboard com gráficos e relatórios
- [ ] Integração com OAuth2
- [ ] Importação/Exportação de dados
- [ ] Webhooks API
- [ ] Sistema de templates de email
- [ ] Validação em tempo real (WebSocket)

## 📞 Contato

- **Email:** contato@seu-dominio.com
- **Suporte:** https://suporte.seu-dominio.com
- **Website:** https://seu-dominio.com

---

**Dashboard Administrativo** | **Última atualização:** Junho de 2026 | **Versão:** 1.0.0
