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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_tarefas
class cl_db_tarefas { 
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
   var $db79_codigo = 0; 
   var $db79_id_usuario = 0; 
   var $db79_tarefasit = 0; 
   var $db79_descr = null; 
   var $db79_data_dia = null; 
   var $db79_data_mes = null; 
   var $db79_data_ano = null; 
   var $db79_data = null; 
   var $db79_hora = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db79_codigo = int4 = Código 
                 db79_id_usuario = int8 = Usuário 
                 db79_tarefasit = int4 = Status 
                 db79_descr = text = Descrição 
                 db79_data = date = Data 
                 db79_hora = char(5) = Hora 
                 ";
   //funcao construtor da classe 
   function cl_db_tarefas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_tarefas"); 
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
       $this->db79_codigo = ($this->db79_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db79_codigo"]:$this->db79_codigo);
       $this->db79_id_usuario = ($this->db79_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["db79_id_usuario"]:$this->db79_id_usuario);
       $this->db79_tarefasit = ($this->db79_tarefasit == ""?@$GLOBALS["HTTP_POST_VARS"]["db79_tarefasit"]:$this->db79_tarefasit);
       $this->db79_descr = ($this->db79_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["db79_descr"]:$this->db79_descr);
       if($this->db79_data == ""){
         $this->db79_data_dia = ($this->db79_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["db79_data_dia"]:$this->db79_data_dia);
         $this->db79_data_mes = ($this->db79_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["db79_data_mes"]:$this->db79_data_mes);
         $this->db79_data_ano = ($this->db79_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["db79_data_ano"]:$this->db79_data_ano);
         if($this->db79_data_dia != ""){
            $this->db79_data = $this->db79_data_ano."-".$this->db79_data_mes."-".$this->db79_data_dia;
         }
       }
       $this->db79_hora = ($this->db79_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["db79_hora"]:$this->db79_hora);
     }else{
       $this->db79_codigo = ($this->db79_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db79_codigo"]:$this->db79_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($db79_codigo){ 
      $this->atualizacampos();
     if($this->db79_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "db79_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db79_tarefasit == null ){ 
       $this->erro_sql = " Campo Status nao Informado.";
       $this->erro_campo = "db79_tarefasit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db79_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "db79_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db79_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "db79_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db79_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "db79_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db79_codigo == "" || $db79_codigo == null ){
       $result = db_query("select nextval('tarefas_db79_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tarefas_db79_codigo_seq do campo: db79_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db79_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tarefas_db79_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $db79_codigo)){
         $this->erro_sql = " Campo db79_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db79_codigo = $db79_codigo; 
       }
     }
     if(($this->db79_codigo == null) || ($this->db79_codigo == "") ){ 
       $this->erro_sql = " Campo db79_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_tarefas(
                                       db79_codigo 
                                      ,db79_id_usuario 
                                      ,db79_tarefasit 
                                      ,db79_descr 
                                      ,db79_data 
                                      ,db79_hora 
                       )
                values (
                                $this->db79_codigo 
                               ,$this->db79_id_usuario 
                               ,$this->db79_tarefasit 
                               ,'$this->db79_descr' 
                               ,".($this->db79_data == "null" || $this->db79_data == ""?"null":"'".$this->db79_data."'")." 
                               ,'$this->db79_hora' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tarefas ($this->db79_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tarefas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tarefas ($this->db79_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db79_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db79_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6363,'$this->db79_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1039,6363,'','".AddSlashes(pg_result($resaco,0,'db79_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1039,6364,'','".AddSlashes(pg_result($resaco,0,'db79_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1039,6368,'','".AddSlashes(pg_result($resaco,0,'db79_tarefasit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1039,6365,'','".AddSlashes(pg_result($resaco,0,'db79_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1039,6366,'','".AddSlashes(pg_result($resaco,0,'db79_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1039,6367,'','".AddSlashes(pg_result($resaco,0,'db79_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db79_codigo=null) { 
      $this->atualizacampos();
     $sql = " update db_tarefas set ";
     $virgula = "";
     if(trim($this->db79_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db79_codigo"])){ 
       $sql  .= $virgula." db79_codigo = $this->db79_codigo ";
       $virgula = ",";
       if(trim($this->db79_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "db79_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db79_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db79_id_usuario"])){ 
       $sql  .= $virgula." db79_id_usuario = $this->db79_id_usuario ";
       $virgula = ",";
       if(trim($this->db79_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "db79_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db79_tarefasit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db79_tarefasit"])){ 
       $sql  .= $virgula." db79_tarefasit = $this->db79_tarefasit ";
       $virgula = ",";
       if(trim($this->db79_tarefasit) == null ){ 
         $this->erro_sql = " Campo Status nao Informado.";
         $this->erro_campo = "db79_tarefasit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db79_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db79_descr"])){ 
       $sql  .= $virgula." db79_descr = '$this->db79_descr' ";
       $virgula = ",";
       if(trim($this->db79_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "db79_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db79_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db79_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["db79_data_dia"] !="") ){ 
       $sql  .= $virgula." db79_data = '$this->db79_data' ";
       $virgula = ",";
       if(trim($this->db79_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "db79_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["db79_data_dia"])){ 
         $sql  .= $virgula." db79_data = null ";
         $virgula = ",";
         if(trim($this->db79_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "db79_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->db79_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db79_hora"])){ 
       $sql  .= $virgula." db79_hora = '$this->db79_hora' ";
       $virgula = ",";
       if(trim($this->db79_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "db79_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db79_codigo!=null){
       $sql .= " db79_codigo = $this->db79_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db79_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6363,'$this->db79_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db79_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1039,6363,'".AddSlashes(pg_result($resaco,$conresaco,'db79_codigo'))."','$this->db79_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db79_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1039,6364,'".AddSlashes(pg_result($resaco,$conresaco,'db79_id_usuario'))."','$this->db79_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db79_tarefasit"]))
           $resac = db_query("insert into db_acount values($acount,1039,6368,'".AddSlashes(pg_result($resaco,$conresaco,'db79_tarefasit'))."','$this->db79_tarefasit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db79_descr"]))
           $resac = db_query("insert into db_acount values($acount,1039,6365,'".AddSlashes(pg_result($resaco,$conresaco,'db79_descr'))."','$this->db79_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db79_data"]))
           $resac = db_query("insert into db_acount values($acount,1039,6366,'".AddSlashes(pg_result($resaco,$conresaco,'db79_data'))."','$this->db79_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db79_hora"]))
           $resac = db_query("insert into db_acount values($acount,1039,6367,'".AddSlashes(pg_result($resaco,$conresaco,'db79_hora'))."','$this->db79_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tarefas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db79_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tarefas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db79_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db79_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db79_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db79_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6363,'$db79_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1039,6363,'','".AddSlashes(pg_result($resaco,$iresaco,'db79_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1039,6364,'','".AddSlashes(pg_result($resaco,$iresaco,'db79_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1039,6368,'','".AddSlashes(pg_result($resaco,$iresaco,'db79_tarefasit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1039,6365,'','".AddSlashes(pg_result($resaco,$iresaco,'db79_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1039,6366,'','".AddSlashes(pg_result($resaco,$iresaco,'db79_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1039,6367,'','".AddSlashes(pg_result($resaco,$iresaco,'db79_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_tarefas
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db79_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db79_codigo = $db79_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tarefas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db79_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tarefas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db79_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db79_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_tarefas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db79_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_tarefas ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = db_tarefas.db79_id_usuario";
     $sql .= "      inner join db_tarefasit  on  db_tarefasit.db80_codigo = db_tarefas.db79_tarefasit";
     $sql2 = "";
     if($dbwhere==""){
       if($db79_codigo!=null ){
         $sql2 .= " where db_tarefas.db79_codigo = $db79_codigo "; 
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
   function sql_query_file ( $db79_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_tarefas ";
     $sql2 = "";
     if($dbwhere==""){
       if($db79_codigo!=null ){
         $sql2 .= " where db_tarefas.db79_codigo = $db79_codigo "; 
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