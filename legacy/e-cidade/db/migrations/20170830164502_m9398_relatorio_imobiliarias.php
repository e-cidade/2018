<?php

use Classes\PostgresMigration;

class M9398RelatorioImobiliarias extends PostgresMigration
{
    public function up()
    {
        $sSql = <<<SQL
            create or replace function fc_agua_relatorio_imobiliaria_valor_receita(integer, integer, integer) returns float8 as
            $$
              select coalesce(sum(k00_valor), 0)
                from arrecad
               where k00_numpre = $1
                 and k00_numpar = $2
                 and k00_receit = $3;
            $$
            language 'sql';

            create or replace function fc_agua_relatorio_imobiliaria_valor_extras(integer, integer, integer, integer) returns float8 as
            $$
              select coalesce(sum(arrecad.k00_valor), 0)
                from (select arrenumcgm.*
                        from arrenumcgm
                             inner join arreinstit  on arreinstit.k00_numpre = arrenumcgm.k00_numpre
                                                   and arreinstit.k00_instit = 4
                       where arrenumcgm.k00_numcgm = $4) as arrenumcgm

                     inner join arrecad       on arrecad.k00_numpre =  arrenumcgm.k00_numpre
                                             and arrecad.k00_tipo   <> $1
                                             and extract(year from arrecad.k00_dtvenc)  = $2
                                             and extract(month from arrecad.k00_dtvenc) = $3

               where not exists (select arrenaoagrupa.k00_numpre
                                   from arrenaoagrupa
                                  where arrenaoagrupa.k00_numpre = arrenumcgm.k00_numpre);
            $$
            language 'sql';

            drop type tp_agua_relatorio_imobiliaria cascade;

            create type tp_agua_relatorio_imobiliaria as (
              x01_entrega        integer,
              x01_matric         integer,
              x54_sequencial     integer,
              z01_nome           varchar,
              z01_ender          varchar,
              k00_dtvenc         date,
              k00_numpre         varchar,
              k00_numpar         varchar,
              valor_serv_agua    float8,
              valor_serv_esgoto  float8,
              valor_cons_agua    float8,
              valor_cole_esgoto  float8,
              valor_extras       float8
            );




            create or replace function fc_agua_relatorio_imobiliaria(integer, integer, integer, varchar, integer) returns setof tp_agua_relatorio_imobiliaria as
            $$
            declare
              iArretipo  alias for $1;
              iExercicio alias for $2;
              iParcela   alias for $3;
              sEntrega   alias for $4;
              iLimit     alias for $5;

              sSql     text;
              rImoveis record;
              rRetorno tp_agua_relatorio_imobiliaria%ROWTYPE;

              nValorServAgua    float8;
              nValorServEsgoto  float8;
              nValorConsAgua    float8;
              nValorColeEsgoto  float8;
              nValorExtras      float8;

              iReceitaServAgua    integer;
              iReceitaServEsgoto  integer;
              iReceitaConsAgua    integer;
              iReceitaColeEsgoto  integer;
            begin

              select x25_receit
                into iReceitaServAgua
                from aguaconsumotipo
               where x25_codconsumotipo = 4;

              select x25_receit
                into iReceitaServEsgoto
                from aguaconsumotipo
               where x25_codconsumotipo = 5;

              select x25_receit
                into iReceitaConsAgua
                from aguaconsumotipo
               where x25_codconsumotipo = 6;

              select x25_receit
                into iReceitaColeEsgoto
                from aguaconsumotipo
               where x25_codconsumotipo = 7;

              sSql := '
                  select x01_entrega,
                         x01_matric,
                         x54_sequencial,
                         a.z01_nome,
                         trim(j14_nome) || \', \' || x01_numero || \'  \' || trim(coalesce(x38_complemento,\'\'))                            as z01_ender,
                         fc_agua_datavencimento(x22_exerc, x22_mes, x01_matric)                                                            as k00_dtvenc,
                         to_char(x22_mes, \'00\')||\'/12\'::varchar                                                                         as k00_numpar,
                         x22_numpre                                                                                                        as k00_numpre,
                         fc_agua_relatorio_imobiliaria_valor_receita(x22_numpre, x22_mes, '||iReceitaServAgua||')                    as valor_serv_agua,
                         fc_agua_relatorio_imobiliaria_valor_receita(x22_numpre, x22_mes, '||iReceitaServEsgoto||')                  as valor_serv_esgoto,
                         fc_agua_relatorio_imobiliaria_valor_receita(x22_numpre, x22_mes, '||iReceitaConsAgua||')                    as valor_cons_agua,
                         fc_agua_relatorio_imobiliaria_valor_receita(x22_numpre, x22_mes, '||iReceitaColeEsgoto||')                  as valor_cole_esgoto,
                         fc_agua_relatorio_imobiliaria_valor_extras('||iArretipo||', x22_exerc, x22_mes,

                           case when x54_condominio is false
                                 and x54_emitiroutrosdebitos = true                         then x54_cgm

                                when x54_condominio is true
                                 and x54_emitiroutrosdebitos = true
                                 and x22_manual = \'2\'                                     then x54_cgm

                                when x54_condominio is true
                                 and x54_emitiroutrosdebitos = false
                                 and x22_manual = \'1\'
                                 and x38_emitiroutrosdebitos is true                        then x38_cgm

                                                                                            else null
                           end
                                                                                                                              )            as valor_extras

                    from aguabase
                         inner join aguacontrato on x54_aguabase = x01_matric
                         inner join aguacalc  on x22_aguacontrato = x54_sequencial
                                             and x22_exerc  = '||iExercicio||'
                                             and x22_mes    = '||iParcela||'
                         left  join aguacontratoeconomia  on x22_manual = \'1\'
                                                         and x38_aguacontrato = x54_sequencial
                                                         and x22_aguacontratoeconomia = x38_sequencial
                         inner join ruas on j14_codigo = x01_codrua
                         inner join cgm a on a.z01_numcgm = x54_cgm
                         left  join iptucadzonaentrega on j85_codigo = x01_entrega ';

              if trim(sEntrega) <> '' and sEntrega is not null then
                sSql := sSql || ' where x01_entrega in ('||sEntrega||') ';
              end if;

              sSql := sSql || '
                group by x01_entrega,
                         x01_matric,
                         x54_sequencial,
                         a.z01_nome,
                         j14_nome,
                         x01_numero,
                         x22_exerc,
                         x22_mes,
                         x22_numpre,
                         x22_manual,
                         x54_cgm,
                         x38_emitiroutrosdebitos,
                         x38_complemento,
                         x38_cgm';

              sSql := sSql || ' order by x01_entrega, z01_ender ';

              if iLimit > 0 then
                sSql := sSql || ' limit '||iLimit;
              end if;

              for rImoveis in execute sSql
              loop
                rRetorno.x01_entrega     := rImoveis.x01_entrega;
                rRetorno.x01_matric      := rImoveis.x01_matric;
                rRetorno.x54_sequencial  := rImoveis.x54_sequencial;
                rRetorno.z01_nome        := rImoveis.z01_nome;
                rRetorno.z01_ender       := rImoveis.z01_ender;
                rRetorno.k00_dtvenc      := rImoveis.k00_dtvenc;
                rRetorno.k00_numpre      := rImoveis.k00_numpre;
                rRetorno.k00_numpar      := rImoveis.k00_numpar;

                rRetorno.valor_serv_agua    := rImoveis.valor_serv_agua;
                rRetorno.valor_serv_esgoto  := rImoveis.valor_serv_esgoto;
                rRetorno.valor_cons_agua    := rImoveis.valor_cons_agua;
                rRetorno.valor_cole_esgoto  := rImoveis.valor_cole_esgoto;
                rRetorno.valor_extras       := rImoveis.valor_extras;

                return next rRetorno;
              end loop;

              return;

            end;
            $$ language 'plpgsql';


            create or replace function fc_agua_relatorio_imobiliaria(integer, integer, integer, varchar) returns setof tp_agua_relatorio_imobiliaria as
            $$
              select * from fc_agua_relatorio_imobiliaria($1, $2, $3, $4, 0);
            $$
            language 'sql';

            create or replace function fc_agua_relatorio_imobiliaria(integer, integer, integer) returns setof tp_agua_relatorio_imobiliaria as
            $$
              select * from fc_agua_relatorio_imobiliaria($1, $2, $3, null, 0);
            $$
            language 'sql';
SQL;
        $this->execute($sSql);
    }

    public function down()
    {
        $sSql = <<<SQL
            create or replace function fc_agua_relatorio_imobiliaria_valor_receita(integer, integer, integer) returns float8 as
            $$
              select coalesce(sum(k00_valor), 0)
                from arrecad
               where k00_numpre = $1
                 and k00_numpar = $2
                 and k00_receit = $3;
            $$
            language 'sql';

            create or replace function fc_agua_relatorio_imobiliaria_valor_extras(integer, integer, integer, integer) returns float8 as
            $$
              select coalesce(sum(arrecad.k00_valor), 0)
                from (select arrematric.*
                        from arrematric
                             inner join arreinstit  on arreinstit.k00_numpre = arrematric.k00_numpre
                                                   and arreinstit.k00_instit = 4
                       where arrematric.k00_matric = $4) as arrematric

                     inner join arrecad       on arrecad.k00_numpre =  arrematric.k00_numpre
                                             and arrecad.k00_tipo   <> $1
                                             and extract(year from arrecad.k00_dtvenc)  = $2
                                             and extract(month from arrecad.k00_dtvenc) = $3

               where not exists (select arrenaoagrupa.k00_numpre
                                   from arrenaoagrupa
                                  where arrenaoagrupa.k00_numpre = arrematric.k00_numpre);
            $$
            language 'sql';

            drop type tp_agua_relatorio_imobiliaria cascade;

            create type tp_agua_relatorio_imobiliaria as (
              x01_entrega   integer,
              x01_matric    integer,
              z01_nome      varchar,
              z01_ender     varchar,
              k00_dtvenc    date,
              k00_numpar    varchar,
              valor_agua    float8,
              valor_esgoto  float8,
              valor_excesso float8,
              valor_extras  float8
            );




            create or replace function fc_agua_relatorio_imobiliaria(integer, integer, integer, varchar, integer) returns setof tp_agua_relatorio_imobiliaria as
            $$
            declare
              iArretipo  alias for $1;
              iExercicio alias for $2;
              iParcela   alias for $3;
              sEntrega   alias for $4;
              iLimit     alias for $5;

              sSql     text;
              rImoveis record;
              rRetorno tp_agua_relatorio_imobiliaria%ROWTYPE;

              nValorAgua    float8;
              nValorEsgoto  float8;
              nValorExcesso float8;
              nValorExtras  float8;

              iReceitaAgua    integer;
              iReceitaEsgoto  integer;
              iReceitaExcesso integer;
            begin

              select x25_receit
                into iReceitaAgua
                from aguaconsumotipo
               where x25_codconsumotipo = (select x18_consumoagua from aguaconf where x18_anousu = iExercicio limit 1);

              select x25_receit
                into iReceitaEsgoto
                from aguaconsumotipo
               where x25_codconsumotipo = (select x18_consumoesgoto from aguaconf where x18_anousu = iExercicio limit 1);

              select x25_receit
                into iReceitaExcesso
                from aguaconsumotipo
               where x25_codconsumotipo = (select x18_consumoexcesso from aguaconf where x18_anousu = iExercicio limit 1);

              sSql := '
                  select x01_entrega,
                         x01_matric,
                         case
                           when b.z01_nome is not null then
                             b.z01_nome
                           else
                             a.z01_nome
                         end as z01_nome,
                         trim(j14_nome) || \', \' || x01_numero || \'  \' || trim(x11_complemento) as z01_ender,
                         fc_agua_datavencimento('||cast(iExercicio as text)||', '||cast(iParcela as text)||', x01_matric) as k00_dtvenc,
                         '|| quote_literal(trim(to_char(iParcela, '00'))||'/12') ||'::varchar as k00_numpar,
                         x22_numpre as k00_numpre,
                         fc_agua_relatorio_imobiliaria_valor_receita(x22_numpre,'||iParcela||', '||iReceitaAgua||') as valor_agua,
                         fc_agua_relatorio_imobiliaria_valor_receita(x22_numpre,'||iParcela||', '||iReceitaEsgoto||') as valor_esgoto,
                         fc_agua_relatorio_imobiliaria_valor_receita(x22_numpre,'||iParcela||', '||iReceitaExcesso||') as valor_excesso,
                         fc_agua_relatorio_imobiliaria_valor_extras('||iArretipo||', '||iExercicio||', '||iParcela||', x01_matric) as valor_extras
                    from aguabase
                         inner join aguacalc  on x22_matric = x01_matric
                                             and x22_exerc  = '||iExercicio||'
                                             and x22_mes    = '||iParcela||'
                         inner join ruas on j14_codigo = x01_codrua
                         left  join cgm a on a.z01_numcgm = x01_numcgm
                         left  join cgm b on b.z01_numcgm = x01_promit
                         left  join aguaconstr on x11_matric = x01_matric and x11_tipo = \'P\'
                         left  join iptucadzonaentrega on j85_codigo = x01_entrega ';

              if trim(sEntrega) <> '' and sEntrega is not null then
                sSql := sSql || ' where x01_entrega in ('||sEntrega||') ';
              end if;

              sSql := sSql || ' order by x01_entrega, z01_ender ';

              if iLimit > 0 then
                sSql := sSql || ' limit '||iLimit;
              end if;

              --raise info 'Sql: %', sSql;

              for rImoveis in execute sSql
              loop
                rRetorno.x01_entrega := rImoveis.x01_entrega;
                rRetorno.x01_matric  := rImoveis.x01_matric;
                rRetorno.z01_nome    := rImoveis.z01_nome;
                rRetorno.z01_ender   := rImoveis.z01_ender;
                rRetorno.k00_dtvenc  := rImoveis.k00_dtvenc;
                rRetorno.k00_numpar  := rImoveis.k00_numpar;
                rRetorno.valor_agua    := rImoveis.valor_agua;
                rRetorno.valor_esgoto  := rImoveis.valor_esgoto;
                rRetorno.valor_excesso := rImoveis.valor_excesso;
                rRetorno.valor_extras  := rImoveis.valor_extras;

                return next rRetorno;
              end loop;

              return;

            end;
            $$ language 'plpgsql';


            create or replace function fc_agua_relatorio_imobiliaria(integer, integer, integer, varchar) returns setof tp_agua_relatorio_imobiliaria as
            $$
              select * from fc_agua_relatorio_imobiliaria($1, $2, $3, $4, 0);
            $$
            language 'sql';

            create or replace function fc_agua_relatorio_imobiliaria(integer, integer, integer) returns setof tp_agua_relatorio_imobiliaria as
            $$
              select * from fc_agua_relatorio_imobiliaria($1, $2, $3, null, 0);
            $$
            language 'sql';
SQL;

        $this->execute($sSql);
    }
}
