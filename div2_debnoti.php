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

include("fpdf151/pdf.php");
include("libs/db_sql.php");

$clrotulo = new rotulocampo;
$clrotulo->label('');
$clrotulo->label('z01_nome');
db_postmemory($HTTP_SERVER_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
$chaves1 = db_getsession('chaves1');
db_destroysession('chaves1');
$head3 = "Débitos notificados que não foram prescritos.";
$head4 = "Lista N° $lista";
if (isset($datadeb)&&$datadeb!=""){
 $head5 = "Data dos Débitos :".db_formatar($datadeb,"d");;
}

// criar uma tabela temporaria par aguardar o numpre e numpar
$sqlCriaTabela = "CREATE TEMP TABLE w_numpre_numpar (
                         numpre   int4,
                         numpar   int4)";
$rsCriaTabela  = pg_query($sqlCriaTabela);
if( $rsCriaTabela == false ){          
	//db_msgbox("Erro ao tentar cria a tabela");
  exit;
}else{
   $sqlIndex = "create index w_numpre_numpar_n_p_in  on w_numpre_numpar(numpre,numpar)";
	 $rsIndex  = pg_query($sqlIndex);
}
$where = " where ";
$or = ""; 
$arr_info = split("XVX",$chaves1);
for ($w=0;$w<count($arr_info);$w++){
	$arr_dados = split("-",$arr_info[$w]);
	$numpre1 = $arr_dados[0];
	$numpar1 = $arr_dados[1];
	//$where .= $or." (listadeb.k61_numpre = $numpre and listadeb.k61_numpar= $numpar)  ";
  //$or = " or  "; 
	
	// inserir o numpre e numpar na tabela temp
	$sqlInclui = " insert into w_numpre_numpar (numpre,numpar) values ($numpre1,$numpar1)";
	$rsInclui  = pg_query($sqlInclui);
	if( $rsInclui == false ){          
	 // db_msgbox("Erro ao tentar incluir na tabela temp ");
    exit;
  }
}

$sql ="select k00_numcgm,k00_matric,k00_inscr,k00_numpre,k00_numpar,k53_notifica,k63_codigo ,k50_dtemite ,z01_numcgm,z01_nome,sum(k00_valor)as valor from (select          arrenumcgm.k00_numcgm,
                                   arrematric.k00_matric,
																	 arreinscr.k00_inscr,
																	 arrecad.k00_numpre,
																	 arrecad.k00_numpar,
																	 notidebitos.k53_notifica,
																	 listanotifica.k63_codigo,
																	 arrecad.k00_valor,
																	 k50_dtemite,
																	 case when j01_numcgm is not null then j01_numcgm else
																	   (case when q02_numcgm is not null then q02_numcgm else arrenumcgm.k00_numcgm
																	     end)
																		 end as numcgm

                  from listadeb 
									    inner join w_numpre_numpar on listadeb.k61_numpre = w_numpre_numpar.numpre
                                                 and listadeb.k61_numpar = w_numpre_numpar.numpar
											inner join arrecad      on listadeb.k61_numpre    = arrecad.k00_numpre
																					   and listadeb.k61_numpar    = arrecad.k00_numpar
											inner join notidebitos  on notidebitos.k53_numpre = listadeb.k61_numpre
																					   and notidebitos.k53_numpar = listadeb.k61_numpar
									    inner join notificacao  on k50_notifica           = k53_notifica 
											                       and k50_instit             = ".db_getsession("DB_instit")."
											left join listanotifica on k63_notifica           = k50_notifica
											left join arrematric    on listadeb.k61_numpre    = arrematric.k00_numpre
											left join arreinscr     on listadeb.k61_numpre    = arreinscr.k00_numpre
											left join arrenumcgm    on listadeb.k61_numpre    = arrenumcgm.k00_numpre
											left join iptubase      on iptubase.j01_matric    = arrematric.k00_matric
											left join issbase       on issbase.q02_inscr      = arreinscr.k00_inscr
                      where k61_codigo = $lista
										
										) as x inner join cgm on x.numcgm = cgm.z01_numcgm 
                     group by k00_numcgm,k00_matric,k00_inscr,k00_numpre,k00_numpar,k53_notifica,k63_codigo ,k50_dtemite,z01_numcgm,z01_nome  

					    		";
									//die($sql);
$result=pg_exec($sql);
$numrows = pg_numrows($result);

if ($numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$valortotal = 0;

for($x = 0; $x < $numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(10,$alt,"Cgm",1,0,"C",1);
      $pdf->cell(15,$alt,"Matrícula",1,0,"C",1);
      $pdf->cell(15,$alt,"Inscrição",1,0,"C",1);
      $pdf->cell(15,$alt,"Numpre",1,0,"C",1); 
      $pdf->cell(15,$alt,"Numpar",1,0,"C",1); 
      $pdf->cell(50,$alt,$RLz01_nome,1,0,"C",1); 
      $pdf->cell(20,$alt,"Cód. Notif.",1,0,"C",1); 
      $pdf->cell(15,$alt,"Lista. Not.",1,0,"C",1); 
      $pdf->cell(15,$alt,"Dt. Notif.",1,0,"C",1); 
      $pdf->cell(20,$alt,"Valor",1,1,"C",1); 
      $troca = 0;
			$p=0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(10,$alt,$k00_numcgm,0,0,"C",$p);
   $pdf->cell(15,$alt,$k00_matric,0,0,"C",$p);
   $pdf->cell(15,$alt,$k00_inscr,0,0,"C",$p);
   $pdf->cell(15,$alt,$k00_numpre,0,0,"C",$p);
   $pdf->cell(15,$alt,$k00_numpar,0,0,"C",$p);
   $pdf->cell(50,$alt,substr($z01_nome,0,28),0,0,"L",$p);
   $pdf->cell(20,$alt,$k53_notifica,0,0,"C",$p);
   $pdf->cell(15,$alt,$k63_codigo,0,0,"C",$p);
   $pdf->cell(15,$alt,db_formatar($k50_dtemite,"d"),0,0,"C",$p);
   $pdf->cell(20,$alt,db_formatar($valor,"f"),0,1,"R",$p);
	 if ($p==0){
		 $p=1;
	 }else{
		 $p=0;
	 }
	 $valortotal = $valortotal + $valor;
   $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(160,$alt,'TOTAL DE REGISTROS : '.$total,"T",0,"L",0);
$pdf->cell(30,$alt,db_formatar($valortotal,"f"),"T",0,"R",0);
$pdf->Output();
?>