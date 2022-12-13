<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Arrecadacao\EmissaoGeral\Registro;

use ECidade\Tributario\Arrecadacao\EmissaoGeral\EmissaoGeral;
use ECidade\Tributario\Arrecadacao\EmissaoGeral\RegistroCollection;
use ECidade\Tributario\Arrecadacao\EmissaoGeral\Registro\IPTU as RegistroIPTU;

class Repository
{

  /**
   * @var \cl_emissaogeralregistro
   */
  private $oDaoPadrao;

  /**
   * @var \cl_emissaogeralmatricula
   */
  private $oDaoMatricula;

  /**
   * @var \cl_emissaogeralinscricao
   */
  private $oDaoInscricao;

  public function __construct()
  {
    $this->oDaoPadrao = new \cl_emissaogeralregistro();
    $this->oDaoMatricula = new \cl_emissaogeralmatricula();
    $this->oDaoInscricao = new \cl_emissaogeralinscricao();
  }

  /**
   * Persiste os dados do registro
   *
   * @param Padrao $oRegistro
   * @throws \Exception
   * @return Padrao
   */
  public function add($oRegistro)
  {
    $this->oDaoPadrao->tr02_sequencial = null;
    $this->oDaoPadrao->tr02_emissaogeral = $oRegistro->getEmissao()->getCodigo();
    $this->oDaoPadrao->tr02_numcgm = $oRegistro->getCgm();
    $this->oDaoPadrao->tr02_numpre = $oRegistro->getNumpre();
    $this->oDaoPadrao->tr02_parcela = $oRegistro->getParcela();
    $this->oDaoPadrao->tr02_situacao = $oRegistro->getSituacao();

    $this->oDaoPadrao->incluir(null);

    if ($this->oDaoPadrao->erro_status == "2") {
      throw new \Exception($this->oDaoPadrao->erro_msg);
    }

    $oRegistro->setCodigo($this->oDaoPadrao->tr02_sequencial);

    /**
     * Salva a vinculação com a matricula
     */
    if ($oRegistro instanceof RegistroIPTU) {

      $this->oDaoMatricula->tr03_sequencial = null;
      $this->oDaoMatricula->tr03_emissaogeralregistro = $oRegistro->getCodigo();
      $this->oDaoMatricula->tr03_matric = $oRegistro->getMatricula();

      $this->oDaoMatricula->incluir(null);

      if ($this->oDaoMatricula->erro_status == "2") {
        throw new \Exception($this->oDaoMatricula->erro_msg);
      }
    }

    return $oRegistro;
  }


  /**
   * Retorna uma collection de registros para a emissão
   *
   * O campo ordem esta configurado para trazer os registros na mesma ordem escolhida no momento da geração desta emissão geral
   *
   * @param EmissaoGeral $oEmissao
   * @return RegistroCollection
   */
  public function getCollection(EmissaoGeral $oEmissao)
  {
    $sSqlRegistros = $this->oDaoPadrao->sql_query_registros(
      "emissaogeralregistro.*, emissaogeralmatricula.*, emissaogeralinscricao.*",
      "tr03_sequencial, tr04_sequencial, tr02_numcgm, tr02_parcela",
      "tr02_emissaogeral = {$oEmissao->getCodigo()}"
    );
    
    $rsRegistros = \db_query($sSqlRegistros);

    if (!$rsRegistros) {
      throw new \Exception("Erro ao buscar dados dos registros.");
    }

    return new RegistroCollection($rsRegistros, $oEmissao);
  }
}
