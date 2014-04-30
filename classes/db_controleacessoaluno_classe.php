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

//MODULO: escola
//CLASSE DA ENTIDADE controleacessoaluno
class cl_controleacessoaluno { 
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
   var $ed100_sequencial = 0; 
   var $ed100_id_usuario = 0; 
   var $ed100_dataleitura_dia = null; 
   var $ed100_dataleitura_mes = null; 
   var $ed100_dataleitura_ano = null; 
   var $ed100_dataleitura = null; 
   var $ed100_horaleitura = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed100_sequencial = int4 = Sequencial interno 
                 ed100_id_usuario = int4 = Usuário da Coleta 
                 ed100_dataleitura = date = Data da Ultima Leitura 
                 ed100_horaleitura = varchar(8) = Hora da Ultima Coleta 
                 ";
   //funcao construtor da classe 
   function cl_controleacessoaluno() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("controleacessoaluno"); 
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
       $this->ed100_sequencial = ($this->ed100_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed100_sequencial"]:$this->ed100_sequencial);
       $this->ed100_id_usuario = ($this->ed100_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed100_id_usuario"]:$this->ed100_id_usuario);
       if($this->ed100_dataleitura == ""){
         $this->ed100_dataleitura_dia = ($this->ed100_dataleitura_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed100_dataleitura_dia"]:$this->ed100_dataleitura_dia);
         $this->ed100_dataleitura_mes = ($this->ed100_dataleitura_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed100_dataleitura_mes"]:$this->ed100_dataleitura_mes);
         $this->ed100_dataleitura_ano = ($this->ed100_dataleitura_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed100_dataleitura_ano"]:$this->ed100_dataleitura_ano);
         if($this->ed100_dataleitura_dia != ""){
            $this->ed100_dataleitura = $this->ed100_dataleitura_ano."-".$this->ed100_dataleitura_mes."-".$this->ed100_dataleitura_dia;
         }
       }
       $this->ed100_horaleitura = ($this->ed100_horaleitura == ""?@$GLOBALS["HTTP_POST_VARS"]["ed100_horaleitura"]:$this->ed100_horaleitura);
     }else{
       $this->ed100_sequencial = ($this->ed100_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed100_sequencial"]:$this->ed100_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed100_sequencial){ 
      $this->atualizacampos();
     if($this->ed100_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário da Coleta nao Informado.";
       $this->erro_campo = "ed100_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed100_dataleitura == null ){ 
       $this->erro_sql = " Campo Data da Ultima Leitura nao Informado.";
       $this->erro_campo = "ed100_dataleitura_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed100_horaleitura == null ){ 
       $this->erro_sql = " Campo Hora da Ultima Coleta nao Informado.";
       $this->erro_campo = "ed100_horaleitura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed100_sequencial == "" || $ed100_sequencial == null ){
       $result = db_query("select nextval('controleacessoaluno_ed100_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: controleacessoaluno_ed100_sequencial_seq do campo: ed100_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed100_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from controleacessoaluno_ed100_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed100_sequencial)){
         $this->erro_sql = " Campo ed100_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed100_sequencial = $ed100_sequencial; 
       }
     }
     if(($this->ed100_sequencial == null) || ($this->ed100_sequencial == "") ){ 
       $this->erro_sql = " Campo ed100_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into controleacessoaluno(
                                       ed100_sequencial 
                                      ,ed100_id_usuario 
                                      ,ed100_dataleitura 
                                      ,ed100_horaleitura 
                       )
                values (
                                $this->ed100_sequencial 
                               ,$this->ed100_id_usuario 
                               ,".($this->ed100_dataleitura == "null" || $this->ed100_dataleitura == ""?"null":"'".$this->ed100_dataleitura."'")." 
                               ,'$this->ed100_horaleitura' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Controle de acesso de alunos ($this->ed100_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Controle de acesso de alunos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Controle de acesso de alunos ($this->ed100_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed100_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed100_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18744,'$this->ed100_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3322,18744,'','".AddSlashes(pg_result($resaco,0,'ed100_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3322,18747,'','".AddSlashes(pg_result($resaco,0,'ed100_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3322,18746,'','".AddSlashes(pg_result($resaco,0,'ed100_dataleitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3322,18745,'','".AddSlashes(pg_result($resaco,0,'ed100_horaleitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed100_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update controleacessoaluno set ";
     $virgula = "";
     if(trim($this->ed100_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed100_sequencial"])){ 
       $sql  .= $virgula." ed100_sequencial = $this->ed100_sequencial ";
       $virgula = ",";
       if(trim($this->ed100_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial interno nao Informado.";
         $this->erro_campo = "ed100_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed100_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed100_id_usuario"])){ 
       $sql  .= $virgula." ed100_id_usuario = $this->ed100_id_usuario ";
       $virgula = ",";
       if(trim($this->ed100_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário da Coleta nao Informado.";
         $this->erro_campo = "ed100_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed100_dataleitura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed100_dataleitura_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed100_dataleitura_dia"] !="") ){ 
       $sql  .= $virgula." ed100_dataleitura = '$this->ed100_dataleitura' ";
       $virgula = ",";
       if(trim($this->ed100_dataleitura) == null ){ 
         $this->erro_sql = " Campo Data da Ultima Leitura nao Informado.";
         $this->erro_campo = "ed100_dataleitura_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed100_dataleitura_dia"])){ 
         $sql  .= $virgula." ed100_dataleitura = null ";
         $virgula = ",";
         if(trim($this->ed100_dataleitura) == null ){ 
           $this->erro_sql = " Campo Data da Ultima Leitura nao Informado.";
           $this->erro_campo = "ed100_dataleitura_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed100_horaleitura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed100_horaleitura"])){ 
       $sql  .= $virgula." ed100_horaleitura = '$this->ed100_horaleitura' ";
       $virgula = ",";
       if(trim($this->ed100_horaleitura) == null ){ 
         $this->erro_sql = " Campo Hora da Ultima Coleta nao Informado.";
         $this->erro_campo = "ed100_horaleitura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed100_sequencial!=null){
       $sql .= " ed100_sequencial = $this->ed100_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed100_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18744,'$this->ed100_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed100_sequencial"]) || $this->ed100_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3322,18744,'".AddSlashes(pg_result($resaco,$conresaco,'ed100_sequencial'))."','$this->ed100_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed100_id_usuario"]) || $this->ed100_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3322,18747,'".AddSlashes(pg_result($resaco,$conresaco,'ed100_id_usuario'))."','$this->ed100_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed100_dataleitura"]) || $this->ed100_dataleitura != "")
           $resac = db_query("insert into db_acount values($acount,3322,18746,'".AddSlashes(pg_result($resaco,$conresaco,'ed100_dataleitura'))."','$this->ed100_dataleitura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed100_horaleitura"]) || $this->ed100_horaleitura != "")
           $resac = db_query("insert into db_acount values($acount,3322,18745,'".AddSlashes(pg_result($resaco,$conresaco,'ed100_horaleitura'))."','$this->ed100_horaleitura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Controle de acesso de alunos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed100_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Controle de acesso de alunos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed100_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed100_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed100_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed100_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18744,'$ed100_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3322,18744,'','".AddSlashes(pg_result($resaco,$iresaco,'ed100_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3322,18747,'','".AddSlashes(pg_result($resaco,$iresaco,'ed100_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3322,18746,'','".AddSlashes(pg_result($resaco,$iresaco,'ed100_dataleitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3322,18745,'','".AddSlashes(pg_result($resaco,$iresaco,'ed100_horaleitura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from controleacessoaluno
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed100_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed100_sequencial = $ed100_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Controle de acesso de alunos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed100_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Controle de acesso de alunos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed100_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed100_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:controleacessoaluno";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed100_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from controleacessoaluno ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = controleacessoaluno.ed100_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($ed100_sequencial!=null ){
         $sql2 .= " where controleacessoaluno.ed100_sequencial = $ed100_sequencial "; 
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
   function sql_query_file ( $ed100_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from controleacessoaluno ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed100_sequencial!=null ){
         $sql2 .= " where controleacessoaluno.ed100_sequencial = $ed100_sequencial "; 
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
  
  function sql_query_horario_aula_turma ( $ed100_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 

    $sql = "select ";
    if ($campos != "*" ) {
      
      $campos_sql = split("#",$campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from turma ";
    $sql .= "      inner join periodoescola on ed17_i_turno = ed57_i_turno ";
    $sql2 = "";
    if ($dbwhere == "") {
      
      if ($ed100_sequencial != null) {
        $sql2 .= " where controleacessoaluno.ed100_sequencial = $ed100_sequencial "; 
      } 
    } else if($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
       
      $sql        .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}
?>