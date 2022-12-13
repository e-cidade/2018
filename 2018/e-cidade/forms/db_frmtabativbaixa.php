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

//MODULO: issqn
include("dbforms/db_classesgenericas.php");
$cliframe_seleciona = new cl_iframe_seleciona;
$cltabativbaixa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("q07_inscr");
$clrotulo->label("q07_databx");
$clrotulo->label("p58_requer");
?>
<script>
function js_verifica(){ 
  inscr=new Number(document.form1.q07_inscr.value);
  if(inscr=="" || inscr=='0'|| isNaN(inscr)==true){
     alert('Verifique a inscrição');
     return false;
  }
  if(inscr!=document.form1.inscricao.value){
     return false;
  }
  obj=atividades.document.getElementsByTagName("INPUT");
  var marcado=false;
  var ok=true; 
  var disponivel=false;
  for(i=0; i<obj.length; i++){
     if(obj[i].type=='checkbox'){
       if(obj[i].checked==true){
          id=obj[i].id.substr(6);     
          radio=atividades.document.getElementById('q88_inscr_'+id);
          if(radio.checked==true && ok==true){
            ok=false;
          } 
          marcado=true; 
       }else{
          disponivel=true; 
       }
     }
  }
  if(ok==false){
    if(disponivel==true){ 
      alert("Para baixar a atividade principal, é preciso selecionar outra  no lugar dela.");
      return false;  
    }
  } 
  processo=document.form1.q11_processo.value;
 
 // if(processo.replace(" ","")==""){
 //   alert("Informe o processo do protocolo!");
 //   processo=document.form1.q11_processo.focus();
 //   return false;
 // }
  if(document.form1.q11_obs.value==""){
    alert("Informe as observacoes!");
    processo=document.form1.q11_obs.focus();
    return false;
  }
  if(!marcado){
    alert('Selecione uma atividade!');
    return false;
  }
  if(document.form1.q07_databx_dia.value=='' || document.form1.q07_databx_mes.value=='' || document.form1.q07_databx_ano.value==""){
     alert('verifique a data de baixa informada!');
     return false;
  }
  if(confirm("Efetuar o calculo para que a proporcionalidade seja calculada?")){
    document.form1.calculo.value="ok";
  }else{
    document.form1.calculo.value="no";
  }
  return js_gera_chaves();
}
</script>
<form name="form1" method="post" action="">
<center>
<fieldset style="margin-top: 20px;">
<legend><b>Baixa de Inscrição</b></legend>
<table border="0">
  <tr>
    <td align="center"> 
<table border="0">
  <tr>   
    <td title="<?=$Tq07_inscr?>" >
    <?
     db_input('calculo',5,0,true,'hidden',1);
     db_ancora($Lq07_inscr,' js_inscr(true); ',1);
    ?>
    </td>    
    <td title="<?=$Tq07_inscr?>" colspan="4">
    <?
     db_input('q07_inscr',5,$Iq07_inscr,true,'text',1,"onchange='js_inscr(false)'");
     isset($q07_inscr)?$inscricao=$q07_inscr:"";
     db_input('inscricao',5,$Iq07_inscr,true,'hidden',1);
    db_input('z01_nome',50,0,true,'text',3);
    ?>
    </td>
  </tr>
    <tr>
      <td nowrap title="<?=@$Tq11_processo?>">
	 <?
       db_ancora(@$Lq11_processo,"js_pesquisaq11_processo(true);",$db_opcao);
       ?>
      </td>
      <td colspan=3> 
  <?
  db_input('q11_processo',10,$Iq11_processo,true,'text',$db_opcao," onchange='js_pesquisaq11_processo(false);'")
  ?>
  <?db_input('p58_requer',40,$Ip58_requer,true,'text',3,'')
  
  ?>
      <td>
    </tr>
  <tr>
    <td nowrap title="<?=@$Tq11_oficio?>">
       <?=@$Lq11_oficio?>
    </td>
    <td valign="top"> 
<?
$xe = array("false"=>"NORMAL","true"=>"OFÍCIO");
db_select('q11_oficio',$xe,true,$db_opcao);
?>

    </td>
    <td nowrap title="<?=@$Tq07_databx?>">
       <?=@$Lq07_databx?>
    </td>
    <td nowrap title="<?=@$Tq07_databx?>" align='left'>
<?
if(empty($q07_databx)){
  $q07_databx_dia=date("d",db_getsession('DB_datausu'));
  $q07_databx_mes=date("m",db_getsession('DB_datausu'));
  $q07_databx_ano=date("Y",db_getsession('DB_datausu'));
}
db_inputdata('q07_databx',@$q07_databx_dia,@$q07_databx_mes,@$q07_databx_ano,true,'text',$db_opcao)
?>
    </td>
  </tr>
  <tr>
  </tr>
    <td nowrap colspan=4 title="<?=@$Tq11_obs?>">
    <?=$Lq11_obs?>    
    <?
    db_textarea('q11_obs',0,70,@$Iq11_obs,true,'text',$db_opcao);
    ?>
    </td>
  <tr>
    <td colspan="4" align="center">
       <input name="baixar" type="submit" onclick="return js_verifica();" id="db_opcao" value="Baixar" <?=($db_botao==false?"disabled":"")?> >
    </td> 
  </tr>
