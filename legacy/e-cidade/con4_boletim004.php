<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

if (!isset($debug)){
  $debug=false;
}
$oDaoConplanoConplanoOrcamento = new cl_conplanoconplanoorcamento;
$oDaoCorgrupoCorrente          = new cl_corgrupocorrente;
$oDaoConlancamCorgrupoCorrente = new cl_conlancamcorgrupocorrente;
db_inicio_transacao();
$erro = false;
$processa_lancamentos = false;
//db_criatabela($resultorcamentaria);exit;
if (!USE_PCASP) {

  for ($rec = 0; $rec < pg_numrows($resultorcamentaria); $rec ++) {
    db_fieldsmemory($resultorcamentaria, $rec);

  //	echo "k02_codrec: $k02_codrec - $o70_codigo<br>";

    $vezes = 0;
    while ($vezes < 2) {
      unset ($dbrec);
      $vezes += 1;
      if ($vezes == 1) {
        $lancar = true;
        if ($arrecada == 0) {
          continue;
        }
      } else {
        $lancar = false;
        if ($estorna == 0) {
          break;
        }
        $arrecada = $estorna;
      }
      //echo $arrecada."<br>";
      $sqlrec = "select o57_fonte
      from orcreceita
      inner join orcfontes on o57_codfon = o70_codfon and orcfontes.o57_anousu = o70_anousu
      inner join orcfontesdes on o60_anousu = orcreceita.o70_anousu and o57_codfon = o60_codfon
      where o70_instit = ".db_getsession("DB_instit")." and o70_anousu = ".db_getsession("DB_anousu")." and o70_codrec = $k02_codrec ";
      $resultrec = pg_query($sqlrec) or die($sqlrec);
      if ($debug==true){
        db_criatabela($resultrec);
      }
      if ($resultrec != false) {
        //db_criatabela($resultrec);exit;
        if (pg_numrows($resultrec) > 0 and $o70_codigo == 1) {
          db_fieldsmemory($resultrec, 0);
          // pesquisa a conta mae para buscar os desdobramentos da receita
          $contamae = db_le_mae_rec_sin($o57_fonte, false);
          $sqlm="select o70_codrec,o60_perc
          from orcfontes
          inner join orcfontesdes on o57_codfon = o60_codfon and o60_anousu =orcfontes.o57_anousu
          inner join orcreceita on o70_codfon = o57_codfon and o70_anousu =  o57_anousu
          where o57_anousu = ".db_getsession("DB_anousu")." and o70_instit=".db_getsession("DB_instit")." and o57_fonte like '$contamae%'
          order by o57_fonte";
          $resultm = pg_query($sqlm) or die($sqlm);
          if ($debug==true){
            db_criatabela($resultm);
          }
          if (pg_numrows($resultm) != 0) {
            $vlrsoma = 0;
            $multiplica = false;
            if ($arrecada < 0) {
              $multiplica = true;
              $arrecada = round($arrecada * -1, 2);
            }
            $primeiro_codrec = 0;
            for ($recc = 0; $recc < pg_numrows($resultm); $recc ++) {
              db_fieldsmemory($resultm, $recc);
              if($primeiro_codrec == 0 ){
                $primeiro_codrec = $o70_codrec;
              }
              // aplica o percentual sobre o valor
              $vlrperc = db_formatar(round($arrecada * ($o60_perc / 100 ), 2), 'p') + 0;
              if($vlrperc == 0){
                continue;
              }
              $vlrsoma = round($vlrsoma + $vlrperc, 2);
              if ($vlrsoma > $arrecada) {
                // arredonda no ultimo desdobramento
                $vlrperc = round($vlrperc -round($vlrsoma - $arrecada, 2), 2);
              }
              $dbrec[$o70_codrec] = round($vlrperc, 2);
            }
            if ($vlrsoma < $arrecada) {
              if(!isset($dbrec[$primeiro_codrec])){
                $dbrec[$primeiro_codrec] = 0;
              }
              $vlrperc = round( $dbrec[$primeiro_codrec] + round($arrecada - $vlrsoma, 2), 2);
              $dbrec[$primeiro_codrec] = round($vlrperc, 2);
            }
            if ($multiplica) {
              reset($dbrec);
              for ($arrr = 0; $arrr < sizeof($dbrec); $arrr ++) {
                $dbrec[key($dbrec)] = round($dbrec[key($dbrec)] * -1, 2);
                next($dbrec);
              }
            }
          } else {
            $msg_erro = "Verifique as fontes das receitas. Receita: $k02_codrec";
            db_msgbox($msg_erro);
            $erro = true;
          }
        } else {
          $dbrec[$k02_codrec] = round($arrecada, 2);
        }
      } else {
        $erro = true;
        $msg_erro = "Erro arquivo orcreceita. Receita: $k02_codrec";
        db_msgbox($msg_erro);
      }
      if ($debug==true){
        print_r($dbrec);
      }
      //echo "$erro---<br>";
      if ($erro == false) {
        // passa aqui quando tem desdobramento (existe no arquivo orcreceitades)
        reset($dbrec);
        for ($i = 0; $i < sizeof($dbrec); $i ++) {
          $codrec = key($dbrec);
          $valor = $dbrec[key($dbrec)];

          if ($valor==0){
            // quando entraria neste if ?
            // por exemplo:
            // arrecadar 0,01 cents de receita e esta receita ser desdobrada em 3,
            // ai só ocorrerá arrecadação na primeira
            continue;
          }
          //db_msgbox($codrec.'-'.$valor);
          $clorcreceitaval->o71_anousu = db_getsession("DB_anousu");
          $clorcreceitaval->o71_codrec = $codrec;
          $clorcreceitaval->o71_coddoc = ($lancar == true ? 100 : 101);
          $clorcreceitaval->o71_mes = $c70_data_mes;
          $clorcreceitaval->o71_valor = $valor;

          $result = $clorcreceitaval->sql_record($clorcreceitaval->sql_query_file($clorcreceitaval->o71_anousu, $clorcreceitaval->o71_codrec, $clorcreceitaval->o71_coddoc, $clorcreceitaval->o71_mes));
          if ($clorcreceitaval->numrows == 0) {
            $clorcreceitaval->incluir($clorcreceitaval->o71_anousu, $clorcreceitaval->o71_codrec, $clorcreceitaval->o71_coddoc, $clorcreceitaval->o71_mes);
            if ($clorcreceitaval->erro_status == '0') {
              $msg_erro = $clorcreceitaval->erro_msg;
              db_msgbox($msg_erro);
              $erro = true;
              break;
            }
          } else {
            db_fieldsmemory($result, 0);
            $clorcreceitaval->o71_valor = round($o71_valor + $valor, 2);
            $clorcreceitaval->alterar($clorcreceitaval->o71_anousu, $clorcreceitaval->o71_codrec, $clorcreceitaval->o71_coddoc, $clorcreceitaval->o71_mes);
            if ($clorcreceitaval->erro_status == '0') {
              $msg_erro = $clorcreceitaval->erro_msg;
              db_msgbox($msg_erro);
              $erro = true;
              break;
            }
          }

          //  valores e contas do lançamento
          $result = $clorcreceita->sql_record($clorcreceita->sql_query_file(db_getsession("DB_anousu"), $codrec, 'o70_codfon'));
          if ($clorcreceita->numrows == 0) {
            $msg_erro = 'Er: Orcreceita - Receita não cadastrada .('.db_getsession("DB_anousu").','.$codrec.') Possível causa: Sem previsão no Orçamento para esta instituição !';
            db_msgbox($msg_erro);
            $erro = true;
            break;
          }
          db_fieldsmemory($result, 0);
          /*
          *
          *
          */
          $sSqlReduzido = $clconplanoreduz->sql_query_file(null,
                                                           null,
                                                           'c61_reduz as contacerta',
                                                           'c61_reduz',
                                                           "c61_codcon = {$o70_codfon}
                                                            and c61_anousu=".db_getsession("DB_anousu")."
                                                            and c61_instit=".db_getsession("DB_instit")
                                                           );
          if (USE_PCASP) {

            $sSqlReduzido = $oDaoConplanoConplanoOrcamento->sql_query_pcasp_analitica(null,
                                                     'conplanoreduz.c61_reduz as contacerta',
                                                     'conplanoreduz.c61_reduz',
                                                     "conplanoorcamentoanalitica.c61_codcon = {$o70_codfon}
                                                      and conplanoorcamentoanalitica.c61_anousu=".db_getsession("DB_anousu")."
                                                      and conplanoorcamentoanalitica.c61_instit=".db_getsession("DB_instit")
                                                     );
          }
          $resultrecr = $clconplanoreduz->sql_record($sSqlReduzido);
          if ($clconplanoreduz->numrows == 0) {
            $msg_erro = 'Er:Conplanoreduz - Receita não cadastrada no plano.( Receita no Plano (codcon): '.$o70_codfon.'  Receita no caixa : '.$codrec.'  )';
            db_msgbox($msg_erro);
            $erro = true;
            /*
            * pode ser que a receita acima exista na orcfontes e nao exista no conplano
            *
            */
            break;
          } else {
            db_fieldsmemory($resultrecr, 0);
          }

          $receita_deducao = false;
          $sql = "select c60_estrut
          from conplano
          where c60_codcon = $o70_codfon and
          c60_anousu=".db_getsession("DB_anousu")." and
          fc_conplano_grupo(".db_getsession("DB_anousu").",substr(c60_estrut,1,2)||'%',9000) is true";

          $resultded = pg_exec(analiseQueryPlanoOrcamento($sql));
          if (pg_numrows($resultded) > 0) {
            $receita_deducao = true; // sempre que for 49xx e for negativo deduz, se for positivo estorna
            if($valor>0){
              $lancar = false;
              $valor = $valor * -1;
            }else{
              $lancar = true;
              $valor = $valor * -1;
            }
          }

          // inclusao de lançamentos

          $clconlancam->c70_codlan = 0;
          $clconlancam->c70_anousu = db_getsession("DB_anousu");
          $clconlancam->c70_data = $c70_data_ano."-".$c70_data_mes."-".$c70_data_dia;
          $clconlancam->c70_valor = abs($valor);
          $result = $clconlancam->incluir($clconlancam->c70_codlan);
          if ($clconlancam->erro_status == '0') {
            $msg_erro = $clconlancam->erro_msg;
            db_msgbox($msg_erro);
            $erro = true;
            break;
          }
          ///--------------------------------------------

          // inclusao historico de lançamentos
          if (!empty ($k12_histcor)) {

            $clconlancamcompl->c72_codlan  = 0;
            $clconlancamcompl->c72_complem = pg_escape_string($k12_histcor);
            $result = $clconlancamcompl->incluir($clconlancam->c70_codlan);
            if ($clconlancamcompl->erro_status == '0') {
              $msg_erro = $clconlancamcompl->erro_msg;
              db_msgbox($msg_erro);
              $erro = true;
              break;
            }
          }

          if (!empty ($k12_codcla)) {
            $clconlancamcompl->c72_codlan = 0;
            $clconlancamcompl->c72_complem = pg_escape_string("FICHA DE COMPENSAÇÃO CLASSIFICAÇÃO: $k12_codcla");
            $result = $clconlancamcompl->incluir($clconlancam->c70_codlan);
            if ($clconlancamcompl->erro_status == '0') {
              $msg_erro = $clconlancamcompl->erro_msg;
              db_msgbox($msg_erro);
              $erro = true;
              break;
            }
          }
          // grava cgm da receita orçamentaria
          if (!empty ($cgm_estornado) || !empty ($cgm_pago)) {
            $clconlancamcgm->c76_codlan = 0;
            $clconlancamcgm->c76_data   = $clconlancam->c70_data;
            $clconlancamcgm->c76_numcgm = $cgm_pago!=""?$cgm_pago:$cgm_estornado;
            $result = $clconlancamcgm->incluir($clconlancam->c70_codlan);
            if ($clconlancamcgm->erro_status == '0') {
              $msg_erro = $clconlancamcgm->erro_msg;
              db_msgbox($msg_erro);
              $erro = true;
              break;
            }
          }
          ///--------------------------------------------

          /// inclui no conlancambol

          $clconlancambol->c77_id       = $k12_id;
          $clconlancambol->c77_autent   = $k12_autent;
          $clconlancambol->c77_databol  = $clconlancam->c70_data;

          $clconlancambol->c77_anousu   = $clconlancam->c70_anousu;
          $clconlancambol->c77_dataproc = $clconlancam->c70_data;
          $clconlancambol->c77_boletim  = $k11_numbol; // não usado para identificar o boletim
          $clconlancambol->c77_instit   = db_getsession("DB_instit");
          $clconlancambol->c77_valor    = $clconlancam->c70_valor;

          $result = $clconlancambol->incluir($clconlancam->c70_codlan);
          if ($clconlancambol->erro_status == '0') {
            $msg_erro = $clconlancambol->erro_msg;
            db_msgbox("01 ".$msg_erro);
            $erro = true;
            break;
          }
          /**
           * - Pesquisamos na tabela corgrupocorrente para ver se a autenticaçao corrente
           *   faz parte de um grupo de lancamentos, caso ele pertenca ai grupo,
           *   lancamos ele na tabela colancamcorgrupocorrente.
           * - devemos pegar apenas os registro que possuem k12_id <> 0 e k12_autent <> 0
           */
          if ($k12_id  != 0 and $k12_autent != 0) {

            $sSqlCorrente  = $oDaoCorgrupoCorrente->sql_query_file(null,
                                                                   "k105_sequencial",
                                                                   null,
                                                                   "k105_id = {$k12_id}
                                                                   and k105_autent = {$k12_autent}
                                                                   and k105_data  = '{$data}'"
                                                                  );

            $rsCorrenteGrupo = $oDaoCorgrupoCorrente->sql_record($sSqlCorrente);
            if ($oDaoCorgrupoCorrente->numrows > 0) {

  //            echo "aqui....{$clconlancam->c70_codlan}<br>";
              $oDaoConlancamCorgrupoCorrente->c23_conlancam        = $clconlancam->c70_codlan;
              $oDaoConlancamCorgrupoCorrente->c23_corgrupocorrente = db_utils::fieldsMemory($rsCorrenteGrupo,0)->k105_sequencial;
              $oDaoConlancamCorgrupoCorrente->incluir(null);
              if ($oDaoConlancamCorgrupoCorrente->erro_status == 0) {

                $msg_erro = $oDaoConlancamCorgrupoCorrente->erro_msg;
                db_msgbox($msg_erro);
                $erro = true;
                break;

              }
            }
          }
          //  receita do lançamento
          $clconlancamrec->c74_codlan = $clconlancam->c70_codlan;
          $clconlancamrec->c74_anousu = db_getsession("DB_anousu");
          $clconlancamrec->c74_data = $c70_data_ano."-".$c70_data_mes."-".$c70_data_dia;
          $clconlancamrec->c74_codrec = $codrec;
          $result = $clconlancamrec->incluir($clconlancam->c70_codlan);
          if ($clconlancamrec->erro_status == '0') {
            $msg_erro = $clconlancamrec->erro_msg;
            db_msgbox($msg_erro);
            //db_msgbox('00');
            $erro = true;
            break;
          }
          //  documento do lançamento
          $clconlancamdoc->c71_codlan = $clconlancam->c70_codlan;
          $clconlancamdoc->c71_coddoc = ($lancar == true ? 100 : 101);
          //$clconlancamdoc->c71_coddoc = 100;
          $clconlancamdoc->c71_data = $c70_data_ano."-".$c70_data_mes."-".$c70_data_dia;
          $result = $clconlancamdoc->incluir($clconlancam->c70_codlan);
          if ($clconlancamdoc->erro_status == '0') {
            $msg_erro = $clconlancamdoc->erro_msg;
            db_msgbox($msg_erro);
            $erro = true;
            break;
          }

          //  conta derecebimento da receita
          $clconlancampag->c82_codlan = $clconlancam->c70_codlan;
          $clconlancampag->c82_anousu = db_getsession("DB_anousu");
          $clconlancampag->c82_reduz = $k12_conta;
          $result = $clconlancampag->incluir($clconlancam->c70_codlan);
          if ($clconlancampag->erro_status == '0') {
            $msg_erro = $clconlancampag->erro_msg;
            db_msgbox($msg_erro);
            $erro = true;
            break;
          }

          $cltranslan = new cl_translan;
          //$cltranslan->db_trans_arrecada_receita($k12_conta,$contacerta,db_getsession("DB_anousu"));

          if ($lancar) {

            if ($receita_deducao == true ) {
              $cltranslan->db_trans_estorno_receita($k12_conta, $contacerta, db_getsession("DB_anousu"));
            } else {
              $cltranslan->db_trans_arrecada_receita($k12_conta, $contacerta, db_getsession("DB_anousu"));
            }
            //$cltranslan->db_trans_arrecada_receita($k12_conta,$contacerta,db_getsession("DB_anousu"));

          } else {

            if ($receita_deducao == true ) {
              $cltranslan->db_trans_arrecada_receita($k12_conta, $contacerta, db_getsession("DB_anousu"));
            } else {
              $cltranslan->db_trans_estorno_receita($k12_conta, $contacerta, db_getsession("DB_anousu"));
            }
            //$cltranslan->db_trans_estorno_receita($k12_conta,$contacerta,db_getsession("DB_anousu"));
          }

          //print_r($cltranslan->arr_debito) ."-njdigfner-<br>";
          //print_r($cltranslan->arr_credito) ."--<br>";
          //print_r($cltranslan->arr_histori) ."--<br>";
          //exit;

          for ($l = 0; $l < sizeof($cltranslan->arr_credito); $l ++) {
            $clconlancamval->c69_sequen = 0;
            $clconlancamval->c69_anousu = db_getsession("DB_anousu");
            $clconlancamval->c69_codlan = $clconlancam->c70_codlan;
            $clconlancamval->c69_codhist = $cltranslan->arr_histori[$l];
            $clconlancamval->c69_credito = $cltranslan->arr_credito[$l];
            $clconlancamval->c69_debito = $cltranslan->arr_debito[$l];
            $clconlancamval->c69_valor = abs($valor);
            $clconlancamval->c69_data = $c70_data_ano."-".$c70_data_mes."-".$c70_data_dia;

            $result = $clconlancamval->incluir($clconlancamval->c69_sequen);
            if ($clconlancamval->erro_status == '0') {
              $msg_erro = $clconlancamval->erro_msg;
              db_msgbox('Erro ao gerar lançamento no conlancamval. (3)('.$msg_erro.') Especificação: D:'.$clconlancamval->c69_debito     .',C:'.$clconlancamval->c69_credito.'   ');
              $erro = true;
              break;
            }
            // variavel para indicar se processa lancamentos
            if ($erro == false){
              $processa_lancamentos = true;
            }


            $clconlancamlr->c81_sequen = $clconlancamval->c69_sequen;
            $clconlancamlr->c81_seqtranslr = $cltranslan->arr_seqtranslr[$l];
            $result = $clconlancamlr->incluir($clconlancamlr->c81_sequen, $clconlancamlr->c81_seqtranslr);
            if ($clconlancamlr->erro_status == '0') {
              $msg_erro = $clconlancamlr->erro_msg;
              db_msgbox($msg_erro);
              $erro = true;
              break;
            }

          }
          next($dbrec);
        }
      }
    }

  }
}

