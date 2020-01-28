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

//MODULO: contabilidade
//CLASSE DA ENTIDADE pcfornetipoidentificacaocredorgenerica
class cl_pcfornetipoidentificacaocredorgenerica { 
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
   var $c26_sequencial = 0; 
   var $c26_tipoidentificacaocredorgenerica = 0; 
   var $c26_pcforne = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c26_sequencial = int4 = Sequencial 
                 c26_tipoidentificacaocredorgenerica = int4 = Identificação do Credor Genérica 
                 c26_pcforne = int4 = Fornecedor 
                 ";
   //funcao construtor da classe 
   function cl_pcfornetipoidentificacaocredorgenerica() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcfornetipoidentificacaocredorgenerica"); 
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
       $this->c26_sequencial = ($this->c26_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c26_sequencial"]:$this->c26_sequencial);
       $this->c26_tipoidentificacaocredorgenerica = ($this->c26_tipoidentificacaocredorgenerica == ""?@$GLOBALS["HTTP_POST_VARS"]["c26_tipoidentificacaocredorgenerica"]:$this->c26_tipoidentificacaocredorgenerica);
       $this->c26_pcforne = ($this->c26_pcforne == ""?@$GLOBALS["HTTP_POST_VARS"]["c26_pcforne"]:$this->c26_pcforne);
     }else{
       $this->c26_sequencial = ($this->c26_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c26_sequencial"]:$this->c26_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c26_sequencial){ 
      $this->atualizacampos();
     if($this->c26_tipoidentificacaocredorgenerica == null ){ 
       $this->erro_sql = " Campo Identificação do Credor Genérica nao Informado.";
       $this->erro_campo = "c26_tipoidentificacaocredorgenerica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c26_pcforne == null ){ 
       $this->erro_sql = " Campo Fornecedor nao Informado.";
       $this->erro_campo = "c26_pcforne";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c26_sequencial == "" || $c26_sequencial == null ){
       $result = db_query("select nextval('pcfornetipoidentificacaocredorgenerica_c26_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pcfornetipoidentificacaocredorgenerica_c26_sequencial_seq do campo: c26_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c26_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pcfornetipoidentificacaocredorgenerica_c26_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c26_sequencial)){
         $this->erro_sql = " Campo c26_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c26_sequencial = $c26_sequencial; 
       }
     }
     if(($this->c26_sequencial == null) || ($this->c26_sequencial == "") ){ 
       $this->erro_sql = " Campo c26_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcfornetipoidentificacaocredorgenerica(
                                       c26_sequencial 
                                      ,c26_tipoidentificacaocredorgenerica 
                                      ,c26_pcforne 
                       )
                values (
                                $this->c26_sequencial 
                               ,$this->c26_tipoidentificacaocredorgenerica 
                               ,$this->c26_pcforne 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vinculo da Inscrição Genérica e do Fornecedor ($this->c26_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vinculo da Inscrição Genérica e do Fornecedor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vinculo da Inscrição Genérica e do Fornecedor ($this->c26_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c26_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c26_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18763,'$this->c26_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3326,18763,'','".AddSlashes(pg_result($resaco,0,'c26_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3326,18764,'','".AddSlashes(pg_result($resaco,0,'c26_tipoidentificacaocredorgenerica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3326,18765,'','".AddSlashes(pg_result($resaco,0,'c26_pcforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c26_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update pcfornetipoidentificacaocredorgenerica set ";
     $virgula = "";
     if(trim($this->c26_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c26_sequencial"])){ 
       $sql  .= $virgula." c26_sequencial = $this->c26_sequencial ";
       $virgula = ",";
       if(trim($this->c26_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "c26_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c26_tipoidentificacaocredorgenerica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c26_tipoidentificacaocredorgenerica"])){ 
       $sql  .= $virgula." c26_tipoidentificacaocredorgenerica = $this->c26_tipoidentificacaocredorgenerica ";
       $virgula = ",";
       if(trim($this->c26_tipoidentificacaocredorgenerica) == null ){ 
         $this->erro_sql = " Campo Identificação do Credor Genérica nao Informado.";
         $this->erro_campo = "c26_tipoidentificacaocredorgenerica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c26_pcforne)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c26_pcforne"])){ 
       $sql  .= $virgula." c26_pcforne = $this->c26_pcforne ";
       $virgula = ",";
       if(trim($this->c26_pcforne) == null ){ 
         $this->erro_sql = " Campo Fornecedor nao Informado.";
         $this->erro_campo = "c26_pcforne";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c26_sequencial!=null){
       $sql .= " c26_sequencial = $this->c26_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c26_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18763,'$this->c26_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c26_sequencial"]) || $this->c26_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3326,18763,'".AddSlashes(pg_result($resaco,$conresaco,'c26_sequencial'))."','$this->c26_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c26_tipoidentificacaocredorgenerica"]) || $this->c26_tipoidentificacaocredorgenerica != "")
           $resac = db_query("insert into db_acount values($acount,3326,18764,'".AddSlashes(pg_result($resaco,$conresaco,'c26_tipoidentificacaocredorgenerica'))."','$this->c26_tipoidentificacaocredorgenerica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c26_pcforne"]) || $this->c26_pcforne != "")
           $resac = db_query("insert into db_acount values($acount,3326,18765,'".AddSlashes(pg_result($resaco,$conresaco,'c26_pcforne'))."','$this->c26_pcforne',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculo da Inscrição Genérica e do Fornecedor nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c26_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vinculo da Inscrição Genérica e do Fornecedor nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c26_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c26_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c26_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c26_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18763,'$c26_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3326,18763,'','".AddSlashes(pg_result($resaco,$iresaco,'c26_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3326,18764,'','".AddSlashes(pg_result($resaco,$iresaco,'c26_tipoidentificacaocredorgenerica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3326,18765,'','".AddSlashes(pg_result($resaco,$iresaco,'c26_pcforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcfornetipoidentificacaocredorgenerica
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c26_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c26_sequencial = $c26_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculo da Inscrição Genérica e do Fornecedor nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c26_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vinculo da Inscrição Genérica e do Fornecedor nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c26_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c26_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcfornetipoidentificacaocredorgenerica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c26_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcfornetipoidentificacaocredorgenerica ";
     $sql .= "      inner join pcforne  on  pcforne.pc60_numcgm = pcfornetipoidentificacaocredorgenerica.c26_pcforne";
     $sql .= "      inner join tipoidentificacaocredorgenerica  on  tipoidentificacaocredorgenerica.c25_sequencial = pcfornetipoidentificacaocredorgenerica.c26_tipoidentificacaocredorgenerica";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pcforne.pc60_numcgm";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pcforne.pc60_usuario";
     $sql .= "      inner join tipoidentificacaocredor  as a on   a.c24_sequencial = tipoidentificacaocredorgenerica.c25_tipoidentificacaocredor";
     $sql2 = "";
     if($dbwhere==""){
       if($c26_sequencial!=null ){
         $sql2 .= " where pcfornetipoidentificacaocredorgenerica.c26_sequencial = $c26_sequencial "; 
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
   function sql_query_file ( $c26_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcfornetipoidentificacaocredorgenerica ";
     $sql2 = "";
     if($dbwhere==""){
       if($c26_sequencial!=null ){
         $sql2 .= " where pcfornetipoidentificacaocredorgenerica.c26_sequencial = $c26_sequencial "; 
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