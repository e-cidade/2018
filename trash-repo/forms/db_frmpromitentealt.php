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
?>
<script>
function js_verizero(){
  j41_numcgm = new Number(document.form1.j41_numcgm.value);
  z01_nome = document.form1.z01_nome.value;
  if(isNaN(j41_numcgm) || j41_numcgm=="0" || z01_nome=="C�digo () n�o Encontrado" ){
    alert("Verifique o campo com o numero da Matr�cula!");
    document.form1.j41_numcgm.focus();
    return false;
  }
  return true;
}
function js_trocaid(valor){
  <?
  if(isset($j41_matric) && $j41_matric!=""){
  ?>
    location.href="cad1_promitentealt.php?j41_matric=<?=$j41_matric?>&j41_numcgm="+valor;
  <?
  }else{
  ?>
    location.href="cad1_promitentealt.php?j41_matric="+document.form1.j41_matric.value+"&j41_numcgm="+valor;
  <?
  }
  ?>
}
</script>

<fieldset>
  <legend><b>Promitentes do im�vel</b></legend>

<table border="0" width="790">
  <tr>
    <td nowrap title="<?=@$Tj41_matric?>">
      <?=@$Lj41_matric?>
    </td>
    <td> 
<?
db_input('j41_matric',10,$Ij41_matric,true,'text',3,"");
db_input("z01_nome",78,$Ij01_numcgm,true,"text",3,"","z01_nomematri");
?>
        </td>
      </tr>
      <tr> 
        <td nowrap title="<?=@$Tj41_numcgm?>">
<?
db_ancora($Lj41_numcgm,' js_cgm(true); ',$db_opcao==2?"3":"1");
?>
        </td>
        <td> 
<?
db_input('j41_numcgm',10,$Ij41_numcgm,true,'text',$db_opcao==2?"3":"1","onchange='js_cgm(false)'");
db_input('z01_nome',78,$Iz01_nome,true,'text',3,"");
?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj41_promitipo?>">
          <?=@$Lj41_promitipo?>
        </td>
        <td> 
<?

$x = array("C"=>"Com contrato","S"=>"Sem contrato");
db_select('j41_promitipo',$x,true,$db_opcao,"");
?>
       </td>
     </tr>
     <tr>
       <td nowrap title="<?=@$Tj41_tipopro?>">
         <?=@$Lj41_tipopro?>
       </td>
       <td> 
        <?
        if ($outros == true) {
          $xs = array("f"=>"Secund�rio","t"=>"Principal");
        }else{
          $xs = array("t"=>"Principal","f"=>"Secund�rio");	
        }
        db_select('j41_tipopro',$xs,true,$db_op,"");
        ?>
        </td>
      </tr>
      <TR><TD colspan="2" align="center">
      <?
        if($outros==true){
          $result = $clpromitente->sql_record($clpromitente->sql_query($j41_matric,"","promitente.*#cgm.z01_nome", "j41_tipopro desc"));
            $num = $clpromitente->numrows;
          echo "<select name='lista' onchange='js_trocaid(this.value)' size='".($num>10?10:($num)+1)."'>";
          $xx="";
          $cgmpromi="";
          for($i=0;$i<$num;$i++){
            db_fieldsmemory($result,$i);
            if($j41_numcgm!=$numcgm || isset($incluir) || isset($alterar) || isset($excluir)){
              if($j41_tipopro == 't'){
                $z01_nome .= " *";
        }		
        echo "<option  value='".$j41_numcgm."'>$z01_nome</option>";
        }
        $cgmpromi.=$xx.$j41_numcgm;
        $xx="#";
        }
        if(!isset($recol)){
          echo "</select>";
          echo "<script>";  
          echo "  function js_prime(){"; 
          echo "    document.form1.j41_tipopro.options[0].selected=true;";
          echo "  }";	
          echo "  js_prime();";
          echo "</script>";
        }  
        }
      ?> 
      </TD></TR>
      <tr><td><input name="cgmpromi" type="hidden" value="<?=@$cgmpromi?>"> </td></tr>
  </table>  

</fieldset>

<br />

<input name="incluir" type="submit" id="incluir" value="Incluir" <?=($db_opcao!=1?"disabled":"")?> onclick="return js_verizero()" >
<input name="alterar" type="submit" id="alterar" value="Alterar" <?=($db_opcao==1?"disabled":"")?> onclick="return js_verizero()" >
<input name="excluir" type="submit" id="excluir" value="Excluir" <?=($db_opcao==1?"disabled":"")?> <?=($db_op02==3?"disabled":"")?> >


<script>
function js_cgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_promitente','func_nome','func_nome.php?funcao_js=parent.js_mostra1|0|1&testanome=true','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_promitente','func_nome','func_nome.php?pesquisa_chave='+document.form1.j41_numcgm.value+'&funcao_js=parent.js_mostra','Pesquisa',false,0);
  }
}
function js_mostra1(chave1,chave2){
  document.form1.j41_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  func_nome.hide();
}
function js_mostra(erro,chave){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.j41_numcgm.focus();
    document.form1.j41_numcgm.value="";
  }
}
</script>