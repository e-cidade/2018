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

require ("libs/db_utils.php");
include("fpdf151/pdf.php");
include("libs/db_sql.php");

$oPost = db_utils::postMemory($_GET);
$instit			= db_getsession('DB_instit');

if ($oPost->tipo == 1){
	$tipo = "Analítico";
}else{
	$tipo = "Sintético";
}

$head2 = "Relatório: Arquivos PIT";
$head4 = "Período: ".db_formatar($oPost->dt_inicial,'d')." à ".db_formatar($oPost->dt_final,'d');
$head6 = "Tipo: ".$tipo;

$pdf = new PDF('L','mm','A4'); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total 	= 0;
$alt 		= 4;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$pdf->ln();

$sWhere = "";
if(isset($oPost->e14_sequencial) && trim($oPost->e14_sequencial) != ''){
	$sWhere = " e14_sequencial = ".$oPost->e14_sequencial; 
}else if($oPost->tipo == 1){
	$sWhere = " e69_dtnota between '$oPost->dt_inicial' and '$oPost->dt_final' ";
}else{
	$sWhere = " e14_dtarquivo between '$oPost->dt_inicial' and '$oPost->dt_final' ";
}

if($oPost->tipo == 1){
	$sQuery = " select 	e15_emparquivopit,
											e14_sequencial as codigo ,
											e14_nomearquivo as arquivo,
											nome as nome,
											e14_dtarquivo as data,
											e14_hora as hora,
											e14_dtinicial,
											e14_dtfinal,
											(case WHEN e14_situacao = 1 then 'Ativo' else 'Cancelado' end) as situacao,
											e16_motivo as motivo,   
											e69_numero,     
											z01_nome, 
											z01_numcgm, 
											z01_incest,
											e60_numemp,
											(case when e11_seriefiscal = 0 then null else e11_seriefiscal end) as e11_seriefiscal,
											z01_cgccpf, 
											e69_dtnota, 
											z01_uf, 
											e10_cfop, 
											e11_sequencial, 
											e11_inscricaosubstitutofiscal,
											e11_basecalculoicms::varchar as e11_basecalculoicms,e11_valoricms::varchar as e11_valoricms,
											e11_basecalculosubstitutotrib,e11_valoricmssubstitutotrib,sum(e70_valor)::varchar as valornota 
									from empnotadadospit       
									inner join empnotadadospitnotas on e11_sequencial = e13_empnotadadospit       
									inner join empnota              on e69_codnota    = e13_empnota        
									inner join empnotaele           on e69_codnota    = e70_codnota        
									inner join empempenho           on e69_numemp     = e60_numemp        
									inner join cgm                  on e60_numcgm = z01_numcgm       
									left join cfop  on  cfop.e10_sequencial = empnotadadospit.e11_cfop       
									left join emparquivopitnotas    on  e15_empnotadadospit  = e11_sequencial       
									left join emparquivopit         on  e15_emparquivopit    = e14_sequencial 
									inner join db_usuarios 					on e14_idusuario       = id_usuario 
									left join emparquivopitanulado on e14_sequencial = e16_emparquivopit 
								where   ".$sWhere." 
									 and e60_instit   = $instit
									 and e69_tipodocumentosfiscal = 50 
								group by 	e15_emparquivopit,e14_sequencial,e14_nomearquivo,nome,e14_dtarquivo,e14_hora,e14_dtinicial,e14_dtfinal,situacao,
													e16_motivo,e69_numero,z01_nome,z01_numcgm,z01_incest,e60_numemp,
													e11_seriefiscal,z01_cgccpf,e69_dtnota,z01_uf,e10_cfop,e11_sequencial,e11_inscricaosubstitutofiscal,
													e11_basecalculoicms,e11_valoricms,e11_basecalculosubstitutotrib,e11_valoricmssubstitutotrib 
								order by e15_emparquivopit,e14_sequencial,e69_dtnota, z01_numcgm,e69_numero  ";
//die($sQuery);
} else {
	$sQuery = "SELECT distinct 	e14_sequencial as codigo,
														e14_nomearquivo as arquivo,
														nome as nome,
														e14_dtarquivo as data,
														e14_hora as hora,
														e14_dtinicial,
														e14_dtfinal,
														(case WHEN e14_situacao = 1 then 'Ativo' else 'Cancelado' end) as situacao,
														e16_motivo as motivo 
										FROM emparquivopit          
						 				inner join  emparquivopitnotas    on e14_sequencial      = e15_emparquivopit         
						 				inner join  empnotadadospit       on e15_empnotadadospit = e11_sequencial          
						 				inner join  empnotadadospitnotas  on e11_sequencial      = e13_empnotadadospit          
						 				inner join  empnota               on e13_empnota         = e69_codnota          
						 				inner join  empempenho            on e69_numemp          = e60_numemp          
										inner join  db_usuarios           on e14_idusuario       = id_usuario 
						 				left join emparquivopitanulado on e14_sequencial = e16_emparquivopit 
										WHERE e60_instit = $instit 
										  and ".$sWhere." ";
//die($sQuery);
}
$rsQuery = pg_query($sQuery);
$iNumRows = pg_num_rows($rsQuery);
$troca = 1;
$fill = 1;
$lAnalitico = true;
$iCodigoAtual = 0;

