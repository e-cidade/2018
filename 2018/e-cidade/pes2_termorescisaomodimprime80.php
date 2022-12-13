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

db_app::import('pessoal.CalculoFolhaRescisao');
db_app::import('pessoal.CalculoFolhaSalario');
db_app::import('pessoal.Servidor');  
db_app::import('pessoal.Rubrica');  
db_app::import('CgmFactory');
db_app::import('DBDate');
db_app::import('exceptions.*');

define('RESULTADO_POR_MATRICULA', 'm');
define('SELECAO_DE_MATRICULAS',   's');
define('INTERVALO_DE_MATRICULAS', 'i');
                               
$oPost                         = db_utils::postMemory($_POST);
$oDaoRescisao                  = db_utils::getDao('rescisao');
$oDaoRhdepend                  = db_utils::getDao('rhdepend');
$oDaoAgrupmanetoRubricaRubrica = db_utils::getDao('agrupamentorubricarubrica');
$oDaoRhpesrescisao             = db_utils::getDao('rhpesrescisao');
$oDaoCadferia                  = db_utils::getDao('cadferia');
$oDaoDBConfig                  = db_utils::getDao('db_config');

if ( empty($oPost->anofolha) || empty($oPost->mesfolha) ) {
  throw new Exception('Período de calculo da folha informado inválido.');
}

$aMatriculas = array();
$aWhere      = array();
$lHomolognet = false;

$sWhere         = null;
$sTipoResultado = null;
$sTipoFiltro    = null;

$iInstituicao   = db_getsession('DB_instit');
$iAnoFolha      = $oPost->anofolha;
$iMesFolha      = $oPost->mesfolha;

/**
 * Usa homolognet
 */ 
if ( !empty($oPost->homolognet) ) {
  $lHomolognet = true;
}

if ( !empty($oPost->tipores) ) {
  $sTipoResultado = $oPost->tipores;
}

if ( !empty($oPost->tipofil) ) {
  $sTipoFiltro = $oPost->tipofil;
}
if ( !empty($oPost->selregist) ) {
  $aMatriculas = $oPost->selregist;
}

/**
 * CNAE
 * Procura o codigo da atividade economica 
 */   
$sSqlCodigoAtividade = $oDaoCfpess->sql_query_file($iAnoFolha, $iMesFolha, $iInstituicao, 'r11_codaec');
$rsCodigoAtividade   = $oDaoCfpess->sql_record($sSqlCodigoAtividade);

if ( $oDaoCfpess->numrows == 0 ) {
  throw new Exception("Código de Atividade Econômica não encontrado para o período {$iMesFolha} / {$iAnoFolha}");
}

$iCodigoAtividade = db_utils::fieldsMemory($rsCodigoAtividade, 0)->r11_codaec;

/**
 * StdClass com todas as informacoes da instituicao
 */   
$oInstituicao = $oDaoDBConfig->getParametrosInstituicao($iInstituicao); 

/**
 * StdClass com as informacoes da instituicao usadas no relatorio 
 */
$oDadosInstituicao             = new StdClass();
$oDadosInstituicao->logo       = $oInstituicao->logo;
$oDadosInstituicao->sNome      = $oInstituicao->nomeinst;
$oDadosInstituicao->sEndereco  = $oInstituicao->ender.', '.$oInstituicao->numero;
$oDadosInstituicao->iCgc       = $oInstituicao->cgc;
$oDadosInstituicao->sBairro    = $oInstituicao->bairro;
$oDadosInstituicao->iCep       = trim(db_formatar($oInstituicao->cep, 'cep'));
$oDadosInstituicao->sUf        = $oInstituicao->uf;
$oDadosInstituicao->sMunicipio = $oInstituicao->munic;
$oDadosInstituicao->sTelefone  = $oInstituicao->telef;
$oDadosInstituicao->sEmail     = $oInstituicao->email;
$oDadosInstituicao->iCnae      = $iCodigoAtividade;

/**
 * Monta array com os filtros de pesquisa para pesquisar na tabela de calculo de rescisao
 */   
if ( $sTipoResultado == RESULTADO_POR_MATRICULA ) {

  if ( $sTipoFiltro == SELECAO_DE_MATRICULAS ) {

    $sMatriculas = implode("','", $aMatriculas);
    $aWhere[]    = "rh01_regist in ('{$sMatriculas}')";
  } 

  if ( $sTipoFiltro == INTERVALO_DE_MATRICULAS ) {

    /**
     * Primeira matricula
     */   
    if ( !empty($oPost->registro1) ) {
      $aWhere[] = 'rh01_regist >= ' . $oPost->registro1;
    }

    /**
     * Ultima matricula
     */   
    if ( !empty($oPost->registro2) ) {
      $aWhere[] = 'rh01_regist <= ' . $oPost->registro2;
    }
  }
} 

$aWhere[] = 'gerfres.r20_anousu = ' . $iAnoFolha;
$aWhere[] = 'gerfres.r20_mesusu = ' . $iMesFolha;
$aWhere[] = 'gerfres.r20_instit = ' . $iInstituicao;
$sWhere   = implode(' and ', $aWhere);

$sSqlRescisao    = $oDaoRescisao->sql_query_termoRescisao($iInstituicao, $sWhere);
$rsRescisao      = $oDaoRescisao->sql_record($sSqlRescisao);
$iTotalRescisoes = $oDaoRescisao->numrows;

if ( $iTotalRescisoes == 0 ) {
  throw new Exception("Não existe rescisões para os funcionarios escolhidos no período de {$iMesFolha} / {$iAnoFolha}");
}

$oPdf = new scpdf();
$oPdf->Open();

