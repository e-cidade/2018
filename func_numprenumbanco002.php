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
require_once('libs/db_utils.php');
require_once("dbforms/db_funcoes.php");
require_once("classes/db_arrecadcompos_classe.php");
require_once("classes/db_arreckey_classe.php");

$oGet = db_utils::postMemory($_GET);

$clarrecadcompos = new cl_arrecadcompos;
$clarreckey      = new cl_arreckey;

$clarrecadcompos->rotulo->label();
$clarreckey->rotulo->label();
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>
<center>
<?
$sWhereArrecadCompos  = "arreckey.k00_numpre = {$oGet->numpre} and ";  
$sWhereArrecadCompos .= "arreckey.k00_numpar = {$oGet->numpar} and ";
$sWhereArrecadCompos .= "arreckey.k00_receit = {$oGet->receita}    ";
$sCampos              = "arreckey.k00_sequencial as seqprincipal, arrecadcompos.*";
$sSqlArrecadCompos    = $clarrecadcompos->sql_query(null,$sCampos,null,$sWhereArrecadCompos);
$rsArrecadCompos      = $clarrecadcompos->sql_record($sSqlArrecadCompos);

if ($clarrecadcompos->numrows > 0) {
?>
 <table border='0' cellspacing="0" cellpadding="0" width="95%" style='border:2px inset white'>
  <tr>
    <th class='table_header' align='center'><b>Sequencial Principal</b></th>
    <th class='table_header' align='center'><b>Sequencial Secundário</b></th>
    <th class='table_header' align='center'><b>Histórico</b></th>
    <th class='table_header' align='center'><b>Correção</b></th>
    <th class='table_header' align='center'><b>Juros</b></th>
    <th class='table_header' align='center'><b>Multa</b></th>
    <th class='table_header' align='center' style='width:18px'>&nbsp;</th>
  </tr>
  <tbody style='height:170px;overflow:scroll;overflow-x:hidden;background-color:white'>
  <?
    for ($iInd = 0; $iInd < $clarrecadcompos->numrows; $iInd++) {
    	
      $oArrecadCompos = db_utils::fieldsMemory($rsArrecadCompos,$iInd);
  ?>
    <tr style='height:1em'>
      <td class='linhagrid' style='text-align:center'><?=$oArrecadCompos->seqprincipal;?></td>
      <td class='linhagrid' style='text-align:center'><?=$oArrecadCompos->k00_sequencial;?></td>
      <td class='linhagrid' style='text-align:right'><?=$oArrecadCompos->k00_vlrhist;?></td>
      <td class='linhagrid' style='text-align:right'><?=$oArrecadCompos->k00_correcao;?></td>
      <td class='linhagrid' style='text-align:right'><?=$oArrecadCompos->k00_juros;?></td>
      <td class='linhagrid' style='text-align:right'><?=$oArrecadCompos->k00_multa;?></td>
    </tr>
  <?
    }
  ?>
    <tr style='height:auto;'><td>&nbsp;</td></tr>
  </tbody>
 </table>
<?
} else {
?>	
	<b>Nenhum registro encontrado</b>
<?	
}
?>
 </center>
</body>
</html>