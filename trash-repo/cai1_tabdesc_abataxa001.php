<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_tabdesc_classe.php");
include("classes/db_tabdescarretipo_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$cltabdesc             = new cl_tabdesc;
$cltabdescarretipo     = new cl_tabdescarretipo;
$cltabdesc->k07_instit = db_getsession("DB_instit");
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){

$k07_valorf=str_replace(",",".",$k07_valorf);
$k07_valorv=str_replace(",",".",$k07_valorv); 
$k07_quamin=str_replace(",",".",$k07_quamin);
$k07_percde=str_replace(",",".",$k07_percde);

  db_inicio_transacao();
	$sqlerro = false;
  $cltabdesc->incluir(null);
  if($cltabdesc->erro_status == "0"){
  	$sqlerro = true;
		$msgerro = $cltabdesc->erro_msg;
		//echo "1 erro $msgerro"; exit;
  }
	if($sqlerro==false){
		$codtabdesc = $cltabdesc->codsubrec;
		if($k78_arretipo != ""){
			$cltabdescarretipo-> k78_tabdesc  = $codtabdesc;
			$cltabdescarretipo-> k78_arretipo = $k78_arretipo;
			$cltabdescarretipo->incluir(null);
			if($cltabdescarretipo->erro_status=="0"){
	  	  $sqlerro = true;
			  $msgerro = $cltabdescarretipo->erro_msg;
				//echo "2 erro $msgerro"; exit;
	    } 
		}
	}
  db_fim_transacao($sqlerro);
}


/*
if(isset($codsubrec) and $codsubrec!=""){

	$sql = "select tabdesc.*,k78_arretipo,k00_descr 
	        from tabdesc 
					left join tabdescarretipo on k78_tabdesc=codsubrec 
					left join arretipo on k00_tipo = k78_arretipo 
					where codsubrec = $codsubrec ";
	$rs = pg_query($sql);
	$linhas = pg_num_rows($rs);
	if($linhas > 0){
		db_fieldsmemory($rs,0);
		
	}
	
}
*/
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmtabdesc.php");
	?>
    </center>
	</td>
  </tr>
</table>

</body>
</html>
<?

if(isset($incluir)){
  if($sqlerro==true){
    db_msgbox($msgerro);
    
  }else{
   db_msgbox("Inclusão efetuada com sucesso!");
   echo " <script> location.href = 'cai1_tabdesc_abataxa002.php?opcao=1&liberaaba=true&chavepesquisa=$codtabdesc&k07_descr=$k07_descr';</script>"; 
   //echo " <script> location.href = 'cai1_tabdesc_abadepto001.php?liberaaba=true&chavepesquisa=$codtabdesc';</script>";
	 //db_redireciona("cai1_tabdesc_abadepto001.php?liberaaba=true&chavepesquisa=$codtabdesc");
  }
}
?>