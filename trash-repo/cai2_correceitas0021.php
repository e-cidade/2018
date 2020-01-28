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


include("libs/db_liborcamento.php");
include("fpdf151/pdf.php");
//#00#//relatorio
//#10#//Procedimentos básicos para a criação de relatórios no formato pdf
//#99#//Fazer o "include" do programa onde enconta-se a classe que será utilizada no relatório,
//#99#//esta pode ser: |pdf|,|pdf1|,|pdf2| e |scpdf|.
//#99#//
//#99#//Exemplo : 
//#99#//   include("fpdf151/pdf.php");            // include do programa que contem a classe
//#99#//   $pdf = new PDF();                      // estancia a classe
//#99#//   $pdf->open();                          // inicia a geração do documento
//#99#//   $pdf->aliasnbpages();                  // define o apelido para o número total de páginas
//#99#//   $pdf->addpage('L');                    // adiciona uma pagina no modo paisagem
//#99#//   $total_geral = 0;                      // criação de uma variável para somar o total de registros
//#99#//   $pdf->settextcolor(0,0,0);             // seta a cor do texto como preta
//#99#//   $pdf->setfillcolor(220);               // define a cor de preenchimento
//#99#//   $pdf->setfont('Arial','B',9);          // seta a fonte como arial, bold e tamanho 9
//#99#//   $pdf->cell(20,6,"CODIGO",1,0,"C",1);   // cria as células para o cabeçalho
//#99#//   $pdf->cell(100,6,"RECEITA",1,0,"C",1);
//#99#//   $pdf->cell(25,6,"VALOR",1,1,"C",1);
//#99#//
//#99#//   for ($i=0;$i<$xxnum;$i++){             // define um "for" para saber quanta linha irá imprimir
//#99#//     db_fieldsmemory($result,$i);         // cria as variáveis resultantes de um record set
//#99#//     if ($pdf->gety() > $pdf->h - 30 ){   // troca de página, caso a posição horizontal seja maior que a altura da página menos 30
//#99#//        $pdf->addpage();                  // adiciona uma nova página
//#99#//        $pdf->setfont('Arial','B',9);     // seta a fonte como arial, bold e tamanho 9
//#99#//        $pdf->cell(20,6,"CODIGO",1,0,"C",1);           // cria as células para o cabeçalho da nova página
//#99#//        $pdf->cell(100,6,"RECEITA",1,0,"C",1);
//#99#//        $pdf->cell(25,6,"VALOR",1,1,"C",1);
//#99#//     }
//#99#//     $pdf->setfont('arial','',7);                      // seta a fonte como arial e tamanho 9
//#99#//     $pdf->cell(20,4,$k02_codigo,1,0,"C",$pre);        // imprime o conteúdo do relatório
//#99#//     $pdf->cell(100,4,strtoupper($k02_drecei),1,0,"L",$pre);
//#99#//     $pdf->cell(25,4,db_formatar($valor,'f'),1,1,"R",$pre);
//#99#//     $total_geral +=$valor;                            // incrementa a variável do total de registros
//#99#//   }
//#99#//   $pdf->cell(120,4,"TOTAL DE REGISTROS",1,0,"C",0);   // imprime o total dos registros
//#99#//   $pdf->cell(25,4,$total_geral,1,1,"R",0);
//#99#//   $pdf->Output();                                     // saída do relatório direto para o browser

