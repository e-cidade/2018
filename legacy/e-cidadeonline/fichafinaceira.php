<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBSeller Servicos de Informatica             
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


include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_cfpess_classe.php");

validaUsuarioLogado();

$oPost   = db_utils::postMemory($_POST);
$iMatric = $oPost->iMatric;
$sSigla  = $oPost->tipocalc;
$iAnoUsu = $oPost->anocalc;
$iMesUsu = $oPost->mescalc;
  

$sSqlDadosServidor  = " select *                                                          ";
$sSqlDadosServidor .= "   from rhpessoal                                                  ";
$sSqlDadosServidor .= "        inner join rhpessoalmov  on rh02_regist = rh01_regist      ";
$sSqlDadosServidor .= "                                and rh02_anousu = ".db_anofolha()."";
$sSqlDadosServidor .= "                                and rh02_mesusu = ".db_mesfolha()."";
$sSqlDadosServidor .= "        inner join rhregime      on rh30_codreg = rh02_codreg      ";
$sSqlDadosServidor .= "        inner join cgm           on z01_numcgm  = rh01_numcgm      ";
$sSqlDadosServidor .= "        inner join db_config     on rh01_instit = codigo           ";

$sSqlDadosServidor .= "  where rh01_regist = {$iMatric}                                   ";
                          
$rsDadosServidor = db_query($sSqlDadosServidor);
$oDadosServidor  = db_utils::fieldsMemory($rsDadosServidor,0);

switch ($sSigla) {
	case 'r14':
		$sTabela = 'gerfsal';
    $iTipoFolha = 1;
	break;
  case 'r22':
  	$sTabela = 'gerfadi';
  break;
  case 'r35':
  	$sTabela = 'gerfs13';
  break;
  case 'r20':
  	$sTabela = 'gerfres';
  break;    	
  case 'r48':
  	$sTabela = 'gerfcom';
    $iTipoFolha = 3;
  break;    	
  case 'supl':
    $iTipoFolha = 6;
  break;
}
$sDescrTipoFolha = $sDescricao;

