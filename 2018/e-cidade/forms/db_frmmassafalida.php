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

//MODULO: cadastro
$clmassafalida->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("j01_matric");
?>
<script>
var array_massafalida = new Array();
function selmatric(matric){
  var testa = false;
   nummassafalida=document.form1.nummassafalida.value;

   var matric =  new Number(matric);
   
   tamanho=document.form1.selmassafalida.options.length;
   for(i=0;i<tamanho;i++){ 
      if(matric == document.form1.selmassafalida.options[i].text){
	testa=true;
        break;
      }
   } 
   if(testa==true){
     alert("Matrícula  já cadastrado!!!");
   }else{
     array_massafalida[nummassafalida] = matric;
     var option = document.createElement("option");
     option.text = matric;
     option.value = nummassafalida;
     document.form1.selmassafalida.backgroundcolor="blue";
     document.form1.selmassafalida.options.add(option);
     document.form1.nummassafalida.value++;
     js_trocacordeselect();
    if(document.form1.matriculas.value==""){
      var x="";
    }else{
      var x="#";
    }  
     document.form1.matriculas.value += x+matric;
     
   }  
}


function js_selexclui(matricu,valormatr){
 opt = document.form1.selmassafalida.options;
 tamanho=opt.length;
 for(i=0;i<tamanho;i++){
  if(opt[i].text == matricu){
     opt[i]=null;
      break;
   }
 }
    document.form1.matriculas.value=""; 
    tamanho=opt.length;
    for(i=0;i<tamanho;i++){
      if(document.form1.matriculas.value==""){
        var x="";
      }else{
        var x="#";
      }  
      document.form1.matriculas.value += x+document.form1.selmassafalida.options[i].text;
    }  
  js_trocacordeselect();
}
function js_iframe(valormatr){
 document.form1.selmassafalida.disabled=true;
      
 opt = document.form1.selmassafalida.options;
 tamanho=opt.length;
 for(i=0;i<tamanho;i++){
  if(opt[i].text == array_massafalida[valormatr]){
    matricu=opt[i].text;
    iframe_matric.js_excluirmatri(matricu,valormatr);  
    break;
   }
 }
}


</script>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td> 
      <input name="nummassafalida" type="hidden" value="0">  
      <input name="matriculas" type="hidden" value="<?=@$matriculas?>">  
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj58_codigo?>">
       <?=@$Lj58_codigo?>
    </td>
    <td> 
<?
db_input('j58_codigo',10,$Ij58_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj58_data?>">
       <?=@$Lj58_data?>
    </td>
    <td> 
<?
db_inputdata('j58_data',@$j58_data_dia,@$j58_data_mes,@$j58_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj58_numcgm?>">
       <?
       db_ancora(@$Lj58_numcgm,"js_pesquisaj58_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j58_numcgm',10,$Ij58_numcgm,true,'text',$db_opcao," onchange='js_pesquisaj58_numcgm(false);'")
?>
       <?
db_input('z01_nome',30,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj58_obs?>">
       <?=@$Lj58_obs?>
    </td>
    <td> 
<?
db_textarea('j58_obs',0,0,$Ij58_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>


  <tr>
    <td align="left" colspan="2" >
    <table border="1"> 
    <tr>
      <td colspan="3" align="left"><b>Matrículas de Massas Falidas</b>
      </td>
    </tr>
    <tr>
      <td align="left" valign="top">
        <?
	echo $Lj01_matric;
	?>
	<br>
        <iframe name="iframe_matric" style="background:red" frameborder="0" marginwidth="0" leftmargin="0" topmargin="0" src="verifica.php?db_botao=<?=$db_botao?>"  height="37" scrolling="no"  width="75">
        </iframe><br>
      </td>
      <td rowspan="6" valign="top" align="center" width="120">
        <select id="selmassafalida" style="background:#cccccc;border-color:red;" name="selmassafalida" size="6"  onchange="js_iframe(this.value)" <?=($db_opcao==3?"disabled":"")?>  >                       
	<?
        /// db_select("selmassafalida","","true",$db_opcao,"onchange='js_iframe(this.value)'","","#cccccc");
	?>  
  	
        </select>
       </td>  	
    </tr>  
    </table>
    </td>
  </tr>




  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaj58_numcgm(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_nome.php?funcao_js=parent.js_mostracgm1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_nome.php?pesquisa_chave='+document.form1.j58_numcgm.value+'&funcao_js=parent.js_mostracgm';
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.j58_numcgm.focus(); 
    document.form1.j58_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.j58_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_massafalida.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>

<script>
/*
function js_tiraoption(valor){
 opt = document.form1.selmassafalida.options;
 tamanho=opt.length;
 for(i=0;i<tamanho;i++){
  if(document.form1.selmassafalida.options[i].text == array_massafalida[valor]){
     document.form1.selmassafalida.options[i]=null;
      break;
   }
 }
   
 tamanho=opt.length;
  document.form1.matriculas.value=""; 
 for(i=0;i<tamanho;i++){
  if(document.form1.matriculas.value==""){
    var x="";
  }else{
    var x="#";
  }  
  document.form1.matriculas.value += x+document.form1.selmassafalida.options[i].text;
 }  
}
*/
</script>