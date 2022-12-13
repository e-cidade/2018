<?php

$oPdf = new FpdfMultiCellBorder();
$oPdf->exibeHeader(false);
$oPdf->mostrarRodape(false);
$oPdf->mostrarTotalDePaginas(true);
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetTopMargin(1);
$oPdf->SetAutoPageBreak( false, 5 );

/**
 * Altura limite para impressão dos exâmes
 */
DBRegistry::add('iAlturaLimiteExames', 270);
DBRegistry::add('requisicao', $oDadosEstrutura->iRequisicao);
DBRegistry::add('solicitante', $oDadosEstrutura->oSolicitante);
DBRegistry::add('data', $oDadosEstrutura->sData);

try {

  foreach ( $oDadosEstrutura->aSetor as $iSetor => $oSetor) {

    DBRegistry::add('setor', $iSetor);

    montaCabecalho( $oPdf );

    $oPdf->SetFont("arial","", 8);
    $oPdf->Cell($oDadosEstrutura->iLarguraPadrao, $oDadosEstrutura->iAlturaPadrao, $oSetor->sDescricao, 'B', 1);

    foreach ($oSetor->aExames as $oExame) {

      imprimirAtributosExame($oPdf, $oExame);

      if ( !empty($oExame->sObservacao) ) {

        $iLinhasObservacao  = 1;
        $oPdf->SetFont("arial", '', 7);
        $iLinhasObservacao += $oPdf->nbLines(187, $oExame->sObservacao);

        // calcula a altura da observação
        $iAlturaObservacao = ($iLinhasObservacao * 3.5)  + $oPdf->getY();
        if ( $iAlturaObservacao > DBRegistry::get('iAlturaLimiteExames') ) {

          rodape($oPdf);
          montaCabecalho($oPdf);
        }
      }

      $oPdf->setX(15);
      $oPdf->SetFont("arial", 'B', 7);
      $oPdf->Cell(30, 3.5, "Observações do Resultado:", 0, 1);
      $oPdf->setX(15);
      $oPdf->SetFont("arial", '', 7);
      $oPdf->MultiCell(187, 3.5, $oExame->sObservacao);
      $oPdf->ln(6);
    }

    rodape($oPdf);
  }
} catch ( Exception $oErro ) {

  $sMessage = urlencode($oErro->getMessage());
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMessage}");
}

function imprimirAtributosExame($oPdf, $oExame) {

  foreach ($oExame->aAtributos as $i => $oAtributo) {

    $oAtributo->iAlturaLinhaComplemento = 4;
    $oPdf->SetFont("arial", "", 8);
    if ( validaQuebraPagina($oPdf, $oAtributo) ) {

      rodape($oPdf);
      montaCabecalho( $oPdf );
    }

    if ( $oAtributo->tipo == 1 ) {

      imprimirAtributoSintetico($oPdf, $oAtributo);
      continue;
    }

    $oPdf->SetFont("arial", "", 8);
    switch ($oAtributo->tiporeferencia) {
      case 1:
      case 3:
        imprimirAtributoAlfaNumerico($oPdf, $oAtributo);
        break;
      case 2:
        imprimirAtributoReferenciaNumerica($oPdf, $oAtributo);
        break;
      default:

        $sMsg = "Revise o cadastro do atributo {$oAtributo->nome} vinculado ao exame {$oExame->sNomeExame}.";
        throw new Exception($sMsg);
        break;
    }

    if ( !empty($oAtributo->titulacao) ) {

      $oPdf->setX(15);
      $oPdf->MultiCell(187, $oAtributo->iAlturaLinhaComplemento, "Titulação: " . $oAtributo->titulacao, 0);
    }


    if (!empty($oAtributo->valorabsolutoanterior) ) {

      $sDataResultado = $oAtributo->dataResultadoAnterior->convertTo(DBDate::DATA_PTBR);
      $oPdf->setX(15);
      $sTexto  = "Último Resultado: {$oAtributo->valorabsolutoanterior} {$oAtributo->unidade} ";
      $sTexto .= "realizado no dia {$sDataResultado}";
      $oPdf->MultiCell(187, $oAtributo->iAlturaLinhaComplemento, $sTexto, 0);
    }

    $oPdf->ln();
  }
}

