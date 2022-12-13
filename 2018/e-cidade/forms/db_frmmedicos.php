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

//MODULO: saude
$oDaoMedicos->rotulo->label();
$oDaoCgm->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label('z01_nome');
$oRotulo->label('s154_c_cns');
$oRotulo->label('s154_c_nome');
$oRotulo->label('s154_i_codigo');
$oRotulo->label('z01_nomecomple');
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr style="display: none;">
    <td nowrap title="<?=@$Tsd03_i_codigo?>">
      <?=$Lsd03_i_tipo?>
    </td>
    <td>
      <?
      $aX = array('1'=>'DA REDE', '2'=>'FORA DA REDE');
      db_select('sd03_i_tipo', $aX, true, $db_opcao, 'onchange="js_tipo();"');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd03_i_codigo?>">
      <?=@$Lsd03_i_codigo?>
    </td>
    <td>
      <?
      db_input('sd03_i_codigo', 10, $Isd03_i_codigo, true, 'text', 3, "");
      db_input('s154_i_codigo', 10, $Is154_i_codigo, true, 'hidden', 3, "");

      // Utilizadao para manter a variável $lBotao, quando aberto em um iframe
      db_input('lBotao', 10, '', true, 'hidden', 3, "");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=$Tsd03_i_crm?>">
      <?=$Lsd03_i_crm?>
    </td>
    <td>
      <?
      db_input('sd03_i_crm', 10, $Isd03_i_crm, true, 'text', $db_opcao, '');
      ?>
    </td>
  </tr>
  <tr name="linhaForaRede">
    <td nowrap title="<?=$Ts154_c_cns?>">
      <?=$Ls154_c_cns?>
    </td>
    <td>
      <?
      db_input('s154_c_cns', 15, $Is154_c_cns, true, 'text', $db_opcao, '');
      ?>
    </td>
  </tr>
  <tr name="linhaForaRede">
    <td nowrap title="<?=$Ts154_c_nome?>">
      <?=$Ls154_c_nome?>
    </td>
    <td>
      <?
      if (isset($s154_c_nome)) {

        $aOrig = array('á', 'é', 'í', 'ó', 'ú', 'â', 'ê', 'ô', 'ã', 'õ', 'à', 'è', 'ì', 'ò', 'ù', 'ç');
        $aDest = array('Á', 'É', 'Í', 'Ó', 'Ú', 'Â', 'Ê', 'Ô', 'Ã', 'Õ', 'À', 'È', 'Ì', 'Ò', 'Ù', 'Ç');
        $s154_c_nome = str_replace($aOrig, $aDest, strtoupper($s154_c_nome));

      }
      db_input('s154_c_nome', 50, $Is154_c_nome, true, 'text', $db_opcao, '');
      ?>
    </td>
  </tr>
  <tr name="linhaRede">
    <td nowrap title="<?=$Tsd03_i_cgm?>">
      <?
      db_ancora($Lsd03_i_cgm, "js_pesquisasd03_i_cgm(true);", $db_opcao);
      ?>
    </td>
    <td>
      <?
      db_input('sd03_i_cgm', 10, $Isd03_i_cgm, true, 'text', $db_opcao, " onchange='js_pesquisasd03_i_cgm(false);'");
      ?>
      <?
      db_input('z01_nome', 40, $Iz01_nome, true, 'text', $db_opcao, '');
      ?>
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>"
   type="submit" id="db_opcao"
   value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
   <?=($db_botao == false ? "disabled" : "")?>
   <?=($db_opcao == 3 ? '' : 'onclick="return js_validaEnvio();"')?>>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
<?
if (isset($lBotao) && $lBotao == 'true') {
?>
  <input name="fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_cadprof.hide();">
<?
}
?>
</form>

