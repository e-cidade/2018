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

require_once('libs/db_utils.php');
require_once('libs/db_stdlib.php');
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/db_usuariosonline.php');
require_once('dbforms/db_funcoes.php');
require_once('libs/db_utils.php');

$oDaoLabSetor      = db_utils::getdao('lab_labsetor');
$oDaoLabRequisicao = db_utils::getdao('lab_requisicao');
$oDaoLabExame      = db_utils::getdao('lab_exame');
$iUsuario          = db_getsession('DB_login');
$iDepto            = db_getsession('DB_coddepto');
$oRotulo           = new rotulocampo;
$oRotulo->label("la09_i_exame");
$oRotulo->label("la24_i_setor");
$oRotulo->label("la02_i_codigo");
$oRotulo->label("la24_c_descr");
$oRotulo->label("la22_i_codigo");
$oRotulo->label("z01_v_nome");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC" >
    <form name='form1' class="container">
    <fieldset>
      <legend>Relatório de Exames Requisitados</legend>
        <table class="form-container">
          <tr>
            <td class="bold">
              Período:
            </td>
            <td>
              <?php
                db_inputdata('dData1', @$iDia1, @$iMes1, @$iAno1, true, 'text', 1, "");
              ?>
              A
              <?php
                db_inputdata('dData2', @$iDia2, @$iMes2, @$iAno2, true, 'text', 1, "");
              ?>
            </td>
          </tr>
          <tr >
            <td class="bold" nowrap title="Laborat&oacute;rio">
              <?php
                db_ancora('<b>Laborat&oacute;rio:</b>', "js_pesquisala02_i_laboratorio(true);", "");
              ?>
            </td>
            <td>
              <?php
                db_input('la02_i_codigo', 10, $Ila02_i_codigo, true, 'text', "",
                         " onchange='js_pesquisala02_i_laboratorio(false);'"
                        );
                db_input('la02_c_descr',50,@$Ila02_c_descr,true,'text',3,'');
              ?>
            </td>
          </tr>
          <tr>
            <td class="bold" nowrap title="<?=@$Tla24_i_setor?>">
              <?php
                db_ancora(@$Lla24_i_setor, "js_pesquisala24_i_setor(true);", "");
              ?>
            </td>
            <td>
              <?php
                db_input('la24_i_setor', 10, $Ila24_i_setor, true, 'text', "",
                         " onchange='js_pesquisala24_i_setor(false);'"
                        );
                db_input('la24_i_codigo', 10, '', true, 'hidden', '', '');
                db_input('la23_c_descr', 50, @$Ila23_c_descr, true, 'text', 3, '');
              ?>
            </td>
          </tr>
          <tr>
            <td class="bold" nowrap title="<?=@$Tla09_i_exame?>">
             <?php
               db_ancora(@$Lla09_i_exame, "js_pesquisala09_i_exame(true);", "");
             ?>
            </td>
            <td>
              <?php
                db_input('la09_i_exame', 10, @$Ila09_i_exame, true, 'text', "",
                         " onchange='js_pesquisala09_i_exame(false);'"
                        );
                db_input('la08_c_descr', 50, @$Ila08_c_descr, true, 'text', 3, '');
              ?>
            </td>
          </tr>
          <tr>
            <td class="bold" title="<?=@$Tla22_i_codigo?>">
              <?php
                db_ancora('<b>Requisição:</b>', "js_pesquisala22_i_codigo(true);", "");
              ?>
            </td>
            <td>
              <?php
                db_input('la22_i_codigo', 10, @$Ila22_i_codigo, true, 'text', "",
                         " onchange='js_pesquisala22_i_codigo(false);'"
                        );
                db_input('z01_v_nome', 50, @$Iz01_v_nome, true, 'text', 3, '');
              ?>
            </td>
          </tr>
          <tr>
            <td class="bold">
              Situação:
            </td>
            <td class="bold" >

              <input class='situacaoCheckBox' type="checkbox" name="autorizado" id="autorizado" value="autorizado" onclick="validaLiberacaoFiltros();">
              <label for="autorizado">Autorizados</label>
              <input class='situacaoCheckBox' type="checkbox" name="coletado" id="coletado" value="Coletado" onclick="validaLiberacaoFiltros();">
              <label for="coletado">Coletados  </label>
              <input class='situacaoCheckBox' type="checkbox" name="confirmado" id="confirmado" value="confirmado" onclick="validaLiberacaoFiltros();">
              <label for="confirmado">Confirmados</label>
              <input class='situacaoCheckBox' type="checkbox" name="entregue" id="entregue" value="entregue" onclick="validaLiberacaoFiltros();">
              <label for="entregue">Entregues</label>
            </td>
          </tr>
        </table>
        <fieldset id='filtros'>
          <legend>Outros Filtros</legend>
          <table class="form-container">
            <tr>
              <td class="field-size5 bold">
                Exibir login:
              </td>
              <td>
                <select id='exibirLogin'>
                  <option selected="selected" value='0'>NÃO</option>
                  <option value='1'>SIM</option>
                </select>
              </td>
            </tr>
            <tr>
              <td class="field-size5 bold">
                Exibir identificação de retirada:
              </td>
              <td>
                <select id='exibirIdentificacao'>
                  <option selected="selected" value='0'>NÃO</option>
                  <option value='1'>SIM</option>
                </select>
              </td>
            </tr>
          </table>
        </fieldset>
    </fieldset>
    <input name='start' type='button' value='Gerar' onclick="js_mandaDados()">
    </form>
    <?
    db_menu(db_getsession("DB_id_usuario"),
            db_getsession("DB_modulo"),
            db_getsession("DB_anousu"),
            db_getsession("DB_instit")
           );
    ?>
  </body>
