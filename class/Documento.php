<?php

class Documento
{
    /**
     * String sem símbolos a validar
     * 
     * @var string
     */
    private $string = '';
    
    /**
     * Tipo do dado a ser validado
     * 
     * 0 -> NENHUM
     * 1 -> CEP
     * 2 -> CPF
     * 3 -> CNPJ
     * 
     * @var string
     */
    private $tipo  = '';
    
    /**
     * Dados do número formatado
     * 
     * @var string
     */
    private $formatado = '';
    
    /**
     * O tipo do dado é um número válido
     * 
     * @var boolean
     */
    private $valido = false;
    
    /**
     * Número do documento a ser validado
     * 
     * @param string $string
     */
    public function __construct($string)
    {
        // Número sem formatação
        $this->string = (string)preg_replace('/[^0-9]/is', '', $string);
        
        // Switch
        switch (strlen($this->string))
        {
            case 8:
            {
                // Tipo
                $this->tipo = 'CEP';
                //
                // CEP Formatado
                $this->formatado = preg_replace("/(\d{5})(\d{3})/", "\$1-\$2", $this->string);
                //
                // Válido
                $this->valido = $this->validaCEP();
                break;
            }
            case 11:
            {
                // Tipo
                $this->tipo = 'CPF';
                //
                // CPF Formatado
                $this->formatado = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $this->string);
                //
                // Válido
                $this->valido = $this->validaCPF();
                break;
            }
            case 14:
            {
                // Tipo
                $this->tipo = 'CNPJ';
                //
                // CNPJ Formatado
                $this->formatado = preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $this->string);
                //
                // Válido
                $this->valido = $this->validaCNPJ();
                break;
            }
        }
    }
    
    /**
     * Retorna os dados passado para a classe
     * 
     * @param boolean $formato      Retorna a string com o formato dos dados ou somente números
     * 
     * @return string               Texto dos dados
     */
    public function getString($formato = false)
    {
        if ($formato)
        {
            return $this->formatado;
        }
        
        return $this->string;
    }

    /**
     * Retorna o tipo do documento: CEP, CPF, CNPJ
     * 
     * @return string       Retorna o tipo
     */
    public function getTipo()
    {
        return $this->tipo;
    }
    
    /**
     * Retorna se o documento passado é válido
     * 
     * @return boolean
     */
    public function valida()
    {
        return $this->valido;
    }

    /**
     * Validar o número do CEP informado
     * 
     * @return bool         <b>TRUE</b> CEP válido, <b>FALSE</b> CEP inválido
     */
    private function validaCEP()
    {
        if (!empty($this->string))
        {
            return (preg_match('/^[0-9]{5,5}([- ]?[0-9]{3,3})?$/', $this->string) !== false);
        }

        return false;
    }

    /**
     * Validar o número do CNPJ informado
     * 
     * @return bool         <b>TRUE</b> CNPJ válido, <b>FALSE</b> CNPJ inválido
     */
    private function validaCNPJ()
    {
        if(!empty($this->string))
        {
            $cnpj = (string)$this->string;

            if (strlen($cnpj) != 14)
            {
                return false;
            }

            if (preg_match('/(\d)\1{13}/', $cnpj))
            {
                return false;
            }

            for ($t = 12; $t < 14; $t++)
            {
                for ($d = 0, $m = ($t - 7), $i = 0; $i < $t; $i++)
                {
                    $d += $cnpj[$i] * $m;
                    
                    $m = ($m == 2 ? 9 : --$m);
                }
                
                $d = ((10 * $d) % 11) % 10;

                if ($cnpj[$i] != $d)
                {
                    return false;
                }
            }
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Validar o número do CPF informado
     * 
     * @return bool         <b>TRUE</b> CPF válido, <b>FALSE</b> CPF inválido
     */
    private function validaCPF()
    {
        if (!empty($this->string))
        {
            $cpf = (string) $this->string;

            if (strlen($cpf) != 11)
            {
                return false;
            }

            if (preg_match('/(\d)\1{10}/', $cpf))
            {
                return false;
            }

            for ($t = 9; $t < 11; $t++)
            {
                for ($d = 0, $c = 0; $c < $t; $c++)
                {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }

                $d = ((10 * $d) % 11) % 10;

                if ($cpf[$c] != $d)
                {
                    return false;
                }
            }

            return true;
        }

        return false;
    }
}
