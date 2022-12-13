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
$oDaoAgendamentos->rotulo->label();

$oRotulo = new rotulocampo;

//Médico
$oRotulo->label('sd03_i_codigo');
$oRotulo->label('z01_nome');
//Unidades
$oRotulo->label('sd02_i_codigo');
//Unidade / Medicos
$oRotulo->label('sd04_i_cbo');
//undmedhorario
$oDaoUndMedHorario->rotulo->label();
//especmedico
$oRotulo->label('sd27_i_codigo');

//CBO
$oRotulo->label('rh70_sequencial');
$oRotulo->label('rh70_estrutural');
$oRotulo->label('rh70_descr');
?>

<form name="form1" method="post">
  <table>
    <tr>
      <td>
        <fieldset>
          <legend>Transferência de Agenda por Especialização</legend>
          <table>
            <tr>
              <td valign="top" colspan="2">
                <fieldset>
                  <legend>De</legend>
                  <table>
                    <!-- PROFISSIONAL -->
                    <tr>
                      <td nowrap title="<?=$Tsd03_i_codigo?>">
                        <?php
                        db_ancora( $Lsd03_i_codigo, "js_pesquisasd03_i_codigo(true, 1);", $db_opcao );
                        ?>
                      </td>
                      <td valing="top" align="top">
                        <?php
                        $sScript = " onchange='js_pesquisasd03_i_codigo(false, 1);' onFocus=\"nextfield='rh70_estrutural'\"";
                        db_input( 'sd02_i_codigo', 10, $Isd02_i_codigo, true, 'hidden', $db_opcao );
                        db_input( 'sd03_i_codigo', 10, $Isd03_i_codigo, true, 'text',   $db_opcao, $sScript);
                        ?>
                      </td>
                      <td colspan="2">
                        <?php
                        db_input( 'z01_nome', 30, $Iz01_nome, true, 'text', 3 );
                        ?>
                      </td>
                    </tr>
                    <!-- CBO -->
                    <tr>
                      <td nowrap title="<?=$Tsd04_i_cbo?>">
                        <?php
                        db_ancora( $Lsd04_i_cbo, "js_pesquisasd04_i_cbo(true, 1);", $db_opcao );
                        ?>
                      </td>
                      <td>
                        <?php
                        $sScript = " onchange='js_pesquisasd04_i_cbo(false, 1);' onFocus=\"nextfield='sd23_d_consulta'\"";
                        db_input( 'sd27_i_codigo',   10, $Isd27_i_codigo,   true, 'hidden', $db_opcao );
                        db_input( 'rh70_sequencial', 10, $Irh70_sequencial, true, 'hidden', $db_opcao );
                        db_input( 'rh70_estrutural', 10, $Irh70_estrutural, true, 'text',   $db_opcao, $sScript );
                        ?>
                      </td>
                      <td colspan="2">
                        <?php
                        db_input( 'rh70_descr', 30, $Irh70_descr, true, 'text', 3 );
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td nowrap title="<?=$Tsd23_d_consulta?>"><?=$Lsd23_d_consulta?></td>
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
                                           " onchange='js_diasem(1)' onFocus=\"nextfield='sd03_i_codigo2'\" ",
                                           "",
                                           "",
                                           "parent.js_diasem(1); ", '', '', '', false, false,
                                           'document.form1.sd02_i_codigo.value',
                                           'document.form1.sd02_i_codigo.value'
                                         );
                        ?>
                      </td>
                      <td>
                        <?php
                        db_input( 'diasemana', 30, $diasemana, true, 'text',   3 );
                        db_input( 'dia',       10, $dia,       true, 'hidden', 3 );
                        ?>
                      </td>
                    </tr>
                  </table>
                </fieldset>              
              </td>
  
              <td valign="top" colspan="2">
                <fieldset>
                  <legend>Para</legend>
                  <table>
                    <!-- PROFISSIONAL -->
                    <tr>
                      <td nowrap title="<?=$Tsd03_i_codigo?>" >
                        <?php
                        db_ancora( $Lsd03_i_codigo, "js_pesquisasd03_i_codigo(true, 2);", $db_opcao );
                        ?>
                      </td>
                      <td valing="top" align="top">
                        <?php
                        $sScript = " onchange='js_pesquisasd03_i_codigo(false, 2);' onFocus=\"nextfield='rh70_estrutural2'\"";
                        db_input( 'sd03_i_codigo2', 10, $Isd03_i_codigo, true, 'text', $db_opcao, $sScript );
                        ?>
                      </td>
                      <td colspan="2">
                        <?php
                        db_input( 'z01_nome2', 30, $Iz01_nome, true, 'text', 3 );
                        ?>
                      </td>
                    </tr>
                    <!-- CBO -->
                    <tr>
                      <td nowrap title="<?=$Tsd04_i_cbo?>">
                        <?php
                        db_ancora( $Lsd04_i_cbo, "js_pesquisasd04_i_cbo(true, 2);", $db_opcao );
                        ?>
                      </td>
                      <td>
                        <?php
                        $sScript = " onchange='js_pesquisasd04_i_cbo(false, 2);' onFocus=\"nextfield='sd23_d_consulta2'\"";
  
                        db_input( 'sd27_i_codigo2',   10, $Isd27_i_codigo,   true, 'hidden', $db_opcao );
                        db_input( 'rh70_sequencial2', 10, $Irh70_sequencial, true, 'hidden', $db_opcao );
                        db_input( 'rh70_estrutural2', 10, $Irh70_estrutural, true, 'text',   $db_opcao, $sScript );
                        ?>
                      </td>
                      <td colspan="2">
                        <?php
                        db_input( 'rh70_descr2', 30, $Irh70_descr, true, 'text', 3 );
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td nowrap title="<?=$Tsd23_d_consulta?>"><?=$Lsd23_d_consulta?></td>
                      <td>
                        <?php
                        $sd23_d_consulta2_dia = !empty($sd23_d_consulta2_dia) ? $sd23_d_consulta2_dia : "";
                        $sd23_d_consulta2_mes = !empty($sd23_d_consulta2_mes) ? $sd23_d_consulta2_mes : "";
                        $sd23_d_consulta2_ano = !empty($sd23_d_consulta2_ano) ? $sd23_d_consulta2_ano : "";
  
                        db_inputdatasaude(
                                           'document.form1.sd27_i_codigo2.value',
                                           'sd23_d_consulta2',
                                           $sd23_d_consulta2_dia,
                                           $sd23_d_consulta2_mes,
                                           $sd23_d_consulta2_ano,
                                           true,
                                           'text',
                                           $db_opcao,
                                           " onchange='js_diasem(2)' onFocus=\"nextfield='done'\" ",
                                           "",
                                           "",
                                           "parent.js_diasem(2); ", '', '', '', false, false,
                                           'document.form1.sd02_i_codigo.value',
                                           'document.form1.sd02_i_codigo.value'
                                         );
                        ?>
                      </td>
                      <td>
                        <?php
                        db_input( 'diasemana2', 30, 'diasemana2', true, 'text',   3 );
                        db_input( 'dia2',       10, 'dia2',       true, 'hidden', 3 );
                        ?>
                      </td>
                    </tr>
                  </table>
                </fieldset>              
              </td>          
            </tr>
            <tr>
              <td colspan="2" align="center"><br>
                <input type="button" name="transferir" value="Confirmar" onclick="js_transferir();"><br>
              </td>
              <td colspan="2" align="center">
                <br>
                <input type="submit" name="limpar" value="Limpar"
                  onclick="location.href='sau4_transfereagenda001.php';">
                <br>
              </td>
            </tr>
            <tr>
              <td height="300px">
                <fieldset style="height: 94%;">
                  <legend>Agendamento De:</legend>
                  <iframe id="frameagendadosde"
                          name="frameagendadosde"
                          src=""
                          width="100%"
                          height="100%"
                          scrolling="yes"
                          frameborder="0"></iframe>
                </fieldset>
              </td>
              <td nowrap align="center">
                <input type="button" onclick="js_moveEsquerda();" value="<">
                <br><br>
                <input type="button" onclick="js_moveDireita();" value=">">
              </td>
              <td>
                <fieldset style="height: 94%;">
                  <legend>Agendamento Para:</legend>
                  <iframe id="frameagendadospara"
                          name="frameagendadospara"
                          src=""
                          width="100%"
                          height="100%"
                          scrolling="yes"
                          frameborder="0"></iframe>
                </fieldset>
              </td>
              <td nowrap align="center">
                <input type="button" onclick="js_moveCima();" value="^">
                <br><br>
                <input type="button" onclick="js_moveBaixo();" value="V">
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
  </table>
