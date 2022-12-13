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

//MODULO: Laboratório
$cllab_autoriza->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("la22_i_cgs");
$clrotulo->label("la22_i_codigo");
$clrotulo->label("z01_v_nome");
$clrotulo->label("la09_i_exame");
$clrotulo->label("la24_i_laboratorio");
$clrotulo->label("la21_d_data");
$clrotulo->label("la21_c_hora");
$clrotulo->label("nome");
?>
<div class="container">
  <form name="form1" method="post" action="">
    <fieldset>
      <legend>Autorização</legend>
      <table class="form-container">
        <tr style="display: none;">
          <td nowrap title="<?=$Tla48_i_codigo?>">
            <label for="la48_i_codigo"><?=$Lla48_i_codigo?></label>
          </td>
          <td>
            <?php
            db_input( 'la48_i_codigo', 10, $Ila48_i_codigo, true, 'text', 3 );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Tla22_i_codigo?>">
            <label for="la22_i_codigo">
              <?php
              db_ancora ( '<b>Requisição:</b>', "js_pesquisala22_i_codigo(true);", "" );
              ?>
            </label>
          </td>
          <td>
            <?php
            db_input( 'la22_i_codigo', 10, $Ila22_i_codigo, true, 'text',"", " onchange='js_pesquisala22_i_codigo(false);'" );
            ?>
          </td>
          <td style="padding-left: 10px;">
            <label for="la22_d_data">Data:</label>
          </td>
          <td>
            <?php
            $la22_d_data_dia = !empty( $la22_d_data_dia ) ? $la22_d_data_dia : "";
            $la22_d_data_mes = !empty( $la22_d_data_mes ) ? $la22_d_data_mes : "";
            $la22_d_data_ano = !empty( $la22_d_data_ano ) ? $la22_d_data_ano : "";

            db_inputdata( 'la22_d_data', $la22_d_data_dia, $la22_d_data_mes, $la22_d_data_ano, true, 'text', 3 );
            ?>
          </td>
          <td style="padding-left: 10px;">
            <label for="nome">Login:</label>
          </td>
          <td>
            <?php
            db_input( 'nome', 10, $Inome, true, 'text', 3 );
            ?>
          </td>
        </tr>
        <tr style="height: 25px;">
          <td>
            <label for="la22_i_cgs"><?=$Lla22_i_cgs?></label>
          </td>
          <td colspan="5">
            <?php
            db_input( 'la22_i_cgs', 10, $Ila22_i_cgs, true, 'text', 3);
            ?>
            <?php
            db_input( 'z01_v_nome', 60, $Iz01_v_nome, true, 'text', 3 );
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="6">
            <fieldset class="separator"><legend>Exames</legend>
              <div id="GridExames"></div>
              <select name="exames" style="display:none"></select>
            </fieldset>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="<?=( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) )?>"
           type="button"
           id="db_opcao"
           value="<?=( $db_opcao == 1 ? "Autorizar" : ( $db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir" ) )?>"
           <?=( $db_botao == false ? "disabled" : "" )?> >
    <input name="limpar"
           type="button"
           id="limpar"
           value="Limpar"
           onclick="document.location = 'lab4_reqexameaut001.php';" >
  </form>
</div>
<script>

$('la48_i_codigo').addClassName( 'field-size2' );
$('la22_i_codigo').addClassName( 'field-size2' );
$('la22_d_data').addClassName( 'field-size2' );
$('la22_i_cgs').addClassName( 'field-size2' );
$('z01_v_nome').style.width = '673px';
$('nome').style.width = '500px';

const MENSAGEM_FRMLAB_AUTORIZA = 'saude.laboratorio.db_frmlab_autoriza.';

var sRPC            = 'lab4_agendar.RPC.php',
    sRPCAutorizacao = 'lab4_autorizacao.RPC.php',
    F               = document.form1,
    objGridExames   = new DBGrid('oGridExames'),
    aHeader         = [ 'Cód.', 'Laboratório', 'Exame', 'Coleta', 'Hora', 'Entrega', 'Urgente'],
    aTamanhoHeader  = [ '1%', '29%', '40%', '9%', '5%', '9%', '7%' ];

    objGridExames.nameInstance = 'objGridExames';
    objGridExames.setCheckbox( 0 );
    objGridExames.setHeader( aHeader );
    objGridExames.setCellWidth( aTamanhoHeader );
    objGridExames.aHeaders[1].lDisplayed = false;
    objGridExames.setHeight(80);
    objGridExames.show($('GridExames'));

function js_pesquisala22_i_codigo(mostra) {

  var sUrl = 'func_lab_requisicao.php?iLaboratorioLogado=<?=$iLaboratorioLogado?>&lSomenteNaoDigitados=true';
  if (mostra==true) {

    sUrl += '&funcao_js=parent.js_mostrarequisicao1|la22_i_codigo|z01_v_nome';
    js_OpenJanelaIframe('','db_iframe_requisicao', sUrl,'Pesquisa',true);
  } else {

    if (document.form1.la22_i_codigo.value != '') {

      sUrl += '&pesquisa_chave='+document.form1.la22_i_codigo.value+'&funcao_js=parent.js_mostrarequisicao';
      js_OpenJanelaIframe('', 'db_iframe_requisicao', sUrl, 'Pesquisa', false);
    } else {
      document.location = 'lab4_reqexameaut001.php';
    }
  }
}

