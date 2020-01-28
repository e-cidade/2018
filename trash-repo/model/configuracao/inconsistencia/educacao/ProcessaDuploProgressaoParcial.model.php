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

require_once 'model/configuracao/inconsistencia/iExcecaoProcessamentoDependencias.interface.php';

/**
 * Processa as exceções encontradas quando tentamos remover os duplos da progressao
 * @author Fabio <fabio.esteves@dbseller.com.br>
 */
class ProcessaDuploProgressaoParcial implements IExcecaoProcessamentoDependencias {
  
  /**
   * Mensagem de erro
   * @var string
   */
  private $sMsgErro;
  
  /**
   * Caminho das mensagens do model
   * @var string
   */
  const CAMINHO_MENSAGENS = "configuracao.inconsistencia.ProcessaDuploProgressaoParcial.";

  /**
   * Processa as dependências do aluno em relação a progressão parcial
   * Caso encontre conflitos dos dados, exclui os registros do aluno incorreto
   * Ao final, altera a progressão do aluno incorreto para o correto
   * 
   * @param integer $iChaveCorreta   código do aluno corréto
   * @param integer $iChaveIncorreta código do aluno que deve ser substituido / removido
   * 
   * @see IExcecaoProcessamentoDependencias::processar()
   */
  public function processar( $iChaveCorreta, $iChaveIncorreta ) {

    $oDaoProgressao    = new cl_progressaoparcialaluno();
    $sWhereProgressao  = "    ed114_aluno = {$iChaveIncorreta} ";
    $sWhereProgressao .= "and exists( select 1 ";
    $sWhereProgressao .= "              from progressaoparcialaluno progressaoincorreta ";
    $sWhereProgressao .= "             where progressaoincorreta.ed114_disciplina          = progressaoparcialaluno.ed114_disciplina ";
    $sWhereProgressao .= "               and progressaoincorreta.ed114_serie               = progressaoparcialaluno.ed114_serie ";
    $sWhereProgressao .= "               and progressaoincorreta.ed114_ano                 = progressaoparcialaluno.ed114_ano ";
    $sWhereProgressao .= "               and progressaoincorreta.ed114_situacaoeducacao    = " . ProgressaoParcialAluno::ATIVA;
    $sWhereProgressao .= "               and progressaoparcialaluno.ed114_situacaoeducacao = " . ProgressaoParcialAluno::ATIVA;
    $sWhereProgressao .= "               and progressaoparcialaluno.ed114_aluno            = {$iChaveCorreta} ) ";
    
    $sSqlConflitosProgressao = $oDaoProgressao->sql_query_file( null, "ed114_sequencial", null, $sWhereProgressao );
    $rsConflitosProgressao   = db_query( $sSqlConflitosProgressao );
    
    if ( !$rsConflitosProgressao ) {
      throw new DBException( _M( CAMINHO_MENSAGENS."erro_busca_conflitos" ) );
    }
    
    if ( pg_num_rows( $rsConflitosProgressao ) ) {
      
      $lRemoveProgresao = $this->removeRegistros( db_utils::getCollectionByRecord( $rsConflitosProgressao ) );
      
      if ( !$lRemoveProgresao ) {
        return false;
      }
    }
    
    return $this->alteraProgressao( $iChaveCorreta, $iChaveIncorreta );
  }
  
