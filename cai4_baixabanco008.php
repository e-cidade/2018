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
require_once("libs/db_utils.php");
$iInstit = db_getsession("DB_instit");

$sql  = "select distinct on(d.idret)                                                                            ";
$sql .= "       d.idret,                                                                                        ";
$sql .= "       d.k15_codbco,                                                                                   ";
$sql .= "       d.k15_codage,                                                                                   ";
$sql .= "       d.k00_numbco,                                                                                   ";
$sql .= "       a.k00_numpre,                                                                                   ";
$sql .= "       case when d.k00_numpar = 0 then 0                                                               ";
$sql .= "       else d.k00_numpar                                                                               ";
$sql .= "       end as k00_numpar,                                                                              ";
$sql .= "       d.k00_numpre as numpre,                                                                         ";
$sql .= "       d.vlrtot,                                                                                       ";
$sql .= "       case when classi = 'f' then 'Não' else 'Sim' end as classi,                                     ";
$sql .= "       coalesce(case when c2.k00_matric is null then c.k00_matric else c2.k00_matric end,0) as matric, ";
$sql .= "       coalesce(case when f.k00_inscr is null then f.k00_inscr else f2.k00_inscr end,0) as inscr,      ";
$sql .= "       coalesce(case                                                                                   "; 
$sql .= "                  when r.k00_numpre is not null                                                        ";
$sql .= "                    then r.k00_numcgm                                                                  ";
$sql .= "                  when a.k00_numpre is not null                                                        ";
$sql .= "                    then a.k00_numcgm                                                                  ";
$sql .= "                  when n.k00_numpre is not null                                                        ";
$sql .= "                    then n.k00_numcgm                                                                  ";
$sql .= "                end , 0) as numcgm                                                                     ";
$sql .= "	 from disbanco d                                                                                      ";
$sql .= "       left join arrecad    a  on a.k00_numpre  = d.k00_numpre                                         ";
$sql .= "       left join recibopaga r  on r.k00_numnov  = d.k00_numpre                                         ";
$sql .= "	      left join arrematric c  on c.k00_numpre  = r.k00_numpre                                         ";
$sql .= "	      left join arrematric c2 on c2.k00_numpre = d.k00_numpre                                         ";
$sql .= "       left join arreinscr  f  on f.k00_numpre  = r.k00_numpre                                         ";
$sql .= "       left join arreinscr  f2 on f2.k00_numpre = d.k00_numpre                                         ";
$sql .= "       left join arrenumcgm n  on n.k00_numpre  = d.k00_numpre                                         ";
$sql .= " where codret = $codret  ";
$sql .= "   and instit = $iInstit ";
if ($opcao == 'erros') {
      $emite = 'SOMENTE ERROS';
      $sql .= " and classi is false ";
//   	  $sql .= " and a.k00_numpre is null and r.k00_numnov is null ";
}else if($opcao == 'corretos') {
      $emite = 'SOMENTE CORRETOS';
      $sql .= " and classi is true "; 
//$sql .= " and ( not a.k00_numpre is null or not r.k00_numnov is null)";
}else { 
      $emite = 'TODOS REGISTROS';
}
$sql .= " order by idret ";
$result = db_query($sql) or die($sql);

$num = pg_numrows($result);

$sql1 = " select disarq.*,
                 nome 
		  from disarq 
		       left outer join db_usuarios on disarq.id_usuario = db_usuarios.id_usuario
		   where codret = $codret
         and instit = $iInstit ";

$result1 = db_query($sql1);
db_fieldsmemory($result1,0);

$head1 = "Usuário: ".$nome;
$head3 = "Identif. Arquivo : ".$codret;
$head5 = "Arquivo : ".$arqret;
$head7 = "DT.Arquivo : ".db_formatar($dtarquivo,'d')."   DT.Retorno : ".db_formatar($dtretorno,'d');
$head9 = "Conta : ".$k00_conta;
//$head8 = "RELATÓRIO DOS VALORES PAGOS";
//$head9 = $emite;

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->AddPage("L"); 
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','',11);
$linha = 0;
$pre = 0;
$total = 0;
$valor = 0;
$pagina = 0;

