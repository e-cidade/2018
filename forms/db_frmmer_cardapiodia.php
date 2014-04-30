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

//MODULO: educaï¿½ï¿½o
$clmer_cardapiodia->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("me01_i_codigo");
$clrotulo->label("ed32_i_codigo");
$clrotulo->label("me37_i_tipocardapio");

?>
<form name="form1" method="post" action="">
<center>
<table border="0" align="center" width="100%">
 <tr>
  <td valign="top" align="center">
   <table border="0" align="center">
    <tr>
     <td valign="top" align="right">
      <b>Cardápio:</b>
     </td>
     <td> 
      <?
      $hoje = date("Y-m-d",db_getsession("DB_datausu"));
      $result_cardapio = $clmer_tipocardapio->sql_record($clmer_tipocardapio->sql_query("",
                                                                                          "me27_i_codigo,me27_c_nome,me27_f_versao,me27_i_id",
                                                                                          "me27_i_id,me27_f_versao desc",
                                                                                          "((me27_d_inicio is not null 
                                                                                             and me27_d_fim is null
                                                                                             and me27_d_inicio <= '$hoje') 
                                                                                            or (me27_d_fim is not null and '$hoje'
                                                                                                between me27_d_inicio and me27_d_fim))"
                                                                                         ));
                                                                                         ?>
      <select name="cardapio" id="cardapio"   onChange="js_pesquisa_refeicao(this.value);js_carrega_iframe(this.value);"
              style="height:18px;font-size:9px;">
      <option value="0"></option>
      <?
      for ($t = 0; $t < $clmer_tipocardapio->numrows; $t++) {
        
        db_fieldsmemory($result_cardapio,$t);
        ?>
        <option value="<?=$me27_i_codigo?>"><?=$me27_c_nome?> - Versão: <?=$me27_f_versao?></option>
        <?
             
      }
      ?>
      </select>
     </td>
     <td align="right"> 
      <b>Mês:</b>
     </td>
     <td> 
      <select name="mes" id="mes" onchange="js_carrega();"  style="font-size:9px;height:18px;">
       <option value="0" <?=@$mes=="0"?"selected":""?>></option>
       <option value="01" <?=@$mes=="01"?"selected":""?>>JANEIRO</option>
       <option value="02" <?=@$mes=="02"?"selected":""?>>FEVEREIRO</option>
       <option value="03" <?=@$mes=="03"?"selected":""?>>MARÇO</option>
       <option value="04" <?=@$mes=="04"?"selected":""?>>ABRIL</option>
       <option value="05" <?=@$mes=="05"?"selected":""?>>MAIO</option>
       <option value="06" <?=@$mes=="06"?"selected":""?>>JUNHO</option>
       <option value="07" <?=@$mes=="07"?"selected":""?>>JULHO</option>
       <option value="08" <?=@$mes=="08"?"selected":""?>>AGOSTO</option>
       <option value="09" <?=@$mes=="09"?"selected":""?>>SETEMBRO</option>
       <option value="10" <?=@$mes=="10"?"selected":""?>>OUTUBRO</option>
       <option value="11" <?=@$mes=="11"?"selected":""?>>NOVEMBRO</option>
       <option value="12" <?=@$mes=="12"?"selected":""?>>DEZEMBRO</option>
      </select>
     </td>
    </tr>
    <tr> 
     <td align="right"> 
      <b>Semana:</b>
     </td>
     <td> 
      <select name="semana" id="semana" onchange="js_carrega_iframe();" style="font-size:9px;height:18px;width:200px;">
       <option value="0"></option>
      </select>
     </td>
     <td align="right"> 
      <b>Dia da Semana:</b>
     </td>
     <td> 
      <?
      $result_dias = $cldiasemana->sql_record(
                                              $cldiasemana->sql_query_rh("",
                                                                         "ed32_i_codigo,ed32_c_descr",
                                                                         "ed32_i_codigo",
                                                                         " ed04_i_escola = $escola"
                                                                        )
                                             );
      ?> 
      <select name="diasemana" id="diasemana" onchange="js_carrega_iframe();" style="font-size:9px;height:18px;">
       <option value="8">TODOS</option>
       <?for ($t = 0; $t < $cldiasemana->numrows; $t++) {
           db_fieldsmemory($result_dias,$t);
        ?>
           <option value="<?=($ed32_i_codigo-1)?>"><?=$ed32_c_descr?></option>
       <?}?>
      </select>
     </td>
    </tr>
    <tr> 
     <td valign="top" align="center" colspan="4">
      <hr>
      <div id="div_refeicao" style="visibility:hidden;">
      <b>Refeição:</b>
      <select name="refeicao" id="refeicao" style="font-size:9px;height:18px;width:200px;" onchange="js_refeicao(this.value);">
      </select>
      </div>
      <div id="div_tiporefeicao" style="visibility:hidden;">
       <table>
        <tr>
         <td valign="top"  rowspan="8">
          <b>Tipo de Refeição:</b>
         </td>
         <td>
          <div id="texto_tiporefeicao"></div>
          <input type="hidden" name="tiporefeicao" id="tiporefeicao" value="" style="background:#DEB887;">
         </td>
        </tr>
       </table>
      </div>
      <div id="div_itemrefeicao" >
      </div>
     </td>
    </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <hr>
   <table width="100%">
    <tr>
     <td align="center">
      <div id="div_grid"></div>
     </td>
    </tr>
    <tr>
     <td align="left">
      <div id="div_itemrefeicaograde" ></div>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</center>
