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

/**
 *
 * @author I
 * @revision $Author: dbeduardo.sirangelo $
 * @version $Revision: 1.4 $
 */
$oRotulo = new rotulocampo;
$oRotulo->label("s113_i_numcgs");
$oRotulo->label("z01_v_nome");
$oRotulo->label("s113_i_codigo");
$oRotulo->label("s113_c_encaminhamento");
$oRotulo->label("s133_c_observacoes");
$oRotulo->label("s133_c_protocolo");
$oRotulo->label("s113_c_hora");
$oRotulo->label("s110_i_codigo");
$oRotulo->label("z01_nome");
?>
<form name='frmConfirmaExame' method="post">
  <table>
    <tr>
      <td>
        <fieldset>
          <legend><b>Confirma Exame do paciente</b></legend>
          <table >
            <tr>
              <td>
                 <b>CGS:</b>
              </td>
                <td colspan='1'>
                <?
                 db_input('z01_i_cgsund',10,$Is113_i_numcgs,true,'text',3," onchange='js_pesquisasd32_i_numcgs(false);'");
                 echo "</td><td colspan=6>";
                 db_input('z01_v_nome',60,$Iz01_v_nome,true,'text',3,'');
                ?>
                </td>
            </tr>
            <tr>
              <td colspan='2'>
                <b>Data do Exame:</b>
              </td>
              <td>
                <?
                 db_inputdata('s113_d_exame',null,null,null,true,3);
                ?>
              </td>
              <td>
                <b>Nasc.:</b>
              </td>
              <td>
              <?
               db_inputdata('z01_d_nasc',null,null,null,true,3);
              ?>
              </td>
              <td>
                <b>CPF:</b>
              </td>
              <td align="left">
              <?
               db_input('z01_v_cgccpf',20,null,true,3);
              ?>
              </td>
            </tr>
            <tr>
              <td colspan='8'>
                <fieldset>
                   <legend>
                     <b>
                       Protocolar o Exame
                     </b>
                   </legend>
                   <table border=0>
                     <tr>
                       <td>
                          <b>Exame:</b>
                       </td>
                       <td colspan="5">
                          <?
                           db_input('s113_i_codigo',10,$Is113_i_codigo,true,3);
                           db_input('s108_c_exame',45,'',true,3);
                          ?>
                       </td>
                     </tr>
                     <tr>
                       <td>
                          <b>Prestadora:</b>
                       </td>
                       <td colspan="5">
                          <?
                           db_input('s110_i_codigo',10,$Is113_i_codigo,true,3);
                           db_input('z01_nome',45,$Iz01_nome,true,3);
                          ?>
                       </td>
                     </tr>
                     <tr>
                       <td>
                          <b>Encaminhamento:</b>
                       </td>
                       <td>
                          <?
                          db_input("s113_c_encaminhamento",10,$Is113_c_encaminhamento,true,$db_opcao);
                          ?>
                       </td>
                       <td>
                          <b>Dia:</b>
                       </td>
                       <td>
                         <?
                          db_inputdata('s113_d_exame',null,null,null,true,3);
                         ?>
                       </td>
                       <td>
                          <b>Hora:</b>
                       </td>
                       <td>
                        <?
                         db_input("s113_c_hora",10,$Is113_c_hora,true,3);
                        ?>
                       </td>
                     </tr>
                     <tr>
                       <td>
                         <b>Protocolo Nro.:</b>
                       </td>
                       <td>
                        <?
                         db_input("s133_c_protocolo",10,$Is133_c_protocolo,true,"text",1);
                         db_input("s133_i_codigo",10,$Is133_c_protocolo,true,"hidden",3);
                        ?>
                       </td>
                     </tr>
                     <tr>
                       <td>
                         <?
                          db_ancora("<b>Resultados do Exame</b>","js_lancaAtributos()",1,null, "preencheexame");
                         ?>
                       </td>
                       <td colspan="6">
                         <?
                          db_input("valoresatributos",50,null,true,"text",3);
                         ?>
                       </td>
                     </tr>
                     <tr>
                       <td>
                         <b>Observações:</b>
                       </td>
                       <td colspan='6'>
                         <?
                          db_textarea("s133_c_observacoes", 4, 56, $Is133_c_observacoes,true,"text", 1);
                         ?>
                       </td>
                     </tr>
                     <tr>
                       <td colspan=7 style='text-align: center'>
                         <input type='button' value='Confirma'  id='confirmar' onclick="js_salvarExame()">
                         <input type='button' value='Emitir Resultado'   id='EmitirResultado' onclick="js_emiteResultado()">
                         <input type='button' value='Pesquisar' id='Cancelar' onclick='js_pesquisa()'>
                       </td>
                     </tr>
                   </table>
                </fieldset>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
  </table>
  <table width='70%'>
  <tr>
              <td width='100%'colspan='7'>
                <fieldset>
                   <legend>
                     <b>Exames Agendados</b>
                   </legend>
                   <div id='gridExames'>
                   </div>
                </fieldset>
              </td>
            </tr>
 </table>
