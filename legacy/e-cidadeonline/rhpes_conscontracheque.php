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
include("dbforms/db_funcoes.php");

$aRetorno = array();
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]),$aRetorno);

$numMatricula  = $aRetorno['matric'];
$codSeq        = $aRetorno['cod'];      
$sTipoFolha    = '';
/*    
$sqlRhEmiteContraCheque = " select rh85_regist,
                                   rh85_anousu,
                                   rh85_mesusu
                              from rhemitecontracheque 
                             where rh85_regist     = '{$numMatricula}' 
                               and rh85_sequencial = '{$codSeq}'";
*/

$sqlRhEmiteContraCheque = "select rh85_regist,
													        rh37_funcao,
													        rh37_descr,
													        rh85_anousu,
													        rh85_mesusu  
													  from rhpessoal 
													     inner join rhpessoalmov        on rh01_regist = rh02_regist 
													                                   and rh02_instit = rh01_instit 
													     inner join rhfuncao            on rh02_funcao = rh37_funcao 
													                                   and rh02_instit = rh37_instit 
													     inner join rhemitecontracheque on rh85_regist = rh01_regist 
													                                   and rh85_anousu=rh02_anousu 
													                                   and rh85_mesusu = rh02_mesusu 
													  where rh85_sequencial = '{$codSeq}'
													    and rh85_regist     = '{$numMatricula}'  
													  order by rh02_anousu,rh02_mesusu ";		


//echo $sqlRhEmiteContraCheque; die();

$rsRhEmiteContraCheque  = db_query($sqlRhEmiteContraCheque);
$iRhEmiteContraCheque   = pg_num_rows($rsRhEmiteContraCheque);

    if($iRhEmiteContraCheque > 0){
      $oRhEmiteContraCheque = db_utils::fieldsMemory($rsRhEmiteContraCheque,0);
      $erro = false;   
    } else {
    	$erro = true;
    	db_redireciona('rhpes_autcontracheq.php');
    }

