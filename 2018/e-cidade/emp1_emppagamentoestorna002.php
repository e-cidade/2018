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


require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

include("libs/db_libcontabilidade.php");

include("libs/db_utils.php");
//------------------------------------------------------
//   Arquivos que verificam se o boletim já foi liberado ou naum
include("classes/db_boletim_classe.php");
$clverficaboletim = new cl_verificaboletim(new cl_boletim);
//------------------------------------------------------

include("libs/db_liborcamento.php");
include("classes/db_orcdotacao_classe.php");
include("classes/db_empempenho_classe.php");
include("classes/db_empelemento_classe.php");
include("classes/db_pagordem_classe.php");
include("classes/db_pagordemele_classe.php");
$clpagordem = new cl_pagordem;
$clpagordemele = new cl_pagordemele;
$clempempenho = new cl_empempenho;
$clempelemento = new cl_empelemento;
$clorcdotacao = new cl_orcdotacao;

include("classes/db_conlancam_classe.php");
include("classes/db_conlancamele_classe.php");
include("classes/db_conlancampag_classe.php");
include("classes/db_conlancamcgm_classe.php");
include("classes/db_conparlancam_classe.php");
include("classes/db_conlancamemp_classe.php");
include("classes/db_conlancamval_classe.php");
include("classes/db_conlancamdot_classe.php");
include("classes/db_conlancamdoc_classe.php");
include("classes/db_conlancamcompl_classe.php");
include("classes/db_saltes_classe.php");
include("classes/db_conplanoreduz_classe.php");
include("classes/db_conlancamlr_classe.php");
include("classes/db_conlancamord_classe.php");

$clconlancam = new cl_conlancam;
$clconlancamele = new cl_conlancamele;
$clconlancampag = new cl_conlancampag;
$clconlancamcompl = new cl_conlancamcompl;
$clconlancamcgm = new cl_conlancamcgm;
$clconparlancam = new cl_conparlancam;
$clconlancamemp = new cl_conlancamemp;
$clconlancamval = new cl_conlancamval;
$clconlancamdot = new cl_conlancamdot;
$clconlancamdoc = new cl_conlancamdoc;
$clsaltes = new cl_saltes;
$clconplanoreduz = new cl_conplanoreduz;
$clconlancamord = new cl_conlancamord;
$clconlancamlr = new cl_conlancamlr;

include("classes/db_cfautent_classe.php");
$clcfautent = new cl_cfautent;

include("libs/db_libcaixa.php");
$clautenticar = new cl_autenticar;

include("classes/db_empagemov_classe.php");
$clempagemov = new cl_empagemov;

//retorna os arrays de lancamento...
$cltranslan = new cl_translan;

$ip = db_getsession("DB_ip");
$porta = 5001;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$db_opcao = 22;
$db_botao = false;

