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

$oParam             = JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';


define ('MSG_BIB4_ACERVORPC', 'educacao.biblioteca.bib4_acervoRPC.');
try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "buscaIdiomas":

      $oDaoIdioma = new cl_idioma();
      $sSqlIdioma = $oDaoIdioma->sql_query_file(null, '*', 'bi22_idioma', null);
      $rsIdioma   = db_query( $sSqlIdioma );

      if ( !$rsIdioma ) {
        throw new DBException("Erro ao buscar idiomas.");
      }

      if ( pg_num_rows($rsIdioma) == 0 ) {
        throw new BusinessException("Nenhum idioma encontrado.");
      }

      $oRetorno->aIdiomas = db_utils::getCollectionByRecord( $rsIdioma );
      break;

    case "exemplaresByAcervo":

      if (empty($oParam->iAcervo)) {
        throw new Exception("Código do acervo é necessário.");
      }

      $oDaoExemplare = new cl_exemplar();

      $sCampos  = "  bi23_codigo ";
      $sCampos .= " ,bi23_codbarras ";
      $sCampos .= " ,bi06_titulo  ";
      $sCampos .= " ,trim(bi04_forma) as aquisicao   ";
      $sCampos .= " ,bi23_dataaquisicao as data_aquisicao ";
      $sCampos .= " ,bi23_situacao   ";
      $sCampos .= " ,bi23_exemplar   ";
      $sCampos .= " ,bi09_nome as estante   ";
      $sCampos .= " ,bi27_letra as letra_estante   ";
      $sCampos .= " ,bi20_sequencia as ordem_estante  ";
      $sCampos .= " ,case  ";
      $sCampos .= "   when not exists(select 1 ";
      $sCampos .= "                      from emprestimoacervo  ";
      $sCampos .= "                     where emprestimoacervo.bi19_exemplar = exemplar.bi23_codigo  ";
      $sCampos .= "                       and not exists(select 1 ";
      $sCampos .= "                                        from devolucaoacervo  ";
      $sCampos .= "                                       where devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo  ";
      $sCampos .= "                                     )  ";
      $sCampos .= "                   ) ";
      $sCampos .= "     then 'DISPONÍVEL' ";
      $sCampos .= "   else 'INDISPONÍVEL' ";
      $sCampos .= " end as status";

      $sSqlExemplar = $oDaoExemplare->sql_query(null, $sCampos, null, "bi23_acervo = {$oParam->iAcervo}");
      $rsExemplar   = db_query($sSqlExemplar);

      if ( !$rsExemplar ) {
        throw new Exception('Erro ao buscar exemplares do acervo.');
      }

      $oRetorno->aExemplares = db_utils::getCollectionByRecord($rsExemplar);

      foreach ( $oRetorno->aExemplares as $oExemplar ) {

        $oExemplar->situacao = 'INATIVO';
        if ($oExemplar->bi23_situacao == 'S') {
          $oExemplar->situacao = 'ATIVO';
        }
        $oExemplar->data_aquisicao = db_formatar( $oExemplar->data_aquisicao, 'd' );

        $oExemplar->ordem  = $oExemplar->ordem_estante;
        $oExemplar->ordem .= !empty($oExemplar->letra_estante) ? " - {$oExemplar->letra_estante}" : '';

      }
      break;

    case 'emprestimosAbertos':

      $aWhere = array('not exists (SELECT * FROM devolucaoacervo WHERE devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo )');

      if ( !empty($oParam->iAcervo) ) {
        $aWhere[] = " bi06_seq = {$oParam->iAcervo} ";
      }

      $sCampos  = ' bi19_codigo as emprestimoacervo, bi18_retirada as data_retirada, bi18_devolucao as data_devolucao, ';
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
        throw new DBException("Erro ao buscar os emprestimos.");
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
    case 'buscaAssuntoAcervo':

      if ( empty($oParam->iAcervo) ) {
        throw new ParameterException( _M( MSG_BIB4_ACERVORPC . "informe_acervo") );
      }

      $sCampos     = " bi15_codigo as codigo, bi15_assunto as descricao ";
      $oDaoAssunto = new cl_assunto();
      $sSqlAssunto = $oDaoAssunto->sql_query_file(null, $sCampos, null, " bi15_acervo = {$oParam->iAcervo} ");
      $rsAssunto   = db_query($sSqlAssunto);

      if ( !$rsAssunto ) {
        throw new DBException( _M( MSG_BIB4_ACERVORPC . "erro_buscar_assunto") );
      }

      $aAssuntos = array();
      if ( pg_num_rows($rsAssunto) > 0 ) {
        $aAssuntos = db_utils::getCollectionByRecord($rsAssunto);
      }
      $oRetorno->aAssuntos = $aAssuntos;

      break;

    case 'excluirAssuntoAcervo' :

      if ( empty($oParam->iAssunto) ) {
        throw new ParameterException( _M( MSG_BIB4_ACERVORPC . "informe_assunto") );
      }
      $oDaoAssunto = new cl_assunto();
      $oDaoAssunto->excluir($oParam->iAssunto);

      if ($oDaoAssunto->erro_status == 0 ) {
        throw new DBException($oDaoAssunto->erro_msg);
      }

      $oRetorno->sMessage = _M( MSG_BIB4_ACERVORPC . "assunto_removido_com_sucesso");

      break;
    case 'adicionarAssunto':

      if ( empty($oParam->iAcervo) ) {
        throw new ParameterException( _M( MSG_BIB4_ACERVORPC . "informe_acervo") );
      }
      if ( empty($oParam->sAssunto) ) {
        throw new ParameterException( _M( MSG_BIB4_ACERVORPC . "informe_assunto") );
      }

      $oDaoAssunto               = new cl_assunto();
      $oDaoAssunto->bi15_codigo  = null;
      $oDaoAssunto->bi15_assunto = $oParam->sAssunto;
      $oDaoAssunto->bi15_acervo  = $oParam->iAcervo;

      $oDaoAssunto->incluir(null);

      if ($oDaoAssunto->erro_status == 0 ) {
        throw new DBException($oDaoAssunto->erro_msg);
      }

      $oRetorno->iCodigoAssuntoAdicionado = $oDaoAssunto->bi15_codigo;

      $oRetorno->sMessage = _M( MSG_BIB4_ACERVORPC . "assunto_adicionao_com_sucesso");

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
