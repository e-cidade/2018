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
$clrhregime->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh30_codreg?>">
      <?=@$Lrh30_codreg?>
    </td>
    <td> 
      <?db_input('rh30_codreg',2,$Irh30_codreg,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh30_descr?>">
      <?=@$Lrh30_descr?>
    </td>
    <td> 
      <?db_input('rh30_descr',40,$Irh30_descr,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh30_utilizacao?>">
      <?=@$Lrh30_utilizacao?>
    </td>
    <td> 
      <?
      $arr_util = Array('3'=>'Educação');
      db_select("rh30_utilizacao",$arr_util,true,$db_opcao,"onChange=\"js_mudautilizacao(this.value)\"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh30_regime?>">
      <?=@$Lrh30_regime?>
    </td>
    <td> 
      <?
      $result_regime = $clrhcadregime->sql_record($clrhcadregime->sql_query_file());
      db_selectrecord("rh30_regime", $result_regime, true, $db_opcao);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh30_vinculo?>">
      <?=@$Lrh30_vinculo?>
    </td>
    <td> 
      <?
      $arr_vinculo = Array('A'=>'Ativo','I'=>'Inativo','P'=>'Pensionista');
      db_select("rh30_vinculo",$arr_vinculo,true,$db_opcao);
      ?>
    </td>
  </tr>
    <tr>
    <td nowrap title="Natureza">
      <b>Natureza : </b>
    </td>
    <td> 
      <?
      $sSqlNatureza = "select rh71_sequencial,rh71_descricao from rhnaturezaregime";
      $rsNatureza   = db_query($sSqlNatureza);
      $iNatureza    = pg_num_rows($rsNatureza);
      $aNatureza    = Array();
      for ($i = 0; $i < $iNatureza; $i++) {

        db_fieldsmemory($rsNatureza,$i);
        $aNatureza[$rh71_sequencial] = $rh71_descricao;

      }
      db_select("rh30_naturezaregime",$aNatureza,true,$db_opcao);
      ?>
    </td>
  </tr>
  <tr>
    <td>
      <?=$Lrh30_vinculomanad?>
    </td>
    <td>
      <?
      include("classes/db_vinculomanad_classe.php");
      $clvinculomanad   = new cl_vinculomanad();
      $sSqlVinculomanad = $clvinculomanad->sql_query_file();
      $rsVinculomanad   = $clvinculomanad->sql_record($sSqlVinculomanad);
      db_selectrecord('rh30_vinculomanad',$rsVinculomanad,true,$db_opcao,'','','','','',1);
      ?>
      </td>
    </tr>
  <tr>
    <td> <?=$Lrh30_periodoaquisitivo?></td>
    <td>
      <?
      $aPeriodoArquisitivo = array("1"=>"12 meses", "2"=>"6 meses");
      db_select("rh30_periodoaquisitivo", $aPeriodoArquisitivo, true, $db_opcao, "style='width : 300px;'");
      ?>
    </td>
  </tr>
  </table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhregime','func_rhregimesec.php?funcao_js=parent.js_preenchepesquisa|rh30_codreg','Pesquisa',true);
}
function js_preenchepesquisa(chave){

  db_iframe_rhregime.hide();
  <?
  if ($db_opcao!=1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>

}
function js_mudautilizacao(valor){

  if (valor==3) {

    document.form1.rh30_regime[0].disabled = true;
    document.form1.rh30_regime[1].disabled = true;
    document.form1.rh30_regime[2].selected = true;
    document.form1.rh30_regimedescr[0].disabled = true;
    document.form1.rh30_regimedescr[1].disabled = true;
    document.form1.rh30_regimedescr[2].selected = true;
    document.form1.rh30_vinculo.value = "A";
    document.form1.rh30_vinculo[2].disabled = true;
    document.form1.rh30_naturezaregime.value = 4;
    selecionado = document.form1.rh30_naturezaregime.selectedIndex;
    tam = document.form1.rh30_naturezaregime.length;
    for (i=0;i<tam;i++) {

      if(i!=selecionado){
       document.form1.rh30_naturezaregime[i].disabled = true;
      }

    }
    document.form1.rh30_vinculomanad.value = 9;
    selecionado = document.form1.rh30_vinculomanad.selectedIndex;
    tam = document.form1.rh30_vinculomanad.length;
    for (i=0;i<tam;i++) {

      if (i!=selecionado) {
        document.form1.rh30_vinculomanad[i].disabled = true;
      }

    }

  }else{

    document.form1.rh30_regime[0].disabled = false;
    document.form1.rh30_regime[1].disabled = false;
    document.form1.rh30_regimedescr[0].disabled = false;
    document.form1.rh30_regimedescr[1].disabled = false;
    document.form1.rh30_vinculo[2].disabled = false;
    tam = document.form1.rh30_naturezaregime.length;
    for (i=0;i<tam;i++) {
      document.form1.rh30_naturezaregime[i].disabled = false;
    }
    tam = document.form1.rh30_vinculomanad.length;
    for (i=0;i<tam;i++) {
      document.form1.rh30_vinculomanad[i].disabled = false;
    }

  }

}
<?if (!isset($chavepesquisa)) {?>
 js_mudautilizacao(3);
<?}?>
</script>