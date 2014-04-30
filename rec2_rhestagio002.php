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
include("libs/db_sql.php");
include("libs/db_utils.php");

$oGet        = db_utils::postMemory($_GET);
$iInstit     = db_getsession("DB_instit");
$inner_join  = "";
$sWhere = "";
switch ($oGet->avaliacao){
   case 'a' :
      $sWhere .= " h56_sequencial is not null ";
      $head2   = "SITUAÇÃO: Somente realizadas";
   break;
   case 'n' :
      $sWhere .= " h56_sequencial is null ";
      $head2   = "SITUAÇÃO: Somente não realizadas";
   break;
   default :
      $sWhere  = '1 = 1 ';
      $head2   = "SITUAÇÃO: Realizadas/Não realizadas";
   break;   
}
if (isset($oGet->dataInicial) && $oGet->dataInicial != null){
   $dataIniAux  = explode ("/",$oGet->dataInicial);
   $dataFimAux  = explode ("/",$oGet->dataFinal);
   $timeInicial = mktime(0,0,0,(int)$dataIniAux[1],(int)$dataIniAux[0],(int)$dataIniAux[2]);
   $timeFinal   = mktime(0,0,0,(int)$dataFimAux[1],(int)$dataFimAux[0],(int)$dataFimAux[2]);
   if ($timeInicial > $timeFinal){

      db_redireciona('db_erros.php?fechar=true&db_erro=data inicial maior que a data final.');
   }else{
     $sWhere .= " and  h64_data between  '{$dataIniAux[2]}-{$dataIniAux[1]}-{$dataIniAux[0]}' and '{$dataFimAux[2]}-{$dataFimAux[1]}-{$dataFimAux[0]}'";
   }
}
if ($oGet->tipo == "l"){
  "lti=&ltf=   flt=0101,0102";
  if(isset($oGet->flt) && $oGet->flt != "") {
	   $sWhere .= " and r70_estrut in ('".str_replace(",","','",$oGet->flt)."') ";
     $head7 = "LOTAÇÃO : {$oGet->flt}";
     
  }elseif((isset($oGet->lti) && $oGet->lti != "" ) && (isset($oGet->ltf) && $oGet->ltf != "")){
    
	   $sWhere .= " and r70_estrut between '{$oGet->lti}' and '{$oGet->ltf}' ";
     $head7 = "LOTAÇÃO : ".$oGet->lti." A ".$oGet->ltf;
	}else if(isset($oGet->lti) && $oGet->lti != ""){
	   $sWhere .= " and r70_estrut >= '{$oGet->lti}' ";
     $head7 = "LOTAÇÃO : {$oGet->lti} A 9999";
	}else if(isset($oGet->ltf) && $oGet->ltf != ""){
	   $sWhere .= " and r70_estrut <= '{$oGet->ltf}'";
     $head7 = "LOTAÇÃO : 0  A {$oGet->ltf}";
	}else{
     $head7 = "LOTAÇÃO : 0  A 9999";
  }
  $inner_join =  " inner join rhlota on r70_codigo = rh02_lota
						                        and r70_instit = rh02_instit";
}elseif ($oGet->tipo == "t"){
  "lci=&lcf=   flc=13004,13006 ";
  if(isset($oGet->flc) && $oGet->flc != "" ) {
	   $sWhere .= " and rh55_estrut in ('".str_replace(",","','",$oGet->flc)."') ";
     $head7 = "LOCAL TRAB. : {$oGet->flc}";
  }elseif((isset($oGet->lci) && $oGet->lci != "" ) && (isset($oGet->lcf) && $oGet->lcf != "")){
	   $sWhere .= " and rh55_estrut between '{$oGet->lci}' and '{$oGet->lcf}' ";
     $head7 = "LOCAL TRAB. : {$oGet->lci} A {$oGet->lcf}";
	 }else if(isset($oGet->lci) && $oGet->lci != ""){
	   $sWhere .= " and rh55_estrut >= '{$oGet->lci}' ";
     $head7 = "LOCAL TRAB. : {$oGet->lci} A 0";
	 }else if(isset($oGet->lcf) && $oGet->lcf != ""){
	   $sWhere .= " and rh55_estrut <= '{$oGet->lcf}' ";
     $head7 = "LOCAL TRAB. : 0 A {$oGet->lcf}";
	}else{
     $head7 = "LOCAL TRAB. : 0  A 9999";
	 }
  $inner_join = "  inner join  rhpeslocaltrab on rh56_seqpes = rh02_seqpes  
			                                       and rh56_princ = 't'
                   inner join rhlocaltrab     on rh55_codigo = rh56_localtrab
		                                         and rh55_instit = {$iInstit} "; 
}elseif ($oGet->tipo == "o"){
  "ori=&orf=  for=2,4";
  if(isset($oGet->for) && $oGet->for != "") {
	   $sWhere .= " and o40_orgao in ({$oGet->for}) ";
     $head7 = "ORGÃOS : {$oGet->for}";
  }elseif((isset($ori) && $oGet->ori != "" ) && (isset($oGet->orf) && $oGet->orf != "")){
	   $sWhere .= " and o40_orgao between {$oGet->ori} and {$oGet->orf}";
     $head7 = "ORGÃOS : $oGet->ori A {$oGet->orf}";
	}else if(isset($oGet->ori) && $oGet->ori != ""){
	   $sWhere .= " and o40_orgao >= {$oGet->ori}";
     $head7 = "ORGÃOS : {$oGet->ori} A 9999";
	}else if(isset($oGet->orf) && $oGet->orf != ""){
	   $sWhere .= " and o40_orgao <= {$oGet->orf} ";
     $head7 = "ORGÃOS : 0 A {$oGet->orf}";
	}else{
     $head7 = "ORGÃOS : 0  A 9999";
	}
  $inner_join =  " inner join rhlota     on r70_codigo  = rh02_lota
									                      and r70_instit  = rh02_instit
			             left join  rhlotaexe  on rh26_codigo = r70_codigo 
									                      and rh26_anousu = {$oGet->anofolha}
		               left join  orcorgao   on o40_orgao   = rh26_orgao 
					                              and o40_anousu  = {$oGet->anofolha}
			                                  and o40_instit  = rh02_instit "; 
}
$sSQLRel  = "select h57_regist,";
$sSQLRel .= "       z01_nome, ";
$sSQLRel .= "       h64_sequencial, ";
$sSQLRel .= "       h64_data, ";
$sSQLRel .= "       h64_seqaval, ";
$sSQLRel .= "       (case when h56_sequencial is null then 'Não' ";
$sSQLRel .= "             else 'Sim' end) as Aplicada, " ;    
$sSQLRel .= "       fc_calculapontosestagio(h64_sequencial,'a') as pontos";
$sSQLRel .= "  from rhestagioagenda";
$sSQLRel .= "       inner join rhestagioagendadata on h64_estagioagenda = h57_sequencial";
$sSQLRel .= "       left outer join rhestagioavaliacao on h56_rhestagioagenda = h64_sequencial";
$sSQLRel .= "       inner join rhpessoal on h57_regist     = rh01_regist ";
$sSQLRel .= "       inner join cgm on z01_numcgm           = rh01_numcgm ";
$sSQLRel .= "       inner join rhpessoalmov on rh02_regist = rh01_regist ";
$sSQLRel .= "                              and rh02_anousu = {$oGet->anofolha}";
$sSQLRel .= "                              and rh02_mesusu = {$oGet->mesfolha}";
$sSQLRel .= "                              and rh02_instit = {$iInstit}";
$sSQLRel .= "       {$inner_join}";
$sSQLRel .= " where {$sWhere}";

