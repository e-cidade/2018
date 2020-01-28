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
include("dbforms/db_classesgenericas.php");
include("classes/db_db_depart_classe.php");
include("classes/db_solicita_classe.php");
$cldb_depart = new cl_db_depart;
$clsolicita = new cl_solicita;
$cliframe_seleciona = new cl_iframe_seleciona;
$clrotulo = new rotulocampo;
$cldb_depart->rotulo->label();
$clsolicita->rotulo->label();
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
        <form name="form1" method="post" action = "com2_solic002.php" target="rel">
           <center>
             <table border="0">
	       <tr>
	         <td align="top" colspan="4">
	           <script>
		   //função para buscar dados dos iframes        action = "emp2_db_depart002.php" target="rel"
                   function buscar_dados(frame,campo,obj,campo_inp,campo_par){
		     cods = "";
	             vir  = "";
		     var_obj = eval("parent.iframe_"+frame+".document.getElementById('"+obj+"').length");
	             for(y=0;y<var_obj;y++){
                       var_if = eval("parseInt(parent.iframe_"+frame+".document.getElementById('"+obj+"').options[y].value)");
		       cods += vir + var_if;
		       vir = ",";
		     }
		     eval("parent.iframe_g1.document.form1."+campo_inp+".value =cods");
		     eval("parent.iframe_g1.document.form1."+campo_par+".value =parent.iframe_"+frame+".document.form1.param_"+obj+".value");
		   }
		   
	           //pegando os dados pelo ID dos documentos nos outros forms         
	           function imprime(){
		     dtini = "";
		     dtfim = "";
		     x = document.form1;
		     if(x.pc10_dataINI_dia.value!="" && x.pc10_dataINI_mes.value!="" && x.pc10_dataINI_ano.value!=""){
		       dtini= new Date(x.pc10_dataINI_ano.value,x.pc10_dataINI_mes.value-1,x.pc10_dataINI_dia.value);
		     }
		     if(x.pc10_dataFIM_dia.value!="" && x.pc10_dataFIM_mes.value!="" && x.pc10_dataFIM_ano.value!=""){
		       dtfim= new Date(x.pc10_dataFIM_ano.value,x.pc10_dataFIM_mes.value-1,x.pc10_dataFIM_dia.value);
		     }
		     if(dtini!="" && dtfim!="" && dtfim<dtini){
		       alert("ERRO: Informe um intervalo de data válido!");
		       x.pc10_dataINI_dia.focus();
		       return false;
		     }
		     for (i = 0;i < x.length;i++){
		       if (x.elements[i].type == "text"){
			 if (x.elements[i].name.indexOf('inp_')!=-1 || x.elements[i].name.indexOf('cod_')!=-1 || x.elements[i].name.indexOf('par_')!=-1){
                           x.elements[i].value = "";
		 	 }
		       }
		     }
                     buscar_dados("g1","pc10_numero","depart","inp_depart","par_depart");
                     buscar_dados("g2","pc50_codcom","tipcom","inp_tipcom","par_tipcom");
                     buscar_dados("g3","oc01_mater","mater","inp_mater","par_mater");
                     buscar_dados("g4","pc13_coddot","dotac","inp_dotac","par_dotac");

		     jan = window.open('','rel','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
		     jan.moveTo(0,0);
		   }
                   function js_testa(campo,valor){
	             msg = "Informe um intervalo de código válido!";
		     erro = false;
		     if(campo=="i"){
		       if(document.form1.pc10_numeroFIM.value!="" && parseInt(valor)>parseInt(document.form1.pc10_numeroFIM.value)){
			 erro = true;
		       }
		     }else if(campo=="f"){
		       if(document.form1.pc10_numeroINI.value!="" && parseInt(valor)<parseInt(document.form1.pc10_numeroINI.value)){
			 erro = true;
		       }
		     }
		     if(erro == true){
		       alert(msg);
		       document.form1.pc10_numeroINI.value = "";
		       document.form1.pc10_numeroFIM.value = "";
		       document.form1.pc10_numeroINI.focus();
                     }
		   }
		   </script>
	         </td>
	       </tr>
	       <tr>
                 <td align="left" colspan="4">
                   <table>
                     <tr>
	               <td valign='center' nowrap align="left" colspan="1" title="<?=@$Tpc10_numero?>">
	               <strong>Solicitações de</strong>
		       </td>
		       <td valign='center'>      
		     <?
                 db_input('pc10_numero',8,$Ipc10_numero,true,'text',1,"onChange=\"js_testa('i',this.value)\"","pc10_numeroINI","");
	             ?>
		       <strong>&nbsp;À&nbsp;</strong>
		     <?
		 db_input('pc10_numero',8,$Ipc10_numero,true,'text',1,"onChange=\"js_testa('f',this.value)\"","pc10_numeroFIM","");
		     ?>
		       </td>
                     </tr>
                   </table>
	         </td>
	       </tr>
	       <tr>
                 <td align="left" colspan="4">
                   <table>
                     <tr>
		       <td valign='center' nowrap align="left" title="<?=@$Tpc10_data?>">
	               <strong>Período</strong>
		       </td>
		       <td valign='center'>
			   <?
		       db_inputdata('pc10_data',@$pc10_data_dia,@$pc10_data_mes,@$pc10_data_ano,true,'text',1,"","pc10_dataINI")
			   ?>
			   <strong>&nbsp;À&nbsp;</strong>
			   <?
		       db_inputdata('pc10_data',@$pc10_data_dia,@$pc10_data_mes,@$pc10_data_ano,true,'text',1,"","pc10_dataFIM")
			    ?>
                       </td>
                     </tr>
                   </table>
		 </td>
               </tr>
	       <tr>
		 <td colspan="4" align="center">
		   <table>
		     <tr>
		       <td align="center">
			  <?
			  $aux = new cl_arquivo_auxiliar;
			  $aux->cabecalho = "<strong>DEPARTAMENTOS DAS SOLICITAÇÕES</strong>";
			  $aux->codigo = "coddepto";
			  $aux->descr  = "descrdepto";
			  $aux->nomeobjeto = 'depart';
			  $aux->funcao_js = 'js_mostra';
			  $aux->funcao_js_hide = 'js_mostra1';
			  $aux->sql_exec  = "";
			  $aux->func_arquivo = "func_db_depart.php";
			  $aux->nomeiframe = "db_iframe_db_depart";
			  $aux->localjan = "";
			  $aux->db_opcao = 2;
			  $aux->tipo = 2;
			  $aux->top = 2;
			  $aux->linhas = 10;
			  $aux->vwhidth = 400;
			  $aux->funcao_gera_formulario();
			  ?>
		       </td>
		     </tr>
		   </table>
		 </td>
	       </tr>
               <tr>
	         <td align="right"> <strong>Opção de Seleção :<strong></td>
		 <td align="left">&nbsp;&nbsp;&nbsp;
		   <?
		   $xxx = array("S"=>"Somente Selecionados&nbsp;&nbsp;","N"=>"Menos os Selecionados&nbsp;&nbsp;");
		   db_select('param_depart',$xxx,true,2);
		   ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	           <input type="submit" name="relatorio1" value="Relatório" onClick="return imprime();"> 
		 </td>
	       </tr>
	       <tr>
		 <td align="right"><strong>Ordem de seleção: </strong></td>
		 <td align="left">&nbsp;&nbsp;&nbsp;
		   <?
		   $yyy = array("pc10_numero"=>"Solicitações","pc10_data"=>"Data emissão","descrdepto"=>"Departamento","pc50_descr"=>"Tipo de compra");
		   db_select('ordem',$yyy,true,2);
		   ?>
		 </td>
	       </tr>
	       <tr>
		 <td align="right"><strong>Totalização por:</strong></td>
		 <td align="left">&nbsp;&nbsp;
		   <input type="checkbox" name="totdepar" checked>Departamento
		   <input type="checkbox" name="tottipco" checked>Tipo de compra
		   <input type="checkbox" name="totmater" checked>Material
		   <input type="checkbox" name="totdotac" checked>Dotação
		 </td>
	       </tr>
	       <tr>
	         <td align="right"><b>Situação da Solicitação:</b></td>
	         <td align="left">&nbsp;&nbsp;
		 <?
		   $matriz = array("T"=>"Todas","A"=>"Aut. empenho","N"=>"Não Aut. empenho");
		   db_select("situacao",$matriz,true,2);
		 ?>
		 </td>
	       </tr>
	       <tr>
	         <td align="right"><b>Mostrar Itens:</b></td>
	         <td align="left">&nbsp;&nbsp;
		 <?
		   $matriz = array("S"=>"Sim","N"=>"Não");
		   db_select("mostrar_itens",$matriz,true,2);
		 ?>
		 </td>
	       </tr>
	     </table>
	   </center>
		     <?
		 db_input('inp_depart',"",0,true,'hidden',3,"");
		 db_input('par_depart',"",0,true,'hidden',3,"");
		     ?>
		     <?
		 db_input('inp_tipcom',"",0,true,'hidden',3,"");
		 db_input('par_tipcom',"",0,true,'hidden',3,"");
		     ?>
		     <?
		 db_input('inp_mater',"",0,true,'hidden',3,"");
		 db_input('par_mater',"",0,true,'hidden',3,"");
		     ?>
		     <?
		 db_input('inp_dotac',"",0,true,'hidden',3,"");
		 db_input('par_dotac',"",0,true,'hidden',3,"");
		     ?>
	 </form>
       </center>
     </td>
   </tr>
</table>
</body>
<script>
document.form1.totdepar.selected=true;
document.form1.tottipco.selected=true;
document.form1.totmater.selected=true;
document.form1.totdotac.selected=true;
</script>
</html>