if (isset($confirmar)) {
  $result = $clempempenho->sql_record($clempempenho->sql_query_file($e60_numemp));
  db_fieldsmemory($result, 0);
  $sqlerro = false;
  db_inicio_transacao();

  $sql = "update empparametro set e39_anousu = e39_anousu where e39_anousu = ".db_getsession("DB_anousu");
  $res = db_query($sql);

  $tot = round($e60_vlrpag - $vlrpag_estornar,2);
  $est = round($vlrpag_estornar,2);
  if (($est) > ($e60_vlrpag)) {
    $erro_msg = "O valor digitado para estornar não está disponivel";
    $sqlerro = true;
  }

  if ($sqlerro == false) {

    // este teste verifica se poderá ser feito lancamento na data e se tem saldo no empenho

    if ($e60_anousu < db_getsession("DB_anousu")) {
      $codteste = "36";

      // verifica se esta na empresto
      $result  = db_query("select e91_numemp from empresto where e91_numemp = $e60_numemp");
      $tot_reg = pg_numrows($result);

      if ($tot_reg == 0) {
        $erro_msg = "Pagamento de RP não estornado";
        $sqlerro  = true;
      }
    } else {
      $codteste = "6";
    }

    if ($sqlerro == false) {
      $sql = "select fc_verifica_lancamento(".$e60_numemp.",'".date("Y-m-d", db_getsession("DB_datausu"))."',".$codteste.",".$est.")";

      $result_erro = db_query($sql);

      //db_criatabela($result_erro);exit;

      $erro_msg = pg_result($result_erro, 0, 0);

      if (substr($erro_msg, 0, 2) > 0) {

        $erro_msg = substr($erro_msg, 3);
        $sqlerro = true;

      }
    }
  }

  if ($sqlerro == false) {
    $clempempenho->e60_vlrpag = "$tot";
    $clempempenho->e60_numemp = $e60_numemp;
    $clempempenho->alterar($e60_numemp);
    if ($clempempenho->erro_status == 0) {
      $sqlerro = true;
      $erro_msg = $clempempenho->erro_msg;
    } else {
      $ok_msg = $clempempenho->erro_msg;
    }
  }
  //array que irá armazenar os valores de cada elemento para fazer os lancamentos contabeis
  $arr_codeleval = array();
  $arr_dados = split("#", $dados);
  $tam = count($arr_dados);
  for ($i = 0; $i < $tam; $i ++) {
    $arr_ele = split("-", $arr_dados[$i]);
    //$arr_ele[0] é o codigo do elemento   $arr_ele[1] é o valor que será estornado  $arr_ele[2] é o valor que já foi anulado

    $elemento = $arr_ele[0];
    $valor = $arr_ele[1];

    //rotina que atualiza os valores dos elementos
    $result03 = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp, $elemento, "e64_vlrpag,e64_vlranu,e64_vlremp"));
    if ($clempelemento->numrows > 0) {
      db_fieldsmemory($result03, 0, true);
    } else {
      $e64_vlrpag = '0';
    }
    //array usado nos lançamento contábeis
    $arr_codeleval[$elemento] = $valor;

    $clempelemento->e64_numemp = $e60_numemp;
    $clempelemento->e64_codele = $elemento;

    if (isset($pag_ord)) {
      $tot = $e64_vlrpag - $valor;
    } else {
      $tot = $e64_vlrpag - $valor;
    }
    if ($sqlerro == false) {
      $clempelemento->e64_vlrpag = "$tot";
      $clempelemento->alterar($e60_numemp, $arr_ele[0]);
      $erro_msg = $clempelemento->erro_msg;
      if ($clempelemento->erro_status == 0) {
        $sqlerro = true;
        break;
      }
    }
    //rotina que atualiza a tabela pagordemele, caso tenha entrado por ordem de pagamento
    if (isset($pag_ord) && $sqlerro == false) {
      $clpagordemele->e53_codord = $e50_codord;
      $clpagordemele->e53_codele = $arr_ele[0];

      $result05 = $clpagordemele->sql_record($clpagordemele->sql_query_file($e50_codord, $elemento));
      db_fieldsmemory($result05, 0);

      if (($valor +0) > ($e53_vlrpag +0)) {
        $sqlerro = true;
        $erro_msg = "O valor digitado para estornar não está disponivel";
        break;
      }

      if ($sqlerro == false) {
        $tot_ele = $e53_vlrpag - $valor;
        $clpagordemele->e53_vlrpag = "$tot_ele";
        $clpagordemele->alterar($e50_codord);
        $erro_msg = $clpagordemele->erro_msg;
        if ($clpagordemele->erro_status == 0) {
          $sqlerro = true;
          break;
        }
      }

    }
    //final
  }
  //rotina que teste se é resto à pagar, se for entra na condição a baixo
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////LANÇAMENTO CONTÁBIL//////////////////////////////////////////////////////////////////////////

  /*verifica se todos os  elementos da tabela empelemento existem no comparlancam*/
  if ($sqlerro == false) {
    $result09 = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp, null, "e64_codele,o56_elemento"));
    $numrows09 = $clempelemento->numrows;
  }

  //dados comuns
  $anousu = db_getsession("DB_anousu");
  $datausu = date("Y-m-d", db_getsession("DB_datausu"));

  if ($e60_anousu == $anousu) {
    $c71_coddoc = '6';
  } else {
    $c71_coddoc = '36';
  }

  if ($sqlerro == false) {
    for ($i = 0; $i < $numrows09; $i ++) {
      db_fieldsmemory($result09, $i);
      //pegas os dados do empelemento
      $valor_estornar = $arr_codeleval[$e64_codele];
      if ($valor_estornar == 0) {
        continue;
      }

      /*conlancam*/
      if ($sqlerro == false) {
        $clconlancam->c70_anousu = $anousu;
        $clconlancam->c70_data = $datausu;
        $clconlancam->c70_valor = $valor_estornar;
        $clconlancam->incluir(null);
        $erro_msg = $clconlancam->erro_msg;
        if ($clconlancam->erro_status == 0) {
          $sqlerro = true;
          break;
        } else {
          $c70_codlan = $clconlancam->c70_codlan;
        }
        $lEvento = EventoContabil::vincularLancamentoNaInstituicao($clconlancam->c70_codlan , db_getsession("DB_instit"));
        $lEvento = EventoContabil::vincularOrdem($clconlancam->c70_codlan);
      }
      /*fim-conlancam*/

      /*conlancamele*/
      if ($sqlerro == false) {
        $clconlancamele->c67_codlan = $c70_codlan;
        $clconlancamele->c67_codele = $e64_codele;
        $clconlancamele->incluir($c70_codlan);
        $erro_msg = $clconlancamele->erro_msg;
        if ($clconlancamele->erro_status == 0) {
          $sqlerro = true;
        }
      }
      /*fim-conlancamele*/

      if ($e60_anousu == db_getsession("DB_anousu")) {

        /*orcdotacaoval*/
        if ($sqlerro == false) {
          $result85 = db_query("select fc_lancam_dotacao($e60_coddot,'$datausu',$c71_coddoc,'$valor_estornar') as dotacao");
          db_fieldsmemory($result85, 0);
          if (substr($dotacao, 0, 1) == 0) {
            //quando o primeiro caractere for igual a zero eh porque deu erro
            $sqlerro = true;
            $erro_msg = "Erro na atualização do orçamento \\n ".substr($dotacao, 1);
          }
        }
        /*fim-orcdotacaoval*/

        /*inicio-conlancamdot*/
        if ($sqlerro == false) {
          $clconlancamdot->c73_data = $datausu;
          $clconlancamdot->c73_anousu = $anousu;
          $clconlancamdot->c73_coddot = $e60_coddot;
          $clconlancamdot->c73_codlan = $c70_codlan;
          $clconlancamdot->incluir($c70_codlan);
          $erro_msg = $clconlancamdot->erro_msg;
          if ($clconlancamdot->erro_status == 0) {
            $sqlerro = true;
            break;
          }
        }
        /*fim-conlancamdot*/
      }

      /*conlancamcgm*/
      if ($sqlerro == false) {
        $clconlancamcgm->c76_data = $datausu;
        $clconlancamcgm->c76_codlan = $c70_codlan;
        $clconlancamcgm->c76_numcgm = $e60_numcgm;
        $clconlancamcgm->incluir($c70_codlan);
        $erro_msg = $clconlancamcgm->erro_msg;
        if ($clconlancamcgm->erro_status == 0) {
          $sqlerro = true;
          break;
        }
      }
      /*fim-conlancamcgm*/

      /*conlancamemp*/
      if ($sqlerro == false) {
        $clconlancamemp->c75_codlan = $c70_codlan;
        $clconlancamemp->c75_numemp = $e60_numemp;
        $clconlancamemp->c75_data = $datausu;
        $clconlancamemp->incluir($c70_codlan);
        $erro_msg = $clconlancamemp->erro_msg;
        if ($clconlancamemp->erro_status == 0) {
          $sqlerro = true;
          break;
        }
      }
      /*fim-conlancamemp*/

      /*conlancamord*/
      /*qando for por ordem de pagamento*/
      if (isset($pag_ord) && $sqlerro == false) {
        $clconlancamord->c80_codlan = $c70_codlan;
        $clconlancamord->c80_codord = $e50_codord;
        $clconlancamord->c80_data = $datausu;
        $clconlancamord->incluir($c70_codlan);
        $erro_msg = $clconlancamord->erro_msg;
        if ($clconlancamord->erro_status == 0) {
          $sqlerro = true;
          break;
        }
      }
      /*fim-conlancamord*/

      /*inicio-conlancamdoc*/
      if ($sqlerro == false) {
        $clconlancamdoc->c71_data = $datausu;
        $clconlancamdoc->c71_coddoc = $c71_coddoc;
        $clconlancamdoc->c71_codlan = $c70_codlan;
        $clconlancamdoc->incluir($c70_codlan);
        $erro_msg = $clconlancamdoc->erro_msg;
        if ($clconlancamdoc->erro_status == 0) {
          $sqlerro = true;
          break;
        }
      }
      /*fim-conlancamdoc*/

      //rotina que pega o codigo reduzido a partir do numero da conta
      //retorna $k13_reduz
      if ($sqlerro == false) {
        $result55 = $clsaltes->sql_record($clsaltes->sql_query_file($k13_conta, "k13_reduz"));
        if ($clsaltes->numrows > 0) {
          db_fieldsmemory($result55, 0);
        } else {
          $sqlerro = true;
          $erro_msg = "Conta tributária inválida!";
          break;
        }
      }

      /*conlancampag*/
      if ($sqlerro == false) {
        $clconlancampag->c82_codlan = $c70_codlan;
        $clconlancampag->c82_anousu = $anousu;
        $clconlancampag->c82_reduz = $k13_reduz;
        $clconlancampag->incluir($c70_codlan);
        $erro_msg = $clconlancampag->erro_msg;
        if ($clconlancampag->erro_status == 0) {
          $sqlerro = true;
        }
      }
      /*fim-conlancampag*/

      if ($sqlerro == false) {
        //rotina que atualiza os array de creditos e debitos conforme o elemento

        if ($e60_anousu == db_getsession("DB_anousu")) {
          $cltranslan->db_trans_estorna_pagamento($e64_codele, $k13_reduz, $e60_anousu);
        } else {
          $cltranslan->db_trans_estorna_pagamento_resto($e64_codele, $k13_reduz, $e60_anousu, $e60_numemp);
        }
        $arr_debito = $cltranslan->arr_debito;
        $arr_credito = $cltranslan->arr_credito;
        $arr_histori = $cltranslan->arr_histori;
        $arr_seqtranslr = $cltranslan->arr_seqtranslr;
        // carlos - conta faltante
        $conta_emp = $cltranslan->conta_emp;

        //conta usada na funçao empautemt
        /*
print_r($arr_debito);
print_r($arr_credito);
die();
/*
/****************************************************/
        /* rotina que verifica se os array com os lançamentos naum estão vazios*/
        if (count($cltranslan->arr_debito) == 0 && $sqlerro == false) {
          $sqlerro = true;
          $erro_msg = 'Conta débito não cadastrada nas transações.';
        }
        if (count($cltranslan->arr_credito) == 0 && $sqlerro == false) {
          $sqlerro = true;
          $erro_msg = 'Conta crédito não cadastrada nas transações.';
        }
        if (count($cltranslan->arr_histori) == 0 && $sqlerro == false) {
          $sqlerro = true;
          $erro_msg = 'Histórico do lançamento nao encontrado.';
        }
        //final=========================================================

        //final da rotina de atualização de arrays/////////
      }

      if ($sqlerro == false) {
        //rotina que inclui no conlancamval
        for ($t = 0; $t < count($arr_credito); $t ++) {
          //rotina que teste se a conta reduzida foi incluida no conplanoreduz
          $clconplanoreduz->sql_record($clconplanoreduz->sql_query_file(null, null, 'c61_codcon', '', "c61_anousu = ".db_getsession("DB_anousu")." and c61_reduz=".$arr_debito[$t]));
          if ($clconplanoreduz->numrows == 0) {
            $sqlerro = true;
            $erro_msg = "(D) Conta ".$arr_debito[$t]." não dísponivel para o exercicio!";
            break;
          }
          $clconplanoreduz->sql_record($clconplanoreduz->sql_query_file(null, null, 'c61_codcon', '', "c61_anousu = ".db_getsession("DB_anousu")." and c61_reduz=".$arr_credito[$t]));
          if ($clconplanoreduz->numrows == 0 && $sqlerro == false) {
            $sqlerro = true;
            $erro_msg = "(C) Conta ".$arr_credito[$t]." não dísponivel para o exercicio!";
            break;

          }
          //final

          if ($sqlerro == false) {
            $clconlancamval->c69_codlan = $c70_codlan;
            $clconlancamval->c69_credito = $arr_credito[$t];
            $clconlancamval->c69_debito = $arr_debito[$t];
            $clconlancamval->c69_codhist = $arr_histori[$t];
            $clconlancamval->c69_valor = $valor_estornar;
            $clconlancamval->c69_data = $datausu;
            $clconlancamval->c69_anousu = $anousu;
            $clconlancamval->incluir(null);
            $erro_msg = $clconlancamval->erro_msg;
            if ($clconlancamval->erro_status == 0) {
              $sqlerro = true;
              break;
            } else {
              $c69_sequen = $clconlancamval->c69_sequen;
            }
          }
          /*conlancamlr   */
          if ($sqlerro == false) {
            $clconlancamlr->c81_sequen = $c69_sequen;
            $clconlancamlr->c81_seqtranslr = $arr_seqtranslr[$t];
            $clconlancamlr->incluir($c69_sequen, $arr_seqtranslr[$t]);
            $erro_msg = $clconlancamlr->erro_msg;
            if ($clconlancamlr->erro_status == 0) {
              $sqlerro = true;
              break;
            }
          }
          /*final*/
        }
        /*fim-conlancamval*/
      }
    }
  }
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //FINAL LANÇAMENTO CONTÁBEIS////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //rotina que inclui no corrente//////////////////////////////////////////////////////////////////////////////////////

  //////////----------------------------------------------//
  //---rotina verifica se impressora esta ligada--//
  //---------------------------------------------//
  if ($sqlerro == false) {
    //rotina que verifica se o ip do usuario irá imprimir autenticar ou naum ira fazer nada
    $result99 = $clcfautent->sql_record($clcfautent->sql_query_file(null, "k11_tipautent", '', "k11_ipterm = '".db_getsession("DB_ip")."'
		                                                                and k11_instit =  ".db_getsession("DB_instit")));
    if ($clcfautent->numrows > 0) {
      db_fieldsmemory($result99, 0);
    } else {
      $erro_msg = "Cadastre o ip ".db_getsession("DB_ip")." como um caixa.";
      $sqlerro = true;
    }

  }

  //------
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////
  if ($sqlerro == false) {
    if ($k11_tipautent!=3) {
      if ($k12_cheque == '') {
        $k12_cheque = '0';
      }
      if ($e91_codcheque == '') {
        $e91_codcheque = '0';
      }
      if (isset($e50_codord) && $e50_codord != '') {
        $orde = $e50_codord;
      } else {
        $orde = '0';
      }

      //funcao que ira retornar a variavel $retorno
      if ($e60_anousu < db_getsession("DB_anousu")) {
        /*RESTO A PAGAR*/
        $result = db_query("select fc_autentemp($e60_numemp,$k13_conta,'$conta_emp','".$datausu."','-$vlrpag_estornar',$k12_cheque,'$ip','$e91_codcheque',$orde,".db_getsession("DB_instit").",0,0,0) as retorno");
      } else {
        $result = db_query("select fc_autentemp($e60_numemp,$k13_conta,'0','".$datausu."','-$vlrpag_estornar',$k12_cheque,'$ip','$e91_codcheque',$orde,".db_getsession("DB_instit").",0,0,0) as retorno");
      }

      if ($result == false) {
        $sqlerro = true;
        $erro_msg = "Erro na função FC_AUTENTEMP!!";
      } else {
        db_fieldsmemory($result, 0);
				if (substr($retorno,0,1) != '1'){

            $erro_msg = $retorno;
						$sqlerro  = true;
				}
      }
    }
  }
  if ($sqlerro == false) {
    $clconlancamcompl->c72_codlan = $c70_codlan;
    if (isset($k12_cheque)) {
      $clconlancamcompl->c72_complem = ($k12_cheque == "" ? "" : "Cheque :$k12_cheque ");
    }
    $clconlancamcompl->c72_complem .= "Motivo: $c72_complem";
    $clconlancamcompl->incluir($c70_codlan);
    $erro_msg = $clconlancamcompl->erro_msg;
    if ($clconlancamcompl->erro_status == 0) {
      $sqlerro = true;
    }
  }

  db_fim_transacao($sqlerro);

  // configura varia vel para o sistema autenticar depois de emitor a nota de estorno
  if ($sqlerro == false ) {
    if ((isset($retorno) && $k11_tipautent == 1) ||  (isset($retorno_imp)) ) {
      if (isset($retorno_imp)) {
        $retorno = $retorno_imp;
      }

      require_once 'model/impressaoAutenticacao.php';
      $oImpressao = new impressaoAutenticacao($retorno);
      $oModelo = $oImpressao->getModelo();
      $oModelo->imprimir();

      /*
      $fd = @fsockopen(db_getsession('DB_ip'),4444);
      @fputs($fd, chr(15)."$retorno".chr(18).chr(10).chr(13));
      @fclose($fd);
      */
    }

    $retorno_imp = true;
    $confirmar_primeira_vez = true;

  }

  //final rotina corrente////////////////////////////////////////////////////////////////////////////////////////////
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////


}

// autentica na impressora
if (isset($retorno_imp) && !isset($confirmar_primeira_vez) ) {

  if (isset($retorno_imp)) {
    $retorno = $retorno_imp;
  }

  require_once 'model/impressaoAutenticacao.php';
  $oImpressao = new impressaoAutenticacao($retorno);
  $oModelo = $oImpressao->getModelo();
  $oModelo->imprimir();

  /*
  $fd = @fsockopen(db_getsession('DB_ip'),4444);
  @fputs($fd, chr(15)."$retorno".chr(18).chr(10).chr(13));
  @fclose($fd);
  */
  $reimpressao = true;

}

function php_erro($msg, $erro = null)
{
  global $e60_numemp, $e50_codord;
  $erro = base64_encode("erro=$erro&erro_msg=$msg&e50_codord=$e50_codord&e60_numemp=$e60_numemp");
  db_redireciona("emp1_emppagamentoestorna001.php?$erro");
}

if (isset($pag_emp) && empty($confirmar) ) {
  $db_opcao = 2;
  $db_botao = true;
  //rotina que traz os dados de empempenho
  if (isset($e60_codemp) && $e60_codemp != '') {
    $arr = split("/", $e60_codemp);
    if (count($arr) == 2 && isset($arr[1]) && $arr[1] != '') {
      $dbwhere_ano = " and e60_anousu = ".$arr[1];
    } else {
      $dbwhere_ano = " and e60_anousu =".db_getsession("DB_anousu");
    }

    $sql = $clempempenho->sql_query("", "*", "e60_numemp",
         " e60_codemp =  '".$arr[0]."' $dbwhere_ano and e60_instit = ".db_getsession("DB_instit"));
  } else {
    $sql = $clempempenho->sql_query($e60_numemp);
  }
  $result = $clempempenho->sql_record($sql);

  if ($clempempenho->numrows > 0) {
    db_fieldsmemory($result, 0, true);
  } else {
    php_erro("Empenho inválido!");
    exit;
  }

  // $result01 = $clpagordemele->sql_record($clpagordemele->sql_query(null,null,'(sum(e53_valor)-sum(e53_vlranu)) as saldo  ',"","e50_numemp = $e60_numemp "));

  $result01 = $clpagordem->sql_record($clpagordem->sql_query(null, 'e50_codord as codord ', "", "e50_numemp = $e60_numemp"));
  $numrows01 = $clpagordem->numrows;

  if ($numrows01 > 0) {
    $existe_ordem = true;
    // db_fieldsmemory($result01,0);
    // if($saldo!=0){
    php_erro("Empenho possui ordens de pagamento, acesse pelo numero da OP !",'true');
    exit;
    // }

  }
} else if (isset($pag_ord)) {
  $db_opcao = 2;
  $db_botao = true;
  //rotina que traz os dados de pagordem
  $result = $clpagordem->sql_record($clpagordem->sql_query($e50_codord));
  if ($clpagordem->numrows > 0) {
    db_fieldsmemory($result, 0, true);
    $result01 = $clpagordem->sql_record($clpagordem->sql_query(null, 'e50_codord as codord', "", "e50_numemp = $e50_numemp"));
    db_fieldsmemory($result01, 0, true);
  } else {
    php_erro("Ordem de pagamento inválido!");
  }

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?


if (isset ($confirmar) && $sqlerro == false) {
	$c72_complem = '';
}
include ("forms/db_frmemppagamentoestorna.php");
?>
    </center>
	</td>
  </tr>
</table>
<?


db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?


if ($db_opcao == 22) {
//	echo "<script>document.form1.pesquisar.click();</script>";
}
if (isset ($confirmar)) {
	if ($sqlerro == true) {
		db_msgbox($erro_msg);
	} else {
		echo "
		       <script>
		          if(confirm('Alteração efetuada com sucesso. \\n \\n Deseja imprimir o relatório? ')){
			    jan = window.open('emp2_emiteestornoemp002.php?codord=$e50_codord&codlan=$c70_codlan','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
			    jan.moveTo(0,0);
		          }
		       </script>

		    ";

	}
}

if( (isset($retorno) && @$k11_tipautent == 1) ||  (isset($retorno_imp)) ){
   echo "
       <script>
         // função para dispara a autenticação
	 function aut(){
	      retorna = confirm('Autenticar novamente?');
	      if(retorna == true){
	          obj=document.createElement('input');
	          obj.setAttribute('name','retorno_imp');
	          obj.setAttribute('type','hidden');
	          obj.setAttribute('value','$retorno');
	          document.form1.appendChild(obj);
	          document.form1.submit();
  	      }
	 }
       </script>
  ";
}



?>