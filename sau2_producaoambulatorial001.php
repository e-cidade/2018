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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

db_postmemory($_POST);

$db_opcao   = 3;
$oDaoProced = db_utils::getdao('sau_procedimento');
$oDaoProced->rotulo->label();
$oRotulo    = new rotulocampo;
$oRotulo->label("sd60_i_codigo");
$oRotulo->label("sd60_c_grupo");
$oRotulo->label("sd60_c_nome");
$oRotulo->label("sd61_i_codigo");
$oRotulo->label("sd61_c_subgrupo");
$oRotulo->label("sd61_c_nome");
$oRotulo->label("sd62_i_codigo");
$oRotulo->label("sd62_c_formaorganizacao");
$oRotulo->label("sd62_c_nome");
$oRotulo->label("sd63_i_codigo");
$oRotulo->label("sd63_c_procedimento");
$oRotulo->label("sd63_c_nome");

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load("scripts.js");
    db_app::load("prototype.js");
    db_app::load("webseller.js");
    db_app::load("strings.js");
    db_app::load("estilos.css");
    db_app::load("datagrid.widget.js");
    db_app::load("grid.style.css");
    ?>
  </head>
  <body bgcolor=#CCCCCC onLoad="a=1" >
    <center>
      <br><br>
      <table width="700">
        <tr> 
          <td align="left" valign="top" bgcolor="#CCCCCC">
            <fieldset style="padding-bottom: 1px;"> 
              <legend><b>Produção Ambulatorial</b></legend>
              <form name="form1" method="post" action="">
                <center>  
                  <!-- FIELDSET SELEÇÃO PERÍODO -->
                  <table width="100%">
                    <tr>
                      <td>
                        <fieldset>
                          <legend><b>Período</b></legend>
                          <table>
                            <tr>
                              <td  nowrap>
                                <b>Data Inicial:</b>
                              </td>
                              <td  nowrap>
                                <?
                                if (isset($data1)) {
                                  
                                  $aData = explode('/', $data1);
                                  $dia1  = $aData[0];
                                  $mes1  = $aData[1];
                                  $ano1  = $aData[2];
                                  
                                }
                                db_inputdata('data1', @$dia1, @$mes1, @$ano1, true, 'text', 1, ""); 
                                ?>
                              </td>
                              <td  nowrap style="padding-left: 23px;">
                                <b>Data Final:</b>
                              </td>
                              <td  nowrap>
                                <?
                                if (isset($data2)) {
                                  
                                  $aData = explode('/', $data2);
                                  $dia2  = $aData[0];
                                  $mes2  = $aData[1];
                                  $ano2  = $aData[2];
                                  
                                } 
                                db_inputdata('data2', @$dia2, @$mes2, @$ano2, true, 'text', 1, ""); 
                                ?>
                              </td>
                              <td  nowrap style="padding-left: 23px;">
                                <b>Agrupar:</b>
                              </td>
                              <td  nowrap>
                                <?
                                $aX = array('1'=>'UPS', '2'=>'SEM AGRUPAMENTO');
                                db_select('agrupar', $aX, true, 1, "");
                                ?>
                              </td>
                            </tr>
                          </table>              
                        </fieldset>
                      </td>
                    </tr> 
                    <!-- FIELDSET UPS -->
                    <tr>
                      <td>
                        <fieldset>
                          <legend><b>UPS</b></legend>
                          <table style="width: 100%;">
                            <tr>
                              <td>
                                <div id="gridUPS">
                                  <!-- LISTAGEM DAS UPS's -->
                                </div>
                              </td>
                            </tr>
                          </table>              
                         </fieldset>
                       </td>
                    </tr> 
                    <!-- FIELDSET ESTRUTURA -->
                    <tr>
                      <td>
                        <fieldset>
                          <legend><b>Estrutura</b></legend>
                          <table>
                            <tr>
                              <!-- GRUPO -->
                              <td nowrap title="<?=@$Tsd60_c_grupo?>" style="width: 24%;">
							                  <?
							                  db_ancora(@$Lsd60_c_grupo, "js_pesquisaGrupo();", 1);
							                  ?>
							                </td>
							                <td nowrap>
							                  <?
                                $aX = array("0"=>"");
                                db_select("sd60_i_codigo", $aX, $Isd60_i_codigo, 1, 
                                          "onchange='js_preencherSubgrupos();' style='width: 500px;'"
                                         );
                                ?>
							                </td>
                            </tr>
                            <tr>
                              <!-- SUB-GRUPO -->
                              <td nowrap title="<?=@$Tsd61_c_subgrupo?>">
							                  <?
							                  db_ancora(@$Lsd61_c_subgrupo, "js_pesquisaSubGrupo();", 1);
							                  ?>
							                </td>
							                <td nowrap>
							                  <?
                                $aX = array("0"=>"");
                                db_select("sd61_i_codigo", $aX, $Isd61_i_codigo, 1, 
                                          "onchange='js_preencherFormaOrganizacao();'  style='width: 500px;'"
                                         );
                                ?>
                              </td>
                            </tr>
                            <tr>
                              <!-- FORMA ORGANIZACAO -->
                              <td nowrap title="<?=@$Tsd62_c_formaorganizacao?>">
							                  <?
							                  db_ancora(@$Lsd62_c_formaorganizacao, "js_pesquisaFormaOrganizacao();", 1);
							                  ?>
							                </td>
							                <td>
							                  <?
                                $aX = array("0"=>"");
                                db_select("sd62_i_codigo", $aX, $Isd62_i_codigo, 1, " style='width: 500px;' ");
                                ?>
							                </td>
                            </tr>
                          </table>              
                         </fieldset>
                       </td>
                    </tr> 
                    <!-- FIELDSET PROCEDIMENTO -->
                    <tr>
                      <td>
                        <fieldset>
                          <legend><b>Procedimento</b></legend>
                          <table>
                            <tr>
                              <td nowrap title="<?=@$Tsd63_c_procedimento?>" style="width: 24%;">
							                  <?
							                  db_ancora ( @$Lsd63_c_procedimento, "js_pesquisaProcedimento(true);", 1);
							                  ?>
							                </td>
							                <td>
							                  <?
							                  db_input('sd63_i_codigo', 10, $Isd63_i_codigo, true, 'hidden', $db_opcao, '');
							                  db_input('sd63_c_procedimento', 10, $Isd63_c_procedimento, true, 'text', 1, 
							                           " onchange='js_pesquisaProcedimento(false);' " 
							                          );
							                  ?>
							                </td>
							                <td>
							                  <?
							                  db_input('sd63_c_nome', 58, $Isd63_c_nome, true, 'text', 3, '');
							                  ?>   
                              </td>
                            </tr>
                          </table>              
                         </fieldset>
                       </td>
                    </tr> 
                    <tr>
                      <td style="padding-top: 8px; padding-bottom: 8px;">
                        <input type="button" name="consultar" id="consultar" value="Consultar" 
                               onClick="js_consultar();">
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div id="gridProcedimentos">
                          <!-- LISTAGEM DOS PROCEDIMENTOS -->
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding-top: 8px;">
                        <?
							          db_input('sProcedimentos', 58, @$IsProcedimentos, true, 'hidden', 3, '');
							          db_input('sEstrutura', 58, @$IsEstrutura, true, 'hidden', 3, '');
							          db_input('sUnidades', 58, @$IsUnidades, true, 'hidden', 3, '');
							          db_input('sNomeArquivo', 58, @$IsNomeArquivo, true, 'hidden', 3, '');
							          ?> 
                        <input type="button" name="relatorio" id="relatorio" value="Relatório" 
                               onClick="return js_gerarRelatorio();">
                      </td>
                    </tr>
                  </table>
                </center>
              </form>
            </fieldset>
	        </td>
        </tr>
      </table>
    </center>
    <?
    db_menu(db_getsession("DB_id_usuario"), 
            db_getsession("DB_modulo"), 
            db_getsession("DB_anousu"),
            db_getsession("DB_instit")
           );
    ?>
  </body>
