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

include("libs/db_sql.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_tarefaanexos_classe.php");
db_postmemory($HTTP_POST_VARS);
$cltarefaanexos = new cl_tarefaanexos;
if (isset($oid_arq)&&$oid_arq!=""){
	
   pg_query ($conn, "begin");
   $loid = pg_lo_open($conn,$oid_arq, "r");
   header('Accept-Ranges: bytes');
   //header('Content-Length: 32029974'); //this is the size of the zipped file
   header('Keep-Alive: timeout=15, max=100');
   $result_nomearq = $cltarefaanexos->sql_record($cltarefaanexos->sql_query_file(null,"*","at25_data,at25_hora","at25_anexo = '$oid_arq'"));
   db_fieldsmemory($result_nomearq,0);
   //header('Content-type: Application/x-zip');
   header('Content-Disposition: attachment; filename="'.$at25_nomearq.'"');
   pg_lo_read_all ($loid);
   pg_lo_close ($loid);
   pg_query ($conn, "commit"); 
   exit;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</script> 
<style>
<?//$cor="#999999"?>
.bordas{
    border: 2px solid #cccccc;
    border-top-color: #999999;
    border-right-color: #999999;
    border-left-color: #999999;
    border-bottom-color: #999999;
    background-color: #999999;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.bordas_corp{
    border: 1px solid #cccccc;
    border-top-color: #999999;
    border-right-color: #999999;
    border-left-color: #999999;
    border-bottom-color: #999999;
    background-color: #cccccc;
}
</style> 
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center">
    <form name="form1" method="post" action="">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr >
      <td colspan="2" align = "center">
      <table>
        	<?
        	$result_anexos = $cltarefaanexos->sql_record($cltarefaanexos->sql_query(null,"*","at25_data desc,at25_hora desc","at25_tarefa = $at25_tarefa"));
        	//$xx = $cltarefaanexos->sql_query(null,"*",null,"at25_tarefa = $at25_tarefa");
        	//die($xx);
        	if($cltarefaanexos->numrows>0){
    echo "<tr >
		  	  <td align='center' COLSPAN ='5'><b>VISUALIZAR ANEXOS<br></b></td>
		  </tr>
			<tr >
		  	  <td align='center' COLSPAN ='5'><b><small>&nbsp;</small></b></td>
		  </tr>
		  <tr class='bordas'>
		      <td class='bordas' align='center'><b>Seq</b></td>
		      <td class='bordas' align='center'><b>Arquivo</b></td>
		      <td class='bordas' align='center'><b>Observao</b></td>
		      <td class='bordas' align='center'><b>Data</b></td>
		      <td class='bordas' align='center'><b>Hora</b></td>
		      <td class='bordas' align='center'><b>Usurio</b></td>
          </tr>";
  }else echo"<b>Nenhum registro encontrado...</b>"; 
	 	
		for ($w= 0; $w< $cltarefaanexos->numrows; $w++) {
			db_fieldsmemory($result_anexos,$w);	
			 echo "
           <tr>	
            <td class='bordas_corp' align='center'>".($w+1)."</td>   
	     <td class='bordas_corp' align='center'><img src='imagens/seta.gif'><a class='links' href='ate3_constarefaanexos.php?oid_arq=$at25_anexo' target='_blank'> $at25_nomearq </a></td>     
	     <td class='bordas_corp' align='center' title='$at25_obs'>".$at25_obs."&nbsp;</td>
		 <td class='bordas_corp' align='center'>".db_formatar($at25_data,'d')."</td>
		 <td class='bordas_corp' align='center'>$at25_hora</td>
		 <td class='bordas_corp' align='center'>$nome</td>
	   		</tr>
	   ";
		
		}
		?>
	  </table>
        </td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>  
      <tr>
        <td align="center"><a class='links' href='ate1_tarefaanexos001.php?vis=1&at25_tarefa=<?=$at25_tarefa?>'> Incluir um novo anexo</a></td>
        <td >&nbsp;</td>
      </tr>   

  </form>
    </table>
<?
  //db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));

$sql = "select distinct on (at80_tarefa, at80_arquivos, at80_versaocvs, at80_data, at80_hora) * from tarefa_arquivos where  at80_tarefa = $at25_tarefa ";

$result = pg_exec($sql);
if(pg_numrows($result)>0){
  echo "<table>";
  echo "<tr>";
  echo "<td > <strong>Arquivos Manipulados: </strong> </td>";
  echo "</tr>";

  echo "<tr>";
  echo "<td >Versao</td>";
  echo "<td >Data</td>";
  echo "<td >Hora</td>";
  echo "<td >Arquivos</td>";
  echo "</tr>";

  for($i=0;$i<pg_numrows($result);$i++){
    db_fieldsmemory($result,$i);
    echo "<tr>";
    echo "<td >$at80_versaocvs</td>";
    echo "<td >".db_formatar($at80_data,"d")."</td>";
    echo "<td >$at80_hora</td>";
    echo "<td >$at80_arquivos</td>";
    echo "</tr>";
  }
  echo "</table>";

}
?>
</body>
</html>
<?
// ....   incluir o anexo   ..... 
//include("ate1_tarefaanexos002.php");
?>