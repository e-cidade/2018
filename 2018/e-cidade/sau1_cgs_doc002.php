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
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_cgs_classe.php");
require_once("classes/db_cgs_und_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_stdlibwebseller.php");

parse_str( $_SERVER["QUERY_STRING"] );
db_postmemory( $_POST );

$clcgs        = new cl_cgs;
$clcgs_und    = new cl_cgs_und;
$z01_i_cgsund = $chavepesquisa;

$db_opcao  = 22;
$db_opcao1 = 3;
$db_botao  = false;

if( isset( $alterar ) ) {

  db_inicio_transacao();
  $db_opcao  = 2;
  $db_opcao1 = 3;

  $iCodigoIBGE  = null;
  $sWhereCgsUnd = "z01_i_cgsund = {$z01_i_cgsund}";
  $sSqlCgsUnd   = $clcgs_und->sql_query_file( null, 'z01_codigoibge', null, $sWhereCgsUnd );
  $rsCgsund     = db_query( $sSqlCgsUnd );

  if( $rsCgsund && pg_num_rows( $rsCgsund ) > 0 ) {
    $iCodigoIBGE = db_utils::fieldsMemory( $rsCgsund, 0 )->z01_codigoibge;
  }

  $clcgs_und->z01_codigoibge  = $iCodigoIBGE;
   $clcgs_und->z01_codigoibge = $iCodigoIBGE;
  $clcgs_und->alterar($z01_i_cgsund);

  db_fim_transacao();
  $db_botao = true;
} else if( isset( $chavepesquisa ) ) {

  if( isset( $lReadOnly ) && $lReadOnly ) {
    $db_opcao = 3; 
  } else {
    $db_opcao = 2; 
  }

  $db_opcao1 = 3;
  $result    = $clcgs_und->sql_record( $clcgs_und->sql_query( $chavepesquisa ) );

  db_fieldsmemory( $result, 0 );
  $result2    = $clcgs_und->sql_record("SELECT * FROM cgs_und_ext WHERE z01_i_cgsund =" . $GLOBALS['z01_i_cgsund']);
  if($result2) {
    db_fieldsmemory($result2,0);
  }

  $j13_codi = @$ed225_i_bairro;
  $db_botao = true;

  $z01_d_cadast_dia = $z01_d_cadast_dia == "" ? date("d") : $z01_d_cadast_dia;
  $z01_d_cadast_mes = $z01_d_cadast_mes == "" ? date("m") : $z01_d_cadast_mes;
  $z01_d_cadast_ano = $z01_d_cadast_ano == "" ? date("Y") : $z01_d_cadast_ano;
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
  include("forms/db_frmsaudoc.php");
  ?>
</body>
</html>
<?php
if( isset( $alterar ) ) {

  if( $clcgs_und->erro_status == "0" ) {

    $clcgs_und->erro( true, false );
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if( $clcgs_und->erro_campo != "" ) {

      echo "<script> document.form1.".$clcgs_und->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcgs_und->erro_campo.".focus();</script>";
    }
  } else {
    $clcgs_und->erro( true, false );
  }
}

if( isset( $retornacgs ) && isset($fechar)) {

  $retornacgs  = str_replace( chr(92), "", $retornacgs );
  $retornacgs  = str_replace( chr(39), "", $retornacgs );
  $retornacgs  = str_replace( "p.", "parent.", $retornacgs );
  $retornanome = str_replace( chr(92), "", $retornanome );
  $retornanome = str_replace( chr(39), "", $retornanome );
  $retornanome = str_replace( "p.", "parent.", $retornanome );

  echo "<script>
          $retornacgs  = document.form1.z01_i_cgsund.value;
          $retornanome = document.form1.z01_v_nome.value;
          parent.parent.db_iframe_cgs_und.hide();
       </script>";
}

if( $db_opcao == 22 ) {
  echo "<script>document.form1.pesquisar.click();</script>";
}