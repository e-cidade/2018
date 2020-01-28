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
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("std/db_stdClass.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php 
     db_app::load('scripts.js, prototype.js, strings.js, datagrid.widget.js, DBHint.widget.js');
     db_app::load('estilos.css, grid.style.css');
    ?>
  </head>
  <body style='background-color: #cccccc'>
    <div>
      <fieldset>
         <legend>
           <b>Movimenta��es do Processo</b>
         </legend>
          <div id='ctnDataGridMovimentacoes' style="width: 100%;"></div>
      </fieldset>
    </div>
  </body>
</html>
<div id='ajudaItem' style='position:absolute;border:1px solid #FFDD00; display:none; text-indent: 15px;
                           background-color: #FFFFCC;width: 70%; '>
  
</div>
<script>
var iCodigoProcesso = '<?php echo $oGet->codigo_processo;?>';
var sRPC = 'prot4_processoprotocolo004.RPC.php';

var oDataGridMovimentacoes = new DBGrid('gridMovimentacoes');
oDataGridMovimentacoes.nameInstance = 'oDataGridMovimentacoes';
oDataGridMovimentacoes.setCellWidth(['8%', '5%', '20%', '20%', '20%', '10%', '10%', '7%']);
oDataGridMovimentacoes.setCellAlign(['center', 'center']);
oDataGridMovimentacoes.setHeader(['Data', 'Hora', 'Departamento', 'Instituicao', 'Login', 'Ocorr�ncia', 'Despacho', 'Imprimir']);
oDataGridMovimentacoes.setHeight(250);
oDataGridMovimentacoes.show($('ctnDataGridMovimentacoes'));
oDataGridMovimentacoes.clearAll(true);

js_buscarMovimentacoes();

function js_buscarMovimentacoes() { 

  js_divCarregando('Buscando movimenta��os do processo.', 'msgbox');
  var oParametro  = new Object();
  oParametro.exec = 'getMovimentacoesProcesso';
  oParametro.iCodigoProcesso = iCodigoProcesso;
  
  new Ajax.Request( sRPC, { method:'post', parameters:'json='+Object.toJSON(oParametro), onComplete: function(oAjax) {

    js_removeObj("msgbox");
    var oRetorno = eval("(" + oAjax.responseText + ")");

    if ( oRetorno.lErro ) {

      alert(oRetorno.sMensagem.urlDecode());
      return false;
    }

    oRetorno.aMovimentacoes.each(function(oMovimento, iSeq) {

      var aLinha = new Array();
      aLinha[0] = oMovimento.sData.urlDecode();
      aLinha[1] = oMovimento.sHora.urlDecode(); 
      aLinha[2] = oMovimento.iDepartamento + ' - ' + oMovimento.sDepartamento.urlDecode();
      aLinha[3] = oMovimento.iInstituicao + ' - ' + oMovimento.sInstituicao.urlDecode();
      aLinha[4] = oMovimento.sLogin.urlDecode();
      aLinha[5] = oMovimento.sObservacoes.urlDecode();
      aLinha[6] = oMovimento.sDespacho.urlDecode().replace(/<br>/g, '');
      aLinha[7] = '';

      if (oMovimento.lImprimir) {

        var sButton  = "<input type='button' value='imprimir' name='Imprimir' ";
            sButton += " onclick='js_imprimeDespacho(\"" + iCodigoProcesso + "\",\"" + oMovimento.iAndamentoInterno + "\")' />";
        aLinha[7] = sButton;
      }

      oDataGridMovimentacoes.addRow(aLinha);
      oDataGridMovimentacoes.aRows[iSeq].aCells[5].sEvents += " onmouseover='js_displayAjuda(\""+aLinha[5]+"\", true)'";
      oDataGridMovimentacoes.aRows[iSeq].aCells[5].sEvents += " onmouseout='js_displayAjuda(\"\", false)'";
      oDataGridMovimentacoes.aRows[iSeq].aCells[6].sEvents += " onmouseover='js_displayAjuda(\""+oMovimento.sDespacho.urlDecode()+"\", true)'";
      oDataGridMovimentacoes.aRows[iSeq].aCells[6].sEvents += " onmouseout='js_displayAjuda(\"\", false)'";
      oDataGridMovimentacoes.aRows[iSeq].aCells[4].sEvents += " onmouseover='js_displayAjuda(\""+aLinha[4]+"\", true)'";
      oDataGridMovimentacoes.aRows[iSeq].aCells[4].sEvents += " onmouseout='js_displayAjuda(\"\", false)'";
    });

    oDataGridMovimentacoes.renderRows();

  }});

}

function js_displayAjuda(sTexto, lShow) {

  if (lShow) {
    
    el     =  $('ctnDataGridMovimentacoes'); 
    var x  = 0;
    var y  = el.offsetHeight;
        x += el.offsetLeft;
        y += el.offsetTop;
    $('ajudaItem').innerHTML     = sTexto;
    $('ajudaItem').style.display = '';
    $('ajudaItem').style.top     = $('ctnDataGridMovimentacoes').scrollTop + 20;
    $('ajudaItem').style.left    = x;
   
  } else {
    $('ajudaItem').style.display = 'none';
  }
}

function js_imprimeDespacho(codproc, codprocandamint) {

  var sUrl = 'pro2_despachointer002.php?codproc='+codproc+'&codprocandamint='+codprocandamint;
  jan = window.open(sUrl, '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>