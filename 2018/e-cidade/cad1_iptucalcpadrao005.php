<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_utils.php");

$cliptucalcpadrao = new cl_iptucalcpadrao;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<?php

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

if ( isset($exec) && $exec != '' ) {
  $j23_anousu = $exec;
}else{
  $exec = @$j23_anousu ;
}

$db_opcao = 2;
$db_botao = false;

if (isset($alterar)) {

  $iAnoUsu = db_getsession("DB_anousu");
	$sqlerro = false;
  db_inicio_transacao();
  if (!isset($j10_sequencial)) {

    $sqlseq    = "select j10_sequencial from iptucalcpadrao where j10_matric = {$j10_matric} and j10_anousu = {$iAnoUsu}";
    $resultseq = db_query($sqlseq);
    $aSqlseq   = db_utils::fieldsMemory($resultseq, 0);
  }

  $cliptucalcpadrao->j10_sequencial = $aSqlseq->j10_sequencial;
  $cliptucalcpadrao->j10_vlrter     = $j10_vlrter;
  $cliptucalcpadrao->j10_aliq       = $j10_aliq;
  $cliptucalcpadrao->alterar($aSqlseq->j10_sequencial);
  if($cliptucalcpadrao->erro_status==0){
    $sqlerro=true;
  }

  $erro_msg = $cliptucalcpadrao->erro_msg;
  db_fim_transacao($sqlerro);
  $db_opcao = 2;
  $db_botao = true;

}else if(isset($chavepesquisa) or 1==1){

   $db_opcao = 2;
   $db_botao = true;

  if($forma == 1){

    /*
     * só buscar os dados e mostrar para alterar
     *
     */
    $sqlpadrao = " select j10_sequencial,j10_anousu,j10_matric,j10_vlrter,j10_aliq,j10_perccorre,j23_vlrter,j23_anousu
                     from iptucalcpadrao
                          left join iptucalcpadraoorigem on j10_sequencial = j27_iptucalcpadrao
                          left join iptucalc             on j23_anousu     = j27_anousu
                                                        and j27_matric     = j23_matric
                    where j10_matric = $j10_matric
                      and j10_anousu = ".db_getsession("DB_anousu");
    $resultpadrao = db_query($sqlpadrao);
    $linhaspadrao = pg_num_rows($resultpadrao);
    if($linhaspadrao>0){
      db_fieldsmemory($resultpadrao,0);
    }

  }elseif($forma == 2){

     /* verificar se ja tem dados incluidos para esta matricula e ano
     * se tiver     -- exclui os dados e inclui os dados importados
     * se não tiver -- inclui os dados importados
     * mostrar para alterar nas 3 abas
     */
    $sqliptucalcpadrao    = "select j10_vlrter, j10_aliq from iptucalcpadrao where j10_matric = $j10_matric and j10_anousu =".db_getsession("DB_anousu");
    $resultiptucalcpadrao = db_query($sqliptucalcpadrao);
    $linhasiptucalcpadrao = pg_num_rows($resultiptucalcpadrao);
    if($linhasiptucalcpadrao>0){

      echo "<script>
            var retorno = confirm('Ja existem dados cadastrados para esta matricula e ano, deseja substitui-los.');
            if(retorno){
              js_OpenJanelaIframe('top.corpo','db_iframe_inclui','cad1_iptucalcpadraoimporta.php?matric=$j10_matric&exec=$exec&perc=$perc&excluir=true','Pesquisa',false);
            }
            </script>
            ";

    }else{

      echo "<script>
              js_OpenJanelaIframe('top.corpo','db_iframe_inclui','cad1_iptucalcpadraoimporta.php?matric=$j10_matric&exec=$exec&perc=$perc&excluir=false','Pesquisa',false);
            </script>";
    }

  }

}

 $sqlpadrao = " select j10_sequencial, j10_anousu, j10_matric, j10_vlrter, j10_aliq,
                       j10_perccorre,  j23_vlrter, j23_anousu
                  from iptucalcpadrao
                       left join iptucalcpadraoorigem on j10_sequencial = j27_iptucalcpadrao
                       left join iptucalc             on j23_anousu     = j27_anousu
                                                     and j27_matric     = j23_matric
                   where j10_matric = $j10_matric
                     and j10_anousu = ".db_getsession("DB_anousu");

 $resultpadrao = db_query($sqlpadrao);
 $linhaspadrao = pg_num_rows($resultpadrao);
 if($linhaspadrao > 0){
  db_fieldsmemory($resultpadrao,0);
 }
?>
<body class="abas">
  <div class="container">
  	<?php
  	  include("forms/db_frmiptucalcpadrao.php");
  	?>
  </div>
</body>
</html>
<?php
if(isset($alterar)){

  if($sqlerro==true){

    db_msgbox($erro_msg);
    if($cliptucalcpadrao->erro_campo!=""){

      echo "<script> document.form1.".$cliptucalcpadrao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cliptucalcpadrao->erro_campo.".focus();</script>";
    }
  }else{
   db_msgbox($erro_msg);
  }
}

if(isset($chavepesquisa)){

 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.iptucalcpadraoconstr.disabled=false;
         top.corpo.iframe_iptucalcpadraoconstr.location.href='cad1_iptucalcpadraoconstr001.php?j11_iptucalcpadrao='+document.form1.chavepesquisa.value+'&j11_matric=$j10_matric&forma=$forma&exec=$exec';
         parent.document.formaba.iptutaxamatric.disabled=false;
         top.corpo.iframe_iptutaxamatric.location.href='cad1_iptutaxamatric001.php?j09_matric=$j10_matric&forma=$forma&exec=$exec';
     ";
         if(isset($liberaaba)){
           if(($forma==2) or (isset($alt))){
             echo "  parent.mo_camada('iptucalcpadrao');";
           }else{
             echo "  parent.mo_camada('iptucalcpadraoconstr');";
           }

         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>