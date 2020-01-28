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

require_once ("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cliptucalcpadraoconstr->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j10_anousu");
$clrotulo->label("j01_numcgm");
if(isset($db_opcaoal)){

  $db_opcao=33;
  $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){

  $db_botao=true;
  $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){

  $db_opcao = 3;
  $db_botao=true;
}else{

  $db_opcao = 1;
  $db_botao=true;
  if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){

    $j11_idcons = "";
    $j11_vlrcons = "";
  }
}

?>
<form name="form1" method="post" action="">
<?php
  db_input('j11_sequencial',10,$Ij11_sequencial,true,'hidden',3,"");
  db_input('j11_iptucalcpadrao',10,$Ij11_iptucalcpadrao,true,'hidden',$db_opcao);
?>
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj11_matric?>">
        <strong>Matrícula:</strong>
    </td>
    <td>
      <?
      db_input('j11_matric',10,$Ij11_matric,true,'text',3)
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj11_idcons?>">
       <?
       db_ancora(@$Lj11_idcons,"js_pesquisaj11_idcons(true);",$db_opcao);
       ?>
    </td>
    <td>
      <?
      db_input('j11_idcons',10,$Ij11_idcons,true,'text',$db_opcao," onchange='js_pesquisaj11_idcons(false);'")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj11_vlrcons?>">
       <?=@$Lj11_vlrcons?>
    </td>
    <td>
      <?
      db_input('j11_vlrcons',10,$Ij11_vlrcons,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  </tr>
    <td colspan="2" align="center">
     <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_enviar();" />
     <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">
    <?php
    	$iAnoUsu = db_getsession("DB_anousu");
    	$sql  = "select j11_sequencial,                                                        ";
    	$sql .= "       j11_iptucalcpadrao,                                                    ";
    	$sql .= "       j11_matric,                                                            ";
    	$sql .= "       j11_idcons,                                                            ";
    	$sql .= "       j11_vlrcons,                                                           ";
    	$sql .= "       j22_anousu as db_ano_origem,                                           ";
    	$sql .= "       j22_valor as db_valor_origem,                                          ";
    	$sql .= "       case when j22_anousu is null then null                                 ";
      $sql .= "            else j10_perccorre                                                ";
      $sql .= "       end as j10_perccorre                                                   ";
    	$sql .= "  from iptucalcpadraoconstr                                                   ";
    	$sql .= "       inner join iptucalcpadrao       on j11_iptucalcpadrao = j10_sequencial ";
    	$sql .= "       left  join iptuconstr           on j11_matric         = j39_matric     ";
    	$sql .= "                                      and j11_idcons         = j39_idcons     ";
    	$sql .= "       left join iptucalcpadraoorigem  on j27_iptucalcpadrao = j10_sequencial ";
    	$sql .= "       left  join iptucale             on j27_matric         = j22_matric     ";
    	$sql .= "                                      and j39_idcons         = j22_idcons     ";
    	$sql .= "                                      and j27_anousu         = j22_anousu     ";
    	$sql .= "where j11_matric = $j11_matric and  j10_anousu =  {$iAnoUsu}                  ";

      $chavepri = array("j11_sequencial"=>@$j11_sequencial);
      $cliframe_alterar_excluir->chavepri      = $chavepri;
      $cliframe_alterar_excluir->sql           = $sql;
      $cliframe_alterar_excluir->alignlegenda  = "left";
      $cliframe_alterar_excluir->campos        = "j11_sequencial,j11_iptucalcpadrao,j11_matric,j11_idcons,j11_vlrcons,db_ano_origem,db_valor_origem,j10_perccorre";
      $cliframe_alterar_excluir->legenda       = "ITENS LANÇADOS";
      $cliframe_alterar_excluir->iframe_height = "200";
      $cliframe_alterar_excluir->iframe_width  = "800";
      $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
  </center>
</form>
<script type="text/javascript">

$("j11_matric").addClassName("field-size5");
$("j11_idcons").addClassName("field-size5");
$("j11_vlrcons").addClassName("field-size5");

function js_enviar(){

  if( empty($F('j11_vlrcons')) || $F('j11_vlrcons') <= 0 ){

    alert('Campo Valor da construção é de preenchimento obrigatório e não pode ser nulo.');
    return false;
  }

  return true;
}

function js_cancelar(){

  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}

function js_pesquisaj11_idcons(mostra){

  if(mostra==true){

    js_OpenJanelaIframe('','db_iframe_iptuconstr','func_iptuconstralt.php?j11_matric='+document.form1.j11_matric.value+'&funcao_js=parent.js_mostraconstr1|j39_idcons','Pesquisa',true,'0','1');
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    js_OpenJanelaIframe('','db_iframe_iptuconstr','func_iptuconstralt.php?j11_matric='+document.form1.j11_matric.value+'&pesquisa_chave='+document.form1.j11_idcons.value+'&funcao_js=parent.js_mostraconstr','Pesquisa',false);
  }
}
function js_mostraconstr1(chave){
  db_iframe_iptuconstr.hide();
  document.form1.j11_idcons.value = chave;
}
function js_mostraconstr(chave,msg){
  db_iframe_iptuconstr.hide();
  if(msg!=null){
    alert(msg);
  }
  document.form1.j11_idcons.value = chave;
}
</script>