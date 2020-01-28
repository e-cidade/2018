<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/JSON.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

switch ( $oParam->exec ) {

  case "getDadosParcelamento":

	  $aSimulacao = array();

	  $oTotal    =  new stdClass();
    $oTotal->valor_total_historico_origem  = 0;
    $oTotal->valor_total_corrigido_origem  = 0;
    $oTotal->valor_total_juros_origem      = 0;
    $oTotal->valor_total_multa_origem      = 0;
    $oTotal->valor_total_desconto_origem   = 0;
    $oTotal->valor_total_geral_origem      = 0;
    $oTotal->valor_parcelas_pagas          = 0;
    $oTotal->valor_parcelas_abertas        = 0;
    $oTotal->perc_abatimento               = 0;
    $oTotal->perc_retorno                  = 0;
    $oTotal->qtd_total_parcelas            = 0;
    $oTotal->qtd_parcelas_pagas            = 0;
    $oTotal->valor_total_corrigido_retorno = 0;
    $oTotal->valor_total_juros_retorno     = 0;
    $oTotal->valor_total_multa_retorno     = 0;
    $oTotal->valor_desconto_retorno        = 0;
    $oTotal->valor_total_geral_retorno     = 0;
    $oTotal->valor_total_historico_retorno = 0;

	  $parcel = $oParam->parcel;

	  $aRetorno = array("aListaSimulacao"=>"",
	                    "iTipoAnulacao"  =>"",
	                    "oTotal"         =>"",
	                    "iStatus"        =>"1",
	                    "sMensagem"      =>"");

	  try {

	     db_inicio_transacao();

	     $sSqlFormaCorrecao  = " select k03_separajurmulparc ";
       $sSqlFormaCorrecao .= "   from numpref              ";
       $sSqlFormaCorrecao .= "  where k03_instit = ".db_getsession("DB_instit");
       $sSqlFormaCorrecao .= "    and k03_anousu = ".db_getsession("DB_anousu");
       $iFormaCorrecao     = db_utils::fieldsMemory(db_query($sSqlFormaCorrecao), 0)->k03_separajurmulparc;

       $sSqlTermo  = "select v07_parcel,                                                           ";
       $sSqlTermo .= "       v07_numpre,                                                           ";
       $sSqlTermo .= "       v07_dtlanc,                                                           ";
       $sSqlTermo .= "       v07_totpar,                                                           ";
       $sSqlTermo .= "       (select count(distinct k00_numpar)                                    ";
       $sSqlTermo .= "          from arrepaga                                                      ";
       $sSqlTermo .= "         where k00_numpre = v07_numpre ) as qtd_parcelas_pagas,              ";
       $sSqlTermo .= "       k40_tipoanulacao                                                      ";
       $sSqlTermo .= "  from termo                                                                 ";
       $sSqlTermo .= "       inner join cadtipoparc on cadtipoparc.k40_codigo = termo.v07_desconto ";
       $sSqlTermo .= " where termo.v07_parcel = {$parcel}";
       $rsTermo    = db_query($sSqlTermo);

       if (pg_num_rows($rsTermo) == 0 ) {
         throw new Exception("Verifique a configuracao dos parametros do modulo arrecadacao.");
	     }

	     $oTermo = db_utils::fieldsMemory($rsTermo,0);
	     $sQueryGeraSimulacao  = "select fc_parc_gera_simulacao_anulacao($parcel) as simulaanula ";
	     $rsGeraSimulacao      = db_query($sQueryGeraSimulacao);
	     if (pg_num_rows($rsGeraSimulacao) == 0 ) {
	       throw new Exception("Erro gerando simulação de anulação do parcelamento {$parcel}");
	     }

	     $oSimulacaoAnula = db_utils::fieldsMemory($rsGeraSimulacao,0);
	     if( substr(trim($oSimulacaoAnula->simulaanula),0,1) != '1') {
	       throw new Exception(substr(trim($oSimulacaoAnula->simulaanula),0));
	     }

	     $sFormulaCalculoSaldo      = " 0 ";
	     $sFormulaCalculoValHistRet = " 0 ";
	     $dDataCorrecaoOrigens      = $oTermo->v07_dtlanc;

       if (isset($oTermo->k40_tipoanulacao) && ( $oTermo->k40_tipoanulacao == 3 || $oTermo->k40_tipoanulacao == 2) ) {


         $sFormulaCalculoSaldo = " v23_valor - ( ( v23_valor * ( (v23_vlrabatido * 100) / ( v23_vlrcor+v23_vlrjur+v23_vlrmul ) ) ) / 100 )";

         if ($oTermo->k40_tipoanulacao == 2) {
           $dDataCorrecaoOrigens = date("Y-m-d", db_getsession("DB_datausu"));
         }

         /*
          *
          * Caso de exemplo para o calculo de retorno do valor histórico:
          *
          *  Parcela X possui o valor Histórico de R$ 24,55, Corrigido R$  26,00, Juros R$ 1,30 e Multa R$ 1,56 e Valor a ser abatido R$ 3,44
          *
          *  O sistema calcula da seguinte forma:
          *    Multiplicamos o valor abatido por 100 (cem) e dividimos pelo valor da total da parcela (R$ 28,86 (26.00+1.30+1.56)): (3,44*100)/28,86 = 11,91.
          *    O resultado é o percentual de abatimento deste débito.
          *
          *    Então calculamos o valor a ser abatido do histórico de retorno multiplicando o valor do histórico pelo percentual de abatimento ((24,55*11,91)/100) = 2,92).
          *    O valor de 2,92 deve ser reduzido do valor Histórico do Débito em questão, logo teremos 24,55 - 2,92 = 21,63.
          *
          *    21.63 é o valor que deve ser consederado como o valor de historico de retorno da parcela.
          *
          */
         $sFormulaCalculoValHistRet = " round((v23_valor - ((v23_valor * ((v23_vlrabatido*100)/(v23_vlrcor+v23_vlrjur+v23_vlrmul)))/100)),2) ";

       } else {

         $sFormulaCalculoSaldo = " ( v23_valor - v23_vlrabatido ) ";

       }

	     $sSql  = "select *,                                                                                                   ";
	     $sSql .= "       (select sum(k00_valor)                                                                               ";
	     $sSql .= "          from arrecad                                                                                      ";
	     $sSql .= "         where k00_numpre = {$oTermo->v07_numpre} ) as valor_parcelas_abertas,                              ";

	     /*
	      *
	      * Informações utilizadas para montar o array de objetos oTotal
	      *
	      */
	     $sSql .= "       round( x.topo_valor_corrigido_origem * (select fc_juros(k02_codigo::integer,                             ";
	     $sSql .= "                                                      v23_dtvenc::date,                                         ";
	     $sSql .= "                                                      '$oTermo->v07_dtlanc'::date,                              ";
	     $sSql .= "                                                      v23_dtoper::date,                                         ";
	     $sSql .= "                                                      false,                                                    ";
	     $sSql .= "                                                      extract( year from '$oTermo->v07_dtlanc'::date)::integer ) ),2) as topo_valor_juros_origem,  ";
       $sSql .= "       round( x.topo_valor_corrigido_origem * (select fc_multa(k02_codigo::integer,                             ";
       $sSql .= "                                                      v23_dtvenc::date,                                         ";
       $sSql .= "                                                      '$oTermo->v07_dtlanc'::date,                              ";
       $sSql .= "                                                      v23_dtoper::date,                                         ";
       $sSql .= "                                                      extract( year from '$oTermo->v07_dtlanc'::date)::integer ) ) ,2) as topo_valor_multa_origem, ";

       /*
        *
        * Informações utilizadas para montar o array de objetos aSimulacao que irá montar a grid das informações das origens
        *
        */
	     $sSql .= "       round( x.grid_valor_corrigido_origem * (select fc_juros(k02_codigo::integer,                             ";
	     $sSql .= "                                                      v23_dtvenc::date,                                         ";
	     $sSql .= "                                                      '{$dDataCorrecaoOrigens}'::date,                          ";
	     $sSql .= "                                                      v23_dtoper::date,                                         ";
	     $sSql .= "                                                      false,                                                    ";
	     $sSql .= "                                                      extract( year from '{$dDataCorrecaoOrigens}'::date)::integer ) ),2) as grid_valor_juros_origem,  ";
       $sSql .= "       round( x.grid_valor_corrigido_origem * (select fc_multa(k02_codigo::integer,                              ";
       $sSql .= "                                                       v23_dtvenc::date,                                         ";
       $sSql .= "                                                       '{$dDataCorrecaoOrigens}'::date,                          ";
       $sSql .= "                                                       v23_dtoper::date,                                         ";
       $sSql .= "                                                       extract( year from '{$dDataCorrecaoOrigens}'::date)::integer ) ) ,2) as grid_valor_multa_origem, ";
	     $sSql .= "       round( x.grid_valor_corrigido_retorno * (select fc_juros(k02_codigo::integer,                             ";
	     $sSql .= "                                                       v23_dtvenc::date,                                         ";
	     $sSql .= "                                                       '".date("Y-m-d",db_getsession("DB_datausu"))."'::date,    ";
	     $sSql .= "                                                       v23_dtoper::date,                                         ";
	     $sSql .= "                                                       false,                                                    ";
	     $sSql .= "                                                       ".db_getsession("DB_anousu")."::integer ) ),2) as grid_valor_juros_retorno, ";
       $sSql .= "       round( x.grid_valor_corrigido_retorno * (select fc_multa(k02_codigo::integer,                             ";
       $sSql .= "                                                       v23_dtvenc::date,                                         ";
       $sSql .= "                                                       '".date("Y-m-d",db_getsession("DB_datausu"))."'::date,    ";
       $sSql .= "                                                       v23_dtoper::date,                                         ";
       $sSql .= "                                                       ".db_getsession("DB_anousu")."::integer ) ) ,2) as grid_valor_multa_retorno  ";
       $sSql .= "  from ( select v23_numpre,                                                                                  ";
		   $sSql .= "                v23_numpar,                                                                                  ";
		   $sSql .= "                v21_sequencial,                                                                              ";
		   $sSql .= "                v21_percretorno,                                                                             ";
	     $sSql .= "                v21_valordevido,                                                                             ";
	     $sSql .= "                coalesce(v21_valorpago,0) as v21_valorpago,                                                  ";
	     $sSql .= "                v21_formaanulacao,                                                                           ";
	     $sSql .= "                k02_codigo,                                                                                  ";
		   $sSql .= "                k02_descr as v23_receit,                                                                     ";
		   $sSql .= "	               v23_dtoper,                                                                                  ";
		   $sSql .= "                v23_dtvenc,                                                                                  ";
		   $sSql .= "     	         v23_valor,                                                                                   ";
		   $sSql .= "	               v23_vlrcor,                                                                                  ";
       $sSql .= "                v23_vlrjur,                                                                                  ";
		   $sSql .= "                v23_vlrmul,                                                                                 ";
		   $sSql .= "                v23_vlrabatido,                                                                              "; /*,v23_saldoabater*/
       $sSql .= "                {$sFormulaCalculoSaldo} as saldo_pagar,                                                      ";
       $sSql .= "                {$sFormulaCalculoValHistRet} as valor_historico_retorno,                                     ";
		   $sSql .= "                arreold.k00_valor as grid_valor_historico_origem,                                            ";

	     /*
	      *
	      * Informações utilizadas para montar o array de objetos oTotal
	      *
	      */
		   $sSql .= "                round( ( select fc_corre(k02_codigo::integer,                                                ";
       $sSql .= "                                             arreold.k00_dtoper::date,                                        ";
       $sSql .= "                                         arreold.k00_valor::float8,                                          ";
		   $sSql .= "                                         '$oTermo->v07_dtlanc'::date,                                        ";
		   $sSql .= "                                         extract( year from '$oTermo->v07_dtlanc'::date)::integer,           ";
		   $sSql .= "                                         arreold.k00_dtvenc::date ) ), 2) as topo_valor_corrigido_origem,    ";

		   /*
		    *
		    * Informações utilizadas para montar o array de objetos aSimulacao que irá montar a grid das informações das origens
		    *
		    */
       $sSql .= "                round( ( select fc_corre(k02_codigo::integer,                                                ";
       $sSql .= "                                             arreold.k00_dtoper::date,                                        ";
       $sSql .= "                                         arreold.k00_valor::float8,                                          ";
		   $sSql .= "                                         '{$dDataCorrecaoOrigens}'::date,                                    ";
		   $sSql .= "                                         extract( year from '{$dDataCorrecaoOrigens}'::date)::integer,       ";
		   $sSql .= "                                         arreold.k00_dtvenc::date ) ), 2) as grid_valor_corrigido_origem,    ";
		   $sSql .= "                round( ( select fc_corre(k02_codigo::integer,                                                ";
       $sSql .= "                                             arreold.k00_dtoper::date,                                        ";
       $sSql .= "                                         {$sFormulaCalculoSaldo}::float8,                                    ";
		   $sSql .= "                                         '".date("Y-m-d",db_getsession("DB_datausu"))."'::date,              ";
		   $sSql .= "                                         ".db_getsession("DB_anousu")."::integer,                            ";
		   $sSql .= "                                         arreold.k00_dtvenc::date ) ), 2) as grid_valor_corrigido_retorno    ";
		   $sSql .= "           from termosimulareg                                                                               ";
		   $sSql .= "                inner join termosimula  on termosimulareg.v23_termosimula = termosimula.v21_sequencial       ";
		   $sSql .= "                inner join arreold      on arreold.k00_numpre             = termosimulareg.v23_numpre        ";
		   $sSql .= "                                       and arreold.k00_numpar             = termosimulareg.v23_numpar        ";
		   $sSql .= "                                       and arreold.k00_receit             = termosimulareg.v23_receit        ";
       $sSql .= "                inner join tabrec       on tabrec.k02_codigo              = termosimulareg.v23_receit        ";
		   $sSql .= "                 left join tabrecjm     on tabrec.k02_codjm               = tabrecjm.k02_codjm               ";
		   $sSql .= "          where v21_parcel = $parcel                                                                         ";
		   $sSql .= "            and v21_ativo = 'true'                                                                           ";
		   $sSql .= "          order by v23_sequencial ) as x                                                                     ";

		   $rsSimulacao = db_query($sSql);
		   if ( pg_num_rows($rsSimulacao) == 0 ) {

		     throw new Exception("Nenhum registro encontrado para a simulação");

		   }

			 $aSimulacao = db_utils::getCollectionByRecord($rsSimulacao,false,false,true);
			 $iLinhasSimulacao = pg_num_rows($rsSimulacao);

		   for ( $iInd=0; $iInd < $iLinhasSimulacao; $iInd++ ) {

		   	 $oSimulacao = db_utils::fieldsMemory($rsSimulacao,$iInd);

		   	 $aSimulacao[$iInd]->grid_valor_total_origem  = ($oSimulacao->grid_valor_corrigido_origem+$oSimulacao->grid_valor_juros_origem+$oSimulacao->grid_valor_multa_origem);
		   	 $aSimulacao[$iInd]->grid_valor_total_retorno = ($oSimulacao->grid_valor_corrigido_retorno+$oSimulacao->grid_valor_juros_retorno+$oSimulacao->grid_valor_multa_retorno);

		   	 /**
		   	  * Verifica se o TIPO DE ANULAÇÃO é igual a 1, caso seja o Valor Histórico de Retorno
		   	  * passa a ser o Saldo a Pagar (Valor - Valor Abatido)
		   	  */
		   	 if (isset($oTermo->k40_tipoanulacao) && ($oTermo->k40_tipoanulacao == 1 || $oTermo->k40_tipoanulacao == 2)) {

           $aSimulacao[$iInd]->grid_valor_historico_retorno = $oSimulacao->saldo_pagar;
		   	   $oTotal->valor_total_historico_retorno          += $oSimulacao->saldo_pagar;

		   	 } else {

		   	   $aSimulacao[$iInd]->grid_valor_historico_retorno = $oSimulacao->valor_historico_retorno;
		   	   $oTotal->valor_total_historico_retorno          += $oSimulacao->valor_historico_retorno;
		   	 }

         $oTotal->valor_parcelas_abertas        = $oSimulacao->valor_parcelas_abertas;
		   	 $oTotal->valor_parcelas_pagas          = $oSimulacao->v21_valorpago;
         $oTotal->perc_retorno                  = $oSimulacao->v21_percretorno;
         $oTotal->qtd_total_parcelas            = $oTermo->v07_totpar;
         $oTotal->qtd_parcelas_pagas            = $oTermo->qtd_parcelas_pagas;


		   	 $oTotal->valor_total_historico_origem  += $oSimulacao->grid_valor_historico_origem;
         $oTotal->valor_total_corrigido_origem  += $oSimulacao->topo_valor_corrigido_origem;
         $oTotal->valor_total_juros_origem      += $oSimulacao->topo_valor_juros_origem;
         $oTotal->valor_total_multa_origem      += $oSimulacao->topo_valor_multa_origem;
         $oTotal->valor_total_geral_origem      += ($oSimulacao->topo_valor_corrigido_origem+
                                                    $oSimulacao->topo_valor_juros_origem+
                                                    $oSimulacao->topo_valor_multa_origem);

         $oTotal->valor_total_corrigido_retorno += $oSimulacao->grid_valor_corrigido_retorno;
         $oTotal->valor_total_juros_retorno     += $oSimulacao->grid_valor_juros_retorno;
         $oTotal->valor_total_multa_retorno     += $oSimulacao->grid_valor_multa_retorno;
         $oTotal->valor_desconto_retorno        += "0.00";
         $oTotal->valor_total_geral_retorno     += ($oSimulacao->grid_valor_corrigido_retorno+
                                                    $oSimulacao->grid_valor_juros_retorno+
                                                    $oSimulacao->grid_valor_multa_retorno);

		   }

		   $oTotal->perc_abatimento = ($oTotal->perc_retorno - 100)*-1;

		   if ( $oTotal->perc_abatimento < 0 ) {
	 	     $oTotal->perc_abatimento = 100;
		   }

		   /**
		    *
		    * Buscamos os valores de desconto no momento do parcelamento do débito
		    *
		    */
       $sSqlValParcHist  = " select abs( round( (select sum(k00_valor)                                              ";
       $sSqlValParcHist .= "                       from arrepaga                                                    ";
       $sSqlValParcHist .= "                      where k00_numpre = {$oTermo->v07_numpre}                          ";
       $sSqlValParcHist .= "                        and k00_hist in (918) ), 2)) as valor_total_desconto_pagamento, ";
       $sSqlValParcHist .= "       sum(vlrdescjur+vlrdescmul+vlrdesccor) as valor_total_desconto_origem             ";
	     $sSqlValParcHist .= "	 from ( select vlrdescjur,                                                             ";
       $sSqlValParcHist .= "                 vlrdescmul,                                                             ";
       $sSqlValParcHist .= "                 vlrdesccor                                                              ";
       $sSqlValParcHist .= "	          from termoini                                                                ";
	     $sSqlValParcHist .= "	         where parcel = {$parcel}                                                      ";
	     $sSqlValParcHist .= "	       union all                                                                       ";
	     $sSqlValParcHist .= "	        select vlrdescjur,                                                             ";
       $sSqlValParcHist .= "                 vlrdescmul,                                                             ";
       $sSqlValParcHist .= "                 vlrdesccor	                                                             ";
	     $sSqlValParcHist .= "	          from termodiv                                                                ";
	     $sSqlValParcHist .= "	         where parcel = {$parcel}                                                      ";
	     $sSqlValParcHist .= "	       union all                                                                       ";
	     $sSqlValParcHist .= "	        select dv10_vlrdescjur as vlrdescjur,                                          ";
       $sSqlValParcHist .= "                 dv10_vlrdescmul as vlrdescmul,                                          ";
       $sSqlValParcHist .= "                 0 as vlrdesccor 	                                                       ";
	     $sSqlValParcHist .= "	          from termodiver                                                              ";
	     $sSqlValParcHist .= "	         where dv10_parcel = {$parcel}                                                 ";
	     $sSqlValParcHist .= "	       union all                                                                       ";
	     $sSqlValParcHist .= "	        select 0          as vlrdescjur,                                               ";
       $sSqlValParcHist .= "                 0          as vlrdescmul,                                               ";
       $sSqlValParcHist .= "                 v07_vlrdes as vlrdesccor 	                                             ";
	     $sSqlValParcHist .= "	           from termoreparc                                                            ";
	     $sSqlValParcHist .= "	                inner join termo on termo.v07_parcel = termoreparc.v08_parcelorigem    ";
	     $sSqlValParcHist .= "	          where v08_parcel = {$parcel} ) as x                                          ";
	     $rsValoresParcelamento   = db_query($sSqlValParcHist);

	     if ( $rsValoresParcelamento ) {

	       $oValoresParcelamento                = db_utils::fieldsMemory($rsValoresParcelamento,0);
	       $oTotal->valor_total_desconto_origem = $oValoresParcelamento->valor_total_desconto_origem;
	       $oTotal->valor_total_geral_origem    = $oTotal->valor_total_geral_origem - $oTotal->valor_total_desconto_origem;

	       if ( $oTermo->k40_tipoanulacao != 3 ) {
           $oTotal->valor_parcelas_pagas = $oTotal->valor_parcelas_pagas - $oValoresParcelamento->valor_total_desconto_pagamento;
	       }
	     }


	     /**
	      *
	      * Buscamos o desconto dado após o lançamento do débito
	      *
	      */
	     if($iFormaCorrecao == 1){
         $sFormaCalculoDesconto = " (round( {$oTotal->valor_total_juros_origem} * descjur / 100,2)) + (round( {$oTotal->valor_total_multa_origem} * descmul / 100,2)) + (round( ({$oTotal->valor_total_corrigido_origem} - {$oTotal->valor_total_historico_origem}) * descvlr / 100,2)) as vlrdesconto ";
       } else {
         $sFormaCalculoDesconto = " (round( {$oTotal->valor_total_juros_origem} * descjur / 100,2)) + (round( {$oTotal->valor_total_multa_origem} * descmul / 100,2)) as vlrdesconto ";
       }
	     $sSqlDescontoRegraParcelamento  = "select {$sFormaCalculoDesconto} ";
	     $sSqlDescontoRegraParcelamento .= "  from arredesconto             ";
	     $sSqlDescontoRegraParcelamento .= "       inner join cadtipoparc on cadtipoparc.k40_codigo = arredesconto.k38_cadtipoparc ";
	     $sSqlDescontoRegraParcelamento .= "       inner join tipoparc    on tipoparc.cadtipoparc   = cadtipoparc.k40_codigo  ";
	     $sSqlDescontoRegraParcelamento .= " where k38_numpre = {$oTermo->v07_numpre} ";
	     $rsDescontoRegraParcelamento = db_query($sSqlDescontoRegraParcelamento);
	     if (pg_num_rows($rsDescontoRegraParcelamento) > 0) {
	       $oDescontoRegra = db_utils::fieldsMemory($rsDescontoRegraParcelamento,0);

	       $oTotal->valor_total_desconto_origem = $oDescontoRegra->vlrdesconto;
	       $oTotal->valor_total_geral_origem    = $oTotal->valor_total_geral_origem - $oTotal->valor_total_desconto_origem;

	     }


	    db_fim_transacao(false);

	    $_SESSION["aListaSimulacao"] = serialize($aSimulacao);
	    $aRetorno["aListaSimulacao"] = $aSimulacao;
	    $aRetorno["iTipoAnulacao"]   = $oTermo->k40_tipoanulacao;
	    $aRetorno["oTotal"]          = $oTotal;

	  } catch (Exception $oErro) {

	    db_fim_transacao(true);

      $aRetorno["sMensagem"] = urlencode($oErro->getMessage());
		  $aRetorno["iStatus"]   = 2;

	  }

	  echo $oJson->encode($aRetorno);

  break;

  case "setConfirmaExclusao" :

    $lErro     = false;
    $sMensagem = "Parcelamento anulado com sucesso !";

	  if ( $oParam->processo == '' ) {
	  	$processo = "null";
	  }else {
	  	$processo = $oParam->processo;
	  }

	  $sSqlAnulacao  = "select fc_excluiparcelamento($oParam->v21_sequencial,";
	  $sSqlAnulacao .= "                             $oParam->usuario,       ";
	  $sSqlAnulacao .= "                             '$oParam->motivo',      ";
	  $sSqlAnulacao .= "                             $processo )             ";

	  db_inicio_transacao();

	  $rsAnulacao = pg_fetch_result(db_query($sSqlAnulacao),0);
	  if ( substr($rsAnulacao,0,1) != '1') {
	  	$lErro     = true;
	  	$sMensagem = "Falha ao anular Parcelamento !";
	  }

	  db_fim_transacao($lErro);

	  echo $oJson->encode(array("mensagem"=>$sMensagem,"erro"=>$lErro));

  break;

}
?>
