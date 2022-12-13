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

//MODULO: empenho
$clempnota->rotulo->label();
$clempnotaele->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("nome");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_vlrliq");
$clrotulo->label("e60_vlranu");
$clrotulo->label("e60_vlremp");

if($db_opcao==1||$db_opcao==11){
  $db_opcao_botao = 1;
}else if($db_opcao == 2 ){
  $db_opcao_botao = 2;
  $db_opcao = 3;
}else if($db_opcao == 3 ){
  $db_opcao_botao = 3;
  $db_opcao = 3;
}else{
  $db_opcao_botao = $db_opcao;
} 


$db_opcao_desab=1;
if(isset($e69_numemp) && $e69_numemp!='' && empty($duv_msg)){
   //rotina que traz os dados do empenho
     $result = $clempempenho->sql_record($clempempenho->sql_query_file($e69_numemp)); 
     db_fieldsmemory($result,0);
   //fim  

   //rotina que irá somar os valores de todas as ordens 
       $result  = $clempnotaele->sql_record($clempnotaele->sql_query(null,null,"e60_numemp,sum(e70_valor) as tot_valor, sum(e70_vlrliq) as tot_vlrliq, sum(e70_vlranu) as tot_vlranu","","e60_numemp=$e69_numemp group by e60_numemp")); 
       if($clempnotaele->numrows>0){
	 db_fieldsmemory($result,0);
       }else{
	   $tot_vlrliq   = '0.00';
	   $tot_vlranu   = '0.00';
	   $tot_valor    = '0.00';
       }  
   //fim  
   
   //pega valores se tiver nota lanaçada
        $tem_elemento = false;
       if(isset($e69_codnota)){
	 $result02  = $clempnotaele->sql_record($clempnotaele->sql_query_file($e69_codnota,null,"sum(e70_valor) as total_valor, sum(e70_vlrliq) as total_vlrliq, sum(e70_vlranu) as total_vlranu  ")); 
	 if($clempnotaele->numrows>0){
	   db_fieldsmemory($result02,0);
	     $total_valor  = number_format($total_valor ,"2",".","");
	     $total_vlrliq = number_format($total_vlrliq,"2",".","");
	     $total_vlranu = number_format($total_vlranu,"2",".","");
   	     $tem_elemento = true;
	 }	 
       }
       if($tem_elemento==false){
	     $total_valor  = '0.00';
	     $total_vlrliq = '0.00';
	     $total_vlranu = '0.00';
       } 
  //fim
  
     //tot_xxx total de todas as ordens
     //total_xx total de só uma orde

  $saldo =   number_format( $total_valor - $total_vlranu,"2",".","");

   //rotina que retorna o valor disponivel
	if($db_opcao_botao==2){
          $vlrdis = ($e60_vlremp-$e60_vlrliq) -   ($tot_valor - $tot_vlranu - $tot_vlrliq);
	}elseif($db_opcao_botao==3){
	  $vlrdis = ($total_valor-$total_vlranu) ;
	}else{  
          $vlrdis = ($e60_vlremp-$e60_vlrliq-$e60_vlranu) -   ($tot_valor - $tot_vlranu - $tot_vlrliq);
	} 
	$vlrdis = number_format($vlrdis,"2",".","");
	$vlrpag = $vlrdis;
    //fim
   
    //rotina que verifica se a nota já foi liquidada
    if($total_vlrliq != 0 && $db_opcao!=1){
	$db_botao=false;
	$desabilita =  true;
	$db_opcao_desab = 33;
	      if(empty($alterar) && empty($anular) && empty($incluir) && empty($operan) ){
		$mens_erro="Nota já foi liquidada. Operação não permitida!";
	      }  
    //-----------------------------------------------------------------------------------------//  
    //rotina que verifica se o valor disponivel eh maior que zero
    }else if(($vlrdis==0||$vlrdis=='') && empty($excluindo) ){//variavel excluindo é gerada somente nos programas de exclusão
	$db_botao=false;
	$desabilita =  true;
	$db_opcao_desab = 33;
	      if(empty($alterar) && empty($anular) && empty($incluir) && empty($operan) ){
		$mens_erro="Não existe saldo dísponivel!";
	      }  
      }  
    //-----------------------------------------------------------------------------------------//  
}    

  ?>
