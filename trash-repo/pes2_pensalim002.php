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
include("classes/db_selecao_classe.php");
$clselecao = new cl_selecao();

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
$head2 = "RESUMO DE PENSOES ALIMENTICIAS";
$head4 = "PERIODO : ".$mes." / ".$ano;

$where = " ";
if(trim($selecao) != ""){
  $result_selecao = $clselecao->sql_record($clselecao->sql_query_file($selecao,db_getsession("DB_instit")));
  if($clselecao->numrows > 0){
    db_fieldsmemory($result_selecao, 0);
    $where = " and ".$r44_where;
    $head8 = "SELEÇÃO: ".$selecao." - ".$r44_descr;
  }
}

if($tipo == 's'){
  $head6  = "SALARIO ";
  $xvalor = " r52_valor + r52_valfer "; 
}elseif($tipo == 'c'){
  $head6 = "COMPLEMENTAR ";
  $xvalor = " r52_valcom"; 
}elseif($tipo == '3'){
  $head6 = "13o.  SALÁRIO ";
  $xvalor = " r52_val13 "; 
}elseif($tipo == 'r'){
  $head6 = "Rescisão ";
  $xvalor = " r52_valres "; 
}

if($ordem == 'n'){
  $ordem = " order by rh01_regist ";
}else{
  if($func == 's'){
//  $ordem = " order by w01_work01, w01_work02||r52_dvagencia, w01_work06 ";
    $ordem = " order by w01_work01, z01_nome ";
  }else{
    $ordem = " order by w01_work01, w01_work06 ";
  }
}

$sql = "
select * from 
(
       select case when trim(r52_codbco) = '' or r52_codbco is null 
                   then '000' 
		   else r52_codbco 
	      end as w01_work01,
              case when db90_descr is not null 
	           then db90_descr 
		   else 'SEM BANCO' 
	      end as xbanco,
              to_char(to_number(case when trim(r52_codage) = '' 
	                     then '0' 
			     else r52_codage 
			end,'99999'),'99999') as w01_work02,
	      case when r52_dvagencia is null 
	           then '' 
		   else r52_dvagencia 
	      end as r52_dvagencia,
	      case when r52_conta   is null 
	           then r52_conta 
		   else r52_conta 
	      end as w01_work03,
	      case when r52_dvconta is null 
	           then '' 
		   else r52_dvconta 
	      end as r52_dvconta,
	      r52_numcgm as w01_work04,
	      cgm.z01_nome as w01_work06,
	      a.z01_nome,
	      rh01_regist,
	      $xvalor as w01_work05 
       from pensao
            inner join cgm       on r52_numcgm = z01_numcgm
      	    inner join rhpessoal on rh01_regist = r52_regist
						inner join rhpessoalmov on rh01_regist = rh02_regist 
						                       and rh02_anousu = ".db_anofolha()." 
						                       and rh02_mesusu = ".db_mesfolha()." 
                                   and rh02_instit = ".db_getsession("DB_instit")."
            inner join rhlota       on r70_codigo  = rh02_lota
                                   and r70_instit  = rh02_instit
	          inner join cgm a     on a.z01_numcgm = rh01_numcgm
	          left  join db_bancos on r52_codbco::varchar(10) = db90_codban
       where r52_anousu = $ano 
         and r52_mesusu = $mes 
         $where

	 and $xvalor > 0
) as x
$ordem
       ";
//echo $sql ; exit;
$result = pg_exec($sql);
//db_criatabela($result);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Nao existem lancamentos no periodo de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$alt = 5;
$total = 0;
$total_g = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);

db_fieldsmemory($result,0);

