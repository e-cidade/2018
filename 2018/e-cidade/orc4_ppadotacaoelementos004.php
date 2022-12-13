<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require ("libs/db_stdlib.php");
require ("libs/db_utils.php");
require ("libs/db_app.utils.php");
require ("std/db_stdClass.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("classes/db_ppadotacao_classe.php");
include ("dbforms/db_funcoes.php");

$db_opcao = 1;
$clppadotacao = new cl_ppadotacao ( );
$clppadotacao->rotulo->label ();
$clrotulo     = new rotulocampo ( );
$clrotulo->label("o54_anousu");
$clrotulo->label("o56_codele");
$clrotulo->label("o55_descr");
$clrotulo->label("o40_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o52_descr");
$clrotulo->label("o53_descr");
$clrotulo->label("o54_anousu");
$clrotulo->label("o55_descr");
$clrotulo->label("o55_descr");
$clrotulo->label("o56_codele");
$clrotulo->label("o11_descricao");
$clrotulo->label("o08_concarpeculiar");
$clrotulo->label("c58_descr");

if (isset ( $_SESSION ["dotacaoestimativa"] )) {
	$oDotacao = $_SESSION ["dotacaoestimativa"];
} else {
	
	db_msg ( "Não foi encontrado informações sobre a dotação." );
	db_redireciona ( "orc4_ppadespesamanual001.php" );

}

/*
 * Pesquisamos as informações sobre a lei escolhida para a dotação
 */
$oDaoPPALei = db_utils::getDao ( "ppaversao" );
$sSqlLei = $oDaoPPALei->sql_query ( $oDotacao->o05_ppaversao );
$rsLei = $oDaoPPALei->sql_record ( $sSqlLei );
if ($oDaoPPALei->numrows == 1) {
	
	$oLei = db_utils::fieldsMemory ( $rsLei, 0 );
} else {
	
	db_msg ( "Não foi encontrado informações sobre a lei do ppa." );
	db_redireciona ( "orc4_ppadespesamanual001.php" );

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" 	src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript"	src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript"	src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript"	src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<form method="post" name='form1'>
<table>
	<tr>
		<td>
		<fieldset><legend><b>Elementos</legend>
		<table>
			<tr>
				<td nowrap title="<?=@$To08_elemento?>">
       <?
							db_ancora ( @$Lo08_elemento, "js_pesquisao08_elemento(true);", $db_opcao );
							?>
    </td>
				<td> 
     <?
					db_input ( 'o08_elemento', 10, $Io08_elemento, true, 'text', $db_opcao, " onchange='js_pesquisao08_elemento(false);'" );
					db_input ( 'o56_elemento', 40, $Io56_codele, true, 'text', 3, '' )?>
    </td>
			</tr>
			<tr>
				<td nowrap title="<?=@$To08_recurso?>">
     <?
					db_ancora ( @$Lo08_recurso, "js_pesquisac62_codrec(true);", $db_opcao );
					?>  
    </td>
				<td> 
    <?
				db_input ( 'o15_codigo', 10, $Io08_recurso, true, 'text', $db_opcao, "onchange='js_pesquisac62_codrec(false);'" );
				db_input ( 'o15_descr', 40, $Io08_recurso, true, 'text', 3, "" );
				?>
    </td>
			</tr>
			<tr>
				<td nowrap title="<?=@$To08_localizadorgastos?>">
       <?
							db_ancora ( @$Lo08_localizadorgastos, "js_pesquisao08_localizadorgastos(true);", $db_opcao );
							?>
    </td>
				<td> 
   <?
			db_input ( 'o08_localizadorgastos', 10, $Io08_localizadorgastos, true, 'text', $db_opcao, " onchange='js_pesquisao08_localizadorgastos(false);'" );
			db_input ( 'o11_descricao', 40, $Io11_descricao, true, 'text', 3, '' );
			?>
    </td>
			</tr>
			<tr>
				<td nowrap title="<?=@$To08_concarpeculiar?>">
      <?
						db_ancora ( @$Lo08_concarpeculiar, "js_pesquisao08_concarpeculiar(true);", $db_opcao );
						?>
      </td>
				<td> 
      <?
						db_input ( 'o08_concarpeculiar', 10, $Io08_concarpeculiar, true, 'text', $db_opcao, " onchange='js_pesquisao08_concarpeculiar(false);'" );
						db_input ( 'c58_descr', 40, $Ic58_descr, true, 'text', 3, '' );
						?>
      </td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
		</table>
		</fieldset>
		</td>
		<td align="center"">
		<fieldset style="width: 100%; height: 116"><legend><b>Valores</b></legend>
		<table>
      <?
						for($i = $oLei->o01_anoinicio; $i <= $oLei->o01_anofinal; $i ++) {
							
							echo "<tr>";
							echo "  <td>";
							echo "     <b>{$i}:</b>";
							echo "  </td>";
							echo "  <td>";
							echo "    <input type='text' class='anovalor' onkeypress='return js_mask(event,\"0-9|.\" )'";
							echo "           name='valor{$i}' onblur='js_calculaValores({$i}, {$oLei->o01_anofinal}, this.value)' ";
							echo "           size='10' id='{$i}'>";
							echo "  </td>";
							echo "</tr>";
						}
						?>
      </table>
		</fieldset>
		</td>
	</tr>
	<tr>
		<td colspan='2' align='center'><input type='button' value='Cadastrar'
			id='btncadastrar' onclick="return js_cadastrarDotacoes()"> <input
			type='button' value='ver Parametros' onclick="js_mostraParametros();">
		</td>
	</tr>
</table>
</form>
</center>
</body>
</html>
<script>
sUrlRPC    = 'orc4_ppaRPC.php';
function js_pesquisao08_elemento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_ppadotacaoele',
                        'db_iframe_orcelemento',
                        'func_orcelemento.php?funcao_js=parent.js_mostraorcelemento1|o56_codele|o56_descr&analitica=1',
                        'Elementos da Despesa',
                        true);
  }else{
     if(document.form1.o08_elemento.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_ppadotacaoele',
                            'db_iframe_orcelemento',
                            'func_orcelemento.php?pesquisa_chave='+document.form1.o08_elemento.value+
                            '&funcao_js=parent.js_mostraorcelemento&tipo_pesquisa=1&analitica=1',
                            'Pesquisa', false);
     }else{
       document.form1.o56_codele.value = ''; 
     }
  }
}
function js_mostraorcelemento(chave,erro){

  document.form1.o56_elemento.value = chave;
   
  if(erro==true){ 
    document.form1.o08_elemento.focus(); 
    document.form1.o08_elemento.value = ''; 
  }
}
function js_mostraorcelemento1(chave1,chave2){
  document.form1.o08_elemento.value = chave1;
  document.form1.o56_elemento.value = chave2;
  db_iframe_orcelemento.hide();
}
function js_pesquisao08_localizadorgastos(mostra){
  
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_ppadotacaoele',
                        'db_iframe_ppasubtitulolocalizadorgasto',
                        'func_ppasubtitulolocalizadorgasto.php?funcao_js=parent.js_mostrappasubtitulolocalizadorgasto1|o11_sequencial|o11_descricao',
                        'Pesquisa',true);
  }else{
     if(document.form1.o08_localizadorgastos.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_ppadotacaoele','db_iframe_ppasubtitulolocalizadorgasto','func_ppasubtitulolocalizadorgasto.php?pesquisa_chave='+document.form1.o08_localizadorgastos.value+'&funcao_js=parent.js_mostrappasubtitulolocalizadorgasto','Pesquisa',false);
     }else{
       document.form1.o11_descricao.value = ''; 
     }
  }
}
function js_mostrappasubtitulolocalizadorgasto(chave,erro){
  document.form1.o11_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o08_localizadorgastos.focus(); 
    document.form1.o08_localizadorgastos.value = ''; 
  }
}
function js_mostrappasubtitulolocalizadorgasto1(chave1,chave2){
  document.form1.o08_localizadorgastos.value = chave1;
  document.form1.o11_descricao.value = chave2;
  db_iframe_ppasubtitulolocalizadorgasto.hide();
}

