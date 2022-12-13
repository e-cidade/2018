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

//21.833.694.
require("libs/db_stdlib.php");
include("classes/db_iptubase_classe.php");
include("classes/db_issbase_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_numpref_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clcgm = new cl_cgm;
$clcgm->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('j01_matric');
$clrotulo->label('q02_inscr');
$clrotulo->label('k00_numpre');
$clrotulo->label('v07_parcel');
$clrotulo->label('k50_notifica');

$clnumpref  = new cl_numpref;

// Carrega informações da sessão
$instit = db_getsession("DB_instit");
$anousu = db_getsession("DB_anousu");
$data   = date("Y-m-d");

if (isset($matricula)) {
   $location = "certidaoimovel003.php";
} else if (isset($inscricao)) {
   $location = "certidaoinscr003.php";
} else {
   $location = "certidaonome003.php";
}
    
    
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_certidao(tipo,titulo,origem, indconjunta) {
	jan = window.open('cai2_emitecnd001.php?titulo='+titulo+'&origem='+origem+'&tipo='+tipo+'&indconj='+indconjunta,'','weidth='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
	//jan=window.open('cai2_emitecnd001.php?tipo='+tipo+'&'+ender,'certreg','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	jan.moveTo(0,0);
}

</script>
<style type="text/css">
<!--
..tabcols {
  font-size:11px;
}
..tabcols1 {
  text-align: right;
  font-size:11px;
}
..btcols {
        height: 17px;
        font-size:10px;
}
..links {
        font-weight: bold;
        color: #0033FF;
        text-decoration: none;
        font-size:10px;
    cursor: hand;
}
a.links:hover {
    color:black;
        text-decoration: underline;
}
..links2 {
        font-weight: bold;
        color: #0587CD;
        text-decoration: none;
        font-size:10px;
}
a.links2:hover {
    color:black;
        text-decoration: underline;
}
..nome {
  color:black;
}
a.nome:hover {
  color:blue;
}
.botao {
background-color:#006699;
border:1px outset #000066;
color:#FFFFFF;
font-family:Arial,Helvetica,sans-serif;
font-size:10px;
font-style:normal;
font-weight:normal;
}
digitaco...zdWFyaW89 (linha 143)

-->
</style>
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="<?=$w01_corbody?>" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<br><br>
<div class="bold2" align="center">Clique na Certidão abaixo para imprimir:</div>
<br><br><br><br>
<table  align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="center" valign="top">
<center>
<?        



$resnumpref = $clnumpref->sql_record($clnumpref->sql_query_file($anousu, $instit,"k03_certissvar"));
if($resnumpref==false || $clnumpref->numrows==0){
	db_msgbox("Tabela de parâmetro (numpref) não configurada! Verifique com administrador");	
	exit;
}else{
	db_fieldsmemory($resnumpref,0);
}

$whereissvar = ($k03_certissvar == 't' ? " k00_valor <> 0 " : "");
		  
$sQueryCndPorTipo   = "select w13_regracnd, w13_tipocertidao from configdbpref where w13_instit = {$instit}";
$resQueryCndPorTipo = db_query($sQueryCndPorTipo);
if(pg_num_rows($resQueryCndPorTipo) > 0){
	db_fieldsmemory($resQueryCndPorTipo,0);
	$sTipo = $w13_regracnd;
}
		  
$nrovias	= "";
$indconj  = "";
$link     = "";
if($w13_tipocertidao == "1") {
	$nrovias 	= 1;
	$indconj  =	0;     
}elseif($w13_tipocertidao == "2"){
  $nrovias	= 1;
  $indconj	= 1;
}elseif($w13_tipocertidao == "3"){
	$nrovias	= 2;
	$indconj  = 0;  
}

for($i=0; $i<$nrovias; $i++) {
	
	if($indconj == 1) {	
		if ($whereissvar <> "") {
				$whereissvar .= " and ";
		}
		$whereissvar .= " arreinstit.k00_instit = {$instit}";
		$link	= " INDIVIDUAL";
		$individualconjunta = 2;
	}elseif($indconj==0){
		$link	= " CONJUNTA";
		$individualconjunta = 1;
	}
	$indconj = 1;
	      
	if(isset($inscricao) && !empty($inscricao)){
		$sqlins = "select * from fc_tipocertidao($inscricao,'i','$data','{$whereissvar}',$sTipo)";  
	  $resultins = db_query($sqlins);
	  $linhasins = pg_num_rows($resultins);
	  if ($linhasins > 0){
	  	db_fieldsmemory($resultins,0);
			$titulo = "INSCRICAO";
			$origem = "$inscricao";
		}  
		$opcao = 'i';         
	}
	
	if(isset($matricula) && !empty($matricula)){
		$sqlmat 	 = "select * from fc_tipocertidao($matricula,'m','$data','{$whereissvar}',$sTipo)";  
		$resultmat = db_query($sqlmat);
		$linhasmat = pg_num_rows($resultmat);
		if ($linhasmat > 0){
			db_fieldsmemory($resultmat,0);
			$titulo = "MATRICULA";
			$origem = "$matricula";
		}
		$opcao = 'm';
	}
	
	if(isset($numcgm) && !empty($numcgm)){
		$sqlcgm    = "select * from fc_tipocertidao($numcgm,'c','$data','{$whereissvar}',$sTipo)";  
		$resultcgm = db_query($sqlcgm);
		$linhascgm = pg_num_rows($resultcgm);
		if ($linhascgm > 0){
			db_fieldsmemory($resultcgm,0);
			$titulo = "CGM";
			$origem = "$numcgm";
		}
		$opcao = 'n';
	}
	
	$certidao = $fc_tipocertidao;
	
	$sql_config 		= "select * from configdbpref where w13_instit = {$instit}";
	$result_config	= db_query($sql_config);
	$iNumRowns      = pg_num_rows($result_config);
	
	db_fieldsmemory($result_config, 0);
	
	if($certidao == "positiva" ) {
		if($w13_libcertpos == 't'){
			if($iNumRowns == 0) {
			  $sMsg  = "As informações e/ou condições disponíveis são insuficientes para a emissão de certidão negativa por meio da Internet."; 
        $sMsg .= "Para maiores informações, dirija-se ao órgão competente da Administração Municipal.";
				db_msgbox($sMsg);
			}
			echo "
			<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			<tr>
			<td align='center'>
				<a href=\"javascript:js_certidao(1,'$titulo','$origem', '$individualconjunta')\">CERTIDÃO POSITIVA $link</a>
			</td>
			</tr>
			</table>\n";
		}else{	
			//db_msgbox("Certidão não liberada pelo site, procure a Prefeitura.");
			if($i==0) {
				db_msgbox("As informações e/ou condições disponíveis são insuficientes para a emissão de certidão negativa por meio da Internet. Para maiores informações, dirija-se ao órgão competente da Administração Municipal.");
			}
		}
	}elseif($certidao	== "regular"){
		echo "
		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>
		<td align='center'>
			<a href=\"javascript:js_certidao(0,'$titulo','$origem', '$individualconjunta')\">CERTIDÃO REGULAR $link</a>
		</td>
		</tr>
		</table>\n";
	}elseif($certidao == "negativa"){
		echo "
		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>
		<td align='center'>
			<a href=\"javascript:js_certidao(2,'$titulo','$origem', '$individualconjunta')\">CERTIDÃO NEGATIVA $link</a>
		</td>
		</tr>
		</table>\n";
	}
}


?>
<br/>
<input type="submit" value="Voltar" class="botao" onclick="js_voltar();">

</center>
</td>
</tr>
</table>

<script>
		function js_voltar(){
		  location.href = '<?=$location?>';
		}		
</script>
</body>
</html>