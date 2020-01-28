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

include("libs/db_sql.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
if ($tipo == 1) {
   include("fpdf151/scpdf.php");
} else {
   include("fpdf151/pdf.php");
}
if ( $d40_codigo == null ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=C�digo da lista nao preenchido!');
}

if ($tipo == 1) {
   $pdf = new scpdf("L");
   $largura = 10;
} else {
   $pdf = new pdf("L");
   $largura = 6;
}

$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();

$result = pg_exec("select munic
                   from db_config 
   	 	   where codigo = ".db_getsession('DB_instit'));
db_fieldsmemory($result,0);
$sql="select ruas.j14_nome, j88_descricao as j14_tipo, z01_nome, z01_ender, cgm.z01_telef, d40_trecho from projmelhorias inner join ruas on ruas.j14_codigo = projmelhorias.d40_codlog inner join ruastipo on j88_codigo = j14_tipo left outer join projmelhoriasresp on projmelhoriasresp.d42_codigo = projmelhorias.d40_codigo left outer join cgm on cgm.z01_numcgm = projmelhoriasresp.d42_numcgm where d40_codigo = $d40_codigo";
$result = pg_exec($sql) or die($sql);
db_fieldsmemory($result,0);
if ( pg_numrows($result) == 0 ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Lista nao cadastrada!');
}

$pdf->SetFont('Arial','',8);

$posicao_rodape = $pdf->h - ($tipo == 1?10:15);
$pdf->text(10,$posicao_rodape,($tipo == 1?'P�gina: '.$pdf->PageNo().'/{nb} -':'').'* refere-se respectivamente �s informa��es: zona fiscal, setor, quadra, lote e sublote. ** refere-se � parte da cal�ada at� a metade da esquina. C�digo da lista '.$d40_codigo.'.');

if ($tipo == 1) {
   $pdf->SetFont('Arial','B',20);
   $pdf->MultiCell(0,0,"PROGRAMA DE PAVIMENTA��O SOLID�RIA",0,"C",0,0);
   $pdf->SetFont('Arial','',12);
   $pdf->Ln(10);
   $pdf->MultiCell(0,0,$munic.', _____ de _______________ de 200___. ',0,0,"R",0);
   $pdf->Ln(5);
   $pdf->Cell(40,5,'',0,0,"L",0);
   $pdf->Cell(65,5,'Senhor Prefeito:',0,0,"L",0);
   $pdf->Ln(5);
   $pdf->MultiCell(0,5,'N�s abaixo assinados e identificados, vimos a presen�a de Vossa Excel�ncia, manifestar nosso desejo de ades�o ao Programa de Pavimenta��o Solid�ria, implantado pela Lei Municipal 2526/99, lei municipal 4133/07 que altera a lei 2526/99',0,"J",0,40);
   $pdf->MultiCell(0,5,'Requeremos, pois, com base no disposto no artigo 2o. I da mencionada Lei, a devida autoriza��o municipal, para contratar, diretamente, uma empresa pavimentadora, para executar as obras de pavimenta��o da rua '.$j14_nome.($d40_trecho==null?'':' ('.$d40_trecho.')') . ', com pedra irregular, responsabilizando-nos pelo pagamento, diretamente � empresa, dos servi�os prestados, sem qualquer responsabiliza��o do Munic�pio.',0,"J",0,40);
   $pdf->MultiCell(0,5,'Requeremos outrossim, a participa��o do Munic�pio, atrav�s da elabora��o do Projeto de engenharia, com planilha orcament�ria, a participa��o financeira na obra, na propor��o de 50% sobre o valor da planilha, na forma nesta constante, bem como a fiscaliza��o e o recebimento da obra.',0,"J",0,40);
   $pdf->Ln(2);
   $pdf->Cell(40,5,'',0,0,"L",0);
   $pdf->Cell(65,5,'Representante dos propriet�rios: ',0,0,"L",0);
   $pdf->SetFont('Arial','U',8);
   $pdf->Cell(40,5,($z01_nome==null?str_pad(' ' ,150):$z01_nome),0,1,"L",0);
   $pdf->Ln(2);

   $pdf->SetFont('Arial','',12);
   $pdf->Cell(40,5,'',0,0,"L",0);
   $pdf->Cell(65,5,'Endere�o para contato:',0,0,"L",0);
   $pdf->SetFont('Arial','U',8);
   $pdf->Cell(40,5,($z01_ender==null?str_pad(' ' ,150):$z01_ender),0,1,"L",0);
   $pdf->Ln(2);

   $pdf->SetFont('Arial','',12);
   $pdf->Cell(40,5,'',0,0,"L",0);
   $pdf->Cell(65,5,'Fone:',0,0,"L",0);
   $pdf->SetFont('Arial','U',8);
   $pdf->Cell(40,5,($z01_telef==null?str_pad(' ' ,150):$z01_telef),0,1,"L",0);
   $pdf->Ln(2);

   $pdf->SetFont('Arial','',12);
   $pdf->Cell(40,5,'',0,0,"L",0);
   $pdf->Cell(65,5,'Assinatura:',0,0,"L",0);
   $pdf->SetFont('Arial','U',8);
   $pdf->Cell(40,5,str_pad(' ',150),0,1,"L",0);
   $pdf->Ln(2);
}

$pdf->SetFont('Arial','',8);
$pdf->SetFillColor(235);

$sql="select distinct proprietario, j39_numero, z01_nome, j01_matric, j40_refant, d41_testada, d41_eixo, d41_obs, d41_pgtopref from projmelhoriasmatric inner join proprietario on proprietario.j01_matric = projmelhoriasmatric.d41_matric where d41_codigo = $d40_codigo order by j40_refant";
$result = pg_exec($sql);
if ( pg_numrows($result) == 0 ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Lista nao cadastrada!');
}

$pdf->SetFont('Arial','B',20);
$pdf->Cell(($tipo == 1?277:197),10,$j14_tipo . ' ' . $j14_nome,1,1,"C",1);
$pdf->SetFont('Arial','',8);
$pdf->Cell(70,$largura,'PROPRIET�RIO',1,0,"C",1);
$pdf->Cell(20,$largura,'NUMERO',1,0,"C",1);
$pdf->Cell(15,$largura,'MATRIC',1,0,"C",1);
$pdf->Cell(25,$largura,'REFER *',1,0,"C",1);
$pdf->Cell(14,$largura,'TESTADA',1,0,"C",1);
$pdf->Cell(14,$largura,'EIXO **',1,0,"C",1);
if ($tipo == 2) {
  $pdf->Cell(25,$largura,'FORMA PGTO',1,0,"C",1);
}
$pdf->Cell(14,$largura,'TOTAL',1,($tipo == 1?0:1),"C",1);
if ($tipo == 1) {
   $pdf->Cell(105,$largura,'ASSINATURA',1,1,"C",1);
}

$quant_reg=0;

for($s=0;$s<pg_numrows($result);$s++){

  if ($pdf->gety() > ($pdf->h-40)) {
      $pdf->AddPage();
      $pdf->text(10,$posicao_rodape,($tipo == 1?'P�gina: '.$pdf->PageNo().'/{nb} -':'').'* refere-se respectivamente �s informa��es: zona fiscal, setor, quadra, lote e sublote. ** refere-se � parte da cal�ada at� a metade da esquina. C�digo da lista '.$d40_codigo.'.');
      $pdf->SetFont('Arial','B',20);
      $pdf->Cell(($tipo == 1?277:197),10,$j14_tipo . ' ' . $j14_nome,1,1,"C",1);
      $pdf->SetFont('Arial','',8);
      $pdf->Cell(70,$largura,'PROPRIET�RIO',1,0,"C",1);
      $pdf->Cell(20,$largura,'NUMERO',1,0,"C",1);
      $pdf->Cell(15,$largura,'MATRIC',1,0,"C",1);
      $pdf->Cell(25,$largura,'REFER *',1,0,"C",1);
      $pdf->Cell(14,$largura,'TESTADA',1,0,"C",1);
      $pdf->Cell(14,$largura,'EIXO **',1,0,"C",1);
      if ($tipo == 2) {
        $pdf->Cell(25,$largura,'FORMA PGTO',1,0,"C",1);
      }
      $pdf->Cell(14,$largura,'TOTAL',1,($tipo == 1?0:1),"C",1);
      if ($tipo == 1) {
	 $pdf->Cell(105,$largura,'ASSINATURA',1,1,"C",1);
      }
  }

  db_fieldsmemory($result,$s);
  $pdf->Cell(70,$largura,$proprietario,1,0,"L",0);
  $pdf->Cell(20,$largura,$j39_numero,1,0,"L",0);
  $pdf->Cell(15,$largura,$j01_matric,1,0,"L",0);
  $pdf->Cell(25,$largura,$j40_refant,1,0,"L",0);
  $pdf->Cell(14,$largura,db_formatar($d41_testada,'f',' ',10),1,0,"L",0);
  $pdf->Cell(14,$largura,db_formatar($d41_eixo,'f',' ',10),1,0,"L",0);
  if ($tipo == 2) {
    $pdf->Cell(25,$largura,($d41_pgtopref == 't'?"PREFEITURA":"EMPREITEIRO"),1,0,"L",0);
  }
  $pdf->Cell(14,$largura,db_formatar($d41_testada+$d41_eixo,'f',' ',10),1,($tipo == 1?0:1),"L",0);
  if ($tipo == 1) {
     $pdf->Cell(105,$largura,'',1,1,"L",0);
  }
  $quant_reg++;
  
}
$pdf->ln(3);

$pdf->Cell(70,$largura,"TOTAL DE REGISTROS: $quant_reg",0,0,"L",0);

$pdf->Output();
?>