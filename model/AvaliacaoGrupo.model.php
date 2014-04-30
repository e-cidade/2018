<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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


class AvaliacaoGrupo {
  
  protected $iGrupo; 
  
  protected $sDescricao;
  
  protected $aPerguntas = array();
  
  /**
   * Identificador do grupo
   * @var string
   */
  protected $sIdentificador;
  
  function __construct($iGrupo = null) {

    $this->iGrupo = $iGrupo;
    $oDaoAvaliacaoGrupo = db_utils::getDao("avaliacaogrupopergunta");
    $sSqlGrupos         = $oDaoAvaliacaoGrupo->sql_query_file($iGrupo);
    $rsGrupo            = $oDaoAvaliacaoGrupo->sql_record($sSqlGrupos);
    if ($oDaoAvaliacaoGrupo->numrows == 1) {
      
       $oDadosGrupo          = db_utils::fieldsMemory($rsGrupo, 0);
       $this->sDescricao     = $oDadosGrupo->db102_descricao; 
       $this->sIdentificador = $oDadosGrupo->db102_identificador;
       unset($oDadosGrupo);   
    }
  }
  /**
   * @return unknown
   */
  public function getGrupo() {

    return $this->iGrupo;
  }
  
  /**
   * @return unknown
   */
  public function getDescricao() {

    return $this->sDescricao;
  }
  
  /**
   * @param integer $sDescricao
   */
  public function setDescricao($sDescricao) {

    $this->sDescricao = $sDescricao;
  }
  
  /**
   * Retorna o identificador do grupo
   * @return string
   */
  public function getIdentificador() {
    return $this->sIdentificador;
  }
  
  /**
   * Seta o identificador do grupo
   * @param string $sIdentificador
   */
  public function setIdentificador($sIdentificador) {
    $this->sIdentificador = $sIdentificador;
  }
  
  public function getPerguntas() {
    
    if (count($this->aPerguntas) == 0) {
      
      $oDaoPergunta    = db_utils::getDao("avaliacaopergunta");
      $sSqlPergunta    = $oDaoPergunta->sql_query_file(null, "*",
                                                       "db103_ordem", 
                                                       "db103_avaliacaogrupopergunta={$this->iGrupo}");
      $rsPergunta      = $oDaoPergunta->sql_record($sSqlPergunta);
      if ($oDaoPergunta->numrows > 0) {
        
        $aPerguntas = db_utils::getColectionByRecord($rsPergunta);
        foreach ($aPerguntas as $oPerguntaTemp) {
          
          $oPergunta          = new AvaliacaoPergunta($oPerguntaTemp->db103_sequencial);
          $this->aPerguntas[] = $oPergunta;
        }
      }
    }
    return $this->aPerguntas;
  }

}
?>