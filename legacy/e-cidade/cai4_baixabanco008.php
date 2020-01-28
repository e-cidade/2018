<?php

require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");

$iInstit = db_getsession("DB_instit");

$sSql  = "select distinct on(d.idret)                                                                            ";
$sSql .= "       d.idret,                                                                                        ";
$sSql .= "       d.k15_codbco,                                                                                   ";
$sSql .= "       d.k15_codage,                                                                                   ";
$sSql .= "       d.k00_numbco,                                                                                   ";
$sSql .= "       a.k00_numpre,                                                                                   ";
$sSql .= "       case when d.k00_numpar = 0 then 0                                                               ";
$sSql .= "       else d.k00_numpar                                                                               ";
$sSql .= "       end as k00_numpar,                                                                              ";
$sSql .= "       d.k00_numpre as numpre,                                                                         ";
$sSql .= "       d.vlrtot,                                                                                       ";
$sSql .= "       case when classi = 'f' then 'Não' else 'Sim' end as classi,                                     ";
$sSql .= "       coalesce(case when c2.k00_matric is null then c.k00_matric else c2.k00_matric end,0) as matric, ";
$sSql .= "       coalesce(case when f.k00_inscr is null then f.k00_inscr else f2.k00_inscr end,0) as inscr,      ";
$sSql .= "       coalesce(case                                                                                   ";
$sSql .= "                  when r.k00_numpre is not null                                                        ";
$sSql .= "                    then r.k00_numcgm                                                                  ";
$sSql .= "                  when a.k00_numpre is not null                                                        ";
$sSql .= "                    then a.k00_numcgm                                                                  ";
$sSql .= "                  when n.k00_numpre is not null                                                        ";
$sSql .= "                    then n.k00_numcgm                                                                  ";
$sSql .= "                end , 0) as numcgm                                                                     ";
$sSql .= "	 from disbanco d                                                                                     ";
$sSql .= "       left join arrecad    a   on a.k00_numpre  = d.k00_numpre                                        ";
$sSql .= "       left join recibopaga r   on r.k00_numnov  = d.k00_numpre                                        ";
$sSql .= "	     left join arrematric c   on c.k00_numpre  = r.k00_numpre                                        ";
$sSql .= "	     left join arrematric c2  on c2.k00_numpre = d.k00_numpre                                        ";
$sSql .= "       left join arreinscr  f   on f.k00_numpre  = r.k00_numpre                                        ";
$sSql .= "       left join arreinscr  f2  on f2.k00_numpre = d.k00_numpre                                        ";
$sSql .= "       left join arrenumcgm n   on n.k00_numpre  = d.k00_numpre                                        ";
$sSql .= " where codret = $codret                                                                                ";
$sSql .= "   and instit = $iInstit                                                                               ";

$sOpcaoEmissao = 'TODOS REGISTROS';
if ($opcao == 'erros') {

  $sOpcaoEmissao = 'SOMENTE ERROS';
  $sSql .= " and classi is false ";
}else if($opcao == 'corretos') {

  $sOpcaoEmissao = 'SOMENTE CORRETOS';
  $sSql .= " and classi is true ";
}

$sSql .= " order by idret ";

$result = db_query($sSql) or die($sSql);
$num    = pg_numrows($result);

$sSqlInstituicao = " select disarq.*, nome
          		         from disarq
          		              left join db_usuarios on disarq.id_usuario = db_usuarios.id_usuario
          		        where codret = $codret
                        and instit = $iInstit ";

$rsInstituicao   = db_query( $sSqlInstituicao );

if( !$rsInstituicao ){

  db_msgbox('Erro ao gerar relatório.');
  die();
}

$sNomeUsuario  = db_utils::fieldsMemory( $rsInstituicao, 0 )->nome;
$sNomeArquivo  = db_utils::fieldsMemory( $rsInstituicao, 0 )->arqret;
$sDataArquivo  = db_utils::fieldsMemory( $rsInstituicao, 0 )->dtarquivo;
$sDataRetorno  = db_utils::fieldsMemory( $rsInstituicao, 0 )->dtretorno;
$iConta        = db_utils::fieldsMemory( $rsInstituicao, 0 )->k00_conta;

$head3 = "Usuário: {$sNomeUsuario}";
$head4 = "Identif. Arquivo: {$codret}";
$head5 = "Arquivo : {$sNomeArquivo}";
$head6 = "DT.Arquivo : ".db_formatar($sDataArquivo,'d')."   DT.Retorno : ".db_formatar($sDataRetorno,'d');
$head7 = "Conta : {$iConta}";

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage("L");
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','',11);

