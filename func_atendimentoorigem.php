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
include("classes/db_atendimento_top_classe.php");
include("classes/db_clientes_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clatendimento_top = new cl_atendimento_top;
$cl_clientes       = new cl_clientes;
$cl_clientes->rotulo->label("at01_codcli");
$cl_clientes->rotulo->label("at01_nomecli");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td align="right" nowrap title="<?=$Tat01_codcli?>">
              <?=$Lat01_codcli?>
            </td>
            <td align="left" nowrap> 
              <? db_input("at01_codcli",10,$Iat01_codcli,true,"text",4,"","chave_at01_codcli"); ?>
            </td>
          </tr>
          <tr> 
            <td align="right" nowrap title="<?=$Tat01_nomecli?>">
              <?=$Lat01_nomecli?>
            </td>
            <td align="left" nowrap> 
              <?
		       db_input("at01_nomecli",20,$Iat01_nomecli,true,"text",4,"","chave_at01_nomecli");
		       ?>
            </td>
          </tr>
          <tr>
          	<td colspan="2"><b>Periodo de:</b><? db_inputdata("data_inicial", @$data_inicial_dia, @$data_inicial_mes, @$data_inicial_ano, true, 'text', 4, "") ?><b>&nbsp;&nbsp;a&nbsp;&nbsp;</b><? db_inputdata("data_final", @$data_final_dia, @$data_final_mes, @$data_final_ano, true, 'text', 4, "") ?></td>
          </tr>
          <tr>
            <td><b>Top de Atendimento:</b></td>
          	<td>
          	<? 
          	   $rs_atend_top = $clatendimento_top->sql_record($clatendimento_top->sql_query(null,"at14_usuario,at10_nome","at14_qtd desc limit 5","at14_codcli = $chave_cliente"));
          	   db_selectrecord("atend_top",$rs_atend_top,true,1,"","chave_atend_top");
          	?>
          	</td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      	$where  = "";
        $campos = "atendlanc.at06_datalanc as dl_Lancado_em,
				   atendlanc.at06_horalanc as dl_Hora,		
				   atend.at02_codatend,
                   atenditem.at05_seq as dl_Andamento,
                   tarefa.at40_sequencial as dl_Tarefa,
                   db_modulos.id_item as db_id_item,
                   db_modulos.nome_modulo as dl_Modulo,
                   tarefa.at40_descr,
                   db_usuclientes.at10_usuario as db_at10_usuario,
                   db_usuclientes.at10_nome as dl_Usuario,
                   db_usuarios.id_usuario as db_id_usuario,
                   db_usuarios.nome as dl_Tecnico,
                   clientes.at01_codcli as db_at01_codcli,
                   clientes.at01_nomecli as dl_Cliente,
                   atenditem.at05_data,
                   atend.at02_solicitado,
                   atend.at02_codtipo as db_at02_codtipo";
        $sql    = "select $campos
                   from clientes
                        inner join atendimento     as atend    on atend.at02_codcli           = clientes.at01_codcli
                        left  join atendimentolanc as atendlanc on atendlanc.at06_codatend    = atend.at02_codatend
						left  join atendimentomod  as atendmod on atendmod.at08_atend         = atend.at02_codatend
						left  join atendimentousu  as atendusu on atendusu.at20_codatend      = atend.at02_codatend
			            left  join db_usuclientes              on db_usuclientes.at10_usuario = atendusu.at20_usuario and
                                                                  db_usuclientes.at10_codcli  = atend.at02_codcli      
						left  join tecnico                     on tecnico.at03_codatend       = atend.at02_codatend
						left  join db_usuarios                 on db_usuarios.id_usuario      = tecnico.at03_id_usuario 	
						left  join db_modulos                  on db_modulos.id_item          = atendmod.at08_modulo 		 
                        left  join atenditem                   on atenditem.at05_codatend     = atend.at02_codatend
					    left  join tarefaitem                  on tarefaitem.at44_atenditem   = atenditem.at05_seq
					    left  join tarefa                      on tarefa.at40_sequencial      = tarefaitem.at44_tarefa
                   group by atend.at02_codatend,atenditem.at05_seq,tarefa.at40_sequencial,db_modulos.id_item,db_modulos.nome_modulo,
                            tarefa.at40_descr,db_usuclientes.at10_usuario,db_usuclientes.at10_nome,db_usuarios.id_usuario,db_usuarios.nome,
                            clientes.at01_codcli,clientes.at01_nomecli,atenditem.at05_data,atendlanc.at06_datalanc,atendlanc.at06_horalanc,		
							atend.at02_solicitado,atend.at02_codtipo,atenditem.at05_perc, tarefa.at40_progresso			
				   having atend.at02_codtipo >= 100 and (atenditem.at05_perc < 100 or tarefa.at40_progresso < 100) ";    
        if(isset($chave_at01_codcli) && (trim($chave_at01_codcli)!="") ){
        	 $where = "and clientes.at01_codcli = $chave_at01_codcli ";
        }else if(isset($chave_at01_nomecli) && (trim($chave_at01_nomecli)!="") ){
        	 $where = "and clientes.at01_nomecli like '%$chave_at01_nomecli%' ";
        }else if(isset($data_inicial_dia)&&(trim($data_inicial_dia)!="")&&
                 isset($data_final_dia)&&(trim($data_final_dia)!="")){
             $data_inicial = $data_inicial_ano . "-" . $data_inicial_mes . "-" . $data_inicial_dia;   	
             $data_final   = $data_final_ano   . "-" . $data_final_mes   . "-" . $data_final_dia;   	
        	 $where = "and atenditem.at05_data between '$data_inicial' and '$data_final' ";
        }
        if(isset($chave_cliente)&&$chave_cliente!="") {
        	$where .= "and clientes.at01_codcli = $chave_cliente ";
        }
        if(isset($chave_usuario)&&$chave_usuario!="") {
        	$where .= "and db_usuclientes.at10_usuario = $chave_usuario ";
        }
        if(isset($chave_tecnico)&&$chave_tecnico!="") {
        	$where .= "and db_usuarios.id_usuario = $chave_tecnico ";
        }
        if(isset($chave_modulo)&&$chave_modulo!="") {
        	$where .= "and db_modulos.id_item = $chave_modulo ";
        }
        if(isset($chave_atend_top)&&$chave_atend_top!="") {
	        if(isset($chave_atend_topdescr)&&$chave_atend_topdescr!="") {
	        	if($chave_atend_top != $chave_atend_topdescr) {
		        	$where .= "and db_usuclientes.at10_usuario = $chave_atend_topdescr ";
	        	}
	        	else {
		        	$where .= "and db_usuclientes.at10_usuario = $chave_atend_top ";
	        	}
	        }
	        else {
	        	$where .= "and db_usuclientes.at10_usuario = $chave_atend_top ";
	        }    	
        }
        if(strlen($where) > 0) {
        	$sql .= $where;
        }
        $sql .= " order by clientes.at01_nomecli, atend.at02_codatend desc";
        $sql = "select * from ($sql) as x order by dl_lancado_em desc, dl_hora desc";


//		echo $sql;

        db_lovrot($sql,20,"()","",$funcao_js);
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
document.form2.chave_at01_codcli.focus();
document.form2.chave_at01_codcli.select();
  </script>
  <?
}
?>