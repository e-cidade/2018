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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcdotacaocontr
class cl_orcdotacaocontr { 
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
   var $o61_sequencial = 0; 
   var $o61_anousu = 0; 
   var $o61_coddot = 0; 
   var $o61_codigo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o61_sequencial = int4 = Código Sequencial 
                 o61_anousu = int4 = Exercício 
                 o61_coddot = int4 = Reduzido 
                 o61_codigo = int4 = Contra Recurso 
                 ";
   //funcao construtor da classe 
   function cl_orcdotacaocontr() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcdotacaocontr"); 
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
       $this->o61_sequencial = ($this->o61_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o61_sequencial"]:$this->o61_sequencial);
       $this->o61_anousu = ($this->o61_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o61_anousu"]:$this->o61_anousu);
       $this->o61_coddot = ($this->o61_coddot == ""?@$GLOBALS["HTTP_POST_VARS"]["o61_coddot"]:$this->o61_coddot);
       $this->o61_codigo = ($this->o61_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["o61_codigo"]:$this->o61_codigo);
     }else{
       $this->o61_sequencial = ($this->o61_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o61_sequencial"]:$this->o61_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o61_sequencial){ 
      $this->atualizacampos();
     if($this->o61_anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "o61_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o61_coddot == null ){ 
       $this->erro_sql = " Campo Reduzido nao Informado.";
       $this->erro_campo = "o61_coddot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o61_codigo == null ){ 
       $this->o61_codigo = "0";
     }
     if($o61_sequencial == "" || $o61_sequencial == null ){
       $result = db_query("select nextval('orcdotacaocontr_o61_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcdotacaocontr_o61_sequencial_seq do campo: o61_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o61_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcdotacaocontr_o61_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o61_sequencial)){
         $this->erro_sql = " Campo o61_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o61_sequencial = $o61_sequencial; 
       }
     }
     if(($this->o61_sequencial == null) || ($this->o61_sequencial == "") ){ 
       $this->erro_sql = " Campo o61_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcdotacaocontr(
                                       o61_sequencial 
                                      ,o61_anousu 
                                      ,o61_coddot 
                                      ,o61_codigo 
                       )
                values (
                                $this->o61_sequencial 
                               ,$this->o61_anousu 
                               ,$this->o61_coddot 
                               ,$this->o61_codigo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Contra recurso da Dotação ($this->o61_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Contra recurso da Dotação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Contra recurso da Dotação ($this->o61_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o61_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,"o61_sequencial={$this->o61_sequencial}"));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11921,'$this->o61_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,779,11921,'','".AddSlashes(pg_result($resaco,0,'o61_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,779,5342,'','".AddSlashes(pg_result($resaco,0,'o61_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,779,5343,'','".AddSlashes(pg_result($resaco,0,'o61_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,779,5344,'','".AddSlashes(pg_result($resaco,0,'o61_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o61_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orcdotacaocontr set ";
     $virgula = "";
     if(trim($this->o61_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o61_sequencial"])){ 
       $sql  .= $virgula." o61_sequencial = $this->o61_sequencial ";
       $virgula = ",";
       if(trim($this->o61_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o61_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o61_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o61_anousu"])){ 
       $sql  .= $virgula." o61_anousu = $this->o61_anousu ";
       $virgula = ",";
       if(trim($this->o61_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o61_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o61_coddot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o61_coddot"])){ 
       $sql  .= $virgula." o61_coddot = $this->o61_coddot ";
       $virgula = ",";
       if(trim($this->o61_coddot) == null ){ 
         $this->erro_sql = " Campo Reduzido nao Informado.";
         $this->erro_campo = "o61_coddot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o61_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o61_codigo"])){ 
        if(trim($this->o61_codigo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o61_codigo"])){ 
           $this->o61_codigo = "0" ; 
        } 
       $sql  .= $virgula." o61_codigo = $this->o61_codigo ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o61_sequencial!=null){
       $sql .= " o61_sequencial = $this->o61_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,"o61_sequencial={$o61_sequencial}"));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11921,'$this->o61_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o61_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,779,11921,'".AddSlashes(pg_result($resaco,$conresaco,'o61_sequencial'))."','$this->o61_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o61_anousu"]))
           $resac = db_query("insert into db_acount values($acount,779,5342,'".AddSlashes(pg_result($resaco,$conresaco,'o61_anousu'))."','$this->o61_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o61_coddot"]))
           $resac = db_query("insert into db_acount values($acount,779,5343,'".AddSlashes(pg_result($resaco,$conresaco,'o61_coddot'))."','$this->o61_coddot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o61_codigo"]))
           $resac = db_query("insert into db_acount values($acount,779,5344,'".AddSlashes(pg_result($resaco,$conresaco,'o61_codigo'))."','$this->o61_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contra recurso da Dotação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o61_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contra recurso da Dotação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o61_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o61_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o61_sequencial=null,$dbwhere=null) {
      
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,"o61_sequencial={$o61_sequencial}"));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11921,'$o61_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,779,11921,'','".AddSlashes(pg_result($resaco,$iresaco,'o61_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,779,5342,'','".AddSlashes(pg_result($resaco,$iresaco,'o61_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,779,5343,'','".AddSlashes(pg_result($resaco,$iresaco,'o61_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,779,5344,'','".AddSlashes(pg_result($resaco,$iresaco,'o61_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcdotacaocontr
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o61_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o61_sequencial = $o61_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contra recurso da Dotação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o61_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contra recurso da Dotação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o61_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o61_sequencial;
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
     /* if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:orcdotacaocontr";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     */
     return $result;
   }

   function sql_query ( $o61_anousu=null,$o61_coddot=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcdotacaocontr ";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacaocontr.o61_codigo";
     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_coddot = orcdotacaocontr.o61_coddot and  orcdotacao.o58_anousu = orcdotacaocontr.o61_anousu";
     $sql .= "      inner join db_config  on  db_config.codigo = orcdotacao.o58_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele and orcelemento.o56_anousu = orcdotacao.o58_anousu";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
     $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
     $sql .= "      inner join orcfuncao  as b on   b.o52_funcao = orcdotacao.o58_funcao";
     $sql .= "      inner join orcsubfuncao  as c on   c.o53_subfuncao = orcdotacao.o58_subfuncao";
     $sql .= "      inner join orcprograma  as d on   d.o54_anousu = orcdotacao.o58_anousu and   d.o54_programa = orcdotacao.o58_programa";
     $sql .= "      inner join orcelemento  as d on   d.o56_codele = orcdotacao.o58_codele and d.o56_anousu = orcdotacao.o58_anousu";
     $sql .= "      inner join orcprojativ  as d on   d.o55_anousu = orcdotacao.o58_anousu and   d.o55_projativ = orcdotacao.o58_projativ";
     $sql .= "      inner join orcorgao  as d on   d.o40_anousu = orcdotacao.o58_anousu and   d.o40_orgao = orcdotacao.o58_orgao";
     $sql .= "      inner join orcunidade  as d on   d.o41_anousu = orcdotacao.o58_anousu and   d.o41_orgao = orcdotacao.o58_orgao and   d.o41_unidade = orcdotacao.o58_unidade";
     $sql2 = "";
     if($dbwhere==""){
       if($o61_anousu!=null ){
         $sql2 .= " where orcdotacaocontr.o61_anousu = $o61_anousu "; 
       } 
       if($o61_coddot!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcdotacaocontr.o61_coddot = $o61_coddot "; 
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

   function sql_query_file ( $o61_anousu=null,$o61_coddot=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcdotacaocontr ";
     $sql2 = "";
     if($dbwhere==""){
       if($o61_anousu!=null ){
         $sql2 .= " where orcdotacaocontr.o61_anousu = $o61_anousu "; 
       } 
       if($o61_coddot!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcdotacaocontr.o61_coddot = $o61_coddot "; 
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
   function sql_query_rec ( $o61_anousu=null,$o61_coddot=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcdotacaocontr ";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacaocontr.o61_codigo";
     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_coddot = orcdotacaocontr.o61_coddot and  orcdotacao.o58_anousu = orcdotacaocontr.o61_anousu";
     $sql .= "      inner join db_config  on  db_config.codigo = orcdotacao.o58_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($o61_anousu!=null ){
         $sql2 .= " where orcdotacaocontr.o61_anousu = $o61_anousu "; 
       } 
       if($o61_coddot!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcdotacaocontr.o61_coddot = $o61_coddot "; 
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
   * retorna os recursos vinculados ativos no perido.
   *
   * @param string $sDataini data inicial, ou data a ser verificada
   * @param string $sDataFim optional final do periodo
   * @param string $campos lista de campos
   * @param string $ordem ordenação dos recursos 
   * @param string $dbwhere filtro de selecao
   * @return string
   */
 function sql_query_convenios ($iDotacao, $iAnousu, $sDataini, $sDataFim = null, $campos="*",$ordem="o15_codigo",$dbwhere=""){ 

   if (empty($sDataini)) {
     return false;
   }
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
     $sql .= " from orcdotacaocontr ";
     $sql .= "      inner join orctiporecconvenio   on o61_codigo = o16_orctiporec";
     $sql .= "      inner join orctiporec           on o15_codigo = o16_orctiporec";
     $sql2 = " where o61_coddot = {$iDotacao} and o61_anousu = {$iAnousu}";
     if ($sDataFim == null) {
        
        $sql2 .= " and (('{$sDataini}' between o16_dtvigenciaini and o16_dtvigenciafim) or ";
        $sql2 .= "('{$sDataini}' between o16_dtprorrogacaoini and o16_dtprorrogacaofim))";
        
     } else {
       
       $sql2 .= " and ((o16_dtvigenciaini <= '{$sDataini}' and o16_dtvigenciafim >= '{$sDataFim}') or ";
       $sql2 .= "(o16_dtprorrogacaoini <= '{$sDataini}' and o16_dtprorrogacaofim >= '{$sDataFim}'))";
       
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