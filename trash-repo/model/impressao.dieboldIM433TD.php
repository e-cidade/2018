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

require_once 'impressao.model.php';

/**
 * Classe de controle referente a impressora Diebold IM433TD
 *  
 * M�todos configurados de acordo com manual do firmware vers�o : I4X5101PD5XX - Revis�o 3 
 * 
 * @package impressora
 */
final class impressaoM433TD extends impressao {
  
  /**
   * Tamanho da margem esquerda correspondente a linha atual de execu��o;
   *
   * @var integer
   */   
  private $iMargemEsquerda;
  
  /**
   * Tamanho da margem direita correspondente a linha atual de execu��o;
   *
   * @var integer
   */   
  private $iMargemDireita;


  /**
   * N�mero de caracteres utilizados na linha atual de execu��o;
   *
   * @var integer
   */ 
  private $iCaractLinhaAtual = 0;
  

  /**
   * Tipo de texto utilizado na linha atual de execu��o, sendo as op��es v�lidas;
   * 
   * 1 - Normal
   * 2 - Condensado
   * 3 - Extendido 
   *
   * @var integer
   */   
  private $iTipoTextoAtual = 1;  
  
  
  
  function __construct($sIp, $sPorta) {

    parent::setIp($sIp);
    parent::setPorta($sPorta);
    
    /**
     *  Define valores padr�es 
     */
    $this->setMargemEsquerda(1);
    $this->setMargemDireita(48);
    $this->setEspacamentoCaracter(0);
    
  }
  
  
  /**
   * M�todo que calcula o alinhamento para o texto informado 
   * 
   * @param string   $sTexto          String a ser impressa 
   * @param integer  $iNroCaracteres  N�mero de caracteres que servir� de base para o alinhamento
   * @param string   $sAlinhamento    Tipo de alinhamento : 'L' - Esquerda, 'R' - Direita, 'C'-Centro  
   * 
   * @return string String a ser impressa formatada   
   *     
   */  
  private function calculaAlinhamentoTexto($sTexto,$iNroCaracteres,$sAlinhamento) {
    
    
    /**
     *  Caso o n�mero de caracteres informado seja igual a 0 (zero) 
     *  ent�o � feito o alinhamento de acordo com toda a linha 
     */
    if ($iNroCaracteres == 0) {
        
      /**
       *  Verifica o tipo de texto atual e define o total de caracteres da linha de acordo com o tipo.
       *  $iNroCaracteres - N�mero total de carateres da linha
       *  $iCoeficiente   - Coeficiente de tamanho de caractere de acordo com o tipo de texto informado
       */
      switch ($this->iTipoTextoAtual) {
        case 1:
          
          $iNroCaracteres = 48;
          $iCoeficiente   = 1;
        break;
        case 2:
          
          $iNroCaracteres = 64;
          $iCoeficiente   = 1.3;
        break;
        case 3:
          
          $iNroCaracteres = 24;
          $iCoeficiente   = 2;
        break;
        default:
          $iNroCaracteres = 0;
        break;
      }

      /**
       *  Caso o total de caracteres utilizados ultrapasse o total da linha ent�o n�o haver� alinhamento 
       */
      if ($this->iCaractLinhaAtual < $iNroCaracteres ) {

        /**
         *  Acerta o total de caracteres por linha :
         *  
         *  - Discontando os caracteres j� informados
         *  - Discontando a margem esquerda configurada 
         *  - Discontando a margem direita configurada
         */
        $iNroCaracteres -= ($this->iCaractLinhaAtual / $iCoeficiente);
        $iNroCaracteres -= ($this->iMargemEsquerda - 1);
        $iNroCaracteres -= ($this->iMargemDireita - $iNroCaracteres);
        
      } else {
         
        $iNroCaracteres = 0;
      }
    }
      
    switch ($sAlinhamento) {
      case "L":
        $sTexto = str_pad($sTexto,$iNroCaracteres," ",STR_PAD_RIGHT);
        break;
      case "R":
        $sTexto = str_pad($sTexto,$iNroCaracteres," ",STR_PAD_LEFT);
        break;
      case "C":
        $sTexto = str_pad($sTexto,$iNroCaracteres," ",STR_PAD_BOTH);
        break;
      default:
        throw new Exception("Erro ao definir alinhamento de linha!");
      break;  
    }    

    return $sTexto;
  }
  
  
  
