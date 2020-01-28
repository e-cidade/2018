<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("std/db_stdClass.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php"); 
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/verticalTab.widget.php");
require_once ("model/aberturaRegistroPreco.model.php");

$clrotulo = new rotulocampo;

$db_opcao = 3;

$clrotulo->label("pc10_numero");
$clrotulo->label("pc10_depto");
$clrotulo->label("descrdepto");
$clrotulo->label("pc67_motivo");

$oGet = db_utils::postMemory($_GET);

$clAbertRegPreco = new aberturaRegistroPreco($oGet->pc10_numero);  

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<?
//db_app::load("scripts.js, strings.js, prototype.js,datagrid.widget.js, widgets/dbautocomplete.widget.js");
//db_app::load("widgets/windowAux.widget.js");
db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, widgets/windowAux.widget.js,
             classes/infoLancamentoContabil.classe.js,messageboard.widget.js");
db_app::load("estilos.css, grid.style.css,tab.style.css");
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_grvDetalhes();">
<center>
<table width="100%">
  <tr>
    <td>
    <fieldset>
      <div id="grvDetalhes">
      
      </div>  
    </fieldset>
    </td>
  </tr>
</table>
</center>
</body>
</html>
<script type="text/javascript">

var detalhe     = '<?=$oGet->exec; ?>';
var pc10_numero = '<?=$oGet->pc10_numero; ?>';
var sUrlRC = 'com4_solicitacaoComprasRegistroPreco.RPC.php';

function js_completaPesquisa(pc10_numero,detalhe) {

   var oParam          = new Object();
   oParam.exec         = "consAberturaDetalhes";
   oParam.pc10_numero  = pc10_numero;
   oParam.detalhe      = detalhe;
   //db_iframe_estimativaregistropreco.hide();
   var oAjax           = new Ajax.Request(sUrlRC,
                                         {
                                          method: "post",
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_retornoCompletaPesquisa
                                         });
}

function js_retornoCompletaPesquisa(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");
  
  if (oRetorno.status == 1) {
    
    oGrvDetalhes.clearAll(true);
    if (oRetorno.dados != false && oRetorno.detalhe == 'estimativa') {
    
      var iNumDados = oRetorno.dados.length;  
     
      if (iNumDados > 0) {
      
        oRetorno.dados.each( 
                  function (oDado, iInd) {

                    var aRow = new Array();
                    aRow[0] = oDado.codigo;
                    aRow[1] = js_formatar(oDado.emissao,'d');
                    aRow[2] = js_formatar(oDado.anulacao,'d');
                    aRow[3] = oDado.departamento.urlDecode();
                    aRow[4] = oDado.sInstituicao.urlDecode();
                    oGrvDetalhes.addRow(aRow);
                    oGrvDetalhes.aRows[iInd].sEvents +=  "ondblClick=\'parent.js_showItens(1, "+oDado.codigo+", \""+oDado.departamento+"\")\'";
                                        
                  }     
      
                  );
        oGrvDetalhes.renderRows();
          
      }
           
	  } else if (oRetorno.dados != false && oRetorno.detalhe == 'compilacao') {
    
      var iNumDados = oRetorno.dados.length;  
     
      if (iNumDados > 0) {
      
        oRetorno.dados.each( 
                  function (oDado, iInd) {
                    var aRow = new Array();
                                                            
                    aRow[0] = oDado.codigo;
                    aRow[1] = js_formatar(oDado.emissao,'d');
                    aRow[2] = js_formatar(oDado.datainicial,'d');
                    aRow[3] = js_formatar(oDado.datafinal,'d');
                    aRow[4] = oDado.departamento.urlDecode();
                    aRow[5] = oDado.processamento.urlDecode();
                    
                    if (oDado.processocompra == null) {
                      oDado.processocompra = '';
                    }
                    aRow[6] = oDado.processocompra;
                    aRow[7] = js_formatar(oDado.datacancelamento, 'd');
                    
                    
                    oGrvDetalhes.addRow(aRow);
                    oGrvDetalhes.aRows[iInd].sEvents +=  "ondblClick=\'parent.js_showItensCompilacao("+oDado.codigo+")\'";
                                        
                  }     
      
                  );
        oGrvDetalhes.renderRows();
      }
           
    } else if (oRetorno.dados != false && oRetorno.detalhe == 'itens') {
    
      var iNumDados = oRetorno.dados.length;  
     
      if (iNumDados > 0) {
      
        oRetorno.dados.each( 
                  function (oDado, iInd) {
                    var aRow = new Array();

                    aRow[0] = oDado.ordem;
                    aRow[1] = oDado.codigo;
                    aRow[2] = oDado.material.urlDecode();
                    aRow[3] = oDado.resumo.urlDecode();
                    aRow[4] = oDado.unidade.urlDecode();
                    aRow[5] = js_formatar(oDado.valor_unitario, 'f');
                    oGrvDetalhes.addRow(aRow);
                    oGrvDetalhes.aRows[iInd].aCells[0].sStyle +="background-color:#DED5CB;font-weight:bold;padding:1px";
                                        
                  }     
      
                  );
        oGrvDetalhes.renderRows();
        if (oRetorno.formacontrole == 1) {
          oGrvDetalhes.showColumn(false, 6);
        }
      }
    } else {
        
    	if(oRetorno.message != ''){
    		  
      	alert(oRetorno.message.urlDecode());
    	}
	  }
  }
}

