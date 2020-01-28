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
//MODULO: atendimento

include ("classes/db_tipoatend_classe.php");
include ("classes/db_db_usuarios_classe.php");
include ("classes/db_clientes_classe.php");
$cldb_usuarios      = new cl_db_usuarios;
$clclientes         = new cl_clientes;
$cltecnico          = new cl_tecnico;
$cltipoatend        = new cl_tipoatend;
$cltecnico    ->rotulo->label();
$cltipoatend  ->rotulo->label();
$clatendimento->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("at01_nomecli");
$clrotulo->label("nome");
$clrotulo->label("at03_codatend");
$clrotulo->label("at03_id_usuario");
$clrotulo->label("at05_data");
$clrotulo->label("at05_perc");
$clrotulo->label("at05_feito");
$clrotulo->label("at05_solicitado");
$clrotulo->label("at08_modulo");

include("dbforms/db_classesgenericas.php");

include("classes/db_db_projetosativcli_classe.php");
$cldb_projetosativcli= new cl_db_projetosativcli;

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$disabled = "disabled";

if(isset($opcao) && $opcao == "alterar"){
        
		if(isset($modulo) && $modulo>=0){
			$moduloalt = $modulo;
		}
	
		$db_opcao = 1;
		$campos= "at34_sequencial,at05_solicitado,at05_feito,at05_perc, at22_modulo as modulo,at05_data, codproced , at22_sequencial,at29_sequencial,at34_tarefacadmotivo as motivo";
    // verificar porque na alteracao de um item da erro de variavel indefinida: $at05_codatend
		$result = $clatendimento ->sql_record($clatendimento ->sql_query_sup("",$campos,"","at02_codatend = $at05_codatend and at05_seq = $at05_seq")); 
	    //die($clatendimento ->sql_query_sup("",$campos,"","at02_codatend = $at05_codatend and at05_seq = $at05_seq"));
	   	db_fieldsmemory($result,0);

	
	   	$db_botao = true;
	 	if (isset($moduloalt)&&$moduloalt>0 &&$moduloalt!=$modulo&&$trocamodulo=='t'){
	  		$modulo = $moduloalt;
	  	}
	   	if($modulo>0){
	   		$at08_modulo = $modulo;
		   /*	echo"
		   	<script>
				document.form1.modulo.value=$modulo;
		   	</script>";*/
	   	}
	

}else 
if(isset($opcao) && $opcao == "excluir"){
  	
  	$db_opcao = 3;
  	//$result = $clatendimento ->sql_record($clatendimento ->sql_query_sup("",$campos,"","at02_codatend = $at05_codatend and at05_seq = $at05_seq"));
  	$result = $clatendimento ->sql_record($clatendimento ->sql_query_sup("","*","","at02_codatend = $at05_codatend and at05_seq = $at05_seq")); 
  //	die($clatendimento ->sql_query_sup("","*","","at02_codatend = $at05_codatend and at05_seq = $at05_seq"));
   	db_fieldsmemory($result,0);
   	$db_botao = true;
}
if((isset($incluir)) || (isset($alterar)) || (isset($excluir))){
	$disabled = "";	
}

//Remove os slashes
$at05_feito=stripslashes(@$at05_feito);
$at05_solicitado=stripslashes(@$at05_solicitado);
//$result_syscadproced = $cl_db_syscadproced->sql_record($cl_db_syscadproced -> sql_query_file ( null,"codproced,descrproced","descrproced",""));

