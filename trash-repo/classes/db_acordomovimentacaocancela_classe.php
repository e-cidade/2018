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
//CLASSE DA ENTIDADE acordomovimentacaocancela
class cl_acordomovimentacaocancela { 
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
   var $ac25_sequencial = 0; 
   var $ac25_acordomovimentacao = 0; 
   var $ac25_acordomovimentacaocancela = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac25_sequencial = int4 = Codigo Sequencial 
                 ac25_acordomovimentacao = int4 = Movimento 
                 ac25_acordomovimentacaocancela = int4 = Movimento Cancelado 
                 ";
   //funcao construtor da classe 
   function cl_acordomovimentacaocancela() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordomovimentacaocancela"); 
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
       $this->ac25_sequencial = ($this->ac25_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac25_sequencial"]:$this->ac25_sequencial);
       $this->ac25_acordomovimentacao = ($this->ac25_acordomovimentacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac25_acordomovimentacao"]:$this->ac25_acordomovimentacao);
       $this->ac25_acordomovimentacaocancela = ($this->ac25_acordomovimentacaocancela == ""?@$GLOBALS["HTTP_POST_VARS"]["ac25_acordomovimentacaocancela"]:$this->ac25_acordomovimentacaocancela);
     }else{
       $this->ac25_sequencial = ($this->ac25_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac25_sequencial"]:$this->ac25_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac25_sequencial){ 
      $this->atualizacampos();
     if($this->ac25_acordomovimentacao == null ){ 
       $this->erro_sql = " Campo Movimento nao Informado.";
       $this->erro_campo = "ac25_acordomovimentacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac25_acordomovimentacaocancela == null ){ 
       $this->erro_sql = " Campo Movimento Cancelado nao Informado.";
       $this->erro_campo = "ac25_acordomovimentacaocancela";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac25_sequencial == "" || $ac25_sequencial == null ){
       $result = db_query("select nextval('acordomovimentacaocancela_ac25_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordomovimentacaocancela_ac25_sequencial_seq do campo: ac25_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac25_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordomovimentacaocancela_ac25_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac25_sequencial)){
         $this->erro_sql = " Campo ac25_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac25_sequencial = $ac25_sequencial; 
       }
     }
     if(($this->ac25_sequencial == null) || ($this->ac25_sequencial == "") ){ 
       $this->erro_sql = " Campo ac25_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordomovimentacaocancela(
                                       ac25_sequencial 
                                      ,ac25_acordomovimentacao 
                                      ,ac25_acordomovimentacaocancela 
                       )
                values (
                                $this->ac25_sequencial 
                               ,$this->ac25_acordomovimentacao 
                               ,$this->ac25_acordomovimentacaocancela 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Acordo Movimentação Cancela ($this->ac25_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Acordo Movimentação Cancela já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Acordo Movimentação Cancela ($this->ac25_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac25_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac25_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16591,'$this->ac25_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2914,16591,'','".AddSlashes(pg_result($resaco,0,'ac25_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2914,16592,'','".AddSlashes(pg_result($resaco,0,'ac25_acordomovimentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2914,16593,'','".AddSlashes(pg_result($resaco,0,'ac25_acordomovimentacaocancela'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac25_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordomovimentacaocancela set ";
     $virgula = "";
     if(trim($this->ac25_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac25_sequencial"])){ 
       $sql  .= $virgula." ac25_sequencial = $this->ac25_sequencial ";
       $virgula = ",";
       if(trim($this->ac25_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo Sequencial nao Informado.";
         $this->erro_campo = "ac25_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac25_acordomovimentacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac25_acordomovimentacao"])){ 
       $sql  .= $virgula." ac25_acordomovimentacao = $this->ac25_acordomovimentacao ";
       $virgula = ",";
       if(trim($this->ac25_acordomovimentacao) == null ){ 
         $this->erro_sql = " Campo Movimento nao Informado.";
         $this->erro_campo = "ac25_acordomovimentacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac25_acordomovimentacaocancela)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac25_acordomovimentacaocancela"])){ 
       $sql  .= $virgula." ac25_acordomovimentacaocancela = $this->ac25_acordomovimentacaocancela ";
       $virgula = ",";
       if(trim($this->ac25_acordomovimentacaocancela) == null ){ 
         $this->erro_sql = " Campo Movimento Cancelado nao Informado.";
         $this->erro_campo = "ac25_acordomovimentacaocancela";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ac25_sequencial!=null){
       $sql .= " ac25_sequencial = $this->ac25_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac25_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16591,'$this->ac25_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac25_sequencial"]) || $this->ac25_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2914,16591,'".AddSlashes(pg_result($resaco,$conresaco,'ac25_sequencial'))."','$this->ac25_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac25_acordomovimentacao"]) || $this->ac25_acordomovimentacao != "")
           $resac = db_query("insert into db_acount values($acount,2914,16592,'".AddSlashes(pg_result($resaco,$conresaco,'ac25_acordomovimentacao'))."','$this->ac25_acordomovimentacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac25_acordomovimentacaocancela"]) || $this->ac25_acordomovimentacaocancela != "")
           $resac = db_query("insert into db_acount values($acount,2914,16593,'".AddSlashes(pg_result($resaco,$conresaco,'ac25_acordomovimentacaocancela'))."','$this->ac25_acordomovimentacaocancela',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Movimentação Cancela nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac25_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Movimentação Cancela nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac25_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac25_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac25_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac25_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16591,'$ac25_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2914,16591,'','".AddSlashes(pg_result($resaco,$iresaco,'ac25_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2914,16592,'','".AddSlashes(pg_result($resaco,$iresaco,'ac25_acordomovimentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2914,16593,'','".AddSlashes(pg_result($resaco,$iresaco,'ac25_acordomovimentacaocancela'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordomovimentacaocancela
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac25_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac25_sequencial = $ac25_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Movimentação Cancela nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac25_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Movimentação Cancela nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac25_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac25_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordomovimentacaocancela";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac25_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordomovimentacaocancela ";
     $sql .= "      inner join acordomovimentacao  on  acordomovimentacao.ac10_sequencial = acordomovimentacaocancela.ac25_acordomovimentacao";
     $sql .= "      inner join acordomovimentacaotipo  on  acordomovimentacaotipo.ac09_sequencial = acordomovimentacao.ac10_acordomovimentacaotipo";
     $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordomovimentacao.ac10_acordo";
     $sql2 = "";
     if($dbwhere==""){
       if($ac25_sequencial!=null ){
         $sql2 .= " where acordomovimentacaocancela.ac25_sequencial = $ac25_sequencial "; 
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
   function sql_query_file ( $ac25_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordomovimentacaocancela ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac25_sequencial!=null ){
         $sql2 .= " where acordomovimentacaocancela.ac25_sequencial = $ac25_sequencial "; 
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