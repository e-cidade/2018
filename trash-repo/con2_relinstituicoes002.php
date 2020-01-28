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
include("libs/db_sql.php");
include("classes/db_db_config_classe.php");
include ("libs/db_utils.php");

$cldbconfig = new cl_db_config();

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//echo "<pre>";
//echo var_dump($_GET);
//echo "</pre>";
//exit();

$head3  = "RELATÓRIO DE INSTITUIÇÕES";

$sCampos  = "db_config.codigo,";
$sCampos .= "db_config.nomeinst,";
$sCampos .= "db_config.nomeinstabrev,";
$sCampos .= "db_config.ender,";
$sCampos .= "db_config.numero,";
$sCampos .= "db_config.db21_compl,";
$sCampos .= "db_config.bairro,";
$sCampos .= "db_config.munic,";
$sCampos .= "db_config.uf,";
$sCampos .= "db_config.cep,";
$sCampos .= "db_config.telef,";
$sCampos .= "db_config.fax,";
$sCampos .= "db_config.email,";
$sCampos .= "db_config.cgc,";
$sCampos .= "db_config.numcgm,";
$sCampos .= "cgm.z01_nome,";
$sCampos .= "db_config.url,";
$sCampos .= "db_config.logo,";
$sCampos .= "db_config.figura,";
$sCampos .= "db_config.pref,";
$sCampos .= "db_config.vicepref,";
$sCampos .= "db_config.prefeitura,";
$sCampos .= "db_config.db21_ativo,";
$sCampos .= "db_config.db21_codcli,";
$sCampos .= "db_config.db21_criacao,";
$sCampos .= "db_config.db21_datalimite,";
$sCampos .= "db_config.db21_codigomunicipoestado,";
$sCampos .= "db_config.dtcont,";
$sCampos .= "db_config.codtrib,";
$sCampos .= "db_config.tribinst,";
$sCampos .= "db_config.db21_tipoinstit,";
$sCampos .= "db_tipoinstit.db21_nome,";
//$sCampos .= "db_config.tx_banc,";
$sCampos .= "db_config.numbanco,";
$sCampos .= "db_config.tpropri,";
$sCampos .= "db_config.tsocios,";
//$sCampos .= "db_config.impdepto,";
$sCampos .= "db_config.nomedebconta,";
$sCampos .= "db_config.db21_regracgmiss,";
$sCampos .= "db_config.db21_regracgmiptu,";
$sCampos .= "db_config.db21_usasisagua,";
$sCampos .= "db_config.ident,";
$sCampos .= "db_config.diario,";
$sCampos .= "db_config.segmento,";
$sCampos .= "db_config.formvencfebraban";

$sWhere = "";
if(isset($instituicoes) && trim($instituicoes) != ""){
		$sWhere .= " codigo in $instituicoes";
}


//die($cldbconfig->sql_query(null,$sCampos,"nomeinst",$sWhere));
$result = $cldbconfig->sql_record($cldbconfig->sql_query(null,$sCampos,"nomeinst",$sWhere));
if ($cldbconfig->numrows == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
  exit;
}

$aRelatorio = db_utils::getColectionByRecord($result);
//echo "<pre>";
//echo var_dump($aRelatorio);
//echo "</pre>";
//
//exit();

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$alt     = 4;

