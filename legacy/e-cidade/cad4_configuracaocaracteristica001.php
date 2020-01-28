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

	require_once ("libs/db_stdlib.php");
	require_once ("libs/db_conecta.php");
	require_once ("libs/db_sessoes.php");
	require_once ("libs/db_usuariosonline.php");
	require_once ("libs/db_app.utils.php");
	require_once ("libs/db_utils.php");
	require_once ("dbforms/db_funcoes.php");
	
?>
<html>
	<head>
		<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="Expires" CONTENT="0">
		<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
		<link href="estilos.css" rel="stylesheet" type="text/css">
	</head>

	<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
  <div class="container" style="width:700px !important;">
    
    <fieldset>
    
      <legend>Vinculação de Características</legend>

      <table class="form-container">
        <tr>
          <td style='width:140px'><strong>Grupo de Car. PIT:</strong></td>
          <td><?php db_select('db139_sequencial', array(), true, 1, 'onchange="js_getCaracteristicasPitPorGrupo();"') ?></td>
        </tr>
       
        <tr>
       	  <td align="left"><strong>Característica PIT:</strong></td>
       	  <td><?php db_select('db140_sequencial', array(), true, 1, 'onchange="js_getCaracteristicasCadastro();"') ?></td>
 	     </tr>
 	    </table>
	
    <div id="gridVinculacaoCaracteristicas" style="margin-top:15px;"></div>
	
	  </fieldset>
	  
	  <input type="submit" value="Salvar" onclick="js_salvar();" />
	  
	</div>
		
		<?php
			db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
		?>
	</body>
</html>

