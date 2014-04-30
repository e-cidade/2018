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
include('libs/db_utils.php');

//parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);

$sData   = date('Y-m-d');
$sWhere  = "";
$sAnd    = "";

if((isset($logs)) && ($logs != '')) {
  
  $sWhere .= "{$sAnd} issruas.j14_codigo in ($logs) ";
  $sAnd    = " and ";
}

if((isset($iPessoa)) && ($iPessoa!='T')) {
  
  //filtro tipo de pessoa 't'rue=fisica, 'f'alse=jurida
  if($iPessoa == 't') {
    $maioroumenor = '<=';
  }else {
    $maioroumenor = '>';
  }
  
  $sWhere  .= "{$sAnd} length(trim(z01_cgccpf)) $maioroumenor 11 ";     
  $sAnd     = " and ";
  
  if($iPessoa == 't') {
    $headPessoa = 'Física';
  } else {
    $headPessoa = 'Jurídica';
  }
}else {
  $headPessoa = 'Física / Jurídica';
}

$headProcesso = 'Com Processo / Sem Processo';
if ((isset($iProcesso)) && ($iProcesso == 'C')) {
  
  //filtro tipo de processo C=com processo, S=sem processo, T=Ambos
  $sWhere      .= "{$sAnd} q14_proces is not null ";
  $sAnd         = " and ";
  $headProcesso = 'Com Processo';
}

if((isset($iProcesso)) && ($iProcesso == 'S')) {
  
  $sWhere      .= "{$sAnd} q14_proces is null ";
  $sAnd         = " and ";
  $headProcesso = 'Sem Processo';
}

if((isset($sSituacao))) {

  $iTam         = 20;
  $headSituacao = 'Todos';
  if ($sSituacao == 'A') {

    $iTam         = 30;
    $headSituacao = 'Ativas';
    $sWhere      .= "{$sAnd} ( issbase.q02_dtbaix is null ";
    $sWhere      .= "    or issbase.q02_dtbaix >= '{$sData}' ) ";
    $sAnd         = " and ";
  } else if ($sSituacao == 'B') {

    $headSituacao = 'Baixadas';
    $sWhere      .= "{$sAnd} issbase.q02_dtbaix is not null ";
    $sWhere      .= "    and issbase.q02_dtbaix < '{$sData}' ";
    $sAnd         = " and ";
  }
}

if (!empty($sWhere)) {
  $sWhere = "where {$sWhere}";
}

$sqlQuery  = "    select distinct                                                                         ";
$sqlQuery .= "           issbase.q02_inscr,                                                               ";
$sqlQuery .= "           cgm.z01_nome,                                                                    ";
$sqlQuery .= "           tabativ.q07_ativ,                                                                ";
$sqlQuery .= "           ativid.q03_descr,                                                                ";
$sqlQuery .= "           ruas.j14_nome,                                                                   ";
$sqlQuery .= "           issbase.q02_numcgm,                                                              ";
$sqlQuery .= "           cgm.z01_cgccpf,                                                                  ";
$sqlQuery .= "           tabativ.q07_datain,                                                              ";
$sqlQuery .= "           issruas.q02_numero,                                                              ";
$sqlQuery .= "           issruas.q02_compl,                                                               ";
$sqlQuery .= "           issquant.q30_quant,                                                              ";
$sqlQuery .= "           issquant.q30_area,                                                               ";
$sqlQuery .= "           issprocesso.q14_proces,                                                          ";
$sqlQuery .= "           issbase.q02_dtbaix,                                                              ";
$sqlQuery .= "           case                                                                             ";
$sqlQuery .= "             when ( issbase.q02_dtbaix is not null and issbase.q02_dtbaix < '{$sData}' )    ";
$sqlQuery .= "               then 'Baixada'                                                               ";
$sqlQuery .= "             else 'Ativa'                                                                   ";
$sqlQuery .= "           end as situacao                                                                  ";
$sqlQuery .= "      from issbase                                                                          ";
$sqlQuery .= "           inner join ativprinc    on ativprinc.q88_inscr   = issbase.q02_inscr             ";
$sqlQuery .= "           inner join tabativ      on ativprinc.q88_inscr   = tabativ.q07_inscr             ";
$sqlQuery .= "                                  and ativprinc.q88_seq     = tabativ.q07_seq               ";
$sqlQuery .= "           inner join ativid       on tabativ.q07_ativ      = ativid.q03_ativ               ";
$sqlQuery .= "           inner join cgm          on issbase.q02_numcgm    = cgm.z01_numcgm                ";
$sqlQuery .= "           inner join issruas      on issruas.q02_inscr     = issbase.q02_inscr             ";
$sqlQuery .= "           inner join ruas         on ruas.j14_codigo       = issruas.j14_codigo            ";
$sqlQuery .= "           left  join issquant     on issbase.q02_inscr     = issquant.q30_inscr            ";
$sqlQuery .= "           left  join issprocesso  on issbase.q02_inscr     = issprocesso.q14_inscr         ";
$sqlQuery .= "  {$sWhere}                                                                                 ";
$sqlQuery .="   order by ruas.j14_nome,issruas.q02_numero                                                 ";

//die($sqlQuery);

$result       = db_query($sqlQuery);
$numrows = pg_num_rows($result);