</form>

<script type="text/javascript">

<?php
  if (!isset($lBotao) || $lBotao != 'true') {
    echo 'js_limpar();';
  } else {

    echo "$('sd23_d_consulta').value = '$sd23_d_consulta';";
    echo 'js_agendados(1);';
    echo "sAgendamentosMarcar = '$sAgendamentos';";
  }
?>
  
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

function js_limpar() {

  $('sd03_i_codigo').value    = '';
  $('rh70_estrutural').value  = '';
  $('sd23_d_consulta').value  = '';
  $('z01_nome').value         = '';
  $('rh70_descr').value       = '';
  $('diasemana').value        = '';
  $('sd03_i_codigo2').value   = '';
  $('rh70_estrutural2').value = '';
  $('sd23_d_consulta2').value = '';
  $('z01_nome2').value        = '';
  $('rh70_descr2').value      = '';
  $('diasemana2').value       = '';
  $('frameagendadosde').src   = '';
  $('frameagendadospara').src = '';
}

function js_transferir() {

  if ($F('sd23_d_consulta2') == '') {

    alert('Selecione uma data para transferir os agendamentos');
    return false;
  }

  var oLadoPara   = document.getElementById('frameagendadospara').contentDocument; // Docuemnto do iframe para
  var oCkLadoPara = oLadoPara.getElementsByName('ckbox'); // checkboxs que represetam os agendamentos do lado para
  var iTam        = null;

  /* aDados[0] -> codigo do agendamento
     aDados[1] -> codigo da undmedhorarios (codigo da grade de horarios)
     aDados[2] -> id da linha */
  var aDadosAntes         = new Array();
  var aDadosNovos         = new Array();
  var oParam              = new Object();
  var aDadosTransferencia = new Array();

  if (oCkLadoPara.length == 0) {

    alert('Nenhum agendamento para transferir.');
    return false;
  }

  for (var iCont = 0; iCont < oCkLadoPara.length; iCont++) {
    
    aDadosAntes = oLadoPara.getElementById('transf_'+iCont).value.split(' ## ');
    if (aDadosAntes.length == 3) { // deve ser transferido, pois os dados antigos estão preenchidos

      aDadosNovos                                = oLadoPara.getElementById('ckbox_'+iCont).value.split(' ## ');
      iTam                                       = aDadosTransferencia.length;
      aDadosTransferencia[iTam]                  = new Object();
      aDadosTransferencia[iTam].sd23_i_codigo    = aDadosAntes[0];
      aDadosTransferencia[iTam].sd23_d_consulta  = $F('sd23_d_consulta2_ano')+'-'+$F('sd23_d_consulta2_mes')+'-'+
                                                   $F('sd23_d_consulta2_dia');
      aDadosTransferencia[iTam].sd23_i_ficha     = oLadoPara.getElementById('td_'+iCont+'1').innerHTML.trim();
      aDadosTransferencia[iTam].sd23_c_hora      = oLadoPara.getElementById('td_'+iCont+'2').innerHTML.trim();
      aDadosTransferencia[iTam].sd23_i_undmedhor = aDadosNovos[1];
    }
  }

  if (aDadosTransferencia.length == 0) {

    alert('Nenhum agendamento para transferir.');
    return false;
  }

  oParam.exec                = 'transferirAgendamentos';
  oParam.aDadosTransferencia = aDadosTransferencia;

  js_ajax(oParam, 'js_retornoTransferir');
}

