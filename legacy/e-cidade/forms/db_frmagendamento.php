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

//MODULO: saude
$clagendamentos->rotulo->label();
$clrotulo = new rotulocampo;

//Médico
$clrotulo->label("sd03_i_codigo");
$clrotulo->label("z01_nome");

//Unidades
$clrotulo->label("sd02_i_codigo");
$clrotulo->label("sd02_c_centralagenda");
$clrotulo->label("descrdepto");

//Unidade / Medicos
$clrotulo->label("sd04_i_cbo");
//undmedhorario
$clundmedhorario->rotulo->label();
//especmedico
$clrotulo->label("sd27_i_codigo");

//Procedimento
$clrotulo->label("s125_i_procedimento");
$clrotulo->label ( "sd63_c_procedimento" );
$clrotulo->label ( "sd63_c_nome" );
$clrotulo->label("sd29_i_procedimento");

//CBO
$clrotulo->label("rh70_sequencial");
$clrotulo->label("rh70_estrutural");
$clrotulo->label("rh70_descr");
$clrotulo->label("s165_formatocomprovanteagend");
?>

<form name="form1" method="post">
  <table>
    <tr>
      <td>
        <fieldset>
          <legend>Agendamento</legend>
          <table>
            <tr>
              <td valign="top">
                <fieldset>
                  <legend>Profissional</legend>
                  <table>
                    <!-- UPS -->
                    <tr>
                      <td nowrap title="<?=$Tsd02_i_codigo?>" >
                        <label for="sd02_i_codigo">
                          <?php
                          db_ancora($Lsd02_i_codigo, "pesquisaUnidade(true);", $db_opcao_cotas);
                          ?>
                        </label>
                      </td>
                      <td>
                        <?php
                        $sScript = "onchange = 'pesquisaUnidade(false);'";
                        db_input('sd02_i_codigo', 10, $Isd02_i_codigo, true, 'text', $db_opcao_cotas, $sScript);
                        ?>
                      </td>
                      <td colspan="2">
                        <?php
                        db_input('descrdepto', 49, $Idescrdepto, true, 'text', 3, '');
                        ?>
                      </td>
                    </tr>

                    <!-- CBO -->
                    <tr>
                      <td nowrap title="<?=$Tsd04_i_cbo?>">
                        <label for="rh70_estrutural">
                          <?php
                          db_ancora($Lsd04_i_cbo, "pesquisaEspecialidade(true, false);", $db_opcao);
                          ?>
                        </label>
                      </td>
                      <td>
                        <?php
                        $sScript = " onchange='pesquisaEspecialidade(false, false);' onFocus=\"nextfield='sd03_i_codigo'\"";
                        db_input('sd02_c_centralagenda',  1, $Isd02_c_centralagenda, true, 'hidden', $db_opcao, "");
                        db_input('sd27_i_codigo',        10, $Isd27_i_codigo,        true, 'hidden', $db_opcao, "");
                        db_input('upssolicitante',       10, $upssolicitante,        true, 'hidden', $db_opcao, "");
                        db_input('rh70_sequencial',      10, $Irh70_sequencial,      true, 'hidden', $db_opcao, "");
                        db_input('rh70_estrutural',      10, $Irh70_estrutural,      true, 'text',   $db_opcao, $sScript);
                        ?>
                      </td>
                      <td colspan="2">
                        <?php
                        db_input('rh70_descr', 49, $Irh70_descr, true, 'text', 3, '');
                        ?>
                      </td>
                    </tr>

                    <!-- PROFISSIONAL -->
                    <tr>
                      <?php
                      $db_opcaoprof = $sd02_c_centralagenda == "S" ? 3 : $db_opcao;
                      ?>
                      <td nowrap title="<?=$Tsd03_i_codigo?>" >
                        <label for="sd03_i_codigo">
                          <?php
                          db_ancora($Lsd03_i_codigo, "pesquisaProfissional(true);", $db_opcaoprof);
                          ?>
                        </label>
                      </td>
                      <td valing="top" align="top">
                        <?php
                        $sScript  = " onchange='pesquisaProfissional(false);' ";
                        $sScript .= "onFocus=\"nextfield='sd23_d_consulta'\"";
                        db_input('sd03_i_codigo', 10, $Isd03_i_codigo, true, 'text', $db_opcaoprof, $sScript);
                        ?>
                      </td>
                      <td colspan="2">
                        <?php
                        db_input('z01_nome', 49, $Iz01_nome, true, 'text', 3, '');
                        ?>
                      </td>
                    </tr>
                    <?php
                    if( $booProced ) {
                      ?>
                      <!-- PROCEDIMENTO -->
                      <tr>
                        <td nowrap title="<?=$Tsd29_i_procedimento?>">
                          <label for="sd63_c_procedimento">
                            <?php
                            db_ancora($Ls125_i_procedimento, "js_pesquisas125_i_procedimento(true);", $db_opcao );
                            ?>
                          </label>
                        </td>
                        <td nowrap>
                          <?php
                          $sScript = " onchange='js_pesquisas125_i_procedimento(false);' ";
                          db_input('s125_i_procedimento', 10, $Is125_i_procedimento, true, 'hidden', $db_opcao, "");
                          db_input('sd63_c_procedimento', 10, $Isd63_c_procedimento, true, 'text',   $db_opcao, $sScript);
                          ?>
                        </td>
                        <td>
                          <?php
                          db_input('sd63_c_nome', 49, $Isd63_c_nome, true, 'text', 3, '');
                          ?>
                        </td>
                      </tr>
                    <?php
                    }
                    ?>

                    <!-- Data Consulta -->
                    <tr>
                      <td nowrap title="<?=$Tsd23_d_consulta?>">
                        <label for="sd23_d_consulta">
                          <?=$Lsd23_d_consulta?>
                        </label>
                      </td>
                      <td>
                        <?php
                        $sd23_d_consulta_dia = !empty($sd23_d_consulta_dia) ? $sd23_d_consulta_dia : "";
                        $sd23_d_consulta_mes = !empty($sd23_d_consulta_mes) ? $sd23_d_consulta_mes : "";
                        $sd23_d_consulta_ano = !empty($sd23_d_consulta_ano) ? $sd23_d_consulta_ano : "";

                        db_inputdatasaude(
                          'document.form1.sd27_i_codigo.value',
                          'sd23_d_consulta',
                          $sd23_d_consulta_dia,
                          $sd23_d_consulta_mes,
                          $sd23_d_consulta_ano,
                          true,
                          'text',
                          $db_opcao,
                          " onchange=\"js_diasem()\" onFocus=\"nextfield='done'\" readonly ",
                          "",
                          "",
                          "parent.js_diasem(); ", '', '', '', false, false,
                          'document.form1.upssolicitante.value',
                          'document.form1.sd02_i_codigo.value'
                        );
                        ?>
                      </td>
                      <td>
                        <?php
                        db_input('diasemana', 49, '', true, 'text', 3, '');
                        ?>
                      </td>
                    </tr>
                  </table>
                </fieldset>

                <fieldset>
                  <legend>Agendamento na Grade de Horário do Dia</legend>
                  <table style="width: 100%;">
                    <tr>
                      <td colspan="3">
                        <iframe id="frameagendados" name="frameagendados"  src=""
                                width="100%" height="250" scrolling="yes" frameborder="0">
                        </iframe>
                      </td>
                    </tr>
                  </table>
                </fieldset>
              </td>
              <td valign="top" height="100%">
                <fieldset>
                  <legend>Calendário</legend>
                  <iframe id="framecalendario" name="framecalendario"
                          src="func_calendariosaude.php?nome_objeto_data=sd23_d_consulta"
                          width="100%" height="315" scrolling="no" frameborder="0">
                  </iframe>
                </fieldset>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <table width="100%">
                  <tr>
                    <td width="80%" nowrap title="<?=$Tsd30_c_tipograde?>">
                      <?php
                      echo $Lsd30_c_tipograde;
                      $x = array('I' => 'Intervalo', 'P' => 'Período');
                      db_input('sd30_c_tipograde', 10, $Isd30_c_tipograde, true, 'text', 3);

                      echo "&nbsp;&nbsp;&nbsp;".$Ls165_formatocomprovanteagend;
                      $aOpcoes = array("1" => "PDF", "2" => "TXT");
                      db_select('s165_formatocomprovanteagend',$aOpcoes,true,$db_opcao,"");
                      ?>
                    </td>
                    <td>
                      <fieldset>
                        <legend>Total de Fichas no Dia</legend>
                        <table>
                          <tr>
                            <td nowrap title="<?=$Tsd30_i_fichas?>" >
                              <?=$Lsd30_i_fichas?>
                            </td>
                            <td valing="top" align="top">
                              <?php
                              db_input('sd30_i_fichas', 10, $Isd30_i_fichas, true, 'text', 3);
                              ?>
                            </td>
                            <td nowrap title="<?=$Tsd30_i_reservas?>" >
                              <?=$Lsd30_i_reservas?>
                            </td>
                            <td valing="top" align="top">
                              <?php
                              db_input('sd30_i_reservas', 10, $Isd30_i_reservas, true, 'text', 3);
                              ?>
                            </td>
                            <td nowrap title="Saldo disponível"><b>Saldo:</b></td>
                            <td>
                              <?php
                              db_input('saldo', 10, '', true, 'text', 3);
                              ?>
                            </td>
                          </tr>
                        </table>
                      </fieldset>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
  </table>
