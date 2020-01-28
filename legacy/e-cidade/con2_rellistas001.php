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
$clrotulo->label("d40_codigo");
$clrotulo->label("j14_nome");
$db_opcao = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_relatorio() {
  obj=document.form1;
  id=obj.dataini_dia.value;
  im=obj.dataini_mes.value;
  ia=obj.dataini_ano.value;
  fd=obj.datafim_dia.value;
  fm=obj.datafim_mes.value
  fa=obj.datafim_ano.value;
  dataini="";
  datafim="";
  if(id!="" && im !="" && ia!=""){
    dataini = ia+"X"+im+"X"+id;
  }  
  if(fd!="" && fm !="" && fa!=""){
     datafim = fa+"X"+fm+"X"+fd;
  } 
  if((dataini=="" && datafim=="") && (id!="" || im !="" || ia!="" || fd!="" && fm !="" && fa!="")){
     alert('Verifique as datas fornecidas!');
     
  }else{ 
       ordem=document.form1.ordem.value;
       jan = window.open('con2_rellistas002.php?ordem='+ordem+'&dataini='+dataini+'&datafim='+datafim,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
       jan.moveTo(0,0);
       return true;
  }  
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1) document.form1.elements[0].focus()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	  <br>
	<center>
        <form name="form1" method="post" action="">
          <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td nowrap title="<?=@$Td40_codigo?>">
	    <b>Listas a partir de </b>
	  </td>
	  <td>
<?
db_inputdata('dataini',"","","",true,'text',$db_opcao,"")
?>
	  <b>até </b>
<?
db_inputdata('datafim',"","","",true,'text',$db_opcao,"")
?>

          </td>
        </tr>
	<tr>
	  <td>
	    <b>Ordenar por</b>
	  </td>
	  <td>
<? 
$result=array("d40_data"=>"DATA","j14_nome"=>"LOGRADOURO","d40_codigo"=>"LISTAS");
db_select("ordem",$result,true,$db_opcao);
?>
	  </td>
	</tr>
        <tr>
	  <td  align="center" colspan="2" align="center">
	    <br>
	    <input name="gerar" type="button" id="boletim" onClick="js_relatorio()" value="Gerar relatório">
	  </td>
        </tr>
       </table>
     </form>
      </center>
	</td>
  </tr>
</table>
    <? 
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>
<script>
function js_contri(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_projmelhorias.php?funcao_js=parent.js_mostracontri1|d40_codigo|j14_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_projmelhorias.php?pesquisa_chave='+document.form1.d40_codigo.value+'&funcao_js=parent.js_mostracontri','Pesquisa',false);
  }
}
function js_mostracontri(chave,erro){
  if(erro==true){ 
    document.form1.d40_codigo.focus(); 
    document.form1.d40_codigo.value=""; 
    document.form1.j14_nome.value=""; 
  }else{
      document.form1.j14_nome.value = chave;
  }  
}
function js_mostracontri1(chave1,chave2){
  document.form1.d40_codigo.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe.hide();
}
</script>