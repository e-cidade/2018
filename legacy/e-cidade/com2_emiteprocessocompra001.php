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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo();
$clrotulo->label("pc80_codproc");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="" >
	<br>
	<br>
	<center>
  	<fieldset style="width: 480px;">
  		<legend><strong>Reemissão de Processo de Compras</strong></legend>
  		
  		<table>
        <tr>
          <td style="font-weight: bolder;" >
            <? db_ancora("Processos de Compra de : ","js_pesquisaProcessoCompras(true, true);",1);?>
          </td>
          <td>
            <?
              db_input("pc80_codproc", 10, $Ipc80_codproc, 
                       true, 
                       "text", 
                       4,
                       "onchange='js_pesquisaProcessoCompras(false, true);'",
                       "pc80_codprocini"
                      ); 
            ?>
          </td>
          <td style="font-weight: bolder;">
            <? db_ancora("<b>Até:</b> ","js_pesquisaProcessoCompras(true, false);",1);?> 
          </td>
          <td>
            <?
              db_input("pc80_codproc_fim", 10, $Ipc80_codproc, 
                       true, 
                       "text", 
                       4,
                       "onchange='js_pesquisaProcessoCompras(false, false);'",
                       "pc80_codprocfim"
                      ); 
            ?>
          </td>
        </tr>
        
        
  			<tr>
  				<td><strong>Data de: </strong></td>
  				<td><?php db_inputdata('pc80_data_inicial', '', '', '', true, 'text', 1, 
  				                       ' onchange="js_preencheIntervaloData();" '); ?></td>
  				<td><Strong>Até: </Strong></td>
  				<td><?php db_inputdata('pc80_data_final', '', '', '', true, 'text', 1); ?></td>
  			</tr>
  		</table>
  	</fieldset>
  	<br>
  	<input type="button" name="btnReemite" id="btnReemite" value="Reemitir" onclick="js_reemitirProcesso();">
	</center>
  <?php
    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"),
            db_getsession("DB_anousu"), db_getsession("DB_instit"));
  ?>
</body>
</html>

<script>
function js_pesquisaProcessoCompras(mostra, lInicial) {

  var sFuncaoRetorno         = 'js_mostraProcessoInicial';
  var sFuncaoRetornoOnChange = 'js_mostraProcessoInicialChange';
  var sCampo                 = 'pc80_codprocini';
  if (!lInicial) {
   
    var sFuncaoRetorno         = 'js_mostraProcessoFinal';
    var sFuncaoRetornoOnChange = 'js_mostraProcessoFinalChange';
    var sCampo                 = 'pc80_codprocfim';
  }
  
  if (mostra) {
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_processo',
                        'func_pcproc.php?funcao_js=parent.'+sFuncaoRetorno+'|'+
                        'pc80_codproc','Pesquisa Processo de Compras',true);
  } else {
     
     var sValorCampo = $F(sCampo); 
     if (sValorCampo != '') {
        js_OpenJanelaIframe('top.corpo', 
                            'db_iframe_processo',
                            'func_pcproc.php?pesquisa_chave='+sValorCampo+
                            '&funcao_js=parent.'+sFuncaoRetornoOnChange,
                            'Pesquisa Processo de Compras', 
                            false);
     } else {
       $F(sCampo).value = '';
     }
  }
}

function js_mostraProcessoInicial(iProcesso) {
  
  $('pc80_codprocini').value = iProcesso;  
  db_iframe_processo.hide();
}

function js_mostraProcessoInicialChange(iProcesso, lErro) {
  
  if (lErro) {
    $('pc80_codprocini').value = '';
  } 
}

function js_mostraProcessoFinal(iProcesso) {
  
  db_iframe_processo.hide();
  $('pc80_codprocfim').value = iProcesso;  
}

function js_mostraProcessoFinalChange(iProcesso, lErro) {
  
  if (lErro) {
    $('pc80_codprocfim').value = '';
  } 
}

	/**
	 * Preenchemos o segundo campo do intervalo de código do processo de compra
	 */
	function js_preencheIntervaloCodigo() {

		if ($F('pc80_codprocini').trim() == "") {
			$('pc80_codprocini').value = $F('pc80_codprocini');
		}
		$('pc80_codprocini').focus();
	}

	/**
	 * Preenchemos o segundo campo do intervalo de data do processo de compra
	 */
	function js_preencheIntervaloData() {

		if ($F('pc80_data_final').trim() == "") {
			$('pc80_data_final').value = $F('pc80_data_inicial');
		}
		$('pc80_data_final').focus();
	}

	/**
	 * Validamos as informações do formulário e reemitimos o documento do processo de compra
	 */
	function js_reemitirProcesso() {

		if ($F('pc80_codprocini') == "" && $F('pc80_data_inicial') == "") {

			alert('Deve ser informado um intervalo para efetuar a pesquisa.');
			return false;
		}

		if ($F('pc80_codprocini') !== "" && $F('pc80_codprocini') !== "") {
			if ($F('pc80_codprocini') > $F('pc80_codprocini')) {

				alert('O primeiro processo do intervalo deve ser menor do que o segundo.');
				return false;
			}
		}

		if ($F('pc80_data_inicial') !== "" && $F('pc80_data_final') !== "") {
			if ($F('pc80_data_inicial') > $F('pc80_data_final')) {

				alert('A primeira data do intervalo deve ser menor do que a segunda.');
				return false;
			}
		}

		var sGetUrl  = "?pc80_codproc_inicial="+$F('pc80_codprocini');
				sGetUrl += "&pc80_codproc_final="+$F('pc80_codprocfim');
				sGetUrl += "&pc80_data_inicial="+$F('pc80_data_inicial');
				sGetUrl += "&pc80_data_final="+$F('pc80_data_final');

		var jan = window.open('com2_emiteprocessocompra002.php'+sGetUrl, '', 'location=0, width='+(screen.availWidth - 5)+
		                 'width='+(screen.availWidth - 5)+', scrollbars=1'); 
		jan.moveTo(0, 0);
	}

</script>