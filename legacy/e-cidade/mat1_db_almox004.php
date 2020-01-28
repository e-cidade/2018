<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_db_almox_classe.php");
include("classes/db_db_almoxdepto_classe.php");
$cldb_almox = new cl_db_almox;
$cldb_almox_depto = new cl_db_almoxdepto;

db_postmemory($HTTP_POST_VARS);

$db_opcao = 1;
$db_botao = true;

if (isset($m91_depto)) {

  // verifica se o departamento já não está cadastrado como um depósito
  $cldb_almox->sql_record($cldb_almox->sql_query(null, "m91_depto", null, "m91_depto = $m91_depto"));
  if ($cldb_almox->numrows == 0) {
	
  if (isset($incluir)) {
  	$sqlerro=false;
    db_inicio_transacao();
    $cldb_almox->incluir($m91_codigo);
    if ($cldb_almox->erro_status == 0) {
      $sqlerro=true;
      $erro_msg = $cldb_almox->erro_msg;
    } 

    if ( $sqlerro==false ) {
      $cldb_almox_depto->m92_codalmox = $cldb_almox->m91_codigo;
      $cldb_almox_depto->m92_depto    = $cldb_almox->m91_depto;
      $cldb_almox_depto->incluir($cldb_almox->m91_codigo,$m91_depto);
      if ($cldb_almox->erro_status == 0) {
        $sqlerro=true;
        $erro_msg = $cldb_almox_depto->erro_msg;
      }
    }
    
    if ( $sqlerro==false ) {
      $erro_msg = $cldb_almox->erro_msg;
    }
     
    db_fim_transacao($sqlerro);
    $m91_codigo = $cldb_almox->m91_codigo;
    $db_opcao = 1;
    $db_botao = true;
  }

  } else {    
  	$sqlerro  = true;
    $erro_msg = "Este departamento já é um depósito! Não poderá ser incluído novamente!";
  }

}
  
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<table border="0" style="padding-top:15px" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="center" bgcolor="#CCCCCC"> 
	  <?
	    include("forms/db_frmdb_almox.php");
	  ?>
    </td>
  </tr>
</table>
</center>
</body>
</html>
<?
if(isset($incluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($cldb_almox->erro_campo!=""){
      echo "<script> document.form1.".$cldb_almox->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_almox->erro_campo.".focus();</script>";
    }
  }else{
   db_msgbox($erro_msg);
   db_redireciona("mat1_db_almox005.php?liberaaba=true&chavepesquisa=$m91_codigo");
  }
}
?>