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
include("libs/db_sql.php");
include("libs/db_utils.php");
include("libs/db_liborcamento.php");
include("libs/db_libcontabilidade.php");
include("classes/db_orctiporec_classe.php");
require_once("model/relatorioContabil.model.php");
//parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$sAgrupa = 1;
db_postmemory($HTTP_POST_VARS);
$opcao = 3;
$oRelataorioContabil        = new relatorioContabil(75);
$clorctiporec = new cl_orctiporec;

$head1   = "DEMONSTRATIVO DE DESPESAS E RECEITAS ";
$head2  = "EXERCÍCIO: ".db_getsession("DB_anousu");
$clselorcdotacao = new cl_selorcdotacao();

$clselorcdotacao->setDados($filtra_despesa);
// passa os parametros vindos da

// $instits = $clselorcdotacao->getInstit();
$instits =  "(".str_replace('-',', ',$db_selinstit).")"; 
$resultinst = db_query("select codigo, nomeinstabrev from db_config where codigo in $instits");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++) {
  db_fieldsmemory($resultinst,$xins);
  $descr_inst .= $xvirg.$nomeinstabrev ; 
  $xvirg = ', ';
}
$dtDataFinal      = $posicaoate; 
$mesini           = 1;
$aDataFinalPartes = explode("/", $dtDataFinal); 
$mesfin           =  $aDataFinalPartes[1];

$head3 = "INSTITUIÇÕES : ".$descr_inst;
$head4 = "Posição Até: {$posicaoate}";
$sStringHead6 = $deficitsuperavit=="e"?"Empenhado":"Liquidado";
$sStringHead7 = $quebrarporrecurso=="s"?"Sim":"Não";
$sStringHead8 = $consideraextra=="s"?"Sim":"Não";
$sStringHead9 = $sAgrupa =="2"?"Sim":"Não";
$head5 = "Deficit/Superavit: {$sStringHead6}";
$head6 = "Quebra Por Recurso: {$sStringHead7}";
$head7 = "Considerar Extra-Orçamentaria:  {$sStringHead8}";
$head8 = "Totalizacao Acumulada:  {$sStringHead9}";
if ($quebrarporrecurso == "n") {
  if($clselorcdotacao->recurso!=""){
    $sele_recursos = " and e91_recurso in ".$clselorcdotacao->recurso;
  } else {
    $sele_recursos = "";
  }
} else {
  $sele_recursos = "";
}
$and       = $clselorcdotacao->recurso == ''?' ':" and ";
$whUnidade = '';
if ($clselorcdotacao->unidade != ''){

  $whUnidade =  " and ".$clselorcdotacao->unidade; 


}
$sqlRec = "select    distinct   o15_codigo as cod_rec,
  o15_codigo ,
  o15_codtri,
  o15_descr as descr_rec
  from       orcdotacao  inner join orcorgao on o58_anousu=o40_anousu and o58_orgao=o40_orgao
  inner join orcunidade on o58_anousu=o41_anousu and o58_unidade=o41_unidade 
  inner join orctiporec on o58_codigo=o15_codigo
  where       o58_anousu=".db_getsession("DB_anousu")." 
  and         o41_anousu=".db_getsession("DB_anousu"); 
  $sqlRec .= ($clselorcdotacao->recurso == ""?"":" and o15_codigo in " . $clselorcdotacao->recurso);			 																																																																		                    
  $sqlRec .= $whUnidade;
  $sqlRec .= " order by o15_codigo";
