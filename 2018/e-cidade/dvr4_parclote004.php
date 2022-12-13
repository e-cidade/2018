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

require("fpdf151/scpdf.php");
include("dbforms/db_funcoes.php");
//require("db_conn.php");
//if(!($conn = pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {
//  $pdf->Cell(200,4,"Erro ao conectar","LRBT",1,"C",0);
//  exit;
//}
//$loteam = 45;
//$DB_anousu = 2004;
//$DB_datausu = date();
db_postmemory($HTTP_POST_VARS);
   $sql = "select  b.j01_matric,
			b.j01_numcgm ,
        	d.*,
		e.*
		from loteloteam a
        	inner join iptubase b           on b.j01_idbql = a.j34_idbql
											and b.j01_baixa is null
        	inner join divermatric c        on b.j01_matric = c.matricula
        	inner join diversos d           on d.coddiver = c.coddiver
											and d.exerc = $DB_anousu
		inner join proprietario e 	on b.j01_matric = e.j01_matric 
		where j34_loteam = $loteam
		order by z01_ender,z01_numero,z01_compl
		";
   $principal= pg_exec($sql);
$tipo = 25;   
//db_postmemory($HTTP_POST_VARS,2);
//exit;
flush();
$pdf = new scpdf();
$pdf->Open();
$pref = 'PREFEITURA MUNICIPAL DE SAPIRANGA';
$fazenda = 'SECRETARIA DA FAZENDA';
$imposto = 'PARCELAMENTO DE LOTEAMENTO';
$pdf->SetMargins(5,2);

pg_exec("BEGIN");

