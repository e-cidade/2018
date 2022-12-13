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
require_once('model/contabilidade/contacorrente/AC/ContaCorrenteFonteRecurso.model.php');

class bal_ver {
  var $arq=null;

  function bal_ver($header){
    umask(74);
    $this->arq = fopen("tmp/BAL_VER.TXT",'w+');
    fputs($this->arq,$header);
    fputs($this->arq,"\r\n");
  }

  function processa($instit=1,$data_ini="",$data_fim="",$tribinst,$subelemento="") {
    global $instituicoes,$contador,$nomeinst,$sinal_anterior,$sinal_final;

    $where = " c61_instit in ($instit)";

    $sLancamentosEncerramento = db_getsession('DB_anousu') >= 2014 ? 'false' : 'true';
    $result = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$data_ini,$data_fim,false,$where,'',false, $sLancamentosEncerramento);
    //    db_criatabela($result);exit;

    $contador=0;

    $array_reduzidos_quebra_linha = array();
    $array_teste = array();
    $array_erro = array();

    $iTotalRegistros = pg_num_rows($result);
    $oDataInicial = new DBDate($data_ini);
    $oDataFinal   = new DBDate($data_fim);

    $lJaProcessouDisponibilidadeRecurso = false;
    for($x = 0; $x < $iTotalRegistros; $x++){

      global $instituicoes,$c61_instit,$c61_reduz,$nivel,$estrutural,$saldo_anterior,$saldo_anterior_debito,$saldo_anterior_credito,$saldo_final,$c60_descr,$c61_codigo;
      db_fieldsmemory($result,$x);

      $aLinhaArquivo = array();
      $aLinhasDisponibilidadeRecurso = array();
      if ($lJaProcessouDisponibilidadeRecurso && substr($estrutural, 0, 5) == '82111') {
        continue;
      }

      $aEstruturalDisponibilidade = array('82111', '82110', '82100', '82000', '80000');
      if (in_array(substr($estrutural, 0, 5), $aEstruturalDisponibilidade) && empty($c61_reduz)) {

        $nSaldoBalancete = $saldo_anterior;
        $nSaldoImplantadoCredito = 0;
        if ((substr($estrutural, 0, 5) == '82111' && $saldo_anterior == 0)) {
          $nSaldoImplantadoCredito = self::getValorImplantadoDisponibilidadeRecurso($oDataInicial);
        }
        $saldo_anterior = ($nSaldoBalancete + $nSaldoImplantadoCredito);
        $sinal_anterior = 'C';
        $saldo_final    = (($saldo_anterior + $saldo_anterior_credito) - $saldo_anterior_debito);
        $sinal_final    = $saldo_final < 0 ? 'D' : 'C';
      }

      if ( substr($estrutural, 0, 5) == '82111' && !empty($c61_reduz) && !$lJaProcessouDisponibilidadeRecurso) {

        $oStdDadosVerificacao = db_utils::fieldsMemory($result, $x);
        $aLinhasDisponibilidadeRecurso = self::constroiLinhaDisponibilizacaoRecurso($oStdDadosVerificacao, $instituicoes[$c61_instit], $oDataInicial, $oDataFinal, $instit);
        if (!$aLinhasDisponibilidadeRecurso) {
          $aLinhasDisponibilidadeRecurso = array();
        }
        $lJaProcessouDisponibilidadeRecurso = true;
        unset($oStdDadosVerificacao);

      } else {

        $aLinhaArquivo[] = formatar($estrutural, 20, 'n');
        if ($c61_instit == 0 || empty($c61_instit)) {
          $aLinhaArquivo[] = "0000";
        } else {
          $aLinhaArquivo[] = $instituicoes[$c61_instit];    // aqui ï¿½ o codtrib, da tabela db_config
        }

        $saldo_anterior = abs($saldo_anterior);
        if ($sinal_anterior == 'D') {
          $aLinhaArquivo[] = formatar($saldo_anterior, 13, 'v');
          $aLinhaArquivo[] = formatar(0, 13, 'v');
        } else {
          $aLinhaArquivo[] = formatar(0, 13, 'v');
          $aLinhaArquivo[] = formatar($saldo_anterior, 13, 'v');
        }
        $saldo_anterior_debito = abs($saldo_anterior_debito);
        $saldo_anterior_credito = abs($saldo_anterior_credito);
        $aLinhaArquivo[] = formatar($saldo_anterior_debito, 13, 'v');
        $aLinhaArquivo[] = formatar($saldo_anterior_credito, 13, 'v');

        $saldo_final = abs($saldo_final);
        if ($sinal_final == 'D') {

          $aLinhaArquivo[] = formatar(dbround_php_52($saldo_final, 2), 13, 'v');
          $aLinhaArquivo[] = formatar(0, 13, 'v');
        } else {

          $aLinhaArquivo[] = formatar(0, 13, 'v');
          $aLinhaArquivo[] = formatar(dbround_php_52($saldo_final, 2), 13, 'v');
        }

        if (!(gettype(strpos($c60_descr, "\n")) == "boolean")) {
          $array_reduzidos_quebra_linha[] = $c61_reduz;
          $c60_descr = str_replace("\n", ' ', $c60_descr);
        }

        $aLinhaArquivo[] = formatar($c60_descr, 148, 'c');
        $aLinhaArquivo[] = ($c61_reduz == 0 ? 'S' : 'A');

        // pesquisa nivel da conta
        $aLinhaArquivo[] = self::getNivel($estrutural);
        $aLinhaArquivo[] = self::getSistemaContabil($estrutural);;
        $aLinhaArquivo[] = self::getEscrituracao($estrutural);
        $aLinhaArquivo[] = self::getNaturezaInformacao($estrutural);
        $aLinhaArquivo[] = self::getIndicadorSuperavit($estrutural);

        $aLinhaArquivo[] = '0000';
      }

      if (count($aLinhasDisponibilidadeRecurso) > 0) {

        foreach ($aLinhasDisponibilidadeRecurso as $aLinhaPorRecurso) {

          $contador++;
          $line = implode('', $aLinhaPorRecurso);
          fputs($this->arq,$line);
          fputs($this->arq,"\r\n");
        }

      } else {

        if (count($aLinhaArquivo) > 0) {
          $contador++;
          $line = implode('', $aLinhaArquivo);
          fputs($this->arq, $line);
          fputs($this->arq, "\r\n");
        }
      }

      self::validarEstrutural($array_teste, $x, $estrutural, $c61_reduz, $saldo_anterior, $sinal_anterior);

    }

