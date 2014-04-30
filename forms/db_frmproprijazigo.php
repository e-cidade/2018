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

//MODULO: cemiterio
$clproprijazigo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cm28_i_codigo");
$clrotulo->label("cm28_i_proprietario");
$clrotulo->label("z01_nome");

$cm29_i_propricemit = $cm28_i_codigo;
$cm29_i_codigo=1;
?>
<form name="form1" method="post" action="">
<center>
<table border="0">

     <?
     db_input('cm29_i_codigo',10,$Icm29_i_codigo,true,'hidden',$db_opcao,"");
     db_input('cm29_i_propricemit',10,$Icm29_i_propricemit,true,'hidden',$db_opcao,"");
     ?>


<!--
  <tr>
    <td nowrap title="<?=@$Tcm29_i_codigo?>">
       <?=@$Lcm29_i_codigo?>
    </td>
    <td> 
     <?
     db_input('cm29_i_codigo',10,$Icm29_i_codigo,true,'text',3,"")
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm29_i_propricemit?>">
       <?
       db_ancora(@$Lcm29_i_propricemit,"js_pesquisacm29_i_propricemit(true);",$db_opcao);
       ?>
    </td>
    <td> 
     <?
     db_input('cm29_i_propricemit',10,$Icm29_i_propricemit,true,'text',$db_opcao," onchange='js_pesquisacm29_i_propricemit(false);'")
     ?>
     <?
     db_input('cm28_i_codigo',10,$Icm28_i_codigo,true,'text',3,'')
     ?>
    </td>
  </tr>
-->


  <tr>
     <td colspan=2 align="left">
     <tr>
         <td colspan=2 nowrap title="<?=@$Tcm28_i_proprietario?>">
            <?=@$Lcm28_i_proprietario?>
          <?
          db_input('cm28_i_proprietario',10,$Icm28_i_proprietario,true,'text',3,"")
          ?>
          <?
           db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
          ?>
         </td>
     </tr>
     </td>
  </tr>

  <tr>
    <td>
       <fieldset><legend><b>Termo de Compromisso</b></legend>
       <table>
       <tr>
         <td nowrap title="<?=@$Tcm29_i_termo?>">
            <?=@$Lcm29_i_termo?>
         </td>
         <td>
          <?
          db_input('cm29_i_termo',10,$Icm29_i_termo,true,'text',$db_opcao,"")
          ?>
         </td>
       </tr>
       <tr>
         <td nowrap title="<?=@$Tcm29_d_termo?>">
            <?=@$Lcm29_d_termo?>
         </td>
         <td>
          <?
          db_inputdata('cm29_d_termo',@$cm29_d_termo_dia,@$cm29_d_termo_mes,@$cm29_d_termo_ano,true,'text',$db_opcao,"")
          ?>
         </td>
       </tr>
       <!--
       <tr>
         <td nowrap title="<?=@$Tcm29_t_termo?>">
            <?=@$Lcm29_t_termo?>
         </td>
         <td>
          <?
          db_textarea('cm29_t_termo',0,0,$Icm29_t_termo,true,'text',$db_opcao,"")
          ?>
         </td>
       </tr>
       -->
       </table>
    </td>
    <td>
       <fieldset><legend><b>Carta de Concessão</b></legend>
       <table>
            <tr>
              <td nowrap title="<?=@$Tcm29_i_concessao?>">
                 <?=@$Lcm29_i_concessao?>
              </td>
              <td>
               <?
               db_input('cm29_i_concessao',10,$Icm29_i_concessao,true,'text',$db_opcao,"")
               ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tcm29_d_concessao?>">
                 <?=@$Lcm29_d_concessao?>
              </td>
              <td>
               <?
               db_inputdata('cm29_d_concessao',@$cm29_d_concessao_dia,@$cm29_d_concessao_mes,@$cm29_d_concessao_ano,true,'text',$db_opcao,"")
               ?>
              </td>
            </tr>
            <!--
            <tr>
              <td nowrap title="<?=@$Tcm29_t_concessao?>">
                 <?=@$Lcm29_t_concessao?>
              </td>
              <td>
               <?
               db_textarea('cm29_t_concessao',0,0,$Icm29_t_concessao,true,'text',$db_opcao,"")
               ?>
              </td>
            </tr>
            -->
       </table>
       </fieldset>
     </td>
  </tr>

  <tr>
    <td colspan=2>
       <fieldset><legend><b>Datas da Construção</b></legend>
       <table>
            <tr>
              <td nowrap title="<?=@$Tcm29_d_estrutura?>">
                 <?=@$Lcm29_d_estrutura?>
              </td>
              <td>
               <?
	       db_input('cm29_d_estrutura',20,$Icm29_d_estrutura,true,'text',$db_opcao,"")
               ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tcm29_d_base?>">
                 <?=@$Lcm29_d_base?>
              </td>
              <td>
               <?
	       db_input('cm29_d_base',20,$Icm29_d_base,true,'text',$db_opcao,"")
               ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tcm29_d_pronto?>">
                 <?=@$Lcm29_d_pronto?>
              </td>
              <td>
               <?
	       db_input('cm29_d_pronto',20,$Icm29_d_pronto,true,'text',$db_opcao,"")
               ?>
              </td>
            </tr>
       </table>
       </fieldset>
    </td>
  </tr>
  </table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<!--<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >-->
</form>
<script>
function js_pesquisacm29_i_propricemit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_propricemit','func_propricemit.php?funcao_js=parent.js_mostrapropricemit1|cm28_i_codigo|cm28_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.cm29_i_propricemit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_propricemit','func_propricemit.php?pesquisa_chave='+document.form1.cm29_i_propricemit.value+'&funcao_js=parent.js_mostrapropricemit','Pesquisa',false);
     }else{
       document.form1.cm28_i_codigo.value = ''; 
     }
  }
}
function js_mostrapropricemit(chave,erro){
  document.form1.cm28_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.cm29_i_propricemit.focus(); 
    document.form1.cm29_i_propricemit.value = ''; 
  }
}
function js_mostrapropricemit1(chave1,chave2){
  document.form1.cm29_i_propricemit.value = chave1;
  document.form1.cm28_i_codigo.value = chave2;
  db_iframe_propricemit.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_proprijazigo','func_proprijazigo.php?funcao_js=parent.js_preenchepesquisa|cm29_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_proprijazigo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>