foreach ($aRelatorio as $oInstituicao){
	
	$pdf->AddPage("P");
	$pdf->setfont('arial','b',9);
  $pdf->cell(190,$alt,"Dados da Instituição",0,1,"C",1);
  
	$pdf->setfont('arial','b',9);
	$pdf->cell(70,$alt,"Código",0,0,"L",0);
	$pdf->setfont('arial','',8);
	$pdf->cell(120,$alt,$oInstituicao->codigo,0,1,"L",0);
	
	$pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Nome da Instituição",0,0,"L",1);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->nomeinst,0,1,"L",1);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Nome abreviaado da Instituição",0,0,"L",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->nomeinstabrev,0,1,"L",0);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Endereço",0,0,"L",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->ender,0,1,"L",0);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Número",0,0,"L",1);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->numero,0,1,"L",1);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Endereço",0,0,"L",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->ender,0,1,"L",0);

  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Complemento Endereço",0,0,"L",1);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->db21_compl,0,1,"L",1);
  
	$pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Bairro",0,0,"L",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->bairro,0,1,"L",0);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Município",0,0,"L",1);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->munic,0,1,"L",1);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Unidade Federativa",0,0,"L",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->uf,0,1,"L",0);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Cep",0,0,"L",1);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->cep,0,1,"L",1);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Telefone",0,0,"L",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->telef,0,1,"L",0);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Fax",0,0,"L",1);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->fax,0,1,"L",1);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Email",0,0,"L",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->email,0,1,"L",0);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"CGC",0,0,"L",1);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->cgc,0,1,"L",1);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"CGM",0,0,"L",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->numcgm." - ".$oInstituicao->z01_nome,0,1,"L",0);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Url",0,0,"L",1);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->url,0,1,"L",1);
    
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Logo",0,0,"L",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->logo,0,1,"L",0);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Figura",0,0,"L",1);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->figura,0,1,"L",1);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Prefeito",0,0,"L",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->pref,0,1,"L",0);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Vice-Prefeito",0,0,"L",1);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->vicepref,0,1,"L",1);
   
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Prefeitura",0,0,"L",0);
  $pdf->setfont('arial','',8);
  $prefeitura = $oInstituicao->prefeitura == "t" ? "Sim" : "Não";
  $pdf->cell(120,$alt,$prefeitura,0,1,"L",0);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Ativo",0,0,"L",1);
  $pdf->setfont('arial','',8);
  switch ($oInstituicao->db21_ativo){
  	case 1: $db21_ativo = "Ativo";
  		break;
    case 2: $db21_ativo = "Inativo";
      break;
    case 3: $db21_ativo = "Offline";
      break;
    default: $db21_ativo = "";
  }
  $pdf->cell(120,$alt,$db21_ativo,0,1,"L",1);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Código do Cliente",0,0,"L",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->db21_codcli,0,1,"L",0);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Data de Criação da Instituição",0,0,"L",1);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,db_formatar($oInstituicao->db21_criacao,"d"),0,1,"L",1);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Data limite que a instituição é válida",0,0,"L",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,db_formatar($oInstituicao->db21_datalimite,"d"),0,1,"L",0);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Código do município no Estado",0,0,"L",1);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->db21_codigomunicipoestado,0,1,"L",1);
  $pdf->Ln(3);
  $pdf->setfont('arial','b',9);
  $pdf->cell(190,$alt,"Dados da Instituição Financeiro",0,1,"C",1);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Data da contabilidade",0,0,"L",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,db_formatar($oInstituicao->dtcont,"d"),0,1,"L",0);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Orgão / Unidade da Instituição",0,0,"L",1);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->codtrib,0,1,"L",1);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Instituição SIAPC/PAD",0,0,"L",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->tribinst,0,1,"L",0);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Tipo de Instituição",0,0,"L",1);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->db21_tipoinstit." - ".$oInstituicao->db21_nome,0,1,"L",1);
  
  $pdf->Ln(3);
  $pdf->setfont('arial','b',9);
  $pdf->cell(190,$alt,"Dados da Instituição Tributário",0,1,"C",1);
  /*
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Taxa Bancária",0,0,"L",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->tx_banc,0,1,"L",0);
  */
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Número do Banco",0,0,"L",1);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->numbanco,0,1,"L",1);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Débitos Proprietários",0,0,"L",0);
  $pdf->setfont('arial','',8);
  $tpropri = $oInstituicao->tpropri == "t" ? "Sim" : "Não";
  $pdf->cell(120,$alt,$tpropri,0,1,"L",0);

  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Débitos Sócios",0,0,"L",1);
  $pdf->setfont('arial','',8);
  $tsocios = $oInstituicao->tsocios == "t" ? "Sim" : "Não";
  $pdf->cell(120,$alt,$tsocios,0,1,"L",1);
  /*
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Imprime Departamento",0,0,"L",0);
  $pdf->setfont('arial','',8);
  $impdepto = $oInstituicao->impdepto == "t" ? "Sim" : "Não";
  $pdf->cell(120,$alt,$impdepto,0,1,"L",0);
  */
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Nome da Instituição no debito em conta",0,0,"L",1);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->nomedebconta,0,1,"L",1);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Regra cgm issbase",0,0,"L",0);
  $pdf->setfont('arial','',8);
  $x = array('0'=>'Não vincular socios','1'=>'Vincular socios');
  $db21_regracgmiss = $x[$oInstituicao->db21_regracgmiss];
  $pdf->cell(120,$alt,$db21_regracgmiss,0,1,"L",0);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Regra cgm iptu",0,0,"L",1);
  $pdf->setfont('arial','',8);
  $x = array('0'=>'Considerar Proprietario e Promitente','1'=>'Considerar Somente Proprietario','2'=>'Considerar Somente Promitente');
  $db21_regracgmiptu = $x[$oInstituicao->db21_regracgmiptu];
  $pdf->cell(120,$alt,$db21_regracgmiptu,0,1,"L",1);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Usa sistema de água",0,0,"L",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->db21_usasisagua,0,1,"L",0);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Identidade",0,0,"L",1);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->ident,0,1,"L",1);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Diário",0,0,"L",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->diario,0,1,"L",0);
       
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Segmento código de barras Febraban",0,0,"L",1);
  $pdf->setfont('arial','',8);
  $x = array('1'=>'Prefeituras','2'=>'Saneamento','3'=>'Energia Elétrica e Gás','4'=>'Telecomunicações','5'=>'Órgãos Governamentais','6'=>'Carnes e Assemelhados ou demais Empresas / Órgãos que serão identificadas através do CNPJ','7'=>'Multas de trânsito','9'=>'Uso exclusivo do banco');
  $segmento = $x[$oInstituicao->segmento];
  $pdf->cell(120,$alt,$segmento,0,1,"L",1);
  
  $pdf->setfont('arial','b',9);
  $pdf->cell(70,$alt,"Forma do vencimento Febraban",0,0,"L",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(120,$alt,$oInstituicao->formvencfebraban,0,1,"L",0);
  
  
}

