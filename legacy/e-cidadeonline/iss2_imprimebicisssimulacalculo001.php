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

require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_isssimulacalculo_classe.php");
require_once("classes/db_isssimulacalculoatividade_classe.php");

$oJson                      = new services_json();
$oIssSimulaCalculo          = new cl_isssimulacalculo();
$oIssSimulaCalculoAtividade = new cl_isssimulacalculoatividade();

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$sSqlMunic = "select munic from db_config where codigo = ".db_getsession("DB_instit");
$rsMunic   = db_query($sSqlMunic);
$sMunic    = db_utils::fieldsMemory($rsMunic,0)->munic; 


$sSqlSimulaCalculo  = $oIssSimulaCalculo->sql_query($oGet->iSimulacao);
$rsSimulacaoCalculo = $oIssSimulaCalculo->sql_record($sSqlSimulaCalculo);
$iLinhasConsulta    = $oIssSimulaCalculo->numrows;
if($iLinhasConsulta == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.");
}

$sSqlSimulaCalculoAtividade  = $oIssSimulaCalculoAtividade->sql_query(null, "*", "q131_seq", "q131_issimulacalculo = {$oGet->iSimulacao}");
$rsSimulacaoCalculoAtividade = $oIssSimulaCalculoAtividade->sql_record($sSqlSimulaCalculoAtividade);
$iLinhasConsultaAtividade    = $oIssSimulaCalculoAtividade->numrows;


$head4 = "BIC simulada de Alvara";

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$alt = 4;
$pri = true;

for ($iInd = 0;$iInd < $iLinhasConsulta;$iInd++){
 $oDados = db_utils::fieldsmemory($rsSimulacaoCalculo,$iInd);
 
 if (($pdf->gety() > $pdf->h -30)  || $pri==true ){
     $pdf->addpage("");
     $pdf->setfillcolor(235);
     $titulo = 9;
     $texto = 8;

     $pdf->setX(5);
     $pdf->SetFont('Arial','B',$titulo);
     $pdf->Cell(200,4,"Dados Cadastrais da Simulação de Alvará","LRBT",1,"C",0);
     $pdf->setX(5);
     $pdf->Cell(200,4,"","",1,"C",0);
     
     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Nome:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$oDados->q130_razaosocial","",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);
     
     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"CNPJ/CPF:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$oDados->q130_cnpjcpf","",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);
   
     //lado esquerdo da tela
     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Endereço:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$oDados->j14_nome, N° $oDados->q130_numero","",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);

     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Complemento:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$oDados->q130_complemento","",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);
     
     //lado esquerdo da tela
     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Bairro:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$oDados->j13_descr","",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);     
     
     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Fone:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$oDados->q130_telefone","",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);
     
     //lado esquerdo da tela
     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Cidade:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$sMunic","",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);
     
     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"E-mail:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$oDados->q130_email","",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);

     //lado esquerdo da tela
     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Cep:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,@$oDados->j29_cep,"",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);
          
     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Data do cadastro:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,db_formatar($oDados->q130_datainicio,"d"),"",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);     

     //lado direito da tela
     $pdf->setX(105);
     $pdf->Cell(60,10,"","",1,"L",0);
          
// fim      
} 
 
 if (($pdf->gety() > $pdf->h -30)  || $pri==true ){
     //$pdf->addpage("");
     $pdf->setfillcolor(235);
     $titulo = 9;
     $texto = 8;
     
     //lado esquerdo da tela
     $pdf->setX(5);
     $pdf->SetFont('Arial','B',$titulo);
     $pdf->Cell(200,4,"Dados da Simulação de Alvará","LRBT",1,"C",0);
     $pdf->setX(5);
     $pdf->Cell(200,4,"","",1,"C",0);
     
     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Simulação:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$oDados->q130_sequencial","",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);
     
     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Data Inicial:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,db_formatar($oDados->q130_datainicio,"d"),"",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);
   
     //lado esquerdo da tela
     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Empregados:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$oDados->q130_empregados","",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);

     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Area","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,"$oDados->q130_area","",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);
     
     //lado esquerdo da tela
     $pdf->setX(10);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Zona Fiscal:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,$oDados->q130_zona."-".$oDados->j50_descr,"",0,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",0,"L",0);     
     
     //lado direito da tela
     $pdf->setX(105);
     $pdf->SetFont('Arial','',$titulo);
     $pdf->Cell(30,4,"Escritório:","",0,"L",1);
     $pdf->SetFont('Arial','',$texto);
     $pdf->Cell(60,4,$oDados->q86_numcgm." - ".$oDados->z01_nome,"",1,"L",0);
     $pdf->Cell(30,1,"","",0,"R",0);
     $pdf->Cell(60,1,"","",1,"L",0);

  }
}  

