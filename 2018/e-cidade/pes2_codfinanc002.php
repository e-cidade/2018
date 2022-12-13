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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_rhrubricas_classe.php");
$oGet = db_utils::postMemory($_GET);

$clrhrubricas = new cl_rhrubricas;

$sCamposRubricas = " rh27_rubric,
                     rh27_descr,
                     case 
                       when rh27_pd = 1  then 'PROVENTO' 
                       when rh27_pd = 2  then 'DESCONTO'
                       else 'BASE' 
                     end as rh27_pd";
$rsRubrica = $clrhrubricas->sql_record($clrhrubricas->sql_query_file($oGet->rubrica,db_getsession("DB_instit"),$sCamposRubricas));
if ($clrhrubricas->numrows > 0) {
  $oDadosRubrica = db_utils::fieldsMemory($rsRubrica,0);
} else {
  db_redireciona("db_erros.php?fechar=true&db_erro=Rubrica não encontrada");
}

$sql = "select distinct 
               rh01_regist,
               z01_nome,
               {$oGet->sigla}_valor as valor,
               {$oGet->sigla}_quant as quant,
               r70_codigo,
               r70_descr 
          from {$oGet->arquivo} 
               inner join rhrubricas   on rh27_rubric = {$oGet->arquivo}.{$oGet->sigla}_rubric
							                        and rh27_instit = ".$sigla."_instit
               inner join rhpessoal    on rh01_regist = {$oGet->arquivo}.{$oGet->sigla}_regist
							 inner join rhpessoalmov on rh02_anousu = {$oGet->ano}
							                        and rh02_mesusu = {$oGet->mes}
																			and rh02_regist = rh01_regist
							                        and rh02_instit = ".db_getsession("DB_instit")." 
               inner join cgm          on z01_numcgm  = rhpessoal.rh01_numcgm
               inner join rhlota       on r70_codigo  = rh02_lota
							                        and r70_instit  = rh02_instit
          where     {$oGet->sigla}_rubric = '{$oGet->rubrica}' 
                and {$oGet->sigla}_anousu = {$oGet->ano} 
                and {$oGet->sigla}_mesusu = {$oGet->mes}
								and {$oGet->sigla}_instit = ".db_getsession("DB_instit")."
          order by z01_nome";
$rsDados = db_query($sql);
$numrows = pg_numrows($rsDados); 
if($numrows == 0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado");
}

$head3 = "FICHA FINANCEIRA POR CÓDIGO";
$head4 = "PERIODO : ".db_formatar($oGet->mes,'s','0',2,'e').'/'.$oGet->ano;
$head5 = "RUBRICA";
$head6 = $oDadosRubrica->rh27_rubric ." - ". $oDadosRubrica->rh27_descr." (".$oDadosRubrica->rh27_pd.")";

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$totalt = 0;
$valort = 0;
$quantt = 0;
$troca = 1;
$p = 1;
$alt = 4;
 
for($iInd=0;$iInd<$numrows;$iInd++){
  $oDados = db_utils::fieldsMemory($rsDados,$iInd);
  if($p==1){
    $p=0;   
  }else{
    $p=1;
  }
  if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->addpage("L");
    $pdf->setfont('arial','b',9);

    $pdf->cell(20,$alt,"Registro",1,0,"C",1);
    $pdf->cell(80,$alt,"Nome",1,0,"C",1);
    $pdf->cell(20,$alt,"Lotação",1,0,"C",1);
    $pdf->cell(80,$alt,"Descrição",1,0,"C",1);
    $pdf->cell(20,$alt,"Quantidade",1,0,"C",1);
    $pdf->cell(20,$alt,"Valor",1,1,"C",1);
    $pre = 1;
    $troca = 0;
  }
  $pdf->setfont('arial','',8);
  if($pre == 1){
    $pre = 0;
  }else{
    $pre = 1;
  }  
  $pdf->cell(20,$alt,$oDados->rh01_regist,0,0,"C",$pre);
  $pdf->cell(80,$alt,$oDados->z01_nome,0,0,"L",$pre);
  $pdf->cell(20,$alt,$oDados->r70_codigo,0,0,"C",$pre);
  $pdf->cell(80,$alt,$oDados->r70_descr,0,0,"L",$pre);
  $pdf->cell(20,$alt,$oDados->quant,0,0,"R",$pre);
  $pdf->cell(20,$alt,db_formatar($oDados->valor,"f"),0,1,"R",$pre);

  $totalt++;
  $valort+=$oDados->valor;
  $quantt+=$oDados->quant;
}
//$pdf->cell(278,1,"","T",1,"L",0);    
$pdf->setfont('arial','b',9);
$pdf->cell(180,$alt,"TOTAIS","T",0,"C",0);
$pdf->cell( 20,$alt,$totalt ,"T",0,"R",0);
$pdf->cell( 20,$alt,$quantt ,"T",0,"R",0);
$pdf->cell( 20,$alt,$valort ,"T",1,"R",0);
$pdf->Output();
?>