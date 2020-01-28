<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("classes/db_empagepag_classe.php");

$clempagepag = new cl_empagepag;

$clrotulo    = new rotulocampo;
$clrotulo->label('z01_nome');
$clrotulo->label('e60_codemp');
$clrotulo->label('e60_anousu');
$clrotulo->label('e83_conta');
$clrotulo->label('e83_descr');
$clrotulo->label('k13_descr');
$clrotulo->label('e86_cheque');
$clrotulo->label('e86_data');
$clrotulo->label('e82_codord');
$clrotulo->label('e81_valor');

db_postmemory($HTTP_POST_VARS);

if (isset($dtini) && $dtini != "") {
  $dtini = implode("-", array_reverse(explode("/", $dtini)));
} else {
  $dtini = date("Y-m-d",db_getsession("DB_datausu"));
}

if (isset($dtfim) && $dtfim != "") {
  $dtfim = implode("-", array_reverse(explode("/", $dtfim)));
} else {
  $dtfim = date("Y-m-d",db_getsession("DB_datausu"));
}

$dbwhere = " e80_instit = ".db_getsession("DB_instit")." and e86_data between '{$dtini}' and '{$dtfim}' ";

if (isset($lista)) {
  
  $listagem = "";
  $virgulas = "";
  for ($i = 0; $i < sizeof($lista); $i++) {
     
    $listagem .= $virgulas.$lista[$i];
    $virgulas  = ",";
  }
  
  if (trim($listagem) != "") {
    
    $in = " in ";
    if ($ver == "sem") {
      $in = " not in ";
    }
    
    $dbwhere .= " and e83_codtipo {$in} ({$listagem}) ";
  }
}

