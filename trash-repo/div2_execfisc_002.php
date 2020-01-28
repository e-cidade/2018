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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if ( !isset($parcel) || $parcel == '' ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Parcelamento não Encontrado.');
  exit; 
}
include("fpdf151/pdf1.php");
$exercicio = db_getsession("DB_anousu");
$borda = 1; 
$bordat = 1;
$preenc = 0;
$TPagina = 57;

///////////////////////////////////////////////////////////////////////
//$xinicial = 50006 ;

 $sql = "select case when v01_numcgm is not null 
                     then v01_numcgm 
                     else v07_numcgm 
                     end as z01_numcgm,
                * 
         from inicial
              inner join inicialcert
 		on inicial.inicial = inicialcert.inicial
              inner join certid
  		on certid.v13_certid = inicialcert.certidao
              left outer join certdiv
		on certidiv.v14_certid = certid.v13_certid
     	      left outer join certter
		on certter.v14_certid = certid.v13_certid
	      left outer join divida
		on divida.v01_coddiv = certidiv.v14_coddiv
	      left outer join termo
		on termo.v07_parcel = certter.v14_parcel
	where inicial.inicial = $xinicial
";
$result=pg_query($sql);
if ( pg_numrows($result) == 0 ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Parcelamento no. '.$parcel. ' não Encontrado.');
  exit; 
}
db_fieldsmemory($result,0);
if ($leng == '14' ) {
   $cpf = db_formatar($z01_cgccpf,'cnpj');
} else {
   $cpf = db_formatar($z01_cgccpf,'cpf');
}
//for($i = 0;$i < pg_numrows($result);$i++){
//   $TotalTaxas  += pg_result($result,$i,"totaltaxa");
//   $TotalQTaxas += pg_result($result,$i,"quanttaxa");
//}
//$pdf->MultiCell(,0,5,"TERMO DE PARCELAMENTO DE DÍIVIDA");
$head1 = 'Departamento de Fazenda';
$pdf = new PDF1(); // abre a classe

if(!defined('DB_BIBLIOT')){
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
}

$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','',11);
if ( date('m') == '01' ) {
     $Mes = 'Janeiro';
}else if ( date('m') == '02') {
     $Mes = 'Fevereiro';
}else if ( date('m') == '03') {
     $Mes = 'Março';
}else if ( date('m') == '04') {
     $Mes = 'Abril';
}else if ( date('m') == '05') {
     $Mes = 'Maio';
}else if ( date('m') == '06') {
     $Mes = 'Junho';
}else if ( date('m') == '07') {
     $Mes = 'Julho';
}else if ( date('m') == '08') {
     $Mes = 'Agosto';
}else if ( date('m') == '09') {
     $Mes = 'Setembro';
}else if ( date('m') == '10') {
     $Mes = 'Outubro';
}else if ( date('m') == '11') {
     $Mes = 'Novembro';
}else if ( date('m') == '12') {
     $Mes = 'Dezembro';
}
$numpar  = $v07_totpar;
$entrada = $v07_vlrent;
$vencent = $v07_dtlanc;
$vlrpar  = $v07_vlrpar;
$vencpar = $v07_dtvenc;
$extenso = db_extenso($v07_valor);
$texto1 = 'MUNICÍPIO DE '.$munic.', pessoal jurídica de direito público, inscrito no CNPJ/MF '.$cgc.', com sede na '.$ender.', '.$bairro.', em '.$munic.'-'.$uf.', vem à presença de Vossa Excelência, por intermédio de seus procuradores, propor a presente:'; 
$pdf->SetFont('Arial','B',11);
$pdf->MultiCell(0,4,"TERMO DE CONFISSÃO DE DÍVIDA E COMPROMISSO DE PAGAMENTO",0,"C",0,0);
$pdf->Ln(4);
$pdf->SetFont('Arial','',11);
$pdf->MultiCell(0,5,$texto1,0,"J",0,40);

