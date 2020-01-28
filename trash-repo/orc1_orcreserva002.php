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
include("classes/db_orcreserva_classe.php");
include("dbforms/db_funcoes.php");
require("classes/db_orcdotacao_classe.php"); //classe da dotação
require("libs/db_liborcamento.php");      // funções do orçamento

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clorcreserva = new cl_orcreserva;
$clorcdotacao = new cl_orcdotacao;  //instancia dotação

$db_opcao = 22;
$db_botao = false;
$op=3;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
     if (($o80_valor - $original) > $atual_menos_reservado ){
        echo "<script> alert('Alteração Cancelada : Dotação sem saldo para este valor'); </script> "; 
   } else {
        db_inicio_transacao();
        $clorcreserva->alterar($o80_codres);
        db_fim_transacao();
        $db_opcao=3;
   }  
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clorcreserva->sql_record($clorcreserva->sql_query_reservas($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
  function dot() {
   //  document.form1.submit(); botao pesquisar dotação
  }
  function critica_form(){
     obj = document.form1;
     var valor = new Number(obj.o80_valor.value);
     var original = new Number(obj.original.value);
     var reservado = new Number(obj.atual_menos_reservado.value);
     var dt = new Date();
     var objData  = new Date(dt.getFullYear(),dt.getMonth(),dt.getDate());
     var datalan = new Date(obj.o80_dtlanc_ano.value,(obj.o80_dtlanc_mes.value-1),obj.o80_dtlanc_dia.value);
     var dataini = new Date(obj.o80_dtini_ano.value,(obj.o80_dtini_mes.value-1),obj.o80_dtini_dia.value);
     var datafim = new Date(obj.o80_dtfim_ano.value,(obj.o80_dtfim_mes.value-1),obj.o80_dtfim_dia.value);

      if ( obj.o80_valor.value =="" ) {
           alert('Valor não pode ser nulo ! ');
	   obj.o80_valor.focus();
      } else if  ((valor - original) > reservado )  {
           alert('Valor maior que o saldo da dotação ! ');	
      } else if  ( datafim.getTime() < objData.getTime())  {
           alert('Data final Inválida : menor que a data atual');
      } else if  ( datafim.getTime() < dataini.getTime())  {
           alert('Data final não pode ser menor que a data inicial '); 
      } else {
	   // cria imput com dados do botão 'inclui,altera,exclui
           var opcao= document.createElement("input");
	       opcao.setAttribute("type","hidden");
	       opcao.setAttribute("name","db_opcao");
	       opcao.setAttribute("value",document.form1.db_opcao.value);
 	       document.form1.appendChild(opcao);  
           document.form1.submit();  
      }
  }  
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<!--
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
-->
<BR><BR>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmorcreserva.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
if(!isset($momenulibera)){
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
}
?>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  if($clorcreserva->erro_status=="0"){
    $clorcreserva->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clorcreserva->erro_campo!=""){
      echo "<script> document.form1.".$clorcreserva->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clorcreserva->erro_campo.".focus();</script>";
    };
  }else{
  	if(isset($momenulibera)){
  		db_msgbox($clorcreserva->erro_msg);
  	  echo "<script>top.corpo.db_iframe_orcreservaalt.hide();</script>";
  	  echo "<script>top.corpo.db_iframe_dotac.jan.document.form1.submit();</script>";
  	  exit;
    }
    $clorcreserva->erro(true,true);
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>