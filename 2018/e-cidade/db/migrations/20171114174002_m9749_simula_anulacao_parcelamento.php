<?php

use Classes\PostgresMigration;

class M9749SimulaAnulacaoParcelamento extends PostgresMigration
{
    public function up()
    {
        $sSql1 = "
        set check_function_bodies to on;
create or replace function fc_excluiparcelamentopag(integer, integer, integer, integer)
returns varchar(100)
as $$
declare
  
  iParcelamento       alias for $1; -- parcelamento
  iTipoParcelamento   alias for $2; -- tipo
    -- 1 = divida ativa
    -- 2 = reparcelamento
    -- 3 = parcelamento de inicial
    -- 4 = parcelamento de diversos
    -- 5 = parcelamento de contribuicao de melhorias 
  iNumpre       	  	alias for $3; -- numpre
  iCodUsuario    	   	alias for $4; -- login
  
  iQuantidade			    integer;
  iIdInicialMov		  	integer;
  iSeqTermoSimulaReg	integer;
  iSeqTermoSimula    	integer;
  iAnoUsu            	integer default 0;
  iTipoAnulacao      	integer default 0;
  iInstit            	integer default 0;

  nValorPago				  numeric(15,2) default 0;
  nValorDevido			  numeric(15,2) default 0;
  nTotalParcelado		  numeric(15,2) default 0;
  nTotalOrigem			  numeric(15,2) default 0;
  nPercAbatimento     numeric(15,2) default 0;
  nTotal      			  numeric(15,2) default 0;
  nValorParcela			  numeric(15,2) default 0;
  nValorTotalParcela  numeric(15,2) default 0;
  nValorAbatido       numeric(15,2) default 0;
  nSaldoAbater        numeric(15,2) default 0;
  nDifArredondamento  numeric(15,2) default 0;

  dDataHoje           date;
  dDataVencParc       date;
  dDataOperParc       date;

  sSql						    text;
  sSqlInicial 		  	text;
  sMensagemErro		  	text;

  sCampoValor         varchar;
  
  rOrigens	          record;
  rIniciaMov       	  record;
  
  lErroOperacao  		  boolean default false;
  lRaise						  boolean default false;
  lParcial					  boolean default false;
  
begin
    
  lRaise := ( case when fc_getsession('DB_debugon') is null then false else true end );
  
  iSeqTermoSimula := nextval('termosimula_v21_sequencial_seq');
  dDataHoje := cast( (select fc_getsession('DB_datausu')) as date);
  if dDataHoje is null then
    raise exception 'ERRO : Variavel de sessao [DB_datausu] nao encontrada.';
  end if;
  
  iAnoUsu := cast( (select fc_getsession('DB_anousu')) as integer);
  if iAnoUsu is null then
    raise exception 'ERRO : Variavel de sessao [DB_anousu] nao encontrada.';
  end if;
  
  iInstit := cast( (select fc_getsession('DB_instit')) as integer);
  if iInstit is null then
    raise exception 'ERRO : Variavel de sessao [DB_instit] nao encontrada.';
  end if;
      
  perform fc_debug(' Variaveis de sessao validadas...',lRaise,false,false);
  
  select k40_tipoanulacao
    into iTipoAnulacao
    from termo 
   inner join cadtipoparc on cadtipoparc.k40_codigo = termo.v07_desconto  
   where termo.v07_parcel = iParcelamento; 
   
  perform fc_debug('Regra de Anulacao da Regra de Parcelamento (cadtipoparc:k40_tipoanulacao): '||iTipoAnulacao,lRaise,false,false);    
  perform fc_debug('',lRaise,false,false);
  
  perform fc_debug('Exercicio : '||coalesce(iAnoUsu,0)||' Instituicao : '||coalesce(iInstit,0)||'',lRaise,false,false);
  perform fc_debug('',lRaise,false,false);
  
  -- calculando valor pago
  perform fc_debug('--------------------------------------------------------------------------------------- ',lRaise,false,false);  
  perform fc_debug('Calculando Valor Pago (funcao fc_parc_getvalorpago()): '                                 ,lRaise,false,false); 
  perform fc_debug(''                                                                                        ,lRaise,false,false);
  select rnvalor::numeric,
         rsmensagemerro,
         rlerro
    into nValorPago,
         sMensagemErro,
         lErroOperacao
    from fc_parc_getvalorpago(iParcelamento,dDataHoje,iAnoUsu);
  if lErroOperacao then      
    perform fc_debug('1 - ERRO :'||coalesce(sMensagemErro,''),lRaise,false,false);
    return '1 - ERRO :'||coalesce(sMensagemErro,'');
  end if;
  perform fc_debug(''                                                                                        ,lRaise,false,false);  
  perform fc_debug('Valor Pago: '||nValorPago                                                                ,lRaise,false,false);
  perform fc_debug('--------------------------------------------------------------------------------------- ',lRaise,false,false);  
  perform fc_debug('',lRaise,false,false);
  
  
  -- calculando valor devido
  perform fc_debug('--------------------------------------------------------------------------------------- ',lRaise,false,false);  
  perform fc_debug('Calculando Valor Devido (funcao fc_parc_getvalordevido()):'                              ,lRaise,false,false);
  perform fc_debug(''                                                                                        ,lRaise,false,false);
  select rnvalor::numeric,
         rsmensagemerro,
         rlerro
    into nValorDevido,
         sMensagemErro,
         lErroOperacao
    from fc_parc_getvalordevido(iParcelamento,dDataHoje,iAnoUsu,iTipoParcelamento);
  if lErroOperacao then
    perform fc_debug('2 - ERRO :'||coalesce(sMensagemErro,''),lRaise,false,false);
    return '2 - ERRO :'||coalesce(sMensagemErro,'');
  end if;
 perform fc_debug(''                                                                                           ,lRaise,false,false);  
 perform fc_debug('Valor Devido :'||coalesce(nValorDevido,0)                                                   ,lRaise,false,false);
 perform fc_debug('--------------------------------------------------------------------------------------- '   ,lRaise,false,false); 
 perform fc_debug('',lRaise,false,false);
 
  --
  -- Funcao fc_parc_getselectorigens(iParcelamento,iTipoParcelamento) retorna sql com as origens do parcelamento(arreold)
  --
  perform fc_debug('--------------------------------------------------------------------------------------- '  ,lRaise,false,false);  
  perform fc_debug('Verificando Origens do Parcelamento: (funcao fc_parc_getselectorigens())'                  ,lRaise,false,false);
  perform fc_debug('Desta funcao e retornado o sql que sera a base da busca das origens do parcelamento'       ,lRaise,false,false);  
  
  sSql := fc_parc_getselectorigens(iParcelamento,iTipoParcelamento);
  
  perform fc_debug(''                                                                                          ,lRaise,false,false);
  perform fc_debug('---------------------------------------------------------------------------------------  ' ,lRaise,false,false);
  
  perform fc_debug(''                                                                                          ,lRaise,false,false);
  perform fc_debug('Verificando valor a ser utilizado como Total Parcelado de acordo com a regra de anulacao:' ,lRaise,false,false);
  perform fc_debug('Regra de Anulacao 1 ............: nTotalParcelado := ( nValorPago + nValorDevido )'        ,lRaise,false,false);
  perform fc_debug('Regra de Anulacao 2 ............: nTotalParcelado := v07_valor'                            ,lRaise,false,false);
  perform fc_debug('Regra de Anulacao 3 ............: nTotalParcelado := nValorDevido'                         ,lRaise,false,false);  
  perform fc_debug('Regra de Anulacao Utilizada ....: '||iTipoAnulacao                                         ,lRaise,false,false);  
  perform fc_debug(''                                                                                          ,lRaise,false,false);
  
  if iTipoAnulacao = 1 then
      -- somando valor pago + devido
    nTotalParcelado := ( nValorPago + nValorDevido );
    
  elsif iTipoAnulacao = 3 then
  
    nTotalParcelado := nValorDevido;

  elsif iTipoAnulacao = 2 then
  
    select v07_valor
      into nTotalParcelado 
      from termo 
     where v07_parcel = iParcelamento; 
    
  end if;
  
  perform fc_debug('nValorDevido .....: '||nValorDevido                                                        ,lRaise,false,false);
  perform fc_debug('nValorPago .......: '||nValorPago                                                          ,lRaise,false,false);
  perform fc_debug('nTotalParcelado ..: '||nTotalParcelado                                                     ,lRaise,false,false);
  perform fc_debug(''                                                                                          ,lRaise,false,false);  
  
  execute 'select count(*) from ('||coalesce(sSql,'')||') as quantidade_origem' into iQuantidade ;
  perform fc_debug('Quantidade de registros da origem : '||coalesce(iQuantidade,0),lRaise,false,false); 

  if iQuantidade = 0 or iQuantidade is null then
    return '3 - ORIGEM DO PARCELAMENTO INCONSISTENTE! TIPO : '||coalesce(iTipoParcelamento,0);
  end if;

  -- verificamos os inflatores
  perform fc_debug('Verificando Inflatores das Origens:',lRaise,false,false);
  perform fc_debug(' - Nao pode haver valor corrigido do debito menor que zero se existir tal registro este vencimento não possui inflator',lRaise,false,false);
  
  execute 'select ''Receita: ''||k00_receit||'' Vencto: ''||k00_dtvenc from ('||coalesce(sSql,'')||') as x where corrigido < 0' into sMensagemErro ;
  if sMensagemErro is not null then
    return '4 - ENCONTRADOS REGISTROS SEM INFLATORES CONFIGURADOS PARA A DATA DE VENCIMENTO '||sMensagemErro||' - TIPO: '||coalesce(iTipoParcelamento,0);
  end if;
  perform fc_debug('',lRaise,false,false);

  
  if iTipoAnulacao = 2 then
    -- sCampoValor := 'corrigido+juros+multa'; -- comentado esta linha conforme conversa com Evandro, pois o valor para calculo deve ser apenas o valor corrigido
    sCampoValor := 'corrigido';
  else
    sCampoValor := 'k00_valor';
  end if;

  perform fc_debug(''                                                                     ,lRaise,false,false);
  perform fc_debug('Buscando saldo a abater:'                                             ,lRaise,false,false);
  perform fc_debug('Soma do campo '||sCampoValor||' do Sql das origens'                   ,lRaise,false,false);
  perform fc_debug('*Regra de Anulacao 1 e 3 utilizam o campo k00_valor do arreold'                   ,lRaise,false,false);
  perform fc_debug('*Regra de Anulacao 2 utiliza o corrigido (fc_corre sobre o k00_valor do arreold)' ,lRaise,false,false);
  
  execute 'select sum('||coalesce(sCampoValor,'')||') from ('||coalesce(sSql,'')||') as valor' 
     into nSaldoAbater;

  nPercAbatimento := ( nValorPago / nTotalParcelado * 100 ) ;
  
  perform fc_debug(' ---------------------------------------------------------------------------------------------- '                  ,lRaise,false,false); 
  perform fc_debug(' Percent. Pago ou Percent. de Abatimento ( nValorPago / nTotalParcelado * 100 ) ..: '||coalesce(nPercAbatimento,0) ,lRaise,false,false);
  perform fc_debug(' VALOR PAGO ......................................................................: '||coalesce(nValorPago,0)      ,lRaise,false,false);
  perform fc_debug(' VALOR TOTAL PARCELADO ...........................................................: '||coalesce(nTotalParcelado,0) ,lRaise,false,false);
  perform fc_debug(' SALDO A ABATER ..................................................................: '||coalesce(nSaldoAbater,0)    ,lRaise,false,false);
  perform fc_debug(' '                                                                                                                 ,lRaise,false,false);
  perform fc_debug('Recalculando Saldo a Abater:'                                           ,lRaise,false,false);
  perform fc_debug('Regra 1 ......: nSaldoAbater = ( nSaldoAbater / 100 ) * nPercAbatimento',lRaise,false,false);
  perform fc_debug('Regra 2 e 3 ..: nSaldoAbater = nValorPago '                             ,lRaise,false,false);
  perform fc_debug('Regra de Anulacao Utilizada ....: '||iTipoAnulacao                                         ,lRaise,false,false);  
  
  if iTipoAnulacao = 2 or iTipoAnulacao = 3 then
    nSaldoAbater    := nValorPago ;
  else
   
    perform fc_debug('nSaldoAbater = ( ( nSaldoAbater / 100 ) * nPercAbatimento ) = ( ( '||nSaldoAbater||' / 100 ) * '||nPercAbatimento||' ) = '||( ( nSaldoAbater / 100 ) * nPercAbatimento ) ,lRaise,false,false);
    nSaldoAbater    := round ( ( ( nSaldoAbater / 100 ) * nPercAbatimento ), 2) ;
  end if;
  
  perform fc_debug(' '                                                                                             ,lRaise,false,false);
  perform fc_debug('Saldo a Abater ou Valor a Abater recalculado : '||coalesce(nSaldoAbater,0)                       ,lRaise,false,false);
  perform fc_debug(' '                                                                                               ,lRaise,false,false);
  perform fc_debug(' ---------------------------------------------------------------------------------------------- ',lRaise,false,false); 
  
  perform fc_debug(' '                         ,lRaise,false,false);
  perform fc_debug('Processando simulacao ...' ,lRaise,false,false);  
  perform fc_debug(' '                         ,lRaise,false,false);

  perform fc_debug(' '                                                                                                                            ,lRaise,false,false);  
  perform fc_debug(' 1 - D E S A T I V A N D O   S I M U L A C A O   D E   A N U L A C A O   D E   P A R C E L A M E N T O   A N T E R I O R E S' ,lRaise,false,false);
  perform fc_debug(' '                                                                                                                            ,lRaise,false,false);  
  update termosimula 
     set v21_ativo = false 
   where v21_parcel = iParcelamento 
     and v21_data  <= dDataHoje;
     
  --
  -- Verificar as variaveis do insert abaixo
  --
  perform fc_debug(' '                                                                        ,lRaise,false,false);  
  perform fc_debug(' 2 - I N S E R I N D O   R E G I S T R O S   N A   T E R M O S I M U L A' ,lRaise,false,false);
  perform fc_debug(' '                                                                        ,lRaise,false,false);  
  insert into termosimula ( v21_sequencial,
                            v21_parcel,    
                            v21_usuario,   
                            v21_data,      
                            v21_hora,      
                            v21_validade,  
                            v21_tipo,
                            v21_percretorno,
                            v21_valordevido,
                            v21_valorpago,
                            v21_formaanulacao,
                            v21_ativo)
                   values ( iSeqTermoSimula,
                            iParcelamento,
                            1,
                            dDataHoje,
                            '',
                            dDataHoje,
                            1,
                            round((100 - nPercAbatimento),2), 
                            nValorDevido,
                            nValorPago,
                            iTipoAnulacao,
                            true );
 
  --
	-- Percorrendo os registros da origem do parcelamento
  --
  perform fc_debug(' '                                                    ,lRaise,false,false);  
  perform fc_debug(' 3 - P E R C O R R E N D O   R E G I S T R O S   D A   O R I G E M   D O   P A R C E L A M E N T O',lRaise,false,false);
  perform fc_debug(' '                                                    ,lRaise,false,false);  
  
  for rOrigens in execute sSql loop 

    perform fc_debug('   ==> INICIO DO PROCESSAMENTO DO NUMPRE: '||rOrigens.k00_numpre||', PARCELA:'||rOrigens.k00_numpar||', RECEITA: '||rOrigens.k00_receit||', VALOR : '||rOrigens.k00_valor,lRaise,false,false);
    perform fc_debug(''                                    ,lRaise,false,false);    
    perform fc_debug('     Calculando valor da parcela...' ,lRaise,false,false);
    
    nTotalOrigem          := nTotalOrigem + rOrigens.k00_valor;
    if iTipoAnulacao = 2 then
    
      perform fc_debug('       Regra de Anulacao 2: Corrigido+Juro+Multa',lRaise,false,false);
      nValorTotalParcela := ( round(rOrigens.corrigido,2) + round(rOrigens.juros,2) + round(rOrigens.multa,2) );
      
    elsif iTipoAnulacao = 3 then
    
      perform fc_debug('       Regra de Anulacao 3: Corrigido+Juro+Multa',lRaise,false,false);
      nValorTotalParcela := ( rOrigens.corrigido + rOrigens.juros + rOrigens.multa );
      
    elsif iTipoAnulacao = 1 then
    
      nValorTotalParcela := ( rOrigens.k00_valor );
      perform fc_debug('       (Regra de Anulacao Padrao): arreold.k00_valor',lRaise,false,false);
      
    end if;
    
    perform fc_debug('     Fim do calculo da parcela ',lRaise,false,false);    
    perform fc_debug('     Valor da Parcela: '||nValorTotalParcela,lRaise,false,false);
    perform fc_debug('',lRaise,false,false);
    perform fc_debug('     - Chamando funcao fc_processa_tipo_anulacao() para processar a regra de retorno das origens do parcelamentos...',lRaise,false,false);
    perform fc_debug('       Neste momento eh calculado o valor de retorno dos debitos de origem (arrecad.k00_valor)',lRaise,false,false);
    
    select rnValorParcela, 
           rnValorAbatido, 
           rnSaldoAbater, 
           rlErro, 
           rsMensagem,
           rlParcial
      into nValorParcela, 
           nValorAbatido, 
           nSaldoAbater, 
           lErroOperacao, 
           sMensagemErro,
           lParcial
      from fc_processa_tipo_anulacao( iTipoAnulacao,      -- Tipo de anulacao (campo k03_tipoanuparc)
                                      round(rOrigens.k00_valor,2), -- Valor historico da parcela
                                      round(nValorTotalParcela,2), -- Valor da parcela para comparacao de acordo com a regra
                                      round(nSaldoAbater,2),       -- Saldo a ser abatido do valor da parcela
                                      round(nValorPago,2),         
                                      round(nValorDevido,2),
                                      round(nTotalParcelado,2) );

    dDataVencParc := rOrigens.k00_dtvenc;
    dDataOperParc := rOrigens.k00_dtoper;
    
    iSeqTermoSimulaReg := nextval('termosimulareg_v23_sequencial_seq');

    perform fc_debug('     Inserindo registros na termosimulareg' ,lRaise,false,false);
    insert into termosimulareg ( v23_sequencial, 
                                 v23_termosimula,
                                 v23_numpre,     
                                 v23_numpar,     
                                 v23_numcgm,     
                                 v23_dtoper,     
                                 v23_dtvenc,
                                 v23_receit,     
                                 v23_hist,       
                                 v23_valor,      
                                 v23_numtot,     
                                 v23_numdig,     
                                 v23_tipo,       
                                 v23_tipojm,     
                                 v23_vlrcor,     
                                 v23_vlrjur,     
                                 v23_vlrmul,     
                                 v23_vlrdes,     
                                 v23_vlrabatido, 
                                 v23_saldoabater )
                        values ( iSeqTermoSimulaReg,
                                 iSeqTermoSimula,
                                 rOrigens.k00_numpre,
                                 rOrigens.k00_numpar,
                                 rOrigens.k00_numcgm,
                                 dDataOperParc,
                                 dDataVencParc,
                                 rOrigens.k00_receit,
                                 rOrigens.k00_hist,
                                 round(rOrigens.k00_valor,2),
                                 rOrigens.k00_numtot,
                                 rOrigens.k00_numdig,
                                 rOrigens.k00_tipo,
                                 rOrigens.k00_tipojm,
                                 round(rOrigens.corrigido,2),
                                 round(rOrigens.juros,2),
                                 round(rOrigens.multa,2),
                                 0, -- desconto
                                 round(nValorAbatido,2),
                                 round(nSaldoAbater,2) );

    nTotal := ( nTotal + nValorParcela );

    perform fc_debug(''                                                                                                                                                                     ,lRaise,false,false);    
    perform fc_debug('   ==> FIM DO PROCESSAMENTO DO NUMPRE: '||rOrigens.k00_numpre||', PARCELA:'||rOrigens.k00_numpar||', RECEITA: '||rOrigens.k00_receit||', VALOR : '||rOrigens.k00_valor,lRaise,false,false);
    
  end loop;

  perform fc_debug('',lRaise,false,false);
  perform fc_debug('Acertando a diferenca dos centavos no ultimo registro (arredondamento)' ,lRaise,false,false);
  update termosimulareg 
     set v23_vlrabatido  = ( v23_vlrabatido + nSaldoAbater ) ,
         v23_saldoabater = ( v23_saldoabater - nSaldoAbater )
   where v23_sequencial = iSeqTermoSimulaReg;

  perform fc_debug(' ---------------------------------------------------------------------------------------------- ',lRaise,false,false);  
  perform fc_debug(' TOTAL PAGO (1)        : '||coalesce(nValorPago,0)||' - DEVIDO: '||coalesce(nValorDevido,0)||' - TOTAL DO PARCELAMENTO: '||coalesce(nTotalParcelado,0)||' - nTotalOrigem: '||coalesce(nTotalOrigem,0),lRaise,false,false); 
  perform fc_debug(' ---------------------------------------------------------------------------------------------- ',lRaise,false,false);

  return 'ok';

end;
$$ language 'plpgsql';

        ";

        $sSql2 = "
        create or replace function fc_parc_getselectorigens_atjuros(integer,integer,integer) returns varchar as
$$
declare

  iParcelamento      alias for $1;
  iTipo              alias for $2;
  iTipoAnulacao      alias for $3;

  iAnoUsu            integer default 0;

  dDataCorrecao      date;

  sCamposSql         varchar default '';
  sSqlRetorno        varchar default '';
  sSql               varchar default '';
  sCampoInicial      varchar default '';

  lRaise             boolean default false;


begin

  lRaise := ( case when fc_getsession('DB_debugon') is null then false else true end );

  iAnoUsu := cast( (select fc_getsession('DB_anousu')) as integer);
  if iAnoUsu is null then
    raise exception 'ERRO : Variavel de sessao [DB_anousu] nao encontrada.';
  end if;

  dDataCorrecao := cast( (select fc_getsession('DB_datausu')) as date);
  if dDataCorrecao is null then
    raise exception 'ERRO : Variavel de sessao [DB_datausu] nao encontrada.';
  end if;

  perform fc_debug(''                                                      ,lRaise,false,false);
  perform fc_debug('Processando funcao fc_parc_getselectorigens_atjuros...' ,lRaise,false,false);

  sCamposSql := ' distinct
                  k00_numcgm,
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
                  k00_tipojm,
                  termo.v07_dtlanc,';

  perform fc_debug('Verificamos a Regra de Anulacao:'                                                                    ,lRaise,false,false);
  perform fc_debug('Regra de Anulacao da Regra de Parcelamento (cadtipoparc:k40_tipoanulacao): '||iTipoAnulacao          ,lRaise,false,false);
  perform fc_debug('Regra de Anulacao 1 ......: Utilizamos o valor do arreold (campo: k00_valor) como corrigido sem aplicar correcao',lRaise,false,false);
  perform fc_debug('Regra de Anulacao 2 e 3 ..: Aplicamos correcao (fc_corre) sobre o valor do arreold (campo: k00_valor)'           ,lRaise,false,false);

  if iTipoAnulacao = 1 then

    sCamposSql := sCamposSql || ' k00_valor as corrigido, \n';
    sCamposSql := sCamposSql || ' 0 as juros,             \n';
    sCamposSql := sCamposSql || ' 0 as multa              \n';

  else

    sCamposSql := sCamposSql || ' fc_corre(arreold.k00_receit,arreold.k00_dtoper,arreold.k00_valor,\''||dDataCorrecao||'\','||iAnoUsu||',\''||dDataCorrecao||'\') as corrigido, \n';
    sCamposSql := sCamposSql || ' 0 as juros, \n';
    sCamposSql := sCamposSql || ' 0 as multa  \n';

  end if;

  if iTipo = 1 then

    perform fc_debug('Tipo de Parcelamento 1 - termodiv: '                                             ,lRaise,false,false);
    perform fc_debug('Sql busca os dados da termodiv, divida e arreold'                                ,lRaise,false,false);
    perform fc_debug('Valor corrigido varia de acordo com a Regra de Anulacao explicado anteriormente' ,lRaise,false,false);

    sSqlRetorno :=                ' select '||sCamposSql||                                                                                          '\n';
    sSqlRetorno := sSqlRetorno || '   from termo                                                                                                     \n';
    sSqlRetorno := sSqlRetorno || '        inner join termodiv  on termo.v07_parcel 	= termodiv.parcel                                              \n';
    sSqlRetorno := sSqlRetorno || '        inner join divida    on termodiv.coddiv   	= divida.v01_coddiv                                            \n';
    sSqlRetorno := sSqlRetorno || '        inner join arreold   on arreold.k00_numpre	= divida.v01_numpre and arreold.k00_numpar = divida.v01_numpar \n';
    sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento ||                                                                 '\n';
    sSqlRetorno := sSqlRetorno || '  order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit                                               \n';

  elsif iTipo = 2 then

    perform fc_debug('Tipo de Parcelamento 2 - termoreparc: ' ,lRaise,false,false);
    perform fc_debug('Sql busca os dados da termoreparc, termo, tabelas de origem do termo (termodiv, termoini, termodiver e termocontrib), arreold etc ' ,lRaise,false,false);
    perform fc_debug('Valor corrigido varia de acordo com a Regra de Anulacao explicado anteriormente' ,lRaise,false,false);

    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||'                                                         \n';
    sSqlRetorno := sSqlRetorno || '     from termoreparc                                                              \n';
    sSqlRetorno := sSqlRetorno || '          inner join termo on v07_parcel            = termoreparc.v08_parcelorigem \n';
    sSqlRetorno := sSqlRetorno || '          inner join arreold on arreold.k00_numpre  = termo.v07_numpre             \n';
    sSqlRetorno := sSqlRetorno || '   where termoreparc.v08_parcel = ' || iParcelamento ||                           '\n';

    sSqlRetorno := sSqlRetorno || ' union all \n';	-- tras os reparcelamentos de divida

    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||'                                                         \n';
    sSqlRetorno := sSqlRetorno || '     from termoreparc                                                              \n';
    sSqlRetorno := sSqlRetorno || '          inner join termo     on v07_parcel         = termoreparc.v08_parcel      \n';
    sSqlRetorno := sSqlRetorno || '          inner join termodiv  on termo.v07_parcel 	= termodiv.parcel             \n';
    sSqlRetorno := sSqlRetorno || '          inner join divida  	on termodiv.coddiv   	= divida.v01_coddiv           \n';
    sSqlRetorno := sSqlRetorno || '          inner join arreold 	on arreold.k00_numpre	= divida.v01_numpre           \n';
    sSqlRetorno := sSqlRetorno || '                              and arreold.k00_numpar = divida.v01_numpar           \n';
    sSqlRetorno := sSqlRetorno || '   where termoreparc.v08_parcel = ' || iParcelamento ||                           '\n';

  	sSqlRetorno := sSqlRetorno || ' union all \n';	-- tras os reparcelamentos do foro

    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||                                                               '\n';
    sSqlRetorno := sSqlRetorno || '     from termoreparc                                                                    \n';
    sSqlRetorno := sSqlRetorno || '          inner join termo         on v07_parcel                = termoreparc.v08_parcel \n';
    sSqlRetorno := sSqlRetorno || '          inner join termoini      on termo.v07_parcel 	       = termoini.parcel        \n';
    sSqlRetorno := sSqlRetorno || '          inner join inicialnumpre on inicialnumpre.v59_inicial = termoini.inicial       \n';
    sSqlRetorno := sSqlRetorno || '          inner join divida 	      on inicialnumpre.v59_numpre  = divida.v01_numpre      \n';
    sSqlRetorno := sSqlRetorno || '          inner join arreold 	    on arreold.k00_numpre        = divida.v01_numpre      \n';
	  sSqlRetorno := sSqlRetorno || '                                  and arreold.k00_numpar        = divida.v01_numpar      \n';
    sSqlRetorno := sSqlRetorno || '   where termoreparc.v08_parcel = ' || iParcelamento;

	  sSqlRetorno := sSqlRetorno || ' union all \n';	-- tras os reparcelamentos de diversos

    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||                                                              '\n';
    sSqlRetorno := sSqlRetorno || '     from termoreparc                                                                   \n';
    sSqlRetorno := sSqlRetorno || '          inner join termo         on v07_parcel             = termoreparc.v08_parcel   \n';
    sSqlRetorno := sSqlRetorno || '          inner join termodiver    on termo.v07_parcel 	 	  = termodiver.dv10_parcel   \n';
    sSqlRetorno := sSqlRetorno || '          inner join diversos      on diversos.dv05_coddiver = termodiver.dv10_coddiver \n';
    sSqlRetorno := sSqlRetorno || '          inner join arreold 	    on arreold.k00_numpre     = diversos.dv05_numpre     \n';
    sSqlRetorno := sSqlRetorno || '   where termoreparc.v08_parcel = ' || iParcelamento ||                                '\n';

	  sSqlRetorno := sSqlRetorno || ' union all \n';	-- tras os reparcelamentos de contribuicao de melhorias

    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||'                                                                          \n';
    sSqlRetorno := sSqlRetorno || '     from termoreparc                                                                               \n';
    sSqlRetorno := sSqlRetorno || '          inner join termo         on v07_parcel                = termoreparc.v08_parcel            \n';
    sSqlRetorno := sSqlRetorno || '          inner join termocontrib  on termo.v07_parcel          = termocontrib.parcel               \n';
    sSqlRetorno := sSqlRetorno || '          inner join contricalc    on contricalc.d09_sequencial = termocontrib.contricalc           \n';
    sSqlRetorno := sSqlRetorno || '          inner join arreold 	     on arreold.k00_numpre        = contricalc.d09_numpre            \n';
	  sSqlRetorno := sSqlRetorno || '          left  join divold  	     on arreold.k00_numpre        = divold.k10_numpre                \n';
    sSqlRetorno := sSqlRetorno || '                                  and arreold.k00_numpar        = divold.k10_numpar                 \n';
	  sSqlRetorno := sSqlRetorno || '                                  and arreold.k00_receit        = divold.k10_receita                \n';
    sSqlRetorno := sSqlRetorno || '   where ( divold.k10_numpre is null and divold.k10_numpar is null and divold.k10_receita is null ) \n';
	  sSqlRetorno := sSqlRetorno || '     and termoreparc.v08_parcel = ' || iParcelamento ||                                            '\n';
    sSqlRetorno := sSqlRetorno || '   order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit                                \n';

  elsif iTipo = 3 then  -- parcelamento de inicial

    perform fc_debug('Tipo de Parcelamento 3 - termoini: '                                                                                      ,lRaise,false,false);
    perform fc_debug('Sql busca os dados da termo, termoini, inicialnumpre, inicialcert, certdiv, divida, arreold, arreoldcalc, certter, termo' ,lRaise,false,false);
    perform fc_debug('Valor corrigido varia de acordo com a Regra de Anulacao explicado anteriormente'                                          ,lRaise,false,false);

    sSqlRetorno :=                '  select '||sCamposSql||', inicial                                                       \n';
    sSqlRetorno := sSqlRetorno || '   from termo                                                                            \n';
    sSqlRetorno := sSqlRetorno || '        inner join termoini    	on termo.v07_parcel 	       = termoini.parcel          \n';
    sSqlRetorno := sSqlRetorno || '        inner join inicialnumpre on inicialnumpre.v59_inicial = termoini.inicial         \n';
  	sSqlRetorno := sSqlRetorno || '        inner join inicialcert   on termoini.inicial          = inicialcert.v51_inicial  \n';
	  sSqlRetorno := sSqlRetorno || '        inner join certdiv       on certdiv.v14_certid        = inicialcert.v51_certidao \n';
    sSqlRetorno := sSqlRetorno || '        inner join divida        on certdiv.v14_coddiv        = divida.v01_coddiv        \n';
    sSqlRetorno := sSqlRetorno || '        inner join arreold 	    on arreold.k00_numpre        = divida.v01_numpre        \n';
    sSqlRetorno := sSqlRetorno || '                               and arreold.k00_numpar         = divida.v01_numpar        \n';
    sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento ||                                        '\n';
	  sSqlRetorno := sSqlRetorno || '  union                                                                                  \n';
    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||', inicial                                                      \n';
    sSqlRetorno := sSqlRetorno || '   from termo                                                                            \n';
    sSqlRetorno := sSqlRetorno || '        inner join termoini    	     on termo.v07_parcel 	  = termoini.parcel           \n';
    sSqlRetorno := sSqlRetorno || '        inner join inicialnumpre      on inicialnumpre.v59_inicial = termoini.inicial    \n';
	  sSqlRetorno := sSqlRetorno || '        inner join inicialcert        on termoini.inicial    = inicialcert.v51_inicial   \n';
	  sSqlRetorno := sSqlRetorno || '        inner join certter            on certter.v14_certid  = inicialcert.v51_certidao  \n';
    sSqlRetorno := sSqlRetorno || '        inner join termo termo_origem on termo_origem.v07_parcel = certter.v14_parcel    \n';
    sSqlRetorno := sSqlRetorno || '        inner join arreold 	         on arreold.k00_numpre	= termo_origem.v07_numpre   \n';
    sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento ||                                        '\n';
    sSqlRetorno := sSqlRetorno || '  order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit                      \n';

  elsif iTipo = 4 then -- parcelamento de diveros

    perform fc_debug('Tipo de Parcelamento 4 - termodiver: '                                           ,lRaise,false,false);
    perform fc_debug('Sql busca os dados da termo, termodiver, diversos e arreold'                     ,lRaise,false,false);
    perform fc_debug('Valor corrigido varia de acordo com a Regra de Anulacao explicado anteriormente' ,lRaise,false,false);

    sSqlRetorno :=                '   select '||sCamposSql ||                                                        '\n';
    sSqlRetorno := sSqlRetorno || '   from termo                                                                      \n';
    sSqlRetorno := sSqlRetorno || '        inner join termodiver on termo.v07_parcel       = termodiver.dv10_parcel   \n';
    sSqlRetorno := sSqlRetorno || '        inner join diversos   on diversos.dv05_coddiver = termodiver.dv10_coddiver \n';
    sSqlRetorno := sSqlRetorno || '        inner join arreold    on arreold.k00_numpre 	   = diversos.dv05_numpre     \n';
    sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento ||                                  '\n';
    sSqlRetorno := sSqlRetorno || '  order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit                \n';

  elsif iTipo = 5 then -- parcelamento de contribuicao de melhorias

    perform fc_debug('Tipo de Parcelamento 2 - termocontrib: '                                                             ,lRaise,false,false);
    perform fc_debug('Sql busca os dados da termo, termocontrib, contricalc e arreold, '                                   ,lRaise,false,false);
    perform fc_debug('havendo um left com a divold apenas para garantir que nao virao registros que sao oriundos da divida',lRaise,false,false);
    perform fc_debug('Valor corrigido varia de acordo com a Regra de Anulacao explicado anteriormente'                     ,lRaise,false,false);

    sSqlRetorno :=                '   select '||sCamposSql ||                                                                         '\n';
    sSqlRetorno := sSqlRetorno || '   from termo                                                                                       \n';
    sSqlRetorno := sSqlRetorno || '        inner join termocontrib on termo.v07_parcel          = termocontrib.parcel                  \n';
    sSqlRetorno := sSqlRetorno || '        inner join contricalc   on contricalc.d09_sequencial = termocontrib.contricalc              \n';
    sSqlRetorno := sSqlRetorno || '        inner join arreold	  	 on arreold.k00_numpre        = contricalc.d09_numpre                \n';
		-- left com divold porque o numpre da contricalc pode estar na arreold tanto por parcelamento como por importacao de divida mais como o que interessa e so os
		-- registros referente ao parcelamento dou um left com divold para garantir que nao vira registros que sao oriundos da divida
	  sSqlRetorno := sSqlRetorno || '        left  join divold       on arreold.k00_numpre        = divold.k10_numpre                    \n';
    sSqlRetorno := sSqlRetorno || '                               and arreold.k00_numpar        = divold.k10_numpar                    \n';
    sSqlRetorno := sSqlRetorno || '                               and arreold.k00_receit        = divold.k10_receita                   \n';
    sSqlRetorno := sSqlRetorno || '   where ( divold.k10_numpre is null and divold.k10_numpar is null and divold.k10_receita is null ) \n';
    sSqlRetorno := sSqlRetorno || '     and termo.v07_parcel = ' || iParcelamento ||                                                  '\n';
    sSqlRetorno := sSqlRetorno || '   order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit                                \n';

  end if;

  if iTipoAnulacao <> 1 then

    perform fc_debug('Tipo de Anulacao '||iTipoAnulacao||', retornamos o sql com calculo do juro e multa em cima do valor corrigido'  ,lRaise,false,false);

    sSql = sSqlRetorno;

    if iTipo = 3 then -- adiciona o numero da inicial aos campos da query quando parcelamento de inicial
      sCampoInicial := ' , inicial \n';
    end if;

    sSqlRetorno := '';
    sSqlRetorno := sSqlRetorno||'select distinct        \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_numcgm,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_dtoper,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_receit,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_hist,     \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_valor,    \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_dtvenc,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_numpre,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_numpar,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_numtot,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_numdig,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_tipo,     \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_tipojm,   \n';
    sSqlRetorno := sSqlRetorno||'       x.corrigido,    \n';
    sSqlRetorno := sSqlRetorno||'       ( x.corrigido * coalesce( fc_juros(x.k00_receit,x.k00_dtvenc,\''||dDataCorrecao||'\',\''||dDataCorrecao||'\',false,'||iAnoUsu||'),0)) as juros, \n';
    sSqlRetorno := sSqlRetorno||'       ( x.corrigido * coalesce( fc_multa(x.k00_receit,x.k00_dtvenc,\''||dDataCorrecao||'\',x.k00_dtoper,'||iAnoUsu||'),0)) as multa                   \n';
    sSqlRetorno := sSqlRetorno||'       '||sCampoInicial||' \n';
    sSqlRetorno := sSqlRetorno||'  from ( '||sSql||' ) as x \n';
    sSqlRetorno := sSqlRetorno||' order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit\n';

  end if;

  return sSqlRetorno;

end;
$$  language 'plpgsql';
        ";

        $this->execute($sSql1);
        $this->execute($sSql2);
    }

