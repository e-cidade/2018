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

$clveicutilizacao->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("ve14_descr");
$clrotulo->label("ve16_bens");
$clrotulo->label("t52_descr");
$clrotulo->label("ve19_veiccadconvenio");
$clrotulo->label("ve17_descr");
?>
<form name="form1" method="post" action="">
<?
   db_input("ve15_veiculos",10,0,true,"hidden",3);
   db_input("sequencial",   10,0,true,"hidden",3);
?>
<center>
<table border="0" width="790">
  <tr>
    <td nowrap align="right" title="<?=@$Tve15_veiccadutilizacao?>">
    <? 
       db_ancora(@$Lve15_veiccadutilizacao,"js_pesquisave15_veiccadutilizacao(true);",$db_opcao) 
    ?>
    </td>
    <td nowrap width="20">
    <? 
       db_input('ve15_veiccadutilizacao',10,$Ive15_veiccadutilizacao,true,'text',$db_opcao,"onChange='js_pesquisave15_veiccadutilizacao(false);'"); 
    ?>
    </td>
    <td nowrap>
    <? 
       db_input('ve14_descr',40,0,true,'text',3); 
    ?>
    </td>
  </tr>
  <tr id="bens" style="display:none;">
    <td nowrap align="right" title="<?=@$Tv16_bens?>">
    <?
       db_ancora(@$Lve16_bens,"js_pesquisave16_bens(true);",$db_opcao);
    ?>
    </td>
    <td nowrap>
    <?
       db_input('ve16_bens',10,$Ive16_bens,true,'text',$db_opcao,"onChange='js_pesquisave16_bens(false);'"); 
    ?>
    </td>
    <td nowrap>
    <? 
       db_input('t52_descr',40,0,true,'text',3); 
    ?>
    </td>
  </tr>
  <tr id="convenio" style="display:none;">
    <td nowrap align="right" title="<?=@$Tv19_veiccadconvenio?>">
    <?
       db_ancora(@$Lve19_veiccadconvenio,"js_pesquisave19_veiccadconvenio(true);",$db_opcao);
    ?>
    </td>
    <td nowrap>
    <?
       db_input('ve19_veiccadconvenio',10,$Ive19_veiccadconvenio,true,'text',$db_opcao,"onChange='js_pesquisave19_veiccadconvenio(false);'"); 
    ?>
    </td>
    <td nowrap>
    <? 
       db_input('ve17_descr',40,0,true,'text',3); 
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap colspan="3" height="50" align="center"> 
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
          type="submit" id="db_opcao" 
          value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
          <?=($db_botao==false?"disabled":"")?> 
       >
       <?
          if ($db_opcao != 1) {
       ?>
         <input name="novo" id="novo" type="submit" value="Novo">
       <?
          }
       ?>
    </td>
  </tr>	
  <tr>
    <td nowrap colspan="3">
    <?
	     $chavepri = array ("ve15_sequencial"=>@$ve15_sequencial);
       $cliframe_alterar_excluir->chavepri = $chavepri;
       $cliframe_alterar_excluir->sql = $clveicutilizacao->sql_query_uso(null,"distinct ve15_sequencial,ve15_veiccadutilizacao,ve14_descr,t52_descr,ve17_descr",null,"ve15_veiculos = $ve15_veiculos");
       $cliframe_alterar_excluir->campos = "ve15_sequencial,ve15_veiccadutilizacao,ve14_descr,t52_descr,ve17_descr";
       $cliframe_alterar_excluir->legenda = "Utilizacao";
	     $cliframe_alterar_excluir->iframe_height = "240";
       $cliframe_alterar_excluir->iframe_width = "100%";
	     $cliframe_alterar_excluir->iframe_alterar_excluir(1);
    ?>
    </td>
  </tr>
