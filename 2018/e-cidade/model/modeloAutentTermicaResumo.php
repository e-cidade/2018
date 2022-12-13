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

require_once 'model/modeloAutentTermicaBasica.php';

class modeloAutentTermicaResumo extends modeloAutentTermicaBasica {
  
  private $sBuffer     = '';
  
  // private $oImpressora = null;
  
  /**
   * 
   */
  function __construct($sIp,$sPorta) {
    parent::__construct($sIp,$sPorta);
  }
  
  function imprimir() {
    
    $this->sBuffer .= "\n";
    $this->sBuffer .= "\n<b>".str_pad("Contribuinte:",15," ",STR_PAD_RIGHT)." </b>" ;
    $this->sBuffer .= "\n<b>".str_pad("Origem:",15," ",STR_PAD_RIGHT)." </b>" ;
    $this->sBuffer .=   "<b>".str_pad("Tipo:",15," ",STR_PAD_RIGHT)." </b>" ;
    $this->sBuffer .= "\n<b>".str_pad("Tipo de Documento:",15," ",STR_PAD_RIGHT)." </b>" ;
    
    $this->sBuffer .= "\n".str_pad("",46,"-",STR_PAD_BOTH) ;
    $this->sBuffer .= "\n<b>".str_pad("Cód. Barras:",15," ",STR_PAD_RIGHT)."</b>" ;
    $this->sBuffer .=      str_pad("123456789000123456789",33," ",STR_PAD_LEFT) ;
    $this->sBuffer .= "\n".str_pad("123456789000123456789",48," ",STR_PAD_LEFT);

    $this->sBuffer .= "\n".str_pad("",46,"-",STR_PAD_BOTH) ;
    $this->sBuffer .= "\n<b>".str_pad("Linha Digitável:",17," ",STR_PAD_RIGHT)."</b>" ;
    $this->sBuffer .=      str_pad("1234567890  00123456789",31," ",STR_PAD_LEFT) ;
    $this->sBuffer .= "\n".str_pad("1234567890  00123456789",48," ",STR_PAD_LEFT);
    
    $this->sBuffer .= "\n".str_pad("",46,"-",STR_PAD_BOTH);
    $this->sBuffer .= "\n<b>".str_pad("Data Pagamento:",15," ",STR_PAD_RIGHT)."</b>" ;
    $this->sBuffer .=      str_pad("13/04/2009",33," ",STR_PAD_LEFT) ;
    
    $this->sBuffer .= "\n<b>".str_pad("Tipo Autent:",15," ",STR_PAD_RIGHT)."</b>" ;
    $this->sBuffer .=      str_pad("Pagamento",15," ",STR_PAD_BOTH) ;
    $this->sBuffer .=      str_pad("15263",18," ",STR_PAD_LEFT) ;
    
    $this->sBuffer .= "\n<b>".str_pad("Valor Pago:",15," ",STR_PAD_RIGHT)."</b>" ;
    $this->sBuffer .=      str_pad("R$ ".db_formatar($this->getValorTotal()),33," ",STR_PAD_LEFT) ;
    
    parent::imprimir($this->sBuffer);
        
  }
  
  
  
}

?>