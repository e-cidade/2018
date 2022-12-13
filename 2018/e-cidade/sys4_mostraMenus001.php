<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$sWhereUsuario = "";
if (db_getsession('DB_id_usuario') != 1) {
  $sWhereUsuario = " and db_permissao.id_usuario = ".db_getsession('DB_id_usuario');  
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript">
</script>
<style type="text/css">
<!--
td {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
input {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
  height: 17px;
  border: 1px solid #999999;
}
-->
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_pesquisaitemcad(jsvalor,jsmodulos){

  return false;

  if(document.form1.ambiente[0].checked)
    ambiente = '1';
  else
    ambiente = '0';
  location.href='con4_orgitens003.php?coditem='+jsvalor+'&modulos='+jsmodulos+'&ambiente='+ambiente;
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect()" >

<table width="790" border="0" cellspacing="0" cellpadding="0">
<tr><td height="430" align="left" valign="top" bgcolor="#CCCCCC">
  <center>
    <form name="form1" method="post">
      <?
     if(!isset($mod)){
      ?>
      <table border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td> <strong>Pesquisa:</strong><br> <input name="procura" type="text" id="procura" onKeyUp="js_pesquisa(this.value.toLowerCase(),document.form1.modulos)" size="25"></td>
      </tr>
    <Tr>
      <td> <strong>M&oacute;dulo:</strong><br>
        <?

       $sSqlModulos = "select distinct db_modulos.id_item,nome_modulo,descr_modulo
                           from db_modulos
                                inner join db_itensmenu on db_itensmenu.id_item = db_modulos.id_item
                                inner join db_permissao on db_permissao.id_item = db_itensmenu.id_item
                          where libcliente is true
                                   {$sWhereUsuario}
                          order by nome_modulo ";

      $result  = db_query( $sSqlModulos );
      $numrows = pg_numrows($result);

      echo "<select onDblClick=\"document.form1.mod.click()\" name=\"modulos\" size=\"18\"  >";
      for($i = 0;$i < $numrows;$i++) {
        echo "<option value=\"".pg_result($result,$i,"id_item")."\">".pg_result($result,$i,"nome_modulo")."</option>\n";
      }
    ?>
        </select>
      </td>
    </Tr>
    <tr>
      <td>
    <input onClick="if(document.form1.modulos.selectedIndex == -1 ) { alert('Selecione um módulo!'); return false; }" name="mod" type="submit" id="selecionar" value="Selecionar"></td>
    </tr>
    </table>
    <?
    } else {
        $result = db_query("select nome_modulo,descr_modulo from db_modulos where id_item = ".$modulos);
        $mod = pg_result($result,0,0);
        $des = pg_result($result,0,1);
    ?>
<table border="1" cellspacing="0" cellpadding="0">
<tr><td>
       <table border="0" cellspacing="0" cellpadding="0">
       <tr>
       <td>Módulo:</td>
       <td nowrap><?=$mod?>&nbsp;&nbsp;<input size="1" type="button" onclick="js_pesquisaitemcad('<?=$modulos?>','<?=$modulos?>')" id="ID<?=$modulos?>" name="CHECK<?=$modulos?>" value="<?=$modulos?>" ><font style="font-size:10px">(<?=$des?>)</font></td>

     </tr>
    </table>
</td></tr>
<tr><td valign="top">
</td></tr>
<tr>
<td valign="top">
      <table border="1" cellspacing="0" cellpadding="0">
         <tr>
           <td>
       <?
       $ambiente = (!isset($ambiente)?"1":$ambiente);
       $wid      = 15;
       $conta    = 0;
       /***************/
       $SQL  = " select distinct i.id_item as pai, ";
       $SQL .= "        m.id_item, ";
       $SQL .= "        m.id_item_filho, ";
       $SQL .= "        m.modulo, ";
       $SQL .= "        i.descricao, ";
       $SQL .= "        i.help, ";
		   $SQL .= "        i.funcao, ";
       $SQL .= "        m.menusequencia ";
       $SQL .= "   from db_itensmenu i ";
       $SQL .= "        inner join db_menu m on m.id_item_filho = i.id_item ";
       $SQL .= "        inner join db_permissao on db_permissao.id_item = i.id_item ";
       $SQL .= "  where m.modulo    = $modulos  ";
       $SQL .= "    and i.itemativo = $ambiente ";
       $SQL .= "    and m.id_item   = $modulos  ";
       $SQL .= "	  {$sWhereUsuario} ";
       $SQL .= "    and db_permissao.anousu      = ".db_getsession('DB_anousu');
       $SQL .= "    and db_permissao.id_modulo   = $modulos ";

       $result = db_query($SQL);
       for($i = 0;$i < pg_numrows($result);$i++) {
         $valor = pg_result($result,$i,"id_item_filho");
         echo "<td id=\"col$i\" valign=\"top\" nowrap>\n<input size=\"1\" type=\"button\" onclick=\"parent.js_CadastrarMenu('$valor','".$modulos."')\" id=\"ID$valor\" name=\"CHECK$valor\" value=\"$valor\" >
               <label for=\"ID$valor\"> ".pg_result($result,$i,"descricao")."</label><br>\n";
              submenus(pg_result($result,$i,"pai"),"col".$i,db_strpos($modulos,"##"));
         echo "</td>\n";
       }
       ?>
       </td>
         </tr>
       </table>
</td></tr>
</table>
  <?
  }
  ?>
    </form>
  </center>
  </td></tr>
</table>
</body>
</html>
<?
function submenus($item,$id,$mod) {

  $sWhereUsuario = "";
  if (db_getsession('DB_id_usuario') != 1) {
    $sWhereUsuario = " and db_permissao.id_usuario = ".db_getsession('DB_id_usuario');  
  }

	
  global $conta;
  global $wid;
  global $ambiente;

  $sSqlSubMenus  = "select distinct m.menusequencia, m.id_item_filho, ";
  $sSqlSubMenus .= "       i.descricao, ";
  $sSqlSubMenus .= "       i.help, ";
  $sSqlSubMenus .= "       i.funcao, ";
  $sSqlSubMenus .= "       m.id_item,m.modulo ";
  $sSqlSubMenus .= "  from db_menu m ";
  $sSqlSubMenus .= "       inner join db_itensmenu i on i.id_item = m.id_item_filho ";
  $sSqlSubMenus .= "       inner join db_permissao on db_permissao.id_item = i.id_item ";
  $sSqlSubMenus .= " where m.modulo = $mod ";
  $sSqlSubMenus .= "   and m.id_item = $item ";
  $sSqlSubMenus .= "   and i.itemativo = $ambiente ";
  $sSqlSubMenus .= "   {$sWhereUsuario} ";
  $sSqlSubMenus .= "   and db_permissao.anousu      =  ".db_getsession('DB_anousu');
  $sSqlSubMenus .= "   and db_permissao.id_modulo   = $mod ";
  $sSqlSubMenus .= " order by m.id_item,m.menusequencia";

  $rsSubMenus = db_query($sSqlSubMenus);
  $iNumRows   = pg_num_rows($rsSubMenus);

  if($iNumRows > 0) {
    $sHtml = "";
    for($x = 0;$x < $iNumRows;$x++) {

      $valor = pg_result($rsSubMenus,$x,"id_item_filho");
      $sHtml .= "<img src=\"imagens/alinha.gif\" ";
      $sHtml .= "     height=\"5\" ";
      $sHtml .= "     id=\"Img".$conta."\" ";
      $sHtml .= "     width=\"".$wid."\" >";
      $sHtml .= "<input size=\"1\" ";
      $sHtml .= "       onClick=\"parent.js_CadastrarMenu('$valor','".$GLOBALS['modulos']."')\" ";
      $sHtml .= "       type=\"button\" ";
      $sHtml .= "       id=\"ID$valor\" ";
      $sHtml .= "       name=\"CHECK$valor\" ";
      $sHtml .= "       value=\"$valor\" > ";
      $sHtml .= "<label for=\"ID$valor\">".pg_result($rsSubMenus,$x,"descricao")."</label><br>\n";

      echo $sHtml;

      $sHtml = '';

      $wid += 15;
      $conta++;
      submenus(pg_result($rsSubMenus,$x,"id_item_filho"),$id,$mod);
      $wid -= 15;

    }

  }

}
?>