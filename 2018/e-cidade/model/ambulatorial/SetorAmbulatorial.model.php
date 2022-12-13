<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
 *  Setor ambulatorial
 *
 * @package    Ambulatorial
 * @author     Andrio Costa - andrio.costa@dbseller.com.br
 * @version    $Revision: 1.1 $
 */
class SetorAmbulatorial {

  CONST MENSAGEM = 'saude.ambulatorial.SetorAmbulatorial.';

  /**
   * Código
   * @var integer
   */
  private $iCodigo;

  /**
   * Unidade do setor
   * @var UnidadeProntoSocorro
   */
  private $oUnidadeProntoSocorro;

  /**
   * Nome do setor
   * @var string
   */
  private $sDescricao;

  /**
   * Identifica a qual local/área pertence o setor ambulatorial
   * @var [type]
   */
  private $iLocal;

  /**
   * Locais
   * @var array
   */
  static $aLocais =  array( 1 => "RECEPÇÃO",
                            2 => "TRIAGEM",
                            3 => "CONSULTA MÉDICA",
                            4 => "EXTERNO");


  /**
   * Setter local para onde foi encaminhado
   * @param integer
   */
  public function setLocal ($iLocal) {
    $this->iLocal = $iLocal;
  }

  /**
   * Getter local para onde foi encaminhado
   * @param integer
   */
  public function getLocal () {
    return $this->iLocal;
  }


  /**
   * [__construct description]
   * @param integer $iCodigo [description]
   */
  function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      $oDaoSetorAmbulatorial = new cl_setorambulatorial();

      $sSqlSetor = $oDaoSetorAmbulatorial->sql_query_file($iCodigo);
      $rsSetor   = db_query($sSqlSetor);

      $oErro = new stdClass();

      if ( !$rsSetor ) {

        $oErro->sErro = pg_last_error();
        throw new Exception( _M(SetorAmbulatorial::MENSAGEM."erro_buscar_setor", $oErro));
      }

      if ( pg_num_rows($rsSetor) > 0 ) {

        $oDados = db_utils::fieldsMemory($rsSetor, 0);


        $this->iCodigo               = $oDados->sd91_codigo;
        $this->oUnidadeProntoSocorro = UnidadeProntoSocorroRepository::getUnidadeProntoSocorroByCodigo($oDados->sd91_unidades);
        $this->sDescricao            = $oDados->sd91_descricao;
        $this->iLocal                = $oDados->sd91_local;
      }
    }
    return true;

  }


  /**
   * Getter doc
   * @param integer
   */
  public function getCodigo () {
    return $this->iCodigo;
  }

  /**
   * Setter unidade do setor
   * @param UnidadeProntoSocorro
   */
  public function setUnidadeProntoSocorro (UnidadeProntoSocorro $oUnidadeProntoSocorro) {
    $this->oUnidadeProntoSocorro = $oUnidadeProntoSocorro;
  }

  /**
   * Getter unidade do setor
   * @param UnidadeProntoSocorro
   */
  public function getUnidadeProntoSocorro () {
    return $this->oUnidadeProntoSocorro;
  }

  /**
   * Setter nome do settor
   * @param string
   */
  public function setDescricao ($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Getter nome do settor
   * @param string
   */
  public function getDescricao () {
    return $this->sDescricao;
  }

  /**
   * Getter nome do local
   * @param string
   */
  public function getDescricaoLocal () {

    if (!empty($this->iCodigo) ) {
      return self::$aLocais[$this->iLocal];
    }
  }
}

