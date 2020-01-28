<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: laboratorio
$oDaoLabControleFisicoFinanceiro->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label('la02_i_codigo');
$oRotulo->label('la02_c_descr');
$oRotulo->label('descrdepto');
$oRotulo->label('la08_c_descr');
$oRotulo->label('sd60_c_grupo');
$oRotulo->label('sd60_c_nome');
$oRotulo->label('sd61_c_subgrupo');
$oRotulo->label('sd61_c_nome');
$oRotulo->label('sd62_c_formaorganizacao');
$oRotulo->label('sd62_c_nome');
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <?
  if ($iTipoControle == 3 || $iTipoControle == 6 || $iTipoControle == 7) {
  ?>
    <tr>
      <td nowrap title="<?=@$Tla56_i_grupo?>">
        <?
        db_ancora(@$Lla56_i_grupo, "js_pesquisala56_i_grupo(true);", $db_opcao);
        ?>
      </td>
      <td> 
        <?
        db_input('la56_i_grupo', 10, $Ila56_i_grupo, true, 'hidden', 3, '');
        db_input('sd60_c_grupo', 2, $Isd60_c_grupo, true, 'text', $db_opcao, 
                 " onchange='js_pesquisala56_i_grupo(false);'"
                );
        db_input('sd60_c_nome', 50, $Isd60_c_nome, true, 'text', 3, '');
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tla56_i_subgrupo?>">
        <?
        db_ancora(@$Lla56_i_subgrupo, "js_pesquisala56_i_subgrupo(true);", $db_opcao);
        ?>
      </td>
      <td> 
        <?
        db_input('la56_i_subgrupo', 10, $Ila56_i_subgrupo, true, 'hidden', 3, '');
        db_input('sd61_c_subgrupo', 2, $Isd61_c_subgrupo, true, 'text', $db_opcao, 
                 " onchange='js_pesquisala56_i_subgrupo(false);'"
                );
        db_input('sd61_c_nome', 50, $Isd61_c_nome, true, 'text', 3, '');
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tla56_i_formaorganizacao?>">
        <?
        db_ancora(@$Lla56_i_formaorganizacao, "js_pesquisala56_i_formaorganizacao(true);", $db_opcao);
        ?>
      </td>
      <td> 
        <?
        db_input('la56_i_formaorganizacao', 10, $Ila56_i_formaorganizacao, true, 'hidden', 3, '');
        db_input('sd62_c_formaorganizacao', 2, $Isd62_c_formaorganizacao, true, 'text', $db_opcao, 
                 " onchange='js_pesquisala56_i_formaorganizacao(false);'"
                );
        db_input('sd62_c_nome', 50, $Isd62_c_nome, true, 'text', 3, '');
        ?>
      </td>
    </tr>
  <?
  } elseif ($iTipoControle == 2 || $iTipoControle == 5 || $iTipoControle == 8) {
  ?>
    <tr>
      <td nowrap title="<?=@$Tla56_i_exame?>">
        <?
        db_ancora(@$Lla56_i_exame, "js_pesquisala56_i_exame(true);", $db_opcao);
        ?>
      </td>
      <td> 
        <?
        db_input('la56_i_exame', 10, $Ila56_i_exame, true, 'text', $db_opcao, 
                 " onchange='js_pesquisala56_i_exame(false);'"
                );
        db_input('la08_c_descr', 50, $Ila08_c_descr, true, 'text', 3, '');
        ?>
      </td>
    </tr>
  <?
  } elseif ($iTipoControle == 9) {
  ?>
    <tr>
      <td nowrap title="<?=@$Tla56_i_laboratorio?>">
        <?
        db_ancora(@$Lla56_i_laboratorio, "js_pesquisala56_i_laboratorio(true);", $db_opcao);
        ?>
      </td>
      <td> 
        <?
        db_input('la56_i_laboratorio', 10, $Ila56_i_laboratorio, true, 'text', $db_opcao, 
                 " onchange='js_pesquisala56_i_laboratorio(false);'"
                );
        db_input('la02_c_descr', 50, $Ila02_c_descr, true, 'text', 3, '');
        ?>
      </td>
    </tr>
  <?
  }
  ?>
  <tr>
    <td nowrap title="<?=@$Tla56_i_teto?>" colspan="2">
      <?
      echo $Lla56_i_teto;
      $aX = array('1' => 'FÍSICO', '2' => 'FINANCEIRO');
      db_select('la56_i_teto', $aX, true, $db_opcao, '');
      echo $Lla56_n_limite;
      db_input('la56_n_limite', 10, $Ila56_n_limite, true, 'text', $db_opcao2, 'onchange="js_limiteminimo(this)"');
      echo $Lla56_i_periodo;
      $aX = array('1' => 'DIÁRIO', '2' => 'MENSAL');
      db_select('la56_i_periodo', $aX, true, $db_opcao, '');
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <table>
        <tr>
          <td>
            <fieldset style='width: 20%;'> <legend><b>Validade:</b></legend>
              <table border="0">
                <tr>
                  <td nowrap title="<?=@$Tla56_d_ini?>">
                    <?
                    echo $Lla56_d_ini;
                    db_inputdata('la56_d_ini', @$la56_d_ini_dia, @$la56_d_ini_mes, @$la56_d_ini_ano, 
                                 true, 'text', $db_opcao, ""
                                );
                    echo $Lla56_d_fim;
                    db_inputdata('la56_d_fim', @$la56_d_fim_dia, @$la56_d_fim_mes, @$la56_d_fim_ano, 
                                 true, 'text', $db_opcao2, ""
                                );
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
          <td>
            <fieldset style='width: 20%;'> <legend><b>Quando o saldo for insuficiente:</b></legend>
              <table border="0">
                <tr>
                  <td nowrap title="<?=@$Tla56_i_liberarequisicaosemsaldo?>">
                    <?
                    $aX = array(2 => 'NÃO LIBERAR REQUISIÇÃO', 1 => 'LIBERAR REQUISIÇÃO');
                    db_select('la56_i_liberarequisicaosemsaldo', $aX, true, $db_opcao2, '');
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <?
      $sParamsConfirmar = $iOperacao == 1 ? "'incluir'" : 
                         ($iOperacao == 2 ? "'alterar', $la56_i_codigo" : "'excluir', $la56_i_codigo");
      $sLabelButton     = $iOperacao == 1 ? 'Incluir' : 
                         ($iOperacao == 2 ? 'Alterar' : 'Excluir');

      ?>
      <input name="confirmar" type="button" id="confirmar" value="<?=$sLabelButton?>" 
        onclick="js_confirmar(<?=$sParamsConfirmar?>);">
      <input name="limpar" type="button" id="limpar" value="Limpar" onclick="js_limpar();">
      <?
      if ($iOperacao == 2 || $iOperacao == 3) {
      ?>
        <input name="cancelar" type="button" id="cancelar" value="Cancelar" onclick="js_cancelar();">
      <?
      } 
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <div id='grid_controle' style='width: 800px;'></div>
    </td>
  </tr>
