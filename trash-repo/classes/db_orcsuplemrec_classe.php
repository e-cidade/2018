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
//CLASSE DA ENTIDADE orcsuplemrec
class cl_orcsuplemrec { 
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
   var $o85_codsup = 0; 
   var $o85_codrec = 0; 
   var $o85_anousu = 0; 
   var $o85_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o85_codsup = int4 = codigo da suplementação 
                 o85_codrec = int8 = codigo da receita 
                 o85_anousu = int4 = ano de execício 
                 o85_valor = float8 = valor 
                 ";
   //funcao construtor da classe 
   function cl_orcsuplemrec() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcsuplemrec"); 
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
       $this->o85_codsup = ($this->o85_codsup == ""?@$GLOBALS["HTTP_POST_VARS"]["o85_codsup"]:$this->o85_codsup);
       $this->o85_codrec = ($this->o85_codrec == ""?@$GLOBALS["HTTP_POST_VARS"]["o85_codrec"]:$this->o85_codrec);
       $this->o85_anousu = ($this->o85_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o85_anousu"]:$this->o85_anousu);
       $this->o85_valor = ($this->o85_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o85_valor"]:$this->o85_valor);
     }else{
       $this->o85_codsup = ($this->o85_codsup == ""?@$GLOBALS["HTTP_POST_VARS"]["o85_codsup"]:$this->o85_codsup);
       $this->o85_codrec = ($this->o85_codrec == ""?@$GLOBALS["HTTP_POST_VARS"]["o85_codrec"]:$this->o85_codrec);
     }
   }
   // funcao para inclusao
   function incluir ($o85_codsup,$o85_codrec){ 
      $this->atualizacampos();
     if($this->o85_anousu == null ){ 
       $this->erro_sql = " Campo ano de execício nao Informado.";
       $this->erro_campo = "o85_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o85_valor == null ){ 
       $this->erro_sql = " Campo valor nao Informado.";
       $this->erro_campo = "o85_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o85_codsup = $o85_codsup; 
       $this->o85_codrec = $o85_codrec; 
     if(($this->o85_codsup == null) || ($this->o85_codsup == "") ){ 
       $this->erro_sql = " Campo o85_codsup nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o85_codrec == null) || ($this->o85_codrec == "") ){ 
       $this->erro_sql = " Campo o85_codrec nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcsuplemrec(
                                       o85_codsup 
                                      ,o85_codrec 
                                      ,o85_anousu 
                                      ,o85_valor 
                       )
                values (
                                $this->o85_codsup 
                               ,$this->o85_codrec 
                               ,$this->o85_anousu 
                               ,$this->o85_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->o85_codsup."-".$this->o85_codrec) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->o85_codsup."-".$this->o85_codrec) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o85_codsup."-".$this->o85_codrec;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o85_codsup,$this->o85_codrec));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6087,'$this->o85_codsup','I')");
       $resac = db_query("insert into db_acountkey values($acount,6088,'$this->o85_codrec','I')");
       $resac = db_query("insert into db_acount values($acount,978,6087,'','".AddSlashes(pg_result($resaco,0,'o85_codsup'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,978,6088,'','".AddSlashes(pg_result($resaco,0,'o85_codrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,978,6089,'','".AddSlashes(pg_result($resaco,0,'o85_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,978,6090,'','".AddSlashes(pg_result($resaco,0,'o85_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o85_codsup=null,$o85_codrec=null) { 
      $this->atualizacampos();
     $sql = " update orcsuplemrec set ";
     $virgula = "";
     if(trim($this->o85_codsup)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o85_codsup"])){ 
       $sql  .= $virgula." o85_codsup = $this->o85_codsup ";
       $virgula = ",";
       if(trim($this->o85_codsup) == null ){ 
         $this->erro_sql = " Campo codigo da suplementação nao Informado.";
         $this->erro_campo = "o85_codsup";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o85_codrec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o85_codrec"])){ 
       $sql  .= $virgula." o85_codrec = $this->o85_codrec ";
       $virgula = ",";
       if(trim($this->o85_codrec) == null ){ 
         $this->erro_sql = " Campo codigo da receita nao Informado.";
         $this->erro_campo = "o85_codrec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o85_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o85_anousu"])){ 
       $sql  .= $virgula." o85_anousu = $this->o85_anousu ";
       $virgula = ",";
       if(trim($this->o85_anousu) == null ){ 
         $this->erro_sql = " Campo ano de execício nao Informado.";
         $this->erro_campo = "o85_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o85_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o85_valor"])){ 
       $sql  .= $virgula." o85_valor = $this->o85_valor ";
       $virgula = ",";
       if(trim($this->o85_valor) == null ){ 
         $this->erro_sql = " Campo valor nao Informado.";
         $this->erro_campo = "o85_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o85_codsup!=null){
       $sql .= " o85_codsup = $this->o85_codsup";
     }
     if($o85_codrec!=null){
       $sql .= " and  o85_codrec = $this->o85_codrec";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o85_codsup,$this->o85_codrec));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6087,'$this->o85_codsup','A')");
         $resac = db_query("insert into db_acountkey values($acount,6088,'$this->o85_codrec','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o85_codsup"]))
           $resac = db_query("insert into db_acount values($acount,978,6087,'".AddSlashes(pg_result($resaco,$conresaco,'o85_codsup'))."','$this->o85_codsup',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o85_codrec"]))
           $resac = db_query("insert into db_acount values($acount,978,6088,'".AddSlashes(pg_result($resaco,$conresaco,'o85_codrec'))."','$this->o85_codrec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o85_anousu"]))
           $resac = db_query("insert into db_acount values($acount,978,6089,'".AddSlashes(pg_result($resaco,$conresaco,'o85_anousu'))."','$this->o85_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o85_valor"]))
           $resac = db_query("insert into db_acount values($acount,978,6090,'".AddSlashes(pg_result($resaco,$conresaco,'o85_valor'))."','$this->o85_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o85_codsup."-".$this->o85_codrec;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o85_codsup."-".$this->o85_codrec;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o85_codsup."-".$this->o85_codrec;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o85_codsup=null,$o85_codrec=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o85_codsup,$o85_codrec));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6087,'$o85_codsup','E')");
         $resac = db_query("insert into db_acountkey values($acount,6088,'$o85_codrec','E')");
         $resac = db_query("insert into db_acount values($acount,978,6087,'','".AddSlashes(pg_result($resaco,$iresaco,'o85_codsup'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,978,6088,'','".AddSlashes(pg_result($resaco,$iresaco,'o85_codrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,978,6089,'','".AddSlashes(pg_result($resaco,$iresaco,'o85_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,978,6090,'','".AddSlashes(pg_result($resaco,$iresaco,'o85_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcsuplemrec
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o85_codsup != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o85_codsup = $o85_codsup ";
        }
        if($o85_codrec != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o85_codrec = $o85_codrec ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o85_codsup."-".$o85_codrec;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o85_codsup."-".$o85_codrec;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o85_codsup."-".$o85_codrec;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcsuplemrec";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $o85_codsup=null,$o85_codrec=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcsuplemrec ";
     $sql .= "      inner join orcreceita  on  orcreceita.o70_anousu = orcsuplemrec.o85_anousu and  orcreceita.o70_codrec = orcsuplemrec.o85_codrec";
     $sql .= "      inner join db_config  on  db_config.codigo = orcreceita.o70_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcreceita.o70_codigo";
     $sql .= "      inner join orcfontes  on  orcfontes.o57_codfon = orcreceita.o70_codfon and orcfontes.o57_anousu = orcreceita.o70_anousu";
     $sql .= "      inner join db_config  as a on   a.codigo = orcreceita.o70_instit";
     $sql .= "      inner join orctiporec  as b on   b.o15_codigo = orcreceita.o70_codigo";
     $sql .= "      inner join orcfontes  as c on   c.o57_codfon = orcreceita.o70_codfon and c.o57_anousu = orcreceita.o70_anousu ";
     $sql2 = "";
     if($dbwhere==""){
       if($o85_codsup!=null ){
         $sql2 .= " where orcsuplemrec.o85_codsup = $o85_codsup "; 
       } 
       if($o85_codrec!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcsuplemrec.o85_codrec = $o85_codrec "; 
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
   function sql_query_file ( $o85_codsup=null,$o85_codrec=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcsuplemrec ";
     $sql2 = "";
     if($dbwhere==""){
       if($o85_codsup!=null ){
         $sql2 .= " where orcsuplemrec.o85_codsup = $o85_codsup "; 
       } 
       if($o85_codrec!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcsuplemrec.o85_codrec = $o85_codrec "; 
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