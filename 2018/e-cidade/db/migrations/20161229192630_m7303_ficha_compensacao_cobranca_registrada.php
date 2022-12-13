<?php

use Classes\PostgresMigration;

class M7303FichaCompensacaoCobrancaRegistrada extends PostgresMigration
{
    public function up()
    {
        $sFuncao = <<<EOT
create or replace function fc_fichacompensacao(integer, integer, integer, date, float8) returns tp_fichacompensacao  as
$$
declare
  -- Parametros
  iCodConvenio alias for $1;
  iNumpre      alias for $2;
  iNumpar      alias for $3;
  dVencimento  alias for $4;
  nValor       alias for $5;

  -- Formatacao do Codigo de barras
  sBanco       char(3);
  sAgencia     varchar;
  sMoeda       char(1) default '9';
  sDigito      char(1);
  sFatorVenc   char(4);
  sValor       char(10);
  sCampoLivre  char(25);

  -- Formatacao Campo Livre
  sCedente     varchar;
  sConvenio    char(7);
  sCarteira    char(6);
  sOperacao    char(3);
  sNossoNumero text;
  sRetornoNossoNumero varchar;
  sNumBanco    varchar; -- numero do banco(arrbanco ) para padrao bsj

  -- Outros
  dDataBase    date default '1997-10-07';
  sCodBar      text default '';
  sLinha       text default '';
  sLinhaCampo1 text;
  sLinhaCampo2 text;
  sLinhaCampo3 text;
  sLinhaCampo4 text;
  sLinhaCampo5 text;

  iModalidadeConvenio integer;
  iTipoConvenio       integer;
  iDigito1            integer;
  iDigito2            integer;
  iResto              integer;

  rRetorno     tp_fichacompensacao;

  -- Debug
  lRaise       boolean default false;

begin

  lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );

  perform fc_debug('------<fc_fichacompensacao>', lRaise, true, false);

  rRetorno.codigobarras   := sCodBar;
  rRetorno.linhadigitavel := sLinha;
  rRetorno.erro           := false;
  rRetorno.mensagem       := '';

  select ar12_sequencial,
         ar12_cadconveniomodalidade,
         ar13_cedente,
         ar13_convenio,
         ar13_carteira,
         ar13_operacao,
         db89_db_bancos,
         db89_codagencia
    into iTipoConvenio,
         iModalidadeConvenio,
         sCedente,
         sConvenio,
         sCarteira,
         sOperacao,
         sBanco,
         sAgencia
    from cadconvenio
         inner join cadtipoconvenio  on ar12_sequencial  = ar11_cadtipoconvenio
         inner join conveniocobranca on ar13_cadconvenio = ar11_sequencial
         inner join bancoagencia     on db89_sequencial  = ar13_bancoagencia
   where ar11_sequencial = iCodConvenio;

  if not found then
    rRetorno.erro     := true;
    rRetorno.mensagem := 'Banco nao cadastrado!';
    return rRetorno;
  end if;

  if iModalidadeConvenio != 1 then
    rRetorno.erro     := true;
    rRetorno.mensagem := 'Tipo de convênio diferente de cobrança!';
    return rRetorno;
  end if;

  if sCedente  is null or
     sConvenio is null or
     sCarteira is null or
     sBanco    is null or
     sCedente  = '' or
     sConvenio = '' or
     sCarteira = '' or
     sBanco    = '' then

    rRetorno.erro     := true;
    rRetorno.mensagem := 'Verificar configuracoes do Cadastro do Banco (Cedente, Convenio, Carteira, Banco)';

    return rRetorno;
  end if;

  -- Formatacoes
  sFatorVenc   := trim(to_char(dVencimento - dDataBase, '0000'));

  --sNossoNumero := '00000600179100058'; -- Para testes...
  sConvenio    := trim(sConvenio);
  sValor       := trim(to_char(nValor * 100, '0000000000'));

  -- Quatro Digitos = BSJ do Banrisul

  if iTipoConvenio = 2 then

    sConvenio    := trim(to_char(sConvenio::integer, '0000'));
    -- nosso numero sem os  digitos verificadores (para codigo de barras e linha digitavel)
    sNumBanco    := trim(fc_fichacompensacaoarrebanco(iCodConvenio, iNumpre, iNumpar))::integer;

    perform fc_debug('------<fc_fichacompensacao>  sNumBanco:     '||sNumBanco, lRaise);

    --
    -- 1. Nosso Numero
    --
    sNossoNumero := sConvenio||lpad(sNumBanco,7,0);

    iDigito1     := fc_modulo10(sNossoNumero); -- Calcula Modulo 10 do NossoNumero
    perform fc_debug('------<fc_fichacompensacao>  iDigito1:      '||iDigito1, lRaise);

    iResto       := fc_modulo11(sNossoNumero||cast(iDigito1 as char(1)), 2, 7); -- Retornar Resto (Calculo Modulo 11 Peso 7)

    if iResto = 1 then -- Digito Invalido
      iDigito1 := iDigito1 + 1; -- Soma-se 1 ao primeiro DV

      if iDigito1 > 9 then
        iDigito1 := 0;
      end if;

      iDigito2 := fc_modulo11(sNossoNumero||cast(iDigito1 as char(1)), 1, 7); -- Retornar Digito (Calculo Modulo 11 Peso 7)
    elsif iResto = 0 then
      iDigito2 := 0;
    else
      iDigito2 := fc_modulo11(sNossoNumero||cast(iDigito1 as char(1)), 1, 7); -- Retornar Digito (Calculo Modulo 11 Peso 7)
    end if;

    perform fc_debug('------<fc_fichacompensacao>  iDigito2:      '||iDigito2, lRaise);

    -- Monta Nosso Numero
    sNossoNumero := sNossoNumero||cast(iDigito1 as char(1))||cast(iDigito2 as char(1));
    perform fc_debug('------<fc_fichacompensacao>  sNossoNumero:  '||sNossoNumero, lRaise);

    --
    -- 2. Campo Livre
    --
    sCampoLivre  := ('23' || sNossoNumero || '00000' || sBanco ); -- '23' + Nosso Num (9) + '00000' + Banco (041) = 25 caracteres
    iDigito1     := fc_modulo10(sCampoLivre); -- Calcula Modulo 10 do Campo Livre
    iResto       := fc_modulo11(sCampoLivre||cast(iDigito1 as char(1)), 2, 7); -- Retornar Resto (Calculo Modulo 11 Peso 7)

