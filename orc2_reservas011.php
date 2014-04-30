<?
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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_liborcamento.php");
require_once("dbforms/db_classesgenericas.php");
$clrotulo = new rotulocampo;
$oAuxDpto = new cl_arquivo_auxiliar;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table  align="center">
    <form name="form1" method="post" action="orc2_reservas002.php">
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Filtros</b>
            </legend>
	          <table>
				      <tr>
				        <td align="left">
				          <strong>Nível :</strong>
				        </td>
				        <td>
								  <?
							       $aNivel = array('1A'=>'Órgão Até o Nível',
							                       '1B'=>'Órgão só o Nível',
							                       '2A'=>'Unidade Até o Nível',
							                       '2B'=>'Unidade só o Nível',
							                       '3A'=>'Função Até o Nível',
							                       '3B'=>'Função só o Nível',
							                       '4A'=>'Subfunção Até o Nível',
							                       '4B'=>'Subfunção só o Nível',
							                       '5A'=>'Programa Até o Nível',
							                       '5B'=>'Programa só o Nível',
							                       '6A'=>'Proj/Ativ Até o Nível',
							                       '6B'=>'Proj/Ativ só o Nível',
							                       '7A'=>'Elemento Até o Nível',
							                       '7B'=>'Elemento só o Nível',
							                       '8A'=>'Recurso Até o Nível',
							                       '8B'=>'Recurso só o Nível',
							                       '9B'=>'Desdobramento só o Nível');
							       
								     db_select('nivel',$aNivel,true,2,"onChange='js_alteraNivel(this.value)' style='width:195px;'");
								     
								     
										 $db_selinstit = db_getsession("DB_instit");
										 db_input("db_selinstit",10,0,true,"hidden",3);				     
								     
								  ?>
				        </td>
				      </tr>
				      <tr>
				        <td align="left">
				          <b>Posição até:</b>
				        </td>
				        <td>
				           <?
				
				             $dtDataIni     = explode('-',date("Y-m-d",db_getsession("DB_datausu")));
				             $data_fin_dia  = $dtDataIni[2]; 
				             $data_fin_mes  = $dtDataIni[1];
				             $data_fin_ano  = $dtDataIni[0];
				             
				             db_inputdata('data_fin',@$data_fin_dia,@$data_fin_mes,@$data_fin_ano,true,'text',1);
				               
				           ?>
				        </td>
				      </tr>
              <tr>
                <td align="left">
                  <strong>Forma de Impressão :</strong>
                </td>
                <td>
                  <?
                     $aFormaImpressao = array('a'=>'Analítico',
                                              's'=>'Sintético');
                     
                     db_select('forma_impressao',$aFormaImpressao,true,2,"style='width:195px;'");
                     
                  ?>
                </td>
              </tr>		
              <tr>
              	<td align="left">
              		<?php 
              		  db_ancora("<strong>Solicitação de Compras:</strong>", "js_pesquisaSolicitacaoInicial();", 1);
              		?>
              	</td>
              	<td>
              		<?php 
              		  db_input('pc10_numero_inicial', 8, 0, true, "text", 1);
              		  db_ancora("<strong>Até:</strong>", "js_pesquisaSolicitacaoFinal();", 1);
              		  db_input('pc10_numero_final', 9, 0, true, "text", 1);
              		?>
              	</td>
              </tr>	
              <tr>
              	<td align="left">
              		<?php 
              		  db_ancora("<strong>Autorização de Empenho:</strong>", "js_pesquisaAutorizacaoEmpenhoInicial();", 1);
              		?>
              	</td>
              	<td>
              		<?php 
              		  db_input('o83_autori_inicial', 8, 0, true, "text", 1);
              		  db_ancora("<strong>Até:</strong>", "js_pesquisaAutorizacaoEmpenhoFinal();", 1);
              		  db_input('o83_autori_final', 9, 0, true, "text", 1);
              		?>
              	</td>
              </tr>
              <tr>
              	<td colspan="2">
              		<?php
              		  /**
              		   * Montamos o componente de seleção de departamentos
              		   */ 
                		$oAuxDpto->cabecalho      = "<strong>Departamento</strong>";
                		$oAuxDpto->codigo         = "coddepto"; //chave de retorno da func
                		$oAuxDpto->descr          = "descrdepto";   //chave de retorno
                		$oAuxDpto->nomeobjeto     = 'departamento';
                		$oAuxDpto->funcao_js      = 'js_mostra_departamento';
                		$oAuxDpto->funcao_js_hide = 'js_mostra_departamento1';
                		$oAuxDpto->sql_exec       = "";
                		$oAuxDpto->func_arquivo   = "func_db_depart.php";  //func a executar
                		$oAuxDpto->nomeiframe     = "db_iframe_db_depart";
                		$oAuxDpto->localjan       = "";
                		$oAuxDpto->db_opcao       = 2;
                		$oAuxDpto->tipo           = 2;
                		$oAuxDpto->top            = 0;
                		$oAuxDpto->linhas         = 5;
                		$oAuxDpto->vwidth         = 400;
                		$oAuxDpto->nome_botao     = 'db_lanca';
                		$oAuxDpto->fieldset       = false;
                		$oAuxDpto->funcao_gera_formulario();
              		?>
              	</td>
              </tr>	      
			      </table>
          </fieldset>
		    </td>
		  </tr>                 
      <tr>
        <td colspan="2">
          <fieldset>
            <legend>
              <b>Tipos de Reserva</b>
            </legend>
            <table>
              <tr>
                <td><input type="checkbox" name="chkSol" />            </td>
                <td>Proveniente de Solicitações de Compras             </td>
              </tr>            
              <tr>
                <td><input type="checkbox" name="chkAut"/>            </td>
                <td>Proveniente de Autorizações de Empenhos            </td>
              </tr>              
              <tr>
                <td><input type="checkbox" name="chkSupl"/>           </td>              
                <td>proveniente de Créditos Adicionais (Suplementações)</td>
              </tr>
              <tr>
                <td><input type="checkbox" name="chkMan"/>            </td>              
                <td>Proveniente de Inclusão Manual                     </td>
              </tr>
              <tr>
                <td><input type="checkbox" name="chkProc"/>           </td>              
                <td>Inclusas mediante a Processamento de Reservas      </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <input  name="vernivel"            id="vernivel"            type="hidden" value="" >
      <input  name="filtra_despesa"      id="filtra_despesa"      type="hidden" value="" >
      <input  name="aListaTipo"          id="aListaTipo"          type="hidden" value="" >
      <input  name="aListaDepartamentos" id="aListaDepartamentos" type="hidden" value="" >
	    <tr>
	      <td align = "center"> 
	        <input  name="emite" id="emite" type="button" value="Processar" onclick="js_emite();" >
	      </td>
	    </tr>
    </form>
  </table>
  <table align="center"  id="msgUsuario" style="display:none" >
    <tr>
      <td>
        <b>Para o nível de desdobramento só é possível imprimir as Reservas de Saldo</b>
      </td>
    </tr>
    <tr>
      <td align="center">
        <b>provenientes de Solicitações de Compra e de Autorizações de Empenho</b>
      </td>
    </tr>    
  </table>
