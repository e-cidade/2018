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

 /**
  *  ID: $Id: pes2_bb240cnab002.php,v 1.56 2016/11/25 16:04:15 dbandrio.costa Exp $
  */
require_once modification("fpdf151/pdf.php");
require_once modification("fpdf151/assinatura.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/db_libcaixa_ze.php");
require_once modification("libs/db_libgertxtfolha.php");
require_once modification("libs/db_utils.php");
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);

$cllayouts_bb  = new LayoutBB;
$clfolha       = new cl_folha;
$clpensao      = new cl_pensao;
$clrharqbanco  = new cl_rharqbanco;
$clorctiporec  = new cl_orctiporec;
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("r38_liq");
$clrotulo->label("r38_banco");
$clrotulo->label("r38_agenc");
$clrotulo->label("r38_conta");
$clrotulo->label("r70_descr");
$clrotulo->label("db83_tipoconta");
$sqlerro = false;

db_sel_instit();

$result_arqbanco = $clrharqbanco->sql_record($clrharqbanco->sql_query($rh34_codarq));

if(!$result_arqbanco || pg_num_rows($result_arqbanco) == 0){
  $sqlerro = true;
  $erro_msg = "Arquivo não encontrado";
}
/**********************************************
 *   Soma a quantidade de registros do tipo   *
 *                                            *
 *   0 HEader do Arquivo                      *
 *     1 Header do Lote                       *
 *       3 Registro Detalhe(A e B)            *
 *     5 Trailer do Lote                      *
 *   9 Trailer do Arquivo                     *
 *                                            *
 **********************************************/

$iQuantidadeRegistrosArquivo = 0;

if( $clrharqbanco->numrows > 0 ){

    db_fieldsmemory($result_arqbanco,0);

    include(modification("dbforms/db_layouttxt.php"));

    $posicao       = "A";
    $layoutimprime = 2;
    $idcaixa       = "P";
    $idcliente     = "P";
    $db90_codban   = $rh34_codban;
    $agenciaheader = $rh34_agencia;
    $contaheader   = $rh34_conta;
    $contalote     = $rh34_conta;
    $conveniobanco = trim($rh34_convenio);
    $descrarquivo  = "FOLHA PAGAMENTO"; // Campo somente do layout 3

    $dvagenciaheader         = "0";
    $dvcontaheader           = "0";
    $dvagenciacontaheader    = " ";
    if (trim($rh34_dvagencia) != "") {
      $dvagenciaheader = $rh34_dvagencia[0];
    }
    if (trim($rh34_dvconta)!="") {

      $dvcontaheader  = $rh34_dvconta[0];
      $digitos        = strlen($rh34_dvconta);
      if ($digitos>1) {
        $dvagenciacontaheader = $rh34_dvconta[1];
      }
    }

    $operacaoheader     = substr($contaheader,0,3);
    $contaheader2       = str_pad(trim(substr($contaheader,4,20)),8);
    $dvagencialote      = $dvagenciaheader;
    $dvcontalote        = $dvcontaheader;
    $dvagenciacontalote = $dvagenciacontaheader;

    $datageracao = $datagera;
    $horageracao = date("H").':'.date("i").':'.date("s");

    if (isset($datageracao) && $datageracao!="") {
      $datag = split('-',$datageracao);
      $datag_dia = $datag[2];
      $datag_mes = $datag[1];
      $datag_ano = $datag[0];
    }

    if (isset($datadeposit) && $datadeposit!="") {
      $datad = split('-',$datadeposit);
      $datad_dia = $datad[2];
      $datad_mes = $datad[1];
      $datad_ano = $datad[0];
    }

    $sequencialarq  = $rh34_sequencial;
    $usoprefeitura1 = $rh34_sequencial;

    $adatadegeracao = $datag_ano."-".$datag_mes."-".$datag_dia;
    $datadedeposito = $datad_ano."-".$datad_mes."-".$datad_dia;

    $sequenciaarqui = $rh34_sequencial;
    $versaodoarquiv = "030";

    $paramnome = $datag_mes.$datag_ano."_".$horageracao;
    $nomearquivo = "folha_".$db90_codban."_".$paramnome.".txt";
    $db_layouttxt = new db_layouttxt($layoutimprime,"tmp/".$nomearquivo, $posicao);

    $conveniobanco = trim($rh34_convenio);
    ////// DADOS SOMENTE CNAB240 CEF
    $parametrotransmiss = substr($rh34_convenio,10,2);
    $indicaambcaixa   = "P";
    $indicaambcliente = "P";
    $densidadearquivo = "01600";
    //////


    $iQuantidadeRegistrosArquivo += 1;
    db_setaPropriedadesLayoutTxt($db_layouttxt,1);

 ///// }

}


