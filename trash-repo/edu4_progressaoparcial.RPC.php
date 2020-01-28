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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("dbforms/db_funcoes.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");

$iEscola           = db_getsession("DB_coddepto");
$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

/**
 * Constante definida com o caminho onde se encontram as mensagens do RPC
 */
define( "CAMINHO_MENSAGENS", "educacao.escola.edu4_progressaoparcial." );

try {

  switch( $oParam->exec ) {

    /**
     * Retorna as progress�es de um aluno
     *
     * @param integer $oParam->iAluno - C�digo do aluno
     *
     * - Par�metros Opcionais
     * @param boolean $oParam->lEscolaSessao - Retorna somente progress�es da escola da sess�o
     * @param boolean $oParam->lInativos     - Retorna progress�es inativas
     *
     * @return array aProgressoes[]
     *         ..... stdClass
     *         .............. integer iCodigo     - C�digo da progress�o
     *         .............. integer iEtapa      - C�digo da etapa da progress�o
     *         .............. string  sEtapa      - Nome da etapa da progress�o
     *         .............. integer iDisciplina - C�digo da disciplina da progress�o
     *         .............. string  sDisciplina - Nome da disciplina da progress�o
     *         .............. integer iAno        - Ano da progress�o
     *         .............. integer iEscola     - C�digo da escola da progress�o
     *         .............. string  sEscola     - Nome da escola da progress�o
     *         .............. string  sSituacao   - Situa��o da progress�o
     *         .............. integer iEnsino     - C�digo do ensino da etapa da progress�o
     *         .............. string sEnsino      - Nome do ensino da etapa da progress�o
     */
  	case 'buscaDadosProgressaoAluno':

  	  if ( !isset( $oParam->iAluno ) || empty( $oParam->iAluno ) ) {
  	    throw new ParameterException( _M( CAMINHO_MENSAGENS."aluno_nao_informado" ) );
  	  }

  	  $oRetorno->aProgressoes = array();
  	  $oAluno                 = AlunoRepository::getAlunoByCodigo( $oParam->iAluno );

  	  if (count( $oAluno->getProgressaoParcial() ) > 0 ) {

  	    /**
  	     * Percorre as progressoes do aluno para armazenar em um stdClass e incrementando o array
  	     */
  	    foreach ( $oAluno->getProgressaoParcial() as $oProgressaoParcial ) {

  	      if ( $oProgressaoParcial->isConcluida() ) {
  	        continue;
  	      }

  	      if (    !$oProgressaoParcial->getSituacaoProgressao()->isAtivo()
  	           && ( !isset( $oParam->lInativos ) || !$oParam->lInativos ) ) {
  	        continue;
  	      }

  	      /**
  	       * Caso tenha sido setado par�metro para validar a escola da sess�o e a progress�o seja de uma escola
  	       * diferente, n�o retorna a progress�o
  	       */
  	      if (    isset( $oParam->lEscolaSessao )
  	           && $oParam->lEscolaSessao
  	           && $oProgressaoParcial->getEscola()->getCodigo() != $iEscola ) {
  	        continue;
  	      }

  	      $oDadosProgressao              = new stdClass();
  	      $oDadosProgressao->iCodigo     = $oProgressaoParcial->getCodigoProgressaoParcial();
  	      $oDadosProgressao->iEtapa      = $oProgressaoParcial->getEtapa()->getCodigo();
  	      $oDadosProgressao->sEtapa      = urlencode( $oProgressaoParcial->getEtapa()->getNome() );
  	      $oDadosProgressao->iDisciplina = $oProgressaoParcial->getDisciplina()->getCodigoDisciplina();
  	      $oDadosProgressao->sDisciplina = urlencode( $oProgressaoParcial->getDisciplina()->getNomeDisciplina() );
  	      $oDadosProgressao->iAno        = $oProgressaoParcial->getAno();
  	      $oDadosProgressao->iEscola     = $oProgressaoParcial->getEscola()->getCodigo();
  	      $oDadosProgressao->sEscola     = urlencode( $oProgressaoParcial->getEscola()->getNome() );
  	      $oDadosProgressao->iEnsino     = $oProgressaoParcial->getEtapa()->getEnsino()->getCodigo();
  	      $oDadosProgressao->sEnsino     = urlencode( $oProgressaoParcial->getEtapa()->getEnsino()->getNome() );
  	      $oDadosProgressao->sSituacao   = urlencode( $oProgressaoParcial->getSituacaoProgressao()->getDescricao());
          $oDadosProgressao->lAtiva      = $oProgressaoParcial->getSituacaoProgressao()->isAtivo();

  	      $oRetorno->aProgressoes[] = $oDadosProgressao;
  	    }
  	  }

  	  break;

  	case 'atualizarSituacaoProgressao':

  	  if ( !isset( $oParam->aProgressoes ) || empty( $oParam->aProgressoes ) ) {
  	    throw new ParameterException( _M( CAMINHO_MENSAGENS."progressao_nao_informada" ) );
  	  }

  	  db_inicio_transacao();

  	  foreach ($oParam->aProgressoes as $oProgressao) {

  	    foreach ($oProgressao->aProgressoes as $iProgressao) {

  	      $oProgressaoParcialAluno = new ProgressaoParcialAluno($iProgressao);
  	      $oProgressaoParcialAluno->alterarSituacao($oProgressao->situacao);
  	    }

  	  }

  	  $oRetorno->message = urlencode( _M( CAMINHO_MENSAGENS."progressao_atualizada" ) );
  	  db_fim_transacao();

  	  break;
  }
} catch ( Exception $oErro ) {

  db_fim_transacao( true );
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode( $oErro->getMessage() );
}

echo $oJson->encode($oRetorno);