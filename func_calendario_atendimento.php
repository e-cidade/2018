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
 
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");

// parse_str($HTTP_SERVER_VARS['QUERY_STRING']); // ta com o globals desativado no php -- Crestani

db_postmemory($_GET);
db_postmemory($_POST);

$nome_objeto_data = '';

if ( isset($seleciona) || isset($acumulaseleciona) || isset($lembrete) ){

   //db_postmemory($HTTP_POST_VARS,2);

   if( !isset($lembrete) ) {

     $sql = " update tarefaagenda  
                 set at77_datavalidade = '".date("Y-m-d",db_getsession("DB_datausu"))."' 
               where at77_tarefa       = $tarefa 
                 and at77_id_usuario = ".db_getsession("DB_id_usuario");

     if( isset($acumulaseleciona) ) {
       $sql .= " and extract(month from at77_dataagenda) = $mes ";
     }  

     $result = pg_exec($sql);

     $tarefa = $HTTP_POST_VARS["tarefa"];
   }else{
     // lembrete fica com tarefa 0
     $tarefa = 0;
     $tarefa_old = $HTTP_POST_VARS["tarefa"];
   }
 

   $ano    = $HTTP_POST_VARS["ano"];
   $mes    = $HTTP_POST_VARS["mes"];
   $observacao = $HTTP_POST_VARS["observacao"];
   $at77_hora = $HTTP_POST_VARS["at77_hora"];
   if( $at77_hora == ''){
     $at77_hora = '08:30';
   }

   $dia=array();
   $tecnico=array();
   reset($HTTP_POST_VARS);
   for($i=0;$i<count($HTTP_POST_VARS);$i++){

     if( substr(key($HTTP_POST_VARS),0,4) == "dia_"){
       $dia[] = $HTTP_POST_VARS[key($HTTP_POST_VARS)];
     }
     if( substr(key($HTTP_POST_VARS),0,8) == "tecnico_"){
       $tecnico[] = $HTTP_POST_VARS[key($HTTP_POST_VARS)];
     }

     next($HTTP_POST_VARS);

   }
 
   if( count($dia) == 0 && $observacao != "" && $tarefa == 0 ){
     $dia[0] = date("d");
     $datavalidade = "'".date("Y-m-d")."'";
   }else{
     $datavalidade = "null";
   }

   if( count($dia) > 0 ){

     // grava agendamento do usuario que esta logado

     for ($i=0;$i<count($dia);$i++){

       $sql = " select at77_sequen from tarefaagenda 
                where at77_tarefa = $tarefa
                  and at77_id_usuario   = ".db_getsession("DB_id_usuario")."
                  and at77_usuenvolvido = ".db_getsession("DB_id_usuario")."
                  and at77_dataagenda   = '".$ano."-".$mes."-".$dia[$i]."'
                  and at77_hora         = '".trim($at77_hora)."'"."
                  and at77_observacao = '$observacao'";

       $result = pg_exec($sql);

       if ( pg_numrows($result) == 0 ){

          $sql = " insert into tarefaagenda values(nextval('tarefaagenda_at77_sequen_seq'),
                                              $tarefa,
                                              ".db_getsession("DB_id_usuario").",
                                              ".db_getsession("DB_id_usuario").",
                                              '".date('Y-m-d',db_getsession("DB_datausu"))."',
                                              '".$ano."-".$mes."-".$dia[$i]."',
                                              '$observacao',
                                              $datavalidade,
                                              '$at77_hora',$cliente,'$at77_tiporetorno','$at77_periodo')";
       }else{

          $sql = " update tarefaagenda  set at77_datavalidade = null,
                                            at77_observacao = '$observacao',
                                            at77_hora = '$at77_hora',
                                            at77_cliente = $cliente,
                                            at77_tiporetorno = '$at77_tiporetorno',
                                            at77_periodo='$at77_periodo'
                   where at77_tarefa = $tarefa 
                     and at77_id_usuario   = ".db_getsession("DB_id_usuario")."
                     and at77_usuenvolvido = ".db_getsession("DB_id_usuario")."
                     and at77_dataagenda   = '".$ano."-".$mes."-".$dia[$i]."'
                     and at77_hora         = '".trim($at77_hora)."'";

       }
       $result = pg_exec($sql);

     }
                                              
     // grava agendamento dos usuarios envolvidos

     for ($i=0;$i<count($dia);$i++){

       for($t=0;$t<count($tecnico);$t++){

         $sql = " select at77_sequen from tarefaagenda 
                  where at77_tarefa = $tarefa
                    and at77_id_usuario   = ".db_getsession("DB_id_usuario")."
                    and at77_usuenvolvido = ".$tecnico[$t]."
                    and at77_dataagenda   = '".$ano."-".$mes."-".$dia[$i]."' 
                    and at77_observacao = '$observacao'";

         $result = pg_exec($sql);

         if ( pg_numrows($result) == 0 ){

           $sql = " insert into tarefaagenda values(nextval('tarefaagenda_at77_sequen_seq'),
                                              $tarefa,
                                              ".db_getsession("DB_id_usuario").",
                                              ".$tecnico[$t].",
                                              '".date('Y-m-d',db_getsession("DB_datausu"))."',
                                              '".$ano."-".$mes."-".$dia[$i]."',
                                              '$observacao',
                                              $datavalidade,
                                              '$at77_hora',$cliente,'$at77_tiporetorno','$at77_periodo')";

         }else{

           $sql = " update tarefaagenda  set at77_datavalidade = null,
                                             at77_observacao = '$observacao',
                                             at77_hora = '$at77_hora',
                                             at77_cliente = $cliente, 
                                             at77_tiporetorno = '$at77_tiporetorno',
                                             at77_periodo='$at77_periodo'
                    where at77_tarefa = $tarefa 
                      and at77_id_usuario   = ".db_getsession("DB_id_usuario")."
                      and at77_usuenvolvido = ".$tecnico[$t]."
                      and at77_dataagenda   = '".$ano."-".$mes."-".$dia[$i]."'";

         }

         $result = pg_exec($sql);
       
       }

     }
 
   }

   if( isset($lembrete) ){
     $tarefa = $tarefa_old;
   }
}

