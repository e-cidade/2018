<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_matrequi_classe.php");
include("classes/db_matrequiitem_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_db_almox_classe.php");
include("classes/db_db_depusu_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_matestoqueini_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
//if (substr($DB_BASE,0,5) != "ontem") {
//	  die("rotina indisponivel");
//}
$clmatrequi = new cl_matrequi;
$clmatrequiitem = new cl_matrequiitem;
$cldb_depart = new cl_db_depart;
$cldb_dbalmox = new cl_db_almox;
$cldb_depusu = new cl_db_depusu;
$cldb_usuarios = new cl_db_usuarios;
$clmatestoqueini = new cl_matestoqueini;
$db_opcao = 1;
$opcao = 1;
$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
  $sqlerro=false;

  $sqlalmox = $cldb_dbalmox->sql_query_file($m40_almox, "*");
  $resalmox = $cldb_dbalmox->sql_record($sqlalmox);
  if($cldb_dbalmox->numrows==0) {
    $sqlerro=true;
    $erro_msg="Departamento $coddepto não é um Almoxarifado!";
  }

  if($sqlerro==false) {
    $clmatrequi->m40_auto  = 'false';
    $clmatrequi->m40_almox = $m40_almox;
    $clmatrequi->incluir($m40_codigo);
    $erro_msg=$clmatrequi->erro_msg;
    if ($clmatrequi->erro_status==0){
      $sqlerro=true;
    }
    $codigo=$clmatrequi->m40_codigo;
  }

  db_fim_transacao($sqlerro);
}else{
  $m40_data_dia=date('d',db_getsession("DB_datausu"));
  $m40_data_mes=date('m',db_getsession("DB_datausu"));
  $m40_data_ano=date('Y',db_getsession("DB_datausu"));
  $m40_depto=db_getsession("DB_coddepto");
  $result_depto=$cldb_depart->sql_record($cldb_depart->sql_query_file($m40_depto,'descrdepto'));
  if ($cldb_depart->numrows!=0){
    db_fieldsmemory($result_depto,0);
  }
  $m40_login=db_getsession("DB_id_usuario");
  $result_login=$cldb_usuarios->sql_record($cldb_usuarios->sql_query_file($m40_login,'nome'));
  if ($cldb_usuarios->numrows!=0){
    db_fieldsmemory($result_login,0);
  }
  $m40_hora=db_hora();
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
<table width="790" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmmatrequi.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($clmatrequi->erro_status=="0"){
    $clmatrequi->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>";
    if($clmatrequi->erro_campo!=""){
      echo "<script> document.form1.".$clmatrequi->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmatrequi->erro_campo.".focus();</script>";
    };
  }else{
//    $clmatrequi->erro(true,true);
    db_msgbox($erro_msg);
    echo "<script>
               parent.iframe_matrequi.location.href='mat1_matrequi002.php?chavepesquisa=".@$codigo."';\n
	 </script>";
  };
};
?>