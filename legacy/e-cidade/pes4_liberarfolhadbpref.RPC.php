<?php

/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
 * 
 * @author Luiz Marcelo Schmitt <luiz.marcelo@dbseller.com.br>
 * @version $Revision: 1.6 $
 */

require_once 'std/db_stdClass.php';
require_once 'libs/db_stdlib.php';
require_once 'libs/db_utils.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/JSON.php';
require_once 'dbforms/db_funcoes.php';

$oJson    = new Services_JSON();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno = new stdClass();

$oRetorno->dados   = array();
$oRetorno->status  = true;
$oRetorno->message = '';

define("ARQUIVO_MENSAGEM", "recursoshumanos.pessoal.pes4_liberarfolhadbpref.");

try {
 
  switch ($oParam->exec) {
    
    /**
     * Somente serão processadas as folhas de adiantamento, rescisão e 13º salário,
     * as outras folhas já existem rotinas que fazem este processamento.
     */
    case 'salvar':

      $aTiposFolhas  = $oParam->selecionados;
      
      if (isset($aTiposFolhas->adiantamento)) {
        
        $oFolhaAdiantamento = FolhaPagamentoAdiantamento::getUltimaFolha();

        /**
         * Verifica se o estado de liberação
         * da folha foi alterado.
         */
        if ($oFolhaAdiantamento->isAberto() == $aTiposFolhas->adiantamento) {

          if ($aTiposFolhas->adiantamento) {

            $oFolhaAdiantamento->fechar();
            $oRetorno->message .= 'Folha de adiantamento liberada.\\n';
          } else {
            
            $oFolhaAdiantamento->setFolhaAberta();
            $oFolhaAdiantamento->salvar();
            $oRetorno->message .= 'Folha de adiantamento não liberada.\\n';
          }
        }
      }
      
      if (isset($aTiposFolhas->rescisao)) {
        
        $oFolhaRescisao     = FolhaPagamentoRescisao::getUltimaFolha();

        /**
         * Verifica se o estado de liberação
         * da folha foi alterado.
         */
        if ($oFolhaRescisao->isAberto() == $aTiposFolhas->rescisao) {

          if ($aTiposFolhas->rescisao) {

            $oFolhaRescisao->fechar();
            $oRetorno->message .= 'Folha de rescisão liberada.\\n';
          } else {
            
            $oFolhaRescisao->setFolhaAberta();
            $oFolhaRescisao->salvar();
            $oRetorno->message .= 'Folha de rescisão não liberada.\\n';
          }
        }
      }
      
      if (isset($aTiposFolhas->salario13)) {
        
        $oFolha13oSalario   = FolhaPagamento13o::getUltimaFolha();

        /**
         * Verifica se o estado de liberação
         * da folha foi alterado.
         */
        if ($oFolha13oSalario->isAberto() == $aTiposFolhas->salario13) {

          if ($aTiposFolhas->salario13) {

            $oFolha13oSalario->fechar();
            $oRetorno->message .= 'Folha de 13º salário liberada.\\n';
          } else {
            
            $oFolha13oSalario->setFolhaAberta();
            $oFolha13oSalario->salvar();
            $oRetorno->message .= 'Folha de 13º salário não liberada.\\n';
          }
        }
      }

      if (!empty($oRetorno->message)) {
        $oRetorno->message = urlencode($oRetorno->message);
      }

      break;

    /**
     * Retornando "null" irá desabilitar os checkbox.
     */  
    case 'getCarregarFolhas':
       
      $oCompetencia  = DBPessoal::getCompetenciaFolha();
      $lAdiantamento = FolhaPagamentoAdiantamento::hasFolha();
      $lSalario      = verificarFolha($oCompetencia, FolhaPagamento::TIPO_FOLHA_SALARIO);
      $lSuplementar  = verificarFolha($oCompetencia, FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR);
      $lComplementar = verificarFolha($oCompetencia, FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR);
      $l13Salario    = FolhaPagamento13o::hasFolha();
      $lRescisao     = FolhaPagamentoRescisao::hasFolha();

      if ($l13Salario) {
        
        $iCodigo                 = FolhaPagamento::getCodigoFolha(FolhaPagamento::TIPO_FOLHA_13o_SALARIO, null, $oCompetencia);
        $oFolha13o               = new FolhaPagamento13o($iCodigo);
        $lTemCalculo13o          = CalculoFolha::hasCalculo($oFolha13o);
        $lFolhaPagamentoAberta   = FolhaPagamento13o::hasFolhaAberta($oCompetencia);
        
        if(!$lTemCalculo13o) {
          $l13Salario = null;
        }

        if ($lTemCalculo13o && $lFolhaPagamentoAberta) {
          $l13Salario = false;
        }

      } else {
        $l13Salario = null;
      }

      if ($lAdiantamento) {
        
        $iCodigo                 = FolhaPagamento::getCodigoFolha(FolhaPagamento::TIPO_FOLHA_ADIANTAMENTO, null, $oCompetencia);
        $oFolhaAdiantamento      = new FolhaPagamentoAdiantamento($iCodigo);
        $lTemCalculoAdiantamento = CalculoFolha::hasCalculo($oFolhaAdiantamento);
        $lFolhaPagamentoAberta   = FolhaPagamentoAdiantamento::hasFolhaAberta($oCompetencia);
        
        if(!$lTemCalculoAdiantamento) {
          $lAdiantamento = null;
        }

        if ($lTemCalculoAdiantamento && $lFolhaPagamentoAberta) {
          $lAdiantamento = false;
        }

      } else {
        $lAdiantamento = null;
      }

      if ($lRescisao) {
        
        $iCodigo               = FolhaPagamento::getCodigoFolha(FolhaPagamento::TIPO_FOLHA_RESCISAO, null, $oCompetencia);
        $oFolhaRescisao        = new FolhaPagamentoRescisao($iCodigo);
        $lTemCalculoRescisao   = CalculoFolha::hasCalculo($oFolhaRescisao);
        $lFolhaPagamentoAberta = FolhaPagamentoRescisao::hasFolhaAberta($oCompetencia);

        if(!$lTemCalculoRescisao) {
          $lRescisao = null;
        }

        if($lTemCalculoRescisao && $lFolhaPagamentoAberta) {
          $lRescisao = false;
        }
        
      } else {
        $lRescisao = null;
      }
    
      $aTiposFolhasAbertas = array (
        "adiantamento" => $lAdiantamento,
        "salario"      => $lSalario,
        "suplementar"  => $lSuplementar,
        "complementar" => $lComplementar,
        "rescisao"     => $lRescisao,
        "13salario"    => $l13Salario
      );
      
      if(is_null($lAdiantamento) && is_null($l13Salario) && is_null($lRescisao)) {
        $oRetorno->message = urlencode('Não existe cadastro para as folhas adiantamento, 13º salário e rescisão para esta competência. Por este motivo as folhas estão indisponíveis para liberação no e-Cidade Online.');
      }
      
      $oRetorno->dados = $aTiposFolhasAbertas;

      break;
  }
} catch (Exception $oErro) {
  
  db_fim_transacao(true);
  $oRetorno->message = $oErro->getMessage();
  $oRetorno->status  = false;
}

echo $oJson->encode($oRetorno);


/**
 * Verifica se a folha de pagamento esta fechado ou aberta,
 * senão existir folha de pagamento retornará null.
 * 
 * @param DBCompetencia $oCompetencia
 * @param Integer $iTipoFolha
 * @return Boolean
 */
function verificarFolha(DBCompetencia $oCompetencia, $iTipoFolha) {
  
  $lFolhaPagamento  = null;
  $aFolhasPagamento = FolhaPagamento::getFolhaCompetenciaTipo($oCompetencia, $iTipoFolha);  
  
  if ($aFolhasPagamento) {
    
    $iIndice         = sizeof($aFolhasPagamento) - 1;
    $lFolhaPagamento = !$aFolhasPagamento[$iIndice]->isAberto();
  }
  
  return $lFolhaPagamento;
}
