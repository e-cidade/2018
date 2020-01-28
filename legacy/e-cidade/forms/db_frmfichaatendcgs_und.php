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
$clprontuarios->rotulo->label();
$clcgs->rotulo->label();
$clcgs_und->rotulo->label();

//Prontuario/Agendamento
$clagendamentos->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("z01_i_numcgs");
$clrotulo->label("s115_c_cartaosus");
$clrotulo->label("s115_c_tipo");

$clrotulo->label("descrdepto");

//Médico
$clrotulo->label("sd03_i_codigo");
$clrotulo->label("z01_nome");
//Unidade / Medicos
$clrotulo->label("sd04_i_cbo");
//especmedico
$clrotulo->label("sd27_i_codigo");

//CBO
$clrotulo->label("rh70_sequencial");
$clrotulo->label("rh70_estrutural");
$clrotulo->label("rh70_descr");

//Setor ambulatorial
$oDaoSetorAmbulatorial  = new cl_setorambulatorial();
?>
<SCRIPT LANGUAGE="JavaScript">
  team = []
  <?php

  $sql1       = "SELECT sd34_i_codigo,sd34_v_descricao ";
  $sql1      .= "  FROM microarea  ";
  $sql1      .= "  ORDER BY sd34_v_descricao";
  $sql_result = db_query( $sql1 );
  $num        = pg_num_rows( $sql_result );
  $conta      = "";

  $aArrayPai = array();
  while( $row = pg_fetch_array( $sql_result ) ) {

    $conta     = $conta+1;
    $cod_micro = $row["sd34_i_codigo"];
    $aArrayFilho = array();

    $sub_sql    = "SELECT sd35_i_codigo,sd33_v_descricao ";
    $sub_sql   .= "  FROM familiamicroarea ";
    $sub_sql   .= "       inner join familia on sd33_i_codigo = sd35_i_familia ";
    $sub_sql   .= " WHERE sd35_i_microarea = '{$cod_micro}' ";
    $sub_sql   .= " ORDER BY sd33_v_descricao";
    $sub_result = db_query( $sub_sql );
    $num_sub    = pg_num_rows( $sub_result );

    if($num_sub>=1){

      $aArrayFilho[] = array('', '');
      $conta_sub = "";

      while( $rowx = pg_fetch_array( $sub_result ) ) {

        $codigo_fam = $rowx["sd35_i_codigo"];
        $nome_fam   = $rowx["sd33_v_descricao"];
        $conta_sub  = $conta_sub+1;

        if( $conta_sub == $num_sub ) {

          $aArrayFilho[] = array(urlencode($nome_fam), $codigo_fam);
          $conta_sub = "";
        } else {
          $aArrayFilho[] = array(urlencode($nome_fam), $codigo_fam);
        }
      }
    } else {
      $aArrayFilho[] = array("Microarea sem familias cadastradas.", '');
    }
    $aArrayPai[] = $aArrayFilho ;
  }
  $sArrayJson = JSON::create()->stringify($aArrayPai);
  ?>
  team = <?=$sArrayJson?>;

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

        selectCtrl.options[j] = new Option(itemArray[i][0].urlDecode());

        if( itemArray[i][1] != null ) {
          selectCtrl.options[j].value = itemArray[i][1];
        }

        <?php
        if( isset( $z01_i_familiamicroarea ) && $z01_i_familiamicroarea != "" ) {?>
         if(<?=trim($z01_i_familiamicroarea)?>==itemArray[i][1]){
           indice = i;
         }
        <?}?>
        j++;
      }

      <?if(isset($z01_i_familiamicroarea)&&$z01_i_familiamicroarea!=""){?>
       selectCtrl.options[indice].selected = true;
      <?}else{?>
       selectCtrl.options[0].selected = true;
      <?}?>
    }
  }
</script>

