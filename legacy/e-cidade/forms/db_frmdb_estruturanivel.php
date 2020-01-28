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

include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

//MODULO: pessoal
$cldb_estruturanivel->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db77_estrut");

if(isset($db_opcaoal)){
    $db_opcao=3;
      $db_botao=false;
}else{
  $db_botao=true;
}
if(isset($opcao) && $opcao=="alterar"){
    $db_opcao = 2;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
    $db_opcao = 3;
    if(isset($db_opcaoal)){
	$db_opcao=33;
    }
}else{  
    $db_opcao = 2;
    $db_botao=false;
    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
      $db78_nivel   ="";
      $db78_descr   ="";
      $db78_inicio  ="";
      $db78_tamanho ="";
    }
} 

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb78_codestrut?>">
       <?=$Ldb78_codestrut?>
    </td>
    <td> 
<?
db_input('db78_codestrut',8,$Idb78_codestrut,true,'text',3);
db_input('db77_estrut',30,$Idb77_estrut,true,'text',3);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb78_nivel?>">
       <?=@$Ldb78_nivel?>
    </td>
    <td> 
<?
db_input('db78_nivel',8,$Idb78_nivel,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb78_inicio?>">
       <?=@$Ldb78_inicio?>
    </td>
    <td> 
<?
db_input('db78_inicio',10,$Idb78_inicio,true,'text',3);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb78_tamanho?>">
       <?=@$Ldb78_tamanho?>
    </td>
    <td> 
<?
db_input('db78_tamanho',10,$Idb78_tamanho,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb78_descr?>">
       <?=@$Ldb78_descr?>
    </td>
    <td> 
<?
if(empty($opcao)){
  $db_opcao02=3;
}else{
  $db_opcao02=1;
}   
db_input('db78_descr',40,$Idb78_descr,true,'text',$db_opcao02);
?>
    </td>
  </tr>
  <tr>
     <td colspan='2' align='center'>
          <input  name  ="<?=($db_opcao==1?"alterar":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
                  type  ="submit" 
                  id    ="db_opcao" 
                  value ="<?=($db_opcao==1?"Alterar":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
                          <?=($db_botao==false?"disabled":"")?>
          >
	  <input name="novo" type="button" id="cancelar" value="Cancelar" onclick="js_cancelar();" <?=(empty($opcao)?"style='visibility:hidden;'":"")?> >
     </td>	  
  </tr>   
</table>
 <table>
    <tr>
      <td valign="top"  align='center'>  
       <?
	$chavepri= array("db78_codestrut"=>$db78_codestrut,"db78_nivel"=>@$db78_nivel);
	$cliframe_alterar_excluir->chavepri      =$chavepri;
	$cliframe_alterar_excluir->sql           = $cldb_estruturanivel->sql_query_file($db78_codestrut,null,"db78_codestrut,db78_nivel,db78_descr,db78_tamanho,db78_inicio","db78_nivel");
	$cliframe_alterar_excluir->campos        ="db78_nivel,db78_descr,db78_tamanho,db78_inicio";
	$cliframe_alterar_excluir->legenda       ="NÍVEIS LANÇADOS";
	$cliframe_alterar_excluir->iframe_height ="200";
	$cliframe_alterar_excluir->opcoes        =2;
	$cliframe_alterar_excluir->iframe_width  ="700";
	$cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);    
       ?>
      </td>
    </tr>
  </table>  
  </center>
</form>
<script>
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
</script>