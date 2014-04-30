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

include ("classes/db_diasemana_classe.php");
include ("classes/db_calendario_classe.php");
include ("classes/db_mer_tipocardapio_classe.php");
include ("classes/db_mer_nutricionista_classe.php");
include ("classes/db_mer_cardapionutri_classe.php");
include ("classes/db_mer_cardapiotipo_classe.php");
include ("classes/db_mer_cardapiodia_classe.php");
include ("classes/db_feriado_classe.php");
require_once('libs/db_utils.php');
include ("fpdf151/pdf.php");

$cldiasemana         = new cl_diasemana;
$clcalendario        = new cl_calendario;
$clferiado           = new cl_feriado;
$clmer_tipocardapio  = new cl_mer_tipocardapio;
$clmer_nutricionista = new cl_mer_nutricionista;
$clmer_cardapiotipo  = new cl_mer_cardapiotipo;
$clmer_cardapiodia   = new cl_mer_cardapiodia;
$clmer_cardapionutri = new cl_mer_cardapionutri;
$lErro               = false;
$sSql                = $clmer_tipocardapio->sql_query_file($cardapio, 'me27_i_ano');
$rs                  = $clmer_tipocardapio->sql_record($sSql);
if ($clmer_tipocardapio->numrows > 0) {
  $calendario = db_utils::fieldsmemory($rs, 0)->me27_i_ano;
} else {
  $lErro = true;
}

if ($codescola != "") {
	
  $condicao2 = " AND exists(select * from mer_cardapiodiaescola 
                           where me37_i_cardapiodia = me12_i_codigo
                           and me37_i_cardapioescola = $codescola)";
  $condicao3 = " and me32_i_codigo = $codescola";
  	
} else {
	
  $condicao2 = "";	
  $condicao3 = "";
  
}
$sql        = " select * from mer_cardapio "; 
$sql       .= "       inner join mer_cardapiodia on me12_i_cardapio=me01_i_codigo";
$sql       .= "       inner join mer_tipocardapio on me27_i_codigo = me01_i_tipocardapio";
$result     = pg_query($sql);
$linhas     = pg_num_rows($result);
if ($linhas == 0 || $lErro) {
	
  ?>
  <table width='100%'>
   <tr>
    <td align='center'><font color='#FF0000' face='arial'>
     <b>Nenhum registro encontrado.<br>
     <input type='button' value='Fechar' onclick='history.back();'></b> </font>
    </td>
   </tr>
  </table>
  <?
  exit ();
  
}

function numerosemana($data,$ano = "0") {
	
  if ($ano=="0") {
  	
    $data      = explode("/",$data);
    $timestamp = mktime(0, 0, 0, $data[1], $data[0], $data[2]);
    
  } else {
  	
    $data      = date("t/m/Y", mktime(0, 0, 0, 2, 1, $ano));
    $data      = explode("/",$data);
    $timestamp = mktime(0, 0, 0, $data[1], $data[0], $data[2]);
    
  }
  return date("W", $timestamp);
}

function somardata($data, $dias= 0, $meses = 0, $ano = 0) {
	
  $data     = explode("/", $data);
  $novadata = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses,   $data[0] + $dias, $data[2] + $ano) );
  return $novadata;
  
}

