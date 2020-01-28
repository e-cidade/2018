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
include("dbforms/db_classesgenericas.php");
require_once("libs/db_utils.php");

$lFormNovo          = false;
$sInicioDepreciacao = null;
$oDaoCfPatri        = db_utils::getDao("cfpatriinstituicao");
$sSqlPatri          = $oDaoCfPatri->sql_query_file(null, 
                                                    "t59_dataimplanatacaodepreciacao", 
                                                    null, "t59_instituicao = ".db_getsession("DB_instit")
                                                  );
$rsPatri            = $oDaoCfPatri->sql_record($sSqlPatri);
if ($oDaoCfPatri->numrows > 0) {
  $sInicioDepreciacao = db_utils::fieldsMemory($rsPatri, 0)->t59_dataimplanatacaodepreciacao;
}
if (!empty($sInicioDepreciacao)) {
  $lFormNovo      = true;
}
$clcriaabas     = new cl_criaabas;
$db_opcao       = 1;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="790" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
     <?
	   $clcriaabas->identifica = array("bens"        => "Cadastro de bens",
	                                   "bensimoveis" => "Dados do imóvel",
	                                   "bensmater"   => "Dados do material"
	                                   );
	   if ($lFormNovo){
	     $clcriaabas->src = array("bens"=>"pat1_bensnovo005.php");
	   } else {
	     $clcriaabas->src = array("bens"=>"pat1_bens005.php");
	   } 
     $clcriaabas->title      =  array("bens"        => "Cadastrar bens",
                                      "bensimoveis" => "Ativar bem como imóvel",
                                      "bensmater"   => "Ativar bem como material"
                                      );
     $clcriaabas->sizecampo  = array("bens"=>"20","bensimoveis"=>"20","bensmater"=>"20");
	   $clcriaabas->disabled   =  array("bensimoveis"=>"true","bensmater"=>"true"); 
	   $clcriaabas->cria_abas(); 
       ?> 
       </td>
    </tr>
  </table>
  <form name="form1">
  </form>
      <? 
	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
      ?>
  </body>
  </html>