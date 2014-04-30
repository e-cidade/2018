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
?>
<script> 	
  function js_verificaid(valor){
     num=(document.form1.selid.options.length)-1;   
    for(i=1;i<=num;i++){
      selid=document.form1.selid.options[i].value;   
      if(valor==selid){ 
        alert("Construção já cadastrada!");
        document.form1.j39_idcons.value="";
        document.form1.j39_idcons.focus();
        return false;  
      break;   
      }  
   }
   if(document.form1.caracteristica.value=="X" || document.form1.caracteristica.value==""){
     alert("Informe as caracteristicas!");
        return false;  
   }
 }
<?if(isset($j39_matric)){?>
  function js_trocaid(valor){
    id_setor=document.form1.id_setor2.value;          
    id_quadra=document.form1.id_quadra2.value;          
   location.href="cad1_iptuconstralt.php?id_setor2="+id_setor+"&id_quadra2="+id_quadra+"&j39_matric=<?=$j39_matric?>&j39_idcons="+valor+"&z01_nome="+document.form1.z01_nome.value;
  } 
<?}?>

function js_testacar(){
   if(document.form1.caracteristica.value=="X" || document.form1.caracteristica.value==""){
     alert("Informe as caracteristicas!");
     return false;  
   }
}
</script> 	
      <table border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td>     
            <input type="hidden" name="j39_dtlan_dia" value="<?=$j39_dtlan_dia?>">
            <input type="hidden" name="j39_dtlan_mes" value="<?=$j39_dtlan_mes?>">
            <input type="hidden" name="j39_dtlan_ano" value="<?=$j39_dtlan_ano?>">
            <input type="hidden" name="id_setor2" value="<?=@$id_setor2?>">
            <input type="hidden" name="id_quadra2" value="<?=@$id_quadra2?>">

           <?=$Lj39_matric?>
          </td>
          <td> 
          <?
           db_input('j39_matric',5,0,true,'text',3,"onchange='js_matri(false)'");
           db_input('z01_nome',45,0,true,'text',3,"");
          ?>
          </td>
        </tr>
        <tr> 
          <td>     
           <?=$Lj39_idcons?>*
          </td>
          <td> 
<?
  db_input('j39_idcons',5,$Ij39_idcons,true,'text',$db_opcaoid,"");
?>
	 </td>
        
          <td rowspan="8" valign="top">
            <table border="0" cellpadding="0" cellspacing="0">
              <tr><td><b>Construções já Cadastradas</b></td></tr> 
              <tr>
                <td align="center">  
<?
if(isset($j39_matric)){
  if(!isset($incluir)){
    $result = $cliptuconstr->sql_record($cliptuconstr->sql_query_file($j39_matric,"","j39_idcons","",""));
  }
  $num=$cliptuconstr->numrows;
  if($num!=""){  
    echo "<select name='selid' onchange='js_trocaid(this.value)'  size='".($num>7?8:($num+1))."'>";
    echo "<option value='nova' ".(!isset($j39_idcons)?"selected":"").">Nova</option>"; 
    $idcons=$j39_idcons;  
    $testasel=true;
    for($i=0;$i<$num;$i++){  
      db_fieldsmemory($result,$i);
      if($j39_idcons!=$idcons){ 
        echo "<option  value='".$j39_idcons."' ".($j39_idcons==$idcons?"selected":"").">$j39_idcons</option>";         
      } 
    }
  } 
}
?> 
                </td>
              </tr>
            </table>     
          </td>
        </tr>
        <tr> 
          <td>          
           <?=$Lj39_ano?>
          </td>
          <td> 
<?
  db_input('j39_ano',5,$Ij39_ano,true,'text',1,"");
?>
          </td>
        </tr>
        <tr> 
          <td>     
           <?=$Lj39_area?>
          </td>
          <td> 
<?
  db_input('j39_area',5,$Ij39_area,true,'text',1,"");
?>
          </td>
        </tr>
        <tr> 
          <td>     
           <?=$Lj39_areap?>
          </td>
          <td> 
<?
  db_input('j39_areap',5,$Ij39_areap,true,'text',1,"");
?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tj39_codigo?>">
<?
  db_ancora(@$Lj39_codigo,"js_pesquisaj39_codigo(true);",$db_opcao);
?>
          </td>
          <td> 
