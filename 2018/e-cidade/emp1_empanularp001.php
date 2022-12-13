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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");

include ("libs/db_liborcamento.php");
include ("classes/db_orcdotacao_classe.php");
include ("classes/db_empempenho_classe.php");
include ("classes/db_empanulado_classe.php");
include ("classes/db_empanuladoele_classe.php");
include ("classes/db_empelemento_classe.php");
include ("classes/db_empresto_classe.php");
include ("classes/db_empanuladotipo_classe.php");

$clempempenho = new cl_empempenho;
$clempanulado = new cl_empanulado;
$clempanuladoele = new cl_empanuladoele;
$clempelemento = new cl_empelemento;
$clorcdotacao = new cl_orcdotacao;
$clempresto  = new cl_empresto;

//lançamentos
include ("classes/db_conlancam_classe.php");
include ("classes/db_conlancamele_classe.php");
include ("classes/db_conlancamcompl_classe.php");
include ("classes/db_conlancamlr_classe.php");
include ("classes/db_conlancamcgm_classe.php");
include ("classes/db_conlancamemp_classe.php");
include ("classes/db_conlancamval_classe.php");
include ("classes/db_conlancamdot_classe.php");
include ("classes/db_conlancamdoc_classe.php");
include ("classes/db_conplanoreduz_classe.php");
$clconlancam = new cl_conlancam;
$clconlancamele = new cl_conlancamele;
$clconlancamcompl = new cl_conlancamcompl;
$clconlancamlr = new cl_conlancamlr;
$clconlancamcgm = new cl_conlancamcgm;
$clconlancamemp = new cl_conlancamemp;
$clconlancamval = new cl_conlancamval;
$clconlancamdot = new cl_conlancamdot;
$clconlancamdoc = new cl_conlancamdoc;
$clconplanoreduz = new cl_conplanoreduz;

include ("libs/db_libcontabilidade.php");
//retorna os arrays de lancamento...
$cltranslan = new cl_translan;
include("classes/db_pcparam_classe.php");
include("classes/db_empautitem_classe.php");
include("classes/db_solicitemprot_classe.php");
include("classes/db_solandam_classe.php");
include("classes/db_solandpadraodepto_classe.php");
include("classes/db_proctransfer_classe.php");
include("classes/db_proctransferproc_classe.php");
include("classes/db_protprocesso_classe.php");
include("classes/db_solordemtransf_classe.php");
$clpcparam = new cl_pcparam;
$clempautitem = new cl_empautitem;
$clsolicitemprot = new cl_solicitemprot;
$clsolandam = new cl_solandam;
$clsolandpadraodepto = new cl_solandpadraodepto;
$clproctransfer = new cl_proctransfer;
$clproctransferproc = new cl_proctransferproc;
$clprotprocesso = new cl_protprocesso;
$clsolordemtransf = new cl_solordemtransf;


parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$db_opcao = 22;
$db_botao = false;
$erro_msg = "";
$sqlerro     = false;