class calendario{ 
   var $sem;//Array com os dias da semana como índice 
   var $mes;//Array com os meses do ano 
   var $nome_objeto_data;
   var $shutdown_function = "";
   var $tarefa = "";
   var $tarefadescricao = "";
   var $data = "";
   var $mensagem_tarefa = "";
   var $observacao = "";
   var $hora= "";
   var $fechar = "";

   function inicializa(){//Atribui valores para $sem e $mes.
       $this->sem=array('Sun'=>1,'Mon'=>2,'Tue'=>3,'Wed'=>4,'Thu'=>5,'Fri'=>6,'Sat'=>7);
       $this->mes=array('1'=>'JANEIRO','2'=>'FEVEREIRO','3'=>'MARÇO','4'=>'ABRIL','5'=>'MAIO','6'=>'JUNHO','7'=>'JULHO','8'=>'AGOSTO','9'=>'SETEMBRO','10'=>'OUTUBRO','11'=>'NOVEMBRO','12'=>'DEZEMBRO');
   } 

   function aux($i){//Complementa a tabela com espaços em branco 
      $retval=""; 
      for($k=0;$k < $i;$k++){ 
         $retval.="<td >&nbsp;</td>"; 
      } 
      return $retval; 
   }
   function cria($dia,$mes,$ano,$marca=0){
      $this->inicializa(); 
      $last  = date ("d", mktime (0,0,0,$mes+1,0,$ano)); /* Inteiro do ultimo dia do mês */
      if($last<$dia) {
        $dia = $last;
      }
      $verf=date ("d/n/Y", mktime (0,0,0,$mes,$dia,$ano)); /* Corrige qualquer data invalida */ 
      
      $this->data = date ("Y-m-d", mktime (0,0,0,$mes,$dia,$ano));/*Corrige qualquer data invalida*/ 

      $pieces = explode("/",$verf); 
      $dia    = $pieces[0]; 
      $mes    = $pieces[1]; 
      $ano    = $pieces[2]; 
      $diasem = date("D", mktime (0,0,0,$mes,1,$ano));/*String com dia da semana em inglês*/ 
      $str = "";
      if($this->sem[$diasem] != 1){/*Se dia semana diferente de domingo,completa com colunas em branco*/ 
         $valor=$this->sem[$diasem]-1; 
         $str="<tr align=center >".$this->aux($valor);
      } 

     // verifica tarefa e seus agendamentos
     $sql = "select at40_descr, at77_dataagenda, at77_usuenvolvido, at77_observacao, at77_hora
             from tarefa
                  left join tarefaagenda on at77_tarefa = at40_sequencial and at77_datavalidade is null
             where at40_sequencial = ".$this->tarefa." 
               and at77_id_usuario = ".db_getsession("DB_id_usuario");
     $result = pg_exec($sql);
     if( pg_numrows($result) > 0 ){
       $this->tarefadescricao = pg_result($result,0,'at40_descr');
       $this->observacao = pg_result($result,0,'at77_observacao');
       $this->hora = pg_result($result,0,'at77_hora');
     }
     $qual_dia_agenda=array();
     $tecnico_envol = array();
     for($dd=0;$dd<pg_numrows($result);$dd++){
       $diaagenda = pg_result($result,$dd,'at77_dataagenda');
       if( $diaagenda != '' && (substr($diaagenda,5,2)+0) == $mes && substr($diaagenda,0,4)==$ano ){
         $qual_dia_agenda[(substr($diaagenda,8,2))+0] = (substr($diaagenda,8,2)+0);
       }
       if( $diaagenda != '' && (substr($diaagenda,5,2)+0 != $mes || substr($diaagenda,0,4)!=$ano) ){
         $this->mensagem_tarefa = "Mês: ".substr($diaagenda,5,2)." Ano: ".substr($diaagenda,0,4);
       }
       $tecnico_env = pg_result($result,$dd,'at77_usuenvolvido');
       $tecnico_envol[$tecnico_env] = $tecnico_env;
     }

      for($i=1;$i < ($last+1);$i++){       //; pega todos os dias do mes informado....
         $diasem=date ("D", mktime (0,0,0,$mes,$i,$ano)); 
         if($this->sem[$diasem] == 1){
            $str.="<tr align=\"center\" >";
            $s="$i";
         }else{ 
            $s="$i"; 
         } 
         $data_script = "$ano-$mes-$s";
         $str.="<td     ";
         if($marca != 0){  // marca o dia atual em laranja
            if($dia == $i){
               $str.= " bgcolor=orange ";  // marcar o dia atual
            }
	 } 
	 if($this->sem[$diasem] == 1 || $this->sem[$diasem] == 7){
     $str.="  bgcolor=#CCCCCC ";
   } 
   $str .="  align='left'>
                 <input id=\"dia_".$this->sem[$diasem]."_$s\" name=\"dia_$s\" value=\"$s\" type=\"checkbox\" ".(isset($qual_dia_agenda[$s])?"checked":"")." style=\"color:black\" onclick=\"js_troca_estado_dia('dia_$s','$s');\"> $s 
              </td>";
         
   if($this->sem[$diasem] == 7){
     $str.="</tr>"; 
   } 

   } 
      $diasem=date ("D", mktime (0,0,0,$mes,$last,$ano)); 
      if($this->sem[$diasem] != 7){
         $valor=7-$this->sem[$diasem]; 
         $str=$str.$this->aux($valor)."</tr>"; 
      } 

      $str="
       
  <table border=\"1\"  cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" height=\"100%\">
  <tr>
   <td align=\"center\" valign=\"top\">


     <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
     <tr align=\"center\">
       <td width=\"100%\" colspan=\"7\" nowrap >
       <script>
       function js_retorna_ano(){
         document.form1.ano.value = Number(document.form1.ano.value) - 1;
         document.form1.submit();
       }
       function js_avanca_ano(){
         document.form1.ano.value = Number(document.form1.ano.value) + 1;
         document.form1.submit();
       }
       function js_retorna_mes(){
         document.form1.mes.value = Number(document.form1.mes.value) - 1;
         if ( document.form1.mes == 0 ){
           document.form1.mes.value = 1;
           document.form1.ano.value = Number(document.form1.ano.value) - 1;
         }
         document.form1.submit();
       }
       function js_avanca_mes(){
         document.form1.mes.value = Number(document.form1.mes.value) + 1;
         if ( document.form1.mes.value > 12 ){
           document.form1.mes.value = 1;
           document.form1.ano.value = Number(document.form1.ano.value) + 1;
         }
         document.form1.submit();
       }
       function js_seleciona_mes(mes){
         document.form1.mes.value = mes;
         document.form1.submit();
       }
 
       </script>
        <FONT SIZE='1' FACE='Verdana' COLOR='black'>
           <a href=\"#\" onclick='js_retorna_ano()'> << </a>
           	   $ano
	         <a href=\"#\" onclick='js_avanca_ano()'> >> </a>   
        </font>

       </td>
     </tr>
     <tr align=\"center\">
       <td width=\"100%\" colspan=\"7\" nowrap>
        <FONT SIZE='1' FACE='Verdana' COLOR='black'>
           <a href=\"#\" onclick='js_retorna_mes()'> << </a>
         ".$this->mes[$mes]."
	         <a href=\"#\" onclick='js_avanca_mes()'> >> </a>   
	      </FONT> 
       </td>

     </tr>


     <tr align=\"center\">
       <td width=\"100%\" colspan=\"7\" nowrap>
        <FONT SIZE='1' FACE='Verdana' COLOR='black'>
           <a href=\"#\" onclick='js_seleciona_mes(1)' title='Janeiro'> Jan </a>&nbsp
           <a href=\"#\" onclick='js_seleciona_mes(2)' title='Fevereiro'> Fev </a>&nbsp
           <a href=\"#\" onclick='js_seleciona_mes(3)' title='Março'> Mar </a>&nbsp
           <a href=\"#\" onclick='js_seleciona_mes(4)' title='Abril'> Abr </a>&nbsp
           <a href=\"#\" onclick='js_seleciona_mes(5)' title='Maio'> Mai </a>&nbsp
           <a href=\"#\" onclick='js_seleciona_mes(6)' title='Junho'> Jun </a>&nbsp
           <a href=\"#\" onclick='js_seleciona_mes(7)' title='Julho'> Jul </a>&nbsp
           <a href=\"#\" onclick='js_seleciona_mes(8)' title='Agosto'> Ago </a>&nbsp
           <a href=\"#\" onclick='js_seleciona_mes(9)' title='Setembro'> Set </a>&nbsp
           <a href=\"#\" onclick='js_seleciona_mes(10)' title='Outubro'> Out </a>&nbsp
           <a href=\"#\" onclick='js_seleciona_mes(11)' title='Novembro'> Nov </a>&nbsp
           <a href=\"#\" onclick='js_seleciona_mes(12)' title='Dezembro'> Dez </a>
	      </FONT> 
       </td>

     </tr>



     <tr align=\"center\">
       <td width=\"100%\" colspan=\"7\" nowrap><br>
        <FONT SIZE='1' FACE='Verdana' COLOR='black'>
        </FONT> 
       </td>
     </tr>
     <tr align=\"center\">
       <td class=dias width=\"10%\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'><input style=\"color:black;border:none\" id=\"domingo\" name=\"domingo\" value=\"Domingo\" type=\"button\" onclick=\"js_troca_estado(1);\"> </font></td>
       <td class=dias width=\"10%\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'><input style=\"color:black;border:none\"  id=\"segunda\" name=\"segunda\" value=\"Segunda\" type=\"button\" onclick=\"js_troca_estado(2);\"> </font></td>
       <td class=dias width=\"20%\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'><input style=\"color:black;border:none\"  id=\"terca\" name=\"terca\" value=\"Terça\" type=\"button\" onclick=\"js_troca_estado(3);\"> </font></td>
       <td class=dias width=\"20%\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'><input style=\"color:black;border:none\"  id=\"quarta\" name=\"quarta\" value=\"Quarta\" type=\"button\" onclick=\"js_troca_estado(4);\"> </font></td>
       <td class=dias width=\"20%\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'><input style=\"color:black;border:none\"  id=\"quinta\" name=\"quinta\" value=\"Quinta\" type=\"button\" onclick=\"js_troca_estado(5);\"> </font></td>
       <td class=dias width=\"10%\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'><input style=\"color:black;border:none\"  id=\"sexta\" name=\"sexta\" value=\"Sexta\" type=\"button\" onclick=\"js_troca_estado(6);\"> </font></td>
       <td class=dias width=\"10%\"><FONT SIZE='1' FACE='Verdana' COLOR='darkgreen'><input style=\"color:black;border:none\"  id=\"sabado\" name=\"sabado\" value=\"Sábado\" type=\"button\" onclick=\"js_troca_estado(7);\"> </font></td>
       </tr>
       ".$str."
     <tr><td colspan='7'>&nbsp</td></tr>
     <tr><td colspan='7'>&nbsp</td></tr>
     <tr><td colspan='7'>&nbsp</td></tr>
     <tr><td colspan='7'><a href='#' onclick='js_pesquisa_tarefa(".$this->tarefa.")' >Tarefa: ".$this->tarefa."</a>&nbsp&nbsp<strong><font size='1'>".($this->mensagem_tarefa!=''?'  Agenda:'.$this->mensagem_tarefa:"")."</font></strong></td></tr>
     ";

     

     $str .= "
     <tr>
       <td colspan='7'>
         <font size='1'>".$this->tarefadescricao."</font>
       </td>
     </tr>
     <tr>
       <td valign'top' ><a href='#' onclick='js_abreclientes()'>Cliente : </a> </td>
       <td colspan='7'>
        <select name='cliente' > 
          <option value='0'>Selecione</option> ";

     $sSqlClientes = "
     select at01_codcli,trim(at01_nomecli)||'-'||db12_uf as at01_nomecli 
     from clientes      
     left join db_uf on at01_uf = db12_codigo ";

     $sSqlQuemAcessa = "select db_depusu.coddepto from db_depart
                        inner join db_depusu on db_depart.coddepto = db_depusu.coddepto
                        where db_depusu.id_usuario = ".db_getsession("DB_id_usuario");
     $rResult = pg_query($sSqlQuemAcessa);

     if(pg_result($rResult,0,0) != 708 && pg_result($rResult,0,0) != 717){
       $sSqlClientes .= " where at01_status is true "; 
     
     }

     $sSqlClientes .= "
     order by at01_nomecli";
     $rsClientes   = db_query($sSqlClientes);
     for ($iCli=0; $iCli < pg_num_rows($rsClientes);$iCli++) {
       $oCliente = db_utils::fieldsMemory($rsClientes,$iCli);
       $str .= " <option value='{$oCliente->at01_codcli}'>{$oCliente->at01_nomecli}</option> ";
       
     }

     $str .= "    
        </select>
       </td>
     </tr>
     <tr>
       <td  valign='top'>Obs.:</td>
       <td colspan='6' valign='top'>
         <textarea cols='50' name='observacao'>".$this->observacao."</textarea>
       </td>
     </tr>
     <tr>
       <td  valign='top'>Hora:</td>
       <td colspan='2' valign='top'>
         ";
      if($this->hora != ""){

         $str .= "<input type='text' value='".$this->hora."' name='at77_hora' size='5' maxlength='5'>";
      
      }else{

         $str .= "<select name='at77_hora' >
                  <option value='07:00' ".(date('H')=='07'?' selected ':'').">07:00</option>
                  <option value='07:30' ".(date('H')=='07' && date('i')>=30?' selected ':'').">07:30</option>
                  <option value='08:00' ".(date('H')=='08'?' selected ':'').">08:00</option>
                  <option value='08:30' ".(date('H')=='08' && date('i')>=30?' selected ':'').">08:30</option>
                  <option value='09:00' ".(date('H')=='09'?' selected ':'').">09:00</option>
                  <option value='09:30' ".(date('H')=='09' && date('i')>=30?' selected ':'').">09:30</option>
                  <option value='10:00' ".(date('H')=='10'?' selected ':'').">10:00</option>
                  <option value='10:30' ".(date('H')=='10' && date('i')>=30?' selected ':'').">10:30</option>
                  <option value='11:00' ".(date('H')=='11'?' selected ':'').">11:00</option>
                  <option value='11:30' ".(date('H')=='11' && date('i')>=30?' selected ':'').">11:30</option>
                  <option value='12:00' ".(date('H')=='12'?' selected ':'').">12:00</option>
                  <option value='12:30' ".(date('H')=='12' && date('i')>=30?' selected ':'').">12:30</option>
                  <option value='13:00' ".(date('H')=='13'?' selected ':'').">13:00</option>
                  <option value='13:30' ".(date('H')=='13' && date('i')>=30?' selected ':'').">13:30</option>
                  <option value='14:00' ".(date('H')=='14'?' selected ':'').">14:00</option>
                  <option value='14:30' ".(date('H')=='14' && date('i')>=30?' selected ':'').">14:30</option>
                  <option value='15:00' ".(date('H')=='15'?' selected ':'').">15:00</option>
                  <option value='15:30' ".(date('H')=='15' && date('i')>=30?' selected ':'').">15:30</option>
                  <option value='16:00' ".(date('H')=='16'?' selected ':'').">16:00</option>
                  <option value='16:30' ".(date('H')=='16' && date('i')>=30?' selected ':'').">16:30</option>
                  <option value='17:00' ".(date('H')=='17'?' selected ':'').">17:00</option>
                  <option value='17:30' ".(date('H')=='17' && date('i')>=30?' selected ':'').">17:30</option>
                  <option value='18:00' ".(date('H')=='18'?' selected ':'').">18:00</option>
                  <option value='18:30' ".(date('H')=='18' && date('i')>=30?' selected ':'').">18:30</option>
                  <option value='19:00' ".(date('H')=='19'?' selected ':'').">19:00</option>
                  <option value='19:30' ".(date('H')=='19' && date('i')>=30?' selected ':'').">19:30</option>
                  <option value='20:00' ".(date('H')=='20'?' selected ':'').">20:00</option>
                  <option value='20:30' ".(date('H')=='20' && date('i')>=30?' selected ':'').">20:30</option>
                 </select>
                 ";
      }

      $str .= "

      </td>
       <td  valign='top'>Período:</td>
       <td colspan='3'><select name='at77_periodo'>
                       <option value='M'>Manhã</option>
                       <option value='T'>Tarde</option>
                       <option value='N'>Noite</option>
                       <option value='D'>Dia</option>
                       </select>
       </td>
 
     </tr>  
     <tr>
       <td  valign='top'>Retorno:</td>
       <td colspan='7'><select name='at77_tiporetorno'>
                       <option value='Sem Retorno'>Sem Retorno</option>
                       <option value='Telefonar'>Telefonar</option>
                       <option value='Enviar Email'>Enviar Email</option>
                       <option value='Acordado'>Acordado</option>
                       <option value='Visita Externa'>Visita Externa</option>
                       <option value='Visita Interna'>Visita Interna</option>
                       </select>
       </td>

     </tr>     
     <tr>
     <td colspan=\"7\" align=\"center\">
     ";
     if ( $this->tarefa != 0 ){
       $str .= "
       <input name='seleciona' value='Agendar' type='submit' > &nbsp&nbsp
       <input name='acumulaseleciona' value='Acumular na Agenda' type='submit' > &nbsp&nbsp ";
     }
     $str .= "<input name='lembrete' value='Lembrete' type='submit'>";

     $str .= "
     <input name='fechar' value='Fechar' type='button' onclick='parent.".($this->fechar!=""?"$this->fechar":"db_iframe_tarefa_agenda").".hide();'>
     <input name='tarefa' value='".$this->tarefa."' type='hidden' >
     <input name='ano' value='".$ano."' type='hidden' >
     <input name='mes' value='".$mes."' type='hidden' >
     </td>
     </tr>";


     $str .= "
     <tr><td align='left' colspan='7'><font size='1'><strong>Tarefa Agendada por Outros</strong></font></td></tr>
     ";
     $sql = "select distinct at77_tarefa,at40_descr,at77_datainclusao, nome as nomeoutros,at77_dataagenda, at77_observacao
             from tarefaagenda 
                  inner join db_usuarios  on  db_usuarios.id_usuario = at77_id_usuario      
                  left  join tarefa on at40_sequencial = at77_tarefa
             where at77_tarefa = '".$this->tarefa."' 
               and at77_datavalidade is null 
               and at77_id_usuario != ".db_getsession("DB_id_usuario")."
               and at77_usuenvolvido = ".db_getsession("DB_id_usuario");
     $result = pg_exec($sql);
      
     if (pg_numrows($result)>0){

       for($i=0;$i<pg_numrows($result);$i++){

         $descr         = pg_result($result,$i,'at77_observacao');
         $nomeoutros    = pg_result($result,$i,'nomeoutros');
         $datainclusao  = db_formatar(pg_result($result,$i,'at77_datainclusao'),'d');
         $dataagenda    = db_formatar(pg_result($result,$i,'at77_dataagenda'),'d');
         $str .= "<tr><td colspan='7'><font size='1'><strong>$nomeoutros</strong> - $descr - $datainclusao - $dataagenda </font></td></tr>";
       }

     }

     $str .= "
     </table>
     </td>

     <td width=\"20%\" valign=\"top\" nowrap>
     <table>
     ";
     $sql = "select * from (select distinct atendareatec.at27_usuarios,db_usuarios.nome 
             from atendareatec       
                  inner join db_usuarios  on  db_usuarios.id_usuario = atendareatec.at27_usuarios      
                  inner join atendcadarea  on  atendcadarea.at26_sequencial = atendareatec.at27_atendcadarea 
            where usuarioativo= '1'
             ) as x
               order by nome ";
     $result = pg_exec($sql);