<fieldset style="width:70%"><legend><b>Dados Pessoais</b></legend>
  <table width="100%" border="0" cellspacing="0" cellpading="0" width="100%">
    <tr>
      <td nowrap width="20%" title="<?=@$Tz01_cgccpf?>">
        <?=@$Lz01_cgccpf?>
      </td>
      <td width="35%">
        <?
        db_input('z01_cgccpf', 15, @$Iz01_cgccpf, true, 'text', 3, "");
        ?>
      </td>
      <td width="20%" align="right">
        <?=@$Lz01_ident?>
      </td>
      <td width="20%">
        <?
        db_input('z01_ident', 15, $Iz01_ident, true, 'text', 3);
        ?>
      </td>
    </tr>
    <tr>
      <td title='<?=$Tz01_numcgm?>' nowrap>
        <?=$Lz01_numcgm?>
      </td>
      <td nowrap>
        <?
        db_input('z01_numcgm', 10, $Iz01_numcgm, true, 'text', 3);
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tz01_nome?>">
        <?=@$Lz01_nome?>
      </td>
      <td nowrap title="<?=@$Tz01_nome?>">
        <?
        $z01_nome2 = @$z01_nome;
        db_input('z01_nome2', 40, $Iz01_nome, true, 'text', 3, "");
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tz01_nomecomple?>">
        <?=@$Lz01_nomecomple?>
      </td>
      <td nowrap title="<?=@$Tz01_nomecomple?>">
        <?
        db_input('z01_nomecomple', 40, $Iz01_nomecomple, true, 'text', 3, "");
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tz01_pai?>">
        <?=@$Lz01_pai?>
      </td>
      <td nowrap title="<?=@$Tz01_pai?>">
        <?
        db_input('z01_pai', 40, $Iz01_pai, true, 'text', 3, "");
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tz01_mae?>">
        <?=@$Lz01_mae?>
      </td>
      <td nowrap title="<?=@$Tz01_mae?>">
        <?
        db_input('z01_mae', 40, $Iz01_mae, true, 'text', 3, "");
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Tz01_nasc?>">
        <?=$Lz01_nasc?>
      </td>
      <td nowrap title="<?=$Tz01_nasc?>">
        <?
        db_inputdata('z01_nasc', @$z01_nasc_dia, @$z01_nasc_mes, @$z01_nasc_ano, true, 'text', 3);
        ?>
      </td>
      <td nowrap align="right" title="<?=$Tz01_estciv?>">
        <?=$Lz01_estciv?>
      </td>
      <td>
        <?
        db_input('z01_estciv', 10, '', true, 'text', 3, '');
        ?>
      </td>
    </tr>
    <tr>
      <td>
        <?=$Lz01_sexo?>
      </td>
      <td>
        <?
        $sex = array("M"=>"Masculino", "F"=>"Feminino");
        db_select('z01_sexo', $sex, true, 3);
        ?>
      </td>
    </tr>
</table>
</fieldset>&nbsp;&nbsp;&nbsp;
<fieldset style="width:70%"><legend><b>Endereço</b></legend>
  <table width="100%" border="0" cellspacing="0" cellpading="0" width="100%">
    <tr>
      <td nowrap title="<?=@$Tz01_ender?>">
        <?=@$Lz01_ender?>
      </td>
      <td nowrap>
        <?
        db_input('z01_ender', 40, $Iz01_ender, true, 'text', 3);
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap width="20%" title="<?=@$Tz01_numero?>">
        <?=@$Lz01_numero?>
      </td>
      </td>
      <td nowrap width="35%">
        <?
        db_input('z01_numero', 8, $Iz01_numero, true, 'text', 3);
        ?>
      </td>
      <td align="right" width="20%">
        <?=@$Lz01_compl?>
      </td>
      <td width="20%">
        <?
        db_input('z01_compl', 10, $Iz01_compl, true, 'text', 3);
        ?>
     </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tz01_munic?>">
        <?=@$Lz01_munic?>
      </td>
      <td nowrap>
        <?
        db_input('z01_munic', 20, $Iz01_munic, true, 'text', 3);
        ?>
      </td>
      <td align="right">
       <?
       echo "<b>UF:</b>";
       ?>
      </td>
      <td>
        <?
        db_input('z01_uf', 2, $Iz01_uf, true, 'text', 3);
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tz01_bairro?>">
        <?=@$Lz01_bairro?>
      </td>
      <td nowrap>
        <?
        db_input('z01_bairro', 25, $Iz01_bairro, true, 'text', 3);
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap  title="<?=@$Tz01_cep?>">
        <?=@$Lz01_cep?>
      </td>
      <td nowrap>
        <?
        db_input('z01_cep', 9, $Iz01_cep, true, 'text', 3);
        ?>
      </td>
      <td nowrap align="right" title="<?=@$Tz01_cxpostal?>">
        <?=@$Lz01_cxpostal?>
      </td>
      <td>
        <?
        db_input('z01_cxpostal', 10, $Iz01_cxpostal, true, 'text', 3);
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tz01_telef?>">
        <?=@$Lz01_telef?>
      </td>
      <td nowrap>
        <?
        db_input('z01_telef', 12, $Iz01_telef, true, 'text', 3);
        ?>
      </td>
      <td nowrap align="right" title="<?=@$Tz01_telcel?>">
        <?=@$Lz01_telcel?>
      </td>
      <td>
        <?
        db_input('z01_telcel', 12, $Iz01_telcel, true, 'text', 3);
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tz01_email?>">
        <?=@$Lz01_email?>
      </td>
      <td nowrap>
        <?
        db_input('z01_email', 30, $Iz01_email, true, 'text', 3);
        ?>
      </td>
    </tr>
    <tr>
      <td>
        <b>Cadastrado Em:</b>
      </td>
      <td>
        <?
        db_inputdata('z01_cadast', @$z01_cadast_dia, @$z01_cadast_mes, @$z01_cadast_ano, true, 'text', 3);
        ?>
      </td>
      <td nowrap align="right">
        <?=@$Lz01_ultalt?>
      </td>
      <td>
        <?
        db_inputdata('z01_ultalt', @$z01_ultalt_dia, @$z01_ultalt_mes, @$z01_ultalt_ano, true, 'text', 3);
        ?>
      </td>
    </tr>
  </table>
