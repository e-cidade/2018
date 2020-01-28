<?php
/**
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
require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_app.utils.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("libs/JSON.php"));

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

db_putsession('DB_desativa_trigger_endereco', '1');
define("MSG_SAU4_CGSRPC", "saude.ambulatorial.sau4_cgsRPC.");

try {

  db_inicio_transacao();

  switch ($oParam->sExecucao) {

    case "buscarDadosCgs":

      if ( !isset($oParam->iCgs) || empty($oParam->iCgs) ) {
        throw new ParameterException( _M( MSG_SAU4_CGSRPC . "informe_cgs" ) );
      }

      $oCgs            = new Cgs( $oParam->iCgs );
      $oDataAtual      = new DBDate( date("Y-m-d") );
      $sIdadeCompleta  = '';
      $sDataNascimento = '';

      if(isset($oParam->lValidaCgs) && $oCgs->getCodigo() == null) {
        throw new ParameterException(_M(MSG_SAU4_CGSRPC . 'cgs_inexistente'));
      }

      if($oCgs->getCodigo() != null) {

        $iQuantidadeDias = DBDate::calculaIntervaloEntreDatas( $oDataAtual, $oCgs->getDataNascimento(), 'd' );
        $sIdadeCompleta  = urlencode( DBDate::getIdadeCompleta($iQuantidadeDias) );
        $sDataNascimento = $oCgs->getDataNascimento()->convertTo(DBDate::DATA_PTBR);
      }

      $oRetorno->oCgs                       = new stdClass();
      $oRetorno->oCgs->iCgs                 = $oCgs->getCodigo();
      $oRetorno->oCgs->sNome                = urlencode( $oCgs->getNome() );
      $oRetorno->oCgs->sSexo                = urlencode( $oCgs->getSexo() );
      $oRetorno->oCgs->dtNascimento         = $sDataNascimento;
      $oRetorno->oCgs->sIdadeCompleta       = $sIdadeCompleta;
      $oRetorno->oCgs->sNomeMae             = urlencode( $oCgs->getNomeMae() );
      $oRetorno->oCgs->sEndereco            = urlencode( $oCgs->getEndereco() );
      $oRetorno->oCgs->iNumero              = $oCgs->getNumero();
      $oRetorno->oCgs->sComplemento         = urlencode( $oCgs->getComplemento() );
      $oRetorno->oCgs->sBairro              = urlencode( $oCgs->getBairro() );
      $oRetorno->oCgs->sCep                 = urlencode( $oCgs->getCep() );
      $oRetorno->oCgs->sMunicipio           = urlencode( $oCgs->getMunicipio() );
      $oRetorno->oCgs->sUF                  = urlencode( $oCgs->getUF() );
      $oRetorno->oCgs->sEstadoCivil         = urlencode( $oCgs->getEstadoCivil() );
      $oRetorno->oCgs->sEmail               = $oCgs->getEmail();
      $oRetorno->oCgs->sCelular             = $oCgs->getCelular();
      $oRetorno->oCgs->sRaca                = $oCgs->getRaca();
      $oRetorno->oCgs->sPis                 = $oCgs->getPis();
      $oRetorno->oCgs->iNaturalidade        = $oCgs->getNaturalidade();
      $oRetorno->oCgs->iPaisOrigem          = $oCgs->getPaisOrigem();
      $oRetorno->oCgs->sMunicipioNascimento = $oCgs->getMunicipioNascimento();
      $oRetorno->oCgs->sUfNascimento        = $oCgs->getUfNascimento();
      $oRetorno->oCgs->sIbgeNascimento      = $oCgs->getIbgeNascimento();
      $oRetorno->oCgs->iEscolaridade        = $oCgs->getEscolaridade();
      $oRetorno->oCgs->sDesconheceMae       = $oCgs->desconheceMae() ? 't' : 'f';
      $oRetorno->oCgs->iCodigoCartaoSus     = '';
      $oRetorno->oCgs->sCartaoSus           = '';

      foreach($oCgs->getCartaoSus() as $oCartaoSus) {

        if($oCartaoSus->sTipoCartaoSus != 'D') {
          continue;
        }

        $oRetorno->oCgs->iCodigoCartaoSus = $oCartaoSus->iCodigo;
        $oRetorno->oCgs->sCartaoSus       = $oCartaoSus->sCartaoSus;
      }

      break;

    case 'buscaExames':

      if ( !isset($oParam->iCgs) || empty($oParam->iCgs) ) {
        throw new ParameterException( _M( MSG_SAU4_CGSRPC . "informe_cgs" ) );
      }

      $oCgs = CgsRepository::getByCodigo( $oParam->iCgs );

      $aExames = array();
      foreach ($oCgs->getRequisicoesExame() as $oRequisicao) {

        foreach ($oRequisicao->getRequisicoesDeExames() as $oExamesRequisicao) {

          $oDados               = new stdClass();
          $oDados->iRequisicao  = $oRequisicao->getCodigo();
          $oDados->iItem        = $oExamesRequisicao->getCodigo();
          $oDados->sExame       = urlencode($oExamesRequisicao->getExame()->getNome());
          $oDados->sSituacao    = urlencode($oExamesRequisicao->getDescricaoSituacao());
          $oDados->dtRequisicao = $oRequisicao->getData()->getDate();
          $aExames[] = $oDados;
        }
      }

      $oRetorno->aExames = $aExames;

      break;

    /**
     * Retorna uma coleção com os documentos do CGS
     */
    case 'documentosCgs':

      if( empty( $oParam->iCgs ) ) {
        throw new ParameterException( _M( MSG_SAU4_CGSRPC . 'informe_cgs' ) );
      }
      $oRetorno->aDocumentos = getDocumentos($oParam->iCgs);
      break;

    /**
     * Realiza o vínculo do CGS com um documento e seus valores salvos
     */
    case 'vinculaCgsDocumento':

      if( empty( $oParam->iCgs ) ) {
        throw new ParameterException( _M( MSG_SAU4_CGSRPC . 'informe_cgs' ) );
      }

      if( empty( $oParam->iDocumento ) ) {
        throw new ParameterException( _M( MSG_SAU4_CGSRPC . 'documento_nao_informado' ) );
      }

      $oCgs = CgsRepository::getByCodigo( $oParam->iCgs );
      $oCgs->salvarCgsDocumento( $oParam->iDocumento );

      $oRetorno->sMessage = _M( MSG_SAU4_CGSRPC . 'documento_salvo' );

      break;

    case 'getDadosCadastroAlteracao':

      if( empty( $oParam->cgs ) ) {
        throw new ParameterException( _M( MSG_SAU4_CGSRPC . 'informe_cgs' ) );
      }

      $oDao                = new cl_cgs_und();
      $sSqlDadosCGS        = $oDao->sql_query_cadastro($oParam->cgs);
      $resultadoQuery      = db_query($sSqlDadosCGS);

      if (!$resultadoQuery) {
        throw new DBException("Não foi possivel recuperar os dados do CGS informado({$oParam->cgs})");
      }

      $oRetorno->informacoesCGS   = (object)array(
        "dados_pessoais" => getDadosPessoais($resultadoQuery),
        "contato"        => getDadosContato($resultadoQuery),
        "biometria"      => getDadosBiometricos($resultadoQuery),
        "outros_dados"   => getOutrosDados($resultadoQuery),
      );

    case 'getDadosCadastroNovo':

      $oRetorno->informacoesPadrao = getDadosPadrao();
      break;

    case 'salvarCgs':

      $oParam = JSON::create()->parse(str_replace("\\","",$_POST["json"]));
      $oCgs   = CgsRepository::getByCodigo( $oParam->iCgs );
      $oCgs->salvar( $oParam );

      $oRetorno->iCgs     = $oCgs->getCodigo();
      $oRetorno->sMessage = _M( MSG_SAU4_CGSRPC . 'dados_salvos' );

      break;

    case 'excluirCgs':

      if( empty( $oParam->iCgs ) ) {
        throw new ParameterException( _M( MSG_SAU4_CGSRPC . 'informe_cgs' ) );
      }

      $oCgs = CgsRepository::getByCodigo( $oParam->iCgs );
      $oCgs->excluir();

      $oRetorno->sMessage = _M( MSG_SAU4_CGSRPC . 'cgs_excluido' );

      break;

    case "validarCGS":

      $oRetorno->valido = Cgs::validar(
        new Cgs($oParam->cgs)
      );
      break;

  }

  db_fim_transacao(false);
} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}

