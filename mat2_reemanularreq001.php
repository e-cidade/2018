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
$clrotulo = new rotulocampo;
$clrotulo->label("m40_codigo");
$db_opcao = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_abre(){
   obj = document.form1;
   query='';
   if (obj.m40_codigo.value!=''){
    query += "&ini="+obj.m40_codigo.value;
    query += "&fim="+obj.m40_codigo.value;
   }
   sDataIni = new String(obj.dtini.value).trim();
   sDataFim = new String(obj.dtfim.value).trim();
   if(sDataIni!='' || sDataFim!=''){
    query += "&perini="+sDataIni;
	query += "&perfim="+sDataFim;    
   }
   if(query==''){
    alert("Selecione algum numero de requisição ou indique o período!");
   }else{
	    jan = window.open('mat2_anulareq001.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);    
   }  
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<center><br><br></br>
<table valign="top" marginwidth="0" width="790" border="0" cellspacing="0" cellpadding="0">
 <tr> 
  <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
  <fieldset style="width:95%"><legend><b>Reemissão de Anulação de Requisição</b></legend>
    <form name='form1'>
    <table>    
      <tr>
	<td nowrap title="<?=@$Tm40_codigo?>">
	   <? db_ancora("<b>Nº Requisição:</b>","js_pesquisam40_codigo(true);",1); ?>
	</td>
	<td> 
	   <? db_input('m40_codigo',15,@$Im40_codigo,true,'text',$db_opcao,"")?>
	</td>
      </tr>

    <tr>
     <td align="right" > 
       <b> Período da anulação:</b>
     </td>  
     <td>
      <?  db_inputdata('dtini',@$dia,@$mes,@$ano,true,'text',1,"");   		          
          echo " a ";
          db_inputdata('dtfim',@$dia,@$mes,@$ano,true,'text',1,"");
       ?>
     </td>
     </tr> 
     <tr>
       <td colspan='2' align='center'>
         <input name='pesquisar' type='button' value='Consultar' onclick='js_abre();'>      
       </td>
     </tr>
    </table>    
    </form>
    </fieldset>
  </td>
 </tr>
</table>
</center>
    <? 
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>
<script>
function js_pesquisam40_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_matpedido','func_matrequireemisanul.php?funcao_js=parent.js_mostramatpedido1|m40_codigo','Pesquisa',true);
  }
}
function js_mostramatpedido1(chave1){
  document.form1.m40_codigo.value = chave1;
  db_iframe_matpedido.hide();
}
</script>