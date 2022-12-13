<?php
$oPdf = new FpdfMultiCellBorder();
$oPdf->exibeHeader(false);
$oPdf->mostrarRodape(false);
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetTopMargin(1);
$oPdf->SetAutoPageBreak( false, 5 );

$oDadosEstrutura->sNome  = "Resultado({$oDadosEstrutura->oSolicitante->iCodigo})" . $oDadosEstrutura->oSolicitante->iCodigo . "_";
$oDadosEstrutura->sNome .= date("d-m-Y",db_getsession("DB_datausu")).".pdf";

/**
 * Percorre os setores
 */
foreach( $oDadosEstrutura->aSetor as $iSetor => $oSetor ) {

  $oDadosEstrutura->iSetor     = $iSetor;
  $oDadosEstrutura->aExames = $oSetor->aExames;

  montaCabecalho( $oPdf, $oDadosEstrutura );
  $oPdf->SetY( 50 );
  $oPdf->Cell( 190, $oDadosEstrutura->iAlturaPadrao, $oSetor->sDescricao, 'B', 1, "L" );
  $oPdf->SetFont('courier', "B", 10);
  atributosExame( $oPdf, $oDadosEstrutura );
  rodape( $oPdf, $oDadosEstrutura, $iSetor );
}

/**
 * ********************************************************
 * Monta o corpo do relatório com a estrutura dos atributos
 * @param scpdf $oPdf
 * @param $oDadosEstrutura
 * ********************************************************
 */
