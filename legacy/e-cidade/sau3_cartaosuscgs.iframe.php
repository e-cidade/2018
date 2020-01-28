<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

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
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<br>
  <table width="500" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td align="left" valign="top" bgcolor="#CCCCCC"> 
        <center>
          <fieldset style='width: 92%;'> <legend><b>Cartões SUS do Paciente</b></legend> 
            <table border="0" width="90%">
              <tr>
                <td>
                  <?
                  db_input('z01_i_cgsund', 10, '', true, 'hidden', 3, '');
                  ?>
                  <div id='grid_cns' style='width: 100%;'></div>
                </td>
              </tr>
            </table>
          </fieldset>
        </center>
      </td>
    </tr>
  </table>
</center>

<script>

oDBGridCns = js_criaDataGrid();
js_getTodosCnsCgs();

function js_ajax(oParam, jsRetorno, sUrl) {

  var mRetornoAjax;

  if (sUrl == undefined) {
    sUrl = 'sau4_ambulatorial.RPC.php';
  }
	var objAjax = new Ajax.Request(sUrl, 
                                 {
                                  method: 'post',
                                  asynchronous: false,
                                  parameters: 'json='+Object.toJSON(oParam),
                                  onComplete: function(oAjax) {
                                  				      var evlJS = jsRetorno+'(oAjax);';
                                                return mRetornoAjax = eval(evlJS);
                                  		        }
                                 }
                                );

  return mRetornoAjax;

}

/**** Bloco de funções do grid início */
function js_criaDataGrid() {

  oDBGrid = new DBGrid('grid_cns');
  oDBGrid.nameInstance = 'oDBGridCns';
  oDBGrid.hasTotalizador = false;
  oDBGrid.setCellWidth(new Array('50%', '50%'));
  oDBGrid.setHeight(100);

  var aHeader = new Array();
  aHeader[0] = 'Cartão SUS';
  aHeader[1] = 'Tipo';
  oDBGrid.setHeader(aHeader);

  var aAligns = new Array();
  aAligns[0] = 'center';
  aAligns[1] = 'center';
  oDBGrid.setCellAlign(aAligns);

  oDBGrid.show($('grid_cns'));
  oDBGrid.clearAll(true);

  return oDBGrid;

}

function js_getTodosCnsCgs() {

  var oParam  = new Object();
	oParam.exec = 'getTodosCnsCgs';
	oParam.iCgs = $F('z01_i_cgsund');

  if ($F('z01_i_cgsund') != '') {
    js_ajax(oParam, 'js_retornoGetTodosCnsCgs');
  }

}

function js_retornoGetTodosCnsCgs(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus == 1) {

    var aLinha = new Array();

    for (var iCont = 0; iCont < oRetorno.aCartoes.length; iCont++) {

      aLinha[0]  = oRetorno.aCartoes[iCont].s115_c_cartaosus.urlDecode();
      aLinha[1]  = oRetorno.aCartoes[iCont].s115_c_tipo.urlDecode() == 'D' ? 'Definitivo' : 'Provisório';
  
      oDBGridCns.addRow(aLinha);

    }

    oDBGridCns.renderRows();
    return true;

  } else {

  	alert(oRetorno.sMessage.urlDecode());
  	return false;

  }

}

</script>
</body>
</html>