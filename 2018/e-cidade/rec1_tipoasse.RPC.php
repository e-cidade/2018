<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBselller Servicos de Informatica
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
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/JSON.php");

$oJson       = new services_json();
$oParametro  = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno    = (object)array( 'erro' => false, 'sMessage'=> '');

try {

  db_inicio_transacao();
  
  switch ($oParametro->exec) {

    case "vincularTipoAssentamentoAtributoDinamico":

	  if(!empty($oParametro->iAtributoDinamico) && !empty($oParametro->iTipoasse)) {

	  	$oDaoTipoassedb_cadattdinamico = new cl_tipoassedb_cadattdinamico();
	    $oDaoTipoassedb_cadattdinamico->h79_db_cadattdinamico = $oParametro->iAtributoDinamico;
	    $oDaoTipoassedb_cadattdinamico->h79_tipoasse          = $oParametro->iTipoasse;
	    $sWhereTipoasseCadDinamico     = "     h79_tipoasse          = {$oParametro->iTipoasse}";
	    $sSqlTipoAsseCadDinamico       = $oDaoTipoassedb_cadattdinamico->sql_query(null,null, 'h79_db_cadattdinamico', null, $sWhereTipoasseCadDinamico);
	    $rsTipoAsseCadDinamico         = db_query($sSqlTipoAsseCadDinamico);

	    if (!$rsTipoAsseCadDinamico) {
	      throw new DBException("Ocorreu um erro ao vincular o atributo dinâmico ao assentamento.");
	    }

	    if (pg_num_rows($rsTipoAsseCadDinamico) > 0) {
	      $oDaoTipoassedb_cadattdinamico->alterar($oParametro->iAtributoDinamico, $oParametro->iTipoasse);
	    } else {
	      $oDaoTipoassedb_cadattdinamico->incluir($oParametro->iAtributoDinamico, $oParametro->iTipoasse);
	    }

	    if ($oDaoTipoassedb_cadattdinamico->erro_sql == '0') {
	      throw new DBException($oDaoTipoassedb_cadattdinamico->erro_msg);
	    }

	  	$oRetorno->sMessage = "Atributo vinculado ao tipo de assentamento com sucesso.";
	  }
      break;
  }
  
  db_fim_transacao(false);  
  
} catch (Exception $eErro){
  
  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->iStatus  = false;
  $oRetorno->sMessage = $eErro->getMessage();
}

$oRetorno->sMensagem = urlencode($oRetorno->sMensagem);
echo $oJson->encode($oRetorno);