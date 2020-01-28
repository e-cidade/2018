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

/**
 * 
 * @author I
 * @revision $Author: dbiuri $
 * @version $Revision: 1.1 $
 */
require("libs/db_stdlib.php");
require("std/db_stdClass.php");
require("libs/db_app.utils.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_custocriteriorateio_classe.php");
include("classes/db_custoplanilhaorigem_classe.php");
require_once("model/custoPlanilha.model.php");
include("dbforms/db_funcoes.php");
$aParamKeys = array(
                    db_getsession("DB_anousu")
                   );
$aParametrosCustos   = db_stdClass::getParametro("parcustos",$aParamKeys);
$iTipoControleCustos = 0; 
$db_opcao            = 1;
$oDaoCustoOrigem     = new cl_custoplanilhaorigem();
if (count($aParametrosCustos) > 0) {
  $iTipoControleCustos = $aParametrosCustos[0]->cc09_tipocontrole;
}
$oRotuloCampo = new rotulocampo();
$oRotuloCampo->label("cc15_anousu");
$oRotuloCampo->label("cc15_mesusu");
?>
<html>
  <head>
  
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
     db_app::load("scripts.js, prototype.js, strings.js, widgets/windowAux.widget.js, datagrid.widget.js");
     db_app::load("estilos.css, grid.style.css");
    ?>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <table border="0" cellpadding="0" cellspacing="0">
      <tr> 
        <td width="360" height="18">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
      </tr>
    </table>
    <center>
    <form name='frmMapaCustos' method="post">
      <table>
        <tr>
          <td>
            <fieldset>
              <legend>
                <b>Mapa dos Custos</b>
              </legend>
              <table>
                <tr>
                  <td>
                    <b>Mês:</b>
                  </td>
                  <td>
                    <?
                      db_input("cc15_mesusu", 10, $Icc15_mesusu, true,"text", $db_opcao);
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <b>Ano:</b>
                  </td>
                  <td>
                    <?
                      db_input("cc15_anousu", 10, $Icc15_anousu, true,"text", $db_opcao);
                    ?>
                  </td>
                </tr> 
                <tr>
                  <td>
                   <b>Formato:</b>
                  </td>
                  <td>
                    <?
                     $aFormatos = array("1" => "PDF", "2" => "CSV"); 
                     db_select("formato",$aFormatos, true,1);
                    ?>
                  </td>
                </tr>
                <tr id='visualizarforma' style='display: none'>
                  <td>
                   <b>Visulizar:</b>
                  </td>
                  <td>
                    <?
                     $aForma = array("1" => "Por Desdobramento", "2" => "Agrupado por Nivel"); 
                     db_select("visualizar",$aForma, true,1);
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td colspan="2" style="text-align: center">
            <input type="button" id="btnVisualizarPlanilha" value="Visualizar">
          </td>
        <tr>
      </table>
    </form>  
    </center>
  </body>
  <?
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
  ?>
  
</html>
<script>
function js_mostrarVisualizacao() {

  if ($F('formato') == 1) {
    $('visualizarforma').style.display = 'none';
  } else {
    $('visualizarforma').style.display = '';  
  }
}

numrel = 0;
function js_visualizarMapa() {
  
  if ($F('formato') == 1) {
  
    jan = window.open('','mapacusto'+numrel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    document.frmMapaCustos.action = "cus1_mapacustos002.php";
    document.frmMapaCustos.target = 'mapacusto' + numrel++;
    setTimeout("document.frmMapaCustos.submit()",1000);
    
  } else {
    
    var cc15_anousu = $F('cc15_anousu');
    var cc15_mesusu = $F('cc15_mesusu');
    var iNivel      = $F('visualizar');
    var sUrl        = 'cus2_mapacustoscsv002.php?cc15_anousu='+cc15_anousu+'&cc15_mesusu='+cc15_mesusu+'&nivel='+iNivel;
    js_OpenJanelaIframe('','db_custos', sUrl);    
  }
}

$('formato').observe("change",js_mostrarVisualizacao);
$('btnVisualizarPlanilha').observe("click",js_visualizarMapa);
</script>