</html>
<script>
/*
 * ===========================================
 *           INICIALIZAR PARÂMETROS
 * ===========================================
 */
var sURL = 'sau4_ambulatorial.RPC.php';
var lTemProcedimentos = false;

js_inicializarGrids();
js_preencherGrupos();

/*
 * ===========================================
 *              MÉTODOS GERAIS
 * ===========================================
 */
function js_ajax(oParam, sCarregando, jsRetorno){ 
	
	var objAjax = new Ajax.Request(
                         sURL, 
                         {
                          method    : 'post', 
                          parameters: 'json='+Object.toJSON(oParam),
                          onCreate  : function(){
                          				js_divCarregando( sCarregando, 'msgbox');
                          			},
                          onComplete: function(objAjax){
                          				var evlJS = jsRetorno+'( objAjax )';
                          				js_removeObj('msgbox');
                          				eval( evlJS );
                          			}
                         }
                        );
    
}

/*
 * ===========================================
 *      MÉTODOS DE CONTROLE DOS GRID'S
 * ===========================================
 */
function js_inicializarGrids() {

	/* GRID UPS */
	var aHeaderUPS = new Array ('<input type="button" name="marcarUPS" value="M" id="marcarUPS" ' + 
			                        ' onClick="js_marcar(this);">',
                              'UPS',
                              'Descrição' 
                             );
    
	oGridUPS              = new DBGrid('oGridUPS');
	oGridUPS.nameInstance = 'oGridUPS';
	oGridUPS.hasTotalizador = false;
	oGridUPS.setCellWidth(new Array('4%', '18%', '80%'));
	oGridUPS.setHeader(aHeaderUPS);
	oGridUPS.setHeight(60);
	oGridUPS.allowSelectColumns(true);
	oGridUPS.setCellAlign(new Array('center', 'center', 'left'));
	oGridUPS.show($('gridUPS'));
	js_selecionarUnidades(); //Preencher o GRID
	
	/* GRID PROCEDIMENTOS */
	var aHeaderProc = new Array ('<input type="button" name="marcarProcedimentos" value="M" id="marcarProcedimentos"' +
			                         ' onClick="js_marcar(this);">',
                               'Procedimento',
                               'Descrição' 
                              );
    
	oGridProcedimentos              = new DBGrid('oGridProcedimentos');
	oGridProcedimentos.nameInstance = 'oGridProcedimentos';
	oGridProcedimentos.hasTotalizador = false;
	oGridProcedimentos.setCellWidth(new Array('4%', '18%', '80%'));
	oGridProcedimentos.setHeader(aHeaderProc);
	oGridProcedimentos.setHeight(60);
	oGridProcedimentos.allowSelectColumns(true);
	oGridProcedimentos.setCellAlign(new Array('center', 'center', 'left'));
	oGridProcedimentos.show($('gridProcedimentos'));
	
}

