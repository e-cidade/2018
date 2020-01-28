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
 * Classe abstrata para processar as Situações do Cadastro Único
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @package social
 * @subpackage cadastrounico
 */
abstract class ProcessarSituacaoCadastroUnico {
  
  protected $fArquivoLog;
  
  /**
   * Insere um tipo de situacao para um cidadao que possui cadastro único
   * @param integer $iCidadaoCadastroUnico
   * @throws DBException
   * @return boolean
   */
  public function insereSituacao ($iCidadaoCadastroUnico) {
    
    $oDaoCadastroUnicoSituacao = new cl_cadastrounicosituacao();
    $oDaoCadastroUnicoSituacao->as12_cidadaocadastrounico      = $iCidadaoCadastroUnico;
    $oDaoCadastroUnicoSituacao->as12_tiposituacaocadastrounico = $this->iTipoSituacao; 
    $oDaoCadastroUnicoSituacao->as12_sequencial                = null;
    
    $oDaoCadastroUnicoSituacao->incluir(null);
    
    if ($oDaoCadastroUnicoSituacao->erro_status == 0) {
      
      throw new DBException(str_replace('\\n', "\n", $oDaoCadastroUnicoSituacao->erro_msg));
    }
    return true;    
  }
  
  /**
   * Remove um tipo de situacao para um cidadao que possui cadastro único
   * @throws DBException
   * @return boolean
   */
  public function removerSituacao () {
    
    $sWhere = " as12_tiposituacaocadastrounico = {$this->iTipoSituacao}";
    
    $oDaoCadastroUnicoSituacao = new cl_cadastrounicosituacao();
    $oDaoCadastroUnicoSituacao->excluir(null, $sWhere);
    
    if ($oDaoCadastroUnicoSituacao->erro_status == 0) {
    
      throw new DBException(str_replace('\\n', "\n", $oDaoCadastroUnicoSituacao->erro_msg));
    }
    return true;
  }
  
  /**
   * Escreve uma linha no arquivo de registros não processados
   * @param integer $iCidadaoCadastroUnico
   */
  public function escreveArquivoRegistrosNaoProcessados ($sLinhaArquivo) {
    
    fputs($this->fArquivoLog, $sLinhaArquivo);
  }
  
}