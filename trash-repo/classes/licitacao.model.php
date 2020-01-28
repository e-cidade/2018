<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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


class licitacao {


  private $iCodLicitacao   = null;
  private $aItensLicitacao = array();
  private $oDados          = null;
  private $oDaoLicita      = null;
  function __construct($iCodLicitacao = null) {

    if (!empty($iCodLicitacao)) {
      $this->iCodLicitacao = $iCodLicitacao;
    }
    $this->oDaoLicita  = db_utils::getDao("liclicita");

  }


  /**
   * traz os Processos de compra VInculadas a licitacao.
   * @return array
   */ 

  function getProcessoCompras() {

    if ($this->iCodLicitacao == null) {

      throw new exception("Código da licitacao nulo");
      return false;

    }
    $oDaoLicitem  = db_utils::getDao("liclicitem");
    $sCampos      = "distinct pc80_codproc,coddepto, descrdepto,login,pc80_data,pc80_resumo";
    $rsProcessos  = $oDaoLicitem->sql_record(
                    $oDaoLicitem->sql_query_inf(null, $sCampos,"pc80_codproc",
                                                      "l21_codliclicita = {$this->iCodLicitacao}")
        );
    if ($oDaoLicitem->numrows > 0) {

      for ($iInd = 0; $iInd < $oDaoLicitem->numrows; $iInd++) {

        $aSolicitacoes[] = db_utils::fieldsMemory($rsProcessos, $iInd); 
      }
      return $aSolicitacoes;
    } else {
      return false;
    }

  }
  /**
   * retorna os Dados da Licitacao
   * @return object
   */
  function getDados() {

     $rsLicita     = $this->oDaoLicita->sql_record($this->oDaoLicita->sql_query($this->iCodLicitacao));
     $this->oDados = db_utils::fieldsMemory($rsLicita, 0);
     return $this->oDados;

  }
}