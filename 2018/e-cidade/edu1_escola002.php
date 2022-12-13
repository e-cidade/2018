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
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_jsplibwebseller.php"));
require_once(modification("libs/db_app.utils.php"));

db_postmemory( $_POST );

$clescola           = new cl_escola;
$clcensouf          = new cl_censouf;
$clcensomunic       = new cl_censomunic;
$clcensodistrito    = new cl_censodistrito;
$clcensoorgreg      = new cl_censoorgreg;
$clcensolinguaindig = new cl_censolinguaindig;
$cldb_depart        = new cl_db_depart;

$db_botao = true;
$db_opcao = 1;

function PegaValores( $array, $tamanho ) {

  $retorno = "";

  for( $x = 1; $x <= $tamanho; $x++ ) {

    $tem = false;

    for( $y = 0; $y < count( $array ); $y++ ) {

      if( $array[$y] == $x ) {

        $retorno .= "1";
        $tem      = true;
        break;
      }
    }

    if( $tem == false ) {
      $retorno .= "0";
    }
  }

  return $retorno;
}

if( isset( $incluir ) ) {

  $db_opcao = 1;
  $tmp_name = $_FILES["ed18_c_logo"]["tmp_name"];
  $name     = $_FILES["ed18_c_logo"]["name"];
  $type     = $_FILES["ed18_c_logo"]["type"];
  $size     = $_FILES["ed18_c_logo"]["size"];

  if( $type != "image/jpeg" && $tmp_name != "" ) {

    db_msgbox( "Utilizar somente imagens no formato JPG ou JPEG!" );
    $ed18_c_logo = "";
  } else {

    @$ed18_c_mantprivada = PegaValores( $ed18_c_mantprivada, 4 );

    db_inicio_transacao();

    $clescola->ed18_c_tipo           = 'S';
    $clescola->ed18_c_logo           = $name;
    $clescola->ed18_c_mantprivada    = $ed18_c_mantprivada;
    $clescola->ed18_i_categprivada   = isset( $ed18_i_categprivada ) ? $ed18_i_categprivada : "";
    $clescola->ed18_i_conveniada     = isset( $ed18_i_conveniada ) ? $ed18_i_conveniada : "";
    $clescola->ed18_codigoreferencia = $ed18_codigoreferencia;
    $clescola->ed18_i_censoorgreg    = empty( $ed18_i_censoorgreg ) ? 'null' : $ed18_i_censoorgreg;

    $clescola->incluir( $ed18_i_codigo );
    db_fim_transacao();
  }
} else if( isset( $alterar ) ) {

  $db_opcao = 2;
  $tmp_name = $_FILES["ed18_c_logo"]["tmp_name"];
  $name     = $_FILES["ed18_c_logo"]["name"];
  $type     = $_FILES["ed18_c_logo"]["type"];
  $size     = $_FILES["ed18_c_logo"]["size"];

  if( $type != "image/jpeg" && $tmp_name != "" ) {

    db_msgbox( "Utilizar somente imagens no formato JPG ou JPEG!" );
    $ed18_c_logo = "";
  } else {

    @$ed18_c_mantprivada = PegaValores( $ed18_c_mantprivada, 5 );

    db_inicio_transacao();

    $clescola->ed18_c_tipo           = 'S';
    $clescola->ed18_c_logo           = $name;
    $clescola->ed18_c_mantprivada    = $ed18_c_mantprivada;
    $clescola->ed18_i_categprivada   = isset( $ed18_i_categprivada ) ? $ed18_i_categprivada : "";
    $clescola->ed18_i_conveniada     = isset( $ed18_i_conveniada ) ? $ed18_i_conveniada : "";
    $clescola->ed18_codigoreferencia = $ed18_codigoreferencia;
    $clescola->ed18_i_censoorgreg    = empty( $ed18_i_censoorgreg ) ? 'null' : $ed18_i_censoorgreg;

    if ( empty($ed18_i_tipolinguapt) ) {
      $GLOBALS["HTTP_POST_VARS"]["ed18_i_tipolinguapt"] = 0;
    }

    if ( empty($ed18_i_tipolinguain) ) {

      $GLOBALS["HTTP_POST_VARS"]["ed18_i_linguaindigena"] = '';
      $GLOBALS["HTTP_POST_VARS"]["ed18_i_tipolinguain"]   = 0;
    }

    if ($ed18_c_mantenedora != 4 ) {

      $GLOBALS["HTTP_POST_VARS"]["ed18_i_cnpjprivada"]     = null;
      $GLOBALS["HTTP_POST_VARS"]["ed18_i_cnpjmantprivada"] = null;

      $clescola->ed18_c_mantprivada     = '00000';
      $clescola->ed18_i_categprivada    = 'null';
      $clescola->ed18_i_conveniada      = 'null';
      $clescola->ed18_i_cnas            = 'null';
      $clescola->ed18_i_cebas           = 'null';
      $clescola->ed18_i_cnpjmantprivada = '0';
      $clescola->ed18_i_cnpjprivada     = '0';
    }



    $clescola->alterar( $ed18_i_codigo );
    db_fim_transacao();
  }
} else if( isset( $excluirfoto ) ) {

  $sql    = "UPDATE escola SET ed18_c_logo = '' WHERE ed18_i_codigo = {$codigoescola}";
  $result = db_query( $sql );
  unlink( exec( "pwd" ) . "/imagens/" . $logo );
  db_redireciona( "edu1_escola002.php" );
} else {

  $ed18_i_codigo = db_getsession( "DB_coddepto" );
  $result        = $clescola->sql_record( $clescola->sql_query( $ed18_i_codigo ) );
  $result_depto  = $cldb_depart->sql_record( $cldb_depart->sql_query_file( "", "*", "", "coddepto = {$ed18_i_codigo}" ) );

  db_fieldsmemory( $result_depto, 0 );

  if( $clescola->numrows != 0 ) {

    db_fieldsmemory( $result, 0 );

    $db_opcao  = 2;
    $db_opcao1 = 1;

    if( isset( $cp06_cep ) ) {

      if( $cp06_cep != "" ) {
        $ed18_c_cep = $cp06_cep;
      }
    }
    ?>
    <script>
      parent.document.formaba.a2.disabled = false;
      parent.document.formaba.a2.style.color = "black";
      parent.document.formaba.a3.disabled = false;
      parent.document.formaba.a3.style.color = "black";
      parent.document.formaba.a4.disabled = false;
      parent.document.formaba.a4.style.color = "black";
      parent.document.formaba.a5.disabled = false;
      parent.document.formaba.a5.style.color = "black";

      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a2.location.href = 'edu1_telefoneescola001.php?ed26_i_escola=<?=$ed18_i_codigo?>&descrdepto=<?=$ed18_c_nome?>';
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a3.location.href = 'edu1_escolaestruturaavaliacao.php?escola=<?=$ed18_i_codigo?>&ed18_c_nome=<?=$ed18_c_nome?>';
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a4.location.href = 'edu1_escoladiretor001.php?ed254_i_escola=<?=$ed18_i_codigo?>&ed18_c_nome=<?=$ed18_c_nome?>';
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a5.location.href = 'edu1_horariosescola001.php?ed17_i_escola=<?=$ed18_i_codigo?>&descrdepto=<?=$ed18_c_nome?>';
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a6.location.href = 'edu1_escolagestor001.php?ed17_i_escola=<?=$ed18_i_codigo?>&ed18_c_nome=<?=$ed18_c_nome?>';
    </script>
    <?php
  } else {

    $ed18_c_nome = $descrdepto;
    $db_opcao    = 1;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/numbers.js"></script>
<?
  db_app::load('prototype.js');
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <?php
  require_once(modification( "forms/db_frmescola.php" ));
  ?>
</body>
</html>
<?php
if( isset( $incluir ) ) {

  if( $clescola->erro_status == "0" ) {

    $clescola->erro( true, false );
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if( $clescola->erro_campo != "" ) {

      echo "<script> document.form1.".$clescola->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clescola->erro_campo.".focus();</script>";
    }
  } else {

    if( $tmp_name != "" ) {

      ///enviar para pasta imagens
      $destino = exec( "pwd" ) . "/imagens/";

      if( !file_exists( $destino . $name ) ) {

        if( !@copy( $tmp_name, $destino . $name ) ) {
          db_msgbox( "NÃO FOI POSSÍVEL EFETUAR UPLOAD. VERIFIQUE PERMISSÃO DO DIRETÓRIO {$destino}" );
        }
      }
    }
    ?>
    <script>
      parent.document.formaba.a2.disabled = false;
      parent.document.formaba.a2.style.color = "black";
      parent.document.formaba.a3.disabled = false;
      parent.document.formaba.a3.style.color = "black";
      parent.document.formaba.a4.disabled = false;
      parent.document.formaba.a4.style.color = "black";
      parent.document.formaba.a5.disabled = false;
      parent.document.formaba.a5.style.color = "black";

      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a2.location.href = 'edu1_telefoneescola001.php?ed26_i_escola=<?=$ed18_i_codigo?>&descrdepto=<?=$ed18_c_nome?>';
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a3.location.href = 'edu1_escolaestrutura001.php?escola=<?=$ed18_i_codigo?>&ed18_c_nome=<?=$ed18_c_nome?>';
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a4.location.href = 'edu1_escoladiretor001.php?ed254_i_escola=<?=$ed18_i_codigo?>&ed18_c_nome=<?=$ed18_c_nome?>';
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a5.location.href = 'edu1_horariosescola001.php?ed17_i_escola=<?=$ed18_i_codigo?>&descrdepto=<?=$ed18_c_nome?>';
    </script>
    <?
    $clescola->erro( true, true );
  }
}

if( isset( $alterar ) ) {

  if( $clescola->erro_status == "0" ) {

    $clescola->erro( true, false );
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled = false;</script>";

    if( $clescola->erro_campo != "" ) {

      echo "<script> document.form1.".$clescola->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clescola->erro_campo.".focus();</script>";
    }
  } else {

    if( $tmp_name != "" ) {

      ///enviar para pasta imagens
      $destino = exec( "pwd" ) . "/imagens/";

      if( !file_exists( $destino . $name ) ) {

        if( !@copy( $tmp_name, $destino . $name ) ) {
          db_msgbox( "NÃO FOI POSSÍVEL EFETUAR UPLOAD. VERIFIQUE PERMISSÃO DO DIRETÓRIO {$destino}" );
        }
      }

      if( $ed18_c_logo != "" ) {
        unlink( exec( "pwd" ) . "/imagens/" . $ed18_c_logo );
      }
    }

    $clescola->erro( true, true );
  }
}
?>