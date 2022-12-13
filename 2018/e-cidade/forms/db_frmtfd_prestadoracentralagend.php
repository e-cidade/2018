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
$cltfd_prestadoracentralagend->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr style="display: '';">
    <td nowrap title="<?=@$Ttf10_i_codigo?>">
      <?=@$Ltf10_i_codigo?>
    </td>
    <td> 
      <?
      db_input('tf10_i_codigo',10,$Itf10_i_codigo,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf10_i_centralagend?>">
      <?
      db_ancora(@$Ltf10_i_centralagend,"js_pesquisatf10_i_centralagend(true);",3);
      ?>
    </td>
    <td nowrap> 
      <?
      db_input('tf10_i_centralagend',10,$Itf10_i_centralagend,true,'text',3,'');
      db_input('z01_nome2',50,$Iz01_nome,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf10_i_prestadora?>">
      <?
      db_ancora(@$Ltf10_i_prestadora,"js_pesquisatf10_i_prestadora(true);",$db_opcao);
      ?>
    </td>
    <td nowrap> 
      <?
      db_input('tf10_i_prestadora',10,$Itf10_i_prestadora,true,'text',$db_opcao," onchange='js_pesquisatf10_i_prestadora(false);'");
      db_input('z01_nome',50,$Iz01_nome,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align"center">
      <fieldset style="width: 75%;"> <legend><b>Período</b></legend>
        <table width="100%">
          <tr>
            <td nowrap title="<?=@$Ttf10_d_validadeini?>">
              <?=@$Ltf10_d_validadeini?>
            </td>
            <td> 
              <?
              if(isset($tf10_d_validadeini) && !empty($tf10_d_validadeini)) {

                $dTmp = explode('/', $tf10_d_validadeini);
                if(count($dTmp) == 3) {
                   
                  $tf10_d_validadeini_dia = $dTmp[0];
                  $tf10_d_validadeini_mes = $dTmp[1];
                  $tf10_d_validadeini_ano = $dTmp[2];

                }

              }
              db_inputdata('tf10_d_validadeini',@$tf10_d_validadeini_dia,@$tf10_d_validadeini_mes,@$tf10_d_validadeini_ano,true,'text',$db_opcao,"");
              ?>
            </td>
            <td nowrap title="<?=@$Ttf10_d_validadefim?>">
              <?=@$Ltf10_d_validadefim?>
            </td>
            <td> 
              <?
              if(isset($tf10_d_validadefim) && !empty($tf10_d_validadefim)) {

                $dTmp = explode('/', $tf10_d_validadefim);
                if(count($dTmp) == 3) {
                   
                  $tf10_d_validadefim_dia = $dTmp[0];
                  $tf10_d_validadefim_mes = $dTmp[1];
                  $tf10_d_validadefim_ano = $dTmp[2];

                }

              }
              db_inputdata('tf10_d_validadefim',@$tf10_d_validadefim_dia,@$tf10_d_validadefim_mes,@$tf10_d_validadefim_ano,true,'text',$db_opcao,"");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
  type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
  <?=($db_botao==false?"disabled":"")?> <?=($db_opcao != 3 ? ' onclick="return js_validaEnvio();"' : '')?>>
<input name="cancelar" type="button" id="cancelar" value="Cancelar" onclick="js_cancelar();" <?=(!isset($opcao)?"disabled":"")?> >
<input name="novo" type="button" id="novo" value="Nova Central" onclick="js_novo()">
</form>

  <table width="100%">
	  <tr>
		  <td valign="top"><br>
        <?
				$aChavepri = array ('tf10_i_codigo' => @$tf10_i_codigo,
                            'tf09_i_codigo' => @$tf09_i_codigo,
                            'tf10_i_prestadora' => @$tf10_i_prestadora,
                            'z01_nome' => @$z01_nome, 
                            'tf10_d_validadeini' => @$tf10_d_validadeini, 
                            'tf10_d_validadefim' => @$tf10_d_validadefim,
                            'tf10_i_centralagend' => @$tf10_i_centralagend,
                            'z01_nome2' => @$z01_nome2);
				$oIframeAE->chavepri = $aChavepri;

        $sCampos = 
        " tf10_i_codigo,
          tf09_i_codigo,
          tf10_i_prestadora,
          a.z01_nome as z01_nome,
          cgm.z01_nome as z01_nome2,
          tf10_d_validadeini,
          tf10_d_validadefim,
          tf10_i_centralagend ";
        
				$oIframeAE->sql = $cltfd_prestadoracentralagend->sql_query(null, $sCampos, ' tf10_i_codigo ',
                                                                   " tf10_i_centralagend = $tf10_i_centralagend ");
				$oIframeAE->campos = 'tf10_i_codigo, tf10_i_prestadora, z01_nome, tf10_d_validadeini, tf10_d_validadefim';
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
			  @$oIframeAE->iframe_alterar_excluir($db_opcao);
				?>
      </td>
  	</tr>
	</table>

<script>

function js_novo() {

  parent.document.formaba.a2.disabled = true;
  top.corpo.iframe_a1.location.href   = 'tfd1_tfd_centralagendamento001.php';
  parent.mo_camada('a1');

}

function js_cancelar() {

  <?
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?tf10_i_centralagend=$tf10_i_centralagend&z01_nome2=$z01_nome2'";
  ?>

}

function js_validaEnvio() {
  
  if($F('tf10_i_prestadora') == '') {

    alert('Escolha uma prestadora.');
    return false;

  }

  return js_validaData();

}

function js_validaData() {
 
  if($F('tf10_d_validadeini') != '') {
  
    if($F('tf10_d_validadefim') != '') {

      aIni = $F('tf10_d_validadeini').split('/');
      aFim = $F('tf10_d_validadefim').split('/');
      dIni = new Date(aIni[2], aIni[1], aIni[0]);
      dFim = new Date(aFim[2], aFim[1], aFim[0]);
  	  if(dFim < dIni) {
      
        alert("Data final menor que a data inicial");
			  $('tf10_d_validadefim').value = '';
			  $('tf10_d_validadefim_dia').value = '';
			  $('tf10_d_validadefim_mes').value = '';
			  $('tf10_d_validadefim_ano').value = '';
        $('tf10_d_validadefim').focus();
        return false;

      }

    }

  } else {

    alert('Preencha a data de início.');
    return false;

  }

  return true;

}

function js_pesquisatf10_i_prestadora(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tfd_prestadora','func_tfd_prestadora.php?funcao_js=parent.js_mostratfd_prestadora1|tf25_i_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.tf10_i_prestadora.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_tfd_prestadora','func_tfd_prestadora.php?pesquisa_chave='+document.form1.tf10_i_prestadora.value+'&funcao_js=parent.js_mostratfd_prestadora','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostratfd_prestadora(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.tf10_i_prestadora.focus(); 
    document.form1.tf10_i_prestadora.value = ''; 
  }
}
function js_mostratfd_prestadora1(chave1,chave2){
  document.form1.tf10_i_prestadora.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_tfd_prestadora.hide();
}
/*
function js_pesquisatf10_i_centralagend(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tfd_centralagendamento','func_tfd_centralagendamento.php?funcao_js=parent.js_mostratfd_centralagendamento1|tf09_i_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.tf10_i_centralagend.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_tfd_centralagendamento','func_tfd_centralagendamento.php?pesquisa_chave='+document.form1.tf10_i_centralagend.value+'&funcao_js=parent.js_mostratfd_centralagendamento','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostratfd_centralagendamento(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.tf10_i_centralagend.focus(); 
    document.form1.tf10_i_centralagend.value = ''; 
  }
}
function js_mostratfd_centralagendamento1(chave1,chave2){
  document.form1.tf10_i_centralagend.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_tfd_centralagendamento.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_tfd_prestadoracentralagend','func_tfd_prestadoracentralagend.php?funcao_js=parent.js_preenchepesquisa|tf10_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tfd_prestadoracentralagend.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}*/
</script>