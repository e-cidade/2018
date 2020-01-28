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
$oDaotfd_ajudacustopedido->rotulo->label();
$oDaotfd_beneficiadosajudacusto->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_i_numcgs");
$clrotulo->label("tf01_i_codigo");
$clrotulo->label("tf01_i_cgsund");
$clrotulo->label("z01_v_nome");
$clrotulo->label("tf12_descricao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr style="display: none;">
    <td nowrap title="<?=@$Ttf14_i_codigo?>">
      <?=@$Ltf14_i_codigo?>
    </td>
    <td>
      <?
      db_input('tf14_i_codigo',10,$Itf14_i_codigo,true,'text',3,"");
      db_input('tf15_i_codigo',10,$Itf15_i_codigo,true,'text',3,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf14_i_pedidotfd?>">
      <?=@$Ltf14_i_pedidotfd?>
    </td>
     <td>
      <?
      db_input('tf14_i_pedidotfd',10,$Itf14_i_pedidotfd,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf01_i_cgsund?>">
      <?
      echo '<b>Paciente:</b>';
      ?>
    </td>
    <td nowrap>
      <?
      db_input('tf01_i_cgsund',10,$Itf01_i_cgsund,true,'text',3,'');
      db_input('z01_v_nome',50,$Iz01_v_nome,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <fieldset style='width: 96%;'> <legend><b>Retirado por:</b></legend>
        <table border="0" width="90%">
          <tr>
            <td nowrap title="<?=@$Ttf14_i_cgsretirou?>">
              <?
              if(isset($tf14_i_cgsretirou)) {
                $db_opcao2 = 3;
              } else {
                $db_opcao2 = 1;
              }
              db_ancora(@$Ltf14_i_cgsretirou,"js_pesquisatf14_i_cgsretirou(true);",$db_opcao2);
              ?>
            </td>
            <td nowrap>
              <?
              db_input('tf14_i_cgsretirou',10,$Itf14_i_cgsretirou,true,'text',$db_opcao2," onchange='js_pesquisatf14_i_cgsretirou(false);'");
              db_input('z01_v_nome2',50,$Iz01_v_nome,true,'text',3,'');
              ?>
            </td>
            <td nowrap>
              <input type="button" name="novocgs" id="novocgs" value="Novo CGS" onclick="js_novoCgs();">
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <fieldset style='width: 96%;'> <legend><b>Ajuda de Custo Para:</b></legend>
        <table border="0" width="90%">
          <tr>
            <td nowrap title="<?=@$Ttf15_i_cgsund?>">
              <?
              db_ancora(@$Ltf15_i_cgsund,"js_pesquisatf15_i_cgsund(true);",$db_opcao);
              ?>
            </td>
            <td nowrap>
              <?
              db_input('tf15_i_cgsund',10,$Itf15_i_cgsund,true,'text',$db_opcao," onchange='js_pesquisatf15_i_cgsund(false);'");
              db_input('z01_v_nome3',50,$Iz01_v_nome,true,'text',3,'');
              db_input('tipo', 10,'', true, 'hidden', 3);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ttf15_i_ajudacusto?>">
              <?
              db_ancora(@$Ltf15_i_ajudacusto,"js_pesquisatf15_i_ajudacusto(true);",$db_opcao);
              ?>
            </td>
            <td nowrap>
              <?
              db_input('tf15_i_ajudacusto',10,$Itf15_i_ajudacusto,true,'text',$db_opcao," onchange='js_pesquisatf15_i_ajudacusto(false);'");
              db_input('tf12_descricao', 50, @$Itf12_descricao, true, 'text', 3, '');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ttf15_observacao?>">
              <?=@$Ltf15_observacao?>
            </td>
            <td nowrap>
              <?
              db_input('tf15_observacao', 62, @$Itf15_observacao, true, 'text', $db_opcao, '');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ttf14_i_cgsretirou?>">
              <?=$Ltf15_f_valoremitido?>
            </td>
            <td nowrap>
              <?
              db_input('tf15_f_valoremitido', 10, $Itf15_f_valoremitido, true, 'text', 3, '');
              db_input('tf12_f_valor', 10, '', true, 'hidden', 3, '');
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="right">
              <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
                type="submit" id="db_opcao"
                value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
                <?=($db_botao==false?"disabled":"")?> onclick="return js_validaEnvio();">
              <input name="cancelar" type="button" id="cancelar"
                value="Cancelar" onclick="js_cancelar();" <?=(!isset($opcao)?"disabled":"")?> >
              <input name="recibo" type="button" id="recibo"
                value="Emitir Recibo" onclick="js_emitirRecibo();" <?=($lBotaoRecibo ? '' : 'disabled')?> >
              <input name="fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_ajuda.hide();">
            </td>
          </tr>
        </table>

        <table width="100%">
	        <tr>
		        <td valign="top"><br>
              <?
				      $aChavepri = array ('tf14_i_codigo' => @$tf14_i_codigo,
                                  'tf14_i_pedidotfd' => @$tf14_i_pedidotfd,
                                  'tf14_i_cgsretirou' => @$tf14_i_cgsretirou,
                                  'z01_v_nome2' => @$z01_v_nome2,
                                  'z01_v_nome3' => @$z01_v_nome3,
                                  'tf14_d_datarecebimento' => @$tf14_d_datarecebimento,
                                  'tf15_i_codigo' => @$tf15_i_codigo,
                                  'tf15_i_ajudacusto' => @$tf15_i_ajudacusto,
                                  'tf15_i_cgsund' => @$tf15_i_cgsund,
                                  'tf15_f_valoremitido' => @$tf15_f_valoremitido,
				                          'tf15_observacao' => @$tf15_observacao,
                                  'tf12_f_valor' => @$tf12_f_valor,
				                          'tf12_descricao' => @$tf12_descricao,
                                  'tf01_i_cgsund' => @$tf01_i_cgsund);
              $oIframeAE->chavepri = $aChavepri;

              $sCampos =
                " tf14_i_codigo,
                  tf14_i_pedidotfd,
                  tf14_i_cgsretirou,
                  tf14_d_datarecebimento,
                  tf15_i_codigo,
                  tf15_i_ajudacusto,
                  tf15_i_cgsund,
                  tf15_f_valoremitido,
                  tf15_f_valoremitido as tf12_f_valor,
                  tf12_descricao,
                  a.z01_v_nome as z01_v_nome2,
                  cgs_und.z01_v_nome,
                  cgs_und.z01_v_nome as z01_v_nome3,
                  tf01_i_cgsund, tf15_observacao ";

	            $oIframeAE->sql = $oDaotfd_beneficiadosajudacusto->sql_query2(null, $sCampos, ' tf15_i_codigo desc ',
                                                                           " tf14_i_pedidotfd = $tf14_i_pedidotfd ");
				      $oIframeAE->campos = 'tf15_i_cgsund, z01_v_nome, tf15_i_ajudacusto, tf15_f_valoremitido';
				      $oIframeAE->legenda = 'Pessoas Beneficiadas:';
				      $oIframeAE->alignlegenda = 'left';
   			      $oIframeAE->msg_vazio = "Não foi encontrado nenhum registro.";
				      $oIframeAE->textocabec = "#DEB887";
				      $oIframeAE->textocorpo = "#444444";
			        $oIframeAE->fundocabec = "#444444";
			        $oIframeAE->fundocorpo = "#eaeaea";
			        $oIframeAE->iframe_height = "100";
			        $oIframeAE->iframe_width = "100%";
			        $oIframeAE->tamfontecabec = 9;
			        $oIframeAE->tamfontecorpo = 9;
			        $oIframeAE->formulario = false;
			        $oIframeAE->iframe_alterar_excluir($db_opcao);
			        ?>
            </td>
          </tr>
        </table>

      </fieldset>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf14_d_datarecebimento?>">
      <?=@$Ltf14_d_datarecebimento?>
    </td>
    <td>
      <?
      if(!isset($tf14_d_datarecebimento) || empty($tf14_d_datarecebimento)) {

        $tf14_d_datarecebimento_dia = date('d', db_getsession('DB_datausu'));
        $tf14_d_datarecebimento_mes = date('m', db_getsession('DB_datausu'));
        $tf14_d_datarecebimento_ano = date('Y', db_getsession('DB_datausu'));

      } else {

        $dTmp = explode('/', $tf14_d_datarecebimento);
        if(count($dTmp) == 3) {

          $tf14_d_datarecebimento_dia = $dTmp[0];
          $tf14_d_datarecebimento_mes = $dTmp[1];
          $tf14_d_datarecebimento_ano = $dTmp[2];

        }

      }
      db_inputdata('tf14_d_datarecebimento',@$tf14_d_datarecebimento_dia,@$tf14_d_datarecebimento_mes,@$tf14_d_datarecebimento_ano,true,'text',3,"")
      ?>
    </td>
  </tr>
  </table>
  </center>
</form>
<script>

function js_cancelar() {

  <?
  echo ' location.href = "'.basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]).'?tf14_i_pedidotfd='.
                          $tf14_i_pedidotfd.'&tf01_i_cgsund="'.
                          '+document.getElementById(\'tf01_i_cgsund\').value+"&z01_v_nome='.$z01_v_nome.'";'
  ?>

}

function js_validaEnvio() {

  oF = document.form1;

  if(oF.tf14_i_pedidotfd.value == '') {

    alert('Nenhum pedido de TFD informado.');
    return false;

  }

  if(oF.tf14_i_cgsretirou.value == '') {

    alert('Informe quem efetuou a retirada da ajuda de custo.');
    return false;

  }

  if(oF.tf15_i_cgsund.value == '') {

    alert('Informe um beneficiado pela ajuda de custo.');
    return false;

  }

  if(oF.tf15_i_ajudacusto.value == '') {

    alert('Informe uma ajuda de custo.');
    return false;

  }

  if(oF.tf15_f_valoremitido.value == '') {

    alert('Informe o valor emitido para esta ajuda de custo.');
    return false;

  }

  if(isNaN(parseFloat(oF.tf15_f_valoremitido.value))) {

    alert('Digite um valor válido para o valor emitido.');
    return false;

  }

  if(parseFloat(oF.tf15_f_valoremitido.value) < parseFloat(oF.tf12_f_valor.value)) {

   alert('O valor a ser emitido é menor que o da ajuda de custo.');
    return true;

  }

  return true;

}

function js_emitirRecibo() {

  sAjudacustoPedido = 'tf14_i_pedidotfd='+document.getElementById('tf14_i_pedidotfd').value;
  oJan = window.open('tfd2_reciboajudacusto002.php?'+sAjudacustoPedido,'','width='+(screen.availWidth-5)+
                     ',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  oJan.moveTo(0,0);

}

function js_pesquisatf15_i_ajudacusto(mostra) {

  if ($F('tf15_i_cgsund')== '') {

    $('tf15_i_ajudacusto').value = '';
    alert('Antes de selecionar uma ajuda, selecine um CGS.');
    return false;
  }

  var lAcompanhante = $F('tipo') == 2; // 1 = Paciente,  2 = Acompanhante

  var sUrl = "func_tfd_ajudacusto.php?";
  
  if(mostra) {

    sUrl += "funcao_js=parent.js_mostraajuda|tf12_i_codigo|tf12_descricao|tf12_f_valor";
    sUrl += "&chave_validade=true&lTrazAjudaAutomatico=false&lAcompanhante="+ lAcompanhante;
    js_OpenJanelaIframe('','db_iframe_tfd_ajudacusto', sUrl, 'Pesquisa Ajudas de Custo',true);

  } else {

    if(document.form1.tf15_i_ajudacusto.value != '') {

      sUrl += "chave_tf12_i_codigo="+$F('tf15_i_ajudacusto');
      sUrl += "&funcao_js=parent.js_mostraajuda|tf12_i_codigo|tf12_descricao|tf12_f_valor";
      sUrl += "&nao_mostra=true&chave_validade=true&lTrazAjudaAutomatico=false&lAcompanhante="+ lPaciente;
      
      js_OpenJanelaIframe('', 'db_iframe_tfd_ajudacusto', sUrl ,'Pesquisa Ajudas de Custo',false);

    } else {

      document.form1.tf12_descricao.value = '';
      document.form1.tf15_f_valoremitido.value = '';
      document.form1.tf12_f_valor.value = '';

    }

  }

}
function js_mostraajuda(chave1, chave2, chave3) {

  if(chave1 == '') {
    chave3 = '';
  }
  document.form1.tf15_i_ajudacusto.value = chave1;
  document.form1.tf12_descricao.value = chave2;
  document.form1.tf15_f_valoremitido.value = chave3;
  document.form1.tf12_f_valor.value = chave3;
  db_iframe_tfd_ajudacusto.hide();

}

function js_pesquisatf14_i_cgsretirou(mostra) {

  if(mostra==true) {
    js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?funcao_js=parent.js_mostracgs1|z01_i_cgsund|z01_v_nome','Pesquisa',true);
  } else {

    if(document.form1.tf14_i_cgsretirou.value != '') {
      js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?pesquisa_chave='+document.form1.tf14_i_cgsretirou.value+'&funcao_js=parent.js_mostracgs','Pesquisa',false);
    } else {

      document.form1.z01_v_nome2.value = '';

    }

  }

}
function js_mostracgs(chave,erro) {

  document.form1.z01_v_nome2.value = chave;
  if(erro==true) {

    document.form1.tf14_i_cgsretirou.focus();
    document.form1.tf14_i_cgsretirou.value = '';

  }

}
function js_mostracgs1(chave1,chave2) {

  document.form1.tf14_i_cgsretirou.value = chave1;
  document.form1.z01_v_nome2.value = chave2;
  db_iframe_cgs_und.hide();

}

function js_pesquisatf15_i_cgsund(mostra) {

  sChave = '&chave_tf01_i_codigo='+document.getElementById('tf14_i_pedidotfd').value;
  if(mostra==true) {
    js_OpenJanelaIframe('',
    	                  'db_iframe_cgs_und_beneficiadosajudacusto',
    	                  'func_cgs_und_beneficiadosajudacusto.php?funcao_js=parent.js_mostracgsbeneficiados1|z01_i_cgsund|z01_v_nome|tipo'+sChave,
    	                  'Pesquisa',
    	                  true,
    	                  10,
    	                  10,
    	                  screen.availWidth-100,
    	                  screen.availHeight-150);
  } else {

    if(document.form1.tf15_i_cgsund.value != '') {

      js_OpenJanelaIframe('',
    	                    'db_iframe_cgs_und_beneficiadosajudacusto',
    	                    'func_cgs_und_beneficiadosajudacusto.php?pesquisa_chave='+document.form1.tf15_i_cgsund.value+sChave+
                          '&funcao_js=parent.js_mostracgsbeneficiados',
                          'Pesquisa',
                          false);

    } else {

      document.form1.z01_v_nome3.value = '';
      $('tf12_descricao').value        = '';
      $('tf15_i_ajudacusto').value     = '';
    }

  }

}

/**
 * @param integer chave1 Codigo do paciente
 * @param boolean erro true 
 * @param integer tipo 1 = Paciente, 2 = Acompanhante 
 */
function js_mostracgsbeneficiados(chave, erro, tipo) {

  $('z01_v_nome3').value = chave;
  $('tipo').value        = tipo;
  
  if (erro) {

    document.form1.tf15_i_cgsund.focus();
    document.form1.tf15_i_cgsund.value = '';
    $('tf12_descricao').value          = '';
    $('tf15_i_ajudacusto').value       = '';
    return false;
  }
  js_pesquisatf15_i_ajudacusto(true);

}
/**
 * @param integer chave1 Codigo do paciente
 * @param string  chave2 Nome do paciente
 * @param integer tipo 1 = Paciente, 2 = Acompanhante 
 */
function js_mostracgsbeneficiados1(chave1,chave2, tipo) {

  $('tf15_i_cgsund').value = chave1;
  $('z01_v_nome3').value   = chave2;
  $('tipo').value          = tipo;
  db_iframe_cgs_und_beneficiadosajudacusto.hide();
  js_pesquisatf15_i_ajudacusto(true);
}

function js_novoCgs() {

	var sArquivo = 'sau1_cgs_und001.php?';
	var sParam   = 'funcao_js=parent.js_mostracgs1';
	sParam      += '&retornacgs=%27%27&retornanome=%27%27&redireciona=';
  js_OpenJanelaIframe('','db_iframe_cgs_und', sArquivo + sParam,'Cadastro CGS',true);

}
</script>