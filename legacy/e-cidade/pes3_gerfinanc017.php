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

include(modification("classes/db_cgm_classe.php"));
include(modification("fpdf151/pdf.php"));
include(modification("classes/db_iptubase_classe.php"));
include(modification("classes/db_issbase_classe.php"));
include(modification("libs/db_utils.php"));

db_postmemory($HTTP_SERVER_VARS);
$sql = "select z01_nome , 
               r70_codigo, 
               r70_descr
          from rhpessoal 
         inner join rhpessoalmov on rh02_anousu = $ano
                                and rh02_mesusu = $mes
                                and rh02_regist = $matricula
                                and rh02_instit = ".db_getsession('DB_instit')."
          inner join cgm         on rh01_numcgm = z01_numcgm
          inner join rhlota      on r70_codigo  = rh02_lota  
                                and r70_instit  = rh02_instit
          where rh01_regist = $matricula";
$result = db_query($sql);
db_fieldsmemory($result,0);

$xtipo = "'x'";
$qualarquivo = '';
if ( $opcao == 'salario' ){
  $sigla   = 'r14_';
  $arquivo = 'gerfsal';
  $qualarquivo = 'Salário';
} else if ( $opcao == 'ferias' ){
  $sigla   = 'r31_';
  $arquivo = 'gerffer';
  $xtipo   = ' r31_tpp ';
  $qualarquivo = 'Férias';
} else if ( $opcao == 'rescisao' ){
  $sigla   = 'r20_';
  $arquivo = 'gerfres';
  $xtipo   = ' r20_tpp ';
  $qualarquivo = 'Rescisão';
} else if ($opcao == 'adiantamento'){
  $sigla   = 'r22_';
  $arquivo = 'gerfadi';
  $qualarquivo = 'Adiantamento';
} else if ($opcao == '13salario'){
  $sigla   = 'r35_';
  $arquivo = 'gerfs13';
  $qualarquivo = '13o. Salário';
} else if ($opcao == 'complementar'){
  $sigla   = 'r48_';
  $arquivo = 'gerfcom';
  $qualarquivo = 'Complementar';
} else if ($opcao == 'fixo'){
  $sigla   = 'r53_';
  $arquivo = 'gerffx';
  $qualarquivo = 'Fixo';
} else if ($opcao == 'previden'){
  $sigla   = 'r60_';
  $arquivo = 'previden';
  $qualarquivo = 'Ajuste da Previdência';
} else if ($opcao == 'irf'){
  $sigla   = 'r61_';
  $arquivo = 'ajusteir';
  $qualarquivo = 'Ajuste do IRRF';
}

$head2 = 'Demonstrativo do Cálculo ('.$ano.'/'.db_formatar($mes,'s','0',2,'e').')';
$head4 = $matricula.' - '.$z01_nome;
$head6 = $r70_codigo.' - '.$r70_descr ;
$head8 = 'Arquivo : '.$qualarquivo;

