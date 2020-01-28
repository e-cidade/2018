<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_app.utils.php");
require_once ("classes/db_termo_classe.php");
require_once ("classes/db_termoanu_classe.php");
require_once ("classes/db_termoanuproc_classe.php");
require_once ("classes/db_termoanusimula_classe.php");
require_once ("classes/db_arrecad_classe.php");
require_once ("classes/db_arreold_classe.php");
require_once ("classes/db_arrecantpgtoparcial_classe.php");
require_once ("classes/db_abatimentoarreckeyarrecadcompos_classe.php");
require_once ("classes/db_abatimentoarreckey_classe.php");
require_once ("classes/db_abatimentorecibo_classe.php");
require_once ("classes/db_recibo_classe.php");
require_once ("classes/db_arrehist_classe.php");
require_once ("classes/db_arrenumcgm_classe.php");
require_once ("classes/db_arrematric_classe.php");
require_once ("classes/db_arreinscr_classe.php");
require_once ("classes/db_abatimento_classe.php");

db_app::import('exceptions.*');

$oPost = db_utils::postMemory($_POST);

if (isset($oPost->processar)) {

  try {

  	$oTermo                           = new cl_termo;
    $oTermoAnu                        = new cl_termoanu();
    $oTermoAnuProc                    = new cl_termoanuproc();
    $oTermoAnuSimula                  = new cl_termoanusimula();
    $oArrecad                         = new cl_arrecad();
    $oArreold                         = new cl_arreold();
    $oArrecantPgtoParcial             = new cl_arrecantpgtoparcial();
    $oAbatimentoArreckeyArrecadcompos = new cl_abatimentoarreckeyarrecadcompos();
    $oAbatimentoArreckey              = new cl_abatimentoarreckey();
    $oAbatimentoRecibo                = new cl_abatimentorecibo();
    $oRecibo                          = new cl_recibo();
    $oArrehist                        = new cl_arrehist();
    $oArrenumcgm                      = new cl_arrenumcgm();
    $oArrematric                      = new cl_arrematric();
    $oArreinscr                       = new cl_arreinscr();
    $oAbatimento                      = new cl_abatimento();

    db_inicio_transacao();

    /*
     * Valida��es para o retorno do parcelamento
     *
     * 1 - N�o podem existir parcelas do parcelamento no arrecad
     * 2 - O parcelamento deve ter origens v�lidas (termodiv, termoini, termocontrib, termoreparc, termodiver)
     */

    $sSqlDadosParcelamento  = "select termo.v07_numpre as numpre,                                                    ";
    $sSqlDadosParcelamento .= "       ( select 1 as tipo                                                             ";
    $sSqlDadosParcelamento .= "           from termodiv                                                              ";
    $sSqlDadosParcelamento .= "          where termodiv.parcel = v07_parcel                                          ";
    $sSqlDadosParcelamento .= "          union                                                                       ";
    $sSqlDadosParcelamento .= "         select 2 as tipo                                                             ";
    $sSqlDadosParcelamento .= "           from termoreparc                                                           ";
    $sSqlDadosParcelamento .= "          where termoreparc.v08_parcel = v07_parcel                                   ";
    $sSqlDadosParcelamento .= "          union                                                                       ";
    $sSqlDadosParcelamento .= "         select 3 as tipo                                                             ";
    $sSqlDadosParcelamento .= "           from termoini                                                              ";
    $sSqlDadosParcelamento .= "          where termoini.parcel = v07_parcel                                          ";
    $sSqlDadosParcelamento .= "          union                                                                       ";
    $sSqlDadosParcelamento .= "         select 4 as tipo                                                             ";
    $sSqlDadosParcelamento .= "           from termodiver                                                            ";
    $sSqlDadosParcelamento .= "          where termodiver.dv10_parcel = v07_parcel                                   ";
    $sSqlDadosParcelamento .= "          union                                                                       ";
    $sSqlDadosParcelamento .= "         select 5 as tipo                                                             ";
    $sSqlDadosParcelamento .= "           from termocontrib                                                          ";
    $sSqlDadosParcelamento .= "          where termocontrib.parcel = v07_parcel limit 1) as tipo_parcelamento,       ";
    $sSqlDadosParcelamento .= "       (select 1                                                                      ";
    $sSqlDadosParcelamento .= "          from arrecad                                                                ";
    $sSqlDadosParcelamento .= "         where arrecad.k00_numpre = termo.v07_numpre                                  ";
    $sSqlDadosParcelamento .= "         limit 1) as arrecad                                                          ";
    $sSqlDadosParcelamento .= "  from termo                                                                          ";
    $sSqlDadosParcelamento .= " where termo.v07_parcel = {$oPost->parcelamento}                                      ";
    $rsDadosParcelamento = $oTermo->sql_record($sSqlDadosParcelamento);
    if ($oTermo->numrows == 0) {
      throw new Exception("Nenhum registro encontrado para o parcelamento {$oPost->parcelamento}");
    }

    $oDadosParcelamento = db_utils::fieldsMemory($rsDadosParcelamento,0);

    // 1 - N�o podem existir parcelas do parcelamento no arrecad
    if (!empty($oDadosParcelamento->arrecad)) {
      throw new BusinessException("Existem parcelas do parcelamento {$oPost->parcelamento} em aberto");
    }

    // 2 - O parcelamento deve ter origens v�lidas (termodiv, termoini, termocontrib, termoreparc, termodiver)
    if (empty($oDadosParcelamento->tipo_parcelamento)) {
    	$sMsg  = "Tipo do parcelamento {$oPost->parcelamento} inconsistente! \\n";
    	$sMsg .= "Sem vinculo com D�vida, Inicial, Contribui��o, Diversos ou Reparcelamento";
    	throw new BusinessException($sMsg);
    }

    /*
     * De acordo com o tipo de parcelamento montamos os dados dos cadtipos validos
     */
    switch ($oDadosParcelamento->tipo_parcelamento) {
    	case 1: //Parcelamento de Divida
    		$sCadTipoValidos = "5";
    	break;
    	case 2: //Reparcelamento
    		$sCadTipoValidos = "6,13,16,17";
    	break;
    	case 3: //Parcelamento de Inicial do Foro
    		$sCadTipoValidos = "12,18";
    	break;
    	case 4: //Parcelamento de Diversos
    		$sCadTipoValidos = "7";
      break;
    	case 5: //Parcelamento de Contribui��o de Melhoria
    		$sCadTipoValidos = "4";
    	break;
    }

    /**
     * Adicionado receita de origem...
     * @todo verificar se n�o � necess�rio adicionar hist�rico (v23_hist)
     */
    $sSqlDadosOrigem  = "select distinct                                                                                                       ";
    $sSqlDadosOrigem .= "       v23_numpre as origem_numpre,                                                                                   ";
    $sSqlDadosOrigem .= "       v23_numpar as origem_numpar,                                                                                   ";
    $sSqlDadosOrigem .= "       v23_valor  as valor_origem,                                                                                    ";
    $sSqlDadosOrigem .= "       v23_receit as receita_origem,                                                                                  ";
    $sSqlDadosOrigem .= "       (select k125_sequencial                                                                                        ";
    $sSqlDadosOrigem .= "          from abatimento                                                                                             ";
    $sSqlDadosOrigem .= "               inner join abatimentoarreckey on abatimentoarreckey.k128_abatimento = abatimento.k125_sequencial       ";
    $sSqlDadosOrigem .= "               inner join arreckey           on arreckey.k00_sequencial            = abatimentoarreckey.k128_arreckey ";
    $sSqlDadosOrigem .= "         where arreckey.k00_numpre  = termosimulareg.v23_numpre                                                       ";
    $sSqlDadosOrigem .= "           and arreckey.k00_numpar  = termosimulareg.v23_numpar                                                       ";
    $sSqlDadosOrigem .= "           and arreckey.k00_receit  = termosimulareg.v23_receit                                                       ";
    $sSqlDadosOrigem .= "           and arreckey.k00_hist    = termosimulareg.v23_hist                                                         ";
    $sSqlDadosOrigem .= "           and arreckey.k00_tipo    = termosimulareg.v23_tipo                                                         ";
    $sSqlDadosOrigem .= "           and abatimento.k125_tipoabatimento = 4 ) as abatimento                                                     ";
    $sSqlDadosOrigem .= "  from termoanu                                                                                                       ";
    $sSqlDadosOrigem .= "       inner join termoanusimula      on termoanusimula.v20_termoanu      = termoanu.v09_sequencial                   ";
    $sSqlDadosOrigem .= "       inner join termosimulareg      on termosimulareg.v23_termosimula   = termoanusimula.v20_termosimula            ";
    $sSqlDadosOrigem .= " where v09_parcel = {$oPost->parcelamento}                                                                            ";
    $rsDadosOrigem = $oTermo->sql_record($sSqlDadosOrigem);
    $iLinhasOrigem = $oTermo->numrows;
    if($iLinhasOrigem == 0) {
      throw new Exception("N�o foram encontrados registros das origens do parcelamento {$oPost->parcelamento}");
    }

     /*
      * A reativa��o do parcelamento � bem simples:
      * Anteriormente:
      * - Buscamos e validamos os dados do parcelamento
      * - Buscamos as origens do parcelamento
      * Nesta etapa:
      * 1 - Verificamos se as origens est�o em aberto, caso n�o estiverem no arrecad n�o ser� reativado o parcelamento
      * 2 - Inclu�mos do arreold os numpres das origens do parcelamento que est�o no arrecad
      * 3 - Exclu�mos do arrecad os numpres das origens do parcelamento
      * 4 - Inclu�mos no arrecad o numpre e as parcelas do parcelamento (Somente se n�o estejam pagas ou canceladas)
      * 5 - Exclu�mos do arreold o numpre e as parcelas do parcelamento
      * 6 - Exclu�mos os registros da anula��o do parcelamento (termoanusimula, termoanu)
      * 7 - Alteramos a situa��o do parcelamento como ativo ( termo.v07_situacao = 1 )
      */
     for ($iInd = 0; $iInd < $iLinhasOrigem; $iInd++) {

       $oDados = db_utils::fieldsMemory($rsDadosOrigem, $iInd);

       $sSqlVerificaPagamentoNumpreOrigem  = "select 1 as bloqueia                          ";
       $sSqlVerificaPagamentoNumpreOrigem .= "  from arrepaga                               ";
       $sSqlVerificaPagamentoNumpreOrigem .= " where k00_numpre = {$oDados->origem_numpre}  ";
       $sSqlVerificaPagamentoNumpreOrigem .= "   and k00_numpar = {$oDados->origem_numpar}  ";
       $sSqlVerificaPagamentoNumpreOrigem .= "union                                         ";
       $sSqlVerificaPagamentoNumpreOrigem .= "select 1 as bloqueia                          ";
       $sSqlVerificaPagamentoNumpreOrigem .= "  from arrecant                               ";
       $sSqlVerificaPagamentoNumpreOrigem .= " where k00_numpre = {$oDados->origem_numpre}  ";
       $sSqlVerificaPagamentoNumpreOrigem .= "   and k00_numpar = {$oDados->origem_numpar}  ";
       $rsVerificaPagamentoNumpreOrigem = db_query($sSqlVerificaPagamentoNumpreOrigem);

       if (pg_num_rows($rsVerificaPagamentoNumpreOrigem) > 0) {

       	 $sMsg  = "Opera��o n�o permitida!\\n";
       	 $sMsg .= "Motivo: Numpre: {$oDados->origem_numpre} Parcela: {$oDados->origem_numpar} possui pagamento ou cancelamento efetuado!";
         throw new Exception	($sMsg);
       }

       $sSqlValidaTipoOrigem  = "select 1                                                            ";
       $sSqlValidaTipoOrigem .= "  from arrecad                                                      ";
       $sSqlValidaTipoOrigem .= "       inner join arretipo on arretipo.k00_tipo = arrecad.k00_tipo  ";
       $sSqlValidaTipoOrigem .= " where arrecad.k00_numpre = {$oDados->origem_numpre}                ";
       $sSqlValidaTipoOrigem .= "   and arrecad.k00_numpar = {$oDados->origem_numpar}                ";
       $sSqlValidaTipoOrigem .= "   and arretipo.k03_tipo in ($sCadTipoValidos)                      ";
       $rsValidaTipoOrigem    = db_query($sSqlValidaTipoOrigem);
       if (pg_num_rows($rsValidaTipoOrigem) == 0) {
       	 $sMsg = "Opera��o n�o permitida!\\n";
       	 $sMsg.= "Poss�veis Causas:\\n";
       	 $sMsg.= " - N�o foi encontrado o Numpre: {$oDados->origem_numpre} Parcela: {$oDados->origem_numpar} na Arrecad \\n";
       	 $sMsg.= " - O tipo de d�bito do Numpre: {$oDados->origem_numpre} Parcela: {$oDados->origem_numpar} n�o � mais ";
       	 $sMsg.= "v�lido como tipo de d�bito para origem do parcelamento\\n";
       	 throw new Exception($sMsg);
       }

       //1 - Inclu�mos no arreold os numpres das origens do parcelamento que est�o no arrecad
       //2 - Exclu�mos do arrecad os numpres das origens do parcelamento
       $sSqlDadosNumpreArrecad = $oArrecad->sql_query_file(null, "*", "","k00_numpre = {$oDados->origem_numpre} and k00_numpar = {$oDados->origem_numpar}");
       $rsDadosNumpreArrecad   = $oArrecad->sql_record($sSqlDadosNumpreArrecad);
       $iLinhasNumpreArrecad   = $oArrecad->numrows;
       for( $iIndNumpreArrecad = 0; $iIndNumpreArrecad < $iLinhasNumpreArrecad; $iIndNumpreArrecad++) {
         $oDadosOrigem = db_utils::fieldsMemory($rsDadosNumpreArrecad, $iIndNumpreArrecad);

         $oArreold->k00_numpre = $oDadosOrigem->k00_numpre;
         $oArreold->k00_numpar = $oDadosOrigem->k00_numpar;
         $oArreold->k00_numcgm = $oDadosOrigem->k00_numcgm;
         $oArreold->k00_dtoper = $oDadosOrigem->k00_dtoper;
         $oArreold->k00_receit = $oDadosOrigem->k00_receit;
         $oArreold->k00_hist   = $oDadosOrigem->k00_hist;
         $oArreold->k00_valor  = $oDados->valor_origem;
         $oArreold->k00_dtvenc = $oDadosOrigem->k00_dtvenc;
         $oArreold->k00_numtot = $oDadosOrigem->k00_numtot;
         $oArreold->k00_numdig = $oDadosOrigem->k00_numdig;
         $oArreold->k00_tipo   = $oDadosOrigem->k00_tipo;
         $oArreold->k00_tipojm = $oDadosOrigem->k00_tipojm;
         $oArreold->incluir();
         if ($oArreold->erro_status == "0") {
       	   $sMsg  = "Erro incluindo dados das origens no arreold\\n";
       	   $sMsg .= "Erro: {$oArreold->erro_msg}";
     			 throw new Exception($sMsg);
         }

         $sWhereExecluir  = "     k00_numpre = {$oDados->origem_numpre}  ";
         $sWhereExecluir .= " and k00_numpar = {$oDados->origem_numpar}  ";
         $sWhereExecluir .= " and k00_receit = {$oDados->receita_origem} ";
         $oArrecad->excluir(null, $sWhereExecluir);
         if ($oArrecad->erro_status == "0") {
           throw new Exception("Erro ao excluir dados da arrecad do numpre {$oDados->numpre}, parcela {$oDados->numpar}");
         }
       }
     }

    /************************************************************************************************
     *
     *   7 - Exclu�mos a compensa��o caso exista
     *
     */
     if (!empty($oDados->abatimento)) {

     	$sSqlNumpreRecibo = $oAbatimentoRecibo->sql_query_file(null,"k127_numprerecibo",null,"k127_abatimento = {$oDados->abatimento}");
     	$rsNumpreRecibo   = $oAbatimentoRecibo->sql_record($sSqlNumpreRecibo);
     	if ($oAbatimentoRecibo->numrows > 0) {
     		$iNumpreRecibo = db_utils::fieldsMemory($rsNumpreRecibo, 0)->k127_numprerecibo;

     		$oRecibo->excluir(null,"k00_numpre = {$iNumpreRecibo}");
     		if ($oRecibo->erro_status == "0") {
     			$sMsg  = "3 - Erro ao excluir compensa��es\\n";
     			$sMsg .= "Erro: {$oRecibo->erro_msg}";
     			throw new Exception($sMsg);
     		}

     		$oArrehist->excluir(null,"k00_numpre = {$iNumpreRecibo}");
     		if ($oArrehist->erro_status == "0") {
     			$sMsg  = "4 - Erro ao excluir compensa��es\\n";
     			$sMsg .= "Erro: {$oArrehist->erro_msg}";
     			throw new Exception($sMsg);
     		}

     		$oArrenumcgm->excluir(null,$iNumpreRecibo);
     		if ($oArrenumcgm->erro_status == "0") {
     			$sMsg  = "5 - Erro ao excluir compensa��es\\n";
     			$sMsg .= "Erro: {$oArrenumcgm->erro_msg}";
     			throw new Exception($sMsg);
     		}

     		$oArrematric->excluir($iNumpreRecibo);
     		if ($oArrematric->erro_status == "0") {
     			$sMsg  = "6 - Erro ao excluir compensa��es\\n";
     			$sMsg .= "Erro: {$oArrematric->erro_msg}";
     			throw new Exception($sMsg);
     		}

     		$oArreinscr->excluir($iNumpreRecibo);
     		if ($oArreinscr->erro_status == "0") {
     			$sMsg  = "7 - Erro ao excluir compensa��es\\n";
     			$sMsg .= "Erro: {$oArreinscr->erro_msg}";
     			throw new Exception($sMsg);
     		}

     		$oAbatimentoRecibo->excluir(null, "k127_numprerecibo = {$iNumpreRecibo}");
     		if ($oAbatimentoRecibo->erro_status == "0") {
     			$sMsg  = "8 - Erro ao excluir compensa��es\\n";
     			$sMsg .= "Erro: {$oAbatimentoRecibo->erro_msg}";
     			throw new Exception($sMsg);
     		}

     	}

     	$oAbatimentoArreckeyArrecadcompos->excluir(null," k129_abatimentoarreckey in (select k128_sequencial
     	                                                                                from abatimentoarreckey
     	                                                                               where k128_abatimento = {$oDados->abatimento} )");
     	if ($oAbatimentoArreckeyArrecadcompos->erro_status == "0") {
     	  $sMsg  = "1 - Erro ao excluir compensa��es\\n";
     	  $sMsg .= "Erro: {$oAbatimentoArreckeyArrecadcompos->erro_msg}";
     	  throw new Exception($sMsg);
      }

      $oAbatimentoArreckey->excluir(null, "k128_abatimento = {$oDados->abatimento}");
     	if ($oAbatimentoArreckey->erro_status == "0"){
     	  $sMsg  = "2 - Erro ao excluir compensa��es\\n";
     	  $sMsg .= "Erro: {$oAbatimentoArreckeyArrecadcompos->erro_msg}";
     	  throw new Exception($sMsg);
     	}

     	$oArrecantPgtoParcial->excluir(null, "k00_abatimento = {$oDados->abatimento}");
     	if ($oArrecantPgtoParcial->erro_status == "0") {
     	$sMsg  = "9 - Erro ao excluir compensa��es\\n";
     	$sMsg .= "Erro: {$oArrecantPgtoParcial->erro_msg}";
     	throw new Exception($sMsg);
     	}

     	$oAbatimento->excluir($oDados->abatimento);
     	if ($oAbatimento->erro_status == "0") {
     	  $sMsg  = "10 - Erro ao excluir compensa��es\\n";
     	  $sMsg .= "Erro: {$oAbatimento->erro_msg}";
     	  throw new Exception($sMsg);
     	}

     }


     $sSqlParcelasParcelamento    = $oArreold->sql_query_file(null, "*", "k00_numpre, k00_numpar, k00_receit", "k00_numpre = {$oDadosParcelamento->numpre}");
     $rsParcelasParcelamento      = $oArreold->sql_record($sSqlParcelasParcelamento);
     $iLinhasParcelasParcelamento = $oArreold->numrows;
     if ($iLinhasParcelasParcelamento == 0) {
       throw new Exception("Nenhum registro encontrado no arreold para o numpre {$oDadosParcelamento->numpre}");
     }

     for ($iInd = 0; $iInd < $iLinhasParcelasParcelamento; $iInd++) {

       $oDados = db_utils::fieldsMemory($rsParcelasParcelamento, $iInd);

       $sSqlValidaNumpre  = "select *                                                                          ";
       $sSqlValidaNumpre .= "  from ( select 1 as cancelado_pago                                               ";
       $sSqlValidaNumpre .= "           from arrecant                                                          ";
       $sSqlValidaNumpre .= "          where arrecant.k00_numpre = {$oDados->k00_numpre}                       ";
       $sSqlValidaNumpre .= "            and arrecant.k00_numpar = {$oDados->k00_numpar}                       ";
       $sSqlValidaNumpre .= "          union                                                                   ";
       $sSqlValidaNumpre .= "         select 1 as cancelado_pago                                               ";
       $sSqlValidaNumpre .= "           from arrepaga                                                          ";
       $sSqlValidaNumpre .= "          where arrepaga.k00_numpre = {$oDados->k00_numpre}                       ";
       $sSqlValidaNumpre .= "            and arrepaga.k00_numpar = {$oDados->k00_numpar} ) as cancelado_pago   ";
       $sSqlValidaNumpre .= "  where cancelado_pago = 1                                                        ";
       $rsValidaNumpre   = db_query($sSqlValidaNumpre);
       if ( pg_num_rows($rsValidaNumpre) == 0) {

          //3 - Inclu�mos no arrecad o numpre e as parcelas do parcelamento (Somente se n�o estejam pagas ou canceladas)
          $oArrecad->k00_numpre = $oDados->k00_numpre;
          $oArrecad->k00_numpar = $oDados->k00_numpar;
          $oArrecad->k00_numcgm = $oDados->k00_numcgm;
          $oArrecad->k00_dtoper = $oDados->k00_dtoper;
          $oArrecad->k00_receit = $oDados->k00_receit;
          $oArrecad->k00_hist   = $oDados->k00_hist  ;
          $oArrecad->k00_valor  = $oDados->k00_valor ;
          $oArrecad->k00_dtvenc = $oDados->k00_dtvenc;
          $oArrecad->k00_numtot = $oDados->k00_numtot;
          $oArrecad->k00_numdig = $oDados->k00_numdig;
          $oArrecad->k00_tipo   = $oDados->k00_tipo  ;
          $oArrecad->k00_tipojm = $oDados->k00_tipojm;
          $oArrecad->incluir();
          if ($oArrecad->erro_status == "0") {
            $sMsg  = "Erro incluindo registros da parcela {$oDados->k00_numpar} do numpre {$oDados->k00_numpre} no Arrecad \\n";
            $sMsg .= "Erro: {$oArrecad->erro_msg}";
            throw new Exception($sMsg);
          }

       }

     }

     //Exclu�mos do arreold o numpre e as parcelas do parcelamento
     $oArreold->excluir_where("k00_numpre = {$oDados->k00_numpre}");
     if($oArreold->erro_status == "0") {
       $sMsg  = "Erro excluindo registros da parcela {$oDados->k00_numpar} do numpre {$oDados->k00_numpre} do Arreold \\n";
       $sMsg .= "Erro: {$oArreold->erro_msg}";
       throw new Exception($sMsg);
     }

     //5 - Exclu�mos os registros da anula��o do parcelamento (termoanusimula, termoanu)
     $sWhere  = "v20_termoanu in (select v09_sequencial                      ";
     $sWhere .= "                  from termoanu                             ";
     $sWhere .= "                 where v09_parcel = {$oPost->parcelamento} )";
     $oTermoAnuSimula->excluir(null, $sWhere);
     if ($oTermo->erro_status == "0") {
       $sMsg  = "Erro ao excluir registros da simula��o da anula��o do parcelamento {$oPost->parcelamento} \\n";
       $sMsg .= "Erro: {$oTermoAnuSimula->erro_msg}";
       throw new Exception($sMsg);
     }

     $oTermoAnuProc->excluir(null, "v22_termoanu in (select v09_sequencial from termoanu where v09_parcel = {$oPost->parcelamento})");
     if ($oTermoProc->erro_status == "0") {
       $sMsg  = "Erro ao excluir anula��o do parcelamento {$oPost->parcelamento} \\n";
       $sMsg .= "Erro: {$oTermoAnuProc->erro_msg}";
       throw new Exception($sMsg);
     }

     $oTermoAnu->excluir(null, "v09_parcel = {$oPost->parcelamento}");
     if ($oTermo->erro_status == "0") {
       $sMsg  = "Erro ao excluir anula��o do parcelamento {$oPost->parcelamento} \\n";
       $sMsg .= "Erro: {$oTermoAnu->erro_msg}";
       throw new Exception($sMsg);
     }


     //6 - Alteramos a situa��o do parcelamento como ativo ( termo.v07_situacao = 1 )
     $oTermo->v07_parcel   = $oPost->parcelamento;
     $oTermo->v07_situacao = 1;
     $oTermo->alterar($oPost->parcelamento);
     if ($oTermo->erro_status == "0") {
       $sMsg  = "Erro ao alterar a situa��o do parcelamento {$oPost->parcelamento} \\n";
       $sMsg .= "Erro: {$oTermo->erro_msg}";
       throw new Exception($sMsg);
     }

     db_fim_transacao(false);
     db_msgbox("Parcelamento reativado com sucesso");

  } catch (Exception $oErro) {

    db_msgbox($oErro->getMessage());
    db_fim_transacao(true);

  }

}

