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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_saltes_classe.php"));
require_once(modification("classes/db_orcreceita_classe.php"));
require_once(modification("classes/db_orcreceitaval_classe.php"));
require_once(modification("classes/db_orcfontes_classe.php"));
require_once(modification("classes/db_orcfontesdes_classe.php"));
require_once(modification("classes/db_conlancam_classe.php"));
require_once(modification("classes/db_conlancamrec_classe.php"));
require_once(modification("classes/db_conlancamval_classe.php"));
require_once(modification("classes/db_conlancamlr_classe.php"));
require_once(modification("classes/db_conlancamdoc_classe.php"));
require_once(modification("classes/db_conplanoreduz_classe.php"));
require_once(modification("classes/db_conlancampag_classe.php"));
require_once(modification("classes/db_conlancamcompl_classe.php"));
require_once(modification("classes/db_conlancamconcarpeculiar_classe.php"));

db_postmemory($HTTP_POST_VARS);

$clsaltes                  = new cl_saltes();
$clorcreceita              = new cl_orcreceita();
$clorcreceitaval           = new cl_orcreceitaval();
$clorcfontes               = new cl_orcfontes();
$clorcfontesdes            = new cl_orcfontesdes();
$clconlancampag            = new cl_conlancampag();
$clconlancamconcarpeculiar = new cl_conlancamconcarpeculiar();

$db_opcao = 1;
$db_botao = false;
$msg_erro = "";

if (isset($c70_data_dia)) {

  db_putsession("cdia","$c70_data_dia");
  db_putsession("cmes","$c70_data_mes");
}