</fieldset>
</form>

<script>
document.form1.z01_nome.style.width = '300px';

oAutoComplete = new dbAutoComplete(document.form1.z01_nome, 'sau4_pesquisanome.RPC.php');
oAutoComplete.setTxtFieldId(document.getElementById('sd03_i_cgm'));
oAutoComplete.setHeightList(180);
oAutoComplete.show();
oAutoComplete.setCallBackFunction(function(id, label) {

                                     document.form1.sd03_i_cgm.value = id;
                                     document.form1.z01_nome.value   = label;
                                     js_getInfoCgm();

                                   });

js_tipo();
js_getInfoCgm();
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


function js_tipo() {

  var oLinhasRede     = document.getElementsByName('linhaRede');
  var oLinhasForaRede = document.getElementsByName('linhaForaRede');

  if (document.getElementById('sd03_i_tipo').value == 1) {

    var iTam    = oLinhasForaRede.length;
    // Oculto todas as linhas com informações de médicos fora da rede
    for (var iCont = 0; iCont < iTam; iCont++) {

      oLinhasForaRede[iCont].style.display = 'none';

    }

    iTam    = oLinhasRede.length;
    // Torno visíveis as linhas de médicos da rede
    for (var iCont = 0; iCont < iTam; iCont++) {

      oLinhasRede[iCont].style.display = '';

    }

  } else { // Médicos fora da rede

    var iTam    = oLinhasRede.length;
    // Oculto todas as linhas com informações de médicos da rede
    for (var iCont = 0; iCont < iTam; iCont++) {

      oLinhasRede[iCont].style.display = 'none';

    }

    iTam    = oLinhasForaRede.length;
    // Torno visíveis as linhas de médicos fora da rede
    for (var iCont = 0; iCont < iTam; iCont++) {

      oLinhasForaRede[iCont].style.display = '';

    }

    js_limpaInfoCgm();

  }

}

function js_validaEnvio() {

  if ($F('sd03_i_tipo') == 1) { // Médico da rede

    if ($F('sd03_i_cgm') == '') {

      alert('Selecione um CGM.');
      return false;

    }

  } else { // Médico fora da rede

    if ($F('s154_c_nome') == '') {

      alert('Digite o nome do médico.');
      return false;

    }

    if ($F('s154_c_cns').length != 0 && $F('s154_c_cns').length != 15) {

      alert('O CNS deve possuir 15 dígitos.');
      return false;

    }

  }

  return true;

}