for ( $iIndice = 0; $iIndice < $iTotalRescisoes; $iIndice++ ) {
                  
  $aAnexos = array();
  $sNomeMae             = "";
  $nRemuneracaoAnterior = "";
  $oServidorRelatorio   = new StdClass();
  $oDadosServidor       = db_utils::fieldsMemory($rsRescisao, $iIndice);

  /**
   * Busca mes anterior a rescisao
   */   
  $tPeriodoAnterior  = strtotime('-1 month', strtotime($oDadosServidor->rh05_recis));
  $iMesFolhaAnterior = date('m', $tPeriodoAnterior);
  $iAnoFolhaAnterior = date('Y', $tPeriodoAnterior);

  /**
   * ---------------------------------------------
   * Inicio das movimentções
   */
  
  $oServidor             = new Servidor($oDadosServidor->rh01_regist,$oPost->anofolha,$oPost->mesfolha);
  $oCalculoFolhaRescisao = new CalculoFolhaRescisao($oServidor);

  $oCalculoFolhaSalario  = new CalculoFolhaSalario(new Servidor ($oServidor->getMatricula(), $iAnoFolhaAnterior, $iMesFolhaAnterior));
  $aRemuneracaoAnterior  = $oCalculoFolhaSalario->getMovimentacoes();

  $nRemuneracaoAnterior = 0;

  foreach ( $aRemuneracaoAnterior as $oRemuneracaoAnterior ) {

    if ( $oRemuneracaoAnterior->iProventoDesconto == Rubrica::TIPO_PROVENTO) {
      $nRemuneracaoAnterior += $oRemuneracaoAnterior->nValor;
    }
    if ( $oRemuneracaoAnterior->iProventoDesconto == Rubrica::TIPO_DESCONTO) {
      $nRemuneracaoAnterior -= $oRemuneracaoAnterior->nValor;
    }
  }

  /**
   * Nao encontrou remuneracao para o mes anterior a rescisao entao buscar base, rubrica R992  
   */
  if ( $nRemuneracaoAnterior == 0 ) {

    $aRemuneracaoAnterior = $oCalculoFolhaRescisao->getMovimentacoes(null, 'R992');
    if ( !empty($aRemuneracaoAnterior[0]) ) {
      $nRemuneracaoAnterior = $aRemuneracaoAnterior[0]->nValor;
    }
  }

  $nRemuneracaoAnterior = trim(db_formatar($nRemuneracaoAnterior, 'f'));
  
  $oDocumentos = $oServidor->getDocumentos();
  $oCgm        = $oServidor->getCGM();

  $aGrupos   = array();
  $aRubricas = array();

  $oGrupoPadrao               = new StdClass;
  $oGrupoPadrao->sDescricao   = "Outras Verbas Devidas"; 
  $oGrupoPadrao->iCodigoGrupo = "00"; 
  $oGrupoPadrao->iTipoGrupo   = Rubrica::TIPO_PROVENTO;
  $oGrupoPadrao->nValor       = 0;
  $oGrupoPadrao->aRubricas    = array();

  $sCodigoSeguranca = '';
  $sCodigoTRCT      = '';
  $iFeriasAvos      = 0;
  $iFeriasVencidas  = 0;
  $i13SalarioAvos   = 0;
  $dFeriaVencidaInicio = '';
  $dFeriaVencidaFinal  = '';
  $aFeriasVencidas = array();

  $sCamposRhpesrescisao = 'rh05_codigoseguranca, rh05_trct, rh05_feriasavos, rh05_feriasvencidas, rh05_13salarioavos'; 
  $sSqlRhpesrescisao    = $oDaoRhpesrescisao->sql_query_file($oDadosServidor->rh02_seqpes, $sCamposRhpesrescisao);
  $rsRhpesrescisao      = $oDaoRhpesrescisao->sql_record($sSqlRhpesrescisao);

  /**
   * Informacoes da rescisao, tabela rhpesresciao 
   */
  if ( $oDaoRhpesrescisao->numrows > 0 ) {

    $oRhpesrescisao   = db_utils::fieldsMemory($rsRhpesrescisao, 0);
    $sCodigoSeguranca = $oRhpesrescisao->rh05_codigoseguranca;
    $sCodigoTRCT      = $oRhpesrescisao->rh05_trct;
    $iFeriasAvos      = $oRhpesrescisao->rh05_feriasavos;
    $iFeriasVencidas  = $oRhpesrescisao->rh05_feriasvencidas;
    $i13SalarioAvos   = $oRhpesrescisao->rh05_13salarioavos;

    /**
     * Servidor com ferias vencidas - Busca periodo de ferias vencidas
     */
    if ( $iFeriasVencidas > 0 ) {
    
      $sCamposCadferia = "r30_perai, r30_peraf, r30_faltas";
      $sOrderCadferia  = "r30_perai desc, r30_peraf desc";
      $sWhereCadferia  = "r30_regist = {$oDadosServidor->rh01_regist} and r30_anousu = {$iAnoFolha} and r30_mesusu = {$iMesFolha}";
      $sSqlCadferia    = $oDaoCadferia->sql_query_file(null, $sCamposCadferia, $sOrderCadferia, $sWhereCadferia);
      $rsCadferia      = $oDaoCadferia->sql_record($sSqlCadferia);

      if ( $oDaoCadferia->numrows > 0 ) {

        $oCadferia = db_utils::fieldsMemory($rsCadferia, 0);
        $dFeriaVencidaInicio = date('d/m/Y', strtotime($oCadferia->r30_peraf));                        
        $dFeriaVencidaFinal  = date('d/m/Y', strtotime('+1 year', strtotime($oCadferia->r30_peraf)));  
      }
    }

  }

  foreach ( $oCalculoFolhaRescisao->getMovimentacoes() as $oDadosMovimentacao ) {

    $oDadosGrupo = $oDaoAgrupmanetoRubricaRubrica->getGrupoPelaRubrica($oDadosMovimentacao->oRubrica->getCodigo());

    /**
     *  Caso não encontre grupo passa para o proximo registro
     */ 
    if ( empty($oDadosGrupo) ) {

      $oGrupoPadrao->aRubricas[] = $oDadosMovimentacao;     
      continue;
    }  

    /**
     * Reescreve descricao de alguns grupos 
     */
    switch( $oDadosGrupo->rh113_codigo ) {

      /**
       * Informar o saldo liquido de dias de salário(número de dias do mês até o afastamento, descontadas as faltas
       * e o DSR refererente às semanas não integralmente trabalhadas.
       *
       * 'Saldo líquido de xx/dias Salário (líquido de xx/faltas e DSR)';
       * Calcular nº dias trabalhados no mes, faltas e afastamentos
       */   
      case '50' :

        $iDiasTrabalhadosMesRescisao = date('d', strtotime($oDadosServidor->rh05_recis));
        $sDescricao = 'Saldo líquido de '. $iDiasTrabalhadosMesRescisao .'/dias Salário/faltas e DSR';
      break;

      /**
       * Busca descricao da rubrica
       *
       * 'Adic. Insal xx%'
       * Na coluna valor, informar o valor referente ao adicional de insalubridade devido no mes do afastamento  do trabalhador
       */   
      case '53' :

      /**
       * Busca descricao da rubrica
       *
       * 'Adic. Periculosidade xx%'
       * Na coluna valor, informar o valor referente ao adicional de pericuosidade devido no mes do afastamento do trabalhador
       */   
      case '54' :
        
      /**
       * Busca descricao da rubrica
       *
       * 'Adic. noturno Horas a 5%'
       * Informar o total de horas noturnas trabalhadas no mês e o percentual incidetne sobre estas horas noturnas.
       * na coluna valor, informar o vlaor referente total de horas extras trabalhadas no mês do afastamento do trabalhador. 
       */   
      case '55' :
        $sDescricao = $oDadosMovimentacao->oRubrica->getDescricao();
      break;

      /**
       * Na coluna valor, informar o valor referente ao decimo terceiro salario proporcional devido no mes do afastamento do trabalhador. 
       */   
      case '63' :
        $sDescricao = '13º Salário Proporcional '. $i13SalarioAvos .'/12 avos';
      break;
        
      /**
       * Na coluna valor, informar o valor referente a ferias proporcionais devidas ao trabalhador.
       */   
      case '65' :
        $sDescricao = 'Férias proporcionais '. $iFeriasAvos .'/12 avos';
      break;

      /**
       * Informar o periodo aquisitivo a que se refere as ferias vencidas, no formato dd/mm/aaaa. caso exista mais de um exercicio devido, 
       * poderao ser criados os subitens 66.2, 66.3... na coluna valor, informar o valor devido ao trabalhador
       */   
      case '66.1' :
        $sDescricao = 'Férias vencidas Per. Aquis. '. $dFeriaVencidaInicio .' a '. $dFeriaVencidaFinal;
      break;

      default :
        $sDescricao = $oDadosGrupo->rh113_descricao;
      break;

    }

    $oStdGrupo               = new StdClass;
    $oStdGrupo->sDescricao   = $oDadosGrupo->rh113_codigo . ' - ' .$sDescricao; 
    $oStdGrupo->iCodigoGrupo = $oDadosGrupo->rh113_codigo; 
    $oStdGrupo->iTipoGrupo   = $oDadosGrupo->rh113_tipo; 
    $oStdGrupo->nValor       = 0;
    $oStdGrupo->aRubricas    = array();

    $aGrupos[$oDadosGrupo->rh114_agrupamentorubrica]     = $oStdGrupo;
    $aRubricas[$oDadosGrupo->rh114_agrupamentorubrica][] = $oDadosMovimentacao;
  }

  unset($oDadosGrupo);

  /**
   * Percorrendo os grupos encontrados e soma o valor das rubricas e colocando as rubricas em cada grupo 
   */   
  foreach ( $aGrupos as $iCodigoGrupo => $oDadosGrupo ) {

    foreach ( $aRubricas[$iCodigoGrupo] as $oDadosMovimentacao) {

      if ( $oDadosMovimentacao->oRubrica->getTipo() == Rubrica::TIPO_DESCONTO) {
        $oDadosGrupo->nValor -= $oDadosMovimentacao->nValor;
      } elseif ( $oDadosMovimentacao->oRubrica->getTipo() == Rubrica::TIPO_PROVENTO) {
        $oDadosGrupo->nValor += $oDadosMovimentacao->nValor;
      }
    }

    unset($aRubricas[$iCodigoGrupo]);
  }

  /**
   * Somando Valores das Rubricas que não estão em um grupo
   */
  foreach ( $oGrupoPadrao->aRubricas as $oDadosMovimentacao ) {

    if ( $oDadosMovimentacao->oRubrica->getTipo() == Rubrica::TIPO_DESCONTO) {
      $oGrupoPadrao->nValor -= $oDadosMovimentacao->nValor;
    } elseif ( $oDadosMovimentacao->oRubrica->getTipo() == Rubrica::TIPO_PROVENTO) {
      $oGrupoPadrao->nValor += $oDadosMovimentacao->nValor;
    } 
  }

  /**
   * Se houver Rubricas no grupo padrão adiciona nos grupos encontrados
   */
  if ( count($oGrupoPadrao->aRubricas) > 0 ) {
    $aGrupos[] = $oGrupoPadrao;
  }

  /**
   * Fim das movimentções  
   * ---------------------------------------------
   */
  
  $oServidorRelatorio->sNomeMae             = $oCgm->getNomeMae();
  $oServidorRelatorio->sNome                = $oCgm->getNome();
  $oServidorRelatorio->sPis                 = $oDocumentos->sPIS;
  $oServidorRelatorio->sEndereco            = "{$oCgm->getLogradouro()}, {$oCgm->getNumero()} {$oCgm->getComplemento()}";
  $oServidorRelatorio->sBairro              = $oCgm->getBairro();
  $oServidorRelatorio->sMunicipio           = $oDadosServidor->z01_munic;
  $oServidorRelatorio->sUf                  = $oDadosServidor->z01_uf;
  $oServidorRelatorio->sCep                 = trim(db_formatar($oDadosServidor->z01_cep, 'cep'));
  $oServidorRelatorio->sCtps                = $oDadosServidor->rh16_ctps_n .'/' . $oDadosServidor->rh16_ctps_s .' ' . $oDadosServidor->rh16_ctps_d .' ' . $oDadosServidor->rh16_ctps_uf;
  $oServidorRelatorio->sCpf                 = trim(db_formatar($oDadosServidor->z01_cgccpf, 'cpf'));
  $oServidorRelatorio->dNascimento          = date('d/m/Y', strtotime($oDadosServidor->rh01_nasc));
  $oServidorRelatorio->dAdmissao            = date('d/m/Y', strtotime($oDadosServidor->rh01_admiss));
  $oServidorRelatorio->dRescisao            = date('d/m/Y', strtotime($oDadosServidor->rh05_recis));
  $oServidorRelatorio->dAvisoPrevio         = $oDadosServidor->rh05_aviso; 
  $oServidorRelatorio->sTipoContrato        = $oDadosServidor->h13_descr;
  $oServidorRelatorio->sCausaRescisao       = $oDadosServidor->rh115_descricao;
  $oServidorRelatorio->sCodigoAfastamento   = $oDadosServidor->rh115_sigla;
  $oServidorRelatorio->sCategoria           = $oDadosServidor->rh52_descr; 
  $oServidorRelatorio->iRegime              = $oDadosServidor->rh30_regime;
  $oServidorRelatorio->iProjetosAtividades  = $oDadosServidor->o55_projativ;
  $oServidorRelatorio->sProjetosAtividades  = $oDadosServidor->o55_descr;
  $oServidorRelatorio->nPensao              = $oDadosServidor->r52_perc ? trim(db_formatar($oDadosServidor->r52_perc, 'f')) : '';
  $oServidorRelatorio->nRemuneracaoAnterior = $nRemuneracaoAnterior;

  /**
   * Titulo do anexo I 
   */
  $oServidorRelatorio->sTituloRelatorio = 'TERMO DE RESCISÃO DO CONTRATO DE TRABALHO';

  /**
   * Titulo do anexo para estatutario 
   */
  if ( $oDadosServidor->rh30_regime == 1 ) {
    $oServidorRelatorio->sTituloRelatorio = 'TERMO DE EXONERAÇÃO';
  }

  /**
   * Data de aviso previo 
   * Caso campo rh05_aviso estiver vazio, pega data da rescisao
   */
  if ( !empty($oDadosServidor->rh05_aviso) ) {
    $oServidorRelatorio->dAvisoPrevio = date('d/m/Y', strtotime($oDadosServidor->rh05_aviso)); 
  } else {
    $oServidorRelatorio->dAvisoPrevio = date('d/m/Y', strtotime($oDadosServidor->rh05_recis)); 
  }

  /**
   * Se servidor nao possuir sindicato informar valores padrao 
   */
  $oServidorRelatorio->sCodigoSindical = '999.000.000.00000-3';
  $oServidorRelatorio->sCnpjSindical   = '37.115.367/0035-00';
  $oServidorRelatorio->sNomeSindical   = 'Ministério do Trabalho e Emprego - MTE';

  if ( !empty($oDadosServidor->rh116_codigo) ) {
    $oServidorRelatorio->sCodigoSindical = $oDadosServidor->rh116_codigo;
  }

  if ( !empty($oDadosServidor->rh116_cnpj) ) {
    $oServidorRelatorio->sCnpjSindical = $oDadosServidor->rh116_cnpj;
  }

  if ( !empty($oDadosServidor->rh116_descricao) ) {
    $oServidorRelatorio->sNomeSindical = $oDadosServidor->rh116_descricao;
  }

  /**
   * Códio de seguranca e TRCT
   */
  $oServidorRelatorio->sCodigoSeguranca = $sCodigoSeguranca;
  $oServidorRelatorio->sCodigoTRCT      = $sCodigoTRCT;

  /**
   * StdClass com os objetos usados nos anexos
   */
  $oDadosRelatorio = new stdClass();
  $oDadosRelatorio->oDadosServidor    = $oServidorRelatorio;
  $oDadosRelatorio->oDadosInstituicao = $oDadosInstituicao; 
  $oDadosRelatorio->aGruposRubricas   = $aGrupos; 

  $oImpCarne = new db_impcarne($oPdf, $iTipoRelatorio);
  $oImpCarne->oDadosRelatorio = $oDadosRelatorio;
  $oImpCarne->imprime();

  /**
   * Não é estatutário - inclui anexos
   * Regime:
   * 1 - Estatutário
   * 2 - CLT 
   * 3 - Extra quadro
   */   
  if ( $oDadosServidor->rh30_regime != 1 ) {
  
    /**
     * Nao usa homolognet
     */   
    if ( !$lHomolognet ) {

      /**
       * Calcula numero de dias entre data de admissao e de rescisao
       */
      $iDias = strtotime($oDadosServidor->rh05_recis) - strtotime($oDadosServidor->rh01_admiss);
      $iDias = (int) floor( $iDias / (60 * 60 * 24)); 

      /**
       * Menos de um ano
       */   
      if ( $iDias < 365 ) {
        $aAnexos = array(5, 6);
      } else {
        $aAnexos = array(5, 7);
      } 

    } else {
      $aAnexos = array(2, 3, 4, 5);  
    }

    /**
     * Inclui anexos de acordo com tempo de contrato e se usa homolognet
     */   
    foreach ( $aAnexos as $iAnexo ) {
      include 'fpdf151/impmodelos/mod_imprime80_' . $iAnexo . '.php';
    }
  }

}

