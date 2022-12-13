<?php

use Classes\PostgresMigration;

class M7985IssqnComplementarIrregular extends PostgresMigration
{
    public function up()
    {
        $sSqlStartSession = "select fc_startsession()";

        $sSqlSetSession = "
select fc_putsession('DB_instit'::varchar , codigo::varchar),
       fc_putsession('DB_datausu'::varchar, current_date::varchar),
       fc_putsession('DB_anousu'::varchar, extract(year from current_date)::varchar),
       fc_putsession('DB_id_usuario'::varchar, '1'),
       fc_putsession('DB_use_pcasp'::varchar, '1')
  from configuracoes.db_config
 where prefeitura is true;
";
        $sSqlViewInscrSimplesComp = "
create or replace view v_inscr_simples_comp as
  select isscadsimples.q38_inscr as inscr,
         isscadsimples.q38_dtinicial::timestamp as inicio,
         case when isscadsimplesbaixa is null
          then date(now())::timestamp
          else isscadsimplesbaixa.q39_dtbaixa::timestamp
         end as fim
    from isscadsimples
         left join isscadsimplesbaixa on isscadsimplesbaixa.q39_isscadsimples = isscadsimples.q38_sequencial
        ";

        $sSqlView = "
create or replace view v_issqn_simples_complementar_irregular as
  select distinct
         issbase.q02_inscr   as issbase_q02_inscr,
         issbase.q02_numcgm  as issbase_q02_numcgm,
         issbase.q02_memo    as issbase_q02_memo,
         issbase.q02_tiplic  as issbase_q02_tiplic,
         issbase.q02_regjuc  as issbase_q02_regjuc,
         issbase.q02_inscmu  as issbase_q02_inscmu,
         issbase.q02_obs     as issbase_q02_obs,
         issbase.q02_dtcada  as issbase_q02_dtcada,
         issbase.q02_dtinic  as issbase_q02_dtinic,
         issbase.q02_dtbaix  as issbase_q02_dtbaix,
         issbase.q02_capit   as issbase_q02_capit,
         issbase.q02_cep     as issbase_q02_cep,
         issbase.q02_dtjunta as issbase_q02_dtjunta,
         issbase.q02_ultalt  as issbase_q02_ultalt,
         issbase.q02_dtalt   as issbase_q02_dtalt,

         issvar.q05_codigo as issvar_q05_codigo,
         issvar.q05_numpre as issvar_q05_numpre,
         issvar.q05_numpar as issvar_q05_numpar,
         issvar.q05_valor  as issvar_q05_valor,
         issvar.q05_ano    as issvar_q05_ano,
         issvar.q05_mes    as issvar_q05_mes,
         issvar.q05_histor as issvar_q05_histor,
         issvar.q05_aliq   as issvar_q05_aliq,
         issvar.q05_bruto  as issvar_q05_bruto,
         issvar.q05_vlrinf as issvar_q05_vlrinf,

         arrepaga.k00_numcgm as arrepaga_k00_numcgm,
         arrepaga.k00_dtoper as arrepaga_k00_dtoper,
         arrepaga.k00_receit as arrepaga_k00_receit,
         arrepaga.k00_hist   as arrepaga_k00_hist,
         arrepaga.k00_valor  as arrepaga_k00_valor,
         arrepaga.k00_dtvenc as arrepaga_k00_dtvenc,
         arrepaga.k00_numpre as arrepaga_k00_numpre,
         arrepaga.k00_numpar as arrepaga_k00_numpar,
         arrepaga.k00_numtot as arrepaga_k00_numtot,
         arrepaga.k00_numdig as arrepaga_k00_numdig,
         arrepaga.k00_conta  as arrepaga_k00_conta,
         arrepaga.k00_dtpaga as arrepaga_k00_dtpaga,

         issvar_numpre_calc.q05_codigo as issvar_numpre_calc_q05_codigo,
         issvar_numpre_calc.q05_numpre as issvar_numpre_calc_q05_numpre,
         issvar_numpre_calc.q05_numpar as issvar_numpre_calc_q05_numpar,
         issvar_numpre_calc.q05_valor  as issvar_numpre_calc_q05_valor,
         issvar_numpre_calc.q05_ano    as issvar_numpre_calc_q05_ano,
         issvar_numpre_calc.q05_mes    as issvar_numpre_calc_q05_mes,
         issvar_numpre_calc.q05_histor as issvar_numpre_calc_q05_histor,
         issvar_numpre_calc.q05_aliq   as issvar_numpre_calc_q05_aliq,
         issvar_numpre_calc.q05_bruto  as issvar_numpre_calc_q05_bruto,
         issvar_numpre_calc.q05_vlrinf as issvar_numpre_calc_q05_vlrinf,

         arrecad.k00_numpre as arrecad_k00_numpre,
         arrecad.k00_numpar as arrecad_k00_numpar,
         arrecad.k00_numcgm as arrecad_k00_numcgm,
         arrecad.k00_dtoper as arrecad_k00_dtoper,
         arrecad.k00_receit as arrecad_k00_receit,
         arrecad.k00_hist   as arrecad_k00_hist,
         arrecad.k00_valor  as arrecad_k00_valor,
         arrecad.k00_dtvenc as arrecad_k00_dtvenc,
         arrecad.k00_numtot as arrecad_k00_numtot,
         arrecad.k00_numdig as arrecad_k00_numdig,
         arrecad.k00_tipo   as arrecad_k00_tipo,
         arrecad.k00_tipojm as arrecad_k00_tipojm,

         datas_comps_simples.ano as datas_comps_simples_ano,
         datas_comps_simples.mes as datas_comps_simples_mes

    from issbase
         inner join arreinscr on arreinscr.k00_inscr = issbase.q02_inscr
         inner join issvar    on issvar.q05_numpre = arreinscr.k00_numpre
         left  join isscalc   on isscalc.q01_numpre = issvar.q05_numpre
         left  join recibopaga on recibopaga.k00_numpre = issvar.q05_numpre
         inner join arrepaga  on arrepaga.k00_numpre = issvar.q05_numpre
         inner join issvar as issvar_numpre_calc  on issvar_numpre_calc.q05_ano = issvar.q05_ano
                                                 and issvar_numpre_calc.q05_mes = issvar.q05_mes
         inner join isscalc as isscalc_numpre_calc  on isscalc_numpre_calc.q01_numpre = issvar_numpre_calc.q05_numpre
                                                   and isscalc_numpre_calc.q01_inscr = issbase.q02_inscr
         inner join arrecad  on arrecad.k00_numpre = issvar_numpre_calc.q05_numpre
                            and arrecad.k00_numpar = issvar_numpre_calc.q05_numpar
         left  join isscadsimples on isscadsimples.q38_inscr = issbase.q02_inscr
         left  join isscadsimplesbaixa on isscadsimplesbaixa.q39_isscadsimples = isscadsimples.q38_sequencial
         left  join (select extract(year from datas.data::timestamp) as ano,
                            extract(month from datas.data::timestamp) as mes,
                            datas.inscr as inscr
                       from (select v_inscr_simples_comp.inscr,
                                    generate_series(v_inscr_simples_comp.inicio::timestamp, v_inscr_simples_comp.fim::timestamp, '1 month'::interval) as data
                               from v_inscr_simples_comp
                            ) as datas
                     ) as datas_comps_simples  on datas_comps_simples.ano = issvar.q05_ano
                                             and datas_comps_simples.mes = issvar.q05_mes
                                             and datas_comps_simples.inscr = issbase.q02_inscr
   where isscalc is null
     and recibopaga is null
     and arrepaga.k00_numtot = 1
     and issvar_numpre_calc.q05_valor = 0
     and issvar_numpre_calc.q05_bruto = 0
     and issvar_numpre_calc.q05_vlrinf = 0
     and ((isscadsimples is not null and isscadsimplesbaixa is null and datas_comps_simples is not null) or
          (isscadsimples is null and isscadsimplesbaixa is null))
     and (   issbase.q02_dtbaix is null
          or (    issvar.q05_ano <= extract(year from issbase.q02_dtbaix)
              and issvar.q05_mes <= extract(month from issbase.q02_dtbaix)
             )
         )
   order by issbase.q02_inscr desc,
            arrecad.k00_numpre desc,
            arrepaga.k00_numpre desc,
            arrepaga.k00_numpar desc
";

        $sSqlFunc = "

create or replace function fc_issqn_simples_complementar_7985() returns boolean as $$
declare
    record_table            record;
    record_table_inscr_comp record;
    record_table_inscr      record;
begin

  drop table if exists w_v_issqn_simples_complementar_7985;
  create table w_v_issqn_simples_complementar_7985 as select * from v_issqn_simples_complementar_irregular;

  for record_table in
      select distinct issbase_q02_inscr
        from w_v_issqn_simples_complementar_7985
       order by issbase_q02_inscr
  loop


    for record_table_inscr_comp in

      select distinct on (issvar_numpre_calc_q05_ano, issvar_numpre_calc_q05_mes)
             issvar_q05_numpre,
             issvar_numpre_calc_q05_ano,
             issvar_numpre_calc_q05_mes
        from v_issqn_simples_complementar_irregular
       where issbase_q02_inscr = record_table.issbase_q02_inscr
       order by issvar_numpre_calc_q05_ano desc,
                issvar_numpre_calc_q05_mes desc

    loop

      for record_table_inscr in
          select *
            from w_v_issqn_simples_complementar_7985
           where issvar_q05_numpre = record_table_inscr_comp.issvar_q05_numpre
      loop

          update issarqsimplesregissvar
             set q68_issvar = record_table_inscr.issvar_numpre_calc_q05_codigo
           where q68_issvar = record_table_inscr.issvar_q05_codigo;

          update issarquivoretencaoregistroissvar
             set q146_issvar = record_table_inscr.issvar_numpre_calc_q05_codigo
           where q146_issvar = record_table_inscr.issvar_q05_codigo;

          update issvardiv
             set q19_issvar = record_table_inscr.issvar_numpre_calc_q05_codigo
           where q19_issvar = record_table_inscr.issvar_q05_codigo;

          update issvarlev
             set q18_codlev = record_table_inscr.issvar_numpre_calc_q05_codigo
           where q18_codlev = record_table_inscr.issvar_q05_codigo;

          update issvarnotas
             set q06_codigo = record_table_inscr.issvar_numpre_calc_q05_codigo
           where q06_codigo = record_table_inscr.issvar_q05_codigo;

          delete from arreinscr
           where arreinscr.k00_numpre = record_table_inscr.issvar_q05_numpre;

          delete from issvar
           where issvar.q05_numpre = record_table_inscr.issvar_q05_numpre
             and issvar.q05_numpar = record_table_inscr.issvar_q05_numpar;

          delete from arrecad
           where arrecad.k00_numpre = record_table_inscr.arrecad_k00_numpre
             and arrecad.k00_numpar = record_table_inscr.arrecad_k00_numpar;

          update issvar
             set q05_numpre = record_table_inscr.issvar_q05_numpre,
                 q05_numpar = record_table_inscr.issvar_q05_numpar,
                 q05_valor  = record_table_inscr.issvar_q05_valor,
                 q05_ano    = record_table_inscr.issvar_q05_ano,
                 q05_mes    = record_table_inscr.issvar_q05_mes,
                 q05_histor = record_table_inscr.issvar_q05_histor,
                 q05_aliq   = record_table_inscr.issvar_q05_aliq,
                 q05_bruto  = record_table_inscr.issvar_q05_bruto,
                 q05_vlrinf = record_table_inscr.issvar_q05_vlrinf
           where q05_codigo = record_table_inscr.issvar_numpre_calc_q05_codigo
             and q05_numpre = record_table_inscr.issvar_numpre_calc_q05_numpre
             and q05_numpar = record_table_inscr.issvar_numpre_calc_q05_numpar
             and q05_valor  = record_table_inscr.issvar_numpre_calc_q05_valor
             and q05_ano    = record_table_inscr.issvar_numpre_calc_q05_ano
             and q05_mes    = record_table_inscr.issvar_numpre_calc_q05_mes
             and q05_histor = record_table_inscr.issvar_numpre_calc_q05_histor
             and q05_aliq   = record_table_inscr.issvar_numpre_calc_q05_aliq
             and q05_bruto  = record_table_inscr.issvar_numpre_calc_q05_bruto
             and q05_vlrinf = record_table_inscr.issvar_numpre_calc_q05_vlrinf;

          update arrecant
             set k00_numpre = record_table_inscr.issvar_numpre_calc_q05_numpre,
                 k00_numpar = record_table_inscr.issvar_numpre_calc_q05_numpar,
                 k00_numtot = record_table_inscr.arrecad_k00_numtot
           where k00_numpre = record_table_inscr.issvar_q05_numpre
             and k00_numpar = record_table_inscr.issvar_q05_numpar;

          update arrepaga
             set k00_numpre = record_table_inscr.issvar_numpre_calc_q05_numpre,
                 k00_numpar = record_table_inscr.issvar_numpre_calc_q05_numpar,
                 k00_numtot = record_table_inscr.arrecad_k00_numtot
           where k00_numpre = record_table_inscr.issvar_q05_numpre
             and k00_numpar = record_table_inscr.issvar_q05_numpar;

          RAISE NOTICE '% - comp: %/%',
                       record_table.issbase_q02_inscr,
                       record_table_inscr_comp.issvar_numpre_calc_q05_ano,
                       record_table_inscr_comp.issvar_numpre_calc_q05_mes;

      end loop;

    end loop;

  end loop;

  return true;

end;

$$ language 'plpgsql'";

        $sSqlExec = "select * from fc_issqn_simples_complementar_7985()";

        $this->execute($sSqlStartSession);
        $this->execute($sSqlSetSession);
        $this->execute($sSqlViewInscrSimplesComp);
        $this->execute($sSqlView);
        $this->execute($sSqlFunc);
        $this->execute($sSqlExec);
    }
}