<div class="container">
  <form id="frmPaciente" name="form1" method="post" action="" class="form-container">
    <fieldset>
      <legend><b>Paciente</b></legend>
      <table>
        <tr>
          <td>
             <?=@$Lsd24_i_codigo?>
          </td>
          <td>
            <?php
            db_input( 'sd24_i_codigo', 12, $Isd24_i_codigo, true, 'text', 3 );
            ?>
          </td>
          <?php
          if( $obj_sau_config->s103_c_lancafaa == "I" ) { ?>
            <td>
              <?php
              db_ancora( @$Lsd23_i_codigo, "js_pesquisasd23_i_codigo(true);", $db_opcao );
              ?>
            </td>
            <td>
              <?php
              db_input( 'sd23_i_codigo', 21, $Isd23_i_codigo, true, 'text', 3 );
              ?>
            </td>
          <? } ?>
        </tr>
        <tr>
          <td>
            <?php
            db_ancora( @$Lsd24_i_unidade, "js_pesquisasd24_i_unidade(true);", 3 );
            ?>
          </td>
          <td colspan=3>
            <?php
            db_input( 'sd24_i_unidade', 12, $Isd24_i_unidade, true, 'text', 3, "onchange='js_pesquisasd24_i_unidade(false);'" );
            @db_input( 'descrdepto', 57, $Idescrdepto, true, 'text', 3 );
            ?>
          </td>
        </tr>
        <!--  CGS / Nome -->
        <tr>
          <td>
            <?php
            db_ancora( @$Lz01_i_cgsund, "js_pesquisaz01_i_cgsund(true);", $db_opcao );
            ?>
          </td>
          <td colspan="3">
            <?php
            db_input( 'z01_i_cgsund', 12, $Iz01_i_cgsund, true, 'text', $db_opcao, "onchange='js_pesquisaz01_i_cgsund(false);'" );
            db_input( 'z01_v_nome',   57, $Iz01_v_nome,   true, 'text', $db_opcao );
            ?>
          </td>
        </tr>
        <!-- Micro Área / Familia -->
        <tr>
          <td>
            <b>Micro:</b>
          </td>
          <td>
            <select  id="z01_v_micro"
                     name="z01_v_micro"
                     onChange="fillSelectFromArray(this.form.z01_i_familiamicroarea, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));" >
             <option></option>
             <?php
             $sql1        = "SELECT sd34_i_codigo,sd34_v_descricao ";
             $sql1       .= "  FROM microarea ";
             $sql1       .= " ORDER BY sd34_v_descricao";
             $sql_result  = db_query($sql1);

             while( $row = pg_fetch_array( $sql_result ) ) {

               $cod_micro  = $row["sd34_i_codigo"];
               $desc_micro = $row["sd34_v_descricao"];
             ?>
               <option value="<?=$cod_micro;?>" <?=$cod_micro == @$sd34_i_codigo ? "selected" : ""?>>
                 <?=$desc_micro;?>
               </option>
             <?
             }
             ?>
            </select>
            <?php
            db_input( 'sd34_i_codigo', 20, @$Isd34_i_codigo, true, 'hidden', $db_opcao );
            ?>
          </td>
          <td>
            <b>Familia:</b>
          </td>
          <td>
            <select id="z01_i_familiamicroarea"
                    name="z01_i_familiamicroarea"
                    onchange="if(this.value=='')document.form1.z01_v_micro.value='';">
              <option value=""></option>
            </select>
            <?php
            if( isset( $z01_i_familiamicroarea ) && $z01_i_familiamicroarea != "" ) {
            ?>
              <script>fillSelectFromArray(document.form1.z01_i_familiamicroarea, team[document.form1.z01_v_micro.selectedIndex-1]);</script>
            <?}?>
          </td>
        </tr>
        <!-- CPF / CGS do municipio -->
        <tr>
          <td>
            <?=@$Lz01_v_cgccpf?>
          </td>
          <td>
            <?php
            db_input( 'z01_v_cgccpf', 12, $Iz01_v_cgccpf, true, 'text', $db_opcao );
            ?>
          </td>
          <td>
            <B>CGS do Munic.</B>
          </td>
          <td>
            <?php
            $xz01_c_municipio = array( 'S' => 'SIM', 'N' => 'NÃO' );
            db_select( 'z01_c_municipio', $xz01_c_municipio, true, $db_opcao, "onchange=js_municipio()" );
            ?>
          </td>
        </tr>
        <!--  CEP  -->
        <tr>
          <td>
             <?php
             db_ancora( @$Lz01_v_cep, "js_cepcon(true);", 1 );
             ?>
          </td>
          <td>
            <?php
            db_input(
                      'z01_v_cep',
                      12,
                      $Iz01_v_cep,
                      true,
                      'text',
                      3
                    );
            ?>
           <input type="button"
                  name="buscacep"
                  value="Pesquisar"
                  onClick="js_cepcon(false)" >
          </td>
        </tr>
        <!--  Endereço -->
        <tr>
          <td>
            <?php
            db_ancora( @$Lz01_v_ender, "js_ruas();", $db_opcao );
            ?>
          </td>
          <td colspan="3">
            <?php
            db_input(
                      'z01_v_ender',
                      73,
                      $Iz01_v_ender,
                      true,
                      'text',
                      3
                    );
            ?>
          </td>
        </tr>
        <!--  Número / Complemento -->
        <tr>
          <td>
             <?=@$Lz01_i_numero?>
          </td>
          <td>
            <?php
            db_input( 'z01_i_numero', 12, $Iz01_i_numero, true, 'text', $db_opcao );
            ?>
          </td>
          <td>
            <?=@$Lz01_v_compl?>
          </td>
          <td>
            <?php
            db_input( 'z01_v_compl', 21, $Iz01_v_compl, true, 'text', $db_opcao );
            ?>
          </td>
        </tr>
        <!--  Bairro -->
        <tr>
          <td>
            <?php
            db_ancora( @$Lz01_v_bairro, "js_bairro();", $db_opcao );
            ?>
          </td>
          <td colspan="4">
            <?php
            db_input( 'j13_codi', 10, @$Ij13_codi, true, 'hidden', 3 );
            db_input(
                      'z01_v_bairro',
                      73,
                      $Iz01_v_bairro,
                      true,
                      'text',
                      3
                    );
            ?>
          </td>
        </tr>
        <!--  Municipio / UF -->
        <tr>
          <td>
            <?=@$Lz01_v_munic?>
          </td>
          <td>
            <?php
            db_input( 'z01_v_munic', 30, $Iz01_v_munic, true, 'text', 3 );
            ?>
          </td>
          <td>
            <?=@$Lz01_v_uf?>
          </td>
          <td>
            <?php
            db_input( 'z01_v_uf', 2, $Iz01_v_uf, true, 'text', 3 );
            ?>
          </td>
        </tr>

        <tr style="display: none;">
          <td><?=@$Lz01_codigoibge?></td>
          <td>
            <?php
            db_input( 'z01_codigoibge', 10, $Iz01_codigoibge, true, 'text', 3 );
            ?>
          </td>
        </tr>
        <!--  Telefone / Cartão SUS -->
        <tr>
          <td>
            <?=@$Lz01_v_telef?>
          </td>
          <td>
            <?php
            db_input( 'z01_v_telef', 12, $Iz01_v_telef, true, 'text', $db_opcao );
            ?>
          </td>
          <td>
            <?=@$Ls115_c_cartaosus?>
          </td>
          <td>
            <?php
            db_input( 's115_i_codigo',    15, @$Is115_i_codigo,   true, 'hidden', $db_opcao );
            db_input( 's115_c_cartaosus', 21, $Is115_c_cartaosus, true, 'text',   $db_opcao );

            $x = array( "D" => "D", "P" => "P" );
            db_select( 's115_c_tipo', $x, true, $db_opcao );
            ?>
          </td>
        </tr>
        <!--  Nascimento / Sexo -->
        <tr>
          <td>
            <?=@$Lz01_d_nasc?>
          </td>
          <td>
            <?php
            db_inputdata( 'z01_d_nasc', @$z01_d_nasc_dia, @$z01_d_nasc_mes, @$z01_d_nasc_ano, true, 'text', $db_opcao );
            ?>
          </td>
          <td>
            <?=@$Lz01_v_sexo?>
          </td>
          <td>
            <?php
            $x = array( 'M' => 'MASCULINO', 'F' => 'FEMININO' );
            db_select( 'z01_v_sexo', $x, true, $db_opcao );
            ?>
          </td>
        </tr>
        <!-- Data Cadastro / Login -->
        <tr>
          <td>
            <B>Cadastro:</B>
          </td>
          <td>
            <?php
            db_inputdata( 'z01_d_cadast', @$z01_d_cadast_dia, @$z01_d_cadast_mes, @$z01_d_cadast_ano, true, 'text', 3 );
            ?>
          </td>
          <td>
            <?=@$Lz01_i_login?>
          </td>
          <td>
            <?php
            db_input( 'z01_i_login',  6, $Iz01_i_login, true, 'hidden', 3 );
            db_input( 'nome',        21, @$nome,        true, 'text',   3 );
            ?>
          </td>
        </tr>
        <tr>
          <td class="bold">
             Setor:
          </td>
          <td colspan="3">
            <?php
              $sCampos  = "sd91_codigo,sd91_descricao";
              $sWhere   = "sd91_local = 1 and sd91_unidades = " . db_getsession('DB_coddepto');
              $sSql     = $oDaoSetorAmbulatorial->sql_query_file( null, $sCampos, null, $sWhere );
              $rsSelect = db_query( $sSql );
              db_selectrecord("sd24_setorambulatorial",$rsSelect,true,1,"","","","","",1);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <?php
    $db_opcaoprof = isset( $sd29_i_profissional ) && (int) $sd29_i_profissional != 0 ? 3 : $db_opcao;
    ?>
    <fieldset>
      <legend>Profissional de Atendimento</legend>
      <table>
        <!-- PROFISSIONAL -->
        <tr>
          <td>
            <?php
            db_ancora( @$Lsd03_i_codigo, "js_pesquisasd03_i_codigo(true,1);", $db_opcaoprof );
            ?>
          </td>
          <td>
            <?php
            $sJavaScript = "onchange='js_pesquisasd03_i_codigo(false,1);' onFocus=\"nextfield='rh70_estrutural'\"";
            db_input( 'sd03_i_codigo', 10, $Isd03_i_codigo, true, 'text', $db_opcaoprof, $sJavaScript );
            ?>
          </td>
          <td colspan="2">
            <?php
            db_input( 'z01_nome', 59, $Iz01_nome, true, 'text', 3 );
            ?>
          </td>
        </tr>
        <!-- CBO -->
        <tr>
          <td>
            <?php
            db_ancora( @$Lsd04_i_cbo, "js_pesquisasd04_i_cbo(true,1);", $db_opcaoprof );
            ?>
          </td>
          <td>
            <?php
            db_input( 'sd27_i_codigo',  10, $Isd27_i_codigo,   true, 'hidden', $db_opcaoprof );
            db_input( 'rh70_sequencial',10, $Irh70_sequencial, true, 'hidden', $db_opcaoprof );

            $sJavaScript = "onchange='js_pesquisasd04_i_cbo(false,1);' onFocus=\"nextfield='sd23_d_consulta'\"";
            db_input( 'rh70_estrutural', 10, $Irh70_estrutural, true, 'text', $db_opcaoprof, $sJavaScript );
            ?>
          </td>
          <td colspan="2">
            <?php
            db_input( 'rh70_descr', 59, $Irh70_descr, true, 'text', 3 );
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="hidden" name="<?=( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) )?>">
    <input name="<?=( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) )?>"
           onclick="prosseguir()"
           type="button"
           id="db_opcao"
           value="Prosseguir"
           <?=( $db_botao == false ? "disabled" : "" )?> >
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisaprontuarios();" >
    <input id="btnFinalizar" type="button" value="Finalizar Atendimento">
    <input name="limpar" type="button" id="limpar" value="Nova FAA" onclick="js_limpa()">
  </form>
