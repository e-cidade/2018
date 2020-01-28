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
require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_app.utils.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("libs/JSON.php"));

define("MSG_LAB_MEDICAMENTOSLABORATORIO", "saude.laboratorio.lab4_medicamentoslaboratorio_RPC.");

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->iStatus = 1;

try {

  switch ($oParam->exec) {

    case 'salvar':

      if ( empty($oParam->sNome) ){
        throw new ParameterException( _M(MSG_LAB_MEDICAMENTOSLABORATORIO . "informe_nome_medicamento") );
      }

      $iCodigoMedicamento           = empty($oParam->iCodigo) ? null : $oParam->iCodigo;

      $oMedicamentoLaboratorioModel = new MedicamentoLaboratorio($iCodigoMedicamento);
      $oMedicamentoLaboratorioModel->setAbreviatura(db_stdClass::normalizeStringJsonEscapeString($oParam->sAbreviatura));
      $oMedicamentoLaboratorioModel->setNome(db_stdClass::normalizeStringJsonEscapeString($oParam->sNome));
      $oMedicamentoLaboratorioModel->salvar();

      $oRetorno->sMessage = urlencode(_M( MSG_LAB_MEDICAMENTOSLABORATORIO . "medicamento_salvo" ));

      break;

    case 'buscar':

      $oDaoMedicamentosLaboratorio        = new cl_medicamentoslaboratorio();
      $aMedicamentos                      = array();

      $sCampos  = " la43_sequencial, la43_nome, la43_abreviatura, ";
      $sCampos .= " exists ((select distinct 1 from medicamentoslaboratoriorequiitem where la44_medicamentoslaboratorio = la43_sequencial)) as vinculado_exames ";
      $sSqlMedicamentosLaboratorio = $oDaoMedicamentosLaboratorio->sql_query_file(null, $sCampos, null, null);
      $rsMedicamentosLaboratorio   = db_query($sSqlMedicamentosLaboratorio);
      $iTotalMedicamentos          = pg_num_rows($rsMedicamentosLaboratorio);

      if ( !$rsMedicamentosLaboratorio ){
        throw new DBException( _M(MSG_LAB_MEDICAMENTOSLABORATORIO . "erro_buscar_medicamentos") . "\n {$oDaoMedicamentosLaboratorio->erro_msg}" );
      }

      for ($iContador = 0; $iContador < $iTotalMedicamentos; $iContador++) {

        $oMedicamento = db_utils::fieldsMemory($rsMedicamentosLaboratorio, $iContador);

        $oDadosMedicamento               = new stdClass();
        $oDadosMedicamento->iCodigo      = $oMedicamento->la43_sequencial;
        $oDadosMedicamento->sMedicamento = urlencode($oMedicamento->la43_nome);
        $oDadosMedicamento->sAbreviatura = urlencode($oMedicamento->la43_abreviatura);
        $oDadosMedicamento->lEditavel    = $oMedicamento->vinculado_exames == 'f' ? true : false;

        $aMedicamentos[] = $oDadosMedicamento;
      }

      $oRetorno->aMedicamentos = $aMedicamentos;

      break;

    case 'excluir':

      if ( empty($oParam->iCodigo) ){
        throw new ParameterException( _M(MSG_LAB_MEDICAMENTOSLABORATORIO . "informe_codigo_medicamento") );
      }

      $oDaoMedicamentosLaboratorio = new cl_medicamentoslaboratorio();
      $oDaoMedicamentosLaboratorio->excluir($oParam->iCodigo);

      if ( $oDaoMedicamentosLaboratorio->erro_status == 0 ) {
        throw new DBException( _M(MSG_LAB_MEDICAMENTOSLABORATORIO . "falha_excluir_medicamento") . "\n {$oDaoMedicamentosLaboratorio->erro_msg}" );
      }

      $oRetorno->sMessage = urlencode(_M(MSG_LAB_MEDICAMENTOSLABORATORIO . "medicamento_excluido"));

      break;

    default:
      break;
  }

} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($oErro->getMessage());
}

$oRetorno->erro = $oRetorno->iStatus == 2;

echo $oJson->encode($oRetorno);
