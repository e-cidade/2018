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

session_start();
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));

if( !session_is_registered("DB_instit")) {
  session_register("DB_instit");
  if(isset($HTTP_POST_VARS["instit"])){
     db_putsession("DB_instit",$HTTP_POST_VARS["instit"]);
  }else{
     parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
     if(isset($instit)){
        db_putsession("DB_instit",$instit);
	   }else{
	      echo "<script>
		          location.href='instit.php';
		          </script>";
		 exit;
    }
  }
}
	
    pg_exec("update db_usuariosonline 
           set uol_arquivo = '', 
		   uol_modulo = 'Selecionando Área' ,
		   uol_inativo = ".time()."
		   where uol_id = ".db_getsession("DB_id_usuario")."
		   and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."' 
		   and uol_hora = ".db_getsession("DB_uol_hora")) or die("Erro(26) atualizando db_usuariosonline"); 


$result = pg_exec("select nomeinst as nome,ender,telef,cep,email,url from db_config where codigo = ".db_getsession("DB_instit"));
if(pg_numrows($result) > 0) {
  db_fieldsmemory($result,0);
  echo "
    <script>
    parent.topo.document.getElementById('infoConfig').innerHTML = '$nome<br>$ender<br>Fone:&nbsp;$telef&nbsp;&nbsp;-&nbsp;&nbsp;Cep:&nbsp;$cep';
    parent.topo.document.getElementById('linkprefa').href = '$url/dbpref/';
    parent.topo.document.getElementById('linkprefa').target = '_blank';
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
  border: 3px outset #666666; 
  border-left: 2px outset #333333; 
  border-top: 2px outset #333333;
}
-->
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_iniciar() {
  parent.topo.document.getElementById('linkprefa').target = "";
  parent.topo.document.getElementById('linkprefa').href = "javascript:alert('Instituição não selecionada!')";
}
function js_status_area(){
  parent.bstatus.document.getElementById('st').innerHTML = '&nbsp;&nbsp;Selecione a Área clicando na figura ou no nome.';
  parent.bstatus.document.getElementById('dtatual').innerHTML  = '<?=(isset($HTTP_SESSION_VARS["DB_datausu"])?date("d/m/Y",db_getsession("DB_datausu")):date("d/m/Y"))  ?>';
  parent.bstatus.document.getElementById('dtanousu').innerHTML = '<?=(isset($HTTP_SESSION_yARS["DB_anousu"])?db_getsession("DB_anousu"):date("Y"))  ?>';
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
    <form name="form1" action="corpo.php" method="post" onSubmit="if(document.form1.selectedIndex == 0) { alert('Escolha uma Área'); return false; }">
	<table border="0" cellspacing="0" cellpadding="0">
    <tr>
	<td>
       <?
       //echo "DB_id_usuario = ".db_getsession("DB_id_usuario"). "  DB_administrador = ".db_getsession("DB_administrador"); 
		  if(db_getsession("DB_id_usuario") == "1" || db_getsession("DB_administrador") == 1 ) {
        
		    $result = pg_exec("select distinct at26_sequencial,at25_descr, at25_figura 
                           from atendcadarea 
                                inner join atendcadareamod on at26_sequencial=at26_codarea
                           order by at25_descr");
		    
		  } else {

		    $result = pg_exec("select distinct at26_sequencial,at25_descr, at25_figura
                           from atendcadarea 
                                inner join atendcadareamod on at26_sequencial=at26_codarea
                           where at26_id_item in (
		               
                           select id_item from (
		     	                        select distinct i.id_modulo as id_item,m.descr_modulo,it.help,it.funcao,m.imagem,m.nome_modulo,
                                         case when u.anousu is null then to_char(CURRENT_DATE,'YYYY')::int4 else u.anousu end
                                  from ( select distinct i.itemativo,p.id_modulo,p.id_usuario,p.id_instit
						                             from db_permissao p 
                                              inner join db_itensmenu i on p.id_item = i.id_item 
						                             where i.itemativo = 1
                                           and p.id_usuario = ".db_getsession("DB_id_usuario")."
                                           and p.id_instit = ".db_getsession("DB_instit")." 
						                               and p.anousu = ".(isset($HTTP_SESSION_VARS["DB_datausu"])?date("Y",db_getsession("DB_datausu")):date("Y"))." 
                                       ) as i						
                                       inner join db_modulos m on m.id_item = i.id_modulo
                                       inner join db_itensmenu it on it.id_item = i.id_modulo
                                       left outer join db_usumod u on u.id_item = i.id_modulo and u.id_usuario = i.id_usuario
                                  where i.id_usuario = ".db_getsession("DB_id_usuario")."
					                        	and i.id_instit = ".db_getsession("DB_instit")."    and libcliente is true 
                               
                                 union
                        
                                 select distinct i.id_modulo as id_item,m.descr_modulo,it.help,it.funcao,m.imagem,m.nome_modulo,
                                       case when u.anousu is null then to_char(CURRENT_DATE,'YYYY')::int4 else u.anousu end
                                 from  (
						                             select distinct i.itemativo,p.id_modulo,h.id_usuario,p.id_instit
						  from db_permissao p 
        					  inner join db_permherda h on h.id_perfil = p.id_usuario
						  inner join db_usuarios u on u.id_usuario = h.id_perfil and u.usuarioativo = '1'
						  inner join db_itensmenu i 
						  on p.id_item = i.id_item 
						  where i.itemativo = 1
						  and h.id_usuario = ".db_getsession("DB_id_usuario")."
						  and p.id_instit = ".db_getsession("DB_instit")." 
						  and p.anousu = ".(isset($HTTP_SESSION_VARS["DB_datausu"])?date("Y",db_getsession("DB_datausu")):date("Y"))." 
						) as i						
                        inner join db_modulos m
                        on m.id_item = i.id_modulo
						inner join db_itensmenu it
						on it.id_item = i.id_modulo
                        left outer join db_usumod u
                        on u.id_item = i.id_modulo
						and u.id_usuario = i.id_usuario
                        where i.id_usuario = ".db_getsession("DB_id_usuario")."
                                                  and libcliente is true
						and i.id_instit = ".db_getsession("DB_instit") . "
            )  as yyy " . ( isset($area_de_acesso) ? " 
                     inner join atendcadareamod on yyy.id_item = at26_id_item
                    where at26_codarea = $area_de_acesso
                     ": "" )." order by nome_modulo 

                        )   order by at25_descr");



		  }
		    ?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
          <td height="430" align="center" valign="middle" bgcolor="#CCCCCC"><br>
            <table border="0" cellspacing="15" cellpadding="5">
                <?
                  //echo pg_numrows($result);
  	             echo "<tr>\n";
	             for($i = 0;$i < pg_numrows($result);$i++) {
	               echo "<td class=\"bordas\" >
	                     <table border=\"0\" cellspacing=\"7\" cellpadding=\"0\">
	                     <tr><td align=\"center\" valign=\"middle\" title=\"".pg_result($result,$i,"at25_descr")."\">
                       <a href=\"corpo.php?".base64_encode("instit=".db_getsession("DB_instit")."&area_de_acesso=".pg_result($result,$i,"at26_sequencial"))."\"><img src=\"imagens/files/area/".trim(pg_result($result,$i,"at25_figura"))."\" alt=\"".pg_result($result,$i,"at25_descr")."\" border=\"0\" width=\"150\" height=\"100\"></a></td></tr>
	                     <tr><td align=\"center\" valign=\"middle\">
                       <a href=\"corpo.php?".base64_encode("instit=".db_getsession("DB_instit")."&area_de_acesso=".pg_result($result,$i,"at26_sequencial"))."\" title=\"".pg_result($result,$i,"at25_descr")."\" >".pg_result($result,$i,"at25_descr")."</a></td></tr>	   
	                     </table>
	                     </td>\n";
	               if( (($i+1) % 4) == 0 && $i > 1)
	                  echo "</tr><tr>\n";
	               }
	             echo "</tr>\n";
	             ?>
            </table>
          </td>
        </tr>
      </table>
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
<script>
js_status_area();
</script>