</div>
<script>

var lObrigarCNS = <?=$lObrigarCNS ? 1 : 0;?>

const MENSAGEM_FRMFICHAATENDCGS_UND = "saude.ambulatorial.db_frmfichaatendcgs_und.";

function js_pesquisasd03_i_codigo( mostra ) {

  if( mostra == true ) {

    js_OpenJanelaIframe(
                         '',
                         'db_iframe_medicos',
                         'func_medicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome'
                                        +'&chave_sd06_i_unidade='+document.form1.sd24_i_unidade.value,
                         'Pesquisa',
                         true
                       );
  } else {

    if( document.form1.sd03_i_codigo.value != '' ) {

      js_OpenJanelaIframe(
                           '',
                           'db_iframe_medicos',
                           'func_medicos.php?pesquisa_chave='+document.form1.sd03_i_codigo.value
                                          +'&funcao_js=parent.js_mostramedicos'
                                          +'&chave_sd06_i_unidade='+document.form1.sd24_i_unidade.value,
                           'Pesquisa',
                           false
                         );
    } else {
      document.form1.z01_nome.value = '';
    }
  }
}

function js_mostramedicos( chave, erro ) {

  document.form1.z01_nome.value = chave;

  if( erro == true ) {

    document.form1.sd03_i_codigo.focus();
    document.form1.sd03_i_codigo.value   = '';
    document.form1.sd27_i_codigo.value   = '';
    document.form1.rh70_estrutural.value = '';
    document.form1.rh70_descr.value      = '';
  } else {
    js_pesquisasd04_i_cbo(true);
  }
}

