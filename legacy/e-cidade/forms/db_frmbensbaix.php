<?
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

//MODULO: patrim
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clbensbaix->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('t51_descr');
?>
<fieldset>
<legend><b>Baixa de Bens</b></legend>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tt55_codbem?>">
       <?=@$Lt55_codbem?>
    </td>
    <td> 
<?
db_input('t55_codbem',8,$It55_codbem,true,'text',3,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt55_baixa?>">
       <?=@$Lt55_baixa?>
    </td>
    <td> 
<?
db_inputdata('t55_baixa',@$t55_baixa_dia,@$t55_baixa_mes,@$t55_baixa_ano,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt55_motivo?>">
<?
db_ancora(@$Lt55_motivo,"js_pesquisat55_motivo(true)",$db_opcao);
?>
    </td>
    <td> 
<?
db_input('t55_motivo',8,$It55_motivo,true,'text',$db_opcao,"onchange = 'js_pesquisat55_motivo(false)'");
?>
<?
db_input('t51_descr',40,$It51_descr,true,'text',3,"js_pesquisat55_motivo(false)");
?>
    </td>
  </tr>
  <tr>
     <td nowrap title="<?=$Tt55_obs?>"><?=$Lt55_obs?></td>
     <td nowrap>
     <?
        db_textarea("t55_obs",5,80,$It55_obs,true,"text",$db_opcao);
     ?>
     </td>
  </tr>
</table>
</fieldset>

<table>
  <tr>
    <td colspan="2" align="center">
<input name="<?=($db_opcao==1?"incluir":"excluir")?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Anular Bem":"Reativar Bem")?>" >
    </td>
  </tr>
</table>
  </center>
</form>

<script>
function js_pesquisat55_motivo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_bensbaix','db_iframe_bensmotbaixa','func_bensmotbaixa.php?funcao_js=parent.js_mostramotivo1|t51_motivo|t51_descr','Pesquisa',true);
  }else{
    if(document.form1.t55_motivo.value != ''){
      js_OpenJanelaIframe('top.corpo.iframe_bensbaix','db_iframe_bensmotbaixa','func_bensmotbaixa.php?pesquisa_chave='+document.form1.t55_motivo.value+'&funcao_js=parent.js_mostramotivo','Pesquisa',false);
    }else{
      document.form1.t51_descr.value = '';
    }
  }
}
function js_mostramotivo(chave,erro){
  document.form1.t51_descr.value = chave;
  if(erro==true){
    document.form1.t55_motivo.focus();
    document.form1.t55_motivo.value = '';
  }
}
function js_mostramotivo1(chave1,chave2){
  document.form1.t55_motivo.value = chave1;
  document.form1.t51_descr.value = chave2;
  db_iframe_bensmotbaixa.hide();
}
</script>