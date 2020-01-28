<?php
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2014 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

/**
 *Classe para instanciacao das movimenta��es
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