<?
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


/**
 * Alterado memória do PHP on the fly, para não estourar a carga dos dados do servidor
 */
ini_set("memory_limit", '-1');

require_once(modification("fpdf151/scpdf.php"));
require_once(modification("fpdf151/impcarne.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("model/convenio.model.php"));
require_once(modification("model/regraEmissao.model.php"));
require_once(modification("model/recibo.model.php"));
require_once(modification("dbforms/db_layouttxt.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_sql.php"));

$oGet    = db_utils::postMemory($_GET);
$iInstit = db_getsession('DB_instit');

if ( $oGet->sLista == '' ) {

  $sMsg = _M('tributario.notificacoes.not2_geratxtlayout002.lista_nao_encontrada');
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
  exit;
}


$complementa_nome_arquivo = "_" . ( @$tipo == "f"?"producao":"teste" ) . "_base_" . db_getsession("DB_base");


$sSufixo      = date("Y-m-d_His",db_getsession("DB_datausu"));
$sNomeArquivo = "tmp/dados_carne_txt_{$sSufixo}{$complementa_nome_arquivo}.txt";
$oLayoutTXT   = new db_layouttxt(82,$sNomeArquivo);
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  >
    <table align="center" width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
          <?php
            db_criatermometro('termometro','Concluido...','blue',1);
            db_atutermometro(1,100,'termometro');
          ?>
        </td>
      </tr>
    </table>
  </body>
</html>

<?

$sSqlDadosCarne  = "select ( select riNumcgm from fc_busca_envolvidos(true,regra,tipo_origem,origem) limit 1) as codigo_cgm,";
$sSqlDadosCarne .= "       ( select rvNome   from fc_busca_envolvidos(true,regra,tipo_origem,origem) limit 1) as nome_contribuinte,";
$sSqlDadosCarne .= "       ( select z01_cgccpf";
$sSqlDadosCarne .= "           from fc_busca_envolvidos(true,regra,tipo_origem,origem)";
$sSqlDadosCarne .= "                inner join cgm on cgm.z01_numcgm = riNumcgm limit 1 ) as cpf,";
$sSqlDadosCarne .= "       case ";
$sSqlDadosCarne .= "          when tipo_origem = 'M' then ( select j34_setor||'-'||j34_quadra||'-'||j34_lote ";
$sSqlDadosCarne .= "                                              from iptubase ";
$sSqlDadosCarne .= "                                                   inner join lote on lote.j34_idbql = iptubase.j01_idbql";
$sSqlDadosCarne .= "                                             where j01_matric = origem) ";
$sSqlDadosCarne .= "          else null ";
$sSqlDadosCarne .= "        end as setor_quadra_lote,";
$sSqlDadosCarne .= "        case
                              when exists ( select 1 from recibounica where recibounica.k00_numpre = y.codigo_arrecadacao ) then true
                              else false
                            end as unica, ";
$sSqlDadosCarne .= "        y.* ";
$sSqlDadosCarne .= "  from (";
$sSqlDadosCarne .= "         select  case";
$sSqlDadosCarne .= "                   when tipo_origem = 'M' then db21_regracgmiptu";
$sSqlDadosCarne .= "                   else db21_regracgmiss";
$sSqlDadosCarne .= "                 end as regra,";
$sSqlDadosCarne .= "                 nomeinst as nome_instituicao,";
$sSqlDadosCarne .= "                 x.*";
$sSqlDadosCarne .= "           from ( select case when k60_tipo = 'M' then ( select k00_matric ";
$sSqlDadosCarne .= "                                                            from arrematric    ";
$sSqlDadosCarne .= "                                                          where arrematric.k00_numpre  = debitos.k22_numpre limit 1)";
$sSqlDadosCarne .= "                         else case when k60_tipo = 'I' then ( select k00_inscr ";
$sSqlDadosCarne .= "                                                                from arreinscr     ";
$sSqlDadosCarne .= "                                                               where arreinscr.k00_numpre   = debitos.k22_numpre limit 1)";
$sSqlDadosCarne .= "                              else (select k00_numcgm ";
$sSqlDadosCarne .= "                                      from arrenumcgm ";
$sSqlDadosCarne .= "                                     where arrenumcgm.k00_numpre = debitos.k22_numpre limit 1 ) end";
$sSqlDadosCarne .= "                         end as origem, ";
$sSqlDadosCarne .= "                         case when k60_tipo = 'N' then 'C' else k60_tipo end as tipo_origem, ";
$sSqlDadosCarne .= "                         k00_tipo     as tipo_debito,";
$sSqlDadosCarne .= "                         k00_descr    as descricao_tipo_debito,";
$sSqlDadosCarne .= "                         ( select v07_parcel ";
$sSqlDadosCarne .= "                             from termo ";
$sSqlDadosCarne .= "                            where v07_numpre = debitos.k22_numpre ";
$sSqlDadosCarne .= "                         )            as numero_parcelamento,";

$dVencimento = implode("-",array_reverse(explode("/",$oGet->sDataVenc)));

// se data preechida
if ( trim($oGet->sDataVenc) != '' ) {

  // se data preechida e utilizacao "v" = vencidas, testa se vencimento do debito menor que a data especificada coloca a data especificada, senao coloca a data do debito
  if ( $iUtilizacao == "v" ) {
    $sSqlDadosCarne .= "                       case when min(debitos.k22_dtvenc) < '{$dVencimento}'::date then '{$dVencimento}'::date else min(debitos.k22_dtvenc) end as vencimento_parcela,";
  // se data preechida e utilizacao "t" = todas, sempre coloca a data especificada
  } else {
    $sSqlDadosCarne .= "                      '".implode("-",array_reverse(explode("/",$oGet->sDataVenc)))."' as vencimento_parcela, ";
  }

// se data nao for preechida, sempre utiliza a data de vencimento original do debito
} else {
  $sSqlDadosCarne .= "                       min(debitos.k22_dtvenc)   as vencimento_parcela,";
}

$sSqlDadosCarne .= "                         debitos.k22_numpar   as parcela,";
$sSqlDadosCarne .= "                         debitos.k22_numpre   as codigo_arrecadacao,";
$sSqlDadosCarne .= "                         sum(debitos.k22_vlrhis)   as valor_historico,";
$sSqlDadosCarne .= "                         sum(debitos.k22_vlrcor)   as valor_corrigido,";
$sSqlDadosCarne .= "                         sum(debitos.k22_juros)    as valor_juros,";
$sSqlDadosCarne .= "                         sum(debitos.k22_multa)    as valor_multa,";
$sSqlDadosCarne .= "                         sum(debitos.k22_desconto) as valor_desconto,";
$sSqlDadosCarne .= "                         ( select k63_notifica";
$sSqlDadosCarne .= "                             from listanotifica";
$sSqlDadosCarne .= "                            where listanotifica.k63_codigo = lista.k60_codigo";
$sSqlDadosCarne .= "                              and listanotifica.k63_numpre = listadeb.k61_numpre limit 1) as numero_notificacao,";
$sSqlDadosCarne .= "                         debitos.k22_exerc    as exercicio,";
$sSqlDadosCarne .= "                         lista.k60_instit     as instituicao";
$sSqlDadosCarne .= "                    from listadeb ";
$sSqlDadosCarne .= "                         inner join lista     on lista.k60_codigo    = listadeb.k61_codigo";
$sSqlDadosCarne .= "                         inner join debitos   on debitos.k22_numpre  = listadeb.k61_numpre";
$sSqlDadosCarne .= "                                             and debitos.k22_numpar  = listadeb.k61_numpar";
$sSqlDadosCarne .= "                                             and debitos.k22_data    = lista.k60_datadeb  ";
$sSqlDadosCarne .= "                                             and debitos.k22_instit  = lista.k60_instit   ";
$sSqlDadosCarne .= "                         inner join tabrec    on tabrec.k02_codigo   = debitos.k22_receit ";
$sSqlDadosCarne .= "                         inner join arretipo  on arretipo.k00_tipo   = debitos.k22_tipo   ";
$sSqlDadosCarne .= "                                             and arretipo.k00_instit = lista.k60_instit   ";
$sSqlDadosCarne .= "                   where listadeb.k61_codigo = {$oGet->sLista}                            ";
//$sSqlDadosCarne .= "                     and listadeb.k61_numpre = 6502581 and listadeb.k61_numpar = 36       ";
$sSqlDadosCarne .= "                     and lista.k60_instit    = {$iInstit}                                 ";

if ( trim($oGet->nValorIni) != '' ) {
  $sSqlDadosCarne .= "                   and debitos.k22_vlrhis >= {$oGet->nValorIni}                         ";
}
if ( trim($oGet->nValorFin) != '' ) {
  $sSqlDadosCarne .= "                   and debitos.k22_vlrhis <= {$oGet->nValorFin}                         ";
}


$sSqlDadosCarne .= "   group by k60_tipo,tipo_debito,descricao_tipo_debito,numero_parcelamento,
                                parcela,codigo_arrecadacao,
                                numero_notificacao,exercicio,instituicao";

$sSqlDadosCarne .= "              ) as x                                                                      ";
$sSqlDadosCarne .= "              inner join db_config  on db_config.codigo = x.instituicao ) as y            ";

switch ($oGet->sOrdem) {
  case "n":
    $sSqlDadosCarne .= " order by nome_contribuinte,origem,tipo_origem,codigo_arrecadacao, parcela ";
  break;
  case "c":
    $sSqlDadosCarne .= " order by codigo_cgm,origem,tipo_origem,codigo_arrecadacao,parcela ";
  break;
  case "m" :
    $sSqlDadosCarne .= " order by origem,tipo_origem,codigo_arrecadacao,parcela ";
  break;
  case "i" :
    $sSqlDadosCarne .= " order by origem,tipo_origem,codigo_arrecadacao,parcela ";
  break;
  case "p":
    $sSqlDadosCarne .= " order by numero_parcelamento,origem,tipo_origem,codigo_arrecadacao,parcela ";
  break;
}

$rsDadosCarne      = db_query($sSqlDadosCarne) or die($sSqlDadosCarne);
$iLinhasDadosCarne = pg_num_rows($rsDadosCarne);
$iTipoDebitoAtual  = '';
$iQtdOrigem        = 0;
$sOrigemAntes      = '';

if ( $iLinhasDadosCarne > 0 ) {

  db_inicio_transacao();

  for ( $iInd=0; $iInd < $iLinhasDadosCarne; $iInd++ ) {

    $oDadosCarne = db_utils::fieldsMemory($rsDadosCarne,$iInd);
    db_atutermometro($iInd,$iLinhasDadosCarne,'termometro');

    $sOrigem = $oDadosCarne->tipo_origem."-".$oDadosCarne->origem."-".$oDadosCarne->codigo_arrecadacao;

    if ( $sOrigem != $sOrigemAntes ) {
      $iQtdOrigem++;
      $lGeraUnica = true;
    }

    $sOrigemAntes = $sOrigem;

    if ( trim($oGet->iQtd) != '' ) {
      if ( $iQtdOrigem > $oGet->iQtd ) {
        break;
      }
    }

    // Seta valor do Header de Arquivo
    $oHeaderArquivo = new stdClass();
    $oHeaderArquivo->ident         = 1;
    $oHeaderArquivo->nome_cedente  = $oDadosCarne->nome_instituicao;

    $sSqlValidaArrecad = " select *
                             from arrecad
                            where k00_numpre = {$oDadosCarne->codigo_arrecadacao}
                              and k00_numpar = {$oDadosCarne->parcela} limit 1";

    $rsValidaArrecad = db_query($sSqlValidaArrecad);

    if ( pg_num_rows($rsValidaArrecad) == 0 ) {
      continue;
    }

    if ( $oDadosCarne->tipo_debito != $iTipoDebitoAtual ) {

      $iTipoDebitoAtual = $oDadosCarne->tipo_debito;

      try {
        $oRegraEmissao = new regraEmissao($iTipoDebitoAtual,
                                          16,
                                          $iInstit,
                                          date("Y-m-d", db_getsession("DB_datausu")),
                                          db_getsession('DB_ip'));
      } catch (Exception $eExeption){
        db_redireciona("db_erros.php?fechar=true&db_erro=2 - {$eExeption->getMessage()}");
        exit;
      }

    }

    // Geração das Únicas *******************************************************************************************/

    if ( $oDadosCarne->unica == 't' && $lGeraUnica ) {

      $sSqlConsultaUnica  = " select *,                                                  ";
      $sSqlConsultaUnica .= "        substr(fc_calcula, 2,13)::float8 as vlrhis,         ";
      $sSqlConsultaUnica .= "        substr(fc_calcula,15,13)::float8 as vlrcor,         ";
      $sSqlConsultaUnica .= "        substr(fc_calcula,28,13)::float8 as vlrjuros,       ";
      $sSqlConsultaUnica .= "        substr(fc_calcula,41,13)::float8 as vlrmulta,       ";
      $sSqlConsultaUnica .= "        substr(fc_calcula,54,13)::float8 as vlrdesconto,    ";
      $sSqlConsultaUnica .= "        ( substr(fc_calcula,15,13)::float8                  ";
      $sSqlConsultaUnica .= "         +substr(fc_calcula,28,13)::float8                  ";
      $sSqlConsultaUnica .= "         +substr(fc_calcula,41,13)::float8                  ";
      $sSqlConsultaUnica .= "         -substr(fc_calcula,54,13)::float8 ) as vlrtotal    ";
      $sSqlConsultaUnica .= "   from ( select recibounica.k00_numpre,                    ";
      $sSqlConsultaUnica .= "                 recibounica.k00_dtvenc as dtvencunic,      ";
      $sSqlConsultaUnica .= "                 recibounica.k00_dtoper as dtoperunic,      ";
      $sSqlConsultaUnica .= "                 recibounica.k00_percdes,                   ";
      $sSqlConsultaUnica .= "                 fc_calcula(recibounica.k00_numpre,         ";
      $sSqlConsultaUnica .= "                            0,                              ";
      $sSqlConsultaUnica .= "                            0,                              ";
      $sSqlConsultaUnica .= "                            recibounica.k00_dtvenc,         ";
      $sSqlConsultaUnica .= "                            recibounica.k00_dtvenc,         ";
      $sSqlConsultaUnica .= "                            ".db_getsession("DB_anousu").") ";
      $sSqlConsultaUnica .= "            from recibounica                                ";
      $sSqlConsultaUnica .= "           where recibounica.k00_numpre = {$oDadosCarne->codigo_arrecadacao} ";

      if ( trim($oGet->sDataVenc) != '' ) {
        // Caso seja informado a data de vencimento, deve ser retornado somente as únicas que não estejam vencidas até a data informada.
        $sSqlConsultaUnica .= "           and recibounica.k00_dtvenc >= '{$dVencimento}'::date  ";
      } else {
        // Caso não seja informado a data de vencimento, deve ser retornado somente as únicas que não estejam vencidas até a data de hoje.
        $sSqlConsultaUnica .= "           and recibounica.k00_dtvenc >= '".date('Y-m-d',db_getsession('DB_datausu'))."'::date  ";
      }

      $sSqlConsultaUnica .= "  ) as unica ";

      $rsConsultaUnica    = db_query($sSqlConsultaUnica);
      $iLinhasUnica       = pg_num_rows($rsConsultaUnica);

      if ( $iLinhasUnica > 0 ) {

        for ( $iIndUnica=0; $iIndUnica < $iLinhasUnica; $iIndUnica++ ) {

          $oDadosUnica = db_utils::fieldsMemory($rsConsultaUnica,$iIndUnica);

          try {

            // Gera Recibo
            $oRecibo = new recibo(2, $oDadosCarne->codigo_cgm, 21);
            $oRecibo->addNumpre($oDadosUnica->k00_numpre,0);

            $sHistorico  = "Numpre:{$oDadosUnica->k00_numpre} ";
            $sHistorico .= "Numpar:0                          ";
            $sHistorico .= "Tipo Débito: {$oDadosCarne->tipo_debito}-{$oDadosCarne->descricao_tipo_debito} ";

            $oRecibo->setHistorico($sHistorico);
            $oRecibo->setDataVencimentoRecibo($oDadosUnica->dtvencunic);
            $oRecibo->setDataRecibo($oDadosUnica->dtvencunic);
            $oRecibo->emiteRecibo();

            if ( $oDadosUnica->vlrtotal == 0 ) {
              $iTercDig = 7;
              $sVlrBar  = '00000000000';
            } else {
              $iTercDig = 7;
              $sVlrBar  = db_formatar(str_replace('.','',str_pad(number_format($oDadosUnica->vlrtotal,2,"","."),11,"0",STR_PAD_LEFT)),'s','0',11,'e');
            }

            // Gera Código de Barras
            $oConvenio = new convenio($oRegraEmissao->getConvenio(),
                                      $oRecibo->getNumpreRecibo(),
                                      '0',
                                      $oDadosUnica->vlrtotal,
                                      $sVlrBar,
                                      $oDadosCarne->vencimento_parcela,
                                      $iTercDig);

            // Convênio SICOB
            if ( $oRegraEmissao->getCadTipoConvenio() == 5 ) {
              $aNossoNumero    = explode("-",$oConvenio->getNossoNumero());
              $sNossoNumero    = $aNossoNumero[0];
              $sDigNossoNumero = $aNossoNumero[1];
            } else {
              $sNossoNumero    = $oConvenio->getNossoNumero();
              $sDigNossoNumero = '';
            }


          } catch (Exception $eException) {
            db_redireciona("db_erros.php?fechar=true&db_erro={$eException->getMessage()}");
            exit;
          }

          // Seta valores para os detalhes do lote
          $oDetalheLote = new stdClass();
          $oDetalheLote->ident                 = 3;
          $oDetalheLote->tipo_debito           = $oDadosCarne->tipo_debito;
          $oDetalheLote->descricao_tipo_debito = $oDadosCarne->descricao_tipo_debito;
          $oDetalheLote->numero_parcelamento   = $oDadosCarne->numero_parcelamento;
          $oDetalheLote->vencimento_parcela    = db_formatar($oDadosUnica->dtvencunic,'d');
          $oDetalheLote->parcela               = 0;
          $oDetalheLote->nosso_numero          = $sNossoNumero;
          $oDetalheLote->dg_nosso_numero       = $sDigNossoNumero;
          $oDetalheLote->codigo_arrecadacao    = str_pad($oRecibo->getNumpreRecibo(),8,"0",STR_PAD_LEFT)."000";
          $oDetalheLote->codigo_barras         = $oConvenio->getLinhaDigitavel().",".$oConvenio->getCodigoBarra();
          $oDetalheLote->valor_historico       = $oDadosUnica->vlrhis;
          $oDetalheLote->valor_corrigido       = $oDadosUnica->vlrcor;
          $oDetalheLote->valor_juros           = $oDadosUnica->vlrjuros;
          $oDetalheLote->valor_multa           = $oDadosUnica->vlrmulta;
          $oDetalheLote->valor_desconto        = $oDadosUnica->vlrdesconto;
          $oDetalheLote->valor_total_parcela   = $oDadosUnica->vlrtotal;
          $oDetalheLote->numero_notificacao    = $oDadosCarne->numero_notificacao;
          $oDetalheLote->exercicios            = $oDadosCarne->exercicio;

          $aDadosTxt[$sOrigem]['aDetalhes'][] = $oDetalheLote;
        }
      }
      $lGeraUnica = false;
    }

    // Fim da Geração das Únicas ************************************************************************************/

    try {

      $oRecibo = new recibo(2, $oDadosCarne->codigo_cgm, 21);
      $oRecibo->addNumpre($oDadosCarne->codigo_arrecadacao,$oDadosCarne->parcela);

      $sHistorico  = "Numpre:{$oDadosCarne->codigo_arrecadacao} ";
      $sHistorico .= "Numpar:{$oDadosCarne->parcela} ";
      $sHistorico .= "Tipo Débito: {$oDadosCarne->tipo_debito}-{$oDadosCarne->descricao_tipo_debito} ";

      $oRecibo->setHistorico($sHistorico);
      $oRecibo->setDataVencimentoRecibo($oDadosCarne->vencimento_parcela);
      $oRecibo->setDataRecibo($oDadosCarne->vencimento_parcela);
      $oRecibo->emiteRecibo();

    } catch ( Exception $eExeption ){

      $oParms           = new stdClass();
      $oParms->iNumpre  = $oDadosCarne->codigo_arrecadacao;
      $oParms->iParcela = $oDadosCarne->parcela;
      $oParms->sErro    = $eExeption->getMessage();

      $sMsg = _M('tributario.notificacoes.not2_geratxtlayout002.nunpre_parcela_exeption', $oParms);
      db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
      exit;
    }

    $rsCalcula = debitos_numpre( $oDadosCarne->codigo_arrecadacao,
                                 0,
                                 $oDadosCarne->tipo_debito,
                                 db_getsession("DB_datausu"),
                                 db_getsession("DB_anousu"),
                                 $oDadosCarne->parcela,
                                 true );

    if ( !$rsCalcula ) {

      $sMsg = _M('tributario.notificacoes.not2_geratxtlayout002.erro_buscar_debitos');
      db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
      exit;
    }

    $oCalcula    = db_utils::fieldsMemory($rsCalcula,0);
    $nValor_total_parcela = $oCalcula->total;

    if ( $nValor_total_parcela == 0 ) {
      $iTercDig = 7;
      $sVlrBar  = '00000000000';
    } else {
      $iTercDig = 7;
      $sVlrBar  = db_formatar(str_replace('.','',str_pad(number_format($nValor_total_parcela,2,"","."),11,"0",STR_PAD_LEFT)),'s','0',11,'e');
    }

    try {
      $oConvenio = new convenio($oRegraEmissao->getConvenio(),
                                $oRecibo->getNumpreRecibo(),
                                0,
                                $nValor_total_parcela,
                                $sVlrBar,
                                $oDadosCarne->vencimento_parcela,
                                $iTercDig);

    } catch (Exception $eExeption){
      db_redireciona("db_erros.php?fechar=true&db_erro=4 - {$eExeption->getMessage()}");
      exit;
    }

      // Convênio SICOB
    if ( $oRegraEmissao->getCadTipoConvenio() == 5 ) {
      $aNossoNumero    = explode("-",$oConvenio->getNossoNumero());
      $sNossoNumero    = $aNossoNumero[0];
      $sDigNossoNumero = $aNossoNumero[1];
    } else {
      $sNossoNumero    = $oConvenio->getNossoNumero();
      $sDigNossoNumero = '';
    }

    // Seta valores para os detalhes do lote
    $oDetalheLote = new stdClass();
    $oDetalheLote->ident                 = 3;
    $oDetalheLote->tipo_debito           = $oDadosCarne->tipo_debito;
    $oDetalheLote->descricao_tipo_debito = $oDadosCarne->descricao_tipo_debito;
    $oDetalheLote->numero_parcelamento   = $oDadosCarne->numero_parcelamento;
    $oDetalheLote->vencimento_parcela    = db_formatar($oDadosCarne->vencimento_parcela,'d');
    $oDetalheLote->parcela               = $oDadosCarne->parcela;
    $oDetalheLote->nosso_numero          = $sNossoNumero;
    $oDetalheLote->dg_nosso_numero       = $sDigNossoNumero;
    $oDetalheLote->codigo_arrecadacao    = str_pad($oRecibo->getNumpreRecibo(),8,"0",STR_PAD_LEFT)."000";
    $oDetalheLote->codigo_barras         = $oConvenio->getLinhaDigitavel().",".$oConvenio->getCodigoBarra();
    $oDetalheLote->valor_historico       = $oCalcula->vlrhis;
    $oDetalheLote->valor_corrigido       = $oCalcula->vlrcor;
    $oDetalheLote->valor_juros           = $oCalcula->vlrjuros;
    $oDetalheLote->valor_multa           = $oCalcula->vlrmulta;
    $oDetalheLote->valor_desconto        = $oCalcula->vlrdesconto;
    $oDetalheLote->valor_total_parcela   = $oCalcula->total;
    $oDetalheLote->numero_notificacao    = $oDadosCarne->numero_notificacao;
    $oDetalheLote->exercicios            = $oDadosCarne->exercicio;

    if ( isset($aDadosTxt[$sOrigem]) ) {

      $aDadosTxt[$sOrigem]['aDetalhes'][] = $oDetalheLote;

    } else {

      $oHeaderLote = new stdClass();
      $oHeaderLote->ident              = 2;
      $oHeaderLote->codigo_cgm         = $oDadosCarne->codigo_cgm;
      $oHeaderLote->nome_contribuinte  = $oDadosCarne->nome_contribuinte;
      $oHeaderLote->cpf                = $oDadosCarne->cpf;
      $oHeaderLote->origem             = $oDadosCarne->origem;
      $oHeaderLote->tipo_origem        = $oDadosCarne->tipo_origem;
      $oHeaderLote->setor_quadra_lote  = $oDadosCarne->setor_quadra_lote;
      $oHeaderLote->data_processamento = date('d/m/Y',db_getsession('DB_datausu'));
      $oHeaderLote->codigo_banco       = $oConvenio->getCodBanco();
      $oHeaderLote->dg_codigo_banco    = '';
      $oHeaderLote->agencia            = $oConvenio->getCodAgencia();
      $oHeaderLote->dg_agencia         = $oConvenio->getDigitoAgencia();
      $oHeaderLote->cedente            = $oConvenio->getCedente();
      $oHeaderLote->dg_cedente         = $oConvenio->getDigitoCedente();


      if ( trim($oDadosCarne->tipo_origem) == 'M' ) {

        $sSqlEnderecoEntrega  = " select substr(fc_iptuender,001,40) as logradouro, ";
        $sSqlEnderecoEntrega .= "        substr(fc_iptuender,042,10) as numero,     ";
        $sSqlEnderecoEntrega .= "        substr(fc_iptuender,053,20) as complemento,";
        $sSqlEnderecoEntrega .= "        substr(fc_iptuender,074,40) as bairro,     ";
        $sSqlEnderecoEntrega .= "        substr(fc_iptuender,115,40) as municipio,  ";
        $sSqlEnderecoEntrega .= "        substr(fc_iptuender,156,02) as uf,         ";
        $sSqlEnderecoEntrega .= "        substr(fc_iptuender,159,08) as cep,        ";
        $sSqlEnderecoEntrega .= "        substr(fc_iptuender,168,20) as cxpostal    ";
        $sSqlEnderecoEntrega .= "   from fc_iptuender({$oDadosCarne->origem})    ";

        $rsEnderecoEntrega    = db_query($sSqlEnderecoEntrega);

        if ( pg_num_rows($rsEnderecoEntrega) > 0 ) {
          $oEnderecoEntrega = db_utils::fieldsMemory($rsEnderecoEntrega,0);
          $sLogradouro  = $oEnderecoEntrega->logradouro;
          $sNumero      = $oEnderecoEntrega->numero;
          $sComplemento = $oEnderecoEntrega->complemento;
          $sBairro      = $oEnderecoEntrega->bairro;
          $sCidade      = $oEnderecoEntrega->municipio;
          $sUF          = $oEnderecoEntrega->uf;
          $sCEP         = $oEnderecoEntrega->cep;
          $sCaixaPostal = $oEnderecoEntrega->cxpostal;
        }

      } else {

        $sSqlEnderecoCGM  = " select z01_ender    as logradouro, ";
        $sSqlEnderecoCGM .= "        z01_numero   as numero,     ";
        $sSqlEnderecoCGM .= "        z01_compl    as complemento,";
        $sSqlEnderecoCGM .= "        z01_bairro   as bairro,     ";
        $sSqlEnderecoCGM .= "        z01_munic    as municipio,  ";
        $sSqlEnderecoCGM .= "        z01_uf       as uf,         ";
        $sSqlEnderecoCGM .= "        z01_cep      as cep,        ";
        $sSqlEnderecoCGM .= "        z01_cxpostal as cxpostal    ";
        $sSqlEnderecoCGM .= "   from cgm                         ";
        $sSqlEnderecoCGM .= "  where z01_numcgm = {$oDadosCarne->codigo_cgm}";

        $rsEnderecoCGM    = db_query($sSqlEnderecoCGM);

        if ( pg_num_rows($rsEnderecoCGM) > 0 ) {

          $oEnderecoCGM = db_utils::fieldsMemory($rsEnderecoCGM,0);
          $sLogradouro  = $oEnderecoCGM->logradouro;
          $sNumero      = $oEnderecoCGM->numero;
          $sComplemento = $oEnderecoCGM->complemento;
          $sBairro      = $oEnderecoCGM->bairro;
          $sCidade      = $oEnderecoCGM->municipio;
          $sUF          = $oEnderecoCGM->uf;
          $sCEP         = $oEnderecoCGM->cep;
          $sCaixaPostal = $oEnderecoCGM->cxpostal;
        }
      }

      $oHeaderLote->logradouro    = $sLogradouro;
      $oHeaderLote->numero_imovel = $sNumero;
      $oHeaderLote->complemento   = $sComplemento;
      $oHeaderLote->bairro        = $sBairro;
      $oHeaderLote->cidade        = $sCidade;
      $oHeaderLote->uf            = $sUF;
      $oHeaderLote->cep           = $sCEP;
      $oHeaderLote->caixa_postal  = $sCaixaPostal;

      $aDadosTxt[$sOrigem]['oHeaderLote'] = $oHeaderLote;
      $aDadosTxt[$sOrigem]['aDetalhes'][] = $oDetalheLote;

    }
  }

  db_fim_transacao(false);

} else {

  if (db_utils::inTransaction()) {
    db_fim_transacao(true);
  }

  $sMsg = _M('tributario.notificacoes.not2_geratxtlayout002.nenhum_registro_encontrado');
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
  exit;
}


$iQtdTotal = 0;

$oLayoutTXT->setByLineOfDBUtils($oHeaderArquivo,1);

foreach ( $aDadosTxt as $iOrigem => $aLinhasArquivo ) {

  $nVlrTotalHist     = 0;
  $nVlrTotalCorr     = 0;
  $nVlrTotalJuros    = 0;
  $nVlrTotalMulta    = 0;
  $nVlrTotalDesconto = 0;
  $iQtdRegistrosLote = 0;

  $oLayoutTXT->setByLineOfDBUtils($aLinhasArquivo['oHeaderLote'],2);

  foreach ($aLinhasArquivo['aDetalhes'] as $iInd => $oDetalheLote1 ) {

    $nVlrTotalHist     += $oDetalheLote1->valor_historico;
    $nVlrTotalCorr     += $oDetalheLote1->valor_corrigido;
    $nVlrTotalJuros    += $oDetalheLote1->valor_juros;
    $nVlrTotalMulta    += $oDetalheLote1->valor_multa;
    $nVlrTotalDesconto += $oDetalheLote1->valor_desconto;
    $iQtdRegistrosLote++;
    $iQtdTotal++;

    $oLayoutTXT->setByLineOfDBUtils($oDetalheLote1,3);

  }

  $nTotalGeral  = $nVlrTotalCorr + $nVlrTotalJuros + $nVlrTotalMulta + $nVlrTotalDesconto;

  $oTrailerLote = new stdClass();
  $oTrailerLote->ident                 = 4;
  $oTrailerLote->valor_total_historico = $nVlrTotalHist;
  $oTrailerLote->valor_total_corrigido = $nVlrTotalCorr;
  $oTrailerLote->valor_total_juros     = $nVlrTotalJuros;
  $oTrailerLote->valor_total_multa     = $nVlrTotalMulta;
  $oTrailerLote->valor_total_desconto  = $nVlrTotalDesconto;
  $oTrailerLote->valor_total           = $nTotalGeral;
  $oTrailerLote->total_registros_lote  = $iQtdRegistrosLote;

  $oLayoutTXT->setByLineOfDBUtils($oTrailerLote,4);

}

$oTrailerArquivo = new stdClass();
$oTrailerArquivo->ident           = 5;
$oTrailerArquivo->total_registros = $iQtdTotal;

$oLayoutTXT->setByLineOfDBUtils($oTrailerArquivo,5);

$sArqLayoutHeaderArq    = "tmp/layout_header_arquivo_{$sSufixo}.txt";
$sArqLayoutHeaderLote   = "tmp/layout_header_lote_{$sSufixo}.txt";
$sArqLayoutDetalheLote  = "tmp/layout_detalhe_lote_{$sSufixo}.txt";
$sArqLayoutTraillerLote = "tmp/layout_trailler_lote_{$sSufixo}.txt";
$sArqLayoutTraillerArq  = "tmp/layout_trailler_arquivo_{$sSufixo}.txt";

$oLayoutTXT->gerarArquivoLeiaute($sArqLayoutHeaderArq,278);    // Header Arquivo
$oLayoutTXT->gerarArquivoLeiaute($sArqLayoutHeaderLote,280);   // Header Lote
$oLayoutTXT->gerarArquivoLeiaute($sArqLayoutDetalheLote,281);  // Detalhes Lote
$oLayoutTXT->gerarArquivoLeiaute($sArqLayoutTraillerLote,282); // Trailler Lote
$oLayoutTXT->gerarArquivoLeiaute($sArqLayoutTraillerArq,283);  // Trailler Arquivo

echo "<script>";

$sListaArquivos  = "{$sNomeArquivo}# Download do Arquivo - {$sNomeArquivo}|";
$sListaArquivos .= "{$sArqLayoutHeaderArq}# Download do Arquivo - {$sArqLayoutHeaderArq}|";
$sListaArquivos .= "{$sArqLayoutHeaderLote}# Download do Arquivo - {$sArqLayoutHeaderLote}|";
$sListaArquivos .= "{$sArqLayoutDetalheLote}# Download do Arquivo - {$sArqLayoutDetalheLote}|";
$sListaArquivos .= "{$sArqLayoutTraillerLote}# Download do Arquivo - {$sArqLayoutTraillerLote}|";
$sListaArquivos .= "{$sArqLayoutTraillerArq}# Download do Arquivo - {$sArqLayoutTraillerArq}|";

echo "  listagem = '{$sListaArquivos}';";
echo "  parent.js_imprimeLista(listagem);";
echo "</script>";

?>