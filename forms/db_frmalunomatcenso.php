<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: escola
$clalunomatcenso->rotulo->label();
require_once("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo = new rotulocampo;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
}else{
 if(isset($alterar)){
  $db_opcao = 2;
  $db_botao1 = true;
 }else{
  $db_opcao = 1;
 }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted280_i_codigo?>">
       <?=@$Led280_i_codigo?>
    </td>
    <td> 
<?
db_input('ed280_i_codigo',10,$Ied280_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted280_i_aluno?>">
       <?
       db_ancora(@$Led280_i_aluno,"",3);
       ?>
    </td>
    <td> 
<?
db_input('ed280_i_aluno',10,$Ied280_i_aluno,true,'text',3," ")
?>
       <?
db_input('ed47_v_nome',40,@$Ied47_v_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td>
      <b>Código INEP do Aluno:</b>    
    </td>
    <td> 
<?
db_input('ed47_c_codigoinep',15,@$Ied47_c_codigoinep,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted280_i_turmacenso?>">
       <?=@$Led280_i_turmacenso?>
    </td>
    <td> 
<?
db_input('ed280_i_turmacenso',10,$Ied280_i_turmacenso,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted280_i_matcenso?>">
       <?=@$Led280_i_matcenso?>
    </td>
    <td> 
<?
db_input('ed280_i_matcenso',10,$Ied280_i_matcenso,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
    <tr>
    <td nowrap title="<?=@$Ted280_i_ano?>">
       <?=@$Led280_i_ano?>
    </td>
    <td> 
<?
db_input('ed280_i_ano',4,$Ied280_i_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
<input name="ed280_i_aluno" type="hidden" value="<?=@$ed280_i_aluno?>">
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=(@$db_botao1==false?"disabled":"")?> >
<table width="100%">
 <tr>
  <td valign="top"><br>
  <?
   $chavepri= array("ed280_i_codigo"=>@$ed280_i_codigo,"ed280_i_aluno"=>@$ed280_i_aluno,"ed47_v_nome"=>@$ed47_v_nome,"ed280_i_matcenso"=>@$ed280_i_matcenso,"ed280_i_turmacenso"=>@$ed280_i_turmacenso,"ed280_i_ano"=>@$ed280_i_ano);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clalunomatcenso->sql_query("","*","","ed280_i_aluno = $ed280_i_aluno");
   $cliframe_alterar_excluir->campos  = "ed280_i_codigo,ed47_v_nome,ed280_i_matcenso,ed280_i_turmacenso,ed280_i_ano";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="100";
   $cliframe_alterar_excluir->iframe_width ="100%";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</center>
</form>
<script>
</script>