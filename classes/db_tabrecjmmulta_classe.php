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

//MODULO: caixa
//CLASSE DA ENTIDADE tabrecjmmulta
class cl_tabrecjmmulta { 
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
   var $k140_sequencial = 0; 
   var $k140_tabrecjm = 0; 
   var $k140_multa = 0; 
   var $k140_faixa = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k140_sequencial = int4 = Código 
                 k140_tabrecjm = int4 = Código do Juro e Multa 
                 k140_multa = int4 = Dias Faixa 
                 k140_faixa = float4 = Faixa Percentual 
                 ";
   //funcao construtor da classe 
   function cl_tabrecjmmulta() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tabrecjmmulta"); 
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
       $this->k140_sequencial = ($this->k140_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k140_sequencial"]:$this->k140_sequencial);
       $this->k140_tabrecjm = ($this->k140_tabrecjm == ""?@$GLOBALS["HTTP_POST_VARS"]["k140_tabrecjm"]:$this->k140_tabrecjm);
       $this->k140_multa = ($this->k140_multa == ""?@$GLOBALS["HTTP_POST_VARS"]["k140_multa"]:$this->k140_multa);
       $this->k140_faixa = ($this->k140_faixa == ""?@$GLOBALS["HTTP_POST_VARS"]["k140_faixa"]:$this->k140_faixa);
     }else{
       $this->k140_sequencial = ($this->k140_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k140_sequencial"]:$this->k140_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k140_sequencial){ 
      $this->atualizacampos();
     if($this->k140_tabrecjm == null ){ 
       $this->erro_sql = " Campo Código do Juro e Multa nao Informado.";
       $this->erro_campo = "k140_tabrecjm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k140_multa == null ){ 
       $this->erro_sql = " Campo Dias Faixa nao Informado.";
       $this->erro_campo = "k140_multa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k140_faixa == null ){ 
       $this->erro_sql = " Campo Faixa Percentual nao Informado.";
       $this->erro_campo = "k140_faixa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k140_sequencial == "" || $k140_sequencial == null ){
       $result = db_query("select nextval('tabrecjmmulta_k140_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tabrecjmmulta_k140_sequencial_seq do campo: k140_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k140_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tabrecjmmulta_k140_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k140_sequencial)){
         $this->erro_sql = " Campo k140_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k140_sequencial = $k140_sequencial; 
       }
     }
     if(($this->k140_sequencial == null) || ($this->k140_sequencial == "") ){ 
       $this->erro_sql = " Campo k140_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tabrecjmmulta(
                                       k140_sequencial 
                                      ,k140_tabrecjm 
                                      ,k140_multa 
                                      ,k140_faixa 
                       )
                values (
                                $this->k140_sequencial 
                               ,$this->k140_tabrecjm 
                               ,$this->k140_multa 
                               ,$this->k140_faixa 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tabrecjmmulta ($this->k140_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tabrecjmmulta já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tabrecjmmulta ($this->k140_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k140_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k140_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18929,'$this->k140_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3366,18929,'','".AddSlashes(pg_result($resaco,0,'k140_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3366,18930,'','".AddSlashes(pg_result($resaco,0,'k140_tabrecjm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3366,18931,'','".AddSlashes(pg_result($resaco,0,'k140_multa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3366,18932,'','".AddSlashes(pg_result($resaco,0,'k140_faixa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k140_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update tabrecjmmulta set ";
     $virgula = "";
     if(trim($this->k140_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k140_sequencial"])){ 
       $sql  .= $virgula." k140_sequencial = $this->k140_sequencial ";
       $virgula = ",";
       if(trim($this->k140_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "k140_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k140_tabrecjm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k140_tabrecjm"])){ 
       $sql  .= $virgula." k140_tabrecjm = $this->k140_tabrecjm ";
       $virgula = ",";
       if(trim($this->k140_tabrecjm) == null ){ 
         $this->erro_sql = " Campo Código do Juro e Multa nao Informado.";
         $this->erro_campo = "k140_tabrecjm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k140_multa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k140_multa"])){ 
       $sql  .= $virgula." k140_multa = $this->k140_multa ";
       $virgula = ",";
       if(trim($this->k140_multa) == null ){ 
         $this->erro_sql = " Campo Dias Faixa nao Informado.";
         $this->erro_campo = "k140_multa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k140_faixa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k140_faixa"])){ 
       $sql  .= $virgula." k140_faixa = $this->k140_faixa ";
       $virgula = ",";
       if(trim($this->k140_faixa) == null ){ 
         $this->erro_sql = " Campo Faixa Percentual nao Informado.";
         $this->erro_campo = "k140_faixa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k140_sequencial!=null){
       $sql .= " k140_sequencial = $this->k140_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k140_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18929,'$this->k140_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k140_sequencial"]) || $this->k140_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3366,18929,'".AddSlashes(pg_result($resaco,$conresaco,'k140_sequencial'))."','$this->k140_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k140_tabrecjm"]) || $this->k140_tabrecjm != "")
           $resac = db_query("insert into db_acount values($acount,3366,18930,'".AddSlashes(pg_result($resaco,$conresaco,'k140_tabrecjm'))."','$this->k140_tabrecjm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k140_multa"]) || $this->k140_multa != "")
           $resac = db_query("insert into db_acount values($acount,3366,18931,'".AddSlashes(pg_result($resaco,$conresaco,'k140_multa'))."','$this->k140_multa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k140_faixa"]) || $this->k140_faixa != "")
           $resac = db_query("insert into db_acount values($acount,3366,18932,'".AddSlashes(pg_result($resaco,$conresaco,'k140_faixa'))."','$this->k140_faixa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tabrecjmmulta nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k140_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tabrecjmmulta nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k140_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k140_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k140_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k140_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18929,'$k140_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3366,18929,'','".AddSlashes(pg_result($resaco,$iresaco,'k140_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3366,18930,'','".AddSlashes(pg_result($resaco,$iresaco,'k140_tabrecjm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3366,18931,'','".AddSlashes(pg_result($resaco,$iresaco,'k140_multa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3366,18932,'','".AddSlashes(pg_result($resaco,$iresaco,'k140_faixa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tabrecjmmulta
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k140_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k140_sequencial = $k140_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tabrecjmmulta nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k140_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tabrecjmmulta nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k140_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k140_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:tabrecjmmulta";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k140_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabrecjmmulta ";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrecjmmulta.k140_tabrecjm";
     $sql .= "      inner join inflan  on  inflan.i01_codigo = tabrecjm.k02_corr";
     $sql2 = "";
     if($dbwhere==""){
       if($k140_sequencial!=null ){
         $sql2 .= " where tabrecjmmulta.k140_sequencial = $k140_sequencial "; 
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
   function sql_query_file ( $k140_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabrecjmmulta ";
     $sql2 = "";
     if($dbwhere==""){
       if($k140_sequencial!=null ){
         $sql2 .= " where tabrecjmmulta.k140_sequencial = $k140_sequencial "; 
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