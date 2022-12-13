<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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


require_once ("libs/db_libcontabilidade.php");
require_once ("libs/db_liborcamento.php");
require_once ("libs/db_utils.php");
require_once ("classes/db_conlancam_classe.php");
require_once ("classes/db_conlancamdot_classe.php");
require_once ("classes/db_conlancamsup_classe.php");
require_once ("classes/db_conlancamdoc_classe.php");
require_once ("classes/db_conlancamrec_classe.php");
require_once ("classes/db_conlancamval_classe.php");
require_once ("classes/db_conlancamretif_classe.php");
require_once ("classes/db_orcsuplem_classe.php");
require_once ("classes/db_orcsuplemlan_classe.php");
require_once ("classes/db_orcprojeto_classe.php");
require_once ("classes/db_orcreserva_classe.php"); // reserva de saldo
require_once ("classes/db_orcreservasup_classe.php"); // reserva de saldo das suplementações
require_once ("classes/db_orcsuplemval_classe.php"); // lançamento das suplementações
require_once ("classes/db_orcsuplemrec_classe.php");


/*
*  recebe o codigo de uma suplementação e processa
*  atente para o parametro $estorno=true ...
*  se quiser imprimir saida na tela, sete a variavel saida_tela=true
*/
function processa_suplementacao($o46_codsup, $data, $usuario, $estorno = false) {
  $erro = false; //retorna esta variavel se algo aconteceu errado, ela terá o conteúdo = true
  $debug= false;  // veriavel que permite debug na tela
  $matriz_dotacao=array();

  global $fc_lancam_suplementacao, $anousu, $valor, $tipo, $dot, $codsup, $o48_tiposup, $erro, $c53_tipo, $documento_estorno,$saldoatual;
  /*
  *  para variáveis boleanas o php retorna vazio para resultados 'false' e retorna '1' para resultados = 'true'
  *
  */
  $auxiliar = new cl_orcsuplem;
  $clconlancam = new cl_conlancam;
  $clconlancamdot = new cl_conlancamdot;
  $clconlancamrec = new cl_conlancamrec;
  $clconlancamsup = new cl_conlancamsup;
  $cltranslan = new cl_translan;
  $clconlancamdoc = new cl_conlancamdoc;
  $clconlancamval = new cl_conlancamval;
  $clconlancamretif = new cl_conlancamretif;

  $sqlsuplem = "select fc_lancam_suplementacao($o46_codsup,'$data',$usuario)";

  $result = $auxiliar->sql_record($sqlsuplem);

  if ($result == false) {
    die("Problema ao executar: $sqlsuplem <br><br>Erro:" . $auxiliar->erro_msg);
    exit;
  }
  if ($debug==true){
    echo "<br> saida da função fc_lancam_suplementação(codsup,data,usuario) = $o46_codsup,$data,$usuario";
    db_criatabela($result);
  }
  if ($auxiliar->numrows > 0) {
    db_fieldsmemory($result, 0);
    if ($fc_lancam_suplementacao[0] == "1") {
      // retornou mensagem de suplementação processada com sucesso
    } else {
      if ($estorno==false){
        $erro = true;
        db_msgbox("Av14: Processamento : ".$fc_lancam_suplementacao);
      } else {
        // quando estorno==true, esta suplementação deve ser já processada
      }
    }
  };
  $sql = "select  codsup,o48_tiposup,tipo,dot,valor
  from (
  select o47_codsup as codsup,'s'::char(1) as tipo, o47_coddot as dot, o47_valor   as valor
  from orcsuplemval
  where o47_codsup=$o46_codsup  and o47_valor > 0
  union
  select  o47_codsup as codsup,  'r'::char(1) as tipo,  o47_coddot as dot,  o47_valor*-1   as valor
  from orcsuplemval
  where o47_codsup=$o46_codsup  and o47_valor < 0
  union
  select  o85_codsup as codsup,  'rec'::char(3) as tipo,  o85_codrec as dot,  o85_valor   as valor
  from orcsuplemrec
  where o85_codsup=$o46_codsup
  ) as x
  inner join orcsuplem on o46_codsup=codsup
  inner join orcsuplemtipo on o46_tiposup =o48_tiposup
  ";
  $rval = db_query($sql) or die($sql);
  if ($debug==true){
    echo "<br> pesquisa na tabela orcsuplemval, as dotações constantes nas suplementações e os tipos ";
    db_criatabela($rval);
  }
  if ($erro == false) {
    for ($x = 0; $x < pg_numrows($rval); $x ++) {
      db_fieldsmemory($rval, $x);
      $clconlancam->c70_anousu = $anousu;
      $clconlancam->c70_data = $data;
      $clconlancam->c70_valor = $valor; //
      $clconlancam->incluir(0);

      if ($clconlancam->erro_status == "0") {
        $erro = true;
        db_msgbox("Er:conlancam:".$clconlancam->erro_msg);
      }
      $lEvento = EventoContabil::vincularLancamentoNaInstituicao($clconlancam->c70_codlan , db_getsession("DB_instit"));
      $lEvento = EventoContabil::vincularOrdem($clconlancam->c70_codlan);
      $codlan = $clconlancam->c70_codlan; //pega o codigo gerado

      /*
      *  para projetos retificadores, não lança conlancamdoc nem conlancamrec
      */
      //if ($estorno==false){
        if ($tipo == "rec") {
          $clconlancamrec->c74_anousu = $anousu;
          $clconlancamrec->c74_codrec = $dot;
          $clconlancamrec->c74_data = $data;
          $clconlancamrec->incluir($codlan);

        } else {
          $clconlancamdot->c73_data = $data;
          $clconlancamdot->c73_anousu = db_getsession("DB_anousu");
          $clconlancamdot->c73_coddot = $dot;
          $clconlancamdot->incluir($codlan);

          if ($clconlancamdot->erro_status == "0") {
            $erro = true;
            db_msgbox("Er:conlancamdot:".$clconlancamdot->erro_message." $dot   ".db_getsession("DB_anousu")." $data");
          }
          $matriz_dotacao[] = $dot; // + anousu corrente
        }
      //}

      $clconlancamsup->c79_data = $data;
      $clconlancamsup->c79_codsup = $codsup;
      $clconlancamsup->incluir($codlan);
      if ($clconlancamsup->erro_status == "0") {
        $erro = true;
        db_msgbox("Er:conlancamsup:  ".$clconlancamsup->erro_message);
        break;
      }

      // pega transacao
      // ver o codigo da instituicao da conta de dotacao, troca o db_getsession e ela e depois devolve o certo
      if ($tipo == "rec") {

         $verdot = "select o70_instit
                      from orcreceita
                     where o70_anousu = ".db_getsession("DB_anousu")."
                       and o70_codrec = $dot";
         $resultdot = db_query($verdot);
          if (pg_numrows($resultdot) > 0) {
            $instit_dot = pg_result($resultdot, 0, 0);
            $instit_atual = db_getsession("DB_instit");
          } else {
            $erro = true;
            db_msgbox("Er: orcdotação. $dot não encontrada  ");
            break;
          }

        $cltranslan->db_trans_suplem($anousu, $o48_tiposup, true, false, $instit_dot); // sempre redução, reduz uma receita e coloca numa dotação !

      } else {

      	$verdot = "select o58_instit,
      	                  o58_valor
                      from orcdotacao
                     where o58_anousu = ".db_getsession("DB_anousu")."
                       and o58_coddot = $dot";
         $resultdot = db_query($verdot);
          if (pg_numrows($resultdot) > 0) {
            $instit_dot = pg_result($resultdot, 0, 0);
            $instit_atual = db_getsession("DB_instit");
            $oDotacao = db_utils::fieldsMemory($resultdot, 0);
          } else {
            $erro = true;
            db_msgbox("Er: orcdotação. $dot não encontrada  ");
            break;
          }

        $lSuplementacaoEspecial = false;
        /**
         * verifica se a suplementacao é especial.(dotacao com valor = 0 e já existe uma suplmentacao para essa mesma
         * dotação)
         */
        if ($oDotacao->o58_valor == 0) {

          $sSqlOutrasSuplementacoes  = "select 1 ";
          $sSqlOutrasSuplementacoes .= "  from orcsuplemval ";
          $sSqlOutrasSuplementacoes .= " where o47_coddot = {$dot}";
          $sSqlOutrasSuplementacoes .= "   and o47_codsup <> {$o46_codsup}";
          $rsOUtrasSuplementacoes    = db_query($sSqlOutrasSuplementacoes);
          if (pg_num_rows($rsOUtrasSuplementacoes) > 0) {
            $lSuplementacaoEspecial = true;
          }
        }
        if ($tipo == "s") { //suplementacao
          $cltranslan->db_trans_suplem($anousu, $o48_tiposup, false, $lSuplementacaoEspecial,$instit_dot);
        } else { // reducao ou receita
          $cltranslan->db_trans_suplem($anousu, $o48_tiposup, true, $lSuplementacaoEspecial,$instit_dot);
        }

      }

      if ($debug==true){
        echo "<br><Br><Br> retorno das transações contábeis";
        print_r($cltranslan->arr_debito);
        print_r($cltranslan->arr_credito);
        print_r($cltranslan->arr_histori);
        echo $cltranslan->coddoc;
      }
      /*
      *  se for estorno descobre qual o coddoc a ser lançado
      */
      if ($estorno == true) {
        /* tres estados possiveis,   rec = receitas ( campo 48_arrecadmaior , s = suplementações (o48_coddocsup)
        *   e   nenhum dos dois  = o48_coddocrec ( documento de redução )
        */
        if ($tipo == 'rec') {
          $sql = "select /* o48_arrecadmaior,*/ c53_tipo
          from orcsuplemtipo      inner join conhistdoc on c53_coddoc = o48_arrecadmaior
          where o48_tiposup = $o48_tiposup";
        }
        elseif ($tipo == 's') {
          $sql = "select /* o48_coddocsup, */ c53_tipo
          from orcsuplemtipo      inner join conhistdoc on c53_coddoc = o48_coddocsup
          where o48_tiposup = $o48_tiposup";
        } else {
          $sql = "select /* o48_coddocred,*/ c53_tipo
          from orcsuplemtipo      inner join conhistdoc on c53_coddoc = o48_coddocred
          where o48_tiposup = $o48_tiposup";
        }
        $rrr = db_query($sql);
        if ($rrr == true) {
          db_fieldsmemory($rrr, 0);
          switch ($c53_tipo) {
            case 40 :
            $documento_estorno = 8; //$documento_estorno = 41;
            break;
            case 50 :
            $documento_estorno = 10; //$documento_estorno = 51;
            break;
            case 60 :
            $documento_estorno = 12; //$documento_estorno = 61;
            break;
          }

        } else {
          $erro = true;
          db_msgbox("E54: Tipo da tabela orcsuplemtipo não encontrado na conhistdoc ! Contante Suporte !");
          break;
        }
      }
      //  out :
      if ($debug==true){
        echo "<br>Quando for estorno ira haver um documento de estorno";
        echo "<br>  Estorno documento:  $documento_estorno";
      }
      // grava documento
      $clconlancamdoc->c71_data = $data;
      if ($estorno == true) {
        $clconlancamdoc->c71_coddoc = $documento_estorno;
      } else {
        $clconlancamdoc->c71_coddoc = $cltranslan->coddoc;
      }
      $clconlancamdoc->incluir($codlan);
      if ($clconlancamdoc->erro_status == "0") {
        $erro = true;
        db_msgbox("documento ".$clconlancamdoc->erro_message);
        break;
      }
      // grava conlancamest - para preservar o documento que originou o estorno
      $clconlancamretif->c79_data = $data;
      $clconlancamretif->c79_coddoc = $cltranslan->coddoc; // documento de origem do estorno
      $clconlancamretif->c79_codsup = $codsup;
      $clconlancamretif->incluir($codlan);
      if ($clconlancamretif->erro_status == "0") {
        $erro = true;
        db_msgbox("documento ".$clconlancamretif->erro_message);
        break;
      }

      //if ($o48_tiposup != 1014) { // só gera lancamentos contábeis se for diferente de transferência de recursos
        for ($fi = 0; $fi < sizeof($cltranslan->arr_debito); $fi ++) {
          $clconlancamval->c69_anousu = $anousu;
          $clconlancamval->c69_codlan = $codlan;
          $clconlancamval->c69_codhist = $cltranslan->arr_histori[$fi]; //
          if ($estorno == true) {
            $clconlancamval->c69_debito = $cltranslan->arr_credito[$fi];
            $clconlancamval->c69_credito = $cltranslan->arr_debito[$fi];
          } else {
            $clconlancamval->c69_debito = $cltranslan->arr_debito[$fi];
            $clconlancamval->c69_credito = $cltranslan->arr_credito[$fi];
          }
          $clconlancamval->c69_valor = $valor;
          $clconlancamval->c69_data = $data;
          $clconlancamval->incluir("");
          if ($clconlancamval->erro_status == "0") {
            $erro = true;
            db_msgbox("Er41:  Não consegui inserir na tabela de lançamentos (conlançanval)");
            break;
          }
        }
      //}
    }
  }
  // o if abaixo serve pra retificação, que no desprocessamento não verifica saldo
  // porém o projeto retificador deve ter as mesmas dotações suplementadas com os mesmos valores ou a maior
  // pra não ficar dotação negativa, por isto deixei comentado este if, pra não liberar a retificação a "lá vontê"
  // if ($estorno == false) //  quando nao for estorno entramos no loop
  for ($x = 0; $x < sizeof($matriz_dotacao); $x ++) {
    $dot = $matriz_dotacao[$x];
    $dtini = $anousu.'-01-01';
    $dtfim = $anousu.'-12-31';
    $sql = "select  substr(fc_dotacaosaldo($anousu,$dot,2,'$dtini','$dtfim'),107,12)::float8 as saldoatual ";
    $res = db_query($sql);
    if ($debug==true){
      db_criatabela($res);
    }
    db_fieldsmemory($res, 0);
    if ($saldoatual < 0) {
      $erro = true;
      db_msgbox("Não posso confirmar esta operação porque a dotação $dot ficará com saldo negativo! (1)");
    }
  }

  //  ------ -------------------
  if ($debug==true){
    echo " para o processamento.."; exit;
  }
  return $erro;
}


