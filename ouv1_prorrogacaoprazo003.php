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

include("fpdf151/pdf.php");
include("libs/db_liborcamento.php");
include("libs/db_utils.php");
include("classes/db_protprocesso_classe.php");
include("classes/db_processoouvidoriaprorrogacao_classe.php");

$clprotprocesso = new cl_protprocesso();
$clprocessoouvidoriaprorrogacao = new cl_processoouvidoriaprorrogacao();

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_GET_VARS);

$codproc	 =  $p58_codproc;
$datausu	 = 	date('Y-m-d',db_getsession('DB_datausu'));
		
$sInner = "";
$sWhere = "";
if($codproc != ""){

	$sCampos  = "p58_codproc,p58_id_usuario,p58_coddepto,p58_dtproc,p58_hora,p58_codigo,p58_requer,p58_numcgm,a.descrdepto as nomedepto";
	$sCampos .= ",nome as nomeusuario, p51_descr as nomeprocesso, z01_nome as nometitular";
	//die($clprotprocesso->sql_query_sql_query_andam_ouvidoria($oParam->chave,$sCampos));	
	$rsProcesso = $clprotprocesso->sql_record($clprotprocesso->sql_query_andam_ouvidoria($codproc,$sCampos));
	
	if($clprotprocesso->numrows > 0){
			
		$aDadosProcesso = db_utils::getColectionByRecord($rsProcesso,false,false,false);
												
												
												
		$sQueryProcessos = " SELECT processoouvidoriaprorrogacao.*,
		                            db_depart.descrdepto,                                                                                                
		                            (select min(p.ov15_dtfim) 
												           from processoouvidoriaprorrogacao p
												          where p.ov15_protprocesso  = {$codproc} 
												            and p.ov15_coddepto      = processoouvidoriaprorrogacao.ov15_coddepto
												            and p.ov15_dtfim         < processoouvidoriaprorrogacao.ov15_dtfim
												            and p.ov15_ativo is true
												        ) as dt_depto_ant,
												        processoouvidoriaprorrogacao.ov15_dtfim
        								   from processoouvidoriaprorrogacao       
				        				        inner join db_depart on ov15_coddepto = coddepto 
												  where ov15_protprocesso = {$codproc} 
												    and ov15_ativo is true 
												  order by ov15_dtfim ";											
												
	// echo $sQueryProcessos."<br><br>";		
	// die($sQueryProcessos);			
		$rsQueryProcessos	= pg_query($sQueryProcessos);
		if(pg_num_rows($rsQueryProcessos)>0){
			$aDadosProrrogacao = array();
			$aDadosSequencial	 = array();
			$iNumRows = pg_num_rows($rsQueryProcessos);

			for($iInd = 0; $iInd < $iNumRows; $iInd++){
				$aDados = db_utils::fieldsMemory($rsQueryProcessos,$iInd);
				$keys = array_keys($aDadosSequencial,$aDados->ov15_coddepto);
   			$sWhere = "";
   			
				if (!empty($aDados->dt_depto_ant)) {
					$sWhere = " and ov15_dtfim > '{$aDados->dt_depto_ant}'";
				}
				
        $sQueryProcesso = "select db_depart.descrdepto,
                                  processoouvidoriaprorrogacao.* 
                             from processoouvidoriaprorrogacao 
																	inner join db_depart on ov15_coddepto = coddepto
                            where ov15_protprocesso = {$codproc} 
                              and ov15_coddepto     = {$aDados->ov15_coddepto}
                              {$sWhere}
                              and ov15_dtfim <= '{$aDados->ov15_dtfim}' 
                              and ov15_ativo is false order by ov15_sequencial";
														 			
			  // echo $sQueryProcesso."<br><br>";
				
				$rsQueryProcesso = pg_query($sQueryProcesso);
				$iNumRows1 = pg_num_rows($rsQueryProcesso);
				if($iNumRows1 > 0){
					
					$mktime_ant = 0;
					for($iInd1=0;$iInd1<$iNumRows1;$iInd1++){
						$aDados1 = db_utils::fieldsMemory($rsQueryProcesso,$iInd1);
						if($iInd1==0){
							$aDados1->prorrogacao = 'Não';
						}else{
							$aDados1->prorrogacao = 'Sim';	
						}
						if($iInd1==0){
							$mktime_ant = 0;
						}else{
							$mktime_ant = $mktime_atual;
						}
						$dt_atual = explode('-',$aDados1->ov15_dtfim);
						$mktime_atual = mktime(0,0,0,$dt_atual[1],$dt_atual[2],$dt_atual[0]);
						if($mktime_ant < $mktime_atual){						
							$aDadosProrrogacao[] = $aDados1;
						}
					}
					$aDados->prorrogacao = 'Sim';
					$aDadosProrrogacao[] = $aDados;
				}else {
					$aDados->prorrogacao = 'Não';
					$aDadosProrrogacao[] = $aDados;
				}
			}
			
			
		}else{
			db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum processo encontrado!');
		}
	
	}
}

 //exit();

