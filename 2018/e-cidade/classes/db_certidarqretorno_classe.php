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

//MODULO: juridico
//CLASSE DA ENTIDADE certidarqretorno
class cl_certidarqretorno { 
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
   var $v84_sequencial = 0; 
   var $v84_certidarqremessa = 0; 
   var $v84_nomearq = null; 
   var $v84_dtarquivo = null; 
   var $v84_dtprocessamento_dia = null; 
   var $v84_dtprocessamento_mes = null; 
   var $v84_dtprocessamento_ano = null; 
   var $v84_dtprocessamento = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v84_sequencial = int4 = Sequencial 
                 v84_certidarqremessa = int4 = Lista 
                 v84_nomearq = varchar(50) = Nome do Arquivo 
                 v84_dtarquivo = text = Data do Arquivo de retorno 
                 v84_dtprocessamento = date = Data de processamento 
                 ";
   //funcao construtor da classe 
   function cl_certidarqretorno() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("certidarqretorno"); 
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
       $this->v84_sequencial = ($this->v84_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v84_sequencial"]:$this->v84_sequencial);
       $this->v84_certidarqremessa = ($this->v84_certidarqremessa == ""?@$GLOBALS["HTTP_POST_VARS"]["v84_certidarqremessa"]:$this->v84_certidarqremessa);
       $this->v84_nomearq = ($this->v84_nomearq == ""?@$GLOBALS["HTTP_POST_VARS"]["v84_nomearq"]:$this->v84_nomearq);
       $this->v84_dtarquivo = ($this->v84_dtarquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["v84_dtarquivo"]:$this->v84_dtarquivo);
       if($this->v84_dtprocessamento == ""){
         $this->v84_dtprocessamento_dia = ($this->v84_dtprocessamento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v84_dtprocessamento_dia"]:$this->v84_dtprocessamento_dia);
         $this->v84_dtprocessamento_mes = ($this->v84_dtprocessamento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v84_dtprocessamento_mes"]:$this->v84_dtprocessamento_mes);
         $this->v84_dtprocessamento_ano = ($this->v84_dtprocessamento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v84_dtprocessamento_ano"]:$this->v84_dtprocessamento_ano);
         if($this->v84_dtprocessamento_dia != ""){
            $this->v84_dtprocessamento = $this->v84_dtprocessamento_ano."-".$this->v84_dtprocessamento_mes."-".$this->v84_dtprocessamento_dia;
         }
       }
     }else{
       $this->v84_sequencial = ($this->v84_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v84_sequencial"]:$this->v84_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($v84_sequencial){ 
      $this->atualizacampos();
     if($this->v84_certidarqremessa == null ){ 
       $this->erro_sql = " Campo Lista nao Informado.";
       $this->erro_campo = "v84_certidarqremessa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v84_nomearq == null ){ 
       $this->erro_sql = " Campo Nome do Arquivo nao Informado.";
       $this->erro_campo = "v84_nomearq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v84_dtarquivo == null ){ 
       $this->erro_sql = " Campo Data do Arquivo de retorno nao Informado.";
       $this->erro_campo = "v84_dtarquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v84_dtprocessamento == null ){ 
       $this->erro_sql = " Campo Data de processamento nao Informado.";
       $this->erro_campo = "v84_dtprocessamento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v84_sequencial == "" || $v84_sequencial == null ){
       $result = db_query("select nextval('certidarqretorno_v84_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: certidarqretorno_v84_sequencial_seq do campo: v84_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v84_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from certidarqretorno_v84_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v84_sequencial)){
         $this->erro_sql = " Campo v84_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v84_sequencial = $v84_sequencial; 
       }
     }
     if(($this->v84_sequencial == null) || ($this->v84_sequencial == "") ){ 
       $this->erro_sql = " Campo v84_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into certidarqretorno(
                                       v84_sequencial 
                                      ,v84_certidarqremessa 
                                      ,v84_nomearq 
                                      ,v84_dtarquivo 
                                      ,v84_dtprocessamento 
                       )
                values (
                                $this->v84_sequencial 
                               ,$this->v84_certidarqremessa 
                               ,'$this->v84_nomearq' 
                               ,'$this->v84_dtarquivo' 
                               ,".($this->v84_dtprocessamento == "null" || $this->v84_dtprocessamento == ""?"null":"'".$this->v84_dtprocessamento."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "certidarqretorno ($this->v84_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "certidarqretorno já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "certidarqretorno ($this->v84_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v84_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v84_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18175,'$this->v84_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3211,18175,'','".AddSlashes(pg_result($resaco,0,'v84_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3211,18176,'','".AddSlashes(pg_result($resaco,0,'v84_certidarqremessa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3211,18177,'','".AddSlashes(pg_result($resaco,0,'v84_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3211,18178,'','".AddSlashes(pg_result($resaco,0,'v84_dtarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3211,18179,'','".AddSlashes(pg_result($resaco,0,'v84_dtprocessamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v84_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update certidarqretorno set ";
     $virgula = "";
     if(trim($this->v84_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v84_sequencial"])){ 
       $sql  .= $virgula." v84_sequencial = $this->v84_sequencial ";
       $virgula = ",";
       if(trim($this->v84_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "v84_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v84_certidarqremessa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v84_certidarqremessa"])){ 
       $sql  .= $virgula." v84_certidarqremessa = $this->v84_certidarqremessa ";
       $virgula = ",";
       if(trim($this->v84_certidarqremessa) == null ){ 
         $this->erro_sql = " Campo Lista nao Informado.";
         $this->erro_campo = "v84_certidarqremessa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v84_nomearq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v84_nomearq"])){ 
       $sql  .= $virgula." v84_nomearq = '$this->v84_nomearq' ";
       $virgula = ",";
       if(trim($this->v84_nomearq) == null ){ 
         $this->erro_sql = " Campo Nome do Arquivo nao Informado.";
         $this->erro_campo = "v84_nomearq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v84_dtarquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v84_dtarquivo"])){ 
       $sql  .= $virgula." v84_dtarquivo = '$this->v84_dtarquivo' ";
       $virgula = ",";
       if(trim($this->v84_dtarquivo) == null ){ 
         $this->erro_sql = " Campo Data do Arquivo de retorno nao Informado.";
         $this->erro_campo = "v84_dtarquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v84_dtprocessamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v84_dtprocessamento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v84_dtprocessamento_dia"] !="") ){ 
       $sql  .= $virgula." v84_dtprocessamento = '$this->v84_dtprocessamento' ";
       $virgula = ",";
       if(trim($this->v84_dtprocessamento) == null ){ 
         $this->erro_sql = " Campo Data de processamento nao Informado.";
         $this->erro_campo = "v84_dtprocessamento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["v84_dtprocessamento_dia"])){ 
         $sql  .= $virgula." v84_dtprocessamento = null ";
         $virgula = ",";
         if(trim($this->v84_dtprocessamento) == null ){ 
           $this->erro_sql = " Campo Data de processamento nao Informado.";
           $this->erro_campo = "v84_dtprocessamento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($v84_sequencial!=null){
       $sql .= " v84_sequencial = $this->v84_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v84_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18175,'$this->v84_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v84_sequencial"]) || $this->v84_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3211,18175,'".AddSlashes(pg_result($resaco,$conresaco,'v84_sequencial'))."','$this->v84_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v84_certidarqremessa"]) || $this->v84_certidarqremessa != "")
           $resac = db_query("insert into db_acount values($acount,3211,18176,'".AddSlashes(pg_result($resaco,$conresaco,'v84_certidarqremessa'))."','$this->v84_certidarqremessa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v84_nomearq"]) || $this->v84_nomearq != "")
           $resac = db_query("insert into db_acount values($acount,3211,18177,'".AddSlashes(pg_result($resaco,$conresaco,'v84_nomearq'))."','$this->v84_nomearq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v84_dtarquivo"]) || $this->v84_dtarquivo != "")
           $resac = db_query("insert into db_acount values($acount,3211,18178,'".AddSlashes(pg_result($resaco,$conresaco,'v84_dtarquivo'))."','$this->v84_dtarquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v84_dtprocessamento"]) || $this->v84_dtprocessamento != "")
           $resac = db_query("insert into db_acount values($acount,3211,18179,'".AddSlashes(pg_result($resaco,$conresaco,'v84_dtprocessamento'))."','$this->v84_dtprocessamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "certidarqretorno nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v84_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "certidarqretorno nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v84_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v84_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v84_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v84_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18175,'$v84_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3211,18175,'','".AddSlashes(pg_result($resaco,$iresaco,'v84_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3211,18176,'','".AddSlashes(pg_result($resaco,$iresaco,'v84_certidarqremessa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3211,18177,'','".AddSlashes(pg_result($resaco,$iresaco,'v84_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3211,18178,'','".AddSlashes(pg_result($resaco,$iresaco,'v84_dtarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3211,18179,'','".AddSlashes(pg_result($resaco,$iresaco,'v84_dtprocessamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from certidarqretorno
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v84_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v84_sequencial = $v84_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "certidarqretorno nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v84_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "certidarqretorno nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v84_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v84_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:certidarqretorno";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $v84_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from certidarqretorno ";
     $sql .= "      inner join certidarqremessa  on  certidarqremessa.v83_sequencial = certidarqretorno.v84_certidarqremessa";
     $sql .= "      inner join lista  on  lista.k60_codigo = certidarqremessa.v83_lista";
     $sql2 = "";
     if($dbwhere==""){
       if($v84_sequencial!=null ){
         $sql2 .= " where certidarqretorno.v84_sequencial = $v84_sequencial "; 
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
   // funcao do sql 
   function sql_query_file ( $v84_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from certidarqretorno ";
     $sql2 = "";
     if($dbwhere==""){
       if($v84_sequencial!=null ){
         $sql2 .= " where certidarqretorno.v84_sequencial = $v84_sequencial "; 
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