function js_pesquisac62_codrec(mostra){
   if(mostra==true){
       js_OpenJanelaIframe('top.corpo.iframe_ppadotacaoele',
                           'db_iframe_orctiporec',
                           'func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr',
                           'Recursos',true);
   }else{
       if(document.form1.o15_codigo.value != ''){ 
           js_OpenJanelaIframe('top.corpo.iframe_ppadotacaoele',
                               'db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='
                                +document.form1.o15_codigo.value+'&funcao_js=parent.js_mostraorctiporec',
                                'Pesquisa',false);
       }else{
           document.form1.o15_descr.value = ''; 
       }
   }
}
function js_mostraorctiporec(chave,erro){
   document.form1.o15_descr.value = chave; 
   if(erro==true){ 
      $('o15_codigo').focus(); 
      $('o15_codigo').setValue(""); 
   } 
}

function js_mostraorctiporec1(chave1,chave2){
    $('o15_codigo').setValue(chave1);
    $('o15_descr').setValue(chave2);
    db_iframe_orctiporec.hide();
}

function js_calculaValores(iAno, iAnoFinal, nValor) {

  if ($F('o08_elemento') == "") {
  
    alert('antes de informar os valores, informe o elemento');
    return false;
    
  } 
  
  js_divCarregando("Aguarde, Calculando Valores","msgBox");
  $('btncadastrar').disabled = true;
  var oParam            = new Object();
  oParam.exec           = "calculaValorEstimativa";
  oParam.iCodCon        = $F('o08_elemento');
  oParam.iAno           = iAno;
  oParam.iAnoFinal      = iAnoFinal;
  oParam.nValor         = nValor;
  oParam.iTipo          = 2;
  oParam.iCodigoLei     = <?=$oLei->o01_sequencial?>;
  oParam.iCodigoVersao  = <?=$oLei->o119_sequencial?>;
  var oAjax   = new Ajax.Request(
                         sUrlRPC, 
                         {
                          method    : 'post', 
                          parameters: 'json='+js_objectToJson(oParam), 
                          onComplete: js_retornoCalculo
                          }
                        );
}

