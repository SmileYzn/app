<?php

class Unidade extends Base
{    
    /**
     * Executa as funçoes do index para a classe
     * 
     * @return mixed     Retorna a consulta da pesquisa realizada ou os dados do item
     */
    public function index()
    {
        if (!empty($this->excluir))
        {
            $this->excluir();
        }

        if (!empty($this->salvar))
        {
            $this->salvar(Extra::dec($_GET['id']));
        }

        if (!empty($_GET['id']))
        {
            return $this->id(Extra::dec($_GET['id']));
        }

        return $this->pesquisar();
    }
    
    /**
     * Executa uma pesquisa para listagem dos dados da classe
     * 
     * @return PDOStatement     Retorna a consulta da pesquisa 1realizada
     */
    public function pesquisar()
    {
        if (!empty($this->buscar))
        {
            $_SESSION[$_SERVER['SCRIPT_NAME']] = $_POST;
        }

        if (!empty($this->limpar))
        {
            unset($_SESSION[$_SERVER['SCRIPT_NAME']]);
        }

        $where = "ativo = 1";

        if (!empty($_SESSION[$_SERVER['SCRIPT_NAME']]['pesquisa']))
        {
            $where .= " AND (nome LIKE '%{$_SESSION[$_SERVER['SCRIPT_NAME']]['pesquisa']}%')";
        }

        return $this->consultarAtivo($where,"ORDER BY id", Paginacao::limit($this->contar($where)));
    }
    
    /**
     * Validar erros na requisição atual
     * 
     * @param int $id       Index do item a ser validado ou 0 se for um novo item
     * 
     * @return boolean      <b>TRUE</b> se validou com sucesso ou <b>FALSE</b>
     */
    public function validar($id)
    {
        if (empty($this->cnpj))
        {
            return ["Preencha o campo: CNPJ"];
        }
        else
        {
            if (!(new Documento($this->cnpj))->valida())
            {
                return ["O CNPJ {$this->cnpj} é inválido"];
            }

            if ($this->contar("ativo = 1 AND id <> '{$id}' AND cnpj = '{$this->cnpj}'") > 0)
            {
                return ["O CNPJ {$this->cnpj} já está registrado"];
            }
        }
        
        $erro = [];

        if (empty($this->nome))
        {
            $erro[] = "Preencha o campo: Nome";
        }

        if (empty($this->estadoFK))
        {
            $erro[] = "Preencha o campo: Estado";
        }

        if (empty($this->cidadeFK))
        {
            $erro[] = "Preencha o campo: Cidade";
        }

        if (empty($this->regimeTributarioFK))
        {
            $erro[] = "Preencha o campo: Regime Tributário";
        }

        return $erro;
    }

    /**
     * Adicionar um registro a tabela
     * 
     * @return int                  Retorna o último index inserido ou 0
     */
    public function salvar($id)
    {
        $erro = $this->validar($id);

        if ($erro)
        {
            Alert::set($erro);
            return 0;
        }
        
        $registro =
        [
            'estadoFK'               => $this->estadoFK,
            'cidadeFK'               => $this->cidadeFK,
            'regimeTributarioFK'     => $this->regimeTributarioFK,
            'cnpj'                   => $this->cnpj,
            'nome'                   => $this->nome,
            'razaoSocial'            => $this->razaoSocial,
            'nomeFantasia'           => $this->nomeFantasia,
            'qsa'                    => $this->qsa,
            'inscricaoEstadual'      => $this->inscricaoEstadual,
            'inscricaoMunicipal'     => $this->inscricaoMunicipal,
            'isento'                 => $this->isento,
            'abertura'               => !empty($this->abertura) ? $this->abertura : null,
            'ultimaAtualizacao'      => !empty($this->ultimaAtualizacao) ? $this->ultimaAtualizacao : null,
            'cnae'                   => $this->cnae,
            'tipo'                   => $this->tipo,
            'porte'                  => $this->porte,
            'naturezaJuridica'       => $this->naturezaJuridica,
            'efr'                    => $this->efr,
            'capitalSocial'          => $this->capitalSocial,
            'situacao'               => $this->situacao,
            'situacaoData'           => !empty($this->situacaoData) ? $this->situacaoData : null,
            'situacaoMotivo'         => $this->situacaoMotivo,
            'situacaoEspecial'       => $this->situacaoEspecial,
            'situacaoEspecialData'   => !empty($this->situacaoEspecialData) ? $this->situacaoEspecialData : null,
            'cep'                    => $this->cep,
            'bairro'                 => $this->bairro,
            'logradouro'             => $this->logradouro,
            'numero'                 => $this->numero,
            'complemento'            => $this->complemento,
            'telefone'               => $this->telefone,
            'celular'                => $this->celular,
            'email'                  => $this->email,
            'website'                => $this->website,
            'observacoes'            => $this->observacoes,
        ];

        $imagem = (new Upload($this->tabela))->imagem('imagem');

        if(!empty($imagem))
        {
            $registro['imagem'] = reset($imagem);
        }
        
        $resultado = $this->registrar($registro, $id);

        if ($resultado)
        {
            unset($_POST);
            
            Alert::set("Registro salvo com sucesso", "success");
            
            return $resultado;
        }
        
        Alert::set("Nenhuma alteração, tente novamente", "warning");
        return 0;
    }
    
    /**
     * Inativar os registros passados via $_POST['id']
     * 
     * @return int      Quantidade de registros inativados
     */
    public function excluir()
    {
        $resultado = 0;
        
        if (empty($this->id) || !is_array($this->id))
        {
            $resultado = $this->inativar($this->id);

            if ($resultado > 0)
            {
                Alert::set("{$resultado} registro(s) removido(s) com sucesso.", "success");
            }
            else
            {
                Alert::set("Nenhuma alteração, tente novamente.");
            }
        }
        else
        {
            Alert::set("Selecione algum registro.");
        }
        
        return $resultado;
    }
}
