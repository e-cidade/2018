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

include(modification("fpdf151/pdf.php"));
include(modification("fpdf151/assinatura.php"));
include(modification("libs/db_sql.php"));
include(modification("libs/db_liborcamento.php"));
include(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$classinatura = new cl_assinatura;

//$tipo_agrupa = substr($nivel,0,1);

$xinstit = split("-",$db_selinstit);
$resultinst = db_query("select codigo, nomeinstabrev as nomeinst from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for ($xins = 0; $xins < pg_numrows($resultinst); $xins++) {
  db_fieldsmemory($resultinst,$xins);
  $descr_inst .= $xvirg.$nomeinst ;
  $xvirg = ', ';
}

$xtipo = 0;
if ($origem == "O") {
  $xtipo = "ORÇAMENTO";
} else {
  $xtipo = "BALANÇO";
  if ($opcao == 3) {
    $head4 = "PERÍODO : ".db_formatar($perini,'d')." A ".db_formatar($perfin,'d') ;
  } else {
    $head4 = "PERÍODO : ".strtoupper(db_mes(substr($perini,5,2)))." A ".strtoupper(db_mes(substr($perfin,5,2)));
  }
}
// pesquisa a conta mae da receita
$head1 = "DEMOSTRATIVO DA DESPESA POR ORGÃO/FUNÇÃO";
$head2 = "ANEXO(9) - EXERCICIO: ".db_getsession("DB_anousu")." - ".$xtipo;
$head3 = "INSTITUIÇÕES : ".$descr_inst;

$xcampos = split("-",$orgaos);


if (substr($nivel,0,1) == '1') {
  $xwhere1 = " trim(to_char(o58_orgao,'99')) in (";
} else if (substr($nivel,0,1) == '2') {
  $xwhere1 = " trim(to_char(o58_orgao,'99'))||'.'||trim(to_char(o58_unidade,'99')) in (";
} else if (substr($nivel,0,1) == '3') {
  $xwhere1 = " trim(to_char(o58_funcao,'9999999999999')) in (";
}
$virgula1 = ' ';
for ($i=0; $i < sizeof($xcampos); $i++) {
  $xxcampos = split("_",$xcampos[$i]);
  $virgula = '';
  $where  = "'";
  $where1 = "'";
  for ($ii=0; $ii<sizeof($xxcampos); $ii++) {
    if ($ii > 0) {
      $where  .= $virgula.$xxcampos[$ii];
      $where1 .= $virgula.$xxcampos[$ii];
      $virgula = '.';
    }
  }
  $xwhere1 .= $virgula1.$where1."'";
  $virgula1 = ', ';
  
}


$xwhere1 .= ") and o58_instit in (".str_replace('-',', ',$db_selinstit).")";




// funcao para gerar work

db_query("begin");

$sql = "select distinct o52_funcao
from orcfuncao f
inner join orcdotacao d on d.o58_anousu = ".db_getsession("DB_anousu")."  and d.o58_funcao = f.o52_funcao
order by o52_funcao";
$result = db_query($sql);
//db_criatabela($result);
$sql = "";
$quantascolunas = pg_numrows($result);

//echo $quantascolunas; exit;

if ($opcao == 1) {
  $xvalor = 'dot_ini';
} else {
  $xvalor = 'empenhado - anulado';
}

for ($i=0; $i<pg_numrows($result); $i++) {
  db_fieldsmemory($result,$i);
  $sql .= " sum(case when o52_funcao = $o52_funcao then $xvalor else 0::float8 end ) as c_$o52_funcao, \n";
}

$anousu  = db_getsession("DB_anousu");
$dataini = $perini;
$datafin = $perfin;



$sql1 = db_dotacaosaldo(3,2,3,true,$xwhere1,$anousu,$dataini,$datafin,1,3,true);

$sql = "select o58_orgao,
".$sql."
o40_descr
from ( $sql1 ) as d
left  outer join orcfuncao f on f.o52_funcao = d.o58_funcao
group by o58_orgao,
o40_descr
order by o58_orgao";

//left  outer join orcunidade u on o41_anousu = ".db_getsession("DB_anousu")." and u.o41_orgao = d.o58_orgao and u.o41_unidade = d.o58_unidade

$result = db_query($sql);

$sql_totalizador = "
select o58_orgao,
o40_descr,
sum($xvalor) as valor
from ( $sql1 ) as d
left  outer join orcfuncao f on f.o52_funcao = d.o58_funcao
group by o58_orgao,
o40_descr
order by o58_orgao";
$result_tot = db_query($sql_totalizador);
//db_criatabela($result_tot);exit;


$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$troca = 1;
$alt = 4;
$contador = 1;
$pagina = 1;
$fim = false;

$tgvalor1 = $tvalor1 = 0;
$tgvalor2 = $tvalor2 = 0;
$tgvalor3 = $tvalor3 = 0;
$tgvalor4 = $tvalor4 = 0;
// a torgao vai guardar o numero do orgao e o valor, a torgaonome, somente o numero como indice + nome
$torgao     = array();
$torgaonome = array();



$pdf->sety(300);

for ($i=0; $i<pg_numrows($result); $i++) {
  db_fieldsmemory($result,$i);
  if ($pdf->gety()>$pdf->h-30 || $pagina ==1) {
    if ($pdf->gety()>$pdf->h-30) {
      $pdf->addpage("L");
    }
    
    $pdf->setfont('arial','b',7);
    $pdf->ln(3);
    $pdf->cell(12,$alt,"CÓDIGO",1,0,"L",0);
    $pdf->cell(85,$alt,"ÓRGÃO",1,0,"L",0);
    
    if ($pagina == 1) {
      $quantascolunas = ($quantascolunas-4);
      
      $fim = true;
      if ($quantascolunas<=0) {
        $x = $quantascolunas+4;
      } else {
        $x = 4;
      }
      
      $pagina = 0;
    } else {
      $contador -= $x;
    }
    for ($li=0; $li<$x; $li++) {
      $campo = pg_fieldname($result,$contador);
      $contador ++;
      $sql = "select o52_descr
from orcfuncao
where o52_funcao = ".substr($campo,2);
      $resultc = db_query($sql);
      db_fieldsmemory($resultc,0);
      $pdf->cell(35,$alt,$o52_descr,1,0,"C",0);
    }
    $pdf->cell(0,$alt,'',0,1,"L",0);
  }
  
  $pdf->cell(12,$alt,$o58_orgao,0,0,"L",0);
  $pdf->cell(85,$alt,$o40_descr,0,0,"L",0);

  if ($quantascolunas+4 >= 4) {
    if ($x>=1) {
      $valor = pg_fieldname($result,$contador-4);
      $pdf->cell(35,$alt,db_formatar($$valor,'f'),0,0,"C",0);
      $tvalor1 += $$valor;
    }
    if ($x>=2) {
      $valor = pg_fieldname($result,$contador-3);
      $pdf->cell(35,$alt,db_formatar($$valor,'f'),0,0,"C",0);
      $tvalor2 += $$valor;
    }
    if ($x>=3) {
      $valor = pg_fieldname($result,$contador-2);
      $pdf->cell(35,$alt,db_formatar($$valor,'f'),0,0,"C",0);
      $tvalor3 += $$valor;
    }
    if ($x>=4) {
      $valor = pg_fieldname($result,$contador-1);
      $pdf->cell(35,$alt,db_formatar($$valor,'f'),0,0,"C",0);
      $tvalor4 += $$valor;
    }
  } else if ($quantascolunas >= 2) {
      if ($x>=1) {
        $valor = pg_fieldname($result,$contador-4);
        $pdf->cell(35,$alt,db_formatar($$valor,'f'),0,0,"C",0);
        $tvalor1 += $$valor;
      }
      if ($x>=2) {
        $valor = pg_fieldname($result,$contador-3);
        $pdf->cell(35,$alt,db_formatar($$valor,'f'),0,0,"C",0);
        $tvalor2 += $$valor;
      }
      if ($x>=3) {
        $valor = pg_fieldname($result,$contador-2);
        $pdf->cell(35,$alt,db_formatar($$valor,'f'),0,0,"C",0);
        $tvalor3 += $$valor;
      }
      if ($x>=4) {
        $valor = pg_fieldname($result,$contador-1);
        $pdf->cell(35,$alt,db_formatar($$valor,'f'),0,0,"C",0);
        $tvalor4 += $$valor;
      }
  } else {
      if ($quantascolunas > 2){
          if ($x>=1) {
            $valor = pg_fieldname($result,$contador-3);
            $pdf->cell(35,$alt,db_formatar($$valor,'f'),0,0,"C",0);
            $tvalor1 += $$valor;
          }
          if ($x>=2) {
            $valor = pg_fieldname($result,$contador-2);
            $pdf->cell(35,$alt,db_formatar($$valor,'f'),0,0,"C",0);
            $tvalor2 += $$valor;
          }
          if ($x>=3) {
            $valor = pg_fieldname($result,$contador-1);
            $pdf->cell(35,$alt,db_formatar($$valor,'f'),0,0,"C",0);
            $tvalor3 += $$valor;
          }
      }

      if ($quantascolunas == -2){
          if ($x>=1) {
            $valor = pg_fieldname($result,$contador-2);
            $pdf->cell(35,$alt,db_formatar($$valor,'f'),0,0,"C",0);
            $tvalor1 += $$valor;
          }
          if ($x>=2) {
            $valor = pg_fieldname($result,$contador-1);
            $pdf->cell(35,$alt,db_formatar($$valor,'f'),0,0,"C",0);
            $tvalor2 += $$valor;
          }
      }

      if ($quantascolunas > -1){
          if ($x>=1) {
            $valor = pg_fieldname($result,$contador-2);
            $pdf->cell(35,$alt,db_formatar($$valor,'f'),0,0,"C",0);
            $tvalor1 += $$valor;
          }
          if ($x>=2) {
            $valor = pg_fieldname($result,$contador-1);
            $pdf->cell(35,$alt,db_formatar($$valor,'f'),0,0,"C",0);
            $tvalor2 += $$valor;
          }
      }
      if ($quantascolunas == -3){
          if ($x>=1) {
            $valor = pg_fieldname($result,$contador-1);
            $pdf->cell(35,$alt,db_formatar($$valor,'f'),0,0,"C",0);
            $tvalor1 += $$valor;
          }
      }


      if ($quantascolunas == -1){
          if ($x>=1) {
            $valor = pg_fieldname($result,$contador-3);
            $pdf->cell(35,$alt,db_formatar($$valor,'f'),0,0,"C",0);
            $tvalor1 += $$valor;
          }
          if ($x>=2) {
            $valor = pg_fieldname($result,$contador-2);
            $pdf->cell(35,$alt,db_formatar($$valor,'f'),0,0,"C",0);
            $tvalor2 += $$valor;
          }
          if ($x>=3) {
            $valor = pg_fieldname($result,$contador-1);
            $pdf->cell(35,$alt,db_formatar($$valor,'f'),0,0,"C",0);
            $tvalor3 += $$valor;
          }
      }
  }

  $pdf->cell(0,$alt,'',0,1,"L",0);
  
  if ($i+1==pg_numrows($result) && ($quantascolunas >= 4 || $quantascolunas > 0) ) {
    $pdf->cell(12,$alt,"TOTAL",0,0,"L",0);
    $pdf->cell(85,$alt,"",0,0,"L",0);
    $pdf->cell(35,$alt,db_formatar($tvalor1,'f'),0,0,"C",0);
    
    if ($tvalor2 > 0 || $tvalor3 > 0 || $tvalor4 > 0 ) {
    	$pdf->cell(35,$alt,db_formatar($tvalor2,'f'),0,0,"C",0);
    }
    
    if ($tvalor3 > 0 || $tvalor4 > 0) {
    	$pdf->cell(35,$alt,db_formatar($tvalor3,'f'),0,0,"C",0);
    	if ($tvalor4 > 0){
    		$pdf->cell(35,$alt,db_formatar($tvalor4,'f'),0,0,"C",0);
    	} else {
    		if ($quantascolunas+4 >= 4) {
    			if ($x>=4) {
    				$pdf->cell(35,$alt,db_formatar($tvalor4,'f'),0,0,"C",0);
    			}
    		} else if ($quantascolunas >= 2) {
    			if ($x>=4) {
    				$pdf->cell(35,$alt,db_formatar($tvalor4,'f'),0,0,"C",0);
    			}
    		}
    	}
    }
    
    $pdf->Ln();
    
    $tgvalor1 += $tvalor1;
    $tgvalor2 += $tvalor2;
    $tgvalor3 += $tvalor3;
    $tgvalor4 += $tvalor4;
    
    $tvalor1 = $tvalor2 = $tvalor3 = $tvalor4 = 0;
    
    
    $fim = false;
    $pagina = 1;
    $i = -1;
  }
  
}

// total por orgao
$pdf->cell(12,$alt,"TOTAL",0,0,"L",0);
$pdf->cell(85,$alt,"",0,0,"L",0);
$pdf->cell(35,$alt,db_formatar($tvalor1,'f'),0,0,"C",0);
if ($tvalor2 > 0 || $tvalor3 > 0 || $tvalor4 > 0 || $x>=1) {
  $pdf->cell(35,$alt,db_formatar($tvalor2,'f'),0,0,"C",0);
}
    
if ($tvalor3 > 0 || $tvalor4 > 0) {
  $pdf->cell(35,$alt,db_formatar($tvalor3,'f'),0,0,"C",0);
  if ($tvalor4 > 0){
  	$pdf->cell(35,$alt,db_formatar($tvalor4,'f'),0,0,"C",0);
  } else {
  	if ($quantascolunas+4 >= 4) {
  		if ($x>=4) {
  			$pdf->cell(35,$alt,db_formatar($tvalor4,'f'),0,0,"C",0);
  		}
  	} else if ($quantascolunas >= 2) {
  		if ($x>=4) {
  			$pdf->cell(35,$alt,db_formatar($tvalor4,'f'),0,0,"C",0);
  		}
  	}
  }
}

$pdf->Ln();

$tgvalor1 += $tvalor1;
$tgvalor2 += $tvalor2;
$tgvalor3 += $tvalor3;
$tgvalor4 += $tvalor4;

$pdf->Ln();
$pdf->cell(12,$alt,"CODIGO",1,0,"L",0);
$pdf->cell(85,$alt,"ORGAO",1,0,"L",0);
$pdf->cell(35,$alt,"TOTAL",1,0,"C",0);
$pdf->Ln();
$tvalor=0;
for ($x=0; $x<pg_numrows($result_tot); $x++) {
  db_fieldsmemory($result_tot,$x);
  $pdf->cell(12,$alt,$o58_orgao,0,0,"L",0);
  $pdf->cell(85,$alt,$o40_descr,0,0,"L",0);
  $pdf->cell(35,$alt,db_formatar($valor,'f'),0,0,"C",0);
  $pdf->Ln();
  $tvalor += $valor;
}
$pdf->Ln();
$pdf->cell(12,$alt,"TOTAL",0,0,"L",0);
$pdf->cell(85,$alt,"",0,0,"L",0);
$pdf->cell(35,$alt,db_formatar($tvalor,'f'),0,0,"C",0);


$pdf->ln(13);

if ($origem != "O") {
  
  assinaturas($pdf, $classinatura,'BG');
  
}

$pdf->Output();
?>