    perform fc_debug('------<fc_fichacompensacao>  sCampoLivre:   '||sCampoLivre, lRaise);

    if iResto = 1 then -- Digito Invalido
      iDigito1 := iDigito1 + 1; -- Soma-se 1 ao primeiro DV

      if iDigito1 > 9 then
        iDigito1 := 0;
      end if;

      iDigito2 := fc_modulo11(sCampoLivre||cast(iDigito1 as char(1)), 1, 7); -- Retornar Digito (Calculo Modulo 11 Peso 7)
    elsif iResto = 0 then
      iDigito2 := 0;
    else
      iDigito2 := fc_modulo11(sCampoLivre||cast(iDigito1 as char(1)), 1, 7); -- Retornar Digito (Calculo Modulo 11 Peso 7)
    end if;

    -- Monta Campo Livre
    sCampoLivre := sCampoLivre||cast(iDigito1 as char(1))||cast(iDigito2 as char(1));

  elsif iTipoConvenio = 1 and sBanco = '001' then

    sNumBanco := trim(fc_fichacompensacaoarrebanco(iCodConvenio, iNumpre, iNumpar));

    if length(sConvenio) = 4 then

      sNumBanco    := lpad(sNumBanco, 7, 0);
      sNossoNumero := sConvenio || sNumBanco;

      select fc_modulo11(sNossoNumero) into iDigito1;

      sDigito := cast(iDigito1 as char(1));

      sCampoLivre := sConvenio;
      sCampoLivre := sCampoLivre || sNumBanco;
      sCampoLivre := sCampoLivre || trim(to_char(sAgencia::integer, '0000'));
      sCampoLivre := sCampoLivre || trim(to_char(sCedente::integer, '00000000'));
      sCampoLivre := sCampoLivre || sCarteira;

    elsif length(sConvenio) = 6 then

      sNumBanco    := lpad(sNumBanco, 5, 0);
      sNossoNumero := sConvenio || sNumBanco;

      select fc_modulo11(sNossoNumero) into iDigito1;

      sDigito := cast(iDigito1 as char(1));

      sCampoLivre := sConvenio;
      sCampoLivre := sCampoLivre || sNumBanco;
      sCampoLivre := sCampoLivre || trim(to_char(sAgencia::integer, '0000'));
      sCampoLivre := sCampoLivre || trim(to_char(sCedente::integer, '00000000'));
      sCampoLivre := sCampoLivre || sCarteira;

    elsif length(sConvenio) = 7 then

      sNumBanco := lpad(sNumBanco, 10, 0);
      sDigito   := '';

      sCampoLivre := '000000';
      sCampoLivre := sCampoLivre || sConvenio;
      sCampoLivre := sCampoLivre || sNumBanco;
      sCampoLivre := sCampoLivre || sCarteira;

    end if;

    sNossoNumero := sConvenio || sNumBanco || sDigito;

    update arrebanco set k00_numbco = sNossoNumero
     where k00_numpre = iNumpre
       and k00_numpar = iNumpar;

  elsif iTipoConvenio = 1 and length(sConvenio) = 6 then

    sNumBanco    := trim(fc_fichacompensacaoarrebanco(iCodConvenio, iNumpre, iNumpar));
    perform fc_debug('------<fc_fichacompensacao> sNumBanco:    ' ||  sNumBanco, lRaise);
    -- sNumBanco    := to_char(sNumBanco, '00000');
    perform fc_debug('------<fc_fichacompensacao> sNumBanco:    ' ||  sNumBanco, lRaise);

    perform fc_debug('------<fc_fichacompensacao> iCodConvenio: ' || iCodConvenio, lRaise);
    perform fc_debug('------<fc_fichacompensacao> iNumpre:      ' || iNumpre, lRaise);
    perform fc_debug('------<fc_fichacompensacao> iNumpar:      ' || iNumpar, lRaise);

    sNossoNumero := (sConvenio || sNumBanco );

    select fc_modulo11(sNossoNumero) into iDigito1;

    sNossoNumero := sNossoNumero || iDigito1;
    perform fc_debug('------<fc_fichacompensacao> sNossoNumero: ' || sNossoNumero, lRaise);

    sCampoLivre  := (sConvenio || '00' || sNossoNumero  || trim(to_char(coalesce(iNumpar, 0), '000')) || '21'); -- Conv (6) + Nosso Num (17) + '21' (2) = 25 caracteres

    elsif iTipoConvenio = 1 and length(sConvenio) = 7 then

    sConvenio    := trim(to_char(sConvenio::integer, '0000000'));
      if iNumpar > 99 then
            rRetorno.erro     := true;
            rRetorno.mensagem := 'para convenios com 7 digitos, parcela deve ser no maximo 99';
            return rRetorno;
        end if;
        sNossoNumero := trim(to_char(coalesce(iNumpre, 0), '00000000')) || trim(to_char(coalesce(iNumpar, 0), '00'));
    sCampoLivre  := ('000000' || sConvenio || sNossoNumero || sCarteira);
        -- seis zeros + convenio de 7 posicoes + Nosso Num (10 - numpre (8) + numpar (2) ) + carteira (2) = 25 caracteres

  -- CONVÊNIO BDL - BANRISUL
  elsif iTipoConvenio = 1 and sBanco = '041' then

    sNumBanco    := trim(fc_fichacompensacaoarrebanco(iCodConvenio, iNumpre, iNumpar));

    /**
     * Cálculo do NC do NossoNúmero
     */
    iDigito1     := fc_modulo10(sNumBanco); -- Calcula Modulo 10 do Campo Livre
    iResto       := fc_modulo11(sNumBanco||cast(iDigito1 as char(1)), 2, 7); -- Retornar Resto (Calculo Modulo 11 Peso 7)

    perform fc_debug('------<fc_fichacompensacao>  sNumBanco:      '||sNumBanco, lRaise);

    if iResto = 1 then -- Digito Invalido
      iDigito1 := iDigito1 + 1; -- Soma-se 1 ao primeiro DV

      if iDigito1 > 9 then
        iDigito1 := 0;
      end if;

      iDigito2 := fc_modulo11(sNumBanco||cast(iDigito1 as char(1)), 1, 7); -- Retornar Digito (Calculo Modulo 11 Peso 7)
    elsif iResto = 0 then
      iDigito2 := 0;
    else
      iDigito2 := fc_modulo11(sNumBanco||cast(iDigito1 as char(1)), 1, 7); -- Retornar Digito (Calculo Modulo 11 Peso 7)
    end if;

