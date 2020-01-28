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
 * Classificao do arcordo
 *
 * @package Contrato
 */
class AcordoClassificacao {
  
  /**
   * Classificaoes
   */
  const EXECUCAO_CONTINUADA = 1;
  const ESCOPO = 2;

  /**
   * Codigo da classificacao
   *
   * @private
   * @var integer
   */
  private $iCodigo;

  /**
   * Descricao da classificacao
   *
   * @private
   * @var string
   */
  private $sDescricao;

  /**
   * Construtor
   *
   * @param integer $iCodigo 
   */
  public function __construct($iCodigo = 0) {

    if (empty($iCodigo)) {
      return;
    }

    $oDaoClassificacao = new cl_acordoclassificacao();
    $sSql = $oDaoClassificacao->sql_query_file($iCodigo);
    $rsClassificacao = $oDaoClassificacao->sql_record($sSql);

    if ($oDaoClassificacao->erro_status == '0') {
      throw new Exception($oDaoClassificacao->erro_msg);
    }

    $oDadosClassificacao = db_utils::fieldsMemory($rsClassificacao, 0);
    $this->iCodigo    = $iCodigo;
    $this->sDescricao = $oDadosClassificacao->ac46_descricao;
  }

  /**
   * Retorna o codigo da classificacao
   *
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a descricao da classificacao
   *
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

}

