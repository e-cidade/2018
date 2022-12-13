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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_benstransfcodigo_classe.php"));
require_once(modification("classes/db_benstransf_classe.php"));
require_once(modification("classes/db_benstransfdiv_classe.php"));
require_once(modification("classes/db_benstransforigemdestino_classe.php"));
require_once(modification("classes/db_departdiv_classe.php"));
require_once(modification("classes/db_bensdiv_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
db_postmemory($_SERVER);
db_postmemory($_POST);
$clbenstransfcodigo          = new cl_benstransfcodigo;
$clbenstransf                = new cl_benstransf;
$clbenstransfdiv             = new cl_benstransfdiv;
$cldepartdiv                 = new cl_departdiv;
$oDaoBensTransfOrigemDestino = new cl_benstransforigemdestino;
$clbensdiv                   = new cl_bensdiv;

$db_opcao = 22;
$db_botao = false;
$sqlerro  = false;

if (isset($incluir) || isset($alterar)) {

  try {

    $oBem = new Bem($t95_codbem);
    if ($oBem->getDepartamento() != db_getsession('DB_coddepto')) {
      throw new BusinessException('Não é possível fazer transferência de bens de um departamento diferente do atual.');
    }
  } catch (Exception $oErro) {

    $sqlerro  = true;
    $erro_msg = $oErro->getMessage();
  }
}

if(isset($incluir)){

  db_inicio_transacao();

  if ($sqlerro == false) {

    if(isset($t31_divisao)&&$t31_divisao!=""){

      $clbenstransfdiv->t31_codtran = $t95_codtran;
      $clbenstransfdiv->t31_bem     = $t95_codbem;
      $clbenstransfdiv->t31_divisao = $t31_divisao;
      $clbenstransfdiv->incluir(null);
      if($clbenstransfdiv->erro_status==0){
        $sqlerro=true;
        $erro_msg = $clbenstransfdiv->erro_msg;
      }
    }
  }
  if($sqlerro==false){
    $clbenstransfcodigo->incluir($t95_codtran,$t95_codbem);
    if($clbenstransfcodigo->erro_status==0){
      $sqlerro=true;
    }
    $erro_msg = $clbenstransfcodigo->erro_msg;
  }

  if ($sqlerro == false) {

    /*
     * buscamos a divisão atual do bem
     */
    $iDivisaoOrigem = null;
    $sSqlBensDiv    = $clbensdiv->sql_query_file($t95_codbem);
    $rsBensdiv      = $clbensdiv->sql_record($sSqlBensDiv);
    if ($clbensdiv->numrows > 0) {
      $iDivisaoOrigem = db_utils::fieldsMemory($rsBensdiv, 0)->t33_divisao;
    }
    $oDaoBensTransfOrigemDestino->t34_transferencia       = $t95_codtran;
    $oDaoBensTransfOrigemDestino->t34_bem                 = $t95_codbem;
    $oDaoBensTransfOrigemDestino->t34_divisaoorigem       = $iDivisaoOrigem;
    $oDaoBensTransfOrigemDestino->t34_divisaodestino      = $t31_divisao;
    $oDaoBensTransfOrigemDestino->t34_departamentoorigem  = db_getsession("DB_coddepto");
    $oDaoBensTransfOrigemDestino->t34_departamentodestino = $depto;
    $oDaoBensTransfOrigemDestino->incluir(null);
    if($oDaoBensTransfOrigemDestino->erro_status == 0){
      $sqlerro=true;
    }
    $erro_msg = $oDaoBensTransfOrigemDestino->erro_msg;
  }

  db_fim_transacao($sqlerro);

}else if(isset($alterar)){

  db_inicio_transacao();
  if(isset($t31_divisao)&&$t31_divisao!=""){
    $result_div=$clbenstransfdiv->sql_record($clbenstransfdiv->sql_query_file(null,"*",null,"t31_codtran = $t95_codtran and t31_bem = $t95_codbem"));
    if ($clbenstransfdiv->numrows>0){
      if($sqlerro==false){
        //$clbenstransfdiv->excluir($t31_codigo);
        $clbenstransfdiv->excluir(null, "t31_codtran = {$t95_codtran} and t31_bem = {$t95_codbem}");
        if($clbenstransfdiv->erro_status==0){
          $sqlerro=true;
          $erro_msg = $clbenstransfdiv->erro_msg;
        }
      }
    }
    if($sqlerro==false){
      if(isset($t31_divisao)&&$t31_divisao!=""){
        $clbenstransfdiv->t31_codtran=$t95_codtran;
        $clbenstransfdiv->t31_bem=$t95_codbem;
        $clbenstransfdiv->t31_divisao=$t31_divisao;
        $clbenstransfdiv->incluir(null);
        if($clbenstransfdiv->erro_status==0){
          $sqlerro=true;
          $erro_msg = $clbenstransfdiv->erro_msg;
        }
      }
    }
  }else{
    if($sqlerro==false){
      $clbenstransfdiv->excluir(null,"t31_codtran = $t95_codtran and t31_bem = $t95_codbem");
      if($clbenstransfdiv->erro_status==0){
        $sqlerro=true;
        $erro_msg = $clbenstransfdiv->erro_msg;
      }
    }
  }
  if($sqlerro==false){
    $clbenstransfcodigo->alterar($t95_codtran,$t95_codbem);
    if($clbenstransfcodigo->erro_status==0){
      $sqlerro=true;
    }
    $erro_msg = $clbenstransfcodigo->erro_msg;
  }
  if($sqlerro == false){

    //echo "sadsadsad"; die();

    /*
     * buscamos a divisão atual do bem
    */
    $iDivisaoOrigem = null;
    $sSqlBensDiv    = $clbensdiv->sql_query_file($t95_codbem);
    $rsBensdiv      = $clbensdiv->sql_record($sSqlBensDiv);
    if ($rsBensdiv && pg_num_rows($rsBensdiv) > 0) {
      $iDivisaoOrigem = db_utils::fieldsMemory($rsBensdiv, 0)->t33_divisao;
    }

    /*
     * verificamos o sequencial
     */
    $sSqlBensTransfOrigemDestino = $oDaoBensTransfOrigemDestino->sql_query_file(null,
                                                                                "t34_sequencial",
                                                                                null,
                                                                                "t34_bem = {$t95_codbem} and t34_transferencia = {$t95_codtran}");
    $rsBensTransfOrigemDestino = $oDaoBensTransfOrigemDestino->sql_record($sSqlBensTransfOrigemDestino);
    // echo $sSqlBensTransfOrigemDestino; die();
    if ($oDaoBensTransfOrigemDestino->numrows > 0) {

      $iSequencial = db_utils::fieldsMemory($rsBensTransfOrigemDestino, 0)->t34_sequencial;
      $oDaoBensTransfOrigemDestino->t34_sequencial          = $iSequencial;
      $oDaoBensTransfOrigemDestino->t34_transferencia       = $t95_codtran;
      $oDaoBensTransfOrigemDestino->t34_bem                 = $t95_codbem;
      $oDaoBensTransfOrigemDestino->t34_divisaoorigem       = $iDivisaoOrigem;
      $oDaoBensTransfOrigemDestino->t34_divisaodestino      = $t31_divisao;
      $oDaoBensTransfOrigemDestino->t34_departamentoorigem  = db_getsession("DB_coddepto");
      $oDaoBensTransfOrigemDestino->t34_departamentodestino = $t93_depart;
      $oDaoBensTransfOrigemDestino->alterar($oDaoBensTransfOrigemDestino->t34_sequencial);
      if($oDaoBensTransfOrigemDestino->erro_status == 0){
        $sqlerro=true;
      }
      $erro_msg = $oDaoBensTransfOrigemDestino->erro_msg;

    }

  }


  db_fim_transacao($sqlerro);


}else if(isset($excluir)){


  $sqlerro = false;
  db_inicio_transacao();
  if($sqlerro==false){
    $clbenstransfdiv->excluir(null,"t31_codtran = $t95_codtran and t31_bem = $t95_codbem");
    if($clbenstransfdiv->erro_status==0){
      $sqlerro=true;
      $erro_msg = $clbenstransfdiv->erro_msg;
    }
  }
  if($sqlerro==false){
    $clbenstransfcodigo->excluir($t95_codtran,$t95_codbem);
    if($clbenstransfcodigo->erro_status==0){
      $sqlerro=true;
    }
    $erro_msg = $clbenstransfcodigo->erro_msg;
  }


  if($sqlerro == false && $t31_divisao != ""){



    /*
     * verificamos o sequencial
    */
    $sSqlBensTransfOrigemDestino = $oDaoBensTransfOrigemDestino->sql_query_file(null,
                                                                                "t34_sequencial",
                                                                                null,
                                                                                "t34_bem = {$t95_codbem} and t34_transferencia = {$t95_codtran}");
    $rsBensTransfOrigemDestino = $oDaoBensTransfOrigemDestino->sql_record($sSqlBensTransfOrigemDestino);

    //echo $sSqlBensTransfOrigemDestino; die();
    if ($oDaoBensTransfOrigemDestino->numrows > 0) {

      $iSequencial = db_utils::fieldsMemory($rsBensTransfOrigemDestino, 0)->t34_sequencial;
      $oDaoBensTransfOrigemDestino->excluir($iSequencial);
      if($oDaoBensTransfOrigemDestino->erro_status == 0){
        $sqlerro=true;
      }
      $erro_msg = $oDaoBensTransfOrigemDestino->erro_msg;
    }
  }

  db_fim_transacao($sqlerro);
}else if(isset($opcao)){
  $result = $clbenstransfcodigo->sql_record($clbenstransfcodigo->sql_query_div($t95_codtran,$t95_codbem));
  if($result!=false && $clbenstransfcodigo->numrows>0){
    db_fieldsmemory($result,0);
  }
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<center>
  <?
  include(modification("forms/db_frmbenstransfcodigo.php"));
  ?>
</center>

</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
  db_msgbox($erro_msg);
  if($sqlerro==true){
    echo "<script> document.form1.".$clbenstransfcodigo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clbenstransfcodigo->erro_campo.".focus();</script>";
  }else{
    echo "<script>
              (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_benstransf.location.href='pat1_benstransf005.php?chavepesquisa=".@$t95_codtran."&db_param=$db_param';
            </script>";
  }
}
?>
