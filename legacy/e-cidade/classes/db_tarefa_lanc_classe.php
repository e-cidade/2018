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
//CLASSE DA ENTIDADE tarefa_lanc
class cl_tarefa_lanc { 
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
   var $at36_sequencia = 0; 
   var $at36_tarefa = 0; 
   var $at36_usuario = 0; 
   var $at36_data_dia = null; 
   var $at36_data_mes = null; 
   var $at36_data_ano = null; 
   var $at36_data = null; 
   var $at36_hora = null; 
   var $at36_ip = null; 
   var $at36_tipo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at36_sequencia = int4 = Sequência 
                 at36_tarefa = int4 = Tarefa 
                 at36_usuario = int4 = Usuário que criou 
                 at36_data = date = Data de criação 
                 at36_hora = char(5) = Hora da criação 
                 at36_ip = varchar(15) = IP 
                 at36_tipo = char(1) = Tipo de lançamento 
                 ";
   //funcao construtor da classe 
   function cl_tarefa_lanc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tarefa_lanc"); 
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
       $this->at36_sequencia = ($this->at36_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["at36_sequencia"]:$this->at36_sequencia);
       $this->at36_tarefa = ($this->at36_tarefa == ""?@$GLOBALS["HTTP_POST_VARS"]["at36_tarefa"]:$this->at36_tarefa);
       $this->at36_usuario = ($this->at36_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["at36_usuario"]:$this->at36_usuario);
       if($this->at36_data == ""){
         $this->at36_data_dia = ($this->at36_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at36_data_dia"]:$this->at36_data_dia);
         $this->at36_data_mes = ($this->at36_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at36_data_mes"]:$this->at36_data_mes);
         $this->at36_data_ano = ($this->at36_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at36_data_ano"]:$this->at36_data_ano);
         if($this->at36_data_dia != ""){
            $this->at36_data = $this->at36_data_ano."-".$this->at36_data_mes."-".$this->at36_data_dia;
         }
       }
       $this->at36_hora = ($this->at36_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["at36_hora"]:$this->at36_hora);
       $this->at36_ip = ($this->at36_ip == ""?@$GLOBALS["HTTP_POST_VARS"]["at36_ip"]:$this->at36_ip);
       $this->at36_tipo = ($this->at36_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["at36_tipo"]:$this->at36_tipo);
     }else{
       $this->at36_sequencia = ($this->at36_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["at36_sequencia"]:$this->at36_sequencia);
     }
   }
   // funcao para inclusao
   function incluir ($at36_sequencia){ 
      $this->atualizacampos();
     if($this->at36_tarefa == null ){ 
       $this->erro_sql = " Campo Tarefa nao Informado.";
       $this->erro_campo = "at36_tarefa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at36_usuario == null ){ 
       $this->erro_sql = " Campo Usuário que criou nao Informado.";
       $this->erro_campo = "at36_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at36_data == null ){ 
       $this->erro_sql = " Campo Data de criação nao Informado.";
       $this->erro_campo = "at36_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at36_hora == null ){ 
       $this->erro_sql = " Campo Hora da criação nao Informado.";
       $this->erro_campo = "at36_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at36_ip == null ){ 
       $this->erro_sql = " Campo IP nao Informado.";
       $this->erro_campo = "at36_ip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at36_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de lançamento nao Informado.";
       $this->erro_campo = "at36_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($at36_sequencia == "" || $at36_sequencia == null ){
       $result = db_query("select nextval('tarefa_lanc_at36_sequencia_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tarefa_lanc_at36_sequencia_seq do campo: at36_sequencia"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at36_sequencia = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tarefa_lanc_at36_sequencia_seq");
       if(($result != false) && (pg_result($result,0,0) < $at36_sequencia)){
         $this->erro_sql = " Campo at36_sequencia maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at36_sequencia = $at36_sequencia; 
       }
     }
     if(($this->at36_sequencia == null) || ($this->at36_sequencia == "") ){ 
       $this->erro_sql = " Campo at36_sequencia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tarefa_lanc(
                                       at36_sequencia 
                                      ,at36_tarefa 
                                      ,at36_usuario 
                                      ,at36_data 
                                      ,at36_hora 
                                      ,at36_ip 
                                      ,at36_tipo 
                       )
                values (
                                $this->at36_sequencia 
                               ,$this->at36_tarefa 
                               ,$this->at36_usuario 
                               ,".($this->at36_data == "null" || $this->at36_data == ""?"null":"'".$this->at36_data."'")." 
                               ,'$this->at36_hora' 
                               ,'$this->at36_ip' 
                               ,'$this->at36_tipo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lançamento de tarefas ($this->at36_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lançamento de tarefas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lançamento de tarefas ($this->at36_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at36_sequencia;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at36_sequencia));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8837,'$this->at36_sequencia','I')");
       $resac = db_query("insert into db_acount values($acount,1507,8837,'','".AddSlashes(pg_result($resaco,0,'at36_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1507,8838,'','".AddSlashes(pg_result($resaco,0,'at36_tarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1507,8839,'','".AddSlashes(pg_result($resaco,0,'at36_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1507,8834,'','".AddSlashes(pg_result($resaco,0,'at36_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1507,8835,'','".AddSlashes(pg_result($resaco,0,'at36_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1507,8836,'','".AddSlashes(pg_result($resaco,0,'at36_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1507,8840,'','".AddSlashes(pg_result($resaco,0,'at36_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at36_sequencia=null) { 
      $this->atualizacampos();
     $sql = " update tarefa_lanc set ";
     $virgula = "";
     if(trim($this->at36_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at36_sequencia"])){ 
       $sql  .= $virgula." at36_sequencia = $this->at36_sequencia ";
       $virgula = ",";
       if(trim($this->at36_sequencia) == null ){ 
         $this->erro_sql = " Campo Sequência nao Informado.";
         $this->erro_campo = "at36_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at36_tarefa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at36_tarefa"])){ 
       $sql  .= $virgula." at36_tarefa = $this->at36_tarefa ";
       $virgula = ",";
       if(trim($this->at36_tarefa) == null ){ 
         $this->erro_sql = " Campo Tarefa nao Informado.";
         $this->erro_campo = "at36_tarefa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at36_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at36_usuario"])){ 
       $sql  .= $virgula." at36_usuario = $this->at36_usuario ";
       $virgula = ",";
       if(trim($this->at36_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário que criou nao Informado.";
         $this->erro_campo = "at36_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at36_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at36_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at36_data_dia"] !="") ){ 
       $sql  .= $virgula." at36_data = '$this->at36_data' ";
       $virgula = ",";
       if(trim($this->at36_data) == null ){ 
         $this->erro_sql = " Campo Data de criação nao Informado.";
         $this->erro_campo = "at36_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at36_data_dia"])){ 
         $sql  .= $virgula." at36_data = null ";
         $virgula = ",";
         if(trim($this->at36_data) == null ){ 
           $this->erro_sql = " Campo Data de criação nao Informado.";
           $this->erro_campo = "at36_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at36_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at36_hora"])){ 
       $sql  .= $virgula." at36_hora = '$this->at36_hora' ";
       $virgula = ",";
       if(trim($this->at36_hora) == null ){ 
         $this->erro_sql = " Campo Hora da criação nao Informado.";
         $this->erro_campo = "at36_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at36_ip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at36_ip"])){ 
       $sql  .= $virgula." at36_ip = '$this->at36_ip' ";
       $virgula = ",";
       if(trim($this->at36_ip) == null ){ 
         $this->erro_sql = " Campo IP nao Informado.";
         $this->erro_campo = "at36_ip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at36_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at36_tipo"])){ 
       $sql  .= $virgula." at36_tipo = '$this->at36_tipo' ";
       $virgula = ",";
       if(trim($this->at36_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de lançamento nao Informado.";
         $this->erro_campo = "at36_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($at36_sequencia!=null){
       $sql .= " at36_sequencia = $this->at36_sequencia";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at36_sequencia));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8837,'$this->at36_sequencia','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at36_sequencia"]))
           $resac = db_query("insert into db_acount values($acount,1507,8837,'".AddSlashes(pg_result($resaco,$conresaco,'at36_sequencia'))."','$this->at36_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at36_tarefa"]))
           $resac = db_query("insert into db_acount values($acount,1507,8838,'".AddSlashes(pg_result($resaco,$conresaco,'at36_tarefa'))."','$this->at36_tarefa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at36_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1507,8839,'".AddSlashes(pg_result($resaco,$conresaco,'at36_usuario'))."','$this->at36_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at36_data"]))
           $resac = db_query("insert into db_acount values($acount,1507,8834,'".AddSlashes(pg_result($resaco,$conresaco,'at36_data'))."','$this->at36_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at36_hora"]))
           $resac = db_query("insert into db_acount values($acount,1507,8835,'".AddSlashes(pg_result($resaco,$conresaco,'at36_hora'))."','$this->at36_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at36_ip"]))
           $resac = db_query("insert into db_acount values($acount,1507,8836,'".AddSlashes(pg_result($resaco,$conresaco,'at36_ip'))."','$this->at36_ip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at36_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1507,8840,'".AddSlashes(pg_result($resaco,$conresaco,'at36_tipo'))."','$this->at36_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamento de tarefas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at36_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lançamento de tarefas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at36_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at36_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at36_sequencia=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at36_sequencia));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8837,'$at36_sequencia','E')");
         $resac = db_query("insert into db_acount values($acount,1507,8837,'','".AddSlashes(pg_result($resaco,$iresaco,'at36_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1507,8838,'','".AddSlashes(pg_result($resaco,$iresaco,'at36_tarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1507,8839,'','".AddSlashes(pg_result($resaco,$iresaco,'at36_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1507,8834,'','".AddSlashes(pg_result($resaco,$iresaco,'at36_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1507,8835,'','".AddSlashes(pg_result($resaco,$iresaco,'at36_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1507,8836,'','".AddSlashes(pg_result($resaco,$iresaco,'at36_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1507,8840,'','".AddSlashes(pg_result($resaco,$iresaco,'at36_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tarefa_lanc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at36_sequencia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at36_sequencia = $at36_sequencia ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamento de tarefas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at36_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lançamento de tarefas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at36_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at36_sequencia;
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
        $this->erro_sql   = "Record Vazio na Tabela:tarefa_lanc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $at36_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefa_lanc ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tarefa_lanc.at36_usuario";
     $sql .= "      inner join tarefa  on  tarefa.at40_sequencial = tarefa_lanc.at36_tarefa";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tarefa.at40_responsavel";
     $sql2 = "";
     if($dbwhere==""){
       if($at36_sequencia!=null ){
         $sql2 .= " where tarefa_lanc.at36_sequencia = $at36_sequencia "; 
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
   function sql_query_file ( $at36_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefa_lanc ";
     $sql2 = "";
     if($dbwhere==""){
       if($at36_sequencia!=null ){
         $sql2 .= " where tarefa_lanc.at36_sequencia = $at36_sequencia "; 
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