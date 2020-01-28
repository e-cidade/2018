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
include("classes/db_vacinasaplicadas_classe.php");
$cl_vacinasaplicadas = new cl_vacinasaplicadas;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?
if(isset($Processar)){
 $result = $cl_vacinasaplicadas -> sql_record($cl_vacinasaplicadas->sql_query("","","","*",$ordem,"sd08_d_data BETWEEN '$data1' AND '$data2'"));
 if($cl_vacinasaplicadas->numrows<>0){
 ?>
 <table width="100%" border="1" cellpadding="1" cellspacing="0">
 <tr bgcolor="#cccccc">
  <td><b>Unidade</b></td>
  <td><b>CGM</b></td>
  <td><b>Vacina</b></td>
  <td><b>Data</b></td>
 </tr>
 <?
 //for
 for($i=0;$i<$cl_vacinasaplicadas->numrows;$i++){
 db_fieldsmemory($result,$i);
 ?>
 <tr>
  <td><?=$sd02_i_codigo?> - <?=$sd02_c_nome?></td>
  <td><?=$z01_numcgm?> - <?=$z01_nome?></td>
  <td><?=$sd08_c_vacina?> - <?=$sd07_c_nome?></td>
  <td><?=substr($sd08_d_data,8,2)."/".substr($sd08_d_data,5,2)."/".substr($sd08_d_data,0,4)?></td>
 </tr>
 <?
 }
 }else{
  echo "<center><br><br><font color='red'>Nenhum registro encontrado!</font></center>";
 }
}else{
echo "<center><br><br>Informe o período e clique em <b>Processar</b>...</center>";
}
?>
</table>
</body>
</html>