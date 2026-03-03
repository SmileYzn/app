<?php
// Instalador web minimal
// Não carrega o autoload da aplicação para evitar consultas antes do DB existir

function escape_val($v)
{
    return str_replace("'", "\\'", $v);
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['install']))
{
    $host = $_POST['db_host'] ?? 'localhost';
    $port = $_POST['db_port'] ?? '3306';
    $db   = $_POST['db_name'] ?? '';
    $user = $_POST['db_user'] ?? '';
    $pass = $_POST['db_pass'] ?? '';

    if (empty($db) || empty($user))
    {
        $errors[] = 'Preencha pelo menos nome do banco e usuário.';
    }

    if (empty($errors))
    {
        try
        {
            $dsnNoDb = "mysql:host={$host};port={$port};charset=utf8mb4";
            $pdo = new PDO($dsnNoDb, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

            // Criar database se não existir
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . str_replace('`','', $db) . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            // Conectar ao DB criado
            $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
            $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

            // Função para executar arquivo SQL (simples, separa por ';')
            $execSqlFile = function(PDO $pdo, $path) {
                if (!file_exists($path)) {
                    throw new Exception("Arquivo SQL não encontrado: {$path}");
                }

                $sql = file_get_contents($path);

                // Dividir por ponto e vírgula e executar statements individuais
                $parts = array_filter(array_map('trim', explode(';', $sql)));

                foreach ($parts as $part) {
                    if (empty($part)) continue;
                    $pdo->exec($part);
                }
            };

            // Executar scripts de criação e dados
            $baseSql1 = __DIR__ . '/../sql/instalar.sql';
            $baseSql2 = __DIR__ . '/../sql/instalar_dados.sql';

            $execSqlFile($pdo, $baseSql1);
            $execSqlFile($pdo, $baseSql2);

            // Gravar arquivo de configuração com credenciais
            $configPath = __DIR__ . '/../../config.php';
            $content = "<?php\n";
            $content .= "define('DB_HOST', '" . escape_val($host) . "');\n";
            $content .= "define('DB_PORT', '" . escape_val($port) . "');\n";
            $content .= "define('DB_NAME', '" . escape_val($db) . "');\n";
            $content .= "define('DB_USER', '" . escape_val($user) . "');\n";
            $content .= "define('DB_PASS', '" . escape_val($pass) . "');\n";

            if (file_put_contents($configPath, $content) === false) {
                throw new Exception('Não foi possível gravar o arquivo de configuração: ' . $configPath);
            }

            // Opcional: criar usuário admin mínimo
            $admin_login = $_POST['admin_login'] ?? '';
            $admin_pass  = $_POST['admin_pass'] ?? '';
            $admin_name  = $_POST['admin_name'] ?? 'Administrador';
            $admin_email = $_POST['admin_email'] ?? '';

            if (!empty($admin_login) && !empty($admin_pass))
            {
                // Importar a classe Extra apenas para encriptar a senha (não carregamos autoload)
                $extraFile = __DIR__ . '/../../class/Extra.php';
                if (file_exists($extraFile)) {
                    require_once $extraFile;
                    $senhaHash = Extra::enc($admin_pass);

                    $stmt = $pdo->prepare('INSERT INTO usuario (login, senha, nome, email, data, ativo) VALUES (?, ?, ?, ?, NOW(), 1)');
                    $stmt->execute([$admin_login, $senhaHash, $admin_name, $admin_email]);
                }
            }

            $success = 'Instalação realizada com sucesso. Remova ou proteja a pasta public/instalar e acesse a aplicação.';
        }
        catch (Exception $ex)
        {
            $errors[] = $ex->getMessage();
        }
    }
}

?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Instalador</title>
    <style>body{font-family:Arial,Helvetica,sans-serif;margin:20px}label{display:block;margin-top:8px}input{width:360px;padding:6px}</style>
</head>
<body>
    <h2>Instalador</h2>

    <?php if ($errors): ?>
        <div style="color:#900"><strong>Erros:</strong><br><?php echo implode('<br>', $errors); ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="color:#060"><strong><?php echo $success; ?></strong></div>
    <?php endif; ?>

    <form method="post">
        <h3>Banco de Dados</h3>
        <label>Host</label>
        <input name="db_host" value="localhost" />
        <label>Porta</label>
        <input name="db_port" value="3306" />
        <label>Nome do banco</label>
        <input name="db_name" value="seu_db" />
        <label>Usuário</label>
        <input name="db_user" value="root" />
        <label>Senha</label>
        <input name="db_pass" type="password" />

        <h3>Usuário Admin (opcional)</h3>
        <label>Login</label>
        <input name="admin_login" />
        <label>Senha</label>
        <input name="admin_pass" type="password" />
        <label>Nome</label>
        <input name="admin_name" />
        <label>E-mail</label>
        <input name="admin_email" />

        <p><button type="submit" name="install" value="1">Instalar</button></p>
    </form>
</body>
</html>