$sql = "select termodiv.*,
               divida.*,
	       coalesce(divmatric.v01_matric,0) as matric,
	       coalesce(divinscr.v01_inscr,0) as inscr,
	       coalesce(divcontr.v01_contr,0) as contr,
	       case when a.j01_numcgm is not null
	            then (select z01_nome from cgm where z01_numcgm =
		    a.j01_numcgm)
		    end as nomematric,
	       case when q02_numcgm is not null
	            then (select z01_nome from cgm where z01_numcgm =
		    q02_numcgm)
		    end as nomeinscr,
	       case when b.j01_numcgm is not null
	            then (select z01_nome from cgm where z01_numcgm =
		    b.j01_numcgm)
		    end as nomecontr
        from termodiv 
	     inner join 
	           divida  
		      on v01_coddiv = coddiv
        left outer join
                   divmatric
                      on divmatric.v01_coddiv =	 divida.v01_coddiv
        left outer join
	           iptubase a
		      on divmatric.v01_matric = a.j01_matric
        left outer join
                   divinscr
                      on divinscr.v01_coddiv  =  divida.v01_coddiv
        left outer join
	           issbase
		      on divinscr.v01_inscr = issbase.q02_inscr
        left outer join
		   divcontr
	              on divcontr.v01_coddiv  =  divida.v01_coddiv
        left outer join
		   contrib
	              on divcontr.v01_contr  =  contrib.d07_contri		      
	left outer join
	           iptubase b
		      on b.j01_matric = contrib.d07_matric
		      
	where parcel = $parcel";
$result = pg_exec($sql);

if ( pg_result($result,0,"matric") > 0 ) {
   $nomedeb = 'Imposto Predial Territorial Urbano em débitos de '.pg_result($result,0,"nomematric");
} else if ( pg_result($result,0,"inscr") > 0 ) {
   $nomedeb = 'Dívida Ativa em débitos de '.pg_result($result,0,"nomeinscr");
} else if ( pg_result($result,0,"contr") > 0 ) {
      $nomedeb =  'Contribuição de Melhorias em débitos de '.pg_result($result,0,"nomecontr");
}
$pdf->SetFont('Arial','B',11);
$pdf->MultiCell(0,8,$nomedeb,0,1,0,0);
$num = pg_numrows($result);
$linha = 20;
//$pdf->Ln(4);
$Tv01_vlrhis = 0;
$Tv01_valor  = 0;
$Tmulta      = 0;
$Tjuros      = 0;
$Tdesconto   = 0;
$Tv01_valor  = 0;
$Total       = 0;
$pdf->SetFont('Arial','B',9);
$pdf->Cell(20,4,"EXERC.",1,0,"C",1);
$pdf->Cell(20,4,"VENC.",1,0,"C",1);
$pdf->Cell(25,4,"HISTÓRICO",1,0,"C",1);
$pdf->Cell(25,4,"CORRIGIDO",1,0,"C",1);
$pdf->Cell(25,4,"MULTA",1,0,"C",1);
$pdf->Cell(25,4,"JUROS",1,0,"C",1);
$pdf->Cell(25,4,"DESCONTO",1,0,"C",1);
$pdf->Cell(25,4,"TOTAL",1,1,"C",1);
for($i=0;$i<$num;$i++) {
   if($linha++>57){
      $linha = 0;
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(20,4,"EXERC.",1,0,"C",1);
      $pdf->Cell(20,4,"VENC.",1,0,"C",1);
      $pdf->Cell(25,4,"HISTÓRICO",1,0,"C",1);
      $pdf->Cell(25,4,"CORRIGIDO",1,0,"C",1);
      $pdf->Cell(25,4,"MULTA",1,0,"C",1);
      $pdf->Cell(25,4,"JUROS",1,0,"C",1);
      $pdf->Cell(25,4,"DESCONTO",1,0,"C",1);
      $pdf->Cell(25,4,"TOTAL",1,1,"C",1);
   }
   db_fieldsmemory($result,$i);
   $pdf->SetFont('Arial','',9);
   $pdf->Cell(20,4,$v01_exerc,1,0,"C",0);
   $pdf->cell(20,4,db_formatar($v01_dtvenc,'d'),1,0,"c",0);
   $pdf->Cell(25,4,number_format($v01_vlrhis,2,",","."),1,0,"R",0);
   $pdf->Cell(25,4,number_format($v01_valor,2,",","."),1,0,"R",0);
   $pdf->Cell(25,4,number_format($multa,2,",","."),1,0,"R",0);
   $pdf->Cell(25,4,number_format($juros,2,",","."),1,0,"R",0);
   $pdf->Cell(25,4,number_format($desconto,2,",","."),1,0,"R",0);
   $pdf->Cell(25,4,number_format($v01_valor+$multa+$juros-$desconto,2,",","."),1,1,"R",0);
   $Tv01_vlrhis += $v01_vlrhis;
   $Tv01_valor  += $v01_valor ;
   $Tmulta      += $multa     ;
   $Tjuros      += $juros     ;
   $Tdesconto   += $desconto  ;
   $Total       += $v01_valor+$multa+$juros-$desconto ;
}
$pdf->SetFont('Arial','B',9);
$pdf->Cell(20,6,'Total',1,0,"L",0);
$pdf->cell(20,6,'',1,0,"c",0);
$pdf->Cell(25,6,number_format($Tv01_vlrhis,2,",","."),1,0,"R",0);
$pdf->Cell(25,6,number_format($Tv01_valor,2,",","."),1,0,"R",0);
$pdf->Cell(25,6,number_format($Tmulta,2,",","."),1,0,"R",0);
$pdf->Cell(25,6,number_format($Tjuros,2,",","."),1,0,"R",0);
$pdf->Cell(25,6,number_format($Tdesconto,2,",","."),1,0,"R",0);
$pdf->Cell(25,6,number_format($Total,2,",","."),1,1,"R",0);
$pdf->Ln(2);
 
