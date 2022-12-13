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
 * Classe repository da classe LoteProcessoCompra 
 * @author $Author: dbmarcos $
 * @version $Revision: 1.1 $
 */

class LoteProcessoCompraRepository {

  /**
   * Instância do repository
   * 
   * @static
   * @var LoteProcessoCompraRepository
   * @access private
   */
  private static $oInstance;
  
  /**
   * Lotes instanciados
   * 
   * @var LoteProcessoCompra[]
   * @access private
   */
  private $aLotes = array();
  
  /**
   * Retorna instância do repository
   *
   * @access private
   * @return LoteProcessoCompraRepository
   */
  private function getInstance() {

    if(self::$oInstance == null) {
      self::$oInstance = new LoteProcessoCompraRepository();
    }

    return self::$oInstance;
  }
  
  /**
   * Retorna o lote pelo código
   *
   * @param Integer $iCodigo
   * @static
   * @access public
   * @return LoteProcessoCompra
   */
  public static function getLoteByCodigo($iCodigo) {
    
    if (!array_key_exists($iCodigo, LoteProcessoCompraRepository::getInstance()->aLotes)) {
      LoteProcessoCompraRepository::getInstance()->aLotes[$iCodigo] = new LoteProcessoCompra($iCodigo);
    }
    return LoteProcessoCompraRepository::getInstance()->aLotes[$iCodigo];
  }
  
}
