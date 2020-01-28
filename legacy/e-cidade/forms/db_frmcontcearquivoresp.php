<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

//MODULO: contabilidade
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clcontcearquivoresp->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("c11_id_usuario");
if(isset($db_opcaoal)){
   $db_opcao=33;
    $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    $db_botao=true;
}else{  
    $db_opcao = 1;
    $db_botao=true;
    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
     $c12_nome = "";
     $c12_cargo = "";
//     $c12_contcearquivo = "";
     $c12_nrodoc = "";
     $c12_tipodoc = "";
     $c12_tipo = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>

<br><br>
<table width="46%" align="center">
<tr>
<td align="center">
<fieldset>
<legend>
<b>Responsáveis pela Geração</b>
</legend>

<table border="0" >
  <tr>
    <td nowrap>
    </td>
    <td> 
<?
db_input('c12_sequencial',10,$Ic12_sequencial,true,'hidden',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc12_contcearquivo?>">
       <?
       db_ancora("<b>Código da Geração :</b>","js_pesquisac12_contcearquivo(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('c12_contcearquivo',10,$Ic12_contcearquivo,true,'text',3," onchange='js_pesquisac12_contcearquivo(false);'")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc12_nome?>">
       <?=@$Lc12_nome?>
    </td>
    <td> 
<?
db_input('c12_nome',34,$Ic12_nome,true,'text',$db_opcao,"onkeyup='js_testaNome();'")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc12_cargo?>">
       <?=@$Lc12_cargo?>
    </td>
    <td> 
<?
db_input('c12_cargo',34,$Ic12_cargo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc12_nrodoc?>">
       <?=@$Lc12_nrodoc?>
    </td>
    <td> 
<?
db_input('c12_nrodoc',10,$Ic12_nrodoc,true,'text',$db_opcao,"onKeyup = \"js_ValidaCampos(this,1,'Número do Documento','f','f',event);\"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc12_tipodoc?>">
       <?=@$Lc12_tipodoc?>
    </td>
    <td> 
<?
$x = array('1'=>'CPF','2'=>'CRC');
db_select('c12_tipodoc',$x,true,$db_opcao,"onchange='js_mudatipo();'");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc12_tipo?>">
       <?=@$Lc12_tipo?>
    </td>
    <td> 
<?
$x = array('1'=>'Titular Reponsável','2'=>'Responsável da Época','3'=>'Responsável Geração dos Dados','4'=>'Contador Reponsável pelas Informações ','5'=>'Responsável Controle Interno');
db_select('c12_tipo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
</table>
</fieldset>
</td>
</tr>

</table>

   <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="<?=($db_opcao==1?"return js_validaCampos();":($db_opcao==2||$db_opcao==22?"return js_validaCampos();":""))?>"  >
   <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >

 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
   $sCampos  = "c12_sequencial, c12_nome, c12_cargo, c12_contcearquivo, c12_nrodoc, ";
   $sCampos .= " case ";
   $sCampos .= "   when c12_tipo = 1 then 'Titular Responsável' ";
   $sCampos .= "   when c12_tipo = 2 then 'Responsável da Época' ";
   $sCampos .= "   when c12_tipo = 3 then 'Responsável pela Geração dos Dados' ";
   $sCampos .= "   when c12_tipo = 4 then 'Contador Responsável pelas Informações' ";
   $sCampos .= "   when c12_tipo = 5 then 'Responsável Controle Interno' ";
   $sCampos .= " end as c12_tipo ";   
	 $chavepri                                = array("c12_sequencial"=>@$c12_sequencial,"c12_contcearquivo" => @$c12_contcearquivo);
   $cliframe_alterar_excluir->alignlegenda  = 'left';
	 $cliframe_alterar_excluir->chavepri      = $chavepri;	 
	 $cliframe_alterar_excluir->sql           = $clcontcearquivoresp->sql_query_file(null,$sCampos,null,"c12_contcearquivo = {$c12_contcearquivo}");
	 $cliframe_alterar_excluir->campos        = "c12_sequencial,c12_nome,c12_cargo,c12_nrodoc,c12_tipo";
	 $cliframe_alterar_excluir->legenda       = "Responsáveis Lançados";
	 $cliframe_alterar_excluir->iframe_height = "110";
	 $cliframe_alterar_excluir->iframe_width  = "700";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
  </center>
</form>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>

function js_testaNome() {
  var expr = new RegExp("[^A-Za-z0-9à-úÁ-ÚüÜ \.,;:@&%-\_]+");
  if ( $('c12_nome').value.match(expr)) {
    alert("Nome deve ser preenchido somente com letras, números, espaço, vírgula, ponto-e-vírgula, hífen, 2 pontos, arroba, sublinhado.");
    $('c12_nome').value = '';
    $('c12_nome').focus();
  }
}

function js_tamnome(){
  var nome=$F('c12_nome');
  var tam=nome.split(" ");
  var passa=true;
  if (tam.length<2){
    alert("Nome inconsistente (regra 1)!");
    $('c12_nome').value="";
    $('c12_nome').focus();
    passa=false;
  }else if (1 == 2){
    for (i=0;i<tam.length;i++){
  if (tam[0].length<2 || tam[1].length<2){
    alert("Nome inconsistente (regra 2)!");
    $('c12_nome').value="";
    $('c12_nome').focus();
    passa=false;
    break;
  }
    }
  }
  if (passa==true){
    return true;
  }else{
    return false;
  }
}

  /*
  * Sistema já carrega com validações para o campo CPF
  */
  
  $('c12_nrodoc').maxLength = 11;

  $('c12_nrodoc').onpaste = function(event) {
    return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
    }

function js_validaCampos() {

  if($F('c12_nome') == '') {

    alert('Campo Nome é de preenchimento obrigatório.');
    return false;
  }

  if($F('c12_cargo') == '') {

    alert('Campo Cargo é de preenchimento obrigatório.');
    return false;
  }

  if($F('c12_nrodoc') == '') {

    alert('Campo Número do Documento é de preenchimento obrigatório.');
    return false;
  }

  if($F('c12_nrodoc').length != 11 && $F('c12_tipodoc') == '1') {

    alert('CPF deve possuir 11 dígitos.');
    return false;
  }
  if($F('c12_nrodoc') != '' && $F('c12_tipodoc') == '1' && !validaCpfCnpj($('c12_nrodoc'))){
    
    alert('O número do CPF informado está incorreto.');
    return false;
  }
 return js_tamnome();
}

function js_mudatipo() {

var iTipo = $F('c12_tipodoc');

  if (iTipo == 1){
      
      $('c12_nrodoc').value = '';
      $('c12_nrodoc').maxLength = 11;
      $('c12_nrodoc').onkeyup = function(event){
        js_ValidaCampos(this,1,'Número do Documento','f','f',event);
      }
      $('c12_nrodoc').onpaste = function(event) {
      return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
      }
  }

  if (iTipo == 2){
      
      $('c12_nrodoc').onkeyup = function(event){
        js_ValidaCampos(this,0,'Número do Documento','f','f',event);
      }
      $('c12_nrodoc').value = '';
      $('c12_nrodoc').maxLength = 20;
      $('c12_nrodoc').onpaste = '';
  }

}

function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisac12_contcearquivo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_contcearquivoresp','db_iframe_contcearquivo','func_contcearquivo.php?funcao_js=parent.js_mostracontcearquivo1|c11_sequencial|c11_id_usuario','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.c12_contcearquivo.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_contcearquivoresp','db_iframe_contcearquivo','func_contcearquivo.php?pesquisa_chave='+document.form1.c12_contcearquivo.value+'&funcao_js=parent.js_mostracontcearquivo','Pesquisa',false);
     }else{
       document.form1.c11_id_usuario.value = ''; 
     }
  }
}
function js_mostracontcearquivo(chave,erro){
  document.form1.c11_id_usuario.value = chave; 
  if(erro==true){ 
    document.form1.c12_contcearquivo.focus(); 
    document.form1.c12_contcearquivo.value = ''; 
  }
}
function js_mostracontcearquivo1(chave1,chave2){
  document.form1.c12_contcearquivo.value = chave1;
  document.form1.c11_id_usuario.value = chave2;
  db_iframe_contcearquivo.hide();
}
</script>