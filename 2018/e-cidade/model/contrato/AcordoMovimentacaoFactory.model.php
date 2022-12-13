<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2014 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

/**
 *Classe para instanciacao das movimentações
 * Class AcordoMovimentacaoFactory
 *
 */
class AcordoMovimentacaoFactory {


  public static function getMovimentacaoPorTipo($iCodigo, $iTipo) {

    $aTipos = self::getTipos();
    if (isset($aTipos[$iTipo])) {
      return new $aTipos[$iTipo]($iCodigo);
    }
  }

  public static function getTipos() {


    $aTipos = array(
                    2  => "AcordoAssinatura",
                    13 => "AcordoAssinatura",
                    8  => "AcordoAnulacao",
                    9  => "AcordoAnulacao",
                    6  => "AcordoRescisao",
                    7  => "AcordoRescisao",
                    11 => "AcordoHomologacao",
                    12 => "AcordoHomologacao",
                    16 => "AcordoMovimentacaoParalisacao",
                    17 => "AcordoMovimentacaoParalisacao",
                    18 => "AcordoMovimentacaoReativacao",
                    19 => "AcordoMovimentacaoReativacao"
                   );

    return $aTipos;
  }
} 