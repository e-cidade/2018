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
$clregenciahorario->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed59_i_codigo");
$clrotulo->label("ed32_i_codigo");
$clrotulo->label("ed17_i_codigo");
$clrotulo->label("ed20_i_codigo");
$clrotulo->label("ed23_i_codigo");
$db_botao1 = false;
?>
<form name="form1" method="post" action="" id="frmGradeHorario">
  <table border="0">
    <tr>
      <td nowrap title="<?=@$Ted58_i_regencia?>">
        <label for="ed58_i_regencia">
          <?php
            db_ancora( @$Led58_i_regencia, "js_pesquisaed58_i_regencia(true);", $db_opcao );
          ?>
        </label>
      </td>
      <td colspan="3">
       <?php
         db_input( 'ed58_i_regencia', 15, "", true, 'text', 3);
         db_input( 'ed232_c_descr',   30, "", true, 'text', 3);
         db_input( 'ed232_c_abrev',   10, "", true, 'text', 3);
         db_input( 'ed58_i_codigo',   15, "", true, 'hidden', 3 );
       ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Ted58_i_rechumano?>">
        <label for="identificacao">
          <?php
            db_ancora( @$Led58_i_rechumano, "js_pesquisaed58_i_rechumano(true);", $db_opcao );
          ?>
        </label>
      </td>
      <td colspan="3">
        <?php
          db_input( 'ed58_i_rechumano', 15, "", true, 'hidden', 3 );
          db_input( 'identificacao',    15, "", true, 'text',   3 );
          db_input( 'z01_nome',         40, "", true, 'text',   3 );
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Ted58_datainicio?>">
        <label for="datainicio" class="bold">
          Vigência do Período:
        </label>
      </td>
      <td style="max-width: 70px;">
        <input id="ed58_datainicio" name="ed58_datainicio" type="text" />
      </td>
      <td><label for="ed58_datafim" class="bold">Até: </label></td>
      <td>
        <input id="ed58_datafim" name="ed58_datafim" type="text" />
      </td>
    </tr>
  </table>
  <table>
    <tr>
      <td>
        <table cellspacing="0" cellpading="0" border="1" bordercolor="#000000">
          <?php
          $sSqlTurmaTurnoAdicional = $clturmaturnoadicional->sql_query( "", "ed246_i_turno", "", "ed246_i_turma = {$ed59_i_turma}" );
          $result_add              = $clturmaturnoadicional->sql_record( $sSqlTurmaTurnoAdicional );

          if( $clturmaturnoadicional->numrows > 0 ) {

            db_fieldsmemory( $result_add, 0 );
            $cod_turnos = "{$ed57_i_turno}, {$ed246_i_turno}";
          } else {
            $cod_turnos = "{$ed57_i_turno}";
          }

          $turno   = "";

          $sOrderPeriodoEscola = "ed15_i_sequencia,ed08_i_sequencia";
          $sWherePeriodoEscola = " ed17_i_escola = {$escola} AND ed17_i_turno in ({$cod_turnos})";
          $sql                 = $clperiodoescola->sql_query( "", "*", $sOrderPeriodoEscola, $sWherePeriodoEscola );
          $result1             = $clperiodoescola->sql_record($sql) or die (pg_errormessage());
          //die($sql);
          $contp   = 0;
          $contd   = 0;
          $contqd  = 0;

          for( $z = 0; $z < $clperiodoescola->numrows; $z++ ) {

            db_fieldsmemory( $result1, $z );
            $contp++;

            $sWhereDiaSemana = "ed04_c_letivo = 'S' AND ed04_i_escola = {$escola}";
            $sSqlDiaSemana   = $cldiasemana->sql_query_rh( "", "*", "ed32_i_codigo", $sWhereDiaSemana );
            $result          = $cldiasemana->sql_record( $sSqlDiaSemana );

            if ($turno != $ed15_c_nome) {
            ?>
            <tr>
              <td colspan="<?=$cldiasemana->numrows+1?>" bgcolor="">
                <label class="bold"><?=$ed15_i_codigo==$ed57_i_turno?"TURNO PRINCIPAL":"TURNO ADICIONAL"?></label>
              </td>
            </tr>
            <tr bgcolor="#444444">
              <td align="center" width="30" style="font-weight: bold; color: #DEB887;">
                <?=pg_result( $result1, $z, "ed15_c_nome" );?>
              </td>
            <?php
            if( $cldiasemana->numrows == 0 ) {
            ?>
              <tr>
                <td>
                  <a href="javascript:parent.location.href='edu1_diasemanaabas001.php'">
                    <label class="bold">Informe os dias lelivos desta escola</label>
                  </a>
                </td>
              </tr>
            <?php
            }

            for( $x = 0; $x < $cldiasemana->numrows; $x++ ) {

              $contd++;
              db_fieldsmemory( $result, $x );
              ?>
              <td>
                <table cellspacing="0" cellpading="0" >
                  <tr>
                    <td width="50" style="font-weight: bold; color: #DEB887;">
                      <div align="center"><?=$ed32_c_abrev?></div>
                    </td>
                  </tr>
                </table>
              </td>
            <?php
            }
            ?>
            </tr>
            <?php
          }

          $turno = $ed15_c_nome
          ?>
          <td align="center" width="120" style="font-weight: bold; background-color: #f3f3f3;">
            <?=$ed08_c_descr?> - <?=$ed17_h_inicio?> / <?=$ed17_h_fim?>
          </td>
          <?php
          for( $x = 0; $x < $cldiasemana->numrows; $x++ ) {

            $quadro = "Q".$z.$x;
            $contqd++;

            db_fieldsmemory( $result, $x );

            $sCampos  = " case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome";
            $sCampos .= ", ed58_i_codigo, ed58_i_periodo, ed58_i_diasemana, ed232_c_abrev";
            $sCampos .= ", ed58_i_regencia, ed232_c_descr, ed58_i_rechumano ";
            $sWhere   = " ed58_i_diasemana = {$ed32_i_codigo} AND ed58_i_periodo = {$ed17_i_codigo} ";
            $sWhere  .= " AND ed59_i_turma = {$ed59_i_turma} AND ed59_i_serie = {$ed59_i_serie}";
            $sWhere  .= " and ed17_i_escola = {$escola} and ed58_ativo is true";
            $sSql     = $clregenciahorario->sql_query( '', $sCampos, '', $sWhere );
            $result2  = $clregenciahorario->sql_record( $sSql );

            if( $clregenciahorario->numrows > 0 ) {

              db_fieldsmemory( $result2, 0 );

              $marcar       = $ed232_c_abrev;
              $valormarcado = $ed58_i_regencia . "|" . $ed58_i_diasemana . "|" . $ed58_i_periodo . "|" . $ed58_i_rechumano;
              $temregistro  = $ed58_i_codigo;
              $temcodrh     = $ed58_i_rechumano;
              $disci        = $ed232_c_descr;
              $regente      = $z01_nome;
            } else {

              $marcar       = "";
              $valormarcado = '';
              $temregistro  = '';
              $temcodrh     = '';
              $disci        = '';
              $regente      = '';
            }
            ?>

            <td>
              <table cellspacing="0" cellpading="0" marginwidth="0">
                <tr>
                  <td>
                    <table class="texto" bgcolor="#cccccc" id="<?=$quadro?>" cellspacing="0" cellpading="0"
                        style="border: 2px outset #f3f3f3; border-bottom-color:#999999; border-right-color:#999999;">
                      <tr>

                       <td align="center" onclick="IncluirHorario(<?=$ed17_i_codigo?>,<?=$ed32_i_codigo?>,'<?=$quadro?>',
                       '<?=@$ed58_i_rechumano?>')" width="50" height="15" onmouseover="InSet('<?=$quadro?>')"
                        onmouseout="OutSet('<?=$quadro?>')">
                          <input type="text"
                                 id="text<?=$quadro?>"
                                 name="text<?=$quadro?>"
                                 value="<?=$marcar?>"
                                 size="7"
                                 style="border:0px;background:#cccccc;text-align:center;font-weight:bold;"
                                 readonly>
                          <input type="hidden"
                                 id="valor<?=$quadro?>"
                                 name="valor<?=$quadro?>"
                                 value="<?=$valormarcado?>"
                                 size="20"
                                 readonly>
                          <input type="hidden"
                                 id="marcado<?=$quadro?>"
                                 name="marcado<?=$quadro?>"
                                 value="<?=$temregistro?>"
                                 size="20"
                                 readonly>
                          <span style="visibility:hidden;position:absolute;" id="codrh<?=$quadro?>">
                            <?=$temcodrh?>
                          </span>
                        </td>
                      </tr>
                    </table>
                    <div name="dados<?=$quadro?>" id="dados<?=$quadro?>" style="visibility:hidden;position:absolute;">
                      <table bgcolor="#f3f3f3" style="border:2px outset #999999;">
                        <tr>
                          <td style="font-size: 9px;">
                            Dados do Horário
                          </td>
                        </tr>
                        <tr>
                          <td height="1" bgcolor="#999999"></td>
                        </tr>
                        <tr>
                          <td style="font-size: 9px;">
                            TURNO: <?=$ed15_c_nome?><br>
                            <?=$ed32_c_descr?><br>
                            Período: <?=$ed08_c_descr?><br>
                            Disciplina: <span id="disc<?=$quadro?>"><?=$disci?></span><br>
                            Regente: <font color="#FF0000"><span id="rh<?=$quadro?>"><?=$regente?></span></font>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </td>
                </tr>
              </table>
            </td>
            <?php
            $marcar           = "";
            $ed58_i_rechumano = "";
          }
          ?>
          <tr>
          <?}?>
          </tr>
        </table>
      </td>
      <td height="15">&nbsp;&nbsp;</td>
      <td valign="top" align="center">
        <table align="center" id="legenda" cellspacing="2" cellpadding="3"
             style="visibility:hidden;border:1px solid #888888;background:#f3f3f3">
          <tr>
            <td colspan="2" align="center" style="font-size:9px;">
              <span id="nome_selec" class="bold"></span>
            </td>
          </tr>
          <tr>
            <td valign="top" >
              <table border="1" width="40" height="20" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td bgcolor="#CCFFCC"></td>
                </tr>
              </table>
            </td>
            <td style="font-size:11px;">
              <label class="bold">Horários disponíveis</label>
            </td>
          </tr>
          <tr>
            <td valign="top" >
              <table border="1" width="40" height="20" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td bgcolor="#6495ED"></td>
                </tr>
              </table>
            </td>
            <td style="font-size:9px;">
              <label class="bold">
                <font style="font-size:11px;">Horários disponíveis.</font><br>
                Professor poderá atender<br>
                turmas simultaneamente.
              </label>
            </td>
          </tr>
          <tr>
            <td valign="top" >
              <table border="1" width="40" height="20" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td bgcolor="#FF9900"></td>
                </tr>
              </table>
            </td>
            <td style="font-size:9px;">
              <label class="bold">
                <font style="font-size:11px;">Horários NÃO disponíveis.</font><br>
                Professor não está disponível<br>
                neste(s) horário(s) na escola.
              </label>
            </td>
          </tr>
          <tr>
            <td valign="top" >
              <table border="1" width="40" height="20" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td bgcolor="#FF0000"></td>
                </tr>
              </table>
            </td>
            <td style="font-size:9px;">
              <label class="bold">
                <font style="font-size:11px;">Horários NÃO disponíveis.</font><br>
                Professor já tem este(s) horário(s)<br>
                marcado(s) em outra turma e/ou escola.<br>
                (Clique no quadro para ver detalhes)
              </label>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br>
  <label class="bold" for="regente-conselheiro">Informe o regente conselheiro desta turma:</label>
  <select name="conselheiro" id='regente-conselheiro' style="width:300px">
    <option value=''></option>
    <?php
    $sCampos     = "DISTINCT ed20_i_codigo,";
    $sCampos    .= " case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome";

    $sWhereRegenciaHorario = "ed59_i_turma = {$ed59_i_turma} and ed58_ativo is true";
    $sSql                  = $clregenciahorario->sql_query( "", $sCampos, "z01_nome", $sWhereRegenciaHorario );
    $result_cons           = $clregenciahorario->sql_record( $sSql );

    for( $r = 0; $r < $clregenciahorario->numrows; $r++ ) {

      db_fieldsmemory( $result_cons, $r );

      $sWhereRegenteConselho = "ed235_i_turma = {$ed59_i_turma} AND ed235_i_rechumano = {$ed20_i_codigo}";
      $sSqlRegenteConselho   = $clregenteconselho->sql_query(
                                                              "",
                                                              "ed235_i_rechumano as reg_conselheiro",
                                                              "",
                                                              $sWhereRegenteConselho
                                                            );
      $result2 = $clregenteconselho->sql_record( $sSqlRegenteConselho );

      if( $clregenteconselho->numrows > 0 ) {

         db_fieldsmemory( $result2, 0 );
         $selected = "selected";
      } else{
        $selected = "";
      }

      echo "<option value='$ed20_i_codigo' $selected>$z01_nome</option>";
    }
    ?>
  </select>
  <?php
  $reg_conselheiro = isset( $reg_conselheiro) ? $reg_conselheiro : "";
  ?>
  <input name="cons_selected" value="<?=@$reg_conselheiro?>" size="20" type="hidden">
  <br><br>
  <input name="incluir" type="submit" value="Salvar" onclick="return validaDados();">
  <input name="limpar" type="button" value="Limpar Tudo"
         onclick="limparGrade()">
  <input id="contp" name="contp" value="<?=$contp?>" size="5" type="hidden">
  <input id="contd" name="contd" value="<?=$cldiasemana->numrows?>" size="5" type="hidden">
  <input name="ed59_i_turma" value="<?=@$ed59_i_turma?>" size="20" type="hidden">
  <input name="ed59_i_serie" value="<?=@$ed59_i_serie?>" size="20" type="hidden">
  <input name="ed57_c_descr" value="<?=@$ed57_c_descr?>" size="20" type="hidden">
  <input name="ed11_c_descr" value="<?=@$ed11_c_descr?>" size="20" type="hidden">
  <input name="ed57_i_turno" value="<?=@$ed57_i_turno?>" size="20" type="hidden">
  <fieldset>
    Primeiro selecione a disciplina e o regente, depois, caso seja necessário, altere a vigência do período e então clique nos quadros para marcar os horários.<br>
    Clique em "Salvar" para confirmar o cadastro dos horários.<br>
    Para desmarcar um horário, clique sobre o quadro referente, selecione o tipo de remoção e confirme a exclusão. <br>
    Para desmarcar todos, clique em "Limpar Tudo", selecione o tipo de remoção e confirme a exclusão.<br>

  </fieldset>
