<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include_once("classes/db_conplanoreduz_classe.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clempelemento = new cl_empelemento;
$clconplanoreduz = new cl_conplanoreduz;
$clrotulo = new rotulocampo;

$clrotulo->label("o56_descr");
$clrotulo->label("o56_elemento");
$clrotulo->label("o56_codele");
$clempelemento->rotulo->label();
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>
<center>
 <table border='0' cellspacing="0" cellpadding="0" width="95%" style='border:2px inset white'>   
 <?
      $result = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp,null,"*","e64_codele"));
      $numrows = $clempelemento->numrows;
      if($numrows>0){
	echo "
	    <tr>
        <th class='table_header' colspan='4'>Movimentação</th>
        <th class='table_header' colspan='4'>Saldo a Pagar</th>
      </tr>
      <tr>
	      <th class='table_header' align='center'><b>$RLe64_vlremp</b></th>
	      <th class='table_header' align='center'><b>$RLe64_vlranu</b></th>
	      <th class='table_header' align='center'><b>$RLe64_vlrliq</b></th>
	      <th class='table_header' align='center'>$RLe64_vlrpag</th>
	      <th class='table_header' align='center'>Liquidado</b></th>
	      <th class='table_header' align='center'><b>A Liquidar</b></th>
	      <th class='table_header' align='center'><b>Geral</b></th>
	      <th class='table_header' align='center' style='width:18px'>&nbsp;</th>
	    </tr>
      <tbody style='height:150px;overflow:scroll;overflow-x:hidden;background-color:white'>
	";
         for($i=0; $i<$numrows; $i++){
	    db_fieldsmemory($result,$i);
	    
	    echo "<tr style='height:1em'>
	      <td class='linhagrid' style='text-align:right'>".db_formatar($e64_vlremp,'f',' ',10)."</td>
        <td class='linhagrid' style='text-align:right'>".db_formatar($e64_vlranu,'f',' ',10)."</td>
	      <td class='linhagrid' style='text-align:right'>".db_formatar($e64_vlrliq,'f',' ',10)."</td>
        <td class='linhagrid' style='text-align:right'>".db_formatar($e64_vlrpag,'f',' ',10)."</td>
	      <td class='linhagrid' style='text-align:right'>".db_formatar(($e64_vlrliq-$e64_vlrpag),'f',' ',10)."</td>
	      <td class='linhagrid' style='text-align:right'>".db_formatar(($e64_vlremp-$e64_vlranu-$e64_vlrliq),'f',' ',10)."</td>
	      <td class='linhagrid' style='text-align:right'>".db_formatar(($e64_vlremp-$e64_vlranu-$e64_vlrpag),'f',' ',10)."</td>
	   </tr>
	   "; 
         }
         echo "<tr style='height:auto;'><td colspan='8'>&nbsp;</tr>";
         echo "</tbody>";
      }	 
 ?>
 </table>
 </center>
</body>
</html>