function js_mostramedicos1( chave1, chave2 ) {

  document.form1.sd03_i_codigo.value = chave1;
  document.form1.z01_nome.value      = chave2;
  db_iframe_medicos.hide();
  js_pesquisasd04_i_cbo(true);
}

function js_pesquisasd04_i_cbo( mostra ) {

  if( mostra == true ) {

    js_OpenJanelaIframe(
                         '',
                         'db_iframe_especmedico',
                         'func_especmedico.php?funcao_js=parent.js_mostrarhcbo1|sd27_i_codigo|rh70_estrutural'
                                                                             +'|rh70_descr|sd27_i_rhcbo'
                                            +'&chave_sd04_i_unidade='+document.form1.sd24_i_unidade.value
                                            +'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,
                         'Pesquisa',
                         true
                       );
  } else {

    if( document.form1.rh70_estrutural.value != '' ) {

      js_OpenJanelaIframe(
                           '',
                           'db_iframe_especmedico',
                           'func_especmedico.php?chave_rh70_estrutural='+document.form1.rh70_estrutural.value
                                              +'&funcao_js=parent.js_mostrarhcbo1|sd27_i_codigo|rh70_estrutural'
                                                                               +'|rh70_descr|sd27_i_rhcbo'
                                              +'&chave_sd04_i_unidade='+document.form1.sd24_i_unidade.value
                                              +'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,
                           'Pesquisa',
                           false
                         );
      document.form1.rh70_estrutural.value = '';
      document.form1.rh70_descr.value      = '';
    } else {
      document.form1.rh70_estrutural.value = '';
    }
  }
}

