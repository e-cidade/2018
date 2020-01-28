<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/db_liborcamento.php");
include("libs/db_libcontabilidade.php");

$iAnoUsu            = db_getsession("DB_anousu");
$oGet               = db_utils::postMemory($_GET);
$codigoRelatorio    = $oGet->c83_codrel;

$oRelatorio = new relatorioContabil($codigoRelatorio, false);
$clrotulo   = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
$sTitulo    = $oRelatorio->getDescricao();
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">

<script>
  
	function js_emite(){

    if (document.form1.o116_periodo.value == 0) {

      alert("Selecione um per�odo!");
      return;
    }

    query = "periodo="+document.form1.o116_periodo.value;

    jan = window.open('con2_anexo13rpps_002_2014.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table  align="center" width="450">
    <form name="form1" method="post" action="" >
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
			<tr>
        <td colspan="3" class='table_header'><?php echo $sTitulo; ?></td>
    		</td>
  		</tr>
      <tr>
        <td align="center" colspan="3">
	  			<fieldset>
						<legend>
							 <b>Selecionar Per�odo</b>
						</legend>
						<table>
          		<tr>
            		<td align="right" >
										<strong>Per�odo :</strong> </td>
            		<td align="left" >
                  <?php
                  $aPeriodos         = $oRelatorio->getPeriodos();
                  $aListaPeriodos    = array();
                  $aListaPeriodos[0] = "Selecione";

                  foreach ($aPeriodos as $oPeriodo) {
                    $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
                  }

                  db_select("o116_periodo", $aListaPeriodos, true, 1);
             			?>
            		</td>
           		</tr>
	  			  </table>
	  			</fieldset>
				</td>
      </tr>
      <tr>
        <td align="center">
          <input  name="emite" id="emite" type="button" value="Imprimir" onclick="js_emite();">
        </td>
      </tr>
  </form>
    </table>
</body>
</html>