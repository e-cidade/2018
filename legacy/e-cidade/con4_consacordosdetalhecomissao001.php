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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/verticalTab.widget.php"));
require_once(modification("model/Acordo.model.php"));
require_once(modification("model/AcordoComissao.model.php"));
require_once(modification("model/AcordoComissaoMembro.model.php"));
require_once(modification("model/CgmFactory.model.php"));

$oGet = db_utils::postMemory($_GET);
$oAcordoComissao = new AcordoComissao ($oGet->iComissao);
?>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<?
db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, widgets/windowAux.widget.js");
db_app::load("widgets/dbmessageBoard.widget.js,widgets/dbtextField.widget.js");
db_app::load("DBViewAcordoPrevisao.classe.js,widgets/dbtextFieldData.widget.js,classes/DBViewAcordoExecucao.classe.js, widgets/DBHint.widget.js");
db_app::load("estilos.css, grid.style.css,tab.style.css");
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<style>
 .tdWidth   {width:150px;}
 .tdBgColor {background-color:#FFFFFF; color: #000000; width: 100%;}
 .fora      {background-color: #d1f07c;}
</style>
</head>

<form name="form1" method="post" action="">
  <center>
  
    <table align="center" style="margin-top:15px;" width="98%"  border = "0">
      
      <tr>
        <td nowrap="nowrap" class="tdWidth" align="left" width="10%">
          <b>Código Comissão:</b>
        </td>
        <td class="tdBgColor"><?php echo $oAcordoComissao->getCodigo(); ?></td>
      
        <td align="left" width="10%" nowrap="nowrap">
          <b>Descrição:</b>
        </td>
        <td class="tdBgColor"><?php echo $oAcordoComissao->getDescricao()?></td>
      </tr>
     
      <tr>
        <td class="tdWidth" align="left" width="20%"  nowrap="nowrap">
          <b>Data Inicial:</b>
        </td>
        <td class="tdBgColor"><?php echo $oAcordoComissao->getDataInicial(); ?></td>
      
        <td align="left" width="20%"  nowrap="nowrap">
          <b>Data Final:</b>
        </td>
        <td class="tdBgColor"><?php echo $oAcordoComissao->getDataFinal()?></td>
      </tr> 
      
      <tr>
        <td colspan="4">
        
		      <fieldset style="margin-top:10px;">
		        <legend align="left"><b>Membros Cadastrados</b></legend>
		          <div id='cntGridMembros'></div> 
		      </fieldset>
        </td>
      </tr>
      
     </table>

      
   </center>
</form>
<script>

function js_init() {

  oGridMembrosComissao              = new DBGrid("gridMembros");
  oGridMembrosComissao.nameInstance = "oGridMembrosComissao";

  oGridMembrosComissao.setCellWidth(new Array(  '100px' ,
																			          '100px' ,
																			          '300px' ,
																			          '150px'
         ));
  
  oGridMembrosComissao.setCellAlign(new Array("center", "center", "left", "left"));
  oGridMembrosComissao.setHeader(new Array("Código", "Cgm", "Membro", "Responsabilidade"));
  oGridMembrosComissao.show($('cntGridMembros'));
}

js_init();

var sUrl = 'con4_contratos.RPC.php';
  
function js_consultaMembros(iAcordo) {
   
  js_divCarregando('Consultando membros da Comissão...','msgBox');
  var strJson = '{"exec":"getMembros","iAcordo":"'+iAcordo+'"}';
  var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: 'json='+strJson, 
                                            onComplete: js_completaGrid 
                                          }
                                  );    
}
 
function js_completaGrid(oAjax) {

  js_removeObj("msgBox");
 
  var oRetorno = eval("("+oAjax.responseText+")");   
  var aMembros = oRetorno.oAcordo.aMembros;   
 
  oGridMembrosComissao.clearAll(true);
    
  aMembros.each(function (oMembro, id) {

   var aLinha = new Array();
   
   aLinha[0] = oMembro.iCodigo;
   aLinha[1] = oMembro.iCodigoCgm;
   aLinha[2] = oMembro.sNome.urlDecode();
   aLinha[3] = oMembro.sResponsabilidade.urlDecode();
   oGridMembrosComissao.addRow(aLinha);     
   
 });
 
 oGridMembrosComissao.renderRows();   
}
 
js_consultaMembros(<?=$oGet->iComissao?>);

</script>