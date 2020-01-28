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

include("classes/db_liclicitem_classe.php");
include("classes/db_solicitemprot_classe.php");
include("classes/db_pcparam_classe.php");
include("classes/db_solandam_classe.php");
include("classes/db_solandpadraodepto_classe.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
$clsolicitemprot = new cl_solicitemprot;
$clpcparam = new cl_pcparam;
$clsolandam = new cl_solandam;
$clsolandpadraodepto = new cl_solandpadraodepto;
$clliclicitem = new cl_liclicitem;
$clrotulo = new rotulocampo;
$clrotulo->label("pc80_codproc");
$clrotulo->label("pc80_resumo");
$clrotulo->label("pc80_data");
$clrotulo->label("id_usuario");
$clrotulo->label("nome");
$clrotulo->label("coddepto");
$clrotulo->label("descrdepto");
//MODULO: compra
$result_pcproc = $clpcproc->sql_record($clpcproc->sql_query(null,"pc80_codproc,pc80_resumo,pc80_data,id_usuario,nome,coddepto,descrdepto","","pc80_codproc=".@$pc80_codproc));
if($clpcproc->numrows>0){
  db_fieldsmemory($result_pcproc,0);
}
?>
<BR><BR>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tpc80_codproc?>">
       <?=@$Lpc80_codproc?>
    </td>
    <td> 
    <?
    db_input('pc80_codproc',8,$Ipc80_codproc,true,'text',3)
    ?>
    </td>
    <td nowrap title="<?=@$Tpc80_data?>">
       <?=@$Lpc80_data?>
    </td>
    <td colspan="3"> 
    <?
    db_inputdata('pc80_data',date("d"),date("m"),date("Y"),true,'text',3);
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tnome?>">
       <?=@$Lnome?>
    </td>
    <td colspan="3">
    <?
    db_input('id_usuario',8,$Inome,true,'text',3);
    db_input('nome',46,$Inome,true,'text',3);
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$descrdepto?>">
       <?=@$Ldescrdepto?>
    </td>
    <td colspan="3"> 
    <?
    db_input('coddepto',8,$Idescrdepto,true,'text',3);
    db_input('descrdepto',46,$Idescrdepto,true,'text',3);
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc80_resumo?>">
       <?=@$Lpc80_resumo?>
    </td>
    <td colspan="3"> 
    <?
    db_textarea('pc80_resumo',4,54,$Ipc80_resumo,true,'text',3)
    ?>
    </td>
  </tr>
  <tr>
    <td colspan="4" align="center">
      <iframe name="iframe_pcprocitem" id="pcprocitem" marginwidth="0" marginheight="0" frameborder="2" src="com1_pcprocitem001.php?pc81_codproc=<?=(@$pc80_codproc)?>" width="600" height="200"></iframe>
    </td>
  </tr>
</table>
</center>
<?
//---------------------------------------Controla Andamento da solicitação---------------------------------
	
	
	$result_conand = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),"pc30_contrandsol"));
	db_fieldsmemory($result_conand,0);
	if (isset($pc30_contrandsol)&&$pc30_contrandsol=='t'){
		$result_pcproc = $clpcprocitem->sql_record($clpcprocitem->sql_query(null,"pc11_codigo","","pc81_codproc=".@$pc80_codproc));		
		if($clpcproc->numrows>0){
			for($w=0;$w<$pcprocitem->numrows;$w++){
  			db_fieldsmemory($result_pcproc,$w);
		$result_prot = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_file(null,"*",null,"pc49_solicitem = $pc11_codigo"));
		if ($clsolicitemprot->numrows>0){
		$result_andam=$clsolandam->sql_record($clsolandam->sql_query_file(null,"*","pc43_ordem desc limit 1","pc43_solicitem=$pc11_codigo"));
      	if ($clsolandam->numrows>0){
      		db_fieldsmemory($result_andam,0);
      	    $result_tipo=$clsolandpadraodepto->sql_record($clsolandpadraodepto->sql_query(null,"*",null,"pc47_solicitem=$pc11_codigo and pc47_ordem=$pc43_ordem"));
      	    if($clsolandpadraodepto->numrows>0){
      	    	db_fieldsmemory($result_tipo,0);
      	      	if ($pc47_pctipoandam!=3||$pc48_depto!=db_getsession("DB_coddepto")){
    				$db_botao=false;
        	    }
      	    }  	
      	}
      	$result_=$clsolicita->sql_record($clsolicita->sql_query_andsol("distinct pc11_numero,pc11_codigo,pc11_quant,pc11_seq,pc11_vlrun,pc11_resum,pc01_codmater,pc01_descrmater,pc01_servico,pc17_unid,pc17_quant,m61_descr,m61_usaquant","where pc11_codigo=$pc11_codigo and  p64_codtran is not null  and y.pc43_depto=".db_getsession("DB_coddepto")));
		if ($clsolicita->numrows==0){
			$db_botao=false;
		}
	  	$result_transf = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_transf(null,"*",null,"pc49_solicitem = $pc11_codigo and p64_codtran is null"));
    	if ($clsolicitemprot->numrows>0){       	
        	$db_botao=false;
        }
		}
			}      	
		}
	}	
	//--------------------------------------------------------------------------------------------------------------------------------------
if (isset($pc80_codproc) && trim($pc80_codproc)!=""){
	
   $result_liclicitem = $clliclicitem->sql_record($clliclicitem->sql_query(null,"*",null,"pc80_codproc=$pc80_codproc"));

   if ($clliclicitem->numrows>0){
	 $db_botao=false;
	 db_msgbox("Existe licitação para itens deste processo de compra!!");
   }else{
  ?>
    <input name="excluir" type="submit" id="db_opcao" value="Excluir" <?=($db_botao==false?"disabled":"")?> >
  <?
  }
}
?>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_pcproc','func_excautitem.php?exc=ok&funcao_js=parent.js_preenchepesquisa|pc80_codproc','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_pcproc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?pc80_codproc='+chave";
  }
  ?>
}
</script>