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
include("libs/db_utils.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_pessoal_classe.php");
include("classes/db_pontofx_classe.php");
include("classes/db_pontofs_classe.php");
include("classes/db_pontofa_classe.php");
include("classes/db_pontofe_classe.php");
include("classes/db_pontofr_classe.php");
include("classes/db_pontof13_classe.php");
include("classes/db_pontocom_classe.php");
include("classes/db_rhrubricas_classe.php");
include("classes/db_lotacao_classe.php");
include("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clrhpessoal  = new cl_rhpessoal;
$clpessoal    = new cl_pessoal;
$clpontofx    = new cl_pontofx;
$clpontofs    = new cl_pontofs;
$clpontofa    = new cl_pontofa;
$clpontofe    = new cl_pontofe;
$clpontofr    = new cl_pontofr;
$clpontof13   = new cl_pontof13;
$clpontocom   = new cl_pontocom;
$clrhrubricas = new cl_rhrubricas;
$cllotacao    = new cl_lotacao;

$db_opcao     = 1;
$db_botao     = true;

$ponto        = $oGet->ponto;
$r90_anousu   = db_anofolha();
$r90_mesusu   = db_mesfolha();  

if( isset($oGet->r90_regist) ) {

  $sWhereRegistro   = "     rh01_regist = {$oGet->r90_regist} "; 
  $sWhereRegistro  .= " and rh02_anousu = {$r90_anousu}";
  $sWhereRegistro  .= " and rh02_mesusu = {$r90_mesusu}";
  
  $sCamposRegistro  = "rh01_regist as r90_regist,      ";
	$sCamposRegistro .= "rh01_admiss as data_de_admissao,";
	$sCamposRegistro .= "z01_nome,                       ";
	$sCamposRegistro .= "rh02_lota as r90_lotac,         ";
	$sCamposRegistro .= "r70_descr                       ";
  
  $sSqlRegistro = $clrhpessoal->sql_query_cgm(null,$sCamposRegistro,null,$sWhereRegistro);
  $rsRegistro   = $clrhpessoal->sql_record($sSqlRegistro);
  
  if($clrhpessoal->numrows > 0){
    db_fieldsmemory($rsRegistro,0);
  }

}



?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/json2.js"></script>
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="" >
	<?
   	include("forms/db_frmrhpesponto.php");
	?>
</body>
</html>