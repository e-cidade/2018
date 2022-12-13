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
include("classes/db_bens_classe.php");
include("dbforms/db_classesgenericas.php");
$aux_bem	 		= new cl_arquivo_auxiliar;
$clbens 			= new cl_bens;
$clrotulo 		= new rotulocampo;
$clbens->rotulo->label();

db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>

<script>
function js_abre(){
  var query = "";
  if(document.getElementById('bens')){
	    //Le os itens lançados na combo dos bens
			vir="";
		 	listaBens="";
		 
		 	for(x=0;x<document.form1.bens.length;x++){
		  	listaBens+=vir+document.form1.bens.options[x].value;
		  	vir=",";
		 	}
			if(listaBens!=""){ 	
				query +='t52_bem=('+listaBens+')';
			} else {
				query +='t52_bem=';
			}
	}  
  if(listaBens == ""){
    alert(_M("patrimonial.patrimonio.pat2_histbem001.informe_bem"));
  }else{
    //query  = "t52_bem="+document.form1.t52_bem.value;  
    query += "&opcao_obs="+document.form1.opcao_obs.value;
    jan = window.open('pat2_histbem002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
    document.form1.t52_bem.style.backgroundColor='';
  }
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC  onLoad="document.form1.t52_bem.focus();">
<form class="container" name="form1" method="post">
  <fieldset>
    <legend>Relatórios - Ficha de Bens/Histórico dos bens</legend>
    <table class="form-container">
      <!-- tr> 
        <td  align="left" nowrap title="<?=$Tt52_bem?>"> <? db_ancora(@$Lt52_bem,"js_pesquisa_bem(true);",1);?>  </td>
        <td align="left" nowrap>
          <?/*
             db_input("t52_bem",8,$It52_bem,true,"text",4,"onchange='js_pesquisa_bem(false);'"); 
             db_input("t52_descr",40,$It52_descr,true,"text",3);  
            */?></td>
      </tr-->
      <tr>
         <td colspan=2 >
         		<?
             // $aux = new cl_arquivo_auxiliar;
             $aux_bem->cabecalho = "<strong>Bens</strong>";
             $aux_bem->codigo = "t52_bem"; //chave de retorno da func
             $aux_bem->descr  = "t52_descr";   //chave de retorno
             $aux_bem->nomeobjeto = 'bens';
             $aux_bem->funcao_js = 'js_mostra_bens';
             $aux_bem->funcao_js_hide = 'js_mostra_bens1';
             $aux_bem->sql_exec  = "";
             $aux_bem->func_arquivo = "func_bens.php";  //func a executar
             $aux_bem->nomeiframe = "db_iframe_bens";
             $aux_bem->localjan = "";
             $aux_bem->onclick = "";
             $aux_bem->db_opcao = 2;
             $aux_bem->tipo = 2;
             $aux_bem->top = 0;
             $aux_bem->linhas = 5;
             $aux_bem->vwhidth = 400;
             $aux_bem->nome_botao = 'db_lanca_bem';
             $aux_bem->funcao_gera_formulario();
           	?>
          </td>
      </tr>
      <tr>
        <td nowrap title="Características adicionais do bem">
          Características adicionais do bem:
        </td>
        <td>
         <?
           $matriz = array("N"=>"NÃO","S"=>"SIM"); 
           db_select("opcao_obs",$matriz,true,1);
         ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="relatorio" type="button" onclick='js_abre();'  value="Gerar relatório">
</form>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
//--------------------------------
function js_pesquisa_bem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bens','func_bens.php?funcao_js=parent.js_mostrabem1|t52_bem|t52_descr','Pesquisa',true);
  }else{
     if(document.form1.t52_bem.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_bens','func_bens.php?pesquisa_chave='+document.form1.t52_bem.value+'&funcao_js=parent.js_mostrabem','Pesquisa',false);
     }else{
       document.form1.t52_descr.value = ''; 
     }
  }
}
function js_mostrabem(chave,erro){
  document.form1.t52_descr.value = chave; 
  if(erro==true){ 
    document.form1.t52_bem.focus(); 
    document.form1.t52_bem.value = ''; 
  }
}
function js_mostrabem1(chave1,chave2){
  document.form1.t52_bem.value = chave1;
  document.form1.t52_descr.value = chave2;
  db_iframe_bens.hide();
}
//--------------------------------
</script>
</body>
</html>
<script>

$("fieldset_bens").addClassName("separator");
$("t52_bem").addClassName("field-size2");
$("t52_descr").addClassName("field-size7");
$("bens").style.width = "100%";
$("opcao_obs").style.width = "340px";

</script>