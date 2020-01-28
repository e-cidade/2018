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
include("classes/db_tabdescdepto_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$cltabdesc             = new cl_tabdesc;
$cltabdescarretipo     = new cl_tabdescarretipo;
$cltabdescdepto        = new cl_tabdescdepto;
$cltabdesc->k07_instit = db_getsession("DB_instit");
$db_opcao = 33;
$db_botao = false;
if((isset($excluir))){
	db_inicio_transacao();
	$erro = false;
	
	$codtabdesc = $cltabdesc->codsubrec;
	$cltabdescarretipo-> k78_tabdesc  = $codsubrec;
	$cltabdescarretipo->excluir(null," k78_tabdesc = $codsubrec ");
	if($cltabdescarretipo->erro_status="0"){
  	  $erro = true;
		  $msgerro = $cltabdescarretipo->erro_msg;
  } 
  $cltabdescdepto->k69_tabdesc  = $codsubrec;
	$cltabdescdepto->excluir(null, " k69_tabdesc = $codsubrec ");
	if($cltabdescdepto->erro_status="0"){
  	    $erro = true;
		    $msgerro = $cltabdescdepto->erro_msg;
	}
	
	$cltabdesc->codsubrec = $codsubrec;
  $cltabdesc->excluir($codsubrec);
  if($cltabdesc->erro_status="0"){
  	$erro = true;
		$msgerro = $cltabdesc->erro_msg;
  }
	
  db_fim_transacao($erro);
  $db_opcao = 33;
  
}else if(isset($chavepesquisa)){
   $db_opcao = 3;

	$sql = "select tabdesc.*,k78_arretipo,k00_descr ,k02_descr,i01_descr
	        from tabdesc 
					inner join tabrec          on k02_codigo  = k07_codigo
					inner join inflan          on k07_codinf  = i01_codigo
					left  join tabdescarretipo on k78_tabdesc = codsubrec 
					left  join arretipo        on k00_tipo    = k78_arretipo 
					where codsubrec = $chavepesquisa ";
	$rs = pg_query($sql);
	$linhas = pg_num_rows($rs);
	if($linhas > 0){
		db_fieldsmemory($rs,0);
	}
  $db_botao = true;
	$codsubrec = $chavepesquisa;
  if(!isset($opcao)){
  	$opcao=3;
  }
 echo "
  <script>
      function js_db_libera(){
        parent.document.formaba.depto.disabled=false;
			  parent.document.formaba.taxa.disabled=false;
		    top.corpo.iframe_depto.location.href='cai1_tabdesc_abadepto001.php?db_opcao=$opcao&coddepto=".@$coddepto."&codsubrec=".@$codsubrec."&k07_descr=$k07_descr';
			  ";
        if(isset($liberaaba)){
          echo "  parent.mo_camada('depto');";
        }
    echo"}\n
    js_db_libera();
		document.form1.codsubrec.value = $chavepesquisa;
  </script>\n
 ";

}
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
if(isset($excluir)){
  if($erro==true){
    db_msgbox($msgerro);
    
  }else{
   db_msgbox("Exclução efetuada com sucesso!");
  // echo " <script> location.href = 'cai1_tabdesc_abataxa002.php?opcao=1&liberaaba=true&chavepesquisa=$codtabdesc&k07_descr=$k07_descr';</script>"; 
   //echo " <script> location.href = 'cai1_tabdesc_abadepto001.php?liberaaba=true&chavepesquisa=$codtabdesc';</script>";
	 //db_redireciona("cai1_tabdesc_abadepto001.php?liberaaba=true&chavepesquisa=$codtabdesc");
  }
}

if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>