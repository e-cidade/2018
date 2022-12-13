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


class DBEstrutura {
  
  protected $iCodigo;
  
  protected $aNiveis = array();
  
  protected $sMascara;
  
  protected $sDescricao;
  
  protected $lSintetico;
  
  /**
   * 
   */
  function __construct($iCodigoEstrutura = null) {
    
    if (!empty($iCodigoEstrutura)) {
      
      $oDaoDbEstrutura = db_utils::getDao("db_estrutura");
      $sSqlEstrutura   = $oDaoDbEstrutura->sql_query($iCodigoEstrutura);
      $rsEstrutura     = $oDaoDbEstrutura->sql_record($sSqlEstrutura);
      if ($oDaoDbEstrutura->numrows > 0) {

         $oDadosEstrutura  = db_utils::fieldsMemory($rsEstrutura, 0);
         $this->iCodigo    = $iCodigoEstrutura;
         $this->sDescricao = $oDadosEstrutura->db77_descr;
         $this->sMascara   = $oDadosEstrutura->db77_estrut;
      }
    }
  }
  
  /**
   * retorna a mascara do estrutural 
   *
   * @return string
   */
  public function getMascara() {
    return $this->sMascara;
  }
  
  public function setMascara($sMascara) {
  	$this->sMascara = $sMascara;
  }
  
  
  /**
   * retorna  a descirção do estrutural
   *
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }
  public function setDescricao($sDescricao) {
  	$this->sDescricao = $sDescricao;
  }
  
  public function setSintetico($lSintetico) {
    $this->lSintetico = $lSintetico;
  }
  
  public function isSintetico() {
  	return $this->lSintetico;
  }

  /**
   * retorna o codigo da estrutura
   *
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }
  
  public function setCodigo($iCodigo) {
  	$this->iCodigo = $iCodigo;
  }
  
  /**
   * retorna os niveis da estrutura
   *
   * @return array
   */
  public function getNiveis() {
    
    if (count($this->aNiveis) == 0) {
      
      $oDaoEstruturaNivel = db_utils::getDao("db_estruturanivel");
      $sSqlNivel          = $oDaoEstruturaNivel->sql_query_file($this->iCodigo,null, "*", "db78_nivel");
      $rsNiveis           = $oDaoEstruturaNivel->sql_record($sSqlNivel);
      for ($i = 0; $i < $oDaoEstruturaNivel->numrows; $i++) {

        $oDadosNivel = db_utils::fieldsMemory($rsNiveis, $i);
        $oNivel      = new stdClass();
        $oNivel->nivel   = $oDadosNivel->db78_nivel+1;
        $oNivel->nome    = $oDadosNivel->db78_descr;
        $oNivel->digitos = $oDadosNivel->db78_tamanho;
        $oNivel->inicio  = $oDadosNivel->db78_inicio;
        $this->aNiveis[] = $oNivel;
        unset($oDadosNivel);
      }
      unset($rsNiveis);
      unset($sSqlNivel);
    }
    return $this->aNiveis;
  }
  
  
  