function validaQuebraPagina( $oPdf, $oAtributo ) {

  $oAtributo->iAlturaLinhaComplemento;

  $iNumeroLinhasAtributo    = 0;
  $iNumeroLinhasComplemento = 0;

  if ( $oAtributo->tiporeferencia == 2 ) {

    $sValor = " {$oAtributo->valorabsoluto} {$oAtributo->unidade}";
    if ( !empty($oAtributo->valorpercentual) ) {
      $sPercent = str_pad($oAtributo->valorpercentual." %", 5," ", STR_PAD_LEFT);
      $sValor   = "{$sPercent}      {$sValor}";
    }

    $iNumeroLinhasAtributo += $oPdf->nbLines(96, $sValor);
    // Sendo resultado numérico, sempre temos que levar em consideração o valor de referência que são 3 linhas.
    $iNumeroLinhasComplemento += 3;
  } else {
    $iNumeroLinhasAtributo += $oPdf->nbLines(96,$oAtributo->valorabsoluto);
  }

  // Calcula quantas linhas a titulação (se houver) ocupará no pdf.
  if ( !empty($oAtributo->titulacao) ) {
    $iNumeroLinhasComplemento += $oPdf->nbLines(187, "Titulação: " . $oAtributo->titulacao) ;
  }

  // Calcula quantas linhas o resultado anterior (se houver) ocupará no pdf.
  if (!empty($oAtributo->valorabsolutoanterior) ) {
    $iNumeroLinhasComplemento += $oPdf->nbLines(187, "Último Resultado: {$oAtributo->valorabsolutoanterior} {$oAtributo->unidade}") ;
  }

  $iAlturaComplementos      = $iNumeroLinhasComplemento * $oAtributo->iAlturaLinhaComplemento;
  $iAlturaAtributos         = $iNumeroLinhasAtributo * 5;
  $iAlturaConteudoAtributos = $iAlturaComplementos + $iAlturaAtributos;
  if ( ($oPdf->getY() + $iAlturaConteudoAtributos) > DBRegistry::get('iAlturaLimiteExames') ) {
    return true;
  }

  return false;
}

function imprimirAtributoAlfaNumerico($oPdf, $oAtributo) {

  $sAtributo = identaNomeAtributo($oAtributo);
  $oPdf->Cell(96, 5, "{$sAtributo} ", 0, 0, '', 0, '', '.');
  $oPdf->MultiCell(96, 5, $oAtributo->valorabsoluto, 0);
}

function imprimirAtributoReferenciaNumerica($oPdf, $oAtributo) {

  $sAtributo = identaNomeAtributo($oAtributo);
  $oPdf->Cell(96, 5, "{$sAtributo} ", 0, 0, '', 0, '', '.');

  $sValor = " {$oAtributo->valorabsoluto} {$oAtributo->unidade}";
  if ( !empty($oAtributo->valorpercentual) ) {
    $sPercent = str_pad($oAtributo->valorpercentual." %", 5," ", STR_PAD_LEFT);
    $sValor   = "{$sPercent}      {$sValor}";
  }

  $oPdf->MultiCell(96, 5, $sValor, 0);

  $oPdf->SetFont("arial", "B", 8);
  $oPdf->ln(4);
  $oPdf->setX(76);
  $oPdf->Cell(30, 4, "Valor de Referência:", 0, 0);
  $oPdf->SetFont("arial", "", 8);
  $oPdf->Cell(30, 4, $oAtributo->referencia, 0, 1);

  $oPdf->ln();

}

function identaNomeAtributo( $oAtributo ) {
  return str_repeat("  ", $oAtributo->nivel ) . $oAtributo->nome;
}

function imprimirAtributoSintetico($oPdf, $oAtributo) {

  $oPdf->SetFont("arial", "B", 8);
  $oPdf->Cell(192, 5, identaNomeAtributo( $oAtributo ), 0, 1);
}


/**
 * [montaCabecalho description]
 * @param  FpdfMultiCellBorder $oPdf            [description]
 * @param  stdClass            $oDadosEstrutura [description]
 */