<div id="div_inclusao" style="top:220px;left:500px;position:absolute;"></div>
<script>
function js_refeicao(refeicao) {

  $('div_inclusao').innerHTML      = "";	
  if (refeicao == "0") {
	  
	$('texto_tiporefeicao').innerHTML      = "";
	$('tiporefeicao').value                = "";
	$('div_tiporefeicao').style.visibility = "hidden";
	$('div_itemrefeicao').style.visibility = "hidden";
	return false;
	
  }
  
  var sAction = 'PesquisaTipoRefeicao';
  var url     = 'mer4_mer_cardapiodiaRPC.php';
  var oAjax = new Ajax.Request(url,{ method    : 'post',
	                                 parameters: 'refeicao='+refeicao+'&sAction='+sAction,
	                                 onComplete: js_retornoPesquisaTipoRefeicao
	                               });
  var sAction = 'PesquisaItemRefeicao';
  var url     = 'mer4_mer_cardapiodiaRPC.php';
  var oAjax = new Ajax.Request(url,{method    : 'post',
	                                    parameters: 'refeicao='+refeicao+'&sAction='+sAction,
	                                    onComplete: js_retornoPesquisaItemRefeicao
	                               });
}


function js_pesquisa_refeicao(codcardapio) {

  $('div_tiporefeicao').style.visibility = "hidden";
  $('div_itemrefeicao').style.visibility = "hidden";
  var sAction = 'PesquisaRefeicao';
  var url     = 'mer4_mer_cardapiodiaRPC.php';
  var oAjax = new Ajax.Request(url,{ method    : 'post',
                                     parameters: 'codcardapio='+codcardapio+'&sAction='+sAction,
                                     onComplete: js_retornoPesquisaRefeicao
                                   });
  
}
function js_retornoPesquisaRefeicao(oAjax) {
    
  var oRetorno = eval("("+oAjax.responseText+")");
  document.form1.refeicao.length = null;  
  if (oRetorno.length==0) {
    document.form1.refeicao.options[document.form1.refeicao.length] = new Option("Nenhuma refeição para este cardápio","0");
  } else {

    document.form1.refeicao.options[document.form1.refeicao.length] = new Option("","0");
    for (var i = 0;i < oRetorno.length; i++) {
	        
      with (oRetorno[i]) {
	              
          document.form1.refeicao.options[document.form1.refeicao.length] = new Option(me01_c_nome.urlDecode()+" - Versão: "+me01_f_versao,me01_i_codigo);
	            
      }
         
    }

  }
  
}

function js_retornoPesquisaTipoRefeicao(oAjax) {
	
  var oRetorno = eval("("+oAjax.responseText+")");
  iCodigos     = '';
  vTexto       = '';
  hHorafim     = '';
  sep1         = '';
  sep2         = '';
  for (var i = 0;i < oRetorno.length; i++) {
	  
	with (oRetorno[i]) {
		
	  iCodigos   += sep1+me03_i_codigo.urlDecode();
	  hHorafim   += sep1+me03_c_fim.urlDecode();
	  vTexto     += sep2+me03_c_tipo.urlDecode();
	  sep1        = ",";
	  sep2        = " | ";
	  
	}
  }
  $('texto_tiporefeicao').innerHTML      = vTexto;
  $('tiporefeicao').value                = iCodigos;
  $('div_tiporefeicao').style.visibility = "visible";
  $('div_itemrefeicao').style.visibility = "visible";
}

function js_retornoPesquisaItemRefeicao(oAjax) {
	
  var oRetorno = eval("("+oAjax.responseText+")");
  sHtml  = '<table border="1" cellspacing="0" cellpadding="2">';
  sHtml += '<tr align="center" bgcolor="#999999"><td colspan="2"><b>'+document.form1.refeicao.options[document.form1.refeicao.selectedIndex].text+'</b></td></tr>';
  sHtml += '<tr align="center" bgcolor="#999999"><td><b>Item</b></td><td><b>Qtde.</b></td></tr>';
  for (var i = 0;i < oRetorno.length; i++) {
	  
	with (oRetorno[i]) {
	  sHtml += '<tr bgcolor="#f3f3f3"><td>'+me35_c_nomealimento.urlDecode()+'</td><td align="center">'+me07_f_quantidade.urlDecode()+'</td></tr>';
	}
  }
  sHtml += '</table>';
  $('div_itemrefeicao').innerHTML = sHtml;
}