    $maxnivelanalitico = 0;
    $maxnivelsintetico = 0;
    for ($x=0; $x < sizeof($array_teste); $x++) {

      if (!isset($array_teste[$x][1])) {
        continue;
      }
      if ($array_teste[$x][1] == "A") {
        if ($array_teste[$x][2] > $maxnivelanalitico) {
          $maxnivelanalitico = $array_teste[$x][2];
        }
      }

      if ($array_teste[$x][1] == "S") {
        if ($array_teste[$x][2] > $maxnivelsintetico) {
          $maxnivelsintetico = $array_teste[$x][2];
        }
      }

    }

    $numerro=0;

    /**
     * @todo
     * entender esse laço de verificação de contas e implementar um método que valida essas informações.
     * Todos arquivos do PAD fazem a mesma coisa.
     */
    for ($nivel_atual=$maxnivelsintetico; $nivel_atual > 0; $nivel_atual--) {

      for ($x=0; $x < sizeof($array_teste); $x++) {

        if (!isset($array_teste[$x][1]) || !isset($array_teste[$x][2])) {
          continue;
        }

        if ($array_teste[$x][1] == "S" && $array_teste[$x][2] == $nivel_atual) {

          $estrutural_sintetico = $array_teste[$x][0];
          $soma_sintetico = $array_teste[$x][3];
          $soma_analitico = 0;

          for ($y=$x+1; $y < sizeof($array_teste); $y++) {

            if (!isset($array_teste[$y][1]) || !isset($array_teste[$y][2])) {
              continue;
            }

            if ($array_teste[$y][1] == "S" && $array_teste[$y][2] <= $nivel_atual) {
              break;
            } elseif ($array_teste[$y][1] == "A" && $array_teste[$y][2] > $nivel_atual) {
              $soma_analitico += $array_teste[$y][3];
            }
          }

          if (round($soma_sintetico,2) != round($soma_analitico,2)) {

            $array_erro[$numerro][0] = $estrutural_sintetico;
            $array_erro[$numerro][1] = 2;
            $numerro++;
          }
        }
      }
    }