function js_selecionarUnidades(){

	var oParam  = new Object();
	oParam.exec = "getUnidadesSaude";	
	js_ajax(oParam, 'Aguarde, Selecionando as unidades...', 'js_preencherGridUnidades');
	
} 

function js_preencherGridUnidades(oAjaxRetorno) {

	oGridUPS.clearAll(true);
	var oRetorno = eval("("+oAjaxRetorno.responseText+")");	
  if (oRetorno.iStatus == 1) {
	 
    if (oRetorno.unidades != undefined && oRetorno.unidades.length > 0) {
     	
    	oRetorno.unidades.each(function (oUnidades, iIterator) {

    	  var oUnidade = new Element('input', {'value' : oUnidades.sd02_i_codigo, 'id':'lbu'+iIterator, 'type': 'text',
      	                                     'readonly':'readonly'});
	   	  var aLinha = new Array();
	      aLinha[0]  = '<input type="checkbox" name="cku' + iIterator + '" id="cku' + iIterator + '">'; 
	      aLinha[1]  = oUnidade.outerHTML;
	      aLinha[2]  = oUnidades.descrdepto.urlDecode(); 
	      oGridUPS.addRow(aLinha);
	      
      });
    	oGridUPS.renderRows();
    }
     	
  } else {
    alert(oRetorno.sMessage.urlDecode());
  }
	
}

