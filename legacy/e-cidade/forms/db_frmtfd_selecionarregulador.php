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
 
$oDaoTfdPedidoRegulado->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("sd03_i_codigo");
$oRotulo->label("sd02_i_codigo");
$oRotulo->label("sd27_i_codigo");
$oRotulo->label("la22_c_medico");
$oRotulo->label("tf01_i_codigo");
$oRotulo->label("z01_nome");
$oRotulo->label("rh70_estrutural");
$oRotulo->label("rh70_descr");
?>

<div class="container">
  <form name="form1" method="post" action="">
    <fieldset>
      <legend>Regulador</legend>
      <table>
        <tr>
          <td>
            <?php
              db_ancora("<b>Regulador:</b>", "js_pesquisa_medico(true); ","");
            ?>
          </td>
          <td>
            <?php
            if (isset($oPedidoRegulado) && $oPedidoRegulado != false) {

              $oDaoPedidoCadastrado = db_utils::fieldsmemory($oPedidoRegulado, 0);
              $sd03_i_codigo        = $oDaoPedidoCadastrado->sd03_i_codigo;
              $tf34_i_especmedico   = $oDaoPedidoCadastrado->tf34_i_especmedico;
              $rh70_estrutural      = $oDaoPedidoCadastrado->rh70_estrutural;
              $rh70_descr           = $oDaoPedidoCadastrado->rh70_descr;
              $sd02_i_codigo        = $oDaoPedidoCadastrado->sd02_i_codigo;
              $descrdepto           = $oDaoPedidoCadastrado->descrdepto;
            }

            db_input( 'sd03_i_codigo',  5, $Isd03_i_codigo, true, 'text',  "", "onchange='js_pesquisa_medico(false);'" );
            db_input( 'z01_nome',      40, $Iz01_nome,      true, 'text',   3 );
            db_input( 'tf01_i_codigo', 40, $Itf01_i_codigo, true, 'hidden', 3 );
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <?php
              db_ancora( "<b>Especialidade:</b>", "js_pesquisa_especmedico(true); ", "" );
            ?>
          </td>
          <td>
            <?php
            $sChange = " onchange='js_pesquisa_especmedico(false);' ";
            db_input( 'tf34_i_especmedico',  5, $Itf34_i_especmedico, true, 'hidden' );
            db_input( 'rh70_estrutural',     5, $Irh70_estrutural,    true, 'text', "", $sChange );
            db_input( 'rh70_descr',         40, $Irh70_descr,         true, 'text', 3 );
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <b>Unidade:</b>
          </td>
          <td>
            <?php
            db_input( 'sd02_i_codigo',  5, $Isd02_i_codigo, true, 'text', 3 );
            db_input( 'descrdepto',    40, $Isd02_i_codigo, true, 'text', 3 );
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name=<?=(@$sd03_i_codigo == "" ? "incluir" : "alterar")?>
           type="submit"
           id=<?=(@$sd03_i_codigo == "" ? "incluir" : "alterar")?>
           value=<?=(@$sd03_i_codigo == "" ? "Incluir" : "Alterar")?>
           onclick="js_validaEnvio();" >
    <input name="fechar" type="button" id="fechar" value="Fechar" onclick="js_fechar();" >
  </form>
</div>
<script>

if (document.form1.sd03_i_codigo.value != "") {
  js_pesquisa_medico(false);
}

function js_validaEnvio() {
	
  if ($('sd03_i_codigo').value == "") {

	  alert("Selecione um Regulador.");
    return false;
  }

  return true;
}

function js_pesquisa_medico(lMostra) {
  
  if (lMostra == true) {

    var sTemp  = 'func_medicos.php?funcao_js=parent.js_mostramedicos|sd03_i_codigo|z01_nome';
    js_OpenJanelaIframe('', 'db_iframe_medicos', sTemp, 'Pesquisa', true);
  } else {
	  
    if (document.form1.sd03_i_codigo.value != '') { 

      js_OpenJanelaIframe('',
                          'db_iframe_medicos',
                          'func_medicos.php?pesquisa_chave='+document.form1.sd03_i_codigo.value+
                          '&funcao_js=parent.js_mostramedicos1&lTodosTiposProf=true',
                          'Pesquisa',
                          false
                         );
    } else {
      js_limpar(); 
    }
  }
}

function js_mostramedicos(chave1, chave2) {

  document.form1.sd03_i_codigo.value = chave1;
  document.form1.z01_nome.value      = chave2;

  limpaCampos();

  db_iframe_medicos.hide();
}

function js_mostramedicos1(chave1, chave2) {

  document.form1.z01_nome.value = chave1;
  if (chave2 == 'true') {

    alert("Regulador não encontrado.");
    $('sd03_i_codigo').value = "";
    $('sd03_i_codigo').focus();
  }

  limpaCampos();
}

