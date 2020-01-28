<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
require("libs/db_utils.php");
include("dbforms/db_funcoes.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");

$sqlusu = "select * from db_usuarios 
           inner join db_usuacgm on db_usuarios.id_usuario = db_usuacgm.id_usuario  
           where db_usuarios.id_usuario = $id_usuario";
$resultusu = db_query($sqlusu);
db_fieldsmemory($resultusu,0);

switch($usuarioativo) {
  case 1:
    $usuarioativo = "Ativo";
    break;
  case 2:
    $usuarioativo = "Bloqueado";
    break;
  case 3:
    $usuarioativo = "Aguardando Ativação";
    break;
  default:
    $usuarioativo = "Inativo";
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.texto {background-color:white}
.selecionados  {background-color:white;
               text-decoration:none;
               border-right:2px outset #2C7AFE;
               border-bottom:1px outset white;
               text-align:center;
               display:block;
               padding:3px;
               color:black
              }
.dados{ display:block;
        background-color:#CCCCCC;
        text-decoration:none;
        border-right:3px outset #A6A6A6;
        border-bottom:3px outset #EFEFEF;
        color:black;
        text-align:center;
        padding:3px;
      }  
</style>
<script>
function js_marca(obj){

   lista = document.getElementsByTagName("A");

   for (i = 0;i < lista.length;i++){

     if (lista[i].className == 'selecionados' && lista[i].className != '') {
       lista[i].className = 'dados';
     }

   }

   obj.className = 'selecionados';

}

function js_carregaFrame(url,idusu) {
  dados.location.href = url+'?id_usuario='+idusu+'&ano='+document.form1.ano.value;
  
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" id='teste'>
<center>
<br>
<form name='form1'>
<table width='100%' cellspacing=0>
<tr>
<td colspan='2'>
<fieldset>
  <legend><b>Dados do Usuário </b></legend>
    <table border='0'>
      <tr>
          <td><b>Código:</b>                               </td>
          <td class='texto'><?=$id_usuario?>               </td>
          <td align='right'><b>CGM:</b>                    </td>
          <td class='texto'><?=$cgmlogin?>&nbsp;&nbsp;     </td>
          <td>&nbsp;&nbsp;<b>Ano:</b>  
          <?
         // $sqlano = "select distinct anousu , anousu as ano from db_permissao where id_usuario = $id_usuario";
          $ano    = db_getsession("DB_anousu");
  				$sqlano = "select distinct s.anousu , s.anousu as ano
										from db_usuarios 
										inner join db_permherda   on db_permherda.id_usuario = db_usuarios.id_usuario 
										inner join db_usuarios  u on u.id_usuario = id_perfil
										inner join db_permissao s on u.id_usuario = s.id_usuario
										where db_usuarios.id_usuario = $id_usuario
										union
										select distinct anousu , anousu as ano from db_permissao  where id_usuario = $id_usuario
										union 
										select $ano as anousu, $ano as ano ";
          $resultano = db_query($sqlano);
          $ano    = db_getsession("DB_anousu");
          $anousu = db_getsession("DB_anousu");
          db_selectrecord("ano",$resultano,true,1,"","","","","js_marca(document.getElementById('depart'));this.blur();js_carregaFrame('con4_consultausuariodepart.php',".$id_usuario.");",1);
          ?>
          </td>
      </tr>
      <tr>
          <td><b>Usuário: </b>                             </td>
          <td class='texto'><?=$nome?> &nbsp;&nbsp;        </td>
          <td align='right'><b>&nbsp; Situação:</b>        </td>
          <td class='texto'><?=$usuarioativo?>             </td>
          <td>&nbsp;                                       </td>
      </tr>
      <tr>
          <td><b>Login: </b>                               </td>
          <td class='texto'><?=$login?>                    </td>
          <td align='right'><b>Email:</b>                  </td>
          <td class='texto'> <?=$email?>                   </td>
          <td>&nbsp;                                       </td>
      </tr>    
      
    </table>
</fieldset>
</td>
</tr>

<tr>
<td colspan='2'>
  <fieldset>
   <legend><b>Detalhamento : </b></legend>
     <table width='100%'>
       <tr>
         <td width='20%' valign='top' height='100%' rowspan='2'>
           <a class='selecionados' id='depart' onclick='js_marca(this);this.blur();js_carregaFrame("con4_consultausuariodepart.php","<?=$id_usuario?>");'  target='dados'><b> Departamento  </b></a> 
           <a class='dados'        onclick='js_marca(this);this.blur();js_carregaFrame("con4_consultausuarioperfil.php","<?=$id_usuario?>");'  target='dados'><b> Perfil        </b></a>
           <a class='dados'        onclick='js_marca(this);this.blur();js_carregaFrame("func_consultapermissao.php","<?=$id_usuario?>");'      target='dados'><b> Permissões    </b></a> 
           
         </td>
         <td valign='top' height='100%' style='border:1px inset white'>
           <iframe height='300' name='dados' frameborder='0' width='100%' src='con4_consultausuariodepart.php?id_usuario=<?=$id_usuario?>' style='background-color:#CCCCCC'>
           </iframe>
         </td>
       </tr>
 
     </table>
  </fieldset>
</td>
</tr>
</table>
</form>
<center>
  <input type='button' value='Voltar'  onclick='parent.db_iframe_consulta.hide()'>
</center>
</body>
</html>
<script>
</script>