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

//MODULO: educação
$oDaoProcResultado->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed40_i_codigo");
$clrotulo->label("ed42_i_codigo");
$clrotulo->label("ed37_i_codigo");

$lPossuiTurmasEncerradas = isset($_GET['possuiTurmasEncerradas']) && $_GET['possuiTurmasEncerradas'] === 'S';

?>
<div class="center">
  <form name="form1" method="post" action="">
    <table border="0" align="left" width="100%">
      <tr>
        <td valign="top">
          <table border="0" width="100%">
            <tr>
              <td nowrap title="<?=@$Ted43_i_codigo?>">
                <?=@$Led43_i_codigo?>
              </td>
              <td>
                <?php
                db_input( 'ed43_i_codigo', 15, $Ied43_i_codigo, true, 'text', 3 );
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Ted43_i_procedimento?>">
                <b>Proc. de Avaliação:</b>
              </td>
              <td>
                <?php
                db_input( 'ed43_i_procedimento', 15, $Ied43_i_procedimento, true, 'text', 3 );
                db_input( 'ed40_c_descr',        30, @$Ied40_c_descr,       true, 'text', 3 );
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Ted43_i_resultado?>">
                <?php
                db_ancora( @$Led43_i_resultado, "js_pesquisaed43_i_resultado(true);", $db_opcao1 );
                ?>
              </td>
              <td>
                <?php
                $sScript = " onchange='js_pesquisaed43_i_resultado(false);'";
                db_input( 'ed43_i_resultado', 15, $Ied43_i_resultado, true, 'text', $db_opcao1, $sScript );
                db_input( 'ed42_c_descr',     30, @$Ied42_c_descr,    true, 'text', 3 );
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Ted43_i_formaavaliacao?>">
                <?php
                db_ancora( @$Led43_i_formaavaliacao, "js_pesquisaed43_i_formaavaliacao(true);", $db_opcao1 );
                ?>
              </td>
              <td>
               <?php
               $sScript = " onchange='js_pesquisaed43_i_formaavaliacao(false);'";
               db_input( 'ed43_i_formaavaliacao', 15, $Ied43_i_formaavaliacao, true, 'text', $db_opcao1, $sScript );
               db_input( 'ed37_c_descr',          30, @$Ied37_c_descr,         true, 'text', 3 );
               ?>
               <input name="minimoaprov" type="hidden" value="">
              </td>
            </tr>
            <?php
            if ($db_opcao != 1 && trim( $forma != "PARECER" ) ) {
            ?>
            <tr>
              <td nowrap title="<?=@$Ted43_c_minimoaprov?>">
                <label class="bold">Mínimo p/ Aprovação:</label>
              </td>
              <td>
              <?php
                if (trim(@$ed37_c_minimoaprov) != trim(@$ed43_c_minimoaprov)) {

                  $disable = "";

                  ?>
                  <input type="radio"
                         name="minimo"
                         value="definido"
                         onclick="js_minimo(this.value)"
                         <?=$db_opcao == 3 ? "disabled" : ""?>>
                         <label><?=@$ed37_c_minimoaprov?></label>
                  <input type="radio"
                         name="minimo"
                         value="escolher"
                         checked
                         onclick="js_minimo(this.value)"
                         <?=$db_opcao == 3 ? "disabled" : ""?>><label>Definir</label>

                <?php
                } else {

                  $disable = "disabled";
                  ?>
                  <input type="radio"
                         name="minimo"
                         value="definido"
                         checked
                         onclick="js_minimo(this.value)"
                         <?=$db_opcao == 3 ? "disabled" : ""?>>
                         <label><?=$ed37_c_minimoaprov?></label>
                  <input type="radio"
                         name="minimo"
                         value="escolher"
                         onclick="js_minimo(this.value)"
                         <?=$db_opcao == 3 ? "disabled" : ""?>><label>Definir</label>
                <?php
                }

                if( trim( $forma == "NIVEL" ) ) {

                  $sSqlConceito = $oDaoConceito->sql_query( "", "*", "", " ed39_i_formaavaliacao = {$ed43_i_formaavaliacao}" );
                  $result       = $oDaoConceito->sql_record( $sSqlConceito );
                  ?>
                  <select name="ed43_c_minimoaprov" <?=$disable?> <?=$db_opcao == 3 ? "disabled" : ""?> >
                    <?php
                    for( $z = 0; $z < $oDaoConceito->numrows; $z++ ) {

                      db_fieldsmemory($result, $z);
                      $selected = trim($ed43_c_minimoaprov) == trim($ed39_c_conceito) ? "selected" : "";
                      echo "<option value='$ed39_c_conceito' $selected>$ed39_c_conceito</option>";
                    }
                    ?>
                  </select>
                  <?php
                  } else if (trim($forma == "NOTA")) {
                  ?>

                    <select name='ed43_c_minimoaprov' <?=$disable?> <?=$db_opcao == 3 ? "disabled" : ""?> >
                      <?php
                      for( $z = $ed37_i_menorvalor; $z <= $ed37_i_maiorvalor; $z = $z + $ed37_i_variacao ) {

                        $selected = isset($ed43_c_minimoaprov) && trim($ed43_c_minimoaprov) == $z && trim(@$ed37_c_minimoaprov) != trim(@$ed43_c_minimoaprov) ? "selected" : "";
                        echo "<option value='$z' $selected>{$z}</option>";
                      }
                      ?>
                    </select>
                  <?php
                  }?>
              </td>
            </tr>
            <?php
            }
            ?>
            <tr>
              <td colspan="2">
                <input name="minimodaforma"      type="hidden" value="<?=@$ed37_c_minimoaprov?>">
                <input name="ed37_i_menorvalor"  type="hidden" value="<?=@$ed37_i_menorvalor?>">
                <input name="ed37_i_maiorvalor"  type="hidden" value="<?=@$ed37_i_maiorvalor?>">
                <input name="ed37_i_variacao"    type="hidden" value="<?=@$ed37_i_variacao?>">
                <input name="ed37_c_minimoaprov" type="hidden" value="<?=@$ed37_c_minimoaprov?>">
                <input name="forma"              type="hidden" value="<?=@$forma?>">
                <input name="qtdperiodos"        type="hidden" value="<?=@$qtdperiodos?>">
                <input name="<?=( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) )?>"
                       type="submit" id="db_opcao"
                       value="<?=( $db_opcao == 1 ? "Incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir" ) )?>"
                       <?=( $db_botao == false || $lPossuiTurmasEncerradas ? "disabled" : "" )?> >
              </td>
            </tr>
          </table>
        </td>
        <td valign="top">
          <?php
          if ($db_opcao != 1) {
          ?>
          <table>
            <tr>
              <td nowrap title="<?=@$Ted43_c_obtencao?>">
                <label><?=@$Led43_c_obtencao?></label>
              </td>
              <td>
              <?php
                if (trim($forma  == "NIVEL")) {
                  $x = array( 'AT' => 'ATRIBUÍDO', 'MC' => 'MAIOR NIVEL', 'UC' => 'ÚLTIMO NIVEL' );
                } else if (trim($forma == "NOTA")) {
                  $x = array(
                              'AT' => 'ATRIBUÍDO',
                              'ME' => 'MÉDIA ARITMÉTICA',
                              'MP' => 'MÉDIA PONDERADA',
                              'SO' => 'SOMA',
                              'MN' => 'MAIOR NOTA',
                              'UN' => 'ÚLTIMA NOTA'
                            );
                } else if (trim($forma == "PARECER")) {
                  $x = array( 'AT' => 'ATRIBUÍDO' );
                }
                db_select( 'ed43_c_obtencao', $x, true, $db_opcao, " onchange = 'js_obtencao(this.value);'" );
              ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Ted43_c_geraresultado?>">
                <label><?=@$Led43_c_geraresultado?></label>
              </td>
              <td>
                <?php
                $elem = ElementosFreq($ed43_i_codigo);
                $x    = array( 'S' => 'SIM', 'N' => 'NÃO' );
                db_select( 'ed43_c_geraresultado', $x, true, $db_opcao, " onchange = 'js_geraresultado(this.value, $elem)'" );
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Ted43_c_boletim?>">
                <label><?=@$Led43_c_boletim?></label>
              </td>
              <td>
                <?php
                $x = array( 'S' => 'SIM', 'N' => 'NÃO' );
                db_select( 'ed43_c_boletim', $x, true, $db_opcao );
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Ted43_c_reprovafreq?>">
                <label><?=@$Led43_c_reprovafreq?></label>
              </td>
              <td>
               <?php
                $x = array( 'S' => 'SIM', 'N' => 'NÃO' );
                db_select( 'ed43_c_reprovafreq', $x, true, $db_opcao );
               ?>
              </td>
            </tr>
            <?php
            if( $forma == "NOTA" ) {
            ?>
              <tr style="display: none">
                <td nowrap title="<?=@$Ted43_c_arredmedia?>">
                  <label><?=@$Led43_c_arredmedia?></label>
                </td>
                <td>
                  <?php
                  $x = array( 'S' => 'SIM', 'N' => 'NÃO' );
                  db_select( 'ed43_c_arredmedia', $x, true, $db_opcao );
                  ?>
                </td>
              </tr>

              <tr id="linhaProporcionalidade">
                <td>
                  <label class="bold">Utilizar Proporcionalidade:</label>
                </td>
                <td>
                  <?php
                  $aOpcoes = array( 't' => 'SIM', 'f' => 'NÃO' );
                  db_select( 'ed43_proporcionalidade', $aOpcoes, true, $db_opcao );
                  ?>
                </td>
              </tr>
            <?php
            }
            ?>
          <?php
          }
          ?>
          </table>
        </td>
      </tr>
    </table>
  </form>
