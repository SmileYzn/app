<?php

class Upload
{
    /**
     * Nome da pasta de destino do arquivo
     * 
     * @var string
     */
    private $pasta   = '';
    
    /**
     * Camnho construído a partir da pasta
     * 
     * @var string
     */
    private $caminho = '';

    /**
     * Construir um caminho de upload
     * 
     * @param string $pasta     Nome da pasta de destino do arquivo
     */
    public function __construct($pasta)
    {
        $this->pasta = $pasta;

        $this->caminho = $_ENV['SG_PATH_PUBLIC'] . $this->pasta;

        if (!is_dir($this->caminho))
        {
            mkdir($this->caminho, 0755, true);
        }
    }
    
    /**
     * Retornar o caminho 'tmp_name' do array $_FILES
     * Verificar se os arquivos foram enviados com sucesso e retorna os dados de $_FILES para cada arquivo
     * 
     * @param string $nome      Nome do campo no array $_FILES
     * 
     * @return array            Array com os caminhos de 'tmp_name'
     */
    private function getArquivos($nome)
    {
        $resultado = [];
        
        if (!empty($_FILES[$nome]['tmp_name']))
        {
            if (is_countable($_FILES[$nome]['tmp_name']))
            {
                foreach ($_FILES[$nome]['tmp_name'] as $key => $tmp_name)
                {
                    if (is_uploaded_file($_FILES[$nome]['tmp_name'][$key]))
                    {
                        $resultado[$key] =
                        [
                            'name'     => $_FILES[$nome]['name'][$key],
                            'type'     => $_FILES[$nome]['type'][$key],
                            'tmp_name' => $_FILES[$nome]['tmp_name'][$key],
                            'error'    => $_FILES[$nome]['error'][$key],
                            'size'     => $_FILES[$nome]['size'][$key],
                        ];
                    }
                }
            }
            else
            {
                if (is_uploaded_file($_FILES[$nome]['tmp_name']))
                {
                    $resultado[0] = $_FILES[$nome];
                }
            }
        }

        return $resultado;
    }

    /**
     * Construir o caminho absoluto de destino do arquivo
     * 
     * @param string $arquivo       Nome do arquivo de destino
     * 
     * @return string               Caminho absoluto do arquivo
     */
    private function getDestino($arquivo)
    {
        return sprintf('%s/%s.%s', $this->caminho, bin2hex(openssl_random_pseudo_bytes(10)), pathinfo($arquivo, PATHINFO_EXTENSION));
    }
    
    /**
     * Retorna o caminho relativo da pasta public
     * 
     * @param string $arquivo       Nome do arquivo de destino
     * 
     * @return string               Caminho relativo a pasta public
     */
    private function getPublic($arquivo)
    {
        return sprintf('public/%s/%s', $this->pasta, basename($arquivo));
    }

    /**
     * Mover um arquivo do upload ($_FILES) para a pasta pública
     * 
     * @param string $nome      Nome do campo a ser tratado em $_FILES
     * 
     * @return mixed            Caminho relativo do arquivo em caso de sucesso ou <b>false</b>
     */
    public function arquivo($nome)
    {
        $resultado = [];
        
        if (!empty($this->pasta) && !empty($nome))
        {
            $arquivos = $this->getArquivos($nome);
            
            foreach ($arquivos as $key => $arquivo)
            {
                $destino = $this->getDestino($arquivo['name']);

                if (move_uploaded_file($arquivo['tmp_name'], $destino))
                {
                    $resultado[$key] = $this->getPublic($destino);
                }
            }
        }
        
        return $resultado;
    }

    /**
     * Mover um arquivo do upload ($_FILES) para a pasta pública e redimensionar como imagem
     * 
     * @param string $pasta     Pasta relativo ao diretório público
     * @param string $nome      Nome do campo a ser inserido no registro
     * @param int $largura      Largura (Width) da nova imagem
     * @param int $altura       Altura (Height) da nova imagem
     * 
     * @return mixed            Caminho relativo do arquivo em caso de sucesso ou <b>false</b>
     */
    public function imagem($nome, $largura = 256, $altura = 256)
    {
        $arquivos = $this->arquivo($nome);

        foreach ($arquivos as $key => $arquivo)
        {
            $destino = dirname($_ENV['SG_PATH_PUBLIC']) . DIRECTORY_SEPARATOR . $arquivo;

            try
            {
                $img = new ImageResize($destino);

                $img->crop($largura, $altura, true);

                $img->save($destino);

                $resultado[$key] = $arquivo;
            }
            catch (Exception $ex)
            {
                @unlink($destino);
            }
        }
        
        if (!empty($resultado))
        {
            
        }
        
        return false;
    }
}
