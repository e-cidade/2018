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
?>
<html>
<head>
<title>Licitações</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
function trocacor(id){
	document.getElementById(id).className += " activeTab";
	for(i = 0; i < 8; i++) {
		if (i!=id){
			document.getElementById(i).className = "tab";
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
</head>
<body >
<?
$sql = "select * from cflicita";
$result= db_query($sql);                   
$linhas = pg_num_rows($result);
//echo "linhas = $linhas";

?>

<form name="form1" method="post" action="">
<center>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-bottom: 0px">
	
   	<tr>
  		<td colspan="<?=$linhas?>">
    		<div class="tabArea">
    		    <?for ($i = 0; $i < $linhas; $i++){
    		      	db_fieldsmemory($result,$i);
       	      		echo "<a id='$i' class='tab activeTab' href= 'licitacao.php?tipo=$l03_codigo' target='lic' onClick='trocacor($i)' >$l03_descr</a>";
    		    }
      	        ?>
      	    </div>
     		<div class="tabMain">
    			<div class="tabIframeWrapper">
  					<iframe name="lic" width="100%" height="800" align="center"  marginheight="8" marginwidth="8" frameborder="0"  src="licitacaoaberto.php"  >
        			</iframe>
 				</div>
  		</td>
  	</tr>
</center>
</form>
	
	

</html>