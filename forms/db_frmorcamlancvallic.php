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

$clpcorcam->rotulo->label();
$clpcorcamforne->rotulo->label();
$clpcorcamval->rotulo->label();
$clrotulo = new rotulocampo;
?>
<script>
function js_trocalote(orcamforne){
  var index = document.form1.l04_descricao.selectedIndex;

  if (document.form1.l04_descricao.options[index].value != "Todos"){
       document.form1.l04_descricao.options[index].selected = true;
       var lote = document.form1.l04_descricao.options[index].value;
  }

  location.href = 'lic1_orcamlancval001.php?pc20_codorc=<?=$pc20_codorc?>&lic=<?=$lic?>&l20_codigo=<?=$l20_codigo?>&pc21_orcamforne='+orcamforne+'&l04_descricao='+lote;
  document.form1.submit();
}
function js_dalocation(valor){
  location.href = 'lic1_orcamlancval001.php?pc20_codorc=<?=$pc20_codorc?>&lic=<?=$lic?>&l20_codigo=<?=$l20_codigo?>&pc21_orcamforne='+valor;
  document.form1.submit();
}
function js_buscarcod(){
  retorno = "";
  erro0 = 0;
  erro1 = 0;
  obj = elementos.document.form1;
  for(i=0;i<obj.elements.length;i++){
    if(obj.elements[i].name.substr(0,6)=="valor_"){
      valor=obj.elements[i].value;
      //var valor=new Number(obj.elements[i].value);
      retorno+=obj.elements[i].name+"_"+valor;	
      erro0++;
    }
  }
  document.form1.valores.value = retorno;
  
  retorno = "";
  for(i=0;i<obj.elements.length;i++){
    if(obj.elements[i].name.substr(0,6)=="vlrun_"){
      //var valor=new Number(obj.elements[i].value);
      valor=obj.elements[i].value;
      retorno+=obj.elements[i].name+"_"+valor;	
      erro0++;
    }
  }
  document.form1.valoresun.value = retorno;

  retorno = "";
  for(i=0;i<obj.elements.length;i++){
    if(obj.elements[i].name.substr(0,5)=="qtde_"){
      var valor=new Number(obj.elements[i].value);
      retorno+=obj.elements[i].name+"_"+valor;
      erro1++;
    }
  }
  document.form1.qtdades.value = retorno;
  
  retorno = "";
  for(i=0;i<obj.elements.length;i++){
    if(obj.elements[i].name.substr(0,11)=="qtdeOrcada_"){
      var valor=new Number(obj.elements[i].value);
      retorno+=obj.elements[i].name+"_"+valor;
      erro1++;
    }
  }
  document.form1.qtdadesOrcadas.value = retorno;


  retorno = "";
  ifen = "";
  div = "#";
  for(i=0;i<obj.elements.length;i++){
    if(obj.elements[i].name.substr(0,14)=="pc23_validmin_"){
			 valor    = obj.elements[i].value;
			 str      = obj.elements[i].name.length - 3;
			 objDt    = obj.elements[i].name.substring(str);
       arr_info = obj.elements[i].name.split("_");
			 if ((objDt == 'dia' || objDt == 'mes' || objDt == 'ano') && valor != ''){
          retorno+=div+ifen+obj.elements[i].value;
          ifen="-";
       if (arr_info[3]=="ano"){
       	ifen="";
        div="#";
       }else{
       div="";
       }     
		 }
    }
  }  
  document.form1.dataval.value = retorno;  
  
  retorno = "";
  for(i=0;i<obj.elements.length;i++){
    if(obj.elements[i].name.substr(0,4)=="obs_"){
      valor=obj.elements[i].value;
      if(valor!=""){
	for(ii=0;ii<valor.length;ii++){
	  if(valor.substr(ii,1)==" "){
	    valor = valor.replace(" ","yw00000wy");
	  }
	}
        retorno+=obj.elements[i].name+"_"+valor;
      }else{
        retorno+= 'obs_';
      }
      erro1++;
    }
  }
  document.form1.obss.value = retorno;
  /*
  if(erro0!=erro1){
    alert('Usuário: \n\nInforme quantidade e valor dos orçamentos. \n\nAdministrador:'+erro0+' -- '+erro1);
    return false;
  }
  */
  //return false;
  return true;
}
</script>
<form name="form1" method="post">
<center>
<table border="0">
  <tr>
    <td>
      <table border="0">
