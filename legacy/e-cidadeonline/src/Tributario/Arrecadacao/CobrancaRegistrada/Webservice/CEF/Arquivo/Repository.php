<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Webservice\CEF\Arquivo;

use ECidade\Tributario\Arrecadacao\Convenio;

/**
 * Repository para os dados que serão incluidos do arquivo
 * de requisição ao Webservice da CEF
 *
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 */
class Repository
{
  public function getDadosIncluiBoleto( $iNumpre, $iConvenio, $nValor )
  {
    require_once "classes/db_recibopaga_classe.php";
    require_once "classes/db_db_config_classe.php";
    require_once "classes/db_parametroscobrancaregistrada_classe.php";
    require_once "std/DBString.php";
    require_once "std/DBDate.php";

    $oRegistro = new \stdClass();
    $oRegistro->tipoEspecie        = "02";
    $oRegistro->flagAceite         = "S";
    $oRegistro->tipo               = "ISENTO";
    $oRegistro->acao               = "DEVOLVER";
    $oRegistro->numeroDias         = "15";
    $oRegistro->codigoMoeda        = "09";
    $oRegistro->flagRegistro       = "S";

    $oConvenio = new Convenio($iConvenio);

    /**
     * Dados do Recibo
     */
    $oDaoRecibopaga = new \cl_recibopaga();
    $sSqlRecibopaga = $oDaoRecibopaga->sql_query_dadosRecibo($iNumpre);
    $rsRecibopaga   = $oDaoRecibopaga->sql_record($sSqlRecibopaga);

    if ( !$rsRecibopaga ) {
      throw new \Exception("Erro ao buscar os dados do recibo para realizar o registro bancário.");
    }

    $oRecibopaga = \db_utils::fieldsMemory($rsRecibopaga, 0);

    $oRegistro->codigoBeneficiario = $oConvenio->getCedente();
    $oRegistro->nossoNumero        = $oRecibopaga->nosso_numero;
    $oRegistro->numeroDocumento    = $iNumpre . "000";
    $oRegistro->dataVencimento     = $oRecibopaga->data_vencimento;
    $oRegistro->valor              = (string) db_formatar($nValor, 'p', ' ', strlen($nValor));
    $oRegistro->valor              = str_pad($oRegistro->valor, 16, '0', STR_PAD_LEFT);
    $oRegistro->valorJuros         = (string) db_formatar(0, 'p', ' ', strlen('0'));
    $oRegistro->valorJuros         = str_pad($oRegistro->valorJuros, 16, '0', STR_PAD_LEFT);
    $oRegistro->data               = $oRecibopaga->data_emissao;

    /**
     * Dados do CGM
     */
    $sCampos = "distinct k00_numcgm, z01_nome, z01_cgccpf";
    $sSqlCgm = $oDaoRecibopaga->sql_query_cgm($sCampos, $iNumpre);
    $rsCgm   = $oDaoRecibopaga->sql_record($sSqlCgm);

    if (!$rsCgm) {
      throw new \Exception("Erro ao buscar o CGM vinculado ao recibo para realizar o registro bancário.");
    }

    $oCgm = \db_utils::fieldsMemory($rsCgm, 0);

    /**
     * Inserimos '1' no cnpj/cpf quando este for vazio, para não quebrar a formatação do arquivo de envio
     */
    if ( $oCgm->z01_cgccpf == '' ) {
      $oCgm->z01_cgccpf = 1;
    }

    $oRegistro->cpfcnpj = $oCgm->z01_cgccpf;
    $oRegistro->nome    = \DBString::removerCaracteresEspeciais($oCgm->z01_nome);

    /**
     * Dados do CGM
     */
    $oDaoDbConfig  = new \cl_db_config();
    $oSqlDbConfig  = $oDaoDbConfig->sql_query(db_getsession("DB_instit"));
    $rsInstituicao = $oDaoDbConfig->sql_record($oSqlDbConfig);

    if (!$rsInstituicao) {
      throw new \Exception("Erro ao buscar os dados da Instituição.");
    }

    $oInstituicao = \db_utils::fieldsMemory($rsInstituicao, 0);

    if ( empty($oInstituicao->cgc) ) {
      throw new \BusinessException("Instituição com o cnpj inválido.");
    }

    /**
     * Hash de Autenticação
     */
    $oAutenticacao = new Autenticacao($oRegistro->codigoBeneficiario,
                                      $oRegistro->nossoNumero,
                                      $oRegistro->dataVencimento,
                                      $oRegistro->valor,
                                      $oInstituicao->cgc);

    $oRegistro->autenticacao = $oAutenticacao->getHash();

    /**
     * Usuário do webservice
     */
    $oDaoParametro = new \cl_parametroscobrancaregistrada();
    $sSqlParametro = $oDaoParametro->sql_query_file();
    $rsParametro   = $oDaoParametro->sql_record($sSqlParametro);

    if (!$rsParametro) {
      throw new \Exception("Não foi encontrado o usuário do webservice da CEF.");
    }

    $oParametro = \db_utils::fieldsMemory($rsParametro, 0);

    $oRegistro->usuarioServico = $oParametro->ar28_usuario;

    return $oRegistro;
  }
}
