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

//MODULO: Arrecadacao
//CLASSE DA ENTIDADE declaracaoquitacaocgm
class cl_declaracaoquitacaocgm { 
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
   var $ar34_sequencial = 0; 
   var $ar34_numcgm = 0; 
   var $ar34_declaracaoquitacao = 0; 
   var $ar34_somentecgm = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ar34_sequencial = int8 = Código 
                 ar34_numcgm = int4 = Número do CGM 
                 ar34_declaracaoquitacao = int8 = Código Declaração 
                 ar34_somentecgm = bool = Somente CGM 
                 ";
   //funcao construtor da classe 
   function cl_declaracaoquitacaocgm() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("declaracaoquitacaocgm"); 
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
       $this->ar34_sequencial = ($this->ar34_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar34_sequencial"]:$this->ar34_sequencial);
       $this->ar34_numcgm = ($this->ar34_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["ar34_numcgm"]:$this->ar34_numcgm);
       $this->ar34_declaracaoquitacao = ($this->ar34_declaracaoquitacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ar34_declaracaoquitacao"]:$this->ar34_declaracaoquitacao);
       $this->ar34_somentecgm = ($this->ar34_somentecgm == "f"?@$GLOBALS["HTTP_POST_VARS"]["ar34_somentecgm"]:$this->ar34_somentecgm);
     }else{
       $this->ar34_sequencial = ($this->ar34_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar34_sequencial"]:$this->ar34_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ar34_sequencial){ 
      $this->atualizacampos();
     if($this->ar34_numcgm == null ){ 
       $this->erro_sql = " Campo Número do CGM nao Informado.";
       $this->erro_campo = "ar34_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar34_declaracaoquitacao == null ){ 
       $this->erro_sql = " Campo Código Declaração nao Informado.";
       $this->erro_campo = "ar34_declaracaoquitacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar34_somentecgm == null ){ 
       $this->erro_sql = " Campo Somente CGM nao Informado.";
       $this->erro_campo = "ar34_somentecgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ar34_sequencial == "" || $ar34_sequencial == null ){
       $result = db_query("select nextval('declaracaoquitacaocgm_ar34_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: declaracaoquitacaocgm_ar34_sequencial_seq do campo: ar34_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ar34_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from declaracaoquitacaocgm_ar34_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ar34_sequencial)){
         $this->erro_sql = " Campo ar34_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ar34_sequencial = $ar34_sequencial; 
       }
     }
     if(($this->ar34_sequencial == null) || ($this->ar34_sequencial == "") ){ 
       $this->erro_sql = " Campo ar34_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into declaracaoquitacaocgm(
                                       ar34_sequencial 
                                      ,ar34_numcgm 
                                      ,ar34_declaracaoquitacao 
                                      ,ar34_somentecgm 
                       )
                values (
                                $this->ar34_sequencial 
                               ,$this->ar34_numcgm 
                               ,$this->ar34_declaracaoquitacao 
                               ,'$this->ar34_somentecgm' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "CGM Declaração de Quitação ($this->ar34_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "CGM Declaração de Quitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "CGM Declaração de Quitação ($this->ar34_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar34_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ar34_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17167,'$this->ar34_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3034,17167,'','".AddSlashes(pg_result($resaco,0,'ar34_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3034,17168,'','".AddSlashes(pg_result($resaco,0,'ar34_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3034,17169,'','".AddSlashes(pg_result($resaco,0,'ar34_declaracaoquitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3034,17180,'','".AddSlashes(pg_result($resaco,0,'ar34_somentecgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ar34_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update declaracaoquitacaocgm set ";
     $virgula = "";
     if(trim($this->ar34_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar34_sequencial"])){ 
       $sql  .= $virgula." ar34_sequencial = $this->ar34_sequencial ";
       $virgula = ",";
       if(trim($this->ar34_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ar34_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar34_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar34_numcgm"])){ 
       $sql  .= $virgula." ar34_numcgm = $this->ar34_numcgm ";
       $virgula = ",";
       if(trim($this->ar34_numcgm) == null ){ 
         $this->erro_sql = " Campo Número do CGM nao Informado.";
         $this->erro_campo = "ar34_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar34_declaracaoquitacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar34_declaracaoquitacao"])){ 
       $sql  .= $virgula." ar34_declaracaoquitacao = $this->ar34_declaracaoquitacao ";
       $virgula = ",";
       if(trim($this->ar34_declaracaoquitacao) == null ){ 
         $this->erro_sql = " Campo Código Declaração nao Informado.";
         $this->erro_campo = "ar34_declaracaoquitacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar34_somentecgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar34_somentecgm"])){ 
       $sql  .= $virgula." ar34_somentecgm = '$this->ar34_somentecgm' ";
       $virgula = ",";
       if(trim($this->ar34_somentecgm) == null ){ 
         $this->erro_sql = " Campo Somente CGM nao Informado.";
         $this->erro_campo = "ar34_somentecgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ar34_sequencial!=null){
       $sql .= " ar34_sequencial = $this->ar34_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ar34_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17167,'$this->ar34_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar34_sequencial"]) || $this->ar34_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3034,17167,'".AddSlashes(pg_result($resaco,$conresaco,'ar34_sequencial'))."','$this->ar34_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar34_numcgm"]) || $this->ar34_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,3034,17168,'".AddSlashes(pg_result($resaco,$conresaco,'ar34_numcgm'))."','$this->ar34_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar34_declaracaoquitacao"]) || $this->ar34_declaracaoquitacao != "")
           $resac = db_query("insert into db_acount values($acount,3034,17169,'".AddSlashes(pg_result($resaco,$conresaco,'ar34_declaracaoquitacao'))."','$this->ar34_declaracaoquitacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar34_somentecgm"]) || $this->ar34_somentecgm != "")
           $resac = db_query("insert into db_acount values($acount,3034,17180,'".AddSlashes(pg_result($resaco,$conresaco,'ar34_somentecgm'))."','$this->ar34_somentecgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "CGM Declaração de Quitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar34_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "CGM Declaração de Quitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar34_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar34_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ar34_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ar34_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17167,'$ar34_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3034,17167,'','".AddSlashes(pg_result($resaco,$iresaco,'ar34_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3034,17168,'','".AddSlashes(pg_result($resaco,$iresaco,'ar34_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3034,17169,'','".AddSlashes(pg_result($resaco,$iresaco,'ar34_declaracaoquitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3034,17180,'','".AddSlashes(pg_result($resaco,$iresaco,'ar34_somentecgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from declaracaoquitacaocgm
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ar34_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ar34_sequencial = $ar34_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "CGM Declaração de Quitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ar34_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "CGM Declaração de Quitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ar34_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ar34_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:declaracaoquitacaocgm";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ar34_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from declaracaoquitacaocgm ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = declaracaoquitacaocgm.ar34_numcgm";
     $sql .= "      inner join declaracaoquitacao  on  declaracaoquitacao.ar30_sequencial = declaracaoquitacaocgm.ar34_declaracaoquitacao";
     $sql .= "      inner join db_config  on  db_config.codigo = declaracaoquitacao.ar30_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = declaracaoquitacao.ar30_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($ar34_sequencial!=null ){
         $sql2 .= " where declaracaoquitacaocgm.ar34_sequencial = $ar34_sequencial "; 
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
   function sql_query_file ( $ar34_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from declaracaoquitacaocgm ";
     $sql2 = "";
     if($dbwhere==""){
       if($ar34_sequencial!=null ){
         $sql2 .= " where declaracaoquitacaocgm.ar34_sequencial = $ar34_sequencial "; 
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