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

require_once 'model/configuracao/inconsistencia/iExcecaoProcessamentoDependencias.interface.php';

/**
 * Processa as exceções encontradas quando tentamos remover os duplos do diário
 * @author Andrio <andrio.costa@dbseller.com.br>
 * @author Fabio <fabio.esteves@dbseller.com.br>
 */
class ProcessaDuploDiario implements IExcecaoProcessamentoDependencias {
  
  /**
   * Mensagem de erro
   * @var string
   */
  private $sMsgErro;

  /**
   * Processa as dependencias do aluno em relação ao seu diário. 
   * O que faz? 
   *  -- Se não conflitar: (ed95_i_escola, ed95_i_calendario, ed95_i_aluno, ed95_i_serie, ed95_i_regencia) 
   *     dar update no aluno para o correto;
   *  -- Se conflitar: deleta todo o diario do aluno incorreto;
   *  
   * @param integer $iChaveCorreta código do aluno corréto
   * @param integer $iChaveIncorreta código do aluno que deve ser substituido / removido
   * @see IExcecaoProcessamentoDependencias::processar()
   */
  public function processar($iChaveCorreta, $iChaveIncorreta) {

    $oDaoDiario = new cl_diario;
    $sCampos    = "ed95_i_codigo, ed95_i_escola, ed95_i_calendario, ed95_i_aluno, ed95_i_serie, ed95_i_regencia";
    
    $sWhereConflito  = "ed95_i_aluno = {$iChaveIncorreta} ";
    $sWhereConflito .= " and exists(select 1 ";
    $sWhereConflito .= "              from diario manter ";
    $sWhereConflito .= "             where manter.ed95_i_escola     = diario.ed95_i_escola ";
    $sWhereConflito .= "               and manter.ed95_i_calendario = diario.ed95_i_calendario ";
    $sWhereConflito .= "               and manter.ed95_i_serie      = diario.ed95_i_serie ";
    $sWhereConflito .= "               and manter.ed95_i_regencia   = diario.ed95_i_regencia ";
    $sWhereConflito .= "               and manter.ed95_i_aluno      = {$iChaveCorreta} ";
    $sWhereConflito .= "           )";
     
    $sSqlRegistrosComConflito = $oDaoDiario->sql_query_file(null, $sCampos, $sCampos, $sWhereConflito);
    $rsRegistrosComConflito   = $oDaoDiario->sql_record($sSqlRegistrosComConflito);
    $iRegistrosComConflito    = $oDaoDiario->numrows;
    
    
    /**
     * Caso exista registros com conflito, devemos excluir os mesmo
     * REgistos com conflito são aqueles que após o update, terão o índice único diario_esc_cal_alu_ser_reg_in
     * (ed95_i_escola, ed95_i_calendario, ed95_i_aluno, ed95_i_serie, ed95_i_regencia) duplicado.
     */
    
    if ($iRegistrosComConflito > 0) {
      
      $lRemoveRegistros = $this->removeRegistros(db_utils::getCollectionByRecord($rsRegistrosComConflito));
      if (!$lRemoveRegistros) {
        return false;
      }
    }
    
    return $this->alteraDiario($iChaveCorreta, $iChaveIncorreta);
    
  }
  
  
  /**
   * Apaga os registros do diário do aluno
   * @param array $aRemover diarios a serem removidos
   * @return boolean
   */
  private function removeRegistros($aRemover) {
    
    $oDaoAmparo                             = new cl_amparo;
    $oDaoAprovConselho                      = new cl_aprovconselho;
    $oDaoDiarioAvaliacao                    = new cl_diarioavaliacao;
    $oDaoDiarioFinal                        = new cl_diariofinal;
    $oDaoDiarioResultado                    = new cl_diarioresultado;
    $oDaoParecerResult                      = new cl_parecerresult; // filha de diarioresultado
    $oDaoAbonoFalta                         = new cl_abonofalta;    // filha de diarioavaliacao
    $oDaoParecerAval                        = new cl_pareceraval;   // filha de diarioavaliacao
    $oDaoProgrParcialAluno                  = new cl_progressaoparcialaluno;                 // filha de diariofinal 
    $oDaoProgrParcialAlunoEncerrado         = new cl_progressaoparcialalunoencerradodiario;  // filha de diariofinal
    $oDaoProgrParcialAlunoDiarioFinalOrigem = new cl_progressaoparcialalunodiariofinalorigem(); 
    $oDaoDiario                             = new cl_diario;
    
    foreach ($aRemover as $oRemover) {
      
      
      /**
       * Exclui amparo para diário
       */
      $oDaoAmparo->excluir(null, "ed81_i_diario = {$oRemover->ed95_i_codigo}");
      if ($oDaoAmparo->erro_status == 0) {
        
        $this->sMsgErro = str_replace("\\n", "\n", $oDaoAmparo->erro_sql);
        return false;
      }
      
      /**
       * Exclui AprovConselho para diário 
       */
      $oDaoAprovConselho->excluir(null, "ed253_i_diario = {$oRemover->ed95_i_codigo}");
      if ($oDaoAprovConselho->erro_status == 0) {
        
        $this->sMsgErro = str_replace("\\n", "\n", $oDaoAprovConselho->erro_sql);
        return false;
      }
      
      /**
       * Busca os diarioavaliacao para excluir os abonofalta e pareceraval 
       */
      $sWhereDiarioAvaliacao = "ed72_i_diario = {$oRemover->ed95_i_codigo}";
      $sSqlDiarioAvaliacao   = $oDaoDiarioAvaliacao->sql_query_file(null, "ed72_i_codigo", null, $sWhereDiarioAvaliacao);
      $rsDiarioAvaliacao     = $oDaoDiarioAvaliacao->sql_record($sSqlDiarioAvaliacao);
      $iLinhaDiarioAvaliacao = $oDaoDiarioAvaliacao->numrows;
      
      if ($iLinhaDiarioAvaliacao > 0) {
        
        for ($i = 0; $i < $iLinhaDiarioAvaliacao; $i++) {
          
          $iDiarioAvaliacao = db_utils::fieldsMemory($rsDiarioAvaliacao, $i)->ed72_i_codigo;
          $oDaoAbonoFalta->excluir(null, "ed80_i_diarioavaliacao = {$iDiarioAvaliacao}");
          if ($oDaoAbonoFalta->erro_status == 0) {

            $this->sMsgErro = str_replace("\\n", "\n", $oDaoAbonoFalta->erro_sql);
            return false;
          }
          
          $oDaoParecerAval->excluir(null, "ed93_i_diarioavaliacao = {$iDiarioAvaliacao}");
          if ($oDaoParecerAval->erro_status == 0) {
            
            $this->sMsgErro = str_replace("\\n", "\n", $oDaoParecerAval->erro_sql);
            return false;
          }
        }
      }
      
      $oDaoDiarioAvaliacao->excluir(null, $sWhereDiarioAvaliacao);
      if ($oDaoDiarioAvaliacao->erro_status == 0) {
        
        $this->sMsgErro = str_replace("\\n", "\n", $oDaoDiarioAvaliacao->erro_sql);
        return false;        
      }
      
      /**
       * Busca os diariofinal para excluir os progressaoparcialalunoencerradodiario e progressaoparcialaluno
       */
      $sWhereDiarioFinal = " ed74_i_diario = {$oRemover->ed95_i_codigo}"; 
      $sSqlDiarioFinal   = $oDaoDiarioFinal->sql_query_file(null, "ed74_i_codigo", null, $sWhereDiarioFinal);
      $rsDiarioFinal     = $oDaoDiarioFinal->sql_record($sSqlDiarioFinal);
      $iLinhaDiarioFinal = $oDaoDiarioFinal->numrows;
      
      if ($iLinhaDiarioFinal > 0) {
        
        for ($i = 0; $i < $iLinhaDiarioFinal; $i++) {
          
          $iDiarioFinal = db_utils::fieldsMemory( $rsDiarioFinal, $i )->ed74_i_codigo;
          
          $oDaoProgrParcialAlunoEncerrado->excluir(null, "ed151_progressaoparcialaluno = {$oRemover->ed95_i_codigo}");
          if ($oDaoProgrParcialAlunoEncerrado->erro_status == 0) {
            
            $this->sMsgErro = str_replace("\\n", "\n", $oDaoProgrParcialAlunoEncerrado->erro_sql);
            return false;
          }

          /**
           * Busca o código da progressão do aluno com base no diário final
           */
          $sSqlDiarioFinalOrigem = $oDaoProgrParcialAlunoDiarioFinalOrigem->sql_query_file( 
	                                                                                           null,
                                                                                             "ed107_progressaoparcialaluno",
                                                                                             null,
                                                                                             "ed107_diariofinal = {$iDiarioFinal}"
                                                                                          );
          $rsDiarioFinalOrigem = db_query( $sSqlDiarioFinalOrigem );
          
          if ( !$rsDiarioFinalOrigem ) {
            
            $this->sMsgErro = str_replace("\\n", "\n", $oDaoProgrParcialAlunoDiarioFinalOrigem->erro_sql);
            return false;
          }
          
          $iLinhasDiarioFinal = pg_num_rows( $rsDiarioFinalOrigem );
          
          /**
           * Percorre progressões retornadas de acordo com o diário final
           */
          for ( $iContador = 0; $iContador < $iLinhasDiarioFinal; $iContador++ ) {
            
            $iProgressaoAluno = db_utils::fieldsMemory( $rsDiarioFinalOrigem, $iContador )->ed107_progressaoparcialaluno;
            
            /**
             * Exclui o registro da tabela progressaoparcialalunodiariofinalorigem de acordo com o diario final
             */
            $oDaoProgrParcialAlunoDiarioFinalOrigem->excluir( null, "ed107_progressaoparcialaluno = {$iProgressaoAluno}" );
            if ($oDaoProgrParcialAlunoDiarioFinalOrigem->erro_status == 0) {
            
              $this->sMsgErro = str_replace("\\n", "\n", $oDaoProgrParcialAlunoDiarioFinalOrigem->erro_sql);
              return false;
            }
            
            $oDaoProgrParcialAluno->excluir(null, "ed114_sequencial = {$iProgressaoAluno}");
            if ($oDaoProgrParcialAluno->erro_status == 0) {
              
              $this->sMsgErro = str_replace("\\n", "\n", $oDaoProgrParcialAluno->erro_sql);
              return false;
            }
          }
        }
      }
      
      $oDaoDiarioFinal->excluir(null, $sWhereDiarioFinal);
      if ($oDaoDiarioFinal->erro_status == 0) {
        
        $this->sMsgErro = str_replace("\\n", "\n", $oDaoDiarioFinal->erro_sql);
        return false;
      }
      
      /**
       * Busca os diarioresultado para excluir parecerresult
       */
      $sWhereDiarioResultado = " ed73_i_diario = {$oRemover->ed95_i_codigo}";
      $sSqlDiarioResultado   = $oDaoDiarioResultado->sql_query_file(null, "ed73_i_codigo", null, $sWhereDiarioResultado);
      $rsDiarioResultado     = $oDaoDiarioResultado->sql_record($sSqlDiarioResultado);
      $iLinhaDiarioResultado = $oDaoDiarioResultado->numrows;

      if ($iLinhaDiarioResultado > 0) {

        $iDiarioResultado = db_utils::fieldsMemory( $rsDiarioResultado, 0 )->ed73_i_codigo;

        $oDaoParecerResult->excluir(null, "ed63_i_diarioresultado = {$iDiarioResultado}");
        if ($oDaoParecerResult->erro_status == 0) {
          
          $this->sMsgErro = str_replace("\\n", "\n", $oDaoParecerResult->erro_sql);
          return false;
        }
      }

      $oDaoDiarioResultado->excluir(null, $sWhereDiarioResultado);
      if ($oDaoDiarioResultado->erro_status == 0) {
      
        $this->sMsgErro = str_replace("\\n", "\n", $oDaoDiarioResultado->erro_sql);
        return false;
      }
      
      /**
       * Exclui o diário do aluno; 
       */
      $oDaoDiario->excluir($oRemover->ed95_i_codigo);
      if ($oDaoDiario->erro_status == 0) {
        
        $this->sMsgErro = str_replace("\\n", "\n", $oDaoDiario->erro_sql);
        return false;
      }
    }
    
    return true;
  }
  
  
  /**
   * Passa o diário do aluno incorreto para o aluno correto 
   * @param integer $iChaveCorreta código do aluno correto
   * @param integer $iChaveIncorreta código do aluno que deve ser substituido / removido
   * @return boolean
   */
  private function alteraDiario($iChaveCorreta, $iChaveIncorreta) {
    
    $sSqlDiario  = " update diario ";
    $sSqlDiario .= "    set ed95_i_aluno = {$iChaveCorreta}  ";
    $sSqlDiario .= "  where ed95_i_aluno = {$iChaveIncorreta}";
    
    $rsDiario   = db_query($sSqlDiario);
    
    if (!$rsDiario) {
      
      $this->sMsgErro = $sSqlDiario;
      return false;
    }
    
    return true;
  }
  
  /**
   * Retorna uma mensagem de erro  
   * @see IExcecaoProcessamentoDependencias::getMensagemErro()
   */
  public function getMensagemErro() {
    
    return $this->sMsgErro;
  }
}