</table>
</center>
</form>

<script>
/* GLOBALS */
iTipoControle   = <?=$iTipoControle?>;
oDBGridControle = js_criaDataGrid();
iOperacao       = 0; // Determina a operação realizada (1 -> inclusão, 2 -> alteração, 3 -> exclusão)
js_getInfoControleFisicoFinanceiro();

function js_ajax(oParam, jsRetorno, sUrl, lAsync) {

  var mRetornoAjax;

  if (sUrl == undefined) {
    sUrl = 'lab4_laboratorio.RPC.php';
  }

  if (lAsync == undefined) {
    lAsync = false;
  }
	
  var oAjax = new Ajax.Request(sUrl, 
                               {
                                 method: 'post', 
                                 asynchronous: lAsync,
                                 parameters: 'json='+Object.toJSON(oParam),
                                 onComplete: function(oAjax) {
                                    
                                               var evlJS           = jsRetorno+'(oAjax);';
                                               return mRetornoAjax = eval(evlJS);
                                               
                                           }
                              }
                             );

  return mRetornoAjax;

}

/**** Bloco de funções do grid início */
function js_criaDataGrid() {

  var iInd               = -1;
  var oDBGrid            = new DBGrid('grid_controle');
  oDBGrid.nameInstance   = 'oDBGridControle';
  oDBGrid.hasTotalizador = false;
  oDBGrid.setHeight(100);
  oDBGrid.allowSelectColumns(false);

  var aHeader = new Array();
  var aAligns = new Array();
  if (iTipoControle == 3 || iTipoControle == 6 || iTipoControle == 7) {

    aHeader[++iInd] = 'Código';
    aAligns[iInd]   = 'left';
    aHeader[++iInd] = 'Grupo';
    aAligns[iInd]   = 'left';
    aHeader[++iInd] = 'Subgrupo';
    aAligns[iInd]   = 'left';
    aHeader[++iInd] = 'Forma de Organização';
    aAligns[iInd]   = 'left';

  } else if (iTipoControle == 2 || iTipoControle == 5 || iTipoControle == 8) {

    aHeader[++iInd] = 'Código';
    aAligns[iInd]   = 'left';
    aHeader[++iInd] = 'Exame';
    aAligns[iInd]   = 'left';

  } else if (iTipoControle == 9) {

    aHeader[++iInd] = 'Código';
    aAligns[iInd]   = 'left';
    aHeader[++iInd] = 'Laboratório';
    aAligns[iInd]   = 'left';

  }
  
  aHeader[++iInd] = 'Teto';
  aAligns[iInd]   = 'left';
  aHeader[++iInd] = 'Limite';
  aAligns[iInd]   = 'left';
  aHeader[++iInd] = 'Inicio';
  aAligns[iInd]   = 'left';
  aHeader[++iInd] = 'Fim';
  aAligns[iInd]   = 'left';
  aHeader[++iInd] = 'Período';
  aAligns[iInd]   = 'left';
  aHeader[++iInd] = 'Opções';
  aAligns[iInd]   = 'center';
  oDBGrid.setHeader(aHeader);
  oDBGrid.setCellAlign(aAligns);
  oDBGrid.setCellWidth(new Array( '5%',
      '25%',
      '15%',
      '10%',
      '10%',
      '10%',
      '10%',
      '20%'
));
  oDBGrid.show($('grid_controle'));
  oDBGrid.clearAll(true);

  return oDBGrid;

}