/*
 * ===========================================
 *              BUSCAR GRUPO
 * ===========================================
 */
function js_pesquisaGrupo() {
	 
	var sParam  = 'func_sau_grupo.php?';
  sParam     += 'funcao_js=parent.js_mostraGrupo';
	sParam     += '|sd60_i_codigo&sOrderBy=sd60_i_anocomp|desc,|sd60_i_mescomp|desc,|sd60_c_grupo' ;
  js_OpenJanelaIframe('', 'db_iframe_sau_grupo', sParam, 'Pesquisa', true);

}

function js_mostraGrupo(sd60_i_codigo){
	
  for (iI = 0; iI < $('sd60_i_codigo').length; iI++) {

	  if ($('sd60_i_codigo').options[iI].value == sd60_i_codigo) {
		  $('sd60_i_codigo').options[iI].selected = true;
	  }
	  
  }
  js_limparProcedimento();
	js_preencherSubgrupos();
	db_iframe_sau_grupo.hide();

}

function js_preencherGrupos() {

  var oParam             = new Object();
  oParam.exec            = "getGrupos";	
  js_ajax(oParam, 'Aguarde, Carregando Grupos...', 'js_retornoSelectGrupos');

}

function js_retornoSelectGrupos(oAjaxRetorno) {

	js_clearSelect($('sd60_i_codigo'));
	js_limparProcedimento();
	var oRetorno = eval("("+oAjaxRetorno.responseText+")");	
	if (oRetorno.iStatus == 1) {

		$('sd60_i_codigo').add(new Option('', ''), null);
    for (iI = 0; iI < oRetorno.grupo.length; iI++) {
    	$('sd60_i_codigo').add(new Option(oRetorno.grupo[iI].nome.urlDecode(), oRetorno.grupo[iI].codigo), null);
    }

	}
	 
}

/*
 * ===========================================
 *             BUSCAR SUBGRUPO
 * ===========================================
 */

function js_pesquisaSubGrupo() {

	if ($('sd60_i_codigo').options[$('sd60_i_codigo').selectedIndex].text.trim() == "") {
		
	  alert("Selecione um Grupo.");
		return;
		    
	}	
	var sParam  = 'func_sau_subgrupo.php?';
  sParam     += 'funcao_js=parent.js_mostraSubGrupo';
	sParam     += '|sd61_i_codigo&sOrderBy=sd61_c_subgrupo,|sd61_i_anocomp|desc,|sd61_i_mescomp|desc,|sd60_c_grupo,';
	sParam     += '|sd61_c_subgrupo' ;
	sParam     += '&chave_grupo=' + $('sd60_i_codigo').options[$('sd60_i_codigo').selectedIndex].text.substring(0,2);
	sParam     += '&lDistinct=1';
  js_OpenJanelaIframe('', 'db_iframe_sau_subgrupo', sParam, 'Pesquisa', true);

}

function js_mostraSubGrupo(sd61_i_codigo){
	
  for (iI = 0; iI < $('sd61_i_codigo').length; iI++) {

	  if ($('sd61_i_codigo').options[iI].value == sd61_i_codigo) {
		  $('sd61_i_codigo').options[iI].selected = true;
	  }
	  
  }
  js_limparProcedimento();
  js_preencherFormaOrganizacao();
	db_iframe_sau_subgrupo.hide();

}

function js_preencherSubgrupos() {

	if ($('sd60_i_codigo').options[$('sd60_i_codigo').selectedIndex].text.trim() == "") {

		$('sd61_i_codigo').selectedIndex = 0;
		$('sd62_i_codigo').selectedIndex = 0;
	  return;
	  
	}	
  var oParam    = new Object();
	oParam.exec   = "getSubGrupos"; 
	oParam.grupo  = $('sd60_i_codigo').options[$('sd60_i_codigo').selectedIndex].text.substring(0,2);
	js_ajax(oParam, 'Aguarde, Carregando Sub Grupo(s)...', 'js_retornoSelectSubGrupos');
	
}

