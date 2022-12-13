<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

class AfastaAssenta {

  
  /**
   * Assentamento
   * 
   * @var Assentamento
   */
  private $oAssentamento;
  
  /**
   * Afastamento
   * 
   * @var Afastamento
   */
  private $oAfastamento;

  /**
   * Construtor da classe
   * 
   * @param Assentamento $oAssentamento
   * @param Afastamento  $oAfastamento
   */
  public function __construct(Assentamento $oAssentamento = null, Afastamento $oAfastamento = null) {

    if(!empty($oAssentamento) && !empty($oAfastamento)) {

      $this->setAssentamento($oAssentamento);
      $this->setAfastamento($oAfastamento);
    }
  }

  /**
   * Define o Assentamento
   * 
   * @param Assentamento $oAssentamento
   */
  public function setAssentamento($oAssentamento) {
    $this->oAssentamento = $oAssentamento;
  }

  /**
   * Retorna o Assentamento
   * 
   * @param  Assentamento $oAssentamento
   * @return Assentamento
   */
  public function getAssentamento ($oAssentamento) {
    return $this->oAssentamento;
  }

  /**
   * Define o Afastamento
   * 
   * @param Afastamento $oAssentamento
   */
  public function setAfastamento($oAfastamento) {
    $this->oAfastamento = $oAfastamento;
  }

  /**
   * Retorna o Afastamento
   * 
   * @param  Afastamento $oAfastamento
   * @return Afastamento
   */
  public function getAfastamento ($oAfastamento) {
    return $this->oAfastamento;
  }

  /**
   * Salva um vínculo entre assentamento e afastamento
   * 
   * @return AfastaAssenta
   */
  public function persist() {
    
    $oDaoAfastaAssenta    = new cl_afastaassenta;
    $sWhereAfastaAssenta  = "     h81_assenta = ". $this->oAssentamento->getCodigo();
    $sWhereAfastaAssenta .= " and h81_afasta  = ". $this->oAfastamento->getCodigoAfastamento();
    $sSqlAfastaAssenta    = $oDaoAfastaAssenta->sql_query_file(null, "*", null, $sWhereAfastaAssenta);
    $rsAfastaAssenta      = db_query($sSqlAfastaAssenta);

    if(!$rsAfastaAssenta) {
      throw new DBException("Erro ao buscar assentamentos e afastamentos vinculados.");
    }

    if(pg_num_rows($rsAfastaAssenta) == 0) {

      $oDaoAfastaAssenta->h81_assenta = $this->oAssentamento->getCodigo();
      $oDaoAfastaAssenta->h81_afasta  = $this->oAfastamento->getCodigoAfastamento();

      $oDaoAfastaAssenta->incluir();

      if($oDaoAfastaAssenta->erro_status == "0") {
        throw new DBException($oDaoAfastaAssenta->erro_msg);
      }
    }

    if(pg_num_rows($rsAfastaAssenta) > 0) {
      throw new BusinessException("Vínculo entre assentamento e afastamento já existente.");
    }

    return $this;
  }

  /**
   * Remove um vínculo entre assentamento e afastamento
   * 
   * @return true
   */
  public function remove() {

    $oDaoAfastaAssenta    = new cl_afastaassenta;
    $sWhereAfastaAssenta  = "     h81_assenta = ". $this->oAssentamento->getCodigo();
    $sWhereAfastaAssenta .= " and h81_afasta  = ". $this->oAfastamento->getCodigoAfastamento();

    $lResponse            = $oDaoAfastaAssenta->excluir(null, $sWhereAfastaAssenta);

    if(!$lResponse) {
      throw new DBException("Erro ao excluir vínculo entre assentamentos e afastamentos.");
    }

    return true;
  }
}