function js_retornoTransferir(oRetorno) {
  
  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus == 1) {

    alert('Transferência realizada com sucesso.');
    js_diasem(1);
    js_diasem(2);
    
    <?php
    if (isset($lBotao)) {
      
      if ($lBotao == 'true') {
        echo 'parent.js_agendados();';
      }
    }
    ?>
    return true;

  } else {

    alert(oRetorno.sMessage.urlDecode().replace(/\\n/g, "\n"));
    return false;
  }
}

function js_moveEsquerda() {

  var oLadoDe     = document.getElementById('frameagendadosde').contentDocument; // Document do iframe de
  var oLadoPara   = document.getElementById('frameagendadospara').contentDocument; // Docuemnto do iframe para
  var oCkLadoPara = oLadoPara.getElementsByName('ckbox'); // checkboxs que represetam os agendamentos do lado para
  var lHorarioSel = false;

  for (var iCont = 0; iCont < oCkLadoPara.length; iCont++) {

    if (!oCkLadoPara[iCont].disabled && oCkLadoPara[iCont].checked) { // foi marcado para mover

      lHorarioSel = true;

      /* aDados[0] -> codigo do agendamento
         aDados[1] -> codigo da undmedhorarios (codigo da grade de horarios)
         aDados[2] -> id da linha */

      // Dados que o agendamento iria possuir
      var aDadosAtual = oCkLadoPara[iCont].value.split(' ## '); 

      // Dados que o agendamento já possuía (no lado de)
      var aDadosOrigem = oLadoPara.getElementById('transf_'+aDadosAtual[2]).value.split(' ## ');

      /* Copio o CGS e nome de quem o agendamento está sendo transferido */
      var iCgs  = oLadoPara.getElementById('td'+'_'+aDadosAtual[2]+'6').innerHTML;
      var sNome = oLadoPara.getElementById('td'+'_'+aDadosAtual[2]+'7').innerHTML;

      /* Apago o CGS e o nome do agendamento sendo transferido */
      oLadoPara.getElementById('td'+'_'+aDadosAtual[2]+'6').innerHTML = '';
      oLadoPara.getElementById('td'+'_'+aDadosAtual[2]+'7').innerHTML = '---------';

      /* Coloco o CGS e nome do agendamento sendo transferido no lugar para onde quero transferir */
      oLadoDe.getElementById('td'+'_'+aDadosOrigem[2]+'6').innerHTML = iCgs;
      oLadoDe.getElementById('td'+'_'+aDadosOrigem[2]+'7').innerHTML = sNome;

      /* Atualizo o campo hidden, colocando true, pois agora o horário está livre novamente */
      oLadoPara.getElementById('livre_'+aDadosAtual[2]).value = 'true ## '+aDadosAtual[2];
      
      /* Desaabilito o checkbox do agendamento transferido e o deseleciono, pois agora o lugar está vazio */
      oLadoPara.getElementById('ckbox_'+aDadosAtual[2]).checked  = false;
      oLadoPara.getElementById('ckbox_'+aDadosAtual[2]).disabled = true;

      /* Habilito o checkbox do novo lugar do agendamento transferido e o seleciono */
      oLadoDe.getElementById('ckbox_'+aDadosOrigem[2]).checked  = true;
      oLadoDe.getElementById('ckbox_'+aDadosOrigem[2]).disabled = false;

      /* retiro os dados do campo hidden dos agendamentos transferidos, 
         pois o agendamento voltou para seu lugar de origem */
      oLadoPara.getElementById('transf_'+aDadosAtual[2]).value = '';
    } // fim if agendamento do lado para foi marcado para ser transferido
  } // fim for procura agendamentos para mover no lado para

  if (!lHorarioSel) {
    alert('Nenhum horário selecionado para transferir.');
  }
}

