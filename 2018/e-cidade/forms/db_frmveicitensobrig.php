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

$clveicitensobrig->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("ve08_descr");

if (!isset($ve09_usuario)){
  $ve09_usuario = db_getsession("DB_id_usuario");
}
?>
<form name="form1" method="post" action="">
<?
   db_input("ve09_veiculos",10,0,true,"hidden",3);
   db_input("sequencial",   10,0,true,"hidden",3);
   db_input("ve09_usuario", 10,0,true,"hidden",3);
?>
<center>
<table border="0" width="790">
  <tr>
    <td nowrap align="right" title="<?=@$Tve09_veiccaditensobrig?>">
    <? 
       db_ancora(@$Lve09_veiccaditensobrig,"js_pesquisave09_veiccaditensobrig(true);",$db_opcao) 
    ?>
    </td>
    <td nowrap width="20">
    <? 
       db_input('ve09_veiccaditensobrig',10,$Ive09_veiccaditensobrig,true,'text',$db_opcao,"onChange='js_pesquisave09_veiccaditensobrig(false);'"); 
    ?>
    </td>
    <td nowrap>
    <? 
       db_input('ve08_descr',40,0,true,'text',3); 
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap colspan="3" height="50" align="center"> 
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==3||$db_opcao==33?"excluir":""))?>" 
          type="submit" id="db_opcao" 
          value="<?=($db_opcao==1?"Incluir":($db_opcao==3||$db_opcao==33?"Excluir":""))?>"
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
	     $chavepri = array ("ve09_sequencial"=>@$ve09_sequencial);
       $cliframe_alterar_excluir->chavepri = $chavepri;
       $cliframe_alterar_excluir->sql = $clveicitensobrig->sql_query_obrigatorio(null,"distinct on (ve08_descr) ve09_sequencial,ve09_veiccaditensobrig,ve08_descr,case when ve10_veicitensobrig is not null then 'BAIXADO' else 'NÃO BAIXADO' end as ve10_veicitensobrig",null,"ve09_veiculos = $ve09_veiculos");
       $cliframe_alterar_excluir->campos = "ve09_sequencial,ve09_veiccaditensobrig,ve08_descr,ve10_veicitensobrig";
       $cliframe_alterar_excluir->legenda = "Itens";
	     $cliframe_alterar_excluir->iframe_height = "240";
       $cliframe_alterar_excluir->iframe_width = "100%";
       $cliframe_alterar_excluir->opcoes = 3;
	     $cliframe_alterar_excluir->iframe_alterar_excluir(1);
    ?>
    </td>
  </tr>
</table>
</center>
</form>
<script>
function js_pesquisave09_veiccaditensobrig(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_veicitensobrig','db_iframe_veiccaditensobrig','func_veiccaditensobrig.php?funcao_js=parent.js_mostraveiccaditensobrig1|ve08_sequencial|ve08_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.ve09_veiccaditensobrig.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_veicitensobrig','db_iframe_veiccaditensobrig','func_veiccaditensobrig.php?pesquisa_chave='+document.form1.ve09_veiccaditensobrig.value+'&funcao_js=parent.js_mostraveiccaditensobrig','Pesquisa',false);
     }else{
       document.form1.ve08_descr.value = ''; 
     }
  }
}
function js_mostraveiccaditensobrig(chave,erro){
  if (erro==false){
    document.form1.ve08_descr.value = chave; 
  }

  if(erro==true){ 
    document.form1.ve09_veiccaditensobrig.focus(); 
    document.form1.ve09_veiccaditensobrig.value = ""; 
  }
}
function js_mostraveiccaditensobrig1(chave1,chave2){
  document.form1.ve09_veiccaditensobrig.value = chave1;
  document.form1.ve08_descr.value             = chave2;

  db_iframe_veiccaditensobrig.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_conplano','func_conplanogeral.php?funcao_js=parent.js_preenchepesquisa|c60_codcon','Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_conplano.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}
</script>