$oTotais = new stdClass();
$oTotais->e11_basecalculoicms          = 0;
$oTotais->e11_valoricmssubstitutotrib  = 0;
$oTotais->valornota                    = 0;
$oTotais->contador                     = 0;
$oTotais->geralBasecalculoicms         = 0;
$oTotais->geralValoricmssubstitutotrib = 0;
$oTotais->geralValornota               = 0;


if($iNumRows > 0){
	
	for($i=0; $i < $iNumRows; $i++){
		
		$oRow = db_utils::fieldsMemory($rsQuery,$i);
		
		if ($iCodigoAtual == $oRow->codigo && $oPost->tipo == 1) {
			$lAnalitico = false;
		} else {
			$lAnalitico = true;
			//Verifica se for analitico ai exibe totais
			if ( $oPost->tipo == 1 ){
			  totaldoArquivo($pdf,$alt,$oTotais);
			  $oTotais->e11_basecalculoicms         = 0;
        $oTotais->e11_valoricmssubstitutotrib = 0;
        $oTotais->valornota                   = 0;
        $oTotais->contador                    = 0;
			}
		}
		
		$iCodigoAtual = $oRow->codigo;
		
		if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){

			$pdf->addpage();
			$pdf->setfont('arial','b',8);
			$pdf->cell(30,$alt ,'Código','TB',0,'C',1);
			$pdf->cell(60,$alt ,'Arquivo',1,0,'C',1);
			$pdf->cell(90,$alt ,'Nome',1,0,'C',1);
			$pdf->cell(40,$alt ,'Período',1,0,'C',1);
			$pdf->cell(40,$alt ,'Data / Hora geração' ,1,0,'C',1);
			$pdf->cell(20,$alt ,'Situação','LTB',1,'C',1);
			//$pdf->cell(100,$alt,'Motivo','TLB',1,'C',1);
			$pdf->ln(1);
			
			if($oPost->tipo == 1){
				$pdf->setfont('arial','b',7);
				$pdf->cell(30,$alt,'CNPJ do emitente','TB',0,'C',1);
				$pdf->cell(75,$alt,'Nome do emitente',1,0,'C',1);
				$pdf->cell(23,$alt,'Inscr. Est. Emit. ',1,0,'C',1);
				$pdf->cell(21,$alt,'Nº da nota',1,0,'C',1);
				$pdf->cell(20,$alt,'Série da nota',1,0,'C',1);
				$pdf->cell(20,$alt,'Data da nota',1,0,'C',1);
				$pdf->cell(10,$alt,'CFOP',1,0,'C',1);
				$pdf->cell(30,$alt,'Base Cálc. ICMS','LTB',0,'C',1);
				$pdf->cell(30,$alt,'Vlr do ICMS sub trib.',1,0,'C',1);
				$pdf->cell(20,$alt,'Vlr da nota',1,1,'C',1);
				$pdf->ln(4);		
			}
						
			$pdf->setfont('arial','',7);
			$fill = 0;
			$troca = 0;
		}
		
		if($lAnalitico){
			
			
			
			$fill = $fill == 1 ? 0 : 1;
			
			if ($oPost->tipo==1) {
				
				$pdf->ln(4);
				$pdf->setfont('arial','B',7);
			
			}
			
			$pdf->cell(30,$alt,$oRow->codigo,0,0,'C',$fill);
			$pdf->cell(60,$alt,$oRow->arquivo,0,0,'L',$fill);
			$pdf->cell(90,$alt,$oRow->nome,0,0,'L',$fill);
			if($oRow->e14_dtinicial != ""){
			 $pdf->cell(40,$alt,db_formatar($oRow->e14_dtinicial,'d').' a '.db_formatar($oRow->e14_dtfinal,'d'),0,0,'C',$fill);
			}else{
				$pdf->cell(40,$alt,'',0,0,'C',$fill);
			}
			
			//$pdf->cell(30,$alt,,0,0,'C',$fill);
			$pdf->cell(40,$alt,db_formatar($oRow->data,'d')." - ".$oRow->hora,0,0,'C',$fill);
			$pdf->cell(20,$alt,$oRow->situacao,0,1,'L',$fill);
			
			$lAnalitico = false;
		}
		
		if ($oPost->tipo == 1){
			
			$fill = 0;
			$pdf->setfont('arial','',7);
			$pdf->cell(30,$alt,db_formatar($oRow->z01_cgccpf,'cnpj'),'B',0,'C',$fill);
			$pdf->cell(75,$alt,$oRow->z01_nome       ,'B',0,'L',$fill);
			$pdf->cell(23,$alt,$oRow->z01_incest     ,'B',0,'R',$fill);
			$pdf->cell(21,$alt,$oRow->e69_numero     ,'B',0,'R',$fill);
			$pdf->cell(20,$alt,$oRow->e11_seriefiscal,'B',0,'R',$fill);
			$pdf->cell(20,$alt,db_formatar($oRow->e69_dtnota,'d'),'B',0,'C',$fill);
			$pdf->cell(10,$alt,$oRow->e10_cfop       ,'B',0,'R',$fill);
			$pdf->cell(30,$alt,db_formatar($oRow->e11_basecalculoicms,'f'),'B',0,'R',$fill);
			$pdf->cell(30,$alt,db_formatar($oRow->e11_valoricmssubstitutotrib,'f'),'B',0,'R',$fill);
			$pdf->cell(20,$alt,db_formatar($oRow->valornota,'f'),'B',1,'R',$fill);
			
		  $oTotais->e11_basecalculoicms          += $oRow->e11_basecalculoicms;
      $oTotais->e11_valoricmssubstitutotrib  += $oRow->e11_valoricmssubstitutotrib;
      $oTotais->valornota                    += $oRow->valornota;
      $oTotais->contador++;
		  
      $oTotais->geralBasecalculoicms         += $oTotais->e11_basecalculoicms; 
      $oTotais->geralValoricmssubstitutotrib += $oTotais->e11_valoricmssubstitutotrib; 
      $oTotais->geralValornota               += $oTotais->valornota;
       	
		}
		
		
    		
	}
	
  if ( $oPost->tipo == 1 ){
  	totaldoArquivo($pdf,$alt,$oTotais);
  	totaldoGeral($pdf,$alt,$oTotais);
  }
  
  totaldeRegistros($pdf,$alt,$iNumRows); 
	
} else {
	
	db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados para o relatório.');

}