$pdf->SetFont('Arial','B',9);
$pdf->Cell(95,6,"DADOS DA ENTRADA",1,0,"C",1);
$pdf->Cell(95,6,"DADOS DAS PARCELAS",1,1,"C",1);
$y = $pdf->GetY();
$pdf->SetFont('Arial','',9);
$pdf->Cell(50,5,'Valor da Entrada ',1,0,"L",0);
$pdf->Cell(45,5,db_formatar($entrada,'f'),1,1,"R",0);
$pdf->Cell(50,5,'Data do Vencimento ','LRTB',0,"L",0);
$pdf->Cell(45,5,db_formatar($vencent,'d'),'LRTB',1,"R",0);
$pdf->Cell(50,5,'','LRB',0,"L",0);
$pdf->Cell(45,5,'','LRB',1,"R",0);
$pdf->SetXY(105,$y);
$pdf->Cell(50,5,'Numero de Parcelas ',1,0,"L",0);
$pdf->Cell(45,5,$numpar,1,1,"R",0);
$pdf->SetX(105);
$pdf->Cell(50,5,'Valor das Demais Parcelas ',1,0,"L",0);
$pdf->Cell(45,5,db_formatar($vlrpar,'f'),1,1,"R",0);
$pdf->SetX(105);
$pdf->Cell(50,5,'Data do Vencimento ',1,0,"L",0);
$pdf->Cell(45,5,db_formatar($vencpar,'d'),1,1,"R",0);
$pdf->SetFont('Arial','',11);
$pdf->Ln(2);
$pdf->MultiCell(0,5,$texto2,0,"J",0,40);
$pdf->MultiCell(0,5,$texto3,0,"J",0,40);
$pdf->MultiCell(0,5,$texto4,0,"J",0,40);
$pdf->MultiCell(0,8,$nomeinst.', '.date('d')." de ".$Mes." de ".date('Y').'.',0,0,"R",0);

$pdf->MultiCell(0,4,"\n\n\n".'Contribuinte ou Representante Legal',0,"C",0);
$pdf->Ln(5);
if ( $pdf->GetY() > 248) {
   $pdf->AddPage();
}
$pdf->MultiCell(0,4,$texto5,0,"J",0,0);
$y = $pdf->GetY();
$pdf->MultiCell(90,4,"\n\n\n".'Flávio Konzen'."\n".'Secretária Municipal da Fazenda',0,"C",0);
$pdf->SetXY(110,$y);
$pdf->MultiCell(90,4,"\n\n\n".'Eroni Belles Ennes'."\n".'Chefe de Seção',0,"C",0);
	    
if(!defined('DB_BIBLIOT'))

$pdf->Output();