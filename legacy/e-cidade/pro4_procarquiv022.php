<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sql.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_protprocesso_classe.php");
require_once("classes/db_procarquiv_classe.php");
require_once("classes/db_proctransfer_classe.php");
require_once("classes/db_proctransand_classe.php");
require_once("classes/db_procandam_classe.php");
require_once("classes/db_arqproc_classe.php");
require_once("classes/db_arqandam_classe.php");

$clprotprocesso = new cl_protprocesso;
$clproctransfer = new cl_proctransfer;
$clproctransand = new cl_proctransand;
$clprocarquiv   = new cl_procarquiv;
$clprocandam    = new cl_procandam;
$clarqproc      = new cl_arqproc;
$clarqandam     = new cl_arqandam;
$clrotulo       = new rotulocampo;

$clprotprocesso->rotulo->label();

$clrotulo->label("z01_nome");
$clrotulo->label("p61_id_usuario");
$clrotulo->label("p68_codproc");
$clrotulo->label("p58_numero");
$clrotulo->label("p89_usuario");
$clrotulo->label("nome");
$clrotulo->label("p67_historico");
$clrotulo->label("p67_dtarq");


db_postmemory($HTTP_POST_VARS);

if (isset($incluir)){
  db_inicio_transacao();
  $sqlerro=false;
  $vt=$HTTP_POST_VARS;
  $ta=sizeof($vt);
  reset($vt);
  for($i=0; $i<$ta; $i++){
    $chave=key($vt);
    if(substr($chave,0,5)=="CHECK"){
      $dados=split("_",$chave);
      $p67_codproc=$dados[1];
      $clprocarquiv->p67_id_usuario = db_getsession("DB_id_usuario");
      $clprocarquiv->p67_coddepto = db_getsession("DB_coddepto");
      $clprocarquiv->p67_codproc= $p67_codproc;
      $clprocarquiv->p67_dtarq  = implode("-", array_reverse(explode("/", $p67_dtarq)));
      $clprocarquiv->incluir("");
      if ($clprocarquiv->erro_status==0){
	$sqlerro=true;
	$erro_msg=$clprocarquiv->erro_msg;
	break;
      }
      $clarqproc->incluir($clprocarquiv->p67_codarquiv,$p67_codproc); 
      if ($clarqproc->erro_status==0){
	$sqlerro=true;
	$erro_msg=$clarqproc->erro_msg;
	break;
      }

      $hoje=date ('Y-m-d',db_getsession("DB_datausu"));

      $clproctransfer->p62_coddepto = db_getsession("DB_coddepto");
      $clproctransfer->p62_dttran      = $hoje;
      $clproctransfer->p62_coddeptorec = db_getsession("DB_coddepto");
      $clproctransfer->p62_id_usorec   = db_getsession("DB_id_usuario");
      $clproctransfer->p62_id_usuario   = db_getsession("DB_id_usuario");
      $clproctransfer->p62_hora        = db_hora();
      $clproctransfer->incluir(null);
      if ($clproctransfer->erro_status==0){
	$sqlerro=true;
	$erro_msg=$clproctarnsfer->erro_msg;
	break;
      }
      //$clproctransfer->erro(true,false);
      
      $cod = $clproctransfer->p62_codtran;
      
      $rsi =  pg_exec("insert into proctransferproc values($cod,$p67_codproc)");
      
      if ($clproctransfer->erro_status == "1" or !$rsi ){
	 $erro = 0;
      }else{
	 $clproctransfer->erro(true,false);
	 $sqlerro = true;
	 break;
	  
      }
      //inclusão do andamento
      $clprocandam->p61_despacho = $clprocarquiv->p67_historico;
      //$clprocandam->p61_dtandam = $hoje;
      $clprocandam->p61_dtandam = implode("-", array_reverse(explode("/", $p67_dtarq)));
      $clprocandam->p61_hora = db_hora();
      $clprocandam->p61_codproc = $p67_codproc;
      $clprocandam->p61_id_usuario = db_getsession("DB_id_usuario");
      $clprocandam->p61_coddepto = db_getsession("DB_coddepto");
      $clprocandam->p61_publico  = 'true';
      $clprocandam->incluir("");
      $erro_msg = $clprocandam->erro_msg;
      if ($clprocandam->erro_status==0){
	 $sqlerro=true;
	 break;
      }
    //  $clprocandam->erro(true,false);

      //inclui  a transferencia. e o andamento do processo na tabela proctransand;
      $clproctransand->p64_codtran  = $clproctransfer->p62_codtran;
      $clproctransand->p64_codandam = $clprocandam->p61_codandam;
      $clproctransand->incluir();

      if ($clproctransand->erro_status == "1"){
	 $erro = 0;
      }else{
	$clproctransand->erro(true,false);
	 $sqlerro = true;
	 break;
      }

      $clarqandam->p69_codarquiv = $clprocarquiv->p67_codarquiv;
      $clarqandam->p69_codandam  = $clprocandam->p61_codandam;
      $clarqandam->p69_arquivado = 'true';
      $clarqandam->incluir();
      $erro_msg = $clarqandam->erro_msg;
      if ($clarqandam->erro_status==0){
	 $sqlerro=true;
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
function js_desabilita(){
  Document.form1.incluir.disabled=true;
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
<body bgcolor="#CCCCCC" style='margin-top: 25px' leftmargin="0"  marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <center>
    <form name="form1" method="post" target="" action="pro4_procarquiv022.php">
      <table>
        <tr>
          <td>
            <fieldset>
              <legend><b>Dados do Arquivamento</b></legend>
                <table border='0'>
                  <tr>
                    <td nowrap title="<?=@$Tp67_historico?>">
                      <?=@$Lp67_historico?>
                    </td>
                    <td>
                      <?
                      db_textarea('p67_historico',6,60,$Ip67_historico,true,'text',1,"")
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td nowrap title="<?=@$Tp67_dtarq?>">
                       <?=@$Lp67_dtarq?>
                    </td>
                    <td> 
                      <?
                      if(empty($y30_data_dia)){
                        $p67_dtarq_dia = date("d",db_getsession("DB_datausu"));
                        $p67_dtarq_mes = date("m",db_getsession("DB_datausu"));
                        $p67_dtarq_ano = date("Y",db_getsession("DB_datausu"));
                      } 
                      db_inputdata('p67_dtarq',@$p67_dtarq_dia,@$p67_dtarq_mes,@$p67_dtarq_ano,true,'text',1,"")
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan=2 align='center'>
                      <input name="incluir" type="submit" value="Arquivar Processos">
                    </td>
                  </tr>
                  <tr>
                    <td colspan=2 align='center' >
                    <?
                  
                       if (!isset($data1) || !isset($data)) {
                           
                          $data=@$data1_ano.'-'.@$data1_mes.'-'.@$data1_dia;
                          $data1=@$data2_ano.'-'.@$data2_mes.'-'.@$data2_dia;
                        }
                        db_input('data',10,'',true,'hidden',3);
                        db_input('data1',10,'',true,'hidden',3);
                        $where="";
                        if (($data != "--") && ($data1 != "--")) {
                  	      $where = $where." and p58_dtproc  between '$data' and '$data1'  ";
                        } else if ($data!="--") {
                  	      $where = $where." and p58_dtproc >= '$data'  ";
                        } else if ($data1!="--"){
                         	$where = $where."and p58_dtproc <= '$data1'   ";  
                        }
                        $depto_atual = db_getsession("DB_coddepto");
                        $usu_atual   = db_getsession("DB_id_usuario");
                        $sSqlProcessos = $clprotprocesso->sql_query_arq(null, 
                                                                        "*",
                                                                        "p58_codproc",
                                                                        "p68_codproc is null 
                                                                         and p61_coddepto={$depto_atual}  {$where}"
                                                                        );
                        $rsProcessos = $clprotprocesso->sql_record($sSqlProcessos);
                        $iNumRows    = $clprotprocesso->numrows;
                        if ($iNumRows > 0) { 

                          echo "
                  	      <table>
                            <tr>
                  	        <td class='cabec' title='Inverte marcação' align='center'>
                  	          <a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
                  	        <td class='cabec' align='center'  title='$Tp58_codproc'>".str_replace(":","",$Lp58_codproc)."</td>
                  	        <td class='cabec' align='center'  title='$Tp58_numero'>".str_replace(":","",$Lp58_numero)."</td>
                  	        <td class='cabec' align='center'  title='$Tp58_dtproc'>".str_replace(":","",$Lp58_dtproc)."</td>
                  	        <td class='cabec' align='center'  title='$Tp58_hora'>".str_replace(":","",$Lp58_hora)."</td>
                  	        <td class='cabec' align='center'  title='$Tz01_nome'>".str_replace(":","",$Lz01_nome)."</td>
                  	      </tr>
                          "; 	   
                        } else {

                          echo "<br><br><b>Sem Processos!!</b>";
                	        echo"<script>js_desabilita();</script>";
                	      }
                        for ( $i = 0; $i < $iNumRows; $i++) {
                          
                          db_fieldsmemory($rsProcessos,$i);
                          $passou                     = true;
                          $sSqlVerificaTransferencia  = "select p63_codproc,p63_codtran, p64_codtran"; 
                	        $sSqlVerificaTransferencia .= "  from proctransferproc ";
                	        $sSqlVerificaTransferencia .= "       left join proctransand on p64_codtran = p63_codtran";  
                          $sSqlVerificaTransferencia .= " where p63_codproc={$p58_codproc}"; 
                          $rsProcessosTransferencia   = db_query($sSqlVerificaTransferencia);
                          if (pg_num_rows($rsProcessos) != 0) {
                	         
                            for ($yy = 0; $yy < pg_num_rows($rsProcessosTransferencia); $yy++) {

                              db_fieldsmemory($rsProcessosTransferencia, $yy);
                        	    if ($p64_codtran == "") {
                        	      $passou = false;
                        	    }
                        	  }
                	        }
                	        if ($passou) {
                	           
                	          echo"
                		        <tr>
                		         <td  class='corpo' title='Inverte a marcação' align='center'>
                		           <input type='checkbox' name='CHECK_{$p58_codproc}' id='CHECK_{$p58_codproc}'></td>
                		         <td  class='corpo'  align='center' title='{$Tp58_codproc}'>
                		          <label style=\"cursor: hand\"><small>{$p58_codproc}</small></label></td>
                		          <td  class='corpo'  align='center' title='{$Tp58_numero}'>
                              <label style=\"cursor: hand\"><small>{$p58_numero}/{$p58_ano}</small></label></td>
                		         <td  class='corpo'  align='center' title='{$Tp58_dtproc}'>
                		              <label style=\"cursor: hand\"><small>".db_formatar($p58_dtproc,'d')."</small></label></td>
                		         <td  class='corpo'  align='center' title='{$Tp58_hora}'>
                		            <label style=\"cursor: hand\"><small>$p58_hora</small></label></td>
                		         <td  class='corpo'  align='center' title='{$Tz01_nome}'>
                		         <label style=\"cursor: hand\"><small>$z01_nome</small></label></td>
                		   </tr>";
                	}
	}
	
        echo"
	   </table>";	        
       

  ?>
  </td>
  </tr>
  </table>
  </fieldset>
  </td>
  </tr>
  </table>
  </form>
</center>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<?
if (isset($incluir)){
    db_msgbox($erro_msg);
    if($sqlerro==true){
      echo "<script> document.form1.".$clprocarquiv->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprocarquiv->erro_campo.".focus();</script>";
    }else{ 
      echo"<script>top.corpo.location.href='pro4_procarquiv011.php';</script>";
    }
}
?>
</body>
</html>