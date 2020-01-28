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

require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("std/db_stdClass.php");

$oJson             = new services_json();
$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->mensagem = '';
$oRetorno->erro    = false;
$sMensagem         = "";

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case 'getCotasMensais' :

      $oEmpenho = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorNumero($oParam->iNumeroEmpenho);
      $aCotasMensais = $oEmpenho->getCotasMensais();
      $oRetorno->cotas = array();
      $lCotaMensais = $oEmpenho->temCotaMensais();
      $nValorCota = 0;
      $oRetorno->valorempenho = $oEmpenho->getValorEmpenho();
      $iMesInicio = 0;
      $nAcertoCota = 0;
      if (!$lCotaMensais) {

        $oDataAtual = new DBDate($oEmpenho->getDataEmissao());
        $iMesInicio = $oDataAtual->getMes();
        $iCotas = 12 - ($iMesInicio) + 1;
        $nValorCota = 0;
        $nAcertoCota = $oEmpenho->getValorEmpenho() - round(($nValorCota * $iCotas), 2);
      }

      $oDataEmpenho = new DBDate($oEmpenho->getDataEmissao());
      $nValorREversoCotas = 0;

      /* Extensão CotaMensalLiquidacao - Parte 1 */

      foreach ($aCotasMensais as $oCotaMensal) {

        $oCota = new StdClass();
        $oCota->nome_mes = urlencode(db_mes($oCotaMensal->getMes(), 2));
        $oCota->mes = urlencode($oCotaMensal->getMes());
        $oCota->valor = $oCotaMensal->getValor();
        $oCota->permitealterar = $oCotaMensal->getMes() >= $oDataEmpenho->getMes();
        if (!$lCotaMensais) {
          $oCota->valor = 0;
        }

        /* Extensão CotaMensalLiquidacao - Parte 2 */

        $oRetorno->cotas[] = $oCota;
      }


      /* Extensão CotaMensalLiquidacao - Parte 3 */
      break;

    case  'salvar' :

      $oEmpenho = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorNumero($oParam->iNumeroEmpenho);
      $aCotas = array();
      foreach ($oParam->cotas as $oCota) {

        $oCotaMensal = new EmpenhoCotaMensal();
        $oCotaMensal->setMes($oCota->mes);
        $oCotaMensal->setValor($oCota->valor);
        $aCotas[] = $oCotaMensal;
      }
      $oEmpenho->adicionarCotas($aCotas);
      $oRetorno->mensagem = 'Cotas Mensais salvas com sucesso.';
      break;
  }

  db_fim_transacao(false);

} catch (Exception $e) {

  $oRetorno->mensagem = $e->getMessage();
  $oRetorno->erro    = true;
}

$oRetorno->mensagem = urlencode($oRetorno->mensagem);
echo $oJson->encode($oRetorno);