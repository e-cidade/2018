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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_lotenumero_classe.php");
require_once("classes/db_lotenumero_proc_classe.php");
require_once("classes/db_protprocesso_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$cllotenumero      = new cl_lotenumero;
$cllotenumero_proc = new cl_lotenumero_proc;
$clprotprocesso    = new cl_protprocesso;

$j12_data_dia = date("d");
$j12_data_mes = date("m");
$j12_data_ano = date("Y");
$db_opcao = 1;
$db_botao = true;

if (isset($incluir)) {
  $sqlerro = false; 
  db_inicio_transacao();
  if($sqlerro == false){
    $cllotenumero->incluir(null);
    $erro_msg=$cllotenumero->erro_msg;
    if($cllotenumero->erro_status==0){
      $sqlerro=true;
    }
  }
  if($sqlerro == false){
    $codigo = $cllotenumero->j12_codigo;
    $cllotenumero_proc->j11_processo = $p58_codproc;
    $cllotenumero_proc->incluir($codigo);  
    $erro_msg=$cllotenumero->erro_msg;
    if($cllotenumero->erro_status==0){
      $sqlerro=true;
    }
  }
  db_fim_transacao();
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" style="margin-top: 25px;">

<table align="center" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmlotenumero.php");
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
  if($cllotenumero->erro_status=="0"){
    $cllotenumero->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cllotenumero->erro_campo!=""){
      echo "<script> document.form1.".$cllotenumero->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cllotenumero->erro_campo.".focus();</script>";
    };
  }else{
    $cllotenumero->erro(true,true);
  };
};
?>