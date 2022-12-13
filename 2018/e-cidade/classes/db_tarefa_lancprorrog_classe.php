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

//MODULO: atendimento
//CLASSE DA ENTIDADE tarefa_lancprorrog
class cl_tarefa_lancprorrog { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $at58_sequencial = 0; 
   var $at58_tarefalanc = 0; 
   var $at58_tarefa = 0; 
   var $at58_diaini_dia = null; 
   var $at58_diaini_mes = null; 
   var $at58_diaini_ano = null; 
   var $at58_diaini = null; 
   var $at58_diafim_dia = null; 
   var $at58_diafim_mes = null; 
   var $at58_diafim_ano = null; 
   var $at58_diafim = null; 
   var $at58_previsao = 0; 
   var $at58_tipoprevisao = null; 
   var $at58_horainidia = null; 
   var $at58_horafim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at58_sequencial = int4 = Sequencial 
                 at58_tarefalanc = int4 = Sequência 
                 at58_tarefa = int4 = Codigo da Tarefa 
                 at58_diaini = date = Dia inicial 
                 at58_diafim = date = Dia final 
                 at58_previsao = int4 = Previsao 
                 at58_tipoprevisao = char(1) = Tipo de previsao 
                 at58_horainidia = char(5) = Hora inicial 
                 at58_horafim = char(5) = Hora final 
                 ";
   //funcao construtor da classe 
   function cl_tarefa_lancprorrog() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tarefa_lancprorrog"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->at58_sequencial = ($this->at58_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["at58_sequencial"]:$this->at58_sequencial);
       $this->at58_tarefalanc = ($this->at58_tarefalanc == ""?@$GLOBALS["HTTP_POST_VARS"]["at58_tarefalanc"]:$this->at58_tarefalanc);
       $this->at58_tarefa = ($this->at58_tarefa == ""?@$GLOBALS["HTTP_POST_VARS"]["at58_tarefa"]:$this->at58_tarefa);
       if($this->at58_diaini == ""){
         $this->at58_diaini_dia = ($this->at58_diaini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at58_diaini_dia"]:$this->at58_diaini_dia);
         $this->at58_diaini_mes = ($this->at58_diaini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at58_diaini_mes"]:$this->at58_diaini_mes);
         $this->at58_diaini_ano = ($this->at58_diaini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at58_diaini_ano"]:$this->at58_diaini_ano);
         if($this->at58_diaini_dia != ""){
            $this->at58_diaini = $this->at58_diaini_ano."-".$this->at58_diaini_mes."-".$this->at58_diaini_dia;
         }
       }
       if($this->at58_diafim == ""){
         $this->at58_diafim_dia = ($this->at58_diafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at58_diafim_dia"]:$this->at58_diafim_dia);
         $this->at58_diafim_mes = ($this->at58_diafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at58_diafim_mes"]:$this->at58_diafim_mes);
         $this->at58_diafim_ano = ($this->at58_diafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at58_diafim_ano"]:$this->at58_diafim_ano);
         if($this->at58_diafim_dia != ""){
            $this->at58_diafim = $this->at58_diafim_ano."-".$this->at58_diafim_mes."-".$this->at58_diafim_dia;
         }
       }
       $this->at58_previsao = ($this->at58_previsao == ""?@$GLOBALS["HTTP_POST_VARS"]["at58_previsao"]:$this->at58_previsao);
       $this->at58_tipoprevisao = ($this->at58_tipoprevisao == ""?@$GLOBALS["HTTP_POST_VARS"]["at58_tipoprevisao"]:$this->at58_tipoprevisao);
       $this->at58_horainidia = ($this->at58_horainidia == ""?@$GLOBALS["HTTP_POST_VARS"]["at58_horainidia"]:$this->at58_horainidia);
       $this->at58_horafim = ($this->at58_horafim == ""?@$GLOBALS["HTTP_POST_VARS"]["at58_horafim"]:$this->at58_horafim);
     }else{
       $this->at58_sequencial = ($this->at58_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["at58_sequencial"]:$this->at58_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($at58_sequencial){ 
      $this->atualizacampos();
     if($this->at58_tarefalanc == null ){ 
       $this->erro_sql = " Campo Sequência nao Informado.";
       $this->erro_campo = "at58_tarefalanc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at58_tarefa == null ){ 
       $this->erro_sql = " Campo Codigo da Tarefa nao Informado.";
       $this->erro_campo = "at58_tarefa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at58_diaini == null ){ 
       $this->at58_diaini = "null";
     }
     if($this->at58_diafim == null ){ 
       $this->at58_diafim = "null";
     }
     if($this->at58_previsao == null ){ 
       $this->at58_previsao = "0";
     }
     if($at58_sequencial == "" || $at58_sequencial == null ){
       $result = db_query("select nextval('tarefa_lancprorrog_at58_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tarefa_lancprorrog_at58_sequencial_seq do campo: at58_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at58_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tarefa_lancprorrog_at58_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $at58_sequencial)){
         $this->erro_sql = " Campo at58_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at58_sequencial = $at58_sequencial; 
       }
     }
     if(($this->at58_sequencial == null) || ($this->at58_sequencial == "") ){ 
       $this->erro_sql = " Campo at58_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tarefa_lancprorrog(
                                       at58_sequencial 
                                      ,at58_tarefalanc 
                                      ,at58_tarefa 
                                      ,at58_diaini 
                                      ,at58_diafim 
                                      ,at58_previsao 
                                      ,at58_tipoprevisao 
                                      ,at58_horainidia 
                                      ,at58_horafim 
                       )
                values (
                                $this->at58_sequencial 
                               ,$this->at58_tarefalanc 
                               ,$this->at58_tarefa 
                               ,".($this->at58_diaini == "null" || $this->at58_diaini == ""?"null":"'".$this->at58_diaini."'")." 
                               ,".($this->at58_diafim == "null" || $this->at58_diafim == ""?"null":"'".$this->at58_diafim."'")." 
                               ,$this->at58_previsao 
                               ,'$this->at58_tipoprevisao' 
                               ,'$this->at58_horainidia' 
                               ,'$this->at58_horafim' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Prorrogacao de tarefas ($this->at58_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Prorrogacao de tarefas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Prorrogacao de tarefas ($this->at58_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at58_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at58_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9057,'$this->at58_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1552,9057,'','".AddSlashes(pg_result($resaco,0,'at58_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1552,9064,'','".AddSlashes(pg_result($resaco,0,'at58_tarefalanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1552,9065,'','".AddSlashes(pg_result($resaco,0,'at58_tarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1552,9058,'','".AddSlashes(pg_result($resaco,0,'at58_diaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1552,9059,'','".AddSlashes(pg_result($resaco,0,'at58_diafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1552,9060,'','".AddSlashes(pg_result($resaco,0,'at58_previsao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1552,9061,'','".AddSlashes(pg_result($resaco,0,'at58_tipoprevisao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1552,9062,'','".AddSlashes(pg_result($resaco,0,'at58_horainidia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1552,9063,'','".AddSlashes(pg_result($resaco,0,'at58_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at58_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update tarefa_lancprorrog set ";
     $virgula = "";
     if(trim($this->at58_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at58_sequencial"])){ 
       $sql  .= $virgula." at58_sequencial = $this->at58_sequencial ";
       $virgula = ",";
       if(trim($this->at58_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "at58_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at58_tarefalanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at58_tarefalanc"])){ 
       $sql  .= $virgula." at58_tarefalanc = $this->at58_tarefalanc ";
       $virgula = ",";
       if(trim($this->at58_tarefalanc) == null ){ 
         $this->erro_sql = " Campo Sequência nao Informado.";
         $this->erro_campo = "at58_tarefalanc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at58_tarefa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at58_tarefa"])){ 
       $sql  .= $virgula." at58_tarefa = $this->at58_tarefa ";
       $virgula = ",";
       if(trim($this->at58_tarefa) == null ){ 
         $this->erro_sql = " Campo Codigo da Tarefa nao Informado.";
         $this->erro_campo = "at58_tarefa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at58_diaini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at58_diaini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at58_diaini_dia"] !="") ){ 
       $sql  .= $virgula." at58_diaini = '$this->at58_diaini' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at58_diaini_dia"])){ 
         $sql  .= $virgula." at58_diaini = null ";
         $virgula = ",";
       }
     }
     if(trim($this->at58_diafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at58_diafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at58_diafim_dia"] !="") ){ 
       $sql  .= $virgula." at58_diafim = '$this->at58_diafim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at58_diafim_dia"])){ 
         $sql  .= $virgula." at58_diafim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->at58_previsao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at58_previsao"])){ 
        if(trim($this->at58_previsao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["at58_previsao"])){ 
           $this->at58_previsao = "0" ; 
        } 
       $sql  .= $virgula." at58_previsao = $this->at58_previsao ";
       $virgula = ",";
     }
     if(trim($this->at58_tipoprevisao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at58_tipoprevisao"])){ 
       $sql  .= $virgula." at58_tipoprevisao = '$this->at58_tipoprevisao' ";
       $virgula = ",";
     }
     if(trim($this->at58_horainidia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at58_horainidia"])){ 
       $sql  .= $virgula." at58_horainidia = '$this->at58_horainidia' ";
       $virgula = ",";
     }
     if(trim($this->at58_horafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at58_horafim"])){ 
       $sql  .= $virgula." at58_horafim = '$this->at58_horafim' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($at58_sequencial!=null){
       $sql .= " at58_sequencial = $this->at58_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at58_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9057,'$this->at58_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at58_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1552,9057,'".AddSlashes(pg_result($resaco,$conresaco,'at58_sequencial'))."','$this->at58_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at58_tarefalanc"]))
           $resac = db_query("insert into db_acount values($acount,1552,9064,'".AddSlashes(pg_result($resaco,$conresaco,'at58_tarefalanc'))."','$this->at58_tarefalanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at58_tarefa"]))
           $resac = db_query("insert into db_acount values($acount,1552,9065,'".AddSlashes(pg_result($resaco,$conresaco,'at58_tarefa'))."','$this->at58_tarefa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at58_diaini"]))
           $resac = db_query("insert into db_acount values($acount,1552,9058,'".AddSlashes(pg_result($resaco,$conresaco,'at58_diaini'))."','$this->at58_diaini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at58_diafim"]))
           $resac = db_query("insert into db_acount values($acount,1552,9059,'".AddSlashes(pg_result($resaco,$conresaco,'at58_diafim'))."','$this->at58_diafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at58_previsao"]))
           $resac = db_query("insert into db_acount values($acount,1552,9060,'".AddSlashes(pg_result($resaco,$conresaco,'at58_previsao'))."','$this->at58_previsao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at58_tipoprevisao"]))
           $resac = db_query("insert into db_acount values($acount,1552,9061,'".AddSlashes(pg_result($resaco,$conresaco,'at58_tipoprevisao'))."','$this->at58_tipoprevisao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at58_horainidia"]))
           $resac = db_query("insert into db_acount values($acount,1552,9062,'".AddSlashes(pg_result($resaco,$conresaco,'at58_horainidia'))."','$this->at58_horainidia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at58_horafim"]))
           $resac = db_query("insert into db_acount values($acount,1552,9063,'".AddSlashes(pg_result($resaco,$conresaco,'at58_horafim'))."','$this->at58_horafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prorrogacao de tarefas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at58_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Prorrogacao de tarefas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at58_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at58_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at58_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at58_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9057,'$at58_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1552,9057,'','".AddSlashes(pg_result($resaco,$iresaco,'at58_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1552,9064,'','".AddSlashes(pg_result($resaco,$iresaco,'at58_tarefalanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1552,9065,'','".AddSlashes(pg_result($resaco,$iresaco,'at58_tarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1552,9058,'','".AddSlashes(pg_result($resaco,$iresaco,'at58_diaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1552,9059,'','".AddSlashes(pg_result($resaco,$iresaco,'at58_diafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1552,9060,'','".AddSlashes(pg_result($resaco,$iresaco,'at58_previsao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1552,9061,'','".AddSlashes(pg_result($resaco,$iresaco,'at58_tipoprevisao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1552,9062,'','".AddSlashes(pg_result($resaco,$iresaco,'at58_horainidia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1552,9063,'','".AddSlashes(pg_result($resaco,$iresaco,'at58_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tarefa_lancprorrog
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at58_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at58_sequencial = $at58_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prorrogacao de tarefas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at58_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Prorrogacao de tarefas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at58_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at58_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:tarefa_lancprorrog";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $at58_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from tarefa_lancprorrog ";
     $sql .= "      inner join tarefa  on  tarefa.at40_sequencial = tarefa_lancprorrog.at58_tarefa";
     $sql .= "      inner join tarefa_lanc  on  tarefa_lanc.at36_sequencia = tarefa_lancprorrog.at58_tarefalanc";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tarefa.at40_responsavel";
     $sql .= "      inner join db_usuarios  as a on   a.id_usuario = tarefa_lanc.at36_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($at58_sequencial!=null ){
         $sql2 .= " where tarefa_lancprorrog.at58_sequencial = $at58_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $at58_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from tarefa_lancprorrog ";
     $sql2 = "";
     if($dbwhere==""){
       if($at58_sequencial!=null ){
         $sql2 .= " where tarefa_lancprorrog.at58_sequencial = $at58_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>