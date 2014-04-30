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

  include("dbforms/db_classesgenericas.php");

  
  $cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
  $clrotulo                 = new rotulocampo;
  $clrotulo->label('j01_matric');
  $clrotulo->label('j01_nome');
  $clhistocorrencia->rotulo->label();
  $clhistocorrenciamatric->rotulo->label();
  
  if(isset($db_opcaoal)){
    $db_opcao=33;
    $db_botao=false;
  }else if(isset($opcao) && $opcao=="alterar"){
    if (($ar23_id_usuario == db_getsession("DB_id_usuario") || db_getsession('DB_administrador') == '1' )) {
      $db_botao=true;
    } else {
      $db_botao=false;
      
    }
    
    $db_opcao = 2;
    
  }else if(isset($opcao) && $opcao=="excluir"){
    
    if (($ar23_id_usuario == db_getsession("DB_id_usuario") || db_getsession('DB_administrador') == '1' )) {
	  $db_botao=true;
    } else {
      $db_botao=false;
    }
    
    $db_opcao = 3;
    
  }else{  
    $db_opcao = 1;
    $db_botao=true;
    if(isset($novo) || isset($alterar) || isset($excluir) || isset($incluir) ){
      $ar23_sequencial  = "";
      $ar23_descricao   = "";
      $ar23_ocorrencia  = ""; 
    }
  } 
?>
<fieldset style="margin-top: 20px;">
<legend><b>Cadastro de Imóveis/Terrenos - Ocorrencias</b></legend>
<form name="form1" method="post" action="">
<center>
<table border="0">
	<tr>
		<td title="<?=$Tj01_matric?>"><?=$Lj01_matric?></td>
		<td>
		<?
  		db_input("j01_matric", 10, $Ij01_matric, true, 'text', 3);
  
		?>
		</td>
	</tr>
	
	<tr>
      <td nowrap title="<?=@$Tar23_sequencial?>"><?=@$Lar23_sequencial?></td>
      <td>
        <?
          db_input('ar23_sequencial',10,$Iar23_sequencial,true,'text', 3," readonly = \"readonly\"");
        ?>
      </td>
      </tr>
	<tr>

	<tr>
		<td nowrap title="<?=@$Tar23_data?>"><?=@$Lar23_data?></td>
		<td>
		<?
		  db_inputdata('ar23_data',@$ar23_data_dia,@$ar23_data_mes,@$ar23_data_ano,true,'text',3,"");
		?>
		</td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tar23_hora?>"><?=@$Lar23_hora?></td>
		<td>
		<?
		  db_input('ar23_hora',10,$Iar23_hora,true,'text',3,"");
		?>
		</td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tar23_tipo?>"><?=@$Lar23_tipo?></td>
		<td>
		<?
      $ar23_tipo = 1;
      db_input('ar23_tipo', 10, $Iar23_tipo, true, 'hidden', 3);
      $ar23_tipo_nome = "Manual";
      db_input('ar23_tipo_nome', 10, $Iar23_tipo_nome, true, 'text', 3);
		  
		?>
		</td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tar23_descricao?>"><?=@$Lar23_descricao?></td>
		<td>
		<?
		  db_input('ar23_descricao',54,$Iar23_descricao,true,'text',$db_opcao,"");
		?>
		</td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tar23_ocorrencia?>"><?=@$Lar23_ocorrencia?></td>
		<td>
		<?
		  db_textarea('ar23_ocorrencia',10,52,$Iar23_ocorrencia,true,'text',$db_opcao,"");
		?>
		</td>
	</tr>
</table>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"	<?=($db_botao==false?"disabled":"")?>> 
 
<input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >

<table>
  <tr>
    <td valign="top"  align="center">  
    <?
      
      $campos  = "ar23_sequencial, ";
      $campos .= "ar23_tipo, ";
      $campos .= "ar23_descricao, ";
      $campos .= "ar23_ocorrencia, ";
      $campos .= "ar23_data, ";
      $campos .= "ar23_hora, ";
      $campos .= "login, ";
      $campos .= "nome_modulo ";
      
      
      $cliframe_alterar_excluir->sql           = $clhistocorrenciamatric->sql_query("", "$campos", "histocorrencia.ar23_data ", "histocorrenciamatric.ar25_matric = $ar25_matric and ar23_instit = " . db_getsession("DB_instit"));
      $cliframe_alterar_excluir->campos        = "$campos";
      $cliframe_alterar_excluir->legenda       = "Ocorrências da Matrícula";
      //$cliframe_alterar_excluir->opcoes        = 1;  
      $cliframe_alterar_excluir->iframe_height = "160";
      $cliframe_alterar_excluir->iframe_width  = "800";
      $chavepri = array("ar23_sequencial"=>$ar23_sequencial);
      $cliframe_alterar_excluir->chavepri      = $chavepri;
      $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
      
    ?>
  </td>
  </tr>
</table>

</center>
</form>
</fieldset>

<script>
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
</script>