</form>
<iframe name="Verifica" src="" frameborder="5" width="90%" height="200" style="display:none;"></iframe>
<script>

var sUrlMsg = 'educacao.escola.db_frmregenciahorario.';

var oGet = js_urlToObject();

function js_pesquisaed58_i_regencia(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe(
                         '',
                         'db_iframe_regencia',
                         'func_regencia.php?turma=<?=@$ed59_i_turma?>&serieregencia=<?=@$ed59_i_serie?>',
                         'Pesquisa de Disciplinas da Turma',
                         true
                       );
  }
}

function js_mostraregencia1( chave1, chave2, chave3 ) {

  document.form1.ed58_i_regencia.value                = chave1;
  document.form1.ed232_c_descr.value                  = chave2;
  document.form1.ed232_c_abrev.value                  = chave3;
  document.form1.ed58_i_rechumano.value               = '';
  document.form1.z01_nome.value                       = '';
  document.form1.identificacao.value                  = '';
  document.getElementById("nome_selec").innerHTML     = "";
  document.getElementById("legenda").style.visibility = "hidden";

  Verifica.location.href = "edu1_regenciahorario002.php?disponibilidade&codcalendario=<?=$codcalendario?>"
                                                     +"&ed57_i_turno=<?=$cod_turnos?>"
                                                     +"&rechumano=0&maisturmas=<?=$maisturmas?>";
  db_iframe_regencia.hide();
}

