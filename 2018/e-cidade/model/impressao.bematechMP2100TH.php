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

require_once 'model/impressao.model.php';

final class impressaoMP2100TH extends impressao {
  
  function __construct($sIp, $sPorta) {

    parent::setIp($sIp);
    parent::setPorta($sPorta);
  
  }
  
  // METODOS PARA COMANDOS DE OPERACAO
  

  /**
   * Metodo para inicializar a impressora
   *
   */
  function inicializa() {

    parent::addComando(chr(27) . chr(64));
  }
  
  /**
   * Metodo para finalizar a impressora
   *
   */
  function finaliza() {

    parent::addComando(chr(27) . chr(64));
    //parent::addComando(chr(02));  
  }
  
  /**
   * Metodo para escrever texto puro
   *
   * @param string  $sText  String a ser impressa obs : quebras devem ser especificadas com "\n"
   */
  function escreverTexto($sText) {

    /**
     * Aplicando negrito
     */
    $sText = str_replace("<b>", chr(27) . chr(69), $sText);
    $sText = str_replace("</b>", chr(27) . chr(70), $sText);
    /**
     * Aplicando italico
     */
    $sText = str_replace("<i>", chr(27) . chr(52), $sText);
    $sText = str_replace("</i>", chr(27) . chr(53), $sText);
    /**
     * Aplicando sublinhado
     */
    $sText = str_replace("<s>", chr(27) . chr(45) . "1", $sText);
    $sText = str_replace("</s>", chr(27) . chr(45) . "0", $sText);
    
    // $sText = $this->truncarLinhas($sText,48);
    $sText = $this->strToAsc($sText);
    
    $sComando = chr(27) . str_replace("\n", chr(10), $sText);
    parent::addComando($sComando);
  
  }
  
  /**
   * Metodo para truncar as linhas do documento
   *
   * @param string    $sText      String a ser verificada
   * @param integer   $iTamanho   Tamanho maximo por linha
   * 
   * @return string               String com as linhas truncadas
   * 
   */
  function truncarLinhas($sText,$iTamanho) {
  	
  	$sRetorno = "";
  	$aLinhas  = explode("\n",$sText);
  	foreach ($aLinhas as $sLinha) {
  		if (strlen($sLinha) > $iTamanho) {
  			$sRetorno .= "\n".substr($sLinha,0,$iTamanho);
  		}else{
  			$sRetorno .= "\n".$sLinha;
  		}
  	}
  	return $sRetorno;
  }
  
  
  /**
   * Metodo para converter uma string para ascii
   *
   * @param   string  $sStr
   * @return  string
   */
  function strToAsc($sStr) {

    $sStrRetorno = "";
    $aCaracters = array (         'é' => '82', 
                                  'É' => '90', 
                                  'á' => 'A0', 
                                  'Á' => '86', 
                                  'í' => 'A1', 
                                  'Í' => '8B', 
                                  'ó' => 'A2', 
                                  'Ó' => '9F', 
                                  'ú' => 'A3', 
                                  'Ú' => '96', 
                                  'ç' => '87', 
                                  'Ç' => '80', 
                                  'ã' => '84', 
                                  'Ã' => '8E', 
                                  'õ' => '94', 
                                  'Õ' => '99', 
                                  'à' => '85', 
                                  'À' => '91' 
    );
    
    for($i = 0; $i < strlen($sStr); $i ++) {
      $char = $sStr [$i];
      if (array_key_exists($char, $aCaracters)) {
        $sStrRetorno .= chr(hexdec($aCaracters [$char]));
      } else {
        $sStrRetorno .= $char;
      }
    }
    return $sStrRetorno;
  }
  
  /**
   * Metodo para habilitar avanco automatico de linha
   *
   * @param boolean $lAutomatico
   */
  function setAvancoAutomatico($lAutomatico = true) {

    if ($lAutomatico) {
      $sComando = chr(27) . chr(122) . '1';
    } else {
      $sComando = chr(27) . chr(122) . '0';
    }
  }
  
  /**
   * Metodo para efetuar um corte no papel
   *
   * @param boolean $lTotal  true para efetuar um corte total ou false para um corte parcial
   */
  function cortarPapel($lTotal = true) {

    if ($lTotal) {
      $sComando = chr(27) . chr(119);
    } else {
      $sComando = chr(27) . chr(109);
    }
    parent::addComando($sComando);
  }
  
  // METODOS PARA POSICIONAMENTO VERTICAL
  /**
   * Metodo para setar a altura da pagina
   *
   * @param integer $iNumLinhas  tamanho da pagina em linhas 
   */
  function setAlturaPagina($iNumLinhas = 12) {

    $sComando = chr(27) . chr(67) . $iNumLinhas;
    parent::addComando($sComando);
  }
  
  /**
   * Avancar uma linha
   *
   */
  function avancarLinha() {

    $sComando = chr(10);
    parent::addComando($sComando);
  }
  
  /**
   * Avancar uma pagina
   *
   */
  function avancarPagina() {

    $sComando = chr(12);
    parent::addComando($sComando);
  }
  
  /**
   * Efetua um saldo de $iNumCaracteres caracteres na vertical
   *
   * @param integer $iNumCaracteres
   */
  function saltoVCarecteres($iNumCaracteres = 0) {

    $sComando = chr(27) . chr(102) . "1" . $iNumCaracteres;
    parent::addComando($sComando);
  }
  
