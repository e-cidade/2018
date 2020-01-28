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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("std/db_stdClass.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_libcontabilidade.php");

$oJson    = new Services_JSON();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno = new stdClass();

$oRetorno->dados   = array();
$oRetorno->erro    = false;
$oRetorno->message = '';

$oInstituicao = new Instituicao(db_getsession("DB_instit"));
$iAnoUsu      = db_getsession("DB_anousu");
$oUsuario     = UsuarioSistemaRepository::getPorCodigo(db_getsession('DB_instit'));

try {

  db_inicio_transacao();
  switch ($oParam->exec) {

    case 'getContasPorEstrutural':

      $oRetorno->contas = array();
      $aContas          = ContaPlanoPCASPRepository::getContasPorEstrutural($oParam->estrutural, $oInstituicao, $iAnoUsu);
      foreach ($aContas as $oConta) {

        $oDadosConta             = new stdClass();
        $oDadosConta->codigo     = $oConta->getCodigoConta();
        $oDadosConta->estrutural = urlencode(db_formatar($oConta->getEstrutural(), 'receita'));
        $oDadosConta->descricao  = urlencode($oConta->getDescricao());
        $oDadosConta->reduzido   = $oConta->getReduzido();
        $oRetorno->contas[]      = $oDadosConta;
      }
      break;

    case 'alterarDadosEstrutural':

      $iTotalContasSelecionadas = count($oParam->contas);

      foreach ($oParam->contas as $oConta) {

        if (!DBNumber::isInteger($oConta->reduzido)) {
          $oConta->reduzido = null;
        }

        $oContaPCASP = ContaPlanoPCASPRepository::getContaByCodigo(
          $oConta->codigo,
          $iAnoUsu,
          $oConta->reduzido
        );

        if ($oContaPCASP->sintetica() && $iTotalContasSelecionadas > 1) {
          throw new Exception("Você só pode selecionar uma conta sintética.");
        }

        $sEstruturalAntigo = $oContaPCASP->getEstrutural();

        $iUltimoAnoPlano = $oContaPCASP->getUltimoAnoPlano('c60_codcon = '.$oContaPCASP->getCodigoConta());
        $sEstruturalNovo = $oContaPCASP->getEstrutural();
        if (!empty($oParam->valor_nivel)) {
          $sEstruturalNovo = alterarEstrutural($oContaPCASP->getEstruturalComMascara(), $oParam->nivel, $oParam->valor_nivel);
        }

        for ($iAnoAlterar = $iAnoUsu; $iAnoAlterar <= $iUltimoAnoPlano; $iAnoAlterar++) {

          $oDaoConplano = new cl_conplano();
          $oDaoConplano->c60_codcon = $oContaPCASP->getCodigoConta();
          $oDaoConplano->c60_anousu = $iAnoAlterar;
          $oDaoConplano->c60_descr  = db_stdClass::normalizeStringJsonEscapeString($oConta->descricao);
          $oDaoConplano->c60_estrut = str_replace('.', '', $sEstruturalNovo);
          $oDaoConplano->alterar($oContaPCASP->getCodigoConta(), $iAnoAlterar);
          if ($oDaoConplano->erro_status == "0") {
            throw new Exception("Não foi possível alterar os dados da conta {$oContaPCASP->getEstruturalComMascara()}.\n\nEstrutural já cadastrado no sistema.");
          }
        }

        /**
         * verifica se existe contas abaixo da conta selecionada, caso exista, altera os estruturais das contas filhas
         */
        if ($oContaPCASP->sintetica() && !empty($oParam->valor_nivel)) {

          $iNivelConta = ContaPlano::getNivelEstrutura(db_formatar($oConta->estrutural, 'receita'));
          $sEstruturalAteNivel = str_replace(".", '', $oContaPCASP->getEstruturaAteNivel($oContaPCASP->getEstruturalComMascara(), $iNivelConta));
          $aContasFilhas = ContaPlanoPCASPRepository::getContasPorEstrutural($sEstruturalAteNivel, $oInstituicao, $iAnoUsu);

          foreach ($aContasFilhas as $oContaFilha) {

            if ($sEstruturalAntigo > $oContaFilha->getEstrutural()) {
              continue;
            }

            $sEstruturalRetorno = alterarEstrutural($oContaFilha->getEstruturalComMascara(), $oParam->nivel, $oParam->valor_nivel);
            $iUltimoAnoPlano    = $oContaPCASP->getUltimoAnoPlano('c60_codcon = '.$oContaFilha->getCodigoConta());
            for ($iAnoAlterar = $iAnoUsu; $iAnoAlterar <= $iUltimoAnoPlano; $iAnoAlterar++) {

              $oDaoConplano = new cl_conplano();
              $oDaoConplano->c60_codcon = $oContaFilha->getCodigoConta();
              $oDaoConplano->c60_anousu = $iAnoAlterar;
              $oDaoConplano->c60_estrut = str_replace('.', '', $sEstruturalRetorno);
              $oDaoConplano->alterar($oContaFilha->getCodigoConta(), $iAnoAlterar);
              if ($oDaoConplano->erro_status == "0") {
                throw new Exception("Não foi possível alterar o estrutural da conta {$oContaPCASP->getEstruturalComMascara()}.\n\nEstrutural já cadastrado no sistema.");
              }
            }
            $oContaFilha->setEstrutural($sEstruturalRetorno);
            $oContaPCASP->setEstrutural($sEstruturalNovo);
            if ($oUsuario->getLogin() != "dbseller") {
              $oContaPCASP->validarEstrutural();
            }
          }
        }

        if (!empty($oParam->valor_nivel)) {

          $oContaPCASP->setEstrutural($sEstruturalNovo);
          if ($oUsuario->getLogin() != "dbseller") {
            $oContaPCASP->validarEstrutural();
          }
        }
      }

      break;


    case 'visualizarAlteracao':

      $oInstituicao = InstituicaoRepository::getInstituicaoSessao();
      $iAnoSessao   = db_getsession('DB_anousu');
      $iTotalContasSelecionadas = count($oParam->aContas);
      $aContasRetorno = array();
      foreach ($oParam->aContas as $oStdDadosConta) {

        $sEstruturalNovo = str_replace('.', '', $oStdDadosConta->estrutural);
        $oContaPCASP = ContaPlanoPCASPRepository::getContaPorEstrutural($sEstruturalNovo, $iAnoSessao, $oInstituicao);

        if ($oContaPCASP->sintetica() && $iTotalContasSelecionadas > 1) {
          throw new Exception("Você só pode selecionar uma conta sintética.");
        }

        if ($oContaPCASP->sintetica()) {

          $iNivelConta = ContaPlano::getNivelEstrutura($oStdDadosConta->estrutural);
          $sEstruturalAteNivel = str_replace(".", '', $oContaPCASP->getEstruturaAteNivel($oStdDadosConta->estrutural, $iNivelConta));
          $aContasFilhas = ContaPlanoPCASPRepository::getContasPorEstrutural($sEstruturalAteNivel, $oInstituicao, $iAnoSessao);

          foreach ($aContasFilhas as $oContaFilha) {

            if ($sEstruturalNovo > $oContaFilha->getEstrutural()) {
              continue;
            }
            $sEstruturalRetorno = alterarEstrutural($oContaFilha->getEstruturalComMascara(), $oParam->iNivel, $oParam->iValorNivel);
            $oStdRetorno = new stdClass();
            $oStdRetorno->codigo_conta    = $oContaFilha->getCodigoConta();
            $oStdRetorno->estrutural_novo = $sEstruturalRetorno;
            $aContasRetorno[] = $oStdRetorno;
          }
        } else {

          $sEstruturalRetorno = alterarEstrutural($oStdDadosConta->estrutural, $oParam->iNivel, $oParam->iValorNivel);
          $oStdRetorno = new stdClass();
          $oStdRetorno->codigo_conta    = $oStdDadosConta->codigo_conta;
          $oStdRetorno->estrutural_novo = $sEstruturalRetorno;
          $aContasRetorno[] = $oStdRetorno;
        }
      }
      $oRetorno->contas = $aContasRetorno;
      break;
  }

  db_fim_transacao(false);

} catch (Exception $oException) {

  db_fim_transacao(true);
  $oRetorno->erro = true;
  $oRetorno->message = urlencode($oException->getMessage());
}
echo $oJson->encode($oRetorno);

/**
 * @param $sEstrutural
 * @param $iNivel
 * @param $sNovoValor
 *
 * @return string
 */
function alterarEstrutural ($sEstrutural, $iNivel, $sNovoValor) {

  if ($iNivel >= 6) {
    $sNovoValor = str_pad($sNovoValor, 2, '0', STR_PAD_LEFT);
  }

  $aEstruturalFilho = explode(".", $sEstrutural);
  $aEstruturalFilho[($iNivel-1)] = $sNovoValor;
  return implode('.', $aEstruturalFilho);
}