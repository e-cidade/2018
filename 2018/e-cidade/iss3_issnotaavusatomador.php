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
require("libs/db_utils.php");
include("dbforms/db_funcoes.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_issnotaavulsatomador_classe.php");
include("classes/db_parissqn_classe.php");
include("classes/db_issnotaavulsaservico_classe.php");

$clissnotaavulsatomador = new cl_issnotaavulsatomador();
$get                    = db_utils::postmemory($_GET);
$rsTom                 = $clissnotaavulsatomador->sql_record($clissnotaavulsatomador->sql_query_tomador($get->q51_sequencial));
$oTom = db_utils::fieldsmemory($rsTom,0);
$q53_nome       = $oTom->z01_nome; 
$q54_inscr      = $oTom->q02_inscr;
$q61_numcgm     = $oTom->q61_numcgm;
$q53_cgccpf     = $oTom->z01_cgccpf;
$q53_endereco   = $oTom->z01_ender;
$q53_numero     = $oTom->z01_numero;
$q53_bairro     = $oTom->z01_bairro;
$q53_munic      = $oTom->z01_munic;
$q53_uf         = $oTom->z01_uf;
$q53_cep        = $oTom->z01_cep;
$q53_email      = $oTom->z01_email;
$q53_sequencial = $oTom->q53_sequencial;
$q53_fone       = $oTom->z01_telef;
$dtservico      = explode("-",$oTom->q53_dtservico);       
$q53_dtservico_dia = $dtservico[2];
$q53_dtservico_mes = $dtservico[1];
$q53_dtservico_ano = $dtservico[0];

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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<?
include("forms/db_frmissnotaavulsatomadorconsulta.php");
?>
</body>
</html>