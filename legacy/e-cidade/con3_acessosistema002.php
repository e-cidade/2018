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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
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
<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr> 
         
<?
$sql = "select ip,nome_modulo,case when l.id_item != 0 then fc_montamenu(l.id_item) end as dl_menu
        from db_logsacessa l
             left join db_itensmenu t on t.id_item = l.id_item
             left join db_modulos m on m.id_item = l.id_modulo
        where codsequen = $codacessa
       ";
$result = pg_query($sql);
db_fieldsmemory($result,0);
echo " <td >IP:$ip</td> ";
echo " <td >Modulo:$nome_modulo</td> ";
echo " <td >Menu: $dl_menu</td>";
?>

</tr>
</table>
<br>
<table width="100%" border="1" cellpadding="0" cellspacing="0">
  <tr> 
    <td bgcolor="#FFCC00">Data</td>
    <td bgcolor="#FFCC00">Hora</td>
    <td bgcolor="#FFCC00">Usu&aacute;rio</td>
    <td bgcolor="#FFCC00">Campo</td>
    <td bgcolor="#FFCC00">Chave</td>
    <td bgcolor="#FFCC00">Tipo</td>
    <td bgcolor="#FFCC00">Conte&uacute;do Anterior</td>
    <td bgcolor="#FFCC00">Conte&uacute;do Atual</td>
  </tr>
<?

$sql = "select distinct d.*,c.nomecam, u.nome
        from db_acountacesso a
           inner join db_acount d on d.id_acount = a.id_acount
           inner join db_syscampo c on c.codcam = d.codcam
           inner join db_usuarios u on u.id_usuario = d.id_usuario
        where a.codsequen = $codacessa 
        order by id_acount ";

$result = pg_query($sql);

if ( pg_numrows($result) > 0 ) {
  $id_acount_ant = pg_result($result,0,"id_acount");
  $cor="#CCCCCC";
}

for($x=0;$x<pg_numrows($result);$x++){

    if ( pg_result($result,$x,"id_acount") != $id_acount_ant ) {
      $id_acount_ant = pg_result($result,$x,"id_acount");
      if ( $cor=="#7F7F7F" ) {
        $cor="#CCCCCC";
      } else {
        $cor="#7F7F7F";
      }
    }

   $chavetitle = "";
   db_fieldsmemory($result,$x);
   $sql = "select campotext as keychave ,c.nomecam as nomecamkey, actipo
                    from db_acountkey y
                         inner join db_syscampo c on codcam = id_codcam
                    where id_acount = $id_acount";
   $res = pg_query($sql);
   if($res!=false){
      for($ii=0;$ii<pg_numrows($res);$ii++){  
         db_fieldsmemory($res,$ii);
         $chavetitle .= $nomecamkey."->".$keychave."\n";
      }
   }
   $processa = true;
   if(isset($dbh_campo) && $dbh_campo!=0){
     if ($dbh_campo != $codcam )
       $processa = false;
     
   }
   if($processa){
      echo "<tr>\n"; 
      echo "  <td bgcolor=$cor  title=\"".$chavetitle."\">".date("d/m/Y",$datahr)."</td>\n";
      echo "  <td bgcolor=$cor  title=\"".$chavetitle."\">".date("H:i",$datahr)."</td>\n";
      echo "  <td bgcolor=$cor  nowrap title=\"".$chavetitle."\">(".$id_usuario.")".substr($nome,0,20)."</td>\n";
      echo "  <td bgcolor=$cor  title=\"".$chavetitle."\">".$nomecam."</td>\n";
      echo "  <td bgcolor=$cor  title=\"".$chavetitle."\">".$chavetitle."</td>\n";
      echo "  <td bgcolor=$cor  title=\"".$chavetitle."\">".$actipo."</td>\n";
      echo "  <td bgcolor=$cor  >".($actipo=='A'||$actipo=='I'?$contant:$contatu)."&nbsp;</td>\n";
      echo "  <td bgcolor=$cor  >".($actipo=='A'||$actipo=='I'?$contatu:$contant)."&nbsp;</td>\n";
      echo "</tr>\n";
    }
}
?>
</table>

</body>
</html>