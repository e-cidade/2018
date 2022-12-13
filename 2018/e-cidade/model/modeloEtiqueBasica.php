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


abstract class modeloEtiquetaBasica {


	protected  $oImpressora;
	private    $sPorta;
	private    $sIp;
	private    $sModelo;

  /**
   * Classe que implementa a estrutura basica do modelo de etiqueta
   * @param string com ip da máquina onde esta a aimpressora $sIp
   * @param string com a porta a ser usada $sPorta
   * @param string com o modelo da impressora $sModelo
   */
  function __construct($sIp,$sPorta,$sModelo) {

    $this->sIp         = $sIp;
    $this->sPorta      = $sPorta;
    $this->sModelo     = $sModelo;
    $this->oImpressora = null;

    switch ($this->sModelo){

    	case 'OS-214':
    		require_once 'model/impressao.argoxOS214.php';
    		$this->oImpressora = new impressaoOS214($this->sIp,$this->sPorta);
    		break;

    	case 'OS-214-Plus':
    	  require_once 'model/impressao.argoxOS214_plus.php';
    	  $this->oImpressora = new impressaoOS214_plus($this->sIp,$this->sPorta);
    	  break;

      default:
      	throw new Exception("Nenhum modelo encontrado !");

    }

  }

}