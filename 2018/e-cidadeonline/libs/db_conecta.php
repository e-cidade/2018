<?
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

include("db_conn.php");

if (!isset($_SESSION)) {
  session_start();
}

if (!isset($HTTP_SESSION_VARS["DB_instit"])){
  if (!session_is_registered("DB_instit")){
    session_register("DB_instit");
  }
  $_SESSION["DB_instit"] = $DB_INSTITUICAO;
}
$conn = @pg_connect("host=$DB_SERVIDOR dbname=$DB_BASEDADOS port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA");
if(!$conn) {
  echo "<script>location.href='manutencao.php'</script>\n";
  exit;
}

require_once("libs/db_libsession.php");
db_savesession($conn, $_SESSION);
?>