//echo "<pre>";
//echo var_dump($aDadosProrrogacao);
//echo "</pre>";
//
//exit();

$head2 = 'Relatório de Prorrogação de Prazos';
$head3 = '';
$head4 = 'Data de Emissão: '.date('d/m/Y',db_getsession('DB_datausu'));

$pdf_cabecalho = true;
$pdf = new PDF("P", "mm", "A4"); 
$pdf->Open();
$pdf->AliasNbPages(); 
$pdf->AddPage();
$pdf->setfillcolor(235);
$pdf->SetFont('Arial','b',7);
//$pdf->ln(2);

$pdf->Cell(30,5,"Processo:",'',0,"L",0);
$pdf->SetFont('Arial','',7);
$pdf->Cell(60,5,$aDadosProcesso[0]->p58_codproc,'',1,"L",0);
$pdf->SetFont('Arial','b',7);
$pdf->Cell(30,5,"Usuário:",'',0,"L",0);
$pdf->SetFont('Arial','',7);
$pdf->Cell(10,5,$aDadosProcesso[0]->p58_id_usuario,'',0,"L",0);
$pdf->Cell(60,5,$aDadosProcesso[0]->nomeusuario,'',1,"L",0);
$pdf->SetFont('Arial','b',7);
$pdf->Cell(30,5,"Departamento:",'',0,"L",0);
$pdf->SetFont('Arial','',7);
$pdf->Cell(10,5,$aDadosProcesso[0]->p58_coddepto,'',0,"L",0);
$pdf->Cell(60,5,$aDadosProcesso[0]->nomedepto,'',1,"L",0);
$pdf->SetFont('Arial','b',7);
$pdf->Cell(30,5,"Data de Criação:",'',0,"L",0);
$pdf->SetFont('Arial','',7);
$pdf->Cell(20,5,db_formatar($aDadosProcesso[0]->p58_dtproc,'d'),'',0,"L",0);
$pdf->SetFont('Arial','b',7);
$pdf->Cell(10,5,"Hora:",'',0,"L",0);
$pdf->SetFont('Arial','',7);
$pdf->Cell(30,5,$aDadosProcesso[0]->p58_hora,'',1,"L",0);
$pdf->SetFont('Arial','b',7);
$pdf->Cell(30,5,"Tipo de Processo:",'',0,"L",0);
$pdf->SetFont('Arial','',7);
$pdf->Cell(10,5,$aDadosProcesso[0]->p58_codigo,'',0,"L",0);
$pdf->Cell(60,5,$aDadosProcesso[0]->nomeprocesso,'',1,"",0);
$pdf->SetFont('Arial','b',7);
$pdf->Cell(30,5,"Requerente:",'',0,"L",0);
$pdf->SetFont('Arial','',7);
$pdf->Cell(60,5,$aDadosProcesso[0]->p58_requer,'',1,"L",0);
$pdf->SetFont('Arial','b',7);
$pdf->Cell(30,5,"Titular do Processo:",'',0,"L",0);
$pdf->SetFont('Arial','',7);
$pdf->Cell(10,5,$aDadosProcesso[0]->p58_numcgm,'',0,"L",0);
$pdf->Cell(60,5,$aDadosProcesso[0]->nometitular,'',1,"L",0);

