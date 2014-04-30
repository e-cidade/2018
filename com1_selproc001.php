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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_pcproc_classe.php");
include("classes/db_pcparam_classe.php");
include("classes/db_solicita_classe.php");
include("classes/db_pctipocompra_classe.php");
include("classes/db_emptipo_classe.php");
include("classes/db_empautoriza_classe.php");
include("classes/db_cflicita_classe.php");
$clpcproc = new cl_pcproc;
$clcflicita = new cl_cflicita;
$clpcparam = new cl_pcparam;
$clpctipocompra = new cl_pctipocompra;
$clsolicita = new cl_solicita;
$clemptipo = new cl_emptipo;
$clempautoriza = new cl_empautoriza;
$clempautoriza->rotulo->label();
$clpcproc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc12_tipo");
$clrotulo->label("e54_codtipo");
$clrotulo->label("e54_autori");
$clrotulo->label("e54_destin");
$clrotulo->label("e54_numerl");
$clrotulo->label("e54_tipol");
$clrotulo->label("pc10_numero");
$clrotulo->label("pc10_resumo");

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$action   = "";
$erro     = false;
$db_botao = true;
$instit   = db_getsession("DB_instit");
 
if (isset($pc10_numero)&&trim($pc10_numero)!=""){
	
  $sql       = "select extract(year from pc10_data) as pc10_data from solicita where pc10_numero = $pc10_numero";
  $resultado = $clsolicita->sql_record($sql);

  if ($clsolicita->numrows > 0){
  	
    db_fieldsmemory($resultado,0);

    if ($pc10_data < db_getsession("DB_anousu")){
      $erro     = true;
      $erro_msg = "Solicitacao de exercicio anterior. Autorizacao cancelada.";
     }
          
   }
   
   $oDaoAcordoPcprocitem = db_utils::getDao("acordopcprocitem");
   $sSqlDadosAcordo      = $oDaoAcordoPcprocitem->sql_query_acordo(null,
				                                                           "ac26_acordo",
				                                                            null,
				                                                           "pc11_numero = {$pc10_numero}
				                                                            and (ac16_acordosituacao  not in (2,3))");
				                                                            
   $rsDadosAcordo = $oDaoAcordoPcprocitem->sql_record($sSqlDadosAcordo);
   
   if ($oDaoAcordoPcprocitem->numrows > 0) {
    
     $iNumeroAcordo = db_utils::fieldsMemory($rsDadosAcordo, 0)->ac26_acordo;
     $erro          = true;
     $erro_msg      = "a solicitação de Compras está vinculada ao acordo {$iNumeroAcordo}, ";
     $erro_msg     .= "Não será possivel gerar Autorizações de Empenho diretamente.";
   }
   
	 $sWhereVerificaAutorizacao  = "     pc10_numero = {$pc10_numero} ";
   $sWhereVerificaAutorizacao .= " and ( e54_autori is null
																          or (        e54_autori is not null
																                and   e54_anulad is not null
																                and ( e61_numemp is null or ( e60_numemp is not null and e60_vlremp = e60_vlranu ))
																                and not exists ( select *  
																                                   from empautitempcprocitem x
																                                        inner join empautoriza aut on aut.e54_autori = x.e73_autori
																                                  where x.e73_pcprocitem = pcprocitem.pc81_codprocitem
																                                    and aut.e54_anulad is null )
																
																             )
																        ) ";
	 $sWhereVerificaAutorizacao .= " and pc11_codigo is not null ";
	 $sWhereVerificaAutorizacao .= " and pc10_correto = 't' ";
	 $sWhereVerificaAutorizacao .= " and pc10_instit  = ".db_getsession('DB_instit');
   
   
   $sSqlVerificaAutorizacao    = $clsolicita->sql_query(null,"*",null,$sWhereVerificaAutorizacao);
   
   $rsDadosVerificaAutorizacao = $clsolicita->sql_record($sSqlVerificaAutorizacao);
  
   if ( $clsolicita->numrows == 0 ) {
     $erro       = true;
     $erro_msg   = "Já existe autorização para a solicitação : {$pc10_numero} ";
   }
   
}

