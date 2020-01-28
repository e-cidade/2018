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
$clrotulo = new rotulocampo;
$clrotulo = new rotulocampo;
$clrotulo->label("q07_inscr");
$clrotulo->label("z01_nome");
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
  for(i=0; i<obj.length; i++){
     if(obj[i].type=='checkbox'){
       if(obj[i].checked==true){
          id=obj[i].id.substr(6);     
          marcado=true; 
       }
     }
  }
  if(!marcado){
    alert('Selecione uma atividade!');
    return false;
  }
  return  js_gera_chaves();
}
function js_habilitacalculo(){
  document.form1.calcular.disabled=false; 
  document.form1.calcular.disabled=''; 
  document.form1.calcular.enabled=true; 
}
</script>
<form name="form1" method="post" action="">
<center>


<table border="0">
  <tr>
    <td align="center"> 
    
    
    
<fieldset style="margin-top: 20px;">
<legend><b>Inscrição</b></legend>
<table border="0">
  <tr>   
    <td title="<?=$Tq07_inscr?>" >
    <?
     db_ancora($Lq07_inscr,' js_inscr(true); ',1);
    ?>
    </td>    
    <td title="<?=$Tq07_inscr?>" colspan="4">
    <? 
     $z01_nome = stripslashes($z01_nome);
     db_input('q07_inscr',8,$Iq07_inscr,true,'text',3,"");
     isset($q07_inscr)?$inscricao=$q07_inscr:"";
     db_input('inscricao',5,$Iq07_inscr,true,'hidden',1);
     db_input('z01_nome',50,0,true,'text',3);
    ?>
    </td>
  </tr>
  <tr>
    <TD><strong>Tipo de Cálculo:</strong></TD>
    <TD>
                 
     <?php
       $aTipos  = array(
         "0" => "Todos ",
         "1" => "ISSQN ",
         "2" => "Alvará"
       );
       db_select("iTipoCalculo", $aTipos, false, 1);
     ?>
   </TD>
  </tr>  
  
  
</table>
 </fieldset>   
<table border="0">  
  <tr>
    <td colspan="3" align="center">
       <input name="calcular" type="submit" onclick="return js_verifica();" id="db_opcao" value="Calcular" disabled >
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
      $cliframe_seleciona->campos  = "q07_inscr,q07_seq,q88_inscr,q03_descr,q07_datain,q07_datafi,q07_databx,q07_perman,q07_quant,q11_tipcalc, q81_descr";
      $cliframe_seleciona->legenda="ATIVIDADES EM FUNCIONAMENTO";
      if(isset($q07_inscr) &&$q07_inscr!=""){
         //$cliframe_seleciona->sql=$cltabativ->sql_query_atividade_inscr($q07_inscr,"*","q07_seq","q07_inscr = $q07_inscr and q07_databx is null and q07_datafi is null");
         $cliframe_seleciona->sql=$cltabativ->sql_query_atividade_inscr($q07_inscr,"*","q07_seq","q07_inscr = $q07_inscr and q07_datain <= '" . date("Y-m-d", db_getsession("DB_datausu")) . "' and (q07_datafi is null or q07_datafi >= '" . date("Y-m-d", db_getsession("DB_datausu")) . "') and (q07_databx is null or q07_databx >= '" . date("Y-m-d", db_getsession("DB_datausu")) . "')");
	       echo "<script>js_habilitacalculo(); </script>";
      }
      $cliframe_seleciona->textocabec ="darkblue";
      $cliframe_seleciona->textocorpo ="black";
      $cliframe_seleciona->fundocabec ="#aacccc";
      $cliframe_seleciona->fundocorpo ="#ccddcc";
      $cliframe_seleciona->iframe_height ="250";
      $cliframe_seleciona->iframe_width ="700";
      $cliframe_seleciona->iframe_nome ="atividades";
      $cliframe_seleciona->chaves ="q07_seq";
      $cliframe_seleciona->checked =true;
      $cliframe_seleciona->iframe_seleciona($db_opcao);    

?>


    </td>
  </tr>
  </table>
  

  
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
</script>