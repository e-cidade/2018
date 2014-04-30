<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: TFD
$oDaoCgsUnd->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("tf29_i_prontuario");
$oRotulo->label("tf30_i_encaminhamento");
$oRotulo->label("tf01_i_cgsund");
$oRotulo->label("s115_c_cartaosus");
$oRotulo->label("j13_codi");
$oRotulo->label("z01_v_compl");
$oRotulo->label("z01)i_numero");
?>
<form name="form1" method="post" action="">
<center>
  <table border="0" width="98%">
    <tr>
      <td nowrap title="<?=@$Ttf30_i_encaminhamento?>" style="width: 50px;">
        <?
        db_ancora(@$Ltf30_i_encaminhamento, 'js_pesquisatf30_i_encaminhamento(true);', $db_opcao);
        ?>
      </td>
      <td>
        <?
        db_input('tf30_i_encaminhamento', 15, $Itf30_i_encaminhamento, true, 'text', $db_opcao,
                 'onchange="js_pesquisatf30_i_encaminhamento(false);"'
                );
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Ttf29_i_prontuario?>">
        <?
        db_ancora(@$Ltf29_i_prontuario, 'js_pesquisatf29_i_prontuario(true);', $db_opcao);
        ?>
      </td>
      <td>
        <?
        db_input('tf29_i_prontuario', 15, $Itf29_i_prontuario, true, 'text', $db_opcao,
                 'onchange="js_pesquisatf29_i_prontuario(false);"'
                );
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Ts115_c_cartaosus?>">
        <?=@$Ls115_c_cartaosus?>
      </td>
      <td>
        <?
        db_input('s115_c_cartaosus2', 15, $Is115_c_cartaosus, true, 'text', $db_opcao,  ' onchange="js_getCgsCns();"');
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Ttf01_i_cgsund?>">
        <?
        db_ancora(@$Ltf01_i_cgsund, "js_pesquisatf01_i_cgsund(true);", $db_opcao);
        ?>
      </td>
      <td>
        <?
        db_input('tf01_i_cgsund', 15, $Itf01_i_cgsund, true, 'text', $db_opcao,
                 ' onchange="js_pesquisatf01_i_cgsund(false); "'
                );
        db_input('z01_v_nome', 50, $Iz01_v_nome, true, 'text', 3, '');
        ?>
      </td>
    </tr>
  </table>

  <table border="0" width="90%">
    <tr>
      <td width="50%" valign="top">
        <fieldset style="height: 165px;"> <legend><b>Endereço</b></legend>
          <table width="100%">
            <tr>
              <td nowrap title="<?=@$Tz01_v_ender?>" width="62px">
                <?
                db_ancora(@$Lz01_v_ender, "js_ruas();", $db_opcao);
                ?>
              </td>
              <td nowrap>
                <?
                db_input('z01_v_ender', 34, $Iz01_v_ender, true, 'text', $db_opcao, 'onchange="js_change();"');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=$Tz01_i_numero?>|<?=$Tz01_v_compl?>" colspan="2">
                <label style="margin-right: 14px;"><b>Número:</b></label>
                <?
                db_input('z01_i_numero', 6, $Iz01_i_numero, true, 'text', $db_opcao, 'onchange="js_change();"');
                ?>
                <label style="margin-left: 10px;"><b>Complemento:</b></label>
                <?
                db_input('z01_v_compl', 12, $Iz01_v_compl, true, 'text', $db_opcao, 'onchange="js_change();"');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=$Tz01_v_bairro?>">
                <?
                db_ancora(@$Lz01_v_bairro, "js_bairro();", $db_opcao);
                ?>
                &nbsp;
              </td>
              <td nowrap>
                <?
                db_input('j13_codi', 10, $Ij13_codi, true, 'hidden', $db_opcao);
                db_input('z01_v_bairro', 34, $Iz01_v_bairro, true, 'text', 3, 'onchange="js_change();"');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tz01_v_munic?>">
                <?=@$Lz01_v_munic?>&nbsp;
              </td>
              <td nowrap>
                <?
                db_input('z01_v_munic', 34, $Iz01_v_munic, true, 'text', $db_opcao, 'onchange="js_change();"');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tz01_v_cep?>">
                <?
	              db_ancora(@$Lz01_v_cep, "js_cepcon(true);", $db_opcao);
	              ?>
                &nbsp;
              </td>
              <td nowrap>
                <?
                db_input('z01_v_cep', 10, $Iz01_v_cep, true, 'text', $db_opcao, 'onchange="js_change();"');
                ?>
                <input type="button" name="buscacep" value="Pesquisar" onclick="js_cepcon(false);">&nbsp;
                &nbsp;&nbsp;
                <label style="margin-left: 20px;"<?=@$Lz01_v_uf?></label>
                &nbsp;
                <?
                db_input('z01_v_uf', 2, $Iz01_v_uf, true, 'text', $db_opcao, 'onchange="js_change();"');
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
      <td width="50%" valign="top">
        <fieldset style="height: 165px; padding-left: 3px;"> <legend><b>Dados Pessoais</b></legend>
          <table width="100%">
            <tr>
              <td nowrap>
                <b>Cartao SUS:</b>
              </td>
              <td nowrap>
                <?
                $z01_i_cgsund2 = '';
                db_input('s115_i_codigo', 1, '', true, 'hidden', 3);
                db_input('z01_i_cgsund2', 1, '', true, 'hidden', 3);
           	    db_input('s115_c_cartaosus', 15, @$Is115_c_cartaosus, true, 'text', $db_opcao,
                         'onchange="js_change();"'
                        );
                ?>
               </td>
               <td nowrap>
                <b>Tipo:</b>
               </td>
               <td nowrap>
                <?
		            $x = array("D"=>"Definitivo", "P"=>"Provisório");
	        	    db_select('s115_c_tipo', $x, true, $db_opcao, 'onchange="js_change();"');
	              ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=$Tz01_d_nasc?>">
                <?=$Lz01_d_nasc?>
              </td>
              <td nowrap>
                <?
                db_inputdata('z01_d_nasc', @$z01_d_nasc_dia, @$z01_d_nasc_mes, @$z01_d_nasc_ano, true,
                             'text', $db_opcao, 'onchange="js_change();"', '', '', 'parent.js_change();'
                            );
                ?>
              </td>
              <td nowrap>
                <label style=""><?=@$Lz01_v_sexo?></label>
              </td>
              <td nowrap>
                <?
		            $x = array("Masculino"=>"Masculino", "Feminino"=>"Feminino");
	        	    db_select('z01_v_sexo', $x, true, $db_opcao, 'onchange="js_change();"');
	              ?>
              </td>
            </tr>
            <tr>
              <td title='<?=$Tz01_i_cgsund?>' nowrap>
                <label><?=@$Lz01_v_cgccpf?></label>
              </td>
              <td nowrap>
                <?
                db_input('z01_v_cgccpf', 12, @$Iz01_v_cgccpf, true, 'text', $db_opcao,
                         "onblur='js_verificaCGCCPF(this);' onchange=\"js_change();\""
                        );
                ?>
              </td>
              <td nowrap>
                <label><?=@$Lz01_v_ident?></label>
              </td>
              <td nowrap>
                <?
                db_input('z01_v_ident', 12, $Iz01_v_ident, true, 'text', $db_opcao, 'onchange="js_change();"');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title=<?=@$Tz01_v_mae?> colspan="1">
                <?=@$Lz01_v_mae?>
              </td>
              <td nowrap colspan="3">
                <?
                db_input('z01_v_mae', 39, $Iz01_v_mae, true, 'text', $db_opcao, 'onchange="js_change();"');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title=<?=@$Tz01_v_pai?> colspan="1">
                <?=@$Lz01_v_pai?>
              </td>
              <td nowrap colspan="3">
                <?
                db_input('z01_v_pai', 39, $Iz01_v_pai, true, 'text', $db_opcao, 'onchange="js_change();"');
                db_input('mudanca', 1, '', true, 'hidden', 3);
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tz01_v_email?>" colspan="1">
                <?=@$Lz01_v_email?>
              </td>
              <td nowrap colspan="3">
                <?
                db_input('z01_v_email', 39, $Iz01_v_email, true, 'text', $db_opcao, 'onchange="js_change();"');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tz01_v_telef?>">
                <label><?=@$Lz01_v_telef?></label>
              </td>
              <td nowrap>
                <?
                db_input('z01_v_telef', 12, $Iz01_v_telef, true, 'text', $db_opcao, 'onchange="js_change();"');
                ?>
              </td>
              <td nowrap>
                <label><?=@$Lz01_v_telcel?></label>
              </td>
              <td nowrap>
                <?
                db_input('z01_v_telcel', 12, $Iz01_v_telcel, true, 'text', $db_opcao, 'onchange="js_change();"');
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
  </table>

  <table border="0" width="99%">
    <tr>
      <td align="right">
        <input name="atualizar" type="button" id="atualizar" value="Atualizar CGS"
          onclick="js_atualizarCgs();" disabled>
        <input name="novo" type="button" id="novo" value="Novo Tratamento"
          onclick="js_novoTratamento();" disabled>
      </td>
    </tr>
  </table>

  <table border="0" width="99%">
    <tr>
      <td>
        <div id='grid_pedidostfd' style='width: 100%;'></div>
      </td>
    </tr>
  </table>

