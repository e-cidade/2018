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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_iptubase_classe.php");
include("classes/db_setorloc_classe.php");
include("libs/db_app.utils.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cliptubase = new cl_iptubase;
$cliptubase->rotulo->label("j01_matric");

$clsetorloc = new cl_setorloc();
$rsSetorLoc = $clsetorloc->sql_record($clsetorloc->sql_query_file(null, 'j05_codigoproprio, j05_descr', 'j05_codigoproprio, j05_descr'));

$clrotulo = new rotulocampo;
$clrotulo->label("j14_codigo");
$clrotulo->label("j14_nome");
$clrotulo->label("z01_nome");
$clrotulo->label("j34_setor");
$clrotulo->label("j34_quadra");
$clrotulo->label("j34_lote");
$clrotulo->label("j06_setorloc");
$clrotulo->label("j06_quadraloc");  
$clrotulo->label("j06_lote");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<? 
	db_app::load('scripts.js, prototype.js, strings.js, dbcomboBox.widget.js, estilos.css');
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <form name="form1" id="form1" method="post" action="" onsubmit="js_append()">
  <tr> 
    <td width="100%" height="63" align="center" valign="top">
	    <center>
        <table width="100%" border="0" align="center" cellspacing="0">


          <tr> 
            <td width="34%" align="right" nowrap title="<?=$Tj01_matric?>">
              <?=$Lj01_matric?>
            </td>
            <td width="33%" align="left" nowrap> 
              <?
		       db_input("j01_matric",8,$Ij01_matric,true,"text",4,"","chave_j01_matric");
		       ?>
            </td>

          </tr>



					
          <tr> 
            <td width="34%" align="right" nowrap title="<?=$Tj14_codigo?>">
                <?
				db_ancora($Lj14_codigo,' js_mostraruas(true); ',2)
				?>
            </td>
            <td width="66%" align="left" nowrap> 
                <?
				db_input("j14_codigo",6,$Ij14_codigo,true,'text',4," onchange='js_mostraruas(false);'")
				?>

                <?
 		        db_input("j14_nome",40,$Ij14_nome,true,"text",3);
				?>

            </td>
          </tr>





           <tr> 
            <td width="34%" align="right" nowrap title="<?=$Tz01_nome?>">
                <?=$Lz01_nome?>
            </td>
            <td width="66%" align="left" nowrap> 
                <?
		db_input("z01_nome",40,$Iz01_nome,true,'text',4)
		?>
            </td>
          </tr>

	



					 <tr> 

						<td width="34%" align="right" nowrap title="<?=$Tj34_setor?>">
								<?=$Lj34_setor?>
								/
								<?=$Lj34_quadra?>
								/
								<?=$Lj34_lote?>
						</td>
						<td width="66%" align="left" nowrap> 
								<?
								db_input("j34_setor",8,$Ij34_setor,true,'text',4);
								db_input("j34_quadra",8,$Ij34_quadra,true,'text',4);
								db_input("j34_lote",8,$Ij34_lote,true,'text',4);
								?>
						</td>
						</tr>
						
					<tr>
          	<td width="34%" align="right" nowrap title="<?=$Tj06_setorloc?>"><?=$Lj06_setorloc?></td>
          	<td>
          	<?
           		db_selectrecord('j05_codigoproprio', $rsSetorLoc, true, 4, '', 'j05_codigoproprio', '', 'todos', 'js_carregaQuadra(this.value)');
          	?>
          	</td>
          </tr>
          
          <tr>
          	<td width="34%" align="right" nowrap title="<?=$Tj06_quadraloc?>"><?=$Lj06_quadraloc?></td>
          	<td id="cboquadraloc" width="66%" >
          		
          	</td>
          </tr>
          
          <tr>
          	<td width="34%" align="right" nowrap title="<?=$Tj06_lote?>"><?=$Lj06_lote?></td>
          	<td id="cboloteloc" width="66%" >
          	  
          	</td>
          </tr>

         <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              </td>
          </tr>




        </table>
      </center>
      </td>
 </tr>
	</form>
   <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           $campos = "iptubase.*";
        }
        $sql = "select distinct j01_matric,z01_nome,case when j39_numero is null then 'Terr' else 'Pred' end as Tipo,  case when ruase.j14_codigo is null then ruas.j14_nome else ruase.j14_nome end as j14_nome, case when j39_numero is null then 0 else j39_numero end as j39_numero,j39_compl,j34_setor,j34_quadra,j34_lote 
			         from iptubase 
				      inner join lote on j34_idbql = j01_idbql 
                                      left outer join testpri on j49_idbql = j01_idbql
                                      left outer join ruas on j14_codigo = j49_codigo
				      inner join cgm on z01_numcgm = j01_numcgm 
				      left outer join iptuconstr on j01_matric = j39_matric 
                                      left outer join ruas as ruase on ruase.j14_codigo = j39_codigo 
                                      left outer join loteloc on j06_idbql = j01_idbql
                                      left join setorloc on j05_codigo = j06_setorloc ";
        $sql2 = "";

        if(isset($chave_j01_matric) && (trim($chave_j01_matric)!="") ){
//	         $sql = $cliptubase->sql_query($chave_j01_matric,$campos,"j01_matric");
              $sql2 =" where j01_baixa is null and  j01_matric = $chave_j01_matric ";			  
       }else if(isset($j14_codigo) && (trim($j14_codigo)!="") ){
	         //$sql = $cliptubase->sql_query("",$campos,"j01_numcgm"," j01_numcgm like '$chave_j01_numcgm%' ");
	     $sql2 = " where j01_baixa is null and  j39_codigo = $j14_codigo order by j39_numero";			  
        }else if(isset($z01_nome) && (trim($z01_nome)!="") ){
				 $sql2 = " where j01_baixa is null and  z01_nome like '$z01_nome%' order by z01_nome";
        }else if( (isset($j34_setor) && (trim($j34_setor)!="")) or ((isset($j34_quadra) && (trim($j34_quadra)!="")) or ((isset($j34_lote) && (trim($j34_lote)!="")))) ){
					$sql2 = " where j01_baixa is null  ";
					if (isset($j34_setor) && trim($j34_setor)!="") {
						$sql2 .= " and j34_setor = '" . str_pad($j34_setor,4,"0",STR_PAD_LEFT) . "'";
					}
					if (isset($j34_quadra) && trim($j34_quadra)!="") {
						$sql2 .= " and j34_quadra = '" . str_pad($j34_quadra,4,"0",STR_PAD_LEFT) . "'";
					}
					if (isset($j34_lote) && trim($j34_lote)!="") {
						$sql2 .= " and j34_lote = '" . str_pad($j34_lote,4,"0",STR_PAD_LEFT) . "'";
					}
				 $sql2 .= " order by j34_setor, j34_quadra, j34_lote";
        }else if((isset($j05_codigoproprio) && ($j05_codigoproprio != '' )) or 
                 (isset($j06_quadraloc)     && ($j06_quadraloc != ''))      or 
                 (isset($j06_lote)          && ($j06_lote != ''))){
                 	
					$sql2 = " where j01_baixa is null";                 	
           
          if(isset($j05_codigoproprio) && ($j05_codigoproprio != 'todos' )) {
          	$sql2 .= " and j05_codigoproprio = '$j05_codigoproprio' ";
          }
          if(isset($j06_quadraloc) && ($j06_quadraloc != '')) {
          	$sql2 .= " and j06_quadraloc = '" . $j06_quadraloc . "'";
          }
          if(isset($j06_lote) && ($j06_lote != '')) {
          	$sql2 .= " and j06_lote = '" . $j06_lote . "'";
          }
        
        }else{
					$sql2 = " where j01_baixa is null  ";
        }
				if($sql2!="" || isset($dblov)){			
           $repassa = array('dblov'=>'0');
           db_lovrot(@$sql.@$sql2,15,"()","",$funcao_js,"","NoMe",$repassa);
        }
      }else{
        $result = $cliptubase->sql_record($cliptubase->sql_query(null,"*",null,"j01_baixa is null and  j01_matric =".$pesquisa_chave));
        if($cliptubase->numrows!=0){
          db_fieldsmemory($result,0);
          echo "<script>".$funcao_js."(\"$z01_nome\",false);</script>";
        }else{
       	      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<script>
function js_mostraruas(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_ruas.php?funcao_js=parent.js_preencheruas|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_ruas.php?pesquisa_chave='+document.form1.j14_codigo.value+'&funcao_js=parent.js_preencheruas';	
  }
}
 function js_preencheruas(chave,chave1){
   document.form1.j14_codigo.value = chave;
   document.form1.j14_nome.value = chave1;
   db_iframe.hide();
 }

var aOptions     = new Array();
aOptions[''] = 'Todos...';

function js_append() {

	$('form1').appendChild($('j06_quadraloc'));
	$('form1').appendChild($('j06_lote'));
		
}
 
function js_mostraQuadra(){
	
	cboQuadras          = new DBComboBox('j06_quadraloc', 'j06_quadraloc', aOptions, '180');
	cboQuadras.onChange = 'js_carregaLote(this.value)';
	cboQuadras.show(document.getElementById('cboquadraloc'));

}
 
function js_mostraLotes(){
		
	cboLotes = new DBComboBox('j06_lote', 'j06_lote', aOptions, '180');
	cboLotes.show(document.getElementById('cboloteloc'));

}

js_mostraQuadra();
js_mostraLotes();

function js_carregaQuadra(iCodSetor) {
	
	js_mostraQuadra();
	js_mostraLotes();
	
	var oParametro       = new Object();
	oParametro.sExec     = 'getQuadraSetor';
	oParametro.iCodSetor = iCodSetor;
	
	var oAjax = new Ajax.Request('func_iptubase.RPC.php',
	                           { 
	                            method: 'POST',
	 						               parameters: 'json='+Object.toJSON(oParametro), 
							                 onComplete: js_retornaQuadra
	                           });

}

function js_retornaQuadra(oAjax) {
	
	var oRetorno = eval("("+oAjax.responseText+")"); 
	var aQuadras = new Array(); 

	if(oRetorno.status == 1) {
		for(var i = 0; i < oRetorno.oQuadras.length; i++) {
			with(oRetorno.oQuadras[i]) {
				cboQuadras.addItem(j06_quadraloc, j06_quadraloc);
		  }
		}
	}	
	js_carregaLote($F('j06_quadraloc'));
	
	return false;
		
}

function js_carregaLote(sQuadra) {
		
	js_mostraLotes();
	var oParametro = new Object();
	
	oParametro.sExec     = 'getLote';
	oParametro.sQuadra   = sQuadra;
	oParametro.iSetor    = $F('j05_codigoproprio');
	
	var oAjax = new Ajax.Request('func_iptubase.RPC.php',
	                           { 
	                            method: 'POST',
								               parameters: 'json='+Object.toJSON(oParametro), 
								               onComplete: js_retornaLote });

}

function js_retornaLote(oAjax) {

	var oRetorno = eval("("+oAjax.responseText+")");
	var aLotes   = new Array(); 
	aLotes['']   = 'Todos...';
	
	if(oRetorno.status == 1) {
		for(var i = 0; i < oRetorno.oLotes.length; i++) {
			with(oRetorno.oLotes[i]) {
				cboLotes.addItem(j06_lote, j06_lote);
		  }
		}
	}	
	
	return false;
	
} 
js_carregaQuadra($F('j05_codigoproprio'));
</script>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
document.form1.chave_j01_matric.focus();
document.form1.chave_j01_matric.select();
  </script>
  <?
}

$db_iframe= new janela('db_iframe','');
$db_iframe ->posX=1;
$db_iframe ->posY=20;
$db_iframe ->largura=770;
$db_iframe ->altura=430;
$db_iframe ->titulo="Pesquisa";
$db_iframe ->iniciarVisivel = false;
$db_iframe ->mostrar();

?>