$oImpCarne->objpdf->output();

class PDFHelper {

  /**
   * Cores
   */   
  const PDF_BRANCO = 245;
  const PDF_CINZA  = 200;

  /**
   * Fontes
   */   
  const FONTE_TITULO        = 9;
  const FONTE_TITULO_COLUNA = 8;
  const FONTE_TEXTO         = 10;

  /**
   * Alturas
   */   
  const ALTURA_LINHA                    = 8;
  const ALTURA_LINHA_VERBAS_RESCISORIAS = 7.5;
  const ALTURA_LINHA_TITULOS            = 3.5;
  const ALTURA_LINHA_TITULO_DOCUMENTO   = 5;

  /**
   * Largura maxima do documento sem contar as margins
   */   
  const LARGURA_MAXIMA = 180;

  /**
   * Margins
   */   
  const MARGIN_LEFT  = 15;
  const MARGIN_RIGHT = 15;
  const MARGIN_TOP   = 10;

  /**
   * Margin top
   * 
   * @var float
   * @access public
   */
  public $nMarginTop = 0;

  /**
   * Percentual do espacamento da esquerda
   */   
  public $nPercentualMarginLeft = 0;

  public static $nTotalLiquido = 0;

  /**
   * Objeto pdf db_impcarne
   */   
  public $oPdf;

