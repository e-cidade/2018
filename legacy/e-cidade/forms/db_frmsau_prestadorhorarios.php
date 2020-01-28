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
$clsau_prestadorhorarios->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("s111_i_prestador");
$clrotulo->label("sd63_i_codigo");
$clrotulo->label("s111_procedimento");
$clrotulo->label("sd63_c_procedimento");
$clrotulo->label("sd63_c_nome");
$clrotulo->label("s112_c_tipograde");
$clrotulo->label("s111_procedimento");

?>
<form name="form1" method="post" onsubmit="return validaDadosEnviados();">
  <table width="80%">
    <tr>
      <td>
        <fieldset>
          <legend>Horário de Atendimento</legend>
          <center>
            <table>
              <tr>
                <td nowrap title="<?=$Ts111_i_prestador?>"><?=$Ls111_i_prestador?></td>
                <td>
                  <?
                  db_input( 's111_i_prestador', 10, $Is111_i_prestador, true, 'text', 3 );
                  db_input( 'z01_nome',         59, $Iz01_nome,          true, 'text', 3 );
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=$Ts111_procedimento?>">
                  <?
                  db_ancora( $Ls111_procedimento, "js_pesquisas111_i_exame(true);", $db_opcao );
                  ?>
                </td>
                <td>
                  <?php
                  $sScript = " onchange='js_pesquisas111_i_exame(false);'";
                  db_input( 's112_i_codigo',        10, $Is112_i_codigo,        true, 'hidden', $db_opcao );
                  db_input( 's112_i_prestadorvinc', 10, $Is112_i_prestadorvinc, true, 'hidden', $db_opcao );
                  db_input( 'sd63_i_codigo',        10, $Isd63_i_codigo,        true, 'hidden', $db_opcao );
                  db_input( 'sd63_c_procedimento',  10, $Isd63_c_procedimento,  true, 'text',   $db_opcao, $sScript );
                  db_input( 'sd63_c_nome',          59, $Isd63_c_nome,          true, 'text',   3 );
                  ?>
                </td>
              </tr>
              <tr>
                <td colspan="2" >
                  <fieldset>
                    <legend>Lançamento</legend>
                    <table>
                      <tr>
                        <td rowspan="2">
                          <table>
                            <tr>
                              <td nowrap title="<?=$Ts112_c_tipograde?>"><?=$Ls112_c_tipograde?></td>
                              <td>
                                <?php
                                $x = array( 'I' => 'Intervalo', 'P' => 'Período' );
                                db_select('s112_c_tipograde',$x,true,$db_opcao,"");
                                ?>
                              </td>
                            </tr>
                            <tr style="display: none;">
                              <td nowrap title="<?=$Ts112_i_tipoficha?>"><?=$Ls112_i_tipoficha?></td>
                              <td>
                                <?php
                                $result = $clsau_tipoficha->sql_record( $clsau_tipoficha->sql_query( "", "*" ) );
                                db_selectrecord( "s112_i_tipoficha", $result, true, $db_opcao, '', '', '', '', '', 1 );
                                ?>
                              </td>
                            </tr>
                            <tr>
                              <td nowrap title="<?=$Ts112_i_diasemana?>"><?=$Ls112_i_diasemana?></td>
                              <td>
                                <?php
                                db_input( 's112_i_diasemana_atual', 10, '', true, 'hidden', 3 );
                                $result = $cldiasemana->sql_record( $cldiasemana->sql_query( "", "*" ) );
                                db_selectrecord( "s112_i_diasemana", $result, true, $db_opcao, '', '', '', '', '', 1 );
                                ?>
                              </td>
                            </tr>
                            <tr>
                              <td nowrap title="<?=$Ts112_i_fichas?>"><?=$Ls112_i_fichas?></td>
                              <td>
                                <?php
                                db_input( 's112_i_fichas', 10, $Is112_i_fichas, true, 'text', $db_opcao );
                                ?>
                              </td>
                            </tr>
                            <tr>
                              <td nowrap title="<?=$Ts112_i_reservas?>"><?=$Ls112_i_reservas?></td>
                              <td>
                                <?php
                                db_input( 's112_i_reservas', 10, $Is112_i_reservas, true, 'text', $db_opcao );
                                ?>
                              </td>
                            </tr>
                          </table>
                        </td>
                        <td>
                          <table>
                            <tr>
                              <td>
                                <fieldset>
                                  <legend>Data Validade</legend>
                                  <table>
                                    <tr>
                                      <td nowrap title="<?=$Ts112_d_valinicial?>"><?=$Ls112_d_valinicial?></td>
                                      <td>
                                        <?php
                                        db_inputdata(
                                                      's112_d_valinicial',
                                                      @$s112_d_valinicial_dia,
                                                      @$s112_d_valinicial_mes,
                                                      @$s112_d_valinicial_ano,
                                                      true,
                                                      'text',
                                                      $db_opcao
                                                    );
                                        ?>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td nowrap title="<?=$Ts112_d_valfinal?>"><?=$Ls112_d_valfinal?></td>
                                      <td>
                                        <?php
                                        db_inputdata(
                                                      's112_d_valfinal',
                                                      @$s112_d_valfinal_dia,
                                                      @$s112_d_valfinal_mes,
                                                      @$s112_d_valfinal_ano,
                                                      true,
                                                      'text',
                                                      $db_opcao
                                                    );
                                        ?>
                                      </td>
                                    </tr>
                                  </table>
                                </fieldset>
                              </td>
                              <td>
                                <fieldset>
                                  <legend>Horário</legend>
                                  <table>
                                    <tr>
                                      <td nowrap title="<?=$Ts112_c_horaini?>"> <?=$Ls112_c_horaini?> </td>
                                      <td>
                                        <?php
                                          db_input( 's112_c_horaini', 10, $Is112_c_horaini, true, 'text', $db_opcao,"","","","",5);
                                        ?>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td nowrap title="<?=$Ts112_c_horafim?>"> <?=$Ls112_c_horafim?> </td>
                                      <td>
                                        <?php
                                          db_input( 's112_c_horafim', 10, $Is112_c_horafim, true, 'text', $db_opcao,"onblur=validaHora();","","","",5);
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
              <tr id="msg_cota" style="">
                <td colspan='2' rowspan="10" valign="top" style="background-color: #fcf8e3 ; border: 1px solid #fcc888 ; padding: 10px 60px;color:red;">
                  Se o mesmo exame tiver Cota Diária definida e Cota Mensal no mesmo período, prevalecerá a <br />
                  Cota Mensal, ou seja, a Cota Diária será desconsiderada, considerando-se apenas a Cota Mensal.
                </td>
              </tr>
            </table>
          <center>
		      <input name="<?=( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) )?>"
		             type="submit"
                 id="db_opcao"
		             value="<?=( $db_opcao == 1 ? "Lançar" : ( $db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir" ) )?>" >
          <input type="button"
                 name="limpa"
                 value="Limpar"
                 onclick="location.href='sau1_sau_prestadorhorarios001.php?s111_i_prestador=<?=$s111_i_prestador?>&z01_nome=<?=$z01_nome?>'">
          </center>

          <table width="100%">
            <tr>
              <td valign="top"><br>
                <?php
                $iProcedimento = null;
                if ( !empty($s111_procedimento) ) {
                  $iProcedimento = $s111_procedimento;
                }
                $iPrestadorHorario = null;
                if ( !empty($s112_i_codigo) ) {
                  $iPrestadorHorario = $s112_i_codigo;
                }
                $chavepri    = array( "s111_procedimento" => $iProcedimento, "s112_i_codigo" => $iPrestadorHorario );
                $sCamposSql  = "s112_i_codigo, s111_procedimento, sd63_c_procedimento, sd63_c_nome, s112_d_valinicial";
                $sCamposSql .= ", s112_d_valfinal, case s112_c_tipograde ";
                $sCamposSql .= "                        when 'I' ";
                $sCamposSql .= "                             then 'Intervalo' ";
                $sCamposSql .= "                        when 'P' ";
                $sCamposSql .= "                             then 'Período' ";
                $sCamposSql .= "                             else 'Não Informado' ";
                $sCamposSql .= "                    end as s112_c_tipograde ";
                $sCamposSql .= ", ed32_c_descr, s112_c_horaini, s112_c_horafim, s112_i_fichas, s112_i_reservas";

                $sOrdenacao = "ed32_i_codigo, s112_c_horaini";
                $sWhere     = " s111_i_prestador = {$s111_i_prestador} AND s111_c_situacao = 'A' and s112_c_tipograde <> 'M' ";

                $sCampos  = "s112_i_codigo, sd63_c_procedimento, sd63_c_nome, s112_d_valinicial, s112_d_valfinal,";
                $sCampos .= " s112_c_tipograde, ed32_c_descr, s112_c_horaini, s112_c_horafim, s112_i_fichas, s112_i_reservas";

                $cliframe_alterar_excluir->chavepri      = $chavepri;
                $cliframe_alterar_excluir->sql           = $clsau_prestadorhorarios->sql_query( "", $sCamposSql, $sOrdenacao, $sWhere );
                $cliframe_alterar_excluir->campos        = $sCampos;
                $cliframe_alterar_excluir->legenda       = "Grade de Horário";
                $cliframe_alterar_excluir->alignlegenda  = "left";
                $cliframe_alterar_excluir->iframe_width  = "100%";
                $cliframe_alterar_excluir->tamfontecabec = 9;
                $cliframe_alterar_excluir->tamfontecorpo = 9;
                $cliframe_alterar_excluir->formulario    = false;
                $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao2);

                ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
 </table>