     for($i=0;$i<pg_numrows($result);$i++){
       $idtec = pg_result($result,$i,'at27_usuarios');
       if( $idtec != db_getsession("DB_id_usuario") ){

         $str .= "<tr>
                    <td nowrap>
                      <input style=\"color:black;size='1'\" ".(isset($tecnico_envol[$idtec])?"checked":"")." id=\"tecnico_$idtec\" name=\"tecnico_$idtec\" value=\"$idtec\" type=\"checkbox\" onclick=\"js_troca_estado('tecnico_$idtec','$idtec');\">";

         $sql = "select at45_sequencial 
                 from tarefaenvol
                 where at45_tarefa = ".$this->tarefa." and at45_usuario = $idtec and at45_perc = 100";

         $result_envol = pg_exec($sql);
         if( pg_numrows($result_envol) > 0 ){
           $str .= "<font size='1' color='red'>".pg_result($result,$i,'nome')."</font></td></tr>";
         }else{
           $str .= "<font size='1'>".pg_result($result,$i,'nome')."</font></td></tr>";
         }
       }
     }


     $str .= "
       </table>
     </td>
     <td width=\"40%\" valign=\"top\">
       <table>
         <tr>
           <td align='center'><strong>Tarefas à realizar</strong>
           </td>
         </tr> ";

