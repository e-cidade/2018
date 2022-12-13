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

$oPost       = db_utils::postMemory($_REQUEST);

$oPost->json = str_replace("\\","",$oPost->json);
$oParam      = JSON::create()->parse($oPost->json);
$oRetorno    = (object)array( 'erro' => false, 'mensagem'=> '');

try {

  db_inicio_transacao();
  
  switch ($oParam->exec) {

    case "getTabelasPrevidencia":

    		$clinssirf           = new cl_inssirf();

				$sCamposPrevidencia  = ' distinct (cast(r33_codtab as integer)-2) as codigo,';
				$sCamposPrevidencia .= ' (cast(r33_codtab as integer)-2) || \' - \' ||array_to_string(array_accum(distinct trim(r33_nome)), \', \') as nome';
				$sOrderPrevidencia   = ' codigo';
				$sWherePrevidencia   = '     r33_instit IN ('. $oParam->sInstituicoes .')';
				$sWherePrevidencia  .= ' and r33_anousu = fc_anofolha('. db_getsession("DB_instit") .')';
				$sWherePrevidencia  .= ' and r33_mesusu = fc_mesfolha('. db_getsession("DB_instit") .')';
				$sWherePrevidencia  .= ' and r33_codtab > 2';
				$sGroupPrevidencia   = ' group by r33_codtab';

    		$sSqlPrevidencia = $clinssirf->sql_query_file(null, null, $sCamposPrevidencia, $sOrderPrevidencia, $sWherePrevidencia.$sGroupPrevidencia);
      	$rsPrevidencia   = db_query($sSqlPrevidencia);

      	if(!$rsPrevidencia) {
      		throw new DBException("Ocorreu um erro ao consultar as tabelas de previdência.");
      	}

      	$oRetorno->aTabelasPrevidencia = array();
      	if(pg_num_rows($rsPrevidencia) > 0) {

      		$oRetorno->aTabelasPrevidencia = db_utils::makeCollectionFromRecord($rsPrevidencia, function ($oTabelaPrevidencia) {
      			return $oTabelaPrevidencia;
      		});
      	}

      break;
  }
  
  db_fim_transacao(false);

} catch (Exception $eErro){
  
  db_fim_transacao(true);

  $oRetorno->erro     = true;
  $oRetorno->status   = false;
  $oRetorno->mensagem = $eErro->getMessage();
}

echo JSON::create()->stringify($oRetorno);