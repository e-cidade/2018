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
require_once modification("libs/db_utils.php");
require_once modification("dbforms/db_funcoes.php");

$oParametros       = JSON::create()->parse( str_replace("\\","",$_POST["json"]) );
$oRetorno          = new stdClass;
$oRetorno->message = '';
$oRetorno->erro    = false;

try {

  db_inicio_transacao();

  switch ($oParametros->exec) {

    case 'getDados':

      $oDaoClassificacao      = new cl_classificacaocredores();
      $sSqlBuscaClassificacao = $oDaoClassificacao->sql_query_file(null, "cc30_codigo, cc30_descricao, cc30_ordem");
      $rsBuscaClassificacao   = db_query($sSqlBuscaClassificacao);
      if (!$rsBuscaClassificacao) {
        throw new Exception("Ocorreu um erro ao buscar as listas de classificação de credores.");
      }

      $oRetorno->aLista = db_utils::makeCollectionFromRecord($rsBuscaClassificacao, function($oRegistro) {

        return (object) array(
          'iCodigo'    => $oRegistro->cc30_codigo,
          'sDescricao' => $oRegistro->cc30_descricao,
          'iOrdem'     => $oRegistro->cc30_ordem
        );
      });

      break;

    case 'salvar':

      if (!empty($oParametros->aLista) || !empty($oParametros->aLista)) {

        foreach ($oParametros->aLista as $oStdLista) {

          if (empty($oStdLista->codigo)) {
            throw new ParameterException("Código da Lista de Classificação de Credor não informado.");
          }
          $oListaClassificacao = ListaClassificacaoCredorRepository::getPorCodigo((int) $oStdLista->codigo);
          if (!empty($oStdLista->ordem)) {
            $oListaClassificacao->setOrdem((int) $oStdLista->ordem);
          }

          $oListaClassificacao->salvar(false);
        }
      }

      $oRetorno->message = urlencode('A ordenação foi salva com sucesso.');
      break;
  }

  db_fim_transacao(false);
} catch (Exception $oException) {

  db_fim_transacao(true);

  $oRetorno->message = urlencode($oException->getMessage());
  $oRetorno->erro = true;
}

echo JSON::create()->stringify($oRetorno);