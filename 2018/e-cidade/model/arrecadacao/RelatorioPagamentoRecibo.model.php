<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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

/**
 * Classe que gera o relat�rio da consulta de pagamentos por recibo
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 */
class RelatorioPagamentoRecibo {

  /**
   * Constantes que representam os valores num�ricos de cada n�vel
   */
  const NIVEL_UM   = 1;
  const NIVEL_DOIS = 2;

  /**
   * Array com o tipo de relat�rio para cada n�vel
   */
  private static $aTipo = array(
    self::NIVEL_UM   => PDFDocument::PRINT_LANDSCAPE,
    self::NIVEL_DOIS => PDFDocument::PRINT_LANDSCAPE
  );

  /**
   * Array com os cabe�alho dos relat�rios de cada n�vel
   */
  private static $aCabecalho = array(
    self::NIVEL_UM   => array( "C�d. Arrecada��o",
                               "Tipo",
                               "Tipo D�bito",
                               "Vencimento",
                               "Pagamento",
                               "Valor"),

    self::NIVEL_DOIS => array( "Numpre",
                               "Parcela",
                               "Total",
                               "Tipo",
                               "Tipo de D�bito",
                               "Receita",
                               "Descri��o",
                               "Vencimento",
                               "Pagamento",
                               "Efetiva��o",
                               "Valor" )
  );

  /**
   * Array com as larguras de cada coluna dos relat�rios de acordo com os n�veis
   */
  private static $aLargura = array(
    self::NIVEL_UM   => array( 10, 10, 40, 20, 10, 10 ),
    self::NIVEL_DOIS => array( 6, 4, 4, 4, 20, 5, 25, 8, 8, 8, 8 )
  );

  /**
   * Array com os alinhamentos do relat�rio de cada n�vel
   */
  private static $aAlinhamento = array(
    self::NIVEL_UM   => array( PDFDocument::ALIGN_CENTER,
                               PDFDocument::ALIGN_CENTER,
                               PDFDocument::ALIGN_LEFT,
                               PDFDocument::ALIGN_CENTER,
                               PDFDocument::ALIGN_CENTER,
                               PDFDocument::ALIGN_RIGHT ),

    self::NIVEL_DOIS => array( PDFDocument::ALIGN_CENTER,
                               PDFDocument::ALIGN_CENTER,
                               PDFDocument::ALIGN_CENTER,
                               PDFDocument::ALIGN_CENTER,
                               PDFDocument::ALIGN_LEFT,
                               PDFDocument::ALIGN_CENTER,
                               PDFDocument::ALIGN_LEFT,
                               PDFDocument::ALIGN_CENTER,
                               PDFDocument::ALIGN_CENTER,
                               PDFDocument::ALIGN_CENTER,
                               PDFDocument::ALIGN_RIGHT )
  );

  /**
   * Array com as formata��es de cada coluna do relat�rio por n�vel
   */
  private static $aFormatacao = array(
    self::NIVEL_UM   => array( false,
                               false,
                               false,
                               PDFTable::FORMAT_DATE,
                               PDFTable::FORMAT_DATE,
                               PDFTable::FORMAT_NUMERIC ),

    self::NIVEL_DOIS => array( false,
                               false,
                               false,
                               false,
                               false,
                               false,
                               false,
                               PDFTable::FORMAT_DATE,
                               PDFTable::FORMAT_DATE,
                               PDFTable::FORMAT_DATE,
                               PDFTable::FORMAT_NUMERIC)
  );

  /**
   * Atributo que armazena o n�vel do relat�rio
   * Este n�vel que determinar� o formato e os tipos de dados do relat�rio
   */
  private $iNivel;

  /**
   * Atributo que conter� os dados que ser�o impresso no relat�rio
   */
  private $aDados = array();

  /**
   * Alteramos o n�vel do relat�rio
   * @param  integer $iNivel
   * @throws ParameterException
   */
  public function setNivel( $iNivel ) {

    /**
     * Colocamos os n�veis em um array para facilitar a valida��o
     */
    $aNiveis = array(
      self::NIVEL_UM,
      self::NIVEL_DOIS
    );

    /**
     * Validamos o n�vel informado para garantir que este � um dos n�veis suportados pela classe
     */
    if ( !in_array($iNivel, $aNiveis) ) {
      throw new ParameterException("N�vel para o relat�rio de pagamentos � inv�lido!");
    }

    $this->iNivel = $iNivel;
  }

  /**
   * Fun��o que busca o n�vel definido para o relat�rio
   * @return interger   [n�vel]
   */
  public function getNivel() {
    return $this->iNivel;
  }

  /**
   * Fun��o que alterar� os dados que ser�o inseridos no relat�rio
   * @param array $aDados
   */
  public function setDados($aDados) {
    $this->aDados = $aDados;
  }

  /**
   * Buscamos as informa��o do relat�rio
   * @return array
   */
  public function getDados() {
    return $this->aDados;
  }

