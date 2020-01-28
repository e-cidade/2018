<?php

use Classes\PostgresMigration;

class M9634Juros extends PostgresMigration
{
    public function up ()
    {
        $sSql =
<<< SQL

set client_encoding = 'LATIN1';

create or replace function fc_juros(integer,date,date,date,bool,integer) returns float8 as
$$
declare

  rece_juros      alias for $1;
  v_data_venc     alias for $2;
  data_hoje       alias for $3;
  data_oper       alias for $4;
  imp_carne       alias for $5;
  subdir          alias for $6;

  carnes          char(10);

  dia             integer default 0;
  dia1            integer;
  dia2            integer;
  v_tipo          integer default 1;
  mesdatacerta    integer default 0;
  mesdatavenc     integer default 0;
  iDiaOperacao    integer;
  iMesOperacao    integer;
  iAnoOperacao    integer;
  iDiaVencimento  integer;
  iMesVencimento  integer;
  iAnoVencimento  integer;

  dia1_par        integer;
  mes1_par        integer;
  ano1_par        integer;
  dia2_par        integer;
  mes2_par        integer;
  ano2_par        integer;
  qano_par        integer;
  qmes_par        integer;

  juros           numeric default 0;
  v_juroscalc     numeric;
  juros_par       numeric;
  juross          numeric;
  juros_acumulado numeric;
  jur_i           numeric;
  juros_partotal  numeric default 0;
  quant_juros     numeric default 0;
  jurostotal      numeric default 0;
  jurosretornar   numeric default 0;

  v_selicatual    float8;

  dt_venci        date;
  data_comercial  date;
  data_venc       date;
  data_venc_base  date;
  data_certa      date;
  data_base       date;
  v_datacertaori  date;
  v_dataopernova  date;
  v_datavencant   date;

  lRaise          boolean default false;

  v_tabrec        record;
  v_tabrecregras  record;

  lPlugin boolean default false;

begin

  v_dataopernova := data_oper;

  lRaise := (case when fc_getsession('DB_debugon') is null then false else true end);

  if lRaise is true then

    if fc_getsession('db_debug') <> '' then
      perform fc_debug('<fc_juros> ------------------------------------------------------------------', lRaise, false, false);
    else
      perform fc_debug('<fc_juros> ------------------------------------------------------------------', lRaise, true, false);
    end if;

    perform fc_debug('<fc_juros> Processando calculo juros...',            lRaise, false, false);
    perform fc_debug('<fc_juros> ',                                        lRaise, false, false);
    perform fc_debug('<fc_juros> ',                                        lRaise, false, false);
    perform fc_debug('<fc_juros> ',                                        lRaise, false, false);
    perform fc_debug('<fc_juros> Parametros: ',                            lRaise, false, false);
    perform fc_debug('<fc_juros> ',                                        lRaise, false, false);
    perform fc_debug('<fc_juros> Receita ..............: ' || rece_juros,  lRaise, false, false);
    perform fc_debug('<fc_juros> Data de Vencimento ...: ' || v_data_venc, lRaise, false, false);
    perform fc_debug('<fc_juros> Data Atual ...........: ' || data_hoje,   lRaise, false, false);
    perform fc_debug('<fc_juros> Data de Operacao .....: ' || data_oper,   lRaise, false, false);
    perform fc_debug('<fc_juros> Impressao de Carne ...: ' || imp_carne,   lRaise, false, false);
    perform fc_debug('<fc_juros> Exercicio ............: ' || subdir,      lRaise, false, false);
    perform fc_debug('<fc_juros> ',                                        lRaise, false, false);
  end if;

  select true
    into lPlugin
    from db_plugin
   where db145_nome = 'PluginAlteracaoJurosMulta'
     and db145_situacao = true;

  if lPlugin then
    perform fc_debug('<fc_juros> Plugin de Alteracao em Juros e Multa ...: ATIVO!', lRaise, false, false);
  end if;

  select *
    into v_tabrec
    from tabrec
         inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
   where k02_codigo = rece_juros;

  if not found then
    if lRaise is true then
      perform fc_debug('<fc_juros> retornando 0 (1)',lRaise,false,false);
    end if;
    return 0;
  end if;

  juros       := 0;
  juros_par   := 0;
  quant_juros := 0;

  data_venc := data_hoje;

  if lRaise is true then
    perform fc_debug('<fc_juros> V E R I F I C A   S A B A D O   E   D O M I N G O - ' || v_tabrec.k02_sabdom, lRaise, false, false);
    perform fc_debug('<fc_juros>',                                                                             lRaise, false, false);
  end if;

  if v_tabrec.k02_sabdom = true then

    if lRaise is true then
      perform fc_debug('<fc_juros> L O O P   I N I C I O - calend: ' || data_venc, lRaise, false, false);
      perform fc_debug('<fc_juros>',                                               lRaise, false, false);
    end if;

    loop

      data_venc := data_venc - 1;

      if lRaise is true then
        perform fc_debug('<fc_juros> calend: ' || data_venc, lRaise, false, false);
      end if;

      select k13_data
        into data_certa
        from calend
       where k13_data = data_venc;

      if data_certa is null then

        data_certa := data_venc + 1;

        exit;

      end if;

    end loop;

    if lRaise is true then
      perform fc_debug('<fc_juros>', lRaise, false, false);
      perform fc_debug('<fc_juros> L O O P   FIM --------- calend: ' || data_certa, lRaise, false, false);
    end if;

    /**
     * Quando a data de vencimento do cáculo de juros é um dia não útil, e o primeiro dia não útil desse periodo/range
     * de dias não úteis for maior que a data de vencimento original do débito, a data para cálculo não recebe alteração.
     * A regra se aplica para respeitar a data de aniversário de vencimento do débito em dias não úteis.
     */
    if data_certa > v_data_venc and lPlugin then
      data_certa := data_hoje;
    end if;

  else

    data_certa := data_hoje;

  end if;

  data_venc := v_data_venc;

  if lRaise is true then
    perform fc_debug('<fc_juros>',                                lRaise, false, false);
    perform fc_debug('<fc_juros> data_certa ...: ' || data_certa, lRaise, false, false);
    perform fc_debug('<fc_juros> data_hoje ....: ' || data_hoje,  lRaise, false, false);
    perform fc_debug('<fc_juros> data_venc ....: ' || data_venc,  lRaise, false, false);
  end if;

  v_datavencant  := data_venc;
  v_datacertaori := data_certa;

  if lRaise is true then
    perform fc_debug('<fc_juros>',                                        lRaise, false, false);
    perform fc_debug('<fc_juros> v_datavencant  ...: ' || v_datavencant,  lRaise, false, false);
    perform fc_debug('<fc_juros> v_datacertaori ...: ' || v_datacertaori, lRaise, false, false);
  end if;

  --
  -- CALCULA JUROS DE PARCELAMENTOS
  --

  if lRaise is true then
    perform fc_debug('<fc_juros> ',                                                                  lRaise, false, false);
    perform fc_debug('<fc_juros> C A L C U L O   D E   J U R O S   P A R C E L A D O - I N I C I O', lRaise, false, false);
    perform fc_debug('<fc_juros> ',                                                                  lRaise, false, false);
  end if;

  juros_partotal := 0;

  for v_tabrecregras in
    select *
      from tabrecregrasjm
           inner join tabrecjm on tabrecjm.k02_codjm = tabrecregrasjm.k04_codjm
     where k04_receit = rece_juros
    order by k04_dtini
  loop

    if lRaise then
      perform fc_debug('<fc_juros> Receita de Juros ...: ' || rece_juros,                    lRaise, false, false);
      perform fc_debug('<fc_juros> Regra encontrada ...: ' || v_tabrecregras.k04_sequencial, lRaise, false, false);
      perform fc_debug('<fc_juros> Receita ............: ' || v_tabrecregras.k04_sequencial, lRaise, false, false);
      perform fc_debug('<fc_juros> Codigo J/M .........: ' || v_tabrecregras.k04_codjm,      lRaise, false, false);
      perform fc_debug('<fc_juros> Data Inicial .......: ' || v_tabrecregras.k04_dtini,      lRaise, false, false);
      perform fc_debug('<fc_juros> Data Final .........: ' || v_tabrecregras.k04_dtfim,      lRaise, false, false);
      perform fc_debug('<fc_juros> k02_jurparate ......: ' || v_tabrecregras.k02_jurparate,  lRaise, false, false);
    end if;

    -- itaqui
    v_tipo = v_tabrecregras.k02_jurparate;
    if v_tipo is null then
      v_tipo = 1; -- calcula ate vcto
    end if;

    if lRaise is true then
      perform fc_debug('<fc_juros>',                                  lRaise, false, false);
      perform fc_debug('<fc_juros> v_tipo .............: ' || v_tipo, lRaise, false, false);
    end if;

    if v_tipo = 1 then
      if data_venc < data_certa then

        if lRaise is true then
          perform fc_debug('<fc_juros>', lRaise, false, false);
          perform fc_debug('<fc_juros> Alterando o valor da variavel data_certa('||data_certa||') para o valor da variavel data_venc('||data_venc||')', lRaise, false, false);
        end if;
        data_certa := data_venc;

      end if;
    elsif v_tipo = 2 then -- calcula ate data atual

      if lRaise is true then
          perform fc_debug('<fc_juros>', lRaise, false, false);
          perform fc_debug('<fc_juros> Alterando o valor da variavel data_venc('||data_venc||') para o valor da variavel data_hoje('||data_hoje||')', lRaise, false, false);
      end if;
      data_venc := data_hoje;

    end if;

    if lRaise is true then
      perform fc_debug('<fc_juros>',                                                             lRaise, false, false);
      perform fc_debug('<fc_juros> v_dataopernova .............: ' || v_dataopernova,            lRaise, false, false);
      perform fc_debug('<fc_juros> v_tabrecregras.k04_dtini ...: ' || v_tabrecregras.k04_dtini,  lRaise, false, false);
      perform fc_debug('<fc_juros> v_tabrecregras.k04_dtfim ...: ' || v_tabrecregras.k04_dtfim,  lRaise, false, false);
      perform fc_debug('<fc_juros>',                                                             lRaise, false, false);
      perform fc_debug('<fc_juros> data_certa .................: ' || data_certa,                lRaise, false, false);
      perform fc_debug('<fc_juros> data_venc ..................: ' || data_venc,                 lRaise, false, false);
      perform fc_debug('<fc_juros> data_hoje ..................: ' || data_hoje,                 lRaise, false, false);
      perform fc_debug('<fc_juros>',                                                             lRaise, false, false);
      perform fc_debug('<fc_juros> juros_par ..................: ' || juros_par,                 lRaise, false, false);
      perform fc_debug('<fc_juros> k02_jurpar .................: ' || v_tabrecregras.k02_jurpar, lRaise, false, false);
    end if;

    if v_dataopernova >= v_tabrecregras.k04_dtini and v_dataopernova <= v_tabrecregras.k04_dtfim then

      if data_certa > v_tabrecregras.k04_dtfim then

        if lRaise is true then
           perform fc_debug('<fc_juros> Alterando o valor da variavel data_certa('||data_certa||') para o valor da variavel v_tabrecregras.k04_dtfim('||v_tabrecregras.k04_dtfim||')', lRaise, false, false);
        end if;

        data_certa := v_tabrecregras.k04_dtfim;

        if lRaise is true then
          perform fc_debug('<fc_juros>',                                     lRaise, false, false);
          perform fc_debug('<fc_juros> data_certa: .......: ' || data_certa, lRaise, false, false);
        end if;
      end if;

      if v_tabrecregras.k02_jurpar is not null and v_tabrecregras.k02_jurpar <> 0 then

        if lRaise is true then
          perform fc_debug('<fc_juros>',                                                                                 lRaise, false, false);
          perform fc_debug('<fc_juros> C A L C U L O   D O   J U R O S   D E   F I N A N C I A M E N T O - I N I C I O', lRaise, false, false);
          perform fc_debug('<fc_juros>',                                                                                 lRaise, false, false);
        end if;

        /*
          select que cria a quantidade de meses para o juros de financiamento conforme intervalo de data informado
          o juros deve ser calculado com base na data de operacao
        */
        select count(*)
          into quant_juros
          from generate_series(v_dataopernova, data_hoje - INTERVAL '1 month', INTERVAL '1 month');

        if lRaise is true then
          perform fc_debug('<fc_juros> v_dataopernova ..............: ' || v_dataopernova,            lRaise, false, false);
          perform fc_debug('<fc_juros> data_certa ..................: ' || data_certa,                lRaise, false, false);
          perform fc_debug('<fc_juros> data_hoje ...................: ' || data_hoje,                 lRaise, false, false);
          perform fc_debug('<fc_juros> quant_juros .................: ' || quant_juros,               lRaise, false, false);
          perform fc_debug('<fc_juros> v_tabrecregras.k02_jurpar ...: ' || v_tabrecregras.k02_jurpar, lRaise, false, false);
          perform fc_debug('<fc_juros> v_tabrecregras.k02_juracu ...: ' || v_tabrecregras.k02_juracu, lRaise, false, false);
        end if;

        juros_par := (quant_juros * cast(v_tabrecregras.k02_jurpar as numeric(8,2)));

        --
        -- para juros sob financiamento nao acumulado
        --
        if lRaise is true then
          perform fc_debug('<fc_juros>',                                              lRaise, false, false);
          perform fc_debug('<fc_juros> juros_par ...................: ' || juros_par, lRaise, false, false);
        end if;

        --
        -- para juros sob financiamento acumulado
        --
        if v_tabrecregras.k02_juracu = 't' and quant_juros > 0 then

          if lRaise is true then
            perform fc_debug('<fc_juros>',                                             lRaise, false, false);
            perform fc_debug('<fc_juros> calculando juros de financiamento acumulado', lRaise, false, false);
          end if;

          juros_par := (1 + (v_tabrecregras.k02_jurpar / 100)) ^ quant_juros;
          juros_par := (juros_par - 1) * 100;

          if lRaise is true then
            perform fc_debug('<fc_juros> percentual de juros ...: ' || v_tabrecregras.k02_jurpar, lRaise, false, false);
            perform fc_debug('<fc_juros> numero de periodos ....: ' || quant_juros,               lRaise, false, false);
            perform fc_debug('<fc_juros> juros acumulado .......: ' || juros_par,                 lRaise, false, false);
          end if;

        end if;

        if lRaise is true then
          perform fc_debug('<fc_juros>',                                  lRaise, false, false);
          perform fc_debug('<fc_juros> somando juros de parcelamento...', lRaise, false, false);
        end if;

        if lRaise is true then
          perform fc_debug('<fc_juros>',                                                                                       lRaise, false, false);
          perform fc_debug('<fc_juros> C A L C U L O   D O   J U R O S   D E   F I N A N C I A M E N T O - F I M', lRaise, false, false);
          perform fc_debug('<fc_juros>',                                                                                       lRaise, false, false);
        end if;

      end if;

    end if;

    if v_tipo = 1 then
      data_venc      := v_tabrecregras.k04_dtfim + 1;
      v_dataopernova := v_tabrecregras.k04_dtfim + 1;
    end if;

    data_certa := v_datacertaori;

    if lRaise is true then
      perform fc_debug('<fc_juros> ',                                       lRaise, false, false);
      perform fc_debug('<fc_juros> data_certa .......: ' || data_certa,     lRaise, false, false);
      perform fc_debug('<fc_juros> v_datacertaori ...: ' || v_datacertaori, lRaise, false, false);
      perform fc_debug('<fc_juros> v_dataopernova ...: ' || v_dataopernova, lRaise, false, false);
      perform fc_debug('<fc_juros> ',                                       lRaise, false, false);
    end if;

    if v_tabrecregras.k02_juros = 999 then
      if data_venc < data_certa then
        juros_par := 0;
      end if;
    end if;

    if lRaise is true  then
      perform fc_debug('<fc_juros>  ',lRaise, false, false);
      perform fc_debug('<fc_juros> somando '||juros_par||' em juros_partotal que atualmente esta em: '||juros_partotal, lRaise, false, false);
      perform fc_debug('<fc_juros> ',lRaise, false, false);
    end if;

    juros_partotal := juros_partotal + juros_par;
    juros_par := 0;

  end loop;

  if lRaise is true then
    perform fc_debug('<fc_juros> ',                                                            lRaise, false, false);
    perform fc_debug('<fc_juros> juros_financ .....: ' || juros_par,                           lRaise, false, false);
    perform fc_debug('<fc_juros> juros_partotal ...: ' || juros_partotal,                      lRaise, false, false);
    perform fc_debug('<fc_juros> v_datavencant ....: ' || v_datavencant,                       lRaise, false, false);
    perform fc_debug('<fc_juros> v_datacertaori ...: ' || v_datacertaori,                      lRaise, false, false);
    perform fc_debug('<fc_juros> ',                                                            lRaise, false, false);
    perform fc_debug('<fc_juros> C A L C U L O   D E   J U R O S   P A R C E L A D O - F I M', lRaise, false, false);
    perform fc_debug('<fc_juros> ',                                                            lRaise, false, false);
  end if;

  --
  -- calcula juros normal
  --
  if lRaise is true then
    perform fc_debug('<fc_juros> ', lRaise, false, false);
    perform fc_debug('<fc_juros> INICIO CALCULO NORMAL', lRaise, false, false);
    perform fc_debug('<fc_juros> ', lRaise, false, false);
    perform fc_debug('<fc_juros> juros: '||juros||' - juros_par: '||juros_par, lRaise, false, false);
    perform fc_debug('<fc_juros> ',lRaise, false, false);
    perform fc_debug('<fc_juros> a - v_datavencant: '||v_datavencant||' - data_certa: '||data_certa, lRaise, false, false);
    perform fc_debug('<fc_juros> ',lRaise, false, false);
  end if;

  if v_datavencant < data_certa then

    if lRaise is true then
      perform fc_debug('<fc_juros> ',lRaise, false, false);
      perform fc_debug('<fc_juros> c a l c u l o    d e  j u r o s   n o r m a l',lRaise, false, false);
      perform fc_debug('<fc_juros> ',lRaise, false, false);
    end if;

    v_dataopernova := data_oper;
    data_venc      := v_datavencant;
    data_certa     := v_datacertaori;
    data_base      := data_certa;
    data_venc_base := data_venc;
    jurostotal     := 0;

    iDiaOperacao   := extract(day   from data_hoje);
    iMesOperacao   := extract(month from data_hoje);
    iAnoOperacao   := extract(year  from data_hoje);

    iDiaVencimento := extract(day   from data_venc_base);
    iMesVencimento := extract(month from data_venc_base);
    iAnoVencimento := extract(year  from data_venc_base);

    if imp_carne = 'f' then

      if lRaise is true then
        perform fc_debug('<fc_juros> data certa: '||data_certa,lRaise, false, false);
      end if;


      for v_tabrecregras in
        select *
          from tabrecregrasjm
               inner join tabrecjm on tabrecjm.k02_codjm = tabrecregrasjm.k04_codjm
         where k04_receit = rece_juros
        order by k04_dtini
      loop

        if lRaise then
          perform fc_debug('<fc_juros> _____________________________________________________'     ,lRaise, false, false);
          perform fc_debug('<fc_juros> Receita de Juros: '||rece_juros                            ,lRaise, false, false);
          perform fc_debug('<fc_juros> Regra encontrada: '||v_tabrecregras.k04_sequencial         ,lRaise, false, false);
          perform fc_debug('<fc_juros> Receita: '||v_tabrecregras.k04_sequencial                  ,lRaise, false, false);
          perform fc_debug('<fc_juros> Codigo J/M: '||v_tabrecregras.k04_codjm                    ,lRaise, false, false);
          perform fc_debug('<fc_juros> Data Inicial: '||v_tabrecregras.k04_dtini                  ,lRaise, false, false);
          perform fc_debug('<fc_juros> Data Final: '||v_tabrecregras.k04_dtfim                    ,lRaise, false, false);
          perform fc_debug('<fc_juros> voltando data de vencimento para original: '||v_datavencant,lRaise, false, false);
        end if;
        data_venc := v_datavencant;

        if lRaise is true then
          perform fc_debug('<fc_juros> '                                 ,lRaise, false, false);
          perform fc_debug('<fc_juros> Verificamos se a data de vencimento base (data_venc_base) estah entre a data inicial e final da tabela de regras de juros e multa da receita (tabrecregrasjm)',lRaise, false, false);
          perform fc_debug('<fc_juros> '                                 ,lRaise, false, false);
          perform fc_debug('<fc_juros> data_venc_base: '||data_venc_base ,lRaise, false, false);
          perform fc_debug('<fc_juros> v_dataopernova: '||v_dataopernova ,lRaise, false, false);
          perform fc_debug('<fc_juros> data_certa: '||data_certa         ,lRaise, false, false);
          perform fc_debug('<fc_juros> data_venc: '||data_venc           ,lRaise, false, false);
          perform fc_debug('<fc_juros> juros: '||juros                   ,lRaise, false, false);
        end if;

        if data_venc_base >= v_tabrecregras.k04_dtini and data_venc_base <= v_tabrecregras.k04_dtfim then

          if lRaise is true then
             perform fc_debug('<fc_juros> ', lRaise, false, false);
             perform fc_debug('<fc_juros> v_dataopernova > v_tabrecregras.k04_dtini e v_dataopernova <= v_tabrecregras.k04_dtfim', lRaise, false, false);
             perform fc_debug('<fc_juros> *****************************************************', lRaise, false, false);
             perform fc_debug('<fc_juros> >> ENTROU NO TIPO DE JURO: '||v_tabrecregras.k04_codjm, lRaise, false, false);
             perform fc_debug('<fc_juros> *****************************************************', lRaise, false, false);
             perform fc_debug('<fc_juros> ', lRaise, false, false);
             perform fc_debug('<fc_juros> ', lRaise, false, false);
          end if;

          data_venc := data_venc_base;
          if data_venc_base > v_tabrecregras.k04_dtfim then

            if lRaise is true then
              perform fc_debug('<fc_juros> Data de Vencimento (data_venc_base) '||data_venc_base||' maior que a data final (k04_dtfim) '||v_tabrecregras.k04_dtfim||' da tabela de regras de juros e multa da receita (tabrecregrasjm)',lRaise, false, false);
              perform fc_debug('<fc_juros> Alteramos a data de vencimento (data_venc) para a ultima data da tabelas de regras de juros e multa da receita (tabrecregrasjm): '||v_tabrecregras.k04_dtfim, lRaise, false, false);
            end if;
            data_venc := v_tabrecregras.k04_dtfim;

          else

            if data_venc_base < v_tabrecregras.k04_dtini then

              if lRaise is true then
                perform fc_debug('<fc_juros> Data de Vencimento (data_venc_base) '||data_venc_base||' menor que a data inicial (k04_dtini) '||v_tabrecregras.k04_dtini||' da tabela de regras de juros e multa da receita (tabrecregrasjm)', lRaise, false, false);
                perform fc_debug('<fc_juros> Alteramos a data de vencimento (data_venc) para a data inicial da tabelas de regras de juros e multa da receita (tabrecregrasjm): '||v_tabrecregras.k04_dtini, lRaise, false, false);
              end if;

              data_venc := v_tabrecregras.k04_dtini;

            else

              if v_datavencant > v_tabrecregras.k04_dtini then

                data_venc := v_datavencant;
                if lRaise is true then
                  perform fc_debug('<fc_juros> Data de Vencimento Anterior!? (v_datavencant) '||v_datavencant||' maior que a data inicial (k04_dtini) '||v_tabrecregras.k04_dtini||' da tabela de regras de juros e multa da receita (tabrecregrasjm)',lRaise, false, false);
                  perform fc_debug('<fc_juros> Alteramos a data de vencimento (data_venc) para a data de vencimento anterior (v_datavencant): '||v_datavencant, lRaise, false, false);
                end if;

              else

                if lRaise is true then
                  perform fc_debug('<fc_juros> Nada eh alterado em termos de data de vencimento', lRaise, false, false);
                end if;

              end if;
            end if;

          end if;

          if lRaise is true then
             perform fc_debug('<fc_juros> ',lRaise, false, false);
             perform fc_debug('<fc_juros> ',lRaise, false, false);
          end if;

          if data_venc < v_tabrecregras.k04_dtfim then
            data_certa := v_tabrecregras.k04_dtfim;
          end if;

          if data_certa > v_datacertaori then
            data_certa := v_datacertaori;
          end if;

          if lRaise is true then
            perform fc_debug('<fc_juros> entrou tipo de juro e multa: '||v_tabrecregras.k04_codjm||' - data_certa: '||data_certa||' - data_venc: '||data_venc||' - data_venc_base: '||data_venc_base||' - juros: '||v_tabrecregras.k02_juros, lRaise, false, false);
          end if;

          if data_venc < data_certa then

            if lRaise is true then
              perform fc_debug('<fc_juros> vencimento MENOR que data certa - data certa: '||data_certa, lRaise, false, false);
            end if;

            if extract(year from data_certa) > extract(year from data_venc) then

              if lRaise is true then
                perform fc_debug('<fc_juros>       ano da data_certa maior que ano do data_venc', lRaise, false, false);
                perform fc_debug('<fc_juros>       juros (1): '||juros, lRaise, false, false);
              end if;

              v_juroscalc := (((extract(year from data_certa) - 1) - (extract(year from data_venc))) * 12);
              if lRaise is true then
                perform fc_debug('<fc_juros>          1 - v_juroscalc: '||v_juroscalc, lRaise, false, false);
              end if;
              juros := juros + v_juroscalc;

              if lRaise is true then
                perform fc_debug('<fc_juros>          juros: '||juros, lRaise, false, false);
              end if;

              v_juroscalc := extract(month from data_certa);
              if lRaise is true then
                perform fc_debug('<fc_juros>          2 - v_juroscalc: '||v_juroscalc, lRaise, false, false);
              end if;
              juros := juros + v_juroscalc;

              if lRaise is true then
                perform fc_debug('<fc_juros>          juros: '||juros, lRaise, false, false);
              end if;

              if (extract(year from (data_venc + 1))) = extract(year from data_venc) then
                v_juroscalc := (13 - (extract(month from (data_venc + 1))));
                if lRaise is true then
                  perform fc_debug('<fc_juros>          3 - v_juroscalc: '||v_juroscalc, lRaise, false, false);
                end if;
                juros := juros + v_juroscalc;
              end if;

              if lRaise is true then
                perform fc_debug('<fc_juros>             juros: '||juros, lRaise, false, false);
              end if;

            else

              if lRaise is true then
                perform fc_debug('<fc_juros>       ano da data_certa menor que ano do data_venc', lRaise, false, false);
                perform fc_debug('<fc_juros>       juros (2): '||juros, lRaise, false, false);
              end if;

              mesdatacerta := extract(month from data_certa);
              mesdatavenc  := extract(month from (data_venc + 1));

              if lRaise is true then
                perform fc_debug('<fc_juros>       mesdatacerta: '||mesdatacerta||' - mesdatavenca: '||mesdatavenc, lRaise, false, false);
              end if;

              v_juroscalc := (extract(month from data_certa) + 1) - extract(month from (data_venc + 1));

              if lRaise is true then
                perform fc_debug('<fc_juros>          4 - v_juroscalc: '||v_juroscalc, lRaise, false, false);
              end if;

              juros := juros + v_juroscalc;

            end if;

            if lRaise is true then
              perform fc_debug('<fc_juros>          *** juros: '||juros||' - juros por dia: '||v_tabrecregras.k02_jurdia, lRaise, false, false);
            end if;

            --
            -- se juros por dia, cobrar proporcional a partir do dia de vencimento
            --
            if v_tabrecregras.k02_jurdia = 't' then
              --
              -- Quando o calculo de juros \E9 diario, desconsideramos os juros calculados anteriormente
              --
              juros  := 0;

              if lRaise is true then
                perform fc_debug('<fc_juros>                             ', lRaise, false, false);
                perform fc_debug('<fc_juros> ----------------------------', lRaise, false, false);
                perform fc_debug('<fc_juros> INICIO CALCULO JUROS DIARIO.', lRaise, false, false);
                perform fc_debug('<fc_juros>          juros por dia: '||v_tabrecregras.k02_jurdia, lRaise, false, false);
                perform fc_debug('<fc_juros> ----------------------------', lRaise, false, false);
                perform fc_debug('<fc_juros>                             ', lRaise, false, false);
              end if;

              /*
                select que cria os dias conforme intervalo de data informado
              */
              select count(*)
                into dia
                from generate_series(data_venc, data_certa - INTERVAL '1 day', INTERVAL '1 day');

              if lRaise is true then
                perform fc_debug('<fc_juros> quantidade de dias de atraso: '||dia, lRaise, false, false);
              end if;

              juross := ( cast(v_tabrecregras.k02_juros as numeric) / 30) * dia;
              juros  := juros + juross;

              if lRaise is true then
                perform fc_debug('<fc_juros> calculo do percentual diario: (v_tabrecregras.k02_juros: '||v_tabrecregras.k02_juros||' / 30) * '||dia, lRaise, false, false);
                perform fc_debug('<fc_juros> juross: '||juross||' / v_tabrecregras.k02_juros: '||v_tabrecregras.k02_juros||' / juros: '||juros, lRaise, false, false);
              end if;

              if lRaise is true then
                perform fc_debug('<fc_juros>                             ', lRaise, false, false);
                perform fc_debug('<fc_juros> -------------------------', lRaise, false, false);
                perform fc_debug('<fc_juros> FIM CALCULO JUROS DIARIO.', lRaise, false, false);
                perform fc_debug('<fc_juros> -------------------------', lRaise, false, false);
                perform fc_debug('<fc_juros>                             ', lRaise, false, false);
              end if;

            end if;

            if lRaise is true then
              perform fc_debug('<fc_juros>       juros: '||juros, lRaise, false, false);
            end if;

            v_juroscalc := cast(v_tabrecregras.k02_juros as numeric(8,2));
            if lRaise is true then
              perform fc_debug('<fc_juros>       5 - v_juroscalc: '||v_juroscalc, lRaise, false, false);
              perform fc_debug('<fc_juros>       6 - juros: '||juros, lRaise, false, false);
            end if;

            if juros is not null and juros <> 0 and v_tabrecregras.k02_jurdia <> 't' then

              if lRaise is true then
                perform fc_debug('<fc_juros>       7 - juros existe...', lRaise, false, false);
              end if;

              data_comercial := data_venc + 1;

              if lRaise is true then
                perform fc_debug('<fc_juros>       7.5 - data_comercial: '||data_comercial||' - data_venc: '||data_venc, lRaise, false, false);
              end if;

              if extract(month from data_comercial) = extract(month from data_venc) then
                if lRaise is true then
                  perform fc_debug('<fc_juros>       8 - mes da data comercial = mes da data vencimento...', lRaise, false, false);
                end if;

                if extract(day from data_venc) >= extract(day from data_certa) then
                  if lRaise is true then
                    perform fc_debug('<fc_juros>       9 - dia da data de vencimento >= dia da data certa...', lRaise, false, false);
                  end if;
                  if lRaise is true then
                    perform fc_debug('<fc_juros> antes: '||juros, lRaise, false, false);
                  end if;

                  -- modificacao feita em carazinho pois os juros estavam negativos em alguns casos
                  -- entao coloquei esse if abaixo antes de diminuir 1 para testar
                  ------if v_tabrecregras.k02_jurdia <> 't' then
                    juros := juros - 1;
                  ------end if;

                  if lRaise is true then
                    perform fc_debug('<fc_juros> depois: '||juros, lRaise, false, false);
                  end if;
                end if;
              end if;
            end if;

            if lRaise is true then
              perform fc_debug('<fc_juros>       10 - v_juroscalc: '||v_juroscalc||' - juros: '||juros, lRaise, false, false);
            end if;

            if v_tabrecregras.k02_jurdia <> 't' then
               juros := juros * v_juroscalc;

               if lRaise is true then
                 perform fc_debug('<fc_juros>       11 - juros: '||juros, lRaise, false, false);
               end if;
            end if;

            if lRaise is true then
              perform fc_debug('<fc_juros>    old: v_dataopernova: '||v_dataopernova||' - data_venc: '||data_venc||' - data_certa: '||data_certa, lRaise, false, false);
              perform fc_debug('<fc_juros>    new: v_dataopernova: '||v_dataopernova||' - data_venc: '||data_venc||' - data_certa: '||data_certa||' - data_venc_base: '||data_venc_base, lRaise, false, false);
              perform fc_debug('<fc_juros> ', lRaise, false, false);
            end if;
            v_dataopernova := v_tabrecregras.k04_dtfim + 1;
            data_venc_base := v_dataopernova;
            data_certa     := v_datacertaori;

          else
            if lRaise is true then
              perform fc_debug('<fc_juros>       vencimento maior que data certa..............', lRaise, false, false);
            end if;
          end if;

        else
          if lRaise is true then
            perform fc_debug('<fc_juros> ', lRaise, false, false);
            perform fc_debug('<fc_juros> data de operacao  f o r a  periodo das regras', lRaise, false, false);
            perform fc_debug('<fc_juros> ', lRaise, false, false);
          end if;
        end if;

        if v_tabrecregras.k02_juros = 999 then

          if lRaise is true then
            perform fc_debug('<fc_juros> k02_juros == 999 - juros: '||juros, lRaise, false, false);
          end if;

          juros := 0;

          if data_venc < data_certa then
            if lRaise is true then
              perform fc_debug('<fc_juros> data_venc ('||data_venc||') < data_certa ('||data_certa||')',lRaise, false, false);
            end if;
            select  i02_valor
            into v_selicatual
            from infla
            where i02_codigo = 'SELIC'
              and i02_valor <> 0
            order by i02_data desc limit 1;

            if lRaise is true then
              perform fc_debug('<fc_juros> juros: '||juros||' - selic: '||v_selicatual, lRaise, false, false);
            end if;

            juros := fc_vlinf('SELIC'::varchar,data_venc);

            if lRaise is true then
              perform fc_debug('<fc_juros> juros: '||juros, lRaise, false, false);
            end if;

            if juros < 0 then
              juros := 0;
            end if;

          end if;
        end if;

        if lRaise is true then
          perform fc_debug('<fc_juros> somando '||juros||' em jurostotal que atualmente esta em: '||jurostotal, lRaise, false, false);
          perform fc_debug('<fc_juros> '                                                     ,lRaise, false, false);
          perform fc_debug('<fc_juros> FIM CALCULO DA REGRA: '||v_tabrecregras.k04_sequencial,lRaise, false, false);
          perform fc_debug('<fc_juros> _____________________________________________________',lRaise, false, false);
          perform fc_debug('<fc_juros> '                                                     ,lRaise, false, false);
        end if;

        jurostotal := jurostotal + juros;
        juros      := 0;

      end loop;

    end if;

  else

    if lRaise is true then
      perform fc_debug('<fc_juros> a - v_datavencant: '||v_datavencant||' - data_certa: '||data_certa, lRaise, false, false);
    end if;

  end if;

  if v_tabrec.k02_juroslimite > 0 and jurostotal > v_tabrec.k02_juroslimite then

    jurostotal := v_tabrec.k02_juroslimite;

    if lRaise is true then
      perform fc_debug('<fc_juros> limite de juros definido para ateh '||jurostotal, lRaise, false, false);
    end if;

  end if;

  if lRaise is true  then
    perform fc_debug('<fc_juros> juros: '||juros||' - juros_par: '||juros_par, lRaise, false, false);
    perform fc_debug('<fc_juros> juros_financiamento: '||juros_partotal||' - juros mora: '||jurostotal, lRaise, false, false);
  end if;

  jurosretornar = (jurostotal::float8 + juros_partotal::float8) / 100::float8;

  if lRaise is true  then
    perform fc_debug('<fc_juros> jurosretornar: '||jurosretornar                                    ,lRaise,false,false);
    perform fc_debug('<fc_juros> '                                                                  ,lRaise,false,false);
    perform fc_debug('<fc_juros> '                                                                  ,lRaise,false,false);
    perform fc_debug('<fc_juros> ------------------------------------------------------------------',lRaise,false,true);
  end if;

  return jurosretornar;

end;
$$ language 'plpgsql';

SQL;
        $this->execute($sSql);
    }