function js_mostrarhcbo( erro, chave1, chave2, chave3, chave4 ) {

  document.form1.rh70_descr.value      = chave1;
  document.form1.rh70_estrutural.value = chave2;
  document.form1.sd27_i_codigo.value   = chave3;
  document.form1.rh70_sequencial.value = chave4;

  if( erro == true ) {

    document.form1.rh70_estrutural.focus();
    document.form1.rh70_estrutural.value = '';
  }
}

function js_mostrarhcbo1( chave1, chave2, chave3, chave4 ) {

  document.form1.sd27_i_codigo.value   = chave1;
  document.form1.rh70_estrutural.value = chave2;
  document.form1.rh70_descr.value      = chave3;
  document.form1.rh70_sequencial.value = chave4;

  db_iframe_especmedico.hide();

  if( chave2 == '' ) {

    document.form1.rh70_estrutural.focus();
    document.form1.rh70_estrutural.value = '';
  }
}

function js_pesquisasd23_i_codigo( mostra ) {

  if( mostra == true ) {

    js_OpenJanelaIframe(
                         '',
                         'db_iframe_prontagendamento',
                         'func_prontagendamento.php?funcao_js=parent.js_mostraagendamento1|dl_FAA|dl_Agenda|sd23_i_numcgs',
                         'Pesquisa',
                         true
                       );
  } else {

    if( document.form1.sd24_i_unidade.value == '' ) {
      document.form1.descrdepto.value = '';
    }
  }
}

function js_mostraagendamento1( faa, agenda, cgs ) {

  db_iframe_prontagendamento.hide();

  parent.document.formaba.a4.disabled = true;
  parent.document.formaba.a3.disabled = true;
  parent.document.formaba.a2.disabled = true;

  if( faa != "" ) {
    location.href ='<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?chavepesquisaprontuario='+faa+'&triagem='+'<?=@$triagem?>';
  } else if( agenda != "" ) {
    location.href ='<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?chavepesquisaagenda='+agenda+'&triagem='+'<?=@$triagem?>';
  } else {
    location.href ='<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?chavepesquisacgs='+cgs+'&triagem='+'<?=@$triagem?>';
  }
}

function js_ruas() {

  js_OpenJanelaIframe(
                       '',
                       'db_iframe_ruas',
                       'func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisaruas|j14_codigo|j14_nome',
                       'Pesquisa',
                       true
                     );
}

