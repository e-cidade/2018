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
   * Classe repository para classes Disciplina
   * @author Iuri Guntchnigg <iuri@dbseller.com.br>
   * @package
   */
  class DisciplinaRepository {

    /**
     * Collection de Disciplina
     * @var array
     */
    private $aDisciplina = array();

    /**
     * Instancia da classe
     * @var DisciplinaRepository
     */
    private static $oInstance;

    private function __construct() {

    }
    private function __clone() {

    }

    /**
     * Retorno uma instancia do Disciplina pelo Codigo
     * @param integer $iCodigo Codigo do Disciplina
     * @return Disciplina
     */
    public static function getDisciplinaByCodigo($iCodigoDisciplina) {

      if (!array_key_exists($iCodigoDisciplina, DisciplinaRepository::getInstance()->aDisciplina)) {
        DisciplinaRepository::getInstance()->aDisciplina[$iCodigoDisciplina] = new Disciplina($iCodigoDisciplina);
      }
      return DisciplinaRepository::getInstance()->aDisciplina[$iCodigoDisciplina];
    }

    /**
     * Retorna a instancia da classe
     * @return DisciplinaRepository
     */
    protected static function getInstance() {

      if (self::$oInstance == null) {

        self::$oInstance = new DisciplinaRepository();
      }
      return self::$oInstance;
    }

    /**
     * Adiciona um Disciplina dao repositorio
     * @param Disciplina $oDisciplina Instancia do Disciplina
     * @return boolean
     */
    public static function adicionarDisciplina(Disciplina $oDisciplina) {

      if(!array_key_exists($oDisciplina->getCodigoDisciplina(), DisciplinaRepository::getInstance()->aDisciplina)) {
        DisciplinaRepository::getInstance()->aDisciplina[$oDisciplina->getCodigoDisciplina()] = $oDisciplina;
      }
      return true;
    }

    /**
     * Remove o Disciplina passado como parametro do repository
     * @param Disciplina $oDisciplina
     * @return boolean
     */
    public static function removerDisciplina(Disciplina $oDisciplina) {
       /**
        *
        */
      if (array_key_exists($oDisciplina->getCodigoDisciplina(), DisciplinaRepository::getInstance()->aDisciplina)) {
        unset(DisciplinaRepository::getInstance()->aDisciplina[$oDisciplina->getCodigoDisciplina()]);
      }
      return true;
    }

    /**
     * Retorna o total de cidadoes existentes no repositorio;
     * @return integer;
     */
    public static function getTotalDisciplina() {
      return count(DisciplinaRepository::getInstance()->aDisciplina);
    }

    public static function getDisciplinaByCodigoCenso($iCodigoDisciplinaCenso) {

      $oDisciplina         = null;
      $oDaoDisciplina      = new cl_disciplina();
      $sCampos             = " ed12_i_codigo ";
      $sSqlDadosDisciplina = $oDaoDisciplina->sql_query_disciplina_censo(
                                                                          null,
                                                                          $sCampos,
                                                                          null,
                                                                          "ed294_censodisciplina = {$iCodigoDisciplinaCenso}"
                                                                        );
      $rsDadosDisciplina   = $oDaoDisciplina->sql_record($sSqlDadosDisciplina);

      if( !$rsDadosDisciplina ) {
        throw new DBException( "Erro ao buscar a disciplina pelo código do censo informado:\n" . pg_last_error() );
      }

      if( pg_num_rows( $rsDadosDisciplina ) > 0 ) {

        $iDisciplina = db_utils::fieldsMemory( $rsDadosDisciplina, 0 )->ed12_i_codigo;
        $oDisciplina = DisciplinaRepository::getDisciplinaByCodigo( $iDisciplina );
      }

      return $oDisciplina;
    }
  }
?>