/*
*  se quiser imprimir saida na tela, sete a variavel $debug=true
*/

function desprocessa_suplementacao($codsup,$anousu,$estornar=false) {
  $erro  = false;    // retorna esta variavel se algo aconteceu errado, ela terá o conteúdo = true
  $debug = false;  // veriavel que permite debug na tela
  $matriz_dotacao = array ();
  $lista_lan = array ();

  global $fc_lancam_suplementacao,$matriz_dotacao,$lista_lan,$c79_codlan,$c73_coddot,$codlan,$saldoatual, $o46_tiposup;

  $clorcsuplemlan = new cl_orcsuplemlan;
  $clorcsuplem = new cl_orcsuplem;
  $clorcprojeto = new cl_orcprojeto;
  $auxiliar = new cl_orcsuplem;
  $clconlancam = new cl_conlancam;
  $clconlancamval = new cl_conlancamval;
  $clconlancamsup = new cl_conlancamsup;
  $clconlancamdot = new cl_conlancamdot;
  $clconlancamdoc = new cl_conlancamdoc;
  $clconlancamrec = new cl_conlancamrec;
  $clconlancamretif = new cl_conlancamretif;

  /*
  *  para variáveis boleanas o php retorna vazio para resultados 'false' e retorna '1' para resultados = 'true'
  */

  if ($debug==true && $estornar==true){
    echo "<br> iniciando desprocessamento de um projeto retificado ";
  }
  // tira as suplementações da lista de processadas
  // no estorno de suplementação não é removido do orcsuplemlan
  if ($erro == false  && $estornar==false) {
    $res = $clorcsuplemlan->excluir($codsup);
    if ($clorcsuplemlan->erro_status == 0) {
      db_msgbox(" (orcsuplemlan) ".$clorcsuplemlan->erro_msg);
      $erro = true;
    }
  }
  // seleciona lançamentos contábeis
  if ($estornar==false){
    $res = $clconlancamsup->sql_record($clconlancamsup->sql_query_file(null, "c79_codlan", null, "c79_codsup=$codsup"));
  } else {
    // lançamentos do projeto retificado
    $sql_retif  = "select c79_codlan
    from conlancamsup
    inner join conlancamdoc on c71_codlan = c79_codlan
    inner join conhistdoc      on c53_coddoc = c71_coddoc
    and c53_tipo in (41,51,61)
    where c79_codsup = $codsup
    ";
    $res = $clconlancamsup->sql_record($sql_retif);
  }
  if ($debug==true){
    echo  $sql_retif;
    db_criatabela($res);
  }
  if ($clconlancamsup->numrows > 0) {
    for ($x = 0; $x < $clconlancamsup->numrows; $x ++) {
      db_fieldsmemory($res, $x);
      $lista_lan[] = $c79_codlan;
    }
    if ($debug==true){
      print_r($lista_lan);
    }
  }
  for ($x = 0; $x < sizeof($lista_lan); $x ++) {
    $codlan = $lista_lan[$x];
    if ($debug==true){
      echo "excluir codlan $codlan <br>";
    }
    $res = $clconlancamsup->excluir($codlan);
    if ($clconlancamsup->erro_status == 0) {
      db_msgbox("Tconlancamsup)".$clconlancamsup->erro_msg);
      $erro = true;
    }
    $res = $clconlancamretif->excluir($codlan);
    if ($clconlancamretif->erro_status == 0) {
      db_msgbox("Tconlancamsup)".$clconlancamretif->erro_msg);
      $erro = true;
    }
    $res = $clconlancamrec->excluir($codlan);
    if ($clconlancamrec->erro_status == 0) {
      db_msgbox($clconlancamrec->erro_msg);
      $erro = true;
    }
    $res = $clconlancamdoc->excluir($codlan);
    if ($clconlancamdoc->erro_status == 0) {
      db_msgbox($clconlancamdoc->erro_msg);
      $erro = true;
    }
    //  projetos que foram retificados não tem lançamnetos na conlancamdot
    //if ($estornar==false){
      $res = $clconlancamdot->sql_record($clconlancamdot->sql_query_file($codlan));
      if ($clconlancamdot->numrows > 0) {
        db_fieldsmemory($res, 0);
        $matriz_dotacao[] = $c73_coddot; // + anousu corrente
      }
      $res = $clconlancamdot->excluir($codlan);
      if ($clconlancamdot->erro_status == 0) {
        db_msgbox($clconlancamdot->erro_msg);
        $erro = true;
      }
    //}

      $res = $clorcsuplem->sql_record($clorcsuplem->sql_query($codsup, "o46_tiposup"));
			if ($clorcsuplem->numrows == 0) {
        db_msgbox("(t) Orcsuplem sem registros!");
        $sqlerro = true;
        break;
			}
			db_fieldsmemory($res, 0);

      //if ($o46_tiposup != 1014) {
				// se for diferente de transferencia de recurso
				// isso porque o tipo 1014 nao gera conlancamval
				// e nao pode dar erro aqui nesse ponto
				// pois se nao existir conlancamval deve dar erro
				// mas somente para os casos diferente de 1014

        $oDaoDetalhes = new cl_contacorrentedetalheconlancamval();
        $oDaoDetalhes->excluir(null, "c28_conlancamval in (select c69_sequen from conlancamval where c69_codlan = {$codlan})");
        if ($oDaoDetalhes->erro_status == '0') {
          db_msgbox("Não foi possível excluir o detalhamento do conta corrente. Lançamento {$codlan}");
          $erro = true;
        }

				$res = $clconlancamval->excluir_codlan($codlan);
				if ($clconlancamval->erro_status == 0) {
					db_msgbox("(t)Conlancamval  ".$clconlancamval->erro_msg);
					$erro = true;
				}
			//}

    $oDaoConlancamInstit = new cl_conlancaminstit();
    $oDaoConlancamInstit->excluir(null, "c02_codlan = {$codlan}");
    if ($oDaoConlancamInstit->erro_status == "0") {
      db_msgbox($oDaoConlancamInstit->erro_msg);
      $erro = true;
    }

    $oDaoConlancamOrdem = new cl_conlancamordem();
    $oDaoConlancamOrdem->excluir(null, "c03_codlan = {$codlan}");
    if ($oDaoConlancamOrdem->erro_status == "0") {
      db_msgbox($oDaoConlancamOrdem->erro_msg);
      $erro = true;
    }

    $res = $clconlancam->excluir($codlan);
    if ($clconlancam->erro_status == 0) {
      db_msgbox($clconlancam->erro_msg);
      $erro = true;
    }
  }
  // para gravar a transação, nenhuma dotação pode ser negativa
  //if ($estornar==false){
    for ($x = 0; $x < sizeof($matriz_dotacao); $x ++) {
      $dot = $matriz_dotacao[$x];
      $dtini = $anousu.'-01-01';
      $dtfim = $anousu.'-12-31';
      $sql = "select  substr(fc_dotacaosaldo($anousu,$dot,2,'$dtini','$dtfim'),107,12)::float8 as saldoatual ";
      $res = db_query($sql);
      if ($debug==true){
        db_criatabela($res);
      }
      db_fieldsmemory($res, 0);
      if ($saldoatual < 0) {
        $erro = true;
        db_msgbox("Não posso confirmar esta operação porque a dotação $dot ficará com saldo negativo! (2)");
      }
    }
  //}
  return $erro; // false indica sucesso
}

