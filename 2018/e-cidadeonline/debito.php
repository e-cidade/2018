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
include("classes/db_debcontapedido_classe.php");
include("classes/db_debcontapedidonet_classe.php");
include("classes/db_debcontapedidocgm_classe.php");
include("classes/db_debcontapedidoinscr_classe.php");
include("classes/db_debcontapedidomatric_classe.php");
include("classes/db_debcontapedidotipo_classe.php");
include("classes/db_debcontapedidotiponumpre_classe.php");
include("classes/db_debcontapedidonumpre_classe.php");
include ("dbforms/db_funcoes.php");
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
postmemory($HTTP_POST_VARS);
$cl_debcontapedido      = new cl_debcontapedido;
$cl_debcontapedidonet   = new cl_debcontapedidonet;
$cl_debcontapedidocgm   = new cl_debcontapedidocgm;
$cl_debcontapedidomatric= new cl_debcontapedidomatric;
$cl_debcontapedidoinscr = new cl_debcontapedidoinscr;
$cl_debcontapedidotipo  = new cl_debcontapedidotipo;
$cl_debcontapedidotiponumpre = new cl_debcontapedidotiponumpre;
$cl_debcontapedidonumpre = new cl_debcontapedidonumpre;
//echo "<br>numpre=$numpre";
$sqlcgm="select z01_nome,z01_numcgm,z01_cgccpf from cgm where z01_numcgm=$numcgm";
$resultcgm=db_query($sqlcgm);
db_fieldsmemory($resultcgm,0);

$ip= $HTTP_SERVER_VARS["REMOTE_ADDR"];

// pegar a instituição....................
$sql="select * from configdbpref"; 
$result = db_query($sql);
db_fieldsmemory($result,0);
$instit= $w13_instit;
//echo "<br>instituição= $instit";
 
$data=date("Y-m-d");
$hora= date("H:i"); 

// verificar se numpres ja estão em debito em conta.....
$np = split("N",$numpres);
  	$total=count($np);
  	$msg="";
  	for ($i = 0; $i < $total; $i++) {
		$np[$i] = $np[$i];
	  	if($np[$i]!=0){
			$parcela = split("P",$np[$i]);
		  	$par[$i]=$parcela[1];
		  	$num[$i]=$parcela[0];
		  	$sqlnumpre= "select * from debcontapedidotiponumpre where d67_numpre = $num[$i] and d67_numpar= $par[$i]";
		  		  	
		  	$resultnumpre=db_query($sqlnumpre);
		  	$linhasnp=pg_num_rows($resultnumpre);
		  	if ($linhasnp!=""){
		  		$imp="t";
		  		db_fieldsmemory($resultnumpre,0);
		  		//echo " parcela $d67_numpar,";
		  		$msg = "$msg ".$d67_numpar.",";
		  		
		  	}
	  	}
  	}
  	if (isset($imp)){
  		db_msgbox("Parcela(s) $msg ja estão em debito em conta");
  		$disabled = 'disabled';
  		
  	}else{
  		$disabled = '';
  	}
//$sqlnumpre= "";
//xxxxxxxxxxxxxxxxxxxxxxxxxxx


//  1- incluir na debcontapedido............