</center>
</form>
<script>

lPermissaoCgs = <? echo db_permissaomenu(db_getsession('DB_anousu'), 1000004, 1045411).';'; ?>


oDBGridPedidostfd = js_cria_datagrid();
sUrl = 'tfd4_pedidotfd.RPC.php';

function js_novoTratamento() {

  if ($F('tf01_i_cgsund') == '') {

    alert('Você deve digitar um CGS.');
    return false;

  }

  if ($F('mudanca') == 'true') {

    if (confirm('Foram detectadas mudanças nas informações do paciente.'+
               ' Deseja atualizar as informações antes de prosseguir?'
              )) {

      js_atualizarCgs();

    }

  }

  sEncaminhamento = '';
  if ($F('tf30_i_encaminhamento').trim() != '') {
    sEncaminhamento = '&tf30_i_encaminhamento='+$F('tf30_i_encaminhamento');
  }
  sProntuario = '';
  if ($F('tf29_i_prontuario').trim() != '') {
    sProntuario = '&tf29_i_prontuario='+$F('tf29_i_prontuario');
  }
  parent.document.formaba.a2.disabled = false;
  top.corpo.iframe_a2.location.href   = 'tfd4_tfd_pedidotfd002.php?tf01_i_cgsund='+$F('tf01_i_cgsund')+
                                        '&z01_v_nome='+$F('z01_v_nome')+sEncaminhamento+sProntuario;
  parent.mo_camada('a2');

}

