<?php

use Classes\PostgresMigration;

class M8313VencimentoIssqnOptantesSimples extends PostgresMigration
{
    public function up()
    {
        $sSqlVencimentosIssqn =
        <<<EOL
            drop function if exists fc_issqn(integer,date,integer,date,boolean,boolean,integer,varchar,integer,integer);
            create or replace function fc_issqn(integer,date,integer,date,boolean,boolean,integer,varchar,integer,integer)
            returns varchar(200)
            as $$
            declare

            iInscr                    alias   for $1; -- inscricao que esta sendo calculada
            dDatahj                   alias   for $2; -- data do sistema
            iAnousu                   alias   for $3; -- ano do sistema
            dDtbaixa                  alias   for $4; -- data da baixa se estiver calculando baixa
            bRecalc                   alias   for $5; -- se e um recalculo
            bGeral                    alias   for $6; -- se e um calculo geral
            iInstit                   alias   for $7; -- instituicao
            sAtivs                    alias   for $8; -- relacao das atividades separadas por virgula. Exemplo: 1,2,3,4,5
            iTipoCalculo              alias   for $9; -- Tipo de calculo: 0 - Todos,
                                        --                                1 - ISSQN,
                                        --                                2 - Alvara
            iNumeroParcelasAlvara     alias   for $10;-- Quantidade de Parcelas em que o Alvare deve ser dividido

            iCalcfixvar               integer default 0;
            iDiasvctoCissqn           integer default 0;
            iConsiderarMesInicio      integer default 0;
            iMesFinal                 integer default 0;
            iDiaInicio                integer default 0;

            v_vencproc                integer default 0;
            v_quantexcluido           integer default 0;
            v_diasjasomados           integer default 0;
            v_anoatualservidor        integer;
            v_tipo_quant              integer;
            iTotalVencimentosCad      integer;
            v_anoiniciocalc           integer;
            v_mesinicio               integer;
            v_numprejapago            integer;
            v_uqtab                   integer;
            v_uqcad                   float8;
            v_ativprinc               integer;
            v_codvencadcalc           integer;
            v_codven                  integer;
            v_sequencia               integer;
            v_tabativ                 integer;
            v_ativtipo                integer;
            v_tipcalc                 integer;
            iInscrexiste              integer;
            v_quantativ               integer;
            v_numpre                  integer;
            v_numpar                  integer;
            v_numtot                  integer;
            v_numdig                  integer;
            v_numcgm                  integer;
            v_receita                 integer;
            v_codbco                  integer;
            iAux                      integer;
            v_diasdesdeinicio         integer;
            v_diasdestevcto           integer;
            iTotalDiasAno             integer;
            v_q81_rec                 integer;
            iQtdeVencProcessar        integer;
            v_totvenc                 integer default 0;
            iQtdParcelas              integer;
            iQtdParcelasPagas         integer;
            v_forcal                  integer;
            iDiasParaVencimento       integer default 0;
            iAnoCadastroEmpresa       integer default 0;
            iNumTot                   integer default 1;

            v_qprovisorio             float8 default 1;
            nValorParcela             float8;
            v_valor                   float8;
            v_valorgrav               float8 default 0;
            v_quant                   float8;
            v_base                    float8;
            v_valinflator             float8;
            v_q81_qini                float8;
            v_q81_qfim                float8;
            v_q81_val                 float8;
            nPercentualParcela        float8;

            nValorTotalPago           float8 default 0; -- total pago usado no caso de um recalculo
            nDescontoPagamentoParcela float8 default 0; -- desconto por parcela, e o valor a ser descontado por parcela no caso de um recalculo com pagamentos
            nPercPagoNovo             float8 default 0; -- percentual pago referente ao total do calculo, usado para calcular o desconto por parcela no caso de um recalculo com pagamento

            dDataCadastro             date;
            v_venc                    date;
            v_vencvar                 date;
            v_venccalc                date;
            iPrimeiroDiaAno           date;
            iUltimoDiaAno             date;
            v_dtvencano               date;
            dMaiorVencimentoCadvenc   date;
            dInicioAtividade          date;
            dInicioAtividadecalc      date;
            v_dtbase                  date;
            v_databaixa               date;
            dDtProporcionalidade      date;
            dVencimentoAtual          date;
            dDataInicialCadSimples    date;


            v_cep                     char(8);
            v_cepinstit               char(8);
            v_integr                  char(1);
            v_var                     char(1);
            v_codage                  char(5);
            v_numbco                  char(15);

            v_descrvariavel           varchar(50);
            v_descrtipcalc            varchar(40);
            v_descrcadcalc            varchar(40);
            sDescrProporcionalidade   varchar;

            v_text                    text;
            v_textexclui              text;
            v_textexclui2             text;
            v_manual                  text default '\n';
            tManual                   text;
            sSqlInsert                text;
            sManualCorrecao           text;
            sManualText               text;

            v_provisorio              boolean default false;
            v_cadcalc_var             boolean default false;
            v_cadcalc_fix             boolean default false;
            v_comcalculo              boolean default false;
            v_continuar               boolean default true;
            v_prim                    boolean;
            v_jagravou                boolean;
            lJaPassouUltVenc          boolean;
            bTemFixado                boolean default false; -- se tem valor fixado(varfixval)
            lProcessaParcVencidas     boolean default false;
            lProcessaParcela          boolean;
            lTabelasCriadas           boolean;
            lInscricaoMei             boolean default false;
            lOptanteSimples           boolean default false;

            v_record_ativ             record;
            v_record_tipcalc          record;
            v_record_cadvenc          record;
            v_record_cadcalc          record;
            v_record_ativprinc        record;
            v_record_maiorvalor       record;
            v_record_somatodos        record;
            v_record_variavel         record;
            v_numprejacalculado       record;
            v_record_excluir          record;
            rDebitos                  record;
            rParcelasAlvara           record;

            dtOperacao                varchar;
            iNumpreArquivoSimples     integer;

            lRaise                    boolean default false; -- variavel para debug
            lAbatimento               boolean default false;

            lCalculaVistoriasMEI      boolean default false;

            begin

            select to_char(fc_getsession('DB_datausu')::date, 'DD/MM/YYYY') into dtOperacao;

              if  dtOperacao is null then
                 RAISE EXCEPTION 'Erro: variavel de sessao DB_datausu nao declarado!';
              end if;

            if     iTipoCalculo = 1 then
               v_manual :=  '  ======================= Calculo de ISSQN          | Data de Calculo: ' || dtOperacao ||'  =========== \n';
            elsif  iTipoCalculo = 2 then
               v_manual :=  '  ======================= Calculo de Alvara         | Data de Calculo: ' || dtOperacao ||'  =========== \n';
            else
               v_manual :=  '  ======================= Calculo de ISSQN e Alvara | Data de Calculo: ' || dtOperacao ||'  =========== \n';
            end if;

            lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );

            perform fc_debug('INICIANDO CALCULO PARA INSCRICAO : '||iInscr||' EXERCICIO : '||iAnousu,lRaise,true,false);

            perform fc_debug('DATA DE BAIXA - '||dDtbaixa,lRaise,false,false);

            perform * from ativtipo
              where q80_ativ in ( select distinct q07_ativ from tabativ where q07_inscr = iInscr );
            if not found then
              return '24-Empresa sem tipo de calculo configurado!';
            end if;

            --
            -- Realizamos a confer√™ncia dos dados para sabermos se a inscri√ß√£o √© optante pelo SIMPLES
            --
            -- Caso seja optante n√£o ser√° calculado a taxa de alvar√° da empresa.
            --
            -- Deve possui cadastro na tabela meicgm...
            -- OU
            -- A empresa deve ser optante com:
            --  - A categoria 3 - MEI;
            --  - Data de in√≠cio do cadastro no simples menor ou igual a data de calculo;
            --  - N√£o pode estar com o cadastro do simples baixado (isscadsimplesbaixa)
            --

            perform *
               from meicgm
                    inner join issbase on issbase.q02_numcgm = meicgm.q115_numcgm
              where issbase.q02_inscr = iInscr;

            if found then
              lInscricaoMei = true;
            end if;

            perform *
               from isscadsimples
              where isscadsimples.q38_inscr     = iInscr
                and isscadsimples.q38_categoria = 3
                and isscadsimples.q38_dtinicial <= dDatahj
                and not exists ( select 1
                                   from isscadsimplesbaixa
                                  where isscadsimplesbaixa.q39_isscadsimples = isscadsimples.q38_sequencial
                                    and q39_dtbaixa <= dDatahj );
            if found then
              lInscricaoMei = true;
            end if;
            --
            -- FIM DA CONFER√äNCIA DE CALCULO DO MEI
            --

            -- Realizamos a confer√™ncia dos dados para sabermos se a inscri√ß√£o √© optante pelo SIMPLES
            -- Caso seja optante, a data de vencimento ser√° sempre o dia 20 do m√™s.
            select q38_dtinicial
              into dDataInicialCadSimples
              from isscadsimples
             where isscadsimples.q38_inscr     = iInscr
               and isscadsimples.q38_dtinicial <= dDatahj
               and not exists ( select 1
                                  from isscadsimplesbaixa
                                 where isscadsimplesbaixa.q39_isscadsimples = isscadsimples.q38_sequencial
                                   and q39_dtbaixa <= dDatahj );
            if found then
              lOptanteSimples = true;
            end if;
            -- FIM DA CONFER√äNCIA DE EMPRESAS OPTANTE PELO SIMPLES

            --
            -- Verificando par√¢metro Calcula Vistorias para MEI:
            --
            select y32_calculavistoriamei::boolean
              into lCalculaVistoriasMEI
              from parfiscal;

            select extract(year from q02_dtinic)
              into iAnoCadastroEmpresa
              from issbase
             where q02_inscr = iInscr;

            if iAnousu < iAnoCadastroEmpresa then
              return '24-N√£o pode ser feito calculo para exercicio menor que o ano de cadastramento da empresa.';
            end if;

            select fc_issqn_criatemptable(lRaise)
              into lTabelasCriadas;
            if lTabelasCriadas is false then
              return '24-Problema ao criar as tabelas temporarias. ';
            end if;

            for rDebitos in
              select q01_numpre
                from isscalc
               where q01_inscr  = iInscr
                 and q01_anousu = iAnousu
            loop

              -- Verifica se existe Pagamento Parcial para o d√©bito informado
              select fc_verifica_abatimento(1,rDebitos.q01_numpre)::boolean into lAbatimento;

              if lAbatimento then
                return '24-Opera√ß√£o Cancelada, D√©bito com Pagamento Parcial!';
              end if;

            end loop;


            sSqlInsert := '
            insert into ativs (q07_inscr,q07_perman,q07_seq,q07_calcula,q07_ativ,q03_descr,q07_datain,q07_datafi,q07_databx,q07_quant,q11_tipcalc)
                   select distinct
                          q07_inscr,
                          q07_perman,
                          q07_seq, \'*\'::char(1) as q07_calcula,
                          q07_ativ,
                          q03_descr,
                          q07_datain,
                          q07_datafi,
                          q07_databx,
                          q07_quant,
                          q11_tipcalc
                     from tabativ
                          left outer join tabativtipcalc on q11_inscr   = q07_inscr
                                                        and q11_seq     = q07_seq
                          inner join ativtipo            on q07_ativ    = q80_ativ
                          inner join tipcalc             on q80_tipcal  = q81_codigo
                          inner join ativid              on q07_ativ    = q03_ativ
                          inner join cadcalc             on q81_cadcalc = q85_codigo
                    where q07_inscr ='||  iInscr ||'
                      and q07_seq in ('||sAtivs||')
             union
                   select distinct
                          q07_inscr,
                          q07_perman,
                          q07_seq, \'*\'::char(1) as q07_calcula,
                          q07_ativ,
                          q03_descr,
                          q07_datain,
                          q07_datafi,
                          q07_databx,
                          q07_quant,
                          q11_tipcalc
                     from tabativ
                          left outer join tabativtipcalc on q11_inscr     = q07_inscr
                                                        and q11_seq       = q07_seq
                          inner join clasativ            on q82_ativ      = q07_ativ
                          inner join issbaseporte        on q45_inscr     = q07_inscr
                          inner join issportetipo        on q41_codclasse = q82_classe
                                                        and q41_codporte  = q45_codporte
                          inner join tipcalc             on q81_codigo    = q41_codtipcalc
                          inner join ativid              on q07_ativ      = q03_ativ
                          inner join cadcalc             on q81_cadcalc   = q85_codigo
                    where q07_inscr =  '|| iInscr ||'
                      and q07_seq in ('||sAtivs||')' ;

            execute sSqlInsert;

            select count(*) from ativs into v_sequencia;

            v_sequencia = 1;
            --
            -- Primeiro dia do ano para calculo
            --
            iPrimeiroDiaAno = to_char(iAnousu, '9999') || '-01-01';

            --
            -- Ultimo dia do ano para calculo
            --
            iUltimoDiaAno = to_char(iAnousu, '9999') || '-12-31';

            --
            -- Total de dias do ano
            --
            iTotalDiasAno  = iUltimoDiaAno::date - iPrimeiroDiaAno::date + 1;

            select q02_inscr
              from issbase
              into iInscrexiste
             where q02_inscr = iInscr;
            if iInscrexiste is null then
              return '11-INSCRICAO NAO CADASTRADA ';
            end if;

            select q02_dtinic,
                   q02_dtinic,
                   extract (month from q02_dtinic)
              from issbase
              into dInicioAtividade,
                   dDataCadastro,
                   v_mesinicio
             where q02_inscr = iInscr;

            if dInicioAtividade is null then

              select q02_dtinic,
                     extract (month from q02_dtinic)
                from issbase
                into dInicioAtividade,
                     v_mesinicio
               where q02_inscr = iInscr;

              if dInicioAtividade is null then
                select min(q07_datain),
                       extract (month from min(q07_datain))
                  into dInicioAtividade,
                       v_mesinicio
                  from tabativ
                 where (q07_datafi is null or q07_datafi >= dDatahj)
                   and (q07_databx is null or q07_databx >= dDatahj);

                if dInicioAtividade is null then
                  return '12-INSCRICAO SEM DATA DE INICIO CONFIGURADA! ';
                else
                  v_manual = v_manual || 'data de inicio da inscricao (baseada na menor data de inicio das atividades): ' || dInicioAtividade || '\n';
                end if;
              else

                v_manual = v_manual || 'data de inicio da inscricao (baseada na data de cadastramento): ' || dInicioAtividade || '\n';

              end if;

            else
              v_manual = v_manual || 'data de inicio da inscricao: ' || dInicioAtividade || '\n';
            end if;

            select extract (year from dDatahj) into v_anoatualservidor;

            perform fc_debug('inscr: '||iInscr||' - inicio: '||dInicioAtividade,lRaise,false,false);

            --*********************************** se calcula por area ou por quantidade de funcionarios ********************************************************
            --
            -- grande bloco que verifica a variavel v_quant, que ser√° utilizada para encontrar posteriormente o tipcalc a ser utilizado no calculo
            --
            select q60_campoutilcalc
              into v_tipo_quant
              from parissqn;

            --  se o campo q60_campoutilcalc for igual a 1 calcula pelo campo q30_area

            if v_tipo_quant is null then
              return '13-PARAMETRO DE QUANTIDADE PARA O CALCULO NAO CONFIGURADO NA TABELA PARISSQN';
            end if;

            --**************************************************************************************************************************************************

            select q04_vbase,
                   q04_calfixvar,
                   q04_diasvcto
              from cissqn
              into v_base,
                   iCalcfixvar,
                   iDiasvctoCissqn
             where cissqn.q04_anousu = iAnousu;

            if v_base = 0 or v_base is null then
              return '14-SEM VALOR BASE CADASTRADO NOS PARAMETROS ';
            end if;

            v_manual = v_manual || 'valor base configurado nos parametros: ' || v_base || '\n';

            select q04_dtbase
              from cissqn
              into v_dtbase
            where cissqn.q04_anousu = iAnousu;
            if v_dtbase is null then
              return '15-SEM DATA BASE CADASTRADA NOS PARAMETROS ';
            end if;
            v_manual = v_manual || 'data base configurada nos parametros: ' || v_dtbase || '\n';

            select distinct i02_valor
              from cissqn
              into v_valinflator
                   inner join infla on q04_inflat = i02_codigo
             where cissqn.q04_anousu = iAnousu
               and date_part('y',i02_data) = iAnousu;
            if v_valinflator is null then
              v_valinflator = 1;
            --      return 'valor do inflator nao configurado corretamente';
            end if;
            v_manual = v_manual || 'valor do inflator configurada nos parametros: ' || v_valinflator || '\n';

            perform fc_debug('valor do inflator : '||v_valinflator,lRaise,false,false);

            select q02_dtbaix
              from issbase
              into v_databaixa
             where q02_inscr = iInscr;
            if v_databaixa is not null then
              return '16-INSCRICAO JA BAIXADA ';
            end if;

            -- Q81_TIPO:
            -- 1 issqn
            -- 4 alvara
            -- 5 taxa de expediente
            select count(*) into v_quantativ from (
            select distinct q07_seq
              from tabativ
                   inner join ativtipo on ativtipo.q80_ativ = tabativ.q07_ativ
                   inner join tipcalc on q81_codigo = q80_tipcal
             where q81_tipo in (1,4,5)
               and q07_inscr = iInscr
               and (q07_datafi is null or q07_datafi >= dDatahj)
               and (q07_databx is null or q07_databx >= dDatahj)) as x;

            perform fc_debug('Total de atividades : '||v_quantativ,lRaise,false,false);

            select q07_inscr
              from tabativ
              into v_tabativ
                   inner join ativtipo on ativtipo.q80_ativ = tabativ.q07_ativ
             where q07_inscr = iInscr
               and q07_datain <= dDatahj;

            select q07_inscr
              from tabativ
              into v_ativtipo
                   inner join ativtipo on ativtipo.q80_ativ = tabativ.q07_ativ
             where q07_inscr = iInscr
               and (q07_datafi is null or q07_datafi >= dDatahj)
               and (q07_databx is null or q07_databx >= dDatahj);

            select q07_inscr
              from tabativ
              into v_tipcalc
                   inner join ativtipo on ativtipo.q80_ativ = tabativ.q07_ativ
                   inner join tipcalc  on q81_codigo = q80_tipcal
             where q81_tipo in (1,4,5)
               and q07_inscr = iInscr
               and q07_datain <= dDatahj;

            perform fc_debug('Data da baixa : '||dDtbaixa,lRaise,false,false);

            -- verifica quais os tipos de calculo para as atividades cadastradas para a inscricao

            select cep
              from db_config
              into v_cepinstit
             where codigo = iInstit;

            if v_cepinstit is null then
              return '17-PROBLEMAS COM A TABELA DB_CONFIG';
            end if;
            v_manual = v_manual || 'cep da instituicao: ' || v_cepinstit || '\n';

            select q02_cep
             from issbase
             into v_cep
            where q02_inscr = iInscr;

            v_manual = v_manual || 'cep da inscricao: ' || v_cep || '\n';

            v_manual = v_manual || '\n--- p r i m e i r a   e t a p a  -----\n';

            select distinct
                   q07_inscr
              into iAux
              from ativs;

            perform fc_debug('Inscricao tabela temporaria (ativs) : '||iAux,lRaise,false,false);

            -- ativs √© uma tabela tempor√°ria criada antes de chamar a funcao

            for v_record_ativ in execute 'select * from ativs where q07_inscr = ' || iInscr loop


              v_manual = v_manual || '\nprocessando atividade: ' || v_record_ativ.q07_ativ || ' - ' || v_record_ativ.q03_descr || ' - sequencia: ' || v_record_ativ.q07_seq || ' - inicio: ' || v_record_ativ.q07_datain || '\n';
              --
              -- sempre entra aqui porque ninguem utiliza o esquema da tabela
              -- tabativtipcalc (tipo de calculo especifico para aquela atividade daquela inscricao)
              --
              if v_record_ativ.q11_tipcalc is null then
                v_text = 'select distinct
                                 tipcalc.*,
                                 cadcalc.q85_outromun,
                                 cadcalc.q85_var,
                                 case
                                   when q81_tipo = 4 then
                                     ( select q83_codven
                                         from ativtipo ativtipo2
                                              inner join tipcalc    on ativtipo2.q80_tipcal = q81_codigo
                                              left  join tipcalcexe on q83_tipcalc = q81_codigo
                                                                   and q83_anousu  = (select extract(year from q02_dtinic)
                                                                                        from issbase
                                                                                       where q02_inscr = '||iInscr||')
                                        where ativtipo2.q80_ativ = ativtipo.q80_ativ and ativtipo2.q80_tipcal = ativtipo.q80_tipcal )
                                   else
                                     q83_codven
                                 end as q83_codven
                            from ativtipo
                                 inner join tipcalc    on q80_tipcal = q81_codigo
                                 left join tipcalcexe  on q83_tipcalc = q81_codigo
                                                      and q83_anousu = ' || iAnousu || '
                                 inner join cadcalc    on cadcalc.q85_codigo = tipcalc.q81_cadcalc
                           where q81_tipo in (1,4,5)
                             and q80_ativ = ' || v_record_ativ.q07_ativ || '
                         union
                          select tipcalc.*,
                                 cadcalc.q85_outromun,
                                 cadcalc.q85_var,
                                 case
                                   when q81_tipo = 4 then
                                     ( select q83_codven
                                         from tipcalc tipcalc2
                                              left  join tipcalcexe tipcalcexe2   on tipcalcexe2.q83_tipcalc = tipcalc2.q81_codigo
                                                                     and tipcalcexe2.q83_anousu  = (select extract(year from issbase2.q02_dtinic)
                                                                                          from issbase issbase2
                                                                                         where issbase2.q02_inscr = '||iInscr||')
                                              inner join cadcalc cadcalc2  on cadcalc2.q85_codigo = tipcalc2.q81_cadcalc
                                              inner join clasativ clasativ2  on clasativ2.q82_classe = issportetipo.q41_codclasse and clasativ2.q82_ativ = ' || v_record_ativ.q07_ativ || '
                                        where tipcalc2.q81_codigo = issportetipo.q41_codtipcalc )
                                   else
                                     q83_codven
                                 end as q83_codven
                            from issportetipo
                                 inner join issbaseporte on q45_inscr = ' || iInscr || '
                                 inner join tipcalc      on q41_codtipcalc = q81_codigo
                                 left  join tipcalcexe   on q83_tipcalc = q81_codigo
                                                        and q83_anousu = ' || iAnousu || '
                                 inner join cadcalc      on cadcalc.q85_codigo = tipcalc.q81_cadcalc
                                 inner join clasativ     on q82_classe = q41_codclasse
                           where q45_codporte = q41_codporte
                             and q81_tipo in (1,4,5)
                             and q82_ativ = ' || v_record_ativ.q07_ativ;

                v_textexclui = 'select distinct
                                       tipcalc.q81_cadcalc
                                  from ativtipo
                                       inner join tipcalc on q80_tipcal = q81_codigo
                                       inner join cadcalc on cadcalc.q85_codigo = tipcalc.q81_cadcalc
                                 where q81_tipo in (1,4,5)
                                   and q80_ativ = ' || v_record_ativ.q07_ativ || '
                               union
                                select tipcalc.q81_cadcalc
                                  from issportetipo
                                       inner join issbaseporte on q45_inscr = ' || iInscr || '
                                       inner join tipcalc      on q41_codtipcalc = q81_codigo
                                       inner join cadcalc      on cadcalc.q85_codigo = tipcalc.q81_cadcalc
                                       inner join clasativ     on q82_classe = q41_codclasse
                                 where q45_codporte = q41_codporte
                                   and q81_tipo in (1,4,5)
                                   and q82_ativ = ' || v_record_ativ.q07_ativ;
              else
                v_text = 'select tipcalc.*,
                                 q85_outromun,
                                 cadcalc.q85_var
                            from tipcalc
                                 left outer join cadcalc on cadcalc.q85_codigo = tipcalc.q81_cadcalc
                           where q81_tipo in (1,4,5)
                             and q81_codigo = ' || v_record_ativ.q11_tipcalc;
              end if;

              -- deletando calculo atual da inscricao do ano
              v_manual = v_manual || 'deletando os calculos antigos da inscricao no ano - apenas os que nao serao recalculados neste calculo\n';

