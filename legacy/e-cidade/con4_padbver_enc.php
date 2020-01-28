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

class bver_enc {
  var $arq=null;

  function bver_enc($header){
    umask(74);
    $this->arq = fopen("tmp/BVER_ENC.TXT",'w+');
    fputs($this->arq,$header);
    fputs($this->arq,"\r\n");
  }

  function processa($instit=1,$data_ini="",$data_fim="",$tribinst,$subelemento="") {

    global $instituicoes,$contador,$nomeinst,$sinal_anterior,$sinal_final;
    $where = " c61_instit in ($instit)";

    $sLancamentosEncerramento = db_getsession('DB_anousu') >= 2014 ? 'false' : 'true';
    $result = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$data_ini,$data_fim,false,$where,'',false, 'true');

    $contador=0;

    $array_reduzidos_quebra_linha = array();
    $array_teste = array();
    $array_erro = array();

    $iTotalRegistros = pg_num_rows($result);
    $oDataInicial = new DBDate($data_ini);
    $oDataFinal   = new DBDate($data_fim);

    for($x = 0; $x < $iTotalRegistros; $x++){

      global $instituicoes,$c61_instit,$c61_reduz,$nivel,$estrutural,$saldo_anterior,$saldo_anterior_debito,$saldo_anterior_credito,$saldo_final,$c60_descr,$c61_codigo;
      db_fieldsmemory($result,$x);
      $aLinhaArquivo = array();
      $aLinhasDisponibilidadeRecurso = array();
      $lJaProcessouDisponibilidadeRecurso = false;


      $aEstruturalDisponibilidade = array('82111', '82110', '82100', '82000', '80000');
      if (in_array(substr($estrutural, 0, 5), $aEstruturalDisponibilidade) && empty($c61_reduz)) {

        $nSaldoBalancete = $saldo_anterior;
        $nSaldoImplantadoCredito = 0;
        if ((substr($estrutural, 0, 5) == '82111' && $saldo_anterior == 0)) {
          $nSaldoImplantadoCredito = bal_ver::getValorImplantadoDisponibilidadeRecurso($oDataInicial);
        }
        $saldo_anterior = ($nSaldoBalancete + $nSaldoImplantadoCredito);
        $sinal_anterior = 'C';
        $saldo_final    = (($saldo_anterior + $saldo_anterior_credito) - $saldo_anterior_debito);
        $sinal_final    = $saldo_final < 0 ? 'D' : 'C';
      }

      if ( substr($estrutural, 0, 5) == '82111' && !empty($c61_reduz) && !$lJaProcessouDisponibilidadeRecurso) {

        $oStdDadosVerificacao = db_utils::fieldsMemory($result, $x);
        $aLinhasDisponibilidadeRecurso = bal_ver::constroiLinhaDisponibilizacaoRecurso($oStdDadosVerificacao, $instituicoes[$c61_instit], $oDataInicial, $oDataFinal, $instit);
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
          $aLinhaArquivo[] = $instituicoes[$c61_instit];    // aqui é o codtrib, da tabela db_config
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
        $aLinhaArquivo[] = bal_ver::getNivel($estrutural);
        $aLinhaArquivo[] = bal_ver::getSistemaContabil($estrutural);;
        $aLinhaArquivo[] = bal_ver::getEscrituracao($estrutural);
        $aLinhaArquivo[] = bal_ver::getNaturezaInformacao($estrutural);
        $aLinhaArquivo[] = bal_ver::getIndicadorSuperavit($estrutural);

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

      bal_ver::validarEstrutural($array_teste, $x, $estrutural, $c61_reduz, $saldo_anterior, $sinal_anterior);

    }

    $maxnivelanalitico = 0;
    $maxnivelsintetico = 0;
    for ($x=0; $x < sizeof($array_teste); $x++) {
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

        if ($array_teste[$x][1] == "S" && $array_teste[$x][2] == $nivel_atual) {

          $estrutural_sintetico = $array_teste[$x][0];
          $soma_sintetico = $array_teste[$x][3];
          $soma_analitico = 0;

          for ($y=$x+1; $y < sizeof($array_teste); $y++) {

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
      echo "<font size='1' color='red'><br><b>AVISO: reduzidos de contas com descrição contendo quebras de linha: $linha_reduzidos<br>O sistema retirou as quebras de linha na geracao do TXT, mas você deve acertar isso para não ter problemas com outras rotinas, acessando o cadastro do plano de contas em Contabilidade->Cadastros->Plano de Contas->Alteração.</b><br></font>";
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

}