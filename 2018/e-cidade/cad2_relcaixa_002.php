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

  ///////////////////////////////////////////////////////////////////////
  if ($opcaoRelatorio == "tabrec"){
    $sql = "
  	        select k02_codigo,k02_tipo,k02_descr,k02_drecei,k02_codjm 
	        from tabrec
	       ";
    $head4 = "Relatórios das Receitas";
	if ($opcaoOrdem == "alfabetica") {
	  $head5 = "Ordem alfabetica";
	  $sql .= "
	  order by j14_nome
	  ";
	} else if ($opcaoOrdem == "numerica") {
	  $head5 = "Ordem Numérica";
	  $sql .= "
	  order by j14_codigo
	  ";
    }
    $pdf = new PDF();
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',9);
    $pdf->setX(5);
    $pdf->Cell(30,4,"Codigo","LRBT",0,"C",0);
    $pdf->Cell(1,4,"Tipo","LRBT",0,"C",0);
    $pdf->Cell(20,4,"Resumida","LRBT",1,"C",0);
    $pdf->Cell(40,4,"Descrição","LRBT",1,"C",0);
    $pdf->Cell(5,4,"Cod.Juros","LRBT",1,"C",0);

    $result = pg_exec($sql);
    $num = pg_numrows($result);
    $pdf->SetFont('Arial','B',9);
	for ($i=0;$i<$num;$i++) {
      $pdf->setX(5);
      $pdf->Cell(30,4,pg_result($result,$i,"k02_codigo"),"B",0,"C",0);
      $pdf->Cell(1,4,trim(pg_result($result,$i,"k02_tipo")),"B",0,"L",0);
      $pdf->Cell(20,4,pg_result($result,$i,"k02_descr"),"B",1,"C",0);
      $pdf->Cell(40,4,pg_result($result,$i,"k02_drecei"),"B",1,"C",0);
      $pdf->Cell(5,4,pg_result($result,$i,"k02_codjm"),"B",1,"C",0);
	}
  ///////////////////////////////////////////////////////////////////////
  } else if ($opcaoRelatorio == "bairros"){
    $sql = "
	select j13_codi, j13_descr, j13_codant 
	from bairro 
	";
    if ($opcaoOrdem == "alfabetica") {
	  $head5 = "Relatório de bairros em ordem alfabetica";
	  $sql .= "
	  order by j13_descr
	  ";
      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $pdf->setX(5);
      $pdf->Cell(140,4,"Nome do bairro","LRBT",0,"C",0);
      $pdf->Cell(30,4,"Codigo","LRBT",0,"C",0);
      $pdf->Cell(30,4,"Codigo Anterior","LRBT",1,"C",0);
      $result = pg_exec($sql);
      $num = pg_numrows($result);
      $pdf->SetFont('Arial','B',9);
	  for ($i=0;$i<$num;$i++) {
        $pdf->setX(5);
        $pdf->Cell(140,4,trim(pg_result($result,$i,"j13_descr")),"B",0,"L",0);
        $pdf->Cell(30,4,pg_result($result,$i,"j13_codi"),"B",0,"C",0);
        $pdf->Cell(30,4,pg_result($result,$i,"j13_codant"),"B",1,"C",0);
	  }
	} else if ($opcaoOrdem == "numerica") {
	  $head6 = "Relatório de bairros em ordem numérica";
	  $sql .= "
	  order by j13_codi
	  ";
      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $pdf->setX(5);
      $pdf->Cell(30,4,"Codigo","LRBT",0,"C",0);
      $pdf->Cell(140,4,"Nome do bairro","LRBT",0,"C",0);
      $pdf->Cell(30,4,"Codigo Anterior","LRBT",1,"C",0);
      $result = pg_exec($sql);
      $num = pg_numrows($result);
      $pdf->SetFont('Arial','B',9);
	  for ($i=0;$i<$num;$i++) {
        $pdf->setX(5);
        $pdf->Cell(30,4,pg_result($result,$i,"j13_codi"),"B",0,"C",0);
        $pdf->Cell(140,4,trim(pg_result($result,$i,"j13_descr")),"B",0,"L",0);
        $pdf->Cell(30,4,pg_result($result,$i,"j13_codant"),"B",1,"C",0);
	  }
	}
  ///////////////////////////////////////////////////////////////////////
  } else if ($opcaoRelatorio == "setor"){
    $sql = "
	select j30_codi, j30_descr, j30_alipre, j30_aliter 
	from setor 
	";
    if ($opcaoOrdem == "alfabetica") {
	  $head5 = "Relatório de setor em ordem alfabetica";
	  $sql .= "
	  order by j30_descr
	  ";
      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $pdf->setX(5);
      $pdf->Cell(140,4,"Setor","LRBT",0,"C",0);
      $pdf->Cell(20,4,"Codigo","LRBT",0,"C",0);
      $pdf->Cell(20,4,"j30_alipre","LRBT",0,"C",0);
      $pdf->Cell(20,4,"j30_aliter","LRBT",1,"C",0);
      $result = pg_exec($sql);
      $num = pg_numrows($result);
      $pdf->SetFont('Arial','B',9);
	  for ($i=0;$i<$num;$i++) {
        $pdf->setX(5);
        $pdf->Cell(140,4,trim(pg_result($result,$i,"j30_descr")),"B",0,"L",0);
        $pdf->Cell(20,4,pg_result($result,$i,"j30_codi"),"B",0,"C",0);
        $pdf->Cell(20,4,pg_result($result,$i,"j30_alipre"),"B",0,"C",0);
        $pdf->Cell(20,4,pg_result($result,$i,"j30_aliter"),"B",1,"C",0);
	  }
	} else if ($opcaoOrdem == "numerica") {
	  $head5 = "Relatório de setor em ordem numérica";
	  $sql .= "
	  order by j30_codi
	  ";
      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $pdf->setX(5);
      $pdf->Cell(20,4,"Codigo","LRBT",0,"C",0);
      $pdf->Cell(140,4,"Setor","LRBT",0,"C",0);
      $pdf->Cell(20,4,"j30_alipre","LRBT",0,"C",0);
      $pdf->Cell(20,4,"j30_aliter","LRBT",1,"C",0);
      $result = pg_exec($sql);
      $num = pg_numrows($result);
      $pdf->SetFont('Arial','B',9);
	  for ($i=0;$i<$num;$i++) {
        $pdf->setX(5);
        $pdf->Cell(20,4,pg_result($result,$i,"j30_codi"),"B",0,"C",0);
        $pdf->Cell(140,4,trim(pg_result($result,$i,"j30_descr")),"B",0,"L",0);
        $pdf->Cell(20,4,pg_result($result,$i,"j30_alipre"),"B",0,"C",0);
        $pdf->Cell(20,4,pg_result($result,$i,"j30_aliter"),"B",1,"C",0);
	  }
	}
  ///////////////////////////////////////////////////////////////////////
  } else if ($opcaoRelatorio == "grupoCaracteristica"){
    $sql = "
	select j32_grupo, j32_descr, j32_tipo 
	from cargrup 
	";
    if ($opcaoOrdem == "alfabetica") {
	  $head5 = "Relatório de Grupo Característica em ordem alfabetica";
	  $sql .= "
	  order by j32_descr
	  ";
      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $pdf->setX(5);
      $pdf->Cell(140,4,"Característica","LRBT",0,"C",0);
      $pdf->Cell(20,4,"Grupo","LRBT",0,"C",0);
      $pdf->Cell(20,4,"Tipo","LRBT",1,"C",0);
      $result = pg_exec($sql);
      $num = pg_numrows($result);
      $pdf->SetFont('Arial','B',9);
	  for ($i=0;$i<$num;$i++) {
        $pdf->setX(5);
        $pdf->Cell(140,4,trim(pg_result($result,$i,"j32_descr")),"B",0,"L",0);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j32_grupo")),"B",0,"C",0);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j32_tipo")),"B",1,"C",0);
	  }
	} else if ($opcaoOrdem == "numerica") {
	  $head5 = "Relatório de Grupo Característica em ordem numérica";
	  $sql .= "
	  order by j32_grupo
	  ";
      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $pdf->setX(5);
      $pdf->Cell(20,4,"Grupo","LRBT",0,"C",0);
      $pdf->Cell(140,4,"Característica","LRBT",0,"C",0);
      $pdf->Cell(20,4,"Tipo","LRBT",1,"C",0);
      $result = pg_exec($sql);
      $num = pg_numrows($result);
      $pdf->SetFont('Arial','B',9);
	  for ($i=0;$i<$num;$i++) {
        $pdf->setX(5);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j32_grupo")),"B",0,"C",0);
        $pdf->Cell(140,4,trim(pg_result($result,$i,"j32_descr")),"B",0,"L",0);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j32_tipo")),"B",1,"C",0);
	  }
	}
  ///////////////////////////////////////////////////////////////////////
  } else if ($opcaoRelatorio == "caracteristica"){
    $sql = "
	select j31_codigo, j31_descr, j31_grupo, j31_pontos 
	from caracter 
	";
    if ($opcaoOrdem == "alfabetica") {
	  $head5 = "Relatório de Característica em ordem alfabetica";
	  $sql .= "
	  order by j31_descr
	  ";
      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $pdf->setX(5);
      $pdf->Cell(120,4,"Característica","LRBT",0,"C",0);
      $pdf->Cell(20,4,"Grupo","LRBT",0,"C",0);
      $pdf->Cell(20,4,"Código","LRBT",0,"C",0);
      $pdf->Cell(20,4,"j31_pontos","LRBT",1,"C",0);
      $result = pg_exec($sql);
      $num = pg_numrows($result);
      $pdf->SetFont('Arial','B',9);
	  for ($i=0;$i<$num;$i++) {
        $pdf->setX(5);
        $pdf->Cell(120,4,trim(pg_result($result,$i,"j31_descr")),"B",0,"L",0);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j31_grupo")),"B",0,"C",0);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j31_codigo")),"B",0,"C",0);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j31_pontos")),"B",1,"C",0);
	  }
	} else if ($opcaoOrdem == "numerica") {
	  $head5 = "Relatório de Característica em ordem numérica";
	  $sql .= "
	  order by j31_codigo
	  ";
      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $pdf->setX(5);
      $pdf->Cell(20,4,"Código","LRBT",0,"C",0);
      $pdf->Cell(120,4,"Característica","LRBT",0,"C",0);
      $pdf->Cell(20,4,"Grupo","LRBT",0,"C",0);
      $pdf->Cell(20,4,"j31_pontos","LRBT",1,"C",0);
      $result = pg_exec($sql);
      $num = pg_numrows($result);
      $pdf->SetFont('Arial','B',9);
	  for ($i=0;$i<$num;$i++) {
        $pdf->setX(5);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j31_codigo")),"B",0,"C",0);
        $pdf->Cell(120,4,trim(pg_result($result,$i,"j31_descr")),"B",0,"L",0);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j31_grupo")),"B",0,"C",0);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j31_pontos")),"B",1,"C",0);
	  }
	} else if ($opcaoOrdem == "grupo") {
	  $head5 = "Relatório de Característica em ordem de grupo";
	  $sql .= "
	  order by j31_grupo
	  ";
      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',9);
      $pdf->setX(5);
      $pdf->Cell(20,4,"Grupo","LRBT",0,"C",0);
      $pdf->Cell(120,4,"Característica","LRBT",0,"C",0);
      $pdf->Cell(20,4,"Código","LRBT",0,"C",0);
      $pdf->Cell(20,4,"j31_pontos","LRBT",1,"C",0);
      $result = pg_exec($sql);
      $num = pg_numrows($result);
      $pdf->SetFont('Arial','B',9);
	  for ($i=0;$i<$num;$i++) {
        $pdf->setX(5);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j31_grupo")),"B",0,"C",0);
        $pdf->Cell(120,4,trim(pg_result($result,$i,"j31_descr")),"B",0,"L",0);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j31_codigo")),"B",0,"C",0);
        $pdf->Cell(20,4,trim(pg_result($result,$i,"j31_pontos")),"B",1,"C",0);
	  }
	}
  }
  $pdf->Output();
