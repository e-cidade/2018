<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
$clempparametro->rotulo->label();
?>
<form name="form1" method="post" action="">
<fieldset>
<legend><b>Manutenção de Parâmetros<b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Te39_anousu?>">
       <?=@$Le39_anousu?>
    </td>
    <td> 
<?
$e39_anousu = db_getsession('DB_anousu');
db_input('e39_anousu',4,$Ie39_anousu,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te60_codemp?>">
       <?=@$Le60_codemp?>
    </td>
    <td> 
<?
db_input('e30_codemp',15,$Ie30_codemp,true,'text',$db_opcao,"")
?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Te30_nroviaaut?>">
       <?=@$Le30_nroviaaut?>
    </td>
    <td> 
<?
db_input('e30_nroviaaut',15,$Ie30_nroviaaut,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te30_nroviaemp?>">
       <?=@$Le30_nroviaemp?>
    </td>
    <td> 
     <? db_input('e30_nroviaemp',15,$Ie30_nroviaemp,true,'text',$db_opcao,"") ?>
    </td>
  </tr>
   <tr>
    <td nowrap title="<?=@$Te30_nroviaord ?>"><?=@$Le30_nroviaord?></td>
    <td><? db_input('e30_nroviaord',15,$Ie30_nroviaord,true,'text',$db_opcao,"") ?> </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Te30_numdec?>">
       <?=@$Le30_numdec?>
    </td>
    <td> 
      <? db_input('e30_numdec',15,$Ie30_numdec,true,'text',$db_opcao,"") ?>
    </td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Te30_liberaempenho ?>">
      <?=@$Le30_liberaempenho ?> 
    </td>
    <td><?
           $matriz = array("f"=>"nao","t"=>"sim");             
           db_select("e30_liberaempenho",$matriz,true,1); 
        ?>     
    </td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Te30_autimportahist ?>"><?=@$Le30_autimportahist ?> </td>
    <td><?
           $matriz = array("f"=>"nao","t"=>"sim");             
           db_select("e30_autimportahist",$matriz,true,1); 
        ?>     
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Te30_trazobsultop?>">       <?=@$Le30_trazobsultop ?>    </td>
    <td><? $matriz = array("1"=>"Não Trazer","2"=>"Trazer Geral","3"=>"Trazer por usuário");             
           db_select("e30_trazobsultop", $matriz,true,1); 
        ?> 
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Te30_opimportaresumo?>">       <?=@$Le30_opimportaresumo ?>    </td>
    <td><? $matriz = array("f"=>"nao","t"=>"sim");             
           db_select("e30_opimportaresumo", $matriz,true,1); 
        ?> 
    </td>
  </tr>
  
  
  <tr>
    <td nowrap title="<?=@$Te30_empdataserv?>"><?=@$Le30_empdataserv ?></td>
    <td><? $matriz = array("f"=>"nao","t"=>"sim");             
           db_select("e30_empdataserv", $matriz,true,1); 
        ?> 
    </td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Te30_empdataemp?>"><?=@$Le30_empdataemp ?></td>
    <td><? $matriz = array("f"=>"nao","t"=>"sim");             
           db_select("e30_empdataemp", $matriz,true,1); 
        ?> 
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te30_formvisuitemaut?>"><?=@$Le30_formvisuitemaut?></td>
    <td><? $matriz = array("1"=>"Por elemento","2"=>"Por Desdobramento","3"=>"Nenhum");             
           db_select("e30_formvisuitemaut", $matriz,true,1); 
        ?> 
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Te30_verificarmatordem?>"><?=@$Le30_verificarmatordem?></td>
    <td><? $matriz = array("0"=>"sim","1"=>"nao");             
           db_select("e30_verificarmatordem", $matriz,true,1); 
        ?> 
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te30_notaliquidacao?>"><?=@$Le30_notaliquidacao?></td>
    <td>
      <?
         if (isset($e30_notaliquidacao) && $e30_notaliquidacao != '') {
           $dtnota = explode("-",$e30_notaliquidacao);
           $e30_notaliquidacao_dia 	= $dtnota[2];
           $e30_notaliquidacao_mes 	= $dtnota[1];
           $e30_notaliquidacao_ano 	= $dtnota[0];
         }
         db_inputdata("e30_notaliquidacao",@$e30_notaliquidacao_dia,@$e30_notaliquidacao_mes,@$e30_notaliquidacao_ano, true,"text",1); 
        ?> 
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te30_retencaomesanterior?>"><?=@$Le30_retencaomesanterior?></td>
    <td><? $matriz = getValoresPadroesCampo("e30_retencaomesanterior");             
           db_select("e30_retencaomesanterior", $matriz,true,1); 
        ?> 
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te30_usadataagenda?>">       <?=@$Le30_usadataagenda?>    </td>
    <td><? $matriz = array("f"=>"nao","t"=>"sim");             	
           db_select("e30_usadataagenda", $matriz,true,1); 
        ?> 
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te30_impobslicempenho?>">       <?=@$Le30_impobslicempenho?>    </td>
    <td>
    	<? $matriz = array("t"=>"sim", "f"=>"nao");             	
           db_select("e30_impobslicempenho", $matriz,true,1); 
        ?> 
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te30_dadosbancoempenho?>">       <?=@$Le30_dadosbancoempenho?>    </td>
    <td>
      <? 
        $aDadosBancoEmpenho = array("f"=>"não", "t"=>"sim");               
        db_select("e30_dadosbancoempenho", $aDadosBancoEmpenho, true, 1); 
      ?> 
    </td>
  </tr>
</table>
</fieldset>
<center>
<table>
 <tr>
  <td>&nbsp;</td>
 </tr>
 <tr>
  <td>
    <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </td>
 </tr>
</table>
</center>
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_empparametro','func_empparametro.php?funcao_js=parent.js_preenchepesquisa|e39_anousu','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_empparametro.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>