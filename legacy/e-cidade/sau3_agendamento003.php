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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("classes/db_agendamentos_classe.php"));
require_once (modification("classes/db_agendamentos_ext_classe.php"));
require_once (modification("classes/db_cgs_classe.php"));
require_once (modification("classes/db_cgs_und_classe.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("dbforms/db_classesgenericas.php"));

$d1 = $d2 = "";
$z01_i_cgsund = "";
db_postmemory($_POST);


$cl_agendamentos_ext      = new cl_agendamentos_ext;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cl_agendamentos          = new cl_agendamentos;
$cl_cgs                   = new cl_cgs;
$cl_cgs_und               = new cl_cgs_und;
$clrotulo                 = new rotulocampo;

$db_opcao = 1;
$db_botao = true;

$cl_agendamentos->rotulo->label();
$cl_cgs->rotulo->label();
$cl_cgs_und->rotulo->label();
$clrotulo->label("z01_i_cgsund");
$clrotulo->label("z01_i_cgs_und");



if (!isset($sql)) {
	global $sql;
    $sql="";
}


if(isset($pesquisar)) {

	global $sql;
	$sql=$cl_agendamentos_ext->sql_query_ext2("","
                        case s114_i_situacao
                             when 1 then 'Cancelado'
                             when 2 then 'Faltou'
                             when 3 then 'Outros'
                        end as dl_Situacao,
                        sd23_d_consulta as dl_Agendado,
												sd23_c_hora as dl_Hora,
												case when s102_i_prontuario is null then
													'Agendado'
												else
													'Atendimento'
												end as sd97_c_tipo,
												sd23_i_ficha,
												z01_nome as dl_Médico,
												rh70_descr as dl_Especialidade,
												sd101_c_descr as dl_Ficha,
                        s114_d_data as dl_Data,
                        s114_v_motivo as dl_Motivo","","");

	$primeiro=false;
	$sql .= " where ";
	if($z01_i_cgsund!="") {

		$sql .= "z01_i_numcgs=".$z01_i_cgsund;
		$primeiro=true;
	}
	$d1=$d2="";
	if ($sd23_d_consulta!="" && $sd23_d_consulta2!="") {

		if ($primeiro==true) {
			$sql .= " and ";
		}
		$d1=$sd23_d_consulta;
    $rest  = "";
    $rest  = substr($sd23_d_consulta, 6);
		$rest .="-";
    $rest .= substr($sd23_d_consulta, 3, 2);
		$rest .="-";
    $rest .= substr($sd23_d_consulta, 0, 2);
    $sql  .= "sd23_d_consulta  BETWEEN '".$rest."' and";

    $d2=$sd23_d_consulta2;
		$rest  = "";
		$rest  = substr($sd23_d_consulta2, 6);
		$rest .="-";
		$rest .= substr($sd23_d_consulta2, 3, 2);
		$rest .="-";
		$rest .= substr($sd23_d_consulta2, 0, 2);
		$sql  .= " '".$rest."'";
    $primeiro=true;
	} else {

    if (($sd23_d_consulta!="" && $sd23_d_consulta2=="")||($sd23_d_consulta=="" && $sd23_d_consulta2!="")) {

		  db_msgbox("Preencha no os dois campos de Data!");
	    $sql="";
    }
	}
	if($primeiro==false || $z01_i_cgsund==""){
        db_msgbox("Preencha no minimo o Campo paciente(CGS)!");
	    $sql="";
	}
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
  <form name="form1" method="post" action="">
    <div class="container">

      <fieldset>
        <legend>Consulta Agenda</legend>
         <table>
           <tr>
             <td nowrap title="$Tjs_pesquisaz01_i_cgsund">
               <b><? db_ancora("$Lz01_i_cgsund","js_pesquisaz01_i_cgsund(true);",$db_opcao);?></b></td>
             <td><?php db_input('z01_i_cgsund',10,$Iz01_i_cgsund,true,'text',$db_opcao,"onchange='js_pesquisaz01_i_cgsund(false);'")?> </td>
             <td><?php db_input('z01_v_nome',40,$Iz01_v_nome,true,'text',3,$db_opcao)?></td>
           </tr>
           <tr>
              <td><label>Período</label></td>
            <td><?php db_inputdata("sd23_d_consulta","","","",true,'text',"","","","","none") ?></td>
            <td><?php db_inputdata("sd23_d_consulta2","","","",true,'text',"","","","","none") ?></td>
           </tr>
         </table>
      </fieldset>
  	  <input type="submit" name="pesquisar" value="Pesquisar">
      <input type="button" name="limpar" value="Limpar" onclick="limpar_campos()">
      </center>
    </div>
    <div class="subcontainer">
      <fieldset style="width:1000px;" ><legend>Agenda de Pacientes</legend>
        <div>
          <?php
            if($sql!=""){
              db_lovrot($sql,"5","()","","");
            }
          ?>
        </div>
      </fieldset>
  	  <input type="button" name="limpar" value="Imprimir" onclick="js_relatorio('<?=$d1?>','<?=$d2?>',<?=$z01_i_cgsund?>);" >
    </div>
	</form>

<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
 function limpar_campos(){
 	document.form1.z01_i_cgsund.value = '';
	document.form1.z01_v_nome.value = '';
 }

 //Funções da Ancora CGS
 function js_pesquisaz01_i_cgsund(mostra){
   if(mostra==true){
	  js_OpenJanelaIframe('','db_iframe_agendamento','func_cgs_und.php?funcao_js=parent.js_agendamento1|z01_i_cgsund|z01_v_nome','Pesquisa Pacientes',true);
   }else{
	 if(document.form1.z01_i_cgsund.value != ''){
		js_OpenJanelaIframe('','db_iframe_agendamento','func_cgs_und.php?pesquisa_chave='+document.form1.z01_i_cgsund.value+'&funcao_js=parent.js_agendamento','Pesquisa Pacientes',false);
     }else{
        document.form1.z01_v_nome.value = '';
     }
   }
 }
 function js_agendamento(chave,erro){
   document.form1.z01_v_nome.value = chave;
   if(erro==true){
      document.form1.z01_i_cgsund.focus();
      document.form1.z01_i_cgsund.value = '';
   }
 }
 function js_agendamento1(chave1,chave2){
   document.form1.z01_i_cgsund.value = chave1;
   document.form1.z01_v_nome.value = chave2;
   db_iframe_agendamento.hide();
 }

 function js_relatorio(d1,d2,cgs){

  x  = 'sau3_agendamento004.php';
  x += '?fg=fg';
  x += '&cgs='+cgs;
  x += '&datai='+d1;
  x += '&dataf='+d2;

  jan = window.open( x ,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>

</html>