function atributosExame( scpdf $oPdf, $oDadosEstrutura ) {

  $oDadosEstrutura->lPrimeiroRegistro = true;

  /**
   * Array com a posição do X a ser setada, de acordo com o nível do atributo
   */
  $aPosicaoAtributos    = array();
  $aPosicaoAtributos[1] = 12;
  $aPosicaoAtributos[2] = 14;
  $aPosicaoAtributos[3] = 16;
  $aPosicaoAtributos[4] = 18;
  $aPosicaoAtributos[5] = 20;

  /**
   * String contendo as observações de todos os exames por setor
   */
  $sObservacao = '';

  /**
   * Percorre os atributos a serem impressos
   */


  foreach ($oDadosEstrutura->aExames as $oDadosExame ) {

    foreach( $oDadosExame->aAtributos as $oAtributos ) {

      if ($oPdf->getY() +5 >= 252 ) {

        rodape( $oPdf, $oDadosEstrutura, $oDadosEstrutura->iSetor );
        montaCabecalho( $oPdf, $oDadosEstrutura );
        $oPdf->SetY( 50 );
      }

      $sNegrito                       = $oAtributos->tipo == 1 ? "b" : "";
      $oDadosEstrutura->iAlturaPadrao = $oAtributos->tipo == 1 ? 5 : 3.5;

      /**
       * Caso seja o primeiro registro da página, imprime o texto dos valores
       */
      if ( $oDadosEstrutura->lPrimeiroRegistro ) {

        $iPosicaoY                          = $oPdf->GetY();
        $oDadosEstrutura->lPrimeiroRegistro = false;
        $oPdf->SetXY( 136, $iPosicaoY );
        $oPdf->Cell( $oDadosEstrutura->iLarguraPadrao, $oDadosEstrutura->iAlturaPadrao, "Valores de Referência", 0, 1, "L" );

        if ( $oAtributos->nivel == 1) {
          $oPdf->SetY( $iPosicaoY );
        }
      }

      $oPdf->SetFont( 'courier', $sNegrito, 10 );
      $oPdf->SetX( $aPosicaoAtributos[ $oAtributos->nivel ] );

      $iAlturaLinhaPadrao    = $oDadosEstrutura->iAlturaPadrao;
      $iNumeroLinhasOcupadas = 1;

      $iColunaNome        = 70;
      $iColunaVlrPercent  = 20;
      $iColunaVlrAbsoluto = 40;
      $iColunaReferencia  = 55;

      if ( $oPdf->NbLines($iColunaNome, $oAtributos->nome) > $iNumeroLinhasOcupadas) {
        $iNumeroLinhasOcupadas = $oPdf->NbLines($iColunaNome, $oAtributos->nome);
      }
      if ( $oPdf->NbLines($iColunaVlrPercent, $oAtributos->valorpercentual) > $iNumeroLinhasOcupadas) {
        $iNumeroLinhasOcupadas = $oPdf->NbLines($iColunaVlrPercent, $oAtributos->valorpercentual);
      }
      if ( $oPdf->NbLines($iColunaVlrAbsoluto,  $oAtributos->valorabsoluto) > $iNumeroLinhasOcupadas) {
        $iNumeroLinhasOcupadas = $oPdf->NbLines($iColunaVlrAbsoluto, $oAtributos->valorabsoluto);
      }
      if ( $oPdf->NbLines($iColunaReferencia,  $oAtributos->referencia) > $iNumeroLinhasOcupadas) {
        $iNumeroLinhasOcupadas = $oPdf->NbLines($iColunaReferencia, $oAtributos->referencia);
      }

      $iYInicio = $oPdf->GetY();
      $iXInicio = $oPdf->GetX();

      $iAlturaLinhaUsada = $iAlturaLinhaPadrao;
      $oPdf->SetXY($iXInicio, $iYInicio);

      /**
       * Verifica se o tipo de atributo é diferente de 1 e adiciona : após o nome
       */
      if ( $oAtributos->tipo != 1 ) {
        $oAtributos->nome .= ':';
      }

      $oPdf->Cell($iColunaNome, $iAlturaLinhaUsada, $oAtributos->nome, 0, 0, 'L',  '', '', '.');
      $iXInicio += $iColunaNome;
      $oPdf->SetXY($iXInicio, $iYInicio);

      $sValorPercentual = $oAtributos->valorpercentual;
      if (!empty($oAtributos->valorpercentual)) {
        $sValorPercentual = "{$oAtributos->valorpercentual} %";
      }
      $oPdf->MultiCell($iColunaVlrPercent, $iAlturaLinhaUsada, $sValorPercentual, 0, 'L');

      $iXInicio += $iColunaVlrPercent;
      $oPdf->SetXY($iXInicio, $iYInicio);
      $oPdf->MultiCell($iColunaVlrAbsoluto, $iAlturaLinhaUsada, $oAtributos->valorabsoluto, 0, 'L');
      $iXInicio += $iColunaVlrAbsoluto;
      $oPdf->SetXY($iXInicio, $iYInicio);
      $oPdf->MultiCell($iColunaReferencia, $iAlturaLinhaUsada, $oAtributos->referencia, 0, 'L');

      $oPdf->SetY($iYInicio + ($iAlturaLinhaPadrao * $iNumeroLinhasOcupadas) );
    }

    $oPdf->SetFont( 'courier', '', 7 );
    $oPdf->Ln();

    /**
     * Lista os medicamentos do exame
     */
    if( !empty($oDadosExame->aMedicamentosExame) ) {

      $oPdf->SetFont( 'courier', 'b', 7 );
      $aMedicamentos = array();

      foreach ($oDadosExame->aMedicamentosExame as $oMedicamento){
        $aMedicamentos[] = $oMedicamento->getNome();
      }

      $sMedicamentos       = implode(', ', $aMedicamentos);
      $iLinhasMedicamentos = $oPdf->NbLines($oDadosEstrutura->iLarguraPadrao , $sMedicamentos);
      $iYinicio            = $oPdf->GetY();
      $iAlturaMedicamentos = ($iLinhasMedicamentos * 4) + $iYinicio;

      /**
       * 252 é a Altura até o quadro do rodapé
       */
      if ( $iAlturaMedicamentos > 252 ) {

        rodape( $oPdf, $oDadosEstrutura, $oDadosEstrutura->iSetor );
        montaCabecalho( $oPdf, $oDadosEstrutura );
        $oPdf->SetY( 50 );
      }
      $oPdf->SetFont( 'courier', 'b', 7 );
      $oPdf->Cell( $oDadosEstrutura->iLarguraPadrao, 4, "Medicamentos:", 0, 1, "L" );
      $oPdf->SetFont( 'courier', '', 7 );
      $oPdf->MultiCell($oDadosEstrutura->iLarguraPadrao, 4, $sMedicamentos, 0, 'J');
    }

    if ( !empty($oDadosExame->sObservacao) ) {

      $oPdf->SetFont( 'courier', 'b', 7 );
      $sObservacao = $oDadosExame->sObservacao;
      $iLinhasObservacao = $oPdf->NbLines($oDadosEstrutura->iLarguraPadrao , $sObservacao);
      $iYinicio          = $oPdf->GetY();
      $iAlturaObservacao = ($iLinhasObservacao * 4) + $iYinicio;

      /**
       * 252 é a Altura até o quadro do rodapé
       */
      if ( $iAlturaObservacao > 252 ) {

        rodape( $oPdf, $oDadosEstrutura, $oDadosEstrutura->iSetor );
        montaCabecalho( $oPdf, $oDadosEstrutura );
        $oPdf->SetY( 50 );
      }
      $oPdf->SetFont( 'courier', 'b', 7 );
      $oPdf->Cell( $oDadosEstrutura->iLarguraPadrao, 4, "Observações do Resultado:", 0, 1, "L" );
      $oPdf->SetFont( 'courier', '', 7 );
      $oPdf->MultiCell($oDadosEstrutura->iLarguraPadrao, 4, $sObservacao, 0, 'J');

    }

    $oPdf->Ln(2);

    $iLinhasMaterialColeta = count($oDadosExame->aDadosMaterialColeta);
    $iYinicio              = $oPdf->GetY();
    $iAlturaMaterialColeta    = ($iLinhasMaterialColeta * 4) + $iYinicio;

    /**
     * 252 é a Altura até o quadro do rodapé
     */
    if ( $iAlturaMaterialColeta > 252 ) {

      rodape( $oPdf, $oDadosEstrutura, $oDadosEstrutura->iSetor );
      montaCabecalho( $oPdf, $oDadosEstrutura );
      $oPdf->SetY( 50 );
    }

    foreach ($oDadosExame->aDadosMaterialColeta as $oMaterialColeta) {

      $oPdf->SetFont( 'courier', 'b', 7 );
      $oPdf->Cell( 15, 4, 'Material:', 0, 0, "L" );
      $oPdf->SetFont( 'courier', '', 7 );
      $oPdf->Cell( 80, 4, $oMaterialColeta->material_coleta, 0, 0, "L" );
      $oPdf->SetFont( 'courier', 'b', 7 );
      $oPdf->Cell( 15, 4, 'Método:', 0, 0, "L" );
      $oPdf->SetFont( 'courier', '', 7 );
      $oPdf->Cell( 80, 4, $oMaterialColeta->metodo_coleta, 0, 1, "L" );
    }

    if ( !empty($oDadosExame->sObservacaoExame) ) {

      $oPdf->SetFont( 'courier', 'b', 7 );
      $sObservacaoExame       = $oDadosExame->sObservacaoExame;
      $iLinhasObservacaoExame = $oPdf->NbLines($oDadosEstrutura->iLarguraPadrao , $sObservacaoExame);
      $iYinicio               = $oPdf->GetY();
      $iAlturaObservacaoExame = ($iLinhasObservacaoExame * 4) + $iYinicio;

      /**
       * 252 é a Altura até o quadro do rodapé
       */
      if ( $iAlturaObservacaoExame > 252 ) {

        rodape( $oPdf, $oDadosEstrutura, $oDadosEstrutura->iSetor );
        montaCabecalho( $oPdf, $oDadosEstrutura );
        $oPdf->SetY( 50 );
      }
      $oPdf->SetFont( 'courier', 'b', 7 );
      $oPdf->Cell( $oDadosEstrutura->iLarguraPadrao, 4, "Observações do Exame:", 0, 1, "L" );
      $oPdf->SetFont( 'courier', '', 7 );
      $oPdf->MultiCell($oDadosEstrutura->iLarguraPadrao, 4, $sObservacaoExame, 0, 'J');

    }

    $oPdf->Ln();
    $oPdf->Ln();
  }
}