</form>

<script type="text/javascript">

const MENSAGENS_DB_FRMSAU_PRESTADORHORARIOS = 'saude.agendamento.db_frmsau_prestadorhorarios.';

var db_opcao = "<?=$db_opcao?>";
new DBInputHora( $('s112_c_horaini') );
new DBInputHora( $('s112_c_horafim') );

$('msg_cota').hide();

if ( $F('s111_i_prestador') != "" ) {

  var oParametros = {
    sExecucao : 'buscarCotas',
    iPrestador : $F('s111_i_prestador')
  }

  var oRequest = new AjaxRequest('sau1_prestadorcotamensal.RPC.php', oParametros);

  oRequest.setCallBack(function( oRetorno, lErro ){

    if (lErro) {
      alert(oRetorno.sMensagem);
      return false;
    }

    if (oRetorno.aCotas.length > 0) {
      $('msg_cota').show();
    }
  }.bind(this));
  oRequest.setMessage("Verificando cota mensal");
  oRequest.execute();
}

$('s112_c_horaini').addClassName('field-size2');
$('s112_c_horafim').addClassName('field-size2');

$('s112_c_tipograde').addClassName('field-size2');
$('s112_i_tipoficha').addClassName('field-size2');
$('s112_i_diasemana').addClassName('field-size2');
$('s112_i_fichas').addClassName('field-size2');
$('s112_i_reservas').addClassName('field-size2');

