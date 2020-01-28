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

//MODULO: Ambulatorial
$clsau_prestadorvinculos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("s110_i_codigo");
$clrotulo->label("s108_c_exame");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ts111_i_codigo?>">
       <?=@$Ls111_i_codigo?>
    </td>
    <td> 
<?
db_input('s111_i_codigo',10,$Is111_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts111_i_prestador?>">
    	<?=@$Ls111_i_prestador?>
       <?
       //db_ancora(@$Ls111_i_prestador,"js_pesquisas111_i_prestador(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('s111_i_prestador',10,$Is111_i_prestador,true,'text',3," onchange='js_pesquisas111_i_prestador(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts111_i_exame?>">
       <?
       db_ancora(@$Ls111_i_exame,"js_pesquisas111_i_exame(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('s111_i_exame',10,$Is111_i_exame,true,'text',$db_opcao," onchange='js_pesquisas111_i_exame(false);'")
?>
       <?
db_input('s108_c_exame',40,$Is108_c_exame,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts111_c_situacao?>">
       <?=@$Ls111_c_situacao?>
    </td>
    <td> 
<?
$x = array('A'=>'ATIVO','I'=>'INATIVO');
db_select('s111_c_situacao',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input type="button" name="limpa" value="Limpa" onclick="location.href='sau1_sau_prestadorvinculos001.php?s111_i_prestador=<?=$s111_i_prestador?>&z01_nome=<?=$z01_nome?>'">

<table>
<tr>
	<td>
		<?
		@$s111_i_prestador=$s111_i_prestador;
		$chavepri= array("s110_i_codigo"=>@$s110_i_codigo,"s111_i_codigo"=>@$s111_i_codigo);
        $cliframe_alterar_excluir->chavepri      =$chavepri;
        $cliframe_alterar_excluir->sql           = $clsau_prestadorvinculos->sql_query(null,"
        																			s110_i_codigo,
        																			s111_i_codigo,
        																			s108_i_codigo,
        																			s108_c_exame,
        																			case s111_c_situacao
        																			 when 'A' then 'ATIVO'
        																			 when 'I' then 'INATIVO'
        																			else 'Não informado'
        																			end as s111_c_situacao",null,"s111_i_prestador=$s111_i_prestador");
        $cliframe_alterar_excluir->campos        ="s111_i_codigo,s108_c_exame,s111_c_situacao";
		$cliframe_alterar_excluir->legenda       ="ITENS DO CARDAPIO";
        $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
        $cliframe_alterar_excluir->textocabec    = "darkblue";
        $cliframe_alterar_excluir->textocorpo    = "black";
        $cliframe_alterar_excluir->fundocabec    = "#aacccc";
        $cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
        $cliframe_alterar_excluir->iframe_width  = "710";
        $cliframe_alterar_excluir->iframe_height = "130";
        $cliframe_alterar_excluir->opcoes         = 1;
        $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
         ?>
	</td>
</tr>	
</table>
</form>
<script>
/*function js_pesquisas111_i_prestador(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_sau_prestadores','func_sau_prestadores.php?funcao_js=parent.js_mostrasau_prestadores1|s110_i_codigo|s110_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.s111_i_prestador.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_sau_prestadores','func_sau_prestadores.php?pesquisa_chave='+document.form1.s111_i_prestador.value+'&funcao_js=parent.js_mostrasau_prestadores','Pesquisa',false);
     }else{
       document.form1.s110_i_codigo.value = ''; 
     }
  }
}
function js_mostrasau_prestadores(chave,erro){
  document.form1.s110_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.s111_i_prestador.focus(); 
    document.form1.s111_i_prestador.value = ''; 
  }
}
function js_mostrasau_prestadores1(chave1,chave2){
  document.form1.s111_i_prestador.value = chave1;
  document.form1.s110_i_codigo.value = chave2;
  db_iframe_sau_prestadores.hide();
}*/

function js_pesquisas111_i_exame(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_sau_exames','func_sau_exames.php?funcao_js=parent.js_mostrasau_exames1|s108_i_codigo|s108_c_exame','Pesquisa',true);
  }else{
     if(document.form1.s111_i_exame.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_sau_exames','func_sau_exames.php?pesquisa_chave='+document.form1.s111_i_exame.value+'&funcao_js=parent.js_mostrasau_exames','Pesquisa',false);
     }else{
       document.form1.s108_i_codigo.value = ''; 
     }
  }
}
function js_mostrasau_exames(chave,erro){
  document.form1.s108_c_exame.value = chave; 
  if(erro==true){ 
    document.form1.s111_i_exame.focus(); 
    document.form1.s111_i_exame.value = ''; 
  }
}
function js_mostrasau_exames1(chave1,chave2){
  document.form1.s111_i_exame.value = chave1;
  document.form1.s108_c_exame.value = chave2;
  db_iframe_sau_exames.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_sau_prestadorvinculos','func_sau_prestadorvinculos.php?funcao_js=parent.js_preenchepesquisa|s111_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_sau_prestadorvinculos.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>