function js_getInfoControleFisicoFinanceiro() {

  var oParam           = new Object();

	oParam.exec          = 'getInfoControleFisicoFinanceiro';
  oParam.iTipoControle = iTipoControle;

  // Verifico se tem laboratório ou departamento
  if ((iTipoControle > 0 && iTipoControle < 7) || iTipoControle == 9) {
    oParam.iLabDepto = parent.document.getElementById('iLabDepto').value;
  }
 
  oDBGridControle.clearAll(true); // Limpo o grid

  js_ajax(oParam, 'js_retornoGetInfoControleFisicoFinanceiro');

}

function js_retornoGetInfoControleFisicoFinanceiro(oRetorno) {
  
  var oRetorno    = eval("("+oRetorno.responseText+")");
  var oRadios     = parent.document.getElementsByName('iRadioControles');
  var iValorRadio = 0;

  if (oRetorno.iStatus == 1) {

    if (oRadios != null && oRadios != undefined && oRadios.length > 0) {

      /* Verifico qual está marcado */
      for (var iCont = 0; iCont < oRadios.length; iCont++) {
      
        if (oRadios[iCont].checked) {

          iValorRadio = oRadios[iCont].value;
          break;

        }
      
      }
      
      /* Verifico qual radiobox deve ser selecionado, para exibir o formulário corretamente */
      var iTipoSelecionar = 0;
      if (oRetorno.aControles[0].la56_i_tipocontrole == 1
          || oRetorno.aControles[0].la56_i_tipocontrole == 4) {
        iTipoSelecionar = 1;
      } else if (oRetorno.aControles[0].la56_i_tipocontrole == 2
          || oRetorno.aControles[0].la56_i_tipocontrole == 5) {
        iTipoSelecionar = 2;
      } else if (oRetorno.aControles[0].la56_i_tipocontrole == 3
          || oRetorno.aControles[0].la56_i_tipocontrole == 6) {
        iTipoSelecionar = 3;
      } else if (oRetorno.aControles[0].la56_i_tipocontrole == 9
          || oRetorno.aControles[0].la56_i_tipocontrole == 4) {
        iTipoSelecionar = 4;
      } else {

        alert('Inconsistência! Tipo de controle não corresponde a nenhuma das opções de controle.');
        return false;

      }
      
      /* Desabilito os radiobox */
      for (var iCont = 0; iCont < oRadios.length; iCont++) {
        oRadios[iCont].disabled = true;
      }

      /* Se o radio que deveria estar selecionado não estiver, seleciono ele pra carregar o formulário corretamente */
      if (iValorRadio != iTipoSelecionar) {

        for (var iCont = 0; iCont < oRadios.length; iCont++) {

          if (oRadios[iCont].value == iTipoSelecionar) {

            oRadios[iCont].checked  = true;
            parent.js_loadFrame();
            //oRadios[iCont].click();
            return;

          }

        }

      }
      
    }

    for (var iCont = 0; iCont < oRetorno.aControles.length; iCont++) {

      with (oRetorno.aControles[iCont]) {

        var aLinha = new Array();
        var iInd   = -1;

        // Se possui as informações do grupo de exames
        if (iTipoControle == 3 || iTipoControle == 6 || iTipoControle == 7) {

          aLinha[++iInd] = la56_i_codigo;
          aLinha[++iInd] = sd60_c_grupo.urlDecode();
          aLinha[++iInd] = sd61_c_subgrupo.urlDecode()
          aLinha[++iInd] = sd62_c_formaorganizacao.urlDecode();

        } else if (iTipoControle == 2 || iTipoControle == 5 || iTipoControle == 8) {

          aLinha[++iInd] = la56_i_codigo;
          aLinha[++iInd] = la08_c_descr.urlDecode();

        } else if (iTipoControle == 9) {

          aLinha[++iInd] = la02_i_codigo;
          aLinha[++iInd] = la02_c_descr.urlDecode();
          
        }

        aLinha[++iInd] = la56_i_teto == 1 ? 'FÍSICO' : 'FINANCEIRO';
        aLinha[++iInd] = la56_n_limite;
        aLinha[++iInd] = js_formataData(la56_d_ini);
        aLinha[++iInd] = js_formataData(la56_d_fim);
        aLinha[++iInd] = la56_i_periodo == 1 ? 'DIÁRIO' : 'MENSAL';
                
        for (iIndx = 0; iIndx < oRetorno.aControles.length; iIndx++) { 
             
          if(iIndx != iCont 
             &&
             oRetorno.aControles[iIndx].la56_i_exame == la56_i_exame
             && 
             ((parseInt(oRetorno.aControles[iIndx].la56_d_fim.split("-")[0].toString(), 10) > 
               parseInt(la56_d_fim.split("-")[0].toString(), 10))
             ||
             (parseInt(oRetorno.aControles[iIndx].la56_d_fim.split("-")[0].toString(), 10) == 
                parseInt(la56_d_fim.split("-")[0].toString(), 10)
                &&
                parseInt(oRetorno.aControles[iIndx].la56_d_fim.split("-")[1].toString(), 10) > 
                parseInt(la56_d_fim.split("-")[1].toString(), 10))
             ||
             (parseInt(oRetorno.aControles[iIndx].la56_d_fim.split("-")[0].toString(), 10) == 
               parseInt(la56_d_fim.split("-")[0].toString(), 10)
               &&
               parseInt(oRetorno.aControles[iIndx].la56_d_fim.split("-")[1].toString(), 10) == 
               parseInt(la56_d_fim.split("-")[1].toString(), 10)
               &&
               parseInt(oRetorno.aControles[iIndx].la56_d_fim.split("-")[2].toString(), 10) > 
               parseInt(la56_d_fim.split("-")[2].toString(), 10)))){
            
            sDisabled = 'disabled';
            break;

          } else {
        	  sDisabled = '';
          }

        }
        aLinha[++iInd] = '<input type="button" onclick="js_alterar('+la56_i_codigo+','+la56_n_limite+');" value="Alterar" '+sDisabled+'>';
        aLinha[iInd]  += '&nbsp;&nbsp;&nbsp;';
        aLinha[iInd]  += '<input type="button" onclick="js_confirmar(\'excluir\','+la56_i_codigo+');" value="Excluir"';
        aLinha[iInd]  += sDisabled+'>';
        
        oDBGridControle.addRow(aLinha);

      }

    }

    oDBGridControle.renderRows();

  } else {

    /* Desabilito os radiobox */
    for (var iCont = 0; iCont < oRadios.length; iCont++) {
      oRadios[iCont].disabled = false;
    }

  }

}
/* FUNÇÕES DO GRID - FIM *************************/
function js_alterar(iId,iGasto) {

  if (iTipoControle < 7) {

    parent.document.iIndexSelect = parent.document.getElementById('iLabDepto').selectedIndex;
    parent.document.getElementById('iLabDepto').onchange = 
      function() { 
        this.selectedIndex = parent.document.iIndexSelect;
      };

  }
  window.location.href = 'lab4_controlefisicofinanceiro002.php?iOperacao=2&la56_i_codigo='+iId+
                         '&iTipoControle='+iTipoControle+'&iGasto='+iGasto;
}

