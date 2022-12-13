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

  /**
   * Classe repository para classes Turno
   * @author Iuri Guntchnigg <iuri@dbseller.com.br>
   * @package
   */
  class TurnoRepository {

    /**
     * Collection de Turno
     * @var array
     */
    private $aTurno = array();

    /**
     * Instancia da classe
     * @var TurnoRepository
     */
    private static $oInstance;

    private function __construct() {

    }
    private function __clone() {

    }

    /**
     * Retorno uma instancia do Turno pelo Codigo
     * @param integer $iCodigo Codigo do Turno
     * @return Turno
     */
    public static function getTurnoByCodigo($iCodigoTurno) {

      if (!array_key_exists($iCodigoTurno, TurnoRepository::getInstance()->aTurno)) {
        TurnoRepository::getInstance()->aTurno[$iCodigoTurno] = new Turno($iCodigoTurno);
      }
      return TurnoRepository::getInstance()->aTurno[$iCodigoTurno];
    }

    /**
     * Retorna a instancia da classe
     * @return TurnoRepository
     */
    protected static function getInstance() {

      if (self::$oInstance == null) {

        self::$oInstance = new TurnoRepository();
      }
      return self::$oInstance;
    }

    /**
     * Adiciona um Turno dao repositorio
     * @param Turno $oTurno Instancia do Turno
     * @return boolean
     */
    public static function adicionarTurno(Turno $oTurno) {

      if(!array_key_exists($oTurno->getCodigo(), TurnoRepository::getInstance()->aTurno)) {
        TurnoRepository::getInstance()->aTurno[$oTurno->getCodigo()] = $oTurno;
      }
      return true;
    }

    /**
     * Remove o Turno passado como parametro do repository
     * @param Turno $oTurno
     * @return boolean
     */
    public static function removerTurno(Turno $oTurno) {
       /**
        *
        */
      if (array_key_exists($oTurno->getCodigo(), TurnoRepository::getInstance()->aTurno)) {
        unset(TurnoRepository::getInstance()->aTurno[$oTurno->getCodigo()]);
      }
      return true;
    }

    /**
     * Retorna o total de cidadoes existentes no repositorio;
     * @return integer;
     */
    public static function getTotalTurno() {
      return count(TurnoRepository::getInstance()->aTurno);
    }


    public static function getTurnosCadastrados() {

      $oDaoTurno  = new cl_turno();
      $sSqlTurnos = $oDaoTurno->sql_query_file(null, "ed15_i_codigo", "ed15_i_sequencia");
      $rsTurnos   = $oDaoTurno->sql_record($sSqlTurnos);

      if ($rsTurnos && $oDaoTurno->numrows > 0) {

        $iLinha = $oDaoTurno->numrows;

        for ($i = 0; $i < $iLinha; $i++) {

          $iCodigoTurno = db_utils::fieldsMemory($rsTurnos, $i)->ed15_i_codigo;
          TurnoRepository::getTurnoByCodigo($iCodigoTurno);
        }
      }

      return TurnoRepository::getInstance()->aTurno;
    }
  }