/**
 * *********************************************************
 * Monta o rodapé da página com a assinatura do profissional
 * @param scpdf $oPdf
 * @param $oDadosEstrutura
 * *********************************************************
 */
function rodape( scpdf $oPdf, $oDadosEstrutura, $iSetor ) {

  $oPdf->SetXY( 10, 250 );
  $oPdf->Rect( 8, 256, 192, 25 );

  $oPdf->SetXY( 14, 260 );
  $oPdf->SetFont( 'arial', '', 9 );

  $oDaoUsuarioLogado     = new cl_lab_labsetor();
  $sCamposUsuarioLogado  = " la47_i_login ";
  $sWhereUsuarioLogado   = " la21_i_requisicao = {$oDadosEstrutura->iRequisicao} and la24_i_setor = {$iSetor} ";
  $sSqlUsuarioLogado     = $oDaoUsuarioLogado->sql_query_cgm_lab_setor( null, $sCamposUsuarioLogado, null, $sWhereUsuarioLogado );
  $rsUsuarioLogado       = db_query( $sSqlUsuarioLogado );

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
      $oPdf->Cell( $oDadosEstrutura->iLarguraPadrao, $oDadosEstrutura->iAlturaPadrao, $sProfissional, 0, 1, "L" );

      $oPdf->Ln( 2 );
      $oPdf->SetX( 14 );
      $sOrgaoClasse = "Órgão Classe: {$oDadosAssinatura->orgao_classe}";
      $oPdf->Cell( $oDadosEstrutura->iLarguraPadrao, $oDadosEstrutura->iAlturaPadrao, $sOrgaoClasse, 0, 1, "L" );

      $sArquivo = "tmp/" . $oDadosAssinatura->la24_c_nomearq;

      db_query("begin");
      pg_loexport( $oDadosAssinatura->assinatura, $sArquivo );
      db_query("end");

      $oPdf->Image( $sArquivo, 170, 260, 15 );
    }

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
    $oPdf->Cell($oDadosEstrutura->iLarguraPadrao, $oDadosEstrutura->iAlturaPadrao, $sEndereço, 0, 1, "C");
    $oPdf->Cell($oDadosEstrutura->iLarguraPadrao, $oDadosEstrutura->iAlturaPadrao, $sContato,  0, 1, "C");
  }
}