function js_pesquisatf30_i_encaminhamento(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_sau_encaminhamentos',
                        'func_sau_encaminhamentos.php?funcao_js=parent.js_mostraencaminhamento|s142_i_codigo|'+
                        's142_i_cgsund|z01_v_nome|s142_i_prontuario&lFiltraTfd=true', 'Pesquisa Encaminhamento',
                        mostra
                       );

  } else {

    if (document.form1.tf30_i_encaminhamento.value != '') {

      sChave = '&chave_s142_i_codigo='+$F('tf30_i_encaminhamento')+'&nao_mostra=true&lFiltraTfd=true';

      js_OpenJanelaIframe('', 'db_iframe_sau_encaminhamentos',
                          'func_sau_encaminhamentos.php?funcao_js=parent.js_mostraencaminhamento|s142_i_codigo|'+
                          's142_i_cgsund|z01_v_nome|s142_i_prontuario'+sChave, 'Pesquisa Encaminhamento', mostra
                         );

     } else {

       js_limpaInfoCgs();

     }

  }

}

function js_mostraencaminhamento(chave1, chave2, chave3, chave4) {

  js_limpaInfoCgs();
  if (chave1 == '' || chave1 == undefined) {

    alert('Chave não encontrada.');
    $('tf30_i_encaminhamento').value = '';
    return false;

  }

  document.form1.tf30_i_encaminhamento.value = chave1;
  document.form1.tf01_i_cgsund.value         = chave2;
  document.form1.z01_v_nome.value            = chave3;
  document.form1.tf29_i_prontuario.value     = chave4;
  js_getInfoCgs();
  db_iframe_sau_encaminhamentos.hide();

}