function montaCabecalho( $oPdf ) {

  $oPdf->AddPage();

  if ( !isset($_SESSION['DB_coddepto']) || empty($_SESSION['DB_coddepto']) ) {
    return;
  }

  try{

    $iRequisicao  = DBRegistry::get('requisicao');
    $oSolicitante = DBRegistry::get('solicitante');

    $oDepartamento = new DBDepartamento( $_SESSION['DB_coddepto'] );
    $oInstituicao  = $oDepartamento->getInstituicao()->getDadosPrefeitura();

    if ( $oInstituicao->getImagemLogo() != "" ) {
      $oPdf->Image('imagens/files/' . $oInstituicao->getImagemLogo(), 7, 7, 20);
    }

    $oPdf->SetFont("arial","B", 8);
    $oPdf->Text( 33, 9, $oDepartamento->getNomeDepartamento() );
    $oPdf->Text( 33, 14, substr($oInstituicao->getDescricao(),0 , 42 ) );
    $oPdf->SetFont("arial","", 8);

    $sEndereço  = $oInstituicao->getLogradouro();
    $sEndereço .= ", " . $oInstituicao->getNumero();

    if ( $oInstituicao->getComplemento() != "" ) {
      $sEndereço .= ", " . $oInstituicao->getComplemento();
    }

    $oPdf->Text( 33, 19, $sEndereço );

    $sMunicipio  = $oInstituicao->getMunicipio();
    $sMunicipio .= " - " . $oInstituicao->getUf();
    $oPdf->Text( 33, 23, $sMunicipio );

    $sTelefoneCnpj  = $oInstituicao->getTelefone();

    if ( $oInstituicao->getCNPJ() != "" ) {
      $sTelefoneCnpj .= " - CNPJ: " . $oInstituicao->getCNPJ();
    }

    $oPdf->Text( 33, 27, $sTelefoneCnpj );
    $oPdf->Text( 33, 31, substr($oInstituicao->getEmail(), 0, 48 ));
    $oPdf->Text( 33, 35, substr($oInstituicao->getSite(),  0, 50 ));


    $aSexo        = array();
    $aSexo[ "M" ] = "MASCULINO";
    $aSexo[ "F" ] = "FEMININO";

    $iLarguraLabel     = 16;
    $iLarguraDescricao = 63;

    $oPdf->SetFillColor(240);
    $oPdf->RoundedRect( 120, 6, 83, 39, 2, 'DF', '123' );

    $oPdf->SetY(8);

    $oPdf->setX(120);
    $oPdf->SetFont("arial", "B", 7);
    $oPdf->Cell( $iLarguraDescricao + $iLarguraLabel, 4, "DADOS DO PACIENTE", 0, 1, "C" );

    $oPdf->SetX(120);
    $oPdf->SetFont("arial","B", 7);
    $oPdf->Cell( $iLarguraLabel, 4, "Requisição:", 0, 0, "L" );
    $oPdf->SetFont("arial","", 7);
    $oPdf->Cell( $iLarguraDescricao, 4, $iRequisicao, 0, 1, "L" );

    $oPdf->SetX(120);
    $oPdf->SetFont("arial","B", 7);
    $oPdf->Cell( $iLarguraLabel, 4, "Paciente:", 0, 0, "L" );
    $oPdf->SetFont("arial","", 7);
    $oPdf->MultiCell( $iLarguraDescricao, 4, $oSolicitante->sNome, 0, "L" );

    $oPdf->SetX(120);
    $oPdf->SetFont("arial","B", 7);
    $oPdf->Cell( $iLarguraLabel, 4, "Idade:", 0, 0, "L" );
    $oPdf->SetFont("arial","", 7);
    $oPdf->Cell( $iLarguraDescricao, 4, $oSolicitante->iIdade, 0, 1, "L" );

    $oPdf->SetX(120);
    $oPdf->SetFont("arial","B", 7);
    $oPdf->Cell( $iLarguraLabel, 4, "Sexo:", 0, 0, "L" );
    $oPdf->SetFont("arial","", 7);
    $oPdf->Cell( $iLarguraDescricao, 4, $aSexo[$oSolicitante->sSexo], 0, 1, "L" );

    $oPdf->SetX(120);
    $oPdf->SetFont("arial","B", 7);
    $oPdf->Cell( $iLarguraLabel, 4, "Médico:", 0, 0, "L" );
    $oPdf->SetFont("arial","", 7);
    $oPdf->MultiCell( $iLarguraDescricao, 4, $oSolicitante->sMedico, 0, "L" );

    $oPdf->SetX(120);
    $oPdf->SetFont("arial","B", 7);
    $oPdf->Cell( $iLarguraLabel, 4, "Convênio:", 0, 0, "L" );
    $oPdf->SetFont("arial","", 7);
    $oPdf->Cell( $iLarguraDescricao, 4, "SUS", 0, 1, "L" );

    $oPdf->SetX(120);
    $oPdf->SetFont("arial","B", 7);
    $oPdf->Cell( $iLarguraLabel, 4, "Data:", 0, 0, "L" );
    $oPdf->SetFont("arial","", 7);
    $oPdf->Cell( $iLarguraDescricao, 4, DBRegistry::get('data'), 0, 1, "L" );

    $oPdf->Line( 10, 45, 200, 45 );
    $oPdf->SetY( 50 );
  } catch( Exception $oErro ) {

    db_msgbox($oErro->getMessage());
    db_redireciona("lab4_emissaoresult001.php");
  }
}