#$result_orctiporec = $clorctiporec->sql_record($clorctiporec->sql_query(null,"o15_codigo, o15_codigo as cod_rec, o15_descr as descr_rec", "o15_codigo", ($clselorcdotacao->recurso == ""?"":"  o15_codigo in " . $clselorcdotacao->recurso)));
  $result_orctiporec = $clorctiporec->sql_record($sqlRec);


  $sele_instit = " and e60_instit in $instits ";

  $pdf = new PDF(); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $total = 0;
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','',8);
  $troca = 1;
  $alt = 6.5;

  $anousu  = db_getsession("DB_anousu");

  $pagina   = 1;
  $tottotal = 0;

  $pdf->addpage("L");

  for ($orctiporec=0; $orctiporec < $clorctiporec->numrows; $orctiporec++) {
    db_fieldsmemory($result_orctiporec, $orctiporec);

    if ($quebrarporrecurso == "n") {
      $sele_recursos = " ";
      $sele_work     = $clselorcdotacao->getDados()." and w.o58_instit in $instits ";
      $sele_work_rec = $clselorcdotacao->getDadosReceita("d")." and d.o70_instit in $instits ";
    } else {
      $sele_recursos = " and e91_recurso = $o15_codigo";
      $sele_work     = $clselorcdotacao->getDados()." and w.o58_instit in $instits and w.o58_codigo = $o15_codigo ";
      $sele_work_rec = $clselorcdotacao->getDadosReceita("d")." and d.o70_instit in $instits and d.o70_codigo = $o15_codigo ";
    }

    //
    $receita_arre = array();
    $despesa_arre = array();
    //
    $despesa_paga = array();
    $despesa_paga_rp = array();
    $despesa_liquidada = array();
    $totalExtra    = array();

    for ($x=0; $x <= 12; $x++) {
      $despesa_paga[$x] = 0;
      $despesa_paga_rp[$x] = 0;
      $despesa_liquidada[$x] = 0;
      $totalExtra[$x] = 0;
    }

    $arrecadado_ant				= 0;
    $empenhado_ant				= 0;
    $liquidado_ant				= 0;
    $deficitsuperavit_ant = 0;
    $orcadapago_ant				= 0;
    $restospago_ant				= 0;
    $totalpago_ant				= 0;

    $mostrartotal=false;

    if ($mesfin <= 6) {
      $mostrartotal=true;
    }

    $var = 0;
    $mostrartotal=true;

    $nTotal_valor_arrecadado			   = 0;
    $nTotal_valor_empenhado					 = 0;
    $nTotal_total_despesa_liquidada	 = 0;
    $nTotal_total_receita_arre			 = 0;
    $nTotal_total_despesa_paga			 = 0;
    $nTotal_total_despesa_paga_geral = 0;
    $nTotal_tot_pago                 = 0;
    $nTotal_totalExtra               = 0;
    $nTotal_totalArrecadado          = 0;

    for($rel=0;$rel<2;$rel++) {

      $valor_arrecadado					= 0;
      $valor_empenhado					= 0;
      $total_despesa_liquidada	= 0;
      $total_receita_arre				= 0;
      $total_despesa_paga				= 0;
      $total_despesa_paga_geral	= 0;
      $tot_pago                 = 0;
      $totalExtra               = 0;

      if($rel > 0) {
        $mesi = 7;
        $mesf = $mesfin;
      } else {
        $mesi = 1;
        $mesf = ($mesfin > 6?6:$mesfin);
      }

      if ($mesfin < 6) {
        $colrec=65+(35*$mesfin);
      } else {
        $colrec=275;
      }

      if ($quebrarporrecurso == "n") {
        $pdf->cell($colrec,$alt,"RECURSOS MARCADOS",1,1,"L",0);
      } else {
        $pdf->cell($colrec,$alt,$cod_rec . ' - ' . $descr_rec,1,1,"L",0);
      }
      $pdf->setfont('arial','b',8);
      $pdf->cell(40,$alt,"MÊS",1,0,"L",0);
      $pdf->setfont('arial','',8);
      for ($mes = $mesi; $mes <= $mesf; $mes++) {
        
        $pdf->cell(22,$alt,strtoupper(db_mes($mes)),1,0,"R",0);
        $pdf->cell(13,$alt,"VAR",1,0,"R",0);
        
      }
      $pdf->cell(25,$alt,'TOTAL GERAL',1,1,"R",0);
      $pdf->cell(40,$alt,"RECEITA ARRECADADA",1,0,"L",0);

      for ($mes = $mesi; $mes <= $mesf; $mes++) {
        $receita_arre[$mes] = 0;
        $ano = db_getsession("DB_anousu") + ($mes == 12?1:0);
        if ($aDataFinalPartes[1] == $mes) {
          $datafim = $ano."-".$aDataFinalPartes[1]."-".$aDataFinalPartes[0];
        } else {
         
          $sql = "select '$ano-" . ($mes == 12?1:$mes + 1) . "-01'::date - '1 days'::interval as datafim";
          $result = db_query($sql);
          db_fieldsmemory($result,0);
          
        }
            
        $dataini = db_getsession("DB_anousu") . "-$mes-01";
        db_query("begin");
        $result = db_receitasaldo(11,1,$opcao,true,$sele_work_rec,$anousu,$dataini,$datafim,"","*",false);

        $receita_arre[$mes] = 0; 

        $imprimecel = false;

        //      db_criatabela($result); exit;

        for($i=0;$i<pg_numrows($result);$i++) {
          db_fieldsmemory($result,$i);
          if (db_conplano_grupo($anousu,$o57_fonte,9004) == true){ 
            $valor_arrecadado   += $saldo_arrecadado;
            $receita_arre[$mes] += $saldo_arrecadado; 
            $imprimecel = true;
          }
        }
        if($imprimecel == false){
          $pdf->cell(22,$alt,db_formatar(0,'f'),1,0,"R",0);
        } else {
          $pdf->cell(22,$alt,db_formatar($receita_arre[$mes],'f'),1,0,"R",0);
        }

        if ($arrecadado_ant == 0 or $receita_arre[$mes] == 0) {
          $variacao = 0;
        } else {
          $variacao = ($receita_arre[$mes] / $arrecadado_ant * 100) - 100;
        }
        $pdf->cell(13,$alt,trim(db_formatar($variacao, 'p')),1,0,"R",0);
        $arrecadado_ant = $receita_arre[$mes];

        db_query("rollback");

      }
      $nTotal_valor_arrecadado += $valor_arrecadado; 
      if ($sAgrupa == '1' || $rel == 0) {
        $pdf->cell(25,$alt,db_formatar($valor_arrecadado,'f'),1,1,"R",0);
      } else if ($sAgrupa == '2' && $rel == 1){
        $pdf->cell(25,$alt,db_formatar($nTotal_valor_arrecadado,'f'),1,1,"R",0);
      }
      // fim da receita arrecadada

      // despesa liquidada
      $pdf->cell(40,$alt,"DESPESA EMPENHADA",1,0,"L",0);

      for ($mes = $mesi; $mes <= $mesf; $mes++) {
        if($mes>=$mesini && $mes <= $mesfin ) {
          $ano = db_getsession("DB_anousu") + ($mes == 12?1:0);
          if ($aDataFinalPartes[1] == $mes) {
            $datafim = $ano."-".$aDataFinalPartes[1]."-".$aDataFinalPartes[0];
          }  else {
            $sql = "select '$ano-" . ($mes == 12?1:$mes + 1) . "-01'::date - '1 days'::interval as datafim";
            $result = db_query($sql);
            db_fieldsmemory($result,0);
          }
          
          $dataini = db_getsession("DB_anousu") . "-$mes-01";
          db_query("begin");
          $result = db_dotacaosaldo(1,2,3,false,$sele_work,$anousu,$dataini,$datafim);
          $saldo_liquidado =0;
          $saldo_empenhado =0;
          $saldo_pago =0;
          for($i=0;$i<pg_numrows($result);$i++){
            db_fieldsmemory($result,$i);
            $saldo_liquidado += $liquidado;
            $saldo_empenhado += ($empenhado-$anulado);
            $saldo_pago += $pago;
          }
          $despesa_paga[$mes] = $saldo_pago;
          $despesa_liquidada[$mes] = $saldo_liquidado;
          $valor_empenhado += $saldo_empenhado;
          $receita_arre[$mes] = $receita_arre[$mes] - ($deficitsuperavit == "e"?$saldo_empenhado:$saldo_liquidado);

          $pdf->cell(22,$alt,db_formatar($saldo_empenhado,'f'),1,0,"R",0);

          if ($empenhado_ant == 0 or $saldo_empenhado == 0) {
            $variacao = 0;
          } else {
            $variacao = ($saldo_empenhado / $empenhado_ant * 100) - 100;
          }
          $pdf->cell(13,$alt,trim(db_formatar($variacao, 'p')),1,0,"R",0);
          $empenhado_ant = $saldo_empenhado;

          db_query("rollback");

        }else{
          $despesa_paga[$mes] = 0;
          $despesa_liquidada[$mes] = 0;
          //$valor_empenhado += 0;
          $receita_arre[$mes] = 0;
          $pdf->cell(22,$alt,db_formatar(0,'f'),1,0,"R",0);
          $pdf->cell(13,$alt,db_formatar(0,'f'),1,0,"R",0);
        }

      }
      $nTotal_valor_empenhado += $valor_empenhado;
      if ($sAgrupa == 1 || $rel == 0) {
        $pdf->cell(25,$alt,db_formatar($valor_empenhado,'f'),1,1,"R",0);
      } else if ($sAgrupa == 2 && $rel == 1) {
        $pdf->cell(25,$alt,db_formatar($nTotal_valor_empenhado,'f'),1,1,"R",0);
      }

      $pdf->cell(40,$alt,"DESPESA LIQUIDADA",1,0,"L",0);

      for ($mes = $mesi; $mes <= $mesf; $mes++) {
        $pdf->cell(22,$alt,db_formatar($despesa_liquidada[$mes],'f'),1,0,'R',0);

        if ($liquidado_ant == 0 or $despesa_liquidada[$mes] == 0) {
          $variacao = 0;
        } else {
          $variacao = ($despesa_liquidada[$mes] / $liquidado_ant * 100) - 100;
        }
        $pdf->cell(13,$alt,trim(db_formatar($variacao, 'p')),1,0,"R",0);
        $liquidado_ant = $despesa_liquidada[$mes];

        $total_despesa_liquidada += $despesa_liquidada[$mes];
      }
      $nTotal_total_despesa_liquidada += $total_despesa_liquidada;
      if ($sAgrupa == 1 || $rel == 0) {
        $pdf->cell(25,$alt,($mostrartotal==true?db_formatar($total_despesa_liquidada,'f'):""),1,1,'R',0);
      } else if ($sAgrupa == 2 && $rel == 1) {
        $pdf->cell(25,$alt,($mostrartotal==true?db_formatar($nTotal_total_despesa_liquidada,'f'):""),1,1,'R',0);
      }

      //
      //   RECEITA   EXTRA-ORCAMENTARIA
      //

      $totalExtra = 0; 
      $pdf->cell(40,$alt,"RECEITA EXTRA",1,0,"L",0);
      $totalReceitaExtra = 0;
      $totalRecAnt       = 0;
      $totalRec          = 0;
      for ($mes = $mesi; $mes <= $mesf; $mes++) {

        $ano = db_getsession("DB_anousu") + ($mes == 12?1:0);
        if ($aDataFinalPartes[1] == $mes) {
            $datafim = $ano."-".$aDataFinalPartes[1]."-".$aDataFinalPartes[0];
          }  else {
            $sql = "select '$ano-" . ($mes == 12?1:$mes + 1) . "-01'::date - '1 days'::interval as datafim";
            $result = db_query($sql);
            db_fieldsmemory($result,0);
          }
        $dataini     = db_getsession("DB_anousu") . "-$mes-01";
        if ($quebrarporrecurso == 'n'){
          $wh    = " c61_instit in $instits "; 
        }else{
          $wh    = " c61_codigo = $o15_codigo and c61_instit in $instits ";

        }
       
        // Para Testes
        //$dataini = "2009-03-27";
        //$datafim = $dataini;
 
        $sql  = "select sum(round(cornump.k12_valor,2)) as valor ";
        $sql .= "  from corrente ";
        $sql .= "       inner join cornump  on cornump.k12_id     = corrente.k12_id ";
        $sql .= "                          and cornump.k12_data   = corrente.k12_data ";
        $sql .= "                          and cornump.k12_autent = corrente.k12_autent ";
        $sql .= "       inner join tabrec   on tabrec.k02_codigo  = cornump.k12_receit ";
        $sql .= "       inner join conplanoexe    on c62_reduz  = k12_conta ";
        $sql .= "                                and c62_anousu = {$anousu} ";
        $sql .= "       inner join conplanoreduz  on c61_reduz  = c62_reduz ";
        $sql .= "                                and c61_anousu = c62_anousu ";
        $sql .= "       inner join conplano       on c60_codcon = c61_codcon ";
        $sql .= "                                and c60_anousu = c61_anousu ";
        $sql .= " where {$wh} ";
        $sql .= "   and corrente.k12_data between '{$dataini}' and '{$datafim}' ";
        $sql .= "   and tabrec.k02_tipo = 'E' ";

        $rs         = db_query($sql);
        $numrows    = pg_num_rows($rs);
        $totalRecp  = 0;
        if($numrows > 0) {
          db_fieldsmemory($rs, 0); 
          $totalRecp = $valor;
        }

        //$totalRec[$mes] = $totalRecp;
        if ($totalRecAnt == 0 or $totalRecp == 0) {
          $variacao = 0;
        } else {
          $variacao = ($totalRecp/ $totalRecAnt * 100) - 100;
        }

        $pdf->cell(22,$alt,db_formatar($totalRecp,'f'),1,0,'R',0);
        $pdf->cell(13,$alt,trim(db_formatar($variacao, 'p')),1,0,"R",0);
        $totalReceitaExtra += $totalRecp;
        $totalRecAnt       = $totalRecp;
        $totalRecMes[$mes] = $totalRecp;

      }
      $nTotal_total_receita_arre += $totalReceitaExtra;
      if ($sAgrupa == 1 || $rel == 0) {
        $pdf->cell(25,$alt,($mostrartotal==true?db_formatar($totalReceitaExtra,'f'):""),1,1,'R',0);
      } else if ($sAgrupa == 2 && $rel == 1) {
        $pdf->cell(25,$alt,($mostrartotal==true?db_formatar($nTotal_total_receita_arre,'f'):""),1,1,'R',0);
      }  
      $pdf->cell(40,$alt,"DESPESA EXTRA",1,0,"L",0);
      $totalDespesaExtra = 0;
      $totalDesAnt       = 0;
      //$totalDes          = 0;
      $totalExtra        = 0; 
      for ($mes = $mesi; $mes <= $mesf; $mes++) {

        $ano = db_getsession("DB_anousu") + ($mes == 12?1:0);
        if ($aDataFinalPartes[1] == $mes) {
            $datafim = $ano."-".$aDataFinalPartes[1]."-".$aDataFinalPartes[0];
          }  else {
            $sql = "select '$ano-" . ($mes == 12?1:$mes + 1) . "-01'::date - '1 days'::interval as datafim";
            $result = db_query($sql);
            db_fieldsmemory($result,0);
          }

        $dataini     = db_getsession("DB_anousu") . "-$mes-01";
        if ($quebrarporrecurso == 'n'){
          $wh    = " corrente.k12_instit in $instits "; 
        }else{
          $wh    = " corrente.k12_instit in $instits and (r1.c61_codigo = $o15_codigo or r1.c61_codigo = $o15_codigo)";
        }

        $sql  = "select round( sum( case when (p1.c60_codsis = 6 and p2.c60_codsis = 6) or ";
        $sql .= "                             (p1.c60_codsis = 6 and p2.c60_codsis = 5) or ";
        $sql .= "                             (p1.c60_codsis = 5 and p2.c60_codsis = 6) then 0 ";
        $sql .= "                        else corrente.k12_valor ";
        $sql .= "                   end ), 2) as valor ";
        $sql .= "  from corrente ";
        $sql .= "       inner join corlanc b on corrente.k12_id     = b.k12_id     ";
        $sql .= "                           and corrente.k12_autent = b.k12_autent ";
        $sql .= "                           and corrente.k12_data   = b.k12_data   ";

        $sql .= "       inner join slip      on slip.k17_codigo     = b.k12_codigo ";

        $sql .= "       left join saltes c   on c.k13_conta         = corrente.k12_conta ";
        $sql .= "       left join saltes d   on d.k13_conta         = b.k12_conta ";

        $sql .= "       inner join conplanoreduz r1  on b.k12_conta   = r1.c61_reduz ";
        $sql .= "                                   and r1.c61_anousu = {$anousu} ";
        $sql .= "                                   and r1.c61_instit = corrente.k12_instit ";
        $sql .= "       inner join conplano      p1  on r1.c61_codcon = p1.c60_codcon ";
        $sql .= "                                   and r1.c61_anousu = p1.c60_anousu ";

        $sql .= "       inner join conplanoreduz r2  on corrente.k12_conta = r2.c61_reduz ";
        $sql .= "                                   and r2.c61_anousu      = {$anousu} ";
        $sql .= "                                   and r2.c61_instit = corrente.k12_instit ";
        $sql .= "       inner join conplano      p2  on r2.c61_codcon      = p2.c60_codcon ";
        $sql .= "                                   and r2.c61_anousu      = p2.c60_anousu ";

        $sql .= " where {$wh} ";
        $sql .= "   and corrente.k12_data between '{$dataini}' and '{$datafim}' ";
        $rs          = db_query($sql);
        $numrows     = pg_num_rows($rs);
        $totalDesp   = 0;
        if($numrows > 0) {
          db_fieldsmemory($rs, 0); 
          $totalDesp = $valor;
        }

        $totalDes[$mes] = $totalDesp;
        if ($totalDesAnt == 0 or $totalDes[$mes] == 0) {
          $variacao = 0;
        } else {
          $variacao = ($totalDes[$mes] / $totalDesAnt * 100) - 100;
        }

        $pdf->cell(22,$alt,db_formatar($totalDes[$mes],'f'),1,0,'R',0);
        $pdf->cell(13,$alt,trim(db_formatar($variacao, 'p')),1,0,"R",0);
        $totalDespesaExtra += $totalDesp;
        $totalDesAnt        = $totalDesp;

      }
      $nTotal_totalExtra += $totalDespesaExtra;
      //$totalExtra = $totalRec-$totalDes;
      if ($sAgrupa == 1 || $rel == 0) {
        $pdf->cell(25,$alt,($mostrartotal==true?db_formatar($totalDespesaExtra,'f'):""),1,1,'R',0);
      } else if ($sAgrupa == 2 && $rel == 1) {  
        $pdf->cell(25,$alt,($mostrartotal==true?db_formatar($nTotal_totalExtra,'f'):""),1,1,'R',0);
      }
      $pdf->cell(40,$alt,"DEFICIT/SUPERAVIT",1,0,"L",0);
      for ($mes = $mesi; $mes <= $mesf; $mes++) {
        //echo "->$mes=>".$receita_arre[$mes]."-----".$totalExtra."<br>";
        $valorDefSup =  ($consideraextra == "s"?$receita_arre[$mes]+$totalRecMes[$mes]-$totalDes[$mes]:$receita_arre[$mes]);
        $pdf->cell(22,$alt,db_formatar($valorDefSup,'f'),1,0,'R',0);

        if ($deficitsuperavit_ant == 0 or $valorDefSup == 0) {
          $variacao = 0;
        } else {
          $variacao = ($valorDefSup / $deficitsuperavit_ant * 100) - 100;
        }
        $pdf->cell(13,$alt,trim(db_formatar($variacao, 'p')),1,0,"R",0);
        $deficitsuperavit_ant = $valorDefSup;

        $total_receita_arre += $valorDefSup;
      }
      $nTotal_totalArrecadado +=  $total_receita_arre;
      if ($sAgrupa == 1 || $rel == 0) {
        $pdf->cell(25,$alt,($mostrartotal == true?db_formatar($total_receita_arre,'f'):""),1,1,'R',0);
      } else if ($sAgrupa == 2 && $rel == 1) {
        $pdf->cell(25,$alt,($mostrartotal == true?db_formatar($nTotal_totalArrecadado,'f'):""),1,1,'R',0);
      }

      // despesa orcada paga
      $pdf->cell(40,$alt,"DESPESA ORÇADA/PAGA",1,0,"L",0);
      for ($mes = $mesi; $mes <= $mesf; $mes++) {
        $pdf->cell(22,$alt,db_formatar($despesa_paga[$mes],'f'),1,0,'R',0);

        if ($orcadapago_ant == 0 or $despesa_paga[$mes] == 0) {
          $variacao = 0;
        } else {
          $variacao = ($despesa_paga[$mes] / $orcadapago_ant * 100) - 100;
        }
        $pdf->cell(13,$alt,trim(db_formatar($variacao, 'p')),1,0,"R",0);
        $orcadapago_ant = $despesa_paga[$mes];

        $total_despesa_paga+=$despesa_paga[$mes];
      }
      $nTotal_total_despesa_paga += $total_despesa_paga;
      if ($sAgrupa == 1 || $rel == 0) {
        $pdf->cell(25,$alt,($mostrartotal == true?db_formatar($total_despesa_paga,'f'):""),1,1,'R',0);
      } else if ($sAgrupa == 2 && $rel == 1) {
        $pdf->cell(25,$alt,($mostrartotal == true?db_formatar($nTotal_total_despesa_paga,'f'):""),1,1,'R',0);
      }

      $pdf->cell(40,$alt,"RESTOS A PAGAR (PAGO)",1,0,"L",0);
      // restos a pagar pago                       
      for ($mes = $mesi; $mes <= $mesf; $mes++) {
        if($mes>=$mesini && $mes <= $mesfin){

          $ano = db_getsession("DB_anousu") + ($mes == 12?1:0);
          if ($aDataFinalPartes[1] == $mes) {
            $datafim = $ano."-".$aDataFinalPartes[1]."-".$aDataFinalPartes[0];
          } else {
            $sql = "select '$ano-" . ($mes == 12?1:$mes + 1) . "-01'::date - '1 days'::interval as datafim";
            $result = db_query($sql);
            db_fieldsmemory($result,0);
          }
          $result = db_query($sql);
          db_fieldsmemory($result,0);

          $dataini = db_getsession("DB_anousu") . "-$mes-01";

          $sql = "select sum(case when c53_tipo = 30 then round(c70_valor,2)::float8 else 0::float8 end) as pago,
            sum(case when c53_tipo = 31 then round(c70_valor,2)::float8 else 0::float8 end) as estorno
              from conlancamdoc
              inner join conlancamemp on c75_codlan = c71_codlan
              inner join empresto     on e91_anousu = ".db_getsession("DB_anousu")." and 
              c75_numemp = e91_numemp
              inner join empempenho   on e60_numemp = e91_numemp
              inner join conhistdoc   on c53_coddoc = c71_coddoc
              inner join conlancam    on c71_codlan = c70_codlan
              where c71_data between '{$dataini}' and '{$datafim}'
              $sele_recursos 
              $sele_instit 
              ";
          $result = db_query($sql);
          db_fieldsmemory($result,0);
          $tot_pago     += $pago-$estorno;
          if (!isset($despesa_paga_rp[$mes]))
            $despesa_paga_rp[$mes] = 0;
          else 	 
            $despesa_paga_rp[$mes] += $pago-$estorno;

          $pdf->cell(22,$alt,db_formatar($pago-$estorno,'f'),1,0,"R",0);

          if ($restospago_ant == 0 or $despesa_paga_rp[$mes] == 0) {
            $variacao = 0;
          } else {
            $variacao = ($despesa_paga_rp[$mes] / $restospago_ant * 100) - 100;
          }
          $pdf->cell(13,$alt,trim(db_formatar($variacao, 'p')),1,0,"R",0);
          $restospago_ant = $despesa_paga_rp[$mes];

        }else{
          $tot_pago += 0;
          if (!isset($despesa_paga_rp[$mes]))
            $despesa_paga_rp[$mes] = 0;
          else 	 
            $despesa_paga_rp[$mes] += 0;

          $pdf->cell(22,$alt,db_formatar(0,'f'),1,0,"R",0);
          $pdf->cell(13,$alt,db_formatar($var,'f'),1,0,"R",0);
        }


      }
      $nTotal_tot_pago += $tot_pago;
      if ($sAgrupa == 1 || $rel == 0) {
        $pdf->cell(25,$alt,($mostrartotal == true?db_formatar($tot_pago,'f'):""),1,1,"R",0);
      } else if ($sAgrupa == 2 && $rel == 1) { 
        $pdf->cell(25,$alt,($mostrartotal == true?db_formatar($nTotal_tot_pago,'f'):""),1,1,"R",0);
      }

      $pdf->cell(40,$alt,"TOTAL PAGO",1,0,"L",0);
      $total_despesa_paga_geral=0;
      $valorDesp = 0;
      for ($mes = $mesi; $mes <= $mesf; $mes++) {
        $valorDesp =  ($consideraextra == "s"? $despesa_paga[$mes]+$despesa_paga_rp[$mes]+$totalDes[$mes] : $despesa_paga[$mes]+$despesa_paga_rp[$mes] );

        $pdf->cell(22,$alt,db_formatar($valorDesp ,'f'),1,0,'R',0);

        if ($totalpago_ant == 0 or $valorDesp == 0) {
          $variacao = 0;
        } else {
          $variacao = ($valorDesp / $totalpago_ant * 100) - 100;
        }
        $pdf->cell(13,$alt,trim(db_formatar($variacao, 'p')),1,0,"R",0);
        $totalpago_ant = $valorDesp;

        $total_despesa_paga_geral+=$valorDesp;
      }
      $nTotal_total_despesa_paga_geral += $total_despesa_paga_geral;
      if ($sAgrupa == 1 || $rel == 0) {
        $pdf->cell(25,$alt,($mostrartotal == true?db_formatar($total_despesa_paga_geral,'f'):"0"),1,1,'R',0);
      } else if ($sAgrupa == 2 && $rel == 1) {
        $pdf->cell(25,$alt,($mostrartotal == true?db_formatar($nTotal_total_despesa_paga_geral,'f'):"0"),1,1,'R',0);
      }

      $pdf->ln(5);

      if ($mesfin <= 6) {
        break;
      }

    }

    if ($quebrarporrecurso == "n") {
      break;
    }

  }
$pdf->ln();
$oRelataorioContabil->getNotaExplicativa($pdf,1);
$pdf->ln();
if ($quebrarporrecurso == "n") {
  $pdf->multicell(260,$alt,"Recursos selecionados: " . ($clselorcdotacao->recurso == ""?"TODOS":$clselorcdotacao->recurso),1,1);
}

$pdf->Output();

?>