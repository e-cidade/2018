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

namespace ECidade\Patrimonial\Protocolo;

/**
 * Class NaturezaCGM
 * @package ECidade\Patrimonial\Protocolo
 */
class NaturezaCGM {

  /**
   * @type integer
   */
  const TIPO_PESSOA_FISICA_JURIDICA_DIREITO_PRIVADO  = 1;

  /**
   * @type string
   */
  const SIGLA_PESSOA_FISICA_JURIDICA_DIREITO_PRIVADO = '000.0';

  /**
   * @type string
   */
  const DESCRICAO_PESSOA_FISICA_JURIDICA_DIREITO_PRIVADO = 'PESSOA FÍSICA OU JURÍDICA DE DIREITO PRIVADO';

  /**
   * @type integer
   */
  const TIPO_PODER_EXECUTIVO_ESTADUAL = 2;

  /**
   * @type string
   */
  const SIGLA_PODER_EXECUTIVO_ESTADUAL = '102.3';

  /**
   * @type string
   */
  const DESCRICAO_PODER_EXECUTIVO_ESTADUAL = 'ÓRGÃO PÚBLICO DO PODER EXECUTIVO ESTADUAL OU DISTRITO FEDERAL';

  /**
   * @type integer
   */
  const TIPO_PODER_EXECUTIVO_MUNICIPAL = 3;

  /**
   * @type string
   */
  const SIGLA_PODER_EXECUTIVO_MUNICIPAL = '103.1';

  /**
   * @type string
   */
  const DESCRICAO_PODER_EXECUTIVO_MUNICIPAL = 'ÓRGÃO PÚBLICO DO PODER EXECUTIVO MUNICIPAL';

  /**
   * @type integer
   */
  const TIPO_FUNDO_PUBLICO = 4;

  /**
   * @type string
   */
  const SIGLA_FUNDO_PUBLICO = '120.1';

  /**
   * @type string
   */
  const DESCRICAO_FUNDO_PUBLICO = 'FUNDO PÚBLICO';

  /**
   * @var array
   */
  private $naturezas = array(

    self::TIPO_PESSOA_FISICA_JURIDICA_DIREITO_PRIVADO => array('sigla' => self::SIGLA_PESSOA_FISICA_JURIDICA_DIREITO_PRIVADO, 'descricao' => self::DESCRICAO_PESSOA_FISICA_JURIDICA_DIREITO_PRIVADO),
    self::TIPO_PODER_EXECUTIVO_ESTADUAL => array('sigla' => self::SIGLA_PODER_EXECUTIVO_ESTADUAL, 'descricao' => self::DESCRICAO_PODER_EXECUTIVO_ESTADUAL),
    self::TIPO_PODER_EXECUTIVO_MUNICIPAL => array('sigla' => self::SIGLA_PODER_EXECUTIVO_MUNICIPAL, 'descricao' => self::DESCRICAO_PODER_EXECUTIVO_MUNICIPAL),
    self::TIPO_FUNDO_PUBLICO => array('sigla' => self::SIGLA_FUNDO_PUBLICO, 'descricao' => self::DESCRICAO_FUNDO_PUBLICO)
  );

  public function __construct() {

    foreach ($this->naturezas as $codigo => $natureza) {
      $natureza['codigo'] = $codigo;
      $this->naturezas[$codigo] = (object)$natureza;
    }
  }

  /**
   * @param $codigo
   * @return bool|\stdClass
   */
  public function getPorCodigo($codigo) {
    return !empty($this->naturezas[$codigo]) ? $this->naturezas[$codigo] : false;
  }

  /**
   * @return \stdClass[]
   */
  public function get() {
    return $this->naturezas;
  }
}



