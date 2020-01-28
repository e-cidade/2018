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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

db_postmemory( $_POST );

$clescolabase       = new cl_escolabase;
$clbase             = new cl_base;
$clbaseserie        = new cl_baseserie;
$clbasemps          = new cl_basemps;
$clbasediscglob     = new cl_basediscglob;
$clescola           = new cl_escola;
$clturma            = new cl_turma;
$clalunocurso       = new cl_alunocurso;
$clatestvaga        = new cl_atestvaga;
$cltransfescolarede = new cl_transfescolarede;

$db_opcao = 1;
$db_botao = true;

if( isset( $incluir ) ) {

  $sWhereEscolaBase = " ed77_i_base = {$ed77_i_base} AND ed77_i_escola = {$ed77_i_escola}";
  $sSqlEscolaBase = $clescolabase->sql_query( "", "*", "", $sWhereEscolaBase );
  $result         = $clescolabase->sql_record( $sSqlEscolaBase );

  if( $clescolabase->numrows > 0 ) {

    db_msgbox("Escola {$ed18_c_nome} já está vinculada a base curricular {$ed31_c_descr}");
    echo "<script>location.href='".$clescolabase->pagina_retorno."'</script>";
  } else {

    db_inicio_transacao();
    $clescolabase->incluir($ed77_i_codigo);
    db_fim_transacao();
  }
}

if( isset( $alterar ) ) {

  db_inicio_transacao();

  $db_opcao = 2;
  $clescolabase->alterar($ed77_i_codigo);

  db_fim_transacao();
}

if( isset( $excluir ) ) {

  db_inicio_transacao();

  $db_opcao = 3;
  $clescolabase->excluir($ed77_i_codigo);

  db_fim_transacao();
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
<body>
  <?php
  include("forms/db_frmescolabase.php");
  ?>
</body>
</html>
<?php
if( isset( $incluir ) ) {

  if( $clescolabase->erro_status == "0" ) {

    $clescolabase->erro( true, false );
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if( $clescolabase->erro_campo != "" ) {

      echo "<script> document.form1.".$clescolabase->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clescolabase->erro_campo.".focus();</script>";
    }
  } else {
    $clescolabase->erro(true,true);
  }
}

if( isset( $alterar ) ) {

  if( $clescolabase->erro_status == "0" ) {

    $clescolabase->erro( true, false );
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if( $clescolabase->erro_campo != "" ) {

      echo "<script> document.form1.".$clescolabase->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clescolabase->erro_campo.".focus();</script>";
    }
  } else {
    $clescolabase->erro(true,true);
  }
}

if( isset( $excluir ) ) {

  if( $clescolabase->erro_status == "0" ) {
    $clescolabase->erro( true, false );
  } else {

    $clescolabase->erro( true, false );
    ?>
    <script>
      parent.location.href='edu1_baseabas002.php';
    </script>
    <?php
  }
}

if( isset( $cancelar ) ) {
  echo "<script>location.href='".$clescolabase->pagina_retorno."'</script>";
}