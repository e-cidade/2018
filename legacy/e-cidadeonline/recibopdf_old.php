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

  include("libs/db_conecta.php");
  include("libs/db_stdlib.php");
  define('FPDF_FONTPATH','font/');
  require("fpdf151/fpdf.php");
  postmemory($HTTP_POST_VARS);
  $clquery = new cl_query;
  $nova=false;
  $head1 = "";
  $head2 = "";
  $head3 = "";
  $head4 = "";
  $head5 = "";
  $head6 = "";
  $head7 = "";
  $head8 = "";
  $head9 = "";
  
  $clquery->sql_query("issplan inner join cgm on q20_numcgm = z01_numcgm","issplan.*,z01_nome,z01_ender,z01_munic","","q20_planilha= $planilha");
  $clquery->sql_record($clquery->sql);
  db_fieldsmemory($clquery->result,0);


  $clquery->sql_query("issplanit","round(sum(q21_valor),2) as q21_valor","","q21_planilha= $planilha");
  $clquery->sql_record($clquery->sql);
  db_fieldsmemory($clquery->result,0);

  if($q20_numpre==""){
    $clquery->sql_query(""," nextval('numpre_campo') as q20_numpre");
    $clquery->sql_record($clquery->sql);
    db_fieldsmemory($clquery->result,0);
    $clquery->sql_query(""," nextval('numbanco_campo') as q20_numbco");
    $clquery->sql_record($clquery->sql);
    db_fieldsmemory($clquery->result,0);
	// $q20_numbco = '8200572002';

    $clquery->sql_update("issplan"," q20_numpre = $q20_numpre, q20_numbco = $q20_numbco"," q20_planilha = $planilha"); 

  }
  $mesv = $q20_mes + 1;
  $anov = $q20_ano ;
  if($q20_mes>12){
    $mesv = 1;
	$anov ++ ;
  }
  
  $dtvenc = date('Y-m-d',mktime(0,0,0,$mesv,10,$anov));
  if($dtvenc < date("Y-m-d")){
    echo "<script>window.opener.alert('Documento Vencido. Emissão não Permitida.');window.close()</script>";
    exit;
  }
  if(isset($valortotal)){
    $tot=$valortotal;    
  }else{
    $tot=$q21_valor;    
  }  
  calc_dac($tot,$dtvenc,$q20_numbco);
  $linha_digitavel  = substr($digitavel_1,0,5).".".substr($digitavel_1,6)."   ";
  $linha_digitavel .= substr($digitavel_2,0,5).".".substr($digitavel_2,6)."   ";
  $linha_digitavel .= substr($digitavel_3,0,5).".".substr($digitavel_3,6)."   ";
  $linha_digitavel .= $digitavel_4."   ";
  $linha_digitavel .= $digitavel_5;

  $clquery->sql_query("db_config","*","","codigo= $DB_INSTITUICAO");
  $clquery->sql_record($clquery->sql);
  db_fieldsmemory($clquery->result,0);


  $localPagamento = "PAGUE NAS AGENCIAS DA CAIXA ECONOMICA FEDERAL OU AGENCIAS LOTERICAS";
  $parcela = "1";
  $vencimento = db_formatar($dtvenc,'d');
  $cedente = $nomeinst;
  $agencia_codigo_cedente = "0461.006.00000037-6";
  $data_documento = date("d-m-Y");
  $numero_documento = $q20_numbco."-".digito_bco($q20_numbco);
  $especie_doc = "ISSQN";
  $aceite = "N";
  $data_processamento = date("d-m-Y");
  $nosso_numero = $q20_numbco."-".digito_bco($q20_numbco);
  $codigo_cedente = "";
  $carteira = "SR";
  $especie = "REAL";
  $quantidade = "";
  $valor = "";
  $valor_documento = "R$".db_formatar($tot,'f');
  $instrucoes1 = "             DOCUMENTO VÁLIDO ATÉ O VENCIMENTO ";
  $instrucoes2 = "            APÓS PAGÁVEL SOMENTE NA PREFEITURA"; 
  $instrucoes3 = "    PAGUE SEUS TRIBUTOS EM DIA, EVITANDO JUROS E MULTA";
  $instrucoes4 = "                     CONTRIBUINTE!";
  $instrucoes5 = "Caso nao esteja exercendo atividade solicite a baixa do alvara,";
  $instrucoes6 = " no Protocolo da Prefeitura, evitando outros Lancamentos ";
  $instrucoes7 = "                 de ISSQN e DIVIDA ATIVA.";

  $desconto_abatimento = "";
  $outras_deducoes = "";
  $mora_multa = "";
  $outros_acrecimos = "";
  $valor_cobrado = "";

  $sacado1 = $z01_nome;
  $sacado2 = $z01_ender;
  $sacado3 = $z01_munic;

   
  $matri= array("1"=>"janeiro","2"=>"Fevereiro","3"=>"Março","4"=>"Abril","5"=>"Maio","6"=>"Junho","7"=>"Julho","8"=>"Agosto","9"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
  $mesx= $matri[$q20_mes];

  $pdf = new FPDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->image('imagens/files/logo_boleto.png',10,2,17);
  $pdf->setfont('arial','B',10);
  $coluna = 30;
  $pdf->text($coluna,5,$nomeinst);  
  $pdf->setfont('arial','',8);
  $pdf->text($coluna,9,$ender);  
  $pdf->text($coluna,12,$munic.' - '.$uf);  
  $pdf->text($coluna,15,$telef);  
  $pdf->text($coluna,18,$email);  
  $pdf->text($coluna,21,$url);  
  
  $clquery->sql_query("cgm","z01_nome","","z01_numcgm= $q20_numcgm");
  $clquery->sql_record($clquery->sql);
  db_fieldsmemory($clquery->result,0);
 
  $pdf->sety(25);
  $pdf->setfont('arial','B',10);
  $pdf->multicell(0,4,'ISSQN COM RETENÇÃO NA FONTE',0,"C",0); 
  $pdf->ln(5);
  $altura = 6;
  $pdf->setfont('arial','B',8);
  $pdf->cell(130,$altura,'TOMADOR DO SERVIÇO',1,0,"C",0);
  $pdf->cell(60,$altura,'DADOS DA PLANILHA',1,1,"C",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(130,$altura,'NOME : '.strtoupper($z01_nome),1,0,"L",0);
  $pdf->cell(60,$altura,'CODIGO : '.db_formatar($planilha,'s','0',4,'e'),1,1,"L",0);
  $pdf->cell(130,$altura,'INSCRIÇÃO : '.$q20_inscr,1,0,"L",0);
  $pdf->cell(60,$altura,'COMPETÊNCIA : '.db_formatar($q20_mes,'s','0',2,'e').'/'.$q20_ano,1,1,"L",0);
  $pdf->multicell(190,$altura,'OBSERVAÇÃO :  Os valores registrados nesta planilha somente serão considerados após o pagamento desta guia.',1,"L",0);  

  $linha = 65; 
  for($i=0;$i<2;$i++){


  $pdf->SetLineWidth(0.05);
  //$pdf->SetDash(1,1);
  $pdf->Line(5,$linha-2,205,$linha-2); // linha tracejada horizontal
  //$pdf->SetDash();
  
  $pdf->Line(47,$linha,47,$linha+9);
  $pdf->Line(63,$linha,63,$linha+9);
  $pdf->SetLineWidth(0.6);
  $pdf->Line(10,$linha+9,195,$linha+9);
  $pdf->SetLineWidth(0.2);

  $pdf->Line(10,$linha+17,195,$linha+17);
  $pdf->Line(10,$linha+25,195,$linha+25);
  $pdf->Line(10,$linha+33,195,$linha+33);
  $pdf->Line(10,$linha+41,195,$linha+41);
  $pdf->Line(149,$linha+49,195,$linha+49);
  $pdf->Line(149,$linha+57,195,$linha+57);
  $pdf->Line(149,$linha+65,195,$linha+65);
  $pdf->Line(149,$linha+73,195,$linha+73);
  $pdf->Line(10,$linha+81,195,$linha+81);

  $pdf->Line(149,$linha+9,149,$linha+81);
  $pdf->Line(169,$linha+9,169,$linha+17);

  $pdf->Line(40,$linha+25,40,$linha+33);
  $pdf->Line(86,$linha+25,86,$linha+33);
  $pdf->Line(112,$linha+25,112,$linha+33);
  $pdf->Line(125,$linha+25,125,$linha+33);

  $pdf->Line(45,$linha+33,45,$linha+41);
  $pdf->Line(65,$linha+33,65,$linha+41);
  $pdf->Line(91,$linha+33,91,$linha+41);
  $pdf->Line(121,$linha+33,121,$linha+41);

  $pdf->Line(10,$linha+93,195,$linha+93);
  //codigo de barras
  $pdf->SetFillColor(0,0,0);

  $pdf->int25(10,$linha+94,@$codigo_barras,20,0.341);
 
  // quadrado inferior //
  //$pdf->Image("logosantander.jpg",10,187,35,7);
  $pdf->SetFont('Arial','b',14);
  $pdf->Text(49,$linha+7,"104-8");
  $pdf->SetFont('Arial','b',11);
  $pdf->Text(70,$linha+7,@$linha_digitavel);
  $pdf->SetFont('Arial','b',5);
  $pdf->Text(13,$linha+11,"Local de Pagamento");
  $pdf->Text(151,$linha+11,"Parcela");
  $pdf->Text(171,$linha+11,"Vencimento");
  $pdf->Text(13,$linha+19,"Cedente");
  $pdf->Text(151,$linha+19,"Agência/Código Cedente");
  $pdf->Text(13,$linha+27,"Data do Documento");
  $pdf->Text(42,$linha+27,"Número do Documento");
  $pdf->Text(88,$linha+27,"Espécie Doc.");
  $pdf->Text(114,$linha+27,"Aceite");
  $pdf->Text(127,$linha+27,"Data do Processamento");
  $pdf->Text(151,$linha+27,"Nosso Número");
  $pdf->Text(13,$linha+35,"Código do Cedente");
  $pdf->Text(47,$linha+35,"Carteira");
  $pdf->Text(67,$linha+35,"Espécie");
  $pdf->Text(93,$linha+35,"Quantidade");
  $pdf->Text(123,$linha+35,"Valor");
  $pdf->Text(151,$linha+35,"( = ) Valor do Documento");
  $pdf->Text(13,$linha+43,"Instruções");
  $pdf->Text(151,$linha+43,"( - ) Desconto / Abatimento");
  $pdf->Text(151,$linha+51,"( - ) Outras Deduções");
  $pdf->Text(151,$linha+59,"( + ) Mora / Multa");
  $pdf->Text(151,$linha+67,"( + ) Outros Acrécimos");
  $pdf->Text(151,$linha+75,"( = ) Valor Cobrado");
  $pdf->Text(13,$linha+83,"Sacado");
  $pdf->Text(13,$linha+91,"Sacador/Avalista");
  $pdf->Text(160,$linha+99,"Autenticação Mecânica");

  $pdf->SetFont('Arial','b',8);
  $pdf->Text(13,$linha+15,@$localPagamento);
  $pdf->SetFont('Arial','',10);
  $pdf->Text(151,$linha+15,@$parcela);
  $pdf->Text(171,$linha+15,@$vencimento);
  $pdf->Text(13,$linha+23,@$cedente);
  $pdf->Text(151,$linha+23,@$agencia_codigo_cedente);
  $pdf->Text(13,$linha+31,@$data_documento);
  $pdf->Text(42,$linha+31,@$numero_documento);
  $pdf->Text(88,$linha+31,@$especie_doc);
  $pdf->Text(114,$linha+31,@$aceite);
  $pdf->Text(127,$linha+31,@$data_processamento);
  $pdf->Text(151,$linha+31,@$nosso_numero);
  $pdf->Text(13,$linha+39,@$codigo_cedente);
  $pdf->Text(47,$linha+39,@$carteira);
  $pdf->Text(67,$linha+39,@$especie);
  $pdf->Text(93,$linha+39,@$quantidade);
  $pdf->Text(123,$linha+39,@$valor);
  $pdf->Text(151,$linha+39,@$valor_documento);

  $pdf->Text(20,$linha+46,@$instrucoes1);
  $pdf->Text(20,$linha+50,@$instrucoes2);
  $pdf->Text(20,$linha+54,@$instrucoes3);
  $pdf->Text(20,$linha+60,@$instrucoes4);
  $pdf->Text(20,$linha+64,@$instrucoes5);
  $pdf->Text(20,$linha+68,@$instrucoes6);
  $pdf->Text(20,$linha+72,@$instrucoes7);

  $pdf->Text(151,$linha+47,@$desconto_abatimento);
  $pdf->Text(151,$linha+55,@$outras_deducoes);
  $pdf->Text(151,$linha+63,@$mora_multa);
  $pdf->Text(151,$linha+71,@$outros_acrecimos);
  $pdf->Text(151,$linha+79,@$valor_cobrado);

  $pdf->Text(29,$linha+85,@$sacado1);
  $pdf->Text(29,$linha+88,@$sacado2);
  $pdf->Text(29,$linha+91,@$sacado3);
				  

   $linha = 183; 

}


  $pdf->Output();

?>