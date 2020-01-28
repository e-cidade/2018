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
require_once("libs/db_liborcamento.php");
require_once("libs/db_utils.php");
require_once("classes/db_ouvidoriaatendimento_classe.php");
require_once("classes/db_proctransferproc_classe.php");
require_once("classes/db_cidadao_classe.php");

$oGet  = db_utils::postMemory($_GET);

$clouvidoriaatendimento = new cl_ouvidoriaatendimento;
$clProcTransferProc     = new cl_proctransferproc();

//Atendimento
$sWhereAtendimento  = "    ov01_instit       = ".db_getsession('DB_instit');
$sWhereAtendimento .= "and ouvidoriaatendimento.ov01_sequencial = {$oGet->iAtendimento}";

/**
 * Busca os dados para o Cabeçalho do relatório
 */

$sCamposCabecalho   = "ov01_dataatend,  ";
$sCamposCabecalho  .= "ov10_cidadao,    ";
$sCamposCabecalho  .= "ov11_cgm,        ";
$sCamposCabecalho  .= "ov05_sequencial as tipo_identificacao, ";
$sCamposCabecalho  .= "ov01_numero,     ";
$sCamposCabecalho  .= "ov01_anousu,     ";
$sCamposCabecalho  .= "ov01_horaatend,  ";
$sCamposCabecalho  .= "ov01_anousu,     ";
$sCamposCabecalho  .= "p58_codproc,     ";
$sCamposCabecalho  .= "p58_ano,         ";
$sCamposCabecalho  .= "ov01_requerente  ";

$sSqlCabecalho      = $clouvidoriaatendimento->sql_query_proc(null, $sCamposCabecalho, "ov01_numero", $sWhereAtendimento);

$rsCabecalho        = $clouvidoriaatendimento->sql_record($sSqlCabecalho);

$oCabecalho = db_utils::fieldsMemory($rsCabecalho, 0);


$sDataProcesso = implode("/", array_reverse(explode("-", $oCabecalho->ov01_dataatend)));
$head2 = "Detalhes do atendimento nº {$oCabecalho->ov01_numero}/{$oCabecalho->ov01_anousu}";
$head3 = "Data do Processo: {$sDataProcesso}";
$head4 = "Processo: {$oCabecalho->p58_codproc} / {$oCabecalho->p58_ano}";
/*
ov01_requerente,
*/
$iAlt   = 5;
$iFonte = 7;

$oPdf = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->AddPage();
$oPdf->setfillcolor(235);



/**
 * ATENDIMENTOS
 * Se Variável vier setada, os dados referentes aos atendimentos irão aparecer no relatório
 **/
