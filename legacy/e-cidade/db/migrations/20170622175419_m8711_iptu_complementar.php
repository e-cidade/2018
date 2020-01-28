<?php

use Classes\PostgresMigration;

class M8711IptuComplementar extends PostgresMigration
{
    public function up()
    {
        $sSql =
<<<SQL
drop function if exists fc_iptu_complementar(integer,integer,numeric,boolean);
drop function if exists fc_iptu_complementar(integer,integer,numeric,integer,boolean);
create or replace function fc_iptu_complementar(
    iMatricula integer,
    iAnousu integer,
    nValor numeric,
    iReceita integer,
    lRaise boolean )
returns integer as
$$
declare

  dDataCorrente   date;
  dDataVencimento date;

  iTipoDebito     integer;
  iProcDiver      integer;
  iNumcgm         integer;
  iNumpre         integer;
  iCodDiver       integer;
  iInstit         integer;

  nValorCorrigido numeric;

  sRetornoArrecad  text;
  sDescricaoDebito text;

  rDadosIptu      record;

  iDiasVencimento integer;
begin

  select fc_getsession('DB_datausu')::date
    into dDataCorrente;

  select *
    from tmpdadosiptu
    into rDadosIptu;

  select q92_diasvcto,
         q82_venc
    into iDiasVencimento,
         dDataVencimento
    from cadvencdesc
         inner join cadvenc on q82_codigo = q92_codigo
   where q82_codigo = rDadosIptu.codvenc
     and q82_venc > dDataCorrente
   order by q82_venc
   limit 1;

  iDiasVencimento := coalesce(iDiasVencimento, 0);
  dDataVencimento := coalesce(dDataVencimento, dDataCorrente);

  if dDataVencimento < dDataCorrente then
    dDataVencimento := dDataCorrente + iDiasVencimento;
  end if;

  if extract(year from dDataVencimento) > iAnousu then
    dDataVencimento := (iAnousu || '-12-31')::date;
  end if;

  perform fc_debug(' <iptu_complementar> ENTROU!', true);
  perform fc_debug(' <iptu_complementar> Valor do IPTU Complementar '||nValor, true);

  select j18_tipodebitorecalculo,
         k00_descr
    into iTipoDebito,
         sDescricaoDebito
    from cfiptu
         inner join arretipo on k00_tipo = j18_tipodebitorecalculo
   where j18_anousu = iAnousu;

  if iTipoDebito is null then
    raise exception '<erro>Tipo de débito não configurado para o recálculo de IPTU.</erro>';
  end if;

  perform fc_debug(' <iptu_complementar> Tipo de débito IPTU Complementar '||iTipoDebito, true);

  select dv09_procdiver
    into iProcDiver
    from procdiver
         inner join arretipo on arretipo.k00_tipo = procdiver.dv09_tipo
   where arretipo.k00_tipo = iTipoDebito
     and dv09_receit = iReceita;

  if not found then
    raise exception '<erro>Nenhuma procedência cadastrada para a receita % e tipo de débito %.</erro>',iReceita, sDescricaoDebito;
  end if;

  perform fc_debug(' <iptu_complementar> Procedência de Diversos '||iProcDiver, true);

  select fc_corre(iReceita, dDataCorrente, nValor, dDataCorrente, iAnousu, dDataVencimento::date + 10)
    into nValorCorrigido;

  perform fc_debug(' <iptu_complementar> Valor Corrigido '||nValorCorrigido, true);

  select j01_numcgm
    into iNumcgm
    from iptubase
   where j01_matric = iMatricula;

  select nextval('numpref_k03_numpre_seq')
    into iNumpre;

  select nextval('diversos_dv05_coddiver_seq')
    into iCodDiver;

  select codigo
    into iInstit
    from db_config
   where prefeitura is true;

  insert into diversos
   values (iCodDiver,
           iNumcgm,
           dDataCorrente,
           iAnousu,
           iNumpre,
           nValorCorrigido,
           iProcDiver,
           1,
           dDataVencimento,
           dDataVencimento,
           0,
           dDataCorrente,
           nValorCorrigido,
           'Recalculo IPTU quitado' ,
           iInstit);

  insert into arrematric
  values (iNumpre,
          iMatricula,
          100);

  select fc_geraarrecad(iTipoDebito,iNumpre,true,1, false)
    into sRetornoArrecad;

  perform fc_debug(' <iptu_complementar> Retorno da fc_geraarrecad '||sRetornoArrecad, true);

  insert into tmpipturecalculonump values(iMatricula, iAnousu, iNumpre);

  return iNumpre;

end;
$$  language 'plpgsql';

SQL;

        $this->execute($sSql);
    }

