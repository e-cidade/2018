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

$clrhparam->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db03_descr");
$clrotulo->label("nomeinst");

/**
 * Ao incluir classe rhparams se campo estiver vazio altera para '0'
 */
if ( $h36_ultimaportaria == '0' ) {
  $h36_ultimaportaria = null;
}
?>
<form name="form1" method="post" action="">

<fieldset>
  <legend>Configurações do Módulo</legend>
  <table class="form-container">
    <tr>
      <td width="160" nowrap title="<?=$Th36_instit?>">
        <label for="h36_instit"><?=$Lh36_instit?></label>
      </td>
      <td>
       <?php
       db_input('h36_instit', 10, $Ih36_instit, true, 'text', 3);
       db_input('nomeinst',   40, $Inomeinst,   true, 'text', 3);
       ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Th36_modtermoposse?>">
        <label for="h36_modtermoposse">
          <?php
          db_ancora($Lh36_modtermoposse, "js_pesquisah36_modtermoposse(true);", $db_opcao);
          ?>
        </label>
    </td>
    <td>
	    <?php
      db_input('h36_modtermoposse', 10, $Ih36_modtermoposse, true, 'text', $db_opcao, " onchange='js_pesquisah36_modtermoposse(false);'");
      db_input('descrModTermo',     40, $Idb03_descr,        true, 'text', 3);
      ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=$Th36_modportariacoletiva?>">
      <label for="h36_modportariacoletiva">
        <?php
        db_ancora($Lh36_modportariacoletiva, "js_pesquisah36_modportariacoletiva(true);", $db_opcao);
        ?>
      </label>
    </td>
    <td>
      <?php
      $sScript = " onchange='js_pesquisah36_modportariacoletiva(false);'";
      db_input('h36_modportariacoletiva', 10, $Ih36_modportariacoletiva, true, 'text', $db_opcao, $sScript);
      db_input('descrModColetiva',        40, $Idb03_descr,              true, 'text', 3);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=$Th36_modportariaindividual?>">
      <label for="h36_modportariaindividual">
        <?php
        db_ancora($Lh36_modportariaindividual, "js_pesquisah36_modportariaindividual(true);", $db_opcao);
        ?>
      </label>
    </td>
    <td>
      <?php
      $sScript = " onchange='js_pesquisah36_modportariaindividual(false);'";
      db_input('h36_modportariaindividual', 10, $Ih36_modportariaindividual, true, 'text', $db_opcao, $sScript);
      db_input('descrModIndividual',        40, $Idb03_descr,                true, 'text', 3);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=$Th36_ultimaportaria?>">
      <label for="h36_ultimaportaria">
        <?=$Lh36_ultimaportaria?>
      </label>
    </td>
    <td>
	  <?php
    db_input('h36_ultimaportaria', 10, $Ih36_ultimaportaria, true, 'text', $db_opcao);
    ?>
    </td>
  </tr>
  </table>
  </fieldset>

  <fieldset>
    <legend><strong>Configuração da avaliação</strong></legend>
    <table class="form-container">

      <tr>
        <td width="160" nowrap title="<?=$Th36_intersticio?>">
          <label for="h36_intersticio">
            <?=$Lh36_intersticio?>
          </label>
        </td>
        <td>
          <?php
          db_input('h36_intersticio', 10, $Ih36_intersticio, true, 'text', $db_opcao);
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=$Th36_pontuacaominpromocao?>">
          <label for="h36_pontuacaominpromocao">
            <?=$Lh36_pontuacaominpromocao?>
          </label>
        </td>
        <td>
          <?php
          db_input('h36_pontuacaominpromocao', 10, $Ih36_pontuacaominpromocao, true, 'text', $db_opcao);
          ?>
        </td>
      </tr>

    </table>
  </fieldset>

  <fieldset>
    <legend>Assentamentos para integração com SIPREV</legend>
    <table class="form-container">
      <tr>
        <td>
          <label for="h36_temposficticios">
            <a href='#' class='dbancora' id='ancora_tempo_ficticio'>Tempo Fictício:</a>
          </label>
        <td>

          <?php
          db_input('h36_temposficticios',10,$Ih36_temposficticios, true, 'text', $db_opcao, ' data="h12_codigo" ');
          db_input('h36_temposficticios_descricao',40, 0, true,'text',3, 'data="h12_descr"');
          ?>
        </td>
      </tr>

      <tr>
        <td>
          <label for="h36_tempocontribuicaorgps">
            <a href='#' class='dbancora' id='ancora_tempo_rgps'>Tempo de Contribuição RGPS:</a>
          </label>
        </td>
        <td>
          <?php
            db_input('h36_tempocontribuicaorgps',10,$Ih36_tempocontribuicaorgps, true, 'text', $db_opcao, ' data="h12_codigo" ');
            db_input('h36_tempocontribuicaorgps_descricao',40, 0, true,'text',3, 'data="h12_descr"');
          ?>
        </td>
      </tr>

      <tr>
        <td>
          <label for="h36_tempocontribuicaorpps">
            <a href='#' class='dbancora' id='ancora_tempo_rpps'>Tempo de Contribuição RPPS:</a>
          </label>
        </td>
        <td>
          <?php
          db_input('h36_tempocontribuicaorpps', 10, $Ih36_tempocontribuicaorpps,  true, 'text', $db_opcao, ' data="h12_codigo" ');
          db_input('h36_tempocontribuicaorpps_descricao', 40, 0, true, 'text',3, 'data="h12_descr"');
          ?>
        </td>
      </tr>

      <tr>
        <td>
          <a href='#' class='dbancora' id='ancora_tempo_sem_contribuicao'>Tempo sem Contribuição:</a>
        </td>
        <td>
          <?php
          db_input('h36_temposemcontribuicao',10,$Ih36_temposemcontribuicao, true, 'text', $db_opcao, ' data="h12_codigo" ');
          db_input('h36_temposemcontribuicao_descricao',40, 0, true,'text',3, 'data="h12_descr"');
          ?>
        </td>
      </tr>

    </table>
  </fieldset>