</form>
<script>
/*
 * Seleciona uma unidade
 */
function pesquisaUnidade(mostra) {

  var sTitulo = 'Pesquisa UPS';

  if (mostra == true) {

    js_OpenJanelaIframe(
      '',
      'db_iframe_unidades',
      'func_unidades.php?iCotas=1&funcao_js=parent.js_mostraunidade|sd02_i_codigo|descrdepto',
      sTitulo,
      mostra
    );
  } else {

    if(document.form1.sd02_i_codigo.value != '') {

      js_OpenJanelaIframe(
        '',
        'db_iframe_unidades',
        'func_unidades.php?iCotas=1'
                        +'&pesquisa_chave=' + document.form1.sd02_i_codigo.value
                        +'&funcao_js=parent.js_mostraunidade_2',
        sTitulo,
        mostra
      )
    } else {

      $('descrdepto').value = '';

      $('rh70_sequencial').value = '';
      $('rh70_estrutural').value = '';
      $('rh70_descr').value      = '';

      $('sd03_i_codigo').value = '';
      $('z01_nome').value      = '';

      $('s125_i_procedimento').value = '';
      $('sd63_c_procedimento').value = '';

      document.getElementById('framecalendario').src = '';
      document.getElementById('frameagendados').src  = '';
    }
  }
}