    perform fc_debug('------<fc_fichacompensacao>  iDigito1:       '||iDigito1, lRaise);
    perform fc_debug('------<fc_fichacompensacao>  iDigito2:       '||iDigito2, lRaise);

    sNossoNumero := sNumBanco||iDigito1||iDigito2;
    perform fc_debug('------<fc_fichacompensacao>  sNossoNumero:   '||sNossoNumero, lRaise);

    --trim(to_char(sNumbcoSeq,'0000000'))

    -- Produto: Cobrança Direta
    sCampoLivre := '21';
    sCampoLivre := sCampoLivre || trim(to_char(sAgencia::integer,'0000'));
    sCampoLivre := sCampoLivre || trim(to_char(sCedente::integer,'0000000'));
    sCampoLivre := sCampoLivre || trim(to_char(sNumBanco::integer,'00000000'));
    sCampoLivre := sCampoLivre || '40';

    perform fc_debug('------<fc_fichacompensacao>  sCampoLivre:    '||sCampoLivre, lRaise);

    /**
     * Cálculo do NC do CampoLivre
     */
    iDigito1     := fc_modulo10(sCampoLivre); -- Calcula Modulo 10 do Campo Livre
    iResto       := fc_modulo11(sCampoLivre||cast(iDigito1 as char(1)), 2, 7); -- Retornar Resto (Calculo Modulo 11 Peso 7)sss

    if iResto = 1 then -- Digito Invalido
      iDigito1 := iDigito1 + 1; -- Soma-se 1 ao primeiro DV

      if iDigito1 > 9 then
        iDigito1 := 0;
      end if;

      iDigito2 := fc_modulo11(sCampoLivre||cast(iDigito1 as char(1)), 1, 7); -- Retornar Digito (Calculo Modulo 11 Peso 7)
    elsif iResto = 0 then
      iDigito2 := 0;
    else
      iDigito2 := fc_modulo11(sCampoLivre||cast(iDigito1 as char(1)), 1, 7); -- Retornar Digito (Calculo Modulo 11 Peso 7)
    end if;

    sCampoLivre := sCampoLivre||iDigito1||iDigito2;

    perform fc_debug('------<fc_fichacompensacao>  sCampoLivre:    '||sCampoLivre, lRaise);
    perform fc_debug('------<fc_fichacompensacao>  sCampoLivre - COMPOSICAO:', lRaise);
    perform fc_debug('------<fc_fichacompensacao>    Produto:      1', lRaise);
    perform fc_debug('------<fc_fichacompensacao>    Constante:    1', lRaise);
    perform fc_debug('------<fc_fichacompensacao>    sAgencia:     '||trim(to_char(sAgencia::integer,'0000')), lRaise);
    perform fc_debug('------<fc_fichacompensacao>    sCedente:     '||trim(to_char(sCedente::integer,'0000000')), lRaise);
    perform fc_debug('------<fc_fichacompensacao>    sNumBanco:    '||trim(to_char(sNumBanco::integer,'00000000')), lRaise);
    perform fc_debug('------<fc_fichacompensacao>    iDigito1:     '||iDigito1, lRaise);
    perform fc_debug('------<fc_fichacompensacao>    iDigito2:     '||iDigito2, lRaise);

  -- CONVENIO SICOB 11 Posições
  elsif iTipoConvenio = 5 then

    sNumBanco := trim(fc_fichacompensacaoarrebanco(iCodConvenio, iNumpre, iNumpar))::integer;
    -- 82 = Sem Registro
    -- 9  = Rápida
    sAgencia := substr(lpad(sAgencia,5,0),2,4);
    if sCarteira = '9' then
      sNossoNumero := lpad(sNumBanco,9,0);
    else
      sNossoNumero := lpad(sNumBanco,8,0);
    end if;

      iDigito1 := 11 - fc_modulo11(sCarteira||sNossoNumero,2,9);
      if iDigito1 > 9 then
        iDigito1 := 0;
      end if;
      sRetornoNossoNumero := (sCarteira||sNossoNumero||'-'||iDigito1);

    if lRaise is true then
      raise notice ' Campo Livre SICOB 11 Pos:';
      raise notice ' Carteira: %, Nosso Número: %, Agencia: %, Operação: %, Cedente: % ',sCarteira,sNossoNumero,sAgencia,sOperacao,sCedente;
    end if;

    if length(sCedente) != 8 then
      rRetorno.erro     := true;
      rRetorno.mensagem := 'Tamanho do campo cedente difere de 8 caracteres!';
      return rRetorno;
    end if;
    sCampoLivre := (sCarteira||sNossoNumero||sAgencia||sOperacao||sCedente);


  -- CONVENIO SIGCB 17 Posições

  elsif iTipoConvenio = 6 then

    if length(sCedente) != 6 then
      rRetorno.erro     := true;
      rRetorno.mensagem := 'Tamanho do campo cedente difere de 6 caracteres!';
      return rRetorno;
    end if;

    -- Calcula DV Cedente
    iDigito1 := 11 - fc_modulo11(sCedente,2,9);
    if iDigito1 > 9 then
      iDigito1 := 0;
    end if;
    sCedente := sCedente || iDigito1;

    sNossoNumero  := trim(fc_fichacompensacaoarrebanco(iCodConvenio, iNumpre, iNumpar));

    if lRaise is true then
      raise notice ' Campo Livre SIGCB :';
      raise notice ' Carteira: %, Nosso Número: %, Cedente: % ',sCarteira,sNossoNumero,sCedente;
    end if;


    sRetornoNossoNumero := substr(sNossoNumero,1,3) ||
                           substr(sNossoNumero,5,3) ||
                           substr(sNossoNumero,9,9);

    iDigito1 := 11 - fc_modulo11(sCarteira||sRetornoNossoNumero,2,9);

    if iDigito1 > 9 then
      iDigito1 := 0;
    end if;

    sRetornoNossoNumero := (sCarteira||'/'||sRetornoNossoNumero||'-'||iDigito1);

    sCampoLivre := ( sCedente || sNossoNumero );

    -- Calcula DV Campo Livre
    iDigito1 := 11 - fc_modulo11(sCampoLivre,2,9);
    if iDigito1 > 9 then
      iDigito1 := 0;
    end if;

