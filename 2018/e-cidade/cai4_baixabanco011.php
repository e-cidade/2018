<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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
require_once(modification("libs/db_utils.php"));

$iInstit = db_getsession("DB_instit");

$sSql  = "select distinct on (d.idret)                                                ";
$sSql .= "       d.idret,                                                             ";
$sSql .= "       d.k15_codbco,                                                        ";
$sSql .= "	      d.k15_codage,                                                       ";
$sSql .= "	      d.k00_numbco,                                                       ";
$sSql .= "       a.k00_numpre,                                                        ";
$sSql .= "       case when d.k00_numpar = 0 then 0                                    ";
$sSql .= "            else d.k00_numpar                                               ";
$sSql .= "       end as k00_numpar,                                                   ";
$sSql .= "       d.k00_numpre as numpre,                                              ";
$sSql .= "       d.vlrtot,case when classi = 'f' then 'Não' else 'Sim' end as classi, ";
$sSql .= "       coalesce(c.k00_matric,0) as matric,                                  ";
$sSql .= "       coalesce(f.k00_inscr,0) as inscr,                                    ";
$sSql .= "       case                                                                 ";
$sSql .= "           when a.k00_numcgm > 0 then a.k00_numcgm                          ";
$sSql .= "           when g.k00_numcgm > 0 then g.k00_numcgm                          ";
$sSql .= "       else 0 end as numcgm                                                 ";
$sSql .= "  from disbanco d                                                           ";
$sSql .= "       left join arrecad    a on a.k00_numpre = d.k00_numpre                ";
$sSql .= "       left join arrepaga   g on g.k00_numpre = d.k00_numpre                ";
$sSql .= "       left join recibopaga r on r.k00_numnov = a.k00_numpre                ";
$sSql .= "       left join arrematric c on c.k00_numpre = d.k00_numpre                ";
$sSql .= "       left join arreinscr  f on f.k00_numpre = d.k00_numpre                ";
$sSql .= " where codret = $codret                                                     ";
$sSql .= "   and instit = $iInstit                                                    ";


$sOpcaoEmissao = 'TODOS REGISTROS';
if ($opcao == 'erros') {

  $sOpcaoEmissao = 'SOMENTE ERROS';
  $sSql .= " and classi is false ";
}else if($opcao == 'corretos') {

  $sOpcaoEmissao = 'SOMENTE CORRETOS';
  $sSql .= " and classi is true ";
}

$sSql .= " order by idret, numpre, k00_numpar ";

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
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','',11);

$linha    = 0;
$pre      = 0;
$total    = 0;
$valor    = 0;
$pagina   = 0;
$primeiro = 0;

$cond_idret  = 0;
$cond_numpre = 0;
$cond_numpar = 0;

