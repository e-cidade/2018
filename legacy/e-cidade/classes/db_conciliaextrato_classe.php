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

//MODULO: Caixa
//CLASSE DA ENTIDADE conciliaextrato
class cl_conciliaextrato { 
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
   var $k87_sequencial = 0; 
   var $k87_conciliaitem = 0; 
   var $k87_extratolinha = 0; 
   var $k87_conciliaorigem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k87_sequencial = int4 = Codigo sequencial 
                 k87_conciliaitem = int4 = Item da conciliação 
                 k87_extratolinha = int8 = Linha do extrato 
                 k87_conciliaorigem = int4 = Origem 
                 ";
   //funcao construtor da classe 
   function cl_conciliaextrato() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conciliaextrato"); 
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
       $this->k87_sequencial = ($this->k87_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k87_sequencial"]:$this->k87_sequencial);
       $this->k87_conciliaitem = ($this->k87_conciliaitem == ""?@$GLOBALS["HTTP_POST_VARS"]["k87_conciliaitem"]:$this->k87_conciliaitem);
       $this->k87_extratolinha = ($this->k87_extratolinha == ""?@$GLOBALS["HTTP_POST_VARS"]["k87_extratolinha"]:$this->k87_extratolinha);
       $this->k87_conciliaorigem = ($this->k87_conciliaorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["k87_conciliaorigem"]:$this->k87_conciliaorigem);
     }else{
       $this->k87_sequencial = ($this->k87_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k87_sequencial"]:$this->k87_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k87_sequencial){ 
      $this->atualizacampos();
     if($this->k87_conciliaitem == null ){ 
       $this->erro_sql = " Campo Item da conciliação nao Informado.";
       $this->erro_campo = "k87_conciliaitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k87_extratolinha == null ){ 
       $this->erro_sql = " Campo Linha do extrato nao Informado.";
       $this->erro_campo = "k87_extratolinha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k87_conciliaorigem == null ){ 
       $this->erro_sql = " Campo Origem nao Informado.";
       $this->erro_campo = "k87_conciliaorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k87_sequencial == "" || $k87_sequencial == null ){
       $result = db_query("select nextval('conciliaextrato_k87_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conciliaextrato_k87_sequencial_seq do campo: k87_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k87_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from conciliaextrato_k87_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k87_sequencial)){
         $this->erro_sql = " Campo k87_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k87_sequencial = $k87_sequencial; 
       }
     }
     if(($this->k87_sequencial == null) || ($this->k87_sequencial == "") ){ 
       $this->erro_sql = " Campo k87_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conciliaextrato(
                                       k87_sequencial 
                                      ,k87_conciliaitem 
                                      ,k87_extratolinha 
                                      ,k87_conciliaorigem 
                       )
                values (
                                $this->k87_sequencial 
                               ,$this->k87_conciliaitem 
                               ,$this->k87_extratolinha 
                               ,$this->k87_conciliaorigem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ligação do item com extrato ($this->k87_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ligação do item com extrato já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ligação do item com extrato ($this->k87_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k87_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k87_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10091,'$this->k87_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1735,10091,'','".AddSlashes(pg_result($resaco,0,'k87_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1735,10092,'','".AddSlashes(pg_result($resaco,0,'k87_conciliaitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1735,10093,'','".AddSlashes(pg_result($resaco,0,'k87_extratolinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1735,10153,'','".AddSlashes(pg_result($resaco,0,'k87_conciliaorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k87_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update conciliaextrato set ";
     $virgula = "";
     if(trim($this->k87_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k87_sequencial"])){ 
       $sql  .= $virgula." k87_sequencial = $this->k87_sequencial ";
       $virgula = ",";
       if(trim($this->k87_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "k87_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k87_conciliaitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k87_conciliaitem"])){ 
       $sql  .= $virgula." k87_conciliaitem = $this->k87_conciliaitem ";
       $virgula = ",";
       if(trim($this->k87_conciliaitem) == null ){ 
         $this->erro_sql = " Campo Item da conciliação nao Informado.";
         $this->erro_campo = "k87_conciliaitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k87_extratolinha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k87_extratolinha"])){ 
       $sql  .= $virgula." k87_extratolinha = $this->k87_extratolinha ";
       $virgula = ",";
       if(trim($this->k87_extratolinha) == null ){ 
         $this->erro_sql = " Campo Linha do extrato nao Informado.";
         $this->erro_campo = "k87_extratolinha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k87_conciliaorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k87_conciliaorigem"])){ 
       $sql  .= $virgula." k87_conciliaorigem = $this->k87_conciliaorigem ";
       $virgula = ",";
       if(trim($this->k87_conciliaorigem) == null ){ 
         $this->erro_sql = " Campo Origem nao Informado.";
         $this->erro_campo = "k87_conciliaorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k87_sequencial!=null){
       $sql .= " k87_sequencial = $this->k87_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k87_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10091,'$this->k87_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k87_sequencial"]) || $this->k87_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,1735,10091,'".AddSlashes(pg_result($resaco,$conresaco,'k87_sequencial'))."','$this->k87_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k87_conciliaitem"]) || $this->k87_conciliaitem != "")
           $resac = db_query("insert into db_acount values($acount,1735,10092,'".AddSlashes(pg_result($resaco,$conresaco,'k87_conciliaitem'))."','$this->k87_conciliaitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k87_extratolinha"]) || $this->k87_extratolinha != "")
           $resac = db_query("insert into db_acount values($acount,1735,10093,'".AddSlashes(pg_result($resaco,$conresaco,'k87_extratolinha'))."','$this->k87_extratolinha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k87_conciliaorigem"]) || $this->k87_conciliaorigem != "")
           $resac = db_query("insert into db_acount values($acount,1735,10153,'".AddSlashes(pg_result($resaco,$conresaco,'k87_conciliaorigem'))."','$this->k87_conciliaorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ligação do item com extrato nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k87_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ligação do item com extrato nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k87_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k87_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k87_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k87_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10091,'$k87_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1735,10091,'','".AddSlashes(pg_result($resaco,$iresaco,'k87_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1735,10092,'','".AddSlashes(pg_result($resaco,$iresaco,'k87_conciliaitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1735,10093,'','".AddSlashes(pg_result($resaco,$iresaco,'k87_extratolinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1735,10153,'','".AddSlashes(pg_result($resaco,$iresaco,'k87_conciliaorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conciliaextrato
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k87_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k87_sequencial = $k87_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ligação do item com extrato nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k87_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ligação do item com extrato nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k87_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k87_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:conciliaextrato";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k87_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conciliaextrato ";
     $sql .= "      inner join conciliaitem  on  conciliaitem.k83_sequencial = conciliaextrato.k87_conciliaitem";
     $sql .= "      inner join extratolinha  on  extratolinha.k86_sequencial = conciliaextrato.k87_extratolinha";
     $sql .= "      inner join conciliaorigem  on  conciliaorigem.k96_sequencial = conciliaextrato.k87_conciliaorigem";
     $sql .= "      inner join conciliatipo  on  conciliatipo.k65_sequencial = conciliaitem.k83_conciliatipo";
     $sql .= "      inner join concilia  on  concilia.k68_sequencial = conciliaitem.k83_concilia";
     $sql .= "      inner join bancoshistmov  on  bancoshistmov.k66_sequencial = extratolinha.k86_bancohistmov";
     $sql .= "      inner join extrato  as a on   a.k85_sequencial = extratolinha.k86_extrato";
     $sql .= "      inner join contabancaria  on  contabancaria.db83_sequencial = extratolinha.k86_contabancaria";
     $sql2 = "";
     if($dbwhere==""){
       if($k87_sequencial!=null ){
         $sql2 .= " where conciliaextrato.k87_sequencial = $k87_sequencial "; 
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
   function sql_query_file ( $k87_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conciliaextrato ";
     $sql2 = "";
     if($dbwhere==""){
       if($k87_sequencial!=null ){
         $sql2 .= " where conciliaextrato.k87_sequencial = $k87_sequencial "; 
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