function js_preenchepesquisaruas( chave, chave1 ) {

  document.form1.z01_v_ender.value = chave1;
  db_iframe_ruas.hide();
}

function js_bairro() {

  js_OpenJanelaIframe(
                       '',
                       'db_iframe_bairro',
                       'func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro|j13_codi|j13_descr',
                       'Pesquisa',
                       true
                     );
}

function js_preenchebairro( chave, chave1 ) {

  document.form1.j13_codi.value     = chave;
  document.form1.z01_v_bairro.value = chave1;
  db_iframe_bairro.hide();
}

function js_ruas1() {

  js_OpenJanelaIframe(
                       '',
                       'db_iframe_ruas1',
                       'func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisaruas1|j14_codigo|j14_nome',
                       'Pesquisa',
                       true
                     );
}

function js_preenchepesquisaruas1( chave, chave1 ) {

  document.form1.z01_v_endcon.value = chave1;
  db_iframe_ruas1.hide();
}

function js_bairro1() {

  js_OpenJanelaIframe(
                       '',
                       'db_iframe_bairro1',
                       'func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro1|j13_codi|j13_descr',
                       'Pesquisa',
                       true
                     );
}

function js_preenchebairro1( chave, chave1 ) {

  document.form1.z01_v_baicon.value = chave1;
  db_iframe_bairro1.hide();
}

function js_pesquisasd24_i_unidade( mostra ) {

  if( mostra == true ) {

    js_OpenJanelaIframe(
                         '',
                         'db_iframe_unidades','func_unidades.php?funcao_js=parent.js_mostraunidades1|sd02_i_codigo|descrdepto',
                         'Pesquisa',
                         true
                       );
  } else {

    if( document.form1.sd24_i_unidade.value != '' ) {

      js_OpenJanelaIframe(
                           '',
                           'db_iframe_unidades',
                           'func_unidades.php?pesquisa_chave='+document.form1.sd24_i_unidade.value
                                           +'&funcao_js=parent.js_mostraunidades',
                           'Pesquisa',
                           false
                         );
    } else {
      document.form1.descrdepto.value = '';
    }
  }
}

function js_mostraunidades( chave, erro ) {

  document.form1.descrdepto.value = chave;

  if( erro == true ) {

    document.form1.sd24_i_unidade.focus();
    document.form1.sd24_i_unidade.value = '';
  }
}

function js_mostraunidades1( chave1, chave2 ) {

  document.form1.sd24_i_unidade.value = chave1;
  document.form1.descrdepto.value     = chave2;
  db_iframe_unidades.hide();
}

function js_pesquisaz01_i_cgsund( mostra ) {

  if( mostra == true ) {

    js_OpenJanelaIframe(
                         '',
                         'db_iframe_cgs_und',
                         'func_cgs_und.php?funcao_js=parent.js_preenchecgs|z01_i_cgsund'
                                        +'&retornacgs=p.p.document.form1.z01_i_cgsund.value'
                                        +'&retornanome=p.p.js_preenchecgs(p.p.document.form1.z01_i_cgsund.value);'
                                                     +'p.p.document.form1.z01_v_nome.value',
                         'Pesquisa',
                         true
                       );
  } else {

    if( document.form1.z01_i_cgsund.value != '' ) {

      js_OpenJanelaIframe(
                           '',
                           'db_iframe_cgs_und',
                           'func_cgs_und.php?funcao_js=parent.js_preenchecgs|z01_i_cgsund'
                                          +'&retornacgs=p.p.document.form1.z01_i_cgsund.value'
                                          +'&retornanome=p.p.js_preenchecgs(p.p.document.form1.z01_i_cgsund.value);'
                                                       +'p.p.document.form1.z01_v_nome.value'
                                          +'&chave_z01_i_cgsund='+document.form1.z01_i_cgsund.value,
                           'Pesquisa',
                           true
                         );
    } else {
      document.form1.z01_i_numcgs.value = '';
    }
  }
}

function js_mostracgs( erro, chave ) {

  document.form1.z01_v_nome.value = chave;

  if( erro == true ) {

    document.form1.z01_i_cgsund.focus();
    document.form1.z01_v_nome.value = '';
  }
}

function js_mostracgs1( chave1, chave2 ) {

  document.form1.z01_i_cgsund.value = chave1;
  document.form1.z01_i_numcgs.value = chave2;
  db_iframe_cgs.hide();
}

