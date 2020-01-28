<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("fpdf151/pdf.php");
require_once("std/DBDate.php");
require_once("libs/exceptions/ParameterException.php");

try {

  $oGet = db_utils::postMemory($_GET);

  $sDataInicial = $oGet->sDataInicial; 
  $sDataFinal   = date('Y-m-d');

  if ( !empty($oGet->sDataFinal) ) {
    $sDataFinal = $oGet->sDataFinal;
  }

  $oDataInicial = new DBDate($sDataInicial);
  $oDataFinal   = new DBDate($sDataFinal);

  /**
   * Configuracoes de conexao
   * - transforma em array configuracoes
   */
  $aConfig = parse_ini_file("integracao_externa/webiss/libs/db_config.ini");

  /**
   * String de conexão com base da integracao
   */
  $sDataSourceWebiss  = "host={$aConfig['ConDestino_host']}         ";
  $sDataSourceWebiss .= "dbname={$aConfig['ConDestino_dbname']}     ";
  $sDataSourceWebiss .= "port={$aConfig['ConDestino_port']}         ";
  $sDataSourceWebiss .= "user={$aConfig['ConDestino_user']}         ";
  $sDataSourceWebiss .= "password={$aConfig['ConDestino_password']} ";

  /**
   * Erro ao conectar com base de integracao 
   */
  if ( !($connWebiss = pg_connect($sDataSourceWebiss)) ) {
    throw new Exception("Erro ao conectar na base de integração... ($sDataSourceWebiss)");
  }

  $sSql = " select distinct ";

  /**
   * Tipo de boleto
   * T - Tomador
   * P - Prestador
   */
  $sSql .= " integra_recibo.tipo_boleto, ";

  /**
   * Data de emissao boleto 
   */
  $sSql .= " integra_recibo.data_emissao, ";

  /**
   * Recibo anulado 
   */
  $sSql .= " case                                                  ";
  $sSql .= "   when integra_recibo_anulado.sequencial is null then ";
  $sSql .= "     false                                             ";
  $sSql .= "   else                                                ";
  $sSql .= "     true                                              ";
  $sSql .= " end as anulado,                                       ";

  /**
   * Recibo baixado 
   */
  $sSql .= " case                                                 ";
  $sSql .= "   when integra_recibo_baixa.sequencial is null then  ";
  $sSql .= "     false                                            ";
  $sSql .= "   else                                               ";
  $sSql .= "     true                                             ";
  $sSql .= " end as baixado,                                      ";

  /**
   * Recibo integrado 
   */
  $sSql .= " integra_recibo.processado as integrado, ";

  /**
   * Cadastro
   * - CPF/CNPJ e nome 
   */
  $sSql .= " integra_cadastro.cpf_cnpj as cpf_cnpj_cadastro, ";
  $sSql .= " integra_cadastro.nome     as nome_cadastro, ";

  /**
   * Empresa
   * - CPF/CNPJ, Inscricao e nome
   */
  $sSql .= " integra_cad_empresa.cpf_cnpj as cpf_cnpj_empresa, ";
  $sSql .= " integra_cad_empresa.inscricao, ";
  $sSql .= " integra_cad_empresa.nome_empresa, ";

  /**
   * Competencia do recibo gerado 
   */
  $sSql .= " integra_recibo.ano_competencia, ";
  $sSql .= " integra_recibo.mes_competencia, ";

  /**
   * Valor lancado 
   */
  $sSql .= " integra_recibo.valor_total, ";

  /**
   * Numpre do recibo, numnov
   */
  $sSql .= " integra_recibo.numdoc as numnov, ";

  /**
   * Numpre e numpar do debito
   */
  $sSql .= " integra_recibo_detalhe.numpre, ";
  $sSql .= " integra_recibo_detalhe.numpar  ";

  /**
   * Recibos 
   */
  $sSql .= " from integra_recibo ";

  /**
   * Detalhes do recibo
   */
  $sSql .= " inner join integra_recibo_detalhe on integra_recibo_detalhe.integra_recibo = integra_recibo.sequencial ";

  /**
   * Recibo anulado 
   */
  $sSql .= " left join integra_recibo_anulado  on integra_recibo_anulado.integra_recibo  = integra_recibo.sequencial ";

  /**
   * Recibo baixado 
   */
  $sSql .= " left join integra_recibo_baixa_detalhe on integra_recibo_baixa_detalhe.integra_recibo = integra_recibo.sequencial                         ";
  $sSql .= " left join integra_recibo_baixa         on integra_recibo_baixa.sequencial             = integra_recibo_baixa_detalhe.integra_recibo_baixa ";

  /**
   * Inscricao da empresa e CGM de cadastro 
   */
  $sSql .= " left join integra_cad_empresa on integra_cad_empresa.sequencial = integra_recibo.integra_cad_empresa ";
  $sSql .= " left join integra_cadastro    on integra_cadastro.sequencial    = integra_recibo.integra_cadastro    ";

  $sSql .= " where integra_recibo_detalhe.numpre is not null                   ";
  $sSql .= "   and integra_recibo.data_emissao >= '{$oDataInicial->getDate()}' ";
  $sSql .= "   and integra_recibo.data_emissao <= '{$oDataFinal->getDate()}'  ";

  $sSql .= " order by integra_recibo.data_emissao, integra_cad_empresa.nome_empresa, integra_cadastro.nome asc";

  $rsRecibos = db_query($connWebiss, $sSql);
  $iRecibos  = pg_num_rows($rsRecibos);
  $aRecibos  = array();

  /**
   * Nao encontrou recibos 
   */
  if ( $iRecibos == 0 ) {
    throw new Exception('Nenhum registro encontrado para os filtros informados.');
  }

  /**
   * Monta array de recibos com dados formatados para PDF 
   */
  for ( $iRecibo = 0; $iRecibo < $iRecibos; $iRecibo++ ) {

    $oDadosRecibo = db_utils::fieldsMemory($rsRecibos, $iRecibo);

    $oRecibo = new StdClass();
    $oRecibo->sDataEmissao     = date('d/m/Y', strtotime($oDadosRecibo->data_emissao));
    $oRecibo->iCpfCnpjCadastro = $oDadosRecibo->cpf_cnpj_cadastro;
    $oRecibo->iCpfCnpjEmpresa  = $oDadosRecibo->cpf_cnpj_empresa;
    $oRecibo->iInscricao       = $oDadosRecibo->inscricao;
    $oRecibo->sNomeEmpresa     = mb_strtoupper(limitar($oDadosRecibo->nome_empresa, 50));
    $oRecibo->sNomeCadastro    = mb_strtoupper(limitar($oDadosRecibo->nome_cadastro,  50));
    $oRecibo->iAnoCompetencia  = $oDadosRecibo->ano_competencia;
    $oRecibo->iMesCompetencia  = $oDadosRecibo->mes_competencia;
    $oRecibo->iNumpreRecibo    = $oDadosRecibo->numnov;
    $oRecibo->iNumpreDebito    = $oDadosRecibo->numpre;
    $oRecibo->iNumparDebito    = $oDadosRecibo->numpar;
    $oRecibo->nValorTotal      = db_formatar($oDadosRecibo->valor_total, 'f');

    $oRecibo->sTipoBoleto = '';

    if ( $oDadosRecibo->tipo_boleto == 'P' ) {
      $oRecibo->sTipoBoleto = 'Prestador';
    }

    if ( $oDadosRecibo->tipo_boleto == 'T' ) {
      $oRecibo->sTipoBoleto = 'Tomador';
    }

    $oRecibo->sSituacao  = 'Integrado';

    if ( $oDadosRecibo->integrado == 'f' ) {
      $oRecibo->sSituacao  = 'Para integrar';
    }

    if ( $oDadosRecibo->baixado == 't' ) {
      $oRecibo->sSituacao  = 'Baixado';
    }

    if ( $oDadosRecibo->anulado == 't' ) {
      $oRecibo->sSituacao  = 'Anulado';
    }

    $aRecibos[$oRecibo->sDataEmissao][] = $oRecibo;
  } 

  $head3 = "Recibos integrados com WEBISS";
  $head5 = "Data inicial: " . $oDataInicial->getDate(DBDate::DATA_PTBR);
  $head6 = "Data final: " . $oDataFinal->getDate(DBDate::DATA_PTBR);
  $head7 = "";

  $oPdf = new PDF('L');
  $oPdf->Open();
  $oPdf->AliasNbPages();

  $oPdf->AddPage();
  $oPdf->SetFillColor(235);

  $iTotalGeralDados = 0;

  /**
   * Percorre array com recibos e monta linhas do relatorio
   */
  foreach ( $aRecibos as $sDataEmissao => $aRecibo ) {

    if ( $oPdf->getY() > 170 ) {
      $oPdf->ln(10);
    }

    $iTotalDados       = count($aRecibo);
    $iTotalGeralDados += $iTotalDados;
    $iLinhaAtual       = 0;

    /**
     * Escreve Header 
     */
    $oPdf->Setfont('Arial', 'b', 8);
    $oPdf->cell( largura(70), 5, $sDataEmissao,      1, 0, 'C', 1);
    $oPdf->cell( largura(24), 5, "Total de Recibos: ",      1, 0, 'R', 1);
    $oPdf->Setfont('Arial', '', 8);
    $oPdf->cell( largura(6), 5, $iTotalDados,      1, 1, 'C', 1);
    $oPdf->Setfont('Arial', 'b', 8);
    $oPdf->cell( largura(6),  5, 'Inscrição', 1, 0, 'C', 1);
    $oPdf->cell( largura(10), 5, 'CPF/CNPJ',  1, 0, 'C', 1);
    $oPdf->cell( largura(39), 5, 'Nome',      1, 0, 'C', 1);
    $oPdf->cell( largura(5),  5, 'Ano',       1, 0, 'C', 1);
    $oPdf->cell( largura(3),  5, 'Mês',       1, 0, 'C', 1);
    $oPdf->cell( largura(7),  5, 'Numnov',    1, 0, 'C', 1);
    $oPdf->cell( largura(7),  5, 'Numpre',    1, 0, 'C', 1);
    $oPdf->cell( largura(3),  5, 'Parc.',     1, 0, 'C', 1);
    $oPdf->cell( largura(8),  5, 'Valor',     1, 0, 'C', 1);
    $oPdf->cell( largura(6),  5, 'Tipo',      1, 0, 'C', 1);
    $oPdf->cell( largura(6),  5, 'Situação',  1, 0, 'C', 1);
    $oPdf->ln();
    $oPdf->Setfont('Arial', '', 8);

    foreach ($aRecibo as $oDadosRecibo) {

      $iLinhaAtual++;
      
      /**
       * Empresa
       * - Tem inscricao 
       */
      if ( !empty($oDadosRecibo->iInscricao) ) {

        $oPdf->cell( largura(6),  5, $oDadosRecibo->iInscricao,      1, 0, 'C');
        $oPdf->cell( largura(10), 5, $oDadosRecibo->iCpfCnpjEmpresa, 1, 0, 'C');
        $oPdf->cell( largura(39), 5, $oDadosRecibo->sNomeEmpresa,    1, 0, 'L');

      } else {

        /**
         * Cadastro
         * - Nao tem inscricao 
         */
        $oPdf->cell( largura(6),  5, '', 1, 0, 'L');
        $oPdf->cell( largura(10), 5, $oDadosRecibo->iCpfCnpjCadastro, 1, 0, 'C');
        $oPdf->cell( largura(39), 5, $oDadosRecibo->sNomeCadastro,    1, 0, 'L');
      }

      $oPdf->cell( largura(5), 5, $oDadosRecibo->iAnoCompetencia, 1, 0, 'C');
      $oPdf->cell( largura(3), 5, $oDadosRecibo->iMesCompetencia, 1, 0, 'C');
      $oPdf->cell( largura(7), 5, $oDadosRecibo->iNumpreRecibo,   1, 0, 'C');
      $oPdf->cell( largura(7), 5, $oDadosRecibo->iNumpreDebito,   1, 0, 'C');
      $oPdf->cell( largura(3), 5, $oDadosRecibo->iNumparDebito,   1, 0, 'C');
      $oPdf->cell( largura(8), 5, $oDadosRecibo->nValorTotal,     1, 0, 'R');
      $oPdf->cell( largura(6), 5, $oDadosRecibo->sTipoBoleto,     1, 0, 'C');
      $oPdf->cell( largura(6), 5, $oDadosRecibo->sSituacao,       1, 0, 'C');
      $oPdf->ln();

      /**
       * Escreve header na proxima pagina
       */
      if ( $oPdf->getY() > 180 && $iTotalDados > $iLinhaAtual ) {

        $oPdf->AddPage();
        $oPdf->Setfont('Arial', 'b', 8);
        $oPdf->cell( largura(6),  5, 'Inscrição', 1, 0, 'C', 1);
        $oPdf->cell( largura(10), 5, 'CPF/CNPJ',  1, 0, 'C', 1);
        $oPdf->cell( largura(39), 5, 'Nome',      1, 0, 'C', 1);
        $oPdf->cell( largura(5),  5, 'Ano',       1, 0, 'C', 1);
        $oPdf->cell( largura(3),  5, 'Mês',       1, 0, 'C', 1);
        $oPdf->cell( largura(7),  5, 'Numnov',    1, 0, 'C', 1);
        $oPdf->cell( largura(7),  5, 'Numpre',    1, 0, 'C', 1);
        $oPdf->cell( largura(3),  5, 'Parc.',     1, 0, 'C', 1);
        $oPdf->cell( largura(8),  5, 'Valor',     1, 0, 'C', 1);
        $oPdf->cell( largura(6),  5, 'Tipo',      1, 0, 'C', 1);
        $oPdf->cell( largura(6),  5, 'Situação',  1, 0, 'C', 1);
        $oPdf->ln();
        $oPdf->Setfont('Arial', '', 8);
      }
    }
    
    $oPdf->ln(5);
  }

  $oPdf->Setfont('Arial', 'b', 8);
  $oPdf->cell( largura(100), 5, "Total de Recibos: $iTotalGeralDados", 1, 1, 'L', 1);

  /**
   * Manda para o browser o pdf 
   */
  $oPdf->Output();

} catch (Exception $oErro) {

  $sMensagemErro = urlEncode($oErro->getMessage());
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMensagemErro}");
}

/**
 * Calcula a largura da linha pela porcentagem 
 * 
 * @param float $nPorcentagem 
 * @access public
 * @return integer
 */
function largura($nPorcentagem = 0) {

  $iColuna = 0;
  $iTotalLinha = 276;

  if ( $nPorcentagem == 0 ) {
    return $iTotalLinha;
  }

  $iColuna = $nPorcentagem / 100 * $iTotalLinha;
  $iColuna = round($iColuna, 2);

  return $iColuna;
}

/**
 * Limitar tamanho de uma string
 *
 * @param string $sString
 * @param intger $iLimite
 * @access public
 * @return string
 */
function limitar($sString, $iLimite) {

  if ( strlen($sString) > $iLimite ) {
    $sString = mb_substr($sString, 0, $iLimite - 3) . '...';
  } else {
    $sString = mb_substr($sString, 0, $iLimite);
  }

  return $sString;
}