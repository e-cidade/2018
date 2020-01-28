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

//MODULO: cemiterio
$cllotecemit->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cm22_i_codigo");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cllotecemit->rotulo->label();
if( isset( $cm22_i_qtdlote ) and (int)$cm22_i_qtdlote > 0 ){
   $cllotecemit->sql_record("select * from lotecemit where cm23_i_quadracemit=$cm23_i_quadracemit" );
   if( $cllotecemit->numrows == 0 ){
        db_inicio_transacao();
        for( $x=1; $x<=$cm22_i_qtdlote; $x++ ){
           $cllotecemit->cm23_i_quadracemit = $cm23_i_quadracemit;
           $cllotecemit->cm23_i_lotecemit = $x;
           $cllotecemit->cm23_c_situacao = 'D';
           $cllotecemit->incluir(null);
        }
        db_fim_transacao();
   }
}

$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
}else{
 if(isset($alterar)){
  $db_opcao = 2;
  $db_botao1 = true;
 }else{
  $db_opcao = 1;
 }
}
if(isset($atualizar)){
 $tam = sizeof($campos);
 for($i=0;$i<$tam;$i++){
  $sql = "UPDATE turno SET
           ed15_i_sequencia = ".($i+1)."
          WHERE ed15_i_codigo = $campos[$i]
         ";
  $query = pg_query($sql);
 }
 echo "<script>location.href='".$cllotecemit->pagina_retorno."'</script>";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcm23_i_codigo?>">
       <?=@$Lcm23_i_codigo?>
    </td>
    <td> 
<?
db_input('cm23_i_codigo',10,$Icm23_i_codigo,true,'text',3,"")

?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm23_i_lotecemit?>">
       <?=@$Lcm23_i_lotecemit?>
    </td>
    <td> 
<?
db_input('cm23_i_lotecemit',10,$Icm23_i_lotecemit,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm23_c_situacao?>">
       <?=@$Lcm23_c_situacao?>
    </td>
    <td> 
<?
$x = array('D'=>'Disponível','O'=>'Ocupado');
db_select('cm23_c_situacao',$x,true,$db_opcao,"disabled");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table>
 <tr>
  <td valign="top"><br>
  <?
   $chavepri= array("cm23_i_quadracemit"=>@$cm23_i_quadracemit,"cm23_i_lotecemit"=>@$cm23_i_lotecemit,"cm23_i_codigo"=>@$cm23_i_codigo);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   $sSql = $cllotecemit->sql_query(null,
                                   "cm23_i_codigo, 
                                    cm23_i_quadracemit, 
                                    cm23_i_lotecemit,
                                    case when cm23_c_situacao = 'D' then
                                         'DISPONÍVEL'
                                    else
                                         'OCUPADO'
                                    end as cm23_c_situacao ",
                                   "cm23_i_codigo",
                                   "cm23_i_quadracemit = {$cm23_i_quadracemit} ");
   @$cliframe_alterar_excluir->sql = $sSql;
   $cliframe_alterar_excluir->campos  ="cm23_i_lotecemit, cm23_c_situacao";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="200";
   $cliframe_alterar_excluir->iframe_width ="650";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>

</form>
<script>
function js_pesquisacm23_i_quadracemit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_quadracemit','func_quadracemit.php?funcao_js=parent.js_mostraquadracemit1|cm22_i_codigo|cm22_c_quadra','Pesquisa',true);
  }else{
     if(document.form1.cm23_i_quadracemit.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_quadracemit','func_quadracemit.php?pesquisa_chave='+document.form1.cm23_i_quadracemit.value+'&funcao_js=parent.js_mostraquadracemit','Pesquisa',false);
     }else{
       document.form1.cm22_i_codigo.value = ''; 
     }
  }
}
function js_mostraquadracemit(chave,erro){
  document.form1.cm22_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.cm23_i_quadracemit.focus(); 
    document.form1.cm23_i_quadracemit.value = ''; 
  }
}
function js_mostraquadracemit1(chave1,chave2){
  document.form1.cm23_i_quadracemit.value = chave1;
  document.form1.cm22_i_codigo.value = chave2;
  db_iframe_quadracemit.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_lotecemit','func_lotecemit.php?funcao_js=parent.js_preenchepesquisa|cm23_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lotecemit.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>