function montasemana($dData, $iSemana = null, $iAno = null) {

  /* 
  Se for passada somente $dData, ou seja, se $iSemana for null (valor default), 
  retorna todos os dias da semana da data passada. A semana sempre começa no domingo. 
  Por exemplo: se passada a data 12/01/2011, vai retornar um vetor com as datas começando
  no dia 09/01/2011 (domingo) e indo até dia 15/01/2011 (sábado).
  */
  if ($iSemana == null) { 
  	
    $dData      = explode('/', $dData);
    // Pego o número do dia da semana. (0 => Domingo, 6 => Sábado)
    $iDiaSemana = date('w', mktime(0, 0, 0, $dData[1], $dData[0], $dData[2]));
    for ($iCont = 0; $iCont < 7; $iCont++) {

      $aSemana[$iCont] = date('d/m/Y', mktime(0, 0, 0, $dData[1], $dData[0] + ($iCont - $iDiaSemana), $dData[2]));

    }

    return $aSemana;
    
  } else { // Retorna um arrays com os dias da semana  $iSemana do ano $iAno, começando no domingo e indo até sábado
  	
    if ($iAno == null) {
      $iAno = date('Y', db_getsession('DB_datausu'));
    }

    if ($iSemana < 1) { 
      $iAno--;
    }

    $iMax1            = date('W', mktime(0, 0, 0, 12, 24, $iAno));
    $iMax2            = date('W', mktime(0, 0, 0, 12, 31, $iAno));
    $iTotalSemanasAno = $iMax1 > $iMax2 ? $iMax1 : $iMax2;

    /* Obtenho o número total de semanas do ano em questão e verifico se a semana solicitada é menor ou igual 
       a este número. Se não for, vou para o ano seguinte, até, que o número da semana seja menor ou igual
       ao número total de semanas do ano solicitado
    */
    while ($iSemana > $iTotalSemanasAno) {

      $iSemana -= $iTotalSemanasAno;
      $iAno++; // Vou para o próximo ano
      $iMax1            = date('W', mktime(0, 0, 0, 12, 24, $iAno));
      $iMax2            = date('W', mktime(0, 0, 0, 12, 31, $iAno));
      $iTotalSemanasAno = $iMax1 > $iMax2 ? $iMax1 : $iMax2;

    }

     /* Obtenho o número total de semanas do ano anterior ($iAno--) e somo ao número da semana solicitada
        enquanto o  número da semana for menor que 1 
    */   
    while ($iSemana < 1) {

      $iSemana += $iTotalSemanasAno;
      if ($iSemana > 0) {
        break;
      }
      $iAno--; // Vou para o ano anterior
      $iMax1            = date('W', mktime(0, 0, 0, 12, 24, $iAno));
      $iMax2            = date('W', mktime(0, 0, 0, 12, 31, $iAno));
      $iTotalSemanasAno = $iMax1 > $iMax2 ? $iMax1 : $iMax2;

    }


    /* Considerando que cada mês tenha 6 semanas (máximo), começo a busca da semana desejada 
       a partir de um mês próximo ao que contém a semana desejada
    */
    $iMes       = ceil($iSemana / 6); // Se algum dia der problema, dá pra dividir por um número maior (7, 8)
    $iDia       = -6;
    $iSemanaTmp = 0;
    while ($iSemanaTmp != $iSemana) { // Percorro cada semana na busca da semana desejada

      $iDia      += 7; // Vou  para a próxima semana
      $iSemanaTmp = date('W', mktime(0, 0, 0, $iMes, $iDia, $iAno));

    }
    /* Se o dia da semana for 0 (domingo), tenho que diminuir pelo menos um 1 dia, pois para a função date,
       quando informado o parâmetro W, a semana é contada começando em segunda, e na rotina, como começando em
       domingo, então, se eu passar um domingo, para a função, este será o primeiro dia, enquando que na verdade
       nem faria parte da semana solicitada, pois seria o primeiro dia após o término da semana (que termina
       em sábado)
    */
    if (date('w', mktime(0, 0, 0, $iMes, $iDia, $iAno)) == 0) {
      $iDia -= 1; // Sábado, último dia da semana desejada
    }
    /* Já encontrei um dia da semana desejada, então basta obter os demais dias da semana, usando a mesma função,
       passando somente o primeiro argumento */
    return montasemana(date('d/m/Y', mktime(0, 0, 0, $iMes, $iDia, $iAno)));

  }

}

