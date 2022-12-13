<?php
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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

db_postmemory($_POST);

$clliclicita         = new cl_liclicita;
$clliclicitaproc     = new cl_liclicitaproc;
$clpccflicitapar     = new cl_pccflicitapar;
$clpccflicitanum     = new cl_pccflicitanum;
$cldb_usuarios       = new cl_db_usuarios;
$clliclicitasituacao = new cl_liclicitasituacao;
$clcflicita          = new cl_cflicita;

$db_opcao = 1;
$db_botao = true;

if(isset($incluir)){

  db_inicio_transacao();

  $sqlerro    = false;
  $anousu     = date('Y',db_getsession("DB_datausu"));
  $instit     = db_getsession("DB_instit") ;
  $anousu     = db_getsession("DB_anousu");

  //verifica se as duas modalidades estão configuradas.
  $result_modalidade=$clpccflicitapar->sql_record($clpccflicitapar->sql_query_modalidade(null,"*",null,"l25_codcflicita = $l20_codtipocom and l25_anousu = $anousu and l03_instit = $instit"));
  if ($clpccflicitapar->numrows == 0){
    $erro_msg="Veririfque se está configurado a numeração de licitação por modalidade.";
    $sqlerro = true;
  }

  $result_numgeral=$clpccflicitanum->sql_record($clpccflicitanum->sql_query_file(null,"*",null,"l24_instit=$instit and l24_anousu=$anousu"));
  if ($clpccflicitanum->numrows==0){
    $erro_msg="Veririfque se está configurado a numeração de licitação por edital.";
    $sqlerro = true;
  }

  //numeração por modalidade
  if ($sqlerro == false){

    if ($clpccflicitapar->numrows > 0){
      db_fieldsmemory($result_modalidade,0,2);
      $l20_numero=$l25_numero;
    } else {
      $erro_msg="Configure a numeração de licitação por modalidade.";
      $sqlerro = true;
    }

    if ($sqlerro == false){
      $clpccflicitapar->l25_numero=$l25_numero+1;
      $clpccflicitapar->alterar_where(null,"l25_codigo = $l25_codigo and l25_anousu = $anousu");
    }

    //numeração geral

    if ($clpccflicitanum->numrows>0){
      db_fieldsmemory($result_numgeral,0);
      $l20_edital=$l24_numero;
    } else {
      $erro_msg="Configure a numeração de licitação por edital.";
      $sqlerro = true;
    }


    if ($sqlerro == false){
      $clpccflicitanum->l24_numero=$l24_numero+1;
      $clpccflicitanum->alterar_where(null,"l24_instit=$instit and l24_anousu=$anousu");
    } else {
      $sqlerro = true;
    }


    //verifica se já existe licitação por modadlidade
    $numero=$l20_numero+1;
    $sqlveriflicitamod = $clpccflicitapar->sql_query_mod_licita(null,"l25_numero as xx",null,"l20_instit=$instit and l25_anousu=$anousu and l20_codtipocom=$l20_codtipocom and l20_numero=$numero and l20_anousu=$anousu");
    $result_verif_licitamod=$clpccflicitapar->sql_record( $sqlveriflicitamod );

    if ($clpccflicitapar->numrows>0){
      $erro_msg="Já existe licitação número $l20_numero.Verificar o cadastro por modalidade.";
      $sqlerro = true;
    }

    //verifica se existe licitação por edital
    $edital=$l20_edital+1;
    $result_verif_licitaedital=$clpccflicitanum->sql_record($clpccflicitanum->sql_query_edital(null,"l20_edital as yy",null,"l20_instit=$instit and l25_anousu=$anousu and l20_edital=$edital and l20_anousu=$anousu"));

    if ($clpccflicitanum->numrows>0){
      $erro_msg="Já existe licitação número $l20_edital.Verificar numeração por edital.";
      $sqlerro = true;
    }


    $l20_procadmin = trim($l20_procadmin);
    if ($lprocsis == 'n' && $l20_procadmin === '') {

      $sqlerro  = true;
      $erro_msg = "O campo Processo Administrativo é de preenchimento obrigatório.";
    }

    if ($sqlerro == false){

      $l20_numero=$l20_numero+1;
      $l20_edital=$l20_edital+1;

      $clliclicita->l20_numero      = $l20_numero;
      $clliclicita->l20_edital      = $l20_edital;
      $clliclicita->l20_anousu      =  $anousu;
      $clliclicita->l20_licsituacao = '0';
      $clliclicita->l20_instit      = db_getsession("DB_instit");

      $clliclicita->incluir(null);

      if ($clliclicita->erro_status=="0"){
        $erro_msg = $clliclicita->erro_msg;
        $sqlerro=true;
      }

    }

    if ( !$sqlerro && $lprocsis == 's') {

      $clliclicitaproc->l34_liclicita    = $clliclicita->l20_codigo;
      $clliclicitaproc->l34_protprocesso = $l34_protprocesso;
      $clliclicitaproc->incluir(null);

      if ( $clliclicitaproc->erro_status == 0 ) {
        $erro_msg = $clliclicitaproc->erro_msg;
        $sqlerro  = true;
      }
    }

    if ($sqlerro == false) {

      $l11_sequencial = '';
      $clliclicitasituacao->l11_id_usuario  = DB_getSession("DB_id_usuario");
      $clliclicitasituacao->l11_licsituacao = '0';
      $clliclicitasituacao->l11_liclicita   = $clliclicita->l20_codigo;
      $clliclicitasituacao->l11_obs         = "Licitação em andamento.";
      $clliclicitasituacao->l11_data        = date("Y-m-d",DB_getSession("DB_datausu"));
      $clliclicitasituacao->l11_hora        = DB_hora();
      $clliclicitasituacao->incluir($l11_sequencial);

      $erro_msg = " Licitação {$l03_descr} número {$l20_numero} incluída com sucesso.";

      if ($clliclicitasituacao->erro_status == 0){
        $erro_msg = $clliclicitasituacao->erro_msg;
        $sqlerro = true;
      }

    }

    $codigo   = $clliclicita->l20_codigo;
    $tipojulg = $clliclicita->l20_tipojulg;
    db_fim_transacao($sqlerro);
  }
}
$db_opcao_editavel = $db_opcao;
?>
  <html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
  <?php include modification("forms/db_frmliclicita.php"); ?>
  </body>
  </html>
<?php
if(isset($incluir)){

  if($clliclicita->erro_status=="0"){
    $clliclicita->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clliclicita->erro_campo!=""){
      echo "<script> document.form1.".$clliclicita->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clliclicita->erro_campo.".focus();</script>";
    }
  } else {

    db_msgbox($erro_msg);

    $sFonteItem = 'lic1_liclicitemalt001.php';
    if ($l20_tipo == 2 ){
      $sFonteItem = 'lic1_itensprelicitacaot001.php';
    }

    if ($sqlerro==false){
      echo " <script>
               parent.iframe_liclicita.location.href='lic1_liclicita002.php?chavepesquisa=$codigo';\n
               parent.iframe_liclicitem.location.href='<?=$sFonteItem?>?licitacao=$codigo';\n
               parent.document.formaba.liclicitem.disabled=false;
             </script> ";
    }
  }
}
?>