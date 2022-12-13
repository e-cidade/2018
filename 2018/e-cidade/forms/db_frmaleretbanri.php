<?php
/**
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

//MODULO: pessoal
$clmovrel->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("r56_descr");
$clrotulo->label("r56_dirarq");
$clrotulo->label("r55_descr");
$clrotulo->label("db50_codigo");
$clrotulo->label("db50_descr");
$clrotulo->label("DBtxt23");
$clrotulo->label("DBtxt25");

if(!isset($r54_anousu)) {
  $r54_anousu = db_anofolha();
}

if(!isset($r54_mesusu)) {
  $r54_mesusu = db_mesfolha();
}
?>
<form name="form1" method="post" action="" enctype="multipart/form-data">
  <fieldset>
    <legend>Geração do Arquivo de Retorno do Banrisul</legend>
    <table class="form-container">
      <tr>
        <td title="Digite o Ano / Mes de competência" >
          <label for="DBtxt23">Ano / Mês :</label>
        </td>
        <td>
          <?php
          db_input('DBtxt23', 4, $IDBtxt23, true, 'text', 1, "", 'r54_anousu');
          db_input('DBtxt25', 2, $IDBtxt25, true, 'text', 1, "", 'r54_mesusu');
          ?>
        </td>
      </tr>
      <tr>
        <td title="<?=$Tdb50_codigo?>">
          <label for="db50_codigo">
            <?php
            db_ancora($Ldb50_codigo, "js_pesquisa(true);", 1);
            ?>
          </label>
        </td>
        <td colspan="3">
          <?php
          db_input("db50_codigo",  6, $Idb50_codigo, true, "text", 4, "onchange='js_pesquisa(false);'");
          db_input("db50_descr",  40, $Idb50_descr,  true, "text", 3);
          ?>
        </td>
      </tr>
      <tr>
        <td title="<?=$Tr54_codrel?>">
          <label for="r54_codrel">
            <?php
            db_ancora($Lr54_codrel, "js_pesquisar54_codrel(true);", 1);
            ?>
          </label>
        </td>
        <td>
          <?php
          $sJavaScript = "onchange='js_pesquisar54_codrel(false);' onblur='document.form1.r56_dirarq.focus()'";
          db_input('r54_codrel',  6, $Ir54_codrel, true, 'text', 1, $sJavaScript);
          db_input('r56_descr',  40, $Ir56_descr,  true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <label for="teste">Teste:</label>
        </td>
        <td style="width: 50px;">
          <?php
          $aOpcoes = array( "f" => "NÃO", "t" => "SIM");
          db_select('teste', $aOpcoes, true, 4);
          ?>
        </td>
      </tr>
      <tr>
        <td title="<?=$Tr56_dirarq?>">
          <label for="diretorio_arquivo">
            <?php
            db_ancora($Lr56_dirarq, "", 3);
            ?>
          </label>
        </td>
        <td >
          <?php
          db_input('diretorio_arquivo', 49, $Ir56_dirarq, true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td align='left' nowrap>
        </td>
        <td nowrap align='left'>
          <?php
          db_input('r56_dirarq', 49, $Ir56_dirarq, true, 'file', 1, "onblur='document.form1.r54_codeve.focus();'");
          db_input('texto', 20, 0, true, 'hidden', 3);
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="gerar"
         type="submit"
         id="db_opcao"
         value="Gerar retorno"
         <?=($db_botao == false ? "disabled" : "")?>
         onclick="return js_verifica_campos();">
</form>
<script>
  function js_verifica_campos() {

    if(document.form1.r54_anousu.value == "" || document.form1.r54_mesusu.value == "") {

      alert("Informe o ano/mês de competência.");
      document.form1.r54_anousu.select();
      document.form1.r54_anousu.focus();

      return false;
    }

    if(document.form1.r54_codrel.value == "") {

      alert("Informe o código do convênio.");
      document.form1.r54_codrel.focus();

      return false;
    }

    if(document.form1.r56_dirarq.value == "") {

      alert("Informe o caminho do arquivo.");
      return false;
    }

    document.form1.texto.value = (window.CurrentWindow || parent.CurrentWindow).bstatus.document.getElementById('st').innerHTML;
    (window.CurrentWindow || parent.CurrentWindow).bstatus.document.getElementById('st').innerHTML = '&nbsp;&nbsp;<blink><strong><font color="red">GERANDO ARQUIVO</font></strong></blink>' ;

    return true;
  }

  function js_pesquisar54_codrel(mostra) {

    if(mostra == true) {

      js_OpenJanelaIframe(
        'CurrentWindow.corpo',
        'db_iframe_convenio',
        'func_convenioalt.php?funcao_js=parent.js_mostraconvenio1|r56_codrel|r56_descr|r56_dirarq&instit=<?=(db_getsession("DB_instit"))?>',
        'Pesquisa',
        true,
        20
      );
    } else {

      if(document.form1.r54_codrel.value != '') {

        js_OpenJanelaIframe(
          'CurrentWindow.corpo',
          'db_iframe_convenio',
          'func_convenioalt.php?pesquisa_chave='+document.form1.r54_codrel.value+'&funcao_js=parent.js_mostraconvenio&instit=<?=(db_getsession("DB_instit"))?>',
          'Pesquisa',
          false,
          '0'
        );
      } else {

        document.form1.r56_descr.value         = '';
        document.form1.diretorio_arquivo.value = '';
      }
    }
  }

  function js_mostraconvenio(chave1, chave2, erro) {

    document.form1.r56_descr.value  = chave1;

    if(erro == true) {

      document.form1.diretorio_arquivo.value = "";
      document.form1.r54_codrel.value        = '';
      document.form1.r54_codrel.focus();
    } else {
      document.form1.diretorio_arquivo.value = chave2;
    }
  }

  function js_mostraconvenio1(chave1, chave2, chave3) {

    document.form1.r54_codrel.value        = chave1;
    document.form1.r56_descr.value         = chave2;
    document.form1.diretorio_arquivo.value = chave3;

    db_iframe_convenio.hide();
  }

  function js_pesquisar54_codeve(mostra) {

    if(mostra == true) {

      js_OpenJanelaIframe(
        'CurrentWindow.corpo',
        'db_iframe_relac',
        'func_relac.php?funcao_js=parent.js_mostrarelac1|r55_codeve|r55_descr|r56_dirarq&instit=<?=(db_getsession("DB_instit"))?>',
        'Pesquisa',
        true,
        '20'
      );
    } else {

      if(document.form1.r54_codeve.value != '') {

        js_OpenJanelaIframe(
          'CurrentWindow.corpo',
          'db_iframe_relac',
          'func_relac.php?pesquisa_chave='+document.form1.r54_codeve.value+'&funcao_js=parent.js_mostrarelac&instit=<?=(db_getsession("DB_instit"))?>',
          'Pesquisa',
          false,
          '0'
        );
      } else {
        document.form1.r55_descr.value = '';
      }
    }
  }

  function js_mostrarelac(chave, erro) {

    document.form1.r55_descr.value  = chave;

    if(erro == true) {

      document.form1.r54_codeve.value = '';
      document.form1.r54_codeve.focus();
    }
  }

  function js_mostrarelac1(chave1, chave2) {

    document.form1.r54_codeve.value = chave1;
    document.form1.r55_descr.value  = chave2;

    db_iframe_relac.hide();
  }

  function js_pesquisa(mostra) {

    if(mostra == true) {

      js_OpenJanelaIframe(
        'CurrentWindow.corpo',
        'db_iframe_db_layouttxt',
        'func_db_layouttxt.php?funcao_js=parent.js_mostra1|db50_codigo|db50_descr',
        'Pesquisa',
        true
      );
    } else {

      if(document.form1.db50_codigo.value != '') {

        js_OpenJanelaIframe(
          'CurrentWindow.corpo',
          'db_iframe_db_layouttxt',
          'func_db_layouttxt.php?pesquisa_chave='+document.form1.db50_codigo.value+'&funcao_js=parent.js_mostra2',
          'Pesquisa',
          false
        );
      } else {

        document.form1.db50_codigo.value = '';
        document.form1.db50_descr.value  = '';
        location.href                    = 'pes2_retbanrisul001.php';
      }
    }
  }

  function js_mostra2(chave, erro) {

    document.form1.db50_descr.value = chave;

    if(erro == true) {

      document.form1.db50_codigo.value = '';
      document.form1.db50_descr.focus();
    }
  }

  function js_mostra1(chave1, chave2) {

    document.form1.db50_codigo.value = chave1;
    document.form1.db50_descr.value  = chave2;
    document.form1.submit();

    db_iframe_db_layouttxt.hide();
  }

  function js_pesquisarh34_codban(mostra) {

    if(mostra == true) {

      js_OpenJanelaIframe(
        'CurrentWindow.corpo',
        'db_iframe_db_bancos',
        'func_db_bancos.php?funcao_js=parent.js_mostradb_bancos1|db90_codban|db90_descr',
        'Pesquisa',
        true
      );
    } else {

      if(document.form1.rh34_codban.value != '') {

        js_OpenJanelaIframe(
          'CurrentWindow.corpo',
          'db_iframe_db_bancos',
          'func_db_bancos.php?pesquisa_chave='+document.form1.rh34_codban.value+'&funcao_js=parent.js_mostradb_bancos',
          'Pesquisa',
          false
        );
      } else {
        document.form1.db90_descr.value = '';
      }
    }
  }
</script>