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

namespace ECidade\Tributario\Integracao\JuntaComercial\Processamento;
use ECidade\Tributario\Integracao\JuntaComercial\Dicionario;

/**
 * Classe que processa as informações de Registro da Junta Comercial
 * @package ECidade\Tributario\Integracao\JuntaComercial\Processamento
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 */
abstract class Base
{
  protected $registro;
  protected $grupo;

  /**
   * @var Dicionario
   */
  protected $dicionario;

  /**
   * Constructor.
   * @param Dicionario $dicionario  objeto que contém os dados do arquivo xml
   */
  public function __construct(Dicionario $dicionario)
  {
    $this->registro = array();
    $this->grupo = array();
    $this->dicionario = $dicionario;
  }

  /**
   * Adicionamos o grupo que a classe deve buscar as informações
   * @param $grupo
   */
  public function addGrupo($grupo)
  {
    $this->grupo[] = $grupo;
  }

  /**
   * Resetamos as informações que já foram processadas
   */
  public function reset()
  {
    $this->grupo = array();
    $this->registro = array();
  }

  /**
   * Função que retorna o dado do dicionário ou, se isso já ocorreu, retorno o valor já existente
   * @param integer $indice
   * @param string $campo
   * @return string|null
   */
  protected function getValor($indice, $campo)
  {
    if ( isset($this->registro[$indice]->$campo) && !empty($this->registro[$indice]->$campo) ) {
      return trim($this->registro[$indice]->$campo);
    }

    return trim($this->dicionario->getCampo($campo));
  }
}