?>
<form name="form1" method="post" action="">
	<center>
	<table width="95%" border="0">
	<?
	?>
	<input name="hora_inicial" type = "hidden" value=<?=(!isset($hora_inicial)?date("H:i"):$hora_inicial)?> >
	<?
	$trocamodulo= "f";
	db_input("trocamodulo",10,"",false,"hidden",3);
	
	if(isset($at34_sequencial)){
		db_input("at34_sequencial",10,"",false,"hidden",3);
	}
	
	if(isset($at22_sequencial)){
		db_input("at22_sequencial",10,"",false,"hidden",3);
	}
	
	if(isset($at29_sequencial)){
		db_input("at29_sequencial",10,"",false,"hidden",3);
	}
	if(isset($at05_seq)){
		db_input("at05_seq",10,"",false,"hidden",3);
	}
	if(!isset($horaini)) {
		$horaini = db_hora();
	}
	db_input("horaini",10,"",false,"hidden",3);
	if(isset($opcao)&&$opcao!="") {
		db_input("opcao",10,"",false,"hidden",3);
	}
	else {
		$opcao = "incluir";
		db_input("opcao",10,"",false,"hidden",3);
	}
	db_input("codatend",10,"",true,"hidden",3);

	
	if(@$codatend != null) {
	    $clatendimento = new cl_atendimento;
		$result        = $clatendimento->sql_record($clatendimento->sql_query_sup(null,"at06_horalanc,at06_datalanc,at02_codatend,at01_codcli,at01_codver,at01_nomecli,at04_codtipo,at04_descr,id_usuario,nome,at10_usuario,at10_nome,at02_observacao","at02_codatend desc","at02_codatend = $codatend"));
		//die($clatendimento->sql_query_sup(null,"at06_horalanc,at06_datalanc,at02_codatend,at01_codcli,at01_nomecli,at04_codtipo,at04_descr,id_usuario,nome,at10_usuario,at10_nome","at02_codatend desc","at02_codatend = $codatend"));
		$linhasatend = $clatendimento->numrows;
		
		if($linhasatend > 0) {
			db_fieldsmemory($result,0);

      if ($at02_observacao != "" and !isset($item_menu) ) {
        $at05_feito .= "\nCONTEUDO DO ATENDIMENTO:\n\n" . $at02_observacao;
      }
			
			?>	
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
   			<tr>
				<td align=left><b>Atendimento Nº&nbsp;&nbsp;<?=$at02_codatend?></b></td>
				<td><b>Usuário envolvidos :</b></td>
   		  	</tr>
   		  	<tr>
				<td align=left><b>Cliente:&nbsp;&nbsp;<?=$at01_codcli?>&nbsp;&nbsp;-&nbsp;&nbsp;<?=$at01_nomecli?></b></td>
				<td rowspan=4 >
					
					<?
						global $at01_codcli;
                     	db_input("at01_codcli",20,"",true,"hidden",3);

					//$sql4   = "select distinct at10_codcli, at10_nome, at10_usuario from db_usuclientes
					//           where at10_codcli = $clientes order by at10_nome";
					// echo "<br> $sql4 <br>";
					if(isset($opcao) && $opcao == "alterar"){
						//echo" é alterar";
						$sqlusu = "
								select distinct at10_codcli, at10_nome, at10_usuario,at21_usuario as usu, 
								case when substr(rh01_nasc,6,5)::varchar = '".date("m-d",db_getsession("DB_datausu"))."'::varchar then 'Aniversário' else  '' end as aniver,
								('".date("Y-m-d",db_getsession("DB_datausu"))."'::date - rh01_nasc)/365 as anos								
								from db_usuclientes
								left join acesso_clientes_dados on at10_codcli = cliente and at10_usuario = id_usuario
								left join atenditemusu on  at21_usuario = at10_usuario
								and at21_atenditem = $at05_seq
								and at21_codatend = $codatend
								where at10_codcli = $clientes order by at10_nome
								";
								//echo"<br>$sqlusu";
					}else{
						//echo"não é alterar";
						$sqlusu = "
								select distinct at10_codcli, at10_nome, at10_usuario,at20_usuario as usu , 
								case when substr(rh01_nasc,6,5)::varchar = '".date("m-d",db_getsession("DB_datausu"))."'::varchar then 'Aniversário' else  '' end as aniver,
								('".date("Y-m-d",db_getsession("DB_datausu"))."'::date - rh01_nasc)/365 as anos
								from db_usuclientes
								left join atendimentousu on at20_usuario = at10_usuario  and at20_codatend = $codatend
								left join acesso_clientes_dados on at10_codcli = cliente and at10_usuario = id_usuario
								where at10_codcli = $clientes order by at10_nome
						"; 
						//echo"<br>$sqlusu<br>";
					}	
					$rs_atend = $clatendimento->sql_record($sqlusu);	
					$linhas =pg_num_rows($rs_atend);
					?>   
					<select name="usuorigem[]" multiple size="5">
					<?
					$at10_usuario_ori = "";
					$at10_nome_ori    = "";
					for ($z = 0; $z < $linhas; $z ++) {
						db_fieldsmemory($rs_atend,$z);
						if($at10_usuario==$usu){
							if ($at10_usuario_ori == "") {
								$at10_usuario_ori = $at10_usuario;
								$at10_nome_ori    = $at10_nome;
								if($aniver!="")
								  $at10_nome_ori .= " <font color='red'>ANIVERSÁRIO $anos Anos Hoje </> ";
							}
							$selected = "SELECTED";
						}
						else{
							$selected = "";
						}
						echo "<option value=$at10_usuario $selected>$at10_nome</option>";
						
					}
					?>
					</select>
					
				</td>
			</tr>
			<tr>	    
				<td align=left><b>Solicitado pelo usuário:&nbsp;&nbsp;<?=$at10_usuario_ori?>&nbsp;&nbsp;-&nbsp;&nbsp;<?=strtoupper($at10_nome_ori) ?></b></td>
			</tr>	  	  
   		  	<tr>
       			<td align=left><b>Tipo de atendimento:&nbsp;&nbsp;<?=$at04_codtipo?>&nbsp;&nbsp;-&nbsp;&nbsp;<?=$at04_descr?></b></td>
			</tr>
			<tr>
	   		  	<td align=left><b>Data: <?=db_formatar($at06_datalanc,"d")?> &nbsp&nbsp Hora: <?=$at06_horalanc?></b></td>
			</tr>
			<tr>
			<?
			$resultversao = $cldb_versao->sql_record($cldb_versao->sql_query_file(null,"db30_codver,fc_versao(db30_codversao, db30_codrelease) as versao_cliente",' db30_codver'," db30_codver = $at01_codver"));
            db_fieldsmemory($resultversao,0);			
			?>
	   		  	<td align=left><b>Versão no Cliente: <?=$versao_cliente?> </b></td>
			</tr>
			
   		    <?
   		    
			  
		}
	}

