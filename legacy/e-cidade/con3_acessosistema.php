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
include("classes/db_db_usuarios_classe.php");
include("classes/db_db_modulos_classe.php");
db_postmemory($HTTP_POST_VARS);


$cldb_usuarios = new cl_db_usuarios;
$cldb_modulos = new cl_db_modulos;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_mostraacount(codacessa){
  js_OpenJanelaIframe('','db_iframe_acessa','con3_acessosistema002.php?codacessa='+codacessa,'',true);
}
</script>
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
<br>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<form name="form1" action="" method="post">
<tr>
<td align='right'><strong>Periodo:</strong></td>
<td align='left'><?
db_inputdata("dataini",@$dataini_dia,@$dataini_mes,@$dataini_ano,true,'text',2);
echo "<strong> &nbsp a &nbsp</strong>";
db_inputdata("datafim",@$datafim_dia,@$datafim_mes,@$datafim_ano,true,'text',2);
?></td>
</tr>
<tr>
<td align='right'><strong>Usuario:</strong></td>
<td align='left'><?
$resultusu = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file(null,"id_usuario,nome||' ---- '||login as nome","nome"," usuarioativo = '1' and usuext = 0"));
db_selectrecord("id_usuario",$resultusu,true,'texte',2,null,null,'0');

?></td>
</tr>
<tr>
<td align='right'><strong>Modulo:</strong></td>
<td align='left'><?
//$resultmod = $cldb_modulos->sql_record($cldb_modulos->sql_query(null,'*',null," libcliente = true  "));
$resultmod = $cldb_modulos->sql_record("select m.id_item,nome_modulo from db_modulos m inner join db_itensmenu i on m.id_item = i.id_item where libcliente = true order by nome_modulo ");

db_selectrecord("modulo",$resultmod,true,'texte',2,null,null,'0');

?></td>
</tr>
<tr>

<td align='center' colspan='2'><input name='pesquisar' type='submit' value='Pesquisar'></td>
</tr>

</form>
</table>
<?

if(isset($pesquisar) || count($HTTP_POST_VARS)>0){
  
  $sql = "select codsequen,ip,data,hora,
                   case when (select db_acountacesso.codsequen from db_acountacesso where db_acountacesso.codsequen = l.codsequen limit 1 ) is not null then 'SIM' end::varchar as dl_acount,
                 arquivo,obs,l.id_usuario,
                 login||'  Nome: '||nome::text as login,
                 nome_modulo,
                 l.id_item,
                 case when l.id_item != 0 then fc_montamenu(l.id_item) end as dl_menu
          from db_logsacessa l
               left join db_itensmenu t on t.id_item = l.id_item
               left join db_usuarios u on u.id_usuario = l.id_usuario
               left join db_modulos m on m.id_item = l.id_modulo
               inner join db_config c on c.codigo = l.instit
          where 1=1 ";
  if($dataini_dia != 0 ){
    $sql .= " and data  between '$dataini_ano-$dataini_mes-$dataini_dia' and '$datafim_ano-$datafim_mes-$datafim_dia' ";
  }
  if($id_usuario != 0 ){
    $sql .= " and l.id_usuario = $id_usuario ";
  }
  if($modulo != 0 ){
    $sql .= " and l.id_modulo = $modulo ";
  }
  $sql .= " order by data desc,hora desc";

  $variaveis["id_usuario"] = $id_usuario;
  $variaveis["modulo"] = $modulo;
  $variaveis["dataini_dia"] = $dataini_dia;
  $variaveis["dataini_mes"] = $dataini_mes;
  $variaveis["dataini_ano"] = $dataini_ano;  
  $variaveis["datafim_dia"] = $datafim_dia;
  $variaveis["datafim_mes"] = $datafim_mes;
  $variaveis["datafim_ano"] = $datafim_ano;
  db_lovrot($sql,20,"()","","js_mostraacount|codsequen",null,"NoMe",$variaveis);

}

db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>