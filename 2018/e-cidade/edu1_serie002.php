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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

parse_str( $_SERVER["QUERY_STRING"] );
db_postmemory( $_POST );

$clserie  = new cl_serie;
$db_opcao = 22;
$db_botao = false;

if( isset( $alterar ) ) {

  $db_opcao = 2;
  $db_botao = true;

  db_inicio_transacao();
  $clserie->alterar($ed11_i_codigo);
  db_fim_transacao();
} else if( isset( $chavepesquisa ) ) {

  $db_opcao = 2;
  $result   = $clserie->sql_record($clserie->sql_query($chavepesquisa));

  db_fieldsmemory( $result, 0 );
  $db_botao = true;
?>
 <script>
   parent.document.formaba.a2.disabled = false;
   parent.document.formaba.a3.disabled = false;

   top.corpo.iframe_a2.location.href = 'edu1_serieregimemat001.php?ed223_i_serie=<?=$ed11_i_codigo?>&ed11_c_descr=<?=$ed11_c_descr?>';

   var sParametros = '?iEtapa=<?=$ed11_i_codigo?>&sEtapa=<?=$ed11_c_descr?>';
   top.corpo.iframe_a3.location.href = 'edu1_vinculoserieetapacenso001.php' + sParametros;
 </script>
<?php
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
include("forms/db_frmserie.php");
?>
</body>
</html>
<script>
 js_tabulacaoforms("form1","ed11_i_ensino",true,1,"ed11_i_ensino",true);
</script>
<?php
if( isset( $alterar ) ) {

  if( $clserie->erro_status == "0" ) {

    $clserie->erro( true, false );
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>";

    if( $clserie->erro_campo != "" ) {

      echo "<script> document.form1.".$clserie->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clserie->erro_campo.".focus();</script>";
    }
  } else {

    $clserie->erro( true, false );
    ?>
    <script>
      parent.document.formaba.a2.disabled = false;
      parent.document.formaba.a3.disabled = false;
      top.corpo.iframe_a2.location.href='edu1_serieregimemat001.php?ed223_i_serie=<?=$ed11_i_codigo?>&ed11_c_descr=<?=$ed11_c_descr?>';
      parent.mo_camada("a2");
    </script>
    <?php
  }
}

if( $db_opcao == 22 ) {
  echo "<script>document.form1.pesquisar.click();</script>";
}