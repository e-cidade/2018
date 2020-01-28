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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("libs/db_utils.php");
include ("dbforms/db_funcoes.php");
include ("dbforms/db_classesgenericas.php");
require ("libs/db_app.utils.php");

require ("classes/db_termo_classe.php");
require ("classes/db_arrecad_classe.php");

$oPost     = db_utils::postMemory($_POST);

$oDAOTermo   = new cl_termo();
$oDaoArrecad = new cl_arrecad();

$rotulo = new rotulocampo();
$rotulo->label('v07_parcel');
$rotulo->label('v07_vlrent');
$rotulo->label('v07_vlrpar');
$rotulo->label('v07_ultpar');
$rotulo->label('v07_totpar');

$lErro    = false;
$sErroMsg = '';

if(isset($processar)) {
  
  try { 	
  
  	db_inicio_transacao();
  	
    if((($oPost->n_valor_parcelas * ($oPost->total_parcelas - 2)) + $oPost->n_valor_primeira_parcela) >= $oPost->valor_total_divida ) {
      throw new Exception("Valor da soma das parcelas é maior que o valor total da divida");
    }
    
    $rDAOTermo = $oDAOTermo->sql_record($oDAOTermo->sql_query_acerta_parcelamento($oPost->v07_parcel, $oPost->n_valor_primeira_parcela, $oPost->n_valor_parcelas));
    $iRegistros = $oDAOTermo->numrows; 
	if ($oDAOTermo->numrows == 0) {
	  throw new Exception("Nenhum dado encontrado para o parcelamento ");
	}	
	    
	$oTermo = db_utils::fieldsMemory($rDAOTermo, 0);
	    
	$nValorEntradaTermo  = $oTermo->nova_entrada_termo;
	    
	$nValorParcelaTermo  = $oTermo->nova_parcela_termo;
	    
	$nValorUltimaParcelaTermo = $oTermo->novo_valor_ultima_parcela_termo;
	    
	$oDAOTermo->v07_vlrent = $nValorEntradaTermo;    
	$oDAOTermo->v07_vlrpar = $nValorParcelaTermo;    
	$oDAOTermo->v07_ultpar = $nValorUltimaParcelaTermo;
	$oDAOTermo->v07_parcel = $oPost->v07_parcel;
	$oDAOTermo->alterar($oPost->v07_parcel);
	if ($oDAOTermo->erro_status == "0") {
      throw new Exception("Erro ao alterar dados do termo. Erro: {$oDAOTermo->erro_msg}"); 
    }
	    
	for($i = 0; $i < $iRegistros; $i++) {
	  
	  $oArrecad = db_utils::fieldsMemory($rDAOTermo, $i); 
	  
	  if ($i == 0) {
	    //corrige diferença de centavos pela soma de peridicas e altera a primeira receita
	    
	    $nDifEntrada = $nValorEntradaTermo - ($oArrecad->novo_valor_primeira_parcela * $iRegistros);
	    $nDifParcela = $nValorParcelaTermo - ($oArrecad->novo_valor_outras_parcelas  * $iRegistros);
	    
	    $nValorPrimeiraParcela = $oArrecad->novo_valor_primeira_parcela + ($nDifEntrada);
	    $nValorOutrasParcelas  = $oArrecad->novo_valor_outras_parcelas  + ($nDifParcela);
	    
	    $nValorUltimaParcela   = $oArrecad->novo_valor_ultima_parcela;
	    
	  } else {
	    
	    $nValorPrimeiraParcela = $oArrecad->novo_valor_primeira_parcela;
        $nValorOutrasParcelas  = $oArrecad->novo_valor_outras_parcelas;
        $nValorUltimaParcela   = $oArrecad->novo_valor_ultima_parcela;
      
	  }
	  
	  $oDaoArrecad->k00_valor = $nValorPrimeiraParcela;
	  $oDaoArrecad->alterar(null,"k00_numpre = {$oArrecad->numpre} and k00_receit = {$oArrecad->receita} and k00_numpar = 1");
	  if ($oDaoArrecad->erro_status == "0") {
	  	throw new Exception("Erro alterando valor da primeira parcela do Numpre {$oArrecad->numpre} Receita {$oArrecad->receita}. Erro: {$oDaoArrecad->erro_msg}");
	  }
	  
	  $oDaoArrecad->k00_valor = $nValorOutrasParcelas;
	  $oDaoArrecad->alterar(null,"k00_numpre = {$oArrecad->numpre} and k00_receit = {$oArrecad->receita} and k00_numpar > 1 and k00_numpar < {$oArrecad->numpar}");
	  if ($oDaoArrecad->erro_status == "0") {
	  	throw new Exception("Erro alterando parcelas do Numpre {$oArrecad->numpre} Receita {$oArrecad->receita}. Erro: {$oDaoArrecad->erro_msg}");
	  }
	  
	  $oDaoArrecad->k00_valor = $nValorUltimaParcela;
	  $oDaoArrecad->alterar(null,"k00_numpre = {$oArrecad->numpre} and k00_receit = {$oArrecad->receita} and k00_numpar = {$oArrecad->numpar}");
	  if ($oDaoArrecad->erro_status == "0") {
	  	throw new Exception("Erro alterando ultima parcela do Numpre {$oArrecad->numpre} Receita {$oArrecad->receita}. Erro: {$oDaoArrecad->erro_msg}");
	  }
	  
	}
	
	db_fim_transacao(false);
	
	$n_valor_ultima_parcela = '';
	db_msgbox("Acerto realizado com sucesso");
	  
    echo '<script>document.form1.pesquisar.click()</script>';
   
  } catch (Exception $oErro ) {
   db_fim_transacao(true);
   
   alert("Operação não realizada! \n".$oErro->getMessage());
  	
  } 
  
}