<?
   if (isset($pc20_codorc) && trim($pc20_codorc) != ""){
?>
        <tr>
          <td nowrap align='right' width="50%" title="<?=@$Tpc20_codorc?>">
	  <?=@$Lpc20_codorc?>
	  </td>
	  <td width="50%" align='left'>
	  <?
	    db_input('pc20_codorc',8,$Ipc20_codorc,true,'text',3,"");
      db_input('valores',40,0,true,'hidden',3,"");
      db_input('valoresun',40,0,true,'hidden',3,"");
      db_input('qtdades',8,0,true,'hidden',3,"");
      db_input('qtdadesOrcadas',8,0,true,'hidden',3,"");
      db_input('obss',8,0,true,'hidden',3,"");
      db_input('lic',6,0,true,'hidden',3,"");
      db_input('lc20_codigo',6,0,true,'text',3,"");
      db_input('dataval',40,0,true,'hidden',3,"");	  
	  ?>
	  </td>
        </tr>	
	<?

	$voltar = false;
	$result_forne = $clpcorcamforne->sql_record($clpcorcamforne->sql_query(null,"pc21_orcamforne,z01_nome","pc21_orcamforne","pc21_codorc=$pc20_codorc"));
	$numrows_forne = $clpcorcamforne->numrows;
	if($numrows_forne>0){
	  if(!isset($pc21_orcamforne) || (isset($pc21_orcamforne) && trim($pc21_orcamforne)=="")){
	    db_fieldsmemory($result_forne,0);
	  }
          $qry = "";
	  if(isset($pc21_orcamforne) && trim($pc21_orcamforne)!=""){
	    $qry = "&pc21_orcamforne=$pc21_orcamforne";
	    $result_lancados = $clpcorcamval->sql_record($clpcorcamval->sql_query(null,null,"pc23_orcamforne,pc23_orcamitem,pc23_valor","","pcorcam.pc20_codorc=$pc20_codorc and pc21_orcamforne=$pc21_orcamforne"));
        // echo ($clpcorcamval->sql_query(null,null,"pc23_orcamforne,pc23_orcamitem,pc23_valor","","pcorcam.pc20_codorc=$pc20_codorc and pc21_orcamforne=$pc21_orcamforne"));
	    if($clpcorcamval->numrows>0 && $db_opcao!=3 && $db_opcao!=33){
	      $voltar = true;
	      $db_opcao=2;
	      $db_botao=true;
	    }
	  }

	  echo "  <tr>
		    <td nowrap width='50%' align='right' title='$Tpc21_orcamforne'>
		    $Lpc21_orcamforne
		    </td>
		    <td width='50%' align='left'>";
	  db_selectrecord("pc21_orcamforne",$result_forne,true,$db_opcao,"","","","","js_dalocation(document.form1.pc21_orcamforne.value);");
	  echo "    <td>      
		  </tr>";

    db_input("l20_codigo",10,"",true,"hidden",3);     
    $achou         = false;
    $res_liclicita = $clliclicita->sql_record($clliclicita->sql_query_file(@$l20_codigo,"l20_tipojulg"));
    if ($clliclicita->numrows > 0){
         db_fieldsmemory($res_liclicita,0);

         if ($l20_tipojulg == 3) {
              if (isset($l04_descricao)&&trim($l04_descricao)!=""){
                   $descricao = $l04_descricao;
              }
              $res_liclicitemlote = $clliclicitemlote->sql_record($clliclicitemlote->sql_query_licitacao(null,"l04_descricao",null,"l20_codigo = $l20_codigo and l21_situacao = 0"));
              if ($clliclicitemlote->numrows > 0){
                   $numrows = $clliclicitemlote->numrows;
                   $achou   = false;

                   for($i = 0; $i < $numrows; $i++){
                        db_fieldsmemory($res_liclicitemlote,$i);
                        if (trim($l04_descricao)==""){
                             $achou    = true;
                             $db_botao = false;
                             break;
                        }
                   }
              }

              if ($achou == false){
                   $qry .= "&l20_codigo=".$l20_codigo;

                   $res_liclicitemlote = $clliclicitemlote->sql_record($clliclicitemlote->sql_query_licitacao(null,"distinct l04_descricao",null,"l20_codigo = $l20_codigo and l21_situacao = 0"));
                   $numrows            = $clliclicitemlote->numrows;

                   if ($clliclicitemlote->numrows > 0){
?>
          <tr>
            <td nowrap align="right"><b>Lote:</b></td>
            <td nowrap align="left"><select name="l04_descricao" id="l04_descricao" onChange="js_trocalote(document.form1.pc21_orcamforne.value);">
                  <option value="T">Todos</option>
<?
                        for($i = 0; $i < $numrows; $i++){
                             db_fieldsmemory($res_liclicitemlote,$i);

                             if (isset($descricao)&&trim(@$descricao)!=""){
                                  if ($l04_descricao == $descricao){
                                       $selected = "SELECTED";
                                       $qry     .= "&descricao=".$l04_descricao;
                                  } else {
                                       $selected = "";
                                  }
                             } else {
                                  $selected = "";
                             }
?>
                  <option value="<?=$l04_descricao?>" <?=$selected?>><?=$l04_descricao?></option>
<?
                        }
?>
            </select></td>
          </tr>
<?
                   }
              }
         }
    }           
		  ?>
		    <tr>
      <td align='right'><?=$Lpc21_validadorc?>
      
      	
      <?
      if(isset($pc21_orcamforne) && trim($pc21_orcamforne)!=""){
      $result_data = $clpcorcamforne->sql_record($clpcorcamforne->sql_query(null,"pc21_validadorc,pc21_prazoent","pc21_orcamforne","pc21_codorc=$pc20_codorc and pc21_orcamforne=$pc21_orcamforne"));
      	if ($clpcorcamforne->numrows>0){
      		db_fieldsmemory($result_data,0);
      	}
      }
       
      db_inputdata("pc21_validadorc",@$pc21_validadorc_dia,@$pc21_validadorc_mes,@$pc21_validadorc_ano,true,"text",$db_opcao); ?></td>
      
      <td><?=$Lpc21_prazoent?>
      <?db_inputdata("pc21_prazoent",@$pc21_prazoent_dia,@$pc21_prazoent_mes,@$pc21_prazoent_ano,true,"text",$db_opcao); ?></td>
      </tr>
		  <?
	  echo "
		    </td>
		  </tr>";

	  echo "  <tr>
		    <td align='center' colspan='2'>
		      <iframe name='elementos' id='elementos'  marginwidth='0' marginheight='0' frameborder='0' src='lic1_orcamlancval0011.php?pc20_codorc=$pc20_codorc&db_opcao=$db_opcao".$qry."&lic=".@$lic."' width='1200' height='300'>
		      </iframe>
		    <td>      
		  </tr>\n";
	  echo "  <tr>
              <td colspan='2' height='30'>&nbsp;</td>  
            </tr>\n
            <tr>
		    <td colspan='2' align='center'>
		      <input name='".($db_opcao==1?"incluir":"alterar")."' type='submit' id='db_opcao' value='".($db_opcao==1?"Incluir":"Alterar")."' ".($db_botao==false?"disabled":"")." onclick='return  js_buscarcod();'>
		      <input name='voltar' type='button' id='voltar' value='Voltar'  onclick='document.location.href=\"lic1_lancavallic001.php\"'>
		      <input name='importar' type='button' id='importar' value='Valores unitários'  onclick='elementos.js_importar(true);elementos.js_somavalor();'>
		      <input name='zerar'  type='button' id='zerar' value='Zerar valores'  onclick='elementos.js_importar(false);elementos.js_somavalor();'>
          <input name='bt_descla' type='button' id='bt_descla' value='Desclassificar itens' onClick='elementos.js_abrejan();'>\n";
          
    echo "<input name='cancdescla' type='button' id='cancdescla' value='Cancelar desclassificacao de itens' onClick='elementos.js_cancdescla($pc20_codorc,$l20_codigo);'>\n";
    
	  if($voltar==true){
        echo "<input name='trocar' type='button' id='trocar' value='Julgar licitação' onclick='document.location.href=\"lic1_pcorcamtroca001.php?pc20_codorc=$pc20_codorc&pc21_orcamforne=$pc21_orcamforne&l20_codigo=$l20_codigo\"'>";
	  }
	}else{
	  echo "  <tr>
		    <td align='center' colspan='2'>
		      <br><br>
		      <strong>Não existem itens para este orçamento.</strong>
		      <br><br>
		    <td>      
		  </tr>";
	}
   } else {
	  echo "  <tr>
		    <td align='center' colspan='2'>
		      <br><br>
		      <strong>Não existem fornecedores lançados para esta licitação.</strong>
		      <br><br>
		    <td>      
		  </tr>";

    echo " <tr>
		    <td align='center' colspan='2'>
		      <input name='voltar' type='button' id='voltar' value='Voltar'  onclick='document.location.href=\"lic1_lancavallic001.php\"'>
        </td>
       </tr>"; 
   }
	?>  
      </table>
    </td>
  </tr>
</table>
</center>
</form>