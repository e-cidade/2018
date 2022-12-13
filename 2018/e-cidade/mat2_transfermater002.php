<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require("fpdf151/scpdf.php");
include("fpdf151/impcarne.php");
include("libs/db_sql.php");
include("classes/db_matestoqueini_classe.php");
include("classes/db_matestoqueinimei_classe.php");
include("classes/db_matrequi_classe.php");
include("classes/db_matrequiitem_classe.php");
include("classes/db_db_almox_classe.php");
$clmatestoqueini = new cl_matestoqueini;
$clmatestoqueinimei = new cl_matestoqueinimei;
$clmatrequi = new cl_matrequi;
$clmatrequiitem = new cl_matrequiitem;
$oAlmox = new cl_db_almox();

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$sqlpref = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = db_query($sqlpref);
db_fieldsmemory($resultpref,0);

//$where_matestoqueini = " transfere.m80_codtipo = 7 and m86_matestoqueini is null";
$where_matestoqueini = " transfere.m80_codtipo = 7 ";
if (isset($ini) && trim($ini)!="") {
	
	if (isset($fim) && trim($fim)!="") {
		
		$where_matestoqueini = " transfere.m80_codigo between $ini and $fim";
	} else {
		
		$where_matestoqueini = " transfere.m80_codigo between $ini and $ini";
	}
}

/* periodos */
if(isset($dataini) && $dataini != "") {
	
	$dataini = explode('/',$dataini);
	$dataini = $dataini[2].'-'.$dataini[1].'-'.$dataini[0];
	$datafim = explode('/',$datafim);
	$datafim = $datafim[2].'-'.$datafim[1].'-'.$datafim[0];
	
	
	if($tipo_periodo == 1) {
	  $where_matestoqueini .= " AND transfere.m80_data BETWEEN '".$dataini."' AND '".($datafim)."' ";  
	  
	} else if($tipo_periodo == 2) {
	    $where_matestoqueini .= " AND recebe.m80_data BETWEEN '".$dataini."' AND '".($datafim)."' ";   
	}	
}

/* almoxarifados */
if(isset($almox) && $almox != "") {
	
	//BUSCA O CODIGO DO DEPARTAMENTO PELO CODIGO DOS ALMOXARIFADOS //
	$sSql    = $oAlmox->sql_query(NULL, "m91_depto", "", "m91_codigo IN ( ".$almox." ) ");
	$rsAlmox = $oAlmox->sql_record($sSql);
  $sAlmox  = "";	
  while($r = pg_fetch_array($rsAlmox)) {
  	$sAlmox .= $r['m91_depto'].",";  	
	}	
	$sAlmox = substr($sAlmox, 0, -1);
  $where_matestoqueini .= " AND matestoquetransf.m83_coddepto IN ( ".$sAlmox." ) ";
}

$campos  = "    distinct(transfere.m80_codigo),transfere.m80_codtipo,  ";
$campos .= "    transfere.m80_data,              ";
$campos .= "    transfere.m80_coddepto,          ";
$campos .= "    b.descrdepto,                    ";
$campos .= "    b.coddepto,                      ";
$campos .= "    transfere.m80_hora,              ";
$campos .= "    transfere.m80_obs,               ";
$campos .= "    nome,                            ";
$campos .= "    a.descrdepto as deptodest,       ";
$campos .= "    a.coddepto as deptodestcod,      ";
$campos .= "    (SELECT m91_codigo FROM db_almox WHERE m91_depto = a.coddepto) AS almox_dest, ";
$campos .= "    (SELECT m91_codigo FROM db_almox WHERE m91_depto = b.coddepto) AS almox_said, ";
$campos .= "    (SELECT SUM(m82_quant) FROM matestoqueinimei 
                  WHERE m82_matestoqueini = transfere.m80_codigo ) AS total_quantidade_transferida";

$sSqlHeader                = $clmatestoqueini->sql_query_transf(null, $campos, 'transfere.m80_codigo', $where_matestoqueini);
$result_pesq_matestoqueini = $clmatestoqueini->sql_record($sSqlHeader);
$numrows_matestoqueini     = $clmatestoqueini->numrows;

if($numrows_matestoqueini==0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum Registro Encontrado ! ");
} 
$pdf = new scpdf();
$pdf->Open();

$pdf1 = new db_impcarne($pdf,27);

//$pdf1 = new db_impcarne($pdf);
//$pdf1->modelo = 26;
$pdf1->objpdf->SetTextColor(0,0,0);
$pdf1->Snumero_ant = "";

for($contador=0;$contador<$numrows_matestoqueini;$contador++){
	
	db_fieldsmemory($result_pesq_matestoqueini,$contador);
  
  $pdf1->prefeitura = $nomeinst;
  $pdf1->logo			  = $logo;
  $pdf1->enderpref  = $ender;
  $pdf1->municpref  = $munic;
  $pdf1->telefpref  = $telef;
  $pdf1->emailpref  = $email;
  $pdf1->emissao    = date("Y-m-d",db_getsession("DB_datausu"));
  $pdf1->cgcpref    = $cgc;
  
  $pdf1->totalQuantTransf = $total_quantidade_transferida;

  $pdf1->Rnumero     = $m80_codigo;
  
  $pdf1->Rdepart        = $descrdepto;
  $pdf1->RdepartCod     = $almox_said; //$coddepto;  
  $pdf1->Rdepartdest    = $deptodest;
  $pdf1->RdepartdestCod = $almox_dest;//$deptodestcod;
    
  $pdf1->Rdata       = $m80_data;
//$pdf1->Rhora       = $m80_hora;
  $pdf1->Rnomeus     = $nome;
  $pdf1->Rresumo     = $m80_obs;
  $pdf1->emissao     = date("Y-m-d",db_getsession("DB_datausu"));

  $sSqlItensCampos = "m60_codmater,m60_descr,m61_descr,m82_quant, m77_lote, m77_dtvalidade";
  $sSqlItens       = $clmatestoqueini->sql_query_qua($m80_codigo,$sSqlItensCampos,"m60_descr");
  $result_itens    = $clmatestoqueini->sql_record($sSqlItens);
  $numrows_itens   = $clmatestoqueini->numrows;

  $pdf1->rcodmaterial  = "m60_codmater";
  $pdf1->rdescmaterial = "m60_descr";
  $pdf1->runidadesaida = "m61_descr";
  $pdf1->rquantdeitens = "m82_quant";
  $pdf1->rlote         = "m77_lote";
  $pdf1->rvalidade     = "m77_dtvalidade";  
  $pdf1->rtotalitens   = $numrows_itens;

  $pdf1->recorddositens = $result_itens;
  $pdf1->linhasdositens = $numrows_itens;

  $pdf1->imprime();
  
}

if(isset($argv[1])){	
  $pdf1->objpdf->Output("/tmp/teste.pdf");
}else{	
  $pdf1->objpdf->Output();
}
?>