</div>
<script>

/**
 * Verifica se a Forma de Obtenção é igual a SOMA e libera o select "Utilizar Proporcionalidade"
 */
(function () {

  if ( $F('ed43_c_obtencao') == 'AT' ) {

    parent.document.formaba.c2.disabled    = true;
    parent.document.formaba.c2.style.color = "#9b9b9b";
  }

  if ( $('linhaProporcionalidade') ) {
    $('linhaProporcionalidade').style.visibility = 'hidden';
  }

  if ( $F('ed43_c_obtencao') == 'SO' ) {
    $('linhaProporcionalidade').style.visibility = 'visible';
  }

})();

function js_pesquisaed43_i_resultado(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe(
                         '',
                         'db_iframe_resultado',
                         'func_resultado.php?resultados=<?=$res_cad?>'
    	                                    +'&funcao_js=parent.js_mostraresultado1|ed42_i_codigo|ed42_c_descr',
    	                   'Pesquisa de Resultados',
    	                   true,
    	                   0,
    	                   0,
    	                   770,
    	                   60
    	                 );
  } else {

    if (document.form1.ed43_i_resultado.value != '') {

      js_OpenJanelaIframe(
                           '',
                           'db_iframe_resultado',
                           'func_resultado.php?resultados=<?=$res_cad?>'
    	                                      +'&pesquisa_chave='+document.form1.ed43_i_resultado.value
    	                                      +'&funcao_js=parent.js_mostraresultado',
    	                     'Pesquisa',
    	                     false
    	                   );
    } else {
      document.form1.ed42_c_descr.value = '';
    }
  }
}

