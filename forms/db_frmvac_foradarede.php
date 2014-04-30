<?
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
?>
<form name="form1" method="post" action="">
  <center>
    <table border="0" width="100%">
      <tr>
        <td>
          <div id='grid_vacinas' style='width: 100%;'></div>
        </td>
      </tr>
    </table>
    
    <table border="0" width="100%">
      <tr>
        <td align="center">
          <?
          db_input('iCgs', 10, '', true, 'hidden', 3, '');
          ?>
          <input type="button" name="confirmar" value="Confirmar" onclick="return js_confirmar();">
          <input type="button" value="Cancelar"  id="cancelar"
            onclick="window.location.href = 'vac4_foradarede004.php?iCgs=<?=$iCgs?>';">
        </td>
      </tr>
    </table>
  </center>
</form>

<script>

oDBGridVacinas = js_criaDatagrid();
iUsuarioSessao = '<?=db_getsession('DB_id_usuario')?>';
sUrl           = 'vac4_vacinas.RPC.php';

js_getDadosVacinas()

function js_ajax(oParam, jsRetorno) {

	var objAjax = new Ajax.Request(
                         sUrl, 
                         {
                           method    : 'post',
                           asynchronous: false,
                           parameters: 'json='+Object.toJSON(oParam),
                           onComplete: 
                                      function(objAjax) {

                          				      var evlJS = jsRetorno+'(objAjax);';
                                        return eval(evlJS);

                          			      }
                         }
                        );

}

function js_esvaziaSelect(oSel) {

  while (oSel.length != 0) {
    
    oSel.options[0] = null;

  }

}

function js_confirmar() {
  
  var aIds                 = document.getElementsByName('identificador');
  var dAtual               = new Date();
  var dTmp;
  var aTmp;
  var iDose;
  var dData;
  var sObs;
  var iId;
  var oParam               = new Object();
  oParam.exec              = 'confirmarVacinasForaRede';
  oParam.iCgs = <?=$iCgs?>;
  oParam.aVacinasInclusao  = new Array();
  oParam.aVacinasAlteracao = new Array();

  for (iCont = 0; iCont < aIds.length; iCont++) {
   
    if (document.getElementById('alterado'+aIds[iCont].value).value == 'true') {
      
      if (document.getElementById('dt'+aIds[iCont].value).value == '') {
        
        document.getElementById('dt'+aIds[iCont].value).focus();
        alert('Preencha a data da aplicação.');
        return false;

      }

      aTmp = document.getElementById('dt'+aIds[iCont].value).value.split('/');
      dTmp = new Date(aTmp[2], aTmp[1] - 1, aTmp[0]);

      if (dTmp > dAtual) {

        document.getElementById('dt'+aIds[iCont].value).focus();
        alert('A data de aplicação não pode ser maior que a data atual.');
        document.getElementById('dt'+aIds[iCont].value).value = '';
        return false;

      }

      if (document.getElementById('observacao'+aIds[iCont].value).value == '') {

        document.getElementById('observacao'+aIds[iCont].value).focus();
        alert('Preencha a observação.');
        return false;

      }
      
      // Para incluir é necessário o ID da dose
      if (document.getElementById('altOuInc'+aIds[iCont].value).value == 'incluir') {
        iDose = aIds[iCont].value;
      } else { // Para alterar é necessário o ID da aplicação
        iId = document.getElementById('codAplica'+aIds[iCont].value).value;
      }

      dData = js_formataData(document.getElementById('dt'+aIds[iCont].value).value, true);
      sObs  = document.getElementById('observacao'+aIds[iCont].value).value;

      // dados ainda não foram lançados e terão que ser incluídos
      if (document.getElementById('altOuInc'+aIds[iCont].value).value == 'incluir') {
        
        oParam.aVacinasInclusao[oParam.aVacinasInclusao.length]           = new Object;
        oParam.aVacinasInclusao[oParam.aVacinasInclusao.length - 1].iDose = iDose;
        oParam.aVacinasInclusao[oParam.aVacinasInclusao.length - 1].dData = dData;
        oParam.aVacinasInclusao[oParam.aVacinasInclusao.length - 1].sObs  = sObs;

      } else { // alteracao

        oParam.aVacinasAlteracao[oParam.aVacinasAlteracao.length]           = new Object;
        oParam.aVacinasAlteracao[oParam.aVacinasAlteracao.length - 1].iId   = iId;
        oParam.aVacinasAlteracao[oParam.aVacinasAlteracao.length - 1].dData = dData;
        oParam.aVacinasAlteracao[oParam.aVacinasAlteracao.length - 1].sObs  = sObs;
      }

    }

  }

  js_ajax(oParam, 'js_retornoConfirmarVacinas');

}

function js_retornoConfirmarVacinas(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus == 1) {

    message_ajax(oRetorno.sMessage.urlDecode());
    $('cancelar').click();

  } else {
    message_ajax(oRetorno.sMessage.urlDecode());
  }

}

/**** Bloco de funções do grid início */
function js_criaDatagrid() {

  oDBGrid                = new DBGrid('grid_pedidostfd');
  oDBGrid.nameInstance   = 'oDBGridPedidostfd';
  oDBGrid.hasTotalizador = false;
  oDBGrid.setCellWidth(new Array('20%','20%', '20%', '10%' , '10%', '20%'));
  oDBGrid.setHeight(255);
  oDBGrid.allowSelectColumns(false);

  var aHeader = new Array();
  aHeader[0]  = 'Calendário';
  aHeader[1]  = 'Vacina';
  aHeader[2]  = 'Dose';
  aHeader[3]  = 'Período de Aplicação';
  aHeader[4]  = 'Aplicação';
  aHeader[5]  = 'Observação';
  oDBGrid.setHeader(aHeader);

  var aAligns = new Array();
  aAligns[0]  = 'center';
  aAligns[1]  = 'center';
  aAligns[2]  = 'center';
  aAligns[3]  = 'center';
  aAligns[4]  = 'center';
  aAligns[5]  = 'center';
  
  oDBGrid.setCellAlign(aAligns);
  oDBGrid.show($('grid_vacinas'));
  oDBGrid.clearAll(true);

  return oDBGrid;

}

