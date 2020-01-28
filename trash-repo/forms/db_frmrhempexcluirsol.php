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

include("classes/db_gerfcom_classe.php");
$clgerfcom = new cl_gerfcom;
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

?>
<table align="center" border="0">
  <form name="form1" method="post" action="">
  <tr>
    <td align="right" nowrap title="Digite o Ano / Mes de competência" >
    <strong>Ano / Mês :&nbsp;&nbsp;</strong>
    </td>
    <td>
      <?
       if(!isset($DBtxt23) || (isset($DBtxt23) && (trim($DBtxt23) == "" || $DBtxt23 == 0))){
         $DBtxt23 = db_anofolha();
       }
       db_input('DBtxt23',4,$IDBtxt23,true,'text',2,'')
      ?>
      &nbsp;/&nbsp;
      <?
       if(!isset($DBtxt25) || (isset($DBtxt23) && (trim($DBtxt25) == "" || $DBtxt25 == 0))){
         $DBtxt25 = db_mesfolha();
       }
       db_input('DBtxt25',2,$IDBtxt25,true,'text',2,'')
      ?>
    </td>
  </tr>
  <tr>
    <td align="right"><b>Ponto:</b</td>
    <td>
     <?
       $x = array("s"=>"Salário","c"=>"Complementar","d"=>"13o. Salário","r"=>"Rescisão","a"=>"Adiantamento");
       db_select('ponto',$x,true,4,"onchange='return js_verifica();'");
     ?>
    </td>
     </tr>
     <?
     if(isset($ponto) && $ponto == "c"){
       $result_semest = $clgerfcom->sql_record($clgerfcom->sql_query_file($DBtxt23,$DBtxt25,null,null,"distinct r48_semest as seq"));
       if($clgerfcom->numrows > 0){
	 echo "
	  <tr>
	    <td align='right' title='Nro. Complementar'><strong>Nro. Complementar:</strong></td>
            <td>
	      <select name='rh40_sequencia' onChange='document.form1.submit();'>
		<option value = '0'>Todos
	      ";
	      for($i=0; $i<$clgerfcom->numrows; $i++){
		db_fieldsmemory($result_semest, $i);
    $selected = "";
    if (isset($rh40_sequencia) && trim($rh40_sequencia) != ""){
      if ($rh40_sequencia == $seq){
        $selected = "SELECTED";
      }
    }
		echo "<option value = '$seq' $selected>$seq";
	      }
	 echo "
	    </td>
	  </tr>
	      ";
       }else{
         echo "
               <tr>
                 <td colspan='2' align='center'>
                   <font color='red'>Sem complementar para este período.</font>
                 </td>
               </tr>
              ";
       }
     }
     ?>
  <tr>
    <td colspan="2" align="center" height="50"> 
      <input  name="processa" id="processa" type="submit" value="Processar" onClick="return js_verifica();" <?=($mostra==false&&isset($processa)?"disabled":"")?>>
    </td>
  </tr>
  <tr>
    <td colspan="2">
<?    
  if ($mostra == true){
    $sql          = $clrhsolicita->sql_query_pcproc(null,"distinct pc10_numero,pc80_codproc","pc10_numero","rh33_anousu   = $ano and
                                                                                                            rh33_mesusu   = $mes and
                                                                                                            rh33_seqfolha = $rh40_sequencia and
                                                                                                            rh33_siglaarq = '$siglaarq'     and
                                                                                                            rh33_instit   = ".db_getsession("DB_instit"));
    $sql_disabled = $clrhsolicita->sql_query_pcproc(null,"distinct pc10_numero,pc80_codproc","pc10_numero","rh33_anousu   = $ano and
                                                                                                            rh33_mesusu   = $mes and
                                                                                                            rh33_seqfolha = $rh40_sequencia and
                                                                                                            rh33_siglaarq = '$siglaarq'     and
                                                                                                            rh33_instit   = ".db_getsession("DB_instit")." and 
                                                                                                            pc80_codproc is not null");
    $campos = "pc10_numero,pc80_codproc";

    $cliframe_seleciona_sol->campos        = $campos;
    $cliframe_seleciona_sol->legenda       = "SOLICITAÇÕES DE COMPRAS";
    $cliframe_seleciona_sol->sql           = $sql;
    $cliframe_seleciona_sol->sql_disabled  = $sql_disabled;
    $cliframe_seleciona_sol->iframe_height = "400";
    $cliframe_seleciona_sol->iframe_width  = "400";
    $cliframe_seleciona_sol->iframe_nome   = "solicita";
    $cliframe_seleciona_sol->chaves        = "pc10_numero";
    $cliframe_seleciona_sol->js_marcador   = "";
    $cliframe_seleciona_sol->dbscrip       = "";
    $cliframe_seleciona_sol->iframe_seleciona(1);

    db_input("solicitacoes",50,0,true,"hidden",3);
  }
    ?>
    </td>
  </tr>
  </form>
</table>
</body>
<script>
function js_verifica(){
  var tam          = solicita.document.form1.length;
  var contador     = 0;
  var separador    = "";
  var solicitacoes = "";
  var erro         = true;

  for (i = 0; i < tam; i++){
    if (solicita.document.form1.elements[i].type == "checkbox"){
      if (solicita.document.form1.elements[i].checked == true){
        solicitacoes += separador+solicita.document.form1.elements[i].value;
        separador     = "_";
        contador++;
      }
    }
  }

  if (contador == 0 && tam > 0){
    alert("Selecione uma ou mais solicitações!");
    erro = false;
    return erro;
  }

  document.form1.solicitacoes.value = solicitacoes;

  return erro;
}
</script>  
</html>