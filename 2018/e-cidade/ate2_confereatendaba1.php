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
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
/* echo "<br><br>sel = $ssel4[0] <br> ";
 echo " sel = $ssel4[1] <br> ";
 echo " sel = $ssel4[2] <br> "; */

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript"
	src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"
	marginheight="0" onLoad="a=1">
<form name="form1">
<table width="95%" border="0" cellpadding="0" cellspacing="0"
	bgcolor="#CCCCCC" align="center">
	<tr>
		<td width="360" height="18">&nbsp;</td>
		<td width="263">&nbsp;</td>
		<td width="25">&nbsp;</td>
		<td width="140">&nbsp;</td>
	</tr>
	<tr>

</table>
<table width="95%" border="0" cellpadding="0" cellspacing="0"
	bgcolor="#CCCCCC" align="center">
	<tr>
		<td height="30">&nbsp;</td>
	</tr>
	<tr>
		<td align='center' colspan=2><b> Período : </b> <? 
		db_inputdata('data1','','','',true,'text',1,"");
		echo "<b> a </b> ";
		db_inputdata('data2','','','',true,'text',1,"");
		?></td>
	</tr>
	<tr>
		<td height="30">&nbsp;</td>
	</tr>

	<tr>
		<td align="center"><b> Motivos</b></td>
	</tr>

	<tr>
		<td align="center"><?

		// motivo
		$sqlmot ="select  at54_sequencial,at54_descr from tarefacadmotivo where at54_tipo = 1 order by at54_descr";
		$resultmot= pg_query($sqlmot);
		db_multiploselect("at54_sequencial", "at54_descr", "nsel1", "ssel1", $resultmot, array(), 4, 250);
		?></td>
	</tr>
	<tr>
		<td height="30">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b> Clientes</b></td>
	</tr>
	<tr>
		<td align="center"><?
		//cliente
		$sqlcliente = "select at01_codcli,at01_nomecli from clientes where at01_ativo is true";
		$resultcliente=pg_query($sqlcliente);
		db_multiploselect("at01_codcli", "at01_nomecli", "nsel2", "ssel2", $resultcliente, array(), 4, 250);
		?></td>
	</tr>
	<tr>
		<td height="30">&nbsp;</td>
	</tr>
	<tr>
		<td align="center">
      <?
      $arr_tipo = Array(
                          "1"=>"Tudo",
                          "2"=>"Somente Totais",
                          "3"=>"Somente Tarefas"
                         );
      db_select("tipo", $arr_tipo, true, 1, "");
      ?>

    </td>
	</tr>
	<tr>
		<td height="30">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><input name="processa" type="button"
			value="Processa" onclick="js_mandadados()"></td>
	</tr>


</table>
</form>
</body>
</html>
<script>

function js_pegaValores(obj){
  var lista = '';
  var vir = '';
  for(x=0;x<obj.length;x++){
    lista += vir+obj.options[x].value;
    vir=",";
  }

}

function js_mandadados(){
 vir="";
 listamotivo="";
 for(x=0;x<document.form1.ssel1.length;x++){
  listamotivo+=vir+document.form1.ssel1.options[x].value;
  vir=",";
 }
 vir="";
 listacliente="";
 for(x=0;x<document.form1.ssel2.length;x++){
  listacliente+=vir+document.form1.ssel2.options[x].value;
  vir=",";
 }
  vir="";
 listatecnico="";
 for(x=0;x<parent.iframe_g2.document.form1.ssel3.length;x++){
  listatecnico+=vir+parent.iframe_g2.document.form1.ssel3.options[x].value;
  vir=",";
 }
   vir="";
 listamodulo="";
 for(x=0;x<parent.iframe_g2.document.form1.ssel4.length;x++){
  listamodulo+=vir+parent.iframe_g2.document.form1.ssel4.options[x].value;
  vir=",";
 }
 
 vir="";
 listaarea="";
 for(x=0;x<parent.iframe_g2.document.form1.ssel7.length;x++){
  listaarea+=vir+parent.iframe_g2.document.form1.ssel7.options[x].value;
  vir=",";
 }
 
 vir="";
 listaproced="";
 if(parent.iframe_g3.document.form1.ssel5){
	 for(x=0;x<parent.iframe_g3.document.form1.ssel5.length;x++){
	  listaproced+=vir+parent.iframe_g3.document.form1.ssel5.options[x].value;
	  vir=",";
	 }
 }
 
 obj = document.form1;
  data_ini =  obj.data1_ano.value+'-'+obj.data1_mes.value+'-'+obj.data1_dia.value;
  data_fin =  obj.data2_ano.value+'-'+obj.data2_mes.value+'-'+obj.data2_dia.value;
 if (data_fin < data_ini){
     alert('Datas invalidas');
     return;
  }

 //alert ('data1='+data_ini+'data2='+data_fin+'motivo='+listamotivo+'cliente='+listacliente+'tecnico='+listatecnico+'modulo='+listamodulo+'proced='+listaproced);
 if((data_ini=="--")||(data_fin=="--")){	
 	alert ('Obrigatório o preenchimento do período.');
 }else{ 
// 	js_OpenJanelaIframe('','db_iframe_relatorio','ate2_confereatend002.php?data1='+data_ini+'&data2='+data_fin+'&motivo='+listamotivo+'&cliente='+listacliente+'&tecnico='+listatecnico+'&modulo='+listamodulo+'&proced='+listaproced,'Pesquisa',false);
  jan = window.open('ate2_confereatend002.php?tipo='+document.form1.tipo.value+'&data1='+data_ini+'&data2='+data_fin+'&motivo='+listamotivo+'&cliente='+listacliente+'&tecnico='+listatecnico+'&modulo='+listamodulo+'&proced='+listaproced+'&areas='+listaarea,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
 }
}

</script>