?>
		<tr>
			<td>
			<?
			global $Mversao;
			$Mversao= "";
			?>
				<input name="pesquisa_item" type="button" value="Selecione o Menu" onclick = "js_pesquisa_menus(<?=$clientes?>,<?=$at01_codver?>,<?=$at10_usuario_ori?>);" >
	            
	            <?
	            db_input("item_menu",10,"",true,"text",3);
	            db_input("descr_menu",60,"",true,"text",3);
	            ?>
			<br><strong>Versão Atual Sistema:</strong>
			<?
			$resultversao = $cldb_versao->sql_record($cldb_versao->sql_query_file(null,"db30_codver,fc_versao(db30_codversao, db30_codrelease) as versao",'db30_codver desc limit 1'));
			db_selectrecord('at67_codver',$resultversao,true,$db_opcao,"","","","");
			
			?>
			
			</td>
			<td>
			
			</td>
		</tr>
		<tr align=center>
		    <td nowrap align=center title="<?=@$Tat02_solicitado?>" valign=top>
		    	<?=@$Lat02_solicitado?>
		    </td>
		    <td nowrap align=center title="<?=@$Tat05_feito?>" valign=top>
		    	<?=@$Lat05_feito?>
		    </td>
		</tr>
		<tr>
		
		    <td align=center> 
				<?db_textarea('at05_solicitado',8, 50,0, true, 'text', $db_opcao, "") ?>
		    </td>
		    <td align=center> 
				<?db_textarea('at05_feito', 8, 50,0, true, 'text', $db_opcao, "") ?>
			</td>
		</tr>
		<tr>
		    <td nowrap title="<?=@$Tat05_perc?>" align="left">
		      	<?=@$Lat05_perc?>
		       	<?
				$matriz = array("0"=>"0%",
			                  "10"=>"10%", 
			                  "20"=>"20%",
			                  "30"=>"30%",
			                  "40"=>"40%",
			                  "50"=>"50%", 
			                  "60"=>"60%",
			                  "70"=>"70%",
			                  "80"=>"80%",
			                  "90"=>"90%",
			                  "100"=>"100%");             
		  		db_select("at05_perc", $matriz,true,$db_opcao); 
				?>
		  		<b> Prioridade:</b>
		  		<?
		  		 $x = array("1"=>"Baixa",
             				"2"=>"Média", 
            				"3"=>"Alta"
	   						); 	
            				db_select("at05_prioridade", $x,true,$db_opcao); 
		  		?>
	
				
		    </td>
		    <td>
		    	<b>
		    	Motivo:
		    	<?
				$resultmot = $cl_tarefacadmotivo->sql_record("select  at54_sequencial,at54_descr from tarefacadmotivo where at54_tipo = 1 order by at54_descr");
				if( pg_numrows($resultmot) > 0){
				  db_selectrecord('motivo',$resultmot,true,2,"","","","0-Nenhum");
				}
				?>
		    	</b>
		    </td>
		    <!--
		    <td align="left" nowrap title="<?=@$Tat41_proced?>"><b>Procedimento:</b>
			<?
				if (isset($at41_proced) and $at41_proced == 0) {
					unset($at41_proced);
				}
				db_selectrecord('at41_proced',($cldb_proced->sql_record($cldb_proced->sql_query(($db_opcao==2?null:@$at41_proced),"at30_codigo,at30_descr","at30_codigo",null))),true,$db_opcao,"","","","0-Nenhum");
			?>
		    </td>
		   -->
		</tr>
		<tr align=center>
		  	<td align="left">
		  		<b>Modulo Verificado:</b>
		  		
		  		
		        <?
		        $sqlmod = "select codmod,nomemod from db_sysmodulo where ativo = 't' order by nomemod";
		        //$sqlmod = "select id_item, nome_modulo from db_modulos order by nome_modulo";
		        $result_modulo = pg_exec($sqlmod);
		        db_selectrecord('modulo',$result_modulo,true,$db_opcao,"","","","0-Nenhum","js_verifica();");
		        
		        ?>
		        
		    </td> 
		    <td align="left" nowrap title="<?=@$Tat05_data?>">
		       	<?=@$Lat05_data?>
				<?db_inputdata('at05_data', @ $at05_data_dia, @ $at05_data_mes, @ $at05_data_ano, true, 'text', $db_opcao, "") ?>
		    </td>       
		</tr>
		<?

		if (isset ($incluir) && $incluir != "") {
			$modulo=0;
		}
		
		if (isset($modulo) && $modulo > 0 ) {
			$sqlprocedmod="
				select codproced,descrproced , nomemod
				from db_syscadproced 
				inner join db_sysmodulo on db_sysmodulo.codmod=db_syscadproced.codmod 
				where db_syscadproced.codmod = $modulo
				order by descrproced"; 
				$result_syscadproced = $cl_db_syscadproced->sql_record($sqlprocedmod);
				//$result_syscadproced = $cl_db_syscadproced->sql_record($cl_db_syscadproced -> sql_query ( null,"codproced,descrproced || ' - ' || nome_modulo","descrproced","codmod=$modulo"));
		}else if ( isset($item_menu) && $item_menu > 0 ) {
			$sqlprocedmod="
			    
				select 0 as codproced,'Nenhuma' as descrproced, '' as nomemod
                
                union 
                
                select * from (
				select distinct p.codproced,descrproced , nomemod
				from db_syscadproced p
       				 inner join db_syscadproceditem i on i.codproced = p.codproced 
				     inner join db_sysmodulo on db_sysmodulo.codmod= p.codmod 
				where i.id_item = $item_menu
				order by descrproced
				) as 
				";
				$result_syscadproced = $cl_db_syscadproced->sql_record($sqlprocedmod);		
		}else{
			$sqlproced="
				select 0 as codproced,'Nenhuma' as descrproced, '' as nomemod
                
                union 
                select * from ( 
				select codproced,descrproced , nomemod
				from db_syscadproced 
				inner join db_sysmodulo on db_sysmodulo.codmod=db_syscadproced.codmod 
				order by descrproced
				) as x";
			$result_syscadproced = $cl_db_syscadproced->sql_record($sqlproced);
			//$result_syscadproced = $cl_db_syscadproced->sql_record($cl_db_syscadproced -> sql_query ( null,"codproced,descrproced || ' - ' || nome_modulo","descrproced",""));
		}
		?>
		
		<tr>
			<td colspan="2">
				<b>
		    	Procedimento:
		    	<?
		    	if( $result_syscadproced==null || pg_numrows($result_syscadproced) == 0 ){
				  $result_syscadproced = pg_query("select 0 as codproced,'Nenhuma' as descrproced");
		    	}
		    	db_selectrecord('codproced',$result_syscadproced,true,$db_opcao,"","","","","js_verifica();");
				?>
				<input name="pesquisaroutras" type="button" value="Outras tarefas" onclick = "js_pesquisa_tarefa_outras();" >
		    	</b>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<b>Tarefa:</b>
				<?
				if (@$codproced >0){
					$sqltarefa = "
							select * from (
							select * from (
							select at40_sequencial,
							case when length(at40_descr) > 50 
								then substr(at40_descr,1,50)||'...' ||' - '|| at40_progresso ||'%' 
								else at40_descr ||' - '|| at40_progresso ||'%'
							end || ' - ' || at36_data || '/' || at36_hora as at40_descr 
							from tarefa 
              left join tarefa_lanc on at40_sequencial = at36_tarefa and at36_tipo = 'I'
							inner join tarefasyscadproced on at40_sequencial= at37_tarefa 
							inner join tarefaclientes on at70_tarefa = at40_sequencial 
							where at70_cliente = $at01_codcli 
									and at40_progresso <> 100
									and at37_syscadproced = $codproced
									order by at40_sequencial desc) as aaa
							union 
              select * from (
							select at40_sequencial,
							case when length(at40_descr) > 50 
								then substr(at40_descr,1,50)||'...' ||' - '|| at40_progresso ||'%' 
								else at40_descr ||' - '|| at40_progresso ||'%'
							end || ' - ' || at36_data || '/' || at36_hora as at40_descr 
							from tarefa 
              left join tarefa_lanc on at40_sequencial = at36_tarefa and at36_tipo = 'I'
							inner join tarefasyscadproced on at40_sequencial= at37_tarefa 
							inner join tarefaclientes on at70_tarefa = at40_sequencial 
							where at70_cliente = $at01_codcli 
									and at40_progresso = 100
									and at37_syscadproced = $codproced
							order by at40_sequencial desc
							limit 3 ) as a) as x
							order by at40_sequencial desc
  				";
//  				die($sqltarefa);
					$resulttarefa = pg_query($sqltarefa);
					$linhastarefa = pg_num_rows($resulttarefa);
					if ($linhastarefa>0){
					  db_selectrecord('at40_sequencial',$resulttarefa,true,$db_opcao,"","","","0-Nenhum","js_disablebotao(this.value);");
				?>
				<input name="pesquisar" type="button" value="Consultar tarefa" onclick = "js_pesquisa_tarefa(document.form1.at40_sequencial.value);" disabled>
				<?
				    }else{
					  $resulttarefa = pg_query("select 0 as at40_sequencial,'Nenhuma' as at40_descr");
				    
					  db_selectrecord('at40_sequencial',$resulttarefa,true,$db_opcao,"","","","0-Nenhum","js_disablebotao(this.value);");
				    
				    }
				}else{
					  $resulttarefa = pg_query("select 0 as at40_sequencial,'Nenhuma'as at40_descr");
				    
					  db_selectrecord('at40_sequencial',$resulttarefa,true,$db_opcao,"","","","","js_disablebotao(this.value);");
				    
				}
				?>
			</td>
      <tr>
      <td colspan="2">
      <strong>Atividade/Projeto:</strong>
  <?
  db_selectrecord('at64_sequencial',$cldb_projetosativcli->sql_record($cldb_projetosativcli->sql_query(null,"at64_sequencial,trim(nomemod)||'-'||substr(at64_descricao,1,50) as at64_descricao","at64_sequencial",null)),true,$db_opcao,"","","","0");
  ?>
      </td>
      </td>
		</tr>
		<tr align=left >
		    <td align=center nowrap colspan=2>
		    	<? 
		    	if ($opcao=="incluir"){?>
		    		<input name="<?=$opcao?>" type="submit"  value="Incluir" onclick="js_limpa();" <?=($db_botao==false?"disabled":"") ?> >	
		    	<?
				}elseif($opcao=="alterar"){?>
		    		<input name="<?=$opcao?>" type="submit"  value="Alterar" <?=($db_botao==false?"disabled":"") ?> >
		    	<?
		    	}else{?>
		    		<input name="<?=$opcao?>" type="submit"  value="Excluir" <?=($db_botao==false?"disabled":"") ?> >
		    	<?
		    	}
		    	
		    	?>
		    	<input name="fechar" type="submit"  value="Fechar atendimento" <?=$disabled?> > 
		  		
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr align=left >
		    <td colspan=2>


   <?
   $sql="select * from atenditem 
