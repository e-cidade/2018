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
include("classes/db_empempenho_classe.php");

//---  parser POST/GET
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

//---- instancia classes
$clempempenho = new cl_empempenho;
$aux = new cl_arquivo_auxiliar;

//--- cria rotulos e labels
$clempempenho->rotulo->label();

//----
//----
$cllote = new cl_lote;
$cliframe_seleciona = new cl_iframe_seleciona;

$cllote->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
if (!isset($testdt)){
  $testdt='sem';
}
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
    <legend>Relatórios - Obras</legend>
    <table class="form-container">
      <tr> 
        <?
          db_input('testdt',10,"",true,"hidden",1);
        ?>
        <td>
          <b>Opções:</b>
        </td>
        <td>
          <select name="ver">
              <option name="condicao1" value="com">Com os proprietários selecionados</option>
              <option name="condicao1" value="sem">Sem os proprietários selecionadas</option>
          </select>
        </td>
      </tr>
      <tr>
         <td colspan="2">
              <?
                // $aux = new cl_arquivo_auxiliar;
                $aux->cabecalho = "<strong>Proprietário</strong>";
                $aux->codigo = "ob03_numcgm"; //chave de retorno da func
                $aux->descr  = "z01_nome";   //chave de retorno
                $aux->nomeobjeto = 'propri';
                $aux->funcao_js = 'js_mostra';
                $aux->funcao_js_hide = 'js_mostra1';
                $aux->sql_exec  = "";
                $aux->func_arquivo = "func_obraspropri.php";  //func a executar
                $aux->nomeiframe = "db_iframe_obraspropri";
                $aux->localjan = "";
                $aux->onclick = "";
                $aux->db_opcao = 2;
                $aux->tipo = 2;
                $aux->top = 1;
                $aux->linhas = 10;
                $aux->vwhidth = 400;
                $aux->funcao_gera_formulario();
             ?>    
         </td>
      </tr>
      <tr>
	      <td>
	        Data da Construção de:
	      </td>
	      <td>
	        <?
            db_inputdata('dtobra1',"","","",true,'text',1);
		        echo "<b>ate:</b>";
            db_inputdata('dtobra2',"","","",true,'text',1);
	        ?>
	      </td>
	    </tr>
      <tr>
        <td>
          Obras:
        </td>
        <td>
            <select name="tipo">
                 <option name="tipo" value="t">Todas </option>
                 <option name="tipo" value="a">Regulares </option>
                 <option name="tipo" value="s">Irregulares </option>
            </select>
        </td>
      </tr>
      <tr>
        <td>
          Alvará:
        </td>
        <td>
  	      <?
            $x = array("t"=>"Todas","c"=>"com Alvará","s"=>"sem Alvará");
            db_select('alvara',$x,true,1,"onchange='js_testadata(this.value);'");
          ?>
	      </td>
	    </tr>
	    <?
	      if ($testdt=='com'){
	    ?>
	      <tr>
	        <td>
	          Alvará de:
	        </td>
	        <td>
	          <?
              db_inputdata('data1',"","","",true,'text',1);
		          echo "<b>ate:</b>";
              db_inputdata('data2',"","","",true,'text',1);
	          ?>
	        </td>
	      </tr>
      <?
        }
      ?>
    </table>
  </fieldset>
  <input type="button" value="Relatório" onClick="js_mandadados()">
</form>
<script>
function js_testadata(valor){
  if (valor=='c'){
    document.form1.testdt.value = 'com';
  }else if (valor=='s'){
    document.form1.testdt.value = 'sem';
  }else if (valor=='t'){
    document.form1.testdt.value = 'sem';
  }
  document.form1.submit();
}
//------------------------------------------
function js_mandadados(){
 vir="";
 listapropri="";
 for(x=0;x<document.form1.propri.length;x++){
  listapropri+=vir+document.form1.propri.options[x].value;
  vir=",";
 }
 vir="";
 listaresp="";
 for(x=0;x<parent.iframe_g2.document.form1.responsavel.length;x++){
  listaresp+=vir+parent.iframe_g2.document.form1.responsavel.options[x].value;
  vir=",";
 }
 vir="";
 listatec="";
 for(x=0;x<parent.iframe_g3.document.form1.tecnico.length;x++){
  listatec+=vir+parent.iframe_g3.document.form1.tecnico.options[x].value;
  vir=",";
 }
 vir="";
 listatipo="";
 for(x=0;x<parent.iframe_g4.document.form1.tiporesp.length;x++){
  listatipo+=vir+parent.iframe_g4.document.form1.tiporesp.options[x].value;
  vir=",";
 }
 query=""; 
 for (i=0 ; i<document.form1.length; i++){
   if (document.form1.elements[i].name=="data1_dia"){
     query='&data1='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value;
   }
   if (document.form1.elements[i].name=="data2_dia"){
     query+='&data2='+document.form1.data2_ano.value+'-'+document.form1.data2_mes.value+'-'+document.form1.data2_dia.value;
   }
 }
 query+='&dtobra1='+document.form1.dtobra1_ano.value+'-'+document.form1.dtobra1_mes.value+'-'+document.form1.dtobra1_dia.value;
 query+='&dtobra2='+document.form1.dtobra2_ano.value+'-'+document.form1.dtobra2_mes.value+'-'+document.form1.dtobra2_dia.value;
 jan = window.open('pro2_obrasrela002.php?&listapropri='+listapropri+'&listaresp='+listaresp+'&listatec='+listatec+'&listatipo='+listatipo+'&tipo='+document.form1.tipo.value+'&verpropri='+document.form1.ver.value+'&verres='+parent.iframe_g2.document.form1.ver.value+'&vertec='+parent.iframe_g3.document.form1.ver.value+'&vertipo='+parent.iframe_g4.document.form1.ver.value+'&alvara='+document.form1.alvara.value+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);





}

</script>

  </body>
</html>
<script>

$("fieldset_propri").addClassName("separator");
$("ob03_numcgm").addClassName("field-size2");
$("z01_nome").addClassName("field-size7");
$("propri").style.width = "100%";
$("dtobra1").addClassName("field-size2");
$("dtobra2").addClassName("field-size2");
$("data1").addClassName("field-size2");
$("data2").addClassName("field-size2");

</script>