/*
 * Retorna a unidade selecionada de acordo com a opção escolhida na âncora
 */
function js_mostraunidade(chave1, chave2) {

  $('sd02_i_codigo').value = chave1;
  $('descrdepto').value    = chave2;
  db_iframe_unidades.hide();
  js_limpar();
  $('rh70_estrutural').focus();
}

/*
 * Retorna as informações de acordo com o código digitado para unidade
 */
function js_mostraunidade_2(chave1, status) {

  $('descrdepto').value = chave1;
  if (status === true) {

    $('sd02_i_codigo').value = '';
    return;
  }

  js_limpar();
  $('rh70_estrutural').focus();
}

/**
 * Pesquisa uma especialidade. As especialidades apresentadas estão de acordo com os profissionais da saúde vinculados
 * a unidade e especialidades cadastrados para o mesmo
 */
function pesquisaEspecialidade(mostra, lFiltrarProfissional) {

  if( $('s125_i_procedimento') != undefined ) {

    document.form1.sd63_c_procedimento.value = '';
    document.form1.sd63_c_nome.value         = '';
    document.form1.s125_i_procedimento.value = '';
  }

  if ($('sd02_i_codigo').value == '') {

    alert("Informe uma unidade prestadora antes de selecionar a especialidade.");
    return;
  }

  if(mostra === false && $F('rh70_estrutural') == '') {

    $('rh70_sequencial').value = '';
    $('rh70_estrutural').value = '';
    $('rh70_descr').value      = '';

    $('s125_i_procedimento').value = '';
    $('sd63_c_procedimento').value = '';

    document.getElementById('framecalendario').src = '';
    document.getElementById('frameagendados').src  = '';

    return;
  }

  var sCamposcotas = '';

  if (<?=$db_opcao_cotas;?> == 1 && <?=db_getsession('DB_coddepto');?> != $F('sd02_i_codigo') ) {
    sCamposcotas += '&lApenasCotas=1&iUpssolicitante=<?=$upssolicitante?>&iUpsprestadora='+$('sd02_i_codigo').value;
  }

  var sFuncao     = mostra === true ? 'mostraEspecialidadeAncora' : 'mostraEspecialidadeCodigo';
  var sUrl        = 'func_especialidadeagendamento.php?funcao_js=parent.' + sFuncao;
  var sParametros = '|rh70_sequencial|rh70_estrutural|rh70_descr';

  if(mostra === false) {
    sParametros = '&pesquisa_chave=' + $F('rh70_estrutural');
  }

  if($F('sd02_c_centralagenda') == 'N') {
    sParametros += '&chave_sd04_i_unidade=' + $F('sd02_i_codigo');
  }

  if ( lFiltrarProfissional === false) {

    $('sd03_i_codigo').value = '';
    $('z01_nome').value      = '';
  }

  if( !empty($F('sd03_i_codigo')) ) {
    sParametros += '&chave_sd04_i_medico=' + $F('sd03_i_codigo');
  }

  sParametros += sCamposcotas;

  js_OpenJanelaIframe('', 'db_iframe_especialidade', sUrl + sParametros, 'Pesquisa Especialidade', mostra);
}

