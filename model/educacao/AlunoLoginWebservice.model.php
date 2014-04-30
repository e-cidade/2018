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
 * Classe para webservices de aluno
 * Atua como um facade para a classe de Aluno.
 * as informações Retornadas são oo codigo do aluno, nome
 * @author dbseller
 *
 */
class AlunoLoginWebservice {

  private $iCodigoAluno = null;
  private $oAluno       = null;
  private $oDadosAluno  = null;
  public function __construct($iCodigoAluno) {
    
    $this->oAluno  = new Aluno($iCodigoAluno);
    if ($this->oAluno->getCodigoAluno() == null || $this->oAluno->getNome() == "") {
      throw new ParameterException(_M('educacao.escola.AlunoLoginWebservice.aluno_nao_encontrado'));
    }
  }
  
  /**
   * Retorna todos os Dados de login do aluno como um stdclass
   * @example
   *  $oAluno   = new AlunoLoginWebservice(1);
   *  $oRetorno = $oAluno->getLogin();
   *  print_r($oRetorno);
   *  retornara o Seguinte Objeto "Aluno
   *                                   (codigo) = 1;
   *                                   (Nome)   = 'MARIA DA SILVA';
   * @return StdClass
   */
  public function getLogin() {
    
    if ($this->oAluno == null) {
      throw new ParameterException(_M('educacao.escola.AlunoLoginWebservice.login_invalido'));
    }
    
    $oLogin         = new stdClass();
    $oLogin->codigo = $this->oAluno->getCodigoAluno();
    $oLogin->nome   = utf8_encode($this->oAluno->getNome());
    return $oLogin;
  }
  
}