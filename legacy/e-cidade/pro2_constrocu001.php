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
include("libs/db_usuariosonline.php");
include("classes/db_lote_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_obrasender_classe.php");

//---  parser POST/GET
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

//---- instancia classes
$clobrasender = new cl_obrasender;
$aux = new cl_arquivo_auxiliar;

//--- cria rotulos e labels
$clobrasender->rotulo->label();

//----
//----
$cllote = new cl_lote;
$cliframe_seleciona = new cl_iframe_seleciona;

$cllote->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("ob08_area");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>
<form class="container" name="form1" method="post" action="" >
  <fieldset>
    <legend>Relatórios - Obras e Construções</legend>
    <table class="form-container">
      <tr> 
        <td>
          Opções:
        </td>
        <td>
          <select name="ver">
            <option name="condicao" value="com">Com os ocupação selecionados</option>
            <option name="condicao" value="sem">Sem os ocupação selecionados</option>
          </select>
        </td>
       </tr>
       <tr>
          <td colspan="2">
            <?
              // $aux = new cl_arquivo_auxiliar;
              $aux->cabecalho = "<strong>Ocupação</strong>";
              $aux->codigo = "j31_codigo"; //chave de retorno da func
              $aux->descr  = "j31_descr";   //chave de retorno
              $aux->nomeobjeto = 'ocupacao';
              $aux->funcao_js = 'js_mostra';
              $aux->funcao_js_hide = 'js_mostra1';
              $aux->sql_exec  = "";
              $aux->func_arquivo = "func_caracterocu.php";  //func a executar
              $aux->nomeiframe = "db_iframe_caracterocu";
              $aux->localjan = "";
              $aux->onclick = "";
              $aux->db_opcao = 2;
              $aux->tipo = 2;
              $aux->top = 1;
              $aux->linhas = 10;
              $aux->whidth = 400;
              $aux->funcao_gera_formulario();
            ?>    
          </td>
      </tr>
      <tr>
        <td>Unidades de:</td>
        <td>
	        <?
            db_input('ob07_unidades',6,$Iob07_unidades,true,'text',1,"","ob07_unidadesINI","");             
          ?>
	        <b>até:</b>
          <?
            db_input('ob07_unidades',6,$Iob07_unidades,true,'text',1,"","ob07_unidadesFIN","");             
          ?>
        </td>
      </tr>
      <tr>
        <td>Pavimentos de:</td>
        <td>
	        <?
            db_input('ob07_pavimentos',6,$Iob07_pavimentos,true,'text',1,"","ob07_pavimentosINI","");             
          ?>
	        <b>até:</b>
          <?
            db_input('ob07_pavimentos',6,$Iob07_pavimentos,true,'text',1,"","ob07_pavimentosFIN","");             
          ?>
        </td>
      </tr>
      <tr>
        <td>Área de:</td>
        <td>
  	      <?
            db_input('ob08_area',6,$Iob08_area,true,'text',1,"","ob08_areaINI","");             
          ?>
  	      <b>até:</b>
          <?
            db_input('ob08_area',6,$Iob08_area,true,'text',1,"","ob08_areaFIN","");             
          ?>
        </td>
      </tr>
      <tr>
        <td>Data inicio de:</td>
        <td>
          <? 
	          db_inputdata('data1','','','',true,'text',1,"");   		          
            echo "<b> a </b> ";
            db_inputdata('data2','','','',true,'text',1,"");
          ?>
        </td>
      </tr>
      <tr>
        <td>Data final de:</td>
        <td>
          <? 
	          db_inputdata('data3','','','',true,'text',1,"");   		          
            echo "<b> a </b> ";
            db_inputdata('data4','','','',true,'text',1,"");
          ?>
	      </td>
      </tr>
      <tr>
        <td>Habite-se:</td>
        <td>
	        <?
            $x = array("t"=>"Todas","c"=>"com Habite-se","s"=>"sem Habite-se");
            db_select('habite',$x,true,1,"");
          ?>
	      </td>
	    </tr>
	  </table>
	</fieldset>
  <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_mandadados();" >
</form>
<script>

function js_mandadados(){
 vir="";
 listaocu="";
 for(x=0;x<document.form1.ocupacao.length;x++){
  listaocu+=vir+document.form1.ocupacao.options[x].value;
  vir=",";
 }
 query = "listaocu="+listaocu; 

 vir="";
 listaconstr="";
 for(x=0;x<parent.iframe_g2.document.form1.constr.length;x++){
  listaconstr+=vir+parent.iframe_g2.document.form1.constr.options[x].value;
  vir=",";
 }
 query +="&listaconstr="+listaconstr;
 
 vir="";
 listalanc="";
 for(x=0;x<parent.iframe_g3.document.form1.lanc.length;x++){
  listalanc+=vir+parent.iframe_g3.document.form1.lanc.options[x].value;
  vir=",";
 }
 query +="&listalanc="+listalanc;
 
 vir="";
 listarua="";
 for(x=0;x<parent.iframe_g4.document.form1.rua.length;x++){
  listarua+=vir+parent.iframe_g4.document.form1.rua.options[x].value;
  vir=",";
 }
 query +="&listarua="+listarua;
 
 vir="";
 listabairro="";
 for(x=0;x<parent.iframe_g5.document.form1.bairro.length;x++){
  listabairro+=vir+parent.iframe_g5.document.form1.bairro.options[x].value;
  vir=",";
 }
 query +="&listabairro="+listabairro;
 
 obj = document.form1;

 query += "&dt="+obj.data1_ano.value+'-'+obj.data1_mes.value+'-'+obj.data1_dia.value;
 query += "&dt1="+obj.data2_ano.value+'-'+obj.data2_mes.value+'-'+obj.data2_dia.value;
 query += "&dt2="+obj.data3_ano.value+'-'+obj.data3_mes.value+'-'+obj.data3_dia.value;
 query += "&dt3="+obj.data4_ano.value+'-'+obj.data4_mes.value+'-'+obj.data4_dia.value;
 query += '&verocu='+obj.ver.value;
 query += '&verconstr='+parent.iframe_g2.document.form1.ver.value;
 query += '&verlanc='+parent.iframe_g3.document.form1.ver.value;
 query += '&verrua='+parent.iframe_g4.document.form1.ver.value;
 query += '&verbairro='+parent.iframe_g5.document.form1.ver.value;
 query += '&uniini='+obj.ob07_unidadesINI.value;
 query += '&unifin='+obj.ob07_unidadesFIN.value;
 query += '&pavini='+obj.ob07_pavimentosINI.value;
 query += '&pavfin='+obj.ob07_pavimentosFIN.value;
 query += '&areaini='+obj.ob08_areaINI.value;
 query += '&areafin='+obj.ob08_areaFIN.value;
 query += '&habite='+obj.habite.value;
 


 jan = window.open('pro2_constr002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);





}

</script>
</body>
</html>

<script>

$("fieldset_ocupacao").addClassName("separator");
$("j31_codigo").addClassName("field-size2");
$("j31_descr").addClassName("field-size7");
$("ocupacao").style.width = "100%";
$("ob07_unidadesINI").addClassName("field-size3");
$("ob07_unidadesFIN").addClassName("field-size3");
$("ob07_pavimentosINI").addClassName("field-size3");
$("ob07_pavimentosFIN").addClassName("field-size3");
$("data1").addClassName("field-size2");
$("data2").addClassName("field-size2");
$("data3").addClassName("field-size2");
$("data4").addClassName("field-size2");
$("ob08_areaINI").addClassName("field-size3");
$("ob08_areaFIN").addClassName("field-size3");

</script>