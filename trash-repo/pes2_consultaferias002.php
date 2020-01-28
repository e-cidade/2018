<?php
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
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

require_once("dbforms/db_funcoes.php");

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("estilos.css");
      db_app::load("scripts.js");
      db_app::load("strings.js");
      db_app::load("prototype.js");
      db_app::load("datagrid.widget.js");
      db_app::load("widgets/datagrid/plugins/DBHint.plugin.js");
      db_app::load("classes/pessoal/ferias/DBViewPeriodoGozoFerias.classe.js");
      db_app::load("classes/pessoal/ferias/DBViewPeriodoGozoRubricas.classe.js");
      db_app::load("widgets/dbtextFieldData.widget.js");
    ?>
  </head>
  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
    <div class='container' style='width:700px !important;'>
      
        <fieldset>
          <legend><strong>Consulta de Férias</strong></legend>

				      <table class='form-container'>
							  <tr> 
								  <td><strong>Matrícula:</strong> 
				            <?
				            db_input('rh01_regist', '', '', true, 'text', 3, "class='field-size2'");
				            ?>
			              <?
			               db_input('z01_nome', '', '', true, 'text', 3, "class='field-size9'");
			              ?> 
			            </td>
			          </tr>
			        </table>
          
          		<fieldset class='separator'>
          		  <legend><strong>Períodos Aquisitos</strong></legend>
          					
          			  <div id="container-grid"></div>
          			  
          		</fieldset>
          		
        </fieldset>
      
      <input name="fechar" type="button"  value="Voltar" onclick="window.location.href = 'pes2_consultaferias001.php'">
    </div>
    <?php
    db_menu(db_getsession("DB_id_usuario"),
            db_getsession("DB_modulo"),
            db_getsession("DB_anousu"),
            db_getsession("DB_instit"));
    ?>
  </body>
</html>
<script type="text/javascript">
var aRegistros = [];
var sUrlRPC    = 'pes2_consultaferias.RPC.php';
(function(){

	oGridPeriodos                 = new DBGrid('GridPeriodos');
	oGridPeriodos.nameInstance    = 'oGridPeriodos';
	oGridPeriodos.sName           = 'GridPeriodos';
	oGridPeriodos.setCellAlign    (new Array("center", "center", "center", "center", "center", "center", "center", "center"));
	oGridPeriodos.setHeader       (["M", "Data Inicial", "Data Final", "Faltas", "Dias Gozados", "Dias Abonados", "Saldo", "Saldo Avos"]);
	oGridPeriodos.aWidths			    = new Array('5%', '15%', '15%', '10%', '15%', '15%', '10%', '10%');
	oGridPeriodos.setHeight(450);	
	oGridPeriodos.show($('container-grid'));
	oGridPeriodos.clearAll(true);

	var oParametros               = new Object();
  oParametros.sExecucao         = 'getPeriodosPorServidor';
  oParametros.iCodigoServidor   = $F('rh01_regist');
	
	var oDadosRequisicao    		  = new Object();
	oDadosRequisicao.method 		  = 'post';
	oDadosRequisicao.asynchronous = false;
	oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametros);
	oDadosRequisicao.onComplete   = function(oAjax){
	    
	  var oRetorno = eval("("+oAjax.responseText+")");
	  if (oRetorno.status == "2") {
	      
	     alert(oRetorno.message.urlDecode());
	     return;
	  }
	
	  for(var iPeriodo=0; iPeriodo < oRetorno.aPeriodosAquisitivos.length; iPeriodo++ ){
	
      var oDadosPeriodos = oRetorno.aPeriodosAquisitivos[iPeriodo];

      oGridPeriodos.addRow([ '<a href="#" onmouseup="mostrarPeriodoGozo('+oDadosPeriodos.iCodigoPeriodoAquisitivo+')">MI</a>',
				                 	   oDadosPeriodos.dDataInicial,
				                	   oDadosPeriodos.dDataFinal,
				                	   new String(oDadosPeriodos.iFaltas),
				                	   new String(oDadosPeriodos.iDiasGozados),
				                	   new String(oDadosPeriodos.iDiasAbono),
				                	   new String(oDadosPeriodos.iSaldo),
				                	   new String(oDadosPeriodos.iSaldoAvo) ]);

	   	 if(!oDadosPeriodos.lDireitoFerias){
		  	 oGridPeriodos.aRows[iPeriodo].addClassName('readonly');
		   }
	  }
	  
	  oGridPeriodos.renderRows();
	  
	  for(var iPeriodo=0; iPeriodo < oRetorno.aPeriodosAquisitivos.length; iPeriodo++ ){
			
		    var oDadosPeriodos = oRetorno.aPeriodosAquisitivos[iPeriodo];
		    if(oDadosPeriodos.sObservacao.urlDecode() != ''){
			    oGridPeriodos.setHint(iPeriodo, null, oDadosPeriodos.sObservacao.urlDecode());
		    }
		}
		 
	};
	
	var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );
   
  })();

 function mostrarPeriodoGozo ( iCodigoPeriodoAquisitivo ) {
   oJanela = DBViewPeriodoGozoFerias.getInstance( iCodigoPeriodoAquisitivo );
   oJanela.show();
 }

 function mostrarPeriodoGozoRubricas ( iCodigoPeriodoGozo ) {
	 
   oJanelaRubricas = DBViewPeriodoGozoRubricas.getInstance( DBViewPeriodoGozoFerias );
   oJanelaRubricas.show();
   oJanelaRubricas.getWindowAux();
   
 }
</script>