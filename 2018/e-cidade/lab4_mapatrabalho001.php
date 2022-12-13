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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once('libs/db_utils.php');

$cllab_labsetor   = new cl_lab_labsetor;
$cllab_requisicao = new cl_lab_requisicao;
$cllab_exame      = new cl_lab_exame;
$clrotulo         = new rotulocampo;

$clrotulo->label("la09_i_exame");
$clrotulo->label("la24_i_setor");
$clrotulo->label("la02_i_codigo");
$clrotulo->label("la24_c_descr");
$clrotulo->label("la23_c_descr");
$clrotulo->label("la23_i_codigo");
$clrotulo->label("la22_i_codigo");

$iUsuario = db_getsession('DB_login');
$iDepto   = db_getsession('DB_coddepto');
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
<body class="body-default">

  <div class="container">

    <form name='form1'>

      <fieldset style="width: 540px">
        <legend>Mapa de Trabalho</legend>
        <table class="form-container">

          <tr>
            <td class="bold" >
              Filtrar:
            </td>
            <td>
              <select id='filtrarRelatorio'>
                <option value='1' >Por Agendamento</option>
                <option value='2' >Por Coleta</option>
              </select>
            </td>
          </tr>

          <tr>
            <td align="left" >
              <strong> Período:</strong>
            </td>
            <td>
              <?php db_inputdata( 'data1', @$dia1, @$mes1, @$ano1, true, 'text', 1 );?>
               A
              <?php db_inputdata( 'data2', @$dia2, @$mes2, @$ano2, true, 'text', 1 )?>
            </td>
          </tr>
          <tr>
            <td nowrap title="Laborat&oacute;rio">
               <?php
               db_ancora('<strong>Laborat&oacute;rio:</strong>', "js_pesquisala02_i_laboratorio(true);", "");
               ?>
            </td>
            <td>
            <?php
              db_input( 'la02_i_codigo', 10, $Ila02_i_codigo, true, 'text', "", "onchange='js_pesquisala02_i_laboratorio(false);'");
              db_input( 'la02_c_descr',  50, @$Ila02_c_descr, true, 'text', 3 );
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tla22_i_codigo?>">
              <?php  db_ancora ( '<strong>Requisição:</strong>', "js_pesquisala22_i_codigo(true);", "" );?>
            </td>
            <td>
            <?php
              db_input ( 'la22_i_codigo', 10, $Ila22_i_codigo, true, 'text', "", "onchange='js_pesquisala22_i_codigo(false);'" );
              db_input ( 'z01_v_nome2',   50, @$Iz01_v_nome,    true, 'text', 3 );
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tla24_i_setor?>">
              <?php
              db_ancora( @$Lla24_i_setor, "js_pesquisala24_i_setor(true);", "" );
              ?>
            </td>
            <td>
            <?php
              db_input( 'la23_i_codigo',  10, $Ila23_i_codigo,  true, 'text', "", "onchange='js_pesquisala24_i_setor(false);'");
              db_input( 'la23_c_descr',  50,  $Ila23_c_descr, true, 'text', 3 );
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tla09_i_exame?>">
              <?php
                db_ancora ( @$Lla09_i_exame, "js_pesquisala09_i_exame(true);", "" );
              ?>
            </td>
            <td>
            <?php
              db_input( 'la09_i_exame',  10, @$Ila09_i_exame, true, 'text', "", "onchange='js_pesquisala09_i_exame(false);'" );
              db_input( 'la09_i_codigo', 10, '',              true, 'hidden' );
              db_input( 'la08_c_descr',  50, @$Ila08_c_descr, true, 'text', 3 );
            ?>
            </td>
          </tr>
          <tr>
            <td>
                <strong>Atributos:</strong>
            </td>
            <td>
            <?php
              $aParam = Array( "2" => "NÃO", "1" => "SIM" );
              db_select( "atributos", $aParam, "", 1 );
            ?>
            </td>
          </tr>
          <tr>
            <td class="bold">
              Ordem:
            </td>
            <td>
              <select id='ordenacaoFiltros'>
                <option selected="selected" value='0'>Selecione</option>
                <option value='1'>Data</option>
                <option value='2'>Requisição</option>
              </select>
            </td>
          </tr>
        </table>

      </fieldset>

      <input name='start' type='button' value='Gerar' onclick="js_mandaDados()"/>
    </form>
  </div>
  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<script type="text/javascript">

function js_limpaCamposTrocaLab() {

  document.form1.la23_i_codigo.value  = '';
  document.form1.la23_c_descr.value  = '';
  js_limpaCamposTrocaSetor();
}

function js_limpaCamposTrocaSetor() {

  document.form1.la09_i_exame.value = '';
  document.form1.la08_c_descr.value = '';
}


