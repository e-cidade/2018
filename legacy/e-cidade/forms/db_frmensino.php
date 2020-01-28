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

//MODULO: educação
$oDaoEnsino->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed36_i_codigo");
$clrotulo->label("ed84_i_codigo");
$clrotulo->label("ed36_c_descr");
$clrotulo->label("ed247_i_codigo");
$clrotulo->label("ed247_c_descr");

/* BUSCA OS DADOS DA MEDIAÇÃO DIDATICO PEDAGOGICA PARA FORMAR O COMBOBOX DE SELEÇÃO */
$oDaoMediacaoDidaticoPedagogica   = db_utils::getDao('mediacaodidaticopedagogica');
$sSqlMediacaoDidaticoPedagogica   = $oDaoMediacaoDidaticoPedagogica->sql_query(null, 'mediacaodidaticopedagogica.ed130_codigo, mediacaodidaticopedagogica.ed130_descricao');
$rsMediacaoDidaticoPedagogica     = $oDaoMediacaoDidaticoPedagogica->sql_record($sSqlMediacaoDidaticoPedagogica);
$iTotalMediacaoDidaticoPedagogica = $oDaoMediacaoDidaticoPedagogica->numrows;

$aMediacaoDidaticoPedagogica      = array("" => "Selecione...");

if ($iTotalMediacaoDidaticoPedagogica > 0) {

  for ($iContadorMediacaoDidaticoPedagogica = 0; $iContadorMediacaoDidaticoPedagogica < $iTotalMediacaoDidaticoPedagogica; $iContadorMediacaoDidaticoPedagogica++) {

    $oDadosMediacaoDidaticoPedagogica = db_utils::fieldsMemory($rsMediacaoDidaticoPedagogica, $iContadorMediacaoDidaticoPedagogica);
    $aMediacaoDidaticoPedagogica[$oDadosMediacaoDidaticoPedagogica->ed130_codigo] = $oDadosMediacaoDidaticoPedagogica->ed130_descricao;
  }
}

