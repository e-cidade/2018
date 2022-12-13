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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
require("libs/db_app.utils.php");

$oArqAuxiliarLog = new cl_arquivo_auxiliar;
$oArqAuxiliarBai = new cl_arquivo_auxiliar;
$oArqAuxiliarFis = new cl_arquivo_auxiliar;
$oArqAuxiliarEnt = new cl_arquivo_auxiliar;

db_sel_instit(null, "db21_usasisagua");

$lMostraFiltrosAgua = false;
if (isset($db21_usasisagua) and $db21_usasisagua == 't') {
	$lMostraFiltrosAgua = true;
}
?>
<html>
	<head>
		<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="Expires" CONTENT="0">
		<?
      db_app::load('scripts.js, prototype.js');
      db_app::load('estilos.css');
    ?>
    
    <script type="text/javascript">
    function js_escondeFieldset(){
  	  var oFields = document.getElementsByTagName("fieldset");

  	  for(var i=0;i < oFields.length;i++){
  		  var oCampo  = oFields[i];
  		  var oLegend = oCampo.getElementsByTagName("legend");
  		  var oTable  = oCampo.getElementsByTagName("table");   
         
  		  oCampo.style.width  = '445px';
  		  oCampo.style.cursor = 'pointer';
         
  		  oTable[0].style.display       = 'none';  
  		  oLegend[0].style.background   = 'url(imagens/seta.gif) no-repeat right';
  		  oLegend[0].style.paddingRight = '10px'; 
  		  oLegend[0].observe('click', function () {
  			  js_mostraFieldset(this);
  			}) ; 
      }
    }

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

    function js_filtros(sTipo) {
  	  if(sTipo == 'matric') {
    		document.getElementById('filtros').style.display= "block";
  	  } else {	  
  	    document.getElementById('filtros').style.display= "none";
  	  }
  	   
    }    
  	function js_gera_relatorio() {
  	  var sTipo        = document.form1.tipo.value;
  	  var dDataInicial = document.form1.data_inicial.value;
  	  var dDataFinal   = document.form1.data_final.value;
  	  var sOrdenar     = document.form1.ordenar.value;
  	  var sDescricao   = document.form1.descricao.value;
  	  var sOcorrencia  = document.form1.ocorrencia.value;
  	  var sLogradouros = '';
    	var sBairros     = '';
    	var sZonaFiscal  = '';
    	var sZonaEntrega = '';
    	var sVirgula     = '';
    	var sQueryString = '';

    	if((dDataInicial == '') && (dDataFinal != '')) {
  		  alert('Data Inicial não informada.');
  		  return false;
    	}
    	if((dDataInicial != '') && (dDataFinal == '')) {
  		  alert('Data Final não informada.');
  		  return false;
      }

    	if (document.form1.logradouro.length > 0) {
  	    for(var l = 0; l < document.form1.logradouro.length; l++) {
    	    sLogradouros += sVirgula + document.form1.logradouro.options[l].value;
  	      sVirgula      = ',';
  	    }
  	  } 
      sVirgula = '';
    	if (document.form1.bairro.length > 0) {
  	    for(var b = 0; b < document.form1.bairro.length; b++) {
          sBairros += sVirgula + document.form1.bairro.options[b].value;
  	      sVirgula  = ',';
  	    }
  	  }
    	sVirgula = '';
    	if (document.form1.zona_fiscal.length > 0) {
  	    for(f = 0; f < document.form1.zona_fiscal.length; f++) {
          sZonaFiscal += sVirgula + document.form1.zona_fiscal.options[f].value;
  	      sVirgula      = ',';
  	    }
  	  }
    	sVirgula = '';
    	if (document.form1.zona_entrega.length > 0) {
  	    for(e = 0; e < document.form1.zona_entrega.length; e++) {
  	      sZonaEntrega += sVirgula + document.form1.zona_entrega.options[e].value;
  	      sVirgula      = ',';
  	    }
  	  }

  	  sQueryString  = 'arr2_relocorrencias002.php?';
  	  sQueryString += 't='     + sTipo;
  	  sQueryString += '&di='   + dDataInicial;
  	  sQueryString += '&df='   + dDataFinal;
  	  sQueryString += '&o='    + sOrdenar;
  	  sQueryString += '&desc=' + sDescricao;
  	  sQueryString += '&oco='  + sOcorrencia;
  	  sQueryString += '&logs=' + sLogradouros;
  	  sQueryString += '&bai='  + sBairros;
  	  sQueryString += '&zf='   + sZonaFiscal;
  	  sQueryString += '&ze='   + sZonaEntrega;

    	jan = window.open(sQueryString, '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  	  jan.moveTo(0,0);
  	}

  	
    </script>  
  </head>
  
  <body bgcolor="#CCCCCC" onload="js_escondeFieldset();" >

  <form name="form1" method="post" action="" style="text-align: center;">
  
    <table style="margin: 20 auto;">
    
    <tr>
      <td title="Tipos de ocorr&ecirc;ncia"><strong>Tipo</strong></td>
      
      <td>
      <?
        $aTipos = array('matric'=>'Matr&iacute;cula', 'cgm'=>'CGM', 'inscr'=>'Inscri&ccedil;&atilde;o');
        
        db_select('tipo', $aTipos, true, 1, ' onchange="js_filtros(this.value)" ;');
      ?>
      </td>
    </tr>
    
    <tr>
      <td title="Per&iacute;odo das ocorr&ecirc;ncias"><strong>Per&iacute;odo</strong></td>
      
      <td>
      <?
        db_inputdata('data_inicial', '', '', '', true, 'text', 1)
      ?>
      a
      <?
        db_inputdata('data_final'  , '', '', '', true, 'text', 1)
      ?>
      </td>
    </tr>
    
    <tr>
      <td title="Descri&ccedil;&atilde;o da ocorr&ecirc;ncia"><strong>Descri&ccedil;&atilde;o</strong></td>
      
      <td>
      <?
        db_input('descricao', 50, null, true, 'text', 1)
      ?>
      </td>
    </tr>
    
    <tr>
      <td title="Ocorr&ecirc;ncia"><strong>Ocorr&ecirc;ncia</strong></td>
      
      <td>
      <?
        db_input('ocorrencia', 50, null, true, 'text', 1)
      ?>
      </td>
    </tr>
    
    <tr>
      <td title="Ordem do relat&oacute;rio"><strong>Ordenar</strong></td>
      
      <td>
      <?
        $aOrdenar = array('codigo'       => 'C&oacute;digo (CGM, Matr&iacute;cula, Inscri&ccedil;&atilde;o)', 
                          'data'         => 'Data Ocorr&ecirc;ncia', 
                          'descricao'    => 'Descri&ccedil;&atilde;o');
        
        db_select('ordenar', $aOrdenar, true, 1)
      ?>
      </td>
    </tr>
    
    </table>
    
    <div id="filtros" style="<?php if (!$lMostraFiltrosAgua) echo 'display:none';?>">
    <table style="margin: 0 auto;">
    
    <tr>
      <td colspan="2">
      <?
        $oArqAuxiliarLog->cabecalho      = '<strong>Logradouros</strong>';
	      $oArqAuxiliarLog->codigo         = 'j14_codigo'; //chave de retorno da func
	      $oArqAuxiliarLog->descr          = 'j14_nome';   //chave de retorno
	      $oArqAuxiliarLog->nomeobjeto     = 'logradouro';
	      $oArqAuxiliarLog->funcao_js      = 'js_mostra_logradouro';
	      $oArqAuxiliarLog->funcao_js_hide = 'js_mostra_logradouro1';
	      $oArqAuxiliarLog->func_arquivo   = 'func_ruas.php';  //func a executar
	      $oArqAuxiliarLog->nomeiframe     = 'db_iframe_ruas';
	      $oArqAuxiliarLog->nome_botao     = 'db_lanca_logradouro';
	      $oArqAuxiliarLog->Labelancora    = 'C&oacute;digo';
	      $oArqAuxiliarLog->db_opcao       = 2;
	      $oArqAuxiliarLog->tipo           = 2;
	      $oArqAuxiliarLog->top            = 0;
	      $oArqAuxiliarLog->linhas         = 4;
	      $oArqAuxiliarLog->vwidth        = 450;
	      $oArqAuxiliarLog->funcao_gera_formulario();
      ?>
      </td>
    </tr>

    <tr>
      <td colspan="2">
      <?
        $oArqAuxiliarBai->cabecalho      = '<strong>Bairros</strong>';
	      $oArqAuxiliarBai->codigo         = 'j13_codi'; //chave de retorno da func
	      $oArqAuxiliarBai->descr          = 'j13_descr';   //chave de retorno
	      $oArqAuxiliarBai->nomeobjeto     = 'bairro';
	      $oArqAuxiliarBai->funcao_js      = 'js_mostra_bairro';
	      $oArqAuxiliarBai->funcao_js_hide = 'js_mostra_bairro1';
	      $oArqAuxiliarBai->func_arquivo   = 'func_bairro.php';  //func a executar
	      $oArqAuxiliarBai->nomeiframe     = 'db_iframe_bairro';
	      $oArqAuxiliarBai->nome_botao     = 'db_lanca_bairro';
	      $oArqAuxiliarBai->Labelancora    = 'C&oacute;digo';
	      $oArqAuxiliarBai->db_opcao       = 2;
	      $oArqAuxiliarBai->tipo           = 2;
	      $oArqAuxiliarBai->top            = 0;
	      $oArqAuxiliarBai->linhas         = 4;
	      $oArqAuxiliarBai->vwidth       = 450;
	      $oArqAuxiliarBai->funcao_gera_formulario();
	    ?>
      </td>
    </tr>
    
    <tr>
      <td colspan="2">
      <?          
        $oArqAuxiliarFis->cabecalho      = '<strong>Zona Fiscal</strong>';
	      $oArqAuxiliarFis->codigo         = 'j50_zona'; //chave de retorno da func
	      $oArqAuxiliarFis->descr          = 'j50_descr';   //chave de retorno
	      $oArqAuxiliarFis->nomeobjeto     = 'zona_fiscal';
	      $oArqAuxiliarFis->funcao_js      = 'js_mostra_zona';
	      $oArqAuxiliarFis->funcao_js_hide = 'js_mostra_zona1';
	      $oArqAuxiliarFis->func_arquivo   = 'func_zonas.php';  //func a executar
	      $oArqAuxiliarFis->nomeiframe     = 'db_iframe_zonas';
	      $oArqAuxiliarFis->nome_botao     = 'db_lanca_zonas';
	      $oArqAuxiliarFis->Labelancora    = 'C&oacute;digo';
	      $oArqAuxiliarFis->db_opcao       = 2;
	      $oArqAuxiliarFis->tipo           = 2;
	      $oArqAuxiliarFis->top            = 0;
	      $oArqAuxiliarFis->linhas         = 4;
	      $oArqAuxiliarFis->vwidth        = 450;
	      $oArqAuxiliarFis->funcao_gera_formulario();
	    ?>
      </td>
    </tr>
    
    <tr>
      <td>
      <?
        $oArqAuxiliarEnt->cabecalho      = '<strong>Zona de Entrega</strong>';
	      $oArqAuxiliarEnt->codigo         = 'j85_codigo'; //chave de retorno da func
	      $oArqAuxiliarEnt->descr          = 'j85_descr';   //chave de retorno
	      $oArqAuxiliarEnt->nomeobjeto     = 'zona_entrega';
	      $oArqAuxiliarEnt->funcao_js      = 'js_mostra_zona_ent';
	      $oArqAuxiliarEnt->funcao_js_hide = 'js_mostra_zona_ent1';
	      $oArqAuxiliarEnt->func_arquivo   = 'func_iptucadzonaentrega.php';  //func a executar
	      $oArqAuxiliarEnt->nomeiframe     = 'db_iframe_zona_ent';
	      $oArqAuxiliarEnt->nome_botao     = 'db_lanca_zona_ent';
	      $oArqAuxiliarEnt->Labelancora    = 'C&oacute;digo';
	      $oArqAuxiliarEnt->db_opcao       = 2;
	      $oArqAuxiliarEnt->tipo           = 2;
	      $oArqAuxiliarEnt->top            = 0;
	      $oArqAuxiliarEnt->linhas         = 4;
	      $oArqAuxiliarEnt->vwidth        = 450;
	      $oArqAuxiliarEnt->funcao_gera_formulario();
      ?>  
      </td>
    </tr>
    
    </table>
    
    </div>
    
    <input type="button" name="gerar" id="gerar" value="Gerar Relat&oacute;rio" onclick="js_gera_relatorio()"/>

  </form>

  </body>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>