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
?
 if (isset($incluir)){
 $db_botao=false;	
 }
 ?>
 
 <form name="form1" method="post" action="">
    <?
    $result_clientes = $clclientes->sql_record($clclientes->sql_query_file(null,"at01_codcli,at01_nomecli","at01_nomecli"," at01_status is true"));
   	$result_area  = $clatendcadarea ->sql_record($clatendcadarea->sql_query(null,"*",null,null));
   //	$l= pg_num_rows($result_area);
   	 
   	
   	db_input("at02_codatend",10,$Iat02_codatend,true,"hidden",3);
   	db_input("at11_origematend",10,$Iat11_origematend,true,"hidden",3);
   	db_input("at05_seq",10,$Iat05_seq,true,"hidden",3);
   	db_input("at40_sequencial",10,$Iat40_sequencial,true,"hidden",3);
   	db_input("at20_sequencial",10,@$Iat20_sequencial,true,"hidden",3);
   	db_input("at16_sequencial",10,@$Iat20_sequencial,true,"hidden",3);
    db_input("at28_sequencial",10,@$Iat28_sequencial,true,"hidden",3);
   
    //<input name="result_tec" type="text" value="">

    ?>
    
      <tr>
      <td ><b>Cliente:</b></td>
         <td >
         <?
//         db_selectrecord("at01_nomecli",$result_clientes,true,1,"onblur='js_submit();'");
	      db_selectrecord("cliente",$result_clientes,true,1,"","","","0","js_cliente();");
         ?>
         <input name='modulos' type='button' value='Pesquisa Módulo' onclick='js_pesquisamodulo()'>
         <script>
         function js_pesquisamodulo(){
           js_OpenJanelaIframe('top.corpo','db_iframe_modulocliente','func_contarefa_menu.php?verifica_area=true&cliente='+document.form1.cliente.value,'Pesquisa',true);
         }
         </script>
         </td>
         
         
      </tr>
      
      <?
      if (!isset($cliente)||$cliente==""){
          // if($clclientes->numrows>0){
          // 		db_fieldsmemory($result_clientes,0);
           		$cliente=0;
          // }
      }
      if (isset($cliente)){     	
      	echo "<script>document.form1.cliente.value=$cliente</script>";
      ?>
      <tr> 
    	<td  align="left" nowrap><b><?db_ancora('Usuário:',"js_pesquisa_usuario(true);",1); ?></b></td>
    	<td align="left" nowrap>
      	<?
      	db_input("usuario",10,$Iat10_usuario,true,"text",4,"onchange='js_pesquisa_usuario(false);'");
      	db_input("nome",40,$Inome,true,"text",3);
        ?>
        </td>
  	  </tr>
  	  <tr> 
    	<td  align="left" nowrap><b>Área:</b></td>
    	<td align="left" nowrap>
    		<? db_selectrecord("area",$result_area,true,1,"","","","0","js_verificaarea();"); ?>
    	</td>
  	  </tr><td>     
  	  <?
  	 if (!isset($area)||$area==""){
  	   	$result_tec = $clatendareatec ->sql_record("select * from ( select distinct on (  at27_usuarios ) at27_usuarios,lower(nome)||case when at71_descr is null then '' else  '('||coalesce(at71_descr,'')||')' end  as nome from atendareatec inner join db_usuarios on id_usuario=at27_usuarios left join atendtecnicoocupado on at27_usuarios = at72_id_usuario left join atendtipoausencia on at72_codtipo = at71_codigo where db_usuarios.usuarioativo = '1') as x order by nome");
  	 }elseif($area>0){
  	 	$result_tec = $clatendareatec ->sql_record("select * from ( select distinct on (  at27_usuarios ) at27_usuarios,lower(nome)||case when at71_descr is null then '' else  '('||coalesce(at71_descr,'')||')' end  as nome from atendareatec inner join db_usuarios on id_usuario=at27_usuarios left join atendtecnicoocupado on at27_usuarios = at72_id_usuario left join atendtipoausencia on at72_codtipo = at71_codigo where  at27_atendcadarea=$area and db_usuarios.usuarioativo = '1') as x order by nome");
  	
  	 }else{
  	 	$result_tec = $clatendareatec ->sql_record("select * from ( select distinct on (  at27_usuarios ) at27_usuarios,lower(nome)||case when at71_descr is null then '' else  '('||coalesce(at71_descr,'')||')' end  as nome from atendareatec inner join db_usuarios on id_usuario=at27_usuarios left join atendtecnicoocupado on at27_usuarios = at72_id_usuario left join atendtipoausencia on at72_codtipo = at71_codigo where db_usuarios.usuarioativo = '1') as x order by nome");

  	 }
  	 ?>
  	
</td>
  	  <tr> 
    	<td  align="left" nowrap><b>Técnico:</b></td>
    	<td align="left" nowrap>
    	<?  
	db_selectrecord('tecnico',$result_tec,true,1,"","","","0-Nenhum",""); 
    	?>

                <input name="Consulta_atendtecnico" type="button" id="consulta_atendtecnico" value="Consulta Ténicos" onclick='js_consulta_atend()'>
<script>
function js_consulta_atend(){
  js_OpenJanelaIframe('top.corpo','db_iframe_atendtecnico','func_atendtecnicoocupado.php','Pesquisa Técnicos Ocupados',true);
}
</script>    	</td>
  	  </tr>
  	  <!--    
      <tr> 
    	<td  align="left" nowrap><b><?db_ancora('Técnico:',"js_pesquisa_tecnico(true);",1);?></b></td>
    	<td align="left" nowrap>
      	<?
      	db_input("tecnico",10,"",true,"text",4,"onchange='js_pesquisa_tecnico(false);'");
      	db_input("nome_tecnico",40,"",true,"text",3);
        ?>
        </td>
  	  </tr> 
 -->
 <!--     <tr> 
    	<td  align="left" nowrap><b><?db_ancora('Módulo:',"js_pesquisa_modulo(true);",1);?></b></td>
    	<td align="left" nowrap>
      	<?
      	db_input("at08_modulo",10,"",true,"text",4,"onchange='js_pesquisa_modulo(false);'");
      	db_input("nome_modulo",40,"",true,"text",3);
        ?>
        </td>
  	  </tr>   
  	  -->   
	<tr>
	    <td nowrap title="<?=@$Tat04_codtipo?>">
    	   <?=@$Lat04_codtipo?>
    	</td>
		<td>
		<?
		  $result = $cltipoatend->sql_record($cltipoatend->sql_query(null,"*","at04_codtipo","at04_codtipo >= 100"));
		  db_selectrecord("at04_codtipo",$result,false,1); 
		?>		
		</td>
	</tr>






	<tr>
	    <td nowrap title="<?=@$Tat16_situacao?>">
    	   <?=@$Lat16_situacao?>
    	</td>
		<td>
		<?
		  $result = $clatendimentocadsituacao->sql_record($clatendimentocadsituacao->sql_query(null,"*",null,""));
		  db_selectrecord("at16_situacao",$result,false,1); 
		?>		
		</td>
	</tr>





	
	<tr>
	    <td nowrap title="<?=@$Tat02_observacao?>">
    	   <?=@$Lat02_observacao?>
    	</td>
		<td>
		<?
		  db_textarea('at02_observacao', 10, 50, $Iat02_observacao, true, 'text', 1, "");
		?>		
		</td>
	</tr>
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <?
      }
      ?>      
      <tr>
        <td colspan=2 align=center>
        <input name='<?=($db_opcao==1?'incluir':($db_opcao==2||$db_opcao==22?'alterar':'excluir')) ?>' type='submit' id='db_opcao' value='<?=($db_opcao==1?'Incluir':($db_opcao==2||$db_opcao==22?'Alterar':'Excluir')) ?>'  <?=($db_botao==false?'disabled':'')?> >
        <input type='button' name='origem'  value='Origem'  <?=(isset($certo)&&$certo==true?"disabled":"") ?> onclick="js_origem();">
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" <?=($db_opcao==1?"disabled":"")?> >
        </td>
      </tr>
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <?      
      if (isset($certo)&&$certo==true){
	  ?>      	
      	<tr>
        	<td colspan=2 align=center>
            <h1>Atendimento Nº <?=$clatendimento->at02_codatend?><? if(isset($at11_origematend)&&$at11_origematend!="") { echo "  Atend. Inicial Nº ".$at11_origematend; } if(isset($at05_seq)&&$at05_seq!="") { echo "  Andamento Nº ".$at05_seq; } if(isset($at40_sequencial)&&$at40_sequencial!="") { echo "  Tarefa Nº ".$at40_sequencial; } ?></h1>
          	</td>
      	</tr>
	   <?
        $codatend=$clatendimento->at02_codatend;
       ?>
       <tr>
         <td align = center><input type='button' name='reset' value='Voltar' onclick="location.href='ate4_atendcli001.php';" >&nbsp;</td>         
         <td align = center><input type='button' name='processa' value='Incluir Andamento' onclick="location.href='ate4_atendsup001.php?chavepesquisa=<?=$codatend?>&opcao=incluir';" >&nbsp;</td>
       </tr>
       <?
      }
      ?>
  </form>
  <script>
