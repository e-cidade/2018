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

//MODULO: empenho
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("o56_elemento");
$clrotulo->label("e69_numero");

$clorctiporec->rotulo->label();
$clempempenho->rotulo->label();
$clorcdotacao->rotulo->label();
$clpagordemele->rotulo->label();
$clpagordemnota->rotulo->label();
$clempnota->rotulo->label();
$clempnotaele->rotulo->label();
$cltabrec->rotulo->label();
if ($tela_estorno){

   $operacao  = 2;//operacao a ser realizada:1 = liquidacao, 2 estorno
   $labelVal  = "SALDO A ESTORNAR";
   $metodo    = "estornarLiquidacaoAJAX";
   $sCredor   = "none";

}else{

   $operacao  = 1;//operacao a ser realizada:1 = liquidacao, 2 estorno
   $labelVal  = "SALDO A LIQUIDAR";
   $metodo    = "liquidarAjax";
   $sCredor   = "normal";
}
$db_opcao_inf=1;
?>
<style>
.tr_tab{
  background-color:white;
  font-size: 8px;
  height : 8px;
}
</style>
<form name=form1 action="" method="POST">
 <input type=hidden name=retencoes value ="">
 <input type=hidden name=e69_codnota value="<?=@$e69_codnota ?>">
 <center>
