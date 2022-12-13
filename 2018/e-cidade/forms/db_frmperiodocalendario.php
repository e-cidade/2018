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
include(modification("dbforms/db_classesgenericas.php"));

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clperiodocalendario->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("ed52_i_codigo");
$clrotulo->label("ed09_i_codigo");
$clrotulo->label("ed52_c_descr");
$clrotulo->label("ed09_c_descr");

$db_botao1 = false;

$ed53_d_inicio_dia = isset( $ed53_d_inicio ) && !empty( $ed53_d_inicio ) ? substr( $ed53_d_inicio, 0, 2 ) : "";
$ed53_d_inicio_mes = isset( $ed53_d_inicio ) && !empty( $ed53_d_inicio ) ? substr( $ed53_d_inicio, 3, 2 ) : "";
$ed53_d_inicio_ano = isset( $ed53_d_inicio ) && !empty( $ed53_d_inicio ) ? substr( $ed53_d_inicio, 6, 4 ) : "";

$ed53_d_fim_dia = isset( $ed53_d_fim ) && !empty( $ed53_d_fim ) ? substr( $ed53_d_fim, 0, 2 ) : "";
$ed53_d_fim_mes = isset( $ed53_d_fim ) && !empty( $ed53_d_fim ) ? substr( $ed53_d_fim, 3, 2 ) : "";
$ed53_d_fim_ano = isset( $ed53_d_fim ) && !empty( $ed53_d_fim ) ? substr( $ed53_d_fim, 6, 4 ) : "";

if( isset( $opcao ) && $opcao == "alterar" ) {

  $db_opcao  = 2;
  $db_opcao1 = 3;
  $db_botao1 = true;
} else if( isset( $opcao ) && $opcao == "excluir" || isset( $db_opcao ) && $db_opcao == 3 ) {

  $db_botao1 = true;
  $db_opcao  = 3;
  $db_opcao1 = 3;
} else {

  if( isset( $alterar ) )  {

    $db_opcao  = 2;
    $db_botao1 = true;
  } else {

    $db_opcao = 1;
    $opcao    = "incluir";
  }
}

$sCampos = "sum(ed53_i_diasletivos) as dias,sum(ed53_i_semletivas) as semanas";
$sWhere  = " ed53_i_calendario = {$ed53_i_calendario} AND ed09_c_somach = 'S'";
$sql1    = $clperiodocalendario->sql_query( "", $sCampos, "", $sWhere );
$result1 = $clperiodocalendario->sql_record($sql1);

db_fieldsmemory( $result1, 0 );

if( $dias == "" ) {

  $dias    = 0;
  $semanas = 0;
}

$sql    = $clcalendario->sql_query( "", "ed52_c_aulasabado", "", " ed52_i_codigo = {$ed53_i_calendario}" );
$result = $clcalendario->sql_record($sql);

db_fieldsmemory( $result, 0 );

$sWhereRegencia = " ed57_i_calendario = {$ed53_i_calendario} AND ed59_c_encerrada = 'S' AND ed59_c_condicao = 'OB'";
$sSqlRegencia   = $clregencia->sql_query( "", "ed59_i_codigo", "", $sWhereRegencia );
$result         = $clregencia->sql_record( $sSqlRegencia );