if ($iRhEmiteContraCheque > 0) {
      
    $RhEmiteContraChequeAnoFolha   = $oRhEmiteContraCheque->rh85_anousu;
    $RhEmiteContraChequeMesFolha   = $oRhEmiteContraCheque->rh85_mesusu;
    $iNumMatric = $oRhEmiteContraCheque->rh85_regist;
    
    $sqlRhPessoalFolha = " select distinct rh01_regist,
                                           rh02_instit,
                                           nomeinst,
                                           z01_nome,
                                           rh37_descr,
                                           rh02_seqpes,
                                           r70_estrut,
                                           rh02_salari,
                                           r70_descr,
                                           rh85_sigla,
                                           rh85_anousu,
                                           rh85_mesusu,
                                           rh85_codautent
                                      from rhpessoal 
                                           inner join cgm          on cgm.z01_numcgm       = rhpessoal.rh01_numcgm   
                                           inner join rhfuncao     on rhfuncao.rh37_funcao = rh01_funcao 
                                           inner join rhemitecontracheque on rh01_regist   = rh85_regist
                                           inner join rhpessoalmov on rh02_regist = rhpessoal.rh01_regist
                                                                  and rh02_anousu = {$RhEmiteContraChequeAnoFolha}
                                                                  and rh02_mesusu = {$RhEmiteContraChequeMesFolha}
                                           left  join rhlota       on r70_codigo  = rh02_lota
                                                                  and r70_instit  = rh02_instit    
                                           inner join db_config    on rh02_instit = codigo                         
                                     where rh01_regist = {$iNumMatric} and rh85_sequencial = {$codSeq} ";
                            
     //echo $sqlRhPessoalFolha; die();
     $rsRhPessoalFolha  = db_query($sqlRhPessoalFolha);
     $iRhPessoalFolha   = pg_num_rows($rsRhPessoalFolha);

     if($iRhPessoalFolha > 0){
        $oRhPessoalFolha = db_utils::fieldsMemory($rsRhPessoalFolha,0);
        $erro = false;   
     } else {
     	  $erro = true;
     	  db_redireciona('rhpes_autcontracheq.php');
     }

       
     switch ( $oRhPessoalFolha->rh85_sigla ) {
     	 
     	case 'r14':
     	 $sTabela    = 'gerfsal';
     	 $sSigla     = 'r14';
     	 $sTipoFolha = 'SALÁRIO';
     	break;

       case 'r31';
        $sTabela    = 'gerffer';
     	$sSigla     = 'r31';
        $sTipoFolha = 'FÉRIAS';
     	 
       case 'r48':
        $sTabela    = 'gerfcom';
        $sSigla     = 'r48';
        $sTipoFolha = 'COMPLEMENTAR';
       break;      	 
     	 
       case 'r20':
        $sTabela    = 'gerfres';
        $sSigla     = 'r20';
        $sTipoFolha = 'RESCISÃO';
       break; 

       case 'r22':
        $sTabela    = 'gerfadi';
        $sSigla     = 'r22';
        $sTipoFolha = 'ADIANTAMENTO';
       break;        
       
       case 'r35':
        $sTabela    = 'gerfs13';
        $sSigla     = 'r35';
        $sTipoFolha = '13o. SALÁRIO';
       break; 

       case 'r53':
        $sTabela    = 'gerffx';
        $sSigla     = 'r53';
        $sTipoFolha = 'FIXO';
       break;        

       case 'r60':
        $sTabela    = 'previden';
        $sSigla     = 'r60';
        $sTipoFolha = 'AJUSTE DA PREVIDÊNCIA';
       break;

       case 'r61':
        $sTabela    = 'ajusteir';
        $sSigla     = 'r61';
        $sTipoFolha = 'AJUSTE DO IRRF';
       break;                      
     }
     
     $sSqlValorContraCheque = "select sum(provento) as provento,
                                      sum(desconto) as desconto 
															  from ( select case when {$sSigla}_pd = 1 then {$sSigla}_valor else 0 end as provento, 
															                case when {$sSigla}_pd = 2 then {$sSigla}_valor else 0 end as desconto  
															           from rhemitecontracheque 
															                inner join {$sTabela} on {$sSigla}_regist = rh85_regist 
																                                   and {$sSigla}_anousu = rh85_anousu 
																                                   and {$sSigla}_mesusu = rh85_mesusu 
															          where rh85_regist = {$iNumMatric} 
															            and rh85_anousu = {$oRhPessoalFolha->rh85_anousu} 
															            and rh85_mesusu = {$oRhPessoalFolha->rh85_mesusu} 
															            and {$sSigla}_rubric < 'R950' 
                                          and rh85_sequencial = {$codSeq}) as x";
     
		 $rsValorContraCheque   = db_query($sSqlValorContraCheque);
		 $iNroValorContraCheque = pg_num_rows($rsValorContraCheque); 
     
    															            
		 if ( $iNroValorContraCheque > 0 ) {
		 	 $oValorContraCheque = db_utils::fieldsMemory($rsValorContraCheque,0);
			 $nValorLiquido = $oValorContraCheque->provento - $oValorContraCheque->desconto;
		 } else {
		 	 db_redireciona('rhpes_autcontracheq.php');
		 }

		 
		 
    
} else {
	db_redireciona('rhpes_autcontracheq.php');
}
?>
<html>
  <head>
    <title><?=$w01_titulo?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="config/estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" src="scripts/db_script.js"></script>
  </head>
  <body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" >
   <br />
    <br />
    <form method="post" action="">
      <table align="center" border="0" cellpadding="3" cellspacing="3" width="80%" class="texto" style="border: 1px solid">
        <tr>
          <td align="center" width="100%" style="border: 1px solid">
            <b>** AUTENTICAÇÃO CONTRA CHEQUE / FOLHA DE PAGAMENTO **</b></td>
        </tr>
        <tr>
          <td width="100%"><b>NOME DA INSTITUIÇÃO:</b>&nbsp;&nbsp;<?=$oRhPessoalFolha->nomeinst;?></td>
        </tr>        
      </table>
      <table align="center" border="0" cellpadding="4" cellspacing="4" width="80%" class="texto" style="border: 1px solid">
        <tr>
          <td align="left" width="10%"><b>MATRICULA:</b></td>
          <td align="left" width="13%">&nbsp;
             <font color="<?=$w01_corfontesite?>"><?=$oRhPessoalFolha->rh01_regist;?></font>
          </td>
          <td align="left" width="2%" >&nbsp;</td>
          <td align="left" width="14%"><b>NOME:</b></td>
          <td align="left" width="25%">&nbsp;
             <font color="<?=$w01_corfontesite?>"><?=$oRhPessoalFolha->z01_nome;?></font>
         </td>
        </tr>        
        <tr>
          <td align="left" width="10%"><b>REF.AO MÊS:</b></td>
          <td align="left" width="13%">&nbsp;
             <font color="<?=$w01_corfontesite?>"><?=$RhEmiteContraChequeMesFolha."/".$RhEmiteContraChequeAnoFolha;?></font>
          </td>
          <td align="left" width="2%" >&nbsp;</td>
          <td align="left" width="14%"><b>TOTAL DOS VENCIMENTOS:</b></td>
          <td align="left" width="25%">&nbsp;
             <font color="<?=$w01_corfontesite?>"><?=$oValorContraCheque->provento;?></font>
         </td>
        </tr>
        <tr>
          <td align="left" width="10%"><b>LIQUIDO A RECEBER:</b></td>
          <td align="left" width="13%">&nbsp;
             <font color="<?=$w01_corfontesite?>"><?=db_formatar($nValorLiquido,'f')?></font>
          </td>
          <td align="left" width="2%" >&nbsp;</td>
          <td align="left" width="14%"><b>TOTAL DOS DESCONTOS:</b></td>
          <td align="left" width="25%">&nbsp;
             <font color="<?=$w01_corfontesite?>"><?=$oValorContraCheque->desconto;?></font>
         </td>
        </tr>   
        <tr>
          <td align="left" width="10%">&nbsp;</td>
          <td align="left" width="20%">&nbsp;</td>
          <td align="left" width="2%" >&nbsp;</td>
        </tr>   
        <tr>
          <td align="left" width="10%"><b>FUNÇÃO:</b></td>
          <td align="left" width="23%">&nbsp;
             <font color="<?=$w01_corfontesite?>"><?=$oRhEmiteContraCheque->rh37_descr;?></font>
          </td>
          <td align="left" width="2%" >&nbsp;</td>
          <td align="left" width="14%"><b>TIPO DE FOLHA:</b></td>
          <td align="left" width="25%">&nbsp;
             <font color="<?=$w01_corfontesite?>"><?=$sTipoFolha;?></font>
         </td>          
          <td align="left" width="2%">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" width="10%"><b>LOTAÇÃO:</b></td>
          <td align="left" width="23%">&nbsp;
             <font color="<?=$w01_corfontesite?>"><?=$oRhPessoalFolha->r70_estrut."-".$oRhPessoalFolha->r70_descr;?></font>
          </td>
          <td align="left" width="2%">&nbsp;</td>
        </tr>                                  
        <tr>
          <td align="left" width="10%"><b>AUTENTICAÇÃO:</b></td>
          <td align="left" width="23%">&nbsp;
             <font color="<?=$w01_corfontesite?>"><?=$oRhPessoalFolha->rh85_codautent;?></font>
          </td>
          <td align="left" width="2%">&nbsp;</td>
        </tr>        
      </table>      
    </form>
  </body>
</html>
