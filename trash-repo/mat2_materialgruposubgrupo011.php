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
require("std/db_stdClass.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_parcustos_classe.php");
require("libs/db_utils.php");
require_once("libs/db_app.utils.php");

db_postmemory($HTTP_POST_VARS);

$clparcustos = new cl_parcustos;
$aux = new cl_arquivo_auxiliar;

$rsParam                  = $clparcustos->sql_record($clparcustos->sql_query_file(db_getsession("DB_anousu"),"cc09_tipocontrole","","cc09_instit = ".db_getsession("DB_instit")) );
if($clparcustos->numrows > 0){
 db_fieldsmemory($rsParam,0);
}else{
 $cc09_tipocontrole = 0;
}

$dPeriodoSetado = date('d/m/Y');

?>
<style>

.inputPadrao {
  width : 100px;
}

</style>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,widgets/dbtextField.widget.js,
               dbmessageBoard.widget.js,dbcomboBox.widget.js,datagrid.widget.js, prototype.maskedinput.js, 
               DBTreeView.widget.js,arrays.js");
  
  db_app::load("estilos.css, grid.style.css");
  db_app::load("widgets/DBLancador.widget.js, widgets/DBAncora.widget.js");
?>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<center>
  <form name="form1" method="post" action="">
    <fieldset style="width: 600px;" >
      <legend>
        <b>Relatorio Grupo/Subgrupo</b>
      </legend>
      
      <table style="width:550px"> 
        <tr id='data' style='display:""'>
          <td style='width:150px' >
            <b>Posição até: </b>
          </td>
          <td>      
            <? 
            db_inputdata('periodo','','','',true,'text',1,"class=inputPadrao");
            ?>&nbsp;
    	     </td>
         </tr>
         <tr id='ordem' style='display:""'>
           <td title="Ordem por Codigo/Departamento/Alfabética" >
             <strong>Ordem:</strong>
           </td>
           <td>
    	       <? 
    	       $tipo_ordem = array("1" => "Codigo",
    	                           "2" => "Alfabética",
    	                          );
    	       db_select("ordem",$tipo_ordem,true,2, "class=inputPadrao"); 
    	       ?>
   	       </td>
	      </tr>
	      <tr id='ordem' style='display:""'>
           <td title="Ordem por Codigo/Departamento/Alfabética" >
             <strong>Emissão:</strong>
           </td>
           <td>
             <? 
             $tipo_ordem = array("1" => "Analítica",
                                 "2" => "Sintética",
                                );
             db_select("tipoemissao",$tipo_ordem,true,2, "class=inputPadrao"); 
             ?>
           </td>
        </tr>
        <tr>
          <td>
            <strong>Grupo / Subgrupo:</strong>
          </td>
          <td>
            <input type="button" name="escolheGrupo" id ="escolheGrupo" value="Escolher" onclick="js_escolheGrupoSubgrupo();" class="inputPadrao"" >
          </td>
        </tr>
      </table>
        <div id="divLancadorAlmoxarifado"></div>
    </fieldset>
  </form>
  <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_mandadados();" class="inputPadrao" >
  </center>
</body>
</html>
<script>



function js_criarLancadorAlmoxarifado() {

	oLancadorAlmoxarifado = new DBLancador("oLancadorAlmoxarifado");
	oLancadorAlmoxarifado.setNomeInstancia("oLancadorAlmoxarifado");
	oLancadorAlmoxarifado.setLabelAncora("Almoxarifado: ");
	oLancadorAlmoxarifado.setTextoFieldset("Almoxarifados Selecionados");
	oLancadorAlmoxarifado.setParametrosPesquisa("func_db_almox.php", ['m91_codigo', 'descrdepto'], "sDescricaoDepartamento=true");
	oLancadorAlmoxarifado.setGridHeight("400px");
	oLancadorAlmoxarifado.show($("divLancadorAlmoxarifado"));
}


function js_testord(valor){	
	if (valor=='S'){
		document.form1.ordem.value='b';
		document.form1.ordem.disabled=true;
	}else{
		document.form1.ordem.value='a';
		document.form1.ordem.disabled=false;
	}
}

