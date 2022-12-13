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

//MODULO: Laboratório
$cllab_requisicao->rotulo->label();
$cllab_requiitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("la02_i_codigo");
$clrotulo->label("la02_c_descr");
$clrotulo->label("la08_i_codigo");
$clrotulo->label("la08_c_descr");
$clrotulo->label("la09_i_codigo");
$clrotulo->label("la09_i_exame");
$clrotulo->label("descrdepto");
$clrotulo->label("nome");
$clrotulo->label("z01_i_cgsund");
$clrotulo->label("z01_v_nome");
$clrotulo->label("z01_nome");
$clrotulo->label("la23_i_codigo");
$clrotulo->label("la38_i_medico");
$clrotulo->label("sd03_i_crm");
$clrotulo->label("la24_i_laboratorio");
?>
<form name="form1" method="post" action="">
  <div class="container">
    <fieldset>
      <legend>Requisição</legend>
      <fieldset style='border:none;'>
        <table class="form-container alinhaColuna">
          <tr>
            <td>
              <label for='la22_i_codigo'>
                <?php echo $Lla22_i_codigo; ?>
              </label>
            </td>
            <td>
              <?php db_input('la22_i_codigo',10,$Ila22_i_codigo,true,'text',3,"") ?>
              <label for="la22_d_data">
                <b>Data:</b>
              </label>
              <?php db_inputdata('la22_d_data',@$la22_d_data_dia,@$la22_d_data_mes,@$la22_d_data_ano,true,'text',3,"") ?>
            </td>
          </tr>
        </table>
      </fieldset>

      <fieldset class='separator'>
        <legend>Profissional</legend>
        <table class="form-container alinhaColuna">
          <tr>
            <td>
              <label for="la38_i_medico">
                <?php db_ancora($Lla38_i_medico,"js_pesquisala38_i_medico(true);",""); ?>
              </label>
            </td>
            <td>
              <?php
                db_input('la38_i_medico',10,$Ila38_i_medico,true,'text',""," onchange='js_pesquisala38_i_medico(false);'");
                db_input('z01_nome',58,$Ila22_c_medico,true,'text',1,'');

                if (db_permissaomenu(date('Y'), db_getsession('DB_modulo'), 8675) == 'true') {
              ?>
                    <input type="button" id="cadProf" title="Cadastro de Profissionais Fora da Rede"
                           name="cadProf" value="Cadastro de Profissionais" onclick="js_abreCadProf();">
              <?php
                }
              ?>
            </td>
          </tr>
        </table>
      </fieldset>


      <fieldset class='separator'>
        <legend>Paciente</legend>
        <table id="tabela1" class="form-container alinhaColuna" >
          <tr>
            <td>
              <label for="la22_i_cgs">
                <?php
                  $iOpcao = $db_opcao == 2 ? 3 : $db_opcao;
                  db_ancora($Lla22_i_cgs,"js_pesquisala22_i_cgs(true);",$iOpcao);
                ?>
              </label>
            </td>
            <td>
              <?php
                $iOpcao = $db_opcao == 2 ? 3 : $db_opcao;
                db_input('la22_i_cgs',10,$Ila22_i_cgs,true,'text',$iOpcao," onchange='js_pesquisala22_i_cgs(false);'");
                db_input('z01_v_nome',82,$Iz01_v_nome,true,'text',3,'');
                db_input('z01_v_sexo',50,"",true,'hidden',3,'');
              ?>
            </td>
          </tr>
          <tr style="display:<?php echo (@$z01_v_sexo == "F") ? "" : "none";?>" id="linha_dum">
            <td>
              <label for="la22_d_dum"><?php echo $Lla22_d_dum?></label>
            </td>
            <td>
              <?php db_inputdata('la22_d_dum',@$la22_d_dum_dia,@$la22_d_dum_mes,@$la22_d_dum_ano,true,'text',$db_opcao,"")?>
            </td>
          </tr>

          <tr>
            <td>
              <label for="la22_c_responsavel"><b>Responsavel:</b></label>
            </td>
            <td>
              <?php db_input('la22_c_responsavel',64,$Ila22_c_responsavel,true,'text',$db_opcao,'')?>
            </td>
          </tr>

          <tr>
            <td>
              <label for="la22_c_contato"><b>Contato:</b></label>
            </td>
            <td>
              <?php db_input('la22_c_contato',64,@$Ila22_c_contato,true,'text',$db_opcao,'')?>
            </td>
          </tr>
        </table>
      </fieldset>

      <fieldset class='separator'>
        <legend>Outras Informações</legend>
        <input type="button" id="medicamentos" name="medicamentos" Value="Medicamentos" onclick="js_lanca(1)">
        <input type="button" id="diagnostico" name="diagnostico" value="Diagnóstico" onclick="js_lanca(2)">
        <input type="button" id="obs" name="obs" value="Observação" onclick="js_lanca(3)">
      </fieldset>

      <fieldset>
        <legend>Exames</legend>
        <table class="form-container alinhaColuna">
          <tr>
            <td>
              <label for="la09_i_exame">
                <?php db_ancora($Lla09_i_exame,"js_pesquisala09_i_exame(true);",$db_opcao); ?>
              </label>
            </td>
            <td>
              <?php
                db_input('la09_i_exame',10,$Ila38_i_medico,true,'text',1, 'onchange="js_pesquisala09_i_exame(false);"' );
                db_input('la08_c_descr',55,$Ila22_c_medico,true,'text',1);
              ?>
            </td>
            <td>
              <?php
                echo "<label for='la21_i_quantidade'>$Lla21_i_quantidade </label>";
                $la21_i_quantidade = 1;
                db_input('la21_i_quantidade',2, $Ila21_i_quantidade,true,'text',1, "onchange='js_validarQuantidade(this);'");
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label for="la09_i_codigo"> <b><?php echo $Lla24_i_laboratorio; ?></b> </label>
            </td>
            <td>
              <?php
                $aOptions=array("0"=>"Selecione::");
                db_select( "la09_i_codigo", $aOptions, $Ila09_i_codigo, $db_opcao, "onchange=\"js_LoadSetorExame(this.value);\"");
              ?>
            </td>
            <td>
              <label for="la21_d_data">
                <a onclick="pegaPosMouse(event);js_calendario();" class="dbancora"
                   id="ancora_calend"
                   style="color: blue; text-decoration: underline; cursor: pointer;">
                  <b>Data:</b>
                </a>
              </label>
              <?php db_inputdata( 'la21_d_data',@$la21_d_data_dia,@$la21_d_data_mes,@$la21_d_data_ano,true,'text',$db_opcao," disabled ")?>
              <input type="button" Value="Lançar" name="lanc" id="lanc" onclick="js_IncluirExame();">
            </td>
          </tr>
        </table>

        <fieldset class='separator'>
          <legend>Exames Lançados</legend>
          <div id="GridExames" name="GridExames"></div>
          <select id='exameSelecionadoGrid' name="exames" style="display: none;"></select>
        </fieldset>

      </fieldset>

    </fieldset>

    <input name="confirma" type="submit" id="btnConfirma" disabled="disabled" value="Confirmar" onclick="return js_envia()">
    <input name="excluir" type="submit" id="db_opcao" value="Excluir" disabled  onclick="return atualizaCota()">
    <?php if (db_permissaomenu(date('Y'), db_getsession('DB_modulo'), 8344) == 'true') { ?>
         <input name="autorizar" type="button" id="autorizar" value="Autorizar" onclick="js_autorizaExames()" disabled >
    <?php } ?>
    <input name="conprov" type="button" id="comprov" value="Comprovante" onclick="js_comprovante();" disabled >
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa()">
    <input name="nova" type="button" id="nova" value="Nova Requisição" onclick="js_nova()">
    <?php
      db_input('la21_c_dia',         20, "", true, 'hidden', $db_opcao, '');
      db_input('la21_c_hora',        20, "", true, 'hidden', $db_opcao, '');
      db_input('la08_i_dias',        20, "", true, 'hidden', $db_opcao, '');
      db_input('la02_i_codigo',      20, "", true, 'hidden', $db_opcao, '');
      db_input('la02_c_descr',       20, "", true, 'hidden', $db_opcao, '');
      db_input('requisitos',         20, "", true, 'hidden', $db_opcao, '');
      db_input('la22_t_medicamento', 20, "", true, 'hidden', $db_opcao, '');
      db_input('la22_t_diagnostico', 20, "", true, 'hidden', $db_opcao, '');
      db_input('la22_t_observacao',  20, "", true, 'hidden', $db_opcao, '');
      db_input('sStr',               20, "", true, 'hidden', $db_opcao, '');
      db_input('sUrgente',           20, "", true, 'hidden', $db_opcao, '')
    ?>
  </div>
