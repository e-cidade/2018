<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

$oRotulo = new rotulocampo();
$oRotulo->label("c70_codlan");
$oRotulo->label("e60_numemp");
$oRotulo->label("e60_codemp");
$oRotulo->label("z01_nome");
$oRotulo->label("ac16_sequencial");
$oRotulo->label("ac16_resumoobjeto");
$oRotulo->label("c36_sequencial");

$sTitulo  = null;
$db_opcao = 1;
$oPost    = db_utils::postMemory($_POST);

 
$iSequencialEmpPresta = $oPost->iSequencialEmpPresta ;
$iNumeroEmpenho       = $oPost->iNumeroEmpenho       ;
$iCodigoMovimento     = $oPost->iCodigoMovimento     ;
$iCodigoEmpenho       = $oPost->iCodigoEmpenho       ;
$nValorJaPago         = 0; 

$oDaoEmpAgeMov     = db_utils::getDao("empagemov");
$oDaoEmpPrestaItem = db_utils::getDao("empprestaitem");

$sSqlEmpPrestaItem = $oDaoEmpPrestaItem->sql_query_file (null, "sum(e46_valor) as totalpago",null, "e46_emppresta = {$iSequencialEmpPresta}");
$rsEmpprestaitem   = $oDaoEmpPrestaItem->sql_record($sSqlEmpPrestaItem);
$sSqlEmpAgeMov     = $oDaoEmpAgeMov->sql_query_file ($iCodigoMovimento);
$rsEmpAgeMov       = $oDaoEmpAgeMov->sql_record($sSqlEmpAgeMov);

if ($oDaoEmpPrestaItem->numrows > 0) {
	
	$nValorJaPago = db_utils::fieldsMemory($rsEmpprestaitem, 0)->totalpago;
}

$oDadosEmpAgeMov = db_utils::fieldsMemory($rsEmpAgeMov, 0);
$oEmpenho        = new EmpenhoFinanceiro($iNumeroEmpenho);
$nValorEmpAgeMov = $oDadosEmpAgeMov->e81_valor;

$iEmpenho   = $oEmpenho->getNumero();
$sEmpenho   = $oEmpenho->getCodigo() . " / " . $oEmpenho->getAnoUso();
$iCredor    = $oEmpenho->getCgm()->getCodigo();
$sCredor    = $oEmpenho->getCgm()->getNomeCompleto();
$iCodMov    = $iCodigoMovimento;
$iEmpPresta = $iSequencialEmpPresta;
$nSaldo     = trim(db_formatar($nValorEmpAgeMov - $nValorJaPago, "f"));//trim(db_formatar($oDadosEmpAgeMov->e81_valor, "f"));
if ($nSaldo <= 0 ) {

	$db_opcao         = 3;
	$sCaminhoMensagem =  "financeiro.caixa.cai4_devolucaoadiantamento002.";
	db_msgbox(_M($sCaminhoMensagem . "tudoPago"));
}