function js_mostrarequisicao ( chave, erro) {

  document.form1.z01_v_nome.value = chave;
	if (erro == true) {

	  document.form1.la22_i_codigo.focus();
	  document.form1.la22_i_codigo.value = '';
	} else {
		js_carregaexames( document.form1.la22_i_codigo.value );
	}
}

function js_mostrarequisicao1(chave1,chave2) {

  document.form1.la22_i_codigo.value = chave1;
  document.form1.z01_v_nome.value    = chave2;
  js_carregaexames(chave1);
	db_iframe_requisicao.hide();
}

function js_AtualizaGrid(){

  objGridExames.clearAll(true);
  tam = F.exames.length;

  var aPosicaoExamesUrgentes = [];

  for( var x = 0; x < tam; x++ ) {

    sText     = F.exames.options[x].text;
    avet      = sText.split('#');

    var alinha = [];
    alinha.push( avet[0] ); //codigo Setor/Exame
    alinha.push( avet[1] ); //descr  laboratorio
    alinha.push( avet[2] ); //descr  exame
    alinha.push( avet[3] ); //data coleta
    alinha.push( avet[4] ); //hora coleta
    alinha.push( avet[8] ); //data entrega

    var scheck = '';

    if ( avet[6] == 1 ) {

      scheck = ' checked ';
      aPosicaoExamesUrgentes.push(x);
    }

    alinha.push( '<input disabled="disabled" type="checkbox" id="urgente'+x+'" '+scheck+' >' );
    objGridExames.addRow(alinha);
  }

  objGridExames.renderRows();

  aPosicaoExamesUrgentes.each( function( iExameUrgente ) {
    objGridExames.aRows[ iExameUrgente ].addClassName( 'error' );
  });

  objGridExames.renderRows();
}

function js_mudadata(data){
  F.la32_d_entrega.value = data;
}

function js_carregaexames( requisicao ) {

  var oParam                     = {};
      oParam.exec                = 'CarregaGridRequi';
      oParam.requisicao          = requisicao;
      oParam.iLaboratorioLogado  = <?=$iLaboratorioLogado?>;

  var oAjaxRequest = new AjaxRequest( sRPC, oParam, js_retornocarregaexames );
      oAjaxRequest.setMessage( _M( MENSAGEM_FRMLAB_AUTORIZA + 'carregando_exames') );
      oAjaxRequest.execute();
}

function js_retornocarregaexames( oAjax, lErro ) {

  while( F.exames.length > 0 ) {
    F.exames.remove(0);
  }

  if( oAjax.status == 1 ) {

	  F.la22_d_data.value = oAjax.dDataRequi;
	  F.nome.value        = oAjax.sLogin;
	  F.la22_i_cgs.value  = oAjax.iCgs;

	  if( oAjax.alinhasgrid.length > 0 ) {

      for( var x = 0; x < oAjax.alinhasgrid.length; x++ ) {
        F.exames.add( new Option( oAjax.alinhasgrid[x], F.exames.length ), null );
      }

      js_AtualizaGrid();
    } else {

      objGridExames.clearAll(true);
      document.location = 'lab4_reqexameaut001.php';
    }
  }
}

$('db_opcao').onclick = function() {

  var aExames             = [],
      aCodigosExames      = [],
      aExamesSelecionados = objGridExames.getSelection('object');

  if ( aExamesSelecionados.length == 0 ) {

    alert( _M( MENSAGEM_FRMLAB_AUTORIZA + 'selecione_exame') );
    return;
  }

  for( var iContador = 0; iContador < aExamesSelecionados.length; iContador++ ) {

    var oExame         = {};
    oExame.iExame      = aExamesSelecionados[ iContador].aCells[1].content;
    oExame.sDataColeta = aExamesSelecionados[ iContador].aCells[4].content;
    aExames.push( oExame );
    aCodigosExames.push(aExamesSelecionados[ iContador].aCells[1].content);
  }

  var oParametros                = {};
      oParametros.exec           = 'autorizaExames';
      oParametros.aExames        = aExames;
      oParametros.aCodigosExames = aCodigosExames;
      oParametros.iRequisicao = $F('la22_i_codigo');

  var oAjaxRequest = new AjaxRequest( sRPCAutorizacao, oParametros, retornoAutorizar );
      oAjaxRequest.setMessage( _M( MENSAGEM_FRMLAB_AUTORIZA + 'autorizando_exames') );
      oAjaxRequest.execute();
};

function retornoAutorizar( oRetorno, lErro ) {

  alert( oRetorno.sMensagem.urlDecode() );
  js_carregaexames( $F('la22_i_codigo') );
}
</script>