  /**
   * Apaga os registros da progressao do aluno
   * @param array $aRemover progressoes a serem removidas
   * 
   * @return boolean
   */
  private function removeRegistros( $aConflitosProgressao ) {
    
    /**
     * Tabelas filhas de progressaoparcialaluno que terão os registros removidos
     */
    $oDaoAlunoEncerradoDiario   = new cl_progressaoparcialalunoencerradodiario();
    $oDaoAlunoMatricula         = new cl_progressaoparcialalunomatricula();
    $oDaoAlunoResultadoFinal    = new cl_progressaoparcialalunoresultadofinal();
    $oDaoAlunoTurmaRegencia     = new cl_progressaoparcialalunoturmaregencia();
    $oDaoAlunoDiarioFinalOrigem = new cl_progressaoparcialalunodiariofinalorigem();
    
    /**
     * Percorre os conflitos encontrados para remoção das tabelas filhas
     */
    foreach ( $aConflitosProgressao as $oConflitoProgressao ) {
      
      /**
       * Exclui da tabela progressaoparcialalunoencerradodiario de acordo com o código da progressao parcial
       */
      $oDaoAlunoEncerradoDiario->excluir( null, "ed151_progressaoparcialaluno = {$oConflitoProgressao->ed114_sequencial}" );
      if ( $oDaoAlunoEncerradoDiario->erro_status == 0 ) {
      
        $this->sMsgErro = str_replace( "\\n", "\n", $oDaoAlunoEncerradoDiario->erro_sql );
        return false;
      }
      
      /**
       * Busca os registros existentes na tabela progressaoparcialalunomatricula de acordo com o código da progressão
       * parcial, para exclusão dos registros nas tabelas filhas desta
       */
      $sWhereAlunoMatricula = "ed150_progressaoparcialaluno = {$oConflitoProgressao->ed114_sequencial}";
      $sSqlAlunoMatricula   = $oDaoAlunoMatricula->sql_query_file( null, "ed150_sequencial", null, $sWhereAlunoMatricula );
      $rsAlunoMatricula     = db_query( $sSqlAlunoMatricula );
      
      if ( !$rsAlunoMatricula ) {
        
        $this->sMsgErro = str_replace( "\\n", "\n", $oDaoAlunoMatricula->erro_sql );
        return false;
      }
      
      $iLinhasAlunoMatricula = pg_num_rows( $rsAlunoMatricula );
      if ( $iLinhasAlunoMatricula > 0 ) {
        
        for ( $iContador = 0; $iContador < $iLinhasAlunoMatricula; $iContador++ ) {
          
          $iAlunoMatricula = db_utils::fieldsMemory( $rsAlunoMatricula, $iContador )->ed150_sequencial;
          
          /**
           * Excluir da tabela progressaoparcialalunoresultadofinal de acordo com o código de progressaoparcialalunomatricula
           */
          $oDaoAlunoResultadoFinal->excluir( null, "ed121_progressaoparcialalunomatricula = {$iAlunoMatricula}" );
          if ( $oDaoAlunoResultadoFinal->erro_status == 0 ) {
          
            $this->sMsgErro = str_replace( "\\n", "\n", $oDaoAlunoResultadoFinal->erro_sql );
            return false;
          }
          
          /**
           * Excluir da tabela progressaoparcialalunoturmaregencia de acordo com o código de progressaoparcialalunomatricula
           */
          $oDaoAlunoTurmaRegencia->excluir( null, "ed115_progressaoparcialalunomatricula = {$iAlunoMatricula}" );
          if ( $oDaoAlunoTurmaRegencia->erro_status == 0 ) {
          
            $this->sMsgErro = str_replace( "\\n", "\n", $oDaoAlunoTurmaRegencia->erro_sql );
            return false;
          }
        }
      }
      
      /**
       * Exclui da tabela progressaoparcialalunomatricula de acordo com o código da progressao parcial
       */
      $oDaoAlunoMatricula->excluir( null, "ed150_progressaoparcialaluno = {$oConflitoProgressao->ed114_sequencial}" );
      if ( $oDaoAlunoMatricula->erro_status == 0 ) {
      
        $this->sMsgErro = str_replace( "\\n", "\n", $oDaoAlunoMatricula->erro_sql );
        return false;
      }
      
      /**
       * Exclui da tabela progressaoparcialalunodiariofinalorigem de acordo com o código da progressao parcial
       */
      $oDaoAlunoDiarioFinalOrigem->excluir( null, "ed107_progressaoparcialaluno = {$oConflitoProgressao->ed114_sequencial}" );
      if ( $oDaoAlunoDiarioFinalOrigem->erro_status == 0 ) {
      
        $this->sMsgErro = str_replace( "\\n", "\n", $oDaoAlunoDiarioFinalOrigem->erro_sql );
        return false;
      }
    }
    
    return true;
  }
  
  /**
   * Passa a progressao do aluno incorreto para o aluno correto 
   * @param integer $iChaveCorreta   código do aluno correto
   * @param integer $iChaveIncorreta código do aluno que deve ser substituido / removido
   * @return boolean
   */
  private function alteraProgressao( $iChaveCorreta, $iChaveIncorreta ) {
    
    $sSqlProgressaoParcial  = " update progressaoparcialaluno ";
    $sSqlProgressaoParcial .= "    set ed114_aluno = {$iChaveCorreta}  ";
    $sSqlProgressaoParcial .= "  where ed114_aluno = {$iChaveIncorreta}";
    
    $rsProgressaoParcial = db_query( $sSqlProgressaoParcial );
    
    if ( !$rsProgressaoParcial ) {
    
      $this->sMsgErro = $sSqlProgressaoParcial;
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