if ($opcao != 'previden' && $opcao != 'irf'){
  
$sql = "select * from (
        select ".$sigla."rubric as rubrica,
               1 as ordem_rub,
               case 
                 when rh27_pd = 3 then 0 
                 else case 
                        when ".$sigla."pd = 1 then ".$sigla."valor 
                        else 0 
                      end 
               end as Provento,
               case 
                 when rh27_pd = 3 then 0 
                 else case 
                        when ".$sigla."pd = 2 then ".$sigla."valor 
                        else 0 
                      end 
               end as Desconto,
               ".$sigla."quant as quant, 
               rh27_descr, 
               ".$xtipo." as tipo , 
               case 
                 when rh27_pd = 3 then 'Base' 
                 else case 
                        when ".$sigla."pd = 1 then 'Provento' 
                        else 'Desconto' 
               end 
               end as provdesc
         
          from ".$arquivo." 
         inner join rhrubricas on rh27_rubric = ".$sigla."rubric
                              and rh27_instit = ".$sigla."instit 
         where ".$sigla."regist = $matricula 
           and ".$sigla."anousu = $ano 
           and ".$sigla."mesusu = $mes 
           and ".$sigla."instit = ".db_getsession("DB_instit")."
           and ".$sigla."pd != 3 

         union

        select 'R950'::varchar(4) as rubrica,
               2,
               provento,
               desconto,
               0 as quant, 
               'TOTAL'::varchar(40) , 
               ''::varchar(1) as tipo , 
               ''::varchar(10) as provdesc
          from (select sum(case when ".$sigla."pd = 1 then ".$sigla."valor else 0 end ) as provento,
                       sum(case when ".$sigla."pd = 2 then ".$sigla."valor else 0 end ) as desconto
                  from ".$arquivo."
                 inner join rhrubricas on rh27_rubric = ".$sigla."rubric 
                                      and rh27_instit = ".$sigla."instit 
                 where ".$sigla."regist = $matricula 
                   and ".$sigla."anousu = $ano
                   and ".$sigla."mesusu = $mes
                   and ".$sigla."instit = ".db_getsession("DB_instit")."
                   and ".$sigla."pd != 3
               ) as  x

         union

        select ".$sigla."rubric as rubrica,
               3,
               ".$sigla."valor as Provento,
               0 as Desconto ,
               ".$sigla."quant as quant, 
               rh27_descr, 
               ".$xtipo." as tipo , 
               case 
                 when rh27_pd = 3 then 'Base' 
                else case 
                  when  ".$sigla."pd = 1 then 'Provento' 
                 else 'Desconto' end 
               end as provdesc
          from ".$arquivo." 
         inner join rhrubricas on rh27_rubric = ".$sigla."rubric 
                              and rh27_instit = ".$sigla."instit 
         where ".$sigla."regist = $matricula 
           and ".$sigla."anousu = $ano 
           and ".$sigla."mesusu = $mes
           and ".$sigla."instit = ".db_getsession("DB_instit")."
           and ".$sigla."pd = 3
        ) as yy order by ordem_rub, rubrica ";  

} else if ($opcao == 'previden'){

$sql = "select previden.*,
               rhrubricas.rh27_rubric,
               rhrubricas.rh27_descr,
               rhrubricas.rh27_pd 
          from previden
         inner join rhrubricas on r60_rubric = rh27_rubric 
                              and rh27_instit = ".db_getsession("DB_instit")." 
         where r60_anousu = $ano   
           and r60_mesusu = $mes  
           and r60_numcgm = $numcgm
           and r60_tbprev = $tbprev
         order by r60_numcgm, r60_tbprev, r60_rubric, r60_regist, r60_folha ";

} else if ($opcao == 'irf'){

$sql = " select ajusteir.*,
                rhrubricas.rh27_rubric,
                rhrubricas.rh27_descr,
                rhrubricas.rh27_pd 
           from ajusteir 
          inner join rhrubricas on r61_rubric = rh27_rubric 
                               and rh27_instit = ".db_getsession("DB_instit")." 
          where r61_anousu = $ano   
            and r61_mesusu = $mes  
            and r61_numcgm = $numcgm
          order by r61_numcgm,  r61_rubric, r61_regist, r61_folha ";
} else if ($opcao == 'complementar') {


}

if (DBPessoal::verificarUtilizacaoEstruturaSuplementar() && $opcao == 'complementar') {

  $sql = "select * from (
       select rh143_rubrica as rubrica,
               1 as ordem_rub,
               case
                 when rh27_pd = 3 then 0
                 else case
                        when rh143_tipoevento = 1 then rh143_valor
                        else 0
                      end
               end as Provento,
               case
                 when rh27_pd = 3 then 0
                 else case
                        when rh143_tipoevento = 2 then rh143_valor
                        else 0
                      end
               end as Desconto,
               rh143_quantidade as quant,
               rh27_descr,
               ".$xtipo." as tipo ,
               case
                 when rh27_pd = 3 then 'Base'
                 else case
                        when rh143_tipoevento = 1 then 'Provento'
                        else 'Desconto'
               end
               end as provdesc

          from rhfolhapagamento 
               inner join rhhistoricocalculo on rh143_folhapagamento = rh141_sequencial
               inner join rhrubricas         on rh27_rubric = rh143_rubrica
                                            and rh27_instit = rh141_instit
         where rh143_regist    = $matricula
           and rh141_anousu    = $ano
           and rh141_mesusu    = $mes
           and rh141_tipofolha = 3
           and rh141_instit    = ".db_getsession("DB_instit")."
           and rh143_tipoevento != 3

         union

        select 'R950'::varchar(4) as rubrica,
               2,
               provento,
               desconto,
               0 as quant,
               'TOTAL'::varchar(40) ,
               ''::varchar(1) as tipo ,
               ''::varchar(10) as provdesc
          from (select sum(case when rh143_tipoevento = 1 then rh143_valor else 0 end ) as provento,
                       sum(case when rh143_tipoevento = 2 then rh143_valor else 0 end ) as desconto
                  from rhfolhapagamento 
                       inner join rhhistoricocalculo on rh143_folhapagamento = rh141_sequencial
                       inner join rhrubricas         on rh27_rubric = rh143_rubrica
                                            and rh27_instit = rh141_instit
                 where rh143_regist = $matricula
                   and rh141_anousu = $ano
                   and rh141_mesusu = $mes
                   and rh141_tipofolha = 3
                   and rh141_instit = ".db_getsession("DB_instit")."
                   and rh143_tipoevento != 3
               ) as  x
  
         union

        select rh143_rubrica as rubrica,
               3,
               rh143_valor as Provento,
               0 as Desconto ,
               rh143_quantidade as quant,
               rh27_descr,
               ".$xtipo." as tipo ,
               case
                 when rh27_pd = 3 then 'Base'
                else case
                  when  rh143_tipoevento = 1 then 'Provento'
                 else 'Desconto' end
               end as provdesc
          from rhfolhapagamento 
               inner join rhhistoricocalculo on rh143_folhapagamento = rh141_sequencial
               inner join rhrubricas         on rh27_rubric = rh143_rubrica
                                            and rh27_instit = rh141_instit
         where rh143_regist = $matricula
           and rh141_anousu = $ano
           and rh141_tipofolha = 3
           and rh141_mesusu = $mes
           and rh141_instit = ".db_getsession("DB_instit")."
           and rh143_tipoevento = 3
        ) as yy order by ordem_rub, rubrica ";

}

