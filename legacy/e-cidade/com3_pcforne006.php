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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_pctipodoccertif_classe.php");
include("classes/db_pcforne_classe.php");
include("classes/db_pcfornecertif_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clpctipodoccertif = new cl_pctipodoccertif;
$clpcforne = new cl_pcforne;
$clpcfornecertif = new cl_pcfornecertif;
$clpctipodoccertif->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc70_descr");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("pc60_numcgm");
$clrotulo->label("pc74_solicitante");
$clrotulo->label("pc74_codigo");
$clrotulo->label("pc74_pctipocertif");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
.bgtd {
  background-color: #FFFFFF;
  color: #000000;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  >
  <center>
  <fieldset style="width: 600px;">
  <legend style="font-weight: bold;">&nbsp;Certificados&nbsp;</legend>
    <br />
    <?
      $sSqlCertificado  = "select * from (select pc74_pctipocertif, max(pc74_codigo) as codigo 
                           from pcfornecertif  where pc74_pcforne = $pc60_numcgm group by pc74_pctipocertif) as x 
                           inner join pcfornecertif on pcfornecertif.pc74_codigo = x.codigo 
                           inner join pctipocertif on pctipocertif.pc70_codigo = pcfornecertif.pc74_pctipocertif";
                           
      $result_certif = pg_exec($sSqlCertificado);
      $numrows       = pg_numrows($result_certif);
      if ($numrows == 0) {
        echo "<h2 align=\"center\">Este fornecedor não possui certificado!</h2>";
      }
      
      /**
        * Caso haja certificado, os mesmo serão mostrados abaixo
        */
        for($w = 0; $w < $numrows; $w++) {
          db_fieldsmemory($result_certif, $w);
          // Link para ser mostrado no iframe
          $sLinkFrame  = "com3_pcforne007.php?pc60_numcgm={$pc60_numcgm}";
          $sLinkFrame .= "&pc74_codigo={$pc74_codigo}";
          $sLinkFrame .= "&pc74_pctipocertif={$pc74_pctipocertif}";
      
    ?>
      <table width="650" border="0">
        <tr>
          <td title="<?=@$Tpc74_codigo?>" width="170"><?=$Lpc74_codigo?></td>
          <td class="bgtd" width="80"><?=$pc74_codigo?></td>
          <td title="<?=@$Tpc74_solicitante?>"><?=$Lpc74_solicitante?></td>
          <td class="bgtd" width="570"><?=$pc74_solicitante?></td>
        </tr>
        <tr>
          <td title="<?=@$Tpc74_pctipocertif?>"><b><?=@$Lpc74_pctipocertif?></b></td>
          <td class="bgtd" colspan="3"><?=@$pc74_pctipocertif?> - <?=@$pc70_descr?></td>
        </tr>
      </table>
   
            <iframe id          = "documentos"
                    frameborder = "0"
                    name        = "documentos"
                    leftmargin  = "0"
                    topmargin   = "0"
                    src         = "<?=$sLinkFrame;?>"
                    height      = "200"
                    width       = "600">
            </iframe> 
        <?}?>
  </fieldset>
  </center>
</body>
</html>