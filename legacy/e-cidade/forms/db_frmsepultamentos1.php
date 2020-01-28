<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

$clsepultamentos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("cm17_i_funeraria");
$clrotulo->label("cm18_i_hospital");
$clrotulo->label("cm14_i_codigo");
$clrotulo->label("nome");
$clrotulo->label("cm04_c_descr");
$clrotulo->label("cm07_d_vencimento");

?>
<form name="form1" method="post" action="">
<fieldset>
  <legend>Certidão de Óbito</legend>
<table border="0">
  <tr>
    <td nowrap title="<?php echo $Tcm01_i_medico; ?>">
      <?php
        db_ancora($Lcm01_i_medico, "js_pesquisacm01_i_medico(true);", $db_opcao);
      ?>
    </td>
    <td>
      <?php

        if (!isset($cm32_nome)) {
          $cm32_nome = '';
        }
        $GLOBALS['Gcm32_nome'] = true;
        db_input('cm01_i_medico', 10, $Icm01_i_medico, true, 'text', $db_opcao, " onchange='js_pesquisacm01_i_medico(false);'");
        db_input('cm32_nome', 40, $cm32_nome, true, 'text', $db_opcao, '');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?php echo $Tcm01_i_causa; ?>">
      <?php
        db_ancora($Lcm01_i_causa, "js_pesquisacm01_i_causa(true);", $db_opcao);
      ?>
    </td>
    <td>
      <?php
        db_input('cm01_i_causa', 10, $Icm01_i_causa, true, 'text', $db_opcao, " onchange='js_pesquisacm01_i_causa(false);'");
        db_input('cm04_c_descr', 40, $Icm04_c_descr, true, 'text', 3, '');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?php echo $Tcm01_c_local; ?>">
       <?php echo $Lcm01_c_local; ?>
    </td>
    <td>
      <?php
        db_input('cm01_c_local', 54, $Icm01_c_local, true, 'text', $db_opcao, "");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?php echo $Tcm01_c_cartorio; ?>">
       <?php echo $Lcm01_c_cartorio; ?>
    </td>
    <td>
      <?php
        db_input('cm01_c_cartorio', 54, $Icm01_c_cartorio, true, 'text', $db_opcao, "");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?php echo $Tcm01_i_hospital; ?>">
      <?php
        db_ancora($Lcm01_i_hospital, "js_pesquisacm01_i_hospital(true);", $db_opcao);
      ?>
    </td>
    <td>
      <?php

        $GLOBALS['Gnome_hospital'] = true;
        db_input('cm01_i_hospital', 10, $Icm01_i_hospital, true, 'text', $db_opcao, " onchange='js_pesquisacm01_i_hospital(false);'");
        db_input('nome_hospital', 40, 0, true, 'text', $db_opcao, '');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?php echo $Tcm01_i_funeraria; ?>">
      <?php
        db_ancora($Lcm01_i_funeraria, "js_pesquisacm01_i_funeraria(true);", $db_opcao);
      ?>
    </td>
    <td>
      <?php

        $GLOBALS['Gnome_funeraria'] = true;
        db_input('cm01_i_funeraria', 10, $Icm01_i_funeraria, true, 'text', $db_opcao, " onchange='js_pesquisacm01_i_funeraria(false);'");
        db_input('nome_funeraria', 40, 0, true, 'text', $db_opcao, '');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?php echo $Lcm01_c_livro; ?>">
       <?php echo $Lcm01_c_livro; ?>
    </td>
    <td>
      <?php
        db_input('cm01_c_livro', 10, $Icm01_c_livro, true, 'text', $db_opcao, "");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?php echo $Lcm01_i_folha; ?>">
       <?php echo $Lcm01_i_folha; ?>
    </td>
    <td>
      <?php
        db_input('cm01_i_folha', 10, $Icm01_i_folha, true, 'text', $db_opcao, "");
      ?>
    </td>
  </tr>
    <td nowrap title="<?php echo $Lcm01_i_registro; ?>">
       <?php echo $Lcm01_i_registro; ?>
    </td>
    <td>
      <?php
        db_input('cm01_i_registro', 10, $Icm01_i_registro, true, 'text', $db_opcao, "");
      ?>
    </td>
  </tr>
 </table>
 </fieldset>
 <fieldset>
 <legend>Declarante</legend>
 <table>
  <tr>
    <td nowrap title="Renovante">
      <?php
        db_ancora("<strong>Declarante:</strong>", "js_pesquisacm01_i_declarante(true);", $db_opcao);
      ?>
    </td>
    <td>
      <?php
        if (!isset($nome_declarante)) {
          $nome_declarante = '';
        }
        db_input('cm01_i_declarante', 10, $Icm01_i_declarante, true, 'text', $db_opcao, " onchange='js_pesquisacm01_i_declarante(false);'");
        db_input('nome_declarante', 38, $nome_declarante, true, 'text', 3, '');
      ?>
    </td>
  </tr>
  <?php
    if($db_opcao == 1) {
  ?>
    <tr>
      <td nowrap title="<?php echo $Tcm07_d_vencimento; ?>">
         <strong>Vencimento:</strong>
      </td>
      <td>
        <?php
          if (!isset($cm07_d_vencimento_dia)) {
            $cm07_d_vencimento_dia = '';
            $cm07_d_vencimento_mes = '';
            $cm07_d_vencimento_ano = '';
          }

          if($cm07_d_vencimento_dia == "") {
            $cm07_d_vencimento_dia = substr($cm01_d_falecimento, 8, 2);
          }

          if($cm07_d_vencimento_mes == "") {
            $cm07_d_vencimento_mes = substr($cm01_d_falecimento, 5, 2);
          }

          if($cm07_d_vencimento_ano == "") {
            $cm07_d_vencimento_ano = substr($cm01_d_falecimento, 0, 4) + 5;
          }

          db_inputdata('cm07_d_vencimento', $cm07_d_vencimento_dia, $cm07_d_vencimento_mes, $cm07_d_vencimento_ano, true, 'text', $db_opcao, "");
        ?>
      </td>
    </tr>
  <?php
    }
  ?>
  </table>
 </fieldset>
 <center>