function js_mostraresultado(chave, erro) {

  document.form1.ed42_c_descr.value = chave;
  if (erro == true) {

    document.form1.ed43_i_resultado.focus();
    document.form1.ed43_i_resultado.value = '';
  }
}

function js_mostraresultado1(chave1, chave2) {

  document.form1.ed43_i_resultado.value = chave1;
  document.form1.ed42_c_descr.value     = chave2;
  db_iframe_resultado.hide();
}

function js_pesquisaed43_i_formaavaliacao(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe(
                         '',
                         'db_iframe_formaavaliacao',
                         'func_formaavaliacao.php?forma=<?=$forma?>'
    	                                         +'&funcao_js=parent.js_mostraformaavaliacao1|ed37_i_codigo|ed37_c_descr|ed37_c_minimoaprov',
    	                   'Pesquisa de Formas de Avaliação',
    	                   true,
    	                   0,
    	                   0,
    	                   770,
    	                   60
    	                 );
  } else {

    if (document.form1.ed43_i_formaavaliacao.value != '') {

      js_OpenJanelaIframe(
                           '',
                           'db_iframe_formaavaliacao',
                           'func_formaavaliacao.php?forma=<?=$forma?>'
    	                                           +'&pesquisa_chave='+document.form1.ed43_i_formaavaliacao.value
    	                                           +'&funcao_js=parent.js_mostraformaavaliacao',
    	                     'Pesquisa',
    	                     false
    	                   );
    } else {
      document.form1.ed37_c_descr.value = '';
    }
  }
}

function js_mostraformaavaliacao(chave1, chave2, erro) {

  document.form1.ed37_c_descr.value = chave1;
  document.form1.minimoaprov.value  = chave2;

  if (erro == true) {

    document.form1.ed43_i_formaavaliacao.value = '';
    document.form1.ed43_i_formaavaliacao.focus();
  }
}

