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

    /**
    * Update para acertar a tabela receitas_movimentacoes.
    */
    function acerta_receitas_movimentacoes() {
        return " UPDATE receitas_movimentacoes SET valor = ( valor * -1 )
                    WHERE receita_id in ( select distinct receitas.id
                    from receitas
                    inner join planocontas on planocontas.id = receitas.planoconta_id
                    where planocontas.estrutural like '9%'
                    or planocontas.estrutural like '49%')";
    }

    /**
    * Consulta Dotacoes na base de origem.
    */
    function consulta_dotacao() {
        return " select o58_coddot    as coddotacao,
                 o58_orgao     as codorgao,  
                 o58_unidade   as codunidade,    
                 o58_funcao    as codfuncao,     
                 o58_subfuncao as codsubfuncao,  
                 o58_programa  as codprograma,   
                 o58_projativ  as codprojeto,    
                 o58_codigo    as codrecurso,    
                 o58_instit    as codinstit,     
                 o58_anousu    as exercicio,     
                 o58_codigo    as recurso,       
                 o58_codele    as codcon         
                 from orcdotacao                      ";
    }


    /**
    * Consulta Empenhos na base da origem;
    */
    function consulta_empenhos($iExercicioBase) {
        return "select distinct e60_numemp as codempenho,                                                      
            e60_codemp as codigo,                                                                   
                       e60_anousu as exercicio,                                                                
                       e60_instit as codinstit,                                                                
                       e60_emiss  as dataemissao,                                                              
                       e60_coddot as coddotacao,                                                               
                       e60_vlremp as valor,                                                                    
                       e60_vlrpag as valor_pago,                                                               
                       e60_vlrliq as valor_liquidado,                                                          
                       e60_vlranu as valor_anulado,                                                            
                       e60_resumo as resumo,                                                                   
                       z01_numcgm as numcgm,                                                                   
                       coalesce(nullif(trim(z01_cgccpf),''),'0') as cgccpf,                                    
                       case                                                                                    
                           when c60_codcon is not null then c60_codcon                                          
                               else o58_codele                                                                      
                                   end as codcon,                                                                          
                                       z01_nome    as nome,                                                                    
                                       e61_autori  as codautoriza,                                                             
                                       e60_numerol as numero_licitacao,                                                        
                                       pc50_descr  as descrtipocompra                                                          
                                           from empempenho                                                                            
                                           inner join cgm          on cgm.z01_numcgm           = empempenho.e60_numcgm             
                                           inner join orcdotacao   on orcdotacao.o58_coddot    = empempenho.e60_coddot             
                                           inner join pctipocompra on pctipocompra.pc50_codcom = empempenho.e60_codcom             
                                           and orcdotacao.o58_anousu    = empempenho.e60_anousu             
                                           left join (  select distinct on (e.e64_numemp) e.e64_numemp, e.e64_codele               
                                                   from empelemento e                                                       
                                                   order by e.e64_numemp, e.e64_codele ) as x                                 
                                           on x.e64_numemp           = empempenho.e60_numemp              
                                           left join conplano      on conplano.c60_codcon    = x.e64_codele                      
                                           and conplano.c60_anousu    = empempenho.e60_anousu               
                                           left join empempaut     on empempaut.e61_numemp   = empempenho.e60_numemp             
                                           where exists (  select 1                                                                    
                                                   from conlancamemp                                                           
                                                   inner join conlancam on conlancam.c70_codlan = conlancamemp.c75_codlan 
                                                   where c75_numemp = e60_numemp                                               
                                                   and c70_data >= '{$iExercicioBase}-01-01'::date )                          
                                           and exists (  select 1                                                                    
                                                   from empempitem                                                             
                                                   where empempitem.e62_numemp = empempenho.e60_numemp )                      "; 
    }

    /**
    * Consulta o id de pessoas de acordo com o codpessoa.
    */
    function consulta_pessoas_codpessoa($numcgm) {
        return " select id
                    from pessoas
                    where codpessoa = {$numcgm} ";
    }

    /**
    * Consulta o tipo da compra de acordo com o codautoriza do empenho.
    * @param $codautoriza Atributo de empenho.
    */
    function consulta_tipo_compra($codautoriza) {
        return " select * 
            from empautitem 
            inner join empautitempcprocitem on empautitempcprocitem.e73_sequen = empautitem.e55_sequen          
            and empautitempcprocitem.e73_autori = empautitem.e55_autori          
            inner join liclicitem           on liclicitem.l21_codpcprocitem    = empautitempcprocitem.e73_sequen
            inner join liclicita            on liclicitem.l21_codliclicita     = liclicita.l20_codigo           
            inner join cflicita             on liclicita.l20_codtipocom        = cflicita.l03_codigo            
            inner join empautoriza          on empautoriza.e54_autori          = empautitem.e55_autori          
            where empautitem.e55_autori = {$codautoriza} ";
    }

    /**
    * Consulta itens do empenho.
    */
    function consulta_itens_empenho($codempenho) {
        return     " select trim(replace(pc01_descrmater, '\r\n', ' ')) as descricao,     
            e62_quant                                   as quantidade,    
                                                        e62_vlrun                                   as valor_unitario,
                                                        e62_vltot                                   as valor_total    
                                                            from empempitem                                                    
                                                            inner join pcmater on pc01_codmater = e62_item                
                                                            where e62_numemp = {$codempenho} "; 
    }

    /**
    * Consulta Processos do Empenho.
    */
        function consulta_processos_empenho($codempenho) {
        return " select pc81_codproc as processo
            from empempaut
            inner join empautitem           on e55_autori = e61_autori
            inner join empautitempcprocitem on e73_autori = e55_autori
            and e73_sequen = e55_sequen
            inner join pcprocitem           on pc81_codprocitem = e73_pcprocitem
            where e61_numemp = {$codempenho} ";
    }

    /**
    * Consulta EmpenhoMovimentacoes na base de origem.
    */
    function consulta_empenhoMovimentacoes_origem($iExercicioBase) {
        return     " select conhistdoc.c53_coddoc   as codtipo,                                               
                    conhistdoc.c53_tipo     as codgrupo,                                              
                    conhistdoc.c53_descr    as descrtipo,                                             
                    conlancamemp.c75_numemp as codempenho,                                            
                    c70_data                as data,                                                  
                    c70_valor               as valor,                                                 
                    c72_complem             as historico                                              
                        from conlancamemp                                                                      
                        inner join conlancam      on conlancam.c70_codlan      = conlancamemp.c75_codlan  
                        inner join conlancamdoc   on conlancamdoc.c71_codlan   = conlancamemp.c75_codlan  
                        inner join conhistdoc     on conhistdoc.c53_coddoc     = conlancamdoc.c71_coddoc  
                        left  join conlancamcompl on conlancamcompl.c72_codlan = conlancamemp.c75_codlan  
                        where c70_data >= '{$iExercicioBase}-01-01'::date                                       
                        and exists ( select * from empempitem where empempitem.e62_numemp = conlancamemp.c75_numemp )";
    }

    /**
    * Consulta ID de empenhos dado codempenho.
    */
    function consulta_empenho_destino($codempenho) {
        return "select id from empenhos where codempenho = {$codempenho} ";
    }

    /**
    * Sql dos servidores.
    */
    function sql_servidores($iExercicioBase) {
        return      " create temp table dados_servidor as                        
          select rh02_anousu as ano,                                 
               rh02_mesusu as mes,                                   
               rh02_salari as salario_base,                          
               rh01_regist as matricula,                             
               z01_nome    as nome,                                  
               z01_cgccpf  as cpf,                                   
               rh37_descr  as cargo,                                 
               r70_descr   as lotacao,                               
               rh30_descr  as vinculo,                               
               rh01_admiss as admissao,                              
               rh05_recis  as rescisao,                              
               codigo      as instituicao,                           
               rh01_instit as instit_servidor                        
          from rhpessoal                                             
               inner join rhpessoalmov on rh02_regist = rh01_regist  
               inner join rhfuncao     on rh37_funcao = rh02_funcao  
                                      and rh37_instit = rh02_instit  
               inner join rhlota       on r70_codigo  = rh02_lota    
                                      and r70_instit  = rh02_instit  
               inner join cgm          on z01_numcgm  = rh01_numcgm  
               inner join rhregime     on rh02_codreg = rh30_codreg  
                                      and rh02_instit = rh30_instit  
               inner join db_config    on codigo      = rh02_instit  
               left join rhpesrescisao on rh05_seqpes = rh02_seqpes  

         where rh02_anousu >= {$iExercicioBase} 

         order by rh02_anousu, rh02_mesusu, rh01_regist          "; 
    }
    
    /**
    * Consulta dados cadastrais do servidor.
    */
    function consulta_dados_cadastrais_servidor() {
        return      "select matricula as id,                         
            nome,                                    
            cpf,                                     
            instit_servidor as instituicao,            
            admissao,                                
            max(rescisao) as rescisao                
                from dados_servidor                           
                group by id, nome, cpf, instit_servidor, admissao ";
    }

    /**
    * Consulta de movimentações de servidores.
    */
    function consulta_movimentacao_servidor() {
        return  "   select matricula as servidor_id,                                         
            ano,                                                              
            mes,                                                              
            cargo,                                                            
            lotacao,                                                          
            vinculo,                                                          
            salario_base                                                      
                from dados_servidor                                                   
                group by servidor_id, ano, mes, cargo, lotacao, vinculo, salario_base ";
    }

    /**
    * Sql para montar uma matriz para pegar a movimentação correspondente a competência.
    */
    function sql_matriz_servidor_movimentacao($schema) {
        return " select id, servidor_id, mes, ano from $schema.servidor_movimentacoes ";
    }

    /**
    * Cria tabela com os totalizadores.
    */
    function cria_tabela_totalizadores() {
        $sSqlTempTableSomatorio  = " create temp table somatorio as                                                                                                                ";
        $sSqlTempTableSomatorio .= "      select r14_anousu as anousu,                                                                                                                ";
        $sSqlTempTableSomatorio .= "              r14_mesusu as mesusu,                                                                                                               ";
        $sSqlTempTableSomatorio .= "              r14_regist as regist,                                                                                                               ";
        $sSqlTempTableSomatorio .= "              'Z888'::char(4) as rubrica,                                                                                                         ";
        $sSqlTempTableSomatorio .= "              sum(r14_valor)  as valor,                                                                                                           ";
        $sSqlTempTableSomatorio .= "              0               as quantidade,                                                                                                      ";
        $sSqlTempTableSomatorio .= "              'base'          as tiporubrica,                                                                                                     ";
        $sSqlTempTableSomatorio .= "              'salario'       as tipofolha,                                                                                                       ";
        $sSqlTempTableSomatorio .= "              r14_instit      as instit                                                                                                           ";
        $sSqlTempTableSomatorio .= "         from gerfsal                                                                                                                             ";
        $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r14_regist                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and ano       = r14_anousu                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and mes       = r14_mesusu                                                                                 ";
        $sSqlTempTableSomatorio .= "        where r14_pd     = 2                                                                                                                      ";
        $sSqlTempTableSomatorio .= "        group by r14_anousu, r14_mesusu, r14_regist, r14_instit                                                                                   ";
        $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
        $sSqlTempTableSomatorio .= "      select r14_anousu, r14_mesusu, r14_regist, 'Z999'::char(4) as r14_rubric, sum(r14_valor) as r14_valor,0,'base', 'salario', r14_instit       ";
        $sSqlTempTableSomatorio .= "         from gerfsal                                                                                                                             ";
        $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r14_regist                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and ano       = r14_anousu                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and mes       = r14_mesusu                                                                                 ";
        $sSqlTempTableSomatorio .= "        where r14_pd = 1                                                                                                                          ";
        $sSqlTempTableSomatorio .= "        group by r14_anousu, r14_mesusu, r14_regist, r14_instit                                                                                   ";
        $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
        $sSqlTempTableSomatorio .= "      select r14_anousu, r14_mesusu, r14_regist, 'Z777'::char(4) as r14_rubric, sum(r14_valor) as r14_valor,0,'base', 'salario', r14_instit       ";
        $sSqlTempTableSomatorio .= "         from gerfsal                                                                                                                             ";
        $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r14_regist                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and ano       = r14_anousu                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and mes       = r14_mesusu                                                                                 ";
        $sSqlTempTableSomatorio .= "        where r14_rubric between 'R901' and 'R915'                                                                                                ";
        $sSqlTempTableSomatorio .= "        group by r14_anousu, r14_mesusu, r14_regist, r14_instit                                                                                   ";
        $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
        $sSqlTempTableSomatorio .= "      select r48_anousu, r48_mesusu, r48_regist, 'Z888'::char(4) as r48_rubric, sum(r48_valor) as r48_valor,0,'base', 'complementar', r48_instit  ";
        $sSqlTempTableSomatorio .= "         from gerfcom                                                                                                                             ";
        $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r48_regist                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and ano       = r48_anousu                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and mes       = r48_mesusu                                                                                 ";
        $sSqlTempTableSomatorio .= "        where r48_pd = 2                                                                                                                          ";
        $sSqlTempTableSomatorio .= "        group by r48_anousu, r48_mesusu, r48_regist, r48_instit                                                                                   ";
        $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
        $sSqlTempTableSomatorio .= "      select r48_anousu, r48_mesusu, r48_regist, 'Z999'::char(4) as r48_rubric, sum(r48_valor) as r48_valor,0,'base', 'complementar', r48_instit  ";
        $sSqlTempTableSomatorio .= "         from gerfcom                                                                                                                             ";
        $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r48_regist                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and ano       = r48_anousu                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and mes       = r48_mesusu                                                                                 ";
        $sSqlTempTableSomatorio .= "        where r48_pd = 1                                                                                                                          ";
        $sSqlTempTableSomatorio .= "        group by r48_anousu, r48_mesusu, r48_regist, r48_instit                                                                                   ";
        $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
        $sSqlTempTableSomatorio .= "      select r48_anousu, r48_mesusu, r48_regist, 'Z777'::char(4) as r48_rubric, sum(r48_valor) as r48_valor,0,'base', 'complementar', r48_instit  ";
        $sSqlTempTableSomatorio .= "         from gerfcom                                                                                                                             ";
        $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r48_regist                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and ano       = r48_anousu                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and mes       = r48_mesusu                                                                                 ";
        $sSqlTempTableSomatorio .= "        where r48_rubric between 'R901' and 'R915'                                                                                                ";
        $sSqlTempTableSomatorio .= "        group by r48_anousu, r48_mesusu, r48_regist, r48_instit                                                                                   ";
        $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
        $sSqlTempTableSomatorio .= "      select r35_anousu, r35_mesusu, r35_regist, 'Z888'::char(4) as r35_rubric, sum(r35_valor) as r35_valor,0,'base', '13salario', r35_instit     ";
        $sSqlTempTableSomatorio .= "         from gerfs13                                                                                                                             ";
        $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r35_regist                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and ano       = r35_anousu                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and mes       = r35_mesusu                                                                                 ";
        $sSqlTempTableSomatorio .= "        where r35_pd = 2                                                                                                                          ";
        $sSqlTempTableSomatorio .= "        group by r35_anousu, r35_mesusu, r35_regist, r35_instit                                                                                   ";
        $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
        $sSqlTempTableSomatorio .= "      select r35_anousu, r35_mesusu, r35_regist, 'Z999'::char(4) as r35_rubric, sum(r35_valor) as r35_valor,0,'base', '13salario', r35_instit     ";
        $sSqlTempTableSomatorio .= "         from gerfs13                                                                                                                             ";
        $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r35_regist                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and ano       = r35_anousu                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and mes       = r35_mesusu                                                                                 ";
        $sSqlTempTableSomatorio .= "        where r35_pd = 1                                                                                                                          ";
        $sSqlTempTableSomatorio .= "        group by r35_anousu, r35_mesusu, r35_regist, r35_instit                                                                                   ";
        $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
        $sSqlTempTableSomatorio .= "      select r35_anousu, r35_mesusu, r35_regist, 'Z777'::char(4) as r35_rubric, sum(r35_valor) as r35_valor,0,'base', '13salario', r35_instit     ";
        $sSqlTempTableSomatorio .= "         from gerfs13                                                                                                                             ";
        $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r35_regist                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and ano       = r35_anousu                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and mes       = r35_mesusu                                                                                 ";
        $sSqlTempTableSomatorio .= "        where r35_rubric between 'R901' and 'R915'                                                                                                ";
        $sSqlTempTableSomatorio .= "        group by r35_anousu, r35_mesusu, r35_regist, r35_instit                                                                                   ";
        $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
        $sSqlTempTableSomatorio .= "      select r20_anousu, r20_mesusu, r20_regist, 'Z888'::char(4) as r20_rubric, sum(r20_valor) as r20_valor,0,'base', 'rescisao', r20_instit      ";
        $sSqlTempTableSomatorio .= "         from gerfres                                                                                                                             ";
        $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r20_regist                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and ano       = r20_anousu                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and mes       = r20_mesusu                                                                                 ";
        $sSqlTempTableSomatorio .= "        where r20_pd = 2                                                                                                                          ";
        $sSqlTempTableSomatorio .= "        group by r20_anousu, r20_mesusu, r20_regist, r20_instit                                                                                   ";
        $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
        $sSqlTempTableSomatorio .= "      select r20_anousu, r20_mesusu, r20_regist, 'Z999'::char(4) as r20_rubric, sum(r20_valor) as r20_valor,0,'base', 'rescisao', r20_instit      ";
        $sSqlTempTableSomatorio .= "         from gerfres                                                                                                                             ";
        $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r20_regist                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and ano       = r20_anousu                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and mes       = r20_mesusu                                                                                 ";
        $sSqlTempTableSomatorio .= "        where r20_pd = 1                                                                                                                          ";
        $sSqlTempTableSomatorio .= "        group by r20_anousu, r20_mesusu, r20_regist, r20_instit                                                                                   ";
        $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
        $sSqlTempTableSomatorio .= "      select r20_anousu, r20_mesusu, r20_regist, 'Z777'::char(4) as r20_rubric, sum(r20_valor) as r20_valor,0,'base', 'rescisao', r20_instit      ";
        $sSqlTempTableSomatorio .= "         from gerfres                                                                                                                             ";
        $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r20_regist                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and ano       = r20_anousu                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and mes       = r20_mesusu                                                                                 ";
        $sSqlTempTableSomatorio .= "        where r20_rubric between 'R901' and 'R915'                                                                                                ";
        $sSqlTempTableSomatorio .= "        group by r20_anousu, r20_mesusu, r20_regist, r20_instit                                                                                   ";
        $sSqlTempTableSomatorio .= "                                                                                                                                                  ";
        $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
        $sSqlTempTableSomatorio .= "      select r22_anousu, r22_mesusu, r22_regist, 'Z888'::char(4) as r22_rubric, sum(r22_valor) as r22_valor,0,'base', 'adiantamento', r22_instit  ";
        $sSqlTempTableSomatorio .= "         from gerfadi                                                                                                                             ";
        $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r22_regist                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and ano       = r22_anousu                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and mes       = r22_mesusu                                                                                 ";
        $sSqlTempTableSomatorio .= "        where r22_pd = 2                                                                                                                          ";
        $sSqlTempTableSomatorio .= "        group by r22_anousu, r22_mesusu, r22_regist, r22_instit                                                                                   ";
        $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
        $sSqlTempTableSomatorio .= "      select r22_anousu, r22_mesusu, r22_regist, 'Z999'::char(4) as r22_rubric, sum(r22_valor) as r22_valor,0,'base', 'adiantamento', r22_instit  ";
        $sSqlTempTableSomatorio .= "         from gerfadi                                                                                                                             ";
        $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r22_regist                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and ano       = r22_anousu                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and mes       = r22_mesusu                                                                                 ";
        $sSqlTempTableSomatorio .= "        where r22_pd = 1                                                                                                                          ";
        $sSqlTempTableSomatorio .= "        group by r22_anousu, r22_mesusu, r22_regist, r22_instit                                                                                   ";
        $sSqlTempTableSomatorio .= "      union all                                                                                                                                   ";
        $sSqlTempTableSomatorio .= "      select r22_anousu, r22_mesusu, r22_regist, 'Z777'::char(4) as r22_rubric, sum(r22_valor) as r22_valor,0,'base', 'adiantamento', r22_instit  ";
        $sSqlTempTableSomatorio .= "         from gerfadi                                                                                                                             ";
        $sSqlTempTableSomatorio .= "              inner join dados_servidor on matricula = r22_regist                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and ano       = r22_anousu                                                                                 ";
        $sSqlTempTableSomatorio .= "                                       and mes       = r22_mesusu                                                                                 ";
        $sSqlTempTableSomatorio .= "        where r22_rubric between 'R901' and 'R915'                                                                                                ";
        $sSqlTempTableSomatorio .= "        group by r22_anousu, r22_mesusu, r22_regist, r22_instit                                                                                   ";
        return $sSqlTempTableSomatorio;
    }

    /**
    * Sql para pegar a folha de pagamento.
    */
    function sql_folha_pagamento($ano, $mes) {
        $sSqlFolhaPagamento  = "   select ano,                                                                                                                                    ";
        $sSqlFolhaPagamento .= "          mes,                                                                                                                                    ";
        $sSqlFolhaPagamento .= "          matricula,                                                                                                                              ";
        $sSqlFolhaPagamento .= "          rubrica,                                                                                                                                ";
        $sSqlFolhaPagamento .= "          case when rh27_descr is not null then rh27_descr                                                                                        ";
        $sSqlFolhaPagamento .= "               when rubrica = 'Z999' then 'Total Bruto'                                                                                           ";
        $sSqlFolhaPagamento .= "               when rubrica = 'Z888' then 'Total Descontos'                                                                                       ";
        $sSqlFolhaPagamento .= "               when rubrica = 'Z777' then 'Descontos Obrigatórios'                                                                                ";
        $sSqlFolhaPagamento .= "          end as descr_rubrica,                                                                                                                   ";
        $sSqlFolhaPagamento .= "          valor,                                                                                                                                  ";
        $sSqlFolhaPagamento .= "          quantidade,                                                                                                                             ";
        $sSqlFolhaPagamento .= "          tiporubrica,                                                                                                                            ";
        $sSqlFolhaPagamento .= "          tipofolha,                                                                                                                              ";
        $sSqlFolhaPagamento .= "          instit                                                                                                                                  ";
        $sSqlFolhaPagamento .= "     from (                                                                                                                                       ";
        $sSqlFolhaPagamento .= "      select r14_anousu as ano,r14_mesusu as mes,r14_regist as matricula,r14_rubric as rubrica, r14_valor as valor, r14_quant as quantidade,      ";
        $sSqlFolhaPagamento .= "        case r14_pd when 1 then 'provento' when 2 then 'desconto' else 'base' end as tiporubrica, 'salario' as tipofolha, r14_instit as instit    ";
        $sSqlFolhaPagamento .= "        from gerfsal                                                                                                                              ";
        $sSqlFolhaPagamento .= "             inner join dados_servidor on matricula = r14_regist                                                                                  ";
        $sSqlFolhaPagamento .= "                                      and ano       = r14_anousu                                                                                  ";
        $sSqlFolhaPagamento .= "                                      and mes       = r14_mesusu                                                                                  ";
        $sSqlFolhaPagamento .= "      where r14_mesusu = {$mes}                                                                                                                   ";
        $sSqlFolhaPagamento .= "        and r14_anousu = {$ano}                                                                                                                   ";
        $sSqlFolhaPagamento .= "      union all                                                                                                                                   ";
        $sSqlFolhaPagamento .= "       select r48_anousu,r48_mesusu,r48_regist,r48_rubric, r48_valor, r48_quant,                                                                  ";
        $sSqlFolhaPagamento .= "         case r48_pd when 1 then 'provento' when 2 then 'desconto' else 'base' end, 'complementar', r48_instit                                    ";
        $sSqlFolhaPagamento .= "         from gerfcom                                                                                                                             ";
        $sSqlFolhaPagamento .= "              inner join dados_servidor on matricula = r48_regist                                                                                 ";
        $sSqlFolhaPagamento .= "                                       and ano       = r48_anousu                                                                                 ";
        $sSqlFolhaPagamento .= "                                       and mes       = r48_mesusu                                                                                 ";
        $sSqlFolhaPagamento .= "      where r48_mesusu = {$mes}                                                                                                                   ";
        $sSqlFolhaPagamento .= "        and r48_anousu = {$ano}                                                                                                                   ";
        $sSqlFolhaPagamento .= "      union all                                                                                                                                   ";
        $sSqlFolhaPagamento .= "       select r35_anousu,r35_mesusu,r35_regist,r35_rubric, r35_valor, r35_quant,                                                                  ";
        $sSqlFolhaPagamento .= "         case r35_pd when 1 then 'provento' when 2 then 'desconto' else 'base' end, '13salario', r35_instit                                       ";
        $sSqlFolhaPagamento .= "         from gerfs13                                                                                                                             ";
        $sSqlFolhaPagamento .= "              inner join dados_servidor on matricula = r35_regist                                                                                 ";
        $sSqlFolhaPagamento .= "                                       and ano       = r35_anousu                                                                                 ";
        $sSqlFolhaPagamento .= "                                       and mes       = r35_mesusu                                                                                 ";
        $sSqlFolhaPagamento .= "      where r35_mesusu = {$mes}                                                                                                                   ";
        $sSqlFolhaPagamento .= "        and r35_anousu = {$ano}                                                                                                                   ";
        $sSqlFolhaPagamento .= "      union all                                                                                                                                   ";
        $sSqlFolhaPagamento .= "       select r20_anousu,r20_mesusu,r20_regist,r20_rubric, r20_valor, r20_quant,                                                                  ";
        $sSqlFolhaPagamento .= "         case r20_pd when 1 then 'provento' when 2 then 'desconto' else 'base' end, 'rescisao', r20_instit                                        ";
        $sSqlFolhaPagamento .= "         from gerfres                                                                                                                             ";
        $sSqlFolhaPagamento .= "              inner join dados_servidor on matricula = r20_regist                                                                                 ";
        $sSqlFolhaPagamento .= "                                       and ano       = r20_anousu                                                                                 ";
        $sSqlFolhaPagamento .= "                                       and mes       = r20_mesusu                                                                                 ";
        $sSqlFolhaPagamento .= "      where r20_mesusu = {$mes}                                                                                                                   ";
        $sSqlFolhaPagamento .= "        and r20_anousu = {$ano}                                                                                                                   ";
        $sSqlFolhaPagamento .= "      union all                                                                                                                                   ";
        $sSqlFolhaPagamento .= "        select r22_anousu,r22_mesusu,r22_regist,r22_rubric, r22_valor, r22_quant,                                                                 ";
        $sSqlFolhaPagamento .= "         case r22_pd when 1 then 'provento' when 2 then 'desconto' else 'base' end, 'adiantamento', r22_instit                                    ";
        $sSqlFolhaPagamento .= "         from gerfadi                                                                                                                             ";
        $sSqlFolhaPagamento .= "              inner join dados_servidor on matricula = r22_regist                                                                                 ";
        $sSqlFolhaPagamento .= "                                       and ano       = r22_anousu                                                                                 ";
        $sSqlFolhaPagamento .= "                                       and mes       = r22_mesusu                                                                                 ";
        $sSqlFolhaPagamento .= "      where r22_mesusu = {$mes}                                                                                                                   ";
        $sSqlFolhaPagamento .= "        and r22_anousu = {$ano}                                                                                                                   ";
        $sSqlFolhaPagamento .= "      union all                                                                                                                                   ";
        $sSqlFolhaPagamento .= "       select anousu, mesusu, regist, rubrica, valor, quantidade, tiporubrica, tipofolha, instit                                                  ";
        $sSqlFolhaPagamento .= "         from somatorio                                                                                                                           ";
        $sSqlFolhaPagamento .= "      where mesusu = {$mes}                                                                                                                   ";
        $sSqlFolhaPagamento .= "        and anousu = {$ano}                                                                                                                   ";
        $sSqlFolhaPagamento .= "     ) as x                                                                                                                                       ";
        $sSqlFolhaPagamento .= "  left join rhrubricas on rubrica = rh27_rubric and instit = rh27_instit                                                                          ";
        $sSqlFolhaPagamento .= "  order by ano,mes,matricula,tipofolha, tiporubrica desc, rubrica;                                                                                ";
        return $sSqlFolhaPagamento;
    }

    /**
    * Sql para os recursos humanos.
    */
    function sql_recursos_humanos() {
        $sSqlRecursosHumanos  = " select h16_regist as servidor_id,                         ";
        $sSqlRecursosHumanos .= "        h12_assent,                                        ";
        $sSqlRecursosHumanos .= "        h12_descr as descricao,                            ";
        $sSqlRecursosHumanos .= "        h16_nrport as numero_portaria,                     ";
        $sSqlRecursosHumanos .= "        h16_atofic as ato_oficial,                         ";
        $sSqlRecursosHumanos .= "        h16_dtconc data_concessao,                         ";
        $sSqlRecursosHumanos .= "        h16_dtterm as data_termino,                        ";
        $sSqlRecursosHumanos .= "        h16_quant as quantidade_dias,                      ";
        $sSqlRecursosHumanos .= "        h16_histor as historico                            ";
        $sSqlRecursosHumanos .= "   from assenta                                            ";
        $sSqlRecursosHumanos .= "      inner join tipoasse       on h12_codigo = h16_assent ";
        $sSqlRecursosHumanos .= "  where exists (select 1                                   ";
        $sSqlRecursosHumanos .= "                  from dados_servidor                      ";
        $sSqlRecursosHumanos .= "                 where matricula = h16_regist              ";
        $sSqlRecursosHumanos .= "               )                                           ";
        return $sSqlRecursosHumanos;
    }

    /**
    * Consulta schemas antigos.
    */
    function consulta_schemas_antigos($numero_bases_antigas) {   
        return  "select distinct schema_name
            from information_schema.schemata
            where schema_name like 'bkp_transparencia_%'
            order by schema_name desc
            offset $numero_bases_antigas ";
    }

    /**
    * Acerta tabela empenhos_movimentacoes_exercicios.
    */
    function acerta_emp_mov_exer() {
        return  " INSERT INTO empenhos_movimentacoes_exercicios (empenho_id,exercicio)
            select distinct empenho_id,
                   extract( year from data) as exercicio
                       from empenhos_movimentacoes ";

    }

    function sql_tipo_instituicao() {
        return "select db21_tipoinstit from configuracoes.db_config where codigo = (select db_config.codigo
                                                                                    from configuracoes.db_config
                                                                                    where db_config.prefeitura is true)";
    }


?>