?>
<?
/*


$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();
  // $parametro recebe cod_matricula
  echo $sql = "select proprietario.* , c.z01_nome as promitente, c.z01_ender as ender_promitente,
                                  j.z01_nome as imobiliaria,j.z01_ender as ender_imobiliaria
                                  from proprietario
                                  left outer join cgm c on j41_numcgm = c.z01_numcgm              
                                  left outer join cgm j on j44_numcgm = j.z01_numcgm              
 			                      where j01_matric = $parametro limit 1";
  $matriculaSelecionada = pg_exec($sql);
  $numMatriculaSelecionada = pg_numrows($matriculaSelecionada);
  if ($numMatriculaSelecionada == 0) {

    $pdf->SetFont('Arial','B',9);
    $pdf->setX(5);
    $pdf->Cell(200,4,"Matrícula Inexistente","LRBT",1,"C",0);
  } else {
    db_fieldsmemory($matriculaSelecionada,0);
    $pdf->SetFont('Arial','B',9);

    $pdf->setX(5);
    $pdf->Cell(200,4,"DADOS CADASTRAIS DO IMÓVEL","LRBT",1,"C",0);
    $pdf->setX(5);
    $pdf->Cell(200,4,"","",1,"C",0);

    $pdf->setX(5);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,4,"Matrícula :","",0,"L",0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(80,4,"$j01_matric","",0,"L",0);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(30,4,"Referência Anterior:","",0,"L",0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(70,4,"$j40_refant","",1,"L",0);

    $pdf->setX(5);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,4,"Proprietário :","",0,"L",0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(80,4,"$z01_nome","",0,"L",0);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,4,"Endereço :","",0,"L",0);
    $pdf->SetFont('Arial','B',9);
	$ender = substr($z01_ender,0,30);
    $pdf->Cell(70,4,"$ender","",1,"L",0);

    $pdf->setX(5);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,4,"Promitente :","",0,"L",0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(80,4,"$promitente","",0,"L",0);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,4,"Endereço :","",0,"L",0);
    $pdf->SetFont('Arial','B',9);
	$ender = substr($ender_promitente,0,30);
    $pdf->Cell(70,4,"$ender","",1,"L",0);

*/
 ?>