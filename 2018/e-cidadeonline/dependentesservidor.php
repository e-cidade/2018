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

$sSqlDependentes = " select * 
                       from rhdepend 
                      where rh31_regist = {$iMatric}
                      order by rh31_nome";


$rsDependentes   = db_query($sSqlDependentes);
$iNroDependentes = @pg_num_rows($rsDependentes);                      
                      
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
    <table  class="tableForm" width="60%">
		   <tr>
		     <td class="tituloForm" colspan="6">
            Dependentes
         </td>
	     </tr>          
      <?
       
        if ( $iNroDependentes > 0 ) {
      
          for ( $iInd=0; $iInd < $iNroDependentes; $iInd++ ) {

            $oDependentes = @db_utils::fieldsMemory($rsDependentes,$iInd);
       
      ?>
            <tr>
              <td class="labelForm" width="20%">
                Nome:
              </td>
              <td class="dadosForm" colspan="3">
                <?=$oDependentes->rh31_nome?>
              </td>
              <td class="labelForm">
                Parentesco:
              </td>
              <td class="dadosForm">
              <?

                 switch ($oDependentes->rh31_gparen) {
                   case 'C':
                    echo 'Conjuje';
                   break;
                   case 'F':
                    echo 'Filho';
                   break;
                   case 'P':
                    echo 'Pai';
                   break;
                   case 'M':
                    echo 'Mãe';
                   break;
                   case 'A':
                    echo 'Avó';
                   break;
                   case 'O':
                    echo 'Outros';
                   break;
                 }
                 
              ?>
              </td>              
            </tr>
            <tr>
              <td class="labelForm">
                Data de Nascimento:
              </td>
              <td class="dadosForm">
                <?=db_formatar($oDependentes->rh31_dtnasc,'d')?>
              </td>
            
            <?

                $sSqlTipoDependente  = " select *                                                 ";
                $sSqlTipoDependente .= "   from fc_tipo_dependente({$iMatric},                    ";
                $sSqlTipoDependente .= "                           {$oDependentes->rh31_codigo},  ";
                $sSqlTipoDependente .= "                           ".db_anofolha().",             ";
                $sSqlTipoDependente .= "                           ".db_mesfolha().",             ";
                $sSqlTipoDependente .= "                           ".db_getsession('DB_instit').")";

                $rsTipoDepend = db_query($sSqlTipoDependente);
                $oTipoDepend  = db_utils::fieldsMemory($rsTipoDepend,0);
                
                if ( $oTipoDepend->rlirrf == 'f' ) {
                	$sIRRF = 'Não';
                }	else {
                	$sIRRF = 'Sim';
                }
                
                if ( $oTipoDepend->rlsalariofamilia == 'f' ) {
                  $sSalarioFamilia = 'Não';
                } else {
                  $sSalarioFamilia = 'Sim';
                }                
            
            ?>
              <td class="labelForm">
                Salário Família:
              </td>
              <td class="dadosForm">
                <?=$sSalarioFamilia?>
              </td>
              <td class="labelForm">
                Dep. IRRF:
              </td>
              <td class="dadosForm">
                <?=$sIRRF?>
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