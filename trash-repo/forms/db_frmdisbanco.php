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

//MODULO: caixa
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cldisbanco->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k34_sequencial");
$clrotulo->label("k35_disbancotxt");
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
     $k00_numbco = "";
     $k15_codbco = "";
     $k15_codage = "";
     $codret = "";
     $dtarq = "";
     $dtpago = "";
     $vlrpago = "";
     $vlrjuros = "";
     $vlrmulta = "";
     $vlracres = "";
     $vlrdesco = "";
     $vlrtot = "";
     $cedente = "";
     $vlrcalc = "";
     $classi = "";
     $k00_numpre = "";
     $k00_numpar = "";
     $convenio = "";
     $idret = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk00_numbco?>">
       <?=@$Lk00_numbco?>
    </td>
    <td> 
<?
db_input('k00_numbco',15,$Ik00_numbco,true,'text',$db_opcao,"")
?>
    </td>
  
    <td nowrap title="<?=@$Tk15_codbco?>">
       <?=@$Lk15_codbco?>
    </td>
    <td> 
<?
db_input('k15_codbco',4,$Ik15_codbco,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_codage?>">
       <?=@$Lk15_codage?>
    </td>
    <td> 
<?
db_input('k15_codage',5,$Ik15_codage,true,'text',$db_opcao,"")
?>
    </td>
 
    <td nowrap title="<?=@$Tcodret?>">
       <?=@$Lcodret?>
    </td>
    <td> 
<?
db_input('codret',6,$Icodret,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdtarq?>">
       <?=@$Ldtarq?>
    </td>
    <td> 
<?
db_inputdata('dtarq',@$dtarq_dia,@$dtarq_mes,@$dtarq_ano,true,'text',$db_opcao,"")
?>
    </td>
 
    <td nowrap title="<?=@$Tdtpago?>">
       <?=@$Ldtpago?>
    </td>
    <td> 
<?
db_inputdata('dtpago',@$dtpago_dia,@$dtpago_mes,@$dtpago_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvlrpago?>">
       <?=@$Lvlrpago?>
    </td>
    <td> 
<?
db_input('vlrpago',15,$Ivlrpago,true,'text',$db_opcao,"")
?>
    </td>
  
    <td nowrap title="<?=@$Tvlrjuros?>">
       <?=@$Lvlrjuros?>
    </td>
    <td> 
<?
db_input('vlrjuros',15,$Ivlrjuros,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvlrmulta?>">
       <?=@$Lvlrmulta?>
    </td>
    <td> 
<?
db_input('vlrmulta',15,$Ivlrmulta,true,'text',$db_opcao,"")
?>
    </td>
  
    <td nowrap title="<?=@$Tvlracres?>">
       <?=@$Lvlracres?>
    </td>
    <td> 
<?
db_input('vlracres',15,$Ivlracres,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvlrdesco?>">
       <?=@$Lvlrdesco?>
    </td>
    <td> 
<?
db_input('vlrdesco',15,$Ivlrdesco,true,'text',$db_opcao,"")
?>
    </td>
  
    <td nowrap title="<?=@$Tvlrtot?>">
       <?=@$Lvlrtot?>
    </td>
    <td> 
<?
db_input('vlrtot',15,$Ivlrtot,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcedente?>">
       <?=@$Lcedente?>
    </td>
    <td> 
<?
db_input('cedente',15,$Icedente,true,'text',$db_opcao,"")
?>
    </td>
  
    <td nowrap title="<?=@$Tvlrcalc?>">
       <?=@$Lvlrcalc?>
    </td>
    <td> 
<?
db_input('vlrcalc',15,$Ivlrcalc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tidret 
                      ?>">
       <?=@$Lidret
       ?>
    </td>
    <td>
    &nbsp; 
<?
db_input('idret',6,$Iidret,true,'text',$db_opcao,"");
db_input('k34_sequencial',6,$Ik34_sequencial,true,'hidden',3,"");
db_input('k35_disbancotxt',6,$Ik35_disbancotxt,true,'hidden',3,"");
?>
    </td>
  
    <td nowrap title="<?//=@$Tclassi
    ?>">
       <?//=@$Lclassi?>
    </td>
    <td> 
<?
//$x = array("f"=>"NAO","t"=>"SIM");
//db_select('classi',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk00_numpre?>">
       <?=@$Lk00_numpre?>
    </td>
    <td> 
<?
db_input('k00_numpre',8,$Ik00_numpre,true,'text',$db_opcao,"")
?>
    </td>
  
    <td nowrap title="<?=@$Tk00_numpar?>">
       <?=@$Lk00_numpar?>
    </td>
    <td> 
<?
db_input('k00_numpar',4,$Ik00_numpar,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tconvenio?>">
       <?=@$Lconvenio?>
    </td>
    <td colspan=3> 
<?
db_input('convenio',100,$Iconvenio,true,'text',$db_opcao,"")
?>
    </td>
    
  </tr>
  <tr>
    <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("idret"=>@$idret);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $cldisbanco->sql_query_txtreg(null,"*",null,"k35_disbancotxt=$k35_disbancotxt");
     $cliframe_alterar_excluir->sql_disabled     = $cldisbanco->sql_query_txtreg(null,"*",null,"k35_disbancotxt=$k35_disbancotxt and classi='t'");
	 $cliframe_alterar_excluir->campos  ="k00_numbco,k15_codbco,k15_codage,codret,dtarq,dtpago,vlrpago,vlrjuros,vlrmulta,vlracres,vlrdesco,vlrtot,cedente,vlrcalc,idret,classi,k00_numpre,k00_numpar,convenio";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
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