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

include("classes/db_db_config_classe.php");
include("classes/db_pctipocompra_classe.php");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cldb_config = new cl_db_config;
$clpctipocompra = new cl_pctipocompra;
$clcflicita->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("");
if(isset($opcao) && $opcao=="alterar"){
  $db_opcao = 2;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
  $db_opcao = 3;
}else{  
  $db_opcao = 1;
} 

?>
<form name="form1" method="post" action="<?=basename($_SERVER['PHP_SELF'])?>" >
      <table border="0" cellspacing="1" cellpadding="1">
	<tr>
	  <td nowrap title="<?=@$Tl03_codigo?>">
	     <b>Código:</b> 
	  </td>
	  <td nowrap title="<?=@$Tl03_codigo?>">
         <?
         db_input('l03_codigo',10,$Il03_codigo,true,'text',3,"");
	     ?>
	  
	     <b>Tipo:</b> 
	  
         <?
         db_input('l03_tipo',4,$Il03_tipo,true,'text',$db_opcao,"");
	     ?>
	  </td>
	  </tr>
	  <tr>
	  <td nowrap title="<?=@$Tl03_descr?>">
	     <b>Descrição:</b> 
	  </td>
	  <td nowrap title="<?=@$Tl03_descr?>">
         <?
         db_input('l03_descr',50,$Il03_descr,true,'text',$db_opcao,"");
	     ?>
	  </td>
	  
	  </tr>
	  <tr>
	  <td nowrap title="<?=@$Tl03_codcom?>">
	  <b>Tipo de Compra:</b>
	  </td>
	  <td nowrap title="<?=@$Tl03_codcom?>">
	  <?
	  $result_tipo=$clpctipocompra->sql_record($clpctipocompra->sql_query_file());
      if (isset($l03_codcom)&&$l03_codcom!=""){
        	echo "<script>document.form1.l03_codcom.selected=$l03_codcom;</script>";
      }
      db_selectrecord("l03_codcom",$result_tipo,true,$db_opcao,"");
      ?>
      </td>
      
      	  </tr>
      	  <tr>
	  <td nowrap title="<?=@$Tl03_instit?>">
	  <b>Instituição:</b>
	  </td>
	  <td nowrap title="<?=@$Tl03_instit?>">
	  <?
	  $l03_instit=db_getsession("DB_instit");
	  $result_instit=$cldb_config->sql_record($cldb_config->sql_query_file());
      if (isset($l03_instit)&&$l03_instit!=""){
        	echo "<script>document.form1.l03_instit.selected=$l03_instit;</script>";
      }
      db_selectrecord("l03_instit",$result_instit,true,3,"");
      ?>
      </td>
      
      	  </tr>
	<tr>
	<td colspan=2 align='center'>
	      <input name=<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir")) ?> type="submit" id="db_opcao" value= <?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir")) ?> <?=($db_botao==false?"disabled":"") ?> >
        </td>
      </tr>
      </table>
      <table>
  <tr>
    <td valign="top"> 
    <?
     $chavepri= array("l03_codigo"=>@$l03_codigo,"l03_descr"=>@$l03_descr,"l03_tipo"=>@$l03_tipo,"l03_codcom"=>@$l03_codcom,"l03_instit"=>@$l03_instit);
     $cliframe_alterar_excluir->chavepri=$chavepri;
     $cliframe_alterar_excluir->sql = $clcflicita->sql_query(null,'*',null,"");
      //$cliframe_alterar_excluir->sql_disabled = 
      $cliframe_alterar_excluir->campos  ="l03_codigo,l03_descr,l03_tipo,l03_codcom,pc50_descr,l03_instit,nomeinst";
      $cliframe_alterar_excluir->legenda="REGISTROS";
      $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
      $cliframe_alterar_excluir->textocabec ="darkblue";
      $cliframe_alterar_excluir->textocorpo ="black";
      $cliframe_alterar_excluir->fundocabec ="#aacccc";
      $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
      $cliframe_alterar_excluir->iframe_width ="710";
      $cliframe_alterar_excluir->iframe_height ="130";
      $lib=1;
      if ($db_opcao==3||$db_opcao==33){
       	$lib=4;
      }
      $cliframe_alterar_excluir->opcoes = @$lib;
      $cliframe_alterar_excluir->iframe_alterar_excluir(@$db_opcao);   
      db_input('db_opcao',10,'',true,'hidden',3);
    ?>
   </td>
 </tr>
 </table>
</form>
<script>

</script>