include("libs/db_sql.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 





$head3 = "RELATÓRIO DE RECEITAS PAGAS";
$head4 = 'TODAS AS RECEITAS';
$ordem = ' order by g.k02_codigo, f.k00_dtpaga, f.k00_numpre ';
$head6 = "Período : ".db_formatar($datai,'d')." a ".db_formatar($dataf,'d');

$where = '';
if($codrec != ''){
  $where = ' g.k02_codigo in ('.$codrec.') and ';

}
 $sql ="select	g.k02_codigo,
	        g.k02_tipo,
		g.k02_drecei,
		o.k02_codrec,
		case when p.k02_codigo is null then o.k02_estorc else p.k02_estpla end as estrutural,
		sum(case when r.k12_estorn = 'f' then f.k12_valor else f.k12_valor end) as valor
	from cornump f
	     inner join corrente r on r.k12_id = f.k12_id and r.k12_data = f.k12_data and r.k12_autent = f.k12_autent
	     inner join tabrec g on g.k02_codigo  = f.k12_receit 
	     left outer join taborc o on o.k02_codigo = g.k02_codigo and o.k02_anousu = ".db_getsession("DB_anousu")."
	     left outer join tabplan p on p.k02_codigo = g.k02_codigo and p.k02_anousu = ".db_getsession("DB_anousu")."
        where $where f.k12_data between '$datai' and '$dataf'
	group by g.k02_tipo,
	         g.k02_codigo,
		 g.k02_drecei,
		 o.k02_codrec,
		 estrutural
	order by g.k02_tipo desc ,g.k02_codigo
      ";
//echo $sql;exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   //db_redireciona('db_erros.php?fechar=true&db_erro=Não existem lançamentos para a receita '.$codrec.' no período de '.db_formatar($datai,'d').' a '.db_formatar($dataf,'d'));

}
$linha = 0;
$pre = 0;
$total_reco = 0;
$total_rece = 0;
$pagina = 0;