</html>

<script>

var oFiltros = new DBToogle('filtros', false);

/**
 * Valida se libera ou não os outros filtros para seleção
 */
function validaLiberacaoFiltros() {

  var lLiberaFiltros = true;

  $$('.situacaoCheckBox').each(function (oElement ) {

    if ( oElement.checked  && oElement.name != 'entregue' ) {
      lLiberaFiltros = false;
    }

  });

  if ( $('entregue').checked ) {
    lLiberaFiltros = true;
  }

  $('exibirLogin').removeAttribute('disabled');
  $('exibirIdentificacao').removeAttribute('disabled');

  if ( !lLiberaFiltros ) {

    $('exibirLogin').setAttribute('disabled', 'disabled');
    $('exibirIdentificacao').setAttribute('disabled', 'disabled');

    $('exibirLogin').value         = 0;
    $('exibirIdentificacao').value = 0;
  }
}

function js_limpaCamposTrocaLab() {

  document.form1.la24_i_setor.value  = '';
  document.form1.la24_i_codigo.value = '';
  document.form1.la23_c_descr.value  = '';
  js_limpaCamposTrocaSetor();

}

function js_limpaCamposTrocaSetor() {

  document.form1.la09_i_exame.value = '';
  document.form1.la08_c_descr.value = '';

}

function js_pesquisala02_i_laboratorio(lMostra) {

  if (lMostra == true) {

    js_OpenJanelaIframe('',
                        'db_iframe_lab_laboratorio',
                        'func_lab_laboratorio.php?checkLaboratorio=true'
                        + '&funcao_js=parent.js_mostralaboratorio1|la02_i_codigo|la02_c_descr',
                        'Pesquisa',
                        true
                       );

  } else {

     if (document.form1.la02_i_codigo.value != '') {

        js_OpenJanelaIframe('',
                            'db_iframe_lab_laboratorio',
                            'func_lab_laboratorio.php?checkLaboratorio=true&pesquisa_chave='
                             + document.form1.la02_i_codigo.value+'&funcao_js=parent.js_mostralaboratorio',
                            'Pesquisa',
                            false
                           );

     } else {
       document.form1.la02_c_descr.value = '';
     }

  }

}

function js_mostralaboratorio(la02_c_descr, lErro) {

  document.form1.la02_c_descr.value = la02_c_descr;
  if (lErro == true) {

    document.form1.la02_i_codigo.focus();
    document.form1.la02_i_codigo.value = '';

  }
  js_limpaCamposTrocaLab();
  limpaDadosRequisicao();
}