if( $clregencia->numrows > 0 ) {

  $db_botao = false;
  $opcoes   = 4;
} else {

  $db_botao = true;
  $opcoes   = 1;
}
?>
<form name="form1" id="frmPeriodosAvaliacaoCalendario" method="post" action="">
  <center>
  <table border="0">
    <tr>
      <td nowrap title="<?=$Ted53_i_codigo?>">
        <label><?=$Led53_i_codigo?></label>
      </td>
      <td>
        <?php
        db_input( 'ed53_i_codigo', 15, $Ied53_i_codigo, true, 'text', 3 );
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Ted53_i_calendario?>">
        <label>
          <?php
          db_ancora( $Led53_i_calendario, "", 3 );
          ?>
        </label>
      </td>
      <td>
        <?php
        db_input( 'ed53_i_calendario', 15, $Ied53_i_calendario, true, 'text', 3 );
        db_input( 'ed52_c_descr',      20, $Ied52_c_descr,     true, 'text', 3 );
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Ted53_i_periodoavaliacao?>">
        <label>
          <?php
          db_ancora( $Led53_i_periodoavaliacao, "js_pesquisaed53_i_periodoavaliacao(true);", $db_opcao1 );
          ?>
        </label>
      </td>
      <td>
        <?php
        $sChange = " onchange='js_pesquisaed53_i_periodoavaliacao(false);'";
        db_input( 'ed53_i_periodoavaliacao', 15, $Ied53_i_periodoavaliacao, true, 'text', $db_opcao1, $sChange );
        db_input( 'ed09_c_descr',            40, $Ied09_c_descr,           true, 'text', 3 );
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Ted53_d_inicio?>">
        <label><?=$Led53_d_inicio?></label>
      </td>
      <td>
        <?php
        db_inputdata(
                      'ed53_d_inicio',
                      $ed53_d_inicio_dia,
                      $ed53_d_inicio_mes,
                      $ed53_d_inicio_ano,
                      true,
                      'text',
                      $db_opcao,
                      " onchange=\"js_calculardiasletivos();\"","","","parent.js_calculardiasletivos();"
                    );
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Ted53_d_fim?>">
        <label><?=$Led53_d_fim?></label>
      </td>
      <td>
        <?php
        db_inputdata(
                      'ed53_d_fim',
                      $ed53_d_fim_dia,
                      $ed53_d_fim_mes,
                      $ed53_d_fim_ano,
                      true,
                      'text',
                      $db_opcao,
                      " onchange='js_calculardiasletivos();'","","", "parent.js_calculardiasletivos();"
                    );
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Ted53_i_diasletivos?>">
        <label><?=$Led53_i_diasletivos?></label>
      </td>
      <td>
        <?php
        db_input( 'ed53_i_diasletivos', 10, $Ied53_i_diasletivos, true, 'text', 3 );
        echo $Led53_i_semletivas;
        db_input( 'ed53_i_semletivas', 10, $Ied53_i_semletivas, true, 'text', 3 );
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Ted53_i_diasletivos?>">
        <label class="bold">Total Dias Calendário:</label>
      </td>
      <td>
        <?php
        db_input( 'dias', 10, 'Total Dias Calendário', true, 'text', 3 );
        ?>
        <label class="bold">Total Semanas Calendário:</label>
        <?php
        db_input( 'semanas', 10, 'Total Semanas Calendário', true, 'text', 3 );
        ?>
      </td>
    </tr>
  </table>
  <input name="ed53_i_calendario" type="hidden" value="<?=$ed53_i_calendario?>">
  <input name="<?=( $db_opcao == 1?  "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) )?>"
         type="submit"
         id="db_opcao"
         value="<?=( $db_opcao == 1 ? "Incluir" : ( $db_opcao == 2|| $db_opcao == 22 ? "Alterar" : "Excluir" ) )?>"
         <?=( $db_botao == false ? "disabled" : "" )?>
  />
  <input name="cancelar" type="submit" value="Cancelar" <?=( $db_botao1 == false ? "disabled" : "" )?> >
  <table width="100%">
    <tr>
      <td valign="top">
        <?php
        $chavepri= array(
                          "ed53_i_codigo"           => @$ed53_i_codigo,
                          "ed53_i_periodoavaliacao" => @$ed53_i_periodoavaliacao,
                          "ed09_c_descr"            => @$ed09_c_descr,
                          "ed53_d_inicio"           => @$ed53_d_inicio,
                          "ed53_d_fim"              => @$ed53_d_fim,
                          "ed53_i_diasletivos"      => @$ed53_i_diasletivos,
                          "ed53_i_semletivas"       => @$ed53_i_semletivas
                        );

        $sCampos = "ed09_c_descr, ed53_d_inicio, ed53_d_fim, ed53_i_diasletivos, ed53_i_semletivas";
        $sLabels = "ed53_i_periodoavaliacao, ed53_d_inicio, ed53_d_fim, ed53_i_diasletivos, ed53_i_semletivas";

        $cliframe_alterar_excluir->chavepri      = $chavepri;
        @$cliframe_alterar_excluir->sql          = $clperiodocalendario->sql_query(
                                                                                    "",
                                                                                    "*",
                                                                                    "ed09_i_sequencia",
                                                                                    "ed53_i_calendario = {$ed53_i_calendario}"
                                                                                  );
        $cliframe_alterar_excluir->campos        = $sCampos;
        $cliframe_alterar_excluir->labels        = $sLabels;
        $cliframe_alterar_excluir->legenda       = "Registros";
        $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
        $cliframe_alterar_excluir->textocabec    = "#DEB887";
        $cliframe_alterar_excluir->textocorpo    = "#444444";
        $cliframe_alterar_excluir->fundocabec    = "#444444";
        $cliframe_alterar_excluir->fundocorpo    = "#eaeaea";
        $cliframe_alterar_excluir->iframe_height = "100";
        $cliframe_alterar_excluir->iframe_width  = "100%";
        $cliframe_alterar_excluir->tamfontecabec = 9;
        $cliframe_alterar_excluir->tamfontecorpo = 9;
        $cliframe_alterar_excluir->opcoes        = $opcoes;
        $cliframe_alterar_excluir->formulario    = false;
        $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
        ?>
      </td>
    </tr>
   <tr>
     <td>
      <iframe src=""
              name="iframe_datas"
              id="iframe_datas"
              width="0"
              height="0"
              style="visibility:hidden;"
              frameborder="0"></iframe>
     </td>
   </tr>
  </table>
  </center>