    public function down ()
    {
        $sSql =
<<< SQL

set client_encoding = 'LATIN1';

create or replace function fc_juros(integer,date,date,date,bool,integer) returns float8 as
$$
declare

  rece_juros      alias for $1;
  v_data_venc     alias for $2;
  data_hoje       alias for $3;
  data_oper       alias for $4;
  imp_carne       alias for $5;
  subdir          alias for $6;

  carnes          char(10);

  dia             integer default 0;
  dia1            integer;
  dia2            integer;
  v_tipo          integer default 1;
  mesdatacerta    integer default 0;
  mesdatavenc     integer default 0;
  iDiaOperacao    integer;
  iMesOperacao    integer;
  iAnoOperacao    integer;
  iDiaVencimento  integer;
  iMesVencimento  integer;
  iAnoVencimento  integer;

  dia1_par        integer;
  mes1_par        integer;
  ano1_par        integer;
  dia2_par        integer;
  mes2_par        integer;
  ano2_par        integer;
  qano_par        integer;
  qmes_par        integer;

  juros           numeric default 0;
  v_juroscalc     numeric;
  juros_par       numeric;
  juross          numeric;
  juros_acumulado numeric;
  jur_i           numeric;
  juros_partotal  numeric default 0;
  quant_juros     numeric default 0;
  jurostotal      numeric default 0;
  jurosretornar   numeric default 0;

  v_selicatual    float8;

  dt_venci        date;
  data_comercial  date;
  data_venc       date;
  data_venc_base  date;
  data_certa      date;
  data_base       date;
  v_datacertaori  date;
  v_dataopernova  date;
  v_datavencant   date;

  lRaise          boolean default false;

  v_tabrec        record;
  v_tabrecregras  record;

begin

  v_dataopernova := data_oper;

  lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );
  if lRaise is true then