for($k = 148;$k < 298;$k++) {
//for($k = 0;$k < pg_numrows($principal);$k++) {
   db_fieldsmemory($principal,$k) ;
   $ver_numcgm = $j01_numcgm;
   $matric = $j01_matric;
   $ver_matric = $j01_matric;
   $totpar = pg_exec("select k00_numpre,k00_numpar,k00_numtot from arrecad where k00_numpre = $k00_numpre ");
for($volta = 0;$volta < pg_numrows($totpar);$volta++) {
   db_fieldsmemory($totpar,$volta);
  //gera um nuvo numpre. "numnov"
  $result = pg_exec("select k00_descr,k00_codbco,k00_codage,k00_txban,k00_rectx,k00_hist1,k00_hist2,
                            k00_hist3,k00_hist4,k00_hist5,k00_hist6,k00_hist7,k00_hist8 
                     from arretipo 
					 where k00_tipo = $tipo");
  db_fieldsmemory($result,0);

  // gera codigo do banco
  $result = pg_exec("select fc_numbco($k00_codbco,'$k00_codage')");
  db_fieldsmemory($result,0);
  $k03_anousu = db_getsession("DB_datausu");
  $k03_numpre = $k00_numpre;
  $sql = "select r.k00_numcgm,r.k00_receit,t.k02_descr,t.k02_drecei,
                 k00_dtoper,
                 k00_dtvenc, k00_numpre,k00_numpar,k00_numtot,
				 sum(r.k00_valor) as valor
          from arrecad r
                   inner join tabrec t on t.k02_codigo = r.k00_receit 
                   inner join tabrecjm on tabrecjm.k02_codjm = t.k02_codjm
		  where r.k00_numpre = ".$k00_numpre." and r.k00_numpar = ".$k00_numpar."
                   group by r.k00_dtoper,r.k00_receit,t.k02_descr,
				         t.k02_drecei,r.k00_numcgm,k00_dtvenc,k00_numpre,
						 k00_numpar,k00_numtot";
  $DadosPagamento = pg_exec($sql);

  $k00_valor = 0;
  for($i=0;$i<pg_numrows($DadosPagamento);$i++){
    $k00_valor  += pg_result($DadosPagamento,$i,"valor");
  } 
  //faz um somatorio do valor
 $k00_receit = trim(pg_result($DadosPagamento,0,"k00_receit"));
 $k00_numpre = trim(pg_result($DadosPagamento,0,"k00_numpre"));
 $k00_numpar = trim(pg_result($DadosPagamento,0,"k00_numpar"));
 $k00_numtot = trim(pg_result($DadosPagamento,0,"k00_numtot"));
 $k00_dtvenc = pg_result($DadosPagamento,0,"k00_dtoper");
  if($k00_dtvenc < pg_result($DadosPagamento,0,"k00_dtvenc")){
     $k00_dtvenc = pg_result($DadosPagamento,0,"k00_dtvenc");
  }
  
  $k00_dtvenc = db_formatar($k00_dtvenc,'d');
   $vlrbar = db_formatar(str_replace('.','',str_pad(number_format($k00_valor,2,"","."),11,"0",STR_PAD_LEFT)),'s','0',11,'e');
//   $vlrbar = "0".str_replace('.','',str_pad(number_format($k00_valor,2,"","."),11,"0",STR_PAD_LEFT));
//   $numbanco = "4268" ;// deve ser tirado do db_config
   $resultnumbco = pg_exec("select numbanco, segmento, formvencfebraban from db_config where codigo = " . db_getsession("DB_instit"));
   db_fieldsmemory($resultnumbco,0) ;// deve ser tirado do db_config

   $numpre = db_numpre($k03_numpre).db_formatar($k00_numpar,'s',"0",3,"e");
   
   $datavencimento = $k00_dtvenc;
   
   if ($formvencfebraban == 1) {
     $db_dtvenc = str_replace("-","",$datavencimento);
     $vencbar = $db_dtvenc . '000000';
   } elseif ($formvencfebraban == 2) {
     $db_dtvenc = str_replace("-","",$datavencimento);
     $db_dtvenc = substr($db_dtvenc,6,2) . substr($db_dtvenc,4,2) . substr($db_dtvenc,2,2);
     $vencbar = $db_dtvenc . '00000000';
   }

   $inibar="8" . $segmento . "6";
   $resultcod = pg_exec("select fc_febraban('$inibar'||'$vlrbar'||'".$numbanco."'||'".$vencbar."'||'$numpre')");
   db_fieldsmemory($resultcod,0);

   if ($fc_febraban == "") {
     db_msgbox("Erro ao gerar codigo de barras (3)!");
     exit;
   }

   $codigo_barras   = substr($fc_febraban,0,strpos($fc_febraban,','));
   $linha_digitavel = substr($fc_febraban,strpos($fc_febraban,',')+1);

  //seleciona dados de identificacao. Verifica se é inscr ou matric e da o respectivo select
  //essa variavel vem do cai3_gerfinanc002.php, pelo window open, criada por parse_str
/*
  if($ver_matric != '') {
     
    $numero = $ver_matric;

    $Identificacao = pg_exec("select *
	                 from proprietario
					 where j01_matric = $ver_matric limit 1");

  } else if($ver_inscr == '') {
    $numero = $ver_inscr;
    $Identificacao = pg_exec("select cgm.z01_nome,cgm.z01_numero,cgm.z01_compl,cgm.z01_ender,cgm.z01_munic,cgm.z01_uf,cgm.z01_cep,c.j14_nome as nomepri,issruas.q02_compl as j39_compl,issruas.q02_numero as j39_numero,j13_descr  
                     from cgm
					 inner join issbase i 
					 on i.q02_numcgm = cgm.z01_numcgm
                     left outer join issruas 	on issruas.q02_inscr = i.q02_inscr
					 left outer join ruas c  	on c.j14_codigo = issruas.j14_codigo
					 left outer join issbairro 	on issbairro.q13_inscr = i.q02_inscr 
					 left outer join bairro b	on b.j13_codi = issbairro.q13_bairro 
					 where i.q02_inscr = ".$ver_inscr);



  } else {
   $numero = $ver_numcgm;
    $Identificacao = pg_exec("select z01_nome,z01_ender,z01_munic,z01_uf,z01_cep,''::bpchar as nomepri,''::bpchar as j39_compl,''::bpchar as j39_numero,z01_bairro as j13_descr 
                     from cgm
					 where z01_numcgm = $numero");
  } 
  
  db_fieldsmemory($Identificacao,0);
*/	
  //verifica divida ativa
  if(isset($j01_matric)){
    $sql = "select j34_setor,j34_quadra,j34_lote,j37_zona,
	               trim(nomepri)||' '||j39_numero||' '||j39_compl as j14_nome,proprietario
		    from proprietario
			where j01_matric = $j01_matric limit 1";
	$result = pg_exec($sql);
	db_fieldsmemory($result,0);
    $sql = "select 0
	        from arrematric a
			     inner join arrecad r on a.k00_numpre = r.k00_numpre 
			where k00_matric = $j01_matric and k00_dtvenc < '".date('Y-m-d',db_getsession("DB_datausu"))."'::date limit 1";
	$result = pg_exec($sql);
    if(pg_numrows($result)!=0){
	   $temdivida = "Existem Débitos Pendente. Verifique sua Situação.";
	}else{    
	   $temdivida = "";
    }
    $sql = "select j23_arealo, j23_areaed,j23_aliq ,vlriptu as j21_valor, j23_vlrter+vlredi as valorvenal
	        from iptucalc,
			     (select sum(j21_valor) as vlriptu
				  from iptucalv
				  where j21_anousu = ".db_getsession("DB_anousu")." and
 				        j21_matric = $j01_matric ) v,
				 (select sum(j22_valor) as vlredi
				  from iptucale
			      where j22_anousu = ".db_getsession("DB_anousu")." and
  		                j22_matric = $j01_matric) e
			where j23_matric = $j01_matric and j23_anousu = ".db_getsession("DB_anousu");
	$result = pg_exec($sql);
    if(pg_numrows($result)==0){
	   echo "Carne nao gerado para este Imóvel.";
	   exit;
	}
    db_fieldsmemory($result,0);
  }

  //select pras observacoes
  $result = pg_exec("select fc_numbco($k00_codbco,'$k00_codage')");
  db_fieldsmemory($result,0);

  $result = pg_exec("select k15_local,k15_aceite,k15_carte,k15_espec,k15_ageced
				   from cadban
                   where k15_codbco = $k00_codbco and
				   k15_codage = '$k00_codage'");
  if(pg_numrows($result) > 0) {	
    $k15_local=pg_result($result,0,0);
    $k15_aceite=pg_result($result,0,1);
    $k15_carte=pg_result($result,0,2);
    $k15_espec=pg_result($result,0,3);
    $k15_ageced=pg_result($result,0,4);
    $fc_numbco=$fc_numbco;
    $dt_hoje = date('Y-m-d',db_getsession("DB_datausu"));
  }
  $numpre = db_sqlformatar($k03_numpre,8,'0').'000999';
  $numpre = $numpre . db_CalculaDV($numpre,11);

  $ip = db_getsession("DB_ip");
  $result = pg_exec("select nomeinst
                     from db_config 
					 where codigo = " . db_getsession("DB_instit"));
  db_fieldsmemory($result,0);
    if($volta+1 % 6  == 0) {
	  $pdf->AddPage();
          
    } else if($volta == 0) {
         $pdf->AddPage();
    }
	$sql = "select * from procdiver where receita = $k00_receit";
    $result = pg_exec($sql);
    db_fieldsmemory($result,0);
  if ( $loteam == 38 ){
     $texto1 = 'PRESTAÇÃO DE LOTE URBANIZADO - LOT. SOL NASCENTE';
	 $texto2 = 'Convênio SEHAB nº 72/99 - Programa Especial do Funco de Desenvolvimento Social';
	 $texto3 = 'Aprovação do Conselho Estadual de Habitação em 08/09/1999';
  }else {
     $texto1 = 'PRESTAÇÃO DE LOTE URBANIZADO COM CASA - LOT. POR-DO-SOL';
	 $texto2 = 'Lei Municipal nº 3049/2002, de 04/12/2002';
	 $texto3 = 'Aprovação do Conselho Estadual de Habitação em dez/2002';
  }
  $loteamento = $dcopdiver;
  $numbanco   = $fc_numbco;
  $v07_parcel = $matric;
  $fazenda = 'FUNDO MUNICIPAL DE HABITAÇÃO';
//  $pdf->SetMargins(0,0,0,0);
  $pdf->SetFont('Times','B',7);
  $pdf->SetTextColor(0,0,0);
  $z = $pdf->GetY() - 1;
  $pdf->SetY($z);
  $pdf->SetX(12);
  $pdf->Cell(75,2,$pref,0,0,"C",0);
//  $pdf->Cell(33,2,'Cód. Arrec. Original',0,0,"C",0);
  $pdf->SetX(102);
  $pdf->Cell(100,2,$pref,0,1,"C",0);
//  $pdf->SetX(170);
//  $pdf->Cell(33,2,'Cód. Arrec. Original',0,1,"C",0);
  $pdf->SetX(12);
  $pdf->Cell(75,2,$fazenda,0,0,"C",0);
//  $pdf->Cell(33,3,$k00_numpre,0,0,"C",0);
  $pdf->SetX(102);
  $pdf->Cell(100,2,$fazenda,0,1,"C",0);
//  $pdf->Cell(33,3,$k00_numpre,0,1,"C",0);
  $pdf->SetX(12);
  $pdf->SetFont('Times','',6);
  $pdf->Cell(75,2,$texto1,0,0,"C",0);
  $pdf->SetX(102);
  $pdf->Cell(100,2,$texto1,0,1,"C",0);
  $pdf->SetX(12);
  $pdf->SetFont('Times','',6);
  $pdf->Cell(75,2,$texto2,0,0,"C",0);
  $pdf->SetX(102);
  $pdf->Cell(100,2,$texto2,0,1,"C",0);
  $pdf->SetX(12);
  $pdf->SetFont('Times','',6);
  $pdf->Cell(75,2,$texto3,0,0,"C",0);
  $pdf->SetX(102);
  $pdf->Cell(100,2,$texto3,0,1,"C",0);


  $y = 0;
  $y = $pdf->GetY();
//  $pdf->Image('imagens/files/logo_boleto.png',5,$y-12,6);
//  $pdf->Image('imagens/files/logo_boleto.png',95,$y-12,6);
  $pdf->SetFont('Times','',5);
  $pdf->Text(7,$y+7,'Responsável');
  $pdf->Text(7,$y+13,'Endereço');
  $pdf->SetFont('Times','B',7);
  $pdf->Rect(5,$y+5,85,12);
  $pdf->Text(7,$y+10,$z01_nome);
  $pdf->Text(7,$y+15,$z01_ender.', '.$z01_numero.'  '.$z01_compl);
  $pdf->SetFont('Times','',5);
  $pdf->Text(108,$y+11,'O PAGAMENTO SOMENTE PODERÁ SER EFETUADO NA PREFEITURA MUNICIPAL DE SAPIRANGA');
//  $pdf->Rect(5,$y+19,28,6);
//  $pdf->Rect(33,$y+19,28,6);
//  $pdf->Rect(61,$y+19,29,6);
  $pdf->Rect(5,$y+19,14,6);
  $pdf->Rect(19,$y+19,14,6);
  $pdf->Rect(33,$y+19,14,6);
  $pdf->Rect(47,$y+19,14,6);
  $pdf->Rect(61,$y+19,14,6);
  $pdf->Rect(75,$y+19,15,6);

  $pdf->Rect(5,$y+25,28,6);
  $pdf->Rect(33,$y+25,28,6);
  $pdf->Rect(61,$y+25,29,6);
  $pdf->Text(8,$y+21,'Matrícula');
  $pdf->Text(24,$y+21,'Zona');
  $pdf->SetFont('Times','',5);
  $pdf->Text(38,$y+21,'Setor');
  $pdf->Text(52,$y+21,'Quadra');
  $pdf->Text(66,$y+21,'Lote');
  $pdf->Text(80,$y+21,'Numpre');
  $pdf->Text(16,$y+27,'Parcela');
  $pdf->Text(41,$y+27,'Vencimento');
  $pdf->Text(72,$y+27,'Valor');
  $pdf->SetFont('Times','',8);
  $pdf->Text(7,$y+24,$j01_matric);
  $pdf->Text(24,$y+24,$j37_zona);
  $pdf->Text(38,$y+24,$j34_setor);
  $pdf->Text(52,$y+24,$j34_quadra);
  $pdf->Text(66,$y+24,$j34_lote);
  $pdf->Text(78,$y+24,db_numpre($k03_numpre,0));
  $pdf->Text(14,$y+30,$k00_numpar.'/'.$k00_numtot);
  $pdf->Text(38,$y+30,$k00_dtvenc);
  $pdf->Text(66,$y+30,db_formatar($k00_valor,'f'));
  $coluna = 12.22;
  $pdf->Rect(95,$y+2,$coluna,6);
  $pdf->Rect(95+($coluna),$y+2,$coluna,6);
  $pdf->Rect(95+($coluna*2),$y+2,$coluna,6);
  $pdf->Rect(95+($coluna*3),$y+2,$coluna,6);
  $pdf->Rect(95+($coluna*4),$y+2,$coluna,6);
  $pdf->Rect(95+($coluna*5),$y+2,$coluna,6);
  $pdf->Rect(95+($coluna*6),$y+2,$coluna,6);
  $pdf->Rect(95+($coluna*7),$y+2,$coluna,6);
  $pdf->Rect(95+($coluna*8),$y+2,$coluna,6);

  $pdf->SetFont('Times','',5);
  $pdf->Text(95+3,$y+4,'Inscrição');
  $pdf->Text(95+($coluna)+4,$y+4,'Numpre');
  $pdf->Text(95+($coluna*2)+5,$y+4,'Zona');
  $pdf->Text(95+($coluna*3)+4,$y+4,'Setor');
  $pdf->Text(95+($coluna*4)+4,$y+4,'Quadra');
  $pdf->Text(95+($coluna*5)+5,$y+4,'Lote');
  $pdf->Text(95+($coluna*6)+3,$y+4,'Parcela');
  $pdf->Text(95+($coluna*7)+2,$y+4,'Vencimento');
  $pdf->Text(95+($coluna*8)+4,$y+4,'Valor');

  $pdf->SetFont('Times','',7);
  $pdf->Text(95+2,$y+7,$j01_matric);
  $pdf->Text(95+($coluna)+1,$y+7,db_numpre($k03_numpre,0));
  $pdf->Text(95+($coluna*2)+5,$y+7,$j37_zona);
  $pdf->Text(95+($coluna*3)+4,$y+7,$j34_setor);
  $pdf->Text(95+($coluna*4)+4,$y+7,$j34_quadra);
  $pdf->Text(95+($coluna*5)+4,$y+7,$j34_lote);
  $pdf->Text(95+($coluna*6)+3,$y+7,$k00_numpar.'/'.$k00_numtot);
  $pdf->Text(95+($coluna*7)+0.5,$y+7,$k00_dtvenc);
  $pdf->Text(95+($coluna*8),$y+7,db_formatar($k00_valor,'f'));
//  $pdf->Rect(95,$y-2,110,10);

  $pdf->SetLineWidth(0.05);
  $pdf->SetDash(1,1); 
  $pdf->Line(5,$y+33,205,$y+33);
  $pdf->Line(93,$y-14,93,$y+34);
  $pdf->SetDash(); 
  $pdf->Ln(41);
  $pdf->SetFont('Times','',10);
  $pdf->Text(110,$y+15,$linha_digitavel);
  $pdf->int25(95,$y+16,$codigo_barras,15,0.341);

}
}
 pg_exec("COMMIT");

$tmpfile = tempnam('tmp','tmppdf').'.pdf';
$pdf->Output($tmpfile);
 echo "<script>location.href='".$tmpfile."'</script>";

//$pdf->Output();
////////FIM CARNES DE LOTEAMENTO
pg_exec("commit");
echo "<script> alert('Processamento Concluído.')</script>";
echo "<script> location.href='dvr3_parclote003.php'</script>";
//    $pdf->Output();

?>