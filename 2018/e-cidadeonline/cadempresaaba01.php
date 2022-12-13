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
/*
 if(@$dbprefcgm!=""){
	$_SESSION["dbprefcgm"] = $codigoitbi;
	echo "
	<script>
	document.form1.disabilitado.value='nao';
	</script>
	";
	}

	if (@$cod!=""){
	echo"
	<script>
	document.form1.disabilitado.value='nao'
	</script>
	";
	}*/
//echo "<br>pessoa = $pessoa <br> cpf = $cpf_cnpj <br> ";
?>


<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js">
</script>
<script>
function trocacor(id){
//alert('troca');
	var dis = document.form1.disabilitado.value;
	if(dis=='nao'){ 
		document.getElementById(id).className += " activeTab";
		if('<?=$pessoa?>'=='F'){
		  var tot = 3;
		}else{
		 var tot = 4;
		}
		for(i = 1; i < tot; i++) {
			if (i!=id){
				document.getElementById(i).className = "tab";
			}
		}
		if(id==2){
		
		    cad.location.href="cadempresa_atividade.php?pessoa="+'<?=$pessoa?>'+"&cpf_cnpj="+'<?=$cpf_cnpj?>';
		 
		}
		if(id==3){
			cad.location.href="cadempresa_socios.php?pessoa="+'<?=$pessoa?>'+"&cpf_cnpj="+'<?=$cpf_cnpj?>';
		}
	}
}
</script>
<style type="text/css">
<?db_estilosite();?>

div.tabArea {
  font-size: 14px;
 // font-weight: bold;
 
}

a.tab {
  background-color:<?echo $w01_corfundomenu;?> ;
  border: 1px solid #000000;  
  border-bottom-width: 0px;
  padding: 2px 1em 2px 1em;
   -moz-border-radius: .75em .75em 0em 0em;
  border-radius-topleft: .75em;
  border-radius-topright: .75em;
  text-decoration: none;
  color: <?echo $w01_corfontemenu;?>; 
}

a.tab:hover {
  background-color:<?echo $w01_corfundomenuativo;?>; 
  color: <?echo $w01_corfontemenu;?>;           
}

a.tab.activeTab, a.tab.activeTab:hover, a.tab.activeTab:visited {
  background-color:<?echo $w01_corfundomenuativo;?>; 
  border-bottom-width: 0px;
  color:<?echo $w01_corfontemenu;?>;
}

div.tabMain {
 // background-color:#0099CC;
  border: 6px solid #000000;
  border-color: <?echo $w01_corfundomenuativo;?>; 
  -moz-border-radius: 0em .3em .3em 0em;
 
  padding: .3em;
  position: relative;
  z-index: 101;
  border-bottom-width: 0px;
  border-right-width: 0px;
  border-left-width: 0px;
}

div.tabIframeWrapper {
  width: 100%;
}

</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"
	bgcolor="<?=$w01_corbody?>">
<form name="form1" method="post" action="">
<center><br>
<input type="hidden" name="disabilitado" value="">
<table width="100%" border="0" cellspacing="0" cellpadding="0"
	style="border-bottom: 0px">
	<tr>
		<td colspan="5" align="center"
			style="font-family:arial; font-size:12px"></td>
	</tr>
	<tr>
		<td colspan="5">
		<div class="tabArea">
		 <?
		 $href = "cadempresa_dados.php?pessoa=$pessoa&cpf_cnpj=$cpf_cnpj&opcao=$opcao";
		 ?>
		  <a id="1" class="tab activeTab" href=<?=$href?> target="cad" onClick="trocacor('1')">Dados iniciais</a> 
			<a id="2" class="tab" target="cad" onClick="trocacor('2')">Atividades</a> 
			<?if($pessoa=='J'){?>
			<a id="3" class="tab" target="cad" onClick="trocacor('3')">Sócios</a>
			<?}?>
		</div>
		<div class="tabMain">
		<div class="tabIframeWrapper">
		<iframe name="cad" width="100%" height="800" align="center" marginheight="8" marginwidth="8" frameborder="0" src="<?=$href?>">
		</iframe></div>
		
		</td>
	</tr>
	</center>
	</form>

</body>
</html>