function js_cancelar() {

  if (iTipoControle < 7) {

    parent.document.getElementById('iLabDepto').onchange = 
      function() { 
        parent.window.frames['iframeControle'].js_getInfoControleFisicoFinanceiro();
      };

  }
  window.location.href = 'lab4_controlefisicofinanceiro002.php?&iTipoControle='+iTipoControle;

}

function js_formataData(dData) {
  
  if (dData == undefined || dData.length != 10) {
    return dData;
  }
  return dData.substr(8, 2)+'/'+dData.substr(5, 2)+'/'+dData.substr(0, 4);

}

function js_validaEnvio() {

  if (iTipoControle == 3 || iTipoControle == 6 || iTipoControle == 7) {

    if ($F('la56_i_grupo') == '' || $F('sd60_c_grupo').value == '') {

      alert('Informe o grupo.');
      return false;

    }

  } else if (iTipoControle == 2 || iTipoControle == 5 || iTipoControle == 8) {

    if ($F('la56_i_exame') == '') {

      alert('Informe o exame.');
      return false;

    }

  } else if (iTipoControle == 9) {

    if ($F('la56_i_laboratorio') == '') {

      alert('Informe o laboratório.');
      return false;

    }

  }

  if ($F('la56_n_limite') == '') {

    alert('Informe o limite.');
    return false;

  }

  if ($F('la56_n_limite') == '') {

    alert('Informe o limite.');
    return false;

  }

  if ($F('la56_d_ini') == '') {

    alert('Informe a data de início da validade do controle.');
    return false;

  }

  if ($F('la56_d_fim') != '') {

    var aIni = $F('la56_d_ini').split('/');
    var aFim = $F('la56_d_fim').split('/');
    var dIni = new Date(aIni[2], aIni[1], aIni[0]);
    var dFim = new Date(aFim[2], aFim[1], aFim[0]);

    if (dFim < dIni) {
    			
      alert('Data final não pode ser menor que a data inicial.');
      $('la56_d_fim').value = '';
      $('la56_d_fim').focus();
      return false;
    
    }

  }

  return true;

}

