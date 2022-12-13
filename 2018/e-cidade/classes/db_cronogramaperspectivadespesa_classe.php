<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: orcamento
//CLASSE DA ENTIDADE cronogramaperspectivadespesa
class cl_cronogramaperspectivadespesa { 
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
   var $o130_sequencial = 0; 
   var $o130_cronogramaperspectiva = 0; 
   var $o130_coddot = 0; 
   var $o130_anousu = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o130_sequencial = int4 = Código Sequencial 
                 o130_cronogramaperspectiva = int4 = Código da Perspectiva 
                 o130_coddot = int4 = Código da Dotação 
                 o130_anousu = int4 = Ano da Dotação 
                 ";
   //funcao construtor da classe 
   function cl_cronogramaperspectivadespesa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cronogramaperspectivadespesa"); 
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
       $this->o130_sequencial = ($this->o130_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o130_sequencial"]:$this->o130_sequencial);
       $this->o130_cronogramaperspectiva = ($this->o130_cronogramaperspectiva == ""?@$GLOBALS["HTTP_POST_VARS"]["o130_cronogramaperspectiva"]:$this->o130_cronogramaperspectiva);
       $this->o130_coddot = ($this->o130_coddot == ""?@$GLOBALS["HTTP_POST_VARS"]["o130_coddot"]:$this->o130_coddot);
       $this->o130_anousu = ($this->o130_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o130_anousu"]:$this->o130_anousu);
     }else{
       $this->o130_sequencial = ($this->o130_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o130_sequencial"]:$this->o130_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o130_sequencial){ 
      $this->atualizacampos();
     if($this->o130_cronogramaperspectiva == null ){ 
       $this->erro_sql = " Campo Código da Perspectiva nao Informado.";
       $this->erro_campo = "o130_cronogramaperspectiva";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o130_coddot == null ){ 
       $this->erro_sql = " Campo Código da Dotação nao Informado.";
       $this->erro_campo = "o130_coddot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o130_anousu == null ){ 
       $this->erro_sql = " Campo Ano da Dotação nao Informado.";
       $this->erro_campo = "o130_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o130_sequencial == "" || $o130_sequencial == null ){
       $result = db_query("select nextval('cronogramaperspectivadespesa_o130_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cronogramaperspectivadespesa_o130_sequencial_seq do campo: o130_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o130_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cronogramaperspectivadespesa_o130_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o130_sequencial)){
         $this->erro_sql = " Campo o130_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o130_sequencial = $o130_sequencial; 
       }
     }
     if(($this->o130_sequencial == null) || ($this->o130_sequencial == "") ){ 
       $this->erro_sql = " Campo o130_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cronogramaperspectivadespesa(
                                       o130_sequencial 
                                      ,o130_cronogramaperspectiva 
                                      ,o130_coddot 
                                      ,o130_anousu 
                       )
                values (
                                $this->o130_sequencial 
                               ,$this->o130_cronogramaperspectiva 
                               ,$this->o130_coddot 
                               ,$this->o130_anousu 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Despesas do Cronograma ($this->o130_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Despesas do Cronograma já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Despesas do Cronograma ($this->o130_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o130_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o130_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15014,'$this->o130_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2639,15014,'','".AddSlashes(pg_result($resaco,0,'o130_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2639,15015,'','".AddSlashes(pg_result($resaco,0,'o130_cronogramaperspectiva'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2639,15016,'','".AddSlashes(pg_result($resaco,0,'o130_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2639,15017,'','".AddSlashes(pg_result($resaco,0,'o130_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o130_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cronogramaperspectivadespesa set ";
     $virgula = "";
     if(trim($this->o130_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o130_sequencial"])){ 
       $sql  .= $virgula." o130_sequencial = $this->o130_sequencial ";
       $virgula = ",";
       if(trim($this->o130_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o130_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o130_cronogramaperspectiva)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o130_cronogramaperspectiva"])){ 
       $sql  .= $virgula." o130_cronogramaperspectiva = $this->o130_cronogramaperspectiva ";
       $virgula = ",";
       if(trim($this->o130_cronogramaperspectiva) == null ){ 
         $this->erro_sql = " Campo Código da Perspectiva nao Informado.";
         $this->erro_campo = "o130_cronogramaperspectiva";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o130_coddot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o130_coddot"])){ 
       $sql  .= $virgula." o130_coddot = $this->o130_coddot ";
       $virgula = ",";
       if(trim($this->o130_coddot) == null ){ 
         $this->erro_sql = " Campo Código da Dotação nao Informado.";
         $this->erro_campo = "o130_coddot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o130_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o130_anousu"])){ 
       $sql  .= $virgula." o130_anousu = $this->o130_anousu ";
       $virgula = ",";
       if(trim($this->o130_anousu) == null ){ 
         $this->erro_sql = " Campo Ano da Dotação nao Informado.";
         $this->erro_campo = "o130_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o130_sequencial!=null){
       $sql .= " o130_sequencial = $this->o130_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o130_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15014,'$this->o130_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o130_sequencial"]) || $this->o130_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2639,15014,'".AddSlashes(pg_result($resaco,$conresaco,'o130_sequencial'))."','$this->o130_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o130_cronogramaperspectiva"]) || $this->o130_cronogramaperspectiva != "")
           $resac = db_query("insert into db_acount values($acount,2639,15015,'".AddSlashes(pg_result($resaco,$conresaco,'o130_cronogramaperspectiva'))."','$this->o130_cronogramaperspectiva',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o130_coddot"]) || $this->o130_coddot != "")
           $resac = db_query("insert into db_acount values($acount,2639,15016,'".AddSlashes(pg_result($resaco,$conresaco,'o130_coddot'))."','$this->o130_coddot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o130_anousu"]) || $this->o130_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2639,15017,'".AddSlashes(pg_result($resaco,$conresaco,'o130_anousu'))."','$this->o130_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Despesas do Cronograma nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o130_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Despesas do Cronograma nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o130_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o130_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o130_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o130_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15014,'$o130_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2639,15014,'','".AddSlashes(pg_result($resaco,$iresaco,'o130_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2639,15015,'','".AddSlashes(pg_result($resaco,$iresaco,'o130_cronogramaperspectiva'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2639,15016,'','".AddSlashes(pg_result($resaco,$iresaco,'o130_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2639,15017,'','".AddSlashes(pg_result($resaco,$iresaco,'o130_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cronogramaperspectivadespesa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o130_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o130_sequencial = $o130_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Despesas do Cronograma nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o130_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Despesas do Cronograma nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o130_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o130_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cronogramaperspectivadespesa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o130_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cronogramaperspectivadespesa ";
     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = cronogramaperspectivadespesa.o130_anousu and  orcdotacao.o58_coddot = cronogramaperspectivadespesa.o130_coddot";
     $sql .= "      inner join cronogramaperspectiva  on  cronogramaperspectiva.o124_sequencial = cronogramaperspectivadespesa.o130_cronogramaperspectiva";
     $sql .= "      inner join db_config  on  db_config.codigo = orcdotacao.o58_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele and  orcelemento.o56_anousu = orcdotacao.o58_anousu";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
     $sql .= "      inner join ppasubtitulolocalizadorgasto  on  ppasubtitulolocalizadorgasto.o11_sequencial = orcdotacao.o58_localizadorgastos";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = cronogramaperspectiva.o124_idusuario";
     $sql .= "      inner join ppaversao  on  ppaversao.o119_sequencial = cronogramaperspectiva.o124_ppaversao";
     $sql2 = "";
     if($dbwhere==""){
       if($o130_sequencial!=null ){
         $sql2 .= " where cronogramaperspectivadespesa.o130_sequencial = $o130_sequencial "; 
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
   function sql_query_file ( $o130_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cronogramaperspectivadespesa ";
     $sql2 = "";
     if($dbwhere==""){
       if($o130_sequencial!=null ){
         $sql2 .= " where cronogramaperspectivadespesa.o130_sequencial = $o130_sequencial "; 
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
  
 function sql_query_dotacao ( $o130_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcdotacao";
     $sql .= "      left join cronogramaperspectivadespesa  on  orcdotacao.o58_anousu = cronogramaperspectivadespesa.o130_anousu and  orcdotacao.o58_coddot = cronogramaperspectivadespesa.o130_coddot";
     $sql .= "      inner join cronogramaperspectiva        on  cronogramaperspectiva.o124_sequencial = cronogramaperspectivadespesa.o130_cronogramaperspectiva";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele and  orcelemento.o56_anousu = orcdotacao.o58_anousu";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
     $sql .= "      inner join ppasubtitulolocalizadorgasto  on  ppasubtitulolocalizadorgasto.o11_sequencial = orcdotacao.o58_localizadorgastos";
     $sql2 = "";
     if($dbwhere==""){
       if($o130_sequencial!=null ){
         $sql2 .= " where cronogramaperspectivadespesa.o130_sequencial = $o130_sequencial "; 
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

  /**
   * Retorna todas as Metas de uma despesa
   *
   * @param $sCampos
   * @param $sWhere
   * @param $sOrder
   * @param $sGroup
   * @return string
   */
  public function sql_query_metas_despesa($sCampos = '*', $sWhere = null, $sOrder = null, $sGroup = null) {

    $sSqlMetas  = "select {$sCampos} ";
    $sSqlMetas .= " from cronogramaperspectivadespesa";
    $sSqlMetas .= "      left join cronogramametadespesa on o131_cronogramaperspectivadespesa = o130_sequencial";
    if (!empty($sWhere)) {
      $sSqlMetas .= " where {$sWhere}";
    }

    if (!empty($sOrder)) {
      $sSqlMetas .= " order by {$sOrder}";
    }

    if (!empty($sGroup)) {
      $sSqlMetas .= " group by {$sGroup}";
    }
    return $sSqlMetas;
  }

  /**
   * Retorna todas as dotacoes que estao fora do cronograma informado
   *
   * @param $sCampos
   * @param $sWhere
   * @param $sOrder
   * @param $sGroup
   * @return string
   */
  public function sql_query_dotacoes_fora_cronograma($sCampos = '*', $iCodigoCronograma, $sWhere = null, $sOrder = null, $sGroup = null) {

    $sSqlMetas  = "select {$sCampos} ";
    $sSqlMetas .= " from orcdotacao ";
    $sSqlMetas .= "      left join cronogramaperspectivadespesa on o130_coddot = o58_coddot";
    $sSqlMetas .= "                                            and o130_anousu = o58_anousu";
    $sSqlMetas .= "                                            and o130_cronogramaperspectiva = {$iCodigoCronograma}";
    if (!empty($sWhere)) {
      $sSqlMetas .= " where {$sWhere}";
    }

    if (!empty($sOrder)) {
      $sSqlMetas .= " order by {$sOrder}";
    }

    if (!empty($sGroup)) {
      $sSqlMetas .= " group by {$sGroup}";
    }
    return $sSqlMetas;
  }
}