<input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>"
       type="submit"
       id="db_opcao"
       value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
       <?=($db_botao == false ? "disabled" : "")?> >

</form>
<script>
function js_pesquisah36_modtermoposse(mostra) {

  if(mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_relatorio','func_db_relatorio.php?funcao_js=parent.js_mostraModTermoPosse1|db63_sequencial|db63_nomerelatorio','Pesquisa',true);
  } else {

    if(document.form1.h36_modtermoposse.value != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_relatorio','func_db_relatorio.php?pesquisa_chave='+document.form1.h36_modtermoposse.value+'&funcao_js=parent.js_mostraModTermoPosse','Pesquisa',false);
    } else {
      document.form1.descrModTermo.value = '';
    }
  }
}

function js_mostraModTermoPosse(chave,erro) {

  document.form1.descrModTermo.value = chave;

  if(erro == true) {

    document.form1.h36_modtermoposse.focus();
    document.form1.h36_modtermoposse.value = '';
  }
}

function js_mostraModTermoPosse1(chave1, chave2) {

  document.form1.h36_modtermoposse.value = chave1;
  document.form1.descrModTermo.value 	   = chave2;
  db_iframe_db_relatorio.hide();
}

function js_pesquisah36_modportariacoletiva(mostra) {

  if(mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_relatorio','func_db_relatorio.php?funcao_js=parent.js_mostraModColetiva1|db63_sequencial|db63_nomerelatorio','Pesquisa',true);
  } else {

    if(document.form1.h36_modportariacoletiva.value != '') {
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_relatorio','func_db_relatorio.php?pesquisa_chave='+document.form1.h36_modportariacoletiva.value+'&funcao_js=parent.js_mostraModColetiva','Pesquisa',false);
    } else {
      document.form1.descrModColetiva.value = '';
    }
  }
}

function js_mostraModColetiva(chave, erro) {

  document.form1.descrModColetiva.value = chave;

  if(erro == true) {

    document.form1.h36_modportariacoletiva.focus();
    document.form1.h36_modportariacoletiva.value = '';
  }
}

function js_mostraModColetiva1(chave1, chave2) {

  document.form1.h36_modportariacoletiva.value = chave1;
  document.form1.descrModColetiva.value 	     = chave2;
  db_iframe_db_relatorio.hide();
}

function js_pesquisah36_modportariaindividual(mostra) {

  if(mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_relatorio','func_db_relatorio.php?funcao_js=parent.js_mostraModIndividual1|db63_sequencial|db63_nomerelatorio','Pesquisa',true);
  } else {

    if(document.form1.h36_modportariaindividual.value != '') {
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_relatorio','func_db_relatorio.php?pesquisa_chave='+document.form1.h36_modportariaindividual.value+'&funcao_js=parent.js_mostraModIndividual','Pesquisa',false);
    } else {
      document.form1.descrModIndividual.value = '';
    }
  }
}

function js_mostraModIndividual(chave, erro) {

  document.form1.descrModIndividual.value = chave;

  if(erro == true) {

    document.form1.h36_modportariaindividual.focus();
    document.form1.h36_modportariaindividual.value = '';
  }
}

function js_mostraModIndividual1(chave1, chave2) {

  document.form1.h36_modportariaindividual.value = chave1;
  document.form1.descrModIndividual.value		     = chave2;
  db_iframe_db_relatorio.hide();
}

var oParametros = {
  'sArquivo'      : 'func_tipoasse.php',
  'sLabel'        : 'Pesquisar Tipo de Assentamento',
  'sObjetoLookUp' : 'db_iframe_tipoasse'
};

new DBLookUp(
  $('ancora_tempo_rgps'),
  $('h36_tempocontribuicaorgps'),
  $('h36_tempocontribuicaorgps_descricao'),
  oParametros
);

new DBLookUp(
  $('ancora_tempo_rpps'),
  $('h36_tempocontribuicaorpps'),
  $('h36_tempocontribuicaorpps_descricao'),
  oParametros
);

new DBLookUp(
  $('ancora_tempo_ficticio'),
  $('h36_temposficticios'),
  $('h36_temposficticios_descricao'),
  oParametros
);

new DBLookUp(
  $('ancora_tempo_sem_contribuicao'),
  $('h36_temposemcontribuicao'),
  $('h36_temposemcontribuicao_descricao'),
  oParametros
);
</script>