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

// Salva o conteudo da sessao no banco de dados
function db_savesession($_conn, $_session) {

  // Cria tabela temporaria para a conexao corrente
  $sql  = "SELECT fc_startsession();";

  $result = pg_query($_conn, $sql) or die("Não foi possível criar sessão no banco de dados (Sql: $sql)!");

  if (pg_num_rows($result)==0) {
    return false;
  }

  // Insere as variaveis da sessao na tabela
  $sql   = "";

  foreach($_session as $key=>$val) {

    $key = strtoupper($key);

    // Intercepta "DB_DATAUSU" para ajustes
    if ($key == "DB_DATAUSU") {
      $time        = microtime(true);
      $micro_time  = sprintf("%06d",($time - floor($time)) * 1000000);
      $time_now    = date("H:i:s");

      $datahora = date("Y-m-d {$time_now}.{$micro_time}O", $val);

      // Cria timestamp "DB_DATAHORAUSU"
      $sql .= "SELECT fc_putsession('DB_DATAHORAUSU', '$datahora'); ";

      $val = date("Y-m-d", $val);
    }

    if (substr($key,0,2) == "DB"){

      $val = pg_escape_string($val);
      $sql .= "SELECT fc_putsession('$key', '$val'); ";
    }
  }

  pg_query($_conn, $sql) or die("Não foi possível criar sessão no banco de dados (Sql: $sql)!");

  return true;
}