function desprocessa_suplementacao2($codsup,$anousu,$estornar=false) {
  $sqlerro  = false;    // retorna esta variavel se algo aconteceu errado, ela terá o conteúdo = true
  $debug    = false;  // veriavel que permite debug na tela
  $matriz_dotacao = array ();
  $lista_lan = array ();
  $anousu = db_getsession("DB_anousu");

  global $fc_lancam_suplementacao,$matriz_dotacao,$lista_lan,$c79_codlan,$c73_coddot,$codlan,$saldoatual;
  global $o47_codsup,$o47_anousu,$o47_coddot,$o47_valor,$atual_menos_reservado, $o46_tiposup;

  $clorcsuplemlan  = new cl_orcsuplemlan;
  $clorcsuplem     = new cl_orcsuplem;
  $clorcsuplemval  = new cl_orcsuplemval;
  $clorcreserva    = new cl_orcreserva;
  $clorcreservasup = new cl_orcreservasup;
  $clorcprojeto   = new cl_orcprojeto;
  $clconlancam    = new cl_conlancam;
  $clconlancamval = new cl_conlancamval;
  $clconlancamsup = new cl_conlancamsup;
  $clconlancamdot = new cl_conlancamdot;
  $clconlancamdoc = new cl_conlancamdoc;
  $clconlancamrec = new cl_conlancamrec;
  $clconlancamretif = new cl_conlancamretif;

  $res = $clorcsuplemlan->excluir($codsup);
  if ($clorcsuplemlan->erro_status == 0) {
    db_msgbox(" (orcsuplemlan) ".$clorcsuplemlan->erro_msg);
    $sqlerro = true;
  }

  if ($sqlerro == false ){
    // seleciona todos lançamentos contabeis
    $res = $clconlancamsup->sql_record($clconlancamsup->sql_query_file(null, "c79_codlan", null, "c79_codsup=$codsup"));

    if ($clconlancamsup->numrows > 0) {
      for ($x = 0; $x < $clconlancamsup->numrows; $x ++) {
        db_fieldsmemory($res, $x);
        $lista_lan[] = $c79_codlan;
      }
      if ($debug==true){
        print_r($lista_lan);
      }
    }
    for ($x = 0; $x < sizeof($lista_lan); $x ++) {
      $codlan = $lista_lan[$x];
      if ($debug==true){
        echo "excluir codlan $codlan <br>";
      }

      $res = $clconlancamsup->excluir($codlan);
      if ($clconlancamsup->erro_status == '0') {
        db_msgbox("Tconlancamsup".$clconlancamsup->erro_msg);
        $sqlerro = true;
        break;
      }

      $res = $clconlancamretif->excluir($codlan);
      if ($clconlancamretif->erro_status == 0) {
        db_msgbox("Tconlancamretif)".$clconlancamretif->erro_msg);
        $erro = true;
        break;
      }

      $res = $clconlancamrec->excluir($codlan);
      if ($clconlancamrec->erro_status == '0') {
        db_msgbox($clconlancamrec->erro_msg);
        $sqlerro = true;
        break;
      }

      $res = $clconlancamdoc->excluir($codlan);
      if ($clconlancamdoc->erro_status == '0') {
        db_msgbox($clconlancamdoc->erro_msg);
        $sqlerro = true;
        break;

      }

      $res = $clconlancamdot->excluir($codlan);
      if ($clconlancamdot->erro_status == '0') {
        db_msgbox($clconlancamdot->erro_msg);
        $sqlerro = true;
        break;
      }

      $res = $clorcsuplem->sql_record($clorcsuplem->sql_query($codsup, "o46_tiposup"));
			if ($clorcsuplem->numrows == 0) {
        db_msgbox("(t) Orcsuplem sem registros!");
        $sqlerro = true;
        break;
			}
			db_fieldsmemory($res, 0);

      //if ($o46_tiposup != 1014) {
				// se for diferente de transferencia de recurso
				// isso porque o tipo 1014 nao gera conlancamval
				// e nao pode dar erro aqui nesse ponto
				// pois se nao existir conlancamval deve dar erro
				// mas somente para os casos diferente de 1014

      $oDaoDetalhes = new cl_contacorrentedetalheconlancamval();
      $oDaoDetalhes->excluir(null, "c28_conlancamval in (select c69_sequen from conlancamval where c69_codlan = {$codlan})");
      if ($oDaoDetalhes->erro_status == '0') {
        db_msgbox("Não foi possível excluir o detalhamento do conta corrente. Lançamento {$codlan}");
        $erro = true;
      }

				$res = $clconlancamval->excluir_codlan($codlan);
				if ($clconlancamval->erro_status == '0') {
					db_msgbox("(t)Conlancamval  ".$clconlancamval->erro_msg);
					$sqlerro = true;
					break;
				}
			//}

      $oDaoConlancamInstit = new cl_conlancaminstit();
      $oDaoConlancamInstit->excluir(null, "c02_codlan = {$codlan}");
      if ($oDaoConlancamInstit->erro_status == "0") {
        db_msgbox($oDaoConlancamInstit->erro_msg);
        $erro = true;
      }

      $oDaoConlancamOrdem = new cl_conlancamordem();
      $oDaoConlancamOrdem->excluir(null, "c03_codlan = {$codlan}");
      if ($oDaoConlancamOrdem->erro_status == "0") {
        db_msgbox($oDaoConlancamOrdem->erro_msg);
        $erro = true;
      }

      $res = $clconlancam->excluir($codlan);
      if ($clconlancam->erro_status == '0') {
        db_msgbox($clconlancam->erro_msg);
        $sqlerro = true;
        break;

      }

    } // END FOR

  }

  if ($sqlerro==false){
  	// seleciona todas as reduções para recriar as reservas de saldo
    $res = $clorcsuplemval->sql_record($clorcsuplemval->sql_query(null,null,null,"o47_codsup,o47_anousu,o47_coddot,o47_valor",null," o47_codsup=$codsup and o47_valor < 0"));

    if ($clorcsuplemval->numrows > 0 ){
      if ($debug==true) db_criatabela($res);

      $rows = $clorcsuplemval->numrows;
      for ($x=0;$x <$rows;$x++){
        db_fieldsmemory($res,$x);


        // verifica se tem saldo a reservar na dotação
        /*
        $resdot= db_dotacaosaldo(8,2,2,"true","o58_coddot=$o47_coddot",db_getsession("DB_anousu"),$anousu.'-01-01',$anousu.'-12-31');
        if ($debug==true) db_criatabela($resdot);
        db_fieldsmemory($resdot,0);
        if ( abs($o47_valor)  > $atual_menos_reservado ){
          $sqlerro =true;
          db_msgbox("Dotação $o47_coddot sem saldo ! (Saldo $atual_menos_reservado) ");
          break;
        }
        */
        // recria reserva de saldo
        if ($sqlerro == false ) {
          // lança reserva
          $clorcreserva->o80_anousu = $anousu;
          $clorcreserva->o80_coddot = $o47_coddot;
          $clorcreserva->o80_dtlanc = date("Y-m-d", db_getsession('DB_datausu'));
          $clorcreserva->o80_dtini  = date("Y-m-d", db_getsession('DB_datausu'));
          $clorcreserva->o80_dtfim  = db_getsession('DB_anousu')."-12-31";
          $clorcreserva->o80_valor  = abs($o47_valor);
          $clorcreserva->o80_descr  = "suplementacao, reserva recriada por desprocessamento ";
          if ($sqlerro==false){
            $clorcreserva->incluir("");
            if ($clorcreserva->erro_status == 0 ){
            	$sqlerro = true;
              db_msgbox("( Dotação $o47_coddot )  ".$clorcreserva->erro_msg);
            }
          }
          $clorcreservasup->o81_codres = $clorcreserva->o80_codres;
          $clorcreservasup->o81_codsup = $o47_codsup;
          if ($sqlerro == false){
            $clorcreservasup->incluir($clorcreservasup->o81_codres);
            if ($clorcreservasup->erro_status == 0 ){
              $sqlerro = true;
              db_msgbox($clorcreservasup->erro_msg);
            }
          }

        }//-- END IF ($sqlerro)
      } //-- END LOOP
    } //--
  }

  // seleciona todas as suplementações, para verificar se não iram ficar negativas
  $res = $clorcsuplemval->sql_record($clorcsuplemval->sql_query(null,null,null,"o47_codsup,o47_anousu,o47_coddot,o47_valor",null," o47_codsup=$codsup and o47_valor > 0"));
  if ($clorcsuplemval->numrows > 0 ){
    if ($debug==true) db_criatabela($res);

    $rows = $clorcsuplemval->numrows;
    for ($x=0;$x <$rows;$x++){
      db_fieldsmemory($res,$x);

      // verifica se tem saldo a des-suplementar na dotacao

      $resdot= db_dotacaosaldo(8,2,2,"true","o58_coddot=$o47_coddot",db_getsession("DB_anousu"),$anousu.'-01-01',$anousu.'-12-31');
      db_fieldsmemory($resdot,0);
      if ( $atual_menos_reservado < 0 ){
        $sqlerro =true;
        db_msgbox("Dotação $o47_coddot não pode ser desprocessada porque ficara com saldo negativo ! ( Valor $o47_valor, Saldo: $atual_menos_reservado) ");
        break;
      }
    }
  }

  return $sqlerro; // false indica sucesso

}

?>