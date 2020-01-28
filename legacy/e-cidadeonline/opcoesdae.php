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
$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                   FROM db_menupref
                   WHERE m_arquivo = 'digitadae.php'
                   ORDER BY m_descricao
                   ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
	if(!session_is_registered("DB_acesso"))
    	echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
    	echo"<script>location.href='opcoesdae.php?nova=1'</script>";
}
$db_verifica_ip = db_verifica_ip();
mens_help();
$dblink="digitadae.php";
db_logs("","",0,"Digita inscricao do Contribuinte.");
postmemory($HTTP_POST_VARS);
$clquery = new cl_query;
if(isset($nova)){
	$clquery->sql_query("issbase"," q02_numcgm ","","q02_inscr  = $inscricaow");
	$clquery->sql_record($clquery->sql);
	$num=$clquery->numrows;
	if($num!=0){
	     db_fieldsmemory($clquery->result,0);
	     $clquery->sql_query("cgm","z01_nome, z01_numcgm",""," z01_numcgm = $q02_numcgm"); // select z01_nome, z01_numcgm from cgm where z01_numcgm = q02_numcgm     
	     $clquery->sql_record($clquery->sql);  // conta as linhas no banco
	     db_fieldsmemory($clquery->result,0);  // cria variaveis (z01_nome, z01_numcgm) apartir dos campos
   	}else{
    	redireciona("digitadae.php?".base64_encode('erroscripts=Acesso a Rotina Inválido, verifique os dados digitados!'));
   	}  
}else{
	if(isset($first)){
    	$inscricaow!=""?"":$inscricaow = 0 ;
    	if ( !empty($cgc) ){
    		$cgccpf = $cgc;
    	}else{
	      	if (!empty($cpf) ){
	        	$cgccpf = $cpf;
	      	}else{
	        	$cgccpf = "";
	      	}
    }
    $cgccpf = str_replace(".","",$cgccpf);
    $cgccpf = str_replace("/","",$cgccpf);
    $cgccpf = str_replace("-","",$cgccpf);  
    $sql_exe = "select * from issbase inner join cgm on q02_numcgm = z01_numcgm where q02_inscr = $inscricaow";
    
    if($db_verifica_ip == "0" ) {
    	$sql_exe = "select * from issbase inner join cgm on q02_numcgm = z01_numcgm where z01_cgccpf = '$cgccpf' and q02_inscr  = '$inscricaow'";
   
    }
    $result = db_query($sql_exe);
 
    if(pg_numrows($result) != 0){
    	db_fieldsmemory($result,0);
    }else{
      	redireciona("digitadae.php?".base64_encode('erroscripts=Acesso a Rotina Inválido, verifique os dados digitados!!!!!!!!!!!!'));
    }  
    if(!isset($DB_LOGADO)  && $m_publico !='t'){
      	$sql = "select fc_permissaodbpref(".db_getsession("DB_login").",2,$inscricaow)";
      	$result = db_query($sql);
      	if(pg_numrows($result)==0){
        	db_redireciona("digitadae.php?".base64_encode('erroscripts=Acesso não Permitido. Contate a Prefeitura.'));
        	exit;
      	}
      	$result = pg_result($result,0,0);
      		if($result=="0"){
        		db_redireciona("digitadae.php?".base64_encode('erroscripts=Acesso não Permitido. Contate a Prefeitura.'));
        		exit;
      		}
    } 
  }  
}
$result = db_query("select * from db_dae where w04_inscr = $inscricaow and w04_ano=$ano");
//$ano = date("Y");
if(pg_numrows($result) == 0){// se não  tiver dai para este ano ele insere	

	$result = db_query("select nextval('seq_db_dae')");   
  	$codigo = pg_result($result,0,0);
  	db_query("insert into db_dae values($codigo,$inscricaow,'f','$ano')");
  	$result = db_query("select * from db_dae where w04_inscr = $inscricaow and w04_ano=$ano");
  	db_fieldsmemory($result,0);// transforma o campo em variavel...todos?
  	if($w04_enviado == 't'){ // se enviado = t , ja foi enviada ...nunca vai entrar aki
  
/*echo "
		<script>
			var confirma = confirm('DAI já enviada, deseja reemitir o relatório?');
            if(confirma == true){
            	window.open('daerelatorio.php?codigo=$w04_codigo','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
            }
        </script>
";
    db_redireciona("digitadae.php");
    exit;*/
	}else{  // se DAI não foi enviada
    	$codigo = $w04_codigo;  // codigo da DAI
    	
  	}  
}else{//se tiver dai para este ano

  	db_fieldsmemory($result,0);// transforma campos da tabela db_dae
  	if($w04_enviado == 't'){  
echo "
		<script>var confirma = confirm('DAI já enviada, deseja reemitir o relatório?');
        if(confirma == true){
            window.open('daerelatorio.php?codigo=$w04_codigo','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
        }
        </script>  
";  
    db_redireciona("digitadae.php");
    exit;
  	}else{
    	$codigo = $w04_codigo;  
  	}  
}

//$result2 = db_query("select * from confsite");
//db_fieldsmemory($result2,0);


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
function trocacor(id){
	document.getElementById(id).className += " activeTab";
	for(i = 1; i < 8; i++) {
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
<?mens_div();?>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" <? mens_OnHelp() ?>>
<form name="form1" method="post" action="opcoesissqn.php">
<center>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-bottom: 0px">
	<tr>
    	<td colspan="5" align="center" style="font-family:arial; font-size:12px">
      		<b><?=$q02_inscr." - ".$z01_nome?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>Competência:<?=$ano?> <br><br>
    	</td>
  	</tr>
   	<tr>
  		<td colspan="5">
    		<div class="tabArea">
       	      	<a id="1" class="tab activeTab" href= "enderecodae.php?<?=base64_encode('inscricaow='.$inscricaow.'&codigo='.$codigo.'&primeira=1')?>" target="dae"  onClick="trocacor('1')" >Endereço</a>
			    <?
			    $sqlsocio="select * from configdbpref";
			    $resultsocio = db_query($sqlsocio);
			    db_fieldsmemory($resultsocio,0);
			    if($w13_libsociosdai=='t'){
			      ?>
			      <a id="2" class="tab" href= "sociosdae.php?<?=base64_encode('inscricaow='.$inscricaow.'&codigo='.$codigo.'&primeira=1')?>" target="dae"  onClick="trocacor('2')" >Socios</a>
			      <?
			    }
			    ?>
			    <a id="3" class="tab" href="valoresdae.php?<?=base64_encode('inscricaow='.$inscricaow.'&codigo='.$codigo.'&primeira=1')?>" target="dae" onClick="trocacor('3')" >Valores</a>
			    <a id="4" class="tab" href="daitomador.php?<?=base64_encode('inscricaow='.$inscricaow.'&codigo='.$codigo.'&primeira=1')?>" target="dae" onClick="trocacor('4')">Retenção como tomador</a>
			    <a id="5" class="tab" href="dairetido.php?<?=base64_encode('inscricaow='.$inscricaow.'&codigo='.$codigo.'&primeira=1')?>" target="dae" onClick="trocacor('5')">Retenção como prestador</a>
			    <a id="6" class="tab" href="enviadae.php?<?=base64_encode('inscricaow='.$inscricaow.'&codigo='.$codigo.'&primeira=1')?>" target="dae" onClick="trocacor('6')"  >Envia DAI</a>
			    <a id="7" class="tab" href="ajudadae.php?<?=base64_encode('inscricaow='.$inscricaow.'&codigo='.$codigo.'&primeira=1')?>" target="dae" onClick="trocacor('7')"  >Ajuda</a>
      	    </div>
     		<div class="tabMain">
    			<div class="tabIframeWrapper">
  					<iframe name="dae" width="100%" height="800" align="center"  marginheight="8" marginwidth="8" frameborder="0"  src="enderecodae.php?<?=base64_encode('inscricaow='.$inscricaow.'&codigo='.$codigo.'&primeira=1')?>"  >
        			</iframe>
 				</div>
  		</td>
  	</tr>
</center>
</form>
</body>
</html>