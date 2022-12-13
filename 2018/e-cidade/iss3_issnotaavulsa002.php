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
include("classes/db_issnotaavulsa_classe.php");
include("classes/db_parissqn_classe.php");
include("classes/db_issnotaavulsaservico_classe.php");

$clissnotaavulsa = new cl_issnotaavulsa();
$get             = db_utils::postmemory($_GET);
$db_opcao        = 1;
$sSqlInfoTomador = null;
(string)$strWh   = null;
(string)$strAnd  = null;

if (isset($get->nota) && $get->nota != null){

  $strWh .= $strAnd .= " q51_numnota = ".$get->nota; 
}

if (isset($get->numcgm) && $get->numcgm != null){

  $strAnd  = $strWh != null?" and ":null;
  $strWh  .= $strAnd . "iss.q02_numcgm = ".$get->numcgm;

}

if (isset($get->inscr) && $get->inscr != null ){

  $strAnd  = $strWh != null?" and ":null;
  $strWh  .= $strAnd . "q51_inscr = ".$get->inscr;

}
if ((isset($get->dtemissini) && $get->dtemissini != null) and (isset($get->dtemissfim) && $get->dtemissfim == null )){

  if ($strWh == null){

    $sSqlInfoTomador  = " cgm.z01_nome as dl_Prestador,";

  }
  $strAnd  = $strWh != null?" and ":null;
  $dtEn    = explode("/",$get->dtemissini);
  $strWh  .= $strAnd . "q51_dtemiss = '".$dtEn[2].$dtEn[1].$dtEn[0]."'";

}else if ((isset($get->dtemissini) && $get->dtemissini == null) and isset($get->dtemissfim) && $get->dtemissfim != null){

  if ($strWh == null){

    $sSqlInfoTomador  = " cgm.z01_nome as dl_Prestador,";

  }
  $strAnd  = $strWh != null?" and ":null;
  $dtEn    = explode("/",$get->dtemissfim);
  $strWh  .= $strAnd . "q51_dtemiss = '".$dtEn[2].$dtEn[1].$dtEn[0]."'";

}else if ((isset($get->dtemissini) && $get->dtemissini != null) and (isset($get->dtemissfim) && $get->dtemissfim != null)){

  if ($strWh == null){

    $sSqlInfoTomador  = " cgm.z01_nome as dl_Prestador,";

  }
  $strAnd  = $strWh != null?" and ":null;
  $dtEnini = explode("/",$get->dtemissini);
  $dtEnfim = explode("/",$get->dtemissfim);
  $strWh  .= $strAnd . "q51_dtemiss between '".$dtEnini[2].$dtEnini[1].$dtEnini[0]."' and ";
  $strWh  .= "'".$dtEnfim[2].$dtEnfim[1].$dtEnfim[0]."'";

}
if ($strWh != ''){
  
   $strWh = " where ".$strWh;

}

$sSqlInfo  = "select q51_sequencial as db_q51_sequencial,";
$sSqlInfo .= "       q51_numnota,"; 
$sSqlInfo .= "       q51_dtemiss,";
$sSqlInfo .= $sSqlInfoTomador;
$sSqlInfo .= "       case when cgmtoma.z01_nome is not null ";
$sSqlInfo .= "            then cgmtoma.z01_nome ";
$sSqlInfo .= "            when cgminscr.z01_nome is not null ";
$sSqlInfo .= "            then cgminscr.z01_nome ";
$sSqlInfo .= "            end as dl_tomador, ";
$sSqlInfo .= "       case when cgmtoma.z01_cgccpf is not null ";
$sSqlInfo .= "            then cgmtoma.z01_cgccpf ";
$sSqlInfo .= "            when cgminscr.z01_cgccpf is not null ";
$sSqlInfo .= "            then cgminscr.z01_cgccpf ";
$sSqlInfo .= "            end as z01_cgccpf, ";
$sSqlInfo .= "      sum(q62_vlrtotal) as q62_vlrtotal,";
$sSqlInfo .= "      sum(q62_vlrdeducao) as q62_vlrdeducao,";
$sSqlInfo .= "      sum(q62_vlrissqn)    as q62_vlrissqn ";
$sSqlInfo .= "from issnotaavulsa";
$sSqlInfo .= "     inner join issbase  iss              on iss.q02_inscr   = q51_inscr";
$sSqlInfo .= "     inner join cgm                       on iss.q02_numcgm  = cgm.z01_numcgm";
$sSqlInfo .= "     inner join issnotaavulsatomador      on q51_sequencial  = q53_issnotaavulsa";
$sSqlInfo .= "     left  join issnotaavulsatomadorcgm   on q53_sequencial  = q61_issnotaavulsatomador";
$sSqlInfo .= "     left  join cgm cgmtoma               on q61_numcgm      = cgmtoma.z01_numcgm";
$sSqlInfo .= "     left  join issnotaavulsatomadorinscr on q53_sequencial  = q54_issnotaavulsatomador";
$sSqlInfo .= "     left  join issbase issb              on q54_inscr       = issb.q02_inscr";
$sSqlInfo .= "     left  join cgm cgminscr              on issb.q02_numcgm = cgminscr.z01_numcgm ";
$sSqlInfo .= "     left  join issnotaavulsaservico      on q51_sequencial  = q62_issnotaavulsa ";
$sSqlInfo .= "     left  join issnotaavulsacanc         on q51_sequencial  = q63_issnotaavulsa ";
$sSqlInfo .= $strWh;
$sSqlInfo .= " group by cgm.z01_nome,";
$sSqlInfo .= "          q54_inscr,";
$sSqlInfo .= "          cgm.z01_cgccpf,";
$sSqlInfo .= "          cgmtoma.z01_nome,";
$sSqlInfo .= "          cgminscr.z01_nome,";
$sSqlInfo .= "          cgmtoma.z01_cgccpf,";
$sSqlInfo .= "          cgminscr.z01_cgccpf,";
$sSqlInfo .= "          q51_dtemiss,";
$sSqlInfo .= "          q51_numnota,";
$sSqlInfo .= "          q51_sequencial";
$sSqlInfo .= " order by  q51_numnota ";            

?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_mostraNota(numNota){

 
  js_OpenJanelaIframe('top.corpo','db_iframe_pesquisanota','iss3_issnotaavulsa003.php?q51_sequencial='+numNota,"Pesquisa Notas Avulsa",true);

}
 </script> 
</head>
<body bgcolor=#cccccc leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<?

db_lovrot($sSqlInfo,15,"()","",'js_mostraNota|q51_sequencial');
?>
</center>
</body>
</html>