$pdf->Output();

function totaldoArquivo($pdf,$alt,$oTotal){
	
	$pdf->ln(2);
	$pdf->setfont('arial','B',7);
	$pdf->cell(199,$alt  ,'Totais do arquivo'                                  ,'TB' ,0,'R',1);
  $pdf->cell(30 ,$alt  ,db_formatar($oTotal->e11_basecalculoicms,'f')        ,'LTB',0,'R',1);
  $pdf->cell(30 ,$alt  ,db_formatar($oTotal->e11_valoricmssubstitutotrib,'f'),'LTB',0,'R',1);
  $pdf->cell(20 ,$alt  ,db_formatar($oTotal->valornota,'f')                  ,'LTB',1,'R',1);
  //$pdf->setfont('arial','B',7);
  $pdf->cell(199,$alt  ,'Total de registros do arquivo'                      ,'TB' ,0,'R',1);
  $pdf->cell(30 ,$alt  ,$oTotal->contador                                    ,'LTB',0,'L',1);
  $pdf->cell(50 ,$alt  ,''                                                   ,'TB' ,1,'R',1);
  //$pdf->cell(20 ,$alt  ,db_formatar($oTotal->valornota,'f')                  ,'LTB',1,'R',1);
  
}
function totaldoGeral($pdf,$alt,$oTotal){
	
	$pdf->ln(2);
	$pdf->setfont('arial','B',7);
  $pdf->cell(199,$alt  ,'Total Geral'                                         ,'TB' ,0,'R',1);
  $pdf->cell(30 ,$alt  ,db_formatar($oTotal->geralBasecalculoicms ,'f')       ,'LTB',0,'R',1);
  $pdf->cell(30 ,$alt  ,db_formatar($oTotal->geralValoricmssubstitutotrib,'f'),'LTB',0,'R',1);
  $pdf->cell(20 ,$alt  ,db_formatar($oTotal->geralValornota,'f')              ,'LTB',1,'R',1);
	
}
function totaldeRegistros($pdf,$alt,$total){
  
  $pdf->ln(2);
  $pdf->setfont('arial','B',7);
  $pdf->cell(199,$alt  ,'Total de Registros','TB' ,0,'R',1);
  $pdf->cell(30 ,$alt  ,$total              ,'LTB',0,'L',1);
  $pdf->cell(50 ,$alt  ,''                  ,'TB' ,1,'R',1);
  
}

?>