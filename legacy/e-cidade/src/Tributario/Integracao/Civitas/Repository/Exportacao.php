<?php
/**
 *     E-cidade Software protectedo para Gestao Municipal
 *  Copyright (C) 2017  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca protecteda Geral GNU, conforme
 *  protectedada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca protecteda Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca protecteda Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Tributario\Integracao\Civitas\Repository;

use \stdClass;
use \db_utils;
use \DBDate;
use \cl_iptubase;
use \cl_lote;
use \cl_cargrup;
use \cl_iptuconstrhabite;
use \Lote;
use \Imovel;
use \Exception;

final class Exportacao
{
    public static function getDados(DBDate $oData)
    {
        $sData = $oData->getDate();

        $oDaoCargrup = new cl_cargrup;

        $rsCargrup = $oDaoCargrup->sql_record($oDaoCargrup->sql_query_file(null, "*", "j32_grupo"));
        $aCaracteristicasDisponiveis = db_utils::getCollectionByRecord($rsCargrup);

        $oDaoLote = new cl_lote;

        $sSql  = " select distinct j01_idbql                                                                                     ";
        $sSql .= "   from iptubase                                                                                               ";
        $sSql .= "        inner join lote on j34_idbql = j01_idbql                                                               ";
        $sSql .= "        inner join bairro on j34_bairro = j13_codi                                                             ";
        $sSql .= "        inner join testada on j36_idbql = j34_idbql                                                            ";
        $sSql .= "        inner join testpri on j49_idbql = j36_idbql                                                            ";
        $sSql .= "                          and j49_face = j36_face                                                              ";
        $sSql .= "        inner join ruas on j14_codigo = j49_codigo                                                             ";
        $sSql .= "        inner join histocorrenciamatric on histocorrenciamatric.ar25_matric = iptubase.j01_matric              ";
        $sSql .= "        inner join histocorrencia on histocorrencia.ar23_sequencial = histocorrenciamatric.ar25_histocorrencia ";
        $sSql .= "                                 and histocorrencia.ar23_data = '{$sData}'                                     ";
        $sSql .= "  where j01_baixa is null                                                                                      ";

        $rsSql = $oDaoLote->sql_record($sSql);

        if (empty($rsSql)) {
            throw new Exception("Não foram encontradas atualizações na data informada.");
        }

        $aGeodados = db_utils::getCollectionByRecord($rsSql);

        $aDados = array();

        foreach ($aGeodados as $oGeodados) {

            $aLinha = array();

            $oLote = new Lote($oGeodados->j01_idbql);

            $aLinha["codigo_setor"]              = $oLote->getCodigoSetor();
            $aLinha["codigo_quadra"]             = $oLote->getQuadra();
            $aLinha["codigo_lote"]               = $oLote->getLote();
            $aLinha["rua_codigo"]                = $oLote->getCodigoLogradouro();
            $aLinha["rua_nome"]                  = $oLote->getLogradouro();
            $aLinha["bairro_codigo"]             = $oLote->getCodigoBairro();
            $aLinha["bairro_descricao"]          = $oLote->getBairro();
            $aLinha["rua_cep"]                   = $oLote->getCep();
            $aLinha["lote_codigo_loteamento"]    = $oLote->getCodigoLoteamento();
            $aLinha["lote_descricao_loteamento"] = $oLote->getDescricaoLoteamento();
            $aLinha["lote_area"]                 = $oLote->getAreaLote();
            $aLinha["valor_testada_lote"]        = $oLote->getValorTestadaLote();
            $aLinha["rua_tipo_testada"]          = $oLote->getCodigoTipoLogradouro();
            $aLinha["rua_tipo_sigla_testada"]    = $oLote->getSiglaTipoLogradouro();

            $aCaracteristicasFaceLote = array();
            $aCaracteristicasLote     = array();

            foreach ($oLote->getCaracteristicasFace() as $oCaracteristicaFace) {
                $aCaracteristicasFaceLote[$oCaracteristicaFace->iCodigoGrupo] = $oCaracteristicaFace;
            }

            foreach ($oLote->getCaracteristicasLote() as $oCaracteristicaLote) {
                $aCaracteristicasLote[$oCaracteristicaLote->iCodigoGrupo] = $oCaracteristicaLote;
            }

            $iFace = 1;
            $iLote = 1;
            $iConstrucao = 1;

            foreach ($aCaracteristicasDisponiveis as $oCaracteristicaDisponivel) {

                if($oCaracteristicaDisponivel->j32_tipo == 'F') {

                    $sCaracteristicaFaceTipo    = "caracteristicas_face_tipo_"     . $iFace;
                    $iCaracteristicaFaceIdGrupo = "caracteristicas_face_id_grupo_" . $iFace;
                    $sCaracteristicaFaceGrupo   = "caracteristicas_face_grupo_"    . $iFace;

                    $aLinha[$sCaracteristicaFaceTipo]    = $oCaracteristicaDisponivel->j32_tipo ;
                    $aLinha[$iCaracteristicaFaceIdGrupo] = $oCaracteristicaDisponivel->j32_grupo;
                    $aLinha[$sCaracteristicaFaceGrupo]   = $oCaracteristicaDisponivel->j32_descr;

                    $iCaracteristicaFaceId     = "caracteristicas_face_id_"        . $iFace;
                    $sCaracteristicaFaceCar    = "caracteristicas_face_descricao_" . $iFace;
                    $iCaracteristicaFacePontos = "caracteristicas_face_pontos_"    . $iFace;

                    if (isset($aCaracteristicasFaceLote[$oCaracteristicaDisponivel->j32_grupo])) {

                        $aLinha[$iCaracteristicaFaceId]     = $aCaracteristicasFaceLote[$oCaracteristicaDisponivel->j32_grupo]->iCodigoCaracteristica;
                        $aLinha[$sCaracteristicaFaceCar]    = $aCaracteristicasFaceLote[$oCaracteristicaDisponivel->j32_grupo]->sCaracteristica;
                        $aLinha[$iCaracteristicaFacePontos] = $aCaracteristicasFaceLote[$oCaracteristicaDisponivel->j32_grupo]->iNumeroPontos;

                    } else {

                        $aLinha[$iCaracteristicaFaceId]     = "";
                        $aLinha[$sCaracteristicaFaceCar]    = "";
                        $aLinha[$iCaracteristicaFacePontos] = "";
                    }

                    $iFace++;

                } else if ($oCaracteristicaDisponivel->j32_tipo == 'L') {

                    $sCaracteristicaLoteTipo    = "lote_tipo_"     . $iLote;
                    $iCaracteristicaLoteIdGrupo = "lote_id_grupo_" . $iLote;
                    $sCaracteristicaLoteGrupo   = "lote_grupo_"    . $iLote;

                    $aLinha[$sCaracteristicaLoteTipo]    = $oCaracteristicaDisponivel->j32_tipo ;
                    $aLinha[$iCaracteristicaLoteIdGrupo] = $oCaracteristicaDisponivel->j32_grupo;
                    $aLinha[$sCaracteristicaLoteGrupo]   = $oCaracteristicaDisponivel->j32_descr;

                    $iCaracteristicaLoteId     = "lote_id_"        . $iLote;
                    $sCaracteristicaLoteCar    = "lote_descricao_" . $iLote;
                    $iCaracteristicaLotePontos = "lote_pontos_"    . $iLote;

                    if (isset($aCaracteristicasLote[$oCaracteristicaDisponivel->j32_grupo])) {

                        $aLinha[$iCaracteristicaLoteId]     = $aCaracteristicasLote[$oCaracteristicaDisponivel->j32_grupo]->iCodigoCaracteristica;
                        $aLinha[$sCaracteristicaLoteCar]    = $aCaracteristicasLote[$oCaracteristicaDisponivel->j32_grupo]->sCaracteristica;
                        $aLinha[$iCaracteristicaLotePontos] = $aCaracteristicasLote[$oCaracteristicaDisponivel->j32_grupo]->iNumeroPontos;

                    } else {

                        $aLinha[$iCaracteristicaLoteId]     = '';
                        $aLinha[$sCaracteristicaLoteCar]    = '';
                        $aLinha[$iCaracteristicaLotePontos] = '';
                    }

                    $iLote++;

                } else if($oCaracteristicaDisponivel->j32_tipo == 'C') {

                    $sCaracteristicaConstrucaoTipo    = "construcao_tipo_"      . $iConstrucao;
                    $iCaracteristicaConstrucaoIdGrupo = "construcao_id_grupo_"  . $iConstrucao;
                    $sCaracteristicaConstrucaoGrupo   = "construcao_grupo_"     . $iConstrucao;
                    $iCaracteristicaConstrucaoId      = "construcao_id_"        . $iConstrucao;
                    $sCaracteristicaConstrucaoCar     = "construcao_descricao_" . $iConstrucao;
                    $iCaracteristicaConstrucaoPontos  = "construcao_pontos_"    . $iConstrucao;

                    $aLinha[$sCaracteristicaConstrucaoTipo]    = '';
                    $aLinha[$iCaracteristicaConstrucaoIdGrupo] = '';
                    $aLinha[$sCaracteristicaConstrucaoGrupo]   = '';
                    $aLinha[$iCaracteristicaConstrucaoId]      = '';
                    $aLinha[$sCaracteristicaConstrucaoCar]     = '';
                    $aLinha[$iCaracteristicaConstrucaoPontos]  = '';

                    $iConstrucao++;
                }
            }

            $oDaoIptubase = new cl_iptubase;

            $rsIptubase = $oDaoIptubase->sql_record($oDaoIptubase->sql_query_file(null,
                                                                                  "j01_matric",
                                                                                  "j01_matric",
                                                                                  " exists (select 1
                                                                                             from histocorrenciamatric
                                                                                                  inner join histocorrencia on histocorrencia.ar23_sequencial = histocorrenciamatric.ar25_histocorrencia
                                                                                            where histocorrenciamatric.ar25_matric = iptubase.j01_matric
                                                                                              and histocorrencia.ar23_data = '{$sData}' )
                                                                                   and j01_idbql = ".$oLote->getCodigoLote()."
                                                                                   and j01_baixa is null "));

            if (!$rsIptubase || $oDaoIptubase->numrows == 0) {
              throw new Exception('Erro ao consultar matriculas do lote.');
            }

            $aImoveis = array();

            $aMatriculas = db_utils::getCollectionByRecord($rsIptubase);

            foreach ($aMatriculas as $oMatricula) {

                $oImovel = new Imovel($oMatricula->j01_matric);

                $aLinha["matricula"] = $oImovel->getMatricula();

                $sSql  = " select 1 as iptu_calculo,                                                            ";
                $sSql .= "        (select 1 from arrecad where k00_numpre = j20_numpre limit 1) as iptu_aberto, ";
                $sSql .= "        (select 1 from arrepaga where k00_numpre = j20_numpre limit 1) as iptu_pago   ";
                $sSql .= "   from iptunump                                                                      ";
                $sSql .= "  where j20_matric = ".$aLinha["matricula"]."                                         ";
                $sSql .= "    and j20_anousu = ".date("Y", db_getsession("DB_datausu"))."                       ";

                $rsCalcIptu = db_query($sSql);

                if (empty($rsCalcIptu)) {
                    throw new Exception("Não foi possível consultar a situação do IPTU para a matricula {$aLinha["matricula"]}.");
                }

                $oCalcIptu = db_utils::fieldsMemory($rsCalcIptu, 0);

                $sSituacaoIptu = "Sem Calculo";

                if (!empty($oCalcIptu->iptu_aberto)) {
                    $sSituacaoIptu = "Em aberto";
                } else if (empty($oCalcIptu->iptu_aberto) and !empty($oCalcIptu->iptu_calculo) and !empty($oCalcIptu->iptu_pago)) {
                    $sSituacaoIptu = "Quitado";
                }

                $aLinha["situacao_iptu"] = $sSituacaoIptu;

                $sIsencao  = " select case when j17_descr is not null                                                                     ";
                $sIsencao .= "                       then 'SIM'                                                                           ";
                $sIsencao .= "                       else 'NAO'                                                                           ";
                $sIsencao .= "                     end as incide_taxa,                                                                    ";
                $sIsencao .= "                     j21_valor,                                                                             ";
                $sIsencao .= "                     case when iptucalhconf.j89_codhis is not null                                          ";
                $sIsencao .= "                       then (select sum(x.j21_valor)                                                        ";
                $sIsencao .= "                               from iptucalv x                                                              ";
                $sIsencao .= "                              where x.j21_anousu = iptucalv.j21_anousu                                      ";
                $sIsencao .= "                                and x.j21_matric = iptucalv.j21_matric                                      ";
                $sIsencao .= "                                and x.j21_receit = iptucalv.j21_receit                                      ";
                $sIsencao .= "                                and x.j21_codhis = iptucalhconf.j89_codhis)                                 ";
                $sIsencao .= "                       else 0                                                                               ";
                $sIsencao .= "                     end as j21_valorisen                                                                   ";
                $sIsencao .= "                from iptucalv                                                                               ";
                $sIsencao .= "                     inner join iptucalh on iptucalh.j17_codhis = j21_codhis                                ";
                $sIsencao .= "                     left join iptucalhconf on iptucalhconf.j89_codhispai = j21_codhis                      ";
                $sIsencao .= "                     inner join tabrec on tabrec.k02_codigo = j21_receit                                    ";
                $sIsencao .= "                     left join iptucadtaxaexe on iptucadtaxaexe.j08_tabrec = j21_receit                     ";
                $sIsencao .= "                                             and iptucadtaxaexe.j08_anousu = ".db_getsession("DB_anousu")." ";
                $sIsencao .= "               where j21_matric = ".$aLinha["matricula"]."                                                  ";
                $sIsencao .= "                 and j21_anousu = ".db_getsession("DB_anousu")."                                            ";
                $sIsencao .= "                 and j17_codhis not in (select j89_codhis from iptucalhconf) and j17_codhis = 2             ";
                $sIsencao .= "               order by iptucalh.j17_codhis                                                                 ";

                $rsIsencao = db_query($sIsencao);

                if (empty($rsIsencao)) {
                    throw new Exception("Não foi possível consultar a isenção de IPTU para a matricula {$aLinha["matricula"]}.");
                }

                $oIsencao = db_utils::fieldsMemory($rsIsencao, 0);

                $sIncideTaxa = "NAO";

                if (!empty($oIsencao->incide_taxa)) {
                    $sIncideTaxa = $oIsencao->incide_taxa;
                }

                $aLinha["incide_taxa"]        = $sIncideTaxa;
                $aLinha["valor_taxa"]         = $oIsencao->j21_valor + 0;
                $aLinha["valor_isencao_taxa"] = $oIsencao->j21_valorisen + 0;

                $sAtivEconomica  = " select j01_matric,                                                                               ";
                $sAtivEconomica .= "        array_accum(q02_inscr::varchar || ';' ||                                                  ";
                $sAtivEconomica .= "                    q07_ativ::varchar || ';' ||                                                   ";
                $sAtivEconomica .= "                    case when q71_estrutural is not null                                          ";
                $sAtivEconomica .= "                      then 'CNAE'                                                                 ";
                $sAtivEconomica .= "                      else 'CBO'                                                                  ";
                $sAtivEconomica .= "                    end || ';' ||                                                                 ";
                $sAtivEconomica .= "                    coalesce(q71_estrutural, '') || ';' ||                                        ";
                $sAtivEconomica .= "                    case when rh70_estrutural is null                                             ";
                $sAtivEconomica .= "                      then ''                                                                     ";
                $sAtivEconomica .= "                      else rh70_estrutural                                                        ";
                $sAtivEconomica .= "                    end                                                                           ";
                $sAtivEconomica .= "        ) as atividades_economicas                                                                ";
                $sAtivEconomica .= "   from iptubase                                                                                  ";
                $sAtivEconomica .= "        inner join issmatric on j01_matric = q05_matric                                           ";
                $sAtivEconomica .= "        inner join issbase on q05_inscr = q02_inscr                                               ";
                $sAtivEconomica .= "        left join iptubaixa on j01_matric = j02_matric                                            ";
                $sAtivEconomica .= "        inner join tabativ on q07_inscr = q02_inscr and q07_datafi is null and q07_databx is null ";
                $sAtivEconomica .= "        inner join ativid on q07_ativ = q03_ativ                                                  ";
                $sAtivEconomica .= "        left join atividcnae on atividcnae.q74_ativid = ativid.q03_ativ                           ";
                $sAtivEconomica .= "        left join cnaeanalitica on cnaeanalitica.q72_sequencial = atividcnae.q74_cnaeanalitica    ";
                $sAtivEconomica .= "        left join cnae on cnae.q71_sequencial = cnaeanalitica.q72_cnae                            ";
                $sAtivEconomica .= "        left join atividcbo on atividcbo.q75_ativid = ativid.q03_ativ                             ";
                $sAtivEconomica .= "        left join rhcbo on rhcbo.rh70_sequencial = atividcbo.q75_rhcbo                            ";
                $sAtivEconomica .= "  where j02_matric is null and q02_dtbaix is null                                                 ";
                $sAtivEconomica .= "    and j01_matric = ".$aLinha["matricula"]."                                                     ";
                $sAtivEconomica .= "  group by j01_matric                                                                             ";
                $sAtivEconomica .= "  order by j01_matric                                                                             ";

                $rsAtividadesEconomicas = db_query($sAtivEconomica);

                if(empty($rsAtividadesEconomicas)){
                  throw new Exception("Erro");
                }

                $oAtividadesEconomicas = db_utils::fieldsMemory($rsAtividadesEconomicas, 0);

                $aLinha["atividades_economicas"] = $oAtividadesEconomicas->atividades_economicas;

                $aLinha["proprietario"] = '';

                $oCgmProprietarioPrincipal = $oImovel->getProprietarioPrincipal();

                if (!empty($oCgmProprietarioPrincipal)) {
                    $aLinha["proprietario"] = $oCgmProprietarioPrincipal->getNome();
                }

                $aLinha["promitente"] = '';

                $oCgmPromitentePrincipal = $oImovel->getPromitentePrincipal();

                if (!empty($oCgmPromitentePrincipal)) {
                    $aLinha["promitente"] = $oCgmPromitentePrincipal->getNome();
                }

                $oImovelEndereco = $oImovel->getImovelEndereco();
                $aLinha["endereco_entrega"]             = $oImovelEndereco->getEndereco();
                $aLinha["endereco_entrega_numero"]      = $oImovelEndereco->getNumero();
                $aLinha["endereco_entrega_complemento"] = $oImovelEndereco->getComplemento();
                $aLinha["endereco_entrega_bairro"]      = $oImovelEndereco->getBairro();
                $aLinha["endereco_entrega_municipio"]   = $oImovelEndereco->getMunicipio();
                $aLinha["endereco_entrega_uf"]          = $oImovelEndereco->getUf();
                $aLinha["endereco_entrega_cep"]         = $oImovelEndereco->getCep();
                $aLinha["endereco_entrega_caixapostal"] = $oImovelEndereco->getCaixaPostal();
                $aLinha["referencia_anterior"]          = $oImovel->getReferenciaAnterior();

                $oIsencao = $oImovel->getDadosIsencaoExercicio();
                $aLinha["isencao_codigo"]    = $oIsencao->iTipoIsencao;
                $aLinha["isencao_descricao"] = $oIsencao->sDescricaoIsencao;

                $oCalculo = $oImovel->getCalculo();
                if($oCalculoIptu = $oCalculo->getCalculoValorIptu()) {

                    $aLinha["valor_venal_terreno"] = $oCalculoIptu->nValorTerreno;
                    $aLinha["valor_iptu_terreno"]  = $oCalculoIptu->nValorTerreno * ($oCalculoIptu->nAliquota / 100);
                }

                $aConstrucoes = $oImovel->getConstrucoes();

                $aLinha["codigo_construcao"]         = '';
                $aLinha["construcao_numero"]         = '';
                $aLinha["construcao_complemento"]    = '';
                $aLinha["construcao_area"]           = '';
                $aLinha["construcao_ano"]            = '';
                $aLinha["construcao_data_demolicao"] = '';
                $aLinha["construcao_data_habite"]    = '';
                $aLinha["construcao_num_habite"]     = '';
                $aLinha["valor_venal_construcao"]    = '';
                $aLinha["valor_iptu_construcao"]     = '';

                if(count($aConstrucoes) > 0) {

                    foreach ($aConstrucoes as $oConstrucao) {

                        $aLinha["codigo_construcao"]         = '';
                        $aLinha["construcao_numero"]         = '';
                        $aLinha["construcao_complemento"]    = '';
                        $aLinha["construcao_area"]           = '';
                        $aLinha["construcao_ano"]            = '';
                        $aLinha["construcao_data_demolicao"] = '';
                        $aLinha["construcao_data_habite"]    = '';
                        $aLinha["construcao_num_habite"]     = '';
                        $aLinha["valor_venal_construcao"]    = '';
                        $aLinha["valor_iptu_construcao"]     = '';

                        $aLinha["codigo_construcao"]         = $oConstrucao->getCodigoConstrucao();
                        $aLinha["construcao_numero"]         = $oConstrucao->getNumeroEndereco();
                        $aLinha["construcao_complemento"]    = $oConstrucao->getComplementoEndereco();
                        $aLinha["construcao_area"]           = $oConstrucao->getArea();
                        $aLinha["construcao_ano"]            = $oConstrucao->getAnoConstrucao();
                        $aLinha["construcao_data_demolicao"] = $oConstrucao->getDataDemolicao();

                        $oHabite = $oConstrucao->getHabite();

                        $aLinha["construcao_num_habite"]  = $oHabite->ob09_habite;
                        $aLinha["construcao_data_habite"] = $oHabite->ob09_data;

                        if ($oCalculoConstrucao = $oCalculo->getCalculoConstrucao($oConstrucao->getCodigoConstrucao())) {

                            $aLinha["valor_venal_construcao"] = $oCalculoConstrucao->nValor;
                            $aLinha["valor_iptu_construcao"]  = ($oCalculoConstrucao->nValor * ($oCalculoIptu->nAliquota / 100));
                        }

                        $aCaracteristicasConstrucao = array();

                        foreach ($oConstrucao->getCaracteristicasConstrucao() as $oCaracteristicaConstrucao) {
                            $aCaracteristicasConstrucao[$oCaracteristicaConstrucao->iCodigoGrupo] = $oCaracteristicaConstrucao;
                        }

                        $iConstrucao = 1;

                        foreach ($aCaracteristicasDisponiveis as $oCaracteristicaDisponivel) {

                            if($oCaracteristicaDisponivel->j32_tipo == 'C') {

                                $sCaracteristicaConstrucaoTipo    = "construcao_tipo_"     . $iConstrucao;
                                $iCaracteristicaConstrucaoIdGrupo = "construcao_id_grupo_" . $iConstrucao;
                                $sCaracteristicaConstrucaoGrupo   = "construcao_grupo_"    . $iConstrucao;

                                $aLinha[$sCaracteristicaConstrucaoTipo]    = $oCaracteristicaDisponivel->j32_tipo ;
                                $aLinha[$iCaracteristicaConstrucaoIdGrupo] = $oCaracteristicaDisponivel->j32_grupo;
                                $aLinha[$sCaracteristicaConstrucaoGrupo]   = $oCaracteristicaDisponivel->j32_descr;

                                $iCaracteristicaConstrucaoId     = "construcao_id_"        . $iConstrucao;
                                $sCaracteristicaConstrucaoCar    = "construcao_descricao_" . $iConstrucao;
                                $iCaracteristicaConstrucaoPontos = "construcao_pontos_"    . $iConstrucao;

                                if (isset($aCaracteristicasConstrucao[$oCaracteristicaDisponivel->j32_grupo])) {

                                    $aLinha[$iCaracteristicaConstrucaoId]     = $aCaracteristicasConstrucao[$oCaracteristicaDisponivel->j32_grupo]->iCodigoCaracteristica;
                                    $aLinha[$sCaracteristicaConstrucaoCar]    = $aCaracteristicasConstrucao[$oCaracteristicaDisponivel->j32_grupo]->sCaracteristica;
                                    $aLinha[$iCaracteristicaConstrucaoPontos] = $aCaracteristicasConstrucao[$oCaracteristicaDisponivel->j32_grupo]->iNumeroPontos;

                                } else {

                                    $aLinha[$iCaracteristicaConstrucaoId]     = '';
                                    $aLinha[$sCaracteristicaConstrucaoCar]    = '';
                                    $aLinha[$iCaracteristicaConstrucaoPontos] = '';
                                }

                                $iConstrucao++;
                            }
                        }
                        $aDados[] = $aLinha;
                    }
                } else {
                    $aDados[] = $aLinha;
                }
            }

        }

        return $aDados;
    }
}
