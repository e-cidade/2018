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
 * Classe para as atividades de um docente em uma escola
 * @package educacao
 * @subpackage recursohumano
 * @author Fabio Esteves - fabio.esteves@dbseller.com.br
 */
class DocenteAtividade {
  
  /**
   * Instancia da atividade
   * @var AtividadeEscolar
   */
  protected $oAtividade;
  
  public function __construct($iCodigo = null) {
    
    if (!empty($iCodigo)) {
      
      $oDaoRecHumanoAtiv   = db_utils::getDao("rechumanoativ");
      $sWhereRecHumanoAtiv = "ed22_i_codigo = {$iCodigo}";
      $sSqlRecHumanoAtiv   = $oDaoRecHumanoAtiv->sql_query_file(null, "*", null, $sWhereRecHumanoAtiv);
      $rsRecHumanoAtiv     = $oDaoRecHumanoAtiv->sql_record($sSqlRecHumanoAtiv);
      
      if ($oDaoRecHumanoAtiv->numrows > 0) {
        
        $oDadosRecHumanoAtiv = db_utils::fieldsMemory($rsRecHumanoAtiv, 0);
        $this->oAtividade    = new AtividadeEscolar($oDadosRecHumanoAtiv->ed22_i_atividade);
        unset($oDadosRecHumanoAtiv);
      }
    }
  }
  
  /**
   * Retorna uma instancia da atividade do docente
   * @return AtividadeEscolar
   */
  public function getAtividade() {
    return $this->oAtividade;
  }
  
  /**
   * Seta uma instancia da atividade do docente
   * @param AtividadeEscolar $oAtividadeEscolar
   */
  public function setAtividade(AtividadeEscolar $oAtividadeEscolar) {
    $this->oAtividade = $oAtividadeEscolar;
  }
}