  /**
   * Fun��o respons�vel por montar o relat�rio
   * @param  object   par�metro da consulta
   * @return string   caminho do arquivo do relat�rio
   * @throws ParameterException, FileException
   */
  public function montarRelatorio( $oParametros ) {

    if ( empty($this->aDados) ) {
      throw new ParameterException("N�o h� nenhum dado para ser mostrado no relat�rio.");
    }
    /**
     * Criamos o objeto do relat�rio com o tipo de acordo com o n�vel definido
     */
    $oRelatorio = new PDFTable( self::$aTipo[$this->iNivel] );
    $oRelatorio->setPercentWidth(true);
    $oRelatorio->setLineHeigth(5);

    /**
     * Adicionamos os cabe�alho das colunas, larguras e alinhamentos ao relat�rio de acordo com o nivel definido
     */
    $oRelatorio->setHeaders     ( self::$aCabecalho  [$this->iNivel] );
    $oRelatorio->setColumnsWidth( self::$aLargura    [$this->iNivel] );
    $oRelatorio->setColumnsAlign( self::$aAlinhamento[$this->iNivel] );

    /**
     * Inserimos os dados de pagamentos de recibos ao relat�rio
     */
    foreach ( $this->aDados as $iIndice => $aDados ) {

      /**
       * For�amos para que os dados inseridos sejam array e n�o objeto
       */
      $aDados = (array) $aDados;

      /**
       * Removemos o index codigo e numpre, pois estes n�o ser�o mostrados no relat�rio
       */
      if ( isset($aDados['codigo']) ) {
        unset($aDados['codigo']);
      }

      if ( isset($aDados['tipodebito'])) {
        unset($aDados['tipodebito']);
      }

      if(isset($aDados['codigo_order'])){
        unset($aDados['codigo_order']);
      }

      if(isset($aDados['abatimento'])){
        unset($aDados['abatimento']);
      }
      /**
       * Passamos somente os valores do array, para que a classe n�o se perca na hora de montar o pdf
       */
      $oRelatorio->addLineInformation(array_values($aDados));
    }

    if($this->iNivel == self::NIVEL_UM){

      $oRelatorio->addFormatting(3, PDFTable::FORMAT_DATE);
      $oRelatorio->addFormatting(4, PDFTable::FORMAT_DATE);
      $oRelatorio->addFormatting(5, PDFTable::FORMAT_NUMERIC);
    }

    if($this->iNivel == self::NIVEL_DOIS){

      $oRelatorio->addFormatting(7,  PDFTable::FORMAT_DATE);
      $oRelatorio->addFormatting(8,  PDFTable::FORMAT_DATE);
      $oRelatorio->addFormatting(9,  PDFTable::FORMAT_DATE);
      $oRelatorio->addFormatting(10, PDFTable::FORMAT_NUMERIC);
    }

    /**
     * Criamos um objeto PDFDocument para que seja poss�vel criar o arquivo pdf e retornar o seu path somente
     */
    $oPdfDocument = new PDFDocument( self::$aTipo[$this->iNivel] );

    /**
     * Criamos uma descri��o para ser adicionado ao cabe�alho superior do relat�rio
     */
    $oPdfDocument->addHeaderDescription("Relatorio de Boletos Pagos");

    /**
     * Adicionamos os dados de filtros utilizados no cabe�alho do relat�rio
     */
    if ( !empty( $oParametros->iCgm ) ) {
      $oPdfDocument->addHeaderDescription("CGM: {$oParametros->iCgm}");
    }

    if ( !empty( $oParametros->iMatric ) ) {
      $oPdfDocument->addHeaderDescription("Matr�cula: {$oParametros->iMatric}");
    }

    if ( !empty( $oParametros->iInscr ) ) {
      $oPdfDocument->addHeaderDescription("Inscri��o: {$oParametros->iInscr}");
    }

    if ( !empty( $oParametros->sDataInicio ) ) {
      $oPdfDocument->addHeaderDescription("Data In�cio: {$oParametros->sDataInicio}");
    }

    if ( !empty( $oParametros->sDataFim ) ) {
      $oPdfDocument->addHeaderDescription("Data Fim: {$oParametros->sDataFim}");
    }

    if($this->iNivel == self::NIVEL_DOIS){

      if ( !empty( $oParametros->iCodigoArrecadacao ) ) {
        $oPdfDocument->addHeaderDescription("C�digo de Arrecada��o: {$oParametros->iCodigoArrecadacao}");
      } else if ( !empty( $oParametros->iCodigo ) ) {
        $oPdfDocument->addHeaderDescription("C�digo de Arrecada��o: {$oParametros->iCodigo}");
      }
    }

    if ( !empty( $oParametros->iTipoDebito ) ) {
      $oPdfDocument->addHeaderDescription("Tipo de Debito: {$oParametros->iTipoDebito}");
    }

    $oPdfDocument->SetFillColor(235);
    $oPdfDocument->setFontSize(8);
    $oPdfDocument->open();

    $oRelatorio->printOut($oPdfDocument, false);

    /**
     * Criamos o arquivo com o relat�rio
     */
    $sNomeArquivo      = "pagamentos_recibos_" . time();
    $sArquivoRelatorio = $oPdfDocument->savePDF($sNomeArquivo);

    /**
     * Validamos se o arquivo foi criado corretamente
     */
    if ( !file_exists($sArquivoRelatorio) ) {
      throw new FileException("Erro ao gerar o arquivo do relat�rio.");
    }

    return $sArquivoRelatorio;
  }
}
