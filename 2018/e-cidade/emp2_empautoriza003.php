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
include("libs/db_usuariosonline.php");
include("classes/db_caracter_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_empautoriza_classe.php");
$clempautoriza = new cl_empautoriza;
$cliframe_seleciona = new cl_iframe_seleciona;
$clrotulo = new rotulocampo;
$clempautoriza->rotulo->label();
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
        <form name="form1" method="post" action = "emp2_empautoriza002.php" target="rel">
           <center>
             <table border="0">
	       <tr>
	         <td align="top" colspan="3">
	           <script>
		   //função para buscar dados dos iframes        action = "emp2_empautoriza002.php" target="rel"
                   function buscar_dados(frame,campo,obj,campo_inp,campo_par,campo_cod){
		     cods = "";
	             vir  = "";
		     ini  = "";
		     fim  = "";
		     varinicio = eval("parent.iframe_"+frame+".document.form1."+campo+"INI.value");
		     varfinal  = eval("parent.iframe_"+frame+".document.form1."+campo+"FIM.value");
		     if(varinicio!="" || varfinal!=""){
		       ini = eval("parseInt(parent.iframe_"+frame+".document.form1."+campo+"INI.value)");
		       fim = eval("parseInt(parent.iframe_"+frame+".document.form1."+campo+"FIM.value)"); 
		     }
		     var_obj = eval("parent.iframe_"+frame+".document.getElementById('"+obj+"').length");
	             for(y=0;y<var_obj;y++){
                       var_if = eval("parseInt(parent.iframe_"+frame+".document.getElementById('"+obj+"').options[y].value)");
		       if((ini!="" && var_if>=ini && fim!="" && var_if<=fim) || ((fim=="" || isNaN(fim)) && ini!="" && var_if>=ini) || ((ini=="" || isNaN(ini)) && fim!="" && var_if<=fim) || (ini=="" && fim=="")  || (isNaN(ini) && isNaN(fim))){
			 cods += vir + var_if;
			 vir = ",";
		       }
		     }
		     eval("parent.iframe_g1.document.form1."+campo_inp+".value =cods");
		     eval("parent.iframe_g1.document.form1."+campo_par+".value =parent.iframe_"+frame+".document.form1.param_"+obj+".value");
		     if(ini!="" || fim!=""){
		       eval ("parent.iframe_g1.document.form1."+campo_cod+".value =ini+'-'+fim");
		     }
		   }
		   
	           //pegando os dados pelo ID dos documentos nos outros forms         
	           function imprime(){
		     dtini = "";
		     dtfim = "";
		     x = document.form1;
		     if(x.e54_emissINI_dia.value!="" && x.e54_emissINI_mes.value!="" && x.e54_emissINI_ano.value!=""){
		       dtini= new Date(x.e54_emissINI_ano.value,x.e54_emissINI_mes.value-1,x.e54_emissINI_dia.value);
		     }
		     if(x.e54_emissFIM_dia.value!="" && x.e54_emissFIM_mes.value!="" && x.e54_emissFIM_ano.value!=""){
		       dtfim= new Date(x.e54_emissFIM_ano.value,x.e54_emissFIM_mes.value-1,x.e54_emissFIM_dia.value);
		     }
		     if(dtini!="" && dtfim!="" && dtfim<dtini){
		       alert("ERRO: Informe um intervalo de data válido!");
		       x.e54_emissINI_dia.focus();
		       return false;
		     }
		     for (i = 0;i < x.length;i++){
		       if (x.elements[i].type == "text"){
			 if (x.elements[i].name.indexOf('inp_')!=-1 || x.elements[i].name.indexOf('cod_')!=-1 || x.elements[i].name.indexOf('par_')!=-1){
                           x.elements[i].value = "";
		 	 }
		       }
		     }
                     buscar_dados("g1","e54_autori","autoriza","inp_autoriza","par_autoriza","cod_autoriza");
                     buscar_dados("g2","z01_numcgm","cgm","inp_cgm","par_cgm","cod_cgm");
                     buscar_dados("g3","id_usuario","db_usuarios","inp_usuarios","par_usuarios","cod_usuarios");
                     buscar_dados("g4","pc50_codcom","pctipocompra","inp_tipcompra","par_tipcompra","cod_tipcompra");
                     buscar_dados("g5","codigo","db_config","inp_config","par_config","cod_config");
                     buscar_dados("g6","e54_depto","db_depart","inp_depart","par_depart","cod_depart");
		     
		     jan = window.open('','rel','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
		     jan.moveTo(0,0);
		   }
                   function js_testa(campo,valor){
	             msg = "Informe um intervalo de código válido!";
		     erro = false;
		     if(campo=="i"){
		       if(document.form1.e54_autoriFIM.value!="" && parseInt(valor)>parseInt(document.form1.e54_autoriFIM.value)){
			 erro = true;
		       }
		     }else if(campo=="f"){
		       if(document.form1.e54_autoriINI.value!="" && parseInt(valor)<parseInt(document.form1.e54_autoriINI.value)){
			 erro = true;
		       }
		     }
		     if(erro == true){
		       alert(msg);
		       document.form1.e54_autoriINI.value = "";
		       document.form1.e54_autoriFIM.value = "";
		       document.form1.e54_autoriINI.focus();
                     }
		   }
		   </script>
	         </td>
	       </tr>
	       <tr>
                 <td align="left" colspan="3">
                   <table>
                     <tr>
	               <td valign='center' nowrap align="left" colspan="1" title="<?=@$Te54_autori?>">
	               <strong>Autorizações de</strong>
		       </td>
		       <td valign='center'>      
		     <?
                 db_input('e54_autori',8,$Ie54_autori,true,'text',1,"onChange=\"js_testa('i',this.value)\"","e54_autoriINI","");
	             ?>
		       <strong>&nbsp;À&nbsp;</strong>
		     <?
		 db_input('e54_autori',8,$Ie54_autori,true,'text',1,"onChange=\"js_testa('f',this.value)\"","e54_autoriFIM","");
		     ?>
		       </td>
                     </tr>
                   </table>
	         </td>
	       </tr>
	       <tr>
                 <td align="left" colspan="3">
                   <table>
                     <tr>
		       <td valign='center' nowrap align="left" title="<?=@$Te54_emiss?>">
	               <strong>Data de emissão</strong>
		       </td>
		       <td valign='center'>
			   <?
		       db_inputdata('e54_emiss',@$e54_emiss_dia,@$e54_emiss_mes,@$e54_emiss_ano,true,'text',1,"","e54_emissINI")
			   ?>
			   <strong>&nbsp;À&nbsp;</strong>
			   <?
		       db_inputdata('e54_emiss',@$e54_emiss_dia,@$e54_emiss_mes,@$e54_emiss_ano,true,'text',1,"","e54_emissFIM")
			    ?>
                       </td>
                     </tr>
                   </table>
		 </td>
               </tr>
	       <tr>
		 <td colspan="3" align="center">
		   <table>
		     <tr>
		       <td align="center">
			  <?
			  $aux = new cl_arquivo_auxiliar;
			  $aux->cabecalho = "<strong>AUTORIZAÇÕES DE EMPENHO</strong>";
			  $aux->codigo = "e54_autori";
			  $aux->descr  = "z01_nome";
			  $aux->nomeobjeto = 'autoriza';
			  $aux->funcao_js = 'js_mostra';
			  $aux->funcao_js_hide = 'js_mostra1';
			  $aux->sql_exec  = "";
			  $aux->func_arquivo = "func_empautoriza.php";
			  $aux->nomeiframe = "db_iframe_empautoriza";
			  $aux->localjan = "";
			  $aux->db_opcao = 2;
			  $aux->tipo = 2;
			  $aux->top = 2;
			  $aux->linhas = 5;
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
		   db_select('param_autoriza',$xxx,true,2);
		   ?>
		 </td>
	         <td align="center">
	           <input type="submit" name="relatorio1" value="Relatório" onClick="return imprime();"> 
	         </td>
	       </tr>
	       <tr>
		 <td align="right"><strong>Ordem de seleção: </strong></td>
		 <td align="left">&nbsp;&nbsp;&nbsp;
		   <select name="ordem">
		     <option  value="e54_autori">Autorização de empenho</option>
		     <option   value="e54_emiss">Data da emissão</option>
		     <option    value="z01_nome">Nome do credor</option>
		     <option        value="nome">Nome do usuário</option>
		     <option value="pc50_descr">Tipo de compra</option>
		     <option    value="nomeinst">Instituição </option>
		   </select>
		 </td>
		 <td>&nbsp;
		 </td>
	       </tr>
               <tr>
	         <td align="right"> <strong>Processar :<strong></td>
		 <td align="left" colspan=2 >&nbsp;&nbsp;&nbsp;
		   <?
		   $rrr= array("N"=>"Não Anuladas","A"=>"Anuladas","T"=>"Todas");
		   db_select('anula',$rrr,true,2);
		   ?>
		 </td>
	       </tr>
               <tr>
	         <td align="right"> <strong>Tipo :<strong></td>
		 <td align="left" colspan=2 >&nbsp;&nbsp;&nbsp;
		   <?
		   $rr= array("T"=>"Todas","E"=>"Empenhadas","N"=>"Não Empenhadas");
		   db_select('tipo',$rr,true,2);
		   
		   ?>
		 </td>
	       </tr>
               <tr>
	         <td align="right"> <strong>Listar Itens :<strong></td>
		 <td align="left" colspan=2 >&nbsp;&nbsp;&nbsp;
		   <?
		   $ll= array("s"=>"Sim","n"=>"Não");
		   db_select('listar',$ll,true,2);
		   ?>
		 </td>
	       </tr>
		 
	     </table>
	   </center>
		     <?
		 db_input('inp_autoriza',"",0,true,'hidden',3,"");
		 db_input('cod_autoriza',"",0,true,'hidden',3,"");
		 db_input('par_autoriza',"",0,true,'hidden',3,"");
		     ?>
		     <br>
		     <?
		 db_input('inp_cgm',"",0,true,'hidden',3,"");
		 db_input('cod_cgm',"",0,true,'hidden',3,"");
		 db_input('par_cgm',"",0,true,'hidden',3,"");
		     ?>
		     <br>
		     <?
		 db_input('inp_usuarios',"",0,true,'hidden',3,"");
		 db_input('cod_usuarios',"",0,true,'hidden',3,"");
		 db_input('par_usuarios',"",0,true,'hidden',3,"");
		     ?>
		     <br>
		     <?
		 db_input('inp_tipcompra',"",0,true,'hidden',3,"");
		 db_input('cod_tipcompra',"",0,true,'hidden',3,"");
		 db_input('par_tipcompra',"",0,true,'hidden',3,"");
		     ?>
		     <br>
		     <?
		 db_input('inp_config',"",0,true,'hidden',3,"");
		 db_input('cod_config',"",0,true,'hidden',3,"");
		 db_input('par_config',"",0,true,'hidden',3,"");
		     ?>
		     <br>
		     <?
		 db_input('inp_depart',"",0,true,'hidden',3,"");
		 db_input('cod_depart',"",0,true,'hidden',3,"");
		 db_input('par_depart',"",0,true,'hidden',3,"");
		     ?>
		     <br>
	 </form>
       </center>
     </td>
   </tr>
</table>
</body>
</html>