<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: patrim
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clpcorcamforne->rotulo->label();
$clpcorcamfornelic->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("l20_codigo");
if(isset($db_opcaoal)){
   $db_opcao=33;
   $db_botao=true;
   $op=1;
}else if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
    $op=1;
}else if(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    $db_botao=true;
    $op=1;
    $rsCgm = $clpcorcamfornelic->sql_record($clpcorcamfornelic->sql_query($pc21_orcamforne));
    if ($clpcorcamfornelic->numrows > 0){
      
      db_fieldsmemory($rsCgm, 0);
    }
}else{  
    $db_opcao = 1;
    if(isset($novo) || isset($verificado) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
     $pc21_orcamforne = "";
     $pc21_numcgm = "";
     $z01_nome = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<br>
<table border="0">
<tr>
    <td  align="right" nowrap title="<?=@$Tl20_codigo?>">
       <b>Licitação :
             </b>
    </td>
    <td> 
<?
db_input('solic',40,"",true,'hidden',3);
db_input('l20_codigo',8,$Il20_codigo,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td  align="right" nowrap title="<?=@$Tpc21_orcamforne?>">
       <?=@$Lpc21_orcamforne?>
    </td>
    <td> 
<?
db_input('pc21_orcamforne',8,$Ipc21_orcamforne,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap title="<?=@$Tpc21_codorc?>">
       <?=@$Lpc21_codorc?>
    </td>
    <td> 
<?
db_input('pc20_codorc',8,$Ipc21_codorc,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td align="right"  nowrap title="<?=@$Tpc21_numcgm?>">
       <?
       db_ancora(@$Lpc21_numcgm,"js_pesquisapc21_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('pc21_numcgm',8,$Ipc21_numcgm,true,'text',$db_opcao," onchange='js_pesquisapc21_numcgm(false);'")
?>
<?
db_input('z01_nome',40,$Iz01_nome,true,'text',3);
?>
    </td>
  </tr>
  <tr>
    <tr>
      <td align="right"  nowrap title="<?=@$Tpc31_liclicitatipoempresa?>">
        <?=@$Lpc31_liclicitatipoempresa?>
      </td>
      <td>
         <?
           $sSqlTipoEmpresas = $oDaoTipoEmpresa->sql_query(null,"*","l32_sequencial");
           $rsTipoEmpresa    = $oDaoTipoEmpresa->sql_record($sSqlTipoEmpresas);
           db_selectrecord("pc31_liclicitatipoempresa",$rsTipoEmpresa,true,$db_opcao);
         ?>
      </td>
    </tr>
  </tr>
  <tr>
    <td align="right" nowrap title="<?=@$Tl22_dtretira?>">
       <?=@$Lpc31_dtretira?>
    </td>
    <td> 
<?
$pc31_dtretira_dia=date('d',db_getsession("DB_datausu"));
$pc31_dtretira_mes=date('m',db_getsession("DB_datausu"));
$pc31_dtretira_ano=date('Y',db_getsession("DB_datausu"));
db_inputdata("pc31_dtretira",@$pc31_dtretira_dia,@$pc31_dtretira_mes,@$pc31_dtretira_ano,true,'text',$db_opcao);
?> 
<?=@$Lpc31_horaretira?>
     <?
     $pc31_horaretira=db_hora();
     db_input('pc31_horaretira',8,$Ipc31_horaretira,true,'text',$db_opcao,""); ?>
    </td>
  </tr>
  <tr>
	  <td align="right" nowrap title="<?=@$Tpc31_nomeretira?>">
	     <?=@$Lpc31_nomeretira?> 
	  </td>
	  <td nowrap title="<?=@$Tpc31_nomeretira?>">
         <?
//         db_input('pc31_nomeretira',50,$Ipc31_nomeretira,true,'text',$db_opcao,"");
           db_input('pc31_nomeretira',50,"",true,'text',$db_opcao,"");
	     ?>
	  </td>
	  
	  </tr>
  
  <tr>
    <td colspan="2" align="center">
     <?
      
      $sWhere = "1!=1";
      if (isset($pc20_codorc) && !empty($pc20_codorc)) {
        $sWhere = "pc22_codorc=".@$pc20_codorc;
      }
      $result_itens = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_file(null,"pc22_codorc","",$sWhere));
      
      if($clpcorcamitem->numrows>0){
        
        if(!empty($pc20_codorc)) {

          $result_forne = $clpcorcamforne->sql_record($clpcorcamforne->sql_query_file(null,"pc21_codorc","","pc21_codorc=".@$pc20_codorc));
        
          if($clpcorcamforne->numrows>0){
            echo "<input name='gera'    type='submit' id='gera'    value='Gerar relatório' onclick='js_gerarel();' ".($db_botao==false?"disabled":"").">&nbsp;";        	
            echo "<input name='lancval' type='button' id='lancval' value='Lançar valores'  onclick='top.corpo.document.location.href=\"lic1_orcamlancval001.php?l20_codigo=$l20_codigo&pc20_codorc=$pc20_codorc\"' ".($db_botao==false?"disabled":"").">";   
          }
        }	
     // $result_sugersol = $clpcsugforn->sql_record($clpcsugforn->sql_query_solsugforne(null," z01_numcgm "));
      }
     ?>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
      <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    </td>
  </tr>
</table>
<table>
  <tr>
    <td valign="top"  align="center">  
     <?
	 $chavepri= array("pc21_orcamforne"=>@$pc21_orcamforne,"pc21_codorc"=>@$pc21_codorc);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 
	 $sWhere     = "1!=1";
	 if (isset($pc20_codorc) && !empty($pc20_codorc)) {
    $sWhere = " pc21_codorc=".@$pc20_codorc;
	 }
	 
	 $cliframe_alterar_excluir->sql     = $clpcorcamforne->sql_query(null,"pc21_orcamforne,pc21_codorc,pc21_numcgm,z01_nome","",$sWhere);  
	 $cliframe_alterar_excluir->campos  ="pc21_orcamforne,pc21_numcgm,z01_nome";
	 $cliframe_alterar_excluir->legenda="FORNECEDORES LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
	 $cliframe_alterar_excluir->opcoes ="3";
 	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
     ?>
    </td>
   </tr>
</table>
</center>
</form>
<script>
function js_gerarel(){
	<?if (isset($solic)&&$solic!=""){?>
  solic = <?=@$solic?>;
  pc20_codorc = document.form1.pc21_codorc.value;
  if(solic==true){
    jan = window.open('com2_solorc002.php?pc20_codorc='+pc20_codorc,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');  
  }else{
    jan = window.open('com2_procorc002.php?pc20_codorc='+pc20_codorc,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');  
  }
  jan.moveTo(0,0);
	<?}?>
}
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisapc21_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','func_nome','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
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
}
function js_mostracgm1(chave1,chave2){
  document.form1.pc21_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  func_nome.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_liclicita','func_liclicita.php?tipo=1&funcao_js=parent.js_preenchepesquisa|l20_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_liclicita.hide();
  <?
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  ?>
}
document.getElementById("pc31_liclicitatipoempresa").style.width      = '5em';
document.getElementById("pc31_liclicitatipoempresadescr").style.width = '20em';
</script>