<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
 * Classe modelo para Ato Legal
 * @package   Educacao
 * @author    Trucolo - trucolo@dbseller.com.br
 * @version   $Revision: 1.3 $
 */
class AtoLegal {

  /**
   * Código do Ato Legal
   * @var integer
   */
  private $iCodigo;

  /**
   * Número do Ato Legal
   * @var integer
   */
  private $iNumero;

  /**
   * Finalidade do Ato Legal
   * @var string
   */
  private $sFinalidade;

  /**
   * Tipo do Ato Legal
   * @var integer
   */
  private $iTipo;

  /**
   * Competência do Governo do Ato Legal
   * @var string
   */
  private $sCompetencia;

  /**
   * Ano de vigência do Ato Legal
   * @var integer
   */
  private $iAno;

  /**
   * Órgão Emitente do Ato Legal
   * @var string
   */
  private $sOrgao;

  /**
   * Data de vigência do Ato Legal
   * @var DBDate
   */
  private $oDtVigor;

  /**
   * Data que foi aprovado o Ato Legal
   * @var DBDate
   */
  private $oDtAprovacao;

  /**
   * Data em que foi publicado o Ato Legal
   * @var DBDate
   */
  private $oDtPublicacao;

  /**
   * Texto completo do Ato Legal
   * @var string
   */
  private $sTexto;

  /**
   * Indica se deve aparecer ou não o Ato Legal no histórico dos alunos
   * @var boolean
   */
  private $lApareceHistorico = false;

  /**
   * Coleção de cursos vinculados ao Ato
   * @var array | Curso
   */
  private $aCurso = array();
  
  /**
   * Tipo decreto
   * @var integer
   */
  const DECRETO = 1;
  
  /**
   * Tipo portaria
   * @var integer
   */
  const PORTARIA = 2;
  
  /**
   * Tipo parecer
   * @var integer
   */
  const PARECER = 3;
  
  /**
   * Tipo lei
   * @var integer
   */
  const LEI = 4;
  
  /**
   * Inicializa a classe a partir de um código de Ato Legal
   * @param string $iCodigo
   * @return boolean
   */
  public function __construct($iCodigo = null) {

    if ($iCodigo != null && DBNumber::isInteger($iCodigo)) {

      $oDaoAtoLegal = db_utils::getDao('atolegal');
      $sSqlAtoLegal = $oDaoAtoLegal->sql_query_file($iCodigo);
      $rsAtoLegal   = $oDaoAtoLegal->sql_record($sSqlAtoLegal);

      if ($oDaoAtoLegal->numrows > 0) {

        $oAtoLegal = db_utils::fieldsMemory($rsAtoLegal, 0);

        $this->iCodigo           = $oAtoLegal->ed05_i_codigo;
        $this->iNumero           = $oAtoLegal->ed05_c_numero;
        $this->sFinalidade       = $oAtoLegal->ed05_c_finalidade;
        $this->iTipo             = $oAtoLegal->ed05_i_tipoato;
        $this->sCompetencia      = $oAtoLegal->ed05_c_competencia;
        $this->iAno              = $oAtoLegal->ed05_i_ano;
        $this->sOrgao            = $oAtoLegal->ed05_c_orgao;
        $this->oDtVigor          = new DBDate($oAtoLegal->ed05_d_vigora);
        $this->oDtAprovacao      = new DBDate($oAtoLegal->ed05_d_aprovado);
        $this->oDtPublicacao     = new DBDate($oAtoLegal->ed05_d_publicado);
        $this->sTexto            = $oAtoLegal->ed05_t_texto;
        $this->lApareceHistorico = $oAtoLegal->ed05_i_aparecehistorico == 1 ? true : false;

      } else {
        return false;
      }

    }
    return true;
  }

  /**
   * Retorna o código do Ato Legal
   * @return number
   */
  public function getCodigoAtoLegal() {
    return $this->iCodigo;
  }

  /**
   * Define um número
   * @param integer $iNumero
   */
  public function setNumero($iNumero) {
    $this->iNumero = $iNumero;
  }

  /**
   * Retorna o número
   * @return number
   */
  public function getNumero() {
    return $this->iNumero;
  }