  /**
   * __construct 
   * 
   * @param FPDF $oPdf 
   * @access public
   * @return void
   */
  public function __construct( FPDF $oPdf) {
    $this->oPdf = $oPdf;
  }

  /**
   * Adciona uma coluna 
   * 
   * @param string $sTitulo - texto do titulo
   * @param string $sValor  - texto da coluna
   * @param float $nPercentualLargura - percentual da largura da coluna 
   * @param float $nAltura  - altura da coluna em mm
   * @access public
   * @return void
   */
  public function addColuna($sTitulo, $sValor, $nPercentualLargura, $nAltura = PDFHelper::ALTURA_LINHA) {

    /**
     * Escreve retangulo
     */   
    $this->oPdf->rect( $this->marginLeft(), $this->marginTop(), $this->larguraColuna($nPercentualLargura), $this->alturaColuna($nAltura) );

    /**
     * Escreve titulo da coluna
     */   
    $this->oPdf->Setfont('Arial', '', PDFHelper::FONTE_TITULO_COLUNA);
    $this->oPdf->text( $this->marginLeft('t'), $this->marginTop('h'), $sTitulo );

    /**
     * Escreve texto da coluna
     */   
    $this->oPdf->Setfont('Arial', '', PDFHelper::FONTE_TEXTO);
    $this->oPdf->text( $this->marginLeft('t'), $this->marginTop('t'), $sValor );

    /**
     * Soma a percentual da largura do campo ao percentual da margin left para escrever o proxima coluna do lado
     */   
    $this->nPercentualMarginLeft += $nPercentualLargura;
  }