function js_moveDireita() {

  var oLadoDe     = document.getElementById('frameagendadosde').contentDocument; // Document do iframe de
  var oLadoPara   = document.getElementById('frameagendadospara').contentDocument; // Document do iframe para
  var oCkLadoDe   = oLadoDe.getElementsByName('ckbox'); // Checkboxs que represetam os agendamentos do lado de
  var oCkLadoPara = oLadoPara.getElementsByName('ckboxPara'); // Checkboxs que represetam as grades de horário
  var lHorarioSel = false;

  if (oCkLadoDe.length == 0 || oCkLadoPara.length == 0) {
    return false;
  }

  for (var iCont = 0; iCont < oCkLadoDe.length; iCont++) {

    if (!oCkLadoDe[iCont].disabled && oCkLadoDe[iCont].checked) { // foi marcado para mover

      lBreak      = false;
      lMarcada    = false;
      lHorarioSel = true;

      for (var iCont2 = 0; iCont2 < oCkLadoPara.length; iCont2++) { // verif se alguma grade foi marc p/ recebr o agend
       
        if (oCkLadoPara[iCont2].checked) {

          lMarcada = true;

          var oLivres = oLadoPara.getElementsByName('lLivre'+oCkLadoPara[iCont2].value);
          var aLivres = new Array();

          for (var iCont3 = 0; iCont3 < oLivres.length; iCont3++) { // verifico se tem algum horario livre para a grade
            
            /* aLivres[0] -> 'true' ou 'false', indicando se está ou não livre
               aLivres[1] -> id da linha */
            aLivres = oLivres[iCont3].value.split(' ## ');

            if (aLivres[0] == 'true') {

              /* aDados[0] -> codigo do agendamento
                 aDados[1] -> codigo da undmedhorarios (codigo da grade de horarios)
                 aDados[2] -> id da linha */
              var aDados = oCkLadoDe[iCont].value.split(' ## ');

              /* Copio o CGS e nome de quem o agendamento está sendo transferido */
              var iCgs   = oLadoDe.getElementById('td'+'_'+aDados[2]+'6').innerHTML;
              var sNome  = oLadoDe.getElementById('td'+'_'+aDados[2]+'7').innerHTML;

              /* Apago o CGS e o nome do agendamento sendo transferido */
              oLadoDe.getElementById('td'+'_'+aDados[2]+'6').innerHTML = '';
              oLadoDe.getElementById('td'+'_'+aDados[2]+'7').innerHTML = '---------';

              /* Coloco o CGS e nome do agendamento sendo transferido no lugar para onde quero transferir */
              oLadoPara.getElementById('td'+'_'+aLivres[1]+'6').innerHTML = iCgs;
              oLadoPara.getElementById('td'+'_'+aLivres[1]+'7').innerHTML = sNome;

              /* Atualizo o campo hidden, colocando false, pois agora o horário não está mais livre */
              oLivres[iCont3].value = 'false ## '+aLivres[1];
             
             /* Desaabilito o checkbox do agendamento transferido e o deseleciono, pois agora o lugar está vazio */
              oCkLadoDe[iCont].checked  = false;
              oCkLadoDe[iCont].disabled = true;

             /* Habilito o checkbox do novo lugar do agendamento transferido e o seleciono */
              oLadoPara.getElementById('ckbox_'+aLivres[1]).disabled = false;
              oLadoPara.getElementById('ckbox_'+aLivres[1]).checked  = true;

              /* Coloco os dados do agendamento transferido no campo hidden dos agendamentos transferidos */
              oLadoPara.getElementById('transf_'+aLivres[1]).value = aDados[0]+' ## '+aDados[1]+' ## '+aDados[2];

              lBreak = true;
              break;
            }
          }

          if (lBreak) {
            break;
          } else {

            alert('A grade não possui mais horários vagos.');
            return false;
          }
        } // fim if grade foi marcada para receber os agendamentos sendo transferidos
      } // fim for que verifica se alguma grade for marcada para receber os agendamentos a serem transferidos

      if (!lMarcada) {

        alert('Selecione uma grade para receber os agendamentos a serem transferidos.');
        break;
      }
    } // fim if foi marcado para ser transferido
  } // fim for que verifica os agendamentos marcados para serem transferidos

  if (!lHorarioSel) {
    alert('Nenhum horário selecionado para transferir.');
  }
}