              v_textexclui2 = 'select q01_numpre,
                                      q01_cadcal
                                 from isscalc
                                where q01_cadcal not in ( ' || v_textexclui || ')
                                  and q01_inscr  = ' || iInscr || '
                                  and q01_anousu = ' || iAnousu;

              -- limpa o financeiro do que foi selecionado pelo usuario antes de calcular

              for v_record_excluir in execute v_textexclui2 loop

                perform fc_debug('Numpre : '||v_record_excluir.q01_numpre,lRaise,false,false);
                -- verifica se n√£o esta no arrecant
                select k00_numpre
                  from arrecant
                  into v_numprejapago
                 where k00_numpre = v_record_excluir.q01_numpre;
                if v_numprejapago is null then

                  v_manual = v_manual || '     deletando numpre ' || v_record_excluir.q01_numpre || ' - calculo: ' || v_record_excluir.q01_cadcal || '\n';
                  delete from arrecad where k00_numpre = v_record_excluir.q01_numpre;
                  delete from isscalc where q01_numpre = v_record_excluir.q01_numpre;
                  v_quantexcluido = v_quantexcluido + 1;

                else
                  perform fc_debug('Numpre ja pago : '||v_record_excluir.q01_numpre,lRaise,false,false);
                end if;

              end loop;

              v_manual = v_manual || 'quantidade de calculos excluidos: ' || v_quantexcluido || ' \n';

              perform fc_debug('Atividade : '||v_record_ativ.q07_ativ,lRaise,false,false);

              perform fc_debug('',lRaise,false,false);
              perform fc_debug('Sql buscando os tipos de calculo : '||v_text,lRaise,false,false);
              perform fc_debug('',lRaise,false,false);

              -- para cada atividade retornada com seu tipo de calculo...


              for v_record_tipcalc in execute v_text loop


                 select *
                   into v_quant, tManual
                   from fc_buscaquantidadeempresa( cast(iInscr                 as integer),
                                                   cast(iAnousu                as integer),
                                                   cast(v_tipo_quant           as integer),
                                                   cast(v_record_ativ.q07_ativ as integer)
                                                 );
                 perform fc_debug('========== Excluindo registros da porcalculo e "do": '||tManual,lRaise,false,false);


                 perform fc_debug('========== QUANTIDADES : '||tManual,lRaise,false,false);

                 v_manual = v_manual||tManual;

                 if v_quant = 0 and v_tipo_quant = 3 then
                   return '30 - Erro buscando as quantidades para o calculo por pontua√ß√£o. Verifique o cadastro de pontua√ß√£o das Classes, Areas, Zonas e Empregados';
                 end if;


                perform fc_debug('========== Tipo de calculo : '||v_record_tipcalc.q81_codigo||'-'||v_record_tipcalc.q81_descr||' - Quantidade : '||v_quant,lRaise,false,false);

                v_continuar = true;

                perform fc_debug('p r o c e s s a n d o  tipo de calculo: ' || v_record_tipcalc.q81_codigo || ' - ' || v_record_tipcalc.q81_descr || ' - gera: ' || v_record_tipcalc.q81_gera,lRaise,false,false);

                if  extract(year from dInicioAtividade )::integer = iAnousu then
                  v_q81_rec  = v_record_tipcalc.q81_recexe;
                  v_q81_qini = v_record_tipcalc.q81_qiexe;
                  v_q81_qfim = v_record_tipcalc.q81_qfexe;
                  v_q81_val  = v_record_tipcalc.q81_valexe;
                else
                  v_q81_rec  = v_record_tipcalc.q81_recpro;
                  v_q81_qini = v_record_tipcalc.q81_qipro;
                  v_q81_qfim = v_record_tipcalc.q81_qfpro;
                  v_q81_val  = v_record_tipcalc.q81_valpro;
                end if;

                perform fc_debug('Quantidade : '||coalesce(v_quant,0)||' Entre : '||coalesce(v_q81_qini,0)||' e '||coalesce(v_q81_qfim,0),lRaise,false,false);

                if coalesce(v_quant,0) >= coalesce(v_q81_qini,0) and coalesce(v_quant,0) <= coalesce(v_q81_qfim,999999) then

                  perform fc_debug('Dentro do if da quantidade gera : '||v_record_tipcalc.q81_gera,lRaise,false,false);
                  perform fc_debug('Ano de inicio de atividades : '||to_number(substr(dInicioAtividade,1,4),'99999'),lRaise,false,false);

                  if v_record_tipcalc.q81_gera = 1 and to_number(substr(dInicioAtividade,1,4),'99999') < iAnousu then
                    perform fc_debug('nao vai processar...',lRaise,false,false);
                    v_manual = v_manual || '\n';
                  else

                    perform fc_debug('entrou no gera 2 do if - verificando pagamentos ',lRaise,false,false);

                    v_manual = v_manual || 'verificando pagamentos\n';

                    for v_numprejacalculado in
                      select q01_numpre
                        from isscalc
                       where q01_anousu = iAnousu
                         and q01_inscr  = iInscr
                         and q01_cadcal = v_record_tipcalc.q81_cadcalc
                         and q01_recei  = v_q81_rec
                    loop

                      perform fc_debug('procurando numpre : '||v_numprejacalculado.q01_numpre,lRaise,false,false);
                      v_manual = v_manual || 'processando numpre: ' || v_numprejacalculado.q01_numpre || '\n';

                      -- trocado ordem de verifica√ß√£o de arrecad, arrecant para arrecant, arrecad
                      select k00_numpre
                        from arrecant
                        into v_numprejapago
                       where k00_numpre = v_numprejacalculado.q01_numpre;

                      perform fc_debug('q01_numpre: '|| v_numprejacalculado.q01_numpre,lRaise,false,false);

                      if v_numprejacalculado.q01_numpre is not null then

                        perform fc_debug('v_numprejacalculado.q01_numpre is not null - v_numprejapago: '||v_numprejapago,lRaise,false,false);

                        if v_numprejapago is null then

                          v_manual = v_manual || 'numpre em aberto\n';

                          if dDtbaixa is not null and v_record_tipcalc.q85_var is true then
                            v_manual = v_manual || 'se data da baixa preenchido e variavel, deleta do arrecad e issvar\n';

                            perform fc_debug('Deletando do arrecad e issvar',lRaise,false,false);

                            delete from arrecad where k00_numpre = v_numprejacalculado.q01_numpre and k00_numpar >= to_number(substr(dDtbaixa,6,2),'999') + 1;

                            perform *
                               from issvarlev
                                    inner join issvar on issvar.q05_codigo = issvarlev.q18_codigo
                              where q05_numpre = v_numprejacalculado.q01_numpre
                                and q05_numpar >= to_number(substr(dDtbaixa,6,2),'999') + 1;
                            if found then
                              return '28 - EMPRESA JA POSSUI LEVANTAMENTO FISCAL PARA COMPETENCIA : '||(to_number(substr(dDtbaixa,6,2),'999') + 1)||'/'||iAnousu ;
                            end if;

                            delete from issvarnotas
                             using issvar
                             where issvar.q05_codigo  = issvarnotas.q06_codigo
                               and issvar.q05_numpre  = v_numprejacalculado.q01_numpre
                               and issvar.q05_numpar >= to_number(substr(dDtbaixa,6,2),'999') + 1;

                            delete from issvar
                             where q05_numpre = v_numprejacalculado.q01_numpre
                               and q05_numpar >= to_number(substr(dDtbaixa,6,2),'999') + 1;

                          elsif dDtbaixa is not null then

                            v_manual = v_manual || 'se data da baixa preenchido e nao variavel, insere no isscalcant e deleta do isscalc e arrecad\n';

                            perform fc_debug('Deletando do arrecad e isscalv',lRaise,false,false);

                            insert into isscalcant select * from isscalc where q01_numpre = v_numprejacalculado.q01_numpre;
                            delete from isscalc where q01_numpre = v_numprejacalculado.q01_numpre;
                            delete from arrecad where k00_numpre = v_numprejacalculado.q01_numpre;
                          end if;

                        else

                          perform fc_debug('Numpre pago.',lRaise,false,false);

                          v_manual = v_manual || 'numpre pago\n';
                          v_comcalculo = true;

                        end if;

                        if v_numprejapago is null then

                          select k00_numpre
                          from arrecad
                          into v_numprejapago
                          where k00_numpre = v_numprejacalculado.q01_numpre;

                          if v_numprejapago is not null then

                            if bRecalc is false then
                              return '18-INSCRICAO JA CALCULADA E RECALCULO NAO PASSADO COMO PARAMETRO';
                            end if;

                            perform fc_debug('recalculo',lRaise,false,false);

                            v_manual = v_manual || 'recalculo\n';
                            v_comcalculo = true;
                          end if;

                        end if;

                      end if;

                    end loop;

                    perform fc_debug('saiu do procura pagamentos - continuar : '||( case when v_continuar is true then 'true' else 'false' end ),lRaise,false,false);

                    if v_continuar then

                        -- se true, busca quantidade do tabativ, senao default 1
                      if v_record_tipcalc.q81_uqtab is false then

                        perform fc_debug('uqtab false',lRaise,false,false);

                        v_uqtab = 1;
                        v_manual = v_manual || 'quantidade utilizada da tabela para calculo (utilizada default do sistema):' || v_uqtab || '\n';
                      else

                        perform fc_debug('uqtab true',lRaise,false,false);

                        if v_record_ativ.q07_quant = 0 then
                          v_uqtab = 1;
                        else
                          v_uqtab = v_record_ativ.q07_quant;
                        end if;
                        v_manual = v_manual || 'quantidade utilizada para calculo (baseada na quantidade da atividade lancada): ' || v_uqtab || '\n';
                      end if;

                      -- se true, busca quantidade do issquant, senao default 1
                      if v_record_tipcalc.q81_uqcad is false then
                        v_uqcad = 1;
                      else
                        select q30_mult
                          from issquant
                          into v_uqcad
                         where issquant.q30_inscr = iInscr
                           and issquant.q30_anousu = iAnousu;
                        if v_uqcad is null then
                          return '19-MULTIPLICADOR NAO LANCADO PARA ESTA INSCRICAO';
                        end if;

                      end if;

                      if v_record_tipcalc.q81_integr is true then
                        v_integr = '1';
                      else
                        v_integr = '0';
                      end if;
                      v_manual = v_manual || 'integral: (1 = sim - 0 = nao): ' || v_integr || '\n';

                      select case when q85_var is true then '1' else '0' end as q85_var
                      from cadcalc
                      into v_var
                      where cadcalc.q85_codigo = v_record_tipcalc.q81_cadcalc;
                      if v_var is null then
                        return '20-NAO DEFINIDO NO CADASTRO DE CALCULO SE VARIAVEL OU NAO';
                      end if;

                      select q85_forcal
                      from cadcalc
                      into v_forcal
                      where cadcalc.q85_codigo = v_record_tipcalc.q81_cadcalc;
                      if v_forcal is null then
                        return '21-nAO DEFINIDO NO CADASTRO DE CALCULO A FORMA DE CALCULO';
                      end if;
                      v_manual = v_manual || 'forma de calculo (1 = atividade principal - 2 = atividade com maior valor - 3 = soma do valor das atividades):' || v_forcal || '\n';

                      select q85_perman
                        from cadcalc
                        into v_provisorio
                       where cadcalc.q85_codigo = v_record_tipcalc.q81_cadcalc;

                      if v_provisorio is true and v_record_ativ.q07_perman is false then

                        v_qprovisorio = v_record_tipcalc.q81_percprovis;
                        v_manual = v_manual || 'provisorio: vai acrescer ' || v_qprovisorio || ' por centro no valor calculado\n';

                        perform fc_debug('Provisorio -- '||v_qprovisorio,lRaise,false,false);

                      else

                        v_qprovisorio = 1;

                        perform fc_debug('Nao provisorio',lRaise,false,false);

                      end if;

                      perform fc_debug('inserindo na tabela tudo... sequencia:'||v_sequencia,lRaise,false,false);
                      perform fc_debug('q81_val: '||v_q81_val||' - v_base: '|| v_base||' - uqtab: '||v_uqtab||' - uqcad: '||v_uqcad||' - qprovisorio: '||v_qprovisorio||' - valinflator: '||v_valinflator,lRaise,false,false);

                      if v_record_tipcalc.q83_codven is null then
                        return '25-VENCIMENTO NAO ENCONTRADO NO CADASTRO DE TIPO DE CALCULO';
                      else
                        v_codven = v_record_tipcalc.q83_codven;
                      end if;
                      perform fc_debug('q81_codigo: '||v_record_tipcalc.q81_codigo||' - q81_cadcalc: '||v_record_tipcalc.q81_cadcalc||' - vencimento: '||v_codven,lRaise,false,false);

                      --
                      -- Se empresa for optante pelo simples nao deve ser efetuado
                      -- calculo de alvara nem de taxa de expediente
                      --
                      if lInscricaoMei then

                        if v_record_tipcalc.q81_cadcalc = 1 then
                          perform fc_debug('Inscricao optante pelo MEI, nao calculando alvara.',lRaise,false,false);
                          continue;
                        end if;

                        -- Se par√¢metro Calcula Vistorias para MEI: estiver N√ÉO ent√£o ignora taxa de expediente

                        if lCalculaVistoriasMEI = false then

                          if v_record_tipcalc.q81_tipo = 5 then -- tipo de c√°lculo for taxa

                            perform fc_debug('Inscricao optante pelo MEI, nao calculando TAXA DE EXPEDIENTE.',lRaise,false,false);
                            continue;
                          end if;

                        end if;

                      end if;


                      insert into
                        tudo values (iAnousu,
                                     iInscr,
                                     v_record_ativ.q07_ativ,
                                     v_record_tipcalc.q81_codigo,
                                     v_record_tipcalc.q81_cadcalc,
                                     v_base,
                                     v_record_tipcalc.q81_recexe,
                                     v_record_tipcalc.q81_recpro,
                                     coalesce(v_quant,0),
                                     v_record_tipcalc.q81_qiexe,
                                     v_record_tipcalc.q81_qfexe,
                                     v_uqtab,
                                     v_uqcad,
                                     v_forcal,
                                     v_codven,
                                     v_integr,
                                     v_record_tipcalc.q81_tippro,
                                     v_q81_val * v_base * v_uqtab * v_uqcad * v_qprovisorio * v_valinflator,
                                     v_q81_val * v_qprovisorio * v_valinflator,
                                     v_record_ativ.q07_datain,
                                     coalesce( v_record_ativ.q07_datafi,(iAnousu||'-12-31')::date ),
                                     v_var,
                                     v_record_tipcalc.q81_gera,
                                     v_sequencia);

                          v_sequencia = v_sequencia + 1;

                    end if;

                  end if;

                end if;

              end loop;
              perform fc_debug('Terminou atividade',lRaise,false,false);

            end loop;

            --
            -- SEGUNDA FASE DO CALCULO
            -- na fase anterior a rotina insere registros na tabela tudo e nela √© que se baseia daqui para frente para saber o que calcular
            --

            perform fc_debug('--------------------------------------------------------------------------------------------------------',lRaise,false,false);
            perform fc_debug('',lRaise,false,false);
            perform fc_debug('SEGUNDA FASE DO CALCULO',lRaise,false,false);
            perform fc_debug('',lRaise,false,false);
            perform fc_debug('Na fase anterior a rotina insere registros na tabela tudo e nela √© que se baseia daqui para frente para saber o que calcular',lRaise,false,false);


            if    iTipoCalculo = 1  then
              perform fc_debug('Excluindo registros de Alvar√°', lRaise, false, false);
              DELETE FROM tudo where cadcalc not in (2,3);
            elsif iTipoCalculo = 2  then

              perform fc_debug('Excluindo registros de ISSQN', lRaise, false, false);
              DELETE FROM tudo where cadcalc not in (1,4,9);
            end if;


            select count(*) into iAux from tudo;

            v_manual = v_manual || '\nregistros processados na etapa de tipos de calculo: ' || iAux || '\n';
            v_manual = v_manual || '\n---- s e g u n d a  e t a p a  -----\n';
            v_manual = v_manual || 'agrupando por cadastro de calculo' || '\n';

            perform fc_debug('Quantidade de registros tabela tudo : '||iAux,lRaise,false,false);


            --
            -- for na tabela tudo com os tipos de calculo a utilizar
            -- veja o detalhe do group by, que faz com que apenas um cadcalc (ALVARA/ISSQN FIXO/ISSQN VARIAVEL) seja utilizado por calculo
            -- nessa fase o sistema cria registros na tabela porcalculo que ser√° utilizada nessa fase
            --
            for v_record_cadcalc in select cadcalc, forcal, var from tudo group by cadcalc, forcal, var loop

              select q85_descr
                into v_descrcadcalc
                from cadcalc
               where q85_codigo = v_record_cadcalc.cadcalc;

              v_manual = v_manual || '   processando calculo ' || v_record_cadcalc.cadcalc || ' - ' || v_descrcadcalc || '\n';

              perform fc_debug('Var : '||v_record_cadcalc.var||' cadcalc : '||v_record_cadcalc.cadcalc,lRaise,false,false);

              -- se for variavel
              if v_record_cadcalc.var = '1' then

                v_manual = v_manual || '      variavel\n';

                -- pode ser fixado por inscricao
                for v_record_variavel in select * from tudo where tudo.cadcalc = v_record_cadcalc.cadcalc limit 1 loop

                  perform fc_debug('Inserindo na tabela tudo fixado por inscricao',lRaise,false,false);

                  execute 'insert into porcalculo values ('
                  || iAnousu || ','
                  || iInscr  || ','
                  || v_base || ','
                  || v_record_variavel.tipcalc || ','
                  || v_record_variavel.cadcalc || ','
                  || v_record_variavel.forcal  || ','
                  || v_record_variavel.codven  || ','
                  || v_record_variavel.integr  || ','
                  || '''' || v_record_variavel.tipopro || '''' || ','
                  || '''' || v_record_variavel.inicio  || '''' || ','
                  || '''' || coalesce( v_record_variavel.final,(iAnousu||'-12-31')::date )   || '''' || ','
                  || 0 || ','
                  || v_record_variavel.valori || ','
                  || 0 || ','
                  || '''' || v_record_variavel.var     || '''' || ','
                  || v_record_variavel.gera || ','
                  || v_record_variavel.seq
                  || ');';

                end loop;

              -- se NAO for variavel
              else

                perform fc_debug('Inserindo na tabela tudo pela atividade principal',lRaise,false,false);

                v_manual = v_manual || '      nao variavel\n';

                -- se for pela atividade principal
                if v_record_cadcalc.forcal = 1 then
                  v_manual = v_manual || '         calculando pela atividade principal\n';

                  select q07_ativ
                    into v_ativprinc
                    from ativprinc
                         inner join tabativ on q07_inscr = q88_inscr and q07_seq = q88_seq
                   where q88_inscr = iInscr;
                  if v_ativprinc is null then
                    return '22-SEM ATIVIDADE PRINCIPAL CADASTRADA PARA ESTA INSCRICAO ';
                  end if;

            --        for v_record_ativprinc in select * from tudo where tudo.cadcalc = v_record_cadcalc.cadcalc and tudo.ativ = v_ativprinc loop
            --        DESCOBRIR QUEM E PORQUE COMENTARAM A LINHA ACIMA
            --        PORQUE PELA LOGICA DEVERIA UTILIZAR A LINHA COMENTADA

                  for v_record_ativprinc in select * from tudo where tudo.cadcalc = v_record_cadcalc.cadcalc limit 1 loop

                    v_manual = v_manual || '            inserindo na tabela de tipos de calculo a processar - tipcalc: ' || v_record_ativprinc.tipcalc || ' - cadcalc: ' || v_record_ativprinc.cadcalc || '\n';
                    execute 'insert into porcalculo values ('
                    || iAnousu || ','
                    || iInscr  || ','
                    || v_base || ','
                    || v_record_ativprinc.tipcalc || ','
                    || v_record_ativprinc.cadcalc || ','
                    || v_record_ativprinc.forcal  || ','
                    || v_record_ativprinc.codven  || ','
                    || v_record_ativprinc.integr  || ','
                    || '''' || v_record_ativprinc.tipopro || '''' || ','
                    || '''' || v_record_ativprinc.inicio  || '''' || ','
                    || '''' || coalesce( v_record_ativprinc.final, (iAnousu||'-12-31')::date )   || '''' || ','
                    || v_record_ativprinc.valor   || ','
                    || v_record_ativprinc.valori  || ','
                    || 0 || ','
                    || '''' || v_record_ativprinc.var     || '''' || ','
                    || v_record_ativprinc.gera    || ','
                    || v_record_ativprinc.seq
                    || ');';

                  end loop;

                end if;

                -- pela atividade que gerou o maior valor
                if v_record_cadcalc.forcal = 2 then
                  v_manual = v_manual || 'calculando pela atividade que gerou o maior valor\n';

                  for v_record_maiorvalor in select * from tudo where tudo.cadcalc = v_record_cadcalc.cadcalc order by valor desc limit 1 loop

                    perform fc_debug('Inserindo na tabela porcalculo pela atividade de maior valor valor : '||v_record_maiorvalor.valor||' - tipo de calculo: '||v_record_maiorvalor.tipcalc||' - vencimento: '||v_record_maiorvalor.codven,lRaise,false,false);

                    execute 'insert into porcalculo values ('
                    || iAnousu || ','
                    || iInscr  || ','
                    || v_base || ','
                    || v_record_maiorvalor.tipcalc || ','
                    || v_record_maiorvalor.cadcalc || ','
                    || v_record_maiorvalor.forcal  || ','
                    || v_record_maiorvalor.codven  || ','
                    || v_record_maiorvalor.integr  || ','
                    || '''' || v_record_maiorvalor.tipopro || '''' || ','
                    || '''' || v_record_maiorvalor.inicio  || '''' || ','
                    || '''' || coalesce( v_record_maiorvalor.final, (iAnousu||'-12-31')::date )   || '''' || ','
                    || v_record_maiorvalor.valor   || ','
                    || v_record_maiorvalor.valori  || ','
                    || 0 || ','
                    || '''' || v_record_maiorvalor.var     || '''' || ','
                    || v_record_maiorvalor.gera    || ','
                    || v_record_maiorvalor.seq
                    || ');';

                  end loop;

                end if;

                -- pela soma de todos os valores calculados
                -- ainda nao implementado totalmente
                -- ou seja, nao funciona ainda...
                -- TESTADORES DEVEM UTILIZAR ESSA FORMULA DE CALCULO NOS TESTES
                if v_record_cadcalc.forcal = 3 then
                  v_manual = v_manual || 'calculando pela soma de todos os valores\n';

                  for v_record_somatodos in select * ,
                                                   (select sum(valor) as somatotal
                                                      from tudo
                                                     where tudo.cadcalc = v_record_cadcalc.cadcalc) as somatotal
                                              from tudo
                                             where tudo.cadcalc = v_record_cadcalc.cadcalc loop

                    select q85_codven
                    from cadcalc
                    into v_codvencadcalc
                    where q85_codigo = v_record_cadcalc.cadcalc;
                    if v_codvencadcalc is null then
                      return '23-SEM VENCIMENTO PADRAO NO CADASTRO DE VENCIMENTOS ';
                    end if;

                    execute 'insert into porcalculo values ('
                    || iAnousu || ','
                    || iInscr  || ','
                    || v_base || ','
                    || v_record_somatodos.tipcalc || ','
                    || v_record_cadcalc.cadcalc || ','
                    || v_record_cadcalc.forcal  || ','
                    || v_codvencadcalc            || ','
                    || v_record_somatodos.integr  || ','
                    || '''' || v_record_somatodos.tipopro || '''' || ','
                    || '''' || v_record_somatodos.inicio  || '''' || ','
                    || '''' || coalesce( v_record_somatodos.final, (iAnousu||'-12-31')::date)   || '''' || ','
                    || v_record_somatodos.somatotal || ','
                    || v_record_somatodos.valori  || ','
                    || 0 || ','
                    || '''' || v_record_somatodos.var     || '''' || ','
                    || v_record_somatodos.gera    || ','
                    || v_record_somatodos.seq
                    || ');';

                  end loop;

                end if;

              end if;

            end loop;
            -- fim do for do select na tabela tudo, que gera os registros na porcalculo

            v_manual = v_manual || '\n---- t e r c e i r a  e t a p a  -----\n';
            v_manual = v_manual || 'agrupando por tipo de vencimento e preparando para calcular\n';

