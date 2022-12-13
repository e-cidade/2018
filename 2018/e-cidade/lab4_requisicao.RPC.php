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
require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "buscarExames":

      $sWhere  = "     la21_i_requisicao = {$oParam->iRequisicao} ";
      $sWhere .= " and la21_c_situacao in ('6 - Coletado', '2 - Lancado') ";
      $sCampos = " la23_i_codigo, la23_c_descr, la21_i_codigo, la08_c_descr, la21_c_situacao ";

      $oDaoRequisicao = new cl_lab_requisicao();
      $sSqlExames     = $oDaoRequisicao->sql_query_coleta_amostra(null, $sCampos, "la23_c_descr, la08_c_descr", $sWhere);
      $rsExames       = db_query($sSqlExames);

      if (!$rsExames) {
        throw new DBException("Erro ao executar a query.\n".pg_last_error(), 1);
      }
      $iLinhas = pg_num_rows($rsExames);
      if ($iLinhas == 0) {
        throw new Exception("Requisição sem exames para digitação.");
      }

      $aSetorExame = array();
      for($i = 0; $i < $iLinhas; $i++) {

        $oDados = db_utils::fieldsMemory($rsExames, $i);

        if ( !array_key_exists($oDados->la23_i_codigo, $aSetorExame) ) {

          $oSetor          = new stdClass();
          $oSetor->sNome   = urlencode($oDados->la23_c_descr);
          $oSetor->iCodigo = $oDados->la23_i_codigo;
          $oSetor->aExames = array();

          $aSetorExame[ $oDados->la23_i_codigo ] = $oSetor;
        }

        $oExame            = new stdClass();
        $oExame->iCodigo   = $oDados->la21_i_codigo;
        $oExame->sNome     = urlencode($oDados->la08_c_descr);
        $oExame->lDigitado = $oDados->la21_c_situacao == '2 - Lancado';

        $aSetorExame[ $oDados->la23_i_codigo ]->aExames[] = $oExame;

      }
      $oRetorno->aExamesRequisicao = $aSetorExame;
      sort($oRetorno->aExamesRequisicao);

      break;
  }

  db_fim_transacao(false);

} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}

$oRetorno->erro = $oRetorno->iStatus == 2;
echo $oJson->encode($oRetorno);