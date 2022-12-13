<?php
/**
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
class ArquivoSiprevEscritorXML  extends ArquivoSiprevEscritor {

  /**
   * Arquivos que utilizarão o elemento padrão que imprime a propriedade operacao separado
   * @var array
   */
  private $aArquivosRegistroOperacaoUnico = array(
    "servidores",
    "dependentes",
    "orgaos",
    "carreiras",
    "cargos",
    "pensionistas"
  );
  /**
   * Quantidade de Dados que serão utilizados para emissão do XML
   * @var array
   */
  private $aArquivosQuebraRegistros = array(
    "historicosFinanceiros"  => 1000,
    "vinculosFuncionaisRgps" => 1000,
    "vinculosFuncionaisRpps" => 1000,
  );

  public function __construct() {}

  /**
   * Transforma os dados passados para XML
   * @param $oArquivo
   * @return caminho do Arquivo
   */

  public function criarArquivo(ArquivoSiprevBase $oArquivo) {


    $fFinalizarArquivo = function($iNumeroArquivo, $oXmlWriter, $oArquivo) {
      $sNomeArquivo = 'tmp/'.$oArquivo->getNomeArquivo().'-'.$iNumeroArquivo.'.xml';
      $rsArquivoXML = fopen($sNomeArquivo, "w");
      fputs($rsArquivoXML, $oXmlWriter->outputMemory());
      fclose($rsArquivoXML);
      unset($oXmlWriter);
      return $sNomeArquivo;
    };


    $iNumeroArquivo = 0;
    $lExisteQuebra = isset($this->aArquivosQuebraRegistros[$oArquivo->getRegistro()]);

    if (!$lExisteQuebra) {

      $xml = $this->escreverArquivo($oArquivo, null, null);

      if (!$xml) {
        return array();
      }


      return array(
        $fFinalizarArquivo(++$iNumeroArquivo, $xml, $oArquivo)
      );
    }

    $arquivos = array();

    $iQuantidade = $this->aArquivosQuebraRegistros[$oArquivo->getRegistro()];
    $iPassagem   = 0;
    while($oXmlWriter = $this->escreverArquivo($oArquivo, $iQuantidade, ++$iPassagem)) {
      $arquivos[] = $fFinalizarArquivo(++$iNumeroArquivo, $oXmlWriter, $oArquivo);
    }
    return $arquivos;
  }



  public function escreverArquivo($oArquivo, $iQuantidadeRegistros, $iPassagem) {

    $oXmlWriter = new XMLWriter();
    $oXmlWriter->openMemory();
    $oXmlWriter->setIndent(true);
    $oXmlWriter->startDocument('1.0','ISO-8859-1',"yes");
    $oXmlWriter->startElementNs('ns2', 'siprev',"http://www.dataprev.gov.br/siprev");
    $oXmlWriter->endDtd();

    $oXmlWriter->startElement('ente');
    $oXmlWriter->writeAttribute("siafi",$oArquivo->getSiafi());
    $oXmlWriter->writeAttribute("cnpj",$oArquivo->getCnpj());
    $oXmlWriter->endElement();
    $dados= $oArquivo->getDados($iQuantidadeRegistros, $iPassagem);

    if (!$dados) {
      return false;
    }

    foreach ($dados as $oLinha) {

      /**
       * Verifica se o arquivo deve criar um elemento com o mesmo nome com o atributo operacao
       */
      if(in_array($oArquivo->getRegistro(), $this->aArquivosRegistroOperacaoUnico)) {

        $oXmlWriter->startElement($oArquivo->getRegistro());
        $oXmlWriter->writeAttribute("operacao", "I");
      }

      foreach ($oArquivo->getElementos() as $aElemento) {
        $this->escreveElemento($oLinha, $oXmlWriter, $aElemento);
      }

      if(in_array($oArquivo->getRegistro(), $this->aArquivosRegistroOperacaoUnico)) {
        $oXmlWriter->endElement();
      }
    }

    $oXmlWriter->endElement();
    return $oXmlWriter;
  }

  public function escreveElemento($oLinha, &$oXmlWriter, &$oElemento, $sNome = '') {

    if (empty($oLinha->$oElemento["nome"])) {
      return false;
    }

    if (is_array($linha = $oLinha->{$oElemento["nome"]})) {

      foreach($linha as $oDados) {

        $dadosManipulados = (object)array(
          $oElemento["nome"] => $oDados
        );
        $this->escreveElemento($dadosManipulados, $oXmlWriter, $oElemento, $sNome = '');
      }

      return;
    }

    $oXmlWriter->startElement($oElemento["nome"]);

    foreach ($oElemento["propriedades"] as $sPropriedade) {

      if (!is_array($sPropriedade)) {

        $sValor  = null;

        if (isset($oLinha->$oElemento["nome"]->$sPropriedade)) {
          $sValor = $oLinha->$oElemento["nome"]->$sPropriedade;
        }

        if ($sValor !== null) {
          $oXmlWriter->writeAttribute($sPropriedade, utf8_encode($sValor));
        }
      } else {
        $this->escreveElemento($oLinha->$oElemento["nome"], $oXmlWriter, $sPropriedade);
      }
    }

    $oXmlWriter->endElement();
  }
}