            perform fc_debug('--------------------------------------------------------------------------------------------------------',lRaise,false,false);
            perform fc_debug('',lRaise,false,false);
            perform fc_debug('TERCEIRA ETAPA DO CALCULO ',lRaise,false,false);
            perform fc_debug('',lRaise,false,false);
            perform fc_debug('agrupando por tipo de vencimento e preparando para calcular',lRaise,false,false);

            for v_record_cadcalc in select porcalculo.*,
                                           tipcalc.q81_excedenteativ
                                      from porcalculo
                                           inner join tipcalc on q81_codigo = porcalculo.tipcalc
            loop

              if v_record_cadcalc.q81_excedenteativ > 0 then
                v_valor = v_record_cadcalc.valor;

                perform fc_debug('Seq : '||v_record_cadcalc.seq||' Valor : '||v_valor,lRaise,false,false);

                v_valor = v_valor + (v_valor * v_record_cadcalc.q81_excedenteativ * (v_quantativ - 1));
                update porcalculo set valor = v_valor where seq = v_record_cadcalc.seq ;

                perform fc_debug('Apos calcular 30% por atividade excedente - Seq : '||v_record_cadcalc.seq||' Valor : '||v_valor,lRaise,false,false);

              end if;
            end loop;

            perform fc_debug('Descobrindo de calcula fixo ou variavel ',lRaise,false,false);

            --
            -- Verifica se calcula fixo ou variavel
            --
            for v_record_cadvenc in select distinct cadcalc from porcalculo
            loop

              if v_record_cadvenc.cadcalc = 2 then

                v_cadcalc_fix = true;

              elsif v_record_cadvenc.cadcalc = 3 then

                v_cadcalc_var = true;

              end if;

            end loop;

            if v_cadcalc_fix = true and v_cadcalc_var = true and iCalcfixvar = 1 then

              perform fc_debug('Inscricao com dois (2) calculos fixo/var, excluido calculo fixo',lRaise,false,false);
              delete from porcalculo where cadcalc = 2;
              v_manual = v_manual || '\n inscricao com dois (2) calculos fixo/variavel, calculado somente variavel \n';

            end if;

            if v_cadcalc_fix = true and v_cadcalc_var = true and iCalcfixvar = 2 then

              perform fc_debug('Inscricao com dois (2) calculos fixo/var, excluido calculo variavel',lRaise,false,false);

              delete from porcalculo where cadcalc = 3;
              v_manual = v_manual || '\n inscricao com dois (2) calculos fixo/variavel, calculado somente fixo \n';

            end if;

            perform fc_debug('select trazendo os vencimentos para gerar as proporcionalidades',lRaise,false,false);

            for v_record_cadvenc in select codven from porcalculo group by codven
            loop

              v_manual = v_manual || 'processando vencimento ' || v_record_cadvenc.codven || '\n';

              perform fc_debug('Codigo do cadastro de vencimentos : '||v_record_cadvenc.codven,lRaise,false,false);

              for v_record_cadcalc in select distinct tipcalc, cadcalc, valor, integr, inicio, final, tipopro, seq from porcalculo where codven = v_record_cadvenc.codven loop

                perform fc_debug('Tipo de calulo : '||v_record_cadcalc.tipcalc||' Cadcalc : '||v_record_cadcalc.cadcalc||'seq :'||v_record_cadcalc.seq,lRaise,false,false);

                select q81_abrev
                into v_descrtipcalc
                  from tipcalc
                 where q81_codigo = v_record_cadcalc.tipcalc;

                select q85_descr
                  into v_descrcadcalc
                  from cadcalc
                 where q85_codigo = v_record_cadcalc.cadcalc;

                v_manual = v_manual || 'processando tipcalc: ' || v_record_cadcalc.tipcalc || ' - ' || v_descrtipcalc || ' - cadcalc: ' || v_record_cadcalc.cadcalc || ' - ' || v_descrcadcalc || '\n';

                v_valor = v_record_cadcalc.valor;
                v_manual = v_manual || 'valor: ' || v_valor || '\n';

                -- se √© para calcular com proporcionalidade
                if v_record_cadcalc.integr = '0' then

                  perform fc_debug('Valor sem proporcionalidade : '||v_valor,lRaise,false,false);

                  v_manual = v_manual || '   nao integral = proporcional ' || ' inicio: ' || v_record_cadcalc.inicio || ' - ano atual ' || iAnousu || '\n';

                  -- soh calcula integralidade se ano do inicio da atividade for igual ao atual ou for calculo de baixa
                  if extract(year from v_record_cadcalc.inicio)::integer = iAnousu or dDtbaixa is not null then
                    --
                    -- Calculo da proporcionalidade
                    --
                    if dDtbaixa is null then
                      dDtProporcionalidade := v_record_cadcalc.final;
                    else
                      dDtProporcionalidade := dDtbaixa;
                    end if;

                    perform fc_debug('Tipo   - '||v_record_cadcalc.tipopro,lRaise,false,false);
                    perform fc_debug('Inicio - '||v_record_cadcalc.inicio,lRaise,false,false);
                    perform fc_debug('Final  - '||dDtProporcionalidade,lRaise,false,false);

                    --
                    -- Funcao fc_issqn_proporcionalidade
                    --   retorna o valor proporcional ao periodo de atividade do exercio e a descricao do tipo de proporcionalidade
                    --
                    select rnValorProporcional,rsTipoProporcionalidade
                      into v_valor,sDescrProporcionalidade
                      from fc_issqn_proporcionalidade(v_record_cadcalc.valor::numeric,v_record_cadcalc.tipopro::varchar,v_record_cadcalc.inicio::date,dDtProporcionalidade::date,iAnousu::integer,dDtbaixa::date);

                    v_manual = v_manual ||sDescrProporcionalidade||' \n';

                  end if;

                  perform fc_debug('Valor com a proporcionalide : '||v_valor,lRaise,false,false);

                  v_manual = v_manual || 'valor ja calculado a proporcionalidade: ' || v_valor || '\n';

                else

                  v_manual = v_manual || 'integral\n';

                end if;

                update porcalculo set valorintegr = v_valor  where seq = v_record_cadcalc.seq ;

              end loop;

            end loop;

            select count(*)
              into iAux
              from porcalculo;

            v_manual = v_manual || '\n---- q u a r t a  e t a p a  -----\n';
            v_manual = v_manual || 'total de calculos que o sistema vai processar: ' || iAux || '\n';

            if iAux = 0 then
              return '24-NENHUM CALCULO EFETUADO!';
            end if;

            --
            -- QUARTA FASE - GERANDO FINANCEIRO
            --
            perform fc_debug('--------------------------------------------------------------------------------------------------------',lRaise,false,false);
            perform fc_debug('',lRaise,false,false);
            perform fc_debug('QUARTA FASE DO CALCULO (GERANDO FINANCEIRO) ',lRaise,false,false);
            perform fc_debug('Quantidade de registros tabela porcalculo : '||iAux,lRaise,false,false);
            perform fc_debug('',lRaise,false,false);
            perform fc_debug('--------------------------------------------------------------------------------------------------------',lRaise,false,false);

            /* for nos calculo da inscricao que esta sendo calculada */

            perform fc_debug('Percorrendo os calculo da inscricao que esta sendo calculada',lRaise,false,false);

            dInicioAtividade := dDataCadastro;

            for v_record_cadcalc in select * from porcalculo loop

              select q81_abrev
                from tipcalc
                into v_descrtipcalc
              where q81_codigo = v_record_cadcalc.tipcalc;

              select q85_descr
              from cadcalc
              into v_descrcadcalc
              where q85_codigo = v_record_cadcalc.cadcalc;

              v_manual = v_manual || '\nprocessando calculo ' || v_record_cadcalc.cadcalc || ' - ' || v_descrcadcalc ||  ' - tipo de calculo: ' || v_record_cadcalc.tipcalc || ' - ' || v_descrtipcalc || '\n';

              -- data de baixa preenchida e √© variavel
              -- resumindo, √© para nao fazer nada se for vari√°vel e calculo para baixa

              if dDtbaixa is not null and v_record_cadcalc.var = '1' then

                perform fc_debug('Com data de baixa passada como parametro: ' || dDtbaixa || ' e variavel: nao calcula',lRaise,false,false);
                v_manual = v_manual || 'com data de baixa passada como parametro: ' || dDtbaixa || ' e variavel: nao calcula\n';
              -- senao
              else

                select q01_numpre
                  from isscalc
                  into v_numpre
                 where q01_anousu = iAnousu
                   and q01_inscr = iInscr
                   and q01_cadcal = v_record_cadcalc.cadcalc;

                if v_numpre is null then

                  perform fc_debug('Sem calculo para o exercicio ',lRaise,false,false);
                  v_comcalculo = false;

                else

                  perform fc_debug('Com calculo para o exercicio numpre : '||v_numpre,lRaise,false,false);
                  v_comcalculo = true;

                end if;

                if v_comcalculo is false then
                  v_manual = v_manual || 'calculo novo\n';

                  select nextval('numpref_k03_numpre_seq')
                    into v_numpre;

                  perform fc_debug('Calculo Novo ',lRaise,false,false);
                  perform fc_debug('Novo numpre : '||v_numpre,lRaise,false,false);

                  if v_numpre is null then
                    return '25-ERRO DO PROCESSAR SEQUENCIA DO NUMPRE';
                  end if;

                else

                  v_manual = v_manual || 'calculo ja existe... utilizar o mesmo numpre\n';
                  select q01_numpre
                    from isscalc
                    into v_numpre
                    where q01_anousu = iAnousu
                      and q01_inscr = iInscr
                      and q01_cadcal = v_record_cadcalc.cadcalc;

                end if;

                v_numpar = 1;

                select max(q82_parc)
                  into v_numtot
                  from cadvenc
                       inner join cadvencdesc on q82_codigo = q92_codigo
                 where cadvenc.q82_codigo = v_record_cadcalc.codven;

                if v_record_cadcalc.var = '1' then
                  v_numtot = 12;
                else

                  if v_numtot is null then
                    v_numtot = 0;
                  end if;

                  if bGeral is false then
                    v_numtot = 1;
                  end if;
                end if;

                v_manual = v_manual || 'total de parcelas baseado no vencimento: ' || v_numtot || '\n';

                perform fc_debug('Codigo do Vencimento encontrado : '||v_record_cadcalc.codven,lRaise,false,false);
                perform fc_debug('Numpre : '||v_numpre||' Parcela : '||v_numpar||' Numtot : '||v_numtot||' Cadcalc : '||v_record_cadcalc.cadcalc,lRaise,false,false);

                select fc_digito(v_numpre,v_numpar,v_numtot)
                  into v_numdig;

                if v_numdig is null then
                  return '26-ERRO DO PROCESSAR FUNCAO DE CALCULO DO DIGITO VERIFICADOR';
                end if;

                select k15_codbco,
                       k15_codage
                  into v_codbco,
                       v_codage
                  from cadvencdescban
                       inner join cadban on cadvencdescban.q93_cadban = cadban.k15_codigo
                where q93_codigo = v_record_cadcalc.codven;

                if v_codbco is null then
                  v_codbco = 0;
                end if;

                if v_codage is null then
                  v_codage = 0;
                end if;

                select q02_numcgm
                  into v_numcgm
                  from issbase
                 where q02_inscr = iInscr;
                if v_numcgm is null then
                  return '27-CGM DA INSCRICAO : '||iInscr||' NAO ENCONTRADO DO CADASTRO DO CGM';
                end if;

                perform fc_debug('Inicio : '||v_record_cadcalc.inicio||' Anousu : '||iAnousu,lRaise,false,false);

                if iAnousu = to_number(substr(v_record_cadcalc.inicio,1,4),'99999') then

                  v_manual = v_manual || 'Ano atual igual ao ano de inicio da atividade: ' || iAnousu || '\n';

                  perform fc_debug('Ano atual igual ao ano de inicio',lRaise,false,false);

                  select recexe
                    into v_receita
                    from tudo
                   where tudo.seq = v_record_cadcalc.seq;
                else

                  v_manual = v_manual||'Ano atual diferente do ano de inicio da atividade: ' || iAnousu || '\n';
                  perform fc_debug('Ano atual diferente ao ano de inicio',lRaise,false,false);
                  select recpro
                    into v_receita
                    from tudo
                   where tudo.seq = v_record_cadcalc.seq;

                end if;

                v_prim = false;

                /**
                 * Se for variavel
                 */
                if v_record_cadcalc.var = '1' then
                  v_manual = v_manual || 'variavel\n';

                  perform fc_debug('Calculando variavel -- vencimento : '||v_record_cadcalc.codven,lRaise,false,false);

                 -- se for baixa
                  perform fc_debug('',lRaise,false,false);
                  perform fc_debug('COMECANDO A PROCESSAR OS VENCIMENTOS (PELO CADASTRO DE VENCIMENTOS CADVENC) ',lRaise,false,false);
                  perform fc_debug('',lRaise,false,false);

                  /* este for calcula pelo cadvenc todas as parcelas pelo select do porcalculo(todos os calculos da inscricao) */
                  for v_record_cadvenc in select * from cadvenc
                                                   inner join cadvencdesc on q82_codigo = q92_codigo
                                             where cadvenc.q82_codigo = v_record_cadcalc.codven
                                             order by q82_parc
                  loop

                    perform fc_debug('Processando vencimentos ',lRaise,false,false);
                    perform fc_debug('PARCELA : '||v_record_cadvenc.q82_parc||' VENCIMENTO : '||v_record_cadvenc.q82_venc,lRaise,false,false);
                    perform fc_debug('1 -- Deletando do arrecad numpre : '||v_numpre||' numpar : '||v_record_cadvenc.q82_parc,lRaise,false,false);

                    delete from arrecad
                     where k00_numpre = v_numpre
                       and k00_numpar = v_record_cadvenc.q82_parc;

                    if to_number(substr(v_record_cadvenc.q82_venc,1,4)||substr(v_record_cadvenc.q82_venc,6,2),'999999') >
                       to_number(substr(v_record_cadcalc.inicio,1,4)||substr(v_record_cadcalc.inicio,6,2),'999999') then

                      v_manual = v_manual || 'vencimento do cadastro de vencimentos: ' || v_record_cadvenc.q82_venc || ' maior ou igual a data de inicio da atividade mais 1 mes\n';

                      select count(*)
                        into iAux
                        from arreinscr
                       where k00_numpre = v_numpre
                         and k00_inscr = iInscr;

                      if iAux = 0 then

                        v_manual = v_manual || 'inserindo numpre no arreinscr: ' || v_numpre || '\n';
                        insert into arreinscr values (v_numpre,iInscr);
                      end if;

                      /* se nao for primeiro calculo do exercicio */
                      if v_prim is false then

                        if v_comcalculo is true then

                          perform fc_debug('Deletando do isscalc numpre : '||v_numpre,lRaise,false,false);
                          v_manual = v_manual || 'deletando numpre do isscalc e arrecad: ' || v_numpre || '\n';
                          delete from isscalc where q01_numpre = v_numpre;

                        end if;

                        insert into isscalc values (iAnousu,iInscr,v_record_cadcalc.cadcalc,v_receita,v_numpre,v_record_cadcalc.valori / v_valinflator);
                        insert into numpres values (v_numpre);
                        v_prim = true;

                      end if;

                      v_valor         := v_record_cadcalc.valori / v_valinflator;
                      v_valorgrav     := 0;
                      v_descrvariavel := 'arrecadacao de issqn variavel nao fixado';
                      v_manual        := v_manual || 'valor: ' || v_valor || '\n';

                      /* verifica se tem valor fixado lancado */
                      select q34_valor
                        into v_valorgrav
                        from varfix
                             inner join varfixval on varfix.q33_codigo = varfixval.q34_codigo
                       where varfix.q33_inscr    = iInscr
                         and varfixval.q34_numpar = v_record_cadvenc.q82_parc;

                      if v_valorgrav is not null then

                        v_descrvariavel = 'arrecadacao de issqn variavel fixado';
                        bTemFixado = true;
                      else

                        v_valorgrav     = 0;
                        v_descrvariavel = 'arrecadacao de issqn variavel nao fixado';
                      end if;

                      perform fc_debug('Tipo de valor : '||v_descrvariavel ,lRaise,false,false);

                      v_numpar = v_record_cadvenc.q82_parc;
                      v_manual = v_manual || 'parcela: ' || v_numpar || '\n';

                      select q05_codigo
                        into iAux
                        from issvar
                             inner join arreinscr on q05_numpre = arreinscr.k00_numpre
                       where q05_ano   = iAnousu
                         and q05_mes   = v_numpar
                         and k00_inscr = iInscr
                         and q05_valor > 0;

                      v_vencvar = v_record_cadvenc.q82_venc;

                      --Se For optante pelo simples, altera o dia do vencimento para dia 20
                      if dDataInicialCadSimples < v_vencvar  and lOptanteSimples ='t' then
                        v_vencvar = to_char(v_vencvar, 'YYYY-MM')||'-20';
                      end if;

                      perform fc_debug('Vencimento variavel : '||v_vencvar,lRaise,false,false);


                      select q05_numpre
                        from issvar
                        into v_numprejapago
                             inner join arrecant on q05_numpre = arrecant.k00_numpre and q05_numpar = arrecant.k00_numpar
                             inner join arreinscr on arrecant.k00_numpre = arreinscr.k00_numpre
                      where q05_ano   = iAnousu
                        and q05_mes   = v_numpar
                        and k00_inscr = iInscr;

                      perform fc_debug('Numpre ja pago '||v_numprejapago||' Numpar : '||v_numpar||' Anousu : '||iAnousu,lRaise,false,false);

                      select q05_numpre
                        into iNumpreArquivoSimples
                        from issvar
                             inner join issarqsimplesregissvar on q68_issvar = q05_codigo
                             inner join arreinscr on q05_numpre = arreinscr.k00_numpre
                      where q05_ano   = iAnousu
                        and q05_mes   = v_numpar
                        and k00_inscr = iInscr;

                      perform fc_debug('Numpre no arquivo do simples '||iNumpreArquivoSimples||' Numpar : '||v_numpar||' Anousu : '||iAnousu,lRaise,false,false);

                      if v_numprejapago is null and iNumpreArquivoSimples is null  then

                        perform fc_debug('2 -- deletando do arrecad numpre : '||v_numpre||' numpar : '||v_numpar,lRaise,false,false);

                        delete from arrecad where k00_numpre = v_numpre and k00_numpar = v_numpar ;

                        perform * from fc_statusdebitos(v_numpre,v_numpar) where rtstatus = 'PAGO' or rtstatus = 'CANCELADO'  limit 1;
                        if found then
                          continue;
                        end if;

                        perform * from issvardiv
                                  inner join issvar    on q05_codigo = q19_issvar
                                  inner join arreinscr on k00_numpre = q05_numpre
                            where issvar.q05_ano       = iAnousu
                              and issvar.q05_mes       = v_numpar
                              and arreinscr.k00_inscr  = iInscr ;

                        if found then
                          return '28 - INSCRICAO COM CALCULO IMPORTADO PARA DIVIDA';
                        end if;

                        perform fc_debug('deletando do issvar Ano : '||iAnousu||' Mes : '||v_numpar||' Inscricao : '||iInscr,lRaise,false,false);
                        perform *
                           from arreinscr
                                inner join issvar    on issvar.q05_numpre    = arreinscr.k00_numpre
                                inner join issvarlev on issvarlev.q18_codigo = issvar.q05_codigo
                          where issvar.q05_ano       = iAnousu
                            and issvar.q05_mes       = v_numpar
                            and arreinscr.k00_inscr  = iInscr ;
                        if found then
                          return '28 - EMPRESA JA POSSUI LEVANTAMENTO FISCAL PARA COMPETENCIA : '||v_numpar||'/'||iAnousu ;
                        end if;

                       delete from issvarnotas
                        using issvar
                        where issvar.q05_codigo = issvarnotas.q06_codigo
                          and issvar.q05_ano    = iAnousu
                          and issvar.q05_mes    = v_numpar ;

                        delete from issvar
                         using arreinscr
                         where issvar.q05_ano       = iAnousu
                           and issvar.q05_mes       = v_numpar
                           and arreinscr.k00_inscr  = iInscr
                           and arreinscr.k00_numpre = issvar.q05_numpre ;

                        delete from informacaodebito
                         where informacaodebito.k163_numpre = v_numpre
                           and informacaodebito.k163_numpar = v_numpar;

                        insert into issvar (q05_codigo, q05_numpre, q05_numpar, q05_valor, q05_ano, q05_mes, q05_histor, q05_aliq, q05_bruto)
                                    values (nextval('issvar_q05_codigo_seq'), v_numpre, v_numpar, v_valorgrav, iAnousu, v_numpar, v_descrvariavel, v_valor, 0);

                        perform fc_debug('INSERT NUMERO 1 NO ARRECAD (issvar)',lRaise,false,false);
                        perform fc_debug('Numpre : '||v_numpre||' Numpar : '||v_numpar||' Valor : '||round(v_valorgrav,2)||' Vencimento : '||v_vencvar||' Tipo : '||v_record_cadvenc.q92_tipo,lRaise,false,false);

                        insert into arrecad (k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numpre, k00_numpar, k00_numtot, k00_numdig, k00_tipo)
                             values (v_numcgm,v_vencvar,v_receita,v_record_cadvenc.q82_hist,round(v_valorgrav,2),v_vencvar,v_numpre,v_numpar,v_numtot,v_numdig,v_record_cadvenc.q92_tipo);

                        if v_codbco != 0 and v_comcalculo is false then

                          select fc_numbco(v_codbco,v_codage) into v_numbco;
                          /*
                              Comentado insert na arrebanco conforme solicitado pelo Paulo e Dalpozzo, pois nao faz
                              o menor sentido gerar arrebanco no calculo de issqn / alvara
                           */
                          --insert into arrebanco values (v_numpre,v_numpar,v_codbco,v_codage,v_numbco,'');

                        end if;

                      else
                        perform fc_debug('Ja pago',lRaise,false,false);
                      end if;

                      v_numpar = v_numpar + 1;

                    end if;

                  end loop;

                  perform fc_debug('FIM DO PROCESSAMENTO DO VARIAVEL',lRaise,false,false);

                else  -- SE NAO FOR VARIAVEL

                  perform fc_debug('',lRaise,false,false);
                  perform fc_debug('--------------------- P R O C E S S A N D O   (  N A O  )  V A R I A V E L  ---------------------',lRaise,false,false);
                  perform fc_debug('vencimento: '||v_record_cadcalc.codven,lRaise,false,false);
                  perform fc_debug('',lRaise,false,false);

                  v_manual = v_manual || 'processando com base no cadastro de vencimentos\n';

                  lJaPassouUltVenc   = false;
                  v_jagravou         = false;
                  iQtdeVencProcessar = 0;

                  -- busca quantas parcelas vai calcular e registrar no arrecad (variavel iQtdeVencProcessar)
                  select count(*)
                    into v_totvenc
                    from cadvencdesc
                         inner join cadvenc on q82_codigo = q92_codigo
                   where cadvencdesc.q92_codigo = v_record_cadcalc.codven ;

                  select count(*)
                    into iQtdeVencProcessar
                    from cadvencdesc
                         inner join cadvenc on q82_codigo = q92_codigo
                   where cadvencdesc.q92_codigo = v_record_cadcalc.codven
                     and ( case
                             when cadvencdesc.q92_formacalcparcvenc = 1
                               then case when q82_venc >= dInicioAtividade then true else false end
                             when cadvencdesc.q92_formacalcparcvenc = 3
                               then q82_venc >= dDatahj and q82_venc >= dInicioAtividade
                             else
                               case
                                 when cadvenc.q82_calculaparcvenc is true
                                   then true
                                 else
                                   q82_venc >= dDatahj and q82_venc >= dInicioAtividade
                               end
                           end );
                     -- esse case tem a finalidade de processar ou nao parcelas
                     -- vencidas de acordo com os parametros do cadastro de
                     -- vencimentos(cadvencdescm,cadvenc)
                  perform fc_debug('Pesquisando quantidade de vencimentos no periodo de atividade',lRaise,false,false);
                  perform fc_debug('Data inicial : '||coalesce(dInicioAtividade,'2999-01-01'::date)    ,lRaise,false,false);
                  perform fc_debug('Data final   : '||iUltimoDiaAno ,lRaise,false,false);
                  perform fc_debug('Quantidade de vencimentos encontrada : '||iQtdeVencProcessar||''    ,lRaise,false,false);