function js_mostraformaavaliacao1(chave1, chave2, chave3) {

  document.form1.ed43_i_formaavaliacao.value = chave1;
  document.form1.ed37_c_descr.value          = chave2;
  document.form1.minimoaprov.value           = chave3;
  db_iframe_formaavaliacao.hide();
}

function js_minimo(valor) {

  if (valor == "definido") {
    document.form1.ed43_c_minimoaprov.disabled = true;
  } else if(valor == "escolher") {
    document.form1.ed43_c_minimoaprov.disabled = false;
  }
}


function js_obtencao(valor) {

  $('linhaProporcionalidade').style.visibility = 'hidden';
  $('ed43_proporcionalidade').value            = 'f';

  if (valor == "AT") {

    parent.document.formaba.c2.disabled    = true;
    parent.document.formaba.c2.style.color = "#9b9b9b";

    if (parent.document.formaba.c4) {

      parent.document.formaba.c4.disabled    = true;
      parent.document.formaba.c4.style.color = "#9b9b9b";
    }
  } else if(valor == "SO") {

    parent.document.formaba.c2.disabled    = false;
    parent.document.formaba.c2.style.color = "black";

    if (parent.document.formaba.c4) {

      parent.document.formaba.c4.disabled    = false;
      parent.document.formaba.c4.style.color = "black";
    }

    $('linhaProporcionalidade').style.visibility = 'visible';
  } else {

    if (parent.document.formaba.c4) {

      parent.document.formaba.c4.disabled    = true;
      parent.document.formaba.c4.style.color = "#9b9b9b";
    }

    parent.document.formaba.c2.disabled = false;
    parent.document.formaba.c2.style.color          = "black";
    parent.iframe_c2.document.form1.fobtencao.value = valor;

    if (valor == "MP") {

      parent.iframe_c2.document.form1.ed44_i_peso.style.visibility      = "visible";
      parent.iframe_c2.document.getElementById("peso").style.visibility = "visible";
    } else {

      parent.iframe_c2.document.form1.ed44_i_peso.style.visibility      = "hidden";
      parent.iframe_c2.document.getElementById("peso").style.visibility = "hidden";
    }
  }
}

function js_geraresultado(valor, elementos) {

  if (valor == "S") {

    if (elementos > 0) {

      parent.document.formaba.c3.disabled        = false;
      parent.document.formaba.c3.style.color     = "black";
      document.form1.ed43_c_reprovafreq.disabled = false;
    } else {

      parent.document.formaba.c3.disabled        = false;
      parent.document.formaba.c3.style.color     = "black";
      document.form1.ed43_c_reprovafreq.disabled = true;
    }
  } else {

    parent.document.formaba.c3.disabled        = true;
    parent.document.formaba.c3.style.color     = "#9b9b9b";
    document.form1.ed43_c_reprovafreq.disabled = true;
  }
}
<?
if ($db_opcao == 2) {?>

  if (document.form1.ed43_c_obtencao.value == "AT") {

    parent.document.formaba.c2.disabled = true;
    parent.document.formaba.c2.style.color = "#9b9b9b";
  } else {

    parent.document.formaba.c2.disabled    = false;
    parent.document.formaba.c2.style.color = "black";
  }

  if (document.form1.ed43_c_obtencao.value == "SO") {

    if (parent.document.formaba.c4) {

      parent.document.formaba.c4.disabled    = false;
      parent.document.formaba.c4.style.color = "black";
    }
  } else {

    if (parent.document.formaba.c4) {

      parent.document.formaba.c4.disabled    = true;
      parent.document.formaba.c4.style.color = "#9b9b9b";
    }
  }

  if (document.form1.ed43_c_geraresultado.value == "S") {

 <? if (ElementosFreq($ed43_i_codigo) > 0) {?>

      parent.document.formaba.c3.disabled        = false;
      parent.document.formaba.c3.style.color     = "#black";
      document.form1.ed43_c_reprovafreq.disabled = false;
 <? } else {?>

      parent.document.formaba.c3.disabled        = false;
      parent.document.formaba.c3.style.color     = "#black";
      document.form1.ed43_c_reprovafreq.disabled = true;
  <?}?>
  } else {

    parent.document.formaba.c3.disabled        = true;
    parent.document.formaba.c3.style.color     = "#9b9b9b";
    document.form1.ed43_c_reprovafreq.disabled = true;
  }
<?
}
?>
</script>