$oRetorno->erro = $oRetorno->iStatus == 2;
echo $oJson->encode($oRetorno);

/**
 * Funções
 */
function getDadosPessoais($rsResultaoCGS) {

  return db_utils::makeFromRecord($rsResultaoCGS, function($oResultado) {

    $oRetorno   = new stdClass();
    $oResultado = DBString::utf8_encode_all($oResultado);

    $oRetorno->codigo_cartao_sus      = trim($oResultado->s115_i_codigo);
    $oRetorno->cadastroInativo        = $oResultado->z01_b_inativo;
    $oRetorno->cns                    = trim($oResultado->s115_c_cartaosus);
    $oRetorno->nome                   = trim($oResultado->z01_v_nome);
    $oRetorno->nome_mae               = trim($oResultado->z01_v_mae);
    $oRetorno->nome_pai               = trim($oResultado->z01_v_pai);
    $oRetorno->sexo                   = trim($oResultado->z01_v_sexo);
    $oRetorno->raca                   = trim($oResultado->z01_c_raca);
    $oRetorno->codigo_etnia           = trim($oResultado->s201_etnia);
    $oRetorno->label_etnia            = trim($oResultado->s200_descricao);
    $oRetorno->fator_rh               = trim($oResultado->z01_i_fatorrh);
    $oRetorno->tipo_sanguineo         = trim($oResultado->z01_i_tiposangue);
    $oRetorno->data_nascimento        = trim($oResultado->z01_d_nasc);
    $oRetorno->nacionalidade          = trim($oResultado->z01_i_nacion);
    $oRetorno->paisOrigem             = trim($oResultado->z01_i_paisorigem);
    $oRetorno->cgsMunicipio           = $oResultado->z01_registromunicipio;

    $oRetorno->municipio_nascimento   = trim($oResultado->z01_v_municnasc);
    $oRetorno->uf_nascimento          = trim($oResultado->z01_v_ufnasc);
    $oRetorno->codigo_ibge_nascimento = $oResultado->z01_codigoibgenasc;

    $oRetorno->data_obito             = trim($oResultado->z01_d_falecimento);
    return $oRetorno;
  }, 0);
}