$linha  = 0;
$pre    = 0;
$total  = 0;
$valor  = 0;
$pagina = 0;

$pdf->SetFont('Arial','B',10);
$pdf->MultiCell(0,12,"RELATÓRIO DOS VALORES PAGOS"."  -  ".$sOpcaoEmissao,0,"C",0);
$pdf->SetFont('Arial','B',7);
$pdf->Cell(10,6,"Banco",1,0,"C",1);
$pdf->Cell(15,6,"Agência",1,0,"C",1);
$pdf->Cell(25,6,"Número do Banco",1,0,"C",1);
$pdf->Cell(15,6,"Numpre",1,0,"C",1);
$pdf->Cell(15,6,"Parcela",1,0,"C",1);
$pdf->Cell(20,6,"N".CHR(176)." Arrecad.",1,0,"C",1);
$pdf->Cell(30,6,"Total Pago",1,0,"C",1);
$pdf->Cell(20,6,"Matr/Inscr",1,0,"C",1);
$pdf->Cell(20,6,"Numcgm",1,0,"C",1);
$pdf->Cell(20,6,"Class.",1,0,"C",1);
$pdf->Cell(87.5,6,"Situação",1,1,"C",1);

for($i=0;$i<$num;$i++) {

   if($pdf->GetY() > ( $pdf->h - 30 )){

      $pdf->Text($pdf->w-20,$pdf->h-5, $pdf->PageNo());
      $pdf->AddPage("L");
      $pdf->SetFont('Arial','B',10);
      $pdf->MultiCell(0,12,"RELATÓRIO DOS VALORES PAGOS"."  -  ".$sOpcaoEmissao,0,"C",0);
      $pdf->SetFont('Arial','B',7);
      $pdf->Cell(10,6,"Banco",1,0,"C",1);
      $pdf->Cell(15,6,"Agência",1,0,"C",1);
      $pdf->Cell(25,6,"Número do Banco",1,0,"C",1);
      $pdf->Cell(15,6,"Numpre",1,0,"C",1);
      $pdf->Cell(15,6,"Parcela",1,0,"C",1);
      $pdf->Cell(20,6,"N".CHR(176)." Arrecad.",1,0,"C",1);
      $pdf->Cell(30,6,"Total Pago",1,0,"C",1);
      $pdf->Cell(20,6,"Matr/Inscr",1,0,"C",1);
      $pdf->Cell(20,6,"Numcgm",1,0,"C",1);
      $pdf->Cell(20,6,"Class.",1,0,"C",1);
      $pdf->Cell(87.5,6,"Situação",1,1,"C",1);
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

   $sSituacao = "";
   $sqlbloqueado = " select ar22_sequencial
                       from numprebloqpag
                      where ar22_numpre = $numpre
                        and ar22_numpar = $k00_numpar";
   $resultbloqueado = db_query($sqlbloqueado) or die($sqlbloqueado);
   if (pg_numrows($resultbloqueado) > 0) {
     $sSituacao = "Numpre Bloqueado";
   }

   if ($sSituacao == "") {

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
            $sSituacao = "CANC $k21_codigo";
         }
      } else {

        db_fieldsmemory($resultcanc,0);
        $sSituacao = "CANC $k21_codigo";
      }
   }

   /**
    * Caso o débito esteja pago pega o numcgm de quem pagou o débito
    */
   if ($sSituacao == "") {

     $sqlpaga = " select case when disbanco.dtpago is not null
	                            then disbanco.dtpago
							                else arrepaga.k00_dtpaga end as k00_dtpaga,
							           k00_numcgm as numcgm
                    from arrepaga
                         left join arreidret on arrepaga.k00_numpre = arreidret.k00_numpre
                                            and arrepaga.k00_numpar = arreidret.k00_numpar
                         left join disbanco  on disbanco.idret = arreidret.idret
                   where arrepaga.k00_numpre = $numpre
                     and arrepaga.k00_numpar = $k00_numpar
                  limit 1";
     $resultpaga = db_query($sqlpaga) or die($sqlpaga);
     if (pg_numrows($resultpaga) == 0) {

       $sqlpaga = " select case when disbanco.dtpago is not null
	                              then disbanco.dtpago
								                else arrepaga.k00_dtpaga end as k00_dtpaga,
	                         k00_numcgm as numcgm
                      from arrepaga
                           inner join db_reciboweb on k99_numpre = arrepaga.k00_numpre
                                                  and k99_numpar = arrepaga.k00_numpar
                           left join arreidret on arrepaga.k00_numpre = arreidret.k00_numpre
                                              and arrepaga.k00_numpar = arreidret.k00_numpar
                           left join disbanco  on disbanco.idret = arreidret.idret
                    where k99_numpre_n = $numpre
                    limit 1";
       $resultpaga = db_query($sqlpaga) or die($sqlpaga);
       if (pg_numrows($resultpaga) > 0) {

         db_fieldsmemory($resultpaga,0);
         $sSituacao = "PAGA EM " . db_formatar($k00_dtpaga,"d");
       }
     } else {

       db_fieldsmemory($resultpaga,0);
       $sSituacao = "PAGA EM " . db_formatar($k00_dtpaga,"d");
     }

   }

   /**
    * Procura numbco repetido pelo idret
    */
   if ( $sSituacao == "" ) {

     $sSqlDuplicados  = "select array_to_string( array_accum(distinct arrebanco.k00_numbco ), ', ') as k00_numbco,           \n";
     $sSqlDuplicados .= "       count( arrebanco.k00_numbco )                                       as quantidade_arrebanco  \n";
     $sSqlDuplicados .= "  from disbanco                                                                                     \n";
     $sSqlDuplicados .= "       inner join arrebanco on trim(arrebanco.k00_numbco) = trim(disbanco.k00_numbco)               \n";
     $sSqlDuplicados .= " where idret = {$idret}                                                                             \n";
     $rsDuplicados    = db_query($sSqlDuplicados);

     if ( !$rsDuplicados ) {
       db_redireciona("db_erros.php?fechar=true&db_erro=". urlencode($sSqlDuplicados) ."Erro ao Buscar dados do Numbanco" . urlencode(pg_last_error()));
       exit();
     }

     if ( pg_num_rows($rsDuplicados) > 0 ) {

       $oResultado        = db_utils::fieldsMemory($rsDuplicados, 0);
       $sNumbcoDuplicado  = trim($oResultado->k00_numbco);
       $iQuantidadeNumbco = $oResultado->quantidade_arrebanco;

       if ($iQuantidadeNumbco > 1 ) {
         $sSituacao        = "Registro (IDRET:{$idret}) com Numbco( {$sNumbcoDuplicado} ) Duplicado.";
       }
     }
   }

   /**
    * Caso ainda nao tenha encontrado uma inconsistencia, verifica se o numpre é de outra instituicao
    */
    $sWhere                  = "    k00_numpre = {$numpre}
                                and k00_instit <> {$iInstit}";
    $oDaoArreinstit          = new cl_arreinstit;
    $sSqlVerificaInstituicao = $oDaoArreinstit->sql_query( null, "*", null, $sWhere );
    $rsArreinstit            = $oDaoArreinstit->sql_record( $sSqlVerificaInstituicao );

    if( $rsArreinstit ){

      if($oDaoArreinstit->numrows > 0){

        $sNomeInstituicao = db_utils::fieldsMemory( $rsArreinstit, 0 )->nomeinst;
        $sSituacao = "Registro (IDRET: {$idret}) com numpre vinculado a outra instituição ({$sNomeInstituicao}).";
      }
    }

   if ($sSituacao != ""){

     $sSqlIptunumpold = "select * from iptunumpold where j130_numpre = {$numpre} ;";
     $rsIptunumpold   = db_query($sSqlIptunumpold);
     $aIptunumpold    = db_utils::getCollectionByRecord($rsIptunumpold);
     if ( count($aIptunumpold) > 0 ) {
       $sSituacao = "RECALCULADO";
     }
   }

   /**
    * Verifica se o numpre existe na base de dados
    */
   if ($sSituacao != ""){

     $sSqlProcuraNumpre = "select 1 from arreinstit where k00_numpre = {$numpre}";
     $rsProcuraNumpre   = db_query($sSqlProcuraNumpre);
     $aProcuraNumpre    = db_utils::getCollectionByRecord($rsProcuraNumpre);
     if ( count($aProcuraNumpre) == 0 ) {
       $sSituacao = "Registro (IDRET: {$idret}) com numpre {$numpre} não encontrado.";
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
   $pdf->MultiCell(87.5,4,$sSituacao,0,1,"L",$pre);
   $total += 1;
   $linha += 1;
   $valor += $vlrtot;
}

$pdf->Ln(5);
$pdf->Cell(90,6,"Total de Registros: " . $total,"TB",0,"L",0);
$pdf->Cell(30,6,db_formatar($valor,'f'),"TB",0,"R",0);
$pdf->cell(0,6,' ',"TB",1,"L",0);
$pdf->Output();