/**
 * Retorna as informações da especialidade quando clicado na âncora de pesquisa
 */
function mostraEspecialidadeAncora(iCodigo, sEstrutural, sDescricao) {

  db_iframe_especialidade.hide();

  $('rh70_sequencial').value = iCodigo;
  $('rh70_estrutural').value = sEstrutural;
  $('rh70_descr').value      = sDescricao;

  buscaProfissionalCalendario();
}

/**
 * Retorna as informações da especialidade quando informado o código
 */
function mostraEspecialidadeCodigo(lErro, iCodigo, sEstrutural, sDescricao) {

  db_iframe_especialidade.hide();

  $('rh70_sequencial').value = '';
  $('rh70_estrutural').value = '';
  $('rh70_descr').value      = '';

  if(lErro === true) {

    $('rh70_descr').value = iCodigo;

    document.getElementById('framecalendario').src = '';
    document.getElementById('frameagendados').src  = '';

    return;
  }

  if(lErro === false) {

    $('rh70_sequencial').value = iCodigo;
    $('rh70_estrutural').value = sEstrutural;
    $('rh70_descr').value      = sDescricao;

    buscaProfissionalCalendario();
  }
}

/**
 * Controla se deve ser pesquisado o profissional ou carregar o calendário
 */
function buscaProfissionalCalendario() {

  if($F('sd03_i_codigo') == '') {
    pesquisaProfissional(true);
  }

  js_calend();
}

/**
 * Pesquisa os médicos vinculados a unidade selecionada. Caso tenha sido informada uma especialidade, busca somente
 * os profissionais que possuam tal especialidade
 */
function pesquisaProfissional(mostra) {

  if ($('sd02_i_codigo').value == '') {

    alert("Informe uma unidade prestadora antes de selecionar a especialidade.");
    return;
  }

  if(mostra == false && $F('sd03_i_codigo') == '') {

    $('z01_nome').value      = '';
    $('sd27_i_codigo').value = '';

    $('s125_i_procedimento').value = '';
    $('sd63_c_procedimento').value = '';

    document.getElementById('framecalendario').src = '';
    document.getElementById('frameagendados').src  = '';
    return;
  }

  var sFuncao     = mostra === true ? 'mostraMedicoAncora' : 'mostraMedicoCodigo';
  var sUrl        = 'func_cboups2.php?chave_sd04_i_medico=0&chave_sd04_i_unidade=' + $F('sd02_i_codigo');
      sUrl       += '&funcao_js=parent.' + sFuncao;
  var sParametros = '|sd03_i_codigo|z01_nome|sd27_i_codigo';

  if(mostra === false) {
    sParametros = '&pesquisa_chave=' + $F('sd03_i_codigo');
  }

  if($F('rh70_estrutural') != '') {
    sParametros += '&chave_rh70_estrutural=' + $F('rh70_estrutural');
  }

  js_OpenJanelaIframe('', 'db_iframe_cboups', sUrl + sParametros, 'Pesquisa Profissional', mostra);
}

