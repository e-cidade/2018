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


require_once ('model/educacao/DisciplinaHistorico.model.php');

class DisciplinaHistoricoRede extends DisciplinaHistorico {

  /**
   *
   */
  function __construct($iCodigo = '') {

    if (!empty($iCodigo)) {

      $oDaoDisciplina = db_utils::getDao("histmpsdisc");
      $sSqlDisciplina = $oDaoDisciplina->sql_query_file($iCodigo);
      $rsDisciplina   = $oDaoDisciplina->sql_record($sSqlDisciplina);
      if ($oDaoDisciplina->numrows > 0) {

        $oDadosDisciplina = db_utils::fieldsMemory($rsDisciplina, 0);
        $this->iCodigo    = $iCodigo;
        $this->setDisciplina(new Disciplina($oDadosDisciplina->ed65_i_disciplina));
        $this->setCargaHoraria($oDadosDisciplina->ed65_i_qtdch);
        $this->setJustificativa($oDadosDisciplina->ed65_i_justificativa);
        $this->setOrdem($oDadosDisciplina->ed65_i_ordenacao);
        $this->setResultadoObtido($oDadosDisciplina->ed65_t_resultobtido);
        $this->setSituacaoDisciplina($oDadosDisciplina->ed65_c_situacao);
        $this->setTipoResultado($oDadosDisciplina->ed65_c_tiporesultado);
        $this->setResultadoFinal($oDadosDisciplina->ed65_c_resultadofinal);
        $this->setTermoFinal($oDadosDisciplina->ed65_c_termofinal);
        $this->setLancamentoAutomatico($oDadosDisciplina->ed65_lancamentoautomatico);
        $this->setOpcional($oDadosDisciplina->ed65_opcional);
        unset($oDadosDisciplina);
      }
    }
  }

  /**
   * Persiste os dados da Disciplina
   *
   * @param integer $iCodigoEtapa Codigo da Etapa
   */
  function salvar($iCodigoEtapa = null) {

    if (!db_utils::inTransaction()) {
      throw new DBException("N�o Existe transa��o com o banco de dados ativa.");
    }
    $oDaoDisciplina = db_utils::getDao("histmpsdisc");
    $oDaoDisciplina->ed65_c_resultadofinal     = $this->getResultadoFinal();
    $oDaoDisciplina->ed65_c_situacao           = $this->getSituacaoDisciplina();
    $oDaoDisciplina->ed65_c_tiporesultado      = $this->getTipoResultado();
    $oDaoDisciplina->ed65_i_disciplina         = $this->getDisciplina()->getCodigoDisciplina();
    $oDaoDisciplina->ed65_i_justificativa      = $this->getJustificativa();
    $oDaoDisciplina->ed65_i_ordenacao          = $this->getOrdem();
    $oDaoDisciplina->ed65_i_qtdch              = $this->getCargaHoraria();
    $oDaoDisciplina->ed65_t_resultobtido       = $this->getResultadoObtido();
    $oDaoDisciplina->ed65_c_tiporesultado      = 'N';
    $oDaoDisciplina->ed65_c_termofinal         = $this->getTermoFinal();
    $oDaoDisciplina->ed65_lancamentoautomatico = $this->isLancamentoAutomatico() ? 'true' : 'false';
    $oDaoDisciplina->ed65_opcional             = $this->isOpcional() ? 'true' : 'false';
    if (empty($this->iCodigo))  {

      $oDaoDisciplina->ed65_i_historicomps  = $iCodigoEtapa;
      $oDaoDisciplina->incluir(null);
      $this->iCodigo = $oDaoDisciplina->ed65_i_codigo;
    } else {

      $oDaoDisciplina->ed65_i_codigo = $this->getCodigo();
      $oDaoDisciplina->alterar($oDaoDisciplina->ed65_i_codigo);
    }
    if ($oDaoDisciplina->erro_status == 0) {
      throw new BusinessException($oDaoDisciplina->erro_msg);
    }
  }

  /**
   * Exclui a disciplina
   */
  public function remover() {

   if (!db_utils::inTransaction()) {
      throw new DBException("N�o Existe transa��o com o banco de dados ativa.");
    }
    if (!empty($this->iCodigo)) {

      $oDaoHistorico = db_utils::getDao("histmpsdisc");
      $oDaoHistorico->excluir($this->getCodigo());
      if ($oDaoHistorico->erro_status == 0) {
        throw new BusinessException($oDaoHistorico->erro_msg);
      }
    }
    return null;
  }
}

?>