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

//MODULO: Ambulatorial
$clsau_prestadorvinculos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("s110_i_codigo");
$clrotulo->label("sd63_i_codigo");
$clrotulo->label("sd63_c_nome");
$clrotulo->label("sd63_c_procedimento");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$iBloqueiaSelecaoExame = 1;
if ( $lExameComAgenda || $db_opcao == 3) {
  $iBloqueiaSelecaoExame = 3;
}
?>
<form name="form1" method="post" action="">
  <div class="container">
    <fieldset class="form-container">
      <legend>Manutenção de Vínculos</legend>
      <table>
        <tr style="display: none;">
          <td nowrap title="<?=@$Ts111_i_codigo?>">
            <?=@$Ls111_i_codigo?>
          </td>
          <td>
            <?php
            db_input( 's111_i_codigo', 10, $Is111_i_codigo, true, 'text', 3 );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ts111_i_prestador?>">
            <?=@$Ls111_i_prestador?>
          </td>
          <td>
            <?php
            $sScript = " onchange='js_pesquisas111_i_prestador(false);'";
            db_input( 's111_i_prestador', 10, $Is111_i_prestador, true, 'text', 3, $sScript );
            db_input( 'z01_nome',         40, $Iz01_nome,         true, 'text', 3 );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ts111_procedimento?>">
            <?php
            db_ancora( @$Ls111_procedimento, "js_pesquisas111_procedimento(true);", $iBloqueiaSelecaoExame );
            ?>
          </td>
          <td>
            <?php
            $sScript = " onchange='js_pesquisas111_procedimento(false);'";
            db_input( 'sd63_i_codigo',       10, $Isd63_i_codigo,       true, 'hidden', $db_opcao );
            db_input( 'sd63_c_procedimento', 10, $Isd63_c_procedimento, true, 'text',   $iBloqueiaSelecaoExame, $sScript );
            db_input( 'sd63_c_nome',         40, $Isd63_c_nome,         true, 'text',   3 );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ts111_c_situacao?>">
            <?=@$Ls111_c_situacao?>
          </td>
          <td>
            <?php
            $x = array( 'A' => 'ATIVO', 'I' => 'INATIVO' );
            db_select( 's111_c_situacao', $x, true, $db_opcao );
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="<?=( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) )?>"
           type="submit"
           id="db_opcao"
           value="<?=( $db_opcao == 1 ? "Incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir" ) )?>"
           <?=( $db_botao == false ? "disabled" : "" )?>
    />
    <input type="button"
           name="limpa"
           value="Limpar"
           onclick="location.href='sau1_sau_prestadorvinculos001.php?s111_i_prestador=<?=$s111_i_prestador?>&z01_nome=<?=$z01_nome?>'"
    />
  </div>
  <div class="container">
    <table>
      <tr>
        <td>
          <?php
          @$s111_i_prestador = $s111_i_prestador;
          $chavepri          = array( "s110_i_codigo" => @$s110_i_codigo, "s111_i_codigo" => @$s111_i_codigo );
          $sCampos           = "s110_i_codigo, s111_i_codigo, sd63_c_procedimento, sd63_i_codigo, sd63_c_nome";
          $sCampos          .= ", case s111_c_situacao           ";
          $sCampos          .= "       when 'A'                  ";
          $sCampos          .= "            then 'ATIVO'         ";
          $sCampos          .= "       when 'I'                  ";
          $sCampos          .= "            then 'INATIVO'       ";
          $sCampos          .= "            else 'Não informado' ";
          $sCampos          .= "   end as s111_c_situacao        ";

          $cliframe_alterar_excluir->chavepri      = $chavepri;
          $cliframe_alterar_excluir->sql           = $clsau_prestadorvinculos->sql_query(
                                                                                          null,
                                                                                          $sCampos,
                                                                                          null,
                                                                                          "s111_i_prestador = {$s111_i_prestador}"
                                                                                        );

          $cliframe_alterar_excluir->campos        = "s111_i_codigo, sd63_c_procedimento, sd63_c_nome, s111_c_situacao";
          $cliframe_alterar_excluir->legenda       = "EXAMES VINCULADOS";
          $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
          $cliframe_alterar_excluir->textocabec    = "darkblue";
          $cliframe_alterar_excluir->textocorpo    = "black";
          $cliframe_alterar_excluir->fundocabec    = "#aacccc";
          $cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
          $cliframe_alterar_excluir->iframe_width  = "710";
          $cliframe_alterar_excluir->iframe_height = "130";
          $cliframe_alterar_excluir->opcoes         = 1;
          $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
          ?>
        </td>
      </tr>
    </table>
  </div>
</form>
<script>
function js_pesquisas111_procedimento( mostra ) {

  if( mostra == true ) {
    js_OpenJanelaIframe(
                         '',
                         'db_iframe_sau_procedimento',
                         'func_sau_procedimento.php?funcao_js=parent.js_mostrasau_exames1|sd63_i_codigo|sd63_c_nome|sd63_c_procedimento'
                                                 +'&lProcedimentosAgendamento',
                         'Pesquisa',
                         true
                       );
  } else {

    if( $('sd63_c_procedimento').value != '' ) {
      js_OpenJanelaIframe(
                           '',
                           'db_iframe_sau_procedimento',
                           'func_sau_procedimento.php?pesquisa_chave=' + $('sd63_c_procedimento').value
                                                  +'&funcao_js=parent.js_mostrasau_exames'
                                                  +'&lProcedimentosAgendamento',
                           'Pesquisa',
                           false
                         );
    } else {

      $('sd63_i_codigo').value       = '';
      $('sd63_c_nome').value         = '';
      $('sd63_c_procedimento').value = '';
    }
  }
}

function js_mostrasau_exames() {

  $('sd63_c_nome').value   = arguments[0];
  $('sd63_i_codigo').value = arguments[2];

  if( arguments[1] == true ) {

    $('sd63_c_procedimento').focus();
    $('sd63_i_codigo').value       = '';
    $('sd63_c_procedimento').value = '';
  }
}

function js_mostrasau_exames1() {

  $('sd63_i_codigo').value       = arguments[0];
  $('sd63_c_nome').value         = arguments[1];
  $('sd63_c_procedimento').value = arguments[2];

  db_iframe_sau_procedimento.hide();
}

function js_pesquisa() {
  js_OpenJanelaIframe(
                       '',
                       'db_iframe_sau_prestadorvinculos',
                       'func_sau_prestadorvinculos.php?funcao_js=parent.js_preenchepesquisa|s111_i_codigo',
                       'Pesquisa',
                       true
                     );
}

function js_preenchepesquisa( chave ) {

  db_iframe_sau_prestadorvinculos.hide();
  <?php
  if( $db_opcao != 1 ) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

$('s111_i_prestador').className    = 'field-size2';
$('z01_nome').className            = 'field-size7';
$('sd63_c_procedimento').className = 'field-size2';
$('sd63_c_nome').className         = 'field-size7';
$('s111_c_situacao').className     = 'field-size2';
</script>