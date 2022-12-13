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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_jsplibwebseller.php");

$ed254_d_datacad_dia = date("d");
$ed254_d_datacad_mes = date("m");
$ed254_d_datacad_ano = date("Y");
$ed254_d_datacad     = $ed254_d_datacad_dia."/".$ed254_d_datacad_mes."/".$ed254_d_datacad_ano;
$ed254_i_usuario     = db_getsession("DB_id_usuario");

db_postmemory( $_POST );

$clescoladiretor = new cl_escoladiretor;
$db_opcao        = 1;
$db_botao        = true;

if ( isset( $incluir ) ) {

  $clescoladiretor->ed254_i_atolegal = $ed254_i_atolegal;
  if ( $ed01_c_exigeato == 'S' && empty( $ed254_i_atolegal ) ) {

    db_msgbox( 'Diretor(a) selecionado(a) exige a informação do Ato Legal.' );
    $sParametro  = "ed254_i_escola={$ed254_i_escola}&ed18_c_nome={$ed18_c_nome}&ed254_i_rechumano={$ed254_i_rechumano}";
    $sParametro .= "&identificacao={$identificacao}&z01_nome={$z01_nome}&ed01_c_exigeato={$ed01_c_exigeato}";
    $sParametro .= "&z01_cgccpf={$z01_cgccpf}&rh37_descr={$rh37_descr}&ed254_i_turno={$ed254_i_turno}";
    $sParametro .= "&ed15_c_nome={$ed15_c_nome}";
    db_redireciona("edu1_escoladiretor001.php?" . $sParametro);
  }

  db_inicio_transacao();

  $sWhereEscolaDiretor = "ed254_i_escola = {$ed254_i_escola} AND ed254_i_turno = {$ed254_i_turno} AND ed254_c_tipo = 'A'";
  $sSqlEscolaDiretor   = $clescoladiretor->sql_query( "", "ed254_i_codigo as jatem", "", $sWhereEscolaDiretor );
  $result              = $clescoladiretor->sql_record( $sSqlEscolaDiretor );

  if( $clescoladiretor->numrows > 0 ) {

    if( $ed254_c_tipo == "A" ) {

      $clescoladiretor->erro_status  = "0";
      $sMensagem                     = "ATENÇÃO! Já existe um diretor com exercício ABERTO para o turno ";
      $sMensagem                    .= trim($ed15_c_nome)."!";
      $clescoladiretor->erro_msg     = $sMensagem;
    } else {
      $clescoladiretor->incluir($ed254_i_codigo);
    }
  } else {
    $clescoladiretor->incluir($ed254_i_codigo);
  }

  db_fim_transacao();
}

if( isset( $alterar ) ) {

  db_inicio_transacao();

  if ( $ed01_c_exigeato == 'S' && empty( $ed254_i_atolegal ) ) {

    db_msgbox( 'Diretor(a) selecionado(a) exige a informação do Ato Legal.' );
    $sParametro  = "ed254_i_escola={$ed254_i_escola}&ed18_c_nome={$ed18_c_nome}&ed254_i_rechumano={$ed254_i_rechumano}";
    $sParametro .= "&identificacao={$identificacao}&z01_nome={$z01_nome}&ed01_c_exigeato={$ed01_c_exigeato}";
    $sParametro .= "&z01_cgccpf={$z01_cgccpf}&rh37_descr={$rh37_descr}&ed254_i_turno={$ed254_i_turno}";
    $sParametro .= "&ed15_c_nome={$ed15_c_nome}";
    db_redireciona("edu1_escoladiretor001.php?" . $sParametro);
  }

  $db_opcao            = 2;
  $sWhereEscolaDiretor = "ed254_i_escola = {$ed254_i_escola} AND ed254_i_turno = {$ed254_i_turno} AND ed254_c_tipo = 'A'";
  $sSqlEscolaDiretor   = $clescoladiretor->sql_query( "", "ed254_i_codigo as jatem", "", $sWhereEscolaDiretor );
  $result              = $clescoladiretor->sql_record( $sSqlEscolaDiretor );

  if( $clescoladiretor->numrows > 0 ) {

    $clescoladiretor->ed254_i_atolegal = $ed254_i_atolegal;
    if( $ed254_c_tipo == "A" ) {

      db_fieldsmemory( $result, 0 );
      $clescoladiretor->ed254_i_atolegal = $ed254_i_atolegal;
      if( $ed254_i_codigo != $jatem ) {

        $clescoladiretor->erro_status  = "0";
        $sMensagem                     = "ATENÇÃO! Já existe um diretor com exercício ABERTO para o turno ";
        $sMensagem                    .= trim($ed15_c_nome)."!";
        $clescoladiretor->erro_msg     = $sMensagem;
      } else {
        $clescoladiretor->alterar($ed254_i_codigo);
      }
    } else {
      $clescoladiretor->alterar($ed254_i_codigo);
    }
  } else {
    $clescoladiretor->alterar($ed254_i_codigo);
  }

  db_fim_transacao();
}

if( isset( $excluir ) ) {

  $db_opcao = 3;
  db_inicio_transacao();
  $clescoladiretor->excluir($ed254_i_codigo);
  db_fim_transacao();
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body bgcolor=#CCCCCC>
  <?require_once("forms/db_frmescoladiretor.php");?>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed254_i_rechumano",true,1,"ed254_i_rechumano",true);
</script>
<?php
  if( isset( $incluir ) ) {

    if( $clescoladiretor->erro_status == "0" ) {

      $clescoladiretor->erro( true, false );
      $db_botao = true;

      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
      if( $clescoladiretor->erro_campo != "" ) {

        echo "<script> document.form1.".$clescoladiretor->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clescoladiretor->erro_campo.".focus();</script>";
      }
    } else {

      $clescoladiretor->erro( true, false );
      db_redireciona("edu1_escoladiretor001.php?ed254_i_escola=$ed254_i_escola&ed18_c_nome=$ed18_c_nome");
    }
  }

  if( isset( $alterar ) ) {

    if( $clescoladiretor->erro_status == "0" ) {

      $clescoladiretor->erro( true, false );
      $db_botao = true;

      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
      if( $clescoladiretor->erro_campo != "" ) {

        echo "<script> document.form1.".$clescoladiretor->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clescoladiretor->erro_campo.".focus();</script>";
      }
    } else {

      $clescoladiretor->erro(true,false);
      db_redireciona("edu1_escoladiretor001.php?ed254_i_escola=$ed254_i_escola&ed18_c_nome=$ed18_c_nome");
    }
  }

  if( isset( $excluir ) ) {

    if( $clescoladiretor->erro_status == "0" ) {
      $clescoladiretor->erro(true,false);
    } else {

      $clescoladiretor->erro(true,false);
      db_redireciona("edu1_escoladiretor001.php?ed254_i_escola=$ed254_i_escola&ed18_c_nome=$ed18_c_nome");
    }
  }

  if( isset( $cancelar ) ) {
    db_redireciona("edu1_escoladiretor001.php?ed254_i_escola=$ed254_i_escola&ed18_c_nome=$ed18_c_nome");
  }
?>