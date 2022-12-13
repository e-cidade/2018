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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("classes/db_lab_valorreferencia_classe.php");
require_once ("classes/db_lab_tiporeferenciaalfa_classe.php");
require_once ("classes/db_lab_tiporeferenciaalnumerico_classe.php");
require_once ("classes/db_lab_valorreferenciasel_classe.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_lab_valorrefselgrupo_classe.php");

parse_str( $_SERVER["QUERY_STRING"] );
db_postmemory($_POST);

$cllab_valorreferencia          = new cl_lab_valorreferencia;
$cllab_tiporeferenciaalnumerico = new cl_lab_tiporeferenciaalnumerico;
$cllab_tiporeferenciaalfa       = new cl_lab_tiporeferenciaalfa;
$cllab_valorreferenciasel       = new cl_lab_valorreferenciasel;
$cllab_valorrefselgrupo         = new cl_lab_valorrefselgrupo;

$db_opcao = 22;
$db_botao = false;

if (isset($alterar)) {

  db_inicio_transacao();

  $db_opcao = 2;
  $cllab_valorreferencia->alterar($la27_i_codigo);

  if ($cllab_valorreferencia->erro_status != "0") {

    if( $iTipo == 1 ) {

      if ($la29_i_codigo != "") {

        $cllab_tiporeferenciaalfa->alterar($la29_i_codigo);

        if ( $cllab_tiporeferenciaalfa->erro_status == "0" ) {

          $cllab_valorreferencia->erro_status = 0;
          $cllab_valorreferencia->erro_sql    = $cllab_tiporeferenciaalfa->erro_sql;
          $cllab_valorreferencia->erro_campo  = $cllab_tiporeferenciaalfa->erro_campo;
          $cllab_valorreferencia->erro_banco  = $cllab_tiporeferenciaalfa->erro_banco;
          $cllab_valorreferencia->erro_msg    = $cllab_tiporeferenciaalfa->erro_msg;
        }
      } else {

        db_query("delete from lab_tiporeferenciaalnumerico where la30_i_valorref = {$la27_i_codigo}");

        $cllab_tiporeferenciaalfa->la29_i_valorref=$la27_i_codigo;
        $cllab_tiporeferenciaalfa->incluir($la29_i_codigo);

        $la29_i_codigo = $cllab_tiporeferenciaalfa->la29_i_codigo;

        if ($cllab_tiporeferenciaalfa->erro_status == "0"){

          $cllab_valorreferencia->erro_status=0;
          $cllab_valorreferencia->erro_sql   = $cllab_tiporeferenciaalfa->erro_sql;
          $cllab_valorreferencia->erro_campo = $cllab_tiporeferenciaalfa->erro_campo;
          $cllab_valorreferencia->erro_banco = $cllab_tiporeferenciaalfa->erro_banco;
          $cllab_valorreferencia->erro_msg   = $cllab_tiporeferenciaalfa->erro_msg;
        }
      }

      if ( $cllab_tiporeferenciaalfa->erro_status != "0" ) {

        db_query("delete from lab_valorrefselgrupo where la51_i_referencia = {$la29_i_codigo}");

        //incluir Grupo selecionavel
        if( $str_valorRefSel != "" ) {

          $vet = explode(",",$str_valorRefSel);
          $cllab_valorrefselgrupo->la51_i_referencia=$cllab_tiporeferenciaalfa->la29_i_codigo;

          for ($x = 0; $x < count($vet); $x++) {

            if ($cllab_valorrefselgrupo->erro_status != "0") {

              $cllab_valorrefselgrupo->la51_i_valorrefsel=$vet[$x];
              $cllab_valorrefselgrupo->incluir(null);

              if ($cllab_valorrefselgrupo->erro_status == "0") {

                $cllab_valorreferencia->erro_status = 0;
                $cllab_valorreferencia->erro_sql    = $cllab_valorrefselgrupo->erro_sql;
                $cllab_valorreferencia->erro_campo  = $cllab_valorrefselgrupo->erro_campo;
                $cllab_valorreferencia->erro_banco  = $cllab_valorrefselgrupo->erro_banco;
                $cllab_valorreferencia->erro_msg    = $cllab_valorrefselgrupo->erro_msg;
              }
            }
          }
        }
      }
    } else {

      if ($la30_i_codigo != "") {

        $cllab_tiporeferenciaalnumerico->alterar($la30_i_codigo);
        if ($cllab_tiporeferenciaalnumerico->erro_status == "0"){

          $cllab_valorreferencia->erro_status=0;
          $cllab_valorreferencia->erro_sql   = $cllab_tiporeferenciaalnumerico->erro_sql;
          $cllab_valorreferencia->erro_campo = $cllab_tiporeferenciaalnumerico->erro_campo;
          $cllab_valorreferencia->erro_banco = $cllab_tiporeferenciaalnumerico->erro_banco;
          $cllab_valorreferencia->erro_msg   = $cllab_tiporeferenciaalnumerico->erro_msg;
        }
      } else {

        db_query("delete from lab_tiporeferenciaalfa where la29_i_valorref = {$la27_i_codigo}");

        $cllab_tiporeferenciaalnumerico->la30_i_valorref = $la27_i_codigo;
        $cllab_tiporeferenciaalnumerico->incluir($la30_i_codigo);

        if ($cllab_tiporeferenciaalnumerico->erro_status == "0") {

          $cllab_valorreferencia->erro_status = 0;
          $cllab_valorreferencia->erro_sql    = $cllab_tiporeferenciaalnumerico->erro_sql;
          $cllab_valorreferencia->erro_campo  = $cllab_tiporeferenciaalnumerico->erro_campo;
          $cllab_valorreferencia->erro_banco  = $cllab_tiporeferenciaalnumerico->erro_banco;
          $cllab_valorreferencia->erro_msg    = $cllab_tiporeferenciaalnumerico->erro_msg;
        }
      }
    }
  }

  db_fim_transacao();
} else if( isset( $chavepesquisa ) ) {

  $db_opcao = 2;
  $result   = $cllab_valorreferencia->sql_record( $cllab_valorreferencia->sql_query( $chavepesquisa ) );

  db_fieldsmemory( $result, 0 );

  $sSqlTipoReferenciaAlfa = $cllab_tiporeferenciaalfa->sql_query( "", "*", "", "la29_i_valorref = {$chavepesquisa}" );
  $result                 = $cllab_tiporeferenciaalfa->sql_record( $sSqlTipoReferenciaAlfa );

  if ($cllab_tiporeferenciaalfa->numrows > 0) {

    db_fieldsmemory($result,0);

    $sql     = $cllab_valorrefselgrupo->sql_query( "", "la28_i_codigo, la28_c_descr", "", "la51_i_referencia = {$la29_i_codigo}" );
    $rResult = $cllab_valorrefselgrupo->sql_record($sql);

    for ($x = 0; $x < $cllab_valorrefselgrupo->numrows; $x++) {

      db_fieldsmemory($rResult,$x);
      $aValorRefSel[$x][1]=$la28_i_codigo;
      $aValorRefSel[$x][2]=$la28_c_descr;
    }

    $iTipo = 1;
  }

  $sSqlTipoReferencialNumerico = $cllab_tiporeferenciaalnumerico->sql_query( "", "*", "", "la30_i_valorref = {$chavepesquisa}" );
  $result                      = $cllab_tiporeferenciaalnumerico->sql_record( $sSqlTipoReferencialNumerico );

  if ($cllab_tiporeferenciaalnumerico->numrows > 0) {
    $iTipo = 2;
  }

  $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/arrays.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/numbers.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/jquery-2.1.1.min.js"></script><!-- MARCO adcionado 11-02-2015 -->
  <script language="JavaScript" type="text/javascript" src="scripts/jquery.filter_input.js"></script><!-- MARCO adcionado 11-02-2015 -->
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
    <body class='body_default' >

   <div class='container'>
  	<?
      $GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"] = 'lab1_lab_valorreferencia002.php';
    	require_once("forms/db_frmlab_valorreferencia.php");
  	?>
  </div>

<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

</body>
</html>
<?php
if( isset( $alterar ) ) {

  if( $cllab_valorreferencia->erro_status == "0" ) {

    $cllab_valorreferencia->erro(true,false);
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if( $cllab_valorreferencia->erro_campo != "" ) {

      echo "<script> document.form1.".$cllab_valorreferencia->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cllab_valorreferencia->erro_campo.".focus();</script>";
    }
  } else {
    $cllab_valorreferencia->erro(true,true);
  }
}

if( $db_opcao == 22 ) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","la27_i_unidade",true,1,"la27_i_unidade",true);
</script>