                  -- utiliza a variavel iTotalVencimentosCad, que e o total de vencimentos para mais abaixo saber em que parcela jogar os centavos de diferenca de arredondamento
                  -- utiliza a variavel dMaiorVencimentoCadvenc para no caso da empresa iniciar depois do ultimo vencimento, calcular tudo em uma parcela e jogar no vencimento 31-12
                  select max(cadvenc.q82_venc), coalesce(max(cadvencdesc.q92_diasvcto),0), count(*)
                    into dMaiorVencimentoCadvenc, iDiasParaVencimento, iTotalVencimentosCad
                    from cadvencdesc
                         inner join cadvenc on q82_codigo = q92_codigo
                   where cadvencdesc.q92_codigo = v_record_cadcalc.codven;

                  -- passando conteudo da variavel do cissqn para cadvencdesc
                  iDiasvctoCissqn := iDiasParaVencimento;

                  perform fc_debug('Quantidade de vencimentos a processar : '||iQtdeVencProcessar,  lRaise,false,false);
                  perform fc_debug('Dias para o vencimento(q92_diasvcto)  : '||iDiasParaVencimento, lRaise,false,false);
                  perform fc_debug('Maior vencimento do cadastro          : '||dMaiorVencimentoCadvenc||' Data atual : '||dDatahj,lRaise,false,false);

                  select count(*), sum(k00_valor)
                    into iQtdParcelasPagas, nValorTotalPago
                    from ( select k00_numpre, k00_numpar, sum(k00_valor) as k00_valor
                             from arrecant
                            where k00_numpre = v_numpre
                         group by k00_numpre, k00_numpar ) as x;

                  perform fc_debug('Quantidade de parcelas pagas : '||coalesce(iQtdParcelasPagas,0)||' Valor total pago : '||coalesce(nValorTotalPago,0),lRaise,false,false);

                  begin

                    select case when count(*) = 1 then
                             1
                           else
                             ( count(*) - iQtdParcelasPagas )
                           end as qtdparcelas,

                           case when count(*) = 1 then
                             100
                           else
                             ( 100 / ( count(*) - iQtdParcelasPagas ) )
                           end as percpago

                      into iQtdParcelas, nPercPagoNovo
                      from cadvencdesc
                           left outer join cadvenc on q82_codigo = q92_codigo
                     where cadvencdesc.q92_codigo = v_record_cadcalc.codven
                       and ( case
                             when cadvencdesc.q92_formacalcparcvenc = 1
                               then case when q82_venc >= dInicioAtividade then true else false end
                             when cadvencdesc.q92_formacalcparcvenc = 3

                               then q82_venc >= dDatahj and q82_venc >= dInicioAtividade
                             else
                               case
                                 when cadvenc.q82_calculaparcvenc is true
                                   then true
                                 else
                                   q82_venc >= dDatahj and q82_venc >= dInicioAtividade
                               end
                           end ) ;

                   exception

                     when division_by_zero then
                       nPercPagoNovo := 0;

                   end;

                  if iQtdParcelasPagas = 0 then
                    nPercPagoNovo := 0;
                  end if;

                  /*
                   * Quando a quantidade total de parcelas for negativo, significa que  o calculo n√£o possui mais
                   * parcelas em aberto, todas est√£o abertas. logo, o total de parcelas devera ser 1
                   */
                  if iQtdParcelas < 0 then
                    iQtdParcelas = 1;
                  end if;

                  if nPercPagoNovo < 0 then
                    nPercPagoNovo = 100;
                  end if;

                  nDescontoPagamentoParcela := coalesce( ( nValorTotalPago / iQtdParcelas ) ,0);

                  perform fc_debug(' PROCURANDO PAGAMENTOS : ',lRaise,false,false);
                  perform fc_debug('--------------------------------------------------------------------------------------------',lRaise,false,false);
                  perform fc_debug(' TOTAL PAGO           : '||coalesce( nValorTotalPago, 0)           ,lRaise,false,false);
                  perform fc_debug(' PARCELAS PAGAS       : '||coalesce( iQtdParcelasPagas, 0)         ,lRaise,false,false);
                  perform fc_debug(' PARCELAS A CALCULAR  : '||coalesce( iQtdParcelas, 0)              ,lRaise,false,false);
                  perform fc_debug(' DESCONTO POR PARCELA : '||coalesce( nDescontoPagamentoParcela, 0) ,lRaise,false,false);
                  perform fc_debug(' PERCENTUAL NOVO      : '||coalesce( nPercPagoNovo, 0)             ,lRaise,false,false);
                  perform fc_debug('--------------------------------------------------------------------------------------------',lRaise,false,false);

                  -- sempre deve deletar o calculo anterior no caso de encontrar um
                  perform fc_debug('DELETANDO DO ARRECAD NUMPRE : '||v_numpre,lRaise,false,false);
                  delete from arrecad where k00_numpre = v_numpre;

                  v_vencproc := 0;

                  -- Loop que gera/processa financeiro
                  for v_record_cadvenc in select *
                                            from cadvencdesc
                                                 left join cadvenc on q82_codigo = q92_codigo
                                           where cadvencdesc.q92_codigo = v_record_cadcalc.codven
                                        order by q82_parc
                  loop

                    perform fc_debug('------------------------------------------------------------------------------------------',lRaise,false,false);
                    perform fc_debug('Processando Vencimento : '||v_record_cadvenc.q82_venc||' Parcela : '||v_record_cadvenc.q82_parc||' Inicio : '||v_record_cadcalc.inicio,lRaise,false,false);
                    perform fc_debug('------------------------------------------------------------------------------------------',lRaise,false,false);

                    /**
                     * Guardar o vencimento atual do cadvenc
                     */
                    dVencimentoAtual := v_record_cadvenc.q82_venc;

                    if dVencimentoAtual is null then
                      dVencimentoAtual := dDatahj;
                    end if;

                    /**
                     * Se a quantidade de vencimentos a processar for igual a zero
                     */
                    if iQtdeVencProcessar = 0 then

                      -- soma dias para o vencimento na data do ultimo vencimento
                      if iDiasvctoCissqn > 0 then

                        dVencimentoAtual := ( dDatahj + iDiasvctoCissqn )::date;
                        perform fc_debug('Trocou o vencimento para : '||dVencimentoAtual,lRaise,false,false);
                      else

                        -- se mesmo somando os dias para vencimento o debito continuar vencido joga para 31/12
                        dVencimentoAtual := cast(to_char(iAnousu,'9999')||'-12-31' as date);
                        perform fc_debug('Trocou o vencimento para ultimo dia do ano : '||dVencimentoAtual,lRaise,false,false);
                      end if;

                    end if;

                    perform fc_debug('Vencimento apos processamento das regras : '||dVencimentoAtual,lRaise,false,false);

                    /**
                     * Variavel para controlar a forma para calculo de parcelas vencidas
                     */
                    lProcessaParcVencidas := ( case
                                                 when v_record_cadvenc.q92_formacalcparcvenc = 1
                                                   then true
                                                 when v_record_cadvenc.q92_formacalcparcvenc = 3
                                                   then v_record_cadvenc.q82_venc between dDatahj and iUltimoDiaAno
                                                 else
                                                   case
                                                     when v_record_cadvenc.q82_calculaparcvenc is true
                                                       then true
                                                     else
                                                       v_record_cadvenc.q82_venc between dDatahj and iUltimoDiaAno
                                                   end
                                               end );

                    perform fc_debug('Processando parcelas vencidas(lProcessaParcVencidas) ? '||(case when lProcessaParcVencidas is true then 'SIM' else 'NAO' end),lRaise,false,false);

                    v_vencproc       = v_vencproc + 1;
                    lProcessaParcela = false;

                    perform fc_debug('Vencimento do cadvenc : '||dVencimentoAtual||' Maior Vencimento : '||dMaiorVencimentoCadvenc,lRaise,false,false);

                    -- se vencimento do cadvenc do registro atual do for maior que o maximo vencimento do cadvenc
                    if v_record_cadvenc.q82_venc > dMaiorVencimentoCadvenc then

                      perform fc_debug('',lRaise,false,false);
                      perform fc_debug('Vencimento do cadastro de vencimentos maior que o maximo vencimento, pasando lJaPassouUltVenc para true',lRaise,false,false);
                      perform fc_debug('',lRaise,false,false);
                      lJaPassouUltVenc = true;
                    end if;

                    perform fc_debug('Proximo vencimento : '||v_vencproc||'  Total de vencimentos : '||iTotalVencimentosCad||' Passa: '||( case when lProcessaParcela is true then 'true' else 'false' end ),lRaise,false,false);

                    perform fc_debug('Data da baixa em branco',lRaise,false,false);
                    lProcessaParcela = true;

                    if extract( year from v_record_cadcalc.inicio) <> iAnousu then

                      perform fc_debug('Ano de inicio diferente do atual ',lRaise,false,false);
                      lProcessaParcela = true;
                      v_numtot         = v_totvenc;

                    else

                      perform fc_debug('Ano de inicio igual do atual ',lRaise,false,false);
                      if v_record_cadvenc.q82_venc >= dInicioAtividade or dVencimentoAtual is null or iQtdeVencProcessar = 0 or lProcessaParcVencidas is true then

                        perform fc_debug('Vencimento: '||dVencimentoAtual||' maior ou igual a inicio: '||dInicioAtividade||' ou vencimento is null ',lRaise,false,false);
                        lProcessaParcela = true;

                      else

                        perform fc_debug('Passando lProcessaParcela para FALSE -- Vencimento: '||dVencimentoAtual||' menor que inicio: '||dInicioAtividade,lRaise,false,false);
                        lProcessaParcela = false;

                      end if;
                      v_numtot = iQtdeVencProcessar;

                    end if;

                    if dDtbaixa is not null and dVencimentoAtual > dDtbaixa then

                      perform fc_debug('Data de baixa : '||dDtbaixa,lRaise,false,false);
                      perform fc_debug('1 - Passando lProcessaParcela para false',lRaise,false,false);
                    end if;

                    if dDatahj > dVencimentoAtual and lProcessaParcVencidas is false then

                      perform fc_debug('Inicio maior que data de vencimento e processar parcelas vencidas NAO',lRaise,false,false);
                      lProcessaParcela = false;
                    end if;

                    perform fc_debug('----------------------------------------------------------------------------------------------',lRaise,false,false);
                    perform fc_debug('Total de vencimentos     : '||iTotalVencimentosCad ,lRaise,false,false);
                    perform fc_debug('Vecimentos do cadastro   : '||v_vencproc,lRaise,false,false);
                    perform fc_debug('Qtd parcelas a calcular  : '||v_totvenc,lRaise,false,false);
                    perform fc_debug('Ja passou ult vencimento : '||(case when lJaPassouUltVenc is true then 'SIM' else 'NAO' end),lRaise,false,false);
                    perform fc_debug('Inicio                   : '||v_record_cadcalc.inicio,lRaise,false,false);
                    perform fc_debug('Vencimento do Cadastro   : '||dVencimentoAtual,lRaise,false,false);
                    perform fc_debug('----------------------------------------------------------------------------------------------',lRaise,false,false);

                    if    v_record_cadcalc.inicio > dVencimentoAtual
                      and iTotalVencimentosCad <> v_vencproc
                      and lJaPassouUltVenc is false                 then

                      perform fc_debug('Passando lProcessaParcela para false',lRaise,false,false);
                      lProcessaParcela = false;
                    end if;

                    perform fc_debug('Passando para o proximo vencimento(lProcessaParcela) ? '||(case when lProcessaParcela is true then 'True' else 'False' end),lRaise,false,false);

                    if lProcessaParcela is true then

                      -- DESCOBRIR EM QUE CASO E UTILIZADO
                      --
                      -- Desabilitado o if abaixo pois √© justamente ele que impede o calculo para anos posteriores,
                      v_venc = dVencimentoAtual;

                      perform fc_debug('Quantidade de vencimentos(iQtdeVencProcessar) a processar : '||iQtdeVencProcessar,lRaise,false,false);
                      if iQtdeVencProcessar = 0 then
                        nPercentualParcela = 100;
                      else

                        -- Verificacao do valor total proporcional
                        if v_record_cadcalc.tipopro = 'D' then

                          v_venc     = v_record_cadvenc.q82_venc;
                          v_venccalc = v_venc;
                          if lJaPassouUltVenc is true or v_record_cadvenc.q82_venc = dMaiorVencimentoCadvenc then

                            v_venccalc = to_char(iAnousu,'9999') || '-12-31';
                            dMaiorVencimentoCadvenc  = v_venccalc;

                          end if;
                          v_anoiniciocalc := extract(year from v_record_cadcalc.inicio);
                          if v_anoiniciocalc < iAnousu then
                            dInicioAtividadecalc = to_char(iAnousu,'9999') || '-01-01';
                          else
                            dInicioAtividadecalc = v_record_cadcalc.inicio;
                          end if;

                          perform fc_debug('Total de vencimentos : '||dMaiorVencimentoCadvenc||' Inicio: '||dInicioAtividadecalc,lRaise,false,false);
                          v_diasdesdeinicio = (iUltimoDiaAno - dInicioAtividadecalc)::integer + 1;
                          perform fc_debug('Vencimento calculo : '||v_venccalc||' Inicio: '||dInicioAtividadecalc,lRaise,false,false);
                          v_diasdestevcto = ((v_venccalc - dInicioAtividadecalc)::integer + 1) - v_diasjasomados;

                          if bGeral is true or dDtbaixa is not null then
                            nPercentualParcela = 100::float8 / iQtdeVencProcessar::float8;
                          else
                            nPercentualParcela = (100::float8 / v_diasdesdeinicio::float8)::float8 * v_diasdestevcto::float8;
                            v_diasjasomados = v_diasjasomados + v_diasdestevcto;
                          end if;

                        else

                          nPercentualParcela = 100::float8 / iQtdParcelas::float8;

                        end if;
                      end if;

                      perform fc_debug('Vencimento : '||v_venc||' Dias de vencimento : '||iDiasvctoCissqn,lRaise,false,false);

                      if v_venc is null and iAnousu < v_anoatualservidor then

                        v_venc = to_char(iAnousu,'9999')||'-12-31';

                        --- verificado o vcto do alvara quando calculado, data calc. + 30 dias
                        if iDiasvctoCissqn > 0 and iAnousu = v_anoatualservidor then

                          select dDatahj + iDiasvctoCissqn
                            into v_venc;

                          v_manual = v_manual || 'alterado vencimento para: '||v_venc||'\n';

                        end if;
                      end if;

                      perform fc_debug('Percentual : '||nPercentualParcela,lRaise,false,false);
                      perform fc_debug('Vencimento : '||v_venc,lRaise,false,false);

                      if v_prim is false then
                        if v_comcalculo is false then
                          insert into arreinscr values (v_numpre,iInscr);
                        else
                          perform fc_debug('deletando do isscalc o numpre : '||v_numpre,lRaise,false,false);
                          v_manual = v_manual || 'deletando numpre do isscalc e arrecad: ' || v_numpre || '\n';
                          delete from isscalc where q01_numpre = v_numpre;
                        end if;

                        insert into isscalc values (iAnousu, iInscr, v_record_cadcalc.cadcalc, v_receita, v_numpre, v_record_cadcalc.valor);
                        insert into numpres values ( v_numpre );
                        v_prim = true;

                      end if;

                      /**
                       * Valida quando executa calculo geral
                       */
                      if bGeral is true then

                        /**
                         * Subtraido valor que ja foi pago, caso contrario subtraira 0
                         */
                        nValorParcela = round( ( (v_record_cadcalc.valorintegr) * nPercentualParcela / 100) - coalesce(nDescontoPagamentoParcela, 0), 2);

                        perform fc_debug('(1) Valor Parcela : '||nValorParcela,lRaise,false,false);
                        perform fc_debug('Valor Total       : '||v_record_cadcalc.valorintegr||' Valor Parcial(parcela) : '||nValorParcela,lRaise,false,false);

                        /**
                         * Ultima Parcela
                         */
                        if v_numpar = v_numtot then

                          /**
                           * Arredonda os centavos e joga na ultima
                           */
                          perform fc_debug('',lRaise,false,false);
                          perform fc_debug('(4) Valor Parcela : '||nValorParcela,lRaise,false,false);
                          nValorParcela = nValorParcela + ((v_record_cadcalc.valorintegr) - (nValorParcela * v_numtot));
                          perform fc_debug('(5) Valor Parcela : '||nValorParcela,lRaise,false,false);
                        end if;

                        perform fc_debug('100 -- Delete from arrecad numpre : '||v_numpre||' numpar : '||v_record_cadvenc.q82_parc,lRaise,false,false);

                        -- se a parcela esta paga ou cancelada passa para a proxima
                        perform * from fc_statusdebitos(v_numpre, v_record_cadvenc.q82_parc)
                          where rtstatus = 'PAGO'
                             or rtstatus = 'CANCELADO' limit 1;
                        if found then

                          perform fc_debug('1 -- PARCELA '||v_record_cadvenc.q82_parc||' ESTA PAGA OU CANCELADA ',lRaise,false,false);
                          continue;
                        end if;

                        nValorParcela := round(nValorParcela,2);
                        perform fc_debug('INSERT NUMERO 2 NO ARRECAD',lRaise,false,false);
                        perform fc_debug('Numpre : '||v_numpre||' Numpar : '||v_numpar||' Valor : '||round(nValorParcela,2)||' Vencimento : '||v_venc||' Tipo : '||v_record_cadvenc.q92_tipo,lRaise,false,false);

                        if nValorParcela > 0 then

                          /**
                           * Alterado para inserir o q82_parc ao inves do numpar pois a pl nao levava em consideracao parcelas
                           * pagas e ou canceladas e assim gerando inconsistencia com numpar pago/cancelado e em aberto ao mesmo tempo
                           */
                          insert into arrecad (k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numpre, k00_numpar, k00_numtot, k00_numdig, k00_tipo)
                               values (v_numcgm, v_dtbase, v_receita, v_record_cadvenc.q92_hist, round(nValorParcela,2),
                                       v_venc, v_numpre, v_record_cadvenc.q82_parc, v_numtot, v_numdig, v_record_cadvenc.q92_tipo);

                        end if;

                      else

                        -- se a parcela esta paga ou cancelada passa para a proxima
                        perform * from fc_statusdebitos(v_numpre, v_record_cadvenc.q82_parc)
                          where rtstatus = 'PAGO'
                             or rtstatus = 'CANCELADO' limit 1;

                        if found then

                          perform fc_debug('2 -- PARCELA '||v_record_cadvenc.q82_parc||' ESTA PAGA OU CANCELADA ',lRaise,false,false);
                          v_numpar = v_numpar + 1;
                          continue;

                          /**
                           * Replicada mesma logica descrita acima quando o calculo eh geral
                           */
                        else
                          v_numpar = v_record_cadvenc.q82_parc;
                        end if;

                        if v_numpar is null then
                          v_numpar := 1;
                        end if;

                        v_dtvencano   = v_venc;
                        nValorParcela = round( ( ( v_record_cadcalc.valorintegr * nPercentualParcela ) / 100 ) - nDescontoPagamentoParcela,2 );

                        v_manual = v_manual || 'valor da parcela: ' || nValorParcela || '\n';

                        perform fc_debug('Valor integral        : '||v_record_cadcalc.valorintegr||' Percentual : '||nPercentualParcela,lRaise,false,false);
                        perform fc_debug('Valor da parcela      : '||nValorParcela,lRaise,false,false);
                        perform fc_debug('Percentual da parcela : '||nPercentualParcela,lRaise,false,false);
                        perform fc_debug('Desconto da parcela   : '||nDescontoPagamentoParcela,lRaise,false,false);

                        perform fc_debug('300 -- Delete from arrecad numpre : '||v_numpre||' numpar : '||v_numpar,lRaise,false,false);

                        if nValorParcela > 0 then

                          perform fc_debug('INSERT NUMERO 3 NO ARRECAD',lRaise,false,false);
                          perform fc_debug('Numpre : '||v_numpre||' Numpar : '||v_numpar||' Valor : '||round(nValorParcela,2)||' - Vencimento : '||v_dtvencano||' Tipo : '||v_record_cadvenc.q92_tipo,lRaise,false,false);

                          /**
                           * Caso Seja Calculo de Alvar√°, valida quantaas parcelas foram informadas para o c√°lculo de Alvara
                           *
                           * Seguindo pelo principio:
                           * -- Dividir o Valor Pela Quantidade
                           * -- Criar parcelas do alvara sempre com base de vencimento
                           * -- Gravar nova estrutura de d√©bitos no arrecad
                           **/
                           if v_record_cadcalc.cadcalc = 1  and iNumeroParcelasAlvara > 1 then

                            perform fc_debug('+----------------------------------------------',lRaise,false,false);
                            perform fc_debug('| Divisao da Parcela do Alvara em - '|| iNumeroParcelasAlvara ||' - partes ',lRaise,false,false);
                            perform fc_debug('+--------------------------------------------',lRaise,false,false);

                             for rParcelasAlvara
                              in select numero_parcela,
                                        valor_parcela,
                                        data_vencimento
                                   from fc_issqn_divide_valores(round(nValorParcela,2), iNumeroParcelasAlvara,v_dtvencano )
                             loop
                               -- ---------------------------- --
                               --  Inserindo Dados no Arrecad  --
                               -- ---------------------------- --
                               perform fc_debug('| Parcela           : '|| rParcelasAlvara.numero_parcela ,lRaise,false,false);
                               perform fc_debug('| ValorParcela      : '|| rParcelasAlvara.valor_parcela  ,lRaise,false,false);
                               perform fc_debug('| VencimentoParcela : '|| rParcelasAlvara.data_vencimento,lRaise,false,false);


                                insert
                                  into
                               arrecad (k00_numcgm,
                                        k00_dtoper,
                                        k00_receit,
                                        k00_hist,
                                        k00_valor,
                                        k00_dtvenc,
                                        k00_numpre,
                                        k00_numpar,
                                        k00_numtot,
                                        k00_numdig,
                                        k00_tipo)
                                values (v_numcgm,
                                        v_dtbase,
                                        v_receita,
                                        v_record_cadvenc.
                                        q92_hist,
                                        rParcelasAlvara.valor_parcela,   -- VALOR ANTERIOR -> round(nValorParcela,2)
                                        rParcelasAlvara.data_vencimento, -- VALOR ANTERIOR -> v_dtvencano,
                                        v_numpre,
                                        rParcelasAlvara.numero_parcela,  -- VALOR ANTERIOR -> v_numpar
                                        iNumeroParcelasAlvara,           -- VALOR ANTERIOR -> v_numtot
                                        v_numdig,
                                        v_record_cadvenc.q92_tipo);

                             perform fc_debug('+--------------------------------------------',lRaise,false,false);
                             end loop;

                           else -- Fim da Validacao de parcelas e Tipo de Calculo, seguindo como era antes

                            iNumTot = v_numtot;
                            if v_numtot = 0 then
                              iNumTot = 1;
                            end if;

                            insert into arrecad (k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numpre, k00_numpar, k00_numtot, k00_numdig, k00_tipo)
                                 values (v_numcgm, v_dtbase, v_receita, v_record_cadvenc.q92_hist, round(nValorParcela,2), v_dtvencano, v_numpre, v_numpar, iNumTot, v_numdig, v_record_cadvenc.q92_tipo);
                          end if;
                        end if;

                      end if; -- final do bloco para bgeral is false
                      perform fc_debug('Valor parcela : '||nValorParcela,lRaise,false,false);

                      if v_comcalculo is false then

                        select fc_numbco(v_codbco,v_codage) into v_numbco;
                        /*
                          Comentado insert na arrebanco conforme solicitado pelo Paulo e Dalpozzo, pois nao faz
                          o menor sentido gerar arrebanco no calculo de issqn / alvara
                        */
                        --insert into arrebanco values (v_numpre,v_numpar,v_codbco,v_codage,v_numbco);
                      end if;

                      v_numpar = v_numpar + 1;
                      v_jagravou = true;

                      if nPercentualParcela = 100 then

                        perform fc_debug('Saindo do for percentual = 100',lRaise,false,false);
                        exit;
                      end if;

                    -- se lProcessaParcela is false
                    else

                      v_manual = v_manual || 'nao utilizando vencimento: ' || v_record_cadvenc.q82_venc || '\n';
                      perform fc_debug('Nao processando financeiro lProcessaParcela is false',lRaise,false,false);

                    end if;

                  end loop;

                end if;

              end if;

            end loop;


            for v_record_cadcalc in select * from numpres loop

