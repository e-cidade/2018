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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load("scripts.js, prototype.js, strings.js, arrays.js, dbcomboBox.widget.js, DBFormCache.js, DBFormSelectCache.js"); 
    db_app::load("estilos.css");
    db_app::load("DBLancador.widget.js, DBAncora.widget.js, dbtextField.widget.js, DBToogle.widget.js");
    ?>
  </head>
  <body style='margin-top: 25px' bgcolor="#cccccc">
  
    <div class='container' style="width: 682px;">
      <form name="form1" id='' method="post">
        <fieldset>
          <legend>Por pedido</legend>      
          <table class='form-container'>
            <tr>
              <td nowrap="nowrap" class='bold'>Período de:</td>
              <td nowrap="nowrap"> 
                <?db_inputdata('dtInicio', '', '', '', true, 'text', 1, "onchange ='js_validaData();'", "", "", "parent.js_validaData()")?>
                <label class='bold field-size1' >até:</label>
                <?db_inputdata('dtFim', '', '', '', true, 'text', 1, "onchange ='js_validaData();'", "", "", "parent.js_validaData()")?>
              </td>
            <tr>
            <tr>
              <td id='ctnCgs' colspan="2" ></td>
              <td></td>
            <tr>
            <tr>
              <td id='ctnPedidos' colspan="2" ></td>
            <tr>
          </table>
        </fieldset>
        <input type="button" value = "Imprimir" name='imprimir' id='imprimir' />  
      </form>
    </div>
  </body>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<script type="text/javascript">

/**
 * Validamos o intervalo entre as datas selecionadas
 */
function js_validaData() {

  if ($('dtInicio').value != '' && $('dtFim').value != '') {

    var aDataInicial = new Array();
    var aDataFinal   = new Array();

    aDataInicial[0]      = $F('dtInicio').substr(0, 2);
    aDataInicial[1]      = $F('dtInicio').substr(3, 2);
    aDataInicial[2]      = $F('dtInicio').substr(6, 4);
    var sNovaDataInicial = aDataInicial[2]+'-'+aDataInicial[1]+'-'+aDataInicial[0];

    aDataFinal[0]      = $F('dtFim').substr(0, 2);
    aDataFinal[1]      = $F('dtFim').substr(3, 2);
    aDataFinal[2]      = $F('dtFim').substr(6, 4);
    var sNovaDataFinal = aDataFinal[2]+'-'+aDataFinal[1]+'-'+aDataFinal[0];

    if (js_diferenca_datas(sNovaDataInicial, sNovaDataFinal, 3) == true) {

      $('dtFim').value = "";
      alert(_M("saude.tfd.tfd2_porpedido.conflito_intervalo"));
      return false;
    }
  }
  return true;
}

var oLancadorCGS = new DBLancador('LancadorCGS');   
oLancadorCGS.setNomeInstancia('oLancadorCGS');  
oLancadorCGS.setLabelAncora('CGS: ');   
oLancadorCGS.setParametrosPesquisa('func_cgs_und.php', ['z01_i_cgsund','z01_v_nome','s115_c_cartaosus'], "lRetornaEstrutural=true");
oLancadorCGS.setGridHeight(100);
oLancadorCGS.show($('ctnCgs'));



var oLancadorPedido = new DBLancador('LancadorPedido');   
oLancadorPedido.setNomeInstancia('oLancadorPedido');  
oLancadorPedido.setLabelAncora('Pedidos: ');   
oLancadorPedido.setParametrosPesquisa('func_tfd_pedidotfd.php', ['tf01_i_codigo','z01_v_nome'], "lRetornaEstrutural=true");
oLancadorPedido.setGridHeight(100);
oLancadorPedido.show($('ctnPedidos'));



$('imprimir').observe("click", function () {

  var aCgs    = new Array();
  var aPedido = new Array();
  
  var aCgsSelecionado    = oLancadorCGS.getRegistros();
  var aPedidoSelecionado = oLancadorPedido.getRegistros();
  
  var lSemFiltroSelecionado = true;
  if ($('dtInicio').value != '' || $('dtFim').value != '') {
    lSemFiltroSelecionado = false;
  }

  if (aCgsSelecionado.length > 0) {

    lSemFiltroSelecionado = false;
    aCgsSelecionado.each( function(oCgs) {

      aCgs.push(oCgs.sCodigo);
    });
  }

  if (aPedidoSelecionado.length > 0) {

    lSemFiltroSelecionado = false;
    aPedidoSelecionado.each(function(oPedigo) {
      aPedido.push(oPedigo.sCodigo);
    });
  }
  
  if (lSemFiltroSelecionado && 
      !confirm(_M("saude.tfd.tfd2_porpedido.sem_filtro_selecionado"))) {

    return false
  }

  var sLocation  = "tfd2_porpedido002.php?";
	sLocation     += "dtInicial="+$F('dtInicio');
	sLocation     += "&dtFim="+$F('dtFim');
	sLocation     += "&aCGS="+aCgs;
	sLocation     += "&aPedido="+aPedido;
	
	jan            = window.open(sLocation, '', 
	  	                         'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
    
});

</script>