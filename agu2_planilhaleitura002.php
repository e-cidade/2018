<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("libs/db_sql.php");
include("libs/db_utils.php");

$objGet  = db_utils::postmemory($_GET);

$NomeMes = db_mes($objGet->mesusu, 1);

$dbwhere1 = "";
$dbwhere2 = "";

if ($objGet->lista != "") {
  if (isset ($objGet->condicao) and $objGet->condicao == "com") {
    $dbwhere1 .= " and x07_codrota in  ($objGet->lista)";
    $dbwhere2 .= " and x01_rota    in  ($objGet->lista)";
  } else {
    $dbwhere1 .= " and x07_codrota not in  ($objGet->lista)";
    $dbwhere2 .= " and x01_rota    not in  ($objGet->lista)";
  }
}

if($objGet->filtro == '2') {
  require_once("classes/db_aguacoletorexportadados_classe.php");
  
  $clAguaColetorExportaDados = new cl_aguacoletorexportadados();
  
  $sSqlAguaColetorExportaDados = $clAguaColetorExportaDados->sql_query_dados(null, "DISTINCT x50_codlogradouro", null, "x49_situacao = 1 and x49_anousu = $objGet->anousu and x49_mesusu = $objGet->mesusu");
  
  $rsAguaColetorExportaDados = $clAguaColetorExportaDados->sql_record($sSqlAguaColetorExportaDados);
  
  $listaNotIn = "";
  $virgula    = "";
  for ($i = 0; $i < $clAguaColetorExportaDados->numrows; $i++) {
    
    $oAguaColetorExportaDados = db_utils::fieldsMemory($rsAguaColetorExportaDados, $i);
    
    $listaNotIn .= $virgula.$oAguaColetorExportaDados->x50_codlogradouro;
    $virgula     = ", ";
    
  }
  if($listaNotIn != '') {
    $dbwhere1 .= " and x01_codrua not in ($listaNotIn)";
    $dbwhere2 .= " and x01_codrua not in ($listaNotIn)";
  }
  
}

/***
 *
 * Rotina que Imprime a Planilha de Leituras
 *
 */

$rotulo = new rotulocampo();
$rotulo->label("x01_entrega");
$rotulo->label("x01_zona");
$rotulo->label("x07_codrota");
$rotulo->label("x04_matric");
$rotulo->label("z01_nome");
$rotulo->label("x04_nrohidro");
$rotulo->label("j88_sigla");
$rotulo->label("j14_nome");
$rotulo->label("x01_numero");
$rotulo->label("x01_orientacao");
$rotulo->label("x01_codrua");
$rotulo->label("x11_complemento");
$rotulo->label("x21_leitura");
$rotulo->label("x21_situacao");
$rotulo->label("j13_descr");

$sql = "
				select * 
				  from (
				        select x01_zona,
				               x07_codrota,
				               x06_descr,
				               x04_matric,
				               z01_nome,
				               x04_nrohidro,
				               x01_codrua,
				               j88_sigla,
				               j14_nome,
				               x01_numero,
				               x01_orientacao,
				               x11_complemento,
				               j13_descr,
				               0::float8  as x21_leitura,
				               0::integer as x21_situacao 
				          from aguahidromatric
				          left join  aguahidrotroca    on x28_codhidrometro = x04_codhidrometro
				         inner join aguabase           on x01_matric        = x04_matric
				          left join aguabasebaixa      on x08_matric        = x04_matric
				         inner join cgm                on z01_numcgm        = x01_numcgm
				          left join aguaconstr         on x11_matric        = x04_matric 
				                                      and x11_tipo          = 'P'
				         inner join ruas               on j14_codigo        = x01_codrua
				          left join ruastipo           on j88_codigo        = j14_tipo  
				         inner join bairro             on j13_codi          = x01_codbairro
				          left join aguarotarua        on x07_codrua        = x01_codrua
				          left join aguarota           on x06_codrota       = x07_codrota
				         where x28_codigo is null 
				           and x08_matric is null 
				           and x01_numero between x07_nroini and x07_nrofim
				              {$dbwhere1}
				
				         union 
				
				        select x01_zona,
				               coalesce(x06_codrota, 999999) as x07_codrota,
				               coalesce(x06_descr, 'SEM ROTA DEFINIDA') as x06_descr,
				               x04_matric,
				               z01_nome,
				               x04_nrohidro,
				               x01_codrua,
				               j88_sigla,
				               j14_nome,
				               x01_numero,
				               x01_orientacao,
				               x11_complemento,
				               j13_descr,
				               0::float8  as x21_leitura,
				               0::integer as x21_situacao 
				          from aguahidromatric
				          left join  aguahidrotroca on x28_codhidrometro = x04_codhidrometro
				         inner join aguabase        on x01_matric        = x04_matric
				          left join aguabasebaixa   on x08_matric        = x04_matric
				         inner join cgm             on z01_numcgm        = x01_numcgm
				          left join aguaconstr      on x11_matric        = x04_matric 
				                                   and x11_tipo          = 'P'
				         inner join ruas            on j14_codigo        = x01_codrua
				          left  join ruastipo       on j88_codigo        = j14_tipo 
				         inner join bairro          on j13_codi          = x01_codbairro
				          left  join aguarotarua    on x07_codrua        = x01_codrua
				          left  join aguarota       on x06_codrota       = x07_codrota
				         where x28_codigo  is null 
				           and x08_matric  is null 
				           and x07_codrota is null
				               {$dbwhere2}) as x
				 order by x07_codrota, x01_codrua, x01_orientacao, x01_numero
			 ";
            

