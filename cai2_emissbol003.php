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
db_postmemory($HTTP_SERVER_VARS);
$seleciona_conta = '';
$descr_conta = 'TODAS AS CONTAS';

if($conta != 0) {
	
  $seleciona_conta = " and ( a.k12_conta = {$conta} or e.k12_conta = {$conta} )";
  
  $sSqlContas  = "select *                                                                   ";
  $sSqlContas .= "  from saltes                                                              ";
  $sSqlContas .= "       inner join conplanoexe   on c62_reduz  = k13_reduz                  "; 
  $sSqlContas .= "                               and c62_anousu = ".db_getsession('DB_anousu');
  $sSqlContas .= "       inner join conplanoreduz on c62_reduz  = c61_reduz                  ";
  $sSqlContas .= "                               and c61_instit = ".db_getsession('DB_instit');
  $sSqlContas .= "                               and c61_anousu = c62_anousu                 ";
  $sSqlContas .= " where k13_conta = {$conta}                                                ";
  $result = db_query($sSqlContas);
  db_fieldsmemory($result,0);
  $descr_conta = "CONTA : ".$conta.' - '.$k13_descr;
  
}

$selecao = 'TODOS OS CAIXAS';
$seleciona = '';

$ordem = " order by a.k12_conta, a.k12_data, a.k12_id, a.k12_autent ";

if ($caixa != 0) {
	 
  $seleciona = " and a.k12_id = {$caixa}";
  $ordem     = " order by a.k12_data, a.k12_conta, a.k12_autent ";
  
  $sql  = "select *                                         ";
  $sql .= "  from cfautent                                  ";
  $sql .= " where k11_id = {$caixa}                         ";
  $sql .= "   and k11_instit = " . db_getsession('DB_instit');
  $result = db_query($sql);
  db_fieldsmemory($result,0);
  $selecao = "CAIXA : ".$caixa.' - '.$k11_local;
  
}

if ($tiporel == "r") {
  $ordem = " order by a.k12_data, a.k12_id, a.k12_autent ";
}

$head1 = "RELATÓRIO DE AUTENTICAÇÕES " . ($tiporel == "c"?"COMPLETO":"RESUMIDO");
$head3 = "DATA INICIAL: " . db_formatar(@$datai,"d");;
$head5 = "DATA FINAL .: " . db_formatar(@$dataf,"d");;
$head7 = $selecao;
$head9 = $descr_conta;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage("L");

$CoL1 = 15;
$CoL2 = 25;
$CoL3 = 20;
$CoL4 = 20;
$CoL5 = 20;
$CoL6 = 35;
$CoL7 = 25;
$CoL8 = 25;

$StrPad1 = 20;
$StrPad2 = 26;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$exercicio = $GLOBALS["DB_anousu"];

$sCampos  = "case                             ";
$sCampos .= "  when b.k12_numpre is not null  ";
$sCampos .= "   then 0                        ";
$sCampos .= "  else null                      ";
$sCampos .= "end as cornump_numpre,           ";
$sCampos .= "case                             ";
$sCampos .= "  when c.k12_empen  is not null  ";
$sCampos .= "    then 0                       ";
$sCampos .= "  else null                      ";
$sCampos .= "end as coremp_empen,             ";
$sCampos .= "case                             ";
$sCampos .= "  when e.k12_conta is not null   ";
$sCampos .= "    then 0                       ";
$sCampos .= "  else null                      ";
$sCampos .= "end as corlanc_conta,            ";
$sCampos .= "case                             ";
$sCampos .= "  when d.k12_codcla is not null  ";
$sCampos .= "    then 0                       ";
$sCampos .= "  else null                      ";
$sCampos .= "end as corcla_codcla             ";