function js_retornoSelectSubGrupos(oAjaxRetorno) {

	js_clearSelect($('sd61_i_codigo'));
	js_clearSelect($('sd62_i_codigo'));
	js_limparProcedimento();
	var oRetorno = eval("("+oAjaxRetorno.responseText+")");	
	if (oRetorno.iStatus == 1) {

		$('sd61_i_codigo').add(new Option('', ''), null);
    for (iI = 0; iI < oRetorno.subgrupo.length; iI++) {
    	$('sd61_i_codigo').add(new Option(oRetorno.subgrupo[iI].nome.urlDecode(), oRetorno.subgrupo[iI].codigo), null);
    }
    
	}
		 
}

/*
 * ===========================================
 *          BUSCAR FORMA ORGANIZAÇÃO
 * ===========================================
 */
function js_pesquisaFormaOrganizacao() {

	if ($('sd60_i_codigo').options[$('sd60_i_codigo').selectedIndex].text.trim() == "") {
		
		alert("Selecione um Grupo.");
	  return;
		    
  }	
  if ($('sd61_i_codigo').options[$('sd61_i_codigo').selectedIndex].text.trim() == "") {
			
	  alert("Selecione um Sub Grupo.");
	  return;
			    
  }	
	var sParam  = 'func_sau_formaorganizacao.php?';
  sParam     += 'funcao_js=parent.js_mostraFormaOrganizacao';
	sParam     += '|sd62_i_codigo&sOrderBy=sd62_c_formaorganizacao,|sd62_i_anocomp|desc,|sd62_i_mescomp|desc,';
	sParam     += '|sd60_c_grupo,|sd61_c_subgrupo,|';
  sParam     += 'sd62_c_formaorganizacao' ;
	sParam     += '&chave_grupo=' + $('sd60_i_codigo').options[$('sd60_i_codigo').selectedIndex].text.substring(0,2);
	sParam     += '&chave_subgrupo=' + $('sd61_i_codigo').options[$('sd61_i_codigo').selectedIndex].text.substring(0,2);
	sParam     += '&lDistinct=1';
  js_OpenJanelaIframe('', 'db_iframe_sau_formaorganizacao', sParam, 'Pesquisa', true);

}

function js_mostraFormaOrganizacao(sd62_i_codigo){

	js_limparProcedimento();
  for (iI = 0; iI < $('sd62_i_codigo').length; iI++) {

	  if ($('sd62_i_codigo').options[iI].value == sd62_i_codigo) {
		  $('sd62_i_codigo').options[iI].selected = true;
	  }
	  
  }
  db_iframe_sau_formaorganizacao.hide();

}

function js_preencherFormaOrganizacao() {

	if ($('sd61_i_codigo').options[$('sd61_i_codigo').selectedIndex].text.trim() == "") {

		  $('sd62_i_codigo').selectedIndex = 0;
	    return;
	        
  }	
  var oParam             = new Object();
	oParam.exec            = "getFormaOrganizacao";	
	oParam.grupo           = $('sd60_i_codigo').options[$('sd60_i_codigo').selectedIndex].text.substring(0,2);
	oParam.subgrupo        = $('sd61_i_codigo').options[$('sd61_i_codigo').selectedIndex].text.substring(0,2);
	js_ajax(oParam, 'Aguarde, Carregando Forma(s) de Organização...', 'js_retornoSelectFormaOrganizacao');
	
}

function js_retornoSelectFormaOrganizacao(oAjaxRetorno) {

	js_clearSelect($('sd62_i_codigo'));
	js_limparProcedimento();
	var oRetorno = eval("("+oAjaxRetorno.responseText+")");	
	if (oRetorno.iStatus == 1) {

		$('sd62_i_codigo').add(new Option('', ''), null);
    for (iI = 0; iI < oRetorno.formaorganizacao.length; iI++) {
        
    	$('sd62_i_codigo').add(new Option(oRetorno.formaorganizacao[iI].nome.urlDecode(),
    	    	                            oRetorno.formaorganizacao[iI].codigo), 
    	    	                 null
    	    	                );
        
    }

	}
		 
}

/*
 * ===========================================
 *            BUSCAR PROCEDIMENTO
 * ===========================================
 */
