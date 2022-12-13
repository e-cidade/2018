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

require_once('libs/db_stdlib.php');
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/db_usuariosonline.php');
require_once('libs/db_utils.php');
require_once('dbforms/db_funcoes.php');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table align="center" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="center" valign="top" bgcolor="#CCCCCC">
      <fieldset style='width: 90%;'> <legend><b>Observação no Agendamento</b></legend>
        
        <fieldset><legend><b>Grade de Horário:</b></legend>
          <table width="100%">
            <tr>
              <td align="center">
                <?
                db_input('sd27_i_codigo', 10, '', true, 'hidden', 3, "");
                db_input('sd23_d_consulta', 10, '', true, 'hidden', 3, "");
                ?>
                <input type="button" name="confirmar" id="confirmar" value="Confirmar" title="Confirmar"
                  onclick="js_confirmar();">
                <input type="button" name="fechar" id="fechar" value="Fechar" title="Fechar janela"
                  onclick="parent.db_iframe_observacao.hide();">
              </td>
            </tr>
            <tr>
              <td>
                <iframe id="frameagendados" name="frameagendados" src="" 
                  width="100%" height="300" scrolling="yes" frameborder="0">
                </iframe>
              </td>
            </tr>
          </table>
        </fieldset>

      </fieldset>
    </td>
  </tr>
</table>

<script>

js_agendados();

function js_ajax(oParam, jsRetorno, sUrl) {

  var mRetornoAjax;

  if (sUrl == undefined) {
    sUrl = 'sau4_agendamento.RPC.php';
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

function js_agendados() {


  sd23_d_consulta = $F('sd23_d_consulta');
  sd27_i_codigo   = $F('sd27_i_codigo');
  
  if (sd23_d_consulta != '') {

    iAno       = sd23_d_consulta.substr(6, 4);
    iMes       = parseInt(sd23_d_consulta.substr(3, 2), 10) - 1;
    iDia       = sd23_d_consulta.substr(0, 2);
    dData      = new Date(iAno, iMes, iDia);
    iDiaSemana = dData.getDay() + 1;

    var sUrl   = 'sau4_agendamento002.php';
    sUrl      += '?sd27_i_codigo='+sd27_i_codigo;
    sUrl      += '&chave_diasemana='+iDiaSemana;
    sUrl      += '&sd23_d_consulta='+sd23_d_consulta;
    sUrl      += '&sTransf=true&sLado=de';
    sUrl      += '&lMostraSeq=true';
    sUrl      += '&lEscondeFicha=true';
    sUrl      += '&lMostraTipoFicha=true';
    sUrl      += '&lEscondeHoraFim=true';
    sUrl      += '&lEscondeReserva=true';
    sUrl      += '&lEscondeTipoGrade=true';
    sUrl      += '&lMostraObs=true';
    sUrl      += '&lEscondeCkBox=true';
    sUrl      += '&lUnificado=true';

    $('frameagendados').src = sUrl;

  }

}

function js_confirmar() {

  var oIframe          = $('frameagendados').contentDocument;
  var aElementos       = oIframe.getElementsByName('ckbox'); // Pego todos os checkbox
  var iTam             = aElementos.length;
  var oParam           = new Object();
  var sCodigos         = '';
  var sSep             = '';
  var iIndice          = 0;
                       
  oParam.exec          = 'lancarObservacaoAgendamentos';
  oParam.aAgendamentos = new Array();

  for (var iCont = 0; iCont < iTam; iCont++) {
    
    var oObs = oIframe.getElementById('obs_'+iCont);
    if (!oObs.disabled && oObs.value != '') {

      var oAgendamento              = new Object();
      oAgendamento.sObs             = oObs.value;
      oAgendamento.iCodigo          = oIframe.getElementById('ckbox_'+iCont).value.split(' ## ')[0];
      oParam.aAgendamentos[iIndice] = oAgendamento;
      iIndice++;

    }

  }

  if (iIndice == 0) {

    alert('Nenhum agendamento para lançar observação.');
    return false;

  } else {

    js_ajax(oParam, 'js_retornoConfirmar');

  }

}

function js_retornoConfirmar(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  message_ajax(oRetorno.sMessage.urlDecode());
  if (oRetorno.iStatus == 1) { // Se não houve erro, atualizo o grid
    parent.js_agendados();
  }

}


</script>

</body>
</html>