function js_pesquisasd03_i_cgm(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'func_nome', 'func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome',
                        'Pesquisa', true
                       );

  } else {

    if (document.form1.sd03_i_cgm.value != '') {

     js_OpenJanelaIframe('', 'func_nome', 'func_cgm.php?pesquisa_chave='+
                         document.form1.sd03_i_cgm.value+'&funcao_js=parent.js_mostracgm',
                         'Pesquisa', false
                        );

    } else {
      document.form1.z01_nome.value = '';
    }

  }

}
function js_mostracgm(erro, chave) {

  document.form1.z01_nome.value = chave;
  if (erro == true) {

    document.form1.sd03_i_cgm.focus();
    document.form1.sd03_i_cgm.value = '';

   } else {
     js_getInfoCgm();
   }

}
function js_mostracgm1(chave1, chave2) {

  document.form1.sd03_i_cgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  func_nome.hide();
  js_getInfoCgm();

}
function js_pesquisa() {

  js_OpenJanelaIframe('', 'db_iframe_medicos', 'func_medicos.php?funcao_js='+
                      'parent.js_preenchepesquisa|sd03_i_codigo',
                      'Pesquisa', true
                     );

}
function js_preenchepesquisa(chave) {

  db_iframe_medicos.hide();
  <?
  if ($db_opcao!=1) {
   echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>

}

function js_formataData(dData) {

  if (dData == undefined || dData.length != 10) {
    return dData;
  }
  return dData.substr(8, 2)+'/'+dData.substr(5, 2)+'/'+dData.substr(0, 4);

}

function js_getInfoCgm() {

  var oParam  = new Object();
	oParam.exec = 'getInfoCgm';
	oParam.iCgm = $F('sd03_i_cgm');

  if ($F('sd03_i_cgm') != '') {
    js_ajax(oParam, 'js_retornoGetInfoCgm');
  }

}

function js_retornoGetInfoCgm(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  dNasc    = js_formataData(oRetorno.z01_nasc);
  dCadast  = js_formataData(oRetorno.z01_cadast);
  dUltAlt  = js_formataData(oRetorno.z01_ultalt);

  $('z01_cgccpf').value            = oRetorno.z01_cgccpf.urlDecode();
  $('z01_ident').value             = oRetorno.z01_ident.urlDecode();
  $('z01_numcgm').value            = oRetorno.z01_numcgm.urlDecode();
  $('z01_nome2').value             = oRetorno.z01_nome.urlDecode();
  $('z01_nomecomple').value        = oRetorno.z01_nomecomple.urlDecode();
  $('z01_pai').value               = oRetorno.z01_pai.urlDecode();
  $('z01_mae').value               = oRetorno.z01_mae.urlDecode();
  $('z01_nasc').value              = dNasc;
  $('z01_sexo_select_descr').value = oRetorno.z01_sexo.urlDecode();
  $('z01_estciv').value            = js_estadoCivil(parseInt(oRetorno.z01_estciv.urlDecode(), 10));
  $('z01_ender').value             = oRetorno.z01_ender.urlDecode();
  $('z01_numero').value            = oRetorno.z01_numero.urlDecode();
  $('z01_compl').value             = oRetorno.z01_compl.urlDecode();
  $('z01_munic').value             = oRetorno.z01_munic.urlDecode();
  $('z01_uf').value                = oRetorno.z01_uf.urlDecode();
  $('z01_bairro').value            = oRetorno.z01_bairro.urlDecode();
  $('z01_cep').value               = oRetorno.z01_cep.urlDecode();
  $('z01_cxpostal').value          = oRetorno.z01_cxpostal.urlDecode();
  $('z01_telef').value             = oRetorno.z01_telef.urlDecode();
  $('z01_telcel').value            = oRetorno.z01_telcel.urlDecode();
  $('z01_email').value             = oRetorno.z01_email.urlDecode();
  $('z01_cadast').value            = dCadast;
  $('z01_ultalt').value            = dUltAlt;

}

function js_limpaInfoCgm() {

  $('sd03_i_cgm').value            = '';
  $('z01_nome').value              = '';
  $('z01_numcgm').value            = '';
  $('z01_ender').value             = '';
  $('z01_bairro').value            = '';
  $('z01_munic').value             = '';
  $('z01_cep').value               = '';
  $('z01_uf').value                = '';
  $('z01_email').value             = '';
  $('z01_telef').value             = '';
  $('z01_telcel').value            = '';
  $('z01_nasc').value              = '';
  $('z01_cgccpf').value            = '';
  $('z01_ident').value             = '';
  $('z01_mae').value               = '';
  $('z01_pai').value               = '';
  $('z01_nome2').value             = '';
  $('z01_sexo_select_descr').value = '';
  $('z01_estciv').value            = '';
  $('z01_numero').value            = '';
  $('z01_compl').value             = '';
  $('z01_cxpostal').value          = '';
  $('z01_cadast').value            = '';
  $('z01_ultalt').value            = '';

}

function js_estadoCivil(iCodigo) {

  switch (iCodigo) {

    case 0:
      return 'Não informado';

    case 1:
      return 'Solteiro.';

    case 2:
      return 'Casado';

    case 3:
      return 'Viúvo';

    case 4:
      return 'Divorciado';

    default:
      return '';

  }

}

</script>