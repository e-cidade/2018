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
 * Curso de ensino
 * @package educacao
 * @author Iuri Guntchnigg - iuri@dbseller.com.br
 *         Fabio Esteves   - fabio.esteves@dbseller.com.br
 * @version $Revision: 1.12 $
 */
class Curso {

  /**
   * Codigo do Curso
   * @var integer
   */
  private $iCodigo;

  /**
   * Nome do Curso
   * @var string
   */
  private $sNome;

  /**
   * Nivel ensino do Curso
   * @var Ensino;
   */
  private $oEnsino;

  /**
   * Adicionado array de etapas
   * @var array
   */
  private $aEtapas = array();

  /**
   * Parametro de avaliacao parcial no curso habilitado ou nao
   * 1 - Nao
   * 2 - Sim
   * @var boolean
   */
  private $lAvaliacaoParcial = false;

  /**
   * Verifica se o Curso gera historico
   * @var boolean
   */
  private $lGeraHistorico;

  /**
   * Cursos que são equivalentes a este
   * @return Curso[]
   */
  private $aCursosEquivalentes = array();

  /**
   *
   */
  function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      require_once(modification("classes/db_cursoedu_classe.php"));
      $oDaoCurso = new cl_curso();
      $sSqlCurso = $oDaoCurso->sql_query($iCodigo);
      $rsCurso   = $oDaoCurso->sql_record($sSqlCurso);
      if ($oDaoCurso->numrows > 0) {

        $oDadosCurso             = db_utils::fieldsMemory($rsCurso, 0);
        $this->iCodigo           = $oDadosCurso->ed29_i_codigo;
        $this->sNome             = trim($oDadosCurso->ed29_c_descr);
        $this->oEnsino           = EnsinoRepository::getEnsinoByCodigo($oDadosCurso->ed29_i_ensino);
        $this->lAvaliacaoParcial = $oDadosCurso->ed29_i_avalparcial == 2 ? true : false;
        $this->lGeraHistorico    = $oDadosCurso->ed29_c_historico == 'S' ? true : false;
        $this->oEnsino->setNome($oDadosCurso->ed10_c_descr);
      }
    }
  }

  /**
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna o nivel de ensino do curso
   * @return Ensino
   */
  public function getEnsino() {

    return $this->oEnsino;
  }

  /**
   * Define o nivel de ensino do Curso
   * @param Ensino
   */
  public function setEnsino($oEnsino) {

    $this->oEnsino = $oEnsino;
  }

  /**
   * Retorna o nome do curso
   * @return string
   */
  public function getNome() {

    return $this->sNome;
  }

  /**
   * Define o nome do curso
   * @param string $sNome
   */
  public function setNome($sNome) {

    $this->sNome = $sNome;
  }

  /**
   * Retorna as Etapas vinculadas a um curso
   * @return Etapa | boolean | array
   */
  public function getEtapas () {

    if (count($this->aEtapas) == 0) {

      require_once(modification("classes/db_cursoedu_classe.php"));
      $oDaoCurso  = new cl_curso();
      $sSqlCurso  = $oDaoCurso->sql_query_curso_serie($this->iCodigo, "distinct ed11_i_codigo, ed11_i_sequencia",
                                                      "ed11_i_sequencia");
      $rsCurso    = $oDaoCurso->sql_record($sSqlCurso);

      $iRegistros = $oDaoCurso->numrows;
      // Se nao retornar nada na query, significa que o curso nao possui etapas vinculadas
      if ($iRegistros == 0) {

        return false;
      }

      for ($i = 0; $i < $iRegistros; $i++) {

        $this->aEtapas[] = new Etapa(db_utils::fieldsMemory($rsCurso, $i)->ed11_i_codigo);
      }
      unset($oDaoCurso);
    }

    return $this->aEtapas;
  }

  /**
   * Retorna se o curso esta com a avaliacao parcial habilitada ou nao
   * @return boolean
   */
  public function usaAvaliacaoParcial () {
    return $this->lAvaliacaoParcial;
  }

  /**
   * Verifica se o curso gera histórico
   * @return boolean
   */
  public function geraHistorico() {

    return $this->lGeraHistorico;
  }

  /**
   * Retorna os cursos que são equivalentes a este
   * @return Curso[]
   */
  public function getCursosEquivalentes() {

    if ( !empty($this->aCursosEquivalentes) || empty($this->iCodigo) ) {
      return $this->aCursosEquivalentes;
    }

    $oDaoCursoEquivalencia = new cl_cursoequivalencia();
    $sCampos               = "cursoequivalente.ed29_i_codigo, cursoequivalente.ed29_c_descr";
    $sWhere                = "ed140_cursoedu = {$this->iCodigo}";
    $sSqlCursoEquivalencia = $oDaoCursoEquivalencia->sql_query(null, $sCampos, null, $sWhere);
    $rsCursoEquivalencia   = db_query( $sSqlCursoEquivalencia );

    if ( !$rsCursoEquivalencia ) {
      throw new DBException( _M(ARQUIVO_MENSAGEM_EQUIVALENCIA . "erro_buscar_cursos_equivalentes") );
    }

    $iTotalCursos = pg_num_rows($rsCursoEquivalencia);

    for ( $iContador = 0; $iContador < $iTotalCursos; $iContador++ ) {

      $oDadosCursoEquivalencia     = db_utils::fieldsMemory( $rsCursoEquivalencia, $iContador );
      $oCursoEquivalente           = new Curso($oDadosCursoEquivalencia->ed29_i_codigo);
      $this->aCursosEquivalentes[] = $oCursoEquivalente;
    }

    return $this->aCursosEquivalentes;
  }
}