<table>
 <tr>
 <td valign="top">
 <fieldset><legend><b>Empenho</b></legend>
    <table >
          <tr>
            <td nowrap><?=db_ancora($Le60_codemp,"js_JanelaAutomatica('empempenho',\$F('e60_numemp'))",$db_opcao_inf)?></td>
            <td nowrap><? db_input('e60_codemp', 13, $Ie60_codemp, true, 'text', 3)?> </td>
            <td nowrap><?=db_ancora($Le60_numemp,"js_JanelaAutomatica('empempenho',\$F('e60_numemp'))",$db_opcao_inf)?></td>
            <td><? db_input('e60_numemp', 13, $Ie60_numemp, true, 'text', 3)?> </td>
          </tr>
          <tr>
            <td><?=db_ancora($Le60_numcgm,"js_JanelaAutomatica('cgm',\$F('e60_numcgm'))",$db_opcao_inf)?></td>
            <td><? db_input('e60_numcgm', 13, $Ie60_numcgm, true, 'text', 3); ?> </td>
            <td colspan=2><? db_input('z01_nome', 50, $Iz01_nome, true, 'text', 3, '');?></td>
          </tr>
          <tr style='display:<?=$sCredor?>'>
            <td><?=db_ancora('<b>Credor:</b>',"js_pesquisae49_numcgm(true)",1)?></td>
            <td><? db_input('e49_numcgm', 13, $Ie60_numcgm, true, 'text', 1,"onchange='js_pesquisae49_numcgm(false)'"); ?> </td>
            <td colspan=2><? db_input('z01_credor', 50, $Iz01_nome, true, 'text', 3, '');?></td>
          </tr>
          <tr>
            <td><?=db_ancora($Le60_coddot,"js_JanelaAutomatica('orcdotacao',\$F('e60_coddot'),'".@$e60_anousu."')",$db_opcao_inf)?></td>
            <td><? db_input('e60_coddot', 13, $Ie60_coddot, true, 'text', 3); ?></td>
            <td width="20"><?=db_ancora($Lo15_codigo,"",3)?></td>
            <td><? db_input('o15_codigo', 5, $Io15_codigo, true, 'text', 3); db_input('o15_descr', 29, $Io15_descr, true, 'text', 3)?></td>
          </tr>
     <?
      echo " <tr> ";
      echo "    <td>&nbsp;</td> ";
      echo "    <td colspan='3'> ";
       if ($operacao == 1 ) {

         echo "       <input type='checkbox' checked id='emitedocumento'> ";
         echo "       <label for='emitedocumento'>Emitir Ordem de Pagamento</label> ";

       }
        echo "   &nbsp; </td> ";
        echo " </tr> ";
        echo " <tr> ";
        echo "    <td>&nbsp;</td> </tr>";
        if ($operacao == 2 ) {

          echo " <tr> ";
          echo "    <td>&nbsp;</td> </tr>";
        }
      ?>
        </table>
        </fieldset>
  </td>
  <td rowspan="1" valign="top" style='height:100%;'>
 <fieldset><legend><b>Valores do Empenho</b></legend>
    <table >
          <tr><td nowrap><?=@$Le60_vlremp?></td><td align=right><? db_input('e60_vlremp', 12, $Ie60_vlremp, true, 'text', 3, '','','','text-align:right')?></td></tr>
          <tr><td nowrap><?=@$Le60_vlranu?></td><td align=right><? db_input('e60_vlranu', 12, $Ie60_vlranu, true, 'text', 3, '','','','text-align:right')?></td></tr>
          <tr><td nowrap><?=@$Le60_vlrliq?></td><td align=right><? db_input('e60_vlrliq', 12, $Ie60_vlrliq, true, 'text', 3, '','','','text-align:right')?></td></tr>
          <tr><td nowrap><?=@$Le60_vlrpag?></td><td align=right><? db_input('e60_vlrpag', 12, $Ie60_vlrpag, true, 'text', 3, '','','','text-align:right')?></td></tr>
          <tr><td colspan=2 align=center class=table_header><?=$labelVal?></td></tr>
          <tr>
            <td><b> SALDO </b></td>
            <td align=right>
              <?
              if($db_opcao==3){
                @$saldo_disp = db_formatar(($e53_valor-$e53_vlranu-$e53_vlrpag),'p');
              }else{
                @$saldo_disp = db_formatar(($e60_vlremp-$e60_vlranu-$e60_vlrliq),'p');
              }
              db_input('saldo_disp', 12, $Ie60_vlrpag, true, 'text', 3, '','','','text-align:right');
              ?>
            </td>
          </tr>
     </table>
     </fieldset>
  </td>
  </tr>
  <tr>
  <td colspan="2">
 <fieldset><legend><b>Notas</b></legend>
       <div style='border:2px inset white'>
        <table  cellspacing=0 cellpadding=0 width='100%'>
          <tr>
            <th class='table_header'>
	          <input type='checkbox'  style='display:none' id='mtodos' onclick='js_marca()'>
           	<a onclick='js_marca()' style='cursor:pointer'>M</a></b></td>
            <th class='table_header'>Seq. Nota</th>
            <th class='table_header'>Nota Fiscal</th>
            <th class='table_header'>Data</th>
            <th class='table_header'>Valor</th>
            <th class='table_header'>Anulado</th>
            <th class='table_header'>Liquidado</th>
            <th class='table_header'>Pago</th>
            <th class='table_header'>Retido</th>
            <th class='table_header' width="18">&nbsp;</th>
          </tr>
          <tbody id='dados' style='height:150;width:95%;overflow:scroll;overflow-x:hidden;background-color:white'>
          </tbody>
        </table>
        </div>
        </fieldset>
  </td>
  </tr>
  <tr>
  <td colspan="2px">
 <fieldset><legend><b>Hist�rico</b></legend>
   <table width="100%">
        <tr>
          <td>
          <?
          db_textarea('historico',4,110,0,true,'text',1,"");
          ?>
          </td>
        </tr>
    </table>
    </fieldset>
    </td>
  </tr>
</table>
 <input name="confirmar" type="button" id="confirmar" value="Confirmar" onclick="return js_liquidar('<?=$metodo?>')" >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa('');" >
</center>
  <? db_input("receitas_valores",30,"",true,'hidden',1,'readonly','','','text-align:right')?>
  <? db_input("valor_liquidar",30,"",true,'hidden',1,'readonly','','','text-align:right')?>