?>
<html>
<head>
	<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Expires" CONTENT="0">
	<?
	  db_app::load('scripts.js, prototype.js, strings.js, datagrid.widget.js');
	  db_app::load('estilos.css, grid.style.css');
	?>
</head>

<body bgcolor="#CCCCCC">
<form name="form1" id="form1" method="post" onsubmit="return js_valida();">
<?
 db_menu(db_getsession('DB_id_usuario'), db_getsession('DB_modulo'), db_getsession('DB_anousu'), db_getsession('DB_instit'))
?>

<table style="margin: 30px auto" width="600">
  
  <tr>
    <td title="<?=$Tv07_parcel?>" width="50%" align="right"><?=$Lv07_parcel?></td>
    <td width="50%">
    <?
      db_input('v07_parcel', 10, $Iv07_parcel, true, 'text', 1)
    ?>
    </td>
  </tr>
  
  <tr>
    <td colspan="2" align="center">
      <input type="button" name="pesquisar" id="pesquisar" value="Pesquisar" onclick="js_pesquisar_parcelamento()" />
    </td>
  </tr>
  
  <tr>
    <td colspan="2">
      <div id="grid"></div>
    </td>
  </tr>
  
  <tr>
    <td title="Total de Parcelas" align="right"><strong>Total de Parcelas</strong></td>
    <td>
    <?
      db_input('total_parcelas', 10, $Iv07_totpar, true, 'text', 3);
    ?>
    </td>
  </tr>
    
  <tr>
    <td title="Valor atual da 1ª parcela" align="right"><strong>Valor Atual 1ª Parcela</strong></td>
    <td>
    <?
      db_input('valor_primeira_parcela', 10, $Iv07_vlrent, true, 'text', 3);
    ?>
    </td>
  </tr>
  
  <tr>
    <td title="Valor outras parcelas" align="right"><strong>Valor Atual Parcelas</strong></td>
    <td>
    <?
      db_input('valor_parcelas', 10, $Iv07_vlrpar, true, 'text', 3);
    ?>
    </td>
  </tr>  
  
  <tr>
    <td title="Valor ultima parcela" align="right"><strong>Valor Atual &Uacute;ltima Parcela</strong></td>
    <td>
    <?
      db_input('valor_ultima_parcela', 10, $Iv07_ultpar, true, 'text', 3);
    ?>
    </td>
  </tr>  
  
  <tr>
    <td title="Valor Total D&iacute;vida" align="right"><strong>Valor Total da D&iacute;vida</strong></td>
    <td>
    <?
      db_input('valor_total_divida', 10, null, true, 'text', 3);
    ?>
    </td>
  </tr>   
  
  <tr>
    <td title="Valor 1ª parcela" align="right"><strong>Valor Entrada</strong></td>
    <td>
      <input type="text" name="n_valor_primeira_parcela" id="n_valor_primeira_parcela" size="10" onchange="return js_formataValor(this);" onkeyup="js_ValidaCampos(this, 4, 'Valor da 1ª Parcela', false, null, event)" autocomplete="off"/>
    </td>
  </tr>
  
  <tr>
    <td title="Valor outras parcelas" align="right"><strong>Valor Parcelas</strong></td>
    <td>
      <input type="text" name="n_valor_parcelas" id="n_valor_parcelas" size="10" onchange="return js_formataValor(this);" onkeyup="js_ValidaCampos(this, 4, 'Valor das Parcelas', false, null, event)" autocomplete="off"/>
    </td>
  </tr>  
  
  <tr>
    <td title="Valor ultima parcela" align="right"><strong>Valor &Uacute;ltima Parcela</strong></td>
    <td>
    <?
      db_input('n_valor_ultima_parcela', 10, $Iv07_ultpar, true, 'text', 3);
    ?>
    </td>
  </tr>  
  
  <tr>
    <td colspan="2" align="center">
      <input type="submit" name="processar" id="processar" value="Processar" />
    </td>
  </tr>  
  