</form>
<script>
function js_pesquisaed53_i_periodoavaliacao( mostra ) {

  if( mostra == true ) {
    js_OpenJanelaIframe(
                         '',
                         'db_iframe_periodoavaliacao',
                         'func_periodoavaliacao.php?calendario=<?=@$ed53_i_calendario?>'
                                                 +'&funcao_js=parent.js_mostraperiodoavaliacao1|ed09_i_codigo|ed09_c_descr',
                         'Pesquisa Períodos de Avaliação',
                         true
                       );
  } else {

    if( document.form1.ed53_i_periodoavaliacao.value != '' ) {
      js_OpenJanelaIframe(
                           '',
                           'db_iframe_periodoavaliacao',
                           'func_periodoavaliacao.php?calendario=<?=@$ed53_i_calendario?>'
                                                   +'&pesquisa_chave=' + document.form1.ed53_i_periodoavaliacao.value
                                                   +'&funcao_js=parent.js_mostraperiodoavaliacao',
                           'Pesquisa',
                           false
                         );
    } else {
      document.form1.ed09_c_descr.value = '';
    }
  }
}

function js_mostraperiodoavaliacao( chave, erro ) {

  document.form1.ed09_c_descr.value   = chave;
  document.form1.ed53_d_fim_dia.value = "";
  document.form1.ed53_d_fim_mes.value = "";
  document.form1.ed53_d_fim_ano.value = "";

  if( erro == true ) {

    document.form1.ed53_i_periodoavaliacao.focus();
    document.form1.ed53_i_periodoavaliacao.value = '';
  }
}

function js_mostraperiodoavaliacao1( chave1, chave2 ) {

  document.form1.ed53_i_periodoavaliacao.value = chave1;
  document.form1.ed09_c_descr.value            = chave2;
  document.form1.ed53_d_fim_dia.value          = "";
  document.form1.ed53_d_fim_mes.value          = "";
  document.form1.ed53_d_fim_ano.value          = "";
  db_iframe_periodoavaliacao.hide();
}

function js_calculardiasletivos() {

  if( document.form1.ed53_i_periodoavaliacao.value == "" ) {

    alert("Preencha o período de avaliação!");

    document.form1.ed53_i_periodoavaliacao.style.backgroundColor = '#99A9AE';
    document.form1.ed53_i_periodoavaliacao.focus();
    document.form1.ed53_d_inicio_dia.value = "";
    document.form1.ed53_d_inicio_mes.value = "";
    document.form1.ed53_d_inicio_ano.value = "";
    document.form1.ed53_d_fim_dia.value    = "";
    document.form1.ed53_d_fim_mes.value    = "";
    document.form1.ed53_d_fim_ano.value    = "";
  } else {

    if (document.form1.ed53_d_fim_ano.value != "" ) {

      d1 = document.form1.ed53_d_inicio_dia.value;
      m1 = document.form1.ed53_d_inicio_mes.value;
      a1 = document.form1.ed53_d_inicio_ano.value;
      d2 = document.form1.ed53_d_fim_dia.value;
      m2 = document.form1.ed53_d_fim_mes.value;
      a2 = document.form1.ed53_d_fim_ano.value;

      if( d1 == "" || m1 == "" || a1 == "" || d2 == "" || m2 == "" || a2 == "" ) {
        alert("Preencha todos os campos das datas!");
      } else {

        data_inicio   = a1+"-"+m1+"-"+d1;
        data_fim      = a2+"-"+m2+"-"+d2;
        dias_per      = document.form1.ed53_i_diasletivos.value;
        semanas_per   = document.form1.ed53_i_semletivas.value;
        total_dias    = document.form1.dias.value;
        total_semanas = document.form1.semanas.value;

        iframe_datas.location.href = "edu1_periodocalendario004.php?sabado=<?=$ed52_c_aulasabado?>"
                                                                 +"&periodo="+document.form1.ed53_i_periodoavaliacao.value
                                                                 +"&calendario=<?=$ed53_i_calendario?>"
                                                                 +"&data_inicio="+data_inicio
                                                                 +"&data_fim="+data_fim
                                                                 +"&total_dias="+total_dias
                                                                 +"&total_semanas="+total_semanas
                                                                 +"&opcao=<?=$opcao?>"
                                                                 +"&dias_per="+dias_per
                                                                 +"&semanas_per="+semanas_per;

        validaDatasPeriodoAvaliacao();
      }
    }
  }
}

$('ed53_d_inicio').observe('blur', function(){
  js_calculardiasletivos();
});

$('ed53_d_inicio').observe('keyup', function(){
  $('db_opcao').setAttribute('disabled', 'disabled');
});

$('ed53_d_fim').observe('blur', function(){
  js_calculardiasletivos();
});

$('ed53_d_fim').observe('keyup', function(){
  $('db_opcao').setAttribute('disabled', 'disabled');
});

document.observe("dom:loaded", function() {
  validaDatasPeriodoAvaliacao();
});

function validaDatasPeriodoAvaliacao() {

  var oInputDataInicial = $('ed53_d_inicio'),
      oInputDataFinal   = $('ed53_d_fim'),
      oBtnSubmit        = $('db_opcao');

  oBtnSubmit.setAttribute('disabled', 'disabled');

  if ( oInputDataInicial.value != '' && oInputDataFinal.value != '') {
    oBtnSubmit.removeAttribute('disabled');
  }
}

</script>