$sOrdem = "corrente_data, corrente_id, corrente_autent";
if ($tiporel == "c") {
	  
	$sCampos  = "b.k12_numpre          as cornump_numpre, ";                                                   
  $sCampos .= "b.k12_numpar          as cornump_numpar, ";                                                   
  $sCampos .= "b.k12_receit          as cornump_receit, ";                                                   
  $sCampos .= "x.k02_descr           as cornump_descr,  ";                                                   
  $sCampos .= "b.k12_valor           as cornump_valor,  ";                                                   
  $sCampos .= "c.k12_empen           as coremp_empen,   ";                                                   
  $sCampos .= "c.k12_codord          as coremp_codord,  ";                                                   
  $sCampos .= "c.k12_cheque          as coremp_cheque,  ";                                                   
  $sCampos .= "e60_codemp            as coremp_codemp,  ";                                                   
  $sCampos .= "e60_anousu            as coremp_anousu,  ";                                                   
  $sCampos .= "e20_pagordem,                            ";                                                   
  $sCampos .= "d.k12_codcla          as corcla_codcla,  ";                                                   
  $sCampos .= "e.k12_conta           as corlanc_conta,  ";                                                   
  $sCampos .= "p.c60_descr           as corlanc_descr,  ";                                                   
  $sCampos .= "e.k12_codigo          as corlanc_slip,   ";                                                   
  $sCampos .= "z01_nome,                                ";                                                   
  $sCampos .= "disbanco.dtarq                           ";
	
	$sOrdem = "corrente_data, tipo, corrente_conta, corrente_data, corrente_id, corrente_autent";
	
} 

$sSql  = "select x.*,                                                                                                  ";
$sSql .= "       case                                                                                                  ";
$sSql .= "         when cornump_numpre is not null                                                                     ";
$sSql .= "           then 'TRIBUTARIO'                                                                                 ";
$sSql .= "         else                                                                                                ";
$sSql .= "           case                                                                                              ";
$sSql .= "             when coremp_empen   is not null and corlanc_conta is not null                                   ";
$sSql .= "               then 'RP'                                                                                     ";
$sSql .= "             else                                                                                            ";
$sSql .= "               case                                                                                          ";
$sSql .= "                 when coremp_empen   is not null                                                             ";
$sSql .= "                   then 'EMPENHO'                                                                            ";
$sSql .= "                 else                                                                                        ";
$sSql .= "                   case                                                                                      ";
$sSql .= "                     when corlanc_conta  is not null                                                         ";
$sSql .= "                       then 'SLIP'                                                                           ";
$sSql .= "                     else                                                                                    ";
$sSql .= "                       case                                                                                  ";
$sSql .= "                         when corcla_codcla  is not null                                                     ";
$sSql .= "                           then 'CLASSIFICAÇÃO'                                                             ";
$sSql .= "                         else                                                                                ";
$sSql .= "                           'ERRO'                                                                            ";
$sSql .= "                       end                                                                                   ";
$sSql .= "                   end                                                                                       ";
$sSql .= "               end                                                                                           ";
$sSql .= "           end                                                                                               ";
$sSql .= "       end as tipo                                                                                           ";
$sSql .= " from ( select distinct                                                                                      ";
$sSql .= "               a.k12_id              as corrente_id,                                                         ";
$sSql .= "               a.k12_data            as corrente_data,                                                       ";
$sSql .= "               a.k12_autent          as corrente_autent,                                                     ";
$sSql .= "               a.k12_hora            as corrente_hora,                                                       ";
$sSql .= "               a.k12_conta           as corrente_conta,                                                      ";
$sSql .= "               w.k13_descr           as corrente_descr,                                                      ";
$sSql .= "               round(a.k12_valor,2)  as corrente_valor,                                                      ";
$sSql .= "               {$sCampos} "; 
$sSql .= "          from corrente a                                                                                    ";
$sSql .= "               left join saltes  w                    on a.k12_conta           = w.k13_conta                 ";
$sSql .= "               left join cornump b                    on b.k12_id              = a.k12_id                    ";
$sSql .= "                                                     and b.k12_data            = a.k12_data                  ";
$sSql .= "                                                     and b.k12_autent          = a.k12_autent                ";
$sSql .= "               left join tabrec  x                    on b.k12_receit          = x.k02_codigo                ";
$sSql .= "               left join coremp  c                    on c.k12_id              = a.k12_id                    ";
$sSql .= "                                                     and c.k12_data            = a.k12_data                  ";
$sSql .= "                                                     and c.k12_autent          = a.k12_autent                ";
$sSql .= "               left join empempenho y                 on y.e60_numemp          = c.k12_empen                 ";
$sSql .= "               left join cgm                          on y.e60_numcgm          = z01_numcgm                  ";
$sSql .= "               left join corcla  d                    on d.k12_id              = a.k12_id                    ";
$sSql .= "                                                     and d.k12_data            = a.k12_data                  ";
$sSql .= "                                                     and d.k12_autent          = a.k12_autent                ";
$sSql .= "               left join discla                       on discla.codcla         = d.k12_codcla                ";
$sSql .= "               left join disbanco                     on discla.codret         = disbanco.codret             ";
$sSql .= "               left join corlanc e                    on e.k12_id              = a.k12_id                    ";
$sSql .= "                                                     and e.k12_data            = a.k12_data                  ";
$sSql .= "                                                     and e.k12_autent          = a.k12_autent                ";
$sSql .= "               left join corgrupocorrente             on a.k12_id              = k105_id                     ";
$sSql .= "                                                     and a.k12_autent          = k105_autent                 ";
$sSql .= "                                                     and a.k12_data            = k105_data                   ";
$sSql .= "               left join retencaocorgrupocorrente     on e47_corgrupocorrente  = k105_sequencial             ";
$sSql .= "               left join retencaoreceitas             on e47_retencaoreceita   = e23_sequencial              ";
$sSql .= "               left join retencaopagordem             on e23_retencaopagordem  = e20_sequencial              ";
$sSql .= "               left join conplanoreduz z              on e.k12_conta           = z.c61_reduz                 ";
$sSql .= "                                                     and z.c61_anousu          =".db_getsession("DB_anousu");
$sSql .= "               left join conplano p                   on z.c61_codcon          = p.c60_codcon                ";
$sSql .= "                                                     and z.c61_anousu          = p.c60_anousu                ";
$sSql .= "               where a.k12_instit = " . db_getsession('DB_instit');
$sSql .= "                 and a.k12_data between '$datai' and '$dataf'                                                ";
$sSql .= "                 $seleciona                                                                                  ";
$sSql .= "                 $seleciona_conta                                                                            ";
$sSql .= "                 $ordem                                                                                      ";
$sSql .= "      ) as x order by {$sOrdem}";
$result  = db_query($sSql) or die ($sSql);
$numrows = pg_numrows($result);