function js_pesquisaProcedimento(lMostra) {

	var sParam  = 'func_sau_procedimento.php?';
  sParam     += 'funcao_js=parent.js_mostraProcedimento';
	sParam     += '|sd63_i_codigo|sd63_c_nome|sd63_c_procedimento';
	if (lMostra) {
		
	  if ($('sd60_i_codigo').options.length > 0 && 
			  $('sd60_i_codigo').options[$('sd60_i_codigo').selectedIndex].text != '') {
		
	    sParam += '&chave_sd60_c_grupo=';
	    sParam += $('sd60_i_codigo').options[$('sd60_i_codigo').selectedIndex].text.substring(0,2);

    }
    if ($('sd61_i_codigo').options.length > 0 && 
    	  $('sd61_i_codigo').options[$('sd61_i_codigo').selectedIndex].text != '') {

	    sParam += '&chave_sd61_c_subgrupo=';
	    sParam += $('sd61_i_codigo').options[$('sd61_i_codigo').selectedIndex].text.substring(0,2);
	  
    }
    if ($('sd62_i_codigo').options.length > 0 && 
    	  $('sd62_i_codigo').options[$('sd62_i_codigo').selectedIndex].text != '') {

	    sParam += '&chave_sd62_c_formaorganizacao=';
	    sParam += $('sd62_i_codigo').options[$('sd62_i_codigo').selectedIndex].text.substring(0,2);
		  
	  }
    js_OpenJanelaIframe('', 'db_iframe_sau_procedimento', sParam, 'Pesquisa', true);
    
	} else {

		 if ($('sd63_c_procedimento').value == '') {
			
       js_limparProcedimento();
			 $('sd63_c_procedimento').focus();
      
		 } else {
			
      sParam += '&nao_mostra=true&chave_sd63_c_procedimento=' + $('sd63_c_procedimento').value;
      js_OpenJanelaIframe('', 'db_iframe_sau_procedimento', sParam, 'Pesquisa', true); 
      
		}  
	    
	}

}

function js_limparProcedimento() {
	
	 $('sd63_i_codigo').value       = '';
	 $('sd63_c_nome').value         = '';
	 $('sd63_c_procedimento').value = '';
	 
}

function js_mostraProcedimento(sd63_i_codigo, sd63_c_nome, sd63_c_procedimento) {

	/* SE NÃO ENCONTRAR */	
	if (sd63_c_procedimento == undefined) {
		
		$('sd63_i_codigo').value       = '';
		$('sd63_c_nome').value         = sd63_c_nome;
		$('sd63_c_procedimento').value = '';
		$('sd63_c_procedimento').focus();
		
	} else {
		
	  $('sd63_i_codigo').value       = sd63_i_codigo;
	  $('sd63_c_nome').value         = sd63_c_nome;
	  $('sd63_c_procedimento').value = sd63_c_procedimento;
	  
	}
  db_iframe_sau_procedimento.hide();

}

/*
 * ===========================================
 *        CONSULTAR POR PROCEDIMENTO(S)
 * ===========================================
 */
function js_consultar() {

	var oParam           = new Object();
	oParam.exec          = "getProcedimentos";	
	oParam.sGrupo        = '';
  oParam.sSubGrupo     = '';
  oParam.sFormaOrg     = '';     
  oParam.sProcedimento = '';
  if ($('sd63_c_procedimento').value != "") {
	  oParam.sProcedimento = $('sd63_c_procedimento').value;
  }
  if ($('sd60_i_codigo').options.length > 0 && 
		  $('sd60_i_codigo').options[$('sd60_i_codigo').selectedIndex].text.trim() != "") {
	  
		oParam.sGrupo = $('sd60_i_codigo').options[$('sd60_i_codigo').selectedIndex].text.substring(0,2); 
		
  }	
  if ($('sd61_i_codigo').options.length > 0 && 
		  $('sd61_i_codigo').options[$('sd61_i_codigo').selectedIndex].text.trim() != "") {
	  
	  oParam.sSubGrupo = $('sd61_i_codigo').options[$('sd61_i_codigo').selectedIndex].text.substring(0,2); 			

	}	
  if ($('sd62_i_codigo').options.length > 0 && 
		  $('sd62_i_codigo').options[$('sd62_i_codigo').selectedIndex].text.trim() != "") {
	  
		oParam.sFormaOrg = $('sd62_i_codigo').options[$('sd62_i_codigo').selectedIndex].text.substring(0,2);
		 			
	}	
  js_ajax(oParam, 'Aguarde, Carregando os Procedimentos...', 'js_preencherGridProcedimentos');
	
}

