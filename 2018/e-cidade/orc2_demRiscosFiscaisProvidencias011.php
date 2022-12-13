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
 * @version $Revision: 1.4 $
 */
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
db_postmemory($HTTP_POST_VARS);

$anousu = db_getsession("DB_anousu");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

variavel = 1;
	function js_emite(anousu){
	  var doc = document.form1;
	  var sQuery = '&sModelo='+doc.modelo.value;
    jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    document.form1.target = 'safo' + variavel++;
    executar = "orc2_demRiscosFiscaisProvidencias002";
//    if (anousu > 2007){
//      executar += "_"+anousu; 
//    }
    executar += ".php";
    document.form1.action = executar+"?mes" +sQuery 
    setTimeout("document.form1.submit()",1000);
    return true;
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table  align="center" width="450">
    <form name="form1" method="post" action="" >
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
			<tr>
    		<td colspan=2 class='table_header'>
      		 <center><b>Demonstrativo dos Riscos Fiscais e Providências</b></center>
    		</td>
  		</tr>
       <tr>
        <td align="left" colspan="2">
          <b>Modelo :</b> <? 
                    $sList = array("ldo"=>"LDO",
                                   "loa"=>"LOA"
                                  );
                    db_select("modelo",$sList,"",1); ?>
        </td>
      </tr>   		
      <tr>
        <td align="center">
          <input  name="emite" id="emite" type="button" value="Imprimir" onclick="js_emite(<?=$anousu?>);">
           <?
          $o116_periodo = 1;
          db_input('o116_periodo',40,'',true,'hidden',3,'');
         ?>
        </td>
      </tr>
  	</form>
  </table>
</body>
</html>