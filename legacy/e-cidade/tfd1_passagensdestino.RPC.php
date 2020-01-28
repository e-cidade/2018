<?php 
/**
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
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_utils.php");
require_once modification("dbforms/db_funcoes.php");

$oPost       = db_utils::postMemory($_REQUEST);

if (!isset($oPost->json)) {
  return terminate(403);
}

$oPost->json = str_replace("\\","",$oPost->json);
$oParametro  = JSON::create()->parse($oPost->json);
$oRetorno    = (object)array( 'erro' => false, 'mensagem'=> '');

try {

  db_inicio_transacao();

  switch ($oParametro->exec) {
  
    case 'getValoresDestinos':
      $oRetorno->valores = getValoresDestinos();
      break;
    case 'salvar':
      $oRetorno->item = salvar($oParametro->item);
      break;
    case 'excluir':
      excluir($oParametro->item);
      break;
    default:
      terminate(404);
      return;
  }
  db_fim_transacao(false);
} catch(Exception $e) {

  $oRetorno->erro = true;
  $oRetorno->mensagem = $e->getMessage();
  terminate(500);
  db_fim_transacao(true);
}

echo JSON::create()->stringify($oRetorno);



/**
 * Funções 
 */

function getValoresDestinos() {

  $oDao  = new cl_passagemdestino();
  $sSql  = $oDao->sql_query(null, "tf37_sequencial, tf37_valor, tf37_destino, tf37_destino, tf03_c_descr");
  $rsSql = db_query($sSql);

  if (!$rsSql) {
    throw new DBException("Erro ao buscar valores dos destinos.");
  }
  return db_utils::getCollectionByRecord($rsSql);
}

function salvar($item) {

  $codigoDestino         = $item->tf37_destino + 0;

  $oDao = new cl_passagemdestino();

  $sWhere = "tf37_destino = {$codigoDestino}";

  if ($item->tf37_sequencial) {
    $sWhere .= " and tf37_sequencial <> {$item->tf37_sequencial}";
  }

  $sSql        = $oDao->sql_query_file(null, '1', null, $sWhere);
  $rsValidacao = db_query($sSql);

  if (!$rsValidacao) {
    throw new DBException("Erro ao verificar existência de passagem para o destino({$item->tf03_c_descr}).");
  }

  if (pg_num_rows($rsValidacao) > 0){
    throw new BusinessException("Valor de passagem já lançado para o destino({$item->tf03_c_descr}).");
  }


  $oDao->tf37_valor      = $item->tf37_valor;
  $oDao->tf37_destino    = $codigoDestino;
  
  if ($item->tf37_sequencial) {//Caractere coringa do front quando não existe sequencial

    $oDao->tf37_sequencial = $item->tf37_sequencial;
    $oDao->alterar($oDao->tf37_sequencial);
  } else {
    $oDao->tf37_sequencial = null;
    $oDao->incluir(null);
  }

  if ($oDao->erro_status == "0") {
    throw new DBException("Erro ao salvar o valor da passagem para o Destino({$item->tf03_c_descr}).");
  }

  $oRetorno = new \stdClass();
  $oRetorno->tf37_sequencial = $oDao->tf37_sequencial;
  $oRetorno->tf37_valor      = $oDao->tf37_valor;
  $oRetorno->tf37_destino    = $oDao->tf37_destino;
  $oRetorno->tf03_c_descr    = $item->tf03_c_descr;
  return $oRetorno;
}

function excluir($item) {
  
  $oDao = new cl_passagemdestino();
  $oDao->tf37_sequencial = $item->tf37_sequencial + 0;
  $oDao->excluir($item->tf37_sequencial + 0);

  if($oDao->erro_status == "0") {
    throw new DBException("Erro ao excluir o destino Destino({$item->tf03_c_descr}).");
  }
  
}

function terminate($codigo = 403) {
  return;
}