/// fim da receira orçamentaria
/// inicio das transferencia e despesa extra-orcamentaria

if (!USE_PCASP) {
  if ($erro == false) {

    $sql = "
    select
    id,
    data,
    autent,
    credito,
    case when k12_estorn =  'f' then valor else 0 end as valor,
    case when k12_estorn <> 'f' then valor else 0 end as estorno,
    debito,
    k17_hist,
    k17_texto,
    k17_codigo
    from (
    select corrente.k12_id as id,
    corrente.k12_data  as data,
    corrente.k12_autent as autent,
    corrente.k12_valor as valor,
    corrente.k12_conta as credito,
    b.k12_conta as debito,
    k12_estorn,
    k17_hist,
    k17_texto,
    k17_codigo
    from corrente
    inner join corlanc b on corrente.k12_id = b.k12_id
    and corrente.k12_autent=b.k12_autent
    and corrente.k12_data = b.k12_data
    inner join saltes c   on c.k13_conta = corrente.k12_conta
    inner join saltes d   on d.k13_conta = b.k12_conta
    inner join conplanoreduz r1 on b.k12_conta = r1.c61_reduz and
    r1.c61_anousu = ".db_getsession("DB_anousu")." and
    r1.c61_instit = ".db_getsession("DB_instit")."
    inner join conplanoreduz r2 on corrente.k12_conta = r2.c61_reduz and
    r2.c61_anousu = ".db_getsession("DB_anousu")." and
    r2.c61_instit = ".db_getsession("DB_instit")."
    inner join slip on k17_codigo = b.k12_codigo
    where corrente.k12_data = '$data' and corrente.k12_instit = ".db_getsession('DB_instit')."
    order by corrente.k12_conta,
    b.k12_conta
    ) as x ";
    $resultdesp = pg_query($sql);
    if ($debug==true){
      db_criatabela($resultdesp);
    }
    if (pg_numrows($resultdesp) > 0) {
      for ($xdesp = 0; $xdesp < pg_numrows($resultdesp); $xdesp ++) {
        db_fieldsmemory($resultdesp, $xdesp);
        //
        if ($estorno != 0) {
          $valor = $estorno;
        }

        $clconlancam->c70_codlan = 0;
        $clconlancam->c70_anousu = db_getsession("DB_anousu");
        $clconlancam->c70_data = $c70_data_ano."-".$c70_data_mes."-".$c70_data_dia;
        if ($arrecada_boletim == true) {
          $clconlancam->c70_valor = ($valor > 0 ? $valor : $valor * -1);
        } else {
          $clconlancam->c70_valor = ($valor > 0 ? $valor * -1 : $valor);
        }
        $result = $clconlancam->incluir($clconlancam->c70_codlan);
        if ($clconlancam->erro_status == '0') {
          $msg_erro =$clconlancam->erro_msg;
          $erro = true;
          break;

        }
        ///--------------------------------------------
        // inclusao historico de lançamentos
        if (!empty ($k17_texto)) {
          $clconlancamcompl->c72_codlan = 0;
          $clconlancamcompl->c72_complem = pg_escape_string("Slip: $k17_codigo  $k17_texto");
          $result = $clconlancamcompl->incluir($clconlancam->c70_codlan);
          if ($clconlancamcompl->erro_status == '0') {
            $msg_erro = $clconlancamcompl->erro_msg;
            $erro = true;
            break;
          }
        }
        /// inclui no conlancambol
        /**
        c77_id       | integer          | default 0
        c77_databol  | date             |              // data do boletim, mesma do corrente
        c77_autent   | integer          | def
        c77_instit   | integer          | default 0
        c77_codlan   | bigint           | not null default 0
        c77_anousu   | integer          | default 0
        c77_boletim  | double precision | default 0    // seria o numero do boletim, mas nao é usado pra nada
        c77_dataproc | date             |              // data de processamento (data atual)
        c77_valor    | double precision | default 0
        */
        $clconlancambol->c77_id       = $id;
        $clconlancambol->c77_autent   = $autent;
        $clconlancambol->c77_databol  = $clconlancam->c70_data;

        $clconlancambol->c77_anousu   = $clconlancam->c70_anousu;
        $clconlancambol->c77_dataproc = $clconlancam->c70_data;
        $clconlancambol->c77_boletim  = $k11_numbol; // não usado para identificar o boletim
        $clconlancambol->c77_instit   = db_getsession("DB_instit");
        $clconlancambol->c77_valor    = $clconlancam->c70_valor;

        $result = $clconlancambol->incluir($clconlancam->c70_codlan);
        if ($clconlancambol->erro_status == '0') {
          $msg_erro = $clconlancambol->erro_msg;
          db_msgbox("02".$msg_erro);
          $erro = true;
          break;
        }

        $clconlancamval->c69_sequen = 0;
        $clconlancamval->c69_anousu = db_getsession("DB_anousu");
        $clconlancamval->c69_codlan = $clconlancam->c70_codlan;
        $clconlancamval->c69_codhist = $k17_hist;
        $clconlancamval->c69_credito = ($valor > 0 ? $credito : $debito);
        $clconlancamval->c69_debito = ($valor > 0 ? $debito : $credito);

        if ($arrecada_boletim == true) {
          $clconlancamval->c69_valor = ($valor > 0 ? $valor : $valor * -1);
        } else {
          $clconlancamval->c69_valor = ($valor > 0 ? $valor * -1 : $valor);
        }

        $clconlancamval->c69_data = $c70_data_ano."-".$c70_data_mes."-".$c70_data_dia;

        $result = $clconlancamval->incluir($clconlancamval->c69_sequen);
        if ($clconlancamval->erro_status == '0') {
          $msg_erro = $clconlancamval->erro_msg;
          db_msgbox('Erro ao gerar lançamento no conlancamval. (1)('.$msg_erro.')');
          $erro = true;
          exit;
          break;
        }
        // variavel para indicar se processa lancamentos
        if ($erro == false){
          $processa_lancamentos = true;
        }

      }
    }
  }
}

