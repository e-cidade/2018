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

define( "MENSAGENS_EXAME_MODEL", "saude.laboratorio.Exame." );

class Exame {

  protected $iCodigo;

  /**
   * Nome do Exame
   * @var string
   */
  protected $sNome = '';

  /**
   * Observação do Exame
   * @var string
   */
  protected $sObservacao = '';

  /**
   * Atributos do Exame
   * @var AtributoExame[]
   */
  protected $aAtributos = array();

  /**
   * Lista com os atributos dispensados do exame
   * @var array
   */
  private $aAtributosDispensados = array();

  /**
   * Instância de ProcedimentoSaude, do procedimento vinculado ao exame
   * @var ProcedimentoSaude
   */
  private $oProcedimento = null;

  /**
   * Instancia o novo Exame
   *
   * @param $iCodigoExame
   * @throws BusinessException
   */
  public function __construct($iCodigoExame) {

    $oDaoExame   = new cl_lab_exame();
    $oDadosExame = db_utils::getRowFromDao($oDaoExame, array($iCodigoExame));

    if ( $oDadosExame == null ) {
      throw new BusinessException("Exame não cadastrado");
    }

    $this->iCodigo     = $oDadosExame->la08_i_codigo;
    $this->sNome       = $oDadosExame->la08_c_descr;
    $this->sObservacao = $oDadosExame->la08_observacao;
  }

  /**
   * @return AtributoExame[]
   */
  public function getAtributos() {

    $aAtributosDispensadosNoExame =  $this->getAtributosDispensados();
    if (count($this->aAtributos) == 0) {


      $oDaoAtributosExame = new cl_lab_exameatributo();
      $sSqlAtributos      = $oDaoAtributosExame->sql_query(null, "la42_i_atributo",
                                                           "la25_c_estrutural",
                                                           "la42_i_exame = {$this->iCodigo}"
                                                          );

      $rsAtributos = $oDaoAtributosExame->sql_record($sSqlAtributos);
      $aListaAtributos = array();
      if ($rsAtributos && $oDaoAtributosExame->numrows > 0) {

        for ($iAtributo = 0; $iAtributo < $oDaoAtributosExame->numrows; $iAtributo++) {

          $iCodigoAtributo   = db_utils::fieldsMemory($rsAtributos, $iAtributo)->la42_i_atributo;
          $aListaAtributos[] = $iCodigoAtributo;
          $aListaAtributos   = $this->montarArvoresAtributo($iCodigoAtributo, $aListaAtributos);
        }

        foreach ($aListaAtributos as $iCodigoAtributo) {

          if (in_array($iCodigoAtributo, $aAtributosDispensadosNoExame)) {
            continue;
          }
          $this->aAtributos[] = AtributoExameRepository::getbyCodigo($iCodigoAtributo);
        }
      }
    }
    return $this->aAtributos;
  }


  protected function montarArvoresAtributo($iAtributo, &$aListaAtributos) {

    $oDaoAtributoLigacao = new cl_lab_exameatributoligacao();
    $sSqlAtributos       = $oDaoAtributoLigacao->sql_query_filho(null,
                                                           "la26_i_exameatributofilho",
                                                           "la25_c_estrutural" ,
                                                           "la26_i_exameatributopai={$iAtributo}"
                                                           );

    $rsAtributos  = $oDaoAtributoLigacao->sql_record($sSqlAtributos);
    $iTotalLinhas = $oDaoAtributoLigacao->numrows;
    for ($iAtributo = 0; $iAtributo < $iTotalLinhas; $iAtributo++) {

      $iCodigoAtributo   = db_utils::fieldsMemory($rsAtributos, $iAtributo)->la26_i_exameatributofilho;
      $aListaAtributos[] = $iCodigoAtributo;
      $this->montarArvoresAtributo($iCodigoAtributo, $aListaAtributos);
    }
    return $aListaAtributos;
  }

  protected function getAtributosDispensados() {

    if (count($this->aAtributosDispensados) == 0) {
      $oDaoAtributosDispensados = new cl_lab_examedisp();
      $sSqlAtributos            = $oDaoAtributosDispensados->sql_query(null, "la50_i_atributo as item",
        null," la42_i_exame={$this->iCodigo} "
      );
      $rsAtributos = $oDaoAtributosDispensados->sql_record($sSqlAtributos);
      if ($rsAtributos && $oDaoAtributosDispensados->numrows > 0) {
        for ($iAtributo = 0; $iAtributo < $oDaoAtributosDispensados->numrows; $iAtributo++) {
          $this->aAtributosDispensados[] = db_utils::fieldsMemory($rsAtributos, $iAtributo)->item;
        }
      }
    }

    return $this->aAtributosDispensados;
  }

  /**
   * Retorna a instância do procedimento vinculado ao exame
   * @return ProcedimentoSaude
   * @throws DBException
   */
  public function getProcedimento() {

    $oDaoExameProced   = new cl_lab_exameproced();
    $sWhereExameProced = "la53_i_exame = {$this->iCodigo}";
    $sSqlExameProced   = $oDaoExameProced->sql_query_file( null, "la53_i_procedimento", null, $sWhereExameProced );
    $rsExameProced     = db_query( $sSqlExameProced );

    if ( !$rsExameProced ) {

      $oMensagem        = new stdClass();
      $oMensagem->sErro = pg_result_error( $rsExameProced );
      throw new DBException( _M( MENSAGENS_EXAME_MODEL . "erro_buscar_procedimento" ) );
    }

    if ( pg_num_rows( $rsExameProced ) ) {
      $this->oProcedimento = new ProcedimentoSaude( db_utils::fieldsMemory( $rsExameProced, 0 )->la53_i_procedimento );
    }

    return $this->oProcedimento;
  }

  /**
   * Retorna o código do exame
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna o nome do exame
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }

  /**
   * Retorna a observação do exame
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  public function getMaterialColeta(){

    $aMateriaisColeta = array();

    if( empty($this->iCodigo) ) {
      return $aMateriaisColeta;
    }

    $oDaoMaterialColeta = new cl_lab_examematerial();

    $sWhere = " la19_i_exame = {$this->iCodigo} ";
    $sCampos = " la11_c_descr as metodo_coleta, la15_c_descr as material_coleta ";

    $sSqlMaterialColeta = $oDaoMaterialColeta->sql_query(null, $sCampos, null, $sWhere);
    $rsMaterialColeta = db_query($sSqlMaterialColeta);

    if( !$rsMaterialColeta ) {
      throw new DBException('Falhar ao buscar os materiais de coleta.');
    }

    $oDadosMaterialColeta = db_utils::getColectionByRecord($rsMaterialColeta);

    return $oDadosMaterialColeta;
  }
}