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
require_once("dbforms/db_funcoes.php");
require_once("classes/db_tabrecjm_classe.php");
require_once("dbforms/db_classesgenericas.php");

$clcriaabas     = new cl_criaabas;
$db_opcao       = 1 ;
$cltabrecjm     = new cl_tabrecjm();
$instit         = db_getsession("DB_instit");

if ( isset($incluir) ) {

  db_postmemory($HTTP_POST_VARS);  

  pg_exec("BEGIN");
  $cltabrecjm->k02_desjm  = 'false';
  $cltabrecjm->k02_instit = $instit;
  $cltabrecjm->incluir($k02_codjm);
  pg_exec("COMMIT");
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
<body bgcolor=#CCCCCC leftmargin="0" style="margin-top:18px;!important;" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1;">

  <?php
    $clcriaabas->identifica = array("detalhes" => "Detalhes", "multas" => "Multas"); 
    $clcriaabas->src        = array("detalhes" => "cai1_recejm004.php");
    $clcriaabas->disabled   = array("multas"   => "true"); 
    $clcriaabas->cria_abas(); 
    
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>

</body>
</html>