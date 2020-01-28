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
$cltfd_ajudacusto->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd63_c_nome");

?>

<form name="form1" id="form1" method="post" action="">
  <fieldset>
    <legend>Ajuda de Custo</legend>
    
    <fieldset style="border:none;">
      <table class='form-container'>
        <tr style="display: none">
          <td class="field-size2" nowrap="nowrap" title="<?=@$Ttf12_i_codigo?>">
            <?=@$Ltf12_i_codigo?>
          </td>
          <td nowrap="nowrap" colspan="3"> 
            <?
            db_input('tf12_i_codigo',10,$Itf12_i_codigo,true,'text',3,"")
            ?>
          </td>
        </tr>
        <tr>
          <td class="field-size2" nowrap="nowrap" title="<?=@$Ttf12_i_procedimento?>">
             <?
             db_ancora(@$Ltf12_i_procedimento,"js_pesquisatf12_i_procedimento(true);",$db_opcao);
             ?>
          </td>
          <td nowrap="nowrap" colspan="3"> 
            <?
            db_input('sd63_c_procedimento',10,'',true,'text',$db_opcao," onchange='js_pesquisatf12_i_procedimento(false);'");
            db_input('tf12_i_procedimento',10,'',true,'hidden',3,'');
            db_input('sd63_c_nome',50,$Isd63_c_nome,true,'text',3,'');
            ?>
          </td>
        </tr>
        <tr>
          <td class="field-size2" nowrap="nowrap" title="<?=@$Ttf12_descricao?>">
            <?=@$Ltf12_descricao?>
          </td>
          <td nowrap="nowrap" colspan="3" title="<?=@$Ttf12_descricao?>">
            <?
            db_input('tf12_descricao', 64, @$Itf12_descricao, true, 'text', $db_opcao, '');
            ?>
          </td>
        </tr>
        <tr>
          <td class="field-size2" nowrap="nowrap" title="<?=@$Ttf12_f_valor?>">
            <?=@$Ltf12_f_valor?>
          </td>
          <td nowrap="nowrap"  class="field-size6" colspan="3"> 
            <?
            db_input('tf12_f_valor',10,$Itf12_f_valor,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td class="field-size2" nowrap="nowrap" title="<?=@$Ttf12_acompanhente?>"><?=@$Ltf12_acompanhente?></td>
          <td nowrap="nowrap">
            <?
              $aX = array('f'=>'NÃO', 't'=>'SIM',);
              db_select('tf12_acompanhente', $aX, true, $db_opcao, '');
            ?>
          </td>
          <td nowrap="nowrap" title="<?=@$Ttf12_faturabpa?>" align="right"><?=@$Ltf12_faturabpa?></td>
          <td nowrap="nowrap" > 
            <?
            $aX = array('t'=>'SIM', 'f'=>'NÃO');
            db_select('tf12_faturabpa', $aX, true, $db_opcao, '');
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    
    <fieldset class='separator'>
      <legend>Período</legend>
      <table class='subtable' >
        <tr>
          <td class="field-size2" nowrap="nowrap" title="<?=@$Ttf12_d_validadeini?>">
            <?=@$Ltf12_d_validadeini?>
          </td>
          <td nowrap="nowrap" class="field-size7" > 
            <?
            if(isset($tf12_d_validadeini) && !empty($tf12_d_validadeini)) {
          
              $dTmp = explode('/', $tf12_d_validadeini);
              if(count($dTmp) == 3) {
                 
                $tf12_d_validadeini_dia = $dTmp[0];
                $tf12_d_validadeini_mes = $dTmp[1];
                $tf12_d_validadeini_ano = $dTmp[2];
          
              }
          
            }
            db_inputdata('tf12_d_validadeini', @$tf12_d_validadeini_dia, @$tf12_d_validadeini_mes,@$tf12_d_validadeini_ano,true,'text',$db_opcao,"");
            ?>
          </td>
          <td class="text-right"  nowrap="nowrap" title="<?=@$Ttf12_d_validadefim?>">
            <?=@$Ltf12_d_validadefim?>
          </td>
          <td nowrap="nowrap" > 
            <?
            if(isset($tf12_d_validadefim) && !empty($tf12_d_validadefim)) {
          
              $dTmp = explode('/', $tf12_d_validadefim);
              if(count($dTmp) == 3) {
                 
                $tf12_d_validadefim_dia = $dTmp[0];
                $tf12_d_validadefim_mes = $dTmp[1];
                $tf12_d_validadefim_ano = $dTmp[2];
              }
            }
            db_inputdata('tf12_d_validadefim',@$tf12_d_validadefim_dia,@$tf12_d_validadefim_mes,@$tf12_d_validadefim_ano,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao"
         value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
         <?=($db_botao==false?"disabled":"")?> <?=($db_opcao != 3 ? 'onclick="return js_validaEnvio();"' : '')?> />
  <input name="cancelar" type="button" id="cancelar" value="Cancelar" 
         onclick="js_cancelar();" <?=(!isset($opcao)?"disabled":"")?> />
</form>
<table width="100%">
  <tr>
	  <td valign="top"><br>
        <?
				$aChavepri = array ('tf12_i_codigo' => @$tf12_i_codigo,
 				                    'tf12_faturabpa' => @$tf12_faturabpa,
                            'tf12_acompanhente' => @$tf12_acompanhente,
 				                    'tf12_descricao' => @$tf12_descricao,
                            'tf12_i_procedimento' => @$tf12_i_procedimento, 
                            'sd63_c_nome' => @$sd63_c_nome, 
                            'sd63_c_procedimento' => @$sd63_c_procedimento, 
                            'tf12_f_valor' => @$tf12_f_valor, 
                            'tf12_d_validadeini' => @$tf12_d_validadeini, 
                            'tf12_d_validadefim' => @$tf12_d_validadefim);
				$oIframeAE->chavepri = $aChavepri;

        $sCampos = 
        " tf12_i_codigo,
          tf12_faturabpa,
          tf12_acompanhente,
          case when tf12_faturabpa is true then  'SIM' else 'NÃO' end as db_fatura  , 
          tf12_descricao,
          tf12_i_procedimento,
          tf12_d_validadeini,
          tf12_d_validadefim,
          sau_procedimento.sd63_c_nome as sd63_c_nome,
          sau_procedimento.sd63_c_procedimento as sd63_c_procedimento,
          round(tf12_f_valor, 2) as tf12_f_valor ";
        
				@$oIframeAE->sql = $cltfd_ajudacusto->sql_query_valor_unitario(null, $sCampos, 
                                                                       'tf12_i_codigo, sau_procedimento.sd63_c_procedimento, 
                                                                        sau_procedimento.sd63_i_anocomp desc, 
                                                                        sau_procedimento.sd63_i_mescomp desc',
                                                                        '');
				$oIframeAE->campos = "tf12_i_codigo, sd63_c_procedimento, tf12_descricao, tf12_f_valor, db_fatura ";
				$oIframeAE->legenda = "Registros";
   			$oIframeAE->msg_vazio = "Não foi encontrado nenhum registro.";
				$oIframeAE->textocabec = "#DEB887";
				$oIframeAE->textocorpo = "#444444";
			  $oIframeAE->fundocabec = "#444444";
			  $oIframeAE->fundocorpo = "#eaeaea";
			  $oIframeAE->iframe_height = "200";
			  $oIframeAE->iframe_width = "100%";
			  $oIframeAE->tamfontecabec = 9;
			  $oIframeAE->tamfontecorpo = 9;
			  $oIframeAE->formulario = false;
			  $oIframeAE->iframe_alterar_excluir($db_opcao);
				?>
    </td>
	</tr>
</table>
<script type="text/javascript">

function js_cancelar() {
  <?
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."'";
  ?>
}

function js_validaEnvio() {
  
  if($F('sd63_c_procedimento') == '' || $F('tf12_i_procedimento') == '') {

    alert('Escolha um procedimento.');
    return false;

  }
  var lDataValida = js_validaData();
  if ($F('tf12_descricao') == '' && lDataValida) {
	  $('tf12_descricao').value = $F('sd63_c_nome').substring(0, 49);
	}
	return lDataValida;
	
}

function js_validaData() {
 
  if($F('tf12_d_validadeini') != '') {
  
    if($F('tf12_d_validadefim') != '') {

      aIni = $F('tf12_d_validadeini').split('/');
      aFim = $F('tf12_d_validadefim').split('/');
      dIni = new Date(aIni[2], aIni[1], aIni[0]);
      dFim = new Date(aFim[2], aFim[1], aFim[0]);
  	  if(dFim < dIni) {
      
        alert("Data final menor que a data inicial");
			  $('tf12_d_validadefim').value = '';
			  $('tf12_d_validadefim_dia').value = '';
			  $('tf12_d_validadefim_mes').value = '';
			  $('tf12_d_validadefim_ano').value = '';
        $('tf12_d_validadefim').focus();
        return false;
      }
    }
  } else {

    alert('Preencha a data de início.');
    return false;
  }
  return true;
}

function js_pesquisatf12_i_procedimento(mostra) {

  var sUrl = "func_sau_procedimento.php?";
  if (mostra) {

    sUrl += "funcao_js=parent.js_mostrasau_procedimento1|sd63_c_procedimento|sd63_c_nome|sd63_i_codigo";
    js_OpenJanelaIframe('top.corpo', 'db_iframe_sau_procedimento', sUrl, 'Pesquisa ', true);
  } else {

    sUrl += "pesquisa_chave=" + $F('sd63_c_procedimento') + "&funcao_js=parent.js_mostrasau_procedimento";
    if ($F('sd63_c_procedimento') != '') { 
      js_OpenJanelaIframe('top.corpo', 'db_iframe_sau_procedimento', sUrl, 'Pesquisa', false);
    } else {
      
      $('sd63_c_nome').value         = ''; 
      $('tf12_i_procedimento').value = ''; 
    }
  }
}

function js_mostrasau_procedimento(sProcedimento, lErro, iCodigoProcedimento) {
  
  $('sd63_c_nome').value         = sProcedimento; 
  $('tf12_i_procedimento').value = iCodigoProcedimento;
   
  if (lErro) { 
    
    $('sd63_c_procedimento').focus(); 
    $('tf12_i_procedimento').value = ''; 
    $('sd63_c_procedimento').value = '';
    return false; 
  }

  js_buscaValorPadrao();
}

function js_mostrasau_procedimento1 (sProcedimento, sDescricao, iCodigoProcedimento) {

  
  $('sd63_c_procedimento').value = sProcedimento;
  $('sd63_c_nome').value         = sDescricao;
  $('tf12_i_procedimento').value = iCodigoProcedimento;
  db_iframe_sau_procedimento.hide();
  js_buscaValorPadrao();
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tfd_ajudacusto','func_tfd_ajudacusto.php?funcao_js=parent.js_preenchepesquisa|tf12_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave) {
  
  db_iframe_tfd_ajudacusto.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_buscaValorPadrao() {

  var oParametros     = new Object();
  oParametros.exec    = "valorPadraoProcedimento"; 
  oParametros.iCodigo = $F('tf12_i_procedimento');

  js_divCarregando("Aguarde, buscando valor padrão do procedimento.", "msgBox");
  new Ajax.Request("tfd4_ajudacusto.RPC.php",
                   { method:     'post',
                     asynchronous: false,
                     parameters: 'json='+Object.toJSON(oParametros),
                     onComplete: js_retornoBuscaValorPadrao
                   }
                  );
}

function js_retornoBuscaValorPadrao(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval ('(' + oAjax.responseText + ')');

  if (oRetorno.status == 2) {
    
    alert(oRetorno.message.urlDecode());
    return false;
  }
  $('tf12_f_valor').value = oRetorno.nValorProcedimento; 
}
</script>