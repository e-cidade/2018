<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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


?>
<script>
// --------------------
function valida_dados(){

   var op= document.createElement("input");
       op.setAttribute("type","hidden");
       op.setAttribute("name","<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>");
       op.setAttribute("value","");
       document.form1.appendChild(op);  
       document.form1.submit();

}
// --------------------
</script>
<form name="form1" method="post" action="">
<center>
<table border=0 >
<tr>
<td valign=top>
 <fieldset><legend><b>Dotação</b></legend>
 <table>
   <tr>
     <td nowrap title="<?=@$To47_coddot?>"> <? db_ancora(@$Lo47_coddot,"js_pesquisao47_coddot(true);",$op); ?> </td>
     <td><? db_input('o47_coddot',10,$Io47_coddot,true,'text',$op,"onchange='js_pesquisao47_coddot(false);'"); ?> </td>
     <td><input type="submit" name="pesquisa_dot"  value="pesquisar"> </td>
  </tr>
  <tr>
     <td nowrap title="<?=@$To58_orgao ?>">Orgão : </td>
     <td><? db_input('o58_orgao',10,"$Io58_orgao",true,'text',3,"");  ?> </td>
     <td colspan=3><? db_input('o40_descr',40,"",true,'text',3,"");  ?> </td>     
  </tr>
  <tr>
     <td nowrap title="<?=@$To58_elemento ?>" >Elemento  : </td>
     <td> <? db_input('o56_elemento',10,"",true,'text',3,"");  ?>  </td>
     <td> <? db_input('o56_descr',40,"",true,'text',3,"");  ?>       </td>
  </tr>
  <tr>
     <td nowrap title="<?=@$To58_codigo ?>" >Recurso  : </td>
     <td> <? db_input('o58_codigo',10,"",true,'text',3,"");  ?> </td>
     <td> <? db_input('o15_descr',40,"",true,'text',3,"");  ?> </td>
  </tr>     
  <tr>
    <td nowrap title="<?=@$To58_concarpeculiar?>">
       <?
       db_ancora(@$Lo58_concarpeculiar,"js_pesquisao58_concarpeculiar(true);",$db_opcao,"","o58_concarpeculiarancora");
       ?>
    </td>
    <td><? db_input('o58_concarpeculiar',10,$Io58_concarpeculiar,true,'text',$db_opcao,"onchange='js_pesquisao58_concarpeculiar(false);'");  ?></td>   
    <td><? db_input('c58_descr',40,$Ic58_descr,true,'text',3,''); ?></td>
  </tr>
  <tr>
    <td>Saldo </td>
    <td><? db_input('atual_menos_reservado',10,'',true,'text',3,'','','','text-align:right');  ?> </td>
  </tr>
  <tr>
    <td><b>Valor a Reduzir:</b></td>
    <td><? db_input('o47_valor',10,$Io47_valor,true,'text',1,'','','','text-align:right');  ?> </td>
    <td><input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
               type="button" id="db_opcao" 
               value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
               <?=($db_botao==false?"disabled":"")?>" onclick='valida_dados();'" >
     </td>

  </tr>
 </table>
 </fieldset>

</td>
<!-- segunda coluna -->
<td valign=top>
 <fieldset><legend><b>Projeto</b></legend>
 <table width=200px>
   <tr><td><b>Projeto</b></td><td><? db_input("o39_codproj",6,'',true,'text',3); ?></td></tr>
   <tr><td><b>Suplementação</b> </td><td><? db_input("o46_codsup",6,'',true,'text',3); ?></td></tr>
 </table>
 </fieldset>

 <fieldset><legend><b>Saldos</b></legend>
 <table width=200px>
   <tr><td><b>Total Reduzido</b></td><td><? db_input("soma_reduz",10,'',true,'text',3,'','','','text-align:right'); ?></td></tr>
 </table>
 </fieldset>

</td>
</tr>
</table>