function js_retornoCalculo(oAjax) {
  
  js_removeObj("msgBox"); 
  $('btncadastrar').disabled = false;
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1){
  
    var aInputsValores = js_getElementbyClass(form1,"anovalor");
    for (var i = 0; i < aInputsValores.length; i++) {
      
      for (var j = 0; j < oRetorno.itens.length; j++) {
        if (oRetorno.itens[j].iAno == aInputsValores[i].id) {
          aInputsValores[i].value = oRetorno.itens[j].nValor;
        }
      }
    } 
  }
}

function js_cadastrarDotacoes() {

  if ($F('o08_elemento') == "") {
  
    alert('informe o elemento da Dotação');
    return false;
    
  }
  
  if ($F('o15_codigo') == "") {
  
    alert('informe o recurso da Dotação');
    return false;
    
  } 
  if ($F('o08_localizadorgastos') == "") {
  
    alert('informe o localizador de gastos da Dotação');
    return false;
    
  }
  if ($F('o08_concarpeculiar') == "") {
  
    alert('Você deve selecionar uma C.Peculiar/Cod de Aplicação antes de incluir o registro.');
    return false;
    
  }
  var oParam                     = new Object();
  oParam.exec                    = "adicionaEstimativaDespesa";
  oParam.iElemento               = $F('o08_elemento');
  oParam.iRecurso                = $F('o15_codigo');
  oParam.iLocalizadorgasto       = $F('o08_localizadorgastos');
  oParam.sCaracteristicaPeculiar = $F('o08_concarpeculiar');
  oParam.iTipo                   = 2;
  oParam.iCodigoLei              = <?=$oLei->o01_sequencial?>;
  oParam.iCodigoVersao           = <?=$oLei->o119_sequencial?>;
  oParam.aAnos                   = new Array();
  /*
   * percorremos os valores cadastrados para o anos da lei, 
   * verificamos quais nao foram prrenchidos.
   * 
   */
   var aInputsValores = js_getElementbyClass(form1,"anovalor");
   var sMsgValores    = ""; 
   var sVirgula       = " ";
   for (var i = 0; i < aInputsValores.length; i++) {
    
     var nValor = new Number(aInputsValores[i].value);
     if (nValor == 0 || nValor == "") {

       sMsgValores += sVirgula+aInputsValores[i].id;
       sVirgula    = ", ";
       
     } else {
       
       var aAno    = new Object();
       aAno.iAno   = aInputsValores[i].id;
       aAno.nValor = aInputsValores[i].value;
       oParam.aAnos.push(aAno);
     }
   }
   if (sMsgValores != "") {
     
     var sMSgUsuario  = 'O(s) ano(s) '+sMsgValores+' estão sem valores definidos.\nPara esses anos, não sera cadastrados ';  
     sMSgUsuario     += 'Dotações\nDeseja continuar?';  
     if (!confirm(sMSgUsuario)) {
       return false;
     }
   }
  js_divCarregando("Aguarde, Cadastrando Despesas","msgBox");
  $('btncadastrar').disabled = true;
  var oAjax   = new Ajax.Request(
                         sUrlRPC, 
                         {
                          method    : 'post', 
                          parameters: 'json='+js_objectToJson(oParam), 
                          onComplete: js_retornoAdicaoDotacao
                          }
                        );
  
  
}