    if fc_getsession('db_debug') <> '' then
      perform fc_debug('<fc_juros> ------------------------------------------------------------------', lRaise, false, false);
    else
      perform fc_debug('<fc_juros> ------------------------------------------------------------------', lRaise, true, false);
    end if;

    perform fc_debug('<fc_juros> Processando calculo juros...',            lRaise, false, false);
    perform fc_debug('<fc_juros> ',                                        lRaise, false, false);
    perform fc_debug('<fc_juros> ',                                        lRaise, false, false);
    perform fc_debug('<fc_juros> ',                                        lRaise, false, false);
    perform fc_debug('<fc_juros> Parametros: ',                            lRaise, false, false);
    perform fc_debug('<fc_juros> ',                                        lRaise, false, false);
    perform fc_debug('<fc_juros> Receita ..............: ' || rece_juros,  lRaise, false, false);
    perform fc_debug('<fc_juros> Data de Vencimento ...: ' || v_data_venc, lRaise, false, false);
    perform fc_debug('<fc_juros> Data Atual ...........: ' || data_hoje,   lRaise, false, false);
    perform fc_debug('<fc_juros> Data de Operacao .....: ' || data_oper,   lRaise, false, false);
    perform fc_debug('<fc_juros> Impressao de Carne ...: ' || imp_carne,   lRaise, false, false);
    perform fc_debug('<fc_juros> Exercicio ............: ' || subdir,      lRaise, false, false);
    perform fc_debug('<fc_juros> ',                                        lRaise, false, false);
  end if;

