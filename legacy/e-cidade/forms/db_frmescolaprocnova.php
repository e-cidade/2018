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

//MODULO: educação
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clescolaproc->rotulo->label();
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
 if($ed82_c_mantenedora=="MUNICIPAL"){
  $ed82_c_mantenedora = 1;
 }elseif($ed82_c_mantenedora=="ESTADUAL"){
  $ed82_c_mantenedora = 2;
 }elseif($ed82_c_mantenedora=="FEDERAL"){
  $ed82_c_mantenedora = 3;
 }else{
  $ed82_c_mantenedora = 4;
 }
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
 if($ed82_c_mantenedora=="MUNICIPAL"){
  $ed82_c_mantenedora = 1;
 }elseif($ed82_c_mantenedora=="ESTADUAL"){
  $ed82_c_mantenedora = 2;
 }elseif($ed82_c_mantenedora=="FEDERAL"){
  $ed82_c_mantenedora = 3;
 }else{
  $ed82_c_mantenedora = 4;
 }
}else{
 if(isset($alterar)){
  $db_opcao = 2;
  $db_botao1 = true;
 }else{
  $db_opcao = 1;
 }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted82_i_codigo?>">
   <?=@$Led82_i_codigo?>
  </td>
  <td>
   <?db_input('ed82_i_codigo',20,$Ied82_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted82_c_nome?>">
   <?=@$Led82_c_nome?>
  </td>
  <td>
   <?db_input('ed82_c_nome',50,$Ied82_c_nome,true,'text',$db_opcao,"")?>
   <?=@$Led82_c_abrev?>
   <?db_input('ed82_c_abrev',20,$Ied82_c_abrev,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
   <td nowrap title="<?=@$Ted82_pais?>">
     <?=@$Led82_pais?>
   </td>
   <td>
     <?
       $aPais      = array();
       $oDaoPais   = db_utils::getDao("pais");
       $sSqlPais   = $oDaoPais->sql_query_file(null, "ed228_i_codigo, ed228_c_descr","ed228_c_descr");
       $rsPais     = $oDaoPais->sql_record($sSqlPais);
       $iTotalPais = $oDaoPais->numrows;

       if ( $iTotalPais > 0 ) {

         for ($iContador = 0; $iContador < $iTotalPais; $iContador++) {

           $oDadosPais = db_utils::fieldsMemory($rsPais, $iContador);
           $aPais[$oDadosPais->ed228_i_codigo] = $oDadosPais->ed228_c_descr;
         }
         db_select('ed82_pais', $aPais, true, $db_opcao, "onChange='js_verificaPais();'");
       } else {

         $aPais = array(''=>'NENHUM REGISTRO');
         db_select('ed82_pais', $aPais, true, $db_opcao);
       }
     ?>
   </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted82_c_mantenedora?>">
   <?=@$Led82_c_mantenedora?>
  </td>
  <td>
   <?
   $x = array('1'=>'MUNICIPAL','2'=>'ESTADUAL','3'=>'FEDERAL','4'=>'PARTICULAR');
   db_select('ed82_c_mantenedora',$x,true,$db_opcao,"");
   ?>
   <?=@$Led82_c_email?>
   <?db_input('ed82_c_email',80,$Ied82_c_email,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted82_i_cep?>">
   <?=@$Led82_i_cep?>
  </td>
  <td>
   <?db_input('ed82_i_cep',8,$Ied82_i_cep,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted82_i_numero?>">
   <?=@$Led82_c_rua?>
  </td>
  <td>
   <?db_input('ed82_c_rua',50,$Ied82_c_rua,true,'text',$db_opcao,"")?>
   <?=@$Led82_i_numero?>
   <?db_input('ed82_i_numero',10,$Ied82_i_numero,true,'text',$db_opcao,"")?>
  </td>
 </tr>
<tr>
  <td nowrap title="<?=@$Ted82_c_complemento?>">
   <?=@$Led82_c_complemento?>
  </td>
  <td>
   <?db_input('ed82_c_complemento',20,$Ied82_c_complemento,true,'text',$db_opcao,"")?>
   <?=@$Led82_c_bairro?>
   <?db_input('ed82_c_bairro',50,$Ied82_c_bairro,true,'text',$db_opcao,"")?>
  </td>
 </tr>
<tr>
     <td nowrap title="<?=@$Ted82_i_censouf?>">
      <?=@$Led82_i_censouf?>
     </td>
     <td>
      <?
      $result_uf = $clcensouf->sql_record($clcensouf->sql_query_file("","ed260_i_codigo,ed260_c_nome","ed260_c_nome"));
      db_selectrecord("ed82_i_censouf",$result_uf,"","","","","","  ","iframe_ufs.location.href='edu1_escolaproc004.php?censouf='+this.value",1);
      ?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted82_i_censomunic?>">
      <?=@$Led82_i_censomunic?>
     </td>
     <td>
      <?
      if(isset($ed82_i_censouf) && $ed82_i_censouf!=""){

       $result_munic = $clcensomunic->sql_record($clcensomunic->sql_query_file("","ed261_i_codigo,ed261_c_nome","ed261_c_nome","ed261_i_censouf = $ed82_i_censouf"));
       if($clcensomunic->numrows==0){
        $x = array(''=>'Selecione o Estado');
        db_select('ed82_i_censomunic',$x,true,@$db_opcao1,"onchange=\"iframe_ufs.location.href='edu1_escolaproc004.php?censomunic='+this.value\"");
       }else{
        db_selectrecord("ed82_i_censomunic",$result_munic,"","","","","","  ","iframe_ufs.location.href='edu1_escolaproc004.php?censomunic='+this.value",1);
       }
      }else{
       $x = array(''=>'Selecione o Estado');
       db_select('ed82_i_censomunic',$x,true,@$db_opcao1,"onchange=\"iframe_ufs.location.href='edu1_escolaproc004.php?censomunic='+this.value\"");
      }
      ?>
     </td>
    </tr>
     <tr>
     <td>
      <?=@$Led82_i_censodistrito?>
     </td>
     <td>
      <?
      if(isset($ed82_i_censomunic) && $ed82_i_censomunic!=""){
       $result_distrito = $clcensodistrito->sql_record($clcensodistrito->sql_query("","ed262_i_codigo,ed262_c_nome","ed262_c_nome","ed262_i_censomunic = $ed82_i_censomunic AND ed261_i_censouf = $ed82_i_censouf"));
       if($clcensodistrito->numrows==0){
        $x = array(''=>'Selecione a Cidade');
        db_select('ed82_i_censodistrito',$x,true,@$db_opcao1,"");
       }else{
        db_selectrecord("ed82_i_censodistrito",$result_distrito,"","","","","","  ","",1);
       }
      }else{
       $x = array(''=>'Selecione a Cidade');
       db_select('ed82_i_censodistrito',$x,true,@$db_opcao1,"");
      }
      ?>
      <iframe name="iframe_ufs" src="" framedorder="0" width="0" height="0" style="visibility:hidden;position:absolute;"></iframe>
     </td>
    </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table>
 <tr>
  <td valign="top">
   <?
   $campos = "ed82_i_codigo,
              ed82_c_nome,
              ed82_c_abrev,
              case
               when ed82_c_mantenedora=1
                then 'MUNICIPAL'
               when ed82_c_mantenedora=2
                then 'ESTADUAL'
               when ed82_c_mantenedora=3
                then 'FEDERAL' else
               'PARTICULAR'
              end as ed82_c_mantenedora,
              ed82_c_email,
              ed82_c_rua,
              ed82_i_numero,
              ed82_c_complemento,
              ed82_c_bairro,
              ed82_i_cep,
              ed82_i_censouf,
              ed82_i_censomunic,
              ed82_i_censodistrito,
              ed261_c_nome,
              ed260_c_sigla,
              ed262_c_nome,
		          ed82_pais";

  $chavepri = array(
                     "ed82_i_codigo"       => @$ed82_i_codigo,
                     "ed82_c_nome"         => @$ed82_c_nome,
                     "ed82_c_abrev"        => @$ed82_c_abrev,
                     "ed82_c_mantenedora"  => @$ed82_c_mantenedora,
                     "ed82_c_email"        => @$ed82_c_email,
                     "ed82_c_rua"          => @$ed82_c_rua,
                     "ed82_i_numero"       => @$ed82_i_numero,
                     "ed82_c_complemento"  => @$ed82_c_complemento,
                     "ed82_c_bairro"       => @$ed82_c_bairro,
                     "ed82_i_cep"          => @$ed82_i_cep,
                     "ed82_i_censouf"      => @$ed82_i_censouf,
                     "ed82_i_censomunic"   => @$ed82_i_censomunic,
                     "ed82_i_censodistrito"=> @$ed82_i_censodistrito,
                     "ed82_pais"           => @$ed82_pais
                   );


  $ed82_i_codigo = isset($ed82_i_codigo) ? $ed82_i_codigo : '';
  $cliframe_alterar_excluir->chavepri=$chavepri;
	$cliframe_alterar_excluir->sql = $clescolaproc->sql_query(@$ed82_i_codigo,$campos,"ed82_c_nome");
	$cliframe_alterar_excluir->campos  ="ed82_i_codigo,ed82_c_nome,ed82_c_mantenedora,ed261_c_nome,ed260_c_sigla,ed262_c_nome";
	$cliframe_alterar_excluir->legenda="Registros";
	$cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
	$cliframe_alterar_excluir->textocabec ="#DEB887";
	$cliframe_alterar_excluir->textocorpo ="#444444";
	$cliframe_alterar_excluir->fundocabec ="#444444";
	$cliframe_alterar_excluir->fundocorpo ="#eaeaea";
	$cliframe_alterar_excluir->iframe_height ="160";
	$cliframe_alterar_excluir->iframe_width ="100%";
	$cliframe_alterar_excluir->tamfontecabec = 9;
	$cliframe_alterar_excluir->tamfontecorpo = 9;
	$cliframe_alterar_excluir->formulario = false;

  ?>
  </td>
 </tr>
</table>
</form>
<script>
var iDbOpcao = <?=$db_opcao;?>;

function js_cep(abre){
 if(abre == true){
  js_OpenJanelaIframe('top.corpo','db_iframe_cep','func_cep.php?funcao_js=parent.js_preenchecep|cep|cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro','Pesquisa de Ruas',true);
 }else{
  js_OpenJanelaIframe('top.corpo','db_iframe_cep','func_cep.php?pesquisa_chave='+document.form1.ed82_i_cep.value+'&funcao_js=parent.js_preenchecep|cep|cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro','Pesquisa',false);
 }
}
function js_preenchecep(chave,chave1,chave2,chave3,chave4){
 document.form1.ed82_i_cep.value = chave;
 document.form1.ed82_c_rua.value = chave1;
 document.form1.ed82_i_censomunic.value = chave2;
 document.form1.ed82_i_censouf.value = chave3;
 document.form1.ed82_c_bairro.value = chave4;
 db_iframe_cep.hide();
}
function js_valida(){
 Vemail = "<?=@$GLOBALS[Sed82_c_email]?>";
 if(jsValidaEmail(document.form1.ed82_c_email.value,Vemail)==false){
  return false;
 }
 return true;
}

function selecionaPaisInicial() {

  if ( iDbOpcao == 1 ) {
    $('ed82_pais').value = 10;
  }

  js_verificaPais();
}

function js_verificaPais() {

  $('ed82_i_censouf').disabled       = false;
  $('ed82_i_censomunic').disabled    = false;
  $('ed82_i_censodistrito').disabled = false;


  if ( $F('ed82_pais') != 10 ) {

    $('ed82_i_censouf').value                 = ' ';
    $('ed82_i_censouf').style.backgroundColor = '#DEB887';
    $('ed82_i_censouf').disabled              = true;

    $('ed82_i_censomunic').value                 = ' ';
    $('ed82_i_censomunic').style.backgroundColor = '#DEB887';
    $('ed82_i_censomunic').disabled              = true;

    $('ed82_i_censodistrito').value                 = ' ';
    $('ed82_i_censodistrito').style.backgroundColor = '#DEB887';
    $('ed82_i_censodistrito').disabled              = true;
  }else{

	  $('ed82_i_censouf').style.backgroundColor       = '#FFFFFF';
	  $('ed82_i_censomunic').style.backgroundColor    = '#FFFFFF';
	  $('ed82_i_censodistrito').style.backgroundColor = '#FFFFFF';
	}
}

selecionaPaisInicial();
</script>