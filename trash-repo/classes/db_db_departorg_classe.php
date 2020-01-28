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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_departorg
class cl_db_departorg { 
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
   var $db01_coddepto = 0; 
   var $db01_anousu = 0; 
   var $db01_orgao = 0; 
   var $db01_unidade = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db01_coddepto = int4 = Código do departamento 
                 db01_anousu = int4 = Ano 
                 db01_orgao = int4 = Órgão 
                 db01_unidade = int4 = Unidade 
                 ";
   //funcao construtor da classe 
   function cl_db_departorg() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_departorg"); 
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
       $this->db01_coddepto = ($this->db01_coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["db01_coddepto"]:$this->db01_coddepto);
       $this->db01_anousu = ($this->db01_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["db01_anousu"]:$this->db01_anousu);
       $this->db01_orgao = ($this->db01_orgao == ""?@$GLOBALS["HTTP_POST_VARS"]["db01_orgao"]:$this->db01_orgao);
       $this->db01_unidade = ($this->db01_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["db01_unidade"]:$this->db01_unidade);
     }else{
       $this->db01_coddepto = ($this->db01_coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["db01_coddepto"]:$this->db01_coddepto);
       $this->db01_anousu = ($this->db01_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["db01_anousu"]:$this->db01_anousu);
     }
   }
   // funcao para inclusao
   function incluir ($db01_coddepto,$db01_anousu){ 
      $this->atualizacampos();
     if($this->db01_orgao == null ){ 
       $this->erro_sql = " Campo Órgão nao Informado.";
       $this->erro_campo = "db01_orgao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db01_unidade == null ){ 
       $this->erro_sql = " Campo Unidade nao Informado.";
       $this->erro_campo = "db01_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->db01_coddepto = $db01_coddepto; 
       $this->db01_anousu = $db01_anousu; 
     if(($this->db01_coddepto == null) || ($this->db01_coddepto == "") ){ 
       $this->erro_sql = " Campo db01_coddepto nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->db01_anousu == null) || ($this->db01_anousu == "") ){ 
       $this->erro_sql = " Campo db01_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_departorg(
                                       db01_coddepto 
                                      ,db01_anousu 
                                      ,db01_orgao 
                                      ,db01_unidade 
                       )
                values (
                                $this->db01_coddepto 
                               ,$this->db01_anousu 
                               ,$this->db01_orgao 
                               ,$this->db01_unidade 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Orgaos e unidades por departamento ($this->db01_coddepto."-".$this->db01_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Orgaos e unidades por departamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Orgaos e unidades por departamento ($this->db01_coddepto."-".$this->db01_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db01_coddepto."-".$this->db01_anousu;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db01_coddepto,$this->db01_anousu));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3451,'$this->db01_coddepto','I')");
       $resac = db_query("insert into db_acountkey values($acount,3454,'$this->db01_anousu','I')");
       $resac = db_query("insert into db_acount values($acount,507,3451,'','".AddSlashes(pg_result($resaco,0,'db01_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,507,3454,'','".AddSlashes(pg_result($resaco,0,'db01_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,507,3452,'','".AddSlashes(pg_result($resaco,0,'db01_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,507,3453,'','".AddSlashes(pg_result($resaco,0,'db01_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db01_coddepto=null,$db01_anousu=null) { 
      $this->atualizacampos();
     $sql = " update db_departorg set ";
     $virgula = "";
     if(trim($this->db01_coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db01_coddepto"])){ 
       $sql  .= $virgula." db01_coddepto = $this->db01_coddepto ";
       $virgula = ",";
       if(trim($this->db01_coddepto) == null ){ 
         $this->erro_sql = " Campo Código do departamento nao Informado.";
         $this->erro_campo = "db01_coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db01_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db01_anousu"])){ 
       $sql  .= $virgula." db01_anousu = $this->db01_anousu ";
       $virgula = ",";
       if(trim($this->db01_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "db01_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db01_orgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db01_orgao"])){ 
       $sql  .= $virgula." db01_orgao = $this->db01_orgao ";
       $virgula = ",";
       if(trim($this->db01_orgao) == null ){ 
         $this->erro_sql = " Campo Órgão nao Informado.";
         $this->erro_campo = "db01_orgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db01_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db01_unidade"])){ 
       $sql  .= $virgula." db01_unidade = $this->db01_unidade ";
       $virgula = ",";
       if(trim($this->db01_unidade) == null ){ 
         $this->erro_sql = " Campo Unidade nao Informado.";
         $this->erro_campo = "db01_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db01_coddepto!=null){
       $sql .= " db01_coddepto = $this->db01_coddepto";
     }
     if($db01_anousu!=null){
       $sql .= " and  db01_anousu = $this->db01_anousu";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db01_coddepto,$this->db01_anousu));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3451,'$this->db01_coddepto','A')");
         $resac = db_query("insert into db_acountkey values($acount,3454,'$this->db01_anousu','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db01_coddepto"]))
           $resac = db_query("insert into db_acount values($acount,507,3451,'".AddSlashes(pg_result($resaco,$conresaco,'db01_coddepto'))."','$this->db01_coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db01_anousu"]))
           $resac = db_query("insert into db_acount values($acount,507,3454,'".AddSlashes(pg_result($resaco,$conresaco,'db01_anousu'))."','$this->db01_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db01_orgao"]))
           $resac = db_query("insert into db_acount values($acount,507,3452,'".AddSlashes(pg_result($resaco,$conresaco,'db01_orgao'))."','$this->db01_orgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db01_unidade"]))
           $resac = db_query("insert into db_acount values($acount,507,3453,'".AddSlashes(pg_result($resaco,$conresaco,'db01_unidade'))."','$this->db01_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Orgaos e unidades por departamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db01_coddepto."-".$this->db01_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Orgaos e unidades por departamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db01_coddepto."-".$this->db01_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db01_coddepto."-".$this->db01_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db01_coddepto=null,$db01_anousu=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db01_coddepto,$db01_anousu));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3451,'$db01_coddepto','E')");
         $resac = db_query("insert into db_acountkey values($acount,3454,'$db01_anousu','E')");
         $resac = db_query("insert into db_acount values($acount,507,3451,'','".AddSlashes(pg_result($resaco,$iresaco,'db01_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,507,3454,'','".AddSlashes(pg_result($resaco,$iresaco,'db01_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,507,3452,'','".AddSlashes(pg_result($resaco,$iresaco,'db01_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,507,3453,'','".AddSlashes(pg_result($resaco,$iresaco,'db01_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_departorg
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db01_coddepto != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db01_coddepto = $db01_coddepto ";
        }
        if($db01_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db01_anousu = $db01_anousu ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Orgaos e unidades por departamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db01_coddepto."-".$db01_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Orgaos e unidades por departamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db01_coddepto."-".$db01_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db01_coddepto."-".$db01_anousu;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_departorg";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db01_coddepto=null,$db01_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_departorg 
               inner join db_depart   on  db_depart.coddepto    = db_departorg.db01_coddepto 
               inner join orcorgao    on  orcorgao.o40_orgao    = db_departorg.db01_orgao and
                                          orcorgao.o40_anousu   = db_departorg.db01_anousu
               inner join orcunidade  on  orcunidade.o41_orgao  = db_departorg.db01_orgao and 
                                          orcunidade.o41_anousu =  db_departorg.db01_anousu";
     
     //orcunidade
     //ororgao
     $sql2 = "";
     if($dbwhere==""){
       if($db01_coddepto!=null ){
         $sql2 .= " where db_departorg.db01_coddepto = $db01_coddepto "; 
       } 
       if($db01_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_departorg.db01_anousu = $db01_anousu "; 
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
   function sql_query_file ( $db01_coddepto=null,$db01_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_departorg ";
     $sql2 = "";
     if($dbwhere==""){
       if($db01_coddepto!=null ){
         $sql2 .= " where db_departorg.db01_coddepto = $db01_coddepto "; 
       } 
       if($db01_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_departorg.db01_anousu = $db01_anousu "; 
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
   function sql_query_orgunid ( $db01_coddepto=null,$db01_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_departorg ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = db_departorg.db01_coddepto";
     $sql .= "      inner join orcorgao   on  orcorgao.o40_orgao = db_departorg.db01_orgao and";
     $sql .= "                                orcorgao.o40_anousu = db_departorg.db01_anousu";
     $sql .= "      inner join orcunidade on  orcunidade.o41_unidade = db_departorg.db01_unidade and ";
     $sql .= "                                orcunidade.o41_orgao   = db_departorg.db01_orgao and";
     $sql .= "                                orcunidade.o41_anousu = db_departorg.db01_anousu";
     $sql2 = "";
     if($dbwhere==""){
       if($db01_coddepto!=null ){
         $sql2 .= " where db_departorg.db01_coddepto = $db01_coddepto "; 
       } 
       if($db01_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_departorg.db01_anousu = $db01_anousu "; 
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