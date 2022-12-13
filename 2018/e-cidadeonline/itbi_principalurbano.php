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
$_SESSION["itbitipo"] = $tipo;

$cod=@$_SESSION["itbi"];
include("libs/db_stdlib.php");
include("libs/db_sql.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
postmemory($HTTP_POST_VARS);

if(@$codigoitbi!=""){ 
	$_SESSION["itbi"] = $codigoitbi;
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
}
 
?>
<link rel="stylesheet" type="text/css" href="include/estilodai.css" >
<link href="/common/default.css" rel="stylesheet" type="text/css" />
<style type="text/css">
</style>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js">
</script>
<script>
function trocacor(id, tipo){
	if(tipo=='rural'){
		var tip = 8;
	}else{
		var tip = 7;
	}
	document.getElementById(id).className += " activeTab";
	for(i = 1; i < tip; i++) {
		if (i!=id){
			document.getElementById(i).className = "tab";
		}
	}
	var dis = document.form1.disabilitado.value;
	
	if(dis=='nao'){ 
		if(id==2){
			itbi.location.href='itbi_dadosimovel.php';
		}
		if(id==3){
			itbi.location.href='itbi_transmitente.php';
		}
		if(id==4){
			itbi.location.href='itbi_comprador.php';
		}
		if(id==5){
			itbi.location.href='itbi_contrucao.php';
		}
		if(id==6){
			itbi.location.href='itbi_envia.php';
		}
		if(tipo=='rural'){
			if(id==7){
				itbi.location.href='itbi_proprietario.php';
			}
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
<?mens_div(); ?>

</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" >
<form name="form1" method="post" action="opcoesissqn.php">

<input type="hidden" name="disabilitado" value="" >

  
<center>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-bottom: 0px">
	<tr>
    	<td colspan="5" align="center" class="titulo">
    	Solicitação de ITBI 
      	</td>
  	</tr>
  	<tr>
    	<td colspan="5" align="center" >
    	&nbsp;
      	</td>
  	</tr>
   	<tr>
  		<td colspan="5">
  			<?
  			if($tipo=="urbano"){
  			?>
  				<div class="tabArea">
	       	      	<a id="1" class="tab activeTab" href= "itbi_itbiurbano.php?mat=<?=$mat?>"  target="itbi"  onClick="trocacor('1','<?=$tipo?>')" >ITBI</a>
				    <a id="2" class="tab"  target="itbi"  onClick="trocacor('2','<?=$tipo?>')" >Dados do imóvel</a>
				    <a id="3" class="tab"  target="itbi"  onClick="trocacor('3','<?=$tipo?>')" >Transmitente</a>
				    <a id="4" class="tab"  target="itbi"  onClick="trocacor('4','<?=$tipo?>')" >Comprador</a>
				    <a id="5" class="tab"  target="itbi"  onClick="trocacor('5','<?=$tipo?>')" >Construção</a>
				    <a id="6" class="tab"  target="itbi"  onClick="trocacor('6','<?=$tipo?>')" >Envia ITBI</a>
			    </div>
  			<?
  			}
  			if ($tipo=="rural"){
  			?>
    		<div class="tabArea">
       	      	<a id="1" class="tab activeTab" href= "itbi_itbirural.php?cnpj=<?=$cnpj?>&sol=<?=$sol?>"  target="itbi"  onClick="trocacor('1','<?=$tipo?>')" >ITBI</a>
			    <a id="2" class="tab"  target="itbi"  onClick="trocacor('2','<?=$tipo?>')" >Dados do imóvel</a>
			    <a id="3" class="tab"  target="itbi"  onClick="trocacor('3','<?=$tipo?>')" >Transmitente</a>
			    <a id="4" class="tab"  target="itbi"  onClick="trocacor('4','<?=$tipo?>')" >Comprador</a>
			    <a id="5" class="tab"  target="itbi"  onClick="trocacor('5','<?=$tipo?>')" >Construção</a>
			    <a id="7" class="tab"  target="itbi"  onClick="trocacor('7','<?=$tipo?>')" >Proprietário</a>
			    <a id="6" class="tab"  target="itbi"  onClick="trocacor('6','<?=$tipo?>')" >Envia ITBI</a>
			 <?
			 }
			 ?>
      	    </div>
     		<div class="tabMain">
    			<div class="tabIframeWrapper">
    				
        			<?
        			if($tipo=="rural"){
        				?>
        				<iframe name="itbi" width="100%" height="800" align="center"  marginheight="8" marginwidth="8" frameborder="0"  src="itbi_itbirural.php?cnpj=<?=$cnpj?>&sol=<?=$sol?>"  >
        				</iframe>
        				<?
        			}else{
        				?>
        				<iframe name="itbi" width="100%" height="800" align="center"  marginheight="8" marginwidth="8" frameborder="0"  src="itbi_itbiurbano.php?mat=<?=$mat?>"  >
        				</iframe>
        				<?
        			}
        			?>
 				</div>
 			</div>	
  		</td>
  	</tr>
</center>
</form>
</body>
</html>

<?
  // 
  ?>