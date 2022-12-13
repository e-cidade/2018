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

include("classes/db_iptubase_classe.php");

include("fpdf151/pdf.php");

$head1 = "";
$head2 = "";

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','',11);
$primeiro =0;
$totalparc  =1;
$totalvalorfinal=0;
$numpre_cor = "";
$numpre_par = "";
$numpre_obs = "";
$numpar_obs = "";
$pre1="";
$pre = 0;
$where1 ="and k27_instit = ".db_getsession('DB_instit'); 
$data_i ="";
$data_f ="";
$cabec = false;
if ($datainicial != "--" and $datafinal != "--"){

      $where1 .= " and (k27_data,k27_data+k27_dias) overlaps ( DATE '$datainicial' - '1 day'::interval, DATE '$datafinal' + '1 day'::interval)";
      $data_f = db_formatar($datafinal,"d");
      $data_i = db_formatar($datainicial,"d");
  }else{

    if($datainicial!="--"){
      $where1 .=" and k27_data >= '$datainicial'";
      $data_i = db_formatar($datainicial,"d");
   }
  
   if($datafinal!="--"){
      $where1 .=" and k27_data <= '$datafinal'";
      $data_f = db_formatar($datafinal,"d");
   }
  }

  $head2 = "Período : $data_i até $data_f";

  if ($tipo_filtro=="CGM"){
		 $numcgm = $cod_filtro;
		 $where =" where k00_numcgm = $cod_filtro $where1";	
	}else if ($tipo_filtro=="MATRICULA"){
		 $matric = $cod_filtro;
		 $where =" where k00_matric =  $cod_filtro $where1";
     $cliptubase = new cl_iptubase;
		 $result_inf = $cliptubase->proprietario_record($cliptubase->proprietario_query($matric,"j34_setor#j34_quadra#j34_lote#tipopri#j39_numero#j39_compl#nomepri#z01_nome#z01_numcgm#z01_cgmpri"));
		 if ($cliptubase->numrows!=0) {
		    db_fieldsmemory($result_inf,0);
		    $z01_numcgm = $z01_cgmpri;
		    $outros = "Matrícula: ".$matric." - SQL: ".$j34_setor."/".$j34_quadra."/".$j34_lote." - Logradouro: ".$tipopri." ".$nomepri.", ".$j39_numero." ".$j39_compl;
		 } 
  }else if ($tipo_filtro=="INSCRICAO"){
		 $inscr = $cod_filtro;
     $where =" where k00_inscr = $cod_filtro $where1";
		 
  }else if ($tipo_filtro=="NUMPRE"){
		 $numpre = $cod_filtro;
     $where =" where k28_numpre = $cod_filtro $where1";
  }
  $head4 = "$tipo_filtro: $cod_filtro";
  $head6 = "RELATÓRIO DE DEBITOS JUSTIFICADOS";
  $sqlarrejustreg="select distinct k27_sequencia,k27_usuario,
                    case 
                      when length(nome) > 28 then substr(nome,0,27)||'...'
                      else nome
                    end as nome,
                    k27_data,k27_hora,k27_dias,k27_obs,k00_numcgm 
										from arrejust 
										inner join arrejustreg on k27_sequencia=k28_arrejust 
										inner join db_usuarios on k27_usuario  = id_usuario
										inner join arrenumcgm  on k28_numpre   = arrenumcgm.k00_numpre
										left  join arrematric  on k28_numpre   = arrematric.k00_numpre
										left  join arreinscr   on k28_numpre   = arreinscr.k00_numpre
										$where
                    order by k27_data ";
  //echo "$sqlarrejustreg";
  $result = db_query($sqlarrejustreg);
	$linhas = pg_num_rows($result);
  $total= 0;
  
  