if($tipo=='T' || $tipo =='O'){
   $pdf->ln(2);
   $pdf->AddPage(); 
   $pdf->SetTextColor(0,0,0);
   $pdf->SetFillColor(220);
   $pdf->SetFont('Arial','B',9);
//   $pdf->Cell(185,6,"RECEITAS ORÇAMENTARIAS",1,1,"C",1);
   $pdf->Cell(20,6,"CODIGO",1,0,"C",1);
   $pdf->Cell(40,6,"ESTRUTURAL",1,0,"C",1);
   $pdf->Cell(100,6,"RECEITA ORÇAMENTÁRIA",1,0,"C",1);
   $pdf->Cell(25,6,"VALOR",1,1,"C",1);
   $pdf->SetFont('Arial','B',9);
   for ($i=0;$i<$xxnum;$i++){
     db_fieldsmemory($result,$i);
     if($k02_tipo=='E')
       continue;

     // verifica se receita tem desdobramento

     $tem_desdobramento = false;

     if($desdobrar=='S'){

       if($k02_codrec != ''){
       
	 $sql = "select o57_fonte 
		 from orcreceita
		      inner join orcfontes on o57_codfon = o70_codfon and o70_anousu = o57_anousu
		      inner join orcfontesdes on o60_anousu = o70_anousu and o60_codfon = o70_codfon
		 where o70_anousu = ".db_getsession("DB_anousu")." and o70_codrec = $k02_codrec";
	 $result1 = pg_exec($sql);
	 if($result1!=false && pg_numrows($result1) > 0){
	   $fonte = pg_result($result1,0,0);
	   $contamae = db_le_mae_rec_sin($fonte,false);

	   $sql = "select o70_codrec,o57_fonte,o57_descr,o60_perc 
		   from orcreceita
			inner join orcfontes on o57_codfon = o70_codfon and o70_anousu = o57_anousu
			inner join orcfontesdes on o60_anousu = o70_anousu and o60_codfon = o70_codfon
		   where o57_fonte like '$contamae%' 
		   order by o57_fonte";
	   $result1 = pg_exec($sql);
	   if($result1!=false && pg_numrows($result1) > 0){
	     $tem_desdobramento = true;
	   }
	 }
       }
     }
     if ($pdf->gety() > $pdf->h - 30 ){
        $pdf->addpage();
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(20,6,"CODIGO",1,0,"C",1);
        $pdf->Cell(40,6,"ESTRUTURAL",1,0,"C",1);
        $pdf->Cell(100,6,"RECEITA",1,0,"C",1);
        $pdf->Cell(25,6,"VALOR",1,1,"C",1);
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(20,4,$k02_codigo,1,0,"C",$pre);
     $pdf->cell(40,4,$estrutural,1,0,"C",$pre);
     $pdf->cell(100,4,strtoupper($k02_drecei),1,0,"L",$pre);
     $pdf->cell(25,4,db_formatar($valor,'f'),1,1,"R",$pre);
     $total_reco +=$valor;

     if($tem_desdobramento){

        unset($dbrec);
	unset($dbrecde);
	unset($dbreces);
	$vlrsoma = 0;
	$multiplica = false;
	if($valor < 0 ){
	  $multiplica = true;
	  $valor = $valor * -1;
	}
	for($recc=0;$recc<pg_numrows($result1);$recc++){
	  db_fieldsmemory($result1,$recc);
	  // aplica o percentual sobre o valor
	  $vlrperc = db_formatar(($valor * ($o60_perc/100)),'p')+0;
	  $vlrsoma = $vlrsoma + $vlrperc;
	  if($vlrsoma > $valor){
	     // arredonda no ultimo desdobramento
	     $vlrperc = $vlrperc - ($vlrsoma - $valor);
	  }   
	  $dbrec[$o70_codrec] = $vlrperc;
	  $dbrecde[$o70_codrec] = $o57_descr;
	  $dbreces[$o70_codrec] = $o57_fonte;
	}
	if($vlrsoma < $valor){
	  $vlrperc = $vlrperc + ($valor - $vlrsoma );
	  $dbrec[$o70_codrec] = $vlrperc;
	}	
	if($multiplica){
	  reset($dbrec);
	  for($arrr=0;$arrr<sizeof($dbrec);$arrr++){
	    $dbrec[key($dbrec)] = $dbrec[key($dbrec)] * -1;
	    next($dbrec);
	  }
	}
        reset($dbrec);
	reset($dbrecde);
	reset($dbreces);
        for($d=0;$d<sizeof($dbrec);$d++){
          $pdf->cell(20,4,'',1,0,"C",$pre);
          $pdf->cell(30,4,$dbreces[key($dbrec)],1,0,"C",$pre);
          $pdf->cell(80,4,strtoupper($dbrecde[key($dbrec)]),1,0,"L",$pre);
          $pdf->cell(25,4,db_formatar($dbrec[key($dbrec)],'f'),1,1,"R",$pre);
	  next($dbrec);
	  next($dbrecde);
	  next($dbreces);
        }

     }

   }
   $pdf->setfont('arial','B',7);
   $pdf->cell(160,4,"TOTAL ...",1,0,"L",0);
   $pdf->cell(25,4,db_formatar($total_reco,'f'),1,1,"R",0);
}
if($tipo=='T' || $tipo =='E'){
   $pdf->ln(2);
   if ($pdf->gety() > $pdf->h - 30 ){
     $pdf->AddPage(); 
   }
   $pdf->SetTextColor(0,0,0);
   $pdf->SetFillColor(220);
//   $pdf->SetFont('Arial','B',9);
//   $pdf->Cell(185,6,"RECEITAS EXTRA-ORÇAMENTARIAS",1,1,"C",1);
   $pdf->SetFont('Arial','B',9);
   $pdf->Cell(20,6,"CODIGO",1,0,"C",1);
   $pdf->Cell(40,6,"ESTRUTURAL",1,0,"C",1);
   $pdf->Cell(100,6,"RECEITA EXTRA-ORÇAMENTÁRIA",1,0,"C",1);
   $pdf->Cell(25,6,"VALOR",1,1,"C",1);
   $pdf->SetFont('Arial','B',9);
   for ($i=0;$i<$xxnum;$i++){
     db_fieldsmemory($result,$i);
     if($k02_tipo=='O')
       continue;
     if ($pdf->gety() > $pdf->h - 30 ){
        $pdf->addpage();
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(20,6,"CODIGO",1,0,"C",1);
        $pdf->Cell(40,6,"ESTRUTURAL",1,0,"C",1);
        $pdf->Cell(100,6,"RECEITA",1,0,"C",1);
        $pdf->Cell(25,6,"VALOR",1,1,"C",1);
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(20,4,$k02_codigo,1,0,"C",$pre);
     $pdf->cell(40,4,$estrutural,1,0,"C",$pre);
     $pdf->cell(100,4,strtoupper($k02_drecei),1,0,"L",$pre);
     $pdf->cell(25,4,db_formatar($valor,'f'),1,1,"R",$pre);
     $total_rece +=$valor;
   }
   $pdf->setfont('arial','B',7);
   $pdf->cell(160,4,"TOTAL ...",1,0,"L",0);
   $pdf->cell(25,4,db_formatar($total_rece,'f'),1,1,"R",0);
}

$pdf->cell(160,4,"TOTAL GERAL",1,0,"L",0);
$pdf->cell(25,4,db_formatar($total_rece+$total_reco,'f'),1,1,"R",0);
$pdf->Output();

?>