$QuebraPagina = 10;
$total = 0;
$pdf->SetFont('Arial','',8);
$pdf->SetTextColor(0,0,0);
$pdf->setfillcolor(235);

$velho_nump_id     = ""; 
$velho_nump_data   = "";
$velho_nump_autent = "";

$passa     = true;
$tipo_old  = "";
$total     = 0;
$total_neg = 0;

if ($tiporel == "c") {
  $sublinha = "T";
} else {
  $sublinha = "";
}

$data_ant = "";
if ($numrows > 0) {
	db_fieldsmemory($result, 0);
	$data_ant = $corrente_data;
}

for ($i=0;$i<$numrows;$i++) {
  $coremp  = false;
  $corlanc = false;
  $cornump = false;
  $corcla  = false;
  db_fieldsmemory($result,$i);

  if ($tiporel == "r") {

		if ($data_ant != $corrente_data) {
			$pdf->ln();
			$data_ant = $corrente_data;
		}

	}

  if(trim($coremp_empen)!="" || trim($coremp_cheque)!="" || trim($coremp_empen)!="" || trim($coremp_anousu)!=""){
    $coremp = true;
  }
  if(trim($corlanc_conta)!="" || trim($corlanc_descr)!="" || trim($corlanc_slip)!=""){
    $corlanc = true;
  }
  if(trim($cornump_numpre)!="" || trim($cornump_numpar)!="" || trim($cornump_receit)!="" || trim($cornump_descr)!="" || trim($cornump_valor)!=""){
    $cornump = true;
    $passa = true;
    if($velho_nump_id ==$corrente_id && $velho_nump_data == $corrente_data && $velho_nump_autent == $corrente_autent){
      $passa = false;
    }
  }
  if(trim($corcla_codcla)!=""){
    $corcla = true;
  }

  if ($pdf->gety() > $pdf->h - 32 || $i == 0) {
    if ($pdf->gety() > $pdf->h - 32) {
      $pdf->AddPage("L");
    }
    
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell($CoL1,5,"CAIXA","TBL",0,"C",1);
    $pdf->Cell($CoL2,5,"DATA","TB",0,"C",1);
    $pdf->Cell($CoL3,5,"AUTENT","TB",0,"C",1);
    $pdf->Cell($CoL4,5,"HORA","TB",0,"C",1);
    $pdf->Cell($CoL5,5,"CREDITO","TB",0,"C",1);
    $pdf->Cell(65,5,"DESCRIÇÃO","TB",0,"E",1);
    $pdf->Cell($CoL6,5,"VALOR EM R$","TB",0,"R",1);
		$pdf->Cell(30,5,"TIPO","RTB",1,"C",1);
		
  }
  
  if (($cornump==true  && $passa == true) || $cornump==false) {
  	
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell($CoL1,5,@$corrente_id,$sublinha,0,"C",0);
    $pdf->Cell($CoL2,5,db_formatar(@$corrente_data,'d'),$sublinha,0,"L",0);
    $pdf->Cell($CoL3,5,@$corrente_autent,$sublinha,0,"C",0);
    $pdf->Cell($CoL4,5,@$corrente_hora,$sublinha,0,"C",0);
    
    if ($tipo=='TRIBUTARIO' || $tipo == 'CLASSIFICAÇÃO' ) {
      $tt='Deb';
    } else {
      $tt='Cre';
    }
    
    $pdf->Cell($CoL5,5,"$tt-".@$corrente_conta,$sublinha,0,"C",0);
    $pdf->Cell(65,5,@$corrente_descr,$sublinha,0,"L",0);
    
    if ($corrente_valor < 0) {
      $pdf->SetTextColor(255,0,0);
    }
    
    $pdf->Cell($CoL6,5,str_pad(number_format($corrente_valor,2,",","."),14," ",STR_PAD_LEFT),$sublinha,0,"R",0);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(30,5,$tipo,$sublinha,0,"C",0);
    $pdf->Cell(20,5,$corcla_codcla,$sublinha,0,"C",0);
    $pdf->Cell(30,5,db_formatar($dtarq,"d"),$sublinha,1,"C",0);
    
    if ($corrente_valor > 0) {
      $total     += $corrente_valor;
    } else {
      $total_neg += $corrente_valor;
    }
    
  }
  
  if ($tiporel == "c") {
      $pdf->SetTextColor(0,0,0);
      $pdf->SetFont('Arial','',8);
      if($coremp==true && $corlanc==true){   
         
        $pdf->Cell(21,5,"RP:".@$coremp_empen,0,0,"C",0);
        $pdf->Cell(21,5,@$coremp_codemp."/".@$coremp_anousu,0,0,"C",0);
        $pdf->Cell(21,5,"OP:".@$coremp_codord,0,0,"C",0);
        $pdf->Cell(21,5,@$coremp_codemp."/".@$coremp_anousu,0,0,"C",0);
        $pdf->Cell(21,5,"Che:".@$coremp_cheque,0,0,"C",0);
        $pdf->Cell(21,5,@$corlanc_slip,0,0,"C",0);
        $pdf->Cell(21,5,"Credor:".@$e60_numcgm,0,0,"C",0);
        $pdf->Cell(40,5,@$z01_nome,0,1,"L",0);
        
      }else if($coremp==true && $corlanc==false){
        $pdf->Cell(20,5,"Emp.:".@$coremp_empen,0,0,"C",0);
        $pdf->Cell(20,5,@$coremp_codemp."/".@$coremp_anousu,0,0,"C",0);
        $pdf->Cell(21,5,"OP:".@$coremp_codord,0,0,"C",0);
        $pdf->Cell(20,5,"Cheque:".@$coremp_cheque,0,0,"C",0);
        $pdf->Cell(21,5,"Credor:".@$e60_numcgm,0,0,"C",0);
        $pdf->Cell(40,5,@$z01_nome,0,1,"L",0);
      }else if($coremp==false && $corlanc==true){
        $pdf->Cell(20,5,"Slip:".@$corlanc_slip,0,0,"C",0);
        $pdf->Cell(20,5,@$corlanc_conta,0,0,"C",0);
        $pdf->Cell(65,5,@$corlanc_descr,0,1,"L",0);
      }else if($cornump==true){
        $pdf->Cell(20,5,"Numpre:".@$cornump_numpre,0,0,"C",0);
        if ($e20_pagordem != "") {
          $pdf->Cell(20,5,"OP:".@$e20_pagordem,0,0,"C",0);
        }
        $pdf->Cell(25,5,@$cornump_receit,0,0,"C",0);
        $pdf->Cell(40,5,@$cornump_descr,0,0,"L",0);
        $pdf->Cell(20,5,@$cornump_valor,0,1,"R",0);
        $velho_nump_id     = $corrente_id;
        $velho_nump_data   = $corrente_data;
        $velho_nump_autent = $corrente_autent;
      }else if($corcla==true){
        $pdf->Cell(20,5,"Slip:".@$corcla_codcla,0,0,"C",0);
        $pdf->Cell(20,5,@$cornump_receit,0,0,"C",0);
        $pdf->Cell(40,5,@$cornump_descr,0,0,"L",0);
        $pdf->Cell(20,5,@$cornump_valor,0,1,"R",0);
      }
    }
  }

  if ( $total != 0 ) {
    $pdf->SetFont('Arial','B',10);
    $pdf->SetTextColor(255,0,0);
    $pdf->ln(3);
    $pdf->Cell($CoL1+$CoL2,5,"TOTAL POSITIVO",0,0,"L",0);
    $pdf->Cell($CoL3,5," ",0,0,"R",0);
    $pdf->Cell($CoL4,5," ",0,0,"R",0);
    $pdf->Cell($CoL5,5," ",0,0,"R",0);
    $pdf->Cell(65,5," ",0,0,"C",0);
    $pdf->Cell($CoL6,5,number_format($total,2,",","."),0,1,"R",0);
    $pdf->Cell($CoL1+$CoL2,5,"TOTAL NEGATIVO",0,0,"L",0);
    $pdf->Cell($CoL3,5," ",0,0,"R",0);
    $pdf->Cell($CoL4,5," ",0,0,"R",0);
    $pdf->Cell($CoL5,5," ",0,0,"R",0);
    $pdf->Cell(65,5," ",0,0,"C",0);
    $pdf->Cell($CoL6,5,number_format($total_neg,2,",","."),0,1,"R",0);
    $pdf->SetTextColor(0,0,0);
  }
  
  
