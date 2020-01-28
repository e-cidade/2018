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
require_once modification('libs/db_stdlib.php');
require_once modification("libs/db_conecta.php");

$oGet = db_utils::postMemory($_GET);

try{

  $oDaoParametro   = new cl_bib_parametros();
  $iDepartamento   = db_getsession('DB_coddepto');
  $sWhereParametro = "bi17_coddepto = {$iDepartamento}";
  $sSqlParametro   = $oDaoParametro->sql_query(null, 'bi26_impressora', null, $sWhereParametro);
  $rsParametro     = db_query( $sSqlParametro );

  if ( !$rsParametro ) {
    throw new DBException("Erro ao buscar parâmetro de impressão.");
  }

  $iModelo = 1;
  if ( pg_num_rows($rsParametro) == 1 ) {
    $iModelo = db_utils::fieldsMemory( $rsParametro , 0)->bi26_impressora ;
  }

  $sSqlEmprestimo  = " SELECT bi18_codigo, bi18_retirada, bi18_devolucao, ov02_sequencial, ov02_nome, bi17_nome ";
  $sSqlEmprestimo .= "   from emprestimo ";
  $sSqlEmprestimo .= "  inner join carteira         on bi16_codigo               = bi18_carteira ";
  $sSqlEmprestimo .= "  inner join leitor           on bi10_codigo               = bi16_leitor   ";
  $sSqlEmprestimo .= "   left join leitorcidadao    on leitorcidadao.bi28_leitor = leitor.bi10_codigo ";
  $sSqlEmprestimo .= "   left join cidadao         on cidadao.ov02_sequencial    = leitorcidadao.bi28_cidadao_sequencial ";
  $sSqlEmprestimo .= "                             and cidadao.ov02_seq          = leitorcidadao.bi28_cidadao_seq ";
  $sSqlEmprestimo .= "  inner join leitorcategoria  on bi07_codigo               = bi16_leitorcategoria ";
  $sSqlEmprestimo .= "  inner join biblioteca       on bi17_codigo               = bi07_biblioteca ";
  $sSqlEmprestimo .= "  where bi18_codigo in ({$emp})";
  $rsEmprestimo    = db_query( $sSqlEmprestimo );

  if ( !$rsEmprestimo ) {
    throw new DBException("Erro ao buscar os dados do emprestimo.");
  }

  $iTotalEmprestimos = pg_num_rows($rsEmprestimo);

  if ( $iTotalEmprestimos == 0 ) {
    throw new BusinessException("Não foi possível localizar o emprestimo.");
  }

  $oDadosEmprestimos               = new stdClass();
  $oDadosEmprestimos->aEmprestimos = db_utils::getCollectionByRecord($rsEmprestimo);

  $sWhereDevolucao               = ' and bi21_codigo is null';
  $oDadosEmprestimos->sTitulo    = "COMPROVANTE EMPRÉSTIMO DE ACERVO";
  $oDadosEmprestimos->sSubTitulo = "RELAÇÃO DE ITENS EMPRESTADOS";
  $oDadosEmprestimos->sLabel     = 'Devolver até';

  if ($oGet->tipo == 1 ) {

    $sWhereDevolucao               = ' and bi21_codigo is not null';
    $oDadosEmprestimos->sTitulo    = "COMPROVANTE DEVOLUÇÃO DE ACERVO";
    $oDadosEmprestimos->sSubTitulo = "RELAÇÃO DE ITENS DEVOLVIDOS";
    $oDadosEmprestimos->sLabel     = "Devolvido em";
    foreach ($oDadosEmprestimos->aEmprestimos as $oEmprestimo) {
      $oEmprestimo->bi18_devolucao = date('Y-m-d');
    }
  }

  $sqlAcervos  = " select bi23_codbarras, bi06_titulo ";
  $sqlAcervos .= "   from emprestimoacervo ";
  $sqlAcervos .= "  inner join emprestimo      on bi18_codigo                           = bi19_emprestimo ";
  $sqlAcervos .= "  inner join exemplar        on exemplar.bi23_codigo                  = emprestimoacervo.bi19_exemplar ";
  $sqlAcervos .= "  left  join devolucaoacervo on devolucaoacervo.bi21_emprestimoacervo = emprestimoacervo.bi19_codigo ";
  $sqlAcervos .= "  inner join acervo on acervo.bi06_seq = exemplar.bi23_acervo ";
  $sqlAcervos .= "  where bi18_codigo in ({$emp})";
  $sqlAcervos .= "   {$sWhereDevolucao} ";
  $rsAcervos   = db_query( $sqlAcervos );

  if ( !$rsAcervos ) {
    throw new DBException("Erro ao buscar os dados do acervo.");
  }

  if ( pg_num_rows($rsAcervos) == 0 ) {
    throw new BusinessException("Nenhum acervo encontrado.");
  }

  $oDadosEmprestimos->aAcervos = db_utils::getCollectionByRecord($rsAcervos);
  if ( $iModelo == 1 ) {

    require_once(modification("bib2_comprovantemodelo_a4.php"));
    return;
  }

  require_once(modification("bib2_comprovantemodelo_80mm.php"));

} catch( Exception $oErro ) {
  db_redireciona('db_erros.php?fechar=true&db_erro='.urlencode( $oErro->getMessage() ));
}