$sDisplayCursoProfissionalizante = "display: none";
if(!empty($ed247_i_codigo)) {
  $sDisplayCursoProfissionalizante = "display: table-row";
}
?>
<form name="form1" method="post" action="" onsubmit="return validarModalidadeEnsino();" >
  <fieldset >
    <legend><?=$sAcao?> de Níveis de Ensino</legend>
    <table >
      <tr>
        <td nowrap title="<?=$Ted10_i_tipoensino?>">
          <?php db_ancora($Led10_i_tipoensino,"js_pesquisaed10_i_tipoensino(true);",$db_opcao);?>
        </td>
        <td>
          <?php
            db_input('ed10_i_codigo', 10, $Ied10_i_codigo, true, 'hidden', 3);
            db_input('ed10_i_tipoensino', 10, $Ied10_i_tipoensino, true, 'text',
                      $db_opcao," onchange='js_pesquisaed10_i_tipoensino(false);'");
            db_input('ed36_c_descr', 35, $Ied36_c_descr, true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Ted10_c_descr?>">
          <?=$Led10_c_descr?>
        </td>
        <td>
          <?php db_input('ed10_c_descr', 49, $Ied10_c_descr, true, 'text', $db_opcao)?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Ted10_c_abrev?>">
          <?=$Led10_c_abrev?>
        </td>
        <td>
          <?php db_input('ed10_c_abrev', 10, $Ied10_c_abrev, true, 'text', $db_opcao)?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Ted10_mediacaodidaticopedagogica?>">
          <?=$Led10_mediacaodidaticopedagogica?>
        </td>
        <td>
          <?php
            db_select('ed130_codigo', $aMediacaoDidaticoPedagogica, true, $db_opcao, "style='width:100%;'");
          ?>
        </td>
      </tr>
      <tr id='ctnCursoProfissionalizante' style="<?=$sDisplayCursoProfissionalizante?>">
        <td nowrap title="<?=$Ted10_censocursoprofiss?>">
          <?php db_ancora($Led10_censocursoprofiss, "pesquisaCursoProfissionalizante(true);", $db_opcao); ?>
        </td>
        <td>
          <?php
            db_input('ed247_i_codigo', 10, $Ied247_i_codigo, true, 'text', $db_opcao,
                     " onchange='pesquisaCursoProfissionalizante(false);'");
            db_input('ed247_c_descr', 35, $Ied247_c_descr, true, 'text', 3);
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
         type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
         <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  <input name="novo" type="button" id="novo" value="Novo Registro" onclick="js_novo()" <?=$db_opcao==1?"disabled":""?>>
</form>

<script>
function js_pesquisaed10_i_tipoensino(mostra) {

  $('ctnCursoProfissionalizante').style.display = 'none';
  $('ed247_i_codigo').value                     = '';
  $('ed247_c_descr').value                      = '';
  if (mostra == true) {

    js_OpenJanelaIframe('','db_iframe_tipoensino','func_tipoensino.php?funcao_js=parent.js_mostratipoensino1'+
    	                '|ed36_i_codigo|ed36_c_descr','Pesquisa Modalidades de Ensino',true
    	               );

  } else {

    if (document.form1.ed10_i_tipoensino.value != '') {

      js_OpenJanelaIframe('','db_iframe_tipoensino',
    	                  'func_tipoensino.php?pesquisa_chave='+document.form1.ed10_i_tipoensino.value+
    	                  '&funcao_js=parent.js_mostratipoensino','Pesquisa Tipos de Ensino',false
    	                 );

    } else {
      document.form1.ed36_c_descr.value = '';
    }

  }

}

function js_mostratipoensino(chave,erro) {

  document.form1.ed36_c_descr.value = chave;
  if (erro == true) {

    document.form1.ed10_i_tipoensino.focus();
    document.form1.ed10_i_tipoensino.value = '';
  }

  if ($F('ed10_i_tipoensino') == 4) {
    $('ctnCursoProfissionalizante').style.display = 'table-row';
  }
}

function js_mostratipoensino1(chave1,chave2) {

  document.form1.ed10_i_tipoensino.value = chave1;
  document.form1.ed36_c_descr.value      = chave2;
  db_iframe_tipoensino.hide();

  if ($F('ed10_i_tipoensino') == 4) {
    $('ctnCursoProfissionalizante').style.display = 'table-row';
  }

}

function js_novo() {
  parent.location.href="edu1_ensinoabas001.php";
}

function js_pesquisa() {

  js_OpenJanelaIframe('','db_iframe_ensino','func_ensino.php?funcao_js=parent.js_preenchepesquisa|ed10_i_codigo',
		              'Pesquisa de Níveis de Ensino',true
		             );

}

function js_preenchepesquisa(chave) {

 db_iframe_ensino.hide();
 <?
 if ($db_opcao != 1) {
   echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
 }
 ?>
}

/**
 * Pesquisa os cursos profissionalizantes
 * @param  {boolean} lMostra
 * @return {void}
 */
function pesquisaCursoProfissionalizante( lMostra ) {

  var sUrl   = 'func_censocursoprofiss.php';
      sUrl  += '?funcao_js=parent.retornoPesquisaCurso';
  var sLabel = 'Pesquisa Cursos Profissionalizantes'
  if (lMostra ) {

    sUrl += '|ed247_i_codigo|ed247_c_descr';
    js_OpenJanelaIframe('', 'db_iframe_censocursoprofiss', sUrl, sLabel, true);
  } else if ($F('ed247_i_codigo') != '') {

    sUrl += '&pesquisa_chave=' + $F('ed247_i_codigo');
    js_OpenJanelaIframe('', 'db_iframe_censocursoprofiss', sUrl, sLabel, false);
  } else {

    $('ed247_i_codigo').value = '';
    $('ed247_c_descr').value  = '';
  }
}

function retornoPesquisaCurso() {

  if (typeof arguments[1] == 'boolean') {

    $('ed247_c_descr').value = arguments[0];
    if (arguments[1]) {
      $('ed247_i_codigo').value = '';
    }
  } else {

    $('ed247_i_codigo').value = arguments[0];
    $('ed247_c_descr').value  = arguments[1];
    db_iframe_censocursoprofiss.hide();
  }
}


function validarModalidadeEnsino() {

  if ( $F('ed10_i_tipoensino') == 4 && $F('ed247_i_codigo') == '' )  {

    alert('Informe o curso profissionalizante.');
    return false;
  }
  return true;
}

function teste() {

}
</script>