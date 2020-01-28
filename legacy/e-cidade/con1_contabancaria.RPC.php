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

require_once ('libs/db_conn.php');
require_once ('libs/db_stdlib.php');
require_once ('libs/db_utils.php');
require_once ("libs/db_app.utils.php");
require_once ('libs/db_conecta.php');
require_once ('libs/JSON.php');
require_once ('libs/db_utils.php');
require_once ('dbforms/db_funcoes.php');
require_once ("classes/db_bancoagencia_classe.php");
require_once ("classes/db_contabancaria_classe.php");
require_once ("model/financeiro/ContaBancaria.model.php");

db_app::import("exceptions.*");

$oDaoBancoAgencia    = new cl_bancoagencia();
$oDaoContaBancaria   = new cl_contabancaria();
$oJson               = new services_json();

$oParam              = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno            = new stdClass();
$aRetorno            = array();
$oRetorno->status    = 1;
$oRetorno->message   = 'teste';

$aContasBancarias = array("237" => "BANCO BRADESCO S.A",
                          "001" => "BANCO DO BRASIL",
                          "341" => "BANCO ITAU S/A",
                          "041" => "BANRISUL",
                          "104" => "CAIXA ECONOMICA FEDERAL");

/**
 * $iRetorno = 0  retornar um objeto.
 * $iRetorno = 1  retorna o array para o widget do autocomplete
 */
$iRetorno            = 0;

