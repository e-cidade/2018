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

//MODULO: tributario
$clisencao->rotulo->label();
$clisencaoproc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("v11_descr");
$clrotulo->label("nome");
if($db_opcao==1){
  $db_action="tri1_isencao004.php?origem=$origem&valorigem=$valorigem";
}else if($db_opcao==2||$db_opcao==22){
  $db_action="tri1_isencao005.php?origem=$origem&valorigem=$valorigem";
}else if($db_opcao==3||$db_opcao==33){
  $db_action="tri1_isencao006.php?origem=$origem&valorigem=$valorigem";
}  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
<tr>
	<?
//	db_msgbox("$origem -- $valorigem");
		if(!isset($origem) || $origem == ''){
			$sqlBuscaOrigem  = " select case ";
			$sqlBuscaOrigem .= "          when v12_isencao is not null then '1' ";
			$sqlBuscaOrigem .= "          when v15_isencao is not null then '3' ";
			$sqlBuscaOrigem .= "          when v16_isencao is not null then '2' ";
			$sqlBuscaOrigem .= "        end as origem, ";
			$sqlBuscaOrigem .= "        case ";
			$sqlBuscaOrigem .= "          when v12_isencao is not null then v12_numcgm ";
			$sqlBuscaOrigem .= "          when v15_isencao is not null then v15_matric ";
			$sqlBuscaOrigem .= "          when v16_isencao is not null then v16_inscr ";
			$sqlBuscaOrigem .= "        end as valorigem, ";
			$sqlBuscaOrigem .= "        case ";
			$sqlBuscaOrigem .= "          when v15_isencao is not null then (select z01_nome from iptubase inner join cgm on z01_numcgm = j01_numcgm where j01_matric = v15_matric limit 1 )";
			$sqlBuscaOrigem .= "          when v12_isencao is not null then (select z01_nome from cgm where z01_numcgm = v12_numcgm limit 1 )";
			$sqlBuscaOrigem .= "          when v16_isencao is not null then (select z01_nome from issbase inner join cgm on q02_numcgm = z01_numcgm where q02_inscr = v16_inscr limit 1 )";
			$sqlBuscaOrigem .= "        end as mostranome ";
			$sqlBuscaOrigem .= "   from isencao ";
			$sqlBuscaOrigem .= "    left join isencaomatric on v15_isencao = v10_sequencial ";
			$sqlBuscaOrigem .= "    left join isencaocgm    on v12_isencao = v10_sequencial ";
			$sqlBuscaOrigem .= "    left join isencaoinscr  on v16_isencao = v10_sequencial ";
			$sqlBuscaOrigem .= " where v10_sequencial = ".($v10_sequencial!=""?$v10_sequencial:$chavepesquisa);
//			echo $sqlBuscaOrigem; 
      if ($v10_sequencial != '' || $chavepesquisa != '') {
			  $rsBuscaOrigem = pg_query($sqlBuscaOrigem);
			  if(pg_numrows($rsBuscaOrigem) > 0){
				  db_fieldsmemory($rsBuscaOrigem,0);
		  	}
			}
		}else if($origem != '' && $valorigem != ''){
      if($origem == 1){
	  		$sqlNome = "select z01_nome as mostranome from cgm where z01_numcgm = $valorigem limit 1 ";
	  	}else if($origem == 2){
	  		$sqlNome = "select z01_nome as mostranome from issbase inner join cgm on q02_numcgm = z01_numcgm where q02_inscr = $valorigem limit 1 ";
		  }else if($origem == 3){
		  	$sqlNome = "select z01_nome as mostranome from iptubase inner join cgm on z01_numcgm = j01_numcgm where j01_matric = $valorigem limit 1 ";
	  	}
//			echo $sqlNome;
      $rsNome = pg_query($sqlNome);
		  $intNumrows = pg_numrows($rsNome);
		  if($intNumrows > 0){
  	    db_fieldsmemory($rsNome,0);
	  	}else{
				db_msgbox('Campo de pesquisa não encontrado !');
			  echo "<script> parent.document.location.href = 'tri4_cadisencaoalt001.php?origemmenu=2'; </script>";
				exit;
			}
		}
