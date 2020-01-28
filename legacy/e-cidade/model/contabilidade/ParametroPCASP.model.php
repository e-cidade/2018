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

/**
 * Class ParametroPCASP
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @package contabilidade
 * @revision $
 */
final class ParametroPCASP {

  /**
   * Caminho do arquivo de configuração
   * @var string
   */
  const CAMINHO_ARQUIVO_CONFIGURACAO = "config/pcasp.txt";

  /**
   * Ano de inicio padrão para clientes que começaram a utilizar o PCASP em 2013
   * @var integer
   */
  const ANO_PADRAO = 2013;

  /**
   * Construtor privado para não permitir instanciar a classe
   */
  private function __construct(){}

  /**
   * Método que retorna o ano de utilização de PCASP do cliente
   * @return int
   */
  public static function getAnoInicioPCASP() {

    if ( file_exists(self::CAMINHO_ARQUIVO_CONFIGURACAO) ) {

      $aDadosAquivo = file(self::CAMINHO_ARQUIVO_CONFIGURACAO);
      if ( trim($aDadosAquivo[0]) != "" && strlen((int)$aDadosAquivo[0]) === 4 && $aDadosAquivo[0] > self::ANO_PADRAO ) {
        return (int)$aDadosAquivo[0];
      }
    }
    return self::ANO_PADRAO;
  }

  /**
   * Valida se o cliente utiliza PCASP no ano informado via parâmetro
   * @param $iAno - integer
   * @return bool
   */
  public static function utilizaPCASPNoAno($iAno) {
    return (int)$iAno >= self::getAnoInicioPCASP();
  }
}