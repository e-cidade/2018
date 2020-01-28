<?php
/*
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

//MODULO: cadastro
$cliptubaixa->rotulo->label();
$cliptubaixaproc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j01_nome");
$clrotulo->label("nome");
$clrotulo->label("j02_dtbaixa");
$clrotulo->label("p58_nome");
?>
<form name="form1" method="post" action="">

  <fieldset>
    <legend>Baixa</legend>

    <fieldset class="separator">
      <legend>Dados do usuário</legend>
      <table class="form-container">
          <?php
          if (!isset($j02_usuario) || $j02_usuario == "") {
              $sqlUsuario = "select id_usuario as j02_usuario,nome from db_usuarios where id_usuario = " . db_getsession('DB_id_usuario');
              $rsUsuario  = db_query($sqlUsuario);
              db_fieldsmemory($rsUsuario, 0);
          }

          if (!isset($j02_data) || $j02_data == "") {
              $j02_data_dia = date('d', db_getsession('DB_datausu'));
              $j02_data_mes = date('m', db_getsession('DB_datausu'));
              $j02_data_ano = date('Y', db_getsession('DB_datausu'));
          }

          if (!isset($j02_hora) || $j02_hora == "") {
              $j02_hora = db_hora();
          }
          ?>
        <tr>
          <td nowrap title="<?=$Tj02_usuario;?>">
            <label for="j02_usuario">
              <?php
              db_ancora($Lj02_usuario, "js_pesquisaj02_usuario(true);", 3);
              ?>
            </label>
          </td>
          <td nowrap>
              <?php
              db_input('j02_usuario', 10, $Ij02_usuario, true, 'text', 3, " onchange='js_pesquisaj02_usuario(false);'");
              db_input('nome', 34, $Inome, true, 'text', 3, '');
              ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=$Tj02_data;?>">
              <label for="j02_data"><?=$Lj02_data;?></label>
          </td>
          <td nowrap>
              <?php
              db_inputdata('j02_data', @$j02_data_dia, @$j02_data_mes, @$j02_data_ano, true, 'text', 3, "");
              echo $Lj02_hora;
              db_input('j02_hora', 5, $Ij02_hora, true, 'text', 3, "");
              ?>
          </td>
        </tr>
      </table>
    </fieldset>

    <fieldset class="separator">
      <legend>Dados da baixa</legend>
      <table>
        <tr>
          <td nowrap title="<?=$Tj02_matric;?>">
            <label for="j02_matric">
              <?php
              db_ancora($Lj02_matric, "js_pesquisaj02_matric(true);", $db_opcao);
              ?>
            </label>
          </td>
          <td>
              <?php
              db_input('j02_matric', 10, $Ij02_matric, true, 'text', $db_opcao, " onchange='js_pesquisaj02_matric(false);'");
              db_input('j01_nome', 34, null, true, 'text', 3, '');
              ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Tj03_codproc;?>">
            <label for="j03_codproc">
              <?php
              db_ancora($Lj03_codproc, "js_pesquisaj03_codproc(true);", $db_opcao);
              ?>
            </label>
          </td>
          <td>
              <?php
              db_input('j03_codproc', 10, $Ij03_codproc, true, 'text', $db_opcao, " onchange='js_pesquisaj03_codproc(false);'");
              db_input('p58_nome', 34, null, true, 'text', 3, '');
              ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Tj02_dtbaixa;?>">
              <label for="j02_dtbaixa"><?=$Lj02_dtbaixa;?></label>
          </td>
          <td>
              <?php
              $j02_dtbaixa_dia = !empty($j02_dtbaixa_dia) ? $j02_dtbaixa_dia: "";
              $j02_dtbaixa_mes = !empty($j02_dtbaixa_mes) ? $j02_dtbaixa_mes: "";
              $j02_dtbaixa_ano = !empty($j02_dtbaixa_ano) ? $j02_dtbaixa_ano: "";

              db_inputdata('j02_dtbaixa', $j02_dtbaixa_dia, $j02_dtbaixa_mes, $j02_dtbaixa_ano, true, 'text', $db_opcao, "");
              ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Tj02_motivo;?>" colspan="2">
            <fieldset>
              <legend>Observações</legend>
              <div>
                  <?php
                  db_textarea('j02_motivo', 5, 45, $Ij02_motivo, true, 'text', $db_opcao, "");
                  ?>
              </div>
            </fieldset>
          </td>
        </tr>
      </table>
    </fieldset>
  </fieldset>
  <input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>"
         type="submit" id="db_opcao"
         value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>"
         <?=($db_botao == false ? "disabled" : "") ?>
         onClick="return js_valida();">
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
</form>
<script type="text/javascript">

  function js_valida() {

    if($F('j02_matric') == '') {

      alert('Campo Matrícula do Imóvel é de preenchimento obrigatório.');
      return false;
    }

    if($F('j02_dtbaixa') == '') {

      alert('Campo Data da Baixa é de preenchimento obrigatório.');
      return false;
    }

    if($F('j02_motivo') == '') {

      alert('Campo Observações é de preenchimento obrigatório.');
      return false;
    }

    return true;
  }

  function js_pesquisaj02_matric(mostra) {

    var sTitulo = 'Pesquisa Matrícula';

    if(mostra == true) {

      js_OpenJanelaIframe(
        'CurrentWindow.corpo',
        'db_iframe_iptubase',
        'func_iptubase.php?funcao_js=parent.js_mostraiptubase1|j01_matric|z01_nome',
        sTitulo,
        mostra
      );
    } else {

      if(document.form1.j02_matric.value != '') {

        js_OpenJanelaIframe(
          'CurrentWindow.corpo',
          'db_iframe_iptubase',
          'func_iptubase.php?pesquisa_chave=' + document.form1.j02_matric.value + '&funcao_js=parent.js_mostraiptubase',
          sTitulo,
          mostra
        );
      } else {
        document.form1.j01_nome.value = '';
      }
    }
  }

  function js_mostraiptubase(chave, erro) {

    document.form1.j01_nome.value = chave;

    if(erro == true) {

      document.form1.j02_matric.focus();
      document.form1.j02_matric.value = '';
    }
  }

  function js_mostraiptubase1(chave1, chave2) {

    document.form1.j02_matric.value = chave1;
    document.form1.j01_nome.value   = chave2;

    db_iframe_iptubase.hide();
  }

  function js_pesquisaj02_usuario(mostra) {

    var sTitulo = 'Pesquisa Usuário';

    if(mostra == true) {

      js_OpenJanelaIframe(
        'CurrentWindow.corpo',
        'db_iframe_db_usuarios',
        'func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome',
        sTitulo,
        mostra
      );
    } else {

      if(document.form1.j02_usuario.value != '') {

        js_OpenJanelaIframe(
          'CurrentWindow.corpo',
          'db_iframe_db_usuarios',
          'func_db_usuarios.php?pesquisa_chave=' + document.form1.j02_usuario.value + '&funcao_js=parent.js_mostradb_usuarios',
          sTitulo,
          mostra
        );
      } else {
        document.form1.nome.value = '';
      }
    }
  }

  function js_mostradb_usuarios(chave, erro) {

    document.form1.nome.value = chave;

    if(erro == true) {

      document.form1.j02_usuario.focus();
      document.form1.j02_usuario.value = '';
    }
  }

  function js_mostradb_usuarios1(chave1, chave2) {

    document.form1.j02_usuario.value = chave1;
    document.form1.nome.value        = chave2;

    db_iframe_db_usuarios.hide();
  }

  function js_pesquisa() {

    js_OpenJanelaIframe(
      'CurrentWindow.corpo',
      'db_iframe_iptubaixa',
      'func_iptubaixa.php?funcao_js=parent.js_preenchepesquisa|j02_matric',
      'Pesquisa',
      true
    );
  }

  function js_preenchepesquisa(chave) {

    db_iframe_iptubaixa.hide();
      <?php
      if ($db_opcao != 1) {
          echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave";
      }
      ?>
  }

  function js_pesquisaj03_codproc(mostra) {

    var sTitulo = 'Pesquisa Processo';

    if(mostra == true) {

      js_OpenJanelaIframe(
        'CurrentWindow.corpo',
        'db_iframe_protprocesso',
        'func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|p58_requer',
        sTitulo,
        mostra
      );
    } else {

      if(document.form1.j03_codproc.value != '') {

        js_OpenJanelaIframe(
          'CurrentWindow.corpo',
          'db_iframe_protprocesso',
          'func_protprocesso.php?pesquisa_chave=' + document.form1.j03_codproc.value
                             + '&funcao_js=parent.js_mostraprotprocesso'
                             + '&requerente=true',
          sTitulo,
          mostra
        );
      } else {
        document.form1.p58_nome.value = '';
      }
    }
  }

  function js_mostraprotprocesso(chave, erro) {

    document.form1.p58_nome.value = chave;

    if(erro == true) {

      document.form1.j03_codproc.focus();
      document.form1.j03_codproc.value = '';
    }
  }

  function js_mostraprotprocesso1(chave1, chave2) {

    document.form1.j03_codproc.value = chave1;
    document.form1.p58_nome.value    = chave2;

    db_iframe_protprocesso.hide();
  }

  $('nome').addClassName('field-size7');
</script>