</form>

<script>

sURL = 'sau4_agendaexamesRPC.php';
function js_init() {

    oGridExames              = new DBGrid('oGridExames');
    oGridExames.nameInstance = 'oGridExames';
    oGridExames.setHeader(new Array("Cod.exame","Protocolo",
                                    "Exame","Descrição","Prestadora","Descricao","Dia",
                                     "Hora","Editar"));
    oGridExames.aHeaders[0].lDisplayed = false;
    oGridExames.show($('gridExames'));
}

function js_pesquisa() {

  js_OpenJanelaIframe('',
                      'lkp_exames',
                      'func_confirmaexames.php?'+
                      'funcao_js=parent.js_consultaexames|z01_i_cgsund','Pesquisa de Exames',
                      true
                     );
}

function js_consultaexames(iCGS) {

  lkp_exames.hide();
  var dtExame  = IFlkp_exames.document.getElementById('s113_d_exame').value;
  var oParam   = new Object();
  oParam.exec  = "getExames";
  oParam.iData = dtExame;
  oParam.iCGS  = iCGS;
  js_divCarregando('Aguarde, Pesquisando', 'msgbox');
  var oAjax = new Ajax.Request(
                         sURL,
                         {
                          method    : 'post',
                          parameters: 'json='+Object.toJSON(oParam),
                          onComplete: js_retornoConsultaExames
                         }
                        );
}

function js_retornoConsultaExames(oAjax) {

  js_removeObj('msgbox');
  oGridExames.clearAll(true);
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {


     if (oRetorno.itens.length > 0) {

       oRetorno.itens.each(function (oExame, iIteracao) {

          var aLinha = new Array();
          aLinha[0]  = oExame.s113_i_codigo,
          aLinha[1]  = oExame.s133_c_protocolo.urlDecode(),
          aLinha[2]  = oExame.sd63_i_codigo,
          aLinha[3]  = oExame.sd63_c_nome.urlDecode(),
          aLinha[4]  = oExame.s110_i_codigo,
          aLinha[5]  = oExame.z01_nome.urlDecode(),
          aLinha[6]  = js_formatar(oExame.s113_d_exame,'d'),
          aLinha[7]  = oExame.s113_c_hora.urlDecode(),
          aLinha[8]  = "<input type='button' value='Editar'  onclick='js_buscaExame("+oExame.s113_i_codigo+")'>";
          sDisabled  = "";
          if (oExame.s133_i_codigo == "") {
            sDisabled  = " disabled ";
          }
          aLinha[8] += "<input type='button' "+sDisabled+" value='Excluir' onclick='js_excluirExame("+oExame.s133_i_codigo+","+oExame.z01_i_cgsund+")'>";
          oGridExames.addRow(aLinha);
         });
        oGridExames.renderRows();
     }
  } else {
    alert(oRetorno.message.urlDecode());
  }

}

