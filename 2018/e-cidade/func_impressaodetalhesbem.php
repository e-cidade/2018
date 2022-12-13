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
 
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");

$oGet = db_utils::postMemory($_GET, false);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body>
	<center>
		<fieldset>
			<legend><strong>Imprimir Pesquisa</strong></legend>
        
      <table>
        
       	<tr>
        		
       		<td>
       			<strong>Escolha o que será impresso no relatório:</strong>
       		</td>
        		
       	</tr>
        	
       	<tr>
       	
       		<td>
       			<input class="sOpcoes" type="checkbox" id="dadosMaterial" value="1" checked>
       			<label for="dadosMaterial">Dados Material</label>
       		</td>
        	
       	</tr>
        	
       	<tr>
        	
       		<td>
       			<input class="sOpcoes" type="checkbox" id="dadosImovel" value="1" checked>
       			<label for="dadosImovel">Dados Imóvel</label>
       		</td>
        	
       	</tr>
        	
       	<tr>
        	
       		<td>
       			<input class="sOpcoes" type="checkbox" id="historicoMovimentacao" value="1" checked>
       			<label for="historicoMovimentacao">Histórico Movimentação</label>
       		</td>
        		
       	</tr>
        	
       	<tr>
        	
       		<td>
       			<input class="sOpcoes" type="checkbox" id="historicoFinanceiro" value="1" checked>
       			<label for="historicoFinanceiro">Histórico Financeiro</label>
       		</td>
        		
       	</tr>
        	
       	<tr>
        	
       		<td>
       			<input class="sOpcoes" type="checkbox" id="historicoPlaca" values="1" checked>
       			<label for="historicoPlaca">Placas</label>
       		</td>
        		
       	</tr>
       	
       	<tr>
       	
       		<td align="center">
       			<input type="button" value="Imprimir" onclick="js_visualizaRelatorio(<?php echo $oGet->t52_bem; ?>);">
       		</td>
       		
       	</tr>
        	
      </table>
    </fieldset>
  </center>
</body>
</html>
<script>

/**
 * Percorremos as opções de impressão e chamamos o fonte responsável pela impressão
 */
function js_visualizaRelatorio(iBem){

	/**
	 * Montamos a string que passa as variáveis atrevés de GET
	 */
  var sGetUrl = '?t52_bem='+iBem;
  if ($('dadosMaterial').checked){
    sGetUrl += '&lDadosMaterial=true';
  }
  if ($('dadosImovel').checked){
    sGetUrl += '&lDadosImovel=true';
  }
  if ($('historicoMovimentacao').checked){
    sGetUrl += '&lHistoricoMovimentacao=true';
  }
  if ($('historicoFinanceiro').checked){
    sGetUrl += '&lHistoricoFinanceiro=true';
  }
  if ($('historicoPlaca').checked){
    sGetUrl += '&lHistoricoPlaca=true';
  }

  /**
   * Chamamos efetivamente o fonte que monta o pdf do relatório
   */
  var sUrl = 'func_impressaodetalhesbem002.php' + sGetUrl;
  var sConfiguracao  = 'width='+(screen.availWidth-5)+',height=';
  		sConfiguracao += (screen.availHeight-40)+',scrollbars=1,location=0 ';
  var jan  = window.open(sUrl, '', sConfiguracao);
}
</script>