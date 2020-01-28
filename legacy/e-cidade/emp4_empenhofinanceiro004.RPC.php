<?php
/*
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
 */

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
require_once(modification('model/empenho/AutorizacaoEmpenho.model.php'));


$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

$iInstituicaoSessao = db_getsession('DB_instit');

try {

  switch ($oParam->exec) {

    case "validarRecursoDotacaoPorAutorizacao":

      $oRetorno->lFundeb = false;
      $oAutorizacaoEmpenho = new AutorizacaoEmpenho($oParam->iCodigoAutorizacaoEmpenho);
      if (!$oAutorizacaoEmpenho->getDotacaoOrcamentaria()) {
        throw new BusinessException('Autorização de empenho sem dotação vinculada.');
      }

      $iCodigoRecursoAutorizacao = $oAutorizacaoEmpenho->getDotacaoOrcamentaria()->getRecurso();
      $iCodigoFundebParametro    = ParametroCaixa::getCodigoRecursoFUNDEB($iInstituicaoSessao);

      $lRecursoFundeb = false;
      if ($iCodigoFundebParametro == $iCodigoRecursoAutorizacao) {
        $lRecursoFundeb = true;
      }

      $oRetorno->lFundeb = $lRecursoFundeb;
      break;

    case "getFinalidadePagamentoFundebEmpenho":

      $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oParam->iSequencialEmpenho);
      $iRecursoDotacao    = $oEmpenhoFinanceiro->getDotacao()->getRecurso();
      $oFinalidade        = $oEmpenhoFinanceiro->getFinalidadePagamentoFundeb();

      $oRetorno->lPossuiFinalidadePagamentoFundeb = false;
      if (!empty($oFinalidade)) {

        $oRetorno->lPossuiFinalidadePagamentoFundeb            = true;
        $oRetorno->oFinalidadePagamentoFundeb                  = new stdClass();
        $oRetorno->oFinalidadePagamentoFundeb->e151_sequencial = $oFinalidade->getCodigoSequencial();
        $oRetorno->oFinalidadePagamentoFundeb->e151_codigo     = $oFinalidade->getCodigo();
        $oRetorno->oFinalidadePagamentoFundeb->e151_descricao  = urlencode($oFinalidade->getDescricao());

      } else if ($iRecursoDotacao === ParametroCaixa::getCodigoRecursoFUNDEB($iInstituicaoSessao))  {

        $oRetorno->lPossuiFinalidadePagamentoFundeb = true;
      }
      break;
  }

} catch (Exception $oErro) {

  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($oErro->getMessage());
}

$oRetorno->erro    = $oRetorno->iStatus === 2;
$oRetorno->message = $oRetorno->sMessage;

echo $oJson->encode($oRetorno);