/**
 * Retorna as informações do profissional quando clicado na âncora de pesquisa
 */
function mostraMedicoAncora(iCodigoProfissional, sNome, iCodigoEspecMedico) {

  db_iframe_cboups.hide();

  $('sd03_i_codigo').value = iCodigoProfissional;
  $('z01_nome').value      = sNome;
  $('sd27_i_codigo').value = iCodigoEspecMedico;

  buscaEspecialidadeCalendario( true );
  if ( $F('rh70_estrutural') != '' && $F('sd03_i_codigo') && $F('sd23_d_consulta') != '') {
    js_agendados();
  }
}

/**
 * Retorna as informações do profissional quando informado o código do mesmo
 */
function mostraMedicoCodigo(lErro, iCodigoProfissional, sNome, iCodigoEspecMedico) {

  db_iframe_cboups.hide();

  $('sd03_i_codigo').value = '';
  $('sd27_i_codigo').value = '';
  $('z01_nome').value      = '';

  if(lErro === true) {

    $('z01_nome').value = iCodigoProfissional;

    document.getElementById('framecalendario').src = '';
    document.getElementById('frameagendados').src  = '';

    return;
  }

  if(lErro === false) {

    $('sd03_i_codigo').value = iCodigoProfissional;
    $('sd27_i_codigo').value = iCodigoEspecMedico;
    $('z01_nome').value      = sNome;

    buscaEspecialidadeCalendario( true );
  }

  if ( $F('rh70_estrutural') != '' && $F('sd03_i_codigo') && $F('sd23_d_consulta') != '' ) {
    js_agendados();
  }
}

/**
 * Valida se deve ser pesquisada especialidade do profissional, ou se deve ser aberto o calendário das datas
 */
function buscaEspecialidadeCalendario( lFiltrarProfissional ) {

  if($F('rh70_estrutural') == '') {

    pesquisaEspecialidade(true, lFiltrarProfissional);
    return;
  }

  js_calend();
}

function js_comprovante (sd23_i_codigo) {

  if (document.form1.s165_formatocomprovanteagend.value == 1) {

    x = 'sau2_agendamento004.php';
    x += '?sd23_i_codigo='+sd23_i_codigo;
    x += '&diasemana='+document.form1.diasemana.value;

    jan = window.open(x,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  } else {

    // Arquivo que gerava o TXT ->  sau2_agendamento005.php
    var oParam           = new Object();
    oParam.exec          = 'gerarComprovanteTXT';
    oParam.sd23_i_codigo = sd23_i_codigo;
    oParam.diasemana     = document.form1.diasemana.value;

    js_webajax(oParam, 'js_retornoComprovante', 'sau4_ambulatorial.RPC.php');
  }
}

function js_retornoComprovante(oAjax) {

  oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.iStatus == 2) {

    message_ajax(oRetorno.sMessage.urlDecode());
    return false;
  } else {

    iTop    = 20;
    iLeft   = 5;
    iHeight = screen.availHeight-210;
    iWidth  = screen.availWidth-35;
    sChave = 'sSessionNome='+oRetorno.sSessionNome;

    js_OpenJanelaIframe ('CurrentWindow.corpo', 'db_iframe_visualizador', 'sau2_fichaatend002.php?'+sChave,
                         'Visualisador', true, iTop, iLeft, iWidth, iHeight
                        );
  }
}