?>
<html>
 <head>
   <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta http-equiv="Expires" CONTENT="0">
 </head>
<?
  db_app::load("scripts.js");
  db_app::load("estilos.css");
?>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  onload="">
<br><br><br><br><br>
<center>
<form name="form1" action="" method="post">
 <fieldset style="width: 400px; height: 50px;">
   <legend><b>Retiva��o de Parcelamento</b></legend>
   <br>
   <table align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
     <td width="100"><b>Parcelamento :</b> </td>
     <td width="300"><input type="text" name="parcelamento" size="10"></td>
    </tr>
   </table>
 </fieldset>
 <br>
 <input type="submit" name="processar" disabled id="processar" value="Processar" onclick="js_processar();">
</form>
</center>
<?

  /*
   * ATEN��O:
   * Esta rotina foi criada unicamente para ser utilizada pelo Usu�rio 1 - DBSeller e somente em casos muito espec�ficos.
   *
   * A libera��o desta rotina para o usu�rio ou seu uso indevido ir� acarretar s�rios problemas entre o tribut�rio e a
   * contabilidade, pois n�o � realizado nenhum acerto cont�bil de estorno ou lan�amento de receita para reativa��o do
   * parcelamento por esta rotina, o que ir� causar problemas s�rios na incorpora��o tribut�ria.
   *
   * A estrutura atual do sistema n�o est� preparada para essas situa��es de reativa��o de parcelamento, para isto bastaria
   * apenas realizar um novo parcelamento, contudo h� casos extremos que isto n�o � poss�vel.
   *
   * Por este motivo, a rotina est� liberada somente para o usu�rio 1 - DBSELLER. Somente para utilizar essa rotina em
   * casos extremos onde n�o exista outra alternativa.
   *
   */
  if(db_getsession("DB_id_usuario") == 1) {
    echo "<script> document.form1.processar.disabled = false </script>";
  } else {
    echo "<script> alert('Rotina bloqueada!') </script>";
  }

  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));

?>
</body>
</html>
<script>
function js_processar() {

  if (documento.form1.parcelamento.value="") {
    alert("Informe o parcelamento a ser retornado!");
  }

  if(confirm("Deseja realmente reativar o parcelamento "+document.form1.parcelamento.value+"?")) {
    return true;
  } else {
    return false;
  }

}
</script>