function js_buscaExame(iCodigoExame) {

  var oParam          = new Object();
  oParam.exec         = "getExames";
  oParam.iCodigoExame = iCodigoExame;
  js_divCarregando('Aguarde, Pesquisando dados do exame', 'msgbox');
  var oAjax = new Ajax.Request(
                         sURL,
                         {
                          method    : 'post',
                          parameters: 'json='+Object.toJSON(oParam),
                          onComplete: js_retornoConsultaExamesUnico
                         }
                        );
}

function js_retornoConsultaExamesUnico(oAjax) {

  js_removeObj('msgbox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

    var aInputs = $$('input,textarea');
    aInputs.each(function(input, i) {

       var valor   = eval("oRetorno.itens."+input.id);
       if (valor    != null && valor != 'undefined') {
         input.value = valor.urlDecode();
       }
     });
  }
}


function js_lancaAtributos() {

  js_OpenJanelaIframe('',
                      'lkp_atributosexames',
                      'sau4_lancavaloresatributos.php?iCodigoExame='+$F('s113_i_codigo')+
                      '&funcao_js=parent.js_consultaexames|z01_i_cgsund&iCodigoConfirmaExame='+$F('s133_i_codigo'),
                      'Resultados Exames',
                      true,
                      100,
                      100,
                      600,
                      500
                     );

}

function js_salvarExame() {

  if ($F('s133_c_protocolo') == "") {

    alert('Informe o número do protocolo.');
    $('s133_c_protocolo').focus();
    return false;

  }
  js_divCarregando('Aguarde, salvando dados do exame', 'msgbox');
  var oParam             = new Object();
  oParam.exec            = "saveExame";
  oParam.iExame          = $F('s113_i_codigo');
  oParam.sObservacao     = $F('s133_c_observacoes');
  oParam.iProtocolo      = $F('s133_c_protocolo');
  oParam.sResultadoExame = $F('valoresatributos');
  var oAjax = new Ajax.Request(
                         sURL,
                         {
                          method    : 'post',
                          parameters: 'json='+Object.toJSON(oParam),
                          onComplete: js_retornoSave
                         }
                        );
}

function js_retornoSave(oAjax) {

  js_removeObj('msgbox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

    alert('Exame salvo com sucesso');
    js_consultaexames($F('z01_i_cgsund'));
    var aInputs = $$('input');
    aInputs.each(function(input, i) {
      if (input.type == 'text' || input.type == 'hidden') {
        input.value = "";
      }
    });
  } else {
    alert(oRetorno.message.urlDecode());
  }
}

function js_excluirExame(iExame, iCGS) {

  sMsg = "Confirma a exclusão da confirmação do exame?";
  if (!confirm(sMsg)) {
    return false;
  }
  js_divCarregando('Aguarde, excluindo confirmação do exame', 'msgbox');
  var oParam             = new Object();
  oParam.exec            = "excluirExame";
  oParam.iConfirmaExame  = iExame;
  oParam.iCGS            = iCGS;
  var oAjax = new Ajax.Request(
                         sURL,
                         {
                          method    : 'post',
                          parameters: 'json='+Object.toJSON(oParam),
                          onComplete: js_retornoExcluir
                         }
                        );
}

function js_retornoExcluir(oAjax) {

 js_removeObj('msgbox');
 var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

    alert('Confirmacao cancelada com sucesso');
    js_consultaexames(oRetorno.iCGS);
    var aInputs = $$('input');
    aInputs.each(function(input, i) {
      if (input.type == 'text' || input.type == 'hidden') {
        input.value = "";
      }
    });
  } else {
    alert(oRetorno.message.urlDecode());
  }
}

function js_emiteResultado() {

  if ($F('s133_i_codigo') == "" ) {

     alert('Selecione um exame que ja foi confirmado.');
     return false;

  }

  var query = 'iCodigoExame='+$F('s133_i_codigo');
  var jan   = window.open('sau2_resultadoexames002.php?'+query,'',
                         'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   jan.moveTo(0,0);

}
</script>