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
define( "MENSAGENS_SETOR", "saude.laboratorio.Setor." );

/**
 * Model para controle de registro para a tabela lab_setor
 * @package Laboratorio
 * @author André Mello andre.mello@dbseller.com.br
 * @author Fabio Esteves fabio.esteves@dbseller.com.br
 */
class Setor {

  /**
   * Código do setor
   * @var integer
   */
  private $iCodigo;

  /**
   * Descrição do setor
   * @var string
   */
  private $sDescricao;

  /**
   * Construtor da classe
   * @param integer $iCodigo
   */
  public function __construct( $iCodigo = null ) {

    if ( !empty( $iCodigo ) ) {

      $oDaoSetor = new cl_lab_setor();
      $sSqlSetor = $oDaoSetor->sql_query_file( $iCodigo );
      $rsSetor   = db_query( $sSqlSetor );

      if ( !$rsSetor ) {

        $oMensagem        = new stdClass();
        $oMensagem->sErro = pg_result_error( $rsSetor );
        throw new DBException( _M( MENSAGENS_SETOR . "erro_sql", $oMensagem ) );
      }

      if ( pg_num_rows( $rsSetor ) == 0 ) {
        throw new DBException( _M( MENSAGENS_SETOR . "setor_nao_encontrado") );
      }

      $oDados           = db_utils::fieldsMemory( $rsSetor, 0 );
      $this->iCodigo    = $oDados->la23_i_codigo;
      $this->sDescricao = $oDados->la23_c_descr;
    }
  }

  /**
   * Retorna o código do setor
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a descriçao do setor
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Seta a descrição do setor
   * @param string $sDescricao
   */
  public function setDescricao( $sDescricao ) {
    $this->sDescricao = $sDescricao;
  }
}