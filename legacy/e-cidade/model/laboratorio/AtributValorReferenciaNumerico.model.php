<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2014 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

class AtributValorReferenciaNumerico {

  protected  $iCodigo;

  protected $iValorInicio;

  protected $iValorFim;

  protected $iValorAbsurdoInicio;

  protected $iValorAbsurdoFim;

  protected $aSexos = array();

  protected $oIdadeInicial = array();

  protected $oIdadeFinal = array();

  /**
   * Atributo base para calculo
   * @var AtributoExame;
   */
  protected $oAtributoBase;

  /**
   * Tipo do c�lculo que a referencia possui
   * @var integer
   */
  protected  $iTipoCalculo = 0;

  /**
   * Instancia uma referencia pelo codigo, ou cria uma nova
   * @param integer $iCodigo
   */
  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      $oDaoReferencia = new cl_lab_tiporeferenciaalnumerico();
      $sListaCampos   = "la59_periodoinicial, la59_periodofinal, la30_f_normalmin, la30_f_normalmax,la30_f_absurdomin,";
      $sListaCampos  .= "la30_f_absurdomax,la30_c_calculavel";
      $sListaSexos    = " array_to_string(array_accum(la60_sexo), ',') as sexos";
      $sSqlReferencia = $oDaoReferencia->sql_query_dados_referencia(null,
                                                                    "{$sListaCampos}, {$sListaSexos}",
                                                                    null,
                                                                    "la30_i_codigo = {$iCodigo} group by {$sListaCampos}"
                                                                   );

      $rsReferencia = $oDaoReferencia->sql_record($sSqlReferencia);