if (isset($lancar) || isset($estornar)) {

  //////////////////////////
  // incluir orcreceitaval
  // incluir lancamentos conlancam
  //                     conlancamrec
  //                     conlancamdoc
  //                     conlancamval
  //                     conlancampag
  $clconlancam      = new cl_conlancam;
  $clconlancamrec   = new cl_conlancamrec;
  $clconlancamdoc   = new cl_conlancamdoc;
  $clconlancamval   = new cl_conlancamval;
  $clconlancamlr    = new cl_conlancamlr;
  $clconlancamcompl = new cl_conlancamcompl;
  $clconplanoreduz  = new cl_conplanoreduz;

  db_inicio_transacao();

  $erro = false;
  $dbrec = array();
  reset($HTTP_POST_VARS);
  $tam = count($HTTP_POST_VARS);
  for($i=0;$i<$tam;$i++){
    $compara = substr(key($HTTP_POST_VARS),0,6);
    if($compara=="db_rec"){
      $dbrec[substr(key($HTTP_POST_VARS),7)] = $HTTP_POST_VARS[key($HTTP_POST_VARS)];
    }
    next($HTTP_POST_VARS);
  }

  if(count($dbrec)==0){
    $dbrec[$o70_codrec] = $c70_valor;
  }

  reset($dbrec);

  for ($i=0;$i<sizeof($dbrec);$i++){

    $codrec = key($dbrec);
    $valor = $dbrec[key($dbrec)];
    if (isset($lancar) && trim($lancar) == ""){
      $valor *= -1;
    }

    $clorcreceitaval->o71_anousu = db_getsession("DB_anousu");
    $clorcreceitaval->o71_codrec = $codrec;
    $clorcreceitaval->o71_coddoc = (isset($lancar)?100:101);
    $clorcreceitaval->o71_mes    = $c70_data_mes;
    $clorcreceitaval->o71_valor  = "$valor";

    $result = $clorcreceitaval->sql_record($clorcreceitaval->sql_query_file($clorcreceitaval->o71_anousu,
                                                                            $clorcreceitaval->o71_codrec,
                                                                            $clorcreceitaval->o71_coddoc,
                                                                            $clorcreceitaval->o71_mes));
    if($clorcreceitaval->numrows==0){
      $clorcreceitaval->incluir($clorcreceitaval->o71_anousu,
                                $clorcreceitaval->o71_codrec,
                                $clorcreceitaval->o71_coddoc,
                                $clorcreceitaval->o71_mes);
      if($clorcreceitaval->erro_status=='0'){

        $msg_erro = $clorcreceitaval->erro_msg;
        $erro = true;
        break;
      }
    }else{

      db_fieldsmemory($result,0);
      $clorcreceitaval->o71_valor = $o71_valor +  (isset($lancar)?$valor:($valor*-1));
      $clorcreceitaval->alterar($clorcreceitaval->o71_anousu,
                                $clorcreceitaval->o71_codrec,
                                $clorcreceitaval->o71_coddoc,
                                $clorcreceitaval->o71_mes);
      if($clorcreceitaval->erro_status=='0'){

        $msg_erro = $clorcreceitaval->erro_msg;
        $erro = true;
        break;
      }
    }

    // inclusao de lançamentos
    $clconlancam->c70_codlan = 0;
    $clconlancam->c70_anousu = db_getsession("DB_anousu");
    $clconlancam->c70_data   = "$c70_data_ano-$c70_data_mes-$c70_data_dia";
    $clconlancam->c70_valor  = $valor;
    $result = $clconlancam->incluir($clconlancam->c70_codlan);
    if ($clconlancam->erro_status=='0') {

      $msg_erro = $clconlancam->erro_msg;
      $erro     = true;
      db_msgbox($msg_erro);
      break;
    }
    $lEvento = EventoContabil::vincularLancamentoNaInstituicao($clconlancam->c70_codlan , db_getsession("DB_instit"));
    $lEvento = EventoContabil::vincularOrdem($clconlancam->c70_codlan);

    if (!$erro) {

      if (isset($c08_concarpeculiar) && !empty($c08_concarpeculiar)) {

        $clconlancamconcarpeculiar->c08_codlan         = $clconlancam->c70_codlan;
        $clconlancamconcarpeculiar->c08_concarpeculiar = $c08_concarpeculiar;
        $clconlancamconcarpeculiar->incluir(null);
        if ($clconlancamconcarpeculiar->erro_status == 0) {

          $msg_erro = $clconlancamconcarpeculiar->erro_msg;
          $erro     = true;

          db_msgbox($msg_erro);
          break;
        }
      }
    }

    $clconlancamrec->c74_codlan = $clconlancam->c70_codlan;
    $clconlancamrec->c74_anousu = db_getsession("DB_anousu");
    $clconlancamrec->c74_data   = "$c70_data_ano-$c70_data_mes-$c70_data_dia";
    $clconlancamrec->c74_codrec = $codrec;
    $result = $clconlancamrec->incluir($clconlancam->c70_codlan);
    if($clconlancamrec->erro_status=='0'){
      $msg_erro = $clconlancamrec->erro_msg;
      db_msgbox("$msg_erro, Verificar as transações contábeis !");
      $erro = true;
      break;
    }
    //  documento do lançamento
    $clconlancamdoc->c71_codlan = $clconlancam->c70_codlan;
    $clconlancamdoc->c71_coddoc = (isset($lancar)?100:101);
    $clconlancamdoc->c71_data   = "$c70_data_ano-$c70_data_mes-$c70_data_dia";
    $result = $clconlancamdoc->incluir($clconlancam->c70_codlan);
    if($clconlancamdoc->erro_status=='0'){
      $msg_erro = $clconlancamdoc->erro_msg;
      db_msgbox($msg_erro);
      $erro = true;
      break;
    }

    //  historico da arrecadacao de receita
    $clconlancamcompl->c72_codlan  = $clconlancam->c70_codlan;
    $clconlancamcompl->c72_complem = $c72_complem;
    $result = $clconlancamcompl->incluir($clconlancam->c70_codlan);
    if($clconlancamcompl->erro_status=='0'){
      $msg_erro = $clconlancamcompl->erro_msg;
      db_msgbox($msg_erro);
      $erro = true;
      break;
    }


    /* lancam conlancampag */
    /* grava no conlancampag só os lançamentos que possuem conta banco  */
    $clconlancampag->c82_anousu= db_getsession("DB_anousu");
    $clconlancampag->c82_reduz = $k13_conta;
    $clconlancampag->incluir($clconlancam->c70_codlan);
    if($clconlancampag->erro_status=='0'){
      $msg_erro = $clconlancampag->erro_msg;
      db_msgbox($msg_erro." Erro ao gravar conlancampag! ");
      $erro = true;
      break;
    }


    //  busca codigo da fontes da receita
    $result = $clorcreceita->sql_record($clorcreceita->sql_query_file(db_getsession("DB_anousu"),$codrec,'o70_codfon'));
    if($clorcreceita->numrows==0){
      $msg_erro = 'Receita não cadastrada.('.db_getsessio("DB_anousu").','.$codrec.') no orcreceita';
      db_msgbox($msg_erro);
      $erro = true;
      break;
    }
    db_fieldsmemory($result,0);
    $sSqlReduzido = $clconplanoreduz->sql_query(null,null,
                                                "c61_reduz,c60_descr",
                                                "c61_reduz","c61_anousu = ".db_getsession("DB_anousu")."
                                                  and c61_codcon = {$o70_codfon}
                                                  and c61_instit = ".db_getsession("DB_instit")
    );
    if (USE_PCASP) {

      $oDaoConplanoConplanoOrcamento = db_utils::getDao("conplanoconplanoorcamento");

      $sSqlReduzido = $oDaoConplanoConplanoOrcamento->sql_query_pcasp_analitica(null,
                                                                                'conplanoreduz.c61_reduz ',
                                                                                'conplanoreduz.c61_reduz',
                                                                                "conplanoorcamentoanalitica.c61_codcon = {$o70_codfon}
                                                    and conplanoorcamentoanalitica.c61_anousu=".db_getsession("DB_anousu")."
                                                    and conplanoorcamentoanalitica.c61_instit=".db_getsession("DB_instit")
      );
    }
    // busca o codcon da conta no plano de contas para pegar o reduzido
    $result = $clconplanoreduz->sql_record($sSqlReduzido);
    db_criatabela($result);
    if($clconplanoreduz->numrows==0){
      $msg_erro = 'Conta da Receita não cadastrada.('.db_getsession("DB_anousu").','.$codrec.') no conplanoreduz.';
      db_msgbox($msg_erro);
      $erro = true;
      break;
    }
    db_fieldsmemory($result,0);
    $iCodigoReduzido = db_utils::fieldsMemory($result, 0)->c61_reduz;

    $cltranslan = new cl_translan ;
    $receita_deducao = false;
    if(isset($lancar)){

      $sql = "select c60_estrut
           from conplano
           where c60_anousu = ".db_getsession("DB_anousu")." and
                 c60_codcon = $o70_codfon and
                 fc_conplano_grupo(".db_getsession("DB_anousu").",substr(c60_estrut,1,2)||'%',9000) is true";

      $resultded = db_query(analiseQueryPlanoOrcamento($sql));
      if(pg_numrows($resultded)>0){
        $receita_deducao = true;
        $cltranslan->db_trans_estorno_receita($k13_conta,$iCodigoReduzido,db_getsession("DB_anousu"));
      }else{
        $cltranslan->db_trans_arrecada_receita($k13_conta,$iCodigoReduzido,db_getsession("DB_anousu"));
      }

    }else{

      $sql = "select c60_estrut
           from conplano
           where c60_anousu = ".db_getsession("DB_anousu")." and
                 c60_codcon = $o70_codfon and
                 fc_conplano_grupo(".db_getsession("DB_anousu").",substr(c60_estrut,1,2)||'%',9000) is true";

      $resultded = db_query(analiseQueryPlanoOrcamento($sql));
      if(pg_numrows($resultded)>0){
        $receita_deducao = true;
        $cltranslan->db_trans_arrecada_receita($k13_conta,$iCodigoReduzido,db_getsession("DB_anousu"));
      }else{
        $cltranslan->db_trans_estorno_receita($k13_conta,$iCodigoReduzido,db_getsession("DB_anousu"));
      }
    }

    // verifica se a receita ta cadastrada nas transações
    $conta_cadastrada=false;
    if(isset($lancar)){
      for ($h=0;$h < sizeof($cltranslan->arr_credito);$h++){

        if($receita_deducao == true){
          if ($cltranslan->arr_debito[$h] ==$iCodigoReduzido )
            $conta_cadastrada=true;
        }else{
          if ($cltranslan->arr_credito[$h] ==$iCodigoReduzido )
            $conta_cadastrada=true;
        }

      }
    }else{ // estorno
      for ($h=0;$h < sizeof($cltranslan->arr_debito);$h++){
        if($receita_deducao == true){
          if ($cltranslan->arr_credito[$h] ==$iCodigoReduzido )
            $conta_cadastrada=true;
        }else{
          if ($cltranslan->arr_debito[$h] ==$iCodigoReduzido )
            $conta_cadastrada=true;
        }
      }
    }

    if ($conta_cadastrada == false){
      db_msgbox("Conta $iCodigoReduzido $c60_descr não cadastrada nas transações!");
      $erro = true;
      break;
    }

    for($l=0;$l<sizeof($cltranslan->arr_credito);$l++){

      $clconlancamval->c69_sequen  = 0;
      $clconlancamval->c69_anousu  = db_getsession("DB_anousu");
      $clconlancamval->c69_codlan  = $clconlancam->c70_codlan;
      $clconlancamval->c69_codhist = $cltranslan->arr_histori[$l];
      $clconlancamval->c69_credito = $cltranslan->arr_credito[$l];
      $clconlancamval->c69_debito  = $cltranslan->arr_debito[$l];
      $clconlancamval->c69_valor   = $valor;
      $clconlancamval->c69_data    = "$c70_data_ano-$c70_data_mes-$c70_data_dia";
      $result = $clconlancamval->incluir($clconlancamval->c69_sequen);
      if($clconlancamval->erro_status=='0'){
        $msg_erro = $clconlancamval->erro_msg;
        db_msgbox($msg_erro);
        $erro = true;
        break;
      }

      $clconlancamlr->c81_sequen  = $clconlancamval->c69_sequen;
      $clconlancamlr->c81_seqtranslr = $cltranslan->arr_seqtranslr[$l];
      $result = $clconlancamlr->incluir($clconlancamlr->c81_sequen,$clconlancamlr->c81_seqtranslr);
      if($clconlancamlr->erro_status=='0'){
        $msg_erro = $clconlancamlr->erro_msg;
        db_msgbox($msg_erro);
        $erro = true;
        break;
      }

    }

    next($dbrec);
  }

  if ($erro==false) {

    $msg_erro = $clconlancam->erro_msg;
    db_msgbox($msg_erro);
  }

  db_fim_transacao($erro);
}

?>
  <html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load("scripts.js, strings.js, prototype.js");
    db_app::load("estilos.css, grid.style.css");
    ?>
    <style type="text/css">
      .box-table-detalhamento tr {
        padding:1px;
        border-right:1px inset black;
        border-bottom:1px inset black;
        border-bottom:1px outset white;
        border-right:1px outset white;
        background-color:#FFFFFF;
        cursor: default;
        empty-cells: show;
      }

      #k13_descr, #o57_descr, #c58_descr {
        width: 82%;
      }

      #c72_complem {
        width: 100%;
      }
    </style>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
  <table width="690" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
      <td height="40px">&nbsp;</td>
    </tr>
    <tr>
      <td valign="top" bgcolor="#CCCCCC">
        <center>
          <?
          include(modification("forms/db_frmreceitaarre.php"));
          ?>
        </center>
      </td>
    </tr>
  </table>
  <?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  </body>
  </html>
<?
if($msg_erro!=''){

  db_msgbox($msg_erro);
  db_redireciona("con4_receitaarre001.php");
}
?>