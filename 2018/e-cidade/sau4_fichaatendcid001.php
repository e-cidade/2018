<?php
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
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
require("libs/db_app.utils.php");
include("dbforms/db_funcoes.php");
include("libs/db_jsplibwebseller.php");


db_postmemory($HTTP_POST_VARS);
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    try{
      db_app::load("scripts.js");
      db_app::load("prototype.js");
      db_app::load("strings.js");
      db_app::load("estilos.css");
      db_app::load("datagrid.widget.js");
      db_app::load("grid.style.css");
    }catch (Exception $eException){
      die( $eException->getMessage() );
    }?>
  </head>
  <body>
    <fieldset ><legend>CID's</legend>
      <div name="cidgrid" id="cidgrid"></div>
    </fieldset>
    <input type="hidden" id="cgs" value="<?=$cgs?>">
    <center>
    <input type="button" value="Fechar" id="fechar" name="fechar" onclick="parent.js_fechar()">
    </center>
  </body>
</html>
<script>
  objGridCid = new DBGrid('gridcid');
  var arrHeader = new Array ( "Atendimento"," CID","Descrição");
  objGridCid.nameInstance = 'oGridExames';
  objGridCid.setHeader( arrHeader );
  objGridCid.setHeight(250);
  objGridCid.show($('cidgrid'));
  init();
  /**
   * Ajax
   */
  function js_ajax( objParam, strCarregando, jsRetorno ){ 
    var objAjax = new Ajax.Request(
                           'sau1_sau_individualprocedRPC.php', 
                           {
                            method    : 'post', 
                            parameters: 'json='+Object.toJSON(objParam),
                            onCreate  : function(){
                                    js_divCarregando( strCarregando, 'msgbox');
                                  },
                            onComplete: function(objAjax){
                                    var evlJS = jsRetorno+'( objAjax )';
                                    js_removeObj('msgbox');
                                    eval( evlJS );
                                  }
                           }
                          );
  }
  
  function init(){
    if(document.getElementById("cgs").value!=''){
  	  var objParam                 = new Object();
	    objParam.exec                = "geraGridCid";
	    objParam.sd24_i_numcgs     = $F('cgs');
	    js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_AtualizaGrid' );
    }
  }
  function js_AtualizaGrid(objAjax){
	  alert
	  var oRetorno = eval("("+objAjax.responseText+")");
	  objGridCid.clearAll(true);
    if(oRetorno.status==1){
	    tam = oRetorno.aItens.length; 
	    for(x=0;x<tam;x++){
	    	oRetorno.aItens[x][2]=oRetorno.aItens[x][2].urlDecode();
	    	objGridCid.addRow(oRetorno.aItens[x]);
	    }
	    objGridCid.renderRows();

	  }else{
		  alert(oRetorno.message);
	  }
  }
</script>