  /**
   * Escreve uma celula 
   * 
   * @param string $sConteudo - Texto da celula
   * @param float $nLargura   - Percentual da largura da celula
   * @param float $nAltura    - Altura da coluna em mm
   * @param bool $lPreenchimento - preenche ou nao ceula
   * @param float $iPaddingTop   - Espacamento top do texto em mm
   * @access public
   * @return void
   */
  public function addCelula($sConteudo, $nLargura, $nAltura = PDFHelper::ALTURA_LINHA, $lPreenchimento = false, $iPaddingTop = 1.3, $iTamanhoFonte = PDFHelper::FONTE_TEXTO) {

    $this->oPdf->Setfont('Arial', '', $iTamanhoFonte);
    $sPreenchimento = $lPreenchimento ? 'DF' : 'D';

    $nMarginTop  = $this->marginTop();
    $nMarginLeft = $this->oPdf->GetLeftMargin();

    $this->oPdf->rect( $nMarginLeft, $nMarginTop, $this->larguraColuna($nLargura), $this->alturaColuna($nAltura), $sPreenchimento );

    $nMarginRight = ($this->oPdf->GetRightMargin() + PDFHelper::LARGURA_MAXIMA) - ($nMarginLeft + $this->larguraColuna($nLargura));

    $this->oPdf->SetTopMargin($nMarginTop);
    $this->oPdf->SetRightMargin($nMarginRight + PDFHelper::MARGIN_RIGHT);
    $this->oPdf->SetLeftMargin ( $nMarginLeft );

    $this->oPdf->SetY($nMarginTop + $iPaddingTop);
    $this->oPdf->write(3, $sConteudo );

    $nMarginLeft = $nMarginLeft + $this->larguraColuna($nLargura);

    $this->oPdf->SetY($nMarginTop);
    $this->oPdf->SetRightMargin(PDFHelper::MARGIN_RIGHT);
    $this->oPdf->SetLeftMargin($nMarginLeft);

    $this->nPercentualMarginLeft += $nLargura;
  }

  public function addTexto($sConteudo, $nLargura, $iTamanhoFonte = PDFHelper::FONTE_TEXTO) {

    $this->oPdf->Setfont('Arial', '', $iTamanhoFonte);
    $nMarginTop  = $this->marginTop();
    $nMarginLeft = $this->oPdf->GetLeftMargin();

    $nMarginRight = ($this->oPdf->GetRightMargin() + PDFHelper::LARGURA_MAXIMA) - ($nMarginLeft + $this->larguraColuna($nLargura));

    $this->oPdf->SetTopMargin($nMarginTop);
    $this->oPdf->SetRightMargin($nMarginRight + PDFHelper::MARGIN_RIGHT - 1);
    $this->oPdf->SetLeftMargin ( $nMarginLeft - 1 );

    $this->oPdf->SetY($nMarginTop);
    $this->oPdf->write(4, $sConteudo);

    $nMarginTopNovo =  $this->oPdf->getY() - $nMarginTop;
    $nMarginLeft    = $nMarginLeft + $this->larguraColuna($nLargura);

    $this->oPdf->SetY($nMarginTop);
    $this->oPdf->SetRightMargin(PDFHelper::MARGIN_RIGHT);
    $this->oPdf->SetLeftMargin($nMarginLeft);

    $this->nPercentualMarginLeft += $nLargura;

    if ( $this->nPercentualMarginLeft >= 100 ) {

      $this->novaLinha(0);
      $this->nMarginTop += $nMarginTopNovo + 4;
    }

  }

  /**
   * Escre uma linha 
   * 
   * @param float $nPercentualMarginTop - Percentual da margin top
   * @access public
   * @return void
   */
  public function novaLinha($nPercentualMarginTop = PDFHelper::ALTURA_LINHA) {

    $this->nMarginTop += $nPercentualMarginTop;
    $this->oPdf->setY($this->nMarginTop);
    $this->oPdf->SetLeftMargin(PDFHelper::MARGIN_LEFT);
    
    $this->nPercentualMarginLeft = 0;
  }

