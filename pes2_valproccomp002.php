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

require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");

$oGet = db_utils::postMemory($_GET);

$sSqlDadosArquivo = "select distinct 
                            rh89_mesusu||' / '||rh89_anousu as competencia_liquidadcao,
														z01_numcgm        as numcgm,
														z01_nome          as nome,
														rh89_valorserv    as valor_servico,
														rh89_valorretinss as valor_inss,
														rh89_codord       as ordem_pgto,
														rh89_dataliq      as data_liquidacao,
														e60_codemp        as codigo_empenho,
														e60_anousu        as ano_usu,
														case
														  when rh90_sequencial is not null then 'SIM' else 'NÃO'
														end as enviado_sefip
 											 from rhautonomolanc
														inner join cgm                   on cgm.z01_numcgm                            = rhautonomolanc.rh89_numcgm
                            inner join pagordem              on rh89_codord                               = e50_codord
                            inner join empempenho            on e50_numemp                                = e60_numemp
														left  join rhsefiprhautonomolanc on rhsefiprhautonomolanc.rh92_rhautonomolanc = rhautonomolanc.rh89_sequencial
														left  join rhsefip               on rhsefip.rh90_sequencial                   = rhsefiprhautonomolanc.rh92_rhsefip
														                                and rhsefip.rh90_ativa is true
 										where rhautonomolanc.rh89_anousu = {$oGet->iAnoCompetencia}
											and rhautonomolanc.rh89_mesusu = {$oGet->iMesCompetencia}  ";

$rsDadosArquivo = db_query($sSqlDadosArquivo);										  
$iLinhasArquivo = pg_num_rows($rsDadosArquivo);										  

if ($iLinhasArquivo == 0 ) {
	$sMsg = "Nenhum registro encontrado para a competência {$oGet->iAnoCompetencia}/{$oGet->iMesCompetencia}";
	db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
}

										  
$head2 = " Valores Processados por Competência ";										  
$head3 = " Competência : ".str_pad($oGet->iMesCompetencia,2,'0',STR_PAD_LEFT)."/{$oGet->iAnoCompetencia}";

$oPdf = new PDF();
$oPdf->Open();

$oPdf->AliasNbPages();
$oPdf->SetFillColor(235);

$iFonte    = 8;
$iAlt      = 5;
$iPreenche = 1;

imprimeCab($oPdf,$iAlt,$iFonte,true);

for ( $iInd=0; $iInd < $iLinhasArquivo; $iInd++ ) {
  
  $oDadosGerados = db_utils::fieldsMemory($rsDadosArquivo,$iInd);
  
	imprimeCab($oPdf,$iAlt,$iFonte);
	
	if ($iPreenche == 1 ) {
		$iPreenche = 0;
	} else {
		$iPreenche = 1;
	}
	
	$oPdf->Cell(20 ,$iAlt,$oDadosGerados->competencia_liquidadcao                    ,0,0,'C',$iPreenche);
	$oPdf->Cell(20 ,$iAlt,$oDadosGerados->numcgm                                     ,0,0,'C',$iPreenche);
	$oPdf->Cell(70 ,$iAlt,$oDadosGerados->nome                                       ,0,0,'L',$iPreenche);
	$oPdf->Cell(25 ,$iAlt,db_formatar($oDadosGerados->valor_servico,'f')             ,0,0,'R',$iPreenche);
	$oPdf->Cell(30 ,$iAlt,db_formatar($oDadosGerados->valor_inss,'f')                ,0,0,'R',$iPreenche);
	$oPdf->Cell(30 ,$iAlt,$oDadosGerados->ordem_pgto                                 ,0,0,'C',$iPreenche);
	$oPdf->Cell(25 ,$iAlt,$oDadosGerados->codigo_empenho.'/'.$oDadosGerados->ano_usu ,0,0,'C',$iPreenche);
	$oPdf->Cell(30 ,$iAlt,db_formatar($oDadosGerados->data_liquidacao,'d')           ,0,0,'C',$iPreenche);
	$oPdf->Cell(30 ,$iAlt,$oDadosGerados->enviado_sefip                              ,0,1,'C',$iPreenche);
	
	
}

$oPdf->Output();

function imprimeCab($oPdf,$iAlt,$iFonte,$lImprime=false){

  if ($oPdf->gety() > $oPdf->h - 30 || $lImprime ){
  	
		$oPdf->AddPage("L");
		$oPdf->SetFont('Arial','b',$iFonte);

    $oPdf->Cell(20 ,$iAlt,"Competência"    ,1,0,'C',1);
	  $oPdf->Cell(20 ,$iAlt,"CGM"            ,1,0,'C',1);
	  $oPdf->Cell(70 ,$iAlt,"Nome"           ,1,0,'C',1);
	  $oPdf->Cell(25 ,$iAlt,"Valor Serviço"  ,1,0,'C',1);
	  $oPdf->Cell(30 ,$iAlt,"Valor INSS"     ,1,0,'C',1);
	  $oPdf->Cell(30 ,$iAlt,"Ordem Pagamento",1,0,'C',1);
	  $oPdf->Cell(25 ,$iAlt,"Empenho"        ,1,0,'C',1);
	  $oPdf->Cell(30 ,$iAlt,"Data Liquidação",1,0,'C',1);
	  $oPdf->Cell(30 ,$iAlt,"Enviado SEFIP"  ,1,1,'C',1);
		
  	$oPdf->SetFont('Arial','',$iFonte);
			
  }
	
}
?>