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
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
postmemory($HTTP_POST_VARS);

if(isset($pesq)){
	
	if ($cod!=""){
		$sql="select  y08_codigo, y08_nota,y08_dtlanc,y08_quantlib,y08_notain,y08_notafi,y08_inscr,z01_nome,q09_descr
		from aidof 
		inner join issbase on  y08_inscr= q02_inscr
		inner join cgm on q02_numcgm=z01_numcgm
		inner join notasiss on q09_codigo= y08_nota
		where y08_numcgm=$cgm and y08_codigo=$cod and (y08_quantlib is not null or y08_quantlib!=0) and (y08_cancel ='f' or y08_cancel is null ) order by z01_nome"; 
		
	}elseif($inscr!=""){
		$sql="select  y08_codigo, y08_nota,y08_dtlanc,y08_quantlib,y08_notain,y08_notafi,y08_inscr,z01_nome,q09_descr
		from aidof 
		inner join issbase on  y08_inscr= q02_inscr
		inner join cgm on q02_numcgm=z01_numcgm
		inner join notasiss on q09_codigo= y08_nota
		where y08_numcgm=$cgm and y08_inscr=$inscr and (y08_quantlib is not null or y08_quantlib!=0) and (y08_cancel ='f' or y08_cancel is null ) order by z01_nome"; 
	}else{
		$sql="select  y08_codigo, y08_nota,y08_dtlanc,y08_quantlib,y08_notain,y08_notafi,y08_inscr,z01_nome,q09_descr
		from aidof 
		inner join issbase on  y08_inscr= q02_inscr
		inner join cgm on q02_numcgm=z01_numcgm
		inner join notasiss on q09_codigo= y08_nota
		where y08_numcgm=$cgm and (y08_quantlib is not null or y08_quantlib!=0) and (y08_cancel ='f' or y08_cancel is null ) order by z01_nome"; 
	}
	
	
}else{

$sql="select  y08_codigo, y08_nota,y08_dtlanc,y08_quantlib,y08_notain,y08_notafi,y08_inscr,z01_nome,q09_descr
	from aidof 
	inner join issbase on  y08_inscr= q02_inscr
	inner join cgm on q02_numcgm=z01_numcgm
	inner join notasiss on q09_codigo= y08_nota
	where y08_numcgm=$cgm and (y08_quantlib is not null or y08_quantlib!=0) and (y08_cancel ='f' or y08_cancel is null ) order by z01_nome"; 

}
//die($sql);

//$sql = "select * from aidof where y08_numcgm=$cgm and (y08_quantlib is not null or y08_quantlib!=0) and y08_cancel !=true";
$result=db_query($sql);
$linha=pg_num_rows($result);
if($linha==0){
	msgbox("Nenhum registro encontrado");
}

if(isset($verifica)){

	$sqlver= "select * from aidofautenticidade  where y01_codautenticidade ='$autent'";
	//die($sqlver);
	$resultver=db_query($sqlver);
	$linhasver = pg_num_rows($resultver);
	if($linhasver>0){
		msgbox("Codigo de autenticação correto");
	}else{
		msgbox("Codigo de autenticação incorreto");
	}
}
?>

<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<style type="text/css">
<?
db_estilosite();
?>

</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
	<form name="form1" method="post" action="" >
	<br><br>
	<div align="center" class="titulo">Verificar autenticidade<br></div><br>
	<div align="center" class="texto">Digite o número que se encontra acima do código de barras da certidão impressa</div><br>
	<div align="center" class="texto" >
		Código de autenticidade<input name="autent" type="text" size="40" value=""> <input name="verifica" type="submit" value="Verificar" class="botao">
	</div><br><br>
	<div><hr></div>
	<br>
	<div align="center" class="titulo">Consulta Aidofs liberadas<br></div><br>
	<div align="center" class="texto" >
		Código<input name="cod" type="text" value""> <b> OU </b>
		Inscrição <input name="inscr" type="text" value"">
		<input name="pesq" type="submit" value="Pesquisar" class="botao">
	</div>
	<br>	
	<table class="tab" align="center" width="80%">
    <tr>
    	<th align="center">Código</th>
    	<th align="center">Tipo de nota</th>
    	<th align="center">Data</th>
    	<th align="center">Quant. liberada</th>
    	<th align="center">Numeração</th>
    	<th align="center">Inscrição</th>
    	<th align="center">Cliente</th>
    </tr>
  
  
  
<?
for($i = 0;$i < $linha; $i++){
	db_fieldsmemory($result,$i);
	echo"
	<tr>
    	<td align='center'>$y08_codigo</td>
    	<td align='center'>$q09_descr</td>
    	<td align='center'>".db_formatar($y08_dtlanc,'d')."</td>
    	<td align='center'>$y08_quantlib</td>
    	<td align='center'>$y08_notain a $y08_notafi</td>
    	<td align='center'>$y08_inscr</td>
    	<td align='left'>$z01_nome</td>
    	
    </tr>
   ";
}

?>
</table>
</form>
</body>
</html>