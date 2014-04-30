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
 * ORgao emissor de carteiras de identidade
 * @author Iuri Andrei Guntchnigg
 * @package Educacao
 * @subpackage censo
 * @version $Revision: 1.1 $
 */
final class CensoOrgaoEmissorRG {
  
  /**
   * Codigo do orgao
   * @var inyeger
   */
  protected $iCodigo;
  
  /**
   * Nome do orgao
   * @var string
   */
  protected $sNome;
  
  /**
   * mensagens de erro
   * @var string
   */
  const CAMINHO_MENSAGENS = 'educacao.escola.CensoOrgaoEmissorRG';
  
  /**
   * Cria uma nova instância do orgao emissor
   * @param integer $iCodigo codigo do orgao emissor
   * @throws ParameterException
   */
  public function __construct($iCodigo) {
    
    if (!empty($iCodigo)) {
      
      if (!DBNumber::isInteger($iCodigo)) {
        throw new ParameterException(_M(self::CAMINHO_MENSAGENS.".codigo_nao_inteiro"));
      }
      $oDaoCensoOrgaoEmissorUF = new cl_censoorgemissrg();
      $sSqlOrgaoEmissor        = $oDaoCensoOrgaoEmissorUF->sql_query_file($iCodigo);
      $rsOrgaoEmissor          = $oDaoCensoOrgaoEmissorUF->sql_record($sSqlOrgaoEmissor);
      if (!$rsOrgaoEmissor || $oDaoCensoOrgaoEmissorUF->numrows == 0) {
        throw new ParameterException(_M(self::CAMINHO_MENSAGENS.".orgao_nao_cadastrado"));
      }
      
      $this->iCodigo = $iCodigo;
      $this->sNome   = db_utils::fieldsMemory($rsOrgaoEmissor, 0)->ed132_c_descr;
    }
  }
  
  /**
   * Retorna o codigo do Orgao
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }
  
  /**
   * Retorna o nome do orgao emissão
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }
}