if (isset($oGet->sAtendimentosVinculados)) {

  $sCamposAtendimento  = "distinct fc_numeroouvidoria(ov01_sequencial) as ov01_numero,";
  $sCamposAtendimento .= "ov01_dataatend,  ";
  $sCamposAtendimento .= "ov01_horaatend,  ";
  $sCamposAtendimento .= "ov01_anousu,     ";
  $sCamposAtendimento .= "ov01_requerente, ";
  $sCamposAtendimento .= "ov01_solicitacao,";
  $sCamposAtendimento .= "p58_codproc,     ";
  $sCamposAtendimento .= "p58_ano,         ";
  $sCamposAtendimento .= "ov01_requerente, ";
  $sCamposAtendimento .= "ov01_executado   ";

  $sSqlAtendimentos = $clouvidoriaatendimento->sql_query_proc("",$sCamposAtendimento,"ov01_numero",$sWhereAtendimento);
  $rsAtendimentos   = $clouvidoriaatendimento->sql_record($sSqlAtendimentos);
  $iNroAtendimentos = $clouvidoriaatendimento->numrows;

  if ( $iNroAtendimentos > 0 )  {

  //=======================================  DADOS PESSOAIS DO REQUERENTE =============================================

    $oStdDadosRequerente = new stdClass();
    $oStdDadosRequerente->codigo         = "";
    $oStdDadosRequerente->nomerequerente = "Anônimo";
    $oStdDadosRequerente->cpfcnpj        = "";
    $oStdDadosRequerente->municipio      = "";
    $oStdDadosRequerente->cep            = "";
    $oStdDadosRequerente->estado         = "";
    $oStdDadosRequerente->endereco       = "";
    $oStdDadosRequerente->complemento    = "";
    $oStdDadosRequerente->numero         = "";
    $oStdDadosRequerente->bairro         = "";
    $oStdDadosRequerente->telefone       = "";


    if ($oCabecalho->tipo_identificacao == 2) {

      if (isset($oCabecalho->ov11_cgm) && $oCabecalho->ov11_cgm != "") {

        $sCamposCgm     = "z01_numcgm  as codigo,         ";
        $sCamposCgm    .= "z01_nome    as nomerequerente, ";
        $sCamposCgm    .= "z01_telef   as telefone,       ";
        $sCamposCgm    .= "z01_cgccpf  as cpfcnpj,        ";
        $sCamposCgm    .= "z01_munic   as municipio,      ";
        $sCamposCgm    .= "z01_cep     as cep,            ";
        $sCamposCgm    .= "z01_uf      as estado,         ";
        $sCamposCgm    .= "z01_ender   as endereco,       ";
        $sCamposCgm    .= "z01_numero  as numero,         ";
        $sCamposCgm    .= "z01_bairro  as bairro,         ";
        $sCamposCgm    .= "z01_compl   as complemento     ";

        $oDaoBuscaCgm           = db_utils::getDao('cgm');
        $sSqlBuscaDadosCGM      = $oDaoBuscaCgm->sql_query_file($oCabecalho->ov11_cgm, $sCamposCgm);
        $rsBuscaDadosCgmCidadao = $oDaoBuscaCgm->sql_record($sSqlBuscaDadosCGM);

      } else {

        $sCamposCidadao  = "ov02_sequencial as codigo,         ";
        $sCamposCidadao .= "ov02_nome       as nomerequerente, ";
        $sCamposCidadao .= "ov07_numero     as telefone,       ";
        $sCamposCidadao .= "ov02_cnpjcpf    as cpfcnpj,        ";
        $sCamposCidadao .= "ov02_munic      as municipio,      ";
        $sCamposCidadao .= "ov02_cep        as cep,            ";
        $sCamposCidadao .= "ov02_uf         as estado,         ";
        $sCamposCidadao .= "ov02_endereco   as endereco,       ";
        $sCamposCidadao .= "ov02_numero     as numero,         ";
        $sCamposCidadao .= "ov02_bairro     as bairro,         ";
        $sCamposCidadao .= "ov02_compl      as complemento     ";

        $oDaoBuscaCidadao       = db_utils::getDao('cidadao');
        //$sSqlBuscaDadosCidadao  = $oDaoBuscaCidadao->sql_query_file($oCabecalho->ov10_cidadao, null, $sCamposCidadao);
        $sSqlBuscaDadosCidadao  = $oDaoBuscaCidadao->sql_query_cidadaotelefone($oCabecalho->ov10_cidadao, null, $sCamposCidadao);
        $rsBuscaDadosCgmCidadao = $oDaoBuscaCidadao->sql_record($sSqlBuscaDadosCidadao);
      }

      $oResultadoRequerente = db_utils::fieldsMemory($rsBuscaDadosCgmCidadao, 0);
      $oStdDadosRequerente->nomerequerente = "{$oResultadoRequerente->codigo} - {$oResultadoRequerente->nomerequerente}";
      $oStdDadosRequerente->cpfcnpj        = $oResultadoRequerente->cpfcnpj;
      $oStdDadosRequerente->municipio      = $oResultadoRequerente->municipio;
      $oStdDadosRequerente->cep            = $oResultadoRequerente->cep;
      $oStdDadosRequerente->estado         = $oResultadoRequerente->estado;
      $oStdDadosRequerente->endereco       = $oResultadoRequerente->endereco;
      $oStdDadosRequerente->complemento    = $oResultadoRequerente->complemento;
      $oStdDadosRequerente->numero         = $oResultadoRequerente->numero;
      $oStdDadosRequerente->bairro         = $oResultadoRequerente->bairro;
      $oStdDadosRequerente->telefone       = $oResultadoRequerente->telefone;

    }


    // NOME DO REQUERENTE
  	$oPdf->SetFont('Arial','b',$iFonte+2);
  	$oPdf->cell(25,  $iAlt, "Requerente: ",             "",  0, "L", 0);
  	$oPdf->SetFont('Arial','',$iFonte);
  	$oPdf->cell(100,  $iAlt, $oStdDadosRequerente->nomerequerente, "",  0, "L", 0);



  	// TELEFONE
  	$oPdf->SetFont('Arial','b',$iFonte+2);
  	$oPdf->cell(10, $iAlt, "Fone:" , "",  0, "L", 0);
  	$oPdf->SetFont('Arial','',$iFonte);
  	$oPdf->cell(25, $iAlt, $oStdDadosRequerente->telefone,  "",  0, "L", 0);


  	// CPF
  	$sCpfCnpj = "CPF:";
  	$iCnpjCpf = db_formatar($oStdDadosRequerente->cpfcnpj , "cpf");
  	if (strlen($oStdDadosRequerente->cpfcnpj) > 11) {

  	  $iCnpjCpf = db_formatar($oStdDadosRequerente->cpfcnpj , "cnpj");
  	  $sCpfCnpj = "CNPJ:";
  	}
  	$oPdf->SetFont('Arial','b',$iFonte+2);
  	$oPdf->cell(15,  $iAlt, $sCpfCnpj , "",  0, "L", 0);
  	$oPdf->SetFont('Arial','',$iFonte);
  	$oPdf->cell(75,  $iAlt, $iCnpjCpf,  "",  1, "L", 0);


  	// ENDERECO
  	$oPdf->SetFont('Arial','b',$iFonte+2);
  	$oPdf->cell(25,  $iAlt, "Endereço: ",             "",  0, "L", 0);
  	$oPdf->SetFont('Arial','',$iFonte);
  	$oPdf->cell(100,  $iAlt, "{$oStdDadosRequerente->endereco}, Número: {$oStdDadosRequerente->numero}", "",  0, "L", 0);

  	//BAIRRO
  	$oPdf->SetFont('Arial','b',$iFonte+2);
  	$oPdf->cell(35,  $iAlt, "Bairro:" , "",  0, "L", 0);
  	$oPdf->SetFont('Arial','',$iFonte);
  	$oPdf->cell(120,  $iAlt, $oStdDadosRequerente->bairro, "",  1, "L", 0);

  	// COMPLEMENTO
  	$oPdf->SetFont('Arial','b',$iFonte+2);
  	$oPdf->cell(25,  $iAlt, "Complemento: ",              "",  0, "L", 0);
  	$oPdf->SetFont('Arial','',$iFonte);
  	$oPdf->cell(100,  $iAlt, $oStdDadosRequerente->complemento , "",  0, "L", 0);


  	//MUNICIPIO
  	$oPdf->SetFont('Arial','b',$iFonte+2);
  	$oPdf->cell(35,  $iAlt, "Município / CEP / UF:" , "",  0, "L", 0);
  	$oPdf->SetFont('Arial','',$iFonte);
  	$oPdf->cell(120,  $iAlt, $oStdDadosRequerente->municipio . "  " .
  	                         $oStdDadosRequerente->cep   . "  " .
  	                         $oStdDadosRequerente->estado  ,  "",  1, "L", 0);

  	$oPdf->Ln();

	//====================================================================================================================

  	$oPdf->SetFont('Arial','b',$iFonte+2);
  	$oPdf->ln();
  	$oPdf->Cell(30,$iAlt,"Atendimentos Vinculados",0,1,"L",0);
    $iCor = 1;

  	for ( $iInd=0; $iInd < $iNroAtendimentos; $iInd++ ) {

      $oAtendimento = db_utils::fieldsMemory($rsAtendimentos,$iInd);

      if ( $iInd == 0 || ($oPdf->GetY() > $oPdf->h - 30)  ) {

      	if ( $iInd != 0 ) {
          $oPdf->addPage("L");
      	}

      	$oPdf->SetFont('Arial','b',$iFonte);
      	$oPdf->Cell(25,$iAlt,"Atendimento"       ,1,0,"C",1);
      	$oPdf->Cell(25,$iAlt,"Data"              ,1,0,"C",1);
      	$oPdf->Cell(25,$iAlt,"Hora"              ,1,0,"C",1);
      	$oPdf->Cell(205,$iAlt,"Requerente"        ,1,1,"C",1);
      	$oPdf->SetFont('Arial','',$iFonte);
      }

      if ( $iCor == 0 ) {
      	$iCor = 1;
      } else {
      	$iCor = 0;
      }

      $oPdf->Cell(25,$iAlt,"{$oAtendimento->ov01_numero}" ,0,0,"C");
      $oPdf->Cell(25,$iAlt,db_formatar($oAtendimento->ov01_dataatend,'d'),0,0,"C");
      $oPdf->Cell(25,$iAlt,$oAtendimento->ov01_horaatend                 ,0,0,"C");
      $oPdf->Cell(205,$iAlt,$oAtendimento->ov01_requerente               ,0,1,"L");

      $oPdf->Cell(280,$iAlt,"Solicitação"       ,1,1,"C",1);
      $oPdf->MultiCell(280,$iAlt,$oAtendimento->ov01_solicitacao ,0,'L');

      $oPdf->Cell(280,$iAlt,"Executado"         ,1,1,"C",1);
      $oPdf->MultiCell(280,$iAlt,$oAtendimento->ov01_executado ,0,'L');

  	}

  	$oPdf->Ln(2*$iAlt);

  }
}