  public function novaPagina() {

    $this->oPdf->AliasNbPages();
    $this->oPdf->AddPage();
    $this->oPdf->setfillcolor(PDFHelper::PDF_CINZA);
    
    $this->oPdf->SetX(PDFHelper::MARGIN_LEFT);
    $this->oPdf->SetY(PDFHelper::MARGIN_TOP);

    $this->oPdf->SetTopMargin(PDFHelper::MARGIN_TOP);
    $this->oPdf->SetRightMargin(PDFHelper::MARGIN_RIGHT);
    $this->oPdf->SetLeftMargin(PDFHelper::MARGIN_LEFT);

    $this->nMarginTop            = PDFHelper::MARGIN_TOP;
    $this->nPercentualMarginLeft = 0;
  }

  /**
   * Escre um titulo 
   * 
   * @param string $sTitulo - texto do titulo
   *
   * @param int $sBorda
   * borda:
   *  0 - sem borda
   *  1 - com borda
   *  L - esquerda
   *  T - acima
   *  R - direita
   *  B - abaixo
   *
   * @param bool $lPreenchimento - preenche ou nao  a celula
   *
   * @param string $sAlinhamento
   * string com alinhamento:
   *  L - ou um texto vazio: alinhado à esquerda (valor padrão)
   *  C - centralizado
   *  R - alinhado à direita
   *
   * @param float $nAlturaLinha  - altura da linha, por padrao é 3.5
   * @param float $nTamanhoFonte - Tamanho da fonte do titulo
   *
   * @access public
   * @return void
   */
  public function addTitulo($sTitulo, $sBorda = 1, $lPreenchimento = true, $sAlinhamento = "C", $nAlturaLinha = PDFHelper::ALTURA_LINHA_TITULOS, $nTamanhoFonte = PDFHelper::FONTE_TITULO) {

    $this->oPdf->Setfont('Arial', 'B', $nTamanhoFonte);
    $this->oPdf->setXY($this->marginLeft(), $this->marginTop());
    $this->oPdf->Cell(PDFHelper::LARGURA_MAXIMA, $nAlturaLinha, $sTitulo, $sBorda, 1, $sAlinhamento, $lPreenchimento);
    
    $this->oPdf->Setfont('Arial', '', PDFHelper::FONTE_TEXTO);
    $this->novaLinha($nAlturaLinha);
  }

  /**
   * Margin top, eixo y 
   * 
   * @param string $sTipo
   * tipo: 
   *  r - rect
   *  t - text
   *  h - header/titulo
   *
   * @param float $nAltura - se informado soma altura em mm ao eixo Y
   * @access public
   * @return float
   */
  public function marginTop($sTipo = null, $nAltura = 0) {
    
    if ( $nAltura > 0 ) {

      $this->nMarginTop += $nAltura;
      $this->oPdf->setY($this->nMarginTop);
    }          
    
    if ( $sTipo == 'h' ) {
      return ($this->nMarginTop + 3);
    }

    if ( $sTipo == 't' ) {
      return ($this->nMarginTop + 7);
    }

    return $this->nMarginTop;
  }

  /**
   * Espacamento esquerdo 
   * 
   * @param string $sTipo
   * tipo: 
   *  r - rect
   *  t - text
   * @param float $nPorcentagem 
   * @access public
   * @return float - margin left em mm
   */
  public function marginLeft($sTipo = null, $nPorcentagem = 0) {

    $iColuna = 0;

    if ( $this->nPercentualMarginLeft > 0 ) {
      $nPorcentagem = $this->nPercentualMarginLeft;
    } 

    if ( $nPorcentagem > 0 ) {
    
      $iTotalLinha = PDFHelper::LARGURA_MAXIMA;
      $iColuna     = $nPorcentagem / 100 * $iTotalLinha;
    }

    switch ( $sTipo ) {
    
      /**
       * Margin left para texto
       */   
      case 't' :
        $iColuna += 0.5;
      break;
    }

    return round( $iColuna + PDFHelper::MARGIN_LEFT , 2);
  }

  /**
   * Largura da coluna 
   * 
   * @param string $sTipo 
   * @param float $nPorcentagem - Porcentagem que a coluna ocupara na linha
   * @access public
   * @return float
   */
  public function larguraColuna($nPorcentagem = 0) {
    
    $iColuna = 0;

    if ( $nPorcentagem > 0 ) {

      $iTotalLinha = PDFHelper::LARGURA_MAXIMA;
      $iColuna     = $nPorcentagem / 100 * $iTotalLinha;
    }

    $iColuna = round($iColuna, 2);

    return $iColuna;
  }

  /**
   * Algura da coluna 
   * 
   * @param float $nAltura - altura da coluna em mm
   * @access public
   * @return float
   */
  public function alturaColuna($nAltura = PDFHelper::ALTURA_LINHA) {
    return $nAltura;
  }

  public function getAlturaCelulaLinhaVerbasRescisorias($aConteudos) {

    $iLarguraMaxima   = PDFHelper::LARGURA_MAXIMA;

    $iLarguraDescricao = $this->larguraColuna(22.22222);
    $iAlturaLinha      = 5;
    $aAltura           = array();

    foreach ( $aConteudos as $oConteudo ) {
      $aAltura[] = $this->oPdf->NbLines($iLarguraDescricao, $oConteudo->sDescricao) * $iAlturaLinha;
    }  

    $iAltura = max($aAltura);
    return $iAltura;
  }

