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
include("classes/db_empelemento_classe.php");
include("classes/db_pagordemele_classe.php");
include("classes/db_pagordem_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clpagordemele = new cl_pagordemele;
$clpagordem = new cl_pagordem;
$clrotulo = new rotulocampo;
$clrotulo->label("o56_descr");
$clrotulo->label("o56_elemento");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="../scripts/scripts.js"></script>
<link href="../estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload='js_calcular();' >
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
  <tr> 
    <td  align="left" valign="top" bgcolor="#CCCCCC"> 
    <form name='form1'>
    <input name='verificador' type='hidden' value='ok'>
    <center>
 <table border='1' cellspacing="0" cellpadding="0">   
 <?
 if(isset($e50_codord) && $e50_codord!= ""){
      $result = $clpagordemele->sql_record($clpagordemele->sql_query($e50_codord));
      $numrows = $clpagordemele->numrows;
      if($numrows>0){
	echo "
	    <tr>
	      <td align='center'><b><small>$RLo56_elemento</small></b></td>
	      <td align='center'><b><small>$RLo56_descr</small></b></td>
              <td align='center'><b><small>Valor da ordem</small></b></td>
              <td align='center'><b><small>Valor anulado ordem</small></b></td>
              <td align='center'><b><small>Valor pago ordem</small></b></td>";
   echo "  </tr>";
         for($i=0; $i<$numrows; $i++){
	    db_fieldsmemory($result,$i);
                 
	      $e="e53_vlrpag_$o56_codele";
	      $$e = number_format($e53_vlrpag,"2",".","");		       
	      $e="e53_vlranu_$o56_codele";
	      $$e = number_format($e53_vlranu,"2",".","");		       
	      $e="e53_valor_$o56_codele";
	      $$e = number_format($e53_valor,"2",".","");		       

	    echo "<tr>	    
   	            <td align='center'><small>$o56_elemento </small></td>
	            <td align='center'><small>$o56_descr </small></td>

                    <td align='center'><small>";db_input("e53_valor_$o56_codele",8,0,true,'text',3);echo "</small></td>
                    <td align='center'><small>";db_input("e53_vlranu_$o56_codele",8,0,true,'text',3);echo "</small></td>
                    <td align='center'><small>";db_input("e53_vlrpag_$o56_codele",8,0,true,'text',3);echo "</small></td>
	          </tr> 
	         ";
		    
         }
      }	 
  }    
 ?>
 </table>
    </form> 
    </center>
    </td>
  </tr>
</table>
</body>
</html>