      if ($oDaoReferencia->numrows > 0) {

        $oDadosReferencia = db_utils::fieldsMemory($rsReferencia, 0);
        $this->setCodigo($iCodigo);
        $this->setValorAbsurdoMaximo($oDadosReferencia->la30_f_absurdomax);
        $this->setValorAbsurdoMinimo($oDadosReferencia->la30_f_absurdomin);
        $this->setValorMinimo($oDadosReferencia->la30_f_normalmin);
        $this->setValorMaximo($oDadosReferencia->la30_f_normalmax);
        $this->aSexos = explode(",", $oDadosReferencia->sexos);

        if (!empty($oDadosReferencia->la59_periodoinicial)) {
          $this->oIdadeInicial = new DBInterval($oDadosReferencia->la59_periodoinicial);
        }

        if (!empty($oDadosReferencia->la59_periodofinal)) {
          $this->oIdadeFinal = new DBInterval($oDadosReferencia->la59_periodofinal);
        }
      }
    }
  }

  /**
   * @param mixed $iValorAbsurdoFim
   */
  public function setValorAbsurdoMaximo($iValorAbsurdoFim) {
    $this->iValorAbsurdoFim = $iValorAbsurdoFim;
  }

  /**
   * @return mixed
   */
  public function getValorAbsurdoMaximo() {
    return $this->iValorAbsurdoFim;
  }

  /**
   * @param mixed $iValorAbsurdoInicio
   */
  public function setValorAbsurdoMinimo($iValorAbsurdoInicio) {
    $this->iValorAbsurdoInicio = $iValorAbsurdoInicio;
  }

  /**
   * @return mixed
   */
  public function getValorAbsurdoMinimo() {
    return $this->iValorAbsurdoInicio;
  }

  /**
   * @param mixed $iValorFim
   */
  public function setValorMaximo($iValorFim) {
    $this->iValorFim = $iValorFim;
  }

  /**
   * @return mixed
   */
  public function getValorMaximo() {
    return $this->iValorFim;
  }

  /**
   * @param mixed $iValorInicio
   */
  public function setValorMinimo($iValorInicio) {
    $this->iValorInicio = $iValorInicio;
  }

  /**
   * @return mixed
   */
  public function getValorMinimo() {
    return $this->iValorInicio;
  }

  /**
   * @param DBInterval $oIdadeFinal
   */
  public function setIdadeFinal(DBInterval $oIdadeFinal) {
    $this->oIdadeFinal = $oIdadeFinal;
  }

  /**
   * @return DBInterval
   */
  public function getIdadeFinal() {
    return $this->oIdadeFinal;
  }

  /**
   * @param DBInterval $oIdadeInicial
   */
  public function setIdadeInicial(DBInterval $oIdadeInicial) {
    $this->oIdadeInicial = $oIdadeInicial;
  }

  /**
   * @return DBInterval
   */
  public function getIdadeInicial() {
    return $this->oIdadeInicial;
  }

  /**
   * @param integer $sSexo
   */
  public function adicionarSexo($sSexo) {

    if (!in_array($sSexo, $this->aSexos)) {
      $this->aSexos[] = $sSexo;
    }
  }

  /**
   * @return array
   */
  public function getSexos() {
    return $this->aSexos;
  }

  /**
   * @param mixed $iCodigo
   */
  protected function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return mixed
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param int $iTipoCalculo
   */
  public function setTipoCalculo($iTipoCalculo) {
    $this->iTipoCalculo = $iTipoCalculo;
  }

  /**
   * @return int
   */
  public function getTipoCalculo() {
    return $this->iTipoCalculo;
  }

  /**
   * @param AtributoExame $oAtributoBase
   */
  public function setAtributoBase(AtributoExame $oAtributoBase) {
    $this->oAtributoBase = $oAtributoBase;
  }

  /**
   * @return AtributoExame
   */
  public function getAtributoBase() {
    return $this->oAtributoBase;
  }


  /**
   * Persiste os dados da Referencia
   * @param integer $iCodigoReferencia
   * @throws BusinessException
   */
  public function salvar($iCodigoReferencia = null) {

    $oDaoTipoRefenciaSexo   = new cl_tiporeferenciaalnumericosexo();
    $oDaoTipoRefenciaIdade  = new cl_tiporeferenciaalnumericofaixaidade();
    $oDaoReferenciaNumerica = new cl_lab_tiporeferenciaalnumerico();
    $oDaoReferenciaCalculo  = new cl_tiporeferenciacalculo();
    if (!empty($this->iCodigo)) {
     $this->removerDadosAuxiliares();
    }

    $oDaoReferenciaNumerica->la30_f_normalmin  = $this->getValorMinimo();
    $oDaoReferenciaNumerica->la30_f_normalmax  = $this->getValorMaximo();
    $oDaoReferenciaNumerica->la30_f_absurdomin = $this->getValorAbsurdoMinimo();
    $oDaoReferenciaNumerica->la30_f_absurdomax = $this->getValorAbsurdoMaximo();
    $oDaoReferenciaNumerica->la30_i_valorref   = $iCodigoReferencia;
    if (empty($this->iCodigo)) {

      $oDaoReferenciaNumerica->incluir(null);
      $this->iCodigo  = $oDaoReferenciaNumerica->la30_i_codigo;
    } else {

      $oDaoReferenciaNumerica->la30_i_codigo = $this->iCodigo;
      $oDaoReferenciaNumerica->alterar($this->iCodigo);
    }

    if ($oDaoReferenciaNumerica->erro_status == 0) {
      throw new BusinessException("Erro ao salvar dados da Referencia numerica");
    }

    foreach ($this->aSexos as $sSexo) {

      $oDaoTipoRefenciaSexo->la60_sexo                    = $sSexo;
      $oDaoTipoRefenciaSexo->la60_tiporeferencialnumerico = $this->iCodigo;
      $oDaoTipoRefenciaSexo->incluir(null);
      if ($oDaoTipoRefenciaSexo->erro_status == 0) {
        throw new BusinessException("Erro ao salvar dados do sexo  da Refer�ncia numerica Erro:".$oDaoTipoRefenciaSexo->erro_msg);
      }
    }


    if ($this->getIdadeInicial() != null) {
      $oDaoTipoRefenciaIdade->la59_periodoinicial = $this->getIdadeInicial()->getInterval();
    }

    if ($this->getIdadeFinal() != null) {
      $oDaoTipoRefenciaIdade->la59_periodofinal = $this->getIdadeFinal()->getInterval();
    }
    $oDaoTipoRefenciaIdade->la59_tiporeferencialnumerico  = $this->iCodigo;
    $oDaoTipoRefenciaIdade->incluir(null);
    if ($oDaoTipoRefenciaIdade->erro_status == 0) {
      throw new BusinessException("Erro ao salvar dados da idade  da Refer�ncia numerica");
    }

    if ($this->getTipoCalculo() > 0) {

      $oDaoReferenciaCalculo->la61_atributobase = $this->getAtributoBase()->getCodigo();
      $oDaoReferenciaCalculo->la61_tipocalculo  = $this->getTipoCalculo();
      $oDaoReferenciaCalculo->la61_tiporeferencialnumerico = $this->iCodigo;
      $oDaoReferenciaCalculo->incluir(null);
      if ($oDaoReferenciaCalculo->erro_status == 0) {
        throw new BusinessException("Erro ao salvar dados de calculo da Refer�ncia numerica");
      }
    }
  }

  /**
   * Remove a referencia
   * @throws BusinessException
   */
  public function remover() {


    $oDaoReferenciaNumerica = new cl_lab_tiporeferenciaalnumerico();

    if (!empty($this->iCodigo)) {

      $this->removerDadosAuxiliares();
      $oDaoReferenciaNumerica->excluir($this->iCodigo);
      if ($oDaoReferenciaNumerica->erro_status == 0) {
        throw new BusinessException("Erro ao remover dados da refer�ncia numerica");
      }
    }
  }

  /**
   * Remove os dados auxilires da Referencia
   * @throws BusinessException
   */
  protected function removerDadosAuxiliares() {

    $oDaoTipoRefenciaSexo   = new cl_tiporeferenciaalnumericosexo();
    $oDaoTipoRefenciaIdade  = new cl_tiporeferenciaalnumericofaixaidade();
    $oDaoReferenciaCalculo  = new cl_tiporeferenciacalculo();
    if (!empty($this->iCodigo)) {

      $oDaoTipoRefenciaIdade->excluir(null, "la59_tiporeferencialnumerico = {$this->iCodigo}");
      if ($oDaoTipoRefenciaIdade->erro_status == 0) {
        throw new BusinessException("Erro ao remover dados da referencia numerica");
      }

      $oDaoTipoRefenciaSexo->excluir(null, "la60_tiporeferencialnumerico = {$this->iCodigo}");
      if ($oDaoTipoRefenciaSexo->erro_status == 0) {
        throw new BusinessException("Erro ao remover dados da refer�ncia numerica");
      }

      $oDaoReferenciaCalculo->excluir(null, "la61_tiporeferencialnumerico = {$this->iCodigo}");
      if ($oDaoReferenciaCalculo->erro_status == 0) {
        throw new BusinessException("Erro ao remover dados de c�lculo da refer�ncia numerica");
      }
    }
  }
}