<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada;

use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Registro;

class RegistroCollection implements \Iterator
{
  /**
   * @var resource
   */
  private $resource;

  /**
   * @var string
   */
  private $sSqlRecibos;

  /**
   * @var integer
   */
  private $iContadorGeral;

  /**
   * @var integer
   */
  private $iContadorParcial;

  /**
   * @var boolean
   */
  private $lStarted;

  /**
   * @var string
   */
  private $sCursorNome;

  public function __construct($sSqlRecibos)
  {
    $this->sSqlRecibos = $sSqlRecibos;
    $this->sCursorNome = "c_cobranca_registrada" . time();
    $this->lStarted = false;
    $this->iContadorGeral = 0;
    $this->iContadorParcial = 0;
  }

  public function current()
  {
    return $this->getRow($this->iContadorParcial);
  }

  public function key()
  {
    return $this->iContadorGeral;
  }

  public function next()
  {
    $this->iContadorGeral++;
    $this->iContadorParcial++;
  }

  public function rewind()
  {
    if (!pg_query("declare " . $this->sCursorNome . " cursor for " . $this->sSqlRecibos)) {
      throw new \Exception("Erro ao buscar os dados dos recibos.");
    }

    $this->lStarted = true;

    $this->iContadorGeral = 0;
    $this->iContadorParcial = 0;

    $this->fetchDatabase();
  }

  public function valid()
  {
    if (!$this->resource || $this->iContadorParcial >= pg_num_rows($this->resource)) {
      $this->fetchDatabase();
    }

    return ($this->resource && $this->iContadorParcial < pg_num_rows($this->resource));
  }

  private function fetchDatabase()
  {
    $this->resource = pg_query("fetch 70000 " . $this->sCursorNome);
    $this->iContadorParcial = 0;
  }

  /**
   * @param integer $iRow
   * @return Registro
   */
  private function getRow($iRow)
  {
    $oObject = pg_fetch_object($this->resource, $iRow);

    if (strlen($oObject->cpf_cnpj) == 11 || empty($oObject->cpf_cnpj)) {

      $oCgm = new \CgmFisico();
      $oCgm->setCpf($oObject->cpf_cnpj);
    } else {

      $oCgm = new \CgmJuridico();
      $oCgm->setCnpj($oObject->cpf_cnpj);
    }

    if (!empty($oObject->endereco_matricula)) {

      $oObject->endereco = substr($oObject->endereco_matricula, 0, 40);
      $oObject->numero = substr($oObject->endereco_matricula, 41, 10);
      $oObject->complemento = substr($oObject->endereco_matricula, 52, 20);
      $oObject->bairro = substr($oObject->endereco_matricula, 73, 40);
      $oObject->municipio = substr($oObject->endereco_matricula, 114, 40);
      $oObject->uf = substr($oObject->endereco_matricula, 155, 2);
      $oObject->cep = substr($oObject->endereco_matricula, 158, 8);
    }

    $oCgm->setNome(trim($oObject->nome));
    $oCgm->setLogradouro(trim($oObject->endereco));
    $oCgm->setNumero(trim($oObject->numero));
    $oCgm->setComplemento(trim($oObject->complemento));
    $oCgm->setBairro(trim($oObject->bairro));
    $oCgm->setMunicipio(trim($oObject->municipio));
    $oCgm->setUf(trim($oObject->uf));
    $oCgm->setCep(trim($oObject->cep));

    $oRegistro = new Registro();
    $oRegistro->setNumeroDocumento($oObject->k00_numnov);
    $oRegistro->setNossoNumero($oObject->nosso_numero);
    $oRegistro->setValor($oObject->valor_total);
    $oRegistro->setDataEmissao(new \DBDate($oObject->k138_data));
    $oRegistro->setDataVencimento(new \DBDate($oObject->k00_dtpaga));
    $oRegistro->setCgm($oCgm);

    $oDataJuros = new \DBDate(date('Y-m-d', strtotime("+2 days",strtotime($oObject->k00_dtpaga))));

    $oRegistro->setCodigoJuros(2);
    $oRegistro->setDataJuros($oDataJuros);
    $oRegistro->setTaxaJuros(0);
    $oRegistro->setCodigoDesconto(0);
    $oRegistro->setValorDesconto(0);

    return $oRegistro;
  }
}
