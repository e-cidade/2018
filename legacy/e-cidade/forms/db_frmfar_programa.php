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

//MODULO: Farm�cia
$clfar_programa->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>

<table border="0">
	<tr>
    <td height="18">&nbsp;</td>
    <td height="18">&nbsp;</td>
  </tr>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tfa12_i_codigo?>">
       <?=@$Lfa12_i_codigo?>
    </td>
    <td> 
<?
db_input('fa12_i_codigo',5,$Ifa12_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa12_c_descricao?>">
       <?=@$Lfa12_c_descricao?>
    </td>
    <td> 
<?
db_input('fa12_c_descricao',40,$Ifa12_c_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa12_c_depadmin?>">
       <?=@$Lfa12_c_depadmin?>
    </td>
    <td> 
     <?
      $y = array("0"=>"","1"=>"Federal","2"=>"Estadual","3"=>"Municipal","4"=>"Particular","5"=>"Outros");
      db_select('fa12_c_depadmin',$y,true,$db_opcao,"");
      ?>
    </td>
  </tr>
    <tr>
    <td nowrap title="<?=@$Tfa12_i_tipoacao?>">
       <?=@$Lfa12_i_tipoacao?>
    </td>
    <td>
     <?
      $sql="select s148_i_codigo,s148_c_sigla,s148_c_descr from sau_tipoacaoprog";
      $result=pg_query($sql);
      $linhas=pg_num_rows($result);
      $vet= array("0"=>"");
      for($x=0;$x<$linhas;$x++){
          db_fieldsmemory($result,$x);
          $vet[$s148_i_codigo] = converteCodificacao("$s148_c_sigla - $s148_c_descr");
      }
      db_select('fa12_i_tipoacao',$vet,true,$db_opcao,"");
      ?>
    </td>
  </tr>
  </table>

<table border="0">
  <tr>
  <td>
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </td>
  </tr>
  </table>
    </center>
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_far_programa','func_far_programa.php?funcao_js=parent.js_preenchepesquisa|fa12_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_far_programa.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>