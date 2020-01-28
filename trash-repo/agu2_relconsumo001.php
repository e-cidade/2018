<?php
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
require("libs/db_app.utils.php");

$clArqAuxiliar = new cl_arquivo_auxiliar();
      
$clArqAuxiliar->db_opcao       = 2;
$clArqAuxiliar->tipo           = 2;
$clArqAuxiliar->top            = 0;
$clArqAuxiliar->linhas         = 4;
$clArqAuxiliar->vwidth         = 350;
$clArqAuxiliar->Labelancora    = 'C&oacute;digo';

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
<script>
function js_escondeFieldset(){ 

	var oFields = document.getElementsByTagName("fieldset");

	//Percorre os fieldset da pagina  
  for(var i=0;i < oFields.length;i++){
   
	  var oCampo = oFields[i];
      
      //CSS para UI
	  oCampo.style.width = '350px';
	  oCampo.style.cursor = 'pointer';
      
      //Elementos Filhos
	  var oLegend = oCampo.getElementsByTagName("legend");
	  var oTable = oCampo.getElementsByTagName("table");   
	  oTable[0].style.display  = 'none';  
	  oLegend[0].style.background = 'url(imagens/seta.gif) no-repeat right';
	  oLegend[0].style.paddingRight  = '10px'; 
	  oLegend[0].observe('click', function () {
		  js_mostraFieldset(this);
    }) ; 
         
  }
}

//js_mostraFieldset
//Função executada no click do label
//@param: objeto label
//primeiro a funcao pega o seu elemento pai, depois seleciona uma tablea dentro dele
//testa se a tabela está visivel e muda a propriedade display do css e a imagem do label

function js_mostraFieldset(oLegend){

	var oTable  = (oLegend.parentNode).getElementsByTagName("table");   
	
	if(oTable[0].style.display == 'block'){
	
	 oLegend.style.background = 'url(imagens/seta.gif) no-repeat right';
	 oTable[0].style.display = 'none';
	
	} else {
	
	 oLegend.style.background = 'url(imagens/setabaixo.gif) no-repeat right';
	 oTable[0].style.display = 'block';

  }

}

