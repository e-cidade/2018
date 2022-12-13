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

//MODULO: contabilidade
//CLASSE DA ENTIDADE contacorrentedetalheconlancamval
class cl_contacorrentedetalheconlancamval { 
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
   var $c28_sequencial = 0; 
   var $c28_contacorrentedetalhe = 0; 
   var $c28_conlancamval = 0; 
   var $c28_tipo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c28_sequencial = int4 = Sequencial 
                 c28_contacorrentedetalhe = int4 = Conta Corrente Detalhe 
                 c28_conlancamval = int4 = Valores do Lançamento 
                 c28_tipo = char(1) = Tipo 
                 ";
   //funcao construtor da classe 
   function cl_contacorrentedetalheconlancamval() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("contacorrentedetalheconlancamval"); 
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
       $this->c28_sequencial = ($this->c28_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c28_sequencial"]:$this->c28_sequencial);
       $this->c28_contacorrentedetalhe = ($this->c28_contacorrentedetalhe == ""?@$GLOBALS["HTTP_POST_VARS"]["c28_contacorrentedetalhe"]:$this->c28_contacorrentedetalhe);
       $this->c28_conlancamval = ($this->c28_conlancamval == ""?@$GLOBALS["HTTP_POST_VARS"]["c28_conlancamval"]:$this->c28_conlancamval);
       $this->c28_tipo = ($this->c28_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["c28_tipo"]:$this->c28_tipo);
     }else{
       $this->c28_sequencial = ($this->c28_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c28_sequencial"]:$this->c28_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c28_sequencial){ 
      $this->atualizacampos();
     if($this->c28_contacorrentedetalhe == null ){ 
       $this->erro_sql = " Campo Conta Corrente Detalhe nao Informado.";
       $this->erro_campo = "c28_contacorrentedetalhe";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c28_conlancamval == null ){ 
       $this->erro_sql = " Campo Valores do Lançamento nao Informado.";
       $this->erro_campo = "c28_conlancamval";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c28_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "c28_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c28_sequencial == "" || $c28_sequencial == null ){
       $result = db_query("select nextval('contacorrentedetalheconlancamval_c28_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: contacorrentedetalheconlancamval_c28_sequencial_seq do campo: c28_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c28_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from contacorrentedetalheconlancamval_c28_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c28_sequencial)){
         $this->erro_sql = " Campo c28_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c28_sequencial = $c28_sequencial; 
       }
     }
     if(($this->c28_sequencial == null) || ($this->c28_sequencial == "") ){ 
       $this->erro_sql = " Campo c28_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into contacorrentedetalheconlancamval(
                                       c28_sequencial 
                                      ,c28_contacorrentedetalhe 
                                      ,c28_conlancamval 
                                      ,c28_tipo 
                       )
                values (
                                $this->c28_sequencial 
                               ,$this->c28_contacorrentedetalhe 
                               ,$this->c28_conlancamval 
                               ,'$this->c28_tipo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Conta Corrente e Valores ($this->c28_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Conta Corrente e Valores já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Conta Corrente e Valores ($this->c28_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c28_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c28_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19666,'$this->c28_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3494,19666,'','".AddSlashes(pg_result($resaco,0,'c28_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3494,19667,'','".AddSlashes(pg_result($resaco,0,'c28_contacorrentedetalhe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3494,19668,'','".AddSlashes(pg_result($resaco,0,'c28_conlancamval'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3494,19675,'','".AddSlashes(pg_result($resaco,0,'c28_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c28_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update contacorrentedetalheconlancamval set ";
     $virgula = "";
     if(trim($this->c28_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c28_sequencial"])){ 
       $sql  .= $virgula." c28_sequencial = $this->c28_sequencial ";
       $virgula = ",";
       if(trim($this->c28_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "c28_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c28_contacorrentedetalhe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c28_contacorrentedetalhe"])){ 
       $sql  .= $virgula." c28_contacorrentedetalhe = $this->c28_contacorrentedetalhe ";
       $virgula = ",";
       if(trim($this->c28_contacorrentedetalhe) == null ){ 
         $this->erro_sql = " Campo Conta Corrente Detalhe nao Informado.";
         $this->erro_campo = "c28_contacorrentedetalhe";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c28_conlancamval)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c28_conlancamval"])){ 
       $sql  .= $virgula." c28_conlancamval = $this->c28_conlancamval ";
       $virgula = ",";
       if(trim($this->c28_conlancamval) == null ){ 
         $this->erro_sql = " Campo Valores do Lançamento nao Informado.";
         $this->erro_campo = "c28_conlancamval";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c28_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c28_tipo"])){ 
       $sql  .= $virgula." c28_tipo = '$this->c28_tipo' ";
       $virgula = ",";
       if(trim($this->c28_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "c28_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c28_sequencial!=null){
       $sql .= " c28_sequencial = $this->c28_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c28_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19666,'$this->c28_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c28_sequencial"]) || $this->c28_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3494,19666,'".AddSlashes(pg_result($resaco,$conresaco,'c28_sequencial'))."','$this->c28_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c28_contacorrentedetalhe"]) || $this->c28_contacorrentedetalhe != "")
           $resac = db_query("insert into db_acount values($acount,3494,19667,'".AddSlashes(pg_result($resaco,$conresaco,'c28_contacorrentedetalhe'))."','$this->c28_contacorrentedetalhe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c28_conlancamval"]) || $this->c28_conlancamval != "")
           $resac = db_query("insert into db_acount values($acount,3494,19668,'".AddSlashes(pg_result($resaco,$conresaco,'c28_conlancamval'))."','$this->c28_conlancamval',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c28_tipo"]) || $this->c28_tipo != "")
           $resac = db_query("insert into db_acount values($acount,3494,19675,'".AddSlashes(pg_result($resaco,$conresaco,'c28_tipo'))."','$this->c28_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Conta Corrente e Valores nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c28_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Conta Corrente e Valores nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c28_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c28_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c28_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c28_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19666,'$c28_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3494,19666,'','".AddSlashes(pg_result($resaco,$iresaco,'c28_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3494,19667,'','".AddSlashes(pg_result($resaco,$iresaco,'c28_contacorrentedetalhe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3494,19668,'','".AddSlashes(pg_result($resaco,$iresaco,'c28_conlancamval'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3494,19675,'','".AddSlashes(pg_result($resaco,$iresaco,'c28_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from contacorrentedetalheconlancamval
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c28_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c28_sequencial = $c28_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Conta Corrente e Valores nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c28_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Conta Corrente e Valores nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c28_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c28_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:contacorrentedetalheconlancamval";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c28_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from contacorrentedetalheconlancamval ";
     $sql .= "      inner join conlancamval  on  conlancamval.c69_sequen = contacorrentedetalheconlancamval.c28_conlancamval";
     $sql .= "      inner join contacorrentedetalhe  on  contacorrentedetalhe.c19_sequencial = contacorrentedetalheconlancamval.c28_contacorrentedetalhe";
     $sql .= "      inner join conlancam  on  conlancam.c70_codlan = conlancamval.c69_codlan";
     $sql .= "      inner join conplanoexe  on  conplanoexe.c62_anousu = conlancamval.c69_anousu and  conplanoexe.c62_reduz = conlancamval.c69_credito";
     $sql .= "      inner join conhist  on  conhist.c50_codhist = conlancamval.c69_codhist";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = contacorrentedetalhe.c19_numcgm";
     $sql .= "      left  join db_config  on  db_config.codigo = contacorrentedetalhe.c19_instit";
     $sql .= "      left  join orctiporec  on  orctiporec.o15_codigo = contacorrentedetalhe.c19_orctiporec";
     $sql .= "      left  join orcorgao  on  orcorgao.o40_anousu = contacorrentedetalhe.c19_orcorgaoanousu and  orcorgao.o40_orgao = contacorrentedetalhe.c19_orcorgaoorgao";
     $sql .= "      left  join orcunidade  on  orcunidade.o41_anousu = contacorrentedetalhe.c19_orcunidadeanousu and  orcunidade.o41_orgao = contacorrentedetalhe.c19_orcunidadeorgao and  orcunidade.o41_unidade = contacorrentedetalhe.c19_orcunidadeunidade";
     $sql .= "      left  join conplanoreduz  on  conplanoreduz.c61_reduz = contacorrentedetalhe.c19_conplanoreduzanousu and  conplanoreduz.c61_anousu = contacorrentedetalhe.c19_reduz";
     $sql .= "      left  join empempenho  on  empempenho.e60_numemp = contacorrentedetalhe.c19_numemp";
     $sql .= "      left  join contabancaria  on  contabancaria.db83_sequencial = contacorrentedetalhe.c19_contabancaria";
     $sql .= "      left  join conlancamconcarpeculiar  on  conlancamconcarpeculiar.c08_sequencial = contacorrentedetalhe.c19_conlancamconcarpeculiar";
     $sql .= "      left  join contacorrente  as a on   a. = contacorrentedetalhe.c19_contacorrente";
     $sql2 = "";
     if($dbwhere==""){
       if($c28_sequencial!=null ){
         $sql2 .= " where contacorrentedetalheconlancamval.c28_sequencial = $c28_sequencial "; 
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
   function sql_query_file ( $c28_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from contacorrentedetalheconlancamval ";
     $sql2 = "";
     if($dbwhere==""){
       if($c28_sequencial!=null ){
         $sql2 .= " where contacorrentedetalheconlancamval.c28_sequencial = $c28_sequencial "; 
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

  function sql_query_lancamentos($sCampos, $sWhere, $sOrder) {

    if (empty($sCampos)) {
      $sCampos = "*";
    }

    if (!empty($sWhere)) {
      $sWhere = " where {$sWhere} ";
    }

    if (!empty($sOrder)) {
      $sOrder = " order by {$sOrder} ";
    }

    $sSql = " select {$sCampos}      ";
    $sSql .= "   from conlancamval " ;
    $sSql .= "        inner join conlancam on conlancam.c70_codlan = conlancamval.c69_codlan ";
    $sSql .= "                           and conlancam.c70_anousu = conlancamval.c69_anousu";
    $sSql .= "        inner join conlancamdoc on conlancamdoc.c71_codlan = conlancamval.c69_codlan ";
    $sSql .= "        inner join conhistdoc on conlancamdoc.c71_coddoc = conhistdoc.c53_coddoc ";
    $sSql .= "        inner join contacorrentedetalheconlancamval on contacorrentedetalheconlancamval.c28_conlancamval = conlancamval.c69_sequen ";
    $sSql .= "        inner join contacorrentedetalhe on contacorrentedetalhe.c19_sequencial = contacorrentedetalheconlancamval.c28_contacorrentedetalhe";
    $sSql .= " $sWhere $sOrder";

    return $sSql;
  }
}