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
include("classes/db_corcheque_classe.php");
include("classes/db_corchequecgm_classe.php");
include("classes/db_bancos_classe.php");
include("classes/db_cfautent_classe.php");
include("classes/db_db_config_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_libcaixa.php");

db_postmemory($HTTP_POST_VARS);
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
$clrotulo->label("k12_agencia");
$clcorcheque = new cl_corcheque;
$clcorchequecgm = new cl_corchequecgm;
$clbancos = new cl_bancos;
$clcfautent = new cl_cfautent;
$cldb_config = new cl_db_config;
if (isset($emite)){
    $sqlerro = false;
    db_inicio_transacao();
		$k12_dtcheque = @$k12_dtcheque_ano."-".@$k12_dtcheque_mes."-".@$k12_dtcheque_dia;
    $clcorcheque->k12_usuario = db_getsession("DB_id_usuario");
    $clcorcheque->k12_dtinc = date("Y-m-d",db_getsession("DB_datausu"));
    $clcorcheque->k12_horainc = db_hora();
    $clcorcheque->incluir(null);
    $erro_msg=$clcorcheque->erro_msg;
    if ($clcorcheque->erro_status==0){
        $sqlerro=true;
    }
    if ($sqlerro==false){
        if (isset($z01_numcgm)&&$z01_numcgm!=""){
            $clcorchequecgm->k12_numcgm = $z01_numcgm;
            $clcorchequecgm->k12_codcorcheque = $clcorcheque->k12_codcorcheque;
            $clcorchequecgm->incluir($clcorcheque->k12_codcorcheque);            
            if ($clcorchequecgm->erro_status==0){
                $sqlerro=true;
                $erro_msg=$clcorchequecgm->erro_msg;
            }
        }
    }
    db_fim_transacao($sqlerro);
    db_msgbox($erro_msg);
    if ($sqlerro==false){
      //$xx = $clcfautent->sql_query_file(null, "k11_tipoimpcheque,k11_ipimpcheque,k11_portaimpcheque","","k11_ipterm='".db_getsession("DB_ip")."' and k11_instit=".db_getsession("DB_instit"));
        $result_tipoimp = $clcfautent->sql_record($clcfautent->sql_query_file(null, "k11_tipoimpcheque,k11_ipimpcheque,k11_portaimpcheque","","k11_ipterm='".db_getsession("DB_ip")."' and k11_instit=".db_getsession("DB_instit")));
        if($clcfautent->numrows > 0) {
            db_fieldsmemory($result_tipoimp, 0);
        }else{
            db_msgbox("Sem impressora de cheque configurada!!");
            echo "<script>parent.db_iframe_emite.hide();</script>";
            exit;
        }
        
        if ($k12_nominal=='t'){
            $nome = $z01_nome;
        }else{
            $nome = "";
        }
		$result_munic = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession("DB_instit"),"munic"));
        db_fieldsmemory($result_munic,0);
        db_imprimecheque($nome, $k12_banco, $k12_vlrcheque, $k12_dtcheque, @$k11_tipoimpcheque,@$k11_ipimpcheque,@$k11_portaimpcheque,@$munic);
        if ($valor-$k12_vlrcheque>=0){
            $k12_vlrcheque = $valor-$k12_vlrcheque;
        }
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
 <table  align="center">
    <form name="form1" method="post" action="">
    <?db_input('valor',10,"",true,'hidden',3);?>
      <br>
      <br>
      <br>
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>   
        <td align="right">
      <?
       db_ancora($Lz01_numcgm,' js_cgm(true); ',1);
      ?>
      </td>
        <td colspan=3 align="left">
      <?
       db_input('z01_numcgm',5,$Iz01_numcgm,true,'text',1,"onchange='js_cgm(false)'");
       db_input('z01_nome',40,0,true,'text',3);
      ?>
      </td>        
      </tr>
      <tr>
        <td align="right" >&nbsp;&nbsp;<strong>Banco:</strong>
        </td>
        <td > 
        <?
        $result_banco = $clbancos->sql_record($clbancos->sql_query());
        db_selectrecord("k12_banco",$result_banco,true,"text")
        ?>
        </td>
        <td align="right"><strong>Agência:</strong>
        </td>
        <td >
        <?db_input("k12_agencia",7,@$Ik12_agencia,true,"text",1);?>  &nbsp;</td>
      </tr>
      <tr>
        <td align="right"><strong>Nominal:</strong>
        </td>
        <td >
        <?
        $xxx=array("f"=>"Não","t"=>"Sim"); 
        db_select ("k12_nominal",$xxx,true,"text",1);
        ?>  &nbsp;&nbsp;</td>
        <td align="right"><strong>N° Cheque:</strong></td>
        <td ><?db_input("k12_numero",22,"",true,"text",1);?> &nbsp;</td>
      </tr>
      <tr>
        <td align="right" >&nbsp;&nbsp;<strong>Data:</strong>
        </td>
        <td > 
        <?
         if (!isset($k12_dtcheque_ano)){
            $k12_dtcheque_ano = date('Y',db_getsession("DB_datausu"));
            $k12_dtcheque_mes = date('m',db_getsession("DB_datausu"));
            $k12_dtcheque_dia = date('d',db_getsession("DB_datausu"));
         }     
        db_inputdata("k12_dtcheque",@$k12_dtcheque_dia,@$k12_dtcheque_mes,@$k12_dtcheque_ano,true,"text",1);        
        ?>
        </td>
        <td align="right"><strong>Valor:</strong>
        </td>
        <td >
        <?
        if (!isset($k12_vlrcheque)){
            $k12_vlrcheque = @$valor;            
        }
        db_input("k12_vlrcheque",10,"",true,"text",1);?>  &nbsp;</td>
              </tr>      
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" align = "center"> 
          <input  name="emite" id="emite" type="submit" value="Emite Cheque" >
        </td>
      </tr>
  </form>
 </table>
</body>
</html>
<script>
function js_cgm(mostra){
  var cgm=document.form1.z01_numcgm.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm|z01_numcgm|z01_nome','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1','Pesquisa',false,0);
  }
}
function js_mostracgm(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_mostracgm1(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  }
}
</script>