function js_pesquisala02_i_laboratorio(mostra) {

  if ( mostra == true ) {

    js_OpenJanelaIframe(
                         '',
                         'db_iframe_lab_laboratorio',
                         'func_lab_laboratorio.php?checkLaboratorio=true'
                                                +'&funcao_js=parent.js_mostralaboratorio1|la02_i_codigo|la02_c_descr',
                         'Pesquisa',
                         true
                       );
  } else {

    if ( document.form1.la02_i_codigo.value != '' ) {

       js_OpenJanelaIframe(
                            '',
                            'db_iframe_lab_laboratorio',
                            'func_lab_laboratorio.php?checkLaboratorio=true'
                                                   +'&pesquisa_chave='+document.form1.la02_i_codigo.value
                                                   +'&funcao_js=parent.js_mostralaboratorio',
                            'Pesquisa',
                            false
                          );
    } else {
      document.form1.la02_c_descr.value = '';
    }
  }
}

function js_mostralaboratorio( chave, erro ) {

  document.form1.la02_c_descr.value = chave;

  if ( erro == true ) {

    document.form1.la02_i_codigo.focus();
    document.form1.la02_i_codigo.value = '';
  }
}

function js_mostralaboratorio1( chave1, chave2 ) {

  document.form1.la02_i_codigo.value = chave1;
  document.form1.la02_c_descr.value  = chave2;
  db_iframe_lab_laboratorio.hide();
}

function js_pesquisala22_i_codigo( mostra ) {

  var sUrl = 'func_lab_requisicao.php?autoriza=2';
  if ( !empty( $F('la02_i_codigo') ) ) {
    sUrl += '&iLaboratorioLogado=' + $F('la02_i_codigo');
  }

  if( mostra == true ) {

    sUrl += '&funcao_js=parent.js_mostrarequisicao1|la22_i_codigo|z01_v_nome';
    js_OpenJanelaIframe('', 'db_iframe_lab_requisicao', sUrl, 'Pesquisa Requisição', true);
  } else {

    if( document.form1.la22_i_codigo.value != '' ) {

      sUrl += '&pesquisa_chave='+$F('la22_i_codigo');
      sUrl += '&funcao_js=parent.js_mostrarequisicao'

      js_OpenJanelaIframe('', 'db_iframe_lab_requisicao', sUrl, 'Pesquisa Requisição', false);
    } else {
      document.form1.z01_v_nome2.value = '';
    }
  }
}

function js_mostrarequisicao( chave, erro ) {

  document.form1.z01_v_nome2.value = chave;
  if( erro == true ) {

    document.form1.la22_i_codigo.focus();
    document.form1.la22_i_codigo.value = '';
  }
}

function js_mostrarequisicao1( chave1, chave2 ) {

  document.form1.la22_i_codigo.value = chave1;
  document.form1.z01_v_nome2.value   = chave2;
  db_iframe_lab_requisicao.hide();
}

function js_pesquisala24_i_setor(mostra) {

  if( $F('la02_i_codigo') == '' ) {

    alert('Escolha um laboratório primeiro.');
    js_limpaCamposTrocaLab();
    return false;
  }

  var sUrl  = 'func_lab_setor.php?';
      sUrl += 'laboratorio='+$F('la02_i_codigo');
  if ( mostra == true ) {

    sUrl += '&funcao_js=parent.js_mostralab_labsetor1|la23_i_codigo|la23_c_descr';
    js_OpenJanelaIframe('', 'db_iframe_lab_setor', sUrl, 'Pesquisa de Setores', true );
  } else {

    if( $F('la23_i_codigo') != '' ) {

      sUrl += '&pesquisa_chave='+$F('la23_i_codigo');
      sUrl += '&funcao_js=parent.js_mostralab_labsetor';
      js_OpenJanelaIframe('', 'db_iframe_lab_setor', sUrl, 'Pesquisa de Setores', false);
    } else {

      $('la23_c_descr').value  = '';
      $('la23_i_codigo').value = '';
    }
  }
}

function js_mostralab_labsetor( chave, erro) {

  document.form1.la23_c_descr.value  = chave;

  if( erro == true ) {

    document.form1.la23_i_codigo.focus();
    document.form1.la23_i_codigo.value = '';
  }
  js_limpaCamposTrocaSetor();
}

function js_mostralab_labsetor1( chave1, chave2) {

  document.form1.la23_i_codigo.value = chave1;
  document.form1.la23_c_descr.value  = chave2;
  db_iframe_lab_setor.hide();
  js_limpaCamposTrocaSetor();
}

