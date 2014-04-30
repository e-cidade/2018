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
include("libs/db_liborcamento.php");
include("classes/db_orcfontes_classe.php");
include("classes/db_orcreceita_classe.php");
$clorcfontes = new cl_orcfontes;
$clorcreceita= new cl_orcreceita;
$clorcfontes->rotulo->label();
$clorcreceita->rotulo->label();
db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

function js_abre(codrec){

   js_OpenJanelaIframe('','db_iframe_orgao','func_saldoorcreceita.php?o70_codrec='+codrec,'pesquisa',true);
 
}

</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<table>
<tr height="20px">
<td ></td>
<td ></td>
</tr>
<?

 

 $filtro  = " o70_anousu = ".db_getsession("DB_anousu") . " and o70_instit = " . db_getsession("DB_instit");
 $filtro .= " and o15_datalimite is null or o15_datalimite > '".date('Y-m-d',db_getsession('DB_datausu'))."'";
 $sql = " select fc_estruturalreceita(o70_anousu,o70_codrec) as dl_estrutural,o57_descr,o70_codrec,o70_codigo,o15_descr
            from orcreceita d
	               inner join orcfontes e on e.o57_codfon = d.o70_codfon and o57_anousu = o70_anousu
	               inner join orctiporec r on r.o15_codigo = d.o70_codigo";
             if(!empty($filtro)) {
               $sql .= " where ";
               $sql .= $filtro." order by dl_estrutural";
             }

 db_lovrot($sql,20,"()","","js_abre|o70_codrec");
 ?>
 <!---
 <input name="retorna" value="Retornar" onclick="location.href='orc3_saldoorcreceita001.php'" type="button">
 --->
 <?
?>
</center>
<?
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>