    public function down()
    {
        $sSql1 = "
        set check_function_bodies to on;
create or replace function fc_excluiparcelamentopag(integer, integer, integer, integer)
returns varchar(100)
as $$
declare
  
  iParcelamento       alias for $1; -- parcelamento
  iTipoParcelamento   alias for $2; -- tipo
    -- 1 = divida ativa
    -- 2 = reparcelamento
    -- 3 = parcelamento de inicial
    -- 4 = parcelamento de diversos
    -- 5 = parcelamento de contribuicao de melhorias 
  iNumpre       	  	alias for $3; -- numpre
  iCodUsuario    	   	alias for $4; -- login
  
  iQuantidade			    integer;
  iIdInicialMov		  	integer;
  iSeqTermoSimulaReg	integer;
  iSeqTermoSimula    	integer;
  iAnoUsu            	integer default 0;
  iTipoAnulacao      	integer default 0;
  iInstit            	integer default 0;

  nValorPago				  numeric(15,2) default 0;
  nValorDevido			  numeric(15,2) default 0;
  nTotalParcelado		  numeric(15,2) default 0;
  nTotalOrigem			  numeric(15,2) default 0;
  nPercAbatimento     numeric(15,2) default 0;
  nTotal      			  numeric(15,2) default 0;
  nValorParcela			  numeric(15,2) default 0;
  nValorTotalParcela  numeric(15,2) default 0;
  nValorAbatido       numeric(15,2) default 0;
  nSaldoAbater        numeric(15,2) default 0;
  nDifArredondamento  numeric(15,2) default 0;

  dDataHoje           date;
  dDataVencParc       date;
  dDataOperParc       date;

  sSql						    text;
  sSqlInicial 		  	text;
  sMensagemErro		  	text;

  sCampoValor         varchar;
  
  rOrigens	          record;
  rIniciaMov       	  record;
  
  lErroOperacao  		  boolean default false;
  lRaise						  boolean default false;
  lParcial					  boolean default false;
  
begin
    
  lRaise := ( case when fc_getsession('DB_debugon') is null then false else true end );
  
  iSeqTermoSimula := nextval('termosimula_v21_sequencial_seq');
  dDataHoje := cast( (select fc_getsession('DB_datausu')) as date);
  if dDataHoje is null then
    raise exception 'ERRO : Variavel de sessao [DB_datausu] nao encontrada.';
  end if;
  
  iAnoUsu := cast( (select fc_getsession('DB_anousu')) as integer);
  if iAnoUsu is null then
    raise exception 'ERRO : Variavel de sessao [DB_anousu] nao encontrada.';
  end if;
  
  iInstit := cast( (select fc_getsession('DB_instit')) as integer);
  if iInstit is null then
    raise exception 'ERRO : Variavel de sessao [DB_instit] nao encontrada.';
  end if;
      
  perform fc_debug(' Variaveis de sessao validadas...',lRaise,false,false);
  
  select k40_tipoanulacao
    into iTipoAnulacao
    from termo 
   inner join cadtipoparc on cadtipoparc.k40_codigo = termo.v07_desconto  
   where termo.v07_parcel = iParcelamento; 
   
  perform fc_debug('Regra de Anulacao da Regra de Parcelamento (cadtipoparc:k40_tipoanulacao): '||iTipoAnulacao,lRaise,false,false);    
  perform fc_debug('',lRaise,false,false);
  
  perform fc_debug('Exercicio : '||coalesce(iAnoUsu,0)||' Instituicao : '||coalesce(iInstit,0)||'',lRaise,false,false);
  perform fc_debug('',lRaise,false,false);
  
  -- calculando valor pago
  perform fc_debug('--------------------------------------------------------------------------------------- ',lRaise,false,false);  
  perform fc_debug('Calculando Valor Pago (funcao fc_parc_getvalorpago()): '                                 ,lRaise,false,false); 
  perform fc_debug(''                                                                                        ,lRaise,false,false);
  select rnvalor::numeric,
         rsmensagemerro,
         rlerro
    into nValorPago,
         sMensagemErro,
         lErroOperacao
    from fc_parc_getvalorpago(iParcelamento,dDataHoje,iAnoUsu);
  if lErroOperacao then      
    perform fc_debug('1 - ERRO :'||coalesce(sMensagemErro,''),lRaise,false,false);
    return '1 - ERRO :'||coalesce(sMensagemErro,'');
  end if;
  perform fc_debug(''                                                                                        ,lRaise,false,false);  
  perform fc_debug('Valor Pago: '||nValorPago                                                                ,lRaise,false,false);
  perform fc_debug('--------------------------------------------------------------------------------------- ',lRaise,false,false);  
  perform fc_debug('',lRaise,false,false);
  
  
  -- calculando valor devido
  perform fc_debug('--------------------------------------------------------------------------------------- ',lRaise,false,false);  
  perform fc_debug('Calculando Valor Devido (funcao fc_parc_getvalordevido()):'                              ,lRaise,false,false);
  perform fc_debug(''                                                                                        ,lRaise,false,false);
  select rnvalor::numeric,
         rsmensagemerro,
         rlerro
    into nValorDevido,
         sMensagemErro,
         lErroOperacao
    from fc_parc_getvalordevido(iParcelamento,dDataHoje,iAnoUsu,iTipoParcelamento);
  if lErroOperacao then
    perform fc_debug('2 - ERRO :'||coalesce(sMensagemErro,''),lRaise,false,false);
    return '2 - ERRO :'||coalesce(sMensagemErro,'');
  end if;
 perform fc_debug(''                                                                                           ,lRaise,false,false);  
 perform fc_debug('Valor Devido :'||coalesce(nValorDevido,0)                                                   ,lRaise,false,false);
 perform fc_debug('--------------------------------------------------------------------------------------- '   ,lRaise,false,false); 
 perform fc_debug('',lRaise,false,false);
 
  --
  -- Funcao fc_parc_getselectorigens(iParcelamento,iTipoParcelamento) retorna sql com as origens do parcelamento(arreold)
  --
  perform fc_debug('--------------------------------------------------------------------------------------- '  ,lRaise,false,false);  
  perform fc_debug('Verificando Origens do Parcelamento: (funcao fc_parc_getselectorigens())'                  ,lRaise,false,false);
  perform fc_debug('Desta funcao e retornado o sql que sera a base da busca das origens do parcelamento'       ,lRaise,false,false);  
  
  sSql := fc_parc_getselectorigens(iParcelamento,iTipoParcelamento);
  
  perform fc_debug(''                                                                                          ,lRaise,false,false);
  perform fc_debug('---------------------------------------------------------------------------------------  ' ,lRaise,false,false);
  
  perform fc_debug(''                                                                                          ,lRaise,false,false);
  perform fc_debug('Verificando valor a ser utilizado como Total Parcelado de acordo com a regra de anulacao:' ,lRaise,false,false);
  perform fc_debug('Regra de Anulacao 1 ............: nTotalParcelado := ( nValorPago + nValorDevido )'        ,lRaise,false,false);
  perform fc_debug('Regra de Anulacao 2 ............: nTotalParcelado := v07_valor'                            ,lRaise,false,false);
  perform fc_debug('Regra de Anulacao 3 ............: nTotalParcelado := nValorDevido'                         ,lRaise,false,false);  
  perform fc_debug('Regra de Anulacao Utilizada ....: '||iTipoAnulacao                                         ,lRaise,false,false);  
  perform fc_debug(''                                                                                          ,lRaise,false,false);
  
  if iTipoAnulacao = 1 then
      -- somando valor pago + devido
    nTotalParcelado := ( nValorPago + nValorDevido );
    
  elsif iTipoAnulacao = 3 then
  
    nTotalParcelado := nValorDevido;

  elsif iTipoAnulacao = 2 then
  
    select v07_valor
      into nTotalParcelado 
      from termo 
     where v07_parcel = iParcelamento; 
    
  end if;
  
  perform fc_debug('nValorDevido .....: '||nValorDevido                                                        ,lRaise,false,false);
  perform fc_debug('nValorPago .......: '||nValorPago                                                          ,lRaise,false,false);
  perform fc_debug('nTotalParcelado ..: '||nTotalParcelado                                                     ,lRaise,false,false);
  perform fc_debug(''                                                                                          ,lRaise,false,false);  
  
  execute 'select count(*) from ('||coalesce(sSql,'')||') as quantidade_origem' into iQuantidade ;
  perform fc_debug('Quantidade de registros da origem : '||coalesce(iQuantidade,0),lRaise,false,false); 

  if iQuantidade = 0 or iQuantidade is null then
    return '3 - ORIGEM DO PARCELAMENTO INCONSISTENTE! TIPO : '||coalesce(iTipoParcelamento,0);
  end if;

  -- verificamos os inflatores
  perform fc_debug('Verificando Inflatores das Origens:',lRaise,false,false);
  perform fc_debug(' - Nao pode haver valor corrigido do debito menor que zero se existir tal registro este vencimento não possui inflator',lRaise,false,false);
  
  execute 'select ''Receita: ''||k00_receit||'' Vencto: ''||k00_dtvenc from ('||coalesce(sSql,'')||') as x where corrigido < 0' into sMensagemErro ;
  if sMensagemErro is not null then
    return '4 - ENCONTRADOS REGISTROS SEM INFLATORES CONFIGURADOS PARA A DATA DE VENCIMENTO '||sMensagemErro||' - TIPO: '||coalesce(iTipoParcelamento,0);
  end if;
  perform fc_debug('',lRaise,false,false);

  
  if iTipoAnulacao = 2 then
    -- sCampoValor := 'corrigido+juros+multa'; -- comentado esta linha conforme conversa com Evandro, pois o valor para calculo deve ser apenas o valor corrigido
    sCampoValor := 'corrigido';
  else
    sCampoValor := 'k00_valor';
  end if;

  perform fc_debug(''                                                                     ,lRaise,false,false);
  perform fc_debug('Buscando saldo a abater:'                                             ,lRaise,false,false);
  perform fc_debug('Soma do campo '||sCampoValor||' do Sql das origens'                   ,lRaise,false,false);
  perform fc_debug('*Regra de Anulacao 1 e 3 utilizam o campo k00_valor do arreold'                   ,lRaise,false,false);
  perform fc_debug('*Regra de Anulacao 2 utiliza o corrigido (fc_corre sobre o k00_valor do arreold)' ,lRaise,false,false);
  
  execute 'select sum('||coalesce(sCampoValor,'')||') from ('||coalesce(sSql,'')||') as valor' 
     into nSaldoAbater;

  nPercAbatimento := ( nValorPago / nTotalParcelado * 100 ) ;
  
  perform fc_debug(' ---------------------------------------------------------------------------------------------- '                  ,lRaise,false,false); 
  perform fc_debug(' Percent. Pago ou Percent. de Abatimento ( nValorPago / nTotalParcelado * 100 ) ..: '||coalesce(nPercAbatimento,0) ,lRaise,false,false);
  perform fc_debug(' VALOR PAGO ......................................................................: '||coalesce(nValorPago,0)      ,lRaise,false,false);
  perform fc_debug(' VALOR TOTAL PARCELADO ...........................................................: '||coalesce(nTotalParcelado,0) ,lRaise,false,false);
  perform fc_debug(' SALDO A ABATER ..................................................................: '||coalesce(nSaldoAbater,0)    ,lRaise,false,false);
  perform fc_debug(' '                                                                                                                 ,lRaise,false,false);
  perform fc_debug('Recalculando Saldo a Abater:'                                           ,lRaise,false,false);
  perform fc_debug('Regra 1 ......: nSaldoAbater = ( nSaldoAbater / 100 ) * nPercAbatimento',lRaise,false,false);
  perform fc_debug('Regra 2 e 3 ..: nSaldoAbater = nValorPago '                             ,lRaise,false,false);
  perform fc_debug('Regra de Anulacao Utilizada ....: '||iTipoAnulacao                                         ,lRaise,false,false);  
  
  if iTipoAnulacao = 2 or iTipoAnulacao = 3 then
    nSaldoAbater    := nValorPago ;
  else
   
    perform fc_debug('nSaldoAbater = ( ( nSaldoAbater / 100 ) * nPercAbatimento ) = ( ( '||nSaldoAbater||' / 100 ) * '||nPercAbatimento||' ) = '||( ( nSaldoAbater / 100 ) * nPercAbatimento ) ,lRaise,false,false);
    nSaldoAbater    := round ( ( ( nSaldoAbater / 100 ) * nPercAbatimento ), 2) ;
  end if;
  
  perform fc_debug(' '                                                                                             ,lRaise,false,false);
  perform fc_debug('Saldo a Abater ou Valor a Abater recalculado : '||coalesce(nSaldoAbater,0)                       ,lRaise,false,false);
  perform fc_debug(' '                                                                                               ,lRaise,false,false);
  perform fc_debug(' ---------------------------------------------------------------------------------------------- ',lRaise,false,false); 
  
  perform fc_debug(' '                         ,lRaise,false,false);
  perform fc_debug('Processando simulacao ...' ,lRaise,false,false);  
  perform fc_debug(' '                         ,lRaise,false,false);

  perform fc_debug(' '                                                                                                                            ,lRaise,false,false);  
  perform fc_debug(' 1 - D E S A T I V A N D O   S I M U L A C A O   D E   A N U L A C A O   D E   P A R C E L A M E N T O   A N T E R I O R E S' ,lRaise,false,false);
  perform fc_debug(' '                                                                                                                            ,lRaise,false,false);  
  update termosimula 
     set v21_ativo = false 
   where v21_parcel = iParcelamento 
     and v21_data  <= dDataHoje;
     
  --
  -- Verificar as variaveis do insert abaixo
  --
  perform fc_debug(' '                                                                        ,lRaise,false,false);  
  perform fc_debug(' 2 - I N S E R I N D O   R E G I S T R O S   N A   T E R M O S I M U L A' ,lRaise,false,false);
  perform fc_debug(' '                                                                        ,lRaise,false,false);  
  insert into termosimula ( v21_sequencial,
                            v21_parcel,    
                            v21_usuario,   
                            v21_data,      
                            v21_hora,      
                            v21_validade,  
                            v21_tipo,
                            v21_percretorno,
                            v21_valordevido,
                            v21_valorpago,
                            v21_formaanulacao,
                            v21_ativo)
                   values ( iSeqTermoSimula,
                            iParcelamento,
                            1,
                            dDataHoje,
                            '',
                            dDataHoje,
                            1,
                            round((100 - nPercAbatimento),2), 
                            nValorDevido,
                            nValorPago,
                            iTipoAnulacao,
                            true );
 
  --
	-- Percorrendo os registros da origem do parcelamento
  --
  perform fc_debug(' '                                                    ,lRaise,false,false);  
  perform fc_debug(' 3 - P E R C O R R E N D O   R E G I S T R O S   D A   O R I G E M   D O   P A R C E L A M E N T O',lRaise,false,false);
  perform fc_debug(' '                                                    ,lRaise,false,false);  
  
  for rOrigens in execute sSql loop 

    perform fc_debug('   ==> INICIO DO PROCESSAMENTO DO NUMPRE: '||rOrigens.k00_numpre||', PARCELA:'||rOrigens.k00_numpar||', RECEITA: '||rOrigens.k00_receit||', VALOR : '||rOrigens.k00_valor,lRaise,false,false);
    perform fc_debug(''                                    ,lRaise,false,false);    
    perform fc_debug('     Calculando valor da parcela...' ,lRaise,false,false);
    
    nTotalOrigem          := nTotalOrigem + rOrigens.k00_valor;
    if iTipoAnulacao = 2 then
    
      perform fc_debug('       Regra de Anulacao 2: Corrigido+Juro+Multa',lRaise,false,false);
      nValorTotalParcela := ( rOrigens.corrigido + rOrigens.juros + rOrigens.multa );
      
    elsif iTipoAnulacao = 3 then
    
      perform fc_debug('       Regra de Anulacao 3: Corrigido+Juro+Multa',lRaise,false,false);
      nValorTotalParcela := ( rOrigens.corrigido + rOrigens.juros + rOrigens.multa );
      
    elsif iTipoAnulacao = 1 then
    
      nValorTotalParcela := ( rOrigens.k00_valor );
      perform fc_debug('       (Regra de Anulacao Padrao): arreold.k00_valor',lRaise,false,false);
      
    end if;
    
    perform fc_debug('     Fim do calculo da parcela ',lRaise,false,false);    
    perform fc_debug('     Valor da Parcela: '||nValorTotalParcela,lRaise,false,false);
    perform fc_debug('',lRaise,false,false);
    perform fc_debug('     - Chamando funcao fc_processa_tipo_anulacao() para processar a regra de retorno das origens do parcelamentos...',lRaise,false,false);
    perform fc_debug('       Neste momento eh calculado o valor de retorno dos debitos de origem (arrecad.k00_valor)',lRaise,false,false);
    
    select rnValorParcela, 
           rnValorAbatido, 
           rnSaldoAbater, 
           rlErro, 
           rsMensagem,
           rlParcial
      into nValorParcela, 
           nValorAbatido, 
           nSaldoAbater, 
           lErroOperacao, 
           sMensagemErro,
           lParcial
      from fc_processa_tipo_anulacao( iTipoAnulacao,      -- Tipo de anulacao (campo k03_tipoanuparc)
                                      round(rOrigens.k00_valor,2), -- Valor historico da parcela
                                      round(nValorTotalParcela,2), -- Valor da parcela para comparacao de acordo com a regra
                                      round(nSaldoAbater,2),       -- Saldo a ser abatido do valor da parcela
                                      round(nValorPago,2),         
                                      round(nValorDevido,2),
                                      round(nTotalParcelado,2) );

    dDataVencParc := rOrigens.k00_dtvenc;
    dDataOperParc := rOrigens.k00_dtoper;
    
    iSeqTermoSimulaReg := nextval('termosimulareg_v23_sequencial_seq');

    perform fc_debug('     Inserindo registros na termosimulareg' ,lRaise,false,false);
    insert into termosimulareg ( v23_sequencial, 
                                 v23_termosimula,
                                 v23_numpre,     
                                 v23_numpar,     
                                 v23_numcgm,     
                                 v23_dtoper,     
                                 v23_dtvenc,
                                 v23_receit,     
                                 v23_hist,       
                                 v23_valor,      
                                 v23_numtot,     
                                 v23_numdig,     
                                 v23_tipo,       
                                 v23_tipojm,     
                                 v23_vlrcor,     
                                 v23_vlrjur,     
                                 v23_vlrmul,     
                                 v23_vlrdes,     
                                 v23_vlrabatido, 
                                 v23_saldoabater )
                        values ( iSeqTermoSimulaReg,
                                 iSeqTermoSimula,
                                 rOrigens.k00_numpre,
                                 rOrigens.k00_numpar,
                                 rOrigens.k00_numcgm,
                                 dDataOperParc,
                                 dDataVencParc,
                                 rOrigens.k00_receit,
                                 rOrigens.k00_hist,
                                 round(rOrigens.k00_valor,2),
                                 rOrigens.k00_numtot,
                                 rOrigens.k00_numdig,
                                 rOrigens.k00_tipo,
                                 rOrigens.k00_tipojm,
                                 round(rOrigens.corrigido,2),
                                 round(rOrigens.juros,2),
                                 round(rOrigens.multa,2),
                                 0, -- desconto
                                 round(nValorAbatido,2),
                                 round(nSaldoAbater,2) );

    nTotal := ( nTotal + nValorParcela );

    perform fc_debug(''                                                                                                                                                                     ,lRaise,false,false);    
    perform fc_debug('   ==> FIM DO PROCESSAMENTO DO NUMPRE: '||rOrigens.k00_numpre||', PARCELA:'||rOrigens.k00_numpar||', RECEITA: '||rOrigens.k00_receit||', VALOR : '||rOrigens.k00_valor,lRaise,false,false);
    
  end loop;

  perform fc_debug('',lRaise,false,false);
  perform fc_debug('Acertando a diferenca dos centavos no ultimo registro (arredondamento)' ,lRaise,false,false);
  update termosimulareg 
     set v23_vlrabatido  = ( v23_vlrabatido + nSaldoAbater ) ,
         v23_saldoabater = ( v23_saldoabater - nSaldoAbater )
   where v23_sequencial = iSeqTermoSimulaReg;

  perform fc_debug(' ---------------------------------------------------------------------------------------------- ',lRaise,false,false);  
  perform fc_debug(' TOTAL PAGO (1)        : '||coalesce(nValorPago,0)||' - DEVIDO: '||coalesce(nValorDevido,0)||' - TOTAL DO PARCELAMENTO: '||coalesce(nTotalParcelado,0)||' - nTotalOrigem: '||coalesce(nTotalOrigem,0),lRaise,false,false); 
  perform fc_debug(' ---------------------------------------------------------------------------------------------- ',lRaise,false,false);

  return 'ok';

end;
$$ language 'plpgsql';

        ";

        $sSql2 = "
        create or replace function fc_parc_getselectorigens_atjuros(integer,integer,integer) returns varchar as
$$
declare

  iParcelamento      alias for $1;
  iTipo              alias for $2;
  iTipoAnulacao      alias for $3;

  iAnoUsu            integer default 0;

  dDataCorrecao      date;

  sCamposSql         varchar default '';
  sSqlRetorno        varchar default '';
  sSql               varchar default '';
  sCampoInicial      varchar default '';

  lRaise             boolean default false;


begin

  lRaise := ( case when fc_getsession('DB_debugon') is null then false else true end );

  iAnoUsu := cast( (select fc_getsession('DB_anousu')) as integer);
  if iAnoUsu is null then
    raise exception 'ERRO : Variavel de sessao [DB_anousu] nao encontrada.';
  end if;

  dDataCorrecao := cast( (select fc_getsession('DB_datausu')) as date);
  if dDataCorrecao is null then
    raise exception 'ERRO : Variavel de sessao [DB_datausu] nao encontrada.';
  end if;

  perform fc_debug(''                                                      ,lRaise,false,false);
  perform fc_debug('Processando funcao fc_parc_getselectorigens_atjuros...' ,lRaise,false,false);

  sCamposSql := ' distinct
                  k00_numcgm,
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
                  k00_tipojm,
                  termo.v07_dtlanc,';

  perform fc_debug('Verificamos a Regra de Anulacao:'                                                                    ,lRaise,false,false);
  perform fc_debug('Regra de Anulacao da Regra de Parcelamento (cadtipoparc:k40_tipoanulacao): '||iTipoAnulacao          ,lRaise,false,false);
  perform fc_debug('Regra de Anulacao 1 ......: Utilizamos o valor do arreold (campo: k00_valor) como corrigido sem aplicar correcao',lRaise,false,false);
  perform fc_debug('Regra de Anulacao 2 e 3 ..: Aplicamos correcao (fc_corre) sobre o valor do arreold (campo: k00_valor)'           ,lRaise,false,false);

  if iTipoAnulacao = 1 then

    sCamposSql := sCamposSql || ' k00_valor as corrigido, \n';
    sCamposSql := sCamposSql || ' 0 as juros,             \n';
    sCamposSql := sCamposSql || ' 0 as multa              \n';

  else

    sCamposSql := sCamposSql || ' fc_corre(arreold.k00_receit,arreold.k00_dtvenc,arreold.k00_valor,\''||dDataCorrecao||'\','||iAnoUsu||',\''||dDataCorrecao||'\') as corrigido, \n';
    sCamposSql := sCamposSql || ' 0 as juros, \n';
    sCamposSql := sCamposSql || ' 0 as multa  \n';

  end if;

  if iTipo = 1 then

    perform fc_debug('Tipo de Parcelamento 1 - termodiv: '                                             ,lRaise,false,false);
    perform fc_debug('Sql busca os dados da termodiv, divida e arreold'                                ,lRaise,false,false);
    perform fc_debug('Valor corrigido varia de acordo com a Regra de Anulacao explicado anteriormente' ,lRaise,false,false);

    sSqlRetorno :=                ' select '||sCamposSql||                                                                                          '\n';
    sSqlRetorno := sSqlRetorno || '   from termo                                                                                                     \n';
    sSqlRetorno := sSqlRetorno || '        inner join termodiv  on termo.v07_parcel 	= termodiv.parcel                                              \n';
    sSqlRetorno := sSqlRetorno || '        inner join divida    on termodiv.coddiv   	= divida.v01_coddiv                                            \n';
    sSqlRetorno := sSqlRetorno || '        inner join arreold   on arreold.k00_numpre	= divida.v01_numpre and arreold.k00_numpar = divida.v01_numpar \n';
    sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento ||                                                                 '\n';
    sSqlRetorno := sSqlRetorno || '  order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit                                               \n';

  elsif iTipo = 2 then

    perform fc_debug('Tipo de Parcelamento 2 - termoreparc: ' ,lRaise,false,false);
    perform fc_debug('Sql busca os dados da termoreparc, termo, tabelas de origem do termo (termodiv, termoini, termodiver e termocontrib), arreold etc ' ,lRaise,false,false);
    perform fc_debug('Valor corrigido varia de acordo com a Regra de Anulacao explicado anteriormente' ,lRaise,false,false);

    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||'                                                         \n';
    sSqlRetorno := sSqlRetorno || '     from termoreparc                                                              \n';
    sSqlRetorno := sSqlRetorno || '          inner join termo on v07_parcel            = termoreparc.v08_parcelorigem \n';
    sSqlRetorno := sSqlRetorno || '          inner join arreold on arreold.k00_numpre  = termo.v07_numpre             \n';
    sSqlRetorno := sSqlRetorno || '   where termoreparc.v08_parcel = ' || iParcelamento ||                           '\n';

    sSqlRetorno := sSqlRetorno || ' union all \n';	-- tras os reparcelamentos de divida

    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||'                                                         \n';
    sSqlRetorno := sSqlRetorno || '     from termoreparc                                                              \n';
    sSqlRetorno := sSqlRetorno || '          inner join termo     on v07_parcel         = termoreparc.v08_parcel      \n';
    sSqlRetorno := sSqlRetorno || '          inner join termodiv  on termo.v07_parcel 	= termodiv.parcel             \n';
    sSqlRetorno := sSqlRetorno || '          inner join divida  	on termodiv.coddiv   	= divida.v01_coddiv           \n';
    sSqlRetorno := sSqlRetorno || '          inner join arreold 	on arreold.k00_numpre	= divida.v01_numpre           \n';
    sSqlRetorno := sSqlRetorno || '                              and arreold.k00_numpar = divida.v01_numpar           \n';
    sSqlRetorno := sSqlRetorno || '   where termoreparc.v08_parcel = ' || iParcelamento ||                           '\n';

  	sSqlRetorno := sSqlRetorno || ' union all \n';	-- tras os reparcelamentos do foro

    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||                                                               '\n';
    sSqlRetorno := sSqlRetorno || '     from termoreparc                                                                    \n';
    sSqlRetorno := sSqlRetorno || '          inner join termo         on v07_parcel                = termoreparc.v08_parcel \n';
    sSqlRetorno := sSqlRetorno || '          inner join termoini      on termo.v07_parcel 	       = termoini.parcel        \n';
    sSqlRetorno := sSqlRetorno || '          inner join inicialnumpre on inicialnumpre.v59_inicial = termoini.inicial       \n';
    sSqlRetorno := sSqlRetorno || '          inner join divida 	      on inicialnumpre.v59_numpre  = divida.v01_numpre      \n';
    sSqlRetorno := sSqlRetorno || '          inner join arreold 	    on arreold.k00_numpre        = divida.v01_numpre      \n';
	  sSqlRetorno := sSqlRetorno || '                                  and arreold.k00_numpar        = divida.v01_numpar      \n';
    sSqlRetorno := sSqlRetorno || '   where termoreparc.v08_parcel = ' || iParcelamento;

	  sSqlRetorno := sSqlRetorno || ' union all \n';	-- tras os reparcelamentos de diversos

    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||                                                              '\n';
    sSqlRetorno := sSqlRetorno || '     from termoreparc                                                                   \n';
    sSqlRetorno := sSqlRetorno || '          inner join termo         on v07_parcel             = termoreparc.v08_parcel   \n';
    sSqlRetorno := sSqlRetorno || '          inner join termodiver    on termo.v07_parcel 	 	  = termodiver.dv10_parcel   \n';
    sSqlRetorno := sSqlRetorno || '          inner join diversos      on diversos.dv05_coddiver = termodiver.dv10_coddiver \n';
    sSqlRetorno := sSqlRetorno || '          inner join arreold 	    on arreold.k00_numpre     = diversos.dv05_numpre     \n';
    sSqlRetorno := sSqlRetorno || '   where termoreparc.v08_parcel = ' || iParcelamento ||                                '\n';

	  sSqlRetorno := sSqlRetorno || ' union all \n';	-- tras os reparcelamentos de contribuicao de melhorias

    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||'                                                                          \n';
    sSqlRetorno := sSqlRetorno || '     from termoreparc                                                                               \n';
    sSqlRetorno := sSqlRetorno || '          inner join termo         on v07_parcel                = termoreparc.v08_parcel            \n';
    sSqlRetorno := sSqlRetorno || '          inner join termocontrib  on termo.v07_parcel          = termocontrib.parcel               \n';
    sSqlRetorno := sSqlRetorno || '          inner join contricalc    on contricalc.d09_sequencial = termocontrib.contricalc           \n';
    sSqlRetorno := sSqlRetorno || '          inner join arreold 	     on arreold.k00_numpre        = contricalc.d09_numpre            \n';
	  sSqlRetorno := sSqlRetorno || '          left  join divold  	     on arreold.k00_numpre        = divold.k10_numpre                \n';
    sSqlRetorno := sSqlRetorno || '                                  and arreold.k00_numpar        = divold.k10_numpar                 \n';
	  sSqlRetorno := sSqlRetorno || '                                  and arreold.k00_receit        = divold.k10_receita                \n';
    sSqlRetorno := sSqlRetorno || '   where ( divold.k10_numpre is null and divold.k10_numpar is null and divold.k10_receita is null ) \n';
	  sSqlRetorno := sSqlRetorno || '     and termoreparc.v08_parcel = ' || iParcelamento ||                                            '\n';
    sSqlRetorno := sSqlRetorno || '   order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit                                \n';

  elsif iTipo = 3 then  -- parcelamento de inicial

    perform fc_debug('Tipo de Parcelamento 3 - termoini: '                                                                                      ,lRaise,false,false);
    perform fc_debug('Sql busca os dados da termo, termoini, inicialnumpre, inicialcert, certdiv, divida, arreold, arreoldcalc, certter, termo' ,lRaise,false,false);
    perform fc_debug('Valor corrigido varia de acordo com a Regra de Anulacao explicado anteriormente'                                          ,lRaise,false,false);

    sSqlRetorno :=                '  select '||sCamposSql||', inicial                                                       \n';
    sSqlRetorno := sSqlRetorno || '   from termo                                                                            \n';
    sSqlRetorno := sSqlRetorno || '        inner join termoini    	on termo.v07_parcel 	       = termoini.parcel          \n';
    sSqlRetorno := sSqlRetorno || '        inner join inicialnumpre on inicialnumpre.v59_inicial = termoini.inicial         \n';
  	sSqlRetorno := sSqlRetorno || '        inner join inicialcert   on termoini.inicial          = inicialcert.v51_inicial  \n';
	  sSqlRetorno := sSqlRetorno || '        inner join certdiv       on certdiv.v14_certid        = inicialcert.v51_certidao \n';
    sSqlRetorno := sSqlRetorno || '        inner join divida        on certdiv.v14_coddiv        = divida.v01_coddiv        \n';
    sSqlRetorno := sSqlRetorno || '        inner join arreold 	    on arreold.k00_numpre        = divida.v01_numpre        \n';
    sSqlRetorno := sSqlRetorno || '                               and arreold.k00_numpar         = divida.v01_numpar        \n';
    sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento ||                                        '\n';
	  sSqlRetorno := sSqlRetorno || '  union                                                                                  \n';
    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||', inicial                                                      \n';
    sSqlRetorno := sSqlRetorno || '   from termo                                                                            \n';
    sSqlRetorno := sSqlRetorno || '        inner join termoini    	     on termo.v07_parcel 	  = termoini.parcel           \n';
    sSqlRetorno := sSqlRetorno || '        inner join inicialnumpre      on inicialnumpre.v59_inicial = termoini.inicial    \n';
	  sSqlRetorno := sSqlRetorno || '        inner join inicialcert        on termoini.inicial    = inicialcert.v51_inicial   \n';
	  sSqlRetorno := sSqlRetorno || '        inner join certter            on certter.v14_certid  = inicialcert.v51_certidao  \n';
    sSqlRetorno := sSqlRetorno || '        inner join termo termo_origem on termo_origem.v07_parcel = certter.v14_parcel    \n';
    sSqlRetorno := sSqlRetorno || '        inner join arreold 	         on arreold.k00_numpre	= termo_origem.v07_numpre   \n';
    sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento ||                                        '\n';
    sSqlRetorno := sSqlRetorno || '  order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit                      \n';

  elsif iTipo = 4 then -- parcelamento de diveros

    perform fc_debug('Tipo de Parcelamento 4 - termodiver: '                                           ,lRaise,false,false);
    perform fc_debug('Sql busca os dados da termo, termodiver, diversos e arreold'                     ,lRaise,false,false);
    perform fc_debug('Valor corrigido varia de acordo com a Regra de Anulacao explicado anteriormente' ,lRaise,false,false);

    sSqlRetorno :=                '   select '||sCamposSql ||                                                        '\n';
    sSqlRetorno := sSqlRetorno || '   from termo                                                                      \n';
    sSqlRetorno := sSqlRetorno || '        inner join termodiver on termo.v07_parcel       = termodiver.dv10_parcel   \n';
    sSqlRetorno := sSqlRetorno || '        inner join diversos   on diversos.dv05_coddiver = termodiver.dv10_coddiver \n';
    sSqlRetorno := sSqlRetorno || '        inner join arreold    on arreold.k00_numpre 	   = diversos.dv05_numpre     \n';
    sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento ||                                  '\n';
    sSqlRetorno := sSqlRetorno || '  order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit                \n';

  elsif iTipo = 5 then -- parcelamento de contribuicao de melhorias

    perform fc_debug('Tipo de Parcelamento 2 - termocontrib: '                                                             ,lRaise,false,false);
    perform fc_debug('Sql busca os dados da termo, termocontrib, contricalc e arreold, '                                   ,lRaise,false,false);
    perform fc_debug('havendo um left com a divold apenas para garantir que nao virao registros que sao oriundos da divida',lRaise,false,false);
    perform fc_debug('Valor corrigido varia de acordo com a Regra de Anulacao explicado anteriormente'                     ,lRaise,false,false);

    sSqlRetorno :=                '   select '||sCamposSql ||                                                                         '\n';
    sSqlRetorno := sSqlRetorno || '   from termo                                                                                       \n';
    sSqlRetorno := sSqlRetorno || '        inner join termocontrib on termo.v07_parcel          = termocontrib.parcel                  \n';
    sSqlRetorno := sSqlRetorno || '        inner join contricalc   on contricalc.d09_sequencial = termocontrib.contricalc              \n';
    sSqlRetorno := sSqlRetorno || '        inner join arreold	  	 on arreold.k00_numpre        = contricalc.d09_numpre                \n';
		-- left com divold porque o numpre da contricalc pode estar na arreold tanto por parcelamento como por importacao de divida mais como o que interessa e so os
		-- registros referente ao parcelamento dou um left com divold para garantir que nao vira registros que sao oriundos da divida
	  sSqlRetorno := sSqlRetorno || '        left  join divold       on arreold.k00_numpre        = divold.k10_numpre                    \n';
    sSqlRetorno := sSqlRetorno || '                               and arreold.k00_numpar        = divold.k10_numpar                    \n';
    sSqlRetorno := sSqlRetorno || '                               and arreold.k00_receit        = divold.k10_receita                   \n';
    sSqlRetorno := sSqlRetorno || '   where ( divold.k10_numpre is null and divold.k10_numpar is null and divold.k10_receita is null ) \n';
    sSqlRetorno := sSqlRetorno || '     and termo.v07_parcel = ' || iParcelamento ||                                                  '\n';
    sSqlRetorno := sSqlRetorno || '   order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit                                \n';

  end if;

  if iTipoAnulacao <> 1 then

    perform fc_debug('Tipo de Anulacao '||iTipoAnulacao||', retornamos o sql com calculo do juro e multa em cima do valor corrigido'  ,lRaise,false,false);

    sSql = sSqlRetorno;

    if iTipo = 3 then -- adiciona o numero da inicial aos campos da query quando parcelamento de inicial
      sCampoInicial := ' , inicial \n';
    end if;

    sSqlRetorno := '';
    sSqlRetorno := sSqlRetorno||'select distinct        \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_numcgm,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_dtoper,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_receit,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_hist,     \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_valor,    \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_dtvenc,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_numpre,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_numpar,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_numtot,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_numdig,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_tipo,     \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_tipojm,   \n';
    sSqlRetorno := sSqlRetorno||'       x.corrigido,    \n';
    sSqlRetorno := sSqlRetorno||'       ( x.corrigido * coalesce( fc_juros(x.k00_receit,x.k00_dtvenc,\''||dDataCorrecao||'\',\''||dDataCorrecao||'\',false,'||iAnoUsu||'),0)) as juros, \n';
    sSqlRetorno := sSqlRetorno||'       ( x.corrigido * coalesce( fc_multa(x.k00_receit,x.k00_dtvenc,\''||dDataCorrecao||'\',x.k00_dtoper,'||iAnoUsu||'),0)) as multa                   \n';
    sSqlRetorno := sSqlRetorno||'       '||sCampoInicial||' \n';
    sSqlRetorno := sSqlRetorno||'  from ( '||sSql||' ) as x \n';
    sSqlRetorno := sSqlRetorno||' order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit\n';

  end if;

  return sSqlRetorno;

end;
$$  language 'plpgsql';
        ";

        $this->execute($sSql1);
        $this->execute($sSql2);
    }

}
