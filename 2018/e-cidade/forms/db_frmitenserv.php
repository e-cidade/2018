<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

//MODULO: cemiterio
$cltaxaserv->rotulo->label();
$clitenserv->rotulo->label();

$clrotulo->label("cm11_c_descr");
$clrotulo->label("cm01_i_codigo");
$clrotulo->label("nome");
$clrotulo->label("z01_nome");
$clrotulo->label("cm31_i_sepultamento");
$clrotulo->label("cm01_i_declarante");
$clrotulo->label("cm11_f_valor");

$dia = date('d',db_getsession("DB_datausu"));
$mes = date('m',db_getsession("DB_datausu"));
$ano = date('Y',db_getsession("DB_datausu"));

$dtAtual = date('Y-m-d',db_getsession("DB_datausu"));

if ( !isset($cm10_d_data_dia) && $db_opcao == 1 ) {

  $cm10_d_data_dia    = $dia;
  $cm10_d_data_mes    = $mes;
  $cm10_d_data_ano    = $ano;

  $cm10_d_privenc_dia = $dia;
  $cm10_d_privenc_mes = $mes;
  $cm10_d_privenc_ano = $ano;

  $cm10_d_dtlanc_dia  = $dia;
  $cm10_d_dtlanc_mes  = $mes;
  $cm10_d_dtlanc_ano  = $ano;
}

if( isset($cm31_i_sepultamento) ){

  $oDaoSepultamentos = db_utils::getDao('sepultamentos');

  $iCodigoSepultamento = $cm31_i_sepultamento;

  $sSqlSepultamentos = $oDaoSepultamentos->sql_query_dados_sepultamento($iCodigoSepultamento, 'cgm.z01_nome as sepultado, cgm3.z01_nome as declarante');
  $rsSepultamentos   = $oDaoSepultamentos->sql_record($sSqlSepultamentos);

  if( $rsSepultamentos ){

    $z01_nome          = db_utils::fieldsMemory($rsSepultamentos, 0)->sepultado;
    $cm01_c_declarante = db_utils::fieldsMemory($rsSepultamentos, 0)->declarante;
  }
}
?>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/numbers.js"></script>
<form name="form1" method="post" action="">
 <input type="hidden" name="cm10_i_numpre" value="<?=@$cm10_i_numpre?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcm10_i_codigo?>">
       <?=@$Lcm10_i_codigo?>
    </td>
    <td>
          <?
          db_input('cm10_i_codigo',10,$Icm10_i_codigo,true,'text',3,"")
          ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm31_i_sepultamento?>">
       <?
       db_ancora(@$Lcm31_i_sepultamento,"js_pesquisacm10_i_sepultamento(true);",$db_opcao);
       ?>
    </td>
    <td>
     <?
     db_input('cm31_i_sepultamento',10,$Icm31_i_sepultamento,true,'text',$db_opcao," onchange='js_pesquisacm10_i_sepultamento(false);'")
     ?>
     <?
     db_input('z01_nome',50,$Iz01_nome,true,'text',3,'')
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm01_i_declarante?>">
       <?
       db_ancora(@$Lcm01_i_declarante,"js_pesquisacm01_i_declarante(true);",3);
       ?>
    </td>
    <td>
     <?
     db_input('cm01_i_declarante',10,$Icm01_i_declarante,true,'text',3," onchange='js_pesquisacm01_i_declarante(false);' ")
     ?>
     <?
     db_input('cm01_c_declarante',50,@$Icm01_c_declarante,true,'text',3,'')
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm10_d_data?>">
       <?=@$Lcm10_d_data?>
    </td>
    <td>
			<?
				db_inputdata('cm10_d_data',@$cm10_d_data_dia,@$cm10_d_data_mes,@$cm10_d_data_ano,true,'text',3,"")
			?>
    </td>
  </tr>
</table>
<fieldset style="width: 600">
 <legend>Valores</legend>