  select *
    into v_tabrec
    from tabrec
         inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
   where k02_codigo = rece_juros;


  if not found then
    if lRaise is true then
      perform fc_debug('<fc_juros> retornando 0 (1)',lRaise,false,false);
    end if;
    return 0;
  end if;

  juros       := 0;
  juros_par   := 0;
  quant_juros := 0;

  data_venc := data_hoje;

  if lRaise is true then
    perform fc_debug('<fc_juros> V E R I F I C A   S A B A D O   E   D O M I N G O - ' || v_tabrec.k02_sabdom, lRaise, false, false);
    perform fc_debug('<fc_juros>',                                                                             lRaise, false, false);
  end if;

  if v_tabrec.k02_sabdom = true then

    if lRaise is true then
      perform fc_debug('<fc_juros> L O O P   I N I C I O - calend: ' || data_venc, lRaise, false, false);
      perform fc_debug('<fc_juros>',                                               lRaise, false, false);
    end if;

    loop

      data_venc := data_venc - 1;

      if lRaise is true then
        perform fc_debug('<fc_juros> calend: ' || data_venc, lRaise, false, false);
      end if;

      select k13_data
        into data_certa
        from calend
       where k13_data = data_venc;

      if data_certa is null then

        data_certa := data_venc + 1;

        exit;

