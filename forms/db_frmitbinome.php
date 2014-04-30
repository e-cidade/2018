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

//MODULO: ITBI
$clitbinome->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("it01_guia");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
if(isset($opcao) && $opcao == "alterar"){
  echo "<script>parent.iframe_transm.location.href='itb1_itbinome002.php?chavepesquisa=$it03_guia&chavepesquisa1=$it03_seq&db_opcao=2&db_botao=true'</script>";
}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>parent.iframe_transm.location.href='itb1_itbinome003.php?chavepesquisa=$it03_guia&chavepesquisa1=$it03_seq&db_opcao=3&db_botao=true'</script>";
}

//MODULO: itbi
$clitbinomecgm->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("it21_numcgm");
$clrotulo->label("z01_nome");


?>

<table align="center" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>
    
			<fieldset>
			<legend>
			  <b>Transmitentes</b>
			</legend>
			<table border="0" align="center">
			  <tr>
			    <td nowrap title="<?=@$Tit21_numcgm?>">
			       <?
			       db_ancora(@$Lit21_numcgm,"js_pesquisait21_numcgm(true);",$db_opcao);
			       ?>
			    </td>
			    <td nowrap colspan="3"> 
			<?
			db_input('it21_numcgm',14,$Iit21_numcgm,true,'text',$db_opcao," onchange='js_pesquisait21_numcgm(false);'")
			?>
			       <?
			db_input('z01_nome',46,$Iz01_nome,true,'text',3,'')
			       ?>
			    </td>
			
			</tr>
			<tr>
			  
			    <td nowrap title="<?=@$Tit03_guia?>">
			       <?
			       db_ancora(@$Lit03_guia,"js_pesquisait03_guia(true);",3);
			       ?>
			    </td>
			    <td> 
			<?
			db_input('it03_guia',14,$Iit03_guia,true,'text',3," onchange='js_pesquisait03_guia(false);'")
			?>
			<?
			db_input('it03_seq',10,$Iit03_seq,true,'hidden',3,"");
			db_input('it03_seq',8, $Iit03_seq,true,'hidden',$db_opcao,"","it03_seq_old");
			if($db_opcao == 2){
			  echo "<script>document.form1.it03_seq_old.value='$it03_seq'</script>";
			}
			?>
			       &nbsp;&nbsp;&nbsp;<?=@$Lit03_nome?>
			       </td>
			       <td>
			<?
			db_input('it03_nome',40,$Iit03_nome,true,'text',$db_opcao,"")
			?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Tit03_cpfcnpj?>">
			       <?=@$Lit03_cpfcnpj?>
			    </td>
			    <td> 
			<?
			db_input('it03_cpfcnpj',14,$Iit03_cpfcnpj,true,'text',$db_opcao,"  onBlur='js_verificaCGCCPF(this)' onKeyDown='return js_controla_tecla_enter(this,event);' onKeyUp='js_limpa(this)' ")
			?>
			<script>
			function js_limpa(obj){
			  x = obj.value;
			  y = x.replace('.','');
			  y = y.replace('/','');
			  y = y.replace('-','');
			  document.form1.it03_cpfcnpj.value = y;
			}
			</script>
			       &nbsp;&nbsp;&nbsp;<?=@$Lit03_endereco?>
			       </td>
			       <td>
			<?
			db_input('it03_endereco',40,$Iit03_endereco,true,'text',$db_opcao,"")
			?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Tit03_numero?>">
			       <?=@$Lit03_numero?>
			    </td>
			    <td> 
			<?
			db_input('it03_numero',14,$Iit03_numero,true,'text',$db_opcao,"")
			?>
			       &nbsp;&nbsp;&nbsp;<?=@$Lit03_bairro?>
			       </td>
			       <td>
			<?
			db_input('it03_bairro',40,$Iit03_bairro,true,'text',$db_opcao,"")
			?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Tit03_cxpostal?>">
			       <?=@$Lit03_cxpostal?>
			    </td>
			    <td> 
			<?
			db_input('it03_cxpostal',14,$Iit03_cxpostal,true,'text',$db_opcao,"")
			?>
			       &nbsp;&nbsp;&nbsp;<?=@$Lit03_compl?>
			       </td>
			       <td>
			<?
			db_input('it03_compl',20,$Iit03_compl,true,'text',$db_opcao,"")
			?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Tit03_munic?>">
			       <?=@$Lit03_munic?>
			    </td>
			    <td> 
			<?
			db_input('it03_munic',40,$Iit03_munic,true,'text',$db_opcao,"")
			?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Tit03_mail?>">
			       <?=@$Lit03_mail?>
			    </td>
			    <td nowrap>
			<?
			db_input('it03_mail',40,$Iit03_mail,true,'text',$db_opcao,"")
			?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Tit03_uf?>">
			       <?=@$Lit03_uf?>
			    </td>
			    <td nowrap> 
			<?
			db_input('it03_uf',2,$Iit03_uf,true,'text',$db_opcao,"")
			?>
			       <?=@$Lit03_cep?>
			<?
			db_input('it03_cep',8,$Iit03_cep,true,'text',$db_opcao,"")
			?>
			    </td>
			  </tr>
			  <tr>
			    <td nowrap title="<?=@$Tit03_princ?>">
			       <?=@$Lit03_princ?>
			    </td>
			    <td> 
			    <?
			    //                           die ($clitbinome->sql_query_file(null,"*",null," it03_guia = $it03_guia and it03_princ = 't' and upper(it03_tipo) = 'T'"));
			    if (!isset($it03_guia) or trim($it03_guia)=='') {
			      $it03_guia = 'NULL';
			    }
			    $result = $clitbinome->sql_record($clitbinome->sql_query_file(null,"*",null," it03_guia = $it03_guia and it03_princ = 't' and upper(it03_tipo) = 'T'"));
			    if($clitbinome->numrows > 0 && $db_opcao == 1){
			      $x = array("f"=>"NAO");
			    }else{
			      $x = array("t"=>"SIM","f"=>"NAO");
			    }
			    db_select('it03_princ',$x,true,$db_opcao,"");
			    ?>
			    </td>
			   <tr>
			    <td nowrap title="<?=@$Tit03_sexo?>">
			       <?=@$Lit03_sexo?>
			    </td>
			     <td> 
			       <?
			       $x = array('m'=>'Masculino','f'=>'Feminino');
			       db_select('it03_sexo',$x,true,$db_opcao,"");
			       ?>
			     </td>
			  </tr>
			
			
			    <tr>
			      <td colspan="3" align="center">
			    
			  <input name="bt_opcao" type="submit" id="bt_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"<?=($db_botao==false?"disabled":"")?> >
			  <input name="novo" type="button" id="novo" value="novo" onclick= 'js_novo()' >
			      </td>
			    </tr>
			    <tr>
			      <td align="top" colspan="4">
			     <?
			      $chavepri= array("it03_guia"=>@$it03_guia,"it03_seq"=>@$it03_seq);
			      $sql = $clitbinome->sql_query_file(null,"*",null," it03_guia = $it03_guia and upper(it03_tipo) = 'T'");

			      $cliframe_alterar_excluir->chavepri=$chavepri;
			      $cliframe_alterar_excluir->campos        = "it03_guia,it03_seq,it03_nome,it03_princ";
			      $cliframe_alterar_excluir->sql           = $sql;
			      $cliframe_alterar_excluir->legenda       = "Transmitentes";
			      $cliframe_alterar_excluir->msg_vazio     = "<font size='1'>Nenhum transmitente Cadastrado!</font>";
			      $cliframe_alterar_excluir->textocabec    = "darkblue";
			      $cliframe_alterar_excluir->textocorpo    = "black";
			      $cliframe_alterar_excluir->fundocabec    = "#aacccc";
			      $cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
			      $cliframe_alterar_excluir->iframe_width  = "100%";
			      $cliframe_alterar_excluir->iframe_height = "170";
			      $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);    
			      
			      
			     $re = $clitbinome->sql_record($clitbinome->sql_query_file(null,"*",null," it03_guia = $it03_guia and upper(it03_tipo) = '".@$tiponome."'"));   //$clitbinome->sql_query("","","*",""," it03_guia = $it03_guia"));
			     if($clitbinome->numrows > 0){
			          echo "<script>parent.document.formaba.constr.disabled = false</script>";
			     }
			   ?>
			   </td>
			 </tr>  
			</table>
			</fieldset>    
    
    </td>
  </tr>