  // METODOS PARA POSICIONAMENTO HORIZONTAL
  

  /**
   * Efetua um saldo de $iNumCaracteres caracteres na horizontal
   *
   * @param integer $iNumCaracteres
   */
  function saltoHCarecteres($iNumCaracteres = 0) {

    $sComando = chr(27) . chr(102) . "0" . $iNumCaracteres;
    parent::addComando($sComando);
  }
  
  /**
   * Metodo para setar a margem diretita de impressao
   *
   * @param integer $iNumCol numero da coluna da margem direita
   */
  function setMargemDireita($iNumCol = 4) {

    $sComando = chr(27) . chr(81) . " $iNumCol";
    parent::addComando($sComando);
  }
  
  /**
   * Metodo para setar a margem esquerda de impressao
   *
   * @param integer $iNumCol numero da coluna da margem esquerda
   */
  function setMargemEsquerda($iNumCol = 4) {

    $sComando = chr(27) . chr(108) . " $iNumCol";
    parent::addComando($sComando);
  }
  
  /**
   * Metodo para setar o alinhamento dos caracteres
   *
   * @param string $sAlinhamento L para esquerda ou C para centralizado
   */
  function setAlinhamento($sAlinhamento = 'L') {

    if (strtoupper($sAlinhamento) == "L") {
      $sComando = chr(27) . chr(97) . "0";
    } else {
      $sComando = chr(27) . chr(97) . "1";
    }
    parent::addComando($sComando);
  }
  
  // Metodos PARA OS TIPOS DE CARACTERES
  

  /**
   * Metodo para ligar ou desligar sublinhado
   *
   * @param boolean $lSublinhado true para habilitar ou false para desabilitar
   */
  function setSublinhado($lSublinhado = true) {

    if ($lSublinhado) {
      $sComando = chr(27) . chr(45) . "1";
    } else {
      $sComando = chr(27) . chr(45) . "0";
    }
    return $sComando;
  
  }
  
  /**
   * Metodo para ligar ou desligar italico
   *
   * @param boolean $lItalico true para habilitar ou false para desabilitar
   */
  function setItalico($lItalico = true) {

    if ($lItalico) {
      $sComando = chr(27) . chr(52);
    } else {
      $sComando = chr(27) . chr(53);
    }
    return $sComando;
  
  }
  
  /**
   * Metodo para ligar ou desligar realce
   *
   * @param boolean $lRealce true para habilitar ou false para desabilitar
   */
  function setRealce($lRealce = true) {

    if ($lRealce) {
      $sComando = chr(27) . chr(69);
    } else {
      $sComando = chr(27) . chr(70);
    }
    
    return $sComando;
  
  }
  
  /**
   * Metodo para aplicar negrito em um texto
   *
   * @param string $sString
   */
  function aplicarNegrito($sString) {

    return $this->setRealce(true) . $sString . $this->setRealce(false);
  }
  
  /**
   * Metodo para setar a tabela padrao de caracteres
   *
   * @param integer $iCodigoTabela codigo da tabela a ser utilizada
   */
  function setTabelaCaracteres($iCodigoTabela = 850) {

    switch ( $iCodigoTabela) {
      case 850 :
        $sComando = chr(27) . chr(116) . "2";
      break;
      case 437 :
        $sComando = chr(27) . chr(116) . "3";
      break;
      case 860 :
        $sComando = chr(27) . chr(116) . "4";
      break;
      case 858 :
        $sComando = chr(27) . chr(116) . "5";
      break;
    }
    
    parent::addComando($sComando);
  }
  
  /**
   * Metodo para ativar o modo sobrescrito
   *
   * @param boolean $lSobrescrito true para habilitar
   */
  function setSobrescrito($lSobrescrito = true) {

    if ($lSobrescrito) {
      $sComando = chr(27) . chr(83) . "0";
    } else {
      $sComando = chr(27) . chr(84);
    }
    return $sComando;
  }
  
  /**
   * Metodo para ativar o modo subscrito
   *
   * @param boolean $lSubscrito true para habilitar
   */
  function setSubscrito($lSubscrito = true) {

    if ($lSubscrito) {
      $sComando = chr(27) . chr(83) . "1";
    } else {
      $sComando = chr(27) . chr(84);
    }
    return $sComando;
  }
  
  /**
   * Ativa ou desativa o modo condensado (42 colunas)
   *
   * @param unknown_type $lModoCondensado
   */
  function modoCondensado($lModoCondensado) {

    if ($lModoCondensado) {
      $sComando = chr(15);
    } else {
      $sComando = chr(18);
    }
    parent::addComando($sComando);
  }
  
  /**
   * Ativa ou desativa o modo expandido da linha
   *
   * @param unknown_type $lModoExpandido
   */
  function modoExpandidoLinha($lModoExpandido) {

    if ($lModoExpandido) {
      $sComando = chr(14);
    } else {
      $sComando = chr(20);
    }
    parent::addComando($sComando);
  }
  
  /**
   * Habilita ou desabilita altura dupla
   *
   * @param boolean $lAlturaDupla
   */
  function setAlturaDuplaLinha($lAlturaDupla) {

    if ($lAlturaDupla) {
      $sComando = chr(100) . "1";
    } else {
      $sComando = chr(100) . "0";
    }
    parent::addComando($sComando);
  }
  
  /**
   * Metodo para setar a largura padrao
   *
   */
  function setLarguraPadrao() {

    parent::addComando(chr(27) . chr(72));
  }

}