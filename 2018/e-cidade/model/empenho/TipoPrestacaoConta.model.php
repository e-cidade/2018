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
 * Model responsбvel pelos tipos de prestaзгo de contas.
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @package empenho
 * @version $Revision: 1.1 $
 */
class TipoPrestacaoConta {

  /**
   * Cуdigo sequencial
   * @var integer
   */
  private $iCodigo;

  /**
   * Descriзгo da prestaзгo de contas
   * @var string
   */
  private $sDescricao;

  /**
   * Tipo de Obrigaзгo da Prestaзгo de Contas
   * @var integer
   */
  private $iCodigoObrigacao;

  /* 
   * @todo verificar a real necessidade das constantes
   */
  //const NAO_OBRIGA     = "Nгo Obriga";
  //const OBRIGA_VALORES = "Obriga Valores";
  //const OBRIGA_CONTAS  = "Obriga Contas";

  /**
   * Constrуi o objeto de acordo com o sequencial informado
   * @return this
   */
  public function __construct($iCodigo = null) {

    $this->iCodigo = $iCodigo;
    if (!empty($this->iCodigo)) {

      $oDaoEmpPrestaTip = db_utils::getDao("empprestatip");
      $sSqlBuscaTipo    = $oDaoEmpPrestaTip->sql_query_file($this->iCodigo);
      $rsBuscaTipo      = $oDaoEmpPrestaTip->sql_record($sSqlBuscaTipo);
      if ($oDaoEmpPrestaTip->erro_status == "0") {

        $sCaminhoMensagem = "financeiro.empenho.TipoPrestacaoConta.tipo_prestacao_nao_encontrado";
        throw new BusinessException(_M($sCaminhoMensagem));
      }

      $oStdDado               = db_utils::fieldsMemory($rsBuscaTipo, 0);
      $this->sDescricao       = $oStdDado->e44_descr;
      $this->iCodigoObrigacao = $oStdDado->e44_obriga;
    }
  }

  /**
   * Retorna o Cуdigo Sequencial do tipo da prestaзгo de contas
   * @return integer
   */ 
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a descriзгo da prestaзгo de contas
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Retorna o tipo da obrigacao
   * @return string
   */
  public function getTipoObrigacao() {
    return $this->iCodigoObrigacao;
  }
}
?>