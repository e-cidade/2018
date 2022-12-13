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

include("classes/db_gerfcom_classe.php");
$clgerfcom = new cl_gerfcom;
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
$clrotulo->label("pc21_numcgm");
$clrotulo->label("z01_nome");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

?>
<table align="center">
  <form name="form1" method="post" action="" onsubmit="return js_verifica();">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left" nowrap title="Digite o Ano / Mes de competência" >
    <strong>Ano / Mês :&nbsp;&nbsp;</strong>
    </td>
    <td>
      <?
       if(!isset($DBtxt23) || (isset($DBtxt23) && (trim($DBtxt23) == "" || $DBtxt23 == 0))){
         $DBtxt23 = db_anofolha();
       }
       db_input('DBtxt23',4,$IDBtxt23,true,'text',2,'')
      ?>
      &nbsp;/&nbsp;
      <?
       if(!isset($DBtxt25) || (isset($DBtxt23) && (trim($DBtxt25) == "" || $DBtxt25 == 0))){
         $DBtxt25 = db_mesfolha();
       }
       db_input('DBtxt25',2,$IDBtxt25,true,'text',2,'')
      ?>
    </td>
  </tr>
  <tr>
    <td><b>Ponto:</b</td>
    <td>
     <?
       $x = array("s"=>"Salário","c"=>"Complementar","d"=>"13o. Salário","r"=>"Rescisão","a"=>"Adiantamento");
       db_select('ponto',$x,true,4,"onchange='document.form1.submit();'");
     ?>
    </td>
     </tr>
     <?
     if(isset($ponto) && $ponto == "c"){
       $result_semest = $clgerfcom->sql_record($clgerfcom->sql_query_file($DBtxt23,$DBtxt25,null,null,"distinct r48_semest as seq"));
       if($clgerfcom->numrows > 0){
	 echo "
	  <tr>
	    <td align='left' title='Nro. Complementar'><strong>Nro. Complementar:</strong></td>
            <td>
	      <select name='rh40_sequencia' onChange='document.form1.submit();'>
		<option value = '0'>Todos
	      ";
	      for($i=0; $i<$clgerfcom->numrows; $i++){
		db_fieldsmemory($result_semest, $i);
    $selected = "";
    if (isset($rh40_sequencia) && trim($rh40_sequencia) != ""){
      if ($rh40_sequencia == $seq){
        $selected = "SELECTED";
      }
    }
		echo "<option value = '$seq' $selected>$seq";
	      }
	 echo "
	    </td>
	  </tr>
	      ";
       }else{
         echo "
               <tr>
                 <td colspan='2' align='center'>
                   <font color='red'>Sem complementar para este período.</font>
                 </td>
               </tr>
              ";
       }
     }
     ?>
  <tr>
    <td><b>Gerado por:</b></td>
    <td>
    <?
       $x = array("G"=>"Geral","O"=>"Orgão","U"=>"Unidade");
       db_select("gerado",$x,true,4,"");
    ?>
    </td>
  </tr>
  <tr>
    <td><b><? db_ancora("Fornecedor: ","js_pesquisapc21_numcgm(true);",4) ?></b></td>
    <td> 
<?
db_input('pc21_numcgm',8,$Ipc21_numcgm,true,'text',4," onchange='js_pesquisapc21_numcgm(false);'")
?>
<?
db_input('z01_nome',40,$Iz01_nome,true,'text',3);
?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center" height="50"> 
      <input  name="gera" id="gera" type="submit" value="Processar" onsubmit="js_verifica();">
    </td>
  </tr>
  </form>
</table>
</body>
<script>
function js_verifica(){
<?
  if (!isset($mostra)){
    $mostra = false;
}

  db_gerarsolicitacao($mostra);
?>
}
function js_pesquisapc21_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','func_nome','func_nome.php?testanome=true&funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.pc21_numcgm.value != ''){
        js_OpenJanelaIframe('top.corpo','func_nome','func_nome.php?pesquisa_chave='+document.form1.pc21_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.pc21_numcgm.focus();
    document.form1.pc21_numcgm.value = '';
  }

  if(erro==false){
    document.form1.submit();
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.pc21_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  func_nome.hide();

  document.form1.submit();
}
</script>  
</html>