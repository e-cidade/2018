<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

include ("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo = new rotulocampo;
$clrhbasesreg->rotulo->label();
$clrotulo->label("z01_nome");
$clrotulo->label("r08_descr");
$clrotulo->label("rh27_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh54_regist?>">
      <?
      db_ancora(@$Lrh54_regist,"js_pesquisarh54_regist(true);",$db_opcao);
      ?>
    </td>
    <td>
      <?
      db_input('rh54_regist',6,$Irh54_regist,true,'text',$db_opcao," onchange='js_pesquisarh54_regist(false);'")
      ?>
      <?
      db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh54_base?>">
      <?
      db_ancora(@$Lrh54_base,"js_pesquisarh54_base(true);",$db_opcao);
      ?>
    </td>
    <td>
      <?
      db_input('rh54_base',6,$Irh54_base,true,'text',$db_opcao," onchange='js_pesquisarh54_base(false);'")
      ?>
      <?
      db_input('r08_descr',40,$Ir08_descr,true,'text',3,'')
      ?>
    </td>
  </tr>
  <?
  if(isset($rh54_regist) && trim($rh54_regist) != ""){
    $dbwhere = " rh54_regist = ".$rh54_regist;
    if(isset($rh54_base) && trim($rh54_base) != ""){
      $dbwhere .= " and rh54_base <> '".$rh54_base."'";
      $arr_sselecionados = Array();
      $arr_nselecionados = Array();
      $basesdefault = true;
      $result_rubrs = $clrhrubricas->sql_record($clrhrubricas->sql_query_basesreg(null,db_getsession('DB_instit'), " r09_rubric, rh54_regist, rh27_rubric, rh27_descr "," rh27_rubric ", "", @$rh54_base, $rh54_regist));
      for($i=0; $i<$clrhrubricas->numrows; $i++){
        db_fieldsmemory($result_rubrs, $i);
        if(trim($rh54_regist) != ""){
	  $basesdefault = false;
	  break;
        }
      }
      for($i=0; $i<$clrhrubricas->numrows; $i++){
        db_fieldsmemory($result_rubrs, $i);
        if(trim($rh54_regist) != "" || ($basesdefault == true && $r09_rubric != "")){
          $arr_sselecionados[$rh27_rubric] = $rh27_rubric . " - " . $rh27_descr;
        }else{
          $arr_nselecionados[$rh27_rubric] = $rh27_rubric . " - " . $rh27_descr;
        }
      }
    ?>
    <tr>
      <td colspan="2">
        <?
        db_multiploselect("value","descr", "naosel", "simsel", $arr_nselecionados, $arr_sselecionados, 25, 300, "Rubricas selecionadas", "Rubricas não selecionadas");
        ?>
      </td>
    </tr>
    <?
    }else{

   $sql_bases = $clrhbasesreg->sql_query_base(null,"distinct rh54_regist, rh54_base, r08_descr","rh54_base",$dbwhere);
  ?>
  <tr>
    <td colspan="2" align="center">
      <?
      $chavepri = Array("rh54_base"=>@$rh54_base,"rh54_regist"=>$rh54_regist);
      $cliframe_alterar_excluir->chavepri = $chavepri;
      $cliframe_alterar_excluir->sql      = $sql_bases;
      $cliframe_alterar_excluir->campos   = "rh54_regist, rh54_base, r08_descr";
      $cliframe_alterar_excluir->iframe_width  = "100%";
      $cliframe_alterar_excluir->opcoes   = 2;
      $cliframe_alterar_excluir->fieldset = false;
      $cliframe_alterar_excluir->iframe_alterar_excluir(1);
      ?>
    </td>
  </tr>
  <?
    }
  }
  ?>
</table>
</center>
<input name="incluir" type="submit" id="db_opcao" value="Processar" <?=($db_botao==false?"disabled":"")?> onclick="return js_selecionacombo();">
</form>
<script>
function js_selecionacombo(){
  js_seleciona_combo(document.form1.simsel);
}
function js_pesquisarh54_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=r&funcao_js=parent.js_mostrarhpessoal1|rh01_regist|z01_nome&instit=<?=db_getsession("DB_instit")?>','Pesquisa',true);
  }else{
    if(document.form1.rh54_regist.value != ''){
      js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=r&pesquisa_chave='+document.form1.rh54_regist.value+'&funcao_js=parent.js_mostrarhpessoal','Pesquisa',false);
    }else{
      document.form1.z01_nome.value = '';
      document.form1.submit();
    }
  }
}
function js_mostrarhpessoal(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.rh54_regist.focus();
    document.form1.rh54_regist.value = '';
  }else{
    document.form1.submit();
  }
}
function js_mostrarhpessoal1(chave1,chave2){
  document.form1.rh54_regist.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_rhpessoal.hide();
  document.form1.submit();
}
function js_pesquisarh54_base(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhbases','func_bases.php?funcao_js=parent.js_mostrarhbases1|r08_codigo|r08_descr','Pesquisa',true);
  }else{
    if(document.form1.rh54_base.value != ''){
      js_OpenJanelaIframe('top.corpo','db_iframe_rhbases','func_bases.php?pesquisa_chave='+document.form1.rh54_base.value+'&funcao_js=parent.js_mostrarhbases','Pesquisa',false);
    }else{
      document.form1.r08_descr.value = '';
      document.form1.submit();
    }
  }
}
function js_mostrarhbases(chave,erro){
  document.form1.r08_descr.value = chave;
  if(erro==true){
    document.form1.rh54_base.focus();
    document.form1.rh54_base.value = '';
  }else{
    document.form1.submit();
  }
}
function js_mostrarhbases1(chave1,chave2){
  document.form1.rh54_base.value = chave1;
  document.form1.r08_descr.value = chave2;
  db_iframe_rhbases.hide();
  document.form1.submit();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhbasesreg','func_rhbasesreg.php?funcao_js=parent.js_preenchepesquisa|rh54_seq','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhbasesreg.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>