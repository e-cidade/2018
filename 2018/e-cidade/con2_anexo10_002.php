<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

include(modification("fpdf151/pdf.php"));
include(modification("fpdf151/assinatura.php"));
include(modification("libs/db_sql.php"));
include(modification("libs/db_liborcamento.php"));
include(modification("libs/db_libcontabilidade.php"));
include(modification("dbforms/db_funcoes.php"));

$classinatura = new cl_assinatura;

// pesquisa a conta mae da receita

$tipo_mesini = 1;
$tipo_mesfim = 1;


$tipo_impressao = 1;
// 1 = orcamento
// 2 = balanco



parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);

$xinstit = split("-",$db_selinstit);
$resultinst = db_query("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
    if (strlen(trim($nomeinstabrev)) > 0){
         $descr_inst .= $xvirg.$nomeinstabrev;
         $flag_abrev  = true;
    } else {
         $descr_inst .= $xvirg.$nomeinst;
    }
        $xvirg = ', ';
}

if($origem == "O"){
    $xtipo = "ORÇAMENTO";
}else{
    $xtipo = "BALANÇO";
    if($opcao == 3)
      $head5 = "ANEXO 10 - PERÍODO : ".db_formatar($perini,'d')." A ".db_formatar($perfin,'d') ;
    else
      $head5 = "ANEXO 10 - PERÍODO : ".strtoupper(db_mes(substr($perini,5,2)))." A ".strtoupper(db_mes(substr($perfin,5,2)));
}

$head2 = "COMPARATIVO DA RECEITA ORÇADA COM A ARRECADADA";
$head3 = "EXERCÍCIO: ".db_getsession("DB_anousu");

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,100);
     }
}

$head4 = "INSTITUIÇÕES : ".$descr_inst;
$head6 = "PREVISÃO DA RECEITA : ".strtoupper($previsaoreceita);

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;

//$sql = "select * from work order by elemento";
//$result = db_query($sql);
$anousu  = db_getsession("DB_anousu");
if($opcao == 2){
  $dataini = $perini;
  $datafin = db_getsession("DB_anousu").'-'.date('m',mktime(0,0,0,substr($perfin,5,2),substr($perfin,8,2),substr($perfin,0,4))).'-'.date('t',mktime(0,0,0,substr($perfin,5,2),substr($perfin,8,2),substr($perfin,0,4)));
}else{
  $dataini = $perini;
  $datafin = $perfin;
}

$result = db_receitasaldo(11,1,3,true,'o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')',$anousu,$dataini,$datafin);

//db_criatabela($result); exit;
if(pg_numrows($result) == 0)
db_redireciona('db_erros.php?fechar=true&db_erro=Movimentação no período de '.db_formatar($dataini,'d').' a '.db_formatar($datafin,'d'));
$pagina = 1;
$tottotal = 0;

$total_para_mais   = 0;
$total_para_menos  = 0;
(float)$saldo_relatorio = 0;
$total_saldo_inicial    = 0;
$total_saldo_arrecadado = 0;
for($i=0;$i < pg_numrows($result);$i++){
  db_fieldsmemory($result,$i);
  $saldo_relatorio = $previsaoreceita=="atualizada"?$saldo_inicial_prevadic:$saldo_inicial;
  $elemento = $o57_fonte;
  $descr    = $o57_descr;
 
 
      
//  if($o57_fonte == '400000000000000' || $o57_fonte == '900000000000000'){
  if(db_conplano_grupo($anousu,$o57_fonte,9004) == true){
  	 // pega o total que já vem calculado na função
     $total_saldo_inicial    += $saldo_relatorio;
     $total_saldo_arrecadado += $saldo_arrecadado;         
     if ($saldo_arrecadado > $saldo_relatorio ){
  	    $total_para_mais += $saldo_relatorio - $saldo_arrecadado;
     } 	
     if ($saldo_arrecadado < $saldo_relatorio){
  	    $total_para_menos += $saldo_relatorio - $saldo_arrecadado;
     } 

     continue;
  }

  if($pdf->gety()>$pdf->h-30 || $pagina ==1){
    $pagina = 0;
    $pdf->addpage();
    $pdf->setfont('arial','b',6);
    $pdf->cell(24,$alt,"RECEITA","B",0,"L",0);
    $pdf->cell(75,$alt,"DESCRIÇÃO","B",0,"L",0);
    $pdf->cell(20,$alt,"ORÇADO","B",0,"R",0);
    $pdf->cell(20,$alt,"ARRECADADO","B",0,"R",0);
    $pdf->cell(20,$alt,"PARA MAIS","B",0,"R",0);
    $pdf->cell(20,$alt,"PARA MENOS","B",1,"R",0);
    $pdf->ln(3);
  
  }
  $pdf->setfont('arial','',6);
  $pdf->cell(24,$alt,db_formatar($elemento,'receita'),0,0,"L",0);
  $pdf->cell(75,$alt,$descr,0,0,"L",0);
  $pdf->cell(20,$alt,db_formatar($saldo_relatorio,'f'),0,0,"R",0);
  $pdf->cell(20,$alt,db_formatar($saldo_arrecadado,'f'),0,0,"R",0);
  $para_mais = 0;
  $para_menos= 0;
  if ($saldo_arrecadado > $saldo_relatorio ){
  	  $para_mais = $saldo_relatorio - $saldo_arrecadado;
  } 	
  if ($saldo_arrecadado < $saldo_relatorio){
  	  $para_menos = $saldo_relatorio - $saldo_arrecadado;
  }	
  $pdf->cell(20,$alt,db_formatar($para_mais,'f'),0,0,"R",0);
  $pdf->cell(20,$alt,db_formatar($para_menos,'f'),0,1,"R",0); 

}
$pdf->setfont('arial','B',6);
$pdf->cell(24,$alt,'',0,0,"L",0);
$pdf->cell(75,$alt,'TOTAL ',0,0,"L",0);
//$pdf->cell(10,$alt,'',0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($total_saldo_inicial,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($total_saldo_arrecadado,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($total_para_mais,'f'),0,0,"R",0);
$pdf->cell(20,$alt,db_formatar($total_para_menos,'f'),0,1,"R",0);




$pdf->Ln(25);

assinaturas($pdf,$classinatura,'BG');



$pdf->Output();


?>
