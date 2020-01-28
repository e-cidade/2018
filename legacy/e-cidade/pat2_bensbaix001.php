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
include("classes/db_bensbaix_classe.php");
$clbensbaix = new cl_bensbaix;
$clrotulo = new rotulocampo;
$clbensbaix->rotulo->label();
db_postmemory($HTTP_POST_VARS);
$t55_dataINI="";
$t55_dataFIM = "";
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>

<script>
function js_abre () {

  d = document.form1;

  var dDataInicial  = d.t55_dataINI.value;
  var dDataFinal    = d.t55_dataFIM.value;
  var iPlacaInicial = d.placaini.value;
  var iPlacaFinal   = d.placafim.value;
  
  // Data Inicial
  var iDataDiaIni = d.t55_dataINI_dia.value;
  var iDataMesIni = d.t55_dataINI_mes.value;
  var iDataAnoIni = d.t55_dataINI_ano.value;
  // Data Final
  var iDataDiaFim = d.t55_dataFIM_dia.value;
  var iDataMesFim = d.t55_dataFIM_mes.value;
  var iDataAnoFim = d.t55_dataFIM_ano.value;
  
  var sDataInicial = "";
  var sDataFinal   = "";

  
  if ( iPlacaInicial == '' && iPlacaFinal == '' && dDataInicial == '' && dDataFinal == '' ) {
  
    alert (_M("patrimonial.patrimonio.pat2_bensbaix001.preencha_datas_placas"));
    return false;
  }
  
  if ( iPlacaInicial != '' || iPlacaFinal != '' ) {
  
    if ( iPlacaFinal != '' && new Number(iPlacaInicial) > new Number(iPlacaFinal) ) {
    
      alert (_M("patrimonial.patrimonio.pat2_bensbaix001.placa_inicial_menor_placa_final"));
      return false;
    }
  }
  
  if ( dDataInicial != '' || dDataFinal != '') {
    
    sDataInicial = iDataAnoIni+"-"+iDataMesIni+"-"+iDataDiaIni;
    sDataFinal   = iDataAnoFim+"-"+iDataMesFim+"-"+iDataDiaFim;
    
    dInicial = new Date (iDataAnoIni,iDataMesIni-1,iDataDiaIni);
    dFinal   = new Date (iDataAnoFim,iDataMesFim-1,iDataDiaFim);
    
    if ( dInicial > dFinal ) {
      alert (_M("patrimonial.patrimonio.pat2_bensbaix001.data_inicial_menor_data_final"));
      return false;
    }
  }

  var sOrder = '';
  if ($F('sOrder') != '' ) {
    sOrder = $F('sOrder');
  }

  
  var sQuery  = "pat2_bensbaix002.php?dataINI="+sDataInicial;
      sQuery += "&dataFIM="+sDataFinal;
      sQuery += "&placaInicial="+iPlacaInicial;
      sQuery += "&placaFinal="+iPlacaFinal;
      sQuery += "&sOrder=" + sOrder;
  
  jan = window.open(sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
  
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>
<form class="container" name="form1" method="post" >
  <fieldset>
    <legend>Baixa de Bens</legend>
    <table class="form-container">
      <tr> 
        <td nowrap title="Bens baixados no intervalo de data"> <? db_ancora(@$Lt55_baixa,"",3);?>  </td>
        <td nowrap>
          <?
            db_inputdata('t55_dataINI',@$t55_dataINI_dia,@$t55_dataINI_mes,@$t55_dataINI_ano,true,'text',1,"");
          ?>
        </td>
        <td nowrap>a</td>
        <td nowrap>
          <?
            db_inputdata('t55_dataFIM',@$t55_dataFIM_dia,@$t55_dataFIM_mes,@$t55_dataFIM_ano,true,'text',1,"");
          ?>
        </td>
      </tr>
      <tr>
        <td><? db_ancora("Placas de: ", "js_pesquisaPlacaInicial(true);",1); ?></td>
        <td>
          <?
            db_input('placaini',10, true, 1, 'text', 1, "onchange='js_pesquisaPlacaInicial(false)'");
          ?>
        </td>
        <td> <b><? db_ancora('até', "js_pesquisaPlacaFinal(true);",1); ?></b></td>
        <td>
          <?
            db_input('placafim', 10, true, 1, 'text', 1, "onchange='js_pesquisaPlacaFinal(false)'");
          ?>
        </td>
      </tr>
      
      <tr>
      
      <td colspan="1"><strong>Ordenação: </strong> </td>
      <td colspan="3"> 
      
        <select id='sOrder'>
          <option value=''>Selecione... </option>
          <option value='t64_descr'>Nome </option>
          <option value='t52_bem'>Sequencial </option>
          <option value='t52_ident'>Placa </option>
        </select>
      
      </td>
      
      </tr>
      
      
      
      
    </table>
  </fieldset>
  <input name="relatorio" type="button" onclick='js_abre ();' value="Gerar Relatório">
</form>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>


<script>

/**
 * Funções para a seleção de Placa Inicial
 */
function js_pesquisaPlacaInicial(mostra) {
  
  if (mostra == true) {

    var sUrlOpenInicial = "func_bens.php?funcao_js=parent.js_preenchePlacaInicial|t52_ident";
    js_OpenJanelaIframe('top.corpo', 'db_iframe_bens', sUrlOpenInicial, 'Pesquisa', true);
  } else {
  
    var sUrlOpenInicial = "func_bens.php?funcao_js=parent.js_preenchePlacaInicial|t52_ident";
    js_OpenJanelaIframe('top.corpo', 'db_iframe_bens', sUrlOpenInicial, 'Pesquisa', false);
  }
}

function js_preenchePlacaInicial(placaInicial) {
  if (placaInicial != '') {
    document.form1.placaini.value = placaInicial;
    db_iframe_bens.hide();
  }
}


/**
 * Funções para a seleção de Placa Final
 */
function js_pesquisaPlacaFinal(mostra) {
  
  if (mostra == true) {

    var sUrlOpenFinal = "func_bens.php?funcao_js=parent.js_mostraplacafim1|t52_ident";
    js_OpenJanelaIframe('top.corpo', 'db_iframe_bens', sUrlOpenFinal, 'Pesquisa', true);
  } else {

     if (document.form1.placafim.value != '') { 
        
       var sUrlOpenFinal = "func_bens.php?pesquisa_chave="+document.form1.placafim.value+"&lRetornoPlaca=true&funcao_js=parent.js_mostraplacafim";
       js_OpenJanelaIframe('top.corpo', 'db_iframe_bens', sUrlOpenFinal, 'Pesquisa', false);
     } else {
       document.form1.placafim.value = ''; 
     }
  }
}

function js_mostraplacafim(chave1, chave2) {

  document.form1.placafim.value = chave1;
  if (chave2 == true) {
    document.form1.placafim.value = '';
  }
  
  db_iframe_bens.hide();
}

function js_mostraplacafim1(placaFinal) {

  var placaInicialCompara = document.form1.placaini.value;
  if (new Number(placaInicialCompara) < new Number(placaFinal)) {
  
    document.form1.placafim.value = placaFinal;
    db_iframe_bens.hide();
  } else {
	  
    alert (_M("patrimonial.patrimonio.pat2_bensbaix001.informe_placa_final_maior_placa_inicial", {placaInicialCompara: placaInicialCompara}));
    return false;
  }
}
</script>


</body>
</html>
<script>

$("t55_dataINI").addClassName("field-size2");
$("t55_dataFIM").addClassName("field-size2");
$("placaini").addClassName("field-size2");
$("placafim").addClassName("field-size2");

</script>