  /**
   * Escreve o cabecalho usado para verbas rescisoarias 
   * 
   * @param integer $iTipoGrupo
   * Tipo de grupo:
   *  1 - Provento
   *  2 - Desconto
   * @access public
   * @return void
   */
  public function escreverCabecalhoVerbasRescisorias($iTipoGrupo) {

    $iPercentualDescricao = 22.22222;
    $iPercentualValor     = 11.11111;
    $sTitulo              = 'Rubrica';

    if ( $iTipoGrupo == Rubrica::TIPO_DESCONTO ) {
      $sTitulo = 'Desconto';
    }
    $this->oPdf->SetLeftMargin(PDFHelper::MARGIN_LEFT);

    $this->oPdf->Setfont('Arial', 'B', PDFHelper::FONTE_TITULO);

    $this->addCelula($sTitulo, $iPercentualDescricao, PDFHelper::ALTURA_LINHA_TITULOS, false, 0.5);
    $this->addCelula("Valor", $iPercentualValor     , PDFHelper::ALTURA_LINHA_TITULOS, false, 0.5);
    
    $this->addCelula($sTitulo, $iPercentualDescricao, PDFHelper::ALTURA_LINHA_TITULOS, false, 0.5);
    $this->addCelula("Valor", $iPercentualValor     , PDFHelper::ALTURA_LINHA_TITULOS, false, 0.5);
    
    $this->addCelula($sTitulo, $iPercentualDescricao, PDFHelper::ALTURA_LINHA_TITULOS, false, 0.5);
    $this->addCelula("Valor", $iPercentualValor     , PDFHelper::ALTURA_LINHA_TITULOS, false, 0.5);

    $this->oPdf->Setfont('Arial', '', PDFHelper::FONTE_TEXTO);

    $this->novaLinha(PDFHelper::ALTURA_LINHA_TITULOS);
  }

  /**
   * Escreve as celular das vebas rescisorias para o anexo I 
   * 
   * @param array $aGrupoRubricas - array com os grupos de rubricas
   * @access public
   * @return void
   */
  public function verbasRescisoriasAnexoI($aGrupoRubricas) {

    $aProventos = array();
    $aDescontos = array();
    $this->oPdf->SetLeftMargin(PDFHelper::MARGIN_LEFT);

    foreach ( $aGrupoRubricas as $oDadosGrupo ) {
      
      $oStdGrupoRubrica             = new StdClass;
      $oStdGrupoRubrica->sDescricao = $oDadosGrupo->sDescricao;
      $oStdGrupoRubrica->nValor     = $oDadosGrupo->nValor;

      if ($oDadosGrupo->iTipoGrupo == Rubrica::TIPO_PROVENTO ) {

        $aProventos[] = $oStdGrupoRubrica;
        continue;
      }

      $aDescontos[] = $oStdGrupoRubrica;
    }

    $this->addTitulo('VERBAS RESCISÓRIAS', 1, false, 'L');
    $this->escreverCabecalhoVerbasRescisorias(Rubrica::TIPO_PROVENTO);
    $nTotalProventos = $this->escreverDadosVerbasRescisorias($aProventos, Rubrica::TIPO_PROVENTO); 
    
    $this->addTitulo('DEDUÇÕES', 1, false, 'L');
    $this->escreverCabecalhoVerbasRescisorias(Rubrica::TIPO_DESCONTO);

    $nTotalDescontos = $this->escreverDadosVerbasRescisorias($aDescontos, Rubrica::TIPO_DESCONTO);

    /**
     * quando valor total das rubricas do grupo de proventos é negativo  
     * atualmente converte numero para positivo para nao somar errado total liquido
     */
    if ( $nTotalDescontos < 0 ) {
      $nTotalDescontos = $nTotalDescontos * -1;
    }

    /**
     * Escreve Totalizador
     */
    $lPreenchimento = false;  
    $sConteudo      = trim(db_formatar($nTotalProventos - $nTotalDescontos, 'f'));     
    $sTitulo        = "TOTAL LÍQUIDO";

    $this->addCelula("", 22.2222, PDFHelper::ALTURA_LINHA_VERBAS_RESCISORIAS, $lPreenchimento);
    $this->addCelula("", 11.1111, PDFHelper::ALTURA_LINHA_VERBAS_RESCISORIAS, $lPreenchimento);  

    $this->addCelula("", 22.2222, PDFHelper::ALTURA_LINHA_VERBAS_RESCISORIAS, $lPreenchimento);
    $this->addCelula("", 11.1111, PDFHelper::ALTURA_LINHA_VERBAS_RESCISORIAS, $lPreenchimento);   

    $lPreenchimento = true;               

    $this->oPdf->Setfont('Arial', 'B', PDFHelper::FONTE_TITULO);
    $this->addCelula($sTitulo,   22.2222, PDFHelper::ALTURA_LINHA_VERBAS_RESCISORIAS, $lPreenchimento, 2.5);

    $this->oPdf->Setfont('Arial', '', PDFHelper::FONTE_TEXTO);
    $this->addCelula($sConteudo, 11.1111, PDFHelper::ALTURA_LINHA_VERBAS_RESCISORIAS, $lPreenchimento, 2.5);     

    $this->novaLinha(PDFHelper::ALTURA_LINHA_VERBAS_RESCISORIAS);

    self::$nTotalLiquido = $sConteudo;
  }

