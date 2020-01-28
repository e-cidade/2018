<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

  
  Class AlunoMatriculaCenso {
    
    /**
     * C�digo do alunomatcenso
     * @var integer
     */
    private $iCodigo;
    
    /**
     * Inst�ncia de Aluno
     * @var Aluno
     */
    private $oAluno;
    
    /**
     * Turma censo
     * @var integer
     */
    private $iTurmaCenso;
    
    /**
     * Ano do alunomatcenso
     * @var integer
     */
    private $iAno;
    
    /**
     * Matr�cula do censo
     * @var integer
     */
    private $iMatriculaCenso;
    
    /**
     * M�todo construtor para a Classe
     * @param Aluno   $oAluno
     * @param integer $iAno
     */
    public function __construct($oAluno, $iAno) {
      
      $oDaoAlunoMatriculaCenso = db_utils::getDao('alunomatcenso');
      $sWhere                  = "ed280_i_aluno = {$oAluno->getCodigoAluno()} and ed280_i_ano = {$iAno}";
      $sSql                    = $oDaoAlunoMatriculaCenso->sql_query_file(null, '*', null, $sWhere);
      
      $rsAlunoMatriculaCenso = $oDaoAlunoMatriculaCenso->sql_record($sSql);
      
      if($oDaoAlunoMatriculaCenso->numrows > 0) {
        
        $oDadoAlunoMatriculaCenso = db_utils::fieldsMemory($rsAlunoMatriculaCenso, 0);
        $this->setCodigo($oDadoAlunoMatriculaCenso->ed280_i_codigo);
        $this->setTurmaCenso($oDadoAlunoMatriculaCenso->ed280_i_turmacenso);
        $this->setMatriculaCenso($oDadoAlunoMatriculaCenso->ed280_i_matcenso);
      }
      
      $this->setAluno($oAluno);
      $this->setAno($iAno);
    }
    
    /**
     * Retorna o c�digo do alunomatcenso
     * @return integer
     */
    public function getCodigo() {
      return $this->iCodigo;
    }
    
    /**
     * Seta o c�digo do alunomatcenso
     * @param integer $iCodigoAlunoMatriculaCenso
     */
    public function setCodigo($iCodigo) {
      $this->iCodigo = $iCodigo;
    }
    
    /**
     * Retorna inst�ncia de Aluno
     * @return Aluno
     */
    public function getAluno() {
      return $this->oAluno;
    }
    
    /**
     * Seta aluno pertencente a tabela alunomatcenso
     * @param Aluno $oAluno
     */
    public function setAluno($oAluno) {
      $this->oAluno = $oAluno;
    }
    
    /**
     * Retorna a turma pertencente a tabela alunomatcenso
     * @return integer
     */
    public function getTurmaCenso() {
      return $this->iTurmaCenso;
    }
    
    /**
     * Seta a turma pertencente a tabela alunomatcenso
     * @param integer $iTurmaCenso
     */
    public function setTurmaCenso($iTurmaCenso) {
      $this->iTurmaCenso = $iTurmaCenso;
    }
    
    /**
     * Retorna o ano do alunomatcenso
     * @return integer
     */
    public function getAno() {
      return $this->iAno;
    }
    
    /**
     * Seta valor do ano referente a alunomatcenso
     * @param integer $iAno
     */
    public function setAno($iAno) {
      $this->iAno = $iAno;
    }
    
    /**
     * Retorna matr�cula do censo
     * @return integer
     */
    public function getMatriculaCenso() {
      return $this->iMatriculaCenso;
    }
    
    /**
     * Seta valor da matr�cula do censo
     * @param integer $iMatriculaCenso
     */
    public function setMatriculaCenso($iMatriculaCenso) {
      $this->iMatriculaCenso = $iMatriculaCenso;
    }
    
    
    /**
     * M�todo respons�vel por salvar um novo registro na tabela alunomatcenso ou caso a propriedade 
     * iCodigoAlunoMatriculaCenso esteja setada, ir� alterar o registro.
     * @throws DBException
     * @return boolean
     */
    public function salvar() {
      
      $oDaoAlunoMatriculaCenso                     = db_utils::getDao('alunomatcenso');
      $oDaoAlunoMatriculaCenso->ed280_i_codigo     = $this->iCodigo;
      $oDaoAlunoMatriculaCenso->ed280_i_aluno      = $this->oAluno->getCodigoAluno();
      $oDaoAlunoMatriculaCenso->ed280_i_turmacenso = $this->iTurmaCenso;
      $oDaoAlunoMatriculaCenso->ed280_i_ano        = $this->iAno;
      $oDaoAlunoMatriculaCenso->ed280_i_matcenso   = $this->iMatriculaCenso;
      
      if (empty($this->iCodigo)) {
        $oDaoAlunoMatriculaCenso->incluir(null);
      } else {
        $oDaoAlunoMatriculaCenso->alterar($this->iCodigo);
      }
      
      if ($oDaoAlunoMatriculaCenso->erro_status == "0") {
        throw new DBException("Erro ao salvar dados do Aluno com a Matr�cula Censo");
      }
      
      $this->setCodigo($oDaoAlunoMatriculaCenso->ed280_i_codigo);
      
      return true;
    }
  }
?>