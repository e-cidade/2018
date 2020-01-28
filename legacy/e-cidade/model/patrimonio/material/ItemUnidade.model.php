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
 * 
 * model para retornar dados da unidade dos materiais (matmater / matunid)
 * @author rafael.lopes rafael.lopes@dbseller.com.br
 *
 */
class ItemUnidade{
  
  /**
   * codigo da unidae
   * @var integer
   */
  private $iCodigo;//m61_codmatunid
  
  /**
   * descricao / nome da unidade
   * @var string
   */
  private $sDescricao;//m61_descr
  
  /**
   * e a unidade usa quantidades
   * @var boolean
   */
  private $lUsaQuantidade;//m61_usaquant
  
  /**
   * abreviação do nome da unidade
   * @var string
   */
  private $sAbreviacao;//m61_abrev
  
  /**
   * 
   * @var bool
   */
  private $lUsaDec;//m61_usadec
  
  
  /**
   * metodo construtor parametro codigo da unidade
   * @param string $iCodigo
   */
  public function __construct ( $iCodigo = null ){
    
    if ( !empty($iCodigo) ) {
      
      $oDaoUnidade = new cl_matunid();
      $sSqlUnidade = $oDaoUnidade->sql_query_file($iCodigo);
      $rsUnidade   = $oDaoUnidade->sql_record($sSqlUnidade);
      if ( $oDaoUnidade->numrows > 0) {
        
        $oDadosUnidade = db_utils::fieldsMemory($rsUnidade, 0);
        $this->setCodigo       ($oDadosUnidade->m61_codmatunid);
        $this->setDescricao    ($oDadosUnidade->m61_descr);
        $this->setAbreviacao   ($oDadosUnidade->m61_abrev);
        $this->setUsaQuantidade($oDadosUnidade->m61_usaquant);
        $this->setUsaDec       ($oDadosUnidade->m61_usadec);
      }
    }
  }
  
  /**
   * seta o codigo da unidade
   * @param integer $iCodigo
   */
  public function setCodigo( $iCodigo ){
    $this->iCodigo = $iCodigo;
  }
  /**
   * retorna o codigo da unidade
   * @return number
   */
  public function getCodigo(){
    return $this->iCodigo;
  }
  
  /**
   * define a descricao da unidade
   * @param string $sDescricao
   */
  public function setDescricao( $sDescricao ){
    $this->sDescricao = $sDescricao;
  }
  /**
   * retorna a descricao da unidade
   * @return string
   */
  public function getDescricao(){
    return $this->sDescricao;
  }
  
  /**
   * define se a unidade usa quantidade
   * @param bool $lUsaQuantidade
   */
  public function setUsaQuantidade( $lUsaQuantidade ){
    $this->lUsaQuantidade = $lUsaQuantidade;
  }
  /**
   * retorna se a unidade utilisa quantidade
   * @return boolean
   */
  public function getUsaQuantidade(){
    return $this->lUsaQuantidade;
  }
  
  /**
   * define a abreviação da unidade
   * @param string $sAbreviacao
   */
  public function setAbreviacao( $sAbreviacao ){
    $this->sAbreviacao = $sAbreviacao;
  }
  /**
   * retorna a abreviação da unidade
   * @return string
   */
  public function getAbreviacao(){
    return $this->sAbreviacao;
  }
  
  /**
   * define se a unidade utiliza dec....
   * @param bool $lUsaDec
   */
  public function setUsaDec( $lUsaDec ){
    $this->lUsaDec = $lUsaDec;
  }
  /**
   * retorna se aunidade utiliza dec !?!?
   * @return boolean
   */
  public function getUsaDec(){
    return $this->lUsaDec;
  }
  
  
  
}