js_setDiaSemanaAtual();

function validaHora() {

  if( empty( $F('s112_c_horaini') ) ) {

    alert( _M( MENSAGENS_DB_FRMSAU_PRESTADORHORARIOS + 'informe_hora_inicial' ) );
    $('s112_c_horafim').value = '';
    $('s112_c_horaini').focus();

    return false;
  }

  if( !empty( $F('s112_c_horaini') ) && !empty( $F('s112_c_horafim') ) && $F('s112_c_horaini') > $F('s112_c_horafim') ) {

    alert( _M( MENSAGENS_DB_FRMSAU_PRESTADORHORARIOS + 'hora_inicial_maior_final' ) );
    $('s112_c_horaini').value = '';
    $('s112_c_horafim').value = '';
    $('s112_c_horaini').focus();

    return false;
  }

  return true;
}

function js_pesquisas111_i_exame( mostra ) {

  if( mostra == true ) {

    js_OpenJanelaIframe(
                         '',
                         'db_iframe_sau_prestadorvinculos',
                         'func_sau_prestadorvinculos.php?chave_s111_i_prestador=' + document.form1.s111_i_prestador.value
                                                      +'&funcao_js=parent.js_mostraexame1|sd63_i_codigo|sd63_c_procedimento'
                                                                                       +'|sd63_c_nome|s111_i_codigo'
                                                      +'&lProcedimentosAgendamento',
                         'Pesquisa',
                         true
                       );
  } else {

    if( $('sd63_c_procedimento').value != '' ) {

      js_OpenJanelaIframe(
                           '',
                           'db_iframe_sau_prestadorvinculos',
                           'func_sau_prestadorvinculos.php?chave_s111_i_prestador=' + document.form1.s111_i_prestador.value
                                                        +'&pesquisa_chave=' + $F('sd63_c_procedimento')
                                                        +'&funcao_js=parent.js_mostraexame1'
                                                        +'&lProcedimentosAgendamento',
                           'Pesquisa',
                           false
                         );
    } else {

      $('sd63_i_codigo').value       = '';
      $('sd63_c_nome').value         = '';
      $('sd63_c_procedimento').value = '';
      $('s112_i_prestadorvinc').value = '';
    }
  }
}