$rh34_wherefolha = "";
$rh34_wherepensa = "";
$aWherePensao    = array();

if($opt_todosbcos) {
  $rh34_wherefolha .= " r38_banco  = '$rh34_codban'     ";
  if (!DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
    $aWherePensao[] = "r52_codbco = '$rh34_codban'";
  }
  if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
    $aWherePensao[] = "r52_codbco = '$rh34_codban'";
  }
}

if (!DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
  $aWherePensao[] = "r52_anousu = " . db_anofolha();
  $aWherePensao[] = "r52_mesusu = ".db_mesfolha();
}

if(trim($rh34_wherefolha) == '') {
  $rh34_wherefolha = 'r38_liq > 0 ';
} else {
  $rh34_wherefolha .= ' and r38_liq > 0 ';
}

$titrelatorio = "Todos os funcionários";
$titarquivo   = "pagtofuncionarios";
$lPensionista = false;



if ($sqlerro == false) {

  if ($tiparq == 0) {

    $sql = $clfolha->sql_query_gerarqbag(null,"folha.*,cgm.*, length(trim(r38_agenc)) as qtddigitosagencia, contabancaria.*,
                                               r70_descr,
                                               case when trim(translate(r38_conta,'0','')) = '' then '02'
                                                    when (select rh02_fpagto
                                                            from rhpessoalmov
                                                           where rh02_regist = r38_regist
                                                             and rh02_anousu = ".db_anofolha()."
                                                            and rh02_mesusu     = ".db_mesfolha()."
                                                            and rh02_instit     = ".db_getsession("DB_instit").") = 4 then '02'
                                                else '01' end as tipo_pagamento,
                                               length(trim(z01_cgccpf)) as tam,
                                               r38_liq as valorori",
                                              "r38_banco,tipo_pagamento, r38_nome",
                                              "$rh34_wherefolha");

    $result  = $clfolha->sql_record($sql);

    if(!$result || pg_num_rows($result) == 0) {
      $sqlerro  = true;
      $erro_msg = 'Não foram encontrados registros para o tipo de arquivo selecionado.';
    }

    $numrows = pg_num_rows($result);
  } else {

    $titarquivo = "pensaojudicial";
    $titrelatorio = "PENSÃO JUDICIAL ";

    if ($qfolha == 1) {

      $campovalor = " r52_valor+r52_valfer ";

      /**
       * Se a variável $DB_COMPLEMENTAR estiver setada, não deve olhar a tabela pensão, pois ao calcular a
       * folha suplementar a tabela pensão é limpa /zerada / apagada
       */
      if ( !DBPessoal::verificarUtilizacaoEstruturaSuplementar() ) {
        $aWherePensao[] = "(r52_valor > 0 or r52_valfer > 0 )";
      }
    } else if ($qfolha == 2) {

      $campovalor     = " r52_valcom ";
      $aWherePensao[] = "r52_valcom > 0";
    } else if ($qfolha == 3) {

      $campovalor     = " r52_val13 ";
      $aWherePensao[] = "r52_val13 > 0";
    } else if ($qfolha == 4) {

      $campovalor     = " r52_valres ";
      $aWherePensao[] = "r52_valres > 0";
    } else if ($qfolha == 5) {

      $campovalor     = " r52_valor  ";
      $aWherePensao[] = "r52_valor > 0";
    }

    /**
     * Se a variável $DB_COMPLEMENTAR estiver setada, o valor do "r38_liq" será da tabela ("rhhistoricopensao")
     * lembrando que a folha de pagamento precisa ter registros na geração de disco ("folhapagamentogeracao").
     * Quando for 13($qfolha == 3) ou Rescisão($qfolha == 4) ele não utiliza os dados da tabela rhhistoricopensao
     */
    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar() && $qfolha != 3 && $qfolha != 4) {
      switch ($qfolha) {

          case 1:
            $iTipoFolha  = FolhaPagamento::TIPO_FOLHA_SALARIO;
            $sOperacao   = "+r52_valfer";
            break;

          case 2:
            $iTipoFolha  = FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR;
            $sOperacao   = "";
            break;

          case 5:
            $iTipoFolha  = FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR;
            $sOperacao   = "";
            break;
      }

      $sCampo  = "(                                                                                      \n";
      $sCampo .= " SELECT SUM(rh145_valor)                                                               \n";
      $sCampo .= "   FROM rhhistoricopensao                                                              \n";
      $sCampo .= "        INNER JOIN rhfolhapagamento       ON rh141_sequencial = rh145_rhfolhapagamento \n";
      $sCampo .= "        INNER JOIN folhapagamentogeracao  ON rh141_sequencial = rh146_folhapagamento   \n";
      $sCampo .= "  WHERE rh141_tipofolha = {$iTipoFolha}                                                \n";
      $sCampo .= "    and rh141_aberto is false                                                          \n";
      $sCampo .= "    and rh145_pensao    = r52_sequencial                                               \n";
      $sCampo .= " ){$sOperacao}                                                                         \n";

      $campovalor       = $sCampo;
    }


    $rh34_wherepensa = implode(' and ', $aWherePensao);
    $sWhere = "$rh34_wherepensa and $campovalor > 0";

    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
      $sWhere =  $opt_todosbcos ? "$rh34_wherepensa and $campovalor > 0" : "$campovalor > 0";
    }
    $sWhere .= " and r52_anousu = ".DBPessoal::getAnoFolha();
    $sWhere .= " and r52_mesusu = ".DBPessoal::getMesFolha();
    $sql = $clpensao->sql_query_gerarqbag(null,null,null,null,"$campovalor as r38_liq, length(trim(r52_codage)||trim(r52_dvagencia)) as qtddigitosagencia, contabancaria.*,
                                               r52_numcgm as r38_regist,
                                               r52_codbco as r38_banco,
                                               trim(r52_conta)||trim(coalesce(r52_dvconta,'')) as r38_conta,
                                               trim(r52_codage)||trim(coalesce(r52_dvagencia,'')) as r38_agenc,
                                               cgm.*,func.z01_nome as nomefuncionario,
                                               '01' as tipo_pagamento,
                                               r70_descr,
                                               length(trim(cgm.z01_cgccpf)) as tam,
                                               $campovalor as valorori",
                                              "r52_codbco,tipo_pagamento,cgm.z01_nome",
                                               $sWhere
                                              );

    $lPensionista = true;
    $result  = $clpensao->sql_record($sql);

    if(!$result || pg_num_rows($result) == 0) {
      $sqlerro  = true;
      $erro_msg = 'Não foram encontrados registros para o tipo de arquivo selecionado.';
    }

    $numrows = pg_num_rows($result);
  }

  if ($numrows > 0) {

    db_fieldsmemory($result,0);

    $idregistroimpressao = $posicao;
    $nomearquivo_impressao = "/tmp/folha_".$db90_codban."_".$paramnome.".pdf";

    if (!is_writable("/tmp/")) {
      $sqlerro= true;
      $erro_msg = 'Sem permissão de gravar o arquivo. Contate suporte.';
    }

    ///// INICIA IMPRESSÃO DO RELATÓRIO
    $pdf = new PDF();
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->setfillcolor(235);
    $total = 0;
    $alt   = 4;

    $head3 = "ARQUIVO DE PAGAMENTO DA FOLHA";
    $head4 = "SEQUENCIAL DO ARQUIVO:  ".$sequenciaarqui;
    $head5 = "DATA DA GERAÇÃO:  ".db_formatar($datagera,"d").' ÀS '.$horageracao.' HS';
    $head6 = "DATA DO PAGAMENTO:  ".db_formatar($datadedeposito,"d");
    if($opt_todosbcos) {
      $head7 = 'BANCO: '.$rh34_codban.' - '.$db90_descr;
    } else {
      $head7 = 'BANCO: TODOS';
    }

    $finalidadedoc     = "00";
    $codigocompromisso = substr($rh34_convenio,6,4);
    $tipocompromisso   = "02";
    $agencialote       = $agenciaheader;


    $tiposervico = "30";

    $sequencialnolote = 0;

    $troca_header     = 0;

    $quantidadefuncionarios = 0;
    $valortotal             = 0;
    $quantidaderegistarq    = 0;

    /**
     * Monta cabecalho para o PDF
     */
    $pdf->addpage("L");
    $pdf->cell(15,$alt,$RLrh01_regist,1,0,"C",1);
    if ($tiparq < 5) {

      $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
      $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
      $pdf->cell(65,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(65,$alt,$RLr70_descr,1,0,"C",1);
      $pdf->cell(10,$alt,'Banco',1,0,"C",1);
    } else {

      $pdf->cell(65,$alt,"Pensionista",1,0,"C",1);
      $pdf->cell(65,$alt,"Funcionário",1,0,"C",1);
      $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
      $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
    }

    $pdf->cell(13,$alt,$RLr38_agenc,1,0,"C",1);
    $pdf->cell(20,$alt,$RLr38_conta,1,0,"C",1);
    $pdf->cell(20,$alt,$RLdb83_tipoconta,1,0,"C",1);
    $pdf->cell(17,$alt,$RLr38_liq,1,1,"C",1);

    $pdf->ln(3);


    /**
     * Cria um array associativo com os codigos do tipo de conta do
     * sistema para o codigos definidos pelo FEBRABAN.
     * 1 => 01 -> Conta Corrente
     * 2 => 05 -> Conta Poupança
     * 4 => 04 -> Conta Salário
     * @var array Tipos de conta
     */
    $aTiposConta = array(
      '1' => '01',
      '2' => '05',
      '4' => '04',
      '0' => '02'//SEM CONTA/CONTA_ZERADA
    );

    /**
     * Percorre os tipos de conta para
     * verificar se possui dados na tabela folha,
     * se existir monta o Lote com os dados do tipo de conta
     */
    $iLote = 0;

    $valortotallote = 0;

    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar() and $lPensionista) {
      $aWherePensao[] =  $opt_todosbcos ? "$campovalor > 0" : "$campovalor > 0";
    }

    $rh34_wherepensa = implode(' and ', $aWherePensao);

    if ($lPensionista) {

      $sCampos = "case when trim(translate(r38_conta,'0','')) = '' then '02'                     ";
      $sCampos .= "     when db89_db_bancos <> '001'                then '03'                     ";
      $sCampos .= " else '01' end as tipo_pagamento,                                              ";
      $sCampos .= "r52_codbco as r38_banco, r52_codage || r52_dvagencia as r38_agenc, r52_conta || r52_dvconta as r38_conta, $campovalor as r38_liq,cgm.*, length(trim(r38_agenc)) as qtddigitosagencia,
      contabancaria.*,
      r70_descr,
      rh01_regist as r38_regist,
      db89_codagencia,
      db89_digito, ";
      $sCampos .= "length(trim(cgm.z01_cgccpf)) as tam,                                           ";
      $sCampos .= "r38_liq as valorori                                                            ";
      $sWhere   = $rh34_wherepensa;
      if (DBPessoal::verificarUtilizacaoEstruturaSuplementar() && !$opt_todosbcos) {
        $sWhere = preg_replace('/^where/', '',trim($rh34_wherepensa));
      }
      $sWhere .= " and r52_anousu = ".DBPessoal::getAnoFolha();
      $sWhere .= " and r52_mesusu = ".DBPessoal::getMesFolha();
      $sSql     = $clpensao->sql_query_gerarqbag(null, null, null, null, $sCampos, "r52_codbco,db83_tipoconta,tipo_pagamento,cgm.z01_nome", $sWhere);

    } else {

      $sCampos = "folha.*,cgm.*, length(trim(r38_agenc)) as qtddigitosagencia, contabancaria.*, r70_descr,
      db89_codagencia,
      db89_digito, ";
      $sCampos .= "case when trim(translate(r38_conta,'0','')) = '' then '02'                                       ";
      $sCampos .= "     when (select rh02_fpagto                                                                    ";
      $sCampos .= "             from rhpessoalmov                                                                   ";
      $sCampos .= "            where rh02_regist = r38_regist                                                       ";
      $sCampos .= "              and rh02_anousu = " . db_anofolha()                                                 ;
      $sCampos .= "              and rh02_mesusu = " . db_mesfolha()                                                 ;
      $sCampos .= "              and rh02_instit = " . db_getsession("DB_instit").") = 4 then '02'                  ";
      $sCampos .= "     when r38_banco <> '001' then '03'                                                           ";
      $sCampos .= " else '01' end as tipo_pagamento,                                                                ";
      $sCampos .= "length(trim(cgm.z01_cgccpf)) as tam,                                                             ";
      $sCampos .= "r38_liq as valorori                                                                              ";
      $sWhere    = $rh34_wherefolha;

      $sSql      = $clfolha->sql_query_gerarqbag(null, $sCampos, "r38_banco,db83_tipoconta,tipo_pagamento,cgm.z01_nome", $sWhere);
    }

    $rsDados   = db_query($sSql);

    if (!$rsDados) {
      db_redireciona('db_erros.php?fechar=true&db_erro='. urlencode("Não foi possivel emitir o arquivo. Erro ao processar a consulta dos dados."));
      exit;
    }

    if (pg_num_rows($rsDados) == 0) {
      db_redireciona('db_erros.php?fechar=true&db_erro='. urlencode("Não foi possivel emitir o arquivo. Nenhum registro encontrado."));
      exit;
    }

    /**
     * Percorre os servidores
     */

    $troca_header = 0;
    $formalancamento = $tipo_pagamento;
    $iQuantidadeServidoresLote = 0;
    $quantidadetotallote       = 0;
    $iQuantidadeServidoresLote = pg_num_rows($rsDados);


    $lGerarHeaderLote             = true;
    $lGerarTraillerLote           = false;
    $iTotalizadorLote             = 0;

    for ( $iServidor = 0; $iServidor < $iQuantidadeServidoresLote; $iServidor++ ) {

      db_fieldsmemory($rsDados, $iServidor);

      $iTipoConta       = $db83_tipoconta;
      $iCodigoTipoConta = $aTiposConta[$iTipoConta];

      if (   ($iTipoConta == "0" && $tipo_pagamento != "02")
          || ($iTipoConta != "0" && $tipo_pagamento == "02") ) {
        continue;
      }

      //Se está no primeiro laço as variáveis não existem e precisam ser iniciadas
      if (!isset($sTipoContaAnterior)) {
        $sTipoContaAnterior = $db83_tipoconta;
      }
      if (!isset($sBancoAnterior)) {
        $sBancoAnterior    = $r38_banco;
      }

      //Compara o banco e o tipo de conta da repetição anterior
      if ( $r38_banco != '001' ) {

        if ( $sBancoAnterior != $r38_banco && $sBancoAnterior == '001' ) {

          $lGerarHeaderLote   = true;
          $lGerarTraillerLote = true;
        }
      } else {
        if ( $sTipoContaAnterior != $db83_tipoconta ) {

          $lGerarHeaderLote   = true;
          $lGerarTraillerLote = true;
        }
      }

      if ($lGerarTraillerLote) {

        $iTotalizadorLote+=2;
        $quantidadetotallote = $iTotalizadorLote;
        $iQuantidadeRegistrosArquivo += 1;
        db_setaPropriedadesLayoutTxt($db_layouttxt, 4);
        $lGerarTraillerLote = false;
        $valortotallote = 0;
      }


      if ($lGerarHeaderLote) {

        $iLote++;
        $loteservico = $iLote;
        $iQuantidadeRegistrosArquivo += 1;
        $formalancamento = $aTiposConta[$iTipoConta];
        if( $tipo_pagamento == 3 ) {
          $formalancamento = 3;
        }
        db_setaPropriedadesLayoutTxt($db_layouttxt, 2);
        $sequencialnolote = 0;
        $lGerarHeaderLote = false;
        $iTotalizadorLote = 0;
      }

      $iTotalizadorLote+=2;

      if ($tipo_pagamento == "02") {
        $r38_banco = '';
      }
      //////////////////////////////////////////////
      $agencia         = db_formatar(str_replace('.','',str_replace('-','',$db89_codagencia)),'s','0', 5,'e',0);
      $conta           = trim(str_replace(',','',str_replace('.','',str_replace('-','',$db83_conta))));
      $qtddigitosconta = strlen($conta) - 4; /////// -4, pois -1 Ã© do dvconta e -3 do codigooperacao
      $dvconta         = substr($conta,-1);
      $codigooperacao  = substr($conta,0,3);
      $conta           = substr($conta, 3, $qtddigitosconta);
      //////////////////////////////////////////////
      //////////////////////////////////////////////
      $quantidaderegistarq ++;
      if ($troca_header != $tipo_pagamento) {

        if ($troca_header != 0) {
          $quantidadetotallote = $sequencialnolote + 2;
          $sequencialnolote = 0;
          $quantidaderegistarq ++;
        }

        ///// HEADER DO LOTE
        $loteservico     = $iLote;

        ///// FINAL DO HEADER DO LOTE
        $troca_header       = $tipo_pagamento;
        $sequencialnolote   = 0;
        $quantidaderegistarq ++;
      }
      //////////////////////////////////////////////
      // CAMPOS LAYOUT CNAB240
      $sequencialnolote ++;
      $compensacao = "700";
      if($r38_banco == $db90_codban){
        $compensacao = str_repeat('0',3);
      }

      if( $tipo_pagamento == 3 ) {
        $compensacao = "018";
      }

      $agenciapagarT = db_formatar(str_replace('.','',str_replace('-','',$db89_codagencia)),'s','0', 6,'e',0);
      $contasapagarT = db_formatar(str_replace('.','',str_replace('-','',$db83_conta)),'s','0',15,'e',0);

      $agenciapagar = substr($agenciapagarT,0,5);
      $digitoagenci = substr($agenciapagarT,5,1);


      $contasapagar = substr($contasapagarT,0,14)+0;
      $digitocontas = substr($contasapagarT,14,1);

      $contasapagar = db_formatar($contasapagar,'s','0',12,'e',0);

      $bancofavorecido     = $r38_banco;
      $agenciafavorecido   = $agenciapagar;
      $dvagenciafavorecido = $digitoagenci;
      $contafavorecido     = $contasapagar;
      $db83_dvconta        = trim($db83_dvconta);
      $dvcontafavorecido   = $db83_dvconta[0];// $digitocontas[0];

      $dvagenciacontafav   = " ";
      if (strlen($digitocontas) == 2){
          $dvagenciacontafav = $db83_dvconta[1];
      }


      $numerocontrolemov   = $r38_regist;
      $sequencialreg       = $iServidor + 1;
      //////////////////////////////////////////////
      //////////////////////////////////////////////

      $valordebito = $r38_liq;
      $dataprocessamento = $datadedeposito;

      ///// REGISTRO A

      $agenciafavorecido            = $db89_codagencia;
      $dvagenciafavorecido          = $db89_digito[0];
      $contafavorecido              = $db83_conta;
      $dvcontafavorecido            = $db83_dvconta[0];
      $dvagenciacontafav            = isset($db83_dvconta[1]) ? $db83_dvconta[1] : " ";
      $iQuantidadeRegistrosArquivo += 1;
      db_setaPropriedadesLayoutTxt($db_layouttxt, 3, $posicao);

      ///// FINAL DO REGISTRO A
     /**
      * Grava Registro N
      */
      //db_setaPropriedadesLayoutTxt($db_layouttxt, 3, 'B');
      if($tam == 11){
        $tipoinscricaofav = "1";
      }else if($tam == 14){
        $tipoinscricaofav = "2";
      }else{
        $tipoinscricaofav = "3";
      }
      $datavencimento = $datadedeposito;
      $valorvencimento = $r38_liq;
      $sTipoConta      = '';
      switch ($db83_tipoconta) {

        case '1':
          $sTipoConta = "Conta Corrente";
          break;
        case '2':
          $sTipoConta = "Conta Poupança";
          break;
        case '3':
          $sTipoConta = "Conta Aplicação";
          break;
        case '4':
          $sTipoConta = "Conta Salário";
          break;

      }
      ///// REGISTRO B
      ///
      $sequencialnolote++;
      $iQuantidadeRegistrosArquivo += 1;
      db_setaPropriedadesLayoutTxt($db_layouttxt, 3, "");
      ///// FINAL DO REGISTRO B

      if($pdf->gety() > $pdf->h - 30){
        $pdf->addpage("L");
        $pdf->cell(15,$alt,$RLrh01_regist,1,0,"C",1);
        if ($tiparq < 5) {

          $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
          $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
          $pdf->cell(65,$alt,$RLz01_nome,1,0,"C",1);
          if(intval(strlen($RLr70_descr)) > 44) {
            $pdf->cell(65,$alt,substr($RLr70_descr,0,30).'...',1,0,"C",1);
          } else {
            $pdf->cell(65,$alt,$RLr70_descr,1,0,"C",1);
          }
          $pdf->cell(10,$alt,'Banco',1,0,"C",1);
        } else {

          $pdf->cell(65,$alt,"Pensionista",1,0,"C",1);
          $pdf->cell(65,$alt,"Funcionário",1,0,"C",1);
          $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
          $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
        }

        $pdf->cell(13,$alt,$RLr38_agenc,1,0,"C",1);
        $pdf->cell(20,$alt,$RLr38_conta,1,0,"C",1);
        $pdf->cell(20,$alt,$RLdb83_tipoconta,1,0,"C",1);
        $pdf->cell(17,$alt,$RLr38_liq,1,1,"C",1);
        $pdf->ln(3);
      }

      $pdf->setfont('arial','',7);
      $pdf->cell(15,$alt,$r38_regist,1,0,"C",0);

      if ($tiparq < 5) {

        $pdf->cell(15, $alt, $z01_numcgm, 1, 0, "C", 0);
        $pdf->cell(20, $alt, $z01_cgccpf, 1, 0, "C", 0);
        $pdf->cell(65, $alt, $z01_nome  , 1, 0, "L", 0);
        if (intval(strlen($r70_descr)) >= 40){
          $pdf->cell(65, $alt, substr($r70_descr,0,30).'...' , 1, 0, "L", 0);
        } else {
          $pdf->cell(65, $alt, $r70_descr, 1, 0, "L", 0);
        }
        $pdf->cell(10,$alt,$r38_banco,1,0,"R",0);
      } else {

        $pdf->cell(65, $alt, $z01_nome       , 1, 0, "L", 0);
        $pdf->cell(65, $alt, $nomefuncionario, 1, 0, "L", 0);
        $pdf->cell(15, $alt, $z01_numcgm     , 1, 0, "C", 0);
        $pdf->cell(20, $alt, $z01_cgccpf     , 1, 0, "C", 0);
      }

      $pdf->cell(13,$alt,$r38_agenc,1,0,"R",0);
      $pdf->cell(20,$alt,$r38_conta,1,0,"R",0);
      $pdf->cell(20,$alt,$sTipoConta,1,0,"L",0);
      $pdf->cell(17,$alt,db_formatar($r38_liq,'f'),1,1,"R",0);

      $quantidadefuncionarios ++;

      $valortotallote += $r38_liq;

      //Variáveis que guardam banco e tipo de conta para comparar no prÃ³xima repetição do laço
      $sTipoContaAnterior = $db83_tipoconta;
      $sBancoAnterior     = $r38_banco;

      $valortotal        += $r38_liq;
    }

   /**
    * Imprimi ultimo trailer do lote
    */
    $iTotalizadorLote += 2;
    $quantidadetotallote = $iTotalizadorLote;
    $iQuantidadeRegistrosArquivo += 1;
    db_setaPropriedadesLayoutTxt($db_layouttxt, 4);


    $pdf->setfont('arial','b',8);
    $pdf->cell(240,$alt,'TOTAL DE FUNCIONÁRIOS',1,0,"C",1);
    $pdf->cell(20,$alt,$quantidadefuncionarios,1,1,"R",1);

    $pdf->cell(240,$alt,'TOTAL GERAL',1,0,"C",1);
    $pdf->cell(20,$alt,db_formatar($valortotal,'f'),1,1,"R",1);

    $quantidadetotallote = $sequencialnolote + 2;
    $quantidaderegistarq ++;


    // VARIAVEIS PARA TRAILLER CEF
    $quanttrailler = $quantidadefuncionarios + 2;
    $valortrailler = $valortotal;
    $sequencialreg += 1;
    //////////////////////////////////

    ///// TRAILLER DE ARQUIVO
    $loteservico = '9999';

    $iQuantidadeRegistrosArquivo += 1;
    $quantidaderegistarq  = $iQuantidadeRegistrosArquivo;
    $quantidadelotesarq   = $iLote;
    db_setaPropriedadesLayoutTxt($db_layouttxt, 5);
    ///// FINAL DO TRAILLER DE ARQUIVO
    //////////////////////////////////
    $pdf->Output($nomearquivo_impressao,false,true);
  }else{
    $sqlerro = true;
    $erro_msg = "Nenhum registro encontrado. Contate o suporte.";
  }
}

  if($sqlerro == false){
    echo "
    <script>
      parent.js_detectaarquivo('tmp/$nomearquivo','$nomearquivo_impressao');
    </script>
    ";
  }else{
    echo "
    <script>
      parent.js_erro('$erro_msg');
    </script>
    ";
  }

  db_fim_transacao($sql);

?>