function js_pesquisatf29_i_prontuario(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_prontuarios', 'func_prontuarios.php?funcao_js=parent.js_mostraprontuarios|'+
                        'sd24_i_codigo|sd24_i_numcgs|z01_v_nome&lFiltraTfd=true', 'Pesquisa FAA', true
                       );

  } else {

    if ($F('tf29_i_prontuario') != '') {

      sChave = 'nao_mostra=true&lFiltraTfd=true';

      js_OpenJanelaIframe('', 'db_iframe_prontuarios', 'func_prontuarios.php?'+sChave+
                          '&funcao_js=parent.js_mostraprontuarios'+
                          '|sd24_i_codigo|sd24_i_numcgs|z01_v_nome&chave_sd24_i_codigo='+
                          $F('tf29_i_prontuario'), 'Pesquisa FAA', false
                         );

    }

  }

}
function js_mostraprontuarios(chave1, chave2, chave3) {

  js_limpaInfoCgs();
  if (chave1 == '' || chave1 == undefined) {

    alert('Chave não encontrada.');
    $('tf29_i_prontuario').value = '';
    return false;

  }
  $('tf29_i_prontuario').value = chave1;
  $('tf01_i_cgsund').value     = chave2;
  $('z01_v_nome').value        = chave3;
  js_getInfoCgs();
  db_iframe_prontuarios.hide();

}

function js_getCgsCns() {

  if ($F('s115_c_cartaosus2') == '') {
    return false;
  }
  if ($F('s115_c_cartaosus2').length != 15 || isNaN($F('s115_c_cartaosus2'))) {

    alert('Número de CNS inválido para busca.');
    $('s115_c_cartaosus2').value = '';
    return false;

  }

  var oParam  = new Object();
	oParam.exec = "getCgsCns";
	oParam.iCns = $F('s115_c_cartaosus2');

	js_ajax(oParam, 'js_retornogetCgsCns');

}
function js_retornogetCgsCns(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.z01_i_cgsund == '') {

    alert('CNS não encontrado.');
    return false;

  }

  js_limpaInfoCgs();
  $('tf01_i_cgsund').value = oRetorno.z01_i_cgsund;
  $('z01_v_nome').value    = oRetorno.z01_v_nome.urlDecode();
  js_getInfoCgs();

}

function js_ajax(oParam, jsRetorno) {

	var objAjax = new Ajax.Request(
                         sUrl,
                         {
                          method: 'post',
                          asynchronous: false,
                          parameters: 'json='+Object.toJSON(oParam),
                          onComplete: function(objAjax) {
                          				var evlJS = jsRetorno+'(objAjax);';
                                  return eval(evlJS);
                          			}
                         }
                        );

}

function js_alterarPedido(iPedido)  {

  sChave                              = 'chavepesquisa='+iPedido;
  parent.document.formaba.a2.disabled = false;
  top.corpo.iframe_a2.location.href   = 'tfd4_tfd_pedidotfd002.php?'+sChave;
  parent.mo_camada('a2');


}

function js_protocoloPedido(iNum) {

  sChave = 'tf01_i_pedidotfd='+iNum;

  jan    = window.open('tfd2_protocolopedidotfd002.php?'+sChave, '',
                       'width='+(screen.availWidth - 5)+',height='+
                       (screen.availHeight - 40)+',scrollbars=1,location=0 '
                      );
  jan.moveTo(0, 0);

}

/**** Bloco de funções do grid início */
function js_cria_datagrid() {

  oDBGrid                = new DBGrid('grid_pedidostfd');
  oDBGrid.nameInstance   = 'oDBGridPedidostfd';
  oDBGrid.hasTotalizador = false;
  oDBGrid.setCellWidth(new Array('5%', '10%', '10%', '30%', '25%', '10%','10%'));
  oDBGrid.setHeight(60);
  oDBGrid.allowSelectColumns(false);

  var aHeader = new Array();
  aHeader[0]  = 'Pedido';
  aHeader[1]  = 'Entrada';
  aHeader[2]  = 'Saída';
  aHeader[3]  = 'Prestadora';
  aHeader[4]  = 'Cidade';
  aHeader[5]  = 'Situação';
  aHeader[6]  = 'Opções';
  oDBGrid.setHeader(aHeader);

  var aAligns = new Array();
  aAligns[0]  = 'center';
  aAligns[1]  = 'center';
  aAligns[2]  = 'center';
  aAligns[3]  = 'center';
  aAligns[4]  = 'center';
  aAligns[5]  = 'center';
  aAligns[6]  = 'center';

  oDBGrid.setCellAlign(aAligns);
  oDBGrid.show($('grid_pedidostfd'));
  oDBGrid.clearAll(true);

  return oDBGrid;

}

