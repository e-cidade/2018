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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
require("libs/db_stdlibwebseller.php");
include("classes/db_prontuarios_classe.php");
include("classes/db_prontuarios_ext_classe.php");
include("classes/db_cgs_classe.php");
include("classes/db_cgs_und_classe.php");
include("classes/db_sau_triagemprocsf_classe.php");

include("dbforms/db_funcoes.php");

$z01_d_cadast_dia = date("d",db_getsession("DB_datausu"));
$z01_d_cadast_mes = date("m",db_getsession("DB_datausu"));
$z01_d_cadast_ano = date("Y",db_getsession("DB_datausu"));
$z01_i_login = DB_getsession("DB_id_usuario");

db_postmemory($HTTP_POST_VARS);

$clprontuarios = new cl_prontuarios_ext;
$clsau_triagemprocsf = new cl_sau_triagemprocsf;

$db_opcao = 2;
$db_botao = true;
$oSauConfig = loadConfig("sau_config");
$sd24_i_unidade = db_getsession("DB_coddepto");

if(isset($proceguir)){
  //$clprontuarios->sd24_i_numcgs = $sd24_i_numcgs;
  if( empty( $chavepesquisaprontuario ) ){
  
    ?>
     <script>
       alert("Deverá ser efetuada a pesquisa da FAA para lançar a triagem!");
     </script>
    <?
  
  }else{

       $clprontuarios->sd24_i_codigo = $chavepesquisaprontuario;
       if( empty($sd24_v_motivo)){
          
          $clprontuarios->erro_sql = " Campo MOTIVO nao Informado.";
          $clprontuarios->erro_campo = "sd24_v_motivo";
          $clprontuarios->erro_banco = "";
          $clprontuarios->erro_msg   = "Usuário: \\n\\n ".$clprontuarios->erro_sql." \\n\\n";
          $clprontuarios->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$clprontuarios->erro_banco." \\n"));
          $clprontuarios->erro_status = "0";       
       
       }else{
         
         db_inicio_transacao();
           $clprontuarios->alterar($chavepesquisaprontuario);
           if($clprontuarios->erro_status!="0"){
              $clsau_triagemprocsf->excluir(null," s147_i_triagem=$clprontuarios->sd24_i_codigo ");
              $clsau_triagemprocsf->s147_i_triagem=$clprontuarios->sd24_i_codigo;
              $vet=explode(",",$listaproc); 
              for($x=0;$x<count($vet);$x++){
                 $clsau_triagemprocsf->s147_i_procsf=$vet[$x];
                 $clsau_triagemprocsf->incluir(null);
              }
           }
         db_fim_transacao();
            
       }
  }

} else if (isset($chavepesquisaprontuario) && !empty($chavepesquisaprontuario)) {
   
   $result = $clprontuarios->sql_record($clprontuarios->sql_query_nolote_ext(null, "*, m.z01_nome as profissional", null, " prontuarios.sd24_i_codigo = $chavepesquisaprontuario" ));
   if( $clprontuarios->numrows > 0 ){
	$obj_prontuario = db_utils::fieldsMemory($result, 0);	   	
	if( $obj_prontuario->sd59_i_prontuario != "" ){   
	    db_msgbox("Impossível alteração de FAA incluída via Lote.");
	    $sd24_i_codigo = null;
	}else{
	    $res_pronproced = $clprontuarios->sql_record("select * from sau_fechapront
	   			                             inner join prontproced on prontproced.sd29_i_codigo = sau_fechapront.sd98_i_prontproced
	   			                             inner join prontuarios on prontuarios.sd24_i_codigo = prontproced.sd29_i_prontuario
	   			                                               where prontuarios.sd24_i_codigo = $chavepesquisaprontuario 
	   			                                             ");
	   			if( $clprontuarios->numrows > 0  ){
	   				db_msgbox("Impossível alteração de FAA fechada.");
	   				$sd24_i_codigo = null;	   				
	   			}else{ 
   					db_fieldsmemory($result,0);
                                        $sql_proc="select * from sau_triagemprocsf 
                                                      iner join sau_procsemfatura on s146_i_codigo=s147_i_procsf 
                                                   where s147_i_triagem=$sd24_i_codigo";
                                        $result_proc=pg_query($sql_proc);
                                        $linhas_proc=pg_num_rows($result_proc);
                                        $proc=array();
                                        for($c=0;$c<$linhas_proc;$c++){
                                            db_fieldsmemory($result_proc,$c);
                                            $proc[$c]=array($s146_i_codigo,$s146_c_cod,$s146_c_descr);
                                        }
	   			}
   			}
	}
}

$sql1 = "select z01_nome  as profissional,sd03_i_codigo,z01_numcgm
                  from cgm 
                  inner join db_usuacgm on cgmlogin= z01_numcgm
                  inner join db_usuarios on db_usuarios.id_usuario= db_usuacgm.id_usuario
                  inner join medicos on medicos.sd03_i_cgm= cgm.z01_numcgm	
                  inner join unidademedicos on unidademedicos.sd04_i_medico= medicos.sd03_i_codigo
                  inner join unidades on unidades.sd02_i_codigo= unidademedicos.sd04_i_unidade		               
                  where sd02_i_codigo = $sd24_i_unidade and db_usuacgm.id_usuario=".db_getsession("DB_id_usuario");
                  
$query1 = pg_query($sql1) or die(pg_errormessage());
$linhas1 = pg_num_rows($query1);
$profissional_branco = true;
if($linhas1>0 && ( !isset( $sd03_i_codigo ) || (int)$sd03_i_codigo == 0 )) {
  db_fieldsmemory($query1,0);
  $profissional_branco = false;
}else if(empty( $chavepesquisaprontuario )){
    ?>
        <script>
          alert('Usuário <?=db_getsession("DB_id_usuario")?>  não é um Profissional da Saúde.')
        </script>
    <?  
}


?>


<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table align="center" width="70%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="center" valign="top" bgcolor="#CCCCCC">
    <br><br>
    <center>
    <fieldset style="width:95%"><legend><b>Triagem</b></legend>
        <?
        include("forms/db_frmtriagem.php");
        ?>
     </fieldset>
    </center>
    </td>
  </tr>
</table>
<?//db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<?
   if( (int)$sd24_i_profissional == 0 ){
     ?>
       <script>
         //js_pesquisasd04_i_cbo(true)
         js_tabulacaoforms("form1","sd24_v_motivo",true,1,"sd24_v_motivo",true);
       </script>
     <?
   }    
if( $profissional_branco == false ){
	?>
		<script>
			js_pesquisasd04_i_cbo(true);
		</script>
	<?   
}
if(isset($proceguir)){
  if($clprontuarios->erro_status=="0"){
    $clprontuarios->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clprontuarios->erro_campo!=""){
      echo "<script> document.form1.".$clprontuarios->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprontuarios->erro_campo.".focus();</script>";
    }
  }else{

  	$clprontuarios->erro(true,false);?>
  	<script>
  	  //alert(<?=$chavepesquisaprontuario?>);
          //location.href='sau4_triagem000.php?chavepesquisaprontuario=<?=$chavepesquisaprontuario?>'
          parent.document.formaba.a2.disabled = false;
          parent.iframe_a2.location.href='sau4_triagemproc001.php?chavepesquisaprontuario=<?=$chavepesquisaprontuario?>';
          parent.mo_camada('a2');
        </script>
 <?}
} 
?>