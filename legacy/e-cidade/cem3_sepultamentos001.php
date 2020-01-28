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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_sepultamentos_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clsepultamentos = new cl_sepultamentos;
$db_opcao        = 1;
$db_botao        = true;

if(isset($sepultamento)){
   $campos = "cm01_i_codigo,cgm.z01_numcgm,cgm.z01_nome,cgm.z01_pai, cgm.z01_mae, cm01_c_conjuge, cm01_d_falecimento, cm01_c_cor,
              case when cm16_i_cemiterio is not null
                   then cm16_c_nome
                   when cm15_i_cemiterio is not null
                   then cgm5.z01_nome
              end as nome_cemiterio,
              case when cm16_i_cemiterio is not null
                   then cm16_i_cemiterio
                   when cm15_i_cemiterio is not null
                   then cm15_i_cemiterio
              end as cm01_i_cemiterio,
   		        cm01_observacoes";
   $result = $clsepultamentos->sql_record($clsepultamentos->sql_query($sepultamento,$campos));

   @db_fieldsmemory($result,0);
   if($clsepultamentos->numrows > 0){
     $db_opcao = 3;
   }
}

$clsepultamentos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("cm14_i_codigo");
$clrotulo->label("nome");
$clrotulo->label("sd03_c_nome");
$clrotulo->label("cm04_c_descr");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default abas">
  <div class="container">
        <?if(!isset($cm01_i_codigo)){?>
            <form name="form1" method="post" action="">
             <table border="0">
              <tr>
               <td nowrap title="<?=@$Tcm01_i_codigo?>">
            <?db_ancora(@$Lcm01_i_codigo,"js_pesquisacm01_i_codigo(true);",$db_opcao);?>
               </td>
               <td>
            <?db_input('sepultamento',10,$Icm01_i_codigo,true,'text',$db_opcao," onchange='js_pesquisacm01_i_codigo(false);'")?>
            <?db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')?>
               </td>
              </tr>
             </table>
            <input name="processar" type="button" id="db_opcao" value="Processar" <?=($db_botao==false?"disabled":"")?> onclick="js_valida();">
            </form>
          <?php
           }
           if($clsepultamentos->numrows > 0){
            include("forms/db_frmsepultamentos.php");
           }
          ?>
  </div>
<script>
//busca cgm / falecido
function js_pesquisacm01_i_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_cgm','func_sepultamentos.php?funcao_js=parent.js_mostracgm1|cm01_i_codigo|z01_nome','Pesquisa',true);
  }else{

     if(document.form1.sepultamento.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_cgm','func_sepultamentos.php?pesquisa_chave='+document.form1.sepultamento.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostracgm(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){

    document.form1.sepultamento.focus();
    document.form1.sepultamento.value = '';
  }
}
function js_mostracgm1(chave1,chave2){

  document.form1.sepultamento.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}

function js_valida(){
 if(  document.form1.sepultamento.value == ''){

  alert('Preencha o Sepultamento');
  document.form1.sepultamento.focus();
 }
 else{
  document.form1.submit();
 }
}
<? if($clsepultamentos->numrows != 0){ ?>

  parent.document.formaba.a2.disabled = false;
  parent.document.formaba.a3.disabled = false;
  parent.document.formaba.a4.disabled = false;
  top.corpo.iframe_a2.location.href   = 'cem3_sepultamentos002.php?sepultamento=<?=$cm01_i_codigo?>';
  top.corpo.iframe_a3.location.href   = 'cem3_sepultamentos003.php?sepultamento=<?=$cm01_i_codigo?>';
  top.corpo.iframe_a4.location.href   = 'cem3_sepultamentos004.php?sepultamento=<?=$cm01_i_codigo?>';
<?}?>
</script>
</body>
</html>