function montaCabecalho( $oPdf, $oDadosEstrutura ) {
  $oPdf->AddPage();

  if ( !isset($_SESSION['DB_coddepto']) || empty($_SESSION['DB_coddepto']) ) {
    return;
  }

  try{

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
    $oPdf->Cell( $iLarguraDescricao, 4, $oDadosEstrutura->iRequisicao, 0, 1, "L" );

    $oPdf->SetX(120);
    $oPdf->SetFont("arial","B", 7);
    $oPdf->Cell( $iLarguraLabel, 4, "Paciente:", 0, 0, "L" );
    $oPdf->SetFont("arial","", 7);
    $oPdf->MultiCell( $iLarguraDescricao, 4, $oDadosEstrutura->oSolicitante->sNome, 0, "L" );

    $oPdf->SetX(120);
    $oPdf->SetFont("arial","B", 7);
    $oPdf->Cell( $iLarguraLabel, 4, "Idade:", 0, 0, "L" );
    $oPdf->SetFont("arial","", 7);
    $oPdf->Cell( $iLarguraDescricao, 4, $oDadosEstrutura->oSolicitante->iIdade, 0, 1, "L" );

    $oPdf->SetX(120);
    $oPdf->SetFont("arial","B", 7);
    $oPdf->Cell( $iLarguraLabel, 4, "Sexo:", 0, 0, "L" );
    $oPdf->SetFont("arial","", 7);
    $oPdf->Cell( $iLarguraDescricao, 4, $aSexo[$oDadosEstrutura->oSolicitante->sSexo], 0, 1, "L" );

    $oPdf->SetX(120);
    $oPdf->SetFont("arial","B", 7);
    $oPdf->Cell( $iLarguraLabel, 4, "Médico:", 0, 0, "L" );
    $oPdf->SetFont("arial","", 7);
    $oPdf->MultiCell( $iLarguraDescricao, 4, $oDadosEstrutura->oSolicitante->sMedico, 0, "L" );

    $oPdf->SetX(120);
    $oPdf->SetFont("arial","B", 7);
    $oPdf->Cell( $iLarguraLabel, 4, "Convênio:", 0, 0, "L" );
    $oPdf->SetFont("arial","", 7);
    $oPdf->Cell( $iLarguraDescricao, 4, "SUS", 0, 1, "L" );

    $oPdf->Line( 10, 45, 200, 45 );
  } catch( Exception $oErro ) {

    db_msgbox($oErro->getMessage());
    db_redireciona("lab4_emissaoresult001.php");
  }
}

$oPdf->Output( );
