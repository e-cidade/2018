<?php

use Classes\PostgresMigration;

class M8711IptuGeraCreditoRecalculo extends PostgresMigration
{
    public function up()
    {
        $sSql =
<<<SQL

create or replace function fc_iptu_geracreditorecalculo(matricula integer,
                                                        ano integer,
                                                        valorCredito numeric,
                                                        raise boolean)
returns boolean as
$$
declare

  receitaCredito   integer;
  numpreNovo       integer;
  cgm              integer;
  codigoAbatimento integer;

  rDadosIptu       record;

begin

  if raise is true then
    perform fc_debug(' <iptu_geracreditorecalculo> Gerando crédito', raise, false, false);
    perform fc_debug(' <iptu_geracreditorecalculo> Valor: ' || valorCredito, raise, false, false);
    perform fc_debug(' <iptu_geracreditorecalculo> Matrícula: ' || matricula, raise, false, false);
  end if;

  select *
    from tmpdadosiptu
    into rDadosIptu;

  select j01_numcgm
    into cgm
    from iptubase
   where j01_matric = matricula;

  if raise is true then
    perform fc_debug(' <iptu_geracreditorecalculo> CGM: ' || cgm, raise, false, false);
  end if;

  select j18_receitacreditorecalculo
    into receitaCredito
    from cfiptu
   where j18_anousu = ano;

  if not found or receitaCredito is null then
    raise exception '<erro>Receita de Crédito não configurada para o recálculo de IPTU.</erro>';
  end if;

  select nextval('numpref_k03_numpre_seq')
    into numpreNovo;

  insert into recibo ( k00_numcgm,
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
                       k00_numnov )
       values ( cgm,
                fc_getsession('DB_datausu')::date,
                receitaCredito,
                505,
                valorCredito,
                fc_getsession('DB_datausu')::date,
                numpreNovo,
                1,
                1,
                0,
                coalesce((select q92_tipo
                   from cadvencdesc
                  where q92_codigo = rDadosIptu.codvenc
                  limit 1), 1),
                0 );

  select nextval('abatimento_k125_sequencial_seq')
    into codigoAbatimento;

  insert into abatimento ( k125_sequencial,
                           k125_tipoabatimento,
                           k125_datalanc,
                           k125_hora,
                           k125_usuario,
                           k125_instit,
                           k125_valor,
                           k125_perc,
                           k125_valordisponivel,
                           k125_abatimentosituacao,
                           k125_observacao )
      values ( codigoAbatimento,
               3, -- Abatimento do tipo crédito
               fc_getsession('DB_datausu')::date,
               extract(hour from now()) || ':' || extract(min from now()),
               fc_getsession('DB_id_usuario')::integer,
               fc_getsession('DB_instit')::integer,
               valorCredito,
               100,
               valorCredito,
               1,
               'Crédito gerado no recálculo de IPTU para a matrícula: ' || matricula );

  insert into abatimentorecibo ( k127_sequencial,
                                 k127_abatimento,
                                 k127_numprerecibo,
                                 k127_numpreoriginal )
       values ( nextval('abatimentorecibo_k127_sequencial_seq'),
                codigoAbatimento,
                numpreNovo,
                null );

  insert into arrehist ( k00_numpre,
                         k00_numpar,
                         k00_hist,
                         k00_dtoper,
                         k00_hora,
                         k00_id_usuario,
                         k00_histtxt,
                         k00_limithist,
                         k00_idhist )
       values ( numpreNovo,
                0,
                505,
                fc_getsession('DB_datausu')::date,
                extract(hour from now()) || ':' || extract(min from now()),
                fc_getsession('DB_id_usuario')::integer,
                '',
                null,
                nextval('arrehist_k00_idhist_seq') );

  insert into arrenumcgm
       values ( cgm,
                numpreNovo );

  insert into arrematric ( k00_numpre,
                           k00_matric,
                           k00_perc )
       values ( numpreNovo,
                matricula,
                100 );

  if raise is true then
    perform fc_debug(' <iptu_geracreditorecalculo> Abatimento: ' || codigoAbatimento, raise, false, false);
    perform fc_debug(' <iptu_geracreditorecalculo> Numpre: ' || numpreNovo, raise, false, false);
  end if;

  insert into tmpipturecalculocreditonump values(matricula, ano, numpreNovo);

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

create or replace function fc_iptu_geracreditorecalculo( matricula integer,
                                                         ano integer,
                                                         valorCredito numeric,
                                                         raise boolean )
returns boolean as
$$
declare

  receitaCredito   integer;
  numpreNovo       integer;
  cgm              integer;
  codigoAbatimento integer;

  rDadosIptu       record;

begin

  if raise is true then
    perform fc_debug(' <iptu_geracreditorecalculo> Gerando crédito', raise, false, false);
    perform fc_debug(' <iptu_geracreditorecalculo> Valor: ' || valorCredito, raise, false, false);
    perform fc_debug(' <iptu_geracreditorecalculo> Matrícula: ' || matricula, raise, false, false);
  end if;

  select *
    from tmpdadosiptu
    into rDadosIptu;

  select j01_numcgm
    into cgm
    from iptubase
   where j01_matric = matricula;

  if raise is true then
    perform fc_debug(' <iptu_geracreditorecalculo> CGM: ' || cgm, raise, false, false);
  end if;

  select j18_receitacreditorecalculo
    into receitaCredito
    from cfiptu
   where j18_anousu = ano;

  if not found or receitaCredito is null then
    raise exception '<erro>Receita de Crédito não configurada para o recálculo de IPTU.</erro>';
  end if;

  select nextval('numpref_k03_numpre_seq')
    into numpreNovo;

  insert into recibo ( k00_numcgm,
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
                       k00_numnov )
       values ( cgm,
                fc_getsession('DB_datausu')::date,
                receitaCredito,
                505,
                valorCredito,
                fc_getsession('DB_datausu')::date,
                numpreNovo,
                1,
                1,
                0,
                coalesce((select q92_tipo
                   from cadvencdesc
                  where q92_codigo = rDadosIptu.codvenc
                  limit 1), 1),
                0 );

  select nextval('abatimento_k125_sequencial_seq')
    into codigoAbatimento;

  insert into abatimento ( k125_sequencial,
                           k125_tipoabatimento,
                           k125_datalanc,
                           k125_hora,
                           k125_usuario,
                           k125_instit,
                           k125_valor,
                           k125_perc,
                           k125_valordisponivel,
                           k125_abatimentosituacao,
                           k125_observacao )
      values ( codigoAbatimento,
               3, -- Abatimento do tipo crédito
               fc_getsession('DB_datausu')::date,
               extract(hour from now()) || ':' || extract(min from now()),
               fc_getsession('DB_id_usuario')::integer,
               fc_getsession('DB_instit')::integer,
               valorCredito,
               100,
               valorCredito,
               1,
               'Crédito gerado no recálculo de IPTU para a matrícula: ' || matricula );

  insert into abatimentorecibo ( k127_sequencial,
                                 k127_abatimento,
                                 k127_numprerecibo,
                                 k127_numpreoriginal )
       values ( nextval('abatimentorecibo_k127_sequencial_seq'),
                codigoAbatimento,
                numpreNovo,
                null );

  insert into arrehist ( k00_numpre,
                         k00_numpar,
                         k00_hist,
                         k00_dtoper,
                         k00_hora,
                         k00_id_usuario,
                         k00_histtxt,
                         k00_limithist,
                         k00_idhist )
       values ( numpreNovo,
                0,
                505,
                fc_getsession('DB_datausu')::date,
                extract(hour from now()) || ':' || extract(min from now()),
                fc_getsession('DB_id_usuario')::integer,
                '',
                null,
                nextval('arrehist_k00_idhist_seq') );

  insert into arrenumcgm
       values ( cgm,
                numpreNovo );

  insert into arrematric ( k00_numpre,
                           k00_matric,
                           k00_perc )
       values ( numpreNovo,
                matricula,
                100 );

  if raise is true then
    perform fc_debug(' <iptu_geracreditorecalculo> Abatimento: ' || codigoAbatimento, raise, false, false);
    perform fc_debug(' <iptu_geracreditorecalculo> Numpre: ' || numpreNovo, raise, false, false);
  end if;

  return true;
end;

$$ language 'plpgsql';

SQL;

      $this->execute($sSql);
    }
}
