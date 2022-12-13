<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_familiamicroarea_classe.php"));
include(modification("dbforms/db_funcoes.php"));

parse_str( $_SERVER["QUERY_STRING"] );
db_postmemory( $_POST );

$clfamiliamicroarea = new cl_familiamicroarea;
$db_opcao           = 22;
$db_botao           = false;

if(isset($alterar)) {

  $sWhereValidaVinculo = "sd35_i_familia = {$sd35_i_familia} AND sd35_i_microarea = {$sd35_i_microarea}";
  $sSqlValidaVinculo   = $clfamiliamicroarea->sql_query_file( null, '1', null, $sWhereValidaVinculo );
  $rsValidaVinculo     = db_query( $sSqlValidaVinculo );

  if( is_resource( $rsValidaVinculo ) && pg_num_rows( $rsValidaVinculo ) > 0 ) {

    db_msgbox( 'Alteração não permitida. Vínculo de Família e Micro Área já existente.' );
    db_redireciona( "sau1_familiamicroarea002.php?chavepesquisa={$sd35_i_codigo}" );
  }

  db_inicio_transacao();

  $db_opcao = 2;
  $clfamiliamicroarea->alterar($sd35_i_codigo);

  db_fim_transacao();
} else if(isset($chavepesquisa)) {

  $db_opcao = 2;
  $result   = $clfamiliamicroarea->sql_record($clfamiliamicroarea->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);
  $db_botao = true;
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
    include(modification("forms/db_frmfamiliamicroarea.php"));
    db_menu();
  ?>
</body>
</html>
<?php
  if(isset($alterar)) {

    if($clfamiliamicroarea->erro_status == "0") {

      $clfamiliamicroarea->erro(true,false);
      $db_botao = true;

      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

      if($clfamiliamicroarea->erro_campo != "") {

        echo "<script> document.form1.".$clfamiliamicroarea->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clfamiliamicroarea->erro_campo.".focus();</script>";
      }
    } else {
      $clfamiliamicroarea->erro(true,true);
    }
  }

  if($db_opcao == 22) {
    echo "<script>document.form1.pesquisar.click();</script>";
  }
?>
<script>
js_tabulacaoforms("form1","sd35_i_familia",true,1,"sd35_i_familia",true);
</script>