$pdf->setfont('arial','b',7);
$pdf->cell(170,4,"Total de Instituições",0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,4,count($aRelatorio),0,1,"L",0);


$pdf->Output();

exit();

/*

	  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
	    $pdf->addpage("P");
			$lCabecalho = false;
	    if(isset($orgaos) && isset($unidades) && isset($departamentos)&& $q_pagina != "N"){
	    	if($iOrgao!=$o40_orgao){
		    	$pdf->setfont('arial','b',9);
		      $pdf->cell(30,$alt,"Órgão",0,0,"L",1);
				  $pdf->cell(30,$alt,$o40_orgao." - ".$o40_descr,0,1,"L",1);
				  if($q_pagina != "N"){
				  $pdf->cell(30,$alt,"Unidade",0,0,"L",1);
				  $pdf->cell(30,$alt,$o41_unidade." - ".$o41_descr,0,1,"L",1);
				  }
				  if($q_pagina == 'departamento'){
				  	$pdf->cell(30,$alt,"Departamento",0,0,"L",1);
			    	$pdf->cell(30,$alt,$t52_depart." - ".$descrdepto,0,1,"L",1);
				  }
				  $pdf->Ln(3);
				  orgao_cabecalho($pdf,$RLt52_descr,$RLt52_ident,$RLt52_depart,$alt);
				  $lCabecalho = true;
				  $p = 0;
				 // $iUnidade = 0;
	    	}else if($iUnidade != $o41_unidade){
	    		
	    		$pdf->setfont('arial','b',9);
	    		$pdf->cell(20,$alt,"Unidade",0,0,"L",1);
				  $pdf->cell(30,$alt,$o41_unidade." - ".$o41_descr,0,1,"L",1);
				  $pdf->Ln(3);
					
		*/		  
?>