function js_retornoAdicaoDotacao(oAjax) {
   
  js_removeObj("msgBox"); 
  $('btncadastrar').disabled = false;
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
    
    if (!confirm('Cadastro das Estimativas Realizadas com sucesso.\nDeseja incluir novas estimativas para a ação?')) {
       parent.location.href = "orc4_ppadespesamanual001.php";
    } else {

      $('o08_elemento').value          = "";
      $('o56_elemento').value          = "";
      $('o15_codigo').value            = "";
      $('o15_descr').value             = "";
      $('o08_localizadorgastos').value = "";
      $('o11_descricao').value         = "";
      $('o08_concarpeculiar').value    = "";
      $('c58_descr').value             = "";
      var aInputsValores = js_getElementbyClass(form1,"anovalor");
      for (var i = 0; i < aInputsValores.length; i++) {
         aInputsValores[i].value = "";    
      }
    }
  } else {
    alert(oRetorno.message.urlDecode());
  }
}

function js_mostraParametros() {
   
   var iCodCon = $F('o08_elemento');
   if (iCodCon == "") {
     
     alert('Informe o elemento');
     return;
     
   }
   var iCodigoLei    = <?=$oLei->o01_sequencial?>; 
   var iCodigoVersao = <?=$oLei->o119_sequencial?>; 
   js_OpenJanelaIframe('',
                       'db_iframe_reprocppaestimativa',
                       'orc4_mostraparametrosestimativa.php?o01_sequencial='+iCodigoLei+'&iCodCon='+iCodCon+
                       "&iTipo=2",
                       'Parâmetro das estimativas',
                       true,
                       ((screen.availHeight-700)/2),
                       ((screen.availWidth-500)/2),
                       650,
                       350);
  
} 

function js_pesquisao08_concarpeculiar(mostra){
  
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_ppadotacaoele',
                        'db_iframe_concarpeculiar',
                        'func_concarpeculiar.php?funcao_js=parent.js_mostraconcarpeculiar1|c58_sequencial|c58_descr',
                        'Pesquisa',true,0,0);
  }else{
     if(document.form1.o08_concarpeculiar.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_ppadotacaoele','db_iframe_concarpeculiar','func_concarpeculiar.php?pesquisa_chave='+document.form1.o08_concarpeculiar.value.trim()+'&funcao_js=parent.js_mostraconcarpeculiar','Pesquisa',false);
     }else{
       $("o08_concarpeculiar").setValue(''); 
     }
  }
}

function js_mostraconcarpeculiar(chave,erro){
  $("c58_descr").value = chave; 
  if(erro==true){ 
    $("o08_concarpeculiar").focus(); 
    $("o08_concarpeculiar").setValue(''); 
  }
}

function js_mostraconcarpeculiar1(chave1,chave2){
  $("o08_concarpeculiar").setValue(chave1); 
  $("c58_descr").setValue(chave2); 
  db_iframe_concarpeculiar.hide();
}

</script>