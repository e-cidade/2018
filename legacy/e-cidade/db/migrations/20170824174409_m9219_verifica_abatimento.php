<?php

use Classes\PostgresMigration;

class M9219VerificaAbatimento extends PostgresMigration
{

    public function up()
    {

        $sSql =
<<<SQL
drop function fc_verifica_abatimento(integer,integer);
drop function fc_verifica_abatimento(integer,integer,integer);
drop function fc_verifica_abatimento(integer,integer,integer,integer);
drop function fc_verifica_abatimento(integer,integer,integer,integer,integer);


create or replace function fc_verifica_abatimento(integer,integer) returns boolean as
$$
declare

  iTipoAbatimento alias for $1;
  iNumpre         alias for $2;

  lAbatimento     boolean;

  begin

    select fc_verifica_abatimento(iTipoAbatimento,iNumpre,null,null,null)::boolean into lAbatimento;

    if lAbatimento then
      return true;
    else
      return false;
    end if;

  end;
$$ language 'plpgsql';


create or replace function fc_verifica_abatimento(integer,integer,integer) returns boolean as
$$
declare

  iTipoAbatimento alias for $1;
  iNumpre         alias for $2;
  iNumpar         alias for $3;

  lAbatimento     boolean;

  begin

    select fc_verifica_abatimento(iTipoAbatimento,iNumpre,iNumpar,null,null)::boolean into lAbatimento;

    if lAbatimento then
      return true;
    else
      return false;
    end if;

  end;
$$ language 'plpgsql';


create or replace function fc_verifica_abatimento(integer,integer,integer,integer) returns boolean as
$$
declare

  iTipoAbatimento alias for $1;
  iNumpre         alias for $2;
  iNumpar         alias for $3;
  iReceita        alias for $4;

  lAbatimento     boolean;

  begin

    select fc_verifica_abatimento(iTipoAbatimento,iNumpre,iNumpar,iReceita,null)::boolean into lAbatimento;

    if lAbatimento then
      return true;
    else
      return false;
    end if;

  end;
$$ language 'plpgsql';


create or replace function fc_verifica_abatimento(integer,integer,integer,integer,integer) returns boolean as
$$
declare

  iTipoAbatimento alias for $1;
  iNumpre         alias for $2;
  iNumpar         alias for $3;
  iReceita        alias for $4;
  iHistorico      alias for $5;

  sSql            text;

  rAbatimento     record;

  begin

    if iNumpre is null then
      return false;
    end if;

    sSql := ' select 1 as abatimento
                from abatimento
                     inner join abatimentoarreckey on abatimentoarreckey.k128_abatimento = abatimento.k125_sequencial
                     inner join arreckey           on arreckey.k00_sequencial            = abatimentoarreckey.k128_arreckey
               where abatimento.k125_tipoabatimento = '||iTipoAbatimento||'
                 and arreckey.k00_numpre            = '||iNumpre;

    if iNumpar is not null then
      sSql := sSql || ' and arreckey.k00_numpar = '||iNumpar;
    end if;

    if iReceita is not null then
      sSql := sSql || ' and arreckey.k00_receit = '||iReceita;
    end if;

    if iHistorico is not null then
      sSql := sSql || ' and arreckey.k00_hist   = '||iHistorico;
    end if;

    sSql := sSql || ' limit 1 ';

    execute sSql into rAbatimento;

    if rAbatimento.abatimento is not null then
      return true;
    else
      return false;
    end if;

  end;

$$  language 'plpgsql';
SQL;

        $this->execute($sSql);

    }

    public function down()
    {

<<<SQL

drop function fc_verifica_abatimento(integer,integer);
drop function fc_verifica_abatimento(integer,integer,integer);
drop function fc_verifica_abatimento(integer,integer,integer,integer);
drop function fc_verifica_abatimento(integer,integer,integer,integer,integer);


create or replace function fc_verifica_abatimento(integer,integer) returns boolean as
$$
declare

  iTipoAbatimento alias for $1;
  iNumpre         alias for $2;

  lAbatimento     boolean;

  begin

    select fc_verifica_abatimento(iTipoAbatimento,iNumpre,null,null,null)::boolean into lAbatimento;

    if lAbatimento then
      return true;
    else
      return false;
    end if;

  end;
$$ language 'plpgsql';


create or replace function fc_verifica_abatimento(integer,integer,integer) returns boolean as
$$
declare

  iTipoAbatimento alias for $1;
  iNumpre         alias for $2;
  iNumpar         alias for $3;

  lAbatimento     boolean;

  begin

    select fc_verifica_abatimento(iTipoAbatimento,iNumpre,iNumpar,null,null)::boolean into lAbatimento;

    if lAbatimento then
      return true;
    else
      return false;
    end if;

  end;
$$ language 'plpgsql';


create or replace function fc_verifica_abatimento(integer,integer,integer,integer) returns boolean as
$$
declare

  iTipoAbatimento alias for $1;
  iNumpre         alias for $2;
  iNumpar         alias for $3;
  iReceita        alias for $4;

  lAbatimento     boolean;

  begin

    select fc_verifica_abatimento(iTipoAbatimento,iNumpre,iNumpar,iReceita,null)::boolean into lAbatimento;

    if lAbatimento then
      return true;
    else
      return false;
    end if;

  end;
$$ language 'plpgsql';


create or replace function fc_verifica_abatimento(integer,integer,integer,integer,integer) returns boolean as
$$
declare

  iTipoAbatimento alias for $1;
  iNumpre         alias for $2;
  iNumpar         alias for $3;
  iReceita        alias for $4;
  iHistorico      alias for $5;

  sSql            text;

  rAbatimento     record;

  begin

    if iNumpre is null then
      raise exception 'Numpre nao informado !';
    end if;

    sSql := ' select 1 as abatimento
                from abatimento
                     inner join abatimentoarreckey on abatimentoarreckey.k128_abatimento = abatimento.k125_sequencial
                     inner join arreckey           on arreckey.k00_sequencial            = abatimentoarreckey.k128_arreckey
               where abatimento.k125_tipoabatimento = '||iTipoAbatimento||'
                 and arreckey.k00_numpre            = '||iNumpre;

    if iNumpar is not null then
      sSql := sSql || ' and arreckey.k00_numpar = '||iNumpar;
    end if;

    if iReceita is not null then
      sSql := sSql || ' and arreckey.k00_receit = '||iReceita;
    end if;

    if iHistorico is not null then
      sSql := sSql || ' and arreckey.k00_hist   = '||iHistorico;
    end if;

    sSql := sSql || ' limit 1 ';

    execute sSql into rAbatimento;

    if rAbatimento.abatimento is not null then
      return true;
    else
      return false;
    end if;

  end;

$$  language 'plpgsql';


SQL;

    $this->execute($sSql);

    }
}
