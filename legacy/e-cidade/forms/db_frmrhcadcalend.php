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

//MODULO: pessoal
$clrhcadcalend->rotulo->label();
$clcalendf->rotulo->label();
if((isset($incluir) || isset($alterar) || isset($excluir)) && isset($sqlerro) && $sqlerro == false){
  $r62_data_dia = "";
  $r62_data_mes = "";
  $r62_data_ano = "";
  $r62_data = "";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh53_calend?>">
       <?=@$Lrh53_calend?>
    </td>
    <td>
<?
db_input('rh53_calend',10,$Irh53_calend,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh53_descr?>">
       <?=@$Lrh53_descr?>
    </td>
    <td>
<?
db_input('rh53_descr',40,$Irh53_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <?
  if($db_opcao == 2){
  ?>
  <tr>
    <td nowrap title="<?=@$Tr62_data?>">
       <?=@$Lr62_data?>
    </td>
    <td>
<?
db_inputdata('r62_data',@$r62_data_dia,@$r62_data_mes,@$r62_data_ano,true,'text',$db_opcao);
?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <?
      include(modification("dbforms/db_classesgenericas.php"));
      $dbwhere = " r62_calend = $rh53_calend ";
      if(isset($r62_data) && trim($r62_data) != ""){
        $dbwhere .= " and r62_data <> '".$r62_data."'";
      }
      $cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
      $chavepri= array("r62_calend"=>@$r62_calend,"r62_data"=>@$r62_data);
      $cliframe_alterar_excluir->chavepri=$chavepri;
      $cliframe_alterar_excluir->opcoes  = 3;
      $cliframe_alterar_excluir->sql     = $clcalendf->sql_query_file(null,null,"r62_calend, r62_data","r62_data",$dbwhere);
      $cliframe_alterar_excluir->campos  ="r62_calend, r62_data";
      $cliframe_alterar_excluir->legenda="DATAS LANÇADAS";
      $cliframe_alterar_excluir->iframe_height ="160";
      $cliframe_alterar_excluir->iframe_width ="700";
      $cliframe_alterar_excluir->iframe_alterar_excluir(1);
      ?>
    </td>
  </tr>
  <?
    $db_opcao = 1;
    if(isset($opcao)){
      $db_opcao = 2;
    }
  }
  ?>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<?if($db_opcao == 2){?>
<input name="excluir" type="submit" id="db_opcao" value="Excluir data">
<?if(isset($opcao)){?>
<input name="novo" type="button" id="novo" value="Novo" onclick="location.href='pes1_rhcadcalend002.php?chavepesquisa=<?=$rh53_calend?>'" >
<?}?>
<?}?>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhcadcalend','func_rhcadcalend.php?funcao_js=parent.js_preenchepesquisa|rh53_calend','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhcadcalend.hide();

  location.href='pes1_rhcadcalend002.php?chavepesquisa='+chave;
  <?php
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
   }
  ?>
}
</script>