inner join atendimento on atendimento.at02_codatend = atenditem.at05_codatend 
inner join clientes on clientes.at01_codcli = atendimento.at02_codcli 
inner join tipoatend on tipoatend.at04_codtipo = atendimento.at02_codtipo 
left join atenditemtarefa on  at18_atenditem = at05_seq
where atenditem.at05_codatend = ".@$at02_codatend 
;
  // echo "<br>$sql<br>";
  
  // $clatenditem->sql_query(null,"*","","at05_codatend = $at02_codatend");
  
    $chavepri= array("at05_seq"=>@$at05_seq,"at05_codatend"=>@$at05_codatend);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->campos="at05_seq,at05_codatend,at05_solicitado,at05_feito,at18_tarefa,at05_horaini,at05_horafim";
    $cliframe_alterar_excluir->sql= $sql;
    $cliframe_alterar_excluir->legenda="Ítens do atendimento";
    $cliframe_alterar_excluir->msg_vazio ="<font size='1'>Nenhum Registro Encontrado!</font>";
    $cliframe_alterar_excluir->textocabec ="darkblue";
    $cliframe_alterar_excluir->textocorpo ="black";
    $cliframe_alterar_excluir->fundocabec ="#aacccc";
    $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
    $cliframe_alterar_excluir->iframe_height ="120";
    $cliframe_alterar_excluir->iframe_width ="100%";
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);    
   ?> 
  			
	
		    </td>
		</tr>
	
	</table>
	</center>
