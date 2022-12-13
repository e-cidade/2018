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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str( $_SERVER["QUERY_STRING"] );
db_postmemory( $_POST );

$clsau_prestadores = new cl_sau_prestadores;
$db_opcao = 22;
$db_botao = false;

if( isset( $alterar ) ) {

  $sWhereSauPrestadores = "s110_i_numcgm = {$s110_i_numcgm} AND s110_i_codigo <> {$s110_i_codigo}";
  $sSqlSauPrestadores   = $clsau_prestadores->sql_query_file( null, '1', null, $sWhereSauPrestadores );
  $rsSauPrestadores     = db_query( $sSqlSauPrestadores );

  if( $rsSauPrestadores && pg_num_rows( $rsSauPrestadores ) > 0 ) {

    db_msgbox( "CGM já cadastrado como prestador." );
    db_redireciona( "sau1_sau_prestadores002.php?chavepesquisa={$s110_i_codigo}" );
  }

  db_inicio_transacao();

  $db_opcao = 2;
  $clsau_prestadores->alterar($s110_i_codigo);

  db_fim_transacao();
} else if( isset( $chavepesquisa ) ) {

  $db_opcao = 2;
  $result   = $clsau_prestadores->sql_record( $clsau_prestadores->sql_query( $chavepesquisa ) );
  db_fieldsmemory( $result, 0 );
  $db_botao = true;

  echo "<script> parent.document.formaba.a2.disabled = false;</script>";
  echo "<script>parent.iframe_a2.location.href = 'sau1_sau_prestadorvinculos001.php?s111_i_prestador=$s110_i_codigo&z01_nome=$z01_nome'</script>";
  echo "<script> parent.document.formaba.a3.disabled = false;</script>";
  echo "<script>parent.iframe_a3.location.href = 'sau1_sau_prestadorhorarios001.php?s111_i_prestador=$s110_i_codigo&z01_nome=$z01_nome'</script>";

  echo "<script> parent.document.formaba.a4.disabled = false;</script>";
  echo "<script>parent.iframe_a4.location.href = 'sau1_prestadorcotamensal001.php?s111_i_prestador=$s110_i_codigo&z01_nome=$z01_nome'</script>";
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <?php
  include(modification("forms/db_frmsau_prestadores.php"));
  ?>
</body>
</html>
<?php
if( isset( $alterar ) ) {

  if( $clsau_prestadores->erro_status == "0" ) {

    $clsau_prestadores->erro( true, false );
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if( $clsau_prestadores->erro_campo != "" ) {

      echo "<script> document.form1.".$clsau_prestadores->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsau_prestadores->erro_campo.".focus();</script>";
    }
  } else {

    $clsau_prestadores->erro( true, false );
	  db_redireciona("sau1_sau_prestadores002.php?chavepesquisa=$s110_i_codigo&z01_nome=$z01_nome");
  }
}

if( $db_opcao == 22 && !isset( $s110_i_codigo ) ) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","s110_i_numcgm",true,1,"s110_i_numcgm",true);
</script>