$pdf->Cell(180,3,"","",1,"L",0);
$pdf->Cell(200,4,"","",1,"C",0);
$pdf->setX(5);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(200,4,"Atividades","LRBT",1,"C",0);
$pdf->setX(5);
$pdf->Cell(200,4,"","",1,"C",0);

if($iLinhasConsultaAtividade > 0) {
  
   $pdf->setX(10);
   $pdf->SetFont('Arial','',$titulo);
   $pdf->cell(15, 4, "Cod."       ,0, 0, "C", 1);
   $pdf->cell(95, 4, "Atividade"  ,0, 0, "C", 1);
   $pdf->cell(10, 4, "Tipo"       ,0, 1, "C", 1);
  
   for ($iInd = 0; $iInd < $iLinhasConsultaAtividade; $iInd++){
     
     $oDadosAtividade = db_utils::fieldsmemory($rsSimulacaoCalculoAtividade,$iInd);
     
     $sTipoAtividade = "S";
     if ( $oDadosAtividade->q131_principal == "t" ) {
       $sTipoAtividade = "P";
     }
     
     $pdf->setX(10);
     $pdf->SetFont('Arial','',$texto);
     $pdf->cell(15 , 4 , $oDadosAtividade->q131_atividade , 0, 0, "C", 0);
     $pdf->cell(95 , 4 , $oDadosAtividade->q03_descr      , 0, 0, "L", 0);
     $pdf->cell(10 , 4 , $sTipoAtividade                  , 0, 1, "L", 0);
		 
   }
      
} else {
  
  $pdf->cell(190,4,"NÃO POSSUI ATIVIDADE",0,1,"C",0);
  
}

$pdf->Cell(180,3,"","",1,"L",0);
$pdf->Cell(200,4,"","",1,"C",0);
$pdf->setX(5);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(200,4,"Dados da Simulação do Calculo","LRBT",1,"C",0);
$pdf->setX(5);
$pdf->Cell(200,4,"","",1,"C",0);

$oCalculo = $oJson->decode(str_replace("\\","",$oGet->oCalculo));

$sTipoCalculo = "";
foreach ($oCalculo as $oDadosCalculo) {
  
  if ($sTipoCalculo <> $oDadosCalculo->sDescricaoCalculo){
    
    $pdf->setX(10);
    $pdf->SetFont('Arial','',$titulo);
    $pdf->cell(20 , 4, "Calculo:"                         , "T", 0, "L", 1);
    $pdf->cell(100, 4, $oDadosCalculo->sDescricaoCalculo  , "T", 1, "L", 1);
    
    $pdf->cell(30 , 4, "Parcela"                         , "T", 0, "C", 1);
    $pdf->cell(45 , 4, "Vencimento"                      , "T", 0, "C", 1);
    $pdf->cell(45 , 4, "Valor"                           , "T", 1, "C", 1);
    
    $sTipoCalculo = $oDadosCalculo->sDescricaoCalculo;
  }

  $pdf->cell(30 , 4, $oDadosCalculo->iParcela                     , 0, 0, "C", 0);
  $pdf->cell(45 , 4, db_formatar($oDadosCalculo->dVencimento,"d") , 0, 0, "C", 0);
  $pdf->cell(45 , 4, db_formatar($oDadosCalculo->nValor,"f")      , 0, 1, "C", 0);
  
}


$pdf->Output();

?>