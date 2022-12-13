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
include("classes/db_clientes_classe.php");
include("classes/db_db_sysmodulo_classe.php");
include("classes/db_db_syscadproced_classe.php");
include("classes/db_db_versao_classe.php");
include("classes/db_db_usuclientes_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clrotulo							= new rotulocampo;
$clrotulo->label('at40_sequencial');

$clclientes           = new cl_clientes;
$cldb_sysmodulo       = new cl_db_sysmodulo;
$cldb_syscadproced    = new cl_db_syscadproced;
$cldb_versao          = new cl_db_versao;
$cldb_usuclientes			= new cl_db_usuclientes;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_pesquisa() {
  location.href = 'func_contarefa_procedimento.php?codmod='+document.form1.codmod.value+'&codcliente='+document.form1.codcliente.value+'&codprocedimento='+document.form1.codprocedimento.value+'&codusuario='+document.form1.codusuario.value;
  document.form1.submit();
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" width="100%" align="center" cellspacing="0" bgcolor="#CCCCCC">
<form name='form1' method='post'>
<tr>
<td>
<strong>Modulo:
<?
global $codmod,$codcliente,$codprocedimento,$codusuario,$tarefas_finalizadas,$at40_sequencial;
$res = $cldb_sysmodulo->sql_record($cldb_sysmodulo->sql_query_file(null,'*','nomemod'));
db_selectrecord('codmod',$res,true,2,'','','','0-Todos',' js_pesquisa();');
?></strong>
<strong>
Procedimento:
<?
$res = $cldb_syscadproced->sql_record($cldb_syscadproced->sql_query(null,'codproced,substr(descrproced,1,40)::varchar as descrproced',"descrproced",($codmod != 0?" db_syscadproced.codmod = $codmod ":"")));
db_selectrecord('codprocedimento',$res,true,2,'','','','0-Todos',' js_pesquisa();');
?>
</strong>
<strong>Clientes:
<?
$res = $clclientes->sql_record($clclientes->sql_query_file(null,'*','at01_nomecli',' at01_ativo is true '));
db_selectrecord('codcliente',$res,true,2,'','','','0-Todos',' js_pesquisa();');
?>
</strong>
</td>
</tr>

<tr>
<td>
<strong>Usuário:
<?
$res = $cldb_usuclientes->sql_record($cldb_usuclientes->sql_query_file(null,'at10_usuario, at10_login','at10_login',($codcliente > 0?" at10_codcli = $codcliente":"")));
db_selectrecord('codusuario',$res,true,2,'','','','0-Todos',' js_pesquisa();');

if (!isset($tarefas_finalizadas)) {
	$tarefas_finalizadas="n";
}

db_ancora("$Lat40_sequencial"," js_pesquisa() ",2);
db_input("at40_sequencial",10,$Iat40_sequencial,true,'text',2," onchange='js_pesquisa()'");

?>
</strong>




</td>
</tr>

<tr>
<td>
<strong>Texto:</strong><input name='texto_pesquisar' type='text' value='<?=@$texto_pesquisar?>' size="40"> 
<strong>Descrição:</strong><input type='radio' name='tipotexto' value='d' <?=(@$tipotexto=='d'||!isset($tipotexto)?'checked':'')?>>
<strong>Obs:</strong><input type='radio' name='tipotexto' value='o' <?=(@$tipotexto=='o'?'checked':'')?>>
<input name='pesquisar_texto' type='submit' value='pesquisar' > 
<strong>Tarefas Finalizadas:</strong>
<select name='tarefas_finalizadas' onchange='js_pesquisa()'>
<option value="n"<?=($tarefas_finalizadas=='n'?"selected":"")?>>Não</option>
<option value="s"<?=($tarefas_finalizadas=='s'?"selected":"")?>>Sim</option>
</select>
<strong>Versão do Cliente:</strong>
<?
if( isset($codver) ) {	
  $resultversao = $cldb_versao->sql_record($cldb_versao->sql_query_file(null,"db30_codver,fc_versao(db30_codversao, db30_codrelease) as versao",'db30_codver desc'));
  db_selectrecord('codver',$resultversao,true,2,"","","","");
?>
<input name='pesquisa_versao' type='submit' value='Pesquisa Versão' > 
<?
}
?>
</td>
</tr>
</form>
<tr>
<td>
<?

if( isset($pesquisa_versao) ) {

      $sql = "select distinct  i.codproced,descrproced, m.codmod,nomemod
              from ( select id_item from (
                     select id_item
                     from db_menu
                     union
                     select id_item_filho
                     from db_menu
                     ) as x where id_item in ( 
                                             select distinct db32_id_item 
                                             from db_versaousu
                                             where db32_codver >= $codver
                                             ) 

                   ) as x
                        inner join db_syscadproceditem i on i.id_item = x.id_item
                        inner join db_syscadproced c on c.codproced = i.codproced
                        inner join db_sysmodulo m on m.codmod = c.codmod
              where 1 = 1
              ";
       if( $codmod != 0 ){
         $sql .= " and m.codmod = $codmod ";
       }
       if( $codprocedimento != 0 ){
         $sql .= " and i.codproced = $codprocedimento ";
       }
       //echo
       $sql .= "  order by m.nomemod,i.codproced";
  
      $result = pg_query($sql);

      if(pg_numrows($result)>0){
        
        
        $listamodulo = 0;
        for($m=0;$m<pg_numrows($result);$m++){
          db_fieldsmemory($result,$m);
          if($listamodulo != $codmod ){
            echo "<font color='blue'><strong>".strtoupper($nomemod)."</strong></font><br><br>";
            $listamodulo = $codmod;
          }
          echo "&nbsp&nbsp<strong>$descrproced </strong><br>";
          $sql = "select distinct db30_codversao,db30_codrelease,trim(db32_obs) as db32_obs
                  from db_versaousu
                       inner join db_versao on db30_codver = db32_codver
                       inner join db_syscadproceditem i on i.id_item = db32_id_item
                       inner join db_syscadproced c on c.codproced = i.codproced
                  where db32_codver >= $codver and c.codproced = $codproced
                  and db32_id_item in 
                  (
                    select id_item
                    from db_menu
                    union
                    select id_item_filho
                    from db_menu
                  )
                  ";

          $resitem = pg_query($sql);
          for($mi=0;$mi<pg_numrows($resitem);$mi++){
            db_fieldsmemory($resitem,$mi);
            echo "&nbsp&nbsp&nbsp<strong>2.$db30_codversao.$db30_codrelease</strong> $db32_obs<br>";
          }
        }
        echo "<br><br>";
      }
 }else{



  $sql = "select distinct 
                 case when db_versao.db30_codver is null then ''::text else '2.'::text||db_versao.db30_codversao::text||'.'::text||db_versao.db30_codrelease::text end::varchar as dl_versao,
                 at40_sequencial as dl_tarefa,
                 at40_progresso::integer as dl_prog,
                 at40_diaini,
								 at40_horainidia,
                 at01_nomecli as dl_cliente,
								 case when db_usuclientes.at10_usuario is null then db_usuclientes2.at10_usuario else db_usuclientes.at10_usuario end as at10_usuario,
								 case when db_usuclientes.at10_login is null then db_usuclientes2.at10_login else db_usuclientes.at10_login end as at10_login,
                 at40_descr,
                 at40_obs,
                 db_sysmodulo.codmod,
                 nomemod,
                 case when db_versao.db30_codver is null then ''::text else '2.'::text||db_versao1.db30_codversao::text||'.'::text||db_versao1.db30_codrelease::text end::varchar as dl_versao_cliente,
                 
                 codproced as dl_proced,
                 descrproced,
								 at55_motivo,
								 at54_descr
          from tarefa 
               left join tarefaclientes     on at40_sequencial = at70_tarefa
               left join clientes           on at70_cliente = at01_codcli
               left join tarefamodulo       on at40_sequencial = at49_tarefa
               left join db_sysmodulo       on at49_modulo = codmod
               left join tarefasyscadproced on at40_sequencial = at37_tarefa
               left join db_syscadproced    on codproced = at37_syscadproced 
               left join tarefaproced       on at41_tarefa = at40_sequencial
               left join db_versaotarefa    on db29_tarefa = at40_sequencial
               left join db_versao          on db29_codver = db30_codver
							 left join tarefamotivo				on at55_tarefa = at40_sequencial
							 left join tarefacadmotivo		on at55_motivo = at54_sequencial

               left join atenditemtarefa			on at18_tarefa = at40_sequencial
               left join atenditem						on at05_seq    = at18_atenditem
							 left join tarefaitem						on at44_atenditem = at05_seq
               left join atendimentoversao		on at05_codatend = at67_codatend
               left join db_versao db_versao1	on at67_codver = db_versao1.db30_codver
							 left join atendimento					on at05_codatend = at02_codatend
							 left join atendimentousu				on at05_codatend = at20_codatend
							 left join db_usuclientes				on at10_usuario = at20_usuario and at10_codcli = at02_codcli
							 
               left join tarefaitem tarefaitem2								on tarefaitem2.at44_tarefa = tarefa.at40_sequencial
               left join atenditem atenditem2									on atenditem2.at05_seq = tarefaitem2.at44_atenditem
               left join atendimentoversao atendimentoversao2 on atenditem2.at05_codatend = atendimentoversao2.at67_codatend
               left join db_versao db_versao2									on atendimentoversao2.at67_codver = db_versao2.db30_codver
							 left join atendimento atendimento2							on atenditem2.at05_codatend = atendimento2.at02_codatend
							 left join atendimentousu atendimentousu2				on atenditem2.at05_codatend = atendimentousu2.at20_codatend
							 left join db_usuclientes	db_usuclientes2				on db_usuclientes2.at10_usuario = atendimentousu2.at20_usuario and db_usuclientes2.at10_codcli = atendimento2.at02_codcli
               
          where ";

		if ($at40_sequencial == "") {
			
      $sql .= " at41_proced not in (9,16,17)";

		 if(!isset($tarefas_finalizadas) or $tarefas_finalizadas == "n"){
				$sql .= " and at40_progresso != 100 ";
		 }else{
				$sql .= " ";
		 }      
		 if($codmod!=0){
				$sql .= " and db_sysmodulo.codmod = $codmod ";
		 }      

		 if($codcliente!=0){
				$sql .= " and at01_codcli = $codcliente ";
		 }      
		 if($codprocedimento!=0){
				$sql .= " and codproced = $codprocedimento ";
		 }
		 if( isset($pesquisar_texto) && trim($texto_pesquisar) != '' ){
				if( $tipotexto == 'd' ){
					 $sql .= " and at40_descr ilike '%$texto_pesquisar%' ";
				}else{
					 $sql .= " and at40_obs ilike '%$texto_pesquisar%' ";
				}
		 }

		 if($codusuario != 0) {
				$sql = " select * from ($sql) as x where at10_usuario = $codusuario ";
		 }      

		 $sql .= " order by at40_diaini desc, at40_horainidia";

		} else {

		 $sql .= " at40_sequencial = $at40_sequencial ";
			
		}

   $js_funcao = 'js_pesquisa_tarefa|dl_tarefa|at40_descr|dl_prog|dl_proced|descrproced|codmod|nomemod|at55_motivo|at54_descr';

   db_lovrot($sql,50,'()',null,$js_funcao);

}

?>
</td>
</tr>
</table>
</body>
</html>
<script>

function js_pesquisa_tarefa(tarefa,descricao,progresso,codproced,descrproced,codmod,nomemod,motivo,motivodescricao){
  if( confirm('Utilizar esta tarefa? \nCancelar = Pesquisar Tarefa \nOK = Utilizar Tarefa')) {
    if(  progresso == 100 ){
      if( confirm('Tarefa já encerrada. Confirmar a utilização?') == false ){
        return;
      }
    } 
    if( codproced != null ){
      P = parent.document.getElementById("codproced");
      PD = parent.document.getElementById("codproceddescr");
      for(i=0;i<P.length;i++){
        if( P[i].value == codproced ){
          break;
        }
      }
      if ( i < P.length ){
        P[i].selected = true;
        PD[i].selected = true;
      }else{
        P.options[P.options.length] = new Option(codproced,codproced);
        PD.options[P.options.length] = new Option(descrproced,codproced);
        P.options[P.options.length-1].selected = true;
        PD.options[PD.options.length-1].selected = true;
      }
    }
    
    if( codmod != null ){
      P = parent.document.getElementById("modulo");
      PD = parent.document.getElementById("modulodescr");
      for(i=0;i<P.length;i++){
        if( P[i].value == codmod ){
          break;
        }
      }
      if ( i < P.length ){
        P[i].selected = true;
        PD[i].selected = true;
      }else{
        P.options[P.options.length] = new Option(codmod,codmod);
        PD.options[P.options.length] = new Option(nomemod,codmod);
        P.options[P.options.length-1].selected = true;
        PD.options[PD.options.length-1].selected = true;
      }
    }

    F = parent.document.getElementById("motivo");
    FD = parent.document.getElementById("motivodescr");
    F.options[F.options.length] = new Option(motivo,motivo);
    FD.options[FD.options.length] = new Option(motivodescricao,tarefa);
    
    F.options[F.options.length-1].selected = true;
    FD.options[FD.options.length-1].selected = true;


		
    F = parent.document.getElementById("at40_sequencial");
    FD = parent.document.getElementById("at40_sequencialdescr");
    F.options[F.options.length] = new Option(tarefa,tarefa);
    FD.options[FD.options.length] = new Option(descricao,tarefa);
    
    F.options[F.options.length-1].selected = true;
    FD.options[FD.options.length-1].selected = true;
    parent.db_iframe_tarefa_cons_outra.hide();

  }else{
    js_OpenJanelaIframe('','db_iframe_tarefa','ate2_contarefa001.php?menu=false&chavepesquisa='+tarefa,'Tarefas',true,'0','0');
  }
}

</script>