function rodape( $oPdf) {

  $iRequisicao = DBRegistry::get('requisicao');
  $iSetor      = DBRegistry::get('setor');

  $iUsuario = buscaUsuarioConferiuRequisicao($iRequisicao, $iSetor);
  if ( is_null($iUsuario) ) {
    return;
  }

  $oPdf->SetXY( 14, 270 );
  $oPdf->SetFont( 'arial', '', 9 );

  $oDaoUsuarioLogado    = new cl_lab_labsetor();
  $sCamposUsuarioLogado = " la47_i_login ";
  $sWhereUsuarioLogado  = " la21_i_requisicao = {$iRequisicao} and la24_i_setor = {$iSetor} ";
  $sSqlUsuarioLogado    = $oDaoUsuarioLogado->sql_query_cgm_lab_setor( null, $sCamposUsuarioLogado, null, $sWhereUsuarioLogado );
  $rsUsuarioLogado      = db_query( $sSqlUsuarioLogado );

  if ( $rsUsuarioLogado && pg_num_rows( $rsUsuarioLogado ) > 0 ) {

    $iUsuario = db_utils::fieldsMemory( $rsUsuarioLogado, 0 )->la47_i_login;
    $oUsuario = new UsuarioSistema( $iUsuario );

    $sCampos             = " la06_c_orgaoclasse as orgao_classe, la24_c_nomearq, la24_o_assinatura as assinatura";
    $sWhere              = " id_usuario = {$iUsuario} and la24_i_setor = {$iSetor} ";
    $sWhere             .= " and la03_i_departamento = " . db_getsession('DB_coddepto');
    $sWhere             .= " and la24_o_assinatura is not null ";
    $sSqlDadosAssinatura = $oDaoUsuarioLogado->sql_query_conferencia('', $sCampos, '', $sWhere);
    $rsDadosAssinatura   = db_query( $sSqlDadosAssinatura );

    if ( $rsDadosAssinatura && pg_num_rows( $rsDadosAssinatura ) > 0 ) {

      $oDadosAssinatura = db_utils::fieldsMemory( $rsDadosAssinatura, 0 );
      $sProfissional    = "Profissional: {$oUsuario->getCodigo()} - {$oUsuario->getNome()}";
      $oPdf->Cell( 192, 5, $sProfissional, 0, 1, "L" );

      $oPdf->Ln( 1 );
      $oPdf->SetX( 14 );
      $sOrgaoClasse = "Órgão Classe: {$oDadosAssinatura->orgao_classe}";
      $oPdf->Cell( 192, 5, $sOrgaoClasse, 0, 1, "L" );

      $sArquivo = "tmp/" . $oDadosAssinatura->la24_c_nomearq;

      db_inicio_transacao();
      DBLargeObject::leitura($oDadosAssinatura->assinatura, $sArquivo);
      db_inicio_transacao();

      $oPdf->Image( $sArquivo, 170, 270, 20 );
    }
  }

  imprimeEndereco( $oPdf );
  $oPdf->showPageNumber();
}

function imprimeEndereco( $oPdf ) {

  $oPdf->Line( 10, 282, 200, 282 );
  $oDepartamento         = new DBDepartamento( $_SESSION['DB_coddepto'] );
  $oEnderecoDepartamento = $oDepartamento->getEndereco();

  $sEndereço = "Endereço: " . $oEnderecoDepartamento->sRua;

  if ( !empty($oEnderecoDepartamento->iNumero) ) {
    $sEndereço .= ", {$oEnderecoDepartamento->iNumero}";
  }

  if ( !empty($oEnderecoDepartamento->sComplemento) ) {
    $sEndereço .= " - {$oEnderecoDepartamento->sComplemento}";
  }

  if ( !empty($oEnderecoDepartamento->sBairro) ) {
    $sEndereço .= " - {$oEnderecoDepartamento->sBairro}";
  }

  if ( $oDepartamento->getInstituicao()->getMunicipio() != "" ) {

    $sEndereço .= " - " . $oDepartamento->getInstituicao()->getMunicipio();
    if ( $oDepartamento->getInstituicao()->getUf() != "" ) {
      $sEndereço .= "/" . $oDepartamento->getInstituicao()->getUf();
    }
  }

  $sContato = "";
  if ( $oDepartamento->getTelefone() != "" ) {
    $sContato = "Contato: " . $oDepartamento->getTelefone();
  }

  if ( $oDepartamento->getEmailDepartamento() != "" ){
    $sContato .= " - " . $oDepartamento->getEmailDepartamento();
  }

  $oPdf->setY(282);
  $oPdf->SetFont("arial","B", 5);
  $oPdf->Cell(192, 3, $sEndereço, 0, 1, "C");
  $oPdf->Cell(192, 3, $sContato,  0, 1, "C");
}

$oPdf->Output( );