/**
 * DESPACHOS
 * Se Variável vier setada, os dados referentes aos despachos irão aparecer no relatório
 **/
if (isset($oGet->sDespachos)) {

  /**
   * Faz a consulta dos despachos
   **/
  $sCamposDespachos  = "p61_dtandam,";
  $sCamposDespachos .= "p61_hora,   ";
  $sCamposDespachos .= "coddepto,   ";
  $sCamposDespachos .= "descrdepto, ";
  $sCamposDespachos .= "login,      ";
  $sCamposDespachos .= "p61_despacho";

  $sSqlDespachos     = $clProcTransferProc->sql_query_andam(null,$oGet->iProcesso,$sCamposDespachos,"p62_codtran,p61_dtandam,p61_hora");
  $rsDespachos       = $clProcTransferProc->sql_record($sSqlDespachos);
  $iNroDespachos     = $clProcTransferProc->numrows;

  /**
   * Caso existam despachos, devem ser impressos no PDF
   */
  if ( $iNroDespachos > 0 )  {

    $oPdf->SetFont('Arial','b',$iFonte+2);
    $oPdf->Cell(30,$iAlt,"Despachos",0,1,"L",0);
    $iCor = 1;

    for ( $iInd=0; $iInd < $iNroDespachos; $iInd++ ) {

      $oDespacho = db_utils::fieldsMemory($rsDespachos,$iInd);

      if ( $iInd == 0 || ($oPdf->GetY() > $oPdf->h - 30)  ) {

        if ( $iInd != 0 ) {
          $oPdf->addPage("L");
        }

        $oPdf->SetFont('Arial','b',$iFonte);

        $oPdf->Cell(25,$iAlt,"Data"          ,1,0,"C",1);
        $oPdf->Cell(25,$iAlt,"Hora"          ,1,0,"C",1);
        $oPdf->Cell(30,$iAlt,"Usuário"       ,1,0,"C",1);
        $oPdf->Cell(200,$iAlt,"Departamento"  ,1,1,"C",1);
        $oPdf->Cell(280,$iAlt,"Despacho"      ,1,1,"C",1);

        $oPdf->SetFont('Arial','',$iFonte);
      }

      if ( $iCor == 0 ) {
        $iCor = 1;
      } else {
        $iCor = 0;
      }

      $oPdf->Cell(25,$iAlt,db_formatar($oDespacho->p61_dtandam,'d'),0,0,"C",0);
      $oPdf->Cell(25,$iAlt,$oDespacho->p61_hora                    ,0,0,"C",0);
      $oPdf->Cell(30,$iAlt,$oDespacho->login                       ,0,0,"L",0);
      $oPdf->Cell(200,$iAlt,"{$oDespacho->coddepto} - {$oDespacho->descrdepto}",0,1,"L",0);
      $oPdf->MultiCell(280,$iAlt,$oDespacho->p61_despacho ,0,'L',0);
      $oPdf->Line($oPdf->GetX(), $oPdf->GetY(), 290, $oPdf->GetY());
    }

    $oPdf->Ln(2*$iAlt);

  }
}