if (isset ($confirmar) || isset ($confirmarn)) {

  db_inicio_transacao();

  $informado_anular_processado     = 0;
  $informado_anular_nao_processado = 0;

  // rotina que pega os valores do empelemento
  $result_elemento = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp, null, "*"));
  if ($clempelemento->numrows > 0 && $sqlerro==false){
    // ok, retornou registros da tabela empelemento
    $numrows = $clempelemento->numrows;
    for ($linha=0;$linha < $numrows;$linha ++ ){
      db_fieldsmemory($result_elemento ,$linha);

      $p  = "elemento_".$e64_codele."_processado";
      $np ="elemento_".$e64_codele."_nao_processado";
      $digitado_processado     = $$p;
      $digitado_nao_processado = $$np;

      $informado_anular_processado     += $digitado_processado;
      $informado_anular_nao_processado += $digitado_nao_processado;
    }
  }
  // - pega o total a anular informado pelo usuario
  $total_a_anular = $informado_anular_processado + $informado_anular_nao_processado;

  // - verifica se ainda tem saldo total a anular
  $result = $clempempenho->sql_record($clempempenho->sql_query_file($e60_numemp));
  db_fieldsmemory($result, 0);

  // agora verifica se os valores a anular processados e não processados estão disponiveis
  $result_restos = $clempresto->sql_record($clempresto->sql_query(db_getsession("DB_anousu"),$e60_numemp));
  if ($clempresto->numrows > 0 )
  db_fieldsmemory($result_restos, 0);
  else
  db_msgbox("RP com valores inscrição não localizados ( Informação: Empresto ) ");

  //rotina que calcula os valores disponiveis
  $processado_no_exercicio =  $e60_vlrliq - $e91_vlrliq;

  //echo "<br> processado no exercicio".$processado_no_exercicio;
  $disponivel_anular_processado        = 0;
  $disponivel_anular_nao_processado = 0;

  // seleciona os saldos do empenho
  $result = $clempempenho->sql_record($clempempenho->sql_query_saldo($e60_numemp,0));
  if ($clempempenho->numrows>0){
    db_fieldsmemory($result,0);

    $disponivel_anular_processado        =$vlr_proc;
    $disponivel_anular_nao_processado =$vlr_nproc;

  }

  if ($informado_anular_processado > $disponivel_anular_processado){
    $erro_msg = "Valor processado não disponivel.  ( E02 )";
    db_msgbox($erro_msg);
    $sqlerro = true;
  }
  if ($informado_anular_nao_processado > $disponivel_anular_nao_processado){
    $erro_msg = "Valor não processado maior que  valor disponivel.  ( E03  $informado_anular_nao_processado, $disponivel_anular_nao_processado)";
    db_msgbox($erro_msg);
    $sqlerro = true;
  }

  // O teste abaixo teste verifica se poderá ser feito lancamento na data e se tem saldo no empenho // é mais uma validação no banco
  if($sqlerro==false){
    $documento = "31"; // não processado
    $sql = "select fc_verifica_lancamento(".$e60_numemp.",'".date("Y-m-d",db_getsession("DB_datausu"))."',".$documento.",".$informado_anular_processado.")";
    $result_erro = db_query($sql);
    $erro_msg = pg_result($result_erro,0,0);
    if(substr($erro_msg,0,2) > 0 ){
      $erro_msg = substr($erro_msg,3);
      db_msgbox($erro_msg);
      $sqlerro = true;
    }
    if ($sqlerro ==false){
      $documento = "32"; // não processado
      $sql = "select fc_verifica_lancamento(".$e60_numemp.",'".date("Y-m-d",db_getsession("DB_datausu"))."',".$documento.",".$informado_anular_nao_processado.")";
      $result_erro = db_query($sql);
      $erro_msg = pg_result($result_erro,0,0);
      if(substr($erro_msg,0,2) > 0 ){
        $erro_msg = substr($erro_msg,3);
        db_msgbox($erro_msg);
        $sqlerro = true;
      }
    }
  }

  // atualiza tabela empempenho
  if ($sqlerro == false) {

    /**
    * quando for estorno/anulação de rp processado
    * subtrair o valor liquidado do e60_vlrliq da tabela empempenho e empelemento tb.
    *
    */

    // 31 diminui liq e anu
    // 32 diminui anu

    $clempempenho->e60_vlranu = $e60_vlranu + $total_a_anular;
		$GLOBALS["HTTP_POST_VARS"]["e60_vlranu"] = $clempempenho->e60_vlranu;
    $clempempenho->e60_numemp = $e60_numemp;
		if ($digitado_processado > 0) {
      $clempempenho->e60_vlrliq = $e60_vlrliq - $digitado_processado;
			$GLOBALS["HTTP_POST_VARS"]["e60_vlrliq"] = $clempempenho->e60_vlrliq;
		}
    $clempempenho->alterar($e60_numemp);
    if ($clempempenho->erro_status == 0) {
      $sqlerro = true;
      $erro_msg = $clempempenho->erro_msg;
      db_msgbox($erro_msg);
    }

  }

  //atualiza tabela empanulado
  if ($sqlerro == false) {
    $clempanulado->e94_numemp = $e60_numemp;
    $clempanulado->e94_valor = $total_a_anular;
    $clempanulado->e94_saldoant = $total_a_anular; // carlos atualizado
    $clempanulado->e94_motivo = $c72_complem;
    $clempanulado->e94_empanuladotipo = $e94_empanuladotipo;
    $clempanulado->e94_data = date("Y-m-d", db_getsession("DB_datausu"));
    $clempanulado->incluir(null);
    $erro_msg = $clempanulado->erro_msg;
    if ($clempanulado->erro_status == 0) {
      $sqlerro = true;
    }
  }


  if ($sqlerro == false){

    $result_conand = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "pc30_contrandsol"));
    db_fieldsmemory($result_conand, 0);
    if (isset ($pc30_contrandsol) && $pc30_contrandsol == 't') {
      $result_itens_transf=$clempautitem->sql_record($clempautitem->sql_query_anuaut(null,null," distinct pc11_codigo as cod_item",null,"e54_anulad is null and e61_numemp = $e60_numemp"));
      $numrows_itens_transf=$clempautitem->numrows;
      if ($numrows_itens_transf>0){
        db_fieldsmemory($result_itens_transf,0);
        $result_ond = $clsolandam->sql_record($clsolandam->sql_query_andpad(null,"*",null,"pc43_solicitem = $cod_item and pc47_pctipoandam=6"));
        $where_sol="";
        if ($clsolandam->numrows>0){
          $where_sol=" pc47_pctipoandam=5 ";
        }else{
          $where_sol=" pc47_pctipoandam=3 ";
        }
        $result_dest_ord = $clsolandpadraodepto->sql_record($clsolandpadraodepto->sql_query(null, "*", null, "pc47_solicitem=$cod_item and $where_sol"));
        if ($clsolandpadraodepto->numrows>0){
          db_fieldsmemory($result_dest_ord,0);
          $depto_dest = $pc48_depto;
          $ordem = $pc47_ordem;
          $clproctransfer->p62_hora = db_hora();
          $clproctransfer->p62_dttran = date("Y-m-d", db_getsession("DB_datausu"));
          $clproctransfer->p62_id_usuario = db_getsession("DB_id_usuario");
          $clproctransfer->p62_coddepto = db_getsession("DB_coddepto");
          $clproctransfer->p62_coddeptorec = $depto_dest;
          $clproctransfer->p62_id_usorec = '0';
          $clproctransfer->incluir(null);
          $codtran=$clproctransfer->p62_codtran;
          //db_msgbox("proctransfer");
          if ($clproctransfer->erro_status == 0) {
            $sqlerro = true;
            $erro_msg=$clproctransfer->erro_msg;
          }
        }
      }
      if (isset($codtran)&&$codtran!=""){
        for($w=0;$w<$numrows_itens_transf;$w++){
          db_fieldsmemory($result_itens_transf,$w);
          if ($sqlerro == false) {
            $result_proc=$clsolicitemprot->sql_record($clsolicitemprot->sql_query_file($cod_item));
            if ($clsolicitemprot->numrows>0){
              db_fieldsmemory($result_proc,0);
              $clproctransferproc->incluir($codtran,$pc49_protprocesso);
              if ($clproctransferproc->erro_status==0){
                $sqlerro=true;
                $erro_msg=$clproctransferproc->erro_msg;
              }
              if ($sqlerro == false) {
                $clprotprocesso->p58_codproc= $pc49_protprocesso;
                $clprotprocesso->p58_despacho="Empenho Anulado!!";
                $clprotprocesso->alterar($pc49_protprocesso);
                if ($clprotprocesso->erro_status==0){
                  $sqlerro=true;
                  $erro_msg=$clprotprocesso->erro_msg;
                }
              }

            }
            if ($sqlerro == false) {
              $clsolordemtransf->pc41_solicitem=$cod_item;
              $clsolordemtransf->pc41_codtran=$codtran;
              $clsolordemtransf->pc41_ordem=$ordem;
              $clsolordemtransf->incluir(null);
              //	db_msgbox("solordemtransf");
              if($clsolordemtransf->erro_status==0){
                $sqlerro=true;
                $erro_msg=$clsolordemtransf->erro_msg;
              }
            }

          }	// if sqlerro
        } // end loop
      }
    }
  }

  //echo "<br><br><br> empanulado novo".$clempanulado->e94_numemp;
  //echo "<br><br><br> empanulado novo".$clempanulado->e94_codanu;

  // rotina que pega os valores do empelemento
  $result_elemento = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp, null, "*"));
  if ($clempelemento->numrows > 0 && $sqlerro==false){
    // ok, retornou registros da tabela empelemento
    $numrows = $clempelemento->numrows;
    for ($linha=0;$linha < $numrows;$linha ++ ){
      db_fieldsmemory($result_elemento ,$linha);

      $p  = "elemento_".$e64_codele."_processado";
      $np ="elemento_".$e64_codele."_nao_processado";
      $digitado_processado     = $$p;
      $digitado_nao_processado = $$np;

      $total_elemento_digitado =  $digitado_processado + $digitado_nao_processado;

      // se o valor digitado for zero ( caso com 2 desdobramentos e somente 1 esta sendo anulado )
      if ($total_elemento_digitado == 0 ) {
				continue;
			}
      //echo "<br> elemento ".$e64_codele;
      //echo "<br> procesado ".$digitado_processado;
      //echo "<br> nao processado ".$digitado_nao_processado;

      /**
      *
      */

      $clempelemento->e64_numemp = $e60_numemp;
      $clempelemento->e64_codele = $e64_codele;
			if ($digitado_processado > 0) {
        $clempelemento->e64_vlrliq = $e64_vlrliq - $digitado_processado;
			  $GLOBALS["HTTP_POST_VARS"]["e64_vlrliq"] = $clempelemento->e64_vlrliq;
			}
      $clempelemento->e64_vlranu     =  $e64_vlranu + $total_elemento_digitado;
			$GLOBALS["HTTP_POST_VARS"]["e64_vlranu"] = $clempelemento->e64_vlranu;
      $clempelemento->alterar($e60_numemp, $e64_codele);
      $erro_msg = $clempelemento->erro_msg;
      if ($clempelemento->erro_status == 0) {
        $sqlerro = true;
      }
      //echo "<br> empanulado ".$clempanulado->e94_codanu;

      $clempanuladoele->e95_codanu = $clempanulado->e94_codanu; // sequencial
      $clempanuladoele->e95_codele = $e64_codele;
      $clempanuladoele->e95_valor = $total_elemento_digitado;
      $clempanuladoele->incluir($clempanulado->e94_codanu);
      $erro_msg = $clempanuladoele->erro_msg;
      if ($clempanuladoele->erro_status == 0) {
        $sqlerro = true;
      }

      /////////////////// /////////////
      ///  CONTABILIDADE  ///
      /////////////////// /////////////

      for ($vez=0;$vez < 2;$vez++ ){

        if ($vez == 0){
          $documento  = 31;
          $valor_anular = $digitado_processado;
        }else{
          $documento  = 32;
          $valor_anular = $digitado_nao_processado;
        }

        if ($valor_anular == 0) {
					continue;
				}

        if ($sqlerro == false) {
          $clconlancam->c70_anousu = db_getsession("DB_anousu");
          $clconlancam->c70_data     = date("Y-m-d", db_getsession("DB_datausu"));
          $clconlancam->c70_valor = $valor_anular;
          $clconlancam->incluir(null);
          $erro_msg = $clconlancam->erro_msg;
          if ($clconlancam->erro_status == 0) {
            $sqlerro = true;
          } else {
            $c70_codlan = $clconlancam->c70_codlan;
          }
          $lEvento = EventoContabil::vincularLancamentoNaInstituicao($clconlancam->c70_codlan , db_getsession("DB_instit"));
          $lEvento = EventoContabil::vincularOrdem($clconlancam->c70_codlan);
        }

        if ($sqlerro == false) {
          $clconlancamele->c67_codlan = $c70_codlan;
          $clconlancamele->c67_codele = $e64_codele;
          $clconlancamele->incluir($c70_codlan);
          if ($clconlancamele->erro_status == 0) {
            $sqlerro = true;
          }
        }

        if ($sqlerro == false) {
          $clconlancamcgm->c76_data = date("Y-m-d", db_getsession("DB_datausu"));
          $clconlancamcgm->c76_codlan = $c70_codlan;
          $clconlancamcgm->c76_numcgm = $e60_numcgm;
          $clconlancamcgm->incluir($c70_codlan);
          if ($clconlancamcgm->erro_status == 0) {
            $sqlerro = true;
          }
        }

        if ($sqlerro == false) {
          if ($c72_complem == '') {
            $c72_complem = 'Anulação de empenho';
          }
          $clconlancamcompl->c72_codlan = $c70_codlan;
          $clconlancamcompl->c72_complem = $c72_complem;
          $clconlancamcompl->incluir($c70_codlan);
          $erro_msg = $clconlancamcompl->erro_msg;
          if ($clconlancamcompl->erro_status == 0) {
            db_msgbox($clconlancamcompl->erro_msg);
            $sqlerro = true;
          }
        }

        if ($sqlerro == false) {
          $clconlancamemp->c75_data = date("Y-m-d", db_getsession("DB_datausu"));
          $clconlancamemp->c75_codlan = $c70_codlan;
          $clconlancamemp->c75_numemp = $e60_numemp;
          $clconlancamemp->incluir($c70_codlan);
          $erro_msg = $clconlancamemp->erro_msg;
          if ($clconlancamemp->erro_status == 0) {
            $sqlerro = true;
          }
        }

        if ($sqlerro == false) {
          $clconlancamdoc->c71_data = date("Y-m-d", db_getsession("DB_datausu"));
          $clconlancamdoc->c71_coddoc = $documento;
          $clconlancamdoc->c71_codlan = $c70_codlan;
          $clconlancamdoc->incluir($c70_codlan);
          $erro_msg = $clconlancamdoc->erro_msg;
          if ($clconlancamdoc->erro_status == 0) {
            db_msgbox($erro_msg);
            $sqlerro = true;
          }
        }

        // $cltranslan->db_trans_estorna_empenho_resto($e60_codcom, $e60_anousu, $e60_numemp);
        $cltranslan->db_trans_rp($documento, $e60_numemp);

        if ($cltranslan->sqlerro == true ){
          db_msgbox($cltranslan->erro_msg);
          $sqlerro = true;
        } else {
          $arr_debito = $cltranslan->arr_debito;
          $arr_credito = $cltranslan->arr_credito;
          $arr_histori = $cltranslan->arr_histori;
          $arr_seqtranslr = $cltranslan->arr_seqtranslr;
        }
        // echo "retorno das transações";
        //echo "<br><br><br>";
        //echo "<br> ".print_r($arr_debito);
        //echo "<br> ".print_r($arr_credito);
        //echo "<br> ".print_r($arr_histori);
        // echo "<br> ".print_r($arr_seqtranslr);
        //echo "<br>valor a anular  $valor_anular<br>";

        for ($t = 0; $t < count($arr_credito); $t ++) {
          //rotina que teste se a conta reduzida foi incluida no conplanoreduz
          $clconplanoreduz->sql_record($clconplanoreduz->sql_query_file(null,null, 'c61_codcon', '', "c61_anousu = ".DB_getsession("DB_anousu")." and c61_reduz=".$arr_debito[$t]));
          if ($clconplanoreduz->numrows == 0) {
            $sqlerro = true;
            $erro_msg = "Conta ".$arr_debito[$t]." não dísponivel para o exercicio!";
            db_msgbox($erro_msg);

          }
          $clconplanoreduz->sql_record($clconplanoreduz->sql_query_file(null,null, 'c61_codcon', '', "c61_anousu = ".DB_getsession("DB_anousu")." and c61_reduz=".$arr_credito[$t]));
          if ($clconplanoreduz->numrows == 0) {
            $sqlerro = true;
            $erro_msg = "Conta ".$arr_credito[$t]." não dísponivel para o exercicio!";
            db_msgbox($erro_msg);
          }
          //final

          if ($sqlerro == false) {
            $clconlancamval->c69_codlan = $c70_codlan;
            $clconlancamval->c69_credito = $arr_credito[$t];
            $clconlancamval->c69_debito = $arr_debito[$t];
            $clconlancamval->c69_codhist = $arr_histori[$t];
            $clconlancamval->c69_valor = $valor_anular;
            $clconlancamval->c69_data = date("Y-m-d", db_getsession("DB_datausu"));
            $clconlancamval->c69_anousu = db_getsession("DB_anousu");
            $clconlancamval->incluir(null);
            $erro_msg = $clconlancamval->erro_msg;
            if ($clconlancamval->erro_status == 0) {
              db_msgbox($erro_msg);
              $sqlerro = true;
            } else {
              $c69_sequen = $clconlancamval->c69_sequen;
            }

            if ($sqlerro == false) {
              $clconlancamlr->c81_sequen = $c69_sequen;
              $clconlancamlr->c81_seqtranslr = $arr_seqtranslr[$t];
              $clconlancamlr->incluir($c69_sequen, $arr_seqtranslr[$t]);
              $erro_msg = $clconlancamlr->erro_msg;
              if ($clconlancamlr->erro_status == 0) {
                db_msgbox($erro_msg);
                $sqlerro = true;
              }

            }

          }

        } // end Loop


      }//end Loop vez


    }//end Loop elementos

  } else {
    db_msgbox($clempelemento->erro_msg);
    $sqlerro = true;
  }


  // $sqlerro = true;
  db_fim_transacao($sqlerro);
  if ($sqlerro == false){
    // if (!isset($imprimir)){
      //   unset($e60_numemp);
    // }
  }

}

