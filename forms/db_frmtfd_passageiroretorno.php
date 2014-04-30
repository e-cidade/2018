<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
$oDaoTfdPassageiroRetorno->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label('z01_v_nome');
$oRotulo->label('tf19_i_cgsund');
?>
<form name="form1" method="post" action=''>
<center>
<table border="0">
  <tr style="display: none;">
    <td nowrap title="<?=@$Ttf31_i_codigo?>">
      <?=@$Ltf31_i_codigo?>
    </td>
    <td> 
     <?
     db_input('tf31_i_codigo', 10, $Itf31_i_codigo, true, 'text', 3, '')
     ?>
    </td>
  </tr>
  <tr style="display: none;">
    <td nowrap title="<?=@$Ttf31_i_veiculodestino?>">
      <?
      db_ancora(@$Ltf31_i_veiculodestino, "js_pesquisatf31_i_veiculodestino(true);", $db_opcao);
      ?>
    </td>
    <td> 
      <?
      db_input('tf31_i_veiculodestino', 10, $Itf31_i_veiculodestino, true, 'text', 3, '');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf31_i_passageiroveiculo?>">
      <?
      db_ancora(@$Ltf31_i_passageiroveiculo, "js_pesquisatf31_i_passageiroveiculo(true);", $db_opcao);
      ?>
    </td>
    <td nowrap> 
      <?
      db_input('tf19_i_cgsund', 10, $Itf19_i_cgsund, true, 'text', $db_opcao, 
               " onchange='js_pesquisatf31_i_passageiroveiculo(false);'"
              );
      db_input('tf31_i_passageiroveiculo', 10, $Itf31_i_passageiroveiculo, true, 'hidden', 3, '');
      db_input('z01_v_nome', 50, $Iz01_v_nome, true, 'text', 3, '');
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <fieldset style='width: 90%; <?=($oParametros->tf11_i_utilizagradehorario == 2 ? 'display: none;' : '')?>'>
        <legend><b>Vagas para Retorno</b></legend>
        <table border="0" width="90%">
          <tr>
            <td nowrap>
              <b>Total: </b>
            </td>
            <td nowrap>
              <?
              if (!isset($total)) {
                $total = 0;
              }
              db_input('total', 2, "", true, 'text', 3, '');?>
            </td>
            <td nowrap>
              <b> - Reservado: </b>
            </td>
            <td nowrap >
              <?
              if (!isset($reservado)) {
                $reservado = 0;
              }
              db_input('reservado', 2, "", true, 'text', 3, '');
              ?>
            </td> 
            <td nowrap>
              <b> - Retorno: </b>
            </td>
            <td nowrap>
              <?
              if (!isset($retorno)) {
                $retorno = 0;
              }
              db_input('retorno', 2, "", true, 'text', 3, '');
              ?>
            </td>
            <td nowrap>
              <b> = Livre: </b>
            </td>
            <td nowrap>
              <?
              if (!isset($livre)) {
                $livre = 0;
              }
              db_input('livre', 2, "", true, 'text', 3, '');?>
            </td>
          </tr>                  
        </table>
      </fieldset>
    </td>
  </tr>
</table>
</center>
<br>
<input name="<?=($db_opcao == 1 ? 'incluir' : ($db_opcao == 2 || $db_opcao == 22 ? 'alterar' : 'excluir'))?>" 
  type="submit" id="db_opcao" 
  value="<?=($db_opcao == 1 ? 'Incluir' : ($db_opcao == 2 || $db_opcao == 22 ? 'Alterar' : 'Excluir'))?>" 
  <?=($db_botao == false ? 'disabled' : '')?> <?=($db_opcao == 1 ? 'onclick=js_validaEnvio();' : '')?> >
<input name="cancelar" type="button" id="cancelar" value="Limpar" onclick="js_cancelar();">
<input name="fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_retorno.hide();">
</form>


