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
 * Singleton dos Par�metros da Farm�cia
 * @author Andr� Mello   <andre.mello@dbseller.com.br>
 * @author F�bio Esteves <fabio.esteves@dbseller.com.br>
 * @package farmacia
 */
class ParametrosFarmacia {

  /**
   * Inst�ncia de ParametrosFarmacia
   * @var ParametrosFarmacia
   */
  static private $oInstancia;

  /**
   * Propriedade que define se acumula ou n�o saldo
   *   1 - Acumula saldo;
   *   2 - N�o acumula saldo.
   * @var integer
   */
  private $iAcumularSaldoContinuado;

  /**
   * Tipo de per�odo a ser gerado para os medicamentos continuados
   *   1 - Representa a forma��o de per�odos est�ticos, que come�am � partir da data de inicio do medicamento continuado.
   *   2 - Representa a forma��o de per�odos din�micos, que come�am a partir d� primeira retirada de medicamentos feita
   *       pelo paciente e o pr�ximo per�odo come�a na margem do atual, caso seja feita alguma retirada de 
   *       medicamento nela.
   * @var integer
   */
  private $iTipoPeriodoContinuado;

  /**
   * Construtor da classe privado para que a classe n�o possa ser instanciada. 
   */
  private function __construct() {

    $oDaoParametrosFarmacia = new cl_far_parametros();
    $sCampos                = 'fa02_i_tipoperiodocontinuado, fa02_i_acumularsaldocontinuado';
    $sSqlParametrosFarmacia = $oDaoParametrosFarmacia->sql_query_file('', $sCampos);
    $rsParametrosFarmacia   = db_query( $sSqlParametrosFarmacia );

    if ( !$rsParametrosFarmacia ) {
      throw new DBException( 'Erro ao buscar os par�metros na farm�cia.\n' . pg_last_error() );
    }

    if ( pg_num_rows( $rsParametrosFarmacia ) > 0 ) {

      $oParametrosFarmacia            = db_utils::fieldsMemory( $rsParametrosFarmacia, 0 );
      $this->iAcumularSaldoContinuado = $oParametrosFarmacia->fa02_i_acumularsaldocontinuado;
      $this->iTipoPeriodoContinuado   = $oParametrosFarmacia->fa02_i_tipoperiodocontinuado;
    }

  }

  /**
   * Clone da classe privado para que a classe n�o possa ser instanciada. 
   */
  private function __clone() {}

  /**
   * Retorna uma inst�ncia de ParametrosFarmacia
   * @return ParametrosFarmacia 
   */
  private static function getInstance() {

    if ( self::$oInstancia == null ) {
      self::$oInstancia = new ParametrosFarmacia();
    }

    return self::$oInstancia;
  }

  /**
   * Retorna se deve acumular o saldo continuado
   * @return integer
   */
  public static function acumularSaldoContinuado() {
    return self::getInstance()->iAcumularSaldoContinuado;
  }

  /**
   * Retorna o tipo do pedido continuado
   * @return integer
   */
  public static function tipoPeriodoContinuado() {
    return self::getInstance()->iTipoPeriodoContinuado;
  }

}