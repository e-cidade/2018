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
require("std/db_stdClass.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_parcustos_classe.php");
require("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);

$clparcustos = new cl_parcustos;
$aux = new cl_arquivo_auxiliar;

$rsParam                  = $clparcustos->sql_record($clparcustos->sql_query_file(db_getsession("DB_anousu"),"cc09_tipocontrole","","cc09_instit = ".db_getsession("DB_instit")) );
if($clparcustos->numrows > 0){
 db_fieldsmemory($rsParam,0);
}else{
 $cc09_tipocontrole = 0;
}

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script>
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
  </tr>
</table >

  <table    align="center">
    <form name="form1" method="post" action="">
      <tr>
         <td align='right' ></td>
         <td ></td>
      </tr>
       <tr> 
           <td colspan=2  align="center">
                <strong>Opções:</strong>
                <select name="ver">
                    <option name="condicao1" value="com">Com os departamentos selecionados</option>
                    <option name="condicao1" value="sem">Sem os departamentos selecionados</option>
                </select>
          </td>
       </tr>
      <tr >
        <td colspan=2 ><?
                 // $aux = new cl_arquivo_auxiliar;
                 $aux->cabecalho = "<strong>Departamentos</strong>";
                 $aux->codigo = "coddepto"; //chave de retorno da func
                 $aux->descr  = "descrdepto";   //chave de retorno
                 $aux->nomeobjeto = 'departamentos';
                 $aux->funcao_js = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec  = "";
                 $aux->func_arquivo = "func_db_depart.php";  //func a executar
                 //$aux->passar_query_string_para_func = "&depusu=".db_getsession('DB_id_usuario'); 
                 $aux->nomeiframe = "db_iframe_db_depart";
                 $aux->localjan = "";
                 $aux->onclick                     ="";
                 $aux->executa_script_apos_incluir ='js_verifica_orgao();';
                 $aux->db_opcao = 2;
                 $aux->tipo = 2;
                 $aux->top = 0;
                 $aux->linhas = 10;
                 $aux->vwhidth = 400;
                 $aux->funcao_gera_formulario();
        	?>
       </td>
      </tr>
      <tr id='data' style='display:""'>
        <td align='center'  colspan=2 >
               <b> Período </b>
               <? 
	                db_inputdata('data1','','','',true,'text',1,"");   		          
                  echo "<b> a</b> ";
                  db_inputdata('data2','','','',true,'text',1,"");
               ?>&nbsp;
	</td>
      </tr>
      <tr align="center" >
       <td colspan=2>
         <table>
         <tr id='quebra' style='display:""'>
             <td align="right"  title="Quebra por departamento" >
               <strong>Quebra por departamento:</strong></td><td>
	       <? 
	       $tipo_que = array("N"=>"Não","S"=>"Sim");
	       db_select("quebra",$tipo_que,true,2,"onchange='js_testord(this.value);'"); ?>
            </td>
	  </tr>
	  <tr id='listar' style='display:""'>
             <td align="right"  title="Listar" >
               <strong>Listar:</strong></td><td>
	       <? 
	       $somente_serv = array("M"=>"Materiais", "T"=>"Todos", "S"=>"Serviços");
	       db_select("listar_serv",$somente_serv,true,2);
		   ?>
       		 </td>
	  </tr>
	   <tr id='ordem' style='display:""'>
             <td align="right"  title="Ordem por  Codigo/Departamento/Alfabética" >
               <strong>Ordem:</strong>
               </td><td>
	       <? 
	       $tipo_ordem = array("a"=>"Codigo","b"=>"Departamento","c"=>"Alfabética","d"=>"Data");
	       db_select("ordem",$tipo_ordem,true,2); ?>
       		 </td>
	  </tr>
       <tr><td align="right">
       	
          <?
		   //verifica se está habilitado o parametro de filtrar por centro de custos.
           if( $cc09_tipocontrole != 0){
		    db_ancora("<b>Centro de de Custo:",'js_pesquisacriterio(true)', 1);
            echo "</td><td>";
            db_input('cc08_sequencial',10,"",true,"text", 1, "onchange='js_pesquisacriterio(false)'");
            db_input('cc08_descricao',40,"",true,"text",3);
            echo "</td></tr>" ;
		   } 
          ?>
	</table>
	</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"><br> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_mandadados();" >
        </td>
      </tr>

  </form>
    </table>
</body>
</html>
<script>
function js_testord(valor){	
	if (valor=='S'){
		document.form1.ordem.value='b';
		document.form1.ordem.disabled=true;
	}else{
		document.form1.ordem.value='a';
		document.form1.ordem.disabled=false;
	}
}
function js_mandadados(){
 
 query="";
 vir="";
 listadepart="";
 
 for(x=0;x<document.form1.departamentos.length;x++){
  listadepart+=vir+document.form1.departamentos.options[x].value;
  vir=",";
 }
 
 vir="";
 listamat="";
 for(x=0;x<parent.iframe_g2.document.form1.material.length;x++){
  listamat+=vir+parent.iframe_g2.document.form1.material.options[x].value;
  vir=",";
 }
 
 vir="";
 listausu="";
 for(x=0;x<parent.iframe_g3.document.form1.usuario.length;x++){
  listausu+=vir+parent.iframe_g3.document.form1.usuario.options[x].value;
  vir=",";
 }

 vir        = "";
 listaorgao = "";
 obj        = parent.iframe_g4.db_iframe_orgao.document.getElementsByTagName("input");
 nObj       = obj.length;

 for (x=0 ; x < nObj;x++){
 
   if (obj[x].type == "checkbox" && obj[x].checked==true){
     listaorgao += vir+obj[x].value;
     vir = ",";
   }        

 }
       
 var sDataIni = new String(document.form1.data1.value).trim();
 var sDataFim = new String(document.form1.data2.value).trim();
   
    
 if ( sDataIni == '' && sDataFim == '' ) {
   alert('Favor informe algum período!');
   return false;
 } else if ( sDataIni == '' ) {
   alert('Favor informe período inicial!');
   return false;
 } else if ( sDataFim == '' ) {
   alert('Favor informe período final!');
   return false;     
 }
 
 query+='&listadepart='+listadepart+'&verdepart='+document.form1.ver.value;
 query+='&listamat='+listamat+'&vermat='+parent.iframe_g2.document.form1.ver.value;
 query+='&listausu='+listausu+'&verusu='+parent.iframe_g3.document.form1.ver.value;
 query+='&quebra_usu='+parent.iframe_g3.document.form1.quebra_usu.value;
 query+='&dataini='+sDataIni;
 query+='&datafin='+sDataFim; 
 query+='&ordem='+document.form1.ordem.value;
 query+='&listar_serv='+document.form1.listar_serv.value;
 query+='&quebra='+document.form1.quebra.value;
 query+='&listaorgao='+listaorgao;
 <?if( $cc09_tipocontrole != 0 ){?>
    query+='&centrocusto='+document.form1.cc08_sequencial.value;
    query+='&centrocustodescr='+document.form1.cc08_descricao.value;
 <?}?>

 jan = window.open('mat2_relsaida002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
 
}

function js_verifica_orgao(){
  orgao=false;
  obj=parent.iframe_g4.db_iframe_orgao.document.getElementsByTagName("input");
  nObj=obj.length;
   for (i=0;i < nObj;i++){
       if (obj[i].type == "checkbox" && obj[i].checked==true){
         orgao=true; 
        }   
   }
   if (orgao==true){
     alert("Já existe órgão selecionado na guia Órgãos.");
     document.getElementById('departamentos').length = 0;
     return false;
   }
}

function js_pesquisacriterio(mostra) {
 if (mostra) {
   js_OpenJanelaIframe('',
                       'db_iframe_custocriteriorateio',
                       'func_custocriteriorateio.php?funcao_js=parent.js_preenchecriterio|cc08_sequencial|cc08_descricao',
                       'Critérios de Rateios ',
                       true);
  } else {
     var chave = document.getElementById("cc08_sequencial").value; 
    js_OpenJanelaIframe('',
                       'db_iframe_custocriteriorateio',
                       'func_custocriteriorateio.php?pesquisa_chave='+chave+'&funcao_js=parent.js_preenchecriterio2',
                       'Critérios de Rateios ',
                       false);
  }
}

function js_preenchecriterio(sequencial, descricao){
  db_iframe_custocriteriorateio.hide();
  document.getElementById("cc08_sequencial").value = sequencial;
  document.getElementById("cc08_descricao").value = descricao;
}

function js_preenchecriterio2(descricao, erro){
  
  if (!erro) {
  
    document.getElementById("cc08_descricao").value = descricao;
  } else {
  
    document.getElementById("cc08_descricao").value = descricao;
    document.getElementById("cc08_sequencial").value = "";
    document.getElementById("cc08_sequencial").focus();
    
  }
  db_iframe_custocriteriorateio.hide();
}
</script>