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

include("fpdf151/pdf.php");
include("libs/db_liborcamento.php");
include("libs/db_utils.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_GET_VARS);

$coddepto	 =  $p58_coddepto;
$datausu	 = 	date('Y-m-d',db_getsession('DB_datausu'));
		
$sWhere = "";

if ($tipo == 0) {
			
	if(trim($dtini) != "" && trim($dtfim) != ""){
		$sWhere = " and p58_dtproc between '".$dtini."' and '".$dtfim."'"; 
	}
		
  $sQueryProcessos  = "select distinct        ";
  $sQueryProcessos .= "         p58_codproc,  ";
  $sQueryProcessos .= "         p58_codigo,   ";  
  $sQueryProcessos .= "         p51_descr,    ";
  $sQueryProcessos .= "         p.p58_requer, ";
  $sQueryProcessos .= "         p.p58_dtproc, ";
  $sQueryProcessos .= "         ( select coddepto||'-'||descrdepto ";
  $sQueryProcessos .= "             from db_depart                 ";
  if( $coddepto != 0 ) {
    $sQueryProcessos .= "          where coddepto = {$coddepto}    ";
  } else {
    $sQueryProcessos .= "          where coddepto = fc_deptoatualprocesso(p58_codproc)";      
  }
  $sQueryProcessos .= "         ) as deptoatual,                   ";    
  $sQueryProcessos .= "         case          ";   
  $sQueryProcessos .= "           when exists  ( select 1 ";
  $sQueryProcessos .= "                            from proctransferproc ";  
  $sQueryProcessos .= "                                 left  join proctransand on proctransand.p64_codtran = proctransferproc.p63_codtran "; 
  $sQueryProcessos .= "                           where p63_codproc     = p58_codproc  ";
  $sQueryProcessos .= "                             and p64_codtran is null limit 1  ) then null "; 
  $sQueryProcessos .= "           else p61_dtandam ";
  $sQueryProcessos .= "         end as p61_dtandam, ";    
  $sQueryProcessos .= "         ( select max( ov15_dtfim )                                               ";
  $sQueryProcessos .= "             from processoouvidoriaprorrogacao                                    ";
  $sQueryProcessos .= "            where processoouvidoriaprorrogacao.ov15_protprocesso = p.p58_codproc  ";
  $sQueryProcessos .= "              and processoouvidoriaprorrogacao.ov15_ativo is true                 ";
    
  if ( $coddepto != 0 ) {
    $sQueryProcessos .= "            and processoouvidoriaprorrogacao.ov15_coddepto  = $coddepto         ";
  } else {
    $sQueryProcessos .= "            and processoouvidoriaprorrogacao.ov15_coddepto  = fc_deptoatualprocesso(p58_codproc)";  
  }
    
  $sQueryProcessos .= "         ) as ov15_dtfim                                                             ";
  $sQueryProcessos .= "     from processoouvidoria                                                          ";
  $sQueryProcessos .= "          inner join protprocesso p on p.p58_codproc          = processoouvidoria.ov09_protprocesso       ";
  $sQueryProcessos .= "          inner join tipoproc       on tipoproc.p51_codigo    = p.p58_codigo                              ";
  $sQueryProcessos .= "          left  join procandam      on procandam.p61_codandam = p.p58_codandam                            ";
  $sQueryProcessos .= "    where p51_tipoprocgrupo = 2                                                                           ";
  $sQueryProcessos .= " and (( exists (select 1                                                                                  ";  
  $sQueryProcessos .= "                  from proctransferproc                                                                   ";                                   
  $sQueryProcessos .= "                       inner join proctransfer on proctransfer.p62_codtran = proctransferproc.p63_codtran ";
  $sQueryProcessos .= "                       left  join proctransand on proctransand.p64_codtran = proctransferproc.p63_codtran ";
  $sQueryProcessos .= "                 where p63_codproc = p58_codproc                                                          ";
    
  if ( $coddepto != 0 ) {
    $sQueryProcessos .= "       and p62_coddeptorec = $coddepto     ";
  } 

  $sQueryProcessos .= "       and p64_codtran is null limit 1 )     ";
  $sQueryProcessos .= "       or (                                  ";
    
  if ( $coddepto != 0 ) {
    $sQueryProcessos .= "               p61_coddepto    = $coddepto ";
  } else {
    $sQueryProcessos .= "               p61_coddepto is not null    ";
  }
    
  $sQueryProcessos .= "               and not exists( select *    ";
  $sQueryProcessos .= "                                 from proctransferproc                                                                 ";                                    
  $sQueryProcessos .= "                                     inner join proctransfer on proctransfer.p62_codtran = proctransferproc.p63_codtran  ";
  $sQueryProcessos .= "                               left  join proctransand on proctransand.p64_codtran = proctransferproc.p63_codtran    ";
  $sQueryProcessos .= "                               where p63_codproc  = p58_codproc                                                      ";
    
  if ( $coddepto != 0 ) {     
    $sQueryProcessos .= "                               and p62_coddepto = $coddepto                                                    ";
  }     

  $sQueryProcessos .= "                                 and p64_codtran is null limit 1 )                                                   "; 
  $sQueryProcessos .= "            )                ";
  $sQueryProcessos .= "     )                       ";
  $sQueryProcessos .= "  or (   p58_codandam = 0    ";        
  $sQueryProcessos .= "   and exists ( select 1     ";
  $sQueryProcessos .= "                 from proctransferproc                                                              ";
  $sQueryProcessos .= "                 inner join proctransfer on proctransfer.p62_codtran = proctransferproc.p63_codtran ";
  $sQueryProcessos .= "                     where p63_codproc = p58_codproc ";
    
  if ( $coddepto != 0 ) {
    $sQueryProcessos .= "                     and p62_coddeptorec = $coddepto ";
  }
    
  $sQueryProcessos .= "                     limit 1 ) ";                        
  $sQueryProcessos .= "    )                          ";                
  $sQueryProcessos .= " )                             ";   
    
  if ( trim($p58_codigo) != '' ) {
    $sQueryProcessos .= " and p58_codigo = ".$p58_codigo;
  }
    
  $sQueryProcessos .= $sWhere;		

  $rsQueryProcessos	= pg_query($sQueryProcessos);

	if(pg_num_rows($rsQueryProcessos)>0){
		$aDados = db_utils::getColectionByRecord($rsQueryProcessos,false,false,false);
	}else{
		db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum processo encontrado!');		
	}
		
} else if ($tipo == 1) {

	//Aqui verifica todos que estao em execução
	
  $sQueryProcessos    = "select p58_codproc,p58_codigo,p58_requer,p58_dtproc,p61_dtandam,ov15_dtfim,p51_descr,            ";
  $sQueryProcessos   .= "       coddepto||'-'||descrdepto as deptoatual                                                   ";
  $sQueryProcessos   .= " from procandam as pa                                                                            ";
  $sQueryProcessos   .= "      inner join db_depart                           on db_depart.coddepto     = pa.p61_coddepto ";
  $sQueryProcessos   .= "      inner join protprocesso as pp                  on pa.p61_codandam        = pp.p58_codandam ";
  $sQueryProcessos   .= "                                                    and pa.p61_codproc         = pp.p58_codproc  ";
  $sQueryProcessos   .= "      inner join tipoproc as tp                      on tp.p51_codigo          = pp.p58_codigo   ";
  $sQueryProcessos   .= "      inner join processoouvidoriaprorrogacao as pop on pop.ov15_protprocesso  = pp.p58_codproc  "; 
  $sQueryProcessos   .= "                                                    and pop.ov15_ativo is true                   ";
  $sQueryProcessos   .= "                                                    and pop.ov15_coddepto      = pa.p61_coddepto "; 
  $sQueryProcessos   .= " where tp.p51_tipoprocgrupo = 2 ";
    
  if ( $coddepto != 0 ) {
    $sQueryProcessos   .= " and pa.p61_coddepto = ".$coddepto;
  }
    
  if ( trim($p58_codigo) != '' ) {
    $sQueryProcessos   .= "  and pp.p58_codigo = ".$p58_codigo;
  }
    
  $sQueryProcessos   .= "  and not exists( select 1 ";
  $sQueryProcessos   .= "                 from proctransferproc ";
  $sQueryProcessos   .= "                         left join proctransand on p64_codtran = p63_codtran ";  
  $sQueryProcessos   .= "                  where p63_codproc = p58_codproc "; 
  $sQueryProcessos   .= "                    and p64_codtran is null limit 1 )";

	if(trim($dtini) != "" && trim($dtfim) != ""){
		$sQueryProcessos .= " and pp.p58_dtproc between '".$dtini."' and '".$dtfim."'";
	}
	
	$rsQueryProcessos	= pg_query($sQueryProcessos);
	if(pg_num_rows($rsQueryProcessos)>0){
		$aDados = db_utils::getColectionByRecord($rsQueryProcessos,false,false,false);
	}else{
		db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum processo encontrado!');
	}
	

	
}else if($tipo == 2){
		
	$sWhere = "";
		
	if(trim($dtini) != "" && trim($dtfim) != ""){
		$sWhere = " and p58_dtproc between '".$dtini."' and '".$dtfim."'";  
	}
		
  $sQueryProcessos  = "select distinct ";
  $sQueryProcessos .= "         p58_codproc,   ";
  $sQueryProcessos .= "         p58_codigo,    ";  
  $sQueryProcessos .= "         p51_descr,     ";
  $sQueryProcessos .= "         p.p58_requer , ";
  $sQueryProcessos .= "         p.p58_dtproc , ";
  $sQueryProcessos .= "         ( select coddepto||'-'||descrdepto ";
  $sQueryProcessos .= "             from db_depart                 ";
  if( $coddepto != 0 ) {
    $sQueryProcessos .= "          where coddepto = {$coddepto}    ";
  } else {
    $sQueryProcessos .= "          where coddepto = fc_deptoatualprocesso(p58_codproc)";      
  }
  $sQueryProcessos .= "         ) as deptoatual,                   ";    
  $sQueryProcessos .= "         case          ";   
  $sQueryProcessos .= "           when exists  ( select 1 ";
  $sQueryProcessos .= "                            from proctransferproc ";  
  $sQueryProcessos .= "                                 left  join proctransand on proctransand.p64_codtran = proctransferproc.p63_codtran "; 
  $sQueryProcessos .= "                           where p63_codproc     = p58_codproc  ";
  $sQueryProcessos .= "                             and p64_codtran is null limit 1  ) then null "; 
  $sQueryProcessos .= "           else p61_dtandam ";
  $sQueryProcessos .= "         end as p61_dtandam, ";    
  $sQueryProcessos .= "         ( select max( ov15_dtfim ) ";
  $sQueryProcessos .= "                 from processoouvidoriaprorrogacao            ";
  $sQueryProcessos .= "               where processoouvidoriaprorrogacao.ov15_protprocesso = p.p58_codproc ";
  $sQueryProcessos .= "                 and processoouvidoriaprorrogacao.ov15_ativo          is true ";
    
  if ( $coddepto != 0 ) {
    $sQueryProcessos .= "                 and processoouvidoriaprorrogacao.ov15_coddepto     = $coddepto ";
  } else {
    $sQueryProcessos .= "            and processoouvidoriaprorrogacao.ov15_coddepto  = fc_deptoatualprocesso(p58_codproc)";
  }    
    
  $sQueryProcessos .= "         ) as ov15_dtfim ";
  $sQueryProcessos .= "     from processoouvidoria ";
  $sQueryProcessos .= "     inner join protprocesso p   on p.p58_codproc                = processoouvidoria.ov09_protprocesso ";
  $sQueryProcessos .= "     inner join tipoproc         on tipoproc.p51_codigo          = p.p58_codigo ";
  $sQueryProcessos .= "     left  join procandam        on procandam.p61_codandam       = p.p58_codandam ";
  $sQueryProcessos .= "     where p51_tipoprocgrupo = 2  ";
  $sQueryProcessos .= " and (( exists (select 1 ";  
  $sQueryProcessos .= "       from proctransferproc ";                                   
  $sQueryProcessos .= "       inner join proctransfer on proctransfer.p62_codtran = proctransferproc.p63_codtran ";
  $sQueryProcessos .= "       left  join proctransand on proctransand.p64_codtran = proctransferproc.p63_codtran ";
  $sQueryProcessos .= "     where p63_codproc     = p58_codproc                  ";
    
  if ( $coddepto != 0 ) {    
    $sQueryProcessos .= "       and p62_coddeptorec = $coddepto ";
  }
    
  $sQueryProcessos .= "       and p64_codtran is null limit 1 )     ";                                         
  $sQueryProcessos .= "       or (                                  ";
    
  if ( $coddepto != 0 ) {
    $sQueryProcessos .= "               p61_coddepto = $coddepto    ";
  } else {
    $sQueryProcessos .= "               p61_coddepto is not null    ";
  }
        
  $sQueryProcessos .= "             and not exists( select *        ";
  $sQueryProcessos .= "                               from proctransferproc ";                                    
  $sQueryProcessos .= "                               inner join proctransfer on proctransfer.p62_codtran = proctransferproc.p63_codtran ";
  $sQueryProcessos .= "                               left  join proctransand on proctransand.p64_codtran = proctransferproc.p63_codtran ";
  $sQueryProcessos .= "                               where p63_codproc  = p58_codproc    ";
    
  if ( $coddepto != 0 ) {   
    $sQueryProcessos .= "                                 and p62_coddepto = $coddepto      ";
  }
    
  $sQueryProcessos .= "                                 and p64_codtran is null limit 1 ) "; 
  $sQueryProcessos .= "         )                      ";
  $sQueryProcessos .= "     )                          ";
  $sQueryProcessos .= " or (   p58_codandam = 0      ";     
  $sQueryProcessos .= "           and exists ( select 1 "; 
  $sQueryProcessos .= "                     from proctransferproc ";          
  $sQueryProcessos .= "                     inner join proctransfer on proctransfer.p62_codtran = proctransferproc.p63_codtran ";
  $sQueryProcessos .= "                     where p63_codproc = p58_codproc  ";
  if ( $coddepto != 0 ) {
    $sQueryProcessos .= "                     and p62_coddeptorec = $coddepto ";
  }
  $sQueryProcessos .= "         limit 1 )                        ";
  $sQueryProcessos .= "   )                  ";
  $sQueryProcessos .= " )    ";
    
  if ( trim($p58_codigo) != '' ) {
    $sQueryProcessos .= " and p58_codigo = ".$p58_codigo; 
  }
    
  $sQueryProcessos .= "             and ( select max( ov15_dtfim ) "; 
  $sQueryProcessos .= "                     from processoouvidoriaprorrogacao ";
  $sQueryProcessos .= "                     where processoouvidoriaprorrogacao.ov15_protprocesso = p.p58_codproc ";
  $sQueryProcessos .= "                     and processoouvidoriaprorrogacao.ov15_ativo is true ";

  if ( $coddepto != 0 ) {
    $sQueryProcessos .= "                   and processoouvidoriaprorrogacao.ov15_coddepto = $coddepto ) < '$datausu' ";
  } else {
    $sQueryProcessos .= "            and processoouvidoriaprorrogacao.ov15_coddepto  = fc_deptoatualprocesso(p58_codproc) ) < '$datausu'";
  }        
    
  if ( trim($p58_codigo) != '' ) {
    $sQueryProcessos .= "                     and p58_codigo = ".$p58_codigo;
  }
    
  $sQueryProcessos .=                       $sWhere;		

	$rsQueryProcessos	= pg_query($sQueryProcessos);
	if (pg_num_rows($rsQueryProcessos) > 0) {
		$aDados = db_utils::getColectionByRecord($rsQueryProcessos,false,false,false);
	} else {
		db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum processo encontrado!');
	}
	
}

$head2 = 'Relatório de Processos';
$head3 = 'Tipo de Processo:';
$head4 = 'Departamento:';
$head5 = 'Processos:';

if($tipo == 0){
	$head5 .= " Todos";
}else if($tipo == 1){
	$head5 .= " Em Andamento";
}else if($tipo == 1){
	$head5 .= " Em Atrazo";	
}

if(trim($dtini) != "" && trim($dtfim) != ""){
	$head6 = "Período: ".db_formatar($dtini,'d')." à ".db_formatar($dtfim,'d');
}

if ( trim($p58_codigo) != '' ) {
	$sQueryTipo  = "select p51_descr,p51_codigo from tipoproc where p51_codigo = $p58_codigo";
	$rsQueryTipo = pg_query($sQueryTipo);
	if (pg_num_rows($rsQueryTipo) > 0) {
		db_fieldsmemory($rsQueryTipo,0);
		$head3 .= " ".$p51_codigo." - ".$p51_descr;		
	}
}

$sQueryDepartamento  = "select coddepto,descrdepto from db_depart where coddepto = $coddepto";
$rsQueryDepartamento = pg_query($sQueryDepartamento);
if (pg_num_rows($rsQueryDepartamento) > 0) {
	db_fieldsmemory($rsQueryDepartamento,0);
	$head4 .= " ".$coddepto." - ".$descrdepto;		
}

$pdf_cabecalho = true;
$pdf = new PDF("L", "mm", "A4"); 
$pdf->Open();
$pdf->AliasNbPages(); 

$iNumRows 	= count($aDados);
$background = 0;
for ($iInd=0; $iInd<$iNumRows; $iInd++) {

	$pdf->SetAutoPageBreak(false);

	if ($pdf->GetY() > $pdf->h - 25 || $pdf_cabecalho == true) {
		
		$pdf_cabecalho = false;  
		$pdf->SetFont('Courier','',7);
	  $pdf->SetTextColor(0,0,0);
	  $pdf->setfillcolor(235);
	  $preenc = 0;
	  $linha = 1;
	  $bordat = 0;
	  $pdf->AddPage('L');
	  $pdf->SetFont('Arial','b',7);
	  $pdf->ln(2);
		
		$pdf->Cell(15,5,"Número"             ,1,0,"C",1);
		$pdf->Cell(60,5,"Tipo de Processo"   ,1,0,"C",1);
		$pdf->Cell(50,5,"Requerente"         ,1,0,"C",1);
		$pdf->Cell(55,5,"Depto Atual"        ,1,0,"C",1);
		$pdf->Cell(25,5,"Data Criação"       ,1,0,"C",1);
		$pdf->Cell(25,5,"Data Recebimento"   ,1,0,"C",1);
		$pdf->Cell(25,5,"Data Vencimento"    ,1,0,"C",1);
		$pdf->Cell(20,5,"Dias em Atraso"     ,1,1,"C",1);
	
		$pdf_cabecalho == false;
	}  

	if ( trim($aDados[$iInd]->ov15_dtfim) != '' && $aDados[$iInd]->ov15_dtfim < date('Y-m-d',db_getsession('DB_datausu'))) {
    $aDataPrevFin = explode('-',$aDados[$iInd]->ov15_dtfim);
    $iDataPrevFin = mktime(0,0,0,$aDataPrevFin[1],$aDataPrevFin[2],$aDataPrevFin[0]);
    $iDiasAtraso  = ceil(((db_getsession('DB_datausu')-$iDataPrevFin)/86400)-1);
	} else {
		$iDiasAtraso  = '';
	}
    
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(15,5,$aDados[$iInd]->p58_codproc                 ,0,0,"C",$background);
  $pdf->Cell(60,5,$aDados[$iInd]->p58_codigo."-".$aDados[$iInd]->p51_descr,0,0,"L",$background);
	$pdf->Cell(50,5,$aDados[$iInd]->p58_requer                  ,0,0,"L",$background);
	$pdf->Cell(55,5,$aDados[$iInd]->deptoatual                  ,0,0,"L",$background);
	$pdf->Cell(25,5,db_formatar($aDados[$iInd]->p58_dtproc,'d') ,0,0,"C",$background);
	$pdf->Cell(25,5,db_formatar($aDados[$iInd]->p61_dtandam,'d'),0,0,"C",$background);
	$pdf->Cell(25,5,db_formatar($aDados[$iInd]->ov15_dtfim,'d') ,0,0,"C",$background);
	$pdf->Cell(20,5,$iDiasAtraso                                ,0,1,"C",$background);
	$background = $background == 0 ? 1 : 0;
	
}
$pdf->Ln(4);
$pdf->Cell(245,5,'Total de Registros:','',0,"R",1);
$pdf->Cell(30,5,$iNumRows,'',1,"R",1);
$pdf->Output();

?>