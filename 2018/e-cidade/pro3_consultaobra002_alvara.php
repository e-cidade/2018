<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */
 

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");

require_once("dbforms/db_funcoes.php");

require_once("classes/db_obrasalvara_classe.php");

$oDaoObrasAlvara = new cl_obrasalvara;
$oGet            = db_utils::postMemory($_GET);

$oDaoParProjetos = db_utils::getDao('parprojetos');
$sSqlParametros  = $oDaoParProjetos->sql_query_pesquisaParametros( db_getsession('DB_anousu') ); 
$rsParametros    = $oDaoParProjetos->sql_record($sSqlParametros);
if ($oDaoParProjetos->erro_status != "0") {
    $oParametros = db_utils::fieldsMemory($rsParametros, 0);
    $db_opcao    = 3;
} else {
   db_msgbox(_M('tributario.projetos.pro3_consultaobra002_alvara.paremetros_nao_configurados'));
   exit;
} 

$iTipoRelatorio = $oParametros->ob21_tipocartaalvara;


/**
 * Solicita��o alvara
 */   
$rsObrasAlvara = $oDaoObrasAlvara->sql_record($oDaoObrasAlvara->sql_query(null, "*", "", "ob04_codobra = {$oGet->parametro}"));

if($oDaoObrasAlvara->numrows > 0){

  $oObrasAlvara = db_utils::fieldsMemory($rsObrasAlvara, 0, true);
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>

  <style>
    #elemento_principal {
      width: 100%;
    } 
    #elemento_principal tr td:first-child {
      width: 150px;
    }
  </style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <br />
  <br />
	<fieldset style="margin-bottom: 10px;">
	  <legend><B>Dados do Alvar�: </B></legend>
	  <table id="elemento_principal">
    <tr> 
      <td nowrap><strong>Cod. Alvar�:</strong></td>
      <td nowrap bgcolor="#FFFFFF"><?php echo $oObrasAlvara->ob04_alvara; ?></td>
    </tr>
    <tr>
      <td nowrap><strong>Data:</strong></td>
      <td nowrap bgcolor="#FFFFFF"><?php echo $oObrasAlvara->ob04_data; ?></td>
    </tr>
  </table>
</fieldset>
<center>
  <input name="emite2" id="emite2" type="button" value="Emitir Carta de Alvar�" onclick="js_emite(<?=$iTipoRelatorio; ?>);" > 
</center>
<fieldset style="margin-top: 5px;">
  <legend>Hist�rico de Renova��es</legend>
  <div id="gridHistorico"></div>
</fieldset>


<?

/**
 * Se n�o existir habite-se
 */   
} else { 
	 
	echo "<br /><br />                                              ";
	echo "<center>                                                  ";
	echo "  <strong>Nenhum alvar� liberado para est� obra.</strong> ";
	echo "</center>                                                 ";
	echo "<br /><br />                                              ";
}
?> 
<script>

  js_historicoAlvara(<?=$oGet->parametro?>);

  function js_historicoAlvara(iCodigoObra){

    var oParam         = {};
    oParam.exec        = 'getHistorico';
    oParam.iCodigoObra = iCodigoObra;

    var oAjax = new Ajax.Request( 'pro4_obrasalvara.RPC.php',
                                 {
                                   method: 'POST',
                                   parameters: 'json=' + Object.toJSON(oParam),
                                   onComplete: function (oAjax){
                                     
                                     var oRetorno = eval("("+oAjax.responseText+")");
                                     js_montaGrid(oRetorno.aHistoricos);
                                   }
                                 });
  }

  function js_montaGrid(aHistoricos){

    var oGridHistorico = new DBGrid('historico_renovacoes');
    oGridHistorico.setHeader(new Array('Data Inicial', 'Data Final'));
    oGridHistorico.setCellWidth(new Array('50%', '50%'));
    oGridHistorico.setHeight(80);
    oGridHistorico.show($('gridHistorico'));
    
    oGridHistorico.clearAll(true);


    for(var iHistorico = 0; iHistorico < aHistoricos.length; iHistorico++){

      var oHistorico = aHistoricos[iHistorico];
      var aLinha = new Array();
      aLinha[0] = js_formatar(oHistorico.ob35_datainicial, 'd');
      aLinha[1] = js_formatar(oHistorico.ob35_datafinal, 'd');

      oGridHistorico.addRow(aLinha);
    }

    oGridHistorico.renderRows();
  }

  function js_emite(iTipoRelatorio) {

    /**
     * Verifica qual relat�rio abrir, 0 pdf, 1 office
     */   
    if(iTipoRelatorio == 0) {
      sTipoArquivoRelatorio = "pro2_execobra002.php";
    } else {
      sTipoArquivoRelatorio = "pro2_execobra003.php";
    }

    jan = window.open(sTipoArquivoRelatorio+'?codigo=<?=$oGet->parametro?>',
      '',
      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
</script>
</body>
</html>