function js_moveBaixo() {

  var oLadoPara   = document.getElementById('frameagendadospara').contentDocument; // Docuemnto do iframe para
  var oCkLadoPara = oLadoPara.getElementsByName('ckbox'); // checkboxs que represetam os agendamentos do lado para
  var lHorarioSel = false;

  for (var iCont = 0; iCont < oCkLadoPara.length; iCont++) {

    if (!oCkLadoPara[iCont].disabled && oCkLadoPara[iCont].checked) { // foi marcado para movera

      lHorarioSel = true;

      /* aDados[0] -> codigo do agendamento
         aDados[1] -> codigo da undmedhorarios (codigo da grade de horarios)
         aDados[2] -> id da linha */
      var aDados     = oCkLadoPara[iCont].value.split(' ## ');
      var sTipoGrade = oLadoPara.getElementById('td_'+iCont+'5').innerHTML;
      var aDadosTmp  = new Array();
      var aLivres    = new Array();

      for (var iCont2 = iCont + 1; iCont2 < oCkLadoPara.length; iCont2++) { // verifico horário livre para o agend

        /* aLivres[0] -> 'true' ou 'false', indicando se está ou não livre
           aLivres[1] -> id da linha */
        aLivres = oLadoPara.getElementById('livre_'+iCont2).value.split(' ## ');

        if (aLivres[0] == 'true') {

          // Quando a grade for período, só movo para outra grade, pois não faz diferença mover para a mesma
          if (sTipoGrade == 'Período') {
          
            aDadosTmp = oLadoPara.getElementById('ckbox_'+iCont2).value.split(' ## ');
            if (aDadosTmp[1] == aDados[1]) { // ainda é a mesma grade
              continue;
            }
          }

          // Dados que o agendamento já possuía (no lado de) para o novo horário e apago do antigo
          oLadoPara.getElementById('transf_'+iCont2).value    = oLadoPara.getElementById('transf_'+aDados[2]).value;
          oLadoPara.getElementById('transf_'+aDados[2]).value = '';
          
          /* Copio o CGS e nome de quem o agendamento está sendo transferido */
          var iCgs  = oLadoPara.getElementById('td'+'_'+aDados[2]+'6').innerHTML;
          var sNome = oLadoPara.getElementById('td'+'_'+aDados[2]+'7').innerHTML;
          
          /* Apago o CGS e o nome do agendamento sendo transferido */
          oLadoPara.getElementById('td'+'_'+aDados[2]+'6').innerHTML = '';
          oLadoPara.getElementById('td'+'_'+aDados[2]+'7').innerHTML = '---------';
          
          /* Coloco o CGS e nome do agendamento sendo transferido no lugar para onde quero transferir */
          oLadoPara.getElementById('td'+'_'+iCont2+'6').innerHTML = iCgs;
          oLadoPara.getElementById('td'+'_'+iCont2+'7').innerHTML = sNome;
          
          /* Atualizo o campo hidden, colocando false, pois agora o horário não está livre */
          oLadoPara.getElementById('livre_'+iCont2).value = 'false ## '+iCont2;

          /* Atualizo o campo hidden do horario onde o agendamento estava antes, pois agora o horário está livre */
          oLadoPara.getElementById('livre_'+aDados[2]).value = 'true ## '+aDados[2];
          
          /* Desabilito o checkbox do agendamento transferido e o deseleciono, pois agora o lugar está vazio */
          oLadoPara.getElementById('ckbox_'+aDados[2]).checked  = false;
          oLadoPara.getElementById('ckbox_'+aDados[2]).disabled = true;
          
          /* Habilito o checkbox do novo lugar do agendamento transferido e o seleciono */
          oLadoPara.getElementById('ckbox_'+iCont2).checked  = true;
          oLadoPara.getElementById('ckbox_'+iCont2).disabled = false;

          return true;
        } // fim if horario livre
      } // fim do for que verifica se existe algum horario livre
    } // fim if agendamento foi marcado para ser movido
  } // fim for procura agendamentos para mover

  if (!lHorarioSel) {
    alert('Nenhum horário selecionado para transferir.');
  } else {
    alert('O horário não pode ser movido.');
  }
}

