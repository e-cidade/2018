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
$cltaxagruporeg->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k06_descr");
$clrotulo->label("k07_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td> 
    	<?
		db_input('k08_taxagruporeg',5,'',true,'hidden','',"")
		?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk08_taxagrupo?>">
       <?
       db_ancora(@$Lk08_taxagrupo,"js_pesquisak08_taxagrupo(true);",$db_opcao);
       ?>
    </td>
    <td> 
    	<?
		db_input('k08_taxagrupo',5,$Ik08_taxagrupo,true,'text',$db_opcao," onchange='js_pesquisak08_taxagrupo(false);'")
		?>
    	<?
		db_input('k06_descr',50,$Ik06_descr,true,'text',3,'')
        ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk08_codsubrec?>">
       <?
       db_ancora(@$Lk08_codsubrec,"js_pesquisak08_codsubrec(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k08_codsubrec',5,$Ik08_codsubrec,true,'text',$db_opcao," onchange='js_pesquisak08_codsubrec(false);'")
?>
       <?
db_input('k07_descr',50,$Ik07_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td valign="top"  align="center" colspan=2>  
    <?
	 //"k08_taxagrupo"=>@$k08_taxagrupo,
	 $sql = " select * 
	             from taxagruporeg
	               inner join tabdesc on codsubrec  = k08_codsubrec 
                                   and k07_instit = ".db_getsession("DB_instit")."
              where k08_taxagrupo = $k08_taxagrupo ";
//   echo $sql."<br></br>"; 
	 $chavepri = array("k08_taxagruporeg"=>@$k08_taxagruporeg,"k08_taxagrupo"=>@$k08_taxagrupo,"k08_codsubrec"=>@$k08_codsubrec,"k07_descr"=>@$k07_descr);
//   print_r($chavepri); 
	 $cliframe_alterar_excluir->chavepri      = $chavepri;
	 $cliframe_alterar_excluir->opcoes        = 3;
	 $cliframe_alterar_excluir->sql           = $sql;
	 $cliframe_alterar_excluir->campos        = "k08_codsubrec,k07_descr";
	 $cliframe_alterar_excluir->legenda       = "Taxas do Grupo";
	 $cliframe_alterar_excluir->iframe_height = "160";
	 $cliframe_alterar_excluir->iframe_width  = "700";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
	</td>
  </tr>
  </table>
  </center>
  
<input 
     name  = "<?=($db_opcao==1?"incluir":"excluir")?>" 
	 type  = "submit" 
	 id    = "db_opcao" 
	 value = "<?=($db_opcao==1?"Incluir":"Excluir")?>" <?=($db_botao==false?"disabled":"")?> 
>

<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
<input name="novo" type="button" id="novoreg" value="Novo" onclick="js_novo();">
<input name="dbopcao" type="hidden" id="op" value="" >

</form>
<script>

function js_novo(){
//  alert("novo");
    js_limpa();
}

function js_limpa(){
//  alert("limpa");
	document.form1.k08_codsubrec.value = '';  
	document.form1.k07_descr.value     = '';  
	document.form1.dbopcao.value       = 1;
	document.form1.submit();
}

function js_pesquisak08_taxagrupo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_taxagrupo','func_taxagrupo.php?funcao_js=parent.js_mostrataxagrupo1|k06_taxagrupo|k06_descr','Pesquisa',true);
  }else{
     if(document.form1.k08_taxagrupo.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_taxagrupo','func_taxagrupo.php?pesquisa_chave='+document.form1.k08_taxagrupo.value+'&funcao_js=parent.js_mostrataxagrupo','Pesquisa',false);
     }else{
       document.form1.k06_descr.value = ''; 
     }
  }
}
function js_mostrataxagrupo(chave,erro){
  document.form1.k06_descr.value = chave; 
  if(erro==true){ 
    document.form1.k08_taxagrupo.focus(); 
    document.form1.k08_taxagrupo.value = ''; 
  }
}
function js_mostrataxagrupo1(chave1,chave2){
  document.form1.k08_taxagrupo.value = chave1;
  document.form1.k06_descr.value = chave2;
  db_iframe_taxagrupo.hide();
}
function js_pesquisak08_codsubrec(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tabdesc','func_tabdesc.php?funcao_js=parent.js_mostratabdesc1|codsubrec|k07_descr','Pesquisa',true);
  }else{
     if(document.form1.k08_codsubrec.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_tabdesc','func_tabdesc.php?pesquisa_chave='+document.form1.k08_codsubrec.value+'&funcao_js=parent.js_mostratabdesc','Pesquisa',false);
     }else{
       document.form1.k07_descr.value = ''; 
     }
  }
}
function js_mostratabdesc(chave,erro){
  document.form1.k07_descr.value = chave; 
  if(erro==true){ 
    document.form1.k08_codsubrec.focus(); 
    document.form1.k08_codsubrec.value = ''; 
  }
}
function js_mostratabdesc1(chave1,chave2){
  document.form1.k08_codsubrec.value = chave1;
  document.form1.k07_descr.value = chave2;
  db_iframe_tabdesc.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_taxagruporeg','func_taxagruporeg.php?funcao_js=parent.js_preenchepesquisa|k08_taxagruporeg','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_taxagruporeg.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>