  /**
   * M�todo para escrever um texto puro 
   * 
   * @param string   $sTexto          String a ser impressa 
   * @param bool     $lQuebraLinha    Flag que informa se a linha deve ser quebrada
   * @param integer  $iNroCaracteres  N�mero de caracteres que servir� de base para o alinhamento
   * @param string   $sAlinhamento    Tipo de alinhamento : 'L' - Esquerda, 'R' - Direita, 'C'-Centro  
   *     
   */    
  public function escrever($sTexto,$lQuebraLinha=false,$iNroCaracteres='',$sAlinhamento='L') {

    
    $sTexto = $this->strToAsc($sTexto);
    
    $sTexto = str_replace("<b>", $this->getComandoNegrito(true), $sTexto);
    $sTexto = str_replace("</b>", $this->getComandoNegrito(false), $sTexto);
    
    $sTexto = str_replace("<i>", $this->getComandoItalico(true), $sTexto);
    $sTexto = str_replace("</i>", $this->getComandoItalico(false), $sTexto);
    
    
    $sTexto = str_replace("<s>", $this->getComandoSublinhado(true), $sTexto);
    $sTexto = str_replace("</s>", $this->getComandoSublinhado(false), $sTexto);
    
    if (trim($iNroCaracteres) != '') {
      $sTexto = $this->calculaAlinhamentoTexto($sTexto,$iNroCaracteres,$sAlinhamento);      
    }
    
    /**
     *  Adiciona o n�mero de carateres utilizados na linha 
     */
    $this->iCaractLinhaAtual += strlen($sTexto);
    
    parent::addComando($sTexto);
    
    if ($lQuebraLinha) {
      $this->novaLinha();      
    }
  }

  
  /**
   * Metodo que imprime linha atual e posiciona o cursor no in�cio da pr�xima linha 
   */
  public function novaLinha() {

    /**
     *  Quando iniciado uma nova linha � zerado o n�mero de caracteres utilizados
     */
    $this->iCaractLinhaAtual = 0;
    parent::addComando(chr(10));
  }  
  
  
  /**
   * Metodo que imprime linha atual e posiciona o cursor no in�cio da pr�xima p�gina
   */  
  public function novaPagina() {
    
    parent::addComando(chr(12));
  }
    

  /**
   * Metodo para efetuar um corte no papel
   *
   * @param boolean $lTotal  true para efetuar um corte total ou false para um corte parcial
   */
  public function cortarPapel($lTotal=true) {

    if ($lTotal) {
      $sComando = chr(17);
    } else {
      $sComando = chr(27) . chr(119);
    }
    parent::addComando($sComando);
  }  
  
  
  /**
   * Metodo para ligar ou desligar texto Expandido
   *
   * @param boolean $lExpandido true para habilitar ou false para desabilitar
   */
  public function modoExpandido($lExpandido=true) {

    
    if ($lExpandido) {
      $this->iTipoTextoAtual = 3;
      $sComando = chr(14);
    } else {
      $this->iTipoTextoAtual = 1;
      $sComando = chr(20);
    }
    
    parent::addComando($sComando);
  }  
  
  
  /**
   * Metodo para ligar ou desligar texto Condensado
   *
   * @param boolean $lCondensado true para habilitar ou false para desabilitar
   */
  public function modoCondensado($lCondensado=true) {

    if ($lCondensado) {
      $this->iTipoTextoAtual = 2;
      $sComando = chr(15);
    } else {
      $this->iTipoTextoAtual = 1;
      $sComando = chr(18);
    }
    
    parent::addComando($sComando);
  }  

  
  /**
   * Metodo para ligar ou desligar texto Sublinhado
   *
   * @param boolean $lSublinhado true para habilitar ou false para desabilitar
   */
  public function getComandoSublinhado($lSublinhado=true) {

    if ($lSublinhado) {
      $sComando = chr(27) . chr(45) . chr(1);
    } else {
      $sComando = chr(27) . chr(45) . chr(0);
    }
    return $sComando;
  }  
  
  
  /**
   * Metodo para ligar ou desligar texto Italico
   *
   * @param boolean $lItalico true para habilitar ou false para desabilitar
   */
  public function getComandoItalico($lItalico=true) {

    if ($lItalico) {
      $sComando = chr(27) . chr(52);
    } else {
      $sComando = chr(27) . chr(53);
    }
    return $sComando;
  }    
  
  
  /**
   * Metodo para ligar ou desligar texto Negrito
   *
   * @param boolean $lNegrito true para habilitar ou false para desabilitar
   */
  public function getComandoNegrito($lNegrito=true) {

    if ($lNegrito) {
      $sComando = chr(27) . chr(69);
    } else {
      $sComando = chr(27) . chr(70);
    }
    return $sComando;
  }      
  
  
  
  /**
   * Metodo para definir o espa�amento entre caracteres sendo os valores entre 0 e 24
   * Obs: O espa�amento 0(zero), n�o implica em colar os caracteres e sim em manter o espa�amento da impress�o normal.
   *
   * @param integer $iEspacamento
   */
  private function setEspacamentoCaracter($iEspacamento=0) {

    $sComando = chr(27) . chr(37) . chr($iEspacamento);
    parent::addComando($sComando);
  }    
  