function js_pesquisaed58_i_rechumano( mostra ) {

  if( document.form1.ed58_i_regencia.value == "" ) {

    alert( "Informe a Disciplina!" );
    document.form1.ed58_i_rechumano.value                = '';
    document.form1.ed58_i_regencia.style.backgroundColor = '#99A9AE';
    document.form1.ed58_i_regencia.focus();
  } else {

    if (mostra == true) {

      js_OpenJanelaIframe(
                           '',
                           'db_iframe_rechumano',
                           'func_rechumanoreg.php?regencia='+document.form1.ed58_i_regencia.value
                                               +'&funcao_js=parent.js_mostrarechumano1|ed20_i_codigo|z01_nome|dl_identificacao',
                           'Pesquisa de Recursos Humanos',
                           true
                         );
    }
  }
}

function js_mostrarechumano1( chave1, chave2, chave3 ) {

  document.form1.ed58_i_rechumano.value               = chave1;
  document.form1.z01_nome.value                       = chave2;
  document.form1.identificacao.value                  = chave3;
  document.getElementById("nome_selec").innerHTML     = chave2;
  document.getElementById("legenda").style.visibility = "visible";

  Verifica.location.href = "edu1_regenciahorario002.php?disponibilidade&codcalendario=<?=$codcalendario?>"
                                                     +"&ed57_i_turno=<?=$cod_turnos?>"
                                                     +"&rechumano="+chave1
                                                     +"&maisturmas=<?=$maisturmas?>";
  db_iframe_rechumano.hide();
}

