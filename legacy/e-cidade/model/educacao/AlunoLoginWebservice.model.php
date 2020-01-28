<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/**
 * Classe para webservices de aluno
 * Atua como um facade para a classe de Aluno.
 * as informa��es Retornadas s�o oo codigo do aluno, nome
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