</form>
<script>
iOperacao     = <?=$operacao;?>;
lPesquisaFunc = false;
function js_emitir(codordem){
  jan = window.open('emp2_emitenotaliq002.php?codordem='+codordem,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
}
function js_pesquisa(iNumEmp) {
  if (iNumEmp == '') {
    js_OpenJanelaIframe('top.corpo', 'db_iframe_empempenho', 'func_empempenho.php?funcao_js=parent.js_preenchepesquisa|e60_numemp', 'Pesquisa', true);
  } else {
    js_consultaEmpenho(iNumEmp,<?=$operacao?>);
  }
}
function js_preenchepesquisa(chave){
  db_iframe_empempenho.hide();
  js_consultaEmpenho(chave,<?=$operacao?>);
  lPesquisaFunc = true; //mostramos as mensagens se o usu�rio clicou na func.
}

function js_marca(){

	 obj = document.getElementById('mtodos');
	 if (obj.checked){
		 obj.checked = false;
	}else{
		 obj.checked = true;
	}
   itens = js_getElementbyClass(form1,'chkmarca');
	 for (i = 0;i < itens.length;i++){
     if (itens[i].disabled == false){
        if (obj.checked == true){
					itens[i].checked=true;
          js_marcaLinha(itens[i]);
       }else{
					itens[i].checked=false;
          js_marcaLinha(itens[i]);
			 }
     }
	 }
}

/**
 * Fun��o que busca os dados do empenho
 */
function js_consultaEmpenho(iEmpenho,operacao){

   js_divCarregando("Aguarde, efetuando pesquisa","msgBox");
   strJson = '{"method":"getEmpenhos","pars":"'+iEmpenho+'","operacao":"'+operacao+'","iEmpenho":"'+iEmpenho+'"}';
   $('dados').innerHTML    = '';
   //$('pesquisar').disabled = true;
   url     = 'emp4_liquidacao004.php';
   oAjax   = new Ajax.Request(
                            url,
                              {
                               method: 'post',
                               parameters: 'json='+strJson,
                               onComplete: js_saida
                              }
                             );

}

 /**
  * Preenche o formul�rio com os dados do empenho
  */
function js_saida(oAjax){

   js_removeObj("msgBox");
    var iNumEmpOld = $F('e60_numemp');
    obj  = eval("("+oAjax.responseText+")");
    $('e60_codemp').value = obj.e60_codemp;
    $('e60_numemp').value = obj.e60_numemp;
    $('e60_numcgm').value = js_decodeUrl(obj.e60_numcgm);
    $('z01_nome').value   = js_decodeUrl(obj.z01_nome);
    $('e49_numcgm').value = '';
    $('z01_credor').value = '';
    $('e60_coddot').value = js_decodeUrl(obj.e60_coddot);
    $('o15_codigo').value = obj.o58_codigo;
    $('o15_descr').value  = js_decodeUrl(obj.o15_descr);
    $('e60_vlremp').value = obj.e60_vlremp;
    $('e60_vlranu').value = obj.e60_vlranu;
    $('e60_vlrpag').value = obj.e60_vlrpag;
    $('e60_vlrliq').value = obj.e60_vlrliq;
    $('historico').value  = obj.e60_resumo.urlDecode();
    $('saldo_disp').value = obj.saldo_dis;
    saida                 = '';
    iTotNotas             = 0;
    $('dados').innerHTML  = '';

    if (obj.aItensPendentesPatrimonio.length > 0) {

      oDBViewNotasPendentes = new DBViewNotasPendentes('oDBViewNotasPendentes');
      oDBViewNotasPendentes.setCodigoNota(obj.aItensPendentesPatrimonio);
  	  oDBViewNotasPendentes.show();
    }

    if (obj.numnotas > 0) {

      for (var i = 0; i < obj.data.length; i++) {

        sClassName = 'normal';
        if (obj.data[i].libera == 'disabled') {
          sClassName = ' disabled ';
        }

        if (in_array(obj.data[i].e69_codnota, obj.aItensPendentesPatrimonio)) {
          sClassName = ' disabled ';
        }

        if (iOperacao == 1) { //liquidacao

          var nSaldoNota = (js_strToFloat(obj.data[i].e70_valor)-js_strToFloat(obj.data[i].e70_vlrliq) -
		                        js_strToFloat(obj.data[i].e70_vlranu)-js_strToFloat(obj.data[i].e53_vlrpag)).toFixed(2);

        } else if (iOperacao == 2) { //estorno
          if (js_strToFloat(obj.data[i].e53_vlrpag) > 0) {
            var nSaldoNota = 0;
          } else {
            var nSaldoNota = (js_strToFloat(obj.data[i].e70_vlrliq)-js_strToFloat(obj.data[i].e70_vlranu)).toFixed(2);
          }
        }

        if (nSaldoNota > 0) {

          iTotNotas++;
          nValorRetencao = obj.data[i].vlrretencao;
          numnota        = js_decodeUrl(obj.data[i].e69_numero);
          iCodOrd        = obj.data[i].e50_codord;
          saida += "<tr class='" + sClassName + "' id='trchk" + obj.data[i].e69_codnota + "' style='height:1em'>";
          saida += "<td class='linhagrid' style='text-align:center'>";
          saida += "<input type='hidden' id='sInfoAgenda"+obj.data[i].e69_codnota+"' value='"+js_decodeUrl(obj.data[i].sInfoAgenda)+"'>";
          saida += "<input type='checkbox' " + obj.data[i].libera + " onclick='js_marcaLinha(this)'";
          saida += " class='chkmarca' name='chk" + obj.data[i].e69_codnota + "'";
          saida += " id='chk" + obj.data[i].e69_codnota + "' value='" + obj.data[i].e69_codnota + "' "+sClassName+"></td>";
          saida += "<td class='linhagrid' style='text-align:center'><b>"
          saida += "<a href='' onclick='js_consultaNota("+obj.data[i].e69_codnota+");return false'>";
          saida += obj.data[i].e69_codnota + "</a></b></td>";
          saida += "<td class='linhagrid' style='text-align:center' id='numero"+obj.data[i].e69_codnota+"' >" + numnota + "</td>";
          saida += "<td class='linhagrid' style='text-align:center'>" + obj.data[i].e69_dtnota + "</td>";
          saida += "<td class='linhagrid' style='text-align:center'>" + obj.data[i].e70_valor + "</td>";
          saida += "<td class='linhagrid' style='text-align:right;width:10%'>" + obj.data[i].e70_vlranu + "</td>";
          saida += "<td class='linhagrid' style='text-align:right;width:10%'>" + obj.data[i].e70_vlrliq + "</td>";
          saida += "<td class='linhagrid' style='text-align:right;width:10%'>" + obj.data[i].e53_vlrpag + "</td>";
          saida += "<td class='linhagrid' style='text-align:right;width:10%'>";
          if (iOperacao == 1) {

            saida += "<a href='' id='retencao"+obj.data[i].e69_codnota+"' ";
            saida += "   onclick='js_lancarRetencao("+obj.data[i].e69_codnota+",\""+iCodOrd+"\",\""+obj.data[i].e70_valor+"\");";
            saida += "return false;'>"+nValorRetencao+"</a>";
          } else {
            saida += nValorRetencao;
          }
          saida += "</td></tr>";

        }
      }

      obj.data.each(function (oCodigoNota, iLinha) {

        if (in_array(oCodigoNota.e69_codnota, obj.aItensPendentesPatrimonio)) {
					alert("Os bens v�nculados a esta nota est�o pendentes de tombamento ou dispensa de tombamento no m�dulo patrimonial! ");
        }
      });

      $('confirmar').disabled = false;
    } else {

      alert("Empenho sem notas lan�adas ou pendentes de inclus�o no m�dulo patrimonial.");
      $('confirmar').disabled = true;
    }
    saida += "<tr style='height:auto'><td colspan='10'>&nbsp;</td></tr>";
    $('dados').innerHTML  = saida;
    if (iTotNotas  == 0) {
      if (iOperacao == 1 ) {
        var sAcao = "liquidar";
      }else if (iOperacao == 2) {
        var sAcao = "estornar";
      }
      $('confirmar').disabled = true;
    }
    if (js_strToFloat(obj.saldo_dis) == 0) {

      if (lPesquisaFunc) {
        alert("Empenho sem <?= strtolower($labelVal);?>");
      }
      $('confirmar').disabled = true;
    }
    $('pesquisar').disabled = false;


}
function js_marcaLinha(obj){

  if (obj.checked){

    $('tr'+obj.id).className='marcado';
  }else{

   $('tr'+obj.id).className='normal';

  }

}

function js_liquidar(metodo){


   itens = js_getElementbyClass(form1,'chkmarca');
   notas = '';
   sV    = '';
   $('pesquisar').disabled = true;
   $('confirmar').disabled = true;
   var aNotas = new Array();
   for (i = 0;i < itens.length;i++){
     if (itens[i].checked == true){
        aNotas.push(itens[i].value);
      }
   }

   if (aNotas.length != 0){

     if (metodo == "estornarLiquidacaoAJAX") {

       var sMensagem = "Aguarde, estornando liquidacao das notas";
       if (!confirm("Confirma o estorno da liquida��o?")) {

         $('pesquisar').disabled = false;
         $('confirmar').disabled = false;
         return false;
       }

     } else {
       var sMensagem = "Aguarde, Liquidando notas";
     }
     js_divCarregando(sMensagem, "msgLiq");
     //strJson  = '{"method":"'+metodo+'","iEmpenho":"'+$F('e60_numemp')+'","notas":['+notas+'],"historico":"'+encodeURIComponent($F('historico'))+'","';
     //strJson += '"pars":"'+$F('e60_numemp')+',"z01_credor":"'+$F('e49_numcgm')+'"}';

     var oParam        = new Object();
     oParam.method     = metodo;
     oParam.iEmpenho   = $F('e60_numemp');
     oParam.notas      = aNotas;
     oParam.historico  = encodeURIComponent($F('historico'));
     oParam.pars       = $F('e60_numemp');
     oParam.z01_credor = $F('e49_numcgm');

     url      = 'emp4_liquidacao004.php';
     oAjax    = new Ajax.Request(
                            url,
                              {
                               method: 'post',
                               parameters: 'json='+Object.toJSON(oParam),
                               onComplete: js_saidaLiquidacao
                              }
                             );
   } else{

     alert('Selecione ao menos 1 (uma) nota para liquidar');
     $('pesquisar').disabled = false;
     $('confirmar').disabled = false;

   }
}

function js_saidaLiquidacao(oAjax){

    js_removeObj("msgLiq");
    $('pesquisar').disabled = false;
    $('confirmar').disabled = false;
    obj      = eval("("+oAjax.responseText+")");
    mensagem = obj.mensagem.replace(/\+/g," ");
    mensagem = unescape(mensagem);
    if (obj.erro == 2){
       alert(mensagem);
    }
    if (obj.erro == 1){
       if (document.getElementById('emitedocumento') && $('emitedocumento').checked) {
         js_emitir(obj.sOrdensGeradas);
       }
       lPesquisaFunc = false;
       js_consultaEmpenho($F('e60_numemp'),<?=$operacao?>);

    }
}
function js_decodeUrl(sTexto){

   texto = sTexto.replace(/\+/g," ");
   texto = unescape(texto);
   return texto;

}
function js_pesquisae49_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.e49_numcgm.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.e49_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_credor.value = '';
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_credor.value = chave;
  if(erro==true){
    document.form1.e49_numcgm.focus();
    document.form1.e49_numcgm.value = '';
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.e49_numcgm.value = chave1;
  document.form1.z01_credor.value = chave2;
  db_iframe_cgm.hide();
}

function removerObj(id) {

   obj = $(id);
   parent
   parent = obj.parentNode;
   parent.removeChild(obj);
}

function js_consultaNota(iCodNota) {
  js_OpenJanelaIframe('top.corpo', 'db_iframe_nota', 'emp2_consultanotas002.php?e69_codnota='+iCodNota, 'Pesquisa Dados da Nota', true);
}

function js_emitir(codordem){

  jan = window.open('emp2_emitenotaliq002.php?codordem='+codordem,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
}

function js_lancarRetencao(iCodNota, iCodOrd, nValor){

   var iNumEmp  = $F('e60_numemp');
   var lSession = <?=$operacao==2?"false":"true"?>;
   var iNumCgm  = $F('e49_numcgm');
   $('e49_numcgm').disabled = true;
   js_OpenJanelaIframe('top.corpo', 'db_iframe_retencao',
                       'emp4_lancaretencoes.php?iNumNota='+iCodNota+'&iNumEmp='+iNumEmp+'&iCodOrd='+iCodOrd+
                       '&iNumCgm='+iNumCgm+
                       "&lSession="+lSession+"&nValorBase="+js_strToFloat(nValor)+"&iCodMov=&callback=true",
                       'Lancar Reten��es', true);

}

function js_atualizaValorRetencao(iCodMov, nValor, iNota, iCodOrdem) {

   if (nValor > 0) {
     $('e49_numcgm').disabled      = true;
   } else {
     $('e49_numcgm').disabled      = false;
   }
   $('retencao'+iNota).innerHTML = js_formatar(nValor,'f');
   db_iframe_retencao.hide();

}

/**
 * Fun��o que simula o in_array do PHP
 */
function in_array(valor,vetor){

  for (var i in vetor) {

    if (valor == vetor[i]) {
      return true;
    }
  }
  return false;
};
$('historico').style.width ='100%';
$("o15_descr").style.width = "222px";
</script>