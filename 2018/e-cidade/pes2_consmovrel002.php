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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_movrel_classe.php"));
require_once(modification("classes/db_convenio_classe.php"));
require_once(modification("classes/db_relac_classe.php"));
require_once(modification("classes/db_rhpessoal_classe.php"));
$clmovrel = new cl_movrel;
$clconvenio = new cl_convenio;
$clrelac = new cl_relac;
$clrhpessoal = new cl_rhpessoal;
$clrotulo = new rotulocampo;
$clmovrel->rotulo->label();
$clrotulo->label('z01_nome');
$clrotulo->label('rh05_recis');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head2 = "RELATÓRIO DE DADOS IMPORTADOS";
$head4 = "PERÍODO : ".$mes." / ".$ano;

$dbwhere = "and r54_instit = ".db_getsession('DB_instit');
$mais = 5;
if(isset($r54_codrel) && $r54_codrel != ""){
  $dbwhere .= " and r54_codrel = '".$r54_codrel."' ";
  $result_codrel = $clconvenio->sql_record($clconvenio->sql_query_file($r54_codrel,db_getsession('DB_instit'),"r56_descr as descrconv,r56_dirarq as diretorio"));
  if($clconvenio->numrows > 0){
    db_fieldsmemory($result_codrel,0);
    $head6 = "CONVÊNIO: ".$r54_codrel." - ".$descrconv;
    $mais ++;
  }
}

if(isset($r54_regist) && $r54_regist != "" || isset($rh01_regist) && trim($rh01_regist) != "" ){
  
  /**
   * If que verifica o retorno do ajax contido no arquivo pes1_lancapontofs001.php
   */
  if ( isset($rh01_regist) && trim($rh01_regist) != "" ) {
    $dbwhere .= " and r54_regist in ({$rh01_regist}) ";
    $result_codreg = $clrhpessoal->sql_record ($clrhpessoal->sql_query_cgm(null, "z01_nome as nomefunc",null, "rh01_regist in ({$rh01_regist}) "));
  } else {
    
    $dbwhere .= " and r54_regist = ".$r54_regist;
    $result_codreg = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm($r54_regist,"z01_nome as nomefunc"));
  }
  if($clrhpessoal->numrows > 0){
    db_fieldsmemory($result_codreg,0);
    $HEAD7 = "head".$mais;
    //$$HEAD7 = "MATRÍCULA: ".$r54_regist." - ".$nomefunc;
    $mais ++;
  }
}

if(isset($r54_codeve) && $r54_codeve != ""){
  $dbwhere .= " and r54_codeve = '".$r54_codeve."' ";
  $result_codeve = $clrelac->sql_record($clrelac->sql_query_file($r54_codeve,db_getsession("DB_instit"),"r55_descr as descrrelac"));
  if($clrelac->numrows > 0){
    db_fieldsmemory($result_codeve,0);
    $HEAD8 = "head".$mais;
    $$HEAD8 = "RELACIONAMENTO: ".$r54_codeve." - ".$descrrelac;
    $mais ++;
  }
}
if(isset($nao_lancados)){
  $HEAD9 = "head".$mais;
  $$HEAD9 = "Não lançados na folha";
  $dbwhere .= " and r54_lancad = 'f' ";
}

$sCamposMovRelDados = "r54_codrel,r54_codeve,r54_regist,z01_nome,r54_quant1,r54_quant2,r54_quant3,r54_lancad,rh05_recis";
$sOrdemMovRelDados  = "r54_lancad,z01_nome";
$sWhereMovRelDados  = "r54_anomes = '".$ano.$mes."' $dbwhere";
$sSqlMovRelDados    = $clmovrel->sql_query_dados(null, $sCamposMovRelDados, $sOrdemMovRelDados, $sWhereMovRelDados, $ano, $mes);

$result_dados = $clmovrel->sql_record($sSqlMovRelDados);
$numrows_dados = $clmovrel->numrows;
if($numrows_dados == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados importados no período de '.$mes.' / '.$ano);
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;

$total_reg = 0;
$total_qt1 = 0;
$total_qt2 = 0;
$total_qt3 = 0;

for($i = 0; $i < $numrows_dados;$i++){
  db_fieldsmemory($result_dados,$i);
  if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->addpage();
    $pdf->setfont('arial','b',8);
    $pdf->cell(15,$alt,$RLr54_codrel,1,0,"C",1);
    $pdf->cell(15,$alt,$RLr54_codeve,1,0,"C",1);
    $pdf->cell(18,$alt,$RLr54_regist,1,0,"C",1);
    $pdf->cell(60,$alt,$RLz01_nome  ,1,0,"C",1);
    $pdf->cell(15,$alt,$RLr54_quant1,1,0,"C",1);
    $pdf->cell(15,$alt,$RLr54_quant2,1,0,"C",1);
    $pdf->cell(15,$alt,$RLr54_quant3,1,0,"C",1);
    $pdf->cell(15,$alt,$RLr54_lancad,1,0,"C",1);
    $pdf->cell(25,$alt,$RLrh05_recis,1,1,"C",1);
    $troca = 0;
  }

  if($r54_lancad == 't'){
    $r54_lancad = "Sim";
  }else{
    $r54_lancad = "Não";
  }

  $pdf->setfont('arial','',7);
  $pdf->cell(15,$alt,$r54_codrel,1,0,"C",0);
  $pdf->cell(15,$alt,$r54_codeve,1,0,"C",0);
  $pdf->cell(18,$alt,$r54_regist,1,0,"C",0);
  $pdf->cell(60,$alt,$z01_nome  ,1,0,"L",0);
  $pdf->cell(15,$alt,db_formatar($r54_quant1,"f"),1,0,"R",0);
  $pdf->cell(15,$alt,db_formatar($r54_quant2,"f"),1,0,"R",0);
  $pdf->cell(15,$alt,db_formatar($r54_quant3,"f"),1,0,"R",0);
  $pdf->cell(15,$alt,$r54_lancad,1,0,"C",0);
  $pdf->cell(25,$alt,db_formatar($rh05_recis,"d"),1,1,"C",0);

  $total_reg ++;
  $total_qt1 += $r54_quant1;
  $total_qt2 += $r54_quant2;
  $total_qt3 += $r54_quant3;
}
$pdf->ln(1);
$pdf->cell(108,$alt,'Quantidade total  :  ',"TB",0,"L",1);
$pdf->cell(15,$alt,db_formatar($total_qt1,"f"),"TB",0,"R",1);
$pdf->cell(15,$alt,db_formatar($total_qt2,"f"),"TB",0,"R",1);
$pdf->cell(15,$alt,db_formatar($total_qt3,"f"),"TB",0,"R",1);
$pdf->cell(40,$alt,"","TB",1,"C",1);
$pdf->ln(1);
$pdf->cell(193,$alt,'Total de registros  :  '.$total_reg,"T",1,"L",0);

$pdf->Output();   
?>