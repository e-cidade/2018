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
  $sql = "select j.v50_codigo,j.v50_numero,to_char(j.v50_dataen,'DD') as data_dia,to_char(j.v50_dataen,'MM') as data_mes,
    to_char(j.v50_dataen,'YYYY') as data_ano,j.v50_reu,j.v50_advoga,j.v50_valor,j.v50_movim,
    j.v50_local,l.v54_descr as localizacaodescr,j.v50_vara,v.v53_descr as varadescr,
    j.v50_situa,s.v52_descr as situacaodescr,j.v50_tipo,t.v51_descr as tipodescr
  from juridico j
  inner join tipojur t
  on t.v51_codigo = j.v50_tipo
  inner join situacao s
  on s.v52_codigo = j.v50_situa
  inner join vara v
  on v.v53_codigo = j.v50_vara
  inner join localiza l
  on l.v54_codigo = j.v50_local
  where j.v50_codigo = $retorno";
  $result = pg_exec($sql);
  db_fieldsmemory($result,0);
}

if(isset($HTTP_POST_VARS["enviar"])) {
  db_postmemory($HTTP_POST_VARS);
  $data = "$data_ano-$data_mes-$data_dia";
  $data = $data=="--"?"null":"'$data'";
  pg_exec("BEGIN");
  $result = pg_exec("UPDATE juridico
					SET v50_numero = '$v50_numero',
						v50_tipo = $db_tipo,
						v50_local = $db_localizacao,
						v50_vara = $db_vara,
						v50_reu = '$v50_reu',
						v50_advoga = '$v50_advoga',
						v50_valor = '$v50_valor',
						v50_situa = $db_situacao,
						v50_dataen = $data,
						v50_movim = '$v50_movim'
					WHERE v50_codigo = $v50_codigo") or die("Erro(43) atualizando juridico");
  $result = pg_exec("DELETE FROM autproc WHERE v55_proces = $v50_codigo") or die("Erro(44) deletando autproc");
  $aux_autor = split("#",$aux_autor);
  $tam = sizeof($aux_autor);
  for($i = 1;$i < $tam;$i++)
    $result = pg_exec("INSERT INTO autproc VALUES($v50_codigo,'".$aux_autor[$i]."',$i)") or die("Erro(48) inserindo em autproc");
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1 && document.form1.codigo) document.form1.codigo.focus(); else document.form1.v50_numero.focus();">
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
           $result = pg_exec("select v50_codigo from juridico where v50_codigo = $codigo");
	       if(pg_numrows($result) > 0) {
 	         db_redireciona("jur1_processo002.php?".base64_encode("retorno=".pg_result($result,0,0)));
	         exit;
	       } else {             
             $filtro = base64_encode("v50_codigo like '".$codigo."%' order by v50_codigo");
	       }
         } else {
		   if(!empty($numero)) {
             $filtro = base64_encode("upper(v50_numero) like upper('".$HTTP_POST_VARS["numero"]."%') order by v50_numero");
           } else
             $filtro = base64_encode("upper(v50_reu) like upper('".$HTTP_POST_VARS["reu"]."%') order by v50_reu");
	     }
         if(isset($HTTP_POST_VARS["filtro"]))
           $filtro = $HTTP_POST_VARS["filtro"];
         $sql = "select v50_codigo as db_codigo,v50_codigo as Código,v50_numero as número,v50_reu as réu,to_char(v50_dataen,'DD-MM-YYYY') as data from juridico where ".base64_decode($filtro);
	     echo "<center>";
         db_lov($sql,15,"jur1_processo002.php",$filtro);
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
		  <td height="30"><strong>Número do Processo:</strong></td>
		  <td height="30"><input type="text" name="numero"></td>
		</tr>
	    <tr>
		  <td height="30"><strong>Réu:</strong></td>
		  <td height="30"><input type="text" name="reu"></td>
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
	      include("forms/db_frmjurproc.php");
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