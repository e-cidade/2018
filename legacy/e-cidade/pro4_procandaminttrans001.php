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
include("libs/db_sql.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_procandamint_classe.php");
include("classes/db_procandamintusu_classe.php");
include("classes/db_protprocesso_classe.php");

$clprocandamint = new cl_procandamint;
$clprocandamintusu = new cl_procandamintusu;
$clprotprocesso = new cl_protprocesso;

$clprocandamint->rotulo->label();
$clprotprocesso->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("p61_id_usuario");
$clrotulo->label("p68_codproc");
$clrotulo->label("p79_usuario");
$clrotulo->label("nome");


db_postmemory($HTTP_POST_VARS);

if (isset($incluir)){
  
  db_inicio_transacao();
  $vt=$HTTP_POST_VARS;
  $ta=sizeof($vt);
  reset($vt);
  for($i=0; $i<$ta; $i++){
    $chave=key($vt);
    if(substr($chave,0,5)=="CHECK"){
      $dados=split("_",$chave); 
      $result1=$clprotprocesso->sql_record($clprotprocesso->sql_query_file($dados[1],"p58_codandam"));
      db_fieldsmemory($result1,0);
      $data= date("Y-m-d",db_getsession("DB_datausu"));
      $sqlerro=false;
      $clprocandamint->p78_codandam=$p58_codandam;
      $clprocandamint->p78_data=$data;
      $clprocandamint->p78_hora=db_hora();
      $clprocandamint->p78_usuario=db_getsession("DB_id_usuario");
      $clprocandamint->incluir(null);
      $erro_msg = $clprocandamint->erro_msg;
      if ($clprocandamint->erro_status==0){
	$sqlerro=true;
      } 
      $codigo = $clprocandamint->p78_sequencial;
      if ($sqlerro==false){
	$clprocandamintusu->p79_usuario = $p79_usuario;
	$clprocandamintusu->incluir($codigo);
	if ($clprocandamintusu->erro_status==0){
	  $sqlerro=true;
	} 
      }
    }
    $proximo=next($vt);
  }
  db_fim_transacao($sqlerro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_marca(obj){ 
   var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type == 'checkbox'){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
     }
   }
   return false;
}
</script>  
<style>
.cabec {
text-align: center;
color: darkblue;
background-color:#aacccc;       
border-color: darkblue;
}
.corpo {
color: black;
background-color:#ccddcc;       
}
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<center>
<form name="form1" method="post" target="" action="pro4_procandaminttrans001.php">
<table border='0'>
<tr height="20px">
<td ></td>
<td ></td>
</tr>
  <tr>
    <td nowrap title="<?=@$Tp78_despacho?>"align='left' colspan=2>
       <?=@$Lp78_despacho?>
<?
db_textarea('p78_despacho',0,80,$Ip78_despacho,true,'text',1,"")
?>
  </tr>
    <tr>
       <td title="<?=$Tp79_usuario?>"align='left'>
           <?db_ancora($Lp79_usuario,"js_pesquisa_usuario(true);",1);?>
       </td>
       <td nowrap title="<?=$Tnome?>">
          <?db_input("p79_usuario",6,$Ip79_usuario,true,"text",1,"onchange='js_pesquisa_usuario(false);'");?>
          <?db_input("nome_dest",40,$Inome,true,"text",3);?>
       </td>
    </tr>
  </tr>
  <tr>
  <td colspan=2 align='center'>
    <input name="incluir" type="submit"   value="Incluir">
  </td>
  </tr>
    <td colspan=2 align='center' >
  <?
	   $sql = "select * from (
				   select p58_codproc,
					  p58_requer,
					  p58_dtproc,
					  p58_hora,
					  z01_nome,
					  p61_id_usuario,
					  arqproc.p68_codproc
				   from   protprocesso
					  inner join cgm on p58_numcgm = z01_numcgm
					  inner join procandam on p58_codandam = p61_codandam
					  left join arqproc on arqproc.p68_codproc = protprocesso.p58_codproc
				   where ( p61_coddepto = ".db_getsession("DB_coddepto").")  ) as x																	                   
				   where   x.p68_codproc is null";
       $result=pg_exec($sql);
       $numrows=pg_numrows($result);
       if($numrows>0){ 
          echo "
	  <br><br>
	  <table>
           <tr>
	     <td class='cabec'  title='Inverte marcação' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
	     <td class='cabec' align='center'  title='$Tp58_codproc'>".str_replace(":","",$Lp58_codproc)."</td>
	     <td class='cabec' align='center'  title='$Tp58_requer'>".str_replace(":","",$Lp58_requer)."</td>
	     <td class='cabec' align='center'  title='$Tp58_dtproc'>".str_replace(":","",$Lp58_dtproc)."</td>
	     <td class='cabec' align='center'  title='$Tp58_hora'>".str_replace(":","",$Lp58_hora)."</td>
	     <td class='cabec' align='center'  title='$Tz01_nome'>".str_replace(":","",$Lz01_nome)."</td>
	   </tr>
          "; 	   
       }else{
         echo "<br><br><b>Sem Processos!!</b>";
       }
       for($i=0; $i<$numrows; $i++){
         db_fieldsmemory($result,$i);
         echo"
           <tr>
	      <td  class='corpo' title='Inverte a marcação' align='center'><input type='checkbox' name='CHECK_$p58_codproc' id='CHECK_".$p58_codproc."'></td>
              <td  class='corpo'  align='center' title='$Tp58_codproc'><label style=\"cursor: hand\"><small>$p58_codproc</small></label></td>
              <td  class='corpo'  align='center' title='$Tp58_requer'><label style=\"cursor: hand\"><small>$p58_requer</small></label></td>
              <td  class='corpo'  align='center' title='$Tp58_dtproc'><label style=\"cursor: hand\"><small>".db_formatar($p58_dtproc,'d')."</small></label></td>
              <td  class='corpo'  align='center' title='$Tp58_hora'><label style=\"cursor: hand\"><small>$p58_hora</small></label></td>
              <td  class='corpo'  align='center' title='$Tz01_nome'><label style=\"cursor: hand\"><small>$z01_nome</small></label></td>
           </tr>";
       }
	 echo"
	   </table>";	        
       

  ?>
  </td>
  </tr>
  </table>
  </form>
</center>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
function js_pesquisa_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.p79_usuario.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.p79_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome_dest.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome_dest.value = chave; 
  if(erro==true){ 
    document.form1.p79_usuario.focus(); 
    document.form1.p79_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.p79_usuario.value = chave1;
  document.form1.nome_dest.value = chave2;
  db_iframe_db_usuarios.hide();
}
</script>
<?
if (isset($incluir)){
    db_msgbox($erro_msg);
    if($sqlerro==true){
      echo "<script> document.form1.".$clprocandamint->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprocandamint->erro_campo.".focus();</script>";
    }else{ 
      echo"<script>top.corpo.location.href='pro4_procandaminttrans001.php';</script>";
    }
}
?>
</body>
</html>