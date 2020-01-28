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
include("classes/db_cflicita_classe.php");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cldb_config = new cl_db_config;
$clcflicita = new cl_cflicita;
$clcflicita->rotulo->label();
$clpccflicitapar->rotulo->label();
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
	  <td nowrap title="<?=@$Tl25_codcflicita?>">
	  <b>Tipo de Compra:</b>
	  </td>
	  <td nowrap title="<?=@$Tl25_codcflicita?>">
	  <?
	  $result_tipo=$clcflicita->sql_record($clcflicita->sql_query_file(null,"l03_codigo,l03_descr",null,"l03_instit=".db_getsession("DB_instit")));
      if (isset($l25_codcflicita)&&$l25_codcflicita!=""){
        	echo "<script>document.form1.l25_codcflicita.selected=$l25_codcflicita;</script>";
      }
      db_selectrecord("l25_codcflicita",$result_tipo,true,$db_opcao,"");
      ?>
      </td>
      
      	  </tr>
      	  <tr>
	  <td nowrap title="<?=@$Tl25_anousu?>">
	     <b>Ano:</b> 
	  </td>
	  <td nowrap title="<?=@$Tl25_anousu?>">
         <?
         if (!isset($l25_anousu)||$l25_anousu==""){
           $l25_anousu=date('Y',db_getsession("DB_datausu"));	
         }
         
         db_input('l25_anousu',10,$Il25_anousu,true,'text',3,"");
	     ?>
	  	     
	  </td>
	  </tr>
	  <tr>
	  <td nowrap title="<?=@$Tl25_numero?>">
	     <b>Numeração:</b> 
	  </td>
	  <td nowrap title="<?=@$Tl25_numero?>">
         <?
              
         db_input('l25_numero',10,$Il25_numero,true,'text',$db_opcao,"");
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
     $chavepri= array("l25_codcflicita"=>@$l25_codcflicita,"l25_anousu"=>@$l25_anousu,"l25_numero"=>@$l25_numero);
     $cliframe_alterar_excluir->chavepri=$chavepri;
     $cliframe_alterar_excluir->sql = $clpccflicitapar->sql_query(null,'*',null,"");
      //$cliframe_alterar_excluir->sql_disabled = 
      $cliframe_alterar_excluir->campos  ="l25_codcflicita,l03_descr,l03_instit,nomeinst,l25_anousu,l25_numero";
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