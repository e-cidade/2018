<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_bens_classe.php");
$aux = new cl_arquivo_auxiliar;
$clbens = new cl_bens;
$clbens->rotulo->label();
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_abre(){
  variavel = 1;
  vir="";
  listagem="";
  ok=true;
  if(document.form1.t52_bem_ini.value!="" && document.form1.t52_bem_fim.value!="" && document.form1.t52_bem_fim.value>document.form1.t52_bem_ini.value || document.form1.t52_bem_fim.value<document.form1.t52_bem_ini.value && document.form1.t52_bem_fim.value==document.form1.t52_bem_ini.value){
    for(i=0;i<document.form1.length;i++){
      if(document.form1.elements[i].name == "altbem[]"){
	for(x=0;x< document.form1.elements[i].length;x++){
	  listagem+=vir+document.form1.elements[i].options[x].value;
	  vir=",";
	}
      }
    }
    param=document.form1.param.value;
    ini = document.form1.t52_bem_ini.value;
    fim = document.form1.t52_bem_fim.value;
    parent.document.formaba.bensimoveis.disabled=false;
    parent.document.formaba.bensmater.disabled=false;
    parent.document.formaba.bensbaix.disabled=false;
    top.corpo.iframe_bensimoveis.location.href='pat1_bensimoveis001.php?global=true';
    top.corpo.iframe_bensmater.location.href='pat1_bensmater001.php?global=true';
    location.href="pat1_bens005.php?lista="+listagem+"&param="+param+"&ini="+ini+"&fim="+fim;;
  }else{
    alert("Informe corretamente o intervalo de código dos bens a ser alterados.");
  }
}
function js_isnumber(campo,nome){
  campo = campo.replace(".",",");
  campo1=new Number(campo);
  if(isNaN(campo1)){
    alert("Este campo deve ser preenchido somente com valores inteiros.");
  }
  if(document.form1.t52_bem_ini.value=="" || document.form1.t52_bem_fim.value=="" || document.form1.t52_bem_fim.value<document.form1.t52_bem_ini.value || document.form1.t52_bem_fim.value==document.form1.t52_bem_ini.value){
    document.form1.db_lanca.disabled=true;
  }else{
    document.form1.db_lanca.disabled=false;
  }
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="document.form1.db_lanca.disabled=true;">
<form name="form1">
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
      <center>
      <table>
	<tr>
          <td align="center" colspan="3">
	    <table width="425">
	      <tr>
                <td>
		  <center>
                  <fieldset><Legend><strong>INTERVALO</strong></legend>
		    <b> Códigos de  </b>
                  <?
                    db_input('t52_bem_ini',8,0,true,'text',1,"onchange='js_isnumber(this.value);'","");
                  ?>
		    <b> a </b>
                  <?
                    db_input('t52_bem_fim',8,0,true,'text',1,"onchange='js_isnumber(this.value,this.name);'","");
                  ?>
	          </fieldset>
		  </center>
	        </td>
              </tr>
	    </table>
	  </td>
	</tr>
        <tr>
	  <td colspan="3">
	    <table>
	      <tr>
		<td>
        <?
        $aux = new cl_arquivo_auxiliar;
        $aux->cabecalho = "<strong>OPÇÃO DE BENS</strong>";
        $aux->codigo = "t52_bem";
        $aux->descr  = "t52_descr";
        $aux->nomeobjeto = 'altbem';
        $aux->funcao_js = 'js_mostra';
        $aux->funcao_js_hide = 'js_mostra1';
        $aux->sql_exec  = "";
        $aux->func_arquivo = "func_bens.php";
        $aux->nomeiframe = "db_iframe";
        $aux->localjan = "";
        $aux->db_opcao = 2;
        $aux->tipo = 2;
        $aux->top = 0;
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
            $xxx = array("S"=>"Somente Selecionados","N"=>"Menos os Selecionados");
            db_select('param',$xxx,true,2);
            ?>
	  </td>
	  <td align="left">
	    <input name="processar" type="button" onclick='js_abre();'  value="Processar dados">
	  </td>
	</tr>
      </table>
      </center>
    </td>
  </tr>
</table>
</form>