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

//MODULO: issqn
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clissarqsimplesreg->rotulo->label();
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
     $q23_issarqsimples = "";
     $q23_seqreg = "";
     $q23_dtarrec = "";
     $q23_dtvenc = "";
     $q23_cnpj = "";
     $q23_tiporec = "";
     $q23_vlrprinc = "";
     $q23_vlrmul = "";
     $q23_vlrjur = "";
     $q23_data = "";
     $q23_vlraut = "";
     $q23_nroaut = "";
     $q23_codbco = "";
     $q23_codage = "";
     $q23_codsiafi = "";
     $q23_codserpro = "";
     $q23_anousu = "";
     $q23_mesusu = "";
     $q23_acao   = "";
     $db_botao = false;
   }
} 
if (!isset($opcao)){
   $db_botao = false;
}
?>

<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr style='display:none'>
    <td nowrap title="<?=@$Tq23_sequencial?>">
       <?=@$Lq23_sequencial?>
    </td>
    <td> 
<?
db_input('q23_sequencial',8,$Iq23_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr style='display:none'>
    <td nowrap title="<?=@$Tq23_issarqsimples?>">
       <?=@$Lq23_issarqsimples?>
    </td>
    <td> 
<?
db_input('q23_issarqsimples',8,$Iq23_issarqsimples,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq23_seqreg?>">
       <?=@$Lq23_seqreg?>
    </td>
    <td> 
<?
db_input('q23_seqreg',8,$Iq23_seqreg,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq23_dtarrec?>">
       <?=@$Lq23_dtarrec?>
    </td>
    <td> 
<?
db_inputdata('q23_dtarrec',@$q23_dtarrec_dia,@$q23_dtarrec_mes,@$q23_dtarrec_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq23_dtvenc?>">
       <?=@$Lq23_dtvenc?>
    </td>
    <td> 
<?
db_inputdata('q23_dtvenc',@$q23_dtvenc_dia,@$q23_dtvenc_mes,@$q23_dtvenc_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq23_cnpj?>">
       <?=@$Lq23_cnpj?>
    </td>
    <td> 
<?
db_input('q23_cnpj',14,$Iq23_cnpj,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq23_tiporec?>">
       <?=@$Lq23_tiporec?>
    </td>
    <td> 
<?
db_input('q23_tiporec',1,$Iq23_tiporec,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq23_vlrprinc?>">
       <?=@$Lq23_vlrprinc?>
    </td>
    <td> 
<?
db_input('q23_vlrprinc',15,$Iq23_vlrprinc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq23_vlrmul?>">
       <?=@$Lq23_vlrmul?>
    </td>
    <td> 
<?
db_input('q23_vlrmul',15,$Iq23_vlrmul,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq23_vlrjur?>">
       <?=@$Lq23_vlrjur?>
    </td>
    <td> 
<?
db_input('q23_vlrjur',15,$Iq23_vlrjur,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq23_data?>">
       <?=@$Lq23_data?>
    </td>
    <td> 
<?
db_inputdata('q23_data',@$q23_data_dia,@$q23_data_mes,@$q23_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq23_vlraut?>">
       <?=@$Lq23_vlraut?>
    </td>
    <td> 
<?
db_input('q23_vlraut',15,$Iq23_vlraut,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq23_nroaut?>">
       <?=@$Lq23_nroaut?>
    </td>
    <td> 
<?
db_input('q23_nroaut',23,$Iq23_nroaut,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq23_codbco?>">
       <?=@$Lq23_codbco?>
    </td>
    <td> 
<?
db_input('q23_codbco',3,$Iq23_codbco,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq23_codage?>">
       <?=@$Lq23_codage?>
    </td>
    <td> 
<?
db_input('q23_codage',4,$Iq23_codage,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq23_codsiafi?>">
       <?=@$Lq23_codsiafi?>
    </td>
    <td> 
<?
db_input('q23_codsiafi',6,$Iq23_codsiafi,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq23_codserpro?>">
       <?=@$Lq23_codserpro?>
    </td>
    <td> 
<?
db_input('q23_codserpro',17,$Iq23_codserpro,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq23_anousu?>">
       <?=@$Lq23_anousu ."/".@$Lq23_mesusu?>
    </td>
    <td> 
<?
db_input('q23_anousu',4,$Iq23_anousu,true,'text',3,"")
?>/
<?
db_input('q23_mesusu',2,$Iq23_mesusu,true,'text',$db_opcao,"")
?>
  </td>
  </tr>
  <tr>
  <td><b>Ação</b></td>
  <td>
  <?
   $acoes = array("0"=>"Processo Normal","1" => "Gerar isscomplementar");
   db_select("q23_acao",$acoes,true,1);
  ?>
  </td>
  </tr>
    <td colspan="2" align="center">
 <input name="alterar" type="submit" id="db_opcao" value="Alterar" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("q23_sequencial"=>@$q23_sequencial);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
   $sSQL  = "select * ";
   $sSQL .= "  From issarqsimplesreg ";
   $sSQL .= "             inner join issarqsimplesregerro on q49_sequencial = q23_sequencial ";
   $sSQL .= " where q23_issarqsimples=".$_GET["q23_issarqsimples"];
   $sSQL .= " order by q23_seqreg ";
	 $cliframe_alterar_excluir->sql     = $sSQL;
	 $cliframe_alterar_excluir->campos  ="q23_sequencial,q23_issarqsimples,q23_seqreg,q23_dtarrec,q23_dtvenc,q23_cnpj,q23_tiporec,q23_vlrprinc,q23_vlrmul,q23_vlrjur,q23_data,q23_vlraut,q23_nroaut,q23_codbco,q23_codage,q23_codsiafi,q23_codserpro,q23_anousu,q23_mesusu,q23_acao";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
   $cliframe_alterar_excluir->opcoes       = 2;
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