function js_moveCima() {

  var oLadoPara   = document.getElementById('frameagendadospara').contentDocument; // Docuemnto do iframe para
  var oCkLadoPara = oLadoPara.getElementsByName('ckbox'); // checkboxs que represetam os agendamentos do lado para
  var lHorarioSel = false;

  for (var iCont = 0; iCont < oCkLadoPara.length; iCont++) {

    if (!oCkLadoPara[iCont].disabled && oCkLadoPara[iCont].checked) { // foi marcado para mover

      lHorarioSel = true;

      /* aDados[0] -> codigo do agendamento
         aDados[1] -> codigo da undmedhorarios (codigo da grade de horarios)
         aDados[2] -> id da linha */
      var aDados     = oCkLadoPara[iCont].value.split(' ## ');
      var sTipoGrade = oLadoPara.getElementById('td_'+iCont+'5').innerHTML;
      var aDadosTmp  = new Array();
      var aLivres    = new Array();

      for (var iCont2 = iCont - 1; iCont2 > -1; iCont2--) { // verifico horário livre para o agend

        /* aLivres[0] -> 'true' ou 'false', indicando se está ou não livre
           aLivres[1] -> id da linha */
        aLivres = oLadoPara.getElementById('livre_'+iCont2).value.split(' ## ');

        if (aLivres[0] == 'true') {

          // Quando a grade for período, só movo para outra grade, pois não faz diferença mover para a mesma
          if (sTipoGrade == 'Período') {
          
            aDadosTmp = oLadoPara.getElementById('ckbox_'+iCont2).value.split(' ## ');
            if (aDadosTmp[1] == aDados[1]) { // ainda é a mesma grade
              continue;
            }
          }

          // Dados que o agendamento já possuía (no lado de) para o novo horário e apago do antigo
          oLadoPara.getElementById('transf_'+iCont2).value    = oLadoPara.getElementById('transf_'+aDados[2]).value;
          oLadoPara.getElementById('transf_'+aDados[2]).value = '';
          
          /* Copio o CGS e nome de quem o agendamento está sendo transferido */
          var iCgs  = oLadoPara.getElementById('td'+'_'+aDados[2]+'6').innerHTML;
          var sNome = oLadoPara.getElementById('td'+'_'+aDados[2]+'7').innerHTML;
          
          /* Apago o CGS e o nome do agendamento sendo transferido */
          oLadoPara.getElementById('td'+'_'+aDados[2]+'6').innerHTML = '';
          oLadoPara.getElementById('td'+'_'+aDados[2]+'7').innerHTML = '---------';
          
          /* Coloco o CGS e nome do agendamento sendo transferido no lugar para onde quero transferir */
          oLadoPara.getElementById('td'+'_'+iCont2+'6').innerHTML = iCgs;
          oLadoPara.getElementById('td'+'_'+iCont2+'7').innerHTML = sNome;
          
          /* Atualizo o campo hidden, colocando false, pois agora o horário não está livre */
          oLadoPara.getElementById('livre_'+iCont2).value = 'false ## '+iCont2;

          /* Atualizo o campo hidden do horario onde o agendamento estava antes, pois agora o horário está livre */
          oLadoPara.getElementById('livre_'+aDados[2]).value = 'true ## '+aDados[2];
          
          /* Desabilito o checkbox do agendamento transferido e o deseleciono, pois agora o lugar está vazio */
          oLadoPara.getElementById('ckbox_'+aDados[2]).checked  = false;
          oLadoPara.getElementById('ckbox_'+aDados[2]).disabled = true;
          
          /* Habilito o checkbox do novo lugar do agendamento transferido e o seleciono */
          oLadoPara.getElementById('ckbox_'+iCont2).checked  = true;
          oLadoPara.getElementById('ckbox_'+iCont2).disabled = false;

          return true;
        } // fim if horario livre
      } // fim do for que verifica se existe algum horario livre
    } // fim if agendamento foi marcado para ser movido
  } // fim for procura agendamentos para mover

  if (!lHorarioSel) {
    alert('Nenhum horário selecionado para transferir.');
  } else {
    alert('O horário não pode ser movido.');
  }
}

function js_marcarAgendamentosSelecionados() {

  var oLadoDe       = document.getElementById('frameagendadosde').contentDocument; // Document do iframe de
  var oCkLadoDe     = oLadoDe.getElementsByName('ckbox'); // checkboxs que represetam os agendamentos do lado de
  var aAgendamentos = sAgendamentosMarcar.split(',');

  if (oCkLadoDe.length == 0 || aAgendamentos.length == 0) {
    return false;
  }

  for (var iCont = 0; iCont < aAgendamentos.length; iCont++) {

    if (!oLadoDe.getElementById('ckbox_'+aAgendamentos[iCont]).disabled) {
      oLadoDe.getElementById('ckbox_'+aAgendamentos[iCont]).checked = true;
    }
  }
}

function js_agendados(depara) {

  if (depara == 1) {

    sd23_d_consulta = $F('sd23_d_consulta');
    sd27_i_codigo   = $F('sd27_i_codigo');
    iframe          = document.getElementById('frameagendadosde');
  } else {

    sd23_d_consulta = $F('sd23_d_consulta2');
    sd27_i_codigo  = $F('sd27_i_codigo2');
    iframe         = document.getElementById('frameagendadospara');
  }
  
  if (sd23_d_consulta != '') {

    iAno        = sd23_d_consulta.substr(6, 4);
    iMes        = parseInt(sd23_d_consulta.substr(3, 2), 10) - 1;
    iDia        = sd23_d_consulta.substr(0, 2);
    dData       = new Date(iAno, iMes, iDia);
    iDiaSemana  = dData.getDay() + 1;

    var sUrl  = 'sau4_agendamento002.php';
    sUrl     += '?sd27_i_codigo='+sd27_i_codigo;
    sUrl     += '&chave_diasemana='+iDiaSemana;
    sUrl     += '&sd23_d_consulta='+sd23_d_consulta;
    sUrl     += '&sTransf=true';
    sUrl     += '&sLado='+(depara == 1 ? 'de' : 'para');
    <?php
    if (isset($lBotao)) {
      echo "sUrl += '&lMarcarAgendamentos=true'";
    }
    ?>

    iframe.src = sUrl;
  }
}