    if (sizeof($array_erro) > 0) {
      echo "<br><b>PROVAVEIS ERROS NOS ESTRUTURAIS:</b><br>";
      for ($x=0; $x <= sizeof($array_erro); $x++) {
        echo $array_erro[$x][0] . "<br>";
      }
    }


    if (sizeof($array_reduzidos_quebra_linha) > 0) {
      $linha_reduzidos="";
      for ($x=0; $x < sizeof($array_reduzidos_quebra_linha); $x++) {
        $linha_reduzidos .= $array_reduzidos_quebra_linha[$x] . ($x == sizeof($array_reduzidos_quebra_linha) - 1?".":",");
      }
      echo "<font size='1' color='red'><br><b>AVISO: reduzidos de contas com descriï¿½ï¿½o contendo quebras de linha: $linha_reduzidos<br>O sistema retirou as quebras de linha na geracao do TXT, mas vocï¿½ deve acertar isso para nï¿½o ter problemas com outras rotinas, acessando o cadastro do plano de contas em Contabilidade->Cadastros->Plano de Contas->Alteraï¿½ï¿½o.</b><br></font>";
    }
    //  trailer
    $contador = espaco(10-(strlen($contador)),'0').$contador;
    $line = "FINALIZADOR".$contador;
    fputs($this->arq,$line);
    fputs($this->arq,"\r\n");

    fclose($this->arq);

    $teste = "true";

    @db_query("drop table work_pl");