function js_pesquisa_especmedico(lMostra) {

  if ($('sd03_i_codigo').value == null || $('sd03_i_codigo').value == '') {

    alert('Médico não informado');
    return false;
  }

  sParam  = 'funcao_js=parent.js_mostraespecmedico|sd27_i_codigo|rh70_estrutural|rh70_descr|sd02_i_codigo|descrdepto';
  if (lMostra == false) {

    if ($('rh70_estrutural').value != null && $('rh70_estrutural').value != '') {

      sParam += '&nao_mostra=true';
      sParam += '&chave_rh70_estrutural='+$('rh70_estrutural').value;
    } else {

      $('tf34_i_especmedico').value = '';
      $('rh70_estrutural').value    = '';
      $('rh70_descr').value         = '';
      $('sd02_i_codigo').value      = '';
      $('descrdepto').value         = '';

      return false;
    }
  }

  sParam += '&chave_sd04_i_medico='+$('sd03_i_codigo').value
  sUrl    = 'func_especmedico.php?'+sParam;
  js_OpenJanelaIframe('', 'db_iframe_especmedico', sUrl, 'Pesquisa', lMostra, 0, 100, 1500, 700);
}

function js_mostraespecmedico(chave1, chave2, chave3, chave4, chave5) {

  if (chave1 == '') {

    $('tf11_especmedico').value = '';
    $('rh70_estrutural').value  = '';
    $('rh70_descr').value       = chave2;
    $('sd02_i_codigo').value    = '';
    $('descrdepto').value       = '';

    return false;
  }

  $('tf34_i_especmedico').value = chave1;
  $('rh70_estrutural').value    = chave2;
  $('rh70_descr').value         = chave3;
  $('sd02_i_codigo').value      = chave4;
  $('descrdepto').value         = chave5;
  db_iframe_especmedico.hide();
}

function js_limpar(){

  document.form1.sd03_i_codigo.value = "";
  document.form1.z01_nome.value      = "";
  document.form1.sd03_i_codigo.focus();
}

function js_limpar_combo(oCombo){

  for (var iInc = oCombo.length - 1; iInc >= 0; iInc--) {
	 oCombo.options[iInc] = null;
  }

  oCombo.selectedIndex = -1;
}

function js_ajax(oParam, jsRetorno) {

  var sUrl = 'tfd4_pedidotfd.RPC.php';
  var objAjax = new Ajax.Request(
                                 sUrl, 
                                 {
                                  method    : 'post',
                                  asynchronous: false,
                                  parameters: 'json='+Object.toJSON(oParam),
                                  onComplete: function(objAjax) {
                        				                         var evlJS = jsRetorno+'(objAjax);';
                                                                 return eval(evlJS);
                          			                            }
                                 }
                                );
}

function js_getEspecMed(){

  var oParam      = new Object();
  oParam.exec     = 'getEspecMedico';
  oParam.iMedico  = document.form1.sd03_i_codigo.value;
  js_ajax(oParam, 'js_retornoGetEspecMedico');
}

function js_retornoGetEspecMedico(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");
  if (oRetorno.iStatus != 1) {

    alert('Nenhuma especialidade encontrada.');
    js_limpar();
    return false;
  } else {

    oSel = document.form1.tf34_i_especmedico;

    for (var iCont = 0; iCont < oRetorno.aEspecialidades.length; iCont++) {

      oSel.options[oSel.options.length] =  new Option(oRetorno.aEspecialidades[iCont].sEspecialidade.urlDecode(),
                                                      oRetorno.aEspecialidades[iCont].iEspecMedico
                                                     );
    }

	  js_verificaProcedimento();
  }
}

function js_verificaProcedimento() {

  var oParam          = new Object();
  oParam.exec         = 'verificaProcedimentosEspecMedico';
  oParam.iEspecMedico = $F('tf34_i_especmedico');
  oParam.iPedido      = <?=@$tf01_i_codigo?>;
  oParam.sIdCkBox     = "";
  js_ajax(oParam, 'js_retornoVerificaProcedimentosEspecMedico');
}

function js_retornoVerificaProcedimentosEspecMedico(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");
  if (oRetorno.iStatus != 1) {
	 
    alert('Este pedido de TFD possui procedimentos que não são abrangidos pelas '+
	      'especialidades do regulador selecionado.'
	     );
	  js_limpar();
  }
}

function limpaCampos() {

  $('rh70_estrutural').value = '';
  $('rh70_descr').value      = '';
  $('sd02_i_codigo').value   = '';
  $('descrdepto').value      = '';
}
</script>