function js_grvDetalhes() {
  
  if (detalhe == 'estimativa') {
    
	  oGrvDetalhes = new DBGrid('detalhes');
	  oGrvDetalhes.nameInstance = 'oGrvDetalhes';
	  oGrvDetalhes.setCellWidth(new Array('10%',
	                                      '20%',
	                                      '20%',
	                                      '25%',
	                                      '25%'
	                                      ));
	                                      
	  oGrvDetalhes.setCellAlign(new Array('right',
	                                      'center',
	                                      'center',
	                                      'left',
	                                      'left'
	                                      ));
	                                      
	  oGrvDetalhes.setHeader(new Array('Código',
	                                   'Emissão',
	                                   'Anulação',
	                                   'Departamento',
	                                   'Instituição'
	                                  ));
	  oGrvDetalhes.setHeight(230);
	  oGrvDetalhes.show($('grvDetalhes'));
	          
	  oGrvDetalhes.clearAll(true);
	  oGrvDetalhes.renderRows();
	  
	 } else if (detalhe == 'compilacao') {
	   
	         
      oGrvDetalhes = new DBGrid('detalhes');
      oGrvDetalhes.nameInstance = 'oGrvDetalhes';
      oGrvDetalhes.setCellWidth(new Array('10%',
                                          '10%',
                                          '10%',
                                          '10%',
                                          '30%',
                                          '10%',
                                          '10%',
                                          '20%'
                                          ));
                                          
      oGrvDetalhes.setCellAlign(new Array('right',
                                          'center',
                                          'center',
                                          'center',
                                          '',
                                          'center',
                                          'right',
                                          'center'
                                          ));
                                          
      oGrvDetalhes.setHeader(new Array('Código',
                                       'Emissão',
                                       'Data Inicial',
                                       'Data Final',
                                       'Departamento',
                                       'Processamento',
                                       'Processo de Compras',
                                       'Data Canc.'
                                      ));
      oGrvDetalhes.setHeight(230);
      oGrvDetalhes.show($('grvDetalhes'));
              
      oGrvDetalhes.clearAll(true);
      oGrvDetalhes.renderRows();
	    
	 } else if (detalhe == 'itens') {

      oGrvDetalhes              = new DBGrid('detalhes');
      oGrvDetalhes.nameInstance = 'oGrvDetalhes';
      oGrvDetalhes.setCellWidth(new Array('5%',
                                          '5%',
                                          '20%',
                                          '50%',
                                          '20%',
                                          '20%'
                                          ));
                                          
      oGrvDetalhes.setCellAlign(new Array('right',
                                          'right',
                                          'left',
                                          'left',
                                          'left',
                                          'right'
                                          ));
                                          
      oGrvDetalhes.setHeader(new Array('Item',
                                       'Código',
                                       'Material',
                                       'Resumo',
                                       'Unidade',
                                       'Valor'
                                      ));
      oGrvDetalhes.setHeight(230);
      oGrvDetalhes.show($('grvDetalhes'));
      oGrvDetalhes.clearAll(true);
      oGrvDetalhes.renderRows();
      
   }
	 js_completaPesquisa(pc10_numero, detalhe);
}


</script>