<?php if(!isset($sepultamento)) { ?>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick='return validar()' >
<?php } ?>
</center>
</form>
<script type="text/javascript">

var oCodigoMedico = $('cm01_i_medico');
var oNomeMedico   = $('cm32_nome');

var oCodigoHospital = $('cm01_i_hospital');
var oNomeHospital   = $('nome_hospital');

var oCodigoFuneraria = $('cm01_i_funeraria');
var oNomeFuneraria   = $('nome_funeraria');

var oCodigoDeclarante = $('cm01_i_declarante');
var oNomeDeclarante   = $('nome_declarante');

var oCodigoCausa    = $('cm01_i_causa');
var oCodigoLocal    = $('cm01_c_local');
var oCodigoCartorio = $('cm01_c_cartorio');

js_controla_campo(oNomeMedico, oCodigoMedico);
js_controla_campo(oNomeHospital, oCodigoHospital);
js_controla_campo(oNomeFuneraria, oCodigoFuneraria);

function validar() {

  try{

    if(oCodigoCausa.value == '') {
      throw new Error("Campo Causa é de preenchimento obrigatório.");
    }

    if(oCodigoLocal.value == '') {
      throw new Error("Campo Local é de preenchimento obrigatório.");
    }

    if(oCodigoCartorio.value == '') {
      throw new Error("Campo Cartório é de preenchimento obrigatório.");
    }

    if(oNomeDeclarante.value == '') {
      throw new Error("Campo Declarante é de preenchimento obrigatório.");
    }

  } catch(erro) {

    alert(erro.message);
    return false;
  }

  return true;
}

function js_controla_campo(oCampo, oCampoRefencia) {

  if (oCampoRefencia.value != '') {

    oCampo.readOnly = true;
    oCampo.classList.add("readOnly");
    return true;
  }

  if(oCampoRefencia.readOnly == false) {

    oCampo.readOnly = false;
    oCampo.classList.remove("readOnly");
  }

}

function js_pesquisacm01_i_funeraria(mostra) {

  oNomeFuneraria.value = "";

  if(mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_a2', 'db_iframe_funerarias', 'func_funerarias.php?funcao_js=parent.js_mostrafunerarias1|cm17_i_funeraria|z01_nome', 'Pesquisa', true);
  } else {
    if(document.form1.cm01_i_funeraria.value != '') {
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_a2', 'db_iframe_funerarias', 'func_funerarias.php?pesquisa_chave='+document.form1.cm01_i_funeraria.value+'&funcao_js=parent.js_mostrafunerarias', 'Pesquisa', false);
    } else {
      js_controla_campo(oNomeFuneraria, oCodigoFuneraria);
    }
  }
}

function js_mostrafunerarias(chave, erro) {

  document.form1.nome_funeraria.value = chave;

  if(erro == true) {

    document.form1.cm01_i_funeraria.focus();
    document.form1.cm01_i_funeraria.value = '';
  }

  js_controla_campo(oNomeFuneraria, oCodigoFuneraria);
}

function js_mostrafunerarias1(chave1, chave2) {

  document.form1.cm01_i_funeraria.value = chave1;
  document.form1.nome_funeraria.value   = chave2;

  js_controla_campo(oNomeFuneraria, oCodigoFuneraria);
  db_iframe_funerarias.hide();
}

function js_pesquisacm01_i_hospital(mostra) {

  oNomeHospital.value = "";

  if(mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_a2', 'db_iframe_hospitais', 'func_hospitais.php?funcao_js=parent.js_mostrahospitais1|cm18_i_hospital|z01_nome', 'Pesquisa', true);
  } else {
    if(document.form1.cm01_i_hospital.value != '') {
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_a2', 'db_iframe_hospitais', 'func_hospitais.php?pesquisa_chave='+document.form1.cm01_i_hospital.value+'&funcao_js=parent.js_mostrahospitais', 'Pesquisa', false);
    } else {
      js_controla_campo(oNomeHospital, oCodigoHospital);
    }
  }
}

