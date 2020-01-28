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

namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Arquivo;

use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Arquivo\CEF;
use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Arquivo\BB;
use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Arquivo\Banrisul;

abstract class Factory {

  /**
   * Retorna o modelo de acordo com o banco
   * @param string $sCodigoBanco
   * @throws \Exception
   * @return \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Arquivo\BaseAbstract
   */
  public static function getModelo($sCodigoBanco) {

    switch ($sCodigoBanco) {
      case BB::CODIGO_BANCO:
        return new BB();
      break;

      case CEF::CODIGO_BANCO:
        return new CEF();
      break;

      case Banrisul::CODIGO_BANCO:
        return new Banrisul();
      break;

      default:
        throw new \Exception("Banco {$sCodigoBanco} não encontrado");
      break;
    }
  }
}