$pdf   = new PDF(); 
$head1 = "RELATÓRIO DE AVALIAÇÕES";
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->addpage();
$pdf->setfillcolor(235);
$troca  = 1;
$alt    = 4;
$rsRel  = @pg_query($sSQLRel);
$iNumRows = pg_num_rows($rsRel);
if ($iNumRows > 0){

  for ($iAtual = 0; $iAtual < $iNumRows; $iAtual++){
   
     $oAval  = db_utils::fieldsMemory($rsRel,$iAtual);
     if ($pdf->getY() > $pdf->h -20  || $troca == 1){
         
         $pdf->setfont('arial','b',8);
         $pdf->cell(25,$alt,"Matrícula",1,0,"C",1);
         $pdf->cell(90,$alt,"Nome",1,0,"C",1);
         $pdf->cell(15,$alt,"Avaliação",1,0,"C",1);
         $pdf->cell(20,$alt,"Data",1,0,"C",1);
         $pdf->cell(20,$alt,"Aplicada",1,0,"C",1);
         $pdf->cell(20,$alt,"Pontos",1,1,"C",1);
         $troca = 0;
     }
     $pdf->setfont('arial','',8);
     $pdf->cell(25,$alt,$oAval->h57_regist,"TRB",0,"R");
     $pdf->setfont('arial','',7);
     $pdf->cell(90,$alt,$oAval->z01_nome  ,1,0);
     $pdf->setfont('arial','',8);
     $pdf->cell(15,$alt,$oAval->h64_seqaval,1,0,"C");
     $pdf->cell(20,$alt,db_formatar($oAval->h64_data,"d"),1,0,"C");
     $pdf->cell(20,$alt,$oAval->aplicada,1,0,"C");
     $pdf->cell(20,$alt,$oAval->pontos,"TLB",1,"R");
  }

}else{
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem avaliações com os filtros selecionados.');
}
$pdf->output();