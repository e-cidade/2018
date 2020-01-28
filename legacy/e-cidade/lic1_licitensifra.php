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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_pcproc_classe.php");
require_once("classes/db_pcprocitem_classe.php");
require_once("classes/db_liclicitem_classe.php");
require_once("classes/db_pcorcamitemlic_classe.php");
require_once("classes/db_pcparam_classe.php");
require_once("classes/db_liclicitemlote_classe.php");
require_once("classes/db_pcorcamitem_classe.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$cliframe_seleciona = new cl_iframe_seleciona;
$clpcproc           = new cl_pcproc;
$clpcprocitem       = new cl_pcprocitem;
$clliclicitem       = new cl_liclicitem;
$clpcorcamitemlic   = new cl_pcorcamitemlic;
$clpcparam          = new cl_pcparam;
$clliclicitemlote   = new cl_liclicitemlote;
$clpcorcamitem      = new cl_pcorcamitem;

$db_opcao=1;
$db_botao=true;

if (!empty($chaves) && isset($chaves)){
  $result_itens=$clpcprocitem->sql_record($clpcprocitem->sql_query_file(null,"*",null,"pc81_codproc=$codprocant"));

  if ($clpcprocitem->numrows>0){
    $vet_pci  = array(array("pci"));
    $cont_pci = 0;
    for($w=0;$w<$clpcprocitem->numrows;$w++){
  	  db_fieldsmemory($result_itens,$w);

      $vet_pci["pci"][$cont_pci] = $pc81_codprocitem;
      $cont_pci++;
    }

    //echo "Aqui ".$cods."<br>";


    if (isset($_SESSION['cods'])){

      $cont_cod = count($_SESSION['cods']);
      $new_cods = "";
      $vir      = "";
      for($x = 0; $x < $cont_pci; $x++){
        for($xx = 0; $xx < $cont_cod; $xx++){
          if ($_SESSION['cods'][$xx] != $vet_pci["pci"][$x]){
            $new_cods .= $vir.$vet_pci["pci"][$x];
            $vir       = ",";
            break;
          }
        }
      }

      $cods = $new_cods;
    }
  }

  if (trim($cods) == ""){
    $info = split('#',$chaves);
    $vir  = "";
    for($xx = 0; $xx < count($info); $xx++){
      if (trim($info[$xx]) != ""){
        $cods .= $vir.$info[$xx];
        $vir   = ",";
      }
    }
  }

  if (trim($cods) != ""){
  echo "<script>
 		if (parent.document.form1.cods.value!=''){
 		  parent.document.form1.cods.value=$cods;
 		}
   	    </script>";
  }

  if (isset($incluir)&&trim($incluir)!="") {

  	$sqlerro=false;
  	db_inicio_transacao();

  	if ($sqlerro==false) {

      $res_lote     = $clliclicitemlote->sql_record($clliclicitemlote->sql_query_licitacao(null,"l21_codpcprocitem",null,"l21_codliclicita=$licitacao"));
      $numrows_lote = $clliclicitemlote->numrows;

      if ($numrows_lote > 0){
           $itens_incluidos = "";
           $separador       = "";
           for($x = 0; $x < $numrows_lote; $x++){
                db_fieldsmemory($res_lote,$x);
                $itens_incluidos .= $separador.$l21_codpcprocitem;
                $separador        = ", ";
           }

           if (strlen(trim($itens_incluidos)) > 0){
                $arr_itens = split(",",$itens_incluidos);
           }
      }

      $dbwhere = " ";
      if (strlen(trim(@$itens_incluidos)) > 0){
           $dbwhere = " and l21_codpcprocitem not in ($itens_incluidos)";
      }

  		$clliclicitem->excluir(null,"l21_codliclicita=$licitacao $dbwhere");
  		if ($clliclicitem->erro_status==0){
  	  		$sqlerro=true;
  	  		$erro_msg = $clliclicitem->erro_msg;
 		  }
  	}


    //echo "FIM ".$cods; exit;
    if ($sqlerro == false) {
  	  $dados = split('#',$chaves);
      $sql_ult_ordem  = "select l21_ordem ";
      $sql_ult_ordem .= "from liclicitem ";
      $sql_ult_ordem .= "where l21_codliclicita=$licitacao ";
      $sql_ult_ordem .= "order by l21_codigo desc limit 1";

    //echo $sql_ult_ordem; exit;

      $res_ult_ordem  = @db_query($sql_ult_ordem);

      if (pg_numrows($res_ult_ordem) > 0){
        $seq = pg_result($res_ult_ordem,0,"l21_ordem");
        $seq++;
      } else {
        $seq = 1;
      }

   // print_r($dados); exit;

    	for($w=0;$w<count($dados);$w++){
    	  if (trim($dados[$w])!=""){
    	  	if ($sqlerro==false){
            $achou = false;
            for($x = 0; $x < count(@$arr_itens); $x++){
                 if (trim($arr_itens[$x]) == trim($dados[$w])){
                   $achou = true;
                   break;
                 }
            }

            if ($achou == false){
   	  	      $clliclicitem->l21_codliclicita  = $licitacao;
  	          $clliclicitem->l21_codpcprocitem = $dados[$w];
              $clliclicitem->l21_situacao      = "0";
              $clliclicitem->l21_ordem         = $seq;
  	          $clliclicitem->incluir(null);
   	          if ($clliclicitem->erro_status==0){
    	          $erro_msg = $clliclicitem->erro_msg;
 	              $sqlerro=true;
 		            break;
  	  	      }

              $seq++;
            }
  	  	  }

          if ($sqlerro == false) {
            if ($achou == false) {

              $coditem = $clliclicitem->l21_codigo;

              /**
               * Vincula os itens ao lote
               **/
              $res_liclicitem = $clliclicitem->sql_record($clliclicitem->sql_query_sol($coditem,"pc11_codigo, pc68_nome"));
              if ($clliclicitem->numrows > 0){
                db_fieldsmemory($res_liclicitem,0);
              }

              $clliclicitemlote->l04_liclicitem = $coditem;

              /**
               * Tipo de julgamento por item
               */
              if ($tipojulg == 1) {
                $clliclicitemlote->l04_descricao = "LOTE_AUTOITEM_".$pc11_codigo;
              }

              /**
               * Tipo de julgamento Global
               */
              if ($tipojulg == 2){
                $clliclicitemlote->l04_descricao = "GLOBAL";
              }

              /**
               * Tipo de julgamento por Lote
               * pega o lote do processo de compras e ja vem sugerido
               */
              if ($tipojulg == 3) {
                $clliclicitemlote->l04_descricao = $pc68_nome;
              }

              if (!empty($clliclicitemlote->l04_descricao)) {

                $clliclicitemlote->incluir(null);

                if ($clliclicitemlote->erro_status == 0){
                  $erro_msg = $clliclicitemlote->erro_msg;
                  $sqlerro  = true;
                  break;
                }
              }

            }
          }
  	    }
  	  }
    }
   // $sqlerro  = true;
  	db_fim_transacao(false);

    if ($sqlerro==false){
      $res_pcorcam = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmaterlic(null,"pc22_codorc",null,"l20_codigo = $licitacao limit 1"));
      if ($clpcorcamitem->numrows > 0){   // Tem orçamento para esta Licitacao
        db_inicio_transacao();

        db_fieldsmemory($res_pcorcam,0);

   	    for($x = 0; $x < count($dados); $x++){
    	    if (trim($dados[$x])!=""){
    		    $clpcorcamitemlic->sql_record($clpcorcamitemlic->sql_query(null,"*",null,"pc81_codprocitem = ".$dados[$x]));

            if ($clpcorcamitemlic->numrows == 0){
              $res_liclicitem = $clliclicitem->sql_record($clliclicitem->sql_query_file(null,"l21_codigo",null,"l21_codpcprocitem = ".$dados[$x]));
              if ($clliclicitem->numrows > 0){
                db_fieldsmemory($res_liclicitem,0);

                $clpcorcamitem->pc22_codorc = $pc22_codorc;
                $clpcorcamitem->incluir(null);
                if ($clpcorcamitem->erro_status == 0){
                  $sqlerro  = true;
                  $erro_msg = $clpcorcamitem->erro_msg;
                  break;
                }

                if ($sqlerro == false){
                  $pc22_orcamitem = $clpcorcamitem->pc22_orcamitem;

                  $clpcorcamitemlic->pc26_orcamitem  = $pc22_orcamitem;
                  $clpcorcamitemlic->pc26_liclicitem = $l21_codigo;
                  $clpcorcamitemlic->incluir(null);

                  if ($clpcorcamitemlic->erro_status == 0){
                    $sqlerro  = true;
                    $erro_msg = $clpcorcamitemlic->erro_msg;
                    break;
                  }
                }
              }
            }
   	      }
    	  }

  	    db_fim_transacao(false);
      }
    }

  	if ($sqlerro==false){

      db_msgbox("Inclusão Efetivada com Sucesso!!");
      echo "<script>parent.parent.iframe_liclicita.bloquearRegistroPreco();</script>";
      if (isset($tipojulg)&&trim($tipojulg)!=""&&$tipojulg==3){
           echo "<script>
                        parent.parent.iframe_liclicitemlote.location.href = 'lic1_liclicitemlote001.php?licitacao=$licitacao&tipojulg=$tipojulg';\n
                        parent.parent.document.formaba.liclicitemlote.disabled=false;

                 </script>";
      }
 	}else{
 		  //db_msgbox(@$erro_msg);
  		db_msgbox("Operação Cancelada!!Contate Suporte!!");
  	}
  	$incluir="";
  }
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_submit_form(){
  document.form1.codprocant.value=document.form1.codproc.value;
  js_gera_chaves();
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<!--
<style>
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #999999;
}
.bordas{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #cccccc;
}
</style>
-->
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
    <center>
    <?

           db_input("tipojulg"  ,1 ,'',true,"hidden",3);
           db_input('licitacao' ,10,'',true,'hidden',3);
           db_input('incluir'   ,10,'',true,'hidden',3);
           db_input('codproc'   ,10,'',true,'hidden',3);
           db_input('codprocant',10,'',true,'hidden',3);
           db_input('cods'      ,10,'',true,'hidden',3);

           if (isset($codproc)&&$codproc!=""){
              $sql = $clpcprocitem->sql_query_pcmater(null,
              		                                  "distinct
              		                                   pc81_codprocitem,
              		                                   pc11_seq,
              		                                   pc11_codigo,
              		                                   pc11_quant,
              		                                   pc11_vlrun,
              		                                   m61_descr,
              		                                   pc01_codmater,
              		                                   pc01_descrmater,
              		                                   pc11_resum",
              		                                  "pc11_seq",
              		                                  "pc81_codproc=$codproc");
              $sql_disabled = $clpcprocitem->sql_query_pcmater(null,
                                                               "distinct pc81_codprocitem",
                                                               null,
                                                               "pc81_codproc={$codproc}
                                                                and (    l21_codliclicita <> {$licitacao}
                                                                      or l21_codliclicita = {$licitacao} and l21_codigo is not null
                                                                      or ( e54_anulad is null and e55_sequen is not null)
                                                                    )");
              if (isset($cods)&&$cods!=""){
                $sql_marca = $clpcprocitem->sql_query_pcmater(null,
                                                              "distinct
                		                                       pc81_codprocitem,
                		                                       pc11_seq,
                		                                       pc11_codigo,
                		                                       pc11_quant,
                		                                       pc11_vlrun,
                                                               m61_descr,
                		                                       pc01_codmater,
                		                                       pc01_descrmater,
                                                               pc11_resum",
                		                                       null,
                                                              "pc81_codproc=$codproc
                                                               and l21_codigo is not null
                                                               and l21_codliclicita = $licitacao
                                                              ");
              }
           }

           $cliframe_seleciona->campos  = "pc81_codprocitem,pc11_seq,pc11_codigo,pc11_quant,pc11_vlrun,m61_descr,pc01_codmater,pc01_descrmater,pc11_resum";
           $cliframe_seleciona->legenda="Itens";
           $cliframe_seleciona->sql=@$sql;
           $cliframe_seleciona->sql_marca=@$sql_marca;

           if (isset($codproc)&&$codproc!="") {

             $result_param = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit")));
             if ($clpcparam->numrows>0) {
           	   db_fieldsmemory($result_param,0);

           	   if ($pc30_contrandsol=='t') {

           		  $result_prot=$clpcprocitem->sql_record($clpcprocitem->sql_query_solprot(null,"*",null,"pc80_codproc=$codproc"));
           		  if ($clpcprocitem->numrows>0){
           		    $sql_disabled = " select pc81_codprocitem
                                        from ( select solandam.pc43_solicitem,
                                                      max(pc43_ordem) as pc43_ordem
                                                 from solandam
                                                group by solandam.pc43_solicitem) as x
                                             inner join solandam     on solandam.pc43_solicitem = x.pc43_solicitem
                                                                    and solandam.pc43_ordem     = x.pc43_ordem
                                             inner join solandpadrao on solandam.pc43_solicitem = solandpadrao.pc47_solicitem
                                                                    and solandam.pc43_ordem     = solandpadrao.pc47_ordem
                                             inner join pcprocitem   on x.pc43_solicitem        = pc81_solicitem
                                              left join liclicitem   on pc81_codprocitem        = l21_codpcprocitem
                                       where (pc81_codproc = $codproc and solandpadrao.pc47_pctipoandam <> 5)
                                         and (l21_codliclicita = $licitacao and l21_codigo is not null)";
           		  }

               }
             }
           }

           $cliframe_seleciona->sql_disabled=@$sql_disabled;
           //$cliframe_seleciona->iframe_height ="200";
           //$cliframe_seleciona->iframe_width ="100";
           $cliframe_seleciona->iframe_nome ="itens_teste";
           $cliframe_seleciona->chaves = "pc81_codprocitem";
           $cliframe_seleciona->iframe_seleciona(1);
    ?>
    </center>
    </td>
  </tr>
</table>
</form>
<script>
</script>
</body>
</html>