/**
 * Retornos
 * Se Variável vier setada, os dados referentes aos Retornos irão aparecer no relatório
 **/
if (isset($oGet->sRetornos)) {

  $sWhereRetornos   = "     ov01_instit       = ".db_getsession('DB_instit');
  $sWhereRetornos  .= " and ov01_sequencial = {$oGet->iAtendimento} ";
  $sWhereRetornos  .= " and ov22_sequencial is not null ";

  $sCamposRetornos  = "distinct ov01_numero,     ";
  $sCamposRetornos .= "         ov01_requerente, ";
  $sCamposRetornos .= "         ov20_dataretorno,";
  $sCamposRetornos .= "         ov20_horaretorno,";
  $sCamposRetornos .= "         ov22_descricao,  ";
  $sCamposRetornos .= "         ov20_informa,    ";
  $sCamposRetornos .= "         ov20_resposta,   ";
  $sCamposRetornos .= "         case when ov20_confirma is true then 'Sim' else 'Não' end as ov20_confirma";

  $sSqlRetornos = $clouvidoriaatendimento->sql_query_retorno("",$sCamposRetornos,"ov01_numero",$sWhereRetornos);
  $rsRetornos   = $clouvidoriaatendimento->sql_record($sSqlRetornos);
  $iNroRetornos = $clouvidoriaatendimento->numrows;

  if ( $iNroRetornos > 0 )  {

    $oPdf->SetFont('Arial','b',$iFonte+2);
    $oPdf->Cell(30,$iAlt,"Retornos",0,1,"L",0);
    $iCor = 1;

    for ( $iInd=0; $iInd < $iNroRetornos; $iInd++ ) {

      $oRetorno = db_utils::fieldsMemory($rsRetornos,$iInd);

      if ( $iInd == 0 || ($oPdf->GetY() > $oPdf->h - 30)  ) {

        if ( $iInd != 0 ) {
          $oPdf->addPage("L");
        }

        $oPdf->SetFont('Arial','b',$iFonte);
        $oPdf->Cell(20,$iAlt,"Atendimento"       ,1,0,"C",1);
        $oPdf->Cell(50,$iAlt,"Requerente"        ,1,0,"C",1);
        $oPdf->Cell(20,$iAlt,"Data"              ,1,0,"C",1);
        $oPdf->Cell(20,$iAlt,"Hora"              ,1,0,"C",1);
        $oPdf->Cell(30,$iAlt,"Tipo Retorno"      ,1,0,"C",1);
        $oPdf->Cell(60,$iAlt,"Informação"        ,1,0,"C",1);
        $oPdf->Cell(60,$iAlt,"Resposta"          ,1,0,"C",1);
        $oPdf->Cell(20,$iAlt,"Confirmação"       ,1,1,"C",1);
        $oPdf->SetFont('Arial','',$iFonte);
      }

      if ( $iCor == 0 ) {
        $iCor = 1;
      } else {
        $iCor = 0;
      }

      $oPdf->Cell(20,$iAlt,$oRetorno->ov01_numero                       ,0,0,"C",$iCor);
      $oPdf->Cell(50,$iAlt,$oRetorno->ov01_requerente                   ,0,0,"L",$iCor);
      $oPdf->Cell(20,$iAlt,db_formatar($oRetorno->ov20_dataretorno,'d') ,0,0,"C",$iCor);
      $oPdf->Cell(20,$iAlt,$oRetorno->ov20_horaretorno                  ,0,0,"C",$iCor);
      $oPdf->Cell(30,$iAlt,$oRetorno->ov22_descricao                    ,0,0,"L",$iCor);

      $iPosY = $oPdf->GetY();
      $iPosX = $oPdf->GetX()+60;
      $oPdf->MultiCell(60,$iAlt,$oRetorno->ov20_informa                   ,0,'L',$iCor);
      $iPosInformaY = $oPdf->GetY();
      $oPdf->SetXY($iPosX,$iPosY);

      $iPosY = $oPdf->GetY();
      $iPosX = $oPdf->GetX()+60;
      $oPdf->MultiCell(60,$iAlt,$oRetorno->ov20_resposta                  ,0,'L',$iCor);
      $iPosRespostaY = $oPdf->GetY();
      $oPdf->SetXY($iPosX,$iPosY);
      $oPdf->Cell(20,$iAlt,$oRetorno->ov20_confirma                     ,0,1,"L",$iCor);

      if ( $iPosInformaY > $iPosRespostaY ) {
  	    $oPdf->SetY($iPosInformaY);
      } else {
      	$oPdf->SetY($iPosRespostaY);
      }
    }
    $oPdf->Ln(2*$iAlt);
  }
}
$oPdf->Output();
?>