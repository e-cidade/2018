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

if (!isset($arqinclude)){
  
  include("fpdf151/pdf.php");
  include("fpdf151/assinatura.php");
  include("libs/db_sql.php");
  include("libs/db_utils.php");
  include("dbforms/db_funcoes.php");
  include("classes/db_db_config_classe.php");
	include("model/linhaRelatorioContabil.model.php");
  include ("model/relatorioContabil.model.php");

  $oRelatorio    = new relatorioContabil(71);
  $classinatura  = new cl_assinatura;
  $cldbconfig    = new cl_db_config;
  db_postmemory($HTTP_SERVER_VARS);
  
}
$oGet  = db_utils::postMemory($_GET);
// retorna o nome do ente da federação que está acessando o DBPortal
$sSqlConfig = $cldbconfig->sql_query_file(db_getsession("DB_instit"), "munic");
$rsRecord   = $cldbconfig->sql_record($sSqlConfig);
$oConfig    = db_utils::fieldsMemory($rsRecord,0);

$anoReferencia = db_getsession("DB_anousu") + 1;
/*
 * validação da opção ldo ou loa, para imprimir no head3.
 */
if ($oGet->sModelo == 'ldo') {
  $sModelo = 'LEI DE DIRETRIZES ORÇAMENTÁRIAS';
} else {
  $sModelo = 'LEI ORÇAMENTÁRIA ANUAL';
}
$head2 = "MUNICÍPIO DE ".$oConfig->munic;
$head3 = $sModelo;
$head4 = "ANEXO DE METAS FISCAIS";
$head5 = $anoReferencia;
$head6 = "MARGEM DE EXPANSÃO DAS DESPESAS OBRIGATÓRIAS DE CARÁTER CONTINUADO";

// exibe a estrutura da array do relatório (usado para debugar)
/*
echo "<pre>";
print_r($oRelatorio->getLinhas());
echo "</pre>";
exit;
*/

  $aRelatorio = $oRelatorio->getLinhas();
	
	// soma todas as variáveis das contas do somatório
	$aSomatorioLinhas = array();

	for ($i=1;$i<7;$i++) {
		
		// armazena o label do relatório no array
		$aLinhasRelatorio[$i]["label"] = $aRelatorio[$i]->o69_labelrel;
    $aSomatorioLinhas[$i] = null;

    // faz a soma das linhas com os seus respectivos valores
		foreach ($aRelatorio[$i]->valoresVariaveis as $oValores) {
		  $aSomatorioLinhas[$i] += $oValores->colunas[0]->o117_valor;
		}

	}

  $pdf = new PDF("P", "mm", "A4"); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $alt            = 4;
  $pagina         = 1;
  $pdf->addpage();
  $pdf->setfont('arial','',7);
  $sFundamentacao = "AMF -Demonstrativo 8 (LRF, art. 4º,".chr(167)." 2º, inciso V)";
  $pdf->cell(165, $alt, $sFundamentacao,'B',0,"L",0);
  $pdf->cell(25,$alt,'R$ 1,00','B',1,"R",0);

  $pdf->cell(95,$alt,"EVENTOS",'B',0,"C",0);
  $pdf->cell(95,$alt,"Valor Previsto para {$anoReferencia}",'LB',1,"C",0);
   
  for ($i=1;$i<4;$i++) {
		$pdf->cell(95,$alt,$aLinhasRelatorio[$i]["label"],'R',0,"L",0);
  	$pdf->setx(105);
	  $pdf->cell(95,$alt,db_formatar($aSomatorioLinhas[$i],'f'),'L',1,"R",0);			
	}

  $pdf->cell(95,$alt,"Saldo Final do Aumento Permanente de Receita (I)",'RBT',0,"L",0);

	// cálculo linha 4
	// somatório linha 2 + somatorio linha 3 - somatorio linha 1
	$calcLinha4 =	$aSomatorioLinhas[1] - ($aSomatorioLinhas[2] + $aSomatorioLinhas[3]);

  $pdf->cell(95,$alt,db_formatar($calcLinha4,'f'),'BT',1,"R",0);
  $pdf->cell(95,$alt,$aLinhasRelatorio[4]["label"],'BR',0,"L",0);
  $pdf->cell(95,$alt,db_formatar($aSomatorioLinhas[4],'f'),'B',1,"R",0);
  
	// cálculo linha 6
	// somatório linha 4 + resultado do cálculo da linha 4
	$calcLinha6 = $aSomatorioLinhas[4] + $calcLinha4;

  $pdf->cell(95,$alt,"Margem Bruta de Despesa (III) = (I+II)",'RBT',0,"L",0);
  $pdf->cell(95,$alt,db_formatar($calcLinha6,'f'),'BT',1,"R",0);
  
	// cálculo linha 7
	// somatório linha 5 + somatório linha 6
	$calcLinha7 = $aSomatorioLinhas[5] + $aSomatorioLinhas[6];

  $pdf->cell(95,$alt,"Saldo Utilizado da Margem Bruta (IV)",'RT',0,"L",0);
  $pdf->cell(95,$alt,db_formatar($calcLinha7,'f'),'T',1,"R",0);
  
  for ($i=5;$i<7;$i++) {
		$pdf->cell(95,$alt,$aLinhasRelatorio[$i]["label"],'R',0,"L",0);
		$pdf->setx(105);
	  $pdf->cell(95,$alt,db_formatar($aSomatorioLinhas[$i],'f'),'L',1,"R",0);		
  }
  
	// cálculo linha 10
	// cálculo finais da linha 6 - linha 7
	$calcLinha10 = $calcLinha6 - $calcLinha7;
  $pdf->cell(95,$alt,"Margem Líquida de Expansão de DOCC (V) = (III-IV)",'RBT',0,"L",0);
  $pdf->cell(95,$alt,db_formatar($calcLinha10,'f'),'BT',1,"R",0);
	$pdf->ln();
	$oRelatorio->getNotaExplicativa($pdf,1);
  $pdf->Output();

?>