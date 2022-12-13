<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE ppaestimativadespesa
class cl_ppaestimativadespesa { 
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
   var $o07_sequencial = 0; 
   var $o07_ppaestimativa = 0; 
   var $o07_coddot = 0; 
   var $o07_anousu = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o07_sequencial = int4 = Código Sequencial 
                 o07_ppaestimativa = int4 = Estimativa 
                 o07_coddot = int4 = Dotação 
                 o07_anousu = int4 = Ano do Exercicio 
                 ";
   //funcao construtor da classe 
   function cl_ppaestimativadespesa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ppaestimativadespesa"); 
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
       $this->o07_sequencial = ($this->o07_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o07_sequencial"]:$this->o07_sequencial);
       $this->o07_ppaestimativa = ($this->o07_ppaestimativa == ""?@$GLOBALS["HTTP_POST_VARS"]["o07_ppaestimativa"]:$this->o07_ppaestimativa);
       $this->o07_coddot = ($this->o07_coddot == ""?@$GLOBALS["HTTP_POST_VARS"]["o07_coddot"]:$this->o07_coddot);
       $this->o07_anousu = ($this->o07_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o07_anousu"]:$this->o07_anousu);
     }else{
       $this->o07_sequencial = ($this->o07_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o07_sequencial"]:$this->o07_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o07_sequencial){ 
      $this->atualizacampos();
     if($this->o07_ppaestimativa == null ){ 
       $this->erro_sql = " Campo Estimativa nao Informado.";
       $this->erro_campo = "o07_ppaestimativa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o07_coddot == null ){ 
       $this->erro_sql = " Campo Dotação nao Informado.";
       $this->erro_campo = "o07_coddot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o07_anousu == null ){ 
       $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
       $this->erro_campo = "o07_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o07_sequencial == "" || $o07_sequencial == null ){
       $result = db_query("select nextval('ppaestimativadespesa_o07_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ppaestimativadespesa_o07_sequencial_seq do campo: o07_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o07_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from ppaestimativadespesa_o07_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o07_sequencial)){
         $this->erro_sql = " Campo o07_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o07_sequencial = $o07_sequencial; 
       }
     }
     if(($this->o07_sequencial == null) || ($this->o07_sequencial == "") ){ 
       $this->erro_sql = " Campo o07_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ppaestimativadespesa(
                                       o07_sequencial 
                                      ,o07_ppaestimativa 
                                      ,o07_coddot 
                                      ,o07_anousu 
                       )
                values (
                                $this->o07_sequencial 
                               ,$this->o07_ppaestimativa 
                               ,$this->o07_coddot 
                               ,$this->o07_anousu 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Despesas da estimativa ($this->o07_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Despesas da estimativa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Despesas da estimativa ($this->o07_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o07_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o07_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13616,'$this->o07_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2385,13616,'','".AddSlashes(pg_result($resaco,0,'o07_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2385,13617,'','".AddSlashes(pg_result($resaco,0,'o07_ppaestimativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2385,13618,'','".AddSlashes(pg_result($resaco,0,'o07_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2385,2679,'','".AddSlashes(pg_result($resaco,0,'o07_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o07_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update ppaestimativadespesa set ";
     $virgula = "";
     if(trim($this->o07_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o07_sequencial"])){ 
       $sql  .= $virgula." o07_sequencial = $this->o07_sequencial ";
       $virgula = ",";
       if(trim($this->o07_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o07_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o07_ppaestimativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o07_ppaestimativa"])){ 
       $sql  .= $virgula." o07_ppaestimativa = $this->o07_ppaestimativa ";
       $virgula = ",";
       if(trim($this->o07_ppaestimativa) == null ){ 
         $this->erro_sql = " Campo Estimativa nao Informado.";
         $this->erro_campo = "o07_ppaestimativa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o07_coddot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o07_coddot"])){ 
       $sql  .= $virgula." o07_coddot = $this->o07_coddot ";
       $virgula = ",";
       if(trim($this->o07_coddot) == null ){ 
         $this->erro_sql = " Campo Dotação nao Informado.";
         $this->erro_campo = "o07_coddot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o07_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o07_anousu"])){ 
       $sql  .= $virgula." o07_anousu = $this->o07_anousu ";
       $virgula = ",";
       if(trim($this->o07_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "o07_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o07_sequencial!=null){
       $sql .= " o07_sequencial = $this->o07_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o07_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13616,'$this->o07_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o07_sequencial"]) || $this->o07_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2385,13616,'".AddSlashes(pg_result($resaco,$conresaco,'o07_sequencial'))."','$this->o07_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o07_ppaestimativa"]) || $this->o07_ppaestimativa != "")
           $resac = db_query("insert into db_acount values($acount,2385,13617,'".AddSlashes(pg_result($resaco,$conresaco,'o07_ppaestimativa'))."','$this->o07_ppaestimativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o07_coddot"]) || $this->o07_coddot != "")
           $resac = db_query("insert into db_acount values($acount,2385,13618,'".AddSlashes(pg_result($resaco,$conresaco,'o07_coddot'))."','$this->o07_coddot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o07_anousu"]) || $this->o07_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2385,2679,'".AddSlashes(pg_result($resaco,$conresaco,'o07_anousu'))."','$this->o07_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Despesas da estimativa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o07_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Despesas da estimativa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o07_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o07_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13616,'$o07_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2385,13616,'','".AddSlashes(pg_result($resaco,$iresaco,'o07_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2385,13617,'','".AddSlashes(pg_result($resaco,$iresaco,'o07_ppaestimativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2385,13618,'','".AddSlashes(pg_result($resaco,$iresaco,'o07_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2385,2679,'','".AddSlashes(pg_result($resaco,$iresaco,'o07_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ppaestimativadespesa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o07_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o07_sequencial = $o07_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Despesas da estimativa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o07_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Despesas da estimativa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o07_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:ppaestimativadespesa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o07_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ppaestimativadespesa ";
     $sql .= "      inner join ppaestimativa  on  ppaestimativa.o05_sequencial = ppaestimativadespesa.o07_ppaestimativa";
     $sql .= "      inner join ppadotacao  on  ppadotacao.o08_sequencial = ppaestimativadespesa.o07_coddot";
     $sql .= "      inner join ppaversao  on  ppaversao.o119_sequencial = ppaestimativa.o05_ppaversao";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = ppadotacao.o08_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = ppadotacao.o08_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = ppadotacao.o08_ano and  orcprograma.o54_programa = ppadotacao.o08_programa";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = ppadotacao.o08_elemento and  orcelemento.o56_anousu = ppadotacao.o08_ano";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = ppadotacao.o08_ano and  orcprojativ.o55_projativ = ppadotacao.o08_projativ";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = ppadotacao.o08_ano and  orcorgao.o40_orgao = ppadotacao.o08_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = ppadotacao.o08_ano and  orcunidade.o41_orgao = ppadotacao.o08_orgao and  orcunidade.o41_unidade = ppadotacao.o08_unidade";
     $sql .= "      left  join ppasubtitulolocalizadorgasto  on  ppasubtitulolocalizadorgasto.o11_sequencial = ppadotacao.o08_localizadorgastos";
     $sql2 = "";
     if($dbwhere==""){
       if($o07_sequencial!=null ){
         $sql2 .= " where ppaestimativadespesa.o07_sequencial = $o07_sequencial "; 
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
   function sql_query_file ( $o07_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ppaestimativadespesa ";
     $sql2 = "";
     if($dbwhere==""){
       if($o07_sequencial!=null ){
         $sql2 .= " where ppaestimativadespesa.o07_sequencial = $o07_sequencial "; 
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
   function sql_query_conplano ( $o07_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 

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
     $sql .= " from ppaestimativadespesa ";
     $sql .= "      inner join ppadotacao     on  o07_coddot = o08_sequencial";
     $sql .= "      inner join ppaestimativa  on  ppaestimativa.o05_sequencial = ppaestimativadespesa.o07_ppaestimativa";
     $sql .= "      left join ppadotacaoorcdotacao on o19_ppadotacao = o08_sequencial";
     $sql .= "      inner join orcelemento     on o08_elemento   = o56_codele    and o56_anousu = o08_ano";
     $sql .= "      inner join conplano        on c60_codcon     = o56_codele    and o56_anousu = c60_anousu";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = ppadotacao.o08_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = ppadotacao.o08_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = ppadotacao.o08_ano and  orcprograma.o54_programa = ppadotacao.o08_programa";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = ppadotacao.o08_ano and  orcprojativ.o55_projativ = ppadotacao.o08_projativ";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = ppadotacao.o08_ano and  orcorgao.o40_orgao = ppadotacao.o08_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = ppadotacao.o08_ano and  orcunidade.o41_orgao = ppadotacao.o08_orgao and  orcunidade.o41_unidade = ppadotacao.o08_unidade";
     $sql .= "      inner join orcproduto  on  orcproduto.o22_codproduto = orcprojativ.o55_orcproduto";
     $sql .= "      inner join orctiporec  on   o15_codigo = o08_recurso";
     $sql .= "      left  join ppasubtitulolocalizadorgasto  on  ppasubtitulolocalizadorgasto.o11_sequencial = ppadotacao.o08_localizadorgastos";
     $sql .= "      inner join ppaversao on o05_ppaversao = o119_sequencial";
     $sql .= "      inner join ppalei   on o01_sequencial = o119_ppalei";
     $sql2 = "";
     if($dbwhere==""){
       if($o07_sequencial!=null ){
         $sql2 .= " where ppaestimativadespesa.o07_sequencial = $o07_sequencial "; 
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
     $sql = analiseQueryPlanoOrcamento($sql);
     return $sql;
  }
  
  function sql_query_estimativadespesa ( $o07_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
  
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
   $sql .= " from ppaestimativadespesa ";
   $sql .= "      inner join ppadotacao     on  o07_coddot = o08_sequencial";
   $sql .= "      inner join ppaestimativa  on  ppaestimativa.o05_sequencial = ppaestimativadespesa.o07_ppaestimativa";
   $sql .= "      left join ppadotacaoorcdotacao on o19_ppadotacao = o08_sequencial";
   $sql .= "      inner join orcelemento     on o08_elemento   = o56_codele    and o56_anousu = o08_ano";
   $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = ppadotacao.o08_funcao";
   $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = ppadotacao.o08_subfuncao";
   $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = ppadotacao.o08_ano and  orcprograma.o54_programa = ppadotacao.o08_programa";
   $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = ppadotacao.o08_ano and  orcprojativ.o55_projativ = ppadotacao.o08_projativ";
   $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = ppadotacao.o08_ano and  orcorgao.o40_orgao = ppadotacao.o08_orgao";
   $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = ppadotacao.o08_ano and  orcunidade.o41_orgao = ppadotacao.o08_orgao and  orcunidade.o41_unidade = ppadotacao.o08_unidade";
   $sql .= "      inner join orcproduto  on  orcproduto.o22_codproduto = orcprojativ.o55_orcproduto";
   $sql .= "      inner join orctiporec  on   o15_codigo = o08_recurso";
   $sql .= "      left  join ppasubtitulolocalizadorgasto  on  ppasubtitulolocalizadorgasto.o11_sequencial = ppadotacao.o08_localizadorgastos";
   $sql .= "      inner join ppaversao on o05_ppaversao = o119_sequencial";
   $sql .= "      inner join ppalei   on o01_sequencial = o119_ppalei";
   $sql2 = "";
   if($dbwhere==""){
    if($o07_sequencial!=null ){
     $sql2 .= " where ppaestimativadespesa.o07_sequencial = $o07_sequencial ";
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
      $sql = analiseQueryPlanoOrcamento($sql);
      return $sql;
  }
  
  
function sql_query_dotacao ( $o07_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 

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
     $sql .= " from ppaestimativadespesa ";
     $sql .= "      inner join ppadotacao     on  o07_coddot = o08_sequencial";
     $sql .= "      inner join ppaestimativa  on  ppaestimativa.o05_sequencial = ppaestimativadespesa.o07_ppaestimativa";
     $sql .= "      left join ppadotacaoorcdotacao on o19_ppadotacao = o08_sequencial";
     $sql .= "      left join orcelemento     on o08_elemento   = o56_codele    and o56_anousu = o08_ano";
     $sql .= "      left join conplano        on c60_codcon     = o56_codele    and o56_anousu = c60_anousu";
     $sql .= "      left join orcfuncao  on  orcfuncao.o52_funcao = ppadotacao.o08_funcao";
     $sql .= "      left join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = ppadotacao.o08_subfuncao";
     $sql .= "      left join orcprograma  on  orcprograma.o54_anousu = ppadotacao.o08_ano and  orcprograma.o54_programa = ppadotacao.o08_programa";
     $sql .= "      left join orcprojativ  on  orcprojativ.o55_anousu = ppadotacao.o08_ano and  orcprojativ.o55_projativ = ppadotacao.o08_projativ";
     $sql .= "      left join orcorgao  on  orcorgao.o40_anousu = ppadotacao.o08_ano and  orcorgao.o40_orgao = ppadotacao.o08_orgao";
     $sql .= "      left join orcunidade  on  orcunidade.o41_anousu = ppadotacao.o08_ano and  orcunidade.o41_orgao = ppadotacao.o08_orgao and  orcunidade.o41_unidade = ppadotacao.o08_unidade";
     $sql .= "      left join orcproduto  on  orcproduto.o22_codproduto = orcprojativ.o55_orcproduto";
     $sql .= "      left join orctiporec  on   o15_codigo = o08_recurso";
     $sql .= "      left  join ppasubtitulolocalizadorgasto  on  ppasubtitulolocalizadorgasto.o11_sequencial = ppadotacao.o08_localizadorgastos";
     $sql .= "      inner join ppaversao on o05_ppaversao = o119_sequencial";
     $sql .= "      inner join ppalei   on o01_sequencial = o119_ppalei";
     $sql2 = "";
     if($dbwhere==""){
       if($o07_sequencial!=null ){
         $sql2 .= " where ppaestimativadespesa.o07_sequencial = $o07_sequencial "; 
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