    public function down()
    {
        $sSql =
<<<SQL

drop function if exists fc_iptu_complementar(integer,integer,numeric,boolean);
drop function if exists fc_iptu_complementar(integer,integer,numeric,integer,boolean);
create or replace function fc_iptu_complementar(
    iMatricula integer,
    iAnousu integer,
    nValor numeric,
    iReceita integer,
    lRaise boolean )
returns boolean as
$$
declare

  dDataCorrente   date;
  dDataVencimento date;

  iTipoDebito     integer;
  iProcDiver      integer;
  iNumcgm         integer;
  iNumpre         integer;
  iCodDiver       integer;
  iInstit         integer;

  nValorCorrigido numeric;

  sRetornoArrecad  text;
  sDescricaoDebito text;

  rDadosIptu      record;

  iDiasVencimento integer;
begin

  select fc_getsession('DB_datausu')::date
    into dDataCorrente;

  select *
    from tmpdadosiptu
    into rDadosIptu;

  select q92_diasvcto,
         q82_venc
    into iDiasVencimento,
         dDataVencimento
    from cadvencdesc
         inner join cadvenc on q82_codigo = q92_codigo
   where q82_codigo = rDadosIptu.codvenc
     and q82_venc > dDataCorrente
   order by q82_venc
   limit 1;

  iDiasVencimento := coalesce(iDiasVencimento, 0);
  dDataVencimento := coalesce(dDataVencimento, dDataCorrente);

  if dDataVencimento < dDataCorrente then
    dDataVencimento := dDataCorrente + iDiasVencimento;
  end if;

  if extract(year from dDataVencimento) > iAnousu then
    dDataVencimento := (iAnousu || '-12-31')::date;
  end if;

  perform fc_debug(' <iptu_complementar> ENTROU!', true);
  perform fc_debug(' <iptu_complementar> Valor do IPTU Complementar '||nValor, true);

  select j18_tipodebitorecalculo,
         k00_descr
    into iTipoDebito,
         sDescricaoDebito
    from cfiptu
         inner join arretipo on k00_tipo = j18_tipodebitorecalculo
   where j18_anousu = iAnousu;

  if iTipoDebito is null then
    raise exception '<erro>Tipo de débito não configurado para o recálculo de IPTU.</erro>';
  end if;

  perform fc_debug(' <iptu_complementar> Tipo de débito IPTU Complementar '||iTipoDebito, true);

  select dv09_procdiver
    into iProcDiver
    from procdiver
         inner join arretipo on arretipo.k00_tipo = procdiver.dv09_tipo
   where arretipo.k00_tipo = iTipoDebito
     and dv09_receit = iReceita;

  if not found then
    raise exception '<erro>Nenhuma procedência cadastrada para a receita % e tipo de débito %.</erro>',iReceita, sDescricaoDebito;
  end if;

  perform fc_debug(' <iptu_complementar> Procedência de Diversos '||iProcDiver, true);

  select fc_corre(iReceita, dDataCorrente, nValor, dDataCorrente, iAnousu, dDataVencimento::date + 10)
    into nValorCorrigido;

  perform fc_debug(' <iptu_complementar> Valor Corrigido '||nValorCorrigido, true);

  select j01_numcgm
    into iNumcgm
    from iptubase
   where j01_matric = iMatricula;

  select nextval('numpref_k03_numpre_seq')
    into iNumpre;

  select nextval('diversos_dv05_coddiver_seq')
    into iCodDiver;

  select codigo
    into iInstit
    from db_config
   where prefeitura is true;

  insert into diversos
   values (iCodDiver,
           iNumcgm,
           dDataCorrente,
           iAnousu,
           iNumpre,
           nValorCorrigido,
           iProcDiver,
           1,
           dDataVencimento,
           dDataVencimento,
           0,
           dDataCorrente,
           nValorCorrigido,
           'Recalculo IPTU quitado' ,
           iInstit);

  insert into arrematric
   values(iNumpre,
          iMatricula,
          100);

  select fc_geraarrecad(iTipoDebito,iNumpre,true,1, false)
    into sRetornoArrecad;

  perform fc_debug(' <iptu_complementar> Retorno da fc_geraarrecad '||sRetornoArrecad, true);

  return true;

end;
$$  language 'plpgsql';

SQL;

        $this->execute($sSql);
    }

}