function js_agendados() {

  obj                        = document.form1;
  obj.saldo.value            = '';
  obj.sd30_i_fichas.value    = '';
  obj.sd30_i_reservas.value  = '';
  obj.sd30_c_tipograde.value = '';
  sd23_d_consulta            = document.getElementById('sd23_d_consulta').value;

  a    =  sd23_d_consulta.substr(6,4);
  m    = (sd23_d_consulta.substr(3,2))-1;
  d    =  sd23_d_consulta.substr(0,2);
  data = new Date(a,m,d);
  dia  = data.getDay()+1;

  if( sd23_d_consulta != "" && obj.sd02_c_centralagenda.value == "N" ) {

    x  = 'sau4_agendamento002.php';
    x += '?sd27_i_codigo='+obj.sd27_i_codigo.value;
    x += '&chave_diasemana='+dia;
    x += '&sd23_d_consulta='+sd23_d_consulta;
    x += '&sd02_i_codigo='+$('sd02_i_codigo').value;
    x += '&rh70_estrutural='+$('rh70_estrutural').value;
  } else if( obj.sd02_c_centralagenda.value == "S" ) {

    x  = 'sau4_agendamento004.php';
    x += '?sd27_i_codigo='+obj.sd27_i_codigo.value;
    x += '&sd27_i_rhcbo='+obj.rh70_sequencial.value;
    x += '&chave_diasemana='+dia;
    x += '&sd23_d_consulta='+sd23_d_consulta;
  }

  //Verifica Procedimento
  if( $('sd63_c_procedimento') != undefined && $F('sd63_c_procedimento') == '' ) {

    alert('Procedimento Obrigatório.');
    $('sd63_c_procedimento').focus();
  } else {

    iframe     = document.getElementById('frameagendados');
    iframe.src = x;
  }
}

function js_diasem() {

  obj = document.form1;

  a    =  obj.sd23_d_consulta_ano.value;
  m    = (obj.sd23_d_consulta_mes.value)-1;
  d    =  obj.sd23_d_consulta_dia.value;
  data = new Date(a,m,d);
  dia  = data.getDay();

  semana    = new Array(6);
  semana[0] = 'Domingo';
  semana[1] = 'Segunda-Feira';
  semana[2] = 'Terça-Feira';
  semana[3] = 'Quarta-Feira';
  semana[4] = 'Quinta-Feira';
  semana[5] = 'Sexta-Feira';
  semana[6] = 'Sábado';

  document.form1.diasemana.value = semana[dia];

  js_agendados();
}

function js_calend() {

  obj  = document.form1;
  a    =  obj.sd23_d_consulta_ano.value;
  m    = (obj.sd23_d_consulta_mes.value)-1;
  d    =  obj.sd23_d_consulta_dia.value;
  data = new Date(a,m,d);
  dia  = data.getDay() + 1;

  sd23_d_consulta = document.getElementById('sd23_d_consulta').value;

  if( $('s125_i_procedimento') != undefined && $F('s125_i_procedimento') == '' ) {
    $('sd63_c_procedimento').focus();
  } else {

    x  = 'func_calendariosaude2.php';
    x += '?sd27_i_codigo='+obj.sd27_i_codigo.value;
    x += '&upssolicitante='+obj.upssolicitante.value;
    x += '&upsprestadora='+obj.sd02_i_codigo.value;
    x += '&mescomp='+sd23_d_consulta.substr(3,2);
    x += '&anocomp='+sd23_d_consulta.substr(6,4);
    x += '&sd27_i_rhcbo='+obj.rh70_sequencial.value;
    x += '&sd27_i_rhcbo_estrutural='+obj.rh70_estrutural.value;
    x += '&sd02_c_centralagenda='+obj.sd02_c_centralagenda.value;
    x += '&nome_objeto_data=sd23_d_consulta';
    x += '&shutdown_function=parent.js_agendados()';

    iframe = document.getElementById('framecalendario');
    iframe.src = x;
  }
}

function js_limpar() {

  $('rh70_estrutural').value = '';
  $('rh70_sequencial').value = '';
  $('rh70_descr').value      = '';
  $('sd03_i_codigo').value   = '';
  $('z01_nome').value        = '';
  $('sd23_d_consulta').value = '';
  $('diasemana').value       = '';
  $('frameagendados').src    = '';
  $('framecalendario').src   = '';
}

