<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("classes/db_vac_descarte_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);
$iDepartamento    = db_getsession("DB_coddepto");
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
<form name="form1" method="post" action="">
<?
if(!isset($db_opcao)){
  $db_opcao = 1;
}

if (isset($opcao)) {
  echo"<script>
         parent.location.href=\"vac4_descartar001.php?opcao=$opcao&vc19_i_codigo=$vc19_i_codigo\";
       </script>";
}

$oIframeAE        = new cl_iframe_alterar_excluir;
$oDaoVacDescarte  = new cl_vac_descarte;


  $aChavepri               = array('vc19_i_codigo'            => @$vc19_i_codigo,
                                   'vc19_i_vacina'            => @$vc19_i_vacina,
                                   'vc06_c_descr'             => @$vc06_c_descr,
                                   'vc19_i_matetoqueitemlote' => @$vc19_i_vacinalote,
                                   'vc19_n_quant'             => @$vc19_n_quant,
                                   'vc19_t_obs'               => @$vc19_t_obs
                                  );

  $oIframeAE->chavepri      = $aChavepri;
  $oIframeAE->sql           = '';
  $oIframeAE->legenda       = 'Descartes Realizados';
  $oIframeAE->campos        = 'vc19_i_codigo, vc06_c_descr, m77_lote, vc19_n_quant';
  $oIframeAE->msg_vazio     = 'Não foi encontrado nenhum registro.';
  $oIframeAE->textocabec    = 'darkblue';
  $oIframeAE->textocorpo    = 'black';
  $oIframeAE->fundocabec    = '#aacccc';
  $oIframeAE->fundocorpo    = '#ccddcc';
  $oIframeAE->formulario    = false;
  $oIframeAE->iframe_width  = '100%';
  $oIframeAE->iframe_height = '130';
  if (isset($vc19_i_vacina) && $vc19_i_vacina != "") {
  	
    $oIframeAE->sql         = $oDaoVacDescarte->sql_query2(null, 
                                                           '*', 
                                                           'vc19_i_codigo desc', 
                                                           " vc19_i_vacina=$vc19_i_vacina ".
                                                           "and m70_coddepto=$iDepartamento ");
    $oIframeAE->iframe_alterar_excluir($db_opcao);
    
  }
?>
</form>
</body>
</html>