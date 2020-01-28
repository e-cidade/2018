<?php

use Classes\PostgresMigration;

class M8711IptuCompensacaoAutomatica extends PostgresMigration
{

    public function up()
    {
        $sSql =
<<<SQL
drop function if exists fc_iptu_compensacao_automatica(numeric, integer, integer, integer, integer, boolean);
create or replace function fc_iptu_compensacao_automatica(nValor numeric, iReceit integer, iNumpre integer, iMatricula integer, iAnousu integer, lRaise boolean)
returns boolean as $$
declare

  iCgm integer;
  iReciboCreditoReceita integer;
  iReciboCreditoNumpre integer;
  iReciboCreditoTipo integer;

  iAbatimentoCredito integer;
  iAbatimentoCompensacao integer;
  iAbatimentoUtilizacao integer;

  iReciboCompensacaoNumpre integer;
  iReciboCompensacaoAutomaticaNumpre integer;

  iPercentual numeric;

  rRecibo record;

begin

  select j01_numcgm
    into iCgm
    from iptubase
   where j01_matric = iMatricula;

  select nextval('numpref_k03_numpre_seq')
    into iReciboCreditoNumpre;

  select j18_receitacreditorecalculo
    into iReciboCreditoReceita
    from cfiptu
   where j18_anousu = iAnousu;

   select coalesce((select q92_tipo
                      from cadvencdesc
                     where q92_codigo = (select codvenc
                                           from tmpdadosiptu)
                     limit 1), 1)
    into iReciboCreditoTipo;

  insert into arrenumcgm (k00_numcgm,
                          k00_numpre) values (iCgm,
                                              iReciboCreditoNumpre);

  insert into arrematric (k00_numpre,
                          k00_matric,
                          k00_perc) values (iReciboCreditoNumpre,
                                            iMatricula,
                                            100);

  insert into recibo (k00_numcgm,
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
                      k00_numnov) values (iCgm,
                                          fc_getsession('DB_datausu')::date,
                                          iReciboCreditoReceita,
                                          505,
                                          nValor,
                                          fc_getsession('DB_datausu')::date,
                                          iReciboCreditoNumpre,
                                          1,
                                          1,
                                          0,
                                          iReciboCreditoTipo,
                                          0);

  select nextval('abatimento_k125_sequencial_seq')
    into iAbatimentoCredito;

  insert into abatimento (k125_sequencial,
                          k125_tipoabatimento,
                          k125_datalanc,
                          k125_hora,
                          k125_usuario,
                          k125_instit,
                          k125_valor,
                          k125_perc,
                          k125_valordisponivel,
                          k125_abatimentosituacao,
                          k125_observacao) values (iAbatimentoCredito,
                                                   3,
                                                   fc_getsession('DB_datausu')::date,
                                                   extract(hour from now()) || ':' || extract(min from now()),
                                                   fc_getsession('DB_id_usuario')::integer,
                                                   fc_getsession('DB_instit')::integer,
                                                   nValor,
                                                   100,
                                                   nValor,
                                                   1,
                                                   'Crédito de compensação automatica gerado no recálculo de IPTU para a matrícula: ' || iMatricula);

  insert into abatimentorecibo (k127_sequencial,
                                k127_abatimento,
                                k127_numprerecibo,
                                k127_numpreoriginal) values (nextval('abatimentorecibo_k127_sequencial_seq'),
                                                             iAbatimentoCredito,
                                                             iReciboCreditoNumpre,
                                                             null);

  insert into arrehist (k00_numpre,
                        k00_numpar,
                        k00_hist,
                        k00_dtoper,
                        k00_hora,
                        k00_id_usuario,
                        k00_histtxt,
                        k00_limithist,
                        k00_idhist) values (iReciboCreditoNumpre,
                                            0,
                                            505,
                                            fc_getsession('DB_datausu')::date,
                                            extract(hour from now()) || ':' || extract(min from now()),
                                            fc_getsession('DB_id_usuario')::integer,
                                            '',
                                            null,
                                            nextval('arrehist_k00_idhist_seq'));

  select nextval('abatimento_k125_sequencial_seq')
    into iAbatimentoCompensacao;

  insert into abatimento (k125_sequencial,
                          k125_tipoabatimento,
                          k125_datalanc,
                          k125_hora,
                          k125_usuario,
                          k125_instit,
                          k125_valor,
                          k125_perc,
                          k125_valordisponivel,
                          k125_abatimentosituacao,
                          k125_observacao) values (iAbatimentoCompensacao,
                                                   4,
                                                   fc_getsession('DB_datausu')::date,
                                                   extract(hour from now()) || ':' || extract(min from now()),
                                                   fc_getsession('DB_id_usuario')::integer,
                                                   fc_getsession('DB_instit')::integer,
                                                   nValor,
                                                   100,
                                                   null,
                                                   1,
                                                   'Compensação automatica gerada no recálculo de IPTU para a matrícula: ' || iMatricula);

  select nextval('numpref_k03_numpre_seq')
    into iReciboCompensacaoNumpre;

  select nextval('numpref_k03_numpre_seq')
    into iReciboCompensacaoAutomaticaNumpre;

  insert into db_reciboweb (k99_numpre,
                            k99_numpar,
                            k99_numpre_n,
                            k99_codbco,
                            k99_codage,
                            k99_numbco,
                            k99_desconto,
                            k99_tipo,
                            k99_origem) values (iNumpre,
                                                1,
                                                iReciboCompensacaoNumpre,
                                                0,
                                                '0',
                                                0,
                                                0,
                                                1,
                                                1);

  select *
    into rRecibo
    from fc_recibo(iReciboCompensacaoNumpre, fc_getsession('DB_datausu')::date, fc_getsession('DB_datausu')::date, iAnousu);

  insert into abatimentorecibo (k127_sequencial,
                                k127_abatimento,
                                k127_numprerecibo,
                                k127_numpreoriginal) values (nextval('abatimentorecibo_k127_sequencial_seq'),
                                                             iAbatimentoCompensacao,
                                                             iReciboCompensacaoNumpre,
                                                             iReciboCompensacaoAutomaticaNumpre);

  insert into arrehist (k00_numpre,
                        k00_numpar,
                        k00_hist,
                        k00_dtoper,
                        k00_hora,
                        k00_id_usuario,
                        k00_histtxt,
                        k00_limithist,
                        k00_idhist) values (iReciboCompensacaoAutomaticaNumpre,
                                            0,
                                            502,
                                            fc_getsession('DB_datausu')::date,
                                            extract(hour from now()) || ':' || extract(min from now()),
                                            fc_getsession('DB_id_usuario')::integer,
                                            '',
                                            null,
                                            nextval('arrehist_k00_idhist_seq'));

  insert into recibo (k00_numcgm,
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
                      k00_numnov) values (iCgm,
                                          fc_getsession('DB_datausu')::date,
                                          iReceit,
                                          504,
                                          nValor,
                                          fc_getsession('DB_datausu')::date,
                                          iReciboCompensacaoAutomaticaNumpre,
                                          1,
                                          1,
                                          0,
                                          iReciboCreditoTipo,
                                          0);

  iPercentual := ((nValor * 100) / (select sum(k00_valor) from recibopaga where k00_numnov = iReciboCompensacaoNumpre));

  update arrecad
     set k00_valor = k00_valor - (k00_valor * (iPercentual / 100))
   where k00_numpre = iNumpre;

  insert into arrepaga (k00_numcgm,
                        k00_dtoper,
                        k00_receit,
                        k00_hist,
                        k00_valor,
                        k00_dtvenc,
                        k00_numpre,
                        k00_numpar,
                        k00_numtot,
                        k00_numdig,
                        k00_conta,
                        k00_dtpaga) values (iCgm,
                                            fc_getsession('DB_datausu')::date,
                                            iReceit,
                                            504,
                                            nValor,
                                            fc_getsession('DB_datausu')::date,
                                            iReciboCompensacaoAutomaticaNumpre,
                                            1,
                                            1,
                                            0,
                                            0,
                                            fc_getsession('DB_datausu')::date);

  insert into arrehist (k00_numpre,
                        k00_numpar,
                        k00_hist,
                        k00_dtoper,
                        k00_hora,
                        k00_id_usuario,
                        k00_histtxt,
                        k00_limithist,
                        k00_idhist) values (iReciboCompensacaoAutomaticaNumpre,
                                            0,
                                            918,
                                            fc_getsession('DB_datausu')::date,
                                            extract(hour from now()) || ':' || extract(min from now()),
                                            fc_getsession('DB_id_usuario')::integer,
                                            '',
                                            null,
                                            nextval('arrehist_k00_idhist_seq'));

  update abatimento
     set k125_valordisponivel = 0
   where k125_sequencial = iAbatimentoCredito;

  update abatimento
     set k125_perc = iPercentual
   where k125_sequencial = iAbatimentoCompensacao;

  select nextval('abatimentoutilizacao_k157_sequencial_seq')
    into iAbatimentoUtilizacao;

  insert into abatimentoutilizacao (k157_sequencial,
                                    k157_tipoutilizacao,
                                    k157_data,
                                    k157_valor,
                                    k157_hora,
                                    k157_usuario,
                                    k157_abatimento,
                                    k157_observacao) values (iAbatimentoUtilizacao,
                                                             2,
                                                             fc_getsession('DB_datausu')::date,
                                                             nValor,
                                                             extract(hour from now()) || ':' || extract(min from now()),
                                                             fc_getsession('DB_id_usuario')::integer,
                                                             iAbatimentoCredito,
                                                             '');

  insert into abatimentoutilizacaodestino (k170_utilizacao,
                                           k170_numpre,
                                           k170_numpar,
                                           k170_receit,
                                           k170_hist,
                                           k170_tipo,
                                           k170_valor) values (iAbatimentoUtilizacao,
                                                               iNumpre,
                                                               1,
                                                               iReceit,
                                                               (select k00_hist
                                                                  from arrecad
                                                                 where k00_numpre = iNumpre
                                                                   and k00_receit = iReceit),
                                                               iReciboCreditoTipo,
                                                               nValor);

  return true;

end;
$$ language 'plpgsql';

SQL;

        $this->execute($sSql);
    }

    public function down()
    {
        $sSql =
<<<SQL
drop function if exists fc_iptu_compensacao_automatica(numeric, integer, integer, integer, integer, boolean);
SQL;

        $this->execute($sSql);
    }


}
