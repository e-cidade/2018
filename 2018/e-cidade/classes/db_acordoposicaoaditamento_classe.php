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

//MODULO: Acordos
//CLASSE DA ENTIDADE acordoposicaoaditamento
class cl_acordoposicaoaditamento { 
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
   var $ac35_sequencial = 0; 
   var $ac35_valor = 0; 
   var $ac35_acordoposicao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac35_sequencial = int4 = Código Sequencial 
                 ac35_valor = float8 = Valor do Aditamento 
                 ac35_acordoposicao = int4 = Código da posicao 
                 ";
   //funcao construtor da classe 
   function cl_acordoposicaoaditamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordoposicaoaditamento"); 
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
       $this->ac35_sequencial = ($this->ac35_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac35_sequencial"]:$this->ac35_sequencial);
       $this->ac35_valor = ($this->ac35_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["ac35_valor"]:$this->ac35_valor);
       $this->ac35_acordoposicao = ($this->ac35_acordoposicao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac35_acordoposicao"]:$this->ac35_acordoposicao);
     }else{
       $this->ac35_sequencial = ($this->ac35_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac35_sequencial"]:$this->ac35_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac35_sequencial){ 
      $this->atualizacampos();
     if($this->ac35_valor == null ){ 
       $this->ac35_valor = "0";
     }
     if($this->ac35_acordoposicao == null ){ 
       $this->erro_sql = " Campo Código da posicao nao Informado.";
       $this->erro_campo = "ac35_acordoposicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac35_sequencial == "" || $ac35_sequencial == null ){
       $result = db_query("select nextval('acordoposicaoaditamento_ac35_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordoposicaoaditamento_ac35_sequencial_seq do campo: ac35_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac35_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordoposicaoaditamento_ac35_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac35_sequencial)){
         $this->erro_sql = " Campo ac35_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac35_sequencial = $ac35_sequencial; 
       }
     }
     if(($this->ac35_sequencial == null) || ($this->ac35_sequencial == "") ){ 
       $this->erro_sql = " Campo ac35_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordoposicaoaditamento(
                                       ac35_sequencial 
                                      ,ac35_valor 
                                      ,ac35_acordoposicao 
                       )
                values (
                                $this->ac35_sequencial 
                               ,$this->ac35_valor 
                               ,$this->ac35_acordoposicao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Saldos dos Aditamentos ($this->ac35_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Saldos dos Aditamentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Saldos dos Aditamentos ($this->ac35_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac35_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac35_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17200,'$this->ac35_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3041,17200,'','".AddSlashes(pg_result($resaco,0,'ac35_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3041,17201,'','".AddSlashes(pg_result($resaco,0,'ac35_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3041,17202,'','".AddSlashes(pg_result($resaco,0,'ac35_acordoposicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac35_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordoposicaoaditamento set ";
     $virgula = "";
     if(trim($this->ac35_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac35_sequencial"])){ 
       $sql  .= $virgula." ac35_sequencial = $this->ac35_sequencial ";
       $virgula = ",";
       if(trim($this->ac35_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "ac35_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac35_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac35_valor"])){ 
        if(trim($this->ac35_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ac35_valor"])){ 
           $this->ac35_valor = "0" ; 
        } 
       $sql  .= $virgula." ac35_valor = $this->ac35_valor ";
       $virgula = ",";
     }
     if(trim($this->ac35_acordoposicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac35_acordoposicao"])){ 
       $sql  .= $virgula." ac35_acordoposicao = $this->ac35_acordoposicao ";
       $virgula = ",";
       if(trim($this->ac35_acordoposicao) == null ){ 
         $this->erro_sql = " Campo Código da posicao nao Informado.";
         $this->erro_campo = "ac35_acordoposicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ac35_sequencial!=null){
       $sql .= " ac35_sequencial = $this->ac35_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac35_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17200,'$this->ac35_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac35_sequencial"]) || $this->ac35_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3041,17200,'".AddSlashes(pg_result($resaco,$conresaco,'ac35_sequencial'))."','$this->ac35_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac35_valor"]) || $this->ac35_valor != "")
           $resac = db_query("insert into db_acount values($acount,3041,17201,'".AddSlashes(pg_result($resaco,$conresaco,'ac35_valor'))."','$this->ac35_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac35_acordoposicao"]) || $this->ac35_acordoposicao != "")
           $resac = db_query("insert into db_acount values($acount,3041,17202,'".AddSlashes(pg_result($resaco,$conresaco,'ac35_acordoposicao'))."','$this->ac35_acordoposicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Saldos dos Aditamentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac35_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Saldos dos Aditamentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac35_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac35_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac35_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac35_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17200,'$ac35_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3041,17200,'','".AddSlashes(pg_result($resaco,$iresaco,'ac35_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3041,17201,'','".AddSlashes(pg_result($resaco,$iresaco,'ac35_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3041,17202,'','".AddSlashes(pg_result($resaco,$iresaco,'ac35_acordoposicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordoposicaoaditamento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac35_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac35_sequencial = $ac35_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Saldos dos Aditamentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac35_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Saldos dos Aditamentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac35_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac35_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordoposicaoaditamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac35_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoposicaoaditamento ";
     $sql .= "      inner join acordoposicao  on  acordoposicao.ac26_sequencial = acordoposicaoaditamento.ac35_acordoposicao";
     $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordoposicao.ac26_acordo";
     $sql .= "      inner join acordoposicaotipo  on  acordoposicaotipo.ac27_sequencial = acordoposicao.ac26_acordoposicaotipo";
     $sql2 = "";
     if($dbwhere==""){
       if($ac35_sequencial!=null ){
         $sql2 .= " where acordoposicaoaditamento.ac35_sequencial = $ac35_sequencial "; 
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
   function sql_query_file ( $ac35_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoposicaoaditamento ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac35_sequencial!=null ){
         $sql2 .= " where acordoposicaoaditamento.ac35_sequencial = $ac35_sequencial "; 
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