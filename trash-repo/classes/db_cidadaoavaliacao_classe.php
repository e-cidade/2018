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

//MODULO: social
//CLASSE DA ENTIDADE cidadaoavaliacao
class cl_cidadaoavaliacao { 
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
   var $as01_sequencial = 0; 
   var $as01_cidadao = 0; 
   var $as01_cidadao_seq = 0; 
   var $as01_avaliacaogruporesposta = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 as01_sequencial = int4 = C�digo Cidad�o Avalia��o 
                 as01_cidadao = int4 = Cidad�o 
                 as01_cidadao_seq = int4 = C�digo Cidad�o 
                 as01_avaliacaogruporesposta = int4 = C�digo da Avalia��o 
                 ";
   //funcao construtor da classe 
   function cl_cidadaoavaliacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cidadaoavaliacao"); 
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
       $this->as01_sequencial = ($this->as01_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as01_sequencial"]:$this->as01_sequencial);
       $this->as01_cidadao = ($this->as01_cidadao == ""?@$GLOBALS["HTTP_POST_VARS"]["as01_cidadao"]:$this->as01_cidadao);
       $this->as01_cidadao_seq = ($this->as01_cidadao_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["as01_cidadao_seq"]:$this->as01_cidadao_seq);
       $this->as01_avaliacaogruporesposta = ($this->as01_avaliacaogruporesposta == ""?@$GLOBALS["HTTP_POST_VARS"]["as01_avaliacaogruporesposta"]:$this->as01_avaliacaogruporesposta);
     }else{
       $this->as01_sequencial = ($this->as01_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as01_sequencial"]:$this->as01_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($as01_sequencial){ 
      $this->atualizacampos();
     if($this->as01_cidadao == null ){ 
       $this->erro_sql = " Campo Cidad�o nao Informado.";
       $this->erro_campo = "as01_cidadao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as01_cidadao_seq == null ){ 
       $this->erro_sql = " Campo C�digo Cidad�o nao Informado.";
       $this->erro_campo = "as01_cidadao_seq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as01_avaliacaogruporesposta == null ){ 
       $this->erro_sql = " Campo C�digo da Avalia��o nao Informado.";
       $this->erro_campo = "as01_avaliacaogruporesposta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($as01_sequencial == "" || $as01_sequencial == null ){
       $result = db_query("select nextval('cidadaoavaliacao_as01_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cidadaoavaliacao_as01_sequencial_seq do campo: as01_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->as01_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cidadaoavaliacao_as01_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $as01_sequencial)){
         $this->erro_sql = " Campo as01_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->as01_sequencial = $as01_sequencial; 
       }
     }
     if(($this->as01_sequencial == null) || ($this->as01_sequencial == "") ){ 
       $this->erro_sql = " Campo as01_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cidadaoavaliacao(
                                       as01_sequencial 
                                      ,as01_cidadao 
                                      ,as01_cidadao_seq 
                                      ,as01_avaliacaogruporesposta 
                       )
                values (
                                $this->as01_sequencial 
                               ,$this->as01_cidadao 
                               ,$this->as01_cidadao_seq 
                               ,$this->as01_avaliacaogruporesposta 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cidadaoavaliacao ($this->as01_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cidadaoavaliacao j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cidadaoavaliacao ($this->as01_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as01_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     if (!isset($_SESSION["DB_usaAccount"])) {
       
       $resaco = $this->sql_record($this->sql_query_file($this->as01_sequencial));
       if(($resaco!=false)||($this->numrows!=0)){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19067,'$this->as01_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3391,19067,'','".AddSlashes(pg_result($resaco,0,'as01_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3391,19068,'','".AddSlashes(pg_result($resaco,0,'as01_cidadao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3391,19069,'','".AddSlashes(pg_result($resaco,0,'as01_avaliacaogruporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($as01_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cidadaoavaliacao set ";
     $virgula = "";
     if(trim($this->as01_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as01_sequencial"])){ 
       $sql  .= $virgula." as01_sequencial = $this->as01_sequencial ";
       $virgula = ",";
       if(trim($this->as01_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo Cidad�o Avalia��o nao Informado.";
         $this->erro_campo = "as01_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as01_cidadao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as01_cidadao"])){ 
       $sql  .= $virgula." as01_cidadao = $this->as01_cidadao ";
       $virgula = ",";
       if(trim($this->as01_cidadao) == null ){ 
         $this->erro_sql = " Campo Cidad�o nao Informado.";
         $this->erro_campo = "as01_cidadao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as01_cidadao_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as01_cidadao_seq"])){ 
       $sql  .= $virgula." as01_cidadao_seq = $this->as01_cidadao_seq ";
       $virgula = ",";
       if(trim($this->as01_cidadao_seq) == null ){ 
         $this->erro_sql = " Campo C�digo Cidad�o nao Informado.";
         $this->erro_campo = "as01_cidadao_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as01_avaliacaogruporesposta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as01_avaliacaogruporesposta"])){ 
       $sql  .= $virgula." as01_avaliacaogruporesposta = $this->as01_avaliacaogruporesposta ";
       $virgula = ",";
       if(trim($this->as01_avaliacaogruporesposta) == null ){ 
         $this->erro_sql = " Campo C�digo da Avalia��o nao Informado.";
         $this->erro_campo = "as01_avaliacaogruporesposta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($as01_sequencial!=null){
       $sql .= " as01_sequencial = $this->as01_sequencial";
     }
     if (!isset($_SESSION["DB_usaAccount"])) {
       
       $resaco = $this->sql_record($this->sql_query_file($this->as01_sequencial));
       if($this->numrows>0){
         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19067,'$this->as01_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as01_sequencial"]) || $this->as01_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3391,19067,'".AddSlashes(pg_result($resaco,$conresaco,'as01_sequencial'))."','$this->as01_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as01_cidadao"]) || $this->as01_cidadao != "")
             $resac = db_query("insert into db_acount values($acount,3391,19068,'".AddSlashes(pg_result($resaco,$conresaco,'as01_cidadao'))."','$this->as01_cidadao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as01_avaliacaogruporesposta"]) || $this->as01_avaliacaogruporesposta != "")
             $resac = db_query("insert into db_acount values($acount,3391,19069,'".AddSlashes(pg_result($resaco,$conresaco,'as01_avaliacaogruporesposta'))."','$this->as01_avaliacaogruporesposta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cidadaoavaliacao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->as01_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cidadaoavaliacao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->as01_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as01_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($as01_sequencial=null,$dbwhere=null) { 
   
   if (!isset($_SESSION["DB_usaAccount"])) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($as01_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19067,'$as01_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3391,19067,'','".AddSlashes(pg_result($resaco,$iresaco,'as01_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3391,19068,'','".AddSlashes(pg_result($resaco,$iresaco,'as01_cidadao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3391,19096,'','".AddSlashes(pg_result($resaco,$iresaco,'as01_cidadao_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3391,19069,'','".AddSlashes(pg_result($resaco,$iresaco,'as01_avaliacaogruporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
      }
     }
     $sql = " delete from cidadaoavaliacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($as01_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " as01_sequencial = $as01_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cidadaoavaliacao nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$as01_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cidadaoavaliacao nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$as01_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$as01_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:cidadaoavaliacao";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $as01_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cidadaoavaliacao ";
     $sql .= "      inner join cidadao  on  cidadao.ov02_sequencial = cidadaoavaliacao.as01_cidadao and  cidadao.ov02_seq = cidadaoavaliacao.as01_cidadao_seq";
     $sql .= "      inner join avaliacaogruporesposta  on  avaliacaogruporesposta.db107_sequencial = cidadaoavaliacao.as01_avaliacaogruporesposta";
     $sql .= "      inner join situacaocidadao  on  situacaocidadao.ov16_sequencial = cidadao.ov02_situacaocidadao";
     $sql2 = "";
     if($dbwhere==""){
       if($as01_sequencial!=null ){
         $sql2 .= " where cidadaoavaliacao.as01_sequencial = $as01_sequencial "; 
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
   function sql_query_file ( $as01_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cidadaoavaliacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($as01_sequencial!=null ){
         $sql2 .= " where cidadaoavaliacao.as01_sequencial = $as01_sequencial "; 
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