if (isset ($e60_numemp)) {
  $db_opcao = 2;
  $db_botao = true;

  //rotina que traz os dados de empempenho
  $result = $clempempenho->sql_record($clempempenho->sql_query($e60_numemp));
  db_fieldsmemory($result, 0);

  $result_restos = $clempresto->sql_record($clempresto->sql_query(db_getsession("DB_anousu"),$e60_numemp));
  if ($clempresto->numrows > 0 )
  db_fieldsmemory($result_restos, 0);
  else
  db_msgbox("RP com valores inscrição não localizados ( Informação: Empresto ) ");

  $data_atual = date("Y-m-d",db_getsession("DB_datausu"));


  // seleciona os saldos do empenho
  $result = $clempempenho->sql_record($clempempenho->sql_query_saldo($e60_numemp,0));
  // echo "<br><br><br>". $clempempenho->sql_query_saldo($e60_numemp,0);
  if ($clempempenho->numrows>0){
    db_fieldsmemory($result,0);

    $disponivel_anular_processado     = $vlr_proc;
    $disponivel_anular_nao_processado = $vlr_nproc;

    $disponivel_anular_processado     = number_format($disponivel_anular_processado , 2, ".", "");
    $disponivel_anular_nao_processado = number_format($disponivel_anular_nao_processado, 2, ".", "");

    $informado_anular_processado 	 = $disponivel_anular_processado;
    $informado_anular_nao_processado = $disponivel_anular_nao_processado;


  } else {
    db_msgbox("Falha no calculo dos saldos, contate suporte ! ");
    $disponivel_anular_processado     = 0;
    $disponivel_anular_nao_processado = 0;

    $vlr_proc  = 0;
    $vlr_nproc = 0;

  }

  //rotina que calcula os valores disponiveis
  /*
  $vlrdis = ($e60_vlremp - ($e60_vlrliq + $e60_vlranu));
  $vlranu = $vlrdis;
  if ($vlrdis == 0 || $vlrdis == '') {
    $db_opcao = 33;
  }
  $vlrdis = number_format($vlrdis, 2, ".", "");

  $processado_no_exercicio =  $e60_vlrliq - $e91_vlrliq;

  $disponivel_anular_processado        = ( $e60_vlrliq - $processado_no_exercicio ) - $e60_vlrpag ;    // liquidado - pago
  $disponivel_anular_nao_processado = ($e60_vlremp -  $e60_vlranu) -$e60_vlrliq;      // empenhado - anulado - liquidado


  $disponivel_anular_processado        = number_format($disponivel_anular_processado , 2, ".", "");
  $disponivel_anular_nao_processado = number_format($disponivel_anular_nao_processado, 2, ".", "");

  $informado_anular_processado 	    = $disponivel_anular_processado;
  $informado_anular_nao_processado = $disponivel_anular_nao_processado;
  */

  // echo "<br><br><br>";
  // echo "<br> ->> processado $disponivel_anular_processado";
  // echo "<br> ->> não processado $disponivel_anular_nao_processado";
  // echo "<br>";


  // O codigo abaixo é pra mapear no protocolo os empenhos anulados
  // Na prefeitura "X", somente é possivel anular empenho se escolher o departamento "Y"

  // -----------------------------------------------------------------------------------------------------------------------
  $result_conand = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "pc30_contrandsol"));
  db_fieldsmemory($result_conand, 0);
  if (isset ($pc30_contrandsol) && $pc30_contrandsol == 't') {
    $result_testitem=$clempautitem->sql_record($clempautitem->sql_query_anuaut(null,null," distinct pc11_codigo as cod_item",null,"e54_anulad is null and e61_numemp = $e60_numemp"));
    if ($clempautitem->numrows>0){
      for($w=0;$w<pg_numrows($result_testitem);$w++){
        db_fieldsmemory($result_testitem,$w);
        $result_prot = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_file($cod_item));
        if ($clsolicitemprot->numrows > 0) {
          $result_andam = $clsolandam->sql_record($clsolandam->sql_query_file(null, "*", "pc43_codigo desc limit 1", "pc43_solicitem=$cod_item"));
          if ($clsolandam->numrows > 0) {
            db_fieldsmemory($result_andam, 0);
            $result_tipo = $clsolandpadraodepto->sql_record($clsolandpadraodepto->sql_query(null, "*", null, "pc47_solicitem=$cod_item and pc47_ordem=$pc43_ordem"));
            if ($clsolandpadraodepto->numrows > 0) {
              db_fieldsmemory($result_tipo, 0);
              if ($pc47_pctipoandam != 7 || $pc48_depto != db_getsession("DB_coddepto")) {
                $db_botao =false;
              }// end if
            }// end if
          }//end if
        }// end if
      }// end for
    }// end if
  }// end if
  // -----------------------------------------------------------------------------------------------------------------------

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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
<tr>
<td width="360" height="18">&nbsp;</td>
<td width="263">&nbsp;</td>
<td width="25">&nbsp;</td>
<td width="140">&nbsp;</td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td> &nbsp; </td>
<tr>
<tr>
<td height="425" align="left" valign="top" bgcolor="#CCCCCC">

<?  include ("forms/db_frmempanulacaorp.php");  ?>

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
  echo "<script>document.form1.pesquisar.click();</script>";
}

if (isset ($confirmar) || isset ($confirmarn)) {
  if ($sqlerro == true) {
    db_msgbox($erro_msg);
  } else {
    // db_msgbox($ok_msg);
    if (isset ($confirmar)) {
      echo "
      <script>
      jan = window.open('emp2_anulemp002.php?e60_numemp=$e60_numemp','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
      </script>
      ";
    }

    echo "<script>document.form1.pesquisar.click();</script>";

  }

}


?>