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
 * Processa as exceções encontradas
 * @package configuracao
 * @subpackage inconsistencia 
 * @author Andrio <andrio.costa@dbseller.com.br>
 */
class ProcessaDuploPadrao implements IExcecaoProcessamentoDependencias {
  
  /**
   * Mensagem de erro
   * @var string
   */
  private $sMsgErro;
  
  /**
   * Nome da tabela que será afetada
   * @var string
   */
  private $sTabela;
  
  /**
   * Nome do campo de 
   * @var string
   */
  private $sCampo;
  
  /**
   * Construtor deve receber o nome da tabela e campo que sera utilizado para processar
   * @param string $sTabela
   * @param string $sCampo
   * @return boolean
   */
  public function __construct($sTabela, $sCampo) {
    
    if (empty($sTabela)) {
      
      $this->sMsgErro = "Uma tabela deve ser definida.";
      return false;
    }
    
    if (empty($sCampo)) {
      
      $this->sMsgErro = "O campo de referência deve ser alterado.";
      return false;
    }
    
    $this->sTabela = $sTabela;
    $this->sCampo  = $sCampo;
  }
  
  /**
   * Realiza o processo padrão para remover o registro duplo, que consiste em trocar o registro errado pelo correto
   * @see IExcecaoProcessamentoDependencias::processar()
   */
  public function processar($iChaveCorreta, $iChaveIncorreta) {
  
    
    /**
     * Altera as dependencias para o codigo do registro correto
     */
    $sSqlDependencia  = "update {$this->sTabela}                     ";
    $sSqlDependencia .= "   set {$this->sCampo} = {$iChaveCorreta}   ";
    $sSqlDependencia .= " where {$this->sCampo} = {$iChaveIncorreta} ";
    
    $rsDependencia    = db_query($sSqlDependencia);
    
    /**
     * Erro na query das dependencias, grava log, retorna para o savepoint e continua o foreach
    */
    if ( !$rsDependencia ) {
    
      $this->sMsgErro = $sSqlDiario;
      return false;
    }
    return true;
  }
  
  /**
   * @see IExcecaoProcessamentoDependencias::getMensagemErro()
   */
  public function getMensagemErro() {
    return $this->sMsgErro;
  }
}