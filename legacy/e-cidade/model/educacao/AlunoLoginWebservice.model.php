<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
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
    
    $oLogin->email  = null;
    
    if ( DBString::isEmail($this->oAluno->getEmail()) ) {
      $oLogin->email  = utf8_encode($this->oAluno->getEmail());
    }

    return $oLogin;
  }
}