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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_tabdesc_classe.php");
require_once("classes/db_tabdescarretipo_classe.php");
require_once("classes/db_tabdesccadban_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$cltabdesc             = new cl_tabdesc;
$cltabdescarretipo     = new cl_tabdescarretipo;
$cltabdesccadban       = new cl_tabdesccadban;
$cltabdesc->k07_instit = db_getsession("DB_instit");
$db_opcao = 1;
$db_botao = true;

if (isset($incluir)) {


  $k07_valorf=str_replace(",",".",$k07_valorf);
  $k07_valorv=str_replace(",",".",$k07_valorv); 
  $k07_quamin=str_replace(",",".",$k07_quamin);
  $k07_percde=str_replace(",",".",$k07_percde);

  try {
  	
    db_inicio_transacao();
	  
    $cltabdesc->incluir(null);
    if($cltabdesc->erro_status == "0"){
    	
      throw new Exception($cltabdesc->erro_msg);
    }
    
    $codtabdesc = $cltabdesc->codsubrec;
	  if($k78_arretipo != ""){
	  	$cltabdescarretipo-> k78_tabdesc  = $codtabdesc;
	  	$cltabdescarretipo-> k78_arretipo = $k78_arretipo;
	  	$cltabdescarretipo->incluir(null);
	  	if($cltabdescarretipo->erro_status=="0"){
	  	  
        throw new Exception($cltabdescarretipo->erro_msg);
	    } 
	  }
	  
	  if ($k114_codban != "") {
      
	  	$cltabdesccadban->k114_tabdesc       = $codtabdesc; 
	  	$cltabdesccadban->k114_codban        = $k114_codban;
	  	$cltabdesccadban->incluir(null);
	  	if ($cltabdesccadban->erro_status == "0") {
	  		throw new Exception($cltabdesccadban->erro_msg);
	  	}
	  }
	  
    db_fim_transacao(false);
	  db_msgbox("Inclusão efetuada com sucesso!");
	  db_redireciona("cai1_tabdesc_abataxa002.php?opcao=1&liberaaba=true&chavepesquisa={$codtabdesc}&k07_descr={$k07_descr}");
	  
  } catch (Exception $oErro) {
  	
  	db_fim_transacao(true);
  	db_msgbox($oErro->getMessage);
  }  
  
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
	<?
	include("forms/db_frmtabdesc.php");
	?>
</body>
</html>