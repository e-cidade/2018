<?php
    function consultaCorrecaoConplano($tabelaOrigem){
        switch($tabelaOrigem){
            case "orcreceita":
                return " select distinct
                                  o70_anousu,
                                  o70_codfon
                             from orcreceita
                            where not exists ( select *
                                                 from conplano
                                               where c60_codcon = o70_codfon
                                                 and c60_anousu = o70_anousu ) ";
            case "orcdotacao":
                return " select distinct
                                  o58_codele,
                                  o58_anousu
                             from orcdotacao
                            where not exists ( select *
                                                 from conplano
                                               where c60_codcon = o58_codele
                                                 and c60_anousu = o58_anousu ) ";
        }
    }
    
    function consultaAuxiliarConplano($tabelaOrigem, $oConplano){
        switch($tabelaOrigem){
            case "orcreceita":
                return " select *
                                   from orcfontes
                                  where o57_codfon  = {$oConplano->o70_codfon}
                                    and o57_anousu >= {$oConplano->o70_anousu}
                               order by o57_anousu asc ";
            case "orcdotacao":
                return " select *
                               from orcelemento
                              where o56_codele  = {$oConplano->o58_codele}
                                and o56_anousu >= {$oConplano->o58_anousu}
                           order by o56_anousu asc ";
        }
    }
    
    function insereConplanoPorFontesOuElemento($tabelaOrigem, $sTabelaPlano, $oOrcFontesOuElemento, $oConplano){
        switch($tabelaOrigem){
            case "orcreceita": //a consulta usará orcfontes
                return "insert into {$sTabelaPlano} ( c60_codcon,
                                                     c60_anousu,
                                                     c60_estrut,
                                                     c60_descr,
                                                     c60_finali,
                                                     c60_codsis,
                                                     c60_codcla
                                                   ) values (
                                                     {$oOrcFontesOuElemento->o57_codfon},
                                                     {$oConplano->o70_anousu},
                                                     '{$oOrcFontesOuElemento->o57_fonte}',
                                                     '{$oOrcFontesOuElemento->o57_descr}',
                                                     '{$oOrcFontesOuElemento->o57_finali}',
                                                     1,
                                                     1
                                                   )";
            case "orcdotacao": //a consulta usará orcelemento
                return $sSqlInsereConplano = " insert into {$sTabelaPlano} ( c60_codcon,
                                                     c60_anousu,
                                                     c60_estrut,
                                                     c60_descr,
                                                     c60_finali,
                                                     c60_codsis,
                                                     c60_codcla
                                                   ) values (
                                                     {$oOrcFontesOuElemento->o56_codele},
                                                     {$oConplano->o58_anousu},
                                                     '{$oOrcFontesOuElemento->o56_elemento}',
                                                     '{$oOrcFontesOuElemento->o56_descr}',
                                                     '{$oOrcFontesOuElemento->o56_finali}',
                                                     1,
                                                     1
                                                   )";
        }
    }
    
    /**
     * Consulta um conplano a partir da tabela de origem
     * @param string $tabelaOrigem Nome da tabela de origem
     * @param $oConplano
     * @return 
     */
    function consultaConplano($tabelaOrigem, $oConplano){
        $codigo = 0;
        if ($tabelaOrigem == "orcreceita")
            $codigo = o70_codfon;
        elseif($tabelaOrigem == "orcdotacao")
            $codigo = o58_codele;
        return "select *
                from conplano
                where c60_codcon = {$oConplano->$codigo}";
    }
    
    /**
     * Insere um complano na tabela plano
     * @param $tabelaOrigem
     * @param $sTabelaPlano 
     * @param $oConplano 
     * @param $oConplanoOrigem 
     * @return object  Description
     */
    function insereConplanoPorChaveConplano($tabelaOrigem, $sTabelaPlano, $oConplano, $oConplanoOrigem){
        $campoCodigo = 0;
        if($tabelaOrigem == "orcreceita") $campoCodigo = "o70_anousu";
        elseif($tabelaOrigem == "orcdotacao") $campoCodigo = "o58_anousu";
        return " insert into {$sTabelaPlano} ( c60_codcon,
                                               c60_anousu,
                                               c60_estrut,
                                               c60_descr,
                                               c60_finali,
                                               c60_codsis,
                                               c60_codcla
                                             ) values (
                                               {$oConplanoOrigem->c60_codcon},
                                               {$oConplano->$campoCodigo},
                                               '{$oConplanoOrigem->c60_estrut}',
                                               '{$oConplanoOrigem->c60_descr}',
                                               '{$oConplanoOrigem->c60_finali}',
                                               {$oConplanoOrigem->c60_codsis},
                                               {$oConplanoOrigem->c60_codcla}
                                             )";
    }

    /**
     * Consulta todos os objetos existentes em uma tabela
     * @param string $tabela Nome da tabela
     * @return 
     */
    function buscaTodosOsObjetosDaTabela($tabela){
        try{
            return " select * from $tabela ";    
        }catch ( Exception $eException ) {
            throw new Exception("ERRO: {$eException->getMessage()}");
        }    
    }
    
    /**
     * Consulta instituições na base de origem
     * @return 
     */
    function consultaInstituicoes(){
        return " select db_config.codigo   as codinstit,
                        db_config.nomeinst as descricao
                        from db_config";
    }
?>