<?php

use Classes\PostgresMigration;

class M9479Parcelamento extends PostgresMigration
{

    public function up()
    {
        $sSql =
<<< SQL
-- quando reparcelar um parcelamento que e de 3 matriculas, tem que gerar 3 arrematric do novo numpre
-- revisar bem parcelamento de diversos e melhorias
-- considera-se que nao pode se parcelar inicial com outro tipo de debito, mesmo que seja outro parcelamento de inicial,
-- mas podemos parcelar mais de uma inicial no mesmo parcelamento, por isso que existe a tabela termoini

-- tipos de testes
-- parcelar um diversos
-- reparcelar um diversos
-- parcelar 2 diversos, um de cada procedencia
-- parcelar 1 diversos e um parcelamento de diversos

set client_encoding = 'LATIN1';

set check_function_bodies to on;
create or replace function fc_parcelamento(integer,date,date,integer,integer,float8,integer,integer,integer,integer,float8,float8,text,integer)
returns varchar(100)
as $$
declare

  v_cgmresp                        alias for $1;  -- cgm do responsavel pelo parcelamento
  v_privenc                        alias for $2;  -- vencimento da entrada
  v_segvenc                        alias for $3;  -- vencimento da parcela 2
  v_diaprox                        alias for $4;  -- dia de vencimento da parcela 3 em diante
  v_totparc                        alias for $5;  -- total de parcelas
  v_entrada                        alias for $6;  -- valor da entrada
  v_login                          alias for $7;  -- login de quem fez o parcelamento
  v_cadtipo                        alias for $8;  -- tipo de debito dos registros selecionados
  v_desconto                       alias for $9;  -- regra de parcelamento utilizada
  v_temdesconto                    alias for $10; -- se tem desconto (nao utilizada)
  v_valorparcela                   alias for $11; -- valor de cada parcela
  v_valultimaparcela               alias for $12; -- valor da ultima parcela

  sObservacao                      alias for $13; -- observacao do parcelamento
  iProcesso                        alias for $14; -- codigo do processo (protprocesso)

  v_ultparc                        integer default 2;
  v_matric                         integer default 0;
  v_inscr                          integer default 0;

  iUltMatric                       integer;
  iUltNumpre                       integer;
  iUltNumpar                       integer;
  iUltReceit                       integer;

  iSeqArrecKey                     integer;
  iSeqArrecadcompos                integer;

  v_anousu                         integer;
  v_totpar                         integer;
  v_cgmpri                         integer;
  v_somar1                         integer;
  v_somar2                         integer;
  v_numpre                         integer;
  v_receita                        integer;
  v_termo                          integer;
  v_termo_ori                      integer;
  v_tipo                           integer;
  v_tiponovo                       integer;
  v_quantparcel                    integer;
  v_var                            integer;
  v_inicialmov                     integer;
  v_totparcdestarec                integer;
  v_contador                       integer;
  v_cadtipoparc                    integer;
  v_recdestino                     integer;
  v_dia                            integer;
  v_ultdiafev                      integer;
  v_maxrec                         integer;
  v_anovenc                        integer;
  v_mesvenc                        integer;
  v_totalparcelas                  integer;
  v_anovencprox                    integer;
  v_mesvencprox                    integer;
  v_recjurosultima                 integer;
  v_recmultaultima                 integer;
  v_histjuro                       integer;
  v_proxmessegvenc                 integer;
  iInstit                          integer;
  iAnousu                          integer;
  iQtdRegistrosMatricula           integer;
  iQtdRegistrosInscricao           integer;
  ultimaparcelareceita             integer;

  v_totaldivida                    numeric default 0;
  v_somar                          numeric default 0;
  v_totalliquido                   numeric default 0;
  v_total_liquido                  numeric default 0;
  v_totalzao                       numeric default 0;

  v_calcula_valprop                numeric(15,10);
  v_calcula_valor                  float8 default 0;
  v_calcula_his                    numeric(15,2);
  v_calcula_cor                    numeric(15,2);
  v_calcula_jur                    numeric(15,2);
  v_calcula_mul                    numeric(15,2);
  v_calcula_desccor                numeric(15,2);
  v_calcula_descjur                numeric(15,2);
  v_calcula_descmul                numeric(15,2);
  nValorMaximoReceita              numeric(15,2);
  nValorMaximoHistorico            numeric(15,2);
  nValorMaximoCorrecao             numeric(15,2);
  nValorMaximoJuro                 numeric(15,2);
  nValorMaximoMulta                numeric(15,2);
  nValorMaximoDescontoCorrecao     numeric(15,2);
  nValorMaximoDescontoJuro         numeric(15,2);
  nValorMaximoDescontoMulta        numeric(15,2);

  nValidacaoPerc                   numeric(15,10);
  nPercentualVirtualCgm            numeric(15,10);
  nDiferencaPercentualCGM          numeric(15,10) default 0;
  nDiferencaPercentualAjuste       numeric(15,10);



  v_descontocor                    float8 default 0;
  v_tipodescontocor                integer default 0;
  v_descontojur                    float8 default 0;
  v_descontomul                    float8 default 0;
  v_total                          float8;
  v_totalcomjuro                   float8;
  v_valparc                        float8;
  v_diferencanaultima              float8;

  v_valorinserir                   float8;
  v_ent_prop                       float8;
  v_vlrateagora                    float8;
  v_totateagora                    float8;
  v_resto                          float8 default 0;
  v_teste                          float8;
  v_saldo                          float8;
  v_calcular                       float8;
  v_valorparcelanew                float8;
  v_valultimaparcelanew            float8;

  v_valdesccor                     float8;
  v_valdescjur                     float8;
  v_valdescmul                     float8;

  nValorTotalOrigem                float8;
  nPercCalc                        float8;
  nSomaPercMatric                  float8;
  nSomaPercInscr                   float8;
  nTotArreMatric                   float8;
  nTotArreInscr                    float8;

  nVlrHis                          numeric default 0;
  nVlrCor                          numeric default 0;
  nVlrJur                          numeric default 0;
  nVlrMul                          numeric default 0;
  nVlrDes                          numeric default 0;
  nPercMatric                      numeric default 0;
  nPercInscr                       numeric default 0;
  nPercCGM                         numeric default 0;
  lIncluiEmParcelas                boolean default false;

  nVlrTotalHistorico               numeric default 0;
  nVlrTotalCorrecao                numeric default 0;
  nVlrTotalJuros                   numeric default 0;
  nVlrTotalMulta                   numeric default 0;

  v_historico_compos               float8 default 0;
  v_correcao_compos                float8 default 0;
  v_juros_compos                   float8 default 0;
  v_multa_compos                   float8 default 0;

  nVlrHistoricoComposicao          numeric(15,2) default 0;
  nVlrCorrecaoComposicao           numeric(15,2) default 0;
  nVlrJurosComposicao              numeric(15,2) default 0;
  nVlrMultaComposicao              numeric(15,2) default 0;
  nVlrTotalParcelamento            numeric(15,2) default 0;
  nVlrTotalComposicao              numeric(15,2) default 0;
  nVlrDiferencaComposicaoTotal     numeric(15,2) default 0;

  nVlrTotalParcelamentoHistorico   numeric(15,2) default 0;
  nVlrTotalParcelamentoCorrigido   numeric(15,2) default 0;
  nVlrTotalParcelamentoJuros       numeric(15,2) default 0;
  nVlrTotalParcelamentoMulta       numeric(15,2) default 0;
  nVlrTotalDescontoCorrigido       numeric(15,2) default 0;
  nVlrTotalDescontoJuros           numeric(15,2) default 0;
  nVlrTotalDescontoMulta           numeric(15,2) default 0;

  nVlrDiferencaComposicaoHistorico numeric(15,2) default 0;
  nVlrDiferencaComposicaoCorrecao  numeric(15,2) default 0;
  nVlrDiferencaComposicaoJuros     numeric(15,2) default 0;
  nVlrDiferencaComposicaoMulta     numeric(15,2) default 0;

  v_ultdiafev_d                    date;
  v_vcto                           date;
  dDataUsu                         date;

  sArreoldJuncao                   varchar default '';
  v_proxmessegvenc_c               varchar(2);
  v_ultdiafev_c                    varchar(10);
  sStringUpdate                    varchar;
  sNumpreSemVinculoMatricInsc      text;

  v_comando                        text;
  v_comando_cria                   text;

  v_iniciais                       record;
  v_record_perc                    record;
  v_record_numpres                 record;
  v_record_numpar                  record;
  v_record_receitas                record;
  v_record_recpar                  record;
  v_record_origem                  record;
  v_record_desconto                record;
  rPercOrigem                      record;
  rSeparaJurMul                    record;
  rAjusteDiferencaPercentual       record;

  lTabelasCriadas                  boolean;
  v_parcnormal                     boolean default false; -- se tem divida ativa selecionada
  v_parcinicial                    boolean default false; -- se tem inicial selecionada
  lParcDiversos                    boolean default false; -- se tem diversos selecionado
  lParcContrib                     boolean default false; -- se tem contribuicao de melhoria selecionado
  lParcParc                        boolean default false; -- se tem parcelamento selecionado (caso esteja efetuando um reparcelamento)
  v_juronaultima                   boolean default false;
  v_descontar                      boolean default false;
  lSeparaJuroMulta                 integer default 2;
  lGravaArrecad                    boolean default true;
  lParcelaZerada                   boolean default false;
  lValidaParcInicial               boolean default false;

  lRaise                           boolean default false;

  v_record_parcelas_parcela            integer default 0;
  v_record_parcelas_receit             integer default 0;
  v_record_parcelas_receitaori         integer default 0;
  v_record_parcelas_hist               integer default 0;
  v_record_parcelas_valor              double precision default 0;
  v_record_parcelas_valprop            double precision default 0;
  v_record_parcelas_valhis             double precision default 0;
  v_record_parcelas_valcor             double precision default 0;
  v_record_parcelas_valjur             double precision default 0;
  v_record_parcelas_valmul             double precision default 0;
  v_record_parcelas_descor             double precision default 0;
  v_record_parcelas_descjur            double precision default 0;
  v_record_parcelas_descmul            double precision default 0;

  iCodcli          integer default  0;
  nFaixaInicial    float8  default -0.01;
  nFaixaFinal      float8  default  3.00;

  begin

    -- valores retornados:
    -- 1 = ok
    -- 2 = tentando parcelar mais de um tipo (k03_tipo) de debito
    -- 3 = tipo de debito nao configurado para parcelamento
    -- 4 = parcelamento nao encontrado pelo numpre
    -- 5 = tentando reparcelar mais de um parcelamento
    -- 6 = tentando parcelar mais de um numpre (debito)

    lRaise  := ( case when fc_getsession('DB_debugon') is null or fc_getsession('DB_debugon') = '' then false else true end );
    if lRaise is true then
      perform fc_debug('Processando parcelamento dos débitos...',lRaise,true,false);
    end if;

    v_totalparcelas       = v_totparc;
    v_valorparcelanew     = v_valorparcela;
    v_valultimaparcelanew = v_valultimaparcela;

    iInstit := cast(fc_getsession('DB_instit') as integer);
    if iInstit is null then
       raise exception 'Variavel de sessão [DB_instit] não encontrada.';
    end if;

    iAnousu := cast(fc_getsession('DB_anousu') as integer);
    if iAnousu is null then
       raise exception 'Variavel de sessão [DB_anousu] não encontrada.';
    end if;

    dDataUsu := cast(fc_getsession('DB_datausu') as date);
    if dDataUsu is null then
       raise exception 'Variavel de sessão [DB_datausu] não encontrada.';
    end if;

    select k03_separajurmulparc
      into lSeparaJuroMulta
      from numpref
     where k03_instit = iInstit
       and k03_anousu = iAnousu;

    --lSeparaJuroMulta = false;

    -- testa se existe algum tipo de parcelamento configurado
    select count(*)
      from tipoparc
     where instit = iInstit
      into v_contador;

    if v_contador is null then
      return '[0] - Sem configuracao na tabela tipoparc para instituicao %', iInstit;
    end if;

    if lRaise is true then
      perform fc_debug('verificando se tem mais de um tipo de debito...',lRaise,false,false);
    end if;

    -- existe uma tabela temporaria chamada totalportipo, criada antes de chamar a funcao de parcelamento
    -- que contem os valores a parcelar agrupada por tipo de debito
    -- nessa tabela existe a informacao se o tipo de debito tem direito a desconto ou nao

    -- a tabela numpres_parc contem os registros marcados na CGF pelo usuario
    -- cria indice na tabela utilizada durante os parcelamentos
    create index numpres_parc_in on numpres_parc using btree (k00_numpre, k00_numpar);

    -- for buscando as origens de cada debito selecionado para parcelar(numpres_parc)
    for v_record_origem in  select arretipo.k03_tipo,
                                   arrecad.k00_numpre,
                                   count(*)
                              from numpres_parc
                             inner join arrecad  on arrecad.k00_numpre = numpres_parc.k00_numpre
                             inner join arretipo on arretipo.k00_tipo  = arrecad.k00_tipo
                             group by arretipo.k03_tipo,
                                      arrecad.k00_numpre
    loop

      -- se origem(k03_tipo) for
      if v_record_origem.k03_tipo = 5 then

        -- 5 divida ativa
        v_parcnormal = true;

      elsif v_record_origem.k03_tipo = 18 then

        -- inicial do foro
        v_parcinicial = true;
        lValidaParcInicial = true;

      elsif v_record_origem.k03_tipo = 4 then

        -- contribuicao de melhoria
        lParcContrib = true;

      elsif v_record_origem.k03_tipo = 7 then

        -- diversos
        lParcDiversos = true;

      elsif v_record_origem.k03_tipo in (6,13,16,17) then

        -- reparcelamentos
        -- 6   parcelamento de divida
        -- 13  parcelamento de inicial de divida
        -- 16  parcelamento de diveros
        -- 17  parcelamento de contribuicao de melhoria
        lParcParc = true;

        if v_record_origem.k03_tipo = 13 then
          lValidaParcInicial = true;
        end if;

      end if;

      if lRaise is true then
        perform fc_debug('k00_tipo: '||v_record_origem.k03_tipo||'k00_numpre:'||v_record_origem.k00_numpre,lRaise,false,false);
      end if;

    end loop;

    if v_parcnormal is true and v_parcinicial is true then
      return '[1] - Nao pode ser parcela divida normal com ajuizada!';
    end if;

    -- se houver débitos do tipo Inicial verificamos se o parâmetro do Mód. Juridio PARTILHA está ativo 'SIM' e
    -- caso exista mais de um processo para as iniciais bloqueamos o parcelamento.
    if lValidaParcInicial is true then

       perform v19_partilha
         from parjuridico
        where v19_anousu = iAnousu
          and v19_instit = iInstit
          and v19_partilha is true;
       if found then

          perform count( distinct case
                                    when processoinicial.v71_processoforo is null
                                      then processoparcel.v71_processoforo
                                    else processoinicial.v71_processoforo end )
             from NUMPRES_PARC
                  left join inicialnumpre                          on inicialnumpre.v59_numpre        = NUMPRES_PARC.k00_numpre
                  left join processoforoinicial as processoinicial on processoinicial.v71_inicial     = inicialnumpre.v59_inicial
                  left join termo                                  on termo.v07_numpre                = NUMPRES_PARC.k00_numpre
                  left join termoini                               on termoini.parcel                 = termo.v07_parcel
                  left join processoforoinicial as processoparcel  on processoparcel.v71_inicial      = termoini.inicial
           having count(distinct case
                                   when processoinicial.v71_processoforo is null
                                     then processoparcel.v71_processoforo
                                   else processoinicial.v71_processoforo end) > 1;
          if found then
            return '[2] - Não é possível parcelar iniciais com processos do foro diferentes para um mesmo parcelamento! [Utilização de Partilha Ativada]';
          end if;

       end if;

    end if;

    if lRaise is true then
      perform fc_debug('guardando o tipo de debito...',lRaise,false,false);
    end if;

    v_tipo = v_cadtipo;

    if lRaise is true then
      perform fc_debug('guardando o tipo de debito...',lRaise,false,false);
    end if;

    -- select na termoconfigo para descobrir qual o tipo de debito
    -- que vai ser gerado com o debito do novo parcelamento
    -- tabela termotipoconfig tem o tipo de debito dos grupos de debitos
    -- que e possivel parcelar

    if lRaise is true then
      perform fc_debug('instit -- '||iInstit,lRaise,false,false);
    end if;

    select k42_tiponovo
      into v_tiponovo
      from termotipoconfig
     where k42_cadtipo = v_tipo
       and k42_instit  = iInstit;
    if not found then
      return '[3] - Este tipo de debito nao esta configurado para parcelamento';
    end if;

    if lRaise is true then
      perform fc_debug('tipo novo:'||v_tiponovo,lRaise,false,false);
    end if;

    -- cria tabela temporarias para utilizacao durante o calculo
    if lRaise is true then
       perform fc_debug('',lRaise,false,false);
       perform fc_debug('+--------------------------------------------------------------------------------------------------',lRaise,false,false);
       perform fc_debug('| ',lRaise,false,false);
       perform fc_debug('| CRIANDO TABELAS TEMPORARIAS PARA O PROCESSAMENTO DO PARCELAMENTO ',lRaise,false,false);
       perform fc_debug('| ',lRaise,false,false);
       perform fc_debug('+--------------------------------------------------------------------------------------------------',lRaise,false,false);
       perform fc_debug('',lRaise,false,false);
    end if;
    select fc_parc_criatemptable(lRaise)
      into lTabelasCriadas;
    if lTabelasCriadas is false then
      return '[4] - Problema ao criar as tabelas temporarias. ';
    end if;


    -- Desativado parâmetro para que não seja gerado registros na incorporação tributária
    perform fc_putsession('DB_utiliza_incorporacao','false');

    -- funcao que corrige o arrecad no caso de encontrar registros duplicados(numpre,numpar,receit)
    perform fc_corrigeparcelamento();

    -- Ativado parâmetro para que continue sendo gerado registros na incorporação tributária
    perform fc_putsession('DB_utiliza_incorporacao','true');

    -- testa se todas as parcelas do parcelamento foram marcadas,
    -- senao nao permite parcelar apenas algumas parcelas do parcelamento
    -- ou seja, ou parcela todas as parcelas do parcelamento, ou nada
    for v_record_origem in select distinct
                                  termo.v07_parcel
                             from numpres_parc
                            inner join termo on termo.v07_numpre = numpres_parc.k00_numpre
                            where k03_tipodebito <> 18
    loop

      -- soma a quantidade de parcelas do parcelamento
      select count(distinct arrecad.k00_numpar)
        into v_somar1
        from arrecad
       inner join termo on termo.v07_parcel = v_record_origem.v07_parcel
       where arrecad.k00_numpre = termo.v07_numpre;

      if lRaise is true then
        perform fc_debug('v_record_origem.v07_parcel: '||v_record_origem.v07_parcel,lRaise,false,false);
      end if;

      -- testa a quantidade de parcelas marcadas
      select count(distinct numpres_parc.k00_numpar)
        into v_somar2
        from numpres_parc
       inner join termo on termo.v07_parcel = v_record_origem.v07_parcel
       where numpres_parc.k00_numpre = termo.v07_numpre;

      if lRaise is true then
        perform fc_debug('Verificando quantidades de parcelaas marcadas com a quantidade de parcelas do débito: v_somar1: '||v_somar1||' - v_somar2: '||v_somar2,lRaise,false,false);
      end if;

      -- compara
      if v_somar1 <> v_somar2 then
        return '[5] - Todas as parcelas do parcelamento ' || v_record_origem.v07_parcel || ' devem ser marcadas!';
      end if;

    end loop;

    if lRaise is true then
      perform fc_debug('entrada'||v_entrada,lRaise,false,false);
      perform fc_debug('valor das parcelas:'||v_valorparcelanew,lRaise,false,false);
      perform fc_debug('valor da ultima parcela:'||v_valultimaparcelanew,lRaise,false,false);
      perform fc_debug('pegando cgm do(s) numpre(s) com arrecad...',lRaise,false,false);
    end if;

    -- busca cgm principal para gravar no arrecad posteriormente
    if v_parcinicial is true then

      select k00_numcgm
        into v_cgmpri
        from arrecad
             inner join numpres_parc on arrecad.k00_numpre = numpres_parc.k00_numpre
       limit 1;

    else

      select k00_numcgm
        into v_cgmpri
        from arrecad
             inner join numpres_parc on arrecad.k00_numpre = numpres_parc.k00_numpre
                                    and arrecad.k00_numpar = numpres_parc.k00_numpar
       limit 1;

    end if;

    if lRaise is true then
      perform fc_debug('Pegando cgm de acordo com matricula ou inscricao...',lRaise,false,false);
    end if;

    v_anousu := iAnousu;

    -- se for parcelamento de inicial
    if v_parcinicial is true then

      if lRaise is true then
        perform fc_debug('t i p o: 18',lRaise,false,false);
      end if;

      -- procura cgm principal por matricula ou inscricao
      for v_record_origem in select distinct
                                    arrematric.k00_matric,
                                    arreinscr.k00_inscr
                               from numpres_parc
                                    left join arrematric on arrematric.k00_numpre = numpres_parc.k00_numpre
                                    left join arreinscr  on arreinscr.k00_numpre  = numpres_parc.k00_numpre
                                    inner join arrecad    on arrecad.k00_numpre    = numpres_parc.k00_numpre
      loop

        if lRaise is true then
          perform fc_debug('processando... matricula: '||v_record_origem.k00_matric||' inscricao: '||v_record_origem.k00_inscr,lRaise,false,false);
        end if;

        if v_record_origem.k00_matric is not null then
          select j01_numcgm
            from iptubase
            into v_cgmpri
           where j01_matric = v_record_origem.k00_matric;
        end if;

        if v_record_origem.k00_inscr is not null then
          select q02_numcgm
            from issbase
            into v_cgmpri
           where q02_inscr = v_record_origem.k00_inscr;
        end if;

      end loop;

    -- senao for inicial do foro
    else

      if lRaise is true then
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug('Buscando CGM princial por matricula ou inscrição',lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
      end if;

      -- procura cgm principal por matricula ou inscricao
      for v_record_origem in select distinct
                                    arrematric.k00_matric,
                                    arreinscr.k00_inscr
                               from numpres_parc
                               left join arrematric on arrematric.k00_numpre = numpres_parc.k00_numpre
                               left join arreinscr  on arreinscr.k00_numpre  = numpres_parc.k00_numpre
                              inner join arrecad    on arrecad.k00_numpre    = numpres_parc.k00_numpre
                                                   and arrecad.k00_numpar = numpres_parc.k00_numpar
      loop

        if lRaise is true then
          perform fc_debug('Processando... matricula: '||v_record_origem.k00_matric||' inscricao: '||v_record_origem.k00_inscr, lRaise, false, false);
        end if;

        if v_record_origem.k00_matric is not null then
          select j01_numcgm
            from iptubase
            into v_cgmpri
           where j01_matric = v_record_origem.k00_matric;
        end if;

        if v_record_origem.k00_inscr is not null then
          select q02_numcgm
            from issbase
            into v_cgmpri
           where q02_inscr = v_record_origem.k00_inscr;
        end if;

      end loop;

      if lRaise is true then
        perform fc_debug('',lRaise,false,false);
        perform fc_debug('',lRaise,false,false);
        perform fc_debug('Fim da busca do CGM principal',lRaise,false,false);
        perform fc_debug('',lRaise,false,false);
      end if;

    end if;

    if lRaise is true  then
      perform fc_debug('agora vai processar correcao e tal...',lRaise,false,false);
    end if;

    -- se for inicial, traz apenas os numpres envolvidos, ja que no caso de parcelamento de inicial
    -- o usuario nao tem opcao de marcar as parcelas, tendo que parcelar toda a inicial
    -- se nao for inicial, traz os numpres com suas respectivas parcelas marcadas
    if v_parcinicial is true then
      v_comando = 'select distinct k00_numpre from numpres_parc';
    else
      v_comando = 'select distinct k00_numpre, k00_numpar from numpres_parc';
    end if;

    -- varre a lista de numpres/parcelas marcados pelo usuario
    for v_record_numpres in execute v_comando
    loop

      if lRaise is true then
        if v_parcinicial is false then
          perform fc_debug('      numpre '||v_record_numpres.k00_numpre||' - numpar: '||v_record_numpres.k00_numpar,lRaise, false, false);
        else
          perform fc_debug('      numpre '||v_record_numpres.k00_numpre||' - numpar: 0',lRaise, false, false);
        end if;
      end if;

      v_matric = 0;
      v_inscr  = 0;

      -- busca a matricula do numpre que esta sendo processado
      select k00_matric
        into v_var
        from arrematric
       where k00_numpre = v_record_numpres.k00_numpre;

      if v_var is not null then
        v_matric = v_var;

        if lRaise is true then
          perform fc_debug(' origem: matricula '||v_matric,lRaise,false,false);
        end if;
      end if;

      -- busca a inscricao do numpre que esta sendo processado
      select k00_inscr
        into v_var
        from arreinscr
       where k00_numpre = v_record_numpres.k00_numpre;

      if v_var is not null then
        v_inscr = v_var;

        if lRaise is true then
          perform fc_debug(' origem: inscricao '||v_inscr,lRaise,false,false);
        end if;
      end if;

      -- processa cada registro acumulando por numpre, parcela, receita e tipo de debito
      -- armazenando as informacoes de valor historico, corrigido, juros e multa
      -- na tabela arrecad_parc_rec para utilizacao em processamento futuro
      -- independente se for inicial ou nao

      -- se for inicial
      if v_parcinicial is true then

        if lRaise is true  then
          perform fc_debug('      entrando tipo 18...',lRaise,false,false);
        end if;

        for v_record_numpar in select k00_numpre,
                                      k00_numpar,
                                      k00_receit,
                                      k03_tipo,
                                      substr(fc_calcula,2,13)::float8  as vlrhis,
                                      substr(fc_calcula,15,13)::float8 as vlrcor,
                                      substr(fc_calcula,28,13)::float8 as vlrjuros,
                                      substr(fc_calcula,41,13)::float8 as vlrmulta,
                                      substr(fc_calcula,54,13)::float8 as vlrdesc,
                                      (substr(fc_calcula,15,13)::float8+substr(fc_calcula,28,13)::float8+substr(fc_calcula,41,13)::float8-substr(fc_calcula,54,13)::float8) as total
                                 from ( select k00_numpre,
                                               k00_numpar,
                                               k00_receit,
                                               k03_tipo,
                                               fc_calcula(k00_numpre,k00_numpar,k00_receit,dDataUsu,dDataUsu,v_anousu) as fc_calcula
                                          from ( select distinct
                                                        arrecad.k00_numpre,
                                                        arrecad.k00_numpar,
                                                        arrecad.k00_receit,
                                                        arretipo.k03_tipo
                                                   from arrecad
                                                        inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo
                                                  where arrecad.k00_numpre = v_record_numpres.k00_numpre
                                               ) as x
                                      ) as y
        loop

          select receit
            from arrecad_parc_rec
            into v_receita
           where numpre = v_record_numpar.k00_numpre
             and numpar = v_record_numpar.k00_numpar
             and receit = v_record_numpar.k00_receit;

          if lRaise is true then
            perform fc_debug('1 - numpre: '||v_record_numpar.k00_numpre||', numpar: '||v_record_numpar.k00_numpar||', receit: '||v_record_numpar.k00_receit||', v_receita: '||v_receita,lRaise,false,false);
          end if;

          -- se nao existe registro insere
          if v_receita is null then

            if lRaise is true then
              perform fc_debug(' ',lRaise,false,false);
              perform fc_debug('inserindo registro na arrecad_parc_rec',lRaise,false,false);
            end if;

            execute 'insert into arrecad_parc_rec values (' || v_record_numpar.k00_numpre || ','
                                                            || v_record_numpar.k00_numpar || ','
                                                            || v_record_numpar.k00_receit || ','
                                                            || v_record_numpar.k03_tipo   || ','
                                                            || v_record_numpar.vlrhis     || ','
                                                            || v_record_numpar.vlrcor     || ','
                                                            || v_record_numpar.vlrjuros   || ','
                                                            || v_record_numpar.vlrmulta   || ','
                                                            || v_record_numpar.vlrdesc    || ','
                                                            || v_record_numpar.total      || ','
                                                            || v_matric                   || ','
                                                            || v_inscr                    || ','
                                                            || 0                          || ','
                                                            || 0                          || ','
                                                            || 0                          || ','
                                                            || 'false'                    || ');';
          -- se ja existe, soma
          else

            execute 'update arrecad_parc_rec set valor   = valor  + ' || v_record_numpar.total    || ','
                                              || 'vlrhis = vlrhis + ' || v_record_numpar.vlrhis   || ','
                                              || 'vlrcor = vlrcor + ' || v_record_numpar.vlrcor   || ','
                                              || 'vlrjur = vlrjur + ' || v_record_numpar.vlrjuros || ','
                                              || 'vlrmul = vlrmul + ' || v_record_numpar.vlrmulta || ','
                                              || 'vlrdes = vlrdes + ' || v_record_numpar.vlrdesc
                                      || ' where numpre = ' || v_record_numpar.k00_numpre
                                      || '   and numpar = ' || v_record_numpar.k00_numpar
                                      || '   and receit = ' || v_record_numpar.k00_receit ||';';
          end if;

        end loop;

        if lRaise is true then
          perform fc_debug('      saindo do tipo 18...',lRaise,false,false);
        end if;

      else -- se nao for inicial foro


        if lRaise is true then
          perform fc_debug(' tipo diferente de 18 ',lRaise,false,false);
        end if;

        if lRaise is true then
          perform fc_debug('numpre: '||v_record_numpres.k00_numpre||' - numpar: '||v_record_numpres.k00_numpar,lRaise,false,false);
        end if;

        for v_record_numpar in select k00_numpre,
                                      k00_numpar,
                                      k00_receit,
                                      k03_tipo,
                                      substr(fc_calcula,2, 13)::float8 as vlrhis,
                                      substr(fc_calcula,15,13)::float8 as vlrcor,
                                      substr(fc_calcula,28,13)::float8 as vlrjuros,
                                      substr(fc_calcula,41,13)::float8 as vlrmulta,
                                      substr(fc_calcula,54,13)::float8 as vlrdesc,
                                      (substr(fc_calcula,15,13)::float8+
                                      substr(fc_calcula,28,13)::float8+
                                      substr(fc_calcula,41,13)::float8-
                                      substr(fc_calcula,54,13)::float8) as total
                                 from ( select distinct
                                               k00_numpre,
                                               k00_numpar,
                                               k00_receit,
                                               k03_tipo,
                                               fc_calcula(k00_numpre,k00_numpar,k00_receit,dDataUsu,dDataUsu,v_anousu) as fc_calcula
                                          from ( select distinct
                                                        arrecad.k00_numpre,
                                                        arrecad.k00_numpar,
                                                        arrecad.k00_receit,
                                                        arretipo.k03_tipo
                                                   from arrecad
                                                        inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo
                                                  where arrecad.k00_numpre = v_record_numpres.k00_numpre
                                                    and arrecad.k00_numpar = v_record_numpres.k00_numpar
                                                                                             ) as x
                                                                            ) as y
          loop

          if lRaise is true then
            perform fc_debug('         dentro do for...',lRaise,false,false);
          end if;

          select receit
            from arrecad_parc_rec
            into v_receita
           where numpre  = v_record_numpar.k00_numpre
             and numpar  = v_record_numpar.k00_numpar
             and receit  = v_record_numpar.k00_receit;

          if lRaise is true then
            perform fc_debug('2 - numpre: '||v_record_numpar.k00_numpre||' numpar: '||v_record_numpar.k00_numpar||' receit: '||v_record_numpar.k00_receit||' v_receita: '||v_receita||' - valor: '||v_record_numpar.total,lRaise,false,false);
          end if;

          -- se nao existe registro insere
          if v_receita is null then

            if lRaise is true then
              perform fc_debug('   inserindo no arrecad_parc_rec... numpre: '||v_record_numpar.k00_numpre,lRaise,false,false);
            end if;

            execute 'insert into arrecad_parc_rec values (' || v_record_numpar.k00_numpre || ',' ||
                                                               v_record_numpar.k00_numpar || ',' ||
                                                               v_record_numpar.k00_receit || ',' ||
                                                               v_record_numpar.k03_tipo   || ',' ||
                                                               v_record_numpar.vlrhis     || ',' ||
                                                               v_record_numpar.vlrcor     || ',' ||
                                                               v_record_numpar.vlrjuros   || ',' ||
                                                               v_record_numpar.vlrmulta   || ',' ||
                                                               v_record_numpar.vlrdesc    || ',' ||
                                                               v_record_numpar.total      || ',' ||
                                                               v_matric                   || ',' ||
                                                               v_inscr                    || ');';

          else

            execute 'update arrecad_parc_rec set valor = valor + ' || v_record_numpar.total
            || ',vlrhis = vlrhis + ' || v_record_numpar.vlrhis
            || ',vlrcor = vlrcor + ' || v_record_numpar.vlrcor
            || ',vlrjur = vlrjur + ' || v_record_numpar.vlrjuros
            || ',vlrmul = vlrmul + ' || v_record_numpar.vlrmulta
            || ',vlrdes = vlrdes + ' || v_record_numpar.vlrdesc
            || ' where numpre = '    || v_record_numpar.k00_numpre || ' and '
            || '       numpar = '    || v_record_numpar.k00_numpar || ' and '
            || '       receit = '    || v_record_numpar.k00_receit || ';';
          end if;

          if lRaise is true then
            perform fc_debug(' fim do for...',lRaise,false,false);
          end if;

        end loop;

      end if;

    end loop;

    if lRaise is true then
      perform fc_debug('gravando na tabela parcelas... tipo: '||v_tipo,lRaise,false,false);
      perform fc_debug('v_temdesconto: '||v_temdesconto,lRaise,false,false);
    end if;

    -- busca regra de parcelamento
    select cadtipoparc.k40_codigo
      into v_cadtipoparc
      from tipoparc
           inner join cadtipoparc on cadtipoparc = k40_codigo
     where maxparc       > 1
       and dDataUsu     >= k40_dtini
       and dDataUsu     <= k40_dtfim
       and k40_codigo    = v_desconto
       and k40_aplicacao = 1 -- Aplicar Antes do Lancamento
     order by maxparc
     limit 1;

    if lRaise is true then
      perform fc_debug('v_cadtipoparc: '||v_cadtipoparc,lRaise,false,false);
    end if;

    -- varre as regras de parcelamento para descobrir o percentual de desconto nos juros e multa de acordo com
    -- a quantidade de parcelas selecionadas pelo usuario
    for v_record_desconto in select *
                               from tipoparc
                              where maxparc     > 1
                                and cadtipoparc = v_cadtipoparc
                                and cadtipoparc = v_desconto
                              order by maxparc
    loop

      if v_totalparcelas >= v_ultparc and v_totalparcelas <= v_record_desconto.maxparc then
        v_tipodescontocor = v_record_desconto.tipovlr;
        v_descontocor = v_record_desconto.descvlr;
        v_descontomul = v_record_desconto.descmul;
        v_descontojur = v_record_desconto.descjur;

        exit;

      end if;

    end loop;

    if lRaise is true then
      perform fc_debug('total do desconto na multa : '||v_descontomul,lRaise,false,false);
      perform fc_debug('total do desconto nos juros: '||v_descontojur,lRaise,false,false);
      perform fc_debug('antes do for do arrecad_parc_rec...',lRaise,false,false);
    end if;

    -- soma o valor corrigido + juros + multa antes de efetuar o desconto
    -- valor apenas para conferencia em possivel debug
    select sum(valor),
           sum(vlrcor+vlrjur+vlrmul-vlrdesccor-vlrdescjur-vlrdescmul)
      into v_somar,
           v_totalliquido
      from arrecad_parc_rec;

    if lRaise is true then
      perform fc_debug('v_somar: '||v_somar||' - v_totalliquido: '||v_totalliquido,lRaise,false,false);
    end if;

    -- varre tabela dos registros a parcelar para aplicar desconto nos juros e multa
    for v_record_recpar in select *
                             from arrecad_parc_rec
    loop

      -- testa se o tipo de debito desse registro tem direito a desconto
      select case
               when k00_cadtipoparc > 0
               then true
               else false
             end
        into v_descontar
        from totalportipo
       where k03_tipodebito = v_record_recpar.tipo;

      if lRaise is true then
        perform fc_debug('tipo: '||v_record_recpar.tipo||' - descontar: '||v_descontar,lRaise,false,false);
      end if;

      -- se tem direito a desconto, aplica o desconto e da update nos valores do registro atual da arrecad_parc_rec
      if v_descontar is true then

        v_valdesccor = 0;

        if v_tipodescontocor = 1 then

          if lRaise is true then
            perform fc_debug('vlrcor: '||v_record_recpar.vlrcor||' - vlrhis: '||v_record_recpar.vlrhis||' - v_descontocor: '||v_descontocor,lRaise,false,false);
          end if;
          v_valdesccor = (v_record_recpar.vlrcor - v_record_recpar.vlrhis) * v_descontocor / 100;

        elsif v_tipodescontocor = 2 then
          v_valdesccor = v_record_recpar.vlrcor * v_descontocor / 100;
        end if;

        if lRaise is true then
          perform fc_debug('v_valdesccor: '||v_valdesccor,lRaise,false,false);
        end if;

        v_valdescjur = v_record_recpar.vlrjur * v_descontojur / 100;

        if lRaise is true then
          perform fc_debug('v_valdescjur: '||v_valdescjur||' - v_descontojur: '||v_descontojur,lRaise,false, false);
        end if;

        v_valdescmul = v_record_recpar.vlrmul * v_descontomul / 100;

        if lRaise is true then
          perform fc_debug('v_valdescmul: '||v_valdescmul||' - v_descontomul: '||v_descontomul,lRaise,false,false);
        end if;

        execute 'update arrecad_parc_rec set vlrjur = ' || v_record_recpar.vlrjur
             || ', vlrmul      = ' || v_record_recpar.vlrmul
             || ', valor       = valor - ' || v_valdescjur || ' - ' || v_valdescmul || ' - ' || v_valdesccor
             || ', vlrdesccor  = ' || v_valdesccor
             || ', vlrdescjur  = ' || v_record_recpar.vlrjur * v_descontojur / 100
             || ', vlrdescmul  = ' || v_record_recpar.vlrmul * v_descontomul / 100
             || ' where numpre = '    || v_record_recpar.numpre || ' and '
             || '       numpar = '    || v_record_recpar.numpar || ' and '
             || '       receit = '    || v_record_recpar.receit ||   ';';

      end if;

      if lRaise is true then
        perform fc_debug('   numpre: '||v_record_recpar.numpre||' - numpar: '||v_record_recpar.numpar||' - receita: '||v_record_recpar.receit,lRaise,false,false);
      end if;

    end loop;

    -- passa o conteudo do campo juro para false em todos os registros
    execute 'update arrecad_parc_rec set juro = false';

    if lRaise is true then
      perform fc_debug('v_desconto: '||v_desconto,lRaise,false,false);
    end if;

    -- se a forma na regra de parcelamento for 2 (juros na ultima)
    select case
             when k40_forma = 2
             then true
             else false
           end
      into v_juronaultima
      from cadtipoparc
     where k40_codigo = v_desconto;

    if v_juronaultima is null then
      v_juronaultima = false;
    end if;

    if lRaise is true then
      perform fc_debug('desconto na ultima: '||v_juronaultima,lRaise,false,false);
    end if;

    for v_record_recpar in select *
                             from arrecad_parc_rec
    loop

      -- se for para colocar juros na ultima
      -- insere mais dois registros: um para juros e outro para multa
      -- e update no campo valor deixando apenas o valor corrigido
      if v_juronaultima is true then

        select k02_recjur,
               k02_recmul
          from tabrec
          into v_recjurosultima,
               v_recmultaultima
         where k02_codigo = v_record_recpar.receit;

        if lRaise is true then
          perform fc_debug('jur: '||v_recjurosultima||' - mul: '||v_recmultaultima,lRaise,false,false);
          perform fc_debug('numpre: '||v_record_recpar.numpre||' - numpar: '||v_record_recpar.numpar||' - jurosnaultima: '||v_recjurosultima,lRaise,false,false);
          perform fc_debug('tipo: '||v_record_recpar.tipo||' - juros: '||v_record_recpar.vlrjur||' - matric: '||v_record_recpar.matric||' - inscr: '||v_record_recpar.inscr||' - descjur: '||v_record_recpar.vlrdescjur||' - descmul: '||v_record_recpar.vlrdescmul,lRaise,false,false);
        end if;

        execute 'insert into arrecad_parc_rec values (' || v_record_recpar.numpre          || ',' ||
                                                           v_record_recpar.numpar          || ',' ||
                                                           v_recjurosultima                || ',' ||
                                                           v_record_recpar.tipo            || ',' ||
                                                           v_record_recpar.vlrjur          || ',' ||
                                                           v_record_recpar.vlrjur          || ',' ||
                                                           0                               || ',' ||
                                                           0                               || ',' ||
                                                           0                               || ',' ||
                                                           v_record_recpar.vlrjur          || ',' ||
                                                           v_record_recpar.matric          || ',' ||
                                                           v_record_recpar.inscr           || ',' ||
                                                           0                               || ',' ||
                                                           v_record_recpar.vlrdescjur      || ',' ||
                                                           v_record_recpar.vlrdescmul      || ',' ||
                                                           'true'                          || ');';

        if lRaise is true then
          perform fc_debug('1',lRaise,false,false);
        end if;

        -- inserindo multa
        execute 'insert into arrecad_parc_rec values (' || v_record_recpar.numpre          || ',' ||
                                                           v_record_recpar.numpar          || ',' ||
                                                           v_recmultaultima                || ',' ||
                                                           v_record_recpar.tipo            || ',' ||
                                                           v_record_recpar.vlrmul          || ',' ||
                                                           v_record_recpar.vlrmul          || ',' ||
                                                           0                               || ',' ||
                                                           0                               || ',' ||
                                                           0                               || ',' ||
                                                           v_record_recpar.vlrmul          || ',' ||
                                                           v_record_recpar.matric          || ',' ||
                                                           v_record_recpar.inscr           || ',' ||
                                                           0                               || ',' ||
                                                           v_record_recpar.vlrdescjur      || ',' ||
                                                           v_record_recpar.vlrdescmul      || ',' ||
                                                           'true'                          || ');';

        if lRaise is true then
          perform fc_debug('2',lRaise,false,false);
        end if;

        execute 'update arrecad_parc_rec set valor  = ' || v_record_recpar.vlrcor ||
                                     ' where numpre = ' || v_record_recpar.numpre ||
                                     '   and numpar = ' || v_record_recpar.numpar ||
                                     '   and receit = ' || v_record_recpar.receit || ';';

        if lRaise is true then
          perform fc_debug('3',lRaise,false,false);
        end if;

      end if;

    end loop;

    if lRaise is true then
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
    end if;

    -- apenas mostra os registros atuais para possivel conferencia
    for v_record_recpar in select *
                             from arrecad_parc_rec loop

      if lRaise is true then
        perform fc_debug('numpre: '||v_record_recpar.numpre||' - par: '||v_record_recpar.numpar||' - rec: '||v_record_recpar.receit||' - cor: '||v_record_recpar.vlrcor||' - jur: '||v_record_recpar.vlrjur||' - tot: '||v_record_recpar.valor||' - juro: '||v_record_recpar.juro,lRaise, false,false);
      end if;

    end loop;

    if lRaise is true then
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
    end if;

    if lRaise is true then
      perform fc_debug('depois do for do arrecad_parc_rec...',lRaise,false,false);
    end if;

    -- calcula valor total com juro
    select sum(valor)
      from arrecad_parc_rec
      into v_totalcomjuro;

    -- se for juros na ultima, o campo valor ja esta sem juros e multa
    -- entao a variavel v_total recebe sem juros e a regra for de colocar os juros na ultima parcela
    -- note que o campo juro da tabela recebe false apenas nos registros que nao sao dos juros para incluir na ultima
    if v_juronaultima is false then
      -- select sum(round(valor, 2))
        -- from (
               select sum(valor) as valor
                from arrecad_parc_rec
            -- group by receit
              -- ) as dados
        into v_total;
    else
      -- select sum(round(valor, 2))
        -- from (
               select sum(valor) as valor
                from arrecad_parc_rec
               where juro is false
            -- group by receit
             -- ) as dados
        into v_total;
    end if;

    -- diferente entre variavel com e sem juros
    -- utilizada na regra de juros na ultima
    v_diferencanaultima = v_totalcomjuro - v_total;

    if lRaise is true then
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug('total (primeira versao do script): '||v_total||' - v_totalparcelas: '||v_totalparcelas,lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug('v_tipo: '||v_tipo,lRaise,false,false);
    end if;

    v_somar = 0;

    if lRaise is true then
      perform fc_debug('antes do tipo 5...',lRaise,false,false);
    end if;

    -- cria variavel para select agrupando os valores por
    -- tipo de origem, receita nova e receita original
    -- note que o sistema tem 3 niveis de origem
    -- 1 = de divida ativa
    -- 2 = parcelamento de divida, parcelamento de inicial, parcelamento de contribuicao, inicial do foro e contribuicao
    -- 3 = diversos

    v_comando =              '  select tipo_origem,                                                                                                                       \n';
    v_comando = v_comando || '         receita,                                                                                                                           \n';
    v_comando = v_comando || '         receitaori,                                                                                                                        \n';
    v_comando = v_comando || '         min(k00_hist) as k00_hist,                                                                                                         \n';
    v_comando = v_comando || '         round(sum(valor),2) as valor,                                                                                                      \n';
    v_comando = v_comando || '         round(sum(total_his),2) as total_his,                                                                                              \n';
    v_comando = v_comando || '         round(sum(total_cor),2) as total_cor,                                                                                              \n';
    v_comando = v_comando || '         round(sum(total_jur),2) as total_jur,                                                                                              \n';
    v_comando = v_comando || '         round(sum(total_mul),2) as total_mul,                                                                                              \n';
    v_comando = v_comando || '         round(sum(total_desccor),2) as total_desccor,                                                                                      \n';
    v_comando = v_comando || '         round(sum(total_descjur),2) as total_descjur,                                                                                      \n';
    v_comando = v_comando || '         round(sum(total_descmul),2) as total_descmul                                                                                       \n';
    v_comando = v_comando || '    from ( select 1 as tipo_origem,                                                                                                         \n';
    v_comando = v_comando || '                  receit as receita,                                                                                                        \n';
    v_comando = v_comando || '                  receitaori,                                                                                                               \n';
    v_comando = v_comando || '                  min(k00_hist) as k00_hist,                                                                                                \n';
    v_comando = v_comando || '                  sum(valor) as valor,                                                                                                      \n';
    v_comando = v_comando || '                  sum(total_his) as total_his,                                                                                              \n';
    v_comando = v_comando || '                  sum(total_cor) as total_cor,                                                                                              \n';
    v_comando = v_comando || '                  sum(total_jur) as total_jur,                                                                                              \n';
    v_comando = v_comando || '                  sum(total_mul) as total_mul,                                                                                              \n';
    v_comando = v_comando || '                  sum(total_desccor) as total_desccor,                                                                                      \n';
    v_comando = v_comando || '                  sum(total_descjur) as total_descjur,                                                                                      \n';
    v_comando = v_comando || '                  sum(total_descmul) as total_descmul                                                                                       \n';
    v_comando = v_comando || '               from ( select a.numpre,                                                                                                      \n';
    v_comando = v_comando || '                             a.numpar,                                                                                                      \n';
    v_comando = v_comando || '                             a.receita as receit,                                                                                           \n';
    v_comando = v_comando || '                             a.receitaori as receitaori,                                                                                    \n';
    v_comando = v_comando || '                             min(k00_hist) as k00_hist,                                                                                     \n';
    v_comando = v_comando || '                             sum(a.valor) as valor,                                                                                         \n';
    v_comando = v_comando || '                             sum(total_his) as total_his,                                                                                   \n';
    v_comando = v_comando || '                             sum(total_cor) as total_cor,                                                                                   \n';
    v_comando = v_comando || '                             sum(total_jur) as total_jur,                                                                                   \n';
    v_comando = v_comando || '                             sum(total_mul) as total_mul,                                                                                   \n';
    v_comando = v_comando || '                             sum(total_desccor) as total_desccor,                                                                           \n';
    v_comando = v_comando || '                             sum(total_descjur) as total_descjur,                                                                           \n';
    v_comando = v_comando || '                             sum(total_descmul) as total_descmul                                                                            \n';
    v_comando = v_comando || '                      from ( select arrecad_parc_rec.numpre,                                                                                \n';
    v_comando = v_comando || '                                    arrecad_parc_rec.numpar,                                                                                \n';
    v_comando = v_comando || '                                    arrecad_parc_rec.receit as receitaori,                                                                  \n';
    v_comando = v_comando || '                                    recparproc.receita as receita,                                                                          \n';
    v_comando = v_comando || '                                    min(proced.k00_hist) as k00_hist,                                                                       \n';
    v_comando = v_comando || '                                    round(sum(arrecad_parc_rec.valor),2) as valor,                                                          \n';
    v_comando = v_comando || '                                    round(sum(vlrhis),2) as total_his,                                                                      \n';
    v_comando = v_comando || '                                    round(sum(vlrcor),2) as total_cor,                                                                      \n';
    v_comando = v_comando || '                                    round(sum(vlrjur),2) as total_jur,                                                                      \n';
    v_comando = v_comando || '                                    round(sum(vlrmul),2) as total_mul,                                                                      \n';
    v_comando = v_comando || '                                    round(sum(vlrdesccor),2) as total_desccor,                                                              \n';
    v_comando = v_comando || '                                    round(sum(vlrdescjur),2) as total_descjur,                                                              \n';
    v_comando = v_comando || '                                    round(sum(vlrdescmul),2) as total_descmul                                                               \n';
    v_comando = v_comando || '                               from arrecad_parc_rec                                                                                        \n';
    v_comando = v_comando || '                                    inner join arrecad     on arrecad.k00_numpre    = arrecad_parc_rec.numpre                               \n';
    v_comando = v_comando || '                                                          and arrecad.k00_numpar    = arrecad_parc_rec.numpar                               \n';
    v_comando = v_comando || '                                                          and arrecad.k00_receit    = arrecad_parc_rec.receit                               \n';
    v_comando = v_comando || '                                                          and arrecad.k00_valor     > 0                                                     \n';
    v_comando = v_comando || '                                    inner join arretipo    on arretipo.k00_tipo     = arrecad.k00_tipo                                      \n';
    v_comando = v_comando || '                                    left  join divida      on divida.v01_numpre     = arrecad.k00_numpre                                    \n';
    v_comando = v_comando || '                                                          and divida.v01_numpar     = arrecad.k00_numpar                                    \n';
    v_comando = v_comando || '                                    left  join recparproc  on recparproc.v03_codigo = divida.v01_proced                                     \n';
    v_comando = v_comando || '                                    inner join proced      on proced.v03_codigo     = divida.v01_proced                                     \n';
    v_comando = v_comando || '                                    where k03_tipo = 5                                                                                      \n';
    if v_juronaultima is true then
     v_comando = v_comando || '                                     and juro is false                                                                                     \n';
    end if;
    v_comando = v_comando || '                                    group by arrecad_parc_rec.numpre,                                                                       \n';
    v_comando = v_comando || '                                             arrecad_parc_rec.numpar,                                                                       \n';
    v_comando = v_comando || '                                             arrecad_parc_rec.receit,                                                                       \n';
    v_comando = v_comando || '                                             recparproc.receita                                                                             \n';
    v_comando = v_comando || '                           ) as a                                                                                                           \n';
    v_comando = v_comando || '                          group by a.numpre,                                                                                                \n';
    v_comando = v_comando || '                                   a.numpar,                                                                                                \n';
    v_comando = v_comando || '                                   a.receita,                                                                                               \n';
    v_comando = v_comando || '                                   a.receitaori                                                                                             \n';
    v_comando = v_comando || '                    ) as x                                                                                                                  \n';
    v_comando = v_comando || '               group by receit,                                                                                                             \n';
    v_comando = v_comando || '                        receitaori                                                                                                          \n';

    v_comando = v_comando || '      union                                                                                                                                 \n';

    v_comando = v_comando || '             select 2 as tipo_origem,                                                                                                       \n';
    v_comando = v_comando || '                    case when recparproc.receita is null then                                                                               \n';
    v_comando = v_comando || '                         arrecad_parc_rec.receit                                                                                            \n';
    v_comando = v_comando || '                       else                                                                                                                 \n';
    v_comando = v_comando || '                         recparproc.receita                                                                                                 \n';
    v_comando = v_comando || '                    end as receit,                                                                                                          \n';
    v_comando = v_comando || '                    arrecad_parc_rec.receit as receitaori,                                                                                  \n';
    v_comando = v_comando || '                    min(arrecad.k00_hist) as k00_hist,                                                                                      \n';
    v_comando = v_comando || '                    round(sum(arrecad_parc_rec.valor),2) as valor,                                                                          \n';
    v_comando = v_comando || '                    round(sum(vlrhis),2) as total_his,                                                                                      \n';
    v_comando = v_comando || '                    round(sum(vlrcor),2) as total_cor,                                                                                      \n';
    v_comando = v_comando || '                    round(sum(vlrjur),2) as total_jur,                                                                                      \n';
    v_comando = v_comando || '                    round(sum(vlrmul),2) as total_mul,                                                                                      \n';
    v_comando = v_comando || '                    round(sum(vlrdesccor),2) as total_desccor,                                                                              \n';
    v_comando = v_comando || '                    round(sum(vlrdescjur),2) as total_descjur,                                                                              \n';
    v_comando = v_comando || '                    round(sum(vlrdescmul),2) as total_descmul                                                                               \n';
    v_comando = v_comando || '               from arrecad_parc_rec                                                                                                        \n';
    v_comando = v_comando || '                    inner join arrecad    on arrecad.k00_numpre = arrecad_parc_rec.numpre                                                   \n';
    v_comando = v_comando || '                                         and arrecad.k00_numpar = arrecad_parc_rec.numpar                                                   \n';
    v_comando = v_comando || '                                         and arrecad.k00_receit = arrecad_parc_rec.receit                                                   \n';
    v_comando = v_comando || '                                         and arrecad.k00_valor > 0                                                                          \n';
    v_comando = v_comando || '                    inner join arretipo   on  arretipo.k00_tipo = arrecad.k00_tipo                                                          \n';
    v_comando = v_comando || '                    left  join divida     on divida.v01_numpre     = arrecad.k00_numpre                                                     \n';
    v_comando = v_comando || '                                         and divida.v01_numpar     = arrecad.k00_numpar                                                     \n';
    v_comando = v_comando || '                    left  join recparproc on recparproc.v03_codigo = divida.v01_proced                                                      \n';
    v_comando = v_comando || '                    left join proced     on proced.v03_codigo     = divida.v01_proced                                                      \n';
    v_comando = v_comando || '              where ( k03_tipo in (6, 13, 18, 17, 4)                                                                                        \n';
    v_comando = v_comando || '                      or (     k03_tipo in (7,16)                                                                                           \n';
    v_comando = v_comando || '                           and exists (select 1                                                                                             \n';
    v_comando = v_comando || '                                         from termo                                                                                         \n';
    v_comando = v_comando || '                                              inner join termoreparc on termoreparc.v08_parcel = termo.v07_parcel                           \n';
    v_comando = v_comando || '                                        where v07_numpre = arrecad_parc_rec.numpre) )                                                       \n';
    v_comando = v_comando || '                    )                                                                                                                       \n';
    v_comando = v_comando || '                and not exists (select 1                                                                                                    \n';
    v_comando = v_comando || '                                  from termo                                                                                                \n';
    v_comando = v_comando || '                                       inner join termodiver on termo.v07_parcel = termodiver.dv10_parcel                                   \n';
    v_comando = v_comando || '                                 where termo.v07_numpre = arrecad_parc_rec.numpre )                                                         \n';
    if v_juronaultima is true then
        v_comando = v_comando || '            and juro is false                                                                                                           \n';
    end if;
    v_comando = v_comando || '              group by recparproc.receita,                                                                                             \n';
    v_comando = v_comando || '                     arrecad_parc_rec.receit                                                                                                \n';

    v_comando = v_comando || '    union \n';

    v_comando = v_comando || '             select 3 as tipo_origem,                                                                                                       \n';
    v_comando = v_comando || '                    recparprocdiver.receita,                                                                                                \n';
    v_comando = v_comando || '                    recparprocdiver.receita as receitaori,                                                                                  \n';
    v_comando = v_comando || '                    procdiver.dv09_hist,                                                                                                    \n';
    v_comando = v_comando || '                    round(sum(valor),2) as valor,                                                                                           \n';
    v_comando = v_comando || '                    round(sum(vlrhis),2) as total_his,                                                                                      \n';
    v_comando = v_comando || '                    round(sum(vlrcor),2) as total_cor,                                                                                      \n';
    v_comando = v_comando || '                    round(sum(vlrjur),2) as total_jur,                                                                                      \n';
    v_comando = v_comando || '                    round(sum(vlrmul),2) as total_mul,                                                                                      \n';
    v_comando = v_comando || '                    round(sum(vlrdesccor),2) as total_desccor,                                                                              \n';
    v_comando = v_comando || '                    round(sum(vlrdescjur),2) as total_descjur,                                                                              \n';
    v_comando = v_comando || '                    round(sum(vlrdescmul),2) as total_descmul                                                                               \n';
    v_comando = v_comando || '               from diversos                                                                                                                \n';
    v_comando = v_comando || '                    left join (select termodiver.*                                                                                          \n';
    v_comando = v_comando || '                                 from termodiver                                                                                            \n';
    v_comando = v_comando || '                                inner join termo on dv10_parcel = v07_parcel                                                                \n';
    v_comando = v_comando || '                                                and v07_situacao = 1) as termodiver on dv05_coddiver             = dv10_coddiver            \n';
    v_comando = v_comando || '                                 left join recparprocdiver                          on recparprocdiver.procdiver = diversos.dv05_procdiver  \n';
    v_comando = v_comando || '                                inner join procdiver                                on procdiver.dv09_procdiver  = diversos.dv05_procdiver  \n';
    v_comando = v_comando || '                                inner join arrecad_parc_rec                         on diversos.dv05_numpre      = arrecad_parc_rec.numpre  \n';
    v_comando = v_comando || '              where dv10_coddiver is null                                                                                                   \n';
    v_comando = v_comando || '              group by recparprocdiver.receita,                                                                                             \n';
    v_comando = v_comando || '                       procdiver.dv09_hist                                                                                                  \n';

    v_comando = v_comando || '    union                                                                                                                                   \n';

    v_comando = v_comando || '           select tipo_origem,                                                                                                              \n';
    v_comando = v_comando || '                  receita,                                                                                                                  \n';
    v_comando = v_comando || '                  receitaori,                                                                                                               \n';
    v_comando = v_comando || '                  dv09_hist,                                                                                                                \n';
    v_comando = v_comando || '                  round(sum(valor),2) as valor,                                                                                             \n';
    v_comando = v_comando || '                  round(sum(vlrhis),2) as total_his,                                                                                        \n';
    v_comando = v_comando || '                  round(sum(vlrcor),2) as total_cor,                                                                                        \n';
    v_comando = v_comando || '                  round(sum(vlrjur),2) as total_jur,                                                                                        \n';
    v_comando = v_comando || '                  round(sum(vlrmul),2) as total_mul,                                                                                        \n';
    v_comando = v_comando || '                  round(sum(vlrdesccor),2) as total_desccor,                                                                                \n';
    v_comando = v_comando || '                  round(sum(vlrdescjur),2) as total_descjur,                                                                                \n';
    v_comando = v_comando || '                  round(sum(vlrdescmul),2) as total_descmul                                                                                 \n';
    v_comando = v_comando || '             from ( select 4 as tipo_origem,                                                                                                \n';
    v_comando = v_comando || '                           (select min(recparprocdiver.receita)                                                                             \n';
    v_comando = v_comando || '                              from termodiver                                                                                               \n';
    v_comando = v_comando || '                                   inner join diversos         on termodiver.dv10_coddiver  = dv05_coddiver                                 \n';
    v_comando = v_comando || '                                   inner join recparprocdiver  on recparprocdiver.procdiver = diversos.dv05_procdiver                       \n';
    v_comando = v_comando || '                                   inner join procdiver        on procdiver.dv09_procdiver  = diversos.dv05_procdiver                       \n';
    v_comando = v_comando || '                             where termodiver.dv10_parcel = v07_parcel ) as receita,                                                        \n';
    v_comando = v_comando || '                           (select min(recparprocdiver.receita)                                                                             \n';
    v_comando = v_comando || '                              from termodiver                                                                                               \n';
    v_comando = v_comando || '                                   inner join diversos         on termodiver.dv10_coddiver  = dv05_coddiver                                 \n';
    v_comando = v_comando || '                                   inner join recparprocdiver  on recparprocdiver.procdiver = diversos.dv05_procdiver                       \n';
    v_comando = v_comando || '                                   inner join procdiver        on procdiver.dv09_procdiver  = diversos.dv05_procdiver                       \n';
    v_comando = v_comando || '                             where termodiver.dv10_parcel = v07_parcel ) as receitaori,                                                     \n';
    v_comando = v_comando || '                           (select min(procdiver.dv09_hist)                                                                                 \n';
    v_comando = v_comando || '                              from termodiver                                                                                               \n';
    v_comando = v_comando || '                                   inner join diversos         on termodiver.dv10_coddiver  = dv05_coddiver                                 \n';
    v_comando = v_comando || '                                   inner join recparprocdiver  on recparprocdiver.procdiver = diversos.dv05_procdiver                       \n';
    v_comando = v_comando || '                                   inner join procdiver        on procdiver.dv09_procdiver  = diversos.dv05_procdiver                       \n';
    v_comando = v_comando || '                             where termodiver.dv10_parcel = v07_parcel ) as dv09_hist,                                                      \n';
    v_comando = v_comando || '                           valor,                                                                                                           \n';
    v_comando = v_comando || '                           vlrhis,                                                                                                          \n';
    v_comando = v_comando || '                           vlrcor,                                                                                                          \n';
    v_comando = v_comando || '                           vlrjur,                                                                                                          \n';
    v_comando = v_comando || '                           vlrmul,                                                                                                          \n';
    v_comando = v_comando || '                           vlrdesccor,                                                                                                      \n';
    v_comando = v_comando || '                           vlrdescjur,                                                                                                      \n';
    v_comando = v_comando || '                           vlrdescmul                                                                                                       \n';
    v_comando = v_comando || '                      from arrecad_parc_rec                                                                                                 \n';
    v_comando = v_comando || '                           inner join termo on v07_numpre = arrecad_parc_rec.numpre                                                         \n';
    v_comando = v_comando || '                           inner join ( select distinct                                                                                     \n';
    v_comando = v_comando || '                                               dv10_parcel                                                                                  \n';
    v_comando = v_comando || '                                          from termodiver ) as parcdiver  on parcdiver.dv10_parcel = termo.v07_parcel                       \n';
    v_comando = v_comando || '                  ) as diver                                                                                                                \n';
    v_comando = v_comando || '            group by tipo_origem,                                                                                                           \n';
    v_comando = v_comando || '                     receita,receitaori,                                                                                                    \n';
    v_comando = v_comando || '                     dv09_hist                                                                                                              \n';
    v_comando = v_comando || '         ) as xxx                                                                                                                           \n';
    v_comando = v_comando || 'group by tipo_origem,                                                                                                                       \n';
    v_comando = v_comando || '         receita,                                                                                                                           \n';
    v_comando = v_comando || '         receitaori                                                                                                                         \n';

    if lRaise then
      perform fc_debug('sql : '||v_comando,lRaise,false,false);
    end if;

    if lRaise then
      perform fc_debug('v_total: '||v_total,lRaise,false,false);
    end if;

    v_comando_cria = 'create temp table w_testando as ' || v_comando;
    execute v_comando_cria;

    -- tipo 3 = parcelamento de diversos
    -- tipo 4 = reparcelamento de diversos

    -- se regra for de juros na ultima, diminui o total de parcelas em 1
    if v_juronaultima is true then
       v_totalparcelas = v_totalparcelas - 1;

       if lRaise is true then
         perform fc_debug('mudando - v_total: '||v_total,lRaise,false,false);
       end if;
    end if;

    -- processa receita por receita para gerar os registros na tabela parcelas
    -- que sera utilizada posteriormente para gerar os registros na tabela arrecad
    for v_record_recpar in execute v_comando
    loop

      if v_record_recpar.tipo_origem is null then
        return '[6] - Não encontrados registros na tabela Divida para um dos debitos que esta sendo parcelado.';
      end if;

      if v_record_recpar.receita is null then
        return '[7] - Receita de parcelamento nao configurada para a procedencia';
      end if;

      -- se origem for divida ativa, soma na variavel v_totaldivida
      if v_record_recpar.tipo_origem = 1 then
          v_totaldivida = v_totaldivida + v_record_recpar.valor;
      end if;

      if lRaise is true then
          perform fc_debug('tipo_origem: '||v_record_recpar.tipo_origem||' - receita: '||v_record_recpar.receita||' - receitaoriginal: '||v_record_recpar.receitaori||' - hist: '||v_record_recpar.k00_hist||' - valor: '||v_record_recpar.valor||' - total_cor: '||v_record_recpar.total_cor,lRaise,false,false);
      end if;

      -- calcula entrada proporcional ao valor desta receita
      -- regra de tres normal em relacao percentual da entrada do registro atual em relacao ao total do parcelamento
      -- se for o caso de ter apenas uma receita em processamento, essa variavel vai ser igual ao valor da entrada
      v_ent_prop = v_record_recpar.valor * (v_entrada / v_total);
      v_total_liquido = v_record_recpar.total_cor + v_record_recpar.total_jur + v_record_recpar.total_mul - v_record_recpar.total_desccor - v_record_recpar.total_descjur - v_record_recpar.total_descmul;

      if lRaise is true then
        perform fc_debug('xxxxxxxxxxxxx: receita: '||v_record_recpar.receita||' - valor: '||v_record_recpar.valor||' - entrada proporcional: '||v_ent_prop||' - valor: '||v_record_recpar.valor||' - total: '||v_total_liquido,lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug('========== receita: '||v_record_recpar.receita,lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
      end if;

      -- processa parcela por parcela


      for v_parcela in 1..v_totalparcelas
      loop

        -- variavel do valor da parcela recebe o valor da receita deste registro / valor total do parcelamento
        -- que na pratica seria a proporcionalidade deste registro em relacao ao total do parcelamento
        v_valparc = v_record_recpar.valor / v_total;

        if lRaise is true then
            perform fc_debug('   v_valparc: '||v_valparc||' - v_total: '||v_total||' - valor: '||v_record_recpar.valor||' - receit: '||v_record_recpar.receita||' - entrada: '||v_entrada,lRaise,false,false);
        end if;

        if v_parcela = 1 then
            -- se parcela igual a 1, entao valor parcela e igual ao valor da entrada * valor da proporcionalidade
            -- deste registro em relacao ao total do parcelamento
            v_valparc = v_entrada * v_valparc;
        else
            -- se nao for a parcela 1 entao
            -- valor da parcela recebe o valor da parcela definido pelo usuario na CGF * valor da proporcionalidade
            -- deste registro em relacao ao total do parcelamento
            v_valparc = v_valorparcelanew * v_valparc;
        end if;

        --v_valparc = round(v_valparc,2);

        if lRaise is true then
            perform fc_debug('   000 = parcela: '||v_parcela||' - receita: '||v_record_recpar.receita||' - valor: '||v_valparc||' - v_valorparcelanew: '||v_valorparcelanew||' - receitaori: '||v_record_recpar.receitaori,lRaise,false,false);
        end if;

        v_calcula_valprop = v_record_recpar.valor / v_total;
        v_teste           = v_record_recpar.valor / v_total;

        if v_teste <= 0 then

          if lRaise is true then
            perform fc_debug('valor: '||v_record_recpar.valor||' - v_total: '||v_total||' - v_teste: '||v_teste||' - parcela: '||v_parcela||' - receita: '||v_record_recpar.receita||' - v_calcula_valprop: '||v_calcula_valprop,lRaise,false,false);
          end if;
        end if;

        if lRaise is true then
          perform fc_debug('v_valparc: '||v_valparc||' - valor: '||v_record_recpar.valor||' - total_his: '||v_record_recpar.total_his||' - total: '||v_total,lRaise,false,false);
        end if;

        --Adicionado arredondamento e função para retornar o mínimo monetário
        v_calcula_valor   = (v_record_recpar.valor);
        v_calcula_his     = fc_arredondaminimomonetario(v_valparc / v_record_recpar.valor * v_record_recpar.total_his);
        v_calcula_cor     = fc_arredondaminimomonetario(v_valparc / v_record_recpar.valor * v_record_recpar.total_cor);
        v_calcula_jur     = fc_arredondaminimomonetario(v_valparc / v_record_recpar.valor * v_record_recpar.total_jur);
        v_calcula_mul     = fc_arredondaminimomonetario(v_valparc / v_record_recpar.valor * v_record_recpar.total_mul);
        v_calcula_desccor = v_valparc / v_calcula_valor * v_record_recpar.total_desccor;
        v_calcula_descjur = fc_arredondaminimomonetario(v_valparc / v_calcula_valor * v_record_recpar.total_descjur);
        v_calcula_descmul = fc_arredondaminimomonetario(v_valparc / v_calcula_valor * v_record_recpar.total_descmul);

        --Arredonda o valor da parcela para inserir na tabela parcelas já arrendado, local onde servirá de base para inserir os valores na arrecad
        v_valparc = round((fc_arredondaminimomonetario(v_valparc)),2);

        if lRaise then
          perform fc_debug('v_calcula_his: '||v_calcula_his||' - v_valparc: '||v_valparc||' - v_calcula_valor: '||v_calcula_valor||' - total_desccor: '||v_record_recpar.total_desccor,lRaise,false,false);
        end if;

        -- Calcula a soma do valor por receita antes do parcelamento.
        select
          sum(valor) as valor_maximo,
          sum(vlrhis) as  valor_historico,
          sum(vlrcor) as  valor_correcao,
          sum(vlrjur) as  valor_juro,
          sum(vlrmul) as  valor_multa,
          sum(vlrdescjur) as  valor_descontoJuro,
          sum(vlrdescmul) as  valor_descontoMulta
          into nValorMaximoReceita,
               nValorMaximoHistorico,
               nValorMaximoCorrecao,
               nValorMaximoJuro,
               nValorMaximoMulta,
               nValorMaximoDescontoJuro,
               nValorMaximoDescontoMulta
          from arrecad_parc_rec
         where receit = v_record_recpar.receitaori
         group by receit;

         select receit,
                receitaori,
                sum(valor),
                sum(valhis),
                sum(valcor),
                sum(valjur),
                sum(valmul),
                sum(descjur),
                sum(descmul)
           into v_record_parcelas_receit,
                v_record_parcelas_receitaori,
                v_record_parcelas_valor,
                v_record_parcelas_valhis,
                v_record_parcelas_valcor,
                v_record_parcelas_valjur,
                v_record_parcelas_valmul,
                v_record_parcelas_descjur,
                v_record_parcelas_descmul
           from parcelas
          where receitaori = v_record_recpar.receitaori
          group by receit, receitaori;

        -- Se alguma das colunas atingiu o máximo então zera este valor para que não lance valores errados
        if v_record_parcelas_valor >= nValorMaximoReceita then
          v_valparc = 0;
        end if;

        if v_record_parcelas_valhis >= nValorMaximoHistorico then
          v_calcula_his = 0;
        end if;

        if v_record_parcelas_valcor >= nValorMaximoCorrecao then
          v_calcula_cor = 0;
        end if;

        if v_record_parcelas_valjur >= nValorMaximoJuro then
          v_calcula_jur = 0;
        end if;

        if v_record_parcelas_valmul >= nValorMaximoMulta then
          v_calcula_mul = 0;
        end if;

        if v_record_parcelas_descjur >= nValorMaximoDescontoJuro then
          v_calcula_descjur = 0;
        end if;

        if v_record_parcelas_descmul >= nValorMaximoDescontoMulta then
          v_calcula_descmul = 0;
        end if;

        --Verifica se o valor da tabela parcelas já atingiu o máximo para a receita
        if     v_record_parcelas_valor >= nValorMaximoReceita
           AND v_record_parcelas_valhis >= nValorMaximoHistorico
           AND v_record_parcelas_valcor >= nValorMaximoCorrecao
           AND v_record_parcelas_valjur >= nValorMaximoJuro
           AND v_record_parcelas_valmul >= nValorMaximoMulta
           AND v_record_parcelas_descjur >= nValorMaximoDescontoJuro
           AND v_record_parcelas_descmul >= nValorMaximoDescontoMulta
        then
          continue;
        end if;

        if v_valparc > 0 then

          if round(v_valparc,2) > 0 then

            lIncluiEmParcelas = true;

          else

            perform * from parcelas where receit = v_record_recpar.receita;

            if found then
              lIncluiEmParcelas = false;
            else
              lIncluiEmParcelas = true;
            end if;

          end if;

          if lIncluiEmParcelas is true then

            -- insere valores calculados na tabela parcelas
            execute 'insert into parcelas values (' || v_parcela                                 || ',' ||
                                                       v_record_recpar.receita                   || ',' ||
                                                       v_record_recpar.receitaori                || ',' ||
                                                       v_record_recpar.k00_hist                  || ',' ||
                                                       v_valparc                                 || ',' ||
                                                       v_calcula_valprop                         || ',' ||
                                                       v_calcula_his                             || ',' ||
                                                       v_calcula_cor                             || ',' ||
                                                       v_calcula_jur                             || ',' ||
                                                       v_calcula_mul                             || ',' ||
                                                       v_calcula_desccor                         || ',' ||
                                                       v_calcula_descjur                         || ',' ||
                                                       v_calcula_descmul                         ||
                                                       ');';

          else

            execute 'update parcelas set '   ||
                    '  valor   = valor   + ' || v_valparc         ||
                    ', valprop = valprop + ' || v_calcula_valprop ||
                    ', valhis  = valhis  + ' || v_calcula_his     ||
                    ', valcor  = valcor  + ' || v_calcula_cor     ||
                    ', valjur  = valjur  + ' || v_calcula_jur     ||
                    ', valmul  = valmul  + ' || v_calcula_mul     ||
                    ', descor  = descor  + ' || v_calcula_desccor ||
                    ', descjur = descjur + ' || v_calcula_descjur ||
                    ', descmul = descmul + ' || v_calcula_descmul ||
                    ' where receit = ' || v_record_recpar.receita;

          end if;

        end if;

      end loop;

    end loop;

    -- se regra for de juros na ultima
    if v_juronaultima is true then

        if lRaise is true then
          perform fc_debug('processando ultima... diferenca: '||(v_totalcomjuro - v_total),lRaise,false,false);
        end if;

        -- soma 1 na variavel do total de parcelas
        v_totalparcelas = v_totalparcelas + 1;

        -- gera comando para agrupar receita por receita somando o valor
        v_comando =              ' select arrecad_parc_rec. ';
        v_comando = v_comando || '        receit as receita, ';
        v_comando = v_comando || '            sum(arrecad_parc_rec.valor) as valor ';
        v_comando = v_comando || '   from arrecad_parc_rec ';
        v_comando = v_comando || '  where juro is true ';
        v_comando = v_comando || '  group by arrecad_parc_rec.receit ';

        select v04_histjuros
          from pardiv
          into v_histjuro;
        if v_histjuro is null then
          v_histjuro = 1;
        end if;

        for v_record_recpar in execute v_comando
        loop

          v_valorinserir = round(v_record_recpar.valor,2);

          if lRaise is true then
             perform fc_debug('111 = inserindo diferenca: '||v_valorinserir||' - receita: '||v_record_recpar.receita||' - valor: '||v_record_recpar.valor.lRaise,false,false);
          end if;

          execute 'insert into parcelas values (' || v_totalparcelas          || ',' ||
                                                     v_record_recpar.receita  || ',' ||
                                                     v_record_recpar.receita  || ',' ||
                                                     v_histjuro               || ',' ||
                                                     v_valorinserir           || ',' ||
                                                     (v_valorinserir) / v_totalcomjuro ||
                                                     ');';

        end loop;

        v_total = v_totalcomjuro;

    end if;

    if lRaise is true then
        perform fc_debug('saindo do tipo 5...',lRaise,false,false);
    end if;

    if lRaise is true then
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug('-',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug('terminou de gravar na tabela parcelas...',lRaise,false,false);
    end if;

    update parcelas set valor   = w_testando.valor,
                        valhis  = w_testando.total_his,
                        valcor  = w_testando.total_cor,
                        valjur  = w_testando.total_jur,
                        valmul  = w_testando.total_mul,
                        descor  = w_testando.total_desccor,
                        descjur = w_testando.total_descjur,
                        descmul = w_testando.total_descmul
     from w_testando
    where receit = w_testando.receita
      and parcelas.valor = 0;

    -- calcula a maior parcela e a soma do valor dos registros da tabela parcelas
    select max(parcela),
           sum(valor)
      from parcelas
      into v_totpar, v_somar;

    if lRaise is true then
      perform fc_debug('total de parcelas: '||v_totpar||' - v_somar: '||v_somar,lRaise,true,true);
    end if;

        -- testa se ocorreu alguma inconsistencia
    if v_totpar = 0 or v_totpar is null then
      return '[8] - Erro ao gerar parcelas... provavelmente falta recparproc...';
    end if;

    select sum(valor)
      into v_totalliquido
      from parcelas;

    if lRaise is true then
      perform fc_debug('v_totalliquido: '||v_totalliquido,lRaise,true,true);
    end if;

    --raise notice 'trocando total (%) por total_liquido (%)', v_total, v_totalliquido;
    --v_total = v_totalliquido;

    -- se for
    -- 6  = parcelamento de divida
    -- 16 = parcelamento de inicial
    -- 17 = parcelamento de melhorias
    -- 13 = inicial do foro
    if v_tipo in (6,16,17,13) then

      -- conta a quantidade de parcelamentos
      select count(v07_parcel)
        into v_quantparcel
        from (select distinct
                     v07_parcel
                from termo
               inner join numpres_parc on termo.v07_numpre = numpres_parc.k00_numpre) as x;
      if v_quantparcel is null then
        return '[9] - Parcelamento nao encontrado pelo numpre';
      end if;

      -- registra o codigo do parcelamento
      select v07_parcel
        into v_termo_ori
        from termo
             inner join numpres_parc on termo.v07_numpre = numpres_parc.k00_numpre
       limit 1;

    end if;

    -- recebe o codigo do novo parcelamento
    select nextval('termo_v07_parcel_seq') into v_termo;

    -- recebe o numpre do novo parcelamento
    select nextval('numpref_k03_numpre_seq') into v_numpre;

    if lRaise is true then
      perform fc_debug('termo '||v_termo,lRaise,false,false);
      perform fc_debug('numpre '||v_numpre,lRaise,false,false);
    end if;

    -- se for reparelamento pega todos os parcelamentos atuais e troca a situacao para 3(inativo)
    if lParcParc then

      for v_record_origem in
        select distinct v07_parcel
          from termo
         inner join numpres_parc on termo.v07_numpre = numpres_parc.k00_numpre
      loop
        -- inativa o parcelamento
        update termo
           set v07_situacao = 3
         where v07_parcel = v_record_origem.v07_parcel;

      end loop;
    end if;

    --if lSeparaJuroMulta and 1=2 then
      /**
       *  Funcao fc_SeparaJuroMulta()
       *
       *    Esta funcao separa o valor do juros e da multa
       *    em registros separados, lancando valor na receita de juro e multa
       *    configurada na tabrec.
       */
      --select * from fc_SeparaJuroMulta() into rSeparaJurMul;

    --end if;

    -- registra o ano do vencimento da segunda parcela
    select extract (year from v_segvenc) into v_anovenc;

    -- registra o mes do vencimento da segunda parcela
    select extract (month from v_segvenc) into v_mesvenc;

    if lRaise is true then
      perform fc_debug('v_anovenc: '||v_anovenc||' - v_mesvenc: '||v_mesvenc,lRaise,false,false);
    end if;

    v_somar = 0;

    -- soma o valor total da tabela parcelas, apenas para conferencia
    for v_record_recpar in select parcela,
                                  receit,
                                  valor
                             from parcelas
    loop
      v_somar = v_somar + v_record_recpar.valor;
      if lRaise is true then
        perform fc_debug('parcela: '||v_record_recpar.parcela||' - receita: '||v_record_recpar.receit||' - valor: '||v_record_recpar.valor,lRaise,false,false);
      end if;
    end loop;

    if lRaise is true then
      perform fc_debug('v_somar: '||v_somar,lRaise,false,false);
    end if;

    -- exibe os valores da tabela parcelas agrupado por receita, apenas para conferencia
    for v_record_recpar in select receit,
                                  sum(valor) as valor,
                                  sum(valhis+valcor+valjur+valmul-descor-descjur-descmul) as sum
                             from parcelas
                            group by receit
    loop
      if lRaise is true then
        perform fc_debug('valor da receita: '||v_record_recpar.receit||' - liquido: '||v_record_recpar.sum||' - valor: '||v_record_recpar.valor,lRaise,false,false);
      end if;
    end loop;

    -- varre a tabela parcelas por receita para gravar os registros no arrecad
    -- existe uma tabela chamada totrec que recebe os valores ja processados e armazena por receita

    -- verifica se tem registro na tabela de configuracao da receita forcada como receita de destino

    for v_record_recpar in select distinct
                                  receitaori
                             from parcelas
    loop

      select case
               when coalesce( (select count(*)
                                 from recreparcori a
                                      inner join recreparcarretipo on k72_codigo = a.k70_codigo
                                where a.k70_recori = recreparcori.k70_recori ),0) = 0
                then k71_recdest
                else case
                       when coalesce( ( select count(*)
                                          from recreparcarretipo
                                         where k72_codigo = recreparcori.k70_codigo
                                           and k72_arretipo = v_tiponovo ),0) = 0
                       then null
                       else k71_recdest
                end
             end as destino
        into v_recdestino
        from recreparcori
             inner join recreparcdest on k70_codigo = k71_codigo
       where k70_recori = v_record_recpar.receitaori
         and v_totparc >= k70_vezesini
         and v_totparc <= k70_vezesfim
         and
         (
           (     ( select count(*)
                     from recreparcori a
                          inner join recreparcarretipo on k72_codigo = a.k70_codigo
                    where a.k70_recori = recreparcori.k70_recori) = 0
             and ( select count(*)
                     from recreparcarretipo
                    where k72_codigo = recreparcori.k70_codigo
                      and k72_arretipo = v_tiponovo) = 0
           )
          or
          (     select count(*)
                  from recreparcori a
                 inner join recreparcarretipo on k72_codigo = a.k70_codigo
                 where a.k70_recori = recreparcori.k70_recori) > 0
            and (select count(*)
                   from recreparcarretipo
                  where k72_codigo = recreparcori.k70_codigo
                    and k72_arretipo = v_tiponovo) > 0
         );


       if lRaise is true or 1 = 1 then
         perform fc_debug('v_recdestino: '||v_recdestino||' - receitaori: '||v_record_recpar.receitaori||' - v_totparc: '||v_totparc||' - v_tiponovo: '||v_tiponovo,lRaise,false,false);
       end if;

       if v_recdestino is not null or v_recdestino <> 0 then
         execute ' update parcelas set receit = ' || v_recdestino || ' where ' ||
                 ' receitaori = ' || v_record_recpar.receitaori || ';';
       end if;

    end loop;

    create temp table w_base_parcelas as
      select parcela,
             receit,
             array_accum(distinct receitaori) as receitaori,
             min(hist)    as hist,
             sum(valor)   as valor,
             sum(valprop) as valprop,
             sum(valhis)  as valhis,
             sum(valcor)  as valcor,
             sum(valjur)  as valjur,
             sum(valmul)  as valmul,
             sum(descor)  as descor,
             sum(descjur) as descjur,
             sum(descmul) as descmul
        from parcelas
       group by parcela, receit
       order by receit, parcela;

    if lRaise is true then
      perform fc_debug('total de parcelas: '||v_totpar||' - v_somar: '||v_somar,lRaise,false,false);
    end if;

    if lRaise is true then
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
    end if;

    for v_record_recpar in  select *
                              from w_base_parcelas
                             order by parcela, receit
    loop

      select count(parcela) into ultimaparcelareceita
        from parcelas
       where receit = v_record_recpar.receit;

      if lRaise is true then
        perform fc_debug('   inicio do loop... parcela: '||v_record_recpar.parcela||' - receita: '||v_record_recpar.receit||' - v_totalparcelas: '||v_totalparcelas||' - valor: '||v_record_recpar.valor||' - valprop: '||v_record_recpar.valprop,lRaise,false,false);
      end if;

      lParcelaZerada = false;

      -- conta o total de parcelas desta receita
      select max(parcela)
        into v_totparcdestarec
        from parcelas
       where receit = v_record_recpar.receit;

      -- soma o que ja foi inserido na tabela totrec da receita do registro atual
      select coalesce(sum(valor),0) into v_totateagora from totrec where receit = v_record_recpar.receit;

      -- soma o total do valor da tabela parcelas da receita do registro atual
      -- V E R I F I C A R
      select round(sum(valor+valcor+valjur+valmul),2) into v_calcular from parcelas where receit = v_record_recpar.receit;

      if lRaise is true then
        perform fc_debug('v_calcular: '||v_calcular,lRaise,false,false);
      end if;

      if lRaise is true then
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug('total desta receita: '||v_record_recpar.receit||' - ate agora: '||v_totateagora,lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
      end if;

      -- registra o valor da receita do registro atual
      v_valparc = v_record_recpar.valor;

      -- se for a ultima parcela
      if v_record_recpar.parcela = v_totalparcelas or v_record_recpar.parcela = ultimaparcelareceita then

        -- se for juros na ultima
        if v_juronaultima is true then

          -- valor da parcela recebe exatamente o valor registrado na receita do registro atual
          v_valparc = v_valparc;
        else

          if lRaise is true then
            perform fc_debug('U L T I M A... - RECEITA: '||v_record_recpar.receit,lRaise,false,false);
            perform fc_debug('v_totalparcelas: '||v_totalparcelas||' - v_valparc: '||v_valparc||' - v_entrada: '||v_entrada||' - v_total: '||v_total||' - valprop: '||v_record_recpar.valprop,lRaise,false,false);
          end if;

          if lRaise is true then
            perform fc_debug('total desta receita: '||v_record_recpar.receit||' - ate agora: '||v_totateagora,lRaise,false,false);
          end if;

          -- saldo e calculado com
          -- (o total de parcelas - 2) * valor registrado na receita do registro atual
          --v_saldo = round((v_totalparcelas - 2) * ( v_valparc + v_record_recpar.valcor + v_record_recpar.valjur + v_record_recpar.valmul),2);
          v_saldo = (v_totalparcelas - 2) * v_valparc;

          if lRaise is true then
            perform fc_debug('Saldo Atual: '||v_saldo,lRaise,false,false);
          end if;

          -- saldo eh calculado com
          -- saldo calculado + ( entrada * valor proporcional dessa receita em relacao ao total do parcelamento )
          v_saldo = v_saldo + (v_entrada * v_record_recpar.valprop);

          if lRaise is true then
            perform fc_debug('111 - v_saldo: '||v_saldo||' - v_totateagora: '||v_totateagora||' - v_calcular: '||v_calcular,lRaise,false,false);
          end if;

          if lRaise is true then
            perform fc_debug('totateagora: '||v_totateagora||' - total: '||v_total||' - valprop: '||v_record_recpar.valprop||' - saldo: '||v_saldo||' - rec: '||v_record_recpar.receit||' - parc: '||v_record_recpar.parcela||' - hist: '||v_record_recpar.hist,lRaise,false,false);
          end if;

          -- se total ate agora for maior ou igual ao total do parcelamento * valor proporcional dessa receita em relacao ao total do parcelamento
          if round(v_totateagora, 2) >= round(v_total * v_record_recpar.valprop, 2) then
            if lRaise is true then
              perform fc_debug('v_totateagora: '||v_totateagora||' - v_total: '||v_total||' - valprop: '||v_record_recpar.valprop, lRaise,false,false);
              perform fc_debug('passou na ultima...',lRaise,false,false);
            end if;
            -- valor da parcela recebe zero
            v_valparc = 0;
            lParcelaZerada=true;
            continue;

          -- se total ate agora for menor ao total do parcelamento * valor proporcional dessa receita em relacao ao total do parcelamento
          else

            if lRaise is true then
              perform fc_debug('nao passou na ultima... v_total: '||v_total||' - v_saldo: '||v_saldo||' - prop: '||v_record_recpar.valprop,lRaise,false,false);
            end if;

            --Alterado forma como faz o ajuste dos centavos na última parcela,
            --pega-se o maximo para a receita e desconta-se o valor da parcela e total até então
            --verificar o round na varíavel totateagora, caso necessária consultar o valor atualizado na tabela parcelas
            nValorMaximoReceita = 0;
            select sum(valor) into nValorMaximoReceita from arrecad_parc_rec where receit = any(v_record_recpar.receitaori);
            v_valparc = (round(nValorMaximoReceita,2) - round(v_totateagora,2) - v_valparc) + v_record_recpar.valor;

            if lRaise is true then
              perform fc_debug('v_valparc: '||v_valparc,lRaise,false,false);
            end if;

            -- se valor da parcela for menor que zero
            if v_valparc < 0 then

              -- valor da parcela recebe
              -- (total do parcelamento * valor proporcional dessa receita em relacao ao total do parcelamento) - saldo - valor da parcela
              v_valparc = (v_total * v_record_recpar.valprop) - v_saldo - v_valparc;
              if lRaise is true then
                perform fc_debug(' ',lRaise,false,false);
                perform fc_debug('t e s t e: '||v_valparc,lRaise,false,false);
                perform fc_debug(' ',lRaise,false,false);
              end if;
            end if;

            --v_valparc := ( v_valparc - ( v_record_recpar.valcor + v_record_recpar.valjur + v_record_recpar.valmul ) );

          end if;

          if lRaise is true then
            perform fc_debug('Valor ultima parcela : '||v_valparc,lRaise,false,false);
          end if;

          -- resto recebe valor da parcela + total ate agora
          v_resto = v_valparc + v_totateagora;

          if lRaise is true then
            perform fc_debug('222 - v_saldo: '||v_saldo||' - totateagora: '||v_resto||' - v_valparc: '||v_valparc||' - v_calcular: '||v_calcular,lRaise,false,false);
          end if;

        end if;

      -- se nao for a ultima parcela
      else

        if lRaise is true then
          perform fc_debug(' ',lRaise,false,false);
          perform fc_debug(' n a o   e   a   u l t i m a ',lRaise,false,false);
          perform fc_debug(' ',lRaise,false,false);
        end if;

        -- se for juros na ultima
        if v_juronaultima is true then

          -- se eh a penultima parcela
          if v_record_recpar.parcela = (v_totalparcelas - 1) then

            if lRaise is true then
              perform fc_debug('nessa',lRaise,false,false);
            end if;

          end if;

        end if;

        if lRaise is true then
          perform fc_debug('v_totalparcelas: '||v_totalparcelas||' - v_valparc: '||v_valparc||' - v_entrada: '||v_entrada||' - valprop: '||v_record_recpar.valprop||' - v_total: '||v_total,lRaise,false,false);
        end if;

        -- saldo recebe (total de parcelas - 2) * valor da parcela
        -- V E R I F I C A R
        v_saldo = (v_totalparcelas - 2) * ( v_valparc + v_record_recpar.valcor + v_record_recpar.valjur + v_record_recpar.valmul );

        -- saldo recebe: saldo + (entrada * valor proporcional dessa receita em relacao ao total do parcelamento) - saldo - valor da parcela)
        v_saldo = v_saldo + (v_entrada * v_record_recpar.valprop);

        if lRaise is true then
          perform fc_debug('v_valparc: '||v_valparc,lRaise,false,false);
          perform fc_debug('parcela: '||v_record_recpar.parcela||' - v_valparc: '||v_valparc||' - saldo: '||v_saldo||' - resto: '||v_resto,lRaise,false,false);
        end if;

        -- se total ate agora for maior que total da receita do registro atual
        if round(v_totateagora,2) > round(v_calcular,2) then

          -- (desativado) v_valparc = round(v_saldo - round((v_record_recpar.parcela - 1) * v_valparc,2)::float8,2);
          -- valor da parcela recebe zero
          v_valparc = 0;
          if lRaise is true then
            perform fc_debug('Valor da parcela recebendo ZERO valparc : '||v_valparc,lRaise,false,false);
            perform fc_debug('111111111111111111111',lRaise,false,false);
          end if;

        -- se total ate agora for menor ou igual que total da receita do registro atual
        else

          -- valor ate agora recebe: parcela * valor da parcela
          -- V E R I F I C A R
          v_vlrateagora = v_record_recpar.parcela * (v_valparc + v_record_recpar.valcor + v_record_recpar.valjur + v_record_recpar.valmul);

          if lRaise is true then
            perform fc_debug('v_vlrateagora: '||v_vlrateagora||' - v_valparc: '||v_valparc,lRaise,false,false);
          end if;

          -- resto recebe: (valor total do parcelamento * valor proporcional dessa receita em relacao ao total do parcelamento) - saldo
          v_resto = v_total * v_record_recpar.valprop - v_saldo;

          if lRaise is true then
            perform fc_debug('parcela: '||v_record_recpar.parcela||' - v_valparc: '||v_valparc||' - saldo: '||v_saldo||' - resto: '||v_resto,lRaise,false,false);
          end if;

          if lRaise is true then
            perform fc_debug('v_totateagora: '||v_totateagora||' - v_valparc: '||v_valparc||' - v_calcular: '||v_calcular,lRaise,false,false);
          end if;

          -- se (total ate agora + valor da parcela) for maior que total da receita do registro atual
          if (v_totateagora + v_valparc) > v_calcular then

            -- valor da parcela recebe: total da receita do registro atual - total ate agora
            v_valparc = v_calcular - v_totateagora;
            if lRaise is true then
              perform fc_debug('22222222222',lRaise,false,false);
            end if;
          end if;

        end if;

      end if;

      if lRaise is true then
        perform fc_debug('   ...',lRaise,false,false);
      end if;

      -- se parcela = 1
      if v_record_recpar.parcela = 1 then
        -- vencimento igual ao vencimento da entrada especificada na CGF
        v_vcto = v_privenc;
        -- valor da parcela = entrada * proporcionalidade
        if lRaise is true then
          perform fc_debug('v_entrada: '||v_entrada||' - valprop: '||v_record_recpar.valprop||' - valcor: '||v_record_recpar.valcor||' - valju: '||v_record_recpar.valjur||' - valmul: '||v_record_recpar.valmul,lRaise,false,false);
        end if;

        if lRaise is true then
          perform fc_debug('   1 === v_valparc: '||v_valparc||' - v_entrada: '||v_entrada||' - valprop: '||v_record_recpar.valprop, lRaise, false,false);
        end if;

        v_valparc = fc_arredondaminimomonetario((v_entrada) * v_record_recpar.valprop);

        if lRaise is true then
          perform fc_debug('   2 === v_valparc: '||v_valparc,lRaise, false,false);
        end if;

      elsif v_record_recpar.parcela = 2 then
        -- vencimento = vencimento da segunda parcela especificada na CGF
        v_vcto = v_segvenc;
      else

        -- soma meses para calcular vencimento baseado na data de vencimento da parcela 2
        execute 'truncate vcto';
        v_comando = 'insert into vcto select ' || '''' || to_char(v_segvenc,'yyyy') || '-' || trim(to_char(v_segvenc, 'mm')) || '-' || trim(to_char(v_segvenc, 'dd')) || '''' || '::date' || '+' || '''' || v_record_recpar.parcela - 3 || ' months' || '''' || '::interval';
        execute v_comando;

        select extract (month from data),
               extract (year from data)
        from vcto
        into v_mesvenc,
        v_anovenc;

        if lRaise is true then
          perform fc_debug('',lRaise,false,false);
          perform fc_debug('v_mesvenc: '||v_mesvenc||' - parcela: '||v_record_recpar.parcela,lRaise,false,false);
          perform fc_debug('',lRaise,false,false);
        end if;

        -- se mes for 12 (dezembro)
        if to_number(to_char(v_segvenc,'mm'), '999') = 12 then
          -- proximo mes = 1 (janeiro)
          v_proxmessegvenc = 1;
        else
          -- soma mes
          v_proxmessegvenc = to_number(to_char(v_segvenc,'mm'), '999') + 1;
        end if;

        -- faz o mes ficar sempre com 2 digitos
        if v_proxmessegvenc < 10 then
          v_proxmessegvenc_c = '0' || trim(to_char(v_proxmessegvenc, '99'));
        else
          v_proxmessegvenc_c = trim(to_char(v_proxmessegvenc, '999'));
        end if;

        -- registra o dia do proximo vencimento especifidada na CGF
        v_dia = v_diaprox;

        -- soma 1 no mes de vencimento
        v_mesvenc = v_mesvenc + 1;
        if lRaise is true then
          perform fc_debug('   executando vcto... v_segvenc: '||v_segvenc||' - v_diaprox: '||v_diaprox||' - v_dia: '||v_dia||' - v_mesvenc: '||v_mesvenc||' - parc: '||v_record_recpar.parcela,lRaise,false,false);
        end if;

        -- se ultrapassar dezembro, passa para janeiro do ano seguinte
        if v_mesvenc = 13 then
          v_mesvenc = 1;
          v_anovenc = v_anovenc + 1;
        end if;

        v_mesvencprox = v_mesvenc + 1;
        v_anovencprox = v_anovenc;

        -- se ultrapassar dezembro, passa para janeiro do ano seguinte
        if v_mesvencprox = 13 then
          v_mesvencprox = 1;
          v_anovencprox = v_anovencprox + 1;
        end if;

        if lRaise is true then
          perform fc_debug('quase... v_mesvencprox: '||v_mesvencprox||' - v_anovencprox: '||v_anovencprox,lRaise,false,false);
        end if;
        -- calcula ultimo dia de fevereiro
        v_ultdiafev_c   = trim(to_char(v_anovencprox,'99999')) || '-' || trim(to_char(v_mesvencprox, '999')) || '-01';
        if lRaise is true then
          perform fc_debug('   1 - v_ultdiafev_c: '||v_ultdiafev_c,lRaise,false,false);
        end if;
        -- calcula ultimo dia de fevereiro
        v_ultdiafev_d   = trim(v_ultdiafev_c)::date - 1;

        if lRaise is true then
          perform fc_debug('   2 - v_ultdiafev_d: '||v_ultdiafev_d,lRaise,false,false);
        end if;
        -- calcula ultimo dia de fevereiro
        v_ultdiafev = to_number(to_char(v_ultdiafev_d, 'dd'), '999');

        -- testa se dia e valido nos meses
        if v_dia = 31 and v_mesvenc in (4, 6, 9, 11) then
          v_dia = 30;
          if lRaise is true then
            perform fc_debug('mudando 1',lRaise,false,false);
          end if;
        elsif v_dia >= 30 and v_mesvenc in (2) then
          v_dia = 28;
          if lRaise is true then
            perform fc_debug('mudando 2',lRaise,false,false);
          end if;
        end if;

        if lRaise is true then
          perform fc_debug('mesvenc: '||v_mesvenc||' - dia: '||v_dia,lRaise,false,false);
        end if;

        -- calcula se vencimento e correto
        if v_mesvenc = 2 and v_dia >= 28 then
          if lRaise is true then
            perform fc_debug('fevereiro...',lRaise,false,false);
          end if;
          v_dia = v_ultdiafev;
        end if;

        -- calcula vencimento
        execute 'truncate vcto';
        v_comando = 'insert into vcto select ' || '''' || to_char(v_anovenc,'99999') || '-' || trim(trim(to_char(v_mesvenc, '999'))) || '-' || trim(to_char(v_dia, '999')) || '''' || '::date';
        execute v_comando;
        select data from vcto into v_vcto;
        if lRaise is true then
          perform fc_debug('   fim vcto... '||v_vcto,lRaise,false,false);
        end if;

      end if;

      if lRaise is true then
        perform fc_debug('          inserindo em totrec a parcela '||v_record_recpar.parcela||' no valor de '||v_valparc,lRaise,false,false);
      end if;

      -- insere na tabela totrec o registro atual com o valor da parcela
      execute 'insert into totrec values (' || v_record_recpar.receit || ', ' || v_record_recpar.parcela || ', ' || v_valparc || ')';

      if lRaise is true then
        perform fc_debug('1 - parcela: '||v_record_recpar.parcela||' - valor: '||v_valparc,lRaise,false,false);
      end if;

      if lRaise is true then
        perform fc_debug('k00_numcgm: '||v_cgmpri||' - k00_receit: '||v_record_recpar.receit||' - k00_hist: '||v_record_recpar.hist||' - k00_valor: '||v_valparc||' - k00_dtvenc: '||v_vcto||' - k00_numpre: '||v_numpre||' - k00_numpar: '||v_record_recpar.parcela||' - k00_numtot: '||v_totalparcelas||' - k00_tipo: '||v_tiponovo,lRaise,false,false);
      end if;

      v_recdestino = v_record_recpar.receit;

      if lRaise is true then
        perform fc_debug('   no arrecad... val: '||v_valparc||' - recdest: '||v_recdestino||' - vcto: '||v_vcto||' - parcela: '||v_record_recpar.parcela,lRaise,false,false);
      end if;

      if v_valparc < 0 then
        return '[10] - valor da parcela ' || v_record_recpar.parcela || ' menor que zero: ' || v_valparc;
      elsif v_valparc = 0 then
        return '[11] - valor da parcela ' || v_record_recpar.parcela || ' zerada: ' || v_valparc;
      end if;

      -- se valor da parcela maior que zero
      -- insere no arrecad

      if lRaise is true then
        perform fc_debug('k00_numpre : '||v_numpre||' k00_numpar : '||v_record_recpar.parcela||' k00_receit : '||v_recdestino||' k00_valor : '||v_valparc,lRaise,false,false);
      end if;

      lGravaArrecad = true;

      if v_valparc > 0 then

        if lSeparaJuroMulta = 1 then

          if lRaise is true then

             perform fc_debug('',lRaise,false,false);
             perform fc_debug('+--------------------------------------------------------------------------------------------',lRaise,false,false);
             perform fc_debug('|                                                                                            ',lRaise,false,false);
             perform fc_debug('|      Processando dados da composicao do Numpre: '||v_numpre||' Parcela: '||v_record_recpar.parcela||' Receita: '||v_recdestino,lRaise,false,false);
             perform fc_debug('|                                                                                            ',lRaise,false,false);
             perform fc_debug('+--------------------------------------------------------------------------------------------',lRaise,false,false);
             perform fc_debug('',lRaise,false,false);

          end if;


          iSeqArrecKey := nextval('arreckey_k00_sequencial_seq');

          if lRaise is true then
             perform fc_debug('     ',lRaise,false,false);
             perform fc_debug('     1. G E R A N D O  D A D O S  N A  T A B E L A  ARRECKEY  P A R A  A  P A R C E L A: '||v_record_recpar.parcela,lRaise,false,false);
             perform fc_debug('        Sequencial: '||iSeqArrecKey||' Numpre: '||v_numpre||' Numpar: '||v_record_recpar.parcela||' Receita: '||v_recdestino||' Historico: '||v_record_recpar.hist||' Tipo: '||v_tiponovo,lRaise,false,false);
             perform fc_debug('',lRaise,false,false);
          end if;

          insert into arreckey ( k00_sequencial,
                                 k00_numpre,
                                 k00_numpar,
                                 k00_receit,
                                 k00_hist,
                                 k00_tipo )
                        values ( iSeqArrecKey,
                                 v_numpre,
                                 v_record_recpar.parcela,
                                 v_recdestino,
                                 v_record_recpar.hist,
                                 v_tiponovo
                               );


          select round(sum(valhis),2),
                 round(sum(valcor-descor-valhis),2),
                 round(sum(valjur-descjur),2),
                 round(sum(valmul-descmul),2)
            into nVlrTotalHistorico,
                 nVlrTotalCorrecao,
                 nVlrTotalJuros,
                 nVlrTotalMulta
            from w_base_parcelas
           where receit = v_record_recpar.receit;

          select round(sum(vlrdesccor),2),
                 round(sum(vlrdescjur),2),
                 round(sum(vlrdescmul),2)
            into nVlrTotalDescontoCorrigido,
                 nVlrTotalDescontoJuros,
                 nVlrTotalDescontoMulta
            from arrecad_parc_rec;

          if lRaise is true then
            perform fc_debug('     ',lRaise,false,false);
            perform fc_debug('     2. C A L C U L A N D O  V A L O R E S  D A  C O M P O S I C A O  D A  P A R C E L A',lRaise,false,false);
            perform fc_debug('     ',lRaise,false,false);
            perform fc_debug('        Valores Totais do Debito:  ',lRaise,false,false);
            perform fc_debug('        Total Historico(nVlrTotalHistorico) ..: '||nVlrTotalHistorico,lRaise,false,false);
            perform fc_debug('        Total Correcao(nVlrTotalCorrecao) ....: '||nVlrTotalCorrecao,lRaise,false,false);
            perform fc_debug('        Total Juros(nVlrTotalJuros) ..........: '||nVlrTotalJuros,lRaise,false,false);
            perform fc_debug('        Total Multa(nVlrTotalMulta) ..........: '||nVlrTotalMulta,lRaise,false,false);
            perform fc_debug('        v_somar(???): '||v_somar,lRaise,false,false);
            perform fc_debug('     ',lRaise,false,false);
          end if;

          v_historico_compos = v_record_recpar.valhis;
          v_correcao_compos  = ( v_record_recpar.valcor - v_record_recpar.descor - v_record_recpar.valhis );
          v_juros_compos     = ( v_record_recpar.valjur - v_record_recpar.descjur );
          v_multa_compos     = ( v_record_recpar.valmul - v_record_recpar.descmul );

          if lRaise is true then
            perform fc_debug('        Parcela: '||v_record_recpar.parcela||' - Receita: '||v_record_recpar.receit,lRaise,false,false);
            perform fc_debug('        Valor da Parcela(v_valparc) ........................: '||v_valparc,lRaise,false,false);
            perform fc_debug('        Valor historico da Composicao(v_historico_compos) ..: '||v_historico_compos,lRaise,false,false);
            perform fc_debug('        Valor corrigido da Composicao(v_correcao_compos) ...: '||v_correcao_compos,lRaise,false,false);
            perform fc_debug('        Valor juros da Composicao(v_juros_compos) ..........: '||v_juros_compos,lRaise,false,false);
            perform fc_debug('        Valor multa da Composicao(v_multa_compos) ..........: '||v_multa_compos,lRaise,false,false);
          end if;

          --
          --
          -- Caso seja a ultima parcela do parcelamento realizamos a verificação nos valores gerados para a composição das parcelas
          -- Se encontrar alguma diferenca é realizado o processamento do ajuste da composicao
          --
          if v_record_recpar.parcela = v_totparcdestarec then

             if lRaise is true then
                perform fc_debug('       ',lRaise,false,false);
                perform fc_debug('       >> U L T I M A  P A R C E L A  D O  P A R C E L A M E N T O <<',lRaise,false,false);
                perform fc_debug('       2.1  VERIFICANDO E PROCESSANDO CORRECAO NAS DIFERENCAS DE VALORES(ARREDONDAMENTO) ',lRaise,false,false);
                perform fc_debug('       ',lRaise,false,false);
             end if;

             --
             -- Verificamos os valores já gerados para a composicao do débito somando com o valor que será gerado para esta parcela e receita
             --
             select sum(k00_vlrhist)+v_historico_compos,
                    sum(k00_correcao)+v_correcao_compos,
                    sum(k00_juros)+v_juros_compos,
                    sum(k00_multa)+v_multa_compos
               into nVlrHistoricoComposicao,
                    nVlrCorrecaoComposicao,
                    nVlrJurosComposicao,
                    nVlrMultaComposicao
               from arrecadcompos
              inner join arreckey on arreckey.k00_sequencial = arrecadcompos.k00_arreckey
              where k00_numpre = v_numpre;

              --
              -- Verificamos o total do valor de origem do parcelamento sem alterações e aplicações de regra.
              --
              select sum(k00_vlrhis),
                     sum(k00_vlrcor-k00_vlrhis),
                     sum(k00_juros),
                     sum(k00_multa),
                     sum(k00_desconto),
                     sum(k00_total)
                into nVlrTotalParcelamentoHistorico,
                     nVlrTotalParcelamentoCorrigido,
                     nVlrTotalParcelamentoJuros,
                     nVlrTotalParcelamentoMulta,
                     nVlrTotalParcelamento
                from totalportipo;

              --
              -- Calculamos os valores de composicao Total e Diferencas
              --
              nVlrTotalComposicao              := (nVlrHistoricoComposicao+nVlrCorrecaoComposicao+nVlrJurosComposicao+nVlrMultaComposicao);

              nVlrDiferencaComposicaoHistorico := nVlrTotalParcelamentoHistorico - nVlrHistoricoComposicao;
              nVlrDiferencaComposicaoCorrecao  := nVlrTotalParcelamentoCorrigido - nVlrCorrecaoComposicao - nVlrTotalDescontoCorrigido;
              nVlrDiferencaComposicaoJuros     := nVlrTotalParcelamentoJuros     - nVlrJurosComposicao    - nVlrTotalDescontoJuros;
              nVlrDiferencaComposicaoMulta     := nVlrTotalParcelamentoMulta     - nVlrMultaComposicao    - nVlrTotalDescontoMulta;

              nVlrDiferencaComposicaoTotal     := round(abs(nVlrDiferencaComposicaoHistorico)+abs(nVlrDiferencaComposicaoCorrecao)+abs(nVlrDiferencaComposicaoJuros)+abs(nVlrDiferencaComposicaoMulta),2);

              if lRaise is true then
                 perform fc_debug('         Valores gerados no processamento da composicao: ',lRaise,false,false);
                 perform fc_debug('         nVlrTotalHistorico ............: '||nVlrHistoricoComposicao,lRaise,false,false);
                 perform fc_debug('         nVlrTotalCorrecao .............: '||nVlrCorrecaoComposicao,lRaise,false,false);
                 perform fc_debug('         nVlrTotalJuros ................: '||nVlrJurosComposicao,lRaise,false,false);
                 perform fc_debug('         nVlrTotalMulta ................: '||nVlrMultaComposicao,lRaise,false,false);
                 perform fc_debug('         ---------------------------------------',lRaise,false,false);
                 perform fc_debug('         Total da Composicao ..........: '||nVlrTotalComposicao,lRaise,false,false);
                 perform fc_debug('         Total do Parcelamento ........: '||nVlrTotalParcelamento,lRaise,false,false);
                 perform fc_debug(' ',lRaise,false,false);
                 perform fc_debug('         Valores das diferencas encontradas: ',lRaise,false,false);
                 perform fc_debug('         Diferenca no Vlr. Historico ..: '||nVlrDiferencaComposicaoHistorico,lRaise,false,false);
                 perform fc_debug('         Diferenca no Vlr. Corrigido ..: '||nVlrDiferencaComposicaoCorrecao,lRaise,false,false);
                 perform fc_debug('         Diferenca no Vlr. dos Juros ..: '||nVlrDiferencaComposicaoJuros,lRaise,false,false);
                 perform fc_debug('         Diferenca no Vlr. da Multa ...: '||nVlrDiferencaComposicaoMulta,lRaise,false,false);
                 perform fc_debug('         ---------------------------------------',lRaise,false,false);
                 perform fc_debug('         Total da Diferenca (abs) .....: '||nVlrDiferencaComposicaoTotal,lRaise,false,false);
              end if;

              --
              -- Caso seja encontrada diferenca na composicao do débito com o total parcelado
              -- Realizamos os ajustes necessarios nos valores onde existem diferenca, se o valor da diferenca existir e não for maior que 1.
              --
              if abs(nVlrDiferencaComposicaoTotal) between 0.01 and 1.00 then

                 if lRaise is true then
                     perform fc_debug('',lRaise,false,false);
                     perform fc_debug('         >> Processando acerto da diferenca da composicao <<',lRaise,false,false);
                 end if;

                 if abs(nVlrDiferencaComposicaoHistorico) <> 0 then

                  if lRaise is true then
                     perform fc_debug('            - Corrigindo diferenca no valor Historico de '||nVlrDiferencaComposicaoHistorico,lRaise,false,false);
                  end if;
                  v_historico_compos := v_historico_compos+nVlrDiferencaComposicaoHistorico;
                 end if;

                 if abs(nVlrDiferencaComposicaoCorrecao) <> 0 then

                  if lRaise is true then
                     perform fc_debug('            - Corrigindo diferenca no valor Corrigido de '||nVlrDiferencaComposicaoCorrecao,lRaise,false,false);
                  end if;
                  v_correcao_compos := v_correcao_compos+nVlrDiferencaComposicaoCorrecao;
                 end if;

                 if abs(nVlrDiferencaComposicaoJuros) <> 0 then

                  if lRaise is true then
                     perform fc_debug('            - Corrigindo diferenca no valor dos Juros de '||nVlrDiferencaComposicaoJuros,lRaise,false,false);
                  end if;
                  v_juros_compos := v_juros_compos+nVlrDiferencaComposicaoJuros;

                 end if;

                 if abs(nVlrDiferencaComposicaoMulta) <> 0 then

                  if lRaise is true then
                     perform fc_debug('            - Corrigindo diferenca no valor da Multa de '||nVlrDiferencaComposicaoMulta,lRaise,false,false);
                  end if;
                  v_multa_compos := v_multa_compos+nVlrDiferencaComposicaoMulta;

                 end if;

                 --
                 --
                 -- Se a variável de sessão db_debugon estiver setada, verificamos os valores finais gerados para a composição do débito
                 -- Essa verificação é realizada buscando os valores já gerados somando com o valor da receita que será cadastrado já com
                 -- os ajustes de valores.
                 --
                 if lRaise is true then

                    select sum(k00_vlrhist)  + v_historico_compos,
                           sum(k00_correcao) + v_correcao_compos,
                           sum(k00_juros)    + v_juros_compos,
                           sum(k00_multa)    + v_multa_compos
                      into nVlrHistoricoComposicao,
                           nVlrCorrecaoComposicao,
                           nVlrJurosComposicao,
                           nVlrMultaComposicao
                      from arrecadcompos
                     inner join arreckey on arreckey.k00_sequencial = arrecadcompos.k00_arreckey
                     where k00_numpre = v_numpre;

                    nVlrTotalComposicao              := (nVlrHistoricoComposicao+nVlrCorrecaoComposicao+nVlrJurosComposicao+nVlrMultaComposicao);

                    nVlrDiferencaComposicaoHistorico := nVlrTotalParcelamentoHistorico - nVlrHistoricoComposicao;
                    nVlrDiferencaComposicaoCorrecao  := nVlrTotalParcelamentoCorrigido - nVlrCorrecaoComposicao - nVlrTotalDescontoCorrigido;
                    nVlrDiferencaComposicaoJuros     := nVlrTotalParcelamentoJuros     - nVlrJurosComposicao    - nVlrTotalDescontoJuros;
                    nVlrDiferencaComposicaoMulta     := nVlrTotalParcelamentoMulta     - nVlrMultaComposicao    - nVlrTotalDescontoMulta;

                    nVlrDiferencaComposicaoTotal     := round(abs(nVlrDiferencaComposicaoHistorico)+abs(nVlrDiferencaComposicaoCorrecao)+abs(nVlrDiferencaComposicaoJuros)+abs(nVlrDiferencaComposicaoMulta),2);

                    perform fc_debug('         Valores gerados no processamento da composicao apos o acerto das diferencas: ',lRaise,false,false);
                    perform fc_debug('         nVlrTotalHistorico ............: '||nVlrHistoricoComposicao,lRaise,false,false);
                    perform fc_debug('         nVlrTotalCorrecao .............: '||nVlrCorrecaoComposicao,lRaise,false,false);
                    perform fc_debug('         nVlrTotalJuros ................: '||nVlrJurosComposicao,lRaise,false,false);
                    perform fc_debug('         nVlrTotalMulta ................: '||nVlrMultaComposicao,lRaise,false,false);
                    perform fc_debug('         ---------------------------------------',lRaise,false,false);
                    perform fc_debug('         Total da Composicao ..........: '||nVlrTotalComposicao,lRaise,false,false);
                    perform fc_debug('         Total do Parcelamento ........: '||nVlrTotalParcelamento,lRaise,false,false);
                    perform fc_debug(' ',lRaise,false,false);
                    perform fc_debug('         Valores das diferencas encontradas: ',lRaise,false,false);
                    perform fc_debug('         Diferenca no Vlr. Historico ..: '||nVlrDiferencaComposicaoHistorico,lRaise,false,false);
                    perform fc_debug('         Diferenca no Vlr. Corrigido ..: '||nVlrDiferencaComposicaoCorrecao,lRaise,false,false);
                    perform fc_debug('         Diferenca no Vlr. dos Juros ..: '||nVlrDiferencaComposicaoJuros,lRaise,false,false);
                    perform fc_debug('         Diferenca no Vlr. da Multa ...: '||nVlrDiferencaComposicaoMulta,lRaise,false,false);
                    perform fc_debug('         ---------------------------------------',lRaise,false,false);
                    perform fc_debug('         Total da Diferenca (abs) .....: '||nVlrDiferencaComposicaoTotal,lRaise,false,false);

                 end if;

              end if;

          end if;

          iSeqArrecadcompos := nextval('arrecadcompos_k00_sequencial_seq');
          insert into arrecadcompos ( k00_sequencial,
                                      k00_arreckey,
                                      k00_vlrhist,
                                      k00_correcao,
                                      k00_juros,
                                      k00_multa )
                             values ( iSeqArrecadcompos,
                                      iSeqArrecKey,
                                      v_historico_compos,
                                      v_correcao_compos,
                                      v_juros_compos,
                                      v_multa_compos );

          if lRaise is true then

            perform fc_debug('',lRaise,false,false);
            perform fc_debug('     3. I N S E R I N D O  R E G I S T R O S  D E  C O M P O S I C A O (ArrecadCompos)',lRaise,false,false);
            perform fc_debug('        Cod. Arreckey(k00_arreckey): '||iSeqArrecKey||' Numpre: '||v_numpre||' Parcela: '||v_record_recpar.parcela||' Receita: '||v_recdestino,lRaise,false,false);
            perform fc_debug('',lRaise,false,false);

          end if;

          if v_historico_compos = 0 and v_correcao_compos = 0 and v_juros_compos = 0 and v_multa_compos = 0 then
            v_valparc = 0;
            lGravaArrecad = false;
          else
            v_valparc = round(v_historico_compos,2);
          end if;

          if lRaise is true then

             perform fc_debug('',lRaise,false,false);
             perform fc_debug('+--------------------------------------------------------------------------------------------',lRaise,false,false);
             perform fc_debug('|                                                                                            ',lRaise,false,false);
             perform fc_debug('|      Fim do processamento da composicao do Numpre: '||v_numpre||' Parcela: '||v_record_recpar.parcela||' Receita: '||v_recdestino,lRaise,false,false);
             perform fc_debug('|                                                                                            ',lRaise,false,false);
             perform fc_debug('+--------------------------------------------------------------------------------------------',lRaise,false,false);
             perform fc_debug('',lRaise,false,false);

          end if;

        end if;

        if lRaise is true then
            perform fc_debug(' ',lRaise,false,false);
            perform fc_debug('Inserindo dados da parcela no Arrecad',lRaise,false,false);
            perform fc_debug('Numpre: '||v_numpre||' Numpar: '||v_record_recpar.parcela||' Receita: '||v_recdestino||' Valor: '||v_valparc||' - Round: '||round(v_valparc,2),lRaise,false,false);
            perform fc_debug(' ',lRaise,false,false);
        end if;

        if lSeparaJuroMulta = 2 then

          if (round(v_valparc,2) <= 0 or v_valparc is null) then
            return '[12] - valor da parcela ' || trim(to_char(v_record_recpar.parcela, '999')) || ' zerada ou em branco! Contate suporte';
          end if;

        end if;

        if lGravaArrecad is true then

          insert into arrecad (k00_numcgm,
                               k00_dtoper,
                               k00_receit,
                               k00_hist,
                               k00_valor,
                               k00_dtvenc,
                               k00_numpre,
                               k00_numpar,
                               k00_numtot,
                               k00_numdig,
                               k00_tipo,
                               k00_tipojm)
                       values (v_cgmpri,
                               dDataUsu,
                               v_recdestino,
                               v_record_recpar.hist,
                               round(v_valparc,2),
                               v_vcto,
                               v_numpre,
                               v_record_recpar.parcela,
                               v_totalparcelas,
                               0,
                               v_tiponovo,
                               0);

          select k00_valor
            into v_teste
            from arrecad
           where k00_numpre = v_numpre
             and k00_numpar = v_record_recpar.parcela
             and k00_receit = v_recdestino;

          if lRaise is true then
            perform fc_debug('Dados inseridos na Arrecad: Valor: '||v_valparc||' - Round: '||round(v_valparc,2)||' - Teste(Valor inserido no Arrecad): '||v_teste,lRaise,false,false);
          end if;

        end if;

        if lRaise is true then
          perform fc_debug(' ',lRaise,false,false);
          perform fc_debug(' ',lRaise,false,false);
        end if;

      else
        perform fc_debug('Valor da parcela(v_valparc) menor ou igual a zero: '||v_valparc,lRaise,false,false);
      end if;

      if lRaise is true then
        perform fc_debug('Receita Origem: '||v_record_recpar.receit||' - Receita Destino: '||v_recdestino,lRaise,false,false);
        perform fc_debug('Receita: '||v_record_recpar.receit||' - Qtd Total de Parcelas da Receita: '||v_totparcdestarec,lRaise,false,false);
      end if;

      -- conta a quantidade total de parcelas desta receita
      select count(*)
        into v_totparcdestarec
        from parcelas
       where receit = v_record_recpar.receit;

      if lRaise is true then
        perform fc_debug('Receita: '||v_record_recpar.receit||' - Qtd Total de Parcelas da Receita: '||v_totparcdestarec,lRaise,false,false);
      end if;

      --
      -- Se parcela atual for igual a ultima parcela desta receita
      -- reinicia as variaveis com os dados especificados na CGF para o vencimento da parcela 2
      --
      if v_record_recpar.parcela = v_totparcdestarec then

        select extract (year from v_segvenc)
          into v_anovenc;

        select extract (month from v_segvenc)
          into v_mesvenc;

      end if;

    end loop;

    if lRaise is true then
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
    end if;

    if lRaise is true then

      -- mostra os valores por parcela do arrecad, apenas para conferencia
      for v_record_recpar in select k00_numpar,
                                    sum(k00_valor)
                               from arrecad
                              where k00_numpre = v_numpre
                              group by k00_numpar
      loop

        if lRaise is true then
          perform fc_debug('2 - parcela: '||v_record_recpar.k00_numpar||' - valor: '||v_record_recpar.sum,lRaise,false,false);
        end if;

      end loop;

    end if;

    -- sum do campo valor
    select sum(valor)
      into nValorTotalOrigem
      from w_base_parcelas;

    for rPercOrigem in select numpre,
                              numpar,
                              receit,
                              sum(valor) as valor
                         from arrecad_parc_rec
                        group by numpre, numpar, receit
    loop

      nPercCalc := ( ( rPercOrigem.valor / nValorTotalOrigem ) * 100 );

      --raise notice 'valor: % - nValorTotalOrigem: % - PercCalcComRound: %', rPercOrigem.valor, nValorTotalOrigem, nPercCalc ;

      perform sum(k00_perc)
         from ( select k00_matric as k00_origem,
                       coalesce(k00_perc, 100) as k00_perc,
                       1 as tipo
                  from arrematric
                 where k00_numpre = rPercOrigem.numpre
                 union
                select k00_inscr as k00_origem,
                       coalesce(k00_perc, 100) as k00_perc,
                       2 as tipo
                  from arreinscr
                 where k00_numpre = rPercOrigem.numpre
                union
                select 0   as k00_origem,
                       100 as k00_perc,
                       3   as tipo
                  from arrenumcgm
                       left join arrematric on arrematric.k00_numpre = arrenumcgm.k00_numpre
                       left join arreinscr  on arreinscr.k00_numpre  = arrenumcgm.k00_numpre
                 where arrematric.k00_numpre is null
                   and arreinscr.k00_numpre  is null
                   and arrenumcgm.k00_numpre = rPercOrigem.numpre
              ) as x
       having cast(round(sum(k00_perc),2) as numeric) <> cast(100 as numeric);
      if found then
          return '[13] - Inconsistencia no percentual da origem - numpre: ' || rPercOrigem.numpre;
      end if;

      for v_record_perc in select k00_matric              as k00_origem,
                                  coalesce(k00_perc, 100) as k00_perc,
                                  1                       as tipo
                             from arrematric
                            where k00_numpre = rPercOrigem.numpre
                            union
                           select k00_inscr               as k00_origem,
                                  coalesce(k00_perc, 100) as k00_perc,
                                  2                       as tipo
                             from arreinscr
                            where k00_numpre = rPercOrigem.numpre
                            union
                           select 0   as k00_origem,
                                  100 as k00_perc,
                                  3   as tipo
                             from arrenumcgm
                             left join arrematric on arrematric.k00_numpre = arrenumcgm.k00_numpre
                             left join arreinscr  on arreinscr.k00_numpre  = arrenumcgm.k00_numpre
                            where arrematric.k00_numpre is null
                              and arreinscr.k00_numpre  is null
                              and arrenumcgm.k00_numpre = rPercOrigem.numpre
      loop

        if lRaise then
          perform fc_debug('---------------------------------------------------------------',lRaise,false,false);
          perform fc_debug('numpre: '||rPercOrigem.numpre||' - perc: '||v_record_perc.k00_perc||' - tipo: '||v_record_perc.tipo||' - percentual por registro: '||nPercCalc,lRaise,false,false);
          perform fc_debug('---------------------------------------------------------------',lRaise,false,false);
        end if;

        if v_record_perc.tipo = 1 then

            execute 'insert into arrecad_parc_rec_perc values ('|| rPercOrigem.numpre                        || ','
                                                                || rPercOrigem.numpar                        || ','
                                                                || rPercOrigem.receit                        || ','
                                                                || v_record_perc.k00_origem                  || ','
                                                                || nPercCalc * v_record_perc.k00_perc / 100  || ','
                                                                || 0                                         || ','
                                                                || 0                                         || ','
                                                                || 0                                         || ','
                                                                || v_record_perc.tipo                        || ');';
        elsif v_record_perc.tipo = 2 then

            execute 'insert into arrecad_parc_rec_perc values (' || rPercOrigem.numpre                        || ','
                                                                 || rPercOrigem.numpar                        || ','
                                                                 || rPercOrigem.receit                        || ','
                                                                 || 0                                         || ','
                                                                 || 0                                         || ','
                                                                 || v_record_perc.k00_origem                  || ','
                                                                 || nPercCalc * v_record_perc.k00_perc / 100  || ','
                                                                 || 0                                         || ','
                                                                 || v_record_perc.tipo                        || ');';

        elsif v_record_perc.tipo = 3 then

            execute 'insert into arrecad_parc_rec_perc values (' || rPercOrigem.numpre                        || ','
                                                                 || rPercOrigem.numpar                        || ','
                                                                 || rPercOrigem.receit                        || ','
                                                                 || 0                                         || ','
                                                                 || 0                                         || ','
                                                                 || 0                                         || ','
                                                                 || 0                                         || ','
                                                                 || nPercCalc * v_record_perc.k00_perc / 100  || ','
                                                                 || v_record_perc.tipo                        || ');';
        end if;

      end loop;

    end loop;

    /**
     * Somamos o percentual virtual do cgm para distribui-lo entre as origens (Matricula e Inscricao)
     */
    select coalesce(sum(perccgm), 0)
      into nPercentualVirtualCgm
      from arrecad_parc_rec_perc
     where percmatric = 0
       and percinscr  = 0;

    select count(*)
      into iQtdRegistrosMatricula
      from arrecad_parc_rec_perc
     where tipo = 1;

    select count(*)
      into iQtdRegistrosInscricao
      from arrecad_parc_rec_perc
     where tipo = 2;


    if ( ((iQtdRegistrosMatricula + iQtdRegistrosInscricao) > 0) and (nPercentualVirtualCgm > 0) ) then

      nDiferencaPercentualCGM = coalesce((nPercentualVirtualCgm / (iQtdRegistrosMatricula + iQtdRegistrosInscricao)), 0);

      if lRaise then
        perform fc_debug('nDiferencaPercentualCGM' || nDiferencaPercentualCGM, lRaise, false, false);
      end if;

      update arrecad_parc_rec_perc
         set percmatric = percmatric + nDiferencaPercentualCGM
       where tipo = 1 ;

      update arrecad_parc_rec_perc
         set percinscr = percinscr + nDiferencaPercentualCGM
       where tipo = 2 ;

      update arrecad_parc_rec_perc
         set perccgm = 0
       where tipo = 3;

    end if;


    /**
     * Calculamos a diferenca no valor percentual entre o somatorio de todas as origens (Matricula e Inscricao)
     */
    select 100 - (sum(percmatric) + sum(percinscr))
      into nDiferencaPercentualAjuste
      from arrecad_parc_rec_perc
     where tipo in (1, 2);

    /**
     * Se existir diferenca no percentual
     * Ajustamos a diferenca no arredondamento no primeiro registro encontrado
     */
    if nDiferencaPercentualAjuste <> cast(0 as numeric(15, 10)) then

      if lRaise then
        perform fc_debug('---------------------------------------------------------------',lRaise,false,false);
        perform fc_debug('Valor da diferenca de arredondamento: '||nDiferencaPercentualAjuste,lRaise,false,false);
        perform fc_debug('---------------------------------------------------------------',lRaise,false,false);
      end if;

      for rAjusteDiferencaPercentual in select *
                                          from arrecad_parc_rec_perc
                                         where tipo <> 3
                                         limit 1
      loop

        if rAjusteDiferencaPercentual.tipo = 1 then

          update arrecad_parc_rec_perc
             set percmatric = percmatric + (select 100 - (sum(percmatric) + sum(percinscr))
                                              from arrecad_parc_rec_perc
                                             where tipo in (1, 2))
           where numpre = rAjusteDiferencaPercentual.numpre
             and numpar = rAjusteDiferencaPercentual.numpar
             and receit = rAjusteDiferencaPercentual.receit
             and matric = rAjusteDiferencaPercentual.matric;

        elsif rAjusteDiferencaPercentual.tipo = 2 then

          update arrecad_parc_rec_perc
             set percinscr = percinscr + (select 100 - (sum(percmatric) + sum(percinscr))
                                              from arrecad_parc_rec_perc
                                             where tipo in (1, 2))
           where numpre = rAjusteDiferencaPercentual.numpre
             and numpar = rAjusteDiferencaPercentual.numpar
             and receit = rAjusteDiferencaPercentual.receit
             and inscr  = rAjusteDiferencaPercentual.inscr;
        end if;

      end loop;

    end if;

    nSomaPercMatric = 0;
    nTotArreMatric  = 0;

    select sum(percmatric)
      into nTotArreMatric
      from arrecad_parc_rec_perc;

    for rPercOrigem in select matric,
                              sum(percmatric) as k00_perc,
                              tipo
                         from arrecad_parc_rec_perc
                        where matric > 0
                        group by matric,tipo
    loop

      if lRaise then
        perform fc_debug('---------------------------------------------------------------',lRaise,false,false);
        perform fc_debug('matric: '||rPercOrigem.matric||' - perc: '||rPercOrigem.k00_perc||' numpre : '||v_numpre ,lRaise,false,false);
        perform fc_debug('---------------------------------------------------------------',lRaise,false,false);
      end if;

      -- tipo = 3 quer dizer que nao tem origem de matricula ou inscricao
      -- (o numpre origem esta somente na arrenumcgm ou seja nao precisa gravar percentual na arrematric ou arreinscr)
      if rPercOrigem.tipo <> 3 then
              insert into arrematric (k00_matric,
                                      k00_numpre,
                                      k00_perc)
                              values (rPercOrigem.matric,
                                      v_numpre,
                                      rPercOrigem.k00_perc);
      end if;

       v_totalzao       := v_totalzao + rPercOrigem.k00_perc;
       nSomaPercMatric  := nSomaPercMatric + rPercOrigem.k00_perc;

    end loop;

    if lRaise then
      perform fc_debug('v_totalzao (1): '||v_totalzao,lRaise,false,false);
    end if;

    nSomaPercInscr = 0;
    nTotArreInscr  = 0;

    select sum(percinscr)
      into nTotArreInscr
      from arrecad_parc_rec_perc;

    for rPercOrigem in select inscr,
                              sum(percinscr) as k00_perc,
                              tipo
                         from arrecad_parc_rec_perc
                        where inscr > 0
                        group by inscr,tipo
    loop

      if lRaise then
        raise info 'inscr: % - perc: % numpre : % ',rPercOrigem.inscr, rPercOrigem.k00_perc, v_numpre;
      end if;

      if lRaise then
        perform fc_debug('---------------------------------------------------------------',lRaise,false,false);
        perform fc_debug('inscr: '||rPercOrigem.inscr||' - perc: '||rPercOrigem.k00_perc||' numpre : '||v_numpre,lRaise,false,false);
        perform fc_debug('---------------------------------------------------------------',lRaise,false,false);
      end if;

      -- tipo = 3 quer dizer que nao tem origem de matricula ou inscricao
      -- (o numpre origem esta somente na arrenumcgm ou seja nao precisa gravar percentual na arrematric ou arreinscr)
      if rPercOrigem.tipo <> 3 then
            insert into arreinscr (k00_inscr,
                                   k00_numpre,
                                   k00_perc)
                           values (rPercOrigem.inscr,
                                   v_numpre,
                                   rPercOrigem.k00_perc);
      end if;

      v_totalzao      := v_totalzao + rPercOrigem.k00_perc;
      nSomaPercInscr  := nSomaPercInscr + rPercOrigem.k00_perc;

    end loop;

    if lRaise then
      perform fc_debug('v_totalzao (2): '||v_totalzao,lRaise,false,false);
      perform fc_debug('nTotArreInscr : '|| nTotArreInscr || 'nSomaPercInscr : ' || nSomaPercInscr || 'TOTAL: ' ||(nTotArreInscr-nSomaPercInscr) );
    end if;

    if lRaise then
      perform fc_debug('v_totalzao (3): '||v_totalzao,lRaise,false,false);
    end if;

    for rPercOrigem in select numpre,
                              sum(perccgm) as k00_perc
                         from arrecad_parc_rec_perc
                        where tipo = 3
                        group by numpre
    loop

      if lRaise then
         perform fc_debug('---------------------------------------------------------------',lRaise,false,false);
         perform fc_debug(' por cgm -- numpre -- '||rPercOrigem.k00_perc||' percentual -- '||rPercOrigem.numpre,lRaise,false,false);
         perform fc_debug('---------------------------------------------------------------',lRaise,false,false);
      end if;

      v_totalzao := v_totalzao + rPercOrigem.k00_perc;

    end loop;

    -- Corrige arredondamentos
    nPercCalc = 100.00 - v_totalzao;
    if nPercCalc < 0.5 then

       --
       -- Jogamos a diferença do percentual na Arreinscr quando:
       --  - Não existir vinculo com matricula
       --  - O percentual da inscrição for menor que o percentual da matricula
       --
       -- Jogamos a difença do percentual na Arrematric quando:
       -- - Não existir vinculo com a inscrição
       -- - O Percentual da matricula for menor que o percentual da inscrição
       --
      if lRaise then
        perform fc_debug('nPercCalc < 0.5 --------------------- nSomaPercInscr ...: '||nSomaPercInscr ,lRaise,false,false);
        perform fc_debug('nPercCalc < 0.5 --------------------- nSomaPercMatric...: '||nSomaPercMatric,lRaise,false,false);
        perform fc_debug('nPercCalc < 0.5 --------------------- nPercCalc.........: '||nPercCalc      ,lRaise,false,false);
        perform fc_debug('nPercCalc < 0.5 --------------------- v_totalzao........: '||v_totalzao     ,lRaise,false,false);
      end if;

       v_totalzao := v_totalzao + nPercCalc;
       if lRaise then
          perform fc_debug('v_totalzao (4): '||v_totalzao,lRaise,false,false);
       end if;

    end if;

    -- soma os percentuais da arrematric e arreinscr... nao esquecendo de que pode NAO ter registros em nenhuma das duas tabelas

    if lRaise is true then
      perform fc_debug(' Apos nPercCalc < 0.5 ---- total utilizado na comparacao final: '||v_total,lRaise,false,false);
      perform fc_debug(' Apos nPercCalc < 0.5 ---- totalzao ..........................: '||v_totalzao,lRaise,false,false);
    end if;

    if round(v_totalzao, 2)::numeric <> 100::numeric and round(v_totalzao, 2)::numeric <> 0::numeric then
      return '[14] - Erro calculando percentual entre as origens devedoras';
    end if;

    if lRaise is true then
      perform fc_debug('',lRaise,false,false);
      perform fc_debug(' Verificando percentuais na arrematric, arreinscr e arrenumcgm gerados para o numpre '||v_numpre,lRaise,false,false);
      perform fc_debug('',lRaise,false,false);
    end if;


    select sum(k00_perc)
      into nValidacaoPerc

       from ( select k00_matric as k00_origem,
                     coalesce(k00_perc, 100) as k00_perc,
                     1 as tipo
                from arrematric
               where k00_numpre = v_numpre
               union
              select k00_inscr as k00_origem,
                     coalesce(k00_perc, 100) as k00_perc,
                     2 as tipo
                from arreinscr
               where k00_numpre = v_numpre
              union
              select 0   as k00_origem,
                     100 as k00_perc,
                     3   as tipo
                from arrenumcgm
                     left join arrematric on arrematric.k00_numpre = arrenumcgm.k00_numpre
                     left join arreinscr  on arreinscr.k00_numpre  = arrenumcgm.k00_numpre
               where arrematric.k00_numpre is null
                 and arreinscr.k00_numpre  is null
                 and arrenumcgm.k00_numpre = v_numpre
            ) as x;

    --having cast(round(nValidacaoPerc +nPercentualVirtualCgm,2) as numeric) <> cast(100.00 as numeric);
    --raise notice 'nValidacaoPerc : % nPercentualVirtualCgm : % ',nValidacaoPerc,nPercentualVirtualCgm;

    --nValidacaoPerc := nValidacaoPerc + nPercentualVirtualCgm;
    nValidacaoPerc := nValidacaoPerc;

    perform fc_debug('----nValidacaoPerc        : ' || nValidacaoPerc);
    perform fc_debug('----nPercentualVirtualCgm : ' || nPercentualVirtualCgm);
    perform fc_debug('----v_totalzao : ' || v_totalzao);

    -- return 'final percentual 2';
    if round(nValidacaoPerc, 2) <> 100.00 then

        --return 'final : perc : '||round(nValidacaoPerc,2)||' numpre : '||v_numpre;

        --
        -- Verificamos se o problema é devido estar no parcelamento débitos que pertencem a matricula/inscrição e débitos sem vinculo com matricula/inscricao
        -- Se for encontrado um numpre que não esteja vinculado a matricula e a inscrição na origem, mostramos uma mensagem de erro diferenciada para facilitar a
        -- correção do caso. Geralmente a correção é realizada vinculando o numpre a uma matricula ou inscrição.
        --
        select array_to_string(array_accum( distinct arrecad_parc_rec.numpre),',')
          into sNumpreSemVinculoMatricInsc
          from arrecad_parc_rec
               left join arrematric on arrematric.k00_numpre = arrecad_parc_rec.numpre
               left join arreinscr  on arreinscr.k00_numpre  = arrecad_parc_rec.numpre
         where arrematric.k00_numpre is null
           and arreinscr.k00_numpre  is null;
        if sNumpreSemVinculoMatricInsc <> '' then
          return '[15] - Inconsistencia no percentual do débito gerado após o processamento do parcelamento - numpre: '||v_numpre||'. - Encontrados numpres que não possuem vinculo com Matricula/Inscrição. Numpres ['||sNumpreSemVinculoMatricInsc||']';
        else
          return '[15] - Inconsistencia no percentual do débito gerado após o processamento do parcelamento - numpre: '||v_numpre;
        end if;

    end if;

    if lRaise is true then
      perform fc_debug('',lRaise,false,false);
    end if;

    -- insere registros na arreparc
    -- agrupados por receita
    for v_record_receitas in  select receit,
                                     sum(vlrhis) as vlrhis,
                                     sum(vlrcor) as vlrcor,
                                     sum(vlrjur) as vlrjur,
                                     sum(vlrmul) as vlrmul,
                                     sum(vlrdes) as vlrdes,
                                     sum(valor)  as valor
                                from arrecad_parc_rec
                               group by receit
    loop

      if lRaise is true then
        perform fc_debug('receita: '||v_record_receitas.receit||' - valor: '||v_record_receitas.valor, lRaise,false,false);
      end if;

      insert into arreparc values (v_numpre,v_record_receitas.receit,v_record_receitas.valor / v_total * 100);

      nVlrHis := nVlrHis + v_record_receitas.vlrhis;
      nVlrCor := nVlrCor + v_record_receitas.vlrcor;
      nVlrJur := nVlrJur + v_record_receitas.vlrjur;
      nVlrMul := nVlrMul + v_record_receitas.vlrmul;
      nVlrDes := nVlrDes + v_record_receitas.vlrdes;

    end loop;

    if lRaise is true then
      perform fc_debug('',lRaise,false,false);
    end if;

    -- insere na termo
    insert into termo ( v07_parcel,
                        v07_dtlanc,
                        v07_valor,
                        v07_numpre,
                        v07_totpar,
                        v07_vlrpar,
                        v07_dtvenc,
                        v07_vlrent,
                        v07_datpri,
                        v07_vlrmul,
                        v07_vlrjur,
                        v07_perjur,
                        v07_permul,
                        v07_login,
                        v07_numcgm,
                        v07_hist,
                        v07_ultpar,
                        v07_desconto,
                        v07_desccor,
                        v07_descjur,
                        v07_descmul,
                        v07_situacao,
                        v07_instit,
                        v07_vlrhis,
                        v07_vlrcor,
                        v07_vlrdes )
               values ( v_termo,
                        dDataUsu,
                        v_total,
                        v_numpre,
                        v_totalparcelas,
                        v_valorparcelanew,
                        v_segvenc,
                        v_entrada,
                        v_privenc,
                        nVlrMul,
                        nVlrJur,
                        0,
                        0,
                        v_login,
                        v_cgmresp,
                        sObservacao,
                        v_valultimaparcelanew,
                        v_desconto,
                        v_descontocor,
                        v_descontojur,
                        v_descontomul,
                        1, -- Situacao Ativo
                        iInstit,
                        nVlrHis,
                        nVlrCor,
                        nVlrDes );

    -- se foi informado codigo do processo entao insere na termoprotprocesso
    if iProcesso is not null and iProcesso != 0  then

      if lRaise is true then
        perform fc_debug(' Insere na protprocesso  Processo : '||iProcesso,lRaise,false,false);
      end if;

      insert into termoprotprocesso (v27_sequencial,
                                     v27_termo,
                                     v27_protprocesso)
                             values (nextval('termoprotprocesso_v27_sequencial_seq'),
                                     v_termo,
                                     iProcesso);
    end if;

    -- se origem tiver parcelamento
    -- insere na termoreparc
    if lParcParc then
      if lRaise is true then
        perform fc_debug('v08_parcel: '||v_termo||' - v08_parcelorigem: '||v_termo_ori,lRaise,false,false);
      end if;

      for v_record_origem in select distinct v07_parcel
                               from termo
                                    inner join numpres_parc on termo.v07_numpre = numpres_parc.k00_numpre
      loop

        if lRaise is true then
          perform fc_debug('into termoreparc...',lRaise,false,false);
        end if;

        insert into termoreparc (v08_sequencial,
                                 v08_parcel,
                                 v08_parcelorigem)
                         values (nextval('termoreparc_v08_sequencial_seq'),
                                 v_termo,
                                 v_record_origem.v07_parcel);

      end loop;

    end if;

    if lRaise is true then
      perform fc_debug('v_totaldivida: '||v_totaldivida,lRaise,false,false);
    end if;

    /**
     * Deve ignorar a receita de juros e multa quando a forma utilizada no parcelamento
     * é juros e multa na ultima
     * retirado arredondamentos para reconstruir a termo div com os debitos tal qual estavam anteriormente
     */
    -- insere na termodiv (obs o select da arrecad_parc_rec da um inner join com a divida so para inserir na termodiv quando a origem for divida)
    if v_juronaultima is true then

      insert into termodiv (parcel,
                            coddiv,
                            valor,
                            vlrcor,
                            juros,
                            multa,
                            desconto,
                            total,
                            vlrdesccor,
                            vlrdescjur,
                            vlrdescmul,
                            numpreant,
                            v77_perc)
                     select x.*,
                            x.valor / v_totaldivida * 100
                       from ( select v_termo,
                                     v01_coddiv,
                                     sum(vlrhis)::numeric     as vlrhis,
                                     sum(vlrcor)::numeric     as vlrcor,
                                     sum(vlrjur)::numeric     as vlrjur,
                                     sum(vlrmul)::numeric     as vlrmul,
                                     sum(vlrdes)::numeric     as vlrdes,
                                     sum(valor)::numeric      as valor,
                                     sum(vlrdesccor)::numeric as vlrdesccor,
                                     sum(vlrdescjur)::numeric as vlrdescjur,
                                     sum(vlrdescmul)::numeric as vlrdescmul,
                                     divida.v01_numpre
                                from arrecad_parc_rec
                                     inner join divida on divida.v01_numpre = arrecad_parc_rec.numpre
                                                      and divida.v01_numpar = arrecad_parc_rec.numpar
                               where tipo = 5
                                 and receit not in ( select distinct receit from arrecad_parc_rec where juro is true )
                            group by v01_coddiv, v01_numpre ) as x;
    else

      insert into termodiv (parcel,
                           coddiv,
                           valor,
                           vlrcor,
                           juros,
                           multa,
                           desconto,
                           total,
                           vlrdesccor,
                           vlrdescjur,
                           vlrdescmul,
                           numpreant,
                           v77_perc)
                    select x.*,
                           x.valor / v_totaldivida * 100
                      from ( select v_termo,
                                    v01_coddiv,
                                    sum(vlrhis)::numeric     as vlrhis,
                                    sum(vlrcor)::numeric     as vlrcor,
                                    sum(vlrjur)::numeric     as vlrjur,
                                    sum(vlrmul)::numeric     as vlrmul,
                                    sum(vlrdes)::numeric     as vlrdes,
                                    sum(valor)::numeric      as valor,
                                    sum(vlrdesccor)::numeric as vlrdesccor,
                                    sum(vlrdescjur)::numeric as vlrdescjur,
                                    sum(vlrdescmul)::numeric as vlrdescmul,
                                    divida.v01_numpre
                               from arrecad_parc_rec
                                    inner join divida on divida.v01_numpre = arrecad_parc_rec.numpre
                                                     and divida.v01_numpar = arrecad_parc_rec.numpar
                              where tipo = 5
                           group by v01_coddiv, v01_numpre ) as x;
    end if;

    -- mostra os valores com origem de divida ativa
    if lRaise is true then

      for v_record_numpres in select *
                                from termodiv
                               where parcel = v_termo
      loop

         perform fc_debug('coddiv: '||v_record_numpres.coddiv||' - vlcor: '||v_record_numpres.vlrcor||' - total: '||v_record_numpres.total||' - juro: '||v_record_numpres.juros||' - multa: '||v_record_numpres.multa,lRaise,false,false);

      end loop;

    end if;

    -- SE ORIGEM FOR DIVERSOS
    if lParcDiversos then

      if lRaise is true then
        perform fc_debug('inserindo em termodiver...',lRaise,false,false);
      end if;

      -- insere na termodiver
      insert into termodiver (dv10_parcel,
                              dv10_coddiver,
                              dv10_valor,
                              dv10_vlrcor,
                              dv10_juros,
                              dv10_multa,
                              dv10_desconto,
                              dv10_total,
                              dv10_numpreant,
                              dv10_vlrdescjur,
                              dv10_vlrdescmul,
                              dv10_perc)
                       select x.*,
                              x.valor/v_total
                         from ( select v_termo,
                                       dv05_coddiver,
                                       sum(vlrhis)::numeric as vlrhis,
                                       sum(vlrcor)::numeric as vlrcor,
                                       sum(vlrjur)::numeric as vlrjur,
                                       sum(vlrmul)::numeric as vlrmul,
                                       sum(vlrdes)::numeric as vlrdes,
                                       sum(valor)::numeric  as valor,
                                       diversos.dv05_numpre,
                                       sum(vlrdescjur)::numeric as vlrdescjur,
                                       sum(vlrdescmul)::numeric as vlrdescmul
                                  from arrecad_parc_rec
                                       inner join diversos on diversos.dv05_numpre = arrecad_parc_rec.numpre
                                 group by dv05_coddiver, dv05_numpre
                              ) as x;
    end if;

    -- SE ORIGEM FOR CONTRIBUICAO DE MELHORIAS
    if lParcContrib then

      if lRaise is true then
        perform fc_debug('inserindo em termodiver...',lRaise,false,false);
      end if;

      -- insere na termodiver
      insert into termocontrib (parcel,
                                contricalc,
                                valor,
                                vlrcor,
                                juros,
                                multa,
                                desconto,
                                total,
                                numpreant,
                                vlrdescjur,
                                vlrdescmul,
                                perc)
                         select x.*,
                                x.valor/v_total
                           from ( select v_termo,
                                         d09_sequencial,
                                         sum(vlrhis)::numeric as vlrhis,
                                         sum(vlrcor)::numeric as vlrcor,
                                         sum(vlrjur)::numeric as vlrjur,
                                         sum(vlrmul)::numeric as vlrmul,
                                         sum(vlrdes)::numeric as vlrdes,
                                         sum(valor)::numeric  as valor,
                                         contricalc.d09_numpre,
                                         sum(vlrdescjur)::numeric as vlrdescjur,
                                         sum(vlrdescmul)::numeric as vlrdescmul
                                    from arrecad_parc_rec
                                         inner join contricalc on contricalc.d09_numpre = arrecad_parc_rec.numpre
                                   group by d09_sequencial,d09_numpre
                                ) as x;
    end if;

    if lRaise is true then
      perform fc_debug('v_parcinicial: '||v_parcinicial,lRaise,false,false);
    end if;

    -- SE ORIGEM FOR INICIAL DO FORO
    if v_parcinicial is true then

      if lRaise is true then
         perform fc_debug('inserindo em termoini...',lRaise,false,false);
      end if;

      -- insere na termoini
      insert into termoini(parcel,
                           inicial,
                           valor,
                           vlrcor,
                           juros,
                           multa,
                           desconto,
                           total,
                           vlrdesccor,
                           vlrdescjur,
                           vlrdescmul,
                           v61_perc)
                    select x.*,
                           x.valor/v_total
                      from ( select v_termo,
                                    inicialnumpre.v59_inicial,
                                    round(sum(vlrhis),2)::float8 as vlrhis,
                                    round(sum(vlrcor),2)::float8 as vlrcor,
                                    round(sum(vlrjur),2)::float8 as vlrjur,
                                    round(sum(vlrmul),2)::float8 as vlrmul,
                                    round(sum(vlrdes),2)::float8 as vlrdes,
                                    round(sum(valor),2)::float8 as valor,
                                    round(sum(vlrdesccor),2)::float8 as vlrdesccor,
                                    round(sum(vlrdescjur),2)::float8 as vlrdescjur,
                                    round(sum(vlrdescmul),2)::float8 as vlrdescmul
                               from arrecad_parc_rec
                                    inner join inicialnumpre on inicialnumpre.v59_numpre = arrecad_parc_rec.numpre
                              group by inicialnumpre.v59_inicial
                           ) as x;

      for v_iniciais in select distinct v59_inicial
                                   from arrecad_parc_rec
                                 inner join inicialnumpre on inicialnumpre.v59_numpre = arrecad_parc_rec.numpre
                                 inner join inicial       on inicial.v50_inicial      = inicialnumpre.v59_inicial
                                                         and inicial.v50_situacao     = 1
      loop

        select nextval('inicialmov_v56_codmov_seq') into v_inicialmov;

        insert into inicialmov values (v_inicialmov,v_iniciais.v59_inicial,4,'',dDataUsu,v_login);
        update inicial set v50_codmov = v_inicialmov where v50_inicial = v_iniciais.v59_inicial;

      end loop;

    end if;

    -- Deletando os registros do arreold que estao incorretamente devido a bug
    -- da versao antiga da funcao fc_excluiparcelamento

    delete from arreold
          using arrecad_parc_rec
          where arreold.k00_numpre = arrecad_parc_rec.numpre
            and arreold.k00_numpar = arrecad_parc_rec.numpar
            and arreold.k00_receit = arrecad_parc_rec.receit;

    -- insere no arreold

    insert into arreold(k00_numcgm,
                        k00_dtoper,
                        k00_receit,
                        k00_hist,
                        k00_valor,
                        k00_dtvenc,
                        k00_numpre,
                        k00_numpar,
                        k00_numtot,
                        k00_numdig,
                        k00_tipo,
                        k00_tipojm)
                 select arrecad.k00_numcgm,
                        arrecad.k00_dtoper,
                        arrecad.k00_receit,
                        arrecad.k00_hist,
                        arrecad.k00_valor,
                        arrecad.k00_dtvenc,
                        arrecad.k00_numpre,
                        arrecad.k00_numpar,
                        arrecad.k00_numtot,
                        arrecad.k00_numdig,
                        arrecad.k00_tipo,
                        arrecad.k00_tipojm
                   from arrecad
                  inner join arrecad_parc_rec on arrecad.k00_numpre = arrecad_parc_rec.numpre
                                             and arrecad.k00_numpar = arrecad_parc_rec.numpar
                                             and arrecad.k00_receit = arrecad_parc_rec.receit
                  left join arreold           on arreold.k00_numpre = arrecad_parc_rec.numpre
                                             and arreold.k00_numpar = arrecad_parc_rec.numpar
                                             and arreold.k00_receit = arrecad_parc_rec.receit
                 where arreold.k00_numpre is null
                   and arrecad.k00_valor > 0;

    delete from arrecad
          using arrecad_parc_rec
          where arrecad.k00_numpre = arrecad_parc_rec.numpre
            and arrecad.k00_numpar = arrecad_parc_rec.numpar
            and arrecad.k00_receit = arrecad_parc_rec.receit;

    -- conta a quantidade de registros do arrecad
    select count(*)
      from arrecad
      into v_contador
     where k00_numpre = v_numpre;

    if lRaise is true then
      perform fc_debug('total final de registros no arrecad: '||v_contador,lRaise,false,false);
    end if;

    -- soma o valor gravado no arrecad
    if lSeparaJuroMulta = 2 then

      select round(sum(k00_valor),2)
        into v_resto
        from arrecad
       where k00_numpre = v_numpre;

    else

      select round(sum(arrecad.k00_valor)+coalesce(sum(arrecadcompos.k00_correcao),0) + coalesce(sum(arrecadcompos.k00_juros),0) + coalesce(sum(arrecadcompos.k00_multa),0) ,2)
        into v_resto
        from arrecad
             left  join arreckey      on arreckey.k00_numpre = arrecad.k00_numpre
                                     and arreckey.k00_numpar = arrecad.k00_numpar
                                     and arreckey.k00_receit = arrecad.k00_receit
                                     and arreckey.k00_hist   = arrecad.k00_hist
             left  join arrecadcompos on arrecadcompos.k00_arreckey = arreckey.k00_sequencial
       where arrecad.k00_numpre = v_numpre;

    end if;

    if lRaise is true then
      perform fc_debug('Total do arrecad (v_resto): '||v_resto||' - v_total: '||v_total,lRaise,false,false);
    end if;

    -- registra a diferenca do valor gravado no arrecad e do total do parcelamento calculado durante o processamento
    v_teste = round(v_total,2) - round(v_resto,2);

    if lRaise is true then

      perform fc_debug('v_teste: '||v_teste,lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug('ACERTAR DIFERENCA',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);

    end if;


    select db21_codcli
      into iCodcli
      from db_config
    where prefeitura is true;

    -- Alterados valores para Capivari, para conseguir analisar melhor os impactos de como resolver o problema da
    -- diferença de valor, sem que seja adicionada a diferença há última parecela.
    --CAPIVARI
    if ( iCodcli = 26 ) then

      nFaixaInicial := -0.01;
      nFaixaFinal := 0.5;

    end if;

    -- Alterado a lógica do v_teste para lançar na última parcela a diferença do valor gravado no arrecad e do total do parcelamento calculado durante o processamento, caso v_teste esteja entre 0.01 e 6.00.
    -- Não se sabe o porquê dos valores descritos, anteriormente já havia lógica semelhante, apenas alteramos o between.
    if abs(v_teste) between nFaixaInicial and nFaixaFinal and v_juronaultima is false then
      if lRaise is true then
        perform fc_debug('entrou no 0.01 - diferenca: '||v_teste,lRaise,false,false);
      end if;

      select k00_receit
        into v_maxrec
        from arrecad
       where k00_numpre = v_numpre
         and k00_numpar = v_totalparcelas
       order by k00_valor desc limit 1;

      update arrecad
         set k00_valor  = k00_valor + v_teste
       where k00_numpre = v_numpre
         and k00_numpar = v_totalparcelas
         and k00_receit = v_maxrec;

    end if;

    -- se juros na ultima
    if v_juronaultima is true then

      select k00_receit
        into v_receita
        from arrecad
       where k00_numpre = v_numpre
         and k00_numpar = v_totalparcelas - 1
       limit 1;

      if round(v_total,2) <> round(v_resto,2) then

        if lRaise is true then
          perform fc_debug('update: '||(round(v_total,2) - round(v_resto,2)),lRaise,false,false);
        end if;

        -- altera o valor da penultima parcela com a diferenca
        update arrecad
           set k00_valor = k00_valor + round(round(v_total,2) - round(v_resto,2),2)
        where k00_numpre = v_numpre
          and k00_numpar = v_totalparcelas - 1
          and k00_receit = v_receita;

      end if;

    end if;

    -- funcao que corrige o arrecad no caso de encontrar registros duplicados(numpre,numpar,receit)
    -- perform fc_corrigeparcelamento();

    if lSeparaJuroMulta = 2 then

      select round(sum(k00_valor),2)
        into v_resto
        from arrecad
       where k00_numpre = v_numpre;

    else

      select round(sum(arrecad.k00_valor)+coalesce(sum(arrecadcompos.k00_correcao),0) + coalesce(sum(arrecadcompos.k00_juros),0) + coalesce(sum(arrecadcompos.k00_multa),0) ,2)
        into v_resto
        from arrecad
             left  join arreckey      on arreckey.k00_numpre = arrecad.k00_numpre
                                     and arreckey.k00_numpar = arrecad.k00_numpar
                                     and arreckey.k00_receit = arrecad.k00_receit
                                     and arreckey.k00_hist   = arrecad.k00_hist
             left  join arrecadcompos on arrecadcompos.k00_arreckey = arreckey.k00_sequencial
       where arrecad.k00_numpre = v_numpre;
    end if;

    if lRaise is true then
      perform fc_debug('total do arrecad (v_resto): '||v_resto||' - v_total: '||v_total||' - totparc: '||v_totparc,lRaise,false,false);
    end if;

    for v_record_recpar in select k00_receit,
                                  sum(k00_valor)
                             from arrecad
                            where k00_numpre = v_numpre
                            group by k00_receit
    loop

      if lRaise is true then
        perform fc_debug('receita: '||v_record_recpar.k00_receit||' - valor: '||v_record_recpar.sum,lRaise,false,false);
      end if;

    end loop;

    -- se total do arrecad for diferenca do total calculado durante o processamento
    -- mostra mensagem de erro
    if lRaise then
      perform fc_debug('Parcelamento : '||v_termo||' Numpre : '||v_numpre||' Total: '||v_total||' - Resto: '||v_resto||' Diferenca: '||(round(v_total,2) - round(v_resto,2)),lRaise,false,false);
      raise notice '%',fc_debug('Fim do Processamento...',lRaise,false,true);
    end if;

    -- raise info 'Valor total: %', v_total;
    -- raise info 'Valor resto: %', v_resto;

    if round(v_total, 2) <> round(v_resto, 2) then
      return '[16] - total gerado da soma das parcelas inconsistente!';
    end if;

    return '1 - Parcelamento efetuado com sucesso - Termo Gerado: '||v_termo||' - Numpre: '||v_numpre;

  end;

$$ language 'plpgsql';
SQL;

        $this->execute($sSql);
    }

    public function down()
    {
        $sSql =
<<<SQL
       -- quando reparcelar um parcelamento que e de 3 matriculas, tem que gerar 3 arrematric do novo numpre
-- revisar bem parcelamento de diversos e melhorias
-- considera-se que nao pode se parcelar inicial com outro tipo de debito, mesmo que seja outro parcelamento de inicial,
-- mas podemos parcelar mais de uma inicial no mesmo parcelamento, por isso que existe a tabela termoini

-- tipos de testes
-- parcelar um diversos
-- reparcelar um diversos
-- parcelar 2 diversos, um de cada procedencia
-- parcelar 1 diversos e um parcelamento de diversos

set client_encoding = 'LATIN1';

drop function if exists fc_parcelamento(integer,date,date,integer,integer,float8,integer,integer,integer,integer,float8,float8);

set check_function_bodies to on;
create or replace function fc_parcelamento(integer,date,date,integer,integer,float8,integer,integer,integer,integer,float8,float8,text,integer)
returns varchar(100)
as $$
declare

  v_cgmresp                        alias for $1;  -- cgm do responsavel pelo parcelamento
  v_privenc                        alias for $2;  -- vencimento da entrada
  v_segvenc                        alias for $3;  -- vencimento da parcela 2
  v_diaprox                        alias for $4;  -- dia de vencimento da parcela 3 em diante
  v_totparc                        alias for $5;  -- total de parcelas
  v_entrada                        alias for $6;  -- valor da entrada
  v_login                          alias for $7;  -- login de quem fez o parcelamento
  v_cadtipo                        alias for $8;  -- tipo de debito dos registros selecionados
  v_desconto                       alias for $9;  -- regra de parcelamento utilizada
  v_temdesconto                    alias for $10; -- se tem desconto (nao utilizada)
  v_valorparcela                   alias for $11; -- valor de cada parcela
  v_valultimaparcela               alias for $12; -- valor da ultima parcela

  sObservacao                      alias for $13; -- observacao do parcelamento
  iProcesso                        alias for $14; -- codigo do processo (protprocesso)

  v_ultparc                        integer default 2;
  v_matric                         integer default 0;
  v_inscr                          integer default 0;

  iUltMatric                       integer;
  iUltNumpre                       integer;
  iUltNumpar                       integer;
  iUltReceit                       integer;

  iSeqArrecKey                     integer;
  iSeqArrecadcompos                integer;

  v_anousu                         integer;
  v_totpar                         integer;
  v_cgmpri                         integer;
  v_somar1                         integer;
  v_somar2                         integer;
  v_numpre                         integer;
  v_receita                        integer;
  v_termo                          integer;
  v_termo_ori                      integer;
  v_tipo                           integer;
  v_tiponovo                       integer;
  v_quantparcel                    integer;
  v_var                            integer;
  v_inicialmov                     integer;
  v_totparcdestarec                integer;
  v_contador                       integer;
  v_cadtipoparc                    integer;
  v_recdestino                     integer;
  v_dia                            integer;
  v_ultdiafev                      integer;
  v_maxrec                         integer;
  v_anovenc                        integer;
  v_mesvenc                        integer;
  v_totalparcelas                  integer;
  v_anovencprox                    integer;
  v_mesvencprox                    integer;
  v_recjurosultima                 integer;
  v_recmultaultima                 integer;
  v_histjuro                       integer;
  v_proxmessegvenc                 integer;
  iInstit                          integer;
  iAnousu                          integer;
  iQtdRegistrosMatricula           integer;
  iQtdRegistrosInscricao           integer;

  v_totaldivida                    numeric default 0;
  v_somar                          numeric default 0;
  v_totalliquido                   numeric default 0;
  v_total_liquido                  numeric default 0;
  v_totalzao                       numeric default 0;

  v_calcula_valprop                numeric(15,10);
  v_calcula_valor                  float8 default 0;
  v_calcula_his                    numeric(15,2);
  v_calcula_cor                    numeric(15,2);
  v_calcula_jur                    numeric(15,2);
  v_calcula_mul                    numeric(15,2);
  v_calcula_desccor                numeric(15,2);
  v_calcula_descjur                numeric(15,2);
  v_calcula_descmul                numeric(15,2);

  nValidacaoPerc                   numeric(15,10);
  nPercentualVirtualCgm            numeric(15,10);
  nDiferencaPercentualCGM          numeric(15,10) default 0;
  nDiferencaPercentualAjuste       numeric(15,10);

  v_descontocor                    float8 default 0;
  v_tipodescontocor                integer default 0;
  v_descontojur                    float8 default 0;
  v_descontomul                    float8 default 0;
  v_total                          float8;
  v_totalcomjuro                   float8;
  v_valparc                        float8;
  v_diferencanaultima              float8;

  v_valorinserir                   float8;
  v_ent_prop                       float8;
  v_vlrateagora                    float8;
  v_totateagora                    float8;
  v_resto                          float8 default 0;
  v_teste                          float8;
  v_saldo                          float8;
  v_calcular                       float8;
  v_valorparcelanew                float8;
  v_valultimaparcelanew            float8;

  v_valdesccor                     float8;
  v_valdescjur                     float8;
  v_valdescmul                     float8;

  nValorTotalOrigem                float8;
  nPercCalc                        float8;
  nSomaPercMatric                  float8;
  nSomaPercInscr                   float8;
  nTotArreMatric                   float8;
  nTotArreInscr                    float8;

  nVlrHis                          numeric default 0;
  nVlrCor                          numeric default 0;
  nVlrJur                          numeric default 0;
  nVlrMul                          numeric default 0;
  nVlrDes                          numeric default 0;
  nPercMatric                      numeric default 0;
  nPercInscr                       numeric default 0;
  nPercCGM                         numeric default 0;
  lIncluiEmParcelas                boolean default false;

  nVlrTotalHistorico               numeric default 0;
  nVlrTotalCorrecao                numeric default 0;
  nVlrTotalJuros                   numeric default 0;
  nVlrTotalMulta                   numeric default 0;

  v_historico_compos               float8 default 0;
  v_correcao_compos                float8 default 0;
  v_juros_compos                   float8 default 0;
  v_multa_compos                   float8 default 0;

  nVlrHistoricoComposicao          numeric(15,2) default 0;
  nVlrCorrecaoComposicao           numeric(15,2) default 0;
  nVlrJurosComposicao              numeric(15,2) default 0;
  nVlrMultaComposicao              numeric(15,2) default 0;
  nVlrTotalParcelamento            numeric(15,2) default 0;
  nVlrTotalComposicao              numeric(15,2) default 0;
  nVlrDiferencaComposicaoTotal     numeric(15,2) default 0;

  nVlrTotalParcelamentoHistorico   numeric(15,2) default 0;
  nVlrTotalParcelamentoCorrigido   numeric(15,2) default 0;
  nVlrTotalParcelamentoJuros       numeric(15,2) default 0;
  nVlrTotalParcelamentoMulta       numeric(15,2) default 0;
  nVlrTotalDescontoCorrigido       numeric(15,2) default 0;
  nVlrTotalDescontoJuros           numeric(15,2) default 0;
  nVlrTotalDescontoMulta           numeric(15,2) default 0;

  nVlrDiferencaComposicaoHistorico numeric(15,2) default 0;
  nVlrDiferencaComposicaoCorrecao  numeric(15,2) default 0;
  nVlrDiferencaComposicaoJuros     numeric(15,2) default 0;
  nVlrDiferencaComposicaoMulta     numeric(15,2) default 0;

  v_ultdiafev_d                    date;
  v_vcto                           date;
  dDataUsu                         date;

  sArreoldJuncao                   varchar default '';
  v_proxmessegvenc_c               varchar(2);
  v_ultdiafev_c                    varchar(10);
  sStringUpdate                    varchar;
  sNumpreSemVinculoMatricInsc      text;

  v_comando                        text;
  v_comando_cria                   text;

  v_iniciais                       record;
  v_record_perc                    record;
  v_record_numpres                 record;
  v_record_numpar                  record;
  v_record_receitas                record;
  v_record_recpar                  record;
  v_record_origem                  record;
  v_record_desconto                record;
  rPercOrigem                      record;
  rSeparaJurMul                    record;
  rAjusteDiferencaPercentual       record;

  lTabelasCriadas                  boolean;
  v_parcnormal                     boolean default false; -- se tem divida ativa selecionada
  v_parcinicial                    boolean default false; -- se tem inicial selecionada
  lParcDiversos                    boolean default false; -- se tem diversos selecionado
  lParcContrib                     boolean default false; -- se tem contribuicao de melhoria selecionado
  lParcParc                        boolean default false; -- se tem parcelamento selecionado (caso esteja efetuando um reparcelamento)
  v_juronaultima                   boolean default false;
  v_descontar                      boolean default false;
  lSeparaJuroMulta                 integer default 2;
  lGravaArrecad                    boolean default true;
  lParcelaZerada                   boolean default false;
  lValidaParcInicial               boolean default false;

  lRaise                           boolean default false;

  begin

    -- valores retornados:
    -- 1 = ok
    -- 2 = tentando parcelar mais de um tipo (k03_tipo) de debito
    -- 3 = tipo de debito nao configurado para parcelamento
    -- 4 = parcelamento nao encontrado pelo numpre
    -- 5 = tentando reparcelar mais de um parcelamento
    -- 6 = tentando parcelar mais de um numpre (debito)

    lRaise  := ( case when fc_getsession('DB_debugon') is null or fc_getsession('DB_debugon') = '' then false else true end );
    if lRaise is true then
      perform fc_debug('Processando parcelamento dos débitos...',lRaise,true,false);
    end if;

    v_totalparcelas       = v_totparc;
    v_valorparcelanew     = v_valorparcela;
    v_valultimaparcelanew = v_valultimaparcela;

    iInstit := cast(fc_getsession('DB_instit') as integer);
    if iInstit is null then
       raise exception 'Variavel de sessão [DB_instit] não encontrada.';
    end if;

    iAnousu := cast(fc_getsession('DB_anousu') as integer);
    if iAnousu is null then
       raise exception 'Variavel de sessão [DB_anousu] não encontrada.';
    end if;

    dDataUsu := cast(fc_getsession('DB_datausu') as date);
    if dDataUsu is null then
       raise exception 'Variavel de sessão [DB_datausu] não encontrada.';
    end if;

    select k03_separajurmulparc
      into lSeparaJuroMulta
      from numpref
     where k03_instit = iInstit
       and k03_anousu = iAnousu;

    --lSeparaJuroMulta = false;

    -- testa se existe algum tipo de parcelamento configurado
    select count(*)
      from tipoparc
     where instit = iInstit
      into v_contador;

    if v_contador is null then
      return '[0] - Sem configuracao na tabela tipoparc para instituicao %', iInstit;
    end if;

    if lRaise is true then
      perform fc_debug('verificando se tem mais de um tipo de debito...',lRaise,false,false);
    end if;

    -- existe uma tabela temporaria chamada totalportipo, criada antes de chamar a funcao de parcelamento
    -- que contem os valores a parcelar agrupada por tipo de debito
    -- nessa tabela existe a informacao se o tipo de debito tem direito a desconto ou nao

    -- a tabela numpres_parc contem os registros marcados na CGF pelo usuario
    -- cria indice na tabela utilizada durante os parcelamentos
    create index numpres_parc_in on numpres_parc using btree (k00_numpre, k00_numpar);

    -- for buscando as origens de cada debito selecionado para parcelar(numpres_parc)
    for v_record_origem in  select arretipo.k03_tipo,
                                   arrecad.k00_numpre,
                                   count(*)
                              from numpres_parc
                             inner join arrecad  on arrecad.k00_numpre = numpres_parc.k00_numpre
                             inner join arretipo on arretipo.k00_tipo  = arrecad.k00_tipo
                             group by arretipo.k03_tipo,
                                      arrecad.k00_numpre
    loop

      -- se origem(k03_tipo) for
      if v_record_origem.k03_tipo = 5 then

        -- 5 divida ativa
        v_parcnormal = true;

      elsif v_record_origem.k03_tipo = 18 then

        -- inicial do foro
        v_parcinicial = true;
        lValidaParcInicial = true;

      elsif v_record_origem.k03_tipo = 4 then

        -- contribuicao de melhoria
        lParcContrib = true;

      elsif v_record_origem.k03_tipo = 7 then

        -- diversos
        lParcDiversos = true;

      elsif v_record_origem.k03_tipo in (6,13,16,17) then

        -- reparcelamentos
        -- 6   parcelamento de divida
        -- 13  parcelamento de inicial de divida
        -- 16  parcelamento de diveros
        -- 17  parcelamento de contribuicao de melhoria
        lParcParc = true;

        if v_record_origem.k03_tipo = 13 then
          lValidaParcInicial = true;
        end if;

      end if;

      if lRaise is true then
        perform fc_debug('k00_tipo: '||v_record_origem.k03_tipo||'k00_numpre:'||v_record_origem.k00_numpre,lRaise,false,false);
      end if;

    end loop;

    if v_parcnormal is true and v_parcinicial is true then
      return '[1] - Nao pode ser parcela divida normal com ajuizada!';
    end if;

    -- se houver débitos do tipo Inicial verificamos se o parâmetro do Mód. Juridio PARTILHA está ativo "SIM" e
    -- caso exista mais de um processo para as iniciais bloqueamos o parcelamento.
    if lValidaParcInicial is true then

       perform v19_partilha
         from parjuridico
        where v19_anousu = iAnousu
          and v19_instit = iInstit
          and v19_partilha is true;
       if found then

          perform count( distinct case
                                    when processoinicial.v71_processoforo is null
                                      then processoparcel.v71_processoforo
                                    else processoinicial.v71_processoforo end )
             from NUMPRES_PARC
                  left join inicialnumpre                          on inicialnumpre.v59_numpre        = NUMPRES_PARC.k00_numpre
                  left join processoforoinicial as processoinicial on processoinicial.v71_inicial     = inicialnumpre.v59_inicial
                  left join termo                                  on termo.v07_numpre                = NUMPRES_PARC.k00_numpre
                  left join termoini                               on termoini.parcel                 = termo.v07_parcel
                  left join processoforoinicial as processoparcel  on processoparcel.v71_inicial      = termoini.inicial
           having count(distinct case
                                   when processoinicial.v71_processoforo is null
                                     then processoparcel.v71_processoforo
                                   else processoinicial.v71_processoforo end) > 1;
          if found then
            return '[2] - Não é possível parcelar iniciais com processos do foro diferentes para um mesmo parcelamento! [Utilização de Partilha Ativada]';
          end if;

       end if;

    end if;

    if lRaise is true then
      perform fc_debug('guardando o tipo de debito...',lRaise,false,false);
    end if;

    v_tipo = v_cadtipo;

    if lRaise is true then
      perform fc_debug('guardando o tipo de debito...',lRaise,false,false);
    end if;

    -- select na termoconfigo para descobrir qual o tipo de debito
    -- que vai ser gerado com o debito do novo parcelamento
    -- tabela termotipoconfig tem o tipo de debito dos grupos de debitos
    -- que e possivel parcelar

    if lRaise is true then
      perform fc_debug('instit -- '||iInstit,lRaise,false,false);
    end if;

    select k42_tiponovo
      into v_tiponovo
      from termotipoconfig
     where k42_cadtipo = v_tipo
       and k42_instit  = iInstit;
    if not found then
      return '[3] - Este tipo de debito nao esta configurado para parcelamento';
    end if;

    if lRaise is true then
      perform fc_debug('tipo novo:'||v_tiponovo,lRaise,false,false);
    end if;

    -- cria tabela temporarias para utilizacao durante o calculo
    if lRaise is true then
       perform fc_debug('',lRaise,false,false);
       perform fc_debug('+--------------------------------------------------------------------------------------------------',lRaise,false,false);
       perform fc_debug('| ',lRaise,false,false);
       perform fc_debug('| CRIANDO TABELAS TEMPORARIAS PARA O PROCESSAMENTO DO PARCELAMENTO ',lRaise,false,false);
       perform fc_debug('| ',lRaise,false,false);
       perform fc_debug('+--------------------------------------------------------------------------------------------------',lRaise,false,false);
       perform fc_debug('',lRaise,false,false);
    end if;
    select fc_parc_criatemptable(lRaise)
      into lTabelasCriadas;
    if lTabelasCriadas is false then
      return '[4] - Problema ao criar as tabelas temporarias. ';
    end if;


    -- Desativado parâmetro para que não seja gerado registros na incorporação tributária
    perform fc_putsession('DB_utiliza_incorporacao','false');

    -- funcao que corrige o arrecad no caso de encontrar registros duplicados(numpre,numpar,receit)
    perform fc_corrigeparcelamento();

    -- Ativado parâmetro para que continue sendo gerado registros na incorporação tributária
    perform fc_putsession('DB_utiliza_incorporacao','true');

    -- testa se todas as parcelas do parcelamento foram marcadas,
    -- senao nao permite parcelar apenas algumas parcelas do parcelamento
    -- ou seja, ou parcela todas as parcelas do parcelamento, ou nada
    for v_record_origem in select distinct
                                  termo.v07_parcel
                             from numpres_parc
                            inner join termo on termo.v07_numpre = numpres_parc.k00_numpre
                            where k03_tipodebito <> 18
    loop

      -- soma a quantidade de parcelas do parcelamento
      select count(distinct arrecad.k00_numpar)
        into v_somar1
        from arrecad
       inner join termo on termo.v07_parcel = v_record_origem.v07_parcel
       where arrecad.k00_numpre = termo.v07_numpre;

      if lRaise is true then
        perform fc_debug('v_record_origem.v07_parcel: '||v_record_origem.v07_parcel,lRaise,false,false);
      end if;

      -- testa a quantidade de parcelas marcadas
      select count(distinct numpres_parc.k00_numpar)
        into v_somar2
        from numpres_parc
       inner join termo on termo.v07_parcel = v_record_origem.v07_parcel
       where numpres_parc.k00_numpre = termo.v07_numpre;

      if lRaise is true then
        perform fc_debug('Verificando quantidades de parcelaas marcadas com a quantidade de parcelas do débito: v_somar1: '||v_somar1||' - v_somar2: '||v_somar2,lRaise,false,false);
      end if;

      -- compara
      if v_somar1 <> v_somar2 then
        return '[5] - Todas as parcelas do parcelamento ' || v_record_origem.v07_parcel || ' devem ser marcadas!';
      end if;

    end loop;

    if lRaise is true then
      perform fc_debug('entrada'||v_entrada,lRaise,false,false);
      perform fc_debug('valor das parcelas:'||v_valorparcelanew,lRaise,false,false);
      perform fc_debug('valor da ultima parcela:'||v_valultimaparcelanew,lRaise,false,false);
      perform fc_debug('pegando cgm do(s) numpre(s) com arrecad...',lRaise,false,false);
    end if;

    -- busca cgm principal para gravar no arrecad posteriormente
    if v_parcinicial is true then

      select k00_numcgm
        into v_cgmpri
        from arrecad
             inner join numpres_parc on arrecad.k00_numpre = numpres_parc.k00_numpre
       limit 1;

    else

      select k00_numcgm
        into v_cgmpri
        from arrecad
             inner join numpres_parc on arrecad.k00_numpre = numpres_parc.k00_numpre
                                    and arrecad.k00_numpar = numpres_parc.k00_numpar
       limit 1;

    end if;

    if lRaise is true then
      perform fc_debug('Pegando cgm de acordo com matricula ou inscricao...',lRaise,false,false);
    end if;

    v_anousu := iAnousu;

    -- se for parcelamento de inicial
    if v_parcinicial is true then

      if lRaise is true then
        perform fc_debug('t i p o: 18',lRaise,false,false);
      end if;

      -- procura cgm principal por matricula ou inscricao
      for v_record_origem in select distinct
                                    arrematric.k00_matric,
                                    arreinscr.k00_inscr
                               from numpres_parc
                                    left join arrematric on arrematric.k00_numpre = numpres_parc.k00_numpre
                                    left join arreinscr  on arreinscr.k00_numpre  = numpres_parc.k00_numpre
                                   inner join arrecad    on arrecad.k00_numpre    = numpres_parc.k00_numpre
      loop

        if lRaise is true then
          perform fc_debug('processando... matricula: '||v_record_origem.k00_matric||' inscricao: '||v_record_origem.k00_inscr,lRaise,false,false);
        end if;

        if v_record_origem.k00_matric is not null then
          select j01_numcgm
            from iptubase
            into v_cgmpri
           where j01_matric = v_record_origem.k00_matric;
        end if;

        if v_record_origem.k00_inscr is not null then
          select q02_numcgm
            from issbase
            into v_cgmpri
           where q02_inscr = v_record_origem.k00_inscr;
        end if;

      end loop;

    -- senao for inicial do foro
    else

      if lRaise is true then
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug('Buscando CGM princial por matricula ou inscrição',lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
      end if;

      -- procura cgm principal por matricula ou inscricao
      for v_record_origem in select distinct
                                    arrematric.k00_matric,
                                    arreinscr.k00_inscr
                               from numpres_parc
                               left join arrematric on arrematric.k00_numpre = numpres_parc.k00_numpre
                               left join arreinscr  on arreinscr.k00_numpre  = numpres_parc.k00_numpre
                              inner join arrecad    on arrecad.k00_numpre    = numpres_parc.k00_numpre
                                                   and arrecad.k00_numpar = numpres_parc.k00_numpar
      loop

        if lRaise is true then
          perform fc_debug('Processando... matricula: '||v_record_origem.k00_matric||' inscricao: '||v_record_origem.k00_inscr, lRaise, false, false);
        end if;

        if v_record_origem.k00_matric is not null then
          select j01_numcgm
            from iptubase
            into v_cgmpri
           where j01_matric = v_record_origem.k00_matric;
        end if;

        if v_record_origem.k00_inscr is not null then
          select q02_numcgm
            from issbase
            into v_cgmpri
           where q02_inscr = v_record_origem.k00_inscr;
        end if;

      end loop;

      if lRaise is true then
        perform fc_debug('',lRaise,false,false);
        perform fc_debug('',lRaise,false,false);
        perform fc_debug('Fim da busca do CGM principal',lRaise,false,false);
        perform fc_debug('',lRaise,false,false);
      end if;

    end if;

    if lRaise is true  then
      perform fc_debug('agora vai processar correcao e tal...',lRaise,false,false);
    end if;

    -- se for inicial, traz apenas os numpres envolvidos, ja que no caso de parcelamento de inicial
    -- o usuario nao tem opcao de marcar as parcelas, tendo que parcelar toda a inicial
    -- se nao for inicial, traz os numpres com suas respectivas parcelas marcadas
    if v_parcinicial is true then
      v_comando = 'select distinct k00_numpre from numpres_parc';
    else
      v_comando = 'select distinct k00_numpre, k00_numpar from numpres_parc';
    end if;

    -- varre a lista de numpres/parcelas marcados pelo usuario
    for v_record_numpres in execute v_comando
    loop

      if lRaise is true then
        if v_parcinicial is false then
          perform fc_debug('      numpre '||v_record_numpres.k00_numpre||' - numpar: '||v_record_numpres.k00_numpar,lRaise, false, false);
        else
          perform fc_debug('      numpre '||v_record_numpres.k00_numpre||' - numpar: 0',lRaise, false, false);
        end if;
      end if;

      v_matric = 0;
      v_inscr  = 0;

      -- busca a matricula do numpre que esta sendo processado
      select k00_matric
        into v_var
        from arrematric
       where k00_numpre = v_record_numpres.k00_numpre;

      if v_var is not null then
        v_matric = v_var;

        if lRaise is true then
          perform fc_debug(' origem: matricula '||v_matric,lRaise,false,false);
        end if;
      end if;

      -- busca a inscricao do numpre que esta sendo processado
      select k00_inscr
        into v_var
        from arreinscr
       where k00_numpre = v_record_numpres.k00_numpre;

      if v_var is not null then
        v_inscr = v_var;

        if lRaise is true then
          perform fc_debug(' origem: inscricao '||v_inscr,lRaise,false,false);
        end if;
      end if;

      -- processa cada registro acumulando por numpre, parcela, receita e tipo de debito
      -- armazenando as informacoes de valor historico, corrigido, juros e multa
      -- na tabela arrecad_parc_rec para utilizacao em processamento futuro
      -- independente se for inicial ou nao

      -- se for inicial
      if v_parcinicial is true then

        if lRaise is true  then
          perform fc_debug('      entrando tipo 18...',lRaise,false,false);
        end if;

        for v_record_numpar in select k00_numpre,
                                      k00_numpar,
                                      k00_receit,
                                      k03_tipo,
                                      substr(fc_calcula,2,13)::float8  as vlrhis,
                                      substr(fc_calcula,15,13)::float8 as vlrcor,
                                      substr(fc_calcula,28,13)::float8 as vlrjuros,
                                      substr(fc_calcula,41,13)::float8 as vlrmulta,
                                      substr(fc_calcula,54,13)::float8 as vlrdesc,
                                      (substr(fc_calcula,15,13)::float8+substr(fc_calcula,28,13)::float8+substr(fc_calcula,41,13)::float8-substr(fc_calcula,54,13)::float8) as total
                                 from ( select k00_numpre,
                                               k00_numpar,
                                               k00_receit,
                                               k03_tipo,
                                               fc_calcula(k00_numpre,k00_numpar,k00_receit,dDataUsu,dDataUsu,v_anousu) as fc_calcula
                                          from ( select distinct
                                                        arrecad.k00_numpre,
                                                        arrecad.k00_numpar,
                                                        arrecad.k00_receit,
                                                        arretipo.k03_tipo
                                                   from arrecad
                                                        inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo
                                                  where arrecad.k00_numpre = v_record_numpres.k00_numpre
                                               ) as x
                                      ) as y
        loop

          select receit
            from arrecad_parc_rec
            into v_receita
           where numpre = v_record_numpar.k00_numpre
             and numpar = v_record_numpar.k00_numpar
             and receit = v_record_numpar.k00_receit;

          if lRaise is true then
            perform fc_debug('1 - numpre: '||v_record_numpar.k00_numpre||', numpar: '||v_record_numpar.k00_numpar||', receit: '||v_record_numpar.k00_receit||', v_receita: '||v_receita,lRaise,false,false);
          end if;

          -- se nao existe registro insere
          if v_receita is null then

            if lRaise is true then
              perform fc_debug(' ',lRaise,false,false);
              perform fc_debug('inserindo registro na arrecad_parc_rec',lRaise,false,false);
            end if;

            execute 'insert into arrecad_parc_rec values (' || v_record_numpar.k00_numpre || ','
                                                            || v_record_numpar.k00_numpar || ','
                                                            || v_record_numpar.k00_receit || ','
                                                            || v_record_numpar.k03_tipo   || ','
                                                            || v_record_numpar.vlrhis     || ','
                                                            || v_record_numpar.vlrcor     || ','
                                                            || v_record_numpar.vlrjuros   || ','
                                                            || v_record_numpar.vlrmulta   || ','
                                                            || v_record_numpar.vlrdesc    || ','
                                                            || v_record_numpar.total      || ','
                                                            || v_matric                   || ','
                                                            || v_inscr                    || ','
                                                            || 0                          || ','
                                                            || 0                          || ','
                                                            || 0                          || ','
                                                            || 'false'                    || ');';
          -- se ja existe, soma
          else

            execute 'update arrecad_parc_rec set valor   = valor  + ' || v_record_numpar.total    || ','
                                              || 'vlrhis = vlrhis + ' || v_record_numpar.vlrhis   || ','
                                              || 'vlrcor = vlrcor + ' || v_record_numpar.vlrcor   || ','
                                              || 'vlrjur = vlrjur + ' || v_record_numpar.vlrjuros || ','
                                              || 'vlrmul = vlrmul + ' || v_record_numpar.vlrmulta || ','
                                              || 'vlrdes = vlrdes + ' || v_record_numpar.vlrdesc
                                      || ' where numpre = ' || v_record_numpar.k00_numpre
                                      || '   and numpar = ' || v_record_numpar.k00_numpar
                                      || '   and receit = ' || v_record_numpar.k00_receit ||';';
          end if;

        end loop;

        if lRaise is true then
          perform fc_debug('      saindo do tipo 18...',lRaise,false,false);
        end if;

      else -- se nao for inicial foro


        if lRaise is true then
          perform fc_debug(' tipo diferente de 18 ',lRaise,false,false);
        end if;

        if lRaise is true then
          perform fc_debug('numpre: '||v_record_numpres.k00_numpre||' - numpar: '||v_record_numpres.k00_numpar,lRaise,false,false);
        end if;

        for v_record_numpar in select k00_numpre,
                                      k00_numpar,
                                      k00_receit,
                                      k03_tipo,
                                      substr(fc_calcula,2, 13)::float8 as vlrhis,
                                      substr(fc_calcula,15,13)::float8 as vlrcor,
                                      substr(fc_calcula,28,13)::float8 as vlrjuros,
                                      substr(fc_calcula,41,13)::float8 as vlrmulta,
                                      substr(fc_calcula,54,13)::float8 as vlrdesc,
                                      (substr(fc_calcula,15,13)::float8+
                                      substr(fc_calcula,28,13)::float8+
                                      substr(fc_calcula,41,13)::float8-
                                      substr(fc_calcula,54,13)::float8) as total
                                 from ( select distinct
                                               k00_numpre,
                                               k00_numpar,
                                               k00_receit,
                                               k03_tipo,
                                               fc_calcula(k00_numpre,k00_numpar,k00_receit,dDataUsu,dDataUsu,v_anousu) as fc_calcula
                                          from ( select distinct
                                                        arrecad.k00_numpre,
                                                        arrecad.k00_numpar,
                                                        arrecad.k00_receit,
                                                        arretipo.k03_tipo
                                                   from arrecad
                                                        inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo
                                                  where arrecad.k00_numpre = v_record_numpres.k00_numpre
                                                    and arrecad.k00_numpar = v_record_numpres.k00_numpar
                                                                                             ) as x
                                                                            ) as y
          loop

          if lRaise is true then
            perform fc_debug('         dentro do for...',lRaise,false,false);
          end if;

          select receit
            from arrecad_parc_rec
            into v_receita
           where numpre  = v_record_numpar.k00_numpre
             and numpar  = v_record_numpar.k00_numpar
             and receit  = v_record_numpar.k00_receit;

          if lRaise is true then
            perform fc_debug('2 - numpre: '||v_record_numpar.k00_numpre||' numpar: '||v_record_numpar.k00_numpar||' receit: '||v_record_numpar.k00_receit||' v_receita: '||v_receita||' - valor: '||v_record_numpar.total,lRaise,false,false);
          end if;

          -- se nao existe registro insere
          if v_receita is null then

            if lRaise is true then
              perform fc_debug('   inserindo no arrecad_parc_rec... numpre: '||v_record_numpar.k00_numpre,lRaise,false,false);
            end if;

            execute 'insert into arrecad_parc_rec values (' || v_record_numpar.k00_numpre || ',' ||
                                                               v_record_numpar.k00_numpar || ',' ||
                                                               v_record_numpar.k00_receit || ',' ||
                                                               v_record_numpar.k03_tipo   || ',' ||
                                                               v_record_numpar.vlrhis     || ',' ||
                                                               v_record_numpar.vlrcor     || ',' ||
                                                               v_record_numpar.vlrjuros   || ',' ||
                                                               v_record_numpar.vlrmulta   || ',' ||
                                                               v_record_numpar.vlrdesc    || ',' ||
                                                               v_record_numpar.total      || ',' ||
                                                               v_matric                   || ',' ||
                                                               v_inscr                    || ');';

          else

            execute 'update arrecad_parc_rec set valor = valor + ' || v_record_numpar.total
            || ',vlrhis = vlrhis + ' || v_record_numpar.vlrhis
            || ',vlrcor = vlrcor + ' || v_record_numpar.vlrcor
            || ',vlrjur = vlrjur + ' || v_record_numpar.vlrjuros
            || ',vlrmul = vlrmul + ' || v_record_numpar.vlrmulta
            || ',vlrdes = vlrdes + ' || v_record_numpar.vlrdesc
            || ' where numpre = '    || v_record_numpar.k00_numpre || ' and '
            || '       numpar = '    || v_record_numpar.k00_numpar || ' and '
            || '       receit = '    || v_record_numpar.k00_receit || ';';
          end if;

          if lRaise is true then
            perform fc_debug(' fim do for...',lRaise,false,false);
          end if;

        end loop;

      end if;

    end loop;

    if lRaise is true then
      perform fc_debug('gravando na tabela parcelas... tipo: '||v_tipo,lRaise,false,false);
      perform fc_debug('v_temdesconto: '||v_temdesconto,lRaise,false,false);
    end if;

    -- busca regra de parcelamento
    select cadtipoparc.k40_codigo
      into v_cadtipoparc
      from tipoparc
           inner join cadtipoparc on cadtipoparc = k40_codigo
     where maxparc       > 1
       and dDataUsu     >= k40_dtini
       and dDataUsu     <= k40_dtfim
       and k40_codigo    = v_desconto
       and k40_aplicacao = 1 -- Aplicar Antes do Lancamento
     order by maxparc
     limit 1;

    if lRaise is true then
      perform fc_debug('v_cadtipoparc: '||v_cadtipoparc,lRaise,false,false);
    end if;

    -- varre as regras de parcelamento para descobrir o percentual de desconto nos juros e multa de acordo com
    -- a quantidade de parcelas selecionadas pelo usuario
    for v_record_desconto in select *
                               from tipoparc
                              where maxparc     > 1
                                and cadtipoparc = v_cadtipoparc
                                and cadtipoparc = v_desconto
                              order by maxparc
    loop

      if v_totalparcelas >= v_ultparc and v_totalparcelas <= v_record_desconto.maxparc then
        v_tipodescontocor = v_record_desconto.tipovlr;
        v_descontocor = v_record_desconto.descvlr;
        v_descontomul = v_record_desconto.descmul;
        v_descontojur = v_record_desconto.descjur;

        exit;

      end if;

    end loop;

    if lRaise is true then
      perform fc_debug('total do desconto na multa : '||v_descontomul,lRaise,false,false);
      perform fc_debug('total do desconto nos juros: '||v_descontojur,lRaise,false,false);
      perform fc_debug('antes do for do arrecad_parc_rec...',lRaise,false,false);
    end if;

    -- soma o valor corrigido + juros + multa antes de efetuar o desconto
    -- valor apenas para conferencia em possivel debug
    select sum(valor),
           sum(vlrcor+vlrjur+vlrmul-vlrdesccor-vlrdescjur-vlrdescmul)
      into v_somar,
           v_totalliquido
      from arrecad_parc_rec;

    if lRaise is true then
      perform fc_debug('v_somar: '||v_somar||' - v_totalliquido: '||v_totalliquido,lRaise,false,false);
    end if;

    -- varre tabela dos registros a parcelar para aplicar desconto nos juros e multa
    for v_record_recpar in select *
                             from arrecad_parc_rec
    loop

      -- testa se o tipo de debito desse registro tem direito a desconto
      select case
               when k00_cadtipoparc > 0
               then true
               else false
             end
        into v_descontar
        from totalportipo
       where k03_tipodebito = v_record_recpar.tipo;

      if lRaise is true then
        perform fc_debug('tipo: '||v_record_recpar.tipo||' - descontar: '||v_descontar,lRaise,false,false);
      end if;

      -- se tem direito a desconto, aplica o desconto e da update nos valores do registro atual da arrecad_parc_rec
      if v_descontar is true then

        v_valdesccor = 0;

        if v_tipodescontocor = 1 then

          if lRaise is true then
            perform fc_debug('vlrcor: '||v_record_recpar.vlrcor||' - vlrhis: '||v_record_recpar.vlrhis||' - v_descontocor: '||v_descontocor,lRaise,false,false);
          end if;
          v_valdesccor = (v_record_recpar.vlrcor - v_record_recpar.vlrhis) * v_descontocor / 100;

        elsif v_tipodescontocor = 2 then
          v_valdesccor = v_record_recpar.vlrcor * v_descontocor / 100;
        end if;

        if lRaise is true then
          perform fc_debug('v_valdesccor: '||v_valdesccor,lRaise,false,false);
        end if;

        v_valdescjur = v_record_recpar.vlrjur * v_descontojur / 100;

        if lRaise is true then
          perform fc_debug('v_valdescjur: '||v_valdescjur||' - v_descontojur: '||v_descontojur,lRaise,false, false);
        end if;

        v_valdescmul = v_record_recpar.vlrmul * v_descontomul / 100;

        if lRaise is true then
          perform fc_debug('v_valdescmul: '||v_valdescmul||' - v_descontomul: '||v_descontomul,lRaise,false,false);
        end if;

        execute 'update arrecad_parc_rec set vlrjur = ' || v_record_recpar.vlrjur
             || ', vlrmul      = ' || v_record_recpar.vlrmul
             || ', valor       = valor - ' || v_valdescjur || ' - ' || v_valdescmul || ' - ' || v_valdesccor
             || ', vlrdesccor  = ' || v_valdesccor
             || ', vlrdescjur  = ' || v_record_recpar.vlrjur * v_descontojur / 100
             || ', vlrdescmul  = ' || v_record_recpar.vlrmul * v_descontomul / 100
             || ' where numpre = '    || v_record_recpar.numpre || ' and '
             || '       numpar = '    || v_record_recpar.numpar || ' and '
             || '       receit = '    || v_record_recpar.receit ||   ';';

      end if;

      if lRaise is true then
        perform fc_debug('   numpre: '||v_record_recpar.numpre||' - numpar: '||v_record_recpar.numpar||' - receita: '||v_record_recpar.receit,lRaise,false,false);
      end if;

    end loop;

    -- passa o conteudo do campo juro para false em todos os registros
    execute 'update arrecad_parc_rec set juro = false';

    if lRaise is true then
      perform fc_debug('v_desconto: '||v_desconto,lRaise,false,false);
    end if;

    -- se a forma na regra de parcelamento for 2 (juros na ultima)
    select case
             when k40_forma = 2
             then true
             else false
           end
      into v_juronaultima
      from cadtipoparc
     where k40_codigo = v_desconto;

    if v_juronaultima is null then
      v_juronaultima = false;
    end if;

    if lRaise is true then
      perform fc_debug('desconto na ultima: '||v_juronaultima,lRaise,false,false);
    end if;

    for v_record_recpar in select *
                             from arrecad_parc_rec
    loop

      -- se for para colocar juros na ultima
      -- insere mais dois registros: um para juros e outro para multa
      -- e update no campo valor deixando apenas o valor corrigido
      if v_juronaultima is true then

        select k02_recjur,
               k02_recmul
          from tabrec
          into v_recjurosultima,
               v_recmultaultima
         where k02_codigo = v_record_recpar.receit;

        if lRaise is true then
          perform fc_debug('jur: '||v_recjurosultima||' - mul: '||v_recmultaultima,lRaise,false,false);
          perform fc_debug('numpre: '||v_record_recpar.numpre||' - numpar: '||v_record_recpar.numpar||' - jurosnaultima: '||v_recjurosultima,lRaise,false,false);
          perform fc_debug('tipo: '||v_record_recpar.tipo||' - juros: '||v_record_recpar.vlrjur||' - matric: '||v_record_recpar.matric||' - inscr: '||v_record_recpar.inscr||' - descjur: '||v_record_recpar.vlrdescjur||' - descmul: '||v_record_recpar.vlrdescmul,lRaise,false,false);
        end if;

        execute 'insert into arrecad_parc_rec values (' || v_record_recpar.numpre          || ',' ||
                                                           v_record_recpar.numpar          || ',' ||
                                                           v_recjurosultima                || ',' ||
                                                           v_record_recpar.tipo            || ',' ||
                                                           v_record_recpar.vlrjur          || ',' ||
                                                           v_record_recpar.vlrjur          || ',' ||
                                                           0                               || ',' ||
                                                           0                               || ',' ||
                                                           0                               || ',' ||
                                                           v_record_recpar.vlrjur          || ',' ||
                                                           v_record_recpar.matric          || ',' ||
                                                           v_record_recpar.inscr           || ',' ||
                                                           0                               || ',' ||
                                                           v_record_recpar.vlrdescjur      || ',' ||
                                                           v_record_recpar.vlrdescmul      || ',' ||
                                                           'true'                          || ');';

        if lRaise is true then
          perform fc_debug('1',lRaise,false,false);
        end if;

        -- inserindo multa
        execute 'insert into arrecad_parc_rec values (' || v_record_recpar.numpre          || ',' ||
                                                           v_record_recpar.numpar          || ',' ||
                                                           v_recmultaultima                || ',' ||
                                                           v_record_recpar.tipo            || ',' ||
                                                           v_record_recpar.vlrmul          || ',' ||
                                                           v_record_recpar.vlrmul          || ',' ||
                                                           0                               || ',' ||
                                                           0                               || ',' ||
                                                           0                               || ',' ||
                                                           v_record_recpar.vlrmul          || ',' ||
                                                           v_record_recpar.matric          || ',' ||
                                                           v_record_recpar.inscr           || ',' ||
                                                           0                               || ',' ||
                                                           v_record_recpar.vlrdescjur      || ',' ||
                                                           v_record_recpar.vlrdescmul      || ',' ||
                                                           'true'                          || ');';

        if lRaise is true then
          perform fc_debug('2',lRaise,false,false);
        end if;

        execute 'update arrecad_parc_rec set valor  = ' || v_record_recpar.vlrcor ||
                                     ' where numpre = ' || v_record_recpar.numpre ||
                                     '   and numpar = ' || v_record_recpar.numpar ||
                                     '   and receit = ' || v_record_recpar.receit || ';';

        if lRaise is true then
          perform fc_debug('3',lRaise,false,false);
        end if;

      end if;

    end loop;

    if lRaise is true then
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
    end if;

    -- apenas mostra os registros atuais para possivel conferencia
    for v_record_recpar in select *
                             from arrecad_parc_rec loop

      if lRaise is true then
        perform fc_debug('numpre: '||v_record_recpar.numpre||' - par: '||v_record_recpar.numpar||' - rec: '||v_record_recpar.receit||' - cor: '||v_record_recpar.vlrcor||' - jur: '||v_record_recpar.vlrjur||' - tot: '||v_record_recpar.valor||' - juro: '||v_record_recpar.juro,lRaise, false,false);
      end if;

    end loop;

    if lRaise is true then
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
    end if;

    if lRaise is true then
      perform fc_debug('depois do for do arrecad_parc_rec...',lRaise,false,false);
    end if;

    -- calcula valor total com juro
    select sum(valor)
      from arrecad_parc_rec
      into v_totalcomjuro;

    -- se for juros na ultima, o campo valor ja esta sem juros e multa
    -- entao a variavel v_total recebe sem juros e a regra for de colocar os juros na ultima parcela
    -- note que o campo juro da tabela recebe false apenas nos registros que nao sao dos juros para incluir na ultima
    if v_juronaultima is false then
      select sum(valor)
        from arrecad_parc_rec
        into v_total;
    else
      select sum(valor)
        from arrecad_parc_rec
       where juro is false
        into v_total;
    end if;

    -- diferente entre variavel com e sem juros
    -- utilizada na regra de juros na ultima
    v_diferencanaultima = v_totalcomjuro - v_total;

    if lRaise is true then
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug('total (primeira versao do script): '||v_total||' - v_totalparcelas: '||v_totalparcelas,lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug('v_tipo: '||v_tipo,lRaise,false,false);
    end if;

    v_somar = 0;

    if lRaise is true then
      perform fc_debug('antes do tipo 5...',lRaise,false,false);
    end if;

    -- cria variavel para select agrupando os valores por
    -- tipo de origem, receita nova e receita original
    -- note que o sistema tem 3 niveis de origem
    -- 1 = de divida ativa
    -- 2 = parcelamento de divida, parcelamento de inicial, parcelamento de contribuicao, inicial do foro e contribuicao
    -- 3 = diversos

    v_comando =              '  select tipo_origem,                                                                                                                       \n';
    v_comando = v_comando || '         receita,                                                                                                                           \n';
    v_comando = v_comando || '         receitaori,                                                                                                                        \n';
    v_comando = v_comando || '         min(k00_hist) as k00_hist,                                                                                                         \n';
    v_comando = v_comando || '         round(sum(valor),2) as valor,                                                                                                      \n';
    v_comando = v_comando || '         round(sum(total_his),2) as total_his,                                                                                              \n';
    v_comando = v_comando || '         round(sum(total_cor),2) as total_cor,                                                                                              \n';
    v_comando = v_comando || '         round(sum(total_jur),2) as total_jur,                                                                                              \n';
    v_comando = v_comando || '         round(sum(total_mul),2) as total_mul,                                                                                              \n';
    v_comando = v_comando || '         round(sum(total_desccor),2) as total_desccor,                                                                                      \n';
    v_comando = v_comando || '         round(sum(total_descjur),2) as total_descjur,                                                                                      \n';
    v_comando = v_comando || '         round(sum(total_descmul),2) as total_descmul                                                                                       \n';
    v_comando = v_comando || '    from ( select 1 as tipo_origem,                                                                                                         \n';
    v_comando = v_comando || '                  receit as receita,                                                                                                        \n';
    v_comando = v_comando || '                  receitaori,                                                                                                               \n';
    v_comando = v_comando || '                  min(k00_hist) as k00_hist,                                                                                                \n';
    v_comando = v_comando || '                  sum(valor) as valor,                                                                                                      \n';
    v_comando = v_comando || '                  sum(total_his) as total_his,                                                                                              \n';
    v_comando = v_comando || '                  sum(total_cor) as total_cor,                                                                                              \n';
    v_comando = v_comando || '                  sum(total_jur) as total_jur,                                                                                              \n';
    v_comando = v_comando || '                  sum(total_mul) as total_mul,                                                                                              \n';
    v_comando = v_comando || '                  sum(total_desccor) as total_desccor,                                                                                      \n';
    v_comando = v_comando || '                  sum(total_descjur) as total_descjur,                                                                                      \n';
    v_comando = v_comando || '                  sum(total_descmul) as total_descmul                                                                                       \n';
    v_comando = v_comando || '               from ( select a.numpre,                                                                                                      \n';
    v_comando = v_comando || '                             a.numpar,                                                                                                      \n';
    v_comando = v_comando || '                             a.receita as receit,                                                                                           \n';
    v_comando = v_comando || '                             a.receitaori as receitaori,                                                                                    \n';
    v_comando = v_comando || '                             min(k00_hist) as k00_hist,                                                                                     \n';
    v_comando = v_comando || '                             sum(a.valor) as valor,                                                                                         \n';
    v_comando = v_comando || '                             sum(total_his) as total_his,                                                                                   \n';
    v_comando = v_comando || '                             sum(total_cor) as total_cor,                                                                                   \n';
    v_comando = v_comando || '                             sum(total_jur) as total_jur,                                                                                   \n';
    v_comando = v_comando || '                             sum(total_mul) as total_mul,                                                                                   \n';
    v_comando = v_comando || '                             sum(total_desccor) as total_desccor,                                                                           \n';
    v_comando = v_comando || '                             sum(total_descjur) as total_descjur,                                                                           \n';
    v_comando = v_comando || '                             sum(total_descmul) as total_descmul                                                                            \n';
    v_comando = v_comando || '                      from ( select arrecad_parc_rec.numpre,                                                                                \n';
    v_comando = v_comando || '                                    arrecad_parc_rec.numpar,                                                                                \n';
    v_comando = v_comando || '                                    arrecad_parc_rec.receit as receitaori,                                                                  \n';
    v_comando = v_comando || '                                    recparproc.receita as receita,                                                                          \n';
    v_comando = v_comando || '                                    min(proced.k00_hist) as k00_hist,                                                                       \n';
    v_comando = v_comando || '                                    round(sum(arrecad_parc_rec.valor),2) as valor,                                                          \n';
    v_comando = v_comando || '                                    round(sum(vlrhis),2) as total_his,                                                                      \n';
    v_comando = v_comando || '                                    round(sum(vlrcor),2) as total_cor,                                                                      \n';
    v_comando = v_comando || '                                    round(sum(vlrjur),2) as total_jur,                                                                      \n';
    v_comando = v_comando || '                                    round(sum(vlrmul),2) as total_mul,                                                                      \n';
    v_comando = v_comando || '                                    round(sum(vlrdesccor),2) as total_desccor,                                                              \n';
    v_comando = v_comando || '                                    round(sum(vlrdescjur),2) as total_descjur,                                                              \n';
    v_comando = v_comando || '                                    round(sum(vlrdescmul),2) as total_descmul                                                               \n';
    v_comando = v_comando || '                               from arrecad_parc_rec                                                                                        \n';
    v_comando = v_comando || '                                    inner join arrecad     on arrecad.k00_numpre    = arrecad_parc_rec.numpre                               \n';
    v_comando = v_comando || '                                                          and arrecad.k00_numpar    = arrecad_parc_rec.numpar                               \n';
    v_comando = v_comando || '                                                          and arrecad.k00_receit    = arrecad_parc_rec.receit                               \n';
    v_comando = v_comando || '                                                          and arrecad.k00_valor     > 0                                                     \n';
    v_comando = v_comando || '                                    inner join arretipo    on arretipo.k00_tipo     = arrecad.k00_tipo                                      \n';
    v_comando = v_comando || '                                    left  join divida      on divida.v01_numpre     = arrecad.k00_numpre                                    \n';
    v_comando = v_comando || '                                                          and divida.v01_numpar     = arrecad.k00_numpar                                    \n';
    v_comando = v_comando || '                                    left  join recparproc  on recparproc.v03_codigo = divida.v01_proced                                     \n';
    v_comando = v_comando || '                                    inner join proced      on proced.v03_codigo     = divida.v01_proced                                     \n';
    v_comando = v_comando || '                                    where k03_tipo = 5                                                                                      \n';
    if v_juronaultima is true then
     v_comando = v_comando || '                                     and juro is false                                                                                     \n';
    end if;
    v_comando = v_comando || '                                    group by arrecad_parc_rec.numpre,                                                                       \n';
    v_comando = v_comando || '                                             arrecad_parc_rec.numpar,                                                                       \n';
    v_comando = v_comando || '                                             arrecad_parc_rec.receit,                                                                       \n';
    v_comando = v_comando || '                                             recparproc.receita                                                                             \n';
    v_comando = v_comando || '                           ) as a                                                                                                           \n';
    v_comando = v_comando || '                          group by a.numpre,                                                                                                \n';
    v_comando = v_comando || '                                   a.numpar,                                                                                                \n';
    v_comando = v_comando || '                                   a.receita,                                                                                               \n';
    v_comando = v_comando || '                                   a.receitaori                                                                                             \n';
    v_comando = v_comando || '                    ) as x                                                                                                                  \n';
    v_comando = v_comando || '               group by receit,                                                                                                             \n';
    v_comando = v_comando || '                        receitaori                                                                                                          \n';

    v_comando = v_comando || '      union                                                                                                                                 \n';

    v_comando = v_comando || '             select 2 as tipo_origem,                                                                                                       \n';
    v_comando = v_comando || '                    case when recparproc.receita is null then                                                                               \n';
    v_comando = v_comando || '                         arrecad_parc_rec.receit                                                                                            \n';
    v_comando = v_comando || '                       else                                                                                                                 \n';
    v_comando = v_comando || '                         recparproc.receita                                                                                                 \n';
    v_comando = v_comando || '                    end as receit,                                                                                                          \n';
    v_comando = v_comando || '                    arrecad_parc_rec.receit as receitaori,                                                                                  \n';
    v_comando = v_comando || '                    min(arrecad.k00_hist) as k00_hist,                                                                                      \n';
    v_comando = v_comando || '                    round(sum(arrecad_parc_rec.valor),2) as valor,                                                                          \n';
    v_comando = v_comando || '                    round(sum(vlrhis),2) as total_his,                                                                                      \n';
    v_comando = v_comando || '                    round(sum(vlrcor),2) as total_cor,                                                                                      \n';
    v_comando = v_comando || '                    round(sum(vlrjur),2) as total_jur,                                                                                      \n';
    v_comando = v_comando || '                    round(sum(vlrmul),2) as total_mul,                                                                                      \n';
    v_comando = v_comando || '                    round(sum(vlrdesccor),2) as total_desccor,                                                                              \n';
    v_comando = v_comando || '                    round(sum(vlrdescjur),2) as total_descjur,                                                                              \n';
    v_comando = v_comando || '                    round(sum(vlrdescmul),2) as total_descmul                                                                               \n';
    v_comando = v_comando || '               from arrecad_parc_rec                                                                                                        \n';
    v_comando = v_comando || '                    inner join arrecad    on arrecad.k00_numpre = arrecad_parc_rec.numpre                                                   \n';
    v_comando = v_comando || '                                         and arrecad.k00_numpar = arrecad_parc_rec.numpar                                                   \n';
    v_comando = v_comando || '                                         and arrecad.k00_receit = arrecad_parc_rec.receit                                                   \n';
    v_comando = v_comando || '                                         and arrecad.k00_valor > 0                                                                          \n';
    v_comando = v_comando || '                    inner join arretipo   on  arretipo.k00_tipo = arrecad.k00_tipo                                                          \n';
    v_comando = v_comando || '                    left  join divida     on divida.v01_numpre     = arrecad.k00_numpre                                                     \n';
    v_comando = v_comando || '                                         and divida.v01_numpar     = arrecad.k00_numpar                                                     \n';
    v_comando = v_comando || '                    left  join recparproc on recparproc.v03_codigo = divida.v01_proced                                                      \n';
    v_comando = v_comando || '                    left join proced     on proced.v03_codigo     = divida.v01_proced                                                      \n';
    v_comando = v_comando || '              where ( k03_tipo in (6, 13, 18, 17, 4)                                                                                        \n';
    v_comando = v_comando || '                      or (     k03_tipo in (7,16)                                                                                           \n';
    v_comando = v_comando || '                           and exists (select 1                                                                                             \n';
    v_comando = v_comando || '                                         from termo                                                                                         \n';
    v_comando = v_comando || '                                              inner join termoreparc on termoreparc.v08_parcel = termo.v07_parcel                           \n';
    v_comando = v_comando || '                                        where v07_numpre = arrecad_parc_rec.numpre) )                                                       \n';
    v_comando = v_comando || '                    )                                                                                                                       \n';
    v_comando = v_comando || '                and not exists (select 1                                                                                                    \n';
    v_comando = v_comando || '                                  from termo                                                                                                \n';
    v_comando = v_comando || '                                       inner join termodiver on termo.v07_parcel = termodiver.dv10_parcel                                   \n';
    v_comando = v_comando || '                                 where termo.v07_numpre = arrecad_parc_rec.numpre )                                                         \n';
    if v_juronaultima is true then
        v_comando = v_comando || '            and juro is false                                                                                                           \n';
    end if;
    v_comando = v_comando || '              group by recparproc.receita,                                                                                             \n';
    v_comando = v_comando || '                     arrecad_parc_rec.receit                                                                                                \n';

    v_comando = v_comando || '    union \n';

    v_comando = v_comando || '             select 3 as tipo_origem,                                                                                                       \n';
    v_comando = v_comando || '                    recparprocdiver.receita,                                                                                                \n';
    v_comando = v_comando || '                    recparprocdiver.receita as receitaori,                                                                                  \n';
    v_comando = v_comando || '                    procdiver.dv09_hist,                                                                                                    \n';
    v_comando = v_comando || '                    round(sum(valor),2) as valor,                                                                                           \n';
    v_comando = v_comando || '                    round(sum(vlrhis),2) as total_his,                                                                                      \n';
    v_comando = v_comando || '                    round(sum(vlrcor),2) as total_cor,                                                                                      \n';
    v_comando = v_comando || '                    round(sum(vlrjur),2) as total_jur,                                                                                      \n';
    v_comando = v_comando || '                    round(sum(vlrmul),2) as total_mul,                                                                                      \n';
    v_comando = v_comando || '                    round(sum(vlrdesccor),2) as total_desccor,                                                                              \n';
    v_comando = v_comando || '                    round(sum(vlrdescjur),2) as total_descjur,                                                                              \n';
    v_comando = v_comando || '                    round(sum(vlrdescmul),2) as total_descmul                                                                               \n';
    v_comando = v_comando || '               from diversos                                                                                                                \n';
    v_comando = v_comando || '                    left join (select termodiver.*                                                                                          \n';
    v_comando = v_comando || '                                 from termodiver                                                                                            \n';
    v_comando = v_comando || '                                inner join termo on dv10_parcel = v07_parcel                                                                \n';
    v_comando = v_comando || '                                                and v07_situacao = 1) as termodiver on dv05_coddiver             = dv10_coddiver            \n';
    v_comando = v_comando || '                                 left join recparprocdiver                          on recparprocdiver.procdiver = diversos.dv05_procdiver  \n';
    v_comando = v_comando || '                                inner join procdiver                                on procdiver.dv09_procdiver  = diversos.dv05_procdiver  \n';
    v_comando = v_comando || '                                inner join arrecad_parc_rec                         on diversos.dv05_numpre      = arrecad_parc_rec.numpre  \n';
    v_comando = v_comando || '              where dv10_coddiver is null                                                                                                   \n';
    v_comando = v_comando || '              group by recparprocdiver.receita,                                                                                             \n';
    v_comando = v_comando || '                       procdiver.dv09_hist                                                                                                  \n';

    v_comando = v_comando || '    union                                                                                                                                   \n';

    v_comando = v_comando || '           select tipo_origem,                                                                                                              \n';
    v_comando = v_comando || '                  receita,                                                                                                                  \n';
    v_comando = v_comando || '                  receitaori,                                                                                                               \n';
    v_comando = v_comando || '                  dv09_hist,                                                                                                                \n';
    v_comando = v_comando || '                  round(sum(valor),2) as valor,                                                                                             \n';
    v_comando = v_comando || '                  round(sum(vlrhis),2) as total_his,                                                                                        \n';
    v_comando = v_comando || '                  round(sum(vlrcor),2) as total_cor,                                                                                        \n';
    v_comando = v_comando || '                  round(sum(vlrjur),2) as total_jur,                                                                                        \n';
    v_comando = v_comando || '                  round(sum(vlrmul),2) as total_mul,                                                                                        \n';
    v_comando = v_comando || '                  round(sum(vlrdesccor),2) as total_desccor,                                                                                \n';
    v_comando = v_comando || '                  round(sum(vlrdescjur),2) as total_descjur,                                                                                \n';
    v_comando = v_comando || '                  round(sum(vlrdescmul),2) as total_descmul                                                                                 \n';
    v_comando = v_comando || '             from ( select 4 as tipo_origem,                                                                                                \n';
    v_comando = v_comando || '                           (select min(recparprocdiver.receita)                                                                             \n';
    v_comando = v_comando || '                              from termodiver                                                                                               \n';
    v_comando = v_comando || '                                   inner join diversos         on termodiver.dv10_coddiver  = dv05_coddiver                                 \n';
    v_comando = v_comando || '                                   inner join recparprocdiver  on recparprocdiver.procdiver = diversos.dv05_procdiver                       \n';
    v_comando = v_comando || '                                   inner join procdiver        on procdiver.dv09_procdiver  = diversos.dv05_procdiver                       \n';
    v_comando = v_comando || '                             where termodiver.dv10_parcel = v07_parcel ) as receita,                                                        \n';
    v_comando = v_comando || '                           (select min(recparprocdiver.receita)                                                                             \n';
    v_comando = v_comando || '                              from termodiver                                                                                               \n';
    v_comando = v_comando || '                                   inner join diversos         on termodiver.dv10_coddiver  = dv05_coddiver                                 \n';
    v_comando = v_comando || '                                   inner join recparprocdiver  on recparprocdiver.procdiver = diversos.dv05_procdiver                       \n';
    v_comando = v_comando || '                                   inner join procdiver        on procdiver.dv09_procdiver  = diversos.dv05_procdiver                       \n';
    v_comando = v_comando || '                             where termodiver.dv10_parcel = v07_parcel ) as receitaori,                                                     \n';
    v_comando = v_comando || '                           (select min(procdiver.dv09_hist)                                                                                 \n';
    v_comando = v_comando || '                              from termodiver                                                                                               \n';
    v_comando = v_comando || '                                   inner join diversos         on termodiver.dv10_coddiver  = dv05_coddiver                                 \n';
    v_comando = v_comando || '                                   inner join recparprocdiver  on recparprocdiver.procdiver = diversos.dv05_procdiver                       \n';
    v_comando = v_comando || '                                   inner join procdiver        on procdiver.dv09_procdiver  = diversos.dv05_procdiver                       \n';
    v_comando = v_comando || '                             where termodiver.dv10_parcel = v07_parcel ) as dv09_hist,                                                      \n';
    v_comando = v_comando || '                           valor,                                                                                                           \n';
    v_comando = v_comando || '                           vlrhis,                                                                                                          \n';
    v_comando = v_comando || '                           vlrcor,                                                                                                          \n';
    v_comando = v_comando || '                           vlrjur,                                                                                                          \n';
    v_comando = v_comando || '                           vlrmul,                                                                                                          \n';
    v_comando = v_comando || '                           vlrdesccor,                                                                                                      \n';
    v_comando = v_comando || '                           vlrdescjur,                                                                                                      \n';
    v_comando = v_comando || '                           vlrdescmul                                                                                                       \n';
    v_comando = v_comando || '                      from arrecad_parc_rec                                                                                                 \n';
    v_comando = v_comando || '                           inner join termo on v07_numpre = arrecad_parc_rec.numpre                                                         \n';
    v_comando = v_comando || '                           inner join ( select distinct                                                                                     \n';
    v_comando = v_comando || '                                               dv10_parcel                                                                                  \n';
    v_comando = v_comando || '                                          from termodiver ) as parcdiver  on parcdiver.dv10_parcel = termo.v07_parcel                       \n';
    v_comando = v_comando || '                  ) as diver                                                                                                                \n';
    v_comando = v_comando || '            group by tipo_origem,                                                                                                           \n';
    v_comando = v_comando || '                     receita,receitaori,                                                                                                    \n';
    v_comando = v_comando || '                     dv09_hist                                                                                                              \n';
    v_comando = v_comando || '         ) as xxx                                                                                                                           \n';
    v_comando = v_comando || 'group by tipo_origem,                                                                                                                       \n';
    v_comando = v_comando || '         receita,                                                                                                                           \n';
    v_comando = v_comando || '         receitaori                                                                                                                         \n';

    if lRaise then
      perform fc_debug('sql : '||v_comando,lRaise,false,false);
    end if;

    if lRaise then
      perform fc_debug('v_total: '||v_total,lRaise,false,false);
    end if;

    v_comando_cria = 'create temp table w_testando as ' || v_comando;
    execute v_comando_cria;

    -- tipo 3 = parcelamento de diversos
    -- tipo 4 = reparcelamento de diversos

    -- se regra for de juros na ultima, diminui o total de parcelas em 1
    if v_juronaultima is true then
       v_totalparcelas = v_totalparcelas - 1;

       if lRaise is true then
         perform fc_debug('mudando - v_total: '||v_total,lRaise,false,false);
       end if;
    end if;

    -- processa receita por receita para gerar os registros na tabela parcelas
    -- que sera utilizada posteriormente para gerar os registros na tabela arrecad
    for v_record_recpar in execute v_comando
    loop

      if v_record_recpar.tipo_origem is null then
        return '[6] - Não encontrados registros na tabela Divida para um dos debitos que esta sendo parcelado.';
      end if;

      if v_record_recpar.receita is null then
        return '[7] - Receita de parcelamento nao configurada para a procedencia';
      end if;

      -- se origem for divida ativa, soma na variavel v_totaldivida
      if v_record_recpar.tipo_origem = 1 then
          v_totaldivida = v_totaldivida + v_record_recpar.valor;
      end if;

      if lRaise is true then
          perform fc_debug('tipo_origem: '||v_record_recpar.tipo_origem||' - receita: '||v_record_recpar.receita||' - receitaoriginal: '||v_record_recpar.receitaori||' - hist: '||v_record_recpar.k00_hist||' - valor: '||v_record_recpar.valor||' - total_cor: '||v_record_recpar.total_cor,lRaise,false,false);
      end if;

      -- calcula entrada proporcional ao valor desta receita
      -- regra de tres normal em relacao percentual da entrada do registro atual em relacao ao total do parcelamento
      -- se for o caso de ter apenas uma receita em processamento, essa variavel vai ser igual ao valor da entrada
      v_ent_prop = v_record_recpar.valor * (v_entrada / v_total);
      v_total_liquido = v_record_recpar.total_cor + v_record_recpar.total_jur + v_record_recpar.total_mul - v_record_recpar.total_desccor - v_record_recpar.total_descjur - v_record_recpar.total_descmul;

      if lRaise is true then
        perform fc_debug('xxxxxxxxxxxxx: receita: '||v_record_recpar.receita||' - valor: '||v_record_recpar.valor||' - entrada proporcional: '||v_ent_prop||' - valor: '||v_record_recpar.valor||' - total: '||v_total_liquido,lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug('========== receita: '||v_record_recpar.receita,lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
      end if;

      -- processa parcela por parcela
      for v_parcela in 1..v_totalparcelas
      loop

        -- variavel do valor da parcela recebe o valor da receita deste registro / valor total do parcelamento
        -- que na pratica seria a proporcionalidade deste registro em relacao ao total do parcelamento
        v_valparc = v_record_recpar.valor / v_total;

        if lRaise is true then
            perform fc_debug('   v_valparc: '||v_valparc||' - v_total: '||v_total||' - valor: '||v_record_recpar.valor||' - receit: '||v_record_recpar.receita||' - entrada: '||v_entrada,lRaise,false,false);
        end if;

        if v_parcela = 1 then
            -- se parcela igual a 1, entao valor parcela e igual ao valor da entrada * valor da proporcionalidade
            -- deste registro em relacao ao total do parcelamento
            v_valparc = v_entrada * v_valparc;
        else
            -- se nao for a parcela 1 entao
            -- valor da parcela recebe o valor da parcela definido pelo usuario na CGF * valor da proporcionalidade
            -- deste registro em relacao ao total do parcelamento
            v_valparc = v_valorparcelanew * v_valparc;
        end if;

        --v_valparc = round(v_valparc,2);

        if lRaise is true then
            perform fc_debug('   000 = parcela: '||v_parcela||' - receita: '||v_record_recpar.receita||' - valor: '||v_valparc||' - v_valorparcelanew: '||v_valorparcelanew||' - receitaori: '||v_record_recpar.receitaori,lRaise,false,false);
        end if;

        v_calcula_valprop = v_record_recpar.valor / v_total;
        v_teste           = v_record_recpar.valor / v_total;

        if v_teste <= 0 then

          if lRaise is true then
            perform fc_debug('valor: '||v_record_recpar.valor||' - v_total: '||v_total||' - v_teste: '||v_teste||' - parcela: '||v_parcela||' - receita: '||v_record_recpar.receita||' - v_calcula_valprop: '||v_calcula_valprop,lRaise,false,false);
          end if;
        end if;

        if lRaise is true then
          perform fc_debug('v_valparc: '||v_valparc||' - valor: '||v_record_recpar.valor||' - total_his: '||v_record_recpar.total_his||' - total: '||v_total,lRaise,false,false);
        end if;

        v_calcula_valor   = v_record_recpar.valor;
        v_calcula_his     = v_valparc / v_record_recpar.valor * v_record_recpar.total_his;
        v_calcula_cor     = v_valparc / v_record_recpar.valor * v_record_recpar.total_cor;
        v_calcula_jur     = v_valparc / v_record_recpar.valor * v_record_recpar.total_jur;
        v_calcula_mul     = v_valparc / v_record_recpar.valor * v_record_recpar.total_mul;
        v_calcula_desccor = v_valparc / v_calcula_valor * v_record_recpar.total_desccor;
        v_calcula_descjur = v_valparc / v_calcula_valor * v_record_recpar.total_descjur;
        v_calcula_descmul = v_valparc / v_calcula_valor * v_record_recpar.total_descmul;

        if lRaise then
          perform fc_debug('v_calcula_his: '||v_calcula_his||' - v_valparc: '||v_valparc||' - v_calcula_valor: '||v_calcula_valor||' - total_desccor: '||v_record_recpar.total_desccor,lRaise,false,false);
        end if;

        if v_valparc > 0 then

          if round(v_valparc,2) > 0 then

            lIncluiEmParcelas = true;

          else

            perform * from parcelas where receit = v_record_recpar.receita;

            if found then
              lIncluiEmParcelas = false;
            else
              lIncluiEmParcelas = true;
            end if;

          end if;

          if lIncluiEmParcelas is true then

            -- insere valores calculados na tabela parcelas
            execute 'insert into parcelas values (' || v_parcela                                 || ',' ||
                                                       v_record_recpar.receita                   || ',' ||
                                                       v_record_recpar.receitaori                || ',' ||
                                                       v_record_recpar.k00_hist                  || ',' ||
                                                       v_valparc                                 || ',' ||
                                                       v_calcula_valprop                         || ',' ||
                                                       v_calcula_his                             || ',' ||
                                                       v_calcula_cor                             || ',' ||
                                                       v_calcula_jur                             || ',' ||
                                                       v_calcula_mul                             || ',' ||
                                                       v_calcula_desccor                         || ',' ||
                                                       v_calcula_descjur                         || ',' ||
                                                       v_calcula_descmul                         ||
                                                       ');';

          else

            execute 'update parcelas set '   ||
                    '  valor   = valor   + ' || v_valparc         ||
                    ', valprop = valprop + ' || v_calcula_valprop ||
                    ', valhis  = valhis  + ' || v_calcula_his     ||
                    ', valcor  = valcor  + ' || v_calcula_cor     ||
                    ', valjur  = valjur  + ' || v_calcula_jur     ||
                    ', valmul  = valmul  + ' || v_calcula_mul     ||
                    ', descor  = descor  + ' || v_calcula_desccor ||
                    ', descjur = descjur + ' || v_calcula_descjur ||
                    ', descmul = descmul + ' || v_calcula_descmul ||
                    ' where receit = ' || v_record_recpar.receita;

          end if;

        end if;

      end loop;

    end loop;

    -- se regra for de juros na ultima
    if v_juronaultima is true then

        if lRaise is true then
          perform fc_debug('processando ultima... diferenca: '||(v_totalcomjuro - v_total),lRaise,false,false);
        end if;

        -- soma 1 na variavel do total de parcelas
        v_totalparcelas = v_totalparcelas + 1;

        -- gera comando para agrupar receita por receita somando o valor
        v_comando =              ' select arrecad_parc_rec. ';
        v_comando = v_comando || '        receit as receita, ';
        v_comando = v_comando || '            sum(arrecad_parc_rec.valor) as valor ';
        v_comando = v_comando || '   from arrecad_parc_rec ';
        v_comando = v_comando || '  where juro is true ';
        v_comando = v_comando || '  group by arrecad_parc_rec.receit ';

        select v04_histjuros
          from pardiv
          into v_histjuro;
        if v_histjuro is null then
          v_histjuro = 1;
        end if;

        for v_record_recpar in execute v_comando
        loop

          v_valorinserir = round(v_record_recpar.valor,2);

          if lRaise is true then
             perform fc_debug('111 = inserindo diferenca: '||v_valorinserir||' - receita: '||v_record_recpar.receita||' - valor: '||v_record_recpar.valor.lRaise,false,false);
          end if;

          execute 'insert into parcelas values (' || v_totalparcelas          || ',' ||
                                                     v_record_recpar.receita  || ',' ||
                                                     v_record_recpar.receita  || ',' ||
                                                     v_histjuro               || ',' ||
                                                     v_valorinserir           || ',' ||
                                                     (v_valorinserir) / v_totalcomjuro ||
                                                     ');';

        end loop;

        v_total = v_totalcomjuro;

    end if;

    if lRaise is true then
        perform fc_debug('saindo do tipo 5...',lRaise,false,false);
    end if;

    if lRaise is true then
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug('-',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug('terminou de gravar na tabela parcelas...',lRaise,false,false);
    end if;

    update parcelas set valor   = w_testando.valor,
                        valhis  = w_testando.total_his,
                        valcor  = w_testando.total_cor,
                        valjur  = w_testando.total_jur,
                        valmul  = w_testando.total_mul,
                        descor  = w_testando.total_desccor,
                        descjur = w_testando.total_descjur,
                        descmul = w_testando.total_descmul
     from w_testando
    where receit = w_testando.receita
      and parcelas.valor = 0;

    -- calcula a maior parcela e a soma do valor dos registros da tabela parcelas
    select max(parcela),
           sum(valor)
      from parcelas
      into v_totpar, v_somar;

    if lRaise is true then
      perform fc_debug('total de parcelas: '||v_totpar||' - v_somar: '||v_somar,lRaise,true,true);
    end if;

        -- testa se ocorreu alguma inconsistencia
    if v_totpar = 0 or v_totpar is null then
      return '[8] - Erro ao gerar parcelas... provavelmente falta recparproc...';
    end if;

    select sum(valor)
      into v_totalliquido
      from parcelas;

    if lRaise is true then
      perform fc_debug('v_totalliquido: '||v_totalliquido,lRaise,true,true);
    end if;

    --raise notice 'trocando total (%) por total_liquido (%)', v_total, v_totalliquido;
    --v_total = v_totalliquido;

    -- se for
    -- 6  = parcelamento de divida
    -- 16 = parcelamento de inicial
    -- 17 = parcelamento de melhorias
    -- 13 = inicial do foro
    if v_tipo in (6,16,17,13) then

      -- conta a quantidade de parcelamentos
      select count(v07_parcel)
        into v_quantparcel
        from (select distinct
                     v07_parcel
                from termo
               inner join numpres_parc on termo.v07_numpre = numpres_parc.k00_numpre) as x;
      if v_quantparcel is null then
        return '[9] - Parcelamento nao encontrado pelo numpre';
      end if;

      -- registra o codigo do parcelamento
      select v07_parcel
        into v_termo_ori
        from termo
             inner join numpres_parc on termo.v07_numpre = numpres_parc.k00_numpre
       limit 1;

    end if;

    -- recebe o codigo do novo parcelamento
    select nextval('termo_v07_parcel_seq') into v_termo;

    -- recebe o numpre do novo parcelamento
    select nextval('numpref_k03_numpre_seq') into v_numpre;

    if lRaise is true then
      perform fc_debug('termo '||v_termo,lRaise,false,false);
      perform fc_debug('numpre '||v_numpre,lRaise,false,false);
    end if;

    -- se for reparelamento pega todos os parcelamentos atuais e troca a situacao para 3(inativo)
    if lParcParc then

      for v_record_origem in
        select distinct v07_parcel
          from termo
         inner join numpres_parc on termo.v07_numpre = numpres_parc.k00_numpre
      loop
        -- inativa o parcelamento
        update termo
           set v07_situacao = 3
         where v07_parcel = v_record_origem.v07_parcel;

      end loop;
    end if;

    --if lSeparaJuroMulta and 1=2 then
      /**
       *  Funcao fc_SeparaJuroMulta()
       *
       *    Esta funcao separa o valor do juros e da multa
       *    em registros separados, lancando valor na receita de juro e multa
       *    configurada na tabrec.
       */
      --select * from fc_SeparaJuroMulta() into rSeparaJurMul;

    --end if;

    -- registra o ano do vencimento da segunda parcela
    select extract (year from v_segvenc) into v_anovenc;

    -- registra o mes do vencimento da segunda parcela
    select extract (month from v_segvenc) into v_mesvenc;

    if lRaise is true then
      perform fc_debug('v_anovenc: '||v_anovenc||' - v_mesvenc: '||v_mesvenc,lRaise,false,false);
    end if;

    v_somar = 0;

    -- soma o valor total da tabela parcelas, apenas para conferencia
    for v_record_recpar in select parcela,
                                  receit,
                                  valor
                             from parcelas
    loop
      v_somar = v_somar + v_record_recpar.valor;
      if lRaise is true then
        perform fc_debug('parcela: '||v_record_recpar.parcela||' - receita: '||v_record_recpar.receit||' - valor: '||v_record_recpar.valor,lRaise,false,false);
      end if;
    end loop;

    if lRaise is true then
      perform fc_debug('v_somar: '||v_somar,lRaise,false,false);
    end if;

    -- exibe os valores da tabela parcelas agrupado por receita, apenas para conferencia
    for v_record_recpar in select receit,
                                  sum(valor) as valor,
                                  sum(valhis+valcor+valjur+valmul-descor-descjur-descmul) as sum
                             from parcelas
                            group by receit
    loop
      if lRaise is true then
        perform fc_debug('valor da receita: '||v_record_recpar.receit||' - liquido: '||v_record_recpar.sum||' - valor: '||v_record_recpar.valor,lRaise,false,false);
      end if;
    end loop;

    -- varre a tabela parcelas por receita para gravar os registros no arrecad
    -- existe uma tabela chamada totrec que recebe os valores ja processados e armazena por receita

    -- verifica se tem registro na tabela de configuracao da receita forcada como receita de destino

    for v_record_recpar in select distinct
                                  receitaori
                             from parcelas
    loop

      select case
               when coalesce( (select count(*)
                                 from recreparcori a
                                      inner join recreparcarretipo on k72_codigo = a.k70_codigo
                                where a.k70_recori = recreparcori.k70_recori ),0) = 0
                then k71_recdest
                else case
                       when coalesce( ( select count(*)
                                          from recreparcarretipo
                                         where k72_codigo = recreparcori.k70_codigo
                                           and k72_arretipo = v_tiponovo ),0) = 0
                       then null
                       else k71_recdest
                end
             end as destino
        into v_recdestino
        from recreparcori
             inner join recreparcdest on k70_codigo = k71_codigo
       where k70_recori = v_record_recpar.receitaori
         and v_totparc >= k70_vezesini
         and v_totparc <= k70_vezesfim
         and
         (
           (     ( select count(*)
                     from recreparcori a
                          inner join recreparcarretipo on k72_codigo = a.k70_codigo
                    where a.k70_recori = recreparcori.k70_recori) = 0
             and ( select count(*)
                     from recreparcarretipo
                    where k72_codigo = recreparcori.k70_codigo
                      and k72_arretipo = v_tiponovo) = 0
           )
          or
          (     select count(*)
                  from recreparcori a
                 inner join recreparcarretipo on k72_codigo = a.k70_codigo
                 where a.k70_recori = recreparcori.k70_recori) > 0
            and (select count(*)
                   from recreparcarretipo
                  where k72_codigo = recreparcori.k70_codigo
                    and k72_arretipo = v_tiponovo) > 0
         );


       if lRaise is true or 1 = 1 then
         perform fc_debug('v_recdestino: '||v_recdestino||' - receitaori: '||v_record_recpar.receitaori||' - v_totparc: '||v_totparc||' - v_tiponovo: '||v_tiponovo,lRaise,false,false);
       end if;

       if v_recdestino is not null or v_recdestino <> 0 then
         execute ' update parcelas set receit = ' || v_recdestino || ' where ' ||
                 ' receitaori = ' || v_record_recpar.receitaori || ';';
       end if;

    end loop;

    create temp table w_base_parcelas as
      select parcela,
             receit,
             min(hist)    as hist,
             sum(valor)   as valor,
             sum(valprop) as valprop,
             sum(valhis)  as valhis,
             sum(valcor)  as valcor,
             sum(valjur)  as valjur,
             sum(valmul)  as valmul,
             sum(descor)  as descor,
             sum(descjur) as descjur,
             sum(descmul) as descmul
        from parcelas
       group by parcela, receit
       order by receit, parcela;

    if lRaise is true then
      perform fc_debug('total de parcelas: '||v_totpar||' - v_somar: '||v_somar,lRaise,false,false);
    end if;

    if lRaise is true then
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
    end if;

    for v_record_recpar in  select *
                              from w_base_parcelas
                             order by parcela, receit
    loop

      if lRaise is true then
        perform fc_debug('   inicio do loop... parcela: '||v_record_recpar.parcela||' - receita: '||v_record_recpar.receit||' - v_totalparcelas: '||v_totalparcelas||' - valor: '||v_record_recpar.valor||' - valprop: '||v_record_recpar.valprop,lRaise,false,false);
      end if;

      lParcelaZerada = false;

      -- conta o total de parcelas desta receita
      select max(parcela)
        into v_totparcdestarec
        from parcelas
       where receit = v_record_recpar.receit;

      -- soma o que ja foi inserido na tabela totrec da receita do registro atual
      select coalesce(sum(valor),0) into v_totateagora from totrec where receit = v_record_recpar.receit;

      -- soma o total do valor da tabela parcelas da receita do registro atual
      -- V E R I F I C A R
      select round(sum(valor+valcor+valjur+valmul),2) into v_calcular from parcelas where receit = v_record_recpar.receit;

      if lRaise is true then
        perform fc_debug('v_calcular: '||v_calcular,lRaise,false,false);
      end if;

      if lRaise is true then
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug('total desta receita: '||v_record_recpar.receit||' - ate agora: '||v_totateagora,lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
      end if;

      -- registra o valor da receita do registro atual
      v_valparc = v_record_recpar.valor;

      -- se for a ultima parcela
      if v_record_recpar.parcela = v_totalparcelas then

        -- se for juros na ultima
        if v_juronaultima is true then

          -- valor da parcela recebe exatamente o valor registrado na receita do registro atual
          v_valparc = v_valparc;
        else

          if lRaise is true then
            perform fc_debug('U L T I M A... - RECEITA: '||v_record_recpar.receit,lRaise,false,false);
            perform fc_debug('v_totalparcelas: '||v_totalparcelas||' - v_valparc: '||v_valparc||' - v_entrada: '||v_entrada||' - v_total: '||v_total||' - valprop: '||v_record_recpar.valprop,lRaise,false,false);
          end if;

          if lRaise is true then
            perform fc_debug('total desta receita: '||v_record_recpar.receit||' - ate agora: '||v_totateagora,lRaise,false,false);
          end if;

          -- saldo e calculado com
          -- (o total de parcelas - 2) * valor registrado na receita do registro atual
          --v_saldo = round((v_totalparcelas - 2) * ( v_valparc + v_record_recpar.valcor + v_record_recpar.valjur + v_record_recpar.valmul),2);
          v_saldo = (v_totalparcelas - 2) * v_valparc;

          if lRaise is true then
            perform fc_debug('Saldo Atual: '||v_saldo,lRaise,false,false);
          end if;

          -- saldo eh calculado com
          -- saldo calculado + ( entrada * valor proporcional dessa receita em relacao ao total do parcelamento )
          v_saldo = v_saldo + (v_entrada * v_record_recpar.valprop);

          if lRaise is true then
            perform fc_debug('111 - v_saldo: '||v_saldo||' - v_totateagora: '||v_totateagora||' - v_calcular: '||v_calcular,lRaise,false,false);
          end if;

          if lRaise is true then
            perform fc_debug('totateagora: '||v_totateagora||' - total: '||v_total||' - valprop: '||v_record_recpar.valprop||' - saldo: '||v_saldo||' - rec: '||v_record_recpar.receit||' - parc: '||v_record_recpar.parcela||' - hist: '||v_record_recpar.hist,lRaise,false,false);
          end if;

          -- se total ate agora for maior ou igual ao total do parcelamento * valor proporcional dessa receita em relacao ao total do parcelamento
          if round(v_totateagora, 2) >= round(v_total * v_record_recpar.valprop, 2) then
            if lRaise is true then
              perform fc_debug('v_totateagora: '||v_totateagora||' - v_total: '||v_total||' - valprop: '||v_record_recpar.valprop, lRaise,false,false);
              perform fc_debug('passou na ultima...',lRaise,false,false);
            end if;
            -- valor da parcela recebe zero
            v_valparc = 0;
            lParcelaZerada=true;
            continue;

          -- se total ate agora for menor ao total do parcelamento * valor proporcional dessa receita em relacao ao total do parcelamento
          else

            if lRaise is true then
              perform fc_debug('nao passou na ultima... v_total: '||v_total||' - v_saldo: '||v_saldo||' - prop: '||v_record_recpar.valprop,lRaise,false,false);
            end if;

            -- valor da parcela recebe: (total do parcelamento * valor proporcional dessa receita em relacao ao total do parcelamento) - saldo calculado
            v_valparc = (v_total * v_record_recpar.valprop) - v_saldo;

            if lRaise is true then
              perform fc_debug('v_valparc: '||v_valparc,lRaise,false,false);
            end if;

            -- se valor da parcela for menor que zero
            if v_valparc < 0 then

              -- valor da parcela recebe
              -- (total do parcelamento * valor proporcional dessa receita em relacao ao total do parcelamento) - saldo - valor da parcela
              v_valparc = (v_total * v_record_recpar.valprop) - v_saldo - v_valparc;
              if lRaise is true then
                perform fc_debug(' ',lRaise,false,false);
                perform fc_debug('t e s t e: '||v_valparc,lRaise,false,false);
                perform fc_debug(' ',lRaise,false,false);
              end if;
            end if;

            --v_valparc := ( v_valparc - ( v_record_recpar.valcor + v_record_recpar.valjur + v_record_recpar.valmul ) );

          end if;

          if lRaise is true then
            perform fc_debug('Valor ultima parcela : '||v_valparc,lRaise,false,false);
          end if;

          -- resto recebe valor da parcela + total ate agora
          v_resto = v_valparc + v_totateagora;

          if lRaise is true then
            perform fc_debug('222 - v_saldo: '||v_saldo||' - totateagora: '||v_resto||' - v_valparc: '||v_valparc||' - v_calcular: '||v_calcular,lRaise,false,false);
          end if;

        end if;

      -- se nao for a ultima parcela
      else

        if lRaise is true then
          perform fc_debug(' ',lRaise,false,false);
          perform fc_debug(' n a o   e   a   u l t i m a ',lRaise,false,false);
          perform fc_debug(' ',lRaise,false,false);
        end if;

        -- se for juros na ultima
        if v_juronaultima is true then

          -- se eh a penultima parcela
          if v_record_recpar.parcela = (v_totalparcelas - 1) then

            if lRaise is true then
              perform fc_debug('nessa',lRaise,false,false);
            end if;

          end if;

        end if;

        if lRaise is true then
          perform fc_debug('v_totalparcelas: '||v_totalparcelas||' - v_valparc: '||v_valparc||' - v_entrada: '||v_entrada||' - valprop: '||v_record_recpar.valprop||' - v_total: '||v_total,lRaise,false,false);
        end if;

        -- saldo recebe (total de parcelas - 2) * valor da parcela
        -- V E R I F I C A R
        v_saldo = (v_totalparcelas - 2) * ( v_valparc + v_record_recpar.valcor + v_record_recpar.valjur + v_record_recpar.valmul );

        -- saldo recebe: saldo + (entrada * valor proporcional dessa receita em relacao ao total do parcelamento) - saldo - valor da parcela)
        v_saldo = v_saldo + (v_entrada * v_record_recpar.valprop);

        if lRaise is true then
          perform fc_debug('v_valparc: '||v_valparc,lRaise,false,false);
          perform fc_debug('parcela: '||v_record_recpar.parcela||' - v_valparc: '||v_valparc||' - saldo: '||v_saldo||' - resto: '||v_resto,lRaise,false,false);
        end if;

        -- se total ate agora for maior que total da receita do registro atual
        if round(v_totateagora,2) > round(v_calcular,2) then

          -- (desativado) v_valparc = round(v_saldo - round((v_record_recpar.parcela - 1) * v_valparc,2)::float8,2);
          -- valor da parcela recebe zero
          v_valparc = 0;
          if lRaise is true then
            perform fc_debug('Valor da parcela recebendo ZERO valparc : '||v_valparc,lRaise,false,false);
            perform fc_debug('111111111111111111111',lRaise,false,false);
          end if;

        -- se total ate agora for menor ou igual que total da receita do registro atual
        else

          -- valor ate agora recebe: parcela * valor da parcela
          -- V E R I F I C A R
          v_vlrateagora = v_record_recpar.parcela * (v_valparc + v_record_recpar.valcor + v_record_recpar.valjur + v_record_recpar.valmul);

          if lRaise is true then
            perform fc_debug('v_vlrateagora: '||v_vlrateagora||' - v_valparc: '||v_valparc,lRaise,false,false);
          end if;

          -- resto recebe: (valor total do parcelamento * valor proporcional dessa receita em relacao ao total do parcelamento) - saldo
          v_resto = v_total * v_record_recpar.valprop - v_saldo;

          if lRaise is true then
            perform fc_debug('parcela: '||v_record_recpar.parcela||' - v_valparc: '||v_valparc||' - saldo: '||v_saldo||' - resto: '||v_resto,lRaise,false,false);
          end if;

          if lRaise is true then
            perform fc_debug('v_totateagora: '||v_totateagora||' - v_valparc: '||v_valparc||' - v_calcular: '||v_calcular,lRaise,false,false);
          end if;

          -- se (total ate agora + valor da parcela) for maior que total da receita do registro atual
          if (v_totateagora + v_valparc) > v_calcular then

            -- valor da parcela recebe: total da receita do registro atual - total ate agora
            v_valparc = v_calcular - v_totateagora;
            if lRaise is true then
              perform fc_debug('22222222222',lRaise,false,false);
            end if;
          end if;

        end if;

      end if;

      if lRaise is true then
        perform fc_debug('   ...',lRaise,false,false);
      end if;

      -- se parcela = 1
      if v_record_recpar.parcela = 1 then
        -- vencimento igual ao vencimento da entrada especificada na CGF
        v_vcto = v_privenc;
        -- valor da parcela = entrada * proporcionalidade
        if lRaise is true then
          perform fc_debug('v_entrada: '||v_entrada||' - valprop: '||v_record_recpar.valprop||' - valcor: '||v_record_recpar.valcor||' - valju: '||v_record_recpar.valjur||' - valmul: '||v_record_recpar.valmul,lRaise,false,false);
        end if;

        if lRaise is true then
          perform fc_debug('   1 === v_valparc: '||v_valparc||' - v_entrada: '||v_entrada||' - valprop: '||v_record_recpar.valprop, lRaise, false,false);
        end if;
        v_valparc = (v_entrada) * v_record_recpar.valprop;
        if lRaise is true then
          perform fc_debug('   2 === v_valparc: '||v_valparc,lRaise, false,false);
        end if;

      elsif v_record_recpar.parcela = 2 then
        -- vencimento = vencimento da segunda parcela especificada na CGF
        v_vcto = v_segvenc;
      else

        -- soma meses para calcular vencimento baseado na data de vencimento da parcela 2
        execute 'truncate vcto';
        v_comando = 'insert into vcto select ' || '''' || to_char(v_segvenc,'yyyy') || '-' || trim(to_char(v_segvenc, 'mm')) || '-' || trim(to_char(v_segvenc, 'dd')) || '''' || '::date' || '+' || '''' || v_record_recpar.parcela - 3 || ' months' || '''' || '::interval';
        execute v_comando;

        select extract (month from data),
               extract (year from data)
        from vcto
        into v_mesvenc,
        v_anovenc;

        if lRaise is true then
          perform fc_debug('',lRaise,false,false);
          perform fc_debug('v_mesvenc: '||v_mesvenc||' - parcela: '||v_record_recpar.parcela,lRaise,false,false);
          perform fc_debug('',lRaise,false,false);
        end if;

        -- se mes for 12 (dezembro)
        if to_number(to_char(v_segvenc,'mm'), '999') = 12 then
          -- proximo mes = 1 (janeiro)
          v_proxmessegvenc = 1;
        else
          -- soma mes
          v_proxmessegvenc = to_number(to_char(v_segvenc,'mm'), '999') + 1;
        end if;

        -- faz o mes ficar sempre com 2 digitos
        if v_proxmessegvenc < 10 then
          v_proxmessegvenc_c = '0' || trim(to_char(v_proxmessegvenc, '99'));
        else
          v_proxmessegvenc_c = trim(to_char(v_proxmessegvenc, '999'));
        end if;

        -- registra o dia do proximo vencimento especifidada na CGF
        v_dia = v_diaprox;

        -- soma 1 no mes de vencimento
        v_mesvenc = v_mesvenc + 1;
        if lRaise is true then
          perform fc_debug('   executando vcto... v_segvenc: '||v_segvenc||' - v_diaprox: '||v_diaprox||' - v_dia: '||v_dia||' - v_mesvenc: '||v_mesvenc||' - parc: '||v_record_recpar.parcela,lRaise,false,false);
        end if;

        -- se ultrapassar dezembro, passa para janeiro do ano seguinte
        if v_mesvenc = 13 then
          v_mesvenc = 1;
          v_anovenc = v_anovenc + 1;
        end if;

        v_mesvencprox = v_mesvenc + 1;
        v_anovencprox = v_anovenc;

        -- se ultrapassar dezembro, passa para janeiro do ano seguinte
        if v_mesvencprox = 13 then
          v_mesvencprox = 1;
          v_anovencprox = v_anovencprox + 1;
        end if;

        if lRaise is true then
          perform fc_debug('quase... v_mesvencprox: '||v_mesvencprox||' - v_anovencprox: '||v_anovencprox,lRaise,false,false);
        end if;
        -- calcula ultimo dia de fevereiro
        v_ultdiafev_c   = trim(to_char(v_anovencprox,'99999')) || '-' || trim(to_char(v_mesvencprox, '999')) || '-01';
        if lRaise is true then
          perform fc_debug('   1 - v_ultdiafev_c: '||v_ultdiafev_c,lRaise,false,false);
        end if;
        -- calcula ultimo dia de fevereiro
        v_ultdiafev_d   = trim(v_ultdiafev_c)::date - 1;

        if lRaise is true then
          perform fc_debug('   2 - v_ultdiafev_d: '||v_ultdiafev_d,lRaise,false,false);
        end if;
        -- calcula ultimo dia de fevereiro
        v_ultdiafev = to_number(to_char(v_ultdiafev_d, 'dd'), '999');

        -- testa se dia e valido nos meses
        if v_dia = 31 and v_mesvenc in (4, 6, 9, 11) then
          v_dia = 30;
          if lRaise is true then
            perform fc_debug('mudando 1',lRaise,false,false);
          end if;
        elsif v_dia >= 30 and v_mesvenc in (2) then
          v_dia = 28;
          if lRaise is true then
            perform fc_debug('mudando 2',lRaise,false,false);
          end if;
        end if;

        if lRaise is true then
          perform fc_debug('mesvenc: '||v_mesvenc||' - dia: '||v_dia,lRaise,false,false);
        end if;

        -- calcula se vencimento e correto
        if v_mesvenc = 2 and v_dia >= 28 then
          if lRaise is true then
            perform fc_debug('fevereiro...',lRaise,false,false);
          end if;
          v_dia = v_ultdiafev;
        end if;

        -- calcula vencimento
        execute 'truncate vcto';
        v_comando = 'insert into vcto select ' || '''' || to_char(v_anovenc,'99999') || '-' || trim(trim(to_char(v_mesvenc, '999'))) || '-' || trim(to_char(v_dia, '999')) || '''' || '::date';
        execute v_comando;
        select data from vcto into v_vcto;
        if lRaise is true then
          perform fc_debug('   fim vcto... '||v_vcto,lRaise,false,false);
        end if;

      end if;

      if lRaise is true then
        perform fc_debug('          inserindo em totrec a parcela '||v_record_recpar.parcela||' no valor de '||v_valparc,lRaise,false,false);
      end if;

      -- insere na tabela totrec o registro atual com o valor da parcela
      execute 'insert into totrec values (' || v_record_recpar.receit || ', ' || v_record_recpar.parcela || ', ' || v_valparc || ')';

      if lRaise is true then
        perform fc_debug('1 - parcela: '||v_record_recpar.parcela||' - valor: '||v_valparc,lRaise,false,false);
      end if;

      if lRaise is true then
        perform fc_debug('k00_numcgm: '||v_cgmpri||' - k00_receit: '||v_record_recpar.receit||' - k00_hist: '||v_record_recpar.hist||' - k00_valor: '||v_valparc||' - k00_dtvenc: '||v_vcto||' - k00_numpre: '||v_numpre||' - k00_numpar: '||v_record_recpar.parcela||' - k00_numtot: '||v_totalparcelas||' - k00_tipo: '||v_tiponovo,lRaise,false,false);
      end if;

      v_recdestino = v_record_recpar.receit;

      if lRaise is true then
        perform fc_debug('   no arrecad... val: '||v_valparc||' - recdest: '||v_recdestino||' - vcto: '||v_vcto||' - parcela: '||v_record_recpar.parcela,lRaise,false,false);
      end if;

      if v_valparc < 0 then
        return '[10] - valor da parcela ' || v_record_recpar.parcela || ' menor que zero: ' || v_valparc;
      elsif v_valparc = 0 then
        return '[11] - valor da parcela ' || v_record_recpar.parcela || ' zerada: ' || v_valparc;
      end if;

      -- se valor da parcela maior que zero
      -- insere no arrecad

      if lRaise is true then
        perform fc_debug('k00_numpre : '||v_numpre||' k00_numpar : '||v_record_recpar.parcela||' k00_receit : '||v_recdestino||' k00_valor : '||v_valparc,lRaise,false,false);
      end if;

      lGravaArrecad = true;

      if v_valparc > 0 then

        if lSeparaJuroMulta = 1 then

          if lRaise is true then

             perform fc_debug('',lRaise,false,false);
             perform fc_debug('+--------------------------------------------------------------------------------------------',lRaise,false,false);
             perform fc_debug('|                                                                                            ',lRaise,false,false);
             perform fc_debug('|      Processando dados da composicao do Numpre: '||v_numpre||' Parcela: '||v_record_recpar.parcela||' Receita: '||v_recdestino,lRaise,false,false);
             perform fc_debug('|                                                                                            ',lRaise,false,false);
             perform fc_debug('+--------------------------------------------------------------------------------------------',lRaise,false,false);
             perform fc_debug('',lRaise,false,false);

          end if;


          iSeqArrecKey := nextval('arreckey_k00_sequencial_seq');

          if lRaise is true then
             perform fc_debug('     ',lRaise,false,false);
             perform fc_debug('     1. G E R A N D O  D A D O S  N A  T A B E L A  ARRECKEY  P A R A  A  P A R C E L A: '||v_record_recpar.parcela,lRaise,false,false);
             perform fc_debug('        Sequencial: '||iSeqArrecKey||' Numpre: '||v_numpre||' Numpar: '||v_record_recpar.parcela||' Receita: '||v_recdestino||' Historico: '||v_record_recpar.hist||' Tipo: '||v_tiponovo,lRaise,false,false);
             perform fc_debug('',lRaise,false,false);
          end if;

          insert into arreckey ( k00_sequencial,
                                 k00_numpre,
                                 k00_numpar,
                                 k00_receit,
                                 k00_hist,
                                 k00_tipo )
                        values ( iSeqArrecKey,
                                 v_numpre,
                                 v_record_recpar.parcela,
                                 v_recdestino,
                                 v_record_recpar.hist,
                                 v_tiponovo
                               );


          select round(sum(valhis),2),
                 round(sum(valcor-descor-valhis),2),
                 round(sum(valjur-descjur),2),
                 round(sum(valmul-descmul),2)
            into nVlrTotalHistorico,
                 nVlrTotalCorrecao,
                 nVlrTotalJuros,
                 nVlrTotalMulta
            from w_base_parcelas
           where receit = v_record_recpar.receit;

          select round(sum(vlrdesccor),2),
                 round(sum(vlrdescjur),2),
                 round(sum(vlrdescmul),2)
            into nVlrTotalDescontoCorrigido,
                 nVlrTotalDescontoJuros,
                 nVlrTotalDescontoMulta
            from arrecad_parc_rec;

          if lRaise is true then
            perform fc_debug('     ',lRaise,false,false);
            perform fc_debug('     2. C A L C U L A N D O  V A L O R E S  D A  C O M P O S I C A O  D A  P A R C E L A',lRaise,false,false);
            perform fc_debug('     ',lRaise,false,false);
            perform fc_debug('        Valores Totais do Debito:  ',lRaise,false,false);
            perform fc_debug('        Total Historico(nVlrTotalHistorico) ..: '||nVlrTotalHistorico,lRaise,false,false);
            perform fc_debug('        Total Correcao(nVlrTotalCorrecao) ....: '||nVlrTotalCorrecao,lRaise,false,false);
            perform fc_debug('        Total Juros(nVlrTotalJuros) ..........: '||nVlrTotalJuros,lRaise,false,false);
            perform fc_debug('        Total Multa(nVlrTotalMulta) ..........: '||nVlrTotalMulta,lRaise,false,false);
            perform fc_debug('        v_somar(???): '||v_somar,lRaise,false,false);
            perform fc_debug('     ',lRaise,false,false);
          end if;

          v_historico_compos = v_record_recpar.valhis;
          v_correcao_compos  = ( v_record_recpar.valcor - v_record_recpar.descor - v_record_recpar.valhis );
          v_juros_compos     = ( v_record_recpar.valjur - v_record_recpar.descjur );
          v_multa_compos     = ( v_record_recpar.valmul - v_record_recpar.descmul );

          if lRaise is true then
            perform fc_debug('        Parcela: '||v_record_recpar.parcela||' - Receita: '||v_record_recpar.receit,lRaise,false,false);
            perform fc_debug('        Valor da Parcela(v_valparc) ........................: '||v_valparc,lRaise,false,false);
            perform fc_debug('        Valor historico da Composicao(v_historico_compos) ..: '||v_historico_compos,lRaise,false,false);
            perform fc_debug('        Valor corrigido da Composicao(v_correcao_compos) ...: '||v_correcao_compos,lRaise,false,false);
            perform fc_debug('        Valor juros da Composicao(v_juros_compos) ..........: '||v_juros_compos,lRaise,false,false);
            perform fc_debug('        Valor multa da Composicao(v_multa_compos) ..........: '||v_multa_compos,lRaise,false,false);
          end if;

          --
          --
          -- Caso seja a ultima parcela do parcelamento realizamos a verificação nos valores gerados para a composição das parcelas
          -- Se encontrar alguma diferenca é realizado o processamento do ajuste da composicao
          --
          if v_record_recpar.parcela = v_totparcdestarec then

             if lRaise is true then
                perform fc_debug('       ',lRaise,false,false);
                perform fc_debug('       >> U L T I M A  P A R C E L A  D O  P A R C E L A M E N T O <<',lRaise,false,false);
                perform fc_debug('       2.1  VERIFICANDO E PROCESSANDO CORRECAO NAS DIFERENCAS DE VALORES(ARREDONDAMENTO) ',lRaise,false,false);
                perform fc_debug('       ',lRaise,false,false);
             end if;

             --
             -- Verificamos os valores já gerados para a composicao do débito somando com o valor que será gerado para esta parcela e receita
             --
             select sum(k00_vlrhist)+v_historico_compos,
                    sum(k00_correcao)+v_correcao_compos,
                    sum(k00_juros)+v_juros_compos,
                    sum(k00_multa)+v_multa_compos
               into nVlrHistoricoComposicao,
                    nVlrCorrecaoComposicao,
                    nVlrJurosComposicao,
                    nVlrMultaComposicao
               from arrecadcompos
              inner join arreckey on arreckey.k00_sequencial = arrecadcompos.k00_arreckey
              where k00_numpre = v_numpre;

              --
              -- Verificamos o total do valor de origem do parcelamento sem alterações e aplicações de regra.
              --
              select sum(k00_vlrhis),
                     sum(k00_vlrcor-k00_vlrhis),
                     sum(k00_juros),
                     sum(k00_multa),
                     sum(k00_desconto),
                     sum(k00_total)
                into nVlrTotalParcelamentoHistorico,
                     nVlrTotalParcelamentoCorrigido,
                     nVlrTotalParcelamentoJuros,
                     nVlrTotalParcelamentoMulta,
                     nVlrTotalParcelamento
                from totalportipo;

              --
              -- Calculamos os valores de composicao Total e Diferencas
              --
              nVlrTotalComposicao              := (nVlrHistoricoComposicao+nVlrCorrecaoComposicao+nVlrJurosComposicao+nVlrMultaComposicao);

              nVlrDiferencaComposicaoHistorico := nVlrTotalParcelamentoHistorico - nVlrHistoricoComposicao;
              nVlrDiferencaComposicaoCorrecao  := nVlrTotalParcelamentoCorrigido - nVlrCorrecaoComposicao - nVlrTotalDescontoCorrigido;
              nVlrDiferencaComposicaoJuros     := nVlrTotalParcelamentoJuros     - nVlrJurosComposicao    - nVlrTotalDescontoJuros;
              nVlrDiferencaComposicaoMulta     := nVlrTotalParcelamentoMulta     - nVlrMultaComposicao    - nVlrTotalDescontoMulta;

              nVlrDiferencaComposicaoTotal     := round(abs(nVlrDiferencaComposicaoHistorico)+abs(nVlrDiferencaComposicaoCorrecao)+abs(nVlrDiferencaComposicaoJuros)+abs(nVlrDiferencaComposicaoMulta),2);

              if lRaise is true then
                 perform fc_debug('         Valores gerados no processamento da composicao: ',lRaise,false,false);
                 perform fc_debug('         nVlrTotalHistorico ............: '||nVlrHistoricoComposicao,lRaise,false,false);
                 perform fc_debug('         nVlrTotalCorrecao .............: '||nVlrCorrecaoComposicao,lRaise,false,false);
                 perform fc_debug('         nVlrTotalJuros ................: '||nVlrJurosComposicao,lRaise,false,false);
                 perform fc_debug('         nVlrTotalMulta ................: '||nVlrMultaComposicao,lRaise,false,false);
                 perform fc_debug('         ---------------------------------------',lRaise,false,false);
                 perform fc_debug('         Total da Composicao ..........: '||nVlrTotalComposicao,lRaise,false,false);
                 perform fc_debug('         Total do Parcelamento ........: '||nVlrTotalParcelamento,lRaise,false,false);
                 perform fc_debug(' ',lRaise,false,false);
                 perform fc_debug('         Valores das diferencas encontradas: ',lRaise,false,false);
                 perform fc_debug('         Diferenca no Vlr. Historico ..: '||nVlrDiferencaComposicaoHistorico,lRaise,false,false);
                 perform fc_debug('         Diferenca no Vlr. Corrigido ..: '||nVlrDiferencaComposicaoCorrecao,lRaise,false,false);
                 perform fc_debug('         Diferenca no Vlr. dos Juros ..: '||nVlrDiferencaComposicaoJuros,lRaise,false,false);
                 perform fc_debug('         Diferenca no Vlr. da Multa ...: '||nVlrDiferencaComposicaoMulta,lRaise,false,false);
                 perform fc_debug('         ---------------------------------------',lRaise,false,false);
                 perform fc_debug('         Total da Diferenca (abs) .....: '||nVlrDiferencaComposicaoTotal,lRaise,false,false);
              end if;

              --
              -- Caso seja encontrada diferenca na composicao do débito com o total parcelado
              -- Realizamos os ajustes necessarios nos valores onde existem diferenca, se o valor da diferenca existir e não for maior que 1.
              --
              if abs(nVlrDiferencaComposicaoTotal) between 0.01 and 1.00 then

                 if lRaise is true then
                     perform fc_debug('',lRaise,false,false);
                     perform fc_debug('         >> Processando acerto da diferenca da composicao <<',lRaise,false,false);
                 end if;

                 if abs(nVlrDiferencaComposicaoHistorico) <> 0 then

                  if lRaise is true then
                     perform fc_debug('            - Corrigindo diferenca no valor Historico de '||nVlrDiferencaComposicaoHistorico,lRaise,false,false);
                  end if;
                  v_historico_compos := v_historico_compos+nVlrDiferencaComposicaoHistorico;
                 end if;

                 if abs(nVlrDiferencaComposicaoCorrecao) <> 0 then

                  if lRaise is true then
                     perform fc_debug('            - Corrigindo diferenca no valor Corrigido de '||nVlrDiferencaComposicaoCorrecao,lRaise,false,false);
                  end if;
                  v_correcao_compos := v_correcao_compos+nVlrDiferencaComposicaoCorrecao;
                 end if;

                 if abs(nVlrDiferencaComposicaoJuros) <> 0 then

                  if lRaise is true then
                     perform fc_debug('            - Corrigindo diferenca no valor dos Juros de '||nVlrDiferencaComposicaoJuros,lRaise,false,false);
                  end if;
                  v_juros_compos := v_juros_compos+nVlrDiferencaComposicaoJuros;

                 end if;

                 if abs(nVlrDiferencaComposicaoMulta) <> 0 then

                  if lRaise is true then
                     perform fc_debug('            - Corrigindo diferenca no valor da Multa de '||nVlrDiferencaComposicaoMulta,lRaise,false,false);
                  end if;
                  v_multa_compos := v_multa_compos+nVlrDiferencaComposicaoMulta;

                 end if;

                 --
                 --
                 -- Se a variável de sessão db_debugon estiver setada, verificamos os valores finais gerados para a composição do débito
                 -- Essa verificação é realizada buscando os valores já gerados somando com o valor da receita que será cadastrado já com
                 -- os ajustes de valores.
                 --
                 if lRaise is true then

                    select sum(k00_vlrhist)  + v_historico_compos,
                           sum(k00_correcao) + v_correcao_compos,
                           sum(k00_juros)    + v_juros_compos,
                           sum(k00_multa)    + v_multa_compos
                      into nVlrHistoricoComposicao,
                           nVlrCorrecaoComposicao,
                           nVlrJurosComposicao,
                           nVlrMultaComposicao
                      from arrecadcompos
                     inner join arreckey on arreckey.k00_sequencial = arrecadcompos.k00_arreckey
                     where k00_numpre = v_numpre;

                    nVlrTotalComposicao              := (nVlrHistoricoComposicao+nVlrCorrecaoComposicao+nVlrJurosComposicao+nVlrMultaComposicao);

                    nVlrDiferencaComposicaoHistorico := nVlrTotalParcelamentoHistorico - nVlrHistoricoComposicao;
                    nVlrDiferencaComposicaoCorrecao  := nVlrTotalParcelamentoCorrigido - nVlrCorrecaoComposicao - nVlrTotalDescontoCorrigido;
                    nVlrDiferencaComposicaoJuros     := nVlrTotalParcelamentoJuros     - nVlrJurosComposicao    - nVlrTotalDescontoJuros;
                    nVlrDiferencaComposicaoMulta     := nVlrTotalParcelamentoMulta     - nVlrMultaComposicao    - nVlrTotalDescontoMulta;

                    nVlrDiferencaComposicaoTotal     := round(abs(nVlrDiferencaComposicaoHistorico)+abs(nVlrDiferencaComposicaoCorrecao)+abs(nVlrDiferencaComposicaoJuros)+abs(nVlrDiferencaComposicaoMulta),2);

                    perform fc_debug('         Valores gerados no processamento da composicao apos o acerto das diferencas: ',lRaise,false,false);
                    perform fc_debug('         nVlrTotalHistorico ............: '||nVlrHistoricoComposicao,lRaise,false,false);
                    perform fc_debug('         nVlrTotalCorrecao .............: '||nVlrCorrecaoComposicao,lRaise,false,false);
                    perform fc_debug('         nVlrTotalJuros ................: '||nVlrJurosComposicao,lRaise,false,false);
                    perform fc_debug('         nVlrTotalMulta ................: '||nVlrMultaComposicao,lRaise,false,false);
                    perform fc_debug('         ---------------------------------------',lRaise,false,false);
                    perform fc_debug('         Total da Composicao ..........: '||nVlrTotalComposicao,lRaise,false,false);
                    perform fc_debug('         Total do Parcelamento ........: '||nVlrTotalParcelamento,lRaise,false,false);
                    perform fc_debug(' ',lRaise,false,false);
                    perform fc_debug('         Valores das diferencas encontradas: ',lRaise,false,false);
                    perform fc_debug('         Diferenca no Vlr. Historico ..: '||nVlrDiferencaComposicaoHistorico,lRaise,false,false);
                    perform fc_debug('         Diferenca no Vlr. Corrigido ..: '||nVlrDiferencaComposicaoCorrecao,lRaise,false,false);
                    perform fc_debug('         Diferenca no Vlr. dos Juros ..: '||nVlrDiferencaComposicaoJuros,lRaise,false,false);
                    perform fc_debug('         Diferenca no Vlr. da Multa ...: '||nVlrDiferencaComposicaoMulta,lRaise,false,false);
                    perform fc_debug('         ---------------------------------------',lRaise,false,false);
                    perform fc_debug('         Total da Diferenca (abs) .....: '||nVlrDiferencaComposicaoTotal,lRaise,false,false);

                 end if;

              end if;

          end if;

          iSeqArrecadcompos := nextval('arrecadcompos_k00_sequencial_seq');
          insert into arrecadcompos ( k00_sequencial,
                                      k00_arreckey,
                                      k00_vlrhist,
                                      k00_correcao,
                                      k00_juros,
                                      k00_multa )
                             values ( iSeqArrecadcompos,
                                      iSeqArrecKey,
                                      v_historico_compos,
                                      v_correcao_compos,
                                      v_juros_compos,
                                      v_multa_compos );

          if lRaise is true then

            perform fc_debug('',lRaise,false,false);
            perform fc_debug('     3. I N S E R I N D O  R E G I S T R O S  D E  C O M P O S I C A O (ArrecadCompos)',lRaise,false,false);
            perform fc_debug('        Cod. Arreckey(k00_arreckey): '||iSeqArrecKey||' Numpre: '||v_numpre||' Parcela: '||v_record_recpar.parcela||' Receita: '||v_recdestino,lRaise,false,false);
            perform fc_debug('',lRaise,false,false);

          end if;

          if v_historico_compos = 0 and v_correcao_compos = 0 and v_juros_compos = 0 and v_multa_compos = 0 then
            v_valparc = 0;
            lGravaArrecad = false;
          else
            v_valparc = round(v_historico_compos,2);
          end if;

          if lRaise is true then

             perform fc_debug('',lRaise,false,false);
             perform fc_debug('+--------------------------------------------------------------------------------------------',lRaise,false,false);
             perform fc_debug('|                                                                                            ',lRaise,false,false);
             perform fc_debug('|      Fim do processamento da composicao do Numpre: '||v_numpre||' Parcela: '||v_record_recpar.parcela||' Receita: '||v_recdestino,lRaise,false,false);
             perform fc_debug('|                                                                                            ',lRaise,false,false);
             perform fc_debug('+--------------------------------------------------------------------------------------------',lRaise,false,false);
             perform fc_debug('',lRaise,false,false);

          end if;

        end if;

        if lRaise is true then
            perform fc_debug(' ',lRaise,false,false);
            perform fc_debug('Inserindo dados da parcela no Arrecad',lRaise,false,false);
            perform fc_debug('Numpre: '||v_numpre||' Numpar: '||v_record_recpar.parcela||' Receita: '||v_recdestino||' Valor: '||v_valparc||' - Round: '||round(v_valparc,2),lRaise,false,false);
            perform fc_debug(' ',lRaise,false,false);
        end if;

        if lSeparaJuroMulta = 2 then

          if (round(v_valparc,2) <= 0 or v_valparc is null) then
            return '[12] - valor da parcela ' || trim(to_char(v_record_recpar.parcela, '999')) || ' zerada ou em branco! Contate suporte';
          end if;

        end if;

        if lGravaArrecad is true then

          insert into arrecad (k00_numcgm,
                               k00_dtoper,
                               k00_receit,
                               k00_hist,
                               k00_valor,
                               k00_dtvenc,
                               k00_numpre,
                               k00_numpar,
                               k00_numtot,
                               k00_numdig,
                               k00_tipo,
                               k00_tipojm)
                       values (v_cgmpri,
                               dDataUsu,
                               v_recdestino,
                               v_record_recpar.hist,
                               round(v_valparc,2),
                               v_vcto,
                               v_numpre,
                               v_record_recpar.parcela,
                               v_totalparcelas,
                               0,
                               v_tiponovo,
                               0);

          select k00_valor
            into v_teste
            from arrecad
           where k00_numpre = v_numpre
             and k00_numpar = v_record_recpar.parcela
             and k00_receit = v_recdestino;

          if lRaise is true then
            perform fc_debug('Dados inseridos na Arrecad: Valor: '||v_valparc||' - Round: '||round(v_valparc,2)||' - Teste(Valor inserido no Arrecad): '||v_teste,lRaise,false,false);
          end if;

        end if;

        if lRaise is true then
          perform fc_debug(' ',lRaise,false,false);
          perform fc_debug(' ',lRaise,false,false);
        end if;

      else
        perform fc_debug('Valor da parcela(v_valparc) menor ou igual a zero: '||v_valparc,lRaise,false,false);
      end if;

      if lRaise is true then
        perform fc_debug('Receita Origem: '||v_record_recpar.receit||' - Receita Destino: '||v_recdestino,lRaise,false,false);
        perform fc_debug('Receita: '||v_record_recpar.receit||' - Qtd Total de Parcelas da Receita: '||v_totparcdestarec,lRaise,false,false);
      end if;

      -- conta a quantidade total de parcelas desta receita
      select count(*)
        into v_totparcdestarec
        from parcelas
       where receit = v_record_recpar.receit;

      if lRaise is true then
        perform fc_debug('Receita: '||v_record_recpar.receit||' - Qtd Total de Parcelas da Receita: '||v_totparcdestarec,lRaise,false,false);
      end if;

      --
      -- Se parcela atual for igual a ultima parcela desta receita
      -- reinicia as variaveis com os dados especificados na CGF para o vencimento da parcela 2
      --
      if v_record_recpar.parcela = v_totparcdestarec then

        select extract (year from v_segvenc)
          into v_anovenc;

        select extract (month from v_segvenc)
          into v_mesvenc;

      end if;

    end loop;

    if lRaise is true then
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
        perform fc_debug(' ',lRaise,false,false);
    end if;

    if lRaise is true then

      -- mostra os valores por parcela do arrecad, apenas para conferencia
      for v_record_recpar in select k00_numpar,
                                    sum(k00_valor)
                               from arrecad
                              where k00_numpre = v_numpre
                              group by k00_numpar
      loop

        if lRaise is true then
          perform fc_debug('2 - parcela: '||v_record_recpar.k00_numpar||' - valor: '||v_record_recpar.sum,lRaise,false,false);
        end if;

      end loop;

    end if;

    -- sum do campo valor
    select sum(valor)
      into nValorTotalOrigem
      from w_base_parcelas;

    for rPercOrigem in select numpre,
                              numpar,
                              receit,
                              sum(valor) as valor
                         from arrecad_parc_rec
                        group by numpre, numpar, receit
    loop

      nPercCalc := ( ( rPercOrigem.valor / nValorTotalOrigem ) * 100 );

      --raise notice 'valor: % - nValorTotalOrigem: % - PercCalcComRound: %', rPercOrigem.valor, nValorTotalOrigem, nPercCalc ;

      perform sum(k00_perc)
         from ( select k00_matric as k00_origem,
                       coalesce(k00_perc, 100) as k00_perc,
                       1 as tipo
                  from arrematric
                 where k00_numpre = rPercOrigem.numpre
                 union
                select k00_inscr as k00_origem,
                       coalesce(k00_perc, 100) as k00_perc,
                       2 as tipo
                  from arreinscr
                 where k00_numpre = rPercOrigem.numpre
                union
                select 0   as k00_origem,
                       100 as k00_perc,
                       3   as tipo
                  from arrenumcgm
                       left join arrematric on arrematric.k00_numpre = arrenumcgm.k00_numpre
                       left join arreinscr  on arreinscr.k00_numpre  = arrenumcgm.k00_numpre
                 where arrematric.k00_numpre is null
                   and arreinscr.k00_numpre  is null
                   and arrenumcgm.k00_numpre = rPercOrigem.numpre
              ) as x
       having cast(round(sum(k00_perc),2) as numeric) <> cast(100 as numeric);
      if found then
          return '[13] - Inconsistencia no percentual da origem - numpre: ' || rPercOrigem.numpre;
      end if;

      for v_record_perc in select k00_matric              as k00_origem,
                                  coalesce(k00_perc, 100) as k00_perc,
                                  1                       as tipo
                             from arrematric
                            where k00_numpre = rPercOrigem.numpre
                            union
                           select k00_inscr               as k00_origem,
                                  coalesce(k00_perc, 100) as k00_perc,
                                  2                       as tipo
                             from arreinscr
                            where k00_numpre = rPercOrigem.numpre
                            union
                           select 0   as k00_origem,
                                  100 as k00_perc,
                                  3   as tipo
                             from arrenumcgm
                             left join arrematric on arrematric.k00_numpre = arrenumcgm.k00_numpre
                             left join arreinscr  on arreinscr.k00_numpre  = arrenumcgm.k00_numpre
                            where arrematric.k00_numpre is null
                              and arreinscr.k00_numpre  is null
                              and arrenumcgm.k00_numpre = rPercOrigem.numpre
      loop

        if lRaise then
          perform fc_debug('---------------------------------------------------------------',lRaise,false,false);
          perform fc_debug('numpre: '||rPercOrigem.numpre||' - perc: '||v_record_perc.k00_perc||' - tipo: '||v_record_perc.tipo||' - percentual por registro: '||nPercCalc,lRaise,false,false);
          perform fc_debug('---------------------------------------------------------------',lRaise,false,false);
        end if;

        if v_record_perc.tipo = 1 then

            execute 'insert into arrecad_parc_rec_perc values ('|| rPercOrigem.numpre                        || ','
                                                                || rPercOrigem.numpar                        || ','
                                                                || rPercOrigem.receit                        || ','
                                                                || v_record_perc.k00_origem                  || ','
                                                                || nPercCalc * v_record_perc.k00_perc / 100  || ','
                                                                || 0                                         || ','
                                                                || 0                                         || ','
                                                                || 0                                         || ','
                                                                || v_record_perc.tipo                        || ');';
        elsif v_record_perc.tipo = 2 then

            execute 'insert into arrecad_parc_rec_perc values (' || rPercOrigem.numpre                        || ','
                                                                 || rPercOrigem.numpar                        || ','
                                                                 || rPercOrigem.receit                        || ','
                                                                 || 0                                         || ','
                                                                 || 0                                         || ','
                                                                 || v_record_perc.k00_origem                  || ','
                                                                 || nPercCalc * v_record_perc.k00_perc / 100  || ','
                                                                 || 0                                         || ','
                                                                 || v_record_perc.tipo                        || ');';

        elsif v_record_perc.tipo = 3 then

            execute 'insert into arrecad_parc_rec_perc values (' || rPercOrigem.numpre                        || ','
                                                                 || rPercOrigem.numpar                        || ','
                                                                 || rPercOrigem.receit                        || ','
                                                                 || 0                                         || ','
                                                                 || 0                                         || ','
                                                                 || 0                                         || ','
                                                                 || 0                                         || ','
                                                                 || nPercCalc * v_record_perc.k00_perc / 100  || ','
                                                                 || v_record_perc.tipo                        || ');';
        end if;

      end loop;

    end loop;

    /**
     * Somamos o percentual virtual do cgm para distribui-lo entre as origens (Matricula e Inscricao)
     */
    select coalesce(sum(perccgm), 0)
      into nPercentualVirtualCgm
      from arrecad_parc_rec_perc
     where percmatric = 0
       and percinscr  = 0;

    select count(*)
      into iQtdRegistrosMatricula
      from arrecad_parc_rec_perc
     where tipo = 1;

    select count(*)
      into iQtdRegistrosInscricao
      from arrecad_parc_rec_perc
     where tipo = 2;


    if ( ((iQtdRegistrosMatricula + iQtdRegistrosInscricao) > 0) and (nPercentualVirtualCgm > 0) ) then

      nDiferencaPercentualCGM = coalesce((nPercentualVirtualCgm / (iQtdRegistrosMatricula + iQtdRegistrosInscricao)), 0);

      if lRaise then
        perform fc_debug('nDiferencaPercentualCGM' || nDiferencaPercentualCGM, lRaise, false, false);
      end if;

      update arrecad_parc_rec_perc
         set percmatric = percmatric + nDiferencaPercentualCGM
       where tipo = 1 ;

      update arrecad_parc_rec_perc
         set percinscr = percinscr + nDiferencaPercentualCGM
       where tipo = 2 ;

      update arrecad_parc_rec_perc
         set perccgm = 0
       where tipo = 3;

    end if;


    /**
     * Calculamos a diferenca no valor percentual entre o somatorio de todas as origens (Matricula e Inscricao)
     */
    select 100 - (sum(percmatric) + sum(percinscr))
      into nDiferencaPercentualAjuste
      from arrecad_parc_rec_perc
     where tipo in (1, 2);

    /**
     * Se existir diferenca no percentual
     * Ajustamos a diferenca no arredondamento no primeiro registro encontrado
     */
    if nDiferencaPercentualAjuste <> cast(0 as numeric(15, 10)) then

      if lRaise then
        perform fc_debug('---------------------------------------------------------------',lRaise,false,false);
        perform fc_debug('Valor da diferenca de arredondamento: '||nDiferencaPercentualAjuste,lRaise,false,false);
        perform fc_debug('---------------------------------------------------------------',lRaise,false,false);
      end if;

      for rAjusteDiferencaPercentual in select *
                                          from arrecad_parc_rec_perc
                                         where tipo <> 3
                                         limit 1
      loop

        if rAjusteDiferencaPercentual.tipo = 1 then

          update arrecad_parc_rec_perc
             set percmatric = percmatric + (select 100 - (sum(percmatric) + sum(percinscr))
                                              from arrecad_parc_rec_perc
                                             where tipo in (1, 2))
           where numpre = rAjusteDiferencaPercentual.numpre
             and numpar = rAjusteDiferencaPercentual.numpar
             and receit = rAjusteDiferencaPercentual.receit
             and matric = rAjusteDiferencaPercentual.matric;

        elsif rAjusteDiferencaPercentual.tipo = 2 then

          update arrecad_parc_rec_perc
             set percinscr = percinscr + (select 100 - (sum(percmatric) + sum(percinscr))
                                              from arrecad_parc_rec_perc
                                             where tipo in (1, 2))
           where numpre = rAjusteDiferencaPercentual.numpre
             and numpar = rAjusteDiferencaPercentual.numpar
             and receit = rAjusteDiferencaPercentual.receit
             and inscr  = rAjusteDiferencaPercentual.inscr;
        end if;

      end loop;

    end if;

    nSomaPercMatric = 0;
    nTotArreMatric  = 0;

    select sum(percmatric)
      into nTotArreMatric
      from arrecad_parc_rec_perc;

    for rPercOrigem in select matric,
                              sum(percmatric) as k00_perc,
                              tipo
                         from arrecad_parc_rec_perc
                        where matric > 0
                        group by matric,tipo
    loop

      if lRaise then
        perform fc_debug('---------------------------------------------------------------',lRaise,false,false);
        perform fc_debug('matric: '||rPercOrigem.matric||' - perc: '||rPercOrigem.k00_perc||' numpre : '||v_numpre ,lRaise,false,false);
        perform fc_debug('---------------------------------------------------------------',lRaise,false,false);
      end if;

      -- tipo = 3 quer dizer que nao tem origem de matricula ou inscricao
      -- (o numpre origem esta somente na arrenumcgm ou seja nao precisa gravar percentual na arrematric ou arreinscr)
      if rPercOrigem.tipo <> 3 then
              insert into arrematric (k00_matric,
                                      k00_numpre,
                                      k00_perc)
                              values (rPercOrigem.matric,
                                      v_numpre,
                                      rPercOrigem.k00_perc);
      end if;

       v_totalzao       := v_totalzao + rPercOrigem.k00_perc;
       nSomaPercMatric  := nSomaPercMatric + rPercOrigem.k00_perc;

    end loop;

    if lRaise then
      perform fc_debug('v_totalzao (1): '||v_totalzao,lRaise,false,false);
    end if;

    nSomaPercInscr = 0;
    nTotArreInscr  = 0;

    select sum(percinscr)
      into nTotArreInscr
      from arrecad_parc_rec_perc;

    for rPercOrigem in select inscr,
                              sum(percinscr) as k00_perc,
                              tipo
                         from arrecad_parc_rec_perc
                        where inscr > 0
                        group by inscr,tipo
    loop

      if lRaise then
        raise info 'inscr: % - perc: % numpre : % ',rPercOrigem.inscr, rPercOrigem.k00_perc, v_numpre;
      end if;

      if lRaise then
        perform fc_debug('---------------------------------------------------------------',lRaise,false,false);
        perform fc_debug('inscr: '||rPercOrigem.inscr||' - perc: '||rPercOrigem.k00_perc||' numpre : '||v_numpre,lRaise,false,false);
        perform fc_debug('---------------------------------------------------------------',lRaise,false,false);
      end if;

      -- tipo = 3 quer dizer que nao tem origem de matricula ou inscricao
      -- (o numpre origem esta somente na arrenumcgm ou seja nao precisa gravar percentual na arrematric ou arreinscr)
      if rPercOrigem.tipo <> 3 then
            insert into arreinscr (k00_inscr,
                                   k00_numpre,
                                   k00_perc)
                           values (rPercOrigem.inscr,
                                   v_numpre,
                                   rPercOrigem.k00_perc);
      end if;

      v_totalzao      := v_totalzao + rPercOrigem.k00_perc;
      nSomaPercInscr  := nSomaPercInscr + rPercOrigem.k00_perc;

    end loop;

    if lRaise then
      perform fc_debug('v_totalzao (2): '||v_totalzao,lRaise,false,false);
      perform fc_debug('nTotArreInscr : '|| nTotArreInscr || 'nSomaPercInscr : ' || nSomaPercInscr || 'TOTAL: ' ||(nTotArreInscr-nSomaPercInscr) );
    end if;

    if lRaise then
      perform fc_debug('v_totalzao (3): '||v_totalzao,lRaise,false,false);
    end if;

    for rPercOrigem in select numpre,
                              sum(perccgm) as k00_perc
                         from arrecad_parc_rec_perc
                        where tipo = 3
                        group by numpre
    loop

      if lRaise then
         perform fc_debug('---------------------------------------------------------------',lRaise,false,false);
         perform fc_debug(' por cgm -- numpre -- '||rPercOrigem.k00_perc||' percentual -- '||rPercOrigem.numpre,lRaise,false,false);
         perform fc_debug('---------------------------------------------------------------',lRaise,false,false);
      end if;

      v_totalzao := v_totalzao + rPercOrigem.k00_perc;

    end loop;

    -- Corrige arredondamentos
    nPercCalc = 100.00 - v_totalzao;
    if nPercCalc < 0.5 then

       --
       -- Jogamos a diferença do percentual na Arreinscr quando:
       --  - Não existir vinculo com matricula
       --  - O percentual da inscrição for menor que o percentual da matricula
       --
       -- Jogamos a difença do percentual na Arrematric quando:
       -- - Não existir vinculo com a inscrição
       -- - O Percentual da matricula for menor que o percentual da inscrição
       --
      if lRaise then
        perform fc_debug('nPercCalc < 0.5 --------------------- nSomaPercInscr ...: '||nSomaPercInscr ,lRaise,false,false);
        perform fc_debug('nPercCalc < 0.5 --------------------- nSomaPercMatric...: '||nSomaPercMatric,lRaise,false,false);
        perform fc_debug('nPercCalc < 0.5 --------------------- nPercCalc.........: '||nPercCalc      ,lRaise,false,false);
        perform fc_debug('nPercCalc < 0.5 --------------------- v_totalzao........: '||v_totalzao     ,lRaise,false,false);
      end if;

       v_totalzao := v_totalzao + nPercCalc;
       if lRaise then
          perform fc_debug('v_totalzao (4): '||v_totalzao,lRaise,false,false);
       end if;

    end if;

    -- soma os percentuais da arrematric e arreinscr... nao esquecendo de que pode NAO ter registros em nenhuma das duas tabelas

    if lRaise is true then
      perform fc_debug(' Apos nPercCalc < 0.5 ---- total utilizado na comparacao final: '||v_total,lRaise,false,false);
      perform fc_debug(' Apos nPercCalc < 0.5 ---- totalzao ..........................: '||v_totalzao,lRaise,false,false);
    end if;

    if round(v_totalzao, 2)::numeric <> 100::numeric and round(v_totalzao, 2)::numeric <> 0::numeric then
      return '[14] - Erro calculando percentual entre as origens devedoras';
    end if;

    if lRaise is true then
      perform fc_debug('',lRaise,false,false);
      perform fc_debug(' Verificando percentuais na arrematric, arreinscr e arrenumcgm gerados para o numpre '||v_numpre,lRaise,false,false);
      perform fc_debug('',lRaise,false,false);
    end if;


    select sum(k00_perc)
      into nValidacaoPerc

       from ( select k00_matric as k00_origem,
                     coalesce(k00_perc, 100) as k00_perc,
                     1 as tipo
                from arrematric
               where k00_numpre = v_numpre
               union
              select k00_inscr as k00_origem,
                     coalesce(k00_perc, 100) as k00_perc,
                     2 as tipo
                from arreinscr
               where k00_numpre = v_numpre
              union
              select 0   as k00_origem,
                     100 as k00_perc,
                     3   as tipo
                from arrenumcgm
                     left join arrematric on arrematric.k00_numpre = arrenumcgm.k00_numpre
                     left join arreinscr  on arreinscr.k00_numpre  = arrenumcgm.k00_numpre
               where arrematric.k00_numpre is null
                 and arreinscr.k00_numpre  is null
                 and arrenumcgm.k00_numpre = v_numpre
            ) as x;

    --having cast(round(nValidacaoPerc +nPercentualVirtualCgm,2) as numeric) <> cast(100.00 as numeric);
    --raise notice 'nValidacaoPerc : % nPercentualVirtualCgm : % ',nValidacaoPerc,nPercentualVirtualCgm;

    --nValidacaoPerc := nValidacaoPerc + nPercentualVirtualCgm;
    nValidacaoPerc := nValidacaoPerc;

    perform fc_debug('----nValidacaoPerc        : ' || nValidacaoPerc);
    perform fc_debug('----nPercentualVirtualCgm : ' || nPercentualVirtualCgm);
    perform fc_debug('----v_totalzao : ' || v_totalzao);

    -- return 'final percentual 2';
    if round(nValidacaoPerc, 2) <> 100.00 then

        --return 'final : perc : '||round(nValidacaoPerc,2)||' numpre : '||v_numpre;

        --
        -- Verificamos se o problema é devido estar no parcelamento débitos que pertencem a matricula/inscrição e débitos sem vinculo com matricula/inscricao
        -- Se for encontrado um numpre que não esteja vinculado a matricula e a inscrição na origem, mostramos uma mensagem de erro diferenciada para facilitar a
        -- correção do caso. Geralmente a correção é realizada vinculando o numpre a uma matricula ou inscrição.
        --
        select array_to_string(array_accum( distinct arrecad_parc_rec.numpre),',')
          into sNumpreSemVinculoMatricInsc
          from arrecad_parc_rec
               left join arrematric on arrematric.k00_numpre = arrecad_parc_rec.numpre
               left join arreinscr  on arreinscr.k00_numpre  = arrecad_parc_rec.numpre
         where arrematric.k00_numpre is null
           and arreinscr.k00_numpre  is null;
        if sNumpreSemVinculoMatricInsc <> '' then
          return '[15] - Inconsistencia no percentual do débito gerado após o processamento do parcelamento - numpre: '||v_numpre||'. - Encontrados numpres que não possuem vinculo com Matricula/Inscrição. Numpres ['||sNumpreSemVinculoMatricInsc||']';
        else
          return '[15] - Inconsistencia no percentual do débito gerado após o processamento do parcelamento - numpre: '||v_numpre;
        end if;

    end if;

    if lRaise is true then
      perform fc_debug('',lRaise,false,false);
    end if;

    -- insere registros na arreparc
    -- agrupados por receita
    for v_record_receitas in  select receit,
                                     sum(vlrhis) as vlrhis,
                                     sum(vlrcor) as vlrcor,
                                     sum(vlrjur) as vlrjur,
                                     sum(vlrmul) as vlrmul,
                                     sum(vlrdes) as vlrdes,
                                     sum(valor)  as valor
                                from arrecad_parc_rec
                               group by receit
    loop

      if lRaise is true then
        perform fc_debug('receita: '||v_record_receitas.receit||' - valor: '||v_record_receitas.valor, lRaise,false,false);
      end if;

      insert into arreparc values (v_numpre,v_record_receitas.receit,v_record_receitas.valor / v_total * 100);

      nVlrHis := nVlrHis + v_record_receitas.vlrhis;
      nVlrCor := nVlrCor + v_record_receitas.vlrcor;
      nVlrJur := nVlrJur + v_record_receitas.vlrjur;
      nVlrMul := nVlrMul + v_record_receitas.vlrmul;
      nVlrDes := nVlrDes + v_record_receitas.vlrdes;

    end loop;

    if lRaise is true then
      perform fc_debug('',lRaise,false,false);
    end if;

    -- insere na termo
    insert into termo ( v07_parcel,
                        v07_dtlanc,
                        v07_valor,
                        v07_numpre,
                        v07_totpar,
                        v07_vlrpar,
                        v07_dtvenc,
                        v07_vlrent,
                        v07_datpri,
                        v07_vlrmul,
                        v07_vlrjur,
                        v07_perjur,
                        v07_permul,
                        v07_login,
                        v07_numcgm,
                        v07_hist,
                        v07_ultpar,
                        v07_desconto,
                        v07_desccor,
                        v07_descjur,
                        v07_descmul,
                        v07_situacao,
                        v07_instit,
                        v07_vlrhis,
                        v07_vlrcor,
                        v07_vlrdes )
               values ( v_termo,
                        dDataUsu,
                        v_total,
                        v_numpre,
                        v_totalparcelas,
                        v_valorparcelanew,
                        v_segvenc,
                        v_entrada,
                        v_privenc,
                        nVlrMul,
                        nVlrJur,
                        0,
                        0,
                        v_login,
                        v_cgmresp,
                        sObservacao,
                        v_valultimaparcelanew,
                        v_desconto,
                        v_descontocor,
                        v_descontojur,
                        v_descontomul,
                        1, -- Situacao Ativo
                        iInstit,
                        nVlrHis,
                        nVlrCor,
                        nVlrDes );

    -- se foi informado codigo do processo entao insere na termoprotprocesso
    if iProcesso is not null and iProcesso != 0  then

      if lRaise is true then
        perform fc_debug(' Insere na protprocesso  Processo : '||iProcesso,lRaise,false,false);
      end if;

      insert into termoprotprocesso (v27_sequencial,
                                     v27_termo,
                                     v27_protprocesso)
                             values (nextval('termoprotprocesso_v27_sequencial_seq'),
                                     v_termo,
                                     iProcesso);
    end if;

    -- se origem tiver parcelamento
    -- insere na termoreparc
    if lParcParc then
      if lRaise is true then
        perform fc_debug('v08_parcel: '||v_termo||' - v08_parcelorigem: '||v_termo_ori,lRaise,false,false);
      end if;

      for v_record_origem in select distinct v07_parcel
                               from termo
                                    inner join numpres_parc on termo.v07_numpre = numpres_parc.k00_numpre
      loop

        if lRaise is true then
          perform fc_debug('into termoreparc...',lRaise,false,false);
        end if;

        insert into termoreparc (v08_sequencial,
                                 v08_parcel,
                                 v08_parcelorigem)
                         values (nextval('termoreparc_v08_sequencial_seq'),
                                 v_termo,
                                 v_record_origem.v07_parcel);

      end loop;

    end if;

    if lRaise is true then
      perform fc_debug('v_totaldivida: '||v_totaldivida,lRaise,false,false);
    end if;

    /**
     * Deve ignorar a receita de juros e multa quando a forma utilizada no parcelamento
     * é juros e multa na ultima
     */
    -- insere na termodiv (obs o select da arrecad_parc_rec da um inner join com a divida so para inserir na termodiv quando a origem for divida)
    if v_juronaultima is true then

      insert into termodiv (parcel,
                            coddiv,
                            valor,
                            vlrcor,
                            juros,
                            multa,
                            desconto,
                            total,
                            vlrdesccor,
                            vlrdescjur,
                            vlrdescmul,
                            numpreant,
                            v77_perc)
                     select x.*,
                            x.valor / v_totaldivida * 100
                       from ( select v_termo,
                                     v01_coddiv,
                                     round(sum(vlrhis),2)::float8     as vlrhis,
                                     round(sum(vlrcor),2)::float8     as vlrcor,
                                     round(sum(vlrjur),2)::float8     as vlrjur,
                                     round(sum(vlrmul),2)::float8     as vlrmul,
                                     round(sum(vlrdes),2)::float8     as vlrdes,
                                     round(sum(valor),2)::float8      as valor,
                                     round(sum(vlrdesccor),2)::float8 as vlrdesccor,
                                     round(sum(vlrdescjur),2)::float8 as vlrdescjur,
                                     round(sum(vlrdescmul),2)::float8 as vlrdescmul,
                                     divida.v01_numpre
                                from arrecad_parc_rec
                                     inner join divida on divida.v01_numpre = arrecad_parc_rec.numpre
                                                      and divida.v01_numpar = arrecad_parc_rec.numpar
                               where tipo = 5
                                 and receit not in ( select distinct receit from arrecad_parc_rec where juro is true )
                            group by v01_coddiv, v01_numpre ) as x;
    else

      insert into termodiv (parcel,
                           coddiv,
                           valor,
                           vlrcor,
                           juros,
                           multa,
                           desconto,
                           total,
                           vlrdesccor,
                           vlrdescjur,
                           vlrdescmul,
                           numpreant,
                           v77_perc)
                    select x.*,
                           x.valor / v_totaldivida * 100
                      from ( select v_termo,
                                    v01_coddiv,
                                    round(sum(vlrhis),2)::float8     as vlrhis,
                                    round(sum(vlrcor),2)::float8     as vlrcor,
                                    round(sum(vlrjur),2)::float8     as vlrjur,
                                    round(sum(vlrmul),2)::float8     as vlrmul,
                                    round(sum(vlrdes),2)::float8     as vlrdes,
                                    round(sum(valor),2)::float8      as valor,
                                    round(sum(vlrdesccor),2)::float8 as vlrdesccor,
                                    round(sum(vlrdescjur),2)::float8 as vlrdescjur,
                                    round(sum(vlrdescmul),2)::float8 as vlrdescmul,
                                    divida.v01_numpre
                               from arrecad_parc_rec
                                    inner join divida on divida.v01_numpre = arrecad_parc_rec.numpre
                                                     and divida.v01_numpar = arrecad_parc_rec.numpar
                              where tipo = 5
                           group by v01_coddiv, v01_numpre ) as x;
    end if;

    -- mostra os valores com origem de divida ativa
    if lRaise is true then

      for v_record_numpres in select *
                                from termodiv
                               where parcel = v_termo
      loop

         perform fc_debug('coddiv: '||v_record_numpres.coddiv||' - vlcor: '||v_record_numpres.vlrcor||' - total: '||v_record_numpres.total||' - juro: '||v_record_numpres.juros||' - multa: '||v_record_numpres.multa,lRaise,false,false);

      end loop;

    end if;

    -- SE ORIGEM FOR DIVERSOS
    if lParcDiversos then

      if lRaise is true then
        perform fc_debug('inserindo em termodiver...',lRaise,false,false);
      end if;

      -- insere na termodiver
      insert into termodiver (dv10_parcel,
                              dv10_coddiver,
                              dv10_valor,
                              dv10_vlrcor,
                              dv10_juros,
                              dv10_multa,
                              dv10_desconto,
                              dv10_total,
                              dv10_numpreant,
                              dv10_vlrdescjur,
                              dv10_vlrdescmul,
                              dv10_perc)
                       select x.*,
                              x.valor/v_total
                         from ( select v_termo,
                                       dv05_coddiver,
                                       round(sum(vlrhis),2)::float8 as vlrhis,
                                       round(sum(vlrcor),2)::float8 as vlrcor,
                                       round(sum(vlrjur),2)::float8 as vlrjur,
                                       round(sum(vlrmul),2)::float8 as vlrmul,
                                       round(sum(vlrdes),2)::float8 as vlrdes,
                                       round(sum(valor),2)::float8  as valor,
                                       diversos.dv05_numpre,
                                       round(sum(vlrdescjur),2)::float8 as vlrdescjur,
                                       round(sum(vlrdescmul),2)::float8 as vlrdescmul
                                  from arrecad_parc_rec
                                       inner join diversos on diversos.dv05_numpre = arrecad_parc_rec.numpre
                                 group by dv05_coddiver, dv05_numpre
                              ) as x;
    end if;

    -- SE ORIGEM FOR CONTRIBUICAO DE MELHORIAS
    if lParcContrib then

      if lRaise is true then
        perform fc_debug('inserindo em termodiver...',lRaise,false,false);
      end if;

      -- insere na termodiver
      insert into termocontrib (parcel,
                                contricalc,
                                valor,
                                vlrcor,
                                juros,
                                multa,
                                desconto,
                                total,
                                numpreant,
                                vlrdescjur,
                                vlrdescmul,
                                perc)
                         select x.*,
                                x.valor/v_total
                           from ( select v_termo,
                                         d09_sequencial,
                                         round(sum(vlrhis),2)::float8 as vlrhis,
                                         round(sum(vlrcor),2)::float8 as vlrcor,
                                         round(sum(vlrjur),2)::float8 as vlrjur,
                                         round(sum(vlrmul),2)::float8 as vlrmul,
                                         round(sum(vlrdes),2)::float8 as vlrdes,
                                         round(sum(valor),2)::float8  as valor,
                                         contricalc.d09_numpre,
                                         round(sum(vlrdescjur),2)::float8 as vlrdescjur,
                                         round(sum(vlrdescmul),2)::float8 as vlrdescmul
                                    from arrecad_parc_rec
                                         inner join contricalc on contricalc.d09_numpre = arrecad_parc_rec.numpre
                                   group by d09_sequencial,d09_numpre
                                ) as x;
    end if;

    if lRaise is true then
      perform fc_debug('v_parcinicial: '||v_parcinicial,lRaise,false,false);
    end if;

    -- SE ORIGEM FOR INICIAL DO FORO
    if v_parcinicial is true then

      if lRaise is true then
         perform fc_debug('inserindo em termoini...',lRaise,false,false);
      end if;

      -- insere na termoini
      insert into termoini(parcel,
                           inicial,
                           valor,
                           vlrcor,
                           juros,
                           multa,
                           desconto,
                           total,
                           vlrdesccor,
                           vlrdescjur,
                           vlrdescmul,
                           v61_perc)
                    select x.*,
                           x.valor/v_total
                      from ( select v_termo,
                                    inicialnumpre.v59_inicial,
                                    round(sum(vlrhis),2)::float8 as vlrhis,
                                    round(sum(vlrcor),2)::float8 as vlrcor,
                                    round(sum(vlrjur),2)::float8 as vlrjur,
                                    round(sum(vlrmul),2)::float8 as vlrmul,
                                    round(sum(vlrdes),2)::float8 as vlrdes,
                                    round(sum(valor),2)::float8 as valor,
                                    round(sum(vlrdesccor),2)::float8 as vlrdesccor,
                                    round(sum(vlrdescjur),2)::float8 as vlrdescjur,
                                    round(sum(vlrdescmul),2)::float8 as vlrdescmul
                               from arrecad_parc_rec
                                    inner join inicialnumpre on inicialnumpre.v59_numpre = arrecad_parc_rec.numpre
                              group by inicialnumpre.v59_inicial
                           ) as x;

      for v_iniciais in select distinct v59_inicial
                                   from arrecad_parc_rec
                                 inner join inicialnumpre on inicialnumpre.v59_numpre = arrecad_parc_rec.numpre
                                 inner join inicial       on inicial.v50_inicial      = inicialnumpre.v59_inicial
                                                         and inicial.v50_situacao     = 1
      loop

        select nextval('inicialmov_v56_codmov_seq') into v_inicialmov;

        insert into inicialmov values (v_inicialmov,v_iniciais.v59_inicial,4,'',dDataUsu,v_login);
        update inicial set v50_codmov = v_inicialmov where v50_inicial = v_iniciais.v59_inicial;

      end loop;

    end if;

    -- Deletando os registros do arreold que estao incorretamente devido a bug
    -- da versao antiga da funcao fc_excluiparcelamento

    delete from arreold
          using arrecad_parc_rec
          where arreold.k00_numpre = arrecad_parc_rec.numpre
            and arreold.k00_numpar = arrecad_parc_rec.numpar
            and arreold.k00_receit = arrecad_parc_rec.receit;

    -- insere no arreold

    insert into arreold(k00_numcgm,
                        k00_dtoper,
                        k00_receit,
                        k00_hist,
                        k00_valor,
                        k00_dtvenc,
                        k00_numpre,
                        k00_numpar,
                        k00_numtot,
                        k00_numdig,
                        k00_tipo,
                        k00_tipojm)
                 select arrecad.k00_numcgm,
                        arrecad.k00_dtoper,
                        arrecad.k00_receit,
                        arrecad.k00_hist,
                        arrecad.k00_valor,
                        arrecad.k00_dtvenc,
                        arrecad.k00_numpre,
                        arrecad.k00_numpar,
                        arrecad.k00_numtot,
                        arrecad.k00_numdig,
                        arrecad.k00_tipo,
                        arrecad.k00_tipojm
                   from arrecad
                  inner join arrecad_parc_rec on arrecad.k00_numpre = arrecad_parc_rec.numpre
                                             and arrecad.k00_numpar = arrecad_parc_rec.numpar
                                             and arrecad.k00_receit = arrecad_parc_rec.receit
                  left join arreold           on arreold.k00_numpre = arrecad_parc_rec.numpre
                                             and arreold.k00_numpar = arrecad_parc_rec.numpar
                                             and arreold.k00_receit = arrecad_parc_rec.receit
                 where arreold.k00_numpre is null
                   and arrecad.k00_valor > 0;

    delete from arrecad
          using arrecad_parc_rec
          where arrecad.k00_numpre = arrecad_parc_rec.numpre
            and arrecad.k00_numpar = arrecad_parc_rec.numpar
            and arrecad.k00_receit = arrecad_parc_rec.receit;

    -- conta a quantidade de registros do arrecad
    select count(*)
      from arrecad
      into v_contador
     where k00_numpre = v_numpre;

    if lRaise is true then
      perform fc_debug('total final de registros no arrecad: '||v_contador,lRaise,false,false);
    end if;

    -- soma o valor gravado no arrecad
    if lSeparaJuroMulta = 2 then

      select round(sum(k00_valor),2)
        into v_resto
        from arrecad
       where k00_numpre = v_numpre;

    else

      select round(sum(arrecad.k00_valor)+coalesce(sum(arrecadcompos.k00_correcao),0) + coalesce(sum(arrecadcompos.k00_juros),0) + coalesce(sum(arrecadcompos.k00_multa),0) ,2)
        into v_resto
        from arrecad
             left  join arreckey      on arreckey.k00_numpre = arrecad.k00_numpre
                                     and arreckey.k00_numpar = arrecad.k00_numpar
                                     and arreckey.k00_receit = arrecad.k00_receit
                                     and arreckey.k00_hist   = arrecad.k00_hist
             left  join arrecadcompos on arrecadcompos.k00_arreckey = arreckey.k00_sequencial
       where arrecad.k00_numpre = v_numpre;

    end if;

    if lRaise is true then
      perform fc_debug('Total do arrecad (v_resto): '||v_resto||' - v_total: '||v_total,lRaise,false,false);
    end if;

    -- registra a diferenca do valor gravado no arrecad e do total do parcelamento calculado durante o processamento
    v_teste = round(v_total,2) - round(v_resto,2);

    if lRaise is true then

      perform fc_debug('v_teste: '||v_teste,lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug('ACERTAR DIFERENCA',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);
      perform fc_debug(' ',lRaise,false,false);

    end if;

    if abs(v_teste) between 0.01 and 3.00 and v_juronaultima is false then

      if lRaise is true then
        perform fc_debug('entrou no 0.01 - diferenca: '||v_teste,lRaise,false,false);
      end if;

      select k00_receit
        into v_maxrec
        from arrecad
       where k00_numpre = v_numpre
         and k00_numpar = v_totalparcelas
       order by k00_valor desc limit 1;

      update arrecad
         set k00_valor  = k00_valor + v_teste
       where k00_numpre = v_numpre
         and k00_numpar = v_totalparcelas
         and k00_receit = v_maxrec;

    end if;

    -- se juros na ultima
    if v_juronaultima is true then

      select k00_receit
        into v_receita
        from arrecad
       where k00_numpre = v_numpre
         and k00_numpar = v_totalparcelas - 1
       limit 1;

      if round(v_total,2) <> round(v_resto,2) then

        if lRaise is true then
          perform fc_debug('update: '||(round(v_total,2) - round(v_resto,2)),lRaise,false,false);
        end if;

        -- altera o valor da penultima parcela com a diferenca
        update arrecad
           set k00_valor = k00_valor + round(round(v_total,2) - round(v_resto,2),2)
        where k00_numpre = v_numpre
          and k00_numpar = v_totalparcelas - 1
          and k00_receit = v_receita;

      end if;

    end if;

    -- funcao que corrige o arrecad no caso de encontrar registros duplicados(numpre,numpar,receit)
    -- perform fc_corrigeparcelamento();

    if lSeparaJuroMulta = 2 then

      select round(sum(k00_valor),2)
        into v_resto
        from arrecad
       where k00_numpre = v_numpre;

    else

      select round(sum(arrecad.k00_valor)+coalesce(sum(arrecadcompos.k00_correcao),0) + coalesce(sum(arrecadcompos.k00_juros),0) + coalesce(sum(arrecadcompos.k00_multa),0) ,2)
        into v_resto
        from arrecad
             left  join arreckey      on arreckey.k00_numpre = arrecad.k00_numpre
                                     and arreckey.k00_numpar = arrecad.k00_numpar
                                     and arreckey.k00_receit = arrecad.k00_receit
                                     and arreckey.k00_hist   = arrecad.k00_hist
             left  join arrecadcompos on arrecadcompos.k00_arreckey = arreckey.k00_sequencial
       where arrecad.k00_numpre = v_numpre;
    end if;

    if lRaise is true then
      perform fc_debug('total do arrecad (v_resto): '||v_resto||' - v_total: '||v_total||' - totparc: '||v_totparc,lRaise,false,false);
    end if;

    for v_record_recpar in select k00_receit,
                                  sum(k00_valor)
                             from arrecad
                            where k00_numpre = v_numpre
                            group by k00_receit
    loop

      if lRaise is true then
        perform fc_debug('receita: '||v_record_recpar.k00_receit||' - valor: '||v_record_recpar.sum,lRaise,false,false);
      end if;

    end loop;

    -- se total do arrecad for diferenca do total calculado durante o processamento
    -- mostra mensagem de erro
    if lRaise then
      perform fc_debug('Parcelamento : '||v_termo||' Numpre : '||v_numpre||' Total: '||v_total||' - Resto: '||v_resto||' Diferenca: '||(round(v_total,2) - round(v_resto,2)),lRaise,false,false);
      raise notice '%',fc_debug('Fim do Processamento...',lRaise,false,true);
    end if;

    if round(v_total, 2) <> round(v_resto, 2) then
      return '[16] - total gerado da soma das parcelas inconsistente!';
    end if;

    return '1 - Parcelamento efetuado com sucesso - Termo Gerado: '||v_termo||' - Numpre: '||v_numpre;

  end;

$$ language 'plpgsql';

SQL;

    $this->execute($sSql);

    }
}
