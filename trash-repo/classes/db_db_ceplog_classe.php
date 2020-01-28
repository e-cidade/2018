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

//MODULO: protocolo
//CLASSE DA ENTIDADE db_ceplog
class cl_db_ceplog { 
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
   var $db11_codlog = 0; 
   var $db11_codigo = 0; 
   var $db11_tipo = null; 
   var $db11_logradouro = null; 
   var $db11_logsemacento = null; 
   var $db11_bairro = null; 
   var $db11_cep = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db11_codlog = int8 = Código do Logradouro 
                 db11_codigo = int8 = Código do Município 
                 db11_tipo = varchar(12) = Tipo de Logradouro 
                 db11_logradouro = varchar(60) = Logradouro 
                 db11_logsemacento = varchar(60) = Logradouro sem Acento 
                 db11_bairro = varchar(40) = Bairro 
                 db11_cep = varchar(8) = Cep 
                 ";
   //funcao construtor da classe 
   function cl_db_ceplog() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_ceplog"); 
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
       $this->db11_codlog = ($this->db11_codlog == ""?@$GLOBALS["HTTP_POST_VARS"]["db11_codlog"]:$this->db11_codlog);
       $this->db11_codigo = ($this->db11_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db11_codigo"]:$this->db11_codigo);
       $this->db11_tipo = ($this->db11_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["db11_tipo"]:$this->db11_tipo);
       $this->db11_logradouro = ($this->db11_logradouro == ""?@$GLOBALS["HTTP_POST_VARS"]["db11_logradouro"]:$this->db11_logradouro);
       $this->db11_logsemacento = ($this->db11_logsemacento == ""?@$GLOBALS["HTTP_POST_VARS"]["db11_logsemacento"]:$this->db11_logsemacento);
       $this->db11_bairro = ($this->db11_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["db11_bairro"]:$this->db11_bairro);
       $this->db11_cep = ($this->db11_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["db11_cep"]:$this->db11_cep);
     }else{
       $this->db11_codlog = ($this->db11_codlog == ""?@$GLOBALS["HTTP_POST_VARS"]["db11_codlog"]:$this->db11_codlog);
     }
   }
   // funcao para inclusao
   function incluir ($db11_codlog){ 
      $this->atualizacampos();
     if($this->db11_codigo == null ){ 
       $this->erro_sql = " Campo Código do Município nao Informado.";
       $this->erro_campo = "db11_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db11_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Logradouro nao Informado.";
       $this->erro_campo = "db11_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db11_logradouro == null ){ 
       $this->erro_sql = " Campo Logradouro nao Informado.";
       $this->erro_campo = "db11_logradouro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db11_logsemacento == null ){ 
       $this->erro_sql = " Campo Logradouro sem Acento nao Informado.";
       $this->erro_campo = "db11_logsemacento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db11_bairro == null ){ 
       $this->erro_sql = " Campo Bairro nao Informado.";
       $this->erro_campo = "db11_bairro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db11_cep == null ){ 
       $this->erro_sql = " Campo Cep nao Informado.";
       $this->erro_campo = "db11_cep";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db11_codlog == "" || $db11_codlog == null ){
       $result = db_query("select nextval('db_ceplog_db11_codlog_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_ceplog_db11_codlog_seq do campo: db11_codlog"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db11_codlog = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_ceplog_db11_codlog_seq");
       if(($result != false) && (pg_result($result,0,0) < $db11_codlog)){
         $this->erro_sql = " Campo db11_codlog maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db11_codlog = $db11_codlog; 
       }
     }
     if(($this->db11_codlog == null) || ($this->db11_codlog == "") ){ 
       $this->erro_sql = " Campo db11_codlog nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_ceplog(
                                       db11_codlog 
                                      ,db11_codigo 
                                      ,db11_tipo 
                                      ,db11_logradouro 
                                      ,db11_logsemacento 
                                      ,db11_bairro 
                                      ,db11_cep 
                       )
                values (
                                $this->db11_codlog 
                               ,$this->db11_codigo 
                               ,'$this->db11_tipo' 
                               ,'$this->db11_logradouro' 
                               ,'$this->db11_logsemacento' 
                               ,'$this->db11_bairro' 
                               ,'$this->db11_cep' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cep por logradouro ($this->db11_codlog) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cep por logradouro já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cep por logradouro ($this->db11_codlog) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db11_codlog;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db11_codlog));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4822,'$this->db11_codlog','I')");
       $resac = db_query("insert into db_acount values($acount,649,4822,'','".AddSlashes(pg_result($resaco,0,'db11_codlog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,649,4823,'','".AddSlashes(pg_result($resaco,0,'db11_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,649,4824,'','".AddSlashes(pg_result($resaco,0,'db11_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,649,4825,'','".AddSlashes(pg_result($resaco,0,'db11_logradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,649,4826,'','".AddSlashes(pg_result($resaco,0,'db11_logsemacento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,649,4827,'','".AddSlashes(pg_result($resaco,0,'db11_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,649,4828,'','".AddSlashes(pg_result($resaco,0,'db11_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db11_codlog=null) { 
      $this->atualizacampos();
     $sql = " update db_ceplog set ";
     $virgula = "";
     if(trim($this->db11_codlog)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db11_codlog"])){ 
        if(trim($this->db11_codlog)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db11_codlog"])){ 
           $this->db11_codlog = "0" ; 
        } 
       $sql  .= $virgula." db11_codlog = $this->db11_codlog ";
       $virgula = ",";
       if(trim($this->db11_codlog) == null ){ 
         $this->erro_sql = " Campo Código do Logradouro nao Informado.";
         $this->erro_campo = "db11_codlog";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db11_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db11_codigo"])){ 
        if(trim($this->db11_codigo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db11_codigo"])){ 
           $this->db11_codigo = "0" ; 
        } 
       $sql  .= $virgula." db11_codigo = $this->db11_codigo ";
       $virgula = ",";
       if(trim($this->db11_codigo) == null ){ 
         $this->erro_sql = " Campo Código do Município nao Informado.";
         $this->erro_campo = "db11_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db11_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db11_tipo"])){ 
       $sql  .= $virgula." db11_tipo = '$this->db11_tipo' ";
       $virgula = ",";
       if(trim($this->db11_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Logradouro nao Informado.";
         $this->erro_campo = "db11_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db11_logradouro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db11_logradouro"])){ 
       $sql  .= $virgula." db11_logradouro = '$this->db11_logradouro' ";
       $virgula = ",";
       if(trim($this->db11_logradouro) == null ){ 
         $this->erro_sql = " Campo Logradouro nao Informado.";
         $this->erro_campo = "db11_logradouro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db11_logsemacento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db11_logsemacento"])){ 
       $sql  .= $virgula." db11_logsemacento = '$this->db11_logsemacento' ";
       $virgula = ",";
       if(trim($this->db11_logsemacento) == null ){ 
         $this->erro_sql = " Campo Logradouro sem Acento nao Informado.";
         $this->erro_campo = "db11_logsemacento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db11_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db11_bairro"])){ 
       $sql  .= $virgula." db11_bairro = '$this->db11_bairro' ";
       $virgula = ",";
       if(trim($this->db11_bairro) == null ){ 
         $this->erro_sql = " Campo Bairro nao Informado.";
         $this->erro_campo = "db11_bairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db11_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db11_cep"])){ 
       $sql  .= $virgula." db11_cep = '$this->db11_cep' ";
       $virgula = ",";
       if(trim($this->db11_cep) == null ){ 
         $this->erro_sql = " Campo Cep nao Informado.";
         $this->erro_campo = "db11_cep";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db11_codlog!=null){
       $sql .= " db11_codlog = $this->db11_codlog";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db11_codlog));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4822,'$this->db11_codlog','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db11_codlog"]))
           $resac = db_query("insert into db_acount values($acount,649,4822,'".AddSlashes(pg_result($resaco,$conresaco,'db11_codlog'))."','$this->db11_codlog',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db11_codigo"]))
           $resac = db_query("insert into db_acount values($acount,649,4823,'".AddSlashes(pg_result($resaco,$conresaco,'db11_codigo'))."','$this->db11_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db11_tipo"]))
           $resac = db_query("insert into db_acount values($acount,649,4824,'".AddSlashes(pg_result($resaco,$conresaco,'db11_tipo'))."','$this->db11_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db11_logradouro"]))
           $resac = db_query("insert into db_acount values($acount,649,4825,'".AddSlashes(pg_result($resaco,$conresaco,'db11_logradouro'))."','$this->db11_logradouro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db11_logsemacento"]))
           $resac = db_query("insert into db_acount values($acount,649,4826,'".AddSlashes(pg_result($resaco,$conresaco,'db11_logsemacento'))."','$this->db11_logsemacento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db11_bairro"]))
           $resac = db_query("insert into db_acount values($acount,649,4827,'".AddSlashes(pg_result($resaco,$conresaco,'db11_bairro'))."','$this->db11_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db11_cep"]))
           $resac = db_query("insert into db_acount values($acount,649,4828,'".AddSlashes(pg_result($resaco,$conresaco,'db11_cep'))."','$this->db11_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cep por logradouro nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db11_codlog;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cep por logradouro nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db11_codlog;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db11_codlog;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db11_codlog=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db11_codlog));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4822,'$db11_codlog','E')");
         $resac = db_query("insert into db_acount values($acount,649,4822,'','".AddSlashes(pg_result($resaco,$iresaco,'db11_codlog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,649,4823,'','".AddSlashes(pg_result($resaco,$iresaco,'db11_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,649,4824,'','".AddSlashes(pg_result($resaco,$iresaco,'db11_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,649,4825,'','".AddSlashes(pg_result($resaco,$iresaco,'db11_logradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,649,4826,'','".AddSlashes(pg_result($resaco,$iresaco,'db11_logsemacento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,649,4827,'','".AddSlashes(pg_result($resaco,$iresaco,'db11_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,649,4828,'','".AddSlashes(pg_result($resaco,$iresaco,'db11_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_ceplog
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db11_codlog != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db11_codlog = $db11_codlog ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cep por logradouro nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db11_codlog;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cep por logradouro nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db11_codlog;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db11_codlog;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_ceplog";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db11_codlog=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_ceplog ";
     $sql .= "      inner join db_cepmunic  on  db_cepmunic.db10_codigo = db_ceplog.db11_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($db11_codlog!=null ){
         $sql2 .= " where db_ceplog.db11_codlog = $db11_codlog "; 
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
   function sql_query_file ( $db11_codlog=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_ceplog ";
     $sql2 = "";
     if($dbwhere==""){
       if($db11_codlog!=null ){
         $sql2 .= " where db_ceplog.db11_codlog = $db11_codlog "; 
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