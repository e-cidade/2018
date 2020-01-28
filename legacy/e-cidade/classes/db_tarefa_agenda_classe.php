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
//CLASSE DA ENTIDADE tarefa_agenda
class cl_tarefa_agenda { 
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
   var $at13_sequencial = 0; 
   var $at13_tarefa = 0; 
   var $at13_dia_dia = null; 
   var $at13_dia_mes = null; 
   var $at13_dia_ano = null; 
   var $at13_dia = null; 
   var $at13_horaini = null; 
   var $at13_horafim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at13_sequencial = int4 = Sequencia 
                 at13_tarefa = int4 = Tarefa 
                 at13_dia = date = Data 
                 at13_horaini = char(5) = Hora inicial 
                 at13_horafim = char(5) = Hora final 
                 ";
   //funcao construtor da classe 
   function cl_tarefa_agenda() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tarefa_agenda"); 
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
       $this->at13_sequencial = ($this->at13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["at13_sequencial"]:$this->at13_sequencial);
       $this->at13_tarefa = ($this->at13_tarefa == ""?@$GLOBALS["HTTP_POST_VARS"]["at13_tarefa"]:$this->at13_tarefa);
       if($this->at13_dia == ""){
         $this->at13_dia_dia = ($this->at13_dia_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at13_dia_dia"]:$this->at13_dia_dia);
         $this->at13_dia_mes = ($this->at13_dia_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at13_dia_mes"]:$this->at13_dia_mes);
         $this->at13_dia_ano = ($this->at13_dia_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at13_dia_ano"]:$this->at13_dia_ano);
         if($this->at13_dia_dia != ""){
            $this->at13_dia = $this->at13_dia_ano."-".$this->at13_dia_mes."-".$this->at13_dia_dia;
         }
       }
       $this->at13_horaini = ($this->at13_horaini == ""?@$GLOBALS["HTTP_POST_VARS"]["at13_horaini"]:$this->at13_horaini);
       $this->at13_horafim = ($this->at13_horafim == ""?@$GLOBALS["HTTP_POST_VARS"]["at13_horafim"]:$this->at13_horafim);
     }else{
       $this->at13_sequencial = ($this->at13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["at13_sequencial"]:$this->at13_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($at13_sequencial){ 
      $this->atualizacampos();
     if($this->at13_tarefa == null ){ 
       $this->erro_sql = " Campo Tarefa nao Informado.";
       $this->erro_campo = "at13_tarefa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at13_dia == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "at13_dia_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at13_horaini == null ){ 
       $this->erro_sql = " Campo Hora inicial nao Informado.";
       $this->erro_campo = "at13_horaini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at13_horafim == null ){ 
       $this->erro_sql = " Campo Hora final nao Informado.";
       $this->erro_campo = "at13_horafim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($at13_sequencial == "" || $at13_sequencial == null ){
       $result = db_query("select nextval('tarefa_agenda_at13_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tarefa_agenda_at13_sequencial_seq do campo: at13_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at13_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tarefa_agenda_at13_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $at13_sequencial)){
         $this->erro_sql = " Campo at13_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at13_sequencial = $at13_sequencial; 
       }
     }
     if(($this->at13_sequencial == null) || ($this->at13_sequencial == "") ){ 
       $this->erro_sql = " Campo at13_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tarefa_agenda(
                                       at13_sequencial 
                                      ,at13_tarefa 
                                      ,at13_dia 
                                      ,at13_horaini 
                                      ,at13_horafim 
                       )
                values (
                                $this->at13_sequencial 
                               ,$this->at13_tarefa 
                               ,".($this->at13_dia == "null" || $this->at13_dia == ""?"null":"'".$this->at13_dia."'")." 
                               ,'$this->at13_horaini' 
                               ,'$this->at13_horafim' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tarefas agendadas ($this->at13_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tarefas agendadas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tarefas agendadas ($this->at13_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at13_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at13_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8767,'$this->at13_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1497,8767,'','".AddSlashes(pg_result($resaco,0,'at13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1497,8771,'','".AddSlashes(pg_result($resaco,0,'at13_tarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1497,8768,'','".AddSlashes(pg_result($resaco,0,'at13_dia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1497,8769,'','".AddSlashes(pg_result($resaco,0,'at13_horaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1497,8770,'','".AddSlashes(pg_result($resaco,0,'at13_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at13_sequencial=null,$dbwhere=null) { 
      $this->atualizacampos();
     $sql = " update tarefa_agenda set ";
     $virgula = "";
     if(trim($this->at13_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at13_sequencial"])){ 
       $sql  .= $virgula." at13_sequencial = $this->at13_sequencial ";
       $virgula = ",";
       if(trim($this->at13_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencia nao Informado.";
         $this->erro_campo = "at13_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at13_tarefa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at13_tarefa"])){ 
       $sql  .= $virgula." at13_tarefa = $this->at13_tarefa ";
       $virgula = ",";
       if(trim($this->at13_tarefa) == null ){ 
         $this->erro_sql = " Campo Tarefa nao Informado.";
         $this->erro_campo = "at13_tarefa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at13_dia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at13_dia_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at13_dia_dia"] !="") ){ 
       $sql  .= $virgula." at13_dia = '$this->at13_dia' ";
       $virgula = ",";
       if(trim($this->at13_dia) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "at13_dia_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at13_dia_dia"])){ 
         $sql  .= $virgula." at13_dia = null ";
         $virgula = ",";
         if(trim($this->at13_dia) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "at13_dia_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at13_horaini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at13_horaini"])){ 
       $sql  .= $virgula." at13_horaini = '$this->at13_horaini' ";
       $virgula = ",";
       if(trim($this->at13_horaini) == null ){ 
         $this->erro_sql = " Campo Hora inicial nao Informado.";
         $this->erro_campo = "at13_horaini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at13_horafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at13_horafim"])){ 
       $sql  .= $virgula." at13_horafim = '$this->at13_horafim' ";
       $virgula = ",";
       if(trim($this->at13_horafim) == null ){ 
         $this->erro_sql = " Campo Hora final nao Informado.";
         $this->erro_campo = "at13_horafim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($at13_sequencial!=null){
       $sql .= " at13_sequencial = $this->at13_sequencial";
     }
     if($dbwhere!=null) {
	     if($at13_sequencial!=null){
	     	 $sql .= " and $dbwhere ";
	     }
	     else {
	     	 $sql .= " $dbwhere ";
	     }
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at13_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8767,'$this->at13_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at13_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1497,8767,'".AddSlashes(pg_result($resaco,$conresaco,'at13_sequencial'))."','$this->at13_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at13_tarefa"]))
           $resac = db_query("insert into db_acount values($acount,1497,8771,'".AddSlashes(pg_result($resaco,$conresaco,'at13_tarefa'))."','$this->at13_tarefa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at13_dia"]))
           $resac = db_query("insert into db_acount values($acount,1497,8768,'".AddSlashes(pg_result($resaco,$conresaco,'at13_dia'))."','$this->at13_dia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at13_horaini"]))
           $resac = db_query("insert into db_acount values($acount,1497,8769,'".AddSlashes(pg_result($resaco,$conresaco,'at13_horaini'))."','$this->at13_horaini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at13_horafim"]))
           $resac = db_query("insert into db_acount values($acount,1497,8770,'".AddSlashes(pg_result($resaco,$conresaco,'at13_horafim'))."','$this->at13_horafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tarefas agendadas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tarefas agendadas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at13_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at13_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8767,'$at13_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1497,8767,'','".AddSlashes(pg_result($resaco,$iresaco,'at13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1497,8771,'','".AddSlashes(pg_result($resaco,$iresaco,'at13_tarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1497,8768,'','".AddSlashes(pg_result($resaco,$iresaco,'at13_dia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1497,8769,'','".AddSlashes(pg_result($resaco,$iresaco,'at13_horaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1497,8770,'','".AddSlashes(pg_result($resaco,$iresaco,'at13_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tarefa_agenda
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at13_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at13_sequencial = $at13_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tarefas agendadas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tarefas agendadas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at13_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:tarefa_agenda";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $at13_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefa_agenda ";
     $sql2 = "";
     if($dbwhere==""){
       if($at13_sequencial!=null ){
         $sql2 .= " where tarefa_agenda.at13_sequencial = $at13_sequencial "; 
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
   function sql_query_envol ( $at13_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefa_agenda ";
     $sql .= "      inner join tarefaenvol on tarefaenvol.at45_tarefa = tarefa_agenda.at13_tarefa";
     $sql .= "      inner join tarefa      on tarefa.at40_sequencial  = tarefa_agenda.at13_tarefa";
     $sql2 = "";
     if($dbwhere==""){
       if($at13_sequencial!=null ){
         $sql2 .= " where tarefa_agenda.at13_sequencial = $at13_sequencial "; 
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
   function sql_query_file ( $at13_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefa_agenda ";
     $sql2 = "";
     if($dbwhere==""){
       if($at13_sequencial!=null ){
         $sql2 .= " where tarefa_agenda.at13_sequencial = $at13_sequencial "; 
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
  
  // Usada para geracao de datas quando tipo de previsao de tarefa igual d = dias
  function retorna_diafinal($mes,$ano) {
  	switch ($mes) {
  		case  1 :
  		case  3 :
  		case  5 :
  		case  7 :
  		case  8 :
   		case 10 :
  		case 12 : $dia = 31;       
  				  break;
  		case  2 : if(($ano%4) == 0) {
  					  $dia = 29;	
  				  }
  				  else {
  					  $dia = 28;	
  				  }
  				  break;
  		default : $dia = 30;
  	}
  	
  	return($dia);
  }
  
  function gera_agenda($cltarefaparam,$cltarefa,$erro_msg) {
  	global $at53_horasdia, $at53_horaini_manha, $at53_horafim_manha, $at53_horaini_tarde, $at53_horafim_tarde, $data_inicial; 
  	
  	$sqlerro = false;
  	
	$result  = $cltarefaparam->sql_record($cltarefaparam->sql_query(null,"*",null,null));
	if($cltarefaparam->numrows > 0) {
	    db_fieldsmemory($result,0);
	}

    $this->at13_tarefa = $cltarefa->at40_sequencial;

    if (!isset($cltarefa->at40_diaini) or $cltarefa->at40_diaini == "" or $cltarefa->at40_diaini == "null") {
      return $sqlerro;
    }
    if (!isset($cltarefa->at40_diafim) or $cltarefa->at40_diafim == "" or $cltarefa->at40_diafim == "null") {
      return $sqlerro;
    }

    $data_inicial = $cltarefa->at40_diaini;
    $data_final   = $cltarefa->at40_diafim;
    
    $cltarefa->at40_horainidia = sprintf("%02d",substr($cltarefa->at40_horainidia,0,2)).":".
                                 sprintf("%02d",substr($cltarefa->at40_horainidia,3,2));

    $cltarefa->at40_horafim    = sprintf("%02d",substr($cltarefa->at40_horafim,0,2)).":".
                                 sprintf("%02d",substr($cltarefa->at40_horafim,3,2));
    
    $primeiro_dia = true;
        
    while($data_inicial < $data_final) {

	    if($primeiro_dia == true) {  // grava o 1 registro com a hora inicial da tarefa com a 1 data de inicio
	    
            $this->at13_horaini = $cltarefa->at40_horainidia;
            
            if($cltarefa->at40_horainidia <= $at53_horafim_manha) {
			    $this->at13_horafim = $at53_horafim_manha;
			    $hora_inicial       = $at53_horaini_tarde;
			    $hora_final         = $at53_horafim_tarde;
            } else {
        		if($cltarefa->at40_horainidia <= $at53_horafim_tarde) {
				    $this->at13_horafim = $at53_horafim_tarde;
        		} else { 
				    $this->at13_horafim = $at53_horaini_manha;
        		}
        		    	
			    $hora_inicial = $at53_horaini_manha;
			    $hora_final   = $at53_horafim_manha;
            }

            $this->at13_dia = $data_inicial;
            $this->incluir(null);
          
            if($this->erro_status == 0) {
			    $sqlerro  = true;
		        $erro_msg = $this->erro_msg;
		        break;
		    }
		    
            $primeiro_dia = false;
            		 
		    if ($this->at13_horafim == $at53_horafim_tarde) {
		        $sqlsoma = "select '$data_inicial'::date + '1 day'::interval as data_inicial";
			$resultsoma = pg_exec($sqlsoma) or die($sqlsoma);
			db_fieldsmemory($resultsoma, 0);
//		    	$data_inicial++;
		    	continue;
		    }
		 	
            $this->at13_dia     = $data_inicial;
            $this->at13_horaini = $hora_inicial;
		    $this->at13_horafim = $hora_final;

			$hora_inicial = $hora_final;
			
            $this->incluir(null);
          
            if($this->erro_status == 0) {
			    $sqlerro  = true;
		        $erro_msg = $this->erro_msg;
		        break;
		    }

        } else {
        	
            $this->at13_dia     = $data_inicial;
            $this->at13_horaini = $at53_horaini_manha;
		    $this->at13_horafim = $at53_horafim_manha;

            $this->incluir(null);
          
            if($this->erro_status == 0) {
			    $sqlerro  = true;
		        $erro_msg = $this->erro_msg;
		        break;
		    }
		    
            $this->at13_dia     = $data_inicial;
            $this->at13_horaini = $at53_horaini_tarde;
		    $this->at13_horafim = $at53_horafim_tarde;

            $this->incluir(null);
          
            if($this->erro_status == 0) {
			    $sqlerro  = true;
		        $erro_msg = $this->erro_msg;
		        break;
		    }
        	
	    }
	
	    $sqlsoma = "select '$data_inicial'::date + '1 day'::interval as data_inicial";
	    $resultsoma = pg_exec($sqlsoma) or die($sqlsoma);
	    db_fieldsmemory($resultsoma, 0);

//	    $data_inicial++;


    }
    
    if($primeiro_dia == true and $sqlerro == false) {

    	$this->at13_dia = $data_inicial;

    	if($cltarefa->at40_horafim > $at53_horafim_tarde) {

	      $this->at13_horaini = $cltarefa->at40_horainidia;
	      $this->at13_horafim = $at53_horafim_manha;
	      $this->incluir(null);
          
          if($this->erro_status == 0) {
		    $sqlerro  = true;
		    $erro_msg = $this->erro_msg;
		  }
		  
		  if ($sqlerro  == false) {
 		    $this->at13_horaini = $at53_horaini_tarde;
	        $this->at13_horafim = $cltarefa->at40_horafim;
	        $this->incluir(null);
          
            if($this->erro_status == 0) {
		      $sqlerro  = true;
		      $erro_msg = $this->erro_msg;
		    }
		  }
          
    	} else {
    		
		  $this->at13_horaini = $cltarefa->at40_horainidia;
	      $this->at13_horafim = $cltarefa->at40_horafim;
	      $this->incluir(null);
          
          if($this->erro_status == 0) {
		    $sqlerro  = true;
		    $erro_msg = $this->erro_msg;
		  }
		      		
    	}
    	
    } else {
    	
	    $this->at13_dia = $data_inicial;

    	if($cltarefa->at40_horafim <= $at53_horafim_manha) {
	        $this->at13_horaini = $at53_horaini_manha;
    	    $this->at13_horafim = $cltarefa->at40_horafim;
            $this->incluir(null);
          
            if($this->erro_status == 0) {
       		    $sqlerro  = true;
		        $erro_msg = $this->erro_msg;
      		}
      		
    	} else {
    		
	        $this->at13_horaini = $at53_horaini_manha;
    	    $this->at13_horafim = $at53_horafim_manha;

            $this->incluir(null);
          
            if($this->erro_status == 0) {
   		        $sqlerro  = true;
   		        $erro_msg = $this->erro_msg;
         	}

            if ($sqlerro == false) {
	          $this->at13_horaini  = $at53_horaini_tarde;
        	  $this->at13_horafim  = $cltarefa->at40_horafim;
              $this->incluir(null);
          
              if($this->erro_status == 0) {
   		          $sqlerro  = true;
       		      $erro_msg = $this->erro_msg;
		      }
            }
    	}
    }

	return($sqlerro);
	
  }
  
}
?>