<table>
  <tr>
    <td nowrap title="<?=@$Tcm10_i_taxaserv?>">
       <?=@$Lcm10_i_taxaserv?>
    </td>
    <td>
       <?
         $sWhere     = " (cm11_d_datalimite >= cast('{$dtAtual}' as date) or cm11_d_datalimite is null) ";
         $rsTaxaServ = $cltaxaserv->sql_record($cltaxaserv->sql_query(null,"*",null,$sWhere));

         $tx      = array();
         $tx[0]   = "Selecione";

         for ( $q = 0; $q < $cltaxaserv->numrows; $q++ ) {

           db_fieldsmemory($rsTaxaServ,$q);
           $tx[$cm11_i_codigo] = $cm11_c_descr;
         }

         db_select("cm10_i_taxaserv",$tx,true,$db_opcao,"onchange='js_pesquisaTaxaServicos();'");
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm10_f_valortaxa?>">
       <?=@$Lcm10_f_valortaxa?>
    </td>
    <td>
     <?
       db_input('cm10_f_valortaxa',10,$Icm10_f_valortaxa,true,'text',3,"")
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm10_f_valor?>">
       <?=@$Lcm10_f_valor?>
    </td>
    <td>
     <?
       db_input('cm10_f_valor',10,$Icm10_f_valor,true,'text',$db_opcao," onkeypress=\"return mascaraValor(event, this);\" onchange='js_habilitabotaocalcular();'");
     ?>
      <input name="calcular" type="button" id="calcular" value="Calcular" onclick="js_calcularValores();">
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm10_d_dtlanc?>">
      <?=@$Lcm10_d_dtlanc?>
    </td>
    <td>
      <?
        db_inputdata('cm10_d_dtlanc',@$cm10_d_dtlanc_dia,@$cm10_d_dtlanc_mes,@$cm10_d_dtlanc_ano,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm10_d_privenc?>">
       <?=@$Lcm10_d_privenc?>
    </td>
    <td>
			<?
			  db_inputdata('cm10_d_privenc',@$cm10_d_privenc_dia,@$cm10_d_privenc_mes,@$cm10_d_privenc_ano,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm10_t_obs?>">
       <?=@$Lcm10_t_obs?>
    </td>
    <td>
			<?
			  db_textarea('cm10_t_obs',3,50,$Icm10_t_obs,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
 </table>
 </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
         type="submit" id="db_opcao"
         value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
         <?=($db_botao==false?"disabled":"")?> onclick="return js_validar();">
</form>
<script>
function js_validar() {

  var nValorTaxa      = new Number(js_strToFloat($F('cm10_f_valortaxa')));
  var nValorCorrigido = new Number(js_strToFloat($F('cm10_f_valor')));

  if ( $F('cm10_f_valortaxa') == "" || nValorTaxa.valueOf() == 0 ) {

    alert('Campo valor taxa não informado!');
    return false;
  }
  if ($F('cm10_f_valor') == "" || nValorCorrigido.valueOf() == 0) {

    alert('Campo valor corrigido não informado!');
    return false;
  }
}

function js_habilitabotaocalcular() {

  var iValorTaxa = $('cm10_f_valortaxa').value;

  if ( iValorTaxa == "" ) {
    $('calcular').disabled      = true;
    $('cm10_f_valor').disabled  = true;
  } else {
    $('calcular').disabled      = false;
    $('cm10_f_valor').disabled  = false;
    $('cm10_f_valor').value =  js_formatar($('cm10_f_valor').value,'f');
  }
}

function js_pesquisaTaxaServicos() {

  js_divCarregando('Aguarde, Pesquisando Valores...','msgBoxTaxaServicos');

  var iCodTaxaServ       = $F('cm10_i_taxaserv');
  var dtLancamento       = $F('cm10_d_dtlanc');

  if ( iCodTaxaServ != 0 ) {
    $('cm10_i_taxaserv').options[0].disabled = true;
  }

  var oParam              = new Object();
      oParam.exec         = "listarTaxaServicos";
      oParam.codtaxaserv  = iCodTaxaServ;
      oParam.dtlancamento = dtLancamento;
  var oAjax               = new Ajax.Request(
                         "cem4_itensserv.RPC.php",
                        {
                          method    : 'post',
                          parameters: 'json='+Object.toJSON(oParam),
                          onComplete: js_retornoPesquisaTaxaServicos
                        }
                      );
}

function js_retornoPesquisaTaxaServicos(oAjax) {

  js_removeObj('msgBoxTaxaServicos');

  var aRetorno = eval("("+oAjax.responseText+")");

  $('cm10_f_valor').value = "";

  if ( aRetorno.numrows == 0 ) {

    alert('Nenhum valor cadastrado para taxa!');
    $('cm10_f_valortaxa').value = "";
    js_habilitabotaocalcular();
  } else {

    if ( aRetorno.oValorTaxa == 0 ) {

      alert('Nenhum valor cadastrado para taxa!');
      $('cm10_f_valortaxa').value = "";
      js_habilitabotaocalcular();
    } else {

      $('cm10_f_valortaxa').value = js_formatar(aRetorno.oValorTaxa,'f');
      js_habilitabotaocalcular();
    }
  }
}

function js_calcularValores() {

  var iCodTaxaServ        = $F('cm10_i_taxaserv');
  var dtLancamento        = $F('cm10_d_dtlanc');
  var dtVencimento        = $F('cm10_d_privenc');
  var iValorCorrigido     = $F('cm10_f_valortaxa');

  if ( iValorCorrigido == "" ) {
    alert("Nenhum valor taxa para calcular!");
    return false;
  }

  js_divCarregando('Aguarde, Calculando Valores...','msgBoxCalcular');

  if ( iCodTaxaServ != 0 ) {

	  var oParam              = new Object();
	      oParam.exec         = "calcularValores";
	      oParam.codtaxaserv  = iCodTaxaServ;
	      oParam.dtlancamento = dtLancamento;
	      oParam.dtvencimento = dtVencimento;
	      oParam.vlcorrigido  = iValorCorrigido;

	  var oAjax               = new Ajax.Request(
	                         "cem4_itensserv.RPC.php",
	                        {
	                          method    : 'post',
	                          parameters: 'json='+Object.toJSON(oParam),
	                          onComplete: js_retornoCalcularValores
	                        }
	                      );

  } else {

    js_removeObj('msgBoxCalcular');
    alert("Selecione uma taxa de serviço!");
  }
}

function js_retornoCalcularValores(oAjax) {

  js_removeObj('msgBoxCalcular');

  var aRetorno = eval("("+oAjax.responseText+")");

  if ( aRetorno.numrows == 0 ) {
    alert('Nenhum valor cadastrado para taxa!');
    $('cm10_f_valortaxa').value = "";
    $('cm10_f_valor').value     = "";
  } else {

    $('cm10_f_valor').value = js_formatar(aRetorno.oValorCorrigido,'f');
    js_habilitabotaocalcular();
  }
}

function js_pesquisacm10_i_sepultamento(mostra) {

  var sUrl1 = 'func_sepultamentos.php?funcao_js=parent.js_mostrasepultamentos1|cm01_i_codigo|z01_nome|cm01_i_declarante|cm01_c_declarante|cm01_d_falecimento';
  var sUrl2 = 'func_sepultamentos.php?pesquisa_chave='+$('cm31_i_sepultamento').value+'&dtfalecimento=true&funcao_js=parent.js_mostrasepultamentos';

  if ( mostra == true ) {
    js_OpenJanelaIframe('','db_iframe_sepultamentos',sUrl1,'Pesquisa',true);
  } else {

     if($('cm31_i_sepultamento').value != '') {
        js_OpenJanelaIframe('','db_iframe_sepultamentos',sUrl2,'Pesquisa',false);
     } else {
       $('z01_nome').value = '';
     }
  }
}

function js_mostrasepultamentos(chave1,chave2,chave3,chave4,erro) {

  $('z01_nome').value          = chave1;
  $('cm01_i_declarante').value = chave2;
  $('cm01_c_declarante').value = chave3;
  $('cm10_d_data').value       = js_formatar(chave4,'d');

  if ( erro == true ) {
    $('cm10_i_sepultamento').focus();
    $('cm10_i_sepultamento').value = '';
  }

  if ( chave3 != "" ) {
    js_pesquisacm01_i_declarante(false);
  }

  if ( chave4 != "" ) {
    $('cm10_d_dtlanc').value = js_formatar(chave4,'d');
  }

}

function js_mostrasepultamentos1(chave1,chave2,chave3,chave4,chave5) {

  $('cm31_i_sepultamento').value = chave1;
  $('z01_nome').value            = chave2;
  $('cm01_i_declarante').value   = chave3;
  $('cm10_d_data').value         = js_formatar(chave5,'d');

  if ( chave3 != "" ) {
    js_pesquisacm01_i_declarante(false);
  }

  if ( chave5 != "" ) {
    $('cm10_d_dtlanc').value = js_formatar(chave5,'d');
  }

  db_iframe_sepultamentos.hide();
}

function js_pesquisacm01_i_declarante(mostra) {

  var sUrl1 = 'func_cgm.php?funcao_js=parent.js_mostradeclarante1|z01_numcgm|z01_nome';
  var sUrl2 = 'func_cgm.php?pesquisa_chave='+$('cm01_i_declarante').value+'&funcao_js=parent.js_mostradeclarante';

  if ( mostra == true ) {
    js_OpenJanelaIframe('','db_iframe_declarante',sUrl1,'Pesquisa',true);
  } else {

   if ( document.form1.cm01_i_declarante.value != '' ) {
     js_OpenJanelaIframe('','db_iframe_declarante',sUrl2,'Pesquisa',false);
   } else {
     $('cm01_c_declarante').value = '';
   }
  }
}

function js_mostradeclarante(erro,chave1) {

  $('cm01_c_declarante').value = chave1;

  if ( erro == true ) {

    $('cm01_i_declarante').focus();
    $('cm01_i_declarante').value = '';
  }
}

function js_mostradeclarante1(chave1,chave2) {

  $('cm01_i_declarante').value = chave1;
  $('cm01_c_declarante').value = chave2;
  db_iframe_declarante.hide();
}

function js_preenchepesquisa(chave) {

  db_iframe_itenserv.hide();
  <?
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
  ?>
}
</script>