function InSet( id ) {

  T = document.getElementById( id );
  D = document.getElementById( "dados" + id );

  T.style.border     = "2px inset #f3f3f3";
  D.style.visibility = "visible";
}

function OutSet( id ) {

  T = document.getElementById( id );
  D = document.getElementById( "dados" + id );

  T.style.border            = "2px outset #f3f3f3";
  T.style.borderBottomColor = "#999999";
  T.style.borderRightColor  = "#999999";
  T.style.fontSize          = "11px;";
  D.style.visibility        = "hidden";
}
var oViewRemocao;

function IncluirHorario( periodo, diasemana, id, codrechumano ) {
  
  if( document.getElementById("text" + id).value != "" ) {

    if (document.getElementById("marcado"+id).value != "") {

      if (oViewRemocao != null) {
        return;
      }
      oViewRemocao = new DBViewRemocaoPeriodoGradeHorario(oGet.ed59_i_turma, oGet.ed59_i_serie, $F(("marcado"+id)), codrechumano);
      oViewRemocao.setCallback(function () {
        atualizaGradeHorario(id);
        oViewRemocao = null;
      });      
      oViewRemocao.setCallBackFechar(function () {
        oViewRemocao = null;
      });

      oViewRemocao.show();
    } else {

      if (document.form1.ed58_i_rechumano.value != "") {
        codrechumano = document.form1.ed58_i_rechumano.value;
      } else {
        codrechumano = 0;
      }

      atualizaGradeHorario(id);
      Verifica.location.href = "edu1_regenciahorario002.php?disponibilidade&codcalendario=<?=$codcalendario?>"
                                                         +"&ed57_i_turno=<?=$cod_turnos?>"
                                                         +"&rechumano="+codrechumano
                                                         +"&maisturmas=<?=$maisturmas?>";
    }

  } else {

    if (document.form1.ed58_i_regencia.value == "" || document.form1.ed58_i_rechumano.value == "") {
      alert( "Informe a Disciplina e o Regente." );
    } else {

      Verifica.location.href = "edu1_regenciahorario002.php?codcalendario=<?=$codcalendario?>"
                               +"&ed57_i_turno=<?=$cod_turnos?>"
                               +"&quadro="+id
                               +"&chavepesquisa="+document.form1.ed58_i_regencia.value
                               +"&diasemana="+diasemana
                               +"&periodo="+periodo
                               +"&rechumano="+document.form1.ed58_i_rechumano.value
                               +"&maisturmas=<?=$maisturmas?>";
    }
  }
}