function js_mostralaboratorio1(la02_i_codigo, la02_c_descr) {

  document.form1.la02_i_codigo.value = la02_i_codigo;
  document.form1.la02_c_descr.value  = la02_c_descr;
  db_iframe_lab_laboratorio.hide();
  js_limpaCamposTrocaLab();
  limpaDadosRequisicao();
}

function js_pesquisala24_i_setor(lMostra) {

  if (document.form1.la02_i_codigo.value == '') {

    alert('Escolha um laboratorio primeiro.');
    js_limpaCamposTrocaLab();
    return false;

  }
  sPesq = 'la24_i_laboratorio='+document.form1.la02_i_codigo.value+'&';
  if (lMostra == true) {

    js_OpenJanelaIframe('',
                      'db_iframe_lab_labsetor',
                      'func_lab_labsetor.php?'
                      + sPesq
                      + 'funcao_js=parent.js_mostralab_labsetor1|la24_i_setor|la23_c_descr|la24_i_codigo',
                      'Pesquisa',
                      true
                     );

  } else {

    if (document.form1.la24_i_setor.value != '') {

      js_OpenJanelaIframe('',
                          'db_iframe_lab_labsetor',
                          'func_lab_labsetor.php?'+sPesq
                          + 'pesquisa_chave='+document.form1.la24_i_setor.value
                          + '&funcao_js=parent.js_mostralab_labsetor',
                          'Pesquisa',
                          false
                         );

    } else {

      document.form1.la23_c_descr.value  = '';
      document.form1.la24_i_codigo.value = '';
    }

  }

}

function js_mostralab_labsetor(la23_c_descr, lErro, la24_i_codigo) {

  document.form1.la23_c_descr.value  = la23_c_descr;
  document.form1.la24_i_codigo.value = la24_i_codigo;
  if (lErro == true) {

    document.form1.la24_i_setor.focus();
    document.form1.la24_i_setor.value  = '';
    document.form1.la24_i_codigo.value = '';

  }
  js_limpaCamposTrocaSetor();
  limpaDadosRequisicao();
}

function js_mostralab_labsetor1(la24_i_setor, la23_c_descr, la24_i_codigo) {

  document.form1.la24_i_setor.value  = la24_i_setor;
  document.form1.la24_i_codigo.value = la24_i_codigo;
  document.form1.la23_c_descr.value  = la23_c_descr;
  db_iframe_lab_labsetor.hide();
  js_limpaCamposTrocaSetor();
  limpaDadosRequisicao();
}

function js_pesquisala09_i_exame(lMostra) {

  var sUrl         = 'func_lab_setorexame.php';
  var sFiltroSetor = '';
  if (document.form1.la24_i_setor.value != '') {
    sFiltroSetor = '&la24_i_codigo='+document.form1.la24_i_codigo.value+'&';
  }

  if (lMostra == true) {

    sUrl += '?funcao_js=parent.js_mostralab_exame1|la08_i_codigo|la08_c_descr';
    sUrl += sFiltroSetor;
    js_OpenJanelaIframe('', 'db_iframe_lab_setorexame', sUrl, 'Pesquisa Exames', true);
  } else {

    if (document.form1.la09_i_exame.value != '') {

      sUrl += '?pesquisa_chave='+$F('la09_i_exame');
      sUrl += '&funcao_js=parent.js_mostralab_exame';
      sUrl += sFiltroSetor;

      js_OpenJanelaIframe('', 'db_iframe_lab_setorexame', sUrl, 'Pesquisa Exames', false);

    } else {
      document.form1.la08_c_descr.value = '';
    }
  }

}

function js_mostralab_exame(la08_c_descr, lErro) {

  document.form1.la08_c_descr.value = la08_c_descr;
  if (lErro == true) {

    document.form1.la09_i_exame.focus();
    document.form1.la09_i_exame.value = '';
  }
  limpaDadosRequisicao();

}

function js_mostralab_exame1(la09_i_exame, la08_c_descr) {

  document.form1.la09_i_exame.value = la09_i_exame;
  document.form1.la08_c_descr.value = la08_c_descr;
  db_iframe_lab_setorexame.hide();

  limpaDadosRequisicao();

}

