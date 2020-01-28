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

  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  include("fpdf151/pdf1.php");
  include("classes/db_calend_classe.php");
  ///////////////////////////////////////////////////////////////////////
  $head4 = "Teste de Calendario";

  $pdf = new PDF1; // abre a classe
  $pdf->Open(); // abre o relatorio
  $pdf->AliasNbPages(); // gera alias para as paginas
  $pdf->AddPage(); // adiciona uma pagina
  $pdf->SetFont('Arial','B',9); // seta a fonte do relatorio
//    $result = pg_exec($sql);
//    $num = pg_numrows($result);
  if(!isset($anousu) || $anousu == ""){
    $anousu = db_getsession("DB_anousu");
  }
  $clcalend = new cl_calend;
  $result = $clcalend->sql_record($clcalend->sql_query("","*","k13_data"," extract(year from k13_data)::integer = $anousu"));
  if($clcalend->numrows == 0){
    db_redireciona("db_erros.php?fechar=true&db_erro=Exercício sem Calendário Gerado.");
  }  
  
  $qualmes = array("Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"); 
  $trocalinha = false;
  $pdf->SetFont('Times','B',12);
  $pdf->multicell(0,10,"CALENDÁRIO OFICIAL DE ".$anousu,0,"C",0);
  $pdf->setY(100);
  $pdf->SetFont('Arial','B',9);
  for($mes=1;$mes<13;$mes++){
    $ultimodia=date('t',mktime(0,0,0,$mes,1));
    $linha = 0;
    $coluna = 6;
    $tamanho = 8;
    $xlinha = 6;
    if($mes<5){
      $poscol = 5;
      $pdf->setX($poscol);
    }else if ($mes<9){
      $poscol = 75;
      if($trocalinha==false){
	    $trocalinha = true;
        $pdf->setY(100);
      }
      $pdf->setX($poscol);
    }else{
      $poscol = 145;
	  if($mes == 9) $trocalinha=false;
      if($trocalinha==false){
	    $trocalinha = true;
        $pdf->setY(100);
      }
      $pdf->setX($poscol);
    }  
    $pdf->Cell(56,4,strtoupper($qualmes[$mes-1]),"LRBT",1,"C",0); // escreve a celula
    $pdf->setX($poscol);
    $dia = 1;
    $matriz_dia = array("Dom" ,"Seg","Ter" ,"Qua","Qui","Sex","Sab");
    for($y=0;$y<=$xlinha;$y++) {
       for($i=0;$i<sizeof($matriz_dia)-1;$i++) {
          $pdf->setfillcolor(170,170,255); 
	      if($y==0)
	        $pdf->setfillcolor(153,169,174); 
	      else if($i==0)
             $pdf->setfillcolor(193,168,174); 

		  if($mes<10) $mesm = "0".$mes;
          else $mesm = $mes;
		  if($matriz_dia[$i]<10) $diam = "0".$matriz_dia[$i];
          else $diam = $matriz_dia[$i];

          $result = $clcalend->sql_record($clcalend->sql_query($anousu."-".$mesm."-".$diam));
          if($clcalend->numrows != 0){
             $pdf->setfillcolor(249,107,87);
          }

		  if($matriz_dia[0]!="" || $matriz_dia[6]!="")
            $pdf->Cell($tamanho,4,$matriz_dia[$i],"LRTB",0,"C",1);
          else{
            $pdf->setfillcolor(170,170,255); 
            $pdf->Cell($tamanho,4,$matriz_dia[$i],"LRTB",0,"C",1);
          }
	      $pdf->setfillcolor(255,255,255); 
       }
       $pdf->setfillcolor(170,170,255); 
       if($y==0)
	      $pdf->setfillcolor(153,169,174); 
       else
          $pdf->setfillcolor(193,168,174); 

	   if($mes<10) $mesm = "0".$mes;
       else $mesm = $mes;
	   if($matriz_dia[6]<10) $diam = "0".$matriz_dia[6];
       else $diam = $matriz_dia[6];
       $result = $clcalend->sql_record($clcalend->sql_query($anousu."-".$mesm."-".$diam));
       if($clcalend->numrows != 0){
          $pdf->setfillcolor(249,107,87);
       }
       if($matriz_dia[0]!="" || $matriz_dia[6]!="")
         $pdf->Cell($tamanho,4,$matriz_dia[6],"LRTB",1,"C",1);
       else{
         $pdf->setfillcolor(170,170,255); 
	     $pdf->Cell($tamanho,4,$matriz_dia[6],"LRTB",1,"C",1);
       }
       $pdf->setfillcolor(255,255,255); 
       $pdf->setX($poscol);
       if($y==0){
          $diames = date('w',mktime(0,0,0,$mes,1));  
  	      for($m=0;$m<7;$m++){
            if($m>=$diames)
	          $matriz_dia[$m] = $dia++;
	        else
	          $matriz_dia[$m] = "" ;
	      }
       }else{
	     for($m=0;$m<7;$m++){
	       if($dia<=$ultimodia)
	         $matriz_dia[$m] = $dia++;
	       else
             $matriz_dia[$m] = "" ;
	     }
       }	
     }
     $pdf->Cell(44,6,"" ,"",1,"C",0);
     $pdf->setX($poscol);
   }
   $pdf->Output();
?>