function js_carrega() {

  if ($('cardapio').value=="0") {
   alert("Informe o cardapio!");
   document.form1.mes.value = "0";
   return false;
  }
  $('div_grid').style.visibility         = "hidden";
  $('div_refeicao').style.visibility     = "hidden";
  $('div_tiporefeicao').style.visibility = "hidden";
  $('div_itemrefeicao').style.visibility = "hidden";
  $('div_inclusao').innerHTML = "";
  new Ajax.Request('mer4_mer_cardapiodia_combo003.php?mes='+document.form1.mes.value+'&cardapio='+$('cardapio').value,
		          {
                    method : 'get',
	                onComplete : function(transport){
	                 document.form1.semana.innerHTML = transport.responseText;
	                }
	              });
}

function js_carrega_iframe() {
	
  diasemana    = document.form1.diasemana.value;
  mes          = document.form1.mes.value;
  semana       = document.form1.semana.value;
  tp_refeicao  = document.form1.tiporefeicao.value;
  cardapio     = document.form1.cardapio.value;
  $('div_inclusao').innerHTML = "";
  if (cardapio == '0' || mes == '0' || semana == '') {
	  
	$('div_grid').style.visibility         = "hidden";
	$('div_refeicao').style.visibility     = "hidden";
	$('div_tiporefeicao').style.visibility = "hidden";
    $('div_itemrefeicao').style.visibility = "hidden";
	
  }
  parametros = '';
  if (cardapio != '0' && mes != '0' && semana != '') {
	  
	parametros = 'semana='+semana+'&mes='+mes+'&cardapio='+cardapio+'&diasemana='+diasemana;
	if (parametros != '') {
		
	  js_divCarregando("Aguarde, carregando registros","msgBox");
	  var sAction = 'MontaGrid';
	  var url     = 'mer4_mer_cardapiodiaRPC.php';
	  var oAjax = new Ajax.Request(url,{method    : 'post',
	                                     parameters: parametros+'&sAction='+sAction,
	                                     onComplete: function(oAjax) {js_retornoMontagrid(oAjax);}
	                                   });
	}
  }
}

function js_retornoMontagrid(oAjax) {

  js_removeObj("msgBox");
  var oRetorno                       = eval("("+oAjax.responseText+")");
  $('div_grid').innerHTML            = oRetorno.urlDecode();
  $('div_grid').style.visibility     = "visible";
  $('div_refeicao').style.visibility = "visible";
  if ($('refeicao').value != "0") {
	  
	$('div_tiporefeicao').style.visibility = "visible";
	$('div_itemrefeicao').style.visibility = "visible";
	
  }
}
	