<table width="100%">
  <tr>
	  <td valign="top"><br>
      <?
			$aChavepri                = array ('tf31_i_codigo' => @$tf31_i_codigo,
                                         'tf31_i_passageiroveiculo' => @$tf31_i_passageiroveiculo, 
                                         'tf31_i_veiculodestino' => @$tf31_i_veiculodestino, 
                                         'tf19_i_cgsund' => @$tf19_i_cgsund, 
                                         'z01_v_nome' => @$z01_v_nome
                                        );
			$oIframeAE->chavepri      = $aChavepri;

      $sCampos                  = ' tf31_i_codigo, tf31_i_passageiroveiculo, tf31_i_veiculodestino,';
      $sCampos                 .= ' tf19_i_cgsund, z01_v_nome ';
      
			$oIframeAE->sql           = $oDaoTfdPassageiroRetorno->sql_query2(null, $sCampos, ' tf31_i_codigo desc ',
                                                                        'tf31_i_veiculodestino = '.
                                                                        $tf31_i_veiculodestino.
                                                                        'and tf31_i_valido = 1'
                                                                       );
			$oIframeAE->campos        = 'tf19_i_cgsund, z01_v_nome';
			$oIframeAE->legenda       = 'Registros';
 			$oIframeAE->msg_vazio     = 'Não foi encontrado nenhum registro.';
			$oIframeAE->textocabec    = '#DEB887';
			$oIframeAE->textocorpo    = '#444444';
		  $oIframeAE->fundocabec    = '#444444';
		  $oIframeAE->fundocorpo    = '#eaeaea';
		  $oIframeAE->iframe_height = '200';
		  $oIframeAE->iframe_width  = '100%';
		  $oIframeAE->tamfontecabec = 9;
		  $oIframeAE->tamfontecorpo = 9;
		  $oIframeAE->formulario    = false;
			$oIframeAE->opcoes        = 3;
		  $oIframeAE->iframe_alterar_excluir($db_opcao);
			?>
    </td>
	</tr>
</table>

<script>

sUrl = 'tfd4_pedidotfd.RPC.php';
<?
if ($oParametros->tf11_i_utilizagradehorario == 1) {
  echo 'js_getLotacaoRetorno();';
}
?>

function js_ajax(oParam, jsRetorno) {

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

function js_cancelar() {

  <?
  echo " location.href = '".basename($GLOBALS['HTTP_SERVER_VARS']['PHP_SELF']).
       "?tf31_i_veiculodestino=$tf31_i_veiculodestino'";
  ?>

}

function js_validaEnvio() {

  <?
  /* Se não utiliza grade de horários, não é feita validação de espaço disponível */
  if ($oParametros->tf11_i_utilizagradehorario != 1) {
    echo 'return true;';
  }
  ?>

  if (parseInt($F('livre'), 10) <= 0) {

    alert('Não há mais lugares para retorno.');
    return false;

  }

  return true;

}

function js_pesquisatf31_i_passageiroveiculo(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_tfd_passageiroveiculo', 'func_tfd_passageiroveiculo.php?'+
                        'funcao_js=parent.js_mostratfd_passageiroveiculo|tf19_i_codigo|z01_v_nome|tf19_i_cgsund'+
                        '&chave_validos=true&chave_fica=true&chave_valida_hora=true&chave_tf19_i_veiculodestino='+
                        document.form1.tf31_i_veiculodestino.value+'&chave_ja_incluidos=true', 
                        'Pesquisa', true
                       );

  } else {

    if (document.form1.tf19_i_cgsund.value != '') {

      js_OpenJanelaIframe('', 'db_iframe_tfd_passageiroveiculo', 'func_tfd_passageiroveiculo.php?'+
                          'chave_tf19_i_cgsund='+document.form1.tf19_i_cgsund.value+'&chave_valida_hora=true'+
                          '&funcao_js=parent.js_mostratfd_passageiroveiculo|tf19_i_codigo|z01_v_nome|tf19_i_cgsund'+
                          '&nao_mostra=true&chave_validos=true&chave_fica=true&chave_tf19_i_veiculodestino='+
                          document.form1.tf31_i_veiculodestino.value+'&chave_ja_incluidos=true', 
                          'Pesquisa', false
                         );

    } else {

      document.form1.tf31_i_passageiroveiculo.value = '';
      document.form1.z01_v_nome                     = '';

    }

  }

}
function js_mostratfd_passageiroveiculo(chave1, chave2, chave3) {

  if (chave1 == '') {
    chave3 = '';
  }
  document.form1.tf31_i_passageiroveiculo.value = chave1;
  document.form1.z01_v_nome.value               = chave2;
  document.form1.tf19_i_cgsund.value            = chave3;
  db_iframe_tfd_passageiroveiculo.hide();

}

function js_getLotacaoRetorno() {

  var oParam             = new Object();
  oParam.exec            = "getLotacaoRetorno";
  oParam.iVeiculoDestino = $F('tf31_i_veiculodestino');

  js_ajax(oParam, 'js_retornoGetLotacaoRetorno');

}

function js_retornoGetLotacaoRetorno(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");
  if (oRetorno.iStatus == 1) {
       
    document.form1.total.value     = oRetorno.iLotacao;
    document.form1.reservado.value = oRetorno.iReservados;
    document.form1.retorno.value   = oRetorno.iRetorno;
    document.form1.livre.value     = oRetorno.iLotacao - oRetorno.iReservados - oRetorno.iRetorno;

  }

}

</script>