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

/**
 * 
 * @author I
 * @revision $Author: dbiuri $
 * @version $Revision: 1.1 $
 */
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<?
db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, widgets/messageboard.widget.js");
db_app::load("widgets/dbautocomplete.widget.js, widgets/windowAux.widget.js,widgets/dbtextField.widget.js");
db_app::load("widgets/dbtextFieldData.widget.js");
db_app::load("estilos.css, grid.style.css");
?>
<script type="text/javascript" src="scripts/resgistroPrecoMovimento.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<style>
/*  .bloqueado {background-color: #d1f07c}*/
  .bloqueado {background-color: rgb(222, 184, 135);}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
  <table>
    <tr height="25">
      <td>&nbsp;</td>
    </tr>
   </table>
  <center>
     <table width="80%">
        <tr>  
          <td>
            <fieldset>
              <legend>
                 <b>Registros de preço - Cancelamento de Bloqueio</b>
              </legend>
              <div id='ctngridSolicita'>
              </div>              
            </fieldset>
          </td>
        </tr>
     </table>
  </center>
</body>
<div style='position:absolute;top: 200px; left:15px;
            border:1px solid black;
            width:300px;
            text-align: left;
            padding:3px;
            background-color: #FFFFCC;
            display:none;z-index: 100000' 
            id='ajudaItem'>

</div>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
  var sUrlRPC = "lic4_licitacao.RPC.php";
  function js_init() {
  
    ogridSolicita     = new DBGrid('gridSolicita');
    ogridSolicita.nameInstance = "ogridSolicita";
    ogridSolicita.setHeight(300);
    ogridSolicita.setCellAlign(new Array("right","right","Left","center","center"));
    ogridSolicita.setCellWidth(new Array("10%","10%","50%",'15%', "15%"));
    ogridSolicita.setHeader(new Array("Licitacao","Registro","Descrição","Data Inicial","Data Final"));
    ogridSolicita.show($('ctngridSolicita'));
    js_getRegistroPreco(); 
    
  }
  
  function js_getRegistroPreco() {
  
    js_divCarregando('Aguarde, pesquisando.', 'msgBox');
    var oParam  = new Object();
    oParam.exec = "getRegistrosdePreco";
    var oAjax   = new Ajax.Request(sUrlRPC,
                                   {
                                    method: "post",
                                    parameters:'json='+Object.toJSON(oParam),
                                    onComplete: js_retornoGetRegistroPreco
                                   });
  }
  
  function js_retornoGetRegistroPreco(oAjax) {
  
    js_removeObj('msgBox'); 
    var oRetorno = eval("("+oAjax.responseText+")");
    ogridSolicita.clearAll(true);
    if (oRetorno.status == 1) {
    
      if (oRetorno.itens.length == 0) {
        ogridSolicita.setStatus('Não foram encontrados Registros');
      }
      for (var i = 0; i < oRetorno.itens.length; i++) {
      
        with(oRetorno.itens[i]) {
          
          var aLinha = new Array();
          aLinha[0]  = licitacao;                 
          aLinha[1]  = solicitacao;                 
          aLinha[2]  = resumo.urlDecode().substring(0,50);
          aLinha[3]  = datainicio;
          aLinha[4]  = datatermino;
          ogridSolicita.addRow(aLinha);
          ogridSolicita.aRows[i].sEvents += "onDblClick='js_getMovimentos(3,"+solicitacao+","+orcamento+")'";
        }
      }
      ogridSolicita.renderRows();
    }
  }

  js_init(); 
</script>