    sCampoLivre := ( sCampoLivre || iDigito1 );

    -- Cendente         (7) +
    -- Nosso Numero 1pt (3) +
    -- Constante1       (1) +
    -- Nosso Numero 2pt (3) +
    -- Constante2       (1) +
    -- Nosso Numero 3pt (9) +
    -- DV Campo Livre   (1)
    --
    -- Total Caraceteres =  25 caracteres
    if lRaise then
      raise notice 'Campo livre : % ',sCampoLivre;
    end if;

  elsif iTipoConvenio = 7 then

    sNumBanco := trim(fc_fichacompensacaoarrebanco(iCodConvenio, iNumpre, iNumpar))::integer;
    -- 82 = Sem Registro
    -- 9  = Rápida
    sAgencia := substr(lpad(sAgencia,5,0),2,4);
    if sCarteira = '9' then
      sNossoNumero := lpad(sNumBanco,9,0);
    else
      sNossoNumero := lpad(sNumBanco,8,0);
    end if;

      iDigito1 := 11 - fc_modulo11(sCarteira||sNossoNumero,2,9);
      if iDigito1 > 9 then
        iDigito1 := 0;
      end if;
      sRetornoNossoNumero := sConvenio||lpad(cast(iNumpre as varchar),10,'0');

    if lRaise is true then
      raise notice ' Campo Livre SICOB 11 Pos:';
      raise notice ' Carteira: %, Nosso Número: %, Agencia: %, Operação: %, Cedente: % ',sCarteira,sNossoNumero,sAgencia,sOperacao,sCedente;
    end if;

    -- if length(sCedente) != 8 then
    --   rRetorno.erro     := true;
    --   rRetorno.mensagem := 'Tamanho do campo cedente difere de 8 caracteres!';
    --   return rRetorno;
    -- end if;
    --sCampoLivre := (sCarteira||sNossoNumero||sAgencia||sOperacao||sCedente);
    -- sNossoNumero := trim(to_char(coalesce(iNumpre, 0), '00000000')) || trim(to_char(coalesce(iNumpar, 0), '00'));

    -- alteração efetuada conforme conversa com Eduardo no skype dia 04/10 as 11 hs1.7785
    sNossoNumero := trim(to_char(coalesce(iNumpar, 0), '00'))||trim(to_char(coalesce(iNumpre, 0), '00000000')) ;
    sCampoLivre  := ('000000' || sConvenio || sNossoNumero || sCarteira);
  else
    rRetorno.erro     := true;
    rRetorno.mensagem := 'convenio diferente de 4 (BSJ), 6 ou 7 posicoes';
    return rRetorno;
    end if;

  if lRaise is true then
    raise notice 'Banco: %  Moeda: %  Fator Venc: %  Nosso Numero: %  Convenio: %  Valor: %  Campo Livre: %',
      sBanco, sMoeda, sFatorVenc, sNossoNumero, sConvenio, sValor, sCampoLivre;

    raise notice 'Tam Banco: %  Tam Moeda: %  Tam Fator Venc: %  Tam Nosso Numero: %  Tam Convenio: %  Tam Valor: %  Tam Campo Livre: %',
      length(sBanco), length(sMoeda), length(sFatorVenc), length(sNossoNumero), length(sConvenio), length(sValor), length(sCampoLivre);
  end if;

  -- Codigo Barras 43 posicoes (da 1 a 4  e da 6 a 44) + Digito Verificador na posicao 5 = Totalizando 44 Posicoes
  --                           1          2          3         4
  --              123 4 5 6789 0123456789 012345 67890123456789012 34
  --              999 9 9 9999 9999999999 999999 99999999999999999 21
  --              ^^^ ^ ^ ^^^^ ^^^^^^^^^^ ^^^^^^^^^^^^^^^^^^^^^^^^^^^
  --               |  | |  |     |               |
  --               |  | |  |     |               +------------------- Campo Livre (Convenio (6) + Nosso Num (17) + '21')
  --               |  | |  |     +----------------------------------- Valor
  --               |  | |  +----------------------------------------- Fator Vencimento (data venc - 07/10/1997)
  --               |  | +-------------------------------------------- Digito Verificador Modulo 11 do codigo de barras (1-4 e 6-44)
  --               |  +---------------------------------------------- Moeda
  --               +------------------------------------------------- Codigo do Banco
  --

  -- Codigo de Barras sem Digito Verificador
  sCodBar      := trim(sBanco || sMoeda || sFatorVenc || sValor || sCampoLivre);

  if lRaise is true then
    raise notice 'CodBar Sem Digito: [%] ', sCodBar;
  end if;

  -- Calcula Digito verificador Modulo 11 para o Codigo de Barras
  sDigito      := fc_modulo11(sCodBar)::char(1); -- Calcula Digito Modulo 11 (Peso 9)
  -- Se retornar '0' entao trocar pra '1', de acordo com manual do CNAB
  --if sDigito = '0' then
  --  sDigito := '1';
  --end if;

  -- Insere Digito verificador na posicao 5-5 do Codigo de Barras
  sCodBar      := trim(sBanco || sMoeda || sDigito || sFatorVenc || sValor || sCampoLivre);

  if lRaise is true then
    raise notice 'CodBar Com Digito: [%]  Digito: %', sCodBar, sDigito;
  end if;

  if length(sCodBar) <> 44 then
    if lRaise is true then
      raise notice 'Tamanho: %', length(sCodBar);
    end if;

    rRetorno.erro     := true;
    rRetorno.mensagem := 'Problema na geracao do codigo de barras, tamanho deve ser 44 e gerou '||length(sCodBar);

    return rRetorno;
  end if;

  -- Linha Digitavel
  sLinhaCampo1 := sBanco || sMoeda || substr(sCodBar, 20, 5);
  sLinhaCampo1 := sLinhaCampo1 || fc_modulo10(sLinhaCampo1); -- 10 Bytes

  sLinhaCampo2 := substr(sCodBar, 25, 10);
  sLinhaCampo2 := sLinhaCampo2 || fc_modulo10(sLinhaCampo2); -- 11 Bytes

  sLinhaCampo3 := substr(sCodBar, 35, 10);
  sLinhaCampo3 := sLinhaCampo3 || fc_modulo10(sLinhaCampo3); -- 11 Bytes

  sLinhaCampo4 := sDigito; -- 1 Byte

  sLinhaCampo5 := sFatorVenc || sValor; -- 14 bytes