</form>
<script>

const MENSAGEM_FORMULARIO_REQUISICAO = "saude.laboratorio.db_frmlab_requisicao.";


/**
 * Variável informa se é utilizado uma forma de controlar os exames.
 *  0 => Não utiliza
 *  1 => Utiliza controle Financeiro
 *  2 => Utiliza controle Atendimento diário (Pacientes por Dia)
 * @type {Number}
 */
var iControlaSaldoExames = 0;


$('la22_i_codigo').addClassName('field-size2');
$('la22_d_data').addClassName('field-size2');
$('la38_i_medico').addClassName('field-size2');
$('la22_i_cgs').addClassName('field-size2');
$('la22_d_dum').addClassName('field-size2');
$('la09_i_exame').addClassName('field-size2');
$('la08_c_descr').addClassName('field-max');
$('la21_i_quantidade').addClassName('field-size1');
$('la21_d_data').addClassName('field-size2');
$('la09_i_codigo').setAttribute('rel', 'ignore-css');
$('la22_c_responsavel').style.width = '100%';
$('la22_c_contato').style.width     = '100%';
$('la09_i_codigo').style.width      = '100%';
$('GridExames').style               = 'width:750px;'

$('la08_c_descr').value     = '';
$('la08_c_descr').onkeydown = '';

