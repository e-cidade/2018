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
include("dbforms/db_funcoes.php");
include("classes/db_db_usuclientes_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$cldb_usuclientes  = new cl_db_usuclientes;
$cldb_usuclientes->rotulo->label("at10_codcli");
$cldb_usuclientes->rotulo->label("at10_login");
$cldb_usuclientes->rotulo->label("at10_nome");

if( isset($incluir) ){

  $erro = false; 
  db_inicio_transacao();


  $result = $cldb_usuclientes->sql_record($cldb_usuclientes->sql_query_file(null,"max(at10_usuario) as numero",null," at10_codcli = $cliente"));
  db_fieldsmemory($result,0,0);
  if($numero < 90000000 ){
     $numero = 90000000;
  }else{
     $numero = $numero + 1 ;
  }


  $cldb_usuclientes->at10_codigo = 0;
  $cldb_usuclientes->at10_codcli = $cliente;
  $cldb_usuclientes->at10_usuario= $numero;
  $cldb_usuclientes->at10_login  = $at10_login;
  $cldb_usuclientes->at10_nome   = $at10_nome;

  $cldb_usuclientes->incluir(0);
  if( $cldb_usuclientes->erro_status == "0" ){
    $erro = true; 
    db_msgbox($cldb_usuclientes->erro_msg);

  }

  db_fim_transacao($erro);
  echo "<script>document.form1.fechar.click()</script>";  

}else if( isset($alterar) ){
  db_inicio_transacao();
  
  if($rh01_nasc==""){
    $rh01_nasc = "null";
  }else{
    $rh01_nasc = "'$rh01_nasc_ano-$rh01_nasc_mes-$rh01_nasc_dia'";
  }
  
  $sql = "select id_usuario from acesso_clientes_dados where cliente = $cliente and id_usuario = $codusu ";
  $result = pg_exec($sql);
  
  if(pg_numrows($result)>0){
    $sql = "update acesso_clientes_dados set rh01_nasc = $rh01_nasc, rh01_sexo = '$rh01_sexo',z01_nome = '$at10_nome'
            where cliente = $cliente and id_usuario = $at10_usuario ";
    $result = pg_exec($sql);
  }else{
    $sql = "insert into acesso_clientes_dados (cliente,login,id_usuario,rh01_nasc,rh01_sexo,z01_nome) 
                                             values($cliente,'$at10_login',$at10_usuario,$rh01_nasc,'$rh01_sexo','$at10_nome')";
    $result = pg_exec($sql);
 
  }  
  
  db_fim_transacao($erro);
  
  
  
}else if( isset($pesquisa) ){

  $sql = " select *, login as at10_login
           from acesso_clientes_dados 
                left join db_usuclientes on cliente = at10_codcli and id_usuario = at10_usuario
           where cliente = $cliente and id_usuario = $codusu ";

  $result = pg_exec($sql);
  if(pg_numrows($result)>0){

    db_fieldsmemory($result,0);   
    
  }else{
  
    $sql = " select at10_usuario, at10_login, at10_nome 
           from  db_usuclientes
           where at10_codcli = $cliente and at10_usuario = $codusu ";
    $result = pg_exec($sql);
    if(pg_numrows($result)>0){

      db_fieldsmemory($result,0);

    }
  }

}


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="document.form1.at10_login.focus();">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form1" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tat10_codcli?>">
              <?=$Lat10_codcli?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("cliente",10,$Iat10_codcli,true,"text",3);
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tat10_login?>">
              <?=$Lat10_login?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("at10_login",20,$Iat10_login,true,"text",2);
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tat10_nome?>">
              <?=$Lat10_nome?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("at10_nome",40,$Iat10_nome,true,"text",2);
		       ?>
            </td>
          </tr>

         <?
              if( isset($pesquisa) ){
       ?>
          
          <tr> 
            <td width="4%" align="right" nowrap title="Nascimento">
              <strong>Nascimento:</strong>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_inputdata("rh01_nasc",substr(@$rh01_nasc,8,2),substr(@$rh01_nasc,5,2),substr(@$rh01_nasc,0,4),true,"text",2);
		       ?>
            </td>
          </tr>

          <tr> 
            <td width="4%" align="right" nowrap title="Sexo">
              <strong>Sexo:</strong>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
               $itens = array("F"=>"Feminino","M"=>"Masculino");
		       db_select("rh01_sexo",$itens,true,"text",2);
		       ?>
            </td>
          </tr>


        <?
        }
        ?>

          <tr> 
            <td colspan="2" align="center"> 
              <?
              if( isset($pesquisa) ){
                echo '<input name="at10_usuario" type="hidden" id="at10_usuario" value="'.$at10_usuario.'"> ';
                echo '<input name="alterar" type="submit" id="alterar" value="Alterar"> ';
              }else{
                echo '<input name="incluir" type="submit" id="incluir" value="Incluir"> ';
              } 
              ?>
              <input name="fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_db_usucliente.hide()">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
</table>
</body>
</html>
<?
if( isset($alterar) || isset($incluir) ){
  echo "<script>document.form1.fechar.click();</script>";  
}
?>