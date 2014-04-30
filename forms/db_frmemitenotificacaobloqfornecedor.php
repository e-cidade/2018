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

//MODULO: Compras
$clrotulo = new rotulocampo;
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
      <fieldset>
        <legend><b>Notificação</b></legend>
        <table border="0">
				  <tr>
				    <td title="<?=@$Tz01_numcgm?>">
				       <?
				         db_ancora('<b>CGM:</b>', "js_pesquisa_z01_numcgm(true);", 1);
				       ?>
				    </td>
				    <td> 
				      <?
				        db_input('z01_numcgm', 10, @$Iz01_numcgm,true, 'text', 1, " onchange='js_pesquisa_z01_numcgm(false);'");
				      ?>
				    </td>
				    <td colspan="2">
				      <?
				        db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, "");
				      ?>
				    </td>
				  </tr>
				  <tr>
				    <td align="left" title="Data Inicial">
				      <b>Data Inicial</b>
				    </td>
				    <td align="left">
				      <?
				        db_inputdata('datainicial', @$datainicial_dia, @$datainicial_mes, @$datainicial_ano, true, 'text', 1, "");
				      ?>
				    </td>
				    <td align="right" title="Data Final" width="120px">
				      <b>Data Final</b>
				    </td>
				    <td align="right">
				      <?
				        db_inputdata('datafinal', @$datafinal_dia, @$datafinal_mes, @$datafinal_ano, true, 'text', 1, "");
				      ?>
				    </td>
				  </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
    </td>
  </tr>
</table>
</form>
<script>
function js_pesquisa_z01_numcgm(mostra) {

  if (mostra == true) {
    
    var sUrl = 'func_nome.php?testanome=false&funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome';
    js_OpenJanelaIframe('top.corpo', 'func_nome', sUrl, 'Pesquisa', true);
  } else {
  
     var sUrl = 'func_nome.php?pesquisa_chave='+$('z01_numcgm').value+'&funcao_js=parent.js_mostracgm';
     if ($('z01_numcgm').value != '') {
        js_OpenJanelaIframe('top.corpo', 'func_nome',  sUrl, 'Pesquisa', false);
     } else {
       $('z01_nome').value = '';
     }
  }
}

function js_mostracgm(erro,chave) {

  $('z01_nome').value = chave;
  if (erro == true) {
  
    $('z01_numcgm').value = '';
    $('z01_numcgm').focus();
  }
}

function js_mostracgm1(chave1, chave2) {

  $('z01_numcgm').value = chave1;
  $('z01_nome').value    = chave2;
  func_nome.hide();
}

function js_pesquisa() {
  
  if ($('z01_numcgm').value == '') {
  
    alert('Informe o CGM do fronecedor!');
    return false;
  }

  var sUrl = 'func_notificacaonotificafornecedor.php?notificacao=true'+
             '&z01_numcgm='+$('z01_numcgm').value+
             '&datainicial='+$('datainicial').value+
             '&datafinal='+$('datafinal').value+
             '&funcao_js=parent.js_notificacao|pc87_notificabloqueiofornecedor|pc86_numcgm|pc86_origem';
  js_OpenJanelaIframe('top.corpo', 'db_iframe_pesquisanotificacao', sUrl, 'Pesquisa', true);
}

/**
 * Executa a notificação de débitos ao fornecedor
 */
function js_notificacao(chave1, chave2, chave3) {

  var sUrlRPC                     = 'com4_notificafornecedor.RPC.php';
  var iNotificaBloqueioFornecedor = new Number(chave1);
  var iCgm                        = new Number(chave2);
  var iOrigem                     = new Number(chave3);
    
  js_divCarregando('Aguarde, processando...', "msgBoxDebitosEmAberto");
    
  var oParam       = new Object();
  oParam.sExecucao = 'debitosEmAberto';
  oParam.iNumCgm   = iCgm;
    
  var oAjax        = new Ajax.Request (sUrlRPC,
                                       {
                                          method: 'post',  
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: function (oAjax) {
                                            js_retornoNotificacao(oAjax, iNotificaBloqueioFornecedor, iOrigem);
                                          }  
                                       });
}

function js_retornoNotificacao(oAjax, iNotificaBloqueioFornecedor, iOrigem) {

  js_removeObj("msgBoxDebitosEmAberto");
                                               
  var oRetorno = eval("("+oAjax.responseText+")");
  var iNumCgm  = new Number(oRetorno.iNumCgm);
  
  if (oRetorno.iParamFornecDeb != 3) {
  
    oNotificarDebitos = new dbViewNotificaFornecedor(iNumCgm, iOrigem, iNotificaBloqueioFornecedor);
    oNotificarDebitos.setFormaNotificacao(oRetorno.aFormaNotificacao, true);
    oNotificarDebitos.setGerarNotificacaoDebito(false);
    if (oRetorno.aFormaNotificacao.length > 0) {
      oNotificarDebitos.show();
    } else {
      oNotificarDebitos.setFormaNotificacao(oRetorno.aFormaNotificacao, false);
    }
  
    /**
     * Retorno do processo de notificação de debitos
     */
	  oNotificarDebitos.setCallBack(function (oRetorno) {
	    
	    if (oRetorno.lFormaNotifEmail) {
	      alert(oRetorno.sMessage.urlDecode());
	    }
	    
	    if (oRetorno.lFormaNotifCarta) {
	      js_emitircartanotificacao(oRetorno.iCodigoNotificaBloqueioFornecedor);
	    }
	    
	    db_iframe_pesquisanotificacao.hide();
	  });
  }
}

function js_emitircartanotificacao(iCodigoNotificaBloqueioFornecedor) {

  var jan = window.open('com2_emitircartanotificacao002.php?iCodigoNotificaBloqueioFornecedor='+iCodigoNotificaBloqueioFornecedor,
                        '',
                        'width='+(screen.availWidth-5)+
                        ',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
}
</script>