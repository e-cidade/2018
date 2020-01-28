<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
session_unregister("DB_instit");
session_unregister("DB_Area");

/*
if(session_is_registered("DB_porta")) {
  db_query("update db_usuariosonline 
           set uol_arquivo = '', uol_modulo = 'Selecionando Instituição' 
		   where uol_id = ".db_getsession("DB_id_usuario")."
		   and uol_ip = '".$HTTP_SERVER_VARS['REMOTE_ADDR']."' 
		   and uol_hora = ".db_getsession("DB_hora")) or die("Erro(26) atualizando db_usuariosonline");
}
*/
//parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

//a primeira vez que entra, pega a porta de conexão no frame acima, depois vai normal

$tem_atualizacoes=false;


$sql = " select db35_codver,
                db30_codver,
		fc_versao(db30_codversao, db30_codrelease) as versaolido
           from db_versao 
	              inner join db_versaousu         on db32_codver = db30_codver
                left  join db_versaolidousuario on db30_codver = db35_codver 
		                                           and db35_id_usuario = ".db_getsession("DB_id_usuario")."
          where db35_codver is null
		        and length(trim(db32_obs)) > 1 
          order by db30_codver limit 1";

$versoes = db_query($sql);
  
if ( pg_numrows($versoes) > 0 ){
   db_fieldsmemory($versoes,0);

   /*
    * Sql que verifica os modulos com atualização de acordo com as permissões do usuário
    * Só será mostrada a janela se houver modulo com atualização para o usuário.
    * 
    * Sql é o mesmo utilizado no arquivo con3_versao004.php    
    */
   
   $sql_modulo = "select distinct modulo,nome_modulo 
                    from (select modulo,nome_modulo 
                            from db_versaousu 
                           inner join db_menu on db32_id_item = db_menu.id_item_filho
                           inner join db_modulos on modulo = db_modulos.id_item 
                           inner join db_permherda h on h.id_usuario = ".db_getsession("DB_id_usuario")."
                           inner join db_usuarios u on u.id_usuario = h.id_perfil and u.usuarioativo = '1'
                           inner join db_permissao p on db_modulos.id_item = p.id_modulo and  p.id_usuario = u.id_usuario 
                           where db32_codver = $db30_codver
                           union
                          select modulo,nome_modulo 
                            from db_versaousu 
                           inner join db_menu on db32_id_item = id_item_filho 
                           inner join db_modulos on modulo = db_modulos.id_item ";
   if ( db_getsession("DB_administrador") != 1 ){
      $sql_modulo .= "     inner join db_permissao p on db_modulos.id_item = p.id_modulo and  p.id_usuario = ".db_getsession("DB_id_usuario");
   }
   
   $sql_modulo .= " where db32_codver = $db30_codver
                    order by nome_modulo) as x";
                    
   $res_modulos = db_query($sql_modulo);
   if ( pg_num_rows($res_modulos) > 0 ) { 
     
	 $tem_atualizacoes = true;
     $versao_lida  = $db30_codver;
	 
   }
}
  


if(!session_is_registered("DB_uol_hora")) {  
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
function js_iniciar() {
  setTimeout("location.href = 'instit.php'",2000);
}
</script>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_iniciar()">
<br><br><br><br><br><br><BR>
<h1 align="center">Aguarde... </h1>
</body>
</html>
<?
} else {
    db_query("update db_usuariosonline 
           set uol_arquivo = '', 
		   uol_modulo = 'Selecionando Instituição' ,
		   uol_inativo = ".time()."
		   where uol_id = ".db_getsession("DB_id_usuario")."
		   and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."' 
		   and uol_hora = ".db_getsession("DB_uol_hora")) or die("Erro(26) atualizando db_usuariosonline"); 

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style type="text/css">
<!--
a {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #C6770D;
	text-decoration: none;
}
a:hover {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #000000;
	text-decoration: underline;
}
.bordas {
	border: 1px solid #000000;
}
-->
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_iniciar() {
  parent.topo.document.getElementById('linkprefa').target = "";
  parent.topo.document.getElementById('linkprefa').href = "javascript:alert('Instituição não selecionada!')";
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_iniciar();">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<center>
    <form name="form1" action="corpo.php" method="post" onSubmit="if(document.form1.selectedIndex == 0) { alert('Escolha uma instituição'); return false; }">
	<table border="0" cellspacing="0" cellpadding="0">
    <tr>
	<td>
       <?
		  if(db_getsession("DB_id_usuario") == "1") {
		    $result = db_query("select codigo,nomeinst,figura from db_config order by prefeitura desc, codigo");
		  } else {
 	        $result = db_query("select c.codigo,c.nomeinst,c.figura 
		                       from db_config c
				    	  	   inner join db_userinst u
							   on u.id_instit = c.codigo
							   where u.id_usuario = ".db_getsession("DB_id_usuario")."
							   order by c.prefeitura desc, c.codigo");
		  }
		  if(pg_numrows($result) == 1 and !$tem_atualizacoes) {
		    echo "<input type=\"hidden\" name=\"instit\" value=\"".pg_result($result,0,"codigo")."\">\n";
			  echo "<script>document.form1.submit()</script>\n";
		  } else {
		    ?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr> 
                <td height="430" align="center" valign="middle" bgcolor="#CCCCCC">
                <table border="1" cellspacing="5" cellpadding="5">
                <?
  	             echo "<tr>\n";
	             for($i = 0;$i < pg_numrows($result);$i++) {
	               echo "<td class=\"bordas\">
	                     <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	                     <tr><td align=\"center\" valign=\"middle\"><a href=\"corpo.php?".base64_encode("instit=".pg_result($result,$i,"codigo"))."\"><img src=\"imagens/files/".pg_result($result,$i,"figura")."\" alt=\"".pg_result($result,$i,"nomeinst")."\" onmouseover=\"js_msg_status(this.alt)\" onmouseout=\"js_lmp_status()\" border=\"0\" width=\"100\" height=\"100\"></a></td></tr>
	                     <tr><td align=\"center\" valign=\"middle\"><a href=\"corpo.php?".base64_encode("instit=".pg_result($result,$i,"codigo"))."\" title=\"".pg_result($result,$i,"nomeinst")."\"  onmouseover=\"js_msg_status(this.title)\" onmouseout=\"js_lmp_status()\">".pg_result($result,$i,"nomeinst")."</a></td></tr>	   
	                     </table>
	                     </td>\n";
	               if(($i % 5) == 0 && $i > 1)
	                  echo "</tr><tr>\n";
	               }
	             echo "</tr>\n";
	             ?>
              </table>
              </td>
             </tr>
           </table>
          <?
		  }
	    ?>
	  </td>
	  </tr>
	  </table>
      </form>
	</center>
  </td>
  </tr>
</table>
</body>
</html>
<?
} //fim do if(!isset($codigoporta))
	
  if($tem_atualizacoes){
    echo "<script>
        js_OpenJanelaIframe('top.corpo','db_iframe_confirma_atualizacoes','con3_versao004.php?versao_lida=".$versao_lida."&registra_atualizacao=true','Leia as atualizações e confirme a leitura no final no texto.',true);
        </script>
    ";
  }

?>