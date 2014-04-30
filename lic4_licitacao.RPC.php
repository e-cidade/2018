<?php
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

require_once("libs/db_stdlib.php");
require_once("std/db_stdClass.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");

require_once("model/compilacaoRegistroPreco.model.php");
require_once("model/licitacao.model.php");

$oJson             = new services_json();
$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
$oRetorno->itens   = array();
$dtDia             = date("Y-m-d", db_getsession("DB_datausu"));

switch ($oParam->exec) {
  

  case "salvarTrocaFornecedor" :
    
    require_once("classes/db_pcorcamtroca_classe.php");
    require_once("classes/db_pcorcamjulg_classe.php");
    
    $oDaoPcorcamtroca = new cl_pcorcamtroca();
    $oDaopcorcamjulg  = new cl_pcorcamjulg();
    try {
       
      db_inicio_transacao(true);
      
      $oDaopcorcamjulg->pc24_orcamforne = $oParam->iFornecedorNovo;
      $oDaopcorcamjulg->pc24_orcamitem  = $oParam->iItem;
      $oDaopcorcamjulg->pc24_pontuacao  = $oParam->iPontuacao;
      $oDaopcorcamjulg->alterar(null, null, "pc24_orcamitem = {$oParam->iItem} and pc24_orcamforne = {$oParam->iFornecedorAntigo}");
      if ($oDaopcorcamjulg->erro_status == "0") {
        throw new Exception($oDaopcorcamjulg->erro_msg);
      }
      
      $oDaoPcorcamtroca->pc25_forneant  = $oParam->iFornecedorAntigo;
      $oDaoPcorcamtroca->pc25_forneatu  = $oParam->iFornecedorNovo;
      $oDaoPcorcamtroca->pc25_motivo    = addslashes($oParam->sMotivo);
      $oDaoPcorcamtroca->pc25_orcamitem = $oParam->iItem;
      $oDaoPcorcamtroca->incluir(null);
      if ($oDaoPcorcamtroca->erro_status == "0") {
        throw new Exception($oDaoPcorcamtroca->erro_msg);
      }
      
      db_fim_transacao(false);
      
      $oRetorno->message = "Troca de fornecedor realizada com sucesso.";
       
    } catch (Exception $eErro) {
    
      db_fim_transacao(true);
      $oRetorno->message = urlencode($eErro->getMessage());
      $oRetorno->status  = 2;
    }    
    
  break;
  
  case "listaItensTroca":
  
    require_once("classes/db_pcorcamforne_classe.php");
    $clpcorcamforne = new cl_pcorcamforne;
    
    $iLicitacao = $oParam->iLicitacao;
    $aItens     = array();
    
    $sCamposItens  = "l21_codigo,        ";
    $sCamposItens  = "l21_codpcprocitem, ";
    $sCamposItens .= "pc01_codmater,     ";
    $sCamposItens .= "pc01_descrmater,   ";
    $sCamposItens .= "z01_numcgm,        ";
    $sCamposItens .= "z01_nome,          ";
    $sCamposItens .= "pc23_valor,        ";
    $sCamposItens .= "pc23_quant,        "; 
    $sCamposItens .= "pc23_vlrun,        ";
    $sCamposItens .= "pc20_codorc,       ";
    $sCamposItens .= "l20_tipojulg,      ";
    $sCamposItens .= "pc24_pontuacao,    ";
    $sCamposItens .= "pc23_orcamitem,    ";
    $sCamposItens .= "pc11_numero,       ";
    $sCamposItens .= "pc24_orcamforne,   ";
    $sCamposItens .= "pc24_orcamitem,    ";
    $sCamposItens .= "pc23_obs           ";
    
    $sSqlItens  = "select {$sCamposItens}                                                                             ";
    $sSqlItens .= " from pcorcam                                                                                      ";
    $sSqlItens .= "inner join pcorcamitem          on pcorcamitem.pc22_codorc         = pcorcam.pc20_codorc           ";
    $sSqlItens .= "inner join pcorcamforne         on pcorcamforne.pc21_codorc        = pcorcam.pc20_codorc           ";
    $sSqlItens .= "inner join pcorcamval           on pcorcamval.pc23_orcamitem       = pcorcamitem.pc22_orcamitem    ";
    $sSqlItens .= "                               and pcorcamval.pc23_orcamforne      = pcorcamforne.pc21_orcamforne  ";
    $sSqlItens .= "inner join pcorcamitemlic       on pcorcamitemlic.pc26_orcamitem   = pcorcamitem.pc22_orcamitem    ";
    $sSqlItens .= "inner join liclicitem           on pcorcamitemlic.pc26_liclicitem  = liclicitem.l21_codigo         ";
    $sSqlItens .= "inner join liclicita            on liclicita.l20_codigo            = liclicitem.l21_codliclicita   ";
    $sSqlItens .= "inner join pcorcamjulg          on pcorcamjulg.pc24_orcamitem      = pcorcamitem.pc22_orcamitem    ";
    $sSqlItens .= "                               and pcorcamjulg.pc24_orcamforne     = pcorcamforne.pc21_orcamforne  ";
    $sSqlItens .= "inner join pcprocitem           on liclicitem.l21_codpcprocitem    = pcprocitem.pc81_codprocitem   ";
    $sSqlItens .= "inner join solicitem            on pcprocitem.pc81_solicitem       = solicitem.pc11_codigo         ";
    $sSqlItens .= "inner join solicitempcmater     on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo         ";
    $sSqlItens .= "inner join pcmater              on solicitempcmater.pc16_codmater  = pc01_codmater                 ";
    $sSqlItens .= "inner join cgm                  on cgm.z01_numcgm                  = pcorcamforne.pc21_numcgm      ";
    $sSqlItens .= "where l21_codliclicita = {$iLicitacao}                                                             ";
    
    $rsItens = db_query($sSqlItens);
    if (pg_numrows($rsItens) > 0) {

      for ($iItem = 0; $iItem < pg_numrows($rsItens); $iItem++) {

        $oItem = db_utils::fieldsMemory($rsItens, $iItem);
        $oDadosItens = new stdClass();
        $oDadosItens->item           = $oItem->pc24_orcamitem;
        $oDadosItens->iOrcamento     = $oItem->pc20_codorc;
        $oDadosItens->cgm            = $oItem->pc24_orcamforne;
        $oDadosItens->nome           = $oItem->z01_nome;
        $oDadosItens->obs            = $oItem->pc23_obs;
        $oDadosItens->valor          = db_formatar($oItem->pc23_valor, "f");
        $oDadosItens->solicita       = $oItem->pc11_numero;
        $oDadosItens->pontuacao      = $oItem->pc24_pontuacao;
        
        $oDadosItens->fornecedor     = $oItem->z01_numcgm    . " - " . $oItem->z01_nome;
        $oDadosItens->material       = $oItem->pc01_codmater . " - " . $oItem->pc01_descrmater;
        $oDadosItens->quantidade     = $oItem->pc23_quant;
        $oDadosItens->valorunitario  = trim(db_formatar($oItem->pc23_vlrun, "f"));
        
        $aItens[] = $oDadosItens;
        
      }
    }
  
    $oRetorno->dados = $aItens;
    break;  
  
  
  case "getRegistrosdePreco":
    
    $sSqlRegistro  = "select distinct l21_codliclicita as licitacao,";
    $sSqlRegistro .= "       pc22_codorc      as orcamento,";
    $sSqlRegistro .= "       pc54_solicita    as solicitacao,";
    $sSqlRegistro .= "       pc54_datainicio  as datainicio,";
    $sSqlRegistro .= "       pc54_datatermino as datatermino,";
    $sSqlRegistro .= "       pc10_resumo      as resumo";
    $sSqlRegistro .= "  from solicitaregistropreco ";
    $sSqlRegistro .= "       inner join solicita       on pc54_solicita    = pc10_numero ";
    $sSqlRegistro .= "       inner join solicitem      on pc10_numero      = pc11_numero ";
    $sSqlRegistro .= "       inner join pcprocitem     on pc81_solicitem   = pc11_codigo ";
    $sSqlRegistro .= "       inner join liclicitem     on pc81_codprocitem = l21_codpcprocitem ";
    $sSqlRegistro .= "       inner join liclicita      on l21_codliclicita = l20_codigo";
    $sSqlRegistro .= "       inner join pcorcamitemlic on pc26_liclicitem  = l21_codigo ";
    $sSqlRegistro .= "       inner join pcorcamitem    on pc26_orcamitem   = pc22_orcamitem  ";
    $sSqlRegistro .= " where cast('{$dtDia}' as date) between pc54_datainicio and pc54_datatermino ";
    $sSqlRegistro .= "   and l20_licsituacao = 1";
    $sSqlRegistro .= " order by l21_codliclicita";
    $rsRegistro    = db_query($sSqlRegistro);
    
    $oRetorno->itens = db_utils::getColectionByRecord($rsRegistro, true, false, true); 
    break;
    
   Case "getItensRegistro":  
     
     $oCompilacao = new compilacaoRegistroPreco($oParam->iSolicitacao);
     $aItens                 = $oCompilacao->getItens();
     foreach ($aItens as $iIndice => $oItem) {
        
       
       $oItemRetono = new stdClass;
       $oItemRetono->codigoitem     = $oItem->getCodigoMaterial();
       $oItemRetono->codigoitemsol  = $oItem->getCodigoItemSolicitacao();
       $oItemRetono->descricaoitem  = $oItem->getDescricaoMaterial();
       $oItemRetono->qtdemin        = $oItem->getQuantidadeMinima();
       $oItemRetono->qtdemax        = $oItem->getQuantidadeMaxima();
       $oItemRetono->codigoitemorca = $oItem->getCodigoItemOrcamento();
       $oItemRetono->resumo         = $oItem->getResumo();
       $oItemRetono->marcado        = false;
       $oItemRetono->bloqueado      = false;
       $oItemRetono->legenda        = "";
       if (isset($oParam->iFornecedor) &&  isset($_SESSION["RP_fornecedores"][$oParam->iFornecedor])) {
         
         if (in_array($oItem->getCodigoItemOrcamento(),$_SESSION["RP_fornecedores"][$oParam->iFornecedor])) {
           $oItemRetono->marcado = true;
         }
       }
       /**
        * Verificamos se o o item nao está Bloqueado ou em desistencia
        */
       if (isset($oParam->iFornecedor) && $oParam->iFornecedor != "") {
         
         $sSqlBloqueio  = "select min(pc66_datainicial) as datainicial, max(pc66_datafinal) as datafinal ";
         $sSqlBloqueio .= "  from registroprecomovimentacaoitens ";
         $sSqlBloqueio .= "       inner join registroprecomovimentacao on pc58_sequencial = pc66_registroprecomovimentacao ";
         $sSqlBloqueio .= " where pc58_situacao    = 1 ";
         $sSqlBloqueio .= "   and pc58_tipo        = 2 ";
         $sSqlBloqueio .= "   and pc66_pcorcamitem = {$oItem->getCodigoItemOrcamento()}";
         $sSqlBloqueio .= "   and pc66_orcamforne  = {$oParam->iFornecedor}";
         $sSqlBloqueio .= "   and '{$dtDia}'::date between pc66_datainicial and pc66_datafinal";
         $rsBloqueio    = db_query($sSqlBloqueio);
         if (pg_num_rows($rsBloqueio) > 0) {
  
           $oBloqueio = db_utils::fieldsMemory($rsBloqueio, 0);
           if ($oBloqueio->datainicial != "" && $oBloqueio->datafinal != "") {
             
           $oItemRetono->bloqueado  = true;
           $sMsgLegenda             = "Item com desistência de <b>".db_formatar($oBloqueio->datainicial,"d")."</b> a "; 
           $sMsgLegenda            .= "<b>".db_formatar($oBloqueio->datafinal, "d")."</b>"; 
           $oItemRetono->legenda    = urlencode($sMsgLegenda);
           }         
         }
       }
       
       if (isset($oParam->verificaBloqueios)) {
         
         $sSqlBloqueio  = "select min(pc66_datainicial) as datainicial, max(pc66_datafinal) as datafinal ";
         $sSqlBloqueio .= "  from registroprecomovimentacaoitens ";
         $sSqlBloqueio .= "       inner join registroprecomovimentacao on pc58_sequencial = pc66_registroprecomovimentacao ";
         $sSqlBloqueio .= " where pc58_situacao    = 1 ";
         $sSqlBloqueio .= "   and pc58_tipo        = 3 ";
         $sSqlBloqueio .= "   and pc66_pcorcamitem = {$oItem->getCodigoItemOrcamento()}";
         $sSqlBloqueio .= "   and '{$dtDia}'::date between pc66_datainicial and pc66_datafinal";
         $rsBloqueio    = db_query($sSqlBloqueio);
         if (pg_num_rows($rsBloqueio) > 0) {
  
           $oBloqueio = db_utils::fieldsMemory($rsBloqueio, 0);
           if ($oBloqueio->datainicial != "" && $oBloqueio->datafinal != "") {
             
           $oItemRetono->bloqueado  = true;
           $sMsgLegenda             = "Item com bloqueio de <b>".db_formatar($oBloqueio->datainicial,"d")."</b> a "; 
           $sMsgLegenda            .= "<b>".db_formatar($oBloqueio->datafinal, "d")."</b>"; 
           $oItemRetono->legenda    = urlencode($sMsgLegenda);
           
           }         
         }
       }
       $oItemRetono->unidade        = $oItem->getUnidade();
       $oDaoMatUnid                 = db_utils::getDao("matunid");
       $sSqlMatUnid                 = $oDaoMatUnid->sql_query_file($oItem->getUnidade());
       $sUnidade                    = db_utils::fieldsMemory($oDaoMatUnid->sql_record($sSqlMatUnid),0)->m61_descr;
       $oItemRetono->descrunidade   = urlencode($sUnidade);
       $oItemRetono->indice         = $iIndice;
       $oItemRetono->ativo          = $oItem->isAtivo();
       $oRetorno->itens[] = $oItemRetono;
       
     }
     break;
     
   case "getFornecedoresItemRegistro" :

     $oCompilacao                    = new compilacaoRegistroPreco($oParam->iSolicitacao);
     $oRetorno->itens                =  $oCompilacao->getFornecedoresPorItem($oParam->iCodigoItemSolicitacao);
     break;
     
   case "saveValoresFornecedoresRegistro":
     
     $oCompilacao = new compilacaoRegistroPreco($oParam->iSolicitacao);
     try {
       
       db_inicio_transacao(true);
       $oCompilacao->setValoresFornecedores(1, $oParam->aItens);
       $oCompilacao->julgarOrcamentoRegistroPreco($oParam->iCodigoOrcamento, $oParam->iCodigoItemOrcamento);
       db_fim_transacao(false);
       
     } catch (Exception $eErro) {

       db_fim_transacao(true);
       $oRetorno->message = urlencode($eErro->getMessage());
       $oRetorno->status  = 2;
     }
     break;
     
   case "julgarRegistroPreco":
    
     $oCompilacao = new compilacaoRegistroPreco($oParam->iSolicitacao);
     try {
       
       db_inicio_transacao(true);
       $oCompilacao->julgarOrcamentoRegistroPreco($oParam->iOrcamento);
       db_fim_transacao(false);
     } catch (Exception $eErro) {

       db_fim_transacao(true);
       $oRetorno->message = urlencode($eErro->getMessage());
       $oRetorno->status  = 2;
     }
     
     break;
     
   case "getVencedoresRegistro":
     
     $iNumeroCasasDecimais = 2; 
     $aParametrosEmpenho   = db_stdClass::getParametro("empparametro", array(db_getsession("DB_anousu")));
     if (count($aParametrosEmpenho) > 0) {
       $iNumeroCasasDecimais = $aParametrosEmpenho[0]->e30_numdec;
     }
     $oCompilacao                    = new compilacaoRegistroPreco($oParam->iSolicitacao);
     $oRetorno->itens                = $oCompilacao->getVencedoresJulgamento($oParam->iOrcamento); 
     $oRetorno->iNumeroCasasDecimais = $iNumeroCasasDecimais; 
     break;
     
   case "getFornecedores":
     
     $oCompilacao     = new compilacaoRegistroPreco($oParam->iSolicitacao);
     $oRetorno->itens = $oCompilacao->getFornecedoresPorOrcamento($oParam->iOrcamento); 
     break;

   case "saveItensDesistenciaFornecedor":
     
     /**
      * Apenas Salvamos os itens que o fornecedor marcou na sessao
      */
     if (!isset($_SESSION["RP_fornecedores"])) {
        $_SESSION["RP_fornecedores"] = array();
     }
     /*
      * 
      */
     $oRetorno->lHabilitarBotao = false;
     unset($_SESSION["RP_fornecedores"][$oParam->iFornecedor]);
     $_SESSION["RP_fornecedores"][$oParam->iFornecedor] = array();
     foreach ($oParam->aItens as $oItem) {
       $_SESSION["RP_fornecedores"][$oParam->iFornecedor][] = $oItem->iItemOrcamento;
     }
     /**
      * Verifica o total de Itens Marcados
      */
     $iTotalItensMarcados = 0;
     foreach ($_SESSION["RP_fornecedores"] as $oFornecedor) {
       $iTotalItensMarcados += count($oFornecedor);
     }
     if ($iTotalItensMarcados > 0) {
       $oRetorno->lHabilitarBotao = true;
     }
     break;
     
   case "salvarDesistencia":

     /**
      * Verificamos se o usuário selecionou algum item
      */
     
     if (isset($_SESSION["RP_fornecedores"])) {
       
       try {
         
         db_inicio_transacao();
         $oCompilacao = new compilacaoRegistroPreco($oParam->iSolicitacao);
         $oCompilacao->salvarDesistencia($_SESSION["RP_fornecedores"],
                                         $oParam->sJustificativa,
                                         $oParam->iTipoDesistencia,
                                         $oParam->dtDataInicial,
                                         $oParam->dtDataFinal
                                         ); 

         foreach ($_SESSION["RP_fornecedores"] as $iFornecedores => $oFornecedores){
         
           foreach ($oFornecedores as $iItem => $oItem) {
             
             $oCompilacao->julgarOrcamentoRegistroPreco($oParam->iOrcamento, $oItem);
           }
         
         }    
         
         db_fim_transacao(false);
         unset($_SESSION["RP_fornecedores"]);
         
       } catch (Exception $eErro) {
         
         $oRetorno->status = 2;
         $oRetorno->message = urlencode($eErro->getMessage());
         db_fim_transacao(true);
         
         
       }
     } else {
       
       $oRetorno->status = 2;
       $oRetorno->message = "Nenhum item Selecionado!\nProcessamento Cancelado.";
     }
     break;
     
   case "bloquearItensRegistro":
     
     try {
         
        db_inicio_transacao();
        $oCompilacao = new compilacaoRegistroPreco($oParam->iSolicitacao);
        $oCompilacao->bloquearItens($oParam->aItens,
                                    $oParam->sJustificativa,
                                    $oParam->iTipoDesistencia,
                                    $oParam->dtDataInicial,
                                    $oParam->dtDataFinal
                                  );                                
        db_fim_transacao(false);
         
       } catch (Exception $eErro) {
         
         $oRetorno->status = 2;
         $oRetorno->message = urlencode($eErro->getMessage());
         db_fim_transacao(true);
         
         
       }
     break;
     
   case "getMovimentosRegistro" :
     
     $oDaoRegistroPrecoMovimentos = db_utils::getDao("registroprecomovimentacaoitens");
     $sWhere                      = "pc20_codorc = {$oParam->iOrcamento} and pc58_situacao = 1 and pc58_tipo = {$oParam->iTipo}";
     $sCampos                     = " distinct  registroprecomovimentacao.*,login,";
     $sCampos                    .= "(select count(*) ";
     $sCampos                    .= "   from registroprecomovimentacaoitens"; 
     $sCampos                    .= "  where pc66_registroprecomovimentacao=pc58_sequencial) as qtditens";
     $sSqlMovimentos              = $oDaoRegistroPrecoMovimentos->sql_query_orcamento(null, $sCampos,"pc58_data", $sWhere);
     $rsMovimentos                = $oDaoRegistroPrecoMovimentos->sql_record($sSqlMovimentos);
     $oRetorno->itens             = db_utils::getColectionByRecord($rsMovimentos, false,false, true);
     break;
     
  case "getItensMovimentosRegistro" :
     
     $oDaoRegistroPrecoMovimentos = db_utils::getDao("registroprecomovimentacaoitens");
     $sWhere                      = "pc66_registroprecomovimentacao = {$oParam->iCodigoMovimentacao} and pc58_situacao = 1";
     $sCampos                     = " distinct  pc01_codmater,z01_nome,pc11_resum,pc01_descrmater,pc66_justificativa";
     $sSqlMovimentos              = $oDaoRegistroPrecoMovimentos->sql_query_orcamento(null, $sCampos,"pc01_codmater", $sWhere);
     $rsMovimentos                = $oDaoRegistroPrecoMovimentos->sql_record($sSqlMovimentos);
     $oRetorno->itens             = db_utils::getColectionByRecord($rsMovimentos, false,false, true);
     break;
     
  case "CancelaMovimentos" :
    
    db_inicio_transacao();
    $oDaoRegistroPrecoMovimentos = db_utils::getDao("registroprecomovimentacao");
    foreach ($oParam->aItens as $oItem) {
      
      $oDaoRegistroPrecoMovimentos->pc58_sequencial = $oItem->iCodigoMovimento;
      $oDaoRegistroPrecoMovimentos->pc58_situacao   = 2;
      $oDaoRegistroPrecoMovimentos->alterar($oItem->iCodigoMovimento);
       
    }
    db_fim_transacao(false);
    break;
    
  case "getValoresParciais" :
    
  	try {
  		
  		$oLicitacao = new licitacao();
  		$oRetorno->nValorSaldoTotal = $oLicitacao->getValoresParciais( $oParam->iCodigoItemProcesso,
  		                                                               $oParam->iCodigoDotacao,
  		                                                               $oParam->iOrcTipoRec )->nValorSaldoTotal;  		
  	} catch (Exception $eErro) {
         
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    break;
}

echo $oJson->encode($oRetorno);
?>