      end if;

    end loop;

    if lRaise is true then
      perform fc_debug('<fc_juros>', lRaise, false, false);
      perform fc_debug('<fc_juros> L O O P   FIM --------- calend: ' || data_certa, lRaise, false, false);
    end if;

  else

    data_certa := data_hoje;

  end if;

  data_venc := v_data_venc;

  if lRaise is true then
    perform fc_debug('<fc_juros>',                                lRaise, false, false);
    perform fc_debug('<fc_juros> data_certa ...: ' || data_certa, lRaise, false, false);
    perform fc_debug('<fc_juros> data_hoje ....: ' || data_hoje,  lRaise, false, false);
    perform fc_debug('<fc_juros> data_venc ....: ' || data_venc,  lRaise, false, false);
  end if;

  v_datavencant  := data_venc;
  v_datacertaori := data_certa;

  if lRaise is true then
    perform fc_debug('<fc_juros>',                                        lRaise, false, false);
    perform fc_debug('<fc_juros> v_datavencant  ...: ' || v_datavencant,  lRaise, false, false);
    perform fc_debug('<fc_juros> v_datacertaori ...: ' || v_datacertaori, lRaise, false, false);
  end if;

  --
  -- CALCULA JUROS DE PARCELAMENTOS
  --

  if lRaise is true then
    perform fc_debug('<fc_juros> ',                                                                  lRaise, false, false);
    perform fc_debug('<fc_juros> C A L C U L O   D E   J U R O S   P A R C E L A D O - I N I C I O', lRaise, false, false);
    perform fc_debug('<fc_juros> ',                                                                  lRaise, false, false);
  end if;

  juros_partotal := 0;

  for v_tabrecregras in
    select *
      from tabrecregrasjm
           inner join tabrecjm on tabrecjm.k02_codjm = tabrecregrasjm.k04_codjm
     where k04_receit = rece_juros
    order by k04_dtini
  loop

    if lRaise then
      perform fc_debug('<fc_juros> Receita de Juros ...: ' || rece_juros,                    lRaise, false, false);
      perform fc_debug('<fc_juros> Regra encontrada ...: ' || v_tabrecregras.k04_sequencial, lRaise, false, false);
      perform fc_debug('<fc_juros> Receita ............: ' || v_tabrecregras.k04_sequencial, lRaise, false, false);
      perform fc_debug('<fc_juros> Codigo J/M .........: ' || v_tabrecregras.k04_codjm,      lRaise, false, false);
      perform fc_debug('<fc_juros> Data Inicial .......: ' || v_tabrecregras.k04_dtini,      lRaise, false, false);
      perform fc_debug('<fc_juros> Data Final .........: ' || v_tabrecregras.k04_dtfim,      lRaise, false, false);
      perform fc_debug('<fc_juros> k02_jurparate ......: ' || v_tabrecregras.k02_jurparate,  lRaise, false, false);
    end if;

    -- itaqui
    v_tipo = v_tabrecregras.k02_jurparate;
    if v_tipo is null then
      v_tipo = 1; -- calcula ate vcto
    end if;

    if lRaise is true then
      perform fc_debug('<fc_juros>',                                  lRaise, false, false);
      perform fc_debug('<fc_juros> v_tipo .............: ' || v_tipo, lRaise, false, false);
    end if;

    if v_tipo = 1 then
      if data_venc < data_certa then

        if lRaise is true then
          perform fc_debug('<fc_juros>', lRaise, false, false);
          perform fc_debug('<fc_juros> Alterando o valor da variavel data_certa('||data_certa||') para o valor da variavel data_venc('||data_venc||')', lRaise, false, false);
        end if;
        data_certa := data_venc;

      end if;
    elsif v_tipo = 2 then -- calcula ate data atual

      if lRaise is true then
          perform fc_debug('<fc_juros>', lRaise, false, false);
          perform fc_debug('<fc_juros> Alterando o valor da variavel data_venc('||data_venc||') para o valor da variavel data_hoje('||data_hoje||')', lRaise, false, false);
      end if;
      data_venc := data_hoje;

    end if;

