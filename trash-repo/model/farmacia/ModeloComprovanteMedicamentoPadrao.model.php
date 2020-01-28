<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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


require_once ('model/farmacia/interfaces/IComprovanteMedicamento.interface.php');
class ModeloComprovanteMedicamentoPadrao implements IComprovanteMedicamento {
  
  protected $oModeloImpressora = null;
  
  protected $sIp;
  
  protected $iNumeroVias = 2;
  /**
   * 
   */
  function __construct() {

  }
  
  
  /**
   * Retorna o numero de Vias que devem ser impressas
   */
  public function getNumeroVias() {
    return $this->iNumeroVias;
  }
  /**
   * Imprime os dados do comprovante
   * @see IComprovanteMedicamento::imprimir()
   */
  function imprimir(ComprovanteEntregaMedicamento $oComprovante) {

    $sMensagemRecibo                 = '';
    $oDaoFarComprovanteTermicaConfig = db_utils::getDao("farcomprovantetermicaconfig");
    $sWhere                          = "fa57_coddepto = {$oComprovante->getDepartamento()}";
    $sSqlMensagemRecibo              = $oDaoFarComprovanteTermicaConfig->sql_query_file(null, "*", null, $sWhere);
    $rsMensagemRecibo                = $oDaoFarComprovanteTermicaConfig->sql_record($sSqlMensagemRecibo);
    if ($oDaoFarComprovanteTermicaConfig->numrows > 0) {
      $sMensagemRecibo = db_utils::fieldsMemory($rsMensagemRecibo, 0)->fa57_mensagem;  
    }
    /*
     * Monta o Laypout do Recibo.
     */
    $oDadosInstituicao = db_stdClass::getDadosInstit();
    $this->oModeloImpressora->modoCondensado(true);
    for ($iVias = 1; $iVias <= $this->getNumeroVias(); $iVias++) { 
    
      $this->oModeloImpressora->escrever("<b>{$oDadosInstituicao->nomeinst}</b>", true, 64, 'C');
      $this->oModeloImpressora->escrever('<b>SECRETARIA MUNICIPAL DA SAUDE</b>', true, 64, 'C');
      $this->oModeloImpressora->escrever("<b>{$oComprovante->getDescricaoDepartamento()}</b>", true, 64, 'C');
      $this->oModeloImpressora->escrever("{$oComprovante->getTelefoneDepartamento()}", true, 64, 'C');
      $sNomeCgs = "{$oComprovante->getSolicitante()->getCodigo()} - {$oComprovante->getSolicitante()->getNome()}";
      $this->oModeloImpressora->escrever("<b>CGS:</b>{$sNomeCgs}", true, 64, 'L');
      $this->oModeloImpressora->escrever("<b>DATA:</b>{$oComprovante->getData()}", true, 64, 'L');
      $this->oModeloImpressora->escrever("<b>HORA:</b>{$oComprovante->getHora()}", true, 64, 'L');
      $this->oModeloImpressora->escrever('RECIBO DE ENTREGA DE MEDICAMENTOS', true, 64, 'C');
      $this->oModeloImpressora->escrever("Numero:{$oComprovante->getCodigo()}", true, 64, 'C');
      $this->oModeloImpressora->escrever('Codigo', false, 9, 'L');
      $this->oModeloImpressora->escrever('Descricao', false, 40, 'L');
      $this->oModeloImpressora->escrever('Unid', false, 5, 'L');
      $this->oModeloImpressora->escrever('Quant', true, 10, 'R');
      foreach ($oComprovante->getMedicamentos() as $oMedicamento) {
        
        $aTexto = $this->texto2Array(trim($oMedicamento->nome), 35); 
        $this->oModeloImpressora->escrever($oMedicamento->codigo, false, 9, 'L');
        $this->oModeloImpressora->escrever($aTexto[0], false, 40, 'L');
        $this->oModeloImpressora->escrever(substr(trim($oMedicamento->unidade) , 0, 5), false, 5, 'L');
        $this->oModeloImpressora->escrever($oMedicamento->quantidade, true, 10, 'R');
        unset($aTexto[0]);
        foreach ($aTexto as $sTexto) {
           
          $this->oModeloImpressora->escrever(' ', false, 9, 'L');
          $this->oModeloImpressora->escrever(trim($sTexto), true, 37, 'L');
        }
      }
      
      $this->oModeloImpressora->novaLinha();
      $this->oModeloImpressora->novaLinha();
      $this->oModeloImpressora->escrever(str_repeat("_", 40), true, 64, 'C');
      $this->oModeloImpressora->escrever("RECEBEDOR", true, 64, 'C');
      $this->oModeloImpressora->escrever($sMensagemRecibo, true, 64, 'C');
      $this->oModeloImpressora->escrever("{$iVias} VIA.", true, 64, 'R');
      $this->oModeloImpressora->cortarPapel();
      $this->oModeloImpressora->addComando("\n");
    }
    $this->oModeloImpressora->rodarComandos();
  }
  
  /**
   * Converte uma string em um array, on cada elemento do array, é uma string do tamanho de $iTamanhoLinha
   * @param string $sTexto texto a ser convertido
   * @param integer $iTamanhoLinha tamanho de caracteres de cada linha
   * @return array com o texto
   */
  protected function texto2Array($sTexto, $iTamanhoLinha) {
    
    $iNumeroPartes = ceil(strlen($sTexto) / $iTamanhoLinha);
    $aTexto        = array();
    $iInicioParte  = 0;
    for ($i = 0; $i < $iNumeroPartes; $i++) {
      
      $sParte        = substr($sTexto, $iInicioParte, $iTamanhoLinha);
      $iInicioParte += $iTamanhoLinha; 
      $aTexto[]      = $sParte;
    }
    return $aTexto;
  }
  /**
   * 
   * @param integer $sIp 
   * @see IComprovanteMedicamento::setIPImpressora()
   */
  function setIPImpressora($sIp) {
    $this->sIp = $sIp;
  }
  
  /**
   * 
   * @param unknown_type $iModelo 
   * @see IComprovanteMedicamento::setModeloImpressora()
   */
  function setModeloImpressora($iModelo) {
    
    if ($this->sIp == "") {
      throw new Exception('Informe o IP');
    }
    switch ($iModelo) {
    	
      case 12:
    	  
        require_once ('model/impressao.dieboldIM433TD.php');
        $this->oModeloImpressora = new impressaoM433TD($this->sIp, 4444);
    	  break;
    	
    	default:
       
    	  throw new Exception('Modelo de impressao nao homologado para esse comprovante,');
       break;
    }
  }
}

?>