function getDadosContato($rsResultaoCGS) {

  return db_utils::makeFromRecord($rsResultaoCGS, function($oResultado) {

    $oRetorno = new stdClass();
    $oRetorno->email                  = trim($oResultado->z01_v_email);
    $oRetorno->telefone_fixo          = trim($oResultado->z01_v_telef);
    $oRetorno->telefone_celular       = trim($oResultado->z01_v_telcel);
    $oRetorno->fax                    = trim($oResultado->z01_v_fax);
    $oRetorno->endereco               = $oResultado->db76_sequencial;
    return $oRetorno;
  }, 0);
}

function getDadosBiometricos($rsResultaoCGS) {

  return db_utils::makeFromRecord($rsResultaoCGS, function($oResultado) {

    $oRetorno = new stdClass();
    $oRetorno->foto_oid               = trim($oResultado->z01_o_oid);
    $oRetorno->foto_caminho           = trim($oResultado->z01_c_foto);
    return $oRetorno;
  }, 0);
}

function getOutrosDados($rsResultaoCGS) {

  return db_utils::makeFromRecord($rsResultaoCGS, function($oResultado) {

    $oRetorno = new stdClass();
    $oRetorno->codigo_cgm      = trim($oResultado->z01_numcgm);
    $oRetorno->label_cgm       = trim($oResultado->z01_nome);
    $oRetorno->codigo_aluno    = trim($oResultado->ed47_i_codigo);
    $oRetorno->label_aluno     = trim($oResultado->ed47_v_nome);
    $oRetorno->codigo_cidadao  = trim($oResultado->ov02_sequencial);
    $oRetorno->label_cidadao   = trim($oResultado->ov02_nome);
    $oRetorno->codigo_ocupacao = trim($oResultado->rh70_sequencial);
    $oRetorno->label_ocupacao  = trim($oResultado->rh70_descr);
    $oRetorno->estado_civil    = trim($oResultado->z01_i_estciv);
    $oRetorno->microarea       = trim($oResultado->sd35_i_microarea);
    $oRetorno->familia         = trim($oResultado->sd35_i_codigo);
    $oRetorno->responsavel     = trim($oResultado->z01_c_nomeresp);
    $oRetorno->observacoes     = trim($oResultado->z01_t_obs);
    $oRetorno->escolaridade    = trim($oResultado->z01_i_escolaridade);
    return $oRetorno;
  }, 0);
}

