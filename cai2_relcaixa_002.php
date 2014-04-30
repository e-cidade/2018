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

//  include("libs/db_stdlib.php");
//    echo 'ala pucha';
include("fpdf151/pdf.php");
include ("libs/db_utils.php");
  //db_postmemory($HTTP_GET_VARS,2);exit;

//phpinfo();
//echo $_SERVER['PHP_SELF'];
$Total = 0;
$borda = "B"; 
$bordat = 1;
$preenc = 0;
$TPagina = 45;
  ///////////////////////////////////////////////////////////////////////
  if ($opcaoRelatorio == "todas" || $opcaoRelatorio == "k02_estorc" || $opcaoRelatorio == "k02_estpla"){
/* 	        select tabrec.k02_codigo,k02_tipo,k02_descr,k02_drecei,k02_codjm , taborc.k02_estorc, receita.o08_reduz,tabrec.k02_recjur,tabrec.k02_recmul 
	        from tabrec
                     left outer join taborc on tabrec.k02_codigo = taborc.k02_codigo
                     left outer join receita on taborc.k02_estorc = receita.o08_codigo
*/
    $sql = "
     select tabrec.k02_recjur, 
	    tabrec.k02_codigo,
	    tabrec.k02_tipo,
	    tabrec.k02_descr,
	    tabrec.k02_drecei,
	    tabrec.k02_codjm , 
	    tabrec.k02_recjur,
	    tabrec.k02_recmul,
	    tabrec.k02_limite,
	    taborc.k02_estorc,
	    tabplan.k02_estpla,
	    taborc.k02_codigo,
	    tabplan.k02_codigo,
	    orcreceita.o70_instit,
	    conplanoreduz.c61_instit,
	    case when taborc.k02_codrec is null then tabplan.k02_reduz else taborc.k02_codrec end as k02_codrec
     	from tabrec
	 	 	left outer join taborc        on tabrec.k02_codigo = taborc.k02_codigo and taborc.k02_anousu = " . db_getsession("DB_anousu") ."
	  		left outer join orcreceita    on taborc.k02_codrec = orcreceita.o70_codrec and taborc.k02_anousu = orcreceita.o70_anousu
	  		left outer join tabplan       on tabrec.k02_codigo = tabplan.k02_codigo and tabplan.k02_anousu = " . db_getsession("DB_anousu") . "
	  		left outer join conplanoexe   on tabplan.k02_reduz = conplanoexe.c62_reduz and tabplan.k02_anousu = conplanoexe.c62_anousu
	  		left outer join conplanoreduz on conplanoexe.c62_reduz = conplanoreduz.c61_reduz and c61_anousu=c62_anousu";
//	where
//	tabrec.k02_codigo in (select distinct k00_receit from arrecad union select distinct k00_receit from arrepaga union select distinct k00_receit from recibo) 

    $head3 = "Relatório de Receitas";
        if($opcaoRelatorio == "todas"){	 
	        $head5  = "Receitas Orçamentárias e Extra-orçamentárias";
	        $where  = " where o70_instit = " . db_getsession("DB_instit");
	        $where .= " or c61_instit    = " . db_getsession("DB_instit");
          $where .= " and tabrec.k02_tipo = 'O' or  tabrec.k02_tipo = 'E'";
	}
        if($opcaoRelatorio == "k02_estorc"){
      	  $head5  = "Receitas Orçamentárias";
          $where  = " where o70_instit    = " . db_getsession("DB_instit");
          $where .= " and tabrec.k02_tipo = 'O'";
	}
        if($opcaoRelatorio == "k02_estpla"){
	        $head5  = "Receitas Extra-orçamentárias";
          $where  = " where c61_instit    = " . db_getsession("DB_instit");
          $where .= " and tabrec.k02_tipo = 'E'";
	}
	$sql .= $where;
	if ($opcaoOrdem == "alfabetica") {
	  $head6 = "Ordem alfabetica";
	  $sql .= " order by tabrec.k02_drecei ";
	}else if ($opcaoOrdem == "numerica") {
	  $head6 = "Ordem Numérica";
	  $sql .= " order by tabrec.k02_codigo	";
	}else if ($opcaoOrdem == "estrutural") {
	  $head6 = "Ordem Numérica";
	  $sql = "select xxx.*, case when k02_estorc is not null then k02_estorc else k02_estpla end as estrutural from ($sql) as xxx order by estrutural";
        }
    $head7 = "Exercício: ".db_getsession("DB_anousu");	
    $pdf = new PDF(); // abre a classe
    $pdf->Open(); // abre o relatorio
    $pdf->AliasNbPages(); // gera alias para as paginas
    $pdf->SetFont('Courier','',7);
    $pdf->SetTextColor(0,0,0);
    $pdf->setfillcolor(235);

    $where = "";

    if($opcaoRelatorio2 == "validas"){
      $where = " and (k02_limite is null or k02_limite >= '" . date('Y-m-d',db_getsession("DB_datausu")) . "')";
    }

    if($opcaoRelatorio2 == "vencidas"){
      $where = " and (k02_limite < '" . date('Y-m-d',db_getsession("DB_datausu")) . "')";
    }
    
    $sql = "select * from ($sql) as x where (o70_instit = " . db_getsession("DB_instit") . " or c61_instit = " . db_getsession("DB_instit") . ")" . $where;
  //  echo $sql;exit;
    $result = pg_exec($sql);
    $num = pg_numrows($result);
    $linha = 0;
    $TPagina = 27;
    $preenc = 0;
    for($i=0;$i<$num;$i++) {

      if($pdf->gety() > $pdf->h - 30 || $linha == 0){
	 $preenc = 1;
         $linha = 1;
         $pdf->AddPage();
         $pdf->SetFont('Arial','B',7);
         $pdf->Cell(12,5,"Codigo",$bordat,0,"C",$preenc);
         $pdf->Cell(3,5,"T",$bordat,0,"C",$preenc);
         $pdf->Cell(10,5,"Red",$bordat,0,"C",$preenc);
         $pdf->Cell(25,5,"Estrutural",$bordat,0,"C",$preenc);
         $pdf->Cell(25,5,"Resumida",$bordat,0,"C",$preenc);
         $pdf->Cell(60,5,"Descrição",$bordat,0,"C",$preenc);
         $pdf->Cell(16,5,"Data limite",$bordat,0,"C",$preenc);
         $pdf->Cell(15,5,"Cod. Juros",$bordat,0,"C",$preenc);
         $pdf->Cell(15,5,"Cod. Multa",$bordat,1,"C",$preenc);
         $pdf->SetFont('Courier','B',7);
         $pdf->SetTextColor(0,0,0);
      }
      if ( $preenc == 0 ) {
	  $preenc = 1; 
	  
      } else {
	  $preenc = 0;
      }
      $pdf->SetFont('Courier','',7);
      $pdf->Cell(12,5,pg_result($result,$i,"k02_codigo"),0,0,"R",$preenc);
      $pdf->Cell(3,5,pg_result($result,$i,"k02_tipo"),0,0,"R",$preenc);
      $pdf->Cell(10,5,pg_result($result,$i,"k02_codrec"),0,0,"R",$preenc);
      $k02_estorc = pg_result($result,$i,"k02_estorc");
      $k02_estpla = pg_result($result,$i,"k02_estpla");
      if(trim($k02_estorc)!=""){
        $pdf->Cell(25,5,$k02_estorc,0,0,"R",$preenc);
      }else if(trim($k02_estpla)){
        $pdf->Cell(25,5,$k02_estpla,0,0,"R",$preenc);
      }else{
        $pdf->Cell(25,5,'',0,0,"R",$preenc);
      }
      $pdf->Cell(25,5,pg_result($result,$i,"k02_descr"),0,0,"L",$preenc);
      $pdf->Cell(60,5,pg_result($result,$i,"k02_drecei"),0,0,"L",$preenc);
      $pdf->Cell(16,5,db_formatar(pg_result($result,$i,"k02_limite"),'d'),0,0,"L",$preenc);
      $pdf->Cell(15,5,pg_result($result,$i,"k02_recjur"),0,0,"R",$preenc);
      $pdf->Cell(15,5,pg_result($result,$i,"k02_recmul"),0,1,"R",$preenc);
      $Total += 1;

    }
    $pdf->SetFont('Courier','B',9);  
    $pdf->Cell(22,5,"Total",$borda,0,"C",$preenc);
    $pdf->Cell(20,5,$Total,$borda,0,"R",$preenc);
    $pdf->Cell(123,5,'',$borda,1,"C",$preenc);
  ///////////////////////////////////////////////////////////////////////
  } else if ($opcaoRelatorio == "tabrecjm"){
    $sql = "
	select k02_codjm, k02_corr, upper(i01_descr) as i01_descr
	from tabrecjm
	     inner join inflan on i01_codigo = k02_corr
	";
    $head5 = "Relatório dos Tipos de Juros e Multa";
    if ($opcaoOrdem == "alfabetica") {
        $head6 = " Ordem Alfabética";
	$sql .= "
	  order by i01_descr
	  ";
    }else{
        $head6 = " Ordem Numérica";
	$sql .= "
	  order by k02_codjm
	  ";
    }
    $pdf = new PDF(); // abre a classe
    $pdf->Open(); // abre o relatorio
    $pdf->AliasNbPages(); // gera alias para as paginas
    $pdf->AddPage(); // adiciona uma pagina

//  $pdf->SetFillColor(255,255,255); 
//  $pdf->SetFont('Arial','B',12);
//  $pdf->SetTextColor(0,100,255);
//  $pdf->Cell(138,15,"TIPOS DE JUROS E MULTA",$bordat,1,"C",$preenc);

    $pdf->SetFont('Arial','B',9); // seta a fonte do relatorio
    $pdf->Cell(14,4,"Codigo",$bordat,0,"C",$preenc); // escreve a celula
    $pdf->Cell(14,4,"Inflator",$bordat,0,"C",$preenc);
    $pdf->Cell(80,4,"Descricao",$bordat,1,"C",$preenc);

    $result = pg_exec($sql);
    $num = pg_numrows($result);
    $pdf->SetFont('Courier','B',9);
    $linha = 0;

    // k02_codjm, k02_corr, i01_descr, k02_juros ,k02_juros ,k02_recmul
    
    for($i=0;$i<$num;$i++) {

//      if ( $linha % 2  == 0 ) {
//	  $pdf->SetFillColor(255,255,255); 
//	  
//      } else {
//	  $pdf->SetFillColor(202,242,249);
//      }
      $pdf->SetTextColor(0,0,0);
      $pdf->Cell(14,4,pg_result($result,$i,"k02_codjm"),$borda,0,"C",$preenc);
      $pdf->Cell(14,4,pg_result($result,$i,"k02_corr"),$borda,0,"C",$preenc);
      $pdf->Cell(80,4,substr(pg_result($result,$i,"i01_descr"),0,35),$borda,1,"L",$preenc);
      if($linha++>$TPagina){
         $linha = 0;
         $pdf->AddPage();
         $pdf->SetFont('Arial','B',9);
         $pdf->Cell(14,4,"Codigo",$bordat,0,"C",$preenc); // escreve a celula
         $pdf->Cell(14,4,"Inflator",$bordat,0,"C",$preenc);
         $pdf->Cell(80,4,"Descricao",$bordat,1,"C",$preenc);
         $pdf->SetFont('Courier','B',9);
      }
    }

  ///////////////////////////////////////////////////////////////////////
  
  } else if ($opcaoRelatorio == "saltes"){
    
//  	$sql = "
//	select saltes.k13_conta,c61_reduz,k13_descr,k13_ident 
//	from saltes
//	     inner join conplanoexe   on c62_reduz = saltes.k13_conta and c62_anousu = " . db_getsession("DB_anousu") . "
//	     inner join conplanoreduz on c61_reduz = c62_reduz and c61_anousu=c62_anousu
//	     where c61_instit = " . db_getsession("DB_instit");

   $sql = "select k13_reduz,
                  k13_ident,
                  c60_estrut,
                  c60_descr,
                  c63_banco,
                  c63_agencia,
                  c63_dvagencia,
                  c63_conta,
                  c63_dvconta,
                  c61_codigo,
                  o15_descr
            from saltes 
                 inner join conplanoreduz   on k13_reduz     = c61_reduz 
                 inner join conplano        on c61_anousu    = c60_anousu and c61_codcon = c60_codcon 
                 inner join conplanoconta   on c60_anousu    = c63_anousu and c60_codcon = c63_codcon 
                 inner join orctiporec      on c61_codigo    = o15_codigo 
           where  c61_anousu  = " .  db_getsession("DB_anousu") . "
             and  c61_instit =  " . db_getsession("DB_instit");
    
   $head5 = "Relatório das contas da tesouraria";
   
   if ($opcaoOrdem == "alfabetica") {
      $head6 = "Ordem Alfabética";
      $sql .= " order by k13_descr ";
   }else{
      $head6 = "Ordem Numérica";
      $sql .= " order by k13_conta ";
   }
   
   //die($sql);
   
   $head7 = "Exercício: ".db_getsession("DB_anousu");
   
    $pdf = new PDF(); // abre a classe
    $pdf->Open(); // abre o relatorio
    $pdf->AliasNbPages(); // gera alias para as paginas
    $pdf->AddPage("L"); // adiciona uma pagina
    $TPagina = 34;


//  $pdf->SetFillColor(255,255,255); 
//  $pdf->SetFont('Arial','B',12);
//  $pdf->SetTextColor(0,100,255);
//  $pdf->Cell(154,15,"CONTAS DA TESOURARIA",$bordat,1,"C",$preenc);



//    $pdf->SetFont('Arial','B',9); // seta a fonte do relatorio
//    $pdf->Cell(14,4,"Codigo",$bordat,0,"C",$preenc); // escreve a celula
//    $pdf->Cell(30,4,"Cod.Reduzido",$bordat,0,"C",$preenc);
//    $pdf->Cell(80,4,"Conta",$bordat,0,"C",$preenc);
//    $pdf->Cell(30,4,"Terminal",$bordat,1,"C",$preenc);

    $pdf->SetFont('Arial','B',9); // seta a fonte do relatorio
    $pdf->Cell(16,4,"Reduzido","TRB",0,"C",$preenc); // escreve a celula
    $pdf->Cell(14,4,"Banco",$bordat,0,"C",$preenc);
    $pdf->Cell(80,4,"Descrição",$bordat,0,"C",$preenc);
    $pdf->Cell(18,4,"Agência",$bordat,0,"C",$preenc);
    $pdf->Cell(35,4,"Conta",$bordat,0,"C",$preenc);
    $pdf->Cell(30,4,"Cod.Recurso",$bordat,0,"C",$preenc);    
    $pdf->Cell(85,4,"Recurso","LTB",1,"C",$preenc);

    $result = pg_exec($sql);
    $num = pg_numrows($result);
    $pdf->SetFont('Arial','',7);
    $linha = 0;
    
    for($i=0;$i<$num;$i++) {

    	$oSaltes = db_utils::fieldsMemory($result,$i);

      $pdf->Cell(16,4,$oSaltes->k13_reduz,"TRB",0,"R",$preenc);
      $pdf->Cell(14,4,$oSaltes->c63_banco,1,0,"R",$preenc);
      $pdf->Cell(80,4,$oSaltes->c60_descr,1,0,"L",$preenc);
      $pdf->Cell(10,4,$oSaltes->c63_agencia,1,0,"R",$preenc);
      $pdf->Cell(8,4,$oSaltes->c63_dvagencia,1,0,"L",$preenc);
      $pdf->Cell(25,4,$oSaltes->c63_conta,1,0,"R",$preenc);
      $pdf->Cell(10,4,$oSaltes->c63_dvconta,1,0,"L",$preenc);      
      $pdf->Cell(30,4,$oSaltes->c61_codigo,1,0,"C",$preenc);
      $pdf->Cell(85,4,$oSaltes->o15_descr,"LTB",1,"L",$preenc);
      
      if($linha++ > $TPagina){
         $linha = 0;
         $pdf->AddPage("L");
         $pdf->SetFont('Arial','B',9);
			   $pdf->Cell(16,4,"Reduzido","TRB",0,"C",$preenc);
			   $pdf->Cell(14,4,"Banco",$bordat,0,"C",$preenc);
         $pdf->Cell(80,4,"Descrição",$bordat,0,"C",$preenc);
         $pdf->Cell(18,4,"Agência",$bordat,0,"C",$preenc);
         $pdf->Cell(35,4,"Conta",$bordat,0,"C",$preenc);
         $pdf->Cell(30,4,"Cod.Recurso",$bordat,0,"C",$preenc);    
         $pdf->Cell(85,4,"Recurso","LTB",1,"C",$preenc);
         $pdf->SetFont('Arial','',7);
      }
    }

   
  ///////////////////////////////////////////////////////////////////////
  } else if ($opcaoRelatorio == "cadban"){
    $sql = "
	select k15_codbco,k15_codage,z01_nome
         from cadban 
         inner join cgm on k15_numcgm = z01_numcgm 
         where k15_instit = ".db_getsession("DB_instit");
	
    $head5 = "Relatório Bancos";
    if ($opcaoOrdem == "alfabetica") {
        $head6 = "Ordem Alfabética";
	$sql .= "
	  order by z01_nome
	  ";
	}
    else{
        $head6 = "Ordem Numérica";
        $sql .= "
	  order by k13_conta
        ";
    }



    $pdf = new PDF(); // abre a classe
    $pdf->Open(); // abre o relatorio
    $pdf->AliasNbPages(); // gera alias para as paginas
    $pdf->AddPage(); // adiciona uma pagina

//  $pdf->SetFillColor(255,255,255); 
//  $pdf->SetFont('Arial','B',12);
//  $pdf->SetTextColor(0,100,255);
//  $pdf->Cell(108,15,"CADASTRO DE BANCOS",$bordat,1,"C",$preenc);

    $pdf->SetFont('Arial','B',9); // seta a fonte do relatorio
    $pdf->Cell(14,4,"Codigo",$bordat,0,"C",$preenc); // escreve a celula
    $pdf->Cell(14,4,"Agencia",$bordat,0,"C",$preenc);
    $pdf->Cell(80,4,"Nome do Banco",$bordat,1,"C",$preenc);

    $result = pg_exec($sql);
    $num = pg_numrows($result);
    $pdf->SetFont('Courier','B',9);
    $linha = 0;

    // k15_codbco,k15_codage,z01_nome
    
    for($i=0;$i<$num;$i++) {

//      if ( $linha % 2  == 0 ) {
//	  $pdf->SetFillColor(255,255,255); 
//	  
//      } else {
//	  $pdf->SetFillColor(202,242,249);
//      }

      $pdf->Cell(14,4,pg_result($result,$i,"k15_codbco"),$borda,0,"C",$preenc);
      $pdf->Cell(14,4,pg_result($result,$i,"k15_codage"),$borda,0,"C",$preenc);
      $pdf->Cell(80,4,substr(pg_result($result,$i,"z01_nome"),0,40),$borda,1,"L",$preenc);
      if($linha++>$TPagina){
         $linha = 0;
         $pdf->AddPage();
         $pdf->SetFont('Arial','B',9);
         $pdf->Cell(14,4,"Codigo",$bordat,0,"C",$preenc);
         $pdf->Cell(14,4,"Agencia",$bordat,0,"C",$preenc);
         $pdf->Cell(80,4,"Nome do Banco",$bordat,1,"C",$preenc);
         $pdf->SetFont('Courier','B',9);
      }
    }



	
  ///////////////////////////////////////////////////////////////////////
  } else if ($opcaoRelatorio == "arretipo"){
    $sql = "
	select k00_tipo,k00_descr 
	from arretipo
	";

    $head5 = "Relatório de Caracteristicas";
    if ($opcaoOrdem == "alfabetica") {
        $head6 = "Ordem Alfabética";
	$sql .= "
	  order by k00_descr
	  ";
	}
    else{
        $head6 = "Ordem Numérica";
        $sql .= "
	  order by k00_tipo
        ";
    }

    $pdf = new PDF(); // abre a classe
    $pdf->Open(); // abre o relatorio
    $pdf->AliasNbPages(); // gera alias para as paginas
    $pdf->AddPage(); // adiciona uma pagina
    $Total = 0;
    $pdf->SetFont('Arial','B',9); // seta a fonte do relatorio
    $pdf->Cell(14,4,"Codigo",$bordat,0,"C",$preenc); // escreve a celula
    $pdf->Cell(80,4,"Descricao",$bordat,1,"C",$preenc);

    $result = pg_exec($sql);
    $num = pg_numrows($result);
    $pdf->SetFont('Courier','B',9);
    $linha = 0;
    for($i=0;$i<$num;$i++) {
      $pdf->Cell(14,4,pg_result($result,$i,"k00_tipo"),$borda,0,"C",$preenc);
      $pdf->Cell(80,4,substr(pg_result($result,$i,"k00_descr"),0,40),$borda,1,"L",$preenc);
      $Total += 1;
      if($linha++>$TPagina){
         $linha = 0;
         $pdf->AddPage();
         $pdf->SetFont('Arial','B',9);
         $pdf->Cell(14,4,"Codigo",$bordat,0,"C",$preenc);
         $pdf->Cell(80,4,"Descricao",$bordat,1,"C",$preenc);
         $pdf->SetFont('Courier','B',9);
      }
    }
         $pdf->Cell(14,4,"Total","LTB",0,"C",$preenc);
         $pdf->Cell(80,4,$Total,"RTB",1,"L",$preenc);
///////////////////////////////////////////////////////////////////////////////////////
	
  } else if ($opcaoRelatorio == "histcalc"){
    $sql = "
	select k01_codigo,k01_descr, k01_tipo 
	from histcalc
	";

    $head5 = "Relatório de Historicos de Calculo";
    if ($opcaoOrdem == "alfabetica") {
        $head6 = "Ordem Alfabética";
	$sql .= "
	  order by k01_descr
	  ";
	}
    else{
        $head6 = "Ordem Numérica";
        $sql .= "
	  order by k01_codigo
        ";
    }

    $pdf = new PDF(); // abre a classe
    $pdf->Open(); // abre o relatorio
    $pdf->AliasNbPages(); // gera alias para as paginas
    $pdf->AddPage(); // adiciona uma pagina
    $TPagina = 50;
    $Total = 0;
    $pdf->SetFont('Arial','B',9); // seta a fonte do relatorio
    $pdf->Cell(14,4,"Codigo",$bordat,0,"C",$preenc); // escreve a celula
    $pdf->Cell(80,4,"Descricao",$bordat,0,"C",$preenc);
    $pdf->Cell(14,4,"Tipo",$bordat,1,"C",$preenc);

    $result = pg_exec($sql);
    $num = pg_numrows($result);
    $pdf->SetFont('Courier','B',9);
    $linha = 0;

    // k01_codigo,k01_descr, k01_tipo
    
    for($i=0;$i<$num;$i++) {
      $pdf->Cell(14,4,pg_result($result,$i,"k01_codigo"),$borda,0,"C",$preenc);
      $pdf->Cell(80,4,substr(pg_result($result,$i,"k01_descr"),0,40),$borda,0,"L",$preenc);
      $pdf->Cell(14,4,pg_result($result,$i,"k01_tipo"),$borda,1,"C",$preenc);
      $Total += 1;
      if($linha++>$TPagina){
         $linha = 0;
         $pdf->AddPage();
         $pdf->SetFont('Arial','B',9);
         $pdf->Cell(14,4,"Codigo",$bordat,0,"C",$preenc);
         $pdf->Cell(80,4,"Descricao",$bordat,0,"C",$preenc);
         $pdf->Cell(14,4,"Tipo",$bordat,1,"C",$preenc);
         $pdf->SetFont('Courier','B',9);
      }
    }
         $pdf->Cell(14,4,"Total","LTB",0,"C",$preenc);
         $pdf->Cell(80,4,$Total,"TB",0,"C",$preenc);
         $pdf->Cell(14,4,"","TBR",1,"C",$preenc);

///////////////////////////////////////////////////////////////////////
	
  } else if ($opcaoRelatorio == "cfautent"){
    $sql = "
	select k11_id,k11_ipterm,k11_local 
	from cfautent
	where k11_instit = " . db_getsession("DB_instit");


    $head5 = "Relatório dos Terminais de Autenticacao";
    if ($opcaoOrdem == "alfabetica") {
        $head6 = "Ordem de Local";
	$sql .= "
	  order by k11_local 
	  ";
	}
    else{
        $head6 = "Ordem de IP";
        $sql .= "
	  order by k11_id
        ";
    }

    $pdf = new PDF(); // abre a classe
    $pdf->Open(); // abre o relatorio
    $pdf->AliasNbPages(); // gera alias para as paginas
    $pdf->AddPage(); // adiciona uma pagina
    $TPagina = 50;
//  $pdf->SetFillColor(255,255,255); 
//  $pdf->SetFont('Arial','B',12);
//  $pdf->SetTextColor(0,100,255);
//  $pdf->Cell(178,15,"RECEITAS CADASTRADAS",$bordat,1,"C",$preenc);

    $pdf->SetFont('Arial','B',9); // seta a fonte do relatorio
    $pdf->Cell(14,4,"Terminal",$bordat,0,"C",$preenc); // escreve a celula
    $pdf->Cell(40,4,"Cod. do IP",$bordat,0,"C",$preenc);
    $pdf->Cell(20,4,"Local",$bordat,1,"C",$preenc);

    $result = pg_exec($sql);
    $num = pg_numrows($result);
    $pdf->SetFont('Courier','B',9);
    $linha = 0;

    // k11_id,k11_ipterm,k11_local
    
    for($i=0;$i<$num;$i++) {
      $pdf->Cell(14,4,pg_result($result,$i,"k11_id"),$borda,0,"C",$preenc);
      $pdf->Cell(40,4,pg_result($result,$i,"k11_ipterm"),$borda,0,"L",$preenc);
      $pdf->Cell(20,4,pg_result($result,$i,"k11_local"),$borda,1,"L",$preenc);
      if($linha++>$TPagina){
         $linha = 0;
         $pdf->AddPage();
         $pdf->SetFont('Arial','B',9);
         $pdf->Cell(14,4,"Terminal",$bordat,0,"C",$preenc);
         $pdf->Cell(40,4,"Numero do IP",$bordat,0,"C",$preenc);
         $pdf->Cell(20,4,"Local",$bordat,1,"C",$preenc);
         $pdf->SetFont('Courier','B',9);
     }
   }  
  } 
 $pdf->Output();
?>