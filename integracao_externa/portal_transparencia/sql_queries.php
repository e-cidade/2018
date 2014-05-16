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
    
    
    /**
     * Consulta Orgãos na base de origem
     * @return
     */
    function consultaOrgaos(){
        return " select o40_instit as codinstit,        
                o40_orgao  as codorgao,        
                o40_descr  as descricao,        
                o40_anousu as exercicio         
                from orcorgao                        ";
    }
    
    function consultaUnidades(){
        return " select o41_instit  as codinstit,       
                        o41_orgao   as codorgao,
                        o41_unidade as codunidade,
                        o41_descr   as descricao,
                        o41_anousu  as exercicio
                        from orcunidade                      ";
    }
    
    function consultaProjetos(){
        return " select o55_instit   as codinstit,
                o55_tipo     as tipo,           
                o55_projativ as codprojeto,     
                o55_descr    as descricao,      
                o55_anousu   as exercicio       
                from orcprojativ                     ";
    }
    
    function consultaFuncoes(){
        return " select o52_funcao as codfuncao, 
                o52_descr  as descricao  
                from orcfuncao                ";
    }
    
    function consultaSubFuncoes(){
        return " select o53_subfuncao as codsubfuncao,
                o53_descr     as descricao     
                from orcsubfuncao                   ";
    }
    
    function consultaProgramas(){
        return " select o54_programa as codprograma,    
                o54_descr    as descricao,      
                o54_anousu    as exercicio      
                from orcprograma                     ";
    }
    
    function consultaRecursos(){
        return " select o15_codigo as codrecurso, 
                o15_descr  as descricao   
                from orctiporec                ";
    }
    
    function consultaPlanoContas(){
        return " select conplano.c60_codcon as codcon,     
                conplano.c60_estrut as estrutural, 
                conplano.c60_descr  as descricao,  
                conplano.c60_anousu as exercicio   
                from conplano                           ";
    }
    
    function consultaPlanoContasPCASP($ano_anterior_implantacao_pcasp){
        return "select distinct codcon, estrutural, descricao, exercicio
                from (select conplano.c60_codcon as codcon,                                                    
                        conplano.c60_estrut as estrutural,                                                
                        conplano.c60_descr  as descricao,                                                 
                        conplano.c60_anousu as exercicio                                                  
                        from conplano where c60_anousu <= " . $ano_anterior_implantacao_pcasp . " union         
                        select conplanoorcamento.c60_codcon as codcon,                                           
                        conplanoorcamento.c60_estrut as estrutural,                                       
                        conplanoorcamento.c60_descr  as descricao,                                        
                        conplanoorcamento.c60_anousu as exercicio                                         
                        from conplanoorcamento where c60_anousu > " . $ano_anterior_implantacao_pcasp . ") as x ";
    }
    
    function consultaReceitas(){
        return "select o70_codrec as codreceita,
               o70_codfon as codcon,
               o70_anousu as exercicio,
               o70_codigo as codrecurso,       
               o70_instit as codinstit,        
               o70_valor  as previsaoinicial   
               from orcreceita";
    }
    
    function consultaMovimentacoesReceitas(){
        return "select o70_codrec as codreceita,                                                           
                o70_anousu as exercicio,                                                            
                c70_data   as data,                                                                 
                sum( case                                                                           
                    when c57_sequencial = 100 then c70_valor                                    
                    when c57_sequencial = 101 then (c70_valor * -1)                             
                    else 0                                                                      
                    end ) as valor,                                                                
                sum(case                                                                            
                    when c57_sequencial = 110  then c70_valor                                       
                    when c57_sequencial = 111 then (c70_valor * -1)                                 
                    else 0                                                                          
                    end ) as previsaoadicional,                                                     
                sum(case                                                                            
                    when c57_sequencial = 58   then c70_valor                                       
                    when c57_sequencial = 104 then (c70_valor * -1)                                 
                    else 0                                                                          
                    end ) as previsao_atualizada                                                    
                from orcreceita                                                                           
                    inner join conlancamrec   on conlancamrec.c74_codrec = orcreceita.o70_codrec         
                    and conlancamrec.c74_anousu = orcreceita.o70_anousu         
                    inner join conlancam      on conlancam.c70_codlan    = conlancamrec.c74_codlan       
                    inner join conlancamdoc   on conlancamdoc.c71_codlan = conlancam.c70_codlan          
                    inner join conhistdoc     on conlancamdoc.c71_coddoc = conhistdoc.c53_coddoc         
                    inner join conhistdoctipo on conhistdoc.c53_tipo     = conhistdoctipo.c57_sequencial 
                    group by o70_codrec,o70_anousu,c70_data                                                    ";
    }
    
    /**
    * Consulta Preparada para execução da função fc_receitasaldo na base de origem
    */
    function movimentacoesReceitas(){
        return " prepare stmt_receitasaldo(integer, integer) as 
                                    select cast(                                                                            
                                    substr(                                                                          
                                    fc_receitasaldo($1,                                                              
                                                    $2,                                                              
                                                    3,                                                               
                                                    current_date,                                                    
                                                    current_date),41,13) as numeric(15,2));                          ";
    }
?>