</body>
</html>
<script>

	variavel = 1;

	/**
	 * Início das funções de pesquisa de solicitação
	 */
	function js_pesquisaSolicitacaoInicial() {

	  var sUrlLookUp = 'func_solicita.php?funcao_js=parent.js_mostraRetornoPesquisaSolicitacaoInicial|pc10_numero';
	  js_OpenJanelaIframe('',
	  	                  'db_iframe_solicita_inicial',
	  	                  sUrlLookUp,
	  	                  'Pesquisa de Solicitações',
	  	                  true);
	}

	function js_mostraRetornoPesquisaSolicitacaoInicial(){
		
		$('pc10_numero_inicial').value = arguments[0];
		db_iframe_solicita_inicial.hide();
	}

	function js_pesquisaSolicitacaoFinal() {

	  var sUrlLookUp = 'func_solicita.php?funcao_js=parent.js_mostraRetornoPesquisaSolicitacaoFinal|pc10_numero';
	  js_OpenJanelaIframe('',
	  	                  'db_iframe_solicita_final',
	  	                  sUrlLookUp,
	  	                  'Pesquisa de Solicitações',
	  	                  true);
	}

	function js_mostraRetornoPesquisaSolicitacaoFinal(){
		
		$('pc10_numero_final').value = arguments[0];
		db_iframe_solicita_final.hide();
	}
	
	/**
	 * Final das funções de pesquisa de solicitação
	 */

	/**
	 * Início das funções de pesquisa de autorização de empenho
	 */

	function js_pesquisaAutorizacaoEmpenhoInicial(){

	  var sUrlLookUp = 'func_orcreservaaut.php?funcao_js=parent.js_mostraRetornoPesquisaAutorizacaoEmpenhoInicial|o83_autori';
	  js_OpenJanelaIframe('',
                        'db_iframe_autorizacao_empenho_inicial',
                        sUrlLookUp,
                        'Pesquisa de Autorizações de Empenho',
                        true);
	}

	function js_mostraRetornoPesquisaAutorizacaoEmpenhoInicial(teste){

		$('o83_autori_inicial').value = arguments[0];
		db_iframe_autorizacao_empenho_inicial.hide();
	}

	function js_pesquisaAutorizacaoEmpenhoFinal(){

	  var sUrlLookUp = 'func_orcreservaaut.php?funcao_js=parent.js_mostraRetornoPesquisaAutorizacaoEmpenhoFinal|o83_autori';
	  js_OpenJanelaIframe('',
                        'db_iframe_autorizacao_empenho_final',
                        sUrlLookUp,
                        'Pesquisa de Autorizações de Empenho',
                        true);
	}

	function js_mostraRetornoPesquisaAutorizacaoEmpenhoFinal(){

		$('o83_autori_final').value = arguments[0];
		db_iframe_autorizacao_empenho_final.hide();
	}
	
	/**
	 * Final das funções de pesquisa de autorização de empenho
	 */
	
	function js_emite(){
	
	  var aListaChk      = $$('input[type="checkbox"]');
	  var aListaTipo     = new Array();
	  var aDepartamentos = js_buscaDepartamentoSelecionado();
	  
    aListaChk.each( 
      function(eElem){ 
        if ( eElem.checked ) {
          aListaTipo.push(eElem.name);
        }
      }
    )	  
	  
	  if ( aListaTipo.length == 0 ) {
		  
	    alert('Selecione pelo menos um tipo de reserva!');
	    return false;
	  }

		/**
		 * Validamos o período de Solicitação de Compras
		 */
	  if ($('pc10_numero_inicial').value.trim() !== "") {

		  if ($('pc10_numero_final').value.trim() !== "") {

			  if ($F('pc10_numero_inicial') > $F('pc10_numero_final')) {
				  
				  alert("A Solicitação de Compras inicial deve ser menor do que a final para que o intervalo seja válido.");
				  return false;
			  }
		  } else {
			  $('pc10_numero_final').value = $('pc10_numero_inicial').value;
		  }
	  }

	  /**
	   * Validamos o período de autorização de empenhos
	   */
	  if ($('o83_autori_inicial').value.trim() !== "") {

		  if ($('o83_autori_final').value.trim() !== "") {

			  if ($F('o83_autori_inicial') > $F('o83_autori_final')) {

				  alert("A Autorização de Empenho inicial deve ser menor do que a final para que o intervalo seja válido.");
				  return false;
			  } 
		  } else {
			  $('o83_autori_final').value = $('o83_autori_inicial').value;
		  }
	  }
	  
	  $('filtra_despesa').value      = parent.iframe_filtro.js_atualiza_variavel_retorno();
	  $('aListaTipo').value          = Object.toJSON(aListaTipo);
	  $('aListaDepartamentos').value = Object.toJSON(aDepartamentos);
	  
		jan = window.open('','iframe_reservasaldo','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
		document.form1.target = 'iframe_reservasaldo';
		setTimeout("document.form1.submit()",1000);
		return true;
	  
	}

	/**
	 * Percorre o select de departamentos e retorna um array com os valores
	 */
	function js_buscaDepartamentoSelecionado() {

	  iDptos = document.getElementById("departamento").length;
	  aValoresDpto = new Array();
	  for (var i = 0; i < iDptos; i++) {
      aValoresDpto.push(document.getElementById("departamento")[i].value);
	  }
	  return aValoresDpto;
	}

  function js_alteraNivel(sNivel){
    
    var aListaChk = $$('input[type="checkbox"]');
      
    aListaChk.each( 
	    function(eElem){ 
        if ( sNivel == '9B' ) {
          if ( eElem.name == 'chkSol' ||  eElem.name == 'chkAut') {
            eElem.checked = true;
          } else {
            eElem.checked = false;
          }
          eElem.disabled = true;
          $('msgUsuario').style.display = '';
	      } else {
	        $('msgUsuario').style.display = 'none';
	        eElem.disabled = false;
	      }
      }
    )
    
  }

</script>