/// fim da receira orçamentaria
/// despesa extra-orcamentaria
if (!USE_PCASP) {
  if ($erro == false) {

    $sql = "
    select
    id,
    data,
    autent,
    credito,
    case when k12_estorn =  'f' then valor else 0 end as valor,
    case when k12_estorn <> 'f' then valor else 0 end as estorno,
    debito,
    k17_hist,
    k17_texto,
    k17_codigo
    from (
    select corrente.k12_id as id,
    corrente.k12_data  as data,
    corrente.k12_autent as autent,
    corrente.k12_valor as valor,
    corrente.k12_conta as credito,
    b.k12_conta as debito,
    k12_estorn,
    k17_hist,
    k17_texto,
    k17_codigo
    from corrente
    inner join corlanc b on corrente.k12_id = b.k12_id
    and corrente.k12_autent=b.k12_autent
    and corrente.k12_data = b.k12_data
    inner join saltes c   on c.k13_conta = corrente.k12_conta
    inner join conplanoreduz r1 on b.k12_conta = r1.c61_reduz and
    r1.c61_anousu = ".db_getsession("DB_anousu")." and
    r1.c61_instit = ".db_getsession("DB_instit")."
    inner join conplanoreduz r2 on corrente.k12_conta = r2.c61_reduz and
    r2.c61_anousu = ".db_getsession("DB_anousu")." and
    r2.c61_instit = ".db_getsession("DB_instit")."
    inner join slip on k17_codigo = b.k12_codigo
    where corrente.k12_data = '$data' and corrente.k12_instit = ".db_getsession('DB_instit')." and
    b.k12_conta not in (select k13_conta from saltes)
    order by corrente.k12_conta,
    b.k12_conta
    ) as x ";
    $resultdesp = pg_query($sql);
    if ($debug==true){
      db_criatabela($resultdesp);
    }
    if (pg_numrows($resultdesp) > 0) {
      for ($xdesp = 0; $xdesp < pg_numrows($resultdesp); $xdesp ++) {
        db_fieldsmemory($resultdesp, $xdesp);
        //
        if ($estorno != 0) {
          $valor = $estorno;
        }
        if ($erro == false){
          $processa_lancamentos = true;
        }

        $clconlancam->c70_codlan = 0;
        $clconlancam->c70_anousu = db_getsession("DB_anousu");
        $clconlancam->c70_data = $c70_data_ano."-".$c70_data_mes."-".$c70_data_dia;
        if ($arrecada_boletim == true) {
          $clconlancam->c70_valor = ($valor > 0 ? $valor : $valor * -1);
        } else {
          $clconlancam->c70_valor = ($valor > 0 ? $valor * -1 : $valor);
        }
        $result = $clconlancam->incluir($clconlancam->c70_codlan);
        if ($clconlancam->erro_status == '0') {
          $msg_erro = $clconlancam->erro_msg;
          db_msgbox($msg_erro);
          $erro = true;
          break;
        }
        // inclusao historico de lançamentos
        if (!empty ($k17_texto)) {
          $clconlancamcompl->c72_codlan = 0;
          $clconlancamcompl->c72_complem = pg_escape_string("Slip $k17_codigo  $k17_texto");
          $result = $clconlancamcompl->incluir($clconlancam->c70_codlan);
          if ($clconlancamcompl->erro_status == '0') {
            $msg_erro = $clconlancamcompl->erro_msg;
            $erro = true;
            break;
          }
        }
        /// inclui no conlancambol
        $clconlancambol->c77_id       = $id;
        $clconlancambol->c77_autent   = $autent;
        $clconlancambol->c77_databol  = $clconlancam->c70_data;

        $clconlancambol->c77_anousu   = $clconlancam->c70_anousu;
        $clconlancambol->c77_dataproc = $clconlancam->c70_data;
        $clconlancambol->c77_boletim  = $k11_numbol; // não usado para identificar o boletim
        $clconlancambol->c77_instit   = db_getsession("DB_instit");
        $clconlancambol->c77_valor    = $clconlancam->c70_valor;

        $result = $clconlancambol->incluir($clconlancam->c70_codlan);
        if ($clconlancambol->erro_status == '0') {
          $msg_erro = $clconlancambol->erro_msg;
          db_msgbox("03".$msg_erro);
          $erro = true;
          break;
        }

        $clconlancamval->c69_sequen = 0;
        $clconlancamval->c69_anousu = db_getsession("DB_anousu");
        $clconlancamval->c69_codlan = $clconlancam->c70_codlan;
        $clconlancamval->c69_codhist = $k17_hist;
        $clconlancamval->c69_credito = ($valor > 0 ? $credito : $debito);
        $clconlancamval->c69_debito = ($valor > 0 ? $debito : $credito);

        if ($arrecada_boletim == true) {
          $clconlancamval->c69_valor = ($valor > 0 ? $valor : $valor * -1);
        } else {
          $clconlancamval->c69_valor = ($valor > 0 ? $valor * -1 : $valor);
        }
        $clconlancamval->c69_data = $c70_data_ano."-".$c70_data_mes."-".$c70_data_dia;

        $result = $clconlancamval->incluir($clconlancamval->c69_sequen);
        if ($clconlancamval->erro_status == '0') {
          $msg_erro = $clconlancamval->erro_msg;
          db_msgbox('Erro ao gerar lançamento no conlancamval. (1)('.$msg_erro.')');
          $erro = true;
          exit;
          break;
        }
        // variavel para indicar se processa lancamentos
        if ($erro == false){
          $processa_lancamentos = true;
        }

      }
    }
  }
}