function js_restaurar() {
  js_carrega_iframe();
}
function js_incluiregistro(codtprefeicao,data,evt) {
	
  if( typeof(event) != "object" ) {
	PosMouseX = evt.layerX;
	PosMouseY = evt.layerY;
  } else {
    PosMouseX = event.x;
    PosMouseY = event.y;
  }
  $('div_inclusao').style.top = PosMouseY;
  $('div_inclusao').style.left = PosMouseX;
  $('div_inclusao').innerHTML = "";
  if (document.form1.refeicao.value=="0") {
    alert("Informe a refeição!");
  } else {

    js_divCarregando("Aguarde, verificando registros","msgBox");
    var sAction = 'VerificaRegistro';
    var url     = 'mer4_mer_cardapiodiaRPC.php';
    parametros = 'tiporefeicao='+codtprefeicao+'&data='+data+'&refeicao='+document.form1.refeicao.value;
    var oAjax = new Ajax.Request(url,{method    : 'post',
                                      parameters: parametros+'&sAction='+sAction,
                                      onComplete: js_retornoVerificaRegistro
                                     });

  }
	
}
function js_retornoVerificaRegistro(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno[0].urlDecode()!="0") {
    alert(oRetorno[0].urlDecode());
  } else {

    js_divCarregando("Aguarde, abrindo tela","msgBox");
    var sAction = 'MontaInclusao';
    var url     = 'mer4_mer_cardapiodiaRPC.php';
    dados = oRetorno[1].urlDecode().split("|");
    parametros  = 'tiporefeicao='+dados[0]+'&data='+dados[1]+'&refeicao='+document.form1.refeicao.value+'&cardapio='+document.form1.cardapio.value;
    var oAjax = new Ajax.Request(url,{method    : 'post',
                                      parameters: parametros+'&sAction='+sAction,
                                      onComplete: js_retornoMontaInclusao
                                     });
  
  }

}
function js_retornoMontaInclusao(oAjax) {
    
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  $('div_inclusao').innerHTML = oRetorno.urlDecode();	  
  
}
function js_salvaregistro (tprefeicao,data,refeicao) {

  tam = document.form1.checkescola.length;
  codescolas = "";
  sep = "";  
  if (tam==undefined) {

	if (document.form1.checkescola.checked==true && document.form1.checkescola.disabled==false) {
      codescolas += document.form1.checkescola.value;
    }

  } else {

    for (i=0;i<tam;i++) {

      if (document.form1.checkescola[i].checked==true && document.form1.checkescola[i].disabled==false) {
          
        codescolas += sep+document.form1.checkescola[i].value;
        sep = "|";
        
      }

    }

  }
  if(codescolas==""){
    alert("Informe alguma escola!");
  } else {

    js_divCarregando("Aguarde, incluindo registro","msgBox");
    var sAction = 'IncluiRefeicao';
    var url     = 'mer4_mer_cardapiodiaRPC.php';
    parametros  = 'tiporefeicao='+tprefeicao+'&data='+data+'&refeicao='+refeicao+'&escolas='+codescolas;
    var oAjax = new Ajax.Request(url,{method    : 'post',
                                      parameters: parametros+'&sAction='+sAction,
                                      onComplete: js_retornoIncluiRefeicao
                                     });

  }
  
}
function js_retornoIncluiRefeicao(oAjax) {
    
  js_removeObj("msgBox");
  //alert("Inclusão efetuada com sucesso!");
  js_carrega_iframe();

}
function js_alteraregistro(codcardapiodia,evt,tipoacao) {

  if( typeof(event) != "object" ) {
    PosMouseX = evt.layerX;
    PosMouseY = evt.layerY;
  } else {
    PosMouseX = event.x;
    PosMouseY = event.y;
  }
  $('div_inclusao').style.top = PosMouseY;
  $('div_inclusao').style.left = PosMouseX;
  $('div_inclusao').innerHTML = "";
  js_divCarregando("Aguarde, abrindo tela","msgBox");
  var sAction = 'MontaAlteracao';
  var url     = 'mer4_mer_cardapiodiaRPC.php';
  parametros  = 'codcardapiodia='+codcardapiodia+'&cardapio='+document.form1.cardapio.value+'&tipoacao='+tipoacao;
  var oAjax = new Ajax.Request(url,{method    : 'post',
                                    parameters: parametros+'&sAction='+sAction,
                                    onComplete: js_retornoMontaAlteracao
                                   });
    
}
function js_retornoMontaAlteracao(oAjax) {
    
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  $('div_inclusao').innerHTML = oRetorno.urlDecode();     
	  
}
function js_updateregistro (codcardapiodia) {

  tam = document.form1.checkescola.length;
  codescolas = "";
  sep = "";  
  if (tam==undefined) {

    if (document.form1.checkescola.checked==true && document.form1.checkescola.disabled==false) {
      codescolas += document.form1.checkescola.value;
    }

  } else {

    for (i=0;i<tam;i++) {

      if (document.form1.checkescola[i].checked==true && document.form1.checkescola[i].disabled==false) {
	          
        codescolas += sep+document.form1.checkescola[i].value;
        sep = "|";
	        
      }

    }

  }
  if(codescolas==""){
    alert("Informe alguma escola!");
  } else {

    js_divCarregando("Aguarde, alterando registro","msgBox");
    var sAction = 'AlteraRefeicao';
    var url     = 'mer4_mer_cardapiodiaRPC.php';
    parametros  = 'codcardapiodia='+codcardapiodia+'&escolas='+codescolas;
    var oAjax = new Ajax.Request(url,{method    : 'post',
                                      parameters: parametros+'&sAction='+sAction,
                                      onComplete: js_retornoAlteraRefeicao
                                     });

  }
	  
}
function js_retornoAlteraRefeicao(oAjax) {
    
  js_removeObj("msgBox");
  //alert("Alteração efetuada com sucesso!");
  js_carrega_iframe();

}

function js_deleteregistro (codcardapiodia) {

  if(confirm("Confirma exclusão desta refeição para este horário?")){

    js_divCarregando("Aguarde, excluindo registro","msgBox");
    var sAction = 'ExcluiRefeicao';
    var url     = 'mer4_mer_cardapiodiaRPC.php';
    parametros  = 'codcardapiodia='+codcardapiodia;
    var oAjax = new Ajax.Request(url,{method    : 'post',
                                      parameters: parametros+'&sAction='+sAction,
                                      onComplete: js_retornoExcluiRefeicao
                                     });
    
  }
	      
}

function js_retornoExcluiRefeicao(oAjax) {
    
  js_removeObj("msgBox");
  //alert("Exclusão efetuada com sucesso!");
  js_carrega_iframe();

}

</script>