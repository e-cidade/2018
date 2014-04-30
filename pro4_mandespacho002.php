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
include("classes/db_protprocesso_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clprotprocesso = new cl_protprocesso();
$rotulo = new rotulocampo();
$db_opcao = 2;
$rotulo->label("p58_codproc");
$rotulo->label("p58_despacho");
$rotulo->label("p58_publico");
if (isset($btnincluir)){
  
   $clprotprocesso->alterar($p58_codproc);
  // $sql = "update protprocesso set p58_pr
   $clprotprocesso->pagina_retorno = "pro4_mandespacho001.php";
   $clprotprocesso->erro(true,true);
}
$sql2 = "select p58_despacho,z01_nome,p51_descr,p61_coddepto
         from   protprocesso inner join cgm on 
                p58_numcgm = z01_numcgm 
                inner join tipoproc on p58_codigo = p51_codigo
		inner join procandam on p58_codandam = p61_codandam
         where  p58_codproc = ".$p58_codproc;
//echo $sql2;
$rs = pg_exec($sql2);
//db_criatabela($rs);exit;
if(pg_numrows($rs)==0){
  $p58_despacho = "";
  $p61_coddepto = 0;

}else{

  $p58_despacho = pg_result($rs,0,"p58_despacho");
  $p61_coddepto = pg_result($rs,0,"p61_coddepto");
}
if($p61_coddepto != db_getsession("DB_coddepto")){
  $db_opcao = 3;
}else{
  $db_opcao = 2;
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <table>
    <form method="post" name="form2" action="pro4_mandespacho002.php">
    <tr>
       <td title="<?=$Tp58_codproc?>">
           <?=$Lp58_codproc;?>
       </td>
       <td>
          <?db_input("p58_codproc",15,$Ip58_codproc,true,"text",3,"");?>
       </td>
    </tr>
    <tr>
      <td><b>Requerente:</b></td>
      <td><?=@pg_result($rs,0,"z01_nome");?></td>
    </tr>
    <tr>
      <td><b>Tipo:</b></td>
      <td><?=@pg_result($rs,0,"p51_descr");?></td>
    </tr>
    <tr>
      <td title="<?=$Tp58_despacho;?>">
          <?=$Lp58_despacho;?>
      </td>
      <td>
        <?db_textarea('p58_despacho',10,60,$Ip58_despacho,true,'text',$db_opcao,"");?>

      </td>
     </tr>
  <tr>
    <td nowrap title="<?=$Tp58_publico?>" >
       <?=$Lp58_publico?>
    </td>
    <td>
	       <?
               $x = array("t"=>"Sim","f"=>"Não");
               db_select('p58_publico',$x,true,1,"");
	       
               ?>
    </td>
  </tr>
     <tr>
         <td  align="center" colspan="2">
            <input type="submit" value="Atualizar" name="btnincluir" <?=($p61_coddepto != db_getsession("DB_coddepto")?" disabled ":"")?>>
         </td>   
     </tr>
</table>
 </center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if($p61_coddepto != db_getsession("DB_coddepto")){
//  echo $p61_coddepto;
  if($p61_coddepto==0){
    db_msgbox("Processo não encontrado.");
  }else{
    db_msgbox("Processo não esta neste setor.");
  } 
  db_redireciona("pro4_mandespacho001.php");
}
?>