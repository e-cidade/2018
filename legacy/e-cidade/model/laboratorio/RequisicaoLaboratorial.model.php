<?php
const CAMINHO_MENSAGEM_REQUISICAO_LABORATORIAL = "laboratorio.RequisicaoLaboratorial";
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2014 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

/**
 * Class RequisicaoLaboratorial
 * @package Laboratorio
 * @author André Mello andre.mello@dbseller.com.br
 * @version $
 */
class RequisicaoLaboratorial {

  private $iCodigo = null;

  /**
   * Nome do médico responsável pela requisição
   * @var string
   */
  private $sMedico;

  /**
   * Array de objetos de RequisicaoExame
   * @var array RequisicaoExame
   */
  private $aRequisicaoExame;

  /**
   * Data da requisição
   * @var DBDate
   */
  private $oData;

/**
 * Construtor da RequisiçãoExame (lab_requisicao)
 * @param integer $iCodigo Código da tabela lab_requisicao
 * @throws BusinessException If Código informado não for encontrado
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
   * Responsável por buscar todas as Requisições de Exames vinculadas a Requisição Laboratorial
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
   * Retorna o nome do médico. Verifica se existe registro no campo la22_c_medico referente a lab_requisicao.
   * Caso não exista o nome de um médico, busca do vínculo de lab_medico com lab_requisicao, o nome no CGM
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
   * Retorna o código da requisição
   * @return integer
   */
  public function getCodigo() {

    return $this->iCodigo;
  }

  /**
   * retorna a data de criação da requisição
   * @return DBDate
   */
  public function getData() {

    return $this->oData;
  }
}