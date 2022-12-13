<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

include("libs/db_conecta.php");

$oPluginService = new PluginService();

try {

  $oConfiguracao = $oPluginService->getConfig()->AcessoBase;
} catch (Exception $oException) {

  echo "Arquivo de configuração inválido.";
  exit;
}

$conn = @pg_connect(  "host={$DB_SERVIDOR} "
                     ."dbname={$DB_BASE} "
                     ."port={$DB_PORTA} "
                     ."user={$oConfiguracao->usuario} "
                     ."password={$oConfiguracao->senha}" );

if ( !$conn ) {

  echo "Contate com Administrador do Sistema! (Conexão Inválida.)   <br>Sessão terminada, feche seu navegador!\n";
  session_destroy();
  exit;
}

$DB_USUARIO = $oConfiguracao->usuario;
$DB_SENHA   = $oConfiguracao->senha;

pg_query($conn, "select fc_startsession()");

$oSearchPath = db_utils::fieldsMemory(pg_query($conn, "select current_setting('search_path')"), 0);

pg_query($conn, "set search_path to {$oSearchPath->current_setting}, plugins");