    if lRaise is true then
      perform fc_debug('<fc_juros>',                                                             lRaise, false, false);
      perform fc_debug('<fc_juros> v_dataopernova .............: ' || v_dataopernova,            lRaise, false, false);
      perform fc_debug('<fc_juros> v_tabrecregras.k04_dtini ...: ' || v_tabrecregras.k04_dtini,  lRaise, false, false);
      perform fc_debug('<fc_juros> v_tabrecregras.k04_dtfim ...: ' || v_tabrecregras.k04_dtfim,  lRaise, false, false);
      perform fc_debug('<fc_juros>',                                                             lRaise, false, false);
      perform fc_debug('<fc_juros> data_certa .................: ' || data_certa,                lRaise, false, false);
      perform fc_debug('<fc_juros> data_venc ..................: ' || data_venc,                 lRaise, false, false);
      perform fc_debug('<fc_juros> data_hoje ..................: ' || data_hoje,                 lRaise, false, false);
      perform fc_debug('<fc_juros>',                                                             lRaise, false, false);
      perform fc_debug('<fc_juros> juros_par ..................: ' || juros_par,                 lRaise, false, false);
      perform fc_debug('<fc_juros> k02_jurpar .................: ' || v_tabrecregras.k02_jurpar, lRaise, false, false);
    end if;

    if v_dataopernova >= v_tabrecregras.k04_dtini and v_dataopernova <= v_tabrecregras.k04_dtfim then

      if data_certa > v_tabrecregras.k04_dtfim then

        if lRaise is true then
           perform fc_debug('<fc_juros> Alterando o valor da variavel data_certa('||data_certa||') para o valor da variavel v_tabrecregras.k04_dtfim('||v_tabrecregras.k04_dtfim||')', lRaise, false, false);
        end if;

        data_certa := v_tabrecregras.k04_dtfim;

        if lRaise is true then
          perform fc_debug('<fc_juros>',                                     lRaise, false, false);
          perform fc_debug('<fc_juros> data_certa: .......: ' || data_certa, lRaise, false, false);
        end if;
      end if;

      if v_tabrecregras.k02_jurpar is not null and v_tabrecregras.k02_jurpar <> 0 then

        if lRaise is true then
          perform fc_debug('<fc_juros>',                                                                                 lRaise, false, false);
          perform fc_debug('<fc_juros> C A L C U L O   D O   J U R O S   D E   F I N A N C I A M E N T O - I N I C I O', lRaise, false, false);
          perform fc_debug('<fc_juros>',                                                                                 lRaise, false, false);
        end if;

        /*
          select que cria a quantidade de meses para o juros de financiamento conforme intervalo de data informado
          o juros deve ser calculado com base na data de operacao
        */
        select count(*)
          into quant_juros
          from generate_series(v_dataopernova, data_hoje - INTERVAL '1 month', INTERVAL '1 month');

        if lRaise is true then
          perform fc_debug('<fc_juros> v_dataopernova ..............: ' || v_dataopernova,            lRaise, false, false);
          perform fc_debug('<fc_juros> data_certa ..................: ' || data_certa,                lRaise, false, false);
          perform fc_debug('<fc_juros> data_hoje ...................: ' || data_hoje,                 lRaise, false, false);
          perform fc_debug('<fc_juros> quant_juros .................: ' || quant_juros,               lRaise, false, false);
          perform fc_debug('<fc_juros> v_tabrecregras.k02_jurpar ...: ' || v_tabrecregras.k02_jurpar, lRaise, false, false);
          perform fc_debug('<fc_juros> v_tabrecregras.k02_juracu ...: ' || v_tabrecregras.k02_juracu, lRaise, false, false);
        end if;

        juros_par := (quant_juros * cast(v_tabrecregras.k02_jurpar as numeric(8,2)));

        --
        -- para juros sob financiamento nao acumulado
        --
        if lRaise is true then
          perform fc_debug('<fc_juros>',                                              lRaise, false, false);
          perform fc_debug('<fc_juros> juros_par ...................: ' || juros_par, lRaise, false, false);
        end if;

        --
        -- para juros sob financiamento acumulado
        --
        if v_tabrecregras.k02_juracu = 't' and quant_juros > 0 then

          if lRaise is true then
            perform fc_debug('<fc_juros>',                                             lRaise, false, false);
            perform fc_debug('<fc_juros> calculando juros de financiamento acumulado', lRaise, false, false);
          end if;

          juros_par := (1 + (v_tabrecregras.k02_jurpar / 100)) ^ quant_juros;
          juros_par := (juros_par - 1) * 100;

          if lRaise is true then
            perform fc_debug('<fc_juros> percentual de juros ...: ' || v_tabrecregras.k02_jurpar, lRaise, false, false);
            perform fc_debug('<fc_juros> numero de periodos ....: ' || quant_juros,               lRaise, false, false);
            perform fc_debug('<fc_juros> juros acumulado .......: ' || juros_par,                 lRaise, false, false);
          end if;

        end if;

        if lRaise is true then
          perform fc_debug('<fc_juros>',                                  lRaise, false, false);
          perform fc_debug('<fc_juros> somando juros de parcelamento...', lRaise, false, false);
        end if;

        if lRaise is true then
          perform fc_debug('<fc_juros>',                                                                                       lRaise, false, false);
          perform fc_debug('<fc_juros> C A L C U L O   D O   J U R O S   D E   F I N A N C I A M E N T O - F I M', lRaise, false, false);
          perform fc_debug('<fc_juros>',                                                                                       lRaise, false, false);
        end if;

      end if;

    end if;

    if v_tipo = 1 then
      data_venc      := v_tabrecregras.k04_dtfim + 1;
      v_dataopernova := v_tabrecregras.k04_dtfim + 1;
    end if;

    data_certa := v_datacertaori;

    if lRaise is true then
      perform fc_debug('<fc_juros> ',                                       lRaise, false, false);
      perform fc_debug('<fc_juros> data_certa .......: ' || data_certa,     lRaise, false, false);
      perform fc_debug('<fc_juros> v_datacertaori ...: ' || v_datacertaori, lRaise, false, false);
      perform fc_debug('<fc_juros> v_dataopernova ...: ' || v_dataopernova, lRaise, false, false);
      perform fc_debug('<fc_juros> ',                                       lRaise, false, false);
    end if;

    if v_tabrecregras.k02_juros = 999 then
      if data_venc < data_certa then
        juros_par := 0;
      end if;
    end if;

    if lRaise is true  then
      perform fc_debug('<fc_juros>  ',lRaise, false, false);
      perform fc_debug('<fc_juros> somando '||juros_par||' em juros_partotal que atualmente esta em: '||juros_partotal, lRaise, false, false);
      perform fc_debug('<fc_juros> ',lRaise, false, false);
    end if;

    juros_partotal := juros_partotal + juros_par;
    juros_par := 0;

  end loop;

  if lRaise is true then
    perform fc_debug('<fc_juros> ',                                                            lRaise, false, false);
    perform fc_debug('<fc_juros> juros_financ .....: ' || juros_par,                           lRaise, false, false);
    perform fc_debug('<fc_juros> juros_partotal ...: ' || juros_partotal,                      lRaise, false, false);
    perform fc_debug('<fc_juros> v_datavencant ....: ' || v_datavencant,                       lRaise, false, false);
    perform fc_debug('<fc_juros> v_datacertaori ...: ' || v_datacertaori,                      lRaise, false, false);
    perform fc_debug('<fc_juros> ',                                                            lRaise, false, false);
    perform fc_debug('<fc_juros> C A L C U L O   D E   J U R O S   P A R C E L A D O - F I M', lRaise, false, false);
    perform fc_debug('<fc_juros> ',                                                            lRaise, false, false);
  end if;

  --
  -- calcula juros normal
  --
  if lRaise is true then
    perform fc_debug('<fc_juros> ', lRaise, false, false);
    perform fc_debug('<fc_juros> INICIO CALCULO NORMAL', lRaise, false, false);
    perform fc_debug('<fc_juros> ', lRaise, false, false);
    perform fc_debug('<fc_juros> juros: '||juros||' - juros_par: '||juros_par, lRaise, false, false);
    perform fc_debug('<fc_juros> ',lRaise, false, false);
    perform fc_debug('<fc_juros> a - v_datavencant: '||v_datavencant||' - data_certa: '||data_certa, lRaise, false, false);
    perform fc_debug('<fc_juros> ',lRaise, false, false);
  end if;

  if v_datavencant < data_certa then

    if lRaise is true then
      perform fc_debug('<fc_juros> ',lRaise, false, false);
      perform fc_debug('<fc_juros> c a l c u l o    d e  j u r o s   n o r m a l',lRaise, false, false);
      perform fc_debug('<fc_juros> ',lRaise, false, false);
    end if;

    v_dataopernova := data_oper;
    data_venc      := v_datavencant;
    data_certa     := v_datacertaori;
    data_base      := data_certa;
    data_venc_base := data_venc;
    jurostotal     := 0;

    iDiaOperacao   := extract(day   from data_hoje);
    iMesOperacao   := extract(month from data_hoje);
    iAnoOperacao   := extract(year  from data_hoje);

