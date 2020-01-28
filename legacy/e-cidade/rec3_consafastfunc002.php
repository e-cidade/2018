<?php
/**
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$classenta = new cl_assenta;

$oGet = db_utils::postMemory($_GET);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<!--<form name="form1">-->
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
   <tr>
     <td colspan="2" align = "center"> 
       <input  name="emite2" id="emite2" type="button" value="Imprimir" onclick="js_emite();" >
     </td>
   </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $dbwhere = "";
      
      $repassa = array();
      if(isset($codMatri) && trim($codMatri) != ""){

        $repassa["codMatri"] = $codMatri;
	      $dbwhere             = " h16_regist = ".$codMatri;
      }
      
      if(isset($codAssen) && trim($codAssen) != ""){

        $repassa["codAssen"]  = $codAssen;
	      $dbwhere             .= " and h16_assent = ".$codAssen;
      }
      
      if(isset($dataIni) && trim($dataIni) != ""){

        $repassa["dataIni"] = $dataIni;
        
        if(isset($dataFim) && trim($dataFim) != "") {

          $repassa["dataFim"]  = $dataFim;
          $dbwhere            .= " and h16_dtconc between '".$dataIni."' and '".$dataFim."' ";
        } else {
	        $dbwhere.= " and h16_dtconc >= '".$dataIni."' ";
	      }
      }

      if(!empty($dbwhere)) {
        $dbwhere .= " and h16_codigo in (select distinct rh193_assentamento_funcional from assentamentofuncional)";
      }

      $sql = $classenta->sql_query_tipo(null,"h12_assent, h12_descr, h16_dtconc, h16_dtterm, h16_quant, h16_nrport, h16_anoato, h16_atofic, h16_histor","h16_dtconc desc ",$dbwhere);
      db_lovrot($sql,20,"()","","","","NoMe",$repassa);
      ?>
     </td>
   </tr>
</table>
<!--</form>-->
</body>
</html>
<script>
function js_emite(){

  var iMatricula = '<?=$codMatri?>';
  var iCodAssen  = '<?=$codAssen?>';
  var sDataIni   = '<?=$oGet->dataIni?>';
  var sDataFim   = '<? isset($oGet->dataFim) ? $oGet->dataFim : '' ?>';

  qry  = 'codMatri='+iMatricula;
  qry += '&codAssen='+iCodAssen;

  if ( sDataIni != '' ) {
    qry += '&dataIni='+sDataIni;
  }

  if ( sDataFim != '' ) {
    qry += '&dataFim='+sDataFim;
  }

  jan = window.open('rec2_consafastfunc002.php?'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>