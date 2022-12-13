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
//CLASSE DA ENTIDADE certidarqremessa
class cl_certidarqremessa { 
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
   var $v83_sequencial = 0; 
   var $v83_nomearq = null; 
   var $v83_lista = 0; 
   var $v83_dtgeracao_dia = null; 
   var $v83_dtgeracao_mes = null; 
   var $v83_dtgeracao_ano = null; 
   var $v83_dtgeracao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v83_sequencial = int4 = Sequencial 
                 v83_nomearq = varchar(50) = Nome do Arquivo 
                 v83_lista = int4 = Lista 
                 v83_dtgeracao = date = Data de Geração 
                 ";
   //funcao construtor da classe 
   function cl_certidarqremessa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("certidarqremessa"); 
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
       $this->v83_sequencial = ($this->v83_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v83_sequencial"]:$this->v83_sequencial);
       $this->v83_nomearq = ($this->v83_nomearq == ""?@$GLOBALS["HTTP_POST_VARS"]["v83_nomearq"]:$this->v83_nomearq);
       $this->v83_lista = ($this->v83_lista == ""?@$GLOBALS["HTTP_POST_VARS"]["v83_lista"]:$this->v83_lista);
       if($this->v83_dtgeracao == ""){
         $this->v83_dtgeracao_dia = ($this->v83_dtgeracao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v83_dtgeracao_dia"]:$this->v83_dtgeracao_dia);
         $this->v83_dtgeracao_mes = ($this->v83_dtgeracao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v83_dtgeracao_mes"]:$this->v83_dtgeracao_mes);
         $this->v83_dtgeracao_ano = ($this->v83_dtgeracao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v83_dtgeracao_ano"]:$this->v83_dtgeracao_ano);
         if($this->v83_dtgeracao_dia != ""){
            $this->v83_dtgeracao = $this->v83_dtgeracao_ano."-".$this->v83_dtgeracao_mes."-".$this->v83_dtgeracao_dia;
         }
       }
     }else{
       $this->v83_sequencial = ($this->v83_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v83_sequencial"]:$this->v83_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($v83_sequencial){ 
      $this->atualizacampos();
     if($this->v83_nomearq == null ){ 
       $this->erro_sql = " Campo Nome do Arquivo nao Informado.";
       $this->erro_campo = "v83_nomearq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v83_lista == null ){ 
       $this->erro_sql = " Campo Lista nao Informado.";
       $this->erro_campo = "v83_lista";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v83_dtgeracao == null ){ 
       $this->erro_sql = " Campo Data de Geração nao Informado.";
       $this->erro_campo = "v83_dtgeracao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v83_sequencial == "" || $v83_sequencial == null ){
       $result = db_query("select nextval('certidarqremessa_v83_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: certidarqremessa_v83_sequencial_seq do campo: v83_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v83_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from certidarqremessa_v83_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v83_sequencial)){
         $this->erro_sql = " Campo v83_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v83_sequencial = $v83_sequencial; 
       }
     }
     if(($this->v83_sequencial == null) || ($this->v83_sequencial == "") ){ 
       $this->erro_sql = " Campo v83_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into certidarqremessa(
                                       v83_sequencial 
                                      ,v83_nomearq 
                                      ,v83_lista 
                                      ,v83_dtgeracao 
                       )
                values (
                                $this->v83_sequencial 
                               ,'$this->v83_nomearq' 
                               ,$this->v83_lista 
                               ,".($this->v83_dtgeracao == "null" || $this->v83_dtgeracao == ""?"null":"'".$this->v83_dtgeracao."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "certidarqremessa ($this->v83_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "certidarqremessa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "certidarqremessa ($this->v83_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v83_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v83_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18170,'$this->v83_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3210,18170,'','".AddSlashes(pg_result($resaco,0,'v83_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3210,18172,'','".AddSlashes(pg_result($resaco,0,'v83_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3210,18171,'','".AddSlashes(pg_result($resaco,0,'v83_lista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3210,18173,'','".AddSlashes(pg_result($resaco,0,'v83_dtgeracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v83_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update certidarqremessa set ";
     $virgula = "";
     if(trim($this->v83_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v83_sequencial"])){ 
       $sql  .= $virgula." v83_sequencial = $this->v83_sequencial ";
       $virgula = ",";
       if(trim($this->v83_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "v83_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v83_nomearq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v83_nomearq"])){ 
       $sql  .= $virgula." v83_nomearq = '$this->v83_nomearq' ";
       $virgula = ",";
       if(trim($this->v83_nomearq) == null ){ 
         $this->erro_sql = " Campo Nome do Arquivo nao Informado.";
         $this->erro_campo = "v83_nomearq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v83_lista)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v83_lista"])){ 
       $sql  .= $virgula." v83_lista = $this->v83_lista ";
       $virgula = ",";
       if(trim($this->v83_lista) == null ){ 
         $this->erro_sql = " Campo Lista nao Informado.";
         $this->erro_campo = "v83_lista";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v83_dtgeracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v83_dtgeracao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v83_dtgeracao_dia"] !="") ){ 
       $sql  .= $virgula." v83_dtgeracao = '$this->v83_dtgeracao' ";
       $virgula = ",";
       if(trim($this->v83_dtgeracao) == null ){ 
         $this->erro_sql = " Campo Data de Geração nao Informado.";
         $this->erro_campo = "v83_dtgeracao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["v83_dtgeracao_dia"])){ 
         $sql  .= $virgula." v83_dtgeracao = null ";
         $virgula = ",";
         if(trim($this->v83_dtgeracao) == null ){ 
           $this->erro_sql = " Campo Data de Geração nao Informado.";
           $this->erro_campo = "v83_dtgeracao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($v83_sequencial!=null){
       $sql .= " v83_sequencial = $this->v83_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v83_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18170,'$this->v83_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v83_sequencial"]) || $this->v83_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3210,18170,'".AddSlashes(pg_result($resaco,$conresaco,'v83_sequencial'))."','$this->v83_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v83_nomearq"]) || $this->v83_nomearq != "")
           $resac = db_query("insert into db_acount values($acount,3210,18172,'".AddSlashes(pg_result($resaco,$conresaco,'v83_nomearq'))."','$this->v83_nomearq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v83_lista"]) || $this->v83_lista != "")
           $resac = db_query("insert into db_acount values($acount,3210,18171,'".AddSlashes(pg_result($resaco,$conresaco,'v83_lista'))."','$this->v83_lista',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v83_dtgeracao"]) || $this->v83_dtgeracao != "")
           $resac = db_query("insert into db_acount values($acount,3210,18173,'".AddSlashes(pg_result($resaco,$conresaco,'v83_dtgeracao'))."','$this->v83_dtgeracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "certidarqremessa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v83_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "certidarqremessa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v83_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v83_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v83_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v83_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18170,'$v83_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3210,18170,'','".AddSlashes(pg_result($resaco,$iresaco,'v83_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3210,18172,'','".AddSlashes(pg_result($resaco,$iresaco,'v83_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3210,18171,'','".AddSlashes(pg_result($resaco,$iresaco,'v83_lista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3210,18173,'','".AddSlashes(pg_result($resaco,$iresaco,'v83_dtgeracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from certidarqremessa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v83_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v83_sequencial = $v83_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "certidarqremessa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v83_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "certidarqremessa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v83_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v83_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:certidarqremessa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $v83_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from certidarqremessa ";
     $sql .= "      inner join lista  on  lista.k60_codigo = certidarqremessa.v83_lista";
     $sql .= "      inner join db_config  on  db_config.codigo = lista.k60_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = lista.k60_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($v83_sequencial!=null ){
         $sql2 .= " where certidarqremessa.v83_sequencial = $v83_sequencial "; 
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
   function sql_query_file ( $v83_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from certidarqremessa ";
     $sql2 = "";
     if($dbwhere==""){
       if($v83_sequencial!=null ){
         $sql2 .= " where certidarqremessa.v83_sequencial = $v83_sequencial "; 
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