//Autocomplete do Exame
oAutoComplete1 = new dbAutoComplete(document.form1.la08_c_descr,'lab4_agendar.RPC.php?tipo=2');
oAutoComplete1.setTxtFieldId(document.getElementById('la09_i_exame'));
oAutoComplete1.setHeightList(180);
oAutoComplete1.show();

oAutoComplete1.setCallBackFunction(function(id,label) {

  document.form1.la09_i_exame.value = id;
  document.form1.la08_c_descr.value = label;
  js_loadLaboratorios(id);
});

$('z01_nome').onkeydown = '';

//Autocomplete do profissional
oAutoComplete2 = new dbAutoComplete(document.form1.z01_nome,'lab4_agendar.RPC.php?tipo=1');
oAutoComplete2.setTxtFieldId(document.getElementById('la38_i_medico'));
oAutoComplete2.setHeightList(180);
oAutoComplete2.show();
oAutoComplete2.setCallBackFunction(function (id, label) {

  $('la38_i_medico').value = id;
  $('z01_nome').value      = label;
  js_desbloqueiaBotao();
})



//Inicializar Rotina
sRPC     = 'lab4_agendar.RPC.php';
lab_ajax = new ws_ajax('lab4_agendar.RPC.php');

var aHeader = ['Cod.', 'Laboratório', 'Exame', 'Quantidade', 'Coleta', 'Hora', 'Entrega', 'Opções','Urgente'];
var aWidth  = ['5%', '15%', '20%', '10%', '10%', '10%', '10%', '10%', '10%'];
var aAlign  = ['right', 'left', 'left', 'right', 'center', 'center', 'center', 'center', 'center'] ;

objGridExames              = new DBGrid('oGridExames');
objGridExames.nameInstance = 'objGridExames';
objGridExames.setCheckbox(0);
objGridExames.setCellWidth( aWidth );
objGridExames.setCellAlign( aAlign )
objGridExames.setHeader( aHeader );
objGridExames.setHeight(80);
objGridExames.show($('GridExames'));

F                                = document.form1;
F.dtjs_la21_d_data.style.display ='none';

<?php
  if ( $cllab_setorexame->numrows > 0 ) {
    echo" js_LoadSetorExame(F.la09_i_codigo.value); ";
  }
?>

//Seletor de tipo
function js_tipo( sexo ) {

  var table = document.getElementById('tabela1');
  status    = 'none';

  if ( sexo == 'F' ) {
    status = '';
  }

  for ( var r = 0; r < table.rows.length; r++ ) {

    var id = table.rows[r].id;

    if ( id == 'linha_dum' ) {
       table.rows[r].style.display = status;
    }
  }
}

function js_AtualizaGrid() {

  objGridExames.clearAll(true);
  tam = F.exames.length;

  for( x = 0 ; x < tam; x++ ) {

    sText = F.exames.options[x].text;
    avet  = sText.split('#');

    var sOpcaoDisabled = '';
    var sClassName     = "";
    var scheck         = (avet[6]==1)?' checked ':'';

    if ( !empty(scheck) ) {
      sClassName = 'error';
    }

    if( avet[8] && avet[8] != '1 - Nao Digitado' ) {

      sOpcaoDisabled = " disabled='disabled'";
      sClassName     = 'disabled';
    }

    alinha    = new Array();
    alinha[0] = avet[0]; //codigo Setor/Exame
    alinha[1] = avet[1]; //descr  laboratorio
    alinha[2] = avet[2]; //descr  exame
    alinha[3] = avet[7]; //quantidade
    alinha[4] = avet[3]; //data coleta
    alinha[5] = avet[4]; //hora coleta
    alinha[6] = avet[5]+' dias'; //data entrega

    var oInputExcluir  = '<input id="btn' + x + '" type="button" name="exc' + x + '" value="Excluir" ';
        oInputExcluir += sOpcaoDisabled + ' onclick="js_excluirExame('+F.exames.options[x].value+',\''+avet[2]+'\')">';
    alinha[7] = oInputExcluir;

    var oCheckUrgente  = '<input type="checkbox" id="urgente' + x + '" ' + scheck + sOpcaoDisabled;
        oCheckUrgente += ' onclick="js_marcaUrgenteLinhaGrid(' + x + ', this)">';
    alinha[8] = oCheckUrgente;

    objGridExames.addRow(alinha);
    objGridExames.aRows[x].addClassName(sClassName);

    if( sClassName == 'disabled' ) {
      objGridExames.aRows[x].aCells[0].content = "<input type='checkbox' id='chkoGridExames" + avet[0] + "' disabled='disabled'>";
    }
  }

  objGridExames.renderRows();
}