  /**
   * Salva uma nova estrutura e seus níveis na base de dados
   * @throws BusinessException
   * @return boolean true
   */
  public function salvar() {
    
    $oDaoEstrutura = db_utils::getDao('db_estrutura');
    $oDaoEstrutura->db77_codestrut        = $this->getCodigo();
    $oDaoEstrutura->db77_estrut           = $this->getMascara();
    $oDaoEstrutura->db77_descr            = $this->getDescricao();
    $oDaoEstrutura->db77_permitesintetico = $this->isSintetico();
    $oDaoEstrutura->incluir($this->getCodigo());
    $this->setCodigo($oDaoEstrutura->db77_codestrut);
    if ($oDaoEstrutura->erro_status == 0) {
      throw new BusinessException("Não foi possível incluir a nova estrutura.\n\n{$oDaoEstrutura->erro_msg}");
    }
    
    $aMascara      = explode(".", $this->getMascara());
    $iTotalPosicao = count($aMascara);
    $iContador     = 0;
    for ($iPosicao = 0; $iPosicao < $iTotalPosicao; $iPosicao++) {
    	
      $iTamanhoNivel       = strlen($aMascara[$iPosicao]);
      $oDaoEstruturaNivel  = db_utils::getDao('db_estruturanivel');
    	$oDaoEstruturaNivel->db78_codestrut = $oDaoEstrutura->db77_codestrut;
    	$oDaoEstruturaNivel->db78_tamanho   = $iTamanhoNivel;
    	$oDaoEstruturaNivel->db78_inicio    = "$iContador";
    	$oDaoEstruturaNivel->db78_nivel     = "$iPosicao";
    	$oDaoEstruturaNivel->db78_descr     = "NÍVEL $iPosicao";
    	$oDaoEstruturaNivel->incluir($oDaoEstrutura->db77_codestrut, "$iPosicao");
    	if ($oDaoEstruturaNivel->erro_status == 0) {
    		throw new BusinessException("Não foi possível definir os níveis da estrutura.");
    	}
    	$iContador = $iContador+$iPosicao;
    } 
    return true;
  }
  /**
   * Remove os estruturais zerados as esquerda
   * 
   * @param  String $sEstrutural Estrutural sintetico que possa ter zeros a direita. Ex.: 01.10.00.00
   * @return String              Estrutural sintetico correto.                       Ex.: 01.10
   */
  public static function removerEstruturalVazio($sEstrutural) {

    $sRegex = '/\.(0)+$/';

    if ( !preg_match($sRegex, $sEstrutural) ) {
      return $sEstrutural;
    }   

    return self::removerEstruturalVazio(preg_replace($sRegex, '',$sEstrutural));
  }

  /**
   * Aplica máscara a um numero
   * 
   * @param  String $sMascara Mascara do Estrutural           Ex.: 00.00.00
   * @param  String $sNumero  NUmero a ser aplicado a mascara Ex.: 123456
   * @return String           Numero com a mascara aplicada   Ex.: 12.34.56 
   */
  public static function mascararString( $sMascara, $sNumero ) {

    $iCaracteresValidos = strlen( str_replace(".", "", $sMascara) );                 // Retorna todos os caracteres != de .
    $sNumero            = str_pad( $sNumero, $iCaracteresValidos, 0, STR_PAD_RIGHT );// Preenche espaços faltando
    $sNumero            = substr( $sNumero, 0, $iCaracteresValidos );                // Corta Pedaços Sobrando
    $aNumero            = str_split($sNumero);                                       // String Transformada em Array
    $aLocaisPontuados = array();

    /**
     * Percorre a mascara procurando a pontuacao
     */
    for ($iPosicao = 0; $iPosicao < strlen($sMascara); $iPosicao++ ) {
    
      $sCaracter = $sMascara[$iPosicao];

      /**
       * Os Locais pontuados devem ser diminiuidos do total pontuado, pois os pontos contam na contagem geral, 
       * mas não são caracteres validos
       *
       * 0  0  .  0  0  .  0  0  .  0  0  -> Mascara Passada
       * |  |  |  |  |  |  |  |  |  |  |   
       * 1  2  3  4  5  6  7  8  9  10 11 -> Posicoes( Comeca em zero, eu sei, mas eh para ilustrar :] )
       * |  |  |  |  |  |  |  |  |  |  |   
       * 1  2  .  3  4  .  5  6  .  7  8   
       *      (3        5        7)       -> Posicoes Pontuadas
       */
      if ( $sCaracter == ".") {
        $aLocaisPontuados[] = $iPosicao - count($aLocaisPontuados);
      }
    }
    
    /**
     * Ordena de Forma Reversa para colocar os numeros sem precisar fazer cálculos de posicao
     */
    rsort($aLocaisPontuados);

    /**
     * Adiciona os Pontos de Trás para frente
     *
     * Ex.:   01010101
     *     -> 010101.01
     *     -> 0101.01.01
     *     -> 01.01.01.01
     */
    foreach ($aLocaisPontuados as $iPosicao ) {
      array_splice($aNumero, $iPosicao, 0, ".");
    }
     
    /**
     * Transforma o array manipulado em string
     */
    $sNumero = implode('', $aNumero);

    return $sNumero;
  }
}