db_app::load("scripts.js");
db_app::load("dbtextField.widget.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("DBLancador.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
db_app::load("widgets/windowAux.widget.js");
db_app::load("widgets/dbmessageBoard.widget.js");
db_app::load("dbcomboBox.widget.js");
db_app::load("widgets/DBAncora.widget.js");
db_app::load("dbtextFieldData.widget.js");

?>


<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
  <meta http-equiv="expires" content="0" />
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

  <center> 

    <fieldset class="container" style="width:600px; margin-top: 50px;">

      <legend>Devolução de Adiantamento - Arrecadação de Receita</legend>

      <table style="width: 100%;">

        <tr>   
          <td width="11%">
           <strong>Empenho: </strong>
          </td>
          <td> 
            <?php 
              db_input('sEmpenho'  , 10, null, true, 'text', 3);
              db_input('iEmpenho'  , 10, null, true, 'hidden', 3);
              db_input('iEmpPresta', 10, null, true, 'hidden', 3);
            ?>
          </td>
        </tr>

        <tr>   
          <td>
            <strong>Movimento: </strong>
          </td>
          <td> 
           <?php db_input('iCodMov', 10, null, true, 'text', 3); ?>
          </td>
        </tr>
        <tr>   
          <td>
            <strong>Credor: </strong>
          </td>
          <td>
            <?php db_input('iCredor', 10, null, true, 'text', 3); ?>
            <?php db_input('sCredor', 60, null, true, 'text', 3); ?> 
          </td>
        </tr>
        <tr>   
          <td>
            <strong>Saldo: </strong>
          </td>
          <td>
            <?php 
              db_input('nSaldo', 10, null, true, 'text', 3); ?>
          </td>
        </tr>
        
      </table>
      
      
      
     <fieldset style="margin-top: 10px;">
      <legend><strong>Configuração Vencimento / Receitas</strong></legend>
      
      <table style="width: 100%;">
      
        <tr>   
          <td>
            <strong>Vencimento: </strong>
          </td>
          <td> 
            <?php 
              list($iAno, $iMes, $iDia) = explode("-", date("Y-m-d", db_getsession("DB_datausu")));
              db_inputdata('dtVencimento', $iDia, $iMes, $iAno, true, 'text', $db_opcao);
            ?>
          </td>
        </tr>      
      
        <tr>   
          <td width="10%">
            <strong><?db_ancora("Receita:","js_pesquisaTipoReceita(true)",$db_opcao);?> </strong>
          </td>
          <td>
            <?php db_input('iReceita', 10, null, true, 'text', $db_opcao, "onchange='js_pesquisaTipoReceita(false);'"); ?>
            <?php db_input('sReceita', 60, null, true, 'text', 3); ?> 
          </td>
        </tr>
      
         <tr>   
          <td>
            <strong>
              <?php db_ancora("CP/CA:","js_pesquisaCaracteristicaPeculiar(true);",$db_opcao);?>
            </strong>
          </td>
          <td>
            <?php db_input('iCaracteristicaPeculiar', 10, null, true, 'text', $db_opcao, "onchange='js_pesquisaCaracteristicaPeculiar(false);'"); ?>
            <?php db_input('sCaracteristicaPeculiar', 60, null, true, 'text', 3); ?> 
          </td>
        </tr>
             
        <tr>   
          <td>
            <strong>Valor: </strong>
          </td>
          <td>
            <?php db_input('nValor', 10, null, true, 'text', $db_opcao, "onKeyPress='return js_teclas(event,this);' onchange='return js_teclas(event,this);' onblur='return js_teclas(event,this);'"); ?>
          </td>
        </tr>        
        
      </table>

      <div style="mergint-top:10px;">
        <input type='button' value='Adicionar Receita' onclick="js_adicionaRegistro();" />
      </div>
      
      <fieldset style="margin-top: 10px;">
        <legend><strong>Receitas Vinculadas</strong></legend>
        
        <div id='ctnGridDetalhamento'></div>
        
      </fieldset>
      
      <div style="mergint-top:10px;">
        <input type='button' value='Excluir Selecionado(s)' onclick="js_removerRegistro();" />
      </div>
      
    </fieldset> 
    
    
    <fieldset>
      <legend><strong>Histórico</strong></legend>
      <?php db_textarea("sHistorico", 2, 130,  null, true, null,1)  ?>
    </fieldset>

    </fieldset>

    
    <div style="margin-top:10px;">
      <input type="button" value="Emitir Recibo" onClick="js_processar();" id='processar' disabled="disabled" />
      <input type="button" value="Voltar" onClick="js_voltar();" />
    </div>

  </center>

  <?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>

</body>
</html>
<script type="text/javascript">

var sRPC              = 'cai4_devolucaoadiantamento004.RPC.php';
var sArquivoMensagens = "financeiro.caixa.cai4_devolucaoadiantamento002.";
var aItensRenderizar  = new Array();

function js_voltar() {

  document.location.href = 'cai4_devolucaoadiantamento001.php';
}


function js_adicionaRegistro(){
  
  var nValor          = $F("nValor"); 
  var iReceita        = $F("iReceita");
  var sReceita        = $F("sReceita");
  var iCaracteristica = $F("iCaracteristicaPeculiar");
  var sCaracteristica = $F("sCaracteristicaPeculiar");
  var oDadosAdicionar = new Object();
  var lAdiciona       = true;

  oGridDetalhamento.aRows.each(function (oRow, iIndice) {

  	var iReceitaAdicionada = oRow.aCells[1].getValue();
	  if (iReceitaAdicionada == iReceita) {

	    alert(_M(sArquivoMensagens + "receitaJaAdicionada"));
	    lAdiciona = false;
	    //js_somavalores();
	    return false;
	  }
  });
  
   
  if (iReceita == '') {
    
	    alert( _M(sArquivoMensagens + "receitaNull") );
	    return false;
	}
  if (iCaracteristica == '') {
    
    alert( _M(sArquivoMensagens + "caracteristicaNull") );
	  return false;
	}
  if (nValor == '' || nValor == 0) {

    alert( _M(sArquivoMensagens + "valorNull") );
    return false;
	}

  if (lAdiciona == true) {
    
	  oDadosAdicionar.iReceita         = iReceita;
	  oDadosAdicionar.sReceita         = sReceita;
	  oDadosAdicionar.iCaracteristica  = iCaracteristica;
	  oDadosAdicionar.sCaracteristica  = sCaracteristica;
	  oDadosAdicionar.nValor           = nValor;
	  
	  aItensRenderizar.push(oDadosAdicionar);
  	renderizarGrid ();
  }
  js_somavalores();
}

function js_removerRegistro() {

	var aLinhas = oGridDetalhamento.getSelection('object');
	
	aLinhas.reverse().each( function( oDadosLinha, iDadosLinha){

	  aItensRenderizar.splice(oDadosLinha.getRowCount(), 1);
  });
	renderizarGrid ();
	js_somavalores();
}


function renderizarGrid () {

  oGridDetalhamento.clearAll(true);
  aItensRenderizar.each( function (oDados, iIndice) {
    
	  var aRow = new Array();
        aRow[0] = oDados.iReceita         ;
        aRow[1] = oDados.sReceita         ;
        aRow[2] = oDados.iCaracteristica  ;
        aRow[3] = oDados.sCaracteristica  ;
        aRow[4] = js_formatar(oDados.nValor, 'f');

      oGridDetalhamento.addRow(aRow)
  });
  
  oGridDetalhamento.renderRows();

  $("iReceita")               .value = "";
  $("sReceita")               .value = "";
  $("iCaracteristicaPeculiar").value = "";
  $("sCaracteristicaPeculiar").value = "";
  $("nValor")                 .value = "";
  
}


function js_somavalores() {
  
	var nValorLinha;
	var iTotalLinhas = oGridDetalhamento.aRows.length;
	var nTotal  = 0;

	for (var iLinha = 0; iLinha < iTotalLinhas; iLinha++) {

	  nValorLinha  = js_strToFloat(oGridDetalhamento.aRows[iLinha].aCells[5].getValue());
	  nTotal      += nValorLinha;
	}

	$('TotalForCol5').innerHTML = js_formatar(parseFloat(nTotal).toFixed(2), 'f');
	js_habilitaBotao();
}
/*
 * função contendo as validações para habilitar o botão de processar
 */

function js_habilitaBotao(){

	var nSaldo              = $("nSaldo").value;
	var nValorDistribuido   = $("TotalForCol5").innerHTML;
	
	$('processar').disabled = true;

	if (nSaldo == nValorDistribuido) {

	  $('processar').disabled = false;
  }
}

function js_processar() {

  var dtVencimento = js_formatar($('dtVencimento').value, 'd');
  var iEmpenho     = $F("iEmpenho");
  var iCodMov      = $F("iCodMov");
  var iEmpPresta   = $F("iEmpPresta"); 
  var sHistorico   = encodeURIComponent(tagString($F("sHistorico")));

  if (dtVencimento == '') {

    alert( _M(sArquivoMensagens + "dtVencimentoNull") );
    return false;
  }

  if (sHistorico == '') {

    alert( _M(sArquivoMensagens + "sHistoricoNull") );
    return false;
  }

  var oParametros              = new Object();
      oParametros.aReceitas    = new Array();
      oParametros.exec         = 'gerarRecibo';  
      oParametros.dtVencimento = dtVencimento;
      oParametros.iEmpenho     = iEmpenho;
      oParametros.iCodMov      = iCodMov;
      oParametros.sHistorico   = sHistorico;
      oParametros.iEmpPresta   = iEmpPresta;

  oGridDetalhamento.aRows.each(function (oRow, iIndice) {

    var oDetalhes = new Object();
    
    oDetalhes.iReceita        = oRow.aCells[1].getValue();
    oDetalhes.iCaracteristica = oRow.aCells[3].getValue();
    oDetalhes.nValor          = js_strToFloat(oRow.aCells[5].getValue());
    oParametros.aReceitas.push(oDetalhes);
  });

  js_divCarregando(_M( sArquivoMensagens + "gerandoRecibo"), 'msgBox');
  
  var oAjax = new Ajax.Request(sRPC, {
                               method     : "post",
                               parameters : 'json='+Object.toJSON(oParametros),
                               onComplete : js_retornoProcessar
                              });  
}

function js_retornoProcessar(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  
  var sMensagem = oRetorno.sMessage.urlDecode(); 


  /**
   * Erro no RPC 
   */
  if ( oRetorno.iStatus > 1 ) {
    
    alert(sMensagem);
    return false;
  }

  var iNumpre = oRetorno.iNumpre;

  queryString  = '&iNumpre='+ iNumpre ;
  queryString += '&lReemissao=true'  ;
  queryString += '&lBarra=t'          ;
  queryString += '&lDevolucaoAdiantamento=t';
    
  jan = window.open('cai4_recibo003.php?'+queryString,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0); 

  js_voltar();
}

//---------------------------------------------------------------



/*
 * função de Pesquisa para Caracteristica Peculiar
 */
function js_pesquisaCaracteristicaPeculiar(mostra) {

  if (mostra == true) {

    var sUrl = 'func_concarpeculiar.php?funcao_js=parent.js_mostraCaracteristicaPeculiar1|c58_sequencial|c58_descr';
    js_OpenJanelaIframe('',
                        'db_iframe_CaracteristicaPeculiar',
                        sUrl,
                        'Pesquisar Caracteristica Peculiar',
                        true);
  } else {

    if ($('iCaracteristicaPeculiar').value != '') {

      js_OpenJanelaIframe('',
                          'db_iframe_tiporeceita',
                          'func_concarpeculiar.php?pesquisa_chave='+$('iCaracteristicaPeculiar').value+
                          '&funcao_js=parent.js_mostraCaracteristicaPeculiar',
                          'Pesquisar Caracteristica Peculiar',
                          false);
     } else {
       $('iCaracteristicaPeculiar').value = '';
     }
  }
}

function js_mostraCaracteristicaPeculiar(chave,erro) {

	  $('sCaracteristicaPeculiar').value = chave;
	  if (erro == true) {

	    $('iCaracteristicaPeculiar').focus();
	    $('iCaracteristicaPeculiar').value = '';
	  } else {

	  }
}
function js_mostraCaracteristicaPeculiar1(chave1,chave2) {

  $('iCaracteristicaPeculiar').value = chave1;
  $('sCaracteristicaPeculiar').value = chave2;
  $('iCaracteristicaPeculiar').focus();
  db_iframe_CaracteristicaPeculiar.hide();
}

//---------------------------------------------------------------
/*
 * função de Pesquisa para o tipo de receita
 */
function js_pesquisaTipoReceita(mostra) {

  if (mostra == true) {


    //func_tabrec_recurso.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_drecei|recurso|k02_estorc
    
    var sUrl = 'func_receitaDevolucaoAdiantamento.php?funcao_js=parent.js_mostraTipoReceita1|k02_codigo|k02_drecei';
    js_OpenJanelaIframe('',
                        'db_iframe_tiporeceita',
                        sUrl,
                        'Pesquisar Tipo de Receita',
                        true);
  } else {

    if ($('iReceita').value != '') {

      js_OpenJanelaIframe('',
                          'db_iframe_tiporeceita',
                          'func_receitaDevolucaoAdiantamento.php?pesquisa_chave='+$('iReceita').value+
                          '&funcao_js=parent.js_mostraTipoReceita',
                          'Pesquisar Tipo de Receita',
                          false);
     } else {
       $('iReceita').value = '';
     }
  }
}

function js_mostraTipoReceita(chave,erro) {

	  $('sReceita').value = chave;
	  if (erro == true) {

	    $('iReceita').focus();
	    $('iReceita').value = '';
	  } else {

	  }
}
function js_mostraTipoReceita1(chave1,chave2) {


  
  $('iReceita').value = chave1;
  $('sReceita').value = chave2;
  $('iReceita').focus();
  db_iframe_tiporeceita.hide();
}



/*
 * funçao para renderizar a grid,
 */
function js_criaGridDetalhamento() {

  oGridDetalhamento = new DBGrid('Detalhamento');
  oGridDetalhamento.nameInstance = 'oGridDetalhamento';
  oGridDetalhamento.setCheckbox(0);
  
  oGridDetalhamento.setCellWidth(new Array( '100px' ,
		                                        '300px',
		                                        '100px',
                                            '300px',
                                            '100px'
                                           ));
  oGridDetalhamento.setCellAlign(new Array( 'right' ,
		                                        'left' ,
		                                        'right' ,
                                            'left' ,
                                            'right'
                                           ));
  oGridDetalhamento.setHeader(new Array( 'Código Receita',
		                                     'Descrição Receita',
		                                     'Código CP/CA' ,
                                         'Descrição CP/CA',
                                         'Valor'
                                        ));
  oGridDetalhamento.hasTotalizador = true;
  oGridDetalhamento.setHeight(200);
  oGridDetalhamento.show($('ctnGridDetalhamento'));
  oGridDetalhamento.clearAll(true);
}
js_criaGridDetalhamento();
</script>