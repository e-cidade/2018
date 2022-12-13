<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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
$oRotulo = new rotulocampo;

$oRotulo->label('sd03_i_codigo');
$oRotulo->label('sd23_d_consulta');
$oRotulo->label('z01_nome');
$oRotulo->label('sd02_i_codigo');
$oRotulo->label('descrdepto');
$oRotulo->label('sd04_i_cbo');
$oRotulo->label('sd27_i_codigo');
$oRotulo->label('rh70_sequencial');
$oRotulo->label('rh70_estrutural');
$oRotulo->label('rh70_descr');
$oRotulo->label("s165_formatocomprovanteagend");
?>

<form name="form1" method="post">
  <?php
    db_input('iUpssolicitante', 10, $iUpssolicitante, true, 'hidden', 3);
  ?>
  <table>
    <tr>
      <td>
        <fieldset><legend><b>Profissional</b></legend>
          <table>
            <!-- UPS -->
            <tr>
              <td nowrap title="<?=$Tsd02_i_codigo?>" >
                <?php
                  db_ancora($Lsd02_i_codigo, "pesquisaUnidade(true);", $db_opcao_cotas);
                ?>
              </td>
              <td>
                <?php
                  $sScript = "onchange = 'pesquisaUnidade(false);'";
                  db_input('sd02_i_codigo', 10, $Isd02_i_codigo, true, 'text', $db_opcao_cotas, $sScript);
                ?>
              </td>
              <td colspan="2">
                <?php
                  db_input('descrdepto', 49, $Idescrdepto, true, 'text', 3);
                ?>
              </td>
            </tr>
            <!-- CBO -->
            <tr>
              <td nowrap title="<?=$Tsd04_i_cbo?>">
                <?php
                  db_ancora($Lsd04_i_cbo, 'pesquisaEspecialidade(true);', 1);
                ?>
              </td>
              <td>
                <?php
                  $sScript = " onchange='pesquisaEspecialidade(false);' onFocus=\"nextfield='sd03_i_codigo'\"";

                  db_input('sd27_i_codigo',   10, $Isd27_i_codigo,   true, 'hidden', 1);
                  db_input('rh70_sequencial', 10, $Irh70_sequencial, true, 'hidden', 1);
                  db_input('rh70_estrutural', 10, $Irh70_estrutural, true, 'text',   1, $sScript);
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
              <td nowrap title="<?=$Tsd03_i_codigo?>" >
                <?php
                  db_ancora($Lsd03_i_codigo, "pesquisaProfissional(true);", 1);
                ?>
              </td>
              <td valing="top" align="top">
                <?php
                  $sScript = " onchange='pesquisaProfissional(false);' onFocus=\"nextfield='sd23_d_consulta'\"";
                  db_input('sd03_i_codigo', 10, $Isd03_i_codigo, true, 'text', 1, $sScript);
                ?>
              </td>
              <td colspan="2">
                <?php
                  db_input('z01_nome', 49, $Iz01_nome, true, 'text', 3, '');
                ?>
              </td>
            </tr>
            <!-- Data Consulta -->
            <tr>
              <td nowrap title="<?=$Tsd23_d_consulta?>">
                <?=$Lsd23_d_consulta?>
              </td>
              <td nowrap>
                <?php
                  $sd23_d_consulta_dia = !empty( $sd23_d_consulta_dia ) ? $sd23_d_consulta_dia : "";
                  $sd23_d_consulta_mes = !empty( $sd23_d_consulta_mes ) ? $sd23_d_consulta_mes : "";
                  $sd23_d_consulta_ano = !empty( $sd23_d_consulta_ano ) ? $sd23_d_consulta_ano : "";

                  db_inputdatasaude(
                                     'document.form1.sd27_i_codigo.value',
                                     'sd23_d_consulta',
                                     $sd23_d_consulta_dia,
                                     $sd23_d_consulta_mes,
                                     $sd23_d_consulta_ano,
                                     true,
                                     'text',
                                     1,
                                     " onchange=\"js_diasem()\" onFocus=\"nextfield='done'\" readonly",
                                     '',
                                     '',
                                     'parent.js_diasem(); ', '', '', '', false, false,
                                     $iUpssolicitante,
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

        <br>

        <fieldset>
          <legend><b>Grade de Horário:</b></legend>
          <table width="100%">
            <tr>
              <td>
                <iframe id="frameagendados" name="frameagendados" src=""
                  width="100%" height="300" scrolling="yes" frameborder="0">
                </iframe>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
      <td valign="top" height="100%">
        <table>
          <tr>
            <td>
              <fieldset>
                <legend><b>Calendário:</b></legend>
                <table width="100%">
                  <tr>
                    <td style="width: 360px; ">
                      <iframe id="framecalendario" name="framecalendario"
                        src="func_calendariosaude.php?nome_objeto_data=sd23_d_consulta"
                        width="400px" height="270" scrolling="no" frameborder="0">
                      </iframe>
                    </td>
                  </tr>
                </table>
              </fieldset>
            </td>
          </tr>

          <tr>
            <td>
              <fieldset>
                <legend><b>Opções:</b></legend>
                <table border="0">
                  <tr>
                    <td align="left" nowrap>
                      <input type="button" style="width: 90px;" name="agendar" id="agendar"
                        value="Agendar" title="Agendar pacientes" onclick="js_agendar();">
                      <input type="button" style="width: 90px;" name="anular" id="anular" value="Anular"
                        title="Anular agendamentos" onclick="js_anular();">
                      <input type="button" style="width: 90px;" name="presenca" id="presenca" value="Presença"
                        title="Marcar presença dos pacientes agendados" onclick="js_presenca();">
                      <input type="button" style="width: 90px;" name="observacao" id="observacao" value="Observação"
                        title="Registrar observações para os agendamentos realizados." onclick="js_observacao();">
                    </td>
                  </tr>
                  <tr>
                    <td align="left" nowrap>
                      <?php
                        //Transferência: AGENDAMENTOS > PROCEDIMENTOS > TRANSFERÊNCIA > GERAL
                        if (db_permissaomenu(date('Y'), db_getsession('DB_modulo'), 7061) == 'true') {
                        ?>
                          <input type="button" style="width: 90px;" name="transferencia" id="transferencia"
                            value="Transferência" title="Transferir agendamentos" onclick="js_transferir();">
                      <?php
                        }
                        //Relatório: AGENDAMENTOS > RELATÓRIO > AGENDAMENTO
                        if (db_permissaomenu(date('Y'), db_getsession('DB_modulo'), 7072) == 'true') {
                        ?>
                          <input type="button" style="width: 90px;" name="relatorio" id="relatorio" value="Relatório"
                            title="Relatório dos agendamentos do profissional" onclick="js_relatorio();">
                      <?php
                        }
                        //Ausência: AMBULATORIAL > CADASTROS > PROFISSIONAL DE SAÚDE > INCLUSÃO
                        if (db_permissaomenu(date('Y'), 1000004, 1100961) == 'true') {
                        ?>
                          <input type="button" style="width: 90px;" name="ausencia" id="ausencia" value="Ausência"
                            title="Marcar ausência para o profissional" onclick="js_ausencia();">
                      <?php
                        }
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td align="left">
                      <input type="button" style="width: 90px;" name="comprovante" id="Comprovante"
                        value="Comprovante" title="Emitir comprovante de agendamento" onclick="js_comprovante();">
                      <?php
                        //Consulta Geral: AMBULATORIAL > CONSULTAS > CONSULTA GERAL DA SAÚDE
                        if (db_permissaomenu(date('Y'), 1000004, 1101027) == 'true') {
                      ?>
                        <input type="button" style="width: 90px;" name="consultaGeral" id="consultaGeral"
                          value="Consulta Geral" title="Consulta geral da saúde por paciente"
                          onclick="js_consultaGeral();">
                      <?php
                        }
                      ?>
                      <input type="button" style="width: 90px;" name="faa" id="faa" value="FAA"
                        title="Emitir FAA" onclick="js_faa();">
                    </td>
                  </tr>
                  <tr>
                    <td align="left">
                      <?php
                        echo $Ls165_formatocomprovanteagend;
                        $aOpcoes = array("1"=>"PDF","2"=>"TXT");
                        db_select('s165_formatocomprovanteagend', $aOpcoes, true, 1, "");
                      ?>
                    </td>
                  <tr>
                </table>
              </fieldset>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>

<script>

/* Tiro a validação de data do evento onblur, pois esta validação será realizada
   dentro da função js_diasem, que está no evento onchange */
$('sd23_d_consulta').onBlur = '';

/* Variáveis globais, utilizadas na rotina js_agendar() */
sTipoFicha      = '';
sHorario        = '';
iFicha          = '';
iUndMedHor      = '';
sTipoGrade      = '';

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
function pesquisaEspecialidade(mostra) {

  if ($('sd02_i_codigo').value == '') {

    alert("Informe uma unidade prestadora antes de selecionar a especialidade.");
    return;
  }

  if(mostra === false && $F('rh70_estrutural') == '') {

    $('rh70_sequencial').value = '';
    $('rh70_estrutural').value = '';
    $('rh70_descr').value      = '';

    document.getElementById('framecalendario').src = '';
    document.getElementById('frameagendados').src  = '';

    return;
  }

  var sCamposcotas = '';

  if (<?=$db_opcao_cotas;?> == 1) {
    sCamposcotas += '&lApenasCotas=1&iUpssolicitante=' + $F('iUpssolicitante') + '&iUpsprestadora=' + $F('sd02_i_codigo');
  }

  var sFuncao     = mostra === true ? 'mostraEspecialidadeAncora' : 'mostraEspecialidadeCodigo';
  var sUrl        = 'func_especialidadeagendamento.php?chave_sd04_i_unidade=' + $F('sd02_i_codigo');
      sUrl       += '&funcao_js=parent.' + sFuncao;
  var sParametros = '|rh70_sequencial|rh70_estrutural|rh70_descr';

  if(mostra === false) {
    sParametros = '&pesquisa_chave=' + $F('rh70_estrutural');
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

    $('z01_nome').value = '';

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

  buscaEspecialidadeCalendario();
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

    buscaEspecialidadeCalendario();
  }
}

/**
 * Valida se deve ser pesquisada especialidade do profissional, ou se deve ser aberto o calendário das datas
 */
function buscaEspecialidadeCalendario() {

  if($F('rh70_estrutural') == '') {

    pesquisaEspecialidade(true);
    return;
  }

  js_calend();
}

function js_ajax(oParam, jsRetorno, sUrl) {

  var mRetornoAjax;

  if (sUrl == undefined) {
    sUrl = 'sau4_agendamento.RPC.php';
  }
  var objAjax = new Ajax.Request(sUrl,
                                 {
                                  method: 'post',
                                  asynchronous: false,
                                  parameters: 'json='+Object.toJSON(oParam),
                                  onComplete: function(oAjax) {
                                                var evlJS = jsRetorno+'(oAjax);';
                                                return mRetornoAjax = eval(evlJS);
                                              }
                                 }
                                );
  return mRetornoAjax;
}

function js_bloqueiaBotoesSolic() {

  var iUnidadeSolic = '<?=db_getsession('DB_coddepto');?>';
  var iUnidadePrest = $F('sd02_i_codigo');

  /* Se a unidade solicitante for diferente da unidade prestadora, bloqueio alguns botões */
  if (iUnidadeSolic != iUnidadePrest) {

    $('presenca').disabled = true;
    $('ausencia').disabled = true;
    $('faa').disabled      = true;
  } else {

    $('presenca').disabled = false;
    $('ausencia').disabled = false;
    $('faa').disabled      = false;
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

function js_agendados() {

  sd23_d_consulta = $F('sd23_d_consulta');
  sd27_i_codigo   = $F('sd27_i_codigo');

  if (sd23_d_consulta != '') {

    iAno       = sd23_d_consulta.substr(6, 4);
    iMes       = parseInt(sd23_d_consulta.substr(3, 2), 10) - 1;
    iDia       = sd23_d_consulta.substr(0, 2);
    dData      = new Date(iAno, iMes, iDia);
    iDiaSemana = dData.getDay() + 1;

    var sUrl  = 'sau4_agendamento002.php';
        sUrl += '?sd27_i_codigo='+sd27_i_codigo;
        sUrl += '&chave_diasemana='+iDiaSemana;
        sUrl += '&sd23_d_consulta='+sd23_d_consulta;
        sUrl += '&sTransf=true&sLado=de';
        sUrl += '&lMostraSeq=true';
        sUrl += '&lEscondeFicha=true';
        sUrl += '&lMostraTipoFicha=true';
        sUrl += '&lEscondeHoraFim=true';
        sUrl += '&lEscondeReserva=true';
        sUrl += '&lEscondeTipoGrade=true';
        sUrl += '&lUnificado=true';

    $('frameagendados').src = sUrl;
    $('framecalendario').contentWindow.location.reload(true);
  }
}

function js_diasem() {

  if ($F('sd23_d_consulta') == '' || !js_validaDbData($('sd23_d_consulta'))) {

    $('frameagendados').src = '';
    return false;
  }

  iAno = $F('sd23_d_consulta_ano');
  iMes = parseInt($F('sd23_d_consulta_mes'), 10) - 1;
  iDia = $F('sd23_d_consulta_dia');

  dData       = new Date(iAno, iMes, iDia);
  iDiaSemana  = dData.getDay();

  sNomeDia    = new Array(6);
  sNomeDia[0] = 'Domingo';
  sNomeDia[1] = 'Segunda-Feira';
  sNomeDia[2] = 'Terça-Feira';
  sNomeDia[3] = 'Quarta-Feira';
  sNomeDia[4] = 'Quinta-Feira';
  sNomeDia[5] = 'Sexta-Feira';
  sNomeDia[6] = 'Sábado';

  $('diasemana').value = sNomeDia[iDiaSemana];

  js_agendados();
}

function js_calend() {

  var sDataConsulta = $F('sd23_d_consulta');

  var sUrl  = 'func_calendariosaude2.php';
      sUrl += '?sd27_i_codigo='+$F('sd27_i_codigo');
      sUrl += '&sd27_i_rhcbo='+$F('rh70_sequencial');
      sUrl += '&upssolicitante='+$F('iUpssolicitante');
      sUrl += '&upsprestadora='+$F('sd02_i_codigo');
      sUrl += '&mescomp='+sDataConsulta.substr(3,2);
      sUrl += '&anocomp='+sDataConsulta.substr(6,4);
      sUrl += '&nome_objeto_data=sd23_d_consulta';
      sUrl += '&shutdown_function=parent.js_agendados()';

  $('framecalendario').src = sUrl;
}

function js_pesquisasd23_i_numcgs(mostra) {

  js_OpenJanelaIframe(
                       '',
                       'db_iframe_cgs_und',
                       'func_cgs_und.php?funcao_js=parent.js_mostracgs|z01_i_cgsund|z01_v_nome&lValidaCGS=true',
                       'Pesquisa',
                       true
                     );
}

function js_mostracgs(chave1, chave2){

  db_iframe_cgs_und.hide();

  var sMensagem  = 'Confirma agendamento para ' + chave2 + ', dia ' + $F('sd23_d_consulta');
      sMensagem += ' às ' + sHorario + ' horas, ficha ' + sTipoFicha + '?';

  if (confirm( sMensagem )) {

    var oParam                  = new Object();
        oParam.exec             = "agendarPaciente";
        oParam.iCgs             = chave1;
        oParam.sd23_d_consulta  = $F('sd23_d_consulta');
        oParam.sd23_c_hora      = sHorario;
        oParam.sd23_i_ficha     = iFicha;
        oParam.sd23_i_undmedhor = iUndMedHor;
        oParam.sd30_c_tipograde = sTipoGrade;
        oParam.sd02_i_codigo    = $('sd02_i_codigo').value;
        oParam.rh70_sequencial  = $('rh70_sequencial').value;
        oParam.rh70_estrutural  = $('rh70_estrutural').value;
        oParam.sd27_i_codigo    = $('sd27_i_codigo').value;
        oParam.sd03_i_codigo    = $('sd03_i_codigo').value;

    if (js_validaDbData($('sd23_d_consulta'))) {
      js_ajax(oParam, 'js_retornoAgendarPaciente');
    }
  }
}

function js_retornoAgendarPaciente(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  message_ajax(oRetorno.sMessage.urlDecode());

  if (oRetorno.iStatus == 1) {

    if ('<?=$oSauConfig->s103_c_emitircomprovante?>' == 'S') {
   
      if (document.form1.s165_formatocomprovanteagend.value == 1) {

        sUrl  = 'sau2_agendamento004.php?';
        sUrl += 'sd23_i_codigo='+oRetorno.iCodigo;
        sUrl += '&diasemana='+$F('diasemana');

        oJan = window.open(sUrl, '', 'width='+(screen.availWidth - 5)+',height='+(screen.availHeight - 40)+
                           ',scrollbars=1,location=0 '
                          );
        oJan.moveTo(0, 0);
      } else {

        var oParam               = new Object();
            oParam.exec          = 'gerarComprovanteTXT';
            oParam.sd23_i_codigo = oRetorno.iCodigo;
            oParam.diasemana     = $F('diasemana');

        js_webajax(oParam, 'js_retornoComprovante', 'sau4_ambulatorial.RPC.php');
      }
    }

    js_agendados();
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

    js_OpenJanelaIframe(
                         'CurrentWindow.corpo',
                         'db_iframe_visualizador',
                         'sau2_fichaatend002.php?' + sChave,
                         'Visualisador',
                         true,
                         iTop,
                         iLeft,
                         iWidth,
                         iHeight
                       );
  }
}

function js_agendar() {
  
  if (!js_validar()) {
    return false;
  }

  var oIframe    = $('frameagendados').contentDocument;
  var aElementos = oIframe.getElementsByName('ckbox'); // Pego todos os checkbox
  var iTam       = aElementos.length;

  for (var iCont = 0; iCont < iTam; iCont++) {

    // Se o checkbox foi marcado e o horário está livre
    if (aElementos[iCont].checked && oIframe.getElementById('livre_'+iCont).value.split(' ## ')[0] == 'true') {

      iFicha     = oIframe.getElementById('td_'+iCont+'1').innerHTML.trim();
      iUndMedHor = aElementos[iCont].value.split(' ## ')[1];
      sTipoFicha = oIframe.getElementById('td_'+iCont+'9').innerHTML.trim();
      sHorario   = oIframe.getElementById('td_'+iCont+'2').innerHTML.trim();
      sTipoGrade = oIframe.getElementById('tipograde_'+iCont).value;

      js_pesquisasd23_i_numcgs();

      return true;
    }
  }

  alert('Selecione um horário livre para realizar o agendamento.');
  
  return false;
}

function js_anular() {
  
  if (!js_validar()) {
    return false;
  }

  var oIframe    = $('frameagendados').contentDocument;
  var aElementos = oIframe.getElementsByName('ckbox'); // Pego todos os checkbox
  var iTam       = aElementos.length;
  var lSel       = false;

  for (var iCont = 0; iCont < iTam; iCont++) {

    // Se o checkbox foi marcado e o horário e possui agendamento
    if (aElementos[iCont].checked && aElementos[iCont].value.split(' ## ')[0] != 0) {

      iTop  = (screen.availHeight - 600) / 2;
      iLeft = (screen.availWidth - 600) / 2;

      sUrl  = 'sau1_agendaconsultaanula001.php';
      sUrl += '?s114_i_agendaconsulta='+aElementos[iCont].value.split(' ## ')[0];
      sUrl += '&db_opcao=1';
      sUrl += '&iIdJanela='+iCont;
      sUrl += '&lExibirPaciente=true';
      sUrl += '&z01_nome='+oIframe.getElementById('td_'+iCont+'7').innerHTML.trim();
      lSel  = true;

      js_OpenJanelaIframe('', 'db_iframe_agendamento'+iCont, sUrl, 'Anulação', true, iTop, iLeft, 600, 250);
    }
  }

  if (!lSel) {
    alert('Selecione um agendamento para anular.');
  }
}

function js_comprovante() {
  
  if (!js_validar()) {
    return false;
  }

  var oIframe          = $('frameagendados').contentDocument;
  var aElementos       = oIframe.getElementsByName('ckbox'); // Pego todos os checkbox
  var iTam             = aElementos.length;
  var sCodAgendamentos = '';
  var sSep             = '';

  for (var iCont = 0; iCont < iTam; iCont++) {

    // Se o checkbox foi marcado e o horário e possui agendamento
    if (aElementos[iCont].checked && aElementos[iCont].value.split(' ## ')[0] != 0) {

      sCodAgendamentos += sSep+aElementos[iCont].value.split(' ## ')[0];
      sSep              = ', ';
    }
  }

  if (sCodAgendamentos == '') {

    alert('Selecione pelo menos um agendamento para emitir o comprovante.');
    return false;
  } else {

    if (document.form1.s165_formatocomprovanteagend.value == 1) {

      sUrl  = 'sau2_agendamento004.php?';
      sUrl += 'sd23_i_codigo='+sCodAgendamentos;
      sUrl += '&diasemana='+$F('diasemana');

      oJan = window.open(sUrl, '', 'width='+(screen.availWidth - 5)+',height='+(screen.availHeight - 40)+
                         ',scrollbars=1,location=0 '
                        );
      oJan.moveTo(0, 0);

      return true;
    } else {

      var oParam           = new Object();
      oParam.exec          = 'gerarComprovanteTXT';
      oParam.sd23_i_codigo = sCodAgendamentos;
      oParam.diasemana     = $F('diasemana');

      js_webajax(oParam, 'js_retornoComprovante', 'sau4_ambulatorial.RPC.php');
    }
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

    js_OpenJanelaIframe(
                         'CurrentWindow.corpo',
                         'db_iframe_visualizador',
                         'sau2_fichaatend002.php?' + sChave,
                         'Visualisador',
                         true,
                         iTop,
                         iLeft,
                         iWidth,
                         iHeight
                       );
  }
}

/* GERAR FAA */
function js_faa() {

  if (!js_validar()) {
    return false;
  }

  var oIframe     = $('frameagendados').contentDocument;
  var aElementos  = oIframe.getElementsByName('ckbox'); // Pego todos os checkbox
  var iTam        = aElementos.length;

  sd23_d_consulta = $F('sd23_d_consulta');

  iAno            = sd23_d_consulta.substr(6, 4);
  iMes            = parseInt(sd23_d_consulta.substr(3, 2), 10) - 1;
  iDia            = sd23_d_consulta.substr(0, 2);
  dData           = new Date(iAno, iMes, iDia);
  iDiaSemana      = dData.getDay() + 1;

  var sCodigos    = '';
  var sSep        = '';

  for (var iCont = 0; iCont < iTam; iCont++) {

    if (aElementos[iCont].value.split(' ## ')[0] != '0' && aElementos[iCont].checked) {

      sCodigos += sSep+aElementos[iCont].value.split(' ## ')[0];
      sSep      = ',';
    }
  }

  if (sCodigos == '') {

    alert('Selecione pelo menos um agendamento para gerar FAA.');
    return false;
  } else {

    var oParam             = new Object();
    oParam.exec            = 'gerarFAATXT';
    oParam.lAgendamentoFaa = true;
    oParam.iUnidade        = $F('sd02_i_codigo');
    oParam.iProfissional   = $F('sd27_i_codigo');
    oParam.iDiasemana      = iDiaSemana;
    oParam.sd23_d_consulta = $F('sd23_d_consulta');
    oParam.iCodAgendamento = sCodigos;
    js_webajax(oParam, 'js_retornoEmissaofaa', 'sau4_ambulatorial.RPC.php');
  }
}

function js_retornoEmissaofaa (oAjax) {

  oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.iStatus == 2) {

    message_ajax(oRetorno.sMessage.urlDecode());
    return false;
  } else {

    if (oRetorno.iTipo == 1) {
      js_emitiefaaPDF (oRetorno);
    } else {
      js_emitirfaaTXT (oRetorno);
    }
  }
}

function js_emitiefaaPDF (oDados) {

  sChave = '?chave_sd29_i_prontuario='+oDados.sChaveProntuarios;
  var WindowObjectReference;
  var strWindowFeatures = "menubar=yes,location=no,resizable=yes,scrollbars=yes,status=yes";

  WindowObjectReference = window.open(oDados.sArquivo+sChave,"CNN_WindowName", strWindowFeatures);
}

function js_emitirfaaTXT (oRetorno) {

  iTop    = 20;
  iLeft   = 5;
  iHeight = screen.availHeight-210;
  iWidth  = screen.availWidth-35;
  sChave = 'sSessionNome='+oRetorno.sSessionNome;

  js_OpenJanelaIframe(
                       'CurrentWindow.corpo',
                       'db_iframe_visualizador',
                       'sau2_fichaatend002.php?' + sChave,
                       'Visualisador',
                       true,
                       iTop,
                       iLeft,
                       iWidth,
                       iHeight
                     );
}

function js_consultaGeral() {

  if (!js_validar()) {
    return false;
  }

  var oIframe      = $('frameagendados').contentDocument;
  var aElementos   = oIframe.getElementsByName('ckbox'); // Pego todos os checkbox
  var iTam         = aElementos.length;
  var iNumMarcados = 0;

  for (var iCont = 0; iCont < iTam; iCont++) {

    // Se o checkbox foi marcado e possui agendamento
    if (aElementos[iCont].checked && aElementos[iCont].value.split(' ## ')[0] != '0') {

      iNumMarcados++;
      iCgs = parseInt(oIframe.getElementById('td_'+iCont+'6').innerHTML, 10);
    }
  }

  if (iNumMarcados <= 0) {

    alert('Selecione um agendamento para a consulta geral.');
    return false;
  } else if(iNumMarcados > 1) {

    alert('Selecione apenas um agendamento para a consulta geral.');
    return false;
  }

  var iLinhas;

  if ((screen.width >= 900) && (screen.height >= 700)) {
    iLinhas = 8;
  } else {
    iLinhas = 5;
  }
  
  var sChave = 'z01_i_cgsund=' + iCgs + '&iLinhas=' + iLinhas;

  js_OpenJanelaIframe(
                       '',
                       'db_iframe_consulta',
                       'sau3_consultasaude002.php?' + sChave,
                       'Consulta Geral da Saúde',
                       true
                     );

  return true;
}

function js_relatorio() {

  if (!js_validar()) {
    return false;
  }

   sd23_d_consulta = $F('sd23_d_consulta');

  iAno            = sd23_d_consulta.substr(6, 4);
  iMes            = parseInt(sd23_d_consulta.substr(3, 2), 10) - 1;
  iDia            = sd23_d_consulta.substr(0, 2);
  dData           = new Date(iAno, iMes, iDia);
  iDiaSemana      = dData.getDay() + 1;

  sUrl            = 'sau2_agendamento003.php?';
  sUrl           += 'sd27_i_codigo='+$F('sd27_i_codigo');
  sUrl           += '&chave_diasemana='+iDiaSemana;
  sUrl           += '&sd23_d_consulta='+sd23_d_consulta;
  sUrl           += '&diasemana='+$F('diasemana');
  sUrl           += '&iUpssolicitante'+$('iUpssolicitante').value;

  oJan            = window.open(sUrl, '', 'width='+(screen.availWidth - 5)+',height='+
                                (screen.availHeight - 40)+',scrollbars=1,location=0'
                               );
  oJan.moveTo(0, 0);
}

function js_transferir() {

  if (!js_validar()) {
    return false;
  }

  var oIframe     = document.getElementById('frameagendados').contentDocument;
  var aCkBox      = oIframe.getElementsByName('ckbox');
  var sAgendas    = '';
  var sSep        = '';

   sd23_d_consulta = $F('sd23_d_consulta');

  iAno            = sd23_d_consulta.substr(6, 4);
  iMes            = parseInt(sd23_d_consulta.substr(3, 2), 10) - 1;
  iDia            = sd23_d_consulta.substr(0, 2);
  dData           = new Date(iAno, iMes, iDia);
  iDiaSemana      = dData.getDay() + 1;

  for (var iCont = 0; iCont < aCkBox.length; iCont++) {

    if (oIframe.getElementById('ckbox_'+iCont).checked) {
      
      sAgendas += sSep+iCont;
      sSep      = ',';
    }
  }

  sUrl  = 'sau4_transfereagenda001.php?';
  sUrl += '&sd27_i_codigo='+$F('sd27_i_codigo');
  sUrl += '&sd02_i_codigo='+$F('sd02_i_codigo');
  sUrl += '&sd03_i_codigo='+$F('sd03_i_codigo');
  sUrl += '&rh70_sequencial='+$F('rh70_sequencial');
  sUrl += '&rh70_estrutural='+$F('rh70_estrutural');
  sUrl += '&rh70_descr='+$F('rh70_descr');
  sUrl += '&z01_nome='+$F('z01_nome');
  sUrl += '&dia='+iDiaSemana;
  sUrl += '&sd23_d_consulta='+sd23_d_consulta;
  sUrl += '&diasemana='+$F('diasemana');
  sUrl += '&lBotao=true';
  sUrl += '&sAgendamentos='+sAgendas;

  js_OpenJanelaIframe('', 'db_iframe_transferencia', sUrl, 'Transferência de Agendamentos', true);
}

function js_ausencia() {

  if (!js_validar()) {
    return false;
  }

  var oIframe     = $('frameagendados').contentDocument;
  var aElementos  = oIframe.getElementsByName('ckbox'); // Pego todos os checkbox
  var iTam        = aElementos.length;
  var lAgendado   = false;
  var lSel        = false;

  for (var iCont = 0; iCont < iTam; iCont++) {
  
    // Se o horário estiver marcado e já tiver paciente agendado, não pode ser marcada ausência para o profissional
    if (oIframe.getElementById('ckbox_'+iCont).checked 
        && oIframe.getElementById('ckbox_'+iCont).value.split(' ## ')[0] != '0') {
      lAgendado = true;
    }
  }

  if (lAgendado){

    alert('Não é possível marcar ausência para horário já agendado.');
    return false;
  }

  iTop  = (screen.availHeight - 600) / 2;
  iLeft = (screen.availWidth - 800) / 2;
  sUrl  = 'sau4_ausenciamedico.iframe.php?';
  sUrl += 'sd06_d_inicio='+$F('sd23_d_consulta');
  sUrl += '&sd06_d_fim='+$F('sd23_d_consulta');
  sUrl += '&sd06_i_especmed='+$F('sd27_i_codigo');
  sUrl += '&rh70_descr='+$F('rh70_descr');
  sUrl += '&sd06_i_medico='+$F('sd03_i_codigo');
  sUrl += '&z01_nome='+$F('z01_nome');
  sUrl += '&sd06_i_unidade='+$F('sd02_i_codigo');
  sUrl += '&descrdepto='+$F('descrdepto');

  var sHoraIni = '';
  var sHoraFim = '';
  var sSep     = '';

  for (var iCont = 0; iCont < iTam; iCont++) {

    // Se o checkbox foi marcado e o horário e possui agendamento
    if (oIframe.getElementById('ckbox_'+iCont).checked) {

      lSel   = true;
      sHoraIni += sSep+oIframe.getElementById('horaini_'+iCont).value; 
      sHoraFim += sSep+oIframe.getElementById('horafim_'+iCont).value;
      sSep      = ',';
    }
  }

  if (!lSel) {

    alert('Selecione pelo menos um horário para marcar ausência para o profissional.');
    return false;
  }

  sUrl += '&sd06_c_horainicio='+sHoraIni;
  sUrl += '&sd06_c_horafim='+sHoraFim;

  js_OpenJanelaIframe(
                       '',
                       'db_iframe_ausencia',
                       sUrl,
                       'Lançamento de Ausência',
                       true,
                       iTop,
                       iLeft,
                       800,
                       450
                     );

  return true;
}

function js_presenca() {

  if (!js_validar()) {
    return false;
  }

  var oIframe     = $('frameagendados').contentDocument;
  var aElementos  = oIframe.getElementsByName('ckbox'); // Pego todos os checkbox
  var iTam        = aElementos.length;
  var oParam      = new Object();
  var sCodigos    = '';
  var sSep        = '';

  for (var iCont = 0; iCont < iTam; iCont++) {

    if (aElementos[iCont].value.split(' ## ')[0] != '0' && aElementos[iCont].checked) {

      sCodigos += sSep+aElementos[iCont].value.split(' ## ')[0];
      sSep      = ',';
    }
  }

  if (sCodigos == '') {

    alert('Selecione pelo menos um agendamento para marcar presença.');
    return false;
  } else {

    oParam.exec          = 'marcarPresencaAgendamentos';
    oParam.sAgendamentos = sCodigos;

    js_ajax(oParam, 'js_retornoPresenca');
  }
}

function js_retornoPresenca(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  message_ajax(oRetorno.sMessage.urlDecode());
  if (oRetorno.iStatus == 1) { // Se não houve erro, atualizo o grid
    js_agendados();
  }
}

function js_observacao() {

  if (!js_validar()) {
    return false;
  }

  sUrl  = 'sau4_obsagendamento.iframe.php';
  sUrl += '?sd23_d_consulta='+$F('sd23_d_consulta');
  sUrl += '&sd27_i_codigo='+$F('sd27_i_codigo');

  js_OpenJanelaIframe('', 'db_iframe_observacao', sUrl, 'Observação', true);
}

function js_validar() {

  if ($F('sd02_i_codigo') == '') {

    alert('Informe a unidade.');
    return false;
  }

  if ($F('sd03_i_codigo') == '') {

    alert('Informe o profissional.');
    return false;
  }

  if ($F('rh70_sequencial') == '' || $F('rh70_estrutural') == '' || $F('sd27_i_codigo') == '') {

    alert('Informe a especialidade.');
    return false;
  }

  if ($F('sd23_d_consulta') == '' || $F('diasemana') == '') {

    alert('Informe a data de consulta.');
    return false;
  }

  return true;
}

</script>