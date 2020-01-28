<?php

use Classes\PostgresMigration;

class M9908FichaCompensacaoArrebanco extends PostgresMigration
{
    public function up()
    {
                $sSql =
<<<SQL
drop function fc_fichacompensacaoarrebanco_old(integer, integer, integer);

drop type tp_fichacompensacaoarrebanco;
create type tp_fichacompensacaoarrebanco as (numbanco text, nossonumero text);

create or replace function fc_fichacompensacaoarrebanco_old(integer, integer, integer) returns tp_fichacompensacaoarrebanco as
$$
declare

  iCodConvenio alias for $1;
  iNumpre      alias for $2;
  iNumpar      alias for $3;

  sMsgRet           varchar;
  sNumbcoSeq        integer;
  iSequencia        integer;
  iCodBanco         integer;
  sConvenio         varchar;
  sCarteira         varchar;
  sAgencia          varchar;
  sNumBanco         varchar;
  sNumBancoa        varchar;
  iResto            integer;
  iDigito1          integer;
  iDigito2          integer;
  iMaximo           integer;
  iTipoConvenio     integer;
  iConvenioCobranca integer;
  sNossoNumero      varchar;

  lEncontraConvenio boolean default false;
  lRaise            boolean default false;

  rReturn tp_fichacompensacaoarrebanco;

begin

  lRaise := (case when fc_getsession('DB_debugon') is null then false else true end);

  /**
   * Selecionamos os dados do banco (agencia, proximo numero da sequencia e o convenio;)
   */
  select db89_codagencia,
         ar13_convenio,
         ar13_carteira,
         db89_db_bancos,
         coalesce((select max(ar20_sequencia) from conveniocobrancaseq where ar20_conveniocobranca = ar13_sequencial),0) as ar20_sequencia,
         ar13_sequencial,
         ar12_sequencial
    into sAgencia,
         sConvenio,
         sCarteira,
         iCodBanco,
         iSequencia,
         iConvenioCobranca,
         iTipoConvenio
    from cadconvenio
         inner join cadtipoconvenio  on ar12_sequencial  = ar11_cadtipoconvenio
         inner join conveniocobranca on ar13_cadconvenio = ar11_sequencial
         inner join bancoagencia     on db89_sequencial  = ar13_bancoagencia
   where ar11_sequencial = iCodConvenio;

   if found then
     lEncontraConvenio := true;
   else
     lEncontraConvenio := false;
   end if;

   perform fc_debug(' <fc_fichacompensacaoarrebanco> Executando fc_fichacompensacaoarrebanco',  lRaise);
   perform fc_debug(' <fc_fichacompensacaoarrebanco> sAgencia:          ' || sAgencia,          lRaise);
   perform fc_debug(' <fc_fichacompensacaoarrebanco> sConvenio:         ' || sConvenio,         lRaise);
   perform fc_debug(' <fc_fichacompensacaoarrebanco> sCarteira:         ' || sCarteira,         lRaise);
   perform fc_debug(' <fc_fichacompensacaoarrebanco> iCodBanco:         ' || iCodBanco,         lRaise);
   perform fc_debug(' <fc_fichacompensacaoarrebanco> iSequencia:        ' || iSequencia,        lRaise);
   perform fc_debug(' <fc_fichacompensacaoarrebanco> iConvenioCobranca: ' || iConvenioCobranca, lRaise);
   perform fc_debug(' <fc_fichacompensacaoarrebanco> iTipoConvenio:     ' || iTipoConvenio,     lRaise);
   perform fc_debug(' <fc_fichacompensacaoarrebanco> ',                                         lRaise);
   perform fc_debug(' <fc_fichacompensacaoarrebanco> lEncontraConvenio: ' || lEncontraConvenio, lRaise);

   if iCodBanco = 1 then
     iMaximo := 99999999;
--      iMaximo := 99999;
   else

     if iTipoConvenio = 5 then
       iMaximo := 99999999;
     else
       iMaximo := 9999999;
     end if;
   end if;

   perform fc_debug(' <fc_fichacompensacaoarrebanco> iMaximo: ' || iMaximo, lRaise);

   if iSequencia > 0 then

     select ar20_valor
       into sNumbcoSeq
       from conveniocobrancaseq
      where ar20_conveniocobranca = iConvenioCobranca
        and ar20_sequencia        = iSequencia
        for update;

     perform fc_debug(' <fc_fichacompensacaoarrebanco> sNumbcoSeq encontrado: ' || sNumbcoSeq, lRaise);
     sNumbcoSeq := sNumbcoSeq + 1;
     perform fc_debug(' <fc_fichacompensacaoarrebanco> sNumbcoSeq próximo:    ' || sNumbcoSeq, lRaise);

     if sNumbcoSeq < iMaximo then

       update conveniocobrancaseq
          set ar20_valor = ar20_valor + 1
        where ar20_conveniocobranca = iConvenioCobranca
          and ar20_sequencia        = iSequencia;
     else

       iSequencia = iSequencia + 1;
       sNumbcoSeq = 1;
       insert into conveniocobrancaseq select nextval('conveniocobrancaseq_ar20_sequencial_seq'), iConvenioCobranca, iSequencia, sNumbcoSeq;
     end if;

    else

      sNumbcoSeq = 1;
      insert into conveniocobrancaseq select nextval('conveniocobrancaseq_ar20_sequencial_seq'), iConvenioCobranca, 1, sNumbcoSeq;
   end if;

   perform fc_debug(' <fc_fichacompensacaoarrebanco> iSequencia: ' || iSequencia, lRaise);
   perform fc_debug(' <fc_fichacompensacaoarrebanco> sNumbcoSeq: ' || sNumbcoSeq, lRaise);

   if lEncontraConvenio then

     -- Verifica convenio SICOB
     if iTipoConvenio = 5 then

       if sCarteira = '9' then
         sNumBancoa := lpad(sNumbcoSeq,9,0);
       else
         sNumBancoa := lpad(sNumbcoSeq,8,0);
       end if;

       sNumBancoa := trim(sCarteira)||sNumBancoa;

       iDigito1 := 11 - fc_modulo11(sNumBancoa,2,9);

       if iDigito1 > 9 then
         iDigito1 := 0;
       end if;

       sNumBancoa := sNumBancoa||iDigito1;

     elsif iTipoConvenio = 6 then

       sConvenio  := trim(sConvenio);
       sNumBancoa := trim(sConvenio)||trim(to_char(sNumbcoSeq,'0000000'));
       iDigito1   := fc_modulo10(sNumBancoa); -- Calcula Modulo 10 do NossoNumero
       iResto     := fc_modulo11(sNumBancoa||cast(iDigito1 as char(1)), 2, 7); -- Retornar Resto

       if iResto = 1 then -- Digito Invalido
         iDigito1 := iDigito1 + 1; -- Soma-se 1 ao primeiro DV
         if iDigito1 > 9 then
           iDigito1 := 0;
         end if;
         iDigito2 := fc_modulo11(sNumBancoa||cast(iDigito1 as char(1)), 1, 7);
       elsif iResto = 0 then
         iDigito2 := 0;
       else
         iDigito2 := fc_modulo11(sNumBancoa||cast(iDigito1 as char(1)), 1, 7);
       end if;

       /**
        * Monta o Nosso Numero
        */
       sNumBancoa := sNumBancoa||cast(iDigito1 as char(1))||cast(iDigito2 as char(1));
       if lRaise then
         raise notice 'Processando SIGCB sNumbancoa: %',sNumbancoa;
       end if;

       sNumBancoa   := lpad(sNumBancoa,15,0);
       sNumBancoa   := substr(sNumBancoa,1,3) || -- 1pt Nosso Numero
                       substr(sCarteira,1,1)  || -- Modalidade de cobrança pode ser 1 'Com registro' ou 2 'Sem registro'
                       substr(sNumBancoa,4,3) || -- 2pt Nosso Numero
                       substr(sCarteira,2,1)  || -- Constante modo de impressão pode 1 'Impresso pela CEF' ou 4 'Impresso pelo cedente'
                       substr(sNumBancoa,7,9);   -- 3pt Nosso Numero

     else

       perform fc_debug(' <fc_fichacompensacaoarrebanco> sNumbcoSeq: ' || sNumbcoSeq , lRaise);
       sConvenio  := trim(sConvenio);
       sNumBancoa := trim(sConvenio)||trim(to_char(sNumbcoSeq,'0000000'));
       iDigito1   := fc_modulo10(sNumBancoa); -- Calcula Modulo 10 do NossoNumero
       iResto     := fc_modulo11(sNumBancoa||cast(iDigito1 as char(1)), 2, 7); -- Retornar Resto

       if iResto = 1 then -- Digito Invalido
         iDigito1 := iDigito1 + 1; -- Soma-se 1 ao primeiro DV
         if iDigito1 > 9 then
           iDigito1 := 0;
         end if;
         iDigito2 := fc_modulo11(sNumBancoa||cast(iDigito1 as char(1)), 1, 7);
       elsif iResto = 0 then
         iDigito2 := 0;
       else
         iDigito2 := fc_modulo11(sNumBancoa||cast(iDigito1 as char(1)), 1, 7);
       end if;

       /**
        * Monta o Nosso Numero
        */
       perform fc_debug(' <fc_fichacompensacaoarrebanco> iDigito1: ' || iDigito1 , lRaise);
       perform fc_debug(' <fc_fichacompensacaoarrebanco> iDigito2: ' || iDigito2 , lRaise);
       sNumBancoa := sNumBancoa||cast(iDigito1 as char(1))||cast(iDigito2 as char(1));
     end if;

     perform fc_debug(' <fc_fichacompensacaoarrebanco> Numbanco -> arrebanco: ' || sNumbancoa, lRaise);

     if iTipoConvenio = 7 then

       perform *
          from arrebanco
         where k00_numpre = iNumpre
           and k00_numpar = iNumpar
           and k00_numbco = sConvenio||lpad(cast(iNumpre as varchar),10,'0');
       if not found then

         sNossoNumero := sConvenio || lpad(cast(iNumpre as varchar), 10, '0');

         insert into arrebanco (k00_numpre, k00_numpar, k00_codbco, k00_codage, k00_numbco)
                      values (iNumpre, iNumpar, iCodBanco, trim(sAgencia), sNossoNumero);
       end if;

     else

       if iTipoConvenio = 6 then
         sNossoNumero := sCarteira || substr(sNumBancoa,1,3) || substr(sNumBancoa,5,3) || substr(sNumBancoa,9,9);
       elsif iTipoConvenio = 1 and iCodBanco = 1 then
         sNossoNumero := sNumbcoSeq;
       else
         sNossoNumero := sNumBancoa;
       end if;


         perform fc_debug(' <fc_fichacompensacaoarrebanco> Numbanco -> arrebanco: ' || sNumbancoa, lRaise);
         perform fc_debug(' <fc_fichacompensacaoarrebanco> sNossoNumero:          ' || sNumbcoSeq, lRaise);

         insert into arrebanco (k00_numpre, k00_numpar, k00_codbco, k00_codage, k00_numbco)
                      values (iNumpre, iNumpar, iCodBanco, trim(sAgencia), sNossoNumero);
       end if;

  else
    raise exception 'Não foi encontrado banco (%)',iCodBanco;
  end if;

  if iTipoConvenio = 5 then
    sNumBanco := trim(to_char(sNumbcoSeq,'00000000'));
  elsif iTipoConvenio = 6 then
    sNumBanco := trim(sNumBancoa);
  elsif iTipoConvenio = 1 and iCodBanco = 1 then
--     sNumBanco := trim(to_char(sNumbcoSeq,'00000'));
    sNumBanco := sNumbcoSeq;
  elsif iTipoConvenio = 1 and iCodBanco = 41 then
    sNumBanco := trim(to_char(sNumbcoSeq,'00000000'));
  else
    sNumBanco := trim(to_char(sNumbcoSeq,'0000000'));
  end if;

  perform fc_debug(' <fc_fichacompensacaoarrebanco> Numbanco retornado: ' || sNumBanco, lRaise);

  rReturn.numbanco    := sNumBanco;
  rReturn.nossonumero := sNossoNumero;

  return rReturn;

end;
$$
language 'plpgsql';

create or replace function fc_fichacompensacaoarrebanco(integer, integer, integer) returns varchar as
$$
declare

  iCodConvenio alias for $1;
  iNumpre      alias for $2;
  iNumpar      alias for $3;

  rReturn tp_fichacompensacaoarrebanco;

begin

  rReturn := fc_fichacompensacaoarrebanco_old(iCodConvenio, iNumpre, iNumpar);

  return trim(rReturn.numbanco);

end;
$$
language 'plpgsql';
SQL;

        $this->execute($sSql);
    }