function js_confirmar(sOp, iCod) {
  
  var oParam     = new Object();

  if (sOp == 'excluir') {
    
    iOperacao = 3;

    if (!confirm('Confirma a exclução do registro?')) {
      return false;
    }
	  oParam.exec          = 'excluirControleFisicoFinanceiro';
	  oParam.la56_i_codigo = iCod;

    js_ajax(oParam, 'js_retornoConfirmar');

  } else {

    if (!js_validaEnvio()) {
      return false;
    }

	  oParam.exec                            = 'incAltControleFisicoFinanceiro';
	  oParam.sOperacao                       = sOp;
    oParam.la56_i_teto                     = $F('la56_i_teto');
    oParam.la56_n_limite                   = $F('la56_n_limite');
    oParam.la56_i_periodo                  = $F('la56_i_periodo');
    oParam.la56_d_ini                      = $F('la56_d_ini');
    oParam.la56_d_fim                      = $F('la56_d_fim');
    oParam.la56_i_liberarequisicaosemsaldo = $F('la56_i_liberarequisicaosemsaldo');

    // Verifico se tem grupo de exames
    if (iTipoControle == 3 || iTipoControle == 6 || iTipoControle == 7) {

      oParam.la56_i_grupo = $F('la56_i_grupo');
      if ($F('la56_i_subgrupo') == '') {
        oParam.la56_i_subgrupo = 'null';
      } else {
        oParam.la56_i_subgrupo = $F('la56_i_subgrupo');
      }

      if ($F('la56_i_formaorganizacao') == '') {
        oParam.la56_i_formaorganizacao = 'null';
      } else {
        oParam.la56_i_formaorganizacao = $F('la56_i_formaorganizacao');
      }

    } else {

      oParam.la56_i_grupo            = 'null';
      oParam.la56_i_subgrupo         = 'null';
      oParam.la56_i_formaorganizacao = 'null';

    }
    
    // Verifico se tem exames
    if (iTipoControle == 2 || iTipoControle == 5 || iTipoControle == 8) {
      oParam.la56_i_exame = $F('la56_i_exame');
    } else {
      oParam.la56_i_exame = 'null';
    }

    // Verifico se tem departamento (no select de cima)
    if (iTipoControle == 1 || iTipoControle == 2 || iTipoControle == 3 || iTipoControle == 9) {
      oParam.la56_i_depto = parent.document.getElementById('iLabDepto').value;
    } else {
      oParam.la56_i_depto = 'null';
    }

    // Verifico se tem laboratório
    if (iTipoControle == 4 || iTipoControle == 5 || iTipoControle == 6 || iTipoControle == 9) {

      if (iTipoControle == 9) { // No formulário
        oParam.la56_i_laboratorio = $F('la56_i_laboratorio');
      } else { // No select de cima
        oParam.la56_i_laboratorio = parent.document.getElementById('iLabDepto').value;
      }

    } else {
      oParam.la56_i_laboratorio = 'null';
    }

    if (sOp == 'alterar') {
      
      iOperacao = 2;
      oParam.la56_i_codigo = iCod;

    } else {

      iOperacao = 1;
      oParam.iTipoControle = iTipoControle;

    }

    js_ajax(oParam, 'js_retornoConfirmar');

  }

}

