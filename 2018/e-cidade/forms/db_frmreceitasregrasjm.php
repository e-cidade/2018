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

//MODULO: caixa
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo = new rotulocampo;
$cltabrec->rotulo->label();
$cltabrecregrasjm->rotulo->label();
$clrotulo->label("k02_corr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk04_sequencial?>" align="left">
      <?=@$Lk04_sequencial?>
    </td>
    <td>    
      <?
      db_input('k04_sequencial',6,$Ik04_sequencial,true,'text',3,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk04_receit?>" align="left">
      <?=@$Lk04_receit?>
    </td>
    <td nowrap>
      <?
      db_input('k04_receit',6,$Ik04_receit,true,'text',3,"");
      ?>
      <?
      db_input('k02_descr',20,$Lk02_descr,true,'text',3);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk04_codjm?>" align="left">
      <?
      db_ancora(@$Lk04_codjm,"js_pesquisak04_codjm(true);",$db_opcao);
      ?>
    </td>
    <td> 
      <?
      db_input('k04_codjm',4,$Ik04_codjm,true,'text',$db_opcao,"onchange='js_pesquisak04_codjm(false)'");
       db_input('k02_corr',40,$Ik02_corr,true,'text',3,"");
      ?>
    </td>
  </tr>
  <tr>
    <td title=<?=@$Tk04_dtini?> align="left">
      <?=$Lk04_dtini?>
    </td>
    <td>     
      <?
      db_inputdata('k04_dtini',@$k04_dtini_dia,@$k04_dtini_mes,@$k04_dtini_ano,true,'text',$db_opcao);
      ?>
    </td>
  </tr>
  <tr>
    <td title=<?=@$Tk04_dtfim?> align="left">
      <?=$Lk04_dtfim?>
    </td>
    <td>     
      <?
      db_inputdata('k04_dtfim',@$k04_dtfim_dia,@$k04_dtfim_mes,@$k04_dtfim_ano,true,'text',$db_opcao);
      ?>
    </td>
  </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<?
if($db_opcao != 1){
?>
<input name="novo" type="button" id="novo" value="Novo" onclick="location.href='cai1_receitaregrasjm001.php?k04_receit=<?=$k04_receit?>'">
<?
}
?>
<table>
  <tr>
    <td valign="top"  align="center">  
      <?
      $dbwhere = " k04_receit = ".$k04_receit;
      if(isset($k04_sequencial) && trim($k04_sequencial) != ""){
	$dbwhere .= " and k04_sequencial <> ".$k04_sequencial;
      }
      $sql = $cltabrecregrasjm->sql_query_tabrec(null,"k04_sequencial, k04_codjm, k04_dtini, k04_dtfim","k04_dtfim desc",$dbwhere);
      $chavepri= array("k04_sequencial"=>@$k04_sequencial);
      $cliframe_alterar_excluir->chavepri=$chavepri;
      $cliframe_alterar_excluir->opcoes  = 1;
      $cliframe_alterar_excluir->sql     = $sql;
      $cliframe_alterar_excluir->campos  ="k04_codjm, k04_dtini, k04_dtfim";
      $cliframe_alterar_excluir->legenda ="REGRAS DE JURO E MULTA";
      $cliframe_alterar_excluir->iframe_height ="160";
      $cliframe_alterar_excluir->iframe_width ="700";
      $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
      ?>
    </td>
  </tr>
</table>
</center>
</form>
<script>
function js_pesquisak04_codjm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_tabrecregrasjm','db_iframe_tabrecjm','func_tabrecjm.php?funcao_js=top.corpo.iframe_tabrecregrasjm.js_mostratabrecjm1|k02_codjm|k02_corr','Pesquisa',true,'0');
  }else{
    if(document.form1.k04_codjm.value != ''){
      js_OpenJanelaIframe('top.corpo.iframe_tabrecregrasjm','db_iframe_tabrecjm','func_tabrecjm.php?pesquisa_chave='+document.form1.k04_codjm.value+'&funcao_js=top.corpo.iframe_tabrecregrasjm.js_mostratabrecjm','Pesquisa',false);
    }
  }
}
function js_mostratabrecjm(chave,erro){
  if(erro==true){
    document.form1.k04_codjm.focus();
    document.form1.k04_codjm.value = '';
    alert(chave);
  }else{
  document.form1.k02_corr.value = chave;
  }
}
function js_mostratabrecjm1(chave1,chave2){

  document.form1.k04_codjm.value = chave1;
  document.form1.k02_corr.value = chave2;
  top.corpo.iframe_tabrecregrasjm.db_iframe_tabrecjm.hide();
}
</script>