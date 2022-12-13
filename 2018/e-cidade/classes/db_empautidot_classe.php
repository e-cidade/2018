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

//MODULO: empenho
//CLASSE DA ENTIDADE empautidot
class cl_empautidot { 
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
   var $e56_autori = 0; 
   var $e56_anousu = 0; 
   var $e56_coddot = 0; 
   var $e56_orctiporec = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e56_autori = int4 = Autorização 
                 e56_anousu = int4 = Ano 
                 e56_coddot = int4 = Dotação 
                 e56_orctiporec = int4 = Contrapartida 
                 ";
   //funcao construtor da classe 
   function cl_empautidot() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empautidot"); 
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
       $this->e56_autori = ($this->e56_autori == ""?@$GLOBALS["HTTP_POST_VARS"]["e56_autori"]:$this->e56_autori);
       $this->e56_anousu = ($this->e56_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["e56_anousu"]:$this->e56_anousu);
       $this->e56_coddot = ($this->e56_coddot == ""?@$GLOBALS["HTTP_POST_VARS"]["e56_coddot"]:$this->e56_coddot);
       $this->e56_orctiporec = ($this->e56_orctiporec == ""?@$GLOBALS["HTTP_POST_VARS"]["e56_orctiporec"]:$this->e56_orctiporec);
     }else{
       $this->e56_autori = ($this->e56_autori == ""?@$GLOBALS["HTTP_POST_VARS"]["e56_autori"]:$this->e56_autori);
     }
   }
   // funcao para inclusao
   function incluir ($e56_autori){ 
      $this->atualizacampos();
     if($this->e56_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "e56_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e56_coddot == null ){ 
       $this->erro_sql = " Campo Dotação nao Informado.";
       $this->erro_campo = "e56_coddot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e56_orctiporec == null ){ 
       $this->e56_orctiporec = "null";
     }
       $this->e56_autori = $e56_autori; 
     if(($this->e56_autori == null) || ($this->e56_autori == "") ){ 
       $this->erro_sql = " Campo e56_autori nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empautidot(
                                       e56_autori 
                                      ,e56_anousu 
                                      ,e56_coddot 
                                      ,e56_orctiporec 
                       )
                values (
                                $this->e56_autori 
                               ,$this->e56_anousu 
                               ,$this->e56_coddot 
                               ,$this->e56_orctiporec 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dotação de empenho ($this->e56_autori) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dotação de empenho já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dotação de empenho ($this->e56_autori) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e56_autori;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e56_autori));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5474,'$this->e56_autori','I')");
       $resac = db_query("insert into db_acount values($acount,812,5474,'','".AddSlashes(pg_result($resaco,0,'e56_autori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,812,5475,'','".AddSlashes(pg_result($resaco,0,'e56_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,812,5476,'','".AddSlashes(pg_result($resaco,0,'e56_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,812,11915,'','".AddSlashes(pg_result($resaco,0,'e56_orctiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e56_autori=null) { 
      $this->atualizacampos();
     $sql = " update empautidot set ";
     $virgula = "";
     if(trim($this->e56_autori)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e56_autori"])){ 
       $sql  .= $virgula." e56_autori = $this->e56_autori ";
       $virgula = ",";
       if(trim($this->e56_autori) == null ){ 
         $this->erro_sql = " Campo Autorização nao Informado.";
         $this->erro_campo = "e56_autori";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e56_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e56_anousu"])){ 
       $sql  .= $virgula." e56_anousu = $this->e56_anousu ";
       $virgula = ",";
       if(trim($this->e56_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "e56_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e56_coddot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e56_coddot"])){ 
       $sql  .= $virgula." e56_coddot = $this->e56_coddot ";
       $virgula = ",";
       if(trim($this->e56_coddot) == null ){ 
         $this->erro_sql = " Campo Dotação nao Informado.";
         $this->erro_campo = "e56_coddot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e56_orctiporec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e56_orctiporec"])){ 
        if(trim($this->e56_orctiporec)=="" && isset($GLOBALS["HTTP_POST_VARS"]["e56_orctiporec"])){ 
           $this->e56_orctiporec = "0" ; 
        } 
       $sql  .= $virgula." e56_orctiporec = $this->e56_orctiporec ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($e56_autori!=null){
       $sql .= " e56_autori = $this->e56_autori";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e56_autori));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5474,'$this->e56_autori','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e56_autori"]))
           $resac = db_query("insert into db_acount values($acount,812,5474,'".AddSlashes(pg_result($resaco,$conresaco,'e56_autori'))."','$this->e56_autori',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e56_anousu"]))
           $resac = db_query("insert into db_acount values($acount,812,5475,'".AddSlashes(pg_result($resaco,$conresaco,'e56_anousu'))."','$this->e56_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e56_coddot"]))
           $resac = db_query("insert into db_acount values($acount,812,5476,'".AddSlashes(pg_result($resaco,$conresaco,'e56_coddot'))."','$this->e56_coddot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e56_orctiporec"]))
           $resac = db_query("insert into db_acount values($acount,812,11915,'".AddSlashes(pg_result($resaco,$conresaco,'e56_orctiporec'))."','$this->e56_orctiporec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dotação de empenho nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e56_autori;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dotação de empenho nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e56_autori;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e56_autori;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e56_autori=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e56_autori));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5474,'$e56_autori','E')");
         $resac = db_query("insert into db_acount values($acount,812,5474,'','".AddSlashes(pg_result($resaco,$iresaco,'e56_autori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,812,5475,'','".AddSlashes(pg_result($resaco,$iresaco,'e56_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,812,5476,'','".AddSlashes(pg_result($resaco,$iresaco,'e56_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,812,11915,'','".AddSlashes(pg_result($resaco,$iresaco,'e56_orctiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empautidot
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e56_autori != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e56_autori = $e56_autori ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dotação de empenho nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e56_autori;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dotação de empenho nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e56_autori;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e56_autori;
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
        $this->erro_sql   = "Record Vazio na Tabela:empautidot";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e56_autori=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empautidot ";
     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empautidot.e56_anousu and  orcdotacao.o58_coddot = empautidot.e56_coddot";
     $sql .= "      inner join empautoriza  on  empautoriza.e54_autori = empautidot.e56_autori";
     $sql .= "      inner join db_config  on  db_config.codigo = orcdotacao.o58_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele and  orcelemento.o56_anousu = orcdotacao.o58_anousu";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
     $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
     $sql .= "      inner join orctiporec  as b on   b.o15_codigo = orcdotacao.o58_codigo";
     $sql .= "      inner join orcfuncao  as c on   c.o52_funcao = orcdotacao.o58_funcao";
     $sql .= "      inner join orcsubfuncao  as d on   d.o53_subfuncao = orcdotacao.o58_subfuncao";
     $sql .= "      inner join orcprograma  as d on   d.o54_anousu = orcdotacao.o58_anousu and   d.o54_programa = orcdotacao.o58_programa";
     $sql .= "      inner join orcelemento  as d on   d.o56_codele = orcdotacao.o58_codele and   d.o56_anousu = orcdotacao.o58_anousu";
     $sql .= "      inner join orcprojativ  as d on   d.o55_anousu = orcdotacao.o58_anousu and   d.o55_projativ = orcdotacao.o58_projativ";
     $sql .= "      inner join orcorgao  as d on   d.o40_anousu = orcdotacao.o58_anousu and   d.o40_orgao = orcdotacao.o58_orgao";
     $sql .= "      inner join orcunidade  as d on   d.o41_anousu = orcdotacao.o58_anousu and   d.o41_orgao = orcdotacao.o58_orgao and   d.o41_unidade = orcdotacao.o58_unidade";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empautoriza.e54_numcgm";
     $sql .= "      inner join db_config  as d on   d.codigo = empautoriza.e54_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empautoriza.e54_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = empautoriza.e54_depto";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empautoriza.e54_codcom";
     $sql2 = "";
     if($dbwhere==""){
       if($e56_autori!=null ){
         $sql2 .= " where empautidot.e56_autori = $e56_autori "; 
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
   function sql_query_dotacao ($e56_autori=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empautidot ";
     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empautidot.e56_anousu and  orcdotacao.o58_coddot = empautidot.e56_coddot";
     $sql .= "      inner join empautoriza  on  empautoriza.e54_autori = empautidot.e56_autori";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele 
                                           and orcelemento.o56_anousu = orcdotacao.o58_anousu";
     $sql .= "  left join orctiporec on o15_codigo = e56_orctiporec";
     $sql2 = "";
     if($dbwhere==""){
       if($e56_autori!=null ){
         $sql2 .= " where empautidot.e56_autori = $e56_autori "; 
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
   function sql_query_file ( $e56_autori=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empautidot ";
     $sql2 = "";
     if($dbwhere==""){
       if($e56_autori!=null ){
         $sql2 .= " where empautidot.e56_autori = $e56_autori "; 
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