//--------------------------------
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_atendcli','func_atendcli.php?funcao_js=parent.js_preenchepesquisa|at02_codatend','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_atendcli.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_origem() {
    js_OpenJanelaIframe('top.corpo','db_iframe_atendimentoorigem','func_atendimentoorigem.php?chave_cliente='+document.form1.cliente.value+'&chave_usuario='+document.form1.usuario.value+'&chave_tecnico='+document.form1.tecnico.value+'&chave_modulo='+document.form1.at08_modulo.value+'&funcao_js=parent.js_mostraatendimentoorigem|at01_codcli|at10_usuario|id_usuario|id_item|at02_codatend','Pesquisa',true);
}
function js_cliente(){
   
    js_OpenJanelaIframe('top.corpo','db_iframe_db_clientes','func_clientes.php?pesquisa_chave='+document.form1.cliente.value+'&observacao=&funcao_js=parent.js_mostra_cliente','Pesquisa',false);
    
	document.form1.usuario.value='';
	document.form1.nome.value='';	
}

function js_mostra_cliente(mens){
  if(mens!=""){
    alert(mens);
  }
}

function js_mostraatendimentoorigem(chave_cliente,chave_usuario,chave_tecnico,chave_modulo,chave_atend,erro){
  document.form1.cliente.value       = chave_cliente; 
  document.form1.clientedescr.value  = chave_cliente;
  document.form1.usuario.value       = chave_usuario; 
  document.form1.tecnico.value       = chave_tecnico; 
  document.form1.at08_modulo.value   = chave_modulo; 
  document.form1.at02_codatend.value = chave_atend;
  db_iframe_atendimentoorigem.hide();
  js_OpenJanelaIframe('top.corpo','db_iframe_usucliente','func_db_usuclientesalt.php?cliente='+document.form1.cliente.value+'&pesquisa_chave='+document.form1.usuario.value+'&funcao_js=parent.js_mostramatordem','Pesquisa',false);
  js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.tecnico.value+'&funcao_js=parent.js_mostra_tecnico','Pesquisa',false);
  js_OpenJanelaIframe('top.corpo','db_iframe_db_modulos','func_db_modulos.php?pesquisa_chave='+document.form1.at08_modulo.value+'&funcao_js=parent.js_mostra_modulo','Pesquisa',false);
}
function js_pesquisa_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_usucliente','func_db_usuclientesalt.php?cliente='+document.form1.cliente.value+'&funcao_js=parent.js_mostramatordem1|at10_usuario|at10_nome','Pesquisa',true);
  }else{
     if(document.form1.usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_usucliente','func_db_usuclientesalt.php?cliente='+document.form1.cliente.value+'&pesquisa_chave='+document.form1.usuario.value+'&funcao_js=parent.js_mostramatordem','Pesquisa',false);
     }else{
       document.form1.usuario.value = '';
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostramatordem(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.usuario.value = ''; 
    document.form1.usuario.focus(); 
  }
}
function js_mostramatordem1(chave1,chave2){
   document.form1.usuario.value = chave1;  
   document.form1.nome.value = chave2;
   db_iframe_usucliente.hide();
}
function js_pesquisa_tecnico(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostra_tecnico1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.tecnico.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.tecnico.value+'&funcao_js=parent.js_mostra_tecnico','Pesquisa',false);
     }else{
       document.form1.tecnico.value      = '';
       document.form1.nome_tecnico.value = ''; 
     }
  }
}
function js_mostra_tecnico(chave,erro){
  document.form1.nome_tecnico.value = chave; 
  if(erro==true){ 
    document.form1.tecnico.value = ''; 
    document.form1.tecnico.focus(); 
  }
}
function js_mostra_tecnico1(chave1,chave2){
   document.form1.tecnico.value = chave1;  
   document.form1.nome_tecnico.value = chave2;
   db_iframe_db_usuarios.hide();
}
function js_pesquisa_modulo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_modulos','func_db_modulos.php?funcao_js=parent.js_mostra_modulo1|id_item|nome_modulo','Pesquisa',true);
  }else{
     if(document.form1.at08_modulo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_modulos','func_db_modulos.php?pesquisa_chave='+document.form1.at08_modulo.value+'&funcao_js=parent.js_mostra_modulo','Pesquisa',false);
     }else{
       document.form1.at08_modulo.value = '';
       document.form1.nome_modulo.value = ''; 
     }
  }
}
function js_mostra_modulo(chave,erro){
  document.form1.nome_modulo.value = chave; 
  if(erro==true){ 
    document.form1.at08_modulo.value = ''; 
    document.form1.at08_modulo.focus(); 
  }
}
function js_mostra_modulo1(chave1,chave2){
   document.form1.at08_modulo.value = chave1;  
   document.form1.nome_modulo.value = chave2;
   db_iframe_db_modulos.hide();
}

function js_verificaarea(area){
	document.form1.submit();
	//pesquisaarea.location.href = 'pesquisaarea.php?area='+area;
}
//--------------------------------
</script>