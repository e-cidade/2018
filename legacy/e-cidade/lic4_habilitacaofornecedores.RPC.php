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
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once(modification("libs/JSON.php"));

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno           = new stdClass();
$oRetorno->mensagem = '';
$oRetorno->erro     = false;

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "getFornecedoresLicitacao":

      $iCodigoLicitacao = filter_var($oParam->codigo_licitacao, FILTER_VALIDATE_INT);
      if ($iCodigoLicitacao == false) {
        throw new ParameterException("O Código da Licitação não foi Informado.");
      }

      $sCampos = " pc20_codorc ";
      $sWhere  = " l20_codigo = {$iCodigoLicitacao} ";

      $oDaoOrcamItemLic = new cl_pcorcamitemlic();
      $sSqlOrcLicitacao = $oDaoOrcamItemLic->sql_query(null, $sCampos, null, $sWhere);

      $sCampos = " pc31_orcamforne as codigo, z01_nome as nome, l17_situacao as situacao ";
      $sWhere  = " pc21_codorc in ($sSqlOrcLicitacao) ";

      $oDaoFornecedoresLicitacao = new cl_pcorcamfornelic();
      $sSqlFornecedoresLicitacao = $oDaoFornecedoresLicitacao->sql_query(null, $sCampos, null, $sWhere);
      $rsFornecedoresLicitacao   = db_query($sSqlFornecedoresLicitacao);
      if ($rsFornecedoresLicitacao === false) {
        throw new DBException("Houve um erro ao buscar os fonecedores da licitação selecionada.");
      }

      $aFornecedores      = array();
      $iTotalFornecedores = pg_num_rows($rsFornecedoresLicitacao);
      for ($i = 0; $i < $iTotalFornecedores; $i++) {
        $aFornecedores[] = db_utils::fieldsMemory($rsFornecedoresLicitacao, $i, false, false, true);
      }

      $oRetorno->aFornecedores = $aFornecedores;

      break;

    case "salvarHabilitacao":

      if (empty($oParam->fornecedores)) {
        throw new ParameterException("Não há Fornecedores cadastrados para esta Licitação.");
      }

      foreach($oParam->fornecedores as $oFornecedor) {

        $oDaoHabilitacao = new cl_pcorcamfornelichabilitacao();
        if (!$oDaoHabilitacao->excluir(null, " l17_pcorcamfornelic = {$oFornecedor->codigo} ")) {
          throw new DBException("Houve um erro na exclusão da habilitação.");
        }

        if ($oFornecedor->situacao == 0) {
          continue;
        }

        $oDaoHabilitacao->l17_pcorcamfornelic = $oFornecedor->codigo;
        $oDaoHabilitacao->l17_situacao        = $oFornecedor->situacao;
        if (!$oDaoHabilitacao->incluir(null)) {
          throw new DBException("Houve um erro ao salvar a habilitação.");
        }
      }

      $oRetorno->mensagem = "Habilitação dos Fornecedores da Licitação salva com sucesso.";
      break;

    default:
      throw new Exception("Nenhuma opção definida.");
      break;
  }

  db_fim_transacao(false);
} catch (Exception $e) {

  $oRetorno->erro     = true;
  $oRetorno->mensagem = $e->getMessage();
  db_fim_transacao(true);
}
$oRetorno->mensagem = urlencode($oRetorno->mensagem);
echo $oJson->encode($oRetorno);