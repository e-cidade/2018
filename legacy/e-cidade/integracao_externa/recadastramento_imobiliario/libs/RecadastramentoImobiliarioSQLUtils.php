<?php
/**
 * Classe de SQLs e Outras Utilidades para a Recadastramento
 *
 * @abstract
 * @package Recadastramento Imobiliário
 * @version $Version: $
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br> 
 */
abstract class RecadastramentoSQLUtils {

  /**
   * Retorna os Campos da tabela iptubase 
   *
   * @param mixed $iCodigoMatricula
   * @param mixed $sCampos 
   * @static
   * @access public
   * @return void
   */
  public static function getDadosIPTUBase( $iCodigoMatricula, $sCampos ) {

    $sSql  = "select $sCampos from iptubase where j01_matric = $iCodigoMatricula";
    $rsSql = pg_query(Conexao::getInstancia()->getConexao(), $sSql);

    if ( !$rsSql ) {
      throw new Exception("Erro ao Buscar os dados da Matricula");
    }
    if ( pg_num_rows($rsSql) == 0) {    
      return false;
    }
    return  db_utils::fieldsMemory( $rsSql, 0 );
  }

  /**
   * Retorna os dados da Situacao Fiscal do Imovel
   * 
   * @param mixed $iCodigoMatricula 
   * @static
   * @access public
   * @return void
   */
  public static function getCaracteristicasLote( $iCodigoMatricula ) {

    $oRetorno   = new stdClass();
    $oRetorno->iIlumunicao           = "";
    $oRetorno->iRedeEletrica         = "";
    $oRetorno->iRedeTelefonica       = "";
    $oRetorno->iRedeAgua             = "";
    $oRetorno->iGaleriasPluviais     = "";
    $oRetorno->iSujeitoInundacoes    = "";
    $oRetorno->iPavimentacao         = "";
    $oRetorno->iColetaLixo           = "";
    $oRetorno->iVarricao             = "";
    $oRetorno->iRedeEsgoto           = "";
    $oRetorno->iPropriedade          = "";
    $oRetorno->iSituacao             = "";
    $oRetorno->iCaracteristica       = "";
    $oRetorno->iNivel                = "";
    $oRetorno->iOcupacao             = "";
    $oRetorno->iNumeroFrentes        = "";
    $oRetorno->iPosicaoFiscal        = "";
    $oRetorno->iArea                 = "";
    $oRetorno->iTipoImovel           = "";
    $oRetorno->iIrregular            = "";


    $sSqlSituacaoFiscal = "select *                                             \n";
    $sSqlSituacaoFiscal.= "  from iptubase                                      \n";
    $sSqlSituacaoFiscal.= "       inner join carlote  on j35_idbql  = j01_idbql \n";
    $sSqlSituacaoFiscal.= "       inner join caracter on j31_codigo = j35_caract\n";
    $sSqlSituacaoFiscal.= "       inner join cargrup  on j32_grupo  = j31_grupo \n";
    $sSqlSituacaoFiscal.= " where j01_matric = $iCodigoMatricula                \n";

    $rsCaracteristicas  = pg_query(Conexao::getInstancia()->getConexao(), $sSqlSituacaoFiscal);

    if ( !$rsCaracteristicas ) {
      throw new Exception('Erro ao Buscar carateristicas do Lote:' . pg_last_error() );
    }

    foreach ( db_utils::getCollectionByRecord($rsCaracteristicas) as $oRegistro ) {

      $oRetorno->iIlumunicao           = $oRegistro->j32_grupo == 1  ? $oRegistro->j31_codigo : $oRetorno->iIlumunicao       ;
      $oRetorno->iRedeEletrica         = $oRegistro->j32_grupo == 2  ? $oRegistro->j31_codigo : $oRetorno->iRedeEletrica     ;
      $oRetorno->iRedeTelefonica       = $oRegistro->j32_grupo == 3  ? $oRegistro->j31_codigo : $oRetorno->iRedeTelefonica   ;
      $oRetorno->iRedeAgua             = $oRegistro->j32_grupo == 4  ? $oRegistro->j31_codigo : $oRetorno->iRedeAgua         ;
      $oRetorno->iGaleriasPluviais     = $oRegistro->j32_grupo == 5  ? $oRegistro->j31_codigo : $oRetorno->iGaleriasPluviais ;
      $oRetorno->iSujeitoInundacoes    = $oRegistro->j32_grupo == 6  ? $oRegistro->j31_codigo : $oRetorno->iSujeitoInundacoes;
      $oRetorno->iPavimentacao         = $oRegistro->j32_grupo == 7  ? $oRegistro->j31_codigo : $oRetorno->iPavimentacao     ;
      $oRetorno->iColetaLixo           = $oRegistro->j32_grupo == 8  ? $oRegistro->j31_codigo : $oRetorno->iColetaLixo       ;
      $oRetorno->iVarricao             = $oRegistro->j32_grupo == 9  ? $oRegistro->j31_codigo : $oRetorno->iVarricao         ;
      $oRetorno->iRedeEsgoto           = $oRegistro->j32_grupo == 10 ? $oRegistro->j31_codigo : $oRetorno->iRedeEsgoto       ;
      $oRetorno->iPropriedade          = $oRegistro->j32_grupo == 20 ? $oRegistro->j31_codigo : $oRetorno->iPropriedade      ;
      $oRetorno->iSituacao             = $oRegistro->j32_grupo == 21 ? $oRegistro->j31_codigo : $oRetorno->iSituacao         ;
      $oRetorno->iCaracteristica       = $oRegistro->j32_grupo == 22 ? $oRegistro->j31_codigo : $oRetorno->iCaracteristica   ;
      $oRetorno->iNivel                = $oRegistro->j32_grupo == 23 ? $oRegistro->j31_codigo : $oRetorno->iNivel            ;
      $oRetorno->iOcupacao             = $oRegistro->j32_grupo == 24 ? $oRegistro->j31_codigo : $oRetorno->iOcupacao         ;
      $oRetorno->iNumeroFrentes        = $oRegistro->j32_grupo == 25 ? $oRegistro->j31_codigo : $oRetorno->iNumeroFrentes    ;
      $oRetorno->iPosicaoFiscal        = $oRegistro->j32_grupo == 44 ? $oRegistro->j31_codigo : $oRetorno->iPosicaoFiscal    ;
      $oRetorno->iArea                 = $oRegistro->j32_grupo == 45 ? $oRegistro->j31_codigo : $oRetorno->iArea             ;
      $oRetorno->iTipoImovel           = $oRegistro->j32_grupo == 46 ? $oRegistro->j31_codigo : $oRetorno->iTipoImovel       ;
      $oRetorno->iIrregular            = $oRegistro->j32_grupo == 47 ? $oRegistro->j31_codigo : $oRetorno->iIrregular        ;
    }
    return $oRetorno;
  }