function getDocumentos($iCodigoCGS) {

  $aDocumentos           = array();
  $oCgs                  = CgsRepository::getByCodigo($iCodigoCGS);

  foreach( $oCgs->getDocumentos() as $oDocumentoBase ) {

    $oDadosDocumento                  = new stdClass();
    $oDadosDocumento->codigoDocumento = $oDocumentoBase->getCodigo();
    $oDadosDocumento->documento       = utf8_encode($oDocumentoBase->getDescricao());
    $oDadosDocumento->documentoValor  = $oDocumentoBase->getDocumento();

    $aDocumentos[] = $oDadosDocumento;
  }

  return $aDocumentos;
}

function getDadosPadrao() {

  /**
   * Dados da microarea e familia
   */
  $oDaoFamiliaMicroArea = new cl_familiamicroarea();
  $sSqlDadosFamilia     = $oDaoFamiliaMicroArea->sql_query(null, "*", "sd34_v_descricao asc, sd33_v_descricao asc");
  $rsDadosFamilias      = db_query($sSqlDadosFamilia);

  if(!$rsDadosFamilias) {
    throw new DBException(_M(MSG_SAU4_CGSRPC.'erro_buscar_dados_familia'));
  }

  $aMicroAreas = array();
  $aFamilias   = array();

  /**
   * Separa a familia da microarea
   */
  db_utils::makeCollectionFromRecord($rsDadosFamilias, function($oDados) use (&$aMicroAreas, &$aFamilias) {

    $aMicroAreas[$oDados->sd34_i_codigo] = array(
      "codigo_microarea" => $oDados->sd34_i_codigo,
      "label_microarea"  => $oDados->sd34_v_descricao
    );

    $aFamilias[$oDados->sd35_i_codigo]   = array(
      "codigo_microarea" => $oDados->sd34_i_codigo,
      "codigo_familia"   => trim($oDados->sd35_i_codigo),
      "label_familia"    => $oDados->sd33_v_descricao,
    );
  });

  foreach ($aFamilias as $oFamilia) {
    $aMicroAreas[$oFamilia["codigo_microarea"]]["familias"][] = $oFamilia;
  }

  /**
   * Paises de origem
   */
  $oDaoPais    = new cl_pais();
  $sCamposPais = "ed228_i_codigo, ed228_c_descr";
  $sSqlPais    = $oDaoPais->sql_query_file( "", $sCamposPais, "ed228_c_descr");
  $rsPais      = db_query( $sSqlPais );

  if( !is_resource( $rsPais ) ) {
    throw new DBException( _M( MSG_SAU4_CGSRPC . 'erro_buscar_pais_origem' ) );
  }

  $aPaises = db_utils::makeCollectionFromRecord($rsPais, function($oResultado) {

    $oRetorno              = new stdClass();
    $oRetorno->codigo_pais = $oResultado->ed228_i_codigo;
    $oRetorno->label_pais  = trim( $oResultado->ed228_c_descr );

    return $oRetorno;
  });

  sort($aMicroAreas);
  sort($aFamilias);

  return (object)array(
    "microareas" => $aMicroAreas,
    "paisOrigem" => $aPaises,
  );
}