function js_mostrahospitais(chave, erro) {

  document.form1.nome_hospital.value = chave;

  if(erro == true) {

    document.form1.cm01_i_hospital.focus();
    document.form1.cm01_i_hospital.value = '';
  }

  js_controla_campo(oNomeHospital, oCodigoHospital);
}

function js_mostrahospitais1(chave1, chave2) {

  document.form1.cm01_i_hospital.value = chave1;
  document.form1.nome_hospital.value   = chave2;

  js_controla_campo(oNomeHospital, oCodigoHospital);
  db_iframe_hospitais.hide();
}

function js_pesquisacm01_i_medico(mostra) {

  oNomeMedico.value = "";

  if(mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_a2', 'db_iframe_legista', 'func_legista.php?funcao_js=parent.js_mostramedicos1|cm32_i_codigo|z01_nome', 'Pesquisa', true);
  } else {
    if(document.form1.cm01_i_medico.value != '') {
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_a2', 'db_iframe_legista', 'func_legista.php?pesquisa_chave='+document.form1.cm01_i_medico.value+'&funcao_js=parent.js_mostramedicos', 'Pesquisa', false);
    } else {
      js_controla_campo(oNomeMedico, oCodigoMedico);
    }
  }
}

function js_mostramedicos(chave, erro) {

  document.form1.cm32_nome.value = chave;

  if(erro == true) {

    document.form1.cm01_i_medico.focus();
    document.form1.cm01_i_medico.value = '';
  }

  js_controla_campo(oNomeMedico, oCodigoMedico);
}

function js_mostramedicos1(chave1, chave2) {

  document.form1.cm01_i_medico.value = chave1;
  document.form1.cm32_nome.value     = chave2;

  js_controla_campo(oNomeMedico, oCodigoMedico);
  db_iframe_legista.hide();
}

function js_pesquisacm01_i_causa(mostra) {

  if(mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_a2', 'db_iframe_causa', 'func_causa.php?funcao_js=parent.js_mostracausa1|cm04_i_codigo|cm04_c_descr', 'Pesquisa', true);
  } else {
    if(document.form1.cm01_i_causa.value != '') {
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_a2', 'db_iframe_causa', 'func_causa.php?pesquisa_chave='+document.form1.cm01_i_causa.value+'&funcao_js=parent.js_mostracausa', 'Pesquisa', false);
    } else {
      document.form1.cm04_c_descr.value = '';
    }
  }
}

function js_mostracausa(chave, erro) {

  document.form1.cm04_c_descr.value = chave;

  if(erro == true) {

    document.form1.cm01_i_causa.focus();
    document.form1.cm01_i_causa.value = '';
  }
}

function js_mostracausa1(chave1, chave2) {

  document.form1.cm01_i_causa.value = chave1;
  document.form1.cm04_c_descr.value = chave2;
  db_iframe_causa.hide();
}

function js_pesquisacm01_i_declarante(mostra) {

  if(mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_a2', 'func_nome', 'func_cgmtipo.php?funcao_js=parent.js_mostradeclarante1|z01_numcgm|z01_nome&tipo=fisico', 'Pesquisa', true);
  } else {
    if(document.form1.cm01_i_declarante.value != '') {
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_a2', 'func_nome', 'func_cgmtipo.php?pesquisa_chave='+document.form1.cm01_i_declarante.value+'&funcao_js=parent.js_mostradeclarante&tipo=fisico', 'Pesquisa', false);
    } else {
      document.form1.nome_declarante.value = '';
    }
  }
}

function js_mostradeclarante(erro, chave) {

  if(document.form1.cm01_i_declarante.value == <?php echo isset($cm01_i_codigo) ? $cm01_i_codigo : 'null'; ?>) {
    alert('Aviso!\n\nCgm informado para o declarante é o mesmo para o Sepultamento!');
    erro = true;
  }

  document.form1.nome_declarante.value = chave;

  if(erro == true) {

    document.form1.cm01_i_declarante.focus();
    document.form1.cm01_i_declarante.value = '';
    document.form1.nome_declarante.value   = '';
  }
}

function js_mostradeclarante1(chave1, chave2) {

  if(chave1 == <?php echo isset($cm01_i_codigo) ? $cm01_i_codigo : 'null'; ?> ) {
    alert('Aviso!\n\nCgm informado para o declarante é o mesmo para o Sepultamento!');
    return false;
  }

  document.form1.cm01_i_declarante.value = chave1;
  document.form1.nome_declarante.value   = chave2;

  func_nome.hide();
}

function js_pesquisa() {
  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_a2', 'db_iframe_sepultamentos', 'func_sepultamentos.php?funcao_js=parent.js_preenchepesquisa|cm01_i_codigo', 'Pesquisa', true);
}

function js_preenchepesquisa(chave) {

  db_iframe_sepultamentos.hide();

  <?php
    if($db_opcao != 1) {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
  ?>
}
</script>