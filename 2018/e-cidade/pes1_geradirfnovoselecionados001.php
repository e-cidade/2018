<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("scripts.js, strings.js,arrays.js, prototype.js,datagrid.widget.js");
  db_app::load("widgets/windowAux.widget.js, widgets/dbmessageBoard.widget.js");
  db_app::load("estilos.css, grid.style.css");
?>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_montaGrid();">
<table border="0" align="center" cellspacing="0" cellpadding="0" width="630px">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td valign="top"> 
     <fieldset>
       <legend>
         <b>Matrículas Selecionadas</b>
       </legend>
       <div id="boxDataGrid"></div>
     </fieldset>
		 <table align='center'>
		   <tr>
		     <td>&nbsp;</td>
		   </tr>
		   <tr>
		     <td>
		       <input type='button' id='confirmar' name='confirmar' value='Confirmar' onclick="js_confirmar();">
		     </td>
		   </tr>
		 </table>
    </td>
  </tr>
</table>
</body>
<script>
/**
 * Monta grid com as matriculas.
 **/
function js_montaGrid() {
  
  /**
   * Instância a DBGrid. 
   **/
  oDBGridMatriculas = new DBGrid("listaMatriculas");
  oDBGridMatriculas.nameInstance = "oDBGridMatriculas";
  oDBGridMatriculas.setCellWidth(new Array('30%', '70%'));
  oDBGridMatriculas.setCellAlign(new Array('left', 'left'));
  oDBGridMatriculas.setCheckbox(0);
  oDBGridMatriculas.setHeader(new Array('Matrícula', 'Nome'));
  oDBGridMatriculas.show($('boxDataGrid'));
  
  var iTipoProcessamento = new Number(parent.iframe_geradirf.$('iTipoProcessamento').value);
  if (iTipoProcessamento == 2) {  
    js_buscaMatriculas();
  }
}
   
/**
 * Busca dados das matriculas.
 **/
function js_buscaMatriculas() {
  
  js_divCarregando('Aguarde buscando matrículas...', 'msgBox');
  
  var oParam              = new Object();
  oParam.exec             = 'getMatriculasDirf';
  oParam.iAno             = parent.iframe_geradirf.$('ano_base').value;
  oParam.sCnpj            = parent.iframe_geradirf.$('cnpjpagador').value;
  oParam.sAcima           = parent.iframe_geradirf.$('acima6000').value;
  oParam.iValor           = parent.iframe_geradirf.$('valor_minimo').textContent;
  oParam.lProcessaEmpenho = parent.iframe_geradirf.$('dadosfinanceiros').value=='s'?true:false;
  
  var oAjax               = new Ajax.Request('pes4_processardirf.RPC.php',
                                             { method:'post',
                                               parameters:'json='+Object.toJSON(oParam),
                                               onComplete: js_preencherGrid
                                             }
                                            );
}
  
/**
 * Preenche os dados no datagrid.
 **/
function js_preencherGrid(oAjax) {
  
  js_removeObj("msgBox");
    
  var aRetorno       = eval("("+oAjax.responseText+")");     
  var aListaMatriculas = aRetorno.aListaMatriculas;
     
  if (aRetorno.status == 2) {
    
    alert(aRetorno.message.urlDecode());
    return false;
  } else {
    
    oDBGridMatriculas.clearAll(true);
     
    if (aListaMatriculas.length > 0) {
        
      oDBGridMatriculas.clearAll(true);
      aListaMatriculas.each(function (oDadoRetorno, iInd) {
          
        aLinha    = new Array();
        aLinha[0] = oDadoRetorno.rh99_regist;
        aLinha[1] = oDadoRetorno.z01_nome.urlDecode();
  
        oDBGridMatriculas.addRow(aLinha); 
      });
          
      oDBGridMatriculas.renderRows();
    }
    
    return true;
  }
}
  
/**
 * Retorna as matriculas selecionadas
 **/
function js_retornaMatriculasSelecionados() {

  var aMatriculasSelecionados = oDBGridMatriculas.getSelection();
  var aSelecionados           = new Array();
  if (aMatriculasSelecionados.length > 0) {
              
    aMatriculasSelecionados.each(function (aDadoRetorno, iInd) {
      aSelecionados[iInd] = aDadoRetorno[0];
    });
  }
  
  return aSelecionados.implode(',');
}

function js_confirmar() {
  parent.mo_camada('geradirf');
}
</script>
</html>