<?
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

//MODULO: educação
$oDaoEscolaProc->rotulo->label();

?>
<form class="container" name="form1" id="form1" method="post" action="">
  <fieldset>
    <legend>Cadastro de Escolas de Procedência de Alunos</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=$Ted82_c_nome?>">
          <label for="ed82_c_nome"> <?=$Led82_c_nome?> </label>
        </td>
        <td>
          <?php db_input('ed82_i_codigo',20,$Ied82_i_codigo,true,'hidden',3,"")?>
          <?php db_input('ed82_c_nome',50,$Ied82_c_nome,true,'text',$oGet->db_opcao)?>
          <label for="ed82_c_abrev"> <?=$Led82_c_abrev?> </label>
          <?php db_input('ed82_c_abrev',20,$Ied82_c_abrev,true,'text',$oGet->db_opcao)?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Ted82_pais?>">
          <label for="paises"><?=$Led82_pais?> </label>
        </td>
        <td>
          <select id="paises" onchange = 'js_validaPais(this);'>
          </select>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Ted82_c_mantenedora?>">
          <label for="ed82_c_mantenedora"><?=$Led82_c_mantenedora?> </label>
        </td>
        <td>
          <?php
            $x = array('1'=>'MUNICIPAL','2'=>'ESTADUAL','3'=>'FEDERAL','4'=>'PARTICULAR');
            db_select('ed82_c_mantenedora', $x, true, $oGet->db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <label for="ed82_c_email"><?=$Led82_c_email?></label>
        </td>
        <td>
          <?php
            db_input('ed82_c_email', 80, $Ied82_c_email, true,'text', $oGet->db_opcao," onKeyUp=\"js_ValidaCamposEdu(this,4,'$GLOBALS[Sed82_c_email]','f','t',event);\"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Ted82_i_cep?>">
          <label for="ed82_i_cep"><?=$Led82_i_cep?></label>
        </td>
        <td>
          <?php
            db_input('ed82_i_cep',8,$Ied82_i_cep,true,'text',$oGet->db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Ted82_i_numero?>">
          <label for="ed82_c_rua"><?=$Led82_c_rua?></label>
        </td>
        <td>
          <?php db_input('ed82_c_rua',50,$Ied82_c_rua,true,'text',$oGet->db_opcao,"")?>
          <label for="ed82_i_numero" ><?=$Led82_i_numero?></label>
          <?php db_input('ed82_i_numero',10,$Ied82_i_numero,true,'text',$oGet->db_opcao,"")?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Ted82_c_complemento?>">
          <label for="ed82_c_complemento"><?=$Led82_c_complemento?></label>
        </td>
        <td>
          <?db_input('ed82_c_complemento',20,$Ied82_c_complemento,true,'text',$oGet->db_opcao,"")?>
          <label for="ed82_c_bairro"><?=$Led82_c_bairro?></label>
          <?db_input('ed82_c_bairro',50,$Ied82_c_bairro,true,'text',$oGet->db_opcao,"")?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Ted82_i_censouf?>">
          <label for="estado"><?=$Led82_i_censouf?></label>
        </td>
        <td>
          <select id='estado' onchange= "js_buscaMunicipios(this.value);">
            <option value ="" selected="selected"> Selecione Estado</option>
          </select>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Ted82_i_censomunic?>">
          <label for="municipio"><?=$Led82_i_censomunic?></label>
        </td>
        <td>
          <select id='municipio' onchange= "js_buscaDistritos(this.value);">
            <option value ="" selected="selected"> Selecione Municipio</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>
          <label for="distrito"><?=$Led82_i_censodistrito?></label>
        </td>
        <td>
          <select id='distrito'>
            <option value ="" selected="selected"> Selecione Distrito</option>
          </select>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?=($oGet->db_opcao==1?"incluir":($oGet->db_opcao==2||$oGet->db_opcao==22?"alterar":"excluir"))?>"
         type="button" id="processar"
         value="<?=($oGet->db_opcao==1?"Incluir":($oGet->db_opcao==2||$oGet->db_opcao==22?"Alterar":"Excluir"))?>"
         <?=($db_botao==false?"disabled":"")?> >
  <input type="button" name="novo" id="novaEscola" value="Nova Escola" onclick="js_novaEscolaProcedencia()" />
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
  </form>
<script>

var oGet = js_urlToObject();

function js_novaEscolaProcedencia() {

  var sParametros = '?db_opcao=1';

  if (oGet.lOrigemTransferencia) {
    sParametros += '&lOrigemTransferencia=true';
  }

  location.href = 'edu1_escolaproc001.php' + sParametros;
}

function js_validaPais(oElement) {

  $('estado').value                    = "";
  $('municipio').value                 = "";
  $('distrito').value                  = "";
  $('estado').disabled                 = false;
  $('municipio').disabled              = false;
  $('distrito').disabled               = false;
  $('estado').style.backgroundColor    = '#FFFFFF';
  $('municipio').style.backgroundColor = '#FFFFFF';
  $('distrito').style.backgroundColor  = '#FFFFFF';
  if (oElement.value != 10) {

    $('estado').value                    = "";
    $('municipio').value                 = "";
    $('distrito').value                  = "";
    $('estado').disabled                 = true;
    $('municipio').disabled              = true;
    $('distrito').disabled               = true;
    $('estado').style.backgroundColor    = '#DEB887';
    $('municipio').style.backgroundColor = '#DEB887';
    $('distrito').style.backgroundColor  = '#DEB887';
  }
}

(function () {

  switch (parseInt(oGet.db_opcao)) {

    case 1:
      $('pesquisar').disabled  = true;
      $('novaEscola').disabled = true;
      break;
    case 2:
      $('pesquisar').disabled = false;
      js_pesquisa();
      break;

    case 3:

      $('pesquisar').disabled = false;
      js_pesquisa();
      $('estado').disabled                 = true;
      $('municipio').disabled              = true;
      $('distrito').disabled               = true;
      $('paises').disabled                 = true;
      $('estado').style.backgroundColor    = '#DEB887';
      $('municipio').style.backgroundColor = '#DEB887';
      $('distrito').style.backgroundColor  = '#DEB887';
      $('paises').style.backgroundColor    = '#DEB887';
      break;
  }

  var oParametro  = {};
  oParametro.exec = 'getPais';

  var oRequest          = {};
  oRequest.method       = 'post';
  oRequest.asynchronous = false;
  oRequest.parameters   = 'json='+Object.toJSON(oParametro);
  oRequest.onComplete   = function (oAjax) {

    js_removeObj("msgBoxD");
    var oRetorno = eval ("(" + oAjax.responseText + ")");

    oRetorno.aPaises.each( function(oPais) {
      $("paises").add(new Option(oPais.sPais.urlDecode(), oPais.iCodigo));
    });

    $("paises").value = 10;

    js_buscaEstados();
  }

  js_divCarregando("Aguarde, carregando países...", "msgBoxD");
  new Ajax.Request("edu4_escolaprocedencia.RPC.php", oRequest);

})();

function js_buscaEstados() {

  var oParametro  = {};
  oParametro.exec = 'getEstados';

  var oRequest = {};
  oRequest.method       = 'post';
  oRequest.asynchronous = false;
  oRequest.parameters   = 'json='+Object.toJSON(oParametro);
  oRequest.onComplete   = function (oAjax) {

    js_removeObj("msgBoxC");
    var oRetorno = eval ("(" + oAjax.responseText + ")");

    oRetorno.aEstados.each( function(oEstado) {
      $("estado").add(new Option(oEstado.sEstado.urlDecode(), oEstado.iCodigo));
    });
  }

  js_divCarregando("Aguarde, carregando estados...", "msgBoxC");
  new Ajax.Request("edu4_escolaprocedencia.RPC.php", oRequest);
}

function js_buscaMunicipios(iEstado) {

  $('municipio').options.length = 0;
  $('distrito').options.length = 0;
  $('municipio').add( new Option("Selecione Município", "") );
  $('distrito').add( new Option("Selecione Distrito", "") );

  if (iEstado == '') {
    return false;
  }

  var oParametro     = {};
  oParametro.exec    = 'getMunicipios';
  oParametro.iEstado = iEstado;

  var oRequest          = {};
  oRequest.method       = 'post';
  oRequest.asynchronous = false;
  oRequest.parameters   = 'json='+Object.toJSON(oParametro);
  oRequest.onComplete   = function (oAjax) {

    js_removeObj("msgBoxA");
    var oRetorno = eval ("(" + oAjax.responseText + ")");

    oRetorno.aMunicipios.each( function(oMunicipio) {
      $("municipio").add(new Option(oMunicipio.sMunicipio.urlDecode(), oMunicipio.iCodigo));
    });
  }

  js_divCarregando("Aguarde, carregando municípios...", "msgBoxA");
  new Ajax.Request("edu4_escolaprocedencia.RPC.php", oRequest);

}

function js_buscaDistritos(iMunicipio) {

  $('distrito').options.length = 0;
  $('distrito').add( new Option("Selecione Distrito", "") );

  if (iMunicipio == '') {
    return false;
  }

  var oParametro        = {};
  oParametro.exec       = 'getDistritos';
  oParametro.iMunicipio = iMunicipio;
  oParametro.iEstado    = $F('estado');

  var oRequest          = {};
  oRequest.method       = 'post';
  oRequest.asynchronous = false;
  oRequest.parameters   = 'json='+Object.toJSON(oParametro);
  oRequest.onComplete   = function (oAjax) {

    js_removeObj("msgBoxB");
    var oRetorno = eval ("(" + oAjax.responseText + ")");

    oRetorno.aDistritos.each( function(oDistrito) {
      $("distrito").add(new Option(oDistrito.sDistrito.urlDecode(), oDistrito.iCodigo));
    });
  }

  js_divCarregando("Aguarde, carregando distritos...", "msgBoxB");
  new Ajax.Request("edu4_escolaprocedencia.RPC.php", oRequest);
}


function js_cep(abre) {

  if (abre == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cep','func_cep.php?funcao_js=parent.js_preenchecep|cep|cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro','Pesquisa de Ruas',true);
  } else {
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cep','func_cep.php?pesquisa_chave='+document.form1.ed82_i_cep.value+'&funcao_js=parent.js_preenchecep|cep|cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro','Pesquisa',false);
  }
}

function js_pesquisa() {

  var sUrl = "func_escolaproc.php?funcao_js=parent.js_mostrapesquisa|ed82_i_codigo";
  js_OpenJanelaIframe('', 'db_iframe_escolaproc', sUrl, 'Pesquisa escola de procedência', true);

}

function js_mostrapesquisa () {

  js_buscaEscolaProcedencia(arguments[0]);
  db_iframe_escolaproc.hide();
}


function js_buscaEscolaProcedencia(iEscolaProcedencia) {

  var oParametro  = {};
  oParametro.exec = 'getEscolaProcedencia';

  oParametro.iEscolaProcedencia = iEscolaProcedencia;

  var oRequest        = {};
  oRequest.method     = 'post';
  oRequest.parameters = 'json='+Object.toJSON(oParametro);
  oRequest.onComplete = function (oAjax) {

    js_removeObj("msgBoxF");
    var oRetorno = eval ("(" + oAjax.responseText + ")");

    if (parseInt(oRetorno.status) == 2) {

      alert(oRetorno.message.urlDecode());
      return false;
    }
    $("ed82_i_codigo").value      = oRetorno.oEscolaProc.iCodigo;
    $("ed82_c_nome").value        = oRetorno.oEscolaProc.sNome.urlDecode();
    $("ed82_c_abrev").value       = oRetorno.oEscolaProc.sAbreviatura.urlDecode();
    $('ed82_c_email').value       = oRetorno.oEscolaProc.sEmail.urlDecode();
    $('ed82_c_rua').value         = oRetorno.oEscolaProc.sRua.urlDecode();
    $('ed82_c_complemento').value = oRetorno.oEscolaProc.sComplemento.urlDecode();
    $('ed82_c_bairro').value      = oRetorno.oEscolaProc.sBairro.urlDecode();
    $('ed82_c_mantenedora').value = oRetorno.oEscolaProc.sMantenedora;
    $('ed82_i_numero').value      = oRetorno.oEscolaProc.iNumero;
    $('ed82_i_cep').value         = oRetorno.oEscolaProc.iCep;
    $('paises').value             = oRetorno.oEscolaProc.iPais;

    if (parseInt(oGet.db_opcao) != 3) {
      js_validaPais($('paises'));
    }

    js_buscaEstados();
    $('estado').value = oRetorno.oEscolaProc.iEstado;
    js_buscaMunicipios(oRetorno.oEscolaProc.iEstado);
    $("municipio").value = oRetorno.oEscolaProc.iMunicipio;
    js_buscaDistritos(oRetorno.oEscolaProc.iMunicipio);
    $('distrito').value  = oRetorno.oEscolaProc.iDistrito;

    $('processar').removeAttribute("disabled");
  }

  js_divCarregando("Aguarde, buscando dados da escola...", "msgBoxF");
  new Ajax.Request("edu4_escolaprocedencia.RPC.php", oRequest);

}

$("processar").observe("click",function (){


  if (oGet.db_opcao == 3) {
    js_excluir();
    return;
  }

  if ( !js_valida() ) {
    return false;
  }

  js_salvar();

});

function js_excluir() {

  var oParametro  = {};
  oParametro.exec = 'excluir';

  oParametro.iEscolaProcedencia = $F("ed82_i_codigo");

  var oRequest          = {};
  oRequest.method       = 'post';
  oRequest.asynchronous = false;
  oRequest.parameters   = 'json='+Object.toJSON(oParametro);
  oRequest.onComplete   = function (oAjax) {

    js_removeObj("msgBoxG");
    var oRetorno = eval ("(" + oAjax.responseText + ")");
    alert(oRetorno.message.urlDecode());

    if (parseInt(oRetorno.status) == 2) {
      return;
    }

    location.href = "edu1_escolaproc003.php?db_opcao=3";
  }
  js_divCarregando("Aguarde, excluindo escola de procedência...", "msgBoxG");
  new Ajax.Request("edu4_escolaprocedencia.RPC.php", oRequest);
}

function js_validaDados() {

  if ($("ed82_c_nome").value.trim() == "") {

    alert("Informe o nome da escola de procedência.");
    return false;
  }

  if ($F("paises") == 10 && $F("estado") == "") {

    alert("Selecione um estado.");
    return false;
  }

  return true;
}

function js_salvar() {


  if ( !js_validaDados() ) {
    return false;
  }

  var oParametro  = {};
  oParametro.exec = 'salvar';

  oParametro.iEscolaProcedencia = $F("ed82_i_codigo");
  oParametro.sNome              = encodeURIComponent(tagString($F("ed82_c_nome")));
  oParametro.sAbreviatura       = encodeURIComponent(tagString($F("ed82_c_abrev")));
  oParametro.iMantenedora       = $F("ed82_c_mantenedora");
  oParametro.sEmail             = encodeURIComponent(tagString($F("ed82_c_email")));
  oParametro.sRua               = encodeURIComponent(tagString($F("ed82_c_rua")));
  oParametro.iNumero            = $F("ed82_i_numero");
  oParametro.sComplemento       = encodeURIComponent(tagString($F("ed82_c_complemento")));
  oParametro.sBairro            = encodeURIComponent(tagString($F("ed82_c_bairro")));
  oParametro.iCep               = $F("ed82_i_cep");
  oParametro.iEstado            = $F("estado");
  oParametro.iMunicipio         = $F("municipio");
  oParametro.iDistrito          = $F("distrito");
  oParametro.iPais              = $F("paises");

  var oRequest          = {};
  oRequest.method       = 'post';
  oRequest.asynchronous = false;
  oRequest.parameters   = 'json='+Object.toJSON(oParametro);
  oRequest.onComplete   = function (oAjax) {

    js_removeObj("msgBoxH");
    var oRetorno = eval ("(" + oAjax.responseText + ")");

    alert(oRetorno.message.urlDecode());

    if (parseInt(oRetorno.status) == 2) {
      return;
    }


    if (oGet.lOrigemTransferencia) {

      parent.db_iframe_escolaprocedencia.hide();
      return;
    }
    location.href = "edu1_escolaproc002.php?db_opcao=2";

  }

  js_divCarregando("Aguarde, salvando escola de procedência...", "msgBoxH");
  new Ajax.Request("edu4_escolaprocedencia.RPC.php", oRequest);
}

function js_preenchecep(chave,chave1,chave2,chave3,chave4) {

 document.form1.ed82_i_cep.value        = chave;
 document.form1.ed82_c_rua.value        = chave1;
 document.form1.ed82_i_censomunic.value = chave2;
 document.form1.ed82_i_censouf.value    = chave3;
 document.form1.ed82_c_bairro.value     = chave4;
 db_iframe_cep.hide();
}

function js_valida() {

    Vemail = "<?=$GLOBALS['Sed82_c_email']?>";

  if (jsValidaEmail(document.form1.ed82_c_email.value,Vemail) == false) {
    return false;
  }
  return true;
}


$("ed82_i_codigo").addClassName("field-size2");
$("ed82_c_nome").addClassName("field-size8");
$("ed82_c_rua").addClassName("field-size8");
$("ed82_i_cep").addClassName("field-size2");
$("ed82_i_codigo").addClassName("field-size2");
$("ed82_i_codigo").addClassName("field-size2");
$("ed82_i_codigo").addClassName("field-size2");
$("ed82_i_codigo").addClassName("field-size2");

</script>