  /**
   * Retorna as Caracteristicas da ConstruÃ§Ã£o
   * 
   * @param mixed $iCodigoMatricula 
   * @param mixed $iIdConstrucao 
   * @static
   * @access public
   * @return stdClass
   */
  public static function getCaracteristicasConstrucao( $iCodigoMatricula, $iIdConstrucao ) {

    $oRetorno                        = new stdClass();
    $oRetorno->iUtilizacao           = "";
    $oRetorno->iTipo                 = "";
    $oRetorno->iNumeroPavimentos     = "";
    $oRetorno->iLocalizacaoUnidade   = "";
    $oRetorno->iUso                  = "";
    $oRetorno->iEstrutura            = "";
    $oRetorno->iAgua                 = "";
    $oRetorno->iEsgoto               = "";
    $oRetorno->iEnergiaEletrica      = "";
    $oRetorno->iInstalacaoSanitaria  = "";
    $oRetorno->iCobertura            = "";
    $oRetorno->iEsquadria            = "";
    $oRetorno->iPiso                 = "";
    $oRetorno->iRevestimentoExterno  = "";
    $oRetorno->iPadraoConstrutivo    = "";
    $oRetorno->iConservacao          = "";
    $oRetorno->iPosicaoFical         = "";

    $sSqlSituacaoFiscal              = "select *                                              \n";
    $sSqlSituacaoFiscal             .= "  from carconstr                                      \n";
    $sSqlSituacaoFiscal             .= "       inner join caracter on j31_codigo = j48_caract \n";
    $sSqlSituacaoFiscal             .= "       inner join cargrup  on j32_grupo  = j31_grupo  \n";
    $sSqlSituacaoFiscal             .= " where j48_matric = $iCodigoMatricula                 \n";
    $sSqlSituacaoFiscal             .= "   and j48_idcons = $iIdConstrucao                    \n";

    $rsCaracteristicas               = pg_query(Conexao::getInstancia()->getConexao(), $sSqlSituacaoFiscal);

    if ( !$rsCaracteristicas ) {
      throw new Exception('Erro ao Buscar carateristicas da Construcao: ' . pg_last_error() );
    }

    foreach ( db_utils::getCollectionByRecord($rsCaracteristicas) as $oRegistro ) {

      $oRetorno->iUtilizacao          = $oRegistro->j32_grupo == 30 ? $oRegistro->j31_codigo : $oRetorno->iUtilizacao;
      $oRetorno->iTipo                = $oRegistro->j32_grupo == 31 ? $oRegistro->j31_codigo : $oRetorno->iTipo;
      $oRetorno->iNumeroPavimentos    = $oRegistro->j32_grupo == 32 ? $oRegistro->j31_codigo : $oRetorno->iNumeroPavimentos;
      $oRetorno->iLocalizacaoUnidade  = $oRegistro->j32_grupo == 33 ? $oRegistro->j31_codigo : $oRetorno->iLocalizacaoUnidade;
      $oRetorno->iUso                 = $oRegistro->j32_grupo == 34 ? $oRegistro->j31_codigo : $oRetorno->iUso;
      $oRetorno->iEstrutura           = $oRegistro->j32_grupo == 35 ? $oRegistro->j31_codigo : $oRetorno->iEstrutura;
      $oRetorno->iAgua                = $oRegistro->j32_grupo == 36 ? $oRegistro->j31_codigo : $oRetorno->iAgua;
      $oRetorno->iEsgoto              = $oRegistro->j32_grupo == 37 ? $oRegistro->j31_codigo : $oRetorno->iEsgoto;
      $oRetorno->iEnergiaEletrica     = $oRegistro->j32_grupo == 38 ? $oRegistro->j31_codigo : $oRetorno->iEnergiaEletrica;
      $oRetorno->iInstalacaoSanitaria = $oRegistro->j32_grupo == 39 ? $oRegistro->j31_codigo : $oRetorno->iInstalacaoSanitaria;
      $oRetorno->iCobertura           = $oRegistro->j32_grupo == 40 ? $oRegistro->j31_codigo : $oRetorno->iCobertura;
      $oRetorno->iEsquadria           = $oRegistro->j32_grupo == 41 ? $oRegistro->j31_codigo : $oRetorno->iEsquadria;
      $oRetorno->iPiso                = $oRegistro->j32_grupo == 42 ? $oRegistro->j31_codigo : $oRetorno->iPiso;
      $oRetorno->iRevestimentoExterno = $oRegistro->j32_grupo == 43 ? $oRegistro->j31_codigo : $oRetorno->iRevestimentoExterno;
      $oRetorno->iPadraoConstrutivo   = $oRegistro->j32_grupo == 48 ? $oRegistro->j31_codigo : $oRetorno->iPadraoConstrutivo;
      $oRetorno->iConservacao         = $oRegistro->j32_grupo == 49 ? $oRegistro->j31_codigo : $oRetorno->iConservacao;
      $oRetorno->iPosicaoFical        = $oRegistro->j32_grupo == 50 ? $oRegistro->j31_codigo : $oRetorno->iPosicaoFical;

    }   
    return $oRetorno;
  }    

