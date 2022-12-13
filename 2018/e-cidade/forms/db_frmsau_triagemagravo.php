<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
$oRotulo = new rotulocampo;
$oRotulo->label('s167_datasintoma');

$oCgsUnd  = new Cgs( $_GET['iCgsUnd'] );
$sSexoCgs = $oCgsUnd->getSexo();

$iTriagemAvulsa = $_GET['iTriagemAvulsa'];

if( !isset($s167_datasintoma) ) {

  $aDataAtual = explode('/', date('d/m/Y', db_getsession('DB_datausu')));
  $s167_datasintoma_dia = $aDataAtual[0];
  $s167_datasintoma_mes = $aDataAtual[1];
  $s167_datasintoma_ano = $aDataAtual[2];
}
?>

<form id="form1" name="form1">
  <fieldset>
    <legend>Agravo</legend>
    <table class="form-container">
      <tr>
        <td>
          Agravo:
        </td>
        <td>
          <input type="hidden" id="triagemAgravo" value="">
          <input type='hidden' id="triagemAvulsa" value=<?php echo $iTriagemAvulsa?>>
          <input type="hidden" id="codigoCid" value="">
          <input type="text" id="agravo" class="field-size9">
        </td>
      </tr>
      <tr>
        <td>Data do Primeiro Sintoma:</td>
        <td>
          <?php 
            db_inputdata('s167_datasintoma',$s167_datasintoma_dia,$s167_datasintoma_mes,$s167_datasintoma_ano,true,'text',1);
          ?>
        </td>
      </tr>
      <tr>
        <td>
          Gestante:
        </td>
        <td>
          <input type="hidden" id="sexoCgs" value= <?php echo $sSexoCgs;?> >
          <select id="gestante">
            <option value='f'>NÃO</option>
            <option value='t'>SIM</option>
          </select>
        </td>
      </tr>
    </table>
  </fieldset>
  <input type="button" id="salvar" value="Salvar" onclick="salvarAgravo();">
</form>
<script>

const MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA = 'saude.ambulatorial.db_frmsau_triagemagravo.';

var sRPC = "sau4_triagemagravo.RPC.php";

function buscarAgravo() {

  js_divCarregando( _M(MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + "buscando_agravo") , "msgB");
  var oParametros = {};
  oParametros.exec           = "buscarAgravo";
  oParametros.iTriagemAvulsa = $F('triagemAvulsa');

  var oRequisicao = {};
  oRequisicao.method = 'post';
  oRequisicao.parameters = 'json='+Object.toJSON(oParametros);
  oRequisicao.onComplete = retornoBuscarAgravo;

  new Ajax.Request( sRPC, oRequisicao);
}

function retornoBuscarAgravo( oAjax ) {

  js_removeObj("msgB");
  
  var oRetorno = JSON.parse( oAjax.responseText );

  if ( oRetorno.lTemAgravo == true ) {

    $('triagemAgravo').value    = oRetorno.iTriagemAgravo;
    $('codigoCid').value        = oRetorno.iCid;
    $('agravo').value           = oRetorno.sCid.urlDecode();
    $('s167_datasintoma').value = oRetorno.dtSintoma.urlDecode();
    $('gestante').value         = oRetorno.lGestante.urlDecode();
  }
}

buscarAgravo();

/**
 * Valida o sexo do CGS para bloquear ou habilitar campo Gestante
 */
function validaSexoCgs() {

  $('gestante').removeAttribute('disabled');

  if( $F('sexoCgs') == 'M' ) {

    $('gestante').value = 'f';
    $('gestante').disable(true);
  }

}

validaSexoCgs();

/**
 * Autocomplete do Tipo de Agravo
 */
$('agravo').onkeydown = '';
oAutoComplete = new dbAutoComplete($('agravo'),'sau4_autocompleteagravo.RPC.php');
oAutoComplete.setTxtFieldId(document.getElementById('agravo'));
oAutoComplete.setHeightList(300);
oAutoComplete.show();
oAutoComplete.setCallBackFunction(function(cod,label) {

    $('codigoCid').value = cod;
    $('agravo').value    = label;
});

/**
 * Verifica se os campos obrigatórios foram preenchidos
 * @return {boolean}
 */
function validarCampos() {

  if ( $F('codigoCid') == '' ) {

    alert( _M(MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + "informe_agravo") );
    return false;
  }

  if ( $F('s167_datasintoma') == '') {

    alert(_M(MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + "informe_data_primeiro_sintoma") );
    return false;
  }

  return true;
}

/**
 * Inclui o Agravo
 */
function salvarAgravo() {
  
  if ( !validarCampos() ) {
    return false;
  }
  
  js_divCarregando( _M(MENSAGENS_SAU4_FORMULARIO_TRIAGEMAVULSA + "salvando_agravo") , "msgA");

  var oParametros = {};
  oParametros.exec           = "salvarAgravo";
  oParametros.iTriagemAgravo = $F('triagemAgravo');
  oParametros.iCid           = $F('codigoCid');
  oParametros.iTriagemAvulsa = $F('triagemAvulsa');
  oParametros.dtSintoma      = $F('s167_datasintoma');
  oParametros.lGestante      = $F('gestante');

  var oRequisicao = {};
  oRequisicao.method = 'post';
  oRequisicao.parameters = 'json='+Object.toJSON(oParametros);
  oRequisicao.onComplete = js_retornoSalvarAgravo;

  new Ajax.Request( sRPC, oRequisicao);
}

/**
 * Função contendo o retorna da inclusão do agravo.
 */
function js_retornoSalvarAgravo( oAjax ) {

  js_removeObj("msgA");

  var oRetorno = eval('('+oAjax.responseText+')');
  $('triagemAgravo').value = oRetorno.iTriagemAgravo;

  alert(oRetorno.message);
  
}

</script>