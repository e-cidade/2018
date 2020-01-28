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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/verticalTab.widget.php");
require_once("classes/db_processoforo_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clprocessoforo = new cl_processoforo;
$clprocessoforo->rotulo->label();

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js");
  db_app::load("widgets/windowAux.widget.js,messageboard.widget.js");
  db_app::load("estilos.css, grid.style.css,tab.style.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<center>
<form name="form1" method="post">
<table width="100%" style="margin-top: 20px;" align="center" border="1">
<?
$sWhere           = " v70_sequencial = {$oGet->v70_sequencial}";
$sSqlProcessoForo = $clprocessoforo->sql_query_cgm_nome(null, " distinct cgm.z01_numcgm, cgm.z01_nome, cgmr.z01_numcgm as numcgm, cgmr.z01_nome as nome", null, $sWhere,false);
$rsProcessoForo   = $clprocessoforo->sql_record($sSqlProcessoForo);
if ($clprocessoforo->numrows > 0) {
  echo "<tr>";
  echo "<td> <b>Nomes das Iniciais </b>";
  echo "</td>";
  echo "<td> <b>Nome no Processo</b>";
  echo "</td>";
  echo "</tr>";
  
  for( $i =0 ; $i <$clprocessoforo->numrows; $i ++){
    db_fieldsmemory($rsProcessoForo, $i);
    echo "<tr>";
    echo "<td>".$z01_numcgm."-".$z01_nome;
    echo "</td>";
    echo "<td>".$numcgm."-".$nome;
    echo "</td>";
    echo "</tr>";    
  }
}else{
  echo "<tr>";
  echo "<td>";
  echo "<b> Não há nomes cadastrados.</b>";
  echo "</td>";
  echo "</tr>";
}
?>
</table>
</center>
</body>