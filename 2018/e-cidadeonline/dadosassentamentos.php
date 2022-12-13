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

session_start();

include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("libs/db_utils.php");

validaUsuarioLogado();

$aRetorno = array();

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]),$aRetorno);

$iMatric  = $aRetorno['iMatric'];

	 	
if ( isset($aRetorno['averba']) ) {
 $sWhereAssenta = " and h12_reltot != 0 ";
 $sTituloTela   = 'Averbação de Tempo'; 
} else {
 $sWhereAssenta = " and h12_reltot = 0  ";
 $sTituloTela   = 'Assentamentos';
}

$sSqlAssenta  = " select *                                                               "; 
$sSqlAssenta .= "   from assenta                                                         "; 
$sSqlAssenta .= "        inner join tipoasse on tipoasse.h12_codigo = assenta.h16_assent ";  
$sSqlAssenta .= "  where h16_regist = {$iMatric}                                         ";
$sSqlAssenta .= "  {$sWhereAssenta}                                                      ";
$sSqlAssenta .= "  order by h16_dtconc                                                   ";

$rsAssenta    = db_query($sSqlAssenta);
$iNroAssenta  = pg_num_rows($rsAssenta);

?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="config/estilos.css"        rel="stylesheet" type="text/css">
<link href="config/portalservidor.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="scripts/scripts.js"></script>
<script language="JavaScript" src="scripts/db_script.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <?mens_OnHelp()?>>
  <form name="form1" method="post" action="">
    <table  class="tableForm" width="70%">
       <tr>
         <td class="tituloForm" colspan="6">
            <?=$sTituloTela?>
         </td>
       </tr>
      <?
       
        if ( $iNroAssenta > 0 ) {
      
          for ( $iInd=0; $iInd < $iNroAssenta; $iInd++ ) {

            $oAssenta = db_utils::fieldsMemory($rsAssenta,$iInd);
       
      ?>
            <tr>
              <td class="labelForm" width="10%">
                Descrição:
              </td>
              <td class="dadosForm" colspan="5">
                <?=$oAssenta->h12_descr?>
              </td>
            </tr>
            <tr>
              <td class="labelForm">
                Data Inicial:
              </td>
              <td class="dadosForm">
                <?=db_formatar($oAssenta->h16_dtconc,'d')?>
              </td>
              <td class="labelForm" width="10%">
                Data Final:
              </td>
              <td class="dadosForm">
                <?
                  if ( $oAssenta->h16_dtterm != '' ) {
                   echo  db_formatar($oAssenta->h16_dtterm,'d');
                  } else {
                   echo "&nbsp;";
                  }
                ?>              
              </td>       
              <td class="labelForm"  width="10%">
                Quantidade:
              </td>
              <td class="dadosForm">
                <?=$oAssenta->h16_quant?>              
              </td>                     
            </tr> 
            <tr>
              <td class="labelForm">
                Nº do Ato:
              </td>
              <td class="dadosForm">
                <?=$oAssenta->h16_nrport?>              
              </td>
              <td class="labelForm">
                Tipo:
              </td>
              <td class="dadosForm" colspan="3">
                <?=$oAssenta->h16_atofic?>              
              </td>       
            </tr>
            <tr>  
              <td class="labelForm">
                Histórico:
              </td>
              <td class="dadosForm" colspan="5">
                <?=$oAssenta->h16_histor." ".$oAssenta->h16_hist2?>              
              </td>                     
            </tr>
            <tr>
              <td class="tituloForm" colspan="6">
              </td>
            </tr>

      <?

          }
          
        } else {
      ?>      
      
      <tr style="font-size:12px;" align="center">
        <td>
          <b>Nenhum Registro Encontrado</b>
        </td>
      </tr>      
        
      <?
        }
    
      ?>  

    </table>
  </form>
</body>