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

parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));

if(isset($retorno)) {
  $result = pg_exec("select c.v56_codigo,c.v56_certid,c.v56_proces,c.v56_execut,c.v56_endere,c.v56_movim,
                       c.v56_vara,v.v53_descr as varadescr,
                       to_char(c.v56_data,'DD') as data_dia,to_char(c.v56_data,'MM') as data_mes,to_char(c.v56_data,'YYYY') as data_ano
                     from cerjur c
					 inner join vara v
					 on v.v53_codigo = c.v56_vara
					 where c.v56_codigo = $retorno");
  db_fieldsmemory($result,0);
}

if(isset($HTTP_POST_VARS["enviar"])) {
  db_postmemory($HTTP_POST_VARS);
  $data = "$data_ano-$data_mes-$data_dia";
  $data = $data=="--"?"null":"'$data'";
  pg_exec("BEGIN");
  pg_exec("UPDATE cerjur
			SET v56_certid = '$v56_certid',
				v56_proces = '$v56_proces',
				v56_data = $data,
				v56_execut = '$v56_execut',
				v56_endere = '$v56_endere',
				v56_movim = '$v56_movim',
				v56_vara = $db_vara
				WHERE v56_codigo = $v56_codigo") or die("Erro(28) alterando cerjur");
  pg_exec("commit");
  db_redireciona();
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1 && document.form1.codigo) document.form1.codigo.focus();">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"><br><br>
	  <?
	   if(isset($HTTP_POST_VARS["procurar"]) || isset($HTTP_POST_VARS["priNoMe"]) || isset($HTTP_POST_VARS["antNoMe"]) || isset($HTTP_POST_VARS["proxNoMe"]) || isset($HTTP_POST_VARS["ultNoMe"])) {
	     db_postmemory($HTTP_POST_VARS);
         if(!empty($codigo)) {
           $result = pg_exec("select v56_codigo from cerjur where v56_codigo = $codigo");
	       if(pg_numrows($result) > 0) {
 	         db_redireciona("jur1_certidao002.php?".base64_encode("retorno=".pg_result($result,0,0)));
	         exit;
	       } else {             
             $filtro = base64_encode("v56_codigo like '".$codigo."%' order by v56_codigo");
	       }
         } else {
		   if(!empty($certidao)) {
             $filtro = base64_encode("upper(v56_certid) like upper('".$HTTP_POST_VARS["certidao"]."%') order by v56_certid");
           } else
             $filtro = base64_encode("upper(v56_proces) like upper('".$HTTP_POST_VARS["processo"]."%') order by v56_proces");
	     }
         if(isset($HTTP_POST_VARS["filtro"]))
           $filtro = $HTTP_POST_VARS["filtro"];
         $sql = "select v56_codigo as db_codigo,v56_codigo as Código,v56_certid as certidão,v56_proces as processo,to_char(v56_data,'DD-MM-YYYY') as data from cerjur where ".base64_decode($filtro);
	     echo "<center>";
         db_lov($sql,15,"jur1_certidao002.php",$filtro);
	     echo "</center>";	  
	   } else if(!isset($retorno)) {
	  ?>
      <center>
	  <form name="form1" method="post">
	  <table border="0" cellpadding="0" cellspacing="0">
	    <tr>
		  <td height="30"><strong>Código:</strong></td>
		  <td height="30"><input type="text" name="codigo"></td>
		</tr>
	    <tr>
		  <td height="30"><strong>Certidão:</strong></td>
		  <td height="30"><input type="text" name="certidao"></td>
		</tr>
	    <tr>
		  <td height="30"><strong>Processo:</strong></td>
		  <td height="30"><input type="text" name="processo"></td>
		</tr>
	    <tr>
		  <td height="30">&nbsp;</td>
		  <td height="30"><input type="submit" name="procurar" value="Procurar"></td>
		</tr>				
	  </table>
	  </form>
	  </center>
	  <?
	    } else {
          include("dbforms/db_funcoes.php");
		  $Alterar_PopularSelect = 1;
	      include("forms/db_frmjurcert.php");
		}
    ?>
	</td>
  </tr>
</table>
<?
	  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>