<style>
<?$cor="#999999"?>
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
.bordas{
         border: 1px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #cccccc;
}
</style>
<form name="form1" method="post" action="">
<center>
<table border='0'>
	<?
	db_input('dados',6,0,true,'hidden',3);
	?>
<tr>
  <td  >
<table border="0">
  <tr>
    <td nowrap title="<?=@$Te69_codnota?>">
       <?=@$Le69_codnota?>
    </td>
    <td> 
<?
db_input('e69_codnota',6,$Ie69_codnota,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te69_numemp?>">
       <?=@$Le69_numemp?>
    </td>
    <td> 
<?
db_input('e69_numemp',13,$Ie69_numemp,true,'text',3)
?>
       <?
db_input('z01_nome',50,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te69_id_usuario?>">
       <?
       db_ancora(@$Le69_id_usuario,"js_pesquisae69_id_usuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('e69_id_usuario',5,$Ie69_id_usuario,true,'text',$db_opcao," onchange='js_pesquisae69_id_usuario(false);'")
?>
       <?
db_input('nome',30,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te69_numero?>">
       <?=@$Le69_numero?>
    </td>
    <td> 
<?
db_input('e69_numero',20,$Ie69_numero,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te69_dtrecebe?>">
       <?=@$Le69_dtrecebe?>
    </td>
    <td> 
<?
if(empty($e69_dtrecebe_dia)){
  $e69_dtrecebe_dia =  date("d",db_getsession("DB_datausu"));
  $e69_dtrecebe_mes =  date("m",db_getsession("DB_datausu"));
  $e69_dtrecebe_ano =  date("Y",db_getsession("DB_datausu"));
 
}
db_inputdata('e69_dtrecebe',@$e69_dtrecebe_dia,@$e69_dtrecebe_mes,@$e69_dtrecebe_ano,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
   <tr> 
     <td colspan='2' align='center'>
       <table>
	<tr class='bordas'>
	  <td class='bordas02' colspan='2' align='center' nowrap title="<?=@$Te60_vlremp?>">
	    <b><small>VALORES</small></b>
	  </td>
	  
	</tr>
      <?if($db_opcao==1){?>
	  <tr class='bordas'>
	    <td class='bordas' nowrap title="Valor que deseja anular">
		
	     <b>Valor disponível:</b>
	    </td>
	    <td class='bordas'> 
      	     <?db_input('vlrdis',10,0,true,'text',3);?>
	    </td>
	  </tr>
      <?}?>   	  

   <?if($db_opcao==2||$db_opcao==3){ ?>	    
           <tr>   
	    <td class='bordas' nowrap title="Valor à pagar">
	       <b>Saldo atual:</b>
	    </td>
	    <td class='bordas'> 
             <?db_input('saldo',10,4,true,'text',3,"onchange='js_verificar(\"campo\");'");?>
	    </td>
	   </tr>  
   <?}?>

   <?if($db_opcao==1){?>	    
	   <tr class='bordas'>
	    <td class='bordas' nowrap title="Valor à pagar">
           <b>Valor da nota</b>
	    </td>
	    <td class='bordas'> 
             <?db_input('vlrpag',10,4,true,'text',$db_opcao_botao,"onchange='js_verificar(\"campo\");'");?>
	    </td>
	  </tr>  
    <?}?> 	  
	  </table>
	  </td>
	</tr>  
  <tr>
    <td colspan='2' align='center'>
<input name="<?=($db_opcao_botao==1||$db_opcao_botao==11?"incluir":($db_opcao_botao==2||$db_opcao_botao==22?"excluir":"anular"))?>" type="submit" id="db_opcao" value="<?=($db_opcao_botao==1?"Incluir":($db_opcao_botao==2||$db_opcao_botao==22?"Excluir":"Anular"))?>" <?=($db_botao==false?"disabled":"")?>  onclick="return js_vericampos();" >
<?if($db_opcao!=1&&$db_opcao!=11){?>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<?}?>

<?if($db_opcao==1||$db_opcao==11){?>
<input name="voltar" type="button"  value="Novo Empenho" onclick="js_pesquisae60_numemp(true);" >
<?}?>
    </td>
  </tr>
  </table>
 </td>
    <td align='center' valign='top' colspan='1' align='center'>
      <table border='0'>
        <tr>
	  <td colspan='2' align='left'>
          <iframe name="ordens" id="elementos" src="forms/db_frmempnotas_notas.php?e60_numemp=<?=@$e69_numemp?>" width="370" height="100" marginwidth="0" marginheight="0" frameborder="0">
          </iframe>
	  </td>
        </tr>   	 

     <?if($db_opcao!=1){?>	 
       <tr> 
        <td>
          <table>
	  <tr class='bordas'>
	    <td class='bordas02' colspan='2' align='center' nowrap title="<?=@$Te60_vlremp?>">
	      <b><small>NOTA</small></b>
	    </td>
	  </tr>
	    <tr class='bordas'>
	      <td class='bordas' nowrap title="<?=@$Te70_valor?>">
		 <?=@$Le70_valor?>
	      </td>
	      <td class='bordas'> 
	  <?
	    db_input('total_valor',15,$Ie60_vlranu,true,'text',3,'')
	  ?>
	      </td>
	    </tr>
	    <tr class='bordas'>
	      <td class='bordas' nowrap title="<?=@$Te70_vlrpag?>">
		 <?=@$Le70_vlrliq?>
	      </td>
	      <td class='bordas'> 
	  <?
	    db_input('total_vlrliq',15,$Ie70_vlrliq,'text',3);
	  ?>
	      </td>
	    </tr>
	    <tr class='bordas'>
	      <td class='bordas' nowrap title="<?=@$Te70_vlranu?>">
		 <?=@$Le70_vlranu?>
	      </td>
	      <td class='bordas'> 
	  <?
	    db_input('total_vlranu',15,$Ie70_vlranu,true,'text',3,'')
	  ?>
	      </td>
	    </tr>
	    </table>
	   </td>  
	 </tr>  
     <?}?>
	 
      </table> 	
    </td>	  
</tr>
</table>
  
<?if(isset($e69_numemp)){?>  
  <table>
    <tr>
      <td>
    <iframe name="elementos" id="elementos" src="forms/db_frmempnotas_elementos.php?db_opcao=<?=$db_opcao_botao?>&e60_numemp=<?=@$e69_numemp?>&e69_codnota=<?=@$e69_codnota?>" width="720" height="150" marginwidth="0" marginheight="0" frameborder="0">
    </iframe>
      </td>
    </tr>
  </table>
<?}?>  
  </center>
</form>
<script>
function js_vericampos(){
  obj = document.form1;
  if(obj.e69_numero.value==""){
    alert("Preencha o numero da nota!")
    return false;
  }
  if(obj.e69_numemp.value==""){
    alert("Preencha o numero do empenho!")
    return false;
  }
  if(obj.e69_id_usuario.value==""){
    alert("Preencha o campo com o usuário responsável!!")
    return false;
  }
  return true;
}

<?
  if(isset($vlrdis)){
    if(isset($mens_erro)){
      echo "alert('$mens_erro');\n";
     } 
  ?>
      function js_verificar(tipo){
        erro=false; 

	vlrpag= new Number(document.form1.vlrpag.value);
	if(isNaN(vlrpag)){
	  erro=true;
	}

        vlrdis = new Number(document.form1.vlrdis.value);
	
	if(vlrpag > vlrdis){
	 erro= true;
	}


	if(erro==false){
	  val = vlrpag.toFixed(2);
	  document.form1.vlrpag.value=val
	  if(tipo=='campo'){
	    elementos.js_coloca(val);
	  }
	  return true;
	}else{
          document.form1.vlrpag.focus();
          document.form1.vlrpag.value="<?=$vlrdis?>";
	  elementos.js_coloca("<?=$vlrdis?>");
	  return false;
	}
	
      }
<?
  }
  
?>

function js_pesquisae69_id_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.e69_id_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.e69_id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.e69_id_usuario.focus(); 
    document.form1.e69_id_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.e69_id_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}

function js_pesquisae60_numemp(mostra){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_mostraempempenho1|e60_numemp','Pesquisa',true);
}
function js_mostraempempenho1(chave){
  location.href = "emp1_empnota004.php?e69_numemp="+chave;
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_empnota','func_empnota.php?funcao_js=parent.js_preenchepesquisa|e69_codnota','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_empnota.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
<?
if($db_opcao==11){
  echo "js_pesquisae60_numemp(true)";
}
?>
</script>