function js_marcaUrgenteLinhaGrid( iLinha, oElemento ) {

  var sIdLinhaGrid = objGridExames.sName + 'row'+objGridExames.sName+''+iLinha;

  $(sIdLinhaGrid).removeClassName('error');

  if (oElemento.checked) {
    $(sIdLinhaGrid).addClassName('error');
  }
}

function js_DadosExames() {

   aVet              = new Array();
   aVet[aVet.length] = F.la09_i_codigo.value;
   aVet[aVet.length] = F.la09_i_codigo.options[F.la09_i_codigo.selectedIndex].text;
   aVet[aVet.length] = F.la08_c_descr.value;
   aVet[aVet.length] = F.la21_d_data.value;
   aVet[aVet.length] = F.la21_c_hora.value;
   aVet[aVet.length] = F.la08_i_dias.value;
   aVet[aVet.length] = '0';
   aVet[aVet.length] = F.la21_i_quantidade.value;

   return sStr = aVet.join('#');
}

function js_IncluirExame() {

  if ( F.la09_i_codigo.value != '' ) {

    if ( F.la21_d_data.value != '' ) {


      <?php if ($oConfig->la49_i_exameduplo == 2) { ?>
        if (js_verificaexame()) {
      <? } ?>

      sStr = js_DadosExames();
      F.exames.add(new Option(sStr,F.exames.length),null);

      js_AtualizaGrid();

      $('la09_i_exame').value = '';
      $('la08_c_descr').value = '';
//      $('la21_d_data').value  = '';

      F.la08_c_descr.select();
      F.la21_i_quantidade.value = 1;

      <?php if ($oConfig->la49_i_exameduplo == 2) { ?>
        }
      <?php } ?>

    }else{
      alert( _M(MENSAGEM_FORMULARIO_REQUISICAO + "informe_data") );
    }
  }else{

    alert( _M(MENSAGEM_FORMULARIO_REQUISICAO + "informe_exame") );
  }

}

function js_verificaexame() {

  tam = F.exames.length;

  if ( tam == 0 ) {
    return true;
  }

  for ( x = 0; x < tam; x++ ) {

    sStr = F.exames.options[x].text;
    aVet = sStr.split('#');

    if ( aVet[0] == F.la09_i_codigo.value ) {

      alert( _M(MENSAGEM_FORMULARIO_REQUISICAO + "exame_ja_lancado") );
      return false;
    }
  }
  return true;
}

function js_excluirExame( id_linha,exame ) {

  if ( confirm( _M(MENSAGEM_FORMULARIO_REQUISICAO + "deseja_apagar_exame", {"sExame" : exame}) ) ) {

     F.exames.remove(id_linha);
     js_AtualizaGrid();
  }
}
//fim funções do Grid

//outras
function js_loadLaboratorios( exame ) {

  if ( ( exame != '' ) && ( exame > 0 ) ) {

    //Requisição ajax normal
    var oParam      = new Object();
    oParam.exec     = 'LoadLaboratorio';
    oParam.exame    = exame;
    js_ajax( oParam, 'js_loadLaboratoriosReturn' );

  } else {
    return false;
  }
}

function js_loadLaboratoriosReturn( oAjax ) {

  oRetorno = lab_ajax.monta(oAjax);


  if ( oRetorno.status == 1 ) {

    Tam = F.la09_i_codigo.length;

    for ( x = Tam; x > 0; x-- ) {
      F.la09_i_codigo.remove(x-1);
    }

    for ( x = 0; x < oRetorno.codigos.length; x++ ) {
      F.la09_i_codigo.add( new Option(oRetorno.laboratorios[x].urlDecode(),oRetorno.codigos[x]),null);
    }

    js_LoadSetorExame(F.la09_i_codigo.value);

  }else{

    Tam = F.la09_i_codigo.length;

    for ( x = Tam; x > 0; x--) {
      F.la09_i_codigo.remove(x-1);
    }

    message_ajax(oRetorno.message);
  }
}

function js_LoadSetorExame( cod ) {

  if ( (cod != '' ) && ( cod > 0) ) {

    //Requisição ajax normal
    var oParam           = new Object();
    oParam.exec          = 'DadosExame';
    oParam.la09_i_codigo = cod;
    js_ajax( oParam, 'js_retornoDadosExame' );

  } else {
    alert( _M(MENSAGEM_FORMULARIO_REQUISICAO + "exame_nao_informado_setor") );
  }
}

function js_retornoDadosExame( oAjax ) {

  oRetorno = lab_ajax.monta(oAjax);

  if ( oRetorno.status == 1 ) {

    F.la08_i_dias.value   = oRetorno.dias;
    F.la02_i_codigo.value = oRetorno.iLaboratorio;
    F.la02_c_descr.value  = oRetorno.sLaboratorio.urlDecode();
    F.requisitos.value    = oRetorno.sRequisitos;
  }else{
    message_ajax(oRetorno.message);
  }
}

