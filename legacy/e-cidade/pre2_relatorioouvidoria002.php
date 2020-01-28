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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_db_ouvidoria_classe.php");

$cl_db_ouvidoria = new cl_db_ouvidoria();

$oGet = db_utils::postMemory($_GET);

$dataini = $oGet->dataini;
$datafim = $oGet->datafim;

$sCampos  = "po01_sequencial as id,        ";
$sCampos .= "po01_data       as data,      ";
$sCampos .= "w03_tipo        as categoria, "; 
$sCampos .= "po01_email      as email,     ";
$sCampos .= "po01_revisado   as revisado,  ";
$sCampos .= "nome           	as login     ";
$sqlOuvidorias = $cl_db_ouvidoria->sql_query(null, $sCampos);
if(!empty($dataini) && !empty($datafim)) {
  $sqlOuvidorias .= " WHERE po01_data BETWEEN '$dataini' AND '$datafim'";
} else if(!empty($dataini)) {
  $sqlOuvidorias .= " WHERE po01_data >= '$dataini'";
} else if(!empty($datafim)) {
  $sqlOuvidorias .= " WHERE po01_data <= '$datafim'";
}

$sqlOuvidorias .= " ORDER BY po01_sequencial DESC";
$rsOuvidorias = $cl_db_ouvidoria->sql_record($sqlOuvidorias); 
$ouvidorias = db_utils::getCollectionByRecord($rsOuvidorias,0);

$sqlCategorias = "SELECT w03_tipo FROM db_tipo";
$rsCategorias = db_query($sqlCategorias); 
$categorias = db_utils::getCollectionByRecord($rsCategorias,0);

$sqlTotalCategorias = "SELECT w03_codtipo, w03_tipo, COUNT(po01_sequencial)
                       FROM db_ouvidoria
                       	INNER JOIN db_tipo ON db_tipo.w03_codtipo = db_ouvidoria.po01_tipo";

if(!empty($dataini) && !empty($datafim)) {
  $sqlTotalCategorias .= " WHERE po01_data BETWEEN '$dataini' AND '$datafim'";
} else if(!empty($dataini)) {
  $sqlTotalCategorias .= " WHERE po01_data >= '$dataini'";
} else if(!empty($datafim)) {
  $sqlTotalCategorias .= " WHERE po01_data <= '$datafim'";
}
$sqlTotalCategorias .= " GROUP BY w03_codtipo, w03_tipo
										     UNION
										     SELECT 0 AS w03_codtipo, 'RESPONDIDAS' AS w03_tipo, 
										     				COUNT(po01_sequencial)
										     FROM db_ouvidoria
											 	 WHERE po01_revisado IS NOT NULL";
							  
if(!empty($dataini) && !empty($datafim)) {
  $sqlTotalCategorias .= " AND po01_data BETWEEN '$dataini' AND '$datafim'";
} else if(!empty($dataini)) {
  $sqlTotalCategorias .= " AND po01_data >= '$dataini'";
} else if(!empty($datafim)) {
  $sqlTotalCategorias .= " AND po01_data <= '$datafim'";
}
$sqlTotalCategorias .= " ORDER BY w03_codtipo";
$rsTotalCategorias = $cl_db_ouvidoria->sql_record($sqlTotalCategorias); 
$totalCategorias = db_utils::getCollectionByRecord($rsTotalCategorias);
$iOuvidorias = pg_num_rows($rsOuvidorias);
if ($iOuvidorias == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Consulta sem registros.');
}

$head1  = "Relatório de Ouvidorias";
if(empty($dataini) && empty($datafim)) {
  $dataini  = $ouvidorias[$iOuvidorias-1]->data;
  $datafim = $ouvidorias[0]->data;
} else if(!empty($dataini)) {
  $dataini = str_replace("/", "-", $dataini);
  $datafim = $ouvidorias[0]->data;
} else if(!empty($datafim)) {
  $dataini  = $ouvidorias[$iOuvidorias-1]->data;
  $datafim = str_replace("/", "-", $datafim);
}

$head2  = "Período: ".$dataini." A ".$datafim;
$head4  = "TOTAIS:";
$head5  = "OUVIDORIAS: ".$iOuvidorias;
foreach($totalCategorias as $totalCategoria) {
	$head5 .= ", ".$totalCategoria->w03_tipo.": ".$totalCategoria->count;
}

// Impressao da Pagina Principal do Formulario;
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$alt = 5;
$troca = true;
for($cont=0;$cont < $iOuvidorias;$cont++){
	db_fieldsmemory($rsOuvidorias,$cont);
	if ($pdf->gety() > $pdf->h - 30 || $troca ) {
		$pdf->addpage();
		
		$pdf->cell(8,$alt,"Num",1,0,"C",1);
		$pdf->cell(44,$alt,"E-mail",1,0,"C",1);
		$pdf->cell(36,$alt,"Categoria",1,0,"C",1);
		$pdf->cell(27,$alt,"Data da Solicitação",1,0,"C",1);
		$pdf->cell(25,$alt,"Data da Resposta",1,0,"C",1);
		$pdf->cell(50,$alt,"Respondido por",1,1,"C",1);
		 
		$troca = false;
	}

	$pdf->setfont('arial','',8);
	$pdf->cell(8,$alt, $id,0,0,"L",0);
	$pdf->cell(44,$alt, $email,0,0,"L",0);
	$pdf->cell(36,$alt, $categoria,0,0,"L",0);
	$pdf->cell(27,$alt, db_formatar($data, "d"),0,0,"L",0);
	$pdf->cell(25,$alt, db_formatar($revisado,"d"),0,0,"L",0);
	$pdf->cell(50,$alt, $login,0,1,"L",0);
}

$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS :  '.$iOuvidorias,1,0,"L",1);

$desArq = "Relatorio_Ouvidoria.pdf";
$pdf->Output($desArq,"I");
?>