$pdf->SetFont('Arial','B',10);
$pdf->MultiCell(0,12,"RELATÓRIO DOS VALORES PAGOS"."  -  ".$emite,0,"C",0);
$pdf->SetFont('Arial','B',7);
$pdf->Cell(10,6,"BANCO",1,0,"C",1);
$pdf->Cell(15,6,"AGÊNCIA",1,0,"C",1);
$pdf->Cell(25,6,"BANCO",1,0,"C",1);
$pdf->Cell(15,6,"NUMPRE",1,0,"C",1);
$pdf->Cell(15,6,"PARCELA",1,0,"C",1);
$pdf->Cell(20,6,"N".CHR(176)." ARRECAD.",1,0,"C",1);
$pdf->Cell(30,6,"TOTAL PAGO",1,0,"C",1);
$pdf->Cell(20,6,"MATR/INSCR",1,0,"C",1);
$pdf->Cell(20,6,"NUMCGM",1,0,"C",1);
$pdf->Cell(20,6,"CLASS.",1,0,"C",1);
$pdf->Cell(87.5,6,"SITUACAO",1,1,"C",1);

for($i=0;$i<$num;$i++) {
   if($pdf->GetY() > ( $pdf->h - 30 )){
      $pdf->Text($pdf->w-20,$pdf->h-5, $pdf->PageNo());
      $pdf->AddPage("L");
      $pdf->SetFont('Arial','B',10);
      $pdf->MultiCell(0,12,"RELATÓRIO DOS VALORES PAGOS"."  -  ".$emite,0,"C",0);
//      $pdf->MultiCell(0,10,"COMPARATIVO DO VALOR VENAL",0,"C",0);
      $pdf->SetFont('Arial','B',7);
      $pdf->Cell(10,6,"BANCO",1,0,"C",1);
      $pdf->Cell(15,6,"AGÊNCIA",1,0,"C",1);
      $pdf->Cell(25,6,"NÚMERO DO BANCO",1,0,"C",1);
      $pdf->Cell(15,6,"NUMPRE",1,0,"C",1);
      $pdf->Cell(15,6,"PARCELA",1,0,"C",1);
      $pdf->Cell(20,6,"N".CHR(176)." ARRECAD.",1,0,"C",1);
      $pdf->Cell(30,6,"TOTAL PAGO",1,0,"C",1);
      $pdf->Cell(20,6,"MATR/INSCR",1,0,"C",1);
      $pdf->Cell(20,6,"NUMCGM",1,0,"C",1);
      $pdf->Cell(20,6,"CLASS.",1,0,"C",1);
      $pdf->Cell(87.5,6,"SITUACAO",1,1,"C",1);
      $linha = 0;
   }

   if($linha % 2 == 0){
     $pre = 0;
   }else {
     $pre = 1;
   }
   db_fieldsmemory($result,$i);
   if ( $matric != 0 ){
      $matins = $matric;
   }else if ( $inscr != 0 ){
      $matins = $inscr;
   }else{
      $matins = 0;
   }

   $situacao = "";
   $sqlbloqueado = " select ar22_sequencial 
                       from numprebloqpag
                      where ar22_numpre = $numpre 
                        and ar22_numpar = $k00_numpar";
   $resultbloqueado = db_query($sqlbloqueado) or die($sqlbloqueado);                        
   if (pg_numrows($resultbloqueado) > 0) {
     $situacao = "Numpre Bloqueado";   	
   }
   
   if ($situacao == "") {
      $sqlcanc = " select k21_codigo 
                     from cancdebitosreg
                    where k21_numpre = $numpre and k21_numpar = $k00_numpar 
                    limit 1";
      $resultcanc = db_query($sqlcanc) or die($sqlcanc);
      if (pg_numrows($resultcanc) == 0) {
         $sqlcanc = " select k21_codigo 
                        from cancdebitosreg 
                       inner join db_reciboweb on k99_numpre = k21_numpre and k99_numpar = k21_numpar
                       where k99_numpre_n = $numpre
                       limit 1";
         $resultcanc = db_query($sqlcanc) or die($sqlcanc);
         if (pg_numrows($resultcanc) > 0) {
            db_fieldsmemory($resultcanc,0);
            $situacao = "CANC $k21_codigo";
         }
      } else {
        db_fieldsmemory($resultcanc,0);
        $situacao = "CANC $k21_codigo";
      }
   }
   
   //CASO O DÉBITOS ESTEJA PAGO PEGA O NUMCGM DE QUEM PAGOU O DÉBITO
   if ($situacao == "") {
   	
     $sqlpaga = " select case when disbanco.dtpago is not null 
	                          then disbanco.dtpago 
							  else arrepaga.k00_dtpaga end as k00_dtpaga,
							  k00_numcgm as numcgm
                  from arrepaga
                  left join arreidret on arrepaga.k00_numpre = arreidret.k00_numpre and arrepaga.k00_numpar = arreidret.k00_numpar
                  left join disbanco  on disbanco.idret = arreidret.idret
                  where arrepaga.k00_numpre = $numpre and arrepaga.k00_numpar = $k00_numpar 
                  limit 1";
     $resultpaga = db_query($sqlpaga) or die($sqlpaga);
     if (pg_numrows($resultpaga) == 0) {
       $sqlpaga = " select case when disbanco.dtpago is not null 
	                            then disbanco.dtpago 
								else arrepaga.k00_dtpaga end as k00_dtpaga,
	                       k00_numcgm as numcgm
                    from arrepaga
                    inner join db_reciboweb on k99_numpre = arrepaga.k00_numpre and k99_numpar = arrepaga.k00_numpar
                    left join arreidret on arrepaga.k00_numpre = arreidret.k00_numpre and arrepaga.k00_numpar = arreidret.k00_numpar
                    left join disbanco  on disbanco.idret = arreidret.idret
                    where k99_numpre_n = $numpre
                    limit 1";
       $resultpaga = db_query($sqlpaga) or die($sqlpaga);
       if (pg_numrows($resultpaga) > 0) {
         db_fieldsmemory($resultpaga,0);
         $situacao = "PAGA EM " . db_formatar($k00_dtpaga,"d");
       }
     } else {
       db_fieldsmemory($resultpaga,0);
       $situacao = "PAGA EM " . db_formatar($k00_dtpaga,"d");
     }

   }

   /**
    * Procura numbco repetido pelo idret
    */
   if ( $situacao == "") {
     
     $sSqlDuplicados = "select array_to_string( array_accum(distinct arrebanco.k00_numbco ), ', ') as k00_numbco,           \n";
     $sSqlDuplicados.= "       count( arrebanco.k00_numbco )                                       as quantidade_arrebanco  \n";
     $sSqlDuplicados.= "  from disbanco                                                                                     \n";
     $sSqlDuplicados.= "       inner join arrebanco on trim(arrebanco.k00_numbco) = trim(disbanco.k00_numbco)               \n";
     $sSqlDuplicados.= " where idret = {$idret}                                                                             \n";
     $rsDuplicados   = db_query($sSqlDuplicados);      
     
     if ( !$rsDuplicados ) {        
       db_redireciona("db_erros.php?fechar=true&db_erro=". urlencode($sSqlDuplicados) ."Erro ao Buscar dados do Numbanco" . urlencode(pg_last_error()));
       exit();
     }

     if ( pg_num_rows($rsDuplicados) > 0 ) {

       $oResultado       = db_utils::fieldsMemory($rsDuplicados, 0);
       $sNumbcoDuplicado = $oResultado->k00_numbco;
       $iQuantidadeNumbco= $oResultado->quantidade_arrebanco;

       if ($iQuantidadeNumbco > 1 ) {
         $situacao         = "Registro (IDRET:{$idret}) com Numbco( {$sNumbcoDuplicado} ) Duplicado ";
       }
     }
   }

   if ($situacao != ""){
     
     $sSqlIptunumpold = "select * from iptunumpold where j130_numpre = {$numpre} ;";
     $rsIptunumpold    = db_query($sSqlIptunumpold);
     $aIptunumpold     = db_utils::getColectionByRecord($rsIptunumpold);
     
     if ( count($aIptunumpold) > 0 ) {     	
       $situacao = "RECALCULADO";
     }
   }
   $pdf->SetFont('Arial','',7);
   $pdf->Cell(10,4,$k15_codbco,0,0,"C",$pre);
   $pdf->cell(15,4,$k15_codage,0,0,"C",$pre);
   $pdf->Cell(25,4,$k00_numbco,0,0,"C",$pre);
   $pdf->Cell(15,4,$k00_numpre,0,0,"C",$pre);
   $pdf->Cell(15,4,$k00_numpar,0,0,"C",$pre);
   $pdf->Cell(20,4,$numpre,0,0,"C",$pre);
   $pdf->Cell(30,4,db_formatar($vlrtot,'f'),0,0,"R",$pre);
   $pdf->Cell(20,4,$matins,0,0,"C",$pre);
   $pdf->Cell(20,4,$numcgm,0,0,"C",$pre);
   $pdf->Cell(20,4,$classi,0,0,"C",$pre);
   $pdf->Cell(87.5,4,$situacao,0,1,"L",$pre);
   $total += 1;
   $linha += 1;
   $valor += $vlrtot;
}
$pdf->Ln(5);
$pdf->Cell(90,6,"Total de Registros :   ".$total,"TB",0,"L",0);
$pdf->Cell(30,6,db_formatar($valor,'f'),"TB",0,"R",0);
$pdf->cell(0,6,' ',"TB",1,"L",0);
$pdf->Output();