function js_formataData(dData) {

  if (dData == undefined || dData.length != 10) {
    return dData;
  }
  return dData.substr(8, 2)+'/'+dData.substr(5, 2)+'/'+dData.substr(0, 4);

}

function js_getPedidosTfdCgs() {

  var oParam  = new Object();
	oParam.exec = 'getPedidosTfdCgs';
	oParam.iCgs = $F('tf01_i_cgsund');

  if ($F('tf01_i_cgsund') != '') {
    js_ajax(oParam, 'js_retornogetPedidosTfdCgs');
  }

}

function js_retornogetPedidosTfdCgs(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {
    return false;
  } else {

    oRetorno.oPedidos.each(
      function (oPedidosTfd) {

        var aLinha = new Array();

        aLinha[0] = oPedidosTfd.tf01_i_codigo;
        aLinha[1] = js_formataData(oPedidosTfd.tf16_d_dataagendamento);
        aLinha[2] = js_formataData(oPedidosTfd.tf17_d_datasaida);
        aLinha[3] = oPedidosTfd.z01_nomeprestadora.urlDecode();
        aLinha[4] = oPedidosTfd.tf03_c_descr.urlDecode();
        aLinha[5] = oPedidosTfd.tf26_c_descr.urlDecode();
        aLinha[6] = '<span onclick="js_alterarPedido('+oPedidosTfd.tf01_i_codigo+');"'+
                    ' style="color: blue; text-decoration: underline; cursor: pointer;"><b>A</b></span>&nbsp;'+
                    '&nbsp;&nbsp;&nbsp;&nbsp;<span onclick="js_protocoloPedido('+oPedidosTfd.tf01_i_codigo+');"'+
                    ' style="color: blue; text-decoration: underline; cursor: pointer;"><b>P</b></span>';

        oDBGridPedidostfd.addRow(aLinha);

    });
    oDBGridPedidostfd.renderRows();

  }

}

/* Bloco de funções do grid fim *****/

/**** Bloco de funções dos dados do CGS início */
 function js_ruas() {

  js_OpenJanelaIframe('', 'db_iframe_ruas', 'func_ruas.php?rural=1&funcao_js='+
                      'parent.js_preenchepesquisaruas|j14_codigo|j14_nome',
                      'Pesquisa', true
                     );

 }
 function js_preenchepesquisaruas(chave, chave1) {

   document.form1.z01_v_ender.value = chave1;
   db_iframe_ruas.hide();
   js_change();

 }

 function js_bairro() {

  js_OpenJanelaIframe('', 'db_iframe_bairro', 'func_bairro.php?rural=1&funcao_js='+
                      'parent.js_preenchebairro|j13_codi|j13_descr', 'Pesquisa', true
                     );

 }

 function js_preenchebairro(chave, chave1) {

  document.form1.j13_codi.value     = chave;
  document.form1.z01_v_bairro.value = chave1;
  db_iframe_bairro.hide();
  js_change();

 }

 function js_cepcon(abre) {

  if (abre == true) {

    js_OpenJanelaIframe('', 'db_iframe_cep', 'func_cep.php?funcao_js=parent.js_preenchecepcon|cep|'+
                        'cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro|z01_v_cep',
                        'Pesquisa', true
                       );

  } else {

    js_OpenJanelaIframe('', 'db_iframe_cep', 'func_cep.php?pesquisa_chave='+document.form1.z01_v_cep.value+
                        '&funcao_js=parent.js_preenchecepcon|cep|cp06_logradouro|cp05_localidades|'+
                        'cp05_sigla|cp01_bairro|z01_v_cep', 'Pesquisa', false
                       );

  }
}
function js_preenchecepcon(chave, chave1, chave2, chave3, chave4, chave5, chave6) {

  document.form1.z01_v_cep.value    = chave;
  document.form1.z01_v_ender.value  = chave1;
  document.form1.z01_v_munic.value  = chave2;
  document.form1.z01_v_uf.value     = chave3;
  document.form1.z01_v_bairro.value = chave4;
  db_iframe_cep.hide();
  js_change();

}
function js_preenchecepcon1(chave, chave1, chave2, chave3, chave4) {

  if (chave == "" && chave1 == "" && chave2 == "" && chave3 == "" && chave4 == "") {

    alert('CEP não encontrado.');
    document.form1.z01_v_cep.focus();

  }

  document.form1.z01_v_cep.value    = chave;
  document.form1.z01_v_ender.value  = chave1;
  document.form1.z01_v_munic.value  = chave2;
  document.form1.z01_v_uf.value     = chave3;
  document.form1.z01_v_bairro.value = chave4;
  js_change();

}