$xsemana = montasemana('', $semana, $calendario);
$pdf     = new PDF();
$pdf->Open();
$pdf->AliasNbPages ();
$sqlcardapioescola        = " select me32_i_codigo as cardapioescolacod,ed18_c_nome from mer_cardapioescola inner join escola  on  escola.ed18_i_codigo = mer_cardapioescola.me32_i_escola where me32_i_tipocardapio = $cardapio $condicao3 ";
$resultcardapioescola     = pg_query($sqlcardapioescola);
$linhascardapioescola     = pg_num_rows($resultcardapioescola);
for($rrr=0; $rrr < $linhascardapioescola; $rrr++){

  db_fieldsmemory($resultcardapioescola,$rrr);
  if ($codescola!="") {
    $condicao4 = "";    
  } else {
  
    $condicao4  = " AND exists(select * from mer_cardapiodiaescola where me37_i_cardapiodia = me12_i_codigo";
    $condicao4 .= " and me37_i_cardapioescola = $cardapioescolacod)";
  
  }
  $head1 = "RELATÓRIOS DE CARDÁPIOS";
  $head2 = "Ano Cardápio: ".pg_result($result,0,'me27_i_ano');
  $head3 = "Cardápio: ".pg_result($result,0,'me27_c_nome');
  $head4 = "Semana: $xsemana[0] à $xsemana[6]";
  $head5 = "Escola: $ed18_c_nome";
  $pdf->ln(5);
  $pdf->addpage("L");
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',10);
  $resultdias = $cldiasemana->sql_record($cldiasemana->sql_query_rh("",
                                                                    "ed32_i_codigo,ed32_c_descr",
                                                                    "ed32_i_codigo",
                                                                    " ed04_c_letivo = 'S' and ed32_i_codigo in(2,3,4,5,6)"
                                                                   ));
                                                                 
  $pdf->cell(40,25,"Tipo Refeição - Horário",1,0,"C",1);
  $quebra = "0";
  
  for ($x=0; $x < $cldiasemana->numrows; $x++) {
	
    db_fieldsmemory($resultdias, $x);
    if ($x == ($cldiasemana->numrows) - 1) {
      $quebra = 1;
    }
    $ind     = $ed32_i_codigo-1;
    $dia_mes = substr($xsemana[$ind],0,5);
    $pdf->cell(48,25,"$ed32_c_descr - $dia_mes", 1, $quebra, "C", 1);
  
  }
  $campos  = " distinct me03_i_codigo,";
  $campos .= " me03_c_tipo,";
  $campos .= " me03_c_inicio,";
  $campos .= " me03_c_fim,";
  $campos .= " me03_i_orden ";
  $sSql    = $clmer_cardapiotipo->sql_query("",$campos,"me03_i_orden"," me27_i_codigo = $cardapio"); 
  $result2 = $clmer_cardapiotipo->sql_record($sSql);
  $linhas2 = pg_num_rows($result2);
  if ($semana==0) {
    $aSemana = montasemana("","01",$calendario+1);
  } else {
  	$aSemana = montasemana("",$semana,$calendario);
  }
  
  $vet          = array ("", "", "", "", "", "" );
  $altura       = 2.8;
  $borda        = true;
  $espaco       = 2;
  $preenche     = 0;
  $naousaespaco = false;
  $usar_quebra  = true;
  $campo_testar = 2;
  $lagurafixa   = 0;
  for($x = 0; $x < $linhas2; $x ++) {
  	
    db_fieldsmemory ( $result2, $x );
    $vet [0] = "\n \n $me03_c_tipo \n \n $me03_c_inicio/$me03_c_fim \n \n ";
    for($i = 1; $i < 6; $i ++) { 

      $dat        = substr($aSemana [$i], 6, 4) . "-" . substr($aSemana[$i],3,2) . "-" . substr($aSemana[$i],0,2);
      $campos        = " me12_i_codigo,me01_i_codigo,me01_c_nome,me01_f_versao,me03_c_fim ";
      $sWhere        = " me12_d_data = '$dat' AND me12_i_tprefeicao = $me03_i_codigo ";
      $sWhere       .= " AND me01_i_tipocardapio = $cardapio $condicao2 $condicao4";
      $sSql          = $clmer_cardapiodia->sql_query_horario("",$campos,"me01_c_nome",$sWhere);
      $resultcard    = $clmer_cardapiodia->sql_record($sSql);
      $linhascard    = $clmer_cardapiodia->numrows;
      
      if ($linhascard > 0) {
      	
      	$sep  = "";
        $str  = "";
        for ($y = 0; $y < $linhascard; $y ++) {
        	
          db_fieldsmemory($resultcard,$y);
          $part = $me01_c_nome;
          $str .= "$sep $part";
          $sep = "\n\n ";
          
        }
        $vet [$i] = "\n" . $str . "\n";
        
      } else {
      	
      	$sSql          = $clferiado->sql_query("","*","","ed54_d_data= '$dat' AND ed54_c_dialetivo = 'N' "); 
        $resultferiado = $clferiado->sql_record($sSql);
        if ($clferiado->numrows > 0) {
        	
          db_fieldsmemory($resultferiado,0);
          $nome = substr($ed96_c_descr,0,20)." - ".substr($ed54_c_descr,0,20);
          
        } else {
          $nome = "";
        }
        $vet [$i] = "\n $nome \n ";
      }
      $pdf->setfillcolor(235);
    }
    $pdf->SetWidths(array(40, 48, 48, 48, 48, 48));  
    $pdf->SetAligns(array("L", "C", "C", "C", "C", "C"));
    $pdf->setfont('arial','',9);
    $set_altura_row = $pdf->h - 32;
    $pdf->Row_multicell($vet,
                      $altura, 
                      $borda, 
                      $espaco, 
                      $preenche, 
                      $naousaespaco, 
                      $usar_quebra, 
                      $campo_testar, 
                      $set_altura_row, 
                      $lagurafixa
                     );
   
  }//for cardapio tipo
  unset($vet);
  $vet     = explode("/",$aSemana[0]);
  $inicio  = $vet[2]."-".$vet[1]."-".$vet[0];
  $vet     = explode("/",$aSemana[6]);
  $fim     = $vet[2]."-".$vet[1]."-".$vet[0];
  $sqlt    = " select me29_i_refeicao, ";
  $sqlt   .= "             me29_i_alimentonovo, ";
  $sqlt   .= "             me29_i_alimentoorig, ";
  $sqlt   .= "             me29_f_quantidade, ";
  $sqlt   .= "             me12_d_data, ";
  $sqlt   .= "             matmater.m60_descr, ";
  $sqlt   .= "             matmater2.m60_descr as m60_descr2, ";
  $sqlt   .= "             me29_d_inicio, ";
  $sqlt   .= "             me29_d_fim, ";
  $sqlt   .= "             me01_c_nome ";
  $sqlt   .= "      from mer_cardapiodia ";
  $sqlt   .= "        inner join mer_subitem on me29_i_refeicao=mer_cardapiodia.me12_i_cardapio ";
  $sqlt   .= "        inner join mer_cardapioitem on me07_i_cardapio=me29_i_refeicao ";
  $sqlt   .= "        AND me07_i_alimento=me29_i_alimentonovo ";
  $sqlt   .= "        inner join matmater on matmater.m60_codmater=me29_i_alimentonovo ";
  $sqlt   .= "        inner join matmater as matmater2 on matmater2.m60_codmater=me29_i_alimentonovo ";
  $sqlt   .= "        inner join mer_tprefeicao on me03_i_codigo=mer_cardapiodia.me12_i_tprefeicao ";
  $sqlt   .= "       inner join mer_cardapio on me01_i_codigo = me29_i_refeicao ";
  $sqlt   .= "      where me29_d_inicio <= '$inicio' AND me29_d_fim >= '$fim' ";
  $sqlt   .= "            AND me29_d_inicio<=me12_d_data AND me29_d_fim>=me12_d_data ";
  $sqlt   .= "      order by me29_i_refeicao";
  $resultt = pg_query($sqlt) or die("Erro - dados da refeição");
  $pdf->setfont('arial','',6);
  $datas="";
  $sep=" ";
  if (pg_num_rows($resultt) > 0) {
  	
    db_fieldsmemory($resultt,0);
    $ref = $me29_i_refeicao;
 
  }
  for ($x=0;$x<pg_num_rows($resultt);$x++) {
	
    db_fieldsmemory($resultt,$x);
    if ($ref==$me29_i_refeicao) {
  	
      $datas=$datas.$sep.db_formatar($me12_d_data,"d");
      $sep=", ";
    
    } else {
  	
      $dat = $me12_d_data;
      $ref = $me29_i_refeicao;
      db_fieldsmemory($resultt,$x-1);
      $pdf->cell(260,4,"*Dia(s) $datas no $me03_c_tipo o item $m60_descr foi substituido por $m60_descr2",0,1,"R",0);
      $datas = $me12_d_data;
    
    }
  }
  if (pg_num_rows($resultt)>0) {
	
    db_fieldsmemory($resultt,$x-1);
    $pdf->cell(260,4,"*Dia(s) $datas na refeicao $me01_c_nome o item $m60_descr foi substituido por $m60_descr2",0,1,"R",0);
  
  }
  $pdf->setfont('arial','b',9);  
  
  $sSql        = $clmer_cardapionutri->sql_query("", "distinct z01_nome,me02_c_crn", "", "me01_i_tipocardapio=$cardapio");
  $resultnutri = $clmer_cardapionutri->sql_record($sSql); 
  //die($sSql);
 
  $pdf->cell(200,4,"",0,1,"R",0);
  $pdf->cell(200,4,"",0,1,"R",0);
  $pdf->cell(200,4,"",0,1,"R",0);
  $pdf->cell(200,4,"",0,1,"R",0);
  $pdf->cell(200,4,"",0,1,"R",0);
  $pdf->cell(200,4,"",0,1,"R",0); 
  $pdf->cell(200,4,"",0,1,"R",0);
 if ($clmer_cardapionutri->numrows>0) {   
  for($k=0;$k<$clmer_cardapionutri->numrows;$k++){
  	db_fieldsmemory($resultnutri,$k);
  	$pdf->cell(20,10,"",0,1,"R",0);
  	$pdf->cell(200,4,"Nutricionista: ",0,0,"R",0);
    $pdf->cell(50,4,$z01_nome,0,1,"R",0);
    $pdf->cell(200,4,"CRN: ",0,0,"R",0);    
    $pdf->cell(12,4,trim($me02_c_crn),0,1,"R",0);
    $pdf->cell(200,10,"",0,1,"R",0); 
  }
 } else { 	
 	$pdf->cell(220,4,"Nenhum nutricionista vinculado a refeição!",0,1,"R",0);
   
 }
  unset($vet);
}
$pdf->Output ();
?>