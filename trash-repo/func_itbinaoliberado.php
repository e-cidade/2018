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
include("classes/db_itbi_classe.php");
include_once("libs/db_app.utils.php");
db_postmemory($HTTP_POST_VARS);

if(!isset($setorCodigo)) {
	$setorCodigo = '';
}

if(!isset($quadra)) {
	$quadra = '';
}
if(!isset($lote)) {
	$lote = '';
}

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clitbi = new cl_itbi;
$clitbi->rotulo->label("it01_guia");
$clitbi->rotulo->label("it01_guia");

$clrotulo = new rotulocampo;
$clrotulo->label("j01_matric");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?
 db_app::load('estilos.css');
 db_app::load('scripts.js, prototype.js, strings.js, DBViewPesquisaSetorQuadraLote.js, dbcomboBox.widget.js');
?>
</head>
<body bgcolor=#CCCCCC >
<table align="center">
  <tr> 
    <td>
	  <form name="form1" method="post" action="" >
        <table align="center">
          <tr> 
            <td title="<?=$Tit01_guia?>">
              <?=$Lit01_guia?>
            </td>
            <td> 
              <?
		        db_input("it01_guia",10,$Iit01_guia,true,"text",4,"","chave_it01_guia");
		      ?>
            </td>
          </tr>
          <tr> 
            <td>
        	   <b>Solicitação:</b>
            </td>
            <td> 
              <?
                $aSolicitacao = array( "t"=>"Todos",
                					   "i"=>"Interna",
                					   "e"=>"Externa" );
                
                db_select("solicitacao",$aSolicitacao,true,1,"onChange='js_mostraUsuario(this.value);'");
		      ?>
            </td>
          </tr>	          
          <tr id="formUsuario" style="display:none"> 
            <td>
        	   <b>Usuário:</b>
            </td>
            <td> 
              <?
                $sWhere  = "     it14_guia is null    "; 
        		$sWhere .= " and it16_guia is null 	  ";
        		$sWhere .= " and usuext = 1 		  ";
        		
                $rsUsuarios = $clitbi->sql_record($clitbi->sql_query_naolib(null,"distinct id_usuario,nome","nome",$sWhere));
				if ( $clitbi->numrows > 0 ) {
                  db_selectrecord('usuario',$rsUsuarios,true,1,"","chave_usuario","","","",1);
				} else {
				  echo "Nenhum usuário encontrado!";	
				}
                
		      ?>
            </td>
          </tr>
          <tr> 
            <td>
        	   <b>Tipo:</b>
            </td>
            <td> 
              <?
                $aTipo = array( "t"=>"Todos",
                		 	    "u"=>"Urbana",
                				"r"=>"Rural" );
                
                db_select("tipo",$aTipo,true,1,"");
		      ?>
            </td>
          </tr>  
          <tr> 
            <td>
        	   <b>Data da Solicitação:</b>
            </td>
            <td> 
              <?
				db_inputdata('datai', "", "", "", true, 'text', 1, "","chave_datai");
			  ?>
			   <b> até </b>
			  <?				 
				db_inputdata('dataf', "", "", "", true, 'text', 1, "","chave_dataf");
		      ?>
            </td>
          </tr>  
                  <tr>   
          <td>
            <?
              db_ancora("<b>Matrícula :</b>",' js_matri(true); ',1);
            ?>
          </td>
          <td> 
            <?
            
	            db_input('j01_matric',10,$Ij01_matric,true,'text',1,"onchange='js_matri(false)'");
	            db_input('z01_nome',35,0,true,'text',3,"","z01_nomematri");
            
            ?>
          </td>
        </tr>  
				<tr> 
					<td colspan="2" align="center">
						<div id="pesquisa"></div>
					</td>
				</tr>	
				      
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframeitbi.hide();">
             </td>
          </tr>
        </table>
 	  </form>          
    </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)){

      	$campos = "itbi.*";
      	
      	$campos  = "itbi.it01_guia, 						  	   																							";
        $campos .= "itbi.it01_data,                    																							";
      	$campos .= "t.it03_nome as dl_Transmitente, 		  	   																			";
      	$campos .= "c.it03_nome as dl_Adquirente,			  	   																				";
      	$campos .= "case 									  	   																										";
      	$campos .= "  when it18_guia is not null then 'Rural' 	   																	";
      	$campos .= "  else 'Urbano' 							                                                  ";
      	$campos .= "end as dl_Tipo,								                                                  ";
      	$campos .= "it06_matric,								                                                    ";
      	$campos .= "nome,										   																											";
      	$campos .= "case  										  																										";
      	$campos .= " when usuext = 0 then 'Interno' else 'Externo' 																	";
      	$campos .= "end as dl_Tipo_Usuário				 		   																						";
      	

      	$sWhere  = "     it14_guia is null    																											"; 
        $sWhere .= " and it16_guia is null   				 																								";
        $sWhere .= " and case                                                                       ";
        $sWhere .= "     when db_usuarios.usuext = 1 then                                           "; 
        $sWhere .= "       case                                                                     "; 
        $sWhere .= "       when itbi.it01_id_usuario = ".db_getsession('DB_id_usuario')." then true ";
        $sWhere .= "         else false                                                             ";
        $sWhere .= "       end                                                                      ";
        $sWhere .= "     else                                                                       ";
        $sWhere .= "       case                                                                     ";
        $sWhere .= "       when itbi.it01_coddepto = ".db_getsession('DB_coddepto')." then true     ";
        $sWhere .= "       else false                                                               ";
        $sWhere .= "       end                                                                      ";
        $sWhere .= "     end                                                                        ";
        $sWhere .= " and c.it03_princ is true 																											";
        $sWhere .= " and t.it03_princ is true 																											";
        $sWhere .= " and itbi.it01_envia is true 																										";
        
        
        if ( isset($solicitacao) && trim($solicitacao) != "" ) {
          if ( $solicitacao == "i" ) {
          	$sWhere .= " and it01_origem = 1";
          } else if ( $solicitacao == "e") {
        	$sWhere .= " and it01_origem = 2";
          }
        }  
        	
        if (isset($chave_usuario) && trim($chave_usuario) != "" && $chave_solicitacao == "e") {
		      $sWhere .= " and it01_id_usuario = {$chave_usuario}";        	
        }
        	       
        if (isset($tipo) && trim($tipo) != "" ) {
          if ($tipo == "u") {
        	$sWhere .= " and it05_guia is not null";          		        	
          } else if ($tipo == "r"){
        	$sWhere .= " and it18_guia is not null";          	
          }
        }
		
        if (isset($chave_datai) && trim($chave_datai) != "" ) {
          $sWhere .= " and it01_data >= '{$chave_datai_ano}-{$chave_datai_mes}-{$chave_datai_dia}'";          
        }
        	
        if (isset($chave_dataf) && trim($chave_dataf) != "" ) {	        	        	
		  $sWhere .= " and it01_data <= '{$chave_dataf_ano}-{$chave_dataf_mes}-{$chave_dataf_dia}'";        	
        }
        
        if (isset($j01_matric) && trim($j01_matric) != "" ) {
          $sWhere .= " and it06_matric = $j01_matric";          
        }
        
      	if(isset($setor) || isset($quadra) || isset($lote)) {
				
					if(isset($setor) and $setor != '') {
						$sWhere .= " and j05_codigoproprio = '{$setorCodigo}' ";
					}
					if(isset($quadra) and $quadra != '') {
						$sWhere .= " and j06_quadraloc = '{$quadra}' ";
					}
					if(isset($lote) and $lote != '') {
						$sWhere .= " and j06_lote = '{$lote}' ";
					}
				
				}
        
        if(isset($chave_it01_guia) && (trim($chave_it01_guia)!="") ){
           $sql = $clitbi->sql_query_naolib(null,$campos,null,$sWhere." and it01_guia = $chave_it01_guia");          
        }else{
           $sql = $clitbi->sql_query_naolib(null,$campos,null,$sWhere);        
        }
			
        db_lovrot($sql,15,"()","",$funcao_js);
        
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clitbi->sql_record($clitbi->sql_query($pesquisa_chave));
          if($clitbi->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$it01_guia',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<script>

  function js_mostraUsuario(sValor){
    
    if ( sValor == "e") {
      document.getElementById('formUsuario').style.display = "";
    } else {
      document.getElementById('formUsuario').style.display = "none";  
    }
  }

/*Inicio filtro dos campo matricula incluidos na tarefa 45401*/
function js_matri(mostra){
  w = document.body.clientWidth - 20;
  h = document.body.clientHeight - 20;
  
  var matri=document.form1.j01_matric.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe3','func_matricitbi.php?valida=false&funcao_js=parent.js_mostramatri|0|1','Pesquisa',true, null, null, w, h);
  }else{
    js_OpenJanelaIframe('','db_iframe3','func_matricitbi.php?pesquisa_chave='+matri+'&funcao_js=parent.js_mostramatri1','Pesquisa',false);
  }
}
function js_mostramatri(chave1,chave2){
  document.form1.j01_matric.value = chave1;
  document.form1.z01_nomematri.value = chave2;
  db_iframe3.hide();
}
function js_mostramatri1(chave,erro){
  document.form1.z01_nomematri.value = chave; 
  if(erro==true){ 
    document.form1.j01_matric.focus(); 
    document.form1.j01_matric.value = ''; 
  }
}
var oPesquisa = new DBViewPesquisaSetorQuadraLote('pesquisa', 'oPesquisa');
    oPesquisa.show();
    oPesquisa.appendForm();
<? 
if (isset($setorCodigo)){
	echo "oPesquisa.setValues('{$setorCodigo}','{$quadra}','{$lote}');"; 
}
?>
</script>