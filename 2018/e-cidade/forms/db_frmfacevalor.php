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

require_once("dbforms/db_classesgenericas.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clfacevalor->rotulo->label();
if(isset($db_opcaoal)){

  $db_opcao = 33;
  $db_botao = false;
}else if(isset($opcao) && $opcao=="alterar"){

  $db_botao = true;
  $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){

  $db_opcao = 3;
  $db_botao = true;
}else{

  $db_opcao = 1;
  $db_botao = true;
  if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){

    $j81_codigo       = "";
    $j81_anousu       = "";
    $j81_valorterreno = "";
    $j81_valorconstr  = "";
  }
}
?>
<form name="form1" method="post" action="">

<fieldset>
 <legend>Face / Valor</legend>

<table>
  <tr>
    <td nowrap title="<?=@$Tj81_face?>">
       <?=@$Lj81_face?>
    </td>
    <td>
      <?php
        db_input('j81_codigo',10,$Ij81_codigo,true,'hidden',3,"");
        db_input('j81_face',4,$Ij81_face,true,'text',3,"");
      ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tj81_anousu?>">
       <?=@$Lj81_anousu?>
    </td>
    <td>
      <?php
        db_input('j81_anousu',4,$Ij81_anousu,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tj81_valorterreno?>">
       <?=@$Lj81_valorterreno?>
    </td>
    <td>
      <?php
        db_input('j81_valorterreno',15,$Ij81_valorterreno,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tj81_valorconstr?>">
       <?=@$Lj81_valorconstr?>
    </td>
    <td>
      <?php
        db_input('j81_valorconstr',15,$Ij81_valorconstr,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  </table>
</fieldset>

  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
  <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >

 <table>
  <tr>
    <td valign="top"  align="center">
    <?php
  	 $chavepri = array("j81_codigo" => @$j81_codigo);
  	 $cliframe_alterar_excluir->chavepri      = $chavepri;
  	 $cliframe_alterar_excluir->sql           = $clfacevalor->sql_query_file(null,"*",null,"j81_face=$j81_face");
  	 $cliframe_alterar_excluir->campos        = "j81_codigo, j81_face, j81_anousu, j81_valorterreno, j81_valorconstr";
  	 $cliframe_alterar_excluir->legenda       = "ITENS LANÇADOS";
  	 $cliframe_alterar_excluir->iframe_height = 160;
  	 $cliframe_alterar_excluir->iframe_width  = 700;
  	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
</form>
<script type="text/javascript">

function js_cancelar(){

  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
</script>