$sql  = "select tipo,                                                                                              ";
$sql .= "       sum(corrente_valor) as valor                                                                       ";
$sql .= "  from ( select x.*,                                                                                      ";
$sql .= "                case                                                                                      ";
$sql .= "                  when cornump_numpre is not null                                                         ";
$sql .= "                    then 'TRIBUTARIO'                                                                     ";
$sql .= "                  else                                                                                    ";
$sql .= "                    case                                                                                  ";
$sql .= "                      when coremp_empen is not null and corlanc_conta is not null                         ";
$sql .= "                        then 'RP'                                                                         ";
$sql .= "                          else                                                                            ";
$sql .= "                            case                                                                          ";
$sql .= "                              when coremp_empen is not null                                               ";
$sql .= "                                then 'EMPENHO'                                                            ";
$sql .= "                                  else                                                                    ";
$sql .= "                                    case                                                                  ";
$sql .= "                                      when corlanc_conta is not null                                      ";
$sql .= "                                        then 'SLIP'                                                       ";
$sql .= "                                      else                                                                ";
$sql .= "                                        case                                                              ";
$sql .= "                                          when corcla_codcla is not null                                  ";
$sql .= "                                            then 'CLASSIFICAÇÕES'                                         ";
$sql .= "                                          else 'ERRO'                                                     ";
$sql .= "                                        end                                                               ";
$sql .= "                                    end                                                                   ";
$sql .= "                            end                                                                           ";
$sql .= "                    end                                                                                   ";
$sql .= "                end as tipo                                                                               ";
$sql .= "           from ( select a.k12_id     as corrente_id,                                                     ";
$sql .= "                         a.k12_data   as corrente_data,                                                   ";
$sql .= "                         a.k12_autent as corrente_autent,                                                 ";
$sql .= "                         a.k12_hora   as corrente_hora,                                                   ";
$sql .= "                         a.k12_conta  as corrente_conta,                                                  ";
$sql .= "                         w.k13_descr  as corrente_descr,                                                  ";
$sql .= "                         case                                                                             ";
$sql .= "                           when b.k12_id is not null                                                      ";
$sql .= "                             then b.k12_valor                                                             ";
$sql .= "                           else round(a.k12_valor,2)                                                      ";
$sql .= "                         end          as corrente_valor,                                                  ";
$sql .= "                         b.k12_numpre as cornump_numpre,                                                  ";
$sql .= "                         b.k12_numpar as cornump_numpar,                                                  ";
$sql .= "                         b.k12_receit as cornump_receit,                                                  ";
$sql .= "                         x.k02_descr  as cornump_descr,                                                   ";
$sql .= "                         b.k12_valor  as cornump_valor,                                                   ";
$sql .= "                         c.k12_empen  as coremp_empen,                                                    ";
$sql .= "                         c.k12_cheque as coremp_cheque,                                                   ";
$sql .= "                         e60_codemp   as coremp_codemp,                                                   ";
$sql .= "                         e60_anousu   as coremp_anousu,                                                   ";
$sql .= "                         d.k12_codcla as corcla_codcla,                                                   ";
$sql .= "                         e.k12_conta  as corlanc_conta,                                                   ";
$sql .= "                         p.c60_descr  as corlanc_descr,                                                   ";
$sql .= "                         e.k12_codigo as corlanc_slip,                                                    ";
$sql .= "                         z01_nome                                                                         ";
$sql .= "                    from corrente a                                                                       ";
$sql .= "                         left join saltes  w       on a.k12_conta  = w.k13_conta                          ";
$sql .= "                         left join cornump b       on b.k12_id     = a.k12_id                             ";
$sql .= "                                                  and b.k12_data   = a.k12_data                           ";
$sql .= "                                                  and b.k12_autent = a.k12_autent                         ";
$sql .= "                         left join tabrec  x       on b.k12_receit = x.k02_codigo                         ";
$sql .= "                         left join coremp  c       on c.k12_id     = a.k12_id                             ";
$sql .= "                                                  and c.k12_data   = a.k12_data                           ";
$sql .= "                                                  and c.k12_autent = a.k12_autent                         ";
$sql .= "                         left join empempenho y    on y.e60_numemp = c.k12_empen                          ";
$sql .= "                         left join cgm             on y.e60_numcgm = z01_numcgm                           ";
$sql .= "                         left join corcla  d       on d.k12_id     = a.k12_id                             ";
$sql .= "                                                  and d.k12_data   = a.k12_data                           ";
$sql .= "                                                  and d.k12_autent = a.k12_autent                         ";
$sql .= "                         left join corlanc e       on e.k12_id     = a.k12_id                             ";
$sql .= "                                                  and e.k12_data   = a.k12_data                           ";
$sql .= "                                                  and e.k12_autent = a.k12_autent                         ";
$sql .= "                         left join conplanoreduz z on e.k12_conta  = z.c61_reduz                          ";
$sql .= "                                                  and z.c61_anousu = ".db_getsession("DB_anousu");
$sql .= "                         left join conplano p      on z.c61_codcon = p.c60_codcon                         ";
$sql .= "                                                  and z.c61_anousu = p.c60_anousu                         ";
$sql .= "                         where a.k12_instit = " . db_getsession('DB_instit');
$sql .= "                           and a.k12_data between '$datai' and '$dataf'                                   ";
$sql .= "                           $seleciona                                                                     ";
$sql .= "                           $seleciona_conta                                                               ";
$sql .= "                           $ordem) as x                                                                   ";
$sql .= "          order by corrente_data, tipo, corrente_conta) as xxx group by tipo                              ";
$result = db_query($sql) or die($sql);

if ($tiporel == "c") {
  $pdf->AddPage("L");
} else {
  $pdf->ln(3);
}

$pdf->Cell(20,5,"TIPO","TBL",0,"C",1);
$pdf->Cell(50,5,"TOTAL","RTB",1,"C",1);

for ($regtotal=0;$regtotal<pg_numrows($result);$regtotal++) {
  db_fieldsmemory($result,$regtotal);
  $pdf->Cell(20,5,$tipo,"",0,"E",0);
  $pdf->Cell(50,5,number_format($valor,2,",","."),0,1,"R",0);
}


$pdf->Output();
?>