$result 	= pg_exec($sql);
$numrows 	= pg_numrows($result);

if ($numrows == 0){
	db_redireciona('db_erros.php?fechar=true&db_erro=Nao existem itens cadastrados para fazer a consulta.');
}


$head2 = "LEITURA DE HIDROMETROS    Ref.: $NomeMes/$objGet->anousu";
$head4 = "";
$head8 = "";

if($objGet->tipodoc == 1) { // 1 = PDF 2 = CSV
	$pdf = new PDF(); 
	$pdf->Open(); 
	$pdf->AliasNbPages();
	$pdf->setfillcolor(235);
	$pdf->setfont('arial','b',8);
} else {
	$file = fopen("tmp/agu2_planilhaleitura002.csv", "w+");
	?>
	<html>
  <head>
  	<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  	<meta http-equiv="Expires" CONTENT="0">
  	<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.x01_matric.focus();" >
	<?	
}
		
$total = 0;

$alt 			= 7;
$total 		= 0;
$fator 		= 1.2;
$largSit 	= 52;
$inicio 	= true;
	
for($x=0; $x<$numrows; $x++) {
	db_fieldsmemory($result,$x);
	
	if($x01_orientacao == "S" || $x01_orientacao == "D" || $x01_orientacao == "E") {	
	  $orientacao = $x01_orientacao;
	}else{
		$orientacao = "";
	}
			
	$logradouro = "Logradouro: " . $x01_codrua . " - " . $j14_nome . " " . $orientacao;
	$orientacao2 = empty($x01_orientacao)?"":"/".$x01_orientacao;
	
	if ($objGet->tipodoc == 1) {
		if ($pdf->gety() > $pdf->h - 25 || $head4 != $logradouro ){
			$head4 = $logradouro;
			$head6 = "Zona: " . $x01_zona . "   Rota: " . $x07_codrota . " - " . $x06_descr;
	
	    if(!$inicio) {
			  $pdf->SetFont('courier','b',8);
	      $pdf->text(10,285,'DATA: ..../..../....                          ASSINATURA LEITURISTA: ________________________________________ ');
			} else {
				$inicio = false;
			}
	
			$pdf->addpage();
			$pdf->setfont('arial','b',8);
	
			$pdf->cell(0,$alt,"",0,1,"C",0);
			
			$pdf->cell($Mx04_matric*$fator      , $alt, $RLx04_matric,1,0,"C",1);
			$pdf->cell(($Mz01_nome+6)*$fator        , $alt, $RLz01_nome,1,0,"C",1);
			$pdf->cell(($Mx01_numero+6)*$fator      , $alt, substr($RLx01_numero,0,3),1,0,"C",1);
			$pdf->cell(($Mx11_complemento+8)*$fator , $alt, $RLx11_complemento,1,0,"C",1);
			$pdf->cell($Mx04_nrohidro*$fator    , $alt, "Hidrometro",1,0,"C",1);
			$pdf->cell($Mx04_matric*$fator      , $alt, $RLx04_matric,1,0,"C",1);
			$pdf->cell(($Mx21_leitura+4)*$fator , $alt, $RLx21_leitura,1,0,"C",1);
			$pdf->cell(($Mx21_situacao+4)*$fator    , $alt, substr($RLx21_situacao,0,3),1,1,"C",1);
		}
		$pdf->setfont('courier','',10);

		$fundo = 0;
	  
		$pdf->cell($Mx04_matric*$fator      , $alt, $x04_matric,1,0,"C",$fundo);
		$pdf->cell(($Mz01_nome+6)*$fator        , $alt, substr($z01_nome,0,26),1,0,"L",$fundo);
		$pdf->cell(($Mx01_numero+6)*$fator      , $alt, $x01_numero.$orientacao2,1,0,"R",$fundo);
	
		$pdf->setfont('courier','',9);
		$pdf->cell(($Mx11_complemento+8)*$fator , $alt, $x11_complemento,1,0,"C",$fundo);
		$pdf->setfont('courier','',9);
		
		$pdf->cell($Mx04_nrohidro*$fator    , $alt, $x04_nrohidro,1,0,"C",$fundo);
		$pdf->cell($Mx04_matric*$fator      , $alt, $x04_matric,1,0,"C",$fundo);
		$pdf->cell(($Mx21_leitura+4)*$fator , $alt, "",1,0,"C",$fundo);
		$pdf->cell(($Mx21_situacao+4)*$fator    , $alt, "",1,1,"C",$fundo);
	
	} else {
		$z01_nome 		= addslashes($z01_nome);
		$x04_nrohidro = addslashes($x04_nrohidro);
		fwrite($file, "\"{$objGet->anousu}\",\"{$objGet->mesusu}\",\"{$x04_matric}\",\"{$z01_nome}\",\"{$x01_numero}{$letra2}\",\"{$x11_complemento}\",\"{$x04_nrohidro}\",\"{$x04_matric}\"\n");
	}		

	$total++;
}
	
if($objGet->tipodoc == 1) {
  $pdf->SetFont('courier','b',8);
  $pdf->text(10,285,'DATA: ..../..../....                          ASSINATURA LEITURISTA: ________________________________________ ');
	$pdf->Output();	
}	else {
	fclose($file);
	$nomearqdados = 'tmp/agu2_planilhaleitura002.csv';
	
	echo "<script>";
  echo "  listagem = '$nomearqdados#Download arquivo CSV (planilha de leitura)|';";
  echo "  parent.js_montarlista(listagem,'form1');";
  echo "</script>";
	
}

?>