              select q01_manual
                into sManualText
                from isscalc
               where q01_inscr  = iInscr
                 and q01_anousu = iAnousu
                 and q01_manual is not null
               limit 1;

              v_manual := v_manual || 'atualizando log do calculo do numpre: ' || v_record_cadcalc.numpre || '\n';
              update isscalc
                 set q01_manual = coalesce(sManualText,' ') || v_manual
               where q01_inscr = iInscr and q01_anousu = iAnousu;

            end loop;

            return '01-OK';

            end;

            $$ language 'plpgsql';
EOL;
        $this->execute($sSqlVencimentosIssqn);
    }

    public function down()
    {
        $sSqlVencimentosIssqn =
        <<<EOL
            drop function if exists fc_issqn(integer,date,integer,date,boolean,boolean,integer,varchar,integer,integer);
            create or replace function fc_issqn(integer,date,integer,date,boolean,boolean,integer,varchar,integer,integer)
            returns varchar(200)
            as $$
            declare

            iInscr                    alias   for $1; -- inscricao que esta sendo calculada
            dDatahj                   alias   for $2; -- data do sistema
            iAnousu                   alias   for $3; -- ano do sistema
            dDtbaixa                  alias   for $4; -- data da baixa se estiver calculando baixa
            bRecalc                   alias   for $5; -- se e um recalculo
            bGeral                    alias   for $6; -- se e um calculo geral
            iInstit                   alias   for $7; -- instituicao
            sAtivs                    alias   for $8; -- relacao das atividades separadas por virgula. Exemplo: 1,2,3,4,5
            iTipoCalculo              alias   for $9; -- Tipo de calculo: 0 - Todos,
                                                    --                  1 - ISSQN,
                                                    --                  2 - Alvara
            iNumeroParcelasAlvara     alias   for $10;-- Quantidade de Parcelas em que o Alvare deve ser dividido

            iCalcfixvar               integer default 0;
            iDiasvctoCissqn           integer default 0;
            iConsiderarMesInicio      integer default 0;
            iMesFinal                 integer default 0;
            iDiaInicio                integer default 0;

            v_vencproc                integer default 0;
            v_quantexcluido           integer default 0;
            v_diasjasomados           integer default 0;
            v_anoatualservidor        integer;
            v_tipo_quant              integer;
            iTotalVencimentosCad      integer;
            v_anoiniciocalc           integer;
            v_mesinicio               integer;
            v_numprejapago            integer;
            v_uqtab                   integer;
            v_uqcad                   float8;
            v_ativprinc               integer;
            v_codvencadcalc           integer;
            v_codven                  integer;
            v_sequencia               integer;
            v_tabativ                 integer;
            v_ativtipo                integer;
            v_tipcalc                 integer;
            iInscrexiste              integer;
            v_quantativ               integer;
            v_numpre                  integer;
            v_numpar                  integer;
            v_numtot                  integer;
            v_numdig                  integer;
            v_numcgm                  integer;
            v_receita                 integer;
            v_codbco                  integer;
            iAux                      integer;
            v_diasdesdeinicio         integer;
            v_diasdestevcto           integer;
            iTotalDiasAno             integer;
            v_q81_rec                 integer;
            iQtdeVencProcessar        integer;
            v_totvenc                 integer default 0;
            iQtdParcelas              integer;
            iQtdParcelasPagas         integer;
            v_forcal                  integer;
            iDiasParaVencimento       integer default 0;
            iAnoCadastroEmpresa       integer default 0;
            iNumTot                   integer default 1;

            v_qprovisorio             float8 default 1;
            nValorParcela             float8;
            v_valor                   float8;
            v_valorgrav               float8 default 0;
            v_quant                   float8;
            v_base                    float8;
            v_valinflator             float8;
            v_q81_qini                float8;
            v_q81_qfim                float8;
            v_q81_val                 float8;
            nPercentualParcela        float8;

            nValorTotalPago           float8 default 0; -- total pago usado no caso de um recalculo
            nDescontoPagamentoParcela float8 default 0; -- desconto por parcela, e o valor a ser descontado por parcela no caso de um recalculo com pagamentos
            nPercPagoNovo             float8 default 0; -- percentual pago referente ao total do calculo, usado para calcular o desconto por parcela no caso de um recalculo com pagamento

            dDataCadastro             date;
            v_venc                    date;
            v_vencvar                 date;
            v_venccalc                date;
            iPrimeiroDiaAno           date;
            iUltimoDiaAno             date;
            v_dtvencano               date;
            dMaiorVencimentoCadvenc   date;
            dInicioAtividade          date;
            dInicioAtividadecalc      date;
            v_dtbase                  date;
            v_databaixa               date;
            dDtProporcionalidade      date;
            dVencimentoAtual          date;

            v_cep                     char(8);
            v_cepinstit               char(8);
            v_integr                  char(1);
            v_var                     char(1);
            v_codage                  char(5);
            v_numbco                  char(15);

            v_descrvariavel           varchar(50);
            v_descrtipcalc            varchar(40);
            v_descrcadcalc            varchar(40);
            sDescrProporcionalidade   varchar;

            v_text                    text;
            v_textexclui              text;
            v_textexclui2             text;
            v_manual                  text default '\n';
            tManual                   text;
            sSqlInsert                text;
            sManualCorrecao           text;
            sManualText               text;

            v_provisorio              boolean default false;
            v_cadcalc_var             boolean default false;
            v_cadcalc_fix             boolean default false;
            v_comcalculo              boolean default false;
            v_continuar               boolean default true;
            v_prim                    boolean;
            v_jagravou                boolean;
            lJaPassouUltVenc          boolean;
            bTemFixado                boolean default false; -- se tem valor fixado(varfixval)
            lProcessaParcVencidas     boolean default false;
            lProcessaParcela          boolean;
            lTabelasCriadas           boolean;
            lInscricaoMei             boolean default false;

            v_record_ativ             record;
            v_record_tipcalc          record;
            v_record_cadvenc          record;
            v_record_cadcalc          record;
            v_record_ativprinc        record;
            v_record_maiorvalor       record;
            v_record_somatodos        record;
            v_record_variavel         record;
            v_numprejacalculado       record;
            v_record_excluir          record;
            rDebitos                  record;
            rParcelasAlvara           record;

            dtOperacao                varchar;
            iNumpreArquivoSimples     integer;

            lRaise                    boolean default false; -- variavel para debug
            lAbatimento               boolean default false;

            lCalculaVistoriasMEI      boolean default false;

            begin

            select to_char(fc_getsession('DB_datausu')::date, 'DD/MM/YYYY') into dtOperacao;

              if  dtOperacao is null then
                 RAISE EXCEPTION 'Erro: variavel de sessao DB_datausu nao declarado!';
              end if;


            if     iTipoCalculo = 1 then
               v_manual :=  '  ======================= Calculo de ISSQN          | Data de Calculo: ' || dtOperacao ||'  =========== \n';
            elsif  iTipoCalculo = 2 then
               v_manual :=  '  ======================= Calculo de Alvara         | Data de Calculo: ' || dtOperacao ||'  =========== \n';
            else
               v_manual :=  '  ======================= Calculo de ISSQN e Alvara | Data de Calculo: ' || dtOperacao ||'  =========== \n';
            end if;

            lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );

            perform fc_debug('INICIANDO CALCULO PARA INSCRICAO : '||iInscr||' EXERCICIO : '||iAnousu,lRaise,true,false);

            perform fc_debug('DATA DE BAIXA - '||dDtbaixa,lRaise,false,false);

            perform * from ativtipo
              where q80_ativ in ( select distinct q07_ativ from tabativ where q07_inscr = iInscr );
            if not found then
              return '24-Empresa sem tipo de calculo configurado!';
            end if;

            --
            -- Realizamos a conferÍncia dos dados para sabermos se a inscriÁ„o È optante pelo SIMPLES
            --
            -- Caso seja optante n„o ser· calculado a taxa de alvar· da empresa.
            --
            -- Deve possui cadastro na tabela meicgm...
            -- OU
            -- A empresa deve ser optante com:
            --  - A categoria 3 - MEI;
            --  - Data de inÌcio do cadastro no simples menor ou igual a data de calculo;
            --  - N„o pode estar com o cadastro do simples baixado (isscadsimplesbaixa)
            --

            perform *
               from meicgm
                    inner join issbase on issbase.q02_numcgm = meicgm.q115_numcgm
              where issbase.q02_inscr = iInscr;

            if found then
              lInscricaoMei = true;
            end if;

            perform *
               from isscadsimples
              where isscadsimples.q38_inscr     = iInscr
                and isscadsimples.q38_categoria = 3
                and isscadsimples.q38_dtinicial <= dDatahj
                and not exists ( select 1
                                   from isscadsimplesbaixa
                                  where isscadsimplesbaixa.q39_isscadsimples = isscadsimples.q38_sequencial
                                    and q39_dtbaixa <= dDatahj );
            if found then
              lInscricaoMei = true;
            end if;

            --
            -- FIM DA CONFER NCIA DE CALCULO DO MEI
            --

            --
            -- Verificando par‚metro Calcula Vistorias para MEI:
            --
            select y32_calculavistoriamei::boolean
              into lCalculaVistoriasMEI
              from parfiscal;

            select extract(year from q02_dtinic)
              into iAnoCadastroEmpresa
              from issbase
             where q02_inscr = iInscr;

            if iAnousu < iAnoCadastroEmpresa then
              return '24-N„o pode ser feito calculo para exercicio menor que o ano de cadastramento da empresa.';
            end if;

            select fc_issqn_criatemptable(lRaise)
              into lTabelasCriadas;
            if lTabelasCriadas is false then
              return '24-Problema ao criar as tabelas temporarias. ';
            end if;

            for rDebitos in
              select q01_numpre
                from isscalc
               where q01_inscr  = iInscr
                 and q01_anousu = iAnousu
            loop

              -- Verifica se existe Pagamento Parcial para o dÈbito informado
              select fc_verifica_abatimento(1,rDebitos.q01_numpre)::boolean into lAbatimento;

              if lAbatimento then
                return '24-OperaÁ„o Cancelada, DÈbito com Pagamento Parcial!';
              end if;

            end loop;


            sSqlInsert := '
            insert into ativs (q07_inscr,q07_perman,q07_seq,q07_calcula,q07_ativ,q03_descr,q07_datain,q07_datafi,q07_databx,q07_quant,q11_tipcalc)
                   select distinct
                          q07_inscr,
                          q07_perman,
                          q07_seq, \'*\'::char(1) as q07_calcula,
                          q07_ativ,
                          q03_descr,
                          q07_datain,
                          q07_datafi,
                          q07_databx,
                          q07_quant,
                          q11_tipcalc
                     from tabativ
                          left outer join tabativtipcalc on q11_inscr   = q07_inscr
                                                        and q11_seq     = q07_seq
                          inner join ativtipo            on q07_ativ    = q80_ativ
                          inner join tipcalc             on q80_tipcal  = q81_codigo
                          inner join ativid              on q07_ativ    = q03_ativ
                          inner join cadcalc             on q81_cadcalc = q85_codigo
                    where q07_inscr ='||  iInscr ||'
                      and q07_seq in ('||sAtivs||')
             union
                   select distinct
                          q07_inscr,
                          q07_perman,
                          q07_seq, \'*\'::char(1) as q07_calcula,
                          q07_ativ,
                          q03_descr,
                          q07_datain,
                          q07_datafi,
                          q07_databx,
                          q07_quant,
                          q11_tipcalc
                     from tabativ
                          left outer join tabativtipcalc on q11_inscr     = q07_inscr
                                                        and q11_seq       = q07_seq
                          inner join clasativ            on q82_ativ      = q07_ativ
                          inner join issbaseporte        on q45_inscr     = q07_inscr
                          inner join issportetipo        on q41_codclasse = q82_classe
                                                        and q41_codporte  = q45_codporte
                          inner join tipcalc             on q81_codigo    = q41_codtipcalc
                          inner join ativid              on q07_ativ      = q03_ativ
                          inner join cadcalc             on q81_cadcalc   = q85_codigo
                    where q07_inscr =  '|| iInscr ||'
                      and q07_seq in ('||sAtivs||')' ;

            execute sSqlInsert;

            select count(*) from ativs into v_sequencia;

            v_sequencia = 1;

            --
            -- Primeiro dia do ano para calculo
            --
            iPrimeiroDiaAno = to_char(iAnousu, '9999') || '-01-01';

            --
            -- Ultimo dia do ano para calculo
            --
            iUltimoDiaAno = to_char(iAnousu, '9999') || '-12-31';

            --
            -- Total de dias do ano
            --
            iTotalDiasAno  = iUltimoDiaAno::date - iPrimeiroDiaAno::date + 1;

            select q02_inscr
              from issbase
              into iInscrexiste
             where q02_inscr = iInscr;
            if iInscrexiste is null then
              return '11-INSCRICAO NAO CADASTRADA ';
            end if;

            select q02_dtinic,
                   q02_dtinic,
                   extract (month from q02_dtinic)
              from issbase
              into dInicioAtividade,
                   dDataCadastro,
                   v_mesinicio
             where q02_inscr = iInscr;

            if dInicioAtividade is null then

              select q02_dtinic,
                     extract (month from q02_dtinic)
                from issbase
                into dInicioAtividade,
                     v_mesinicio
               where q02_inscr = iInscr;

              if dInicioAtividade is null then
                select min(q07_datain),
                       extract (month from min(q07_datain))
                  into dInicioAtividade,
                       v_mesinicio
                  from tabativ
                 where (q07_datafi is null or q07_datafi >= dDatahj)
                   and (q07_databx is null or q07_databx >= dDatahj);

                if dInicioAtividade is null then
                  return '12-INSCRICAO SEM DATA DE INICIO CONFIGURADA! ';
                else
                  v_manual = v_manual || 'data de inicio da inscricao (baseada na menor data de inicio das atividades): ' || dInicioAtividade || '\n';
                end if;
              else

                v_manual = v_manual || 'data de inicio da inscricao (baseada na data de cadastramento): ' || dInicioAtividade || '\n';

              end if;

            else
              v_manual = v_manual || 'data de inicio da inscricao: ' || dInicioAtividade || '\n';
            end if;

            select extract (year from dDatahj) into v_anoatualservidor;

            perform fc_debug('inscr: '||iInscr||' - inicio: '||dInicioAtividade,lRaise,false,false);

            --*********************************** se calcula por area ou por quantidade de funcionarios ********************************************************
            --
            -- grande bloco que verifica a variavel v_quant, que ser· utilizada para encontrar posteriormente o tipcalc a ser utilizado no calculo
            --
            select q60_campoutilcalc
              into v_tipo_quant
              from parissqn;

            --  se o campo q60_campoutilcalc for igual a 1 calcula pelo campo q30_area

            if v_tipo_quant is null then
              return '13-PARAMETRO DE QUANTIDADE PARA O CALCULO NAO CONFIGURADO NA TABELA PARISSQN';
            end if;

            --**************************************************************************************************************************************************

            select q04_vbase,
                   q04_calfixvar,
                   q04_diasvcto
              from cissqn
              into v_base,
                   iCalcfixvar,
                   iDiasvctoCissqn
             where cissqn.q04_anousu = iAnousu;

            if v_base = 0 or v_base is null then
              return '14-SEM VALOR BASE CADASTRADO NOS PARAMETROS ';
            end if;

            v_manual = v_manual || 'valor base configurado nos parametros: ' || v_base || '\n';

            select q04_dtbase
              from cissqn
              into v_dtbase
            where cissqn.q04_anousu = iAnousu;
            if v_dtbase is null then
              return '15-SEM DATA BASE CADASTRADA NOS PARAMETROS ';
            end if;
            v_manual = v_manual || 'data base configurada nos parametros: ' || v_dtbase || '\n';

            select distinct i02_valor
              from cissqn
              into v_valinflator
                   inner join infla on q04_inflat = i02_codigo
             where cissqn.q04_anousu = iAnousu
               and date_part('y',i02_data) = iAnousu;
            if v_valinflator is null then
              v_valinflator = 1;
            --      return 'valor do inflator nao configurado corretamente';
            end if;
            v_manual = v_manual || 'valor do inflator configurada nos parametros: ' || v_valinflator || '\n';

            perform fc_debug('valor do inflator : '||v_valinflator,lRaise,false,false);

            select q02_dtbaix
              from issbase
              into v_databaixa
             where q02_inscr = iInscr;
            if v_databaixa is not null then
              return '16-INSCRICAO JA BAIXADA ';
            end if;

            -- Q81_TIPO:
            -- 1 issqn
            -- 4 alvara
            -- 5 taxa de expediente
            select count(*) into v_quantativ from (
            select distinct q07_seq
              from tabativ
                   inner join ativtipo on ativtipo.q80_ativ = tabativ.q07_ativ
                   inner join tipcalc on q81_codigo = q80_tipcal
             where q81_tipo in (1,4,5)
               and q07_inscr = iInscr
               and (q07_datafi is null or q07_datafi >= dDatahj)
               and (q07_databx is null or q07_databx >= dDatahj)) as x;

            perform fc_debug('Total de atividades : '||v_quantativ,lRaise,false,false);

            select q07_inscr
              from tabativ
              into v_tabativ
                   inner join ativtipo on ativtipo.q80_ativ = tabativ.q07_ativ
             where q07_inscr = iInscr
               and q07_datain <= dDatahj;

            select q07_inscr
              from tabativ
              into v_ativtipo
                   inner join ativtipo on ativtipo.q80_ativ = tabativ.q07_ativ
             where q07_inscr = iInscr
               and (q07_datafi is null or q07_datafi >= dDatahj)
               and (q07_databx is null or q07_databx >= dDatahj);

            select q07_inscr
              from tabativ
              into v_tipcalc
                   inner join ativtipo on ativtipo.q80_ativ = tabativ.q07_ativ
                   inner join tipcalc  on q81_codigo = q80_tipcal
             where q81_tipo in (1,4,5)
               and q07_inscr = iInscr
               and q07_datain <= dDatahj;

            perform fc_debug('Data da baixa : '||dDtbaixa,lRaise,false,false);

            -- verifica quais os tipos de calculo para as atividades cadastradas para a inscricao

            select cep
              from db_config
              into v_cepinstit
             where codigo = iInstit;

            if v_cepinstit is null then
              return '17-PROBLEMAS COM A TABELA DB_CONFIG';
            end if;
            v_manual = v_manual || 'cep da instituicao: ' || v_cepinstit || '\n';

            select q02_cep
             from issbase
             into v_cep
            where q02_inscr = iInscr;

            v_manual = v_manual || 'cep da inscricao: ' || v_cep || '\n';

            v_manual = v_manual || '\n--- p r i m e i r a   e t a p a  -----\n';

            select distinct
                   q07_inscr
              into iAux
              from ativs;

            perform fc_debug('Inscricao tabela temporaria (ativs) : '||iAux,lRaise,false,false);

            -- ativs È uma tabela tempor·ria criada antes de chamar a funcao

            for v_record_ativ in execute 'select * from ativs where q07_inscr = ' || iInscr loop


              v_manual = v_manual || '\nprocessando atividade: ' || v_record_ativ.q07_ativ || ' - ' || v_record_ativ.q03_descr || ' - sequencia: ' || v_record_ativ.q07_seq || ' - inicio: ' || v_record_ativ.q07_datain || '\n';
              --
              -- sempre entra aqui porque ninguem utiliza o esquema da tabela
              -- tabativtipcalc (tipo de calculo especifico para aquela atividade daquela inscricao)
              --
              if v_record_ativ.q11_tipcalc is null then
                v_text = 'select distinct
                                 tipcalc.*,
                                 cadcalc.q85_outromun,
                                 cadcalc.q85_var,
                                 case
                                   when q81_tipo = 4 then
                                     ( select q83_codven
                                         from ativtipo ativtipo2
                                              inner join tipcalc    on ativtipo2.q80_tipcal = q81_codigo
                                              left  join tipcalcexe on q83_tipcalc = q81_codigo
                                                                   and q83_anousu  = (select extract(year from q02_dtinic)
                                                                                        from issbase
                                                                                       where q02_inscr = '||iInscr||')
                                        where ativtipo2.q80_ativ = ativtipo.q80_ativ and ativtipo2.q80_tipcal = ativtipo.q80_tipcal )
                                   else
                                     q83_codven
                                 end as q83_codven
                            from ativtipo
                                 inner join tipcalc    on q80_tipcal = q81_codigo
                                 left join tipcalcexe  on q83_tipcalc = q81_codigo
                                                      and q83_anousu = ' || iAnousu || '
                                 inner join cadcalc    on cadcalc.q85_codigo = tipcalc.q81_cadcalc
                           where q81_tipo in (1,4,5)
                             and q80_ativ = ' || v_record_ativ.q07_ativ || '
                         union
                          select tipcalc.*,
                                 cadcalc.q85_outromun,
                                 cadcalc.q85_var,
                                 case
                                   when q81_tipo = 4 then
                                     ( select q83_codven
                                         from tipcalc tipcalc2
                                              left  join tipcalcexe tipcalcexe2   on tipcalcexe2.q83_tipcalc = tipcalc2.q81_codigo
                                                                     and tipcalcexe2.q83_anousu  = (select extract(year from issbase2.q02_dtinic)
                                                                                          from issbase issbase2
                                                                                         where issbase2.q02_inscr = '||iInscr||')
                                              inner join cadcalc cadcalc2  on cadcalc2.q85_codigo = tipcalc2.q81_cadcalc
                                              inner join clasativ clasativ2  on clasativ2.q82_classe = issportetipo.q41_codclasse and clasativ2.q82_ativ = ' || v_record_ativ.q07_ativ || '
                                        where tipcalc2.q81_codigo = issportetipo.q41_codtipcalc )
                                   else
                                     q83_codven
                                 end as q83_codven
                            from issportetipo
                                 inner join issbaseporte on q45_inscr = ' || iInscr || '
                                 inner join tipcalc      on q41_codtipcalc = q81_codigo
                                 left  join tipcalcexe   on q83_tipcalc = q81_codigo
                                                        and q83_anousu = ' || iAnousu || '
                                 inner join cadcalc      on cadcalc.q85_codigo = tipcalc.q81_cadcalc
                                 inner join clasativ     on q82_classe = q41_codclasse
                           where q45_codporte = q41_codporte
                             and q81_tipo in (1,4,5)
                             and q82_ativ = ' || v_record_ativ.q07_ativ;

                v_textexclui = 'select distinct
                                       tipcalc.q81_cadcalc
                                  from ativtipo
                                       inner join tipcalc on q80_tipcal = q81_codigo
                                       inner join cadcalc on cadcalc.q85_codigo = tipcalc.q81_cadcalc
                                 where q81_tipo in (1,4,5)
                                   and q80_ativ = ' || v_record_ativ.q07_ativ || '
                               union
                                select tipcalc.q81_cadcalc
                                  from issportetipo
                                       inner join issbaseporte on q45_inscr = ' || iInscr || '
                                       inner join tipcalc      on q41_codtipcalc = q81_codigo
                                       inner join cadcalc      on cadcalc.q85_codigo = tipcalc.q81_cadcalc
                                       inner join clasativ     on q82_classe = q41_codclasse
                                 where q45_codporte = q41_codporte
                                   and q81_tipo in (1,4,5)
                                   and q82_ativ = ' || v_record_ativ.q07_ativ;
              else
                v_text = 'select tipcalc.*,
                                 q85_outromun,
                                 cadcalc.q85_var
                            from tipcalc
                                 left outer join cadcalc on cadcalc.q85_codigo = tipcalc.q81_cadcalc
                           where q81_tipo in (1,4,5)
                             and q81_codigo = ' || v_record_ativ.q11_tipcalc;
              end if;

              -- deletando calculo atual da inscricao do ano
              v_manual = v_manual || 'deletando os calculos antigos da inscricao no ano - apenas os que nao serao recalculados neste calculo\n';

              v_textexclui2 = 'select q01_numpre,
                                      q01_cadcal
                                 from isscalc
                                where q01_cadcal not in ( ' || v_textexclui || ')
                                  and q01_inscr  = ' || iInscr || '
                                  and q01_anousu = ' || iAnousu;

              -- limpa o financeiro do que foi selecionado pelo usuario antes de calcular

              for v_record_excluir in execute v_textexclui2 loop

                perform fc_debug('Numpre : '||v_record_excluir.q01_numpre,lRaise,false,false);
                -- verifica se n„o esta no arrecant
                select k00_numpre
                  from arrecant
                  into v_numprejapago
                 where k00_numpre = v_record_excluir.q01_numpre;
                if v_numprejapago is null then

                  v_manual = v_manual || '     deletando numpre ' || v_record_excluir.q01_numpre || ' - calculo: ' || v_record_excluir.q01_cadcal || '\n';
                  delete from arrecad where k00_numpre = v_record_excluir.q01_numpre;
                  delete from isscalc where q01_numpre = v_record_excluir.q01_numpre;
                  v_quantexcluido = v_quantexcluido + 1;

                else
                  perform fc_debug('Numpre ja pago : '||v_record_excluir.q01_numpre,lRaise,false,false);
                end if;

              end loop;

              v_manual = v_manual || 'quantidade de calculos excluidos: ' || v_quantexcluido || ' \n';

              perform fc_debug('Atividade : '||v_record_ativ.q07_ativ,lRaise,false,false);

              perform fc_debug('',lRaise,false,false);
              perform fc_debug('Sql buscando os tipos de calculo : '||v_text,lRaise,false,false);
              perform fc_debug('',lRaise,false,false);

              -- para cada atividade retornada com seu tipo de calculo...


              for v_record_tipcalc in execute v_text loop


                 select *
                   into v_quant, tManual
                   from fc_buscaquantidadeempresa( cast(iInscr                 as integer),
                                                   cast(iAnousu                as integer),
                                                   cast(v_tipo_quant           as integer),
                                                   cast(v_record_ativ.q07_ativ as integer)
                                                 );
                 perform fc_debug('========== Excluindo registros da porcalculo e "do": '||tManual,lRaise,false,false);


                 perform fc_debug('========== QUANTIDADES : '||tManual,lRaise,false,false);

                 v_manual = v_manual||tManual;

                 if v_quant = 0 and v_tipo_quant = 3 then
                   return '30 - Erro buscando as quantidades para o calculo por pontuaÁ„o. Verifique o cadastro de pontuaÁ„o das Classes, Areas, Zonas e Empregados';
                 end if;


                perform fc_debug('========== Tipo de calculo : '||v_record_tipcalc.q81_codigo||'-'||v_record_tipcalc.q81_descr||' - Quantidade : '||v_quant,lRaise,false,false);

                v_continuar = true;

                perform fc_debug('p r o c e s s a n d o  tipo de calculo: ' || v_record_tipcalc.q81_codigo || ' - ' || v_record_tipcalc.q81_descr || ' - gera: ' || v_record_tipcalc.q81_gera,lRaise,false,false);

                if  extract(year from dInicioAtividade )::integer = iAnousu then
                  v_q81_rec  = v_record_tipcalc.q81_recexe;
                  v_q81_qini = v_record_tipcalc.q81_qiexe;
                  v_q81_qfim = v_record_tipcalc.q81_qfexe;
                  v_q81_val  = v_record_tipcalc.q81_valexe;
                else
                  v_q81_rec  = v_record_tipcalc.q81_recpro;
                  v_q81_qini = v_record_tipcalc.q81_qipro;
                  v_q81_qfim = v_record_tipcalc.q81_qfpro;
                  v_q81_val  = v_record_tipcalc.q81_valpro;
                end if;

                perform fc_debug('Quantidade : '||coalesce(v_quant,0)||' Entre : '||coalesce(v_q81_qini,0)||' e '||coalesce(v_q81_qfim,0),lRaise,false,false);

                if coalesce(v_quant,0) >= coalesce(v_q81_qini,0) and coalesce(v_quant,0) <= coalesce(v_q81_qfim,999999) then

                  perform fc_debug('Dentro do if da quantidade gera : '||v_record_tipcalc.q81_gera,lRaise,false,false);
                  perform fc_debug('Ano de inicio de atividades : '||to_number(substr(dInicioAtividade,1,4),'99999'),lRaise,false,false);

                  if v_record_tipcalc.q81_gera = 1 and to_number(substr(dInicioAtividade,1,4),'99999') < iAnousu then
                    perform fc_debug('nao vai processar...',lRaise,false,false);
                    v_manual = v_manual || '\n';
                  else

                    perform fc_debug('entrou no gera 2 do if - verificando pagamentos ',lRaise,false,false);

                    v_manual = v_manual || 'verificando pagamentos\n';

                    for v_numprejacalculado in
                      select q01_numpre
                        from isscalc
                       where q01_anousu = iAnousu
                         and q01_inscr  = iInscr
                         and q01_cadcal = v_record_tipcalc.q81_cadcalc
                         and q01_recei  = v_q81_rec
                    loop

                      perform fc_debug('procurando numpre : '||v_numprejacalculado.q01_numpre,lRaise,false,false);
                      v_manual = v_manual || 'processando numpre: ' || v_numprejacalculado.q01_numpre || '\n';

                      -- trocado ordem de verificaÁ„o de arrecad, arrecant para arrecant, arrecad
                      select k00_numpre
                        from arrecant
                        into v_numprejapago
                       where k00_numpre = v_numprejacalculado.q01_numpre;

                      perform fc_debug('q01_numpre: '|| v_numprejacalculado.q01_numpre,lRaise,false,false);

                      if v_numprejacalculado.q01_numpre is not null then

                        perform fc_debug('v_numprejacalculado.q01_numpre is not null - v_numprejapago: '||v_numprejapago,lRaise,false,false);

                        if v_numprejapago is null then

                          v_manual = v_manual || 'numpre em aberto\n';

                          if dDtbaixa is not null and v_record_tipcalc.q85_var is true then
                            v_manual = v_manual || 'se data da baixa preenchido e variavel, deleta do arrecad e issvar\n';

                            perform fc_debug('Deletando do arrecad e issvar',lRaise,false,false);

                            delete from arrecad where k00_numpre = v_numprejacalculado.q01_numpre and k00_numpar >= to_number(substr(dDtbaixa,6,2),'999') + 1;

                            perform *
                               from issvarlev
                                    inner join issvar on issvar.q05_codigo = issvarlev.q18_codigo
                              where q05_numpre = v_numprejacalculado.q01_numpre
                                and q05_numpar >= to_number(substr(dDtbaixa,6,2),'999') + 1;
                            if found then
                              return '28 - EMPRESA JA POSSUI LEVANTAMENTO FISCAL PARA COMPETENCIA : '||(to_number(substr(dDtbaixa,6,2),'999') + 1)||'/'||iAnousu ;
                            end if;

                            delete from issvarnotas
                             using issvar
                             where issvar.q05_codigo  = issvarnotas.q06_codigo
                               and issvar.q05_numpre  = v_numprejacalculado.q01_numpre
                               and issvar.q05_numpar >= to_number(substr(dDtbaixa,6,2),'999') + 1;

                            delete from issvar
                             where q05_numpre = v_numprejacalculado.q01_numpre
                               and q05_numpar >= to_number(substr(dDtbaixa,6,2),'999') + 1;

                          elsif dDtbaixa is not null then

                            v_manual = v_manual || 'se data da baixa preenchido e nao variavel, insere no isscalcant e deleta do isscalc e arrecad\n';

                            perform fc_debug('Deletando do arrecad e isscalv',lRaise,false,false);

                            insert into isscalcant select * from isscalc where q01_numpre = v_numprejacalculado.q01_numpre;
                            delete from isscalc where q01_numpre = v_numprejacalculado.q01_numpre;
                            delete from arrecad where k00_numpre = v_numprejacalculado.q01_numpre;
                          end if;

                        else

                          perform fc_debug('Numpre pago.',lRaise,false,false);

                          v_manual = v_manual || 'numpre pago\n';
                          v_comcalculo = true;

                        end if;

                        if v_numprejapago is null then

                          select k00_numpre
                          from arrecad
                          into v_numprejapago
                          where k00_numpre = v_numprejacalculado.q01_numpre;

                          if v_numprejapago is not null then

                            if bRecalc is false then
                              return '18-INSCRICAO JA CALCULADA E RECALCULO NAO PASSADO COMO PARAMETRO';
                            end if;

                            perform fc_debug('recalculo',lRaise,false,false);

                            v_manual = v_manual || 'recalculo\n';
                            v_comcalculo = true;
                          end if;

                        end if;

                      end if;

                    end loop;

                    perform fc_debug('saiu do procura pagamentos - continuar : '||( case when v_continuar is true then 'true' else 'false' end ),lRaise,false,false);

                    if v_continuar then

                        -- se true, busca quantidade do tabativ, senao default 1
                      if v_record_tipcalc.q81_uqtab is false then

                        perform fc_debug('uqtab false',lRaise,false,false);

                        v_uqtab = 1;
                        v_manual = v_manual || 'quantidade utilizada da tabela para calculo (utilizada default do sistema):' || v_uqtab || '\n';
                      else

                        perform fc_debug('uqtab true',lRaise,false,false);

                        if v_record_ativ.q07_quant = 0 then
                          v_uqtab = 1;
                        else
                          v_uqtab = v_record_ativ.q07_quant;
                        end if;
                        v_manual = v_manual || 'quantidade utilizada para calculo (baseada na quantidade da atividade lancada): ' || v_uqtab || '\n';
                      end if;

                      -- se true, busca quantidade do issquant, senao default 1
                      if v_record_tipcalc.q81_uqcad is false then
                        v_uqcad = 1;
                      else
                        select q30_mult
                          from issquant
                          into v_uqcad
                         where issquant.q30_inscr = iInscr
                           and issquant.q30_anousu = iAnousu;
                        if v_uqcad is null then
                          return '19-MULTIPLICADOR NAO LANCADO PARA ESTA INSCRICAO';
                        end if;

                      end if;

                      if v_record_tipcalc.q81_integr is true then
                        v_integr = '1';
                      else
                        v_integr = '0';
                      end if;
                      v_manual = v_manual || 'integral: (1 = sim - 0 = nao): ' || v_integr || '\n';

                      select case when q85_var is true then '1' else '0' end as q85_var
                      from cadcalc
                      into v_var
                      where cadcalc.q85_codigo = v_record_tipcalc.q81_cadcalc;
                      if v_var is null then
                        return '20-NAO DEFINIDO NO CADASTRO DE CALCULO SE VARIAVEL OU NAO';
                      end if;

                      select q85_forcal
                      from cadcalc
                      into v_forcal
                      where cadcalc.q85_codigo = v_record_tipcalc.q81_cadcalc;
                      if v_forcal is null then
                        return '21-nAO DEFINIDO NO CADASTRO DE CALCULO A FORMA DE CALCULO';
                      end if;
                      v_manual = v_manual || 'forma de calculo (1 = atividade principal - 2 = atividade com maior valor - 3 = soma do valor das atividades):' || v_forcal || '\n';

                      select q85_perman
                        from cadcalc
                        into v_provisorio
                       where cadcalc.q85_codigo = v_record_tipcalc.q81_cadcalc;

                      if v_provisorio is true and v_record_ativ.q07_perman is false then

                        v_qprovisorio = v_record_tipcalc.q81_percprovis;
                        v_manual = v_manual || 'provisorio: vai acrescer ' || v_qprovisorio || ' por centro no valor calculado\n';

                        perform fc_debug('Provisorio -- '||v_qprovisorio,lRaise,false,false);

                      else

                        v_qprovisorio = 1;

                        perform fc_debug('Nao provisorio',lRaise,false,false);

                      end if;

                      perform fc_debug('inserindo na tabela tudo... sequencia:'||v_sequencia,lRaise,false,false);
                      perform fc_debug('q81_val: '||v_q81_val||' - v_base: '|| v_base||' - uqtab: '||v_uqtab||' - uqcad: '||v_uqcad||' - qprovisorio: '||v_qprovisorio||' - valinflator: '||v_valinflator,lRaise,false,false);

                      if v_record_tipcalc.q83_codven is null then
                        return '25-VENCIMENTO NAO ENCONTRADO NO CADASTRO DE TIPO DE CALCULO';
                      else
                        v_codven = v_record_tipcalc.q83_codven;
                      end if;
                      perform fc_debug('q81_codigo: '||v_record_tipcalc.q81_codigo||' - q81_cadcalc: '||v_record_tipcalc.q81_cadcalc||' - vencimento: '||v_codven,lRaise,false,false);

                      --
                      -- Se empresa for optante pelo simples nao deve ser efetuado
                      -- calculo de alvara nem de taxa de expediente
                      --
                      if lInscricaoMei then

                        if v_record_tipcalc.q81_cadcalc = 1 then
                          perform fc_debug('Inscricao optante pelo MEI, nao calculando alvara.',lRaise,false,false);
                          continue;
                        end if;

                        -- Se par‚metro Calcula Vistorias para MEI: estiver N√O ent„o ignora taxa de expediente

                        if lCalculaVistoriasMEI = false then

                          if v_record_tipcalc.q81_tipo = 5 then -- tipo de c·lculo for taxa

                            perform fc_debug('Inscricao optante pelo MEI, nao calculando TAXA DE EXPEDIENTE.',lRaise,false,false);
                            continue;
                          end if;

                        end if;

                      end if;


                      insert into
                        tudo values (iAnousu,
                                     iInscr,
                                     v_record_ativ.q07_ativ,
                                     v_record_tipcalc.q81_codigo,
                                     v_record_tipcalc.q81_cadcalc,
                                     v_base,
                                     v_record_tipcalc.q81_recexe,
                                     v_record_tipcalc.q81_recpro,
                                     coalesce(v_quant,0),
                                     v_record_tipcalc.q81_qiexe,
                                     v_record_tipcalc.q81_qfexe,
                                     v_uqtab,
                                     v_uqcad,
                                     v_forcal,
                                     v_codven,
                                     v_integr,
                                     v_record_tipcalc.q81_tippro,
                                     v_q81_val * v_base * v_uqtab * v_uqcad * v_qprovisorio * v_valinflator,
                                     v_q81_val * v_qprovisorio * v_valinflator,
                                     v_record_ativ.q07_datain,
                                     coalesce( v_record_ativ.q07_datafi,(iAnousu||'-12-31')::date ),
                                     v_var,
                                     v_record_tipcalc.q81_gera,
                                     v_sequencia);

                          v_sequencia = v_sequencia + 1;

                    end if;

                  end if;

                end if;

              end loop;
              perform fc_debug('Terminou atividade',lRaise,false,false);

            end loop;

            --
            -- SEGUNDA FASE DO CALCULO
            -- na fase anterior a rotina insere registros na tabela tudo e nela È que se baseia daqui para frente para saber o que calcular
            --

            perform fc_debug('--------------------------------------------------------------------------------------------------------',lRaise,false,false);
            perform fc_debug('',lRaise,false,false);
            perform fc_debug('SEGUNDA FASE DO CALCULO',lRaise,false,false);
            perform fc_debug('',lRaise,false,false);
            perform fc_debug('Na fase anterior a rotina insere registros na tabela tudo e nela È que se baseia daqui para frente para saber o que calcular',lRaise,false,false);


            if    iTipoCalculo = 1  then
              perform fc_debug('Excluindo registros de Alvar·', lRaise, false, false);
              DELETE FROM tudo where cadcalc not in (2,3);
            elsif iTipoCalculo = 2  then

              perform fc_debug('Excluindo registros de ISSQN', lRaise, false, false);
              DELETE FROM tudo where cadcalc not in (1,4,9);
            end if;


            select count(*) into iAux from tudo;

            v_manual = v_manual || '\nregistros processados na etapa de tipos de calculo: ' || iAux || '\n';
            v_manual = v_manual || '\n---- s e g u n d a  e t a p a  -----\n';
            v_manual = v_manual || 'agrupando por cadastro de calculo' || '\n';

            perform fc_debug('Quantidade de registros tabela tudo : '||iAux,lRaise,false,false);


            --
            -- for na tabela tudo com os tipos de calculo a utilizar
            -- veja o detalhe do group by, que faz com que apenas um cadcalc (ALVARA/ISSQN FIXO/ISSQN VARIAVEL) seja utilizado por calculo
            -- nessa fase o sistema cria registros na tabela porcalculo que ser· utilizada nessa fase
            --
            for v_record_cadcalc in select cadcalc, forcal, var from tudo group by cadcalc, forcal, var loop

              select q85_descr
                into v_descrcadcalc
                from cadcalc
               where q85_codigo = v_record_cadcalc.cadcalc;

              v_manual = v_manual || '   processando calculo ' || v_record_cadcalc.cadcalc || ' - ' || v_descrcadcalc || '\n';

              perform fc_debug('Var : '||v_record_cadcalc.var||' cadcalc : '||v_record_cadcalc.cadcalc,lRaise,false,false);

              -- se for variavel
              if v_record_cadcalc.var = '1' then

                v_manual = v_manual || '      variavel\n';

                -- pode ser fixado por inscricao
                for v_record_variavel in select * from tudo where tudo.cadcalc = v_record_cadcalc.cadcalc limit 1 loop

                  perform fc_debug('Inserindo na tabela tudo fixado por inscricao',lRaise,false,false);

                  execute 'insert into porcalculo values ('
                  || iAnousu || ','
                  || iInscr  || ','
                  || v_base || ','
                  || v_record_variavel.tipcalc || ','
                  || v_record_variavel.cadcalc || ','
                  || v_record_variavel.forcal  || ','
                  || v_record_variavel.codven  || ','
                  || v_record_variavel.integr  || ','
                  || '''' || v_record_variavel.tipopro || '''' || ','
                  || '''' || v_record_variavel.inicio  || '''' || ','
                  || '''' || coalesce( v_record_variavel.final,(iAnousu||'-12-31')::date )   || '''' || ','
                  || 0 || ','
                  || v_record_variavel.valori || ','
                  || 0 || ','
                  || '''' || v_record_variavel.var     || '''' || ','
                  || v_record_variavel.gera || ','
                  || v_record_variavel.seq
                  || ');';

                end loop;

              -- se NAO for variavel
              else

                perform fc_debug('Inserindo na tabela tudo pela atividade principal',lRaise,false,false);

                v_manual = v_manual || '      nao variavel\n';

                -- se for pela atividade principal
                if v_record_cadcalc.forcal = 1 then
                  v_manual = v_manual || '         calculando pela atividade principal\n';

                  select q07_ativ
                    into v_ativprinc
                    from ativprinc
                         inner join tabativ on q07_inscr = q88_inscr and q07_seq = q88_seq
                   where q88_inscr = iInscr;
                  if v_ativprinc is null then
                    return '22-SEM ATIVIDADE PRINCIPAL CADASTRADA PARA ESTA INSCRICAO ';
                  end if;

            --        for v_record_ativprinc in select * from tudo where tudo.cadcalc = v_record_cadcalc.cadcalc and tudo.ativ = v_ativprinc loop
            --        DESCOBRIR QUEM E PORQUE COMENTARAM A LINHA ACIMA
            --        PORQUE PELA LOGICA DEVERIA UTILIZAR A LINHA COMENTADA

                  for v_record_ativprinc in select * from tudo where tudo.cadcalc = v_record_cadcalc.cadcalc limit 1 loop

                    v_manual = v_manual || '            inserindo na tabela de tipos de calculo a processar - tipcalc: ' || v_record_ativprinc.tipcalc || ' - cadcalc: ' || v_record_ativprinc.cadcalc || '\n';
                    execute 'insert into porcalculo values ('
                    || iAnousu || ','
                    || iInscr  || ','
                    || v_base || ','
                    || v_record_ativprinc.tipcalc || ','
                    || v_record_ativprinc.cadcalc || ','
                    || v_record_ativprinc.forcal  || ','
                    || v_record_ativprinc.codven  || ','
                    || v_record_ativprinc.integr  || ','
                    || '''' || v_record_ativprinc.tipopro || '''' || ','
                    || '''' || v_record_ativprinc.inicio  || '''' || ','
                    || '''' || coalesce( v_record_ativprinc.final, (iAnousu||'-12-31')::date )   || '''' || ','
                    || v_record_ativprinc.valor   || ','
                    || v_record_ativprinc.valori  || ','
                    || 0 || ','
                    || '''' || v_record_ativprinc.var     || '''' || ','
                    || v_record_ativprinc.gera    || ','
                    || v_record_ativprinc.seq
                    || ');';

                  end loop;

                end if;

                -- pela atividade que gerou o maior valor
                if v_record_cadcalc.forcal = 2 then
                  v_manual = v_manual || 'calculando pela atividade que gerou o maior valor\n';

                  for v_record_maiorvalor in select * from tudo where tudo.cadcalc = v_record_cadcalc.cadcalc order by valor desc limit 1 loop

                    perform fc_debug('Inserindo na tabela porcalculo pela atividade de maior valor valor : '||v_record_maiorvalor.valor||' - tipo de calculo: '||v_record_maiorvalor.tipcalc||' - vencimento: '||v_record_maiorvalor.codven,lRaise,false,false);

                    execute 'insert into porcalculo values ('
                    || iAnousu || ','
                    || iInscr  || ','
                    || v_base || ','
                    || v_record_maiorvalor.tipcalc || ','
                    || v_record_maiorvalor.cadcalc || ','
                    || v_record_maiorvalor.forcal  || ','
                    || v_record_maiorvalor.codven  || ','
                    || v_record_maiorvalor.integr  || ','
                    || '''' || v_record_maiorvalor.tipopro || '''' || ','
                    || '''' || v_record_maiorvalor.inicio  || '''' || ','
                    || '''' || coalesce( v_record_maiorvalor.final, (iAnousu||'-12-31')::date )   || '''' || ','
                    || v_record_maiorvalor.valor   || ','
                    || v_record_maiorvalor.valori  || ','
                    || 0 || ','
                    || '''' || v_record_maiorvalor.var     || '''' || ','
                    || v_record_maiorvalor.gera    || ','
                    || v_record_maiorvalor.seq
                    || ');';

                  end loop;

                end if;

                -- pela soma de todos os valores calculados
                -- ainda nao implementado totalmente
                -- ou seja, nao funciona ainda...
                -- TESTADORES DEVEM UTILIZAR ESSA FORMULA DE CALCULO NOS TESTES
                if v_record_cadcalc.forcal = 3 then
                  v_manual = v_manual || 'calculando pela soma de todos os valores\n';

                  for v_record_somatodos in select * ,
                                                   (select sum(valor) as somatotal
                                                      from tudo
                                                     where tudo.cadcalc = v_record_cadcalc.cadcalc) as somatotal
                                              from tudo
                                             where tudo.cadcalc = v_record_cadcalc.cadcalc loop

                    select q85_codven
                    from cadcalc
                    into v_codvencadcalc
                    where q85_codigo = v_record_cadcalc.cadcalc;
                    if v_codvencadcalc is null then
                      return '23-SEM VENCIMENTO PADRAO NO CADASTRO DE VENCIMENTOS ';
                    end if;

                    execute 'insert into porcalculo values ('
                    || iAnousu || ','
                    || iInscr  || ','
                    || v_base || ','
                    || v_record_somatodos.tipcalc || ','
                    || v_record_cadcalc.cadcalc || ','
                    || v_record_cadcalc.forcal  || ','
                    || v_codvencadcalc            || ','
                    || v_record_somatodos.integr  || ','
                    || '''' || v_record_somatodos.tipopro || '''' || ','
                    || '''' || v_record_somatodos.inicio  || '''' || ','
                    || '''' || coalesce( v_record_somatodos.final, (iAnousu||'-12-31')::date)   || '''' || ','
                    || v_record_somatodos.somatotal || ','
                    || v_record_somatodos.valori  || ','
                    || 0 || ','
                    || '''' || v_record_somatodos.var     || '''' || ','
                    || v_record_somatodos.gera    || ','
                    || v_record_somatodos.seq
                    || ');';

                  end loop;

                end if;

              end if;

            end loop;
            -- fim do for do select na tabela tudo, que gera os registros na porcalculo

            v_manual = v_manual || '\n---- t e r c e i r a  e t a p a  -----\n';
            v_manual = v_manual || 'agrupando por tipo de vencimento e preparando para calcular\n';

            perform fc_debug('--------------------------------------------------------------------------------------------------------',lRaise,false,false);
            perform fc_debug('',lRaise,false,false);
            perform fc_debug('TERCEIRA ETAPA DO CALCULO ',lRaise,false,false);
            perform fc_debug('',lRaise,false,false);
            perform fc_debug('agrupando por tipo de vencimento e preparando para calcular',lRaise,false,false);

            for v_record_cadcalc in select porcalculo.*,
                                           tipcalc.q81_excedenteativ
                                      from porcalculo
                                           inner join tipcalc on q81_codigo = porcalculo.tipcalc
            loop

              if v_record_cadcalc.q81_excedenteativ > 0 then
                v_valor = v_record_cadcalc.valor;

                perform fc_debug('Seq : '||v_record_cadcalc.seq||' Valor : '||v_valor,lRaise,false,false);

                v_valor = v_valor + (v_valor * v_record_cadcalc.q81_excedenteativ * (v_quantativ - 1));
                update porcalculo set valor = v_valor where seq = v_record_cadcalc.seq ;

                perform fc_debug('Apos calcular 30% por atividade excedente - Seq : '||v_record_cadcalc.seq||' Valor : '||v_valor,lRaise,false,false);

              end if;
            end loop;

            perform fc_debug('Descobrindo de calcula fixo ou variavel ',lRaise,false,false);

            --
            -- Verifica se calcula fixo ou variavel
            --
            for v_record_cadvenc in select distinct cadcalc from porcalculo
            loop

              if v_record_cadvenc.cadcalc = 2 then

                v_cadcalc_fix = true;

              elsif v_record_cadvenc.cadcalc = 3 then

                v_cadcalc_var = true;

              end if;

            end loop;

            if v_cadcalc_fix = true and v_cadcalc_var = true and iCalcfixvar = 1 then

              perform fc_debug('Inscricao com dois (2) calculos fixo/var, excluido calculo fixo',lRaise,false,false);
              delete from porcalculo where cadcalc = 2;
              v_manual = v_manual || '\n inscricao com dois (2) calculos fixo/variavel, calculado somente variavel \n';

            end if;

            if v_cadcalc_fix = true and v_cadcalc_var = true and iCalcfixvar = 2 then

              perform fc_debug('Inscricao com dois (2) calculos fixo/var, excluido calculo variavel',lRaise,false,false);

              delete from porcalculo where cadcalc = 3;
              v_manual = v_manual || '\n inscricao com dois (2) calculos fixo/variavel, calculado somente fixo \n';

            end if;

            perform fc_debug('select trazendo os vencimentos para gerar as proporcionalidades',lRaise,false,false);

            for v_record_cadvenc in select codven from porcalculo group by codven
            loop

              v_manual = v_manual || 'processando vencimento ' || v_record_cadvenc.codven || '\n';

              perform fc_debug('Codigo do cadastro de vencimentos : '||v_record_cadvenc.codven,lRaise,false,false);

              for v_record_cadcalc in select distinct tipcalc, cadcalc, valor, integr, inicio, final, tipopro, seq from porcalculo where codven = v_record_cadvenc.codven loop

                perform fc_debug('Tipo de calulo : '||v_record_cadcalc.tipcalc||' Cadcalc : '||v_record_cadcalc.cadcalc||'seq :'||v_record_cadcalc.seq,lRaise,false,false);

                select q81_abrev
                into v_descrtipcalc
                  from tipcalc
                 where q81_codigo = v_record_cadcalc.tipcalc;

                select q85_descr
                  into v_descrcadcalc
                  from cadcalc
                 where q85_codigo = v_record_cadcalc.cadcalc;

                v_manual = v_manual || 'processando tipcalc: ' || v_record_cadcalc.tipcalc || ' - ' || v_descrtipcalc || ' - cadcalc: ' || v_record_cadcalc.cadcalc || ' - ' || v_descrcadcalc || '\n';

                v_valor = v_record_cadcalc.valor;
                v_manual = v_manual || 'valor: ' || v_valor || '\n';

                -- se È para calcular com proporcionalidade
                if v_record_cadcalc.integr = '0' then

                  perform fc_debug('Valor sem proporcionalidade : '||v_valor,lRaise,false,false);

                  v_manual = v_manual || '   nao integral = proporcional ' || ' inicio: ' || v_record_cadcalc.inicio || ' - ano atual ' || iAnousu || '\n';

                  -- soh calcula integralidade se ano do inicio da atividade for igual ao atual ou for calculo de baixa
                  if extract(year from v_record_cadcalc.inicio)::integer = iAnousu or dDtbaixa is not null then
                    --
                    -- Calculo da proporcionalidade
                    --
                    if dDtbaixa is null then
                      dDtProporcionalidade := v_record_cadcalc.final;
                    else
                      dDtProporcionalidade := dDtbaixa;
                    end if;

                    perform fc_debug('Tipo   - '||v_record_cadcalc.tipopro,lRaise,false,false);
                    perform fc_debug('Inicio - '||v_record_cadcalc.inicio,lRaise,false,false);
                    perform fc_debug('Final  - '||dDtProporcionalidade,lRaise,false,false);

                    --
                    -- Funcao fc_issqn_proporcionalidade
                    --   retorna o valor proporcional ao periodo de atividade do exercio e a descricao do tipo de proporcionalidade
                    --
                    select rnValorProporcional,rsTipoProporcionalidade
                      into v_valor,sDescrProporcionalidade
                      from fc_issqn_proporcionalidade(v_record_cadcalc.valor::numeric,v_record_cadcalc.tipopro::varchar,v_record_cadcalc.inicio::date,dDtProporcionalidade::date,iAnousu::integer,dDtbaixa::date);

                    v_manual = v_manual ||sDescrProporcionalidade||' \n';

                  end if;

                  perform fc_debug('Valor com a proporcionalide : '||v_valor,lRaise,false,false);

                  v_manual = v_manual || 'valor ja calculado a proporcionalidade: ' || v_valor || '\n';

                else

                  v_manual = v_manual || 'integral\n';

                end if;

                update porcalculo set valorintegr = v_valor  where seq = v_record_cadcalc.seq ;

              end loop;

            end loop;

            select count(*)
              into iAux
              from porcalculo;

            v_manual = v_manual || '\n---- q u a r t a  e t a p a  -----\n';
            v_manual = v_manual || 'total de calculos que o sistema vai processar: ' || iAux || '\n';

            if iAux = 0 then
              return '24-NENHUM CALCULO EFETUADO!';
            end if;

            --
            -- QUARTA FASE - GERANDO FINANCEIRO
            --
            perform fc_debug('--------------------------------------------------------------------------------------------------------',lRaise,false,false);
            perform fc_debug('',lRaise,false,false);
            perform fc_debug('QUARTA FASE DO CALCULO (GERANDO FINANCEIRO) ',lRaise,false,false);
            perform fc_debug('Quantidade de registros tabela porcalculo : '||iAux,lRaise,false,false);
            perform fc_debug('',lRaise,false,false);
            perform fc_debug('--------------------------------------------------------------------------------------------------------',lRaise,false,false);

            /* for nos calculo da inscricao que esta sendo calculada */

            perform fc_debug('Percorrendo os calculo da inscricao que esta sendo calculada',lRaise,false,false);

            dInicioAtividade := dDataCadastro;

            for v_record_cadcalc in select * from porcalculo loop

              select q81_abrev
                from tipcalc
                into v_descrtipcalc
              where q81_codigo = v_record_cadcalc.tipcalc;

              select q85_descr
              from cadcalc
              into v_descrcadcalc
              where q85_codigo = v_record_cadcalc.cadcalc;

              v_manual = v_manual || '\nprocessando calculo ' || v_record_cadcalc.cadcalc || ' - ' || v_descrcadcalc ||  ' - tipo de calculo: ' || v_record_cadcalc.tipcalc || ' - ' || v_descrtipcalc || '\n';

              -- data de baixa preenchida e È variavel
              -- resumindo, È para nao fazer nada se for vari·vel e calculo para baixa

              if dDtbaixa is not null and v_record_cadcalc.var = '1' then

                perform fc_debug('Com data de baixa passada como parametro: ' || dDtbaixa || ' e variavel: nao calcula',lRaise,false,false);
                v_manual = v_manual || 'com data de baixa passada como parametro: ' || dDtbaixa || ' e variavel: nao calcula\n';
              -- senao
              else

                select q01_numpre
                  from isscalc
                  into v_numpre
                 where q01_anousu = iAnousu
                   and q01_inscr = iInscr
                   and q01_cadcal = v_record_cadcalc.cadcalc;

                if v_numpre is null then

                  perform fc_debug('Sem calculo para o exercicio ',lRaise,false,false);
                  v_comcalculo = false;

                else

                  perform fc_debug('Com calculo para o exercicio numpre : '||v_numpre,lRaise,false,false);
                  v_comcalculo = true;

                end if;

                if v_comcalculo is false then
                  v_manual = v_manual || 'calculo novo\n';

                  select nextval('numpref_k03_numpre_seq')
                    into v_numpre;

                  perform fc_debug('Calculo Novo ',lRaise,false,false);
                  perform fc_debug('Novo numpre : '||v_numpre,lRaise,false,false);

                  if v_numpre is null then
                    return '25-ERRO DO PROCESSAR SEQUENCIA DO NUMPRE';
                  end if;

                else

                  v_manual = v_manual || 'calculo ja existe... utilizar o mesmo numpre\n';
                  select q01_numpre
                    from isscalc
                    into v_numpre
                    where q01_anousu = iAnousu
                      and q01_inscr = iInscr
                      and q01_cadcal = v_record_cadcalc.cadcalc;

                end if;

                v_numpar = 1;

                select max(q82_parc)
                  into v_numtot
                  from cadvenc
                       inner join cadvencdesc on q82_codigo = q92_codigo
                 where cadvenc.q82_codigo = v_record_cadcalc.codven;

                if v_record_cadcalc.var = '1' then
                  v_numtot = 12;
                else

                  if v_numtot is null then
                    v_numtot = 0;
                  end if;

                  if bGeral is false then
                    v_numtot = 1;
                  end if;
                end if;

                v_manual = v_manual || 'total de parcelas baseado no vencimento: ' || v_numtot || '\n';

                perform fc_debug('Codigo do Vencimento encontrado : '||v_record_cadcalc.codven,lRaise,false,false);
                perform fc_debug('Numpre : '||v_numpre||' Parcela : '||v_numpar||' Numtot : '||v_numtot||' Cadcalc : '||v_record_cadcalc.cadcalc,lRaise,false,false);

                select fc_digito(v_numpre,v_numpar,v_numtot)
                  into v_numdig;

                if v_numdig is null then
                  return '26-ERRO DO PROCESSAR FUNCAO DE CALCULO DO DIGITO VERIFICADOR';
                end if;

                select k15_codbco,
                       k15_codage
                  into v_codbco,
                       v_codage
                  from cadvencdescban
                       inner join cadban on cadvencdescban.q93_cadban = cadban.k15_codigo
                where q93_codigo = v_record_cadcalc.codven;

                if v_codbco is null then
                  v_codbco = 0;
                end if;

                if v_codage is null then
                  v_codage = 0;
                end if;

                select q02_numcgm
                  into v_numcgm
                  from issbase
                 where q02_inscr = iInscr;
                if v_numcgm is null then
                  return '27-CGM DA INSCRICAO : '||iInscr||' NAO ENCONTRADO DO CADASTRO DO CGM';
                end if;

                perform fc_debug('Inicio : '||v_record_cadcalc.inicio||' Anousu : '||iAnousu,lRaise,false,false);

                if iAnousu = to_number(substr(v_record_cadcalc.inicio,1,4),'99999') then

                  v_manual = v_manual || 'Ano atual igual ao ano de inicio da atividade: ' || iAnousu || '\n';

                  perform fc_debug('Ano atual igual ao ano de inicio',lRaise,false,false);

                  select recexe
                    into v_receita
                    from tudo
                   where tudo.seq = v_record_cadcalc.seq;
                else

                  v_manual = v_manual||'Ano atual diferente do ano de inicio da atividade: ' || iAnousu || '\n';
                  perform fc_debug('Ano atual diferente ao ano de inicio',lRaise,false,false);
                  select recpro
                    into v_receita
                    from tudo
                   where tudo.seq = v_record_cadcalc.seq;

                end if;

                v_prim = false;

                /**
                 * Se for variavel
                 */
                if v_record_cadcalc.var = '1' then
                  v_manual = v_manual || 'variavel\n';

                  perform fc_debug('Calculando variavel -- vencimento : '||v_record_cadcalc.codven,lRaise,false,false);

                 -- se for baixa
                  perform fc_debug('',lRaise,false,false);
                  perform fc_debug('COMECANDO A PROCESSAR OS VENCIMENTOS (PELO CADASTRO DE VENCIMENTOS CADVENC) ',lRaise,false,false);
                  perform fc_debug('',lRaise,false,false);

                  /* este for calcula pelo cadvenc todas as parcelas pelo select do porcalculo(todos os calculos da inscricao) */
                  for v_record_cadvenc in select * from cadvenc
                                                   inner join cadvencdesc on q82_codigo = q92_codigo
                                             where cadvenc.q82_codigo = v_record_cadcalc.codven
                                             order by q82_parc
                  loop

                    perform fc_debug('Processando vencimentos ',lRaise,false,false);
                    perform fc_debug('PARCELA : '||v_record_cadvenc.q82_parc||' VENCIMENTO : '||v_record_cadvenc.q82_venc,lRaise,false,false);
                    perform fc_debug('1 -- Deletando do arrecad numpre : '||v_numpre||' numpar : '||v_record_cadvenc.q82_parc,lRaise,false,false);

                    delete from arrecad
                     where k00_numpre = v_numpre
                       and k00_numpar = v_record_cadvenc.q82_parc;

                    if to_number(substr(v_record_cadvenc.q82_venc,1,4)||substr(v_record_cadvenc.q82_venc,6,2),'999999') >
                       to_number(substr(v_record_cadcalc.inicio,1,4)||substr(v_record_cadcalc.inicio,6,2),'999999') then

                      v_manual = v_manual || 'vencimento do cadastro de vencimentos: ' || v_record_cadvenc.q82_venc || ' maior ou igual a data de inicio da atividade mais 1 mes\n';

                      select count(*)
                        into iAux
                        from arreinscr
                       where k00_numpre = v_numpre
                         and k00_inscr = iInscr;

                      if iAux = 0 then

                        v_manual = v_manual || 'inserindo numpre no arreinscr: ' || v_numpre || '\n';
                        insert into arreinscr values (v_numpre,iInscr);
                      end if;

                      /* se nao for primeiro calculo do exercicio */
                      if v_prim is false then

                        if v_comcalculo is true then

                          perform fc_debug('Deletando do isscalc numpre : '||v_numpre,lRaise,false,false);
                          v_manual = v_manual || 'deletando numpre do isscalc e arrecad: ' || v_numpre || '\n';
                          delete from isscalc where q01_numpre = v_numpre;

                        end if;

                        insert into isscalc values (iAnousu,iInscr,v_record_cadcalc.cadcalc,v_receita,v_numpre,v_record_cadcalc.valori / v_valinflator);
                        insert into numpres values (v_numpre);
                        v_prim = true;

                      end if;

                      v_valor         := v_record_cadcalc.valori / v_valinflator;
                      v_valorgrav     := 0;
                      v_descrvariavel := 'arrecadacao de issqn variavel nao fixado';
                      v_manual        := v_manual || 'valor: ' || v_valor || '\n';

                      /* verifica se tem valor fixado lancado */
                      select q34_valor
                        into v_valorgrav
                        from varfix
                             inner join varfixval on varfix.q33_codigo = varfixval.q34_codigo
                       where varfix.q33_inscr    = iInscr
                         and varfixval.q34_numpar = v_record_cadvenc.q82_parc;

                      if v_valorgrav is not null then

                        v_descrvariavel = 'arrecadacao de issqn variavel fixado';
                        bTemFixado = true;
                      else

                        v_valorgrav     = 0;
                        v_descrvariavel = 'arrecadacao de issqn variavel nao fixado';
                      end if;

                      perform fc_debug('Tipo de valor : '||v_descrvariavel ,lRaise,false,false);

                      v_numpar = v_record_cadvenc.q82_parc;
                      v_manual = v_manual || 'parcela: ' || v_numpar || '\n';

                      select q05_codigo
                        into iAux
                        from issvar
                             inner join arreinscr on q05_numpre = arreinscr.k00_numpre
                       where q05_ano   = iAnousu
                         and q05_mes   = v_numpar
                         and k00_inscr = iInscr
                         and q05_valor > 0;

                      v_vencvar = v_record_cadvenc.q82_venc;

                      perform fc_debug('Vencimento variavel : '||v_vencvar,lRaise,false,false);

                      select q05_numpre
                        from issvar
                        into v_numprejapago
                             inner join arrecant on q05_numpre = arrecant.k00_numpre and q05_numpar = arrecant.k00_numpar
                             inner join arreinscr on arrecant.k00_numpre = arreinscr.k00_numpre
                      where q05_ano   = iAnousu
                        and q05_mes   = v_numpar
                        and k00_inscr = iInscr;

                      perform fc_debug('Numpre ja pago '||v_numprejapago||' Numpar : '||v_numpar||' Anousu : '||iAnousu,lRaise,false,false);

                      select q05_numpre
                        into iNumpreArquivoSimples
                        from issvar
                             inner join issarqsimplesregissvar on q68_issvar = q05_codigo
                             inner join arreinscr on q05_numpre = arreinscr.k00_numpre
                      where q05_ano   = iAnousu
                        and q05_mes   = v_numpar
                        and k00_inscr = iInscr;

                      perform fc_debug('Numpre no arquivo do simples '||iNumpreArquivoSimples||' Numpar : '||v_numpar||' Anousu : '||iAnousu,lRaise,false,false);

                      if v_numprejapago is null and iNumpreArquivoSimples is null  then

                        perform fc_debug('2 -- deletando do arrecad numpre : '||v_numpre||' numpar : '||v_numpar,lRaise,false,false);

                        delete from arrecad where k00_numpre = v_numpre and k00_numpar = v_numpar ;

                        perform * from fc_statusdebitos(v_numpre,v_numpar) where rtstatus = 'PAGO' or rtstatus = 'CANCELADO'  limit 1;
                        if found then
                          continue;
                        end if;

                        perform * from issvardiv
                                  inner join issvar    on q05_codigo = q19_issvar
                                  inner join arreinscr on k00_numpre = q05_numpre
                            where issvar.q05_ano       = iAnousu
                              and issvar.q05_mes       = v_numpar
                              and arreinscr.k00_inscr  = iInscr ;

                        if found then
                          return '28 - INSCRICAO COM CALCULO IMPORTADO PARA DIVIDA';
                        end if;

                        perform fc_debug('deletando do issvar Ano : '||iAnousu||' Mes : '||v_numpar||' Inscricao : '||iInscr,lRaise,false,false);
                        perform *
                           from arreinscr
                                inner join issvar    on issvar.q05_numpre    = arreinscr.k00_numpre
                                inner join issvarlev on issvarlev.q18_codigo = issvar.q05_codigo
                          where issvar.q05_ano       = iAnousu
                            and issvar.q05_mes       = v_numpar
                            and arreinscr.k00_inscr  = iInscr ;
                        if found then
                          return '28 - EMPRESA JA POSSUI LEVANTAMENTO FISCAL PARA COMPETENCIA : '||v_numpar||'/'||iAnousu ;
                        end if;

                       delete from issvarnotas
                        using issvar
                        where issvar.q05_codigo = issvarnotas.q06_codigo
                          and issvar.q05_ano    = iAnousu
                          and issvar.q05_mes    = v_numpar ;

                        delete from issvar
                         using arreinscr
                         where issvar.q05_ano       = iAnousu
                           and issvar.q05_mes       = v_numpar
                           and arreinscr.k00_inscr  = iInscr
                           and arreinscr.k00_numpre = issvar.q05_numpre ;

                        delete from informacaodebito
                         where informacaodebito.k163_numpre = v_numpre
                           and informacaodebito.k163_numpar = v_numpar;

                        insert into issvar (q05_codigo, q05_numpre, q05_numpar, q05_valor, q05_ano, q05_mes, q05_histor, q05_aliq, q05_bruto)
                                    values (nextval('issvar_q05_codigo_seq'), v_numpre, v_numpar, v_valorgrav, iAnousu, v_numpar, v_descrvariavel, v_valor, 0);

                        perform fc_debug('INSERT NUMERO 1 NO ARRECAD (issvar)',lRaise,false,false);
                        perform fc_debug('Numpre : '||v_numpre||' Numpar : '||v_numpar||' Valor : '||round(v_valorgrav,2)||' Vencimento : '||v_vencvar||' Tipo : '||v_record_cadvenc.q92_tipo,lRaise,false,false);

                        insert into arrecad (k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numpre, k00_numpar, k00_numtot, k00_numdig, k00_tipo)
                             values (v_numcgm,v_vencvar,v_receita,v_record_cadvenc.q82_hist,round(v_valorgrav,2),v_vencvar,v_numpre,v_numpar,v_numtot,v_numdig,v_record_cadvenc.q92_tipo);

                        if v_codbco != 0 and v_comcalculo is false then

                          select fc_numbco(v_codbco,v_codage) into v_numbco;
                          /*
                              Comentado insert na arrebanco conforme solicitado pelo Paulo e Dalpozzo, pois nao faz
                              o menor sentido gerar arrebanco no calculo de issqn / alvara
                           */
                          --insert into arrebanco values (v_numpre,v_numpar,v_codbco,v_codage,v_numbco,'');

                        end if;

                      else
                        perform fc_debug('Ja pago',lRaise,false,false);
                      end if;

                      v_numpar = v_numpar + 1;

                    end if;

                  end loop;

                  perform fc_debug('FIM DO PROCESSAMENTO DO VARIAVEL',lRaise,false,false);

                else  -- SE NAO FOR VARIAVEL

                  perform fc_debug('',lRaise,false,false);
                  perform fc_debug('--------------------- P R O C E S S A N D O   (  N A O  )  V A R I A V E L  ---------------------',lRaise,false,false);
                  perform fc_debug('vencimento: '||v_record_cadcalc.codven,lRaise,false,false);
                  perform fc_debug('',lRaise,false,false);

                  v_manual = v_manual || 'processando com base no cadastro de vencimentos\n';

                  lJaPassouUltVenc   = false;
                  v_jagravou         = false;
                  iQtdeVencProcessar = 0;

                  -- busca quantas parcelas vai calcular e registrar no arrecad (variavel iQtdeVencProcessar)
                  select count(*)
                    into v_totvenc
                    from cadvencdesc
                         inner join cadvenc on q82_codigo = q92_codigo
                   where cadvencdesc.q92_codigo = v_record_cadcalc.codven ;

                  select count(*)
                    into iQtdeVencProcessar
                    from cadvencdesc
                         inner join cadvenc on q82_codigo = q92_codigo
                   where cadvencdesc.q92_codigo = v_record_cadcalc.codven
                     and ( case
                             when cadvencdesc.q92_formacalcparcvenc = 1
                               then case when q82_venc >= dInicioAtividade then true else false end
                             when cadvencdesc.q92_formacalcparcvenc = 3
                               then q82_venc >= dDatahj and q82_venc >= dInicioAtividade
                             else
                               case
                                 when cadvenc.q82_calculaparcvenc is true
                                   then true
                                 else
                                   q82_venc >= dDatahj and q82_venc >= dInicioAtividade
                               end
                           end );
                     -- esse case tem a finalidade de processar ou nao parcelas
                     -- vencidas de acordo com os parametros do cadastro de
                     -- vencimentos(cadvencdescm,cadvenc)
                  perform fc_debug('Pesquisando quantidade de vencimentos no periodo de atividade',lRaise,false,false);
                  perform fc_debug('Data inicial : '||coalesce(dInicioAtividade,'2999-01-01'::date)    ,lRaise,false,false);
                  perform fc_debug('Data final   : '||iUltimoDiaAno ,lRaise,false,false);
                  perform fc_debug('Quantidade de vencimentos encontrada : '||iQtdeVencProcessar||''    ,lRaise,false,false);

                  -- utiliza a variavel iTotalVencimentosCad, que e o total de vencimentos para mais abaixo saber em que parcela jogar os centavos de diferenca de arredondamento
                  -- utiliza a variavel dMaiorVencimentoCadvenc para no caso da empresa iniciar depois do ultimo vencimento, calcular tudo em uma parcela e jogar no vencimento 31-12
                  select max(cadvenc.q82_venc), coalesce(max(cadvencdesc.q92_diasvcto),0), count(*)
                    into dMaiorVencimentoCadvenc, iDiasParaVencimento, iTotalVencimentosCad
                    from cadvencdesc
                         inner join cadvenc on q82_codigo = q92_codigo
                   where cadvencdesc.q92_codigo = v_record_cadcalc.codven;

                  -- passando conteudo da variavel do cissqn para cadvencdesc
                  iDiasvctoCissqn := iDiasParaVencimento;

                  perform fc_debug('Quantidade de vencimentos a processar : '||iQtdeVencProcessar,  lRaise,false,false);
                  perform fc_debug('Dias para o vencimento(q92_diasvcto)  : '||iDiasParaVencimento, lRaise,false,false);
                  perform fc_debug('Maior vencimento do cadastro          : '||dMaiorVencimentoCadvenc||' Data atual : '||dDatahj,lRaise,false,false);

                  select count(*), sum(k00_valor)
                    into iQtdParcelasPagas, nValorTotalPago
                    from ( select k00_numpre, k00_numpar, sum(k00_valor) as k00_valor
                             from arrecant
                            where k00_numpre = v_numpre
                         group by k00_numpre, k00_numpar ) as x;

                  perform fc_debug('Quantidade de parcelas pagas : '||coalesce(iQtdParcelasPagas,0)||' Valor total pago : '||coalesce(nValorTotalPago,0),lRaise,false,false);

                  begin

                    select case when count(*) = 1 then
                             1
                           else
                             ( count(*) - iQtdParcelasPagas )
                           end as qtdparcelas,

                           case when count(*) = 1 then
                             100
                           else
                             ( 100 / ( count(*) - iQtdParcelasPagas ) )
                           end as percpago

                      into iQtdParcelas, nPercPagoNovo
                      from cadvencdesc
                           left outer join cadvenc on q82_codigo = q92_codigo
                     where cadvencdesc.q92_codigo = v_record_cadcalc.codven
                       and ( case
                             when cadvencdesc.q92_formacalcparcvenc = 1
                               then case when q82_venc >= dInicioAtividade then true else false end
                             when cadvencdesc.q92_formacalcparcvenc = 3

                               then q82_venc >= dDatahj and q82_venc >= dInicioAtividade
                             else
                               case
                                 when cadvenc.q82_calculaparcvenc is true
                                   then true
                                 else
                                   q82_venc >= dDatahj and q82_venc >= dInicioAtividade
                               end
                           end ) ;

                   exception

                     when division_by_zero then
                       nPercPagoNovo := 0;

                   end;

                  if iQtdParcelasPagas = 0 then
                    nPercPagoNovo := 0;
                  end if;

                  /*
                   * Quando a quantidade total de parcelas for negativo, significa que  o calculo n„o possui mais
                   * parcelas em aberto, todas est„o abertas. logo, o total de parcelas devera ser 1
                   */
                  if iQtdParcelas < 0 then
                    iQtdParcelas = 1;
                  end if;

                  if nPercPagoNovo < 0 then
                    nPercPagoNovo = 100;
                  end if;

                  nDescontoPagamentoParcela := coalesce( ( nValorTotalPago / iQtdParcelas ) ,0);

                  perform fc_debug(' PROCURANDO PAGAMENTOS : ',lRaise,false,false);
                  perform fc_debug('--------------------------------------------------------------------------------------------',lRaise,false,false);
                  perform fc_debug(' TOTAL PAGO           : '||coalesce( nValorTotalPago, 0)           ,lRaise,false,false);
                  perform fc_debug(' PARCELAS PAGAS       : '||coalesce( iQtdParcelasPagas, 0)         ,lRaise,false,false);
                  perform fc_debug(' PARCELAS A CALCULAR  : '||coalesce( iQtdParcelas, 0)              ,lRaise,false,false);
                  perform fc_debug(' DESCONTO POR PARCELA : '||coalesce( nDescontoPagamentoParcela, 0) ,lRaise,false,false);
                  perform fc_debug(' PERCENTUAL NOVO      : '||coalesce( nPercPagoNovo, 0)             ,lRaise,false,false);
                  perform fc_debug('--------------------------------------------------------------------------------------------',lRaise,false,false);

                  -- sempre deve deletar o calculo anterior no caso de encontrar um
                  perform fc_debug('DELETANDO DO ARRECAD NUMPRE : '||v_numpre,lRaise,false,false);
                  delete from arrecad where k00_numpre = v_numpre;

                  v_vencproc := 0;

                  -- Loop que gera/processa financeiro
                  for v_record_cadvenc in select *
                                            from cadvencdesc
                                                 left join cadvenc on q82_codigo = q92_codigo
                                           where cadvencdesc.q92_codigo = v_record_cadcalc.codven
                                        order by q82_parc
                  loop

                    perform fc_debug('------------------------------------------------------------------------------------------',lRaise,false,false);
                    perform fc_debug('Processando Vencimento : '||v_record_cadvenc.q82_venc||' Parcela : '||v_record_cadvenc.q82_parc||' Inicio : '||v_record_cadcalc.inicio,lRaise,false,false);
                    perform fc_debug('------------------------------------------------------------------------------------------',lRaise,false,false);

                    /**
                     * Guardar o vencimento atual do cadvenc
                     */
                    dVencimentoAtual := v_record_cadvenc.q82_venc;

                    if dVencimentoAtual is null then
                      dVencimentoAtual := dDatahj;
                    end if;

                    /**
                     * Se a quantidade de vencimentos a processar for igual a zero
                     */
                    if iQtdeVencProcessar = 0 then

                      -- soma dias para o vencimento na data do ultimo vencimento
                      if iDiasvctoCissqn > 0 then

                        dVencimentoAtual := ( dDatahj + iDiasvctoCissqn )::date;
                        perform fc_debug('Trocou o vencimento para : '||dVencimentoAtual,lRaise,false,false);
                      else

                        -- se mesmo somando os dias para vencimento o debito continuar vencido joga para 31/12
                        dVencimentoAtual := cast(to_char(iAnousu,'9999')||'-12-31' as date);
                        perform fc_debug('Trocou o vencimento para ultimo dia do ano : '||dVencimentoAtual,lRaise,false,false);
                      end if;

                    end if;

                    perform fc_debug('Vencimento apos processamento das regras : '||dVencimentoAtual,lRaise,false,false);

                    /**
                     * Variavel para controlar a forma para calculo de parcelas vencidas
                     */
                    lProcessaParcVencidas := ( case
                                                 when v_record_cadvenc.q92_formacalcparcvenc = 1
                                                   then true
                                                 when v_record_cadvenc.q92_formacalcparcvenc = 3
                                                   then v_record_cadvenc.q82_venc between dDatahj and iUltimoDiaAno
                                                 else
                                                   case
                                                     when v_record_cadvenc.q82_calculaparcvenc is true
                                                       then true
                                                     else
                                                       v_record_cadvenc.q82_venc between dDatahj and iUltimoDiaAno
                                                   end
                                               end );

                    perform fc_debug('Processando parcelas vencidas(lProcessaParcVencidas) ? '||(case when lProcessaParcVencidas is true then 'SIM' else 'NAO' end),lRaise,false,false);

                    v_vencproc       = v_vencproc + 1;
                    lProcessaParcela = false;

                    perform fc_debug('Vencimento do cadvenc : '||dVencimentoAtual||' Maior Vencimento : '||dMaiorVencimentoCadvenc,lRaise,false,false);

                    -- se vencimento do cadvenc do registro atual do for maior que o maximo vencimento do cadvenc
                    if v_record_cadvenc.q82_venc > dMaiorVencimentoCadvenc then

                      perform fc_debug('',lRaise,false,false);
                      perform fc_debug('Vencimento do cadastro de vencimentos maior que o maximo vencimento, pasando lJaPassouUltVenc para true',lRaise,false,false);
                      perform fc_debug('',lRaise,false,false);
                      lJaPassouUltVenc = true;
                    end if;

                    perform fc_debug('Proximo vencimento : '||v_vencproc||'  Total de vencimentos : '||iTotalVencimentosCad||' Passa: '||( case when lProcessaParcela is true then 'true' else 'false' end ),lRaise,false,false);

                    perform fc_debug('Data da baixa em branco',lRaise,false,false);
                    lProcessaParcela = true;

                    if extract( year from v_record_cadcalc.inicio) <> iAnousu then

                      perform fc_debug('Ano de inicio diferente do atual ',lRaise,false,false);
                      lProcessaParcela = true;
                      v_numtot         = v_totvenc;

                    else

                      perform fc_debug('Ano de inicio igual do atual ',lRaise,false,false);
                      if v_record_cadvenc.q82_venc >= dInicioAtividade or dVencimentoAtual is null or iQtdeVencProcessar = 0 or lProcessaParcVencidas is true then

                        perform fc_debug('Vencimento: '||dVencimentoAtual||' maior ou igual a inicio: '||dInicioAtividade||' ou vencimento is null ',lRaise,false,false);
                        lProcessaParcela = true;

                      else

                        perform fc_debug('Passando lProcessaParcela para FALSE -- Vencimento: '||dVencimentoAtual||' menor que inicio: '||dInicioAtividade,lRaise,false,false);
                        lProcessaParcela = false;

                      end if;
                      v_numtot = iQtdeVencProcessar;

                    end if;

                    if dDtbaixa is not null and dVencimentoAtual > dDtbaixa then

                      perform fc_debug('Data de baixa : '||dDtbaixa,lRaise,false,false);
                      perform fc_debug('1 - Passando lProcessaParcela para false',lRaise,false,false);
                    end if;

                    if dDatahj > dVencimentoAtual and lProcessaParcVencidas is false then

                      perform fc_debug('Inicio maior que data de vencimento e processar parcelas vencidas NAO',lRaise,false,false);
                      lProcessaParcela = false;
                    end if;

                    perform fc_debug('----------------------------------------------------------------------------------------------',lRaise,false,false);
                    perform fc_debug('Total de vencimentos     : '||iTotalVencimentosCad ,lRaise,false,false);
                    perform fc_debug('Vecimentos do cadastro   : '||v_vencproc,lRaise,false,false);
                    perform fc_debug('Qtd parcelas a calcular  : '||v_totvenc,lRaise,false,false);
                    perform fc_debug('Ja passou ult vencimento : '||(case when lJaPassouUltVenc is true then 'SIM' else 'NAO' end),lRaise,false,false);
                    perform fc_debug('Inicio                   : '||v_record_cadcalc.inicio,lRaise,false,false);
                    perform fc_debug('Vencimento do Cadastro   : '||dVencimentoAtual,lRaise,false,false);
                    perform fc_debug('----------------------------------------------------------------------------------------------',lRaise,false,false);

                    if    v_record_cadcalc.inicio > dVencimentoAtual
                      and iTotalVencimentosCad <> v_vencproc
                      and lJaPassouUltVenc is false                 then

                      perform fc_debug('Passando lProcessaParcela para false',lRaise,false,false);
                      lProcessaParcela = false;
                    end if;

                    perform fc_debug('Passando para o proximo vencimento(lProcessaParcela) ? '||(case when lProcessaParcela is true then 'True' else 'False' end),lRaise,false,false);

                    if lProcessaParcela is true then

                      -- DESCOBRIR EM QUE CASO E UTILIZADO
                      --
                      -- Desabilitado o if abaixo pois È justamente ele que impede o calculo para anos posteriores,
                      v_venc = dVencimentoAtual;

                      perform fc_debug('Quantidade de vencimentos(iQtdeVencProcessar) a processar : '||iQtdeVencProcessar,lRaise,false,false);
                      if iQtdeVencProcessar = 0 then
                        nPercentualParcela = 100;
                      else

                        -- Verificacao do valor total proporcional
                        if v_record_cadcalc.tipopro = 'D' then

                          v_venc     = v_record_cadvenc.q82_venc;
                          v_venccalc = v_venc;
                          if lJaPassouUltVenc is true or v_record_cadvenc.q82_venc = dMaiorVencimentoCadvenc then

                            v_venccalc = to_char(iAnousu,'9999') || '-12-31';
                            dMaiorVencimentoCadvenc  = v_venccalc;

                          end if;
                          v_anoiniciocalc := extract(year from v_record_cadcalc.inicio);
                          if v_anoiniciocalc < iAnousu then
                            dInicioAtividadecalc = to_char(iAnousu,'9999') || '-01-01';
                          else
                            dInicioAtividadecalc = v_record_cadcalc.inicio;
                          end if;

                          perform fc_debug('Total de vencimentos : '||dMaiorVencimentoCadvenc||' Inicio: '||dInicioAtividadecalc,lRaise,false,false);
                          v_diasdesdeinicio = (iUltimoDiaAno - dInicioAtividadecalc)::integer + 1;
                          perform fc_debug('Vencimento calculo : '||v_venccalc||' Inicio: '||dInicioAtividadecalc,lRaise,false,false);
                          v_diasdestevcto = ((v_venccalc - dInicioAtividadecalc)::integer + 1) - v_diasjasomados;

                          if bGeral is true or dDtbaixa is not null then
                            nPercentualParcela = 100::float8 / iQtdeVencProcessar::float8;
                          else
                            nPercentualParcela = (100::float8 / v_diasdesdeinicio::float8)::float8 * v_diasdestevcto::float8;
                            v_diasjasomados = v_diasjasomados + v_diasdestevcto;
                          end if;

                        else

                          nPercentualParcela = 100::float8 / iQtdParcelas::float8;

                        end if;
                      end if;

                      perform fc_debug('Vencimento : '||v_venc||' Dias de vencimento : '||iDiasvctoCissqn,lRaise,false,false);

                      if v_venc is null and iAnousu < v_anoatualservidor then

                        v_venc = to_char(iAnousu,'9999')||'-12-31';

                        --- verificado o vcto do alvara quando calculado, data calc. + 30 dias
                        if iDiasvctoCissqn > 0 and iAnousu = v_anoatualservidor then

                          select dDatahj + iDiasvctoCissqn
                            into v_venc;

                          v_manual = v_manual || 'alterado vencimento para: '||v_venc||'\n';

                        end if;
                      end if;

                      perform fc_debug('Percentual : '||nPercentualParcela,lRaise,false,false);
                      perform fc_debug('Vencimento : '||v_venc,lRaise,false,false);

                      if v_prim is false then
                        if v_comcalculo is false then
                          insert into arreinscr values (v_numpre,iInscr);
                        else
                          perform fc_debug('deletando do isscalc o numpre : '||v_numpre,lRaise,false,false);
                          v_manual = v_manual || 'deletando numpre do isscalc e arrecad: ' || v_numpre || '\n';
                          delete from isscalc where q01_numpre = v_numpre;
                        end if;

                        insert into isscalc values (iAnousu, iInscr, v_record_cadcalc.cadcalc, v_receita, v_numpre, v_record_cadcalc.valor);
                        insert into numpres values ( v_numpre );
                        v_prim = true;

                      end if;

                      /**
                       * Valida quando executa calculo geral
                       */
                      if bGeral is true then

                        /**
                         * Subtraido valor que ja foi pago, caso contrario subtraira 0
                         */
                        nValorParcela = round( ( (v_record_cadcalc.valorintegr) * nPercentualParcela / 100) - coalesce(nDescontoPagamentoParcela, 0), 2);

                        perform fc_debug('(1) Valor Parcela : '||nValorParcela,lRaise,false,false);
                        perform fc_debug('Valor Total       : '||v_record_cadcalc.valorintegr||' Valor Parcial(parcela) : '||nValorParcela,lRaise,false,false);

                        /**
                         * Ultima Parcela
                         */
                        if v_numpar = v_numtot then

                          /**
                           * Arredonda os centavos e joga na ultima
                           */
                          perform fc_debug('',lRaise,false,false);
                          perform fc_debug('(4) Valor Parcela : '||nValorParcela,lRaise,false,false);
                          nValorParcela = nValorParcela + ((v_record_cadcalc.valorintegr) - (nValorParcela * v_numtot));
                          perform fc_debug('(5) Valor Parcela : '||nValorParcela,lRaise,false,false);
                        end if;

                        perform fc_debug('100 -- Delete from arrecad numpre : '||v_numpre||' numpar : '||v_record_cadvenc.q82_parc,lRaise,false,false);

                        -- se a parcela esta paga ou cancelada passa para a proxima
                        perform * from fc_statusdebitos(v_numpre, v_record_cadvenc.q82_parc)
                          where rtstatus = 'PAGO'
                             or rtstatus = 'CANCELADO' limit 1;
                        if found then

                          perform fc_debug('1 -- PARCELA '||v_record_cadvenc.q82_parc||' ESTA PAGA OU CANCELADA ',lRaise,false,false);
                          continue;
                        end if;

                        nValorParcela := round(nValorParcela,2);
                        perform fc_debug('INSERT NUMERO 2 NO ARRECAD',lRaise,false,false);
                        perform fc_debug('Numpre : '||v_numpre||' Numpar : '||v_numpar||' Valor : '||round(nValorParcela,2)||' Vencimento : '||v_venc||' Tipo : '||v_record_cadvenc.q92_tipo,lRaise,false,false);

                        if nValorParcela > 0 then

                          /**
                           * Alterado para inserir o q82_parc ao inves do numpar pois a pl nao levava em consideracao parcelas
                           * pagas e ou canceladas e assim gerando inconsistencia com numpar pago/cancelado e em aberto ao mesmo tempo
                           */
                          insert into arrecad (k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numpre, k00_numpar, k00_numtot, k00_numdig, k00_tipo)
                               values (v_numcgm, v_dtbase, v_receita, v_record_cadvenc.q92_hist, round(nValorParcela,2),
                                       v_venc, v_numpre, v_record_cadvenc.q82_parc, v_numtot, v_numdig, v_record_cadvenc.q92_tipo);

                        end if;

                      else

                        -- se a parcela esta paga ou cancelada passa para a proxima
                        perform * from fc_statusdebitos(v_numpre, v_record_cadvenc.q82_parc)
                          where rtstatus = 'PAGO'
                             or rtstatus = 'CANCELADO' limit 1;

                        if found then

                          perform fc_debug('2 -- PARCELA '||v_record_cadvenc.q82_parc||' ESTA PAGA OU CANCELADA ',lRaise,false,false);
                          v_numpar = v_numpar + 1;
                          continue;

                          /**
                           * Replicada mesma logica descrita acima quando o calculo eh geral
                           */
                        else
                          v_numpar = v_record_cadvenc.q82_parc;
                        end if;

                        if v_numpar is null then
                          v_numpar := 1;
                        end if;

                        v_dtvencano   = v_venc;
                        nValorParcela = round( ( ( v_record_cadcalc.valorintegr * nPercentualParcela ) / 100 ) - nDescontoPagamentoParcela,2 );

                        v_manual = v_manual || 'valor da parcela: ' || nValorParcela || '\n';

                        perform fc_debug('Valor integral        : '||v_record_cadcalc.valorintegr||' Percentual : '||nPercentualParcela,lRaise,false,false);
                        perform fc_debug('Valor da parcela      : '||nValorParcela,lRaise,false,false);
                        perform fc_debug('Percentual da parcela : '||nPercentualParcela,lRaise,false,false);
                        perform fc_debug('Desconto da parcela   : '||nDescontoPagamentoParcela,lRaise,false,false);

                        perform fc_debug('300 -- Delete from arrecad numpre : '||v_numpre||' numpar : '||v_numpar,lRaise,false,false);

                        if nValorParcela > 0 then

                          perform fc_debug('INSERT NUMERO 3 NO ARRECAD',lRaise,false,false);
                          perform fc_debug('Numpre : '||v_numpre||' Numpar : '||v_numpar||' Valor : '||round(nValorParcela,2)||' - Vencimento : '||v_dtvencano||' Tipo : '||v_record_cadvenc.q92_tipo,lRaise,false,false);

                          /**
                           * Caso Seja Calculo de Alvar·, valida quantaas parcelas foram informadas para o c·lculo de Alvara
                           *
                           * Seguindo pelo principio:
                           * -- Dividir o Valor Pela Quantidade
                           * -- Criar parcelas do alvara sempre com base de vencimento
                           * -- Gravar nova estrutura de dÈbitos no arrecad
                           **/
                           if v_record_cadcalc.cadcalc = 1  and iNumeroParcelasAlvara > 1 then

                            perform fc_debug('+----------------------------------------------',lRaise,false,false);
                            perform fc_debug('| Divisao da Parcela do Alvara em - '|| iNumeroParcelasAlvara ||' - partes ',lRaise,false,false);
                            perform fc_debug('+--------------------------------------------',lRaise,false,false);

                             for rParcelasAlvara
                              in select numero_parcela,
                                        valor_parcela,
                                        data_vencimento
                                   from fc_issqn_divide_valores(round(nValorParcela,2), iNumeroParcelasAlvara,v_dtvencano )
                             loop
                               -- ---------------------------- --
                               --  Inserindo Dados no Arrecad  --
                               -- ---------------------------- --
                               perform fc_debug('| Parcela           : '|| rParcelasAlvara.numero_parcela ,lRaise,false,false);
                               perform fc_debug('| ValorParcela      : '|| rParcelasAlvara.valor_parcela  ,lRaise,false,false);
                               perform fc_debug('| VencimentoParcela : '|| rParcelasAlvara.data_vencimento,lRaise,false,false);


                                insert
                                  into
                               arrecad (k00_numcgm,
                                        k00_dtoper,
                                        k00_receit,
                                        k00_hist,
                                        k00_valor,
                                        k00_dtvenc,
                                        k00_numpre,
                                        k00_numpar,
                                        k00_numtot,
                                        k00_numdig,
                                        k00_tipo)
                                values (v_numcgm,
                                        v_dtbase,
                                        v_receita,
                                        v_record_cadvenc.
                                        q92_hist,
                                        rParcelasAlvara.valor_parcela,   -- VALOR ANTERIOR -> round(nValorParcela,2)
                                        rParcelasAlvara.data_vencimento, -- VALOR ANTERIOR -> v_dtvencano,
                                        v_numpre,
                                        rParcelasAlvara.numero_parcela,  -- VALOR ANTERIOR -> v_numpar
                                        iNumeroParcelasAlvara,           -- VALOR ANTERIOR -> v_numtot
                                        v_numdig,
                                        v_record_cadvenc.q92_tipo);

                             perform fc_debug('+--------------------------------------------',lRaise,false,false);
                             end loop;

                           else -- Fim da Validacao de parcelas e Tipo de Calculo, seguindo como era antes

                            iNumTot = v_numtot;
                            if v_numtot = 0 then
                              iNumTot = 1;
                            end if;

                            insert into arrecad (k00_numcgm, k00_dtoper, k00_receit, k00_hist, k00_valor, k00_dtvenc, k00_numpre, k00_numpar, k00_numtot, k00_numdig, k00_tipo)
                                 values (v_numcgm, v_dtbase, v_receita, v_record_cadvenc.q92_hist, round(nValorParcela,2), v_dtvencano, v_numpre, v_numpar, iNumTot, v_numdig, v_record_cadvenc.q92_tipo);
                          end if;
                        end if;

                      end if; -- final do bloco para bgeral is false
                      perform fc_debug('Valor parcela : '||nValorParcela,lRaise,false,false);

                      if v_comcalculo is false then

                        select fc_numbco(v_codbco,v_codage) into v_numbco;
                        /*
                          Comentado insert na arrebanco conforme solicitado pelo Paulo e Dalpozzo, pois nao faz
                          o menor sentido gerar arrebanco no calculo de issqn / alvara
                        */
                        --insert into arrebanco values (v_numpre,v_numpar,v_codbco,v_codage,v_numbco);
                      end if;

                      v_numpar = v_numpar + 1;
                      v_jagravou = true;

                      if nPercentualParcela = 100 then

                        perform fc_debug('Saindo do for percentual = 100',lRaise,false,false);
                        exit;
                      end if;

                    -- se lProcessaParcela is false
                    else

                      v_manual = v_manual || 'nao utilizando vencimento: ' || v_record_cadvenc.q82_venc || '\n';
                      perform fc_debug('Nao processando financeiro lProcessaParcela is false',lRaise,false,false);

                    end if;

                  end loop;

                end if;

              end if;

            end loop;


            for v_record_cadcalc in select * from numpres loop

              select q01_manual
                into sManualText
                from isscalc
               where q01_inscr  = iInscr
                 and q01_anousu = iAnousu
                 and q01_manual is not null
               limit 1;

              v_manual := v_manual || 'atualizando log do calculo do numpre: ' || v_record_cadcalc.numpre || '\n';
              update isscalc
                 set q01_manual = coalesce(sManualText,' ') || v_manual
               where q01_inscr = iInscr and q01_anousu = iAnousu;

            end loop;

            return '01-OK';

            end;

            $$ language 'plpgsql';
EOL;
        $this->execute($sSqlVencimentosIssqn);
    }
}
