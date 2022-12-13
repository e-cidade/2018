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

define("MENSAGEM_SAUDECONFIGURACAO", "saude.ambulatorial.SaudeConfiguracao.");

/**
 * Classe para controle das Configurações da Saúde
 * @author André Mello   andre.mello@dbseller.com.br
 * @package Ambulatorial
 */
class SaudeConfiguracao {

  /**
   * Controla se obriga informar CNS
   * @var boolean
   */
  private $lObrigarCns = false;

  /**
   * Busca todos as configurações da saúde e define as propriedades da classe
   */
  public function __construct() {

    $oSauConfigDao    = new cl_sau_config();
    $sSqlConfiguracao = $oSauConfigDao->sql_query_file();
    $rsConfiguracao   = db_query( $sSqlConfiguracao );

    if ( !$rsConfiguracao ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( MENSAGEM_SAUDECONFIGURACAO . 'erro_buscar_configuracao', $oErro ) );
    }

    if ( pg_num_rows($rsConfiguracao) > 0 ) {

      $oDadosConfiguracao = db_utils::fieldsMemory($rsConfiguracao, 0);
      $this->lObrigarCns  = $oDadosConfiguracao->s103_obrigarcns == 't';
    }
  }

  /**
   * Retorna se deve obrigar informar CNS
   * @return boolean
   */
  public function obrigarCns() {
    return $this->lObrigarCns;
  }

  /**
   * Define se deve ser obrigado informar CNS
   * @param boolean $lObrigarCns
   */
  public function setObrigarCns( $lObrigarCns ) {
    $this->lObrigarCns = $lObrigarCns;
  }
}