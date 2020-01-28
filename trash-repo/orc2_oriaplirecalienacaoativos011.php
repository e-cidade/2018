<?
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

$clrotulo = new rotulocampo();
$clrotulo->label("o05_ppalei");
$clrotulo->label("o0i_descricao");


?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css"            rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table  align="center" width="450">
    <form name="form1" method="post" action="" >
			<tr>
    		<td colspan=2  class='table_header'>
      		 DEMONSTRATIVO V - ORIGEM E APLICAÇÃO DOS RECURSOS OBTIDOS COM A ALIENAÇÃO DE ATIVOS
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
        <td align="center" style='padding-top:20px;' >
          <input  name="emite" id="emite" type="button" value="Imprimir" onclick="js_emite();">
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
<script>

  function js_emite(){   
    
    var doc = document.form1;
    var sQuery = '?sModelo='+doc.modelo.value;
    var jan = window.open('orc2_oriaplirecalienacaoativos002.php'+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
        jan.moveTo(0,0);
  }
  
</script>