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
 * Classe que prove os dados do historico do aluno para os webservices do portal do aluno
 * @author dbseller
 *
 */
class HistoricoAlunoWebservice {
  
  
  /**
   * Instancia do aluno
   * @var Aluno
   */
  protected $oAluno;
  
  /**
   * Instancia o webservice
   * @param integer $iCodigoAluno Codigo do aluno
   */
  public function __construct($iAluno) {
    
    $this->oAluno = new Aluno($iAluno);
    
  }
  
  /**
   * Retorna os historicos do aluno
   * @return stdClass
   */
  public function getHistoricos() {
    
    $oDaoHistorico = new cl_historico();
    $iCodigoAluno  = $this->oAluno->getCodigoAluno();
    $sSqlHistorico = $oDaoHistorico->sql_query_file(null, "ed61_i_codigo",
                                                    "ed61_i_codigo",
                                                    "ed61_i_aluno = {$iCodigoAluno}"
                                                   );
    $rsHistoricos            = $oDaoHistorico->sql_record($sSqlHistorico);
    $iTotalLinhas            = $oDaoHistorico->numrows;
    $oHistoricoDados         = new stdClass();
    $oHistoricoDados->etapas = array();
    $oHistoricoDados->linhas = $iTotalLinhas;
    $oHistoricoDados->query  = $sSqlHistorico;
    if ($rsHistoricos && $iTotalLinhas > 0) {
      
      for ($i = 0; $i < $iTotalLinhas; $i++) {
        
        $iCodigoHistorico = db_utils::fieldsMemory($rsHistoricos, $i)->ed61_i_codigo;
        $oHistorico = new HistoricoAluno($iCodigoHistorico);
        $oHistorico->setAluno($this->oAluno);
        foreach ($this->getEtapasHistorico($oHistorico) as $oEtapa) {
           $oHistoricoDados->etapas[] = $oEtapa;
        }
      }
    }
    
    uasort($oHistoricoDados->etapas, array($this, "ordenarEtapas"));
    return $oHistoricoDados;
  }
  
  /**
   * Retorna as etapas do historico do aluno
   * @param HistoricoAluno $oHistorico Instancia do historico
   * @return stdClass
   */
  protected function getEtapasHistorico(HistoricoAluno $oHistorico) {
    
    $aEtapas = array();
    foreach ($oHistorico->getEtapas() as $oEtapa) {
      
      /**
       * ignoramos etapas reprovadas, ou etapas que o aluno está com aprovacao parcial
       */
      if ($oEtapa->getResultadoAno() == "R" || $oEtapa->getResultadoAno() == "P") {
        continue;
      }
      $iAno            = $oEtapa->getAnoCurso();
      $iEnsino         = $oEtapa->getEtapa()->getEnsino()->getCodigo();
      $sTermoResultado = DBEducacaoTermo::getTermoEncerramento($iEnsino, $oEtapa->getResultadoAno(), $iAno);
      
      $oEtapaRetorno                    = new stdClass();
      $oEtapaRetorno->etapa             = utf8_encode($oEtapa->getEtapa()->getNome());
      $oEtapaRetorno->curso             = utf8_encode($oHistorico->getCursoHistorico()->getNome());
      $oEtapaRetorno->escola_etapa      = utf8_encode($oEtapa->getEscola()->getNome());
      $oEtapaRetorno->ano_etapa         = utf8_encode($oEtapa->getAnoCurso());
      $oEtapaRetorno->minimo_aprovacao  = utf8_encode($oEtapa->getMininoParaAprovacao());
      $oEtapaRetorno->resultado_etapa   = utf8_encode($sTermoResultado[0]->sDescricao);
      $oEtapaRetorno->ordem_etapa       = utf8_encode($oEtapa->getEtapa()->getOrdem());
      $oEtapaRetorno->situacao_etapa    = utf8_encode($oEtapa->getSituacaoEtapa());
      $oEtapaRetorno->disciplinas_etapa = $this->getDisciplinasDaEtapa($oEtapa);
      $oEtapaRetorno->dias_letivos      = $oEtapa->getDiasLetivos();
      $oEtapaRetorno->carga_horaria     = $oEtapa->getCargaHoraria();
      $aEtapas[] = $oEtapaRetorno;
    }
    return $aEtapas;
  }
  
  /**
   * Retorna as disciplinas cursadas na etapa
   * @param HistoricoEtapa $oEtapa
   * @return multitype:stdClass
   */
  protected function getDisciplinasDaEtapa (HistoricoEtapa $oEtapa) {
    
    $aDisciplinas = array();
    $iAno            = $oEtapa->getAnoCurso();
    $iEnsino         = $oEtapa->getEtapa()->getEnsino()->getCodigo();
    foreach ($oEtapa->getDisciplinas() as $oDisciplinaHistorico) {

      $sTermoResultado = DBEducacaoTermo::getTermoEncerramento(
                                                               $iEnsino,
                                                               $oDisciplinaHistorico->getResultadoFinal(),
                                                               $iAno
                                                               );
      
      $oDisciplina                            = new stdClass();
      $oDisciplina->nome_disciplina           = utf8_encode($oDisciplinaHistorico->getDisciplina()->getNomeDisciplina());
      $oDisciplina->resultado_disciplina      = utf8_encode($sTermoResultado[0]->sDescricao);
      $oDisciplina->aproveitamento_disciplina = utf8_encode($oDisciplinaHistorico->getResultadoObtido());
      $oDisciplina->situacao_disciplina       = utf8_encode($oDisciplinaHistorico->getSituacaoDisciplina());
      $oDisciplina->carga_horaria             = utf8_encode($oDisciplinaHistorico->getCargaHoraria());
      $oDisciplina->ordem_disciplina          = utf8_encode($oDisciplinaHistorico->getOrdem());
      $aDisciplinas[] = $oDisciplina;
    }
    return $aDisciplinas;
  }
  
  /**
   * Metodo para ordenar as etapas corretamente
   * @param stdClass $oEtapaAtual Dados com a etapa Atual
   * @param unknown $oProximaEtapa Dados com a proxima etapa
   * @return number
   */
  protected function ordenarEtapas($oEtapaAtual, $oProximaEtapa) {
    
    return ($oEtapaAtual->ordem_etapa < $oProximaEtapa->ordem_etapa) ? -1 : 1;
  }
}