     $sql = "select * 
               from ( select distinct 
                             at77_tarefa,
                             at40_descr,
                             at77_datainclusao, 
                             at77_hora
                        from tarefaagenda 
                             left join tarefa on at40_sequencial = at77_tarefa
                       where at77_id_usuario = ".db_getsession("DB_id_usuario")." 
                         and at77_dataagenda = '".$this->data."'
                         and at77_datavalidade is null ) as x 
              order by at77_datainclusao,at77_hora ";

     $result = pg_exec($sql);
      
     if (pg_numrows($result)>0){

       for($i=0;$i<pg_numrows($result);$i++){
         $codtarefa = pg_result($result,$i,'at77_tarefa');
         $descr  = pg_result($result,$i,'at40_descr');
         $datainclusao  = db_formatar(pg_result($result,$i,'at77_datainclusao'),'d');
         $hora  = pg_result($result,$i,'at77_hora');
         $str .= "<tr>
                    <td><font size='1'><strong>
                      <a href='#' onclick='js_pesquisa_tarefa($codtarefa)' >$codtarefa</a>&nbsp<strong><a href='#' onclick='js_abre_agendamento($codtarefa)'>$hora</a></strong></strong> - $descr - $datainclusao </font>
                    <script>
                    
                    function js_abre_agendamento(tarefa){
                          parent.js_OpenJanelaIframe('','db_iframe_tarefa_agenda_hora','func_calendario_atendimento.php?tarefa='+tarefa,'Pesquisa',true);
                    }

                    </script>
                    </td>
                  </tr>";
       }

     }else{
       $str .= "<tr>
                  <td><font size='1'>&nbsp</font></td>
                </tr>";
     }