$pdf->Cell(190,2,"",'B',1,"L",0);



$iNumRows 	= count($aDadosProrrogacao);
$background = 0;
$prorrogacao = "Não";

for($iInd=0; $iInd<$iNumRows; $iInd++){
	$pdf->SetAutoPageBreak(false);
	//$pdf->h;
	if($pdf->GetY() > $pdf->h - 25 || $pdf_cabecalho == true){
		$pdf_cabecalho = false;  
		$pdf->SetFont('Courier','',7);
	  $pdf->SetTextColor(0,0,0);
	  $pdf->setfillcolor(235);
	  $preenc = 0;
	  $linha = 1;
	  
	  if($iInd != 0 && $pdf->GetY() > $pdf->h - 25 ){
	  	$pdf->AddPage();
	  }
	  
	  $pdf->SetFont('Arial','b',7);
		$pdf->Cell(60,5,"Departamento : ".$aDadosProrrogacao[$iInd]->ov15_coddepto.' - '.$aDadosProrrogacao[$iInd]->descrdepto,'',1,"L",0);
		
		$pdf->SetFont('Arial','b',6);
		$pdf->Cell(15,5,"Data Inicial",'TB',0,"C",1);
		$pdf->Cell(15,5,"Data Final",'LTB',0,"C",1);
		$pdf->Cell(15,5,"Prorrogação",'LTB',0,"C",1);
		$pdf->Cell(145,5,"Motivo",'LTB',1,"C",1);
		$background = 0;		
		$pdf_cabecalho == false;
	}
		$pdf->SetFont('Arial','',6);
		/*
		if($iInd == 0 || ($iInd+1 == $iNumRows)){
			
			$dtini 		= db_formatar($aDadosProrrogacao[$aDados[$iInd]->ov15_sequencial]->ov15_dtini,'d');
			$dtfim		= db_formatar($aDadosProrrogacao[$aDados[$iInd]->ov15_sequencial]->ov15_dtfim,'d');
			$dtinipro = "";
			$dtfimpro = "";
						
		}else{
			//if($aDados[$iInd]->ov15_coddepto == $aDados[$iInd-1]->ov15_coddepto){
			if(true){
				$dtini 		= db_formatar($aDados[$iInd-1]->ov15_dtini,'d');
				$dtfim		= db_formatar($aDados[$iInd-1]->ov15_dtfim,'d');
				$dtinipro = db_formatar($aDados[$iInd]->ov15_dtini,'d');
				$dtfimpro = db_formatar($aDados[$iInd]->ov15_dtfim,'d');
			
			}else{

				$dtini 		= db_formatar($aDados[$iInd]->ov15_dtini,'d');
				$dtfim		= db_formatar($aDados[$iInd]->ov15_dtfim,'d');
				$dtinipro = "";
				$dtfimpro = "";
				
			}
			
		}
		*/
		$dtini 		= db_formatar($aDadosProrrogacao[$iInd]->ov15_dtini,'d');
		$dtfim		= db_formatar($aDadosProrrogacao[$iInd]->ov15_dtfim,'d');

		$prorrogacao = $aDadosProrrogacao[$iInd]->prorrogacao;
		
		$pdf->Cell(15,5,$dtini,'',0,"C",$background);
		$pdf->Cell(15,5,$dtfim,'',0,"C",$background);
		$pdf->Cell(15,5,$prorrogacao,'',0,"C",$background);
		
		$pdf->MultiCell(145,5,$aDadosProrrogacao[$iInd]->ov15_motivo,0,"L",$background);
		
		$background = $background == 0 ? 1 : 0;
		//$prorrogacao		=	"Sim";
		
		if(($iInd+1 < $iNumRows) && ($aDadosProrrogacao[$iInd]->ov15_coddepto != $aDadosProrrogacao[$iInd+1]->ov15_coddepto)){
			$pdf_cabecalho 	= true;
			//$prorrogacao		=	"Não"; 
		}
		
}
	$pdf->Ln(4);
	$pdf->Cell(160,5,'Total de Registros:','',0,"R",1);
	$pdf->Cell(30,5,$iNumRows,'',1,"R",1);

	$pdf->Output();

?>