function atualizaGradeHorario(id) {

  qdr_atual = document.getElementById( "codrh" + id ).innerHTML.trim();
  document.getElementById("marcado"+id).value   = "";
  document.getElementById("text"+id).value      = "";
  document.getElementById("valor"+id).value     = "";
  document.getElementById("disc"+id).innerHTML  = "";
  document.getElementById("rh"+id).innerHTML    = "<font color='#FF0000'>HORÀRIO LIVRE</font>";
  document.getElementById("codrh"+id).innerHTML = "";
  aindatem = false;

  for (z = 0; z < <?=$clperiodoescola->numrows?>; z++) {

    for (x = 0; x < <?=$cldiasemana->numrows?>; x++) {

      qdr = "codrhQ"+z+x;
      if (document.getElementById(qdr).innerHTML.trim() == qdr_atual) {
        aindatem = true;
      }
    }
  }

  if (aindatem == false) {

    tam = document.form1.conselheiro.length;
    for (i = 0; i < tam; i++) {

      if (document.form1.conselheiro.options[i].value == qdr_atual) {

        document.form1.conselheiro.options[i] = null;
        break;
      }
    }

    ordenarLista(document.form1.conselheiro);
    document.form1.conselheiro.value = document.form1.cons_selected.value;
  }
}


function ordenarLista( select ) {

  arrTextos       = new Array(); // text de cada option
  arrValues       = new Array(); // value de cada option
  arrGuardaTextos = new Array(); // text de cada option de novo
  arrTextos[0]    = arrValues[0] = arrGuardaTextos[0] = "";
  var total       = select.length;

  for (i = 1; i < total; i++) {

    arrTextos[i]       = select.options[i].text;
    arrValues[i]       = select.options[i].value;
    arrGuardaTextos[i] = select.options[i].text;
  }

  arrTextos.sort();
  for (i = 1; i < total; i++) {

    select.options[i].text = arrTextos[i];
    for (j = 1; j < total; j++) {

      if (arrTextos[i] == arrGuardaTextos[j]) {

        select.options[i].value = arrValues[j];
        j = select.length;
      }
    }
  }
}

