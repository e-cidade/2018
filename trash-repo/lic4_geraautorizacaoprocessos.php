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
require("libs/db_utils.php");
require("model/licitacao.model.php");

$oPost = db_utils::postMemory($_POST);
if ($oPost->lSelecionaPc == "f") {
  db_redireciona("lic4_geraaut002.php?l20_codigo={$oPost->l20_codigo}");
}
$oLicitacao = new Licitacao($oPost->l20_codigo);
$oDados     = $oLicitacao->getDados();
$oDados->aProcessos = $oLicitacao->getProcessoCompras();
if (count($oDados->aProcessos) == 1) {
  db_redireciona("lic4_geraaut002.php?l20_codigo={$oPost->l20_codigo}");
}
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>

<script>
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table  border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td  height="18">&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
</table>
<center>
<form method="post" name='frmProcessos'>
  <input type='hidden' name='l20_codigo' id='l20_codigo' value='<?=$oPost->l20_codigo;?>'>
  <table width="70%">
    <tr>
      <td style='text-align:center'>
        <h3>Licitação <?=$oPost->l20_codigo;?></h3>
        <b>Processos de compra vinculados</b>
      <td>
     </tr>
     <tr>
       <td>
        <fieldset>
            <legend><b>Processos de Compras</b></legend>
            <table cellpadding='0' width="100%" cellspacing='0' style='border:2px inset white'>
              <tr>
                <th class='table_header'>
                  <input type='checkbox' style='display:none' id='mtodos' >
                  <a onclick="js_marca($('mtodos'))">M<a>
                </th>
                <th class='table_header'>
                   Processo de Compras
                </th>
                <th class='table_header'>
                   Data
                </th>
                <th class='table_header'>
                   Usuário
                </th>
                <th class='table_header'>
                  Departamento 
                </th>
                <th class='table_header'>
                  Resumo PC 
                </th>
                <tbody style='background-color:white'>
                <?
                  $sTbody = '';
                  foreach ($oDados->aProcessos as $oProcesso) {

                     $sTbody .= "<tr>\n";
                     $sTbody .= "  <td class='linhagrid'>";
                     $sTbody .= "     <input type='checkbox' value='{$oProcesso->pc80_codproc}'";
                     $sTbody .= "            class='aProcessos' id='processo{$oProcesso->pc80_codproc}' checked>"; 
                     $sTbody .= "  </td>";
                     $sTbody .= "  <td class='linhagrid'>";
                     $sTbody .=      $oProcesso->pc80_codproc;
                     $sTbody .= "  </td>";
                     $sTbody .= "  <td class='linhagrid'>";
                     $sTbody .=      db_formatar($oProcesso->pc80_data,"d");
                     $sTbody .= "  </td>";
                     $sTbody .= "  <td class='linhagrid'>";
                     $sTbody .=     $oProcesso->login;
                     $sTbody .= "  </td>";
                     $sTbody .= "  <td class='linhagrid'>";
                     $sTbody .= "     {$oProcesso->coddepto} - {$oProcesso->descrdepto}";
                     $sTbody .= "  </td>";
                     $sTbody .= "  <td class='linhagrid'>";
                     $sTbody .=    substr($oProcesso->pc80_resumo,0,50);
                     $sTbody .= "  </td>";
                     $sTbody .= "</tr>";
                   
                  }
                  echo $sTbody;
                ?>
                </tbody>
            </table>
        </fieldset>
      </td>
    </tr>  
    <tr>
       <td style='text-align:center'>
          <input type='button' value='Processar' onclick='js_processar()'>
       </td>
    </tr>
  </table>
</form>
</center>
<body>
</html>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

<script>
function js_processar() {
   
  aItens     = js_getElementbyClass(frmProcessos,"aProcessos","checked==true");
  sProcessos = '';
  sVirgula   = '';
  for (i = 0; i < aItens.length; i++) {
    
    sProcessos += sVirgula+aItens[i].value;
    sVirgula    = ",";
  }
  sUrl          = 'lic4_geraaut002?l20_codigo='+$F('l20_codigo')+'&processos='+sProcessos;
  location.href = sUrl;
}
function js_marca(obj) {
 
  
  aItens   = js_getElementbyClass(frmProcessos,"aProcessos");
  for (var i = 0; i < aItens.length; i++) {
    if (obj.checked == true) {
      aItens[i].checked = true;
    } else {
      aItens[i].checked = false;
    }
  }
  if (obj.checked == true) {
    obj.checked = false;
  } else {
   obj.checked = true;
  }  
}
</script>