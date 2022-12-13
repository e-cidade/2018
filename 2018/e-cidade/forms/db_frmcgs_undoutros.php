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

//MODULO: saude
$clcgs->rotulo->label();
$clcgs_und->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd33_v_descricao");
$clrotulo->label("sd34_v_descricao");

$clrotulo->label("s200_codigo");
$clrotulo->label("s200_identificador");
$clrotulo->label("s200_descricao");

$escola = db_getsession("DB_coddepto");
?>
<div class="container">
  <form name="form1" method="post" action="" enctype="multipart/form-data" class="form-container">
    <fieldset>
    <legend>Outros Dados</legend>
      <table>
        <tr>
          <td nowrap title="<?=@$Tz01_i_cgsund?>">
            <?php
            db_ancora( @$Lz01_i_cgsund, "", $db_opcao1 );
            ?>
          </td>
          <td colspan="6">
            <?php
            $z01vnome = $z01_i_cgsund .' - '.$z01_v_nome;

            db_input( 'z01vnome',        83, $Iz01_v_nome, true,   'text', $db_opcao1 );
            db_input( 'localrecebefoto',  6,            0, true, 'hidden',          3 );
            db_input( 'z01_i_cgsund',    20,            0, true, 'hidden',          3 );
            db_input( 'z01_v_nome',      60, $Iz01_v_nome, true, 'hidden',          3 );
            ?>
          </td>
          <td title="<?=@$Trh50_oid?>" rowspan="7" id='fotofunc'>
            <?php
            global $oid;

            if( trim( $z01_i_cgsund ) != "" && $z01_i_cgsund != null ) {

              $result_foto = db_query("select z01_o_oid as oid from cgs_und where z01_i_cgsund = {$z01_i_cgsund}");
              if( pg_numrows( $result_foto ) > 0 ) {
                db_fieldsmemory( $result_foto, 0 );
              }
            }

            $mostrarimagem = "imagens/none1.jpeg";

            if( !empty($oid) ) {
              $mostrarimagem = "func_mostrarimagem.php?oid=".$oid;
            }

            $href = "<img src='".$mostrarimagem."' border=0 width='95' height='120'>";
            db_ancora( "{$href}", "js_alterafoto();", "{$db_opcao}" );
            ?>
          </td>
        </tr>
        <SCRIPT LANGUAGE="JavaScript">
          team = new Array(
          <?php
          // Seleciona todos os calendários
          $sql1        = "SELECT sd34_i_codigo,sd34_v_descricao ";
          $sql1       .= "  FROM microarea ";
          $sql1       .= " ORDER BY sd34_v_descricao";
          $sql_result  = db_query( $sql1 );

          $num   = pg_num_rows( $sql_result );
          $conta = "";

          while( $row = pg_fetch_array( $sql_result ) ) {

            $conta     = $conta + 1;
            $cod_micro = $row["sd34_i_codigo"];

            echo "new Array(\n";
            $sub_sql     = "SELECT sd35_i_codigo,sd33_v_descricao ";
            $sub_sql    .= "  FROM familiamicroarea ";
            $sub_sql    .= "       inner join familia on sd33_i_codigo = sd35_i_familia ";
            $sub_sql    .= " WHERE sd35_i_microarea = '{$cod_micro}' ";
            $sub_sql    .= " ORDER BY sd33_v_descricao";
            $sub_result  = db_query( $sub_sql );
            $num_sub     = pg_num_rows($sub_result);

            if( $num_sub >= 1 ) {

              echo "new Array(\"\", ''),\n";
              $conta_sub = "";

              while( $rowx = pg_fetch_array( $sub_result ) ) {

                $codigo_fam = $rowx["sd35_i_codigo"];
                $nome_fam   = $rowx["sd33_v_descricao"];
                $conta_sub  = $conta_sub + 1;

                if( $conta_sub == $num_sub ) {

                  echo "new Array(\"$nome_fam\", $codigo_fam)\n";
                  $conta_sub = "";
                } else {
                  echo "new Array(\"$nome_fam\", $codigo_fam),\n";
                }
              }
            } else {
              echo "new Array(\"Microarea sem familias cadastradas.\", '')\n";
            }

            if( $num > $conta ) {
              echo "),\n";
            }
          }

          echo "))\n";
          ?>

          //Inicio da função JS
          function fillSelectFromArray( selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem ) {

            var i, j;
            var prompt;

            // empty existing items
            for( i = selectCtrl.options.length; i >= 0; i-- ) {
              selectCtrl.options[i] = null;
            }

            prompt = (itemArray != null) ? goodPrompt : badPrompt;
            if( prompt == null ) {

              selectCtrl.options[0] = new Option('','');
              j = 0;
            } else {

              selectCtrl.options[0] = new Option(prompt);
              j = 1;
            }

            if( itemArray != null ) {

              // add new items
              for( i = 0; i < itemArray.length; i++ ) {

                selectCtrl.options[j] = new Option(itemArray[i][0]);

                if( itemArray[i][1] != null ) {
                  selectCtrl.options[j].value = itemArray[i][1];
                }

                <?php
                if( isset( $z01_i_familiamicroarea ) && $z01_i_familiamicroarea != "" ) {
                ?>
                  if( <?=trim( $z01_i_familiamicroarea)?> == itemArray[i][1] ) {
                    indice = i;
                  }
                <?}?>
                j++;
              }

              <?php
              if( isset( $z01_i_familiamicroarea ) && $z01_i_familiamicroarea != "" ) {
              ?>
                selectCtrl.options[indice].selected = true;
              <?php
              } else {
              ?>
                selectCtrl.options[0].selected = true;
              <?}?>
            }
          }
        </script>
        <tr title="<?=@$Trh70_descr?>">
          <td  align="left" nowrap  >
            <strong>
              <?
              db_ancora("Ocupação", "js_pesquisaCbo(true);", $db_opcao);
              ?>
            </strong>
          </td>
          <td colspan="4" align="left">
            <?
            db_input("z01_i_codocupacao",  10, "", true, "text", $db_opcao, "onchange='js_pesquisaCbo(false);'");
            db_input("z01_i_descocupacao",  110, "",  true, "text", 3, "");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap>
            <b>Micro:</b>&nbsp;&nbsp;&nbsp;
          </td>
          <td nowrap>
            <select id="z01_v_micro"
                    name="z01_v_micro"
                    onChange="fillSelectFromArray(this.form.z01_i_familiamicroarea, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));">
             <option value=""></option>
             <?php
             $sql1        = "SELECT sd34_i_codigo,sd34_v_descricao ";
             $sql1       .= "  FROM microarea ";
             $sql1       .= " ORDER BY sd34_v_descricao";
             $sql_result  = db_query( $sql1 );

             while( $row = pg_fetch_array( $sql_result ) ) {

               $cod_micro  = $row["sd34_i_codigo"];
               $desc_micro = $row["sd34_v_descricao"];
               ?>
               <option value="<?=$cod_micro;?>" <?=$cod_micro == @$sd34_i_codigo ? "selected" : ""?>><?=$desc_micro;?></option>
               <?php
             }
             ?>
            </select>
          </td>
          <td nowrap >
            <label class="bold">Família:</label>
          </td>
          <td nowrap>
            <select id="z01_i_familiamicroarea"
                    name="z01_i_familiamicroarea"
                    onchange="if(this.value=='')document.form1.z01_v_micro.value='';">
             <option value=""></option>
            </select>
            <?php
            if( isset( $z01_i_familiamicroarea ) && $z01_i_familiamicroarea != "" ) {
            ?>
              <script>fillSelectFromArray(document.form1.z01_i_familiamicroarea, team[document.form1.z01_v_micro.selectedIndex-1]);</script>
            <?php
            }
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <?=@$Lz01_i_fatorrh?>
          </td>
          <td>
            <?php
            $x = array( '0' => '', '1' => 'POSITIVO', '2' => 'NEGATIVO' );
            db_select( 'z01_i_fatorrh', $x, true, $db_opcao );
            ?>
          </td>
          <td>
            <?=@$Lz01_i_tiposangue?>
          </td>
          <td>
            <?php
              $x = array( '0' => '', '1' => 'A', '2' => 'B', '3' => 'O', '4' => 'AB' );
              db_select( 'z01_i_tiposangue', $x, true, $db_opcao );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="Responsável:">
            <label class="bold">Responsável:</label>
          </td>
          <td colspan="3">
            <?php
            db_input( 'z01_c_nomeresp', 83, $Iz01_c_nomeresp, true, 'text', $db_opcao );
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <?=@$Lz01_c_raca?>
          </td>
          <td>
            <?php
            $x = array(
                        'NÃO DECLARADA'  => 'NÃO DECLARADA',
                        'BRANCA'         => 'BRANCA',
                        'PRETA'          => 'PRETA',
                        'PARDA'          => 'PARDA',
                        'AMARELA'        => 'AMARELA',
                        'INDÍGENA'       => 'INDÍGENA',
                        'SEM INFORMACAO' => 'SEM INFORMACAO'
                      );
            db_select( 'z01_c_raca', $x, true, $db_opcao, "onchange='js_validaRaca();'" );
            ?>
          </td>
          <td>
            <?=@$Lz01_c_bolsafamilia?>
          </td>
          <td>
            <?php
            $x = array( 'N' => 'NÃO', 'S' => 'SIM' );
            db_select( 'z01_c_bolsafamilia', $x, true, $db_opcao );
            ?>
          </td>
          <td style="display: none;">
            <?php
            $x = array( 'N' => 'NÃO', 'S' => 'SIM' );
            db_select( 'z01_c_passivo',      $x, true, $db_opcao , "style='visibility:hidden;'" );
            ?>
          </td>
        </tr>
        <tr id='selecionaEtnia' style="display: none;">
          <td nowrap="nowrap" >
            <?php
            db_ancora( 'Etnia', "js_buscaEtnia();", $db_opcao );
            ?>
          </td>
          <td nowrap>
           <?php
           db_input( 's200_codigo',        10, $Is200_codigo,        true, 'hidden', $db_opcao );
           db_input( 's200_identificador', 10, $Is200_identificador, true, 'text',           3 );
           db_input( 's200_descricao',     40, $Is200_descricao,     true, 'text',           3 );
           ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tz01_t_obs?>">
            <?=@$Lz01_t_obs?>
          </td>
          <td colspan="4">
            <?php
            db_textarea( 'z01_t_obs', 4, 117, $Iz01_t_obs, true, 'text', $db_opcao );
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="alterar" type="submit" value="<?=$db_value?>" <?=($db_botao == false ? "disabled" : "" );?>>
    <?php
    if( isset( $retornacgs ) ){
      echo "<input name='fechar' type='submit' value='Fechar''";
    }
    ?>
  </form>
</div>
<script>
function js_alterafoto() {
  js_OpenJanelaIframe( '', 'db_iframe_localfoto', 'func_localfoto.php', 'Foto do funcionário', true, 0 );
}

function js_cartaosus() {

  if( document.form1.z01_c_cartaosus.value != '' ) {
    document.form1.z01_c_cartaosus.value = preenche( document.form1.z01_c_cartaosus.value, '0', 16, 'l' );
  }
}

function js_pesquisasd35_i_familiamicroarea( mostra ) {

  if( mostra == true ) {

    js_OpenJanelaIframe(
                         '',
                         'db_iframe_familiamicroarea',
                         'func_familiamicroarea.php?funcao_js=parent.js_mostrafamiliamicroarea1|sd35_i_codigo'
                                                                                             +'|sd33_v_descricao'
                                                                                             +'|sd34_v_descricao',
                         'Pesquisa',
                         true
                       );
  } else {

    if( document.form1.sd35_i_familia.value != '' ) {

      js_OpenJanelaIframe(
                           'CurrentWindow.corpo',
                           'db_iframe_familia',
                           'func_familia.php?pesquisa_chave=' + document.form1.z01_i_familiamicroarea.value
                                          +'&funcao_js=parent.js_mostrafamiliamicroarea',
                           'Pesquisa',
                           false
                         );
    } else {
      document.form1.z01_i_familiamicroarea.value = '';
    }
  }
}

function js_mostrafamiliamicroarea( chave, erro ) {

  document.form1.sd33_v_descricao.value = chave;

  if( erro == true ) {

    document.form1.z01_i_familiamicroarea.focus();
    document.form1.z01_i_familiamicroarea.value = '';
  }
}

function js_mostrafamiliamicroarea1( chave1, chave2, chave3 ) {

  document.form1.z01_i_familiamicroarea.value = chave1;
  document.form1.sd33_v_descricao.value       = chave2;
  document.form1.sd34_v_descricao.value       = chave3;
  db_iframe_familiamicroarea.hide();
}

function js_naturalidade() {

  js_OpenJanelaIframe(
                       '',
                       'db_iframe_ceplocalidades',
                       'func_ceplocalidades.php?funcao_js=parent.js_preenchepesquisanaturalidade|cp05_sigla|cp05_localidades',
                       'Pesquisa',
                       true
                     );
}

function js_preenchepesquisanaturalidade( chave, chave1 ) {

  document.form1.z01_c_naturalidade.value = chave1;
  db_iframe_ceplocalidades.hide();
}

function js_transporte( transporte ) {

  if( transporte == "" ) {
    document.form1.z01_c_zona.value = "";
  }
}

function js_transporte1( transporte, zona ) {

  if( transporte == "" ) {
    document.form1.z01_c_zona.value = "";
  }

  if( zona == "" ) {
    document.form1.z01_c_transporte.value = "";
  }
}

function js_novo() {
  parent.location = "edu1_cgs_undabas001.php";
}

function js_validaRaca() {

  if( $F('z01_c_raca') == 'INDÍGENA' ) {
    $('selecionaEtnia').style.display = 'table-row';
  } else {
    $('selecionaEtnia').style.display = 'none';
  }
}

function js_buscaEtnia() {

  var sURL  = 'func_etnia.php?';
      sURL += 'funcao_js=parent.js_mostraEtnia|s200_codigo|s200_identificador|s200_descricao';
  js_OpenJanelaIframe( '', 'db_iframe_etnia', sURL, 'Pesquisa Etnia', true );
}

function js_mostraEtnia( iCodigo, iIdentificador, sDescricao ) {

  $('s200_codigo').value        = iCodigo;
  $('s200_identificador').value = iIdentificador;
  $('s200_descricao').value     = sDescricao;
  db_iframe_etnia.hide();
}

js_validaRaca();
function js_pesquisaCbo(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_Cbo','func_rhcbo.php?funcao_js=parent.js_mostraCbo|rh70_sequencial|rh70_descr|rh70_estrutural','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_Cbo','func_rhcbo.php?lCadastroCgm=true&pesquisa_chave='+document.form1.z01_i_codocupacao.value+'&funcao_js=parent.js_mostraCboHide','Pesquisa', false);
  }

}

js_pesquisaCbo(false);

function js_mostraCboHide(chave, chave2, chave3, erro){

  if (chave2 != false) {

    if(erro==true){

      document.form1.z01_i_codocupacao.value = '';
      document.form1.z01_i_codocupacao.focus();

    }

    document.form1.z01_i_descocupacao.value = chave3 + ' - ' + chave2;

  } else {

    document.form1.z01_i_codocupacao.value = '';
    document.form1.z01_i_descocupacao.value      = '';

  }

}

function js_mostraCbo(chave1,chave2,chave3){

  document.form1.z01_i_codocupacao.value = chave1;
  document.form1.z01_i_descocupacao.value      = chave3 + ' - ' + chave2;
  db_iframe_Cbo.hide();

}

$('z01vnome').className               = 'field-size-max';
$('z01_v_micro').className            = 'field-size-max';
$('z01_i_familiamicroarea').className = 'field-size-max';
$('z01_i_fatorrh').className          = 'field-size-max';
$('z01_i_tiposangue').className       = 'field-size-max';
$('z01_c_nomeresp').className         = 'field-size-max';
$('z01_c_raca').className             = 'field-size9';
$('z01_c_bolsafamilia').className     = 'field-size9';
</script>