  /**
   * Retorna os Dados Atuais do Imovel para que posssam ser comparados para a importacao
   * 
   * @param  integer $iCodigoMatricula 
   * @param  integer $iCodigoConstrucao 
   * @static
   * @access public
   * @return stdClass
   */
  public static function getDadosAtuaisImovel($iCodigoMatricula, $iCodigoConstrucao) {

    $sSql  = "select proprietario.j34_setor        as setor_cartografico,                                        \n";
    $sSql .= "       proprietario.j34_quadra       as quadra_cartografica,                                       \n";
    $sSql .= "       proprietario.j34_lote         as lote_cartografico,                                         \n";
    $sSql .= "       ''                            as unidade_imobiliaria,                                       \n";
    $sSql .= "       proprietario.z01_nomecompleto as nome_proprietario,                                         \n";
    $sSql .= "       case char_length(proprietario.z01_cgccpf)                                                   \n";
    $sSql .= "         when 11                                                                                   \n";
    $sSql .= "         then cast( proprietario.z01_cgccpf as varchar )                                           \n";
    $sSql .= "         else ''                                                                                   \n";
    $sSql .= "       end                           as cpf_proprietario,                                          \n";
    $sSql .= "       case char_length(proprietario.z01_cgccpf)                                                   \n";
    $sSql .= "         when 14                                                                                   \n";
    $sSql .= "         then cast( proprietario.z01_cgccpf as varchar )                                           \n";
    $sSql .= "         else ''                                                                                   \n";
    $sSql .= "       end                           as cnpj_proprietario,                                         \n";
    $sSql .= "       cgm_proprietario.z01_telef    as telefone_proprietario,                                     \n";
    $sSql .= "       testpri.j49_codigo            as codigo_logradouro_lote,                                    \n";
    $sSql .= "       coalesce(testadanumero.j15_numero,0)  as numero_logradouro_lote,                            \n";
    $sSql .= "       testada.j36_testad            as valor_testada_principal,                                   \n";
    $sSql .= "       lote.j34_area                 as area_terreno,                                              \n";
    $sSql .= "       /* Inicio caracteristicas do lote */                                                        \n";
    $sSql .= "       coalesce((select j35_caract                                                                 \n";
    $sSql .= "          from carlote                                                                             \n";
    $sSql .= "               inner join caracter on caracter.j31_codigo = carlote.j35_caract                     \n";
    $sSql .= "               inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo                     \n";
    $sSql .= "         where caracter.j31_grupo = 20                                                             \n";
    $sSql .= "           and carlote.j35_idbql  = iptubase.j01_idbql                                             \n";
    $sSql .= "                           ), '0' ) as caracteristica_lote_propriedade,                            \n";
    $sSql .= "       coalesce((select j35_caract                                                                 \n";
    $sSql .= "          from carlote                                                                             \n";
    $sSql .= "               inner join caracter on caracter.j31_codigo = carlote.j35_caract                     \n";
    $sSql .= "               inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo                     \n";
    $sSql .= "         where caracter.j31_grupo = 21                                                             \n";
    $sSql .= "           and carlote.j35_idbql  = iptubase.j01_idbql                                             \n";
    $sSql .= "                           ), '0' ) as caracteristica_lote_situacao,                               \n";
    $sSql .= "       coalesce((select j35_caract                                                                 \n";
    $sSql .= "          from carlote                                                                             \n";
    $sSql .= "               inner join caracter on caracter.j31_codigo = carlote.j35_caract                     \n";
    $sSql .= "               inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo                     \n";
    $sSql .= "         where caracter.j31_grupo = 22                                                             \n";
    $sSql .= "           and carlote.j35_idbql  = iptubase.j01_idbql                                             \n";
    $sSql .= "                           ), '0' ) as  caracteristica_lote_caracteristica,                        \n";
    $sSql .= "       coalesce((select j35_caract                                                                 \n";
    $sSql .= "          from carlote                                                                             \n";
    $sSql .= "               inner join caracter on caracter.j31_codigo = carlote.j35_caract                     \n";
    $sSql .= "               inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo                     \n";
    $sSql .= "         where caracter.j31_grupo = 23                                                             \n";
    $sSql .= "           and carlote.j35_idbql  = iptubase.j01_idbql                                             \n";
    $sSql .= "                           ), '0' ) as  caracteristica_lote_nivel,                                 \n";
    $sSql .= "       coalesce((select j35_caract                                                                 \n";
    $sSql .= "          from carlote                                                                             \n";
    $sSql .= "               inner join caracter on caracter.j31_codigo = carlote.j35_caract                     \n";
    $sSql .= "               inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo                     \n";
    $sSql .= "         where caracter.j31_grupo = 25                                                             \n";
    $sSql .= "           and carlote.j35_idbql  = iptubase.j01_idbql                                             \n";
    $sSql .= "                           ), '0' ) as  caracteristica_lote_numero_frentes,                        \n";
    $sSql .= "       coalesce((select j35_caract                                                                 \n";
    $sSql .= "          from carlote                                                                             \n";
    $sSql .= "               inner join caracter on caracter.j31_codigo = carlote.j35_caract                     \n";
    $sSql .= "               inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo                     \n";
    $sSql .= "         where caracter.j31_grupo = 24                                                             \n";
    $sSql .= "           and carlote.j35_idbql  = iptubase.j01_idbql                                             \n";
    $sSql .= "                           ), '0' ) as  caracteristica_lote_ocupacao,                              \n";
    $sSql .= "       coalesce((select j48_caract                                                                 \n";
    $sSql .= "          from carconstr                                                                           \n";
    $sSql .= "               inner join caracter on caracter.j31_codigo = carconstr.j48_caract                   \n";
    $sSql .= "               inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo                     \n";
    $sSql .= "         where caracter.j31_grupo   = 30                                                           \n";
    $sSql .= "           and carconstr.j48_matric = iptubase.j01_matric                                          \n";
    $sSql .= "           and carconstr.j48_idcons = iptuconstr.j39_idcons                                        \n";
    $sSql .= "                           ), '0' ) as  caracteristica_construcao_utilizacao,                      \n";
    $sSql .= "/**********  Caracteristicas da Construcao  ******************/                                    \n";
    $sSql .= "       coalesce((select j48_caract                                                                 \n";
    $sSql .= "          from carconstr                                                                           \n";
    $sSql .= "               inner join caracter on caracter.j31_codigo = carconstr.j48_caract                   \n";
    $sSql .= "               inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo                     \n";
    $sSql .= "         where caracter.j31_grupo   = 32                                                           \n";
    $sSql .= "           and carconstr.j48_matric = iptubase.j01_matric                                          \n";
    $sSql .= "           and carconstr.j48_idcons = iptuconstr.j39_idcons                                        \n";
    $sSql .= "                           ), '0' ) as  caracteristica_construcao_numero_pavimentos,               \n";
    $sSql .= "       coalesce((select j48_caract                                                                 \n";
    $sSql .= "          from carconstr                                                                           \n";
    $sSql .= "               inner join caracter on caracter.j31_codigo = carconstr.j48_caract                   \n";
    $sSql .= "               inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo                     \n";
    $sSql .= "         where caracter.j31_grupo   = 33                                                           \n";
    $sSql .= "           and carconstr.j48_matric = iptubase.j01_matric                                          \n";
    $sSql .= "           and carconstr.j48_idcons = iptuconstr.j39_idcons                                        \n";
    $sSql .= "                           ), '0' ) as  caracteristica_construcao_localizacao_unidade,             \n";
    $sSql .= "       coalesce((select j48_caract                                                                 \n";
    $sSql .= "          from carconstr                                                                           \n";
    $sSql .= "               inner join caracter on caracter.j31_codigo = carconstr.j48_caract                   \n";
    $sSql .= "               inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo                     \n";
    $sSql .= "         where caracter.j31_grupo   = 31                                                           \n";
    $sSql .= "           and carconstr.j48_matric = iptubase.j01_matric                                          \n";
    $sSql .= "           and carconstr.j48_idcons = iptuconstr.j39_idcons                                        \n";
    $sSql .= "                           ), '0' ) as  caracteristica_construcao_tipo,                            \n";
    $sSql .= "       coalesce((select j48_caract                                                                 \n";
    $sSql .= "          from carconstr                                                                           \n";
    $sSql .= "               inner join caracter on caracter.j31_codigo = carconstr.j48_caract                   \n";
    $sSql .= "               inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo                     \n";
    $sSql .= "         where caracter.j31_grupo   = 48                                                           \n";
    $sSql .= "           and carconstr.j48_matric = iptubase.j01_matric                                          \n";
    $sSql .= "           and carconstr.j48_idcons = iptuconstr.j39_idcons                                        \n";
    $sSql .= "                           ), '0' ) as  caracteristica_construcao_padrao_construtivo,              \n";
    $sSql .= "       coalesce((select j48_caract                                                                 \n";
    $sSql .= "          from carconstr                                                                           \n";
    $sSql .= "               inner join caracter on caracter.j31_codigo = carconstr.j48_caract                   \n";
    $sSql .= "               inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo                     \n";
    $sSql .= "         where caracter.j31_grupo   = 49                                                           \n";
    $sSql .= "           and carconstr.j48_matric = iptubase.j01_matric                                          \n";
    $sSql .= "           and carconstr.j48_idcons = iptuconstr.j39_idcons                                        \n";
    $sSql .= "                           ), '0' ) as  caracteristica_construcao_conservacao,                     \n";
    $sSql .= "       coalesce((select j48_caract                                                                 \n";
    $sSql .= "          from carconstr                                                                           \n";
    $sSql .= "               inner join caracter on caracter.j31_codigo = carconstr.j48_caract                   \n";
    $sSql .= "               inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo                     \n";
    $sSql .= "         where caracter.j31_grupo   = 34                                                           \n";
    $sSql .= "           and carconstr.j48_matric = iptubase.j01_matric                                          \n";
    $sSql .= "           and carconstr.j48_idcons = iptuconstr.j39_idcons                                        \n";
    $sSql .= "                           ), '0' ) as  caracteristica_construcao_uso,                             \n";
    $sSql .= "       coalesce((select j48_caract                                                                 \n";
    $sSql .= "          from carconstr                                                                           \n";
    $sSql .= "               inner join caracter on caracter.j31_codigo = carconstr.j48_caract                   \n";
    $sSql .= "               inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo                     \n";
    $sSql .= "         where caracter.j31_grupo   = 35                                                           \n";
    $sSql .= "           and carconstr.j48_matric = iptubase.j01_matric                                          \n";
    $sSql .= "           and carconstr.j48_idcons = iptuconstr.j39_idcons                                        \n";
    $sSql .= "                           ), '0' ) as  caracteristica_construcao_estrutura,                       \n";
    $sSql .= "       coalesce((select j48_caract                                                                 \n";
    $sSql .= "          from carconstr                                                                           \n";
    $sSql .= "               inner join caracter on caracter.j31_codigo = carconstr.j48_caract                   \n";
    $sSql .= "               inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo                     \n";
    $sSql .= "         where caracter.j31_grupo   = 36                                                           \n";
    $sSql .= "           and carconstr.j48_matric = iptubase.j01_matric                                          \n";
    $sSql .= "           and carconstr.j48_idcons = iptuconstr.j39_idcons                                        \n";
    $sSql .= "                           ), '0' ) as  caracteristica_construcao_agua,                            \n";
    $sSql .= "       coalesce((select j48_caract                                                                 \n";
    $sSql .= "          from carconstr                                                                           \n";
    $sSql .= "               inner join caracter on caracter.j31_codigo = carconstr.j48_caract                   \n";
    $sSql .= "               inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo                     \n";
    $sSql .= "         where caracter.j31_grupo   = 37                                                           \n";
    $sSql .= "           and carconstr.j48_matric = iptubase.j01_matric                                          \n";
    $sSql .= "           and carconstr.j48_idcons = iptuconstr.j39_idcons                                        \n";
    $sSql .= "                           ), '0' ) as  caracteristica_construcao_esgoto,                          \n";
    $sSql .= "       coalesce((select j48_caract                                                                 \n";
    $sSql .= "          from carconstr                                                                           \n";
    $sSql .= "               inner join caracter on caracter.j31_codigo = carconstr.j48_caract                   \n";
    $sSql .= "               inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo                     \n";
    $sSql .= "         where caracter.j31_grupo   = 38                                                           \n";
    $sSql .= "           and carconstr.j48_matric = iptubase.j01_matric                                          \n";
    $sSql .= "           and carconstr.j48_idcons = iptuconstr.j39_idcons                                        \n";
    $sSql .= "                           ), '0' ) as  caracteristica_construcao_energia_eletrica,                \n";
    $sSql .= "       coalesce((select j48_caract                                                                 \n";
    $sSql .= "          from carconstr                                                                           \n";
    $sSql .= "               inner join caracter on caracter.j31_codigo = carconstr.j48_caract                   \n";
    $sSql .= "               inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo                     \n";
    $sSql .= "         where caracter.j31_grupo   = 39                                                           \n";
    $sSql .= "           and carconstr.j48_matric = iptubase.j01_matric                                          \n";
    $sSql .= "           and carconstr.j48_idcons = iptuconstr.j39_idcons                                        \n";
    $sSql .= "                           ), '0'  ) as caracteristica_construcao_instalacao_sanitaria,            \n";
    $sSql .= "       coalesce((select j48_caract                                                                 \n";
    $sSql .= "          from carconstr                                                                           \n";
    $sSql .= "               inner join caracter on caracter.j31_codigo = carconstr.j48_caract                   \n";
    $sSql .= "               inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo                     \n";
    $sSql .= "         where caracter.j31_grupo   = 40                                                           \n";
    $sSql .= "           and carconstr.j48_matric = iptubase.j01_matric                                          \n";
    $sSql .= "           and carconstr.j48_idcons = iptuconstr.j39_idcons                                        \n";
    $sSql .= "                           ), '0'  ) as caracteristica_construcao_cobertura,                       \n";
    $sSql .= "       coalesce((select j48_caract                                                                 \n";
    $sSql .= "          from carconstr                                                                           \n";
    $sSql .= "               inner join caracter on caracter.j31_codigo = carconstr.j48_caract                   \n";
    $sSql .= "               inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo                     \n";
    $sSql .= "         where caracter.j31_grupo   = 41                                                           \n";
    $sSql .= "           and carconstr.j48_matric = iptubase.j01_matric                                          \n";
    $sSql .= "           and carconstr.j48_idcons = iptuconstr.j39_idcons                                        \n";
    $sSql .= "                           ), '0'  ) as caracteristica_construcao_esquadria,                       \n";
    $sSql .= "       coalesce((select j48_caract                                                                 \n";
    $sSql .= "          from carconstr                                                                           \n";
    $sSql .= "               inner join caracter on caracter.j31_codigo = carconstr.j48_caract                   \n";
    $sSql .= "               inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo                     \n";
    $sSql .= "         where caracter.j31_grupo   = 42                                                           \n";
    $sSql .= "           and carconstr.j48_matric = iptubase.j01_matric                                          \n";
    $sSql .= "           and carconstr.j48_idcons = iptuconstr.j39_idcons                                        \n";
    $sSql .= "                           ), '0'  ) as caracteristica_construcao_piso,                            \n";
    $sSql .= "       coalesce((select j48_caract                                                                 \n";
    $sSql .= "          from carconstr                                                                           \n";
    $sSql .= "               inner join caracter on caracter.j31_codigo = carconstr.j48_caract                   \n";
    $sSql .= "               inner join cargrup  on cargrup.j32_grupo   = caracter.j31_grupo                     \n";
    $sSql .= "         where caracter.j31_grupo   = 43                                                           \n";
    $sSql .= "           and carconstr.j48_matric = iptubase.j01_matric                                          \n";
    $sSql .= "           and carconstr.j48_idcons = iptuconstr.j39_idcons                                        \n";
    $sSql .= "                           ), '0'  )      as caracteristica_construcao_revestimento_externo,       \n";
    $sSql .= "       proprietario.j05_codigoproprio     as setor_localizacao, /** Planta de Localizacao   */     \n";
    $sSql .= "       proprietario.j06_quadraloc         as quadra_localizacao,                                   \n";
    $sSql .= "       lpad(proprietario.j06_lote,10,0)   as lote_localizacao,                                     \n";
    $sSql .= "       ''                                 as cnpj_mobiliario,                                      \n";
    $sSql .= "       ''                                 as codigo_atividade_mobiliario,                          \n";
    $sSql .= "       ''                                 as cnae_mobiliario,                                      \n";
    $sSql .= "       ''                                 as inscricao_municipal,                                  \n";
    $sSql .= "       ''                                 as razao_socila,                                         \n";
    $sSql .= "       ''                                 as observacao,                                           \n";
    $sSql .= "       ''                                 as distrito,                                             \n";
    $sSql .= "       lote.j34_bairro                    as bairro,                                               \n";
    $sSql .= "       coalesce(iptuconstr.j39_area, '0') as areaconstruida                                        \n";
    $sSql .= "  from iptubase                                                                                    \n";
    $sSql .= "       inner join lote                    on lote.j34_idbql              = iptubase.j01_idbql      \n";
    $sSql .= "       inner join proprietario            on iptubase.j01_matric         = proprietario.j01_matric \n";
    $sSql .= "       inner join cgm as cgm_proprietario on cgm_proprietario.z01_numcgm = proprietario.z01_numcgm \n";
    $sSql .= "       inner join testada                 on testada.j36_idbql           = iptubase.j01_idbql      \n";
    $sSql .= "       inner join testpri                 on testpri.j49_idbql           = testada.j36_idbql       \n";
    $sSql .= "                                         and testpri.j49_face            = testada.j36_face        \n";
    $sSql .= "       left  join testadanumero           on testadanumero.j15_idbql     = testada.j36_idbql       \n";
    $sSql .= "                                         and testadanumero.j15_face      = testada.j36_face        \n";
    $sSql .= "       left  join iptuconstr              on iptuconstr.j39_matric       = iptubase.j01_matric     \n";
    $sSql .= " where iptubase.j01_matric   = {$iCodigoMatricula}                                                 \n";
    if ( !empty($iCodigoConstrucao) ) {
      $sSql .= "   and iptuconstr.j39_idcons = {$iCodigoConstrucao}                                              \n";
    }
    $rsSql = pg_query(Conexao::getInstancia()->getConexao(), $sSql);

    if ( !$rsSql ) {
      throw new Exception( "Erro ao Buscar os Dados do Imóvel e Construção: " . pg_last_error() );      
    }

    if ( pg_num_rows($rsSql) == 0 ) {
      return false;
    }
    return db_utils::fieldsMemory($rsSql, 0);    
  }

