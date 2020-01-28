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

//MODULO: cadastro
include("classes/db_db_usuarios_classe.php");
$cldb_usuarios = new cl_db_usuarios;
$cliptunaogeracarne->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
      if($db_opcao==1){
 	   $db_action="cad1_iptunaogeracarne004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="cad1_iptunaogeracarne005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="cad1_iptunaogeracarne006.php";
      }  
if ($db_opcao==1){
	$result_usu = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file(db_getsession("DB_id_usuario")));
	if ($cldb_usuarios->numrows>0){
		db_fieldsmemory($result_usu,0);
		$j66_usuario = $id_usuario; 
	}
  @$j66_data_dia = date("d",db_getsession("DB_datausu"));
	@$j66_data_mes = date("m",db_getsession("DB_datausu"));
	@$j66_data_ano = date("Y",db_getsession("DB_datausu"));
}
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj66_sequencial?>">
       <?=@$Lj66_sequencial?>
    </td>
    <td> 
<?
db_input('j66_sequencial',10,$Ij66_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj66_data?>">
       <?=@$Lj66_data?>
    </td>
    <td> 
<?
db_inputdata('j66_data',@$j66_data_dia,@$j66_data_mes,@$j66_data_ano,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj66_usuario?>">
       <?
       db_ancora(@$Lj66_usuario,"js_pesquisaj66_usuario(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('j66_usuario',10,$Ij66_usuario,true,'text',3," onchange='js_pesquisaj66_usuario(false);'");
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaj66_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_iptunaogeracarne','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.j66_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_iptunaogeracarne','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.j66_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.j66_usuario.focus(); 
    document.form1.j66_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.j66_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_iptunaogeracarne','db_iframe_iptunaogeracarne','func_iptunaogeracarne.php?funcao_js=parent.js_preenchepesquisa|j66_sequencial','Pesquisa',true,'0','1','775','390');
}
function js_preenchepesquisa(chave){
  db_iframe_iptunaogeracarne.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>