function validaDados() {

  var oDataInicioCalendario = Date.convertFromUTC(oDatasCalendario.dataInicio, 'd/m/Y');
  var oDataFimCalendario    = Date.convertFromUTC(oDatasCalendario.dataFim, 'd/m/Y');

  if (oDataInicio.getValue() == null) {
    alert( _M(sUrlMsg + 'data_inicio_vazia', oDatasCalendario) );
    return false;
  }

  if (oDataFim.getValue() == null) {
    alert( _M(sUrlMsg + 'data_fim_vazia', oDatasCalendario) );
    return false;
  }

  if (    oDataInicio.getValue().getTime() < oDataInicioCalendario.getTime()
      ||  oDataInicio.getValue().getTime() > oDataFimCalendario.getTime()) {

    alert( _M(sUrlMsg + 'data_inicio_invalida', oDatasCalendario) );
    return false;
  }

  if (   oDataFim.getValue().getTime() > oDataFimCalendario.getTime()
      || oDataFim.getValue().getTime() < oDataInicioCalendario.getTime()) {

    alert(_M(sUrlMsg + 'data_fim_invalida', oDatasCalendario));
    return false;
  }

  if (oDataFim.getValue().getTime() < oDataInicio.getValue().getTime()) {

    alert(_M(sUrlMsg + 'data_fim_menor_que_inicio', oDatasCalendario));
    return false;
  }

  return true;
}


/**
 * Objeto datas de inicio e fim da regencia do professor.
 * @type {DBInputDate}
 */
var oDataInicio = new DBInputDate($('ed58_datainicio'));
var oDataFim    = new DBInputDate($('ed58_datafim'));

/**
 * Datas de inicio e fim do calendário em string
 * @type {Object}
 */
var oDatasCalendario = {};

$('ed58_i_regencia').addClassName('field-size2');
$('ed232_c_abrev').addClassName('field-size2');
$('identificacao').addClassName('field-size2');


new AjaxRequest('edu4_calendario.RPC.php', {exec: 'dadosCalendarioTurma', iTurma : oGet.ed59_i_turma}, function (oRetorno, lErro) {

  if (lErro) {

    alert(oRetorno.message);
    return;
  }

  oDatasCalendario = {
    'dataInicio' : oRetorno.dataInicio,
    'dataFim'    : oRetorno.dataFim
  }
  oDataInicio.value = oDatasCalendario.dataInicio;
  oDataFim.value    = oDatasCalendario.dataFim;

}).setMessage( _M(sUrlMsg + 'buscando_dados_calendario') ).execute();

document.getElementById('z01_nome').style.width = "320px";

function limparGrade() {

  if (oViewRemocao != null) {
    return;
  }

  oViewRemocao = new DBViewRemocaoPeriodoGradeHorario(oGet.ed59_i_turma, oGet.ed59_i_serie );
  oViewRemocao.setCallback(function () {

    var url  = 'edu1_regenciahorario001.php?ed59_i_turma=' + oGet.ed59_i_turma;
        url += '&ed57_c_descr=' + oGet.ed57_c_descr;
        url += '&ed57_i_turno=' + oGet.ed57_i_turno;
        url += '&ed59_i_serie=' + oGet.ed59_i_serie;
        url += '&ed11_c_descr=' + oGet.ed11_c_descr;

    location.href = url;
  });
  oViewRemocao.setCallBackFechar(function () {
    oViewRemocao = null;
  });

  oViewRemocao.show();
}

</script>
