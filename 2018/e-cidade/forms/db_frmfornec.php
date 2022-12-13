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

//MODULO: patrim
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clpcorcamforne->rotulo->label();
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
    if(isset($novo) || isset($verificado) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
     $pc21_orcamforne = "";
     $pc21_numcgm = "";
     $z01_nome = "";
   }
}
?>
<form name="form1" method="post" action="">
<?
  if(!isset($pc10_numero)){
    $pc10_numero = 0;
  }
  if(isset($solic)){
  	$solicitacao = $solic;
  }

  db_input('pc10_numero',10,0,false,'hidden',3);
  db_input('solicitacao',10,0,false,'hidden',3);
  db_input('pc80_codproc',10,0,false,'hidden',3);
?>
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tpc21_orcamforne?>">
       <?=@$Lpc21_orcamforne?>
    </td>
    <td>
<?
db_input('pc21_orcamforne',8,$Ipc21_orcamforne,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc21_codorc?>">
       <?=@$Lpc21_codorc?>
    </td>
    <td>
<?
db_input('pc21_codorc',8,$Ipc21_codorc,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc21_numcgm?>">
       <?
       db_ancora(@$Lpc21_numcgm,"js_pesquisapc21_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('pc21_numcgm',8,$Ipc21_numcgm,true,'text',$db_opcao," onchange='js_pesquisapc21_numcgm(false);'")
?>
<?
db_input('z01_nome',40,$Iz01_nome,true,'text',3);
?>
    </td>
  </tr>
  </tr>
    <td colspan="2" align="center">
     <?
      if(isset($pc21_codorc) && trim($pc21_codorc)!=""){
        echo "<input name='gerabranco'    type='submit' id='gerabranco'    value='Gerar em branco' onclick='js_gerarel(true);' ".($db_botao==false?"disabled":"").">&nbsp;";
      }
      $result_itens = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_file(null,"pc22_codorc","","pc22_codorc=".@$pc21_codorc));
      if($clpcorcamitem->numrows>0){
        $result_forne = $clpcorcamforne->sql_record($clpcorcamforne->sql_query_file(null,"pc21_codorc","","pc21_codorc=$pc21_codorc"));
        if($clpcorcamforne->numrows>0){
          echo "<input name='gera'    type='submit' id='gera'    value='Gerar relatório' onclick='js_gerarel(false);' ".($db_botao==false?"disabled":"").">&nbsp;";
          echo "<input name='lancval' type='button' id='lancval' value='Lançar valores'  onclick='top.corpo.document.location.href=\"com1_orcamlancval001.php?pc20_codorc=$pc21_codorc&sol=$solic\"' ".($db_botao==false?"disabled":"").">";
        }
      }
     ?>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
             type="submit" id="db_opcao"
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
             <?=($db_botao==false?"disabled":"")?>  >
      <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();"
             <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
</table>
<table width="90%">
  <tr>
    <td valign="top"  align="center">
     <?
			 $chavepri= array("pc21_orcamforne"=>@$pc21_orcamforne);
			 $cliframe_alterar_excluir->chavepri=$chavepri;
			 $cliframe_alterar_excluir->sql     = $clpcorcamforne->sql_query(null,"pc21_orcamforne,pc21_codorc,pc21_numcgm,z01_nome",""," pc21_codorc=$pc21_codorc");
			 $cliframe_alterar_excluir->campos  ="pc21_orcamforne,pc21_numcgm,z01_nome";
			 $cliframe_alterar_excluir->legenda="FORNECEDORES LANÇADOS";
			 $cliframe_alterar_excluir->iframe_height ="160";
			 $cliframe_alterar_excluir->iframe_width ="100%";
			 $cliframe_alterar_excluir->opcoes ="3";
		 	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
     ?>
    </td>
   </tr>
</table>
</center>
</form>
<script>

$('pc21_numcgm').observe('input', function() {

  $('db_opcao').disabled = true;

  if ($('gerabranco')) {
    $('gerabranco').disabled = true;
  }

  if ($('gera')) {
    $('gera').disabled = true;
  }

  if ($('lancval')) {
    $('lancval').disabled = true;
  }
})

function js_gerarel(embranco) {

  var solic       = <?=$solic?>;
  var pc20_codorc = $('pc21_codorc').value;
  if (embranco == true) {
    pc20_codorc += "&forne=branco";
  }

  if (solic == true) {

    var sUrl = 'com2_solorc002.php?pc20_codorc='+pc20_codorc;
    jan = window.open(sUrl, '', 'width='+(screen.availWidth-5)+
                                ',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  } else {

    var sUrl = 'com2_procorc002.php?pc20_codorc='+pc20_codorc+'&gera_branco='+true;
    jan = window.open(sUrl, '', 'width='+(screen.availWidth-5)+
                                ',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  }

  jan.moveTo(0,0);
}

function js_cancelar() {

  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}

function js_pesquisapc21_numcgm(mostra) {

  if (mostra == true) {

    var sUrl = 'func_nome.php?testanome=true&funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome';
    js_OpenJanelaIframe('top.corpo.iframe_fornec', 'func_nome', sUrl, 'Pesquisa', true, 0);
  } else {

    if ($('pc21_numcgm').value != '') {

      var sUrl = 'func_nome.php?pesquisa_chave='+$('pc21_numcgm').value+'&funcao_js=parent.js_mostracgm';
      js_OpenJanelaIframe('top.corpo.iframe_fornec', 'func_nome', sUrl, 'Pesquisa', false);
    } else {
      $('z01_nome').value = '';
    }
  }
}

function js_mostracgm(erro, chave) {

  $('z01_nome').value = chave;
  if (erro == true) {

    $('pc21_numcgm').focus();
    $('pc21_numcgm').value = '';
  } else {
    js_debitosemaberto();
  }
}

function js_mostracgm1(chave1, chave2) {

  $('pc21_numcgm').value = chave1;
  $('z01_nome').value    = chave2;
  func_nome.hide();

  js_debitosemaberto();
}

/**
 * Procura se o fornecedor possui débitos em aberto
 */
function js_debitosemaberto() {

  var sUrlRPC              = 'com4_notificafornecedor.RPC.php';
  var iCgm                 = $('pc21_numcgm').value;

  if ($('gerabranco')) {
    $('gerabranco').disabled = true;
  }

  if ($('gera')) {
    $('gera').disabled = true;
  }

  if ($('lancval')) {
    $('lancval').disabled = true;
  }

  $('db_opcao').disabled   = true;

  js_divCarregando('Aguarde, verificando débitos em aberto...',"msgBoxDebitosEmAberto");

  var oParam        = new Object();
  oParam.sExecucao  = 'debitosEmAberto';
  oParam.iNumCgm    = iCgm;
  oParam.sLiberacao = "P";

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

    if ($('gerabranco')) {
      $('gerabranco').disabled = false;
    }

    if ($('gera')) {
      $('gera').disabled = false;
    }

    if ($('lancval')) {
      $('lancval').disabled = false;
    }

    $('db_opcao').disabled   = false;
  } else if (iParamFornecDeb == 2) {

    if (iDebitosEmAberto > 0) {

      var sMensagem  = 'O fornecedor '+ iNumCgm +' possui débitos em aberto.';
          sMensagem += '\n Deseja Notifica-lo?';
      if (confirm(sMensagem)) {
        js_NotificacaoDebitos(iNumCgm, iParamFornecDeb, oRetorno.aFormaNotificacao, lParamGerarNotifDebitos, true);
      } else {
        js_NotificacaoDebitos(iNumCgm, iParamFornecDeb, oRetorno.aFormaNotificacao, lParamGerarNotifDebitos, false);
      }
    } else {

	    if ($('gerabranco')) {
	      $('gerabranco').disabled = false;
	    }

	    if ($('gera')) {
	      $('gera').disabled = false;
	    }

	    if ($('lancval')) {
	      $('lancval').disabled = false;
	    }

	    $('db_opcao').disabled   = false;
    }
  } else if (iParamFornecDeb == 3) {

    if (iDebitosEmAberto > 0) {

      alert('O fornecedor '+ iNumCgm +' possui débitos em aberto.');

      js_NotificacaoDebitos(iNumCgm, iParamFornecDeb, oRetorno.aFormaNotificacao, lParamGerarNotifDebitos, true);
    } else {
	    if ($('gerabranco')) {
		    $('gerabranco').disabled = false;
		  }

		  if ($('gera')) {
		    $('gera').disabled = false;
		  }

		  if ($('lancval')) {
		    $('lancval').disabled = false;
		  }

		  $('db_opcao').disabled   = false;
    }
  }
}

/**
 * Executa a notificação de débitos ao fornecedor
 */
function js_NotificacaoDebitos(iNumCgm, iParamFornecDeb, aFormaNotificacao, lGerarNotificacaoDebito, lMostrarJanela) {

    var iOrigem       = 2;
    var iCodigoOrigem = $('pc21_codorc').value;

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

      if ($('gerabranco')) {
        $('gerabranco').disabled = false;
      }

      if ($('gera')) {
        $('gera').disabled = false;
      }

      if ($('lancval')) {
        $('lancval').disabled = false;
      }

      $('db_opcao').disabled   = false;

      if (iParamFornecDeb == 3) {

        $('pc21_numcgm').value = '';
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