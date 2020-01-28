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
include("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");

$oGet       = db_utils::postMemory($_GET);
$clcriaabas = new cl_criaabas;

if ( $oGet->iDepto == db_getsession('DB_coddepto')) {
	$lDeptoSessao = true; 
} else {
	$lDeptoSessao = false;
}

$lErro = false;

if ( $lDeptoSessao ) {
	if ( isset($oGet->lRecebido) && $oGet->lRecebido == 'false' ) {
		
	  require_once('model/processoOuvidoria.model.php');
	  $oProcessoOuvidoria = new processoOuvidoria();

	  require_once('classes/db_db_depart_classe.php');
    $cldb_depart = new cl_db_depart();
	  
    $rsDescrDepart   = $cldb_depart->sql_record($cldb_depart->sql_query_file(db_getsession('DB_coddepto')));
	  $oDepart         = db_utils::fieldsMemory($rsDescrDepart,0);
	  $sMsgRecebimento = "Recebimento depto. ".$oDepart->coddepto." - ".$oDepart->descrdepto; 
	  
	  db_inicio_transacao();
		  
	  try {
	  	$oProcessoOuvidoria->incluirRecebimento($oGet->iCodProcesso,$sMsgRecebimento);
	  } catch (Exception $eException) {
	  	$lErro = true;
	  }
	  
	  db_fim_transacao($lErro);
		  
	}
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table valign="top" marginwidth="0" width="790" border="0" cellspacing="0" cellpadding="0">
   <tr>
       <td>
        <?
          $clcriaabas->identifica = array("listadespacho"=>"Despachos do Processo",
                                          "despachoatual"=>"Despacho Atual");
          
          $clcriaabas->title 	    = array("listadespacho"=>"Despachos do Processo",
                                          "despachoatual"=>"Despacho Atual");
          if ( $lDeptoSessao ) {
          	$sQuerySessao = 'true';
          } else {
          	$sQuerySessao = 'false';
          }
          
          $sQuery  = "?iCodProcesso={$oGet->iCodProcesso}";
          $sQuery .= "&lDeptoSessao={$sQuerySessao}";
          
          $clcriaabas->src 		    = array("listadespacho"=>"ouv1_detalhesdespacho002.php{$sQuery}",
                                          "despachoatual"=>"ouv1_detalhesdespacho003.php{$sQuery}");
          
          
	        $clcriaabas->cria_abas();
        ?>
       </td>
   </tr>
   <tr>
  </tr>
</table>
</body>
<script>

  document.formaba.listadespacho.size = 25;
  document.formaba.despachoatual.size = 25;
  
  function js_fechar() {
    parent.js_pesquisar();
    parent.db_iframe_detalhes.hide();  
  }
  
  <?
    if ( $lDeptoSessao && isset($oGet->lRecebido) && $oGet->lRecebido == 'false' ) {
	    if (!$lErro) {
	    	echo "parent.js_pesquisar();";
	    }
    }
  ?>
  
</script>
</html>