    iDiaVencimento := extract(day   from data_venc_base);
    iMesVencimento := extract(month from data_venc_base);
    iAnoVencimento := extract(year  from data_venc_base);

    if imp_carne = 'f' then

      if lRaise is true then
        perform fc_debug('<fc_juros> data certa: '||data_certa,lRaise, false, false);
      end if;


      for v_tabrecregras in
        select *
          from tabrecregrasjm
               inner join tabrecjm on tabrecjm.k02_codjm = tabrecregrasjm.k04_codjm
         where k04_receit = rece_juros
        order by k04_dtini
      loop

        if lRaise then
          perform fc_debug('<fc_juros> _____________________________________________________'     ,lRaise, false, false);
          perform fc_debug('<fc_juros> Receita de Juros: '||rece_juros                            ,lRaise, false, false);
          perform fc_debug('<fc_juros> Regra encontrada: '||v_tabrecregras.k04_sequencial         ,lRaise, false, false);
          perform fc_debug('<fc_juros> Receita: '||v_tabrecregras.k04_sequencial                  ,lRaise, false, false);
          perform fc_debug('<fc_juros> Codigo J/M: '||v_tabrecregras.k04_codjm                    ,lRaise, false, false);
          perform fc_debug('<fc_juros> Data Inicial: '||v_tabrecregras.k04_dtini                  ,lRaise, false, false);
          perform fc_debug('<fc_juros> Data Final: '||v_tabrecregras.k04_dtfim                    ,lRaise, false, false);
          perform fc_debug('<fc_juros> voltando data de vencimento para original: '||v_datavencant,lRaise, false, false);
        end if;
        data_venc := v_datavencant;

        if lRaise is true then
          perform fc_debug('<fc_juros> '                                 ,lRaise, false, false);
          perform fc_debug('<fc_juros> Verificamos se a data de vencimento base (data_venc_base) estah entre a data inicial e final da tabela de regras de juros e multa da receita (tabrecregrasjm)',lRaise, false, false);
          perform fc_debug('<fc_juros> '                                 ,lRaise, false, false);
          perform fc_debug('<fc_juros> data_venc_base: '||data_venc_base ,lRaise, false, false);
          perform fc_debug('<fc_juros> v_dataopernova: '||v_dataopernova ,lRaise, false, false);
          perform fc_debug('<fc_juros> data_certa: '||data_certa         ,lRaise, false, false);
          perform fc_debug('<fc_juros> data_venc: '||data_venc           ,lRaise, false, false);
          perform fc_debug('<fc_juros> juros: '||juros                   ,lRaise, false, false);
        end if;

        if data_venc_base >= v_tabrecregras.k04_dtini and data_venc_base <= v_tabrecregras.k04_dtfim then

          if lRaise is true then
             perform fc_debug('<fc_juros> ', lRaise, false, false);
             perform fc_debug('<fc_juros> v_dataopernova > v_tabrecregras.k04_dtini e v_dataopernova <= v_tabrecregras.k04_dtfim', lRaise, false, false);
             perform fc_debug('<fc_juros> *****************************************************', lRaise, false, false);
             perform fc_debug('<fc_juros> >> ENTROU NO TIPO DE JURO: '||v_tabrecregras.k04_codjm, lRaise, false, false);
             perform fc_debug('<fc_juros> *****************************************************', lRaise, false, false);
             perform fc_debug('<fc_juros> ', lRaise, false, false);
             perform fc_debug('<fc_juros> ', lRaise, false, false);
          end if;

          data_venc := data_venc_base;
          if data_venc_base > v_tabrecregras.k04_dtfim then

            if lRaise is true then
              perform fc_debug('<fc_juros> Data de Vencimento (data_venc_base) '||data_venc_base||' maior que a data final (k04_dtfim) '||v_tabrecregras.k04_dtfim||' da tabela de regras de juros e multa da receita (tabrecregrasjm)',lRaise, false, false);
              perform fc_debug('<fc_juros> Alteramos a data de vencimento (data_venc) para a ultima data da tabelas de regras de juros e multa da receita (tabrecregrasjm): '||v_tabrecregras.k04_dtfim, lRaise, false, false);
            end if;
            data_venc := v_tabrecregras.k04_dtfim;

          else

            if data_venc_base < v_tabrecregras.k04_dtini then

              if lRaise is true then
                perform fc_debug('<fc_juros> Data de Vencimento (data_venc_base) '||data_venc_base||' menor que a data inicial (k04_dtini) '||v_tabrecregras.k04_dtini||' da tabela de regras de juros e multa da receita (tabrecregrasjm)', lRaise, false, false);
                perform fc_debug('<fc_juros> Alteramos a data de vencimento (data_venc) para a data inicial da tabelas de regras de juros e multa da receita (tabrecregrasjm): '||v_tabrecregras.k04_dtini, lRaise, false, false);
              end if;

              data_venc := v_tabrecregras.k04_dtini;

            else

              if v_datavencant > v_tabrecregras.k04_dtini then

                data_venc := v_datavencant;
                if lRaise is true then
                  perform fc_debug('<fc_juros> Data de Vencimento Anterior!? (v_datavencant) '||v_datavencant||' maior que a data inicial (k04_dtini) '||v_tabrecregras.k04_dtini||' da tabela de regras de juros e multa da receita (tabrecregrasjm)',lRaise, false, false);
                  perform fc_debug('<fc_juros> Alteramos a data de vencimento (data_venc) para a data de vencimento anterior (v_datavencant): '||v_datavencant, lRaise, false, false);
                end if;

              else

                if lRaise is true then
                  perform fc_debug('<fc_juros> Nada eh alterado em termos de data de vencimento', lRaise, false, false);
                end if;

              end if;
            end if;

          end if;

          if lRaise is true then
             perform fc_debug('<fc_juros> ',lRaise, false, false);
             perform fc_debug('<fc_juros> ',lRaise, false, false);
          end if;

          if data_venc < v_tabrecregras.k04_dtfim then
            data_certa := v_tabrecregras.k04_dtfim;
          end if;

          if data_certa > v_datacertaori then
            data_certa := v_datacertaori;
          end if;

          if lRaise is true then
            perform fc_debug('<fc_juros> entrou tipo de juro e multa: '||v_tabrecregras.k04_codjm||' - data_certa: '||data_certa||' - data_venc: '||data_venc||' - data_venc_base: '||data_venc_base||' - juros: '||v_tabrecregras.k02_juros, lRaise, false, false);
          end if;

          if data_venc < data_certa then

            if lRaise is true then
              perform fc_debug('<fc_juros> vencimento MENOR que data certa - data certa: '||data_certa, lRaise, false, false);
            end if;

            if extract(year from data_certa) > extract(year from data_venc) then

              if lRaise is true then
                perform fc_debug('<fc_juros>       ano da data_certa maior que ano do data_venc', lRaise, false, false);
                perform fc_debug('<fc_juros>       juros (1): '||juros, lRaise, false, false);
              end if;

              v_juroscalc := (((extract(year from data_certa) - 1) - (extract(year from data_venc))) * 12);
              if lRaise is true then
                perform fc_debug('<fc_juros>          1 - v_juroscalc: '||v_juroscalc, lRaise, false, false);
              end if;
              juros := juros + v_juroscalc;

              if lRaise is true then
                perform fc_debug('<fc_juros>          juros: '||juros, lRaise, false, false);
              end if;

              v_juroscalc := extract(month from data_certa);
              if lRaise is true then
                perform fc_debug('<fc_juros>          2 - v_juroscalc: '||v_juroscalc, lRaise, false, false);
              end if;
              juros := juros + v_juroscalc;

              if lRaise is true then
                perform fc_debug('<fc_juros>          juros: '||juros, lRaise, false, false);
              end if;

              if (extract(year from (data_venc + 1))) = extract(year from data_venc) then
                v_juroscalc := (13 - (extract(month from (data_venc + 1))));
                if lRaise is true then
                  perform fc_debug('<fc_juros>          3 - v_juroscalc: '||v_juroscalc, lRaise, false, false);
                end if;
                juros := juros + v_juroscalc;
              end if;

              if lRaise is true then
                perform fc_debug('<fc_juros>             juros: '||juros, lRaise, false, false);
              end if;

            else

              if lRaise is true then
                perform fc_debug('<fc_juros>       ano da data_certa menor que ano do data_venc', lRaise, false, false);
                perform fc_debug('<fc_juros>       juros (2): '||juros, lRaise, false, false);
              end if;

              mesdatacerta := extract(month from data_certa);
              mesdatavenc  := extract(month from (data_venc + 1));

              if lRaise is true then
                perform fc_debug('<fc_juros>       mesdatacerta: '||mesdatacerta||' - mesdatavenca: '||mesdatavenc, lRaise, false, false);
              end if;

              v_juroscalc := (extract(month from data_certa) + 1) - extract(month from (data_venc + 1));

              if lRaise is true then
                perform fc_debug('<fc_juros>          4 - v_juroscalc: '||v_juroscalc, lRaise, false, false);
              end if;

              juros := juros + v_juroscalc;