function js_preencherGridProcedimentos(oAjaxRetorno) {

	oGridProcedimentos.clearAll(true);
	var oRetorno = eval("("+oAjaxRetorno.responseText+")");	
  if (oRetorno.iStatus == 1) {

    if (oRetorno.procedimento != undefined && oRetorno.procedimento.length > 0) {
     	
    	oRetorno.procedimento.each(function (oProcedimentos, iIterator) {
        	     		
	   		                var aLinha = new Array();
	                      aLinha[0]  = '<input type="checkbox" name="ckp' + iIterator + '" id="ckp' + iIterator + '">'; 
		                    aLinha[1]  = '<label name="txp' + iIterator + '" id="txp' + iIterator + '">0';
		                    aLinha[1] +=  + oProcedimentos.sd63_c_procedimento.urlDecode() + '</label>'; 
	                      aLinha[2]  = oProcedimentos.sd63_c_nome.urlDecode(); 
	                      oGridProcedimentos.addRow(aLinha);
	                      
                     	});
    	                oGridProcedimentos.renderRows();
    }
     	
  } else {
    alert(oRetorno.sMessage.urlDecode());
  }
	
}

function js_gerarRelatorio(lGerar) {
 
	if ($('data1').value == '') {

	  alert("Selecione a data inicial.");
    return false;
  
	}
	if ($('data2').value == '') {

	  alert("Selecione a data final.");
	  return false;
	  
  }	
	if (js_getUnidades() == '' && $('agrupar').value == 1) {

	  alert("Selecione uma UPS.");
	  return false;  
	
	}
	if (oGridProcedimentos.getNumRows() == 0 && $('agrupar').value == 2) {

	  alert("Selecione um Procedimento.");
	  return false;
	  
	}

  gerarTxtProcedimentos();
  
}

function js_relatorio(lArquivo, sNomeArquivo) {

	var sParam         = '';
	sParam  = 'dataini=' + $('data1').value + '&datafim=' + $('data2').value;
  sParam += "&agrupar=" + $('agrupar').value;
  sParam += '&lBuscarProcedimentos=' + lArquivo;
  sParam += '&sArquivo=' + $F('sNomeArquivo');
  sParam += "&estrutura=" + $F('sEstrutura');
  sParam += '&unidades=' + $F('sUnidades');
    
  jan = window.open('sau2_producaoambulatorial002.php?' + sParam,
                    '',
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                   );
  jan.moveTo(0, 0);

}
	
function gerarTxtProcedimentos() {

	$('sProcedimentos').value = js_getProcedimentos();
	$('sUnidades').value      = js_getUnidades();
	$('sEstrutura').value     = js_getEstrutura() + js_getCodigosEstrutura();

	salvaProcedimentosSessao();
	
}

function salvaProcedimentosSessao() {

  var oSelf                      = this;
  var oParametros                = new Object();
      oParametros.exec           = 'salvaProcedimentosSessao';
      oParametros.sProcedimentos = $F('sProcedimentos');
      
  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametros);
      oDadosRequisicao.asynchronous = true;
      oDadosRequisicao.onComplete = function(oAjax) {
                                      retornosalvaProcedimentosSessao(oAjax);
                                    };
      
  js_divCarregando("Aguarde....", "msgBoxA");
  new Ajax.Request('sau4_producaoambulatorial.RPC.php', oDadosRequisicao);
} 

function retornosalvaProcedimentosSessao (oAjax) {

  js_removeObj("msgBoxA");
  var oSelf    = this;
  var oRetorno = eval('('+oAjax.responseText+')');


  if (oRetorno.status != 1) {

    alert('falha');
    return false;
  }

  js_relatorio(lTemProcedimentos, '');
  
}


