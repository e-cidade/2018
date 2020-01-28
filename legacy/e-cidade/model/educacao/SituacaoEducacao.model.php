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
 * Representacao das Situacoes Escolares de um aluno
 * @author: iuri@dbseller.com.br
 * @package Educacao
 *
 * @version $Revision: 1.4 $
 */

/**
 * Situacao da Educacao
 * Representa uma situacao do aluno dentro da educação, dentro de cada tipo de rotina
 * @package Educacao
 */
class SituacaoEducacao {

  /**
   * Codigo da Situacao
   * @var integer
   */
  private $iCodigo;

  /**
   * Descricao da Situacao
   * @var string
   */
  private $sDescricao;

  /**
   * Tipo da Situacao
   * @var integer
   */
  private $iTipoSituacao;

  /**
   * a Situacao tem estatus de ativo /inativo
   * @var boolean
   */
   private $lAtivo;

  /**
   * Caminho das Mensagens ao usuário
   */
  const CAMINHO_ARQUIVO = 'educacao.escola.SituacaoEducacao.';

  /**
   * Instância uma nova Situação, através do seu código
   * @param $iCodigo
   * @throws BusinessException
   */
  public function __construct($iCodigo) {

    if (empty($iCodigo) || !DBNumber::isInteger($iCodigo)) {
      throw new BusinessException(_M(self::CAMINHO_ARQUIVO."codigo_nao_informado"));
    }

    $oDaoSituacaoEducacao = new cl_situacaoeducacao;
    $sSqlSituacaoEducacao = $oDaoSituacaoEducacao->sql_query_file($iCodigo);
    $rsSituacaoEducacao   = $oDaoSituacaoEducacao->sql_record($sSqlSituacaoEducacao);

    if (!$rsSituacaoEducacao || $oDaoSituacaoEducacao->numrows == 0)  {
      throw new BusinessException(_M(self::CAMINHO_ARQUIVO."situacao_nao_encontrada"));
    }

    $oDadosSituacao      = db_utils::fieldsMemory($rsSituacaoEducacao, 0);
    $this->iCodigo       = $oDadosSituacao->ed109_sequencial;
    $this->sDescricao    = $oDadosSituacao->ed109_descricao;
    $this->lAtivo        = $oDadosSituacao->ed109_ativo == 't';
    $this->iTipoSituacao = $oDadosSituacao->ed109_tiposituacaoeducacao;

  }

  /**
   * Retorna o tipo da Situacao
   * @return int Tipo da Situacao
   */
  public function getTipoSituacao() {
    return $this->iTipoSituacao;
  }

  /**
   * Retorna o codigo da situação
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Verifica se  situacao é ativa
   * Informa para os registros que estiverem utilizando essa situacao devem ser utilizados como ativos
   * @return boolean
   */
  public function isAtivo() {
    return $this->lAtivo;
  }

  /**
   * Retorna a descricao da situacao
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }
}