            end if;

            if lRaise is true then
              perform fc_debug('<fc_juros>          *** juros: '||juros||' - juros por dia: '||v_tabrecregras.k02_jurdia, lRaise, false, false);
            end if;

            --
            -- se juros por dia, cobrar proporcional a partir do dia de vencimento
            --
            if v_tabrecregras.k02_jurdia = 't' then
              --
              -- Quando o calculo de juros é diario, desconsideramos os juros calculados anteriormente
              --
              juros  := 0;

              if lRaise is true then
                perform fc_debug('<fc_juros>                             ', lRaise, false, false);
                perform fc_debug('<fc_juros> ----------------------------', lRaise, false, false);
                perform fc_debug('<fc_juros> INICIO CALCULO JUROS DIARIO.', lRaise, false, false);
                perform fc_debug('<fc_juros>          juros por dia: '||v_tabrecregras.k02_jurdia, lRaise, false, false);
                perform fc_debug('<fc_juros> ----------------------------', lRaise, false, false);
                perform fc_debug('<fc_juros>                             ', lRaise, false, false);
              end if;

              /*
                select que cria os dias conforme intervalo de data informado
              */
              select count(*)
                into dia
                from generate_series(data_venc, data_certa - INTERVAL '1 day', INTERVAL '1 day');

              if lRaise is true then
                perform fc_debug('<fc_juros> quantidade de dias de atraso: '||dia, lRaise, false, false);
              end if;

              juross := ( cast(v_tabrecregras.k02_juros as numeric) / 30) * dia;
              juros  := juros + juross;

              if lRaise is true then
                perform fc_debug('<fc_juros> calculo do percentual diario: (v_tabrecregras.k02_juros: '||v_tabrecregras.k02_juros||' / 30) * '||dia, lRaise, false, false);
                perform fc_debug('<fc_juros> juross: '||juross||' / v_tabrecregras.k02_juros: '||v_tabrecregras.k02_juros||' / juros: '||juros, lRaise, false, false);
              end if;

              if lRaise is true then
                perform fc_debug('<fc_juros>                             ', lRaise, false, false);
                perform fc_debug('<fc_juros> -------------------------', lRaise, false, false);
                perform fc_debug('<fc_juros> FIM CALCULO JUROS DIARIO.', lRaise, false, false);
                perform fc_debug('<fc_juros> -------------------------', lRaise, false, false);
                perform fc_debug('<fc_juros>                             ', lRaise, false, false);
              end if;

            end if;

            if lRaise is true then
              perform fc_debug('<fc_juros>       juros: '||juros, lRaise, false, false);
            end if;

            v_juroscalc := cast(v_tabrecregras.k02_juros as numeric(8,2));
            if lRaise is true then
              perform fc_debug('<fc_juros>       5 - v_juroscalc: '||v_juroscalc, lRaise, false, false);
              perform fc_debug('<fc_juros>       6 - juros: '||juros, lRaise, false, false);
            end if;

            if juros is not null and juros <> 0 and v_tabrecregras.k02_jurdia <> 't' then

              if lRaise is true then
                perform fc_debug('<fc_juros>       7 - juros existe...', lRaise, false, false);
              end if;

              data_comercial := data_venc + 1;

              if lRaise is true then
                perform fc_debug('<fc_juros>       7.5 - data_comercial: '||data_comercial||' - data_venc: '||data_venc, lRaise, false, false);
              end if;

              if extract(month from data_comercial) = extract(month from data_venc) then
                if lRaise is true then
                  perform fc_debug('<fc_juros>       8 - mes da data comercial = mes da data vencimento...', lRaise, false, false);
                end if;

                if extract(day from data_venc) >= extract(day from data_certa) then
                  if lRaise is true then
                    perform fc_debug('<fc_juros>       9 - dia da data de vencimento >= dia da data certa...', lRaise, false, false);
                  end if;
                  if lRaise is true then
                    perform fc_debug('<fc_juros> antes: '||juros, lRaise, false, false);
                  end if;

                  -- modificacao feita em carazinho pois os juros estavam negativos em alguns casos
                  -- entao coloquei esse if abaixo antes de diminuir 1 para testar
                  ------if v_tabrecregras.k02_jurdia <> 't' then
                    juros := juros - 1;
                  ------end if;

                  if lRaise is true then
                    perform fc_debug('<fc_juros> depois: '||juros, lRaise, false, false);
                  end if;
                end if;
              end if;
            end if;

            if lRaise is true then
              perform fc_debug('<fc_juros>       10 - v_juroscalc: '||v_juroscalc||' - juros: '||juros, lRaise, false, false);
            end if;

            if v_tabrecregras.k02_jurdia <> 't' then
               juros := juros * v_juroscalc;

               if lRaise is true then
                 perform fc_debug('<fc_juros>       11 - juros: '||juros, lRaise, false, false);
               end if;
            end if;

            if lRaise is true then
              perform fc_debug('<fc_juros>    old: v_dataopernova: '||v_dataopernova||' - data_venc: '||data_venc||' - data_certa: '||data_certa, lRaise, false, false);
              perform fc_debug('<fc_juros>    new: v_dataopernova: '||v_dataopernova||' - data_venc: '||data_venc||' - data_certa: '||data_certa||' - data_venc_base: '||data_venc_base, lRaise, false, false);
              perform fc_debug('<fc_juros> ', lRaise, false, false);
            end if;
            v_dataopernova := v_tabrecregras.k04_dtfim + 1;
            data_venc_base := v_dataopernova;
            data_certa     := v_datacertaori;

          else
            if lRaise is true then
              perform fc_debug('<fc_juros>       vencimento maior que data certa..............', lRaise, false, false);
            end if;
          end if;

        else
          if lRaise is true then
            perform fc_debug('<fc_juros> ', lRaise, false, false);
            perform fc_debug('<fc_juros> data de operacao  f o r a  periodo das regras', lRaise, false, false);
            perform fc_debug('<fc_juros> ', lRaise, false, false);
          end if;
        end if;

        if v_tabrecregras.k02_juros = 999 then

          if lRaise is true then
            perform fc_debug('<fc_juros> k02_juros == 999 - juros: '||juros, lRaise, false, false);
          end if;

          juros := 0;

          if data_venc < data_certa then
            if lRaise is true then
              perform fc_debug('<fc_juros> data_venc ('||data_venc||') < data_certa ('||data_certa||')',lRaise, false, false);
            end if;
            select  i02_valor
            into v_selicatual
            from infla
            where i02_codigo = 'SELIC'
              and i02_valor <> 0
            order by i02_data desc limit 1;

            if lRaise is true then
              perform fc_debug('<fc_juros> juros: '||juros||' - selic: '||v_selicatual, lRaise, false, false);
            end if;

            juros := fc_vlinf('SELIC'::varchar,data_venc);

            if lRaise is true then
              perform fc_debug('<fc_juros> juros: '||juros, lRaise, false, false);
            end if;

            if juros < 0 then
              juros := 0;
            end if;

          end if;
        end if;

        if lRaise is true then
          perform fc_debug('<fc_juros> somando '||juros||' em jurostotal que atualmente esta em: '||jurostotal, lRaise, false, false);
          perform fc_debug('<fc_juros> '                                                     ,lRaise, false, false);
          perform fc_debug('<fc_juros> FIM CALCULO DA REGRA: '||v_tabrecregras.k04_sequencial,lRaise, false, false);
          perform fc_debug('<fc_juros> _____________________________________________________',lRaise, false, false);
          perform fc_debug('<fc_juros> '                                                     ,lRaise, false, false);
        end if;

        jurostotal := jurostotal + juros;
        juros      := 0;

      end loop;

    end if;

  else

    if lRaise is true then
      perform fc_debug('<fc_juros> a - v_datavencant: '||v_datavencant||' - data_certa: '||data_certa, lRaise, false, false);
    end if;

  end if;

  if v_tabrec.k02_juroslimite > 0 and jurostotal > v_tabrec.k02_juroslimite then

    jurostotal := v_tabrec.k02_juroslimite;

    if lRaise is true then
      perform fc_debug('<fc_juros> limite de juros definido para ateh '||jurostotal, lRaise, false, false);
    end if;

  end if;

  if lRaise is true  then
    perform fc_debug('<fc_juros> juros: '||juros||' - juros_par: '||juros_par, lRaise, false, false);
    perform fc_debug('<fc_juros> juros_financiamento: '||juros_partotal||' - juros mora: '||jurostotal, lRaise, false, false);
  end if;

  jurosretornar = (jurostotal::float8 + juros_partotal::float8) / 100::float8;

  if lRaise is true  then
    perform fc_debug('<fc_juros> jurosretornar: '||jurosretornar                                    ,lRaise,false,false);
    perform fc_debug('<fc_juros> '                                                                  ,lRaise,false,false);
    perform fc_debug('<fc_juros> '                                                                  ,lRaise,false,false);
    perform fc_debug('<fc_juros> ------------------------------------------------------------------',lRaise,false,true);
  end if;

  return jurosretornar;

end;
$$ language 'plpgsql';

SQL;
        $this->execute($sSql);
    }
}