function js_diasem(depara) {

  if (depara == 1) {
    
    if ($F('sd23_d_consulta') == '') {

      js_limpaDataConsulta(1);
      return false;
    }

    iAno = $F('sd23_d_consulta_ano');
    iMes = parseInt($F('sd23_d_consulta_mes'), 10) - 1;
    iDia = $F('sd23_d_consulta_dia');
  } else {

    if ($F('sd23_d_consulta2') == '') {

      js_limpaDataConsulta(2);
      return false;
    }

    iAno = $F('sd23_d_consulta2_ano');
    iMes = parseInt($F('sd23_d_consulta2_mes'), 10) - 1;
    iDia = $F('sd23_d_consulta2_dia');
  }

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

  if (depara == 1) {

    document.form1.diasemana.value = sNomeDia[iDiaSemana];
    document.form1.dia.value       = (iDiaSemana + 1);
  } else {

    document.form1.diasemana2.value = sNomeDia[iDiaSemana];
    document.form1.dia2.value       = (iDiaSemana + 1);
  }
  
  js_agendados(depara);
}

function js_calend() {

  var sUrl    = 'func_calendariosaude.php';
  sUrl       += '?nome_objeto_data=sd23_d_consulta';
  sUrl       += '&sd27_i_codigo='+$F('sd27_i_codigo');
  sUrl       += '&shutdown_function=parent.js_agendados()';
  
  iframe = $('framecalendario');
  iframe.src = x;
}

function js_limpaFrames(iLado) {

  if (iLado == 1) {
    $('frameagendadosde').src   = '';
  } else {
    $('frameagendadospara').src = '';
  }
}

function js_limpaDataConsulta(iLado) {

  if (iLado == 1) {

    $('diasemana').value        = '';
    $('sd23_d_consulta').value  = '';
  } else {

    $('diasemana2').value       = '';
    $('sd23_d_consulta2').value = '';
  }

  js_limpaFrames(iLado);
}

function js_limpaEspecialidade(iLado) {

  if (iLado == 1) {

    $('rh70_descr').value      = '';
    $('rh70_sequencial').value = '';
    $('sd27_i_codigo').value   = '';
    $('sd23_d_consulta').value = '';
    js_limpaDataConsulta(iLado);
  } else {

    $('rh70_descr2').value      = '';
    $('rh70_sequencial2').value = '';
    $('sd27_i_codigo2').value   = '';
    $('sd23_d_consulta2').value = '';
    js_limpaDataConsulta(iLado);
  }
}

function js_limpaProfissional(iLado) {

  if (iLado == 1) {

    $('z01_nome').value      = '';
    js_limpaEspecialidade(iLado);
  } else {

    $('z01_nome2').value      = '';
    js_limpaEspecialidade(iLado);
  }
}

function js_pesquisasd04_i_cbo(mostra, depara) {

  if (mostra == true) {

    if (depara == 2) {

      js_OpenJanelaIframe('', 'db_iframe_especmedico', 'func_especmedico.php?'+
                          'funcao_js=parent.js_mostrarhcbo2|sd27_i_codigo|rh70_estrutural|'+
                          'rh70_descr|sd27_i_rhcbo&chave_sd04_i_unidade='+
                          document.form1.sd02_i_codigo.value+'&chave_sd04_i_medico='+
                          document.form1.sd03_i_codigo2.value, 'Pesquisa', true
                         );
    } else {

      js_OpenJanelaIframe('', 'db_iframe_especmedico', 'func_especmedico.php?'+
                          'funcao_js=parent.js_mostrarhcbo1|sd27_i_codigo|rh70_estrutural|'+
                          'rh70_descr|sd27_i_rhcbo&chave_sd04_i_unidade='+
                          document.form1.sd02_i_codigo.value+'&chave_sd04_i_medico='+
                          document.form1.sd03_i_codigo.value, 'Pesquisa', true
                         );
    }
  } else {

    if (depara == 2) {

      if (document.form1.rh70_estrutural2.value != '') { 

        js_OpenJanelaIframe('', 'db_iframe_especmedico', 'func_especmedico.php?chave_rh70_estrutural='+
                            document.form1.rh70_estrutural2.value+'&funcao_js=parent.js_mostrarhcbo2|'+
                            'sd27_i_codigo|rh70_estrutural|rh70_descr|sd27_i_rhcbo&chave_sd04_i_unidade='+
                            document.form1.sd02_i_codigo.value+'&chave_sd04_i_medico='+
                            document.form1.sd03_i_codigo2.value, 'Pesquisa', false
                           );
        document.form1.rh70_estrutural2.value = '';
        document.form1.rh70_descr2.value      = '';
      } else {
        js_limpaEspecialidade(2);
      }
    } else {

      if (document.form1.rh70_estrutural.value != '') {

        js_OpenJanelaIframe('', 'db_iframe_especmedico', 'func_especmedico.php?chave_rh70_estrutural='+
                            document.form1.rh70_estrutural.value+'&funcao_js=parent.js_mostrarhcbo1|'+
                            'sd27_i_codigo|rh70_estrutural|rh70_descr|sd27_i_rhcbo&chave_sd04_i_unidade='+
                            document.form1.sd02_i_codigo.value+'&chave_sd04_i_medico='+
                            document.form1.sd03_i_codigo.value, 'Pesquisa', false
                           );
        document.form1.rh70_estrutural.value = '';
        document.form1.rh70_descr.value      = '';
      } else {
        js_limpaEspecialidade(2);
      }
    }
  }
}

