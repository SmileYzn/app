<?php

class Cidade extends Base
{
    /**
     * Retorna as cidades com base no index (ID) do estado
     * 
     * @param int $estadoFK     Index do estado a retornar cidades
     * 
     * @return array            Retorna o <b>Array</b> com as cidades
     */
    public function get($estadoFK)
    {
        $resultado = [];
        
        if (!empty($estadoFK))
        {
            $run = $this->consultar("estadoFK = '{$estadoFK}'", "ORDER BY nome");
            
            foreach ($run as $row)
            {
                $resultado[$row['id']] = $row['nome'];
            }
        }
        
        return $resultado;
    }
}
