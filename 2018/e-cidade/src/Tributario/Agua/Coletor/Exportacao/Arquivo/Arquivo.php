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

namespace ECidade\Tributario\Agua\Coletor\Exportacao\Arquivo;

abstract class Arquivo {

  /**
   * @var string
   */
  protected $sNomeArquivo;

  /**
   * @var int
   */
  protected $iCodigoLayout;

  /**
   * @return int
   */
  public function getCodigoLayout() {
    return $this->iCodigoLayout;
  }

  /**
   * @return string
   */
  public function getNomeArquivo() {
    return $this->sNomeArquivo;
  }

  /**
   * @param int $iCodigoLayout
   */
  public function setCodigoLayout($iCodigoLayout) {
    $this->iCodigoLayout = $iCodigoLayout;
  }

  /**
   * @param string $sNomeArquivo
   */
  public function setNomeArquivo($sNomeArquivo) {
    $this->sNomeArquivo = $sNomeArquivo;
  }

  /**
   * @return \stdClass[]
   */
  public abstract function getDados();

  /**
   * Gera o arquivos de exportação
   * @return \File
   * @throws \Exception
   * @throws \ParameterException
   */
  public function gerar() {

    if (!$this->getNomeArquivo()) {
      throw new \ParameterException('Nome do arquivo não informado.');
    }

    if (!$this->getCodigoLayout()) {
      throw new \ParameterException('Código do Layout de Arquivo não informado.');
    }

    $sArquivo = "tmp/" . strtolower("{$this->getNomeArquivo()}.txt");
    if (file_exists($sArquivo)) {
      unlink($sArquivo);
    }

    $oLayout = new \db_layouttxt($this->getCodigoLayout(), $sArquivo);
    if ($oLayout->ocorreuErro()) {
      throw new \Exception($oLayout->getMensagemErro());
    }

    foreach ($this->getDados() as $oRegistro) {
      $oLayout->setByLineOfDBUtils($oRegistro, \db_layouttxt::TIPO_LINHA_REGISTRO);
    }

    $oLayout->fechaArquivo();

    return new \File($sArquivo);
  }
}