  if lRaise is true then
    raise notice 'Linha Campo1: %  Campo2: %  Campo3: %  Campo4: %  Campo5: %',
    sLinhaCampo1, sLinhaCampo2, sLinhaCampo3, sLinhaCampo4, sLinhaCampo5;
  end if;


  --
  -- Campos Linha Digitavel
  --
  --           1           2           3           4
  -- 12345 67890 12345 678901 23456 789012  3  45678901234567
  -- 99999.99999 99999.999999 99999.999999  9  99999999999999
  -- ^^^^^^^^^^^ ^^^^^^^^^^^^ ^^^^^^^^^^^^  ^  ^^^^^^^^^^^^^^
  --   bloco 1     bloco 2       bloco 3    D    bloco 4
  --
  -- Tamanho total: 57
  --
  -- Posicoes:
  --
  -- - BLOCO 1
  --   . 01-03 = Codigo do Banco
  --   . 04-04 = Codigo da Moeda (padrao 9)
  --   . 05-09 = Posicao 20 - 24 do Codigo de Barras (CONVENIO - primeiros 5 digitos)
  --   . 10-10 = Digito verificador Modulo 10 da Posicao 01-09
  --
  -- - BLOCO 2
  --   . 11-20 = Posicao 25 - 34 do Codigo de Barras (Ultimo digito do Convenio + 9 caracteres inicias do NOSSO NUMERO)
  --   . 21-21 = Digito verificador Modulo 10 da Posicao 11-20
  --
  -- - BLOCO 3
  --   . 22-31 = Posicao 35 - 44 do Codigo de Barras (8 Ultimos digitos do NOSSO NUMERO + '21')
  --   . 32-32 = Digito verificador Modulo 10 da Posicao 11-20
  --
  -- - DIGITO VERIFICADOR
  --   . 33-33 = Digito Verificador do Codigo de Barras (Posicao 5-5 do Codigo de Barras)
  --
  -- - BLOCO 4
  --   . 34-37 = Fator Vencimento (data venc - 07/10/1997)
  --   . 38-47 = Valor do Documento
  --
  sLinha := substr(sLinhaCampo1, 1, 5) ||'.'|| substr(sLinhaCampo1, 6, 5) || ' ' ||
            substr(sLinhaCampo2, 1, 5) ||'.'|| substr(sLinhaCampo2, 6, 6) || ' ' ||
            substr(sLinhaCampo3, 1, 5) ||'.'|| substr(sLinhaCampo3, 6, 6) || '  ' ||
            sLinhaCampo4 || '  ' || sLinhaCampo5;

  rRetorno.codigobarras   := sCodBar;
  rRetorno.linhadigitavel := sLinha;
  rRetorno.convenio       := sConvenio;
  rRetorno.campolivre     := sCampoLivre;

  if iTipoConvenio in (5,6,7) then
    rRetorno.nossonumero  := sRetornoNossoNumero;
  else
    rRetorno.nossonumero  := sNossoNumero;
  end if;

  perform fc_debug('------<fc_fichacompensacao>', lRaise, false, true);

  return rRetorno;

end;
$$
language 'plpgsql';

create or replace function fc_fichacompensacaoarrebanco(integer, integer, integer) returns varchar  as
$$
declare

  iCodConvenio alias for $1;
  iNumpre      alias for $2;
  iNumpar      alias for $3;

  sMsgRet           varchar;
  sNumbcoSeq        integer; -- sequencial do arrebanco;
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

begin

  lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );

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

         insert into arrebanco (k00_numpre, k00_numpar, k00_codbco, k00_codage, k00_numbco)
                      values (iNumpre, iNumpar, iCodBanco, trim(sAgencia),sConvenio||lpad(cast(iNumpre as varchar),10,'0'));
       end if;

     else

       if iTipoConvenio = 6 then
         sNossoNumero := sCarteira || substr(sNumBancoa,1,3) || substr(sNumBancoa,5,3) || substr(sNumBancoa,9,9);
       else
         sNossoNumero := sNumBancoa;
       end if;

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

  return sNumBanco;

end;
$$
language 'plpgsql';