<center>
<?  ///////// total reduzido
     /*
      $soma_reduz = $clorcsuplemval->sql_record ($clorcsuplemval->sql_query_file("","" ,"" ,"sum(o47_valor) as soma_red ","" ,"o47_codsup=$o47_codsup and o47_valor < 0"));  
      if ($soma_reduz){
          db_fieldsmemory($soma_reduz,0);
	  $soma_red *=-1;
          echo "<script> document.form1.total_reducao.value='$soma_red';  </script>";
      }	 
      // total suplementado 
       $soma_supl =$clorcsuplemval->sql_record ($clorcsuplemval->sql_query_file("","" ,"" ,"sum(o47_valor) as soma_supl ","" ,"o47_codsup=$o47_codsup and o47_valor >= 0"));  
       if ($soma_supl) {
          db_fieldsmemory($soma_supl,0);       
	  echo "<script>
	          document.form1.total_suplem.value='$soma_supl' ; 
		  if (parent.iframe_reduz.document.form1.total_suplem) {
		     parent.iframe_reduz.document.form1.total_suplem.value='$soma_supl';
		   } 		  
	        </script>";	  
	}
	*/
  ////////////////
  $clorcsuplemval = new cl_orcsuplemval;
  $chavepri= array("o47_anousu"=>$anousu,"o47_coddot"=>@$o47_coddot);
  $cliframe_alterar_excluir->chavepri=$chavepri;
  $cliframe_alterar_excluir->sql   =  $clorcsuplemval->sql_query_file("","" ,"" ,"fc_estruturaldotacao(o47_anousu,o47_coddot) as o50_estrutdespesa,*" ,"" ,"o47_codsup=$o46_codsup and o47_valor < 0");  
  $cliframe_alterar_excluir->campos  ="o47_anousu,o50_estrutdespesa,o47_coddot,o47_valor";
  $cliframe_alterar_excluir->legenda="lista";
  $cliframe_alterar_excluir->iframe_height ="200";
  $cliframe_alterar_excluir->opcoes = 3;
  $cliframe_alterar_excluir->iframe_alterar_excluir(1);

  ?>
</form>
</center>


<script>
function js_pesquisao47_coddot(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_orcdotacao','func_orcdotacao.php?funcao_js=parent.js_mostraorcdotacao1|o58_coddot','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('','db_iframe_orcdotacao','func_orcdotacao.php?pesquisa_chave='+document.form1.o47_coddot.value+'&funcao_js=parent.js_mostraorcdotacao','Pesquisa',false);
  }
}
function js_mostraorcdotacao(chave,erro){
  if(erro==true){ 
    document.form1.o47_coddot.focus(); 
    document.form1.o47_coddot.value = ''; 
  } else {
    document.form1.pesquisa_dot.click();
  }
}
function js_mostraorcdotacao1(chave1){
  document.form1.o47_coddot.value = chave1;
  db_iframe_orcdotacao.hide();
  document.form1.pesquisa_dot.click();
}

// --------------------------------------

function js_pesquisao47_coddoc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_suplem','db_iframe_conhistdoc','func_conhistdoc.php?funcao_js=parent.js_mostraconhistdoc1|c53_coddoc|c53_descr','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_suplem','db_iframe_conhistdoc','func_conhistdoc.php?pesquisa_chave='+document.form1.o47_coddoc.value+'&funcao_js=parent.js_mostraconhistdoc','Pesquisa',false);
  }
}
function js_mostraconhistdoc(chave,erro){
  document.form1.c53_descr.value = chave; 
  if(erro==true){ 
    document.form1.o47_coddoc.focus(); 
    document.form1.o47_coddoc.value = ''; 
  }
}
function js_mostraconhistdoc1(chave1,chave2){
  document.form1.o47_coddoc.value = chave1;
  document.form1.c53_descr.value = chave2;
  db_iframe_conhistdoc.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_suplem','db_iframe_orcsuplemval','func_orcsuplemval.php?funcao_js=parent.js_preenchepesquisa|o45_anousu|1|2','Pesquisa',true,0);
}
function js_preenchepesquisa(chave,chave1,chave2){
 db_iframe_orcsuplemval.hide();
}

function js_importa_suplementacao(){
   js_OpenJanelaIframe('top.corpo.iframe_suplem','db_iframe_orcsuplem_imp','func_orcsuplem_importa.php?funcao_js=parent.js_importa_suplementacao_01|o46_codsup','Pesquisa',true);
}
function js_importa_suplementacao_01(chave1,chave2){
  document.form1.codsup_imp.value = chave1;
  db_iframe_orcsuplem_imp.hide();
}
function js_pesquisao58_concarpeculiar(mostra){
  
  if(mostra==true){
    js_OpenJanelaIframe('',
                        'db_iframe_concarpeculiar',
                        'func_concarpeculiar.php?funcao_js=parent.js_mostraconcarpeculiar1|c58_sequencial|c58_descr',
                        'Pesquisa',
                        true);
  }else{
     if(document.form1.o58_concarpeculiar.value != ''){ 
         js_OpenJanelaIframe('',
                             'db_iframe_concarpeculiar',
                             'func_concarpeculiar.php?pesquisa_chave='+document.form1.o58_concarpeculiar.value.trim()+'&funcao_js=parent.js_mostraconcarpeculiar',
                             'Pesquisa',
                             false);
     }else{
       document.form1.c58_descr.value = ''; 
     }
  }
}

function js_mostraconcarpeculiar(chave,erro){
  document.form1.c58_descr.value = chave; 
  if(erro==true){ 
    document.form1.o58_concarpeculiar.focus(); 
    document.form1.o58_concarpeculiar.value = ''; 
  }
}

function js_mostraconcarpeculiar1(chave1,chave2){
  document.form1.o58_concarpeculiar.value =chave1; 
  document.form1.c58_descr.value = chave2; 
  db_iframe_concarpeculiar.hide();
}
</script>