?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css"               rel="stylesheet" type="text/css">
<link href="config/estilos.css"        rel="stylesheet" type="text/css">
<link href="config/portalservidor.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="scripts/scripts.js"></script>
<script language="JavaScript" src="scripts/db_script.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <?php mens_OnHelp(); ?> >
  <form name="form1" method="post" target="iframeFichaFinanceira">
    <table align="center" class="tableForm" width="60%">
      <tr>  
        <td>
          <table width="100%">
			      <tr>  
			        <td class="tituloForm" colspan="8">
                 DADOS PESSOAIS           
			        </td>
			      </tr>
            <tr>
              <td class="labelForm" width="120px">
                Instituição:
              </td>
              <td class="dadosForm" colspan="7">
                <?=$oDadosServidor->nomeinst?>
              </td>              
            </tr>            
            <tr>
              <td  class="labelForm">
                Matrícula:  
              </td>
              <td class="dadosForm" >
                <?=$iMatric ?>
              </td>
           
              <td class="labelForm">
                CPF:
              </td>
              <td class="dadosForm">
                <?=$oDadosServidor->z01_cgccpf?>
              </td>
              <td class="labelForm">
                RG:
              </td>
              <td class="dadosForm" colspan="3">
                <?=$oDadosServidor->z01_ident?>
              </td>                                          
            </tr>
            <tr>
              <td class="labelForm">
                Nome:
              </td>
              <td class="dadosForm" colspan="7">
                <?=$oDadosServidor->z01_nome?>              
              </td>              
            </tr>
            <tr>
             
              <td class="labelForm">
                Tipo de Folha:
              </td>
              <td class="dadosForm" colspan="3">
                <?php echo mb_strtoupper($sDescrTipoFolha);?>
              </td>              

              <td class="labelForm" width="120px">
                Vínculo:
              </td>
              <td class="dadosForm" colspan="2">
                <?=$oDadosServidor->rh30_descr?>
              </td>              

            </tr>            
          </table>  
        </td>
      </tr>   
      <tr>  
        <td>
          <table width="100%">
			      <tr>  
			        <td class="tituloForm">
			          RUBRICAS          
			        </td>
			      </tr>
            <tr>  
              <td>
                <table width="100%">
			            <thead>
				            <tr>
				              <th class="headerTableForm"> Rubrica       </th>
					            <th class="headerTableForm"> Descrição     </th>
					            <th class="headerTableForm"> Quantidade    </th>
			                <th class="headerTableForm" width='10%'>&nbsp;</th>
				              <th class="headerTableForm"> Proventos     </th>
				              <th class="headerTableForm"> Descontos     </th>
				            </tr>           
			            </thead> 
			            <tbody>
				            <?
                      $sCompetenciaFolha    = db_anofolha().db_mesfolha();
                      $sCompetenciaConsulta = $iAnoUsu.str_pad($iMesUsu, 2, 0, STR_PAD_LEFT);

			                if( $sCompetenciaConsulta < $sCompetenciaFolha ) {

				                $sSqlRubricas = " select {$sSigla}_rubric as rubric,
				                                         {$sSigla}_quant  as quant,
				                                         rh27_descr       as descr,
			                                           {$sSigla}_valor  as valor,
                                                 {$sSigla}_pd     as tipo,
				                                         case when {$sSigla}_pd = 1 then {$sSigla}_valor else 0 end as provento, 
				                                         case when {$sSigla}_pd = 2 then {$sSigla}_valor else 0 end as desconto
				                                    from rhpessoal 
									  								        inner join rhpessoalmov  on rh02_regist      = rh01_regist     
																		                                and rh02_anousu      = ".db_anofolha()."
																		                                and rh02_mesusu      = ".db_mesfolha()."	                                   
				                                    inner join {$sTabela}    on {$sSigla}_regist = rh01_regist 
																                                    and {$sSigla}_anousu = {$iAnoUsu}
																                                    and {$sSigla}_mesusu = {$iMesUsu}
																                                    and {$sSigla}_instit = rh02_instit
				                                    inner join rhrubricas    on rh27_rubric      = {$sSigla}_rubric
				                                                            and rh27_instit      = rh02_instit  
				                                    where rh01_regist = {$iMatric}
				                                    order by {$sSigla}_pd,
				                                             {$sSigla}_rubric";

                        /**
                         * Se o parâmetro da sessão "DB_COMPLEMENTAR" estiver ativado e a folha selecionada for salário, complementar ou suplementar, 
                         * busca os dados das rubricas da tabela rhhistoricocalculo.
                         */
                        if (cl_cfpess::verificarUtilizacaoEstruturaSuplementar() && ($sSigla == 'r48' || $sSigla == 'r14' || $sSigla == 'supl') ){

                          $sSqlRubricas  = "  select rh143_rubrica    as rubric,                                                          ";
                          $sSqlRubricas .= "        rh143_quantidade as quant,                                                            ";
                          $sSqlRubricas .= "        rh27_descr       as descr,                                                            ";
                          $sSqlRubricas .= "        sum(rh143_valor) as valor,                                                            ";
                          $sSqlRubricas .= "        rh143_tipoevento as tipo,                                                             ";
                          $sSqlRubricas .= "        case when (rh143_tipoevento) = 1 then sum(rh143_valor) else 0 end as provento,        ";
                          $sSqlRubricas .= "        case when (rh143_tipoevento) = 2 then sum(rh143_valor) else 0 end as desconto         ";
                          $sSqlRubricas .= "   from rhfolhapagamento                                                                      ";
                          $sSqlRubricas .= "        inner join rhhistoricocalculo on rh143_folhapagamento = rh141_sequencial              ";
                          $sSqlRubricas .= "        inner join rhrubricas         on  rh27_rubric = rh143_rubrica                         ";
                          $sSqlRubricas .= "                                     and rh27_instit = rh141_instit                           ";
                          $sSqlRubricas .= "  where rh141_sequencial = {$iSequencial}                                                     ";
                          $sSqlRubricas .= "    and rh143_regist = {$iMatric}                                                             ";

                          $sSqlRubricas .= "  group by rh143_rubrica, rh143_quantidade, rh27_descr, rh143_tipoevento                      ";
                          $sSqlRubricas .= "  order by rh143_tipoevento,                                                                  ";
                          $sSqlRubricas .= "           rh143_rubrica                                                                      ";
                        }

				                $rsRubricas   = db_query($sSqlRubricas);
				                $iNroRubricas = pg_num_rows($rsRubricas);

                      } else {
                        $iNroRubricas = 0;
                        db_msgbox("O período informado deve ser inferior a competência atual da folha.");
                      }
			
				               $nBasePrev = 0;
				               $nBaseIRRF = 0;
				               $nTotDesc  = 0;
				               $nTotProv  = 0;
				               $nSaldoDevedor = 0;
				               $nBaseIRRFFerias = 0;
				               
			                 for ( $iInd=0; $iInd < $iNroRubricas; $iInd++ ){
			                  
			                   $oRubricas = db_utils::fieldsMemory($rsRubricas,$iInd);	
			                 	
			                   if( $oRubricas->rubric == 'R981' || $oRubricas->rubric == 'R982' ){
								           $nBaseIRRF += $oRubricas->valor;
								         } else if ( $oRubricas->rubric == 'R992'){
								           $nBasePrev += $oRubricas->valor; 
								         } else if ( $oRubricas->rubric == 'R983'){
			                     $nBaseIRRFFerias += $oRubricas->valor; 
			                   } else if ( $oRubricas->rubric == 'R928'){
			                     $nSaldoDevedor += $oRubricas->valor; 
			                   }
			                   
								         
								         if ( $oRubricas->tipo == 3 ) {
								           continue;
								         }
								         
			                   echo " <tr>
									                <td class='dadosForm' align='center'>{$oRubricas->rubric}  </td>
									                <td class='dadosForm' align='left'  >{$oRubricas->descr}   </td>
									                <td class='dadosForm' align='right' >{$oRubricas->quant}   </td>
									                <td class='dadosForm' align='center'> &nbsp;                 </td>
									                <td class='dadosForm' align='right' >".db_formatar($oRubricas->provento, 'f')."</td>
									                <td class='dadosForm' align='right' >".db_formatar($oRubricas->desconto, 'f')."</td>
										            </tr>";
									                
			
								         $nTotProv += $oRubricas->provento;
								         $nTotDesc += $oRubricas->desconto;
									                
			                 }
				            ?>
			            </tbody>            
			          </table>
			        </td>
			      </tr>
			    </table>      
        </td>
      </tr>      
      <tr>  
        <td>
          <table width="100%">
			      <tr>  
			        <td class="tituloForm" colspan="4">RESUMO</td>
			      </tr>
            <tr>
              <td class="labelForm">               </td>            
              <td class="labelForm" align="center">Base p/ IRRF   </td>
              <td class="labelForm" align="center">Total Vantagens</td>
              <td class="labelForm" align="center">Saldo Devedor  </td>                            
            </tr>            
            <tr>
              <td class="dadosForm">
              </td>
              <td class="dadosForm" align="right">
                <?=db_formatar($nBaseIRRF,'f')?>
              </td>                 
              <td class="dadosForm" align="right">
                <?=db_formatar($nTotProv,'f')?>              
              </td>                 
              <td class="dadosForm" align="right">
                <?=db_formatar($nSaldoDevedor,'f')?>
              </td>                                                                            
            </tr>
            <tr>
              <td class="labelForm" align="center">Base Previdência  </td>            
              <td class="labelForm" align="center">Base p/IRRF Férias</td>
              <td class="labelForm" align="center">Total Descontos   </td>
              <td class="labelForm" align="center">Líquido a Receber </td>                            
            </tr>            
            <tr>
              <td class="dadosForm" align="right">
                <?=db_formatar($nBasePrev,'f')?>
              </td>
              <td class="dadosForm" align="right">
                <?=db_formatar($nBaseIRRFFerias,'f')?>
              </td>                 
              <td class="dadosForm" align="right">
                <?=db_formatar($nTotDesc,'f')?>
              </td>                 
              <td class="dadosForm" align="right">
                <?=db_formatar($nTotProv - $nTotDesc,'f')?>
              </td>                                                                            
            </tr>            
          </table>  
        </td>
      </tr>      
    </table>
  </form>
</body>
<script>
</script>