function js_formataData(dData, lFormatoBanco) {
  
  if (dData == undefined || dData.length != 10) {
    return dData;
  }

  if (lFormatoBanco != true) {
    return dData.substr(8,2)+'/'+dData.substr(5,2)+'/'+dData.substr(0,4);
  }  else {
    return dData.substr(6,4)+'-'+dData.substr(3,2)+'-'+dData.substr(0,2);
  }


}

function js_renderizaGrid() {

  var aLinha    = new Array();
  var sDisabled = '';
  var sOnchange = '';
  for (iCont = 0; iCont < aDadosVacinas.length; iCont++) {
   
    
    if (aDadosVacinas[iCont].foraRede == 'false' || aDadosVacinas[iCont].passouinicio == 'false') {
      sDisabled = 'disabled';
    }
    
    if (aDadosVacinas[iCont].foraRede == 'true' && iUsuarioSessao != aDadosVacinas[iCont].vc16_i_usuario) {
      sOnchange = '  onchange="js_denyUsuario(\''+aDadosVacinas[iCont].login+'\')"'; 
    } else {
      sOnchange = '  onchange="js_setAlterado('+aDadosVacinas[iCont].vc07_i_codigo+')"';
    }

    if (aDadosVacinas[iCont].foraRede.urlDecode() == 'true') {
      sAltInc = 'alterar';
    } else {
      sAltInc = 'incluir';
    }

//alert(aDadosVacinas[iCont].vc16_i_usuario);

    aLinha[0]  = aDadosVacinas[iCont].vc05_c_descr.urlDecode();
    aLinha[1]  = aDadosVacinas[iCont].vc07_c_nome.urlDecode();
    aLinha[2]  = aDadosVacinas[iCont].vc03_c_descr.urlDecode();
    aLinha[3]  = aDadosVacinas[iCont].periodo.urlDecode();
    aLinha[4]  = '<input size="8" maxlength="10" type="text" name="dt'+aDadosVacinas[iCont].vc07_i_codigo;
    aLinha[4] += '" id="dt'+aDadosVacinas[iCont].vc07_i_codigo;
    aLinha[4] += '" onfocus="js_validaEntrada(this);" onkeyup="return js_mascaraData(this,event);"';
    aLinha[4] += '  onblur="js_validaDbData(this);" '+sOnchange; 
    aLinha[4] += '  value="'+js_formataData(aDadosVacinas[iCont].dataAplicacao)+'" '+sDisabled+'>'; 
    aLinha[4] += '<input type="hidden" name="dt_dia" id="dt'+aDadosVacinas[iCont].vc07_i_codigo+'_dia">'; 
    aLinha[4] += '<input type="hidden" name="dt_mes" id="dt'+aDadosVacinas[iCont].vc07_i_codigo+'_mes">'; 
    aLinha[4] += '<input type="hidden" name="dt_ano" id="dt'+aDadosVacinas[iCont].vc07_i_codigo+'_ano">'; 
    aLinha[5]  = '<input type="text" name="observacao" id="observacao'+aDadosVacinas[iCont].vc07_i_codigo;
    aLinha[5] += '" value="'+aDadosVacinas[iCont].obsAplicacao.urlDecode()+'" size="30" '+sDisabled+sOnchange; 
    aLinha[5] += '<input type="hidden" id="alterado'+aDadosVacinas[iCont].vc07_i_codigo+'">';
    aLinha[5] += '<input type="hidden" id="altOuInc'+aDadosVacinas[iCont].vc07_i_codigo+'" value="'+sAltInc+'">';
    aLinha[5] += '<input type="hidden" id="codAplica'+aDadosVacinas[iCont].vc07_i_codigo+'" ';
    aLinha[5] += '  value="'+aDadosVacinas[iCont].vc16_i_codigo+'">';
    aLinha[5] += '<input type="hidden" name="identificador" value="'+aDadosVacinas[iCont].vc07_i_codigo+'">';

    oDBGridVacinas.addRow(aLinha);
    sDisabled = '';

  }
  oDBGridVacinas.renderRows();

}

function js_setAlterado(iId) {

  document.getElementById('alterado'+iId).value = 'true';

}

function js_denyUsuario(sUsuario) {

  alert('Esta informação não será alterada pois foi lançada pelo usuário '+sUsuario+'.');

}

/* Bloco de funções do grid fim *****/

/* Vetor que vai receber os dados das vacinas do PHP */
var aDadosVacinas = new Array();

function js_getDadosVacinas() {

  oParam      = new Object();
  oParam.exec = 'getDadosVacinas';
  oParam.iCgs = <?=$iCgs?>;
  js_ajax(oParam, 'js_retornoGetDadosVacinas');

}

function js_retornoGetDadosVacinas(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");
 
  if (oRetorno.iStatus == 1) {

    aDadosVacinas = oRetorno.aDadosVacinas;
    js_renderizaGrid();

  } else {
    alert(oRetorno.sMessage.urlDecode());
  }

}

</script>