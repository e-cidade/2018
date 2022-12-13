<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2017  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Remessa;

use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Remessa\RemessaService;
use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Remessa\RemessaTemporaryService;
use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Remessa\RemessaArchive;
use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Remessa\Remessa;
use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Arquivo\Factory;
use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;
use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\RegistroCollection;
use \tableDataManager;
use \DBLargeObject;
use \DateTime;

class RemessaBuilder
{
  private static $iQuantRegArq = 300000;

  private $oRemessaService;

  private $oRemessaTemporaryService;

  private $oRemessaArchive;

  private $iBanco;

  private $oModeloArquivo;

  private $oModeloHeader;

  public function __construct(RemessaService $oRemessaService, RemessaTemporaryService $oRemessaTemporaryService, RemessaArchive $oRemessaArchive)
  {
    $this->oRemessaService = $oRemessaService;
    $this->oRemessaTemporaryService = $oRemessaTemporaryService;
    $this->oRemessaArchive = $oRemessaArchive;

    $this->iBanco = $this->oRemessaTemporaryService->getCodigoBanco();

    $this->oModeloArquivo = Factory::getModelo($this->iBanco);
    $this->oModeloHeader = CobrancaRegistrada::carregarHeader($this->oRemessaTemporaryService->getConvenio());
  }

  public function processaArquivoRemessa($lCallBack, $lQuebraLinha=false)
  {
    $aRecibosGerados = array();
    $iQuantidadeAtual = 0;
    $iQuantidadeArquivo = self::$iQuantRegArq;
    $iQuantidadeRegistros = $this->oRemessaTemporaryService->getQuantidadeRegistros();

    $iRemessa = $this->oModeloHeader->getSequencial();

    $this->oRemessaArchive->open();

    while ($iQuantidadeAtual < $iQuantidadeRegistros) {

      $sSql = $this->oRemessaTemporaryService->getSqlCollection($iQuantidadeArquivo, $iQuantidadeAtual);

      $oCollection = new RegistroCollection($sSql);

      $this->oModeloArquivo->setCallback(function($iQuantidade) use($iQuantidadeAtual, $iQuantidadeRegistros, $lCallBack) {

        $iRegistroAtual = ($iQuantidade + $iQuantidadeAtual);

        $iCalculoPercentual = intval($iRegistroAtual * 100 / $iQuantidadeRegistros);
        call_user_func_array($lCallBack, array($iRegistroAtual, $iCalculoPercentual));
      });

      $this->oModeloArquivo->setRegistros($oCollection);
      $this->oModeloArquivo->setHeader($this->oModeloHeader);

      $oArquivo = $this->oModeloArquivo->gerarArquivo($lQuebraLinha);

      $sFilePath = $oArquivo->getFilePath();
      $sBaseName = $oArquivo->getBaseName();

      $this->oRemessaArchive->addFile($sFilePath, $sBaseName);

      $aRecibosGerados[$iRemessa] = array(
        "arquivo" => array(
          "path" => $sFilePath,
          "name" => $sBaseName
        ),
        "recibos" => $this->oModeloArquivo->getRecibosGerados()
      );

      $this->oModeloHeader->setSequencial(++$iRemessa);

      $iQuantidadeAtual += $iQuantidadeArquivo;
    }

    $this->oRemessaArchive->close();

    return (object) array(
      "sArquivoNome" => $this->oRemessaArchive->getNameArchive(),
      "aReciboGerados" => $aRecibosGerados
    );
  }

  public function salvaArquivoRemessa($conn, $aRecibosGerados, $lCallBack)
  {
    $iQuantidadeAtual = 0;
    $iQuantidadeRegistros = $this->oRemessaTemporaryService->getQuantidadeRegistros();

    $oRemessaRecibo = new tableDataManager(
      $conn,
      "remessacobrancaregistradarecibo",
      "k148_sequencial",
      true,
      800,
      "remessacobrancaregistradarecibo_k148_sequencial_seq"
    );

    foreach ($aRecibosGerados as $iRemessa => $aGeracao) {

      $this->oRemessaArchive->open();

      $this->oRemessaArchive->addFile($aGeracao['arquivo']['path'], $aGeracao['arquivo']['name']);

      $sNomeRemessaCompactada = $this->oRemessaArchive->getNameArchive();

      $this->oRemessaArchive->close();

      $iOid = DBLargeObject::criaOID(true);
      DBLargeObject::escrita($sNomeRemessaCompactada, $iOid);

      $iInstit = $this->oModeloHeader->getInstituicao()->getSequencial();
      $iConvenio = $this->oRemessaTemporaryService->getConvenio();
      $iSequencialRemessa = $iRemessa;

      $oDataEmissao = new DateTime(date("Y-m-d", db_getsession("DB_datausu")));
      $oDataEmissao->setTime(date("H"), date("i"));

      $oRemessa = new Remessa($iInstit, $iConvenio, $iSequencialRemessa, $oDataEmissao, $iOid);

      $iCodigoRemessa = $this->oRemessaService->salvar($oRemessa);

      foreach($aGeracao['recibos'] as $sRecibo) {

        $iCalculoPercentual = intval((++$iQuantidadeAtual) * 100 / $iQuantidadeRegistros);

        call_user_func_array($lCallBack, array($iQuantidadeAtual, $iCalculoPercentual));

        $oRegistro = (object) array(
          "k148_remessacobrancaregistrada" => $iCodigoRemessa,
          "k148_numpre" => $sRecibo
        );

        $oRemessaRecibo->setByLineOfDBUtils($oRegistro);
        $oRemessaRecibo->insertValue();
      }

      CobrancaRegistrada::removerRecibos($aGeracao['recibos']);
    }

    $oRemessaRecibo->persist();
  }
}
