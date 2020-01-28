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

session_start();

include ("libs/db_stdlib.php");
db_postmemory($HTTP_POST_VARS);

if(isset($trocar)){
	echo "baseeeeeeeeeeeeeeeeee = $base";
	session_register("BASE");
	$_SESSION["BASE"] = $base;
	echo "sessão... ".$_SESSION["BASE"];
	echo"<script> parent.location.href='index.php';</script> ";
	//db_redireciona("index.php");
	//echo"<script>vari = window.open('index.php',self);</script>";
	
		
}
$sql= "select datname from pg_database where substr(datname,1,6) != 'templa'";
$result= db_query($sql);
$linha=pg_num_rows($result);


?>

<form name="form2" method="post" action="trocabase.php">
<select name="base" >
<?
for($i=0;$i<$linha;$i++){
	db_fieldsmemory($result, $i);
	echo"<option value='$datname'>$datname</option>";
}

?>
<input name="trocar" value="trocar base" type="submit">
</select>
</form>