</table>
<script type="text/javascript">

function js_valida() {

	if(($('n_valor_primeira_parcela').value == '') || (parseFloat($('n_valor_primeira_parcela').value) <= 0)) {
		alert('Valor da 1ª parcela não informado ou inválido.');
		$('n_valor_primeira_parcela').focus();
		return false;
	}

	if(($('n_valor_parcelas').value == '') || (parseFloat($('n_valor_parcelas').value) <= 0)){
    alert('Valor das parcelas não informado ou inválido.');
    $('n_valor_parcelas').focus();
    return false;
  }

	fSomaParcelas = (parseFloat($('n_valor_parcelas').value) * (parseInt($('total_parcelas').value) - 2)) + (parseFloat($('n_valor_primeira_parcela').value));

	if(fSomaParcelas >= parseFloat($('valor_total_divida').value)) {
		alert('Valor da soma das parcelas é maior que o valor total da divida');
		return false;

	}
}

function js_init_table() {
    
  oDataGrid = new DBGrid('grid');
  oDataGrid.nameInstance = 'oDataGrid';
  oDataGrid.setCellAlign(new Array('center', 'center', 'center', 'center'));
  oDataGrid.setCellWidth(new Array('25%', '25%', '25%', '25%'));
  oDataGrid.setHeader(new Array('PARCELAMENTO', 'NUMPRE', 'NUMPAR', 'VALOR'));
  oDataGrid.setHeight(150);
  oDataGrid.show($('grid'));
      
}

function js_pesquisar_parcelamento() {

	var oParam     = new Object();
	
	if($F('v07_parcel') == '') {
    return false;
	}

	oParam.exec             = 'getParcelamento';
  oParam.iCodParcelamento = $F('v07_parcel');

  js_divCarregando('Aguarde, pesquisando parcelamento.', 'msgbox');
  
  var oAjax = new Ajax.Request('arr4_acertaparcelamento.RPC.php', { method: 'POST', parameters: 'json='+Object.toJSON(oParam), onComplete: js_retorno_parcelamento });
}

function js_retorno_parcelamento(oAjax) {

	js_removeObj('msgbox');
	
	var oRetorno = eval("("+oAjax.responseText+")");

	var fValorTotal = 0;

	if(oRetorno.status == 1) {

		if(oRetorno.parcelas.length > 0) {
			oDataGrid.clearAll(true);

			for(var i = 0; i < oRetorno.parcelas.length; i++) {
        with(oRetorno.parcelas[i]) {
          aCol = new Array();
          aCol[0] = v07_parcel;
          aCol[1] = k00_numpre;
          aCol[2] = k00_numpar;
          aCol[3] = float2moeda(k00_valor);

          fValorTotal += parseFloat(k00_valor);

          oDataGrid.addRow(aCol);

          if(k00_numpar == 1)
        	  $('valor_primeira_parcela').value = float2moeda(k00_valor);
    	      $('total_parcelas').value = parseInt(v07_totpar);
    	    if(k00_numpar == 2)
        	  $('valor_parcelas').value = float2moeda(k00_valor);
        	if(k00_numpar == parseInt(v07_totpar))
            $('valor_ultima_parcela').value = float2moeda(k00_valor);
                
        }  
				  
			}

			$('valor_total_divida').value = float2moeda(fValorTotal);
			oDataGrid.renderRows();	
			
		}

	}	else {

		alert(oRetorno.message);
		return false;
		
	}
	
}

function js_formataValor(obj) {

	obj.value = float2moeda(obj.value);

	if($('n_valor_primeira_parcela').value != '' && $('n_valor_parcelas').value != '') {
		
		valor_parcela_1 = parseFloat($('n_valor_primeira_parcela').value);
		valor_parcelas  = parseFloat($('n_valor_parcelas').value);
		valor_total     = parseFloat($('valor_total_divida').value);
	
		$('n_valor_ultima_parcela').value = float2moeda(valor_total - ((valor_parcelas * (parseInt($('total_parcelas').value) - 2)) + valor_parcela_1));		

	}

}

function float2moeda(num) {

  x = 0;

  if(num < 0) {
	  num = Math.abs(num);
    x = 1;
  }
  if(isNaN(num)) num = "0";

  cents = Math.floor((num*100+0.5)%100);

  num = Math.floor((num*100+0.5)/100).toString();

  if(cents < 10) cents = "0" + cents;
  
  for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
    num = num.substring(0,num.length-(4*i+3))+''+num.substring(num.length-(4*i+3));

  ret = num + '.' + cents;

  if (x == 1) 
    ret = '-' + ret;

  return ret;

}

js_init_table();

</script>
</form>
</body>
</html>