function js_processa() {

	var sQueryString = '';

	var oZonaFiscal  = document.form1.zona_fiscal;
	var oZonaEntrega = document.form1.zona_entrega;
	var oLogradouro  = document.form1.logradouro;
	var oBairro      = document.form1.bairro;

	var sZonaFiscal  = '';
	var sZonaEntrega = '';
	var sLogradouro  = '';
	var sBairro      = ''; 
	
	var sVirgula    = '';
	
	if (oZonaFiscal.length > 0) {

    for (var i = 0; i < oZonaFiscal.length; i++) {

      sZonaFiscal  += sVirgula+oZonaFiscal.options[i].value;
      sVirgula      = ',';
       
    }
		  
	}

  sVirgula     = '';	
  if (oZonaEntrega.length > 0) {

    for (var i = 0; i < oZonaEntrega.length; i++) {

      sZonaEntrega += sVirgula+oZonaEntrega.options[i].value;
      sVirgula      = ',';
		       
    }
		      
  }	

  sVirgula    = '';  
  if (oLogradouro.length > 0) {
	    
    for (var i = 0; i < oLogradouro.length; i++) {

      sLogradouro  += sVirgula+oLogradouro.options[i].value;
      sVirgula      = ',';
	           
    }
	          
  }  


  sVirgula = '';
  if (oBairro.length > 0) {
	  
    for (var i = 0; i < oBairro.length; i++) {

      sBairro  += sVirgula+oBairro.options[i].value;
      sVirgula  = ',';
             
    }
	            
  }

  sQueryString  = '?zonafiscal='  + sZonaFiscal;

  sQueryString += '&zonaentrega=' + sZonaEntrega;

  sQueryString += '&logradouro='  + sLogradouro; 

  sQueryString += '&bairro='      + sBairro;

  sQueryString += '&consumoinicial=' + $F('consumoinicial');

  sQueryString += '&consumofinal='   + $F('consumofinal') ;

  sQueryString += '&datainicial='    + $F('datainicial');

  sQueryString += '&datafinal='      + $F('datafinal');

  sQueryString += '&ordenar='        + $F('ordenar');

  window.open('agu2_relconsumo002.php' + sQueryString , '', 'width='+(screen.availWidth-5)+', height='+(screen.availHeight-40)+', scrollbars=1, location=0 '); 
  
}
</script>
</head>
<body bgcolor=#CCCCCC onload="js_escondeFieldset();">
<form name="form1" method="POST"  >
<table style="margin: 10px auto">
  <tr>
    <td>
    <?
      $clArqAuxiliar->cabecalho      = '<strong>Zona Fiscal</strong>';
      $clArqAuxiliar->codigo         = 'j50_zona'; 
      $clArqAuxiliar->descr          = 'j50_descr';
      $clArqAuxiliar->nomeobjeto     = 'zona_fiscal';
      $clArqAuxiliar->funcao_js      = 'js_mostra_zona';
      $clArqAuxiliar->funcao_js_hide = 'js_mostra_zona1';
      $clArqAuxiliar->func_arquivo   = 'func_zonas.php'; 
      $clArqAuxiliar->nomeiframe     = 'db_iframe_zonas';
      $clArqAuxiliar->nome_botao     = 'db_lanca_zonas';
      $clArqAuxiliar->funcao_gera_formulario();
    ?>
    </td>
  </tr>
  
  <tr>
    <td>
    <? 
      $clArqAuxiliar->cabecalho      = '<strong>Zona de Entrega</strong>';
      $clArqAuxiliar->codigo         = 'j85_codigo'; //chave de retorno da func
      $clArqAuxiliar->descr          = 'j85_descr';   //chave de retorno
      $clArqAuxiliar->nomeobjeto     = 'zona_entrega';
      $clArqAuxiliar->funcao_js      = 'js_mostra_zona_ent';
      $clArqAuxiliar->funcao_js_hide = 'js_mostra_zona_ent1';
      $clArqAuxiliar->func_arquivo   = 'func_iptucadzonaentrega.php';  //func a executar
      $clArqAuxiliar->nomeiframe     = 'db_iframe_zona_ent';
      $clArqAuxiliar->nome_botao     = 'db_lanca_zona_ent';
      $clArqAuxiliar->funcao_gera_formulario();
    ?>
    </td>
  </tr>
  
  <tr>
    <td>
    <?
      $clArqAuxiliar->cabecalho      = '<strong>Logradouros</strong>';
      $clArqAuxiliar->codigo         = 'j14_codigo'; //chave de retorno da func
      $clArqAuxiliar->descr          = 'j14_nome';   //chave de retorno
      $clArqAuxiliar->nomeobjeto     = 'logradouro';
      $clArqAuxiliar->funcao_js      = 'js_mostra_logradouro';
      $clArqAuxiliar->funcao_js_hide = 'js_mostra_logradouro1';
      $clArqAuxiliar->func_arquivo   = 'func_ruas.php';  //func a executar
      $clArqAuxiliar->nomeiframe     = 'db_iframe_ruas';
      $clArqAuxiliar->nome_botao     = 'db_lanca_logradouro';
      $clArqAuxiliar->funcao_gera_formulario();
    ?>
    </td>
  </tr>
  
  <tr>
    <td>
    <?
      $clArqAuxiliar->cabecalho      = '<strong>Bairros</strong>';
      $clArqAuxiliar->codigo         = 'j13_codi'; //chave de retorno da func
      $clArqAuxiliar->descr          = 'j13_descr';   //chave de retorno
      $clArqAuxiliar->nomeobjeto     = 'bairro';
      $clArqAuxiliar->funcao_js      = 'js_mostra_bairro';
      $clArqAuxiliar->funcao_js_hide = 'js_mostra_bairro1';
      $clArqAuxiliar->func_arquivo   = 'func_bairro.php';  //func a executar
      $clArqAuxiliar->nomeiframe     = 'db_iframe_bairro';
      $clArqAuxiliar->nome_botao     = 'db_lanca_bairro';
      $clArqAuxiliar->funcao_gera_formulario();
    ?>
    </td>
  </tr>
  
  <tr>
    <td align="center"><br/>
      <strong>M&eacute;dia Consumo:</strong>
      <? db_input('consumoinicial', 10, $consumoinicial, true, 'text', 1)?>
         a
      <? db_input('consumofinal', 10, $consumofinal, true, 'text', 1)?>
      
    </td>
  </tr>
  
  <tr>
    <td align="center"><br/>
      <strong>Per&iacute;odo:</strong>
      <? db_inputdata('datainicial', $datainicial_dia, $datainicial_mes, $datainicial_ano, true, 'text', 1)?>
      a
      <? db_inputdata('datafinal', $datafinal_dia, $datafinal_mes, $datafinal_ano, true, 'text', 1)?>
      
    </td>
  </tr>
  
  <tr>
    <td align="center"><br/>
      <strong>Ordenar por:</strong>
      <?
        $aOrdenar = array('matricula'          => 'Matr&iacute;cula'             , 
                          'nome'               => 'Nome'                         , 
                          'logradouro, numero' => 'Logradouro'                   , 
                          'datainstalacao'     => 'Data Instala&ccedil;&atilde;o', 
                          'consumomedio'       => 'Consumo M&eacute;dio'         , 
                          'consumototal'       => 'Consumo Total'                );
        
        db_select('ordenar', $aOrdenar, true, 1);
      ?>
    </td>
  </tr>
  
  <tr>
    <td align="center">
      <input type="button" name="processar" value="Processar" onclick="js_processa()" />
    </td>
  </tr>
</table>

<? 
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</form>
</body>
</html>