function js_pesquisatf01_i_cgsund(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_cgs_und', 'func_cgs_und.php?funcao_js=parent.js_mostracgs1|'+
                        'z01_i_cgsund|z01_v_nome', 'Pesquisa', true
                       );

  } else {

     if (document.form1.tf01_i_cgsund.value != '') {

        js_OpenJanelaIframe('', 'db_iframe_cgs_und', 'func_cgs_und.php?pesquisa_chave='+
                            document.form1.tf01_i_cgsund.value+
                            '&funcao_js=parent.js_mostracgs', 'Pesquisa', false
                           );

     } else {

       document.form1.z01_v_nome.value = '';
       js_limpaInfoCgs();

     }

  }

}
function js_mostracgs(chave, erro){

  iCgs = $F('tf01_i_cgsund');
  js_limpaInfoCgs();
  document.form1.tf01_i_cgsund.value = iCgs;
  document.form1.z01_v_nome.value    = chave;
  if (erro == true){

    document.form1.tf01_i_cgsund.focus();
    document.form1.tf01_i_cgsund.value = '';

  } else {
    js_getInfoCgs();
  }
  js_change();

}
function js_mostracgs1(chave1, chave2){

  js_limpaInfoCgs();
  document.form1.tf01_i_cgsund.value = chave1;
  document.form1.z01_v_nome.value    = chave2;
  js_getInfoCgs();
  db_iframe_cgs_und.hide();
  js_change();

}

function js_getInfoCgs() {

  var oParam  = new Object();
	oParam.exec = "getInfoCgs";
	oParam.iCgs = $F('tf01_i_cgsund');

  if ($F('tf01_i_cgsund') != '') {
    js_ajax(oParam, 'js_retornogetInfoCgs');
  }

}

function js_retornogetInfoCgs(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.z01_d_nasc != '') {

    aNasc = oRetorno.z01_d_nasc.split('-');
    dNasc = aNasc[2]+'/'+aNasc[1]+'/'+aNasc[0];

  } else {

    aNasc = new Array('', '', '');
    dNasc = '';
  }

  $('z01_i_cgsund2').value     = oRetorno.z01_i_cgsund.urlDecode();
  $('z01_v_ender').value       = oRetorno.z01_v_ender.urlDecode();
  $('z01_v_bairro').value      = oRetorno.z01_v_bairro.urlDecode();
  $('z01_v_munic').value       = oRetorno.z01_v_munic.urlDecode();
  $('z01_v_cep').value         = oRetorno.z01_v_cep.urlDecode();
  $('z01_v_uf').value          = oRetorno.z01_v_uf.urlDecode();
  $('z01_v_email').value       = oRetorno.z01_v_email.urlDecode();
  $('z01_v_telef').value       = oRetorno.z01_v_telef.urlDecode();
  $('z01_v_telcel').value      = oRetorno.z01_v_telcel.urlDecode();
  $('z01_i_numero').value      = oRetorno.z01_i_numero.urlDecode();
  $('z01_v_compl').value       = oRetorno.z01_v_compl.urlDecode();
  var sSexo                    = oRetorno.z01_v_sexo.urlDecode();
  if (sSexo.toUpperCase() == 'M') {
	  $('z01_v_sexo').options[0].selected = true;
  } else {
	  $('z01_v_sexo').options[1].selected = true;
  }
  $('z01_d_nasc').value        = dNasc;
  $('z01_d_nasc_dia').value    = aNasc[2];
  $('z01_d_nasc_mes').value    = aNasc[1];
  $('z01_d_nasc_ano').value    = aNasc[0];
  $('z01_v_cgccpf').value      = oRetorno.z01_v_cgccpf.urlDecode();
  $('z01_v_ident').value       = oRetorno.z01_v_ident.urlDecode();
  $('z01_v_mae').value         = oRetorno.z01_v_mae.urlDecode();
  $('z01_v_pai').value         = oRetorno.z01_v_pai.urlDecode();
  $('s115_c_cartaosus').value  = oRetorno.s115_c_cartaosus.urlDecode();
  $('s115_c_cartaosus2').value = oRetorno.s115_c_cartaosus.urlDecode();
  $('s115_i_codigo').value     = oRetorno.s115_i_codigo.urlDecode();

  if (oRetorno.z01_i_cgsund != '') {
    $('novo').disabled = false;
  }

  if (oRetorno.s115_c_tipo == 'D') {
    $('s115_c_tipo').options[0].selected = true;
  } else {
    $('s115_c_tipo').options[1].selected = true;
  }

  js_getPedidosTfdCgs();

}

