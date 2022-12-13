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
 * Classe repository para classe ItinerarioVinculoAluno
 * @author  Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package transporteescolar
 */
class ItinerarioVinculoAlunoRepository {

  /**
   * Collection de ItinerarioVinculoAluno
   * @var array
   */
  private $aItinerarioVinculoAluno = array();

  /**
   * Instancia da classe
   * @var ItinerarioVinculoAlunoRepository
   */
  private static $oInstance;

  private function __construct() {}

  private function __clone() {}

  /**
   * Retorno uma instancia do ItinerarioVinculoAluno pelo Codigo
   * @param  integer $iCodigo - Código do ItinerarioVinculoAluno
   * @return ItinerarioVinculoAluno
   */
  public static function getItinerarioVinculoAlunoByCodigo($iCodigoItinerarioVinculoAluno) {

    if (!array_key_exists($iCodigoItinerarioVinculoAluno, ItinerarioVinculoAlunoRepository::getInstance()->aItinerarioVinculoAluno)) {
      ItinerarioVinculoAlunoRepository::getInstance()->aItinerarioVinculoAluno[$iCodigoItinerarioVinculoAluno] = new ItinerarioVinculoAluno($iCodigoItinerarioVinculoAluno);
    }

    return ItinerarioVinculoAlunoRepository::getInstance()->aItinerarioVinculoAluno[$iCodigoItinerarioVinculoAluno];
  }

  /**
   * Retorna a instancia da classe
   * @return ItinerarioVinculoAlunoRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new ItinerarioVinculoAlunoRepository();
    }

    return self::$oInstance;
  }

  /**
   * Adiciona um ItinerarioVinculoAluno ao repositório
   * @param  ItinerarioVinculoAluno $oItinerarioVinculoAluno - Instância de ItinerarioVinculoAluno
   * @return boolean
   */
  public static function adicionarItinerarioVinculoAluno(ItinerarioVinculoAluno $oItinerarioVinculoAluno) {

    if(!array_key_exists($oItinerarioVinculoAluno->getCodigoItinerarioVinculoAluno(), ItinerarioVinculoAlunoRepository::getInstance()->aItinerarioVinculoAluno)) {
      ItinerarioVinculoAlunoRepository::getInstance()->aItinerarioVinculoAluno[$oItinerarioVinculoAluno->getCodigoItinerarioVinculoAluno()] = $oItinerarioVinculoAluno;
    }

    return true;
  }

  /**
   * Remove o ItinerarioVinculoAluno passado como parametro do repository
   * @param  ItinerarioVinculoAluno $oItinerarioVinculoAluno
   * @return boolean
   */
  public static function removerItinerarioVinculoAluno(ItinerarioVinculoAluno $oItinerarioVinculoAluno) {

    if (array_key_exists($oItinerarioVinculoAluno->getCodigoItinerarioVinculoAluno(), ItinerarioVinculoAlunoRepository::getInstance()->aItinerarioVinculoAluno)) {
      unset(ItinerarioVinculoAlunoRepository::getInstance()->aItinerarioVinculoAluno[$oItinerarioVinculoAluno->getCodigoItinerarioVinculoAluno()]);
    }

    return true;
  }

  /**
   * Busca todos os vínculos de Itinerário e Aluno através de seu ponto de parada
   * @param  ItinerarioPontoParada $oItinerarioPontoParada
   * @return ItinerarioVinculoAluno[]
   */
  public static function getItinerarioVinculoAlunoPorPontoParada(ItinerarioPontoParada $oItinerarioPontoParada ) {

    $aVinculoAlunoPontoParada = array();
    $sWhere                   = "tre12_linhatransportepontoparada = {$oItinerarioPontoParada->getCodigo()}";
    $oDaoVinculoAluno         = new cl_linhatransportepontoparadaaluno();
    $sSql                     = $oDaoVinculoAluno->sql_query_file(null, "tre12_sequencial", null, $sWhere);
    $rsVinculoAluno           = db_query($sSql);
    
    if ( $rsVinculoAluno && pg_num_rows($rsVinculoAluno) > 0) {

      $iLinhas = pg_num_rows($rsVinculoAluno);

      for ( $iContador = 0; $iContador < $iLinhas; $iContador++ ) {

        $iCodigoVinculoAluno        = db_utils::fieldsMemory($rsVinculoAluno, $iContador)->tre12_sequencial;
        $aVinculoAlunoPontoParada[] = ItinerarioVinculoAlunoRepository::getItinerarioVinculoAlunoByCodigo($iCodigoVinculoAluno);
      }
    }
    return $aVinculoAlunoPontoParada;
  }
}
?>