function js_pesquisala09_i_exame(mostra) {

  if( empty( $F('la02_i_codigo') ) ) {

    alert( 'É necessário primeiramente informar o Laboratório para pesquisar o Exame.' );
    return false;
  }

  var sFiltro = 'la02_i_codigo='+$F('la02_i_codigo');
  if ( $F('la23_i_codigo') != '' ) {
    sFiltro = 'la23_i_codigo='+$F('la23_i_codigo');
  }

  var sUrl = 'func_lab_setorexame.php?'+sFiltro;

  if ($F('la02_i_codigo') != '') {

    if( mostra == true ) {

      sUrl += '&funcao_js=parent.js_mostralab_exame1|la08_i_codigo|la08_c_descr|la09_i_codigo';
      js_OpenJanelaIframe('', 'db_iframe_lab_setorexame', sUrl, 'Pesquisa Exames', true );
    } else {

      if( $F('la09_i_exame') != '' ) {

        sUrl += '&pesquisa_chave='+$F('la09_i_exame');
        sUrl += '&funcao_js=parent.js_mostralab_exame'

        js_OpenJanelaIframe('', 'db_iframe_lab_setorexame', sUrl, 'Pesquisa Exames', false);
      } else {

        document.form1.la08_c_descr.value  = '';
        document.form1.la09_i_codigo.value = '';
      }
    }
  }
}

function js_mostralab_exame( chave, erro, chave2 ) {

  document.form1.la08_c_descr.value  = chave;
  document.form1.la09_i_codigo.value = chave2;

  if ( erro == true ) {

    document.form1.la09_i_exame.focus();
    document.form1.la09_i_exame.value = '';
  }
}

function js_mostralab_exame1( chave1, chave2, chave3 ) {

  document.form1.la09_i_exame.value  = chave1;
  document.form1.la08_c_descr.value  = chave2;
  document.form1.la09_i_codigo.value = chave3;
  db_iframe_lab_setorexame.hide();
}


function js_mandaDados() {

  if ( $F('la22_i_codigo') == '' && document.form1.data1.value == "" && document.form1.data2.value == "" ) {

     alert("Preencha o Período ou informe a Requisição.");
     document.form1.data1.focus();
     return false;
  }

  if ( $F('data1') != '' && $F('data2') == '' ) {

    alert("Informe o período final.");
    return false;
  }

  if (  $F('data1') == '' && $F('data2') != '' ) {

    alert("Informe o período inicial.");
    return false;
  }

  if ( $F('la22_i_codigo') == '' && document.form1.la02_i_codigo.value == "" ) {

    alert("Pesquise um laboratório");
    document.form1.la02_i_codigo.focus();
    return false;
  }

  var sDatas = '';
  if ($F('data1') != "" && $F('data2') != "" ) {
    sDatas = $F('data1') + ','+$F('data2');
  }

  var sFiltroOrdem = '';
  switch( parseInt($F('ordenacaoFiltros')) ) {
    case 1:
      sFiltroOrdem = '&iOrdemData=1';
      break;
    case 2:
      sFiltroOrdem = '&iOrdemRequisicao=1';
      break;
  }



  oF               = document.form1;
  var sParametros  = '';
      sParametros += 'filtrarRelatorio='+$F('filtrarRelatorio');
      sParametros += '&datas='+sDatas;
      sParametros += '&laboratorio='+oF.la02_i_codigo.value;
      sParametros += '&iRequisicao=' + $F('la22_i_codigo');
      sParametros += '&labsetor='+oF.la23_i_codigo.value;
      sParametros += '&exame='+oF.la09_i_exame.value;
      sParametros += '&nomesetor='+oF.la23_c_descr.value;
      sParametros += '&iAtributo='+oF.atributos.value;
      sParametros += sFiltroOrdem;
  jan = window.open(
                     'lab4_mapatrabalho002.php?' + sParametros,
                     '',
                     'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0'
                   );
  jan.moveTo( 0, 0 );
}

function js_validadata() {

  if( document.form1.data1.value != '' && document.form1.data2.value != '' ) {

    aIni = document.form1.data1.value.split('/');
    aFim = document.form1.data2.value.split('/');
    dIni = new Date(aIni[2], aIni[1], aIni[0]);
    dFim = new Date(aFim[2], aFim[1], aFim[0]);

    if(dFim < dIni) {

      alert("Data final não pode ser menor que a data inicial.");
      document.form1.data2.value = '';
      return false;
    }

    return true;
  } else {

    alert('Preencha o período.');
    return false
  }
}


$('data1').addEventListener('drop', js_changeValue.bind(this, $('data1').id));
$('data2').addEventListener('drop', js_changeValue.bind(this, $('data2').id));
$('la22_i_codigo').addEventListener('drop', js_changeValue.bind(this, $('la22_i_codigo').id));

function js_changeValue(sId, oEvent) {

  oEvent.preventDefault();
  oEvent.stopImmediatePropagation();

  return false;


}
</script>