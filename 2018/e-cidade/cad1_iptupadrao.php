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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_iptubase_classe.php");

$cliptubase = new cl_iptubase;
$cliptubase->rotulo->label();

db_postmemory($HTTP_POST_VARS);

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
<body class="body-default">

  <div class="container">

   <form name="form1" method="post" action="">

   <fieldset style="width: 510px;">

    <legend>Digitação para o cálculo</legend>

    <table>
        <tr>
           <td>
             <?php
              db_ancora($Lj01_matric,' js_matri(true); ',1);
             ?>
           </td>
           <td>
            <?php

             db_input('j01_matric', 5, 0, true, 'text', 1, "onchange='js_matri(false)'");
             db_input('z01_nome', 30, 0, true, 'text', 3, "");
            ?>
           </td>
        </tr>
        <tr>
           <td ><strong>Forma de Inclusão:<strong></td>
           <td >
           <?php

            $aFormaInclusao = array( "0" => "Nenhum",
                                     "1" => "Digitação manual ",
                                     "2" => "Importar calculos anteriores");
            db_select("forma", $aFormaInclusao, 1, 1, "onchange='js_mostraexerc()'");
           ?>
           </td>
        </tr>
        <tr >
           <td ><strong>Exercício origem:<strong></td>
           <td >
           <?
            $arrayexec = array("0" => "Nenhum ");
            db_select("exec",$arrayexec,1,1,"onchange='js_mostraexerc(document.form1.exec.value)'");
           ?>
           </td>
        </tr>
        <tr>
           <td><strong>Percentual:<strong></td>
           <td><? db_input('percentual',10,0,true,'text',1,""); ?></td>
        </tr>
    </table>
    </fieldset>

    <input name="processar" id="processar" type="button" value="Processar" onclick="js_emite();" />

   </form>

   </fieldset>

  </div>
  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<script type="text/javascript">

$("z01_nome").addClassName("field-size7");
$("percentual").addClassName("field-size5");
$("exec").addClassName("field-size5");
$("forma").addClassName("field-size5");

$("forma").disabled      = true;
$("exec").disabled       = true;
$("percentual").disabled = true;

function js_emite(){

  var iMatricula       = $F('j01_matric');
  var iFormaInclusao   = $F('forma');
  var iExercicioOrigem = $F('exec');
  var iPercentual      = $F('percentual');

  if ( iFormaInclusao == '2' && iExercicioOrigem == '0' ) {

    alert('Campo Forma de Inclusão é de preencimento obrigatório.');
    return false;
  }

  if ( iFormaInclusao == '2' && iPercentual == '' ) {

    alert('Campo Percentual é de preencimento obrigatório.');
    return false;
  }

  if( empty(iMatricula)){

    alert('Campo Matrícula é de preencimento obrigatório.');
    return false;
  }

  if(iFormaInclusao == 0){

    alert('Campo Forma de inclusão é de preencimento obrigatório.');
    return false;
  }

  location.href = 'cad1_iptucalcpadrao001.php?j01_matric='+iMatricula+'&forma='+iFormaInclusao+'&exec='+iExercicioOrigem+'&perc='+iPercentual;
}

function js_matri(mostra){

  document.form1.forma.value           = 0;
  document.form1.exec.length           = 0;
  document.form1.exec.options[0]       = new Option();
  document.form1.exec.options[0].value = 0;
  document.form1.exec.options[0].text  = 'Nenhum';
  document.form1.exec.disabled         = true;
  document.form1.percentual.disabled   = true;
  document.form1.percentual.value      = "";
  var matri = document.form1.j01_matric.value;

  if(mostra==true){

    db_iframe.jan.location.href = 'func_iptubasealtpadrao.php?funcao_js=parent.js_mostra|j01_matric|z01_nome';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_iptubasealtpadrao.php?pesquisa_chave='+matri+'&funcao_js=parent.js_mostra1';
  }
  if(matri!=""){
    document.form1.forma.disabled=false;
  }
}
function js_mostra(chave1,chave2){

  document.form1.j01_matric.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe.hide();
}
function js_mostra1(chave,erro){

  document.form1.z01_nome.value = chave;
  if(erro==true){

    document.form1.j01_matric.focus();
    document.form1.j01_matric.value = '';
  }
}

function js_mostraexerc(obj){

  var forma      = document.form1.forma.value;
  var j01_matric = document.form1.j01_matric.value;

  if (typeof(obj) == 'undefined') {
    obj = '';
  }

  if( forma == 2 ){

   if(obj != ''){
     js_OpenJanelaIframe('','db_iframe_exerc','cad1_buscaexercicio.php?&forma='+forma+'&j01_matric='+j01_matric+'&exec='+obj,'Pesquisa',false);
   }else{
     js_OpenJanelaIframe('','db_iframe_exerc','cad1_buscaexercicio.php?&forma='+forma+'&j01_matric='+j01_matric,'Pesquisa',false);
   }
  }

  if( forma == 1 || forma == 0 ){

   document.form1.exec.length           = 0;
   document.form1.exec.options[0]       = new Option();
   document.form1.exec.options[0].value = 0;
   document.form1.exec.options[0].text  = 'Nenhum';
   document.form1.exec.disabled         = true;
   document.form1.percentual.disabled   = true;
   document.form1.percentual.value      = "";
  }
}

function js_addSelectFromStr(str){

  var obj = document.form1.exec;
  obj.length = 0;
  var linhas = str.split("X");

  for(i=0;i<linhas.length;i++){

    obj.options[i]       = new Option();
    obj.options[i].value = linhas[i];
    obj.options[i].text  = linhas[i];
  }
  document.form1.exec.disabled=false;
  document.form1.percentual.disabled=false;
}

</script>
<?php

  $func_iframe = new janela('db_iframe','');
  $func_iframe->posX           = 1;
  $func_iframe->posY           = 20;
  $func_iframe->largura        = 780;
  $func_iframe->altura         = 430;
  $func_iframe->titulo         = 'Pesquisa';
  $func_iframe->iniciarVisivel = false;
  $func_iframe->mostrar();

  if(isset($invalido)){
    echo "<script>alert('Numero de matrícula inválido!')</script>";
  }
?>