function js_mandaDados() {

  var sUrlExame = 'lab2_examesrequisitados002.php?';
  if ( $F('la22_i_codigo') == '') {

    if (!js_validadata()) {
      return false;
    }
  }


  sUrlExame += 'dtInicial='             + $F('dData1');
  sUrlExame += '&dtFinal='              + $F('dData2');
  sUrlExame += '&iLaboratorio='         + $F('la02_i_codigo');
  sUrlExame += '&iSetor='               + $F('la24_i_setor');
  sUrlExame += '&iLabsetor='            + $F('la24_i_codigo');
  sUrlExame += '&iExame='               + $F('la09_i_exame');
  sUrlExame += '&lColetado='            + $('coletado').checked;
  sUrlExame += '&lConfirmado='          + $('confirmado').checked;
  sUrlExame += '&lEntregue='            + $('entregue').checked;
  sUrlExame += '&lAutorizado='          + $('autorizado').checked;
  sUrlExame += '&lExibirLogin='         + $F('exibirLogin');
  sUrlExame += '&lExibirIdentificacao=' + $F('exibirIdentificacao');
  sUrlExame += '&iRequisicao='          + $F('la22_i_codigo')

  var sTamanho = 'width='+(screen.availWidth-5) +',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ';

  var jan = window.open( sUrlExame, '', sTamanho);
  jan.moveTo( 0, 0 );

}

function js_validadata() {

  limpaDadosRequisicao();
  if (document.form1.dData1.value != '' && document.form1.dData2.value != '' ) {

    aIni = document.form1.dData1.value.split('/');
    aFim = document.form1.dData2.value.split('/');
    dIni = new Date(aIni[2], aIni[1], aIni[0]);
    dFim = new Date(aFim[2], aFim[1], aFim[0]);
    if (dFim < dIni) {

      alert("A data final não pode ser menor que a data inicial.");
      $('dData2').value = '';
      return false;

    }
    return true;

  } else {

    alert('Preencha o periodo.');
    return false
  }

}

function js_pesquisala22_i_codigo(lMostra){

  if (lMostra == true) {

    js_OpenJanelaIframe('',
                        'db_iframe_lab_requisicao',
                        'func_lab_requisicao.php?&funcao_js='
                        + 'parent.js_mostrarequisicao1|la22_i_codigo|z01_v_nome',
                        'Pesquisa',
                        true
                       );

  } else {

    if (document.form1.la22_i_codigo.value != '') {

      js_OpenJanelaIframe('',
                          'db_iframe_lab_requisicao',
                          'func_lab_requisicao.php?&pesquisa_chave='+document.form1.la22_i_codigo.value
                          + '&funcao_js=parent.js_mostrarequisicao',
                          'Pesquisa',
                          false
                         );
    } else {
      document.form1.z01_v_nome.value = '';
    }

  }

}

function js_mostrarequisicao(z01_v_nome, lErro) {

  document.form1.z01_v_nome.value = z01_v_nome;
  if (lErro == true) {

    document.form1.la22_i_codigo.focus();
    document.form1.la22_i_codigo.value = '';

  }
  limpaOutrosFiltros();

}

function js_mostrarequisicao1(la22_i_codigo, z01_v_nome) {

  document.form1.la22_i_codigo.value = la22_i_codigo;
  document.form1.z01_v_nome.value    = z01_v_nome;
  db_iframe_lab_requisicao.hide();
  limpaOutrosFiltros()

}

function limpaOutrosFiltros() {

  $('dData1').value        = '';
  $('dData1_dia').value    = '';
  $('dData1_mes').value    = '';
  $('dData1_ano').value    = '';
  $('dData2').value        = '';
  $('dData2_dia').value    = '';
  $('dData2_mes').value    = '';
  $('dData2_ano').value    = '';
  $('la02_i_codigo').value = '';
  $('la02_c_descr').value  = '';
  $('la24_i_setor').value  = '';
  $('la24_i_codigo').value = '';
  $('la23_c_descr').value  = '';
  $('la09_i_exame').value  = '';
  $('la08_c_descr').value  = '';
}

function limpaDadosRequisicao() {

  $('la22_i_codigo').value = '';
  $('z01_v_nome').value    = '';
}

</script>