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
$cltfd_gradehorarios->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("tf03_c_descr");

?>
<form name="form1" method="post" action="">
<center>
<table border="0" cellpadding="2">
  <tr style="display: none;">
    <td nowrap title="<?=@$Ttf02_i_codigo?>">
       <?=@$Ltf02_i_codigo?>
    </td>
    <td> 
      <?
      db_input('tf02_i_codigo',10,$Itf02_i_codigo,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf02_i_destino?>">
      <?
      db_ancora(@$Ltf02_i_destino,"js_pesquisatf02_i_destino(true);",$db_opcao);
      ?>
    </td>
    <td nowrap> 
      <?
      db_input('tf02_i_destino',10,$Itf02_i_destino,true,'text',$db_opcao," onchange='js_pesquisatf02_i_destino(false);' ");
      db_input('tf03_c_descr',50,$Itf03_c_descr,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <fieldset style="width: 96%;"> <legend><b>Grade de Horários das Saídas</b></legend>
        <table width="100%" cellpadding="2">
          <tr>
            <td nowrap title="<?=@$Ttf02_i_diasemana?>" colspan="2">
              <?=@$Ltf02_i_diasemana?>&nbsp;&nbsp;&nbsp;
              <input type="checkbox" id="chk_seg" name="chk_seg" value="2" 
                <?=$db_opcao != 1 ? 'readonly ' : ''?><?=@$tf02_i_diasemana == 2 || isset($chk_seg) ? 'checked' : ''?>>Seg 
              <input type="checkbox" id="chk_ter" name="chk_ter" value="3" 
                <?=$db_opcao != 1 ? 'readonly ' : ''?><?=@$tf02_i_diasemana == 3 || isset($chk_ter) ? 'checked' : ''?>>Ter 
              <input type="checkbox" id="chk_qua" name="chk_qua" value="4" 
                <?=$db_opcao != 1 ? 'readonly ' : ''?><?=@$tf02_i_diasemana == 4 || isset($chk_qua) ? 'checked' : ''?>>Qua
							<input type="checkbox" id="chk_qui" name="chk_qui" value="5" 
                <?=$db_opcao != 1 ? 'readonly ' : ''?><?=@$tf02_i_diasemana == 5 || isset($chk_qui) ? 'checked' : ''?>>Qui 
							<input type="checkbox" id="chk_sex" name="chk_sex" value="6" 
                <?=$db_opcao != 1 ? 'readonly ' : ''?><?=@$tf02_i_diasemana == 6 || isset($chk_sex) ? 'checked' : ''?>>Sex 
							<input type="checkbox" id="chk_sab" name="chk_sab" value="7" 
                <?=$db_opcao != 1 ? 'readonly ' : ''?><?=@$tf02_i_diasemana == 7 || isset($chk_sab) ? 'checked' : ''?>>Sáb 
							<input type="checkbox" id="chk_dom" name="chk_dom" value="1" 
                <?=$db_opcao != 1 ? 'readonly ' : ''?><?=@$tf02_i_diasemana == 1 || isset($chk_dom) ? 'checked' : ''?>>Dom
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ttf02_c_horario?>" width="5%">
              <?=@$Ltf02_c_horario?>&nbsp;&nbsp;&nbsp;
              <?
              db_input('tf02_c_horario',5,$Itf02_c_horario,true,'text',$db_opcao," onKeyUp=\"mascara_hora(this.value,'tf02_c_horario', event)\" ")
              ?>
              &nbsp;&nbsp;&nbsp;
            </td>
            <td nowrap title="<?=@$Ttf02_i_lotacao?>">
              <?=@$Ltf02_i_lotacao?>&nbsp;&nbsp;&nbsp;
              <?
              db_input('tf02_i_lotacao',5,$Itf02_i_lotacao,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align"center">
              <fieldset style="width: 75%;"> <legend><b>Período</b></legend>
                <table width="100%">
                  <tr>
                    <td nowrap title="<?=@$Ttf02_d_validadeini?>">
                      <?=@$Ltf02_d_validadeini?>
                    </td>
                    <td colspan="3"> 
                      <?
                      if(isset($tf02_d_validadeini) && !empty($tf02_d_validadeini)) {

                        $dTmp = explode('/', $tf02_d_validadeini);
                        if(count($dTmp) == 3) {
                   
                          $tf02_d_validadeini_dia = $dTmp[0];
                          $tf02_d_validadeini_mes = $dTmp[1];
                          $tf02_d_validadeini_ano = $dTmp[2];

                        }

                      }
                      db_inputdata('tf02_d_validadeini',@$tf02_d_validadeini_dia,@$tf02_d_validadeini_mes,@$tf02_d_validadeini_ano,true,'text',$db_opcao,"")
                      ?>
                    </td>
                    <td nowrap title="<?=@$Ttf02_d_validadefim?>">
                      <?=@$Ltf02_d_validadefim?>
                    </td>
                    <td colspan="3"> 
                      <?
                      if(isset($tf02_d_validadefim) && !empty($tf02_d_validadefim)) {

                        $dTmp = explode('/', $tf02_d_validadefim);
                        if(count($dTmp) == 3) {
                   
                          $tf02_d_validadefim_dia = $dTmp[0];
                          $tf02_d_validadefim_mes = $dTmp[1];
                          $tf02_d_validadefim_ano = $dTmp[2];

                        }

                      }
                      db_inputdata('tf02_d_validadefim',@$tf02_d_validadefim_dia,@$tf02_d_validadefim_mes,@$tf02_d_validadefim_ano,true,'text',$db_opcao,"")
                      ?>
                    </td>
                  </tr>
                </table>
              </fieldset>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ttf02_c_localsaida?>" colspan="2">
              <?=@$Ltf02_c_localsaida?>&nbsp;&nbsp;&nbsp;
              <?
              db_input('tf02_c_localsaida',50,$Itf02_c_localsaida,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="4" align="center">
              <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
                type="submit" id="db_opcao"
                value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
                <?=($db_botao==false?"disabled":"")?> <?=($db_opcao != 3 ? ' onclick="return js_validaEnvio();"' : '')?>>
              <input name="cancelar" type="button" id="cancelar" value="Cancelar" onclick="js_cancelar();" 
                <?=(!isset($opcao)?"disabled":"")?>>
            </td>
          </tr>
        </table>

        <table width="100%">
       	  <tr>
		        <td valign="top"><br>
              <?
			       	$aChavepri = array ('tf02_i_codigo' => @$tf02_i_codigo,
                                  'tf02_i_diasemana' => @$tf02_i_diasemana, 
                                  'tf02_i_destino' => @$tf02_i_destino, 
                                  'tf02_i_lotacao' => @$tf02_i_lotacao, 
                                  'tf02_c_horario' => @$tf02_c_horario, 
                                  'tf02_d_validadeini' => @$tf02_d_validadeini, 
                                  'tf02_d_validadefim' => @$tf02_d_validadefim,
                                  'tf02_c_localsaida' => @$tf02_c_localsaida,
                                  'tf03_c_descr' => @$tf03_c_descr);
       			  $oIframeAE->chavepri = $aChavepri;

              $sCampos = 
              " tf02_i_codigo,
                tf02_i_diasemana,
                tf02_i_destino,
                tf02_i_lotacao,
                tf02_c_horario,
                tf02_d_validadeini,
                tf02_d_validadefim,
                tf02_c_localsaida,
                tf03_c_descr, 
                ed32_c_descr ";

        
				      @$oIframeAE->sql = $cltfd_gradehorarios->sql_query(null, $sCampos, 'tf02_i_codigo', '');
				      $oIframeAE->campos = 'tf02_i_codigo, tf02_i_destino, tf03_c_descr, ed32_c_descr, tf02_d_validadeini, tf02_d_validadefim';
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

      </fieldset>
    </td>
  </tr>
</table>
</center>
</form>

<script>

function js_cancelar() {

  <?
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."'";
  ?>

}

function js_validaEnvio() {
  
  if($F('tf02_i_destino') == '') {

    alert('Escolha um destino.');
    return false;

  }

  if(!$('chk_seg').checked && !$('chk_ter').checked && !$('chk_qua').checked && 
     !$('chk_qui').checked && !$('chk_sex').checked && !$('chk_sab').checked && !$('chk_dom').checked) {

    alert('Escolha um dia da semana.');
    return false;

  }

  if(!js_validaHora()) {
    return false;
  }

  if($F('tf02_i_lotacao') == ''){

    alert('Preencha a lotação.');
    return false;

  }

  if($F('tf02_c_localsaida') == ''){

    alert('Preencha o local de saída.');
    return false;

  }

  return js_validaData();

}

function js_validaHora() {

  if($F('tf02_c_horario') == '') {
      
    alert('Preencha o horário.');
    return false;
   
  }
	
  if($F('tf02_c_horario').length != 5) {
      
    alert('Preencha corretamente o horário.');
    return false;
   
  }

  hr_ini  = ($F('tf02_c_horario').substring(0,2));
	mi_ini  = ($F('tf02_c_horario').substring(3,5));

  if(isNaN(hr_ini) || isNaN(mi_ini)) {
        
    alert('Preencha corretamente o horário.');
    return false;

  }

	return true;

}

function js_validaData() {
 
  if($F('tf02_d_validadeini') != '') {
  
    if($F('tf02_d_validadefim') != '') {

      aIni = $F('tf02_d_validadeini').split('/');
      aFim = $F('tf02_d_validadefim').split('/');
      dIni = new Date(aIni[2], aIni[1], aIni[0]);
      dFim = new Date(aFim[2], aFim[1], aFim[0]);
  	  if(dFim < dIni) {
      
        alert("Data final menor que a data inicial");
			  $('tf02_d_validadefim').value = '';
			  $('tf02_d_validadefim_dia').value = '';
			  $('tf02_d_validadefim_mes').value = '';
			  $('tf02_d_validadefim_ano').value = '';
        $('tf02_d_validadefim').focus();
        return false;

      }

    }

  } else {

    alert('Preencha a data de início.');
    return false;

  }

  return true;

}

function js_pesquisatf02_i_destino(mostra) {

  if(mostra == true) {

    js_OpenJanelaIframe('','db_iframe_tfd_destino','func_tfd_destino.php?funcao_js=parent.js_mostratfd_destino1|'+
                        'tf03_i_codigo|tf03_c_descr&chave_validade=true','Pesquisa',true);
  } else {

     if(document.form1.tf02_i_destino.value != '') {

        js_OpenJanelaIframe('','db_iframe_tfd_destino','func_tfd_destino.php?pesquisa_chave='+
                            document.form1.tf02_i_destino.value+'&funcao_js=parent.js_mostratfd_destino'+
                            '&chave_validade=true','Pesquisa',false);

     } else {
       document.form1.tf03_c_descr.value = ''; 
     }

  }

}
function js_mostratfd_destino(chave,erro){
  document.form1.tf03_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.tf02_i_destino.focus(); 
    document.form1.tf02_i_destino.value = ''; 
  }
}
function js_mostratfd_destino1(chave1,chave2){
  document.form1.tf02_i_destino.value = chave1;
  document.form1.tf03_c_descr.value = chave2;
  db_iframe_tfd_destino.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tfd_gradehorarios','func_tfd_gradehorarios.php?funcao_js=parent.js_preenchepesquisa|tf02_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tfd_gradehorarios.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>