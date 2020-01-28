<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("std/DBDate.php"));

$oGet            = db_utils::postMemory($_GET);
$aFiltroSituacao = array();
$aSituacoes      = array();

try {

  /**
   * Valida quais checkbox foram marcados, para filtrar pela situação
   */
  if ($oGet->lColetado == 'true') {

    $aFiltroSituacao[] = " la21_c_situacao ='" . RequisicaoExame::COLETADO ."'";
    $aSituacoes[]      = "Coletado";
  }

  if ($oGet->lConfirmado == 'true') {

    $aFiltroSituacao[] = "la21_c_situacao = '" .RequisicaoExame::CONFERIDO ."'";
    $aSituacoes[]      = "Conferido";
  }
  if ($oGet->lAutorizado== 'true') {

    $aFiltroSituacao[] = "la21_c_situacao = '".RequisicaoExame::AUTORIZADO ."'";
    $aSituacoes[]      = "Autorizado";
  }

  if ($oGet->lEntregue == 'true') {

    $aFiltroSituacao[] = " la21_c_situacao = '".RequisicaoExame::ENTREGUE . "'";
    $aSituacoes[]      = "Entregue";
  }

  $aWhere       = array();
  $sPeriodo     = "";
  $sLaboratorio = "";
  $sSetor       = "";
  $sExame       = "";
  $sRequisicao  = "";
  $sSituacao    = "";

  if ( count($aFiltroSituacao) > 0 ) {

    $sFiltrosSituacao = implode(" or ", $aFiltroSituacao);
    $aWhere[]         = " ({$sFiltrosSituacao})";
    $sSituacao        = implode( ", ", $aSituacoes );
  }

  $lInformouRequisicao = false;
  if ( !empty($oGet->iRequisicao) ) {

    $lInformouRequisicao = true;
    $aWhere[] = " la22_i_codigo = {$oGet->iRequisicao}";
    $sRequisicao = $oGet->iRequisicao;
  }

  /**
   * Quando requisição estiver informada, não devemos olhar os outros filtros
   */
  if ( !$lInformouRequisicao ) {

    if ( !empty($oGet->dtInicial) ) {

      $oDtInicial  = new DBDate($oGet->dtInicial);
      $aWhere[]    = " la22_d_data >= '" . $oDtInicial->getDate() ."'";
      $sPeriodo   .= $oGet->dtInicial;
    }

    if ( !empty($oGet->dtFinal) ) {

      $oDtFinal    = new DBDate($oGet->dtFinal);
      $aWhere[]    = " la22_d_data <= '" . $oDtFinal->getDate() . "'";
      $sPeriodo   .= " até " . $oGet->dtFinal;
    }

    if ( !empty($oGet->iLaboratorio) ) {

      $aWhere[]     = " la02_i_codigo = {$oGet->iLaboratorio} ";
      $oLaboratorio = new Laboratorio( $oGet->iLaboratorio );
      $sLaboratorio = substr( $oLaboratorio->getDescricao(), 0, 31 );
    }

    if ( !empty($oGet->iSetor) ) {

      $aWhere[] = " la24_i_setor = {$oGet->iSetor} " ;
      $oSetor   = new Setor( $oGet->iSetor );
      $sSetor   = substr( $oSetor->getDescricao(), 0, 35 );
    }

    if ( !empty($oGet->iExame) ) {

      $aWhere[] = " la08_i_codigo = {$oGet->iExame} " ;
      $oExame   = new Exame( $oGet->iExame );
      $sExame   = substr( $oExame->getNome(), 0, 34 );
    }
  }

  $sWhere           = implode( " and ", $aWhere) ;
  $oDaoLabRequiItem = new cl_lab_requiitem();

  $sCampos    = " la22_i_codigo, ";
  $sCampos   .= " la02_i_codigo, ";
  $sCampos   .= " trim(la02_c_descr) as laboratorio, ";
  $sCampos   .= " la24_i_codigo, ";
  $sCampos   .= " trim(la23_c_descr) as setor, ";
  $sCampos   .= " la21_d_data        as data_requisicao, ";
  $sCampos   .= " trim(z01_v_nome)   as paciente,  ";
  $sCampos   .= " la08_i_codigo, ";
  $sCampos   .= " trim(la08_c_descr) as exame, ";
  $sCampos   .= " trim(la15_c_descr) as material, ";
  $sCampos   .= " la32_d_entrega     as data_coleta, ";
  $sCampos   .= " la52_d_data        as data_resultado, ";
  $sCampos   .= " la31_d_data        as data_entrega,";
  $sCampos   .= " la31_retiradopor   as retirado_por,";
  $sCampos   .= " la31_c_documento   as documento, ";
  $sCampos   .= " (select nome from db_usuarios where id_usuario = lab_entrega.la31_i_usuario) as usuario_entrega";
  $sOrdem     = " la22_i_codigo ";
  $sSqlExames = $oDaoLabRequiItem->sql_query_nova(null, $sCampos, $sOrdem, $sWhere);
  $rsExames   = db_query($sSqlExames);

  if (!$rsExames || pg_num_rows($rsExames) == 0) {
    throw new DBException("Nenhum registro encontrado.");
  }

  $iLinha = pg_num_rows($rsExames);
  $aDados = array();

  /**
   * Array com os campos que devem ser apresentados no cabeçalho.
   * Guarda um objeto com as informações de cada um , indexando pelo tipo
   * array['exame']     - Padrão
   *      ['material']  - Padrão
   *      ['retirado']  - De acordo com o filtro de identificação
   *      ['documento'] - De acordo com o filtro de identificção
   *      ['login']     - De acordo com o filtro de login
   *      ['coleta']    - Padrão
   *      ['resultado'] - Padrão
   *      ['entrega']   - Padrão
   */
  $aCabecalho = array();

  for ($i = 0; $i < $iLinha; $i++) {

    $oExame = db_utils::fieldsMemory($rsExames, $i);
    $iSetor = $oExame->la24_i_codigo;
    $iRequisicao = $oExame->la22_i_codigo;

    /**
     * Objeto com os valores padrões referente a coluna dos exames
     */
    $oCabecalhoExame           = new stdClass();
    $oCabecalhoExame->iLargura = 85;
    $oCabecalhoExame->sTitulo  = "Exame";
    $oCabecalhoExame->iSubStr  = 65;
    $oCabecalhoExame->sBorda   = "R";
    $aCabecalho["exame"]       = $oCabecalhoExame;

    /**
     * Objeto com os valores padrões referente a coluna dos materiais
     */
    $oCabecalhoMaterial           = new stdClass();
    $oCabecalhoMaterial->iLargura = 61;
    $oCabecalhoMaterial->sTitulo  = "Material";
    $oCabecalhoMaterial->iSubStr  = 45;
    $oCabecalhoMaterial->sBorda   = "R";
    $aCabecalho["material"]       = $oCabecalhoMaterial;

    /**
     * Adiciona um setor
     */
    if ( !array_key_exists($iSetor, $aDados) ) {

      $oDadosSetor               = new stdClass();
      $oDadosSetor->sSetor       = $oExame->setor;
      $oDadosSetor->sLaboratorio = $oExame->laboratorio;
      $oDadosSetor->aRequisicoes = array();
      $aDados[$iSetor]           = $oDadosSetor;
    }

    /**
     * Adiciona uma requisição
     */
    if ( !array_key_exists( $iRequisicao, $aDados[$iSetor]->aRequisicoes) ) {

      $oRequisicao            = new stdClass();
      $oRequisicao->iCodigo   = $iRequisicao;

      $oData                  = new DBDate($oExame->data_requisicao);
      $oRequisicao->sData     = $oData->getDate( DBDate::DATA_PTBR );
      $oRequisicao->sPaciente = $oExame->paciente;
      $oRequisicao->aExames   = array();

      $aDados[$iSetor]->aRequisicoes[$iRequisicao] = $oRequisicao;
    }

    /**
     * Array com as informações dos exames, indexado pelos campos a serem impressos
     */
    $aExamesRequisicao              = array();
    $aExamesRequisicao['exame']     = $oExame->exame;
    $aExamesRequisicao['material']  = $oExame->material;

    /**
     * Caso tenha sido selecionado para exibir a identificação de quem retirou, criamos os objetos com os valores para
     * Retirado e Documento, incrementando o array das informações do exame, e recalculando a largura das colunas
     */
    if ( $oGet->lExibirIdentificacao ) {

      /**
       * Objeto com os valores padrões referente a coluna de quem retirou
       */
      $oCabecalhoRetirado           = new stdClass();
      $oCabecalhoRetirado->iLargura = 37;
      $oCabecalhoRetirado->sTitulo  = "Retirado";
      $oCabecalhoRetirado->iSubStr  = 27;
      $oCabecalhoRetirado->sBorda   = "R";
      $aCabecalho["retirado"]       = $oCabecalhoRetirado;

      /**
       * Objeto com os valores padrões referente a coluna do documento de quem retirou
       */
      $oCabecalhoDocumento           = new stdClass();
      $oCabecalhoDocumento->iLargura = 23;
      $oCabecalhoDocumento->sTitulo  = "Documento";
      $oCabecalhoDocumento->iSubStr  = 17;
      $oCabecalhoDocumento->sBorda   = "R";
      $aCabecalho["documento"]       = $oCabecalhoDocumento;

      $aCabecalho['exame']->iLargura    -= $aCabecalho['retirado']->iLargura;
      $aCabecalho['material']->iLargura -= $aCabecalho['documento']->iLargura;

      $aCabecalho['exame']->iSubStr    -= 29;
      $aCabecalho['material']->iSubStr -= 17;

      $aExamesRequisicao['retirado']  = $oExame->retirado_por;
      $aExamesRequisicao['documento'] = $oExame->documento;
    }

    /**
     * Caso tenha sido selecionado para exibir o login de quem incluiu a retirada, criamos um novo objeto com os valores
     * para Login, incrementando o array das informações do exame, e recalculando a largura das colunas
     */
    if ( $oGet->lExibirLogin ) {

      /**
       * Objeto com os valores padrões referente a coluna do login de quem incluiu a retirada
       */
      $oCabecalhoLogin           = new stdClass();
      $oCabecalhoLogin->iLargura = 30;
      $oCabecalhoLogin->sTitulo  = "Login";
      $oCabecalhoLogin->iSubStr  = 21;
      $oCabecalhoLogin->sBorda   = "R";
      $aCabecalho["login"]       = $oCabecalhoLogin;

      $aCabecalho['exame']->iLargura    -= $aCabecalho['login']->iLargura / 2;
      $aCabecalho['material']->iLargura -= $aCabecalho['login']->iLargura / 2;

      $aCabecalho['exame']->iSubStr    -= 12;
      $aCabecalho['material']->iSubStr -= 12;

      $aExamesRequisicao['login'] = $oExame->usuario_entrega;
    }

    /**
     * Objetos com as informações referentes a Data de Coleta, Data do Resultado e Data da Entrega
     */
    $oCabecalhoColeta           = new stdClass();
    $oCabecalhoColeta->iLargura = 14;
    $oCabecalhoColeta->sTitulo  = "Coleta";
    $oCabecalhoColeta->iSubStr  = 10;
    $oCabecalhoColeta->sBorda   = "R";

    $oCabecalhoResultado           = new stdClass();
    $oCabecalhoResultado->iLargura = 18;
    $oCabecalhoResultado->sTitulo  = "Resultado";
    $oCabecalhoResultado->iSubStr  = 10;
    $oCabecalhoResultado->sBorda   = "R";

    $oCabecalhoEntrega           = new stdClass();
    $oCabecalhoEntrega->iLargura = 14;
    $oCabecalhoEntrega->sTitulo  = "Entrega";
    $oCabecalhoEntrega->iSubStr  = 10;
    $oCabecalhoEntrega->sBorda   = "";

    $aExamesRequisicao['coleta']    = null;
    $aExamesRequisicao['resultado'] = null;
    $aExamesRequisicao['entrega']   = null;

    if ( !empty($oExame->data_coleta) ) {

      $oDataColeta                 = new DBDate($oExame->data_coleta);
      $aExamesRequisicao['coleta'] = $oDataColeta->getDate( DBDate::DATA_PTBR );
    }

    if ( !empty($oExame->data_resultado) ) {

      $oDataResultado                 = new DBDate($oExame->data_resultado);
      $aExamesRequisicao['resultado'] = $oDataResultado->getDate( DBDate::DATA_PTBR );
    }

    if ( !empty($oExame->data_entrega) ) {

      $oDataEntrega                 = new DBDate($oExame->data_entrega);
      $aExamesRequisicao['entrega'] = $oDataEntrega->getDate( DBDate::DATA_PTBR );
    }

    $aCabecalho["coleta"]    = $oCabecalhoColeta;
    $aCabecalho["resultado"] = $oCabecalhoResultado;
    $aCabecalho["entrega"]   = $oCabecalhoEntrega;

    $aDados[$iSetor]->aRequisicoes[$iRequisicao]->aExames[] = $aExamesRequisicao;
  }

  $oPdf = new PDF();
  $oPdf->Open();

  /**
   * Cabeçalhos padrão
   */
  $head1 = "Exames Requisitados";
  $head3 = "Período: {$sPeriodo}";
  $head4 = "Laboratório: {$sLaboratorio}";
  $head5 = "Setor: {$sSetor}";
  $head6 = "Exame: {$sExame}";
  $head7 = "Requisição: {$sRequisicao}";
  $head8 = "Situação: {$sSituacao}";
  $oPdf->AddPage();

  $iAlturaPadrao  = 5;
  $iLarguraPadrao = 192;

  /**
   * Percorre os dados por setor, imprimindo o nome do setor
   */
  foreach($aDados as $oDadosSetor ) {

    $oPdf->SetFont( 'arial', 'b', 7 );
    $oPdf->SetFillColor(225, 225, 225);

    $sSetor = "Setor: {$oDadosSetor->sSetor}";
    $oPdf->Cell($iLarguraPadrao, $iAlturaPadrao, $sSetor, "TB", 1, "L", 1);

    $oPdf->SetFillColor(240, 240, 240);

    /**
     * Percorre as requisições do setor, imprimindo seu código, nome do paciente e a data da requisição
     */
    foreach( $oDadosSetor->aRequisicoes as $oRequisicao ) {

      $oPdf->SetFont( 'arial', 'b', 7 );
      $sRequisicao  = "Requisição: {$oRequisicao->iCodigo} - Paciente: " . substr($oRequisicao->sPaciente, 0 , 63);
      $sRequisicao .= " - Data: {$oRequisicao->sData}";
      $oPdf->Cell($iLarguraPadrao, $iAlturaPadrao, $sRequisicao, "TB", 1, "L", 1);

      /**
       * Percorre os cabeçalhos a serem impressos, conforme as propriedades
       */
      foreach( $aCabecalho as $oCabecalho ) {
        $oPdf->Cell( $oCabecalho->iLargura, $iAlturaPadrao, $oCabecalho->sTitulo, $oCabecalho->sBorda, 0, "L");
      }

      $oPdf->Line(10, $oPdf->GetY() + $iAlturaPadrao, 202, $oPdf->GetY() + $iAlturaPadrao );

      $oPdf->Ln();
      $oPdf->SetFont( 'arial', '', 5 );

      /**
       * Percorre os exames da requisição, preenchendo conforme suas propriedades
       */
      foreach( $oRequisicao->aExames as $aExame ) {

        foreach( $aExame as $sIndice => $sExame ) {
          $oPdf->Cell(
                       $aCabecalho[$sIndice]->iLargura,
                       $iAlturaPadrao,
                       substr( $sExame, 0 , $aCabecalho[$sIndice]->iSubStr ),
                       $aCabecalho[$sIndice]->sBorda,
                       0,
                       "L"
                     );
        }

        $oPdf->Ln();
      }
    }
  }

  /**
   * Totalizador de exames encontrados
   */
  $oPdf->Ln( 5 );
  $oPdf->SetFont( 'arial', 'b', 7 );
  $oPdf->Cell( $iLarguraPadrao, $iAlturaPadrao, "TOTAL DE EXAMES DO PRESTADOR: {$iLinha}", 0, 1, "C" );

  $oPdf->Output();

} catch (Exception $oErro) {

  $sMensagem = trim( preg_replace('/\s+/', ' ', $oErro->getMessage() ));
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMensagem}");
}