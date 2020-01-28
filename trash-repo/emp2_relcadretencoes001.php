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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
require("libs/db_utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
require("classes/empenho.php");
require("classes/db_retencaotiporecgrupo_classe.php");
require("classes/db_retencaotipocalc_classe.php");
include("dbforms/db_funcoes.php");
$oGet = db_utils::postMemory($_GET);

$oClempenho              = new empenho();
$oClretencaotipocalc     = new cl_retencaotipocalc();
$oClretencaotiporecgrupo = new cl_retencaotiporecgrupo();
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
    <style>
      .MovAtualizada {
        background-color: #c97e73;
      }
    </style>
  </head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name='form1' action='emp2_relcadretencoes002.php' method="get">
	<center>
	  <fieldset style="width: 20%">
	    <legend><b>Relatório de Retenções cadastradas</b></legend>
	      <table>
	        <tr>
	          <td>
	            <strong>Tipo de Cálculo: </strong>
	          </td>
	          <td>
	            <? 
		            $sCampos               = "e32_sequencial ,e32_descricao";
		            $sSqlRetencaoTipoCalc  = $oClretencaotipocalc->sql_query("", $sCampos, "e32_sequencial", "");
		            $rsSqlRetencaoTipoCalc = $oClretencaotipocalc->sql_record($sSqlRetencaoTipoCalc);
		            db_selectrecord("e21_retencaotipocalc", $rsSqlRetencaoTipoCalc, true, 1, "", "", "", '0', "");
	            ?>
	          </td>
	        </tr>
	        <tr>
	          <td>
	            <strong>Grupo:</strong>
	          </td>
	          <td>
	            <?
	              $sCampos                   = "e01_sequencial, e01_descricao";
	              $sSqlRetencaoTipoRecGrupo  = $oClretencaotiporecgrupo->sql_query("", $sCampos, "e01_sequencial", "");
	              $rsSqlRetencaoTipoRecGrupo = $oClretencaotiporecgrupo->sql_record($sSqlRetencaoTipoRecGrupo);
	              db_selectrecord("e21_retencaotiporecgrupo", $rsSqlRetencaoTipoRecGrupo, true, 1, "", "", "", '0', "");
	            ?>
	          </td>
	        </tr>  
	      </table>
	  </fieldset>
	  <br>
	  <input name="gerar_relatorio"  id="gerar_relatorio" type="submit" value="Gerar Relatório">
	</center>
</form>
</body>
</html>