<?php
const CAMINHO_MENSAGEM_REQUISICAO_LABORATORIAL = "laboratorio.RequisicaoLaboratorial";
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

/**
 * Class RequisicaoLaboratorial
 * @package Laboratorio
 * @author Andr� Mello andre.mello@dbseller.com.br
 * @version $
 */
class RequisicaoLaboratorial {

  private $iCodigo = null;

  /**
   * Nome do m�dico respons�vel pela requisi��o
   * @var string
   */
  private $sMedico;

  /**
   * Array de objetos de RequisicaoExame
   * @var array RequisicaoExame
   */
  private $aRequisicaoExame;

  /**
   * Data da requisi��o
   * @var DBDate
   */
  private $oData;

/**
 * Construtor da Requisi��oExame (lab_requisicao)
 * @param integer $iCodigo C�digo da tabela lab_requisicao
 * @throws BusinessException If C�digo informado n�o for encontrado
 */
  public function __construct($iCodigo) {

    if ( !empty($iCodigo) ) {

      $oDaoRequisicaoLaboratorial = new cl_lab_requisicao();
      $sSqlRequisicaoLaboratorial = $oDaoRequisicaoLaboratorial->sql_query_file($iCodigo);
      $rsRequisicaoLaboratorial   = $oDaoRequisicaoLaboratorial->sql_record($sSqlRequisicaoLaboratorial);

      if (!$rsRequisicaoLaboratorial || $oDaoRequisicaoLaboratorial->numrows == 0) {
        throw new BusinessException( _M(CAMINHO_MENSAGEM_REQUISICAO_LABORATORIAL.".codigo_nao_encontrado") );
      }

      $oDadosRequisicao = db_utils::fieldsMemory($rsRequisicaoLaboratorial, 0);
      $this->iCodigo    = $oDadosRequisicao->la22_i_codigo;
      $this->sMedico    = $oDadosRequisicao->la22_c_medico;
      $this->oData      = new DBDate($oDadosRequisicao->la22_d_data);
    }
  }

  /**
   * Respons�vel por buscar todas as Requisi��es de Exames vinculadas a Requisi��o Laboratorial
   * @return RequisicaoExame[]
   */
  public function getRequisicoesDeExames() {

    if ( count ($this->aRequisicaoExame) == 0 ) {

      $oDaoRequisicaoExame   = new cl_lab_requiitem();
      $sWhere                = "la21_i_requisicao = {$this->iCodigo}";
      $sSqlRequisicaoExame   = $oDaoRequisicaoExame->sql_query_file('', 'la21_i_codigo', '', $sWhere);
      $rsRequisicaoExame     = $oDaoRequisicaoExame->sql_record( $sSqlRequisicaoExame );
      $iLinhaRequisicaoExame = $oDaoRequisicaoExame->numrows;

      if ( $rsRequisicaoExame && $iLinhaRequisicaoExame > 0 ) {

        for ( $iContador = 0; $iContador < $iLinhaRequisicaoExame; $iContador++ ) {

          $iRequisicaoExame         = db_utils::fieldsMemory($rsRequisicaoExame, $iContador)->la21_i_codigo;
          $oRequisicaoExame         = new RequisicaoExame( $iRequisicaoExame );
          $this->aRequisicaoExame[] = $oRequisicaoExame;
        }
      }
    }
    return $this->aRequisicaoExame;
  }

  /**
   * Retorna o nome do m�dico. Verifica se existe registro no campo la22_c_medico referente a lab_requisicao.
   * Caso n�o exista o nome de um m�dico, busca do v�nculo de lab_medico com lab_requisicao, o nome no CGM
   * @return string
   */
  public function getMedico() {

    if( empty( $this->sMedico ) ) {

      $oDaoLabMedicos = new cl_lab_medico();
      $sSqlLabMedico  = $oDaoLabMedicos->sql_query( null, 'z01_nome', null, "la38_i_requisicao = {$this->iCodigo}" );
      $rsLabMedico    = db_query( $sSqlLabMedico );

      if( !$rsLabMedico ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();
        throw new DBException( _M( CAMINHO_MENSAGEM_REQUISICAO_LABORATORIAL + 'erro_buscar_medico', $oErro ) );
      }

      if( pg_num_rows( $rsLabMedico ) > 0 ) {
        $this->sMedico = db_utils::fieldsMemory( $rsLabMedico, 0 )->z01_nome;
      }
    }

    return $this->sMedico;
  }


  /**
   * Retorna o c�digo da requisi��o
   * @return integer
   */
  public function getCodigo() {

    return $this->iCodigo;
  }

  /**
   * retorna a data de cria��o da requisi��o
   * @return DBDate
   */
  public function getData() {

    return $this->oData;
  }
}