/**
 * Ajax
 */
function js_ajax( objParam, strCarregando, jsRetorno, strURL ) {

  if (strURL == undefined) {
    strURL = 'sau1_sau_individualprocedRPC.php';
  }

  var objAjax = new Ajax.Request(
                         strURL,
                         {
                          method    : 'post',
                          parameters: 'json='+Object.toJSON(objParam),
                          onCreate  : function(){
                                  js_divCarregando( strCarregando, 'msgbox');
                                },
                          onComplete: function(objAjax){
                                  var evlJS = jsRetorno+'( objAjax )';
                                  js_removeObj('msgbox');
                                  eval( evlJS );
                                }
                         }
                        );
}
/**
 * Pesquisa Procedimento
 */
function js_pesquisas125_i_procedimento(mostra){

  if ($F('sd27_i_codigo') == '') {

    alert('Selecione um profissional e uma especialidade primeiro.');
    return false;
  }

  if ($F('sd02_i_codigo') == '') {

    alert('Selecione uma unidade primeiro.');
    return false;
  }

  var strParam = '';
  strParam += 'func_sau_proccbo.php';
  strParam += '?chave_rh70_sequencial='+$F('rh70_sequencial');
  strParam += '&funcao_js=parent.js_mostraprocedimentos1|sd96_i_procedimento|sd63_c_procedimento|sd63_c_nome';
  strParam += '&campoFoco=sd63_c_procedimento&lFiltrarPadroes=true&lBotaoMostrarTodos=true';
  strParam += '&lControleOutrasRotinas=true';
  strParam += '&iEspecMed='+$F('sd27_i_codigo');

  if(mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_sau_proccbo', strParam, 'Pesquisa Procedimentos', true);
  } else {

    if($F('sd63_c_procedimento') != '') {

      strParam += '&chave_sd63_c_procedimento='+$F('sd63_c_procedimento')+'&chave_nao_mostra=true';
      strParam += '&lAutomatico=true';

      js_OpenJanelaIframe('', 'db_iframe_sau_proccbo', strParam, 'Pesquisa Procedimentos', false);
    } else {
      $('sd63_c_nome').value = '';
    }
  }

  $('sd63_c_procedimento').focus();
}

function js_mostraprocedimentos1(chave1, chave2, chave3) {

  if(chave1 == '') {
    alert('CBO não tem ligação com procedimento');
  }

  $('s125_i_procedimento').value = chave1;
  $('sd63_c_procedimento').value = chave2;
  $('sd63_c_nome').value         = chave3;
  db_iframe_sau_proccbo.hide();
  js_calend();
}

function js_getProcedimentoPadraoProfissional() {

  <?php
  if(!$booProced) {
    echo 'return false;';
  }
  ?>

  if(    $F('rh70_sequencial') == ''
      || $F('rh70_estrutural') == ''
      || $F('sd03_i_codigo') == ''
      || $F('sd27_i_codigo') == '' ) {
    return false;
  }

  var oParam       = new Object();
  oParam.exec      = 'getProcedimentosPadraoProfissional';
  oParam.iEspecMed = $F('sd27_i_codigo');

  js_ajax(oParam, 'Procurando procedimento padrão', 'js_retornoGetProcedimentosPadraoProfissional', 'sau4_agendamento.RPC.php');
}

function js_retornoGetProcedimentosPadraoProfissional(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus == 1) { // Possui procedimentos padrão

    iTam = oRetorno.aProcedimentos.length;

    if (iTam == 1) { // Se tiver apenas um procedimento padrão vinculado

      $('s125_i_procedimento').value = oRetorno.aProcedimentos[0].sd63_i_codigo;
      $('sd63_c_procedimento').value = oRetorno.aProcedimentos[0].sd63_c_procedimento.urlDecode();
      $('sd63_c_nome').value         = oRetorno.aProcedimentos[0].sd63_c_nome.urlDecode();
      js_calend();
    }
  }
}
</script>