if(isset($incluir)){
	$data=date("Y-m-d");
	$sqlerro = false;
	db_inicio_transacao();   
	$cl_debcontapedido->d63_instit   = $instit;
	$cl_debcontapedido->d63_banco    = $banco;
	$cl_debcontapedido->d63_agencia  = $ag;
	$cl_debcontapedido->d63_conta    = $conta;
	$cl_debcontapedido->d63_datalanc = $data;
	$cl_debcontapedido->d63_horalanc = $hora;
	$cl_debcontapedido->d63_status   = 1;
	$cl_debcontapedido->incluir(null);
			
	if ($cl_debcontapedido->erro_status == 0) {
		$sqlerro = true;
		echo"entrei no erro do cl_debcontapedido....";
		die($cl_debcontapedido->erro_sql);
		$erro_msg = $cl_debcontapedido->erro_msg;
	}	

//  2- incluir na debcontapedidonet ............

	$cl_debcontapedidonet->d64_codigo = $cl_debcontapedido->d63_codigo;
	$cl_debcontapedidonet->d64_ip     = $ip;
	$cl_debcontapedidonet-> incluir($cl_debcontapedido->d63_codigo);

	if ($cl_debcontapedidonet->erro_status == 0) {
		$sqlerro = true;
		echo"entrei no erro do cl_debcontapedidonet....";
		die($cl_debcontapedidonet->erro_sql);
		$erro_msg = $cl_debcontapedidonet->erro_msg;
	}
		
//  3- incluir na debcontapedidocgm ............

	$cl_debcontapedidocgm->d70_codigo = $cl_debcontapedido->d63_codigo;
	$cl_debcontapedidocgm->d70_numcgm = $numcgm;
	$cl_debcontapedidocgm->incluir($cl_debcontapedido->d63_codigo);
	
	if ($cl_debcontapedidocgm->erro_status == 0) {
		$sqlerro = true;
		echo"entrei no erro do cl_debcontapedidocgm....";
		die($cl_debcontapedidocgm->erro_sql);
		$erro_msg = $cl_debcontapedidocgm->erro_msg;
	}
 
//  4- incluir na debcontapedidomatric .........

	if(isset($matric)){
		$tipomi="MATRICULA";
		$mat_ins = $matric;
		$cl_debcontapedidomatric->d68_codigo = $cl_debcontapedido->d63_codigo;
 		$cl_debcontapedidomatric->d68_matric = $matric;
 		$cl_debcontapedidomatric->incluir($cl_debcontapedido->d63_codigo);
 		if ($cl_debcontapedidomatric->erro_status == 0) {
			$sqlerro = true;
			echo"entrei no erro do cl_debcontapedidomatric....";
			die($cl_debcontapedidomatric->erro_sql);
			$erro_msg = $cl_debcontapedidomatric->erro_msg;
		}
	
	}
	
//  5- incluir na debcontapedidoinscr...........

	if(isset($inscr)){
		$tipomi="INSCRIÇÃO";
		$mat_ins = $inscr;
		$cl_debcontapedidoinscr-> d69_codigo = $cl_debcontapedido->d63_codigo;
		$cl_debcontapedidoinscr-> d69_inscr  = $inscr;
		$cl_debcontapedidoinscr->incluir($cl_debcontapedido->d63_codigo);
		if ($cl_debcontapedidoinscr->erro_status == 0) {
			$sqlerro = true;
			echo"entrei no erro do cl_debcontapedidoinscr....";
			die($cl_debcontapedidoinscr->erro_sql);
			$erro_msg = $cl_debcontapedidoinscr->erro_msg;
		}
  
	
	}
//  6- incluir na debcontapedidotipo ...........
	
	$cl_debcontapedidotipo-> d66_codigo   = $cl_debcontapedido->d63_codigo;
	$cl_debcontapedidotipo-> d66_arretipo = $tipo;
	$cl_debcontapedidotipo-> incluir(null);
	if ($cl_debcontapedidotipo->erro_status == 0) {
		$sqlerro = true;
		echo"entrei no erro do cl_debcontapedidotipo....";
		die($cl_debcontapedidotipo->erro_sql);
		$erro_msg = $cl_debcontapedidotipo->erro_msg;
	}
	

// pega os numres e numpar da string 
	$numpress=$numpres;
  	$numpres = split("N",$numpres);
  	$total=count($numpres);
  	for ($i = 0; $i < $total; $i++) {
		$numpres[$i] = $numpres[$i];
	  	if($numpres[$i]!=0){
			$numpar = split("P",$numpres[$i]);
		  	$numpar[$i]=$numpar[1];
		  	$numpre[$i]=$numpar[0];
		  	
//  7- incluir na debcontapedidotiponumpe.......
	
			$cl_debcontapedidotiponumpre-> d67_codigo = $cl_debcontapedido->d63_codigo;
			$cl_debcontapedidotiponumpre-> d67_numpre = $numpre[$i];
			$cl_debcontapedidotiponumpre-> d67_numpar = $numpar[$i];
			$cl_debcontapedidotiponumpre-> incluir(null);
			if ($cl_debcontapedidotipo->erro_status == 0) {
				$sqlerro = true;
				echo"entrei no erro do cl_debcontapedidotiponumpre....";
				die($cl_debcontapedidotiponumpre->erro_sql);
				$erro_msg = $cl_debcontapedidotiponumpre->erro_msg;
			}
		
//  8- incluir na debcontapedidonumpre .........
	/*
			$cl_debcontapedidonumpre-> d71_codigo = $cl_debcontapedido->d63_codigo;
			$cl_debcontapedidonumpre-> d71_numpre = $numpre[$i];
			$cl_debcontapedidonumpre-> incluir(null);	
		  	if ($cl_debcontapedidonumpre->erro_status == 0) {
				$sqlerro = true;
				echo"entrei no erro do cl_debcontapedidonumpre....";
				die($cl_debcontapedidonumpre->erro_sql);
				$erro_msg = $cl_debcontapedidonumpre->erro_msg;
			}*/
	  	}
    }

db_fim_transacao($sqlerro);

	if($sqlerro == false){
		$codigo=$cl_debcontapedido->d63_codigo;
		//$tipomi="inscr";
		//$mat_ins = $inscr;
		echo "<script>
			window.open('debito_relatorio.php?tipomi=$tipomi&mat_ins=$mat_ins&cgm=$z01_numcgm&numpres=$numpress&cod=$codigo&banco=$banco','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
			</script>";
	}
}


?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
</head>
<style type="text/css">
<?
db_estilosite()
?>
</style>
<script>

</script>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>">
<table width='300px' align='center' border='0' cellpadding='4' cellspacing='10'  >
<form name="form1" method="post" target="">
<br><br>
<tr class="titulo">
	<td colspan="2" align="center"> Incluir Debito em conta</td>
	<br>
</tr>
<tr class="texto">
	<td>Bancos conveniados:</td>
	<td>
	<select  name="banco" >
	    	<option value="0">selecione</option>
	    	<?
	    	$sql="select * from bancos inner join debcontaparam on d62_banco=codbco inner join configdbpref on d62_instituicao=w13_instit;";
	    	$resultbc=db_query($sql);
	    	$linhasbc=pg_num_rows($resultbc);
	    	for ($i=0; $i<=$linhasbc;$i++){
	    		db_fieldsmemory($resultbc,$i);
	    		echo "<option value ='$codbco'> $nomebco </option>";
	    	}
	    	?>
	    </select>
		<input type="hidden" name="ag" size="10" value="9999">
		<input type="hidden" name="conta" size="15" value="9999">
	</td>
</tr>


	<?
	 echo"<tr class='texto'><td colspan='2'>Nome: $z01_nome </td> </tr>";
	 
	 echo"<tr class='texto'><td colspan='2'>CPF/CNPJ: $z01_cgccpf </td></tr>";
	 echo"<tr class='texto'><td colspan='2'>CGM: $z01_numcgm </td></tr>";
	 echo"<tr class='texto'>";
	 if(isset($matric)){
		echo "<td colspan='2'>Matricula: $matric</td>";
	 }
 	 if(isset($inscr)){
		echo "<td colspan='2'>Inscrição: $inscr</td>";
	 }
	 echo"</tr>";
	 ?>
	

<tr class="texto">
		
	<td colspan="2" align="center"> 
		<input  type="submit" name="incluir" value="Incluir" class="botao" <?=$disabled?> >
		
	</td>
	
</tr>

</form>
</table>
</body>
</html>

<?
/*if(isset($imprime)){
echo "<script>
	  window.open('debito_relatorio.php?cgm=$z01_numcgm&numpres=$numpres&cod= 9999&banco=$banco','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	  </script>";
}*/
?>