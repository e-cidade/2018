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

//MODULO: pessoal
//CLASSE DA ENTIDADE historic
class cl_historic { 
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
   var $r25_anousu = 0; 
   var $r25_mesusu = 0; 
   var $r25_codigo = null; 
   var $r25_descr = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r25_anousu = int4 = Ano do Exercicio 
                 r25_mesusu = int4 = Mes do Exercicio 
                 r25_codigo = varchar(4) = Código 
                 r25_descr = varchar(30) = Descrição 
                 ";
   //funcao construtor da classe 
   function cl_historic() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("historic"); 
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
       $this->r25_anousu = ($this->r25_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r25_anousu"]:$this->r25_anousu);
       $this->r25_mesusu = ($this->r25_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r25_mesusu"]:$this->r25_mesusu);
       $this->r25_codigo = ($this->r25_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r25_codigo"]:$this->r25_codigo);
       $this->r25_descr = ($this->r25_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["r25_descr"]:$this->r25_descr);
     }else{
       $this->r25_anousu = ($this->r25_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r25_anousu"]:$this->r25_anousu);
       $this->r25_mesusu = ($this->r25_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r25_mesusu"]:$this->r25_mesusu);
       $this->r25_codigo = ($this->r25_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r25_codigo"]:$this->r25_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($r25_anousu,$r25_mesusu,$r25_codigo){ 
      $this->atualizacampos();
     if($this->r25_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "r25_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r25_anousu = $r25_anousu; 
       $this->r25_mesusu = $r25_mesusu; 
       $this->r25_codigo = $r25_codigo; 
     if(($this->r25_anousu == null) || ($this->r25_anousu == "") ){ 
       $this->erro_sql = " Campo r25_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r25_mesusu == null) || ($this->r25_mesusu == "") ){ 
       $this->erro_sql = " Campo r25_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r25_codigo == null) || ($this->r25_codigo == "") ){ 
       $this->erro_sql = " Campo r25_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into historic(
                                       r25_anousu 
                                      ,r25_mesusu 
                                      ,r25_codigo 
                                      ,r25_descr 
                       )
                values (
                                $this->r25_anousu 
                               ,$this->r25_mesusu 
                               ,'$this->r25_codigo' 
                               ,'$this->r25_descr' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Historico do funcionario ($this->r25_anousu."-".$this->r25_mesusu."-".$this->r25_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Historico do funcionario já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Historico do funcionario ($this->r25_anousu."-".$this->r25_mesusu."-".$this->r25_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r25_anousu."-".$this->r25_mesusu."-".$this->r25_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r25_anousu,$this->r25_mesusu,$this->r25_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4005,'$this->r25_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4006,'$this->r25_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4007,'$this->r25_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,560,4005,'','".AddSlashes(pg_result($resaco,0,'r25_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,560,4006,'','".AddSlashes(pg_result($resaco,0,'r25_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,560,4007,'','".AddSlashes(pg_result($resaco,0,'r25_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,560,4008,'','".AddSlashes(pg_result($resaco,0,'r25_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r25_anousu=null,$r25_mesusu=null,$r25_codigo=null) { 
      $this->atualizacampos();
     $sql = " update historic set ";
     $virgula = "";
     if(trim($this->r25_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r25_anousu"])){ 
       $sql  .= $virgula." r25_anousu = $this->r25_anousu ";
       $virgula = ",";
       if(trim($this->r25_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r25_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r25_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r25_mesusu"])){ 
       $sql  .= $virgula." r25_mesusu = $this->r25_mesusu ";
       $virgula = ",";
       if(trim($this->r25_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r25_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r25_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r25_codigo"])){ 
       $sql  .= $virgula." r25_codigo = '$this->r25_codigo' ";
       $virgula = ",";
       if(trim($this->r25_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "r25_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r25_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r25_descr"])){ 
       $sql  .= $virgula." r25_descr = '$this->r25_descr' ";
       $virgula = ",";
       if(trim($this->r25_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "r25_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r25_anousu!=null){
       $sql .= " r25_anousu = $this->r25_anousu";
     }
     if($r25_mesusu!=null){
       $sql .= " and  r25_mesusu = $this->r25_mesusu";
     }
     if($r25_codigo!=null){
       $sql .= " and  r25_codigo = '$this->r25_codigo'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r25_anousu,$this->r25_mesusu,$this->r25_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4005,'$this->r25_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4006,'$this->r25_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4007,'$this->r25_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r25_anousu"]))
           $resac = db_query("insert into db_acount values($acount,560,4005,'".AddSlashes(pg_result($resaco,$conresaco,'r25_anousu'))."','$this->r25_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r25_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,560,4006,'".AddSlashes(pg_result($resaco,$conresaco,'r25_mesusu'))."','$this->r25_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r25_codigo"]))
           $resac = db_query("insert into db_acount values($acount,560,4007,'".AddSlashes(pg_result($resaco,$conresaco,'r25_codigo'))."','$this->r25_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r25_descr"]))
           $resac = db_query("insert into db_acount values($acount,560,4008,'".AddSlashes(pg_result($resaco,$conresaco,'r25_descr'))."','$this->r25_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Historico do funcionario nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r25_anousu."-".$this->r25_mesusu."-".$this->r25_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Historico do funcionario nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r25_anousu."-".$this->r25_mesusu."-".$this->r25_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r25_anousu."-".$this->r25_mesusu."-".$this->r25_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r25_anousu=null,$r25_mesusu=null,$r25_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r25_anousu,$r25_mesusu,$r25_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4005,'$r25_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4006,'$r25_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4007,'$r25_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,560,4005,'','".AddSlashes(pg_result($resaco,$iresaco,'r25_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,560,4006,'','".AddSlashes(pg_result($resaco,$iresaco,'r25_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,560,4007,'','".AddSlashes(pg_result($resaco,$iresaco,'r25_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,560,4008,'','".AddSlashes(pg_result($resaco,$iresaco,'r25_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from historic
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r25_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r25_anousu = $r25_anousu ";
        }
        if($r25_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r25_mesusu = $r25_mesusu ";
        }
        if($r25_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r25_codigo = '$r25_codigo' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Historico do funcionario nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r25_anousu."-".$r25_mesusu."-".$r25_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Historico do funcionario nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r25_anousu."-".$r25_mesusu."-".$r25_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r25_anousu."-".$r25_mesusu."-".$r25_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:historic";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function atualiza_incluir (){
  	 $this->incluir($this->r25_anousu,$this->r25_mesusu,$this->r25_codigo);
   }
   function sql_query ( $r25_anousu=null,$r25_mesusu=null,$r25_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from historic ";
     $sql2 = "";
     if($dbwhere==""){
       if($r25_anousu!=null ){
         $sql2 .= " where historic.r25_anousu = $r25_anousu "; 
       } 
       if($r25_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " historic.r25_mesusu = $r25_mesusu "; 
       } 
       if($r25_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " historic.r25_codigo = '$r25_codigo' "; 
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
   function sql_query_file ( $r25_anousu=null,$r25_mesusu=null,$r25_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from historic ";
     $sql2 = "";
     if($dbwhere==""){
       if($r25_anousu!=null ){
         $sql2 .= " where historic.r25_anousu = $r25_anousu "; 
       } 
       if($r25_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " historic.r25_mesusu = $r25_mesusu "; 
       } 
       if($r25_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " historic.r25_codigo = '$r25_codigo' "; 
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