function js_mandadados() {


  var aAlmoxarifadosSelecionados = oLancadorAlmoxarifado.getRegistros();

  if (aAlmoxarifadosSelecionados.length == 0) {
    if (!confirm("Não foi selecionado nenhum almoxarifado, deseja continuar com o processamento?")) {
      return false;
    }
  }

  var sAlmoxarifados = "";
  var sVirgula       = "";
  
  aAlmoxarifadosSelecionados.each(function (oAlmoxarifado, iIndice) {
    sAlmoxarifados += sVirgula+oAlmoxarifado.sCodigo;
    sVirgula = ",";
  });
  
 query="";
 vir="";
 listamat="";

 for (x=0; x<parent.iframe_g2.document.form1.material.length; x++) {

  listamat += vir+parent.iframe_g2.document.form1.material.options[x].value;
  vir = ",";
 }
 
 var sDataFim = new String(document.form1.periodo.value).trim();
    
 if ( sDataFim == '' ) {
   alert('Informe o período.');
   return false;     
 }
 
 var aLinhas = oTreeViewGrupos.getNodesChecked();
 var aContas = new Array();
 
 aLinhas.each ( 
   function(oRetornoCheck) {
   
     aContas.push(oRetornoCheck.value);
   }
 );
 
 query+= '&listamat=' + listamat;
 query+= '&vermat=' + parent.iframe_g2.document.form1.ver.value;
 query+= '&datafin=' + sDataFim; 
 query+= '&ordem=' + document.form1.ordem.value;
 query+= '&emissao=' + document.form1.tipoemissao.value;
 query+= '&grupos=' + aContas.implode(',');
 query+= "&sAlmoxarifados=" + sAlmoxarifados;
 
 jan = window.open('mat2_materialgruposubgrupo002.php?'+query,'',
                   'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
}

var windowGrupo = '';
var oTreeViewGrupos = new DBTreeView('treeViewGrupo');

function js_escolheGrupoSubgrupo() {
 
  if ( !(windowGrupo instanceof windowAux) ) { 
    
    var iTamWidth          = screen.availWidth / 2;
    var iTamHeight         = screen.availHeight / 2;
    var iTamHeightFieldset = iTamHeight - 150;

    /**
     *  Configurações do componente WindowAux
     */
    windowGrupo = new windowAux("escolheGrupo", "Escolha de Grupos/Subgrupos", iTamWidth, iTamHeight);
    var sContent  = "<div id='divEscolheGrupo' style=''>";
        sContent += "  <div id='divListaGrupo' style='padding: 2px;'>";
        sContent += "    <fieldset id='' style='height:"+iTamHeightFieldset+";'>";
        sContent += "       <div id='ctnTreeView' style='width:100%'>";
        sContent += "       </div>";
        sContent += "    </fieldset>";
        sContent += "    <center>";
        sContent += "      <input type='button' name='btnSalvarGrupos' id='btnSalvarGrupos' value='Salvar' onclick='windowGrupo.hide();'>";
        sContent += "    </center>";
        sContent += "  </div>";
        sContent += "</div>";
    
    windowGrupo.setContent(sContent);
    windowGrupo.setShutDownFunction(function() {
      windowGrupo.hide();
    });
    oTreeViewGrupos.show($('ctnTreeView'));
    oNoPrincipal = oTreeViewGrupos.addNode("0", "Grupos / Subgrupos");
    js_divCarregando("Aguarde, buscando registros","msgBox");
    var sRPCArq = 'mat4_materialgrupo.RPC.php';
    var oParam  = new Object();
    oParam.exec = 'getGrupos'; 
    var oAjax   = new Ajax.Request(sRPCArq,
                                   {method: 'post',
                                    asynchronous: false,
                                    parameters: 'json='+Object.toJSON(oParam), 
                                    onComplete: js_retornoWindowGrupo
                                   }) ;
    
    
    oTreeViewGrupos.allowFind(true);
    oTreeViewGrupos.setFindOptions('matchedonly');
    
    /**
     *  Configurações do componente Message Board
     */
    var sIdMsgBoard       = "helpWindowGrupo";
    var sTitleMsgBoard    = "Escolha de Grupos / Subgrupos";
    var sHelpMsgBoard     = "Escolha os grupos/subgrupos para adicionar ao filtro.";
    var oWhereAddMsgBoard = "escolheGrupo";
    var ajudaWindowGrupo  = new DBMessageBoard(sIdMsgBoard, sTitleMsgBoard, sHelpMsgBoard, 
                                               windowGrupo.getContentContainer());
      
      
  }
  windowGrupo.show();  
}

function js_retornoWindowGrupo (oAjax) {
  
  js_removeObj("msgBox");
  var oRetorno      = eval("(" + oAjax.responseText + ")");
  var iTotalRetorno = oRetorno.aGrupos.length;
  var sRetornoInfo  = oRetorno.aGrupos;
  
  for (var i = 0; i < iTotalRetorno; i++) {
  
  
    with(oRetorno.aGrupos[i]) {
    
      var iRetNivel      = nivel;
      var sRetLabel      = estrutural+" - "+descricaogrupo.urlDecode();
      var sRetParentNode = conta_pai;
      oCheck = function (oNode, event) {
                   
       if (oNode.checkbox.checked) {
           oNode.checkAll(event);       
        } else {
           oNode.uncheckAll(event);
        }
      }
      oTreeViewGrupos.addNode(codigogrupo, 
                              sRetLabel, 
                              sRetParentNode,
                              null, 
                              null, 
                              {checked:false,
                               onClick:oCheck
                              }
                              );
    }
  }
   oNoPrincipal.expand();
}
$('periodo').value='<?=$dPeriodoSetado;?>';
js_criarLancadorAlmoxarifado();
</script>