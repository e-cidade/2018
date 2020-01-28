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
$clrotulo->label("y80_codsani");
$clrotulo->label("z01_nome");
?>
<script>
function js_verifica(){ 
  inscr=new Number(document.form1.q07_inscr.value);
  if(inscr=="" || inscr=='0'|| isNaN(inscr)==true){
     alert('Verifique a inscrição');
     return false;
  4}
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
<table border="0">
  <tr>   
    <td title="<?=$Ty80_codsani?>" >
    <?
      if(isset($y80_codsani) &&$y80_codsani!=""){
         $result_nome=$clsaniatividade->sql_record($clsaniatividade->sql_query($y80_codsani,'',"z01_nome"));
         if ($clsaniatividade->numrows!=0){
       db_fieldsmemory($result_nome,0);
     }
      }
     db_ancora($Ly80_codsani,' js_inscr(true); ',1);
    ?>
    </td>    
    <td title="<?=$Ty80_codsani?>" colspan="4">
    <?
     db_input('y80_codsani',5,$Iy80_codsani,true,'text',3,"");
     isset($y80_codsani)?$inscricao=$y80_codsani:"";
     db_input('inscricao',5,$Iy80_codsani,true,'hidden',1);
     db_input('z01_nome',50,0,true,'text',3);
    ?>
    </td>
  </tr>




   <? 
     if (isset($oParfiscal->y32_calcvistanosanteriores) && $oParfiscal->y32_calcvistanosanteriores == 't'){
        $sStyle = "";
     }else{
        $sStyle = "style='display:none'";
     } 
   ?> 
  <tr id='anocalculo' <?=$sStyle?> >
       <td height="25" title="" align="right">
         <b>Ano de calculo : </b>
       </td>
       <td height="25">
       <?
          $rsAnosCalculo = $clcissqn->sql_record($clcissqn->sql_query_file(null,"q04_anousu","q04_anousu desc"," q04_anousu <= ".db_getsession('DB_anousu')));
          $aAnos = array();
          for ($i=0; $i < $clcissqn->numrows; $i++) {
            $oAnos = db_utils::fieldsMemory($rsAnosCalculo,$i);
            $aAnos[$oAnos->q04_anousu] = $oAnos->q04_anousu;
          }
          
          db_select('anoini', $aAnos, true, 2,"");
        ?>
       </td>
     </tr>



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
    if(isset($y80_codsani) &&$y80_codsani!=""){
        $sql = $clsaniatividade->sql_query($y80_codsani,null,"*","y80_codsani","y83_codsani=$y80_codsani and  y83_dtfim is  null");
        $res = pg_query($sql);
        $lin = pg_num_rows($res);
        if ($lin==0){
          echo "<div><b> Nenhuma das atividades cadastradas possui calculo de sanitário. </b></div><br>";
        }else{
          echo "<script>  document.form1.calcular.disabled=false; </script>";
        }
        $cliframe_seleciona->sql= $sql;
	// echo "<script>js_habilitacalculo(); </script>";
      }

      $cliframe_seleciona->campos  = "y83_codsani,y83_seq,y83_ativ,q03_descr,y83_dtini,y83_dtfim";
      $cliframe_seleciona->legenda="ATIVIDADES EM FUNCIONAMENTO";
      $cliframe_seleciona->textocabec ="darkblue";
      $cliframe_seleciona->textocorpo ="black";
      $cliframe_seleciona->fundocabec ="#aacccc";
      $cliframe_seleciona->fundocorpo ="#ccddcc";
      $cliframe_seleciona->iframe_height ="250";
      $cliframe_seleciona->iframe_width ="700";
      $cliframe_seleciona->iframe_nome ="atividades";
      $cliframe_seleciona->chaves ="y83_seq";
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