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

class cl_emissaogeral extends DAOBasica{

  public function __construct()
  {
    parent::__construct("tributario.emissaogeral");
  }

  /**
   * Função que cria a query de consulta dos retornos e suas ocorrências e movimentações por emissão geral
   *
   * @param  integer $iCodigoEmissao  Código da Emissão Geral
   * @param  string  $sCampos         String com os campos que serão retornados na query
   * @return string                   SQL pronta
   */
  public function sql_query_ocorrencias_movimentacao( $iCodigoEmissao, $sCampos = "*" )
  {
    $sSqlOcorrencia  = " select {$sCampos}                                                                                         ";
    $sSqlOcorrencia .= "   from emissaogeral                                                                                       ";
    $sSqlOcorrencia .= "        inner join emissaogeralregistro ON tr02_emissaogeral = tr01_sequencial                             ";
    $sSqlOcorrencia .= "        inner join retornocobrancaregistrada on tr02_numpre = k168_numpre                                  ";
    $sSqlOcorrencia .= "        inner join ocorrenciaretornocobrancaregistrada ON k170_retornocobrancaregistrada = k168_sequencial ";
    $sSqlOcorrencia .= "        inner join ocorrenciacobrancaregistrada ON k149_sequencial = k170_ocorrenciacobrancaregistrada     ";
    $sSqlOcorrencia .= "        inner join movimentoocorrenciacobrancaregistrada ON k149_movimento = k169_sequencial               ";
    $sSqlOcorrencia .= "  where tr01_sequencial = {$iCodigoEmissao}                                                                ";

    return $sSqlOcorrencia;
  }
}