$sCampos         = "distinct e83_conta, e83_descr, e83_codtipo";
$sSqlEmpAgePag   = $clempagepag->sql_query_pago(null, null, $sCampos, "", $dbwhere);
$rsSqlEmpAgePag  = $clempagepag->sql_record($sSqlEmpAgePag);
$numrows         = $clempagepag->numrows; 
if ($numrows == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro cadastrado!');   
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->SetAutoPageBreak(false);
$pdf->AliasNbPages(); 

$head2 = "Relatório de Cheques Emitidos";
$head3 = "Data inicial: ".db_formatar($dtini,"d");
$head4 = "Data final: ".db_formatar($dtfim,"d");

if ($considerar == "t") {
  $head5 = "Considerar registros autenticados e não autenticados";
} elseif ($considerar == "s") {
  $head5 = "Considerar somente autenticados";
} elseif ($considerar == "n") {
  $head5 = "Considerar somente não autenticados";
}

if ($filtro == "t") {
  $head6 = "Filtro: Todos";
} elseif ($filtro == "o") {
  $head6 = "Filtro: Ordem de pagamento";
} elseif ($filtro == "s") {
  $head6 = "Filtro: Slips";
}

if (isset($totaliza)) {
  
  if ($totaliza == 't') {
    $head7 = "Totaliza por Cheque: Sim";
  } else {
    $head7 = "Totaliza por Cheque: Não";
  }
}

if (isset($quebrarpaginaconta)) {
  
  if ($quebrarpaginaconta == 't') {
    $head8 = "Quebrar Página por Conta: Sim";
  } else {
    $head8 = "Quebrar Página por Conta: Não";
  }
}

$pri               = true;
$listou            = false;

$pago              = '0.00';
$estorno           = '0.00';

$totcheque         = 0;
$vlrtot            = 0;
$total_valor_conta = 0;
$total_quant_conta = 0;
$nTotalValorCheque = 0;
$nTotalNumCheques  = 0;
$sContaAnt         = '';

for ($i = 0; $i < $numrows; $i++) {
  
  db_fieldsmemory($rsSqlEmpAgePag, $i);

  $sSql  = "   select *                                                                                                 ";
  $sSql .= "     from ( select distinct on (e81_codmov,e91_cheque) e86_data,                                            "; 
  $sSql .= "                   e91_cheque as e86_cheque,                                                                ";
  $sSql .= "                   e91_valor as e81_valor,                                                                  ";
  $sSql .= "                   e60_codemp,                                                                              ";
  $sSql .= "                   e60_anousu,                                                                              ";
  $sSql .= "                   e82_codord,                                                                              ";
  $sSql .= "                   case                                                                                     ";
  $sSql .= "                     when cgm.z01_nome is null                                                              ";
  $sSql .= "                       then cgm_slip.z01_nome                                                               ";
  $sSql .= "                     else cgm.z01_nome                                                                      ";
  $sSql .= "                   end as z01_nome,                                                                         ";
  $sSql .= "                   k13_descr,                                                                               ";
  $sSql .= "                   e89_codigo,                                                                              ";
  $sSql .= "                   corconf.k12_data,                                                                        ";
  $sSql .= "                   corempagemov.k12_sequencial                                                              ";
  $sSql .= "              from empagepag                                                                                ";
  $sSql .= "                   inner join  empagemov         on e81_codmov              = e85_codmov                    ";
  $sSql .= "                   inner join  empage      on empage.e80_codage       = empagemov.e81_codage          ";
  $sSql .= "                   inner join  empageconf        on e86_codmov              = e81_codmov                    ";
  $sSql .= "                   inner join  empagetipo        on e83_codtipo             = e85_codtipo                   ";
  $sSql .= "                   inner join  saltes            on k13_conta               = e83_conta                     ";
  $sSql .= "                   inner join  empageconfche     on e91_codmov              = e86_codmov                    ";
  $sSql .= "                                                and e91_ativo is true                                       ";
  $sSql .= "                   left  join  corconf           on corconf.k12_codmov      = e91_codcheque                 "; 
  $sSql .= "                                                and corconf.k12_ativo is true                               ";
  $sSql .= "                   left  join  empagemovforma    on e97_codmov              = e81_codmov                    ";
  $sSql .= "                   left  join  corempagemov      on corempagemov.k12_codmov = e81_codmov                    ";
  $sSql .= "                   left  join  empord            on e82_codmov              = e81_codmov                    ";
  $sSql .= "                   left  join  pagordem          on e50_codord              = e82_codord                    ";
  $sSql .= "                   left  join  empempenho        on e60_numemp              = e50_numemp                    ";
  $sSql .= "                   left  join  cgm               on cgm.z01_numcgm          = e60_numcgm                    ";
  $sSql .= "                   left  join  empageslip        on e89_codmov              = e81_codmov                    ";
  $sSql .= "                   left  join  slipnum           on e89_codigo              = k17_codigo                    ";
  $sSql .= "                   left  join  cgm as cgm_slip   on cgm_slip.z01_numcgm     = k17_numcgm                    ";
  $sSql .= "                   left  join  empageconfchecanc on e81_codmov              = empageconfchecanc.e93_codmov  ";
  $sSql .= "             where e85_codtipo = {$e83_codtipo}                                                             ";
  $sSql .= "               and {$dbwhere}                                                                               ";
  $sSql .= "               and (e97_codmov is null or e97_codforma = 2) ) as x                                          ";
  $sSql .= " order by e86_cheque,e89_codigo                                                                             ";

  $rsSql      = $clempagepag->sql_record($sSql);
  $numrowsSql = $clempagepag->numrows; 
  
  $prim        = true;
  $trocacheque = false;
  $codcheque   = 0;
  
  for ($c = 0; $c < $numrowsSql; $c++) {
    
    db_fieldsmemory($rsSql, $c);

    if ($considerar == "s" and $k12_data == "") {
      continue;
    } elseif ($considerar == "n" and $k12_data != "") {
      continue;
    }

    if ($filtro == "o" and $e82_codord == "") {
      continue;
    } elseif ($filtro == "s" and $e89_codigo == "") {
      continue;
    }

    if (isset($quebrarpaginaconta) && $sContaAnt != $e83_conta ) {
      if (!$pri) {
        if ($quebrarpaginaconta == 't') {
          $pdf->addpage("P");
        }
      }
      $sContaAnt = $e83_conta;
    }
    
    $vlrtot += $e81_valor;
    
    if ($codcheque != $e86_cheque) {
      
      $codcheque   = $e86_cheque;
      $trocacheque = true;
      $totcheque ++ ;
    } else {
      
      $trocacheque        = false;
      $nTotalValorCheque += $e81_valor;
      $nTotalNumCheques ++;
    }
    
    if (  ($pdf->gety() > $pdf->h -30)  || $prim==true|| $pri==true) {
      
      if ( $pdf->gety() > $pdf->h -30 || $pri==true ){    

        $prox = true;
        
        $pdf->addpage("P");
        $pdf->setfillcolor(235);
        $pdf->setfont('arial','b',7);
        $pdf->cell(15,4,$RLe86_data,1,0,"C",1);
        $pdf->cell(20,4,$RLe86_cheque,1,0,"C",1);
        $pdf->cell(15,4,$RLe60_codemp,1,0,"C",1);
        $pdf->cell(15,4,$RLe60_anousu,1,0,"C",1);
        $pdf->cell(10,4,$RLe82_codord,1,0,"C",1);
        $pdf->cell(15,4,$RLe81_valor,1,0,"C",1);
        $pdf->cell(65,4,$RLz01_nome,1,0,"C",1);
        $pdf->cell(10,4,"SLIP",1,0,"C",1);
        $pdf->cell(10,4,"Seq",1,0,"C",1);
        $pdf->cell(17,4,"Autenticação",1,1,"C",1);
        $pdf->ln(1);
      }   

      if ($pri == true || $prim == true) { 
        
        $prim = false;
        $pri  = false;
      }

      $pdf->setfillcolor(235);
      $pdf->setfont('arial','b',7);
      
      $pdf->cell(192,4,$RLe83_conta.": {$e83_conta}  {$RLe83_descr}: {$e83_descr} - {$k13_descr}",1,1,"L",1);
      $pdf->setfont('arial','',6);
    }
    
    $listou = true;
    if ($trocacheque == true) {
      
      if ($c > 0 && $totaliza == "t") {
        
        $pdf->setfont('arial','b',7);
        $pdf->cell(20,4,"Total de OP/SLip",'TB',0,"C",1);
        $pdf->cell(10,4,$nTotalNumCheques,'TB',0,"R",1);
        $pdf->cell(43,4,"Total do Cheque ",'TB',0,"R",1);
        $pdf->cell(17,4,db_formatar($nTotalValorCheque,"f"),'TB',1,"R",1);
        $pdf->setfont('arial','', 6);
      }
      
      $nTotalValorCheque = $e81_valor;
      $nTotalNumCheques  = 1;
      
      $pdf->cell(15,4,db_formatar($e86_data,'d'),1,0,"C",0);
      $pdf->cell(20,4,$e86_cheque,1,0,"R",0);
      
    } else {
      
      $pdf->cell(15,4,'',1,0,"C",0);
      $pdf->cell(20,4,'',1,0,"C",0);
    }
    
    $pdf->cell(15,4,$e60_codemp,1,0,"R",0);
    $pdf->cell(15,4,$e60_anousu,1,0,"C",0);
    $pdf->cell(10,4,$e82_codord,1,0,"R",0);
    $pdf->cell(15,4,db_formatar($e81_valor,'f'),1,0,"R",0);
    $pdf->cell(65,4,$z01_nome,1,0,"L",0);
    $pdf->cell(10,4,$e89_codigo,1,0,"C",0);
    $pdf->cell(10,4,$k12_sequencial,1,0,"C",0);
    $pdf->cell(17,4,db_formatar($k12_data,"d"),1,1,"C",0);
    
    $total_quant_conta++;
    $total_valor_conta += $e81_valor;
  }
  
  if ($totaliza == "t" && $numrowsSql > 0) {
    
    $pdf->setfont('arial','b',7);
    $pdf->cell(20,4,"Total de OP/SLip",'TB',0,"C",1);
    $pdf->cell(10,4,$nTotalNumCheques,'TB',0,"R",1);
    $pdf->cell(43,4,"Total do Cheque ",'TB',0,"R",1);
    $pdf->cell(17,4,db_formatar($nTotalValorCheque,"f"),'TB',1,"R",1);
    $pdf->setfont('arial','', 6);
  }
   
  if ($total_quant_conta > 0) {
    
    $pdf->cell(30,4,"Quantidade de cheques desta conta:",0,0,"L",0);
    $pdf->cell(10,4,$total_quant_conta,0,0,"R",0);
    $pdf->cell(15,4,"",0,0,"L",0);
    $pdf->cell(18,4,"Total:",0,0,"R",0);
    $pdf->cell(17,4,db_formatar($total_valor_conta,'f'),0,1,"R",0);
    $pdf->ln(1);
  }
  
  $total_valor_conta = 0;
  $total_quant_conta = 0;
}

$pdf->ln(1);

if ( $pdf->gety() > $pdf->h -30) {
  $pdf->AddPage("P");    
}

$pdf->setfont('arial','b',8);

$pdf->cell(50,4,"QUANTIDADE TOTAL GERAL DE CHEQUES:",0,0,"L",0);
$pdf->cell(50,4,$totcheque,0,1,"R",0);
$pdf->ln(1);

$pdf->cell(50,4,"VALOR TOTAL GERAL PAGO:",0,0,"L",0);
$pdf->cell(50,4,db_formatar($vlrtot,'f'),0,1,"R",0);

if ($listou == false) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Registros não localizados ! ');   
} else {
  $pdf->Output();
}
?>