</form>
<script>
function js_disablebotao(val){
   if(val == 0){
     document.form1.pesquisar.disabled=true;
   }else{
     document.form1.pesquisar.disabled=false;
   } 
}

function js_pesquisa(){
	js_OpenJanelaIframe('top.corpo','db_iframe_atend','func_atendimentoinc.php?opcao=<?=$opcao?>&funcao_js=parent.js_preenchepesquisa|at02_codatend','Pesquisa',true);
  	document.form1.opcao.value=<?=$opcao?>;	
}
function js_preenchepesquisa(chave){
  	<?
  	if($db_opcao!=1||$db_opcao!=2) {
		echo " db_iframe_atend.hide();";
	  	echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&opcao=".$opcao."'";
  	}
	?>
}
function js_verifica(){
	document.form1.trocamodulo.value='t';
	document.form1.submit();
	
}
function js_limpa(){
	
}

function js_pesquisa_tarefa (tarefa){
	js_OpenJanelaIframe('','db_iframe_tarefa_cons','ate2_contarefa001.php?menu=false&chavepesquisa='+tarefa,'Pesquisa',true);
}

function js_pesquisa_tarefa_outras (){
   js_OpenJanelaIframe('','db_iframe_tarefa_cons_outra','func_contarefa_procedimento.php?codver=0&codmod='+document.form1.modulo.value+'&codcliente=<?=@$at01_codcli?>&codprocedimento='+document.form1.codproced.value+'&codusuario=<?=@$at10_usuario_ori?>','Pesquisa Procedimentos',true);
}

function js_pesquisa_menus (codcli,codver,codusuario){
   js_OpenJanelaIframe('','db_iframe_tarefa_menu','func_contarefa_menu.php?codvercli='+codver+'&codver='+document.form1.at67_codver.value+'&cliente='+codcli+'&cod_usuario='+codusuario,'Pesquisa Menus',true);
}

</script>