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
include("libs/db_sql.php");
include("classes/db_matordem_classe.php");

$clmatordem = new cl_matordem();

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
</head>
<script>
function js_mostra(){
	document.form1.submit();
}
function js_imprime(ord){

		jan = window.open('emp2_ordemcompra002.php?m51_codordem_ini='+ord+'&m51_codordem_fim='+ord,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	}
function js_alterar(orc,sol,forne,cgm){
	location.href='for_orcamlista.php?orc='+orc+'&sol='+sol+'&forne='+forne+'&cgm='+cgm;
}
</script>
<style type="text/css">
<?
db_estilosite()
?>
</style>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>">
<br>
<table width='600px' align='center' class="tab"  >
<form name="form1" method="post" target="">
<?


if($id_usuario!=""){
	if(!isset($mostra)){
		$mostra = 1;
	}

?>  <div align="center" class='titulo'>Ordems de Compra:
	<select name="mostra"  onchange="js_mostra()">
<?
    echo"
    	 		 <option value=\"1\"".($mostra==1?" selected":"").">Apenas com saldo a entregar/liquidar</option>
                 <option value=\"2\"".($mostra==2?" selected":"").">Todos</option>
        </select> </div><br>";

	$sSqlUsu = "select db_usuarios.id_usuario,senha,u.cgmlogin
    from db_usuarios
    inner join db_usuacgm u on u.id_usuario = db_usuarios.id_usuario
    where cgmlogin = ".$_SESSION["CGM"];
  $rsUsu   = db_query($sSqlUsu);
	db_fieldsmemory($rsUsu,0);
	if ($mostra == 1){

      $where = " and ((e60_vlremp - e60_vlranu - e60_vlrliq) > 0)";
	}else{

     $where = "";
	}
	$sql     = $clmatordem->sql_query_anu("","*","m51_codordem","m51_numcgm = $cgmlogin
	                                      and matordemanu.m53_codordem is null
																				$where  ");

																				$result  = db_query($sql);
	$linhas  = pg_num_rows($result);
	if($linhas>0){
		    echo"
		    <tr >
				<th align='center'> Ordem de Compra
				</th>
				<th align='center'> Data
				</th>
				<th align='center'>Empenho
				</th>
				<th align='center'> Emissao Empenho
				</th>
				<th align='center'> Nome da Instituição
				</th>
				</th>
				<th align='center'>Imprimir
				</th>";
		for ($i = 0; $i < $linhas; $i ++) {

		  db_fieldsmemory($result,$i);

			echo "<tr align='center' class='texto'>";
			echo"<td>$m51_codordem</td>";

				echo"
				</td>
				<td> ".db_formatar($m51_data, 'd')."
				</td>
				<td>".$e60_codemp."</td>
				<td> ".db_formatar($e60_emiss, 'd')."
				</td>
				<td>".$nomeinst."</td>
				<td align='left'>
					<input name='imprimir' type='button' value='Imprimir' class='botao' onclick='js_imprime($m51_codordem,$id_usuario)'>
					";

				echo"</td>";
				echo"
				</tr>";

		}
	}

}else{
	echo " não logado";
}
?>
</form>
</table>
</body>
</html>