if(!isset($numrows) || $numrows == 0){
    db_redireciona('db_erros.php?fechar=true&db_erro=Não foi encontrado nenhum registro para o filtro selecionado.');
}

$pdf = new PDF('L'); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$head1 = "Relatório de Inscrições ISS";
$head2 = "Filtros";
$head3 = "Tipo de Pessoa: {$headPessoa}";
$head4 = "Processo: {$headProcesso}";
$head5 = "Situação das Inscrições: {$headSituacao}";

$troca = 1;
for($i=0;$i<$numrows; $i++) {

   if ($pdf->gety() > $pdf->h - 40 || $troca != 0 ) {
     $pdf->AddPage();
    
     $pdf->SetTextColor(0,0,0);
     $pdf->SetFillColor(220);
     $pdf->setfont('arial','B',8);
        
     $pdf->Cell(20, 6, "Inscrição", 1, 0, "C", 1);
     $pdf->Cell(90, 6, 'Nome', 1, 0, "C", 1);
     $pdf->Cell(30, 6, 'Atividade', 1, 0, "C", 1);
     $pdf->Cell(50, 6, 'Descrição', 1, 0, "C", 1);
     $pdf->Cell(90, 6, 'Rua',  1, 1, "C", 1);
//      $pdf->Ln();  
     $pdf->Cell($iTam, 6, 'CGM', 1, 0, "C", 1);
     $pdf->Cell(40, 6, 'CNPJ/CPF',  1, 0, "C", 1);
     $pdf->Cell(30, 6, 'Data Inclusão', 1, 0, "C", 1);
     $pdf->Cell($iTam, 6, 'Número',  1, 0, "C", 1);
     $pdf->Cell(50, 6, 'Complemento', 1, 0, "C", 1);
     $pdf->Cell(30, 6, 'Quantidade',  1, 0, "C", 1);
     $pdf->Cell(20, 6, 'Área',  1, 0, "C", 1);
     $pdf->Cell(30, 6, 'Processo', 1, 0, "C", 1);
     
     if ($sSituacao != 'A') {
       $pdf->Cell(20, 6, 'Data Baixa', 1, 0, "C", 1);
     }
     $pdf->Cell(20, 6, 'Situação', 1, 1, "C", 1);
     
     $pdf->Ln(2);
    
     $troca = 0;
   }

  if($i % 2 == 0){
    $corfundo = 255;
  } else {
    $corfundo = 240;  
  }
  $pdf->SetFillColor($corfundo);
    $pdf->setfont('arial','',8);
  
    $oInscricoes = db_utils::fieldsMemory($result,$i);
    
  $oInscricoes->q07_datain = db_formatar($oInscricoes->q07_datain, 'd');
  
  $pdf->cell(20,6,substr($oInscricoes->q02_inscr,0,10), '0',0,"C",1);
  $pdf->cell(90,6,substr($oInscricoes->z01_nome,0,45),0,0,"C",1);
  $pdf->cell(30,6,substr($oInscricoes->q07_ativ,0,15),0,0,"C",1);
  $pdf->cell(50,6,substr($oInscricoes->q03_descr,0,25),0,0,"C",1);
  $pdf->cell(90,6,substr($oInscricoes->j14_nome,0,45),0,1,"C",1);
//  $pdf->Ln();
  $pdf->cell($iTam,6,substr($oInscricoes->q02_numcgm,0,15),0,0,"C",1);
  
  $tipo     = '';
  $cnpjcpf = '';
  if((strlen(trim($oInscricoes->z01_cgccpf)) <= 11) and (strlen(trim($oInscricoes->z01_cgccpf)!=''))){
    $tipo   = 'cpf';
    $cnpjcpf  = substr(db_formatar($oInscricoes->z01_cgccpf, $tipo),0,20);
    
  }elseif(strlen(trim($oInscricoes->z01_cgccpf) > 11)) {
    $tipo   = 'cnpj';
    $cnpjcpf  = substr(db_formatar($oInscricoes->z01_cgccpf, $tipo),0,20);
  }

  $pdf->cell(40,6, $cnpjcpf,0,0,"C",1);

  $pdf->cell(30,6,substr($oInscricoes->q07_datain,0,15),0,0,"C",1);
  $pdf->cell($iTam,6,substr($oInscricoes->q02_numero,0,15),0,0,"C",1);
  $pdf->cell(50,6,substr($oInscricoes->q02_compl,0,25),0,0,"C",1);
  $pdf->cell(30,6,substr($oInscricoes->q30_quant,0,15),0,0,"C",1);
  $pdf->cell(20,6,substr($oInscricoes->q30_area,0,20),0,0,"C",1);
  $pdf->cell(30,6,substr($oInscricoes->q14_proces,0,15),0,0,"C",1);
  
  if ($sSituacao != 'A') {
    $pdf->cell(20,6,db_formatar($oInscricoes->q02_dtbaix,'d'),0,0,"C",1);
  }
  $pdf->cell(20,6,$oInscricoes->situacao,0,1,"C",1);
  //$pdf->Ln();
} 
$pdf->SetFillColor(255);
$pdf->cell("",6,"Total de Registros: $numrows",0,0,"R",1);
$pdf->Output();
?>