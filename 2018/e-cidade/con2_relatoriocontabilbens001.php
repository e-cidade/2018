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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_cfpatri_classe.php");
$clcfpatri = new cl_cfpatri;
$clrotulo = new rotulocampo;
$clrotulo->label("t64_class");
$clrotulo->label("t64_descr");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC" style="margin-top:25px" >
  	<form name="form1" id="form1">
	  	<center>
	  		<div style="margin-top: 25px; width: 500px;">
			  	<fieldset>
			  		<legend><b>Filtros</b></legend>
					  <table border='0' width="550px">
							<tr>
								<td class="PrimeiraColuna">
								<?php 
	                db_ancora("<b>Classificação Inicial:</b>", "js_abreLookupClassificacaoInicial(true);", 1);
	              ?>
								</td>
								<td  align="left">
	              <?php 
	                db_input("t64_class", 8, false, true, "text", 3, "onchange='js_abreLookupClassificacaoInicial(false);'");
	                db_input("t64_descr", 38, false, true, "text", 3);
	              ?>
	            	</td>
						  </tr>
						  <tr align="left" nowrap title="Classificação">
						  	<td class="PrimeiraColuna">
						  	<?php 
	                db_ancora("<b>Classificação Final:</b>", "js_abreLookupClassificacaoFinal(true);", 1);
	              ?>
						  	</td>
						    <td  align="left">
	              <?php 
	                db_input("t64_class2", 8, false, true, "text", 3, "onchange='js_abreLookupClassificacaoFinal(false);'");
	                db_input("t64_descr2", 38, false, true, "text", 3);
	              ?>
	            	</td>
							</tr>
							<tr>
								<td class="PrimeiraColuna">
									<b>Valor Contábil:</b>
								</td>
								<td>
									<?php
										$aOpcoes = array(1 => "Anterior à primeira reavaliação", 2 => "Após a Última Reavaliação");
										db_select("valorContabil", $aOpcoes, false, 1);
									?>
								</td>
							</tr>
							<tr>
								<td class="PrimeiraColuna">
									<b>Modelo de Impressão:</b>
								</td>
								<td>
									<?php
										$aOpcoes = array(1 => "Analítico", 2 => "Sintético");
										db_select("modeloRelatorio", $aOpcoes, false, 1,"style='width:231px'");
									?>
								</td>
							</tr>
							
						</table>
					</fieldset>
				</div>
				<table>
  				<tr>
  					<td colspan="2" align="center">
              <input type="button" name="btnGerarRelatorio" value="Gerar Relatório" onclick="js_gerarRelatorio();"/>									
  					</td>
  				</tr>
				</table>
			</center>
		</form>
  <?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
  </body>
</html>
<script type="text/javascript">

var sUrlRPC = "";
									
function js_gerarRelatorio() {
	
	 sQuery  = "iClassificacaoInicial=" + $F('t64_class')+"&iClassificacaoFinal="+$F('t64_class2');
	 sQuery += "&iValorContabil="+$F('valorContabil')+"&iModeloRelatorio="+$F('modeloRelatorio');
	 jan    = window.open('con2_relatoriocontabilbens002.php?'+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	 jan.moveTo(0,0);
}

/**
 * Abre função de pesquisa das classificações
 */
function js_abreLookupClassificacaoInicial(lMostra) {

  if (lMostra == true) {

    var sUrlOpen = "func_clabens.php?funcao_js=parent.js_mostraclabensInicial|t64_class|t64_descr&analitica=true";
    js_OpenJanelaIframe('top.corpo', 'db_iframe_clabens', sUrlOpen, 'Pesquisa Classificação', true);  
  } else {

    testa = new String($('t64_class').value);     
    
     if(testa != '' && testa != 0){
       i = 0;       
       for(i = 0;i < $('t64_class').value.length;i++) {
         testa = testa.replace('.','');       
       }
       js_OpenJanelaIframe('top.corpo','db_iframe_clabens','func_clabens.php?pesquisa_chave='+testa+'&funcao_js=parent.js_mostraclabensInicial1&analitica=true','Pesquisa',false);
     }else{
       $('t64_descr').value = '';
     }
  }
}

function js_mostraclabensInicial1(sDescricao,lErro) {

	$('t64_descr').value = sDescricao;
	
  if(lErro==true) {
	  
    $('t64_class').value = '';
    $('t64_class').focus();
  }
}

function js_mostraclabensInicial(iClassificacao,sClassificacao) {
	
  $('t64_class').value = iClassificacao;
  $('t64_descr').value = sClassificacao;
  db_iframe_clabens.hide();
}

function js_abreLookupClassificacaoFinal(lMostra) {

  if (lMostra == true) {

    var sUrlOpen = "func_clabens.php?funcao_js=parent.js_mostraclabensFinal|t64_class|t64_descr&analitica=true";
    js_OpenJanelaIframe('top.corpo', 'db_iframe_clabens', sUrlOpen, 'Pesquisa Classificação', true);  
  } else {

    testa = new String($F('t64_class2'));
         
     if(testa != '' && testa != 0){
       i = 0;       
       for(i = 0;i < $('t64_class2').value.length;i++){
         testa = testa.replace('.','');       
       }
       js_OpenJanelaIframe('top.corpo','db_iframe_clabens','func_clabens.php?pesquisa_chave='+testa+'&funcao_js=parent.js_mostraclabensFinal1&analitica=true','Pesquisa',false);
     }else{
       $('t64_descr2').value = '';
     }
  }
}

function js_mostraclabensFinal1(sDescricao,lErro) {
	
  $('t64_descr2').value = sDescricao;
  
  if(lErro==true) {
	  
    $('t64_class2').value = '';
    $('t64_class2').focus();
  }
}

function js_mostraclabensFinal(iClasse,sDescricao) {
	
  $('t64_class2').value = iClasse;
  $('t64_descr2').value = sDescricao;
  db_iframe_clabens.hide();
}


</script>
<style type="text/css">
.PrimeiraColuna{
	width: 130px;
}	              
</style>