EOT;
        $this->execute($sFuncao);
    }

    public function down()
    {
        $sFuncao = <<<EOL
create or replace function fc_fichacompensacao(integer, integer, integer, date, float8) returns tp_fichacompensacao  as
$$
declare
  -- Parametros
  iCodConvenio alias for $1;
  iNumpre      alias for $2;
  iNumpar      alias for $3;
  dVencimento  alias for $4;
  nValor       alias for $5;

  -- Formatacao do Codigo de barras
  sBanco       char(3);
  sAgencia     varchar;
  sMoeda       char(1) default '9';
  sDigito      char(1);
  sFatorVenc   char(4);
  sValor       char(10);
  sCampoLivre  char(25);

  -- Formatacao Campo Livre
  sCedente     varchar;
  sConvenio    char(7);
  sCarteira    char(6);
  sOperacao    char(3);
  sNossoNumero text;
  sRetornoNossoNumero varchar;
  sNumBanco    varchar; -- numero do banco(arrbanco ) para padrao bsj

  -- Outros
  dDataBase    date default '1997-10-07';
  sCodBar      text default '';
  sLinha       text default '';
  sLinhaCampo1 text;
  sLinhaCampo2 text;
  sLinhaCampo3 text;
  sLinhaCampo4 text;
  sLinhaCampo5 text;

  iModalidadeConvenio integer;
  iTipoConvenio       integer;
  iDigito1            integer;
  iDigito2            integer;
  iResto              integer;

  rRetorno     tp_fichacompensacao;

  -- Debug
  lRaise       boolean default false;

begin

  lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );

  rRetorno.codigobarras   := sCodBar;
  rRetorno.linhadigitavel := sLinha;
  rRetorno.erro           := false;
  rRetorno.mensagem       := '';

  select ar12_sequencial,
         ar12_cadconveniomodalidade,
         ar13_cedente,
         ar13_convenio,
         ar13_carteira,
         ar13_operacao,
         db89_db_bancos,
         db89_codagencia
    into iTipoConvenio,
         iModalidadeConvenio,
         sCedente,
         sConvenio,
         sCarteira,
         sOperacao,
         sBanco,
         sAgencia
    from cadconvenio
         inner join cadtipoconvenio  on ar12_sequencial  = ar11_cadtipoconvenio
         inner join conveniocobranca on ar13_cadconvenio = ar11_sequencial
         inner join bancoagencia     on db89_sequencial  = ar13_bancoagencia
   where ar11_sequencial = iCodConvenio;

  if not found then
    rRetorno.erro     := true;
    rRetorno.mensagem := 'Banco nao cadastrado!';
    return rRetorno;
  end if;

  if iModalidadeConvenio != 1 then
    rRetorno.erro     := true;
    rRetorno.mensagem := 'Tipo de convênio diferente de cobrança!';
    return rRetorno;
  end if;

  if sCedente  is null or
     sConvenio is null or
     sCarteira is null or
     sBanco    is null or
     sCedente  = '' or
     sConvenio = '' or
     sCarteira = '' or
     sBanco    = '' then

    rRetorno.erro     := true;
    rRetorno.mensagem := 'Verificar configuracoes do Cadastro do Banco (Cedente, Convenio, Carteira, Banco)';

    return rRetorno;
  end if;

  -- Formatacoes
  sFatorVenc   := trim(to_char(dVencimento - dDataBase, '0000'));

  --sNossoNumero := '00000600179100058'; -- Para testes...
  sConvenio    := trim(sConvenio);
  sValor       := trim(to_char(nValor * 100, '0000000000'));

  -- Quatro Digitos = BSJ do Banrisul

  if iTipoConvenio = 2 then

    sConvenio    := trim(to_char(sConvenio::integer, '0000'));
    -- nosso numero sem os  digitos verificadores (para codigo de barras e linha digitavel)
    sNumBanco    := trim(fc_fichacompensacaoarrebanco(iCodConvenio, iNumpre, iNumpar))::integer;

    --
    -- 1. Nosso Numero
    --
    sNossoNumero := sConvenio||lpad(sNumBanco,7,0);

    iDigito1     := fc_modulo10(sNossoNumero); -- Calcula Modulo 10 do NossoNumero

    iResto       := fc_modulo11(sNossoNumero||cast(iDigito1 as char(1)), 2, 7); -- Retornar Resto (Calculo Modulo 11 Peso 7)

    if iResto = 1 then -- Digito Invalido
      iDigito1 := iDigito1 + 1; -- Soma-se 1 ao primeiro DV

      if iDigito1 > 9 then
        iDigito1 := 0;
      end if;

      iDigito2 := fc_modulo11(sNossoNumero||cast(iDigito1 as char(1)), 1, 7); -- Retornar Digito (Calculo Modulo 11 Peso 7)
    elsif iResto = 0 then
      iDigito2 := 0;
    else
      iDigito2 := fc_modulo11(sNossoNumero||cast(iDigito1 as char(1)), 1, 7); -- Retornar Digito (Calculo Modulo 11 Peso 7)
    end if;

    -- Monta Nosso Numero
    sNossoNumero := sNossoNumero||cast(iDigito1 as char(1))||cast(iDigito2 as char(1));

    --
    -- 2. Campo Livre
    --
    sCampoLivre  := ('23' || sNossoNumero || '00000' || sBanco ); -- '23' + Nosso Num (9) + '00000' + Banco (041) = 25 caracteres
    iDigito1     := fc_modulo10(sCampoLivre); -- Calcula Modulo 10 do Campo Livre
    iResto       := fc_modulo11(sCampoLivre||cast(iDigito1 as char(1)), 2, 7); -- Retornar Resto (Calculo Modulo 11 Peso 7)

    if iResto = 1 then -- Digito Invalido
      iDigito1 := iDigito1 + 1; -- Soma-se 1 ao primeiro DV

      if iDigito1 > 9 then
        iDigito1 := 0;
      end if;

      iDigito2 := fc_modulo11(sCampoLivre||cast(iDigito1 as char(1)), 1, 7); -- Retornar Digito (Calculo Modulo 11 Peso 7)
    elsif iResto = 0 then
      iDigito2 := 0;
    else
      iDigito2 := fc_modulo11(sCampoLivre||cast(iDigito1 as char(1)), 1, 7); -- Retornar Digito (Calculo Modulo 11 Peso 7)
    end if;

    -- Monta Campo Livre
    sCampoLivre := sCampoLivre||cast(iDigito1 as char(1))||cast(iDigito2 as char(1));


    elsif iTipoConvenio = 1 and length(sConvenio) = 6 then

    sConvenio    := trim(to_char(sConvenio::integer, '000000'));
        sNossoNumero := trim(to_char(coalesce(iNumpre, 0), '00000000000000')) || trim(to_char(coalesce(iNumpar, 0), '000'));
    sCampoLivre  := (sConvenio || sNossoNumero || '21'); -- Conv (6) + Nosso Num (17) + '21' (2) = 25 caracteres

    elsif iTipoConvenio = 1 and length(sConvenio) = 7 then

    sConvenio    := trim(to_char(sConvenio::integer, '0000000'));
      if iNumpar > 99 then
            rRetorno.erro     := true;
            rRetorno.mensagem := 'para convenios com 7 digitos, parcela deve ser no maximo 99';
            return rRetorno;
        end if;
        sNossoNumero := trim(to_char(coalesce(iNumpre, 0), '00000000')) || trim(to_char(coalesce(iNumpar, 0), '00'));
    sCampoLivre  := ('000000' || sConvenio || sNossoNumero || sCarteira);
        -- seis zeros + convenio de 7 posicoes + Nosso Num (10 - numpre (8) + numpar (2) ) + carteira (2) = 25 caracteres



  -- CONVENIO SICOB 11 Posições
  elsif iTipoConvenio = 5 then

    sNumBanco := trim(fc_fichacompensacaoarrebanco(iCodConvenio, iNumpre, iNumpar))::integer;
    -- 82 = Sem Registro
    -- 9  = Rápida
    sAgencia := substr(lpad(sAgencia,5,0),2,4);
    if sCarteira = '9' then
      sNossoNumero := lpad(sNumBanco,9,0);
    else
      sNossoNumero := lpad(sNumBanco,8,0);
    end if;

      iDigito1 := 11 - fc_modulo11(sCarteira||sNossoNumero,2,9);
      if iDigito1 > 9 then
        iDigito1 := 0;
      end if;
      sRetornoNossoNumero := (sCarteira||sNossoNumero||'-'||iDigito1);

    if lRaise is true then
      raise notice ' Campo Livre SICOB 11 Pos:';
      raise notice ' Carteira: %, Nosso Número: %, Agencia: %, Operação: %, Cedente: % ',sCarteira,sNossoNumero,sAgencia,sOperacao,sCedente;
    end if;

    if length(sCedente) != 8 then
      rRetorno.erro     := true;
      rRetorno.mensagem := 'Tamanho do campo cedente difere de 8 caracteres!';
      return rRetorno;
    end if;
    sCampoLivre := (sCarteira||sNossoNumero||sAgencia||sOperacao||sCedente);


  -- CONVENIO SIGCB 17 Posições

  elsif iTipoConvenio = 6 then

    if length(sCedente) != 6 then
      rRetorno.erro     := true;
      rRetorno.mensagem := 'Tamanho do campo cedente difere de 6 caracteres!';
      return rRetorno;
    end if;

    -- Calcula DV Cedente
    iDigito1 := 11 - fc_modulo11(sCedente,2,9);
    if iDigito1 > 9 then
      iDigito1 := 0;
    end if;
    sCedente := sCedente || iDigito1;

    sNossoNumero  := trim(fc_fichacompensacaoarrebanco(iCodConvenio, iNumpre, iNumpar));

    if lRaise is true then
      raise notice ' Campo Livre SIGCB :';
      raise notice ' Carteira: %, Nosso Número: %, Cedente: % ',sCarteira,sNossoNumero,sCedente;
    end if;


    sRetornoNossoNumero := substr(sNossoNumero,1,3) ||
                           substr(sNossoNumero,5,3) ||
                           substr(sNossoNumero,9,9);

    iDigito1 := 11 - fc_modulo11(sCarteira||sRetornoNossoNumero,2,9);

    if iDigito1 > 9 then
      iDigito1 := 0;
    end if;

    sRetornoNossoNumero := (sCarteira||'/'||sRetornoNossoNumero||'-'||iDigito1);

    sCampoLivre := ( sCedente || sNossoNumero );

    -- Calcula DV Campo Livre
    iDigito1 := 11 - fc_modulo11(sCampoLivre,2,9);
    if iDigito1 > 9 then
      iDigito1 := 0;
    end if;

    sCampoLivre := ( sCampoLivre || iDigito1 );

    -- Cendente         (7) +
    -- Nosso Numero 1pt (3) +
    -- Constante1       (1) +
    -- Nosso Numero 2pt (3) +
    -- Constante2       (1) +
    -- Nosso Numero 3pt (9) +
    -- DV Campo Livre   (1)
    --
    -- Total Caraceteres =  25 caracteres
    if lRaise then
      raise notice 'Campo livre : % ',sCampoLivre;
    end if;

  elsif iTipoConvenio = 7 then

    sNumBanco := trim(fc_fichacompensacaoarrebanco(iCodConvenio, iNumpre, iNumpar))::integer;
    -- 82 = Sem Registro
    -- 9  = Rápida
    sAgencia := substr(lpad(sAgencia,5,0),2,4);
    if sCarteira = '9' then
      sNossoNumero := lpad(sNumBanco,9,0);
    else
      sNossoNumero := lpad(sNumBanco,8,0);
    end if;

      iDigito1 := 11 - fc_modulo11(sCarteira||sNossoNumero,2,9);
      if iDigito1 > 9 then
        iDigito1 := 0;
      end if;
      sRetornoNossoNumero := sConvenio||lpad(cast(iNumpre as varchar),10,'0');

    if lRaise is true then
      raise notice ' Campo Livre SICOB 11 Pos:';
      raise notice ' Carteira: %, Nosso Número: %, Agencia: %, Operação: %, Cedente: % ',sCarteira,sNossoNumero,sAgencia,sOperacao,sCedente;
    end if;

    if length(sCedente) != 8 then
      rRetorno.erro     := true;
      rRetorno.mensagem := 'Tamanho do campo cedente difere de 8 caracteres!';
      return rRetorno;
    end if;
    --sCampoLivre := (sCarteira||sNossoNumero||sAgencia||sOperacao||sCedente);
    -- sNossoNumero := trim(to_char(coalesce(iNumpre, 0), '00000000')) || trim(to_char(coalesce(iNumpar, 0), '00'));

    -- alteração efetuada conforme conversa com Eduardo no skype dia 04/10 as 11 hs
    sNossoNumero := trim(to_char(coalesce(iNumpar, 0), '00'))||trim(to_char(coalesce(iNumpre, 0), '00000000')) ;
    sCampoLivre  := ('000000' || sConvenio || sNossoNumero || sCarteira);
  else
    rRetorno.erro     := true;
    rRetorno.mensagem := 'convenio diferente de 4 (BSJ), 6 ou 7 posicoes';
    return rRetorno;
    end if;

  if lRaise is true then
    raise notice 'Banco: %  Moeda: %  Fator Venc: %  Nosso Numero: %  Convenio: %  Valor: %  Campo Livre: %',
      sBanco, sMoeda, sFatorVenc, sNossoNumero, sConvenio, sValor, sCampoLivre;

    raise notice 'Tam Banco: %  Tam Moeda: %  Tam Fator Venc: %  Tam Nosso Numero: %  Tam Convenio: %  Tam Valor: %  Tam Campo Livre: %',
      length(sBanco), length(sMoeda), length(sFatorVenc), length(sNossoNumero), length(sConvenio), length(sValor), length(sCampoLivre);
  end if;

  -- Codigo Barras 43 posicoes (da 1 a 4  e da 6 a 44) + Digito Verificador na posicao 5 = Totalizando 44 Posicoes
  --                           1          2          3         4
  --              123 4 5 6789 0123456789 012345 67890123456789012 34
  --              999 9 9 9999 9999999999 999999 99999999999999999 21
  --              ^^^ ^ ^ ^^^^ ^^^^^^^^^^ ^^^^^^^^^^^^^^^^^^^^^^^^^^^
  --               |  | |  |     |               |
  --               |  | |  |     |               +------------------- Campo Livre (Convenio (6) + Nosso Num (17) + '21')
  --               |  | |  |     +----------------------------------- Valor
  --               |  | |  +----------------------------------------- Fator Vencimento (data venc - 07/10/1997)
  --               |  | +-------------------------------------------- Digito Verificador Modulo 11 do codigo de barras (1-4 e 6-44)
  --               |  +---------------------------------------------- Moeda
  --               +------------------------------------------------- Codigo do Banco
  --

  -- Codigo de Barras sem Digito Verificador
  sCodBar      := trim(sBanco || sMoeda || sFatorVenc || sValor || sCampoLivre);

  if lRaise is true then
    raise notice 'CodBar Sem Digito: [%] ', sCodBar;
  end if;

  -- Calcula Digito verificador Modulo 11 para o Codigo de Barras
  sDigito      := fc_modulo11(sCodBar)::char(1); -- Calcula Digito Modulo 11 (Peso 9)
  -- Se retornar '0' entao trocar pra '1', de acordo com manual do CNAB
  --if sDigito = '0' then
  --  sDigito := '1';
  --end if;

  -- Insere Digito verificador na posicao 5-5 do Codigo de Barras
  sCodBar      := trim(sBanco || sMoeda || sDigito || sFatorVenc || sValor || sCampoLivre);

  if lRaise is true then
    raise notice 'CodBar Com Digito: [%]  Digito: %', sCodBar, sDigito;
  end if;

  if length(sCodBar) <> 44 then
    if lRaise is true then
      raise notice 'Tamanho: %', length(sCodBar);
    end if;

    rRetorno.erro     := true;
    rRetorno.mensagem := 'Problema na geracao do codigo de barras, tamanho deve ser 44 e gerou '||length(sCodBar);

    return rRetorno;
  end if;

  -- Linha Digitavel
  sLinhaCampo1 := sBanco || sMoeda || substr(sCodBar, 20, 5);
  sLinhaCampo1 := sLinhaCampo1 || fc_modulo10(sLinhaCampo1); -- 10 Bytes

  sLinhaCampo2 := substr(sCodBar, 25, 10);
  sLinhaCampo2 := sLinhaCampo2 || fc_modulo10(sLinhaCampo2); -- 11 Bytes

  sLinhaCampo3 := substr(sCodBar, 35, 10);
  sLinhaCampo3 := sLinhaCampo3 || fc_modulo10(sLinhaCampo3); -- 11 Bytes

  sLinhaCampo4 := sDigito; -- 1 Byte

  sLinhaCampo5 := sFatorVenc || sValor; -- 14 bytes

  if lRaise is true then
    raise notice 'Linha Campo1: %  Campo2: %  Campo3: %  Campo4: %  Campo5: %',
      sLinhaCampo1, sLinhaCampo2, sLinhaCampo3, sLinhaCampo4, sLinhaCampo5;
  end if;


  --
  -- Campos Linha Digitavel
  --
  --           1           2           3           4
  -- 12345 67890 12345 678901 23456 789012  3  45678901234567
  -- 99999.99999 99999.999999 99999.999999  9  99999999999999
  -- ^^^^^^^^^^^ ^^^^^^^^^^^^ ^^^^^^^^^^^^  ^  ^^^^^^^^^^^^^^
  --   bloco 1     bloco 2       bloco 3    D    bloco 4
  --
  -- Tamanho total: 57
  --
  -- Posicoes:
  --
  -- - BLOCO 1
  --   . 01-03 = Codigo do Banco
  --   . 04-04 = Codigo da Moeda (padrao 9)
  --   . 05-09 = Posicao 20 - 24 do Codigo de Barras (CONVENIO - primeiros 5 digitos)
  --   . 10-10 = Digito verificador Modulo 10 da Posicao 01-09
  --
  -- - BLOCO 2
  --   . 11-20 = Posicao 25 - 34 do Codigo de Barras (Ultimo digito do Convenio + 9 caracteres inicias do NOSSO NUMERO)
  --   . 21-21 = Digito verificador Modulo 10 da Posicao 11-20
  --
  -- - BLOCO 3
  --   . 22-31 = Posicao 35 - 44 do Codigo de Barras (8 Ultimos digitos do NOSSO NUMERO + '21')
  --   . 32-32 = Digito verificador Modulo 10 da Posicao 11-20
  --
  -- - DIGITO VERIFICADOR
  --   . 33-33 = Digito Verificador do Codigo de Barras (Posicao 5-5 do Codigo de Barras)
  --
  -- - BLOCO 4
  --   . 34-37 = Fator Vencimento (data venc - 07/10/1997)
  --   . 38-47 = Valor do Documento
  --
  sLinha := substr(sLinhaCampo1, 1, 5) ||'.'|| substr(sLinhaCampo1, 6, 5) || ' ' ||
            substr(sLinhaCampo2, 1, 5) ||'.'|| substr(sLinhaCampo2, 6, 6) || ' ' ||
            substr(sLinhaCampo3, 1, 5) ||'.'|| substr(sLinhaCampo3, 6, 6) || '  ' ||
            sLinhaCampo4 || '  ' || sLinhaCampo5;

  rRetorno.codigobarras   := sCodBar;
  rRetorno.linhadigitavel := sLinha;
  rRetorno.convenio       := sConvenio;
  rRetorno.campolivre     := sCampoLivre;

  if iTipoConvenio in (5,6,7) then
    rRetorno.nossonumero  := sRetornoNossoNumero;
  else
    rRetorno.nossonumero  := sNossoNumero;
  end if;

  return rRetorno;

