<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("classes/db_orctiporec_classe.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

$oGet             = db_utils::postMemory($_GET); 
$iCodigoRelatorio = $oGet->iCodigoRelatorio;

echo "<script>\n";
echo "  var iCodigoRelatorio = {$iCodigoRelatorio};\n";
echo "  var aFiltros         = new Array();\n";
if (isset($oGet->filtros) && $oGet->filtros != "") {

  $aFiltros         = explode(",", $oGet->filtros);
  foreach ($aFiltros as $iFiltro => $sFiltro) {
    echo "aFiltros[$iFiltro] = '{$sFiltro}';\n"; 
  }
}
echo "</script>\n";
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
     db_app::load("scripts.js, prototype.js, windowAux.widget.js, filtroorcamento.widget.js, strings.js");
     db_app::load("estilos.css, grid.style.css");
    ?>
  </head>
  <body>
  <div id='filtros'>
  </div>
  </body>
</html>
<script>
  /**
   * Caso exista a aba relatorio, desabilitamos seus compenentes até a tela terminar de carregar
   */
  if (parent.iframe_relatorio) {
    
    var frmRelatorio = parent.iframe_relatorio.document.forms[0];
    for (var i = 0; i < frmRelatorio.elements.length; i++) {
      
      with(frmRelatorio.elements[i]) {
        disabled = true;
      }
    }
  }
  
  sURlRPC = "con4_configuracaorelatorioRPC.php";
  function js_saveFiltrosUsuarios() {
  
     var oParam = new Object();
     oParam.iRelatorio = iCodigoRelatorio;  
     oParam.exec       = "salvarParametroRelatorioUsuario";
     oParam.filters = new Object();
     oParam.filters.orgao     = filtro.getOrgaos();
     oParam.filters.unidade   = filtro.getUnidades();
     oParam.filters.funcao    = filtro.getFuncoes();
     oParam.filters.subfuncao = filtro.getSubFuncoes();
     oParam.filters.programa  = filtro.getProgramas();
     oParam.filters.projativ  = filtro.getProjAtivs();
     oParam.filters.elemento  = filtro.getElementos();
     oParam.filters.recurso   = filtro.getRecursos();
     js_divCarregando('Aguarde, Salvando Dados',"msgBox");
     $('btnSalvar').disabled = true;
     var oAjax = new Ajax.Request(sURlRPC,
                                  {method:"post",
                                   parameters:"json="+Object.toJSON(oParam),
                                   onComplete:js_retornosaveParametro
                                  }
                                 );
     
   
  }
  
  function js_retornosaveParametro(oAjax) {
      
    $('btnSalvar').disabled = false;
    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 1) {
      alert('Dados Salvos com sucesso.');
    } else {
      alert(oRetorno.message.urlDecode());
    } 
  }
  
  function js_getFiltrosUsuario() {
   
   var oParam = new Object();
   oParam.iRelatorio = iCodigoRelatorio;  
   oParam.exec       = "getParametrosRelatorioUsuario";
   js_divCarregando('Aguarde, pesquisando suas configuraçoes para esse relatório',"msgBox");
   var oAjax = new Ajax.Request(sURlRPC,
                                {method:"post",
                                 parameters:"json="+Object.toJSON(oParam),
                                 onComplete:js_retornoGetFiltros
                                }
                               ); 
 }
 
 function js_retornoGetFiltros(oAjax) {
 
   js_removeObj("msgBox"); 
   var oRetorno = eval("("+oAjax.responseText+")");
   filtro = new filtroOrcamento("filtroRelatorio"+iCodigoRelatorio);
   filtro.showInline($('filtros'));
   if (aFiltros.length > 0) {
    filtro.setFiltros(aFiltros);
   }
   //filtro.setFiltroDefault("recurso");
   filtro.setData(oRetorno.filter);
   filtro.showSaveButton(true);
   filtro.setCallBackSave(js_saveFiltrosUsuarios);
   if (parent.iframe_relatorio) {
    
     var frmRelatorio  =parent.iframe_relatorio.document.forms[0];
     for (var i = 0; i < frmRelatorio.elements.length; i++) {
      
       with(frmRelatorio.elements[i]) {
         disabled = false;
       }
     }
   }
 }
 
 function getFiltros() {
 
   oFilters = new Object();
   oFilters.orgao     = filtro.getOrgaos();
   oFilters.unidade   = filtro.getUnidades();
   oFilters.funcao    = filtro.getFuncoes();
   oFilters.subfuncao = filtro.getSubFuncoes();
   oFilters.programa  = filtro.getProgramas();
   oFilters.projativ  = filtro.getProjAtivs();
   oFilters.elemento  = filtro.getElementos();
   oFilters.recurso   = filtro.getRecursos();
   return oFilters;
 }
 js_getFiltrosUsuario();
</script>