</table>
</td>
</tr>
<tr>
<td>  
  <tr>   
    <td align="center" colspan="2"> 
   <?
     //todos as propiedades alteradas, deverão ser alterada em iss1_tabativbaixaiframe.php
     $query_string = "a=1";
   $campos= "q07_inscr,q07_seq,q88_inscr,q07_ativ,q03_descr,q07_datain,q07_datafi,q07_databx,q07_perman,q07_quant,q11_tipcalc, q81_descr";
   if(isset($q07_inscr) && $q07_inscr!="" && $q07_inscr !=0){
     $query_string.="&q07_inscr=$q07_inscr";  
   }
     $query_string.="&db_opcao=".$db_opcao;
     $chaves="q88_inscr,q07_seq";
     echo "  
           <fieldset><Legend align=\"center\"><b>ATIVIDADES EM FUNCIONAMENTO</b></Legend>
              <iframe id=\"ativ\"  frameborder=\"0\" name=\"atividades\"   leftmargin=\"0\" topmargin=\"0\" src=\"iss1_tabativbaixaiframe.php?".base64_encode($query_string)."\" height=\"250\" width=\"700\">
              </iframe> 
            </fieldset>";
   if(isset($q07_inscr) && $q07_inscr!="" && $q07_inscr !=0){
       echo "<script>";   
################## quando for setado a propriedade chaves, sera gerado um input contendo todas as chaves#################
        if(isset($chaves)){        
           $matriz01=split(",",$chaves);       
           echo "  
          function js_gera_chaves(){
            tabela=atividades.document.getElementById('tabela_seleciona');\n
            var coluna='';\n  
            var sep=''; 
            for(i=1; i<tabela.rows.length; i++){\n
              id=tabela.rows[i].id.substr(6);\n  
              if(atividades.document.getElementById('CHECK_'+id).checked==true){\n";
                echo "coluna+=sep;\n";
                $sep="";
                for($y=0; $y<sizeof($matriz01); $y++){
                  echo  "colu ='$sep'+atividades.document.getElementById('".$matriz01[$y]."_'+i).innerHTML;\n
                         coluna+=colu.replace('&nbsp;','');\n
                  ";
                   $sep="-";
                }
             echo "
                sep='#'; \n 
              }
	    } 
            obj=document.createElement('input');
            obj.setAttribute('name','chaves');
            obj.setAttribute('type','hidden');
            obj.setAttribute('value',coluna);
            document.form1.appendChild(obj);
            return true;
          }
          </script>     
          ";
        } 
     }	   
?>


    </td>
  </tr>
  </table>
  </fieldset>
  </center>
</form>
<script>
function js_inscr(mostra){
  var inscr=document.form1.q07_inscr.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_inscr','func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr|z01_nome','Pesquisa',true);
  }else{
    if(inscr!=""){
      js_OpenJanelaIframe('top.corpo','db_iframe_inscr','func_issbase.php?pesquisa_chave='+inscr+'&funcao_js=parent.js_mostrainscr1','Pesquisa',false);
    }else{
      document.form1.z01_nome.value="";
      document.form1.submit();  
    }
  }
}
function js_mostrainscr(chave1,chave2){
  document.form1.q07_inscr.value = chave1;
  document.form1.z01_nome.value = chave2;
  atividades.location.href="iss1_tabativbaixaiframe.php?q07_inscr="+chave1+"&z01_nome="+chave2;
  document.form1.submit(); 
  db_iframe_inscr.hide();
}
function js_mostrainscr1(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.q07_inscr.focus(); 
    document.form1.q07_inscr.value = ''; 
  }else{
    document.form1.submit();
  }
}
function js_pesquisaq11_processo(mostra){

  if(mostra==true){

    js_OpenJanelaIframe('top.corpo','db_iframe_processo','func_protprocesso.php?funcao_js=parent.js_mostraprocesso1|p58_codproc|p58_requer','Pesquisa',true);
  }else{

     if(document.form1.q11_processo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_processo','func_protprocesso.php?pesquisa_chave='+document.form1.q11_processo.value+'&funcao_js=parent.js_mostraprocesso','Pesquisa',false);
     }else{
       document.form1.q11_processo.value = ''; 
     }
  }
}
function js_mostraprocesso(chave,erro){
  document.form1.q11_processo.value = chave; 
  document.form1.p58_requer.value = erro; 
  
  if(erro==true){ 
    document.form1.q11_processo.focus(); 
    document.form1.q11_processo.value = ''; 
  }
}
function js_mostraprocesso1(chave1,chave2){
  document.form1.q11_processo.value = chave1;
  document.form1.p58_requer.value = chave2;
  db_iframe_processo.hide();
}
</script>