if($linhas>0){
  for($i=0;$i<$linhas;$i++) {
    db_fieldsmemory($result,$i);
    
    $dados = "select z01_numcgm,z01_nome,z01_ender,z01_munic,z01_uf,z01_cgccpf,z01_ident,z01_numero,z01_compl
from cgm where z01_numcgm = $k00_numcgm";
    //echo "bbbbbb   ====== $dados";
    $resultdados = db_query($dados);
	  $linhasdados = pg_num_rows($resultdados);
	  if($linhasdados>0){
	    db_fieldsmemory($resultdados,0);
	  }
     $sqlnumpre="
       select k28_arrejust,
              k28_numpre,
              k28_numpar,
              k28_receita ||' - '|| k02_descr as receita,
			        case when arrecant.k00_valor is not null then arrecant.k00_valor
			            when arrecad.k00_valor is not null then arrecad.k00_valor
			            when arreold.k00_valor is not null then arreold.k00_valor
			        else '0' 
              end as valor,
			        case when arrematric.k00_numpre is not null then 'Matricula - '|| k00_matric
			            when arreinscr.k00_numpre is not null  then 'Inscricao - '||k00_inscr 
			        else 'CGM - '||arrenumcgm.k00_numcgm  
			        end as origem
       from arrejustreg
       inner join arrejust on k27_sequencia       = k28_arrejust 
       left join arrecad      on arrecad.k00_numpre  = k28_numpre
                             and arrecad.k00_numpar  = k28_numpar
			                       and arrecad.k00_receit  = k28_receita
       left join arrecant     on arrecant.k00_numpre = k28_numpre
                             and arrecant.k00_numpar = k28_numpar
                             and arrecant.k00_receit = k28_receita
       left join arreold      on arreold.k00_numpre  = k28_numpre
                             and arreold.k00_numpar  = k28_numpar 
                             and arreold.k00_receit  = k28_receita
       inner join arrenumcgm  on k28_numpre          = arrenumcgm.k00_numpre
       left  join arrematric  on k28_numpre          = arrematric.k00_numpre
       left  join arreinscr   on k28_numpre          = arreinscr.k00_numpre
       inner join tabrec      on k28_receita         = k02_codigo
       where k27_sequencia = $k27_sequencia
       order by k28_numpre,k28_numpar";
      //echo "<br>$sqlnumpre<br>";
      $resultnumpre = db_query($sqlnumpre);
	    $linhasnumpre = pg_num_rows($resultnumpre);
      
   if($pdf->GetY() > ( $pdf->h - 30 )||($primeiro ==0)){
      $primeiro =1;
           
      $pdf->Text($pdf->w-20,$pdf->h-5, $pdf->PageNo());
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',10);
      //$pdf->MultiCell(190,12,"RELATÓRIO DE DEBITOS JUSTIFICADOS",0,"C",0);
      $pdf->SetFont('Arial','B',9);
      //$pdf->Cell(190,10,$tipo_filtro.": ".$cod_filtro,0,1,"L",0);
      $pdf->setxy(8,35);
      $X = 8;
      $Y = 38;
    if(!$cabec) {
      $pdf->Cell(0,21,'',"TB",0,'C');
      $pdf->Text($X,$Y,"Numcgm:");
      $pdf->Text($X+40,$Y,@$outros);
      $pdf->Text($X,$Y + 4,"Nome:");
      $pdf->Text($X,$Y + 8,"CNPJ/CPF:");
      $pdf->Text($X + 45,$Y + 8,"Identidade:");
      $pdf->Text($X,$Y + 12,"Endereço:");
      $pdf->Text($X + 110,$Y + 12,"Número:");
      $pdf->Text($X + 155,$Y + 12,"Complemento:");
      $pdf->Text($X,$Y + 16,"Município:");
      $pdf->Text($X + 55,$Y + 16,"UF:");
      $pdf->SetFont('Arial','I',8);
      $pdf->Text($X + 18,$Y,$z01_numcgm);
      $pdf->Text($X + 18,$Y + 4,$z01_nome);
      $pdf->Text($X + 18,$Y + 8,db_cgccpf($z01_cgccpf));
      $pdf->Text($X + 18 + 45,$Y + 8,$z01_ident);
      $pdf->Text($X + 18,$Y + 12,$z01_ender);
      $pdf->Text($X + 130,$Y + 12,$z01_numero);
      $pdf->Text($X + 180,$Y + 12,$z01_compl);
      $pdf->Text($X + 18,$Y + 16,$z01_munic);
      $pdf->Text($X + 18 + 45,$Y + 16,$z01_uf);
      $pdf->SetXY(5,60);
      $cabec = true;
    }
    $pdf->SetFont('Arial','B',9);
      $pdf->Ln(3);
      $pdf->Cell(15,5,"Código",1,0,"C",1);
      $pdf->Cell(15,5,"Data",1,0,"C",1);
      $pdf->Cell(15,5,"Hora",1,0,"C",1);
      $pdf->Cell(15,5,"Dias just.",1,0,"C",1);
      $pdf->Cell(50,5,"Usuário",1,0,"C",1);
      $pdf->Cell(80,5,"Obs",1,0,"C",1);
      $pdf->Ln();
      	$pdf->Cell(60,5,"",1,0,"C",1);
		    $pdf->Cell(20,5,"Numpre",1,0,"C",1);
		    $pdf->Cell(15,5,"Parc",1,0,"C",1);
		    $pdf->Cell(45,5,"Receita",1,0,"C",1);
		    $pdf->Cell(20,5,"Valor",1,0,"C",1);
		    $pdf->Cell(30,5,"Origem",1,0,"C",1);
        $pdf->Ln();
    }
    // para fazer zebrado
    /*
     * // SE A COR FOR POR NUMPRE
    if($numpre_cor==""){
		      $numpre_cor = $k28_numpre;
		      $numpre_par = $k28_numpar;

	      }
	      if($numpre_cor != $k28_numpre || $numpre_par != $k28_numpar ){
          $numpre_cor = $k28_numpre;
		      $numpre_par = $k28_numpar;
          if($pre == 0){
		        $pre = 1;
		      }else{
            $pre = 0;
          }
	      }
            
     //SE A COR FOR POR COD JUSTIFICADO
  if($numpre_cor==""){
		      $numpre_cor = $k27_sequencia;
		 }
	      if($numpre_cor != $k27_sequencia){
          $numpre_cor = $k27_sequencia;
		      
          if($pre == 0){
		        $pre = 1;
		      }else{
            $pre = 0;
          }
	      }
    
    */
  
	      
    $pdf->SetFont('Arial','',8);
    $pre = 0;
    $pdf->Cell(15,5,$k27_sequencia,"T",0,"C",$pre);
    $pdf->Cell(15,5,db_formatar($k27_data,'d'),"T",0,"C",$pre);
    $pdf->Cell(15,5,$k27_hora,"T",0,"C",$pre);
    $pdf->Cell(15,5,$k27_dias,"T",0,"C",$pre);
    $pdf->Cell(50,5,$nome,"T",0,"L",$pre);
    $pdf->MultiCell(80,5,$k27_obs,"T","L",$pre);
    $total ++;
		if($linhasnumpre>0){
		  $totalvalor = 0;
      for($x=0;$x<$linhasnumpre;$x++) {
		    db_fieldsmemory($resultnumpre,$x);
			  if($numpre_cor==0){
			      $numpre_cor = $k28_numpre;
			      $numpre_par = $k28_numpar;
	      }
	      if($numpre_cor != $k28_numpre || $numpre_par != $k28_numpar ){
          $numpre_cor = $k28_numpre;
		      $numpre_par = $k28_numpar;
          $totalparc += 1;
	      }
		    $pdf->Cell(60,5,"",0,0,"C",$pre);
		    $pdf->Cell(20,5,$k28_numpre,0,0,"C",$pre);
		    $pdf->Cell(15,5,$k28_numpar,0,0,"C",$pre);
		    $pdf->Cell(45,5,$receita,0,0,"L",$pre);
		    $pdf->Cell(20,5,db_formatar($valor,"f"),0,0,"R",$pre);
		    $pdf->Cell(30,5,$origem,0,0,"L",$pre);
		    $pdf->Ln();
		    $totalvalor += $valor;
		    
		  }
		  $pdf->SetFont('Arial','B',8);
		  $pdf->Cell(60,4,"",0,0,"C",$pre);
		  $pdf->Cell(80,4,"Total","T",0,"L",$pre);
		  $pdf->Cell(20,4,$totalvalor,"T",0,"R",$pre);
		  $pdf->Cell(30,4,"","T",1,"L",$pre);
		  $totalvalorfinal += $totalvalor;
		}
  }
}


$pdf->Cell(190,4,"","T",1,"L",$pre);
$pdf->Ln(5);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(60,5,"Total Justificados :   ".@$total,"TB",0,"L",0);
$pdf->Cell(40,5,"Total de Parcelas :".$totalparc,"TB",0,"L",$pre);
$pdf->Cell(60,5,"Total de Valor :".$totalvalorfinal,"TB",0,"R",$pre);
$pdf->Cell(30,5,"","TB",0,"L",$pre);
$pdf->Ln();
$pdf->Output();

?>