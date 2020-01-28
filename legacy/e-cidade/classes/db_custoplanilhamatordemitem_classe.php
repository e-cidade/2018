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

//MODULO: Custos
//CLASSE DA ENTIDADE custoplanilhamatordemitem
class cl_custoplanilhamatordemitem { 
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
   var $cc20_sequencial = 0; 
   var $cc20_custoplanilhaapuracao = 0; 
   var $cc20_matordemitemcustocriterio = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cc20_sequencial = int4 = Sequencial 
                 cc20_custoplanilhaapuracao = int4 = Custo Planilha Apuração 
                 cc20_matordemitemcustocriterio = int4 = Mat. Ordem item Custo Criterio 
                 ";
   //funcao construtor da classe 
   function cl_custoplanilhamatordemitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("custoplanilhamatordemitem"); 
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
       $this->cc20_sequencial = ($this->cc20_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc20_sequencial"]:$this->cc20_sequencial);
       $this->cc20_custoplanilhaapuracao = ($this->cc20_custoplanilhaapuracao == ""?@$GLOBALS["HTTP_POST_VARS"]["cc20_custoplanilhaapuracao"]:$this->cc20_custoplanilhaapuracao);
       $this->cc20_matordemitemcustocriterio = ($this->cc20_matordemitemcustocriterio == ""?@$GLOBALS["HTTP_POST_VARS"]["cc20_matordemitemcustocriterio"]:$this->cc20_matordemitemcustocriterio);
     }else{
       $this->cc20_sequencial = ($this->cc20_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc20_sequencial"]:$this->cc20_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($cc20_sequencial){ 
      $this->atualizacampos();
     if($this->cc20_custoplanilhaapuracao == null ){ 
       $this->erro_sql = " Campo Custo Planilha Apuração nao Informado.";
       $this->erro_campo = "cc20_custoplanilhaapuracao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc20_matordemitemcustocriterio == null ){ 
       $this->erro_sql = " Campo Mat. Ordem item Custo Criterio nao Informado.";
       $this->erro_campo = "cc20_matordemitemcustocriterio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cc20_sequencial == "" || $cc20_sequencial == null ){
       $result = db_query("select nextval('custoplanilhamatordemitem_cc20_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: custoplanilhamatordemitem_cc20_sequencial_seq do campo: cc20_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cc20_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from custoplanilhamatordemitem_cc20_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $cc20_sequencial)){
         $this->erro_sql = " Campo cc20_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cc20_sequencial = $cc20_sequencial; 
       }
     }
     if(($this->cc20_sequencial == null) || ($this->cc20_sequencial == "") ){ 
       $this->erro_sql = " Campo cc20_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into custoplanilhamatordemitem(
                                       cc20_sequencial 
                                      ,cc20_custoplanilhaapuracao 
                                      ,cc20_matordemitemcustocriterio 
                       )
                values (
                                $this->cc20_sequencial 
                               ,$this->cc20_custoplanilhaapuracao 
                               ,$this->cc20_matordemitemcustocriterio 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Custo Plaanilha Material Ordem Item ($this->cc20_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Custo Plaanilha Material Ordem Item já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Custo Plaanilha Material Ordem Item ($this->cc20_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc20_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cc20_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15139,'$this->cc20_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2665,15139,'','".AddSlashes(pg_result($resaco,0,'cc20_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2665,15140,'','".AddSlashes(pg_result($resaco,0,'cc20_custoplanilhaapuracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2665,15141,'','".AddSlashes(pg_result($resaco,0,'cc20_matordemitemcustocriterio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cc20_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update custoplanilhamatordemitem set ";
     $virgula = "";
     if(trim($this->cc20_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc20_sequencial"])){ 
       $sql  .= $virgula." cc20_sequencial = $this->cc20_sequencial ";
       $virgula = ",";
       if(trim($this->cc20_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "cc20_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc20_custoplanilhaapuracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc20_custoplanilhaapuracao"])){ 
       $sql  .= $virgula." cc20_custoplanilhaapuracao = $this->cc20_custoplanilhaapuracao ";
       $virgula = ",";
       if(trim($this->cc20_custoplanilhaapuracao) == null ){ 
         $this->erro_sql = " Campo Custo Planilha Apuração nao Informado.";
         $this->erro_campo = "cc20_custoplanilhaapuracao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc20_matordemitemcustocriterio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc20_matordemitemcustocriterio"])){ 
       $sql  .= $virgula." cc20_matordemitemcustocriterio = $this->cc20_matordemitemcustocriterio ";
       $virgula = ",";
       if(trim($this->cc20_matordemitemcustocriterio) == null ){ 
         $this->erro_sql = " Campo Mat. Ordem item Custo Criterio nao Informado.";
         $this->erro_campo = "cc20_matordemitemcustocriterio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cc20_sequencial!=null){
       $sql .= " cc20_sequencial = $this->cc20_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cc20_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15139,'$this->cc20_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc20_sequencial"]) || $this->cc20_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2665,15139,'".AddSlashes(pg_result($resaco,$conresaco,'cc20_sequencial'))."','$this->cc20_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc20_custoplanilhaapuracao"]) || $this->cc20_custoplanilhaapuracao != "")
           $resac = db_query("insert into db_acount values($acount,2665,15140,'".AddSlashes(pg_result($resaco,$conresaco,'cc20_custoplanilhaapuracao'))."','$this->cc20_custoplanilhaapuracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc20_matordemitemcustocriterio"]) || $this->cc20_matordemitemcustocriterio != "")
           $resac = db_query("insert into db_acount values($acount,2665,15141,'".AddSlashes(pg_result($resaco,$conresaco,'cc20_matordemitemcustocriterio'))."','$this->cc20_matordemitemcustocriterio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Custo Plaanilha Material Ordem Item nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc20_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Custo Plaanilha Material Ordem Item nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc20_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc20_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cc20_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cc20_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15139,'$cc20_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2665,15139,'','".AddSlashes(pg_result($resaco,$iresaco,'cc20_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2665,15140,'','".AddSlashes(pg_result($resaco,$iresaco,'cc20_custoplanilhaapuracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2665,15141,'','".AddSlashes(pg_result($resaco,$iresaco,'cc20_matordemitemcustocriterio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from custoplanilhamatordemitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cc20_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cc20_sequencial = $cc20_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Custo Plaanilha Material Ordem Item nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cc20_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Custo Plaanilha Material Ordem Item nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cc20_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cc20_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:custoplanilhamatordemitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $cc20_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from custoplanilhamatordemitem ";
     $sql .= "      inner join custoplanilhaapuracao  on  custoplanilhaapuracao.cc17_sequencial = custoplanilhamatordemitem.cc20_custoplanilhaapuracao";
     $sql .= "      inner join custoplanoanalitica  on  custoplanoanalitica.cc04_sequencial = custoplanilhaapuracao.cc17_custoplanoanalitica";
     $sql .= "      inner join custoplanilhaorigem  on  custoplanilhaorigem.cc14_sequencial = custoplanilhaapuracao.cc17_custoplanilhaorigem";
     $sql .= "      inner join custoplanilha  on  custoplanilha.cc15_sequencial = custoplanilhaapuracao.cc17_custoplanilha";
     $sql2 = "";
     if($dbwhere==""){
       if($cc20_sequencial!=null ){
         $sql2 .= " where custoplanilhamatordemitem.cc20_sequencial = $cc20_sequencial "; 
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
   function sql_query_file ( $cc20_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from custoplanilhamatordemitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($cc20_sequencial!=null ){
         $sql2 .= " where custoplanilhamatordemitem.cc20_sequencial = $cc20_sequencial "; 
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