for($i=0;$i<$num;$i++) {

  db_fieldsmemory($result,$i);

 // Alterado para verificar o idret, conforme Tarefa 26622.
 if ( $cond_idret == $idret ) {

   if ( $cond_numpre == $numpre ) {

     if ( $k00_numpar != 0 ) {

       if ( $cond_numpar == $k00_numpar ) {
         continue;
       }
     }
   }
 }

 $cond_idret  = $idret;
 $cond_numpre = $numpre;
 $cond_numpar = $k00_numpar;

  $sqlrec = "
select debitos.k00_numpre,
       debitos.k00_numpar,
       debitos.k00_receit,

       (select rtstatus from fc_statusdebitos(debitos.k00_numpre, debitos.k00_numpar, debitos.k00_receit) limit 1) as k00_status,

       tabrec.k02_descr,
       sum(k00_valor) as k00_valor,
       debitos.k00_origem,
       debitos.k00_numcgm,
       arrematric.k00_matric,
       arreinscr.k00_inscr
from (
select k00_numpre, k00_numpar, k00_receit, k00_valor, 'RECIBOPAGA' as k00_origem, k00_numcgm
  from recibopaga
 where k00_numnov = $numpre
union all
select k00_numpre, k00_numpar, k00_receit, k00_valor, 'RECIBO' as k00_origem, k00_numcgm
  from recibo
 where k00_numpre = $numpre
union all
select k00_numpre, k00_numpar, k00_receit, k00_valor, 'ARRECANT' as k00_origem, k00_numcgm
  from arrecant
 where k00_numpre = $numpre ";

  if($k00_numpar<>0) {
    $sqlrec .= " and k00_numpar = $k00_numpar ";
  }

  $sqlrec .= "
union all
select k00_numpre, k00_numpar, k00_receit, k00_valor, 'ARREOLD' as k00_origem, k00_numcgm
  from arreold
       inner join arretipo on arretipo.k00_tipo = arreold.k00_tipo
 where k00_numpre = $numpre ";

  if($k00_numpar<>0) {
    $sqlrec .= " and k00_numpar = $k00_numpar ";
    $sqlrec .= " and not exists ( select 1 from arrecad  x where x.k00_numpre = $numpre and x.k00_numpar = $k00_numpar ) ";
    $sqlrec .= " and not exists ( select 1 from arrecant x where x.k00_numpre = $numpre and x.k00_numpar = $k00_numpar ) ";
    $sqlrec .= " and not exists ( select 1 from arrepaga x where x.k00_numpre = $numpre and x.k00_numpar = $k00_numpar ) ";
  } else {
    $sqlrec .= " and not exists ( select 1 from arrecad  x where x.k00_numpre = $numpre ) ";
    $sqlrec .= " and not exists ( select 1 from arrecant x where x.k00_numpre = $numpre ) ";
    $sqlrec .= " and not exists ( select 1 from arrepaga x where x.k00_numpre = $numpre ) ";
  }

  $sqlrec .= " ) as debitos
   inner join tabrec on k02_codigo = k00_receit
   left  join arrematric on arrematric.k00_numpre = debitos.k00_numpre
   left  join arreinscr  on arreinscr.k00_numpre = debitos.k00_numpre
group by debitos.k00_numpre,
         debitos.k00_numpar,
         debitos.k00_receit,
         tabrec.k02_descr,
         debitos.k00_origem,
         debitos.k00_numcgm,
         k00_status,
         arrematric.k00_matric,
         arreinscr.k00_inscr
order by debitos.k00_numpre,
         debitos.k00_numpar,
         debitos.k00_receit";

$resultrec = db_query($sqlrec);
$linhasrec = pg_num_rows($resultrec);

  if($pdf->GetY() > ( $pdf->h - 30 )||($primeiro ==0)){

    $primeiro =1;
    $pdf->Text($pdf->w-20,$pdf->h-5, $pdf->PageNo());
    $pdf->AddPage("L");
    $pdf->SetFont('Arial','B',10);
    $pdf->MultiCell(0,12,"RELATÓRIO DOS VALORES PAGOS"."  -  ".$sOpcaoEmissao,0,"C",0);
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(13,6,"Banco",1,0,"C",1);
    $pdf->Cell(15,6,"Agência",1,0,"C",1);
    $pdf->Cell(25,6,"N".CHR(176)." Arrecadação",1,0,"C",1);
    $pdf->Cell(25,6,"Valor Total",1,0,"C",1);
    $pdf->Cell(20,6,"Cgm",1,0,"C",1);
    $pdf->Cell(20,6,"Numpre",1,0,"C",1);
    $pdf->Cell(20,6,"Numpar",1,0,"C",1);
    $pdf->Cell(20,6,"Receita",1,0,"C",1);
    $pdf->Cell(35,6,"Descrição Receita",1,0,"C",1);
    $pdf->Cell(20,6,"Valor",1,0,"C",1);
    $pdf->Cell(20,6,"Matrícula",1,0,"C",1);
    $pdf->Cell(20,6,"Inscrição",1,0,"C",1);
    $pdf->Cell(20,6,"Situação",1,0,"C",1);
    $pdf->Ln();
    $linha = 0;
  }

  if($i % 2 == 0){
    $pre = 0;
  }else {
    $pre = 1;
  }

  if ( $matric != 0 ){
    $matins = $matric;
  }else if ( $inscr != 0 ){
    $matins = $inscr;
  }else{
    $matins = 0;
  }
  if($linhasrec>0){
    $pdf->SetFont('Arial','B',7);
  }else{
    $pdf->SetFont('Arial','',7);
  }
  if($numcgm=="0"){
    $numcgm = "";
  }

  /**
   * Procura numbco repetido pelo idret
   */
  $sSqlDuplicados  = "select arrebanco.k00_numbco,                                             ";
  $sSqlDuplicados .= "       idret,                                                            ";
  $sSqlDuplicados .= "       exists(   select 1                                                ";
  $sSqlDuplicados .= "                   from arrebanco as subquery                            ";
  $sSqlDuplicados .= "                  where subquery.k00_numbco = arrebanco.k00_numbco       ";
  $sSqlDuplicados .= "                    and subquery.k00_numbco <> '0'                       ";
  $sSqlDuplicados .= "               group by subquery.k00_numbco                              ";
  $sSqlDuplicados .= "                 having count(*) > 1  ) as duplicado                     ";
  $sSqlDuplicados .= "  from disbanco                                                          ";
  $sSqlDuplicados .= "       left join arrebanco on arrebanco.k00_numpre = disbanco.k00_numpre ";
  $sSqlDuplicados .= "                          and arrebanco.k00_numpar = disbanco.k00_numpar ";
  $sSqlDuplicados .= " where idret = {$idret}                                                  ";
  $rsDuplicados    = db_query($sSqlDuplicados);

  if ( !$rsDuplicados || pg_num_rows($rsDuplicados) == 0) {
    exit();
  }

  $aNumbco = array();
  $oDados  = db_utils::getCollectionByRecord($rsDuplicados);

  foreach ($oDados as $oNumbcoDuplicado) {

    if ($oNumbcoDuplicado->duplicado == 't') {
      $aNumbco[] =  $oNumbcoDuplicado->k00_numbco;
    }
  }

  $sSituacao   = "";
  if ( !empty($aNumbco) ) {
    $sSituacao = "Registro com Numbco(" . implode(", ", $aNumbco) . ") Duplicado.";
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

  $total  += 1;
  $linha  += 1;
  $valor  += $vlrtot;

  $pdf->Cell(13,4,$k15_codbco,"T",0,"C",$pre);
  $pdf->Cell(15,4,$k15_codage,"T",0,"C",$pre);
  $pdf->Cell(25,4,$numpre,"T",0,"C",$pre);
  $pdf->Cell(25,4,db_formatar($vlrtot,'f'),"T",0,"R",$pre);
  $pdf->Cell(20,4,$numcgm,"T",0,"C",$pre);
  $pdf->Cell(175,4,$sSituacao,"T",0,"L",$pre);
  $pdf->Ln();

		//Receita
		if($linhasrec>0){

		  $valorcalc = 0;
		  for($c=0;$c<$linhasrec;$c++){

		    db_fieldsmemory($resultrec,$c);
		    $valorcalc += $k00_valor;
		  }

      $totrec = 0;
		  for($r=0;$r<$linhasrec;$r++){

		    //calcular
        db_fieldsmemory($resultrec,$r);
        if($valorcalc > 0){
		      $valorperc = ($k00_valor * 100)/$valorcalc;
        }else{
          $valorperc = 0;
        }

		    $valorrec = round(($valorperc/100) * $vlrtot, 2);
        $totrec += $valorrec;

		    if($r==($linhasrec-1)){
		      $valorrec = $valorrec + ($vlrtot - $totrec);
		    }

		    $pdf->SetFont('Arial','',7);
		    $pdf->Cell(13,4,"",0,0,"C",$pre);
		    $pdf->Cell(15,4,"",0,0,"C",$pre);
		    $pdf->Cell(25,4,"",0,0,"C",$pre);
		    $pdf->Cell(25,4,"",0,0,"C",$pre);
		    $pdf->Cell(20,4,$k00_numcgm,0,0,"C",$pre);
		    $pdf->Cell(20,4,$k00_numpre,0,0,"C",$pre);
		    $pdf->Cell(20,4,$k00_numpar,0,0,"C",$pre);
		    $pdf->Cell(20,4,$k00_receit,0,0,"R",$pre);
		    $pdf->Cell(35,4,$k02_descr,0,0,"L",$pre);
		    $pdf->Cell(20,4,db_formatar($valorrec ,'f'),0,0,"R",$pre);
		    $pdf->Cell(20,4,$k00_matric,0,0,"C",$pre);
		    $pdf->Cell(20,4,$k00_inscr,0,0,"C",$pre);
		    $pdf->Cell(20,4,$k00_status,0,0,"C",$pre);
		    $pdf->Ln();

		  }
		}
}
$pdf->Ln(5);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(53,6,"Total de Registros :   ".$total,"TB",0,"L",0);
$pdf->Cell(25,6,db_formatar($valor,'f'),"TB",0,"R",0);
$pdf->cell(195,6,' ',"TB",1,"L",0);

$pdf->Output();