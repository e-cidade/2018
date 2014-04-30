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

//MODULO: custos
$clparcustos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");
$clrotulo->label("db77_descr");
?>
<form name="form1" method="post" action="">
<fieldset>
<legend> <b>Manutenção de Parâmetros:</b> </legend>
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcc09_anousu?>">
       <?=@$Lcc09_anousu?>
    </td>
    <td> 
		<?
          $cc09_anousu = db_getsession('DB_anousu');
    	  db_input('cc09_anousu',10,$Icc09_anousu,true,'text',"3","")
		?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcc09_instit?>">
       <?=@$Lcc09_instit?>
    </td>
    <td> 
       <?
         db_input('cc09_instit',10,$Icc09_instit,true,'text',3,"")
       ?>
       <?
         db_input('nomeinst',50,$Inomeinst,true,'text',3,'')
       ?>
    </td>
    </tr>
    <tr>
    <td nowrap title="<?=@$Tcc09_mascaracustoplano?>">
       <?
         db_ancora(@$Lcc09_mascaracustoplano,"js_pesquisacc09_mascaracustoplano(true);",$db_opcao);
       ?>
    </td>
    <td> 
       <?
         db_input('cc09_mascaracustoplano',10,$Icc09_mascaracustoplano,true,'text',$db_opcao,
         " onchange='js_pesquisacc09_mascaracustoplano(false);'")
       ?>
       <?
         db_input('db77_descr',50,$Idb77_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
     <td nowrap title="<?=@$Tcc09_tipocontrole?>">
      <?=$Lcc09_tipocontrole?>
    </td>
    <td>
      <?
       db_select("cc09_tipocontrole", getValoresPadroesCampo("cc09_tipocontrole"),true,1);
      ?>
    </td>
  </tr>
  </table>
  </center>
</fieldset>

<center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit"
       id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
       <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</center>

</form>
<script>
function js_pesquisacc09_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config',
                        'func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst',
                        'Pesquisa',true);
  }else{
     if(document.form1.cc09_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config',
                            'func_db_config.php?pesquisa_chave='
                            +document.form1.cc09_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.cc09_instit.focus(); 
    document.form1.cc09_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.cc09_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisacc09_mascaracustoplano(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_estrutura',
                        'func_db_estrutura.php?funcao_js=parent.js_mostradb_estrutura1|db77_codestrut|db77_descr',
                        'Pesquisa',true);
  }else{
     if(document.form1.cc09_mascaracustoplano.value != ''){ 
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_db_estrutura',
                            'func_db_estrutura.php?pesquisa_chave='
                             +document.form1.cc09_mascaracustoplano.value+'&funcao_js=parent.js_mostradb_estrutura',
                             'Pesquisa',false);
     }else{
       document.form1.db77_descr.value = ''; 
     }
  }
}
function js_mostradb_estrutura(chave,erro){
  document.form1.db77_descr.value = chave; 
  if(erro==true){ 
    document.form1.cc09_mascaracustoplano.focus(); 
    document.form1.cc09_mascaracustoplano.value = ''; 
  }
}
function js_mostradb_estrutura1(chave1,chave2){
  document.form1.cc09_mascaracustoplano.value = chave1;
  document.form1.db77_descr.value = chave2;
  db_iframe_db_estrutura.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_parcustos',
                      'func_parcustos.php?funcao_js=parent.js_preenchepesquisa|cc09_anousu',
                      'Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_parcustos.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>