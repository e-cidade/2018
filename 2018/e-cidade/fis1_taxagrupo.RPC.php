<?php
/**
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

    case 'getGrupos':
      $oRetorno->valores = getGrupos();
      break;
    case 'salvar':
      $oRetorno->item = salvar($oParametro->item);
      $oRetorno->mensagem = 'Grupo salvo com sucesso.';
      break;
    case 'excluir':

      $oDaoGrupoTaxaDiversos   = new cl_grupotaxadiversos();
      $sWhereGrupoTaxaDiversos = "EXISTS(select 1 from taxadiversos where y119_grupotaxadiversos = y118_sequencial)";
      $sSqlGrupoTaxaDiversos   = $oDaoGrupoTaxaDiversos->sql_query_file(null, '1', null, $sWhereGrupoTaxaDiversos);
      $rsGrupoTaxaDiversos     = db_query($sSqlGrupoTaxaDiversos);

      if(!$rsGrupoTaxaDiversos) {
        throw new DBException('Erro ao validar a exclusão do grupo.');
      }

      if(pg_num_rows($rsGrupoTaxaDiversos) > 0) {
        throw new BusinessException('Exclusão não permitida. Grupo vinculado a uma taxa já lançada.');
      }

      excluir($oParametro->item);
      $oRetorno->mensagem = 'Grupo excluído com sucesso.';
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

function getGrupos() {

  $oDao  = new cl_grupotaxadiversos();

  $sSql  = $oDao->sql_query(null, "y118_sequencial, y118_descricao, y118_inflator, y118_procedencia, i01_codigo, i01_descr, dv09_procdiver, dv09_descr");
  $rsSql = db_query($sSql);

  if (!$rsSql) {
    throw new DBException("Erro ao buscar grupos de Taxas.");
  }
  return db_utils::getCollectionByRecord($rsSql);
}

function salvar($item) {

  $codigoInflator    = pg_escape_string($item->i01_codigo);
  $codigoProcedencia = $item->dv09_procdiver + 0;

  $oDao = new cl_grupotaxadiversos();

  $oDao->y118_descricao   = mb_strtoupper(pg_escape_string($item->y118_descricao));
  $oDao->y118_inflator    = $codigoInflator;
  $oDao->y118_procedencia = $codigoProcedencia;

  if (isset($item->y118_sequencial) && $item->y118_sequencial) {//Caractere coringa do front quando não existe sequencial

    $oDao->y118_sequencial = $item->y118_sequencial;
    $oDao->alterar($oDao->y118_sequencial);
  } else {
    $oDao->y118_sequencial = null;
    $oDao->incluir(null);
  }

  if ($oDao->erro_status == "0") {
    throw new \DBException("Erro ao salvar o Grupo ({$item->y118_descricao}).");
  }

  $codigoGrupo      = $oDao->y118_sequencial;
  $oDao             = new cl_grupotaxadiversos();
  $sSql             = $oDao->sql_query($codigoGrupo, "y118_sequencial, y118_descricao, y118_inflator, y118_procedencia, i01_codigo, i01_descr, dv09_procdiver, dv09_descr");
  $rsDadosInseridos = db_query($sSql);

  if (!$rsDadosInseridos) {
    throw new DBException("Erro ao consolidar os dados inseridos.");
  }

  if (pg_num_rows($rsDadosInseridos) == 0){
    throw new BusinessException("O Grupo ({$item->y118_descricao}) não foi salvo.");
  }

  return db_utils::makeFromRecord($rsDadosInseridos, function($dadosInseridos) {

    return (object)array(
      "y118_sequencial"   => $dadosInseridos->y118_sequencial,
      "y118_descricao"    => mb_strtoupper($dadosInseridos->y118_descricao),
      "y118_inflator"     => $dadosInseridos->y118_inflator,
      "y118_procedencia"  => $dadosInseridos->y118_procedencia,
      "i01_codigo"        => $dadosInseridos->i01_codigo,
      "i01_descr"         => mb_strtoupper($dadosInseridos->i01_descr),
      "dv09_procdiver"    => $dadosInseridos->dv09_procdiver,
      "dv09_descr"        => mb_strtoupper($dadosInseridos->dv09_descr),
    );
  });
}

function excluir($item) {

  $oDao = new cl_grupotaxadiversos();
  $oDao->y118_sequencial = $item->y118_sequencial + 0;
  $oDao->excluir($item->y118_sequencial + 0);

  if($oDao->erro_status == "0") {
    throw new DBException("Erro ao excluir o Grupo ({$item->y118_descricao}).");
  }

}

function terminate($codigo = 403) {
  return;
}