function js_calendario() {

  if ( $F(la21_i_quantidade) != '' && parseInt($F(la21_i_quantidade))  > 0 ) {
    show_calendariolaboratorio('la21_d_data', 'parent.js_HoraExame(); ', $F(la09_i_codigo), $F(la21_i_quantidade) );
  } else {
    alert( _M(MENSAGEM_FORMULARIO_REQUISICAO + "quantidade_invalida") );
  }
}

function js_HoraExame(){
  //retorno da função do calendario
}


function js_desbloqueiaBotao() {

  $('btnConfirma').setAttribute('disabled',  'disabled');
  if ( $F('la22_i_cgs') != '' && $F('la38_i_medico') != '' ) {
    $('btnConfirma').removeAttribute('disabled');
  }
}

function js_validaCampos() {

  if ($F('la22_i_cgs') == '') {

    alert( _M(MENSAGEM_FORMULARIO_REQUISICAO + "informe_cgs") );
    return false;
  }


  if ( $F('la38_i_medico') == '' ) {

    alert( _M(MENSAGEM_FORMULARIO_REQUISICAO + "informe_medico") );
    return false;
  }
  return true;
}

function js_envia() {

  if (!js_validaCampos() ){
    return false;
  }


  sStr     = '';
  sUrgente = '';
  sSep     = '';
  tam      = F.exames.length;

  if ( tam == 0 ) {

    alert( _M(MENSAGEM_FORMULARIO_REQUISICAO + "lance_exame") );
    return false;
  }

  aSetores = new Array();
  aDatas   = new Array();

  for ( x = 0; x < tam; x++ ) {

    sStr       += encodeURI(sSep+F.exames.options[x].text);
    mTmp        = F.exames.options[x].text.split('#');
    aSetores[x] = mTmp[0];
    aDatas[x]   = mTmp[3];

    if ( document.getElementById('urgente' + x).checked == true ) {
      sUrgente += sSep + '1';
    } else {
      sUrgente += sSep + '0';
    }
    sSep = '##';
  }

  // 1 => Utiliza controle Financeiro
  if ( iControlaSaldoExames == 1 ) {
    // valida o saldo do controle financeiro
    // Envio o setorexame e a data do exame no formato brasileiro para a função
    if ( !js_verificarSaldo(aSetores, aDatas) ) {
      return false;
    }
  }

  // 2 => Utiliza controle Atendimento diário (Pacientes por Dia)
  if ( iControlaSaldoExames == 2 ) {

    if ( !validarLimiteDiario(aSetores, aDatas) ) {
      return false;
    }
  }

  F.sStr.value     = sStr;
  F.sUrgente.value = sUrgente;
  return true;
}

function js_lanca( iQual ) {

  if ( iQual == 1 ) {

    sNome  = 'MEDICAMENTO';
    sTexto = F.la22_t_medicamento.value;
    sCampo = 'la22_t_medicamento';
  } else if ( iQual == 2 ) {

    sNome  = 'DIAGNÓSTICO';
    sTexto = F.la22_t_diagnostico.value;
    sCampo = 'la22_t_diagnostico';
  } else {

    sNome  = 'OBSERVAÇÃO';
    sTexto = F.la22_t_observacao.value;
    sCampo = 'la22_t_observacao';
  }

  iTop  = ( screen.availHeight - 600 ) / 2;
  iLeft = ( screen.availWidth - 600 ) / 2;   
  js_OpenJanelaIframe("",
                      "db_iframe_lab_box","lab4_box001.php?sNome="+sNome+"&sTexto="+sTexto+"&sCampo="+sCampo+"",
                      "Pesquisa",
                      true,
                      iTop,
                      iLeft,
                      600,
                      200);
}

function js_nova() {
  location.href = 'lab4_agendar001.php';
}

