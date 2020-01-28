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
require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_app.utils.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("libs/JSON.php"));

$oJson                  = new services_json();
$oParam                 = JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "buscarEmprestimoParaDevolucao":

      if ( empty($oParam->iBiblioteca) ) {
        throw new ParameterException("Parâmetro da biblioteca não configurado. Para configurar, acesse o menu Procedimentos > Parâmetros.");
      }

      $aWhere = array('not exists (SELECT * FROM devolucaoacervo WHERE devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo )');

      if ( empty($oParam->iCodigoCarteira) && empty($oParam->iCodigoExemplar) ) {
        throw new BusinessException("É necessário informar: Ou a carteira do leitor ou o exemplar.");
      }

      $aWhere[] = " bi17_codigo = {$oParam->iBiblioteca}";
      if ( !empty($oParam->iCodigoCarteira) ) {
        $aWhere[] = " bi18_carteira = {$oParam->iCodigoCarteira} ";
      }

      if ( !empty($oParam->iCodigoExemplar) ) {
        $aWhere[] = " bi19_exemplar = {$oParam->iCodigoExemplar} ";
      }

      $sCampos  = ' bi18_codigo as emprestimo, bi18_retirada as data_retirada, bi18_devolucao as data_devolucao, ';
      $sCampos .= ' bi19_codigo as emprestimoacervo, bi23_codbarras as codigo_barras, bi06_titulo as titulo, ';
      $sCampos .= ' bi07_tempo as dias_emprestimo, acervo.bi06_seq as acervo, bi23_codigo as exemplar,';
      $sCampos .= ' bi16_codigo as carteira,';
      $sCampos .= ' (select ov02_nome ';
      $sCampos .= '    from leitorcidadao ';
      $sCampos .= '    join cidadao on cidadao.ov02_sequencial = leitorcidadao.bi28_cidadao_sequencial ';
      $sCampos .= '                and cidadao.ov02_seq        = leitorcidadao.bi28_cidadao_seq ';
      $sCampos .= '   where bi28_leitor = bi16_leitor) as leitor';


      $sWhere = implode( ' and ', $aWhere );
      $oDao   = new cl_emprestimoacervo();

      $sSqlEmprestimos = $oDao->sql_query_emprestimos_acervo_com_autor ( null, $sCampos, 'bi06_titulo', $sWhere);
      $rsEmprestimos   = db_query($sSqlEmprestimos);

      if ( !$rsEmprestimos ) {
        throw new DBException("Erro ao buscar os empréstimos.");
      }

      if ( pg_num_rows($rsEmprestimos) == 0 ) {
        throw new BusinessException("Nenhum empréstimo para o Leitor selecionado");
      }

      $oRetorno->aEmprestimos = array();

      $oData   = new DBDate( date('Y-m-d') );
      $iLinhas = pg_num_rows($rsEmprestimos);
      for ( $i = 0; $i < $iLinhas; $i++ ) {

        $oDados = db_utils::fieldsMemory($rsEmprestimos, $i);

        $oDados->lVencido = false;
        $oDtDevolucao     = new DBDate($oDados->data_devolucao);
        if ( $oDtDevolucao->getTimeStamp() < $oData->getTimeStamp() ) {
          $oDados->lVencido = true;
        }
        $oRetorno->aEmprestimos[] = $oDados;
      }

      break;

    case 'devolver':

      if ( count($oParam->aEmprestimos) == 0 ) {
        throw new BusinessException("Não foi selecionado nenhum exemplar.");
      }

      $sData = date( "Y-m-d", db_getsession("DB_datausu") );

      foreach ($oParam->aEmprestimos as $oDados) {

        $oDaoDevolucao = new cl_devolucaoacervo;

        $sSqlValida = $oDaoDevolucao->sql_query_file(null, "1", null, "bi21_emprestimoacervo = {$oDados->iEmprestimoAcervo}");
        $rsValida   = db_query($sSqlValida);

        //livro ja foi devolvido
        if (!$rsValida || pg_num_rows($rsValida) > 0) {
          continue;
        }

        $oDaoDevolucao->bi21_emprestimoacervo = $oDados->iEmprestimoAcervo;
        $oDaoDevolucao->bi21_entrega          = $sData;
        $oDaoDevolucao->bi21_usuario          = db_getsession("DB_id_usuario");
        $oDaoDevolucao->incluir($oDados->iEmprestimoAcervo);

        if ( $oDaoDevolucao->erro_status == 0 ) {
          throw new DBException("Erro ao salvar devolução do livro." . str_replace('\\n', "\n", $oDaoDevolucao->erro_msg));
        }
      }

      $oRetorno->sMessage = "Exemplar devolvido com sucesso.";

      break;

    case 'renovar':

      if ( empty($oParam->aItens) ) {
        throw new Exception("Nenhum exemplar informado.");
      }
      if ( empty($oParam->dtDevolucao) ) {
        throw new Exception("Data de devolução não informada.");
      }
      if ( empty($oParam->dtRetirada) ) {
        throw new Exception("Data de retirada não informada.");
      }
      if ( empty($oParam->iBiblioteca) ) {
        throw new Exception("Biblioteca não informada.");
      }

      $oDaoDevoluacaoAcervo = new cl_devolucaoacervo;
      $oDaoEmprestimo       = new cl_emprestimo;
      $oDaoEmprestimoAcervo = new cl_emprestimoacervo;

      $oDtDevolucao = new DBDate($oParam->dtDevolucao);
      $oDtRetirada  = new DBDate($oParam->dtRetirada);
      $iUsuario     = db_getsession("DB_id_usuario");

      $oDaoEmprestimo->bi18_codigo    = null;
      $oDaoEmprestimo->bi18_retirada  = $oDtRetirada->getDate();
      $oDaoEmprestimo->bi18_devolucao = $oDtDevolucao->getDate();
      $oDaoEmprestimo->bi18_carteira  = $oParam->aItens[0]->carteira;
      $oDaoEmprestimo->bi18_usuario   = $iUsuario;

      $oDaoEmprestimo->incluir(null);
      if ( $oDaoEmprestimo->erro_status == 0 ) {
        throw new BusinessException("Erro ao salvar a renovação.");
      }

      $iNovoEmprestimo = $oDaoEmprestimo->bi18_codigo;
      foreach ( $oParam->aItens as $oEmprestimo ) {

        $oDaoDevoluacaoAcervo->bi21_codigo           = $oEmprestimo->emprestimoacervo;
        $oDaoDevoluacaoAcervo->bi21_emprestimoacervo = $oEmprestimo->emprestimoacervo;
        $oDaoDevoluacaoAcervo->bi21_entrega          = $oParam->dtRetirada;
        $oDaoDevoluacaoAcervo->bi21_usuario          = $iUsuario;
        $oDaoDevoluacaoAcervo->incluir( $oEmprestimo->emprestimoacervo );

        if ( $oDaoDevoluacaoAcervo->erro_status == '0' ) {
          throw new BusinessException("Erro ao salvar devolução do acervo.");
        }

        $oDaoEmprestimoAcervo->bi19_codigo     = null;
        $oDaoEmprestimoAcervo->bi19_emprestimo = $iNovoEmprestimo;
        $oDaoEmprestimoAcervo->bi19_exemplar   = $oEmprestimo->exemplar;
        $oDaoEmprestimoAcervo->incluir( null );

        if ( $oDaoEmprestimoAcervo->erro_status == '0' ) {
          throw new BusinessException("Erro ao salvar a renovação.");
        }
      }
      $oRetorno->iNovoEmprestimo = $iNovoEmprestimo;
      $oRetorno->sMessage        = "Renovação de empréstimo realizada com sucesso.";

      break;

    case 'temReserva':

      $oRetorno->lTemReserva        = false;
      $oRetorno->aAcervosReservados = array();
      if ( empty($oParam->aAcervos) ) {
        throw new ParameterException("Nenhum acervo informado.");
      }

      if ( empty($oParam->dtDevolucao) ) {
        throw new ParameterException("Data de devolução não informada.");
      }

      $oDtDevolucao   = new DBDate($oParam->dtDevolucao);
      $sAcervos       = implode(",", $oParam->aAcervos);

      /* Verifica a quantidade de reservas existentes para o(s) acervo(s) informado(s) */
      $oDaoReserva    = new cl_reserva();
      $sCamposReserva = 'count(*), bi14_acervo';
      $sWhereReserva  = "bi14_acervo in ({$sAcervos}) and bi14_situacao = 'A' ";
      $sWhereReserva .= " and bi14_datareserva <= '{$oDtDevolucao->getDate()}'";
      $sWhereReserva .= " group by 2 ";
      $sSqlReserva    = $oDaoReserva->sql_query_file(null, $sCamposReserva, '', $sWhereReserva);
      $rsReserva      = db_query($sSqlReserva);

      if ( !$rsReserva ) {
        throw new DBException("Erro ao buscar as reservas.");
      }

      if ( pg_num_rows($rsReserva) == 0 ) {

        $oRetorno->lTemReserva = false;
        break;
      }

      $iLinhas      = pg_num_rows($rsReserva);
      $oDaoExemplar = new cl_exemplar();
      for ($i = 0; $i < $iLinhas; $i ++ ) {

        $oDadosReserva = db_utils::fieldsMemory($rsReserva, $i);

        /* Verifica se há exemplares disponíveis de acordo com o acervo informado. */
        $sWhere  = " NOT exists (SELECT * ";
        $sWhere .= "               FROM emprestimoacervo ";
        $sWhere .= "              WHERE emprestimoacervo.bi19_exemplar = exemplar.bi23_codigo ";
        $sWhere .= "                AND NOT exists (SELECT * ";
        $sWhere .= "                                  FROM devolucaoacervo ";
        $sWhere .= "                                 WHERE devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo)) ";
        $sWhere .= " and bi23_situacao = 'S' ";
        $sWhere .= " and bi06_biblioteca = {$oParam->iBiblioteca} ";
        $sWhere .= " and acervo.bi06_seq = {$oDadosReserva->bi14_acervo} ";

        $sSqlExemplares = $oDaoExemplar->sql_query_dados_exemplar(null, 'bi06_titulo', null,  $sWhere);
        $rsExemplares   = db_query( $sSqlExemplares );

        if ( !$rsExemplares ) {
          throw new DBException("Erro ao buscar os exemplares disponíveis.");
        }

        $iTotalExemplares = pg_num_rows($rsExemplares);
        if (  $oDadosReserva->count > $iTotalExemplares ) {

          $oRetorno->lTemReserva          = true;
          $oRetorno->aAcervosReservados[] = db_utils::fieldsMemory( $rsExemplares, 0 )->bi06_titulo;
        }
      }

      break;
  }

  db_fim_transacao(false);


} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = $eErro->getMessage();
}
$oRetorno->erro = $oRetorno->iStatus == 2;
echo JSON::create()->stringify($oRetorno);