/// fim da despesa estra e transferencias
/// inicio da receita extra
if (!USE_PCASP) {
  if ($erro == false) {

    $sql = " /*  seleciona todos os registros com histrorico sem agrupar   */

    select *
    from (
    select
    k12_conta,
    tabrec.k02_codigo,
    k02_reduz,
    sum( case when corrente.k12_estorn = 'f' then cornump.k12_valor else 0::float8 end) as arrecada,
    sum( case when corrente.k12_estorn = 't' then cornump.k12_valor*-1 else 0::float8 end) as estorna,
    k12_histcor,
    0 as k12_id,
    0 as k12_autent
    from corrente
    inner join cornump  on corrente.k12_id      = cornump.k12_id
    and corrente.k12_data   = cornump.k12_data
    and corrente.k12_autent = cornump.k12_autent
    left outer join corhist on corrente.k12_id = corhist.k12_id
    and corrente.k12_data   = corhist.k12_data
    and corrente.k12_autent = corhist.k12_autent
    inner join tabrec  on k12_receit = tabrec.k02_codigo
    inner join tabplan on tabplan.k02_codigo = tabrec.k02_codigo
    and k02_anousu = ".db_getsession('DB_anousu')."
    inner join conplanoexe on k12_conta = c62_reduz
    and c62_anousu = ".db_getsession('DB_anousu')."
    inner join conplanoreduz on c62_reduz = c61_reduz
    and c61_anousu = c62_anousu
    and c61_instit = ".db_getsession('DB_instit')."
    where corrente.k12_instit = ".db_getsession("DB_instit")." and corrente.k12_data  = '".$data."'
    and corhist.k12_id is null
    group by k12_conta,tabrec.k02_codigo,k02_reduz,k12_histcor

    ) as x
    union all

    select k12_conta,
    tabrec.k02_codigo,
    k02_reduz,
    case when corrente.k12_estorn = 'f' then cornump.k12_valor else 0::float8 end as arrecada,
    case when corrente.k12_estorn = 't' then cornump.k12_valor*-1 else 0::float8 end as estorna,
    k12_histcor,
    corrente.k12_id ,
    corrente.k12_autent
    from corrente
    inner join cornump  on corrente.k12_id      = cornump.k12_id
    and corrente.k12_data   = cornump.k12_data
    and corrente.k12_autent = cornump.k12_autent
    left outer join corhist on corrente.k12_id = corhist.k12_id
    and corrente.k12_data   = corhist.k12_data
    and corrente.k12_autent = corhist.k12_autent
    inner join tabrec  on k12_receit = tabrec.k02_codigo
    inner join tabplan on tabplan.k02_codigo = tabrec.k02_codigo
    and k02_anousu = ".db_getsession('DB_anousu')."
    inner join conplanoexe on k12_conta = c62_reduz
    and c62_anousu = ".db_getsession('DB_anousu')."
    inner join conplanoreduz on c62_reduz = c61_reduz
    and c61_anousu = c62_anousu
    and c61_instit = ".db_getsession('DB_instit')."
    where corrente.k12_instit = ".db_getsession("DB_instit")." and corrente.k12_data  = '".$data."'
    and corhist.k12_id is not null



    ";

    $resultextra = pg_query($sql);
    if ($debug==true){
      db_criatabela($resultextra);
    }
    if (pg_numrows($resultextra) > 0) {
      for ($xdesp = 0; $xdesp < pg_numrows($resultextra); $xdesp ++) {
        db_fieldsmemory($resultextra, $xdesp);
        //
        if ($erro == false){
          $processa_lancamentos = true;
        }
        if ($arrecada != 0) {
          $valor = $arrecada;
          $clconlancam->c70_codlan = 0;
          $clconlancam->c70_anousu = db_getsession("DB_anousu");
          $clconlancam->c70_data = $c70_data_ano."-".$c70_data_mes."-".$c70_data_dia;
          if ($arrecada_boletim == true) {
            $clconlancam->c70_valor = $valor;
          } else {
            $clconlancam->c70_valor = $valor * -1;
          }
          $result = $clconlancam->incluir($clconlancam->c70_codlan);
          if ($clconlancam->erro_status == '0') {
            $msg_erro = $clconlancam->erro_msg;
            db_msgbox($msg_erro);
            $erro = true;
            break;
          }
          // inclusao historico de lançamentos
          if (!empty ($k12_histcor)) {
            $clconlancamcompl->c72_codlan = 0;
            $clconlancamcompl->c72_complem = pg_escape_string($k12_histcor);
            $result = $clconlancamcompl->incluir($clconlancam->c70_codlan);
            if ($clconlancamcompl->erro_status == '0') {
              $msg_erro = $clconlancamcompl->erro_msg;
              db_msgbox($msg_erro);
              $erro = true;
              break;
            }
          }

          /// inclui no conlancambol
          $clconlancambol->c77_id       = $k12_id;
          $clconlancambol->c77_autent   = $k12_autent;
          $clconlancambol->c77_databol  = $clconlancam->c70_data;

          $clconlancambol->c77_anousu   = $clconlancam->c70_anousu;
          $clconlancambol->c77_dataproc = $clconlancam->c70_data;
          $clconlancambol->c77_boletim  = $k11_numbol; // não usado para identificar o boletim
          $clconlancambol->c77_instit   = db_getsession("DB_instit");
          $clconlancambol->c77_valor    = $clconlancam->c70_valor;
          $result = $clconlancambol->incluir($clconlancam->c70_codlan);
          if ($clconlancambol->erro_status == '0') {
            $msg_erro = $clconlancambol->erro_msg;
            db_msgbox("06".$msg_erro);
            $erro = true;
            break;
          }
          /**
           * - Pesquisamos na tabela corgrupocorrente para ver se a autenticaçao corrente
           *   faz parte de um grupo de lancamentos, caso ele pertenca ai grupo,
           *   lancamos ele na tabela colancamcorgrupocorrente.
           * - devemos pegar apenas os registro que possuem k12_id <> 0 e k12_autent <> 0
           */
          if ($k12_id  != 0 and $k12_autent != 0) {

            $sSqlCorrente  = $oDaoCorgrupoCorrente->sql_query_file(null,
                                                                   "k105_sequencial",
                                                                   null,
                                                                   "k105_id = {$k12_id}
                                                                   and k105_autent = {$k12_autent}
                                                                   and k105_data  = '{$data}'"
                                                                  );

            $rsCorrenteGrupo = $oDaoCorgrupoCorrente->sql_record($sSqlCorrente);
            if ($oDaoCorgrupoCorrente->numrows > 0) {

  //            echo "aqui....{$clconlancam->c70_codlan}<br>";
              $oDaoConlancamCorgrupoCorrente->c23_conlancam        = $clconlancam->c70_codlan;
              $oDaoConlancamCorgrupoCorrente->c23_corgrupocorrente = db_utils::fieldsMemory($rsCorrenteGrupo,0)->k105_sequencial;
              $oDaoConlancamCorgrupoCorrente->incluir(null);
              if ($oDaoConlancamCorgrupoCorrente->erro_status == 0) {

                $msg_erro = $oDaoConlancamCorgrupoCorrente->erro_msg;
                db_msgbox($msg_erro);
                $erro = true;
                break;

              }
            }
          }
          $clconlancamval->c69_sequen = 0;
          $clconlancamval->c69_anousu = db_getsession("DB_anousu");
          $clconlancamval->c69_codlan = $clconlancam->c70_codlan;
          $clconlancamval->c69_codhist = 9500;
          $clconlancamval->c69_credito = $k02_reduz;
          $clconlancamval->c69_debito = $k12_conta;

          if ($arrecada_boletim == true) {
            $clconlancamval->c69_valor = $valor;
          } else {
            $clconlancamval->c69_valor = $valor * -1;
          }
          $clconlancamval->c69_data = $c70_data_ano."-".$c70_data_mes."-".$c70_data_dia;

          $result = $clconlancamval->incluir($clconlancamval->c69_sequen);
          if ($clconlancamval->erro_status == '0') {
            $msg_erro = $clconlancamval->erro_msg;
            db_msgbox('(2) Voce está tentando debitar a conta ' . $k12_conta . ' e creditar a conta ' . $k02_reduz . ' no valor de ' . db_formatar($clconlancam->c70_valor, 'f') . ', porem essas contas nao pertences a mesma instituicao. Verifique os registros! (' . $msg_erro . ')');
            $erro = true;
            break;
          }
        }
        if ($estorna != 0) {
          $valor = $estorna;
          $clconlancam->c70_codlan = 0;
          $clconlancam->c70_anousu = db_getsession("DB_anousu");
          $clconlancam->c70_data = $c70_data_ano."-".$c70_data_mes."-".$c70_data_dia;
          if ($arrecada_boletim == true) {
            $clconlancam->c70_valor = $valor;
          } else {
            $clconlancam->c70_valor = $valor * -1;
          }
          $result = $clconlancam->incluir($clconlancam->c70_codlan);
          if ($clconlancam->erro_status == '0') {
            $msg_erro = $clconlancam->erro_msg;
            $erro = true;
            break;
          }

          //   inclusao historico de lançamentos
          if (!empty ($k12_histcor)) {
            $clconlancamcompl->c72_codlan = 0;
            $clconlancamcompl->c72_complem = pg_escape_string($k12_histcor);
            $result = $clconlancamcompl->incluir($clconlancam->c70_codlan);
            if ($clconlancamcompl->erro_status == '0') {
              $msg_erro = $clconlancamcompl->erro_msg;
              db_msgbox($msg_erro);
              $erro = true;
              break;
            }
          }

          /// inclui no conlancambol
          $clconlancambol->c77_id       = $k12_id;
          $clconlancambol->c77_autent   = $k12_autent;
          $clconlancambol->c77_databol  = $clconlancam->c70_data;

          $clconlancambol->c77_anousu   = $clconlancam->c70_anousu;
          $clconlancambol->c77_dataproc = $clconlancam->c70_data;
          $clconlancambol->c77_boletim  = $k11_numbol; // não usado para identificar o boletim
          $clconlancambol->c77_instit   = db_getsession("DB_instit");
          $clconlancambol->c77_valor    = $clconlancam->c70_valor;



          $result = $clconlancambol->incluir($clconlancam->c70_codlan);
          if ($clconlancambol->erro_status == '0') {
            $msg_erro = $clconlancambol->erro_msg;
            db_msgbox("05".$msg_erro);
            $erro = true;
            break;
          }

          $clconlancamval->c69_sequen = 0;
          $clconlancamval->c69_anousu = db_getsession("DB_anousu");
          $clconlancamval->c69_codlan = $clconlancam->c70_codlan;
          $clconlancamval->c69_codhist = 9500;
          $clconlancamval->c69_credito = $k12_conta;
          $clconlancamval->c69_debito = $k02_reduz;
          if ($arrecada_boletim == true) {
            $clconlancamval->c69_valor = $valor;
          } else {
            $clconlancamval->c69_valor = $valor * -1;
          }
          $clconlancamval->c69_data = $c70_data_ano."-".$c70_data_mes."-".$c70_data_dia;

          $result = $clconlancamval->incluir($clconlancamval->c69_sequen);
          if ($clconlancamval->erro_status == '0') {
            $msg_erro = $clconlancamval->erro_msg;
            db_msgbox('Erro ao gerar lançamento no conlancamval. (4)('.$msg_erro.')');
            $erro = true;
            break;
          }
        }
        // variavel para indicar se processa lancamentos
        if ($erro == false){
          $processa_lancamentos = true;
        }

      }
    }
  }
}

if ($erro == false) {
  $instit = db_getsession("DB_instit");
  $msg_erro = $clconlancam->erro_msg;

  $clboletim->k11_data = "$data";
  $clboletim->k11_instit = $instit;
  $clboletim->k11_lanca = $arrecada_boletim == true ? 'true' : 'false';
  $clboletim->alterar($data, $instit);
  $erro_msg = $clboletim->erro_msg;

  if ($clboletim->erro_status == '0') {
    db_msgbox($clboletim->erro_msg);
    $erro = true;
  }
}
if ($processa_lancamentos == true) {
  if ($erro == true)
  $msg_erro = "A verificação encontrou inconsistências!";
} else {
	$msg_erro = "Processamento concluído com sucesso!";
}
/// fim da receitaa extra


if ($debug==false){
 // $erro = true;
  db_fim_transacao($erro);
}
?>