</table>

<script>
function js_novo(){

  document.form1.it21_numcgm.value ='';
  document.form1.z01_nome.value =''; 
  document.form1.it03_nome.value ='';
  document.form1.it03_cpfcnpj.value ='';
  document.form1.it03_endereco.value ='';
  document.form1.it03_numero.value ='';
  document.form1.it03_bairro.value ='';
  document.form1.it03_cxpostal.value ='';
  document.form1.it03_compl.value ='';
  document.form1.it03_munic.value ='';
  document.form1.it03_mail.value ='';
  document.form1.it03_uf.value ='';
  document.form1.it03_cep.value ='';

  parent.iframe_transm.location.href='itb1_itbinome001.php?it03_guia='+document.form1.it03_guia.value;
}


function js_pesquisait03_guia(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_compnome','db_iframe_itbi','func_itbi.php?funcao_js=parent.js_mostraitbi1|it01_guia|it01_guia','Pesquisa',true);
  }else{
     if(document.form1.it03_guia.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_compnome','db_iframe_itbi','func_itbi.php?pesquisa_chave='+document.form1.it03_guia.value+'&funcao_js=parent.js_mostraitbi','Pesquisa',false);
     }else{
        document.form1.it01_guia.value = ''; 
     }
  }
}
function js_mostraitbi(chave,erro){
  document.form1.it01_guia.value = chave; 
  if(erro==true){ 
    document.form1.it03_guia.focus(); 
    document.form1.it03_guia.value = ''; 
  }
}
function js_mostraitbi1(chave1,chave2){
  document.form1.it03_guia.value = chave1;
  document.form1.it01_guia.value = chave2;
  db_iframe_itbi.hide();
}
function js_mostraitbinomecgm(chave,erro){
  document.form1.it21_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.it21_itbinome.focus(); 
    document.form1.it21_itbinome.value = ''; 
  }
}
function js_mostraitbinomecgm1(chave1,chave2){
  document.form1.it21_itbinome.value = chave1;
  document.form1.it21_numcgm.value = chave2;
  db_iframe_itbinomecgm.hide();
}
function js_pesquisait21_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome&testanome=1','Pesquisa',true);
  }else{
     if(document.form1.it21_numcgm.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.it21_numcgm.value+'&funcao_js=parent.js_mostracgm&testanome=1','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
//  parent.iframe_transm.location.href='itb1_itbinome001.php?mostraitbinomecgm=t&it21_numcgm='+document.form1.it21_numcgm.value;

}

function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.it21_numcgm.focus(); 
    document.form1.it21_numcgm.value = ''; 
  }
  parent.iframe_transm.location.href='itb1_itbinome001.php?mostraitbinomecgm=t&it21_numcgm='+document.form1.it21_numcgm.value+'&it03_guia='+document.form1.it03_guia.value;
}

function js_mostracgm1(chave1,chave2){
  document.form1.it21_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
  parent.iframe_transm.location.href='itb1_itbinome001.php?mostraitbinomecgm=t&it21_numcgm='+chave1+'&z01_nome='+chave2+'&it03_guia='+document.form1.it03_guia.value;
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_itbinome','func_itbinome.php?funcao_js=parent.js_preenchepesquisa|it03_seq|it03_guia','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_itbinome.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>