function js_mostraexame1() {

  if( arguments[1] !== true && arguments[1] !== false ) {

    $('sd63_i_codigo').value        = arguments[0];
    $('sd63_c_procedimento').value  = arguments[1];
    $('sd63_c_nome').value          = arguments[2];
    $('s112_i_prestadorvinc').value = arguments[3];
  } else if( arguments[1] === false ) {

    $('s112_i_prestadorvinc').value = arguments[0];
    $('sd63_i_codigo').value        = arguments[2];
    $('sd63_c_nome').value          = arguments[3];
  } else if( arguments[1] === true ) {

    $('s112_i_prestadorvinc').value = '';
    $('sd63_i_codigo').value        = '';
    $('sd63_c_procedimento').value  = '';
    $('sd63_c_nome').value          = arguments[0];
  }

  db_iframe_sau_prestadorvinculos.hide();
}

function js_setDiaSemanaAtual() {

  if ( db_opcao == 2 ) {

    var dia_semana = document.getElementById( "s112_i_diasemana" );
    $('s112_i_diasemana_atual').value = dia_semana.options[ dia_semana.selectedIndex ].value;
  }
}

function validaDadosEnviados() {

  if ( $F('sd63_c_procedimento') == '' ) {
    alert (_M( MENSAGENS_DB_FRMSAU_PRESTADORHORARIOS + "informe_exame"));
    return false;
  }
  if ( $F('s112_i_fichas') == '' ) {
    alert (_M( MENSAGENS_DB_FRMSAU_PRESTADORHORARIOS + "informe_numero_fichas"));
    return false;
  }
  if ( $F('s112_i_reservas') == '' ) {
    alert (_M( MENSAGENS_DB_FRMSAU_PRESTADORHORARIOS + "informe_ficha_reserva"));
    return false;
  }
  if ( $F('s112_c_horaini') == '' ) {
    alert (_M( MENSAGENS_DB_FRMSAU_PRESTADORHORARIOS + "informe_hora_inicial"));
    return false;
  }
  if ( $F('s112_c_horafim') == '' ) {
    alert (_M( MENSAGENS_DB_FRMSAU_PRESTADORHORARIOS + "informe_hora_final"));
    return false;
  }
  return true;
}
</script>