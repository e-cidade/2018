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
 * Classe que gera o relatório da consulta de pagamentos por recibo
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 */
class RelatorioPagamentoRecibo {

  /**
   * Constantes que representam os valores numéricos de cada nível
   */
  const NIVEL_UM   = 1;
  const NIVEL_DOIS = 2;

  /**
   * Array com o tipo de relatório para cada nível
   */
  private static $aTipo = array(
    self::NIVEL_UM   => PDFDocument::PRINT_LANDSCAPE,
    self::NIVEL_DOIS => PDFDocument::PRINT_LANDSCAPE
  );

  /**
   * Array com os cabeçalho dos relatórios de cada nível
   */
  private static $aCabecalho = array(
    self::NIVEL_UM   => array( "Cód. Arrecadação",
                               "Tipo",
                               "Tipo Débito",
                               "Vencimento",
                               "Pagamento",
                               "Valor"),

    self::NIVEL_DOIS => array( "Numpre",
                               "Parcela",
                               "Total",
                               "Tipo",
                               "Tipo de Débito",
                               "Receita",
                               "Descrição",
                               "Vencimento",
                               "Pagamento",
                               "Efetivação",
                               "Valor" )
  );

  /**
   * Array com as larguras de cada coluna dos relatórios de acordo com os níveis
   */
  private static $aLargura = array(
    self::NIVEL_UM   => array( 10, 10, 40, 20, 10, 10 ),
    self::NIVEL_DOIS => array( 6, 4, 4, 4, 20, 5, 25, 8, 8, 8, 8 )
  );

  /**
   * Array com os alinhamentos do relatório de cada nível
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
   * Array com as formatações de cada coluna do relatório por nível
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
   * Atributo que armazena o nível do relatório
   * Este nível que determinará o formato e os tipos de dados do relatório
   */
  private $iNivel;

  /**
   * Atributo que conterá os dados que serão impresso no relatório
   */
  private $aDados = array();

  /**
   * Alteramos o nível do relatório
   * @param  integer $iNivel
   * @throws ParameterException
   */
  public function setNivel( $iNivel ) {

    /**
     * Colocamos os níveis em um array para facilitar a validação
     */
    $aNiveis = array(
      self::NIVEL_UM,
      self::NIVEL_DOIS
    );

    /**
     * Validamos o nível informado para garantir que este é um dos níveis suportados pela classe
     */
    if ( !in_array($iNivel, $aNiveis) ) {
      throw new ParameterException("Nível para o relatório de pagamentos é inválido!");
    }

    $this->iNivel = $iNivel;
  }

  /**
   * Função que busca o nível definido para o relatório
   * @return interger   [nível]
   */
  public function getNivel() {
    return $this->iNivel;
  }

  /**
   * Função que alterará os dados que serão inseridos no relatório
   * @param array $aDados
   */
  public function setDados($aDados) {
    $this->aDados = $aDados;
  }

  /**
   * Buscamos as informação do relatório
   * @return array
   */
  public function getDados() {
    return $this->aDados;
  }

  /**
   * Função responsável por montar o relatório
   * @param  object   parâmetro da consulta
   * @return string   caminho do arquivo do relatório
   * @throws ParameterException, FileException
   */
  public function montarRelatorio( $oParametros ) {

    if ( empty($this->aDados) ) {
      throw new ParameterException("Não há nenhum dado para ser mostrado no relatório.");
    }
    /**
     * Criamos o objeto do relatório com o tipo de acordo com o nível definido
     */
    $oRelatorio = new PDFTable( self::$aTipo[$this->iNivel] );
    $oRelatorio->setPercentWidth(true);
    $oRelatorio->setLineHeigth(5);

    /**
     * Adicionamos os cabeçalho das colunas, larguras e alinhamentos ao relatório de acordo com o nivel definido
     */
    $oRelatorio->setHeaders     ( self::$aCabecalho  [$this->iNivel] );
    $oRelatorio->setColumnsWidth( self::$aLargura    [$this->iNivel] );
    $oRelatorio->setColumnsAlign( self::$aAlinhamento[$this->iNivel] );

    /**
     * Inserimos os dados de pagamentos de recibos ao relatório
     */
    foreach ( $this->aDados as $iIndice => $aDados ) {

      /**
       * Forçamos para que os dados inseridos sejam array e não objeto
       */
      $aDados = (array) $aDados;

      /**
       * Removemos o index codigo e numpre, pois estes não serão mostrados no relatório
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
       * Passamos somente os valores do array, para que a classe não se perca na hora de montar o pdf
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
     * Criamos um objeto PDFDocument para que seja possível criar o arquivo pdf e retornar o seu path somente
     */
    $oPdfDocument = new PDFDocument( self::$aTipo[$this->iNivel] );

    /**
     * Criamos uma descrição para ser adicionado ao cabeçalho superior do relatório
     */
    $oPdfDocument->addHeaderDescription("Relatorio de Boletos Pagos");

    /**
     * Adicionamos os dados de filtros utilizados no cabeçalho do relatório
     */
    if ( !empty( $oParametros->iCgm ) ) {
      $oPdfDocument->addHeaderDescription("CGM: {$oParametros->iCgm}");
    }

    if ( !empty( $oParametros->iMatric ) ) {
      $oPdfDocument->addHeaderDescription("Matrícula: {$oParametros->iMatric}");
    }

    if ( !empty( $oParametros->iInscr ) ) {
      $oPdfDocument->addHeaderDescription("Inscrição: {$oParametros->iInscr}");
    }

    if ( !empty( $oParametros->sDataInicio ) ) {
      $oPdfDocument->addHeaderDescription("Data Início: {$oParametros->sDataInicio}");
    }

    if ( !empty( $oParametros->sDataFim ) ) {
      $oPdfDocument->addHeaderDescription("Data Fim: {$oParametros->sDataFim}");
    }

    if($this->iNivel == self::NIVEL_DOIS){

      if ( !empty( $oParametros->iCodigoArrecadacao ) ) {
        $oPdfDocument->addHeaderDescription("Código de Arrecadação: {$oParametros->iCodigoArrecadacao}");
      } else if ( !empty( $oParametros->iCodigo ) ) {
        $oPdfDocument->addHeaderDescription("Código de Arrecadação: {$oParametros->iCodigo}");
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
     * Criamos o arquivo com o relatório
     */
    $sNomeArquivo      = "pagamentos_recibos_" . time();
    $sArquivoRelatorio = $oPdfDocument->savePDF($sNomeArquivo);

    /**
     * Validamos se o arquivo foi criado corretamente
     */
    if ( !file_exists($sArquivoRelatorio) ) {
      throw new FileException("Erro ao gerar o arquivo do relatório.");
    }

    return $sArquivoRelatorio;
  }
}
