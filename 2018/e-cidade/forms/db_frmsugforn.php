<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clpcsugforn->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");

if(isset($db_opcaoal)){
   $db_opcao=33;
   $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    $db_botao=true;
}else{
    $db_opcao = 1;
    $db_botao=true;
    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
     $pc40_numcgm = "";
     $z01_nome = "";
   }
}
?>
<form name="form1" method="post" action="" >
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tpc40_solic?>">
      <?=@$Lpc40_solic?>
    </td>
    <td>
			<?
			db_input('pc40_solic',8,$Ipc40_solic,true,'text',3)
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc40_numcgm?>">
       <?
       db_ancora(@$Lpc40_numcgm,"js_pesquisapc40_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td>
			<?
			db_input('pc40_numcgm',8,$Ipc40_numcgm,true,'text',$db_opcao," onchange='js_pesquisapc40_numcgm(false);'")
			?>
			<?
			db_input('z01_nome',40,$Iz01_nome,true,'text',3);
			?>
    </td>
  </tr>
  </tr>
    <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
        type="submit" id="db_opcao"
        value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
        <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
 <table width="90%">
  <tr>
    <td valign="top"  align="center">
    <?
		  $chavepri= array("pc40_solic" => @$pc40_solic, "pc40_numcgm" => @$pc40_numcgm);
		  $cliframe_alterar_excluir->chavepri      = $chavepri;
		  $cliframe_alterar_excluir->sql           = $clpcsugforn->sql_query(@$pc40_solic, null, "distinct pc40_solic,pc40_numcgm,z01_nome");
		  $cliframe_alterar_excluir->campos        = "pc40_solic, pc40_numcgm, z01_nome";
		  $cliframe_alterar_excluir->legenda       = "ITENS LANÇADAS";
		  $cliframe_alterar_excluir->iframe_height = "160";
		  $cliframe_alterar_excluir->iframe_width  = "100%";
		  $cliframe_alterar_excluir->opcoes        = "3";
		  $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
  </center>
</form>
<script>

/**
 * definido desabilitado de arrancada para o botao pois estava acontecendo do usuario
 * entrar com um cgm e clicar direto no botao sem antes ele realizar a busca e validação do mesmo
 */
<?php if ( $db_opcao != 33 && $db_opcao != 3 ) : ?>
  $('db_opcao').disabled = true;
<?php endif; ?>

$('pc40_numcgm').observe('input', function(){
  $('db_opcao').disabled = true;
} );

function js_cancelar() {

  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}

function js_pesquisapc40_numcgm(mostra) {

  if (mostra == true) {

    var sUrl = 'func_nome.php?testanome=false&funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome';
    js_OpenJanelaIframe('top.corpo.iframe_sugforn', 'func_nome', sUrl, 'Pesquisa', true, 0);
  } else {

     var sUrl = 'func_nome.php?pesquisa_chave='+$('pc40_numcgm').value+'&funcao_js=parent.js_mostracgm';
     if ($('pc40_numcgm').value != '') {
        js_OpenJanelaIframe('top.corpo.iframe_sugforn', 'func_nome',  sUrl, 'Pesquisa', false);
     } else {
       $('z01_nome').value = '';
     }
  }
}

function js_mostracgm(erro,chave) {

  $('z01_nome').value = chave;
  if (erro == true) {

    $('pc40_numcgm').value = '';
    $('pc40_numcgm').focus();
  } else {
    js_debitosemaberto();
  }
}

function js_mostracgm1(chave1, chave2) {

  $('pc40_numcgm').value = chave1;
  $('z01_nome').value    = chave2;
  func_nome.hide();
  js_debitosemaberto();
}

/**
 * Procura se o fornecedor possui débitos em aberto
 */
function js_debitosemaberto() {

  var sUrlRPC            = 'com4_notificafornecedor.RPC.php';
  var iCgm               = $('pc40_numcgm').value;
  $('db_opcao').disabled = true;

  js_divCarregando('Aguarde, verificando débitos em aberto...',"msgBoxDebitosEmAberto");

  var oParam        = new Object();
  oParam.sExecucao  = 'debitosEmAberto';
  oParam.iNumCgm    = iCgm;
  oParam.sLiberacao = "S";

  var oAjax        = new Ajax.Request (sUrlRPC,
                                       {
                                          method: 'post',
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_retornodebitosemaberto
                                       });
}

/**
 * Retorno com os débitos em aberto e informações de configuração
 */
function js_retornodebitosemaberto(oAjax) {

  js_removeObj("msgBoxDebitosEmAberto");

  var oRetorno                = eval("("+oAjax.responseText+")");
  var iNumCgm                 = new Number(oRetorno.iNumCgm);
  var iParamFornecDeb         = new Number(oRetorno.iParamFornecDeb);
  var iDebitosEmAberto        = new Number(oRetorno.iDebitosEmAberto);
  var lParamGerarNotifDebitos = oRetorno.lParamGerarNotifDebitos;

  if (iParamFornecDeb == 1) {
    $('db_opcao').disabled = false;
    lEnvia = true;
  } else if (iParamFornecDeb == 2) {

    if (iDebitosEmAberto > 0) {

	    var sMensagem  = 'O fornecedor '+ iNumCgm +' possui débitos em aberto.';
	        sMensagem += '\n Deseja Notifica-lo?';
	    if (confirm(sMensagem)) {
	      js_NotificacaoDebitos(iNumCgm, iParamFornecDeb, oRetorno.aFormaNotificacao, lParamGerarNotifDebitos, true);
	    } else {
	      js_NotificacaoDebitos(iNumCgm, iParamFornecDeb, oRetorno.aFormaNotificacao, lParamGerarNotifDebitos, false);
	    }
    }

    $('db_opcao').disabled   = false;


  } else if (iParamFornecDeb == 3) {

    if (iDebitosEmAberto > 0) {

      alert('O fornecedor '+ iNumCgm +' possui débitos em aberto.');
      js_NotificacaoDebitos(iNumCgm, iParamFornecDeb, oRetorno.aFormaNotificacao, lParamGerarNotifDebitos, true);
      $('db_opcao').disabled = true;

    } else {
    	$('db_opcao').disabled = false;
    	lEnvia = true;
    }
  }
}

/**
 * Executa a notificação de débitos ao fornecedor
 */
function js_NotificacaoDebitos(iNumCgm, iParamFornecDeb, aFormaNotificacao, lGerarNotificacaoDebito, lMostrarJanela) {

    var iOrigem       = 1;
    var iCodigoOrigem = $('pc40_solic').value;

    oNotificarDebitos = new dbViewNotificaFornecedor(iNumCgm, iOrigem);
    oNotificarDebitos.setCodigoOrigem(iCodigoOrigem);
    oNotificarDebitos.setGerarNotificacaoDebito(lGerarNotificacaoDebito);
    if (lMostrarJanela) {

	    oNotificarDebitos.setFormaNotificacao(aFormaNotificacao, true);
	    if (aFormaNotificacao.length > 0) {
	      oNotificarDebitos.show();
	    } else {
	      oNotificarDebitos.setFormaNotificacao(aFormaNotificacao, false);
	    }
    } else {

      oNotificarDebitos.setGerarNotificacaoDebito(false);
      oNotificarDebitos.setFormaNotificacao(0, false);
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

      $('db_opcao').disabled = false;
      if (iParamFornecDeb == 3) {
        $('pc40_numcgm').value = '';
        $('z01_nome').value    = '';
      }
    });
}

function js_emitircartanotificacao(iCodigoNotificaBloqueioFornecedor) {

  var jan = window.open('com2_emitircartanotificacao002.php?iCodigoNotificaBloqueioFornecedor='+iCodigoNotificaBloqueioFornecedor,
                        '',
                        'width='+(screen.availWidth-5)+
                        ',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
}
</script>