function js_comprovante() {

  var iExames             = $('exameSelecionadoGrid').options.length;
  var aExamesSelecionados = [];

  for ( var i = 0; i < iExames; i++ ) {
    aExamesSelecionados.push( $('exameSelecionadoGrid').options[i].text.split('#')[0] );
  }

  if ( aExamesSelecionados.length == 0 ) {

    alert( _M(MENSAGEM_FORMULARIO_REQUISICAO + "lance_exame") );
    return false;
  }

  if ( $F('la22_i_codigo') == '' ) {

    alert( _M(MENSAGEM_FORMULARIO_REQUISICAO + "requisicao_sem_codigo") );
    return false;
  }

  var sUrl  = 'lab2_comprovante001.php?sListaExames='+aExamesSelecionados;
      sUrl += '&la22_i_codigo='+$F('la22_i_codigo');

  jan = window.open(sUrl, '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

//Lookup's
function js_pesquisala22_i_cgs( mostra ) {

  if ( mostra == true) {

    js_OpenJanelaIframe('',
                        'db_iframe_cgs_und',
                        'func_cgs_und.php?funcao_js=parent.js_mostracgs_und1|z01_i_cgsund|z01_v_nome|z01_v_sexo',
                        'Pesquise o Paciente',
                        true);
  } else {

    if ( document.form1.la22_i_cgs.value != '' ) {

       js_OpenJanelaIframe('',
                           'db_iframe_cgs_und',
                           'func_cgs_und.php?pesquisa_chave='+document.form1.la22_i_cgs.value+'&funcao_js=parent.js_mostracgs_und',
                           'Pesquise o Paciente',
                           false);
    } else {
      document.form1.z01_v_nome.value = '';
      js_desbloqueiaBotao();
    }
  }
}

function js_mostracgs_und( chave, erro, sexo ) {

  document.form1.z01_v_nome.value = chave;

  if ( erro == true ) {

    document.form1.la22_i_cgs.focus();
    document.form1.la22_i_cgs.value = '';
  } else {
    js_tipo(sexo);
  }
  js_desbloqueiaBotao();
}

function js_mostracgs_und1( chave1, chave2, sexo ) {

  document.form1.la22_i_cgs.value = chave1;
  document.form1.z01_v_nome.value = chave2;
  js_tipo(sexo);
  db_iframe_cgs_und.hide();
  js_desbloqueiaBotao();
}

function js_pesquisala09_i_exame ( mostra ) {

  if (mostra == true) {

    js_OpenJanelaIframe('',
                        'db_iframe_lab_exame',
                        'func_lab_exame.php?funcao_js=parent.js_mostralab_exame1|la08_i_codigo|la08_c_descr&iVinculo=0'+
                        '&iAtivo=1',
                        'Pesquisa exames',
                        true);
  } else {

     if ( $F('la09_i_exame') != '') {

        js_OpenJanelaIframe('',
                            'db_iframe_lab_exame',
                            'func_lab_exame.php?pesquisa_chave='+document.form1.la09_i_exame.value+
                            '&funcao_js=parent.js_mostralab_exame&iVinculo=0&iAtivo=1',
                            'Pesquisa',
                            false);
     } else {

       $('la08_c_descr').value = '';
       $('la09_i_exame').value = '';
       js_desbloqueiaBotao();
     }
  }
}

function js_mostralab_exame ( chave, erro ) {

  document.form1.la08_c_descr.value = chave;

  if (erro == true) {

    document.form1.la09_i_exame.focus();
    document.form1.la09_i_exame.value = '';
  } else {
    js_loadLaboratorios(document.form1.la09_i_exame.value);
  }
}

function js_mostralab_exame1 ( chave1, chave2 ) {

  document.form1.la09_i_exame.value = chave1;
  document.form1.la08_c_descr.value = chave2;
  js_loadLaboratorios(chave1);
  db_iframe_lab_exame.hide();
}

function js_pesquisala38_i_medico ( mostra ) {

  if (mostra==true) {

  js_OpenJanelaIframe('',
                      'db_iframe_medicos',
                      'func_medicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome'+
                      '&lTodosTiposProf=true',
                      'Pesquise o Profissional',
                      true);
  } else {

    if ( document.form1.la38_i_medico.value != '' ) {

      js_OpenJanelaIframe('',
                          'db_iframe_medicos',
                          'func_medicos.php?pesquisa_chave='+document.form1.la38_i_medico.value+
                          '&funcao_js=parent.js_mostramedicos&lTodosTiposProf=true',
                          'Pesquise o Profissional',
                          false);
     } else {
        document.form1.z01_nome.value = '';
        js_desbloqueiaBotao();
     }
  }
}

function js_mostramedicos( chave, erro, chave2 ) {

  document.form1.z01_nome.value = chave;

  if ( erro == true ) {

    document.form1.la38_i_medico.focus();
    document.form1.la38_i_medico.value = '';
  }
  js_desbloqueiaBotao();
}

function js_mostramedicos1( chave1, chave2 ) {

  document.form1.la38_i_medico.value = chave1;
  document.form1.z01_nome.value      = chave2;
  db_iframe_medicos.hide();
  js_desbloqueiaBotao();
}

<?php
  if ( isset( $alinhasgrid ) ) {

    if ( count($alinhasgrid) > 0 ) {

      for ( $x = 0; $x < count( $alinhasgrid ); $x++ ) {
        echo" F.exames.add(new Option('".$alinhasgrid[$x]."',F.exames.length),null);";
      }

      echo"js_AtualizaGrid(); ";
      echo"F.la21_d_data.value='';";
      echo"F.comprov.disabled=false;";
      echo"F.excluir.disabled=false;";
      echo"F.confirma.value='Alterar';";

      if ( db_permissaomenu(date('Y'), db_getsession('DB_modulo'), 8344) == 'true') {
        echo"F.autorizar.disabled=false;";
      }
    }
  }
?>


function js_pesquisa() {

  js_OpenJanelaIframe('',
                      'db_iframe_lab_requisicao',
                      'func_lab_requisicao.php?iDepResitante=<?=$departamento?>&'+
                      'funcao_js=parent.js_preenchepesquisa|la22_i_codigo','Pesquisa',true);
}

function js_preenchepesquisa( chave ) {

  db_iframe_lab_requisicao.hide();
  location.href = 'lab4_agendar001.php?chavepesquisa='+chave;
}

function js_ajax( oParam, jsRetorno, sUrl, lAsync ) {

  var mRetornoAjax;

  if ( sUrl == undefined ) {
    sUrl = 'lab4_agendar.RPC.php';
  }

  if ( lAsync == undefined ) {
    lAsync = false;
  }

  var oAjax = new Ajax.Request(sUrl,
                               {
                                 method:       'post',
                                 asynchronous: lAsync,
                                 parameters:   'json='+Object.toJSON(oParam),
                                 onComplete:   function(oAjax) {

                                                 var evlJS           = jsRetorno+'(oAjax);';
                                                 return mRetornoAjax = eval(evlJS);
                                               }
                              }
                             );
  return mRetornoAjax;
}

function js_preencheMedicoRecemCadastrado( iCod, sNome ) {

  $('la38_i_medico').value = iCod;
  js_pesquisala38_i_medico(false);
}

function js_abreCadProf() {

  iTop  = (screen.availHeight - 650) / 2;
  iLeft = (screen.availWidth - 800) / 2;

  if ( $F('la38_i_medico') == '' ) {

    sGet = 's154_c_nome='+$F('z01_nome')+'&sd03_i_tipo=2&lBotao=true';

    js_OpenJanelaIframe('', "db_iframe_cadprof", "sau1_sau_medicosforarede001.php?"+sGet,
                        'Cadastro de Profissionais Fora da Rede', true,iTop, iLeft, 800, 300
                       );
  } else {

    var oParam     = new Object();
    oParam.exec    = 'verificaForaRede';
    oParam.iMedico = $F('la38_i_medico');

    if ( js_ajax(oParam, 'js_retornoVerificaForaRede', 'sau4_ambulatorial.RPC.php', false) ) {

      sGet = 'chavepesquisa='+$F('fa04_i_profissional')+'&lBotao=true';

      js_OpenJanelaIframe('', "db_iframe_cadprof", "sau1_sau_medicosforarede002.php?"+sGet,
                          'Cadastro de Profissionais Fora da Rede', true,iTop, iLeft, 800, 300
                         );
    } else {
      alert( _M(MENSAGEM_FORMULARIO_REQUISICAO + "profissional_fora_rede") );
    }
  }
}

function js_verificarSaldo( aSetores, aDatas ) {

  var oParam         = new Object();
  oParam.exec        = 'verificarSaldoExame';
  oParam.iSetorExame = aSetores;
  oParam.dData       = aDatas;

  return js_ajax(oParam, 'js_retornoVerificarSaldo', 'lab4_laboratorio.RPC.php');
}

function js_retornoVerificarSaldo( oRetorno ) {

  var oRetorno = eval("("+oRetorno.responseText+")");

  if ( oRetorno.iStatus == 1 ) {

    if ( oRetorno.lSaldoSuficiente == true ) {
      return true;
    } else { // Saldo insuficiente

      if ( oRetorno.lLiberarSemSaldo == true ) {

        var lLiberar = confirm ( _M(MENSAGEM_FORMULARIO_REQUISICAO + "saldo_excedido")  );

        if ( lLiberar ) {
          return true;
        } else {
          return false;
        }
      }

      alert(oRetorno.sMessage.urlDecode());
      return false;
    }
  } else {

    alert(oRetorno.sMessage.urlDecode());
    return false;
  }
}

function js_autorizaExames() {

  var aSelecionados     = [];
  var aSelecionadosGrid = objGridExames.getSelection("array");

  if ( aSelecionadosGrid.length == 0 ) {

    alert( _M(MENSAGEM_FORMULARIO_REQUISICAO + "selecione_exame") );
    return;
  }

  var aExames = [];
  aSelecionadosGrid.each( function ( aItemSelecionado ) {

    aExames.push({sDataColeta: aItemSelecionado[5], iExame : aItemSelecionado[0]});
    aSelecionados.push( aItemSelecionado[0] );
  });


  var oParametros            = {};
  oParametros.exec           = "autorizaExames";
  oParametros.iRequisicao    = $F('la22_i_codigo');
  oParametros.aCodigosExames = aSelecionados;
  oParametros.aExames        = aExames;

  var oRequest          = {};
  oRequest.asynchronous = false;
  oRequest.method       = 'post';
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.onComplete   = js_retornoAutorizaExames;

  js_divCarregando( _M(MENSAGEM_FORMULARIO_REQUISICAO + "autorizando_exames"), "msgBoxA" );
  new Ajax.Request('lab4_autorizacao.RPC.php', oRequest);
}

function js_retornoAutorizaExames( oAjax ) {

  js_removeObj('msgBoxA');
  var oRetorno = eval( "(" + oAjax.responseText + ")" );

  alert( oRetorno.sMensagem.urlDecode() );

  if ( parseInt(oRetorno.iStatus) == 1 ) {
    location.href = "lab4_agendar001.php?chavepesquisa="+$F('la22_i_codigo');
  }
}

function js_validarQuantidade (oElement) {

  if (oElement.value == '' || parseInt(oElement.value) <= 0) {

    oElement.value             = '';
    $('la21_d_data').value     = '';
    $('la21_d_data_dia').value = '';
    $('la21_d_data_mes').value = '';
    $('la21_d_data_ano').value = '';
  }
}


(function () {

  new AjaxRequest('lab4_cotasatendimento.RPC.php', {exec: 'verificaUsoCotas'}, function(oRetorno, lErro) {

    if(lErro){
      alert(oRetorno.sMessage);
      return;
    }
    iControlaSaldoExames = oRetorno.tipo;
  }).setMessage(_M( MENSAGEM_FORMULARIO_REQUISICAO + 'verifica_uso_cotas' )).execute();

})()

/**
 * Controla o limete de atendimentos por dia por paciente
 * @param  {array} aSetorExame       Codigo do setorexame
 * @param  {array} aDatasAgendamento Data dos agendamentos de cada exame
 * @return {boolean}
 */
function validarLimiteDiario(aSetorExame, aDatasAgendamento) {

  var iExames          = aSetorExame.length;
  var aExamesAgendados = [];
  for ( var i = 0; i < iExames; i++ ) {

    aExamesAgendados.push( {
        iSetorExame : aSetorExame[i],
        dataAgenda  : aDatasAgendamento[i]
      });
  }

  var oParametros = {
    'exec'             : 'alteraCotas',
    'aExamesAgendados' : aExamesAgendados,
    'iCgs'             : $F('la22_i_cgs'),
    'lAdicionar'       : true
  }

  var lPermiteRequisicao = false;
  new AjaxRequest('lab4_cotasatendimento.RPC.php', oParametros, function(oRetorno, lErro) {

    if (lErro) {

      alert(oRetorno.sMessage);
      return lPermiteRequisicao = !lErro;
    }

    lPermiteRequisicao = true;

    /**
     * Valida se atingiu a cota
     */
    if ( oRetorno.lAtingiuCotaDiaria ) {

      var sMensagemConfirme = oRetorno.sMessage;
      sMensagemConfirme    += "\nDeseja liberar essa requisição?";
      if ( !confirm(sMensagemConfirme) ) {
        return lPermiteRequisicao = false;
      }

      /**
       * Caso tenha atingido a cota e confirmado inclusão,
       * força a inclusão de uma requisição incrementando assim as cotas
       */
      oParametros.exec = 'forcarInclusaoCota';
      new AjaxRequest('lab4_cotasatendimento.RPC.php', oParametros, function(oRetornoForca, lErroForca) {

        if ( lErroForca ) {

          alert(oRetornoForca.sMessage);
          return lPermiteRequisicao = !lErroForca;
        }

        return lPermiteRequisicao = true;
      }).asynchronous(false).execute();
    }

    return lPermiteRequisicao;
  }).asynchronous(false).setMessage(_M( MENSAGEM_FORMULARIO_REQUISICAO + 'validando_cota' )).execute();

  return lPermiteRequisicao;
}

function atualizaCota() {

  if ( iControlaSaldoExames != 2 ) {
    return true;
  }

  var aExamesAgendados  = [];
  var iQuantidadeExames = F.exames.length;
  for ( x = 0; x < iQuantidadeExames; x++ ) {

    var aDadosExame = F.exames.options[x].text.split('#');
    aExamesAgendados.push({
      iSetorExame : aDadosExame[0],
      dataAgenda  : aDadosExame[3]
    })
  }

  var oParametros = {
    'exec'             : 'alteraCotas',
    'aExamesAgendados' : aExamesAgendados,
    'iCgs'             : $F('la22_i_cgs'),
    'lAdicionar'       : false
  }

  var lPermiteRequisicao = false;
  new AjaxRequest('lab4_cotasatendimento.RPC.php', oParametros, function(oRetorno, lErro) {

    if (lErro) {

      alert(oRetorno.sMessage);
      return lPermiteRequisicao = !lErro;
    }

    return lPermiteRequisicao = true
  }).asynchronous(false).setMessage(_M( MENSAGEM_FORMULARIO_REQUISICAO + 'validando_cota' )).execute();

  return lPermiteRequisicao;
}

</script>