  /**
   * Metodo para definir o tamanho do avan�o da linha sendo os valores padr�es correspondentes aos tamanhos :
   * 
   *  1 - 3,25mm
   *  2 - 3,75mm
   *  3 - 4,25mm    
   *
   * @param integer $iAvanco
   */
  public function setAvancoLinha($iAvanco=1) {

    switch ($iAvanco) {
    	case 1:
    	 $sComando = chr(27) . chr(21) . chr(26);
    	break;
      case 2:
       $sComando = chr(27) . chr(21) . chr(30);
      break;    	
      case 3:
       $sComando = chr(27) . chr(21) . chr(34);
      break;    	
    	default:
    		throw new Exception("Avan�o de linha : {$iAvanco} n�o configurado!");
    	break;
    }
    parent::addComando($sComando);
  }  
  

  /**
   * Metodo que define o n�mero de linhas que ser� impresso por p�gina sendo o padr�o 12 e podendo variar entre 1 e 255
   * 
   * @param integer $iLinhas
   */
  public function setLinhasPagina($iLinhas=12) {

    if ($iLinhas <= 0 || $iLinhas > 255 ) {
      throw new Exception("N�mero de linhas por p�gina deve ser maior que 0 (zero) e menor que 255 !"); 
    }
    
    $sComando = chr(27) . chr(67) . chr($iLinhas);
    
    parent::addComando($sComando);
  }  


  /**
   * Metodo que desativa qualquer atributo que esteja selecionado, assumindo a configura��o default 
   */
  public function resetAtributos() {
    
    parent::addComando(chr(27) . chr(72));
  }  
  
  
  /**
   * Metodo que define o n�mero de linhas que ser� impresso na margem inferior da p�gina ou ( salto do picote )
   * 
   * @param integer $iLinhas
   */
  public function saltoPicote($iLinhas=0) {

    if ($iLinhas > 255 ) {
      throw new Exception("N�mero de linhas da margem inferior deve ser menor que 255 !"); 
    }
    
    $sComando = chr(27) . chr(255) . chr($iLinhas);
    
    parent::addComando($sComando);
  } 
  
  
  /**
   * Metodo que define o tamanho da margem direita da p�gina
   * 
   * @param integer $iTam
   */
  public function setMargemDireita($iTam=48) {

    $this->iMargemDireita = $iTam;
    
    $sComando = chr(27) . chr(81) . chr($iTam);
    
    parent::addComando($sComando);
  }

  /**
   * Metodo que define o tamanho da margem esquerda da p�gina
   * 
   * @param integer $iTam
   */
  public function setMargemEsquerda($iTam=1) {

    $this->iMargemEsquerda = $iTam; 
    
    $sComando = chr(27) . chr(108) . chr($iTam);
    
    parent::addComando($sComando);
  }    
  
  /**
   * Metodo para ligar ou desligar o par�metro que duplica a altura da linha
   *
   * @param boolean $lDuplicaAltura true para habilitar ou false para desabilitar
   */
  public function setDuplicaAltura($lDuplicaAltura=true) {

    if ($lDuplicaAltura) {
      $sComando = chr(27) . chr(100) . chr(1);
    } else {
      $sComando = chr(27) . chr(100) . chr(0);
    }
    
    parent::addComando($sComando);
  }      

  
  /**
   * Metodo que define a tabela de carateres a ser utilizada sendo o padr�o 4
   * 
   *  1 - Abicomp
   *  2 - Code Page 850
   *  3 - Code Page 437
   *  4 - ANSI
   *
   * @param integer $iTabelaCaracteres 
   */
  public function setTabelaCaracteres($iTabelaCaracteres=4) {

    $sComando = chr(27) . chr(116) . chr($iTabelaCaracteres);
    
    parent::addComando($sComando);
  }  
  
function strToAsc($sStr) {

    $sStrRetorno = "";
    $aCaracters = array('�' => 'e', 
                        '�' => 'E', 
                        '�' => 'a', 
                        '�' => 'A', 
                        '�' => 'i', 
                        '�' => 'I', 
                        '�' => 'o', 
                        '�' => 'O', 
                        '�' => 'u', 
                        '�' => 'U', 
                        '�' => 'c', 
                        '�' => 'C', 
                        '�' => 'a', 
                        '�' => 'A', 
                        '�' => 'o', 
                        '�' => 'O', 
                        '�' => 'a', 
                        '�' => 'A' 
                       );
    
    for ($i = 0; $i < strlen($sStr); $i ++) {
      
      $char = $sStr [$i];
      if (array_key_exists($char, $aCaracters)) {
        $sStrRetorno .= $aCaracters [$char];
      } else {
        $sStrRetorno .= $char;
      }
    }
    return $sStrRetorno;
  }
}