     $str .= "
     </table>
     </td>
     </tr>
     </table> ";
     echo $str; 

   } 

} 

$clcalendario = new calendario; 
if (!isset($mes)){
  $mes = date("n",db_getsession("DB_datausu"));
}
if (!isset($ano)){
  $ano = date("Y",db_getsession("DB_datausu"));
}
if(isset($shutdown_function)){
  $clcalendario->shutdown_function = $shutdown_function;
}
if (isset($nome_objeto_data)){
  $clcalendario->nome_objeto_data = $nome_objeto_data;
}

echo "<html>";
echo "<head>
<script>

function js_troca_estado(qual_botao){
  elementos = document.form1;
  q = '';
  for( i=0; i < elementos.length ; i++ ){
    if ( elementos[i].id.substring(0,5) == 'dia_'+qual_botao ){ 
      elementos[i].checked = !elementos[i].checked;
    }
  }
}

function js_troca_estado_dia(qual_botao,conteudo){
  document.getElementById(qual_botao).style.color = (document.getElementById(qual_botao).style.color=='red'?document.getElementById(qual_botao).style.color='black':'red');
}

function js_pesquisa_tarefa (tarefa){
  if ( tarefa == 0 ){
    alert('Agenda sem Tarefa Cadastrada.');
  }else{
    parent.js_OpenJanelaIframe('','db_iframe_tarefa_cons','ate2_contarefa001.php?menu=false&chavepesquisa='+tarefa,'Pesquisa',true);
  }
}


