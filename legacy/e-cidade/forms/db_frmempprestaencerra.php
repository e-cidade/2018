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

//MODULO: empenho
$clrotulo = new rotulocampo;
$clrotulo->label("e45_acerta");
$clrotulo->label("e60_codemp");
$clrotulo->label("e45_codmov");

if(isset($tranca)){
  $db_opcao = 33;
  $db_botao= false;
}
if($db_opcao==1){
  $nome ="Encerrar";
}else{
  $nome ="Atualizar";
}
require_once("libs/db_app.utils.php");
db_app::load("scripts.js");
db_app::load("prototype.js");
?>
<form name="form1" method="post" onsubmit="return js_verifica();" action="">
<center>
<fieldset style="width: 400px">
<legend><b>Encerramento</b></legend>

<table border="0">
  <tr>
    <td nowrap title="<?=@$Te60_codemp?>">
       <?=@$Le60_codemp?>
    </td>
    <td>
      <?php
      db_input('e60_codemp',10,$Ie60_codemp,true,'text',3);
      db_input('e60_numemp',10,0,true,'hidden',3);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?php echo $Te45_codmov; ?>">
      <?php echo $Le45_codmov; ?>
    </td>
    <td>
      <?php db_input('e45_codmov', 10, $Ie45_codmov, true); ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te45_acerta?>">
       <?=@$Le45_acerta?>
    </td>
    <td>
<?
if(  empty($e45_acerta_dia)  ){
//$e45_acerta_ano =  date("Y",db_getsession("DB_datausu"));
//$e45_acerta_mes =  date("m",db_getsession("DB_datausu"));
//$e45_acerta_dia =  date("d",db_getsession("DB_datausu"));
}
db_inputdata('e45_acerta',@$e45_acerta_dia,@$e45_acerta_mes,@$e45_acerta_ano,true,'text',$db_opcao);
?>
    </td>
  </tr>

  </table>
  </center>
</fieldset>
<br>
<input name="atualizar" type="submit" id="db_opcao" value="<?=$nome?>" <?=($db_botao==false?"disabled":"")?> >
</form>
<script>

  function js_verifica(){

    var dtDataEncerramento = $F("e45_acerta");

    if (dtDataEncerramento == "") {

      alert("Selecione a data de encerramento.");
      return false

    }
  }

  // Pega o código da movimentacao do campo na primeira aba
  document.form1.e45_codmov.value = top.corpo.iframe_emppresta.document.form1.e45_codmov.value;
</script>