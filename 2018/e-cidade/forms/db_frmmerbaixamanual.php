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
$db_botao1 = false;
?>
<form name="form1" method="post" action="">
<center>
<table border="0" align="center" width="100%">
  <tr>
    <td align="center">
    <table align="center"><tr><td>
     <b>Cardápio:</b>
    </td>
    <td>        
     <?
     $hoje = date("Y-m-d",db_getsession("DB_datausu"));
     $result_tipocardapio = $clmer_tipocardapio->sql_record(
                             $clmer_tipocardapio->sql_query("",
                                                            "me27_i_codigo,me27_c_nome,me27_f_versao,me27_i_id",
                                                            "me27_i_id,me27_f_versao desc",
                                                            "((me27_d_inicio is not null 
                                                               and me27_d_fim is null
                                                               and me27_d_inicio <= '$hoje') 
                                                               or (me27_d_fim is not null and '$hoje'
                                                               between me27_d_inicio and me27_d_fim))"
                                                            ));
                                                                                         ?>
      <select name="cardapio" id="cardapio"   onChange="js_carrega_iframe(this.value);"
              style="height:18px;font-size:10px;">
        <option value="0"></option>
        <?
        for ($t = 0; $t < $clmer_tipocardapio->numrows; $t++) {
        
          db_fieldsmemory($result_tipocardapio,$t);
          ?>
          <option value="<?=$me27_i_codigo?>"><?=$me27_c_nome?> - Versão: <?=$me27_f_versao?></option>
          <?
               
        }
        ?>
      </select>
     </td>
     <td>
       <b>Mês:</b>
     </td>
     <td>
      <select name="mes" id="mes" onchange="js_carrega();"  style="font-size:9px;width:100px;height:18px;">
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
     <td>
      <b>Semana:</b><br>
     </td>
     <td>
      <div name="div_semana">
       <select name="semana" id="semana" onchange="js_carrega_iframe();" style="font-size:9px;width:200px;height:18px;">
        <option value="0"></option>
       </select>
      </div>
     </td>
     <td>
      <b>Dia da Semana:</b>
     </td>
     <td>
      <?
      $result_dias = $cldiasemana->sql_record($cldiasemana->sql_query_rh("",
                                                                         "ed32_i_codigo,ed32_c_descr",
                                                                         "ed32_i_codigo",
                                                                         " ed04_i_escola = $escola"
                                                                        ));
      ?>
      <select name="diasemana" id="diasemana" onchange="js_carrega_iframe();" style="font-size:9px;width:100px;height:18px;">
       <option value="8">TODOS</option>
       <?
       for ($t = 0; $t < $cldiasemana->numrows; $t++) {
       	
         db_fieldsmemory($result_dias,$t);
         ?>
         <option value="<?=($ed32_i_codigo-1)?>"><?=$ed32_c_descr?></option>
         <?

       }
       ?>
      </select>
     </td>
     </tr></table>
    </td> 
  </tr>
  <tr>
    <td align="center" colspan="4">
      <table width="100%">
        <tr>
          <td align="center">
            <div id="div_grid"></div>
          </td>
        </tr>
        <tr>
          <td align="right">
            <div id="div_itemrefeicaograde" ></div>
          </td>
        </tr>
      </table>
    </td>  
  </tr>
</table>
</center>
<script>
function js_carrega() {

  if ($('cardapio').value=="0") {
    alert("Informe o cardapio!");
    document.form1.mes.value = "0";
    return false;
  }
  $('div_grid').style.visibility = "hidden";
  new Ajax.Request('mer4_mer_cardapiodia_combo003.php?mes='+document.form1.mes.value+'&cardapio='+$('cardapio').value,{
  method : 'get',
  onComplete : function(transport){
               document.form1.semana.innerHTML = transport.responseText;
              }
  });
  
}

function js_carrega_iframe() {
	
  cardapio   = document.form1.cardapio.value;
  diasemana  = document.form1.diasemana.value;
  mes        = document.form1.mes.value;
  semana     = document.form1.semana.value;
  if (cardapio == '0' || mes == '0' || semana == '') {
    $('div_grid').style.visibility = "hidden";
  }
  parametros = '';
  if (cardapio != '0' && mes != '0' && semana != '') {
	  
    parametros = 'semana='+semana+'&mes='+mes+'&cardapio='+cardapio+'&diasemana='+diasemana;
    if (parametros != '') {
        
      js_divCarregando("Aguarde, carregando registros","msgBox");
      var sAction = 'MontaGrid';
      var url     = 'mer4_mer_baixamanualRPC.php';
      var oAjax = new Ajax.Request(url,{method    : 'post',
                                     parameters: parametros+'&sAction='+sAction,
                                     onComplete: js_retornoMontagrid
                                   });
      
    } 
       
  }
  
}

function js_retornoMontagrid(oAjax) {
	
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  $('div_grid').innerHTML = oRetorno.urlDecode();
  $('div_grid').style.visibility = "visible";
  
}

function js_calcular() {
	
  registros = "";
  sep = "";
  if (document.form1.checkbaixa) {
   tam = document.form1.checkbaixa.length;
  
   if (tam == undefined) {

     if (document.form1.checkbaixa.checked==true) {
       registros = document.form1.checkbaixa.value;
     }

   } else {
	  
     for (t = 0; t < tam; t++) {
        
       if (document.form1.checkbaixa[t].checked == true) {
          
         registros += sep+document.form1.checkbaixa[t].value;
         sep = ",";
         
       }
      
     }
    
   }
  }
  if (registros == "") {
	  
    alert("Nenhum registro para calcular!");
    return false;
    
  }
  js_OpenJanelaIframe('top.corpo','db_iframe_calculo','mer4_mer_baixamanual002.php?lista='+registros,
		              ' Cálculo de Itens para Baixa no Estoque',true
		             );
}
function js_verificarefeicao(quadro,me12_i_codigo,me01_i_codigo) {

  if (document.form1.checkbaixa[quadro].checked==true) {
	  
    js_divCarregando("Aguarde, verificando registros","msgBox");
    var sAction = 'VerificaRefeicao';
	var url     = 'mer4_mer_baixamanualRPC.php';
	parametros = 'codcardapiodia='+me12_i_codigo+'&codrefeicao='+me01_i_codigo+'&quadro='+quadro;
	var oAjax = new Ajax.Request(url,{method    : 'post',
	                             parameters: parametros+'&sAction='+sAction,
	                             onComplete: js_retornoVerificaRefeicao
	                            });
    
  }

}
function js_retornoVerificaRefeicao(oAjax) {
    
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno[0] < 0) {
    alert("Refeição contém alimento(s) não vinculado(s) a materiais!");
    document.form1.checkbaixa[oRetorno[2]].checked = false;
    return false;
  }
  if (oRetorno[1] == 0) {
    alert("Refeição não contém informação de consumo!");
    document.form1.checkbaixa[oRetorno[2]].checked = false;
    return false;
  }
	  
}

</script>