<?php
if( isset( $triagem ) && $triagem == "false" ) {
?>
  function js_pesquisaprontuarios() {

    js_OpenJanelaIframe(
                         '',
                         'db_iframe_prontuarios002',
                         'func_prontuarios_novo.php?funcao_js=parent.js_preenchepesquisa|sd24_i_codigo&lFiltraMovimentados=false',
                         'Pesquisa',
                         true
                       );
  }
<?php
} else {
?>
  function js_pesquisaprontuarios() {

    js_OpenJanelaIframe(
                         '',
                         'db_iframe_prontuarios',
                         'func_prontuarios_novo.php?funcao_js=parent.js_preenchepesquisa|sd24_i_codigo&lFiltraMovimentados=false',
                         'Pesquisa',
                         true
                       );
  }
<?}?>

function js_preenchecgs( chave ) {

  db_iframe_cgs_und.hide();

  parent.document.formaba.a4.disabled = true;
  parent.document.formaba.a3.disabled = true;
  parent.document.formaba.a2.disabled = true;

  location.href ='<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?chavepesquisacgs='+chave+'&triagem='+'<?=@$triagem?>';
}

<?php
if( isset( $triagem ) && $triagem == "false" ) {
?>
  function js_preenchepesquisa( chave ) {

    db_iframe_prontuarios002.hide();
    location.href ='<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?chavepesquisaprontuario='+chave+'&triagem='+'<?=@$triagem?>';
  }
<?php
} else {
?>
  function js_preenchepesquisa( chave ) {

    db_iframe_prontuarios.hide();
    location.href ='<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?chavepesquisaprontuario='+chave+'&triagem='+'<?=@$triagem?>';
  }
<?}?>

function js_limpa() {

  parent.document.formaba.a4.disabled = true;
  parent.document.formaba.a3.disabled = true;
  parent.document.formaba.a2.disabled = true;

  <?php
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."'";
  ?>
}

function js_cepcon( abre ) {

  if( abre == true ) {

    js_OpenJanelaIframe(
                         "",
                         'db_iframe_cep',
                         'func_cep.php?funcao_js=parent.js_preenchecepcon|cep|cp06_logradouro|cp05_localidades'
                                                                       +'|cp05_sigla|cp01_bairro',
                         'Pesquisa',
                         true
                       );
  } else {

    js_OpenJanelaIframe(
                         "",
                         'db_iframe_cep',
                         'func_cep.php?pesquisa_chave='+document.form1.z01_v_cep.value
                                    +'&funcao_js=parent.js_preenchecepcon|cep|cp06_logradouro|cp05_localidades'
                                                                       +'|cp05_sigla|cp01_bairro',
                         'Pesquisa',
                         false
                       );
  }
}

function js_preenchecepcon( chave, chave1, chave2, chave3, chave4 ) {

  document.form1.z01_v_cep.value    = chave;
  document.form1.z01_v_ender.value  = chave1;
  document.form1.z01_v_munic.value  = chave2;
  document.form1.z01_v_uf.value     = chave3;
  document.form1.z01_v_bairro.value = chave4;
  db_iframe_cep.hide();
}

function js_anular() {

  if( document.form1.sd24_i_codigo.value == "" ) {
    alert( "FAA não informada!" );
  } else {

    iTop  = ( screen.availHeight-600 ) / 2;
    iLeft = ( screen.availWidth-600 ) / 2;

    if( document.form1.anular.value == "Anular FAA" ) {

      js_OpenJanelaIframe(
                           '',
                           'db_iframe_prontanulado',
                           'sau1_prontanulado001.php?chavepesquisaprontuario='+document.form1.sd24_i_codigo.value,
                           'Anular FAA',
                           true,
                           iTop,
                           iLeft,
                           600,
                           210
                         );
    } else {

      js_OpenJanelaIframe(
                           '',
                           'db_iframe_prontanulado',
                           'sau1_prontanulado003.php?chavepesquisa='+document.form1.sd24_i_codigo.value,
                           'Anular FAA',
                           true,
                           iTop,
                           iLeft,
                           600,
                           210
                         );
    }
  }
}

