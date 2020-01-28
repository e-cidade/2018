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
include("classes/db_acidentes_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clacidentes = new cl_acidentes;
$db_opcao = 1;
$db_botao = true;
db_putsession("id_acidente",0);
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  db_inicio_transacao();
  $clacidentes->tr07_depto = db_getsession("DB_coddepto");
  $clacidentes->incluir($tr07_id);
  db_fim_transacao();
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td  width="100%" height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?
	include("forms/db_frmacidentes.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
//db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  if($clacidentes->erro_status=="0"){
    $clacidentes->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clacidentes->erro_campo!=""){
      echo "<script> document.form1.".$clacidentes->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clacidentes->erro_campo.".focus();</script>";
    }
  }else{
  	db_msgbox("Operação efetuada com Sucesso"); 
    ?>
     <script>
       top.corpo.iframe_condutores.location.href = "tra4_veiculos001.php?tr08_idacidente=<?=@$clacidentes->tr07_id?>";
       top.corpo.iframe_vitimas.location.href = "tra4_vitimas001.php?tr10_idacidente=<?=@$clacidentes->tr07_id?>";       
       parent.document.formaba.condutores.disabled=false;
       parent.document.formaba.vitimas.disabled=false;
       parent.mo_camada('condutores');
     </script>
    <?   
  }
}
?>