    public function down()
    {
        $sSql =
<<<SQL
drop function fc_fichacompensacaoarrebanco_old(integer, integer, integer);

drop type tp_fichacompensacaoarrebanco;
create type tp_fichacompensacaoarrebanco as (numbanco text, nossonumero text);

create or replace function fc_fichacompensacaoarrebanco_old(integer, integer, integer) returns tp_fichacompensacaoarrebanco as
$$
declare

  iCodConvenio alias for $1;
  iNumpre      alias for $2;
  iNumpar      alias for $3;

  sMsgRet           varchar;
  sNumbcoSeq        integer;
  iSequencia        integer;
  iCodBanco         integer;
  sConvenio         varchar;
  sCarteira         varchar;
  sAgencia          varchar;
  sNumBanco         varchar;
  sNumBancoa        varchar;
  iResto            integer;
  iDigito1          integer;
  iDigito2          integer;
  iMaximo           integer;
  iTipoConvenio     integer;
  iConvenioCobranca integer;
  sNossoNumero      varchar;

  lEncontraConvenio boolean default false;
  lRaise            boolean default false;

  rReturn tp_fichacompensacaoarrebanco;

begin

  lRaise := (case when fc_getsession('DB_debugon') is null then false else true end);

  /**
   * Selecionamos os dados do banco (agencia, proximo numero da sequencia e o convenio;)
   */
  select db89_codagencia,
         ar13_convenio,
         ar13_carteira,
         db89_db_bancos,
         coalesce((select max(ar20_sequencia) from conveniocobrancaseq where ar20_conveniocobranca = ar13_sequencial),0) as ar20_sequencia,
         ar13_sequencial,
         ar12_sequencial
    into sAgencia,
         sConvenio,
         sCarteira,
         iCodBanco,
         iSequencia,
         iConvenioCobranca,
         iTipoConvenio
    from cadconvenio
         inner join cadtipoconvenio  on ar12_sequencial  = ar11_cadtipoconvenio
         inner join conveniocobranca on ar13_cadconvenio = ar11_sequencial
         inner join bancoagencia     on db89_sequencial  = ar13_bancoagencia
   where ar11_sequencial = iCodConvenio;

   if found then
     lEncontraConvenio := true;
   else
     lEncontraConvenio := false;
   end if;

   perform fc_debug(' <fc_fichacompensacaoarrebanco> Executando fc_fichacompensacaoarrebanco',  lRaise);
   perform fc_debug(' <fc_fichacompensacaoarrebanco> sAgencia:          ' || sAgencia,          lRaise);
   perform fc_debug(' <fc_fichacompensacaoarrebanco> sConvenio:         ' || sConvenio,         lRaise);
   perform fc_debug(' <fc_fichacompensacaoarrebanco> sCarteira:         ' || sCarteira,         lRaise);
   perform fc_debug(' <fc_fichacompensacaoarrebanco> iCodBanco:         ' || iCodBanco,         lRaise);
   perform fc_debug(' <fc_fichacompensacaoarrebanco> iSequencia:        ' || iSequencia,        lRaise);
   perform fc_debug(' <fc_fichacompensacaoarrebanco> iConvenioCobranca: ' || iConvenioCobranca, lRaise);
   perform fc_debug(' <fc_fichacompensacaoarrebanco> iTipoConvenio:     ' || iTipoConvenio,     lRaise);
   perform fc_debug(' <fc_fichacompensacaoarrebanco> ',                                         lRaise);
   perform fc_debug(' <fc_fichacompensacaoarrebanco> lEncontraConvenio: ' || lEncontraConvenio, lRaise);

   if iCodBanco = 1 then
     iMaximo := 99999;
   else

     if iTipoConvenio = 5 then
       iMaximo := 99999999;
     else
       iMaximo := 9999999;
     end if;
   end if;

   perform fc_debug(' <fc_fichacompensacaoarrebanco> iMaximo: ' || iMaximo, lRaise);

   if iSequencia > 0 then

     select ar20_valor
       into sNumbcoSeq
       from conveniocobrancaseq
      where ar20_conveniocobranca = iConvenioCobranca
        and ar20_sequencia        = iSequencia
        for update;

     perform fc_debug(' <fc_fichacompensacaoarrebanco> sNumbcoSeq encontrado: ' || sNumbcoSeq, lRaise);
     sNumbcoSeq := sNumbcoSeq + 1;
     perform fc_debug(' <fc_fichacompensacaoarrebanco> sNumbcoSeq próximo:    ' || sNumbcoSeq, lRaise);

     if sNumbcoSeq < iMaximo then

       update conveniocobrancaseq
          set ar20_valor = ar20_valor + 1
        where ar20_conveniocobranca = iConvenioCobranca
          and ar20_sequencia        = iSequencia;
     else

       iSequencia = iSequencia + 1;
       sNumbcoSeq = 1;
       insert into conveniocobrancaseq select nextval('conveniocobrancaseq_ar20_sequencial_seq'), iConvenioCobranca, iSequencia, sNumbcoSeq;
     end if;

    else

      sNumbcoSeq = 1;
      insert into conveniocobrancaseq select nextval('conveniocobrancaseq_ar20_sequencial_seq'), iConvenioCobranca, 1, sNumbcoSeq;
   end if;

   perform fc_debug(' <fc_fichacompensacaoarrebanco> iSequencia: ' || iSequencia, lRaise);
   perform fc_debug(' <fc_fichacompensacaoarrebanco> sNumbcoSeq: ' || sNumbcoSeq, lRaise);

   if lEncontraConvenio then

     -- Verifica convenio SICOB
     if iTipoConvenio = 5 then

       if sCarteira = '9' then
         sNumBancoa := lpad(sNumbcoSeq,9,0);
       else
         sNumBancoa := lpad(sNumbcoSeq,8,0);
       end if;

       sNumBancoa := trim(sCarteira)||sNumBancoa;

       iDigito1 := 11 - fc_modulo11(sNumBancoa,2,9);

       if iDigito1 > 9 then
         iDigito1 := 0;
       end if;

       sNumBancoa := sNumBancoa||iDigito1;

     elsif iTipoConvenio = 6 then

       sConvenio  := trim(sConvenio);
       sNumBancoa := trim(sConvenio)||trim(to_char(sNumbcoSeq,'0000000'));
       iDigito1   := fc_modulo10(sNumBancoa); -- Calcula Modulo 10 do NossoNumero
       iResto     := fc_modulo11(sNumBancoa||cast(iDigito1 as char(1)), 2, 7); -- Retornar Resto

       if iResto = 1 then -- Digito Invalido
         iDigito1 := iDigito1 + 1; -- Soma-se 1 ao primeiro DV
         if iDigito1 > 9 then
           iDigito1 := 0;
         end if;
         iDigito2 := fc_modulo11(sNumBancoa||cast(iDigito1 as char(1)), 1, 7);
       elsif iResto = 0 then
         iDigito2 := 0;
       else
         iDigito2 := fc_modulo11(sNumBancoa||cast(iDigito1 as char(1)), 1, 7);
       end if;

       /**
        * Monta o Nosso Numero
        */
       sNumBancoa := sNumBancoa||cast(iDigito1 as char(1))||cast(iDigito2 as char(1));
       if lRaise then
         raise notice 'Processando SIGCB sNumbancoa: %',sNumbancoa;
       end if;

       sNumBancoa   := lpad(sNumBancoa,15,0);
       sNumBancoa   := substr(sNumBancoa,1,3) || -- 1pt Nosso Numero
                       substr(sCarteira,1,1)  || -- Modalidade de cobrança pode ser 1 'Com registro' ou 2 'Sem registro'
                       substr(sNumBancoa,4,3) || -- 2pt Nosso Numero
                       substr(sCarteira,2,1)  || -- Constante modo de impressão pode 1 'Impresso pela CEF' ou 4 'Impresso pelo cedente'
                       substr(sNumBancoa,7,9);   -- 3pt Nosso Numero

     else

       perform fc_debug(' <fc_fichacompensacaoarrebanco> sNumbcoSeq: ' || sNumbcoSeq , lRaise);
       sConvenio  := trim(sConvenio);
       sNumBancoa := trim(sConvenio)||trim(to_char(sNumbcoSeq,'0000000'));
       iDigito1   := fc_modulo10(sNumBancoa); -- Calcula Modulo 10 do NossoNumero
       iResto     := fc_modulo11(sNumBancoa||cast(iDigito1 as char(1)), 2, 7); -- Retornar Resto

       if iResto = 1 then -- Digito Invalido
         iDigito1 := iDigito1 + 1; -- Soma-se 1 ao primeiro DV
         if iDigito1 > 9 then
           iDigito1 := 0;
         end if;
         iDigito2 := fc_modulo11(sNumBancoa||cast(iDigito1 as char(1)), 1, 7);
       elsif iResto = 0 then
         iDigito2 := 0;
       else
         iDigito2 := fc_modulo11(sNumBancoa||cast(iDigito1 as char(1)), 1, 7);
       end if;

       /**
        * Monta o Nosso Numero
        */
       perform fc_debug(' <fc_fichacompensacaoarrebanco> iDigito1: ' || iDigito1 , lRaise);
       perform fc_debug(' <fc_fichacompensacaoarrebanco> iDigito2: ' || iDigito2 , lRaise);
       sNumBancoa := sNumBancoa||cast(iDigito1 as char(1))||cast(iDigito2 as char(1));
     end if;

     perform fc_debug(' <fc_fichacompensacaoarrebanco> Numbanco -> arrebanco: ' || sNumbancoa, lRaise);

     if iTipoConvenio = 7 then

       perform *
          from arrebanco
         where k00_numpre = iNumpre
           and k00_numpar = iNumpar
           and k00_numbco = sConvenio||lpad(cast(iNumpre as varchar),10,'0');
       if not found then

         sNossoNumero := sConvenio || lpad(cast(iNumpre as varchar), 10, '0');

         insert into arrebanco (k00_numpre, k00_numpar, k00_codbco, k00_codage, k00_numbco)
                      values (iNumpre, iNumpar, iCodBanco, trim(sAgencia), sNossoNumero);
       end if;

     else

       if iTipoConvenio = 6 then
         sNossoNumero := sCarteira || substr(sNumBancoa,1,3) || substr(sNumBancoa,5,3) || substr(sNumBancoa,9,9);
       elsif iTipoConvenio = 1 and iCodBanco = 1 then
         sNossoNumero := sNumbcoSeq;
       else
         sNossoNumero := sNumBancoa;
       end if;


         perform fc_debug(' <fc_fichacompensacaoarrebanco> Numbanco -> arrebanco: ' || sNumbancoa, lRaise);
         perform fc_debug(' <fc_fichacompensacaoarrebanco> sNossoNumero:          ' || sNumbcoSeq, lRaise);

         insert into arrebanco (k00_numpre, k00_numpar, k00_codbco, k00_codage, k00_numbco)
                      values (iNumpre, iNumpar, iCodBanco, trim(sAgencia), sNossoNumero);
       end if;

  else
    raise exception 'Não foi encontrado banco (%)',iCodBanco;
  end if;

  if iTipoConvenio = 5 then
    sNumBanco := trim(to_char(sNumbcoSeq,'00000000'));
  elsif iTipoConvenio = 6 then
    sNumBanco := trim(sNumBancoa);
  elsif iTipoConvenio = 1 and iCodBanco = 1 then
    sNumBanco := trim(to_char(sNumbcoSeq,'00000'));
  elsif iTipoConvenio = 1 and iCodBanco = 41 then
    sNumBanco := trim(to_char(sNumbcoSeq,'00000000'));
  else
    sNumBanco := trim(to_char(sNumbcoSeq,'0000000'));
  end if;

  perform fc_debug(' <fc_fichacompensacaoarrebanco> Numbanco retornado: ' || sNumBanco, lRaise);

  rReturn.numbanco    := sNumBanco;
  rReturn.nossonumero := sNossoNumero;

  return rReturn;

end;
$$
language 'plpgsql';

create or replace function fc_fichacompensacaoarrebanco(integer, integer, integer) returns varchar as
$$
declare

  iCodConvenio alias for $1;
  iNumpre      alias for $2;
  iNumpar      alias for $3;

  rReturn tp_fichacompensacaoarrebanco;

begin

  rReturn := fc_fichacompensacaoarrebanco_old(iCodConvenio, iNumpre, iNumpar);

  return trim(rReturn.numbanco);

end;
$$
language 'plpgsql';
SQL;

        $this->execute($sSql);
    }
}
