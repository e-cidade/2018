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

//MODULO: Farmácia
$clfar_class->rotulo->label();
if($db_opcao==1){
    $ac="Far1_far_class001.php";
}else if($db_opcao==22 || $db_opcao==2){
    $ac="Far1_far_class002.php";
}else if($db_opcao==33 || $db_opcao==3){
    $ac="Far1_far_class003.php";
}
$result_fa02_i_codigo = $cl_far_parametros->sql_record($cl_far_parametros->sql_query_file(null,"fa02_i_dbestrutura"));
if($cl_far_parametros->numrows>0){
  db_fieldsmemory($result_fa02_i_codigo,0);
}
$tipo = $db_opcao==1?"Inclusão":($db_opcao==2||$db_opcao==22?"Alteração":"Exclusão");
?>
<form name="form1" method="post" action="">
<center>
<fieldset style="width:50%"><legend><b><?=@$tipo?> de Classificação</b></legend>
<table border="0">
  <tr>
    <td height="18">&nbsp;</td>
    <td height="18">&nbsp;</td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tfa05_i_codigo?>">
       <?=@$Lfa05_i_codigo?>
    </td>
    <td> 
<?
db_input('fa05_i_codigo',10,$Ifa05_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  
  <?

   if(isset($estrutura_altera) || isset($chavepesquisa) && isset($fa05_c_class)){
     if(empty($estrutura_altera)){
       $estrutura_altera=$fa05_c_class;
     }
      db_input('estrutura_altera',4,$Ifa05_i_codigo,true,'hidden',3);
   }
   
   //if(isset($db_atualizar) && (empty($estrutura_altera) || (isset($estrutura_altera) && str_replace(".","",$t64_class) != $estrutura_altera))){ 
   //  $cldb_estrut->db_estrut_inclusao($t64_class,$mascara,"clabens","t64_class","t64_analitica");
   //  if($cldb_estrut->erro_status==0){
   //    $err_estrutural = $cldb_estrut->erro_msg;
   //  }else{
   //    $focar=true;
   //  }
  // }
   
   $cldb_estrut->autocompletar = true;
   $cldb_estrut->mascara = true;
   $cldb_estrut->reload  = true;
   $cldb_estrut->input   = false;
   $cldb_estrut->size    = 10;
   $cldb_estrut->nome    = "fa05_c_class";
   $cldb_estrut->db_opcao= $db_opcao==1?1:3;
   $cldb_estrut->db_mascara("$fa02_i_dbestrutura");
?>
  <tr>
    <td nowrap title="<?=@$Tfa05_c_tipo?>">
       <?=@$Lfa05_c_tipo?>
    </td>
    <td>
          <?
          $sex = array("S"=>"Sintético","A"=>"Analítico");
          db_select('fa05_c_tipo',$sex,true,$db_opcao);
          ?>
          </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa05_c_descr?>">
       <?=@$Lfa05_c_descr?>
    </td>
    <td> 
<?
db_input('fa05_c_descr',50,$Ifa05_c_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
    <td nowrap title="<?=@$Tfa05_t_obs?>">
       <?=@$Lfa05_t_obs?>
    </td>
    <td> 
<?
db_textarea('fa05_t_obs',1,47,$Ifa05_t_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  
  </table>
	</fieldset>
  </center>
<table border="0">
  <tr>
    <td height="18">&nbsp;</td>
    <td height="18">&nbsp;</td>
  </tr>
  <tr>
  <td>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </td>

  </tr>
  </table>
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_far_class','func_far_class.php?funcao_js=parent.js_preenchepesquisa|fa05_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_far_class.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}


</script>
<?
if(isset($focar)){
    echo "<script>
    document.form1.fa05_c_descr.focus();
    </script>";
}
if(isset($err_estrutural)){
    db_msgbox($err_estrutural);
    echo "<script> document.form1.fa05_i_codigo.style.backgroundColor='#99A9AE';</script>";
}

?>