switch ($oParam->exec) {


  case "getDados":

    $oDaoContaBancaria                 = new ContaBancaria($oParam->iCodigoContaBancaria);
    $oRetorno->db89_sequencial         = $oDaoContaBancaria->getSequencialBancoAgencia();
    $oRetorno->db89_db_bancos          = $oDaoContaBancaria->getCodigoBanco();
    $oRetorno->db90_descr              = $oDaoContaBancaria->getDescricaoBanco();
    $oRetorno->db89_codagencia         = $oDaoContaBancaria->getNumeroAgencia();
    $oRetorno->db89_digito             = $oDaoContaBancaria->getDVAgencia();
    $oRetorno->db83_conta              = $oDaoContaBancaria->getNumeroConta();
    $oRetorno->db83_dvconta            = $oDaoContaBancaria->getDVConta();
    $oRetorno->db83_identificador      = $oDaoContaBancaria->getIdentificador();
    $oRetorno->db83_codigooperacao     = $oDaoContaBancaria->getCodigoOperacao();
    $oRetorno->db83_tipoconta          = $oDaoContaBancaria->getTipoConta();
    $oRetorno->db83_sequencial         = $oDaoContaBancaria->getSequencialContaBancaria();
   break;
  case "getAgencia":

     $sSqlAgencias = $oDaoBancoAgencia->sql_query("",
                                                " distinct  db89_codagencia, db89_digito  ",
                                                " db89_codagencia ",
                                                " db90_codban = '{$oParam->sBanco}' and db89_codagencia ilike '{$oParam->sAgencia}%' ");
     $rsAgencias   = $oDaoBancoAgencia->sql_record($sSqlAgencias);
     $oAgencias    = db_utils::getCollectionByRecord($rsAgencias);

     foreach ($oAgencias as $oAgencia) {

      $oItensAutoComplete        = new stdClass();
      $oItensAutoComplete->cod   = $oAgencia->db89_codagencia;
      $oItensAutoComplete->label = $oAgencia->db89_codagencia ."-".$oAgencia->db89_digito;
      $aRetorno[]                = $oItensAutoComplete;
      unset($oItensAutoComplete);
     }
     $iRetorno = 1;
  break;
  case "getConta":

    /**
     * Valida se é uma conta do plano de contas
     */
    $lPlanoConta = 'false';
    if ($oParam->isContaPlano) {
      $lPlanoConta = 'true';
    }
    $sSqlContas = $oDaoContaBancaria->sql_query("",
                                              " distinct  db83_sequencial, db83_conta, db83_dvconta,db83_identificador, db83_codigooperacao,db83_tipoconta ",
                                              " db83_conta ",
                                              " db90_codban      ilike '{$oParam->sBanco}'    and
                                                db89_codagencia  ilike '{$oParam->sAgencia}'  and
                                                db83_conta       ilike '{$oParam->sConta}%'   and
                                                db83_contaplano is {$lPlanoConta}
                                              ");
    $rsContas   = $oDaoContaBancaria->sql_record($sSqlContas);
    $oContas    = db_utils::getCollectionByRecord($rsContas);

    foreach ($oContas as $oConta) {

      $oItensAutoComplete        = new stdClass();
      $oItensAutoComplete->cod   = $oJson->encode($oConta);
      $oItensAutoComplete->label = $oConta->db83_conta ."-".$oConta->db83_dvconta. "-Op:".$oConta->db83_codigooperacao;
      $aRetorno[]                = $oItensAutoComplete;
      unset($oItensAutoComplete);
    }
    $iRetorno = 1;
  break;
  case "salvarDados":

    try {

      db_inicio_transacao();
      $oRetorno->sDescricaoContaBancaria = '';

      $oContaBancaria = new ContaBancaria($oParam->oDados->iSequencialConta);
      $oContaBancaria->setDVAgencia       ($oParam->oDados->inputDvAgencia)
                     ->setNumeroAgencia   ($oParam->oDados->inputNumeroAgencia  )
                     ->setCodigoBanco     ($oParam->oDados->inputCodigoBanco)
                     ->setNumeroConta     ($oParam->oDados->inputNumeroConta)
                     ->setDVConta         ($oParam->oDados->inputDvConta)
                     ->setIdentificador   ($oParam->oDados->inputIdentificador)
                     ->setCodigoOperacao  ($oParam->oDados->inputOperacao)
                     ->setTipoConta       ($oParam->oDados->cboTipoConta)
                     ->setPlanoConta      ($oParam->oDados->lContaPlano)
                     ->salvar();
      $oRetorno->iSequencialContaBancaria = $oContaBancaria->getSequencialContaBancaria();
      $oRetorno->sDescricaoContaBancaria  = $oContaBancaria->getDadosConta();
      db_fim_transacao(false);
    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->message = $eErro->getMessage();
    }
    break;

  case "getDadosContaBancaria":

    try {

      $oContaBancaria    = new ContaBancaria($oParam->iCodigoConta);
      $oRetorno->iCodigoContaBancaria = $oParam->iCodigoConta;
      $oRetorno->iCodigoBanco         = $oContaBancaria->getCodigoBanco();
      $oRetorno->sDescricaoBanco      = $oContaBancaria->getDescricaoBanco();
      $oRetorno->iCodigoAgencia       = $oContaBancaria->getNumeroAgencia();
      $oRetorno->iDigitoAgencia       = $oContaBancaria->getDVAgencia();


    } catch (Exception $eErro) {

      $oRetorno->status = 2;
      $oRetorno->message = $eErro->getMessage();
    }

    break;


  case "getContasPorCodigoBanco":

    try {

      $sWhereContaBancaria  = "     db_bancos.db90_codban = '{$oParam->sCodigoBanco}' \n";
      $sWhereContaBancaria .= "     and db83_contaplano is true                       \n";

      $oDaoContaBancaria    = db_utils::getDao('contabancaria');
      $sSqlBuscaContas      = $oDaoContaBancaria->sql_query(null,
                                                           "distinct db83_identificador",
                                                           null,
                                                           $sWhereContaBancaria);
      $rsBuscaContas       = $oDaoContaBancaria->sql_record($sSqlBuscaContas);

      if ($oDaoContaBancaria->numrows == 0) {

        $sMsgErro  = "Não foi possível buscar os dados de conta bancária para o banco ";
        $sMsgErro .= "{$oParam->sCodigoBanco} - {$aContasBancarias[$oParam->sCodigoBanco]}.\n\n";
        $sMsgErro .= "Verifique cadastro de contas bancárias.";
        throw new BusinessException($sMsgErro);
      }

      $oRetorno->aContasBancarias = db_utils::getCollectionByRecord($rsBuscaContas);

    } catch (Exception $eErro) {

      $oRetorno->message = $eErro->getMessage();
      $oRetorno->status = 2;
    }

    break;
}
$oRetorno->message = urlencode($oRetorno->message);
if ($iRetorno == 1) {
  echo($oJson->encode($aRetorno));
} else {
  echo($oJson->encode($oRetorno));
}

?>