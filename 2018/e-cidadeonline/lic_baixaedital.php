<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

include("libs/db_stdlib.php");
include("libs/db_sql.php");


	//############## teste ####################
if (isset($oid_arq)&&$oid_arq!=""){
	
   db_query ($conn, "begin");
   $loid = pg_lo_open($conn,$oid_arq, "r");
   header('Accept-Ranges: bytes');
   //header('Content-Length: 32029974'); //this is the size of the zipped file
   header('Keep-Alive: timeout=15, max=100');
   $sqloid =  "select * from liclicitaedital where l27_arquivo = $oid_arq";
   $resultoid = db_query($sqloid);
   //$result_nomearq = $cltarefaanexos->sql_record($cltarefaanexos->sql_query_file(null,"*",null,"at25_anexo = '$oid_arq'"));
   db_fieldsmemory($resultoid,0);
   //header('Content-type: Application/x-zip');
   header('Content-Disposition: attachment; filename="'.$l27_arqnome.'"');
   pg_lo_read_all ($loid);
   pg_lo_close ($loid);
   db_query ($conn, "commit"); 
   exit;
}
		
		
	//#########################3	
?>
<html>
<head>
<title>Licitações</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link rel="stylesheet" type="text/css" href="include/estilodai.css" >
<script language="JavaScript" src="scripts/db_script.js"></script>
<style type="text/css">
<?
db_estilosite()
?>
</style>
</head>
<body >
<table width="100%" border="0" align= "center" Cellspacing="10">
	<form name="form1" method="post" action="">
		<?
	

		//var_dump($HTTP_SERVER_VARS);
		//die();
		
		$sql = "select  l03_descr,  l20_numero, l20_objeto,l20_dataaber,l20_horaaber 
				from liclicita
				inner join cflicita on l03_codigo=l20_codtipocom 
				where   l20_codigo=$lic";
		$result=db_query($sql);
		db_fieldsmemory($result,0);
		
		echo"<tr><td></td></tr>
			<tr>
				<td class='texto'><b> $l03_descr N $l20_numero </b></td>
			</tr>
 			<tr><td class='texto'> Data: ".db_formatar($l20_dataaber,"d")." </td></tr>
			<tr><td class='texto'> Hora: $l20_horaaber </td></tr>
		 	<tr><td class='texto'> Objeto: $l20_objeto </td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td class='texto'> Documentos disponíveis para download:</td></tr>
";
		
		$sqledital= "select l20_codigo,l27_arquivo, l27_arqnome
					from liclicita
					inner join liclicitaedital on l20_codigo = l27_liclicita
					inner join liclicitaweb on l29_liclicita=l20_codigo
					where l20_codigo=$lic";  
					$resultedital = db_query($sqledital);
					$linhaedital = pg_num_rows($resultedital);
//echo "<br>$sqledital<br>";
    					
					
		for ($e = 0; $e < $linhaedital; $e++) {
			db_fieldsmemory($resultedital,$e);
			/*
			//######### joga arquivos para a maq
      $linkarquivo = $DOCUMENT_ROOT."/tmp/".$l27_arqnome; 				
			$sqlexport = "select lo_export($l27_arquivo, '$linkarquivo')";
			//$sqllo = "select * from pg_largeobject where loid=$l27_arquivo";
			//echo "<br>$linkarquivo<br>";
			//echo "<br>$sqllo<br>";
			$resultlo = db_query($conn, $sqlexport);
			if(pg_num_rows($resultlo)>0) {
				//$resultexport = db_query($sqlexport);
        db_query($conn, "begin;");
        //var_dump($conn);
				if (pg_lo_export($conn, $l27_arquivo, $linkarquivo)) {
				  echo "ok";
				  db_query($conn, "commit;");
				} else {
				  echo "erro<br>";
				  echo pg_last_error($conn);
				  db_query($conn, "rollback");				
				}
			}
*/
			//echo"<tr ><td><img src='imagens/seta.gif'><a class='links' href='$linkarquivo' target='_blank'> $l27_arqnome </a></td></tr>";
echo"<tr ><td><img src='imagens/seta.gif'>
<a class='links' href='lic_baixaedital.php?oid_arq=$l27_arquivo' target='_blank'> $l27_arqnome </a></td></tr>";


		}
		
		db_query("commit;");
		
		?>
	</form>
</table>
</html>