function js_municipio() {

  if( document.form1.z01_i_cgsund.value != "" ) {
     location.href ='<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?chavepesquisacgs='+document.form1.z01_i_cgsund.value
                                                                          +'&chavepesquisaprontuario='+document.form1.sd24_i_codigo.value
                                                                          +'&chavepesquiamunicipio='+document.form1.z01_c_municipio.value;
  } else {

    query  = '?chavepesquiamunicipio='+document.form1.z01_c_municipio.value;
    query += '&z01_v_nome='+document.form1.z01_v_nome.value;
    query += '&sd34_i_codigo='+document.form1.z01_v_micro.value;
    query += '&z01_v_cgccpf='+document.form1.z01_v_cgccpf.value;
    location.href ='<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+query;
  }
}

if( $('sd23_i_codigo') ) {
  $('sd23_i_codigo').className          = 'field-size-max';
}

$('sd24_i_codigo').className            = 'field-size2';
$('sd24_i_unidade').className           = 'field-size2';
$('z01_i_cgsund').className             = 'field-size2';
$('z01_v_micro').className              = 'field-size-max';
$('z01_i_familiamicroarea').className   = 'field-size-max';
$('z01_v_cgccpf').className             = 'field-size-max';
$('z01_c_municipio').className          = 'field-size-max';
$('z01_v_cep').className                = 'field-size2';
$('z01_v_ender').className              = 'field-size-max';
$('z01_i_numero').className             = 'field-size2';
$('z01_v_compl').className              = 'field-size-max';
$('z01_v_bairro').className             = 'field-size-max';
$('z01_v_munic').className              = 'field-size-max';
$('z01_codigoibge').className           = 'field-size2';
$('z01_v_uf').className                 = 'field-size2';
$('z01_v_telef').className              = 'field-size2';
$('s115_c_cartaosus').className         = 'field-size4';
$('s115_c_tipo').className              = 'field-size1';
$('z01_d_nasc').className               = 'field-size2';
$('z01_v_sexo').className               = 'field-size-max';
$('z01_d_cadast').className             = 'field-size2';
$('nome').className                     = 'field-size-max';
$('sd24_setorambulatorial').className   = 'field-size-max';

$('sd03_i_codigo').className   = 'field-size2';
$('z01_nome').className        = 'field-size-max';
$('rh70_estrutural').className = 'field-size2';
$('rh70_descr').className      = 'field-size-max';

$('descrdepto').size = 61;
$('z01_v_nome').size = 61;

$('s115_c_cartaosus').onkeyup = function() {
  $('s115_c_cartaosus').value = $F('s115_c_cartaosus').somenteNumeros();
}

$('s115_c_cartaosus').onkeydown = function() {
  $('s115_c_cartaosus').value = $F('s115_c_cartaosus').somenteNumeros();
}

$('s115_c_cartaosus').onchange = function() {

  if( !$F('s115_c_cartaosus').validaCNS() ) {

    alert( 'Número do cartão do SUS inválido' );
    $('s115_c_cartaosus').value = '';
    return false;
  }

  return true;
};

/**
 * Mostra a janela contendo os motivos de alta para finalizar o prontuário.
 */
$('btnFinalizar').onclick = function() {

  if ( empty($F('sd24_i_codigo')) ) {

    alert( _M( MENSAGEM_FRMFICHAATENDCGS_UND + 'informe_prontuario' ) );
    return;
  }

  var fRedireciona = function() {

    parent.document.formaba.a2.disabled = true;
    parent.document.formaba.a3.disabled = true;
    parent.document.formaba.a4.disabled = true;
    location.href='sau4_fichaatendabas001.php?';
  };

  var oViewMotivosAlta = new DBViewMotivosAlta();
      oViewMotivosAlta.setProntuario( $F('sd24_i_codigo') );
      oViewMotivosAlta.setCallbackSalvar(fRedireciona);
      oViewMotivosAlta.show();
};

/**
 * Valida se o CGS foi informado antes de prosseguir
 */
function prosseguir() {

  if ( empty($F('z01_i_cgsund')) ) {

    alert( _M( MENSAGEM_FRMFICHAATENDCGS_UND + 'informe_cgs' ) );
    return;
  }

  if (lObrigarCNS && empty($F('s115_c_cartaosus')) ) {

    alert( _M( MENSAGEM_FRMFICHAATENDCGS_UND + 'informe_cns' ) );
    return;
  }

  $('frmPaciente').submit();
}

</script>