function js_getEstrutura() {

	sEstrutura = "";
  if ($('sd60_i_codigo').options.length > 0 && 
		  $('sd60_i_codigo').options[$('sd60_i_codigo').selectedIndex].text.trim() != "") {

	  sEstrutura = $('sd60_i_codigo').options[$('sd60_i_codigo').selectedIndex].text.substring(0,2); 

  }	
	if ($('sd61_i_codigo').options.length > 0 && 
			$('sd61_i_codigo').options[$('sd61_i_codigo').selectedIndex].text.trim() != "") {
		
		sEstrutura += $('sd61_i_codigo').options[$('sd61_i_codigo').selectedIndex].text.substring(0,2); 		
			
	}	
	if ($('sd62_i_codigo').options.length > 0 && 
			$('sd62_i_codigo').options[$('sd62_i_codigo').selectedIndex].text.trim() != "") {
		
		sEstrutura += $('sd62_i_codigo').options[$('sd62_i_codigo').selectedIndex].text.substring(0,2); 		
			
  }	
	return sEstrutura;
	
}

function js_getCodigosEstrutura() {

	sEstrutura = "";
  if ($('sd60_i_codigo').options.length > 0 && 
 	  $('sd60_i_codigo').options[$('sd60_i_codigo').selectedIndex].text.trim() != "") {
   
    sEstrutura = "&gp=" + $('sd60_i_codigo').options[$('sd60_i_codigo').selectedIndex].value; 
 
	}	
  if ($('sd61_i_codigo').options.length > 0 && 
		  $('sd61_i_codigo').options[$('sd61_i_codigo').selectedIndex].text.trim() != "") {

	  sEstrutura += "&sg=" + $('sd61_i_codigo').options[$('sd61_i_codigo').selectedIndex].value; 			

  }	
  if ($('sd62_i_codigo').options.length > 0 && 
		  $('sd62_i_codigo').options[$('sd62_i_codigo').selectedIndex].text.trim() != "") {
	  
		sEstrutura += "&fo=" + $('sd62_i_codigo').options[$('sd62_i_codigo').selectedIndex].value; 			

  }

	return sEstrutura;
	
}

function js_getUnidades() {

	var sStr   = '';
	var lTodos = true;
	for (var iI = 0; iI < oGridUPS.getNumRows(); iI++) {

		if ($('cku' + iI).checked == 1) {


		  if (sStr != '') {
			  sStr += ',';  
		  } 
		  sStr += $('lbu' + iI).value; 
	    
		} else {
      lTodos = false;
	  }

	}
	if (lTodos) {
	  sStr += '&todos=true';
  }

	return sStr;
	
}

function js_getProcedimentos() {

  var iLinha         = 0;
  var sProcedimentos = '';
	for (var iI = 0; iI < oGridProcedimentos.getNumRows(); iI++) {

		if ($('ckp' + iI).checked == 1) {

			if(sProcedimentos != '') {
				sProcedimentos += ','; 
			}
		  sProcedimentos += "'" + $('txp' + iI).innerHTML + "'"; 

		  lTemProcedimentos = true;
		}
	}

	return sProcedimentos;
	
}

function js_marcar(oButton) {

	var oGrid = '';
	var oCk   = '';
	if (oButton.name == 'marcarUPS') {

	  oGrid = oGridUPS;
	  oCk   = 'cku';
		   
	} else {
		
		oGrid = oGridProcedimentos;
		oCk   = 'ckp';
		
  }
  for(var iI = 0; iI < oGrid.getNumRows(); iI++) {
	  $(oCk + iI).checked = (oButton.value == 'M') ? 1 : 0;  
	}
	if (oButton.value == 'M') {
	  oButton.value = 'D';
	} else {
	  oButton.value = 'M';
	}
	
}

function js_clearSelect(oSelect) {

  for (iI = oSelect.length; iI > 0; iI--) {
	  oSelect.remove(iI - 1);
  }
	
}

</script>