<?
  db_input('j39_codigo',5,$Ij39_codigo,true,'text',$db_opcao," onchange='js_pesquisaj39_codigo(false);'");
  db_input('j14_nome',40,$Ij14_nome,true,'text',3,'');
?>
          <td>
        <tr>
        <tr> 
          <td>     
           <?=$Lj39_numero?>
          </td>
          <td> 
<?
  db_input('j39_numero',5,$Ij39_numero,true,'text',1,"");
?>
          </td>
        </tr>
        <tr> 
          <td>     
           <?=$Lj39_compl?>
          </td>
          <td> 
<?
  db_input('j39_compl',5,$Ij39_compl,true,'text',1,"");
?>
          </td>
        </tr>
        <tr>
          <td>
            <b>
<?
  db_ancora("Características","js_mostracaracteristica();",1);
?>
            </b> 
          </td>
          <td> 
<?
  db_input('caracteristica',15,1,true,'hidden',1,"")
?>
          <td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tj39_dtdemo?>">
          <?=@$Lj39_dtdemo?>
          </td>
          <td> 
<?
db_inputdata('j39_dtdemo',@$j39_dtdemo_dia,@$j39_dtdemo_mes,@$j39_dtdemo_ano,true,'text',$db_opcao,"")
?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tj39_idaument?>">
          <?=@$Lj39_idaument?>
          </td>
          <td> 
<?
db_input('j39_idaument',6,$Ij39_idaument,true,'text',$db_opcao,"")
?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tj39_idprinc?>">
          <?=@$Lj39_idprinc?>
          </td>
          <td>
          <?
	    if(isset($j39_matric) && $num > 0){
	      $x = array("f"=>"Construção Secundária","t"=>"Constução Principal");
	    }else{
	      $x = array("t"=>"Construção Principal","f"=>"Constução Secundária");
	    }
            db_select('j39_idprinc',$x,true,$db_opcao,"");
	  ?>
	  </td>
	</tr>
	<tr>
	  <td colspan="2" align="center">
	  <br>
             <input name="<?=($db_botao==1?"incluir":"alterar")?>" type="submit" value="<?=($db_botao==1?"Incluir":"Alterar")?>" <?=($testasel==true?"onclick=\"return js_verificaid(document.form1.j39_idcons.value)\"":"onclick=\"return js_testacar()\"")?> >
          </td>
        </tr>
	<tr>
	  <td colspan="2" align="left">
	  <br><br>
	    *(caso o campo não seja preenchido, o código será gerado automaticamente)
          </td>
        </tr>
      </table>
<script>
function js_matri(mostra){
  var matri=document.form1.j39_matric.value;
  if(mostra==true){
    db_iframe.jan.location.href = 'func_iptubase.php?funcao_js=parent.js_mostra|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_iptubase.php?pesquisa_chave='+matri+'&funcao_js=parent.js_mostra1';
  }
}
function js_mostra(chave1,chave2){
  document.form1.j39_matric.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
function js_mostra1(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.j39_matric.focus(); 
    document.form1.j39_matric.value = ''; 
  }
}

function js_mostracaracteristica(){
  caracteristica=document.form1.caracteristica.value;
   if(caracteristica!=""){
     db_iframe.jan.location.href = 'cad1_cargeral001.php?db_opcao=<?=$db_opcao?>&caracteristica='+caracteristica+'&tipogrupo=C';
   }else{
    db_iframe.jan.location.href = 'cad1_cargeral001.php?db_opcao=<?=$db_opcao?>&tipogrupo=C&codigo='+document.form1.j39_idcons.value;
   }
    db_iframe.setTitulo('Pesquisa Caracteristica');
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
}
function js_pesquisaj39_codigo(mostra){
idsetor=document.form1.id_setor2.value;
idquadra=document.form1.id_quadra2.value;

  if(mostra==true){
    db_iframe.jan.location.href = 'func_ruasconstr.php?idsetor='+idsetor+'&idquadra='+idquadra+'&funcao_js=parent.js_mostraruas1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_ruasconstr.php?idsetor='+idsetor+'&idquadra='+idquadra+'&pesquisa_chave='+document.form1.j39_codigo.value+'&funcao_js=parent.js_mostraruas';
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.j39_codigo.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe.hide();
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave; 
  if(erro==true){ 
    document.form1.j39_codigo.focus(); 
    document.form1.j39_codigo.value = ''; 
  }
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=1;
$func_iframe->largura=780;
$func_iframe->altura=390;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>