function js_abreclientes(){
  if(document.form1.cliente.value != 0){ 
    parent.js_OpenJanelaIframe('','db_iframe_cliente_cons','ate1_clientes002.php?menu=false&chavepesquisa='+document.form1.cliente.value,'Pesquisa',true);
  }else{
    parent.js_OpenJanelaIframe('','db_iframe_cliente_cons','ate1_clientes001.php?menu=false','Pesquisa',true);
  }

}
</script>
</head>";

echo "<body>";
echo "<form name=\"form1\" method=\"post\">";

if( ! isset($tarefa) ){
  $clcalendario->tarefa = 0;
}else{
  $clcalendario->tarefa = $tarefa;
}
if( isset($fechar) ){
  $clcalendario->fechar = $fechar;
}
$clcalendario->cria(date("d",db_getsession("DB_datausu")),$mes,$ano,1);
echo "</form>";
echo "</body>";
echo "</html>";

?> 
<script>
function janela(d,m,a){ 
  <?
  echo "parent.document.getElementById('".$nome_objeto_data."_dia').value = (d<10?'0'+d:d);\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_mes').value = (m<10?'0'+m:m);\n";
  echo "parent.document.getElementById('".$nome_objeto_data."_ano').value = a;\n";
  echo "parent.js_comparaDatas".$nome_objeto_data."((d<10?'0'+d:d),(m<10?'0'+m:m),a);\n";
  echo "parent.iframe_data_".$nome_objeto_data.".hide();\n";

  if (isset($shutdown_function) && ($shutdown_function!='none')){
      echo $shutdown_function."\n";
  }

  ?>
}

</script>