<script type="text/javascript">
	/**
	 * Instancia de GridView
	 */
	var oGridVinculacao;
	var sUrlRPC    = 'cad4_configuracaocaracteristica.RPC.php';
	var oGet       = js_urlToObject();
	var aRegistros = [];

	(function() {

		/**
     * Instancia de GridView
     */
    oGridVinculacao              					 = new DBGrid('GridVinculacao');
    oGridVinculacao.nameInstance 					 = 'oGridVinculacao';
    oGridVinculacao.setCellWidth(new Array( "&nbsp;", "15%", "40%", "40%" ));
    oGridVinculacao.setCellAlign(new Array( 'center', 'center', 'left', 'left' ));
    oGridVinculacao.setCheckbox(1);
    oGridVinculacao.setHeader(new Array( "&nbsp;", "Código", "Grupo", "Descrição" ));
    oGridVinculacao.aHeaders[1].lDisplayed = false;
    oGridVinculacao.show($('gridVinculacaoCaracteristicas'));
    oGridVinculacao.clearAll(true);
    
	  /**
     * Popula Combo de Grupo de Caracteristicas
	   */
    var oParametros               = new Object();
    oParametros.sExecucao         = 'getGruposCaracteristicasPit';

    var oDadosRequisicao          = new Object();
    oDadosRequisicao.method       = 'POST';
    oDadosRequisicao.asynchronous = false;
    oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametros);
    oDadosRequisicao.onComplete   = function(oAjax){

      var oRetorno = eval("("+oAjax.responseText+")");
      if (oRetorno.iStatus == "2") {

        alert( oRetorno.sMensagem.urlDecode() );
        return;
      }

      for ( var iGrupoCaracteristica = 0; iGrupoCaracteristica < oRetorno.oGruposCaracteristicaPit.length; iGrupoCaracteristica++) {
          
   		  if(iGrupoCaracteristica == 0){
        		 var oOpcao        = document.createElement("option");
        		 oOpcao.value 		 = null;
             oOpcao.text  		 = 'Selecione...';
             $('db139_sequencial').appendChild( oOpcao );
        }
          
        var oDadosGruposCaracteristica = oRetorno.oGruposCaracteristicaPit[iGrupoCaracteristica];
        var oOpcao                     = document.createElement("option");
            oOpcao.value               = oDadosGruposCaracteristica.db139_sequencial;
            oOpcao.text                = oDadosGruposCaracteristica.db139_descricao.urlDecode();
            $('db139_sequencial').appendChild(oOpcao);
      }
				      
      $('db139_sequencial').appendChild( oSelect );
    }

    var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );
    
	})();

	/**
	  * Popula Combo de Grupo de Caracteristicas Pit Por Grupo
	  */
	function js_getCaracteristicasPitPorGrupo (){

	    var oParametros                   = new Object();
	    oParametros.sExecucao             = 'getCaracteristicasPitPorGrupo';
	    oParametros.iGrupoCaracteristica  = $('db139_sequencial').value;
	                                      
	    var oDadosRequisicao              = new Object();
	    oDadosRequisicao.method           = 'POST';
	    oDadosRequisicao.asynchronous     = false;
	    oDadosRequisicao.parameters       = 'json='+Object.toJSON(oParametros);
	    oDadosRequisicao.onComplete       = function(oAjax){

	      $('db140_sequencial').innerHTML = '';
	      oGridVinculacao.clearAll( true );
	      
	      var oRetorno = eval("("+oAjax.responseText+")");
	      if (oRetorno.iStatus == "2") {

	        alert( oRetorno.sMensagem.urlDecode() );
	        return;
	      }
	      
	      for ( var iCaracteristicasPit = 0; iCaracteristicasPit < oRetorno.oCaracteristicasPit.length; iCaracteristicasPit++) {
	          
	   		  if(iCaracteristicasPit == 0){
	        		 var oOpcao              = document.createElement("option");
	        		 oOpcao.value 		       = null;
	             oOpcao.text  		       = 'Selecione...';
	             $('db140_sequencial').appendChild( oOpcao );
	        }
	          
	        var oDadosCaracteristicasPit = oRetorno.oCaracteristicasPit[iCaracteristicasPit];
	        var oOpcao                   = document.createElement("option");
	            oOpcao.value             = oDadosCaracteristicasPit.db140_sequencial;
	            oOpcao.text              = oDadosCaracteristicasPit.db140_descricao.urlDecode();
	            $('db140_sequencial').appendChild(oOpcao);
	        }
					      
	        $('db140_sequencial').appendChild( oSelect );
	    }

	    var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );
	}

	/**
	  * Popula Grid de Caracteristicas
	  */
	function js_getCaracteristicasCadastro (){

	    var oParametros                  = new Object();
	    oParametros.sExecucao            = 'getCaracteristicasCadastro';
	    oParametros.iCaracteristicaPit   = $('db140_sequencial').value;
	    
	    var oDadosRequisicao             = new Object();
	    oDadosRequisicao.method          = 'POST';
	    oDadosRequisicao.asynchronous    = false;
	    oDadosRequisicao.parameters      = 'json='+Object.toJSON(oParametros);
	    oDadosRequisicao.onComplete      = function(oAjax){

	      oGridVinculacao.clearAll( true );
	      
	      var oRetorno = eval("("+oAjax.responseText+")");
	      if (oRetorno.iStatus == "2") {

	        alert( oRetorno.sMensagem.urlDecode() );
	        return;
	      }
	      
	      for(var iCaracteristica=0; iCaracteristica < oRetorno.oCaracteristicasCadastro.length; iCaracteristica++ ){

			    var oDados       = oRetorno.oCaracteristicasCadastro[iCaracteristica];
			    var lSelecionado = oDados.lselecionado == "t";
			    
			    oGridVinculacao.addRow( 
					                        ['',oDados.j31_codigo,
					    									   oDados.j32_descr.urlDecode(),
					    									   oDados.j31_descr.urlDecode()],
					    									   '',
					    									   '',
					    									   lSelecionado
					    									  );
			  }
		  
	      oGridVinculacao.renderRows();
	    }

	    var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );
	}

  /**
   * Salva as vinculações no banco
   */
	function js_salvar() {
			var oParametros                  = new Object();
	    oParametros.sExecucao            = 'salvar';
	    oParametros.iCaracteristicaPit   = $('db140_sequencial').value;
	    oParametros.aCaracteristicas     = new Array();

	    var aSelecionados 							 = oGridVinculacao.getSelection();
	    for (var iIndex = 0; iIndex < aSelecionados.length; iIndex++) {
				oParametros.aCaracteristicas.push ( aSelecionados[iIndex][0] );		
		  }
	    
	    var oDadosRequisicao             = new Object();
	    oDadosRequisicao.method          = 'POST';
	    oDadosRequisicao.asynchronous    = false;
	    oDadosRequisicao.parameters      = 'json='+Object.toJSON(oParametros);
	    oDadosRequisicao.onComplete      = function(oAjax){

	      var oRetorno = eval("("+oAjax.responseText+")");
	      if (oRetorno.iStatus == "2") {

	        alert( oRetorno.sMensagem.urlDecode() );
	        return;
	      }

	      if (  oParametros.aCaracteristicas.length == 0 ) {
		      alert( _M('tributario.cadastro.cad4_configuracaocaracteristica.nenhuma_vinculacao_com_sucesso') );
		      window.location = 'cad4_configuracaocaracteristica001.php';
		      return;
		    }
		    
	      alert( _M('tributario.cadastro.cad4_configuracaocaracteristica.vinculacao_com_sucesso') );
	      window.location = 'cad4_configuracaocaracteristica001.php';	      
	    }

	    var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );
	}
</script>