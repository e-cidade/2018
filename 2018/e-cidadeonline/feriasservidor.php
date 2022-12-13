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


$sSqlFeriasGozadas  = " select *
												  from cadferia
												 where r30_regist = {$iMatric}
												   and r30_anousu = ".db_anofolha()."
												   and r30_mesusu = ".db_mesfolha()."";

$rsFeriasGozadas   = db_query($sSqlFeriasGozadas);
$iNroFeriasGozadas = pg_num_rows($rsFeriasGozadas);												   



$sSqlFeriasaGozar  = " select * from (
                        select to_char(max(ultimo_periodo) + cast('1 day'  as interval ),'yyyy-mm-dd')  as periodo_aquisitivo_inicial,
                              to_char(max(ultimo_periodo) + cast('1 year' as interval ),'yyyy-mm-dd')  as periodo_aquisitivo_final
									  	   from ( select case  
											                   when r30_peraf is not null then r30_peraf  
											                   else rh01_admiss 
											                 end as ultimo_periodo 
											            from rhpessoal
											                 inner join rhpessoalmov  on rh02_regist = rh01_regist
											                                         and rh02_anousu = ".db_anofolha()."
                                                               and rh02_mesusu = ".db_mesfolha()."
                                       left  join rhpesrescisao on rh05_seqpes = rh02_seqpes										                                            
											                 left  join cadferia      on r30_regist  = rh02_regist  
											                                         and r30_anousu  = rh02_anousu 
											                                         and r30_mesusu  = rh02_mesusu
											                  left join rhregime      on rh30_codreg = rh02_codreg                    
											           where rh01_regist = {$iMatric}
											             and rh05_seqpes is null 
											             and  rh30_vinculo not in ('P','I'))  as x ) as y 
										where periodo_aquisitivo_inicial is not null 
										  and periodo_aquisitivo_final   is not null ";
											             
$rsFeriasaGozar   = db_query($sSqlFeriasaGozar);
$iNroFeriasaGozar = pg_num_rows($rsFeriasaGozar);


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
  
          <table  class="tableForm" width="600px;">
			      <tr>
			        <td class="tituloForm"  colspan="3">
			          <b>Férias Gozadas</b>
			        </td>
			      </tr>          
		      <?
		       
		        if ( $iNroFeriasGozadas > 0 ) {
		      
		          for ( $iInd=0; $iInd < $iNroFeriasGozadas; $iInd++ ) {
		
		            $oFeriasGozadas = db_utils::fieldsMemory($rsFeriasGozadas,$iInd);
		       
		      ?>
            <tr>
              <td class="subTituloForm" colspan="2">
                Período Aquisitivo : <?=db_formatar($oFeriasGozadas->r30_perai,'d')." à ".db_formatar($oFeriasGozadas->r30_peraf,'d')?>
              </td>
              <td class="subTituloForm">
                Abono : <?=$oFeriasGozadas->r30_abono?> 
              </td>
            </tr>
            
                      
            <tr>
              <td class="labelForm" align="center">Períodos de Gozo:</td>
              <td class="labelForm" align="center">Dias de Gozo:    </td>
              <td class="labelForm" align="center">Mês Pagamento    </td>              
            </tr>            
            
            
            <tr>
              <td class="dadosForm" align="center">
                <?=db_formatar($oFeriasGozadas->r30_per1i,'d')." à ".db_formatar($oFeriasGozadas->r30_per1f,'d')?>
              </td>
              <td class="dadosForm" align="center"><?=$oFeriasGozadas->r30_dias1?></td>
              <td class="dadosForm" align="center"><?=$oFeriasGozadas->r30_proc1?></td> 
            </tr>
            
            <? 
                if ( trim($oFeriasGozadas->r30_proc2) != '' ) { 
            ?>
            
            <tr>
              <td class="dadosForm" align="center">
                <?=db_formatar($oFeriasGozadas->r30_per2i,'d')." à ".db_formatar($oFeriasGozadas->r30_per2f,'d')?>
              </td>
              <td class="dadosForm" align="center"><?=$oFeriasGozadas->r30_dias2?></td>
              <td class="dadosForm" align="center"><?=$oFeriasGozadas->r30_proc2?></td> 
            </tr>
		         
            
  		      <?
                }
            ?>    
            <tr>
              <td>
                &nbsp;
              </td>
            </tr>                
            <?    
		          }
		        ?>
		         
		      <? 
		          
		        } else {
		      ?>      
            <tr>
              <td class="dadosForm"  colspan="2" align="center">      
                Nenhum Registro Encontrado
              </td>
            </tr>
        
		      <?
		        }
		      ?>  
			      <tr>
			        <td class="tituloForm"  colspan="3">
			          <b>Férias a Gozar</b>
			        </td>
			      </tr>          
          <?
           
            if ( $iNroFeriasaGozar > 0 ) {
          
              for ( $iInd=0; $iInd < $iNroFeriasaGozar; $iInd++ ) {
    
                $oFeriasaGozar = db_utils::fieldsMemory($rsFeriasaGozar,$iInd);

                if ( $oFeriasaGozar->periodo_aquisitivo_final < date('Y-m-d',db_getsession('DB_datausu'))) {
                	
          ?>
            <tr>
              <td class="labelForm">
                Período Aquisitivo:
              </td>
              <td class="dadosForm">
                <?=db_formatar($oFeriasaGozar->periodo_aquisitivo_inicial,'d')." à ".db_formatar($oFeriasaGozar->periodo_aquisitivo_final,'d')?>
              </td>
            </tr>
          
          <?
                  $lMonstraMsg = true; 
                } else if ( $iNroFeriasaGozar == 1  ) {
                	$lMonstraMsg = false;
          ?>      	
            <tr>
              <td class="dadosForm"  colspan="3" align="center">      
                Nenhum Registro Encontrado
              </td>
            </tr>            
           
          <?      	
                }
                 	
              }
              
              if ($lMonstraMsg) {
          ?>
            <tr>
              <td style="font-size: 11px;font-weight:bold;" colspan="3">
                *Períodos aquisitivos de férias ainda não gozadas estão sujeitos a avaliação pela Instituição
              </td>
            </tr>    
          <?
              }
          
              
            } else {
          ?>      
      
            <tr>
              <td class="dadosForm"  colspan="3" align="center">      
                Nenhum Registro Encontrado
              </td>
            </tr>
              
        
          <?
            }
          ?>  
    </table>
  </form>
</body>