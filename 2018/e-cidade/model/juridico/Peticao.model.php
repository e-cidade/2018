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

/**
 * Gera petições de inicias quitadas ou parceladas
 * 
 * @package  Juridico 
 * @author   jeferson.belmiro <jeferson.belmiro@dbseller.com.br>
 * @author   alberto          <alberto@dbseller.com.br> 
 * 
 * @revision $Author  : $
 * @version  $Revision: 1.4 $
 */
class Peticao {
  
  /**
   * Código da petição
   * @var integer
   */
  private $iCodigoPeticao;
  
  /**
   * Objeto Inicial
   * @var inicial
   */
  private $oInicial;
  
  /**
   * Código do tipo de petição 
   * @var integer
   */
  private $iTipoPeticao;
  
  /**
   * Data da geração da petição
   * @var object
   */
  private $oDataPeticao;
  
  /**
   * Hora da geração da petição
   * @var string
   */
  private $sHoraPeticao;
  
  /**
   * Código do usuário da geração da petição
   * @var integer
   */
  private $iCodigoUsuario;
  
  /**
   * Texto da observação da petição
   * @var string
   */
  private $sTexto;

  /**
   * Método construtor da classe Peticao
   * Caso seja passado como parametro o código da peticao, se existir, será carregado os valores da tabela
   * @param integer $iCodigoPeticao
   */
  public function __construct($iCodigoPeticao = null) {
  	
    if (!empty($iCodigoPeticao)) {
      
      $oDaoJurpeticoes = db_utils::getDao('jurpeticoes');
      
      $sSqlJurpeticoes = $oDaoJurpeticoes->sql_query_file($iCodigoPeticao);
      
      $rsJurpeticoes   = $oDaoJurpeticoes->sql_record($sSqlJurpeticoes);
      
      if ($rsJurpeticoes and $oDaoJurpeticoes->numrows > 0) {
        
        $oJurpeticoes = db_utils::fieldsMemory($rsJurpeticoes, 0);
        
        $this->setCodigoPeticao ($oJurpeticoes->v60_peticao);
        $this->setInicial       (new inicial($oJurpeticoes->v60_inicial));
        $this->setTipoPeticao   ($oJurpeticoes->v60_tipopet);
        $this->setDataPeticao   (new DBDate($oJurpeticoes->v60_data));
        $this->setHoraPeticao   ($oJurpeticoes->v60_hora   );
        $this->setCodigoUsuario ($oJurpeticoes->v60_usuario);
        $this->setTexto         ($oJurpeticoes->v60_texto  );
        unset($oJurpeticoes);
        
      }
      
    }
    
    return;
    
  }
    
  /**
   * Retorna o código da petição
   * @return integer
   */
  public function getCodigoPeticao() {
    return $this->iCodigoPeticao;
  }

  /**
   * Define o código da petição
   * @param $iCodigoPeticao
   */
  public function setCodigoPeticao($iCodigoPeticao) {
    $this->iCodigoPeticao = $iCodigoPeticao;
  }

  /**
   * Retorna o objeto inicial
   * @return inicial
   */
  public function getInicial() {
    return $this->oInicial;
  }

  /**
   * Define o objeto inicial
   * @param $iCodigoInicial
   */
  public function setInicial(inicial $oInicial) {
    $this->oInicial = $oInicial;
  }

  /**
   * Retorna o código do tipo de petição
   * 1 - Parcelamento / 2 - Inicial Quitada
   * @return integer
   */
  public function getTipoPeticao() {
    return $this->iTipoPeticao;
  }

  /**
   * Define o tipo de petição
   * @param $iTipoPeticao
   */
  public function setTipoPeticao($iTipoPeticao) {
    $this->iTipoPeticao = $iTipoPeticao;
  }

  /**
   * Retorna a data da petição
   * @return DBDate object
   */
  public function getDataPeticao() {
    return $this->oDataPeticao;
  }

  /**
   * Define a data da petição
   * @param DBDate
   */
  public function setDataPeticao(DBDate $oDataPeticao) {
    $this->oDataPeticao = $oDataPeticao;
  }

  /**
   * Retorna a hora da petição
   * @return string
   */
  public function getHoraPeticao() {
    return $this->sHoraPeticao;
  }

  /**
   * Define hora da petição
   * @param $sHoraPeticao
   */
  public function setHoraPeticao($sHoraPeticao) {
    $this->sHoraPeticao = $sHoraPeticao;
  }

  /**
   * Retorna o código do usuário
   * @return 
   */
  public function getCodigoUsuario() {
    return $this->iCodigoUsuario;
  }

  /**
   * Define código do usuario
   * @param $iCodigoUsuario
   */
  public function setCodigoUsuario($iCodigoUsuario) {
    $this->iCodigoUsuario = $iCodigoUsuario;
  }

  /**   
   * Retorna o texto da petição
   * @return text 
   */
  public function getTexto() {
    return $this->sTexto;
  }

  /**
   * Define o texto da petição
   * @param $sTexto
   */
  public function setTexto($sTexto) {
    $this->sTexto = $sTexto;
  }
  
  /**
  * Salvar na tabela jurpeticoes
  * Caso a chave primaria estiver setada, altera, caso contrario inclui a peticao
  * @throws DBException - Erro na inclusão ou alteração
  * @return boolean     - True se não tiver erros
  */
  public function salvar() {
  
    $oDaoJurpeticoes              = db_utils::getDao('jurpeticoes');
    $oDaoJurpeticoes->v60_inicial = $this->getInicial()->getCodigoInicial();
    $oDaoJurpeticoes->v60_tipopet = $this->getTipoPeticao();
    $oDaoJurpeticoes->v60_data    = $this->getDataPeticao()->getDate();
    $oDaoJurpeticoes->v60_hora    = $this->getHoraPeticao();
    $oDaoJurpeticoes->v60_usuario = $this->getCodigoUsuario();
    $oDaoJurpeticoes->v60_texto   = $this->getTexto();
  
    /**
     * Incluir
     */
    if(empty($this->iCodigoPeticao)) {
        
      $oDaoJurpeticoes->incluir(null);
      
      $this->setCodigoPeticao($oDaoJurpeticoes->v60_peticao);
        
      if ($oDaoJurpeticoes->erro_status == '0') {
        throw new DBException('Petição não incluida. Operação abortada. ERRO: ' . $oDaoJurpeticoes->erro_msg);
      }
  
      return true;
    }
  
    /**
     * Alterar
     */
    $oDaoJurpeticoes->v60_peticao = $this->getCodigoPeticao();
    $oDaoJurpeticoes->alterar( $this->getCodigoPeticao() );
  
    if ($oDaoJurpeticoes->erro_status == '0') {
      throw new DBException('Petição não alterada. Operação abortada. ERRO: ' . $oDaoJurpeticoes->erro_msg);
    }
  
    return true;
  }
    
  /**
   * Emite a peticao
   * @return string com nome do arquivo gerado
   */
  public function emissao() {
  	
  	db_app::import('juridico.PeticaoEmissao');
  	
  	$oPeticaoEmissao = new PeticaoEmissao($this->getTipoPeticao());
  	
  	$oPeticaoEmissao->adicionarPeticao($this);
  	
  	return $oPeticaoEmissao->emitir();
  	
  }
  
}