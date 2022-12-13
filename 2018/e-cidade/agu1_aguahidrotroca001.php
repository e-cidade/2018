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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_aguahidrotroca_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$claguahidrotroca = new cl_aguahidrotroca;
$db_opcao = 1;
$db_botao = true;
$lErro    = false;

if(isset($incluir)){
  
  $oDaoHidroMatric = new cl_aguahidromatric();
  
  $sSqlMatricula  = $oDaoHidroMatric->sql_query_file($x28_codhidrometro, 'x04_matric');
  $rsSqlMatricula = db_query($sSqlMatricula);
  
  if (pg_num_rows($rsSqlMatricula) <= 0) {
    db_msgbox('Matricula não encontrada, favor verificar cadastros!');
    $lErro = true;
  }
  
  $iMatricula = db_utils::fieldsMemory($rsSqlMatricula, 0)->x04_matric;
  
  $oColetorExportacao = new clExpDadosColetores();
  
  if ($oColetorExportacao->getImportacaoPendente($iMatricula)) {
    db_msgbox('Existe uma Importação de dados pendente, favor verificar!');
    $lErro = true;
  }

  if (!$lErro) {  
    db_inicio_transacao();
    $claguahidrotroca->incluir($x28_codhidrometro);
    db_fim_transacao();
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmaguahidrotroca.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($incluir)){
  if($claguahidrotroca->erro_status=="0"){
    $claguahidrotroca->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($claguahidrotroca->erro_campo!=""){
      echo "<script> document.form1.".$claguahidrotroca->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$claguahidrotroca->erro_campo.".focus();</script>";
    };
  }else{
    $claguahidrotroca->erro(true,true);
  };
};
?>