function js_mostrarhcbo1(chave1, chave2, chave3, chave4) {

  js_limpaEspecialidade(1);
  document.form1.sd27_i_codigo.value   = chave1;
  document.form1.rh70_estrutural.value = chave2;
  document.form1.rh70_descr.value      = chave3;
  document.form1.rh70_sequencial.value = chave4;

  db_iframe_especmedico.hide();

  if (chave2 == '') {

    document.form1.rh70_estrutural.focus(); 
    document.form1.rh70_estrutural.value = ''; 
  }
}

function js_mostrarhcbo2(chave1, chave2, chave3, chave4) {

  js_limpaEspecialidade(2);
  document.form1.sd27_i_codigo2.value   = chave1;
  document.form1.rh70_estrutural2.value = chave2;
  document.form1.rh70_descr2.value      = chave3;
  document.form1.rh70_sequencial2.value = chave4;
  
  db_iframe_especmedico.hide();
  
  if ((chave2 == '') || (document.form1.rh70_sequencial2.value != document.form1.rh70_sequencial.value) ) {

    if (document.form1.rh70_sequencial2.value != document.form1.rh70_sequencial.value) {
      alert('CBO do profissional de destino difere do profissional de origem.');
    }

    document.form1.rh70_estrutural2.focus();
    document.form1.sd27_i_codigo2.value   = '';
    document.form1.rh70_estrutural2.value = '';
    document.form1.rh70_descr2.value      = '';
    document.form1.rh70_sequencial2.value = '';
  }
}

function js_pesquisasd03_i_codigo(mostra, depara) {

  if (mostra == true) {

    if (depara == 2) {

      js_OpenJanelaIframe('', 'db_iframe_medicos', 'func_cboups.php?chave_sd04_i_medico=0'+
                          '&funcao_js=parent.js_mostramedicos2|sd03_i_codigo|z01_nome&chave_sd04_i_unidade='+
                          document.form1.sd02_i_codigo.value+'&chave_rh70_estrutural='+
                          document.form1.rh70_estrutural.value, 'Pesquisa', true
                         );
    } else {

      js_OpenJanelaIframe('', 'db_iframe_medicos', 'func_medicos.php?funcao_js=parent.js_mostramedicos1|'+
                          'sd03_i_codigo|z01_nome&chave_sd06_i_unidade='+document.form1.sd02_i_codigo.value, 
                          'Pesquisa', true
                         );
    }
  } else {

    if (document.form1.sd03_i_codigo.value != '') {

      if (depara == 2) {

        js_OpenJanelaIframe('', 'db_iframe_medicos', 'func_cboups.php?chave_sd04_i_medico='+
                            document.form1.sd03_i_codigo.value+'&funcao_js=parent.js_mostramedicos2|'+
                            'sd03_i_codigo|z01_nome&chave_sd04_i_unidade='+
                            document.form1.sd02_i_codigo.value+'&chave_rh70_estrutural='+
                            document.form1.rh70_estrutural.value, 'Pesquisa', false
                           );
      } else {

        js_OpenJanelaIframe('', 'db_iframe_medicos', 'func_medicos.php?pesquisa_chave='+
                            document.form1.sd03_i_codigo.value+
                            '&funcao_js=parent.js_mostramedicos_1&chave_sd06_i_unidade='+
                            document.form1.sd02_i_codigo.value, 'Pesquisa', false
                           );
      }
    } else {
      document.form1.z01_nome.value = '';
    }
  }
}

function js_mostramedicos_1(chave, erro) {

  js_limpaProfissional(1);
  document.form1.z01_nome.value = chave;
  if (erro == true) {

    document.form1.sd03_i_codigo.focus();
    document.form1.sd03_i_codigo.value   = '';
    document.form1.sd27_i_codigo.value   = '';
    document.form1.rh70_estrutural.value = '';
    document.form1.rh70_descr.value      = '';
  } else {
    js_pesquisasd04_i_cbo(true, 1);    
  }
}

function js_mostramedicos_2(chave, erro) {

  js_limpaProfissional(2);
  document.form1.z01_nome2.value = chave;
  if (erro == true) {

    document.form1.sd03_i_codigo2.focus();
    document.form1.sd03_i_codigo2.value   = '';
    document.form1.sd27_i_codigo2.value   = '';
    document.form1.rh70_estrutural2.value = '';
    document.form1.rh70_descr2.value      = '';
  } else {
    js_pesquisasd04_i_cbo(true, 2);    
  }
}

function js_mostramedicos1(chave1, chave2) {

  js_limpaProfissional(1);
  document.form1.sd03_i_codigo.value = chave1;
  document.form1.z01_nome.value      = chave2;
  db_iframe_medicos.hide();
  js_pesquisasd04_i_cbo(true, 1);
}

function js_mostramedicos2(chave1, chave2) {

  js_limpaProfissional(2);
  document.form1.sd03_i_codigo2.value = chave1;
  document.form1.z01_nome2.value      = chave2;
  db_iframe_medicos.hide();
  js_pesquisasd04_i_cbo(true, 2);
}
</script>