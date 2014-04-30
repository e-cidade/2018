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

session_start();
//session_unregister("DB_Area");

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
require("libs/db_conn.php");
/*
echo "<pre>";
echo var_dump($_SESSION);
echo "</pre>";
echo "<pre>";
echo var_dump($HTTP_GET_VARS);
echo "</pre>";
echo "<pre>";
echo var_dump($HTTP_POST_VARS);
echo "</pre>";
echo "<pre>";
echo var_dump(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
echo "</pre>";
*/
if(session_is_registered("DB_uol_hora")) {
  pg_exec("update db_usuariosonline 
            set uol_arquivo = '', 
			uol_modulo = 'Selecionando Módulo' ,
			uol_inativo = ".time()."
            where uol_id = ".db_getsession("DB_id_usuario")."
			and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."' 
			and uol_hora = ".db_getsession("DB_uol_hora"))
   or die("Erro(26) atualizando db_usuariosonline");
}

if(isset($DB_SELLER)){
  if(!session_is_registered("DB_SELLER")) {
     session_register("DB_SELLER");
     db_putsession("DB_SELLER","on");
  }
  if(!session_is_registered("DB_NBASE")) {
    session_register("DB_NBASE");
    db_putsession("DB_NBASE",$DB_BASE);
  }
}else if(session_is_registered("DB_NBASE")) {
  session_unregister("DB_NBASE");
}

if(!session_is_registered("DB_instit")) {
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
  
  db_logsmanual_demais("Acesso instituição - Login: ".db_getsession("DB_login"),db_getsession("DB_id_usuario"),0,0,0,db_getsession("DB_instit")); 
}else{
	parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
  if(isset($area_de_acesso)){
    db_putsession("DB_Area",$area_de_acesso);
    
  }
}

if(db_getsession("DB_instit") == "") {
  
	db_erro("Instituição não selecionada.",0);
}
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
	color: #FFFFFF;
	text-decoration: none;
}
a:hover {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #FFFFFF;
	text-decoration: none;
}
.bordas {
	border: 1px solid #000000;
}
-->
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_atualizacao_versao(){
  js_OpenJanelaIframe('top.corpo','dbiframe_atualiza','con3_versao004.php?nao_imprimir=true;id_item=0',"Atualizacoes");
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="100%" align='center'><strong><a href='#' onclick='js_atualizacao_versao();'>Leia as Atualizacoes - Clique Aqui</a></strong></td>
  </tr>
</table>
<table height="100%" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="middle" bgcolor="#CCCCCC">
      <table border="0" cellspacing="1" cellpadding="0">
        <?
        if(session_is_registered("DB_Area")){
        	$area_de_acesso = db_getsession("DB_Area");
        }
	if (db_getsession("DB_id_usuario") == 1 || db_getsession("DB_administrador") == 1) {
		     $sql = "select distinct 	db_modulos.id_item,
		     				                  db_modulos.descr_modulo,
						                      db_itensmenu.help,
						                      db_itensmenu.funcao,
						                      db_modulos.imagem,
						                      db_modulos.nome_modulo,
						                      extract (year from current_date) as anousu
		     			               from db_itensmenu
					                        inner join db_menu on 	db_itensmenu.id_item = db_menu.id_item
					                        inner join db_modulos 	on db_itensmenu.id_item = db_modulos.id_item
                                  ";
          if( isset($area_de_acesso) ){
             $sql .= "            inner join atendcadareamod on db_modulos.id_item = at26_id_item
                            where libcliente is true and at26_codarea = $area_de_acesso
                     ";
          }else{
            $sql .= "       where libcliente is true ";
          }
          $sql .= " 	      order by db_modulos.nome_modulo";
//	 die($sql);
	} else {
		     $sql = "select * from (
		     	select distinct i.id_modulo as id_item,m.descr_modulo,it.help,it.funcao,m.imagem,m.nome_modulo,
                        case when u.anousu is null then to_char(CURRENT_DATE,'YYYY')::int4 else u.anousu end
                        from 
						(
						  select distinct i.itemativo,p.id_modulo,p.id_usuario,p.id_instit
						  from db_permissao p 
						  inner join db_itensmenu i 
						  on p.id_item = i.id_item 
						  where i.itemativo = 1
						  and p.id_usuario = ".db_getsession("DB_id_usuario")."
						  and p.id_instit = ".db_getsession("DB_instit")." 
						  and (p.anousu = ".(isset($HTTP_SESSION_VARS["DB_datausu"])?date("Y",db_getsession("DB_datausu")):date("Y"))." 
               or  p.anousu = ".(isset($HTTP_SESSION_VARS["DB_datausu"])?date("Y",db_getsession("DB_datausu")):date("Y"))."+1)
						) as i						
                        inner join db_modulos m
                        on m.id_item = i.id_modulo
						inner join db_itensmenu it
						on it.id_item = i.id_modulo
                        left outer join db_usumod u
                        on u.id_item = i.id_modulo
						and u.id_usuario = i.id_usuario
                        where i.id_usuario = ".db_getsession("DB_id_usuario")."
						and i.id_instit = ".db_getsession("DB_instit")
                                    ."    and libcliente is true 
		      union
		     select distinct i.id_modulo as id_item,m.descr_modulo,it.help,it.funcao,m.imagem,m.nome_modulo,
                        case when u.anousu is null then to_char(CURRENT_DATE,'YYYY')::int4 else u.anousu end
                        from 
						(
						  select distinct i.itemativo,p.id_modulo,h.id_usuario,p.id_instit
						  from db_permissao p 
        					  inner join db_permherda h on h.id_perfil = p.id_usuario
						  inner join db_usuarios u on u.id_usuario = h.id_perfil and u.usuarioativo = '1'
						  inner join db_itensmenu i 
						  on p.id_item = i.id_item 
						  where i.itemativo = 1
						  and h.id_usuario = ".db_getsession("DB_id_usuario")."
						  and p.id_instit = ".db_getsession("DB_instit")." 
						  and (p.anousu = ".(isset($HTTP_SESSION_VARS["DB_datausu"])?date("Y",db_getsession("DB_datausu")):date("Y"))." 
               or  p.anousu = ".(isset($HTTP_SESSION_VARS["DB_datausu"])?date("Y",db_getsession("DB_datausu")):date("Y"))."+1)
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
            )  as yyy ";

          $iNumModulos = isset($_SESSION["DB_totalmodulos"]) == true ? $_SESSION["DB_totalmodulos"] : 0;
                                    
          if( (isset($area_de_acesso) && $iNumModulos > 20) || (!isset($_GET["link"]) && isset($area_de_acesso))){
             $sql .= " 
                    inner join atendcadareamod on yyy.id_item = at26_id_item
                    where at26_codarea = $area_de_acesso
                     ";
          }
 
          $sql .= " order by nome_modulo ";


         }
         $result = pg_exec($sql) or die($sql);
         $numrows = pg_numrows($result);
         if ( $numrows == 0 ) {
            db_erro("Usuário sem nenhuma permissao de acesso! Contate suporte!",0);
	          exit;
	       }else{
            
           if( ! isset($area_de_acesso) && !session_is_registered("DB_Area")) {
             if ( $numrows > 20  ){
             	db_putsession("DB_totalmodulos",$numrows);
             //	die('qwdfkjsdfkjsdfsdflkçdsflksdf');
               echo "<script>location.href='area.php?instit=".db_getsession("DB_instit")."';</script>";
               exit;
             }
           }
           
	       	 
         }

	 echo "<tr>\n";
	 for($i = 0;$i < $numrows;$i++) {
	   echo "<td align=\"center\" valign=\"top\">
	   <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"border: 2px solid #cccccc;\" onMouseOver=\"this.bgColor='#c3c3c3'; this.style.border = '2px outset #666666'; this.style.borderLeft = '2px outset #999999'; this.style.borderTop = '2px outset #999999';\" onMouseOut=\"this.bgColor='#999999'; this.style.border = '2px solid #cccccc'\" bgcolor=\"#999999\">
             <tr>
	       <td>
                 <table border=\"0\" style=\"border: 3px outset #666666; border-left: 2px outset #333333; border-top: 2px outset #333333\" cellspacing=\"0\" cellpadding=\"0\">
       	           <tr><td align=\"center\" valign=\"middle\"><a  title='".pg_result($result,$i,"help")."' id=\"link\" href=\"modulos.php?".base64_encode("anousu=".pg_result($result,$i,"anousu")."&modulo=".pg_result($result,$i,"id_item")."&nomemod=".pg_result($result,$i,"nome_modulo"))."\"><img src=\"imagens/modulos/".(trim(pg_result($result,$i,"imagem"))==""?"img.php?nome=".pg_result($result,$i,"nome_modulo")."":pg_result($result,$i,"imagem"))."\" alt=\"".pg_result($result,$i,"help")."\" onmouseover=\"js_msg_status(this.alt)\" onmouseout=\"js_msg_status('Selecione o módulo clicando na figura.')\" border=\"0\" width=\"100\" height=\"100\"></a></td></tr>
	           <tr><td>
                     <table width=\"100%\" style=\"border: 1px solid #666666;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	               <tr><td align=\"center\" valign=\"middle\"><a href=\"modulos.php?".base64_encode("anousu=".pg_result($result,$i,"anousu")."&modulo=".pg_result($result,$i,"id_item")."&nomemod=".pg_result($result,$i,"nome_modulo"))."\" title=\"".pg_result($result,$i,"help")."\"  onmouseover=\"js_msg_status(this.title)\" onmouseout=\"js_msg_status('Selecione o módulo clicando na figura.')\">".pg_result($result,$i,"descr_modulo")."</a></td></tr>	   
	             </table>
		   </td></tr>
		 </table>  
	       </td>
	     </tr>
	   </table>\n";
	   if((($i + 1) % 9) == 0)
	     echo "</tr><tr>\n";
	 }
	 echo "</tr>\n";
	?>
    </table>
  </td>
  </tr>
</table>
<div id="joao"></div>
</body>
</html>
       <script>
       parent.bstatus.document.getElementById('st').innerHTML = '&nbsp;&nbsp;Selecione o módulo clicando na figura.';
       parent.bstatus.document.getElementById('dtatual').innerHTML  = '<?=(isset($HTTP_SESSION_VARS["DB_datausu"])?date("d/m/Y",db_getsession("DB_datausu")):date("d/m/Y"))  ?>';
       parent.bstatus.document.getElementById('dtanousu').innerHTML = '<?=(isset($HTTP_SESSION_yARS["DB_anousu"])?db_getsession("DB_anousu"):date("Y"))  ?>';
       </script>