if (isset($pc80_codproc)&&trim($pc80_codproc)!=""){
	
     $sql       = "select extract(year from pc80_data) as pc80_data from pcproc where pc80_codproc = $pc80_codproc";
     $resultado = $clpcproc->sql_record($sql);

     if ($clpcproc->numrows > 0){
          db_fieldsmemory($resultado,0);

          if ($pc80_data < db_getsession("DB_anousu")){
               $erro     = true;
               $erro_msg = "Processo de compras de exercicio anterior. Autorizacao cancelada.";
          }
     }
     $oDaoAcordoPcprocitem = db_utils::getDao("acordopcprocitem");
     $sSqlDadosAcordo      = $oDaoAcordoPcprocitem->sql_query_acordo(null,
                                                          "ac26_acordo",
                                                           null,
                                                          "pc80_codproc = {$pc80_codproc}
                                                           and (ac16_acordosituacao  not in (2,3))"
                                                           );
                                                           
     $rsDadosAcordo = $oDaoAcordoPcprocitem->sql_record($sSqlDadosAcordo);
     if ($oDaoAcordoPcprocitem->numrows > 0) {
    
       $iNumeroAcordo = db_utils::fieldsMemory($rsDadosAcordo, 0)->ac26_acordo;
       $erro          = true;
       $erro_msg      = "O processo de compras está vinculado ao acordo {$iNumeroAcordo}, ";
       $erro_msg     .= "Não será possivel gerar Autorizações de Empenho diretamente.";
   } 
}


$res_pcparam = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit")));

if ($clpcparam->numrows > 0){
     db_fieldsmemory($res_pcparam,0);

     if ($pc30_contrandsol == "t"){
          $dbwhere = "";
          if (isset($pc80_codproc) && trim(@$pc80_codproc)!=""){
               $dbwhere = "pc81_codproc = $pc80_codproc and ";
          }

          if (isset($pc10_numero) && trim(@$pc10_numero)!=""){
               $dbwhere = "pc11_numero = $pc10_numero and ";
          }

          if (strlen(trim(@$dbwhere)) > 0){ 
               $sql = "select pc81_codproc, pc11_numero
                       from solandam 
			                      inner join solandpadrao  on solandpadrao.pc47_solicitem  = solandam.pc43_solicitem and 
                                                        solandpadrao.pc47_ordem      = solandam.pc43_ordem
	                          inner join solicitemprot on solicitemprot.pc49_solicitem = solandam.pc43_solicitem
                            inner join solicitem     on solicitem.pc11_codigo        = solandam.pc43_solicitem
                            left  join pcprocitem    on pcprocitem.pc81_solicitem    = solandam.pc43_solicitem
                       where $dbwhere 
		                         solandam.pc43_ordem >= 4 and
		                         solandam.pc43_depto = ".db_getsession("DB_coddepto");

               $result_andam = @pg_exec($sql);
               $numrows      = @pg_numrows($result_andam);

               if ($numrows == 0){
                    $db_botao = false;     
               }
          }

          //echo "<br><br>".$sql;
     }
}
if ($erro==true){
     db_msgbox($erro_msg);
     echo "<script>document.location.href = 'com1_selproc001.php'</script>";
}

if((isset($pc80_codproc) && trim($pc80_codproc)!="") || (isset($anul) && trim($anul)!="") || (isset($pc10_numero) && trim($pc10_numero)!="")){
 $action = 'com1_gerautaut001.php?';
}