$result = db_query($sql);
$oResult = db_utils::getCollectionByRecord($result);

if ($opcao != 'previden' && $opcao != 'irf'){
  $pdf = new PDF(); // abre a classe
  $pdf->Open(); // abre o relatorio
  $pdf->AliasNbPages(); // gera alias para as paginas
  $pagina = 1;
  $pdf->setleftmargin(30);
  $alt = 5;
  $pdf->setfillcolor(235);
   
  
  $aBase     = array();
  $aProvento = array();
  $aDesconto = array();
  $aTotal    = array();
  foreach( $oResult as $oDados){
    if ($oDados->provdesc == "Base") {
      $aBase[] = $oDados;
    }
    
    if ($oDados->provdesc == "Provento") {
      $aProvento[] = $oDados;
    }
    
    if ($oDados->provdesc == "Desconto") {
      $aDesconto[] = $oDados;
    }

    if ($oDados->rubrica == "R950" ) {
      $aTotal[] = $oDados;
    }

  }
  
  $pdf->addpage();
  $pdf->ln(5);
  $pdf->SetFont('arial','B',7);
  $pdf->cell(10,$alt,"CÓDIGO",1,0,"C",1);
  $pdf->cell(60,$alt,"DESCRIÇÃO",1,0,"C",1);
  $pdf->cell(15,$alt,"QUANT",1,0,"C",1);
  $pdf->cell(25,$alt,"PROVENTOS",1,0,"C",1);
  $pdf->cell(25,$alt,"DESCONTOS",1,0,"C",1);
  $pdf->cell(10,$alt,"TIPO",1,1,"C",1);

  $pdf->SetFont('arial','',7);

  foreach ($aProvento as $oDados) {

    if ($pdf->gety() > $pdf->h - 20){
      $pdf->addpage();
      $pdf->ln(5);
      $pdf->SetFont('arial','B',7);
      $pdf->cell(10,$alt,"CÓDIGO",1,0,"C",1);
      $pdf->cell(60,$alt,"DESCRIÇÃO",1,0,"C",1);
      $pdf->cell(15,$alt,"QUANT",1,0,"C",1);
      $pdf->cell(25,$alt,"PROVENTOS",1,0,"C",1);
      $pdf->cell(25,$alt,"DESCONTOS",1,0,"C",1);
      $pdf->cell(10,$alt,"TIPO",1,1,"C",1);
    }
    $pdf->SetFont('arial','',7);
      
    if ($oDados->tipo == 'x') {
     $oDados->tipo = '';
    }  
    $pdf->cell(10,$alt,$oDados->rubrica,"RL",0,"C",0);
    $pdf->cell(60,$alt,$oDados->rh27_descr,"LR",0,"L",0);
    $pdf->cell(15,$alt,db_formatar($oDados->quant,'f'),"LR",0,"R",0);
    $pdf->cell(25,$alt,db_formatar($oDados->provento,'f'),"LR",0,"R",0);
    $pdf->cell(25,$alt,db_formatar($oDados->desconto,'f'),"LR",0,"R",0);
    $pdf->cell(10,$alt,$oDados->tipo,"LR",1,"C",0);
  }
  
  foreach ($aDesconto as $oDados) {

    if ($pdf->gety() > $pdf->h - 20) {
      $pdf->addpage();
      $pdf->ln(5);
      $pdf->SetFont('arial','B',7);
      $pdf->cell(10,$alt,"CÓDIGO",1,0,"C",1);
      $pdf->cell(60,$alt,"DESCRIÇÃO",1,0,"C",1);
      $pdf->cell(15,$alt,"QUANT",1,0,"C",1);
      $pdf->cell(25,$alt,"PROVENTOS",1,0,"C",1);
      $pdf->cell(25,$alt,"DESCONTOS",1,0,"C",1);
      $pdf->cell(10,$alt,"TIPO",1,1,"C",1);
    }
    $pdf->SetFont('arial','',7);  
  
    if ($oDados->tipo == 'x') {
     $oDados->tipo = '';
    }  
    $pdf->cell(10,$alt,$oDados->rubrica,"RL",0,"C",0);
    $pdf->cell(60,$alt,$oDados->rh27_descr,"LR",0,"L",0);
    $pdf->cell(15,$alt,db_formatar($oDados->quant,'f'),"LR",0,"R",0);
    $pdf->cell(25,$alt,db_formatar($oDados->provento,'f'),"LR",0,"R",0);
    $pdf->cell(25,$alt,db_formatar($oDados->desconto,'f'),"LR",0,"R",0);
    $pdf->cell(10,$alt,$oDados->tipo,"LR",1,"C",0);
  }  
  
  foreach ( $aTotal as $oDados) {

    if ($pdf->gety() > $pdf->h - 20){
      $pdf->addpage();
      $pdf->ln(5);
      $pdf->SetFont('arial','B',7);
      $pdf->cell(10,$alt,"CÓDIGO",1,0,"C",1);
      $pdf->cell(60,$alt,"DESCRIÇÃO",1,0,"C",1);
      $pdf->cell(15,$alt,"QUANT",1,0,"C",1);
      $pdf->cell(25,$alt,"PROVENTOS",1,0,"C",1);
      $pdf->cell(25,$alt,"DESCONTOS",1,0,"C",1);
      $pdf->cell(10,$alt,"TIPO",1,1,"C",1);
    }
      
    $pdf->SetFont('arial','B',7);
    $pdf->cell(70,$alt,'TOTAL',"TLR",0,"C",1);
    $pdf->cell(15,$alt,'',"LRT",0,"R",1);
    $pdf->cell(25,$alt,db_formatar($oDados->provento,'f'),"LRT",0,"R",1);
    $pdf->cell(25,$alt,db_formatar($oDados->desconto,'f'),"LRT",0,"R",1);
    $pdf->cell(10,$alt,$oDados->tipo,"LRT",1,"C",1);
    $pdf->cell(70,$alt,'LÍQUIDO',"LB",0,"C",1);
    $pdf->cell(15,$alt,'',"LBR",0,"R",1);
    $pdf->cell(25,$alt,'',"LBR",0,"R",1);
//    $pdf->cell(25,$alt,db_formatar(abs(round($provento,2)-round($desconto,2)),'f'),"LRB",0,"R",1);
    $pdf->cell(25,$alt,db_formatar(abs(round($oDados->provento,2)-round($oDados->desconto,2)),'f'),"LRB",0,"R",1);
    $pdf->cell(10,$alt,$oDados->tipo,"RBL",1,"C",1);
  }        

  $pdf->SetFont('arial','',7);
  
  foreach ($aBase as $oDados) {

    if ($pdf->gety() > $pdf->h - 20){
      $pdf->addpage();
      $pdf->ln(5);
      $pdf->SetFont('arial','B',7);
      $pdf->cell(10,$alt,"CÓDIGO",1,0,"C",1);
      $pdf->cell(60,$alt,"DESCRIÇÃO",1,0,"C",1);
      $pdf->cell(15,$alt,"QUANT",1,0,"C",1);
      $pdf->cell(25,$alt,"PROVENTOS",1,0,"C",1);
      $pdf->cell(25,$alt,"DESCONTOS",1,0,"C",1);
      $pdf->cell(10,$alt,"TIPO",1,1,"C",1);
    }
    $pdf->SetFont('arial','',7);
    
    if ($oDados->tipo == 'x') {
     $oDados->tipo = '';
    }
    $pdf->cell(10,$alt,$oDados->rubrica,"RL",0,"C",0);
    $pdf->cell(60,$alt,$oDados->rh27_descr,"LR",0,"L",0);
    $pdf->cell(15,$alt,db_formatar($oDados->quant,'f'),"LR",0,"R",0);
    $pdf->cell(25,$alt,db_formatar($oDados->provento,'f'),"LR",0,"R",0);
    $pdf->cell(25,$alt,db_formatar($oDados->desconto,'f'),"LR",0,"R",0);
    $pdf->cell(10,$alt,$oDados->tipo,"LR",1,"C",0);
  }

  $pdf->cell(145,1,'',"T",1,"C",0);
}
$pdf->Output();