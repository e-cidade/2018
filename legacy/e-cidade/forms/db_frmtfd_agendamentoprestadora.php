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

//MODULO: TFD
$oDaotfd_agendamentoprestadora->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label('tf10_i_codigo');
$oRotulo->label('tf01_i_cgsund');
$oRotulo->label('tf01_i_codigo');
$oRotulo->label('z01_nome');
$oRotulo->label('tf09_i_codigo');
$oRotulo->label('z01_v_nome');
$oRotulo->label('tf10_i_centralagend');
$oRotulo->label('tf10_i_prestadora');
$oRotulo->label('z01_munic');
$oRotulo->label('z01_uf');
$oRotulo->label('z01_ender');
$oRotulo->label('z01_numero');
$oRotulo->label('z01_compl');
$oRotulo->label('z01_bairro');
?>
<form name="form1" method="post" action="">
<center>
<table style = "width:100%;border:0">
  <tr style="display: none;">
    <td nowrap title="<?=@$Ttf16_i_codigo?>">
      <?=@$Ltf16_i_codigo?>
    </td>
    <td colspan="3">
      <?
      db_input('tf16_i_codigo',10,$Itf16_i_codigo,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf16_i_pedidotfd?>">
      <?=@$Ltf16_i_pedidotfd?>
    </td>
    <td colspan="3">
      <?
      db_input('tf16_i_pedidotfd',10,$Itf16_i_pedidotfd,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf01_i_cgsund?>">
      <?
      echo '<b>Paciente:</b>';
      ?>
    </td>
    <td nowrap colspan="3">
      <?
      db_input('tf01_i_cgsund',10,$Itf01_i_cgsund,true,'text',3,'');
      db_input('z01_v_nome',50,$Iz01_v_nome,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf10_i_centralagend?>">
      <?
      db_ancora(@$Ltf10_i_centralagend,"js_pesquisatf10_i_centralagend(true);",$db_opcao);
      ?>
    </td>
    <td nowrap colspan="3">
      <?
      db_input('tf10_i_centralagend',10,$Itf10_i_centralagend,true,'text',$db_opcao," onchange='js_pesquisatf10_i_centralagend(false);'");
      db_input('z01_nome',50,$Iz01_nome,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf10_i_prestadora?>">
      <?
      db_ancora(@$Ltf10_i_prestadora,"js_pesquisatf10_i_prestadora(true);",$db_opcao);
      ?>
    </td>
    <td nowrap colspan="3">
      <?
      db_input('tf10_i_prestadora',10,$Itf10_i_prestadora,true,'text',$db_opcao," onchange='js_pesquisatf10_i_prestadora(false);'");
      db_input('z01_nome2',50,$Iz01_nome,true,'text',3,'');
      db_input('tf16_i_prestcentralagend',2,$Itf16_i_prestcentralagend,true,'hidden',3,'');
      db_input('tf09_i_numcgm',2,'',true,'hidden',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="4" align="left">
      <fieldset style='width: 98%;'> <legend><b>Endereço da Prestadora</b></legend>
        <table>
          <tr>
            <td nowrap title="<?=@$Tz01_munic?>">
              <?=@$Lz01_munic?>
            </td>
            <td nowrap>
              <?
		          db_input('z01_munic',60,$Iz01_munic,true,'text',3);
              echo $Lz01_uf;
		          db_input('z01_uf',2,$Iz01_uf,true,'text',3);
		          ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tz01_bairro?>">
              <?=$Lz01_bairro?>
            </td>
            <td nowrap>
              <?
              db_input('z01_bairro',69,$Iz01_uf,true,'text',3);
 		          ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tz01_ender?>">
              <?=$Lz01_ender?>
            </td>
            <td nowrap>
	            <?
  	  	      db_input('z01_ender',69,$Iz01_ender,true,'text',3);
	            ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tz01_numero?>">
              <?=$Lz01_numero?>
            </td>
            <td nowrap>
              <?
		          db_input('z01_numero',6,$Iz01_numero,true,'text',3,'');
              echo '&nbsp;'.$Lz01_compl;
              db_input('z01_compl',46,$Iz01_compl,true,'text',3,'');
		          ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
<fieldset style='width: 86%;'> <legend><b>Dados do Agendamento</b></legend>
<table>
  <tr>
    <td nowrap title="<?=@$Ttf16_c_protocolo?>">
      <?=@$Ltf16_c_protocolo?>
    </td>
    <td>
      <?
      db_input('tf16_c_protocolo',30,$Itf16_c_protocolo,true,'text',$db_opcao,"")
      ?>
    </td>
    <td nowrap title="<?=@$Ttf16_d_dataagendamento?>">
       <?=@$Ltf16_d_dataagendamento?>
    </td>
    <td>
      <?
      db_inputdata('tf16_d_dataagendamento',@$tf16_d_dataagendamento_dia,@$tf16_d_dataagendamento_mes,@$tf16_d_dataagendamento_ano,true,'text',$db_opcao,"")
      ?>
    </td>
    <td nowrap title="<?=@$Ttf16_c_horaagendamento?>">
      <?=@$Ltf16_c_horaagendamento?>
    </td>
    <td>
      <?
      db_input('tf16_c_horaagendamento',4,$Itf16_c_horaagendamento,true,'text',$db_opcao,'onKeyUp="mascara_hora(this.value,\'tf16_c_horaagendamento\', event)"')
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf16_c_local?>">
      <?=@$Ltf16_c_local?>
    </td>
    <td nowrap>
      <?
      db_input('tf16_c_local',30,$Itf16_c_local,true,'text',$db_opcao,"")
      ?>
    </td>
    <td nowrap title="<?=@$Ttf16_sala?>">
      <?=@$Ltf16_sala?>
    </td>
    <td nowrap>
      <?
      db_input('tf16_sala',15,$Itf16_sala,true,'text',$db_opcao,"")
      ?>
    </td>
    <td nowrap title="<?=@$Ttf16_sequencia?>">
      <?=@$Ltf16_sequencia?>
    </td>
    <td nowrap>
      <?
      db_input('tf16_sequencia',15,$Itf16_sequencia,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttf16_d_datasistema?>">
      <?=@$Ltf16_c_medico?>
    </td>
    <td nowrap>
      <?
      db_input('tf16_c_medico',30,$Itf16_c_medico,true,'text',$db_opcao,"")
      ?>
    </td>
    <td nowrap title="<?=@$Ttf16_c_crmmedico?>">
       <?=@$Ltf16_c_crmmedico?>
    </td>
    <td>
      <?
      db_input('tf16_c_crmmedico',15,$Itf16_c_crmmedico,true,'text',$db_opcao,"")
      ?>
    </td>
    <td nowrap title="<?=@$Ttf16_c_cnsmedico?>">
      <?=@$Ltf16_c_cnsmedico?>
    </td>
    <td>
      <?
      db_input('tf16_c_cnsmedico',15,$Itf16_c_cnsmedico,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
</table>
</fieldset>
</center>
<br>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
  type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
  <?=($db_botao==false?"disabled":"")?> onclick="return js_validaEnvio();">
<input name="fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_prestadora.hide();">
</form>
<script>

new DBInputHora($('tf16_c_horaagendamento'));

sUrl = 'tfd4_pedidotfd.RPC.php';

<?
if($db_opcao == 2) {
  // echo "js_getEspecMed();";
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

function js_init() {

  var iPrestadora = '';
  if ( $F('db_opcao') == 'Alterar') {
    iPrestadora = $F('tf10_i_prestadora');
  }

  js_pesquisatf10_i_centralagend(false);

  $('tf10_i_prestadora').value = iPrestadora;
  if ( $F('db_opcao') == 'Alterar') {

    setTimeout(function () {
      js_pesquisatf10_i_prestadora(false);
    }, 100);
  }
}

function js_validaEnvio() {

  if($F('tf16_i_pedidotfd').trim() == '') {

    alert('Código do pedido não informado.');
    return false;

  }

  if($F('tf10_i_centralagend').trim() == '') {

    alert('Informe uma central de agendamento.');
    return false;

  }

  if($F('tf10_i_prestadora').trim() == '') {

    alert('Informe uma prestadora.');
    return false;

  }

  if($F('tf16_d_dataagendamento').trim() == '') {

    alert('Informe a data do agendamento.');
    return false;

  }

  if(!js_validaHora()) {
    return false;
  }

  return true;

}

function js_validaHora() {

  if($F('tf16_c_horaagendamento') == '') {

    alert('Preencha o horário.');
    return false;

  }

  if($F('tf16_c_horaagendamento').length != 5) {

    alert('Preencha corretamente o horário.');
    return false;

  }

  hr_ini  = ($F('tf16_c_horaagendamento').substring(0,2));
	mi_ini  = ($F('tf16_c_horaagendamento').substring(3,5));

  if(isNaN(hr_ini) || isNaN(mi_ini)) {

    alert('Preencha corretamente o horário.');
    return false;

  }

	return true;

}

function js_getInfoCgm() {

  var oParam = new Object();
	oParam.exec = "getInfoCgm";
	oParam.iCgm = $F('tf09_i_numcgm');

  if($F('tf10_i_prestadora') != '') {
    js_ajax(oParam, 'js_retornogetInfoCgm');
  }

}
function js_retornogetInfoCgm(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  $('z01_munic').value = oRetorno.z01_munic.urlDecode();
  $('z01_uf').value = oRetorno.z01_uf.urlDecode();
  $('z01_bairro').value = oRetorno.z01_bairro.urlDecode();
  $('z01_ender').value = oRetorno.z01_ender.urlDecode();
  $('z01_numero').value = oRetorno.z01_numero.urlDecode();
  $('z01_compl').value = oRetorno.z01_compl.urlDecode();

}
function js_limpaInfoCgm() {

  $('z01_munic').value = '';
  $('z01_uf').value = '';
  $('z01_bairro').value = '';
  $('z01_ender').value = '';
  $('z01_numero').value = '';
  $('z01_compl').value = '';
  $('tf10_i_prestadora').value = '';
  $('z01_nome2').value = '';
  $('tf16_i_prestcentralagend').value = '';
  $('tf09_i_numcgm').value = '';

}

function js_pesquisatf10_i_prestadora(mostra) {

  if(document.form1.tf10_i_centralagend.value == '') {

    alert('Escolha uma central de agendmento primeiro');
    js_limpaInfoCgm();
    return false;

  }
  sChave = 'chave_tf10_i_centralagend='+document.form1.tf10_i_centralagend.value;
  if(mostra==true) {

    js_OpenJanelaIframe('','db_iframe_tfd_prestadoracentralagend','func_tfd_prestadoracentralagend.php?'+sChave+
                        '&funcao_js=parent.js_mostratfd_prestadora|tf10_i_prestadora|z01_nome|tf10_i_codigo|z01_numcgm',
                        'Pesquisa',true);

  } else {

     if(document.form1.tf10_i_prestadora.value != '') {

        js_OpenJanelaIframe('','db_iframe_tfd_prestadoracentralagend','func_tfd_prestadoracentralagend.php?'+sChave+
                            '&funcao_js=parent.js_mostratfd_prestadora|tf10_i_prestadora|z01_nome|tf10_i_codigo|z01_numcgm'+
                            '&chave_tf10_i_prestadora='+document.form1.tf10_i_prestadora.value+'&nao_mostra=true',
                            'Pesquisa',false);

     } else {
       js_limpaInfoCgm();
     }

  }

}
function js_mostratfd_prestadora(chave1, chave2, chave3, chave4) {

  if(chave1 == '') {

    chave3 = '';
    chave4 = '';
    js_limpaInfoCgm();

  }
  document.form1.tf10_i_prestadora.value = chave1;
  document.form1.z01_nome2.value = chave2;
  document.form1.tf16_i_prestcentralagend.value = chave3;
  document.form1.tf09_i_numcgm.value = chave4;

  if($F('tf09_i_numcgm') != '') {
    js_getInfoCgm();
  }
  db_iframe_tfd_prestadoracentralagend.hide();

}

function js_pesquisatf10_i_centralagend(mostra) {

  if(mostra==true) {

    js_OpenJanelaIframe('','db_iframe_tfd_centralagendamento','func_tfd_centralagendamento.php?'+
                        'funcao_js=parent.js_mostratfd_centralagendamento1|tf09_i_codigo|z01_nome','Pesquisa',true);

  } else {

     if(document.form1.tf10_i_centralagend.value != '') {

        js_OpenJanelaIframe('','db_iframe_tfd_centralagendamento','func_tfd_centralagendamento.php?pesquisa_chave='+
                            document.form1.tf10_i_centralagend.value+
                            '&funcao_js=parent.js_mostratfd_centralagendamento','Pesquisa',false);

     } else {
       document.form1.z01_nome.value = '';
     }

  }

}
function js_mostratfd_centralagendamento(chave,erro) {

  document.form1.z01_nome.value = chave;
  if(erro==true) {

    document.form1.tf10_i_centralagend.focus();
    document.form1.tf10_i_centralagend.value = '';

  }
  js_limpaInfoCgm();

}
function js_mostratfd_centralagendamento1(chave1,chave2) {

  document.form1.tf10_i_centralagend.value = chave1;
  document.form1.z01_nome.value = chave2;
  js_limpaInfoCgm();
  db_iframe_tfd_centralagendamento.hide();

}

String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g,"");
}
/*
function js_pesquisatf16_i_prestcentralagend(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tfd_prestadoracentralagend','func_tfd_prestadoracentralagend.php?funcao_js=parent.js_mostratfd_prestadoracentralagend1|tf10_i_codigo|tf10_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.tf16_i_prestcentralagend.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_tfd_prestadoracentralagend','func_tfd_prestadoracentralagend.php?pesquisa_chave='+document.form1.tf16_i_prestcentralagend.value+'&funcao_js=parent.js_mostratfd_prestadoracentralagend','Pesquisa',false);
     }else{
       document.form1.tf10_i_codigo.value = '';
     }
  }
}
function js_mostratfd_prestadoracentralagend(chave,erro){
  document.form1.tf10_i_codigo.value = chave;
  if(erro==true){
    document.form1.tf16_i_prestcentralagend.focus();
    document.form1.tf16_i_prestcentralagend.value = '';
  }
}
function js_mostratfd_prestadoracentralagend1(chave1,chave2){
  document.form1.tf16_i_prestcentralagend.value = chave1;
  document.form1.tf10_i_codigo.value = chave2;
  db_iframe_tfd_prestadoracentralagend.hide();
}
function js_pesquisatf16_i_pedidotfd(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tfd_pedidotfd','func_tfd_pedidotfd.php?funcao_js=parent.js_mostratfd_pedidotfd1|tf01_i_codigo|tf01_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.tf16_i_pedidotfd.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_tfd_pedidotfd','func_tfd_pedidotfd.php?pesquisa_chave='+document.form1.tf16_i_pedidotfd.value+'&funcao_js=parent.js_mostratfd_pedidotfd','Pesquisa',false);
     }else{
       document.form1.tf01_i_codigo.value = '';
     }
  }
}
function js_mostratfd_pedidotfd(chave,erro){
  document.form1.tf01_i_codigo.value = chave;
  if(erro==true){
    document.form1.tf16_i_pedidotfd.focus();
    document.form1.tf16_i_pedidotfd.value = '';
  }
}
function js_mostratfd_pedidotfd1(chave1,chave2){
  document.form1.tf16_i_pedidotfd.value = chave1;
  document.form1.tf01_i_codigo.value = chave2;
  db_iframe_tfd_pedidotfd.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tfd_agendamentoprestadora','func_tfd_agendamentoprestadora.php?funcao_js=parent.js_preenchepesquisa|tf16_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tfd_agendamentoprestadora.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
*/
</script>