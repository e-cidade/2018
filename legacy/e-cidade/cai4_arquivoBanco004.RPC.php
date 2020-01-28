<?php
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

//cai4_arquivoBanco004.RPC.php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/exceptions/BusinessException.php"));
require_once(modification("libs/exceptions/DBException.php"));
require_once(modification("libs/exceptions/ParameterException.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/patrimonio/Inventario.model.php"));
require_once(modification("model/configuracao/DBDepartamento.model.php"));
require_once(modification("model/configuracao/DBDivisaoDepartamento.model.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBNumber.php"));
require_once(modification("model/dbLayoutReader.model.php"));
require_once(modification("model/dbLayoutLinha.model.php"));

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

db_inicio_transacao();
try {

  switch ($oParam->exec) {

  	case "cancelarMovimentos" :

  	  $oArquivo     = ArquivoTransmissao::getInstance($oParam->iArquivo);
  	  $iAno         = $oArquivo->getDataGeracaoArquivo()->getAno();
  	  $iInstituicao = $oArquivo->getInstituicao()->getSequencial();

  		foreach ($oParam->aMovimentos as $iCodigoMovimento) {

    	  $oMovimento = MovimentoArquivoTransmissao::getInstance($iCodigoMovimento, $iAno, $iInstituicao);
    	  $oArquivo->desvincularMovimento($oMovimento);
  		}
  		$oRetorno->sMessage = "Movimento desvinculado com sucesso";
  		db_fim_transacao(false);
  	break;

  	case "getMovimentosVinculados" :

  		$aDados   = array();
  		$oArquivo = ArquivoTransmissao::getInstance($oParam->iArquivo);

  		foreach ($oArquivo->getMovimentos() as $oMovimento) {

        if ($oMovimento->getProcessado()) {
          continue;
        }

        $sEmpenho = $oMovimento->getNumeroEmpenho();
        $sOrdem   = $oMovimento->getCodigoOrdem();
        $sSlip    = $oMovimento->getCodigoSlip();
        $oDado                   = new stdClass();
        $oDado->iMovimento       =  $oMovimento->getCodigoMovimento();
        $oDado->iEmpenho         =  $sEmpenho;
        $oDado->iSlip            =  $sSlip.$sOrdem;

  		  $sFornecedor = $oMovimento->getNome();

  		  if (empty($sFornecedor)) {
  		    $sFornecedor = urlencode("Pagamento por código de barras");
  		  }
  		  $oDado->sNome            =  $sFornecedor;

  		  $oDBDate = new DBDate($oMovimento->getDataGeracao());
  		  $oDado->dtEmissao        = $oDBDate->getDate(DBDate::DATA_PTBR) ;
  		  $oDado->nValor           =  $oMovimento->getValorInteiro();
  		  $oDado->sContaPagadora   =  $oMovimento->getContaPagadora();
  		  $aDados[] = $oDado;
  		}
  		$oRetorno->aDados = $aDados;
  		db_fim_transacao(false);
  	break;

    case "gerarArquivoTXT":

      $dtGeracaoArquivo       = implode("-", array_reverse(explode("/", $oParam->dtGeracaoArquivo)));
      $dtAutorizacaoPagamento = implode("-", array_reverse(explode("/", $oParam->dtAutorizacaoPagamento)));
      $oGeradorArquivoOBN = new GeradorArquivoOBN();
      $oGeradorArquivoOBN->setDescricao($oParam->sDescricaoArquivo);
      $oGeradorArquivoOBN->setDataGeracao($dtGeracaoArquivo);
      $oGeradorArquivoOBN->setDataAutorizacaoPagamento($dtAutorizacaoPagamento);
      $oGeradorArquivoOBN->setHoraGeracao(db_hora());
      $oGeradorArquivoOBN->setInstituicao(new Instituicao(db_getsession("DB_instit")));
      $oGeradorArquivoOBN->setAno(db_getsession("DB_anousu"));
      $oGeradorArquivoOBN->setCodigoRemessa(null);
      $oGeradorArquivoOBN->construirRemessa(explode(",", $oParam->sMovimentos));
      $oRetorno->sArquivo = $oGeradorArquivoOBN->getLocalizacao();

      db_fim_transacao(false);

    break;

    case "regerarArquivoObn" :

    	$iCodGera               = $oParam->iCodGera  ;
    	$dtGeracaoArquivo       = $oParam->dtGeracao ;
    	$dtAutorizacaoPagamento = $oParam->dtAutoriza;
    	$oGeradorArquivoOBN     = new GeradorArquivoOBN();

    	$oGeradorArquivoOBN->setCodigoRemessa($iCodGera);
    	$oGeradorArquivoOBN->setDataGeracao($dtGeracaoArquivo);
    	$oGeradorArquivoOBN->setDataAutorizacaoPagamento($dtAutorizacaoPagamento);
    	$oGeradorArquivoOBN->setHoraGeracao(db_hora());
    	$oGeradorArquivoOBN->setAno(db_getsession("DB_anousu"));
    	$oGeradorArquivoOBN->setInstituicao(new Instituicao(db_getsession("DB_instit")));
    	$oGeradorArquivoOBN->regerarArquivo();
        /* [Inicio plugin GeracaoArquivoOBN  - Salvar Geracao Arquivo TXT OBN - parte1] */
        /* [Fim plugin GeracaoArquivoOBN  - Salvar Geracao Arquivo TXT OBN - parte1] */
      $oRetorno->sArquivo = $oGeradorArquivoOBN->getLocalizacao();
      db_fim_transacao(false);

    break;

    default:
      throw new BusinessException("Não localizado case para execução no RPC.");
  }

} catch (Exception $eErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus      = 2;
  $oRetorno->sMessage     = urlencode($eErro->getMessage());
}

$oRetorno->erro     = $oRetorno->iStatus == 2;
$oRetorno->mensagem = $oRetorno->sMessage;

echo $oJson->encode($oRetorno);