$setafoco = "document.form1.pc80_codproc.focus();";
$anular = "false";
if(isset($anul) && trim($anul)!=""){
  $setafoco = "document.form1.e54_autori.focus();";
  $anular = "true";
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_abre(){
  <?
    echo '
      erro=0;
      resp=0;
      if(document.form1.pc80_codproc.value == "" && document.form1.pc10_numero.value == ""){
	erro++;
	resp=1; 
      }
      ';
      if(isset($anul) && trim($anul)!=""){
	echo '
	if(document.form1.e54_autori.value == "" && document.form1.pc80_codproc.value == "" && document.form1.pc10_numero.value== ""){
	  erro++;
	  resp=2; 
	}else{
	  erro=0;
	  resp=0;
	}
	';
      }
      echo '
      if(erro>0){
	if(resp == 1 && erro==1){
	  document.form1.pc80_codproc.focus();
	  alert("Informe o código do processo ou solicitação.");
	}else if(resp == 2 && erro==2){
	  document.form1.e54_autori.focus();
	  alert("Informe o código da autorização, processo ou solicitação.");
	}
      }else{
	document.form1.submit();
      }';
  ?>
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<!-- Melhoria Acresentada, para dar foco no campo destino, apos submeter a consulta  -->
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="<?=$setafoco?>document.form1.e54_destin.focus();" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<form name="form1" method="post" action="<?=($action)?>">
<fieldset style="margin-top: 10px; width: 350px;">
  <legend><strong>Gerar autorização de Empenho</strong></legend>
  
<table border='0'>
  <?


  
  if(isset($anul)){
    echo "<td align='left' nowrap title='$Te54_autori'>";db_ancora(@$Le54_autori,"js_pesquisa_autori(true);",1);echo "</td>";
    echo "<td>";
    db_input("e54_autori",8,$Ie54_autori,true,"text",4,"onchange='js_pesquisa_autori(false);'"); 
    db_input("anul",8,0,true,"hidden",3); 
    echo "</td>";
    $campo = "e54_autori";
  }
  ?>
  <tr> 
  <?

  $texthidden = "hidden";
  if((!isset($pc80_codproc) || (isset($pc80_codproc) && trim($pc80_codproc)=="")) && (!isset($pc10_numero) || (isset($pc10_numero) && trim($pc10_numero)==""))){
    $texthidden = "text";
    echo "<td align='left' nowrap title='$Tpc80_codproc'>";db_ancora(@$Lpc80_codproc,"js_pesquisa_pcproc(true);",1);echo "</td>";
    echo "<td align='left' nowrap>";
  }else
  {
          
  ?>
    <td nowrap title="<?=@$Te54_destin?>">
      <?=$Le54_destin?>
    </td>
    <td>
      <?
         db_input("e54_destin",40,$Ie54_destin,true,"text",1, "", "", "", "", 30); 
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc12_tipo?>">
       <?=@$Lpc12_tipo?>
    </td>
    <td> 
    <?
    $parampesquisa = true;
    if(isset($tipodecompra)){
      $e54_codcom = $tipodecompra;
    }
    if((isset($pc12_tipo) && $pc12_tipo=='' || !isset($pc12_tipo)) && !isset($tipodecompra)){
      $somadata = $clpcparam->sql_record($clpcparam->sql_query_file($instit,"pc30_tipcom as e54_codcom"));
      if($clpcparam->numrows>0){
	    db_fieldsmemory($somadata,0);
      }
    }
    $result_tipocompra=$clpctipocompra->sql_record($clpctipocompra->sql_query_file(null,"pc50_codcom,pc50_descr"));
    db_selectrecord("e54_codcom",$result_tipocompra,true,1,"","","","","js_reload(this.value)");
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_tipol?>">
       <?=@$Le54_tipol?>
    </td>
    <td> 
<?
if(isset($tipodecompra) || isset($e54_codcom)){
  if(isset($e54_codcom) && empty($tipodecompra)){
    $tipodecompra=$e54_codcom;
  }
  $result=$clcflicita->sql_record($clcflicita->sql_query_file(null,"l03_tipo,l03_descr",'',"l03_codcom=$tipodecompra"));
  if($clcflicita->numrows>0){
    db_selectrecord("e54_tipol",$result,true,1,"","","");
    $dop=1;
  }else{
    $e54_tipol='';
    $e54_numerl='';
    db_input('e54_tipol',8,$Ie54_tipol,true,'text',3);
    $dop=3;
  }  
}else{   
  $dop=3;
  $e54_tipol='';
  $e54_numerl='';
  db_input('e54_tipol',8,$Ie54_tipol,true,'text',3);
}  
?>
       <?=@$Le54_numerl?>
<?
db_input('e54_numerl',8,$Ie54_numerl,true,'text',$dop);
?>
    </td>
  </tr>  

  <tr>
    <td nowrap title="<?=@$Te54_codtipo?>">
      <?=$Le54_codtipo?>
    </td>
    <td>
      <?
        $result=$clemptipo->sql_record($clemptipo->sql_query_file(null,"e41_codtipo,e41_descr"));
        db_selectrecord("e54_codtipo",$result,true,1);
      ?>
      </td>
  </tr>
<?
$db_opcao=1;
?>
  
  <tr>
    <td nowrap title="<?=@$Te54_praent?>">
       <?=@$Le54_praent?>
    </td>
    <td> 
<?
db_input('e54_praent',30,$Ie54_praent,true,'text',$db_opcao, "", "", "", "", 30);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_entpar?>">
       <?=@$Le54_entpar?>
    </td>
    <td> 
<?
db_input('e54_entpar',30,$Ie54_entpar,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_conpag?>">
       <?=@$Le54_conpag?>
    </td>
    <td> 
<?
db_input('e54_conpag',30,$Ie54_conpag,true,'text',$db_opcao, "", "", "", "", 30);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_codout?>">
       <?=@$Le54_codout?>
    </td>
    <td> 
<?
db_input('e54_codout',30,$Ie54_codout,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_contat?>">
       <?=@$Le54_contat?>
    </td>
    <td> 
<?
db_input('e54_contat',20,$Ie54_contat,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_telef?>">
       <?=@$Le54_telef?>
    </td>
    <td> 
<?
db_input('e54_telef',20,$Ie54_telef,true,'text',$db_opcao,"")
?>
    </td>
  </tr>


<?

if (isset($pc10_numero)&&($pc10_numero!="")){       

$result_sol = $clsolicita->sql_record($clsolicita->sql_query_numero_solicita($pc10_numero,"distinct(pc10_numero),pc80_codproc"));
$verifica=$clsolicita->numrows;
//solicita com fornecedor sugerido. Se $verifica for igual a zero, nao possui PC 
if($verifica==0){
         $result_solicita = $clsolicita->sql_record($clsolicita->sql_query_solicita($pc10_numero,"pc10_resumo"));
         if($clsolicita->numrows > 0){
              db_fieldsmemory($result_solicita,0);
               $clsolicita->pc10_resumo = stripslashes(addslashes(chop($pc10_resumo)));
        
   ?>
   <tr>
   <td nowrap title="<?=@$Tpc10_resumo?>">
   <?=@$Lpc10_resumo?>
   </td>
   <td>
   <?
     @$pc10_resumo = stripslashes($pc10_resumo);
     db_textarea('pc10_resumo',7,80,$Ipc10_resumo,true,'text',2,"");
                                                                       
    }
}
//verifica se tem solicitação juntada -1 PC  e várias solicitações

if ($verifica>0)
      {
        
     db_fieldsmemory($result_sol,0); 
     $result_proc = $clpcproc->sql_record($clpcproc->sql_query_proc_solicita($pc80_codproc,"distinct(pc10_numero),pc10_resumo"));
     if($clpcproc->numrows<2){
         $result_solicita = $clsolicita->sql_record($clsolicita->sql_query_solicita($pc10_numero,"pc10_resumo"));
      if($clsolicita->numrows> 0)
          {
           db_fieldsmemory($result_solicita,0);
           $clsolicita->pc10_resumo = chop($pc10_resumo); 
           }
    ?>

        <tr>
        <td nowrap title="<?=@$Tpc10_resumo?>">
     <?=@$Lpc10_resumo?>
        </td>
        <td>
     <?
     @$pc10_resumo = stripslashes($pc10_resumo);
     db_textarea('pc10_resumo',7,80,$Ipc10_resumo,true,'text',2,"");
          }
      }

       
}                               



?>
</td>
</tr>
                          
  <?
  }
  ?>

<?
    
     db_input("pc80_codproc",8,$Ipc80_codproc,true,"$texthidden",4,"onchange='js_pesquisa_pcproc(false);'"); 
	   db_input('sol',6,0,true,'hidden',3);

?>
    
    <?
    if($texthidden=="text"){
      echo "<tr>";
      echo "  <td align='left' nowrap title='$Tpc10_numero'>";db_ancora(@$Lpc10_numero,"js_pesquisa_solicita(true);",1);echo "</td>";
      echo "  <td align='left' nowrap>";
    }
      db_input("pc10_numero",8,$Ipc10_numero,true,$texthidden,4,"onchange='js_pesquisa_solicita(false);'"); 
    if($texthidden=="text"){
      echo "  </td>";
      echo "</tr>";
    }
    ?>

</table>
</fieldset>
<input name="lancar" type="button" onclick='js_abre();'  value="Enviar dados" <?=($db_botao==false?"disabled":"")?>>
</form>
</center>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
function js_reload(valor) {
  /*
  * Melhroia adicionada para nao se perder o valor do destino, apos selecionar outros campos
  */
  var vr_destino = document.form1.e54_destin.value;
  //alert(vr_destino);
  location.href = "com1_selproc001.php?sol="+document.form1.sol.value+"&pc10_numero="
   +document.form1.pc10_numero.value+"&pc80_codproc="+document.form1.pc80_codproc.value+"&tipodecompra="+valor+"&e54_destin="+vr_destino;
}
//--------------------------------
function js_pesquisa_solicita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_solicita','func_solicita.php?gerautori=true&anular=<?=($anular)?>&funcao_js=parent.js_mostrasolicita1|pc10_numero','Pesquisa',true);
  }else{
     if(document.form1.pc10_numero.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_solicita','func_solicita.php?gerautori=true&anular=<?=($anular)?>&pesquisa_chave='+document.form1.pc10_numero.value+'&funcao_js=parent.js_mostrasolicita','Pesquisa',false);
     }
  }
}
function js_mostrasolicita(chave,erro){
  if(erro==true){ 
    document.form1.pc10_numero.focus(); 
    document.form1.pc10_numero.value = ''; 
  }
}
function js_mostrasolicita1(chave1,chave2){
  document.form1.pc10_numero.value = chave1;
  db_iframe_solicita.hide();
}
function js_pesquisa_autori(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empautoriza','func_empautoriza.php?anul=ok&funcao_js=parent.js_mostraautori1|e54_autori','Pesquisa',true);
  }else{
     if(document.form1.e54_autori.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_empautoriza','func_empautoriza.php?anul=ok&pesquisa_chave='+document.form1.e54_autori.value+'&funcao_js=parent.js_mostraautori','Pesquisa',false);
     }
  }
}
function js_mostraautori(chave,erro){
  if(erro==true){ 
    document.form1.e54_autori.focus(); 
    document.form1.e54_autori.value = ''; 
  }
}
function js_mostraautori1(chave1,chave2){
  document.form1.e54_autori.value = chave1;
  db_iframe_empautoriza.hide();
}

function js_pesquisa_pcproc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pcproc','func_pcproc.php?funcao_js=parent.js_mostrapcproc1|pc80_codproc','Pesquisa',true);
  }else{
     if(document.form1.pc80_codproc.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pcproc','func_pcproc.php?pesquisa_chave='+document.form1.pc80_codproc.value+'&funcao_js=parent.js_mostrapcproc','Pesquisa',false);
     }
  }
}
function js_mostrapcproc(chave,erro){
  if(erro==true){ 
    document.form1.pc80_codproc.focus(); 
    document.form1.pc80_codproc.value = ''; 
  }
}
function js_mostrapcproc1(chave1,chave2){
  document.form1.pc80_codproc.value = chave1;
  db_iframe_pcproc.hide();
}
//--------------------------------
</script>
</body>
</html>