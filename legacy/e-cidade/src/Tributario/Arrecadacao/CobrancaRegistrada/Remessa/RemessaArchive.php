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

use \ZipArchive;

class RemessaArchive
{
  private $oZipArchive;

  private $sNameArchive;

  public function __construct() {
    $this->oZipArchive = new ZipArchive();
  }

  public function getNameArchive()
  {
    return $this->sNameArchive;
  }

  private function novo()
  {
    $this->sNameArchive = "tmp/cobranca_registrada_".time().".zip";
  }

  public function open()
  {
    $this->novo();

    if ($this->oZipArchive->open($this->sNameArchive, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
      throw new Exception("Erro ao gerar arquivo.");
    }
  }

  public function addFile($sFilePath, $sBaseName)
  {
    if (!$this->oZipArchive->addFile($sFilePath, $sBaseName)) {
      throw new Exception("Erro ao compactar arquivo {$sArquivo}.");
    }
  }

  public function close()
  {
    $this->oZipArchive->close();
  }
}