function js_retornoConfirmar(oRetorno) {
  
  oRetorno = eval("("+oRetorno.responseText+")");

  alert(oRetorno.sMessage.urlDecode().replace(/\\n/g, "\n"));
  if (oRetorno.iStatus == 1) {

    if (iOperacao == 1) {
      
      js_limpar();
      js_getInfoControleFisicoFinanceiro();

    } else if (iOperacao == 2) {
      js_cancelar();
    } else {
      js_getInfoControleFisicoFinanceiro();
    }

    return true;

  } else {
    return false;
  }

}

function js_limpar() {

  if (iTipoControle == 3 || iTipoControle == 6 || iTipoControle == 7) {

    $('la56_i_grupo').value            = '';
    $('sd60_c_grupo').value            = '';
    $('sd60_c_nome').value             = '';
    $('la56_i_subgrupo').value         = '';
    $('sd61_c_subgrupo').value         = '';
    $('sd61_c_nome').value             = '';
    $('la56_i_formaorganizacao').value = '';
    $('sd62_c_formaorganizacao').value = '';
    $('sd62_c_nome').value             = '';

  } else if (iTipoControle == 2 || iTipoControle == 5 || iTipoControle == 8) {

    $('la56_i_exame').value = '';
    $('la08_c_descr').value = '';

  } else if (iTipoControle == 9) {

    $('la56_i_laboratorio').value = '';
    $('la02_c_descr').value       = '';

  }

  $('la56_i_teto').options.selectedIndex    = 0;
  $('la56_i_periodo').options.selectedIndex = 0;
  $('la56_n_limite').value                  = '';
  $('la56_d_ini').value                     = '';
  $('la56_d_fim').value                     = '';

}

