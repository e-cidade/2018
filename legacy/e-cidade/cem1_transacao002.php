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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_sepultamentos_classe.php"));
include(modification("classes/db_sepulturas_classe.php"));
include(modification("classes/db_sepulta_classe.php"));
include(modification("classes/db_lotecemit_classe.php"));
include(modification("classes/db_ossoario_classe.php"));
include(modification("classes/db_restosgavetas_classe.php"));
include(modification("classes/db_gavetas_classe.php"));
include(modification("classes/db_retiradas_classe.php"));
include(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clsepultamentos = new cl_sepultamentos;
$cllotecemit = new cl_lotecemit;
$clsepulturas = new cl_sepulturas;
$clsepulta = new cl_sepulta;
$clossoario = new cl_ossoario;
$clrestosgavetas = new cl_restosgavetas;
$clgavetas = new cl_gavetas;
$clretiradas = new cl_retiradas;

$db_opcao = 1;
$db_botao = true;

$lErro = false;


//incluir
if(isset($incluir)){
 db_inicio_transacao();
  //Faz a troca

  if( $tipoant == "sepulta" ){
      $clsepulta->excluir( $codigoant );
  }elseif( $tipoant == "ossoariogeral" ){
      $clossoario->excluir( $codigoant );
  }elseif( $tipoant == "ossoariopart" || $tipoant == "jazigo" ){

      if ($local == 3 or $local == 4) {

        $result_gavetas = $clgavetas->sql_record($clgavetas->sql_query(null,"cm26_i_codigo",null,"cm27_i_restogaveta = $codigoant"));

        if ($clgavetas->numrows > 0) {

          for ($gavetas=0; $gavetas < $clgavetas->numrows; $gavetas++) {
            db_fieldsmemory($result_gavetas, $gavetas);
            $clgavetas->excluir(null, "cm27_i_restogaveta = $cm26_i_codigo");
          }

        }

      }
      $clrestosgavetas->excluir($codigoant);

  }

  if($local == 1){

    //sepulta
    //verifica se há cadastros de sepultamentos para a sepultura;

    $result_sepulta = $clsepulta->sql_record($clsepulta->sql_query(null,"*",null,"cm05_i_codigo = $cm24_i_sepultura "));
    if($clsepulta->numrows > 0){
    ?>
		<script>
		  if(confirm("Aviso!\n\n Já existe um Sepultamento cadastrado para a sepultura!\nConfirma o cadastro?")){
		    <?php

          $sUpdateLote  = $cllotecemit->sql_query_atualiza_situacao($cm23_i_codigo, 'O');
          $rsUpdateLote = db_query($sUpdateLote);

          if ( empty($rsUpdateLote) ) {

            echo "<script>alert('Erro ao alterar situação da sepultura atual para ocupada.');</script>";
            $lErro = true;
          }

    		  $clsepulta->incluir(null);

	      ?>
		  }
		</script>
		<?php
		}else{

         $cllotecemit->cm23_i_codigo = $cm23_i_codigo;
         $cllotecemit->cm23_c_situacao = 'O';
         $cllotecemit->alterar( $cm23_i_codigo );

         $clsepulta->incluir(null);
	  }
  }elseif($local == 2){
        //ossoario geral
        $clossoario->cm06_d_entrada = date("Y-m-d",db_getsession("DB_datausu"));
        $clossoario->incluir(null);

  }elseif($local == 3){

        //ossoario particular / restos
        $clrestosgavetas->incluir(null);
        $cllotecemit->cm23_i_codigo = $cm23_i_codigo;
        $cllotecemit->cm23_c_situacao = 'O';
        $cllotecemit->alterar( $cm23_i_codigo );

  }elseif($local == 4){
        //jazigo

        $cllotecemit->cm23_i_codigo = $cm23_i_codigo;
        $cllotecemit->cm23_c_situacao = 'O';
        $cllotecemit->alterar( $cm23_i_codigo );

        $clrestosgavetas->cm26_i_codigo = "null";
        $clrestosgavetas->incluir(null);

        //Gavetas
        $clgavetas->cm27_i_restogaveta = $clrestosgavetas->cm26_i_codigo;
        $clgavetas->incluir(null);

  }elseif($local == 5){
        $clretiradas->incluir(null);

  }

  if(isset($lotecemit) && !empty($lotecemit)){

    $sUpdateLote  = $cllotecemit->sql_query_atualiza_situacao($lotecemit, 'D');
    $rsUpdateLote = db_query($sUpdateLote);

    if ( empty($rsUpdateLote) ) {

      echo "<script>alert('Erro ao alterar situação da sepultura antiga para disponível.');</script>";
      $lErro = true;
    }
  }

  db_fim_transacao($lErro);
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

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>

    <form name="form1" method="post">

     <table>

       <input name="tipoant" type="hidden">
       <input name="codigoant" type="hidden">
       <input name="lotecemit" type="hidden" value="<?=@$lotecemit?>">


       <?db_input('sepultamento',10,@$sepultamento,true,'hidden',3)?>
       <?//db_input('nome',40,$nome,true,'text',3)?>
      <tr>
       <td>Localização</td>
       <td>

        <?php
	          $arrayValores = array( "0"=>"Selecione",
																	 " " => '------------------',
	                                 "1"=>"Sepultura",
	                                 "2"=>"Ossário Geral",
	                                 "3"=>"Ossário Particular",
	                                 "4"=>"Jazigo",
																	 "  " => '------------------',
																	 "5" => 'Retirada'
);
	          db_select("local",$arrayValores,true,2,"onchange='submit()'");
         	?>
       </td>
      </tr>

      <script>
           document.form1.tipoant.value = (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a3.document.form1.tipoant.value;
           document.form1.codigoant.value = (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a3.document.form1.codigo.value;
      </script>

     </table>
     <table>
      <tr>
       <td>
        <?
         $result = $clsepultamentos->sql_record($clsepultamentos->sql_query(@$sepultamento,"cm01_i_cemiterio, cgm.z01_nome as nome_sepultamento"));
         db_fieldsmemory($result,0);
         if(isset($local)){
          if($local == 1){
           $cm24_i_sepultamento = $sepultamento;
           include(modification("forms/db_frmsepulta.php"));
          }elseif($local == 2){
           $cm06_i_sepultamento = $sepultamento;
           include(modification("forms/db_frmossoario.php"));
          }elseif($local == 3){
           $cm26_i_sepultamento = $sepultamento;
           $tipo='O';
           include(modification("forms/db_frmrestosgavetas.php"));
          }elseif($local == 4){
           $cm26_i_sepultamento = $sepultamento;
           $tipo='J';
           include(modification("forms/db_frmrestosgavetas.php"));
          }elseif($local == 5){
           $cm08_i_sepultamento = $sepultamento;
           $tipo='R';
           include(modification('forms/db_frmretiradas.php'));
          }
         }
        ?>
       </td>
      </tr>
     </table>
     </form>
    </center>
        </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
 if($local == 1){
  db_msgbox($clsepulta->erro_msg);
  if($clsepulta->erro_status != "0"){ $OK = 1; }
 }elseif($local == 2){
  db_msgbox($clossoario->erro_msg);
  if($clossoario->erro_status != "0"){ $OK = 1; }
 }elseif($local == 3){
  db_msgbox($clrestosgavetas->erro_msg);
  if($clrestosgavetas->erro_status != "0"){ $OK = 1; }
 }elseif($local == 4){
  db_msgbox($clgavetas->erro_msg);
  if($clgavetas->erro_status != "0"){ $OK = 1; }
 }elseif($local == 5){
  db_msgbox($clretiradas->erro_msg);
  if($clretiradas->erro_status != "0"){ $OK = 1; }
 }



 //se não deu erro, volta à página inicial do cadastro
 if($OK == 1){
  echo "<script>";
  echo " parent.document.formaba.a2.disabled=true; ";
  echo " parent.document.formaba.a3.disabled=true; ";
  echo " (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a1.location.href='cem3_sepultamentos001.php';";
  echo " parent.mo_camada('a1'); ";
  echo "</script>";
 }
}
?>