  /**
   * Define a finalidade
   * @param string $sFinalidade
   */
  public function setFinalidade($sFinalidade) {
    $this->sFinalidade = $sFinalidade;
  }

  /**
   * Retorna a finalidade
   * @return string
   */
  public function getFinalidade() {
    return $this->sFinalidade;
  }

  /**
   * Retorna o tipo
   * @return integer
   */
  public function getTipo() {
    return $this->iTipo;
  }

  /**
   * Define a competência
   * @param string $sCompetencia
   */
  public function setCompetencia($sCompetencia) {
    $this->sCompetencia = $sCompetencia;
  }

  /**
   * Retorna a competência
   * @return string
   */
  public function getCompetencia() {
    return $this->sCompetencia;
  }

  /**
   * Define o ano
   * @param integer $iAno
   */
  public function setAno($iAno) {
    $this->iAno = $iAno;
  }

  /**
   * Retorna o ano
   * @return number
   */
  public function getAno() {
    return $this->iAno;
  }

  /**
   * Define o Órgão
   * @param string $sOrgao
   */
  public function setOrgao($sOrgao) {
    $this->sOrgao = $sOrgao;
  }

  /**
   * Retorna o Órgão
   * @return string
   */
  public function getOrgao() {
    return $this->sOrgao;
  }

  /**
   * Define a data que vigora
   * @param DBDate $oDataVigor
   */
  public function setDataVigor(DBDate $oDataVigor) {
    $this->oDtVigor = $oDataVigor;
  }

  /**
   * Retorna a data que vigora
   * @return DBDate
   */
  public function getDataVigor() {
    return $this->oDtVigor;
  }

  /**
   * Define a data em que foi aprovado
   * @param DBDate $oDataDeAprovacao
   */
  public function setDataDeAprovacao(DBDate $oDataDeAprovacao) {
    $this->oDtAprovacao = $oDataDeAprovacao;
  }

  /**
   * Retorna a data que foi aprovado
   * @return DBDate
   */
  public function getDataDeAprovacao() {
    return $this->oDtAprovacao;
  }

  /**
   * Define a data que foi publicado
   * @param DBDate $oDataDePublicado
   */
  public function setDataDePublicado(DBDate $oDataDePublicado) {
    $this->oDtPublicacao = $oDataDePublicado;
  }

  /**
   * Retorna a data que foi publicado
   * @return DBDate
   */
  public function getDataDePublicacao() {
    return $this->oDtPublicacao;
  }

  /**
   * Define o texto
   * @param string $sTexto
   */
  public function setTexto($sTexto) {
    $this->sTexto = $sTexto;
  }

  /**
   * Retorna o texto
   * @return string
   */
  public function getTexto() {
    return $this->sTexto;
  }

  /**
   * Define se aparece no histórico
   * @param boolean $lApareceHistorico
   */
  public function setApareceHistorico($lApareceHistorico = false) {
    $this->lApareceHistorico = $lApareceHistorico;
  }

  /**
   * Retorna se aparece no histórico ou não.
   * @return boolean
   */
  public function apareceHistorico() {
    return $this->lApareceHistorico;
  }

  /**
   * Retorna todos os cursos ao qual o Ato foi vinculado
   * @return array | Curso 
   */
  public function getCursosVinculado() {
     
    if (count($this->aCurso) == 0) {
       
      $oDaoCurso    = new cl_cursoato();
      $sSqlAtoCurso = $oDaoCurso->sql_query(null, "ed29_i_codigo", null, "ed215_i_atolegal = {$this->iCodigo}");
      $rsAtoCurso   = $oDaoCurso->sql_record($sSqlAtoCurso);
      $iLinhas      = $oDaoCurso->numrows;
      
      if ($iLinhas > 0) {
         
        for ($i = 0; $i < $iLinhas; $i++ ) {
           
          $this->aCurso[] = new Curso(db_utils::fieldsMemory($rsAtoCurso, $i)->ed29_i_codigo);
        }
      }
    }
  
    return $this->aCurso;
  }
  
  /**
   * Verifica se existe um curso vinculado ao Ato legal
   * @return boolean
   */
  public function existeCursoVinculado() {
  	
    if (count($this->getCursosVinculado()) > 0) {
      return true;
    }
    return  false;
  }
}
?>