function js_pesquisala56_i_laboratorio(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_lab_laboratorio', 'func_lab_laboratorio.php?funcao_js='+
                        'parent.js_mostralab_laboratorio1|la02_i_codigo|la02_c_descr', 'Pesquisa', true
                       );

  } else {

    if (document.form1.la56_i_laboratorio.value != '') {

       js_OpenJanelaIframe('top.corpo', 'db_iframe_lab_laboratorio', 'func_lab_laboratorio.php?pesquisa_chave='+
                           document.form1.la56_i_laboratorio.value+'&funcao_js=parent.js_mostralab_laboratorio', 
                           'Pesquisa', false
                          );

    } else {
      document.form1.la02_c_descr.value = ''; 
    }

  }

}
function js_mostralab_laboratorio(chave, erro) {

  document.form1.la02_c_descr.value = chave; 
  if (erro == true) {

    document.form1.la56_i_laboratorio.focus();
    document.form1.la56_i_laboratorio.value = '';

  }

}
function js_mostralab_laboratorio1(chave1, chave2) {

  document.form1.la56_i_laboratorio.value = chave1;
  document.form1.la02_c_descr.value       = chave2;
  parent.db_iframe_lab_laboratorio.hide();

}
/*
function js_pesquisala56_i_depto(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_db_depart', 'func_db_depart.php?funcao_js='+
                        'parent.js_mostradb_depart1|coddepto|descrdepto', 'Pesquisa', true
                       );

  } else {

    if (document.form1.la56_i_depto.value != '') {

       js_OpenJanelaIframe('top.corpo', 'db_iframe_db_depart', 'func_db_depart.php?pesquisa_chave='+
                           document.form1.la56_i_depto.value+'&funcao_js=parent.js_mostradb_depart', 
                           'Pesquisa', false
                          );
       
    } else {
      document.form1.descrdepto.value = ''; 
    }

  }

}
function js_mostradb_depart(chave, erro) {

  document.form1.descrdepto.value = chave; 
  if (erro == true) { 

    document.form1.la56_i_depto.focus(); 
    document.form1.la56_i_depto.value = ''; 

  }

}
function js_mostradb_depart1(chave1, chave2) {

  document.form1.la56_i_depto.value = chave1;
  document.form1.descrdepto.value   = chave2;
  db_iframe_db_depart.hide();

}
*/
function js_pesquisala56_i_exame(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_lab_exame', 'func_lab_exame.php?funcao_js='+
                        'parent.js_mostralab_exame1|la08_i_codigo|la08_c_descr', 'Pesquisa', true
                       );

  } else {

    if (document.form1.la56_i_exame.value != '') { 
       js_OpenJanelaIframe('top.corpo', 'db_iframe_lab_exame', 'func_lab_exame.php?pesquisa_chave='+
                           document.form1.la56_i_exame.value+'&funcao_js=parent.js_mostralab_exame', 
                           'Pesquisa', false
                          );

    } else {
      document.form1.la08_c_descr.value = ''; 
    }

  }

}
function js_mostralab_exame(chave, erro) {

  document.form1.la08_c_descr.value = chave; 
  if (erro == true) {

    document.form1.la56_i_exame.focus(); 
    document.form1.la56_i_exame.value = '';

  }

}
function js_mostralab_exame1(chave1, chave2) {

  document.form1.la56_i_exame.value = chave1;
  document.form1.la08_c_descr.value = chave2;
  parent.db_iframe_lab_exame.hide();

}
function js_pesquisala56_i_grupo(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_sau_grupo', 'func_sau_grupo.php?funcao_js='+
                        'parent.js_mostrasau_grupo|sd60_i_codigo|sd60_c_nome|sd60_c_grupo', 
                        'Pesquisa', true
                       );

  } else {

    if (document.form1.sd60_c_grupo.value != '') {

       js_OpenJanelaIframe('top.corpo', 'db_iframe_sau_grupo', 'func_sau_grupo.php?chave_sd60_c_grupo='+
                           document.form1.sd60_c_grupo.value+'&funcao_js=parent.js_mostrasau_grupo|'+
                           'sd60_i_codigo|sd60_c_nome|sd60_c_grupo&nao_mostra=true', 
                           'Pesquisa', false
                          );

    } else {

      js_limpaGrupo();
      js_limpaSubGrupo();
      js_limpaFormaOrg();

    }

  }

}
function js_mostrasau_grupo(chave1, chave2, chave3) {

  js_limpaSubGrupo();
  js_limpaFormaOrg();

  if (chave1 == '') {
    chave3 = '';
  }
  document.form1.la56_i_grupo.value = chave1;
  document.form1.sd60_c_nome.value  = chave2;
  document.form1.sd60_c_grupo.value = chave3;
  parent.db_iframe_sau_grupo.hide();

}
function js_pesquisala56_i_subgrupo(mostra) {

  if ($F('sd60_c_grupo') == '' || $F('la56_i_grupo') == '') {

    alert('Selecione um grupo primeiro.');
    $('sd61_c_subgrupo').value = '';
    return false;

  }

  var sGet = '&chave_grupo='+$F('sd60_c_grupo');

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_sau_subgrupo', 'func_sau_subgrupo.php?'+
                        'funcao_js=parent.js_mostrasau_subgrupo|sd61_i_codigo|sd61_c_nome|sd61_c_subgrupo'+
                        sGet, 'Pesquisa', true
                       );

  } else {

    if (document.form1.sd61_c_subgrupo.value != '') {

      js_OpenJanelaIframe('top.corpo', 'db_iframe_sau_subgrupo', 'func_sau_subgrupo.php?chave_sd61_c_subgrupo='+
                          document.form1.sd61_c_subgrupo.value+'&funcao_js=parent.js_mostrasau_subgrupo|'+
                          'sd61_i_codigo|sd61_c_nome|sd61_c_subgrupo&nao_mostra=true'+sGet, 
                          'Pesquisa', false
                         );

    } else {

      js_limpaSubGrupo();
      js_limpaFormaOrg();

    }

  }

}
function js_mostrasau_subgrupo(chave1, chave2, chave3) {

  js_limpaFormaOrg();

  if (chave1 == '') {
    chave3 = '';
  }

  document.form1.la56_i_subgrupo.value = chave1;
  document.form1.sd61_c_nome.value     = chave2;
  document.form1.sd61_c_subgrupo.value = chave3;
  parent.db_iframe_sau_subgrupo.hide();

}