  /**
   * Escreve as celular das vebas rescisorias para o anexo I 
   * 
   * @param array $aGrupoRubricas - array com os grupos de rubricas
   * @access public
   * @return void
   */
  public function verbasRescisoriasAnexoII($aGrupoRubricas) {

    $aProventos = array();
    $aDescontos = array();
    $this->oPdf->SetLeftMargin(PDFHelper::MARGIN_LEFT);

    foreach ( $aGrupoRubricas as $oDadosGrupo ) {
      
      $oStdGrupoRubrica             = new StdClass;
      $oStdGrupoRubrica->sDescricao = $oDadosGrupo->sDescricao;
      $oStdGrupoRubrica->nValor     = $oDadosGrupo->nValor;

      if ($oDadosGrupo->iTipoGrupo == Rubrica::TIPO_PROVENTO ) {

        $aProventos[] = $oStdGrupoRubrica;
        continue;
      }

      $aDescontos[] = $oStdGrupoRubrica;
    }

    $this->addTitulo('VERBAS RESCISÓRIAS', 1, false, 'L');
    $this->escreverCabecalhoVerbasRescisorias(Rubrica::TIPO_PROVENTO);
    $nTotalProventos = $this->escreverDadosVerbasRescisorias($aProventos, Rubrica::TIPO_PROVENTO); 
    
    $this->novaLinha(5);
    $this->addTitulo('DISCRIMINAÇÃO DAS DEDUÇÕES', 1, true, 'C');
    $this->addTitulo('DEDUÇÕES', 1, false, 'L');
    $this->escreverCabecalhoVerbasRescisorias(Rubrica::TIPO_DESCONTO);
    $nTotalDescontos = $this->escreverDadosVerbasRescisorias($aDescontos, Rubrica::TIPO_DESCONTO);

    /**
     * Escreve Totalizador
     */
    $lPreenchimento = false;  
    $sConteudo      = trim(db_formatar($nTotalProventos - $nTotalDescontos, 'f'));     
    $sTitulo        = "TOTAL LÍQUIDO";

    $this->addCelula("", 22.2222, PDFHelper::ALTURA_LINHA_VERBAS_RESCISORIAS, $lPreenchimento);
    $this->addCelula("", 11.1111, PDFHelper::ALTURA_LINHA_VERBAS_RESCISORIAS, $lPreenchimento);  

    $this->addCelula("", 22.2222, PDFHelper::ALTURA_LINHA_VERBAS_RESCISORIAS, $lPreenchimento);
    $this->addCelula("", 11.1111, PDFHelper::ALTURA_LINHA_VERBAS_RESCISORIAS, $lPreenchimento);   

    $lPreenchimento = true;               

    $this->oPdf->Setfont('Arial', 'B', PDFHelper::FONTE_TITULO);
    $this->addCelula($sTitulo,   22.2222, PDFHelper::ALTURA_LINHA_VERBAS_RESCISORIAS, $lPreenchimento, 2.5);

    $this->oPdf->Setfont('Arial', '', PDFHelper::FONTE_TEXTO);
    $this->addCelula($sConteudo, 11.1111, PDFHelper::ALTURA_LINHA_VERBAS_RESCISORIAS, $lPreenchimento, 2.5);     

    $this->novaLinha(PDFHelper::ALTURA_LINHA_VERBAS_RESCISORIAS);
  }

  
  /**
   * Escreve as celulas dos grupos de verbas rescisórias  
   * 
   * @param mixed $aGrupoRubricas 
   * @param integer $iTipoGrupo
   * Tipo de grupo:
   *  1 - Provento
   *  2 - Desconto
   * @access public
   * @return float - valor total dos grupos do tipo provento/desconto
   */
  public function escreverDadosVerbasRescisorias($aGrupoRubricas, $iTipoGrupo) {

    $nTotalVerbasRescisorias = 0;
    $iPercentualDescricao    = 22.22222;
    $iPercentualValor        = 11.11111;
    $iAlturaPadrao           = PDFHelper::ALTURA_LINHA_VERBAS_RESCISORIAS;
    $iContadorCelulas        = 0;
    $iContadorLinhas         = 0;
    $aLinhaGrade             = array();

    for ( $iIndice = 0; $iIndice < count($aGrupoRubricas); $iIndice++ ) {

      if ( $iContadorCelulas == 3 ) {

        $iContadorLinhas++;
        $iContadorCelulas = 0;
      }

      $aLinhaGrade[ $iContadorLinhas ][ $iContadorCelulas ] = $aGrupoRubricas[$iIndice];
      $iContadorCelulas++;
    }

    $iAlturaCelula = 0;

    foreach ( $aLinhaGrade as $aLinha ) {

      $iAlturaCelula = $this->getAlturaCelulaLinhaVerbasRescisorias($aLinha);
      $iContadorInternoCelula = 0;

      foreach ( $aLinha as $oCelula ) {
      
        $iContadorInternoCelula++;
        $this->addCelula($oCelula->sDescricao, $iPercentualDescricao, $iAlturaCelula, false, 1.3, PDFHelper::FONTE_TITULO_COLUNA);
        $this->addCelula(trim(db_formatar($oCelula->nValor, 'f')), $iPercentualValor, $iAlturaCelula);

        $nTotalVerbasRescisorias += $oCelula->nValor;
      }

      if ($iContadorInternoCelula == 3) {
        $this->novaLinha($iAlturaCelula); 
      }
    }

    /**
     * Gera as Celulas Extras para a tabela não ficar com espaço sem bordas
     */
    for ($iCelulaExtra = $iContadorCelulas; $iCelulaExtra <= 2; $iCelulaExtra++) {

      $this->addCelula("", $iPercentualDescricao, $iAlturaCelula);
      $this->addCelula("", $iPercentualValor    , $iAlturaCelula); 
      if ($iCelulaExtra == 2) {
        $this->novaLinha($iAlturaCelula);
      }
    }

    /**
     * Escreve totalizador
     */
    for ($iCelulaTotalizador = 0; $iCelulaTotalizador < 3; $iCelulaTotalizador++) {

      $lPreenchimento = false;
      $sConteudo      = "";
      $sTitulo        = "";

      if ( $iCelulaTotalizador == 2 ) {
      
        $lPreenchimento = true;  
        $sConteudo      = trim(db_formatar($nTotalVerbasRescisorias, 'f'));     
        $sTitulo        =  $iTipoGrupo == Rubrica::TIPO_PROVENTO ? "TOTAL BRUTO" : "TOTAL DEDUÇÕES";
      }

      $this->oPdf->Setfont('Arial', 'B', PDFHelper::FONTE_TITULO);
       $this->addCelula($sTitulo,   $iPercentualDescricao, $iAlturaPadrao, $lPreenchimento, 2.5);
      $this->oPdf->Setfont('Arial', '', PDFHelper::FONTE_TEXTO);
      $this->addCelula($sConteudo, $iPercentualValor    , $iAlturaPadrao, $lPreenchimento, 2.5);  
    }

    $this->novaLinha($iAlturaPadrao);

    return $nTotalVerbasRescisorias;
  }

}