  /**
   * Altera os Dados de uma tabela
   * @param string   $sTabela
   * @param stdClass $oCampos
   * @param string   $sWhere
   */
  public static function alterar($sTabela, $oCampos, $sWhere = null) {
   
    $oConexao = Conexao::getInstancia();
    
    $aCamposRelacionados = array();
   
    foreach ( $oCampos as $sCampo => $sValor) {
      $aCamposRelacionados[] = " {$sCampo} = '{$sValor}'"; 
    }
    
    $sSql = "update $sTabela                              \n"; 
    $sSql.= "   set". implode(", \n", $aCamposRelacionados);
    if ( !empty($sWhere) ) {
      $sSql.= " where  $sWhere                            \n";
    }
   
    return $oConexao->query($sSql);
  } 
 
  /**
   * Remove os dados de uma tabela
   */
  public static function excluir($sTabela, $sUsing, $sWhere) {
    
    $oConexao = Conexao::getInstancia();

    $sSql = "delete            \n";
    $sSql.= "  from {$sTabela} \n";
    
    if ( !empty($sUsing) ) {
      $sSql.= " using {$sUsing} \n";
    }
    
    if ( !empty($sWhere) ) {
      $sSql.= " where {$sWhere} \n";
    }
    
    return $oConexao->query($sSql);
  }
}
