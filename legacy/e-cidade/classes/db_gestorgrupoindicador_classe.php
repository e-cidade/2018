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

//MODULO: gestorbi
//CLASSE DA ENTIDADE gestorgrupoindicador
class cl_gestorgrupoindicador { 
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
   var $g03_sequencial = 0; 
   var $g03_descricao = null; 
   var $g03_datalimite_dia = null; 
   var $g03_datalimite_mes = null; 
   var $g03_datalimite_ano = null; 
   var $g03_datalimite = null; 
   var $g03_projeto = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 g03_sequencial = int4 = Código Sequencial 
                 g03_descricao = varchar(100) = Descricao do Grupo 
                 g03_datalimite = date = Data Limite 
                 g03_projeto = text = Projeto 
                 ";
   //funcao construtor da classe 
   function cl_gestorgrupoindicador() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("gestorgrupoindicador"); 
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
       $this->g03_sequencial = ($this->g03_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["g03_sequencial"]:$this->g03_sequencial);
       $this->g03_descricao = ($this->g03_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["g03_descricao"]:$this->g03_descricao);
       if($this->g03_datalimite == ""){
         $this->g03_datalimite_dia = ($this->g03_datalimite_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["g03_datalimite_dia"]:$this->g03_datalimite_dia);
         $this->g03_datalimite_mes = ($this->g03_datalimite_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["g03_datalimite_mes"]:$this->g03_datalimite_mes);
         $this->g03_datalimite_ano = ($this->g03_datalimite_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["g03_datalimite_ano"]:$this->g03_datalimite_ano);
         if($this->g03_datalimite_dia != ""){
            $this->g03_datalimite = $this->g03_datalimite_ano."-".$this->g03_datalimite_mes."-".$this->g03_datalimite_dia;
         }
       }
       $this->g03_projeto = ($this->g03_projeto == ""?@$GLOBALS["HTTP_POST_VARS"]["g03_projeto"]:$this->g03_projeto);
     }else{
       $this->g03_sequencial = ($this->g03_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["g03_sequencial"]:$this->g03_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($g03_sequencial){ 
      $this->atualizacampos();
     if($this->g03_descricao == null ){ 
       $this->erro_sql = " Campo Descricao do Grupo nao Informado.";
       $this->erro_campo = "g03_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->g03_datalimite == null ){ 
       $this->g03_datalimite = "null";
     }
     if($this->g03_projeto == null ){ 
       $this->erro_sql = " Campo Projeto nao Informado.";
       $this->erro_campo = "g03_projeto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($g03_sequencial == "" || $g03_sequencial == null ){
       $result = db_query("select nextval('gestorgrupoindicador_g03_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: gestorgrupoindicador_g03_sequencial_seq do campo: g03_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->g03_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from gestorgrupoindicador_g03_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $g03_sequencial)){
         $this->erro_sql = " Campo g03_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->g03_sequencial = $g03_sequencial; 
       }
     }
     if(($this->g03_sequencial == null) || ($this->g03_sequencial == "") ){ 
       $this->erro_sql = " Campo g03_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into gestorgrupoindicador(
                                       g03_sequencial 
                                      ,g03_descricao 
                                      ,g03_datalimite 
                                      ,g03_projeto 
                       )
                values (
                                $this->g03_sequencial 
                               ,'$this->g03_descricao' 
                               ,".($this->g03_datalimite == "null" || $this->g03_datalimite == ""?"null":"'".$this->g03_datalimite."'")." 
                               ,'$this->g03_projeto' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "gestorgrupoindicador ($this->g03_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "gestorgrupoindicador já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "gestorgrupoindicador ($this->g03_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->g03_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->g03_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16048,'$this->g03_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2816,16048,'','".AddSlashes(pg_result($resaco,0,'g03_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2816,16049,'','".AddSlashes(pg_result($resaco,0,'g03_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2816,16050,'','".AddSlashes(pg_result($resaco,0,'g03_datalimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2816,18466,'','".AddSlashes(pg_result($resaco,0,'g03_projeto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($g03_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update gestorgrupoindicador set ";
     $virgula = "";
     if(trim($this->g03_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g03_sequencial"])){ 
       $sql  .= $virgula." g03_sequencial = $this->g03_sequencial ";
       $virgula = ",";
       if(trim($this->g03_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "g03_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g03_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g03_descricao"])){ 
       $sql  .= $virgula." g03_descricao = '$this->g03_descricao' ";
       $virgula = ",";
       if(trim($this->g03_descricao) == null ){ 
         $this->erro_sql = " Campo Descricao do Grupo nao Informado.";
         $this->erro_campo = "g03_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->g03_datalimite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g03_datalimite_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["g03_datalimite_dia"] !="") ){ 
       $sql  .= $virgula." g03_datalimite = '$this->g03_datalimite' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["g03_datalimite_dia"])){ 
         $sql  .= $virgula." g03_datalimite = null ";
         $virgula = ",";
       }
     }
     if(trim($this->g03_projeto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["g03_projeto"])){ 
       $sql  .= $virgula." g03_projeto = '$this->g03_projeto' ";
       $virgula = ",";
       if(trim($this->g03_projeto) == null ){ 
         $this->erro_sql = " Campo Projeto nao Informado.";
         $this->erro_campo = "g03_projeto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($g03_sequencial!=null){
       $sql .= " g03_sequencial = $this->g03_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->g03_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16048,'$this->g03_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g03_sequencial"]) || $this->g03_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2816,16048,'".AddSlashes(pg_result($resaco,$conresaco,'g03_sequencial'))."','$this->g03_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g03_descricao"]) || $this->g03_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2816,16049,'".AddSlashes(pg_result($resaco,$conresaco,'g03_descricao'))."','$this->g03_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g03_datalimite"]) || $this->g03_datalimite != "")
           $resac = db_query("insert into db_acount values($acount,2816,16050,'".AddSlashes(pg_result($resaco,$conresaco,'g03_datalimite'))."','$this->g03_datalimite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["g03_projeto"]) || $this->g03_projeto != "")
           $resac = db_query("insert into db_acount values($acount,2816,18466,'".AddSlashes(pg_result($resaco,$conresaco,'g03_projeto'))."','$this->g03_projeto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "gestorgrupoindicador nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->g03_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "gestorgrupoindicador nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->g03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->g03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($g03_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($g03_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16048,'$g03_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2816,16048,'','".AddSlashes(pg_result($resaco,$iresaco,'g03_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2816,16049,'','".AddSlashes(pg_result($resaco,$iresaco,'g03_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2816,16050,'','".AddSlashes(pg_result($resaco,$iresaco,'g03_datalimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2816,18466,'','".AddSlashes(pg_result($resaco,$iresaco,'g03_projeto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from gestorgrupoindicador
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($g03_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " g03_sequencial = $g03_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "gestorgrupoindicador nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$g03_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "gestorgrupoindicador nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$g03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$g03_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:gestorgrupoindicador";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $g03_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gestorgrupoindicador ";
     $sql2 = "";
     if($dbwhere==""){
       if($g03_sequencial!=null ){
         $sql2 .= " where gestorgrupoindicador.g03_sequencial = $g03_sequencial "; 
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
   function sql_query_file ( $g03_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gestorgrupoindicador ";
     $sql2 = "";
     if($dbwhere==""){
       if($g03_sequencial!=null ){
         $sql2 .= " where gestorgrupoindicador.g03_sequencial = $g03_sequencial "; 
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