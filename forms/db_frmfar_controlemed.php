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

//MODULO: Farmácia
$oDaoFarControlemed->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("fa01_i_codigo");
$oRotulo->label("fa11_i_codigo");
$oRotulo->label("fa12_i_codigo");
$oRotulo->label("fa11_i_cgsund");
$oRotulo->label("fa11_t_obs");
$oRotulo->label("m60_descr");
$oRotulo->label("fa12_c_descricao");

$hoje = date('Y-m-d', db_getsession('DB_datausu'));

?>
<form name="form1" method="post" action="">
<center>
<table>
  <tr>
    <td nowrap title="<?=@$Tfa11_i_cgsund?>">
      <?
      if (isset($lBotao)) {
        db_ancora(@$Lfa11_i_cgsund,'', 3);
      } else {
        db_ancora(@$Lfa11_i_cgsund,"js_pesquisafa11_i_cgsund(true);",$db_opcao1);
      }
      ?>
    </td>
    <td> 
      <?
      if (isset($lBotao)) {

        db_input('fa11_i_cgsund',10,@$Ifa11_i_cgsund,true,'text', 3);
        db_input('lBotao',1,'',true,'hidden', 3);

      } else {
        db_input('fa11_i_cgsund',10,@$Ifa11_i_cgsund,true,'text',$db_opcao1," onchange='js_pesquisafa11_i_cgsund(false);'");
      }
      db_input('z01_v_nome',50,@$Iz01_v_nome,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa10_i_programa?>">
      <?
      db_ancora(@$Lfa10_i_programa,"js_pesquisafa10_i_programa(true);",$db_opcao);
      ?>
    </td>
    <td> 
      <?
      db_input('fa10_i_programa',10,@$Ifa10_i_programa,true,'text',$db_opcao," onchange='js_pesquisafa10_i_programa(false);'");
      db_input('fa12_c_descricao',50,$Ifa12_c_descricao,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
      <fieldset style="width:85%"><legend><b>Lançar Medicamentos Continuados</b></legend>
        <table border="0" align="left">
          <tr>
            <td colspan="3"> 
              <?
              db_input('fa10_i_codigo', 5, $Ifa10_i_codigo, true, 'hidden', $db_opcao, '');
              db_input('fa11_i_codigo', 5, $Ifa11_i_codigo, true, 'hidden', $db_opcao, '');
              db_input('fa10_i_controle', 5, $Ifa10_i_controle, true, 'hidden', $db_opcao, '');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tfa10_i_medicamento?>">
              <?
              db_ancora(@$Lfa10_i_medicamento, "js_pesquisafa10_i_medicamento(true);", $db_opcao);
              ?>
            </td>
            <td colspan="2" nowrap> 
              <?
              db_input('fa10_i_medicamento', 10, $Ifa10_i_medicamento, true, 'text', $db_opcao, 
                       " onchange='js_pesquisafa10_i_medicamento(false);'"
                      );
              db_input('m60_descr', 50, $Im60_descr, true, 'text', $db_opcao, '');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tfa10_i_quantidade?>">
              <?=@$Lfa10_i_quantidade?>
            </td>
            <td> 
              <?
              db_input('fa10_i_quantidade',10,$Ifa10_i_quantidade,true,'text',$db_opcao,"")
              ?>
            </td>
            <td rowspan="3" align="right"> 
              <fieldset style="width:60%"><legend><b> Validade </b></legend>
                <table border="0">
                  <tr>               
          			    <td nowrap title="<?=@$Tfa10_d_dataini?>">
                      <?=@$Lfa10_d_dataini?>
                    </td>         
                    <td>
                      <?
                      if (!isset($fa10_d_dataini)) {

                        $vet                = explode("-", $hoje);
                        $fa10_d_dataini     = $vet[2]."/".$vet[1]."/".$vet[0];
                        $fa10_d_dataini_dia = $vet[2];
                        $fa10_d_dataini_mes = $vet[1];
                        $fa10_d_dataini_ano = $vet[0];

                      }
                      db_inputdata('fa10_d_dataini', @$fa10_d_dataini_dia, @$fa10_d_dataini_mes, @$fa10_d_dataini_ano,
                                   true, 'text', $db_opcao, 
                                   " onKeyDown='return js_controla_tecla_enter(this,event);' "
                                  );
                      ?>
                    </td>
                  </tr>
                  <tr>
	                  <td nowrap title="<?=@$Tfa10_d_datafim?>">
                      <?=@$Lfa10_d_datafim?>
                    </td>
	                  <td> 
                      <?
                      db_inputdata('fa10_d_datafim', @$fa10_d_datafim_dia, @$fa10_d_datafim_mes, 
                                   @$fa10_d_datafim_ano, true, 'text', $db_opcao, 
                                   " onKeyDown='return js_controla_tecla_enter(this,event);' ".
                                   " onchange=\"js_troca();\"", '', '', 'parent.js_troca();'
                                  );
                      ?>
                    </td>
                  </tr>
                </table> 
              </fieldset>       
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tfa10_i_prazo?>">
              <?=@$Lfa10_i_prazo?>
            </td>
            <td colspan="2"> 
              <?
              db_input('fa10_i_prazo', 10, $Ifa10_i_prazo, true, 'text', $db_opcao, "onchange='js_prazo();'")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tfa10_i_margem?>">
              <?=@$Lfa10_i_margem?>
            </td>
            <td colspan="2"> 
              <?
              db_input('fa10_i_margem', 10, $Ifa10_i_margem, true, 'text', $db_opcao, "onchange='js_margem();'");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tfa11_t_obs?>">
              <?=@$Lfa11_t_obs?>
            </td>
            <td colspan="2"> 
              <?
              db_textarea('fa11_t_obs', 1, 60, @$Ifa11_t_obs, true, 'text', $db_opcao,
                          " onKeyDown='return js_controla_tecla_enter(this,event);' "
                         );
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>

<table border="0">
  <tr>
    <td height="18">&nbsp;</td>
    <td height="18">&nbsp;</td>
  </tr>
  <tr>
    <td>
      <?
      if (!isset($opcao) && isset($db_opcao) && $db_opcao==3) {
        $db_botao=false;	  
      }
      ?>
      <input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>" 
        type="submit" id="db_opcao" 
        value="<?=($db_opcao == 1 ? "Lançar" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>" 
        <?=($db_botao == false ? "disabled" : "")?> 
        <?=($db_botao == false || isset($confirmar) ? "disabled" : "")?>
        onclick="return js_verifica();">
      <input name="imprimir" type="button" id="imprimir" value="Imprimir Carteirinha" 
        <?=(!isset($fa11_i_cgsund) ? "disabled" : "");?> onclick="js_carteirinha();">
      <?
      if (!isset($lBotao)) {
      ?>
        <input name="controle" type="button" id="controle" value="Novo Controle" 
          onclick='location.href="far1_far_controlemed001.php"'>
      <?
      }
      ?>
      <input name="cancelar" type="button" id="cancelar" value="Cancelar"  
        <?=($db_opcao == 1 || isset($incluir) ? "disabled" : "")?> 
        onclick='location.href="far1_far_controlemed001.php?cancelar&fa11_i_cgsund=<?=@$fa11_i_cgsund?>&z01_v_nome=<?=@$z01_v_nome?>"'>
      <?
      if (isset($lBotao)) {
      ?>
        <input name="fechar" type="button" id="fechar" value="Fechar" onclick="parent.js_fechaFrameContinuados();">
      <?
      }
      ?>
    </td>
  </tr>
</table>
</center>

<center>
<table>
  <tr>
    <td valign="top">
      <?  
      if (isset($fa11_i_cgsund)) {
        
        $sSql   = $oDaoFarControlemed->sql_query('', 'fa11_i_codigo', '', "fa11_i_cgsund = $fa11_i_cgsund");
        $result = $oDaoFarControlemed->sql_record($sSql);
	      if ($oDaoFarControlemed->numrows > 0) {
          db_fieldsmemory($result, 0);
        }
        $controle = "fa11_i_cgsund=$fa11_i_cgsund";
       } else {
       	$controle = 0;
       }
      
      $chavepri                             = array("fa10_i_codigo"=>@$fa10_i_codigo);
      $oIframeAlterarExcluir->chavepri      = $chavepri;
      $oIframeAlterarExcluir->sql           = $oDaoFarControlemed->sql_query('', '*', '', "$controle");
      $oIframeAlterarExcluir->campos        = 'fa10_i_codigo, m60_descr, fa10_i_quantidade, fa10_i_prazo, ';
      $oIframeAlterarExcluir->campos       .= 'fa10_d_dataini,fa10_d_datafim,fa12_c_descricao ';
      $oIframeAlterarExcluir->legenda       = 'Registros';
      $oIframeAlterarExcluir->msg_vazio     = 'Não foi encontrado nenhum registro.';
      $oIframeAlterarExcluir->textocabec    = '#DEB887';
      $oIframeAlterarExcluir->textocorpo    = '#444444';
      $oIframeAlterarExcluir->fundocabec    = '#444444';
      $oIframeAlterarExcluir->fundocorpo    = '#eaeaea';
      $oIframeAlterarExcluir->tamfontecabec = 9;
      $oIframeAlterarExcluir->tamfontecorpo = 9;
      $oIframeAlterarExcluir->formulario    = false;
		  $oIframeAlterarExcluir->iframe_width  = '570';
      $oIframeAlterarExcluir->iframe_height = '130';
		  $oIframeAlterarExcluir->opcoes        =  $db_opcao;
      $oIframeAlterarExcluir->iframe_alterar_excluir($db_opcao);
      ?>
    </td>
  </tr>
</table>
</center>
</form>

<script>
<?
if (isset($lBotao)) {
?>
js_pesquisafa11_i_cgsundBotao(false);
<?
}
?>

function js_pesquisafa10_i_medicamento(mostra) {

  if (mostra==true) {
    js_OpenJanelaIframe('','db_iframe_far_matersaude','func_far_matersaude.php?funcao_js=parent.js_mostrafar_matersaude1|fa01_i_codigo|m60_descr','Pesquisa',true);
  }else{
     if (document.form1.fa10_i_medicamento.value != '') { 
        js_OpenJanelaIframe('','db_iframe_far_matersaude','func_far_matersaude.php?pesquisa_chave='+document.form1.fa10_i_medicamento.value+'&funcao_js=parent.js_mostrafar_matersaude','Pesquisa',false);
     }else{
       document.form1.fa01_i_codigo.value = ''; 
       document.form1.m60_descr.value = ''; 
     }
  }

}
function js_mostrafar_matersaude(chave,erro) {

  document.form1.m60_descr.value = chave; 
  if (erro==true) { 
    document.form1.fa10_i_medicamento.focus(); 
    document.form1.fa10_i_medicamento.value = ''; 
  }

}
function js_mostrafar_matersaude1(chave1,chave2) {

  document.form1.fa10_i_medicamento.value = chave1;
  document.form1.m60_descr.value = chave2;
  db_iframe_far_matersaude.hide();

}
function js_pesquisafa10_i_controle(mostra) {

  if (mostra==true) {
    js_OpenJanelaIframe('','db_iframe_far_controle','func_far_controle.php?funcao_js=parent.js_mostrafar_controle1|fa11_i_codigo|fa11_i_codigo','Pesquisa',true);
  }else{
     if (document.form1.fa10_i_controle.value != '') { 
        js_OpenJanelaIframe('','db_iframe_far_controle','func_far_controle.php?pesquisa_chave='+document.form1.fa10_i_controle.value+'&funcao_js=parent.js_mostrafar_controle','Pesquisa',false);
     }else{
       document.form1.fa11_i_codigo.value = ''; 
     }
  }

}
function js_mostrafar_controle(chave,erro) {

  document.form1.fa11_i_codigo.value = chave; 
  if (erro==true) { 
    document.form1.fa10_i_controle.focus(); 
    document.form1.fa10_i_controle.value = ''; 
  }

}
function js_mostrafar_controle1(chave1,chave2) {

  document.form1.fa10_i_controle.value = chave1;
  document.form1.fa11_i_codigo.value = chave2;
  db_iframe_far_controle.hide();

}
function js_pesquisafa10_i_programa(mostra) {

  if (mostra==true) {
    js_OpenJanelaIframe('','db_iframe_far_programa','func_far_programa.php?funcao_js=parent.js_mostrafar_programa1|fa12_i_codigo|fa12_c_descricao','Pesquisa',true);
  }else{
     if (document.form1.fa10_i_programa.value != '') { 
        js_OpenJanelaIframe('','db_iframe_far_programa','func_far_programa.php?pesquisa_chave='+document.form1.fa10_i_programa.value+'&funcao_js=parent.js_mostrafar_programa','Pesquisa',false);
     }else{
       document.form1.fa12_i_codigo.value = ''; 
     }
  }

}
function js_mostrafar_programa(chave,erro) {

  document.form1.fa12_c_descricao.value = chave; 
  if (erro==true) { 
    document.form1.fa10_i_programa.focus(); 
    document.form1.fa10_i_programa.value = ''; 
  }

}
function js_mostrafar_programa1(chave1,chave2) {

  document.form1.fa10_i_programa.value = chave1;
  document.form1.fa12_c_descricao.value = chave2;
  db_iframe_far_programa.hide();

}
function js_pesquisafa11_i_cgsund(mostra) {

  if (mostra==true) {
    js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?funcao_js=parent.js_mostracgs_und1|z01_i_cgsund|z01_v_nome','Pesquisa',true);
  }else{
     if (document.form1.fa11_i_cgsund.value != '') {
        js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?pesquisa_chave='+document.form1.fa11_i_cgsund.value+'&funcao_js=parent.js_mostracgs_und','Pesquisa',false);
     }else{
       document.form1.z01_i_cgsund.value = ''; 
     }
  }

}
function js_mostracgs_und(chave,erro) {

  document.form1.z01_v_nome.value = chave; 
  if (erro==true) { 
    document.form1.fa11_i_cgsund.focus(); 
    document.form1.fa11_i_cgsund.value = '';
  }else{
    document.form1.z01_v_nome.value=chave;
    document.form1.submit();
  }

}
function js_mostracgs_und1(chave1,chave2) {

  document.form1.fa11_i_cgsund.value = chave1;
  document.form1.z01_v_nome.value = chave2;
  db_iframe_cgs_und.hide();
  document.form1.submit();

}


function js_pesquisafa11_i_cgsundBotao() {
  
  if (document.form1.fa11_i_cgsund.value != '') {
    js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?pesquisa_chave='+document.form1.fa11_i_cgsund.value+'&funcao_js=parent.js_mostracgs_undBotao','Pesquisa',false);
  }

}
function js_mostracgs_undBotao(chave,erro) {

  document.form1.z01_v_nome.value = chave; 
  if (erro==true) { 
    document.form1.fa11_i_cgsund.focus(); 
    document.form1.fa11_i_cgsund.value = '';
  }else{
    document.form1.z01_v_nome.value=chave;
  }

}

function js_pesquisa() {

  js_OpenJanelaIframe('top.corpo','db_iframe_far_controlemed','func_far_controlemed.php?funcao_js=parent.js_preenchepesquisa|fa10_i_codigo','Pesquisa',true);

}
function js_preenchepesquisa(chave) {

  db_iframe_far_controlemed.hide();
  <?
  if ($db_opcao!=1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>

}
function js_carteirinha() {

 jan = window.open('far2_carteira001.php?fa10_i_codigo='+document.form1.fa10_i_codigo.value+'&fa11_i_cgsund='+document.form1.fa11_i_cgsund.value+'&fa10_d_dataini='+document.form1.fa10_d_dataini.value+'&fa10_d_datafim'+document.form1.fa10_d_datafim.value+'&fa10_i_medicamento'+document.form1.fa10_i_medicamento.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);	

}
function js_margem() {

		if (Number(document.form1.fa10_i_margem.value) > (Number(document.form1.fa10_i_prazo.value)/2)) {
			alert("Margem tem que ser menor ou igual a metade da frequência.");
			document.form1.fa10_i_margem.value = "";
		}	

}
function js_prazo() {

	if (document.form1.fa10_i_margem.value!='') {	
		if (Number(document.form1.fa10_i_margem.value) > (Number(document.form1.fa10_i_prazo.value)/2)) {
			alert("Margem tem que ser menor ou igual a metade da frequência.");
			document.form1.fa10_i_margem.value = "";
		}
  }	

}
function js_troca() {

  if (document.form1.fa10_d_datafim.value != ""  && document.form1.fa10_d_dataini.value != "" ) {

    aIni = document.form1.fa10_d_dataini.value.split('/');
    aFim = document.form1.fa10_d_datafim.value.split('/');
    dIni = new Date(aIni[2], aIni[1], aIni[0]);
    dFim = new Date(aFim[2], aFim[1], aFim[0]);

  	if (dFim < dIni) {
      
      alert("Data final menor que a data inicial");
			document.form1.fa10_d_datafim.value = "";
		  document.form1.fa10_d_datafim_dia.value = "";
		  document.form1.fa10_d_datafim_mes.value = "";
      document.form1.fa10_d_datafim_ano.value = "";
      document.form1.fa10_d_datafim.focus();

		}

  }

}
function js_verifica() {

   if (($('fa11_i_cgsund').value=='')||($('fa11_i_cgsund').value=='0')) {
      alert('CGS não informado!');
      return false;
   }
   if (($('fa10_i_medicamento').value=='')||($('fa10_i_medicamento').value=='0')) {
      alert('Medicamento não informado!');
      return false;
   }
   if (($('fa10_i_quantidade').value=='')||($('fa10_i_quantidade').value=='0')) {
      alert('Quantidade não informado!');
      return false;
   }
   if (($('fa10_i_prazo').value=='')||($('fa10_i_prazo').value=='0')) {
      alert('Frequencia não informado!');
      return false;
   }
   if (($('fa10_i_margem').value=='')||($('fa10_i_margem').value=='0')) {
      alert('Margem não informado!');
      return false;
   }
   if (($('fa10_i_programa').value=='')||($('fa10_i_programa').value=='0')) {
      alert('Programa não informado!');
      return false;
   }
   if (($('fa11_t_obs').value=='')) {
      alert('Observação não informado!');
      return false;
   }
   if (($('fa10_d_dataini').value=='')) {
      alert('Data inicial não informado!');
      return false;
   }
   
   return true;

}

// Autocomplete do medicamento
oAutoComplete = new dbAutoComplete(document.form1.m60_descr,'far4_retirada_autonomeRPC.php?tipo=1');
oAutoComplete.setTxtFieldId(document.getElementById('fa10_i_medicamento'));
oAutoComplete.show();
oAutoComplete.setCallBackFunction(function(id,label) {

                                    document.form1.fa10_i_medicamento.value = id;
                                    document.form1.m60_descr.value = label;
                                    document.form1.fa10_i_quantidade.focus();
                                   });


</script>