function js_limpaInfoCgs() {

  $('z01_i_cgsund2').value             = '';
  $('z01_i_numero').value              = '';
  $('z01_v_compl').value               = '';
  $('z01_v_ender').value               = '';
  $('z01_v_bairro').value              = '';
  $('z01_v_munic').value               = '';
  $('z01_v_cep').value                 = '';
  $('z01_v_uf').value                  = '';
  $('z01_v_email').value               = '';
  $('z01_v_telef').value               = '';
  $('z01_v_telcel').value              = '';
  $('z01_d_nasc').value                = '';
  $('z01_d_nasc_dia').value            = '';
  $('z01_d_nasc_mes').value            = '';
  $('z01_d_nasc_ano').value            = '';
  $('z01_v_sexo').options[0].selected  = true;
  $('z01_v_cgccpf').value              = '';
  $('z01_v_ident').value               = '';
  $('z01_v_mae').value                 = '';
  $('z01_v_pai').value                 = '';
  $('s115_c_cartaosus2').value         = '';
  $('s115_c_cartaosus').value          = '';
  $('s115_i_codigo').value             = '';
  $('s115_c_tipo').options[0].selected = true;
  $('atualizar').disabled              = true;
  $('novo').disabled                   = true;
  $('mudanca').value                   = '';
  $('tf29_i_prontuario').value         = '';
  $('tf30_i_encaminhamento').value     = '';
  $('tf01_i_cgsund').value             = '';
  $('z01_v_nome').value                = '';

  // Limpa o gride também
  oDBGridPedidostfd.clearAll(true);

}

function js_atualizarCgs() {

  oParam = new Object();

  oParam.exec             = 'atualizarCgs';
  oParam.iCgs             = $F('z01_i_cgsund2');
  oParam.z01_v_ender      = $F('z01_v_ender');
  oParam.z01_v_bairro     = $F('z01_v_bairro');
  oParam.z01_v_munic      = $F('z01_v_munic');
  oParam.z01_v_compl      = $F('z01_v_compl');
  oParam.z01_i_numero     = $F('z01_i_numero');
  oParam.z01_v_cep        = $F('z01_v_cep');
  oParam.z01_v_uf         = $F('z01_v_uf');
  oParam.z01_v_email      = $F('z01_v_email');
  oParam.z01_v_telef      = $F('z01_v_telef');
  oParam.z01_v_sexo       = $F('z01_v_sexo').substring(0, 1);
  oParam.z01_v_telcel     = $F('z01_v_telcel');
  oParam.z01_d_nasc       = $F('z01_d_nasc');
  oParam.z01_v_cgccpf     = $F('z01_v_cgccpf');
  oParam.z01_v_ident      = $F('z01_v_ident');
  oParam.z01_v_mae        = $F('z01_v_mae');
  oParam.z01_v_pai        = $F('z01_v_pai');
  oParam.s115_c_cartaosus = $F('s115_c_cartaosus');
  oParam.s115_c_tipo      = $F('s115_c_tipo');
  oParam.s115_i_codigo    = $F('s115_i_codigo');

  js_ajax(oParam, 'js_retornoatualizarCgs');

}

function js_retornoatualizarCgs(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {
    message_ajax(oRetorno.sMessage.urlDecode());
  } else {

    alert('Informações do CGS atualizadas com sucesso.');
    $('atualizar').disabled = true;
    $('mudanca').value      = false;

  }

}

function js_change() {

  if ($F('z01_i_cgsund2') != '') {

    $('mudanca').value = true;
    if (lPermissaoCgs) {
      $('atualizar').disabled = false;
    }

  }

}

/* Bloco de funções dados do CGS fim ****/
</script>