if($func != 's'){
  
  if($tipoquebra == 'a'){
    $quebra = substr($w01_work01,0,3).$w01_work02;
  }else{  
    $quebra = substr($w01_work01,0,3);
  }
  $troca = 0;

  for($x = 0; $x < pg_numrows($result);$x++){
     db_fieldsmemory($result,$x);
     if ($quebra != substr($w01_work01,0,3).$w01_work02 && $tipoquebra == 'a'){
        $pdf->setfont('arial','b',8);
        $pdf->cell(80,$alt,'TOTAL DA AGENCIA',"T",0,"C",0);
        $pdf->cell(40,$alt,'',"T",0,"C",0);
        $pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);
        $pdf->sety(300);
        $total = 0;
        $quebra = substr($w01_work01,0,3).$w01_work02;
     }
     if ($quebra != substr($w01_work01,0,3) && $tipoquebra != 'a'){
        $pdf->setfont('arial','b',8);
        $pdf->cell(80,$alt,'TOTAL DO BANCO',"T",0,"C",0);
        $pdf->cell(40,$alt,'',"T",0,"C",0);
        $pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);
        $pdf->sety(300);
        $total = 0;
        $quebra = substr($w01_work01,0,3);
     }
     if ($pdf->gety() > $pdf->h - 30 || $troca == 0){
        $pdf->addpage();
        $pdf->setfont('arial','b',8);
        if($tipoquebra == 'a'){
          $pdf->cell(80,$alt,$xbanco.' - Agencia : '.$w01_work02,0,1,"C",0);
        }else{
          $pdf->cell(80,$alt,$xbanco,0,1,"C",0);
        }
        $pdf->ln(3);
        $pdf->cell(80,$alt,'NOME DO BENEFICIARIO',1,0,"C",1);
        $pdf->cell(20,$alt,'AGENCIA',1,0,"C",1);
        $pdf->cell(20,$alt,'CONTA',1,0,"C",1);
        $pdf->cell(30,$alt,'VALOR',1,1,"C",1);
        $troca = 1;
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(80,$alt,$w01_work06,0,0,"l",0);
     $pdf->cell(20,$alt,$w01_work02.$r52_dvagencia,0,0,"R",0);
     $pdf->cell(20,$alt,$w01_work03.$r52_dvconta,0,0,"R",0);
     $pdf->cell(30,$alt,db_formatar($w01_work05,'f'),0,1,"R",0);
     $total += $w01_work05;
     $total_g += $w01_work05;
  }
  $pdf->setfont('arial','b',8);
  if($tipoquebra == 'a'){
    $pdf->cell(80,$alt,'TOTAL DA AGENCIA',"T",0,"C",0);
  }else{
    $pdf->cell(80,$alt,'TOTAL DO BANCO',"T",0,"C",0);
  }
  $pdf->cell(40,$alt,'',"T",0,"C",0);
  $pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);

  $pdf->ln(5);
  $pdf->cell(80,$alt,'TOTAL DO GERAL',"T",0,"C",0);
  $pdf->cell(40,$alt,'',"T",0,"C",0);
  $pdf->cell(30,$alt,db_formatar($total_g,'f'),"T",1,"R",0);
}else{

  $troca = 0;

  for($x = 0; $x < pg_numrows($result);$x++){
     db_fieldsmemory($result,$x);
     if ($pdf->gety() > $pdf->h - 30 || $troca == 0){
        $pdf->addpage('L');
        $pdf->setfont('arial','b',8);
        $pdf->ln(3);
        $pdf->cell(15,$alt,'MATRIC',1,0,"C",1);
        $pdf->cell(80,$alt,'NOME DO FUNCIONARIO',1,0,"C",1);
        $pdf->cell(15,$alt,'NUMCGM',1,0,"C",1);
        $pdf->cell(80,$alt,'NOME DO BENEFICIARIO',1,0,"C",1);
        $pdf->cell(10,$alt,'BANCO',1,0,"C",1);
        $pdf->cell(20,$alt,'AGENCIA',1,0,"C",1);
        $pdf->cell(20,$alt,'CONTA',1,0,"C",1);
        $pdf->cell(30,$alt,'VALOR',1,1,"C",1);
        $troca = 1;
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(15,$alt,$rh01_regist,0,0,"l",0);
     $pdf->cell(80,$alt,$z01_nome,0,0,"l",0);
     $pdf->cell(15,$alt,$w01_work04,0,0,"l",0);
     $pdf->cell(80,$alt,$w01_work06,0,0,"l",0);
     $pdf->cell(10,$alt,$w01_work01,0,0,"l",0);
     $pdf->cell(20,$alt,$w01_work02.$r52_dvagencia,0,0,"R",0);
     $pdf->cell(20,$alt,$w01_work03.$r52_dvconta,0,0,"R",0);
     $pdf->cell(30,$alt,db_formatar($w01_work05,'f'),0,1,"R",0);
     $total += $w01_work05;
     $total_g += $w01_work05;
  }
  $pdf->ln(5);
  $pdf->cell(200,$alt,'TOTAL DO GERAL',"T",0,"C",0);
  $pdf->cell(40,$alt,'',"T",0,"C",0);
  $pdf->cell(30,$alt,db_formatar($total_g,'f'),"T",1,"R",0);
}

$pdf->Output();
   
?>