function js_pesquisala56_i_formaorganizacao(mostra) {

  if ($F('sd60_c_grupo') == '' || $F('la56_i_grupo') == '') {

    alert('Selecione um grupo primeiro.');
    $('sd62_c_formaorganizacao').value = '';
    return false;

  }

  if ($F('sd61_c_subgrupo') == '' || $F('la56_i_subgrupo') == '') {

    alert('Selecione um subgrupo primeiro.');
    $('sd62_c_formaorganizacao').value = '';
    return false;

  }

  var sGet = '&chave_grupo='+$F('sd60_c_grupo')+'&chave_subgrupo='+$F('sd61_c_subgrupo');

  if (mostra == true) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_sau_formaorganizacao', 'func_sau_formaorganizacao.php?'+
                        'funcao_js=parent.js_mostrasau_formaorganizacao|sd62_i_codigo|sd62_c_nome|'+
                        'sd62_c_formaorganizacao'+sGet, 'Pesquisa', true
                       );

  } else {

    if (document.form1.sd62_c_formaorganizacao.value != '') {

       js_OpenJanelaIframe('top.corpo', 'db_iframe_sau_formaorganizacao', 'func_sau_formaorganizacao.php?'+
                           'chave_sd62_c_formaorganizacao='+document.form1.sd62_c_formaorganizacao.value+
                           '&funcao_js=parent.js_mostrasau_formaorganizacao|sd62_i_codigo|sd62_c_nome|'+
                           'sd62_c_formaorganizacao&nao_mostra=true'+sGet, 'Pesquisa', false
                          );

    } else {
      js_limpaFormaOrg();
    }

  }

}
function js_mostrasau_formaorganizacao(chave1, chave2, chave3) {

  if (chave1 == '') {
    chave3 = '';
  }

  document.form1.la56_i_formaorganizacao.value = chave1;
  document.form1.sd62_c_nome.value             = chave2;
  document.form1.sd62_c_formaorganizacao.value = chave3;
  parent.db_iframe_sau_formaorganizacao.hide();

}

function js_limpaGrupo() {

  $('la56_i_grupo').value = '';
  $('sd60_c_grupo').value = '';
  $('sd60_c_nome').value  = '';

}

function js_limpaSubGrupo() {

  $('la56_i_subgrupo').value         = '';
  $('sd61_c_subgrupo').value         = '';
  $('sd61_c_nome').value             = '';

}

function js_limpaFormaOrg() {

  $('la56_i_formaorganizacao').value = '';
  $('sd62_c_formaorganizacao').value = '';
  $('sd62_c_nome').value             = '';

}
function js_limiteminimo(oLimite){
	if (parseFloat(oLimite.value) <  parseInt($F('iGasto').value,10)) {

		alert('Quantidade não pode ser menor que o saldo já gasto!');
		oLimite.value = '';
		
	}
}
</script>