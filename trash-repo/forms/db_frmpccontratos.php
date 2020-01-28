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

//MODULO: compras
$clpccontratos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("p70_descr");
$clrotulo->label("l03_tipo");
$clrotulo->label("p71_datalanc");
$clrotulo->label("o58_orgao");
$clrotulo->label("o58_orgao");
$clrotulo->label("o58_orgao");
$clrotulo->label("o58_orgao");
$clrotulo->label("pc50_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tp71_codcontr?>">
       <?=@$Lp71_codcontr?>
    </td>
    <td> 
<?
db_input('p71_codcontr',10,$Ip71_codcontr,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp71_codtipo?>">
       <?
       db_ancora(@$Lp71_codtipo,"js_pesquisap71_codtipo(true);",($db_opcao == 1?1:3));
       ?>
    </td>
    <td> 
<?
db_input('p71_codtipo',8,$Ip71_codtipo,true,'text',($db_opcao == 1?1:3)," onchange='js_pesquisap71_codtipo(false);'");
//db_input('p71_codtipo',8,$Ip71_codtipo,true,'text',$db_opcao," onchange='js_pesquisap71_codtipo(false);'","p71_codtipo_old")
?>
       <?
db_input('p70_descr',40,$Ip70_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <?
  if(isset($p71_codtipo)){
    include("classes/db_pctipocontrato_classe.php");
    $clpctipocontrato = new cl_pctipocontrato;
    $res = $clpctipocontrato->sql_record($clpctipocontrato->sql_query(@$p71_codtipo));
    if($clpctipocontrato->numrows > 0){
      db_fieldsmemory($res,0);
      if($p70_tipo == 'D'){
        $clpccontrdep->rotulo->label();
      ?>
  <tr>
    <td nowrap title="<?=@$Tp74_valor?>">
       <?=@$Lp74_valor?>
    </td>
    <td> 
<?
db_input('p74_valor',20,$Ip74_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp74_perc?>">
       <?=@$Lp74_perc?>
    </td>
    <td> 
<?
db_input('p74_perc',20,$Ip74_perc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>

      <?
      }elseif($p70_tipo == 'L'){
        $clpccontrlic->rotulo->label();
      ?>
  <tr>
    <td nowrap title="<?=@$Tp75_tipo?>">
       <?
       db_ancora(@$Lp75_tipo,"js_pesquisap75_tipo(true);",($db_opcao == 1?1:3));
       ?>
    </td>
    <td> 
<?
include("classes/db_cflicita_classe.php");
$clcflicita = new cl_cflicita;
$result = $clcflicita->sql_record($clcflicita->sql_query());
db_selectrecord("p75_tipo",$result,true,($db_opcao == 1?1:3),"","p75_tipo");
?>
       <?
db_input('l03_tipo',1,$Il03_tipo,true,'hidden',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp75_numero?>">
       <?=@$Lp75_numero?>
    </td>
    <td> 
<?
db_input('p75_numero',8,$Ip75_numero,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
      <?
      }elseif($p70_tipo == 'C'){
        $clpccontrcompra->rotulo->label();
      ?>
  <tr>
    <td nowrap title="<?=@$Tp72_codcom?>">
       <?
       db_ancora(@$Lp72_codcom,"js_pesquisap72_codcom(true);",($db_opcao == 1?1:3));
       ?>
    </td>
    <td> 
<?
include("classes/db_pctipocompra_classe.php");
$clpctipocompra = new cl_pctipocompra;
$result = $clpctipocompra->sql_record($clpctipocompra->sql_query());
db_selectrecord("p72_codcom",$result,true,($db_opcao == 1?1:3),"","p72_codcom");
       ?>
    </td>
  </tr>
      <?
      }
    }
  }
  ?>
  <tr>
    <td nowrap title="<?=@$Tp71_datalanc?>">
       <?=@$Lp71_datalanc?>
    </td>
    <td> 
<?
if(empty($p71_datalanc_dia)){
  $p71_datalanc_dia = date("d",db_getsession("DB_datausu"));
  $p71_datalanc_mes = date("m",db_getsession("DB_datausu"));
  $p71_datalanc_ano = date("Y",db_getsession("DB_datausu"));
} 
db_inputdata('p71_datalanc',@$p71_datalanc_dia,@$p71_datalanc_mes,@$p71_datalanc_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp71_numcgm?>">
       <?
       db_ancora(@$Lp71_numcgm,"js_pesquisap71_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p71_numcgm',8,$Ip71_numcgm,true,'text',$db_opcao," onchange='js_pesquisap71_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp71_dtini?>">
       <?=@$Lp71_dtini?>
    </td>
    <td> 
<?
db_inputdata('p71_dtini',@$p71_dtini_dia,@$p71_dtini_mes,@$p71_dtini_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp71_dtfim?>">
       <?=@$Lp71_dtfim?>
    </td>
    <td> 
<?
db_inputdata('p71_dtfim',@$p71_dtfim_dia,@$p71_dtfim_mes,@$p71_dtfim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisap71_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?testanome=true&funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.p71_numcgm.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?testanome=true&pesquisa_chave='+document.form1.p71_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.p71_numcgm.focus(); 
    document.form1.p71_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.p71_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisap71_codtipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_pctipocontrato','func_pctipocontrato.php?funcao_js=parent.js_mostrapctipocontrato1|p70_codtipo|p70_descr','Pesquisa',true);
  }else{
     if(document.form1.p71_codtipo.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_pctipocontrato','func_pctipocontrato.php?pesquisa_chave='+document.form1.p71_codtipo.value+'&funcao_js=parent.js_mostrapctipocontrato','Pesquisa',false);
     }else{
       document.form1.p70_descr.value = ''; 
     }
  }
}
function js_mostrapctipocontrato(chave,erro){
  document.form1.p70_descr.value = chave; 
  if(erro==true){ 
    document.form1.p71_codtipo.focus(); 
    document.form1.p71_codtipo.value = ''; 
  }else{
    <?
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?db_opcao=$db_opcao&abas=1&p71_codtipo='+document.form1.p71_codtipo.value+'&p70_descr='+chave";
    ?>
  }
}
function js_mostrapctipocontrato1(chave1,chave2){
  document.form1.p71_codtipo.value = chave1;
  document.form1.p70_descr.value = chave2;
  <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?db_opcao=$db_opcao&".($db_opcao == 2 && @$p71_codcontr != ""?"chavepesquisa=$p71_codcontr&":"")."abas=1&p71_codtipo='+chave1+'&p70_descr='+chave2\n";
  ?>
  db_iframe_pctipocontrato.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_pccontratos','func_pccontratos.php?funcao_js=parent.js_preenchepesquisa|p71_codcontr','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_pccontratos.hide();
  <?
    if($db_opcao == 2 || $db_opcao == 22){
      echo " location.href = 'com1_pccontratos002.php?abas=1&chavepesquisa='+chave;";
    }elseif($db_opcao == 33 || $db_opcao == 3){
      echo " location.href = 'com1_pccontratos003.php?abas=1&chavepesquisa='+chave;";
    }
  ?>
}
</script>