//		echo $origem;
    if($origem == 1){
			echo "<td>";
		  db_ancora("<b>Numcgm</b>","",3);
			echo "</td>";
			echo "<td>";
		  db_input('valorigem',10,'',true,'text',3,"");
		  db_input('mostranome',40,'',true,'text',3,"");
			echo "</td>";
    }else if($origem == 2){
			echo "<td>";
		  db_ancora("<b>Inscrição</b>","",3);
			echo "</td>";
			echo "<td>";
		  db_input('valorigem',10,'',true,'text',3,"");
		  db_input('mostranome',40,'',true,'text',3,"");
			echo "</td>";
    }else if($origem == 3){
			echo "<td>";
		  db_ancora("<b>Matrícula</b>","",3);
			echo "</td>";
			echo "<td>";
		  db_input('valorigem',10,'',true,'text',3,"");
		  db_input('mostranome',40,'',true,'text',3,"");
			echo "</td>";
    }
	?>
</tr>
  <tr>
    <td nowrap >
    </td>
    <td> 
<?
db_input('v10_sequencial',10,$Iv10_sequencial,true,'hidden',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv10_isencaotipo?>">
       <?
       db_ancora(@$Lv10_isencaotipo,"js_pesquisav10_isencaotipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v10_isencaotipo',10,$Iv10_isencaotipo,true,'text',$db_opcao," onchange='js_pesquisav10_isencaotipo(false);'")
?>
       <?
db_input('v11_descr',40,$Iv11_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv17_protprocesso?>">
       <?
       db_ancora(@$Lv17_protprocesso,"js_pesquisav17_protprocesso(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v17_protprocesso',10,$Iv17_protprocesso,true,'text',$db_opcao," onchange='js_pesquisav17_protprocesso(false);'")
?>
       <?
db_input('p58_codproc',40,$Ip58_codproc,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tv10_dtisen?>">
       <?=@$Lv10_dtisen?>
    </td>
    <td> 
<?
db_inputdata('v10_dtisen',@$v10_dtisen_dia,@$v10_dtisen_mes,@$v10_dtisen_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisav17_protprocesso(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_isencao','db_iframe_protprocesso','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|z01_nome','Pesquisa',true,'0');
  }else{
     if(document.form1.v17_protprocesso.value != ''){ 
       js_OpenJanelaIframe('top.corpo.iframe_isencao','db_iframe_protprocesso','func_protprocesso.php?pesquisa_chave='+document.form1.v17_protprocesso.value+'&funcao_js=parent.js_mostraprotprocesso','Pesquisa',false,'0');
     }else{
       document.form1.p58_codproc.value = ''; 
     }
  }
}
function js_mostraprotprocesso(chave,chave1,erro){
  document.form1.p58_codproc.value = chave1; 
  if(erro==true){ 
    document.form1.v17_protprocesso.focus(); 
    document.form1.v17_protprocesso.value = ''; 
  }
}
function js_mostraprotprocesso1(chave1,chave2){
//	alert(chave1+' - '+chave2);
  document.form1.v17_protprocesso.value = chave1;
  document.form1.p58_codproc.value = chave2;
  db_iframe_protprocesso.hide();
}
function js_pesquisav10_isencaotipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_isencao','db_iframe_isencaotipo','func_isencaotipo.php?funcao_js=parent.js_mostraisencaotipo1|v11_sequencial|v11_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.v10_isencaotipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_isencao','db_iframe_isencaotipo','func_isencaotipo.php?pesquisa_chave='+document.form1.v10_isencaotipo.value+'&funcao_js=parent.js_mostraisencaotipo','Pesquisa',false,'0');
     }else{
       document.form1.v11_descr.value = ''; 
     }
  }
}
function js_mostraisencaotipo(chave,erro){
  document.form1.v11_descr.value = chave; 
  if(erro==true){ 
    document.form1.v10_isencaotipo.focus(); 
    document.form1.v10_isencaotipo.value = ''; 
  }
}
function js_mostraisencaotipo1(chave1,chave2){
  document.form1.v10_isencaotipo.value = chave1;
  document.form1.v11_descr.value = chave2;
  db_iframe_isencaotipo.hide();
}
function js_pesquisav10_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_isencao','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true,'0');
  }else{
     if(document.form1.v10_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_isencao','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.v10_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false,'0');
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.v10_usuario.focus(); 
    document.form1.v10_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.v10_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_isencao','db_iframe_isencao','func_isencaoalt.php?funcao_js=parent.js_preenchepesquisa|v10_sequencial','Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_isencao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>