</table>
</center>
</form>
<script>
function js_pesquisave15_veiccadutilizacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_veicutilizacao','db_iframe_veiccadutilizacao','func_veiccadutilizacao.php?funcao_js=parent.js_mostraveiccadutilizacao1|ve14_sequencial|ve14_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.ve15_veiccadutilizacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_veicutilizacao','db_iframe_veiccadutilizacao','func_veiccadutilizacao.php?pesquisa_chave='+document.form1.ve15_veiccadutilizacao.value+'&funcao_js=parent.js_mostraveiccadutilizacao','Pesquisa',false);
     }else{
       document.form1.ve14_descr.value = ''; 
     }
  }

  var obj = document.getElementById("bens");
  if (document.form1.ve15_veiccadutilizacao.value == 1){
    obj.style.display = "";
  } else {
    document.form1.ve16_bens.value = "";
    document.form1.t52_descr.value = "";
    obj.style.display = "none";
  }

  var obj = document.getElementById("convenio");
  if (document.form1.ve15_veiccadutilizacao.value == 2){
    obj.style.display = "";
  } else {
    document.form1.ve19_veiccadconvenio.value = "";
    document.form1.ve17_descr.value           = "";
    obj.style.display = "none";
  }
}
function js_mostraveiccadutilizacao(chave,erro){
  if (erro==false){
    document.form1.ve14_descr.value = chave; 
  }

  if(erro==true){ 
    document.form1.ve15_veiccadutilizacao.focus(); 
    document.form1.ve09_veiccadutilizacao.value = ""; 
  }
}
function js_mostraveiccadutilizacao1(chave1,chave2){
  document.form1.ve15_veiccadutilizacao.value = chave1;
  document.form1.ve14_descr.value             = chave2;
  db_iframe_veiccadutilizacao.hide();
  var obj = document.getElementById("convenio" );
  obj.style.display = "none";
  var obj = document.getElementById("bens" );
    obj.style.display = "none";
    if (chave1==2){
        var obj = document.getElementById("convenio" );
            obj.style.display = "";
    }
    if (chave1==1){
         var obj = document.getElementById("bens" );
              obj.style.display = "";
       
    }

}
function js_pesquisave16_bens(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_veicutilizacao','db_iframe_bens','func_bens.php?funcao_js=parent.js_mostraveicutilizacaobens1|t52_bem|t52_descr','Pesquisa',true,'0');
  }else{
    if(document.form1.ve16_bens.value != ""){
        js_OpenJanelaIframe('top.corpo.iframe_veicutilizacao','db_iframe_bens','func_bens.php?pesquisa_chave='+document.form1.ve16_bens.value+'&funcao_js=parent.js_mostraveicutilizacaobens','Pesquisa',false);
    } else {
      document.form1.t52_descr.value = "";
    }
  }
}
function js_mostraveicutilizacaobens1(chave1,chave2){
  document.form1.ve16_bens.value = chave1;
  document.form1.t52_descr.value = chave2;

  db_iframe_bens.hide();
}
function js_mostraveicutilizacaobens(chave,erro){
  if (erro==false){
    document.form1.t52_descr.value = chave; 
  }

  if(erro==true){ 
    document.form1.ve16_bens.focus(); 
    document.form1.ve16_bens.value = ""; 
  }
}
function js_pesquisave19_veiccadconvenio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_veicutilizacao','db_iframe_veiccadconvenio','func_veiccadconvenio.php?funcao_js=parent.js_mostraveiccadconvenio1|ve17_sequencial|ve17_descr','Pesquisa',true,'0');
  }else{
    if(document.form1.ve19_veiccadconvenio.value != ""){
        js_OpenJanelaIframe('top.corpo.iframe_veicutilizacao','db_iframe_veiccadconvenio','func_veiccadconvenio.php?pesquisa_chave='+document.form1.ve19_veiccadconvenio.value+'&funcao_js=parent.js_mostraveiccadconvenio','Pesquisa',false);
    } else {
      document.form1.ve17_descr.value = "";
    }
  }
}
function js_mostraveiccadconvenio1(chave1,chave2){
  document.form1.ve19_veiccadconvenio.value = chave1;
  document.form1.ve17_descr.value           = chave2;

  db_iframe_veiccadconvenio.hide();
}
function js_mostraveiccadconvenio(chave,erro){
  if (erro==false){
    document.form1.ve17_descr.value = chave; 
  }

  if(erro==true){ 
    document.form1.ve19_veiccadconvenio.focus(); 
    document.form1.ve19_veiccadconvenio.value = ""; 
  }
}

js_pesquisave15_veiccadutilizacao(false);
</script>