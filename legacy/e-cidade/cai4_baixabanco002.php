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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<?
if(isset($pesquisar)){

  if($db_opcao==1){

    echo "<script>
        function js_mostradados(codret){
      disbanco.jan.location.href='cai4_baixabanco003.php?codret='+codret;
      disbanco.mostraMsg();
      disbanco.show();
      disbanco.focus();
    }
        </script>";

  }else if($db_opcao==2){

    echo "<script>
        function js_verificar(codret){
      disbanco.jan.location.href='cai4_baixabanco004.php?codret='+codret;
      disbanco.mostraMsg();
      disbanco.show();
      disbanco.focus();
    }
        </script>";

  }else if($db_opcao==3){

    echo "<script>
        function js_classifica(codcla){
      disbanco.jan.location.href='cai4_baixabanco007.php?codcla='+codcla;
      disbanco.mostraMsg();
      disbanco.show();
      disbanco.focus();
    }
        </script>";

  }else if($db_opcao==5){

    echo "<script>
        function js_inclusao(banco,agencia,conta){
      disbanco.jan.location.href='cai4_baixabanco010.php?codbco='+banco+'&codage='+agencia+'&conta='+conta;
      disbanco.mostraMsg();
      disbanco.show();
      disbanco.focus();
    }
        </script>";

  }else{

    echo "<script>
         function js_geraclas(codret){
        if(confirm('Confirma Classificação de Receita?')==true)

           location.href='cai4_baixabanco006.php?codret='+codret+'&k15_codbco=$k15_codbco&k15_codage=$k15_codage';
    }
        </script>";
  }
}
?>
</head>
<body class="body-default" onLoad="a=1">

<table border="0" cellspacing="0" cellpadding="0" align="center" style="padding-top: 20px;">
  <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
    <center>
  <?
  if (isset($pesquisar)) {

    $instit      = db_getsession("DB_instit");
    $whereinstit = " and disarq.instit = $instit ";

    $disbanco = new janela("disbanco","");
    $disbanco->iniciarVisivel = false;
    $disbanco->largura = "700";
    $disbanco->altura = "400";
    $disbanco->mostrar();
    $xwhere = "";
    $sAnd = "";

    if (@$k15_codbco != "") {
       $xwhere .= " disarq.k15_codbco = $k15_codbco";
       $sAnd = " and ";
    }

    if (@$k15_codage != "") {
       $xwhere .= $sAnd." (disarq.k15_codage = '$k15_codage' or disarq.k15_codage = '$k15_codage')";
       $sAnd = " and ";
    }

    if( @$datapes_dia != "") {

       if ($k15_codbco != "" || $k15_codage != "") {
        $xwhere .= $sAnd." dtarquivo = '$datapes_ano-$datapes_mes-$datapes_dia'";
       }
    }

        //data para pequisa do arquivo
      if (isset($datapes) && $datapes != ''){
        $dtarquivo =  $datapes_ano.'-'.$datapes_mes.'-'.$datapes_dia;
        $xwhere .= $sAnd." disarq.dtarquivo = '$dtarquivo' ";
      }


    if ($db_opcao == 1) {

       $sql = "select disarq.codret,arqret,disarq.k15_codbco,disarq.k15_codage,dtarquivo,k00_conta,substr(k13_descr,1,20) as k13_descr,sum(vlrtot) as vlrtot
                 from disarq
                      inner join disbanco    on disbanco.codret = disarq.codret
                      left outer join saltes on k13_conta       = k00_conta
                where disbanco.classi = false
                ".(empty($xwhere)?"":" and ".$xwhere)." $whereinstit
                group by disarq.codret,arqret,disarq.k15_codbco,disarq.k15_codage,dtarquivo,k00_conta,substr(k13_descr,1,20) order by disarq.codret desc";
       $varrep = array("db_opcao"=>"1","pesquisar"=>"pesquisar");
       db_lovrot($sql,15,"()","","js_mostradados|0","","NoMe",$varrep);

    } else if ($db_opcao == 3) {

       // Verifica classificacoes executas para um arquivo
       $sql = "select disarq.codret,discla.codcla,arqret,disarq.k15_codbco,disarq.k15_codage,dtarquivo ,k00_conta,substr(k13_descr,1,20) as k13_descr,sum(vlrrec) as vlrrec,discla.dtaute
                 from disarq
                      left outer join saltes on k13_conta     = k00_conta
                      inner join discla      on discla.codret = disarq.codret
                      left outer join disrec on disrec.codcla = discla.codcla
           ".(empty($xwhere)?"":"where ".$xwhere). " ".
             (empty($xwhere)?"where disarq.instit = {$instit}":"and disarq.instit={$instit}")
           . " group by disarq.codret,discla.codcla,arqret,disarq.k15_codbco,disarq.k15_codage,dtarquivo ,k00_conta,substr(k13_descr,1,20),discla.dtaute order by disarq.codret desc";
         $varrep = array("db_opcao"=>"3","pesquisar"=>"pesquisar");

         db_lovrot($sql,15,"()","","js_classifica|1","","NoMe",$varrep);

     }else if ($db_opcao == 5) {

       // Inclusão manual de movimentação
         $sql = "select disarq.k15_codbco,
                        disarq.k15_codage,
                        disarq.k15_conta,
                        z01_nome
                   from cadban as disarq
                        inner join cgm on disarq.k15_numcgm = z01_numcgm
                  ".(empty($xwhere)?"":"where ".$xwhere)." ".
                    (empty($xwhere)?" where ":" and ".$xwhere).
                    (empty($xwhere)?"  k15_instit = {$instit} ":" and k15_instit = {$instit} ");

         $varrep = array("db_opcao"=>"3","pesquisar"=>"pesquisar");
         db_lovrot($sql,15,"()","","js_inclusao|0|1|2","","NoMe");

     } else {

      // Verifica classificacoes executadas para um arquivo
      $sql = "select disarq.codret,disarq.arqret,disarq.k15_codbco,disarq.k15_codage,disarq.dtarquivo,k00_conta,substr(k13_descr,1,20) as k13_descr,sum(vlrtot) as vlrtot
                from disarq
                     inner join disbanco    on disbanco.codret = disarq.codret
                     left outer join saltes on k13_conta       = k00_conta
               where disbanco.classi = false ".(empty($xwhere)?"":" and ".$xwhere)." ".$whereinstit ."
               group by disarq.codret,disarq.arqret,disarq.k15_codbco,disarq.k15_codage,disarq.dtarquivo,k00_conta,substr(k13_descr,1,20) order by disarq.codret desc";

         if ($db_opcao == 2) {

           $varrep = array("db_opcao"=>"2","pesquisar"=>"pesquisar");
           db_lovrot($sql,15,"()","","js_verificar|0","","NoMe",$varrep);
         } else {

            $varrep = array("db_opcao"=>"0","pesquisar"=>"pesquisar");
            db_lovrot($sql,15,"()","","js_geraclas|0","","NoMe",$varrep);
         }
     }

} else {
  include("forms/db_caiarq004.php");
}
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
<script type="text/javascript">
function js_getBanco(){

  var iBanco = document.getElementById('k15_codbco').value;
  alert(iBanco);
}

</script>