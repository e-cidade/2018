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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_utils.php");

$oGet               = db_utils::postMemory($_GET);
$iCodigoEmpNotaItem = "";
if (isset($oGet->iCodigoEmpNotaItem) && !empty($oGet->iCodigoEmpNotaItem)) {
  
  $iCodigoEmpNotaItem = "?iCodigoEmpNotaItem={$oGet->iCodigoEmpNotaItem}";
}

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
<?
  db_app::load("scripts.js, prototype.js, strings.js, DBToogle.widget.js, dbmessageBoard.widget.js, widgets/messageboard.widget.js");
  db_app::load("estilos.css, grid.style.css, classes/DBViewNotasPendentes.classe.js, widgets/windowAux.widget.js, datagrid.widget.js");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<div style="margin-top: 18px;"></div>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
     <?
  	   $clcriaabas->identifica = array("bens"        => "Cadastro de bens",
  	                                   "bensimoveis" => "Dados do imóvel",
  	                                   "bensmater"=>"Dados do material"
  	                                  );
  	   
  	   $clcriaabas->sizecampo  = array("bens"        => "20",
  	                                   "bensimoveis" => "20",
  	                                   "bensmater"   => "20");
  	   
  	   $clcriaabas->title      =  array("bens"        => "Cadastrar bens",
  	                                    "bensimoveis" => "Ativar bem como imóvel",
  	                                    "bensmater"   => "Ativar bem como material"
  	                                   );
   	   
       if ($lFormNovo) {
         $clcriaabas->src = array("bens"=>"pat1_bensnovo004.php".$iCodigoEmpNotaItem);
//          $clcriaabas->src = array("bens"=>"pat1_bens004.php");
       } else {
  	     $clcriaabas->src = array("bens"=>"pat1_bens004.php");
       }
  	   $clcriaabas->disabled   =  array("bensimoveis"=>"true","bensmater"=>"true","bensbaix"=>"true"); 
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