    return $teste;
  }


  public static function validarEstrutural(&$array_teste, $x, $estrutural, $c61_reduz, $saldo_anterior, $sinal_anterior) {

    $sql_nivel = "select fc_nivel_plano2005('$estrutural') as nivel";
    $result_nivel = db_query($sql_nivel) or die($sql_nivel);
    $nivel = db_utils::fieldsMemory($result_nivel, 0)->nivel;

    $array_teste[$x][0]=$estrutural;
    $array_teste[$x][1]=($c61_reduz==0?'S':'A');
    $array_teste[$x][2]=$nivel;
    if ($sinal_anterior=='D') {
      $saldo_anterior = $saldo_anterior*-1;
    }
    $array_teste[$x][3]=$saldo_anterior;
  }


  public static function getNivel($sEstrutural) {

    $sql = "select fc_nivel_plano2005('$sEstrutural') as nivel ";
    $resultsis = db_query($sql);
    return formatar(db_utils::fieldsMemory($resultsis, 0)->nivel, 2, 'n');
  }

  public static function getConsultaContaCorrente($sReduzidos, $sWhere) {

    $sSqlBuscaValor  = "  select x.c19_orctiporec,";
    $sSqlBuscaValor .= "         sum(case when c69_debito in ({$sReduzidos}) then c69_valor else 0 end) as valor_debito,";
    $sSqlBuscaValor .= "         sum(case when c69_credito in ({$sReduzidos}) then c69_valor else 0 end) as valor_credito";
    $sSqlBuscaValor .= "    from ( select distinct conlancamval.*, c19_orctiporec";
    $sSqlBuscaValor .= "             from conlancam ";
    $sSqlBuscaValor .= "                  inner join conlancamval on c69_codlan = c70_codlan ";
    $sSqlBuscaValor .= "                  inner join contacorrentedetalheconlancamval on c28_conlancamval = c69_sequen ";
    $sSqlBuscaValor .= "                  inner join contacorrentedetalhe on c19_sequencial = c28_contacorrentedetalhe ";
    $sSqlBuscaValor .= "            where {$sWhere} ";
    $sSqlBuscaValor .= "          ) as x ";
    $sSqlBuscaValor .= " group by x.c19_orctiporec;";
    return $sSqlBuscaValor;
  }

  /**
   * @param stdClass $oBalanceteVerificacao
   * @return array
   */
  public static function constroiLinhaDisponibilizacaoRecurso(stdClass $oBalanceteVerificacao, $iInstituicao, DBDate $oDataInicial, DBDate $oDataFinal, $sInstituicao) {

    $aWhereReduzidos = array(
      "c60_anousu = {$oDataInicial->getAno()}",
      "c60_estrut = '{$oBalanceteVerificacao->estrutural}'",
      "c61_instit in ({$sInstituicao})"
    );

    $oDaoReduzido = new cl_conplanoreduz();
    $sSqlBuscaReduzidos = $oDaoReduzido->sql_query_analitica(null, null, "distinct array_to_string(array_accum(c61_reduz), ',') as reduzidos ", null, implode(' and ', $aWhereReduzidos));
    $rsBuscaEstrutural = db_query($sSqlBuscaReduzidos);
    if (!$rsBuscaEstrutural) {
      echo "Ocorreu um erro ao buscar o estrutural da conta analitica: {$oBalanceteVerificacao->c61_reduz}."; exit;
    }
    $sReduzidos = db_utils::fieldsMemory($rsBuscaEstrutural, 0)->reduzidos;

    $sWhere  = "     c70_data between '{$oDataInicial->getDate()}' and '{$oDataFinal->getDate()}' ";
    $sWhere .= " and (c69_debito in ({$sReduzidos}) or c69_credito in ({$sReduzidos})) ";
    $sWhere .= " and c19_instit in ({$sInstituicao}) ";
    $sWhere .= " and c19_conplanoreduzanousu = {$oDataInicial->getAno()} ";
    $sWhere .= " and c19_reduz in ({$sReduzidos}) ";
    $sWhere .= " and c19_contacorrente = " . DisponibilidadeFinanceira::CONTA_CORRENTE;
    $sWhere .= " order by c19_orctiporec ";
    $rsBuscaValoresPorRecurso  = db_query(self::getConsultaContaCorrente($sReduzidos, $sWhere));
    $iTotalRegistros = pg_num_rows($rsBuscaValoresPorRecurso);
    if (!$rsBuscaValoresPorRecurso || $iTotalRegistros == 0) {
      return false;
    }

    $aRecursosComMovimento = array();
    $aAgrupamento = array();
    for ($iRow = 0; $iRow < $iTotalRegistros; $iRow++) {

      $aLinhaRecurso    = array();
      $oStdDadosRecurso = db_utils::fieldsMemory($rsBuscaValoresPorRecurso, $iRow);

      $aLinhaRecurso[] = formatar($oBalanceteVerificacao->estrutural, 20, 'n');
      $aLinhaRecurso[] = $iInstituicao;


      $aCamposSaldoAnterior = array(
        'c19_orctiporec',
        'sum(coalesce(c29_credito, 0)) as saldo_credito_anterior',
        'sum(coalesce(c29_debito, 0))  as saldo_debito_anterior'
      );

      $aWhereSaldoAnterior = array(
        "c19_reduz in ({$sReduzidos})",
        "c19_conplanoreduzanousu = {$oDataInicial->getAno()}",
        "c29_anousu = {$oDataInicial->getAno()}",
        "c29_mesusu = 0",
        "c19_orctiporec = {$oStdDadosRecurso->c19_orctiporec}"
      );

      $sWhereSaldoAnterior = implode(' and ', $aWhereSaldoAnterior) . " group by c19_orctiporec ";

      $oDaoContaCorrenteSaldo = new cl_contacorrentesaldo();
      $sSqlBuscaSaldoAnterior = $oDaoContaCorrenteSaldo->sql_query_busca_saldo(implode(',', $aCamposSaldoAnterior), null, $sWhereSaldoAnterior);
      $rsBuscaSaldoAnterior   = db_query($sSqlBuscaSaldoAnterior);
      $oValorRecurso          = db_utils::fieldsMemory($rsBuscaSaldoAnterior, 0);

      /* Saldos Anteriores */
      $aLinhaRecurso[] = formatar(round($oValorRecurso->saldo_debito_anterior , 2), 13, 'v');
      $aLinhaRecurso[] = formatar(round($oValorRecurso->saldo_credito_anterior, 2), 13, 'v');

      /* Saldo Movimentado no Periodo */
      $aLinhaRecurso[] = formatar(round($oStdDadosRecurso->valor_debito,2), 13, 'v');
      $aLinhaRecurso[] = formatar(round($oStdDadosRecurso->valor_credito,2), 13, 'v');

      $nCalculoSaldoFinal = (($oValorRecurso->saldo_credito_anterior + $oStdDadosRecurso->valor_credito) - ($oStdDadosRecurso->valor_debito + $oValorRecurso->saldo_debito_anterior));
      $lDevedora = $nCalculoSaldoFinal < 0;

      $nCalculoSaldoFinal = abs($nCalculoSaldoFinal);
      if ($lDevedora) {
        $aLinhaRecurso[] = formatar(round($nCalculoSaldoFinal, 2), 13, 'v');
        $aLinhaRecurso[] = formatar(0, 13, 'v');
      } else {
        $aLinhaRecurso[] = formatar(0, 13, 'v');
        $aLinhaRecurso[] = formatar(round($nCalculoSaldoFinal, 2), 13, 'v');
      }

      if (!(gettype(strpos($oBalanceteVerificacao->c60_descr, "\n")) == "boolean")) {
        $oBalanceteVerificacao->c60_descr = str_replace("\n", ' ', $oBalanceteVerificacao->c60_descr);
      }
      $aLinhaRecurso[] = formatar($oBalanceteVerificacao->c60_descr, 148, 'c');
      $aLinhaRecurso[] = ($oBalanceteVerificacao->c61_reduz == 0 ? 'S' : 'A');

      $aLinhaRecurso[] = self::getNivel($oBalanceteVerificacao->estrutural);
      $aLinhaRecurso[] = self::getSistemaContabil($oBalanceteVerificacao->estrutural);;
      $aLinhaRecurso[] = self::getEscrituracao($oBalanceteVerificacao->estrutural);
      $aLinhaRecurso[] = self::getNaturezaInformacao($oBalanceteVerificacao->estrutural);
      $aLinhaRecurso[] = self::getIndicadorSuperavit($oBalanceteVerificacao->estrutural);
      $aLinhaRecurso[] = str_pad($oStdDadosRecurso->c19_orctiporec, 4, '0', STR_PAD_LEFT);

      $aRecursosComMovimento[] = $oStdDadosRecurso->c19_orctiporec;
      $aAgrupamento[] = $aLinhaRecurso;
    }

    $oDaoContaCorrenteSaldo = new cl_contacorrentesaldo();
    $aCampos = array(
      'c19_orctiporec',
      'sum(coalesce(c29_credito, 0)) as saldo_credito_anterior',
      'sum(coalesce(c29_debito, 0))  as saldo_debito_anterior'
    );
    $sWhere = implode(
      ' and ',
      array(
        "c29_mesusu = 0",
        "c29_anousu = ".db_getsession('DB_anousu'),
        "c19_orctiporec not in (".implode(',', $aRecursosComMovimento).")",
        "c19_reduz in ({$sReduzidos})"
      )
    );
    $sWhere .= " group by c19_orctiporec order by c19_orctiporec ";
    $sSqlBuscaSaldos = $oDaoContaCorrenteSaldo->sql_query_busca_saldo_implantacao(implode(',', $aCampos), $sWhere, db_getsession('DB_anousu'));
    $rsBuscaSaldos   = db_query($sSqlBuscaSaldos);
    if (!$rsBuscaSaldos) {
      echo "Ocorreu um erro ao buscar os saldos implantados.";exit;
    }
    for ($iRowImplantado = 0; $iRowImplantado < pg_num_rows($rsBuscaSaldos); $iRowImplantado++) {

      $oStdImplantado = db_utils::fieldsMemory($rsBuscaSaldos, $iRowImplantado);
      $aLinhaRecurso    = array();
      $aLinhaRecurso[] = formatar($oBalanceteVerificacao->estrutural, 20, 'n');
      $aLinhaRecurso[] = $iInstituicao;
      /* Saldos Anteriores */
      $aLinhaRecurso[] = formatar(round($oStdImplantado->saldo_debito_anterior , 2), 13, 'v');
      $aLinhaRecurso[] = formatar(round($oStdImplantado->saldo_credito_anterior, 2), 13, 'v');

      /* Saldo Movimentado no Periodo */
      $aLinhaRecurso[] = formatar(round(0,2), 13, 'v');
      $aLinhaRecurso[] = formatar(round(0,2), 13, 'v');

      $nCalculoSaldoFinal = (($oStdImplantado->saldo_credito_anterior) - ($oStdImplantado->saldo_debito_anterior));
      $lDevedora = $nCalculoSaldoFinal < 0;

      $nCalculoSaldoFinal = abs($nCalculoSaldoFinal);
      if ($lDevedora) {
        $aLinhaRecurso[] = formatar(round($nCalculoSaldoFinal, 2), 13, 'v');
        $aLinhaRecurso[] = formatar(0, 13, 'v');
      } else {
        $aLinhaRecurso[] = formatar(0, 13, 'v');
        $aLinhaRecurso[] = formatar(round($nCalculoSaldoFinal, 2), 13, 'v');
      }

      if (!(gettype(strpos($oBalanceteVerificacao->c60_descr, "\n")) == "boolean")) {
        $oBalanceteVerificacao->c60_descr = str_replace("\n", ' ', $oBalanceteVerificacao->c60_descr);
      }
      $aLinhaRecurso[] = formatar($oBalanceteVerificacao->c60_descr, 148, 'c');
      $aLinhaRecurso[] = ($oBalanceteVerificacao->c61_reduz == 0 ? 'S' : 'A');
      $aLinhaRecurso[] = self::getNivel($oBalanceteVerificacao->estrutural);
      $aLinhaRecurso[] = self::getSistemaContabil($oBalanceteVerificacao->estrutural);;
      $aLinhaRecurso[] = self::getEscrituracao($oBalanceteVerificacao->estrutural);
      $aLinhaRecurso[] = self::getNaturezaInformacao($oBalanceteVerificacao->estrutural);
      $aLinhaRecurso[] = self::getIndicadorSuperavit($oBalanceteVerificacao->estrutural);
      $aLinhaRecurso[] = str_pad($oStdImplantado->c19_orctiporec, 4, '0', STR_PAD_LEFT);
      $aAgrupamento[] = $aLinhaRecurso;
    }


    return $aAgrupamento;
  }

  public static function getSistemaContabil($sEstrutural) {

    $sql = "select c52_descrred
              from conplano
                   inner join consistema on c60_codsis = c52_codsis
             where c60_anousu = " . db_getsession("DB_anousu") . " and c60_estrut = '$sEstrutural'";
    $resultsis = db_query($sql);
    $sSistemaContabil = "F";
    if (pg_numrows($resultsis) > 0) {
      $sSistemaContabil = db_utils::fieldsMemory($resultsis, 0)->c52_descrred;
    }

    if (USE_PCASP) {
      $sSistemaContabil = " ";
    }
    return $sSistemaContabil;
  }

  public static function getNaturezaInformacao($sEstrutural) {

    $iEstrtutural = substr($sEstrutural, 0, 1);
    $sNaturezaInformacao = " ";
    switch ($iEstrtutural) {

      case  1:
      case  2:
      case  3:
      case  4:

        $sNaturezaInformacao = "P";
        break;

      case  5:
      case  6:
        $sNaturezaInformacao = "O";
        break;

      case  7:
      case  8:
        $sNaturezaInformacao = "C";
        break;
    }

    if (!USE_PCASP) {
      $sNaturezaInformacao = " ";
    }
    return $sNaturezaInformacao;
  }

  public static function getEscrituracao($estrutural) {

    // definimos escrituraï¿½ï¿½o
    $sSqlEscrituracao = "    select distinct c60_codcon                                             ";
    $sSqlEscrituracao .= "      from conplano                                                        ";
    $sSqlEscrituracao .= "inner join conplanoreduz on conplano.c60_codcon = conplanoreduz.c61_codcon ";
    $sSqlEscrituracao .= "                        and conplano.c60_anousu = conplanoreduz.c61_anousu ";
    $sSqlEscrituracao .= "where c60_estrut = '{$estrutural}'                                         ";

    $rsEscrituracao = db_query($sSqlEscrituracao);
    $sEscrituracao = "N";
    if (pg_num_rows($rsEscrituracao) > 0) {
      $sEscrituracao = "S";
    }

    if (!USE_PCASP) {
      $sEscrituracao = " ";
    }
    return $sEscrituracao;
  }

  public static function getIndicadorSuperavit($estrutural) {

    // definimos superavit
    $sSqlSuperavit = "    select c60_identificadorfinanceiro              ";
    $sSqlSuperavit .= "      from conplano                                 ";
    $sSqlSuperavit .= "     where c60_anousu = " . db_getsession("DB_anousu");
    $sSqlSuperavit .= "       and c60_estrut = '{$estrutural}'             ";
    $rsSuperavit = db_query($sSqlSuperavit);

    if (pg_numrows($rsSuperavit) > 0) {

      $sIndicadorSuperavitFinanceiro = pg_result($rsSuperavit, 0, 'c60_identificadorfinanceiro');
      if ($sIndicadorSuperavitFinanceiro == "N") {
        $sIndicadorSuperavitFinanceiro = "P";
      }
    } else {
      $sIndicadorSuperavitFinanceiro = "P";
    }

    if (!USE_PCASP) {
      $sIndicadorSuperavitFinanceiro = " ";
    }

    return $sIndicadorSuperavitFinanceiro;
  }

  public static function getValorImplantadoDisponibilidadeRecurso(DBDate $oData) {

    $aWhere = array(
      "c29_anousu = {$oData->getAno()}",
      "c29_mesusu = 0",
      "substring(c60_estrut, 1, 5) = '82111'"
    );

    $sCampos = "coalesce(sum(c29_credito), 0) as valor_implantado_credito";
    $oDaoSaldoImplantado = new cl_contacorrentesaldo();
    $sSqlBuscaSaldoImplantado = $oDaoSaldoImplantado->sql_query_busca_saldo($sCampos, null, implode(' and ', $aWhere));
    $rsBuscaSaldo = db_query($sSqlBuscaSaldoImplantado);
    if (!$rsBuscaSaldo) {
      throw new Exception("Ocorreu um erro ao buscar o saldo implantado do conta corrente.");
    }
    return db_utils::fieldsMemory($rsBuscaSaldo, 0)->valor_implantado_credito;
  }
}