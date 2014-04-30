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
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

if(isset($btAtualiza)){
   $erro           = 1;
   $ipSAM          = "192.168.1.1";
   $dbSAM          = "sam30";
   $sqlRecurso     = "
   			select 
				* 
			from 
				orctiporec";
   $resultRecurso  = pg_query($sqlRecurso);   
   $connSam30      = pg_connect("host = $ipSAM dbname = $dbSAM user = postgres");
   while($linha    = pg_fetch_array($resultRecurso)){
   	$selectRec = "
			select 
				* 
			from 
				tiporec 
			where 
			o15_codigo = '".$linha["o15_codigo"]."' and 
			o15_anoexe = 2005";
//	echo $selectRec."<br>";
	$resultRec = pg_query($connSam30, $selectRec);
	if (pg_num_rows($resultRec) == 0){
	  	$finali    = substr($linha["o15_finali"], 0, 160);
		$insertRec = 
		"insert into 
			tiporec 
			(
			o15_anoexe, 
			o15_codigo, 
			o15_descr, 
			o15_finali
			)
		values
			(
			2005, 
			'".$linha["o15_codigo"]."', 
			'".$linha["o15_descr"]."', 
			'".$finali."'
			)";
		$resultRec = pg_query($connSam30, $insertRec);
		if ($resultRec){
			$erro = 0;  
		}
		else{
			$erro = 1;  
		}
	}
   } 
   $sql = 
   	"SELECT 
		o70_anousu, 
		o70_codrec, 
		o70_valor, 
		o70_reclan, 
		o70_codigo, 
		o57_fonte 
	FROM 
		orcreceita 
	inner join orcfontes on o57_codfon = o70_codfon";
   $result = pg_query($conn, $sql);
   while($linha = pg_fetch_array($result)){
     	if (substr($linha["o57_fonte"],0,7) == '4199099' ){
		$direita  = substr($linha["o57_fonte"], 1, 7);
		$esquerda = substr($linha["o57_fonte"], 10, 15);
		$fonte    = $direita.$esquerda;
	}
	else{  
		$fonte = substr($linha["o57_fonte"], 1, 12);
	}
      $selectSam30 = "
      			select 
				* 
			from 
				receita 
			where 
				o08_codest = '".$fonte."' and 
				o08_anoexe = 2005";
        //echo $selectSam30."<br>";exit;
	$resultSelect = pg_query($connSam30, $selectSam30);
	if (pg_num_rows($resultSelect) == 0){
            $insert = 
		"insert into
			receita
			(
			o08_anoexe, 
			o08_reduz,
			o08_valor,
			o08_lancad,
			o08_recurs,
			o08_codest
			)
			values
			(
			".$linha["o70_anousu"].",
			".$linha["o70_codrec"].",
			".$linha["o70_valor"].",
			'".$linha["o70_reclan"]."',
			'".$linha["o70_codigo"]."',
			'".$fonte."'
			)";
//	    echo $insert."<br>";
	    $resultSam30 = pg_query($connSam30, $insert);
     	    if ($resultSam30){
	    	$erro = 0;
	    }
	    else{
	    	$erro = 1;  
	    }
	}
   }
   $sqlFonte = "
   		select 
			o57_fonte, 
			o57_descr 
		from 
			orcfontes";
   $resultFonte = pg_query($conn, $sqlFonte); 
   while($linha = pg_fetch_array($resultFonte)){	  
     	if (substr($linha["o57_fonte"],0,7) == '4199099' ){
		$direita  = substr($linha["o57_fonte"], 1, 7);
		$esquerda = substr($linha["o57_fonte"], 10, 15);
		$fonte    = $direita.$esquerda;
	}
	else{  
		$fonte = substr($linha["o57_fonte"], 1, 12);
	}
	$descr = substr($linha["o57_descr"], 0, 40);
   	$selectFonte = "
			select 
				o19_codigo, 
				o19_descr 
			from 
				fonte 
			where 
				o19_anoexe = 2005 and 
				o19_codigo = '".$fonte."'";
	$resultF = pg_query($connSam30, $selectFonte);
	if (pg_num_rows($resultF) == 0){
		$insertFonte = "
				insert into 
					fonte
					(
					o19_anoexe, 
					o19_codigo, 
					o19_descr
					)
					values
					(
					2005, 
					'".$fonte."', 
					'".$descr."'
					)";
		$resultInsert = pg_query($connSam30, $insertFonte);
		if ($resultInsert){
			$erro = 0;
		}
		else{
			$erro = 1;  
		}
	}
   }
   if ($erro == 0){
   	db_msgbox("Receitas atualizadas com sucesso");  
   }
   else{
   	db_msgbox("Erro! Receitas já atualizadas"); 
   }
   require("libs/db_conecta.php");
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
       <form name='frm' action='age4_atuasam30.php' method='post'>
           <br>
   	   <input type='submit' name='btAtualiza' value='Atualiza Receita'>
       </form>
    </center>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>