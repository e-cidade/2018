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

//MODULO: contabilidade
$clcongrupo->rotulo->label();
?>
<form name="form1" method="post" action="">
<table border="0" width="100%">
  <tr>
    <td nowrap width="20" height="50" title="<?=@$Tc20_descr?>"><b>
       <? db_ancora("Grupo","js_pesquisac20_sequencial(true);",$db_opcao); ?>
    </b></td>
    <td> 
<?
db_input("sequencial",    10,0,true,"hidden",3);
db_input("c21_codcon",    10,0,true,"hidden",3);
db_input("c21_anousu",     4,0,true,"hidden",3);
db_input('c20_sequencial',10,$Ic20_sequencial,true,'text',$db_opcao,"onChange='js_pesquisac20_sequencial(false);'");
db_input('c20_descr',50,$Ic20_descr,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<?
   if ($db_opcao != 1){
?>
      <input name="novo" id="novo" value="Novo" type="submit">
<?
   }
?>
    </td>
  </tr>
  </table>
<?
	$chavepri = array ("c20_sequencial"=>@$c20_sequencial,"c21_sequencial"=>@$c21_sequencial,"c21_codcon"=>$c21_codcon,"c21_anousu"=>$c21_anousu);
	$cliframe_alterar_excluir->chavepri      = $chavepri;
	$cliframe_alterar_excluir->sql           = $clconplanogrupo->sql_query(null,"c21_sequencial,c20_sequencial,c20_descr,c21_codcon,c21_anousu","c20_descr","c21_codcon = $c21_codcon and c21_anousu = $c21_anousu");
	$cliframe_alterar_excluir->campos        = "c21_sequencial,c20_descr";
	$cliframe_alterar_excluir->legenda       = "Grupos";
	$cliframe_alterar_excluir->iframe_height = "240";
	$cliframe_alterar_excluir->iframe_width  = "100%";
	$cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
?>
</form>
<script>
function js_pesquisac20_sequencial(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_grupos','db_iframe_grupo','func_congrupo.php?funcao_js=parent.js_mostracongrupo1|c20_sequencial|c20_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.c20_sequencial.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_grupos','db_iframe_grupo','func_congrupo.php?pesquisa_chave='+document.form1.c20_sequencial.value+'&funcao_js=parent.js_mostracongrupo','Pesquisa',false);
     }else{
       document.form1.c20_descr.value = ''; 
     }
  }
}
function js_mostracongrupo(chave,erro){
  document.form1.c20_descr.value = chave; 
  if(erro==true){ 
    document.form1.c20_sequencial.focus(); 
    document.form1.c20_sequencial.value = ''; 
  }
}
function js_mostracongrupo1(chave1,chave2){
  document.form1.c20_sequencial.value = chave1;
  document.form1.c20_descr.value      = chave2;
  db_iframe_grupo.hide();
}
</script>