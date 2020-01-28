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

//MODULO: empenho
//CLASSE DA ENTIDADE empagedadosretmovocorrencia
class cl_empagedadosretmovocorrencia { 
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
   var $e02_sequencial = 0; 
   var $e02_empagedadosret = 0; 
   var $e02_empagedadosretmov = 0; 
   var $e02_errobanco = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e02_sequencial = int4 = Sequencial 
                 e02_empagedadosret = int4 = Sequencial EmpAgeDadosRet 
                 e02_empagedadosretmov = int4 = Sequencial EmpAgeDadosRetMov 
                 e02_errobanco = int4 = Sequencial ErroBanco 
                 ";
   //funcao construtor da classe 
   function cl_empagedadosretmovocorrencia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empagedadosretmovocorrencia"); 
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
       $this->e02_sequencial = ($this->e02_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e02_sequencial"]:$this->e02_sequencial);
       $this->e02_empagedadosret = ($this->e02_empagedadosret == ""?@$GLOBALS["HTTP_POST_VARS"]["e02_empagedadosret"]:$this->e02_empagedadosret);
       $this->e02_empagedadosretmov = ($this->e02_empagedadosretmov == ""?@$GLOBALS["HTTP_POST_VARS"]["e02_empagedadosretmov"]:$this->e02_empagedadosretmov);
       $this->e02_errobanco = ($this->e02_errobanco == ""?@$GLOBALS["HTTP_POST_VARS"]["e02_errobanco"]:$this->e02_errobanco);
     }else{
       $this->e02_sequencial = ($this->e02_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e02_sequencial"]:$this->e02_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($e02_sequencial){ 
      $this->atualizacampos();
     if($this->e02_empagedadosret == null ){ 
       $this->erro_sql = " Campo Sequencial EmpAgeDadosRet nao Informado.";
       $this->erro_campo = "e02_empagedadosret";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e02_empagedadosretmov == null ){ 
       $this->erro_sql = " Campo Sequencial EmpAgeDadosRetMov nao Informado.";
       $this->erro_campo = "e02_empagedadosretmov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e02_errobanco == null ){ 
       $this->erro_sql = " Campo Sequencial ErroBanco nao Informado.";
       $this->erro_campo = "e02_errobanco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e02_sequencial == "" || $e02_sequencial == null ){
       $result = db_query("select nextval('empagedadosretmovocorrencia_e02_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empagedadosretmovocorrencia_e02_sequencial_seq do campo: e02_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e02_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from empagedadosretmovocorrencia_e02_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e02_sequencial)){
         $this->erro_sql = " Campo e02_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e02_sequencial = $e02_sequencial; 
       }
     }
     if(($this->e02_sequencial == null) || ($this->e02_sequencial == "") ){ 
       $this->erro_sql = " Campo e02_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empagedadosretmovocorrencia(
                                       e02_sequencial 
                                      ,e02_empagedadosret 
                                      ,e02_empagedadosretmov 
                                      ,e02_errobanco 
                       )
                values (
                                $this->e02_sequencial 
                               ,$this->e02_empagedadosret 
                               ,$this->e02_empagedadosretmov 
                               ,$this->e02_errobanco 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "empagedadosretmovocorrencia ($this->e02_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "empagedadosretmovocorrencia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "empagedadosretmovocorrencia ($this->e02_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e02_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e02_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18446,'$this->e02_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3262,18446,'','".AddSlashes(pg_result($resaco,0,'e02_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3262,18449,'','".AddSlashes(pg_result($resaco,0,'e02_empagedadosret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3262,18447,'','".AddSlashes(pg_result($resaco,0,'e02_empagedadosretmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3262,18448,'','".AddSlashes(pg_result($resaco,0,'e02_errobanco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e02_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update empagedadosretmovocorrencia set ";
     $virgula = "";
     if(trim($this->e02_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e02_sequencial"])){ 
       $sql  .= $virgula." e02_sequencial = $this->e02_sequencial ";
       $virgula = ",";
       if(trim($this->e02_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "e02_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e02_empagedadosret)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e02_empagedadosret"])){ 
       $sql  .= $virgula." e02_empagedadosret = $this->e02_empagedadosret ";
       $virgula = ",";
       if(trim($this->e02_empagedadosret) == null ){ 
         $this->erro_sql = " Campo Sequencial EmpAgeDadosRet nao Informado.";
         $this->erro_campo = "e02_empagedadosret";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e02_empagedadosretmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e02_empagedadosretmov"])){ 
       $sql  .= $virgula." e02_empagedadosretmov = $this->e02_empagedadosretmov ";
       $virgula = ",";
       if(trim($this->e02_empagedadosretmov) == null ){ 
         $this->erro_sql = " Campo Sequencial EmpAgeDadosRetMov nao Informado.";
         $this->erro_campo = "e02_empagedadosretmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e02_errobanco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e02_errobanco"])){ 
       $sql  .= $virgula." e02_errobanco = $this->e02_errobanco ";
       $virgula = ",";
       if(trim($this->e02_errobanco) == null ){ 
         $this->erro_sql = " Campo Sequencial ErroBanco nao Informado.";
         $this->erro_campo = "e02_errobanco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e02_sequencial!=null){
       $sql .= " e02_sequencial = $this->e02_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e02_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18446,'$this->e02_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e02_sequencial"]) || $this->e02_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3262,18446,'".AddSlashes(pg_result($resaco,$conresaco,'e02_sequencial'))."','$this->e02_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e02_empagedadosret"]) || $this->e02_empagedadosret != "")
           $resac = db_query("insert into db_acount values($acount,3262,18449,'".AddSlashes(pg_result($resaco,$conresaco,'e02_empagedadosret'))."','$this->e02_empagedadosret',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e02_empagedadosretmov"]) || $this->e02_empagedadosretmov != "")
           $resac = db_query("insert into db_acount values($acount,3262,18447,'".AddSlashes(pg_result($resaco,$conresaco,'e02_empagedadosretmov'))."','$this->e02_empagedadosretmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e02_errobanco"]) || $this->e02_errobanco != "")
           $resac = db_query("insert into db_acount values($acount,3262,18448,'".AddSlashes(pg_result($resaco,$conresaco,'e02_errobanco'))."','$this->e02_errobanco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "empagedadosretmovocorrencia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e02_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "empagedadosretmovocorrencia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e02_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e02_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18446,'$e02_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3262,18446,'','".AddSlashes(pg_result($resaco,$iresaco,'e02_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3262,18449,'','".AddSlashes(pg_result($resaco,$iresaco,'e02_empagedadosret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3262,18447,'','".AddSlashes(pg_result($resaco,$iresaco,'e02_empagedadosretmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3262,18448,'','".AddSlashes(pg_result($resaco,$iresaco,'e02_errobanco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empagedadosretmovocorrencia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e02_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e02_sequencial = $e02_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "empagedadosretmovocorrencia nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e02_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "empagedadosretmovocorrencia nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e02_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:empagedadosretmovocorrencia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $e02_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empagedadosretmovocorrencia ";
     $sql .= "      inner join errobanco          on  errobanco.e92_sequencia      = empagedadosretmovocorrencia.e02_errobanco";
     $sql .= "      inner join empagedadosretmov  on  empagedadosretmov.e76_codmov = empagedadosretmovocorrencia.e02_empagedadosretmov 
                                                 and  empagedadosretmov.e76_codret = empagedadosretmovocorrencia.e02_empagedadosret";
     $sql .= "      inner join empagedadosret     on  empagedadosret.e75_codret    = empagedadosretmov.e76_codret";
     $sql2 = "";
     if($dbwhere==""){
       if($e02_sequencial!=null ){
         $sql2 .= " where empagedadosretmovocorrencia.e02_sequencial = $e02_sequencial "; 
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
   function sql_query_file ( $e02_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empagedadosretmovocorrencia ";
     $sql2 = "";
     if($dbwhere==""){
       if($e02_sequencial!=null ){
         $sql2 .= " where empagedadosretmovocorrencia.e02_sequencial = $e02_sequencial "; 
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