end;
$$
language 'plpgsql';


create or replace function fc_fichacompensacaoarrebanco(integer, integer, integer) returns varchar  as
$$
declare

  iCodConvenio alias for $1;
  iNumpre      alias for $2;
  iNumpar      alias for $3;

  sMsgRet           varchar;
  sNumbcoSeq        integer; -- sequencial do arrebanco;
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

begin

  lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );

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

     sNumbcoSeq := sNumbcoSeq + 1;

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

         insert into arrebanco (k00_numpre, k00_numpar, k00_codbco, k00_codage, k00_numbco)
                      values (iNumpre, iNumpar, iCodBanco, trim(sAgencia),sConvenio||lpad(cast(iNumpre as varchar),10,'0'));
       end if;

     else

       if iTipoConvenio = 6 then
         sNossoNumero := sCarteira || substr(sNumBancoa,1,3) || substr(sNumBancoa,5,3) || substr(sNumBancoa,9,9);
       else
         sNossoNumero := sNumBancoa;
       end if;

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
  else
    sNumBanco := trim(to_char(sNumbcoSeq,'0000000'));
  end if;

  perform fc_debug(' <fc_fichacompensacaoarrebanco> Numbanco retornado: ' || sNumBanco, lRaise);

  return sNumBanco;

end;
$$
language 'plpgsql';

EOL;

        $this->execute($sFuncao);
    }
}
